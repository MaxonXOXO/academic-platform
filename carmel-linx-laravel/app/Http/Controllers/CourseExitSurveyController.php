<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\Student;

class CourseExitSurveyController extends Controller
{
    /**
     * Initiate a course exit survey for a subject.
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

            // Check if there is already an active exit survey
            $activeSurvey = DB::table('course_exit_surveys')
                ->where('batch_subject_id', $subjectId)
                ->where('status', 'Active')
                ->first();

            if ($activeSurvey) {
                return response()->json(['status' => 'ERROR', 'message' => 'A course exit survey is already active for this subject.']);
            }

            $facultyName = Session::get('userName') ?? 'Faculty Member';
            $customQuestions = $request->input('questions');

            DB::table('course_exit_surveys')->insert([
                'batch_subject_id' => $subjectId,
                'faculty_name' => $facultyName,
                'status' => 'Active',
                'custom_questions' => $customQuestions ? json_encode($customQuestions) : null,
                'initiated_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Course Exit survey initiated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Close and finalize an active exit survey.
     */
    public function closeSurvey(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            $affected = DB::table('course_exit_surveys')
                ->where('batch_subject_id', $subjectId)
                ->where('status', 'Active')
                ->update([
                    'status' => 'Completed',
                    'updated_at' => now()
                ]);

            if ($affected === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'No active exit survey found to close.']);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Course Exit survey closed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Fetch active or completed exit survey results summary.
     */
    public function getSurveyResults(Request $request, $subjectId)
    {
        try {
            $batchSubject = BatchSubject::with('classroom')->find($subjectId);
            if (!$batchSubject) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
            }

            $survey = DB::table('course_exit_surveys')
                ->where('batch_subject_id', $subjectId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$survey) {
                return response()->json(['status' => 'INACTIVE']);
            }

            $totalStudents = Student::where('classroom_id', $batchSubject->classroom_id)->count();

            $responses = DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $survey->id)
                ->get();

            $respondedCount = $responses->count();

            $averages = [
                'CO1' => 0.0,
                'CO2' => 0.0,
                'CO3' => 0.0,
                'CO4' => 0.0,
            ];
            $attainmentPercentages = [
                'CO1' => 0.0,
                'CO2' => 0.0,
                'CO3' => 0.0,
                'CO4' => 0.0,
            ];

            if ($respondedCount > 0) {
                $avgCo1 = ($responses->avg('co1_q1') + $responses->avg('co1_q2')) / 2;
                $avgCo2 = ($responses->avg('co2_q3') + $responses->avg('co2_q4')) / 2;
                $avgCo3 = ($responses->avg('co3_q5') + $responses->avg('co3_q6')) / 2;
                $avgCo4 = ($responses->avg('co4_q7') + $responses->avg('co4_q8') + $responses->avg('co4_q9')) / 3;

                $averages = [
                    'CO1' => round($avgCo1, 2),
                    'CO2' => round($avgCo2, 2),
                    'CO3' => round($avgCo3, 2),
                    'CO4' => round($avgCo4, 2),
                ];

                $attainmentPercentages = [
                    'CO1' => round(($avgCo1 / 3) * 100, 1),
                    'CO2' => round(($avgCo2 / 3) * 100, 1),
                    'CO3' => round(($avgCo3 / 3) * 100, 1),
                    'CO4' => round(($avgCo4 / 3) * 100, 1),
                ];
            }

