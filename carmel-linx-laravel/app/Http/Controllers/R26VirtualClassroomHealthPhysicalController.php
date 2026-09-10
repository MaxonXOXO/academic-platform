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
use App\Models\R26HealthPhysicalCourseFile;
use App\Models\R26HealthPhysicalEvaluation;
use App\Models\R26HealthPhysicalFitnessTest;
use App\Models\R26HealthPhysicalEseMark;
use App\Models\StaffProfile;
use App\Models\StudentProfile;

class R26VirtualClassroomHealthPhysicalController extends Controller
{
    /**
     * Virtual Classroom for Health and Physical Education (R2026 S1 Unique Paper)
     */
    public function show($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Fetch classroom
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or Create Health & Physical Course File Record
        $hpCourseFile = R26HealthPhysicalCourseFile::where('batch_subject_id', $subjectId)->first();

        if (!$hpCourseFile) {
            $hpCourseFile = R26HealthPhysicalCourseFile::create([
                'batch_subject_id' => $subjectId,
                'program' => $classroom->department ?? 'Engineering',
                'course_title' => $batchSubject->subject_name ?: 'Health and Physical Education',
                'course_code' => $batchSubject->subject_code ?: '1009',
                'semester' => 'I',
                'type_of_course' => 'Health & Physical',
                'teaching_scheme' => '0:0:2:0',
                'contact_hours' => 30,
                'credits' => 1.0,
                'cie_marks' => 60,
                'ese_marks' => 40,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Demonstrate understanding of personal health, hygiene, and physical fitness principles.', 'cognitive_level' => 'Understand'],
                    ['id' => 'CO2', 'description' => 'Perform posture evaluation, warming-up exercises, and basic physical fitness assessments.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Demonstrate skills, techniques, and sportsmanship in chosen games/athletics.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Practice yoga, stress management, first aid, and lifestyle disease prevention.', 'cognitive_level' => 'Apply']
                ],
                'parsed_activities' => [
                    ['activity_no' => 'ACT-01', 'title' => 'Orientation, Body Mass Index (BMI) & Posture Assessment', 'co_id' => 'CO1', 'hours' => 3],
                    ['activity_no' => 'ACT-02', 'title' => 'Warming-Up Protocols & General Physical Fitness Drills', 'co_id' => 'CO2', 'hours' => 3],
                    ['activity_no' => 'ACT-03', 'title' => 'Calisthenics, Aerobics & Cardiovascular Endurance Activities', 'co_id' => 'CO2', 'hours' => 3],
                    ['activity_no' => 'ACT-04', 'title' => 'Athletic Events - Sprint, Distance Running & Relay Technique', 'co_id' => 'CO3', 'hours' => 3],
                    ['activity_no' => 'ACT-05', 'title' => 'Major Games Skill Practice (Volleyball / Football / Basketball / Badminton)', 'co_id' => 'CO3', 'hours' => 6],
                    ['activity_no' => 'ACT-06', 'title' => 'Yogic Asanas, Pranayama & Relaxation Techniques for Stress Relief', 'co_id' => 'CO4', 'hours' => 4],
                    ['activity_no' => 'ACT-07', 'title' => 'First Aid, CPR Fundamentals & Sports Injury Management', 'co_id' => 'CO4', 'hours' => 4],
                    ['activity_no' => 'ACT-08', 'title' => 'Fitness Test Evaluation, Logbook Submission & Physical Viva', 'co_id' => 'CO4', 'hours' => 4]
                ],
                'parsed_copo' => [
                    'credit' => 1.0,
                    'l_t_p_r' => '0:0:2:0',
                    'cie_marks' => 60,
                    'ese_marks' => 40,
                    'total_hours' => 30,
                    'mappings' => [
                        'CO1' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                        'CO2' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                        'CO3' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                        'CO4' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2']
                    ]
                ],
                'parsed_eval_scheme' => [
                    'day_work' => [
                        ['key' => 'c1', 'title' => 'Physical Fitness & Warm-Up', 'max_marks' => 10],
                        ['key' => 'c2', 'title' => 'Skill Execution & Technique', 'max_marks' => 15],
                        ['key' => 'c3', 'title' => 'Activity Logbook / Record', 'max_marks' => 10],
                        ['key' => 'c4', 'title' => 'Viva-Voce & Game Rules', 'max_marks' => 10],
                        ['key' => 'c5', 'title' => 'Sportsmanship & Attendance', 'max_marks' => 5]
                    ],
                    'total_max' => 50
                ]
            ]);
        }

