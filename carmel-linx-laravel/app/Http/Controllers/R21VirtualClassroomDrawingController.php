<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\R21DrawingCourseFile;
use App\Models\R21DrawingSheetEvaluation;
use App\Models\R21DrawingSeriesTest;
use App\Models\R21DrawingAttendanceEvaluation;
use App\Models\StaffProfile;

class R21VirtualClassroomDrawingController extends Controller
{
    /**
     * Main Virtual Drawing Hall Dashboard (R-2021 Regulation 11.2.3)
     * CIA: 40% Formative + 40% Summative + 20% Attendance
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
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Default sheets (minimum 2 sheets per module across 4 modules = 8 sheets)
        $defaultSheets = [
            ['sheet_no' => 'Sheet 1', 'module' => 'Module 1', 'title' => 'Lettering, Numbering & Dimensioning Practice', 'co_id' => 'CO1', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 2', 'module' => 'Module 1', 'title' => 'Conic Sections - Ellipse & Parabola Constructions', 'co_id' => 'CO1', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 3', 'module' => 'Module 2', 'title' => 'Projections of Points and Straight Lines', 'co_id' => 'CO2', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 4', 'module' => 'Module 2', 'title' => 'Projections of Plane Surfaces (Lamina)', 'co_id' => 'CO2', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 5', 'module' => 'Module 3', 'title' => 'Projections of Regular Solids (Prisms & Pyramids)', 'co_id' => 'CO3', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 6', 'module' => 'Module 3', 'title' => 'Section of Solids & True Shape of Sections', 'co_id' => 'CO3', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 7', 'module' => 'Module 4', 'title' => 'Orthographic Projections from Isometric Views', 'co_id' => 'CO4', 'max_timely' => 50, 'max_appearance' => 50],
            ['sheet_no' => 'Sheet 8', 'module' => 'Module 4', 'title' => 'Development of Lateral Surfaces of Truncated Solids', 'co_id' => 'CO4', 'max_timely' => 50, 'max_appearance' => 50],
        ];

        // Fetch or Create Drawing Course File Record (R2021)
        $drawingCourseFile = R21DrawingCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? $classroom->branch ?? 'Engineering',
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
                'semester' => $classroom->current_semester ?? $batchSubject->semester ?? 'I',
                'type_of_course' => 'Drawing Courses',
                'teaching_scheme' => '0:0:3:0',
                'contact_hours' => 60,
                'credits' => 2.0,
                'cia_marks' => 50, // 40% Formative + 40% Summative + 20% Attendance
                'ese_marks' => 100,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Understand and apply principles of lettering, dimensioning and conic sections.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Draw orthographic projections of points, lines, and plane surfaces.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Construct projections of solids and sectional views of engineering components.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Develop orthographic projections and surface developments of 3D objects.', 'cognitive_level' => 'Apply']
                ],
                'parsed_modules' => [
                    ['module_id' => 'I', 'title' => 'Lettering, Dimensioning & Conic Sections', 'hours' => 15.0, 'content' => 'Drawing instruments, BIS codes, Lettering, Scales, Conic sections (Ellipse, Parabola)'],
                    ['module_id' => 'II', 'title' => 'Projections of Points, Lines & Planes', 'hours' => 15.0, 'content' => 'First angle projection, Points in quadrants, Lines inclined to planes, Projections of planes'],
                    ['module_id' => 'III', 'title' => 'Projections & Sections of Solids', 'hours' => 15.0, 'content' => 'Prisms, Pyramids, Cylinders, Cones, Section planes and true shape of section'],
                    ['module_id' => 'IV', 'title' => 'Orthographic Projections & Development of Surfaces', 'hours' => 15.0, 'content' => 'Multiview orthographic projections, Lateral surface development of truncated solids']
                ],
                'parsed_sheets' => $defaultSheets,
                'parsed_copo' => [
                    'credit' => 2.0,
                    'l_t_p_r' => '0:0:3:0',
                    'cia_marks' => 50,
                    'ese_marks' => 100,
                    'total_hours' => 60,
                    'mappings' => [
                        'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-'],
                        'CO2' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'2', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-'],
                        'CO3' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'3', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-'],
                        'CO4' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'3', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-']
                    ]
                ],
                'parsed_textbooks' => [
                    'Engineering Drawing - N. D. Bhatt & V. M. Panchal (Charotar Publishing House)',
                    'Engineering Graphics - K. C. John (PHI Learning)',
                    'Engineering Graphics - P. I. Varghese (VIP Publishers)'
                ]
            ]
        );

        if (empty($drawingCourseFile->parsed_sheets)) {
            $drawingCourseFile->parsed_sheets = $defaultSheets;
            $drawingCourseFile->save();
        }

        // Fetch / Auto-Generate Drawing Lab Lesson Plan
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() < 15) {
            $this->generateR21DrawingLessonPlan($batchSubject, $drawingCourseFile);
            $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        }

        // Attendance from student_attendance
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Attendance Evaluations Table (for overrides / caching)
        $attEvals = R21DrawingAttendanceEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Formative Sheet Evaluations
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Summative Series Tests (Test 1 & Test 2)
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $ciaMax = $drawingCourseFile->cia_marks ?: 50;
        $formativeMax = round($ciaMax * 0.40, 2); // 40% (20M for 50 CIA)
        $summativeMax = round($ciaMax * 0.40, 2); // 40% (20M for 50 CIA)
        $attMax       = round($ciaMax * 0.20, 2); // 20% (10M for 50 CIA)

        // Process Consolidated Student Computations
        $studentResults = $students->map(function ($student) use (
            $attendanceData,
            $attEvals,
            $sheetEvals,
            $seriesTests,
            $drawingCourseFile,
            $formativeMax,
            $summativeMax,
            $attMax,
            $ciaMax
        ) {
            $regNo = $student->reg_no;

            // 1. Attendance Marks (Clause 11.2.3.c - 20% of CIA)
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : 100.00;

            // Kerala SBTE Attendance Slab for 20% weightage
            if ($attPercentage >= 90) { $calcAttMark = $attMax; }
            elseif ($attPercentage >= 80) { $calcAttMark = round($attMax * 0.8, 2); }
            elseif ($attPercentage >= 75) { $calcAttMark = round($attMax * 0.6, 2); }
            elseif ($attPercentage >= 70) { $calcAttMark = round($attMax * 0.4, 2); }
            elseif ($attPercentage >= 65) { $calcAttMark = round($attMax * 0.2, 2); }
            else { $calcAttMark = 0.00; }

            // Check manual override if present in R21DrawingAttendanceEvaluation
            $savedAtt = $attEvals->get($regNo);
            $finalAttMark = ($savedAtt && $savedAtt->override_mark !== null) 
                ? floatval($savedAtt->override_mark) 
                : ($savedAtt ? floatval($savedAtt->attendance_mark) : $calcAttMark);

            // 2. Formative Assessment: Drawing Sheets (Clause 11.2.3.b - 40% of CIA)
            // Min 2 sheets per module evaluated on Timely Completion (50%) + Appearance (50%) = 100%
            $stSheets = $sheetEvals->get($regNo, collect());
            $evaluatedSheetsCount = $stSheets->where('is_absent', false)->count();
            $avgSheetScore100 = $evaluatedSheetsCount > 0 
                ? $stSheets->where('is_absent', false)->avg('total_score_100') 
                : 0.00;
            $formativeMark = round((($avgSheetScore100 / 100.0) * $formativeMax) * 2) / 2;

            // 3. Summative Assessment: Series Tests (Clause 11.2.3.a - 40% of CIA)
            // Average of Test 1 and Test 2 (each evaluated on Procedure 40% + Final 30% + Dimensioning 20% + Neatness 10% = 100%)
            $stTests = $seriesTests->get($regNo, collect());
            $t1 = $stTests->where('test_no', 'Test 1')->first();
            $t2 = $stTests->where('test_no', 'Test 2')->first();

            $t1Score = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : null;
            $t2Score = ($t2 && !$t2->is_absent) ? floatval($t2->total_score_100) : null;

            if ($t1Score !== null && $t2Score !== null) {
                $avgTestScore100 = ($t1Score + $t2Score) / 2.0;
            } elseif ($t1Score !== null) {
                $avgTestScore100 = $t1Score;
            } elseif ($t2Score !== null) {
                $avgTestScore100 = $t2Score;
            } else {
                $avgTestScore100 = 0.00;
            }

            $summativeMark = round((($avgTestScore100 / 100.0) * $summativeMax) * 2) / 2;

            // Total CIA (Max 50) = Formative (20) + Summative (20) + Attendance (10)
            $totalCia = round(($formativeMark + $summativeMark + $finalAttMark) * 2) / 2;
            if ($totalCia > $ciaMax) $totalCia = $ciaMax;

            // Pass eligibility in CIA (minimum 40% of CIA = 20/50)
            $isCiaPass = ($totalCia >= ($ciaMax * 0.40));

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_percentage' => $attPercentage,
                'att_marks' => $finalAttMark,
                'calc_att_marks' => $calcAttMark,
                'sheet_count' => $evaluatedSheetsCount,
                'avg_sheet_score' => round($avgSheetScore100, 2),
                'formative_mark' => $formativeMark,
                'test1_score' => $t1Score !== null ? $t1Score : '-',
                'test2_score' => $t2Score !== null ? $t2Score : '-',
                'avg_test_score' => round($avgTestScore100, 2),
                'summative_mark' => $summativeMark,
                'total_cia' => $totalCia,
                'is_pass' => $isCiaPass,
                'sheets_detail' => $stSheets->keyBy('sheet_no'),
                't1_detail' => $t1,
                't2_detail' => $t2,
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

        return view('r21_drawing.virtual_classroom_drawing', compact(
            'batchSubject',
            'classroom',
            'drawingCourseFile',
            'students',
            'lessonPlans',
            'studentResults',
            'sheetEvals',
            'seriesTests',
            'assignedStaff',
            'hod',
            'formativeMax',
            'summativeMax',
            'attMax',
            'ciaMax'
        ));
    }

    /**
     * Auto-Generate standard 60-Hour / 45-Hour Drawing Lab Lesson Plan
     */
    private function generateR21DrawingLessonPlan($batchSubject, $drawingCourseFile)
    {
        LessonPlan::where('batch_subject_id', $batchSubject->id)->delete();

        $modules = $drawingCourseFile->parsed_modules ?: [];
        $sheets  = $drawingCourseFile->parsed_sheets ?: [];

        $planTemplates = [
            ['day' => 1, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Introduction to Engineering Drawing, Drawing Instruments, Sheet Layout, BIS Conventions'],
            ['day' => 2, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Lettering (Single Stroke Vertical & Inclined), Numbering and Dimensioning practice'],
            ['day' => 3, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Drawing Sheet 1: Lettering, Dimensioning & Title Block Construction'],
            ['day' => 4, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Geometrical Constructions: Bisecting lines, angles, regular polygons (Pentagon, Hexagon)'],
            ['day' => 5, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Conic Sections: Ellipse by Concentric Circles and Rectangular methods'],
            ['day' => 6, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Conic Sections: Parabola by Tangent and Rectangular methods'],
            ['day' => 7, 'mod' => 'Module I', 'co' => 'CO1', 'topic' => 'Drawing Sheet 2: Conic Sections (Ellipse & Parabola Construction)'],
            ['day' => 8, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Concept of Orthographic Projection, First and Third Angle Projections, Quadrants'],
            ['day' => 9, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Projections of Points in all 4 Quadrants with respect to HP and VP'],
            ['day' => 10, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Projections of Straight Lines parallel and perpendicular to reference planes'],
            ['day' => 11, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Projections of Straight Lines inclined to one plane and parallel to the other'],
            ['day' => 12, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Drawing Sheet 3: Projections of Points and Straight Lines'],
            ['day' => 13, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Projections of Plane Surfaces (Triangular, Square, Pentagonal, Hexagonal, Circular Lamina)'],
            ['day' => 14, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Planes inclined to one reference plane and perpendicular to the other'],
            ['day' => 15, 'mod' => 'Module II', 'co' => 'CO2', 'topic' => 'Drawing Sheet 4: Projections of Plane Surfaces (Lamina)'],
            ['day' => 16, 'mod' => 'Summative', 'co' => 'CO1, CO2', 'topic' => 'Summative Assessment Test 1 (Modules I & II - 40M Weightage)'],
            ['day' => 17, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'Projections of Regular Solids: Prisms and Pyramids with axis perpendicular/parallel to HP/VP'],
            ['day' => 18, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'Projections of Cylinders and Cones inclined to one reference plane'],
            ['day' => 19, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'Drawing Sheet 5: Projections of Regular Solids (Prisms, Pyramids & Cones)'],
            ['day' => 20, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'Section of Solids: Section plane parallel and perpendicular to HP and VP'],
            ['day' => 21, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'True shape of section for Prisms and Cylinders'],
            ['day' => 22, 'mod' => 'Module III', 'co' => 'CO3', 'topic' => 'Drawing Sheet 6: Section of Solids & True Shape of Sections'],
            ['day' => 23, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Conversion of Pictorial/Isometric Views into Orthographic Multi-Views (Elevation, Plan, Side view)'],
            ['day' => 24, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Orthographic Projections of Machine Components with hidden lines'],
            ['day' => 25, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Drawing Sheet 7: Orthographic Projections from 3D Isometric Views'],
            ['day' => 26, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Development of Lateral Surfaces: Parallel Line Method for Prisms and Cylinders'],
            ['day' => 27, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Development of Lateral Surfaces: Radial Line Method for Pyramids and Cones'],
            ['day' => 28, 'mod' => 'Module IV', 'co' => 'CO4', 'topic' => 'Drawing Sheet 8: Development of Lateral Surfaces of Truncated Solids'],
            ['day' => 29, 'mod' => 'Summative', 'co' => 'CO3, CO4', 'topic' => 'Summative Assessment Test 2 (Modules III & IV - 40M Weightage)'],
            ['day' => 30, 'mod' => 'Consolidation', 'co' => 'All COs', 'topic' => 'Drawing Sheets Final Review, CIA Mark Finalization & Student Verification']
        ];

        foreach ($planTemplates as $idx => $p) {
            LessonPlan::create([
                'batch_subject_id' => $batchSubject->id,
                'day_no' => $p['day'],
                'remarks' => $p['mod'],
                'topic_content' => $p['topic'],
                'allocated_hours' => 2,
                'pedagogy' => 'Drawing Hall Board & Live Demonstration',
                'co_id' => $p['co'],
                'status' => 'Pending'
            ]);
        }
    }

    /**
     * Upload & Parse Syllabus PDF
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:15360'
        ]);

        $file = $request->file('syllabus_file');
        $filename = 'r21_drawing_syllabus_' . $subjectId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('syllabi', $filename, 'public');

        $courseFile = R21DrawingCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->syllabus_pdf_path = '/storage/' . $path;
        $courseFile->save();

        return response()->json([
            'success' => true,
            'message' => 'Syllabus PDF uploaded successfully.',
            'path' => $courseFile->syllabus_pdf_path
        ]);
    }

    /**
     * Save Formative Sheet Marks (Timely Completion 50% + Appearance 50% = 100%)
     */
    public function saveSheetMarks(Request $request, $subjectId)
    {
        $sheetNo = $request->input('sheet_no');
        $evaluations = $request->input('evaluations', []);

        if (empty($sheetNo) || !is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $sheetConfig = collect($courseFile->parsed_sheets ?? [])->where('sheet_no', $sheetNo)->first();

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $timely = floatval($eval['timely_completion'] ?? 0);
            $appearance = floatval($eval['appearance_organization'] ?? 0);
            $isAbsent = !empty($eval['is_absent']);

            if ($timely > 50) $timely = 50;
            if ($timely < 0) $timely = 0;
            if ($appearance > 50) $appearance = 50;
            if ($appearance < 0) $appearance = 0;

            $total = $isAbsent ? 0.00 : ($timely + $appearance);

            R21DrawingSheetEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'sheet_no' => $sheetNo,
                    'reg_no' => $regNo,
                ],
                [
                    'sheet_title' => $sheetConfig['title'] ?? $sheetNo,
                    'module_no' => $sheetConfig['module'] ?? null,
                    'co_id' => $sheetConfig['co_id'] ?? null,
                    'timely_completion' => $timely,
                    'appearance_organization' => $appearance,
                    'total_score_100' => $total,
                    'is_absent' => $isAbsent,
                    'remarks' => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Marks for {$sheetNo} saved successfully."
        ]);
    }

    /**
     * Save Summative Series Test Marks (4 Criteria breakdown)
     * Procedure 40% + Final 30% + Dimensioning 20% + Neatness 10% = 100%
     */
    public function saveSeriesTestMarks(Request $request, $subjectId)
    {
        $testNo = $request->input('test_no', 'Test 1');
        $evaluations = $request->input('evaluations', []);

        if (!is_array($evaluations)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        foreach ($evaluations as $eval) {
            $regNo = $eval['reg_no'] ?? null;
            if (!$regNo) continue;

            $procedure = floatval($eval['procedure_drawing'] ?? 0);
            $finalDraw = floatval($eval['final_drawing'] ?? 0);
            $dimen     = floatval($eval['dimensioning'] ?? 0);
            $neat      = floatval($eval['neatness'] ?? 0);
            $isAbsent  = !empty($eval['is_absent']);

            if ($procedure > 40) $procedure = 40;
            if ($finalDraw > 30) $finalDraw = 30;
            if ($dimen > 20)     $dimen = 20;
            if ($neat > 10)      $neat = 10;

            $total = $isAbsent ? 0.00 : ($procedure + $finalDraw + $dimen + $neat);

            R21DrawingSeriesTest::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'test_no' => $testNo,
                    'reg_no' => $regNo,
                ],
                [
                    'procedure_drawing' => $procedure,
                    'final_drawing' => $finalDraw,
                    'dimensioning' => $dimen,
                    'neatness' => $neat,
                    'total_score_100' => $total,
                    'is_absent' => $isAbsent,
                    'remarks' => $eval['remarks'] ?? null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Summative {$testNo} marks saved successfully."
        ]);
    }

    /**
     * Save Attendance Override Marks
     */
    public function saveAttendanceMarks(Request $request, $subjectId)
    {
        $records = $request->input('records', []);
        if (!is_array($records)) {
            return response()->json(['success' => false, 'message' => 'Invalid data payload.'], 400);
        }

        $courseFile = R21DrawingCourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $ciaMax = $courseFile->cia_marks ?: 50;
        $attMax = round($ciaMax * 0.20, 2);

        foreach ($records as $rec) {
            $regNo = $rec['reg_no'] ?? null;
            if (!$regNo) continue;

            $override = isset($rec['override_mark']) && $rec['override_mark'] !== '' 
                ? floatval($rec['override_mark']) 
                : null;

            if ($override !== null) {
                if ($override > $attMax) $override = $attMax;
                if ($override < 0) $override = 0;
            }

            $attMark = floatval($rec['attendance_mark'] ?? 0);
            $finalMark = $override !== null ? $override : $attMark;

            R21DrawingAttendanceEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo,
                ],
                [
                    'attendance_percentage' => floatval($rec['attendance_percentage'] ?? 100),
                    'attendance_mark' => $attMark,
                    'override_mark' => $override,
                    'final_attendance_mark' => $finalMark,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marks updated successfully.'
        ]);
    }

    /**
     * Print Drawing Formative Sheet Register
     */
    public function printFormativeRegister($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderBy('roll_no')->get();
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $sheets = $courseFile->parsed_sheets ?: [];

        return view('r21_drawing.sheet_evaluation_print', compact('batchSubject', 'courseFile', 'students', 'sheetEvals', 'sheets'));
    }

    /**
     * Print Summative Series Test Register
     */
    public function printSummativeRegister($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderBy('roll_no')->get();
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');

        return view('r21_drawing.summative_test_print', compact('batchSubject', 'courseFile', 'students', 'seriesTests'));
    }

    /**
     * Print Consolidated CIA Marksheet & Attainment
     */
    public function printConsolidatedCia($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)->orderBy('roll_no')->get();
        
        $sheetEvals = R21DrawingSheetEvaluation::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $seriesTests = R21DrawingSeriesTest::where('batch_subject_id', $subjectId)->get()->groupBy('reg_no');
        $attEvals = R21DrawingAttendanceEvaluation::where('batch_subject_id', $subjectId)->get()->keyBy('reg_no');

        $ciaMax = $courseFile->cia_marks ?: 50;
        $formativeMax = round($ciaMax * 0.40, 2);
        $summativeMax = round($ciaMax * 0.40, 2);
        $attMax       = round($ciaMax * 0.20, 2);

        $studentResults = $students->map(function ($s) use ($sheetEvals, $seriesTests, $attEvals, $formativeMax, $summativeMax, $attMax, $ciaMax) {
            $regNo = $s->reg_no;

            // Attendance
            $savedAtt = $attEvals->get($regNo);
            $attMark = $savedAtt ? floatval($savedAtt->final_attendance_mark) : $attMax;

            // Formative
            $stSheets = $sheetEvals->get($regNo, collect());
            $validSheets = $stSheets->where('is_absent', false);
            $avgSheetScore = $validSheets->count() > 0 ? $validSheets->avg('total_score_100') : 0.00;
            $formativeMark = round((($avgSheetScore / 100.0) * $formativeMax) * 2) / 2;

            // Summative
            $stTests = $seriesTests->get($regNo, collect());
            $t1 = $stTests->where('test_no', 'Test 1')->first();
            $t2 = $stTests->where('test_no', 'Test 2')->first();
            $t1Score = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : null;
            $t2Score = ($t2 && !$t2->is_absent) ? floatval($t2->total_score_100) : null;

            if ($t1Score !== null && $t2Score !== null) {
                $avgTest = ($t1Score + $t2Score) / 2.0;
            } elseif ($t1Score !== null) {
                $avgTest = $t1Score;
            } elseif ($t2Score !== null) {
                $avgTest = $t2Score;
            } else {
                $avgTest = 0.00;
            }
            $summativeMark = round((($avgTest / 100.0) * $summativeMax) * 2) / 2;

            $totalCia = round(($formativeMark + $summativeMark + $attMark) * 2) / 2;
            if ($totalCia > $ciaMax) $totalCia = $ciaMax;

            return [
                'student' => $s,
                'formative_mark' => $formativeMark,
                'summative_mark' => $summativeMark,
                'attendance_mark' => $attMark,
                'total_cia' => $totalCia,
                'is_pass' => ($totalCia >= ($ciaMax * 0.40)),
            ];
        });

        return view('r21_drawing.cia_consolidated_print', compact('batchSubject', 'courseFile', 'classroom', 'studentResults', 'ciaMax', 'formativeMax', 'summativeMax', 'attMax'));
    }

    /**
     * Print Lesson Plan
     */
    public function printLessonPlan($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $subjectId)->first();
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)->orderBy('day_no')->get();

        return view('r21_drawing.lesson_plan_print', compact('batchSubject', 'courseFile', 'classroom', 'lessonPlans'));
    }
}
