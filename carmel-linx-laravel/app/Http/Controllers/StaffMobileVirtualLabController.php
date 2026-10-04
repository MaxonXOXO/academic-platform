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
        $batchUniqueSlots = ['1' => [], '2' => [], 'Whole' => []];
        $allUniqueSlots = [];
        $studentPresentSlots = [];

        foreach ($classLogs as $log) {
            $slotKey = $log->date . '_P' . $log->period;
            $allUniqueSlots[$slotKey] = true;
            $sb = (string)($log->sub_batch ?? 'Whole');
            if ($sb === '1' || $sb === 1) {
                $batchUniqueSlots['1'][$slotKey] = true;
            } elseif ($sb === '2' || $sb === 2) {
                $batchUniqueSlots['2'][$slotKey] = true;
            } else {
                $batchUniqueSlots['Whole'][$slotKey] = true;
            }

            $pList = json_decode($log->present_students ?? '[]', true) ?: [];
            if (is_array($pList)) {
                foreach ($pList as $rNo) {
                    $studentPresentSlots[$rNo][$slotKey] = true;
                }
            }
        }

        $b1Scheduled = count($batchUniqueSlots['1']) + count($batchUniqueSlots['Whole']);
        $b2Scheduled = count($batchUniqueSlots['2']) + count($batchUniqueSlots['Whole']);
        $wholeScheduled = count($allUniqueSlots);
        $totalAttClasses = $wholeScheduled ?: $classLogs->count();

        $assignedBatches = \App\Models\R26StudentLabBatch::where('batch_subject_id', $subjectId)
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->pluck('lab_batch', 'reg_no');

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

        // Authoritative official attendance from TEAMS upload in student_attendance
        $officialAttendance = DB::table('student_attendance')
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // ── Per-student data payload ───────────────────────────────────────────
        $studentsData = $students->map(function ($student, $sIdx) use (
            $batchSubject, $experiments, $totalExperiments,
            $allExpMarks, $evaluations, $tests, $allTestMarks,
            $t1, $t2, $totalAttClasses, $b1Scheduled, $b2Scheduled, $wholeScheduled, $studentPresentSlots, $conductedExperimentsCount, $assignedBatches, $officialAttendance
        ) {
            $regNo = $student->reg_no;

            $labBatch = null;
            if (isset($assignedBatches[$regNo])) {
                $labBatch = (string)$assignedBatches[$regNo];
            } elseif ($batchSubject->lab_batch_cutoff && $student->roll_no !== null) {
                $labBatch = ((int)$student->roll_no <= (int)$batchSubject->lab_batch_cutoff) ? '1' : '2';
            } elseif ($batchSubject->lab_batch_mode === 'full') {
                $labBatch = '1';
            } else {
                $mid = (int)ceil($student->count ?? 25);
                $labBatch = ($sIdx < $mid) ? '1' : '2';
            }

            // Fixed batch-based conducted hours
            if ($batchSubject->subject_type === 'Theory') {
                $totalForStudent = $wholeScheduled ?: $totalAttClasses;
            } elseif ($labBatch === '1') {
                $totalForStudent = $b1Scheduled > 0 ? $b1Scheduled : ($wholeScheduled ?: $totalAttClasses);
            } elseif ($labBatch === '2') {
                $totalForStudent = $b2Scheduled > 0 ? $b2Scheduled : ($wholeScheduled ?: $totalAttClasses);
            } else {
                $totalForStudent = $wholeScheduled ?: $totalAttClasses;
            }

            // ── Attendance ─────────────────────────────────────────────────────
            $rawPresent = isset($studentPresentSlots[$regNo]) ? count($studentPresentSlots[$regNo]) : 0;
            $presentAtt = min($totalForStudent, $rawPresent);
            $logAttPct = $totalForStudent > 0 ? round(($presentAtt / $totalForStudent) * 100, 2) : 100.0;

            // Authoritative attendance: TEAMS uploaded attendance is official for percentage and CIA attendance mark
            $stOfficial = $officialAttendance->get($regNo, collect());
            if ($stOfficial->isNotEmpty()) {
                if ($batchSubject->subject_type !== 'Theory' && ($batchSubject->lab_batch_mode === 'split' || !empty($labBatch))) {
                    $stFiltered = $stOfficial->filter(function($att) use ($labBatch) {
                        $sb = (string)($att->sub_batch ?? 'Whole');
                        if ($sb === $labBatch || $sb === 'Whole') return true;
                        return in_array($att->status, ['Present', 'Late']);
                    });
                    if ($stFiltered->isNotEmpty()) {
                        $stOfficial = $stFiltered;
                    }
                }
                $offTot = $stOfficial->count();
                $offPres = $stOfficial->whereIn('status', ['Present', 'Late'])->count();
                $attPct = ($offTot > 0) ? round(($offPres / $offTot) * 100, 2) : 100.0;
            } else {
                $attPct = $logAttPct;
            }
            $calculatedAttMark = \App\Services\AttainmentService::calculateR21AttendanceMark($attPct, 15.0);
            $attendanceMarks = $calculatedAttMark;

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

            // Lab Work mark = average across conducted/completed experiments (max 37.5)
            $totalCompletedExps = ($conductedExperimentsCount > 0) ? $conductedExperimentsCount : ($totalExperiments > 0 ? $totalExperiments : 1);
            $totalDivisor = max($totalCompletedExps, $countGraded, 1);
            $avgLabWork = round($sumExpTotal / $totalDivisor, 2);

            // ── Test marks (Max 15) ───────────────────────────────────────────
            $scoreT1 = $t1
                ? $allTestMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->sum('marks_obtained')
                : 0.0;
            $scoreT2 = $t2
                ? $allTestMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->sum('marks_obtained')
                : 0.0;

            $scaledTests15 = round(($scoreT1 + $scoreT2) / 2, 2);
            $avgTest40     = $scaledTests15;

            // ── CIA Total (identical formula to desktop) ───────────────────────
            $totalCIA = (float)round($avgLabWork + $openEndedMarks + $scaledTests15 + $attendanceMarks);

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
