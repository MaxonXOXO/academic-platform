<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Attendance & Condonation Register - {{ $classroom->classroom_id }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111;
            margin: 0 auto;
            padding: 8px;
            font-size: 9px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #222;
            padding-bottom: 4px;
        }
        .header h1 {
            font-size: 13.5px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 11px;
            margin: 0 0 2px 0;
            font-weight: 600;
        }
        .header h3 {
            font-size: 9.5px;
            margin: 0;
            font-weight: bold;
            color: #0284c7;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 6px;
            font-size: 8.5px;
            font-weight: 600;
        }
        .meta-table td {
            padding: 1px 0;
        }
        .stats-summary-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }
        .stats-summary-card {
            flex: 1;
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            text-align: center;
            background: #f8fafc;
            border-radius: 3px;
        }
        .stats-summary-card .num {
            font-size: 13px;
            font-weight: bold;
            font-family: monospace, Courier, sans-serif;
        }
        .stats-summary-card .lbl {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #64748b;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th, .report-table td {
            border: 1px solid #333;
            padding: 3.5px 2px;
            text-align: center;
            font-size: 8px;
        }
        .report-table th {
            background-color: #f1f3f5;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .font-mono {
            font-family: monospace, Courier, sans-serif;
        }
        .status-eligible {
            color: #15803d;
            font-weight: bold;
        }
        .status-condonation {
            color: #b45309;
            font-weight: bold;
        }
        .status-special {
            color: #7e22ce;
            font-weight: bold;
        }
        .status-detained {
            color: #b91c1c;
            font-weight: bold;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 35px;
            font-weight: bold;
            font-size: 9px;
            vertical-align: bottom;
            border-top: none;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 8px;
        }
        .btn {
            background-color: #0284c7;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        @media print {
            .action-bar { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="btn" onclick="window.print()">🖨️ Print Condonation Register (A4)</button>
        <button class="btn" style="background:#475569;" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2>DEPARTMENT OF {{ strtoupper($classroom->department ?? $classroom->branch ?? 'ENGINEERING') }}</h2>
        <h3>CONSOLIDATED ATTENDANCE & CONDONATION ELIGIBILITY REGISTER</h3>
        <div style="font-size: 8.5px; color: #555; margin-top: 1px;">
            {{ $regulation_title }} | Evaluation Period: {{ $period['label'] ?? 'Full Semester' }}
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
            <td><strong>Semester:</strong> S{{ $classroom->current_semester ?? '' }}</td>
            <td><strong>Academic Scheme:</strong> {{ $scheme }} ({{ $scheme === 'R26' ? 'Rev 2026 Rule 7' : 'Rev 2021 Clause 10' }})</td>
            <td><strong>Date of Report:</strong> {{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="stats-summary-bar">
        <div class="stats-summary-card">
            <div class="lbl">Total Students</div>
            <div class="num">{{ $summary['total_students'] ?? 0 }}</div>
        </div>
        <div class="stats-summary-card" style="border-color:#86efac; background:#f0fdf4;">
            <div class="lbl">Original Eligible</div>
            <div class="num" style="color:#15803d;">{{ $summary['original_eligible_count'] ?? 0 }}</div>
        </div>
        <div class="stats-summary-card" style="border-color:#38bdf8; background:#f0f9ff;">
            <div class="lbl">Revised ESE Eligible</div>
            <div class="num" style="color:#0284c7;">{{ $summary['revised_eligible_count'] ?? 0 }}</div>
        </div>
        @if(($summary['promoted_count'] ?? 0) > 0)
            <div class="stats-summary-card" style="border-color:#a7f3d0; background:#ecfdf5;">
                <div class="lbl">Promoted via Duty Leaves</div>
                <div class="num" style="color:#059669;">+{{ $summary['promoted_count'] }}</div>
            </div>
        @endif
        <div class="stats-summary-card" style="border-color:#fcd34d; background:#fffbeb;">
            <div class="lbl">Condonation Required</div>
            <div class="num" style="color:#b45309;">{{ $summary['condonation_count'] ?? 0 }}</div>
        </div>
        @if($scheme === 'R26')
            <div class="stats-summary-card" style="border-color:#d8b4fe; background:#faf5ff;">
                <div class="lbl">Special Condonation (DTE)</div>
                <div class="num" style="color:#7e22ce;">{{ $summary['special_condonation_count'] ?? 0 }}</div>
            </div>
        @endif
        <div class="stats-summary-card" style="border-color:#fca5a5; background:#fef2f2;">
            <div class="lbl">Detained (&lt;{{ $scheme === 'R26' ? '50' : '65' }}%)</div>
            <div class="num" style="color:#b91c1c;">{{ $summary['detained_count'] ?? 0 }}</div>
        </div>
        <div class="stats-summary-card">
            <div class="lbl">Total Duty Hours</div>
            <div class="num">{{ $summary['total_special_hours'] ?? 0 }} hrs</div>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 3%;">Roll</th>
                <th style="width: 9%;">Reg No</th>
                <th style="width: 17%;">Student Name</th>
                <th style="width: 7%;">Conducted</th>
                <th style="width: 7%;">Attended</th>
                <th style="width: 7%;">Original %</th>
                <th style="width: 8%;">Original Status</th>
                <th style="width: 8%;">Duty Hours</th>
                <th style="width: 8%;">Revised Attd</th>
                <th style="width: 8%;">Revised ESE %</th>
                <th style="width: 10%;">Final ESE Status</th>
                <th style="width: 8%;">Shortage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $st)
                @php
                    $revStatus = $st['revised']['status'];
                    $statusClass = $revStatus === 'Eligible' ? 'status-eligible' : ($revStatus === 'Condonation' ? 'status-condonation' : ($revStatus === 'Special Condonation' ? 'status-special' : 'status-detained'));
                @endphp
                <tr>
                    <td class="font-mono font-bold">{{ $st['roll_no'] ?: '-' }}</td>
                    <td class="font-mono">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                    <td class="align-left" style="font-weight: 600;">{{ $st['name'] }}</td>
                    <td class="font-mono">{{ $st['original']['conducted'] }}</td>
                    <td class="font-mono">{{ $st['original']['attended'] }}</td>
                    <td class="font-mono font-bold">{{ $st['original']['percentage'] }}%</td>
                    <td style="font-size: 7.5px;">{{ $st['original']['status'] }}</td>
                    <td class="font-mono font-bold" style="color: {{ $st['special_attendance']['hours'] > 0 ? '#15803d' : '#64748b' }};">
                        {{ $st['special_attendance']['hours'] > 0 ? '+' . $st['special_attendance']['hours'] : '-' }}
                    </td>
                    <td class="font-mono">{{ $st['revised']['attended'] }}</td>
                    <td class="font-mono font-bold" style="color: #0284c7;">{{ $st['revised']['percentage'] }}%</td>
                    <td class="{{ $statusClass }}">
                        {{ $revStatus }}
                        @if($st['revised']['promoted'])
                            <span style="font-size: 6.5px; display: block; color: #15803d;">(Promoted)</span>
                        @endif
                    </td>
                    <td class="font-mono" style="color: {{ $st['revised']['shortage_pct'] > 0 ? '#b45309' : '#15803d' }};">
                        {{ $st['revised']['shortage_pct'] > 0 ? '-' . $st['revised']['shortage_pct'] . '%' : 'None' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="padding: 10px;">No students found for this classroom.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>
                __________________________________________<br>
                <strong>Class Tutor / Faculty Advisor</strong><br>
                <span style="font-size: 8px; font-weight: normal; color: #64748b;">Carmel Polytechnic College</span>
            </td>
            <td>
                __________________________________________<br>
                <strong>Head of Department</strong><br>
                <span style="font-size: 8px; font-weight: normal; color: #64748b;">Dept. of {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</span>
            </td>
            <td>
                __________________________________________<br>
                <strong>Principal / Head of Institution</strong><br>
                <span style="font-size: 8px; font-weight: normal; color: #64748b;">Carmel Polytechnic College, Alappuzha</span>
            </td>
        </tr>
    </table>

</body>
</html>
