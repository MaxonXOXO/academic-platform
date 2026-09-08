<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\Student;

class MidSemSurveyController extends Controller
{
    /**
     * Initiate a mid-semester survey for a subject.
     */
    /**
     * Initiate a mid-semester survey for a subject.
     */
    public function initiateSurvey(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $batchSubject = BatchSubject::find($subjectId);
            if (!$batchSubject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }

            // Check if there is already an active survey
            $activeSurvey = DB::table('mid_semester_surveys')
                ->where('batch_subject_id', $subjectId)
                ->where('status', 'Active')
                ->first();

            if ($activeSurvey) {
                return response()->json(['status' => 'ERROR', 'message' => 'A survey is already active for this subject.']);
            }

            $facultyName = Session::get('userName') ?? 'Faculty Member';
            $customQuestions = $request->input('questions');

            DB::table('mid_semester_surveys')->insert([
                'batch_subject_id' => $subjectId,
                'faculty_name' => $facultyName,
                'status' => 'Active',
                'custom_questions' => $customQuestions ? json_encode($customQuestions) : null,
                'initiated_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Mid-Semester survey initiated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Close and finalize an active survey.
     */
    public function closeSurvey(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $affected = DB::table('mid_semester_surveys')
                ->where('batch_subject_id', $subjectId)
                ->where('status', 'Active')
                ->update([
                    'status' => 'Completed',
                    'updated_at' => now()
                ]);

            if ($affected === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'No active survey found to close.']);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Survey closed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Fetch active or completed survey results.
     */
    public function getSurveyResults(Request $request, $subjectId)
    {
        try {
            $batchSubject = BatchSubject::with('classroom')->find($subjectId);
            if (!$batchSubject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }

            $survey = DB::table('mid_semester_surveys')
                ->where('batch_subject_id', $subjectId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$survey) {
                return response()->json(['status' => 'INACTIVE']);
            }

            $totalStudents = Student::where('classroom_id', $batchSubject->classroom_id)->count();

            $responses = DB::table('student_survey_responses')
                ->where('survey_id', $survey->id)
                ->get();

            $respondedCount = $responses->count();

            // Calculate score distribution and averages for old columns (backward compatibility)
            $averages = [
                'pace' => 0.0,
                'clarity' => 0.0,
                'interaction' => 0.0,
                'practicality' => 0.0,
                'evaluation' => 0.0,
            ];

            $distribution = [
                'pace' => [3 => 0, 2 => 0, 1 => 0],
                'clarity' => [3 => 0, 2 => 0, 1 => 0],
                'interaction' => [3 => 0, 2 => 0, 1 => 0],
                'practicality' => [3 => 0, 2 => 0, 1 => 0],
                'evaluation' => [3 => 0, 2 => 0, 1 => 0],
            ];

            if ($respondedCount > 0) {
                $paceSum = 0;
                $claritySum = 0;
                $interactionSum = 0;
                $practicalitySum = 0;
                $evaluationSum = 0;

                foreach ($responses as $r) {
                    $p = $r->q6_syllabus_pace ?? $r->pace_score ?? 2;
                    $c = $r->q7_concept_clarity ?? $r->clarity_score ?? 2;
                    $i = $r->q9_student_interaction ?? $r->interaction_score ?? 2;
                    $pr = $r->q13_branch_specific ?? $r->practicality_score ?? 2;
                    $ev = $r->q11_evaluation_fairness ?? $r->evaluation_score ?? 2;

                    $paceSum += $p;
                    $claritySum += $c;
                    $interactionSum += $i;
                    $practicalitySum += $pr;
                    $evaluationSum += $ev;

                    $distribution['pace'][in_array($p, [1, 2, 3]) ? $p : 2]++;
                    $distribution['clarity'][in_array($c, [1, 2, 3]) ? $c : 2]++;
                    $distribution['interaction'][in_array($i, [1, 2, 3]) ? $i : 2]++;
                    $distribution['practicality'][in_array($pr, [1, 2, 3]) ? $pr : 2]++;
                    $distribution['evaluation'][in_array($ev, [1, 2, 3]) ? $ev : 2]++;
                }

                $averages['pace'] = round($paceSum / $respondedCount, 2);
                $averages['clarity'] = round($claritySum / $respondedCount, 2);
                $averages['interaction'] = round($interactionSum / $respondedCount, 2);
                $averages['practicality'] = round($practicalitySum / $respondedCount, 2);
                $averages['evaluation'] = round($evaluationSum / $respondedCount, 2);
            }

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'survey' => $survey,
                    'total_students' => $totalStudents,
                    'responded_count' => $respondedCount,
                    'averages' => $averages,
                    'distribution' => $distribution
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function saveNotes(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD', 'Tutor'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $updateData = [];

            if ($request->has('improvements_noted')) {
                $updateData['improvements_noted'] = $request->input('improvements_noted');
            }
            if ($request->has('action_taken')) {
                $updateData['action_taken'] = $request->input('action_taken');
            }
            if ($request->has('action_taken_by_tutor')) {
                $updateData['action_taken_by_tutor'] = $request->input('action_taken_by_tutor');
            }
            if ($request->has('action_taken_by_hod')) {
                $updateData['action_taken_by_hod'] = $request->input('action_taken_by_hod');
            }

            if (empty($updateData)) {
                return response()->json(['status' => 'ERROR', 'message' => 'No notes data provided to update.']);
            }

            $updateData['updated_at'] = now();

            $affected = DB::table('mid_semester_surveys')
                ->where('batch_subject_id', $subjectId)
                ->where('status', 'Completed')
                ->update($updateData);

            if ($affected === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'No completed survey found to update notes. Survey must be closed first.']);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Notes saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Render the student survey form page.
     */
    public function studentViewSurvey($surveyId)
    {
        $regNo = Session::get('userId');
        if (!$regNo || Session::get('userRole') !== 'Student') {
            return redirect('/');
        }

        $survey = DB::table('mid_semester_surveys')
            ->join('batch_subjects', 'mid_semester_surveys.batch_subject_id', '=', 'batch_subjects.id')
            ->leftJoin('class_management', 'batch_subjects.classroom_id', '=', 'class_management.classroom_id')
            ->leftJoin('r26_class_management', 'batch_subjects.classroom_id', '=', 'r26_class_management.classroom_id')
            ->where('mid_semester_surveys.id', $surveyId)
            ->select(
                'mid_semester_surveys.*', 
                'batch_subjects.subject_name', 
                'batch_subjects.subject_code',
                'batch_subjects.semester',
                DB::raw("COALESCE(class_management.batch_year, r26_class_management.batch_year) as batch_year"),
                DB::raw("COALESCE(class_management.branch, r26_class_management.branch) as branch")
            )
            ->first();

        if (!$survey || $survey->status !== 'Active') {
            return "Survey is not active or does not exist.";
        }

        // Check if student has already responded
        $responded = DB::table('student_survey_responses')
            ->where('survey_id', $surveyId)
            ->where('reg_no', $regNo)
            ->exists();

        if ($responded) {
            return "You have already submitted your response for this survey.";
        }

        // Determine branch code/name to show correct laboratory question
        $student = Student::where('reg_no', $regNo)->first();
        $branch = $student ? $student->branch : 'Other';

        return view('student_survey', [
            'survey' => $survey,
            'branch' => $branch
        ]);
    }

    /**
     * Submit student survey response.
     */
    public function studentSubmitSurvey(Request $request)
    {
        $regNo = Session::get('userId');
        if (!$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $request->validate([
            'survey_id' => 'required|integer',
            'q5' => 'required|integer|min:1|max:3',
            'q6' => 'required|integer|min:1|max:3',
            'q7' => 'required|integer|min:1|max:3',
            'q8' => 'required|integer|min:1|max:3',
            'q9' => 'required|integer|min:1|max:3',
            'q10' => 'required|integer|min:1|max:3',
            'q11' => 'required|integer|min:1|max:3',
            'q12' => 'required|integer|min:1|max:3',
            'q13' => 'nullable|integer|min:1|max:3',
            'q17' => 'nullable|string|max:1000',
            'q18' => 'nullable|string|max:1000',
        ]);

        $surveyId = $request->input('survey_id');

        try {
            $survey = DB::table('mid_semester_surveys')->where('id', $surveyId)->first();
            if (!$survey || $survey->status !== 'Active') {
                return response()->json(['status' => 'ERROR', 'message' => 'Survey is no longer active.']);
            }

            // Verify unique submission
            $exists = DB::table('student_survey_responses')
                ->where('survey_id', $surveyId)
                ->where('reg_no', $regNo)
                ->exists();

            if ($exists) {
                return response()->json(['status' => 'ERROR', 'message' => 'You have already submitted a response for this survey.']);
            }

            // Populate both old columns (for backward compatibility) and new columns
            DB::table('student_survey_responses')->insert([
                'survey_id' => $surveyId,
                'reg_no' => $regNo,
                // Backward compatibility columns
                'pace_score' => $request->input('q6'),
                'clarity_score' => $request->input('q7'),
                'interaction_score' => $request->input('q9'),
                'practicality_score' => $request->input('q13') ?? $request->input('q7'),
                'evaluation_score' => $request->input('q11'),
                // New SAR 12-question columns
                'q5_co_communication' => $request->input('q5'),
                'q6_syllabus_pace' => $request->input('q6'),
                'q7_concept_clarity' => $request->input('q7'),
                'q8_teaching_tools' => $request->input('q8'),
                'q9_student_interaction' => $request->input('q9'),
                'q10_assessment_alignment' => $request->input('q10'),
                'q11_evaluation_fairness' => $request->input('q11'),
                'q12_slow_learner_support' => $request->input('q12'),
                'q13_branch_specific' => $request->input('q13'),
                'q17_difficult_topics' => $request->input('q17'),
                'q18_suggestions' => $request->input('q18'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Survey feedback submitted successfully.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Print the PDF layout for the Mid-Semester Survey.
     */
    public function printSurveyReport($subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD', 'Principal'])) {
            return redirect('/');
        }

        $batchSubject = BatchSubject::with('classroom')->find($subjectId);
        if (!$batchSubject) return "Subject not found.";

        $survey = DB::table('mid_semester_surveys')
            ->where('batch_subject_id', $subjectId)
            ->where('status', 'Completed')
            ->first();

        if (!$survey) {
            $survey = DB::table('mid_semester_surveys')
                ->where('batch_subject_id', $subjectId)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$survey) {
            $survey = (object)[
                'id' => 0,
                'batch_subject_id' => $subjectId,
                'faculty_name' => Session::get('userName') ?? 'Faculty Member',
                'status' => 'Pending',
                'improvements_noted' => 'None recorded.',
                'action_taken' => 'Ongoing regular syllabus progress tracking.',
                'action_taken_by_tutor' => 'Academic mentoring provided.',
                'action_taken_by_hod' => 'Reviewed and approved.',
                'created_at' => now()
            ];
        }

        $totalStudents = Student::where('classroom_id', $batchSubject->classroom_id)->count();

        $responses = $survey->id ? DB::table('student_survey_responses')
            ->where('survey_id', $survey->id)
            ->get() : collect();

        $respondedCount = $responses->count();

        // Calculate averages for the 9 questions (8 core + 1 branch specific)
        $fields = [
            'q5_co_communication',
            'q6_syllabus_pace',
            'q7_concept_clarity',
            'q8_teaching_tools',
            'q9_student_interaction',
            'q10_assessment_alignment',
            'q11_evaluation_fairness',
            'q12_slow_learner_support',
            'q13_branch_specific'
        ];

        $averages = [];
        $satisfaction = [];

        foreach ($fields as $field) {
            if ($respondedCount > 0) {
                // Filter out nulls for branch specific if some students didn't fill it
                $validResponses = $responses->whereNotNull($field);
                $count = $validResponses->count();
                
                if ($count > 0) {
                    $avg = $validResponses->avg($field);
                    $averages[$field] = round($avg, 2);
                    
                    // Satisfaction rate = percentage of scores >= 2 (Satisfactory/Good)
                    $satisfied = $validResponses->where($field, '>=', 2)->count();
                    $satisfaction[$field] = round(($satisfied / $count) * 100, 1);
                } else {
                    $averages[$field] = 0.0;
                    $satisfaction[$field] = 0.0;
                }
            } else {
                $averages[$field] = 0.0;
                $satisfaction[$field] = 0.0;
            }
        }

        // CO Mappings and attainment calculations:
        // Formula: Attainment % = (Avg Score / 3) * 100
        $coAttainments = [
            'CO1' => [
                'name' => 'CO1: Core Knowledge & Course Outcomes',
                'percent' => round((($averages['q5_co_communication'] + $averages['q6_syllabus_pace']) / 2) / 3 * 100, 1)
            ],
            'CO2' => [
                'name' => 'CO2: Problem Solving & Design Capabilities',
                'percent' => round((($averages['q7_concept_clarity'] + $averages['q8_teaching_tools']) / 2) / 3 * 100, 1)
            ],
            'CO3' => [
                'name' => 'CO3: Modern Tools, Labs & Practical Application',
                'percent' => round((($averages['q9_student_interaction'] + ($averages['q13_branch_specific'] > 0 ? $averages['q13_branch_specific'] : $averages['q7_concept_clarity'])) / 2) / 3 * 100, 1)
            ],
            'CO4' => [
                'name' => 'CO4: Continuous Assessment & Evaluation Standards',
                'percent' => round((($averages['q10_assessment_alignment'] + $averages['q11_evaluation_fairness'] + $averages['q12_slow_learner_support']) / 3) / 3 * 100, 1)
            ]
        ];

        // Open-ended feedback collections
        $difficultTopics = $responses->whereNotNull('q17_difficult_topics')->pluck('q17_difficult_topics')->toArray();
        $suggestions = $responses->whereNotNull('q18_suggestions')->pluck('q18_suggestions')->toArray();

        return view('classroom_survey_report_print', [
            'subject' => $batchSubject,
            'survey' => $survey,
            'totalStudents' => $totalStudents,
            'respondedCount' => $respondedCount,
            'averages' => $averages,
            'satisfaction' => $satisfaction,
            'coAttainments' => $coAttainments,
            'difficultTopics' => $difficultTopics,
            'suggestions' => $suggestions
        ]);
    }

}
