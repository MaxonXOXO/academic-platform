<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StudentAttendanceController extends Controller
{
    /**
     * Show Mobile-Friendly Student Attendance Review Page.
     */
    public function showStudentAttendance()
    {
        if (Session::get('userRole') !== 'Student') {
            return redirect('/');
        }

        $regNo = Session::get('userId');
        $student = DB::table('students')->where('reg_no', $regNo)->orWhere('adm_no', $regNo)->first();

        if (!$student) {
            return redirect('/dashboard/student')->with('error', 'Student profile not found.');
        }

        // Classroom & Tutor details
        $classroom = DB::table('class_management')->where('classroom_id', $student->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $student->classroom_id)->first();
        }
        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        // Batch Subjects
        $batchSubjects = DB::table('batch_subjects')
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('subject_code', 'asc')
            ->get();

        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // 6-Hour Period Timings Definition
        $periodTimings = [
            1 => '9:00 AM – 10:00 AM',
            2 => '10:00 AM – 11:00 AM',
            3 => '11:10 AM – 12:10 PM',
            4 => '1:00 PM – 2:00 PM',
            5 => '2:00 PM – 3:00 PM',
            6 => '3:00 PM – 4:00 PM',
            7 => 'Special / Extra Class'
        ];

        // Today's Hour-Wise Attendance Grid
        $todayDate = now()->toDateString();
        $todayLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->where('date', $todayDate)
            ->orderBy('period', 'asc')
            ->get();

        $hourlyStatus = [];
        for ($p = 1; $p <= 6; $p++) {
            $hourlyStatus[$p] = [
                'period' => $p,
                'time_slot' => $periodTimings[$p],
                'status' => 'Not Marked',
                'subject_name' => 'Free Period',
                'subject_code' => '',
                'topic' => '',
                'badge_class' => 'bg-slate-800 text-slate-400 border border-slate-700'
            ];
        }

        // Period 7: Special / Remedial Hour
        $hourlyStatus[7] = [
            'period' => 7,
            'time_slot' => $periodTimings[7],
            'status' => 'Not Scheduled',
            'subject_name' => 'Special Hour (Remedial / Extra Class)',
            'subject_code' => 'P7',
            'topic' => 'Special / Remedial Session',
            'badge_class' => 'bg-slate-950 text-slate-500 border border-slate-800'
        ];

        $studentIds = array_filter([$student->reg_no ?? null, $student->adm_no ?? null]);

        foreach ($todayLogs as $log) {
            $period = (int)$log->period;
            if ($period >= 1 && $period <= 7) {
                $subj = $batchSubjects->firstWhere('id', $log->batch_subject_id);
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                $statusText = 'Absent';
                $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';

                if (!empty(array_intersect($studentIds, $pList))) {
                    $statusText = 'Present';
                    $badgeClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                } elseif (!empty(array_intersect($studentIds, $aList))) {
                    $statusText = 'Absent';
                    $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                }

                $hourlyStatus[$period] = [
                    'period' => $period,
                    'time_slot' => $periodTimings[$period],
                    'status' => $statusText,
                    'subject_name' => ($period === 7 ? '[Special 7th Hour] ' : '') . ($subj->subject_name ?? 'Class Session'),
                    'subject_code' => $subj->subject_code ?? '',
                    'topic' => $log->topics_covered ?? ($period === 7 ? 'Remedial / Extra Class' : 'Regular Session'),
                    'badge_class' => $badgeClass
                ];
            }
        }

        // Overall Attendance Calculation (Strictly Periods 1 to 6)
        $totalConductedClasses = 0;
        $totalAttendedClasses = 0;

        $subjectStats = [];
        foreach ($batchSubjects as $subj) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $subj->id)
                ->where('period', '<=', 6) // Standard 6-Hour Academic Day
                ->get(['present_students', 'absent_students']);

            $subjConducted = 0;
            $subjAttended = 0;

            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];

                $hasP = !empty(array_intersect($studentIds, $pList));
                $hasA = !empty(array_intersect($studentIds, $aList));

                if ($hasP || $hasA) {
                    $subjConducted++;
                    $totalConductedClasses++;
                    if ($hasP) {
                        $subjAttended++;
                        $totalAttendedClasses++;
                    }
                }
            }

            $subjPct = $subjConducted > 0 ? round(($subjAttended / $subjConducted) * 100, 1) : 0.0;
            $subjectStats[] = [
                'subject_code' => $subj->subject_code,
                'subject_name' => $subj->subject_name,
                'conducted' => $subjConducted,
                'attended' => $subjAttended,
                'percentage' => $subjPct
            ];
        }

        $overallAttendancePct = $totalConductedClasses > 0 
            ? round(($totalAttendedClasses / $totalConductedClasses) * 100, 1) 
            : 0.0;

        // Fetch Student Leave Request Records
        $leaveRecords = \App\Models\LeaveRecord::whereIn('reg_no', $studentIds)
            ->orderBy('leave_date', 'desc')
            ->get();

        return view('student_attendance', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'subjectStats',
            'periodTimings',
            'leaveRecords'
        ));
    }

    /**
     * Show Mobile-First Student Web App Dashboard.
     */
    public function showStudentMobileDashboard(Request $request)
    {
        if (Session::get('userRole') !== 'Student') {
            return redirect('/');
        }

        $regNo = Session::get('userId');
        $student = DB::table('students')->where('reg_no', $regNo)->orWhere('adm_no', $regNo)->first();

        if (!$student) {
            return redirect('/dashboard/student')->with('error', 'Student profile not found.');
        }

        $studentIds = array_filter([$student->reg_no ?? null, $student->adm_no ?? null]);

        // Classroom & Tutor details
        $classroom = DB::table('class_management')->where('classroom_id', $student->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $student->classroom_id)->first();
        }
        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        // Batch Subjects
        $batchSubjects = DB::table('batch_subjects')
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('subject_code', 'asc')
            ->get();

        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // 6-Hour Period Timings Definition
        $periodTimings = [
            1 => '9:00 AM – 10:00 AM',
            2 => '10:00 AM – 11:00 AM',
            3 => '11:10 AM – 12:10 PM',
            4 => '1:00 PM – 2:00 PM',
            5 => '2:00 PM – 3:00 PM',
            6 => '3:00 PM – 4:00 PM',
            7 => 'Special / Extra Class'
        ];

        // 7. Active Universal Day Order & Student Classroom Timetable
        $activeDayOrder = \App\Services\DayOrderService::getActiveDayOrder();

        $dayMap = [
            'Monday' => 'Day 1',
            'Tuesday' => 'Day 2',
            'Wednesday' => 'Day 3',
            'Thursday' => 'Day 4',
            'Friday' => 'Day 5',
        ];

        // Today's Hour-Wise Attendance Grid
        $todayDate = now()->toDateString();
        $todayLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->where('date', $todayDate)
            ->orderBy('period', 'asc')
            ->get();

        // Load student classroom timetable for activeDayOrder
        $classTtSlots = [];
        if ($student->classroom_id) {
            $cIdClean = preg_replace('/[^a-zA-Z0-9_-]/', '', $student->classroom_id);
            $ttFile = storage_path("app/timetables/{$cIdClean}.json");
            if (file_exists($ttFile)) {
                $rawTt = json_decode(file_get_contents($ttFile), true);
                if ($rawTt) {
                    $dayAlt = array_search($activeDayOrder, $dayMap);
                    $dayData = $rawTt[$activeDayOrder] ?? ($dayAlt ? ($rawTt[$dayAlt] ?? null) : null);
                    if ($dayData && is_array($dayData)) {
                        $classTtSlots = $dayData;
                    }
                }
            }
        }

        $hourlyStatus = [];
        for ($p = 1; $p <= 6; $p++) {
            $slotDetails = $classTtSlots[$p] ?? null;
            $ttSubCode = '';
            if (is_array($slotDetails)) {
                if (!empty($slotDetails['is_parallel']) && !empty($slotDetails['parallel_labs'])) {
                    $pCodes = [];
                    foreach ($slotDetails['parallel_labs'] as $pLab) {
                        if (!empty($pLab['subject'])) $pCodes[] = trim($pLab['subject']);
                    }
                    $ttSubCode = implode(' / ', array_unique($pCodes));
                } else {
                    $ttSubCode = $slotDetails['subject'] ?? ($slotDetails['subject_code'] ?? '');
                }
            } else {
                $ttSubCode = $slotDetails;
            }
            $matchedSub = $batchSubjects->firstWhere('subject_code', $ttSubCode);

            $hourlyStatus[$p] = [
                'period' => $p,
                'time_slot' => $periodTimings[$p],
                'status' => 'Not Marked',
                'subject_name' => $matchedSub->subject_name ?? ($ttSubCode ?: 'Free Period'),
                'subject_code' => $ttSubCode ?: '',
                'topic' => $ttSubCode ? 'Scheduled Class Slot' : 'Free Period',
                'badge_class' => 'bg-slate-800 text-slate-400 border border-slate-700'
            ];
        }

        // Period 7: Special / Remedial Hour
        $hourlyStatus[7] = [
            'period' => 7,
            'time_slot' => $periodTimings[7],
            'status' => 'Not Scheduled',
            'subject_name' => 'Special Hour (Remedial / Extra Class)',
            'subject_code' => 'P7',
            'topic' => 'Special / Remedial Session',
            'badge_class' => 'bg-slate-950 text-slate-500 border border-slate-800'
        ];

        foreach ($todayLogs as $log) {
            $period = (int)$log->period;
            if ($period >= 1 && $period <= 7) {
                $subj = $batchSubjects->firstWhere('id', $log->batch_subject_id);
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                $statusText = 'Absent';
                $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';

                if (!empty(array_intersect($studentIds, $pList))) {
                    $statusText = 'Present';
                    $badgeClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                } elseif (!empty(array_intersect($studentIds, $aList))) {
                    $statusText = 'Absent';
                    $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                }

                $hourlyStatus[$period] = [
                    'period' => $period,
                    'time_slot' => $periodTimings[$period],
                    'status' => $statusText,
                    'subject_name' => ($period === 7 ? '[Special 7th Hour] ' : '') . ($subj->subject_name ?? 'Class Session'),
                    'subject_code' => $subj->subject_code ?? '',
                    'topic' => $log->topics_covered ?? ($period === 7 ? 'Remedial / Extra Class' : 'Regular Session'),
                    'badge_class' => $badgeClass
                ];
            }
        }

        // Overall Attendance Calculation (Strictly Periods 1 to 6)
        $totalConductedClasses = 0;
        $totalAttendedClasses = 0;

        $subjectStats = [];
        foreach ($batchSubjects as $subj) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $subj->id)
                ->where('period', '<=', 6) // Standard 6-Hour Academic Day
                ->get(['present_students', 'absent_students']);

            $subjConducted = 0;
            $subjAttended = 0;

            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];

                $hasP = !empty(array_intersect($studentIds, $pList));
                $hasA = !empty(array_intersect($studentIds, $aList));

                if ($hasP || $hasA) {
                    $subjConducted++;
                    $totalConductedClasses++;
                    if ($hasP) {
                        $subjAttended++;
                        $totalAttendedClasses++;
                    }
                }
            }

            $subjPct = $subjConducted > 0 ? round(($subjAttended / $subjConducted) * 100, 1) : 0.0;
            $subjectStats[] = [
                'subject_code' => $subj->subject_code,
                'subject_name' => $subj->subject_name,
                'conducted' => $subjConducted,
                'attended' => $subjAttended,
                'percentage' => $subjPct
            ];
        }

        $overallAttendancePct = $totalConductedClasses > 0 
            ? round(($totalAttendedClasses / $totalConductedClasses) * 100, 1) 
            : 0.0;

        // Fetch Student Leave Request Records
        $leaveRecords = \App\Models\LeaveRecord::whereIn('reg_no', $studentIds)
            ->orderBy('leave_date', 'desc')
            ->get();

        // Active Online Tests from test_configs
        $activeTests = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('test_configs')) {
            $activeTests = DB::table('test_configs')
                ->where('classroom_id', $student->classroom_id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Check for active campus-wide event today for students
        $todayDate = date('Y-m-d');
        $userBranch = $student->branch ?? Session::get('userBranch', 'ALL');
        $campusEventToday = \App\Models\PrincipalScheduledEvent::where('is_published', true)
            ->where(function ($q) use ($todayDate) {
                $q->where(function ($q1) use ($todayDate) {
                    $q1->whereNull('end_date')
                       ->where('event_date', $todayDate);
                })->orWhere(function ($q2) use ($todayDate) {
                    $q2->whereNotNull('end_date')
                       ->where('event_date', '<=', $todayDate)
                       ->where('end_date', '>=', $todayDate);
                });
            })
            ->where(function($q) use ($userBranch) {
                $q->where('target_audience', 'ALL_CAMPUS')
                  ->orWhere('target_audience', 'STUDENTS_ONLY')
                  ->orWhere(function($q2) use ($userBranch) {
                      $q2->where('target_audience', 'DEPT_SPECIFIC')
                         ->where(function($q3) use ($userBranch) {
                             $q3->where('target_department', 'ALL')
                                ->orWhere('target_department', $userBranch);
                         });
                  });
            })
            ->orderBy('created_at', 'desc')
            ->first();

        return response(view('student_mobile_dashboard', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'subjectStats',
            'periodTimings',
            'leaveRecords',
            'activeTests',
            'activeDayOrder',
            'campusEventToday'
        )))->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }
}
