<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\R26CourseFile;
use App\Models\R26CourseFileDocument;
use App\Models\R26StudentLabBatch;
use App\Models\R26PracticalExperimentEvaluation;
use App\Models\R26OpenEndedEvaluation;
use App\Models\R26PracticalSeriesEvaluation;
use App\Models\R26PracticalSeriesExam;
use App\Models\R26PracticalEseMark;
use App\Models\R26PracticalCourseFile;
use App\Models\StaffProfile;
use App\Models\SubjectStaffAssignment;

class R26VirtualClassroomPracticalController extends Controller
{
    private function getStaff()
    {
        $userId = Session::get('userId');
        if (!$userId) return null;
        return StaffProfile::where('mobile_no', $userId)->first();
    }

    private function getClassroom($classroomId)
    {
        $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $classroomId)->first();
        }
        return $classroom;
    }

    /**
     * Compute attendance mark per student as per Table 2.1.
     */
    private function computeAttendanceMark(float $pct): int
    {
        if ($pct >= 90) return 5;
        if ($pct >= 80) return 4;
        if ($pct >= 75) return 3;
        if ($pct >= 70) return 2;
        if ($pct >= 65) return 1;
        return 0;
    }

    /**
     * Build consolidated CIA + ESE scores array for all students.
     */
    private function buildConsolidatedScores($students, $experimentLogs, $openEndedLogs, $seriesExamLogs, $eseMarks, $attendanceMarks): array
    {
        $scores = [];
        foreach ($students as $student) {
            $regNo = $student->reg_no;

            // 1. Day Work avg → scale to 30M
            $expNums = [];
            foreach ($experimentLogs as $expNo => $logs) {
                $log = $logs->where('reg_no', $regNo)->first();
                if ($log) $expNums[] = floatval($log->total_score_50);
            }
            $avgExp50 = count($expNums) > 0 ? array_sum($expNums) / count($expNums) : 0;
            $scaled30 = round(($avgExp50 / 50) * 30, 2);

            // 2. Open-ended → scale to 10M
            $openLog = $openEndedLogs->get($regNo);
            $open50 = $openLog ? floatval($openLog->total_score_50) : 0;
            $scaled10 = round(($open50 / 50) * 10, 2);

            // 3. Series avg → scale to 15M
            $seriesNums = [];
            foreach (['Series 1', 'Series 2'] as $sName) {
                if (isset($seriesExamLogs[$sName])) {
                    $log = $seriesExamLogs[$sName]->where('reg_no', $regNo)->first();
                    if ($log) $seriesNums[] = floatval($log->total_score_40);
                }
            }
            $avg40 = count($seriesNums) > 0 ? array_sum($seriesNums) / count($seriesNums) : 0;
            $scaled15 = round(($avg40 / 40) * 15, 2);

            // 4. Attendance → 5M
            $att5 = $attendanceMarks[$regNo]['mark'] ?? 5;

            $cia60 = round($scaled30 + $scaled10 + $scaled15 + $att5, 2);

            $eseRec = $eseMarks->get($regNo);
            $ese40 = $eseRec ? floatval($eseRec->ese_score) : 0.00;

            $scores[$regNo] = [
                'raw_exp_avg'        => round($avgExp50, 2),
                'scaled_lab_work_30' => $scaled30,
                'raw_open_ended'     => round($open50, 2),
                'scaled_open_ended_10' => $scaled10,
                'raw_series_avg'     => round($avg40, 2),
                'scaled_series_15'   => $scaled15,
                'attendance_mark_5'  => $att5,
                'total_cia_60'       => $cia60,
                'ese_score_40'       => $ese40,
                'grand_total_100'    => round($cia60 + $ese40, 2),
            ];
        }
        return $scores;
    }

    /**
     * Display Practical Virtual Classroom for R2026.
     * Uses isolated R26PracticalCourseFile — does NOT touch R2021 course_files table.
     */
    public function show($subjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom    = $this->getClassroom($batchSubject->classroom_id);
        if (!$classroom) abort(404, 'Classroom not found.');

        $students  = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')->orderBy('name', 'asc')->get();
        $labBatches = R26StudentLabBatch::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // R2026 Practical course file (isolated table)
        $practicalCourseFile = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();

        $lessonPlans    = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();
        
        // Dynamically fetch actual date from class log if not set in DB
        $classLogs = DB::table('class_logs_attendance')
            ->whereIn('lesson_plan_id', $lessonPlans->pluck('id'))
            ->whereNotNull('date')
            ->get()
            ->groupBy('lesson_plan_id');

        foreach ($lessonPlans as $lp) {
            if (!$lp->actual_date && isset($classLogs[$lp->id])) {
                $lp->actual_date = $classLogs[$lp->id]->sortByDesc('date')->first()->date;
                if ($lp->status === 'Pending') {
                    $lp->status = 'Completed';
                }
            }
        }

        $experimentLogs = R26PracticalExperimentEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('experiment_no');
        $openEndedLogs  = R26OpenEndedEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $seriesExamLogs = R26PracticalSeriesEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('series_no');
        $seriesExams    = R26PracticalSeriesExam::where('batch_subject_id', $subjectId)->get()->keyBy('exam_name');
        $eseMarks       = R26PracticalEseMark::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Attendance
        $attendanceMarks = [];
        foreach ($students as $student) {
            $total   = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->count();
            $present = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->whereIn('status', ['Present', 'Late'])->count();
            $pct = $total > 0 ? round(($present / $total) * 100, 2) : 100.00;
            $attendanceMarks[$student->reg_no] = ['percentage' => $pct, 'mark' => $this->computeAttendanceMark($pct)];
        }

        $consolidatedScores = $this->buildConsolidatedScores($students, $experimentLogs, $openEndedLogs, $seriesExamLogs, $eseMarks, $attendanceMarks);

        // Fetch all assigned staff members for this lab/subject
        $assignedStaffList = \App\Models\SubjectStaffAssignment::where('batch_subject_id', $subjectId)
            ->with('staffProfile')
            ->get()
            ->map(function($assignment, $idx) {
                $sp = $assignment->staffProfile;
                if (!$sp) return null;
                $role = $sp->designation ?: ($idx === 0 ? 'Lecturer' : 'Demonstrator');
                return [
                    'name' => $sp->name,
                    'designation' => $role
                ];
            })
            ->filter()
            ->values();

        if ($assignedStaffList->isEmpty() && $staff) {
            $assignedStaffList = collect([[
                'name' => $staff->name,
                'designation' => $staff->designation ?: 'Lecturer'
            ]]);
        }

        // Parser mode indicator
        $parseModeLabel = 'AI Off - Local';

        return view('r26_practical.virtual_classroom_practical', compact(
            'batchSubject', 'classroom', 'students', 'labBatches',
            'practicalCourseFile', 'staff', 'assignedStaffList',
            'lessonPlans', 'experimentLogs', 'openEndedLogs',
            'seriesExamLogs', 'seriesExams', 'eseMarks',
            'attendanceMarks', 'consolidatedScores', 'parseModeLabel'
        ));
    }

    /**
     * Upload and parse Revision 2026 Practical Syllabus PDF.
     * Stores in dedicated folder: r26_practical_syllabi/
     * Saves to isolated r26_practical_course_files table.
     * Does NOT touch anything in the R2021 system.
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Session expired. Please log in again.']);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            // 1. Store file in dedicated R26 practical folder (never r26_syllabi which is shared)
            $file = $request->file('syllabus_file');
            $storedPath = $file->store('r26_practical_syllabi', 'public');
            $fullPath = storage_path('app/public/' . $storedPath);

            // 2. Run Python parser (same parser used by R26 Theory — it extracts COs, CO-PO, credits)
            $pyPath = base_path('app/Services/r26_syllabus_parser.py');
            $pythonBin = file_exists('/usr/bin/python3') ? '/usr/bin/python3' : 'python3';
            $sitePkg = '/home/carmel/.local/lib/python3.14/site-packages';
            $command = "PYTHONIOENCODING=utf-8 PYTHONPATH={$sitePkg}:\$PYTHONPATH {$pythonBin} " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPath) . " 2>&1";
            $jsonOutput = shell_exec($command);
            $parsedResult = json_decode($jsonOutput, true);

            // Default values
            $courseTitle    = $batchSubject->subject_name;
            $courseCode     = $batchSubject->subject_code;
            $credits        = 1;
            $teachingScheme = '0:0:2:0';
            $cieMks         = 60;
            $eseMks         = 40;
            $totalHours     = 30;
            $cosArray       = [];
            $copoMatrix     = [];

            if (!empty($parsedResult) && $parsedResult['status'] === 'SUCCESS') {
                $d = $parsedResult['data'];
                if (!empty($d['course_title']))   $courseTitle    = $d['course_title'];
                if (!empty($d['course_code']))     $courseCode     = $d['course_code'];
                if (!empty($d['credits']))         $credits        = (int)$d['credits'];
                if (!empty($d['teaching_scheme'])) $teachingScheme = $d['teaching_scheme'];
                if (!empty($d['cie_marks']))       $cieMks         = (int)$d['cie_marks'];
                if (!empty($d['ese_marks']))       $eseMks         = (int)$d['ese_marks'];
                if (!empty($d['total_hours']))     $totalHours     = (int)$d['total_hours'];
                if (!empty($d['cos']))             $cosArray       = $d['cos'];
                if (!empty($d['copo_matrix']))     $copoMatrix     = $d['copo_matrix'];
            }

            // 3. Extract full PDF text for experiment parsing (using inline python dump)
            $dumpPy = storage_path('app/scratch_r26_dump.py');
            file_put_contents($dumpPy,
                "import pypdf, sys\n" .
                "sys.stdout.reconfigure(encoding='utf-8')\n" .
                "r = pypdf.PdfReader(sys.argv[1])\n" .
                "print(''.join([p.extract_text() for p in r.pages]))\n"
            );
            $cmdDump = "PYTHONIOENCODING=utf-8 PYTHONPATH={$sitePkg}:\$PYTHONPATH {$pythonBin} " . escapeshellarg($dumpPy) . " " . escapeshellarg($fullPath) . " 2>&1";
            $pdfText = shell_exec($cmdDump);
            @unlink($dumpPy);

            // 4. Parse experiments from "Detailed Syllabus" section
            $experiments = $this->parseExperimentsFromText((string)$pdfText);

            // 5. Build CO-PO payload (includes scheme info alongside the mapping)
            $copoPayload = [
                'credits'        => $credits,
                'l_t_p_r'        => $teachingScheme,
                'cie_marks'      => $cieMks,
                'ese_marks'      => $eseMks,
                'total_hours'    => $totalHours,
                'mappings'       => $copoMatrix,
            ];

            // 6. Save into ISOLATED r26_practical_course_files table
            R26PracticalCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path'   => $storedPath,
                    'course_title'        => $courseTitle,
                    'course_code'         => $courseCode,
                    'credits'             => $credits,
                    'teaching_scheme'     => $teachingScheme,
                    'cie_marks'           => $cieMks,
                    'ese_marks'           => $eseMks,
                    'total_hours'         => $totalHours,
                    'parsed_cos'          => json_encode($cosArray),
                    'parsed_copo'         => json_encode($copoPayload),
                    'parsed_experiments'  => json_encode($experiments),
                    'manual_experiments'  => null, // reset manual on re-upload
                    'status'              => 'Draft',
                ]
            );

            return response()->json([
                'status'       => 'SUCCESS',
                'message'      => 'Syllabus uploaded & parsed! Found ' . count($cosArray) . ' COs and ' . count($experiments) . ' experiments.',
                'reload'       => true,
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Upload error: ' . $e->getMessage()]);
        }
    }

    /**
     * PHP regex parser for practical experiments from PDF text.
     * Pattern: lines ending with   CO<n>  <Taxonomy>  <hours>
     */
    private function parseExperimentsFromText(string $pdfText): array
    {
        $pos = stripos($pdfText, 'Detailed Syllabus');
        if ($pos === false) $pos = stripos($pdfText, 'Course Outline');
        $syllabusText = ($pos !== false) ? substr($pdfText, $pos) : $pdfText;

        $lines       = explode("\n", $syllabusText);
        $experiments = [];
        $block       = [];
        $counter     = 1;

        foreach ($lines as $line) {
            $t = trim($line);
            if ($t === '') continue;

            if (preg_match('/(CO\d+)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s+(\d+)\s*$/i', $t, $m)) {
                $co       = strtoupper($m[1]);
                $taxonomy = $m[2];
                $hours    = (int)$m[3];

                // Strip the CO/taxonomy/hours suffix from the line
                $clean = trim(preg_replace('/(CO\d+)\s+(Remember|Understand|Apply|Analyze|Evaluate|Create)\s+(\d+)\s*$/i', '', $t));
                if ($clean !== '') $block[] = $clean;

                $blockText = implode(' ', $block);
                // Strip boilerplate headers
                $blockText = preg_replace('/Module\s+[IVX]+\s*/i', '', $blockText);
                $blockText = preg_replace('/Expt\s*No\.?\s*Experiment\s*name\s*Practical\s*Outcome\s*Mapped\s*CO\s*Taxonomy\s*Level\s*Instructional\s*Hours/i', '', $blockText);
                $blockText = preg_replace('/Expt\s*Hours\s*/i', '', $blockText);
                $blockText = preg_replace('/Diploma Curriculum Revision 2026 \d+ Page #\d+\s*/i', '', $blockText);
                $blockText = trim($blockText);

                $exptNo = '';
                $title  = '';
                $desc   = '';

                if (preg_match('/^(\d+)\s+(.+)$/s', $blockText, $mm)) {
                    $exptNo    = 'Expt ' . $mm[1];
                    $remaining = trim($mm[2]);
                    $counter   = (int)$mm[1] + 1;
                    // Try to split title from description on period
                    $dotPos = strpos($remaining, '. ');
                    if ($dotPos !== false && $dotPos > 5) {
                        $title = trim(substr($remaining, 0, $dotPos));
                        $desc  = trim(substr($remaining, $dotPos + 2));
                    } else {
                        $title = $remaining;
                    }
                } elseif (stripos($blockText, 'Series Test') !== false || stripos($blockText, 'Series Exam') !== false) {
                    $exptNo = 'Test';
                    $title  = $blockText;
                } elseif (stripos($blockText, 'Open-Ended') !== false || stripos($blockText, 'Open Ended') !== false) {
                    $exptNo = 'OEE';
                    $title  = $blockText;
                } else {
                    $exptNo = 'Expt ' . $counter;
                    $title  = $blockText;
                    $counter++;
                }

                $experiments[] = [
                    'expt_no'     => $exptNo,
                    'title'       => $title,
                    'description' => $desc,
                    'co'          => $co,
                    'taxonomy'    => $taxonomy,
                    'hours'       => $hours,
                ];
                $block = [];

            } else {
                // Skip header lines
                if (preg_match('/Expt\s*No|Detailed Syllabus|Practical Outcome|Instructional/i', $t)) continue;
                $block[] = $t;
            }
        }
        return $experiments;
    }

    /**
     * Save CO-PO mapping matrix.
     */
    public function saveCoPoMapping(Request $request, $subjectId)
    {
        $pcf = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$pcf) return response()->json(['success' => false, 'message' => 'Upload syllabus first.']);

        $mappings = $request->input('mappings', []);
        $copo = json_decode($pcf->parsed_copo, true) ?: [];
        $copo['mappings'] = $mappings;
        $pcf->parsed_copo = json_encode($copo);
        $pcf->save();

        return response()->json(['success' => true, 'message' => 'CO-PO Articulation Matrix saved!']);
    }

    /**
     * Save manually edited experiments list (stored in manual_experiments column).
     * Does not overwrite parsed_experiments from PDF.
     */
    public function saveExperimentsList(Request $request, $subjectId)
    {
        $pcf = R26PracticalCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $experiments = $request->input('experiments', []);
        $pcf->manual_experiments = json_encode($experiments);
        $pcf->save();

        // Sync into practical_experiments table so future/other batches can reuse databank
        if (is_array($experiments)) {
            foreach ($experiments as $exp) {
                $exptNo = $exp['expt_no'] ?? '';
                if (!$exptNo) continue;
                \DB::table('practical_experiments')->updateOrInsert(
                    [
                        'batch_subject_id' => $subjectId,
                        'experiment_no'    => $exptNo
                    ],
                    [
                        'title'      => $exp['title'] ?? '',
                        'co_tag'     => $exp['co'] ?? 'CO1',
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Experiments list saved successfully and preserved in Databank!']);
    }

    /**
     * Generate day-wise lesson plans from experiment list.
     * Single batch: 1 row per experiment.
     * Split batch: 2 rows (Batch A, Batch B) per experiment.
     * Always adds 2 extra days for Series Test 1 & 2 at the end.
     */
    public function generateLessonPlan(Request $request, $subjectId)
    {
        $pcf  = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();
        $mode = $request->input('mode', 'single');
        $selectedBatch = $request->input('batch', 'Full');

        $experiments = $pcf ? $pcf->getExperimentsArray() : [];
        if (empty($experiments)) {
            return response()->json(['success' => false, 'message' => 'No experiments configured. Upload syllabus or import defaults first.']);
        }

        LessonPlan::where('batch_subject_id', $subjectId)->delete();

        // Determine target sub-batches based on batch and mode inputs
        if ($selectedBatch === 'A') {
            $targetSubBatches = ['Batch A'];
        } elseif ($selectedBatch === 'B') {
            $targetSubBatches = ['Batch B'];
        } else {
            $targetSubBatches = ($mode === 'split') ? ['Batch A', 'Batch B'] : ['Whole'];
        }

        // 1. Filter out non-experiment rows (like Tests or OEE that were parsed from syllabus)
        $filteredExpts = array_values(array_filter($experiments, function($expt) {
            $exptNo = $expt['expt_no'] ?? '';
            return $exptNo !== 'Test' && $exptNo !== 'OEE';
        }));

        $totalProposed = (int)($pcf->total_hours ?? 30);
        if ($totalProposed <= 0) $totalProposed = 30;

        $numExpts = count($filteredExpts);
        $assignedHours = [];

        if ($numExpts > 0) {
            $targetHours = ($mode === 'split') ? (int)($totalProposed / 2) : $totalProposed;

            // Distribute targetHours among the filtered experiments
            $baseHours = (int)($targetHours / $numExpts);
            if ($baseHours < 1) $baseHours = 1;
            
            $remainder = $targetHours - ($baseHours * $numExpts);
            
            for ($i = 0; $i < $numExpts; $i++) {
                $h = $baseHours;
                if ($remainder > 0) {
                    $h++;
                    $remainder--;
                } elseif ($remainder < 0 && $h > 1) {
                    $h--;
                    $remainder++;
                }
                $assignedHours[$i] = $h;
            }
        }

        $dayNo = 1;

        // 2. Insert Experiment Rows
        foreach ($filteredExpts as $idx => $expt) {
            $title  = $expt['title']   ?? ('Experiment ' . ($idx + 1));
            $exptNo = $expt['expt_no'] ?? ('Expt ' . ($idx + 1));
            $co     = $expt['co']      ?? 'CO1';
            
            $hours = $assignedHours[$idx] ?? 2;

            // Split into chunks of max 2 hours
            $chunks = [];
            $hTemp = $hours;
            while ($hTemp > 0) {
                if ($hTemp >= 2) {
                    $chunks[] = 2;
                    $hTemp -= 2;
                } else {
                    $chunks[] = 1;
                    $hTemp -= 1;
                }
            }

            foreach ($chunks as $chunkHours) {
                foreach ($targetSubBatches as $bName) {
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $dayNo++,
                        'co_id'            => $co,
                        'topic_content'    => "{$exptNo}: {$title}",
                        'allocated_hours'  => $chunkHours,
                        'pedagogy'         => 'Practical',
                        'sub_batch'        => $bName,
                        'status'           => 'Pending',
                    ]);
                }
            }
        }

        // 3. Always add two more days with lab for series exams at the end (1 hour each)
        foreach ($targetSubBatches as $bName) {
            LessonPlan::create([
                'batch_subject_id' => $subjectId,
                'day_no'           => $dayNo++,
                'co_id'            => 'CO2',
                'topic_content'    => 'Series 1 (Practical Exam)',
                'allocated_hours'  => 1,
                'pedagogy'         => 'Exam',
                'sub_batch'        => $bName,
                'status'           => 'Pending',
            ]);
        }
        foreach ($targetSubBatches as $bName) {
            LessonPlan::create([
                'batch_subject_id' => $subjectId,
                'day_no'           => $dayNo++,
                'co_id'            => 'CO4',
                'topic_content'    => 'Series 2 (Practical Exam)',
                'allocated_hours'  => 1,
                'pedagogy'         => 'Exam',
                'sub_batch'        => $bName,
                'status'           => 'Pending',
            ]);
        }

        $totalRows = LessonPlan::where('batch_subject_id', $subjectId)->count();
        return response()->json(['success' => true, 'message' => "Lesson plan generated! {$totalRows} entries created (mode: {$mode}).", 'reload' => true]);
    }

    /**
     * Bulk update lesson plan day rows.
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $plans = $request->input('plans', []);
        foreach ($plans as $id => $data) {
            $actualDate = $data['actual_date'] ?? null;
            $status     = $data['status'] ?? 'Pending';
            if ($actualDate && $status === 'Pending') {
                $status = 'Completed';
            }

            $updateData = [
                'topic_content'   => $data['topic_content'] ?? '',
                'co_id'           => $data['co_id'] ?? 'CO1',
                'allocated_hours' => (int)($data['allocated_hours'] ?? 1),
                'pedagogy'        => $data['pedagogy'] ?? 'Practical',
                'proposed_date'   => $data['proposed_date'] ?? null,
                'actual_date'     => $actualDate,
                'status'          => $status,
            ];

            if (isset($data['sub_batch'])) {
                $updateData['sub_batch'] = $data['sub_batch'];
            }

            LessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->update($updateData);
        }
        return response()->json(['success' => true, 'message' => 'Lesson plan saved!']);
    }

    public function deleteLessonPlanRow(Request $request, $subjectId, $planId)
    {
        LessonPlan::where('id', $planId)
            ->where('batch_subject_id', $subjectId)
            ->delete();

        return response()->json(['success' => true, 'status' => 'SUCCESS', 'message' => 'Row deleted successfully.']);
    }

    /**
     * Save Table 2.2 Experiment Marks (Continuous Log).
     */
    public function saveExperimentMarks(Request $request, $subjectId)
    {
        $staff  = $this->getStaff();
        $expNo  = $request->input('experiment_no', 'Exp 1');
        $title  = $request->input('title', '');
        $marks  = $request->input('marks', []);

        foreach ($marks as $regNo => $c) {
            $c1 = (float)($c['c1'] ?? 0);  // max 10
            $c2 = (float)($c['c2'] ?? 0);  // max 10
            $c3 = (float)($c['c3'] ?? 0);  // max 5
            $c4 = (float)($c['c4'] ?? 0);  // max 10
            $c5 = (float)($c['c5'] ?? 0);  // max 10
            $c6 = (float)($c['c6'] ?? 0);  // max 5
            $total = $c1 + $c2 + $c3 + $c4 + $c5 + $c6;

            R26PracticalExperimentEvaluation::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'experiment_no' => $expNo, 'reg_no' => $regNo],
                [
                    'title'                  => $title,
                    'prep_punctuality'       => $c1,
                    'setup_procedure'        => $c2,
                    'observation_recording'  => $c3,
                    'analysis_interpretation'=> $c4,
                    'viva_voce'              => $c5,
                    'teamwork_discipline'    => $c6,
                    'total_score_50'         => $total,
                    'assessor_mobile_no'     => $staff?->mobile_no,
                ]
            );
        }
        return response()->json(['success' => true, 'message' => 'Continuous log saved!']);
    }

    /**
     * Save Table 2.3 Open-Ended Project Marks.
     */
    public function saveOpenEndedMarks(Request $request, $subjectId)
    {
        $staff = $this->getStaff();
        $marks = $request->input('marks', []);
        foreach ($marks as $regNo => $c) {
            $c1 = (float)($c['c1'] ?? 0); $c2 = (float)($c['c2'] ?? 0); $c3 = (float)($c['c3'] ?? 0);
            $c4 = (float)($c['c4'] ?? 0); $c5 = (float)($c['c5'] ?? 0);
            R26OpenEndedEvaluation::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                [
                    'project_title'           => $c['title'] ?? 'Open-ended Project',
                    'originality_relevance'   => $c1,
                    'objectives_plan'         => $c2,
                    'execution_recording'     => $c3,
                    'analysis_presentation'   => $c4,
                    'teamwork_innovation'     => $c5,
                    'total_score_50'          => $c1 + $c2 + $c3 + $c4 + $c5,
                    'assessor_mobile_no'      => $staff?->mobile_no,
                ]
            );
        }
        return response()->json(['success' => true, 'message' => 'Open-ended marks saved!']);
    }

    /**
     * Save Table 3.1 Series Exam Marks.
     */
    public function saveSeriesExamMarks(Request $request, $subjectId)
    {
        $staff    = $this->getStaff();
        $seriesNo = $request->input('series_no', 'Series 1');
        $marks    = $request->input('marks', []);
        foreach ($marks as $regNo => $c) {
            $c1 = (float)($c['c1'] ?? 0); $c2 = (float)($c['c2'] ?? 0); $c3 = (float)($c['c3'] ?? 0);
            $c4 = (float)($c['c4'] ?? 0); $c5 = (float)($c['c5'] ?? 0);
            R26PracticalSeriesEvaluation::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'series_no' => $seriesNo, 'reg_no' => $regNo],
                [
                    'writeup_procedure'  => $c1,
                    'setup_execution'    => $c2,
                    'observation_result' => $c3,
                    'viva_voce'          => $c4,
                    'record_completion'  => $c5,
                    'total_score_40'     => $c1 + $c2 + $c3 + $c4 + $c5,
                    'assessor_mobile_no' => $staff?->mobile_no,
                ]
            );
        }
        return response()->json(['success' => true, 'message' => 'Series exam marks saved!']);
    }

    /**
     * Configure Series Exam Question Blueprint.
     */
    public function configureSeriesExam(Request $request, $subjectId)
    {
        $examName = $request->input('exam_name', 'Series 1');
        R26PracticalSeriesExam::updateOrCreate(
            ['batch_subject_id' => $subjectId, 'exam_name' => $examName],
            [
                'co_tags'          => $request->input('co_tags', []),
                'max_marks'        => (int)$request->input('max_marks', 40),
                'duration_minutes' => (int)$request->input('duration_minutes', 120),
                'question_outline' => $request->input('question_outline', ''),
                'locked'           => false,
            ]
        );
        return response()->json(['success' => true, 'message' => 'Series exam blueprint saved!']);
    }

    /**
     * Save ESE Practical Marks (out of 40).
     */
    public function saveEseMarks(Request $request, $subjectId)
    {
        $staff = $this->getStaff();
        foreach ($request->input('marks', []) as $regNo => $val) {
            R26PracticalEseMark::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                ['ese_score' => (float)$val, 'assessor_mobile_no' => $staff?->mobile_no]
            );
        }
        return response()->json(['success' => true, 'message' => 'ESE marks saved!']);
    }

    /**
     * Assign student to Batch A / Batch B for lab splits.
     */
    public function assignLabBatch(Request $request, $subjectId)
    {
        $regNo    = $request->input('reg_no');
        $labBatch = $request->input('lab_batch');
        if (empty($labBatch)) {
            R26StudentLabBatch::where('batch_subject_id', $subjectId)->where('reg_no', $regNo)->delete();
        } else {
            R26StudentLabBatch::updateOrCreate(
                ['batch_subject_id' => $subjectId, 'reg_no' => $regNo],
                ['lab_batch' => $labBatch]
            );
        }
        return response()->json(['success' => true, 'message' => 'Lab batch updated!']);
    }

    /**
     * Course File Portfolio Checklist (R26 Practical - 25 docs).
     */
    public function viewCourseFile($subjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) return redirect('/')->with('error', 'Please log in.');

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom    = $this->getClassroom($batchSubject->classroom_id);

        $courseFile = R26CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId, 'academic_year' => '2026-2027'],
            ['status' => 'Draft']
        );

        $docNames = [
            1  => 'Class Timetable (current semester Program timetable)',
            2  => 'Faculty Workload',
            3  => 'Student List with register numbers',
            4  => 'Course Syllabus with Recommended Books (SITTTR)',
            5  => 'Course Information Sheet',
            6  => 'Course Outcomes & CO-PO Mapping',
            7  => 'Academic Calendar & Semester Layout',
            8  => 'Course Plan / Lesson Planner',
            9  => 'Course Log and Attendance Register',
            10 => 'Practical Series Exam Question Papers with Mark Splitup / Scheme',
            11 => 'Practical Series Examination Result Analysis (NBA)',
            12 => 'Weaker Student Coaching Schedule and Proof',
            13 => 'Teaching and Learning Methods Proof',
            14 => 'Open-Ended Experiment Rubrics and Marks',
            15 => 'Internal Marks – SBTE (CIA Register)',
            16 => 'Grade Sheet – Proof of CO Evaluations',
            17 => 'External Practical Exam Question Papers / Question Bank',
            18 => 'SBTE External Examination Result',
            19 => 'Attainment of Course Outcomes (CO) – CO-PO-PSO Map',
            20 => 'Attainment of PO/PSO Report',
            21 => 'Mid Semester Survey & Report',
            22 => 'End Semester / Course Exit Survey & Report',
            23 => 'Series Exam Sample Answer Scripts / Records',
            24 => 'Open-Ended Experiment Sample Records',
            25 => 'Others',
        ];

        foreach ($docNames as $num => $name) {
            R26CourseFileDocument::firstOrCreate(
                ['r26_course_file_id' => $courseFile->id, 'document_number' => $num],
                ['document_name' => $name, 'is_checked' => false]
            );
        }

        $documents = R26CourseFileDocument::where('r26_course_file_id', $courseFile->id)
            ->orderBy('document_number')->get();

        return view('r26_practical.course_file_preparation', compact('batchSubject', 'classroom', 'courseFile', 'documents'));
    }

    /**
     * Save a course file document check/remark.
     */
    public function saveCourseFileDoc(Request $request, $subjectId)
    {
        R26CourseFileDocument::where('id', $request->input('doc_id'))->update([
            'is_checked' => $request->input('is_checked', false),
            'remarks'    => $request->input('remarks', ''),
        ]);
        return response()->json(['success' => true, 'message' => 'Checklist updated!']);
    }

    /**
     * Upload and attach a physical file to a course file document row.
     */
    public function uploadCourseFileDocAttachment(Request $request, $subjectId)
    {
        $docId = $request->input('doc_id');
        $request->validate(['attachment' => 'required|file|mimes:pdf,jpg,png,zip|max:10240']);

        $file     = $request->file('attachment');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/r26_practical_course_docs', $fileName);
        $publicUrl = 'storage/r26_practical_course_docs/' . $fileName;

        R26CourseFileDocument::where('id', $docId)->update(['data_payload' => $publicUrl, 'is_checked' => true]);
        return response()->json(['success' => true, 'attachment_url' => '/' . $publicUrl]);
    }

    /**
     * Print report views (cia, plan, attainment).
     */
    public function printReport($subjectId, $type)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom    = $this->getClassroom($batchSubject->classroom_id);
        $students     = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderBy('roll_no')->get();
        $labBatches   = R26StudentLabBatch::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $lessonPlans  = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();
        $experimentLogs = R26PracticalExperimentEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('experiment_no');
        $openEndedLogs  = R26OpenEndedEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $seriesExamLogs = R26PracticalSeriesEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('series_no');
        $eseMarks       = R26PracticalEseMark::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $attendanceMarks = [];
        foreach ($students as $st) {
            $total   = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $st->reg_no)->count();
            $present = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $st->reg_no)->whereIn('status', ['Present', 'Late'])->count();
            $pct = $total > 0 ? round(($present / $total) * 100, 2) : 100.00;
            $attendanceMarks[$st->reg_no] = ['percentage' => $pct, 'mark' => $this->computeAttendanceMark($pct)];
        }

        $consolidatedScores = $this->buildConsolidatedScores($students, $experimentLogs, $openEndedLogs, $seriesExamLogs, $eseMarks, $attendanceMarks);

        if ($type === 'cia') {
            return view('r26_practical.reports_print', compact('batchSubject', 'classroom', 'students', 'labBatches', 'attendanceMarks', 'consolidatedScores'));
        }

        if ($type === 'plan') {
            return view('r26_practical.lesson_plan_print', compact('batchSubject', 'classroom', 'lessonPlans'));
        }

        if ($type === 'attainment') {
            $pcf     = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();
            $copo    = $pcf ? json_decode($pcf->parsed_copo, true) : [];
            $mappings = $copo['mappings'] ?? [];

            $directStats = [];
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $totalAssessed = 0; $totalMet = 0;
                foreach ($students as $st) {
                    $sc = $consolidatedScores[$st->reg_no] ?? [];
                    $coScore = match($coTag) {
                        'CO1' => ($sc['scaled_open_ended_10'] ?? 0),
                        'CO2' => ($sc['scaled_lab_work_30'] ?? 0) * 0.5,
                        'CO3' => ($sc['scaled_lab_work_30'] ?? 0) * 0.5,
                        'CO4' => ($sc['scaled_series_15'] ?? 0),
                        default => 0
                    };
                    $eseCo = ($sc['ese_score_40'] ?? 0) / 4;
                    $pct   = (($coScore + $eseCo) / 25) * 100;
                    if ($pct >= 50) $totalMet++;
                    $totalAssessed++;
                }
                $met  = $totalAssessed > 0 ? ($totalMet / $totalAssessed) * 100 : 0;
                $lvl  = $met >= 70 ? 3 : ($met >= 60 ? 2 : ($met >= 50 ? 1 : 0));
                $directStats[$coTag] = ['met_percent' => round($met, 1), 'level' => $lvl];
            }

            $exitResponses = DB::table('student_course_exit_responses')
                ->join('course_exit_surveys', 'student_course_exit_responses.exit_survey_id', '=', 'course_exit_surveys.id')
                ->where('course_exit_surveys.batch_subject_id', $subjectId)->get();
            $n = count($exitResponses);
            $indirectStats = [];
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $lvl = 0.0;
                if ($n > 0) {
                    $avg = match($coTag) {
                        'CO1' => ($exitResponses->avg('co1_q1') + $exitResponses->avg('co1_q2')) / 2,
                        'CO2' => ($exitResponses->avg('co2_q3') + $exitResponses->avg('co2_q4')) / 2,
                        'CO3' => ($exitResponses->avg('co3_q5') + $exitResponses->avg('co3_q6')) / 2,
                        'CO4' => ($exitResponses->avg('co4_q7') + $exitResponses->avg('co4_q8') + $exitResponses->avg('co4_q9')) / 3,
                        default => 0
                    };
                    $lvl = round($avg, 2);
                }
                $indirectStats[$coTag] = ['level' => $lvl];
            }

            $combinedStats = [];
            $poAttainments = [];
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $combined = 0.80 * $directStats[$coTag]['level'] + 0.20 * $indirectStats[$coTag]['level'];
                $combinedStats[$coTag] = round($combined, 2);
            }
            for ($p = 1; $p <= 11; $p++) {
                $poName = "PO$p"; $sw = 0; $sa = 0;
                foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                    $corr = isset($mappings[$coTag][$poName]) ? (int)$mappings[$coTag][$poName] : 0;
                    if ($corr > 0) { $sw += $corr; $sa += $combinedStats[$coTag] * $corr; }
                }
                $poAttainments[$poName] = ['value' => $sw > 0 ? round($sa / $sw, 2) : 0.0, 'weight' => $sw];
            }

            return view('r26_practical.attainment_report_print', compact('batchSubject', 'classroom', 'directStats', 'indirectStats', 'combinedStats', 'poAttainments', 'mappings'));
        }

        abort(404, 'Report type not found.');
    }
}
