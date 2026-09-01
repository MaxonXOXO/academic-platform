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
 * Strictly for staff on mobile. Does NOT touch R2026 models or routes.
 * Desktop interfaces remain completely unchanged.
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

        $isPractical = str_contains($subTypeLower, 'lab') || 
                       str_contains($subTypeLower, 'practical') || 
                       str_contains($subTypeLower, 'practicum') || 
                       str_contains($subTypeLower, 'drawing') || 
                       str_contains($subTypeLower, 'workshop');
        $isR2021     = str_contains($revCode, '2021') || str_contains($revCode, 'R21') || str_contains($revCode, 'REV2021');

        if (!$isPractical || !$isR2021) {
            return redirect('/staff/mobile')
                ->with('error', 'Virtual Lab is only available for R2021 Lab/Practical subjects.');
        }

        // Load students
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where('status', 'Approved')
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no']);

        // Load experiments
        $experiments = PracticalExperiment::where('batch_subject_id', $subjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();

        $expIds = $experiments->pluck('id')->toArray();

        // Load experiment marks (all students)
        $experimentMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)->get();

        // Load consolidated evaluations (open-ended / attendance marks / board exam)
        $evaluations = PracticalEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Load practical tests
        $tests = PracticalTest::where('batch_subject_id', $subjectId)->get();
        $testIds = $tests->pluck('id')->toArray();
        $testMarks = PracticalTestMark::whereIn('practical_test_id', $testIds)->get();

        // Build per-student data payload
        $studentsData = $students->map(function ($student) use (
            $batchSubject, $experiments, $experimentMarks, $evaluations, $tests, $testMarks
        ) {
            $regNo = $student->reg_no;

            // Attendance
            $totalAtt = DB::table('student_attendance')
                ->where('reg_no', $regNo)
                ->where('subject_code', $batchSubject->subject_code)
                ->count();
            $presentAtt = $totalAtt > 0
                ? DB::table('student_attendance')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->whereIn('status', ['Present', 'Late'])
                    ->count()
                : 0;
            $attPct = $totalAtt > 0 ? round(($presentAtt / $totalAtt) * 100, 1) : 100.0;
            $suggestedAttMark = round(($attPct / 100) * 15, 2);

            // Consolidated eval (open-ended / attendance override / board exam)
            $eval = $evaluations->get($regNo);
            $openEndedMarks = $eval ? (float)$eval->micro_project : 0.0;
            $openEndedTopic = $eval ? ($eval->open_ended_topic ?? '') : '';
            $attendanceMarks = $eval ? (float)$eval->attendance_marks : $suggestedAttMark;
            $boardExam = $eval ? $eval->board_exam_marks : null;

            // Experiment marks per experiment
            $expMarksMap = [];
            $sumExp = 0;
            $countExp = 0;
            foreach ($experiments as $exp) {
                $mark = $experimentMarks
                    ->where('practical_experiment_id', $exp->id)
                    ->where('reg_no', $regNo)
                    ->first();
                $expMarksMap[$exp->id] = $mark ? [
                    'prerequisites' => (float)$mark->prerequisites,
                    'work_done'     => (float)$mark->work_done,
                    'result'        => (float)$mark->result,
                    'rough_record'  => (float)$mark->rough_record,
                    'fair_record'   => (float)$mark->fair_record,
                    'total'         => (float)$mark->total_mark,
                ] : null;

                if ($mark) {
                    $sumExp += (float)$mark->total_mark;
                    $countExp++;
                }
            }
            $avgLabWork = $countExp > 0 ? round($sumExp / $countExp, 2) : 0.0;

            // Test marks
            $t1 = $tests->where('test_name', 'Test 1')->first();
            $t2 = $tests->where('test_name', 'Test 2')->first();

            $t1Co1 = $t1 ? $testMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->where('co_tag', 'CO1')->first() : null;
            $t1Co2 = $t1 ? $testMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->where('co_tag', 'CO2')->first() : null;
            $t2Co3 = $t2 ? $testMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->where('co_tag', 'CO3')->first() : null;
            $t2Co4 = $t2 ? $testMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->where('co_tag', 'CO4')->first() : null;

            $scoreT1 = ($t1Co1 ? (float)$t1Co1->marks_obtained : 0.0) + ($t1Co2 ? (float)$t1Co2->marks_obtained : 0.0);
            $scoreT2 = ($t2Co3 ? (float)$t2Co3->marks_obtained : 0.0) + ($t2Co4 ? (float)$t2Co4->marks_obtained : 0.0);
            $avgTests = round(($scoreT1 + $scoreT2) / 2, 2);

            $totalInternal = round($avgTests + $avgLabWork + $openEndedMarks + $attendanceMarks, 2);

            return [
                'reg_no'               => $regNo,
                'name'                 => $student->name,
                'roll_no'              => $student->roll_no,
                'sbte_reg_no'          => $student->sbte_reg_no ?? $regNo,
                'att_pct'              => $attPct,
                'att_total'            => $totalAtt,
                'att_present'          => $presentAtt,
                'suggested_att_mark'   => $suggestedAttMark,
                'attendance_marks'     => $attendanceMarks,
                'open_ended_marks'     => $openEndedMarks,
                'open_ended_topic'     => $openEndedTopic,
                'board_exam'           => $boardExam,
                'exp_marks'            => $expMarksMap,
                'avg_lab_work'         => $avgLabWork,
                'test1_co1'            => $t1Co1 ? (float)$t1Co1->marks_obtained : null,
                'test1_co2'            => $t1Co2 ? (float)$t1Co2->marks_obtained : null,
                'test2_co3'            => $t2Co3 ? (float)$t2Co3->marks_obtained : null,
                'test2_co4'            => $t2Co4 ? (float)$t2Co4->marks_obtained : null,
                'score_t1'             => $scoreT1,
                'score_t2'             => $scoreT2,
                'avg_tests'            => $avgTests,
                'total_internal'       => $totalInternal,
            ];
        });

        return view('staff_mobile_virtual_lab', [
            'batchSubject'  => $batchSubject,
            'experiments'   => $experiments,
            'tests'         => $tests,
            'studentsData'  => $studentsData,
            'subjectId'     => $subjectId,
        ]);
    }
}