        // Dynamic Evaluation Scheme extracted from uploaded PDF
        $evalScheme = $hpCourseFile->parsed_eval_scheme ?: [
            'day_work' => [
                ['key' => 'c1', 'title' => 'Physical Fitness & Warm-Up', 'max_marks' => 10],
                ['key' => 'c2', 'title' => 'Skill Execution & Technique', 'max_marks' => 15],
                ['key' => 'c3', 'title' => 'Activity Logbook / Record', 'max_marks' => 10],
                ['key' => 'c4', 'title' => 'Viva-Voce & Game Rules', 'max_marks' => 10],
                ['key' => 'c5', 'title' => 'Sportsmanship & Attendance', 'max_marks' => 5]
            ],
            'total_max' => 50
        ];

        // Lesson Plans
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() < 10) {
            $this->generate30HourLessonPlan($batchSubject, $hpCourseFile);
            $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        }

        // Attendance Records
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Continuous Activity Evaluations
        $activityEvals = R26HealthPhysicalEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Physical Fitness Tests CA1 & CA2
        $fitnessTests = R26HealthPhysicalFitnessTest::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // ESE Marks
        $eseMarks = R26HealthPhysicalEseMark::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Student Results Computation (60 CIE + 40 ESE = 100)
        $studentResults = $students->map(function ($student) use (
            $attendanceData,
            $activityEvals,
            $fitnessTests,
            $eseMarks
        ) {
            $regNo = $student->reg_no;

            // 1. Attendance Marks (Max 5)
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : 100.00;

            if ($attPercentage >= 90) { $attMarks = 5; }
            elseif ($attPercentage >= 80) { $attMarks = 4; }
            elseif ($attPercentage >= 75) { $attMarks = 3; }
            elseif ($attPercentage >= 70) { $attMarks = 2; }
            elseif ($attPercentage >= 65) { $attMarks = 1; }
            else { $attMarks = 0; }

            // 2. Continuous Activity Log (Max 30 CIE Marks)
            $stActs = $activityEvals->get($regNo, collect());
            $avgScore50 = $stActs->avg('total_score_50') ?: 0.00;
            $activityMarks = round((($avgScore50 / 50.0) * 30.0) * 2) / 2;

            // 3. Fitness Tests CA1 & CA2 (Max 15 CIE Marks)
            $stTests = $fitnessTests->get($regNo, collect());
            $ca1 = $stTests->where('test_no', 'CA1')->first();
            $ca2 = $stTests->where('test_no', 'CA2')->first();
            $ca1Score = ($ca1 && !$ca1->is_absent) ? $ca1->total_score_40 : 0.00;
            $ca2Score = ($ca2 && !$ca2->is_absent) ? $ca2->total_score_40 : 0.00;

            $ca1Marks = round((($ca1Score / 40.0) * 7.5) * 2) / 2;
            $ca2Marks = round((($ca2Score / 40.0) * 7.5) * 2) / 2;
            $testMarks = $ca1Marks + $ca2Marks;

            // 4. Logbook / Posture & Activity Record (Max 10 CIE Marks)
            $logbookMarks = 10.0;

            // Total CIE Marks (Max 60)
            $totalCieMarks = round(($attMarks + $activityMarks + $testMarks) * 2) / 2;
            if ($totalCieMarks > 60.0) $totalCieMarks = 60.0;

            // ESE Marks (Max 40)
            $stEse = $eseMarks->get($regNo);
            $fScore = $stEse ? floatval($stEse->fitness_test_score) : 0.00;
            $sScore = $stEse ? floatval($stEse->skill_demo_score) : 0.00;
            $vScore = $stEse ? floatval($stEse->viva_score) : 0.00;
            $rScore = $stEse ? floatval($stEse->record_score) : 0.00;
            $totalEse = ($stEse && !$stEse->is_absent) ? ($fScore + $sScore + $vScore + $rScore) : 0.00;

            $totalCourseMarks = $totalCieMarks + $totalEse;
            $isPassed = ($totalEse >= 16.0 && $totalCourseMarks >= 40.0);

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_percentage' => $attPercentage,
                'att_marks' => $attMarks,
                'activity_marks' => $activityMarks,
                'ca1_score' => $ca1Score,
                'ca2_score' => $ca2Score,
                'test_marks' => $testMarks,
                'total_cie_marks' => $totalCieMarks,
                'ese_fitness' => $fScore,
                'ese_skill' => $sScore,
                'ese_viva' => $vScore,
                'ese_record' => $rScore,
                'total_ese' => $totalEse,
                'total_course_marks' => $totalCourseMarks,
                'is_passed' => $isPassed
            ];
        });

        // Assigned Staff
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        // Surveys for Indirect Attainment
        $exitSurvey = DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $exitSurveyResponses = collect();
        if ($exitSurvey) {
            $exitSurveyResponses = DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }

        $midSemSurvey = DB::table('mid_semester_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $midSemResponses = collect();
        if ($midSemSurvey) {
            $midSemResponses = DB::table('student_survey_responses')
                ->where('survey_id', $midSemSurvey->id)
                ->get();
        }

        // CO-PO Matrix & Attainment Calculation
        $copoPayload = $hpCourseFile->parsed_copo ?: [];
        $mappings = $copoPayload['mappings'] ?? [];

        if (empty($mappings)) {
            $mappings = [
                'CO1' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                'CO2' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                'CO3' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2'],
                'CO4' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'-', 'PO4'=>'-', 'PO5'=>'3', 'PO6'=>'3', 'PO7'=>'2', 'PO8'=>'3', 'PO9'=>'3', 'PO10'=>'2', 'PO11'=>'2']
            ];
        }

        $cf = \App\Models\CourseFile::where('batch_subject_id', $subjectId)->first();
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

        $totalStudents = max(1, $studentResults->count());
        $directStats = [];
        $indirectStats = [];
        $combinedStats = [];

        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $attainedCount = $studentResults->filter(function($s) use ($cieThreshold) {
                // Strictly exclude attendance marks from academic outcome attainment
                $academicScore = max(0, ($s['total_cie_marks'] ?? 0) - ($s['att_marks'] ?? 0)) + ($s['total_ese'] ?? 0);
                $maxMarks = max(1, ($s['max_cie_marks'] ?? 50) + ($s['max_ese_marks'] ?? 50) - ($s['max_att_marks'] ?? 0));
                $pct = ($academicScore / $maxMarks) * 100;
                return $pct >= $cieThreshold;
            })->count();

            $percentage = ($attainedCount / $totalStudents) * 100;
            $directLevel = (float)\App\Services\AttainmentService::calculateBatchLevel($percentage, $lvl3Val, $lvl2Val, $lvl1Val);
            
            $directStats[$coTag] = [
                'count' => $attainedCount,
                'percentage' => round($percentage, 1),
                'level' => $directLevel
            ];

            // Indirect attainment from surveys
            $indirectLevel = 2.5;
            $indirectAvg = 2.50;
            $indirectPct = 83.3;

            if ($exitSurveyResponses->count() > 0) {
                if ($coTag === 'CO1') {
                    $indirectAvg = ($exitSurveyResponses->avg('co1_q1') + $exitSurveyResponses->avg('co1_q2')) / 2;
                } elseif ($coTag === 'CO2') {
                    $indirectAvg = ($exitSurveyResponses->avg('co2_q3') + $exitSurveyResponses->avg('co2_q4')) / 2;
                } elseif ($coTag === 'CO3') {
                    $indirectAvg = ($exitSurveyResponses->avg('co3_q5') + $exitSurveyResponses->avg('co3_q6')) / 2;
                } else {
                    $indirectAvg = ($exitSurveyResponses->avg('co4_q7') + $exitSurveyResponses->avg('co4_q8') + $exitSurveyResponses->avg('co4_q9')) / 3;
                }
                $indirectPct = ($indirectAvg / 3.0) * 100;
                $indirectLevel = (float)\App\Services\AttainmentService::calculateBatchLevel($indirectPct, $lvl3Val, $lvl2Val, $lvl1Val);
            }
            $indirectRating = \App\Services\AttainmentService::getLevelLabel((int)round($indirectLevel));

            $indirectStats[$coTag] = [
                'avg_score' => round($indirectAvg, 2),
                'percentage' => round($indirectPct, 1),
                'level' => $indirectLevel,
                'rating' => $indirectRating
            ];

            $combinedLevel = \App\Services\AttainmentService::calculateOverallAttainment($directLevel, $indirectLevel, 0.80, 0.20);
            $combinedStats[$coTag] = $combinedLevel;
        }

        $poAttainments = [];
        for ($p = 1; $p <= 11; $p++) {
            $poName = "PO" . $p;
            $sumWeight = 0;
            $sumAttainment = 0;

            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $correlation = isset($mappings[$coTag][$poName]) && is_numeric($mappings[$coTag][$poName]) ? (int)$mappings[$coTag][$poName] : 0;
                if ($correlation > 0) {
                    $sumWeight += $correlation;
                    $sumAttainment += $combinedStats[$coTag] * $correlation;
                }
            }

            $poAttainments[$poName] = [
                'value' => $sumWeight > 0 ? round($sumAttainment / $sumWeight, 2) : 0.0,
                'weight' => $sumWeight
            ];
        }

        return view('r26_health_physical.virtual_classroom_health_physical', compact(
            'batchSubject',
            'classroom',
            'students',
            'hpCourseFile',
            'evalScheme',
            'lessonPlans',
            'studentResults',
            'activityEvals',
            'fitnessTests',
            'eseMarks',
            'assignedStaff',
            'mappings',
            'directStats',
            'indirectStats',
            'combinedStats',
            'poAttainments',
            'midSemSurvey',
            'exitSurvey',
            'midSemResponses',
            'exitSurveyResponses'
        ));
    }

    /**
     * Printable Reports for Health & Physical Education
     */
    public function printReport($subjectId, $type)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        if ($students->isEmpty()) {
            $students = StudentProfile::where('branch', $batchSubject->branch)
                ->where('batch_year', $batchSubject->batch_year)
                ->orderBy('reg_no')
                ->get();
        }
        $hpCourseFile = R26HealthPhysicalCourseFile::where('batch_subject_id', $subjectId)->first();
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();
        $activityEvals = R26HealthPhysicalEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $fitnessTests = R26HealthPhysicalFitnessTest::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $eseMarks = R26HealthPhysicalEseMark::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        // Also compute student results for consolidated reports
        $evalScheme = $hpCourseFile->parsed_eval_scheme ?: [
            'day_work' => [
                ['key' => 'c1', 'title' => 'Physical Fitness & Warm-Up', 'max_marks' => 10],
                ['key' => 'c2', 'title' => 'Skill Execution & Technique', 'max_marks' => 15],
                ['key' => 'c3', 'title' => 'Activity Logbook / Record', 'max_marks' => 10],
                ['key' => 'c4', 'title' => 'Viva-Voce & Game Rules', 'max_marks' => 10],
                ['key' => 'c5', 'title' => 'Sportsmanship & Attendance', 'max_marks' => 5]
            ]
        ];

        $studentResults = $students->map(function ($student) use ($activityEvals, $fitnessTests, $eseMarks) {
            $stEval = $activityEvals->get($student->reg_no, collect())->first();
            $stTests = $fitnessTests->get($student->reg_no, collect());
            $stEse = $eseMarks->get($student->reg_no);

            $attMarks = 5.0;
            $activityMarks = $stEval ? floatval($stEval->total_score_50) * 0.6 : 0.0;
            $ca1 = $stTests->where('test_no', 'CA1')->first();
            $ca2 = $stTests->where('test_no', 'CA2')->first();
            $ca1Score = $ca1 ? floatval($ca1->total_score_40) : 0.0;
            $ca2Score = $ca2 ? floatval($ca2->total_score_40) : 0.0;
            $testMarks = (($ca1Score + $ca2Score) / 80.0) * 15.0;

            $totalCieMarks = round($attMarks + $activityMarks + $testMarks, 2);
            $totalEse = $stEse ? floatval($stEse->total_ese_40) : 0.0;
            $totalCourseMarks = round($totalCieMarks + $totalEse, 2);

            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_marks' => $attMarks,
                'activity_marks' => round($activityMarks, 2),
                'test_marks' => round($testMarks, 2),
                'total_cie_marks' => $totalCieMarks,
                'total_ese' => $totalEse,
                'total_course_marks' => $totalCourseMarks,
                'is_passed' => ($totalCourseMarks >= 40.0 && $totalCieMarks >= 24.0)
            ];
        });

        // Surveys for Indirect Attainment Report
        $exitSurvey = DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $exitSurveyResponses = collect();
        if ($exitSurvey) {
            $exitSurveyResponses = DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }

        $midSemSurvey = DB::table('mid_semester_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $midSemResponses = collect();
        if ($midSemSurvey) {
            $midSemResponses = DB::table('student_survey_responses')
                ->where('survey_id', $midSemSurvey->id)
                ->get();
        }

        return view('r26_health_physical.reports_print', compact(
            'batchSubject', 'classroom', 'students', 'hpCourseFile', 'lessonPlans',
            'activityEvals', 'fitnessTests', 'eseMarks', 'studentResults', 'evalScheme', 'type',
            'exitSurvey', 'exitSurveyResponses', 'midSemSurvey', 'midSemResponses'
        ));
    }

    /**
     * Upload & Parse Health and Physical Education Syllabus PDF
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $path = $file->store('r26_health_physical_syllabi', 'public');

            $pyPath = base_path('app/Services/r26_health_physical_syllabus_parser.py');
            $fullPdfPath = storage_path('app/public/' . $path);
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "python " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            } else {
                $command = "PYTHONIOENCODING=utf-8 PYTHONPATH=/home/carmel/.local/lib/python3.14/site-packages:\$PYTHONPATH /usr/bin/python3 " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            }
            $jsonOutput = shell_exec($command);

            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || ($parsedResult['status'] ?? '') === 'ERROR') {
                $errDetail = $parsedResult['message'] ?? trim($jsonOutput ?: 'No output from Python parser.');
                throw new \Exception('Failed to parse Health & Physical syllabus PDF: ' . $errDetail);
            }

            $data = $parsedResult['data'];

            $hpCourseFile = R26HealthPhysicalCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'program' => $data['program'] ?? 'Engineering',
                    'course_title' => $data['course_title'] ?? 'Health and Physical Education',
                    'course_code' => $data['course_code'] ?? '1009',
                    'semester' => 'I',
                    'type_of_course' => 'Health & Physical',
                    'teaching_scheme' => $data['teaching_scheme'] ?? '0:0:2:0',
                    'contact_hours' => $data['total_hours'] ?? 30,
                    'credits' => $data['credits'] ?? 1.0,
                    'cie_marks' => $data['cie_marks'] ?? 60,
                    'ese_marks' => $data['ese_marks'] ?? 40,
                    'parsed_cos' => $data['cos'] ?? [],
                    'parsed_activities' => $data['activities'] ?? [],
                    'parsed_copo' => [
                        'credit' => $data['credits'] ?? 1.0,
                        'l_t_p_r' => $data['teaching_scheme'] ?? '0:0:2:0',
                        'cie_marks' => $data['cie_marks'] ?? 60,
                        'ese_marks' => $data['ese_marks'] ?? 40,
                        'total_hours' => $data['total_hours'] ?? 30,
                        'mappings' => $data['copo_matrix'] ?? []
                    ],
                    'parsed_eval_scheme' => $data['eval_scheme'] ?? []
                ]
            );

            // Auto-generate 30-Hour Lesson Plan
            $this->generate30HourLessonPlan($batchSubject, $hpCourseFile);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Health & Physical Education syllabus uploaded & parsed successfully! Dynamic evaluation titles updated.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save Continuous Fitness & Activity Log Marks (Dynamic criteria based on uploaded PDF)
     */
    public function saveActivityMarks(Request $request, $subjectId)
    {
        $request->validate([
            'activity_no' => 'required|string',
            'activity_title' => 'nullable|string',
            'marks_data' => 'required|array'
        ]);

        $actNo = $request->input('activity_no');
        $actTitle = $request->input('activity_title');
        $marksData = $request->input('marks_data');
        $assessorMobile = Session::get('mobileNo');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $c1 = floatval($row['c1'] ?? 0);
            $c2 = floatval($row['c2'] ?? 0);
            $c3 = floatval($row['c3'] ?? 0);
            $c4 = floatval($row['c4'] ?? 0);
            $c5 = floatval($row['c5'] ?? 0);
            $c6 = floatval($row['c6'] ?? 0);
            $total50 = $c1 + $c2 + $c3 + $c4 + $c5 + $c6;

            R26HealthPhysicalEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'activity_no' => $actNo,
                    'reg_no' => $regNo
                ],
                [
                    'activity_title' => $actTitle,
                    'criteria_json' => [
                        'c1' => $c1, 'c2' => $c2, 'c3' => $c3, 'c4' => $c4, 'c5' => $c5, 'c6' => $c6
                    ],
                    'c1' => $c1, 'c2' => $c2, 'c3' => $c3, 'c4' => $c4, 'c5' => $c5, 'c6' => $c6,
                    'total_score_50' => $total50,
                    'assessor_mobile_no' => $assessorMobile
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Activity evaluation marks saved successfully!']);
    }

    /**
     * Save Physical Fitness & Skill Test Marks (CA1 & CA2)
     */
    public function saveFitnessTestMarks(Request $request, $subjectId)
    {
        $request->validate([
            'test_no' => 'required|string',
            'marks_data' => 'required|array'
        ]);

        $testNo = $request->input('test_no');
        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $total40 = floatval($row['total_score_40'] ?? 0);
            $isAbsent = !empty($row['is_absent']);

            R26HealthPhysicalFitnessTest::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'test_no' => $testNo,
                    'reg_no' => $regNo
                ],
                [
                    'total_score_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Physical Fitness test marks saved successfully!']);
    }

    /**
     * Save ESE Marks for Health and Physical Education (Max 40)
     */
    public function saveEseMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $f = floatval($row['fitness_test_score'] ?? 0);
            $s = floatval($row['skill_demo_score'] ?? 0);
            $v = floatval($row['viva_score'] ?? 0);
            $r = floatval($row['record_score'] ?? 0);
            $total40 = $f + $s + $v + $r;
            $isAbsent = !empty($row['is_absent']);

            R26HealthPhysicalEseMark::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'fitness_test_score' => $f,
                    'skill_demo_score' => $s,
                    'viva_score' => $v,
                    'record_score' => $r,
                    'total_ese_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'ESE marks saved successfully!']);
    }

    /**
     * Auto Generate 30-Hour Health & Physical Education Lesson Plan
     */
    private function generate30HourLessonPlan($batchSubject, $hpFile)
    {
        LessonPlan::where('batch_subject_id', $batchSubject->id)->delete();

        // 30-Hour Schedule structured with all syllabus topics + 2 Series Tests
        $schedule = [
            // CO1 & CO2 (Hours 1 - 14)
            ['title' => 'Orientation, Body Mass Index (BMI) & Posture Assessment', 'co_id' => 'CO1', 'hours' => 3],
            ['title' => 'Warming-Up Protocols & General Physical Fitness Drills', 'co_id' => 'CO2', 'hours' => 3],
            ['title' => 'Calisthenics, Aerobics & Cardiovascular Endurance Activities', 'co_id' => 'CO2', 'hours' => 4],
            ['title' => 'Athletic Events: Sprint, Distance Running & Relay Technique', 'co_id' => 'CO2', 'hours' => 4],
            // Series Test I (Hour 15)
            ['title' => 'Series Test I (Continuous Fitness & Theory Evaluation)', 'co_id' => 'CO2', 'hours' => 1, 'is_test' => true],
            // CO3 & CO4 (Hours 16 - 29)
            ['title' => 'Major Games Skill Practice (Volleyball / Football / Basketball / Badminton)', 'co_id' => 'CO3', 'hours' => 6],
            ['title' => 'Yogic Asanas, Pranayama & Relaxation Techniques for Stress Relief', 'co_id' => 'CO4', 'hours' => 3],
            ['title' => 'First Aid, CPR Fundamentals & Sports Injury Management', 'co_id' => 'CO4', 'hours' => 3],
            ['title' => 'Fitness Test Evaluation & Logbook Submission', 'co_id' => 'CO4', 'hours' => 2],
            // Series Test II (Hour 30)
            ['title' => 'Series Test II (Practical & Comprehensive Skill Evaluation)', 'co_id' => 'CO4', 'hours' => 1, 'is_test' => true],
        ];

        $currentHour = 1;
        $startDate = now();

        foreach ($schedule as $act) {
            $title = $act['title'];
            $coTag = $act['co_id'];
            $hrs = $act['hours'];
            $isTest = !empty($act['is_test']);

            for ($h = 1; $h <= $hrs; $h++) {
                $topicStr = $isTest ? $title : "{$title} (Session {$h}/{$hrs})";
                $proposedDate = $startDate->copy()->addDays($currentHour)->toDateString();

                LessonPlan::create([
                    'batch_subject_id' => $batchSubject->id,
                    'day_no' => $currentHour,
                    'planned_date' => $proposedDate,
                    'proposed_date' => $proposedDate,
                    'actual_date' => null,
                    'topic_content' => $topicStr,
                    'slo' => $isTest ? 'Evaluate physical fitness and practical sports skills' : 'Execute practical physical fitness drills and posture techniques',
                    'co_tag' => $coTag,
                    'co_id' => $coTag,
                    'allocated_hours' => 1,
                    'taxonomy' => 'Apply',
                    'mode' => 'P',
                    'pedagogy' => $isTest ? 'Series Evaluation Test' : 'Physical Practical Session',
                    'sub_batch' => 'Whole',
                    'status' => 'Pending'
                ]);
                $currentHour++;
            }
        }
    }

    /**
     * Bulk update Lesson Plan
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $plans = $request->input('plans', []);
        foreach ($plans as $id => $data) {
            $actualDate = $data['actual_date'] ?? null;
            $status = $data['status'] ?? 'Pending';
            if ($actualDate && $status === 'Pending') {
                $status = 'Completed';
            }

            LessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->update([
                'topic_content' => $data['topic_content'] ?? '',
                'co_tag' => $data['co_tag'] ?? ($data['co_id'] ?? 'CO1'),
                'co_id' => $data['co_tag'] ?? ($data['co_id'] ?? 'CO1'),
                'allocated_hours' => intval($data['allocated_hours'] ?? 1),
                'pedagogy' => $data['pedagogy'] ?? 'Physical Practical Session',
                'proposed_date' => $data['proposed_date'] ?? null,
                'actual_date' => $actualDate,
                'status' => $status,
            ]);
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Lesson plan updated successfully!']);
    }
}
