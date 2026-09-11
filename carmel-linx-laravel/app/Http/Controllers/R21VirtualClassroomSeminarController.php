<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\CourseFile;
use App\Models\SeminarEvaluation;
use App\Models\StudentSeminarRegistration;
use App\Models\AcademicMark;
use App\Models\StaffProfile;

class R21VirtualClassroomSeminarController extends Controller
{
    /**
     * Virtual Seminar Room Dashboard (R-2021 Regulation Clause 11.2.6)
     * CIA only in Semester 5, treated as ESE mark (Total 75 Marks)
     * Rubrics:
     *   1. Relevance of Topic: 10% (7.5 Marks)
     *   2. Literature Survey: 10% (7.5 Marks)
     *   3. Presentation (Slides, Delivery): 50% (37.5 Marks)
     *   4. Interaction / Discussion: 10% (7.5 Marks)
     *   5. Seminar Report: 10% (7.5 Marks)
     *   6. Attendance: 10% (7.5 Marks)
     * Total: 100% = 75 Marks
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

        // Classroom details
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Course File for syllabus PDF
        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'syllabus_pdf_path' => null,
                'parsed_modules' => [],
                'parsed_cos' => [],
                'parsed_copo' => [],
                'parsed_textbooks' => []
            ]
        );

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Seminar Registrations (Topic, Date, Guide)
        $seminarRegs = StudentSeminarRegistration::where('batch_subject_id', $subjectId)
            ->with('guide')
            ->get()
            ->keyBy('reg_no');

        // Seminar Evaluations
        $allEvaluations = SeminarEvaluation::where('batch_subject_id', $subjectId)->get();
        $myEvaluations = $allEvaluations->where('assessor_mobile_no', $userId)->keyBy('reg_no');

        // Lab Batches from r26_student_lab_batches
        $labBatches = DB::table('r26_student_lab_batches')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Attendance data from student_attendance
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Department Guides
        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $guides = StaffProfile::where(function($q) use ($deptCode) {
                if (!empty($deptCode)) {
                    $q->where('branch', $deptCode);
                }
            })
            ->whereIn('designation', ['HOD', 'Lecturer', 'Demonstrator', 'Workshop Superintendent', 'Assistant Professor'])
            ->orderBy('name', 'asc')
            ->get(['mobile_no', 'name', 'designation']);

        if ($guides->isEmpty()) {
            $guides = StaffProfile::orderBy('name', 'asc')->get(['mobile_no', 'name', 'designation']);
        }

        // Active Staff Info
        $activeStaff = StaffProfile::where('mobile_no', $userId)->first();
        $staffProfiles = StaffProfile::all()->keyBy('mobile_no');

        // Process student results
        $studentResults = $students->map(function ($student) use ($seminarRegs, $allEvaluations, $myEvaluations, $labBatches, $attendanceData, $staffProfiles) {
            $regNo = $student->reg_no;
            $reg = $seminarRegs->get($regNo);
            $myEval = $myEvaluations->get($regNo);
            $stAllEvals = $allEvaluations->where('reg_no', $regNo);
            $evalCount = $stAllEvals->count();

            // Averaged rubrics across all assessors
            $avgRelevance = $evalCount > 0 ? round($stAllEvals->avg('relevance'), 2) : null;
            $avgLiterature = $evalCount > 0 ? round($stAllEvals->avg('literature'), 2) : null;
            $avgPresentation = $evalCount > 0 ? round($stAllEvals->avg('presentation'), 2) : null;
            $avgInteraction = $evalCount > 0 ? round($stAllEvals->avg('interaction'), 2) : null;
            $avgReport = $evalCount > 0 ? round($stAllEvals->avg('report'), 2) : null;
            $avgAttendance = $evalCount > 0 ? round($stAllEvals->avg('attendance'), 2) : null;
            $finalAvgScore = $evalCount > 0 ? round($stAllEvals->avg('total_score'), 2) : 0.0;

            // Suggested Attendance mark based on student_attendance
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 1) : 100.0;

            // SBTE 10% Attendance Slab (Max 7.5 M)
            if ($attPercentage >= 90) { $suggestedAttMark = 7.5; }
            elseif ($attPercentage >= 80) { $suggestedAttMark = 6.0; }
            elseif ($attPercentage >= 75) { $suggestedAttMark = 4.5; }
            elseif ($attPercentage >= 70) { $suggestedAttMark = 3.0; }
            elseif ($attPercentage >= 65) { $suggestedAttMark = 1.5; }
            else { $suggestedAttMark = 0.0; }

            // Batch assignment
            $batchRow = $labBatches->get($regNo);
            $batchAssignment = $batchRow ? (string)$batchRow->lab_batch : 'Unassigned';
            if ($batchAssignment === '1' || $batchAssignment === 'Batch 1') $batchAssignment = '1';
            elseif ($batchAssignment === '2' || $batchAssignment === 'Batch 2') $batchAssignment = '2';
            else $batchAssignment = 'Unassigned';

            // SBTE Polytechnic Grading (out of 75 Marks)
            $gradeData = self::calculateSbteGrade($finalAvgScore, $evalCount > 0);

            // Status
            $isCompleted = ($evalCount > 0);
            $isScheduled = ($reg && !empty($reg->presentation_date));
            $status = $isCompleted ? 'Completed' : ($isScheduled ? 'Scheduled' : 'Pending');

            $assessorsList = $stAllEvals->map(function ($ev) use ($staffProfiles) {
                $sp = $staffProfiles->get($ev->assessor_mobile_no);
                return [
                    'assessor_mobile' => $ev->assessor_mobile_no,
                    'assessor_name' => $sp ? $sp->name : $ev->assessor_mobile_no,
                    'designation' => $sp ? $sp->designation : 'Assessor',
                    'relevance' => (float)$ev->relevance,
                    'literature' => (float)$ev->literature,
                    'presentation' => (float)$ev->presentation,
                    'interaction' => (float)$ev->interaction,
                    'report' => (float)$ev->report,
                    'attendance' => (float)$ev->attendance,
                    'total_score' => (float)$ev->total_score,
                ];
            })->values();

            return [
                'reg_no' => $regNo,
                'sbte_reg_no' => $student->sbte_reg_no ?? $regNo,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'academic_status' => $student->academic_status,
                'batch' => $batchAssignment,
                'topic' => $reg ? $reg->topic : null,
                'presentation_date' => $reg && $reg->presentation_date ? date('Y-m-d', strtotime($reg->presentation_date)) : null,
                'presentation_date_formatted' => $reg && $reg->presentation_date ? date('d-m-Y', strtotime($reg->presentation_date)) : null,
                'guide_name' => $reg && $reg->guide ? $reg->guide->name : null,
                'guide_mobile_no' => $reg ? $reg->guide_mobile_no : null,
                'att_percentage' => $attPercentage,
                'suggested_att_mark' => $suggestedAttMark,
                'my_evaluation' => $myEval ? [
                    'relevance' => (float)$myEval->relevance,
                    'literature' => (float)$myEval->literature,
                    'presentation' => (float)$myEval->presentation,
                    'interaction' => (float)$myEval->interaction,
                    'report' => (float)$myEval->report,
                    'attendance' => (float)$myEval->attendance,
                    'total_score' => (float)$myEval->total_score,
                ] : null,
                'assessors_list' => $assessorsList,
                'eval_count' => $evalCount,
                'avg_relevance' => $avgRelevance,
                'avg_literature' => $avgLiterature,
                'avg_presentation' => $avgPresentation,
                'avg_interaction' => $avgInteraction,
                'avg_report' => $avgReport,
                'avg_attendance' => $avgAttendance,
                'final_score' => $finalAvgScore,
                'letter_grade' => $gradeData['grade'],
                'grade_point' => $gradeData['point'],
                'result' => $gradeData['result'],
                'status' => $status,
                'is_completed' => $isCompleted
            ];
        });

        // Statistics
        $totalStudents = $studentResults->count();
        $completedCount = $studentResults->where('is_completed', true)->count();
        $pendingCount = $totalStudents - $completedCount;
        $batch1Count = $studentResults->where('batch', '1')->count();
        $batch2Count = $studentResults->where('batch', '2')->count();
        $unassignedCount = $studentResults->where('batch', 'Unassigned')->count();
        $classAvg = $completedCount > 0 ? round($studentResults->where('is_completed', true)->avg('final_score'), 2) : 0.0;

        return view('r21_seminar.virtual_classroom_seminar', compact(
            'batchSubject',
            'classroom',
            'courseFile',
            'students',
            'studentResults',
            'guides',
            'activeStaff',
            'totalStudents',
            'completedCount',
            'pendingCount',
            'batch1Count',
            'batch2Count',
            'unassignedCount',
            'classAvg'
        ));
    }

    /**
     * Upload & Save Syllabus PDF for Seminar
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $request->validate([
            'syllabus_file' => 'required|mimes:pdf|max:15360'
        ]);

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $file = $request->file('syllabus_file');
        $filename = 'r21_seminar_syllabus_' . $subjectId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('syllabi', $filename, 'public');

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $courseFile->syllabus_pdf_path = '/storage/' . $path;
        $courseFile->save();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Syllabus PDF uploaded successfully.',
            'path' => $courseFile->syllabus_pdf_path
        ]);
    }

    /**
     * Save / Upsert Seminar Evaluation (Clause 11.2.6 - 75 Marks Total)
     */
    public function saveEvaluation(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Please log in.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'reg_no' => 'required|string',
            'relevance' => 'required|numeric|min:0|max:7.5',
            'literature' => 'required|numeric|min:0|max:7.5',
            'presentation' => 'required|numeric|min:0|max:37.5',
            'interaction' => 'required|numeric|min:0|max:7.5',
            'report' => 'required|numeric|min:0|max:7.5',
            'attendance' => 'required|numeric|min:0|max:7.5',
        ]);

        $regNo = $request->input('reg_no');
        $relevance = round((float)$request->input('relevance'), 2);
        $literature = round((float)$request->input('literature'), 2);
        $presentation = round((float)$request->input('presentation'), 2);
        $interaction = round((float)$request->input('interaction'), 2);
        $report = round((float)$request->input('report'), 2);
        $attendance = round((float)$request->input('attendance'), 2);

        $totalScore = round($relevance + $literature + $presentation + $interaction + $report + $attendance, 2);
        if ($totalScore > 75.0) {
            $totalScore = 75.0;
        }

        // Check if an assessor was selected from modal or use current user
        $requestedAssessor = $request->input('assessor_mobile_no');
        if ($requestedAssessor && StaffProfile::where('mobile_no', $requestedAssessor)->exists()) {
            $assessorMobile = $requestedAssessor;
        } else {
            $assessorMobile = $userId;
            if (!StaffProfile::where('mobile_no', $assessorMobile)->exists()) {
                $fallback = DB::table('subject_staff_assignments')
                    ->where('batch_subject_id', $subjectId)
                    ->value('staff_mobile_no');
                if (!$fallback) {
                    $fallback = StaffProfile::value('mobile_no');
                }
                if ($fallback) {
                    $assessorMobile = $fallback;
                }
            }
        }

        // 1. Save assessor evaluation
        SeminarEvaluation::updateOrCreate(
            [
                'batch_subject_id' => $subjectId,
                'reg_no' => $regNo,
                'assessor_mobile_no' => $assessorMobile
            ],
            [
                'relevance' => $relevance,
                'literature' => $literature,
                'presentation' => $presentation,
                'interaction' => $interaction,
                'report' => $report,
                'attendance' => $attendance,
                'total_score' => $totalScore
            ]
        );

        // 2. Also update Topic, Guide and Presentation Date if provided
        if ($request->has('topic') && !empty(trim($request->input('topic') ?? ''))) {
            $guideMobile = trim($request->input('guide_mobile_no') ?? '');
            if ($guideMobile === '' || !StaffProfile::where('mobile_no', $guideMobile)->exists()) {
                $guideMobile = null;
            }
            StudentSeminarRegistration::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'topic' => trim($request->input('topic')),
                    'presentation_date' => $request->input('presentation_date') ?: null,
                    'guide_mobile_no' => $guideMobile
                ]
            );
        }

        // 3. Compute averaged score across all assessors for this student
        $studentAllEvals = SeminarEvaluation::where('batch_subject_id', $subjectId)
            ->where('reg_no', $regNo)
            ->get();

        $evalCount = $studentAllEvals->count();
        $averageScore = $evalCount > 0 ? round($studentAllEvals->avg('total_score'), 2) : $totalScore;

        // 4. Upsert into AcademicMark as Continuous Internal Assessment (CIA / ESE Mark for S5) out of 75
        DB::table('syllabus_registry')->updateOrInsert(
            ['subject_code' => $batchSubject->subject_code],
            [
                'subject_name' => $batchSubject->subject_name,
                'revision_year' => 2021,
                'co_count' => 6,
                'cia_marks' => 75,
                'ese_marks' => 0,
                'credits' => 1.5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $existingMark = AcademicMark::where('reg_no', $regNo)
            ->where('subject_code', $batchSubject->subject_code)
            ->where('category', 'Seminar')
            ->first();

        if ($existingMark) {
            $existingMark->marks_obtained = $averageScore;
            $existingMark->max_marks = 75;
            $existingMark->co_tag = 'CO1';
            $existingMark->entered_by = $assessorMobile;
            $existingMark->save();
        } else {
            $newMark = new AcademicMark();
            $newMark->reg_no = $regNo;
            $newMark->subject_code = $batchSubject->subject_code;
            $newMark->category = 'Seminar';
            $newMark->co_tag = 'CO1';
            $newMark->max_marks = 75;
            $newMark->marks_obtained = $averageScore;
            $newMark->entered_by = $assessorMobile;
            $newMark->save();
        }

        // Compute SBTE Grade
        $gradeData = self::calculateSbteGrade($averageScore, true);

        // Total completed seminars count for the subject
        $completedStudentsCount = SeminarEvaluation::where('batch_subject_id', $subjectId)
            ->distinct('reg_no')
            ->count('reg_no');

        $staffProfiles = StaffProfile::all()->keyBy('mobile_no');
        $assessorsList = $studentAllEvals->map(function ($ev) use ($staffProfiles) {
            $sp = $staffProfiles->get($ev->assessor_mobile_no);
            return [
                'assessor_mobile' => $ev->assessor_mobile_no,
                'assessor_name' => $sp ? $sp->name : $ev->assessor_mobile_no,
                'designation' => $sp ? $sp->designation : 'Assessor',
                'relevance' => (float)$ev->relevance,
                'literature' => (float)$ev->literature,
                'presentation' => (float)$ev->presentation,
                'interaction' => (float)$ev->interaction,
                'report' => (float)$ev->report,
                'attendance' => (float)$ev->attendance,
                'total_score' => (float)$ev->total_score,
            ];
        })->values();

        $updatedReg = StudentSeminarRegistration::where('batch_subject_id', $subjectId)
            ->where('reg_no', $regNo)
            ->with('guide')
            ->first();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Seminar evaluation saved successfully.',
            'data' => [
                'reg_no' => $regNo,
                'my_total' => $totalScore,
                'average_score' => $averageScore,
                'eval_count' => $evalCount,
                'letter_grade' => $gradeData['grade'],
                'grade_point' => $gradeData['point'],
                'result' => $gradeData['result'],
                'completed_count' => $completedStudentsCount,
                'assessors_list' => $assessorsList,
                'topic' => $updatedReg ? $updatedReg->topic : null,
                'presentation_date' => $updatedReg && $updatedReg->presentation_date ? date('Y-m-d', strtotime($updatedReg->presentation_date)) : null,
                'presentation_date_formatted' => $updatedReg && $updatedReg->presentation_date ? date('d-m-Y', strtotime($updatedReg->presentation_date)) : null,
                'guide_name' => $updatedReg && $updatedReg->guide ? $updatedReg->guide->name : null,
                'guide_mobile_no' => $updatedReg ? $updatedReg->guide_mobile_no : null,
            ]
        ]);
    }

    /**
     * Update Student Seminar Schedule & Log (Topic, Date, Guide)
     */
    public function updateSeminarSchedule(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Please log in.'], 401);
        }

        $request->validate([
            'reg_no' => 'required|string',
            'topic' => 'required|string|max:255',
            'presentation_date' => 'nullable|date',
            'guide_mobile_no' => 'nullable|string|max:30',
        ]);

        $regNo = $request->input('reg_no');
        $topic = trim($request->input('topic'));
        $presentationDate = $request->input('presentation_date');
        $guideMobile = trim($request->input('guide_mobile_no') ?? '');
        if ($guideMobile === '' || !StaffProfile::where('mobile_no', $guideMobile)->exists()) {
            $guideMobile = null;
        }

        $semReg = StudentSeminarRegistration::updateOrCreate(
            [
                'batch_subject_id' => $subjectId,
                'reg_no' => $regNo
            ],
            [
                'topic' => $topic,
                'presentation_date' => $presentationDate,
                'guide_mobile_no' => $guideMobile
            ]
        );

        $guide = StaffProfile::where('mobile_no', $guideMobile)->first();

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Seminar schedule and log updated successfully.',
            'data' => [
                'reg_no' => $regNo,
                'topic' => $semReg->topic,
                'presentation_date' => $semReg->presentation_date ? date('Y-m-d', strtotime($semReg->presentation_date)) : null,
                'presentation_date_formatted' => $semReg->presentation_date ? date('d-m-Y', strtotime($semReg->presentation_date)) : null,
                'guide_name' => $guide ? $guide->name : '-',
                'guide_mobile_no' => $guideMobile
            ]
        ]);
    }

    /**
     * Print Consolidated Seminar Evaluation Sheet (Clause 11.2.6 - 75M)
     */
    public function printReport($subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no), roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $allEvaluations = SeminarEvaluation::where('batch_subject_id', $subjectId)->get();
        $seminarRegs = StudentSeminarRegistration::where('batch_subject_id', $subjectId)->with('guide')->get()->keyBy('reg_no');

        $reportData = $students->map(function ($student) use ($allEvaluations, $seminarRegs) {
            $regNo = $student->reg_no;
            $reg = $seminarRegs->get($regNo);
            $stAllEvals = $allEvaluations->where('reg_no', $regNo);
            $evalCount = $stAllEvals->count();

            $avgRelevance = $evalCount > 0 ? round($stAllEvals->avg('relevance'), 2) : 0;
            $avgLiterature = $evalCount > 0 ? round($stAllEvals->avg('literature'), 2) : 0;
            $avgPresentation = $evalCount > 0 ? round($stAllEvals->avg('presentation'), 2) : 0;
            $avgInteraction = $evalCount > 0 ? round($stAllEvals->avg('interaction'), 2) : 0;
            $avgReport = $evalCount > 0 ? round($stAllEvals->avg('report'), 2) : 0;
            $avgAttendance = $evalCount > 0 ? round($stAllEvals->avg('attendance'), 2) : 0;
            $finalAvgScore = $evalCount > 0 ? round($stAllEvals->avg('total_score'), 2) : 0;

            $gradeData = self::calculateSbteGrade($finalAvgScore, $evalCount > 0);

            return [
                'roll_no' => $student->roll_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $regNo,
                'name' => $student->name,
                'topic' => $reg ? $reg->topic : '-',
                'presentation_date' => $reg && $reg->presentation_date ? date('d-m-Y', strtotime($reg->presentation_date)) : '-',
                'guide_name' => $reg && $reg->guide ? $reg->guide->name : '-',
                'relevance' => $avgRelevance,
                'literature' => $avgLiterature,
                'presentation' => $avgPresentation,
                'interaction' => $avgInteraction,
                'report' => $avgReport,
                'attendance' => $avgAttendance,
                'total_score' => $finalAvgScore,
                'letter_grade' => $gradeData['grade'],
                'grade_point' => $gradeData['point'],
                'result' => $gradeData['result'],
                'eval_count' => $evalCount
            ];
        });

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
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
        $fullDepartment = $branchMap[strtoupper($deptCode)] ?? $deptCode;

        return view('r21_seminar.seminar_report_print', [
            'subject' => $batchSubject,
            'classroom' => $classroom,
            'fullDepartment' => $fullDepartment,
            'students' => $reportData,
            'totalStudents' => $reportData->count(),
            'currentYear' => date('Y')
        ]);
    }

    /**
     * SBTE Kerala Polytechnic Grading Scale (Max 75 Marks)
     */
    public static function calculateSbteGrade($score, $hasEvaluations = true)
    {
        if (!$hasEvaluations || $score === null || $score === '') {
            return ['grade' => '-', 'point' => '-', 'result' => 'Pending'];
        }

        $s = (float)$score;
        $pct = ($s / 75.0) * 100.0;

        if ($pct >= 90.0) {
            return ['grade' => 'S', 'point' => 10, 'result' => 'Pass'];
        } elseif ($pct >= 80.0) {
            return ['grade' => 'A', 'point' => 9, 'result' => 'Pass'];
        } elseif ($pct >= 70.0) {
            return ['grade' => 'B', 'point' => 8, 'result' => 'Pass'];
        } elseif ($pct >= 60.0) {
            return ['grade' => 'C', 'point' => 7, 'result' => 'Pass'];
        } elseif ($pct >= 50.0) {
            return ['grade' => 'D', 'point' => 6, 'result' => 'Pass'];
        } elseif ($pct >= 40.0) {
            return ['grade' => 'E', 'point' => 5, 'result' => 'Pass'];
        } else {
            return ['grade' => 'F', 'point' => 0, 'result' => 'Failed'];
        }
    }
}
