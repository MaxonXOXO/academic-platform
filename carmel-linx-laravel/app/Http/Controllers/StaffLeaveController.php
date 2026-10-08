<?php

namespace App\Http\Controllers;

use App\Models\StaffLeaveRequest;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class StaffLeaveController extends Controller
{
    /**
     * Submit a new staff leave application.
     */
    public function applyLeave(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $staff = StaffProfile::where('mobile_no', $mobileNo)->first();
        $staffName = $staff ? $staff->name : Session::get('userName', 'Staff Member');
        $designation = $staff ? ($staff->designation ?? Session::get('userRole', 'Lecturer')) : Session::get('userRole', 'Lecturer');
        $department = $staff ? ($staff->branch ?? $staff->department ?? Session::get('userBranch', 'General')) : Session::get('userBranch', 'General');

        $request->validate([
            'leave_type'        => 'required|string',
            'from_date'         => 'required|date',
            'to_date'           => 'required|date|after_or_equal:from_date',
            'session_type'      => 'required|string|in:Full Day,FN,AN',
            'ccl_date'          => 'nullable|date',
            'total_days'        => 'required|numeric|min:0.5',
            'reason'            => 'required|string',
            'work_arrangement'  => 'nullable|array',
        ]);

        try {
            // Generate unique leave code
            $leaveCode = 'SLV-' . date('Y') . '-' . strtoupper(Str::random(6));

            // Generate digital signature hash
            $sigPayload = $mobileNo . '|' . $leaveCode . '|' . $request->from_date . '|' . microtime();
            $sigHash = hash('sha256', $sigPayload);

            $leave = StaffLeaveRequest::create([
                'leave_code'           => $leaveCode,
                'staff_mobile'         => $mobileNo,
                'staff_name'           => $staffName,
                'designation'          => $designation,
                'department'           => $department,
                'leave_type'           => $request->leave_type,
                'from_date'            => $request->from_date,
                'to_date'              => $request->to_date,
                'session_type'         => $request->session_type,
                'ccl_date'             => $request->ccl_date,
                'total_days'           => $request->total_days,
                'reason'               => $request->reason,
                'work_arrangement'     => $request->work_arrangement ?? [],
                'staff_signature_hash' => $sigHash,
                'submitted_at'         => now(),
                'hod_status'           => 'Pending',
                'coordinator_status'   => 'Pending',
                'principal_status'     => 'Pending',
                'overall_status'       => 'Pending_HOD',
            ]);

            return response()->json([
                'status'     => 'SUCCESS',
                'message'    => 'Leave application submitted successfully and sent to HOD for approval.',
                'leave_code' => $leaveCode,
                'request'    => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch leave application history for the logged-in staff member.
     */
    public function getMyLeaveHistory()
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        try {
            $history = StaffLeaveRequest::where('staff_mobile', $mobileNo)
                ->orderByDesc('id')
                ->get();

            $clTaken = StaffLeaveRequest::where('staff_mobile', $mobileNo)
                ->where('leave_type', 'Casual Leave')
                ->where('overall_status', '!=', 'Rejected')
                ->sum('total_days');

            return response()->json([
                'status'   => 'SUCCESS',
                'leaves'   => $history,
                'cl_total' => 15,
                'cl_taken' => (float)$clTaken
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get pending approvals based on logged-in user role (HOD, Coordinator, Principal).
     */
    public function getPendingApprovals()
    {
        $mobileNo = Session::get('userId');
        $userRole = Session::get('userRole');
        $department = Session::get('userBranch');

        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        try {
            $isSfCoord = StaffProfile::isSfAcademicCoordinator($mobileNo);
            $query = StaffLeaveRequest::query();

            if ($userRole === 'HOD') {
                if ($isSfCoord) {
                    // Dual Role: Aided HOD (Pending_HOD for their department) + SF Coordinator (Pending_Coordinator for SF staff)
                    $query->where(function($q) use ($department) {
                        $q->where(function($sub) use ($department) {
                            $sub->where('department', $department)
                                ->where('overall_status', 'Pending_HOD');
                        })->orWhere('overall_status', 'Pending_Coordinator');
                    });
                } else {
                    $query->where('department', $department)
                          ->where('overall_status', 'Pending_HOD');
                }
            } elseif ($userRole === 'Academic_Coordinator' || str_contains(strtolower($userRole), 'coordinator')) {
                $query->where('overall_status', 'Pending_Coordinator');
            } elseif (in_array($userRole, ['Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
                $query->where(function($q) {
                    $q->where('overall_status', 'Pending_Principal')
                      ->orWhere('overall_status', 'Pending_HOD')
                      ->orWhere('overall_status', 'Pending_Coordinator');
                });
            } else {
                return response()->json(['status' => 'SUCCESS', 'approvals' => []]);
            }

            $approvals = $query->orderByDesc('id')->get()->map(function($req) {
                $req->start_date = $req->from_date ? $req->from_date->format('Y-m-d') : '';
                $req->end_date = $req->to_date ? $req->to_date->format('Y-m-d') : '';
                $req->leave_category = $req->leave_type;
                $req->session = $req->session_type;
                return $req;
            });

            return response()->json([
                'status'    => 'SUCCESS',
                'role'      => $userRole,
                'is_sf_coordinator' => $isSfCoord,
                'approvals' => $approvals
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper to determine if department follows Self-Financing (SF) 3-tier hierarchy or Aided 2-tier hierarchy.
     * SF (EL, CT, AU, General SF): HOD -> Academic Coordinator -> Principal
     * Aided (EEE, ME, CE, GEN AIDED): HOD -> Principal (Skips Academic Coordinator)
     */
    private function isSelfFinancingDepartment($dept)
    {
        $d = strtoupper(trim($dept));
        if (in_array($d, ['EL', 'CT', 'AU', 'SF', 'GEN SF', 'GENERAL SF', 'ELECTRONICS', 'COMPUTER', 'AUTOMOBILE', 'COMPUTER TECH'])) {
            return true;
        }
        if (str_contains($d, 'SF') || str_contains($d, 'SELF')) {
            return true;
        }
        return false;
    }

    /**
     * Process multi-stage leave approval (HOD -> Coordinator -> Principal).
     */
    public function processApproval(Request $request)
    {
        $mobileNo = Session::get('userId');
        $userRole = Session::get('userRole');
        $approverStaff = StaffProfile::where('mobile_no', $mobileNo)->first();
        $actorName = $approverStaff ? $approverStaff->name : Session::get('userName', 'Approver');

        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $request->validate([
            'leave_id'  => 'required|exists:staff_leave_requests,id',
            'stage'     => 'required|in:HOD,Coordinator,Principal',
            'action'    => 'required|in:Approved,Rejected',
            'remarks'   => 'nullable|string',
        ]);

        if ($request->stage === 'Coordinator') {
            $isSfCoord = StaffProfile::isSfAcademicCoordinator($mobileNo);
            if (!in_array($userRole, ['Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance', 'Principal', 'Super_Admin', 'Admin']) && !$isSfCoord) {
                return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized: You are not authorized as Self-Financing Academic Coordinator.'], 403);
            }
        }

        try {
            $leave = StaffLeaveRequest::findOrFail($request->leave_id);
            $isSF = $this->isSelfFinancingDepartment($leave->department);

            if ($request->action === 'Rejected') {
                if ($request->stage === 'HOD') {
                    $leave->hod_status = 'Rejected';
                    $leave->hod_mobile = $mobileNo;
                    $leave->hod_name = $actorName;
                    $leave->hod_remarks = $request->remarks;
                    $leave->hod_action_at = now();
                } elseif ($request->stage === 'Coordinator') {
                    $leave->coordinator_status = 'Rejected';
                    $leave->coordinator_mobile = $mobileNo;
                    $leave->coordinator_name = $actorName;
                    $leave->coordinator_remarks = $request->remarks;
                    $leave->coordinator_action_at = now();
                } elseif ($request->stage === 'Principal') {
                    $leave->principal_status = 'Rejected';
                    $leave->principal_mobile = $mobileNo;
                    $leave->principal_name = $actorName;
                    $leave->principal_remarks = $request->remarks;
                    $leave->principal_action_at = now();
                }
                $leave->overall_status = 'Rejected';
                $leave->save();

                return response()->json(['status' => 'SUCCESS', 'message' => 'Leave application rejected.']);
            }

            // Processing APPROVAL
            if ($request->stage === 'HOD') {
                $leave->hod_status = 'Approved';
                $leave->hod_mobile = $mobileNo;
                $leave->hod_name = $actorName;
                $leave->hod_remarks = $request->remarks;
                $leave->hod_action_at = now();

                if ($isSF) {
                    // Self-Financing Stream: Moves to Academic Coordinator
                    $leave->overall_status = 'Pending_Coordinator';
                    $leave->coordinator_status = 'Pending';
                } else {
                    // Aided Stream (EEE, ME, CE, GEN AIDED): Skips Academic Coordinator -> Moves straight to Principal
                    $leave->overall_status = 'Pending_Principal';
                    $leave->coordinator_status = 'N/A';
                    $leave->coordinator_name = 'N/A (Aided Stream)';
                }
            } elseif ($request->stage === 'Coordinator') {
                $leave->coordinator_status = 'Approved';
                $leave->coordinator_mobile = $mobileNo;
                $leave->coordinator_name = $actorName;
                $leave->coordinator_remarks = $request->remarks;
                $leave->coordinator_action_at = now();
                $leave->overall_status = 'Pending_Principal';
            } elseif ($request->stage === 'Principal') {
                $leave->principal_status = 'Approved';
                $leave->principal_mobile = $mobileNo;
                $leave->principal_name = $actorName;
                $leave->principal_remarks = $request->remarks;
                $leave->principal_action_at = now();
                $leave->overall_status = 'Approved';
            }

            $leave->save();

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Leave application approved successfully.',
                'leave'   => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate printable A4 PDF formal Leave Application view.
     */
    public function generateLeavePDF($id)
    {
        $leave = StaffLeaveRequest::where('id', $id)
            ->orWhere('leave_code', $id)
            ->firstOrFail();

        return view('staff_leave_pdf', compact('leave'));
    }

    /**
     * View Consolidated Staff Leave Reports Page / Data.
     */
    public function getLeaveReports(Request $request)
    {
        $userRole = Session::get('userRole');
        $userBranch = Session::get('userBranch');

        $allowedRoles = ['HOD', 'Academic_Coordinator', 'Academic_Coordinator_SF', 'Principal', 'Admin', 'Super_Admin', 'Chairman'];
        if (!in_array($userRole, $allowedRoles)) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access to leave ledger.'], 403);
            }
            return redirect('/staff/mobile')->with('error', 'Staff leave reports and ledgers are restricted to HOD and Principal access.');
        }

        $leaveType    = $request->query('leave_type');
        $status       = $request->query('status');
        $academicYear = $request->query('academic_year', date('Y'));

        $userId       = Session::get('userId');
        $isSfCoord    = StaffProfile::isSfAcademicCoordinator($userId);

        // If user is HOD and not SF Coordinator, strictly enforce filtering by their department branch only
        if ($userRole === 'HOD' && !$isSfCoord) {
            $department = $userBranch;
        } else {
            $department = $request->query('department');
        }

        $query = StaffLeaveRequest::query();

        if (!empty($academicYear)) {
            $query->whereYear('from_date', $academicYear);
        }
        if (!empty($department)) {
            $query->where(function($q) use ($department) {
                $q->where('department', $department)
                  ->orWhere('department', 'like', '%' . $department . '%');
            });
        }
        if (!empty($leaveType)) {
            $query->where('leave_type', $leaveType);
        }
        if (!empty($status)) {
            $query->where('overall_status', $status);
        }

        $leaves = $query->orderByDesc('id')->get();

        // Calculate Category-wise Totals for Academic Year Summary
        $summary = [
            'CL'     => 0.0, // Casual Leave
            'CCL'    => 0.0, // Compensatory Casual Leave
            'DL'     => 0.0, // Duty Leave
            'ML'     => 0.0, // Medical Leave
            'LOP'    => 0.0, // Loss of Pay
            'SL'     => 0.0, // Special Leave
            'OTHERS' => 0.0,
            'TOTAL_DAYS' => 0.0,
        ];

        foreach ($leaves as $l) {
            $type = strtolower($l->leave_type);
            $days = (float)$l->total_days;
            $summary['TOTAL_DAYS'] += $days;

            if (str_contains($type, 'compensatory') || str_contains($type, 'ccl')) {
                $summary['CCL'] += $days;
            } elseif (str_contains($type, 'casual') || str_contains($type, 'cl')) {
                $summary['CL'] += $days;
            } elseif (str_contains($type, 'duty') || str_contains($type, 'dl')) {
                $summary['DL'] += $days;
            } elseif (str_contains($type, 'medical') || str_contains($type, 'ml')) {
                $summary['ML'] += $days;
            } elseif (str_contains($type, 'loss') || str_contains($type, 'lop')) {
                $summary['LOP'] += $days;
            } elseif (str_contains($type, 'special') || str_contains($type, 'sl')) {
                $summary['SL'] += $days;
            } else {
                $summary['OTHERS'] += $days;
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'SUCCESS',
                'summary' => $summary,
                'leaves'  => $leaves
            ]);
        }

        return view('staff_leave_reports', compact('leaves', 'summary', 'academicYear'));
    }
}
