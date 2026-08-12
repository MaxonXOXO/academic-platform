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
use App\Models\R26DrawingCourseFile;
use App\Models\R26DrawingSlotEvaluation;
use App\Models\R26DrawingPracticalTest;
use App\Models\R26DrawingOeeEvaluation;
use App\Models\R26DrawingEseMark;
use App\Models\StaffProfile;

class R26VirtualClassroomDrawingController extends Controller
{
    /**
     * Main Virtual Drawing Hall Dashboard (Lab Model - 60 CIE + 40 ESE)
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

        // Fetch classroom details
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or Create Drawing Course File Record
        $drawingCourseFile = R26DrawingCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? 'Engineering',
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
                'semester' => $classroom->current_semester ?? 'I',
                'type_of_course' => 'Lab',
                'teaching_scheme' => '0:0:3:0',
                'contact_hours' => 45,
                'credits' => 1.5,
                'cie_marks' => 60,
                'ese_marks' => 40,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Construct geometrical figures and illustrate development of surfaces.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Interpret projections of points and lines, orthographic projections and sectional views.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Familiarization in using CAD software and 2D drafting tools.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Develop orthographic projections and sectional views in CAD software.', 'cognitive_level' => 'Apply']
                ],
                'parsed_modules' => [
                    ['module_id' => 'I', 'title' => 'Engineering Graphics Fundamentals & Conic Sections', 'hours' => 9.0, 'content' => 'Regular Polygons, Ellipse, Parabola, Development of Surfaces'],
                    ['module_id' => 'II', 'title' => 'Projections & Sectional Views', 'hours' => 12.0, 'content' => 'Projections of Points, Lines, Orthographic Projections, Sectional Views'],
                    ['module_id' => 'III', 'title' => 'Computer Aided Drafting (CAD) Basics', 'hours' => 12.0, 'content' => 'CAD editor, Draw/Modify commands, Line properties, Text, Dimensions'],
                    ['module_id' => 'IV', 'title' => 'CAD 2D Drafting & Plotting', 'hours' => 12.0, 'content' => 'Orthographic components in CAD, Sectional views in CAD, Printing/Plotting']
                ],
                'parsed_exercises' => [
                    ['exercise_no' => 'EXE-01', 'module' => 'Module I', 'title' => 'Drawing Regular Polygons (Pentagon & Hexagon)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-02', 'module' => 'Module I', 'title' => 'Drawing Conic Sections (Ellipse by Rectangular & Concentric Circle Method)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-03', 'module' => 'Module I', 'title' => 'Drawing Development of Surfaces (Prism & Cylinder)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-04', 'module' => 'Module II', 'title' => 'Drawing Basic Projections of Points & Lines in Quadrants', 'co_id' => 'CO2', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-05', 'module' => 'Module II', 'title' => 'Drawing Orthographic Projections & Sectional Views of Engineering Objects', 'co_id' => 'CO2', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-06', 'module' => 'Module III', 'title' => 'CAD Software Basics & Familiarization of Draw and Modify Commands', 'co_id' => 'CO3', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-07', 'module' => 'Module III', 'title' => 'CAD Line Properties, Layers, Text, and Dimensioning Practice', 'co_id' => 'CO3', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-08', 'module' => 'Module IV', 'title' => 'Developing Orthographic Views of Components in CAD', 'co_id' => 'CO4', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-09', 'module' => 'Module IV', 'title' => 'Developing Sectional Views & Plotting CAD Drawings', 'co_id' => 'CO4', 'hours' => 6.0]
                ],
                'parsed_copo' => [
                    'credit' => 1.5,
                    'l_t_p_r' => '0:0:3:0',
                    'cie_marks' => 60,
                    'ese_marks' => 40,
                    'total_hours' => 45,
                    'mappings' => [
                        'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO2' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'2', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO3' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'2', 'PO4'=>'3', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO4' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'3', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-']
                    ]
                ],
                'parsed_textbooks' => [
                    'Engineering Graphics with AUTOCAD - P I Varghese (VIP Publishers)',
                    'Engineering Graphics - K. C John (PHI Learning)',
                    'Engineering Drawing with CAD Applications - N. D. Bhatt & V. M. Panchal'
                ]
            ]
        );

        // Fetch / Auto-Generate 45-Hour Drawing Lab Lesson Plan
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() < 15) {
            $this->generate45HourLabLessonPlan($batchSubject, $drawingCourseFile);
            $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        }

        // Fetch Attendance Records
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Fetch Continuous Practical Evaluations (CE - Max 50 per slot)
        $slotEvals = R26DrawingSlotEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Practical Tests Marks (CA1 & CA2 - Max 40)
        $practicalTests = R26DrawingPracticalTest::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Open-Ended Experiment Evaluations (OEE - Max 50)
        $oeeEvals = R26DrawingOeeEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch ESE Marks (Max 40)
        $eseMarks = R26DrawingEseMark::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Map Students & Compute CIE (60) + ESE (40) = Total (100)
        $studentResults = $students->map(function ($student) use (
            $attendanceData,
            $slotEvals,
            $practicalTests,
            $oeeEvals,
            $eseMarks,
            $batchSubject
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

            // 2. Continuous Evaluation (CE - Max 30 CIE Marks)
            $stSlots = $slotEvals->get($regNo, collect());
            $avgSlotScore50 = $stSlots->avg('total_score_50') ?: 0.00;
            $ceMarks = round((($avgSlotScore50 / 50.0) * 30.0) * 2) / 2;

            // 3. Practical Tests CA1 & CA2 (Max 15 CIE Marks: CA1 [7.5] + CA2 [7.5])
            $stTests = $practicalTests->get($regNo, collect());
            $ca1 = $stTests->where('test_no', 'CA1')->first();
            $ca2 = $stTests->where('test_no', 'CA2')->first();
            $ca1Score = ($ca1 && !$ca1->is_absent) ? $ca1->total_score_40 : 0.00;
            $ca2Score = ($ca2 && !$ca2->is_absent) ? $ca2->total_score_40 : 0.00;
            
            $ca1Marks = round((($ca1Score / 40.0) * 7.5) * 2) / 2;
            $ca2Marks = round((($ca2Score / 40.0) * 7.5) * 2) / 2;
            $practicalTestMarks = $ca1Marks + $ca2Marks;

            // 4. Open-Ended Experiment (OEE - Max 10 CIE Marks)
            $stOee = $oeeEvals->get($regNo);
            $oeeScore50 = $stOee ? floatval($stOee->total_score_50) : 0.00;
            $oeeMarks = round((($oeeScore50 / 50.0) * 10.0) * 2) / 2;

            // Total CIE Marks (Max 60)
            $totalCieMarks = round(($attMarks + $ceMarks + $practicalTestMarks + $oeeMarks) * 2) / 2;
            if ($totalCieMarks > 60.0) $totalCieMarks = 60.0;

            // ESE Marks (Max 40)
            $stEse = $eseMarks->get($regNo);
            $partA = $stEse ? floatval($stEse->part_a_mcq) : 0.00;
            $partB = $stEse ? floatval($stEse->part_b_cad) : 0.00;
            $partC = $stEse ? floatval($stEse->part_c_viva) : 0.00;
            $partD = $stEse ? floatval($stEse->part_d_record) : 0.00;
            $totalEse = ($stEse && !$stEse->is_absent) ? ($partA + $partB + $partC + $partD) : 0.00;

            $totalCourseMarks = $totalCieMarks + $totalEse;

            // Pass Criteria:
            // 1. Min 40% in ESE = 16 / 40
            // 2. Min 40% in Total Combined = 40 / 100
            $isEsePass = ($totalEse >= 16.0);
            $isTotalPass = ($totalCourseMarks >= 40.0);
            $isPassed = ($isEsePass && $isTotalPass);

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_percentage' => $attPercentage,
                'att_marks' => $attMarks,
                'ce_marks' => $ceMarks,
                'ca1_score' => $ca1Score,
                'ca2_score' => $ca2Score,
                'practical_test_marks' => $practicalTestMarks,
                'oee_score' => $oeeScore50,
                'oee_marks' => $oeeMarks,
                'total_cie_marks' => $totalCieMarks,
                'ese_part_a' => $partA,
                'ese_part_b' => $partB,
                'ese_part_c' => $partC,
                'ese_part_d' => $partD,
                'total_ese' => $totalEse,
                'total_course_marks' => $totalCourseMarks,
                'is_passed' => $isPassed
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
        $copoPayload = $drawingCourseFile->parsed_copo ?: [];
        $mappings = $copoPayload['mappings'] ?? [];

        if (empty($mappings)) {
            $mappings = [
                'CO1' => ['PO1'=>'3','PO2'=>'2','PO3'=>'3','PO4'=>'-','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO2' => ['PO1'=>'3','PO2'=>'3','PO3'=>'2','PO4'=>'-','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO3' => ['PO1'=>'2','PO2'=>'-','PO3'=>'2','PO4'=>'3','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO4' => ['PO1'=>'3','PO2'=>'2','PO3'=>'3','PO4'=>'3','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-']
            ];
        }

        $totalStudents = max(1, $studentResults->count());
        $directStats = [];
        $indirectStats = [];
        $combinedStats = [];

        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $attainedCount = $studentResults->filter(function($s) {
                return $s['total_course_marks'] >= 50.0; // 50% target
            })->count();

            $percentage = ($attainedCount / $totalStudents) * 100;
            $directLevel = ($percentage >= 70) ? 3.0 : (($percentage >= 60) ? 2.0 : (($percentage >= 50) ? 1.0 : 0.0));
            
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
                $indirectLevel = ($indirectPct >= 70) ? 3.0 : (($indirectPct >= 60) ? 2.0 : (($indirectPct >= 50) ? 1.0 : 0.0));
            }
            $indirectRating = ($indirectPct >= 70) ? 'High (L3)' : (($indirectPct >= 60) ? 'Medium (L2)' : (($indirectPct >= 50) ? 'Low (L1)' : 'Nil (L0)'));

            $indirectStats[$coTag] = [
                'avg_score' => round($indirectAvg, 2),
                'percentage' => round($indirectPct, 1),
                'level' => $indirectLevel,
                'rating' => $indirectRating
            ];

            $combinedLevel = round((0.80 * $directLevel) + (0.20 * $indirectLevel), 2);
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

        return view('r26_drawing.virtual_classroom_drawing', compact(
            'batchSubject',
            'classroom',
            'students',
            'drawingCourseFile',
            'lessonPlans',
            'studentResults',
            'slotEvals',
            'practicalTests',
            'oeeEvals',
            'eseMarks',
            'assignedStaff',
            'hod',
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
     * Upload & Parse Drawing Syllabus PDF
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
            $path = $file->store('r26_drawing_syllabi', 'public');

            // Execute Python parser
            $pyPath = base_path('app/Services/r26_drawing_syllabus_parser.py');
            $fullPdfPath = storage_path('app/public/' . $path);
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = "python " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            } else {
                $command = "PYTHONIOENCODING=utf-8 PYTHONPATH=/home/carmel/.local/lib/python3.14/site-packages:\$PYTHONPATH /usr/bin/python3 " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            }
            $jsonOutput = shell_exec($command);

            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || ($parsedResult['status'] ?? '') === 'ERROR') {
                $errDetail = $parsedResult['message'] ?? trim($jsonOutput ?: 'No output returned from Python parser.');
                throw new \Exception('Failed to parse Drawing syllabus PDF: ' . $errDetail);
            }

            $data = $parsedResult['data'];

            $drawingCourseFile = R26DrawingCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'program' => $data['program'] ?? 'Engineering',
                    'course_title' => $data['course_title'] ?? $batchSubject->subject_name,
                    'course_code' => $data['course_code'] ?? $batchSubject->subject_code,
                    'semester' => $data['semester'] ?? 'I',
                    'type_of_course' => 'Lab',
                    'teaching_scheme' => $data['teaching_scheme'] ?? '0:0:3:0',
                    'contact_hours' => $data['total_hours'] ?? 45,
                    'credits' => $data['credits'] ?? 1.5,
                    'cie_marks' => $data['cie_marks'] ?? 60,
                    'ese_marks' => $data['ese_marks'] ?? 40,
                    'parsed_cos' => $data['cos'] ?? [],
                    'parsed_modules' => $data['modules'] ?? [],
                    'parsed_exercises' => $data['exercises'] ?? [],
                    'parsed_copo' => [
                        'credit' => $data['credits'] ?? 1.5,
                        'l_t_p_r' => $data['teaching_scheme'] ?? '0:0:3:0',
                        'cie_marks' => $data['cie_marks'] ?? 60,
                        'ese_marks' => $data['ese_marks'] ?? 40,
                        'total_hours' => $data['total_hours'] ?? 45,
                        'mappings' => $data['copo_matrix'] ?? []
                    ],
                    'parsed_textbooks' => [
                        'Engineering Graphics with AUTOCAD - P I Varghese',
                        'Engineering Graphics - K. C John',
                        'Engineering Drawing with CAD Applications - N. D. Bhatt'
                    ]
                ]
            );

            // Automatically regenerate Lesson Plan based on parsed syllabus exercises
            $this->generate45HourLabLessonPlan($batchSubject, $drawingCourseFile);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Drawing syllabus uploaded and parsed successfully!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save Continuous Practical Evaluation (CE - Max 50 per slot)
     */
    public function saveSlotMarks(Request $request, $subjectId)
    {
        $request->validate([
            'exercise_no' => 'required|string',
            'exercise_title' => 'nullable|string',
            'marks_data' => 'required|array'
        ]);

        $exNo = $request->input('exercise_no');
        $exTitle = $request->input('exercise_title');
        $marksData = $request->input('marks_data');
        $assessorMobile = Session::get('mobileNo');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $p1 = floatval($row['prep_punctuality'] ?? 0);
            $p2 = floatval($row['setup_procedure'] ?? 0);
            $p3 = floatval($row['observation_recording'] ?? 0);
            $p4 = floatval($row['analysis_interpretation'] ?? 0);
            $p5 = floatval($row['viva_voce'] ?? 0);
            $p6 = floatval($row['workmanship_discipline'] ?? 0);
            $total50 = $p1 + $p2 + $p3 + $p4 + $p5 + $p6;

            R26DrawingSlotEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'exercise_no' => $exNo,
                    'reg_no' => $regNo
                ],
                [
                    'exercise_title' => $exTitle,
                    'prep_punctuality' => $p1,
                    'setup_procedure' => $p2,
                    'observation_recording' => $p3,
                    'analysis_interpretation' => $p4,
                    'viva_voce' => $p5,
                    'workmanship_discipline' => $p6,
                    'total_score_50' => $total50,
                    'assessor_mobile_no' => $assessorMobile
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Slot evaluation marks saved successfully!']);
    }

