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
use App\Models\StaffProfile;
use Illuminate\Support\Facades\Session;
use DB;

/**
 * StaffMobileVirtualLabController
 *
 * Serves the mobile-optimised Virtual Lab evaluation page for R2021 practical/lab subjects.
 * Uses the same R2021 model set as VirtualClassroomPracticalController (desktop),
 * ensuring data is fully shared across both faculty and both devices for the same class.
 *
 * CIA formula (identical to desktop):
 *   Lab Work   = avg(total_mark per graded experiment)       [max 37.5]
 *   Open-Ended = micro_project from PracticalEvaluation      [max 7.5]
 *   Tests      = avg(Test1+Test2) / 40 × 15                  [max 15]
 *   Attendance = slab(att% from class_logs_attendance)        [max 15]
 *   ──────────────────────────────────────────────────────────────────
 *   Total CIA  = Lab Work + Open-Ended + Tests + Attendance   [max 75]
 */
class StaffMobileVirtualLabController extends Controller
{
    /**
     * Show the mobile Virtual Lab evaluation dashboard for a given R2021 batch subject.
     */
    public function show(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role === 'Student') {
            return redirect('/');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        // Safety guard: only serve for R2021 practical/lab subjects
        $subTypeLower = strtolower(trim($batchSubject->subject_type ?? ''));
        $revCode      = strtoupper(trim($batchSubject->syllabus_revision_code ?? ''));

        $isPractical = str_contains($subTypeLower, 'lab')       ||
                       str_contains($subTypeLower, 'practical') ||
                       str_contains($subTypeLower, 'practicum') ||
                       str_contains($subTypeLower, 'drawing')   ||
                       str_contains($subTypeLower, 'workshop');
        $isR2021 = str_contains($revCode, '2021') || str_contains($revCode, 'R21') || str_contains($revCode, 'REV2021');

        if (!$isPractical || !$isR2021) {
            return redirect('/staff/mobile')
                ->with('error', 'Virtual Lab is only available for R2021 Lab/Practical subjects.');
        }

        // ── Students ───────────────────────────────────────────────────────────
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where('status', 'Approved')
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no']);

        // ── Auto-sync Experiments With Class Logs & Marks ───────────────────
        \App\Http\Controllers\AttendanceController::syncPracticalExperimentsWithLogs($subjectId);

        // ── Experiments (R2021) ────────────────────────────────────────────────
        $experiments = PracticalExperiment::where('batch_subject_id', $subjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();

        $totalExperiments = $experiments->count();
        $expIds = $experiments->pluck('id')->toArray();

        // All experiment marks for this subject
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)->get();

        // ── Consolidated evaluations (R2021) ───────────────────────────────────
        $evaluations = PracticalEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // ── Tests (R2021) ──────────────────────────────────────────────────────
        $tests    = PracticalTest::where('batch_subject_id', $subjectId)->get();
        $testIds  = $tests->pluck('id')->toArray();
        $allTestMarks = PracticalTestMark::whereIn('practical_test_id', $testIds)->get();
        $t1 = $tests->where('test_name', 'Test 1')->first();
        $t2 = $tests->where('test_name', 'Test 2')->first();

        // ── Attendance from class_logs_attendance ──────────────────────────────
        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubject->id)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['date', 'period', 'topics_covered', 'sub_batch', 'present_students', 'absent_students']);

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

        $totalAttClasses = count($actualSlotKeys); // total actual conducted session slots

        // Determine which experiments have been conducted in this class
        $conductedExpIds = $experiments->filter(function($exp) use ($allExpMarks, $classLogs) {
            $hasMarks = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count() > 0;
            if ($hasMarks) return true;

            $expNo = trim((string)$exp->experiment_no);
            $expTitle = strtolower(trim((string)($exp->title ?? '')));
            foreach ($classLogs as $l) {
                $t = trim((string)($l->topics_covered ?? ''));
                if (empty($t)) continue;
                if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) return true;
                if (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                    $nums = preg_split('/[\s,&-]+/', $mList[1]);
                    if (in_array($expNo, array_map('trim', $nums))) return true;
                }
                if (!empty($expTitle) && strlen($expTitle) >= 6) {
                    $tLower = strtolower($t);
                    if (str_contains($tLower, $expTitle) || (strlen($tLower) >= 6 && str_contains($expTitle, $tLower))) return true;
                }
            }

            return false;
        })->pluck('id')->toArray();
        $conductedExperimentsCount = count($conductedExpIds);

        // ── Per-student data payload ───────────────────────────────────────────
        $studentsData = $students->map(function ($student) use (
            $batchSubject, $experiments, $totalExperiments,
            $allExpMarks, $evaluations, $tests, $allTestMarks,
            $t1, $t2, $totalAttClasses, $studentPresentSlots, $studentScheduledSlots, $conductedExperimentsCount
        ) {
            $regNo = $student->reg_no;

            // ── Attendance ─────────────────────────────────────────────────────
            $presentAtt = isset($studentPresentSlots[$regNo]) ? count($studentPresentSlots[$regNo]) : 0;
            $scheduledAtt = isset($studentScheduledSlots[$regNo]) ? count($studentScheduledSlots[$regNo]) : 0;
            $totalForStudent = $scheduledAtt > 0 ? $scheduledAtt : $totalAttClasses;
            $attPct = $totalForStudent > 0 ? round(($presentAtt / $totalForStudent) * 100, 1) : 100.0;

            // Attendance marks out of 15 proportional to attendance % for all percentages (R2021)
            $calculatedAttMark = $totalForStudent > 0 ? round(($presentAtt / $totalForStudent) * 15, 1) : 15.0;

            // Allow override from PracticalEvaluation.attendance_marks if set (> 0) by faculty
            $eval = $evaluations->get($regNo);
            $attendanceMarks = ($eval && $eval->attendance_marks !== null && (float)$eval->attendance_marks > 0)
                ? (float)$eval->attendance_marks
                : $calculatedAttMark;

            // ── Open-Ended ─────────────────────────────────────────────────────
            $openEndedMarks = $eval ? (float)($eval->micro_project ?? 0) : 0.0;
            $openEndedTopic = $eval ? ($eval->open_ended_topic ?? '') : '';

            // ── Experiment marks — keyed by exp->id ───────────────────────────
            $expMarksMap = [];
            $sumExpTotal = 0;
            $countGraded = 0;

            foreach ($experiments as $exp) {
                $mark = $allExpMarks
                    ->where('practical_experiment_id', $exp->id)
                    ->where('reg_no', $regNo)
                    ->first();

                $hasScore = $mark && (
                    (float)$mark->total_mark > 0 ||
                    (float)$mark->rough_record > 0 ||
                    (float)$mark->fair_record > 0 ||
                    (float)$mark->prerequisites > 0 ||
                    (float)$mark->work_done > 0 ||
                    (float)$mark->result > 0
                );

                if ($hasScore) {
                    $expMarksMap[$exp->id] = [
                        'rough_record'    => (float)$mark->rough_record,
                        'fair_record'     => (float)$mark->fair_record,
                        'obs_prep'        => (float)$mark->prerequisites,
                        'proc_punct'      => (float)$mark->work_done,
                        'viva'            => (float)$mark->result,
                        'total'           => (float)$mark->total_mark,
                        'graded'          => true,
                        'evaluation_date' => $mark->evaluation_date ?: ($exp->conducted_date ?? null),
                    ];
                    $sumExpTotal += (float)$mark->total_mark;
                    $countGraded++;
                } else {
                    $expMarksMap[$exp->id] = [
                        'rough_record'    => null,
                        'fair_record'     => null,
                        'obs_prep'        => null,
                        'proc_punct'      => null,
                        'viva'            => null,
                        'total'           => null,
                        'graded'          => false,
                        'evaluation_date' => ($mark && $mark->evaluation_date) ? $mark->evaluation_date : ($exp->conducted_date ?? null),
                    ];
                }
            }

            // Lab Work mark = average of graded experiment totals (max 37.5)
            $avgLabWork = $countGraded > 0 ? round($sumExpTotal / $countGraded, 2) : 0.0;

            // ── Test marks ─────────────────────────────────────────────────────
            $scoreT1 = $t1
                ? $allTestMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->sum('marks_obtained')
                : 0.0;
            $scoreT2 = $t2
                ? $allTestMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->sum('marks_obtained')
                : 0.0;

            $avgTest40     = ($scoreT1 + $scoreT2) / 2;
            $scaledTests15 = round(($avgTest40 / 40) * 15, 2);

            // ── CIA Total (identical formula to desktop) ───────────────────────
            $totalCIA = round($avgLabWork + $openEndedMarks + $scaledTests15 + $attendanceMarks, 2);

            // ── Experiment detail for popup (all exps, graded & ungraded) ──────
            $expDetail = [];
            foreach ($experiments as $exp) {
                $m = $expMarksMap[$exp->id] ?? null;
                $expDetail[] = [
                    'exp_id'          => $exp->id,
                    'exp_no'          => $exp->experiment_no,
                    'title'           => $exp->title,
                    'co_tag'          => $exp->co_tag ?? '',
                    'rough'           => $m['rough_record'] ?? null,
                    'fair'            => $m['fair_record']  ?? null,
                    'obs'             => $m['obs_prep']     ?? null,
                    'proc'            => $m['proc_punct']   ?? null,
                    'viva'            => $m['viva']         ?? null,
                    'total'           => $m['total']        ?? null,
                    'graded'          => $m['graded']       ?? false,
                    'evaluation_date' => $m['evaluation_date'] ?? ($exp->conducted_date ?? null),
                ];
            }

            return [
                'reg_no'               => $regNo,
                'name'                 => $student->name,
                'roll_no'              => $student->roll_no,
                'sbte_reg_no'          => $student->sbte_reg_no ?? $regNo,
                'att_pct'              => $attPct,
                'att_total'            => $totalForStudent,
                'att_present'          => $presentAtt,
                'att_slab_mark'        => $calculatedAttMark,
                'suggested_att_mark'   => $calculatedAttMark,
                'attendance_marks'     => $attendanceMarks,
                'open_ended_marks'     => $openEndedMarks,
                'open_ended_topic'     => $openEndedTopic,
                'exp_marks'            => $expMarksMap,
                'exp_detail'           => $expDetail,
                'graded_count'         => $countGraded,
                'attended_count'       => $countGraded,
                'conducted_count'      => $conductedExperimentsCount,
                'total_exp_count'      => $conductedExperimentsCount,
                'total_syllabus_count' => $totalExperiments,
                'avg_lab_work'         => $avgLabWork,
                'score_t1'             => (float)$scoreT1,
                'score_t2'             => (float)$scoreT2,
                'avg_test_40'          => round($avgTest40, 2),
                'scaled_tests_15'      => $scaledTests15,
                'total_cia'            => $totalCIA,
            ];
        });

        return view('staff_mobile_virtual_lab', [
            'batchSubject'              => $batchSubject,
            'experiments'               => $experiments,
            'totalExperiments'          => $totalExperiments,
            'conductedExperimentsCount' => $conductedExperimentsCount,
            'tests'                     => $tests,
            'studentsData'              => $studentsData,
            'subjectId'                 => $subjectId,
        ]);
    }
}
