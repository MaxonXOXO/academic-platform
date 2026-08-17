<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\DayOrderService;
use Carbon\Carbon;

class PrincipalDashboardController extends Controller
{
    /**
     * Target 6 major engineering departments
     */
    private const TARGET_BRANCHES = ['CT', 'EL', 'ME', 'CE', 'EEE', 'AU'];

    /**
     * Check if current user has executive access permissions.
     */
    private function checkAccess()
    {
        $role = Session::get('userRole');
        return in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin']);
    }

    /**
     * Render the Today's Institutional Timetable Desk page.
     */
    public function showTodayTimetable(Request $request)
    {
        if (!$this->checkAccess()) {
            return redirect('/');
        }

        $date = $request->query('date', Carbon::now()->toDateString());
        $activeDayOrder = DayOrderService::getActiveDayOrder($date);

        return view('principal_today_timetable', compact('date', 'activeDayOrder'));
    }

    /**
     * API Endpoint: Aggregates real-time timetable, staff assignment, 
     * student attendance, and subject coverage status for all 6 departments and 3 semesters.
     */
    public function getTodayTimetableData(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $targetDate = $request->query('date', Carbon::now()->toDateString());
        $activeDayOrder = DayOrderService::getActiveDayOrder($targetDate);

        // Fetch all classrooms from both Rev 2021 (<=2025) & Rev 2026 (>=2026) schemes
        $r21Classrooms = DB::table('class_management')
            ->whereIn('branch', array_merge(self::TARGET_BRANCHES, ['EE']))
            ->where('batch_year', '<=', 2025)
            ->get(['classroom_id', 'branch', 'current_semester', 'batch_year', 'tutor_mobile_no']);

        $r26Classrooms = DB::table('r26_class_management')
            ->whereIn('branch', array_merge(self::TARGET_BRANCHES, ['EE']))
            ->where('batch_year', '>=', 2026)
            ->get(['classroom_id', 'branch', 'current_semester', 'batch_year', 'tutor_mobile_no']);


        $allClassrooms = $r21Classrooms->concat($r26Classrooms);

        // Group classrooms by branch
        $branchesData = [];
        $totalScheduledSlots = 0;
        $totalConductedSlots = 0;
        $grandTotalPresent = 0;
        $grandTotalEnrolled = 0;

        foreach (self::TARGET_BRANCHES as $branch) {
            $branchCode = $branch;

            // Find classrooms for this branch (handling EE as EEE fallback)
            $classrooms = $allClassrooms->filter(function ($c) use ($branchCode) {
                return $c->branch === $branchCode || ($branchCode === 'EEE' && $c->branch === 'EE');
            })->sortBy('current_semester');

            $branchClassroomsData = [];

            foreach ($classrooms as $c) {
                $cId = $c->classroom_id;
                $semester = (int)$c->current_semester;
                $cIdClean = preg_replace('/[^a-zA-Z0-9_-]/', '', $cId);

                // Enrolled student count for this classroom
                $enrolledCount = DB::table('students')
                    ->where('classroom_id', $cId)
                    ->where('status', 'Active')
                    ->count();

                // Batch subjects for mapping code -> subject_name & staff
                $batchSubjects = DB::table('batch_subjects')
                    ->where('classroom_id', $cId)
                    ->get();

                $subjectIds = $batchSubjects->pluck('id')->toArray();

                // Class logs attendance records for target date
                $todayLogs = DB::table('class_logs_attendance')
                    ->whereIn('batch_subject_id', $subjectIds)
                    ->where('date', $targetDate)
                    ->get()
                    ->keyBy('period');

                // Read timetable JSON file
                $ttFile = storage_path("app/timetables/{$cIdClean}.json");
                $ttData = [];
                if (file_exists($ttFile)) {
                    $raw = json_decode(file_get_contents($ttFile), true);
                    if ($raw && isset($raw[$activeDayOrder])) {
                        $ttData = $raw[$activeDayOrder];
                    }
                }

                $periods = [];
                for ($p = 1; $p <= 6; $p++) {
                    $slot = $ttData[$p] ?? ($ttData[(string)$p] ?? null);
                    
                    $subCode = '';
                    $staffNameOrMobile = '';

                    if (is_array($slot)) {
                        $subCode = trim($slot['subject'] ?? ($slot['subject_code'] ?? ''));
                        $staffNameOrMobile = trim($slot['staff'] ?? ($slot['staff_name'] ?? ''));
                    } elseif (is_string($slot)) {
                        $subCode = trim($slot);
                    }

                    $matchedSubj = $batchSubjects->firstWhere('subject_code', $subCode);

                    // Resolve staff details
                    $staffName = $staffNameOrMobile;
                    $staffPhoto = null;
                    if ($matchedSubj && !empty($matchedSubj->staff_mobile_no)) {
                        $staffProfile = DB::table('staff_profiles')->where('mobile_no', $matchedSubj->staff_mobile_no)->first();
                        if ($staffProfile) {
                            $staffName = $staffProfile->name;
                            $staffPhoto = $staffProfile->photo_url ?? null;
                        }
                    } elseif (!empty($staffNameOrMobile) && preg_match('/^[0-9]{10}$/', $staffNameOrMobile)) {
                        $staffProfile = DB::table('staff_profiles')->where('mobile_no', $staffNameOrMobile)->first();
                        if ($staffProfile) {
                            $staffName = $staffProfile->name;
                            $staffPhoto = $staffProfile->photo_url ?? null;
                        }
                    }

                    // Check if class attendance log exists for this period
                    $log = $todayLogs->get($p);
                    $isMarked = false;
                    $presentCount = 0;
                    $absentCount = 0;
                    $attPct = 0.0;
                    $topicCovered = null;

                    if ($log) {
                        $isMarked = true;
                        $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                        $aList = json_decode($log->absent_students ?? '[]', true) ?: [];
                        $presentCount = count($pList);
                        $absentCount = count($aList);
                        $totalInLog = $presentCount + $absentCount;

                        $denom = $totalInLog > 0 ? $totalInLog : ($enrolledCount > 0 ? $enrolledCount : 1);
                        $attPct = round(($presentCount / $denom) * 100, 1);
                        $topicCovered = $log->topics_covered ?: 'Topic Logged (Details blank)';

                        $totalConductedSlots++;
                        $grandTotalPresent += $presentCount;
                        $grandTotalEnrolled += ($totalInLog > 0 ? $totalInLog : $enrolledCount);
                    }

                    if (!empty($subCode)) {
                        $totalScheduledSlots++;
                    }

                    $periods[$p] = [
                        'period' => $p,
                        'subject_code' => $subCode ?: 'FREE',
                        'subject_name' => $matchedSubj ? $matchedSubj->subject_name : ($subCode ?: 'Free Period'),
                        'staff_assigned' => $staffName ?: 'Not Assigned',
                        'staff_photo' => $staffPhoto,
                        'is_marked' => $isMarked,
                        'present_count' => $presentCount,
                        'absent_count' => $absentCount,
                        'enrolled_count' => $enrolledCount,
                        'attendance_percentage' => $attPct,
                        'topic_covered' => $topicCovered,
                        'status_label' => !empty($subCode) ? ($isMarked ? 'Conducted' : 'Pending') : 'Free'
                    ];
                }

                $branchClassroomsData[] = [
                    'classroom_id' => $cId,
                    'semester' => $semester,
                    'batch_year' => $c->batch_year,
                    'enrolled_students' => $enrolledCount,
                    'periods' => $periods
                ];
            }

            $branchesData[$branchCode] = [
                'branch_code' => $branchCode,
                'classrooms' => array_values($branchClassroomsData)
            ];
        }

        $overallAttendancePct = $grandTotalEnrolled > 0 
            ? round(($grandTotalPresent / $grandTotalEnrolled) * 100, 1) 
            : 0.0;

        $coveragePct = $totalScheduledSlots > 0 
            ? round(($totalConductedSlots / $totalScheduledSlots) * 100, 1) 
            : 0.0;

        return response()->json([
            'success' => true,
            'date' => $targetDate,
            'active_day_order' => $activeDayOrder,
            'summary' => [
                'total_scheduled_slots' => $totalScheduledSlots,
                'total_conducted_slots' => $totalConductedSlots,
                'coverage_percentage' => $coveragePct,
                'overall_attendance_percentage' => $overallAttendancePct,
                'total_departments' => count(self::TARGET_BRANCHES)
            ],
            'branches' => $branchesData
        ]);
    }
}
