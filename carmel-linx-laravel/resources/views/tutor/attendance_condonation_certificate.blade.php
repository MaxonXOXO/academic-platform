<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBTE Attendance & Condonation Certificate - {{ $student->name }} ({{ $student->sbte_reg_no ?: $student->reg_no }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        :root {
            --primary: #0f172a;
            --accent: #0284c7;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            line-height: 1.35;
            font-size: 10.5px;
            padding: 12px;
        }
        .a4-container {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 18px 22px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 1px;
        }
        .header h2 {
            font-size: 11.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 2px;
        }
        .header h3 {
            font-size: 11px;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .header p {
            font-size: 9px;
            color: #64748b;
            font-weight: 600;
            margin-top: 1px;
        }
        .student-info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background: #f8fafc;
            border: 1px solid var(--border);
        }
        .student-info-grid td {
            padding: 4px 8px;
            font-size: 10px;
            border: 1px solid #e2e8f0;
        }
        .student-info-grid td.label {
            font-weight: 700;
            color: #475569;
            width: 18%;
            background: #f1f5f9;
        }
        .student-info-grid td.value {
            font-weight: 600;
            color: #0f172a;
            width: 32%;
        }
        .student-info-grid td.value-highlight {
            font-weight: 700;
            color: #0284c7;
            font-family: monospace, Courier, sans-serif;
        }
        .section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #0f172a;
            color: #ffffff;
            padding: 3.5px 8px;
            border-radius: 2px;
            margin-bottom: 6px;
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-title span.sub {
            font-size: 8.5px;
            font-weight: normal;
            opacity: 0.9;
            text-transform: none;
        }
        .summary-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }
        .summary-card {
            padding: 6px 8px;
            border: 1px solid var(--border);
            border-radius: 4px;
            text-align: center;
            background: #f8fafc;
        }
        .summary-card .lbl {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }
        .summary-card .num {
            font-size: 14px;
            font-weight: 800;
            font-family: monospace, Courier, sans-serif;
            margin-top: 1px;
            color: #0f172a;
        }
        .summary-card.highlight {
            border: 1.5px solid #0284c7;
            background: #f0f9ff;
        }
        .summary-card.highlight .num {
            color: #0284c7;
        }
        .summary-card.accent-emerald {
            border-color: #86efac;
            background: #f0fdf4;
        }
        .summary-card.accent-emerald .num {
            color: #15803d;
        }
        .summary-card.accent-amber {
            border-color: #fcd34d;
            background: #fffbeb;
        }
        .summary-card.accent-amber .num {
            color: #b45309;
        }
        .summary-card.accent-rose {
            border-color: #fca5a5;
            background: #fef2f2;
        }
        .summary-card.accent-rose .num {
            color: #b91c1c;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .report-table th, .report-table td {
            border: 1px solid #334155;
            padding: 4px 4px;
            text-align: center;
            font-size: 9px;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 6px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .font-mono {
            font-family: monospace, Courier, sans-serif;
        }
        .decision-box {
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 10px;
            border: 1.5px solid #cbd5e1;
        }
        .decision-eligible {
            background: #f0fdf4;
            border-color: #86efac;
            color: #14532d;
        }
        .decision-condonation {
            background: #fffbeb;
            border-color: #fcd34d;
            color: #78350f;
        }
        .decision-special {
            background: #faf5ff;
            border-color: #d8b4fe;
            color: #581c87;
        }
        .decision-detained {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #7f1d1d;
        }
        .decision-box h4 {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .decision-box p {
            font-size: 9px;
            line-height: 1.35;
        }
        .duty-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 700;
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            margin-right: 3px;
        }
        .duty-badge.ncc { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
        .duty-badge.nss { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .duty-badge.iedc { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .duty-badge.placement { background: #ede9fe; color: #5b21b6; border-color: #ddd6fe; }
        .duty-badge.menstrual { background: #fce7f3; color: #9d174d; border-color: #fbcfe8; }
        .duty-badge.pwd { background: #e0e7ff; color: #3730a3; border-color: #c7d2fe; }

        .rules-card {
            border: 1px dashed #94a3b8;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 3px;
            margin-bottom: 12px;
            font-size: 8px;
            color: #475569;
            line-height: 1.35;
        }
        .rules-card strong {
            color: #0f172a;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 35px;
            font-weight: 700;
            font-size: 9.5px;
            vertical-align: bottom;
            border-top: none;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 10px;
        }
        .btn {
            background-color: #0284c7;
            color: #fff;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn:hover { background-color: #0369a1; }
        .btn-secondary { background-color: #475569; }
        .btn-secondary:hover { background-color: #334155; }
        @media print {
            body { background: none; padding: 0; }
            .a4-container { border: none; box-shadow: none; padding: 0; margin: 0; width: 100%; min-height: auto; }
            .action-bar { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="btn" onclick="window.print()">🖨️ Print Certificate (A4)</button>
        <button class="btn btn-secondary" onclick="window.close()">Close Window</button>
    </div>

    <div class="a4-container">

        <!-- College Header -->
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
            <h2>DEPARTMENT OF {{ strtoupper($classroom->department ?? $classroom->branch ?? 'ENGINEERING') }}</h2>
            <h3>ATTENDANCE SHORTAGE CONDONATION CERTIFICATE & ABSENTEE LOG</h3>
            <p>
                @if($scheme === 'R26')
                    SBTE Kerala Diploma Curriculum Revision 2026 — Rule 7 (Attendance, Relaxations & Condonation)
                @else
                    SBTE Kerala Diploma Regulations 2021 — Clause 10 (Attendance & Condonation Rules)
                @endif
            </p>
        </div>

        <!-- Student Meta Information Grid -->
        <table class="student-info-grid">
            <tr>
                <td class="label">Student Name:</td>
                <td class="value" style="font-size: 11px; font-weight: 800;">{{ $student->name }}</td>
                <td class="label">Roll Number:</td>
                <td class="value value-highlight">{{ $student->roll_no ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Register No (SBTE):</td>
                <td class="value value-highlight">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_id }}</td>
            </tr>
            <tr>
                <td class="label">Program / Branch:</td>
                <td class="value">{{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</td>
                <td class="label">Current Semester:</td>
                <td class="value font-bold">Semester {{ $classroom->current_semester ?? 'Current' }} ({{ $scheme }})</td>
            </tr>
            <tr>
                <td class="label">Academic Scheme:</td>
                <td class="value font-bold text-sky-700">
                    {{ $scheme === 'R26' ? 'Revision 2026 (Rule 7)' : 'Revision 2021 (Clause 10)' }}
                </td>
                <td class="label">Date of Issue:</td>
                <td class="value">{{ date('d-m-Y') }}</td>
            </tr>
        </table>

        <!-- Attendance & Shortage Statistics Matrix -->
        <div class="summary-box">
            <div class="summary-card">
                <div class="lbl">Total Conducted Hours</div>
                <div class="num">{{ $stats['total_conducted'] }}</div>
            </div>
            <div class="summary-card">
                <div class="lbl">Actual Attended Hours</div>
                <div class="num text-slate-700">{{ $stats['original_attended'] }}</div>
            </div>
            <div class="summary-card {{ $stats['duty_hours_credited'] > 0 ? 'accent-emerald' : '' }}">
                <div class="lbl">{{ $stats['duty_hours_credited'] > 0 ? 'Duty Leaves Credited' : 'Total Missed Hours' }}</div>
                <div class="num {{ $stats['duty_hours_credited'] > 0 ? '' : 'text-slate-700' }}">
                    {{ $stats['duty_hours_credited'] > 0 ? '+' . $stats['duty_hours_credited'] : $stats['total_missed_hours'] }}
                </div>
            </div>
            <div class="summary-card highlight">
                <div class="lbl">Effective Exam Attd %</div>
                <div class="num">{{ $stats['effective_percentage'] }}%</div>
            </div>
        </div>

        <div class="summary-box" style="margin-top: -4px;">
            <div class="summary-card">
                <div class="lbl">Required Threshold</div>
                <div class="num">{{ $evaluation['required_pct'] }}%</div>
            </div>
            <div class="summary-card {{ $stats['gross_shortage_hours'] > 0 ? 'accent-rose' : 'accent-emerald' }}">
                <div class="lbl">Gross Shortage Hours</div>
                <div class="num">{{ $stats['gross_shortage_hours'] > 0 ? $stats['gross_shortage_hours'] . ' hrs' : 'None (0 hrs)' }}</div>
            </div>
            <div class="summary-card {{ $stats['net_shortage_hours'] > 0 ? 'accent-amber' : 'accent-emerald' }}">
                <div class="lbl">Net Shortage Hours</div>
                <div class="num">{{ $stats['net_shortage_hours'] > 0 ? $stats['net_shortage_hours'] . ' hrs' : 'None (0 hrs)' }}</div>
            </div>
            <div class="summary-card {{ $evaluation['status'] === 'Eligible' ? 'accent-emerald' : ($evaluation['status'] === 'Condonation' ? 'accent-amber' : 'accent-rose') }}">
                <div class="lbl">SBTE Exam Status</div>
                <div class="num" style="font-size: 11px;">{{ $evaluation['status'] }}</div>
            </div>
        </div>

        <!-- Official Ruling / Decision Box -->
        @php
            $stStatus = $evaluation['status'];
            $decBoxClass = $stStatus === 'Eligible' ? 'decision-eligible' : ($stStatus === 'Condonation' ? 'decision-condonation' : ($stStatus === 'Special Condonation' ? 'decision-special' : 'decision-detained'));
        @endphp
        <div class="decision-box {{ $decBoxClass }}">
            <h4>
                @if($stStatus === 'Eligible')
                    ✓ ESE ELIGIBLE — NORMAL CANDIDATE ({{ $evaluation['rule'] }})
                @elseif($stStatus === 'Condonation')
                    ⚠️ ATTENDANCE SHORTAGE — CONDONATION REQUIRED ({{ $evaluation['rule'] }})
                @elseif($stStatus === 'Special Condonation')
                    ⭐ SPECIAL CONDONATION REQUIRED — DTE SANCTION ({{ $evaluation['rule'] }})
                @else
                    ✕ INELIGIBLE / DETAINED — SEVERE ATTENDANCE SHORTAGE ({{ $evaluation['rule'] }})
                @endif
            </h4>
            <p>
                {{ $evaluation['decision'] }}
                @if($stStatus === 'Condonation')
                    Candidate must submit genuine medical certificate issued by a registered medical practitioner (Asst. Surgeon rank) along with the prescribed condonation fee of Rs. {{ $stats['fee_amount'] }}.
                @elseif($stStatus === 'Special Condonation')
                    Candidate must have passed at least 50% courses up to the previous semester and obtain Academic Council recommendation for DTE sanction (prescribed fee Rs. 1,500).
                @endif
            </p>
        </div>

        <!-- Special Attendance / Duty Leaves Credited Details (if any) -->
        @if($special_records->isNotEmpty())
            <div class="section-title">
                <span>Sanctioned Duty Leaves & Relaxations by Tutor (Exam Credit Only)</span>
                <span class="sub">Total Hours Credited: <strong>{{ $stats['duty_hours_credited'] }} hrs</strong></span>
            </div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 14%;">Date</th>
                        <th style="width: 10%;">Hours</th>
                        <th style="width: 18%;">Category</th>
                        <th>Event Description / Official Reason</th>
                        <th style="width: 16%;">Source / Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($special_records as $idx => $sRec)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="font-mono">{{ $sRec->date ? $sRec->date->format('d-m-Y') : 'Semester Credit' }}</td>
                            <td class="font-mono font-bold text-emerald-700">+{{ $sRec->hours }}</td>
                            <td>
                                @php
                                    $catClass = strtolower(preg_replace('/[^a-z]/', '', $sRec->category));
                                @endphp
                                <span class="duty-badge {{ $catClass }}">{{ $sRec->category }}</span>
                            </td>
                            <td class="align-left">{{ $sRec->reason ?: 'Officially Approved Event Duty Leave' }}</td>
                            <td style="font-size: 8px; color: #64748b;">{{ $sRec->source }} ({{ $sRec->recorded_by ?: 'Tutor' }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Itemized Chronological Absentee Table -->
        <div class="section-title">
            <span>Chronological Table of Absent Dates & Missed Class Hours</span>
            <span class="sub">Total Absent Dates: <strong>{{ count($absences) }}</strong> | Total Missed Hours: <strong>{{ $stats['total_missed_hours'] }} hrs</strong></span>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 11%;">Day</th>
                    <th style="width: 8%;">Hours</th>
                    <th style="width: 16%;">Period Slots</th>
                    <th style="width: 20%;">Course(s) Scheduled</th>
                    <th>Reason / Activity Record</th>
                    <th style="width: 12%;">Document</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absences as $idx => $ab)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="font-mono font-bold">{{ $ab['formatted_date'] }}</td>
                        <td>{{ $ab['day'] }}</td>
                        <td class="font-mono font-bold text-rose-700">{{ $ab['hours_count'] }}</td>
                        <td style="font-size: 8px;">{{ $ab['periods_str'] }}</td>
                        <td class="align-left font-mono" style="font-size: 8px;">{{ $ab['subjects_str'] }}</td>
                        <td class="align-left" style="font-size: 8.5px;">
                            @if(str_contains(strtolower($ab['category']), 'duty') || in_array($ab['category'], ['NCC', 'NSS', 'IEDC', 'Placement']))
                                <span class="duty-badge">{{ $ab['category'] }}</span>
                            @endif
                            {{ $ab['reason'] }}
                        </td>
                        <td style="font-size: 8px; color: {{ $ab['document_submitted'] !== 'No' ? '#15803d' : '#64748b' }}; font-weight: 600;">
                            {{ $ab['document_submitted'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 12px; color: #15803d; font-weight: bold;">
                            ✓ No absences recorded. Student maintained 100% attendance!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Regulatory Guidelines Box -->
        <div class="rules-card">
            @if($scheme === 'R26')
                <strong>SBTE Diploma Curriculum Revision 2026 — Rule 7 Provisions:</strong>
                <ul style="margin-left: 14px; margin-top: 2px;">
                    <li><strong>Rule 7.1:</strong> Mandatory minimum 75% attendance for ESE eligibility. Relaxations: 2% for Menstrual leave (min 73%), 5% for differently-abled PWD (min 70%).</li>
                    <li><strong>Rule 7.2:</strong> Duty Leave for officially approved co-curricular/extracurricular events permitted up to 10 days per semester with prior Principal approval.</li>
                    <li><strong>Rule 7.3:</strong> Regular condonation (60% to &lt;75%) granted on medical grounds by Principal (1st), RDTE/DTE (2nd), DTE (3rd). Special condonation (50% to &lt;60%) granted once during program by DTE (Fee: Rs. 1,500).</li>
                </ul>
            @else
                <strong>SBTE Diploma Regulations 2021 — Clause 10 Norms:</strong>
                <ul style="margin-left: 14px; margin-top: 2px;">
                    <li><strong>Clause 10.1:</strong> Minimum 75% aggregate attendance required in each semester to appear for End Semester Examination.</li>
                    <li><strong>Clause 10.2:</strong> Principal empowered to condone shortage up to 10% (min 65% aggregate attendance) on genuine medical/valid grounds once during the program.</li>
                    <li><strong>Clause 10.3:</strong> Shortage below 65% is strictly non-condonable. Candidate must be detained and repeat the semester.</li>
                </ul>
            @endif
        </div>

        <!-- Official Signatures Table -->
        <table class="footer-signatures">
            <tr>
                <td>
                    __________________________________<br>
                    <strong>Signature of Class Tutor</strong><br>
                    <span style="font-size: 7.5px; color: #64748b; font-weight: normal;">Date: {{ date('d-m-Y') }}</span>
                </td>
                <td>
                    __________________________________<br>
                    <strong>Head of the Department</strong><br>
                    <span style="font-size: 7.5px; color: #64748b; font-weight: normal;">Department Seal & Signature</span>
                </td>
                <td>
                    __________________________________<br>
                    <strong>Principal / Head of Institution</strong><br>
                    <span style="font-size: 7.5px; color: #64748b; font-weight: normal;">Carmel Polytechnic College</span>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
