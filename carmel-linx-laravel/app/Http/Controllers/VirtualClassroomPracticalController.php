<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\PracticalExperiment;
use App\Models\PracticalExperimentMark;
use App\Models\PracticalEvaluation;
use App\Models\PracticalTest;
use App\Models\PracticalTestMark;
use App\Models\R26StudentLabBatch;
use Illuminate\Support\Facades\Session;
use DB;

/**
 * VirtualClassroomPracticalController
 *
 * Desktop Virtual Lab for R2021 Practical/Lab subjects.
 * Uses the canonical R2021 model set (PracticalExperiment, PracticalExperimentMark,
 * PracticalEvaluation, PracticalTest, PracticalTestMark) — same tables as the mobile
 * controller — ensuring both faculty (on any device) see the same data for the same class.
 *
 * NOTE: Previously this controller used R26 models (r26_practical_experiment_evaluations, etc.)
 * which caused data isolation between desktop and mobile. This has been corrected.
 */
class VirtualClassroomPracticalController extends Controller
{
    private function getStaff()
    {
        $userId = session('userId') ?? Session::get('userId');
        if (!$userId) return null;
        return \App\Models\StaffProfile::where('mobile_no', $userId)->first();
    }

    /**
     * Display the R2021 Practical Virtual Classroom (desktop).
     */
    public function show($batchSubjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        // Fetch students enrolled in this classroom
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        // Fetch lab batch designations (R26StudentLabBatch is shared across R2021 too — batch_subject_id keyed)
        $labBatches = R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->keyBy('reg_no');

        // ── Auto-sync Experiments With Class Logs & Marks ───────────────────
        \App\Http\Controllers\AttendanceController::syncPracticalExperimentsWithLogs($batchSubjectId);

        // ── R2021 Experiment Setup ──────────────────────────────────────────────
        // Experiments are configured per batch_subject in practical_experiments table.
        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();

        $totalExperiments = $experiments->count();
        $expIds = $experiments->pluck('id')->toArray();

        // All experiment marks for this subject (all students, all experiments)
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)->get();

        // Build groupBy: experiment_no => collection (for backward-compat with blade template slots)
        $experimentLogs = collect();
        foreach ($experiments as $exp) {
            $marksForExp = $allExpMarks->where('practical_experiment_id', $exp->id);
            // Store under experiment_no key, each item carries reg_no, rubric fields, total_mark
            $experimentLogs[$exp->experiment_no] = $marksForExp->map(function($m) use ($exp) {
                return (object)[
                    'reg_no'           => $m->reg_no,
                    'experiment_no'    => $exp->experiment_no,
                    'date'             => $m->evaluation_date ?: ($exp->conducted_date ?? null),
                    'evaluation_date'  => $m->evaluation_date ?: ($exp->conducted_date ?? null),
                    'rough_record'     => $m->rough_record,
                    'fair_record'      => $m->fair_record,
                    'prerequisites'    => $m->prerequisites,
                    'work_done'        => $m->work_done,
                    'result'           => $m->result,
                    'total_mark'       => $m->total_mark,
                ];
            });
        }

        // Open-ended marks from practical_evaluations table
        $openEndedLogs = collect();
        $evalRecords = PracticalEvaluation::where('batch_subject_id', $batchSubjectId)->get();
        foreach ($evalRecords as $ev) {
            if ($ev->micro_project > 0 || $ev->open_ended_topic) {
                $openEndedLogs[$ev->reg_no] = (object)[
                    'reg_no'        => $ev->reg_no,
                    'micro_project' => $ev->micro_project,
                    'topic'         => $ev->open_ended_topic,
                ];
            }
        }

        // Series exam marks from practical_tests & practical_test_marks
        $seriesExamLogs = collect();
        $testRecords = PracticalTest::where('batch_subject_id', $batchSubjectId)->get();
        $testIds = $testRecords->pluck('id')->toArray();
        $allTestMarks = PracticalTestMark::whereIn('practical_test_id', $testIds)->get();
        $t1 = $testRecords->where('test_name', 'Test 1')->first();
        $t2 = $testRecords->where('test_name', 'Test 2')->first();
        foreach ($testRecords as $t) {
            $marksForTest = $allTestMarks->where('practical_test_id', $t->id);
            $seriesExamLogs[$t->test_name] = $marksForTest->map(function($tm) use ($t) {
                return (object)[
                    'reg_no'         => $tm->reg_no,
                    'test_name'      => $t->test_name,
                    'max_marks'      => $t->max_marks,
                    'marks_obtained' => $tm->marks_obtained,
                ];
            });
        }

