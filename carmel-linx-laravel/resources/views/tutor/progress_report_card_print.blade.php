<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress Report Card - {{ $classroom['id'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            color: #111827;
            margin: 0 auto;
            padding: 8px;
            font-size: 11px;
            line-height: 1.4;
            background-color: #f3f4f6;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding: 10px 14px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .btn {
            background-color: #0284c7;
            color: #ffffff;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn:hover {
            background-color: #0369a1;
        }
        .btn-secondary {
            background-color: #4b5563;
        }
        .btn-secondary:hover {
            background-color: #374151;
        }
        .card-sheet {
            background-color: #ffffff;
            border: 2px solid #1e293b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            page-break-after: always;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .card-sheet:last-child {
            page-break-after: auto;
            margin-bottom: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 900;
            color: #0f172a;
        }
        .header h2 {
            font-size: 13px;
            margin: 0 0 3px 0;
            font-weight: 700;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .student-bio-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            margin-bottom: 14px;
            background-color: #f8fafc;
        }
        .student-bio-grid td {
            padding: 5.5px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .student-bio-grid strong {
            color: #0f172a;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid #334155;
            padding: 5.5px 6px;
            text-align: center;
            font-size: 9.5px;
        }
        .marks-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
        }
        .marks-table td.align-left {
            text-align: left;
            padding-left: 8px;
        }
        .marks-table tr:nth-child(even) td {
            background-color: #fbfcfd;
        }
        .summary-dashboard {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }
        .summary-box {
            flex: 1;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            text-align: center;
        }
        .summary-box .label {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .summary-box .val {
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
            margin-top: 2px;
            font-family: monospace;
        }
        .rank-highlight {
            color: #b45309 !important;
        }
        .eligibility-box {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 9.5px;
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
        }
        .signatures-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures-table td {
            width: 33.33%;
            text-align: center;
            padding-top: 45px;
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
            vertical-align: bottom;
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .card-sheet {
                border: 2px solid #000;
                box-shadow: none;
                margin-bottom: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <span style="font-size: 12px; font-weight: 600; color: #4b5563;">
            Student Progress Report Cards (PTM Mode) - {{ count($students) }} Student Card(s)
        </span>
        <a href="/tutor/progress-report/print?classroom_id={{ $classroom['id'] }}&mode=consolidated" class="btn btn-secondary">
            Switch to Broadsheet Register
        </a>
        <button class="btn" onclick="window.print()">
            Print Report Cards (A4)
        </button>
        <button class="btn btn-secondary" onclick="window.close()">Close</button>
    </div>

    @foreach($students as $st)
        <div class="card-sheet">
            <!-- Official Header -->
            <div class="header">
                <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                <h2>STATE BOARD OF TECHNICAL EDUCATION, KERALA</h2>
                <h3>STUDENT ACADEMIC PROGRESS REPORT CARD (REVISION 2021)</h3>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">
                    Continuous Internal Evaluation (CIE) & Series Examination Progress Record
                </div>
            </div>

            <!-- Student Bio Details Grid -->
            <table class="student-bio-grid">
                <tr>
                    <td style="width: 35%;"><strong>Student Name:</strong> {{ $st['name'] }}</td>
                    <td style="width: 25%;"><strong>Roll No:</strong> {{ $st['roll_no'] ?: '-' }}</td>
                    <td style="width: 40%;"><strong>SBTE Reg No:</strong> {{ $st['sbte_reg_no'] }}</td>
                </tr>
                <tr>
                    <td><strong>Branch / Department:</strong> {{ $classroom['branch_name'] }} ({{ $classroom['branch_code'] }})</td>
                    <td><strong>Semester:</strong> S{{ $classroom['semester'] }}</td>
                    <td><strong>Batch:</strong> {{ $classroom['batch'] }} ({{ $classroom['id'] }})</td>
                </tr>
                <tr>
                    <td><strong>Class Tutor / Advisor:</strong> {{ $classroom['tutor_name'] }}</td>
                    <td><strong>Report Date:</strong> {{ $classroom['date'] }}</td>
                    <td><strong>Guardian Contact:</strong> {{ $st['guardian_mobile'] ?: ($st['phone'] ?: 'N/A') }}</td>
                </tr>
            </table>

            <!-- Subject-wise Series Exam & Attendance Table -->
            <table class="marks-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 9%;">Code</th>
                        <th style="width: 32%;">Subject / Course Name</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 7%;">CO1</th>
                        <th style="width: 7%;">CO2</th>
                        <th style="width: 7%;">CO3</th>
                        <th style="width: 7%;">CO4</th>
                        <th style="width: 8%; background-color: #e2e8f0;">Total</th>
                        <th style="width: 14%; background-color: #dbeafe;">Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $idx = 1; @endphp
                    @foreach($subjects as $subj)
                        @php
                            $sData = $st['subjects'][$subj->id] ?? null;
                            $co = $sData['co_marks'] ?? ['CO1'=>null,'CO2'=>null,'CO3'=>null,'CO4'=>null];
                            $att = $sData['attendance'] ?? ['attended'=>0, 'conducted'=>0, 'percentage'=>100];
                        @endphp
                        <tr>
                            <td style="color: #64748b;">{{ $idx++ }}</td>
                            <td style="font-weight: 700; color: #1e3a8a;">{{ $subj->subject_code }}</td>
                            <td class="align-left" style="font-weight: 600;">{{ $subj->subject_name }}</td>
                            <td style="color: #4b5563; font-size: 8.5px;">{{ $subj->subject_type }}</td>
                            <td>{{ $co['CO1'] !== null ? $co['CO1'] : '-' }}</td>
                            <td>{{ $co['CO2'] !== null ? $co['CO2'] : '-' }}</td>
                            <td>{{ $co['CO3'] !== null ? $co['CO3'] : '-' }}</td>
                            <td>{{ $co['CO4'] !== null ? $co['CO4'] : '-' }}</td>
                            <td style="font-weight: 700; background-color: #f8fafc;">
                                {{ ($sData && $sData['subject_total'] !== null) ? $sData['subject_total'] : '-' }}
                            </td>
                            <td style="background-color: #f0fdf4;">
                                @if($att['conducted'] > 0)
                                    <strong style="color: {{ $att['percentage'] >= 75 ? '#15803d' : ($att['percentage'] >= 65 ? '#b45309' : '#b91c1c') }}">
                                        {{ $att['percentage'] }}%
                                    </strong> 
                                    <span style="font-size: 8px; color: #64748b;">({{ $att['attended'] }}/{{ $att['conducted'] }})</span>
                                @else
                                    <span style="color: #94a3b8;">100%</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Performance Overview Dashboard -->
            <div class="summary-dashboard">
                <div class="summary-box">
                    <div class="label">Grand Total Series Marks</div>
                    <div class="val" style="color: #1e3a8a;">
                        {{ $st['grand_total_marks'] > 0 ? $st['grand_total_marks'] : '0.0' }}
                    </div>
                </div>
                <div class="summary-box">
                    <div class="label">Semester Attendance</div>
                    <div class="val" style="color: {{ $st['overall_attendance'] >= 75 ? '#15803d' : ($st['overall_attendance'] >= 65 ? '#b45309' : '#b91c1c') }};">
                        {{ $st['overall_attendance'] }}%
                    </div>
                </div>
                <div class="summary-box">
                    <div class="label">Class Rank</div>
                    <div class="val rank-highlight">
                        @if($st['class_rank'] !== null)
                            #{{ $st['class_rank'] }}
                            <span style="font-size: 9px; font-weight: normal; color: #64748b;">of {{ $summary['total_students'] }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </div>
                </div>
                <div class="summary-box">
                    <div class="label">Attendance Eligibility</div>
                    <div class="val" style="font-size: 13px; color: {{ $st['status'] === 'Eligible' ? '#15803d' : ($st['status'] === 'Condonation' ? '#b45309' : '#b91c1c') }};">
                        {{ $st['status'] }}
                    </div>
                </div>
            </div>

            <!-- SBTE Kerala Attendance Compliance Note -->
            <div class="eligibility-box">
                <strong>SBTE Regulation 2021 (Clause 10 Note):</strong> 
                Minimum 75% aggregate semester attendance is mandatory to appear for the End Semester Board Examination (ESE). 
                Students having attendance between 65% - 74.9% are in Condonation shortage. Attendance below 65% results in detention.
            </div>

            <!-- Signatures Section -->
            <table class="signatures-table">
                <tr>
                    <td>
                        ______________________________________<br>
                        <strong>Signature of Parent / Guardian</strong><br>
                        Name: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    </td>
                    <td>
                        ______________________________________<br>
                        <strong>{{ $classroom['tutor_name'] ?: 'Class Tutor / Advisor' }}</strong><br>
                        Class Tutor / Faculty Advisor
                    </td>
                    <td>
                        ______________________________________<br>
                        <strong>Head of Department</strong><br>
                        Dept. of {{ $classroom['branch_name'] }}
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

</body>
</html>
