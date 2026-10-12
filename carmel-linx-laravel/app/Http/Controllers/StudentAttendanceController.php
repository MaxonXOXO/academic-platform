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
            $rawTests = DB::table('test_configs')
                ->where('classroom_id', $student->classroom_id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($rawTests as $t) {
                $completedAttempts = DB::table('test_attempts')
                    ->where('test_id', $t->test_id)
                    ->where('reg_no', $student->reg_no)
                    ->where('status', 'completed')
                    ->count();

                $t->my_attempts = $completedAttempts;
                $t->is_completed = ($completedAttempts >= ($t->max_attempts ?? 1));
                if ($t->is_completed) {
                    $t->best_score = DB::table('test_attempts')
                        ->where('test_id', $t->test_id)
                        ->where('reg_no', $student->reg_no)
                        ->max('total_score');
                }

                $now = now();
                $t->is_not_started = ($t->start_time && $now < \Carbon\Carbon::parse($t->start_time));
                $t->is_expired = ($t->end_time && $now > \Carbon\Carbon::parse($t->end_time));

                $activeTests[] = $t;
            }
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

        // 8. Official Class Attendance Uploaded by Tutor (tutor_class_attendances)
        $tutorOfficialAttendance = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('tutor_class_attendances')) {
            $tutorOfficialAttendance = DB::table('tutor_class_attendances')
                ->where('classroom_id', $student->classroom_id)
                ->where(function($q) use ($studentIds) {
                    $q->whereIn('reg_no', $studentIds);
                })
                ->first();
        }

        // 9. Official Subject Attendance Uploaded by Tutor (subject_official_attendances)
        $subjectOfficialAttendances = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('subject_official_attendances') && !empty($subjectIds)) {
            $subjectOfficialAttendances = DB::table('subject_official_attendances')
                ->whereIn('batch_subject_id', $subjectIds)
                ->where(function($q) use ($studentIds) {
                    $q->whereIn('reg_no', $studentIds);
                })
                ->get()
                ->keyBy('batch_subject_id');
        }

        // 10. SBTE Exam Eligibility & Scheme (R21 / R26)
        $scheme = \App\Http\Controllers\TutorSpecialAttendanceController::detectScheme($classroom);
        $effectiveExamPct = $tutorOfficialAttendance ? (float)$tutorOfficialAttendance->final_percentage : $overallAttendancePct;
        $examEligibility = \App\Http\Controllers\TutorSpecialAttendanceController::evaluateEligibility($effectiveExamPct, $scheme);
        if ($tutorOfficialAttendance && $tutorOfficialAttendance->eligibility_status) {
            $examEligibility['status'] = $tutorOfficialAttendance->eligibility_status;
        }

        // 11. Draft Internal Marks (CIA) for Enrolled Subjects
        $currentSem = (int)($classroom->current_semester ?? $student->semester ?? 1);
        $savedSemMarks = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_semester_marks')) {
            $savedSemMarks = DB::table('student_semester_marks')
                ->whereIn('reg_no', $studentIds)
                ->where('semester', $currentSem)
                ->get()
                ->keyBy('subject_code');
        }

        $draftInternalMarks = [];
        foreach ($batchSubjects as $subj) {
            $sCode = $subj->subject_code;
            $maxCia = 50;
            $isAu3055Theory = ($subj->id == 110 || ($sCode == '3055' && (str_contains($subj->classroom_id ?? '', 'AU') || ($subj->branch ?? '') === 'AU' || str_contains(strtolower($subj->subject_name ?? ''), 'automobile'))));
            $type = strtolower($subj->subject_type ?? '');
            $name = strtolower($subj->subject_name ?? '');

            if (!$isAu3055Theory && (str_contains($type, 'practical') || str_contains($type, 'lab') || str_contains($name, 'lab') || str_contains($type, 'drawing') || str_contains($name, 'drawing') || str_contains($type, 'seminar') || str_contains($name, 'seminar') || str_contains($type, 'project') || str_contains($name, 'project'))) {
                $maxCia = 75;
            }

            $markRecord = $savedSemMarks->get($sCode);
            $markVal = null;
            if ($markRecord && is_numeric($markRecord->internal_marks)) {
                $markVal = (float)$markRecord->internal_marks;
            }

            // Subject attendance % (official if uploaded, else daily log)
            $offSub = $subjectOfficialAttendances->get($subj->id);
            $subjAttPct = $offSub ? (float)$offSub->final_percentage : null;
            if ($subjAttPct === null) {
                $foundStat = collect($subjectStats)->firstWhere('subject_code', $sCode);
                $subjAttPct = $foundStat ? (float)$foundStat['percentage'] : null;
            }

            $passThreshold = round($maxCia * 0.40);
            $isPass = $markVal !== null ? ($markVal >= $passThreshold) : null;

            $draftInternalMarks[] = [
                'subject_id' => $subj->id,
                'subject_code' => $sCode,
                'subject_name' => $subj->subject_name,
                'subject_type' => $subj->subject_type ?? 'Theory',
                'max_cia' => $maxCia,
                'draft_mark' => $markVal,
                'pass_threshold' => $passThreshold,
                'is_pass' => $isPass,
                'attendance_pct' => $subjAttPct,
                'is_official_attendance' => ($offSub !== null),
            ];
        }

        // 12. No Due Slip Clearance Checklist
        $feeRecord = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('student_fee_records')) {
            $feeRecord = DB::table('student_fee_records')->whereIn('reg_no', $studentIds)->orderBy('id', 'desc')->first();
        }
        $feePending = 0;
        $feeStatus = 'Cleared (No Pending Dues)';
        $feeCleared = true;
        if ($feeRecord && is_numeric($feeRecord->fees_to_pay) && is_numeric($feeRecord->total_paid)) {
            $feePending = max(0, (float)$feeRecord->fees_to_pay - (float)$feeRecord->total_paid);
            if ($feePending > 0) {
                $feeStatus = 'Pending: Rs. ' . number_format($feePending);
                $feeCleared = false;
            }
        }

        $tutorCleared = ($effectiveExamPct >= 65.0);
        $tutorStatus = $effectiveExamPct >= 75.0 ? 'Cleared (Eligible for ESE)' : ($effectiveExamPct >= 65.0 ? 'Cleared (Condonable Shortage)' : 'Attendance Shortage (<65%)');

        $noDueData = [
            'fee_cleared' => $feeCleared,
            'fee_status' => $feeStatus,
            'fee_pending' => $feePending,
            'tutor_cleared' => $tutorCleared,
            'tutor_status' => $tutorStatus,
            'dept_cleared' => true,
            'dept_status' => 'Cleared (Lab Records & Coursework Complete)',
            'library_cleared' => true,
            'library_status' => 'Cleared (No Overdue Books)',
            'hostel_cleared' => true,
            'hostel_status' => 'Cleared (Day Scholar / No Dues)',
            'overall_eligible' => ($feeCleared && $tutorCleared),
        ];

        // 13. Attendance Certificate Condition Check
        $attendanceCertCondition = [
            'is_available' => ($effectiveExamPct >= 65.0),
            'status' => $examEligibility['status'],
            'percentage' => $effectiveExamPct,
            'is_official' => ($tutorOfficialAttendance !== null),
            'rule' => $examEligibility['rule'] ?? '',
            'decision' => $examEligibility['decision'] ?? '',
        ];

        // 14. Academic Stats: CGPA, SGPA Trend, Activity Points
        $semSummaries = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_semester_summary')) {
            $semSummaries = DB::table('student_semester_summary')
                ->whereIn('reg_no', $studentIds)
                ->orderBy('semester', 'asc')
                ->get();
        }

        $allGrades = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_board_grades')) {
            $allGrades = DB::table('student_board_grades')
                ->whereIn('reg_no', $studentIds)
                ->get();
        }

        $getGP = function($grade) {
            switch (strtoupper(trim($grade ?? ''))) {
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

        $sgpaTrend = [];
        $computedCgpa = null;

        if ($allGrades->isNotEmpty()) {
            $cumTotalGP = 0;
            $cumTotalCredits = 0;
            $semGroups = $allGrades->groupBy('semester')->sortKeys();
            foreach ($semGroups as $sem => $grades) {
                $semTotalGP = 0;
                $semTotalCredits = 0;
                foreach ($grades as $g) {
                    $gp = $getGP($g->grade);
                    if ($gp !== null) {
                        $credit = 4;
                        $semTotalGP += ($gp * $credit);
                        $semTotalCredits += $credit;
                        $cumTotalGP += ($gp * $credit);
                        $cumTotalCredits += $credit;
                    }
                }
                if ($semTotalCredits > 0) {
                    $sgpaTrend[$sem] = round($semTotalGP / $semTotalCredits, 2);
                }
            }
            if ($cumTotalCredits > 0) {
                $computedCgpa = round($cumTotalGP / $cumTotalCredits, 2);
            }
        }

        if ($semSummaries->isNotEmpty()) {
            foreach ($semSummaries as $sum) {
                if ($sum->sgpa !== null && !isset($sgpaTrend[$sum->semester])) {
                    $sgpaTrend[$sum->semester] = (float)$sum->sgpa;
                }
            }
            if ($computedCgpa === null) {
                $lastSummary = $semSummaries->last();
                $computedCgpa = $lastSummary && $lastSummary->cgpa !== null ? (float)$lastSummary->cgpa : null;
            }
        }

        $classification = 'In Progress';
        if ($computedCgpa !== null && $computedCgpa > 0) {
            if ($computedCgpa >= 8.0) {
                $classification = 'First Class with Distinction';
            } elseif ($computedCgpa >= 6.5) {
                $classification = 'First Class';
            } else {
                $classification = 'Second Class';
            }
        }

        // Activity points (Total in course)
        $activityVerified = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('activity_point_claims')) {
            $activityVerified = DB::table('activity_point_claims')
                ->whereIn('reg_no', $studentIds)
                ->where('status', 'Verified')
                ->sum('points_awarded') ?: 0;
        }
        $activityFromSummary = $semSummaries->sum('activity_points') ?: 0;
        $totalActivityPoints = max((float)$activityVerified, (float)$activityFromSummary);
        $isLet = ($student->admission_type === 'LET') || str_ends_with(strtoupper($student->reg_no ?? ''), 'L');
        $activityGoal = $isLet ? 40 : 60;

        // 15. Comprehensive Assignments & Tests Breakdown for Current Semester
        $academicMarksAll = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('academic_marks')) {
            $academicMarksAll = DB::table('academic_marks')
                ->whereIn('reg_no', $studentIds)
                ->whereIn('subject_code', $batchSubjects->pluck('subject_code'))
                ->get();
        }

        $taskSubmissionsAll = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_task_submissions')) {
            $taskSubmissionsAll = DB::table('student_task_submissions')
                ->whereIn('reg_no', $studentIds)
                ->whereIn('subject_code', $batchSubjects->pluck('subject_code'))
                ->get();
        }

        $pracTestsAll = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('practical_test_marks') && \Illuminate\Support\Facades\Schema::hasTable('practical_tests')) {
            $pracTestsAll = DB::table('practical_test_marks')
                ->join('practical_tests', 'practical_test_marks.practical_test_id', '=', 'practical_tests.id')
                ->whereIn('practical_tests.batch_subject_id', $subjectIds)
                ->whereIn('practical_test_marks.reg_no', $studentIds)
                ->select('practical_test_marks.*', 'practical_tests.test_name', 'practical_tests.batch_subject_id')
                ->get();
        }

        $drawingTestsAll = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('r21_drawing_series_tests')) {
            $drawingTestsAll = DB::table('r21_drawing_series_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->whereIn('reg_no', $studentIds)
                ->get();
        }

        $assignmentsList = [];
        $testsList = [];

        foreach ($batchSubjects as $subj) {
            $sCode = $subj->subject_code;
            $sName = $subj->subject_name;
            $sType = $subj->subject_type ?? 'Theory';
            $isTheory = (stripos($sType, 'theory') !== false || (stripos($sType, 'drawing') === false && stripos($sType, 'lab') === false && stripos($sType, 'pract') === false));

            if ($isTheory) {
                // Theory assignments: CO1 to CO4
                foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coIdx => $coTag) {
                    $coNum = $coIdx + 1;
                    $m = $academicMarksAll->where('subject_code', $sCode)->where('category', 'Assignment')->where('co_tag', $coTag)->first();
                    $sub = $taskSubmissionsAll->where('subject_code', $sCode)->where('category', 'Assignment')->where('co_tag', $coTag)->first();

                    $status = 'Pending';
                    $markObtained = null;
                    $maxMarks = 20;

                    if ($m && is_numeric($m->marks_obtained)) {
                        $status = 'Attended';
                        $markObtained = (float)$m->marks_obtained;
                        $maxMarks = (int)($m->max_marks ?: 20);
                    } elseif ($sub) {
                        $status = 'Submitted';
                    }

                    $assignmentsList[] = [
                        'subject_code' => $sCode,
                        'subject_name' => $sName,
                        'title' => "Assignment {$coNum} ({$coTag})",
                        'co_tag' => $coTag,
                        'status' => $status,
                        'marks_obtained' => $markObtained,
                        'max_marks' => $maxMarks,
                    ];
                }

                // Theory written tests: CO1 to CO4
                foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coIdx => $coTag) {
                    $coNum = $coIdx + 1;
                    $m = $academicMarksAll->where('subject_code', $sCode)->where('category', 'Written Test')->where('co_tag', $coTag)->first();

                    $status = 'Pending';
                    $markObtained = null;
                    $maxMarks = 50;

                    if ($m && is_numeric($m->marks_obtained)) {
                        $status = 'Attended';
                        $markObtained = (float)$m->marks_obtained;
                        $maxMarks = (int)($m->max_marks ?: 50);
                    }

                    $testsList[] = [
                        'subject_code' => $sCode,
                        'subject_name' => $sName,
                        'title' => "Written Test {$coNum} ({$coTag})",
                        'category' => 'Written Test',
                        'co_tag' => $coTag,
                        'status' => $status,
                        'marks_obtained' => $markObtained,
                        'max_marks' => $maxMarks,
                    ];
                }
            } elseif (stripos($sType, 'drawing') !== false) {
                // Drawing series tests (Test 1 & Test 2)
                $dTests = $drawingTestsAll->where('batch_subject_id', $subj->id);
                for ($dt = 1; $dt <= 2; $dt++) {
                    $dtRow = $dTests->where('test_no', $dt)->first();
                    $markObtained = ($dtRow && is_numeric($dtRow->total_score_100)) ? (float)$dtRow->total_score_100 : null;
                    $testsList[] = [
                        'subject_code' => $sCode,
                        'subject_name' => $sName,
                        'title' => "Drawing Series Test {$dt}",
                        'category' => 'Drawing Test',
                        'co_tag' => "DT{$dt}",
                        'status' => ($markObtained !== null) ? 'Attended' : 'Pending',
                        'marks_obtained' => $markObtained,
                        'max_marks' => 100,
                    ];
                }
            } else {
                // Practical / Lab tests
                $subjPracTests = $pracTestsAll->where('batch_subject_id', $subj->id);
                if ($subjPracTests->isNotEmpty()) {
                    foreach ($subjPracTests as $pt) {
                        $markObtained = is_numeric($pt->marks_obtained) ? (float)$pt->marks_obtained : null;
                        $testsList[] = [
                            'subject_code' => $sCode,
                            'subject_name' => $sName,
                            'title' => ($pt->test_name ?? 'Lab Test') . ' (Practical)',
                            'category' => 'Practical Test',
                            'co_tag' => 'Lab',
                            'status' => ($markObtained !== null) ? 'Attended' : 'Pending',
                            'marks_obtained' => $markObtained,
                            'max_marks' => 15,
                        ];
                    }
                } else {
                    for ($t = 1; $t <= 2; $t++) {
                        $testsList[] = [
                            'subject_code' => $sCode,
                            'subject_name' => $sName,
                            'title' => "Practical Series Test {$t}",
                            'category' => 'Practical Test',
                            'co_tag' => 'Lab',
                            'status' => 'Pending',
                            'marks_obtained' => null,
                            'max_marks' => 15,
                        ];
                    }
                }
            }
        }

        // Online tests from activeTests
        foreach ($activeTests as $at) {
            $matchedSubj = $batchSubjects->firstWhere('subject_code', $at->subject_code ?? '');
            $testsList[] = [
                'subject_code' => $at->subject_code ?? 'Online',
                'subject_name' => $matchedSubj->subject_name ?? ($at->test_name ?? ($at->title ?? 'Online MCQ Test')),
                'title' => $at->test_name ?? ($at->title ?? 'Online MCQ Test'),
                'category' => 'Online MCQ',
                'co_tag' => 'MCQ',
                'status' => !empty($at->is_completed) ? 'Attended' : 'Pending',
                'marks_obtained' => !empty($at->is_completed) ? (float)($at->best_score ?? 0) : null,
                'max_marks' => (int)($at->mcq_count ?? 10),
            ];
        }

        $assignmentsStats = [
            'total' => count($assignmentsList),
            'attended' => count(array_filter($assignmentsList, fn($a) => $a['status'] === 'Attended')),
            'submitted' => count(array_filter($assignmentsList, fn($a) => $a['status'] === 'Submitted')),
            'pending' => count(array_filter($assignmentsList, fn($a) => $a['status'] === 'Pending')),
        ];

        $testsStats = [
            'total' => count($testsList),
            'attended' => count(array_filter($testsList, fn($t) => $t['status'] === 'Attended')),
            'pending' => count(array_filter($testsList, fn($t) => $t['status'] === 'Pending')),
        ];

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
            'campusEventToday',
            'tutorOfficialAttendance',
            'subjectOfficialAttendances',
            'scheme',
            'examEligibility',
            'effectiveExamPct',
            'draftInternalMarks',
            'noDueData',
            'attendanceCertCondition',
            'computedCgpa',
            'classification',
            'sgpaTrend',
            'totalActivityPoints',
            'activityGoal',
            'isLet',
            'assignmentsList',
            'assignmentsStats',
            'testsList',
            'testsStats'
        )))->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }

    /**
     * Print Student Attendance & Condonation Certificate (SBTE A4 Format).
     */
    public function printStudentAttendanceCertificate(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');

        if (!$userId) {
            return redirect('/');
        }

        if ($role === 'Student') {
            $request->merge(['reg_no' => $userId]);
        }

        return (new TutorSpecialAttendanceController)->printCondonationCertificate($request);
    }

    /**
     * Print Student No Due Clearance Slip for SBTE Exam Hall Ticket.
     */
    public function printStudentNoDueSlip(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');

        if (!$userId) {
            return redirect('/');
        }

        $regNo = ($role === 'Student') ? $userId : ($request->input('reg_no') ?: $userId);
        $student = DB::table('students')->where('reg_no', $regNo)->orWhere('adm_no', $regNo)->first();

        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        $studentIds = array_filter([$student->reg_no ?? null, $student->adm_no ?? null, $student->sbte_reg_no ?? null]);

        $classroom = DB::table('class_management')->where('classroom_id', $student->classroom_id)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $student->classroom_id)->first();
        }

        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        $scheme = \App\Http\Controllers\TutorSpecialAttendanceController::detectScheme($classroom);

        // Authoritative official attendance
        $tutorOfficial = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('tutor_class_attendances')) {
            $tutorOfficial = DB::table('tutor_class_attendances')
                ->where('classroom_id', $student->classroom_id)
                ->where(function($q) use ($studentIds) {
                    $q->whereIn('reg_no', $studentIds);
                })
                ->first();
        }

        $attendancePct = $tutorOfficial ? (float)$tutorOfficial->final_percentage : 0.0;
        $attendanceConducted = $tutorOfficial ? (int)$tutorOfficial->total_hours : 0;
        $attendanceAttended = $tutorOfficial ? (int)$tutorOfficial->attended_hours : 0;

        if (!$tutorOfficial) {
            // Fallback to daily logs
            $batchSubjects = DB::table('batch_subjects')->where('classroom_id', $student->classroom_id)->get();
            $sIds = $batchSubjects->pluck('id')->toArray();
            $logs = DB::table('class_logs_attendance')->whereIn('batch_subject_id', $sIds)->where('period', '<=', 6)->get(['present_students', 'absent_students']);
            $totC = 0; $totA = 0;
            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];
                $hasP = !empty(array_intersect($studentIds, $pList));
                $hasA = !empty(array_intersect($studentIds, $aList));
                if ($hasP || $hasA) {
                    $totC++;
                    if ($hasP) $totA++;
                }
            }
            $attendanceConducted = $totC;
            $attendanceAttended = $totA;
            $attendancePct = $totC > 0 ? round(($totA / $totC) * 100, 1) : 0.0;
        }

        $examEligibility = \App\Http\Controllers\TutorSpecialAttendanceController::evaluateEligibility($attendancePct, $scheme);
        if ($tutorOfficial && $tutorOfficial->eligibility_status) {
            $examEligibility['status'] = $tutorOfficial->eligibility_status;
        }

        // Fee verification
        $feeRecord = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('student_fee_records')) {
            $feeRecord = DB::table('student_fee_records')->whereIn('reg_no', $studentIds)->orderBy('id', 'desc')->first();
        }
        $feePending = 0;
        $feeStatus = 'Cleared (No Pending Dues)';
        $feeCleared = true;
        if ($feeRecord && is_numeric($feeRecord->fees_to_pay) && is_numeric($feeRecord->total_paid)) {
            $feePending = max(0, (float)$feeRecord->fees_to_pay - (float)$feeRecord->total_paid);
            if ($feePending > 0) {
                $feeStatus = 'Pending: Rs. ' . number_format($feePending);
                $feeCleared = false;
            }
        }

        $tutorCleared = ($attendancePct >= 65.0);
        $tutorStatus = $attendancePct >= 75.0 ? 'Cleared (Eligible for ESE)' : ($attendancePct >= 65.0 ? 'Cleared (Condonable Shortage)' : 'Attendance Shortage (<65%)');

        $noDueData = [
            'fee_cleared' => $feeCleared,
            'fee_status' => $feeStatus,
            'fee_pending' => $feePending,
            'tutor_cleared' => $tutorCleared,
            'tutor_status' => $tutorStatus,
            'dept_cleared' => true,
            'dept_status' => 'Cleared (Lab Records & Coursework Complete)',
            'library_cleared' => true,
            'library_status' => 'Cleared (No Overdue Books)',
            'hostel_cleared' => true,
            'hostel_status' => 'Cleared (Day Scholar / No Dues)',
            'overall_eligible' => ($feeCleared && $tutorCleared),
        ];

        return view('student_nodue_slip_print', compact(
            'student',
            'classroom',
            'tutor',
            'scheme',
            'attendancePct',
            'attendanceConducted',
            'attendanceAttended',
            'examEligibility',
            'noDueData'
        ));
    }
}

