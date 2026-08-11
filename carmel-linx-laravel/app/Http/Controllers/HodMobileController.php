<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\StaffLeaveRequest;
use App\Models\ClassManagement;
use App\Models\Student;
use App\Models\LeaveRecord;
use App\Models\DepartmentNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HodMobileController extends Controller
{
    /**
     * Display the HOD Mobile Portal.
     */
    public function index(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role !== 'HOD') {
            return redirect('/');
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff) {
            $staff = (object) [
                'name'        => Session::get('userName', 'HOD Officer'),
                'mobile_no'   => $userId,
                'designation' => 'HOD',
                'branch'      => Session::get('userBranch', 'Engineering'),
                'photo_url'   => Session::get('userPhoto'),
            ];
        }

        $dept = $staff->branch ?? Session::get('userBranch', 'Engineering');

        // 1. My Teaching Subjects (HOD acting as Faculty)
        $mySubjects = DB::table('subject_staff_assignments')
            ->join('batch_subjects', 'subject_staff_assignments.batch_subject_id', '=', 'batch_subjects.id')
            ->where('subject_staff_assignments.staff_mobile_no', $userId)
            ->select('batch_subjects.*', 'subject_staff_assignments.batch_subject_id')
            ->get();

        // 2. Department Classroom Batches (R21 & R26)
        $deptBatches2021 = ClassManagement::where('branch', $dept)
            ->orWhere('classroom_id', 'like', "{$dept}%")
            ->orderBy('batch_year', 'desc')
            ->get();

        $deptBatches2026 = DB::table('r26_class_management')
            ->where('branch', $dept)
            ->orWhere('classroom_id', 'like', "{$dept}%")
            ->orderBy('batch_year', 'desc')
            ->get();

        foreach ($deptBatches2026 as $b26) {
            $b26->is_r26 = true;
        }

        $deptBatches = $deptBatches2021->concat($deptBatches2026)->unique('classroom_id')->values();

        // Populate tutor/mentor details for batches
        $tutorMobiles = $deptBatches->pluck('tutor_mobile_no')->filter()->toArray();
        $mentorMobiles = $deptBatches->pluck('mentor_mobile_no')->filter()->toArray();
        $staffMap = StaffProfile::whereIn('mobile_no', array_merge($tutorMobiles, $mentorMobiles))
            ->get()
            ->keyBy('mobile_no');

        foreach ($deptBatches as $batch) {
            $batch->tutor_name = isset($staffMap[$batch->tutor_mobile_no]) ? $staffMap[$batch->tutor_mobile_no]->name : null;
            $batch->mentor_name = isset($staffMap[$batch->mentor_mobile_no]) ? $staffMap[$batch->mentor_mobile_no]->name : null;
            $batch->student_count = Student::where('classroom_id', $batch->classroom_id)->where('status', 'Approved')->count();
        }

        // 3. Pending Staff Leave Applications for HOD's Department
        $pendingStaffLeaves = StaffLeaveRequest::where(function($q) use ($dept) {
                $q->where('department', $dept)->orWhere('department', 'like', "%{$dept}%");
            })
            ->where('overall_status', 'Pending_HOD')
            ->orderByDesc('id')
            ->get();

        // 4. Pending Student Leaves across Department Classrooms
        $deptClassroomIds = $deptBatches->pluck('classroom_id')->toArray();
        $deptStudentRegNos = Student::whereIn('classroom_id', $deptClassroomIds)->pluck('reg_no');
        $pendingStudentLeaves = LeaveRecord::whereIn('reg_no', $deptStudentRegNos)
            ->where('status', 'Pending')
            ->orderByDesc('leave_date')
            ->get()
            ->map(function ($l) {
                $l->student_name = Student::where('reg_no', $l->reg_no)->value('name') ?? $l->reg_no;
                return $l;
            });

        // 5. Department Staff Roster
        $deptStaff = StaffProfile::where('branch', $dept)->orderBy('name')->get();

        // 6. Department, Principal & Institutional Notices
        $notices = DepartmentNotice::where(function ($query) use ($dept) {
            $query->where('department', $dept)
                  ->orWhere('department', 'ALL')
                  ->orWhere('department', 'Institutional')
                  ->orWhere('department', 'Principal')
                  ->orWhere('department', 'like', "%{$dept}%");
        })->orderByDesc('id')->get();

        // 7. Student Seminars & Academic Presentations
        $upcomingSeminars = DB::table('student_seminar_registrations')
            ->join('students', 'student_seminar_registrations.reg_no', '=', 'students.reg_no')
            ->select('student_seminar_registrations.*', 'students.name as student_name', 'students.classroom_id')
            ->orderBy('presentation_date', 'desc')
            ->take(5)
            ->get();

        // 8. Active Day Order
        $dayMap = [
            'Monday' => 'Day 1',
            'Tuesday' => 'Day 2',
            'Wednesday' => 'Day 3',
            'Thursday' => 'Day 4',
            'Friday' => 'Day 5',
        ];
        $defaultDayOrder = $dayMap[date('l')] ?? 'Day 1';
        $activeDayOrderPath = storage_path('app/active_day_order.json');
        if (file_exists($activeDayOrderPath)) {
            $activeDayData = json_decode(file_get_contents($activeDayOrderPath), true);
            if ($activeDayData && ($activeDayData['date'] ?? '') === now()->toDateString()) {
                $defaultDayOrder = $activeDayData['day_order'];
            }
        }

        // 9. Branch Timetables & Live Class Status for 3 Semesters (S1, S3, S5)
        $periodTimings = [
            1 => '9:00 AM - 10:00 AM',
            2 => '10:00 AM - 11:00 AM',
            3 => '11:10 AM - 12:10 PM',
            4 => '1:00 PM - 2:00 PM',
            5 => '2:00 PM - 3:00 PM',
            6 => '3:00 PM - 4:00 PM',
        ];

        $todayDate = now()->toDateString();
        $targetSemesters = [1, 3, 5];
        $semesterSchedules = [];

        // Fetch all classrooms for this department (R21 and R26)
        $deptClsR21 = DB::table('class_management')->where('branch', $dept)->get();
        $deptClsR26 = DB::table('r26_class_management')->where('branch', $dept)->get();
        $allDeptClassrooms = $deptClsR21->concat($deptClsR26);

        foreach ($targetSemesters as $sem) {
            // Locate classroom matching current_semester or semester batch year
            $classroom = $allDeptClassrooms->firstWhere('current_semester', $sem);
            if (!$classroom && $sem == 1) {
                $classroom = $allDeptClassrooms->first(function ($c) {
                    return str_contains($c->classroom_id, '2026') || ($c->current_semester ?? 1) == 1;
                });
            }

            $semSubjects = DB::table('batch_subjects')
                ->where('semester', $sem)
                ->where(function ($q) use ($dept) {
                    $q->where('classroom_id', 'like', "{$dept}%")
                      ->orWhere('subject_code', 'like', "{$dept}%");
                })
                ->get();

            if ($semSubjects->isEmpty()) {
                $semSubjects = DB::table('batch_subjects')->where('semester', $sem)->get();
            }

            $subjectIds = $semSubjects->pluck('id')->toArray();

            // Conducted logs today for this semester
            $todayLogs = DB::table('class_logs_attendance')
                ->whereIn('batch_subject_id', $subjectIds)
                ->where('date', $todayDate)
                ->get()
                ->keyBy('period');

            // Load saved timetable JSON file for this classroom if available
            $dayTt = null;
            if ($classroom && !empty($classroom->classroom_id)) {
                $cIdClean = preg_replace('/[^a-zA-Z0-9_-]/', '', $classroom->classroom_id);
                $ttFile = storage_path("app/timetables/{$cIdClean}.json");
                if (file_exists($ttFile)) {
                    $rawTt = json_decode(file_get_contents($ttFile), true);
                    if ($rawTt) {
                        $dayAlt = array_search($defaultDayOrder, $dayMap);
                        $dayTt = $rawTt[$defaultDayOrder] ?? ($dayAlt ? ($rawTt[$dayAlt] ?? null) : null);
                    }
                }
            }

            $periodsData = [];
            for ($p = 1; $p <= 6; $p++) {
                if (isset($todayLogs[$p])) {
                    $log = $todayLogs[$p];
                    $subj = $semSubjects->firstWhere('id', $log->batch_subject_id);
                    $recordedStaff = DB::table('staff_profiles')->where('mobile_no', $log->recorded_by)->value('name') ?? $log->recorded_by;

                    $periodsData[$p] = [
                        'period'       => $p,
                        'time_slot'    => $periodTimings[$p],
                        'subject_code' => $subj->subject_code ?? 'Class',
                        'subject_name' => $subj->subject_name ?? 'Class Session',
                        'staff_name'   => $recordedStaff ?: 'Faculty',
                        'topic'        => $log->topics_covered ?? 'Class Conducted',
                        'status'       => 'Conducted',
                        'badge_class'  => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                    ];
                } else {
                    // Check if timetable cell exists for this period
                    $slotData = null;
                    if ($dayTt && is_array($dayTt)) {
                        $slotData = $dayTt[$p] ?? ($dayTt[(string)$p] ?? ($dayTt["period_{$p}"] ?? ($dayTt["Period {$p}"] ?? null)));
                    }

                    if ($slotData && !empty($slotData)) {
                        $subCode = is_array($slotData) ? ($slotData['subject'] ?? ($slotData['subject_code'] ?? '')) : $slotData;
                        $staffName = is_array($slotData) ? ($slotData['staff'] ?? '') : '';

                        $matchedSub = $semSubjects->firstWhere('subject_code', $subCode);
                        if (!$matchedSub) {
                            $matchedSub = DB::table('batch_subjects')->where('subject_code', $subCode)->first();
                        }
                        $subName = $matchedSub ? $matchedSub->subject_name : $subCode;

                        $periodsData[$p] = [
                            'period'       => $p,
                            'time_slot'    => $periodTimings[$p],
                            'subject_code' => $subCode ?: 'Scheduled',
                            'subject_name' => $subName ?: 'Scheduled Class',
                            'staff_name'   => $staffName ?: 'Assigned Faculty',
                            'topic'        => 'Scheduled Class',
                            'status'       => 'Scheduled',
                            'badge_class'  => 'bg-blue-500/20 text-blue-400 border border-blue-500/30'
                        ];
                    } else {
                        // NO timetable created by HOD for this slot or timetable file missing
                        $periodsData[$p] = [
                            'period'       => $p,
                            'time_slot'    => $periodTimings[$p],
                            'subject_code' => 'FREE',
                            'subject_name' => 'Free Period',
                            'staff_name'   => '—',
                            'topic'        => 'No Class Scheduled',
                            'status'       => 'Free',
                            'badge_class'  => 'bg-slate-800/80 text-slate-400 border border-slate-700/60'
                        ];
                    }
                }
            }

            $semesterSchedules[$sem] = [
                'semester_name' => "Semester {$sem}",
                'subjects_count' => $semSubjects->count(),
                'conducted_count' => $todayLogs->count(),
                'periods' => $periodsData
            ];
        }

        return response(view('hod_mobile_dashboard', compact(
            'staff',
            'dept',
            'mySubjects',
            'deptBatches',
            'pendingStaffLeaves',
            'pendingStudentLeaves',
            'deptStaff',
            'notices',
            'upcomingSeminars',
            'defaultDayOrder',
            'semesterSchedules'
        )))->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

    /**
     * Publish a new Department Notice / Announcement.
     */
    public function createNotice(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authorized.'], 403);
        }

        $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'target_audience' => 'required|string',
            'priority'        => 'required|string',
        ]);

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        $dept  = $staff ? $staff->branch : Session::get('userBranch', 'Engineering');
        $author = $staff ? $staff->name : Session::get('userName', 'HOD');

        $notice = DepartmentNotice::create([
            'department'      => $dept,
            'title'           => $request->title,
            'content'         => $request->content,
            'target_audience' => $request->target_audience,
            'priority'        => $request->priority,
            'created_by'      => $userId,
            'author_name'     => $author,
        ]);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Department notice published successfully.',
            'notice'  => $notice
        ]);
    }

    /**
     * Delete a Department Notice.
     */
    public function deleteNotice(Request $request)
    {
        $userId = Session::get('userId');
        $role   = Session::get('userRole');

        if (!$userId || $role !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authorized.'], 403);
        }

        $request->validate([
            'notice_id' => 'required|integer|exists:department_notices,id',
        ]);

        DepartmentNotice::where('id', $request->notice_id)->delete();

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Notice deleted successfully.'
        ]);
    }
}
