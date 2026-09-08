<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Leave Master Report & Ledger - Carmel Linx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 0.78rem;
        }
        .card-custom {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .bg-slate-900 { background-color: #0f172a !important; }
        .bg-slate-800 { background-color: #1e293b !important; }
        .text-cyan { color: #38bdf8 !important; }
        .btn-approve {
            background: #059669;
            border-color: #059669;
            color: #ffffff;
        }
        .btn-approve:hover {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
        }
        .pulse-amber {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            animation: pulse-amber-anim 2s infinite;
        }
        @keyframes pulse-amber-anim {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
            70% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        .table-responsive-custom {
            overflow-y: auto;
            overflow-x: auto;
            max-height: calc(100vh - 280px);
            scrollbar-width: thin;
            scrollbar-color: #334155 #0f172a;
            border-radius: 6px;
        }
        .table-responsive-custom::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .table-responsive-custom::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #38bdf8;
        }
        .table-dark-custom {
            --bs-table-bg: #1e293b !important;
            --bs-table-color: #e2e8f0 !important;
            --bs-table-striped-bg: #192333 !important;
            --bs-table-hover-bg: #24344d !important;
            --bs-table-border-color: rgba(255, 255, 255, 0.08) !important;
            color: #e2e8f0 !important;
            font-size: 0.76rem;
            background-color: #1e293b !important;
            margin-bottom: 0;
        }
        .table-dark-custom > :not(caption) > * > * {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 0.45rem 0.65rem !important;
            box-shadow: none !important;
        }
        .table-dark-custom th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #0f172a !important;
            color: #38bdf8 !important;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.65rem !important;
            border-bottom: 2px solid rgba(56, 189, 248, 0.3) !important;
            white-space: nowrap;
        }
        .table-dark-custom td {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            vertical-align: middle;
            padding: 0.45rem 0.65rem !important;
        }
        .table-dark-custom tfoot td {
            position: sticky;
            bottom: 0;
            z-index: 9;
            background-color: #0f172a !important;
            padding: 0.45rem 0.65rem !important;
        }
        .table-dark-custom tbody tr:hover > * {
            background-color: #24344d !important;
        }
        .form-select, .form-control {
            font-size: 0.72rem !important;
            padding: 0.25rem 0.5rem;
        }
        .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
        .btn-sm {
            font-size: 0.72rem;
            padding: 0.2rem 0.55rem;
        }
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                font-size: 9pt !important;
            }
            .card-custom {
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                color: #000000 !important;
                box-shadow: none !important;
            }
            .table-dark-custom {
                color: #000000 !important;
                font-size: 8.5pt !important;
            }
            .table-dark-custom th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
                border-bottom: 2px solid #000000 !important;
            }
            .table-dark-custom td {
                border-bottom: 1px solid #e2e8f0 !important;
            }
            .btn, form, button, .no-print {
                display: none !important;
            }
            .text-white {
                color: #000000 !important;
            }
            .text-secondary, .text-slate-400 {
                color: #475569 !important;
            }
        }
    </style>
