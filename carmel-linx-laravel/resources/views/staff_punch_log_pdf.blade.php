<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Biometric Punch Log - {{ $staff->name ?? 'Staff' }} ({{ date('F Y', strtotime($startDate)) }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 12mm 14mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .institution-title {
            font-size: 16px;
            font-weight: 800;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .meta-table td {
            padding: 6px 10px;
            font-size: 11px;
            vertical-align: middle;
        }
        .meta-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 9.5px;
        }
        .meta-value {
            font-weight: 700;
            color: #0f172a;
        }
        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 14px;
        }
        .summary-card {
            padding: 8px 10px;
            text-align: center;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }
        .summary-card.present {
            background: #ecfdf5;
            border-color: #6ee7b7;
        }
        .summary-card.late {
            background: #fffbeb;
            border-color: #fcd34d;
        }
        .summary-card.early {
            background: #faf5ff;
            border-color: #d8b4fe;
        }
        .summary-card.punched {
            background: #f0f9ff;
            border-color: #7dd3fc;
        }
        .summary-num {
            font-size: 18px;
            font-weight: 900;
            font-family: monospace;
            line-height: 1.1;
        }
        .summary-card.present .summary-num { color: #059669; }
        .summary-card.late .summary-num { color: #d97706; }
        .summary-card.early .summary-num { color: #9333ea; }
        .summary-card.punched .summary-num { color: #0284c7; }
        .summary-lbl {
            font-size: 9px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .log-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .log-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
            padding: 6px 8px;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }
        .log-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .log-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .log-table tr.weekend {
            background-color: #f1f5f9;
        }
        .mono {
            font-family: monospace;
            font-size: 10.5px;
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-present { background: #d1fae5; color: #065f46; }
        .badge-late { background: #fef3c7; color: #92400e; }
        .badge-early { background: #f3e8ff; color: #6b21a8; }
        .badge-absent { background: #fee2e2; color: #991b1b; }
        .badge-weekend { background: #e2e8f0; color: #475569; }
        .badge-ccl { background: #e0f2fe; color: #0369a1; }
        .footer-note {
            margin-top: 18px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            font-size: 8.5px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="institution-title">Carmel Polytechnic College</div>
                <div class="doc-title">Self-Financing Biometric Time Punch Log Report</div>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                <div style="font-size: 12px; font-weight: 800; color: #0284c7;">
                    {{ date('F Y', strtotime($startDate)) }}
                </div>
                <div style="font-size: 9px; color: #64748b;">
                    Generated on {{ date('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td width="30%">
                <span class="meta-label">Staff Name</span><br>
                <span class="meta-value">{{ $staff->name ?? 'N/A' }}</span>
            </td>
            <td width="20%">
                <span class="meta-label">Staff ID</span><br>
                <span class="meta-value">{{ $staff->id ?? ($staff->mobile_no ?? 'N/A') }}</span>
            </td>
            <td width="25%">
                <span class="meta-label">Department</span><br>
                <span class="meta-value">{{ $staff->department ?? 'General' }}</span>
            </td>
            <td width="25%">
                <span class="meta-label">Designation</span><br>
                <span class="meta-value">{{ $staff->designation ?? 'Faculty / Staff' }}</span>
            </td>
        </tr>
    </table>

    <table class="summary-grid">
        <tr>
            <td width="25%">
                <div class="summary-card present">
                    <div class="summary-num">{{ $summary['present'] ?? 0 }}</div>
                    <div class="summary-lbl">Present Days</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card late">
                    <div class="summary-num">{{ $summary['late'] ?? 0 }}</div>
                    <div class="summary-lbl">Late Arrivals</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card early">
                    <div class="summary-num">{{ $summary['early_out'] ?? 0 }}</div>
                    <div class="summary-lbl">Early Departures</div>
                </div>
            </td>
            <td width="25%">
                <div class="summary-card punched">
                    <div class="summary-num">{{ $summary['total_punched'] ?? 0 }}</div>
                    <div class="summary-lbl">Total Days Punched</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="log-table">
        <thead>
            <tr>
                <th width="14%">Date</th>
                <th width="12%">Day</th>
                <th width="18%">Check-In</th>
                <th width="18%">Check-Out</th>
                <th width="18%">Duration</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $curDate = strtotime($startDate);
                $stopDate = strtotime($endDate);
                $todayDateStr = date('Y-m-d');
            @endphp
            @while($curDate <= $stopDate)
                @php
                    $dStr = date('Y-m-d', $curDate);
                    $dayName = date('D', $curDate);
                    $isWeekend = ($dayName === 'Sun' || $dayName === 'Sat');
                    $rec = $records[$dStr] ?? null;
                    $curDate = strtotime('+1 day', $curDate);
                @endphp
                <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                    <td><strong>{{ date('d-m-Y', strtotime($dStr)) }}</strong></td>
                    <td style="color: {{ $isWeekend ? '#6366f1' : '#475569' }}; font-weight: {{ $isWeekend ? '700' : 'normal' }};">
                        {{ $dayName }}
                    </td>
                    <td>
                        @if($rec && !empty($rec['in_time']))
                            <span class="mono" style="color: #059669;">{{ $rec['in_time'] }}</span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($rec && !empty($rec['out_time']))
                            <span class="mono" style="color: #0284c7;">{{ $rec['out_time'] }}</span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($rec && !empty($rec['duration']))
                            <span class="mono" style="color: #b45309;">{{ $rec['duration'] }}</span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($isWeekend)
                            @if($rec && !empty($rec['in_time']))
                                <span class="badge badge-ccl">Duty (CCL)</span>
                            @else
                                <span class="badge badge-weekend">Weekend</span>
                            @endif
                        @elseif($rec)
                            @if($rec['status'] === 'Present')
                                <span class="badge badge-present">✓ Present</span>
                            @elseif($rec['status'] === 'Late')
                                <span class="badge badge-late">⚠ Late In</span>
                            @elseif($rec['status'] === 'Early Out')
                                <span class="badge badge-early">↩ Early Out</span>
                            @else
                                <span class="badge badge-absent">✗ Absent</span>
                            @endif
                        @else
                            @if($dStr < $todayDateStr)
                                <span class="badge badge-absent">✗ No Record</span>
                            @elseif($dStr === $todayDateStr)
                                <span class="badge badge-ccl" style="background:#e0f2fe; color:#0284c7;">Today</span>
                            @else
                                <span style="color: #94a3b8;">—</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endwhile
        </tbody>
    </table>

    <div class="footer-note">
        Official Biometric Verification Record &bull; Carmel Linx Academic Platform &bull; Valid without physical signature
    </div>

</body>
</html>
