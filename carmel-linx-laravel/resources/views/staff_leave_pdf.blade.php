<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Leave Application - {{ $leave->leave_code }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 10mm 14mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            font-size: 11.5px;
            line-height: 1.4;
            background-color: #f1f5f9;
        }

        /* Screen Preview Container: mimics an actual A4 sheet */
        @media screen {
            body {
                padding: 20px 10px;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
            }
            .page-container {
                width: 210mm;
                min-height: 297mm;
                background: #ffffff;
                box-shadow: 0 4px 25px rgba(15, 23, 42, 0.15);
                padding: 14mm 16mm 12mm 16mm;
                border-radius: 4px;
                border: 1px solid #cbd5e1;
            }
            .action-bar {
                width: 210mm;
                margin-bottom: 12px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            .page-container {
                width: 100% !important;
                min-height: auto !important;
                box-shadow: none !important;
                padding: 0 !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
            .section-block, .info-grid, .table-custom, .signatures-table {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }

        /* Header Elements */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .header-subtitle {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            margin-top: 2px;
        }
        .doc-title-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 4px 16px;
            font-size: 12px;
            font-weight: 800;
            border-radius: 3px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        /* Section Styling */
        .section-header {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            border-left: 3px solid #0284c7;
            padding-left: 7px;
            margin-top: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Information Grid */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .info-grid td {
            padding: 5px 9px;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            vertical-align: middle;
        }
        .info-grid .label {
            background-color: #f8fafc;
            font-weight: 700;
            color: #334155;
            width: 22%;
        }

        /* Table Custom */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .table-custom th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            font-size: 10.5px;
            padding: 5px 8px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .table-custom td {
            border: 1px solid #cbd5e1;
            padding: 4.5px 8px;
            font-size: 10.5px;
            vertical-align: middle;
        }

        /* Approval & Stamp Boxes */
        .signatures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-top: 4px;
        }
        .stamp-box {
            border: 1.5px dashed #94a3b8;
            border-radius: 6px;
            padding: 6px 8px;
            text-align: center;
            background: #fafafa;
            min-height: 85px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stamp-title {
            font-weight: 800;
            font-size: 9.5px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }
        .badge-status {
            display: inline-block;
            padding: 1.5px 8px;
            border-radius: 10px;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-approved { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Footer System Audit */
        .footer-audit {
            margin-top: 14px;
            padding-top: 6px;
            border-top: 1px solid #cbd5e1;
            font-size: 9px;
            color: #64748b;
            text-align: center;
            line-height: 1.3;
        }
    </style>
</head>
<body>

    <!-- On-Screen Action Bar (Hidden when printing) -->
    <div class="action-bar no-print">
        <div style="font-size: 12px; font-weight: 700; color: #475569;">
            <i class="fa-solid fa-file-pdf text-danger me-1"></i> A4 Leave Slip Preview
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.close(); if(!window.closed){ window.history.back(); }" style="background: #475569; color: white; border: none; padding: 7px 16px; font-size: 12px; font-weight: 700; border-radius: 4px; cursor: pointer;">
                <i class="fa-solid fa-xmark me-1"></i> Close
            </button>
            <button onclick="window.print()" style="background: #0284c7; color: white; border: none; padding: 7px 18px; font-size: 12px; font-weight: 700; border-radius: 4px; cursor: pointer; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);">
                <i class="fa-solid fa-print me-1"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Page Container (Single A4 Sheet) -->
    <div class="page-container">

        <!-- Institutional Header -->
        <table class="header-table">
            <tr>
                <td style="width: 58px; vertical-align: middle;">
                    <div style="width: 46px; height: 46px; background: #0f172a; color: white; border-radius: 8px; font-weight: 900; font-size: 19px; display: flex; align-items: center; justify-content: center; text-align: center; line-height: 46px;">
                        CL
                    </div>
                </td>
                <td style="vertical-align: middle;">
                    <div class="header-title">Carmel Polytechnic College</div>
                    <div class="header-subtitle">Carmel Linx Academic Platform & Staff Governance System</div>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 140px;">
                    <div style="font-size: 10px; font-weight: 700; color: #64748b;">APPLICATION NO:</div>
                    <div style="font-size: 13px; font-weight: 800; color: #0284c7; font-family: monospace;">{{ $leave->leave_code }}</div>
                </td>
            </tr>
        </table>

        <div style="text-align: center;">
            <div class="doc-title-badge">Formal Staff Leave Application</div>
        </div>

        <!-- Section 1: Staff Information -->
        <div class="section-block">
            <div class="section-header">1. Applicant Profile</div>
            <table class="info-grid">
                <tr>
                    <td class="label">Staff Name:</td>
                    <td style="font-weight: 700; font-size: 11.5px;">{{ $leave->staff_name }}</td>
                    <td class="label">Staff ID / Mobile:</td>
                    <td style="font-family: monospace; font-weight: 600;">{{ $leave->staff_mobile }}</td>
                </tr>
                <tr>
                    <td class="label">Designation:</td>
                    <td>{{ $leave->designation }}</td>
                    <td class="label">Department:</td>
                    <td><strong style="color: #0369a1;">{{ $leave->department }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Section 2: Leave Details -->
        <div class="section-block">
            <div class="section-header">2. Leave Schedule & Justification</div>
            <table class="info-grid">
                <tr>
                    <td class="label">Leave Category:</td>
                    <td style="font-weight: 700; color: #0369a1;">{{ $leave->leave_type }}</td>
                    <td class="label">Session Mode:</td>
                    <td><strong style="color: #b45309;">{{ $leave->session_type }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Leave Period:</td>
                    <td colspan="3">
                        <strong>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y (l)') }}</strong>
                        &nbsp;to&nbsp;
                        <strong>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y (l)') }}</strong>
                    </td>
                </tr>
                @if($leave->ccl_date || str_contains($leave->leave_type, 'Compensatory') || str_contains($leave->leave_type, 'CCL'))
                <tr>
                    <td class="label">CCL Worked Duty Date:</td>
                    <td colspan="3"><strong style="color: #0f766e;">{{ $leave->ccl_date ? \Carbon\Carbon::parse($leave->ccl_date)->format('d M Y (l)') : 'N/A' }}</strong></td>
                </tr>
                @endif
                <tr>
                    <td class="label">Total Leave Duration:</td>
                    <td colspan="3">
                        <strong style="font-size: 12px; color: #0f172a;">{{ number_format($leave->total_days, 1) }} {{ $leave->total_days == 1 ? 'Day' : 'Days' }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="label">Reason / Justification:</td>
                    <td colspan="3" style="font-style: italic; color: #334155;">{{ $leave->reason }}</td>
                </tr>
            </table>
        </div>

        <!-- Section 3: Work Arrangement -->
        <div class="section-block">
            <div class="section-header">3. Class Duty & Substitute Work Arrangements</div>
            @if(!empty($leave->work_arrangement) && is_array($leave->work_arrangement) && count($leave->work_arrangement) > 0)
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th style="width: 100px;">Date</th>
                            <th style="width: 80px;">Period</th>
                            <th>Classroom / Batch</th>
                            <th>Designated Substitute Staff</th>
                            <th style="width: 80px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leave->work_arrangement as $index => $arr)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td>{{ $arr['date'] ?? '-' }}</td>
                                <td>{{ $arr['period'] ?? '-' }}</td>
                                <td>{{ $arr['classroom'] ?? '-' }}</td>
                                <td><strong style="color: #0369a1;">{{ $arr['substitute_name'] ?? 'Assigned Staff' }}</strong></td>
                                <td style="text-align: center;"><span class="badge-status badge-approved">Confirmed</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 6px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; color: #64748b; font-style: italic; font-size: 10.5px; margin-bottom: 6px;">
                    No class timetable substitute arrangements were required for this period.
                </div>
            @endif
        </div>

        <!-- Section 4: Multi-Stage Approval Verification & Digital Signatures -->
        <div class="section-block">
            <div class="section-header">4. Multi-Stage Approval & Signatures</div>
            
            <table class="signatures-table">
                <tr>
                    <!-- Staff Signature -->
                    <td style="width: 25%; vertical-align: top;">
                        <div class="stamp-box">
                            <div class="stamp-title">Staff Signature</div>
                            <div style="font-weight: 700; font-size: 11px; margin-top: 3px;">{{ $leave->staff_name }}</div>
                            <div style="font-size: 9px; color: #64748b; margin-top: 2px;">{{ $leave->submitted_at ? \Carbon\Carbon::parse($leave->submitted_at)->format('d-M-Y H:i') : '-' }}</div>
                            <div style="font-size: 7.5px; font-family: monospace; color: #94a3b8; word-break: break-all; margin-top: 4px;">
                                HASH: {{ substr($leave->staff_signature_hash ?? 'N/A', 0, 14) }}...
                            </div>
                        </div>
                    </td>

                    <!-- Stage 1: HOD -->
                    <td style="width: 25%; vertical-align: top;">
                        <div class="stamp-box">
                            <div class="stamp-title">Stage 1: HOD</div>
                            <div style="margin-top: 2px;">
                                @if($leave->hod_status === 'Approved')
                                    <span class="badge-status badge-approved">APPROVED</span>
                                @elseif($leave->hod_status === 'Rejected')
                                    <span class="badge-status badge-rejected">REJECTED</span>
                                @else
                                    <span class="badge-status badge-pending">PENDING</span>
                                @endif
                            </div>
                            <div style="font-weight: 700; font-size: 10px; margin-top: 3px;">{{ $leave->hod_name ?? 'Head of Dept' }}</div>
                            <div style="font-size: 9px; color: #64748b;">{{ $leave->hod_action_at ? \Carbon\Carbon::parse($leave->hod_action_at)->format('d-M-Y H:i') : '-' }}</div>
                            @if($leave->hod_remarks)
                                <div style="font-size: 8.5px; font-style: italic; color: #475569; margin-top: 2px;">"{{ $leave->hod_remarks }}"</div>
                            @endif
                        </div>
                    </td>

                    <!-- Stage 2: Academic Coordinator -->
                    <td style="width: 25%; vertical-align: top;">
                        <div class="stamp-box">
                            <div class="stamp-title">Stage 2: Coordinator</div>
                            <div style="margin-top: 2px;">
                                @if($leave->coordinator_status === 'Approved')
                                    <span class="badge-status badge-approved">APPROVED</span>
                                @elseif($leave->coordinator_status === 'Rejected')
                                    <span class="badge-status badge-rejected">REJECTED</span>
                                @elseif($leave->coordinator_status === 'N/A')
                                    <span class="badge-status" style="background:#e2e8f0; color:#475569;">N/A (AIDED)</span>
                                @else
                                    <span class="badge-status badge-pending">PENDING</span>
                                @endif
                            </div>
                            <div style="font-weight: 700; font-size: 10px; margin-top: 3px;">{{ $leave->coordinator_name ?? 'Coordinator (SF)' }}</div>
                            <div style="font-size: 9px; color: #64748b;">{{ $leave->coordinator_action_at ? \Carbon\Carbon::parse($leave->coordinator_action_at)->format('d-M-Y H:i') : '-' }}</div>
                            @if($leave->coordinator_remarks)
                                <div style="font-size: 8.5px; font-style: italic; color: #475569; margin-top: 2px;">"{{ $leave->coordinator_remarks }}"</div>
                            @endif
                        </div>
                    </td>

                    <!-- Stage 3: Principal -->
                    <td style="width: 25%; vertical-align: top;">
                        <div class="stamp-box">
                            <div class="stamp-title">Stage 3: Principal</div>
                            <div style="margin-top: 2px;">
                                @if($leave->principal_status === 'Approved')
                                    <span class="badge-status badge-approved">APPROVED</span>
                                @elseif($leave->principal_status === 'Rejected')
                                    <span class="badge-status badge-rejected">REJECTED</span>
                                @else
                                    <span class="badge-status badge-pending">PENDING</span>
                                @endif
                            </div>
                            <div style="font-weight: 700; font-size: 10px; margin-top: 3px;">{{ $leave->principal_name ?? 'Principal' }}</div>
                            <div style="font-size: 9px; color: #64748b;">{{ $leave->principal_action_at ? \Carbon\Carbon::parse($leave->principal_action_at)->format('d-M-Y H:i') : '-' }}</div>
                            @if($leave->principal_remarks)
                                <div style="font-size: 8.5px; font-style: italic; color: #475569; margin-top: 2px;">"{{ $leave->principal_remarks }}"</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer System Audit -->
        <div class="footer-audit">
            This document is an authenticated institutional leave slip generated automatically by Carmel Linx Academic Platform.<br>
            Document Code: <strong>{{ $leave->leave_code }}</strong> &bull; Printed on: {{ date('d M Y, h:i A') }}
        </div>

    </div>

</body>
</html>