    /**
     * Save Practical Tests CA1 & CA2 (Max 40)
     */
    public function savePracticalTestMarks(Request $request, $subjectId)
    {
        $request->validate([
            'test_no' => 'required|string',
            'marks_data' => 'required|array'
        ]);

        $testNo = $request->input('test_no');
        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $w = floatval($row['writeup_procedure'] ?? 0);
            $s = floatval($row['setup_execution'] ?? 0);
            $o = floatval($row['observation_result'] ?? 0);
            $v = floatval($row['viva_voce'] ?? 0);
            $r = floatval($row['record_completion'] ?? 0);
            $total40 = $w + $s + $o + $v + $r;
            $isAbsent = !empty($row['is_absent']);

            R26DrawingPracticalTest::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'test_no' => $testNo,
                    'reg_no' => $regNo
                ],
                [
                    'writeup_procedure' => $w,
                    'setup_execution' => $s,
                    'observation_result' => $o,
                    'viva_voce' => $v,
                    'record_completion' => $r,
                    'total_score_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Practical Test marks saved successfully!']);
    }

    /**
     * Save Open-Ended Experiment Marks (OEE - Max 50)
     */
    public function saveOeeMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $m1 = floatval($row['originality_relevance'] ?? 0);
            $m2 = floatval($row['objectives_plan'] ?? 0);
            $m3 = floatval($row['execution_recording'] ?? 0);
            $m4 = floatval($row['analysis_presentation'] ?? 0);
            $m5 = floatval($row['teamwork_innovation'] ?? 0);
            $total50 = $m1 + $m2 + $m3 + $m4 + $m5;

            R26DrawingOeeEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'originality_relevance' => $m1,
                    'objectives_plan' => $m2,
                    'execution_recording' => $m3,
                    'analysis_presentation' => $m4,
                    'teamwork_innovation' => $m5,
                    'total_score_50' => $total50
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Open-ended experiment marks saved successfully!']);
    }

    /**
     * Save End Semester Exam Marks (ESE - Max 40)
     */
    public function saveEseMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $pa = floatval($row['part_a_mcq'] ?? 0);
            $pb = floatval($row['part_b_cad'] ?? 0);
            $pc = floatval($row['part_c_viva'] ?? 0);
            $pd = floatval($row['part_d_record'] ?? 0);
            $total40 = $pa + $pb + $pc + $pd;
            $isAbsent = !empty($row['is_absent']);

            R26DrawingEseMark::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'part_a_mcq' => $pa,
                    'part_b_cad' => $pb,
                    'part_c_viva' => $pc,
                    'part_d_record' => $pd,
                    'total_ese_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'ESE CAD practical marks saved successfully!']);
    }

    /**
     * API Endpoint to Generate/Regenerate 45-Hour Drawing Lab Lesson Plan
     */
    public function generateLessonPlanApi(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $drawingFile = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $mode = $request->input('mode', 'single');

        $this->generate45HourLabLessonPlan($batchSubject, $drawingFile, $mode);

        $totalRows = LessonPlan::where('batch_subject_id', $subjectId)->count();
        return response()->json(['status' => 'SUCCESS', 'message' => "Drawing Lab Lesson Plan generated! {$totalRows} entries created (mode: {$mode})."]);
    }

    /**
     * API Endpoint to Bulk Update Drawing Lab Lesson Plan Entries
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $plans = $request->input('plans', []);
        $dayNoCounter = 1;
        foreach ($plans as $id => $data) {
            $topic = trim($data['topic_content'] ?? '');
            if (empty($topic)) {
                if (is_numeric($id) && intval($id) > 0) {
                    LessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->delete();
                }
                continue;
            }

            $actualDate = $data['actual_date'] ?? null;
            $status = $data['status'] ?? 'Pending';
            if ($actualDate && $status === 'Pending') {
                $status = 'Completed';
            }

            $payload = [
                'batch_subject_id' => $subjectId,
                'day_no' => intval($data['day_no'] ?? $dayNoCounter),
                'topic_content' => $topic,
                'co_tag' => $data['co_tag'] ?? ($data['co_id'] ?? 'CO1'),
                'co_id' => $data['co_tag'] ?? ($data['co_id'] ?? 'CO1'),
                'allocated_hours' => intval($data['allocated_hours'] ?? 1),
                'pedagogy' => $data['pedagogy'] ?? 'Drawing Lab Practical (P)',
                'proposed_date' => $data['proposed_date'] ?? null,
                'planned_date' => $data['proposed_date'] ?? null,
                'actual_date' => $actualDate,
                'status' => $status,
            ];

            if (is_numeric($id) && intval($id) > 0) {
                LessonPlan::where('id', $id)->where('batch_subject_id', $subjectId)->update($payload);
            } else {
                LessonPlan::create($payload);
            }
            $dayNoCounter++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Drawing Lab Lesson Plan updated successfully!']);
    }

    /**
     * Print View for Drawing Lab Lesson Plan
     */
    public function printLessonPlan($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $drawingCourseFile = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no', 'asc')->get();

        $staffMobile = Session::get('mobileNo') ?: Session::get('userId');
        $staff = StaffProfile::where('mobile_no', $staffMobile)->first();

        return view('r26_drawing.lesson_plan_print', compact('batchSubject', 'classroom', 'drawingCourseFile', 'lessonPlans', 'staff'));
    }

    /**
     * Auto Generate 45-Hour Drawing Lab Lesson Plan (including 2 Practical Series Tests & OEE)
     */
    private function generate45HourLabLessonPlan($batchSubject, $drawingFile, $mode = 'single')
    {
        LessonPlan::where('batch_subject_id', $batchSubject->id)->delete();

        $parsedExercises = $drawingFile->parsed_exercises ?? [];
        
        if (empty($parsedExercises)) {
            $parsedExercises = [
                ['title' => 'Drawing Regular Polygons (Pentagon & Hexagon)', 'co_id' => 'CO1', 'hours' => 3],
                ['title' => 'Drawing Conic Sections (Ellipse & Parabola)', 'co_id' => 'CO1', 'hours' => 3],
                ['title' => 'Development of Surfaces (Prism & Cylinder)', 'co_id' => 'CO1', 'hours' => 3],
                ['title' => 'Projections of Points & Lines in Quadrants', 'co_id' => 'CO2', 'hours' => 4],
                ['title' => 'Orthographic Projections & Sectional Views of Objects', 'co_id' => 'CO2', 'hours' => 5],
                ['title' => 'CAD Software Interface & Draw/Modify Commands', 'co_id' => 'CO3', 'hours' => 6],
                ['title' => 'CAD Line Properties, Layers, Text, and Dimensioning', 'co_id' => 'CO3', 'hours' => 6],
                ['title' => 'Developing 2D Orthographic Components in CAD', 'co_id' => 'CO4', 'hours' => 5],
                ['title' => 'Developing Sectional Views & CAD Plotting', 'co_id' => 'CO4', 'hours' => 4],
            ];
        }

        $currentHour = 1;
        // Drawing lab is conducted as a single batch (whole class)
        $batchesToCreate = ['Whole'];

        foreach ($parsedExercises as $ex) {
            $title = $ex['title'] ?? 'Practical Drawing Session';
            $coTag = $ex['co_id'] ?? 'CO1';
            $hrs = intval($ex['hours'] ?? 3);

            for ($h = 1; $h <= $hrs; $h++) {
                // Insert Practical Series Test 1 (CA1) at Hour 19-21 (Week 7)
                if ($currentHour == 19) {
                    for ($ca1H = 1; $ca1H <= 3; $ca1H++) {
                        foreach ($batchesToCreate as $bName) {
                            LessonPlan::create([
                                'batch_subject_id' => $batchSubject->id,
                                'day_no' => $currentHour,
                                'planned_date' => now()->addDays($currentHour)->toDateString(),
                                'proposed_date' => now()->addDays($currentHour)->toDateString(),
                                'topic_content' => "PRACTICAL SERIES TEST 1 (CA1): Manual Descriptive Drawing Exam (Modules I & II - 40 Marks) [Hour {$ca1H}/3]",
                                'slo' => 'Execute manual orthographic and sectional drawing under examination conditions',
                                'co_tag' => 'CO2',
                                'co_id' => 'CO2',
                                'allocated_hours' => 1,
                                'taxonomy' => 'Apply',
                                'mode' => 'P',
                                'pedagogy' => 'Series Test Examination (CA1)',
                                'sub_batch' => $bName,
                                'status' => 'Pending'
                            ]);
                        }
                        $currentHour++;
                    }
                }

                // Insert Open-Ended Experiment (OEE) at Hour 40-42 (Week 14)
                if ($currentHour == 40) {
                    for ($oeeH = 1; $oeeH <= 3; $oeeH++) {
                        foreach ($batchesToCreate as $bName) {
                            LessonPlan::create([
                                'batch_subject_id' => $batchSubject->id,
                                'day_no' => $currentHour,
                                'planned_date' => now()->addDays($currentHour)->toDateString(),
                                'proposed_date' => now()->addDays($currentHour)->toDateString(),
                                'topic_content' => "OPEN-ENDED EXPERIMENT (OEE): CAD Mini-Project Design & Evaluation [Hour {$oeeH}/3]",
                                'slo' => 'Design and evaluate independent engineering drawing component using CAD software',
                                'co_tag' => 'CO3',
                                'co_id' => 'CO3',
                                'allocated_hours' => 1,
                                'taxonomy' => 'Create',
                                'mode' => 'P',
                                'pedagogy' => 'Open-Ended Project (OEE)',
                                'sub_batch' => $bName,
                                'status' => 'Pending'
                            ]);
                        }
                        $currentHour++;
                    }
                }

                // Insert Practical Series Test 2 (CA2) at Hour 43-45 (Week 15)
                if ($currentHour == 43) {
                    for ($ca2H = 1; $ca2H <= 3; $ca2H++) {
                        foreach ($batchesToCreate as $bName) {
                            LessonPlan::create([
                                'batch_subject_id' => $batchSubject->id,
                                'day_no' => $currentHour,
                                'planned_date' => now()->addDays($currentHour)->toDateString(),
                                'proposed_date' => now()->addDays($currentHour)->toDateString(),
                                'topic_content' => "PRACTICAL SERIES TEST 2 (CA2): End-Sem CAD Practical Exam (Modules III & IV - 40 Marks) [Hour {$ca2H}/3]",
                                'slo' => 'Execute 2D CAD drafting and sectional views under timed examination conditions',
                                'co_tag' => 'CO4',
                                'co_id' => 'CO4',
                                'allocated_hours' => 1,
                                'taxonomy' => 'Apply',
                                'mode' => 'P',
                                'pedagogy' => 'Series Test Examination (CA2)',
                                'sub_batch' => $bName,
                                'status' => 'Pending'
                            ]);
                        }
                        $currentHour++;
                    }
                }

                if ($currentHour > 45) break 2;

                foreach ($batchesToCreate as $bName) {
                    LessonPlan::create([
                        'batch_subject_id' => $batchSubject->id,
                        'day_no' => $currentHour,
                        'planned_date' => now()->addDays($currentHour)->toDateString(),
                        'proposed_date' => now()->addDays($currentHour)->toDateString(),
                        'topic_content' => $title . " (Hour {$h}/{$hrs})",
                        'slo' => "Demonstrate drafting accuracy for " . $title,
                        'co_tag' => $coTag,
                        'co_id' => $coTag,
                        'allocated_hours' => 1,
                        'taxonomy' => (str_contains(strtolower($title), 'cad') ? 'Apply' : 'Understand'),
                        'mode' => 'P',
                        'pedagogy' => 'Drawing Lab Practical (P)',
                        'sub_batch' => $bName,
                        'status' => 'Pending'
                    ]);
                }

                $currentHour++;
            }
        }

        // Fill any remaining hours up to exactly 45 hours
        while ($currentHour <= 45) {
            if ($currentHour == 19 || $currentHour == 20 || $currentHour == 21) {
                $ca1H = $currentHour - 18;
                $topic = "PRACTICAL SERIES TEST 1 (CA1): Manual Descriptive Drawing Exam (Modules I & II - 40 Marks) [Hour {$ca1H}/3]";
                $coTag = 'CO2';
                $pedagogy = 'Series Test Examination (CA1)';
            } elseif ($currentHour == 40 || $currentHour == 41 || $currentHour == 42) {
                $oeeH = $currentHour - 39;
                $topic = "OPEN-ENDED EXPERIMENT (OEE): CAD Mini-Project Design & Evaluation [Hour {$oeeH}/3]";
                $coTag = 'CO3';
                $pedagogy = 'Open-Ended Project (OEE)';
            } elseif ($currentHour == 43 || $currentHour == 44 || $currentHour == 45) {
                $ca2H = $currentHour - 42;
                $topic = "PRACTICAL SERIES TEST 2 (CA2): End-Sem CAD Practical Exam (Modules III & IV - 40 Marks) [Hour {$ca2H}/3]";
                $coTag = 'CO4';
                $pedagogy = 'Series Test Examination (CA2)';
            } else {
                $topic = "CAD Drawing Revision & Portfolio Finalization (Hour " . ($currentHour) . ")";
                $coTag = 'CO4';
                $pedagogy = 'Drawing Lab Revision (P)';
            }

            foreach ($batchesToCreate as $bName) {
                LessonPlan::create([
                    'batch_subject_id' => $batchSubject->id,
                    'day_no' => $currentHour,
                    'planned_date' => now()->addDays($currentHour)->toDateString(),
                    'proposed_date' => now()->addDays($currentHour)->toDateString(),
                    'topic_content' => $topic,
                    'slo' => 'Drafting precision and review',
                    'co_tag' => $coTag,
                    'co_id' => $coTag,
                    'allocated_hours' => 1,
                    'taxonomy' => 'Apply',
                    'mode' => 'P',
                    'pedagogy' => $pedagogy,
                    'sub_batch' => $bName,
                    'status' => 'Pending'
                ]);
            }

            $currentHour++;
        }
    }

    /**
     * Default Model Questions with Choice Options (Modules I & II for Series 1, Modules III & IV for Series 2)
     */
    public function getDefaultSeriesQuestions($testNo = 1)
    {
        if ($testNo == 1) {
            return [
                'test_title' => 'SERIES EXAMINATION 1 (MANUAL DRAWING)',
                'modules_covered' => 'Module I (Geometrical Constructions & Conics) & Module II (Orthographic Projections)',
                'duration' => '1.5 Hours / 2 Hours',
                'max_marks' => 40,
                'co_tags' => 'CO1 & CO2',
                'instructions' => 'Answer Question 1 (Module I) AND Question 2 (Module II). Choose EITHER Option A OR Option B in each question. Show all construction lines clearly.',
                'questions' => [
                    [
                        'q_no' => 'Q1',
                        'module' => 'Module I',
                        'co' => 'CO1',
                        'total_marks' => 20,
                        'option_a' => [
                            'title' => 'Option A (Universal Circle Method & Concentric Ellipse)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Construct a regular Hexagon of side 40 mm using the universal circle method. Show all construction lines clearly.',
                                    'marks' => 10,
                                    'scheme' => 'Universal circle construction: 4 Marks | Hexagon geometry: 4 Marks | Dimensioning & neatness: 2 Marks',
                                    'answer_key' => '1. Draw AB = 40 mm. 2. Erect perpendicular bisector. 3. Arc radius AB to locate 6-point. 4. Draw circle of radius 6-point to A, step off 40 mm sides.'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Draw an Ellipse having major axis = 100 mm and minor axis = 60 mm using the Concentric Circles Method.',
                                    'marks' => 10,
                                    'scheme' => 'Concentric circles: 3 Marks | 12-sector division: 4 Marks | Ellipse plot: 2 Marks | Dimensioning: 1 Mark',
                                    'answer_key' => '1. Draw major circle Dia 100 mm & minor circle Dia 60 mm. 2. Divide into 12 equal 30° sectors. 3. Project vertical from major & horizontal from minor circle.'
                                ]
                            ]
                        ],
                        'option_b' => [
                            'title' => 'Option B (Parabola & Surface Development)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Draw a Parabola with focus 50 mm away from directrix using eccentricity method (e = 1).',
                                    'marks' => 10,
                                    'scheme' => 'Directrix & Axis setup: 3 Marks | Vertex & Focus points: 3 Marks | Parabolic curve plot: 4 Marks',
                                    'answer_key' => '1. Draw directrix and axis. Mark Focus F at 50 mm. 2. Mark Vertex V at mid-point 25 mm. 3. Plot points where VF = VF’ to get parabola.'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Draw the stretch-out development of a truncated square prism of base 30 mm and height 60 mm cut at 45° to base.',
                                    'marks' => 10,
                                    'scheme' => 'Prism elevation & cutting plane: 4 Marks | Development stretch-out: 4 Marks | True lengths & neatness: 2 Marks',
                                    'answer_key' => '1. Draw Front View showing 45° cutting plane. 2. Project horizontal lines onto 4×30 = 120 mm stretch-out boundary.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'q_no' => 'Q2',
                        'module' => 'Module II',
                        'co' => 'CO2',
                        'total_marks' => 20,
                        'option_a' => [
                            'title' => 'Option A (Line Inclination & Step Block Orthographic)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'A line AB 70 mm long has end A 15 mm above HP and 20 mm in front of VP. Line is parallel to VP and inclined at 30° to HP. Draw Front View & Top View.',
                                    'marks' => 10,
                                    'scheme' => 'XY line & Point A setup: 2 Marks | Front View a’b’ at 30°: 4 Marks | Top View ab horizontal: 3 Marks | Dimensions: 1 Mark',
                                    'answer_key' => '1. XY line: a’ 15 mm above, a 20 mm below. 2. a’b’ = 70 mm at 30° to XY. 3. Project b’ down to get Top View ab (length = 70 cos 30° = 60.6 mm).'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Draw 1st Angle Orthographic Projections (Front View & Top View) of a rectangular step block (Base 60x40 mm, Height 30 mm, step 20x15 mm).',
                                    'marks' => 10,
                                    'scheme' => 'Front View step profile: 4 Marks | Top View alignment: 4 Marks | 1st Angle Symbol & dimensions: 2 Marks',
                                    'answer_key' => '1. Draw Front View (60x30 mm with step cutout). 2. Project Top View (60x40 mm). 3. Add 1st Angle Projection symbol.'
                                ]
                            ]
                        ],
                        'option_b' => [
                            'title' => 'Option B (Line Projections & L-Bracket Orthographic)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'A line CD 80 mm long has end C 20 mm above HP and 25 mm in front of VP. Line is inclined at 45° to VP and parallel to HP. Draw its projections.',
                                    'marks' => 10,
                                    'scheme' => 'XY line & Point C setup: 2 Marks | Top View cd at 45°: 4 Marks | Front View c’d’ horizontal: 3 Marks | Dimensions: 1 Mark',
                                    'answer_key' => '1. Mark c’ 20 mm above XY, c 25 mm below XY. 2. Top View cd = 80 mm at 45° to XY. 3. Project d up to meet horizontal line from c’.'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Draw 1st Angle Orthographic Projections (Front View, Top View, and Right Side View) of an L-bracket component (60x60x15 mm thick).',
                                    'marks' => 10,
                                    'scheme' => 'Front View: 3 Marks | Top View: 3 Marks | Side View: 3 Marks | Symbol & Neatness: 1 Mark',
                                    'answer_key' => '1. Draw Front View L-shape (60x60x15 mm). 2. Project Top View rectangle. 3. Project Side View on left of Front View.'
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            return [
                'test_title' => 'SERIES EXAMINATION 2 (CAD 2D DRAFTING & PLOTTING)',
                'modules_covered' => 'Module III (CAD Commands & Layering) & Module IV (CAD 2D Component Drafting & Sectional Plotting)',
                'duration' => '1.5 Hours / 2 Hours',
                'max_marks' => 40,
                'co_tags' => 'CO3 & CO4',
                'instructions' => 'Answer Question 1 (Module III) AND Question 2 (Module IV). Choose EITHER Option A OR Option B in each question.',
                'questions' => [
                    [
                        'q_no' => 'Q1',
                        'module' => 'Module III',
                        'co' => 'CO3',
                        'total_marks' => 20,
                        'option_a' => [
                            'title' => 'Option A (CAD Environment & A4 Title Block)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Set up CAD environment: Units: Decimal (mm), Limits: (0,0) to (297,210). Create layers: OUTLINE (White, 0.4mm), CENTER (Red, 0.18mm), HIDDEN (Yellow, 0.25mm), DIM (Cyan, 0.18mm).',
                                    'marks' => 10,
                                    'scheme' => 'Units & Limits commands: 3 Marks | Layer setup & colors: 4 Marks | Linetypes & line weights: 3 Marks',
                                    'answer_key' => 'UNITS -> Decimal 0.00 | LIMITS -> 0,0 to 297,210 -> ZOOM -> ALL | LAYER -> Create OUTLINE, CENTER, HIDDEN, DIM.'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Create a standard A4 Title Block at bottom right containing College Name, Student Name, Reg No, Date, and Scale (1:1) using RECTANG, OFFSET, and MTEXT.',
                                    'marks' => 10,
                                    'scheme' => 'Title block border geometry: 4 Marks | Text formatting & alignment: 4 Marks | Scale & Reg No entries: 2 Marks',
                                    'answer_key' => 'RECTANG -> @185,65 | EXPLODE | OFFSET -> 10, 20 | MTEXT -> Height 3.5mm for fields, 5mm for College Name.'
                                ]
                            ]
                        ],
                        'option_b' => [
                            'title' => 'Option B (Coordinate Systems & Dimension Style)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Explain CAD coordinate entry systems (Absolute, Relative Rectangular, Relative Polar) with command syntax examples for a 50x30 mm rectangle.',
                                    'marks' => 10,
                                    'scheme' => 'Absolute coordinates explanation: 3 Marks | Relative rectangular syntax: 3 Marks | Relative polar syntax: 4 Marks',
                                    'answer_key' => '1. Absolute: 0,0 to 50,0 to 50,30 to 0,30. 2. Relative Rectangular: @50,0 to @0,30 to @-50,0. 3. Relative Polar: @50<0 to @30<90.'
                                ],
                                [
                                    'sub_no' => '(ii)',
                                    'text' => 'Configure a custom dimension style ISO-25 in CAD setting Text Height 3.5mm, Arrowhead 2.5mm, and Extension Line Offset 1.5mm.',
                                    'marks' => 10,
                                    'scheme' => 'DIMSTYLE manager setup: 3 Marks | Text & Arrow parameters: 4 Marks | Extension line offset & Save: 3 Marks',
                                    'answer_key' => 'DIMSTYLE -> Modify -> Lines (Offset 1.5mm) -> Symbols & Arrows (Size 2.5mm) -> Text (Height 3.5mm).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'q_no' => 'Q2',
                        'module' => 'Module IV',
                        'co' => 'CO4',
                        'total_marks' => 20,
                        'option_a' => [
                            'title' => 'Option A (Sectional Shaft Drafting & PDF Plotting)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Draft the 2D Sectional Front View and Top View of a Flanged Shaft Component in CAD. Apply HATCH (ANSI31, Scale 1.0), complete dimensioning, and Plot to PDF (RegNo_CA2.pdf).',
                                    'marks' => 20,
                                    'scheme' => '2D Geometry & Alignment: 8 Marks | Sectional Hatching: 4 Marks | Dimensioning style: 4 Marks | PDF Plot layout: 4 Marks',
                                    'answer_key' => '1. Centerline on CENTER layer. 2. Draw upper profile on OUTLINE layer. 3. MIRROR. 4. HATCH (ANSI31). 5. Add dimensions on DIM. 6. PLOT -> DWG to PDF.pc3 A4.'
                                ]
                            ]
                        ],
                        'option_b' => [
                            'title' => 'Option B (Stepped Pulley & Keyway Drafting)',
                            'sub_questions' => [
                                [
                                    'sub_no' => '(i)',
                                    'text' => 'Draft the 2D Front View and Top View of a Stepped Pulley with Keyway in CAD. Draw hidden lines on HIDDEN layer, add complete dimensions, and plot to A4 PDF.',
                                    'marks' => 20,
                                    'scheme' => 'Pulley & Keyway Geometry: 8 Marks | Hidden Layer Detail: 4 Marks | Complete Dimensions: 4 Marks | PDF Plotting: 4 Marks',
                                    'answer_key' => '1. Draw concentric circles for pulley steps and keyway slot. 2. Project Front View with hidden keyway lines on HIDDEN layer. 3. Plot to A4 PDF.'
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
    }

    /**
     * API: Get Question Bank JSON for Series Test 1 or 2
     */
    public function getSeriesQpApi($subjectId, $testNo = 1)
    {
        $cf = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $stored = $cf ? ($cf->series_test_qps ?? []) : [];
        $key = 'test_' . $testNo;

        if (isset($stored[$key]) && !empty($stored[$key])) {
            return response()->json([
                'status' => 'SUCCESS',
                'data' => $stored[$key]
            ]);
        }

        $default = $this->getDefaultSeriesQuestions($testNo);
        return response()->json([
            'status' => 'SUCCESS',
            'data' => $default
        ]);
    }

    /**
     * API: Save Customized Question Bank & QP into Database
     */
    public function saveSeriesQpApi(Request $request, $subjectId)
    {
        $testNo = $request->input('test_no', 1);
        $payload = $request->input('payload');

        $cf = R26DrawingCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'course_title' => 'Engineering Drawing with CAD',
                'course_code' => '1004',
                'cie_marks' => 60,
                'ese_marks' => 40
            ]
        );

        $stored = $cf->series_test_qps ?: [];
        $key = 'test_' . $testNo;
        $stored[$key] = $payload;

        $cf->series_test_qps = $stored;
        $cf->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Series Test {$testNo} Question Bank & QP successfully saved!"
        ]);
    }

    /**
     * Print View for Drawing Lab Series Examination (QP, Valuation Scheme, or Answer Key)
     */
    public function printSeriesTestQP(Request $request, $subjectId, $testNo = 1)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first()
            ?? R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $drawingCourseFile = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();

        $staffMobile = Session::get('mobileNo') ?: Session::get('userId');
        $staff = StaffProfile::where('mobile_no', $staffMobile)->first();

        $docType = strtolower($request->query('doc_type', 'qp'));

        $stored = $drawingCourseFile ? ($drawingCourseFile->series_test_qps ?? []) : [];
        $key = 'test_' . $testNo;

        if (isset($stored[$key]) && !empty($stored[$key])) {
            $qpData = $stored[$key];
        } else {
            $qpData = $this->getDefaultSeriesQuestions($testNo);
        }

        return view('r26_drawing.series_test_qp_print', compact(
            'batchSubject', 'classroom', 'drawingCourseFile', 'staff', 'testNo', 'docType', 'qpData'
        ));
    }

    /**
     * Print Subject-Wise Attendance Log & CIA Attendance Report for R26 Drawing Lab
     */
    public function printAttendanceReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = \App\Models\R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $drawingCourseFile = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();

        // Fetch 15-Slot Drawing Lab Lesson Plans
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date', 'topic_content', 'co_id', 'pedagogy', 'status']);

        // Fetch Attendance Records
        $allAttendance = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get();

        $attByPlan = $allAttendance->groupBy('lesson_plan_id');

        // Build Attendance Matrix & Totals per Student
        $attendanceMatrix = [];
        $attendanceTotals = [];

        foreach ($students as $st) {
            $attendanceMatrix[$st->reg_no] = [];
            $attendanceTotals[$st->reg_no] = [
                'present' => 0,
                'total' => 0,
                'percentage' => 100.0,
                'cia_marks' => 5
            ];
        }

        foreach ($lessonPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');

            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;
                $attendanceMatrix[$st->reg_no][$plan->id] = $status;

                if ($status !== null) {
                    $attendanceTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $attendanceTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        // Compute Percentages & CIA Attendance Marks
        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $tot = $attendanceTotals[$rNo]['total'];
            $pres = $attendanceTotals[$rNo]['present'];

            if ($tot > 0) {
                $pct = round(($pres / $tot) * 100, 1);
                $attendanceTotals[$rNo]['percentage'] = $pct;

                if ($pct >= 90) { $am = 5; }
                elseif ($pct >= 80) { $am = 4; }
                elseif ($pct >= 75) { $am = 3; }
                elseif ($pct >= 70) { $am = 2; }
                elseif ($pct >= 65) { $am = 1; }
                else { $am = 0; }

                $attendanceTotals[$rNo]['cia_marks'] = $am;
            } else {
                $attendanceTotals[$rNo]['percentage'] = null;
                $attendanceTotals[$rNo]['cia_marks'] = 0;
            }
        }

        // Assigned Staff & HOD
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation')
            ->first();

        return view('r26_drawing.attendance_report_print', compact(
            'batchSubject',
            'classroom',
            'students',
            'drawingCourseFile',
            'lessonPlans',
            'attendanceMatrix',
            'attendanceTotals',
            'assignedStaff',
            'hod'
        ));
    }

    /**
     * Print Consolidated Single-Page A4 Sheet Attendance Report for R26 Drawing Lab
     */
    public function printConsolidatedAttendanceReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = \App\Models\R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $drawingCourseFile = R26DrawingCourseFile::where('batch_subject_id', $subjectId)->first();

        // Fetch Lesson Plans & Attendance Records
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get(['id', 'day_no', 'proposed_date', 'actual_date']);

        $allAttendance = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get();

        $attByPlan = $allAttendance->groupBy('lesson_plan_id');

        $attendanceTotals = [];
        foreach ($students as $st) {
            $attendanceTotals[$st->reg_no] = [
                'present' => 0,
                'total' => 0,
                'percentage' => 100.0,
                'cia_marks' => 5
            ];
        }

        foreach ($lessonPlans as $plan) {
            $planAtt = $attByPlan->get($plan->id, collect());
            $planAttByReg = $planAtt->keyBy('reg_no');

            foreach ($students as $st) {
                $rec = $planAttByReg->get($st->reg_no);
                $status = $rec ? $rec->status : null;

                if ($status !== null) {
                    $attendanceTotals[$st->reg_no]['total']++;
                    if (in_array($status, ['Present', 'Late'])) {
                        $attendanceTotals[$st->reg_no]['present']++;
                    }
                }
            }
        }

        foreach ($students as $st) {
            $rNo = $st->reg_no;
            $tot = $attendanceTotals[$rNo]['total'];
            $pres = $attendanceTotals[$rNo]['present'];

            if ($tot > 0) {
                $pct = round(($pres / $tot) * 100, 1);
                $attendanceTotals[$rNo]['percentage'] = $pct;

                if ($pct >= 90) { $am = 5; }
                elseif ($pct >= 80) { $am = 4; }
                elseif ($pct >= 75) { $am = 3; }
                elseif ($pct >= 70) { $am = 2; }
                elseif ($pct >= 65) { $am = 1; }
                else { $am = 0; }

                $attendanceTotals[$rNo]['cia_marks'] = $am;
            } else {
                $attendanceTotals[$rNo]['percentage'] = null;
                $attendanceTotals[$rNo]['cia_marks'] = 0;
            }
        }

        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation')
            ->first();

        return view('r26_drawing.attendance_consolidated_print', compact(
            'batchSubject',
            'classroom',
            'students',
            'drawingCourseFile',
            'lessonPlans',
            'attendanceTotals',
            'assignedStaff',
            'hod'
        ));
    }
}
