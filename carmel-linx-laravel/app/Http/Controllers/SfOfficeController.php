<?php

namespace App\Http\Controllers;

use App\Models\StaffLeaveRequest;
use App\Models\StaffProfile;
use App\Models\SfStaffTimePunch;
use App\Models\SfStaffCclCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SfOfficeController extends Controller
{
    /**
     * Check authorization for SF Office.
     */
    private function checkAccess()
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId) {
            return false;
        }

        $allowedRoles = [
            'SF_Office', 'Self_Office', 'Office_SF', 'Office',
            'Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF',
            'Super_Admin', 'Principal', 'Admin', 'Chairman'
        ];

        if (in_array($userRole, $allowedRoles) || $userId === '9000000009') {
            return true;
        }

        if (StaffProfile::isSfAcademicCoordinator($userId)) {
            return true;
        }

        return false;
    }

    /**
     * Get active Leave Financial Year dates (April 1 to March 31).
     */
    public static function getLeaveCycleDates($referenceDate = null)
    {
        $date = $referenceDate ? \Carbon\Carbon::parse($referenceDate) : now();
        $startMd = DB::table('system_settings')->where('key', 'sf_office_leave_year_start')->value('value') ?: '04-01';
        $endMd   = DB::table('system_settings')->where('key', 'sf_office_leave_year_end')->value('value') ?: '03-31';

        $startParts = explode('-', $startMd);
        $startMonth = (int)($startParts[0] ?? 4);
        $startDay   = (int)($startParts[1] ?? 1);

        $curYear = (int)$date->format('Y');
        $curMonth = (int)$date->format('m');

        if ($curMonth >= $startMonth) {
            $cycleStart = sprintf('%04d-%02d-%02d', $curYear, $startMonth, $startDay);
            $cycleEnd   = sprintf('%04d-%s', $curYear + 1, $endMd);
            $label      = $curYear . ' - ' . ($curYear + 1);
        } else {
            $cycleStart = sprintf('%04d-%02d-%02d', $curYear - 1, $startMonth, $startDay);
            $cycleEnd   = sprintf('%04d-%s', $curYear, $endMd);
            $label      = ($curYear - 1) . ' - ' . $curYear;
        }

        return [
            'start' => $cycleStart,
            'end'   => $cycleEnd,
            'label' => $label,
        ];
    }

    /**
     * Main SF Office Dashboard view.
     */
    public function index(Request $request)
    {
        if (!$this->checkAccess()) {
            return redirect('/')->with('error', 'Unauthorized access to SF Office Console.');
        }

        $cycle = self::getLeaveCycleDates();
        $staffCount = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF'])->count();
        $today = now()->format('Y-m-d');
        $todayPunches = SfStaffTimePunch::where('punch_date', $today)->count();

        $pendingApprovals = StaffLeaveRequest::where(function($q) {
            $q->where('office_status', 'Pending')
              ->orWhereNull('office_status');
        })
        ->where('overall_status', '!=', 'Rejected')
        ->where('is_historical', false)
        ->whereIn('department', ['EL', 'CT', 'AU', 'GEN_SF', 'SF'])
        ->count();

        $cclQuotaSetting = (float)(DB::table('system_settings')->where('key', 'sf_office_annual_cl_quota')->value('value') ?: 15);

        return view('sf_office_dashboard', [
            'cycle'            => $cycle,
            'staffCount'       => $staffCount,
            'todayPunches'     => $todayPunches,
            'pendingApprovals' => $pendingApprovals,
            'clQuota'          => $cclQuotaSetting,
            'userName'         => Session::get('userName', 'SF Office Administrator'),
            'userRole'         => Session::get('userRole', 'SF_Office'),
            'userId'           => Session::get('userId', '9000000009'),
        ]);
    }

    /**
     * API: Get dashboard summary statistics.
     */
    public function getStats(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $cycle = self::getLeaveCycleDates();
        $today = now()->format('Y-m-d');

        $sfStaffMobiles = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF'])
            ->pluck('mobile_no')
            ->toArray();

        $todayPunchesCount = SfStaffTimePunch::where('punch_date', $today)
            ->whereIn('staff_id', $sfStaffMobiles)
            ->count();

        // Staff on leave today
        $onLeaveTodayCount = StaffLeaveRequest::where('overall_status', 'Approved')
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->whereIn('staff_mobile', $sfStaffMobiles)
            ->count();

        // Pending office approval count
        $pendingOfficeCount = StaffLeaveRequest::where(function($q) {
            $q->where('office_status', 'Pending')
              ->orWhereNull('office_status');
        })
        ->where('overall_status', '!=', 'Rejected')
        ->where('is_historical', false)
        ->whereIn('staff_mobile', $sfStaffMobiles)
        ->count();

        // Active valid CCL credits in system
        $activeCclCount = SfStaffCclCredit::where('status', 'Active')
            ->where('valid_until', '>=', $today)
            ->sum(DB::raw('earned_days - used_days'));

        // Past backfill records count this cycle
        $pastBackfillsCount = StaffLeaveRequest::where('is_historical', true)
            ->where('from_date', '>=', $cycle['start'])
            ->where('from_date', '<=', $cycle['end'])
            ->count();

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'total_staff'         => count($sfStaffMobiles),
                'today_punches'       => $todayPunchesCount,
                'on_leave_today'      => $onLeaveTodayCount,
                'pending_office'      => $pendingOfficeCount,
                'active_ccl_pool'     => (float)$activeCclCount,
                'past_backfills_count'=> $pastBackfillsCount,
                'cycle'               => $cycle,
            ]
        ]);
    }

    /**
     * API: Get all Self-Financing staff with their live leave balances.
     */
    public function getStaffList(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $cycle = self::getLeaveCycleDates();
        $clQuota = (float)(DB::table('system_settings')->where('key', 'sf_office_annual_cl_quota')->value('value') ?: 15);
        $today = now()->format('Y-m-d');

        $staff = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF'])
            ->orderBy('branch')
            ->orderBy('name')
            ->get();

        $result = [];
        foreach ($staff as $s) {
            // Calculate CL taken in current cycle
            $clTaken = (float) StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where('leave_type', 'Casual Leave')
                ->where('overall_status', 'Approved')
                ->where('from_date', '>=', $cycle['start'])
                ->where('from_date', '<=', $cycle['end'])
                ->sum('total_days');

            // Calculate active valid CCL balance
            $cclCredits = SfStaffCclCredit::where('staff_mobile', $s->mobile_no)
                ->where('status', 'Active')
                ->where('valid_until', '>=', $today)
                ->sum(DB::raw('earned_days - used_days'));

            // Calculate CCL taken
            $cclTaken = (float) StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where(function($q) {
                    $q->where('leave_type', 'like', '%Compensatory%')
                      ->orWhere('leave_type', 'like', '%CCL%');
                })
                ->where('overall_status', 'Approved')
                ->where('from_date', '>=', $cycle['start'])
                ->where('from_date', '<=', $cycle['end'])
                ->sum('total_days');

            // Calculate Duty Leave taken
            $dlTaken = (float) StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where(function($q) {
                    $q->where('leave_type', 'like', '%Duty%')
                      ->orWhere('leave_type', 'like', '%DL%');
                })
                ->where('overall_status', 'Approved')
                ->where('from_date', '>=', $cycle['start'])
                ->where('from_date', '<=', $cycle['end'])
                ->sum('total_days');

            // Total all leaves taken
            $totalTaken = (float) StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where('overall_status', 'Approved')
                ->where('from_date', '>=', $cycle['start'])
                ->where('from_date', '<=', $cycle['end'])
                ->sum('total_days');

            $result[] = [
                'mobile_no'     => $s->mobile_no,
                'name'          => $s->name,
                'branch'        => $s->branch,
                'designation'   => $s->designation,
                'photo_url'     => $s->photo_url,
                'is_online'     => (bool)$s->is_online,
                'cl_quota'      => $clQuota,
                'cl_taken'      => $clTaken,
                'cl_remaining'  => max(0.0, $clQuota - $clTaken),
                'ccl_balance'   => (float)$cclCredits,
                'ccl_taken'     => $cclTaken,
                'dl_taken'      => $dlTaken,
                'total_taken'   => $totalTaken,
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'cycle'  => $cycle,
            'staff'  => $result,
        ]);
    }

    /**
     * API: Get Time Punch logs for SF staff (Strict Privacy: NO face snapshot images in office view).
     */
    public function getPunchLogs(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $dateFrom   = $request->query('date_from', now()->subDays(7)->format('Y-m-d'));
        $dateTo     = $request->query('date_to', now()->format('Y-m-d'));
        $staffId    = $request->query('staff_id');
        $branch     = $request->query('branch');
        $holidayOnly= $request->query('holiday_only');

        $sfStaffMobiles = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF']);
        if (!empty($branch)) {
            $sfStaffMobiles->where('branch', strtoupper($branch));
        }
        $sfStaffList = $sfStaffMobiles->pluck('name', 'mobile_no')->toArray();
        $sfMobiles = array_keys($sfStaffList);

        $query = SfStaffTimePunch::whereIn('staff_id', $sfMobiles)
            ->whereBetween('punch_date', [$dateFrom, $dateTo]);

        if (!empty($staffId)) {
            $query->where('staff_id', $staffId);
        }

        // Strictly select columns WITHOUT face images
        $punches = $query->select([
            'id',
            'staff_id',
            'staff_name',
            'punch_date',
            'in_time',
            'out_time',
            'in_gps_distance_meters',
            'in_premises_status',
            'out_gps_distance_meters',
            'out_premises_status',
            'punch_status',
            'remarks',
            'created_at'
        ])
        ->orderByDesc('punch_date')
        ->orderByDesc('in_time')
        ->get();

        // Fetch staff profiles for department/branch
        $staffMap = StaffProfile::whereIn('mobile_no', $sfMobiles)
            ->select('mobile_no', 'branch', 'designation')
            ->get()
            ->keyBy('mobile_no');

        // Fetch existing credited CCL punches
        $creditedPunches = SfStaffCclCredit::whereIn('staff_mobile', $sfMobiles)
            ->whereNotNull('source_punch_id')
            ->pluck('source_punch_id')
            ->toArray();

        $rows = [];
        foreach ($punches as $p) {
            $pDate = \Carbon\Carbon::parse($p->punch_date);
            $dayOfWeek = (int)$pDate->format('w'); // 0 = Sun, 6 = Sat
            $isSunday = ($dayOfWeek === 0);
            $isSaturday = ($dayOfWeek === 6);
            $isHoliday = $isSunday || $isSaturday;

            if ($holidayOnly && !$isHoliday) {
                continue;
            }

            // Duration calculation
            $durationStr = 'In Progress';
            $durationHours = 0.0;
            if ($p->in_time && $p->out_time) {
                $inT  = strtotime($p->punch_date . ' ' . $p->in_time);
                $outT = strtotime($p->punch_date . ' ' . $p->out_time);
                if ($outT > $inT) {
                    $diffSec = $outT - $inT;
                    $hrs = floor($diffSec / 3600);
                    $mins = floor(($diffSec % 3600) / 60);
                    $durationStr = "{$hrs}h {$mins}m";
                    $durationHours = round($diffSec / 3600, 2);
                }
            }

            // CCL eligibility on Holiday/Saturday
            $cclEligible = false;
            $cclDays = 0.0;
            if ($isHoliday && $p->in_time) {
                $cclEligible = true;
                $cclDays = ($durationHours >= 5.5 || ($p->in_time && $p->out_time && $durationHours >= 4.0)) ? 1.0 : 0.5;
            }

            $st = $staffMap->get($p->staff_id);

            $rows[] = [
                'id'            => $p->id,
                'staff_id'      => $p->staff_id,
                'staff_name'    => $p->staff_name ?: ($sfStaffList[$p->staff_id] ?? 'SF Staff'),
                'branch'        => $st ? $st->branch : 'SF',
                'designation'   => $st ? $st->designation : 'Faculty',
                'punch_date'    => $p->punch_date,
                'day_name'      => $pDate->format('l'),
                'in_time'       => $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--',
                'out_time'      => $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--',
                'duration'      => $durationStr,
                'duration_hours'=> $durationHours,
                'distance_in'   => $p->in_gps_distance_meters ? "{$p->in_gps_distance_meters}m" : '--',
                'distance_out'  => $p->out_gps_distance_meters ? "{$p->out_gps_distance_meters}m" : '--',
                'premises_status'=> $p->in_premises_status ?: 'INSIDE_PREMISES',
                'punch_status'  => $p->punch_status ?: 'PRESENT',
                'is_holiday'    => $isHoliday,
                'ccl_eligible'  => $cclEligible,
                'ccl_days'      => $cclDays,
                'ccl_credited'  => in_array($p->id, $creditedPunches),
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'total'  => count($rows),
            'logs'   => $rows,
        ]);
    }

    /**
     * API: Get Leave applications for review and ledger.
     */
    public function getLeaveRequests(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $cycle = self::getLeaveCycleDates();
        $status = $request->query('status'); // 'pending_office', 'approved', 'rejected', 'all'
        $type = $request->query('type');
        $dept = $request->query('department');
        $staffId = $request->query('staff_id');
        $isHistorical = $request->query('is_historical');

        $query = StaffLeaveRequest::whereIn('department', ['EL', 'CT', 'AU', 'GEN_SF', 'SF'])
            ->where('from_date', '>=', $cycle['start'])
            ->where('from_date', '<=', $cycle['end']);

        if ($status === 'pending_office') {
            $query->where(function($q) {
                $q->where('office_status', 'Pending')
                  ->orWhereNull('office_status');
            })->where('overall_status', '!=', 'Rejected')
              ->where('is_historical', false);
        } elseif ($status === 'approved') {
            $query->where('overall_status', 'Approved');
        } elseif ($status === 'rejected') {
            $query->where('overall_status', 'Rejected');
        }

        if (!empty($type)) {
            $query->where('leave_type', $type);
        }
        if (!empty($dept)) {
            $query->where('department', strtoupper($dept));
        }
        if (!empty($staffId)) {
            $query->where('staff_mobile', $staffId);
        }
        if ($isHistorical !== null && $isHistorical !== '') {
            $query->where('is_historical', (bool)$isHistorical);
        }

        $leaves = $query->orderByDesc('from_date')->orderByDesc('id')->get()->map(function($l) {
            return [
                'id'                  => $l->id,
                'leave_code'          => $l->leave_code,
                'staff_mobile'        => $l->staff_mobile,
                'staff_name'          => $l->staff_name,
                'designation'         => $l->designation,
                'department'          => $l->department,
                'leave_type'          => $l->leave_type,
                'from_date'           => $l->from_date ? $l->from_date->format('Y-m-d') : '',
                'to_date'             => $l->to_date ? $l->to_date->format('Y-m-d') : '',
                'session_type'        => $l->session_type,
                'total_days'          => (float)$l->total_days,
                'reason'              => $l->reason,
                'is_historical'       => (bool)$l->is_historical,
                'office_status'       => $l->office_status ?: 'Pending',
                'office_name'         => $l->office_name,
                'office_remarks'      => $l->office_remarks,
                'office_action_at'    => $l->office_action_at ? $l->office_action_at->format('d M Y, h:i A') : null,
                'coordinator_status'  => $l->coordinator_status ?: 'Pending',
                'coordinator_name'    => $l->coordinator_name,
                'coordinator_remarks' => $l->coordinator_remarks,
                'coordinator_action_at'=> $l->coordinator_action_at ? $l->coordinator_action_at->format('d M Y, h:i A') : null,
                'principal_status'    => $l->principal_status ?: 'Pending',
                'principal_name'      => $l->principal_name,
                'principal_remarks'   => $l->principal_remarks,
                'principal_action_at' => $l->principal_action_at ? $l->principal_action_at->format('d M Y, h:i A') : null,
                'overall_status'      => $l->overall_status,
                'submitted_at'        => $l->submitted_at ? $l->submitted_at->format('d M Y, h:i A') : '',
            ];
        });

        return response()->json([
            'status' => 'SUCCESS',
            'leaves' => $leaves,
        ]);
    }

    /**
     * API: Process Office Review & Approval (Tier 1 of SF 3-tier workflow).
     */
    public function processOfficeApproval(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'leave_id' => 'required|exists:staff_leave_requests,id',
            'action'   => 'required|in:Approved,Rejected',
            'remarks'  => 'nullable|string',
        ]);

        try {
            $leave = StaffLeaveRequest::findOrFail($request->leave_id);
            $officeMobile = Session::get('userId', '9000000009');
            $officeName   = Session::get('userName', 'SF Office Administrator');

            if ($request->action === 'Rejected') {
                $leave->office_status    = 'Rejected';
                $leave->office_mobile    = $officeMobile;
                $leave->office_name      = $officeName;
                $leave->office_remarks   = $request->remarks ?: 'Rejected by SF Office.';
                $leave->office_action_at = now();
                $leave->overall_status   = 'Rejected';
                $leave->save();

                return response()->json([
                    'status'  => 'SUCCESS',
                    'message' => 'Leave application rejected by SF Office.',
                    'leave'   => $leave
                ]);
            }

            // Approving: Move to SF Academic Coordinator (Tier 2)
            $leave->office_status    = 'Approved';
            $leave->office_mobile    = $officeMobile;
            $leave->office_name      = $officeName;
            $leave->office_remarks   = $request->remarks ?: 'Verified and forwarded to Academic Coordinator.';
            $leave->office_action_at = now();

            if ($leave->overall_status !== 'Approved') {
                $leave->overall_status = 'Pending_Coordinator';
                $leave->coordinator_status = 'Pending';
            }

            $leave->save();

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Leave application approved by SF Office and forwarded to Academic Coordinator.',
                'leave'   => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Save Past Leave Entry (Mid-Year Implementation Backfill).
     */
    public function savePastLeaveEntry(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'staff_mobile' => 'required|string',
            'leave_type'   => 'required|string',
            'from_date'    => 'required|date',
            'to_date'      => 'required|date|after_or_equal:from_date',
            'session_type' => 'required|string|in:Full Day,FN,AN',
            'total_days'   => 'required|numeric|min:0.5',
            'reason'       => 'nullable|string',
            'remarks'      => 'nullable|string',
        ]);

        try {
            $staff = StaffProfile::where('mobile_no', $request->staff_mobile)->first();
            if (!$staff) {
                return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.'], 404);
            }

            $leaveCode = 'HIST-' . date('Y') . '-' . strtoupper(Str::random(6));
            $officeName = Session::get('userName', 'SF Office Administrator');
            $officeMobile = Session::get('userId', '9000000009');

            $leave = StaffLeaveRequest::create([
                'leave_code'           => $leaveCode,
                'staff_mobile'         => $staff->mobile_no,
                'staff_name'           => $staff->name,
                'designation'          => $staff->designation ?: 'Faculty',
                'department'           => $staff->branch ?: 'GEN_SF',
                'leave_type'           => $request->leave_type,
                'from_date'            => $request->from_date,
                'to_date'              => $request->to_date,
                'session_type'         => $request->session_type,
                'total_days'           => (float)$request->total_days,
                'reason'               => $request->reason ?: 'Mid-Year Historical Leave Backfill Log',
                'is_historical'        => true,
                'submitted_at'         => now(),
                'office_status'        => 'Approved',
                'office_mobile'        => $officeMobile,
                'office_name'          => $officeName,
                'office_remarks'       => $request->remarks ?: 'Office Past Leave Backfill Logged',
                'office_action_at'     => now(),
                'hod_status'           => 'Approved',
                'hod_name'             => 'Office Verified',
                'coordinator_status'   => 'Approved',
                'coordinator_name'     => 'Office Verified',
                'principal_status'     => 'Approved',
                'principal_name'       => 'Office Verified',
                'overall_status'       => 'Approved',
            ]);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Past leave record successfully added to staff leave ledger.',
                'leave'   => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Delete a Past Historical Leave Entry.
     */
    public function deletePastLeaveEntry(Request $request, $id)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        try {
            $leave = StaffLeaveRequest::where('id', $id)
                ->where('is_historical', true)
                ->firstOrFail();

            $leave->delete();

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Historical past leave record deleted from ledger.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Credit Compensatory Casual Leave (CCL) from Holiday/Saturday duty.
     */
    public function creditCcl(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'staff_mobile'     => 'required|string',
            'duty_date'        => 'required|date',
            'session_type'     => 'required|string|in:Full Day,Half Day',
            'source_punch_id'  => 'nullable|integer',
            'remarks'          => 'nullable|string',
        ]);

        try {
            $staff = StaffProfile::where('mobile_no', $request->staff_mobile)->first();
            if (!$staff) {
                return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.'], 404);
            }

            // Check duplicate credit for the same duty date
            $existing = SfStaffCclCredit::where('staff_mobile', $staff->mobile_no)
                ->where('duty_date', $request->duty_date)
                ->first();

            if ($existing) {
                return response()->json(['status' => 'ERROR', 'message' => 'CCL already credited for ' . $staff->name . ' on ' . $request->duty_date], 422);
            }

            $validityDays = (int)(DB::table('system_settings')->where('key', 'sf_office_ccl_validity_days')->value('value') ?: 60);
            $earnedDays = ($request->session_type === 'Full Day') ? 1.0 : 0.5;
            $validUntil = \Carbon\Carbon::parse($request->duty_date)->addDays($validityDays)->format('Y-m-d');

            $ccl = SfStaffCclCredit::create([
                'staff_mobile'    => $staff->mobile_no,
                'staff_name'      => $staff->name,
                'duty_date'       => $request->duty_date,
                'session_type'    => $request->session_type,
                'earned_days'     => $earnedDays,
                'used_days'       => 0.0,
                'valid_until'     => $validUntil,
                'source_punch_id' => $request->source_punch_id,
                'status'          => 'Active',
                'remarks'         => $request->remarks ?: "Holiday Extra Work ({$request->session_type})",
                'credited_by'     => Session::get('userName', 'SF Office'),
            ]);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => "Successfully credited {$earnedDays} CCL day(s) for {$staff->name}. Valid until {$validUntil} (2 months).",
                'ccl'     => $ccl
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get CCL credits ledger.
     */
    public function getCclLedger(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $staffId = $request->query('staff_id');
        $status  = $request->query('status'); // 'Active', 'Consumed', 'Expired'

        $query = SfStaffCclCredit::query();
        if (!empty($staffId)) {
            $query->where('staff_mobile', $staffId);
        }
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $today = now()->format('Y-m-d');
        $credits = $query->orderByDesc('duty_date')->get()->map(function($c) use ($today) {
            $isExpired = ($c->valid_until && $c->valid_until->format('Y-m-d') < $today && $c->status === 'Active');
            return [
                'id'           => $c->id,
                'staff_mobile' => $c->staff_mobile,
                'staff_name'   => $c->staff_name,
                'duty_date'    => $c->duty_date ? $c->duty_date->format('Y-m-d') : '',
                'session_type' => $c->session_type,
                'earned_days'  => (float)$c->earned_days,
                'used_days'    => (float)$c->used_days,
                'balance'      => max(0.0, (float)$c->earned_days - (float)$c->used_days),
                'valid_until'  => $c->valid_until ? $c->valid_until->format('Y-m-d') : '',
                'status'       => $isExpired ? 'Expired' : $c->status,
                'remarks'      => $c->remarks,
                'credited_by'  => $c->credited_by,
            ];
        });

        return response()->json([
            'status'  => 'SUCCESS',
            'credits' => $credits,
        ]);
    }

    /**
     * API: Monthly Leave Report.
     */
    public function getMonthlyReport(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $month = (int) $request->query('month', now()->format('m'));
        $year  = (int) $request->query('year', now()->format('Y'));
        $dept  = $request->query('department');

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate   = \Carbon\Carbon::parse($startDate)->endOfMonth()->format('Y-m-d');

        $staffQuery = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF']);
        if (!empty($dept)) {
            $staffQuery->where('branch', strtoupper($dept));
        }
        $staff = $staffQuery->orderBy('branch')->orderBy('name')->get();

        $rows = [];
        $grandTotals = ['CL' => 0.0, 'CCL' => 0.0, 'DL' => 0.0, 'ODL' => 0.0, 'ML' => 0.0, 'LOP' => 0.0, 'OTHERS' => 0.0, 'TOTAL' => 0.0];

        foreach ($staff as $s) {
            $leaves = StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where('overall_status', 'Approved')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('from_date', [$startDate, $endDate])
                      ->orWhereBetween('to_date', [$startDate, $endDate]);
                })
                ->get();

            $staffTotals = ['CL' => 0.0, 'CCL' => 0.0, 'DL' => 0.0, 'ODL' => 0.0, 'ML' => 0.0, 'LOP' => 0.0, 'OTHERS' => 0.0, 'TOTAL' => 0.0];
            foreach ($leaves as $l) {
                $days = (float)$l->total_days;
                $t = strtoupper(trim($l->leave_type));
                $staffTotals['TOTAL'] += $days;

                if (str_contains($t, 'CASUAL') || $t === 'CL') {
                    $staffTotals['CL'] += $days;
                } elseif (str_contains($t, 'COMPENSATORY') || str_contains($t, 'CCL')) {
                    $staffTotals['CCL'] += $days;
                } elseif (str_contains($t, 'OFFICIAL') || str_contains($t, 'ODL')) {
                    $staffTotals['ODL'] += $days;
                } elseif (str_contains($t, 'DUTY') || $t === 'DL') {
                    $staffTotals['DL'] += $days;
                } elseif (str_contains($t, 'MEDICAL') || $t === 'ML') {
                    $staffTotals['ML'] += $days;
                } elseif (str_contains($t, 'LOSS') || str_contains($t, 'LOP')) {
                    $staffTotals['LOP'] += $days;
                } else {
                    $staffTotals['OTHERS'] += $days;
                }
            }

            foreach ($staffTotals as $k => $v) {
                $grandTotals[$k] += $v;
            }

            $rows[] = [
                'mobile_no'   => $s->mobile_no,
                'name'        => $s->name,
                'branch'      => $s->branch,
                'designation' => $s->designation,
                'breakdown'   => $staffTotals,
            ];
        }

        return response()->json([
            'status'       => 'SUCCESS',
            'month'        => $month,
            'month_name'   => \Carbon\Carbon::parse($startDate)->format('F Y'),
            'year'         => $year,
            'grand_totals' => $grandTotals,
            'rows'         => $rows,
        ]);
    }

    /**
     * API: Cumulative & Yearly Leave Report (April 1 to March 31).
     */
    public function getYearlyReport(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $yearParam = $request->query('year');
        $cycle = self::getLeaveCycleDates($yearParam ? "{$yearParam}-05-01" : null);
        $clQuota = (float)(DB::table('system_settings')->where('key', 'sf_office_annual_cl_quota')->value('value') ?: 15);
        $dept = $request->query('department');

        $staffQuery = StaffProfile::whereIn('branch', ['EL', 'CT', 'AU', 'GEN_SF', 'SF']);
        if (!empty($dept)) {
            $staffQuery->where('branch', strtoupper($dept));
        }
        $staff = $staffQuery->orderBy('branch')->orderBy('name')->get();

        $rows = [];
        $grandTotals = [
            'CL_TAKEN' => 0.0,
            'CCL_EARNED' => 0.0,
            'CCL_TAKEN' => 0.0,
            'DL_TAKEN' => 0.0,
            'ML_TAKEN' => 0.0,
            'LOP_TAKEN' => 0.0,
            'TOTAL_TAKEN' => 0.0,
            'REMAINING_CL' => 0.0,
        ];

        foreach ($staff as $s) {
            $leaves = StaffLeaveRequest::where('staff_mobile', $s->mobile_no)
                ->where('overall_status', 'Approved')
                ->where('from_date', '>=', $cycle['start'])
                ->where('from_date', '<=', $cycle['end'])
                ->get();

            $tot = ['CL' => 0.0, 'CCL' => 0.0, 'DL' => 0.0, 'ODL' => 0.0, 'ML' => 0.0, 'LOP' => 0.0, 'OTHERS' => 0.0, 'TOTAL' => 0.0];
            foreach ($leaves as $l) {
                $days = (float)$l->total_days;
                $t = strtoupper(trim($l->leave_type));
                $tot['TOTAL'] += $days;

                if (str_contains($t, 'CASUAL') || $t === 'CL') {
                    $tot['CL'] += $days;
                } elseif (str_contains($t, 'COMPENSATORY') || str_contains($t, 'CCL')) {
                    $tot['CCL'] += $days;
                } elseif (str_contains($t, 'OFFICIAL') || str_contains($t, 'ODL')) {
                    $tot['ODL'] += $days;
                } elseif (str_contains($t, 'DUTY') || $t === 'DL') {
                    $tot['DL'] += $days;
                } elseif (str_contains($t, 'MEDICAL') || $t === 'ML') {
                    $tot['ML'] += $days;
                } elseif (str_contains($t, 'LOSS') || str_contains($t, 'LOP')) {
                    $tot['LOP'] += $days;
                } else {
                    $tot['OTHERS'] += $days;
                }
            }

            // Total CCL earned during cycle
            $cclEarned = (float) SfStaffCclCredit::where('staff_mobile', $s->mobile_no)
                ->where('duty_date', '>=', $cycle['start'])
                ->where('duty_date', '<=', $cycle['end'])
                ->sum('earned_days');

            $clRemaining = max(0.0, $clQuota - $tot['CL']);

            $grandTotals['CL_TAKEN'] += $tot['CL'];
            $grandTotals['CCL_EARNED'] += $cclEarned;
            $grandTotals['CCL_TAKEN'] += $tot['CCL'];
            $grandTotals['DL_TAKEN'] += ($tot['DL'] + $tot['ODL']);
            $grandTotals['ML_TAKEN'] += $tot['ML'];
            $grandTotals['LOP_TAKEN'] += $tot['LOP'];
            $grandTotals['TOTAL_TAKEN'] += $tot['TOTAL'];
            $grandTotals['REMAINING_CL'] += $clRemaining;

            $rows[] = [
                'mobile_no'    => $s->mobile_no,
                'name'         => $s->name,
                'branch'       => $s->branch,
                'designation'  => $s->designation,
                'cl_quota'     => $clQuota,
                'cl_taken'     => $tot['CL'],
                'cl_remaining' => $clRemaining,
                'ccl_earned'   => $cclEarned,
                'ccl_taken'    => $tot['CCL'],
                'dl_taken'     => $tot['DL'] + $tot['ODL'],
                'ml_taken'     => $tot['ML'],
                'lop_taken'    => $tot['LOP'],
                'total_taken'  => $tot['TOTAL'],
            ];
        }

        return response()->json([
            'status'       => 'SUCCESS',
            'cycle'        => $cycle,
            'cl_quota'     => $clQuota,
            'grand_totals' => $grandTotals,
            'rows'         => $rows,
        ]);
    }

    /**
     * API: Get Office Settings.
     */
    public function getSettings(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $settings = [
            'leave_year_start'    => DB::table('system_settings')->where('key', 'sf_office_leave_year_start')->value('value') ?: '04-01',
            'leave_year_end'      => DB::table('system_settings')->where('key', 'sf_office_leave_year_end')->value('value') ?: '03-31',
            'annual_cl_quota'     => DB::table('system_settings')->where('key', 'sf_office_annual_cl_quota')->value('value') ?: '15',
            'ccl_validity_days'   => DB::table('system_settings')->where('key', 'sf_office_ccl_validity_days')->value('value') ?: '60',
        ];

        return response()->json([
            'status'   => 'SUCCESS',
            'settings' => $settings,
            'cycle'    => self::getLeaveCycleDates(),
        ]);
    }

    /**
     * API: Update Office Settings.
     */
    public function updateSettings(Request $request)
    {
        if (!$this->checkAccess()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'annual_cl_quota'   => 'required|numeric|min:1|max:50',
            'ccl_validity_days' => 'required|numeric|min:7|max:365',
            'leave_year_start'  => 'required|string',
            'leave_year_end'    => 'required|string',
        ]);

        $keys = [
            'sf_office_annual_cl_quota'   => (string)$request->annual_cl_quota,
            'sf_office_ccl_validity_days' => (string)$request->ccl_validity_days,
            'sf_office_leave_year_start'  => (string)$request->leave_year_start,
            'sf_office_leave_year_end'    => (string)$request->leave_year_end,
        ];

        foreach ($keys as $k => $v) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $k],
                ['value' => $v, 'updated_at' => now()]
            );
        }

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Office rules and leave settings updated successfully.',
            'cycle'   => self::getLeaveCycleDates(),
        ]);
    }
}
