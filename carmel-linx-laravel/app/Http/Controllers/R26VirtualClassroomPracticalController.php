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
use App\Models\CourseFile;
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
     * Compute attendance mark per student as per Table 2.1 (Rev 2026 rubric).
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

            // 3. Combined CE (40M) = Daywork 30M + Open Ended 10M
            $ceTotal40 = round($scaled30 + $scaled10, 2);

            // 4. Series avg → scale to 15M (Two series tests in 40 -> avg in 40 -> converted to 15)
            $seriesNums = [];
            foreach (['Series 1', 'Series 2'] as $sName) {
                if (isset($seriesExamLogs[$sName])) {
                    $log = $seriesExamLogs[$sName]->where('reg_no', $regNo)->first();
                    if ($log) $seriesNums[] = floatval($log->total_score_40);
                }
            }
            $avg40 = count($seriesNums) > 0 ? array_sum($seriesNums) / count($seriesNums) : 0;
            $scaled15 = round(($avg40 / 40) * 15, 2);

            // 5. Attendance → 5M (default 0 if no upload/log)
            $att5 = $attendanceMarks[$regNo]['mark'] ?? 0;

            // 6. Total CIA (60M) strictly rounded off without decimal!
            $cia60 = (int)round($scaled30 + $scaled10 + $scaled15 + $att5);

            // 7. ESE Mark (40M)
            $eseRec = $eseMarks->get($regNo);
            $ese40 = $eseRec ? floatval($eseRec->ese_score) : 0.00;
            $eseAbsent = $eseRec ? (bool)$eseRec->is_absent : false;

            // 8. Grand Total (100M)
            $grandTotal100 = (int)round($cia60 + $ese40);

            // 9. SBTE Grade Calculation
            if ($eseAbsent) {
                $grade = 'AB';
            } elseif ($ese40 < 16.0 && $ese40 > 0) {
                $grade = 'F';
            } elseif ($grandTotal100 >= 90) {
                $grade = 'S';
            } elseif ($grandTotal100 >= 80) {
                $grade = 'A';
            } elseif ($grandTotal100 >= 70) {
                $grade = 'B';
            } elseif ($grandTotal100 >= 60) {
                $grade = 'C';
            } elseif ($grandTotal100 >= 50) {
                $grade = 'D';
            } elseif ($grandTotal100 >= 40) {
                $grade = 'E';
            } else {
                $grade = 'F';
            }

            $scores[$regNo] = [
                'raw_exp_avg'          => round($avgExp50, 2),
                'scaled_lab_work_30'   => $scaled30,
                'raw_open_ended'       => round($open50, 2),
                'scaled_open_ended_10' => $scaled10,
                'ce_total_40'          => $ceTotal40,
                'raw_series_avg'       => round($avg40, 2),
                'scaled_series_15'     => $scaled15,
                'attendance_mark_5'    => $att5,
                'total_cia_60'         => $cia60,
                'ese_score_40'         => $ese40,
                'ese_absent'           => $eseAbsent,
                'grand_total_100'      => $grandTotal100,
                'grade'                => $grade,
            ];
        }
        return $scores;
    }

    /**
     * Compute direct, indirect and PO attainment data for Revision 2026 Practical.
     */
    private function computeAttainmentData($batchSubject, $students, $consolidatedScores): array
    {
        $subjectId = $batchSubject->id;
        $pcf     = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();
        $copo    = ($pcf && !empty($pcf->parsed_copo)) ? (is_array($pcf->parsed_copo) ? $pcf->parsed_copo : (json_decode($pcf->parsed_copo, true) ?: [])) : [];
        $mappings = $copo['mappings'] ?? [];

        $cf = CourseFile::where('batch_subject_id', $subjectId)->first();
        $settings = [];
        if ($cf && $cf->attainment_settings) {
            $settings = is_string($cf->attainment_settings) ? json_decode($cf->attainment_settings, true) : $cf->attainment_settings;
        }
        $eseConfig = $settings['ese_config'] ?? [];
        $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3Val = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2Val = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1Val = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));

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
                if ($pct >= $cieThreshold) $totalMet++;
                $totalAssessed++;
            }
            $met  = $totalAssessed > 0 ? ($totalMet / $totalAssessed) * 100 : 0;
            $lvl  = \App\Services\AttainmentService::calculateBatchLevel($met, $lvl3Val, $lvl2Val, $lvl1Val);
            $directStats[$coTag] = ['met_percent' => round($met, 1), 'level' => $lvl];
        }

        $exitSurvey = DB::table('course_exit_surveys')->where('batch_subject_id', $subjectId)->first();
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
            $combined = \App\Services\AttainmentService::calculateOverallAttainment($directStats[$coTag]['level'], $indirectStats[$coTag]['level'], 0.80, 0.20);
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

        return [
            'directStats' => $directStats,
            'indirectStats' => $indirectStats,
            'combinedStats' => $combinedStats,
            'poAttainments' => $poAttainments,
            'mappings' => $mappings,
            'exitSurvey' => $exitSurvey,
            'exitResponsesCount' => $n,
            'cieThreshold' => $cieThreshold,
            'targetStudentPercent' => $targetStudentPercent,
        ];
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
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get();
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

        // Official Subject Attendance (TEAMS upload + override support)
        $subjectOfficialAttendance = DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        // Attendance
        $attendanceMarks = [];
        foreach ($students as $student) {
            $stOfficial = $subjectOfficialAttendance->get($student->reg_no)
                ?: (!empty($student->sbte_reg_no) ? $subjectOfficialAttendance->get($student->sbte_reg_no) : null);

            $teamsPct = null;
            if ($stOfficial) {
                if ($stOfficial->teams_percentage !== null) {
                    $teamsPct = (float)$stOfficial->teams_percentage;
                } elseif ($stOfficial->final_percentage !== null) {
                    $teamsPct = (float)$stOfficial->final_percentage;
                }
            }

            $total   = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->count();
            $present = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->whereIn('status', ['Present', 'Late'])->count();
            $logPct = $total > 0 ? round(($present / $total) * 100, 2) : null;

            $rawPct = $teamsPct !== null ? $teamsPct : $logPct;

            $overridePct = null;
            if ($stOfficial && $stOfficial->override_percentage !== null && $stOfficial->override_percentage !== '') {
                $overridePct = (float)$stOfficial->override_percentage;
            }

            $effectivePct = $overridePct !== null ? $overridePct : $rawPct;
            $attMark = $effectivePct !== null ? $this->computeAttendanceMark($effectivePct) : 0;

            $attendanceMarks[$student->reg_no] = [
                'teams_percentage'    => $teamsPct,
                'raw_percentage'      => $rawPct,
                'override_percentage' => $overridePct,
                'effective_percentage'=> $effectivePct,
                'percentage'          => $effectivePct,
                'mark'                => $attMark,
            ];
        }

        $consolidatedScores = $this->buildConsolidatedScores($students, $experimentLogs, $openEndedLogs, $seriesExamLogs, $eseMarks, $attendanceMarks);
        $attainmentData     = $this->computeAttainmentData($batchSubject, $students, $consolidatedScores);

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

        $userRole = Session::get('userRole');
        $dashboardUrl = match ($userRole) {
            'Superadmin' => '/dashboard/superadmin',
            'Chairman' => '/dashboard/chairman',
            'Admin' => '/dashboard/admin',
            'Principal' => '/dashboard/principal',
            'HOD' => '/dashboard/hod',
            'Tutor' => '/dashboard/tutor',
            'General_Coordinator_Aided' => '/dashboard/general-coordinator-aided',
            'General_Coordinator_SF' => '/dashboard/general-coordinator-sf',
            'Academic_Coordinator' => '/dashboard/academic-coordinator',
            'Demonstrator' => '/dashboard/demonstrator',
            'Trade_Instructor', 'Trade Instructor' => '/dashboard/tradeinstructor',
            'Workshop_Superintendent', 'Workshop' => '/dashboard/workshop',
            default => '/dashboard/lecturer',
        };

        return view('r26_practical.virtual_classroom_practical', compact(
            'batchSubject', 'classroom', 'students', 'labBatches',
            'practicalCourseFile', 'staff', 'assignedStaffList',
            'lessonPlans', 'experimentLogs', 'openEndedLogs',
            'seriesExamLogs', 'seriesExams', 'eseMarks',
            'attendanceMarks', 'consolidatedScores', 'parseModeLabel',
            'attainmentData', 'dashboardUrl'
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
            if (!$storedPath) {
                throw new \Exception('Failed to store syllabus PDF.');
            }
            $fullPath = storage_path('app/public/' . $storedPath);

            // 2. Run Python parser (extracts metadata, COs, CO-PO, modules, and experiments)
            $pyPath = base_path('app/Services/r26_syllabus_parser.py');
            $pythonBin = file_exists('/usr/bin/python3') ? '/usr/bin/python3' : 'python3';
            $sitePkg = '/home/carmel/.local/lib/python3.14/site-packages';
            $command = "PYTHONIOENCODING=utf-8 PYTHONPATH={$sitePkg}:\$PYTHONPATH {$pythonBin} " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPath) . " 2>&1";
            $jsonOutput = shell_exec($command);
            $parsedResult = json_decode($jsonOutput, true);

            if (!$parsedResult || ($parsedResult['status'] ?? '') !== 'SUCCESS') {
                throw new \Exception($parsedResult['message'] ?? 'Failed to parse syllabus PDF.');
            }

            $d = $parsedResult['data'];
            $courseTitle    = !empty($d['course_title']) ? $d['course_title'] : $batchSubject->subject_name;
            $courseCode     = !empty($d['course_code']) ? $d['course_code'] : $batchSubject->subject_code;
            $credits        = !empty($d['credits']) ? (int)$d['credits'] : 1;
            $teachingScheme = !empty($d['teaching_scheme']) ? $d['teaching_scheme'] : '0:0:2:0';
            $cieMks         = !empty($d['cie_marks']) ? (int)$d['cie_marks'] : 60;
            $eseMks         = !empty($d['ese_marks']) ? (int)$d['ese_marks'] : 40;
            $totalHours     = !empty($d['total_hours']) ? (int)$d['total_hours'] : 30;
            $cosArray       = !empty($d['cos']) ? $d['cos'] : [];
            $copoMatrix     = !empty($d['copo_matrix']) ? $d['copo_matrix'] : [];
            $modulesArray   = !empty($d['modules']) ? $d['modules'] : [];
            $experiments    = !empty($d['experiments']) ? $d['experiments'] : [];

            // Fallback for experiments if python parser returned empty
            if (empty($experiments)) {
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
                $experiments = $this->parseExperimentsFromText((string)$pdfText);
            }

            // 5. Build CO-PO payload (includes scheme info alongside the mapping)
            $copoPayload = [
                'credit'         => $credits,
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
                    'parsed_modules'      => json_encode($modulesArray),
                    'parsed_copo'         => json_encode($copoPayload),
                    'parsed_experiments'  => json_encode($experiments),
                    'manual_experiments'  => null, // reset manual on re-upload
                    'status'              => 'Draft',
                ]
            );

            return response()->json([
                'status'       => 'SUCCESS',
                'message'      => 'Syllabus uploaded & parsed! Found ' . count($cosArray) . ' COs, ' . count($modulesArray) . ' modules, and ' . count($experiments) . ' experiments.',
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
        $copo = (!empty($pcf->parsed_copo)) ? (is_array($pcf->parsed_copo) ? $pcf->parsed_copo : (json_decode($pcf->parsed_copo, true) ?: [])) : [];
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
        if ($selectedBatch === 'A' || $selectedBatch === 'Batch 1') {
            $targetSubBatches = ['Batch 1'];
        } elseif ($selectedBatch === 'B' || $selectedBatch === 'Batch 2') {
            $targetSubBatches = ['Batch 2'];
        } else {
            $targetSubBatches = ($mode === 'split') ? ['Batch 1', 'Batch 2'] : ['Full Batch'];
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
        $savedIds = [];
        foreach ($plans as $id => $data) {
            $actualDate = $data['actual_date'] ?? null;
            $status     = $data['status'] ?? 'Pending';
            if ($actualDate && $status === 'Pending') {
                $status = 'Completed';
            }

            $updateData = [
                'day_no'          => (int)($data['day_no'] ?? 1),
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

            if (str_starts_with((string)$id, 'new_') || !is_numeric($id)) {
                $created = LessonPlan::create(array_merge($updateData, [
                    'batch_subject_id' => $subjectId,
                ]));
                $savedIds[$id] = $created->id;
            } else {
                LessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->update($updateData);
                $savedIds[$id] = $id;
            }
        }
        return response()->json(['success' => true, 'status' => 'SUCCESS', 'message' => 'Lesson plan saved!', 'id_map' => $savedIds]);
    }

    public function deleteLessonPlanRow(Request $request, $subjectId, $planId)
    {
        LessonPlan::where('id', $planId)
            ->where('batch_subject_id', $subjectId)
            ->delete();

        return response()->json(['success' => true, 'status' => 'SUCCESS', 'message' => 'Row deleted successfully.']);
    }

    /**
     * Save Attendance Override for CIA Register (Rev 2026 Practical).
     */
    public function saveAttendanceOverride(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $regNo = $request->input('reg_no');
        $rawOverride = $request->input('override_percentage');

        if ($rawOverride !== null && $rawOverride !== '') {
            $overridePct = min(100.0, max(0.0, (float)$rawOverride));
        } else {
            $overridePct = null;
        }

        $existing = DB::table('subject_official_attendances')
            ->where('batch_subject_id', $subjectId)
            ->where('reg_no', $regNo)
            ->first();

        $rawPct = ($existing && $existing->teams_percentage !== null) ? (float)$existing->teams_percentage : null;
        if ($rawPct === null) {
            $total = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $regNo)->count();
            $present = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $regNo)->whereIn('status', ['Present', 'Late'])->count();
            $rawPct = $total > 0 ? round(($present / $total) * 100, 2) : null;
        }
        $effectivePct = $overridePct !== null ? $overridePct : $rawPct;
        $attMark5 = $effectivePct !== null ? $this->computeAttendanceMark($effectivePct) : 0;

        DB::table('subject_official_attendances')->updateOrInsert(
            [
                'batch_subject_id' => $subjectId,
                'reg_no' => $regNo,
            ],
            [
                'subject_code' => $batchSubject->subject_code,
                'classroom_id' => $batchSubject->classroom_id,
                'override_percentage' => $overridePct,
                'final_percentage' => $effectivePct,
                'attendance_mark' => $attMark5,
                'final_mark' => $attMark5,
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Attendance override saved',
            'override_percentage' => $overridePct,
            'effective_percentage' => $effectivePct,
            'attendance_mark' => $attMark5,
        ]);
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
     * Save Series 1 & Series 2 direct scores in bulk without modal splitup.
     */
    public function saveSeriesBulkDirect(Request $request, $subjectId)
    {
        $staff    = $this->getStaff();
        $assessor = $staff?->mobile_no ?? Session::get('userId');
        $marks    = $request->input('marks', []);

        foreach ($marks as $regNo => $data) {
            if (isset($data['series_1']) && $data['series_1'] !== '' && $data['series_1'] !== null) {
                $score1 = min(40.0, max(0.0, (float)$data['series_1']));
                R26PracticalSeriesEvaluation::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'series_no'        => 'Series 1',
                        'reg_no'           => $regNo,
                    ],
                    [
                        'total_score_40'     => $score1,
                        'assessor_mobile_no' => $assessor,
                    ]
                );
            }

            if (isset($data['series_2']) && $data['series_2'] !== '' && $data['series_2'] !== null) {
                $score2 = min(40.0, max(0.0, (float)$data['series_2']));
                R26PracticalSeriesEvaluation::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'series_no'        => 'Series 2',
                        'reg_no'           => $regNo,
                    ],
                    [
                        'total_score_40'     => $score2,
                        'assessor_mobile_no' => $assessor,
                    ]
                );
            }
        }

        return response()->json([
            'status'  => 'SUCCESS',
            'success' => true,
            'message' => 'Series marks updated successfully!',
        ]);
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
        $students     = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get();
        $labBatches   = R26StudentLabBatch::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $lessonPlans  = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();
        $experimentLogs = R26PracticalExperimentEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('experiment_no');
        $openEndedLogs  = R26OpenEndedEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');
        $seriesExamLogs = R26PracticalSeriesEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('series_no');
        $eseMarks       = R26PracticalEseMark::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Official Subject Attendance (TEAMS upload + override support)
        $subjectOfficialAttendance = DB::table('subject_official_attendances')
            ->where(function ($query) use ($subjectId, $batchSubject) {
                $query->where('batch_subject_id', $subjectId)
                      ->orWhere(function ($q) use ($batchSubject) {
                          $q->where('subject_code', $batchSubject->subject_code)
                            ->where('classroom_id', $batchSubject->classroom_id);
                      });
            })
            ->get()
            ->keyBy('reg_no');

        $attendanceMarks = [];
        foreach ($students as $student) {
            $stOfficial = $subjectOfficialAttendance->get($student->reg_no)
                ?: (!empty($student->sbte_reg_no) ? $subjectOfficialAttendance->get($student->sbte_reg_no) : null);

            $teamsPct = null;
            if ($stOfficial) {
                if ($stOfficial->teams_percentage !== null) {
                    $teamsPct = (float)$stOfficial->teams_percentage;
                } elseif ($stOfficial->final_percentage !== null) {
                    $teamsPct = (float)$stOfficial->final_percentage;
                }
            }

            $total   = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->count();
            $present = DB::table('student_attendance')->where('subject_code', $batchSubject->subject_code)->where('reg_no', $student->reg_no)->whereIn('status', ['Present', 'Late'])->count();
            $logPct = $total > 0 ? round(($present / $total) * 100, 2) : null;

            $rawPct = $teamsPct !== null ? $teamsPct : $logPct;

            $overridePct = null;
            if ($stOfficial && $stOfficial->override_percentage !== null && $stOfficial->override_percentage !== '') {
                $overridePct = (float)$stOfficial->override_percentage;
            }

            $effectivePct = $overridePct !== null ? $overridePct : $rawPct;
            $attMark = $effectivePct !== null ? $this->computeAttendanceMark($effectivePct) : 0;

            $attendanceMarks[$student->reg_no] = [
                'teams_percentage'    => $teamsPct,
                'raw_percentage'      => $rawPct,
                'override_percentage' => $overridePct,
                'effective_percentage'=> $effectivePct,
                'percentage'          => $effectivePct,
                'mark'                => $attMark,
            ];
        }

        $consolidatedScores = $this->buildConsolidatedScores($students, $experimentLogs, $openEndedLogs, $seriesExamLogs, $eseMarks, $attendanceMarks);

        if (in_array($type, ['cia', 'consolidated', 'attendance', 'series', 'ce'])) {
            return view('r26_practical.reports_print', compact(
                'batchSubject', 'classroom', 'students', 'labBatches',
                'attendanceMarks', 'consolidatedScores', 'type',
                'seriesExamLogs', 'experimentLogs', 'openEndedLogs'
            ));
        }

        if ($type === 'plan') {
            $assignedStaffList = $batchSubject->getAssignedFacultyList();
            $facultyNames = $batchSubject->getAssignedFacultyNames();
            return view('r26_practical.lesson_plan_print', compact('batchSubject', 'classroom', 'lessonPlans', 'assignedStaffList', 'facultyNames'));
        }

        if ($type === 'attainment') {
            $pcf     = R26PracticalCourseFile::where('batch_subject_id', $subjectId)->first();
            $copo    = ($pcf && !empty($pcf->parsed_copo)) ? (is_array($pcf->parsed_copo) ? $pcf->parsed_copo : (json_decode($pcf->parsed_copo, true) ?: [])) : [];
            $mappings = $copo['mappings'] ?? [];

            $cf = CourseFile::where('batch_subject_id', $subjectId)->first();
            $settings = [];
            if ($cf && $cf->attainment_settings) {
                $settings = is_string($cf->attainment_settings) ? json_decode($cf->attainment_settings, true) : $cf->attainment_settings;
            }
            $eseConfig = $settings['ese_config'] ?? [];
            $cieThreshold = (float)($eseConfig['cie_threshold_percent'] ?? 50.0);
            $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
            $lvl3Val = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
            $lvl2Val = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
            $lvl1Val = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));

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
                    if ($pct >= $cieThreshold) $totalMet++;
                    $totalAssessed++;
                }
                $met  = $totalAssessed > 0 ? ($totalMet / $totalAssessed) * 100 : 0;
                $lvl  = \App\Services\AttainmentService::calculateBatchLevel($met, $lvl3Val, $lvl2Val, $lvl1Val);
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
                $combined = \App\Services\AttainmentService::calculateOverallAttainment($directStats[$coTag]['level'], $indirectStats[$coTag]['level'], 0.80, 0.20);
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
