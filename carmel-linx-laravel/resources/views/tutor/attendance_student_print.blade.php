<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance & Condonation Statement - {{ $student['name'] }} ({{ $student['sbte_reg_no'] }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
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
            line-height: 1.4;
            font-size: 11px;
            padding: 15px;
        }
        .a4-container {
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px 24px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .header h2 {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 2px;
        }
        .header h3 {
            font-size: 11px;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .header p {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 600;
            margin-top: 2px;
        }
        .student-info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 4px;
        }
        .student-info-grid td {
            padding: 5px 10px;
            font-size: 10.5px;
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
            font-size: 11px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #0f172a;
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 3px;
            margin-bottom: 8px;
            margin-top: 10px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .report-table th, .report-table td {
            border: 1px solid #334155;
            padding: 5px 4px;
            text-align: center;
            font-size: 10px;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 6px;
        }
        .font-mono {
            font-family: monospace, Courier, sans-serif;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-eligible {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-condonation {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
        }
        .badge-detained {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .summary-box {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }
        .summary-card {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 4px;
            text-align: center;
            background: #f8fafc;
        }
        .summary-card .num {
            font-size: 16px;
            font-weight: 800;
            font-family: monospace, Courier, sans-serif;
            margin-top: 2px;
        }
        .summary-card .lbl {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }
        .summary-card.highlight {
            border: 1.5px solid #0284c7;
            background: #f0f9ff;
        }
        .summary-card.highlight .num {
            color: #0284c7;
            font-size: 18px;
        }
        .decision-box {
            padding: 10px 14px;
            border-radius: 5px;
            margin-bottom: 14px;
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
        .decision-detained {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #7f1d1d;
        }
        .decision-box h4 {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .decision-box p {
            font-size: 9.5px;
            line-height: 1.35;
        }
        .rules-card {
            border: 1px dashed #94a3b8;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.35;
        }
        .rules-card strong {
            color: #0f172a;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 45px;
            font-weight: 700;
            font-size: 10px;
            vertical-align: bottom;
            border-top: none;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 12px;
        }
        .btn {
            background-color: #0284c7;
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn:hover {
            background-color: #0369a1;
        }
        .btn-secondary {
            background-color: #475569;
        }
        .btn-secondary:hover {
            background-color: #334155;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .a4-container {
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="btn" onclick="window.print()">🖨️ Print Statement (A4)</button>
        <button class="btn btn-secondary" onclick="window.close()">Close Window</button>
    </div>

    <div class="a4-container">

        <!-- College Header -->
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
            <h2>DEPARTMENT OF {{ strtoupper($classroom->department ?? $classroom->branch ?? 'ENGINEERING') }}</h2>
            <h3>STUDENT ATTENDANCE & ESE ELIGIBILITY STATEMENT</h3>
            <p>SBTE Kerala Diploma Regulations 2021 — Clause 10 (Attendance & Condonation Rules)</p>
        </div>

        <!-- Student Meta Information Grid -->
        <table class="student-info-grid">
            <tr>
                <td class="label">Student Name:</td>
                <td class="value" style="font-size: 11.5px; font-weight: 800;">{{ $student['name'] }}</td>
                <td class="label">Roll Number:</td>
                <td class="value value-highlight">{{ $student['roll_no'] ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Register No (SBTE):</td>
                <td class="value value-highlight">{{ $student['sbte_reg_no'] }}</td>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_id }}</td>
            </tr>
            <tr>
                <td class="label">Program / Branch:</td>
                <td class="value">{{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</td>
                <td class="label">Current Semester:</td>
                <td class="value font-bold">Semester {{ $classroom->current_semester ?? 'Current' }}</td>
            </tr>
            <tr>
                <td class="label">Evaluation Period:</td>
                <td class="value" style="font-weight: 700; color: #0284c7;">{{ $period['label'] ?? 'Full Semester' }}</td>
                <td class="label">Date of Issue:</td>
                <td class="value">{{ date('d-m-Y') }}</td>
            </tr>
        </table>

        <!-- Subject-Wise Breakdown Table -->
        <div class="section-title">Subject-Wise Attendance Breakdown for the Period</div>
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 14%;">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 12%;">Course Type</th>
                    <th style="width: 12%;">Classes Conducted</th>
                    <th style="width: 12%;">Classes Attended</th>
                    <th style="width: 12%;">Attendance %</th>
                    <th style="width: 13%;">Subject Status</th>
                    <th style="width: 10%;">CIA Mark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $idx => $subj)
                    @php
                        $sData = $student['subjects'][$subj->id] ?? null;
                        $conducted = $sData['conducted'] ?? 0;
                        $attended = $sData['attended'] ?? 0;
                        $pct = $sData['percentage'] ?? ($conducted > 0 ? round(($attended / $conducted) * 100, 1) : 100.0);
                        $ciaMark = $sData['cia_attendance_mark'] ?? 0.0;
                    @endphp
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="font-mono font-bold">{{ $subj->subject_code }}</td>
                        <td class="align-left" style="font-weight: 600;">{{ $subj->subject_name }}</td>
                        <td style="font-size: 8.5px; text-transform: uppercase; color: #475569;">{{ $subj->subject_type ?? 'Theory' }}</td>
                        <td class="font-mono">{{ $conducted }}</td>
                        <td class="font-mono">{{ $attended }}</td>
                        <td class="font-mono font-bold" style="color: {{ $pct >= 75 ? '#15803d' : ($pct >= 65 ? '#b45309' : '#b91c1c') }};">
                            {{ $conducted > 0 ? $pct . '%' : '-' }}
                        </td>
                        <td>
                            @if($conducted == 0)
                                <span style="color: #64748b; font-size: 8.5px;">No Logs</span>
                            @elseif($pct >= 75)
                                <span class="status-badge badge-eligible">Normal (≥75%)</span>
                            @elseif($pct >= 65)
                                <span class="status-badge badge-condonation">Shortage</span>
                            @else
                                <span class="status-badge badge-detained">Severe (<65%)</span>
                            @endif
                        </td>
                        <td class="font-mono font-bold text-slate-700">
                            {{ $ciaMark }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 15px; color: #64748b;">No subjects assigned for this semester.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Aggregate Attendance Stats -->
        <div class="summary-box">
            <div class="summary-card">
                <div class="lbl">Total Classes Conducted</div>
                <div class="num text-slate-800">{{ $student['total_conducted'] }}</div>
            </div>
            <div class="summary-card">
                <div class="lbl">Total Classes Attended</div>
                <div class="num text-emerald-600">{{ $student['total_attended'] }}</div>
            </div>
            <div class="summary-card">
                <div class="lbl">Class Hours Missed</div>
                <div class="num text-rose-600">{{ max(0, $student['total_conducted'] - $student['total_attended']) }}</div>
            </div>
            <div class="summary-card highlight">
                <div class="lbl">Total Period Average %</div>
                <div class="num">{{ $student['overall_percentage'] }}%</div>
            </div>
        </div>

        <!-- Official Eligibility / Condonation Ruling Box -->
        @php
            $overallPct = $student['overall_percentage'];
        @endphp
        @if($overallPct >= 75.0)
            <div class="decision-box decision-eligible">
                <h4>✓ ESE ELIGIBLE — NORMAL CANDIDATE (Clause 10.1)</h4>
                <p>
                    The student has secured an aggregate attendance of <strong>{{ $overallPct }}%</strong> (minimum prescribed is 75%). The student is fully eligible to register and appear for the upcoming SBTE End Semester Examination.
                </p>
            </div>
        @elseif($overallPct >= 65.0)
            <div class="decision-box decision-condonation">
                <h4>⚠️ CONDONATION REQUIRED — ATTENDANCE SHORTAGE (Clause 10.2)</h4>
                <p>
                    The student has secured an aggregate attendance of <strong>{{ $overallPct }}%</strong>, which is between <strong>65.0% and 74.9%</strong>. The student requires Condonation of Attendance Shortage approved by the Principal upon submitting genuine medical/valid reasons along with the prescribed SBTE condonation fee.
                </p>
            </div>
        @else
            <div class="decision-box decision-detained">
                <h4>✕ DETAINED / INELIGIBLE — SEVERE SHORTAGE (Clause 10.3)</h4>
                <p>
                    The student has secured an aggregate attendance of <strong>{{ $overallPct }}%</strong>, which is <strong>below 65.0%</strong>. As per SBTE regulations, shortage below 65% is <strong>NOT condonable</strong> under any circumstances. The candidate is not eligible to appear for the End Semester Examination and must repeat the semester with subsequent batch.
                </p>
            </div>
        @endif

        <!-- Regulatory Guidelines Box -->
        <div class="rules-card">
            <strong>SBTE Regulation 2021 Clause 10 Norms:</strong>
            <ul style="margin-left: 16px; margin-top: 3px;">
                <li><strong>Clause 10.1:</strong> A candidate shall secure a minimum of 75% attendance in aggregate in each semester to be eligible for the End Semester Examination.</li>
                <li><strong>Clause 10.2:</strong> The Principal is empowered to condone attendance shortage up to 10% (i.e. aggregate attendance not less than 65%) on valid medical/genuine grounds once during the entire course of study.</li>
                <li><strong>Clause 10.3:</strong> Candidates having less than 65% aggregate attendance shall be detained and must re-enroll for the semester.</li>
            </ul>
        </div>

        <!-- Signatures Table -->
        <table class="footer-signatures">
            <tr>
                <td>
                    __________________________________<br>
                    <strong>Signature of Class Tutor</strong><br>
                    <span style="font-size: 8px; color: #64748b; font-weight: normal;">Date: {{ date('d-m-Y') }}</span>
                </td>
                <td>
                    __________________________________<br>
                    <strong>Head of the Department</strong><br>
                    <span style="font-size: 8px; color: #64748b; font-weight: normal;">Seal & Signature</span>
                </td>
                <td>
                    __________________________________<br>
                    <strong>Principal / Head of Institution</strong><br>
                    <span style="font-size: 8px; color: #64748b; font-weight: normal;">Carmel Polytechnic College</span>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
