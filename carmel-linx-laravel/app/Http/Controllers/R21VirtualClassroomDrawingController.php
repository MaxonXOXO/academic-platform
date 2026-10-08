<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\R21DrawingCourseFile;
use App\Models\R21DrawingSheetEvaluation;
use App\Models\R21DrawingSeriesTest;
use App\Models\R21DrawingAttendanceEvaluation;
use App\Models\PracticalEvaluation;
use App\Models\StaffProfile;

class R21VirtualClassroomDrawingController extends Controller
{
    /**
     * Main Virtual Drawing Hall Dashboard (SBTE Revision 2021 Drawing Lab Scheme)
     * Total CIA: 75 Marks | ESE: 50 Marks | Total Course Marks: 125 Marks
     * Split-up:
     * - Continuous Drawing Work: 37.5 Marks (with direct faculty override option)
     * - Open-Ended Evaluation: 7.5 Marks
     * - Series Tests Average: 15.0 Marks (Test 1 & Test 2 average)
     * - Attendance: 15.0 Marks (20% of 75 CIA)
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

        // Default sheets appropriate for the drawing course
        $isCivilBuildingDrawing = ($batchSubject->subject_code == '3018' || str_contains(strtolower($batchSubject->subject_name), 'building drawing'));
        if ($isCivilBuildingDrawing) {
            $defaultSheets = [
                ['sheet_no' => 'Sheet 1', 'module' => 'Module 1', 'title' => 'Plan, Elevation & Section of Single Storied Residential Building', 'co_id' => 'CO1', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 2', 'module' => 'Module 1', 'title' => 'Plan, Elevation & Section of Two Storied Residential Building', 'co_id' => 'CO1', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 3', 'module' => 'Module 2', 'title' => 'Types of Roofs & Roof Trusses (King Post & Queen Post Trusses)', 'co_id' => 'CO2', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 4', 'module' => 'Module 2', 'title' => 'Doors, Windows & Ventilators - Details & Joinery', 'co_id' => 'CO2', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 5', 'module' => 'Module 3', 'title' => 'Stairs & Staircase Details (Dog-legged & Open Newel)', 'co_id' => 'CO3', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 6', 'module' => 'Module 3', 'title' => 'Public / Commercial Building (School / Clinic / Office Plan)', 'co_id' => 'CO3', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 7', 'module' => 'Module 4', 'title' => 'Preparation of Approval / Municipal Submission Drawing', 'co_id' => 'CO4', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 8', 'module' => 'Module 4', 'title' => 'Detailed Estimation, Bar Bending Schedule & Quantity Take-off', 'co_id' => 'CO4', 'max_timely' => 25, 'max_appearance' => 25],
            ];
        } else {
            $defaultSheets = [
                ['sheet_no' => 'Sheet 1', 'module' => 'Module 1', 'title' => 'Drawing Sheet 1 - Fundamental Drafting Practice', 'co_id' => 'CO1', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 2', 'module' => 'Module 1', 'title' => 'Drawing Sheet 2 - Geometric & Projection Layout', 'co_id' => 'CO1', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 3', 'module' => 'Module 2', 'title' => 'Drawing Sheet 3 - Component Drafting & Sections', 'co_id' => 'CO2', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 4', 'module' => 'Module 2', 'title' => 'Drawing Sheet 4 - Structural / Assembly Details', 'co_id' => 'CO2', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 5', 'module' => 'Module 3', 'title' => 'Drawing Sheet 5 - Plan & Elevation Projections', 'co_id' => 'CO3', 'max_timely' => 25, 'max_appearance' => 25],
                ['sheet_no' => 'Sheet 6', 'module' => 'Module 3', 'title' => 'Drawing Sheet 6 - Advanced Engineering Layouts', 'co_id' => 'CO3', 'max_timely' => 25, 'max_appearance' => 25],
            ];
        }

        // Fetch or Create Course File (Strictly R2021 Lab Model: CIA 75, ESE 50)
        $drawingCourseFile = R21DrawingCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? $classroom->branch ?? 'Civil Engineering',
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
                'semester' => $classroom->current_semester ?? $batchSubject->semester ?? 3,
                'type_of_course' => 'Practical / Lab',
                'teaching_scheme' => '0:0:3:0',
                'contact_hours' => 60,
                'credits' => 2.0,
                'cia_marks' => 75,
                'ese_marks' => 50,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Draft residential building plans and sectional elevations as per building rules.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Construct joinery, structural roof trusses, doors and windows.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Develop detailed drawings for staircases and public utility buildings.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Prepare municipal submission drawings, quantity estimation, and bar schedules.', 'cognitive_level' => 'Apply']
                ],
                'parsed_modules' => [
                    ['module_id' => 'I', 'title' => 'Residential Building Planning & Sectional Elevation', 'hours' => 15.0, 'content' => 'Building bye-laws, Kerala Municipality Building Rules, Single & Two-storied plans'],
                    ['module_id' => 'II', 'title' => 'Structural Trusses & Joinery Details', 'hours' => 15.0, 'content' => 'Timber and steel roof trusses, doors, windows, ventilators, joinery details'],
                    ['module_id' => 'III', 'title' => 'Stairs & Public Building Layouts', 'hours' => 15.0, 'content' => 'Dog-legged stairs, open-newel stairs, planning of schools, clinics, and offices'],
                    ['module_id' => 'IV', 'title' => 'Municipal Submission & Quantity Estimation', 'hours' => 15.0, 'content' => 'Site plan, service plan, key plan, Bar bending schedules and structural quantity take-off']
                ],
                'parsed_sheets' => $defaultSheets,
            ]
        );

        // Enforce CIA 75 and ESE 50 if outdated values were stored
        if ($drawingCourseFile->cia_marks != 75 || $drawingCourseFile->ese_marks != 50) {
            $drawingCourseFile->cia_marks = 75;
            $drawingCourseFile->ese_marks = 50;
            if ($isCivilBuildingDrawing && str_contains(json_encode($drawingCourseFile->parsed_sheets), 'Conic Sections')) {
                $drawingCourseFile->parsed_sheets = $defaultSheets;
            }
            $drawingCourseFile->save();
        }

        if (empty($drawingCourseFile->parsed_sheets)) {
            $drawingCourseFile->parsed_sheets = $defaultSheets;
            $drawingCourseFile->save();
        }

        // Lesson Plans
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        // Attendance statistics & statutory R21 marks
        $attMax = 15.0; // 20% of CIA
        $attMap = $this->calculateAttendanceMap($batchSubject, $students, $attMax);

        $attEvals = R21DrawingAttendanceEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // 3. Practical evaluations (stores 37.5 Continuous Override, 7.5 Open Ended, and 50 Board Exam)
        $practicalEvals = PracticalEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // 4. Formative Sheet Evaluations
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // 5. Summative Series Tests (Test 1 & Test 2)
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Assessment Parameters (Strict SBTE R2021)
        $ciaMax         = 75.0;
        $eseMax         = 50.0;
        $continuousMax  = 37.5; // 50% of CIA
        $openEndedMax   = 7.5;  // 10% of CIA
        $summativeMax   = 15.0; // 20% of CIA

        // Consolidated student calculations
        $studentResults = $students->map(function ($student) use (
            $attMap,
            $attEvals,
            $practicalEvals,
            $sheetEvals,
            $seriesTests,
            $continuousMax,
            $openEndedMax,
            $summativeMax,
            $attMax,
            $ciaMax,
            $eseMax
        ) {
            $regNo = $student->reg_no;
            $pEval = $practicalEvals->get($regNo);

            // A. Attendance Marks (15.0 Marks Max)
            $attInfo = $attMap[$regNo] ?? [
                'total' => 0, 'attended' => 0, 'percentage' => 100.0, 'source' => 'Default', 'calc_mark' => $attMax
            ];
            $totalAtt = $attInfo['total'];
            $presentAtt = $attInfo['attended'];
            $attPercentage = $attInfo['percentage'];
            $attSource = $attInfo['source'];
            $calcAttMark = $attInfo['calc_mark'];

            $savedAtt = $attEvals->get($regNo);
            $attOverride = ($savedAtt && $savedAtt->override_mark !== null) 
                ? floatval($savedAtt->override_mark) 
                : null;

            $finalAttMark = ($attOverride !== null) 
                ? $attOverride 
                : (($savedAtt && $savedAtt->final_attendance_mark !== null) 
                    ? floatval($savedAtt->final_attendance_mark) 
                    : (($pEval && $pEval->attendance_marks !== null && floatval($pEval->attendance_marks) > 0) 
                        ? floatval($pEval->attendance_marks) 
                        : $calcAttMark));

            // B. Continuous Drawing Work (37.5 Marks Max)
            $stSheets = $sheetEvals->get($regNo, collect());
            $validSheets = $stSheets->where('is_absent', false);
            $evaluatedSheetsCount = $validSheets->count();
            $avgSheetScore100 = $evaluatedSheetsCount > 0 ? $validSheets->avg('total_score_100') : 0.00;
            $calculatedContinuous = round((($avgSheetScore100 / 100.0) * $continuousMax) * 2) / 2;

            // Check faculty override in practical_evaluations.lab_work_marks
            $continuousOverride = ($pEval && $pEval->lab_work_marks !== null && $pEval->lab_work_marks !== '') 
                ? floatval($pEval->lab_work_marks) 
                : null;

            $hasContinuousOverride = ($continuousOverride !== null);
            $finalContinuousMark = $hasContinuousOverride ? $continuousOverride : $calculatedContinuous;
            if ($finalContinuousMark > $continuousMax) $finalContinuousMark = $continuousMax;

            // C. Open-Ended Evaluation (7.5 Marks Max)
            $openEndedMark = ($pEval && $pEval->micro_project !== null) ? floatval($pEval->micro_project) : 0.00;
            if ($openEndedMark > $openEndedMax) $openEndedMark = $openEndedMax;
            $openEndedTopic = $pEval ? ($pEval->open_ended_topic ?? '') : '';

            // D. Summative Series Tests (15.0 Marks Max)
            $stTests = $seriesTests->get($regNo, collect());
            $t1 = $stTests->where('test_no', 'Test 1')->first();
            $t2 = $stTests->where('test_no', 'Test 2')->first();

            $t1Raw = ($t1 && !$t1->is_absent && $t1->total_score_100 !== null) ? floatval($t1->total_score_100) : null;
            $t2Raw = ($t2 && !$t2->is_absent && $t2->total_score_100 !== null) ? floatval($t2->total_score_100) : null;

            // Scaled to 15.0 Max
            $t1Score15 = ($t1Raw !== null) ? round(($t1Raw / 100.0) * 15.0, 2) : null;
            $t2Score15 = ($t2Raw !== null) ? round(($t2Raw / 100.0) * 15.0, 2) : null;

            if ($t1Score15 !== null && $t2Score15 !== null) {
                $avgSeries15 = round(($t1Score15 + $t2Score15) / 2.0, 2);
            } elseif ($t1Score15 !== null) {
                $avgSeries15 = $t1Score15;
            } elseif ($t2Score15 !== null) {
                $avgSeries15 = $t2Score15;
            } else {
                $avgSeries15 = 0.00;
            }
            if ($avgSeries15 > $summativeMax) $avgSeries15 = $summativeMax;

            // E. Total CIA (Max 75.0 - Whole Number)
            $totalCia = (int)round($finalContinuousMark + $openEndedMark + $avgSeries15 + $finalAttMark);
            if ($totalCia > $ciaMax) $totalCia = (int)$ciaMax;
            $isCiaPass = ($totalCia >= 30); // Minimum 40% of 75

            // F. ESE Board Exam Mark (Max 50.0)
            $eseMark = ($pEval && $pEval->board_exam_marks !== null && $pEval->board_exam_marks !== '')
                ? floatval($pEval->board_exam_marks)
                : null;

            $grandTotal = ($eseMark !== null) ? (int)round($totalCia + $eseMark) : $totalCia;
            $isOverallPass = $isCiaPass && ($eseMark === null || $eseMark >= 20.0);

            return [
                'reg_no'                  => $student->reg_no,
                'sbte_reg_no'             => $student->sbte_reg_no ?? $student->reg_no,
                'name'                    => $student->name,
                'roll_no'                 => $student->roll_no,

                // Attendance
                'att_total'               => $totalAtt,
                'att_attended'            => $presentAtt,
                'att_percentage'          => $attPercentage,
                'att_source'              => $attSource,
                'calc_att_mark'           => $calcAttMark,
                'att_override'            => $attOverride,
                'final_att_mark'          => $finalAttMark,

                // Continuous (37.5)
                'sheet_count'             => $evaluatedSheetsCount,
                'avg_sheet_score_100'     => round($avgSheetScore100, 2),
                'calc_continuous'         => $calculatedContinuous,
                'continuous_override'     => $continuousOverride,
                'has_continuous_override' => $hasContinuousOverride,
                'final_continuous_mark'   => $finalContinuousMark,

                // Open-Ended (7.5)
                'open_ended_mark'         => $openEndedMark,
                'open_ended_topic'        => $openEndedTopic,

                // Series Tests (15.0)
                't1_score_15'             => $t1Score15,
                't2_score_15'             => $t2Score15,
                't1_absent'               => $t1 ? $t1->is_absent : false,
                't2_absent'               => $t2 ? $t2->is_absent : false,
                'avg_series_15'           => $avgSeries15,

                // Totals & Status
                'total_cia'               => $totalCia,
                'is_cia_pass'             => $isCiaPass,
                'ese_mark'                => $eseMark,
                'grand_total'             => $grandTotal,
                'is_overall_pass'         => $isOverallPass,

                'sheets_detail'           => $stSheets->keyBy('sheet_no'),
            ];
        });

        // Assigned Staff & HOD
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation', 'mobile_no')
            ->first();

        return view('r21_drawing.virtual_classroom_drawing', compact(
            'batchSubject',
            'classroom',
            'drawingCourseFile',
            'students',
            'lessonPlans',
            'studentResults',
            'sheetEvals',
            'seriesTests',
            'assignedStaff',
            'hod',
            'continuousMax',
            'openEndedMax',
            'summativeMax',
            'attMax',
            'ciaMax',
            'eseMax'
        ));
    }

    /**
     * Fast Save Consolidated CIA Register (Continuous 37.5 Override, Open-Ended 7.5, Attendance, ESE)
     */
    public function saveFastCiaRegister(Request $request, $subjectId)
    {
        $records = $request->input('records', []);
        if (!is_array($records)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        $userId = Session::get('userId');

        foreach ($records as $r) {
            $regNo = $r['reg_no'] ?? null;
            if (!$regNo) continue;

            $contOverride = (isset($r['continuous_override']) && $r['continuous_override'] !== '' && $r['continuous_override'] !== null)
                ? min(37.5, max(0, floatval($r['continuous_override'])))
                : null;

            $openEnded = (isset($r['open_ended_mark']) && $r['open_ended_mark'] !== '' && $r['open_ended_mark'] !== null)
                ? min(7.5, max(0, floatval($r['open_ended_mark'])))
                : 0.0;

            $openTopic = $r['open_ended_topic'] ?? null;

            $attMark = (isset($r['attendance_mark']) && $r['attendance_mark'] !== '' && $r['attendance_mark'] !== null)
                ? min(15.0, max(0, floatval($r['attendance_mark'])))
                : null;

            $attOverride = (isset($r['attendance_override']) && $r['attendance_override'] !== '' && $r['attendance_override'] !== null)
                ? min(15.0, max(0, floatval($r['attendance_override'])))
                : null;

            $eseMark = (isset($r['ese_mark']) && $r['ese_mark'] !== '' && $r['ese_mark'] !== null)
                ? min(50.0, max(0, floatval($r['ese_mark'])))
                : null;

            $existingEval = PracticalEvaluation::where('batch_subject_id', $subjectId)->where('reg_no', $regNo)->first();
            $attMarkToSave = ($attMark !== null) ? $attMark : (($attOverride !== null) ? $attOverride : ($existingEval ? $existingEval->attendance_marks : 0.00));

            PracticalEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo
                ],
                [
                    'assessor_mobile_no' => $userId,
                    'lab_work_marks'     => $contOverride,
                    'micro_project'      => $openEnded,
                    'open_ended_topic'   => $openTopic,
                    'attendance_marks'   => $attMarkToSave,
                    'board_exam_marks'   => $eseMark,
                ]
            );

            if ($attOverride !== null) {
                R21DrawingAttendanceEvaluation::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'reg_no'           => $regNo
                    ],
                    [
                        'override_mark'         => $attOverride,
                        'final_attendance_mark' => $attOverride,
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Consolidated CIA marks saved successfully.'
        ]);
    }

    /**
     * Fast Save Series Tests (Test 1 & Test 2 out of 15 Marks)
     */
    public function saveSeriesFast(Request $request, $subjectId)
    {
        $records = $request->input('records', []);
        if (!is_array($records)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        foreach ($records as $r) {
            $regNo = $r['reg_no'] ?? null;
            if (!$regNo) continue;

            // Test 1 (0-15 Marks)
            if (isset($r['t1_mark']) && $r['t1_mark'] !== '' && $r['t1_mark'] !== null) {
                $t1Score15 = min(15.0, max(0, floatval($r['t1_mark'])));
                $t1Score100 = round(($t1Score15 / 15.0) * 100.0, 2);
                $t1Absent = !empty($r['t1_absent']);

                R21DrawingSeriesTest::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'test_no'          => 'Test 1',
                        'reg_no'           => $regNo,
                    ],
                    [
                        'procedure_drawing' => round($t1Score100 * 0.40, 2),
                        'final_drawing'     => round($t1Score100 * 0.30, 2),
                        'dimensioning'      => round($t1Score100 * 0.20, 2),
                        'neatness'          => round($t1Score100 * 0.10, 2),
                        'total_score_100'   => $t1Absent ? 0.00 : $t1Score100,
                        'is_absent'         => $t1Absent,
                    ]
                );
            }

            // Test 2 (0-15 Marks)
            if (isset($r['t2_mark']) && $r['t2_mark'] !== '' && $r['t2_mark'] !== null) {
                $t2Score15 = min(15.0, max(0, floatval($r['t2_mark'])));
                $t2Score100 = round(($t2Score15 / 15.0) * 100.0, 2);
                $t2Absent = !empty($r['t2_absent']);

                R21DrawingSeriesTest::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'test_no'          => 'Test 2',
                        'reg_no'           => $regNo,
                    ],
                    [
                        'procedure_drawing' => round($t2Score100 * 0.40, 2),
                        'final_drawing'     => round($t2Score100 * 0.30, 2),
                        'dimensioning'      => round($t2Score100 * 0.20, 2),
                        'neatness'          => round($t2Score100 * 0.10, 2),
                        'total_score_100'   => $t2Absent ? 0.00 : $t2Score100,
                        'is_absent'         => $t2Absent,
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Series examination marks saved successfully.'
        ]);
    }

    /**
     * Save / Update Drawing Sheets Configuration
     */
    public function saveSheetsConfig(Request $request, $subjectId)
    {
        $sheets = $request->input('sheets', []);
        if (!is_array($sheets) || empty($sheets)) {
            return response()->json(['success' => false, 'message' => 'Invalid sheet list.'], 400);
        }

        $courseFile = R21DrawingCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->parsed_sheets = $sheets;
        $courseFile->save();

        return response()->json([
            'success' => true,
            'message' => 'Drawing sheets configuration updated successfully.'
        ]);
    }

    /**
     * Save Formative Sheet Marks
     */
    public function saveSheetMarks(Request $request, $subjectId)
    {
        $sheetNo = $request->input('sheet_no');
        $evaluations = $request->input('evaluations', []);

        if (empty($sheetNo) || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $sheetConfig = collect($courseFile->parsed_sheets ?? [])->where('sheet_no', $sheetNo)->first();

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $timely = floatval($eval['timely_completion'] ?? 0);
            $appearance = floatval($eval['appearance_organization'] ?? 0);
            $isAbsent = !empty($eval['is_absent']);

            if ($timely > 50) $timely = 50;
            if ($timely < 0) $timely = 0;
            if ($appearance > 50) $appearance = 50;
            if ($appearance < 0) $appearance = 0;

            $total = $isAbsent ? 0.00 : ($timely + $appearance);

            R21DrawingSheetEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'sheet_no'         => $sheetNo,
                    'reg_no'           => $regNo,
                ],
                [
                    'sheet_title'             => $sheetConfig['title'] ?? $sheetNo,
                    'module_no'               => $sheetConfig['module'] ?? null,
                    'co_id'                   => $sheetConfig['co_id'] ?? null,
                    'timely_completion'       => $timely,
                    'appearance_organization' => $appearance,
                    'total_score_100'         => $total,
                    'is_absent'               => $isAbsent,
                    'remarks'                 => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Marks for {$sheetNo} saved successfully."
        ]);
    }

    /**
     * Save Summative Series Test Marks (Detailed 4 Criteria breakdown)
     */
    public function saveSeriesTestMarks(Request $request, $subjectId)
    {
        $testNo = $request->input('test_no', 'Test 1');
        $evaluations = $request->input('evaluations', []);

        if (!is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $procedure = floatval($eval['procedure_drawing'] ?? 0);
            $finalDraw = floatval($eval['final_drawing'] ?? 0);
            $dimen     = floatval($eval['dimensioning'] ?? 0);
            $neat      = floatval($eval['neatness'] ?? 0);
            $isAbsent  = !empty($eval['is_absent']);

            if ($procedure > 40) $procedure = 40;
            if ($finalDraw > 30) $finalDraw = 30;
            if ($dimen > 20)     $dimen = 20;
            if ($neat > 10)      $neat = 10;

            $total = $isAbsent ? 0.00 : ($procedure + $finalDraw + $dimen + $neat);

            R21DrawingSeriesTest::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'test_no'          => $testNo,
                    'reg_no'           => $regNo,
                ],
                [
                    'procedure_drawing' => $procedure,
                    'final_drawing'     => $finalDraw,
                    'dimensioning'      => $dimen,
                    'neatness'          => $neat,
                    'total_score_100'   => $total,
                    'is_absent'         => $isAbsent,
                    'remarks'           => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Summative {$testNo} marks saved successfully."
        ]);
    }

    /**
     * Save Attendance Override Marks
     */
    public function saveAttendanceMarks(Request $request, $subjectId)
    {
        $records = $request->input('records', []);
        if (!is_array($records)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        $attMax = 15.0;

        foreach ($records as $rec) {
            $regNo = $rec['reg_no'] ?? null;
            if (!$regNo) continue;

            $override = isset($rec['override_mark']) && $rec['override_mark'] !== '' 
                ? floatval($rec['override_mark']) 
                : null;

            if ($override !== null) {
                if ($override > $attMax) $override = $attMax;
                if ($override < 0) $override = 0;
            }

            $attMark = floatval($rec['attendance_mark'] ?? 0);
            $finalMark = $override !== null ? $override : $attMark;

            R21DrawingAttendanceEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo,
                ],
                [
                    'attendance_percentage' => floatval($rec['attendance_percentage'] ?? 100),
                    'attendance_mark'       => $attMark,
                    'override_mark'         => $override,
                    'final_attendance_mark' => $finalMark,
                ]
            );

            PracticalEvaluation::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                ['attendance_marks' => $finalMark]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marks updated successfully.'
        ]);
    }

    /**
     * Upload & Parse Syllabus PDF
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:15360'
        ]);

        $file = $request->file('syllabus_file');
        $filename = 'r21_drawing_syllabus_' . $subjectId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('syllabi', $filename, 'public');

        $courseFile = R21DrawingCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->syllabus_pdf_path = '/storage/' . $path;
        $courseFile->save();

        return response()->json([
            'success' => true,
            'message' => 'Syllabus PDF uploaded successfully.',
            'path'    => $courseFile->syllabus_pdf_path
        ]);
    }

    /**
     * Print Drawing Formative Sheet Register (Max 37.5M)
     */
    public function printFormativeRegister($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $practicalEvals = PracticalEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $sheets = $courseFile->parsed_sheets ?: [];

        return view('r21_drawing.sheet_evaluation_print', compact('batchSubject', 'courseFile', 'students', 'sheetEvals', 'practicalEvals', 'sheets'));
    }

    /**
     * Print Summative Series Test Register (Max 15.0M)
     */
    public function printSummativeRegister($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');

        return view('r21_drawing.summative_test_print', compact('batchSubject', 'courseFile', 'students', 'seriesTests'));
    }

    /**
     * Print Consolidated CIA Marksheet (CIA: 75M, ESE: 50M -> Total: 125M)
     */
    public function printConsolidatedCia($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderByRollOrName()->get();

        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $attEvals = R21DrawingAttendanceEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $practicalEvals = PracticalEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $ciaMax        = 75.0;
        $eseMax        = 50.0;
        $continuousMax = 37.5;
        $openEndedMax  = 7.5;
        $summativeMax  = 15.0;
        $attMax        = 15.0;

        $attMap = $this->calculateAttendanceMap($batchSubject, $students, $attMax);

        $studentResults = $students->map(function ($s) use ($sheetEvals, $seriesTests, $attEvals, $practicalEvals, $attMap, $continuousMax, $openEndedMax, $summativeMax, $attMax, $ciaMax, $eseMax) {
            $regNo = $s->reg_no;
            $pEval = $practicalEvals->get($regNo);

            // Attendance (Max 15)
            $attInfo = $attMap[$regNo] ?? null;
            $calcAttMark = $attInfo ? $attInfo['calc_mark'] : $attMax;
            $savedAtt = $attEvals->get($regNo);
            $attOverride = ($savedAtt && $savedAtt->override_mark !== null) ? floatval($savedAtt->override_mark) : null;
            $attMark = ($attOverride !== null) 
                ? $attOverride 
                : (($savedAtt && $savedAtt->final_attendance_mark !== null) 
                    ? floatval($savedAtt->final_attendance_mark) 
                    : (($pEval && $pEval->attendance_marks !== null && floatval($pEval->attendance_marks) > 0) 
                        ? floatval($pEval->attendance_marks) 
                        : $calcAttMark));

            // Continuous Work (Max 37.5)
            $stSheets = $sheetEvals->get($regNo, collect());
            $validSheets = $stSheets->where('is_absent', false);
            $avgSheetScore = $validSheets->count() > 0 ? $validSheets->avg('total_score_100') : 0.00;
            $calcContinuous = round((($avgSheetScore / 100.0) * $continuousMax) * 2) / 2;

            $continuousOverride = ($pEval && $pEval->lab_work_marks !== null && $pEval->lab_work_marks !== '') ? floatval($pEval->lab_work_marks) : null;
            $continuousMark = ($continuousOverride !== null) ? $continuousOverride : $calcContinuous;

            // Open-Ended (Max 7.5)
            $openEndedMark = ($pEval && $pEval->micro_project !== null) ? floatval($pEval->micro_project) : 0.00;

            // Series Tests (Max 15)
            $stTests = $seriesTests->get($regNo, collect());
            $t1 = $stTests->where('test_no', 'Test 1')->first();
            $t2 = $stTests->where('test_no', 'Test 2')->first();
            $t1Score = ($t1 && !$t1->is_absent && $t1->total_score_100 !== null) ? round((floatval($t1->total_score_100) / 100.0) * 15.0, 2) : null;
            $t2Score = ($t2 && !$t2->is_absent && $t2->total_score_100 !== null) ? round((floatval($t2->total_score_100) / 100.0) * 15.0, 2) : null;

            if ($t1Score !== null && $t2Score !== null) {
                $seriesAvg = round(($t1Score + $t2Score) / 2.0, 2);
            } elseif ($t1Score !== null) {
                $seriesAvg = $t1Score;
            } elseif ($t2Score !== null) {
                $seriesAvg = $t2Score;
            } else {
                $seriesAvg = 0.00;
            }

            // Total CIA (Max 75 - Whole Number)
            $totalCia = (int)round($continuousMark + $openEndedMark + $seriesAvg + $attMark);
            if ($totalCia > $ciaMax) $totalCia = (int)$ciaMax;

            // ESE Mark (Max 50)
            $eseMark = ($pEval && $pEval->board_exam_marks !== null) ? floatval($pEval->board_exam_marks) : null;
            $grandTotal = ($eseMark !== null) ? (int)round($totalCia + $eseMark) : $totalCia;

            return [
                'student'         => $s,
                'continuous_mark' => $continuousMark,
                'is_overridden'   => ($continuousOverride !== null),
                'open_ended_mark' => $openEndedMark,
                'series_mark'     => $seriesAvg,
                'attendance_mark' => $attMark,
                'total_cia'       => $totalCia,
                'is_pass'         => ($totalCia >= 30),
                'ese_mark'        => $eseMark,
                'grand_total'     => $grandTotal,
            ];
        });

        return view('r21_drawing.cia_consolidated_print', compact(
            'batchSubject',
            'courseFile',
            'classroom',
            'studentResults',
            'ciaMax',
            'eseMax',
            'continuousMax',
            'openEndedMax',
            'summativeMax',
            'attMax'
        ));
    }

    /**
     * Apply Grouped Sheets Average to Continuous CIA (Max 37.5 Marks)
     */
    public function applySheetsAverageToCia(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->get(['reg_no']);
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');

        $continuousMax = 37.5;
        $updatedCount = 0;
        $attMap = $this->calculateAttendanceMap($batchSubject, $students, 15.0);

        foreach ($students as $student) {
            $regNo = $student->reg_no;
            $stSheets = $sheetEvals->get($regNo, collect());
            $validSheets = $stSheets->where('is_absent', false);
            if ($validSheets->count() > 0) {
                $avgSheetScore100 = $validSheets->avg('total_score_100');
                $calculatedContinuous = min($continuousMax, max(0.0, round((($avgSheetScore100 / 100.0) * $continuousMax) * 2) / 2));
            } else {
                $calculatedContinuous = 0.0;
            }

            $attInfo = $attMap[$regNo] ?? null;
            $defaultAtt = $attInfo ? $attInfo['calc_mark'] : 0.00;

            $existingEval = PracticalEvaluation::where('batch_subject_id', $subjectId)->where('reg_no', $regNo)->first();
            PracticalEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no'           => $regNo
                ],
                [
                    'assessor_mobile_no' => $userId,
                    'lab_work_marks'     => $calculatedContinuous,
                    'attendance_marks'   => ($existingEval && $existingEval->attendance_marks > 0) ? $existingEval->attendance_marks : $defaultAtt,
                    'micro_project'      => $existingEval ? $existingEval->micro_project : 0.00,
                ]
            );
            $updatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully calculated and applied sheet averages to Continuous CIA for {$updatedCount} students."
        ]);
    }

    /**
     * Save Lesson Plans Updates
     */
    public function saveLessonPlans(Request $request, $subjectId)
    {
        $plans = $request->input('plans', []);
        if (!is_array($plans)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        foreach ($plans as $index => $p) {
            $id = $p['id'] ?? null;
            $dayNo = !empty($p['day_no']) ? intval($p['day_no']) : ($index + 1);
            $hours = isset($p['allocated_hours']) && $p['allocated_hours'] !== '' ? intval($p['allocated_hours']) : 2;
            $status = in_array($p['status'] ?? '', ['Pending', 'In Progress', 'Completed']) ? $p['status'] : 'Pending';

            $data = [
                'batch_subject_id' => $subjectId,
                'day_no'           => $dayNo,
                'remarks'          => $p['remarks'] ?? null,
                'topic_content'    => !empty($p['topic_content']) ? $p['topic_content'] : 'Drawing Exercise',
                'allocated_hours'  => $hours,
                'co_id'            => !empty($p['co_id']) ? trim($p['co_id']) : 'CO1',
                'proposed_date'    => !empty($p['proposed_date']) ? $p['proposed_date'] : null,
                'actual_date'      => !empty($p['actual_date']) ? $p['actual_date'] : null,
                'actual_hours'     => isset($p['actual_hours']) && $p['actual_hours'] !== '' ? floatval($p['actual_hours']) : $hours,
                'status'           => $status,
                'sub_batch'        => 'Whole',
                'mode'             => 'L',
            ];

            if ($id && is_numeric($id)) {
                LessonPlan::where('id', $id)
                    ->where('batch_subject_id', $subjectId)
                    ->update($data);
            } else {
                LessonPlan::create($data);
            }
        }

        $deletedIds = $request->input('deleted_ids', []);
        if (!empty($deletedIds) && is_array($deletedIds)) {
            LessonPlan::where('batch_subject_id', $subjectId)
                ->whereIn('id', $deletedIds)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson plans updated successfully.'
        ]);
    }

    /**
     * Print Lesson Plan
     */
    public function printLessonPlan($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();

        $assignedStaffList = $batchSubject->getAssignedFacultyList();
        $facultyNames = $batchSubject->getAssignedFacultyNames();

        return view('r21_drawing.lesson_plan_print', compact('batchSubject', 'courseFile', 'classroom', 'lessonPlans', 'assignedStaffList', 'facultyNames'));
    }

    /**
     * Compute attendance statistics & statutory R21 marks for enrolled students
     */
    protected function calculateAttendanceMap($batchSubject, $students, $attMax = 15.0)
    {
        $subjectId = $batchSubject->id;

        // 1. Attendance from official TEAMS student_attendance upload & class logs
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->get();

        // 2. Common institutional attendance fallback across the classroom
        $classroomRegNos = $students->pluck('reg_no');
        $commonAttendanceData = DB::table('student_attendance')
            ->whereIn('reg_no', $classroomRegNos)
            ->get()
            ->groupBy('reg_no');

        $commonClassLogs = DB::table('class_logs_attendance')
            ->join('batch_subjects', 'class_logs_attendance.batch_subject_id', '=', 'batch_subjects.id')
            ->where('batch_subjects.classroom_id', $batchSubject->classroom_id)
            ->select('class_logs_attendance.*')
            ->get();

        $attMap = [];
        foreach ($students as $student) {
            $regNo = $student->reg_no;

            $stAtt = $attendanceData->get($regNo, collect());
            if ($stAtt->isNotEmpty()) {
                $totalAtt = $stAtt->count();
                $presentAtt = $stAtt->whereIn('status', ['Present', 'Late'])->count();
                $attPercentage = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100, 2) : 100.00;
                $attSource = 'Subject TEAMS Log';
            } elseif ($classLogs->isNotEmpty()) {
                $presLogs = 0;
                $totLogs = $classLogs->count();
                foreach ($classLogs as $cl) {
                    $pList = json_decode($cl->present_students ?? '[]', true) ?: [];
                    if (in_array($regNo, $pList)) $presLogs++;
                }
                $attPercentage = $totLogs > 0 ? round(($presLogs / $totLogs) * 100, 2) : 100.00;
                $totalAtt = $totLogs;
                $presentAtt = $presLogs;
                $attSource = 'Subject Class Log';
            } else {
                $stCommon = $commonAttendanceData->get($regNo, collect());
                if ($stCommon->isNotEmpty()) {
                    $totalAtt = $stCommon->count();
                    $presentAtt = $stCommon->whereIn('status', ['Present', 'Late'])->count();
                    $attPercentage = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100, 2) : 100.00;
                    $attSource = 'Common TEAMS Log';
                } elseif ($commonClassLogs->isNotEmpty()) {
                    $presLogs = 0;
                    $totLogs = $commonClassLogs->count();
                    foreach ($commonClassLogs as $cl) {
                        $pList = json_decode($cl->present_students ?? '[]', true) ?: [];
                        if (in_array($regNo, $pList)) $presLogs++;
                    }
                    $attPercentage = $totLogs > 0 ? round(($presLogs / $totLogs) * 100, 2) : 100.00;
                    $totalAtt = $totLogs;
                    $presentAtt = $presLogs;
                    $attSource = 'Common Class Log';
                } else {
                    $totalAtt = 0;
                    $presentAtt = 0;
                    $attPercentage = 100.00;
                    $attSource = 'Default';
                }
            }

            $calcAttMark = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, $attMax);

            $attMap[$regNo] = [
                'total'       => $totalAtt,
                'attended'    => $presentAtt,
                'percentage'  => $attPercentage,
                'source'      => $attSource,
                'calc_mark'   => $calcAttMark,
            ];
        }

        return $attMap;
    }
}
