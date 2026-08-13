<?php

namespace App\Http\Controllers;

use App\Models\ClassManagement;
use App\Models\MentoringBatch;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\TutorDiary;
use App\Models\AuditLog;
use App\Models\StudentFamilyDetail;
use App\Models\StudentPriorEducation;
use App\Models\StudentFeeRecord;
use App\Models\ExtracurricularActivity;
use App\Models\LeaveRecord;
use App\Models\DisciplinaryAction;
use App\Models\StudentSemesterSummary;
use App\Models\AcademicMark;
use App\Models\StudentBoardGrade;
use App\Models\BatchSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MentoringController extends Controller
{
    // ─────────────────────────────────────────────
    //  HELPER: resolve which classrooms the actor mentors
    // ─────────────────────────────────────────────
    private function getMentorClassrooms(): array
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return [];

        // As Tutor (Mentor-1) for classrooms (R21 and R26)
        $asTutor21 = ClassManagement::where('tutor_mobile_no', $mobileNo)->pluck('classroom_id')->toArray();
        $asTutor26 = DB::table('r26_class_management')->where('tutor_mobile_no', $mobileNo)->pluck('classroom_id')->toArray();
        $asTutor   = array_merge($asTutor21, $asTutor26);

        // As Mentor-2 for classrooms (R21 and R26)
        $asMentor21 = ClassManagement::where('mentor_mobile_no', $mobileNo)->pluck('classroom_id')->toArray();
        $asMentor26 = DB::table('r26_class_management')->where('mentor_mobile_no', $mobileNo)->pluck('classroom_id')->toArray();
        $asMentor2  = array_merge($asMentor21, $asMentor26);

        return array_unique(array_merge($asTutor, $asMentor2));
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/my-batches
    //  Returns batches and students assigned to the current mentor
    // ─────────────────────────────────────────────
    public function getMyBatches()
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classrooms = $this->getMentorClassrooms();

            $result = [];
            foreach ($classrooms as $classroomId) {
                $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
                if (!$classroom) continue;

                // Determine role in this classroom
                $isTutor    = ($classroom->tutor_mobile_no === $mobileNo);
                $isMentor2  = ($classroom->mentor_mobile_no === $mobileNo);
                $batchLabel = $isTutor ? 'A' : 'B';

                // Students assigned to this mentor in mentoring_batches
                $assigned = MentoringBatch::where('classroom_id', $classroomId)
                    ->where('mentor_no', $mobileNo)
                    ->with('student')
                    ->get()
                    ->map(fn($b) => [
                        'reg_no'   => $b->reg_no,
                        'name'     => $b->student->name ?? 'Unknown',
                        'branch'   => $b->student->branch ?? '',
                        'status'   => $b->student->status ?? '',
                        'batch'    => $b->batch_label,
                        'photo'    => $b->student->photo_url ?? null,
                    ]);

                // Unassigned students in this classroom (no batch yet)
                $allStudents = Student::where('classroom_id', $classroomId)->pluck('reg_no')->toArray();
                $assignedIds = MentoringBatch::where('classroom_id', $classroomId)->pluck('reg_no')->toArray();
                $unassignedIds = array_diff($allStudents, $assignedIds);

                // Get partner mentor name
                $partnerName = null;
                if ($isTutor && $classroom->mentor_mobile_no) {
                    $p = StaffProfile::where('mobile_no', $classroom->mentor_mobile_no)->first();
                    $partnerName = $p?->name;
                } elseif ($isMentor2) {
                    $p = StaffProfile::where('mobile_no', $classroom->tutor_mobile_no)->first();
                    $partnerName = $p?->name . ' (Tutor)';
                }

                $result[] = [
                    'classroom_id'   => $classroomId,
                    'branch'         => $classroom->branch,
                    'batch_year'     => $classroom->batch_year,
                    'current_semester' => $classroom->current_semester,
                    'my_role'        => $isTutor ? 'Mentor-1 (Tutor)' : 'Mentor-2',
                    'my_batch'       => $batchLabel,
                    'partner_name'   => $partnerName,
                    'mentor1_mobile' => $classroom->tutor_mobile_no,
                    'mentor2_mobile' => $classroom->mentor_mobile_no,
                    'my_students'    => $assigned,
                    'unassigned_count' => count($unassignedIds),
                    'total_students'   => count($allStudents),
                ];
            }

            return response()->json(['status' => 'SUCCESS', 'batches' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/students/{classroom_id}
    //  Full roster with batch assignments
    // ─────────────────────────────────────────────
    public function getClassroomStudents(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // Authorise: must be tutor or mentor2 of this class
            $allowed = in_array($mobileNo, [$classroom->tutor_mobile_no, $classroom->mentor_mobile_no])
                    || in_array(Session::get('userRole'), ['Super_Admin', 'Principal', 'Admin', 'HOD']);
            if (!$allowed) return response()->json(['status' => 'ERROR', 'message' => 'Not authorised for this classroom.']);

            $students = Student::where('classroom_id', $classroomId)->get();
            $batches  = MentoringBatch::where('classroom_id', $classroomId)->get()->keyBy('reg_no');

            $data = $students->map(function ($s) use ($batches) {
                $batch = $batches->get($s->reg_no);
                return [
                    'reg_no'       => $s->reg_no,
                    'name'         => $s->name,
                    'branch'       => $s->branch,
                    'status'       => $s->status,
                    'photo'        => $s->photo_url,
                    'batch_label'  => $batch?->batch_label ?? null,
                    'mentor_no'    => $batch?->mentor_no ?? null,
                ];
            });

            return response()->json([
                'status'    => 'SUCCESS',
                'classroom' => [
                    'id'       => $classroomId,
                    'branch'   => $classroom->branch,
                    'year'     => $classroom->batch_year,
                    'tutor'    => $classroom->tutor_mobile_no,
                    'mentor2'  => $classroom->mentor_mobile_no,
                ],
                'students'  => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/assign-batch
    //  Tutor assigns a student to Batch A or B
    //  Body: { classroom_id, reg_no, batch_label ('A'|'B') }
    // ─────────────────────────────────────────────
    public function assignBatch(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'classroom_id' => 'required|string',
            'reg_no'       => 'required|string',
            'batch_label'  => 'required|in:A,B',
        ]);

        try {
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // Only the Tutor (Mentor-1) can split batches
            if ($classroom->tutor_mobile_no !== $mobileNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Only the Class Tutor can assign mentoring batches.']);
            }

            $mentorNo = ($request->batch_label === 'A')
                ? $classroom->tutor_mobile_no
                : $classroom->mentor_mobile_no;

            if (!$mentorNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Mentor-2 has not been assigned to this class yet. Ask your HOD to assign one first.']);
            }

            // Upsert the batch assignment
            MentoringBatch::updateOrCreate(
                ['classroom_id' => $request->classroom_id, 'reg_no' => strtoupper($request->reg_no)],
                ['mentor_no'    => $mentorNo, 'batch_label' => $request->batch_label, 'assigned_by' => $mobileNo]
            );

            // Update student's mentor_mobile_no field too
            Student::where('reg_no', strtoupper($request->reg_no))->update(['mentor_mobile_no' => $mentorNo]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Student assigned to Batch ' . $request->batch_label . '.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/assign-mentor2
    //  HOD assigns a Mentor-2 to a classroom
    //  Body: { classroom_id, mentor_mobile_no }
    // ─────────────────────────────────────────────
    public function assignMentor2(Request $request)
    {
        $role     = Session::get('userRole');
        $branch   = Session::get('userBranch');
        $mobileNo = Session::get('userId');

        if (!in_array($role, ['HOD', 'Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Only HOD or Admin can assign Mentor-2.']);
        }

        $request->validate([
            'classroom_id'     => 'required|string',
            'mentor_mobile_no' => 'required|string',
        ]);

        try {
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // HOD can only assign for their branch
            if ($role === 'HOD' && $classroom->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'You can only assign mentors for classrooms in your branch.']);
            }

            $mentor = StaffProfile::where('mobile_no', $request->mentor_mobile_no)->first();
            if (!$mentor) return response()->json(['status' => 'ERROR', 'message' => 'Staff member not found.']);

            $oldMentor = $classroom->mentor_mobile_no;
            $classroom->update(['mentor_mobile_no' => $request->mentor_mobile_no]);

            // Audit
            AuditLog::create([
                'performed_by'      => $mobileNo,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $request->classroom_id,
                'target_name'       => 'Classroom ' . $request->classroom_id,
                'action'            => 'Mentor-2 Assigned',
                'details'           => "Assigned {$mentor->name} ({$mentor->mobile_no}) as Mentor-2. Previous: " . ($oldMentor ?? 'None'),
                'ip_address'        => request()->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => "{$mentor->name} assigned as Mentor-2 for {$request->classroom_id}."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/diary/{reg_no}
    //  Diary entries for a student (staff view)
    // ─────────────────────────────────────────────
    public function getStudentDiary(string $regNo)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $entries = TutorDiary::where('reg_no', strtoupper($regNo))
                ->orderByDesc('date')
                ->get()
                ->map(function ($e) {
                    $loggedBy = $e->logged_by
                        ? (StaffProfile::where('mobile_no', $e->logged_by)->value('name') ?? $e->logged_by)
                        : 'System';
                    $approvedBy = $e->approved_by
                        ? (StaffProfile::where('mobile_no', $e->approved_by)->value('name') ?? $e->approved_by)
                        : null;
                    return [
                        'diary_id'        => $e->diary_id,
                        'date'            => $e->date,
                        'category'        => $e->category,
                        'discussion_notes'=> $e->discussion_notes,
                        'action_taken'    => $e->action_taken,
                        'remarks'         => $e->remarks,
                        'student_remarks' => $e->student_remarks,
                        'entry_source'    => $e->entry_source,
                        'approval_status' => $e->approval_status,
                        'logged_by_name'  => $loggedBy,
                        'approved_by_name'=> $approvedBy,
                        'created_at'      => $e->created_at,
                    ];
                });

            $student = Student::where('reg_no', strtoupper($regNo))->first();

            return response()->json([
                'status'  => 'SUCCESS',
                'student' => $student ? ['name' => $student->name, 'branch' => $student->branch] : null,
                'entries' => $entries,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/full-diary/{reg_no}
    //  Fetch all 7 sections of the mentoring diary
    // ─────────────────────────────────────────────
    
    public function saveExtendedProfile(Request $request)
    {
        $regNo = Session::get('userId');
        if (Session::get('userRole') !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated as student.'], 403);
        }

        $validated = $request->validate([
            'gender' => 'nullable|string',
            'caste' => 'nullable|string',
            'religion' => 'nullable|string',
            'special_category' => 'nullable|string',
            'reservation' => 'nullable|string',
            'quota' => 'nullable|string',
            'is_physically_disabled' => 'nullable|boolean',
            'disability_category' => 'nullable|string',
            'guardian_occupation' => 'nullable|string',
            'monthly_family_income' => 'nullable|string',
            'has_vehicle_pass' => 'nullable|boolean',
            'vehicle_pass_id' => 'nullable|string',
            'communication_address' => 'nullable|string',
        ]);

        try {
            $profile = \App\Models\StudentMentoringProfile::updateOrCreate(
                ['reg_no' => strtoupper($regNo)],
                $validated
            );

            return response()->json(['status' => 'SUCCESS', 'message' => 'Profile updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function getFullStudentDiary(string $regNo)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $student = Student::where('reg_no', strtoupper($regNo))->first();
            if (!$student) return response()->json(['status' => 'ERROR', 'message' => 'Student not found.']);
            
            $extended_profile = \App\Models\StudentMentoringProfile::where('reg_no', $student->reg_no)->first();

            // 1 & 2. Personal & Profile Info
            $family = StudentFamilyDetail::where('reg_no', $student->reg_no)->get();
            $education = StudentPriorEducation::where('reg_no', $student->reg_no)->get();
            $fees = StudentFeeRecord::where('reg_no', $student->reg_no)->get();

            // 4. Extracurricular
            $extracurricular = \App\Models\ActivityPointClaim::where('reg_no', $student->reg_no)->get();

            // 5. Meeting Logs (Diary)
            $meetings = TutorDiary::where('reg_no', $student->reg_no)
                ->orderByDesc('date')
                ->get()
                ->map(function ($e) {
                    $loggedBy = $e->logged_by ? (StaffProfile::where('mobile_no', $e->logged_by)->value('name') ?? $e->logged_by) : 'System';
                    return [
                        'diary_id' => $e->diary_id,
                        'date' => $e->date,
                        'category' => $e->category,
                        'discussion_notes' => $e->discussion_notes,
                        'action_taken' => $e->action_taken,
                        'approval_status' => $e->approval_status,
                        'logged_by_name' => $loggedBy,
                    ];
                });

            // 6. Leaves
            $leaves = LeaveRecord::where('reg_no', $student->reg_no)->orderByDesc('leave_date')->get();

            // 7. Disciplinary
            $disciplinary = DisciplinaryAction::where('reg_no', $student->reg_no)->orderByDesc('date')->get();

            // 8. Board Exams
            $board = StudentSemesterSummary::where('reg_no', $student->reg_no)->orderBy('semester', 'desc')->get();

            // 9. Academics (Structured by Semester and Subject)
            $batchSubjects = BatchSubject::where('classroom_id', $student->classroom_id)->get();
            $academicMarks = AcademicMark::where('reg_no', $student->reg_no)->get();
            $boardGrades = StudentBoardGrade::where('reg_no', $student->reg_no)->get();
            
            $academics = [];
            foreach ($batchSubjects as $subject) {
                $sem = $subject->semester;
                $code = $subject->subject_code;
                
                if (!isset($academics[$sem])) {
                    $academics[$sem] = [];
                }
                
                // Find marks for this subject
                $subjectMarks = $academicMarks->where('subject_code', $code);
                
                // Find board grade for this subject
                $bGrade = $boardGrades->where('semester', $sem)->where('subject_code', $code)->first();
                
                // Helper to extract mark
                $getMark = function($cat, $co) use ($subjectMarks) {
                    $m = $subjectMarks->where('category', $cat)->where('co_tag', $co)->first();
                    return $m ? $m->marks_obtained : '--';
                };

                $academics[$sem][] = [
                    'subject_code' => $code,
                    'subject_name' => $subject->subject_name,
                    'type' => $subject->subject_type,
                    'tests' => [
                        'CO1' => $getMark('Written Test', 'CO1'),
                        'CO2' => $getMark('Written Test', 'CO2'),
                        'CO3' => $getMark('Written Test', 'CO3'),
                        'CO4' => $getMark('Written Test', 'CO4'),
                    ],
                    'assignments' => [
                        'CO1' => $getMark('Assignment', 'CO1'),
                        'CO2' => $getMark('Assignment', 'CO2'),
                        'CO3' => $getMark('Assignment', 'CO3'),
                        'CO4' => $getMark('Assignment', 'CO4'),
                    ],
                    'mcq' => [
                        'CO1' => $getMark('Online Test', 'CO1'),
                        'CO2' => $getMark('Online Test', 'CO2'),
                        'CO3' => $getMark('Online Test', 'CO3'),
                        'CO4' => $getMark('Online Test', 'CO4'),
                    ],
                    'internal_mark' => '--', // Database generated later
                    'attendance' => '--', // Will be added later
                    'board_result' => $bGrade ? [
                        'grade' => $bGrade->grade,
                        'internal_marks' => $bGrade->internal_marks,
                        'external_marks' => $bGrade->external_marks,
                        'total_marks' => $bGrade->total_marks,
                        'exam_month_year' => $bGrade->exam_month_year,
                        'passed' => $bGrade->passed,
                        'chances_taken' => $bGrade->chances_taken
                    ] : null
                ];
            }

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'student'         => $student,
                    'extended_profile'=> $extended_profile,
                    'profile'         => $student,
                    'family'          => $family,
                    'education'       => $education,
                    'fees'            => $fees,
                    'extracurricular' => $extracurricular,
                    'meetings'        => $meetings,
                    'leaves'          => $leaves ?? [],
                    'disciplinary'    => $disciplinary ?? [],
                    'board'           => $board,
                    'academics'       => (krsort($academics) ? $academics : $academics),
                    'syllabus_list'   => [],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/diary/add
    //  Staff adds a diary entry for a student
    //  Body: { reg_no, date, category, discussion_notes, action_taken, remarks }
    // ─────────────────────────────────────────────
    public function addDiaryEntry(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'reg_no'            => 'required|string',
            'date'              => 'required|date',
            'category'          => 'required|string|max:100',
            'discussion_notes'  => 'required|string',
        ]);

        try {
            TutorDiary::create([
                'reg_no'            => strtoupper($request->reg_no),
                'date'              => $request->date,
                'category'          => $request->category,
                'discussion_notes'  => $request->discussion_notes,
                'action_taken'      => $request->action_taken,
                'remarks'           => $request->remarks,
                'logged_by'         => $mobileNo,
                'entry_source'      => 'Staff',
                'approval_status'   => 'Approved', // Staff entries auto-approved
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Diary entry saved.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/diary/approve
    //  Mentor approves or rejects a student self-entry
    //  Body: { diary_id, decision ('Approved'|'Rejected') }
    // ─────────────────────────────────────────────
    public function approveDiaryEntry(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'diary_id' => 'required|string',
            'decision' => 'required|in:Approved,Rejected',
        ]);

        try {
            $entry = TutorDiary::where('diary_id', $request->diary_id)->first();
            if (!$entry) return response()->json(['status' => 'ERROR', 'message' => 'Entry not found.']);

            $entry->update([
                'approval_status' => $request->decision,
                'approved_by'     => $mobileNo,
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => "Entry {$request->decision}."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    //  GET /api/mentoring/backlog-report/{classroom_id}
    //  Generate backlog report for a classroom
    // ────────────────────────────────────────────────────────────────────────
    public function getBacklogReport(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $students = Student::where('classroom_id', $classroomId)->get();
            $boardGrades = StudentBoardGrade::whereIn('reg_no', $students->pluck('reg_no'))->get();
            
            $noBacklogs = [];
            $withBacklogs = [];

            foreach ($students as $student) {
                // Get all board grades for this student
                $studentGrades = $boardGrades->where('reg_no', $student->reg_no);
                
                // Count how many subjects have passed == 0
                // Wait, if no record exists for a subject, it's pending/not written yet. 
                // Backlogs are explicitly those that are failed (passed == 0)
                $backlogs = $studentGrades->where('passed', 0)->count();

                $studentData = [
                    'reg_no' => $student->reg_no,
                    'name' => $student->name,
                    'backlog_count' => $backlogs
                ];

                if ($backlogs > 0) {
                    $withBacklogs[] = $studentData;
                } else {
                    $noBacklogs[] = $studentData;
                }
            }

            // Sort arrays
            usort($noBacklogs, fn($a, $b) => strcmp($a['name'], $b['name']));
            usort($withBacklogs, fn($a, $b) => strcmp($a['name'], $b['name']));

            return response()->json([
                'status' => 'SUCCESS',
                'no_backlogs' => $noBacklogs,
                'with_backlogs' => $withBacklogs
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/report/{classroom_id}
    //  Full mentoring report — both batches, both mentor names
    // ─────────────────────────────────────────────
    public function getMentoringReport(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            $mentor1 = StaffProfile::where('mobile_no', $classroom->tutor_mobile_no)->first();
            $mentor2 = StaffProfile::where('mobile_no', $classroom->mentor_mobile_no)->first();

            $students = Student::where('classroom_id', $classroomId)->get();
            $batches  = MentoringBatch::where('classroom_id', $classroomId)->get()->keyBy('reg_no');

            $batchA = []; $batchB = []; $unassigned = [];

            foreach ($students as $s) {
                $batch = $batches->get($s->reg_no);
                $diaryCount = TutorDiary::where('reg_no', $s->reg_no)->count();
                $row = [
                    'reg_no'       => $s->reg_no,
                    'name'         => $s->name,
                    'status'       => $s->status,
                    'diary_count'  => $diaryCount,
                    'batch_label'  => $batch?->batch_label,
                ];
                if (!$batch) { $unassigned[] = $row; }
                elseif ($batch->batch_label === 'A') { $batchA[] = $row; }
                else { $batchB[] = $row; }
            }

            return response()->json([
                'status'    => 'SUCCESS',
                'classroom' => [
                    'id'         => $classroomId,
                    'branch'     => $classroom->branch,
                    'batch_year' => $classroom->batch_year,
                ],
                'mentor1'    => ['mobile' => $classroom->tutor_mobile_no,   'name' => $mentor1?->name ?? 'Not Assigned', 'designation' => $mentor1?->designation ?? ''],
                'mentor2'    => ['mobile' => $classroom->mentor_mobile_no,  'name' => $mentor2?->name ?? 'Not Assigned', 'designation' => $mentor2?->designation ?? ''],
                'batch_a'    => $batchA,
                'batch_b'    => $batchB,
                'unassigned' => $unassigned,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/student/mentoring/self-entry
    //  Student adds a self-reflection entry (Pending approval)
    // ─────────────────────────────────────────────
    public function studentSelfEntry(Request $request)
    {
        $regNo = Session::get('userId');
        $role  = Session::get('userRole');
        if ($role !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Only students can submit self-entries.'], 403);
        }

        $request->validate([
            'category'        => 'required|string|max:100',
            'student_remarks' => 'required|string',
        ]);

        try {
            TutorDiary::create([
                'reg_no'           => $regNo,
                'date'             => now()->toDateString(),
                'category'         => $request->category,
                'discussion_notes' => '(Student Self Entry)',
                'student_remarks'  => $request->student_remarks,
                'logged_by'        => null,
                'entry_source'     => 'Student',
                'approval_status'  => 'Pending',
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Your entry has been submitted and is pending mentor approval.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/student/mentoring/diary
    //  Student views their own diary
    // ─────────────────────────────────────────────
        

    public function studentSaveExtraCurricular(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');
        
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $regNo = $request->input('reg_no');
        
        if ($role === 'Student') {
            $regNo = $userId;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        $request->validate([
            'semester'         => 'required|integer',
            'segment'          => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer'
        ]);

        $data = [
            'reg_no'           => strtoupper($regNo),
            'semester'         => $request->semester,
            'activity_segment' => $request->segment,
            'activity_name'    => $request->activity_name,
            'level'            => $request->level,
            'points_claimed'   => $request->points_claimed,
            'status'           => 'Pending',
        ];

        if ($request->has('activity_id') && !empty($request->activity_id)) {
            \App\Models\ActivityPointClaim::where('id', $request->activity_id)
                ->where('reg_no', strtoupper($regNo))
                ->update($data);
            $msg = 'Activity updated successfully.';
        } else {
            \App\Models\ActivityPointClaim::create($data);
            $msg = 'Activity submitted for verification.';
        }

        return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
    }

    public function studentViewDiary(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');
        
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $regNo = $request->input('reg_no');
        
        if ($role === 'Student') {
            $regNo = $userId;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        try {
            // Call the shared full diary fetching logic
            // Since this is the same logic a mentor uses to view a student's diary, we just reuse it.
            // But we must fake the session briefly or extract the logic.
            // Since getFullStudentDiary checks for mentor auth, we'll extract the logic.

            $student = Student::where('reg_no', strtoupper($regNo))->first();
            if (!$student) return response()->json(['status' => 'ERROR', 'message' => 'Student not found.']);

            $extended_profile = \App\Models\StudentMentoringProfile::where('reg_no', $student->reg_no)->first();
            $family = StudentFamilyDetail::where('reg_no', $student->reg_no)->get();
            $education = StudentPriorEducation::where('reg_no', $student->reg_no)->get();
            $fees = StudentFeeRecord::where('reg_no', $student->reg_no)->get();
            $extracurricular = \App\Models\ActivityPointClaim::where('reg_no', $student->reg_no)->get();
            $leaves = LeaveRecord::where('reg_no', $student->reg_no)->orderByDesc('leave_date')->get();
            $disciplinary = DisciplinaryAction::where('reg_no', $student->reg_no)->orderByDesc('date')->get();
            $meetings = TutorDiary::where('reg_no', $student->reg_no)->orderByDesc('date')->get()
                ->map(function ($e) {
                    $loggedBy = $e->logged_by
                        ? (\App\Models\StaffProfile::where('mobile_no', $e->logged_by)->value('name') ?? $e->logged_by)
                        : 'System';
                    return [
                        'diary_id'         => $e->diary_id,
                        'date'             => $e->date,
                        'category'         => $e->category,
                        'discussion_notes' => $e->discussion_notes,
                        'action_taken'     => $e->action_taken,
                        'approval_status'  => $e->approval_status,
                        'logged_by_name'   => $loggedBy,
                    ];
                });
            $board = StudentSemesterSummary::where('reg_no', $student->reg_no)->orderBy('semester', 'desc')->get();

            // 9. Academics (Structured by Semester and Subject)
            $batchSubjects = BatchSubject::where('classroom_id', $student->classroom_id)->get();
            $academicMarks = AcademicMark::where('reg_no', $student->reg_no)->get();
            $boardGrades = StudentBoardGrade::where('reg_no', $student->reg_no)->get();
            
            $academics = [];
            foreach ($batchSubjects as $subject) {
                $sem = $subject->semester;
                $code = $subject->subject_code;
                
                if (!isset($academics[$sem])) {
                    $academics[$sem] = [];
                }
                
                // Find marks for this subject
                $subjectMarks = $academicMarks->where('subject_code', $code);
                
                // Find board grade for this subject
                $bGrade = $boardGrades->where('semester', $sem)->where('subject_code', $code)->first();
                
                // Helper to extract mark
                $getMark = function($cat, $co) use ($subjectMarks) {
                    $m = $subjectMarks->where('category', $cat)->where('co_tag', $co)->first();
                    return $m ? $m->marks_obtained : '--';
                };

                $academics[$sem][] = [
                    'subject_code' => $code,
                    'subject_name' => $subject->subject_name,
                    'type' => $subject->subject_type,
                    'tests' => [
                        'CO1' => $getMark('Written Test', 'CO1'),
                        'CO2' => $getMark('Written Test', 'CO2'),
                        'CO3' => $getMark('Written Test', 'CO3'),
                        'CO4' => $getMark('Written Test', 'CO4'),
                    ],
                    'assignments' => [
                        'CO1' => $getMark('Assignment', 'CO1'),
                        'CO2' => $getMark('Assignment', 'CO2'),
                        'CO3' => $getMark('Assignment', 'CO3'),
                        'CO4' => $getMark('Assignment', 'CO4'),
                    ],
                    'mcq' => [
                        'CO1' => $getMark('Online Test', 'CO1'),
                        'CO2' => $getMark('Online Test', 'CO2'),
                        'CO3' => $getMark('Online Test', 'CO3'),
                        'CO4' => $getMark('Online Test', 'CO4'),
                    ],
                    'internal_mark' => '--',
                    'attendance' => '--',
                    'board_result' => $bGrade ? [
                        'grade' => $bGrade->grade,
                        'internal_marks' => $bGrade->internal_marks,
                        'external_marks' => $bGrade->external_marks,
                        'total_marks' => $bGrade->total_marks,
                        'exam_month_year' => $bGrade->exam_month_year,
                        'passed' => $bGrade->passed,
                        'chances_taken' => $bGrade->chances_taken
                    ] : null
                ];
            }

            // Fetch all board grades for this student and map subject names
            $allBoardGrades = $boardGrades->map(function($bg) {
                $syllabus = \DB::table('syllabus_registry')->where('subject_code', $bg->subject_code)->first();
                $bg->subject_name = $syllabus ? $syllabus->subject_name : 'Unknown Subject';
                return $bg;
            });

            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'student' => [
                        'name'         => $student->name,
                        'reg_no'       => $student->reg_no,
                        'branch'       => $student->branch,
                        'classroom_id' => $student->classroom_id,
                        'photo_url'    => $student->photo_url,
                    ],
                    'profile' => [
                        'name' => $student->name,
                        'reg_no' => $student->reg_no,
                        'annual_income' => $student->annual_income,
                        'residential_status' => $student->residential_status,
                        'scholarships' => $student->scholarships,
                        'is_fee_waiver' => $student->is_fee_waiver,
                        'guardian_name' => $student->guardian_name,
                        'guardian_relationship' => $student->guardian_relationship,
                        'guardian_mobile' => $student->guardian_mobile,
                        'guardian_address' => $student->guardian_address,
                        'profile_verified_at' => $student->profile_verified_at,
                    ],
                    'extended_profile' => $extended_profile,
                    'family'          => $family,
                    'education'       => $education,
                    'fees'            => $fees,
                    'extracurricular' => $extracurricular,
                    'leaves'          => $leaves,
                    'disciplinary'    => $disciplinary,
                    'meetings'        => $meetings,
                    'board'           => $board,
                    'all_board_grades' => $allBoardGrades,
                    'academics'       => $academics
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function saveStudentMentoringData(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');
        
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $regNo = $request->input('reg_no');
        
        if ($role === 'Student') {
            $regNo = $userId;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        DB::beginTransaction();
        try {
            $student = Student::where('reg_no', strtoupper($regNo))->first();

            // 1. Update Profile Fields
            if ($request->has('profile')) {
                $p = $request->profile;
                $student->update([
                    'annual_income' => $p['annual_income'] ?? $student->annual_income,
                    'residential_status' => $p['residential_status'] ?? $student->residential_status,
                    'scholarships' => $p['scholarships'] ?? $student->scholarships,
                    'is_fee_waiver' => $p['is_fee_waiver'] ?? $student->is_fee_waiver,
                    'guardian_name' => $p['guardian_name'] ?? $student->guardian_name,
                    'guardian_relationship' => $p['guardian_relationship'] ?? $student->guardian_relationship,
                    'guardian_mobile' => $p['guardian_mobile'] ?? $student->guardian_mobile,
                    'guardian_address' => $p['guardian_address'] ?? $student->guardian_address,
                ]);
            }

            // Update/Create Extended Profile Fields
            if ($request->has('extended_profile')) {
                $ep = $request->extended_profile;
                \App\Models\StudentMentoringProfile::updateOrCreate(
                    ['reg_no' => strtoupper($regNo)],
                    [
                        'gender' => $ep['gender'] ?? null,
                        'religion' => $ep['religion'] ?? null,
                        'caste' => $ep['caste'] ?? null,
                        'special_category' => $ep['special_category'] ?? null,
                        'reservation' => $ep['reservation'] ?? null,
                        'quota' => $ep['quota'] ?? null,
                        'is_physically_disabled' => isset($ep['is_physically_disabled']) ? (int)$ep['is_physically_disabled'] : 0,
                        'disability_category' => $ep['disability_category'] ?? null,
                        'guardian_occupation' => $ep['guardian_occupation'] ?? null,
                        'monthly_family_income' => $ep['monthly_family_income'] ?? null,
                        'has_vehicle_pass' => isset($ep['has_vehicle_pass']) ? (int)$ep['has_vehicle_pass'] : 0,
                        'vehicle_pass_id' => $ep['vehicle_pass_id'] ?? null,
                        'communication_address' => $ep['communication_address'] ?? null,
                    ]
                );
            }

            // 2. Update Family Details
            if ($request->has('family')) {
                StudentFamilyDetail::where('reg_no', $regNo)->delete();
                foreach ($request->family as $f) {
                    StudentFamilyDetail::create([
                        'reg_no' => $regNo,
                        'name' => $f['name'],
                        'relationship' => $f['relationship'] ?? '',
                        'education' => $f['education'] ?? '',
                        'occupation' => $f['occupation'] ?? '',
                        'contact_no' => $f['contact_no'] ?? ''
                    ]);
                }
            }

            // 3. Update Prior Education
            if ($request->has('education')) {
                StudentPriorEducation::where('reg_no', $regNo)->delete();
                foreach ($request->education as $e) {
                    StudentPriorEducation::create([
                        'reg_no' => $regNo,
                        'course' => $e['course'],
                        'institution' => $e['institution'] ?? '',
                        'year_of_completion' => $e['year_of_completion'] ?? '',
                        'total_percentage' => $e['total_percentage'] ?? ''
                    ]);
                }
            }

            // Extracurricular is now handled via separate endpoints (ActivityPointClaim)

            // 5. Update Board Exams (Semester Summary)
            if ($request->has('board')) {
                StudentSemesterSummary::where('reg_no', $regNo)->delete();
                foreach ($request->board as $b) {
                    StudentSemesterSummary::create([
                        'reg_no' => $regNo,
                        'semester' => $b['semester'],
                        'sgpa' => $b['sgpa'] ?? null,
                        'cgpa' => $b['cgpa'] ?? null,
                        'activity_points' => $b['activity_points'] ?? 0
                    ]);
                }
            }

            // 6. Update Subject-wise Board Grades
            if ($request->has('board_grades')) {
                StudentBoardGrade::where('reg_no', $regNo)->delete();
                foreach ($request->board_grades as $bg) {
                    StudentBoardGrade::create([
                        'reg_no' => $regNo,
                        'semester' => $bg['semester'],
                        'subject_code' => $bg['subject_code'],
                        'grade' => $bg['grade'] ?? null,
                        'internal_marks' => $bg['internal_marks'] ?? null,
                        'external_marks' => $bg['external_marks'] ?? null,
                        'total_marks' => $bg['total_marks'] ?? null,
                        'exam_month_year' => $bg['exam_month_year'] ?? null,
                        'passed' => $bg['passed'] ?? 1,
                        'chances_taken' => $bg['chances_taken'] ?? 1,
                    ]);
                }

                // Recalculate SGPA and CGPA dynamically based on board grades
                $allGrades = StudentBoardGrade::where('reg_no', $regNo)->get();
                $batchSubjects = BatchSubject::get();
                $subjTypeMap = $batchSubjects->pluck('subject_type', 'subject_code')->toArray();

                $getGP = function($grade) {
                    switch (strtoupper(trim($grade))) {
                        case 'S': return 10;
                        case 'A': return 9;
                        case 'B': return 8;
                        case 'C': return 7;
                        case 'D': return 6;
                        case 'E': return 5;
                        case 'F': return 0;
                        default: return null;
                    }
                };

                $getCredit = function($code) use ($subjTypeMap) {
                    $type = $subjTypeMap[$code] ?? 'Theory';
                    if (stripos($type, 'practical') !== false || stripos($type, 'lab') !== false) {
                        return 2;
                    }
                    return 4;
                };

                $semestersList = $allGrades->pluck('semester')->unique()->sort()->toArray();
                $cumTotalGP = 0;
                $cumTotalCredits = 0;

                foreach ($semestersList as $sem) {
                    $semGrades = $allGrades->where('semester', $sem);
                    $semTotalGP = 0;
                    $semTotalCredits = 0;

                    foreach ($semGrades as $g) {
                        $gp = $getGP($g->grade);
                        if ($gp !== null) {
                            $credit = $getCredit($g->subject_code);
                            $semTotalGP += ($gp * $credit);
                            $semTotalCredits += $credit;

                            $cumTotalGP += ($gp * $credit);
                            $cumTotalCredits += $credit;
                        }
                    }

                    $calculatedSgpa = $semTotalCredits > 0 ? round($semTotalGP / $semTotalCredits, 2) : null;
                    $calculatedCgpa = $cumTotalCredits > 0 ? round($cumTotalGP / $cumTotalCredits, 2) : null;

                    // Update or create the summary record (preserving activity points)
                    $summary = StudentSemesterSummary::where('reg_no', $regNo)->where('semester', $sem)->first();
                    if ($summary) {
                        $summary->update([
                            'sgpa' => $calculatedSgpa,
                            'cgpa' => $calculatedCgpa
                        ]);
                    } else {
                        StudentSemesterSummary::create([
                            'reg_no' => $regNo,
                            'semester' => $sem,
                            'sgpa' => $calculatedSgpa,
                            'cgpa' => $calculatedCgpa,
                            'activity_points' => 0
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Data saved successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function verifyData(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $student = Student::where('reg_no', $request->reg_no)->first();
            if ($student) {
                $student->update([
                    'profile_verified_at' => now(),
                    'profile_verified_by' => $mobileNo
                ]);
                return response()->json(['status' => 'SUCCESS']);
            }
            return response()->json(['status' => 'ERROR', 'message' => 'Student not found']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function unverifyData(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $student = Student::where('reg_no', $request->reg_no)->first();
            if ($student) {
                $student->update([
                    'profile_verified_at' => null,
                    'profile_verified_by' => null
                ]);
                return response()->json(['status' => 'SUCCESS']);
            }
            return response()->json(['status' => 'ERROR', 'message' => 'Student not found']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function mentorDownloadPdf(string $regNo)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response("Not authenticated.", 401);

        $dataResponse = app(MentoringController::class)->getFullStudentDiary($regNo);
        if ($dataResponse->status() !== 200) {
            return response("Failed to load student data.", 500);
        }

        $data = $dataResponse->getData(true);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mentoring_pdf', $data);
        return $pdf->download('mentoring_diary_' . strtolower($regNo) . '.pdf');
    }

    public function studentDownloadPdf()
    {
        $regNo = Session::get('userId');
        if (Session::get('userRole') !== 'Student' || !$regNo) {
            return response("Not authenticated as student.", 403);
        }

        $dataResponse = app(MentoringController::class)->getFullStudentDiary($regNo);
        if ($dataResponse->status() !== 200) {
            return response("Failed to load your data.", 500);
        }

        $data = $dataResponse->getData(true);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('mentoring_pdf', $data);
        return $pdf->download('my_mentoring_diary.pdf');
    }

    public function printDiary(string $regNo)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');

        if (!$userId) return redirect('/');

        // Basic authorization: Students can only print their own diary.
        if ($role === 'Student' && strtoupper($userId) !== strtoupper($regNo)) {
            return response("Unauthorized.", 403);
        }

        $dataResponse = app(MentoringController::class)->getFullStudentDiary($regNo);
        if ($dataResponse->status() !== 200) {
            return response("Failed to load student data.", 500);
        }

        $responseData = $dataResponse->getData();
        $data = (array) ($responseData->data ?? $responseData);
        $data['student'] = $data['profile'] ?? null; 
        // Cast the inner 'data' object to an array so view can extract $student, etc.

        return view('student_mentoring_diary_print', $data);
    }

    public function printLeaveReport(string $regNo)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');
        if (!$userId) return redirect('/');

        $student = Student::where('reg_no', strtoupper($regNo))->first();
        if (!$student) return response("Student not found.", 404);

        $leaves = LeaveRecord::where('reg_no', $student->reg_no)->orderByDesc('leave_date')->get();
        $totalLeaves = LeaveRecord::where('reg_no', $student->reg_no)->where('status', 'Approved')->sum('no_of_days');

        // Assume 90 working days per semester
        $workingDays = 90;
        $attendancePercentage = max(0, min(100, (($workingDays - $totalLeaves) / $workingDays) * 100));

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
        ];
        $branchKey = strtoupper($student->branch);
        $fullDepartment = $branchMap[$branchKey] ?? $student->branch;
        
        $cleanedBatch = preg_replace('/^[A-Z]+_/', '', $student->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        return view('student_leave_report_print', [
            'student' => $student,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'leaves' => $leaves,
            'totalLeaves' => $totalLeaves,
            'attendancePercentage' => round($attendancePercentage, 2)
        ]);
    }

    public function printCondonationReport(string $classroomId)
    {
        $userId = Session::get('userId');
        if (!$userId) return redirect('/');

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
        ];
        
        $branchKey = strtoupper(explode('_', $classroomId)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        
        $cleanedBatch = preg_replace('/^[A-Z]+_/', '', $classroomId);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        $students = Student::where('classroom_id', $classroomId)->get()->map(function($s) {
            $totalLeaves = LeaveRecord::where('reg_no', $s->reg_no)->where('status', 'Approved')->sum('no_of_days');
            $workingDays = 90;
            $attendancePercentage = max(0, min(100, (($workingDays - $totalLeaves) / $workingDays) * 100));
            $s->total_leaves = $totalLeaves;
            $s->attendance_percentage = round($attendancePercentage, 2);

            // Fetch condonation/attendance related disciplinary actions
            $condonationDetails = DisciplinaryAction::where('reg_no', $s->reg_no)
                ->where(function($query) {
                    $query->where('description', 'like', '%condonation%')
                          ->orWhere('action_taken', 'like', '%condonation%')
                          ->orWhere('description', 'like', '%attendance%')
                          ->orWhere('action_taken', 'like', '%attendance%');
                })->first();

            $s->condonation_reason = $condonationDetails ? $condonationDetails->description : ($attendancePercentage < 75 ? 'Attendance Shortage (< 75%)' : '-');
            $s->condonation_action = $condonationDetails ? $condonationDetails->action_taken : ($attendancePercentage < 75 ? 'Condonation Required' : '-');
            return $s;
        });

        return view('classroom_condonation_report_print', [
            'classroomId' => $classroomId,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'students' => $students
        ]);
    }

    /**
     * Student Academic Report — powers the "My Academic Stats" panel and "Works To Do" counters.
     * Returns semester-wise mark data, active tasks and overall CGPA.
     * Active tasks only show for the CURRENT semester and only when a lecturer has
     * actually published an assignment deadline or MCQ test.
     */
    public function getStudentAcademicReport()
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');
        
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $regNo = $request->input('reg_no');
        
        if ($role === 'Student') {
            $regNo = $userId;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        try {
            $student = Student::where('reg_no', strtoupper($regNo))->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student profile not found.']);
            }

            // ── Classroom info (includes current_semester) ───────────────────────
            $classroom = ClassManagement::where('classroom_id', $student->classroom_id)->first();
            if (!$classroom) {
                $classroom = DB::table('r26_class_management')->where('classroom_id', $student->classroom_id)->first();
            }
            $currentSem = $classroom ? (int) $classroom->current_semester : 1;

            // ── Batch subjects ───────────────────────────────────────────────────
            $batchSubjects = BatchSubject::where('classroom_id', $student->classroom_id)->get();
            $allSemesters  = $batchSubjects->pluck('semester')->unique()->sort()->values();

            // Subjects for the current semester only
            $currentSemSubjects = $batchSubjects->where('semester', $currentSem);
            $currentSubjectCodes = $currentSemSubjects->pluck('subject_code')->all();

            // ── All academic marks for this student ──────────────────────────────
            $marks = AcademicMark::where('reg_no', $regNo)->get();

            // Helper: get a single mark value
            $getM = function ($subjectCode, $category, $co) use ($marks) {
                $row = $marks
                    ->where('subject_code', $subjectCode)
                    ->where('category', $category)
                    ->where('co_tag', $co)
                    ->first();
                return $row ? $row->marks_obtained : null;
            };

            // ── Active tasks: only subjects lecturer has actually activated ───────
            // For MCQ: check test_configs with is_active=true for current-sem subjects
            // For Assignments: check course_files with assignment_deadline set and not expired
            // For Written Tests: check academic_marks entries published by lecturers
            $activeTasks = [];
            $statsAssignActive  = 0;
            $statsAssignDone    = 0;
            $statsWTActive      = 0;
            $statsWTDone        = 0;
            $statsOTActive      = 0;

            if (!empty($currentSubjectCodes)) {
                // --- MCQ / Online Tests (active test_configs for current sem subjects) ---
                $activeTests = DB::table('test_configs')
                    ->whereIn('subject_code', $currentSubjectCodes)
                    ->where('is_active', true)
                    ->get();

                foreach ($activeTests as $tc) {
                    $subName = $batchSubjects->where('subject_code', $tc->subject_code)->first()?->subject_name ?? $tc->subject_code;
                    $hasDone = DB::table('test_attempts')
                        ->where('reg_no', $regNo)
                        ->where('test_id', $tc->test_id)
                        ->where('status', 'completed')
                        ->exists();

                    if (!$hasDone) {
                        $statsOTActive++;
                        // note: Online tests shown in the online-tests section, not here
                    }
                }

                // --- Assignments: only if lecturer set a deadline (course_files) ---
                // Check if assignment config was published (test_configs with category='Assignment')
                $assignedCOs = DB::table('test_configs')
                    ->whereIn('subject_code', $currentSubjectCodes)
                    ->where('test_name', 'like', '%Assignment%')
                    ->where('is_active', true)
                    ->get();

                foreach ($assignedCOs as $ac) {
                    $subName = $batchSubjects->where('subject_code', $ac->subject_code)->first()?->subject_name ?? $ac->subject_code;
                    // Extract CO from test_name if present
                    preg_match('/CO\d/', $ac->test_name, $matches);
                    $co = $matches[0] ?? 'CO1';

                    $hasMark = $marks->where('subject_code', $ac->subject_code)
                                     ->where('category', 'Assignment')
                                     ->where('co_tag', $co)->isNotEmpty();
                    if (!$hasMark) {
                        $statsAssignActive++;
                        $activeTasks[] = [
                            'type'         => 'Assignment',
                            'subject_code' => $ac->subject_code,
                            'subject'      => $subName,
                            'co_tag'       => $co,
                            'status'       => 'Active',
                            'deadline'     => $ac->end_time ?? null,
                            'start'        => $ac->start_time ?? null,
                            'questions'    => [],
                        ];
                    } else {
                        $statsAssignDone++;
                    }
                }

                // --- Written Tests: show only if lecturer has saved marks for at least
                //     one student (meaning test was conducted) but this student's marks
                //     haven't come yet (or all marks present = done) ---
                // Approach: check all subject+CO combinations where ANY academic_mark
                //     of category 'Written Test' exists for this classroom's students.
                $wtPublished = DB::table('academic_marks')
                    ->whereIn('subject_code', $currentSubjectCodes)
                    ->where('category', 'Written Test')
                    ->select('subject_code', 'co_tag')
                    ->distinct()
                    ->get();

                foreach ($wtPublished as $wt) {
                    $subName = $batchSubjects->where('subject_code', $wt->subject_code)->first()?->subject_name ?? $wt->subject_code;
                    $hasMark = $marks->where('subject_code', $wt->subject_code)
                                     ->where('category', 'Written Test')
                                     ->where('co_tag', $wt->co_tag)->isNotEmpty();
                    if (!$hasMark) {
                        $statsWTActive++;
                        $activeTasks[] = [
                            'type'         => 'Written Test',
                            'subject_code' => $wt->subject_code,
                            'subject'      => $subName,
                            'co_tag'       => $wt->co_tag,
                            'status'       => 'Pending',
                            'deadline'     => null,
                            'start'        => null,
                            'questions'    => [],
                        ];
                    } else {
                        $statsWTDone++;
                    }
                }
            }

            // Count submitted assignments from marks
            $statsAssignDone = $marks->where('category', 'Assignment')
                ->whereIn('subject_code', $currentSubjectCodes)->count();

            // ── Semester summaries (SGPA / CGPA / activity points) ───────────────
            $semSummaries = StudentSemesterSummary::where('reg_no', $regNo)
                ->orderBy('semester', 'asc')->get()->keyBy('semester');

            // ── Overall CGPA ─────────────────────────────────────────────────────
            $latestSummary = $semSummaries->last();
            $overallCgpa   = $latestSummary ? $latestSummary->cgpa : null;
            $totalActPts   = $semSummaries->sum('activity_points');

            // ── Build semesters array for frontend ───────────────────────────────
            $semestersData = [];
            foreach ($allSemesters as $sem) {
                $semSubjects = $batchSubjects->where('semester', $sem);
                $summ = $semSummaries->get($sem);

                $subjectRows = [];
                foreach ($semSubjects as $sub) {
                    $subjectRows[] = [
                        'subject_code'         => $sub->subject_code,
                        'subject_name'         => $sub->subject_name,
                        'CO1'   => $getM($sub->subject_code, 'Written Test', 'CO1'),
                        'CO2'   => $getM($sub->subject_code, 'Written Test', 'CO2'),
                        'CO3'   => $getM($sub->subject_code, 'Written Test', 'CO3'),
                        'CO4'   => $getM($sub->subject_code, 'Written Test', 'CO4'),
                        'Assg1' => $getM($sub->subject_code, 'Assignment', 'CO1'),
                        'Assg2' => $getM($sub->subject_code, 'Assignment', 'CO2'),
                        'Assg3' => $getM($sub->subject_code, 'Assignment', 'CO3'),
                        'Assg4' => $getM($sub->subject_code, 'Assignment', 'CO4'),
                        'WT1'   => $getM($sub->subject_code, 'Written Test', 'CO1'),
                        'WT2'   => $getM($sub->subject_code, 'Written Test', 'CO2'),
                        'WT3'   => $getM($sub->subject_code, 'Written Test', 'CO3'),
                        'WT4'   => $getM($sub->subject_code, 'Written Test', 'CO4'),
                        'OT1'   => $getM($sub->subject_code, 'Online Test', 'CO1'),
                        'OT2'   => $getM($sub->subject_code, 'Online Test', 'CO2'),
                        'OT3'   => $getM($sub->subject_code, 'Online Test', 'CO3'),
                        'OT4'   => $getM($sub->subject_code, 'Online Test', 'CO4'),
                        'attendance_percentage' => 0,
                    ];
                }

                $semestersData[] = [
                    'semester'        => $sem,
                    'sgpa'            => $summ ? $summ->sgpa : null,
                    'cgpa'            => $summ ? $summ->cgpa : null,
                    'activity_points' => $summ ? $summ->activity_points : 0,
                    'subjects'        => $subjectRows,
                    'is_current'      => ((int)$sem === $currentSem),
                ];
            }

            return response()->json([
                'status'       => 'SUCCESS',
                'overall'      => [
                    'cgpa'             => $overallCgpa,
                    'activity_points'  => $totalActPts,
                    'current_semester' => $currentSem,
                ],
                'semesters'    => $semestersData,
                'active_tasks' => $activeTasks,
                'stats'        => [
                    'assignments_active'      => $statsAssignActive,
                    'assignments_submitted'   => $statsAssignDone,
                    'written_tests_active'    => $statsWTActive,
                    'written_tests_submitted' => $statsWTDone,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function saveExtraCurricular(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'reg_no'           => 'required|string',
            'semester'         => 'required|integer',
            'activity_segment' => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer',
            'points_awarded'   => 'required|integer',
            'status'           => 'required|string'
        ]);

        $data = [
            'reg_no'           => strtoupper($request->reg_no),
            'semester'         => $request->semester,
            'activity_segment' => $request->activity_segment,
            'activity_name'    => $request->activity_name,
            'level'            => $request->level,
            'points_claimed'   => $request->points_claimed,
            'points_awarded'   => $request->points_awarded,
            'status'           => $request->status,
            'verified_by'      => $mobileNo,
        ];

        if ($request->has('id') && !empty($request->id)) {
            \App\Models\ActivityPointClaim::where('id', $request->id)->update($data);
            $msg = 'Activity updated successfully.';
        } else {
            \App\Models\ActivityPointClaim::create($data);
            $msg = 'Activity added successfully.';
        }

        return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
    }

    public function saveLeaveRecord(Request $request)
    {
        $mobileNo = Session::get('userId');
        $role = Session::get('userRole');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $regNo = $request->input('reg_no');
        if ($role === 'Student') {
            $regNo = $mobileNo;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        $request->validate([
            'semester'   => 'required|integer',
            'leave_date' => 'required|string',
            'no_of_days' => 'required|string',
            'reason'     => 'required|string'
        ]);

        $data = [
            'reg_no'          => strtoupper($regNo),
            'semester'        => $request->semester,
            'leave_date'      => $request->leave_date,
            'no_of_days'      => $request->no_of_days,
            'reason'          => $request->reason,
            'parent_informed' => $request->has('parent_informed') ? $request->parent_informed : false,
            'status'          => $request->input('status', 'Pending'),
            'approved_by'     => ($role === 'Student') ? null : $mobileNo,
        ];

        if ($request->has('id') && !empty($request->id)) {
            LeaveRecord::where('id', $request->id)->update($data);
            $msg = 'Leave record updated successfully.';
        } else {
            LeaveRecord::create($data);
            $msg = 'Leave record added successfully.';
        }

        return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
    }

    // ─────────────────────────────────────────────────────────────────
    //  POST /api/mentoring/leave/approve
    //  Tutor/Mentor approves or rejects a student leave application
    // ─────────────────────────────────────────────────────────────────
    public function approveLeaveRecord(Request $request)
    {
        $mobileNo = Session::get('userId');
        $role     = Session::get('userRole');

        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        if ($role === 'Student') return response()->json(['status' => 'ERROR', 'message' => 'Students cannot approve leave.'], 403);

        $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|in:Approved,Rejected',
        ]);

        try {
            $leave = LeaveRecord::find($request->id);
            if (!$leave) return response()->json(['status' => 'ERROR', 'message' => 'Leave record not found.']);

            $leave->update([
                'status'      => $request->status,
                'approved_by' => $mobileNo,
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Leave ' . strtolower($request->status) . ' successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  POST /api/mentoring/diary/delete
    //  Tutor/Mentor deletes a diary session entry
    // ─────────────────────────────────────────────────────────────────
    public function deleteDiaryEntry(Request $request)
    {
        $mobileNo = Session::get('userId');
        $role     = Session::get('userRole');

        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        if ($role === 'Student') return response()->json(['status' => 'ERROR', 'message' => 'Students cannot delete diary logs.'], 403);

        $request->validate([
            'diary_id' => 'required|string',
        ]);

        try {
            $entry = TutorDiary::where('diary_id', $request->diary_id)->first();
            if (!$entry) return response()->json(['status' => 'ERROR', 'message' => 'Meeting log not found.']);

            $entry->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Meeting log deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  POST /api/mentoring/disciplinary/delete
    //  Tutor/Mentor deletes a disciplinary action record
    // ─────────────────────────────────────────────────────────────────
    public function deleteDisciplinary(Request $request)
    {
        $mobileNo = Session::get('userId');
        $role     = Session::get('userRole');

        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        if ($role === 'Student') return response()->json(['status' => 'ERROR', 'message' => 'Students cannot delete disciplinary actions.'], 403);

        $request->validate([
            'id' => 'required|integer',
        ]);

        try {
            $action = DisciplinaryAction::find($request->id);
            if (!$action) return response()->json(['status' => 'ERROR', 'message' => 'Disciplinary record not found.']);

            $action->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Disciplinary action deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function saveDisciplinary(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'reg_no'      => 'required|string',
            'date'        => 'required|date',
            'description' => 'required|string'
        ]);

        // Only store reported_by if the staff profile exists (avoids FK constraint violation)
        $staffExists = StaffProfile::where('mobile_no', $mobileNo)->exists();
        $reportedBy  = $staffExists ? $mobileNo : null;

        $data = [
            'reg_no'       => strtoupper($request->reg_no),
            'date'         => $request->date,
            'description'  => $request->description,
            'action_taken' => $request->action_taken,
            'reported_by'  => $reportedBy,
        ];

        try {
            if ($request->has('id') && !empty($request->id)) {
                DisciplinaryAction::where('id', $request->id)->update($data);
                $msg = 'Disciplinary action updated successfully.';
            } else {
                DisciplinaryAction::create($data);
                $msg = 'Disciplinary action added successfully.';
            }

            return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  GET /api/mentoring/classroom/{classroomId}/leaves
    //  Fetch all student leave records for a classroom
    // ─────────────────────────────────────────────────────────────────
    public function getClassroomLeaves(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $studentRegNos = Student::where('classroom_id', $classroomId)->pluck('reg_no');
            $leaves = LeaveRecord::whereIn('reg_no', $studentRegNos)
                ->orderByDesc('leave_date')
                ->get()
                ->map(function ($l) {
                    $l->student_name = Student::where('reg_no', $l->reg_no)->value('name') ?? $l->reg_no;
                    return $l;
                });

            $students = Student::where('classroom_id', $classroomId)
                ->orderBy('name')
                ->select('reg_no', 'name')
                ->get();

            return response()->json([
                'status' => 'SUCCESS', 
                'data' => $leaves,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    public function tutorViewFullDiary(string $regNo)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');
        if (!$userId || $role === 'Student') return redirect('/');
        return view('tutor_student_diary_full', ['studentRegNo' => $regNo]);
    }

    public function showStaffMobileDashboard(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role === 'Student') {
            return redirect('/');
        }

        // Auto-redirect desktop users without mode=mobile to their desktop dashboard
        $ua = strtolower($request->header('User-Agent', ''));
        $isMobileDevice = (bool)preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|iphone|ipad|ipod|palm|phone|opera mini|iemobile/i', $ua);

        if (!$isMobileDevice && !$request->has('mobile') && $request->input('mode') !== 'mobile') {
            switch ($role) {
                case 'HOD':
                    return redirect('/dashboard/hod?mode=desktop');
                case 'Super_Admin':
                case 'Principal':
                    return redirect('/dashboard/principal?mode=desktop');
                case 'Admin':
                    return redirect('/dashboard/admin?mode=desktop');
                case 'Gen_Dept_Coordinator_Aided':
                    return redirect('/dashboard/general-coordinator-aided?mode=desktop');
                case 'Gen_Dept_Coordinator_Self_Finance':
                    return redirect('/dashboard/general-coordinator-sf?mode=desktop');
                case 'Academic_Coordinator':
                case 'Academic Coordinator':
                case 'Academic_Coordinator_SF':
                    return redirect('/dashboard/academic-coordinator?mode=desktop');
                case 'Demonstrator':
                    return redirect('/dashboard/demonstrator?mode=desktop');
                case 'Trade_Instructor':
                    return redirect('/dashboard/tradeinstructor?mode=desktop');
                case 'Workshop_Superintendent':
                    return redirect('/dashboard/workshop?mode=desktop');
                case 'Lecturer':
                case 'Physical_Instructor':
                case 'Physical Instructor':
                default:
                    return redirect('/dashboard/lecturer?mode=desktop');
            }
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff) {
            $staff = (object) [
                'name' => Session::get('userName', 'Staff Member'),
                'mobile_no' => $userId,
                'designation' => $role,
                'department' => Session::get('userBranch', 'Academic'),
                'photo_url' => Session::get('userPhoto'),
            ];
        }

        // 1. Staff Assigned Subjects & Classrooms
        $assignments = DB::table('subject_staff_assignments')
            ->join('batch_subjects', 'subject_staff_assignments.batch_subject_id', '=', 'batch_subjects.id')
            ->where('subject_staff_assignments.staff_mobile_no', $userId)
            ->select('batch_subjects.*', 'subject_staff_assignments.batch_subject_id')
            ->get();

        foreach ($assignments as $subj) {
            $tot = DB::table('lesson_plans')->where('batch_subject_id', $subj->id)->count();
            $comp = DB::table('lesson_plans')->where('batch_subject_id', $subj->id)->where('status', 'Completed')->count();
            $subj->total_lesson_plans = $tot;
            $subj->completed_lesson_plans = $comp;
            $subj->progress_percent = $tot > 0 ? round(($comp / $tot) * 100) : 0;
        }

        // 2. Classrooms where staff is Tutor (Mentor-1) or Mentor-2
        $cls21 = ClassManagement::where('tutor_mobile_no', $userId)
            ->orWhere('mentor_mobile_no', $userId)
            ->get();
        $cls26 = DB::table('r26_class_management')->where('tutor_mobile_no', $userId)
            ->orWhere('mentor_mobile_no', $userId)
            ->get();
        $classrooms = $cls21->concat($cls26);

        // 3. Pending Leaves for Staff's Classrooms
        $classroomIds = $classrooms->pluck('classroom_id')->toArray();
        $studentRegNos = Student::whereIn('classroom_id', $classroomIds)->pluck('reg_no');
        $pendingLeaves = LeaveRecord::whereIn('reg_no', $studentRegNos)
            ->where('status', 'Pending')
            ->orderByDesc('leave_date')
            ->get()
            ->map(function ($l) {
                $l->student_name = Student::where('reg_no', $l->reg_no)->value('name') ?? $l->reg_no;
                return $l;
            });

        // 4. Active Test Configs
        $activeTests = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('test_configs')) {
            $activeTests = DB::table('test_configs')
                ->where('is_active', 1)
                ->orderByDesc('test_id')
                ->get();
        }

        // 5. Timetable Schedule by Day Order (Day 1 to Day 5)
        $dayMap = [
            'Monday' => 'Day 1',
            'Tuesday' => 'Day 2',
            'Wednesday' => 'Day 3',
            'Thursday' => 'Day 4',
            'Friday' => 'Day 5',
        ];
        $defaultDayOrder = \App\Services\DayOrderService::getActiveDayOrder();

        $fullTimetablesByDay = [
            'Day 1' => [],
            'Day 2' => [],
            'Day 3' => [],
            'Day 4' => [],
            'Day 5' => [],
        ];

        $dir = storage_path("app/timetables");
        if (is_dir($dir)) {
            $files = glob($dir . "/*.json");
            $staffObjName = isset($staff->name) ? trim($staff->name) : '';

            foreach ($files as $file) {
                $cId = str_replace(['.json', $dir . '/'], '', $file);
                $ttData = json_decode(file_get_contents($file), true);
                if ($ttData) {
                    foreach (['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'] as $dayKey) {
                        $dayAlt = array_search($dayKey, $dayMap);
                        $dayData = $ttData[$dayKey] ?? ($dayAlt ? ($ttData[$dayAlt] ?? null) : null);
                        if ($dayData && is_array($dayData)) {
                            foreach ($dayData as $period => $details) {
                                if (!empty($details)) {
                                    $subCode = is_array($details) ? ($details['subject'] ?? ($details['subject_code'] ?? '')) : $details;
                                    $staffName = is_array($details) ? ($details['staff'] ?? '') : '';
                                    $staffNameClean = trim($staffName);

                                    $matchesName = false;
                                    if (!empty($staffObjName) && !empty($staffNameClean)) {
                                        if (str_contains(strtolower($staffNameClean), strtolower($staffObjName)) || 
                                            str_contains(strtolower($staffObjName), strtolower($staffNameClean)) ||
                                            $staffNameClean === $userId) {
                                            $matchesName = true;
                                        }
                                    }

                                    $assignedSub = $assignments->first(function ($item) use ($subCode, $cId) {
                                        if (($item->subject_code ?? '') !== $subCode) return false;
                                        if (!empty($item->classroom_id) && $item->classroom_id !== $cId) return false;
                                        return true;
                                    });
                                    if (!$assignedSub) {
                                        $assignedSub = $assignments->firstWhere('subject_code', $subCode);
                                    }

                                    $isOwnClass = $matchesName || ($assignedSub && (empty($staffNameClean) || $matchesName));

                                    if ($isOwnClass) {
                                        $fullTimetablesByDay[$dayKey][] = (object) [
                                            'period' => (int)$period,
                                            'classroom_id' => $cId,
                                            'subject_code' => $subCode,
                                            'subject_name' => $assignedSub->subject_name ?? $subCode,
                                            'staff_name' => $staffName,
                                            'batch_subject_id' => $assignedSub->id ?? null,
                                            'progress_percent' => $assignedSub->progress_percent ?? 0,
                                            'completed_lesson_plans' => $assignedSub->completed_lesson_plans ?? 0,
                                            'total_lesson_plans' => $assignedSub->total_lesson_plans ?? 0,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $todaySchedule = $fullTimetablesByDay[$defaultDayOrder] ?? [];

        // 6. Remedial Rooms Assigned to Staff
        $remedialRooms = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('remedial_rooms')) {
            $remedialRooms = DB::table('remedial_rooms')
                ->where('created_by_mobile', $userId)
                ->get();
        }

        // 7. Staff To-Do Items
        $todos = [];
        
        // 7.1 Student Leave Applications
        if (count($pendingLeaves) > 0) {
            $todos[] = (object) [
                'type' => 'leave',
                'title' => count($pendingLeaves) . ' Student Leave Applications Pending',
                'subtitle' => 'Action required for tutorship batch',
                'badge' => 'Leave Request',
                'badge_class' => 'bg-warning text-dark',
                'icon' => 'fa-solid fa-clock-rotate-left text-warning',
                'link' => '#'
            ];
        }

        // 7.2 Student Assignment Submissions Received
        $assignmentSubmissionsCount = 0;
        if (count($assignments) > 0 && \Illuminate\Support\Facades\Schema::hasTable('test_attempts')) {
            $assignmentSubmissionsCount = DB::table('test_attempts')
                ->join('test_configs', 'test_attempts.test_id', '=', 'test_configs.test_id')
                ->whereIn('test_configs.subject_code', $assignments->pluck('subject_code')->toArray())
                ->where('test_configs.test_name', 'like', '%Assignment%')
                ->where('test_attempts.status', 'completed')
                ->count();
        }
        if ($assignmentSubmissionsCount > 0) {
            $todos[] = (object) [
                'type' => 'assignment_submission',
                'title' => $assignmentSubmissionsCount . ' Student Assignment Submissions Received',
                'subtitle' => 'Assignments submitted for your subjects',
                'badge' => 'Assignment',
                'badge_class' => 'bg-emerald text-white',
                'icon' => 'fa-solid fa-file-circle-check text-emerald-400',
                'link' => '/dashboard/lecturer?mode=desktop'
            ];
        }

        // 7.3 Series / Written Exams Declared
        $declaredSeriesCount = 0;
        if (count($assignments) > 0 && \Illuminate\Support\Facades\Schema::hasTable('series_exams')) {
            $declaredSeriesCount = DB::table('series_exams')
                ->whereIn('batch_subject_id', $assignments->pluck('batch_subject_id')->toArray())
                ->count();
        }
        if ($declaredSeriesCount > 0) {
            $todos[] = (object) [
                'type' => 'series_exam',
                'title' => $declaredSeriesCount . ' Series / Written Exams Scheduled',
                'subtitle' => 'Internal test dates & question paper configurations declared',
                'badge' => 'Series Exam',
                'badge_class' => 'bg-rose text-white',
                'icon' => 'fa-solid fa-pen-nib text-rose-400',
                'link' => '/dashboard/lecturer?mode=desktop'
            ];
        }

        // 7.4 Pre-Declared Academic Calendar & Events
        $hasCalendarEvents = false;
        if (\Illuminate\Support\Facades\Schema::hasTable('academic_calendars')) {
            $hasCalendarEvents = DB::table('academic_calendars')->exists();
        }
        if ($hasCalendarEvents) {
            $todos[] = (object) [
                'type' => 'calendar_event',
                'title' => 'Pre-Declared Academic Calendar & Events Active',
                'subtitle' => 'Institutional schedule, series exam windows & holidays set',
                'badge' => 'Calendar Event',
                'badge_class' => 'bg-cyan text-dark',
                'icon' => 'fa-solid fa-calendar-check text-cyan',
                'link' => '/hod/academic-calendar'
            ];
        }

        // 7.5 Activity Point Claims Pending
        $pendingActivityClaimsCount = 0;
        if (!empty($studentRegNos) && count($studentRegNos) > 0) {
            if (\Illuminate\Support\Facades\Schema::hasTable('activity_point_claims')) {
                $pendingActivityClaimsCount = DB::table('activity_point_claims')
                    ->whereIn('reg_no', $studentRegNos)
                    ->where('status', 'Pending')
                    ->count();
            }
        }
        if ($pendingActivityClaimsCount > 0) {
            $todos[] = (object) [
                'type' => 'activity_points',
                'title' => $pendingActivityClaimsCount . ' Activity Point Claims Pending Verification',
                'subtitle' => 'Extra-curricular claims submitted by tutorship students',
                'badge' => 'Activity Claim',
                'badge_class' => 'bg-success text-white',
                'icon' => 'fa-solid fa-trophy text-success',
                'link' => count($classroomIds) > 0 ? '/tutor/mentoring-diary/' . $classroomIds[0] : '#'
            ];
        }

        // 7.6 Student Seminar Notifications Initialized
        $pendingSeminarsCount = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('student_seminar_registrations')) {
            $pendingSeminarsCount = DB::table('student_seminar_registrations')
                ->where(function($q) use ($userId, $studentRegNos) {
                    $q->where('guide_mobile_no', $userId);
                    if (!empty($studentRegNos) && count($studentRegNos) > 0) {
                        $q->orWhereIn('reg_no', $studentRegNos);
                    }
                })
                ->count();
        }
        if ($pendingSeminarsCount > 0) {
            $todos[] = (object) [
                'type' => 'seminar',
                'title' => $pendingSeminarsCount . ' Student Seminar Topic Registrations',
                'subtitle' => 'Seminar topics & presentation dates initialized by students',
                'badge' => 'Seminar',
                'badge_class' => 'bg-primary text-white',
                'icon' => 'fa-solid fa-chalkboard-user text-primary',
                'link' => count($classroomIds) > 0 ? '/tutor/mentoring-diary/' . $classroomIds[0] : '#'
            ];
        }

        // 7.7 Daily Attendance Task
        if (count($assignments) > 0) {
            $todos[] = (object) [
                'type' => 'attendance',
                'title' => 'Mark Attendance for Today\'s Classes (' . date('d M Y') . ')',
                'subtitle' => count($assignments) . ' active subject assignments',
                'badge' => 'Daily Task',
                'badge_class' => 'bg-info text-dark',
                'icon' => 'fa-solid fa-clipboard-user text-info',
                'link' => '#'
            ];
        }

        // 7.8 Remedial Classes
        if (count($remedialRooms) > 0) {
            $todos[] = (object) [
                'type' => 'remedial',
                'title' => count($remedialRooms) . ' Remedial Classes Scheduled',
                'subtitle' => 'Log student attendance & session topics',
                'badge' => 'Remedial',
                'badge_class' => 'bg-danger text-white',
                'icon' => 'fa-solid fa-kit-medical text-danger',
                'link' => '/remedial-sessions'
            ];
        }

        // 7.9 Active Online Tests
        if (count($activeTests) > 0) {
            $todos[] = (object) [
                'type' => 'test',
                'title' => count($activeTests) . ' Online MCQ Test Configurations Published',
                'subtitle' => 'Monitor student submission results',
                'badge' => 'Online Test',
                'badge_class' => 'bg-purple text-white',
                'icon' => 'fa-solid fa-laptop-code text-purple',
                'link' => '/dashboard/lecturer?mode=desktop'
            ];
        }

        // 7.10 Today's Biometric Attendance Punch Record
        $staffId = Session::get('userStaffId') ?? Session::get('mobileNo') ?? Session::get('userId') ?? 'SF-STAFF-DEMO';
        $todayPunch = \App\Models\SfStaffTimePunch::where(function($q) use ($staffId, $userId) {
            $q->where('staff_id', $staffId);
            if ($userId) {
                $q->orWhere('staff_id', $userId);
            }
        })
        ->where('punch_date', now()->format('Y-m-d'))
        ->first();

        return response(view('staff_mobile_dashboard', compact(
            'staff',
            'assignments',
            'classrooms',
            'pendingLeaves',
            'activeTests',
            'todaySchedule',
            'remedialRooms',
            'todos',
            'fullTimetablesByDay',
            'defaultDayOrder',
            'todayPunch'
        )))->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }
}
