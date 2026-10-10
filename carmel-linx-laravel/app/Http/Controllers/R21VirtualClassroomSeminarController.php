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
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
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

        // Authoritative official attendance from TEAMS upload in student_attendance & class_logs_attendance
        $subjectAttendance = DB::table('student_attendance')
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Fallback to overall uploaded TEAMS attendance for the classroom students if seminar has no separate rows
        $overallAttendance = DB::table('student_attendance')
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->get()
            ->groupBy('reg_no');

        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->get();

        // Department Guides
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

        // Assigned faculty for this specific subject (Committee Member 1)
        $assignedFaculty = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.mobile_no', 'staff_profiles.name', 'staff_profiles.designation')
            ->get();

        // Department staff (Committee Member 2 can be any staff from the department)
        $departmentStaff = StaffProfile::where(function($q) use ($deptCode) {
                if (!empty($deptCode)) {
                    $q->where('branch', $deptCode);
                }
            })
            ->orderBy('name', 'asc')
            ->get(['mobile_no', 'name', 'designation']);

        if ($departmentStaff->isEmpty()) {
            $departmentStaff = $guides;
        }

        // Active Staff Info
        $activeStaff = StaffProfile::where('mobile_no', $userId)->first();
        $staffProfiles = StaffProfile::all()->keyBy('mobile_no');

        // Dashboard Return URL by Role
        $role = Session::get('userRole');
        $dashboardUrl = '/dashboard/lecturer';
        if ($role === 'HOD') {
            $dashboardUrl = '/dashboard/hod';
        } elseif ($role === 'Principal') {
            $dashboardUrl = '/dashboard/principal';
        } elseif ($role === 'Demonstrator') {
            $dashboardUrl = '/dashboard/demonstrator';
        } elseif ($role === 'Super_Admin') {
            $dashboardUrl = '/dashboard/superadmin';
        } elseif ($role === 'Admin') {
            $dashboardUrl = '/dashboard/admin';
        } elseif ($role === 'Gen_Dept_Coordinator_Aided') {
            $dashboardUrl = '/dashboard/general-coordinator-aided';
        } elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') {
            $dashboardUrl = '/dashboard/general-coordinator-sf';
        } elseif ($role === 'Trade_Instructor') {
            $dashboardUrl = '/dashboard/tradeinstructor';
        } elseif ($role === 'Workshop_Superintendent') {
            $dashboardUrl = '/dashboard/workshop';
        }

        // Process student results
        $studentResults = $students->map(function ($student) use ($seminarRegs, $allEvaluations, $myEvaluations, $labBatches, $subjectAttendance, $overallAttendance, $classLogs, $staffProfiles) {
            $regNo = $student->reg_no;
            $reg = $seminarRegs->get($regNo);
            $myEval = $myEvaluations->get($regNo);
            $stAllEvals = $allEvaluations->where('reg_no', $regNo);
            
            // Only count evaluations where at least one seminar rubric has marks > 0
            $validEvals = $stAllEvals->filter(function ($ev) {
                return ((float)$ev->relevance > 0 || (float)$ev->literature > 0 || (float)$ev->presentation > 0 || (float)$ev->interaction > 0 || (float)$ev->report > 0);
            });
            $evalCount = $validEvals->count();

            // Authoritative Attendance percentage and mark from uploaded TEAMS attendance
            $stAtt = $subjectAttendance->get($regNo);
            if (!$stAtt || $stAtt->isEmpty()) {
                $stAtt = $overallAttendance->get($regNo);
            }

            if ($stAtt && $stAtt->isNotEmpty()) {
                $totalAtt = $stAtt->count();
                $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
                $attPercentage = ($totalAtt > 0) ? round(($present / $totalAtt) * 100, 1) : 0.0;
            } elseif ($classLogs->isNotEmpty()) {
                $totalAtt = $classLogs->count();
                $present = 0;
                foreach ($classLogs as $cl) {
                    $pList = json_decode($cl->present_students, true) ?: [];
                    if (in_array($regNo, $pList)) {
                        $present++;
                    }
                }
                $attPercentage = ($totalAtt > 0) ? round(($present / $totalAtt) * 100, 1) : 0.0;
            } else {
                $attPercentage = 0.0;
            }

            // Rev 2021: Attendance mark directly converted to max marks (Max 7.5M per Clause 11.2.6)
            $savedAttMark = $validEvals->isNotEmpty() ? (float)$validEvals->first()->attendance : null;
            $calculatedAttMark = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
            $attendanceMark = ($savedAttMark !== null && $savedAttMark > 0) ? $savedAttMark : $calculatedAttMark;

            // Averaged rubrics across all assessors for the 5 presentation components (Max 67.5M)
            $avgRelevance = $evalCount > 0 ? round($validEvals->avg('relevance'), 2) : null;
            $avgLiterature = $evalCount > 0 ? round($validEvals->avg('literature'), 2) : null;
            $avgPresentation = $evalCount > 0 ? round($validEvals->avg('presentation'), 2) : null;
            $avgInteraction = $evalCount > 0 ? round($validEvals->avg('interaction'), 2) : null;
            $avgReport = $evalCount > 0 ? round($validEvals->avg('report'), 2) : null;
            $avgAttendance = $attendanceMark;

            // Seminar presentation component (Max 67.5M)
            $seminarScore = ($evalCount > 0)
                ? round(($avgRelevance ?? 0) + ($avgLiterature ?? 0) + ($avgPresentation ?? 0) + ($avgInteraction ?? 0) + ($avgReport ?? 0), 2)
                : null;

            // Final Continuous Internal Assessment (CIA Total 75M - Whole Number) = Seminar (67.5M) + Logged Attendance (7.5M)
            $finalAvgScore = ($evalCount > 0)
                ? min(75, (int)round($seminarScore + $attendanceMark))
                : 0;

            // Batch assignment
            $batchRow = $labBatches->get($regNo);
            $batchAssignment = $batchRow ? (string)$batchRow->lab_batch : 'Unassigned';
            if ($batchAssignment === '1' || $batchAssignment === 'Batch 1') $batchAssignment = '1';
            elseif ($batchAssignment === '2' || $batchAssignment === 'Batch 2') $batchAssignment = '2';
            else $batchAssignment = 'Unassigned';

            // Report submission mark is the decisive final step:
            // A student is evaluated ONLY on the basis of RPRT mark > 0.
            $isCompleted = ($avgReport !== null && (float)$avgReport > 0);

            // SBTE Polytechnic Grading (out of 75 Marks) - strictly based on isCompleted (RPRT > 0)
            $gradeData = self::calculateSbteGrade($finalAvgScore, $isCompleted);

            // Status
            $isScheduled = ($reg && !empty($reg->presentation_date));
            $status = $isCompleted ? 'Completed' : ($isScheduled ? 'Scheduled' : 'Pending');

            $assessorsList = $validEvals->map(function ($ev) use ($staffProfiles, $attendanceMark) {
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
                    'attendance' => (float)$attendanceMark,
                    'total_score' => (int)min(75, round((float)$ev->relevance + (float)$ev->literature + (float)$ev->presentation + (float)$ev->interaction + (float)$ev->report + (float)$attendanceMark)),
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
                'attendance_mark' => $attendanceMark,
                'suggested_att_mark' => $attendanceMark,
                'seminar_score' => $seminarScore,
                'my_evaluation' => ($myEval && ((float)$myEval->relevance > 0 || (float)$myEval->literature > 0 || (float)$myEval->presentation > 0 || (float)$myEval->interaction > 0 || (float)$myEval->report > 0)) ? [
                    'relevance' => (float)$myEval->relevance,
                    'literature' => (float)$myEval->literature,
                    'presentation' => (float)$myEval->presentation,
                    'interaction' => (float)$myEval->interaction,
                    'report' => (float)$myEval->report,
                    'attendance' => (float)$attendanceMark,
                    'total_score' => (float)min(75.0, round((float)$myEval->relevance + (float)$myEval->literature + (float)$myEval->presentation + (float)$myEval->interaction + (float)$myEval->report + (float)$attendanceMark, 2)),
                ] : null,
                'assessors_list' => $assessorsList,
                'eval_count' => $evalCount,
                'avg_relevance' => $avgRelevance,
                'avg_literature' => $avgLiterature,
                'avg_presentation' => $avgPresentation,
                'avg_interaction' => $avgInteraction,
                'avg_report' => $avgReport,
                'avg_attendance' => $attendanceMark,
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
            'fullDepartment',
            'dashboardUrl',
            'courseFile',
            'students',
            'studentResults',
            'guides',
            'assignedFaculty',
            'departmentStaff',
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
        if (\App\Models\ConsolidatedCiaApproval::isLockedForSubject($subjectId)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Consolidated CIA marks for this semester have been approved and locked by the Head of Department. Edits are disabled.'
            ], 403);
        }

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
            'attendance' => 'nullable|numeric|min:0|max:7.5',
        ]);

        $regNo = $request->input('reg_no');
        $relevance = round((float)$request->input('relevance'), 2);
        $literature = round((float)$request->input('literature'), 2);
        $presentation = round((float)$request->input('presentation'), 2);
        $interaction = round((float)$request->input('interaction'), 2);
        $report = round((float)$request->input('report'), 2);

        // Authoritative Attendance from uploaded TEAMS attendance for this student (with override support)
        $attOverride = $request->input('attendance');
        $attPctOverride = $request->input('att_percentage');
        $attPercentage = null;

        if ($attPctOverride !== null && $attPctOverride !== '') {
            $attPercentage = min(100.0, max(0.0, (float)$attPctOverride));
        }

        if ($attOverride !== null && $attOverride !== '') {
            $attendance = min(7.5, max(0.0, round((float)$attOverride, 2)));
            if ($attPercentage === null) {
                $attPercentage = round(($attendance / 7.5) * 100, 1);
            }
        } elseif ($attPercentage !== null) {
            $attendance = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
        } else {
            $officialAttendance = DB::table('student_attendance')
                ->where('reg_no', $regNo)
                ->where('subject_code', $batchSubject->subject_code)
                ->get();

            if ($officialAttendance->isEmpty()) {
                $officialAttendance = DB::table('student_attendance')
                    ->where('reg_no', $regNo)
                    ->get();
            }

            if ($officialAttendance->isNotEmpty()) {
                $offTot = $officialAttendance->count();
                $offPres = $officialAttendance->whereIn('status', ['Present', 'Late'])->count();
                $attPercentage = ($offTot > 0) ? round(($offPres / $offTot) * 100, 1) : 0.0;
            } else {
                $classLogs = DB::table('class_logs_attendance')->where('batch_subject_id', $subjectId)->get();
                if ($classLogs->isNotEmpty()) {
                    $offTot = $classLogs->count();
                    $offPres = 0;
                    foreach ($classLogs as $cl) {
                        $pList = json_decode($cl->present_students, true) ?: [];
                        if (in_array($regNo, $pList)) $offPres++;
                    }
                    $attPercentage = ($offTot > 0) ? round(($offPres / $offTot) * 100, 1) : 0.0;
                } else {
                    $attPercentage = 0.0;
                }
            }

            $attendance = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
        }

        $totalScore = min(75.0, round($relevance + $literature + $presentation + $interaction + $report + $attendance, 2));

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

        // 3. Compute averaged score across all assessors for this student (Round Figure CIA Total out of 75M)
        $studentAllEvals = SeminarEvaluation::where('batch_subject_id', $subjectId)
            ->where('reg_no', $regNo)
            ->get();

        $evalCount = $studentAllEvals->count();
        $avgRelevance = round($studentAllEvals->avg('relevance'), 2);
        $avgLiterature = round($studentAllEvals->avg('literature'), 2);
        $avgPresentation = round($studentAllEvals->avg('presentation'), 2);
        $avgInteraction = round($studentAllEvals->avg('interaction'), 2);
        $avgReport = round($studentAllEvals->avg('report'), 2);
        $seminarComponent = round($avgRelevance + $avgLiterature + $avgPresentation + $avgInteraction + $avgReport, 2);
        $averageScore = min(75, (int)round($seminarComponent + $attendance));

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
            ->where(function($q) use ($batchSubject, $subjectId) {
                $q->where('batch_subject_id', $subjectId)
                  ->orWhere('subject_code', $batchSubject->subject_code);
            })
            ->where('category', 'Seminar')
            ->first();

        if ($existingMark) {
            $existingMark->batch_subject_id = $subjectId;
            $existingMark->marks_obtained = $averageScore;
            $existingMark->max_marks = 75;
            $existingMark->co_tag = 'CO1';
            $existingMark->entered_by = $assessorMobile;
            $existingMark->save();
        } else {
            $newMark = new AcademicMark();
            $newMark->batch_subject_id = $subjectId;
            $newMark->reg_no = $regNo;
            $newMark->subject_code = $batchSubject->subject_code;
            $newMark->category = 'Seminar';
            $newMark->co_tag = 'CO1';
            $newMark->max_marks = 75;
            $newMark->marks_obtained = $averageScore;
            $newMark->entered_by = $assessorMobile;
            $newMark->save();
        }

        // Decisive final step: isCompleted is strictly based on RPRT mark > 0
        $isCompleted = ($avgReport !== null && (float)$avgReport > 0);
        $gradeData = self::calculateSbteGrade($averageScore, $isCompleted);

        // Total completed seminars count for the subject (strictly based on report mark > 0)
        $completedStudentsCount = SeminarEvaluation::where('batch_subject_id', $subjectId)
            ->where('report', '>', 0)
            ->distinct('reg_no')
            ->count('reg_no');

        $staffProfiles = StaffProfile::all()->keyBy('mobile_no');
        $assessorsList = $studentAllEvals->map(function ($ev) use ($staffProfiles, $attendance) {
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
                'attendance' => (float)$attendance,
                'total_score' => (int)min(75, round((float)$ev->relevance + (float)$ev->literature + (float)$ev->presentation + (float)$ev->interaction + (float)$ev->report + (float)$attendance)),
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
                'my_total' => (int)min(75, round($totalScore)),
                'average_score' => $averageScore,
                'seminar_score' => $seminarComponent,
                'avg_relevance' => $avgRelevance,
                'avg_literature' => $avgLiterature,
                'avg_presentation' => $avgPresentation,
                'avg_interaction' => $avgInteraction,
                'avg_report' => $avgReport,
                'attendance_mark' => $attendance,
                'att_percentage' => $attPercentage,
                'eval_count' => $evalCount,
                'is_completed' => $isCompleted,
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
     * Batch Save Seminar Evaluations from Inline Table
     */
    public function saveBatchEvaluations(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Please log in.'], 401);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $evaluations = $request->input('evaluations', []);
        if (!is_array($evaluations) || empty($evaluations)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No evaluations provided.'], 422);
        }

        $savedCount = 0;
        foreach ($evaluations as $item) {
            $regNo = $item['reg_no'] ?? null;
            if (!$regNo) continue;

            $relevance = isset($item['relevance']) ? min(7.5, max(0.0, round((float)$item['relevance'], 2))) : 0.0;
            $literature = isset($item['literature']) ? min(7.5, max(0.0, round((float)$item['literature'], 2))) : 0.0;
            $presentation = isset($item['presentation']) ? min(37.5, max(0.0, round((float)$item['presentation'], 2))) : 0.0;
            $interaction = isset($item['interaction']) ? min(7.5, max(0.0, round((float)$item['interaction'], 2))) : 0.0;
            $report = isset($item['report']) ? min(7.5, max(0.0, round((float)$item['report'], 2))) : 0.0;

            // Only save if at least one seminar rubric mark was actually entered
            if ($relevance == 0 && $literature == 0 && $presentation == 0 && $interaction == 0 && $report == 0) {
                continue;
            }

            // Attendance from TEAMS (or override if provided in row)
            $attPercentage = null;
            if (isset($item['att_percentage']) && $item['att_percentage'] !== null && $item['att_percentage'] !== '') {
                $attPercentage = min(100.0, max(0.0, (float)$item['att_percentage']));
            }

            if (isset($item['attendance']) && $item['attendance'] !== null && $item['attendance'] !== '') {
                $attendance = min(7.5, max(0.0, round((float)$item['attendance'], 2)));
                if ($attPercentage === null) {
                    $attPercentage = round(($attendance / 7.5) * 100, 1);
                }
            } elseif ($attPercentage !== null) {
                $attendance = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
            } else {
                $officialAttendance = DB::table('student_attendance')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->get();

                if ($officialAttendance->isEmpty()) {
                    $officialAttendance = DB::table('student_attendance')
                        ->where('reg_no', $regNo)
                        ->get();
                }

                if ($officialAttendance->isNotEmpty()) {
                    $offTot = $officialAttendance->count();
                    $offPres = $officialAttendance->whereIn('status', ['Present', 'Late'])->count();
                    $attPercentage = ($offTot > 0) ? round(($offPres / $offTot) * 100, 1) : 0.0;
                } else {
                    $classLogs = DB::table('class_logs_attendance')->where('batch_subject_id', $subjectId)->get();
                    if ($classLogs->isNotEmpty()) {
                        $offTot = $classLogs->count();
                        $offPres = 0;
                        foreach ($classLogs as $cl) {
                            $pList = json_decode($cl->present_students, true) ?: [];
                            if (in_array($regNo, $pList)) $offPres++;
                        }
                        $attPercentage = ($offTot > 0) ? round(($offPres / $offTot) * 100, 1) : 0.0;
                    } else {
                        $attPercentage = 0.0;
                    }
                }

                $attendance = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
            }

            $totalScore = (int)min(75, round($relevance + $literature + $presentation + $interaction + $report + $attendance));

            // Assessor
            $assessorMobile = $userId;
            if (!StaffProfile::where('mobile_no', $assessorMobile)->exists()) {
                $fallback = DB::table('subject_staff_assignments')->where('batch_subject_id', $subjectId)->value('staff_mobile_no') ?: StaffProfile::value('mobile_no');
                if ($fallback) $assessorMobile = $fallback;
            }

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

            // Committee average & AcademicMark
            $studentAllEvals = SeminarEvaluation::where('batch_subject_id', $subjectId)->where('reg_no', $regNo)->get();
            $avgRelevance = round($studentAllEvals->avg('relevance'), 2);
            $avgLiterature = round($studentAllEvals->avg('literature'), 2);
            $avgPresentation = round($studentAllEvals->avg('presentation'), 2);
            $avgInteraction = round($studentAllEvals->avg('interaction'), 2);
            $avgReport = round($studentAllEvals->avg('report'), 2);
            $seminarComponent = round($avgRelevance + $avgLiterature + $avgPresentation + $avgInteraction + $avgReport, 2);
            $averageScore = min(75, (int)round($seminarComponent + $attendance));

            AcademicMark::updateOrCreate(
                [
                    'reg_no' => $regNo,
                    'subject_code' => $batchSubject->subject_code,
                    'category' => 'Seminar'
                ],
                [
                    'batch_subject_id' => $subjectId,
                    'co_tag' => 'CO1',
                    'max_marks' => 75,
                    'marks_obtained' => $averageScore,
                    'entered_by' => $assessorMobile
                ]
            );

            $savedCount++;
        }

        $completedStudentsCount = SeminarEvaluation::where('batch_subject_id', $subjectId)
            ->where('report', '>', 0)
            ->distinct('reg_no')
            ->count('reg_no');

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Successfully saved {$savedCount} seminar evaluations.",
            'saved_count' => $savedCount,
            'completed_count' => $completedStudentsCount
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
     * Supports:
     * - 'consolidated': Full 6-Rubric Clause 11.2.6 Evaluation Register
     * - 'internal' / 'cia_submission': Official SBTE Final CIA Mark Entry Statement (75M)
     * - 'ese' / 'grades': End-Semester Exam & Final SBTE Grade Report (75M)
     * - 'schedule': Seminar Presentation Schedule & Guide Allocation Log
     */
    public function printReport(Request $request, $subjectId)
    {
        $rawType = strtolower($request->query('type', 'consolidated'));
        if (in_array($rawType, ['internal', 'cia_submission', 'cia', 'cie'])) {
            $reportType = 'internal';
        } elseif (in_array($rawType, ['ese', 'grades', 'grade', 'final', 'results'])) {
            $reportType = 'ese';
        } elseif (in_array($rawType, ['attendance', 'att', 'attendance_report'])) {
            $reportType = 'attendance';
        } elseif (in_array($rawType, ['topic_splitup', 'topic', 'topics', 'splitup'])) {
            $reportType = 'topic_splitup';
        } elseif ($rawType === 'schedule') {
            $reportType = 'schedule';
        } else {
            $reportType = 'consolidated';
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $allEvaluations = SeminarEvaluation::where('batch_subject_id', $subjectId)->get();
        $seminarRegs = StudentSeminarRegistration::where('batch_subject_id', $subjectId)->with('guide')->get()->keyBy('reg_no');
        $staffProfiles = StaffProfile::all()->keyBy('mobile_no');

        // Authoritative official attendance from student_attendance and class_logs_attendance
        $subjectAttendance = DB::table('student_attendance')
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $overallAttendance = DB::table('student_attendance')
            ->whereIn('reg_no', $students->pluck('reg_no'))
            ->get()
            ->groupBy('reg_no');

        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->get();

        $reportData = $students->map(function ($student) use ($allEvaluations, $seminarRegs, $subjectAttendance, $overallAttendance, $classLogs, $staffProfiles) {
            $regNo = $student->reg_no;
            $reg = $seminarRegs->get($regNo);
            $stAllEvals = $allEvaluations->where('reg_no', $regNo);
            $evalCount = $stAllEvals->count();

            // Authoritative Attendance from TEAMS uploaded attendance
            $stAtt = $subjectAttendance->get($regNo);
            if (!$stAtt || $stAtt->isEmpty()) {
                $stAtt = $overallAttendance->get($regNo);
            }

            if ($stAtt && $stAtt->isNotEmpty()) {
                $totalAtt = $stAtt->count();
                $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
                $attPercentage = ($totalAtt > 0) ? round(($present / $totalAtt) * 100, 1) : 0.0;
            } elseif ($classLogs->isNotEmpty()) {
                $totalAtt = $classLogs->count();
                $present = 0;
                foreach ($classLogs as $cl) {
                    $pList = json_decode($cl->present_students, true) ?: [];
                    if (in_array($regNo, $pList)) $present++;
                }
                $attPercentage = ($totalAtt > 0) ? round(($present / $totalAtt) * 100, 1) : 0.0;
            } else {
                $attPercentage = 0.0;
            }

            // Rev 2021: Statutory Attendance mark (Max 7.5M per Clause 11.2.6)
            $savedAttMark = $stAllEvals->isNotEmpty() ? (float)$stAllEvals->first()->attendance : null;
            $calculatedAttMark = \App\Services\AttainmentService::calculateR21AttendanceMark($attPercentage, 7.5);
            $attendanceComponent = ($savedAttMark !== null && $savedAttMark > 0) ? $savedAttMark : $calculatedAttMark;

            // Averaged Presentation Rubrics across committee faculty
            $avgRelevance = $evalCount > 0 ? round($stAllEvals->avg('relevance'), 2) : 0;
            $avgLiterature = $evalCount > 0 ? round($stAllEvals->avg('literature'), 2) : 0;
            $avgPresentation = $evalCount > 0 ? round($stAllEvals->avg('presentation'), 2) : 0;
            $avgInteraction = $evalCount > 0 ? round($stAllEvals->avg('interaction'), 2) : 0;
            $avgReport = $evalCount > 0 ? round($stAllEvals->avg('report'), 2) : 0;

            // Splitup components: Seminar evaluation (67.5M) + Attendance (7.5M) = 75M
            $seminarComponent = ($evalCount > 0)
                ? round($avgRelevance + $avgLiterature + $avgPresentation + $avgInteraction + $avgReport, 2)
                : 0;

            $finalAvgScore = ($evalCount > 0)
                ? min(75, (int)round($seminarComponent + $attendanceComponent))
                : 0;

            // Report submission mark is the decisive final step:
            // A student is evaluated ONLY on the basis of RPRT mark > 0.
            $isCompleted = ($avgReport !== null && (float)$avgReport > 0);
            $gradeData = self::calculateSbteGrade($finalAvgScore, $isCompleted);

            $assessorsList = $stAllEvals->map(function ($ev) use ($staffProfiles, $attendanceComponent) {
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
                    'attendance' => (float)$attendanceComponent,
                    'total_score' => (int)min(75, round((float)$ev->relevance + (float)$ev->literature + (float)$ev->presentation + (float)$ev->interaction + (float)$ev->report + (float)$attendanceComponent)),
                ];
            })->values();

            return [
                'roll_no' => $student->roll_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $regNo,
                'name' => $student->name,
                'topic' => $reg && !empty($reg->topic) ? $reg->topic : '—',
                'presentation_date' => $reg && $reg->presentation_date ? date('d-m-Y', strtotime($reg->presentation_date)) : '—',
                'guide_name' => $reg && $reg->guide ? $reg->guide->name : '—',
                'att_percentage' => $attPercentage,
                'relevance' => $avgRelevance,
                'literature' => $avgLiterature,
                'presentation' => $avgPresentation,
                'interaction' => $avgInteraction,
                'report' => $avgReport,
                'attendance' => $attendanceComponent,
                'seminar_score' => $seminarComponent,
                'attendance_score' => $attendanceComponent,
                'total_score' => $finalAvgScore,
                'score_in_words' => $isCompleted ? self::numberToWords($finalAvgScore) : '—',
                'letter_grade' => $gradeData['grade'],
                'grade_point' => $gradeData['point'],
                'result' => $gradeData['result'],
                'eval_count' => $evalCount,
                'assessors' => $assessorsList,
                'status' => $isCompleted ? 'Completed' : ($reg && !empty($reg->presentation_date) ? 'Scheduled' : 'Pending'),
                'is_completed' => $isCompleted
            ];
        });

        // Summary Statistics (Count evaluated only if RPRT mark > 0)
        $completedStudents = $reportData->where('is_completed', true);
        $completedCount = $completedStudents->count();
        $passedCount = $completedStudents->where('result', 'Pass')->count();
        $failedCount = $completedStudents->where('result', 'Failed')->count();
        $passRate = $completedCount > 0 ? round(($passedCount / $completedCount) * 100, 1) : 0.0;
        $avgScoreOverall = $completedCount > 0 ? round($completedStudents->avg('total_score'), 2) : 0.0;
        $highestScore = $completedCount > 0 ? round($completedStudents->max('total_score'), 2) : 0.0;
        $lowestScore = $completedCount > 0 ? round($completedStudents->min('total_score'), 2) : 0.0;

        $gradeStats = [
            'S' => $completedStudents->where('letter_grade', 'S')->count(),
            'A' => $completedStudents->where('letter_grade', 'A')->count(),
            'B' => $completedStudents->where('letter_grade', 'B')->count(),
            'C' => $completedStudents->where('letter_grade', 'C')->count(),
            'D' => $completedStudents->where('letter_grade', 'D')->count(),
            'E' => $completedStudents->where('letter_grade', 'E')->count(),
            'F' => $completedStudents->where('letter_grade', 'F')->count(),
        ];

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
            'completedCount' => $completedCount,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'passRate' => $passRate,
            'avgScoreOverall' => $avgScoreOverall,
            'highestScore' => $highestScore,
            'lowestScore' => $lowestScore,
            'gradeStats' => $gradeStats,
            'reportType' => $reportType,
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

    /**
     * Convert numeric marks (0 - 75.0) to words for SBTE Mark Entry Register
     */
    public static function numberToWords($num)
    {
        if ($num === null || $num === '' || !is_numeric($num)) return '—';
        $num = (int)round((float)$num);
        
        $ones = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'
        ];
        $tens = [
            2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
            6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
        ];

        $parts = explode('.', (string)$num);
        $whole = (int)$parts[0];
        $decimal = isset($parts[1]) ? (string)$parts[1] : null;

        $convertBelowHundred = function($n) use ($ones, $tens) {
            if ($n < 20) return $ones[$n];
            $t = (int)($n / 10);
            $rem = $n % 10;
            return $tens[$t] . ($rem > 0 ? ' ' . $ones[$rem] : '');
        };

        $words = '';
        if ($whole < 100) {
            $words = $convertBelowHundred($whole);
        } else {
            $words = (string)$whole;
        }

        if ($decimal !== null && $decimal !== '' && (int)$decimal > 0) {
            $decWords = [];
            foreach (str_split($decimal) as $d) {
                $decWords[] = $ones[(int)$d] ?? $d;
            }
            $words .= ' Point ' . implode(' ', $decWords);
        }

        return $words;
    }

    /**
     * Attainment Summary for Revision 2021 Seminar (Clause 11.2.6)
     * Direct Attainment is 100% CIA-based (Total 75M, 67.5M Academic, Attendance excluded)
     */
    public function getAttainmentSummary($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('ISNULL(roll_no) ASC, CAST(roll_no AS UNSIGNED) ASC, CASE WHEN admission_type = \'LET\' THEN 1 ELSE 0 END ASC, UPPER(name) ASC')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no']);

        $allEvaluations = SeminarEvaluation::where('batch_subject_id', $subjectId)->get();

        $cos = is_array($courseFile->parsed_cos) ? $courseFile->parsed_cos : (json_decode($courseFile->parsed_cos ?? '[]', true) ?: []);
        if (empty($cos)) {
            $cos = [
                ['id' => 'CO1', 'description' => 'Identify contemporary engineering developments and conduct thorough literature review.'],
                ['id' => 'CO2', 'description' => 'Synthesize technical information and deliver effective oral presentation.'],
                ['id' => 'CO3', 'description' => 'Defend methodology, respond to technical queries and prepare standard report.']
            ];
        }

        $settings = is_array($courseFile->attainment_settings) ? $courseFile->attainment_settings : (json_decode($courseFile->attainment_settings ?? '[]', true) ?: []);
        $eseConfig = array_merge(\App\Services\AttainmentService::getDefaultEseConfig('Seminar', 'REV2021'), $settings['ese_config'] ?? []);

        $targetStudentPercent = (float)($eseConfig['target_student_percent'] ?? 70.0);
        $lvl3 = (float)($eseConfig['level3_percent'] ?? $targetStudentPercent);
        $lvl2 = (float)($eseConfig['level2_percent'] ?? max(0, $targetStudentPercent - 10));
        $lvl1 = (float)($eseConfig['level1_percent'] ?? max(0, $targetStudentPercent - 20));

        $exitSurvey = DB::table('course_exit_surveys')->where('batch_subject_id', $subjectId)->first();
        $exitResponses = collect();
        if ($exitSurvey) {
            $exitResponses = DB::table('student_course_exit_responses')->where('exit_survey_id', $exitSurvey->id)->get();
        }

        $matrix = [];
        $directSum = 0;
        $indirectSum = 0;
        $overallSum = 0;

        foreach ($cos as $co) {
            $coTag = $co['id'] ?? 'CO1';
            $totalAssessed = 0;
            $cieMet = 0;

            foreach ($students as $stud) {
                $regNo = $stud->reg_no ?: $stud->sbte_reg_no;
                $stEvals = $allEvaluations->where('reg_no', $regNo);
                if ($stEvals->count() > 0) {
                    $totalAssessed++;
                    // Academic CIA: Relevance (7.5) + Literature (7.5) + Presentation (37.5) + Interaction (7.5) + Report (7.5) = 67.5M Max
                    $relevance = $stEvals->avg('relevance');
                    $literature = $stEvals->avg('literature');
                    $presentation = $stEvals->avg('presentation');
                    $interaction = $stEvals->avg('interaction');
                    $report = $stEvals->avg('report');
                    $academicScore = $relevance + $literature + $presentation + $interaction + $report;

                    if ($academicScore >= (67.5 * 0.50)) { // 50% threshold
                        $cieMet++;
                    }
                }
            }

            $cieMetPct = $totalAssessed > 0 ? round(($cieMet / $totalAssessed) * 100, 1) : 0.0;
            $cieLevel = \App\Services\AttainmentService::calculateBatchLevel($cieMetPct, $lvl3, $lvl2, $lvl1);
            $directAttainment = $cieLevel; // 100% CIE for seminar

            $coSurveyRows = $exitResponses->where('co_tag', $coTag);
            if ($coSurveyRows->count() > 0) {
                $indirectLevel = round((float)$coSurveyRows->avg('rating'), 2);
            } else {
                $indirectLevel = $directAttainment > 0 ? round($directAttainment * 0.9, 2) : 2.5;
            }

            $overallAttainment = round((0.80 * $directAttainment) + (0.20 * $indirectLevel), 2);

            $matrix[] = [
                'co_tag' => $coTag,
                'description' => $co['description'] ?? '',
                'cie_assessed' => $totalAssessed,
                'cie_met_pct' => $cieMetPct,
                'cie_level' => $cieLevel,
                'ese_level' => 0.0,
                'direct_attainment' => $directAttainment,
                'indirect_attainment' => $indirectLevel,
                'overall_attainment' => $overallAttainment
            ];

            $directSum += $directAttainment;
            $indirectSum += $indirectLevel;
            $overallSum += $overallAttainment;
        }

        $numCos = count($matrix);
        $avgDirect = $numCos > 0 ? round($directSum / $numCos, 2) : 0.0;
        $avgIndirect = $numCos > 0 ? round($indirectSum / $numCos, 2) : 0.0;
        $avgOverall = $numCos > 0 ? round($overallSum / $numCos, 2) : 0.0;

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'subject_id' => $batchSubject->id,
                'subject_code' => $batchSubject->subject_code,
                'subject_name' => $batchSubject->subject_name,
                'revision' => 'REV2021',
                'subject_type' => 'Seminar',
                'matrix' => $matrix,
                'average_direct' => $avgDirect,
                'average_indirect' => $avgIndirect,
                'average_overall' => $avgOverall,
                'ese_config' => $eseConfig,
                'survey' => [
                    'id' => $exitSurvey->id ?? null,
                    'status' => $exitSurvey->status ?? 'Not Initiated',
                    'responded_count' => $exitResponses->groupBy('student_reg_no')->count(),
                    'total_students' => count($students),
                    'student_url' => $exitSurvey ? url("/student/course-exit/{$exitSurvey->id}") : null,
                    'report_url' => url("/classroom/{$subjectId}/course-exit/report"),
                    'has_responses' => $exitResponses->count() > 0
                ]
            ]
        ]);
    }
}

