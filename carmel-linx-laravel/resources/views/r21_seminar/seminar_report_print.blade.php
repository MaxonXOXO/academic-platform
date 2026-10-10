<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($reportType === 'internal' || $reportType === 'cia_submission' || $reportType === 'cia')
            SBTE Final CIA Mark Entry Statement (75M) - {{ $subject->subject_name }}
        @elseif($reportType === 'ese' || $reportType === 'grades')
            SBTE Seminar ESE Mark & Grade Report (75M) - {{ $subject->subject_name }}
        @elseif($reportType === 'attendance')
            Official TEAMS Attendance Statement & Marks (7.5M) - {{ $subject->subject_name }}
        @elseif($reportType === 'topic_splitup')
            Seminar Topic Splitup & Allocation Register - {{ $subject->subject_name }}
        @elseif($reportType === 'schedule')
            Seminar Presentation Schedule & Log - {{ $subject->subject_name }}
        @else
            Consolidated Seminar Evaluation Register (Clause 11.2.6) - {{ $subject->subject_name }}
        @endif
    </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @page {
            size: A4 {{ $reportType === 'cia_submission' ? 'landscape' : 'landscape' }};
            margin: 8mm 10mm 10mm 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0 auto;
            padding: 12px 16px;
            font-size: 10px;
            line-height: 1.35;
            background-color: #f8fafc;
        }
        .report-sheet {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 1140px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            position: relative;
        }
        .header .institute-title {
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header .dept-title {
            font-size: 12px;
            margin: 0 0 4px 0;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }
        .header .report-badge-title {
            display: inline-block;
            font-size: 11px;
            margin: 0;
            font-weight: 800;
            color: #0f172a;
            background: #e2e8f0;
            padding: 3px 12px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 9.5px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2.5px 4px;
            vertical-align: middle;
        }
        .meta-label {
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 9px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #334155;
            padding: 4px 3px;
            text-align: center;
            font-size: 9px;
            vertical-align: middle;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            line-height: 1.25;
        }
        .report-table th.sub-th {
            background-color: #f8fafc;
            font-size: 7.5px;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
            padding-right: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .font-mono-bold {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            font-size: 9px;
        }
        .grade-badge {
            font-weight: 800;
            font-size: 9.5px;
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        .footer-signatures td {
            text-align: center;
            padding-top: 38px;
            font-weight: 700;
            font-size: 9.5px;
            vertical-align: bottom;
            color: #1e293b;
        }
        .cert-statement {
            margin-top: 14px;
            padding: 8px 12px;
            background-color: #f8fafc;
            border: 1px dashed #94a3b8;
            font-size: 8.5px;
            color: #334155;
            line-height: 1.4;
            border-radius: 4px;
        }
        .stats-summary-grid {
            margin-top: 10px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
        }
        .stat-box {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            text-align: center;
            background: #ffffff;
            border-radius: 4px;
        }
        .stat-box-title {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }
        .stat-box-num {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }
        /* Interactive Action Bar (Screen Only) */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            background: #0e1628;
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid #1e293b;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 1140px;
            margin-left: auto;
            margin-right: auto;
        }
        .report-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .nav-btn {
            padding: 6px 11px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s;
            cursor: pointer;
            border: 1px solid #334155;
            color: #cbd5e1;
            background: #1e293b;
        }
        .nav-btn:hover {
            background: #334155;
            color: #ffffff;
        }
        .nav-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #3b82f6;
            font-weight: 700;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            shrink-0;
        }
        .btn-print {
            background: #10b981;
            color: #ffffff;
            border: 1px solid #059669;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s;
        }
        .btn-print:hover {
            background: #059669;
        }
        .btn-close {
            background: #475569;
            color: #ffffff;
            border: 1px solid #334155;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-close:hover {
            background: #334155;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: #ffffff;
            }
            .report-sheet {
                padding: 0;
                box-shadow: none;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .report-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .report-table th.sub-th {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .stat-box, .cert-statement {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- SCREEN ONLY: Tool Bar & Report Switcher -->
    <div class="no-print action-bar">
        <div class="report-nav">
            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-right: 4px;">Select Report:</span>
            
            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=internal" class="nav-btn {{ ($reportType === 'internal' || $reportType === 'cia_submission' || $reportType === 'cia') ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">verified</span>
                <span>1. CIA Report (75M)</span>
            </a>

            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=attendance" class="nav-btn {{ $reportType === 'attendance' ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">how_to_reg</span>
                <span>2. Attendance Report</span>
            </a>

            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=consolidated" class="nav-btn {{ $reportType === 'consolidated' ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">assignment</span>
                <span>3. Consolidated Report (75M)</span>
            </a>

            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=topic_splitup" class="nav-btn {{ $reportType === 'topic_splitup' ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">category</span>
                <span>4. Topic Splitup Report</span>
            </a>

            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=ese" class="nav-btn {{ ($reportType === 'ese' || $reportType === 'grades') ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">school</span>
                <span>5. ESE Grade Report</span>
            </a>

            <a href="/r21/classroom/seminar/{{ $subject->id }}/print?type=schedule" class="nav-btn {{ $reportType === 'schedule' ? 'active' : '' }}">
                <span class="material-symbols-rounded" style="font-size: 14px;">calendar_today</span>
                <span>6. Presentation Schedule Report</span>
            </a>
        </div>

        <div class="action-buttons">
            <button class="btn-print" onclick="window.print()">
                <span class="material-symbols-rounded" style="font-size: 15px;">print</span>
                <span>Print Document</span>
            </button>
            <button class="btn-close" onclick="window.close()">
                <span class="material-symbols-rounded" style="font-size: 14px;">close</span>
                <span>Close</span>
            </button>
        </div>
    </div>

    <!-- MAIN PRINTABLE SHEET -->
    <div class="report-sheet">

        <!-- ======================================================================= -->
        <!-- REPORT 1: CONSOLIDATED 6-RUBRIC EVALUATION REGISTER (CLAUSE 11.2.6) -->
        <!-- ======================================================================= -->
        @if($reportType === 'consolidated')
            <div class="header">
                <div class="institute-title">Carmel Polytechnic College, Alappuzha</div>
                <div class="dept-title">Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">CONSOLIDATED SEMINAR EVALUATION REGISTER (REGULATION CLAUSE 11.2.6 - REVISION 2021)</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 13%" class="meta-label">Course Title:</td>
                    <td style="width: 37%" class="meta-val"><strong>{{ $subject->subject_name }}</strong> ({{ $subject->formatted_subject_code ?? $subject->subject_code }})</td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Evaluation Scheme:</td>
                    <td class="meta-val">Continuous Assessment (<strong>CIA only, treated as ESE Mark - Max 75 Marks</strong>)</td>
                    <td class="meta-label">Date of Generation:</td>
                    <td class="meta-val">{{ date('d-m-Y') }} (Academic Year: {{ $currentYear }})</td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%">Roll</th>
                        <th rowspan="2" style="width: 9%">SBTE Reg No</th>
                        <th rowspan="2" style="width: 12%">Student Name</th>
                        <th rowspan="2" style="width: 13%">Seminar Topic</th>
                        <th rowspan="2" style="width: 9%">Assigned Guide</th>
                        <th rowspan="2" style="width: 3.5%">Att.<br>%</th>
                        <th rowspan="2" style="width: 8%">Evaluator</th>
                        <th colspan="6">Clause 11.2.6 Statutory Evaluation Rubrics</th>
                        <th rowspan="2" style="width: 5.5%">Final CIA<br>(75M)</th>
                        <th rowspan="2" style="width: 4.5%">SBTE<br>Grade</th>
                        <th rowspan="2" style="width: 4.5%">Result</th>
                    </tr>
                    <tr>
                        <th class="sub-th" style="width: 4.5%">Relevance<br>(7.5M)</th>
                        <th class="sub-th" style="width: 4.5%">Literature<br>(7.5M)</th>
                        <th class="sub-th" style="width: 5%">Presentation<br>(37.5M)</th>
                        <th class="sub-th" style="width: 4.5%">Discussion<br>(7.5M)</th>
                        <th class="sub-th" style="width: 4.5%">Report<br>(7.5M)</th>
                        <th class="sub-th" style="width: 4.5%">Attendance<br>(7.5M)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $st)
                        @php
                            $evalAssessors = $st['assessors'] ?? [];
                            $hasMultiple = count($evalAssessors) >= 2;
                        @endphp
                        @if($hasMultiple)
                            @php
                                $a1 = $evalAssessors[0];
                                $a2 = $evalAssessors[1];
                            @endphp
                            <!-- Assessor 1 (Member 1) Row -->
                            <tr>
                                <td rowspan="3">{{ $st['roll_no'] ?? '-' }}</td>
                                <td rowspan="3" class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                                <td rowspan="3" class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                                <td rowspan="3" class="align-left" style="font-size: 8px;">{{ $st['topic'] }}</td>
                                <td rowspan="3" class="align-left" style="font-size: 8.5px;">{{ $st['guide_name'] }}</td>
                                <td rowspan="3" style="font-size: 8.5px;">{{ $st['att_percentage'] }}%</td>
                                <td class="align-left" style="font-size: 8px; font-weight: 700; background-color: #f8fafc;">
                                    M1: {{ $a1['assessor_name'] }}
                                </td>
                                <td>{{ number_format($a1['relevance'], 1) }}</td>
                                <td>{{ number_format($a1['literature'], 1) }}</td>
                                <td>{{ number_format($a1['presentation'], 1) }}</td>
                                <td>{{ number_format($a1['interaction'], 1) }}</td>
                                <td>{{ number_format($a1['report'], 1) }}</td>
                                <td>{{ number_format($a1['attendance'], 1) }}</td>
                                <td class="font-mono-bold">{{ round($a1['total_score']) }}</td>
                                <td rowspan="3">
                                    <span class="grade-badge">{{ $st['letter_grade'] }}</span>
                                </td>
                                <td rowspan="3" style="font-weight: 700; {{ $st['result'] === 'Pass' ? 'color: #047857;' : ($st['result'] === 'Failed' ? 'color: #b91c1c;' : 'color: #64748b;') }}">
                                    {{ $st['result'] }}
                                </td>
                            </tr>
                            <!-- Assessor 2 (Member 2) Row -->
                            <tr>
                                <td class="align-left" style="font-size: 8px; font-weight: 700; background-color: #f8fafc;">
                                    M2: {{ $a2['assessor_name'] }}
                                </td>
                                <td>{{ number_format($a2['relevance'], 1) }}</td>
                                <td>{{ number_format($a2['literature'], 1) }}</td>
                                <td>{{ number_format($a2['presentation'], 1) }}</td>
                                <td>{{ number_format($a2['interaction'], 1) }}</td>
                                <td>{{ number_format($a2['report'], 1) }}</td>
                                <td>{{ number_format($a2['attendance'], 1) }}</td>
                                <td class="font-mono-bold">{{ round($a2['total_score']) }}</td>
                            </tr>
                            <!-- Consolidated Average Row -->
                            <tr style="background-color: #f1f5f9; font-weight: 700;">
                                <td class="align-left" style="font-size: 8px; font-weight: 800; background-color: #e2e8f0; color: #0f172a;">
                                    Consolidated Avg
                                </td>
                                <td>{{ number_format($st['relevance'], 1) }}</td>
                                <td>{{ number_format($st['literature'], 1) }}</td>
                                <td>{{ number_format($st['presentation'], 1) }}</td>
                                <td>{{ number_format($st['interaction'], 1) }}</td>
                                <td>{{ number_format($st['report'], 1) }}</td>
                                <td>{{ number_format($st['attendance'], 1) }}</td>
                                <td class="font-mono-bold" style="background-color: #e2e8f0; font-size: 10px;">
                                    {{ round($st['total_score']) }}
                                </td>
                            </tr>
                        @else
                            <!-- Single Assessor or Pending Evaluation Row -->
                            <tr>
                                <td>{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                                <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                                <td class="align-left" style="font-size: 8px;">{{ $st['topic'] }}</td>
                                <td class="align-left" style="font-size: 8.5px;">{{ $st['guide_name'] }}</td>
                                <td style="font-size: 8.5px;">{{ $st['att_percentage'] }}%</td>
                                <td class="align-left" style="font-size: 8px; font-weight: 600;">
                                    {{ count($evalAssessors) === 1 ? 'M1: ' . $evalAssessors[0]['assessor_name'] : '—' }}
                                </td>
                                <td>{{ $st['is_completed'] ? number_format($st['relevance'], 1) : '—' }}</td>
                                <td>{{ $st['is_completed'] ? number_format($st['literature'], 1) : '—' }}</td>
                                <td>{{ $st['is_completed'] ? number_format($st['presentation'], 1) : '—' }}</td>
                                <td>{{ $st['is_completed'] ? number_format($st['interaction'], 1) : '—' }}</td>
                                <td>{{ $st['is_completed'] ? number_format($st['report'], 1) : '—' }}</td>
                                <td>{{ $st['is_completed'] ? number_format($st['attendance'], 1) : '—' }}</td>
                                <td class="font-mono-bold" style="background-color: #f1f5f9; font-size: 9.5px;">
                                    {{ $st['is_completed'] ? round($st['total_score']) : '—' }}
                                </td>
                                <td>
                                    <span class="grade-badge">{{ $st['is_completed'] ? $st['letter_grade'] : '—' }}</span>
                                </td>
                                <td style="font-weight: 700; {{ $st['is_completed'] ? ($st['result'] === 'Pass' ? 'color: #047857;' : ($st['result'] === 'Failed' ? 'color: #b91c1c;' : 'color: #64748b;')) : 'color: #64748b;' }}">
                                    {{ $st['is_completed'] ? $st['result'] : 'Pending' }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div class="stats-summary-grid">
                <div class="stat-box">
                    <div class="stat-box-title">Total Enrolled</div>
                    <div class="stat-box-num">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Evaluated</div>
                    <div class="stat-box-num">{{ $completedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Passed / Failed</div>
                    <div class="stat-box-num">{{ $passedCount }} / {{ $failedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Pass Percentage</div>
                    <div class="stat-box-num">{{ $passRate }}%</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Batch Average</div>
                    <div class="stat-box-num">{{ $avgScoreOverall }} / 75</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Highest Score</div>
                    <div class="stat-box-num">{{ $highestScore }} / 75</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Lowest Score</div>
                    <div class="stat-box-num">{{ $lowestScore }} / 75</div>
                </div>
            </div>

            <table class="footer-signatures">
                <tr>
                    <td style="width: 25%">
                        Faculty Guide / Coordinator<br>
                        (Signature)
                    </td>
                    <td style="width: 25%">
                        Senior Assessor Member 1<br>
                        (Seminar Committee)
                    </td>
                    <td style="width: 25%">
                        Senior Assessor Member 2<br>
                        (Seminar Committee)
                    </td>
                    <td style="width: 25%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>

        <!-- ======================================================================= -->
        <!-- REPORT 2: OFFICIAL SBTE FINAL CIA MARK ENTRY STATEMENT (75M) -->
        <!-- ======================================================================= -->
        @elseif($reportType === 'internal' || $reportType === 'cia_submission')
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha (Institution Code: 043)</div>
                <div class="report-badge-title">DIPLOMA EXAMINATION (REVISION 2021) — CONTINUOUS INTERNAL ASSESSMENT (CIA) MARK STATEMENT</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 14%" class="meta-label">Branch / Department:</td>
                    <td style="width: 36%" class="meta-val">{{ $fullDepartment }}</td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Course Code &amp; Title:</td>
                    <td class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                    <td class="meta-label">Maximum Assessment:</td>
                    <td class="meta-val"><strong>CIA: 75 Marks</strong> (Statutory Clause 11.2.6: Only CIA, treated as Final ESE Mark)</td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 3.5%">Sl.<br>No</th>
                        <th style="width: 3.5%">Roll<br>No</th>
                        <th style="width: 10%">SBTE Reg Number</th>
                        <th style="width: 15%">Name of Candidate</th>
                        <th style="width: 5%">Att.<br>%</th>
                        <th style="width: 6.5%">Attendance<br>Mark (7.5)</th>
                        <th style="width: 7%">Seminar<br>Eval (67.5)</th>
                        <th style="width: 7.5%">Total CIA<br>Mark (75)</th>
                        <th style="width: 17%">Total Marks in Words</th>
                        <th style="width: 5%">Letter<br>Grade</th>
                        <th style="width: 4%">Grade<br>Point</th>
                        <th style="width: 6%">Result</th>
                        <th style="width: 10%">Signature of Candidate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td>{{ $st['att_percentage'] }}%</td>
                            <td>{{ $st['is_completed'] ? number_format($st['attendance_score'], 1) : '—' }}</td>
                            <td>{{ $st['is_completed'] ? number_format($st['seminar_score'], 1) : '—' }}</td>
                            <td class="font-mono-bold" style="background-color: #f1f5f9; font-size: 10px;">
                                {{ $st['is_completed'] ? round($st['total_score']) : '—' }}
                            </td>
                            <td class="align-left" style="font-size: 8px; font-weight: 600; text-transform: capitalize;">
                                {{ $st['score_in_words'] }}
                            </td>
                            <td>
                                <span class="grade-badge">{{ $st['is_completed'] ? $st['letter_grade'] : '—' }}</span>
                            </td>
                            <td>{{ $st['is_completed'] ? $st['grade_point'] : '—' }}</td>
                            <td style="font-weight: 700; {{ $st['is_completed'] ? ($st['result'] === 'Pass' ? 'color: #047857;' : ($st['result'] === 'Failed' ? 'color: #b91c1c;' : 'color: #64748b;')) : 'color: #64748b;' }}">
                                {{ $st['is_completed'] ? $st['result'] : 'Pending' }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Grade Statistics Summary -->
            <div class="stats-summary-grid" style="margin-top: 8px; margin-bottom: 8px;">
                <div class="stat-box">
                    <div class="stat-box-title">Total Registered</div>
                    <div class="stat-box-num">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Total Appeared</div>
                    <div class="stat-box-num">{{ $completedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Total Passed</div>
                    <div class="stat-box-num" style="color: #047857;">{{ $passedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Total Failed</div>
                    <div class="stat-box-num" style="color: #b91c1c;">{{ $failedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Pass Percentage</div>
                    <div class="stat-box-num">{{ $passRate }}%</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Average CIA Mark</div>
                    <div class="stat-box-num">{{ $avgScoreOverall }} / 75</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Grade Distribution</div>
                    <div class="stat-box-num" style="font-size: 9px;">
                        S:{{ $gradeStats['S'] }} A:{{ $gradeStats['A'] }} B:{{ $gradeStats['B'] }} C:{{ $gradeStats['C'] }} D:{{ $gradeStats['D'] }} E:{{ $gradeStats['E'] }} F:{{ $gradeStats['F'] }}
                    </div>
                </div>
            </div>

            <div class="cert-statement">
                <strong>STATUTORY CERTIFICATION &amp; DECLARATION:</strong><br>
                Certified that the continuous internal assessment marks entered above have been evaluated strictly as per the Kerala SBTE Diploma Curriculum (Revision 2021) Clause 11.2.6 statutory rubrics across all six assessment criteria (Relevance, Literature Survey, Presentation &amp; Communication, Discussion &amp; Viva, Seminar Report, and Attendance &amp; Punctuality). All marks have been verified with the institutional seminar evaluation registers and attendance logs.
            </div>

            <table class="footer-signatures" style="margin-top: 25px;">
                <tr>
                    <td style="width: 25%">
                        Course Coordinator / Guide<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Seminar Committee Member<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Principal<br>
                        (Institution Seal &amp; Signature)
                    </td>
                </tr>
            </table>

        <!-- ======================================================================= -->
        <!-- REPORT 3: SBTE END-SEMESTER EXAM (ESE) & FINAL GRADE STATEMENT (75M) -->
        <!-- ======================================================================= -->
        @elseif($reportType === 'ese' || $reportType === 'grades')
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha (Institution Code: 043) &bull; Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">DIPLOMA EXAMINATION (REVISION 2021) — SEMINAR END-SEMESTER &amp; FINAL GRADE STATEMENT (75 MARKS)</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 14%" class="meta-label">Course Code &amp; Title:</td>
                    <td style="width: 36%" class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} &bull; {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Curriculum Scheme:</td>
                    <td class="meta-val">Revision 2021 &bull; Statutory Clause 11.2.6 (<strong>Continuous CIA treated as End-Semester Exam — Max 75 Marks</strong>)</td>
                    <td class="meta-label">Date of Generation:</td>
                    <td class="meta-val">{{ date('d-m-Y') }} (Academic Year: {{ $currentYear }})</td>
                </tr>
            </table>

            <!-- SBTE 2021 Official 7-Point Grading Scale -->
            <div style="margin-bottom: 8px; padding: 5px 8px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px;">
                <div style="font-size: 8px; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 3px; letter-spacing: 0.3px;">
                    SBTE Kerala Revision 2021 Official Grading Scale (Regulation Clause 11.2.6 &bull; Maximum: 75 Marks &bull; Minimum Pass: 30 / 40%)
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 8px; text-align: center;">
                    <thead>
                        <tr style="background: #e2e8f0; font-weight: 700; color: #1e293b;">
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 14%;">Letter Grade</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">S</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">A</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">B</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">C</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">D</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 12%;">E</th>
                            <th style="border: 1px solid #94a3b8; padding: 2px 4px; width: 14%;">F (Failed)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700; background: #f1f5f9;">Marks Range (75M)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">67.5 – 75.0 (≥90%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">60.0 – 67.4 (≥80%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">52.5 – 59.9 (≥70%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">45.0 – 52.4 (≥60%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">37.5 – 44.9 (≥50%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">30.0 – 37.4 (≥40%)</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; color: #b91c1c; font-weight: 700;">&lt; 30.0 (&lt;40%)</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700; background: #f1f5f9;">Grade Point</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">10</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">9</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">8</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">7</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">6</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700;">5</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700; color: #b91c1c;">0</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; font-weight: 700; background: #f1f5f9;">Performance Definition</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Outstanding</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Excellent</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Very Good</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Good</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Fair</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px;">Satisfactory</td>
                            <td style="border: 1px solid #94a3b8; padding: 2px 4px; color: #b91c1c;">Failed</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 3.5%">Sl.<br>No</th>
                        <th style="width: 3.5%">Roll<br>No</th>
                        <th style="width: 10%">SBTE Reg Number</th>
                        <th style="width: 16%">Name of Candidate</th>
                        <th style="width: 10%">Continuous Seminar<br>Score (67.5M)</th>
                        <th style="width: 9%">Logged TEAMS<br>Attendance (7.5M)</th>
                        <th style="width: 8.5%">Final ESE / CIA<br>Mark (75M)</th>
                        <th style="width: 6%">SBTE Letter<br>Grade</th>
                        <th style="width: 5%">Grade<br>Point</th>
                        <th style="width: 7%">Result<br>Status</th>
                        <th style="width: 21.5%">Statutory Remarks / Verification</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td>{{ $st['is_completed'] ? number_format($st['seminar_score'], 1) : '—' }}</td>
                            <td>{{ $st['is_completed'] ? number_format($st['attendance_score'], 1) : '—' }}</td>
                            <td class="font-mono-bold" style="background-color: #f1f5f9; font-size: 10px;">
                                {{ $st['is_completed'] ? round($st['total_score']) : '—' }}
                            </td>
                            <td>
                                <span class="grade-badge {{ $st['letter_grade'] === 'S' ? 'color: #b45309;' : ($st['letter_grade'] === 'F' ? 'color: #b91c1c;' : '') }}">{{ $st['is_completed'] ? $st['letter_grade'] : '—' }}</span>
                            </td>
                            <td>{{ $st['is_completed'] ? $st['grade_point'] : '—' }}</td>
                            <td style="font-weight: 700; {{ $st['is_completed'] ? ($st['result'] === 'Pass' ? 'color: #047857;' : ($st['result'] === 'Failed' ? 'color: #b91c1c;' : 'color: #64748b;')) : 'color: #64748b;' }}">
                                {{ $st['is_completed'] ? $st['result'] : 'Pending' }}
                            </td>
                            <td class="align-left" style="font-size: 8px;">
                                @if($st['is_completed'])
                                    @if($st['letter_grade'] === 'S')
                                        Passed with Outstanding Performance (Grade S)
                                    @elseif($st['result'] === 'Pass')
                                        Passed as per Clause 11.2.6 criteria (Grade {{ $st['letter_grade'] }})
                                    @elseif($st['result'] === 'Failed')
                                        Failed &bull; Below 40% threshold (&lt;30 Marks)
                                    @else
                                        Evaluated
                                    @endif
                                @else
                                    <span style="color: #64748b;">Evaluation Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Grade Statistics Summary -->
            <div class="stats-summary-grid" style="margin-top: 8px; margin-bottom: 8px;">
                <div class="stat-box">
                    <div class="stat-box-title">Total Enrolled</div>
                    <div class="stat-box-num">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Appeared / Evaluated</div>
                    <div class="stat-box-num">{{ $completedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Passed / Failed</div>
                    <div class="stat-box-num"><span style="color: #047857;">{{ $passedCount }}</span> / <span style="color: #b91c1c;">{{ $failedCount }}</span></div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Pass Percentage</div>
                    <div class="stat-box-num">{{ $passRate }}%</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Class Average</div>
                    <div class="stat-box-num">{{ $avgScoreOverall }} / 75</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Highest Score</div>
                    <div class="stat-box-num">{{ $highestScore }} / 75</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Grade Scale (S-F)</div>
                    <div class="stat-box-num" style="font-size: 8.5px;">
                        S:{{ $gradeStats['S'] }} A:{{ $gradeStats['A'] }} B:{{ $gradeStats['B'] }} C:{{ $gradeStats['C'] }} D:{{ $gradeStats['D'] }} E:{{ $gradeStats['E'] }} F:{{ $gradeStats['F'] }}
                    </div>
                </div>
            </div>

            <div class="cert-statement">
                <strong>END-SEMESTER EXAMINATION RESULTS CERTIFICATION:</strong><br>
                Certified that the above marks and grades have been computed in compliance with Regulation Clause 11.2.6 of Kerala State Board of Technical Education (Revision 2021). The Continuous Internal Assessment mark (out of 75) forms the complete statutory End-Semester Examination award. TEAMS attendance percentage has been applied strictly for the attendance rubric (7.5 Marks).
            </div>

            <table class="footer-signatures" style="margin-top: 25px;">
                <tr>
                    <td style="width: 25%">
                        Faculty Guide / Evaluator<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Seminar Committee Member<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                    <td style="width: 25%">
                        Principal<br>
                        (Institution Seal &amp; Signature)
                    </td>
                </tr>
            </table>

        <!-- ======================================================================= -->
        <!-- REPORT 4: SEMINAR PRESENTATION SCHEDULE & TOPIC LOG -->
        <!-- ======================================================================= -->
        @elseif($reportType === 'schedule')
            <div class="header">
                <div class="institute-title">Carmel Polytechnic College, Alappuzha</div>
                <div class="dept-title">Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">SEMINAR PRESENTATION SCHEDULE &amp; GUIDE ALLOCATION LOG</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 14%" class="meta-label">Course Title:</td>
                    <td style="width: 36%" class="meta-val"><strong>{{ $subject->subject_name }}</strong> ({{ $subject->formatted_subject_code ?? $subject->subject_code }})</td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Academic Year:</td>
                    <td class="meta-val">{{ $currentYear }}</td>
                    <td class="meta-label">Date of Notification:</td>
                    <td class="meta-val">{{ date('d-m-Y') }}</td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 4%">Sl No</th>
                        <th style="width: 4%">Roll</th>
                        <th style="width: 11%">SBTE Reg No</th>
                        <th style="width: 16%">Student Name</th>
                        <th style="width: 28%">Approved Seminar Topic</th>
                        <th style="width: 15%">Faculty Guide</th>
                        <th style="width: 11%">Presentation Date</th>
                        <th style="width: 11%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td class="align-left" style="font-size: 8.5px;">{{ $st['topic'] }}</td>
                            <td class="align-left" style="font-size: 8.5px;">{{ $st['guide_name'] }}</td>
                            <td class="font-mono-bold" style="font-size: 8.5px;">{{ $st['presentation_date'] }}</td>
                            <td style="font-weight: 700; {{ $st['status'] === 'Completed' ? 'color: #047857;' : ($st['status'] === 'Scheduled' ? 'color: #2563eb;' : 'color: #d97706;') }}">
                                {{ $st['status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-signatures" style="margin-top: 40px;">
                <tr>
                    <td style="width: 50%">
                        Seminar Coordinator<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 50%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>

        <!-- ======================================================================= -->
        <!-- REPORT 5: OFFICIAL TEAMS ATTENDANCE STATEMENT & MARKS (CLAUSE 11.2.6)  -->
        <!-- ======================================================================= -->
        @elseif($reportType === 'attendance')
            <div class="header">
                <div class="institute-title">Carmel Polytechnic College, Alappuzha</div>
                <div class="dept-title">Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">OFFICIAL CLASS ATTENDANCE STATEMENT &amp; STATUTORY ATTENDANCE MARKS (CLAUSE 11.2.6)</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 14%" class="meta-label">Course Title:</td>
                    <td style="width: 36%" class="meta-val"><strong>{{ $subject->subject_name }}</strong> ({{ $subject->formatted_subject_code ?? $subject->subject_code }})</td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Academic Year:</td>
                    <td class="meta-val">{{ $currentYear }}</td>
                    <td class="meta-label">Statutory Weightage:</td>
                    <td class="meta-val"><strong>10% (7.5 Marks Max)</strong> &bull; Calculated from Official TEAMS Attendance Records</td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 4%">Sl No</th>
                        <th style="width: 4%">Roll</th>
                        <th style="width: 13%">SBTE Reg No</th>
                        <th style="width: 24%">Student Name</th>
                        <th style="width: 10%">TEAMS Log</th>
                        <th style="width: 10%">Attended</th>
                        <th style="width: 11%">Attendance %</th>
                        <th style="width: 12%">Attn Mark (7.5M)</th>
                        <th style="width: 12%">Student Signature</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td class="font-mono-bold">{{ $st['total_attendance_sessions'] ?? '—' }}</td>
                            <td class="font-mono-bold">{{ $st['attended_sessions'] ?? '—' }}</td>
                            <td class="font-mono-bold" style="{{ $st['att_percentage'] < 75 ? 'color: #dc2626; font-weight: 800;' : 'color: #047857;' }}">
                                {{ $st['att_percentage'] }}%
                            </td>
                            <td class="font-mono-bold" style="font-size: 11px; color: #0284c7;">
                                {{ number_format($st['attendance'], 1) }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 10px; font-size: 8.5px; color: #64748b; line-height: 1.4;">
                <strong>Statutory Conversion Formula:</strong> Per SBTE Polytechnic Regulations (Rev 2021) Clause 11.2.6, Attendance Marks (Max 7.5) = \((Att\% \times 7.5 / 100)\) rounded to nearest half-mark. Minimum 75% attendance is mandatory for academic eligibility.
            </div>

            <table class="footer-signatures" style="margin-top: 35px;">
                <tr>
                    <td style="width: 33%">
                        Course Faculty<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 33%">
                        Seminar Coordinator<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 34%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>

        <!-- ======================================================================= -->
        <!-- REPORT 6: SEMINAR TOPIC ALLOCATION & TECHNICAL SPLIT-UP REGISTER      -->
        <!-- ======================================================================= -->
        @elseif($reportType === 'topic_splitup')
            <div class="header">
                <div class="institute-title">Carmel Polytechnic College, Alappuzha</div>
                <div class="dept-title">Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">SEMINAR TOPIC ALLOCATION &amp; TECHNICAL SPLIT-UP REGISTER</div>
            </div>

            <table class="meta-table">
                <tr>
                    <td style="width: 14%" class="meta-label">Course Title:</td>
                    <td style="width: 36%" class="meta-val"><strong>{{ $subject->subject_name }}</strong> ({{ $subject->formatted_subject_code ?? $subject->subject_code }})</td>
                    <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                    <td style="width: 36%" class="meta-val">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Academic Year:</td>
                    <td class="meta-val">{{ $currentYear }}</td>
                    <td class="meta-label">Total Candidates:</td>
                    <td class="meta-val"><strong>{{ count($students) }} Students</strong></td>
                </tr>
            </table>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 4%">Sl No</th>
                        <th style="width: 4%">Roll</th>
                        <th style="width: 12%">SBTE Reg No</th>
                        <th style="width: 20%">Student Name</th>
                        <th style="width: 32%">Approved Seminar Topic</th>
                        <th style="width: 16%">Faculty Guide</th>
                        <th style="width: 12%">Presentation Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td class="align-left" style="font-weight: 600;">{{ $st['topic'] }}</td>
                            <td class="align-left">{{ $st['guide_name'] }}</td>
                            <td class="font-mono-bold">{{ $st['presentation_date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-signatures" style="margin-top: 40px;">
                <tr>
                    <td style="width: 50%">
                        Seminar Coordinator<br>
                        (Name &amp; Signature)
                    </td>
                    <td style="width: 50%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>
        @endif

    </div>

</body>
</html>