            $attainmentLevels = [];
            $attainmentRatings = [];
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coKey) {
                $pct = $attainmentPercentages[$coKey] ?? 0;
                $lvl = ($pct >= 70) ? 3 : (($pct >= 60) ? 2 : (($pct >= 50) ? 1 : 0));
                $rtg = ($pct >= 70) ? 'High' : (($pct >= 60) ? 'Medium' : (($pct >= 50) ? 'Low' : 'Nil'));
                $attainmentLevels[$coKey] = $lvl;
                $attainmentRatings[$coKey] = $rtg;
            }

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'survey' => $survey,
                    'total_students' => $totalStudents,
                    'responded_count' => $respondedCount,
                    'averages' => $averages,
                    'attainment_percentages' => $attainmentPercentages,
                    'attainment_levels' => $attainmentLevels,
                    'attainment_ratings' => $attainmentRatings,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Render the student course exit survey form.
     */
    public function studentViewSurvey($surveyId)
    {
        $regNo = Session::get('userId');
        if (!$regNo || Session::get('userRole') !== 'Student') {
            return redirect('/');
        }

        $survey = DB::table('course_exit_surveys')
            ->join('batch_subjects', 'course_exit_surveys.batch_subject_id', '=', 'batch_subjects.id')
            ->leftJoin('class_management', 'batch_subjects.classroom_id', '=', 'class_management.classroom_id')
            ->leftJoin('r26_class_management', 'batch_subjects.classroom_id', '=', 'r26_class_management.classroom_id')
            ->where('course_exit_surveys.id', $surveyId)
            ->select(
                'course_exit_surveys.*', 
                'batch_subjects.subject_name', 
                'batch_subjects.subject_code',
                'batch_subjects.semester',
                DB::raw("COALESCE(class_management.batch_year, r26_class_management.batch_year) as batch_year")
            )
            ->first();

        if (!$survey || $survey->status !== 'Active') {
            return "Course Exit Survey is not active or does not exist.";
        }

        // Check if student has already responded
        $responded = DB::table('student_course_exit_responses')
            ->where('exit_survey_id', $surveyId)
            ->where('reg_no', $regNo)
            ->exists();

        if ($responded) {
            return "You have already submitted your response for this Course Exit survey.";
        }

        return view('student_course_exit_survey', compact('survey'));
    }

    /**
     * Submit student course exit survey answers.
     */
    public function studentSubmitSurvey(Request $request)
    {
        $regNo = Session::get('userId');
        if (!$regNo || Session::get('userRole') !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized']);
        }

        $request->validate([
            'survey_id' => 'required|integer',
            'q1' => 'required|integer|min:1|max:3',
            'q2' => 'required|integer|min:1|max:3',
            'q3' => 'required|integer|min:1|max:3',
            'q4' => 'required|integer|min:1|max:3',
            'q5' => 'required|integer|min:1|max:3',
            'q6' => 'required|integer|min:1|max:3',
            'q7' => 'required|integer|min:1|max:3',
            'q8' => 'required|integer|min:1|max:3',
            'q9' => 'required|integer|min:1|max:3',
            'q10' => 'required|integer|min:1|max:3',
        ]);

        $surveyId = $request->input('survey_id');

        try {
            $survey = DB::table('course_exit_surveys')->where('id', $surveyId)->first();
            if (!$survey || $survey->status !== 'Active') {
                return response()->json(['status' => 'ERROR', 'message' => 'Survey is no longer active.']);
            }

            // Verify unique submission
            $exists = DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $surveyId)
                ->where('reg_no', $regNo)
                ->exists();

            if ($exists) {
                return response()->json(['status' => 'ERROR', 'message' => 'You have already submitted a response for this survey.']);
            }

            DB::table('student_course_exit_responses')->insert([
                'exit_survey_id' => $surveyId,
                'reg_no' => $regNo,
                'co1_q1' => $request->input('q1'),
                'co1_q2' => $request->input('q2'),
                'co2_q3' => $request->input('q3'),
                'co2_q4' => $request->input('q4'),
                'co3_q5' => $request->input('q5'),
                'co3_q6' => $request->input('q6'),
                'co4_q7' => $request->input('q7'),
                'co4_q8' => $request->input('q8'),
                'co4_q9' => $request->input('q9'),
                'co_overall_q10' => $request->input('q10'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Course Exit feedback submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Print the PDF layout for the Course Exit Survey.
     */
    public function printSurveyReport($subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'HOD', 'Principal'])) {
            return redirect('/');
        }

        $batchSubject = BatchSubject::with('classroom')->find($subjectId);
        if (!$batchSubject) return "Subject not found.";

        $survey = DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->where('status', 'Completed')
            ->first();

        if (!$survey) {
            $survey = DB::table('course_exit_surveys')
                ->where('batch_subject_id', $subjectId)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$survey) return "No Course Exit survey report exists for this classroom subject.";

        // Classroom & Institutional Metadata
        $classroom = DB::table('class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $batchYear = $classroom->batch_year ?? $batchSubject->classroom->batch_year ?? '2021 - 2024';
        $branch = $classroom->branch ?? $classroom->department ?? $batchSubject->classroom->branch ?? 'Civil Engineering';
        $semesterNum = $batchSubject->semester ?? 5;
        $academicYear = 'Year ' . (ceil($semesterNum / 2)) . ' (Semester ' . $semesterNum . ')';
        $facultyName = $survey->faculty_name ?? Session::get('userName') ?? 'Faculty Member';
        $revision = $batchSubject->syllabus_revision_code ?? 'R-2021';
        $subjectCode = method_exists($batchSubject, 'getFormattedSubjectCodeAttribute') 
            ? $batchSubject->formatted_subject_code 
            : strtoupper($batchSubject->subject_code);

        $totalStudents = Student::where('classroom_id', $batchSubject->classroom_id)->count();
        if ($totalStudents === 0) $totalStudents = 60; // Default fallback for demonstration if roster empty

        $responses = DB::table('student_course_exit_responses')
            ->where('exit_survey_id', $survey->id)
            ->get();

        $respondedCount = $responses->count();

        $fields = [
            'co1_q1', 'co1_q2',
            'co2_q3', 'co2_q4',
            'co3_q5', 'co3_q6',
            'co4_q7', 'co4_q8', 'co4_q9',
            'co_overall_q10'
        ];

        $averages = [];
        $satisfaction = [];
        $scaleCounts = [];

        foreach ($fields as $field) {
            if ($respondedCount > 0) {
                $avg = $responses->avg($field);
                $averages[$field] = round($avg, 2);
                
                // Satisfaction = percentage of scores >= 2 (Medium / High)
                $satisfied = $responses->where($field, '>=', 2)->count();
                $satisfaction[$field] = round(($satisfied / $respondedCount) * 100, 1);

                $scaleCounts[$field] = [
                    'high' => $responses->where($field, 3)->count(),
                    'med'  => $responses->where($field, 2)->count(),
                    'low'  => $responses->where($field, 1)->count(),
                ];
            } else {
                $averages[$field] = 0.0;
                $satisfaction[$field] = 0.0;
                $scaleCounts[$field] = ['high' => 0, 'med' => 0, 'low' => 0];
            }
        }

        // Calculate CO Indirect Attainments (Scale 1 to 3 & NBA High/Med/Low Rating)
        $cosData = [
            'CO1' => [
                'name' => 'CO1: Core subject knowledge, principles, and course Outcomes mapping.',
                'pct' => $respondedCount > 0 ? round((($averages['co1_q1'] + $averages['co1_q2']) / 2) / 3 * 100, 1) : 0,
                'avg' => $respondedCount > 0 ? round(($averages['co1_q1'] + $averages['co1_q2']) / 2, 2) : 0
            ],
            'CO2' => [
                'name' => 'CO2: Analytical reasoning, problem-solving, and design methods.',
                'pct' => $respondedCount > 0 ? round((($averages['co2_q3'] + $averages['co2_q4']) / 2) / 3 * 100, 1) : 0,
                'avg' => $respondedCount > 0 ? round(($averages['co2_q3'] + $averages['co2_q4']) / 2, 2) : 0
            ],
            'CO3' => [
                'name' => 'CO3: Tool usage, lab execution, safety standards, and practical skills.',
                'pct' => $respondedCount > 0 ? round((($averages['co3_q5'] + $averages['co3_q6']) / 2) / 3 * 100, 1) : 0,
                'avg' => $respondedCount > 0 ? round(($averages['co3_q5'] + $averages['co3_q6']) / 2, 2) : 0
            ],
            'CO4' => [
                'name' => 'CO4: Continuous assessments, engineering ethics, and lifelong learning.',
                'pct' => $respondedCount > 0 ? round((($averages['co4_q7'] + $averages['co4_q8'] + $averages['co4_q9']) / 3) / 3 * 100, 1) : 0,
                'avg' => $respondedCount > 0 ? round(($averages['co4_q7'] + $averages['co4_q8'] + $averages['co4_q9']) / 3, 2) : 0
            ]
        ];

        $coAttainments = [];
        $sumAvg = 0;
        $coCount = count($cosData);

        foreach ($cosData as $coKey => $item) {
            $pct = $item['pct'];
            $level = ($pct >= 70) ? 3 : (($pct >= 60) ? 2 : (($pct >= 50) ? 1 : 0));
            $rating = ($pct >= 70) ? 'High (Level 3)' : (($pct >= 60) ? 'Medium (Level 2)' : (($pct >= 50) ? 'Low (Level 1)' : 'Nil (Level 0)'));
            $sumAvg += $item['avg'];

            $coAttainments[$coKey] = [
                'name' => $item['name'],
                'avg' => $item['avg'],
                'percent' => $pct,
                'level' => $level,
                'rating' => $rating
            ];
        }

        $overallAvg = $coCount > 0 ? round($sumAvg / $coCount, 2) : 0;
        $overallPct = round(($overallAvg / 3) * 100, 1);
        $overallLevel = ($overallPct >= 70) ? 3 : (($overallPct >= 60) ? 2 : (($overallPct >= 50) ? 1 : 0));

        return view('classroom_course_exit_report_print', [
            'subject' => $batchSubject,
            'subjectCode' => $subjectCode,
            'survey' => $survey,
            'batchYear' => $batchYear,
            'branch' => $branch,
            'academicYear' => $academicYear,
            'facultyName' => $facultyName,
            'revision' => $revision,
            'totalStudents' => $totalStudents,
            'respondedCount' => $respondedCount,
            'averages' => $averages,
            'satisfaction' => $satisfaction,
            'scaleCounts' => $scaleCounts,
            'coAttainments' => $coAttainments,
            'overallAvg' => $overallAvg,
            'overallPct' => $overallPct,
            'overallLevel' => $overallLevel
        ]);
    }
}