        // Attendance from class_logs_attendance
        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubject->id)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['id', 'date', 'period', 'topics_covered', 'lesson_plan_id', 'present_students', 'absent_students', 'sub_batch']);

        // Normalize student attendance slots and actual engaged hours
        $studentPresentSlots = [];    // [reg_no => [slotKey => true]]
        $studentScheduledSlots = [];  // [reg_no => [slotKey => true]]
        $actualSlotKeys = [];         // unique slots for the batch

        foreach ($classLogs as $log) {
            $slotKey = $log->date . '_P' . $log->period;
            $batchSlotKey = $slotKey . '_' . ($log->sub_batch ?? 'Whole');
            $actualSlotKeys[$batchSlotKey] = true;

            $pList = json_decode($log->present_students ?? '[]', true);
            $aList = json_decode($log->absent_students ?? '[]', true);
            if (is_array($pList)) {
                foreach ($pList as $rNo) {
                    $studentPresentSlots[$rNo][$slotKey] = true;
                    $studentScheduledSlots[$rNo][$slotKey] = true;
                }
            }
            if (is_array($aList)) {
                foreach ($aList as $rNo) {
                    $studentScheduledSlots[$rNo][$slotKey] = true;
                }
            }
        }

        $totalAttClasses = count($actualSlotKeys);

        // Pre-compute attendance mark per student
        $attendanceMarks = [];
        $evalMap = $evalRecords->keyBy('reg_no');
        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $present = isset($studentPresentSlots[$rNo]) ? count($studentPresentSlots[$rNo]) : 0;
            $scheduled = isset($studentScheduledSlots[$rNo]) ? count($studentScheduledSlots[$rNo]) : 0;
            $totalForStudent = $scheduled > 0 ? $scheduled : $totalAttClasses;
            $attMark = $totalForStudent > 0 ? round(($present / $totalForStudent) * 15, 1) : 15.0;

            // Allow override from practical_evaluations if manually set (> 0)
            $ev = $evalMap->get($rNo);
            if ($ev && $ev->attendance_marks !== null && (float)$ev->attendance_marks > 0) {
                $attMark = (float)$ev->attendance_marks;
            }
            $attendanceMarks[$rNo] = $attMark;
        }

        // ── Conducted Experiments / Class Logs ─────────────────────────────────
        // Build conducted session details list for the Completed Experiments table.
        // Group raw period logs into consolidated session entries (same date, sub_batch, topic).
        $studentRollMap = $students->pluck('roll_no', 'reg_no');

        $groupedLogs = $classLogs->groupBy(function($l) {
            return $l->date . '###' . ($l->sub_batch ?? 'Whole') . '###' . trim($l->topics_covered ?? '');
        });

        $logSessions = [];
        foreach ($groupedLogs as $groupKey => $logs) {
            $first = $logs->first();
            $topic = trim($first->topics_covered ?? '');
            if (!$topic && !$first->lesson_plan_id) continue;

            $date = $first->date;
            $subBatchVal = $first->sub_batch ?? 'Whole';
            $batchLabel = ($subBatchVal === '1' || $subBatchVal === 1) ? 'Batch 1' : (($subBatchVal === '2' || $subBatchVal === 2) ? 'Batch 2' : 'Whole Class');
            $periods = $logs->pluck('period')->unique()->sort()->values()->all();
            $hoursCount = count($periods);
            $periodStr = $hoursCount > 0 ? implode(', ', array_map(fn($p) => 'P' . $p, $periods)) : 'Session';
            $hoursText = "{$hoursCount} " . ($hoursCount === 1 ? 'hr' : 'hrs') . " ({$periodStr})";
            $pList = json_decode($first->present_students ?? '[]', true) ?: [];
            $aList = json_decode($first->absent_students ?? '[]', true) ?: [];
            $totalInLog = count($pList) + count($aList);
            $presentCount = count($pList);
            $absentCount = count($aList);

            $absentRolls = collect($aList)->map(fn($r) => $studentRollMap->get($r))->filter(fn($r) => $r !== null)->sort()->values()->all();
            $absentRollsStr = !empty($absentRolls) ? implode(', ', $absentRolls) : ($presentCount > 0 ? 'None' : '-');

            $logSessions[] = [
                'date' => $date,
                'sub_batch' => $subBatchVal,
                'batch_label' => $batchLabel,
                'periods' => $periods,
                'hours_count' => $hoursCount,
                'hours_text' => $hoursText,
                'topic' => $topic,
                'lesson_plan_id' => $first->lesson_plan_id,
                'present_count' => $presentCount,
                'absent_count'  => $absentCount,
                'absent_roll_nos' => $absentRollsStr,
                'total_count' => $totalInLog > 0 ? $totalInLog : $students->count(),
                'attendance_pct'=> $totalInLog > 0 ? round(($presentCount / $totalInLog) * 100, 1) : 100.0,
            ];
        }

        $conductedDetails = [];

        if ($experiments->isEmpty()) {
            foreach ($logSessions as $idx => $s) {
                $conductedDetails[] = [
                    'experiment_id' => null,
                    'experiment_no' => 'Exp ' . ($idx + 1),
                    'title'         => $s['topic'] ?: 'Practical Session',
                    'co_tag'        => 'CO1',
                    'date'          => $s['date'],
                    'periods'       => $s['periods'],
                    'hours_count'   => $s['hours_count'],
                    'hours_text'    => $s['hours_text'],
                    'batch'         => $s['batch_label'],
                    'sub_batch'     => $s['sub_batch'],
                    'present_count' => $s['present_count'],
                    'absent_count'  => $s['absent_count'],
                    'absent_roll_nos' => $s['absent_roll_nos'],
                    'total_count'   => $s['total_count'],
                    'attendance_pct'=> $s['attendance_pct'],
                ];
            }
        } else {
            // When syllabus experiments exist: match each conducted/graded experiment with its log sessions for EACH batch
            foreach ($experiments as $exp) {
                $hasMarks = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count() > 0;
                $expNo = trim((string)$exp->experiment_no);
                $expTitle = strtolower(trim((string)($exp->title ?? '')));

                $matchingSessions = [];

                foreach ($logSessions as $s) {
                    $t = trim((string)($s['topic'] ?? ''));
                    if (empty($t)) continue;

                    $matches = false;
                    // Match experiment number e.g. "Exp 10", "Experiment 10", "Expt 10"
                    if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) {
                        $matches = true;
                    } elseif (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                        $nums = preg_split('/[\s,&-]+/', $mList[1]);
                        if (in_array($expNo, array_map('trim', $nums))) {
                            $matches = true;
                        }
                    } elseif (!empty($expTitle) && strlen($expTitle) >= 6) {
                        $tLower = strtolower($t);
                        if (str_contains($tLower, $expTitle) || (strlen($tLower) >= 6 && str_contains($expTitle, $tLower))) {
                            $matches = true;
                        }
                    }

                    if ($matches) {
                        $matchingSessions[] = $s;
                    }
                }

                if (!empty($matchingSessions)) {
                    foreach ($matchingSessions as $mSession) {
                        $conductedDetails[] = [
                            'experiment_id' => $exp->id,
                            'experiment_no' => 'Exp ' . $exp->experiment_no,
                            'title'         => $exp->title,
                            'co_tag'        => $exp->co_tag ?? 'CO1',
                            'date'          => $mSession['date'],
                            'periods'       => $mSession['periods'],
                            'hours_count'   => $mSession['hours_count'],
                            'hours_text'    => $mSession['hours_text'],
                            'batch'         => $mSession['batch_label'],
                            'sub_batch'     => $mSession['sub_batch'],
                            'present_count' => $mSession['present_count'],
                            'absent_count'  => $mSession['absent_count'],
                            'absent_roll_nos' => $mSession['absent_roll_nos'],
                            'total_count'   => $mSession['total_count'],
                            'attendance_pct'=> $mSession['attendance_pct'],
                        ];
                    }
                } elseif ($hasMarks && !empty($exp->conducted_date)) {
                    $mSession = collect($logSessions)->firstWhere('date', $exp->conducted_date);
                    $gradedCount = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count();
                    $conductedDetails[] = [
                        'experiment_id' => $exp->id,
                        'experiment_no' => 'Exp ' . $exp->experiment_no,
                        'title'         => $exp->title,
                        'co_tag'        => $exp->co_tag ?? 'CO1',
                        'date'          => $mSession ? $mSession['date'] : $exp->conducted_date,
                        'periods'       => $mSession ? $mSession['periods'] : [1, 2, 3],
                        'hours_count'   => $mSession ? $mSession['hours_count'] : 3,
                        'hours_text'    => $mSession ? $mSession['hours_text'] : '3 hrs (Lab)',
                        'batch'         => $mSession ? $mSession['batch_label'] : 'Whole Class',
                        'sub_batch'     => $mSession ? $mSession['sub_batch'] : 'Whole',
                        'present_count' => $mSession ? $mSession['present_count'] : $gradedCount,
                        'absent_count'  => $mSession ? $mSession['absent_count'] : 0,
                        'absent_roll_nos' => $mSession ? $mSession['absent_roll_nos'] : 'None',
                        'total_count'   => $mSession ? $mSession['total_count'] : $students->count(),
                        'attendance_pct'=> $mSession ? $mSession['attendance_pct'] : ($students->count() > 0 ? round(($gradedCount / $students->count()) * 100, 1) : 100.0),
                    ];
                }
            }
        }

        // Order completed experiments: Batch 1 in initial rows, then Batch 2, then Whole Class / others
        usort($conductedDetails, function($a, $b) {
            $batchRank = function($item) {
                $sb = (string)($item['sub_batch'] ?? '');
                $b = strtolower((string)($item['batch'] ?? ''));
                if ($sb === '1' || str_contains($b, 'batch 1') || $b === 'b1') return 1;
                if ($sb === '2' || str_contains($b, 'batch 2') || $b === 'b2') return 2;
                return 3;
            };
            $rA = $batchRank($a);
            $rB = $batchRank($b);
            if ($rA !== $rB) return $rA <=> $rB;

            preg_match('/\d+/', (string)($a['experiment_no'] ?? ''), $mA);
            preg_match('/\d+/', (string)($b['experiment_no'] ?? ''), $mB);
            $numA = isset($mA[0]) ? (int)$mA[0] : 0;
            $numB = isset($mB[0]) ? (int)$mB[0] : 0;
            if ($numA !== $numB) return $numA <=> $numB;

            return strcmp((string)($a['date'] ?? ''), (string)($b['date'] ?? ''));
        });

        // Determine which experiments have been conducted in this class
        $conductedCount = count($conductedDetails);

        $actualLabHours = $classLogs->map(function($l) {
            return $l->date . '_P' . $l->period;
        })->unique()->count();
        if ($actualLabHours === 0 && $conductedCount > 0) {
            $actualLabHours = $conductedCount * 3;
        }

        // ── Per-Student Experiment Detail Map ─────────────────────────────────
        // Used by the student detail popup: shows each experiment's rubric marks + graded status
        $studentExpDetail = [];
        $gradedCount      = [];

        foreach ($students as $student) {
            $regNo = $student->reg_no;
            $studentExpDetail[$regNo] = [];
            $graded = 0;

            foreach ($experiments as $exp) {
                $mark = $allExpMarks
                    ->where('practical_experiment_id', $exp->id)
                    ->where('reg_no', $regNo)
                    ->first();

                $isGraded = $mark !== null && (
                    (float)$mark->total_mark > 0 ||
                    (float)$mark->rough_record > 0 ||
                    (float)$mark->fair_record > 0 ||
                    (float)$mark->prerequisites > 0 ||
                    (float)$mark->work_done > 0 ||
                    (float)$mark->result > 0
                );
                if ($isGraded) $graded++;

                $studentExpDetail[$regNo][$exp->id] = [
                    'exp_id'    => $exp->id,
                    'exp_no'    => $exp->experiment_no,
                    'title'     => $exp->title,
                    'co_tag'    => $exp->co_tag ?? '',
                    'rough'     => $isGraded ? (float)$mark->rough_record   : null,
                    'fair'      => $isGraded ? (float)$mark->fair_record    : null,
                    'obs'       => $isGraded ? (float)$mark->prerequisites  : null,
                    'proc'      => $isGraded ? (float)$mark->work_done      : null,
                    'viva'      => $isGraded ? (float)$mark->result         : null,
                    'total'     => $isGraded ? (float)$mark->total_mark     : null,
                    'graded'    => $isGraded,
                ];
            }

            $gradedCount[$regNo] = $graded;
        }

        // ── Pre-calculate consolidated scores for all students (Table 4.1 CIA Breakdown / 75) ──
        $consolidatedScores = [];
        foreach ($students as $student) {
            $regNo = $student->reg_no;

            // 1. Lab Work — average across actually graded experiments
            $studentExpScores = [];
            foreach ($experiments as $exp) {
                $mark = $allExpMarks
                    ->where('practical_experiment_id', $exp->id)
                    ->where('reg_no', $regNo)
                    ->first();
                if ($mark && (float)$mark->total_mark > 0) {
                    $studentExpScores[] = floatval($mark->total_mark);
                }
            }
            $avgLabWork375 = count($studentExpScores) > 0
                ? round(array_sum($studentExpScores) / count($studentExpScores), 2)
                : 0.0;

            // 2. Open-Ended (max 7.5 direct — stored as micro_project in PracticalEvaluation)
            $eval = $evalMap->get($regNo);
            $openEndedMark = $eval ? round((float)$eval->micro_project, 2) : 0.0;

            // 3. Tests — avg of Test 1 + Test 2 total scores (each /40), scaled to 15
            $t1Score = 0; $t2Score = 0;
            if ($t1) {
                $t1Score = $allTestMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->sum('marks_obtained');
            }
            if ($t2) {
                $t2Score = $allTestMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->sum('marks_obtained');
            }
            $avgTest40 = ($t1Score + $t2Score) / 2;
            $scaledTests15 = round(($avgTest40 / 40) * 15, 2);

            // 4. Attendance mark (slab, out of 15)
            $attMark = $attendanceMarks[$regNo]['mark'] ?? 0;

            // CIA Total out of 75
            $totalCIA = round($avgLabWork375 + $openEndedMark + $scaledTests15 + $attMark, 2);

            $consolidatedScores[$regNo] = [
                'avg_lab_work_375'    => $avgLabWork375,
                'scaled_lab_work_30'  => $avgLabWork375,   // alias for template
                'open_ended_mark'     => $openEndedMark,
                'scaled_open_ended_10' => $openEndedMark,  // alias for template
                'test1_score'         => $t1Score,
                'test2_score'         => $t2Score,
                'avg_test_40'         => round($avgTest40, 2),
                'scaled_series_15'    => $scaledTests15,
                'att_mark_15'         => $attMark,
                'total_cia_60'        => $totalCIA,        // alias (actually /75)
                'total_cia_75'        => $totalCIA,
            ];
        }

        $labBatchConfig = [
            'mode' => $batchSubject->lab_batch_mode ?: 'split',
            'cutoff' => $batchSubject->lab_batch_cutoff,
            'is_configured' => $labBatches->isNotEmpty() || $batchSubject->lab_batch_cutoff !== null,
            'b1_count' => $students->filter(fn($s) => in_array($labBatches->get($s->reg_no)->lab_batch ?? '', ['1', 'Batch 1', 'Batch A']))->count(),
            'b2_count' => $students->filter(fn($s) => in_array($labBatches->get($s->reg_no)->lab_batch ?? '', ['2', 'Batch 2', 'Batch B']))->count(),
        ];

        return view('virtual_classroom_practical', compact(
            'batchSubject',
            'students',
            'labBatches',
            'experiments',
            'totalExperiments',
            'conductedCount',
            'experimentLogs',
            'openEndedLogs',
            'seriesExamLogs',
            'attendanceMarks',
            'consolidatedScores',
            'studentExpDetail',
            'gradedCount',
            'conductedDetails',
            'actualLabHours',
            'labBatchConfig'
        ));
    }

    /**
     * Save experiment marks for a single experiment (desktop grading modal).
     * Writes to practical_experiment_marks via PracticalExperimentMark (R2021 model).
     */
    public function saveExperimentMarks(Request $request, $batchSubjectId)
    {
        $staff  = $this->getStaff();
        $expNo  = $request->input('experiment_no', 'Exp 1');
        $title  = $request->input('title', '');
        $marksData = $request->input('marks', []);

        // Resolve experiment record (create if not exists)
        $exp = PracticalExperiment::firstOrCreate(
            ['batch_subject_id' => $batchSubjectId, 'experiment_no' => $expNo],
            ['title' => $title, 'co_tag' => 'CO1']
        );
        if ($title && $exp->title !== $title) {
            $exp->title = $title;
            $exp->save();
        }

        $expDate = $request->input('date', $request->input('exp_date', $request->input('evaluation_date')));

        $hasAnyGenuineMarks = false;
        foreach ($marksData as $regNo => $criteria) {
            $rough = min(5.0,  (float)($criteria['c1'] ?? 0));   // Rough Record max 5
            $fair  = min(7.5,  (float)($criteria['c2'] ?? 0));   // Fair Record max 7.5
            $obs   = min(7.5,  (float)($criteria['c3'] ?? 0));   // Observation & Prep max 7.5
            $proc  = min(7.5,  (float)($criteria['c4'] ?? 0));   // Procedure & Punctuality max 7.5
            $viva  = min(10.0, (float)($criteria['c5'] ?? 0));   // Viva max 10
            $total = $rough + $fair + $obs + $proc + $viva;      // max 37.5

            if ($total > 0) {
                $hasAnyGenuineMarks = true;
            }

            $markData = [
                'assessor_mobile_no' => $staff->mobile_no ?? null,
                'rough_record'       => $rough,
                'fair_record'        => $fair,
                'prerequisites'      => $obs,    // Observation & Prep
                'work_done'          => $proc,   // Procedure & Punctuality
                'result'             => $viva,   // Viva / Output
                'total_mark'         => $total,
            ];
            if ($expDate) {
                $markData['evaluation_date'] = $expDate;
            }

            PracticalExperimentMark::updateOrCreate(
                [
                    'practical_experiment_id' => $exp->id,
                    'reg_no'                  => $regNo,
                ],
                $markData
            );
        }

        // Only stamp conducted_date if at least one student has genuine marks (> 0)
        if ($expDate && empty($exp->conducted_date) && $hasAnyGenuineMarks) {
            $exp->conducted_date = $expDate;
            $exp->save();
        }

        // Reconcile experiments with class logs & marks
        \App\Http\Controllers\AttendanceController::syncPracticalExperimentsWithLogs($batchSubjectId);

        return response()->json(['success' => true, 'message' => 'Experiment marks saved successfully!']);
    }

    /**
     * Save open-ended / micro-project marks (desktop).
     * Writes to practical_evaluations.micro_project (R2021 model).
     */
    public function saveOpenEndedMarks(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $marksData = $request->input('marks', []);

        foreach ($marksData as $regNo => $criteria) {
            // c1 is the single open-ended score slider (max 7.5)
            $openMark = min(7.5, (float)($criteria['c1'] ?? 0));
            $topic    = $criteria['title'] ?? '';

            PracticalEvaluation::updateOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'reg_no' => $regNo],
                [
                    'assessor_mobile_no' => $staff->mobile_no ?? null,
                    'micro_project'      => $openMark,
                    'open_ended_topic'   => $topic,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Open-ended experiment evaluation saved!']);
    }

    /**
     * Save series/test exam marks (desktop).
     * "Series 1" → Test 1, "Series 2" → Test 2.
     * Writes to practical_test_marks (R2021 model).
     */
    public function saveSeriesExamMarks(Request $request, $batchSubjectId)
    {
        $staff    = $this->getStaff();
        $seriesNo = $request->input('series_no', 'Series 1');
        $testName = $seriesNo === 'Series 2' ? 'Test 2' : 'Test 1';
        $marksData = $request->input('marks', []);

        $test = PracticalTest::firstOrCreate(
            ['batch_subject_id' => $batchSubjectId, 'test_name' => $testName],
            ['questions' => []]
        );

        foreach ($marksData as $regNo => $criteria) {
            // c1+c2+c3 from the slider UI (3-criteria format, max 5+5+5 = 15 internal, stored on /40 scale)
            $c1 = (float)($criteria['c1'] ?? 0);
            $c2 = (float)($criteria['c2'] ?? 0);
            $c3 = (float)($criteria['c3'] ?? 0);
            $total = $c1 + $c2 + $c3; // total out of 15 max from UI (scaled to display as /40)

            // Store combined as CO1 (for Test 1) or CO3 (for Test 2)
            $co = $testName === 'Test 1' ? 'CO1' : 'CO3';
            PracticalTestMark::updateOrCreate(
                ['practical_test_id' => $test->id, 'reg_no' => $regNo, 'co_tag' => $co],
                ['marks_obtained' => $total, 'assessor_mobile_no' => $staff->mobile_no ?? null]
            );
        }

        return response()->json(['success' => true, 'message' => 'Practical Series Exam marks saved!']);
    }

    /**
     * Assign student to a lab batch (Batch A / Batch B).
     */
    public function assignLabBatch(Request $request, $batchSubjectId)
    {
        $regNo    = $request->input('reg_no');
        $labBatch = $request->input('lab_batch');

        if (empty($labBatch)) {
            R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
                ->where('reg_no', $regNo)
                ->delete();
        } else {
            R26StudentLabBatch::updateOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'reg_no' => $regNo],
                ['lab_batch' => $labBatch]
            );
        }

        return response()->json(['success' => true, 'message' => 'Lab batch assigned successfully!']);
    }

    /**
     * Attendance log API — returns per-session (date+period+topic) attendance
     * for the given batch_subject_id. Client-side filters by student reg_no.
     */
    public function getAttendanceLog(Request $request, $subjectId)
    {
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['id', 'date', 'period', 'topics_covered', 'sub_batch', 'present_students', 'absent_students']);

        $result = $logs->map(function($log) {
            return [
                'date'      => $log->date,
                'period'    => $log->period,
                'topic'     => $log->topics_covered ?? '—',
                'sub_batch' => $log->sub_batch ?? 'Whole',
                'present'   => json_decode($log->present_students ?? '[]', true) ?: [],
                'absent'    => json_decode($log->absent_students  ?? '[]', true) ?: [],
            ];
        });

        return response()->json([
            'status'         => 'SUCCESS',
            'total_sessions' => $logs->count(),
            'logs'           => $result,
        ]);
    }

    /**
     * Save/update CIA summary (open-ended, series tests, attendance marks) for a single student.
     * Can be invoked from both Desktop and Mobile student detail modals.
     */
    public function saveStudentCiaSummary(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $assessorMobile = $staff->mobile_no ?? Session::get('userId');

        $regNo          = $request->input('reg_no');
        $openEndedMark  = $request->has('open_ended_mark') && $request->input('open_ended_mark') !== '' && $request->input('open_ended_mark') !== null ? (float)$request->input('open_ended_mark') : null;
        $openEndedTopic = $request->input('open_ended_topic');
        $test1          = $request->has('test1') && $request->input('test1') !== '' && $request->input('test1') !== null ? (float)$request->input('test1') : null;
        $test2          = $request->has('test2') && $request->input('test2') !== '' && $request->input('test2') !== null ? (float)$request->input('test2') : null;
        $attendanceMark = $request->has('attendance_mark') && $request->input('attendance_mark') !== '' && $request->input('attendance_mark') !== null ? (float)$request->input('attendance_mark') : null;

        if (!$regNo) {
            return response()->json(['success' => false, 'message' => 'Student registration number is required.'], 400);
        }

        // 1. Update PracticalEvaluation (open-ended & attendance marks)
        $eval = PracticalEvaluation::firstOrNew([
            'batch_subject_id' => $batchSubjectId,
            'reg_no'           => $regNo
        ]);
        $eval->assessor_mobile_no = $assessorMobile;
        if ($openEndedMark !== null) {
            $eval->micro_project = min(7.5, max(0, $openEndedMark));
        }
        if ($openEndedTopic !== null) {
            $eval->open_ended_topic = $openEndedTopic;
        }
        if ($attendanceMark !== null) {
            $eval->attendance_marks = min(15, max(0, $attendanceMark));
        }
        $eval->save();

        // 2. Update Practical Tests
        if ($test1 !== null) {
            $t1 = PracticalTest::firstOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'test_name' => 'Test 1'],
                ['questions' => []]
            );
            PracticalTestMark::updateOrCreate(
                ['practical_test_id' => $t1->id, 'reg_no' => $regNo, 'co_tag' => 'CO1'],
                ['marks_obtained' => min(40, max(0, $test1)), 'assessor_mobile_no' => $assessorMobile]
            );
        }

        if ($test2 !== null) {
            $t2 = PracticalTest::firstOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'test_name' => 'Test 2'],
                ['questions' => []]
            );
            PracticalTestMark::updateOrCreate(
                ['practical_test_id' => $t2->id, 'reg_no' => $regNo, 'co_tag' => 'CO3'],
                ['marks_obtained' => min(40, max(0, $test2)), 'assessor_mobile_no' => $assessorMobile]
            );
        }

        // 3. Compute updated consolidated CIA
        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)->get();
        $expIds = $experiments->pluck('id')->toArray();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)
            ->where('reg_no', $regNo)
            ->get();

        $sumExp = $allExpMarks->sum('total_mark');
        $cntExp = $allExpMarks->count();
        $avgLabWork = $cntExp > 0 ? round($sumExp / $cntExp, 2) : 0.0;

        $t1Score = $test1;
        $t2Score = $test2;
        if ($t1Score === null) {
            $t1Obj = PracticalTest::where('batch_subject_id', $batchSubjectId)->where('test_name', 'Test 1')->first();
            if ($t1Obj) {
                $m = PracticalTestMark::where('practical_test_id', $t1Obj->id)->where('reg_no', $regNo)->first();
                $t1Score = $m ? (float)$m->marks_obtained : 0.0;
            } else {
                $t1Score = 0.0;
            }
        }
        if ($t2Score === null) {
            $t2Obj = PracticalTest::where('batch_subject_id', $batchSubjectId)->where('test_name', 'Test 2')->first();
            if ($t2Obj) {
                $m = PracticalTestMark::where('practical_test_id', $t2Obj->id)->where('reg_no', $regNo)->first();
                $t2Score = $m ? (float)$m->marks_obtained : 0.0;
            } else {
                $t2Score = 0.0;
            }
        }

        $avgTest40 = ($t1Score + $t2Score) / 2;
        $scaledTests15 = round(($avgTest40 / 40) * 15, 2);

        $attVal = $attendanceMark ?? (float)($eval->attendance_marks ?? 0);
        $oeVal  = $openEndedMark ?? (float)($eval->micro_project ?? 0);
        $totalCIA = round($avgLabWork + $oeVal + $scaledTests15 + $attVal, 2);

        // Sync StudentSemesterMarks
        try {
            $batchSubj = BatchSubject::find($batchSubjectId);
            if ($batchSubj) {
                \App\Models\StudentSemesterMarks::updateOrCreate(
                    [
                        'reg_no'       => $regNo,
                        'subject_code' => $batchSubj->subject_code,
                        'semester'     => $batchSubj->semester
                    ],
                    [
                        'subject_name'   => $batchSubj->subject_name,
                        'internal_marks' => $totalCIA,
                    ]
                );
            }
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'CIA Summary updated successfully!',
            'data'    => [
                'reg_no'               => $regNo,
                'open_ended_mark'      => $oeVal,
                'open_ended_topic'     => $eval->open_ended_topic ?? '',
                'test1_score'          => $t1Score,
                'test2_score'          => $t2Score,
                'avg_test_40'          => round($avgTest40, 2),
                'scaled_series_15'     => $scaledTests15,
                'att_mark_15'          => $attVal,
                'avg_lab_work_375'     => $avgLabWork,
                'total_cia'            => $totalCIA,
                'total_cia_75'         => $totalCIA,
            ]
        ]);
    }

    /**
     * Helper to prepare R2021 practical report dataset for printable marksheet views.
     */
    public function getPracticalReportData($batchSubjectId)
    {
        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();
        $expIds = $experiments->pluck('id')->toArray();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)->get();

        $evaluations = PracticalEvaluation::where('batch_subject_id', $batchSubjectId)->get()->keyBy('reg_no');
        $tests       = PracticalTest::where('batch_subject_id', $batchSubjectId)->get();
        $testIds     = $tests->pluck('id')->toArray();
        $allTestMarks = PracticalTestMark::whereIn('practical_test_id', $testIds)->get();

        // Attendance from class_logs_attendance
        $classLogs  = DB::table('class_logs_attendance')->where('batch_subject_id', $batchSubjectId)->get(['present_students', 'absent_students']);
        $totalClasses = $classLogs->count();
        $studentAttCounts = [];
        $studentScheduledCounts = [];
        foreach ($classLogs as $log) {
            $pList = json_decode($log->present_students ?? '[]', true);
            $aList = json_decode($log->absent_students ?? '[]', true);
            if (is_array($pList)) {
                foreach ($pList as $rNo) {
                    $studentAttCounts[$rNo] = ($studentAttCounts[$rNo] ?? 0) + 1;
                    $studentScheduledCounts[$rNo] = ($studentScheduledCounts[$rNo] ?? 0) + 1;
                }
            }
            if (is_array($aList)) {
                foreach ($aList as $rNo) {
                    $studentScheduledCounts[$rNo] = ($studentScheduledCounts[$rNo] ?? 0) + 1;
                }
            }
        }

        $t1 = $tests->where('test_name', 'Test 1')->first();
        $t2 = $tests->where('test_name', 'Test 2')->first();

        $mappedStudents = $students->map(function ($student) use ($batchSubject, $experiments, $allExpMarks, $evaluations, $tests, $allTestMarks, $t1, $t2, $totalClasses, $studentAttCounts, $studentScheduledCounts) {
            $regNo = $student->reg_no;

            // Attendance calculation (proportional out of 15 for R2021)
            $presentClasses = $studentAttCounts[$regNo] ?? 0;
            $scheduledClasses = $studentScheduledCounts[$regNo] ?? 0;
            $totalForStudent = $scheduledClasses > 0 ? $scheduledClasses : $totalClasses;
            $pct = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 100, 2) : 100.00;
            $suggestedAttendance = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 15, 1) : 15.0;

            $eval = $evaluations->get($regNo);
            $microProject = $eval ? (float)$eval->micro_project : 0.00;
            $attendanceMarks = ($eval && $eval->attendance_marks !== null && (float)$eval->attendance_marks > 0) ? (float)$eval->attendance_marks : $suggestedAttendance;
            
            // ESE Board Marks or Grade
            $boardExam = $eval ? ($eval->board_exam_marks !== null ? $eval->board_exam_marks : null) : null;
            if ($boardExam === null) {
                $bGrade = DB::table('student_board_grades')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->value('grade');
                if ($bGrade) {
                    $boardExam = $bGrade;
                }
            }

            // 5 Rubrics Continuous Lab Work (Max 37.5)
            $sumRough = 0; $sumFair = 0; $sumObs = 0; $sumProc = 0; $sumViva = 0;
            $gradedExpCount = 0;
            foreach ($experiments as $exp) {
                $mark = $allExpMarks->where('practical_experiment_id', $exp->id)->where('reg_no', $regNo)->first();
                if ($mark && ($mark->total_mark > 0 || $mark->rough_record > 0 || $mark->fair_record > 0 || $mark->prerequisites > 0 || $mark->work_done > 0 || $mark->result > 0)) {
                    $sumRough += (float)$mark->rough_record;
                    $sumFair  += (float)$mark->fair_record;
                    $sumObs   += (float)$mark->prerequisites;
                    $sumProc  += (float)$mark->work_done;
                    $sumViva  += (float)$mark->result;
                    $gradedExpCount++;
                }
            }

            $avgRough = $gradedExpCount > 0 ? round($sumRough / $gradedExpCount, 2) : 0.00;
            $avgFair  = $gradedExpCount > 0 ? round($sumFair / $gradedExpCount, 2) : 0.00;
            $avgObs   = $gradedExpCount > 0 ? round($sumObs / $gradedExpCount, 2) : 0.00;
            $avgProc  = $gradedExpCount > 0 ? round($sumProc / $gradedExpCount, 2) : 0.00;
            $avgViva  = $gradedExpCount > 0 ? round($sumViva / $gradedExpCount, 2) : 0.00;
            $avgLabWork = round($avgRough + $avgFair + $avgObs + $avgProc + $avgViva, 2);

            // Practical Series Tests (Max 15)
            $scoreT1 = $t1 ? (float)$allTestMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->sum('marks_obtained') : 0.0;
            $scoreT2 = $t2 ? (float)$allTestMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->sum('marks_obtained') : 0.0;
            $avgTests = round(($scoreT1 + $scoreT2) / 2, 2);

            // Total Internal Assessment (Max 75)
            $totalInternal = round($avgLabWork + $microProject + $avgTests + $attendanceMarks, 2);

            $student->avg_rough_record = $avgRough;
            $student->avg_fair_record  = $avgFair;
            $student->avg_obs_prep     = $avgObs;
            $student->avg_proc_punct   = $avgProc;
            $student->avg_viva_voce    = $avgViva;
            $student->avg_lab_work     = $avgLabWork;
            $student->tests = [
                'Test 1' => ['total' => $scoreT1],
                'Test 2' => ['total' => $scoreT2],
                'average' => $avgTests
            ];
            $student->micro_project = $microProject;
            $student->attendance_marks = $attendanceMarks;
            $student->attendance_percentage = $pct;
            $student->total_classes = $totalClasses;
            $student->present_classes = $presentClasses;
            $student->total_internal = $totalInternal;
            $student->board_exam_marks = $boardExam;

            return $student;
        });

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];
        $branchKey = strtoupper(explode('_', $batchSubject->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        $cleanedBatch = preg_replace('/^[A-Z]+_/', '', $batchSubject->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        return [
            'subject' => $batchSubject,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'students' => $mappedStudents,
            'totalStudents' => $mappedStudents->count(),
            'currentYear' => date('Y')
        ];
    }

    /**
     * Print Consolidated R2021 Practical Continuous Internal Assessment (CIA 75M) Evaluation Register.
     */
    public function printReport($batchSubjectId)
    {
        return view('classroom_practical_report_print', $this->getPracticalReportData($batchSubjectId));
    }

    /**
     * Print Practical Series Examination Marksheet (15M).
     */
    public function printSeriesReport($batchSubjectId)
    {
        return view('classroom_practical_series_print', $this->getPracticalReportData($batchSubjectId));
    }

    /**
     * Print End-Semester Examination & Consolidated Final Results Marksheet (125M).
     */
    public function printFinalResults($batchSubjectId)
    {
        return view('classroom_practical_final_results_print', $this->getPracticalReportData($batchSubjectId));
    }

    /**
     * Print Completed Practical Experiments & Sessions Log Report.
     */
    public function printExperimentsLog($batchSubjectId)
    {
        $batchSubject = BatchSubject::with('classroom')->findOrFail($batchSubjectId);

        // Reconcile and sync practical experiment conducted dates against class logs and graded marks
        AttendanceController::syncPracticalExperimentsWithLogs($batchSubjectId);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $experiments->pluck('id'))->where('total_mark', '>', 0)->get();

        $classLogs = DB::table('class_logs_attendance')->where('batch_subject_id', $batchSubjectId)->get();

        // Group class logs by (date, sub_batch, topics_covered)
        $groupedLogs = $classLogs->groupBy(function($l) {
            return $l->date . '###' . ($l->sub_batch ?? 'Whole') . '###' . trim($l->topics_covered ?? '');
        });

        $studentRollMap = $students->pluck('roll_no', 'reg_no');

        $logSessions = [];
        foreach ($groupedLogs as $groupKey => $logs) {
            $first = $logs->first();
            $topic = trim($first->topics_covered ?? '');
            if (!$topic && !$first->lesson_plan_id) continue;

            $date = $first->date;
            $subBatchVal = $first->sub_batch ?? 'Whole';
            $batchLabel = ($subBatchVal === '1' || $subBatchVal === 1) ? 'Batch 1' : (($subBatchVal === '2' || $subBatchVal === 2) ? 'Batch 2' : 'Whole Class');
            $periods = $logs->pluck('period')->unique()->sort()->values()->all();
            $hoursCount = count($periods);
            $periodStr = $hoursCount > 0 ? implode(', ', array_map(fn($p) => 'P' . $p, $periods)) : 'Session';
            $hoursText = "{$hoursCount} " . ($hoursCount === 1 ? 'hr' : 'hrs') . " ({$periodStr})";
            $pList = json_decode($first->present_students ?? '[]', true) ?: [];
            $aList = json_decode($first->absent_students ?? '[]', true) ?: [];
            $totalInLog = count($pList) + count($aList);
            $presentCount = count($pList);
            $absentCount = count($aList);

            $absentRolls = collect($aList)->map(fn($r) => $studentRollMap->get($r))->filter(fn($r) => $r !== null)->sort()->values()->all();
            $absentRollsStr = !empty($absentRolls) ? implode(', ', $absentRolls) : ($presentCount > 0 ? 'None' : '-');

            $logSessions[] = [
                'date' => $date,
                'sub_batch' => $subBatchVal,
                'batch_label' => $batchLabel,
                'periods' => $periods,
                'hours_count' => $hoursCount,
                'hours_text' => $hoursText,
                'topic' => $topic,
                'lesson_plan_id' => $first->lesson_plan_id,
                'present_count' => $presentCount,
                'absent_count'  => $absentCount,
                'absent_roll_nos' => $absentRollsStr,
                'total_count' => $totalInLog > 0 ? $totalInLog : $students->count(),
                'attendance_pct' => $totalInLog > 0 ? round(($presentCount / $totalInLog) * 100, 1) : 100.0,
            ];
        }

        $conductedDetails = [];

        if ($experiments->isEmpty()) {
            foreach ($logSessions as $idx => $s) {
                $conductedDetails[] = [
                    'experiment_id' => null,
                    'experiment_no' => 'Exp ' . ($idx + 1),
                    'title'         => $s['topic'] ?: 'Practical Session',
                    'co_tag'        => 'CO1',
                    'date'          => $s['date'],
                    'periods'       => $s['periods'],
                    'hours_count'   => $s['hours_count'],
                    'hours_text'    => $s['hours_text'],
                    'batch'         => $s['batch_label'],
                    'sub_batch'     => $s['sub_batch'],
                    'present_count' => $s['present_count'],
                    'absent_count'  => $s['absent_count'],
                    'absent_roll_nos' => $s['absent_roll_nos'],
                    'total_count'   => $s['total_count'],
                    'attendance_pct'=> $s['attendance_pct'],
                ];
            }
        } else {
            foreach ($experiments as $exp) {
                $hasMarks = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count() > 0;
                $expNo = trim((string)$exp->experiment_no);
                $expTitle = strtolower(trim((string)($exp->title ?? '')));

                $matchingSessions = [];

                foreach ($logSessions as $s) {
                    $t = trim((string)($s['topic'] ?? ''));
                    if (empty($t)) continue;

                    $matches = false;
                    // Match experiment number e.g. "Exp 10", "Experiment 10", "Expt 10"
                    if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) {
                        $matches = true;
                    } elseif (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                        $nums = preg_split('/[\s,&-]+/', $mList[1]);
                        if (in_array($expNo, array_map('trim', $nums))) {
                            $matches = true;
                        }
                    } elseif (!empty($expTitle) && strlen($expTitle) >= 6) {
                        $tLower = strtolower($t);
                        if (str_contains($tLower, $expTitle) || (strlen($tLower) >= 6 && str_contains($expTitle, $tLower))) {
                            $matches = true;
                        }
                    }

                    if ($matches) {
                        $matchingSessions[] = $s;
                    }
                }

                if (!empty($matchingSessions)) {
                    foreach ($matchingSessions as $mSession) {
                        $conductedDetails[] = [
                            'experiment_id' => $exp->id,
                            'experiment_no' => 'Exp ' . $exp->experiment_no,
                            'title'         => $exp->title,
                            'co_tag'        => $exp->co_tag ?? 'CO1',
                            'date'          => $mSession['date'],
                            'periods'       => $mSession['periods'],
                            'hours_count'   => $mSession['hours_count'],
                            'hours_text'    => $mSession['hours_text'],
                            'batch'         => $mSession['batch_label'],
                            'sub_batch'     => $mSession['sub_batch'],
                            'present_count' => $mSession['present_count'],
                            'absent_count'  => $mSession['absent_count'],
                            'absent_roll_nos' => $mSession['absent_roll_nos'],
                            'total_count'   => $mSession['total_count'],
                            'attendance_pct'=> $mSession['attendance_pct'],
                        ];
                    }
                } elseif ($hasMarks && !empty($exp->conducted_date)) {
                    $mSession = collect($logSessions)->firstWhere('date', $exp->conducted_date);
                    $gradedCount = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count();
                    $conductedDetails[] = [
                        'experiment_id' => $exp->id,
                        'experiment_no' => 'Exp ' . $exp->experiment_no,
                        'title'         => $exp->title,
                        'co_tag'        => $exp->co_tag ?? 'CO1',
                        'date'          => $mSession ? $mSession['date'] : ($exp->conducted_date ?: 'Conducted'),
                        'periods'       => $mSession ? $mSession['periods'] : [1, 2, 3],
                        'hours_count'   => $mSession ? $mSession['hours_count'] : 3,
                        'hours_text'    => $mSession ? $mSession['hours_text'] : '3 hrs (Lab)',
                        'batch'         => $mSession ? $mSession['batch_label'] : 'Whole Class',
                        'sub_batch'     => $mSession ? $mSession['sub_batch'] : 'Whole',
                        'present_count' => $mSession ? $mSession['present_count'] : $gradedCount,
                        'absent_count'  => $mSession ? $mSession['absent_count'] : 0,
                        'absent_roll_nos' => $mSession ? $mSession['absent_roll_nos'] : 'None',
                        'total_count'   => $mSession ? $mSession['total_count'] : $students->count(),
                        'attendance_pct'=> $mSession ? $mSession['attendance_pct'] : ($students->count() > 0 ? round(($gradedCount / $students->count()) * 100, 1) : 100.0),
                    ];
                }
            }
        }

        // Order completed experiments: Batch 1 in initial rows, then Batch 2, then Whole Class / others
        usort($conductedDetails, function($a, $b) {
            $batchRank = function($item) {
                $sb = (string)($item['sub_batch'] ?? '');
                $b = strtolower((string)($item['batch'] ?? ''));
                if ($sb === '1' || str_contains($b, 'batch 1') || $b === 'b1') return 1;
                if ($sb === '2' || str_contains($b, 'batch 2') || $b === 'b2') return 2;
                return 3;
            };
            $rA = $batchRank($a);
            $rB = $batchRank($b);
            if ($rA !== $rB) return $rA <=> $rB;

            preg_match('/\d+/', (string)($a['experiment_no'] ?? ''), $mA);
            preg_match('/\d+/', (string)($b['experiment_no'] ?? ''), $mB);
            $numA = isset($mA[0]) ? (int)$mA[0] : 0;
            $numB = isset($mB[0]) ? (int)$mB[0] : 0;
            if ($numA !== $numB) return $numA <=> $numB;

            return strcmp((string)($a['date'] ?? ''), (string)($b['date'] ?? ''));
        });

        $actualLabHours = $classLogs->map(function($l) {
            return $l->date . '_P' . $l->period;
        })->unique()->count();
        if ($actualLabHours === 0 && count($conductedDetails) > 0) {
            $actualLabHours = count($conductedDetails) * 3;
        }

        $totalExperiments = $experiments->count();
        $conductedCount = count($conductedDetails);
        $coveragePct = $totalExperiments > 0 ? round(($conductedCount / $totalExperiments) * 100) : 0;

        $cleanedBatch = preg_replace('/^([A-Z]+)_(\d{4})_(\d{4})$/', '$1 ($2-$3)', $batchSubject->classroom_id ?? '');
        $branch = explode('_', $batchSubject->classroom_id ?? '')[0] ?? 'Engineering';
        $deptMap = [
            'CT' => 'Computer Engineering',
            'EL' => 'Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'AU' => 'Automobile Engineering',
        ];
        $fullDepartment = $deptMap[$branch] ?? ($branch . ' Department');

        return view('classroom_practical_experiments_print', compact(
            'batchSubject', 'students', 'conductedDetails', 'totalExperiments',
            'conductedCount', 'actualLabHours', 'coveragePct', 'cleanedBatch', 'fullDepartment'
        ));
    }
}
