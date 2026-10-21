<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\R21WorkshopCourseFile;
use App\Models\R21WorkshopStudentAllocation;
use App\Models\R21WorkshopWorkEvaluation;
use App\Models\R21WorkshopSectionEvaluation;
use App\Models\R21WorkshopAttendanceEvaluation;
use App\Models\R21WorkshopCiaSummary;
use App\Models\ConsolidatedCiaApproval;
use App\Services\AttainmentService;

class R21VirtualClassroomWorkshopController extends Controller
{
    /**
     * Standard 6 Sections for Mechanical Workshop in Revision 2021
     */
    public const WORKSHOP_SECTIONS = [
        'Machine shop',
        'Fitting',
        'Smithy',
        'Welding',
        'Sheet metal',
        'Foundry'
    ];

    /**
     * Display the Mechanical Workshop Virtual Classroom Dashboard
     * Total CIA: 75 Marks | Total ESE: 50 Marks (Total = 125 Marks)
     * - Continuous Work Evaluation: 37.5 Marks (2 works per section * 3 sections = 6 works /20 -> scaled to 37.5)
     * - Open-Ended Record: 7.5 Marks (Rough 10 + Fair 10 per section = /20 across 3 sections -> scaled to 7.5)
     * - Trade Tests Average: 15.0 Marks (3 tests, each /15 -> average /15)
     * - Attendance: 15.0 Marks (from official TEAMS upload / slab)
     */
    public function show($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        // Fetch classroom details
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or create Course File
        $defaultBatchAllocation = [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];

        $courseFile = R21WorkshopCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program'           => $classroom->department ?? $classroom->branch ?? 'Mechanical Engineering',
                'course_title'      => $batchSubject->subject_name,
                'course_code'       => $batchSubject->subject_code,
                'semester'          => $classroom->current_semester ?? $batchSubject->semester ?? 3,
                'type_of_course'    => 'Mechanical Workshop',
                'teaching_scheme'   => '0:0:3:0',
                'contact_hours'     => 45,
                'credits'           => 1.5,
                'cia_marks'         => 75,
                'ese_marks'         => 50,
                'sections_config'   => self::WORKSHOP_SECTIONS,
                'batch_allocation'  => $defaultBatchAllocation,
            ]
        );

        $sectionsConfig = $courseFile->sections_config ?: self::WORKSHOP_SECTIONS;
        $batchAllocation = $courseFile->batch_allocation ?: $defaultBatchAllocation;

        // Fetch student allocations
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Auto-initialize allocations if not present
        if ($allocations->isEmpty() && $students->isNotEmpty()) {
            $halfCount = (int)ceil($students->count() / 2);
            $cutoff = $courseFile->batch_split_cutoff;

            foreach ($students as $idx => $st) {
                if ($cutoff !== null) {
                    $batchNo = ((int)$st->roll_no <= (int)$cutoff) ? '1' : '2';
                } else {
                    $batchNo = ($idx < $halfCount) ? '1' : '2';
                }

                $assignedSecs = $batchAllocation[$batchNo] ?? ($batchNo === '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry']);

                $alloc = R21WorkshopStudentAllocation::create([
                    'batch_subject_id'  => $subjectId,
                    'reg_no'            => $st->reg_no,
                    'batch_no'          => $batchNo,
                    'assigned_sections' => $assignedSecs,
                ]);
                $allocations->put($st->reg_no, $alloc);
            }
        }

        // Fetch Work Evaluations (group by reg_no -> section_name)
        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no')
            ->map(fn($items) => $items->keyBy('section_name'));

        // Fetch Section Evaluations (Rough Record, Fair Record, Test)
        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no')
            ->map(fn($items) => $items->keyBy('section_name'));

        // Fetch Attendance Evaluations
        $attEvals = R21WorkshopAttendanceEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch Official / TEAMS attendances
        $subjectOfficialAttendance = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('subject_official_attendances')) {
            $subjectOfficialAttendance = DB::table('subject_official_attendances')
                ->where('batch_subject_id', $subjectId)
                ->get()
                ->keyBy('reg_no');
        }

        // Fetch CIA Summaries & Overrides
        $ciaSummaries = R21WorkshopCiaSummary::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Check if marks are locked by HOD
        $isLocked = false;
        if (class_exists(ConsolidatedCiaApproval::class)) {
            $isLocked = ConsolidatedCiaApproval::isLockedForSubject($subjectId);
        }

        // Compute CIA data per student
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);

        $role = Session::get('userRole');
        $backUrl = '/dashboard/lecturer';
        if ($role === 'Demonstrator') {
            $backUrl = '/dashboard/demonstrator';
        } elseif ($role === 'HOD') {
            $backUrl = '/dashboard/hod';
        } elseif ($role === 'Principal') {
            $backUrl = '/dashboard/principal';
        }

        // Compute Attainment data
        $attainment = $this->computeWorkshopAttainment($subjectId, $students, $calculatedScores, $courseFile);

        return view('r21_workshop.virtual_classroom_workshop', compact(
            'batchSubject',
            'classroom',
            'students',
            'courseFile',
            'sectionsConfig',
            'batchAllocation',
            'allocations',
            'workEvals',
            'sectionEvals',
            'attEvals',
            'ciaSummaries',
            'calculatedScores',
            'attainment',
            'isLocked',
            'backUrl'
        ));
    }

    /**
     * Helper to calculate continuous CIA breakdown for students
     */
    public function calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation = null)
    {
        if ($batchAllocation === null) {
            $batchAllocation = [
                '1' => ['Fitting', 'Smithy', 'Welding'],
                '2' => ['Machine shop', 'Sheet metal', 'Foundry']
            ];
        }

        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no')
            ->map(fn($items) => $items->keyBy('section_name'));

        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no')
            ->map(fn($items) => $items->keyBy('section_name'));

        $attEvals = R21WorkshopAttendanceEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        $subjectOfficialAttendance = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('subject_official_attendances')) {
            $subjectOfficialAttendance = DB::table('subject_official_attendances')
                ->where('batch_subject_id', $subjectId)
                ->get()
                ->keyBy('reg_no');
        }

        $ciaSummaries = R21WorkshopCiaSummary::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        $calculatedScores = [];
        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $alloc = $allocations->get($rNo);
            $batchNo = $alloc->batch_no ?? '1';
            $assignedSections = $alloc->assigned_sections ?? ($batchAllocation[$batchNo] ?? ['Fitting', 'Smithy', 'Welding']);

            $stWorks = $workEvals->get($rNo, collect());
            $stSecs = $sectionEvals->get($rNo, collect());
            $stSummary = $ciaSummaries->get($rNo);

            // 1. Work Evaluation:
            // In Machine shop: 4 works (work_1_mark, work_2_mark, work_3_mark, work_4_mark) -> avg of 4 works
            // In Sheet metal: Sheet metal works (work_1, work_2) + Aluminium fabrication work (alum_work_mark) -> avg
            // In other sections: 2 works (work_1, work_2) -> avg
            $workScores = [];
            $sectionWorkDetails = [];
            foreach ($assignedSections as $secName) {
                $wRec = $stWorks->get($secName);
                if ($wRec) {
                    if ($secName === 'Machine shop') {
                        $m1 = (float)$wRec->work_1_mark;
                        $m2 = (float)$wRec->work_2_mark;
                        $m3 = (float)($wRec->work_3_mark ?? 0);
                        $m4 = (float)($wRec->work_4_mark ?? 0);
                        $secAvg = round(($m1 + $m2 + $m3 + $m4) / 4.0, 2);
                        $sectionWorkDetails[$secName] = ['w1' => $m1, 'w2' => $m2, 'w3' => $m3, 'w4' => $m4, 'avg' => $secAvg];
                        $workScores[] = $secAvg;
                    } elseif ($secName === 'Sheet metal') {
                        $s1 = (float)$wRec->work_1_mark;
                        $s2 = (float)$wRec->work_2_mark;
                        $sa = (float)($wRec->alum_work_mark ?? 0);
                        $secAvg = ($sa > 0) ? round(($s1 + $s2 + $sa) / 3.0, 2) : round(($s1 + $s2) / 2.0, 2);
                        $sectionWorkDetails[$secName] = ['w1' => $s1, 'w2' => $s2, 'alum' => $sa, 'avg' => $secAvg];
                        $workScores[] = $secAvg;
                    } else {
                        $w1 = (float)$wRec->work_1_mark;
                        $w2 = (float)$wRec->work_2_mark;
                        $secAvg = round(($w1 + $w2) / 2.0, 2);
                        $sectionWorkDetails[$secName] = ['w1' => $w1, 'w2' => $w2, 'avg' => $secAvg];
                        $workScores[] = $secAvg;
                    }
                } else {
                    $sectionWorkDetails[$secName] = ['w1' => 0, 'w2' => 0, 'avg' => 0];
                    $workScores[] = 0.0;
                }
            }
            $sumWorks = array_sum($workScores);
            $avgWork20 = count($workScores) > 0 ? round($sumWorks / count($workScores), 2) : 0.0;
            // Formula: (Avg / 20) * 37.5 = (Sum / 120) * 37.5
            $rawWork375 = count($workScores) > 0 ? round(($avgWork20 / 20.0) * 37.5, 2) : 0.0;
            $finalWork375 = ($stSummary && $stSummary->override_work_375 !== null) ? (float)$stSummary->override_work_375 : $rawWork375;

            // 2. Open-Ended Record:
            // Rough Record (10M) + Fair Record (10M) = 20M per section across 3 sections
            // For Sheet metal: includes Aluminium Fabrication Rough (10M) + Fair (10M)
            $recordScores = [];
            $sectionRecordDetails = [];
            foreach ($assignedSections as $secName) {
                $sRec = $stSecs->get($secName);
                if ($sRec) {
                    $rough = (float)$sRec->rough_record_mark;
                    $fair  = (float)$sRec->fair_record_mark;
                    $smTot = $rough + $fair;

                    if ($secName === 'Sheet metal' && ($sRec->alum_rough_record_mark !== null || $sRec->alum_fair_record_mark !== null)) {
                        $alumRough = (float)($sRec->alum_rough_record_mark ?? 0);
                        $alumFair  = (float)($sRec->alum_fair_record_mark ?? 0);
                        $alumTot   = $alumRough + $alumFair;
                        $secRecAvg = round(($smTot + $alumTot) / 2.0, 2);
                        $sectionRecordDetails[$secName] = [
                            'rough' => $rough, 'fair' => $fair, 'sm_total' => $smTot,
                            'alum_rough' => $alumRough, 'alum_fair' => $alumFair, 'alum_total' => $alumTot,
                            'record_total' => $secRecAvg
                        ];
                        $recordScores[] = $secRecAvg;
                    } else {
                        $sectionRecordDetails[$secName] = [
                            'rough' => $rough, 'fair' => $fair, 'record_total' => $smTot
                        ];
                        $recordScores[] = $smTot;
                    }
                } else {
                    $sectionRecordDetails[$secName] = ['rough' => 0, 'fair' => 0, 'record_total' => 0];
                    $recordScores[] = 0.0;
                }
            }
            $sumRecords = array_sum($recordScores);
            $avgRecord20 = count($recordScores) > 0 ? round($sumRecords / count($recordScores), 2) : 0.0;
            // Formula: (Avg / 20) * 7.5
            $rawOpenEnded75 = count($recordScores) > 0 ? round(($avgRecord20 / 20.0) * 7.5, 2) : 0.0;
            $finalOpenEnded75 = ($stSummary && $stSummary->override_open_ended_75 !== null) ? (float)$stSummary->override_open_ended_75 : $rawOpenEnded75;

            // 3. Trade Tests: 1 test per section -> average of 3 tests out of 15
            $testScores = [];
            foreach ($assignedSections as $secName) {
                $sRec = $stSecs->get($secName);
                if ($sRec && !$sRec->is_test_absent) {
                    $testScores[] = (float)$sRec->test_mark;
                } else {
                    $testScores[] = 0.0;
                }
            }
            $sumTests = array_sum($testScores);
            $rawTestAvg15 = count($testScores) > 0 ? round($sumTests / count($testScores), 2) : 0.0;
            $finalTestAvg15 = ($stSummary && $stSummary->override_test_15 !== null) ? (float)$stSummary->override_test_15 : $rawTestAvg15;

            // 4. Attendance: out of 15.0 marks
            $attRec = $attEvals->get($rNo);
            $offRec = $subjectOfficialAttendance->get($rNo) ?: (!empty($st->sbte_reg_no) ? $subjectOfficialAttendance->get($st->sbte_reg_no) : null);

            if ($attRec && $attRec->override_mark !== null) {
                $finalAttPct = (float)$attRec->final_percentage;
                $finalAtt15 = (float)$attRec->override_mark;
            } elseif ($offRec && ($offRec->override_percentage !== null || $offRec->teams_percentage !== null || $offRec->final_percentage !== null)) {
                $finalAttPct = (float)$offRec->final_percentage;
                $finalAtt15 = (float)($offRec->final_mark ?? AttainmentService::calculateR21AttendanceMark($finalAttPct, 15.0));
            } elseif ($attRec && $attRec->final_attendance_mark > 0) {
                $finalAttPct = (float)$attRec->final_percentage;
                $finalAtt15 = (float)$attRec->final_attendance_mark;
            } else {
                $finalAttPct = 85.0; // Default benchmark
                $finalAtt15 = AttainmentService::calculateR21AttendanceMark($finalAttPct, 15.0);
            }

            // Total CIA (out of 75 Marks)
            $calculatedCia75 = (int)round($finalWork375 + $finalOpenEnded75 + $finalTestAvg15 + $finalAtt15);
            $totalCia75 = ($stSummary && $stSummary->override_total_cia !== null) ? (int)$stSummary->override_total_cia : $calculatedCia75;

            $isPassed = $totalCia75 >= 30; // 40% of 75 is 30 marks minimum

            $calculatedScores[$rNo] = [
                'batch_no'              => $batchNo,
                'assigned_sections'     => $assignedSections,
                'work_scores'           => $workScores,
                'section_work_details'  => $sectionWorkDetails,
                'avg_work_20'           => $avgWork20,
                'work_score_375'        => $finalWork375,
                'is_work_overridden'    => ($stSummary && $stSummary->override_work_375 !== null),
                'record_scores'         => $recordScores,
                'section_record_details'=> $sectionRecordDetails,
                'avg_record_20'         => $avgRecord20,
                'open_ended_75'         => $finalOpenEnded75,
                'is_open_overridden'    => ($stSummary && $stSummary->override_open_ended_75 !== null),
                'test_scores'           => $testScores,
                'test_avg_15'           => $finalTestAvg15,
                'is_test_overridden'    => ($stSummary && $stSummary->override_test_15 !== null),
                'att_pct'               => $finalAttPct,
                'att_mark_15'           => $finalAtt15,
                'is_att_overridden'     => ($attRec && $attRec->override_mark !== null),
                'total_cia_75'          => $totalCia75,
                'is_cia_overridden'     => ($stSummary && $stSummary->override_total_cia !== null),
                'is_passed'             => $isPassed,
            ];
        }

        return $calculatedScores;
    }

    /**
     * Save Batch Split & Section Allocation Setup
     */
    public function saveBatchSetup(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $batch1Sections = $request->input('batch1_sections', []);
        $batch2Sections = $request->input('batch2_sections', []);
        $cutoff = $request->input('batch_split_cutoff');
        $studentBatches = $request->input('student_batches', []); // [reg_no => batch_no]

        if (count($batch1Sections) !== 3 || count($batch2Sections) !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Please select exactly 3 sections for Batch 1 and 3 sections for Batch 2.'
            ], 422);
        }

        $allocationConfig = [
            '1' => array_values($batch1Sections),
            '2' => array_values($batch2Sections)
        ];

        $courseFile->batch_allocation = $allocationConfig;
        $courseFile->batch_split_cutoff = $cutoff ? (int)$cutoff : null;
        $courseFile->save();

        // Update student allocations
        foreach ($studentBatches as $regNo => $bNo) {
            $assigned = $allocationConfig[$bNo] ?? ($bNo === '1' ? $batch1Sections : $batch2Sections);
            R21WorkshopStudentAllocation::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                ['batch_no' => $bNo, 'assigned_sections' => $assigned]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Workshop batches and section allocations saved successfully.'
        ]);
    }

    /**
     * Save Continuous Work Evaluation (Work 1 to 4 / Aluminium Fab, each out of 20 marks)
     */
    public function saveWorkMarks(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $sectionName = $request->input('section_name');
        $evaluations = $request->input('evaluations', []);

        if (!$sectionName || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid evaluation payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $w1 = min(20.0, max(0.0, floatval($eval['work_1_mark'] ?? 0)));
            $w2 = min(20.0, max(0.0, floatval($eval['work_2_mark'] ?? 0)));
            $w3 = isset($eval['work_3_mark']) && $eval['work_3_mark'] !== '' ? min(20.0, max(0.0, floatval($eval['work_3_mark']))) : 0.0;
            $w4 = isset($eval['work_4_mark']) && $eval['work_4_mark'] !== '' ? min(20.0, max(0.0, floatval($eval['work_4_mark']))) : 0.0;
            $alum = isset($eval['alum_work_mark']) && $eval['alum_work_mark'] !== '' ? min(20.0, max(0.0, floatval($eval['alum_work_mark']))) : 0.0;

            if ($sectionName === 'Machine shop') {
                $avg = round(($w1 + $w2 + $w3 + $w4) / 4.0, 2);
            } elseif ($sectionName === 'Sheet metal') {
                $avg = ($alum > 0 || (isset($eval['alum_work_mark']) && $eval['alum_work_mark'] !== '')) ? round(($w1 + $w2 + $alum) / 3.0, 2) : round(($w1 + $w2) / 2.0, 2);
            } else {
                $avg = round(($w1 + $w2) / 2.0, 2);
            }

            R21WorkshopWorkEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                    'section_name'     => $sectionName,
                ],
                [
                    'work_1_mark'      => $w1,
                    'work_2_mark'      => $w2,
                    'work_3_mark'      => $w3,
                    'work_4_mark'      => $w4,
                    'alum_work_mark'   => $alum,
                    'work_avg_20'      => $avg,
                    'remarks'          => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Works for section '{$sectionName}' saved successfully."
        ]);
    }

    /**
     * Save Section Evaluations: Rough Record (10M), Fair Record (10M), Aluminium Fab Rough & Fair -> Total 20M
     */
    public function saveRecordMarks(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $sectionName = $request->input('section_name');
        $evaluations = $request->input('evaluations', []);

        if (!$sectionName || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid record payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $rough = min(10.0, max(0.0, floatval($eval['rough_record_mark'] ?? 0)));
            $fair  = min(10.0, max(0.0, floatval($eval['fair_record_mark'] ?? 0)));
            $smTotal = min(20.0, max(0.0, $rough + $fair));

            $alumRough = isset($eval['alum_rough_record_mark']) && $eval['alum_rough_record_mark'] !== '' ? min(10.0, max(0.0, floatval($eval['alum_rough_record_mark']))) : null;
            $alumFair = isset($eval['alum_fair_record_mark']) && $eval['alum_fair_record_mark'] !== '' ? min(10.0, max(0.0, floatval($eval['alum_fair_record_mark']))) : null;
            $alumTotal = ($alumRough !== null || $alumFair !== null) ? min(20.0, max(0.0, ($alumRough ?? 0) + ($alumFair ?? 0))) : null;

            if ($sectionName === 'Sheet metal' && $alumTotal !== null) {
                $total = round(($smTotal + $alumTotal) / 2.0, 2);
            } else {
                $total = $smTotal;
            }

            R21WorkshopSectionEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                    'section_name'     => $sectionName,
                ],
                [
                    'rough_record_mark'      => $rough,
                    'fair_record_mark'       => $fair,
                    'record_total_20'        => $total,
                    'alum_rough_record_mark' => $alumRough,
                    'alum_fair_record_mark'  => $alumFair,
                    'alum_record_total_20'   => $alumTotal,
                    'remarks'                => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Record marks for section '{$sectionName}' saved successfully."
        ]);
    }

    /**
     * Save Section Trade Test Marks (15M Max)
     */
    public function saveTestMarks(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $sectionName = $request->input('section_name');
        $evaluations = $request->input('evaluations', []);

        if (!$sectionName || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid test payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $isAbsent = !empty($eval['is_absent']);
            $testMark = $isAbsent ? 0.0 : min(15.0, max(0.0, floatval($eval['test_mark'] ?? 0)));

            R21WorkshopSectionEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                    'section_name'     => $sectionName,
                ],
                [
                    'test_mark'      => $testMark,
                    'is_test_absent' => $isAbsent,
                    'remarks'        => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Test marks for section '{$sectionName}' saved successfully."
        ]);
    }

    /**
     * Save Fast Consolidated CIA Overrides
     */
    public function saveFastCia(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $studentsData = $request->input('students', []);
        if (!is_array($studentsData)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        foreach ($studentsData as $item) {
            $regNo = $item['reg_no'] ?? null;
            if (!$regNo) continue;

            $work375 = isset($item['work_375']) && $item['work_375'] !== '' ? floatval($item['work_375']) : null;
            $open75  = isset($item['open_75']) && $item['open_75'] !== '' ? floatval($item['open_75']) : null;
            $test15  = isset($item['test_15']) && $item['test_15'] !== '' ? floatval($item['test_15']) : null;
            $att15   = isset($item['att_15']) && $item['att_15'] !== '' ? floatval($item['att_15']) : null;

            if ($work375 !== null) $work375 = min(37.5, max(0.0, $work375));
            if ($open75  !== null) $open75  = min(7.5, max(0.0, $open75));
            if ($test15  !== null) $test15  = min(15.0, max(0.0, $test15));
            if ($att15   !== null) $att15   = min(15.0, max(0.0, $att15));

            $totalCia = null;
            if ($work375 !== null || $open75 !== null || $test15 !== null || $att15 !== null) {
                $totalCia = (int)round(($work375 ?? 0) + ($open75 ?? 0) + ($test15 ?? 0) + ($att15 ?? 0));
            }

            R21WorkshopCiaSummary::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                ],
                [
                    'override_work_375'      => $work375,
                    'override_open_ended_75' => $open75,
                    'override_test_15'       => $test15,
                    'override_attendance_15' => $att15,
                    'override_total_cia'     => $totalCia,
                ]
            );

            // Also synchronize attendance evaluation override if att15 is present
            if ($att15 !== null) {
                R21WorkshopAttendanceEvaluation::updateOrCreate(
                    ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                    ['override_mark' => $att15, 'final_attendance_mark' => $att15]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Consolidated CIA marks saved successfully.'
        ]);
    }

    /**
     * Save Attendance Overrides
     */
    public function saveAttendance(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $evaluations = $request->input('evaluations', []);
        if (!is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid attendance payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $overridePct = isset($eval['override_percentage']) && $eval['override_percentage'] !== '' ? floatval($eval['override_percentage']) : null;
            $overrideMark = isset($eval['override_mark']) && $eval['override_mark'] !== '' ? floatval($eval['override_mark']) : null;

            if ($overridePct !== null) $overridePct = min(100.0, max(0.0, $overridePct));
            if ($overrideMark !== null) $overrideMark = min(15.0, max(0.0, $overrideMark));

            $finalPct = $overridePct ?? floatval($eval['attendance_percentage'] ?? 85.0);
            $finalMark = $overrideMark ?? AttainmentService::calculateR21AttendanceMark($finalPct, 15.0);

            R21WorkshopAttendanceEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                ],
                [
                    'override_percentage'   => $overridePct,
                    'final_percentage'      => $finalPct,
                    'override_mark'         => $overrideMark,
                    'final_attendance_mark' => $finalMark,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marks saved successfully.'
        ]);
    }

    /**
     * Upload Syllabus PDF
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:15360'
        ]);

        $file = $request->file('syllabus_file');
        $filename = 'r21_workshop_syllabus_' . $subjectId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('syllabi', $filename, 'public');

        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->syllabus_pdf_path = '/storage/' . $path;
        $courseFile->save();

        return response()->json([
            'success' => true,
            'message' => 'Syllabus PDF uploaded successfully.',
            'path'    => $courseFile->syllabus_pdf_path
        ]);
    }

    /**
     * Print Consolidated CIA Register (A4 Landscape)
     */
    public function printConsolidatedCia($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));
        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));
        $attEvals = R21WorkshopAttendanceEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $ciaSummaries = R21WorkshopCiaSummary::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        return view('r21_workshop.cia_consolidated_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'students',
            'allocations',
            'workEvals',
            'sectionEvals',
            'attEvals',
            'ciaSummaries'
        ));
    }

    /**
     * Print Continuous Work Evaluation Register (A4 Landscape)
     */
    public function printWorkRegister($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));
        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));

        return view('r21_workshop.work_evaluation_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'students',
            'allocations',
            'workEvals',
            'sectionEvals'
        ));
    }

    /**
     * Show Mobile Virtual Workshop Evaluation Dashboard (R2021)
     */
    public function showMobile(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role === 'Student') {
            return redirect('/');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        $defaultBatchAllocation = [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];

        $courseFile = R21WorkshopCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program'          => $classroom->department ?? $classroom->branch ?? 'Mechanical Engineering',
                'course_title'     => $batchSubject->subject_name,
                'course_code'      => $batchSubject->subject_code,
                'semester'         => $classroom->current_semester ?? $batchSubject->semester ?? 3,
                'type_of_course'   => 'Mechanical Workshop',
                'teaching_scheme'  => '0:0:3:0',
                'contact_hours'    => 45,
                'credits'          => 1.5,
                'cia_marks'        => 75,
                'ese_marks'        => 50,
                'sections_config'  => self::WORKSHOP_SECTIONS,
                'batch_allocation' => $defaultBatchAllocation,
            ]
        );

        $sections = $courseFile->sections_config ?: self::WORKSHOP_SECTIONS;
        $batchAllocation = $courseFile->batch_allocation ?: $defaultBatchAllocation;

        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Auto-initialize allocations if not present
        if ($allocations->isEmpty() && $students->isNotEmpty()) {
            $halfCount = (int)ceil($students->count() / 2);
            $cutoff = $courseFile->batch_split_cutoff;

            foreach ($students as $idx => $st) {
                if ($cutoff !== null) {
                    $batchNo = ((int)$st->roll_no <= (int)$cutoff) ? '1' : '2';
                } else {
                    $batchNo = ($idx < $halfCount) ? '1' : '2';
                }

                $assignedSecs = $batchAllocation[$batchNo] ?? ($batchNo === '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry']);

                $alloc = R21WorkshopStudentAllocation::create([
                    'batch_subject_id'  => $subjectId,
                    'reg_no'            => $st->reg_no,
                    'batch_no'          => $batchNo,
                    'assigned_sections' => $assignedSecs,
                ]);
                $allocations->put($st->reg_no, $alloc);
            }
        }

        // Filter students per section
        $sectionStudents = [];
        foreach ($sections as $sec) {
            $sectionStudents[$sec] = $students->filter(function($st) use ($sec, $allocations) {
                $al = $allocations->get($st->reg_no);
                return $al && in_array($sec, (array)($al->assigned_sections ?? []));
            })->values();
        }

        // Fetch Work Evals & Section Evals
        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('section_name')
            ->map(fn($g) => $g->keyBy('reg_no'));

        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('section_name')
            ->map(fn($g) => $g->keyBy('reg_no'));

        // Compute Full CIA Summary for Mobile Status Report
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);

        return view('r21_workshop.mobile_workshop', compact(
            'batchSubject',
            'classroom',
            'courseFile',
            'sections',
            'sectionStudents',
            'students',
            'allocations',
            'workEvals',
            'sectionEvals',
            'calculatedScores'
        ));
    }

    /**
     * Mobile Fast Save for a specific section & mode
     */
    public function saveMobileSectionData(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $sectionName = $request->input('section_name');
        $mode        = $request->input('mode'); // 'works', 'records', 'tests'
        $evaluations = $request->input('evaluations', []);

        if (!$sectionName || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        if ($mode === 'works') {
            foreach ($evaluations as $item) {
                $regNo = $item['reg_no'] ?? null;
                if (!$regNo) continue;

                $w1 = min(20.0, max(0.0, floatval($item['work_1'] ?? 0)));
                $w2 = min(20.0, max(0.0, floatval($item['work_2'] ?? 0)));
                $w3 = isset($item['work_3']) && $item['work_3'] !== '' ? min(20.0, max(0.0, floatval($item['work_3']))) : 0.0;
                $w4 = isset($item['work_4']) && $item['work_4'] !== '' ? min(20.0, max(0.0, floatval($item['work_4']))) : 0.0;
                $wAlum = isset($item['alum_work']) && $item['alum_work'] !== '' ? min(20.0, max(0.0, floatval($item['alum_work']))) : 0.0;

                if ($sectionName === 'Machine shop') {
                    $avg = round(($w1 + $w2 + $w3 + $w4) / 4.0, 2);
                } elseif ($sectionName === 'Sheet metal') {
                    $avg = ($wAlum > 0 || (isset($item['alum_work']) && $item['alum_work'] !== '')) ? round(($w1 + $w2 + $wAlum) / 3.0, 2) : round(($w1 + $w2) / 2.0, 2);
                } else {
                    $avg = round(($w1 + $w2) / 2.0, 2);
                }

                R21WorkshopWorkEvaluation::updateOrCreate(
                    ['batch_subject_id' => $subjectId, 'reg_no' => $regNo, 'section_name' => $sectionName],
                    [
                        'work_1_mark'    => $w1,
                        'work_2_mark'    => $w2,
                        'work_3_mark'    => $w3,
                        'work_4_mark'    => $w4,
                        'alum_work_mark' => $wAlum,
                        'work_avg_20'    => $avg,
                    ]
                );
            }
        } elseif ($mode === 'records') {
            foreach ($evaluations as $item) {
                $regNo = $item['reg_no'] ?? null;
                if (!$regNo) continue;

                $rough = min(10.0, max(0.0, floatval($item['rough'] ?? 0)));
                $fair  = min(10.0, max(0.0, floatval($item['fair'] ?? 0)));
                $smTotal = min(20.0, max(0.0, $rough + $fair));

                $alumRough = isset($item['alum_rough']) && $item['alum_rough'] !== '' ? min(10.0, max(0.0, floatval($item['alum_rough']))) : null;
                $alumFair  = isset($item['alum_fair']) && $item['alum_fair'] !== '' ? min(10.0, max(0.0, floatval($item['alum_fair']))) : null;
                $alumTotal = ($alumRough !== null || $alumFair !== null) ? min(20.0, max(0.0, ($alumRough ?? 0) + ($alumFair ?? 0))) : null;

                if ($sectionName === 'Sheet metal' && $alumTotal !== null) {
                    $total = round(($smTotal + $alumTotal) / 2.0, 2);
                } else {
                    $total = $smTotal;
                }

                R21WorkshopSectionEvaluation::updateOrCreate(
                    ['batch_subject_id' => $subjectId, 'reg_no' => $regNo, 'section_name' => $sectionName],
                    [
                        'rough_record_mark'      => $rough,
                        'fair_record_mark'       => $fair,
                        'record_total_20'        => $total,
                        'alum_rough_record_mark' => $alumRough,
                        'alum_fair_record_mark'  => $alumFair,
                        'alum_record_total_20'   => $alumTotal,
                    ]
                );
            }
        } elseif ($mode === 'tests') {
            foreach ($evaluations as $item) {
                $regNo = $item['reg_no'] ?? null;
                if (!$regNo) continue;

                $isAbsent = !empty($item['is_absent']);
                $test = $isAbsent ? 0.0 : min(15.0, max(0.0, floatval($item['test_mark'] ?? 0)));

                R21WorkshopSectionEvaluation::updateOrCreate(
                    ['batch_subject_id' => $subjectId, 'reg_no' => $regNo, 'section_name' => $sectionName],
                    ['test_mark' => $test, 'is_test_absent' => $isAbsent]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully updated {$mode} for {$sectionName}."
        ]);
    }

    /**
     * Get Mobile Attendance for a specific date and batch/section
     */
    public function getMobileAttendance(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $date = $request->input('date', now()->toDateString());
        $batchName = $request->input('batch_name', 'Full');
        $sectionName = $request->input('section_name');

        $records = [];
        $hasExisting = false;

        // 1. Check in student_attendance
        if (\Illuminate\Support\Facades\Schema::hasTable('student_attendance')) {
            $attRows = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('date', $date)
                ->get();

            if ($attRows->isNotEmpty()) {
                $hasExisting = true;
                foreach ($attRows as $row) {
                    $records[$row->reg_no] = in_array($row->status, ['Present', 'Late']);
                }
            }
        }

        // 2. Check in class_logs_attendance if student_attendance was empty
        if (!$hasExisting && \Illuminate\Support\Facades\Schema::hasTable('class_logs_attendance')) {
            $logQuery = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $subjectId)
                ->where('date', $date);

            $cLog = $logQuery->orderBy('id', 'desc')->first();
            if ($cLog) {
                $hasExisting = true;
                $pList = json_decode($cLog->present_students ?? '[]', true) ?: [];
                $aList = json_decode($cLog->absent_students ?? '[]', true) ?: [];
                foreach ($pList as $rNo) {
                    $records[$rNo] = true;
                }
                foreach ($aList as $rNo) {
                    $records[$rNo] = false;
                }
            }
        }

        return response()->json([
            'success'      => true,
            'date'         => $date,
            'batch_name'   => $batchName,
            'section_name' => $sectionName,
            'has_existing' => $hasExisting,
            'records'      => $records
        ]);
    }

    /**
     * Mobile Section Attendance Roll Call (Supports past dates, Full Batch, B1, and B2)
     */
    public function saveMobileSectionAttendance(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $sectionName = $request->input('section_name');
        $batchName   = $request->input('batch_name', 'Full'); // 'Full', '1', '2'
        $records     = $request->input('records', []); // [{reg_no, is_present}]
        $date        = $request->input('date', now()->toDateString());
        $period      = (int)$request->input('period', 1);

        if (!is_array($records) || empty($records)) {
            return response()->json(['success' => false, 'message' => 'No attendance records provided.'], 400);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $userId = Session::get('userId');

        $presentList = [];
        $absentList  = [];
        $subBatchLabel = ($batchName && $batchName !== 'Full') ? "Batch {$batchName}" : ($sectionName ?: 'Whole Class');

        foreach ($records as $item) {
            $regNo = $item['reg_no'] ?? null;
            if (!$regNo) continue;

            $isPres = !empty($item['is_present']);
            $status = $isPres ? 'Present' : 'Absent';

            if ($isPres) {
                $presentList[] = $regNo;
            } else {
                $absentList[] = $regNo;
            }

            // Update student_attendance
            if (\Illuminate\Support\Facades\Schema::hasTable('student_attendance')) {
                DB::table('student_attendance')->updateOrInsert(
                    [
                        'subject_code' => $batchSubject->subject_code,
                        'date'         => $date,
                        'reg_no'       => $regNo,
                    ],
                    [
                        'status'       => $status,
                        'sub_batch'    => $batchName !== 'Full' ? $batchName : ($sectionName ?: 'Whole'),
                        'updated_at'   => now(),
                    ]
                );
            }
        }

        // Also update class_logs_attendance for session records and conducted hours
        if (\Illuminate\Support\Facades\Schema::hasTable('class_logs_attendance')) {
            $logSubBatch = $batchName !== 'Full' ? $batchName : 'Whole';
            DB::table('class_logs_attendance')->updateOrInsert(
                [
                    'batch_subject_id' => $subjectId,
                    'date'             => $date,
                    'period'           => $period,
                    'sub_batch'        => $logSubBatch,
                ],
                [
                    'present_students' => json_encode($presentList),
                    'absent_students'  => json_encode($absentList),
                    'topics_covered'   => "Mechanical Workshop Practical Session ({$subBatchLabel})",
                    'recorded_by'      => $userId ?: 'Staff',
                    'updated_at'       => now(),
                ]
            );
        }

        return response()->json([
            'success'       => true,
            'message'       => "Attendance for {$subBatchLabel} on {$date} saved successfully.",
            'date'          => $date,
            'batch'         => $batchName,
            'present_count' => count($presentList),
            'absent_count'  => count($absentList),
        ]);
    }

    /**
     * Compute CO Attainment for Mechanical Workshop (R-2021)
     */
    public function computeWorkshopAttainment($subjectId, $students, $calculatedScores, $courseFile)
    {
        $defaultCos = [
            ['id' => 'CO1', 'description' => 'Perform basic lathe and machine tool operations (turning, facing, shaping) maintaining dimensional tolerances and workshop safety.'],
            ['id' => 'CO2', 'description' => 'Fabricate mechanical joints in fitting, smithy, and welding sections following standard trade practices.'],
            ['id' => 'CO3', 'description' => 'Execute sheet metal layout, bending, forming, and aluminium fabrication jobs compliant with engineering standards.'],
            ['id' => 'CO4', 'description' => 'Maintain engineering rough and fair records, observe shop-floor safety, and document manufacturing processes accurately.'],
        ];

        $cos = $courseFile->parsed_cos ?: $defaultCos;
        $copoRaw = $courseFile->parsed_copo ?: [];
        $targetPct = isset($copoRaw['_target_percentage']) ? (float)$copoRaw['_target_percentage'] : 50.0;
        $savedSurveys = $copoRaw['_survey_scores'] ?? [];

        $defaultCopo = [
            'CO1' => ['PO1' => 3, 'PO2' => 2, 'PO3' => 1, 'PO4' => 1, 'PO5' => 2, 'PO6' => null, 'PO7' => null, 'PO8' => 1, 'PO9' => 2, 'PO10' => 1, 'PO11' => null, 'PO12' => 2, 'PSO1' => 3, 'PSO2' => 2],
            'CO2' => ['PO1' => 3, 'PO2' => 2, 'PO3' => 2, 'PO4' => 1, 'PO5' => 2, 'PO6' => null, 'PO7' => null, 'PO8' => 1, 'PO9' => 2, 'PO10' => 1, 'PO11' => null, 'PO12' => 2, 'PSO1' => 3, 'PSO2' => 2],
            'CO3' => ['PO1' => 3, 'PO2' => 2, 'PO3' => 2, 'PO4' => 1, 'PO5' => 2, 'PO6' => null, 'PO7' => null, 'PO8' => 1, 'PO9' => 2, 'PO10' => 1, 'PO11' => null, 'PO12' => 2, 'PSO1' => 3, 'PSO2' => 2],
            'CO4' => ['PO1' => 2, 'PO2' => 1, 'PO3' => 1, 'PO4' => 1, 'PO5' => 1, 'PO6' => 2, 'PO7' => 1, 'PO8' => 3, 'PO9' => 3, 'PO10' => 3, 'PO11' => 1, 'PO12' => 2, 'PSO1' => 2, 'PSO2' => 2],
        ];

        $copo = [];
        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coKey) {
            $copo[$coKey] = $copoRaw[$coKey] ?? $defaultCopo[$coKey];
        }

        $totalStudents = count($students);
        $attainmentRows = [];
        $direct = [];
        $indirect = [];
        $overall = [];
        $coDefinitions = [];
        $lvl3 = 70.0;
        $lvl2 = 60.0;
        $lvl1 = 50.0;

        foreach ($cos as $co) {
            $coId = $co['id'];
            $coDefinitions[$coId] = $co['description'] ?? '';
            $metCount = 0;

            foreach ($students as $st) {
                $sc = $calculatedScores[$st->reg_no] ?? null;
                if (!$sc) continue;

                $scoreVal = 0;
                $maxVal = 20;

                if ($coId === 'CO1') {
                    $scoreVal = $sc['work_score_375'] ?? 0;
                    $maxVal = 37.5;
                } elseif ($coId === 'CO2') {
                    $scoreVal = $sc['work_score_375'] ?? 0;
                    $maxVal = 37.5;
                } elseif ($coId === 'CO3') {
                    $scoreVal = ($sc['work_score_375'] ?? 0) * 0.6 + ($sc['open_ended_75'] ?? 0) * 0.4;
                    $maxVal = (37.5 * 0.6) + (7.5 * 0.4);
                } else {
                    $scoreVal = ($sc['open_ended_75'] ?? 0) + ($sc['att_mark_15'] ?? 0);
                    $maxVal = 7.5 + 15.0;
                }

                if ($maxVal > 0 && ($scoreVal / $maxVal) >= ($targetPct / 100.0)) {
                    $metCount++;
                }
            }

            $metPercent = $totalStudents > 0 ? round(($metCount / $totalStudents) * 100, 1) : 0.0;
            $directLevel = 0;
            if ($metPercent >= $lvl3) $directLevel = 3;
            elseif ($metPercent >= $lvl2) $directLevel = 2;
            elseif ($metPercent >= $lvl1) $directLevel = 1;
            else $directLevel = 0;

            $surveyAvg = isset($savedSurveys[$coId]) ? (float)$savedSurveys[$coId] : 2.5;
            $indirectLevel = $surveyAvg;
            $overallLevel = round((0.80 * $directLevel) + (0.20 * $indirectLevel), 2);
            $overallAttainment = round(($overallLevel / 3.0) * 100, 1);

            $direct[$coId] = [
                'target_percentage'    => $targetPct,
                'total_students'       => $totalStudents,
                'students_achieved'    => $metCount,
                'attainment_percentage'=> $metPercent,
                'attainment_level'     => $directLevel,
            ];

            $indirect[$coId] = [
                'survey_avg'   => $surveyAvg,
                'survey_level' => $indirectLevel,
            ];

            $overall[$coId] = [
                'overall_level'      => $overallLevel,
                'overall_attainment' => $overallAttainment,
            ];

            $attainmentRows[] = [
                'co_id'           => $coId,
                'description'     => $co['description'] ?? '',
                'target_threshold'=> $targetPct . '% Marks (Half-Scale)',
                'total_assessed'  => $totalStudents,
                'students_met'    => $metCount,
                'met_percentage'  => $metPercent,
                'direct_level'    => $directLevel,
                'indirect_level'  => $indirectLevel,
                'overall_level'   => $overallLevel,
            ];
        }

        return [
            'cos'               => $cos,
            'copo'              => $copo,
            'attainmentRows'    => $attainmentRows,
            'lvl3'              => $lvl3,
            'lvl2'              => $lvl2,
            'lvl1'              => $lvl1,
            'target_percentage' => $targetPct,
            'direct'            => $direct,
            'indirect'          => $indirect,
            'overall'           => $overall,
            'co_definitions'    => $coDefinitions,
            'co_po_matrix'      => $copo,
        ];
    }

    /**
     * Save Attainment Settings
     */
    public function saveAttainmentSettings(Request $request, $subjectId)
    {
        $this->checkLocked($subjectId);

        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        if ($request->has('cos')) {
            $courseFile->parsed_cos = $request->input('cos');
        }
        $copo = $courseFile->parsed_copo ?: [];
        if ($request->has('copo')) {
            $copo = array_merge($copo, $request->input('copo'));
        }
        if ($request->has('target_percentage')) {
            $copo['_target_percentage'] = (float)$request->input('target_percentage');
        }
        if ($request->has('survey_scores')) {
            $copo['_survey_scores'] = $request->input('survey_scores');
        }
        $courseFile->parsed_copo = $copo;
        $courseFile->save();

        return response()->json([
            'success' => true,
            'message' => 'Workshop CO-PO Attainment configuration saved successfully.'
        ]);
    }

    /**
     * Print Consolidated 3 Section Report (A4 Landscape)
     */
    public function printConsolidated3Section($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first() 
            ?: DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $batchAllocation = $courseFile->batch_allocation ?: [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);
        $workEvals = R21WorkshopWorkEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));
        $sectionEvals = R21WorkshopSectionEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no')->map(fn($items) => $items->keyBy('section_name'));
        $attEvals = R21WorkshopAttendanceEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $ciaSummaries = R21WorkshopCiaSummary::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        return view('r21_workshop.consolidated_3section_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'students',
            'allocations',
            'batchAllocation',
            'calculatedScores',
            'workEvals',
            'sectionEvals',
            'attEvals',
            'ciaSummaries'
        ));
    }

    /**
     * Print Workshop CIA Summary & Statistical Analysis Report (A4 Portrait/Landscape)
     */
    public function printSummaryReport($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $batchAllocation = $courseFile->batch_allocation ?: [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);

        // Aggregates
        $totalStudents = count($students);
        $passedCount = 0;
        $failedCount = 0;
        $b1Count = 0;
        $b2Count = 0;
        $ciaScores = [];

        $gradeBands = [
            'O (>= 68M / 90%)'    => 0,
            'A+ (60 - 67M / 80%)' => 0,
            'A (53 - 59M / 70%)'  => 0,
            'B+ (45 - 52M / 60%)' => 0,
            'B (38 - 44M / 50%)'  => 0,
            'C (30 - 37M / 40%)'  => 0,
            'F (< 30M / Below Min)'=> 0,
        ];

        foreach ($students as $st) {
            $sc = $calculatedScores[$st->reg_no] ?? null;
            if (!$sc) continue;
            $tot = (int)($sc['total_cia_75'] ?? 0);
            $ciaScores[] = $tot;
            if ($tot >= 30) $passedCount++;
            else $failedCount++;

            if (($sc['batch_no'] ?? '1') == '1') $b1Count++;
            else $b2Count++;

            if ($tot >= 68) $gradeBands['O (>= 68M / 90%)']++;
            elseif ($tot >= 60) $gradeBands['A+ (60 - 67M / 80%)']++;
            elseif ($tot >= 53) $gradeBands['A (53 - 59M / 70%)']++;
            elseif ($tot >= 45) $gradeBands['B+ (45 - 52M / 60%)']++;
            elseif ($tot >= 38) $gradeBands['B (38 - 44M / 50%)']++;
            elseif ($tot >= 30) $gradeBands['C (30 - 37M / 40%)']++;
            else $gradeBands['F (< 30M / Below Min)']++;
        }

        $avgScore = count($ciaScores) > 0 ? round(array_sum($ciaScores) / count($ciaScores), 2) : 0;
        $maxScore = count($ciaScores) > 0 ? max($ciaScores) : 0;
        $minScore = count($ciaScores) > 0 ? min($ciaScores) : 0;
        $passRate = $totalStudents > 0 ? round(($passedCount / $totalStudents) * 100, 1) : 0;

        return view('r21_workshop.summary_report_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'students',
            'totalStudents',
            'passedCount',
            'failedCount',
            'b1Count',
            'b2Count',
            'avgScore',
            'maxScore',
            'minScore',
            'passRate',
            'gradeBands',
            'calculatedScores',
            'batchAllocation'
        ));
    }

    /**
     * Print Internal Report / CIA Marks Statement (A4 Landscape)
     */
    public function printInternalReport($subjectId)
    {
        return $this->printConsolidatedCia($subjectId);
    }

    /**
     * Print Dual Batch Internal Report (A4 Landscape)
     */
    public function printDualBatchInternal($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $batchAllocation = $courseFile->batch_allocation ?: [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);

        $b1Students = $students->filter(fn($st) => ($calculatedScores[$st->reg_no]['batch_no'] ?? '1') == '1');
        $b2Students = $students->filter(fn($st) => ($calculatedScores[$st->reg_no]['batch_no'] ?? '1') == '2');

        return view('r21_workshop.dual_batch_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'b1Students',
            'b2Students',
            'batchAllocation',
            'calculatedScores'
        ));
    }

    /**
     * Print Attendance Register & Class Log Report (A4 Portrait/Landscape)
     */
    public function printAttendanceClassLog($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21WorkshopCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?: DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $allocations = R21WorkshopStudentAllocation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $batchAllocation = $courseFile->batch_allocation ?: [
            '1' => ['Fitting', 'Smithy', 'Welding'],
            '2' => ['Machine shop', 'Sheet metal', 'Foundry']
        ];
        $calculatedScores = $this->calculateContinuousCia($subjectId, $students, $allocations, $batchAllocation);
        $attEvals = R21WorkshopAttendanceEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Fetch logs
        $logs = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('class_logs_attendance')) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $subjectId)
                ->orderBy('date', 'asc')
                ->orderBy('period', 'asc')
                ->get();
        }

        return view('r21_workshop.attendance_class_log_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'students',
            'calculatedScores',
            'attEvals',
            'logs'
        ));
    }

    /**
     * Check if CIA marks are locked
     */
    protected function checkLocked($subjectId)
    {
        if (class_exists(ConsolidatedCiaApproval::class) && ConsolidatedCiaApproval::isLockedForSubject($subjectId)) {
            abort(403, 'Consolidated CIA marks have been approved and locked by the Head of Department.');
        }
    }
}
