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

        // Determine if HOD belongs to Self-Financing department: AU, EL, CT, GEN_SF
        $deptUpper = strtoupper(trim($dept));
        $sfBranches = ['AU', 'EL', 'CT', 'GEN_SF', 'SF', 'AUTOMOBILE', 'ELECTRONICS', 'COMPUTER', 'COMPUTER TECH'];
        $isSfHod = in_array($deptUpper, $sfBranches) || str_contains($deptUpper, 'SF') || str_contains($deptUpper, 'SELF');

        // Fetch Today's Biometric Attendance Punch Record for SF HOD
        $todayPunch = null;
        $inTimeFormatted = null;
        $outTimeFormatted = null;
        $isPunchedIn = false;
        $isPunchedOut = false;
        $isCompleted = false;
        $inStatusLabel = 'PRESENT';
        $outStatusLabel = 'OUT RECORDED';
        $campusHours = null;

        if ($isSfHod && \Illuminate\Support\Facades\Schema::hasTable('sf_staff_time_punches')) {
            $staffId = Session::get('userStaffId') ?? Session::get('mobileNo') ?? $userId;
            $todayPunch = \App\Models\SfStaffTimePunch::where(function($q) use ($staffId, $userId) {
                $q->where('staff_id', $staffId);
                if ($userId) {
                    $q->orWhere('staff_id', $userId);
                }
            })
            ->where('punch_date', now()->format('Y-m-d'))
            ->first();

            if ($todayPunch) {
                $inTimeFormatted = !empty($todayPunch->in_time) ? date('h:i A', strtotime($todayPunch->in_time)) : null;
                $outTimeFormatted = !empty($todayPunch->out_time) ? date('h:i A', strtotime($todayPunch->out_time)) : null;

                $isPunchedIn = !empty($inTimeFormatted);
                $isPunchedOut = !empty($outTimeFormatted);
                $isCompleted = $isPunchedIn && $isPunchedOut;

                if ($isPunchedIn && !empty($todayPunch->in_time)) {
                    $inHi = date('H:i', strtotime($todayPunch->in_time));
                    if ($inHi < '08:45') {
                        $inStatusLabel = 'EARLY IN';
                    } elseif ($inHi > '09:15') {
                        $inStatusLabel = 'LATE IN';
                    } else {
                        $inStatusLabel = 'PRESENT';
                    }
                }

                if ($isPunchedOut && !empty($todayPunch->out_time)) {
                    $outHi = date('H:i', strtotime($todayPunch->out_time));
                    if ($outHi < '16:00') {
                        $outStatusLabel = 'EARLY OUT';
                    } elseif ($outHi > '16:30') {
                        $outStatusLabel = 'LATE OUT';
                    } else {
                        $outStatusLabel = 'ON TIME OUT';
                    }
                }

                if ($isCompleted && !empty($todayPunch->in_time) && !empty($todayPunch->out_time)) {
                    $tIn = strtotime($todayPunch->punch_date . ' ' . $todayPunch->in_time);
                    $tOut = strtotime($todayPunch->punch_date . ' ' . $todayPunch->out_time);
                    $diffSec = max(0, $tOut - $tIn);
                    $hrs = floor($diffSec / 3600);
                    $mins = round(($diffSec % 3600) / 60);
                    $campusHours = "{$hrs}h {$mins}m in Campus";
                } elseif ($isPunchedIn && !empty($todayPunch->in_time)) {
                    $tIn = strtotime(($todayPunch->punch_date ?? date('Y-m-d')) . ' ' . $todayPunch->in_time);
                    $diffSec = max(0, time() - $tIn);
                    $hrs = floor($diffSec / 3600);
                    $mins = round(($diffSec % 3600) / 60);
                    $campusHours = "{$hrs}h {$mins}m in Campus";
                }
            }
        }

        // 1. My Teaching Subjects (HOD acting as Faculty)
        $mySubjects = DB::table('subject_staff_assignments')
            ->join('batch_subjects', 'subject_staff_assignments.batch_subject_id', '=', 'batch_subjects.id')
            ->where('subject_staff_assignments.staff_mobile_no', $userId)
            ->select('batch_subjects.*', 'subject_staff_assignments.batch_subject_id')
            ->get();

        // 2. Department Classroom Batches (R21 for <= 2025 & R26 for >= 2026)
        $deptBatches2021 = ClassManagement::where(function($q) use ($dept) {
                $q->where('branch', $dept)->orWhere('classroom_id', 'like', "{$dept}%");
            })
            ->where('batch_year', '<=', 2025)
            ->orderBy('batch_year', 'desc')
            ->get();

        $deptBatches2026 = DB::table('r26_class_management')
            ->where(function($q) use ($dept) {
                $q->where('branch', $dept)->orWhere('classroom_id', 'like', "{$dept}%");
            })
            ->where('batch_year', '>=', 2026)
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

        // 3. Pending & Recent Staff Leave Applications for HOD's Department
        $deptStaffMobiles = StaffProfile::where('branch', $dept)->pluck('mobile_no')->toArray();
        $pendingStaffLeaves = StaffLeaveRequest::where(function($q) use ($dept, $deptStaffMobiles) {
                $q->where('department', $dept)
                  ->orWhere('department', 'like', "%{$dept}%");
                if (!empty($deptStaffMobiles)) {
                    $q->orWhereIn('staff_mobile', $deptStaffMobiles);
                }
            })
            ->where('overall_status', 'Pending_HOD')
            ->orderByDesc('id')
            ->get();

        $recentStaffLeaves = StaffLeaveRequest::where(function($q) use ($dept, $deptStaffMobiles) {
                $q->where('department', $dept)
                  ->orWhere('department', 'like', "%{$dept}%");
                if (!empty($deptStaffMobiles)) {
                    $q->orWhereIn('staff_mobile', $deptStaffMobiles);
                }
            })
            ->where('overall_status', '!=', 'Pending_HOD')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        // 3b. Self-Financing Coordinator Pending Leaves (for Jacob Kurian or designated SF Coordinator)
        $isSfCoordinator = StaffProfile::isSfAcademicCoordinator($userId);
        $pendingSfCoordinatorLeaves = collect();
        $recentSfCoordinatorLeaves = collect();

        if ($isSfCoordinator) {
            $pendingSfCoordinatorLeaves = StaffLeaveRequest::where('overall_status', 'Pending_Coordinator')
                ->orderByDesc('id')
                ->get();

            $recentSfCoordinatorLeaves = StaffLeaveRequest::where(function($q) use ($userId) {
                    $q->where('coordinator_mobile', $userId)
                      ->orWhere(function($sub) {
                          $sub->where('coordinator_status', '!=', 'Pending')
                              ->whereNotNull('coordinator_action_at');
                      });
                })
                ->orderByDesc('coordinator_action_at')
                ->take(8)
                ->get();
        }

        // 4. Pending & Recent Student Leaves across Department Classrooms
        $deptClassroomIds = $deptBatches->pluck('classroom_id')->toArray();
        $deptStudentRegNos = Student::where(function($q) use ($dept, $deptClassroomIds) {
                $q->whereIn('classroom_id', $deptClassroomIds)
                  ->orWhere('branch', $dept)
                  ->orWhere('classroom_id', 'like', "{$dept}%");
            })->pluck('reg_no');

        $pendingStudentLeaves = LeaveRecord::whereIn('reg_no', $deptStudentRegNos)
            ->where('status', 'Pending')
            ->orderByDesc('leave_date')
            ->get()
            ->map(function ($l) {
                $l->student_name = Student::where('reg_no', $l->reg_no)->value('name') ?? $l->reg_no;
                return $l;
            });

        $recentStudentLeaves = LeaveRecord::whereIn('reg_no', $deptStudentRegNos)
            ->where('status', '!=', 'Pending')
            ->orderByDesc('leave_date')
            ->take(8)
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
        $defaultDayOrder = \App\Services\DayOrderService::getActiveDayOrder();

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
            $dayMap = ['Monday' => 'Day 1', 'Tuesday' => 'Day 2', 'Wednesday' => 'Day 3', 'Thursday' => 'Day 4', 'Friday' => 'Day 5'];
            // Revision 2021 Sem 3 and Sem 5 regular classes concluded on 6 October 2026.
            // Semester exams commence 13 October onwards.
            // Stop display of 2021 timetables; keep 2026 (Sem 1) timetable as usual.
            $isRev2021Sem = in_array((int)$sem, [3, 5]) ||
                ($classroom && (str_contains($classroom->classroom_id, '2024') || str_contains($classroom->classroom_id, '2025')));

            if (!$isRev2021Sem && $classroom && !empty($classroom->classroom_id)) {
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
                        $subCode = '';
                        $staffName = '';
                        if (is_array($slotData)) {
                            if (!empty($slotData['is_parallel']) && !empty($slotData['parallel_labs'])) {
                                $pCodes = [];
                                $pStaff = [];
                                foreach ($slotData['parallel_labs'] as $pLab) {
                                    if (!empty($pLab['subject'])) $pCodes[] = trim($pLab['subject']);
                                    if (!empty($pLab['staff'])) {
                                        $stArr = is_array($pLab['staff']) ? $pLab['staff'] : explode(',', $pLab['staff']);
                                        foreach ($stArr as $st) { if ($st) $pStaff[] = trim($st); }
                                    }
                                }
                                $subCode = implode(' / ', array_unique($pCodes));
                                $staffName = implode(', ', array_unique($pStaff));
                            } else {
                                $subCode = $slotData['subject'] ?? ($slotData['subject_code'] ?? '');
                                $staffName = $slotData['staff'] ?? '';
                            }
                        } else {
                            $subCode = $slotData;
                        }

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
                        // NO timetable or classes concluded
                        $periodsData[$p] = [
                            'period'       => $p,
                            'time_slot'    => $periodTimings[$p],
                            'subject_code' => $isRev2021Sem ? '—' : 'FREE',
                            'subject_name' => $isRev2021Sem ? 'Classes Concluded (Exams 13 Oct)' : 'Free Period',
                            'staff_name'   => '—',
                            'topic'        => $isRev2021Sem ? 'Rev 2021 regular classes concluded on 06 Oct 2026' : 'No Class Scheduled',
                            'status'       => $isRev2021Sem ? 'Concluded' : 'Free',
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
            'recentStaffLeaves',
            'pendingStudentLeaves',
            'recentStudentLeaves',
            'deptStaff',
            'notices',
            'upcomingSeminars',
            'defaultDayOrder',
            'semesterSchedules',
            'isSfHod',
            'todayPunch',
            'inTimeFormatted',
            'outTimeFormatted',
            'isPunchedIn',
            'isPunchedOut',
            'isCompleted',
            'inStatusLabel',
            'outStatusLabel',
            'campusHours',
            'isSfCoordinator',
            'pendingSfCoordinatorLeaves',
            'recentSfCoordinatorLeaves'
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