</head>
<body class="p-2 p-md-3">
    <div class="container-fluid px-1 px-md-2 d-flex flex-column h-100">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-file-invoice-dollar text-info me-1.5"></i> Staff Leave Master Ledger & Report Center
                </h6>
                <small class="text-secondary d-none d-lg-inline" style="font-size: 0.72rem;">| Multi-stage approval audit trail</small>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                @if(Session::get('userRole') === 'HOD')
                    <a href="/dashboard/hod" class="btn btn-outline-light btn-sm rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to HOD Console
                    </a>
                @elseif(in_array(Session::get('userRole'), ['Principal', 'Super_Admin', 'Admin']))
                    <a href="/dashboard/superadmin" class="btn btn-outline-light btn-sm rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-arrow-left me-1"></i> Control Desk
                    </a>
                @else
                    <a href="/staff/mobile?mode=mobile" class="btn btn-outline-light btn-sm rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-calendar-check me-1"></i> My Leave Portal
                    </a>
                @endif
                <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-print me-1"></i> Print
                </button>
            </div>
        </div>

        @php
            $currentUserRole = Session::get('userRole');
            $userBranch = Session::get('userBranch');
            $pendingQuery = \App\Models\StaffLeaveRequest::query();
            
            if ($currentUserRole === 'HOD') {
                $pendingQuery->where(function($q) use ($userBranch) {
                    $q->where('department', $userBranch)->orWhere('department', 'like', "%{$userBranch}%");
                })->where('overall_status', 'Pending_HOD');
            } elseif (in_array($currentUserRole, ['Academic_Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance']) || str_contains(strtolower((string)$currentUserRole), 'coordinator')) {
                $pendingQuery->where('overall_status', 'Pending_Coordinator');
            } elseif (in_array($currentUserRole, ['Principal', 'Super_Admin', 'Admin', 'Chairman'])) {
                $pendingQuery->whereIn('overall_status', ['Pending_Principal', 'Pending_HOD', 'Pending_Coordinator']);
            } else {
                $pendingQuery->whereRaw('1=0');
            }
            $myPendingCount = $pendingQuery->count();
        @endphp

        <!-- Centered Compact Pending Notification Banner -->
        @if($myPendingCount > 0)
        <div class="d-flex justify-content-center mb-2 no-print">
            <div class="card-custom py-1 px-3 border border-warning d-inline-flex align-items-center justify-content-between gap-2.5 pulse-amber shadow-sm rounded-pill" style="font-size: 0.74rem; max-width: 90%;">
                <div class="d-flex align-items-center gap-2 text-nowrap">
                    <i class="fa-solid fa-bell text-warning"></i>
                    <strong class="text-warning">{{ $myPendingCount }} Staff Leave {{ $myPendingCount == 1 ? 'Application' : 'Applications' }} Pending Your Review</strong>
                </div>
                <span class="text-slate-400 d-none d-md-inline text-nowrap" style="font-size: 0.7rem;">— Quick actions in table below</span>
                <div>
                    @if(request('status') !== 'Pending_HOD' && request('status') !== 'Pending_Coordinator' && request('status') !== 'Pending_Principal')
                        <a href="/staff/leave/reports?status={{ $currentUserRole === 'HOD' ? 'Pending_HOD' : ($currentUserRole === 'Principal' ? 'Pending_Principal' : 'Pending_Coordinator') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-2.5 py-0.5" style="font-size: 0.67rem; line-height: 1.2;">
                            <i class="fa-solid fa-bolt me-1"></i> Filter Pending
                        </a>
                    @else
                        <a href="/staff/leave/reports" class="btn btn-outline-secondary rounded-pill px-2.5 py-0.5" style="font-size: 0.67rem; line-height: 1.2;">
                            <i class="fa-solid fa-list me-1"></i> View All
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Bar (Slim & Compact) -->
        <div class="card-custom py-1.5 px-2.5 mb-2">
            <form method="GET" action="/staff/leave/reports" class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label text-secondary fw-bold mb-0" style="font-size: 0.67rem;">Academic Year</label>
                    <select name="academic_year" class="form-select form-select-sm bg-dark text-white border-secondary py-1" style="font-size: 0.73rem;">
                        @foreach([date('Y'), date('Y')-1, date('Y')-2] as $yr)
                            <option value="{{ $yr }}" {{ ($academicYear ?? date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }} - {{ $yr+1 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label text-secondary fw-bold mb-0" style="font-size: 0.67rem;">Department</label>
                    @if(Session::get('userRole') === 'HOD')
                        <input type="text" class="form-control form-control-sm bg-dark text-info border-secondary fw-bold py-1" style="font-size: 0.73rem;" value="{{ Session::get('userBranch') }} Dept (HOD Ledger)" disabled readonly>
                        <input type="hidden" name="department" value="{{ Session::get('userBranch') }}">
                    @else
                        <select name="department" class="form-select form-select-sm bg-dark text-white border-secondary py-1" style="font-size: 0.73rem;">
                            <option value="">All Departments</option>
                            <option value="EL" {{ request('department') == 'EL' || request('department') == 'Electronics' ? 'selected' : '' }}>Electronics (EL)</option>
                            <option value="ME" {{ request('department') == 'ME' || request('department') == 'Mechanical' ? 'selected' : '' }}>Mechanical (ME)</option>
                            <option value="CE" {{ request('department') == 'CE' || request('department') == 'Civil' ? 'selected' : '' }}>Civil (CE)</option>
                            <option value="EEE" {{ request('department') == 'EEE' || request('department') == 'Electrical' ? 'selected' : '' }}>Electrical (EEE)</option>
                            <option value="CT" {{ request('department') == 'CT' || request('department') == 'Computer' ? 'selected' : '' }}>Computer (CT)</option>
                            <option value="AU" {{ request('department') == 'AU' || request('department') == 'Automobile' ? 'selected' : '' }}>Automobile (AU)</option>
                            <option value="General" {{ request('department') == 'General' ? 'selected' : '' }}>General Science</option>
                        </select>
                    @endif
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label text-secondary fw-bold mb-0" style="font-size: 0.67rem;">Leave Type</label>
                    <select name="leave_type" class="form-select form-select-sm bg-dark text-white border-secondary py-1" style="font-size: 0.73rem;">
                        <option value="">All Types</option>
                        <option value="Casual Leave" {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave (CL)</option>
                        <option value="Compensatory Casual Leave" {{ request('leave_type') == 'Compensatory Casual Leave' ? 'selected' : '' }}>Compensatory (CCL)</option>
                        <option value="Duty Leave" {{ request('leave_type') == 'Duty Leave' ? 'selected' : '' }}>Duty Leave (DL)</option>
                        <option value="Medical Leave" {{ request('leave_type') == 'Medical Leave' ? 'selected' : '' }}>Medical Leave (ML)</option>
                        <option value="Loss of Pay" {{ request('leave_type') == 'Loss of Pay' ? 'selected' : '' }}>Loss of Pay (LOP)</option>
                        <option value="Special Leave" {{ request('leave_type') == 'Special Leave' ? 'selected' : '' }}>Special Leave (SL)</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label text-secondary fw-bold mb-0" style="font-size: 0.67rem;">Status</label>
                    <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary py-1" style="font-size: 0.73rem;">
                        <option value="">All Statuses</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Final Approved</option>
                        <option value="Pending_HOD" {{ request('status') == 'Pending_HOD' ? 'selected' : '' }}>Pending HOD</option>
                        <option value="Pending_Coordinator" {{ request('status') == 'Pending_Coordinator' ? 'selected' : '' }}>Pending Coordinator</option>
                        <option value="Pending_Principal" {{ request('status') == 'Pending_Principal' ? 'selected' : '' }}>Pending Principal</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-info btn-sm text-dark fw-bold w-100 py-1" style="font-size: 0.73rem;">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="/staff/leave/reports" class="btn btn-outline-secondary btn-sm py-1 px-2 text-nowrap" style="font-size: 0.73rem;">Reset</a>
                </div>
            </form>
        <!-- Ledger Table (Scrollable within table only) -->
        <div class="card-custom p-2 mb-2 flex-grow-1 d-flex flex-column overflow-hidden">
            <div class="table-responsive-custom flex-grow-1">
                <table class="table table-dark table-hover table-dark-custom align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Staff Member</th>
                            <th>Dept & Role</th>
                            <th>Type (Category)</th>
                            <th>Duration / Session</th>
                            <th>Days</th>
                            <th>HOD</th>
                            <th>Coord.</th>
                            <th>Principal</th>
                            <th>Overall</th>
                            <th class="text-end" style="min-width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="font-mono text-cyan fw-bold">{{ $leave->leave_code }}</td>
                                <td>
                                    <strong class="d-block fw-bold text-white mb-0.5" style="color: #ffffff !important; font-size: 0.88rem;">{{ $leave->staff_name }}</strong>
                                    <small class="text-cyan font-mono" style="font-size: 0.72rem;"><i class="fa-solid fa-phone-flip me-1 opacity-50"></i>{{ $leave->staff_mobile }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $leave->department }}</span>
                                    <small class="text-slate-400 d-block">{{ $leave->designation }}</small>
                                </td>
                                <td>
                                    <strong class="text-info d-block">{{ $leave->leave_type }}</strong>
                                    @if($leave->ccl_date)
                                        <small class="text-teal-400 font-mono d-block" style="font-size: 0.72rem;">CCL Worked: {{ \Carbon\Carbon::parse($leave->ccl_date)->format('d M Y') }}</small>
                                    @endif
                                    @if(!empty($leave->reason))
                                        <small class="text-slate-400 d-block mt-0.5" style="font-size: 0.72rem; max-width: 170px;" title="{{ $leave->reason }}">
                                            <em>"{{ Str::limit($leave->reason, 45) }}"</em>
                                        </small>
                                    @endif
                                    @if(!empty($leave->work_arrangement) && is_array($leave->work_arrangement) && count($leave->work_arrangement) > 0)
                                        <span class="badge bg-slate-900 border border-info border-opacity-50 text-info mt-1" style="font-size: 0.65rem;" title="{{ count($leave->work_arrangement) }} Substitute Periods">
                                            <i class="fa-solid fa-user-clock me-1"></i>{{ count($leave->work_arrangement) }} Sub(s)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="d-block">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</small>
                                    <span class="badge bg-warning text-dark fw-bold">{{ $leave->session_type }}</span>
                                </td>
                                <td class="fw-bold text-white">{{ number_format($leave->total_days, 1) }}</td>
                                <td>
                                    @if($leave->hod_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->hod_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->coordinator_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->coordinator_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($leave->coordinator_status === 'N/A')
                                        <span class="badge bg-secondary" title="Aided Stream - Not Applicable">N/A (Aided)</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->principal_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->principal_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->overall_status === 'Approved')
                                        <span class="badge bg-success px-2.5 py-1">Final Approved</span>
                                    @elseif($leave->overall_status === 'Rejected')
                                        <span class="badge bg-danger px-2.5 py-1">Rejected</span>
                                    @else
                                        <span class="badge bg-info text-dark px-2.5 py-1">{{ str_replace('_', ' ', $leave->overall_status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    @php
                                        $canApprove = false;
                                        $stageToProcess = 'HOD';
                                        $currentUserRole = Session::get('userRole');

                                        if ($leave->overall_status === 'Pending_HOD' && in_array($currentUserRole, ['HOD', 'Principal', 'Admin', 'Super_Admin'])) {
                                            $canApprove = true;
                                            $stageToProcess = 'HOD';
                                        } elseif ($leave->overall_status === 'Pending_Coordinator' && (in_array($currentUserRole, ['Academic_Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance', 'Principal', 'Admin', 'Super_Admin']) || str_contains(strtolower((string)$currentUserRole), 'coordinator'))) {
                                            $canApprove = true;
                                            $stageToProcess = 'Coordinator';
                                        } elseif ($leave->overall_status === 'Pending_Principal' && in_array($currentUserRole, ['Principal', 'Admin', 'Super_Admin', 'Chairman'])) {
                                            $canApprove = true;
                                            $stageToProcess = 'Principal';
                                        }
                                    @endphp

                                    <div class="d-inline-flex gap-1.5 align-items-center">
                                        @if($canApprove)
                                            <button type="button" 
                                                    class="btn btn-sm btn-approve py-1 px-2.5 fw-bold shadow-sm" 
                                                    onclick="openActionModal({{ $leave->id }}, '{{ addslashes($leave->staff_name) }}', '{{ $leave->leave_code }}', '{{ $leave->leave_type }}', '{{ $leave->total_days }}', '{{ $stageToProcess }}', 'Approved', '{{ addslashes($leave->reason) }}', {{ json_encode($leave->work_arrangement ?? []) }})"
                                                    title="Quick Approve">
                                                <i class="fa-solid fa-check me-1"></i> Approve
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger py-1 px-2.5 fw-bold shadow-sm" 
                                                    onclick="openActionModal({{ $leave->id }}, '{{ addslashes($leave->staff_name) }}', '{{ $leave->leave_code }}', '{{ $leave->leave_type }}', '{{ $leave->total_days }}', '{{ $stageToProcess }}', 'Rejected', '{{ addslashes($leave->reason) }}', {{ json_encode($leave->work_arrangement ?? []) }})"
                                                    title="Quick Reject">
                                                <i class="fa-solid fa-xmark me-1"></i> Reject
                                            </button>
                                        @else
                                            @if($leave->hod_status === 'Approved')
                                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-40 py-1 px-2 text-nowrap" style="font-size: 0.68rem;">
                                                    <i class="fa-solid fa-check-double me-1"></i>HOD Approved
                                                </span>
                                                @if($leave->overall_status !== 'Approved' && $leave->overall_status !== 'Rejected' && in_array($currentUserRole, ['HOD', 'Principal', 'Admin']))
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning py-0.5 px-2 text-nowrap" 
                                                            style="font-size: 0.68rem;"
                                                            onclick="openActionModal({{ $leave->id }}, '{{ addslashes($leave->staff_name) }}', '{{ $leave->leave_code }}', '{{ $leave->leave_type }}', '{{ $leave->total_days }}', 'HOD', 'Rejected', '{{ addslashes($leave->reason) }}', {{ json_encode($leave->work_arrangement ?? []) }})"
                                                            title="Revoke / Reject HOD Approval">
                                                        <i class="fa-solid fa-rotate-left me-1"></i> Revoke
                                                    </button>
                                                @endif
                                            @elseif($leave->hod_status === 'Rejected')
                                                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-40 py-1 px-2 text-nowrap" style="font-size: 0.68rem;">
                                                    <i class="fa-solid fa-xmark me-1"></i>HOD Rejected
                                                </span>
                                            @endif
                                        @endif
                                        <a href="/staff/leave/{{ $leave->id }}/pdf" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-pill text-nowrap" title="View / Print PDF Application">
                                            <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-secondary">
                                    No staff leave records found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(isset($summary))
                    <tfoot class="border-top border-secondary">
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end text-slate-400 py-2.5" style="background-color: #0f172a !important; color: #94a3b8 !important;">TOTAL DAYS IN SELECTION:</td>
                            <td class="text-info font-mono fs-6 py-2.5" style="background-color: #0f172a !important; color: #38bdf8 !important;">{{ number_format($summary['TOTAL_DAYS'], 1) }}</td>
                            <td colspan="5" style="background-color: #0f172a !important;"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Academic Year Category Summary Bar (Full Width, Uncrowded & Slim) -->
        @if(isset($summary))
        <div class="card-custom py-1.5 px-3 w-100 mt-auto">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-1.5 text-nowrap">
                    <i class="fa-solid fa-chart-pie text-info" style="font-size: 0.85rem;"></i>
                    <span class="fw-bold text-white small" style="font-size: 0.74rem;">AY {{ $academicYear ?? date('Y') }} Leave Totals:</span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between flex-grow-1 flex-wrap gap-1.5 text-nowrap">
                    <!-- CL -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">CL (Casual):</span>
                        <strong class="text-info font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['CL'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">/ 15 d</span>
                    </div>

                    <!-- CCL -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">CCL (Comp.):</span>
                        <strong class="text-teal-400 font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['CCL'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">d</span>
                    </div>

                    <!-- DL -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">DL (Duty):</span>
                        <strong class="text-warning font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['DL'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">d</span>
                    </div>

                    <!-- ML -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">ML (Medical):</span>
                        <strong class="text-primary font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['ML'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">d</span>
                    </div>

                    <!-- LOP -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">LOP (Pay Loss):</span>
                        <strong class="text-danger font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['LOP'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">d</span>
                    </div>

                    <!-- SL / Others -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-secondary border-opacity-25 flex-grow-1 text-center">
                        <span class="text-secondary" style="font-size: 0.68rem;">SL / Other:</span>
                        <strong class="text-purple-400 font-mono ms-1" style="font-size: 0.78rem;">{{ number_format($summary['SL'] + $summary['OTHERS'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">d</span>
                    </div>

                    <!-- Total Taken -->
                    <div class="px-2.5 py-1 rounded bg-slate-900 border border-info border-opacity-40 flex-grow-1 text-center">
                        <span class="text-info fw-bold" style="font-size: 0.68rem;">Total Taken:</span>
                        <strong class="text-white font-mono ms-1" style="font-size: 0.82rem;">{{ number_format($summary['TOTAL_DAYS'], 1) }}</strong>
                        <span class="text-slate-400" style="font-size: 0.65rem;">Days</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Approval / Rejection Modal -->
    <div class="modal fade" id="leaveActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-slate-900 text-white border border-secondary shadow-lg">
                <div class="modal-header border-bottom border-secondary py-2.5">
                    <h6 class="modal-title fw-bold" id="actionModalTitle">Process Staff Leave</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="p-3 rounded-3 bg-slate-800 border border-secondary border-opacity-25 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-1.5">
                            <div>
                                <h6 class="fw-bold text-white mb-0" id="modalStaffName"></h6>
                                <span class="badge bg-info text-dark mt-1" id="modalLeaveType"></span>
                            </div>
                            <span class="badge bg-secondary font-mono" id="modalLeaveCode"></span>
                        </div>
                        <div class="small text-slate-300 mt-2">
                            <span class="text-secondary">Duration:</span> <strong class="text-white" id="modalTotalDays"></strong> Day(s)
                        </div>
                        <div class="small text-slate-300 mt-2">
                            <span class="text-secondary fw-bold">Reason:</span>
                            <div id="modalLeaveReason" class="fst-italic text-slate-200 bg-slate-900 p-2 rounded mt-1 border border-secondary border-opacity-25"></div>
                        </div>
                        <div id="modalWorkArrangementBox" class="small text-slate-300 mt-2 d-none">
                            <span class="text-secondary fw-bold">Substitute Work Arrangements:</span>
                            <div id="modalWorkArrangementList" class="mt-1 space-y-1"></div>
                        </div>
                    </div>

                    <input type="hidden" id="modalLeaveId">
                    <input type="hidden" id="modalStage">
                    <input type="hidden" id="modalAction">

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            Remarks / Notes <small class="text-slate-400 font-normal">(Optional)</small>
                        </label>
                        <textarea id="modalRemarks" class="form-control bg-dark text-white border-secondary small" rows="2" placeholder="e.g. Approved / Substitute arrangements verified."></textarea>
                    </div>

                    <div id="modalAlert" class="alert d-none py-2 px-3 small mt-2 mb-0"></div>
                </div>
                <div class="modal-footer border-top border-secondary py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="modalSubmitBtn" onclick="submitLeaveDecision()" class="btn btn-success btn-sm px-3 fw-bold">
                        <span id="modalSubmitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                        <span id="modalSubmitText">Confirm</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let actionModalInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('leaveActionModal');
            if (modalEl) {
                actionModalInstance = new bootstrap.Modal(modalEl);
            }
        });

        function openActionModal(leaveId, staffName, leaveCode, leaveType, totalDays, stage, action, reason, workArrangement) {
            document.getElementById('modalLeaveId').value = leaveId;
            document.getElementById('modalStage').value = stage;
            document.getElementById('modalAction').value = action;
            document.getElementById('modalRemarks').value = '';

            document.getElementById('modalStaffName').innerText = staffName;
            document.getElementById('modalLeaveCode').innerText = leaveCode;
            document.getElementById('modalLeaveType').innerText = leaveType;
            document.getElementById('modalTotalDays').innerText = totalDays;
            document.getElementById('modalLeaveReason').innerText = reason || 'No specific reason provided.';

            // Render Work Arrangement
            const workBox = document.getElementById('modalWorkArrangementBox');
            const workList = document.getElementById('modalWorkArrangementList');
            workList.innerHTML = '';
            if (workArrangement && Array.isArray(workArrangement) && workArrangement.length > 0) {
                workArrangement.forEach(arr => {
                    const row = document.createElement('div');
                    row.className = 'p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25 small d-flex justify-content-between text-slate-300 mb-1';
                    row.innerHTML = `<span>${arr.date || ''} (Period ${arr.period || ''})</span><span class="text-cyan fw-bold">${arr.substitute_name || 'Assigned Staff'}</span>`;
                    workList.appendChild(row);
                });
                workBox.classList.remove('d-none');
            } else {
                workBox.classList.add('d-none');
            }

            const modalTitle = document.getElementById('actionModalTitle');
            const submitBtn = document.getElementById('modalSubmitBtn');
            const submitText = document.getElementById('modalSubmitText');
            const alertEl = document.getElementById('modalAlert');
            alertEl.className = 'alert d-none';

            if (action === 'Approved') {
                modalTitle.innerHTML = `<i class="fa-solid fa-circle-check text-success me-1"></i> Approve Leave — ${stage} Level`;
                submitBtn.className = 'btn btn-success btn-sm px-3 fw-bold';
                submitText.innerText = 'Confirm Approval';
            } else {
                modalTitle.innerHTML = `<i class="fa-solid fa-circle-xmark text-danger me-1"></i> Reject Leave — ${stage} Level`;
                submitBtn.className = 'btn btn-danger btn-sm px-3 fw-bold';
                submitText.innerText = 'Confirm Rejection';
            }

            if (actionModalInstance) {
                actionModalInstance.show();
            }
        }

        function submitLeaveDecision() {
            const leaveId = document.getElementById('modalLeaveId').value;
            const stage = document.getElementById('modalStage').value;
            const action = document.getElementById('modalAction').value;
            const remarks = document.getElementById('modalRemarks').value;

            const alertEl = document.getElementById('modalAlert');
            const spinner = document.getElementById('modalSubmitSpinner');
            const submitBtn = document.getElementById('modalSubmitBtn');

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

            alertEl.className = 'alert d-none';
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;

            fetch('/api/staff/leave/process-approval', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    leave_id: leaveId,
                    stage: stage,
                    action: action,
                    remarks: remarks
                })
            })
            .then(res => res.json())
            .then(data => {
                spinner.classList.add('d-none');
                submitBtn.disabled = false;
                if (data.status === 'SUCCESS') {
                    alertEl.className = 'alert alert-success py-2 px-3 small';
                    alertEl.innerHTML = `<i class="fa-solid fa-check me-1"></i> ${data.message || 'Leave updated successfully!'}`;
                    alertEl.classList.remove('d-none');
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    alertEl.className = 'alert alert-danger py-2 px-3 small';
                    alertEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data.message || 'Action failed.'}`;
                    alertEl.classList.remove('d-none');
                }
            })
            .catch(err => {
                spinner.classList.add('d-none');
                submitBtn.disabled = false;
                alertEl.className = 'alert alert-danger py-2 px-3 small';
                alertEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> Network error: ${err.message}`;
                alertEl.classList.remove('d-none');
            });
        }
    </script>
</body>
</html>
