<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress Report (REV2021) - {{ $classroom['id'] }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm 8mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            color: #111827;
            margin: 0 auto;
            padding: 10px;
            font-size: 9px;
            line-height: 1.3;
            background-color: #fff;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .btn {
            background-color: #0284c7;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
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
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 6px;
        }
        .header h1 {
            font-size: 15px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 800;
            color: #111827;
        }
        .header h2 {
            font-size: 12px;
            margin: 0 0 2px 0;
            font-weight: 700;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header h3 {
            font-size: 10px;
            margin: 0;
            font-weight: 600;
            color: #4b5563;
        }
        .meta-grid {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-collapse: collapse;
            margin-bottom: 8px;
            background-color: #f8fafc;
        }
        .meta-grid td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }
        .meta-grid strong {
            color: #1e293b;
        }
        .subject-legend {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #e2e8f0;
        }
        .subject-legend th, .subject-legend td {
            padding: 2.5px 6px;
            font-size: 8px;
            border: 1px solid #e2e8f0;
        }
        .subject-legend th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: left;
            color: #334155;
            text-transform: uppercase;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #475569;
            padding: 3px 1.5px;
            text-align: center;
            font-size: 8px;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 7.5px;
            font-weight: 700;
            color: #0f172a;
        }
        .report-table th.subj-header {
            background-color: #e2e8f0;
            font-size: 8px;
            border-bottom: 1px solid #475569;
        }
        .report-table th.co-sub {
            background-color: #f8fafc;
            font-size: 7px;
            font-weight: 600;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fbfcfd;
        }
        .col-tot {
            background-color: #f1f5f9;
            font-weight: 700;
        }
        .col-grand {
            background-color: #eff6ff !important;
            font-weight: 800;
            color: #1e3a8a;
            font-size: 8.5px;
        }
        .col-att {
            background-color: #f0fdf4 !important;
            font-weight: 700;
        }
        .rank-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-weight: 800;
            font-size: 8px;
        }
        .rank-top1 {
            background-color: #fef08a;
            color: #854d0e;
            border: 1px solid #ca8a04;
        }
        .rank-top2 {
            background-color: #e2e8f0;
            color: #334155;
            border: 1px solid #94a3b8;
        }
        .rank-top3 {
            background-color: #ffedd5;
            color: #9a3412;
            border: 1px solid #f97316;
        }
        .rank-general {
            color: #0f172a;
            font-weight: 700;
        }
        .status-eligible {
            color: #15803d;
            font-weight: 700;
        }
        .status-condonation {
            color: #b45309;
            font-weight: 700;
        }
        .status-detained {
            color: #b91c1c;
            font-weight: 700;
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
            font-weight: 700;
            font-size: 9.5px;
            vertical-align: bottom;
            color: #1e293b;
        }
        @media print {
            .action-bar {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .report-table tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <span style="font-size: 11px; font-weight: 600; color: #4b5563;">
            Showing {{ count($students) }} Students across {{ count($subjects) }} Subjects (REV2021)
        </span>
        <a href="/tutor/progress-report/print?classroom_id={{ $classroom['id'] }}&mode=all_cards" target="_blank" class="btn btn-secondary" title="Print Individual Report Card Slips for Parent-Teacher Meeting">
            PTM Student Cards View
        </a>
        <button class="btn" onclick="window.print()">
            Print Progress Register (A4 Landscape)
        </button>
        <button class="btn btn-secondary" onclick="window.close()">Close</button>
    </div>

    <!-- Official Header -->
    <div class="header">
        <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2>STATE BOARD OF TECHNICAL EDUCATION, KERALA</h2>
        <h3>STUDENT SEMESTER PROGRESS REPORT & CONSOLIDATED SERIES MARKS REGISTER</h3>
        <div style="font-size: 8.5px; color: #6b7280; margin-top: 2px;">
            Academic Scheme: <strong>{{ $classroom['scheme_name'] }}</strong> | Evaluation Framework: Continuous Internal Evaluation (CIE)
        </div>
    </div>

    <!-- Classroom & Academic Metadata Grid -->
    <table class="meta-grid">
        <tr>
            <td style="width: 25%;"><strong>Batch / Academic Year:</strong> {{ $classroom['batch'] }}</td>
            <td style="width: 25%;"><strong>Branch / Department:</strong> {{ $classroom['branch_name'] }} ({{ $classroom['branch_code'] }})</td>
            <td style="width: 25%;"><strong>Semester:</strong> Semester {{ $classroom['semester'] }} (S{{ $classroom['semester'] }})</td>
            <td style="width: 25%; text-align: right;"><strong>Report Date:</strong> {{ $classroom['date'] }}</td>
        </tr>
        <tr>
            <td><strong>Classroom ID:</strong> {{ $classroom['id'] }}</td>
            <td><strong>Class Tutor / Advisor:</strong> {{ $classroom['tutor_name'] }}</td>
            <td>
                <strong>Total Students:</strong> {{ $summary['total_students'] }} | 
                <strong>Class Average Attd:</strong> {{ $summary['average_attendance'] }}%
            </td>
            <td style="text-align: right;">
                @if(!empty($summary['topper']))
                    <strong>Class Topper:</strong> {{ $summary['topper']['name'] }} (Roll #{{ $summary['topper']['roll_no'] }}) - <strong>{{ $summary['topper']['marks'] }}M</strong>
                @else
                    <strong>Class Topper:</strong> N/A
                @endif
            </td>
        </tr>
    </table>

    <!-- Subject Code & Title Mapping Legend -->
    <table class="subject-legend">
        <thead>
            <tr>
                <th style="width: 8%;">Sub Code</th>
                <th style="width: 28%;">Course / Subject Title</th>
                <th style="width: 14%;">Subject Type</th>
                <th style="width: 8%;">Sub Code</th>
                <th style="width: 28%;">Course / Subject Title</th>
                <th style="width: 14%;">Subject Type</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subList = $subjects->values();
                $half = ceil($subList->count() / 2);
            @endphp
            @for($i = 0; $i < $half; $i++)
                @php
                    $s1 = $subList->get($i);
                    $s2 = $subList->get($i + $half);
                @endphp
                <tr>
                    <td style="font-weight: 700; color: #1e3a8a;">{{ $s1 ? $s1->subject_code : '-' }}</td>
                    <td>{{ $s1 ? $s1->subject_name : '-' }}</td>
                    <td style="color: #4b5563;">{{ $s1 ? $s1->subject_type : '-' }}</td>
                    <td style="font-weight: 700; color: #1e3a8a;">{{ $s2 ? $s2->subject_code : '-' }}</td>
                    <td>{{ $s2 ? $s2->subject_name : '-' }}</td>
                    <td style="color: #4b5563;">{{ $s2 ? $s2->subject_type : '-' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- Main Consolidated Progress Broadsheet Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 2.2%;">#</th>
                <th rowspan="2" style="width: 3.5%;">Roll<br>No</th>
                <th rowspan="2" style="width: 7.5%;">SBTE<br>Reg No</th>
                <th rowspan="2" style="width: 12%;">Student Name</th>
                @foreach($subjects as $subj)
                    <th colspan="5" class="subj-header" title="{{ $subj->subject_name }}">
                        {{ $subj->subject_code }}
                    </th>
                @endforeach
                <th rowspan="2" class="col-grand" style="width: 5%;">Grand<br>Total</th>
                <th rowspan="2" class="col-att" style="width: 5.5%;">Total<br>Attd %</th>
                <th rowspan="2" style="width: 4.5%; background-color: #fef9c3; color: #854d0e; font-weight: 800;">Class<br>Rank</th>
            </tr>
            <tr>
                @foreach($subjects as $subj)
                    <th class="co-sub">CO1</th>
                    <th class="co-sub">CO2</th>
                    <th class="co-sub">CO3</th>
                    <th class="co-sub">CO4</th>
                    <th class="co-sub col-tot">Tot</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $st)
                <tr>
                    <td style="color: #64748b;">{{ $idx + 1 }}</td>
                    <td style="font-weight: 700; font-family: monospace;">{{ $st['roll_no'] ?: '-' }}</td>
                    <td style="font-family: monospace; font-weight: 600;">{{ $st['sbte_reg_no'] }}</td>
                    <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>

                    @foreach($subjects as $subj)
                        @php
                            $sData = $st['subjects'][$subj->id] ?? null;
                            $co = $sData['co_marks'] ?? ['CO1'=>null,'CO2'=>null,'CO3'=>null,'CO4'=>null];
                        @endphp
                        <td>{{ $co['CO1'] !== null ? $co['CO1'] : '-' }}</td>
                        <td>{{ $co['CO2'] !== null ? $co['CO2'] : '-' }}</td>
                        <td>{{ $co['CO3'] !== null ? $co['CO3'] : '-' }}</td>
                        <td>{{ $co['CO4'] !== null ? $co['CO4'] : '-' }}</td>
                        <td class="col-tot">
                            {{ ($sData && $sData['subject_total'] !== null) ? $sData['subject_total'] : '-' }}
                        </td>
                    @endforeach

                    <!-- Grand Total Marks -->
                    <td class="col-grand">
                        {{ $st['grand_total_marks'] > 0 ? $st['grand_total_marks'] : '-' }}
                    </td>

                    <!-- Total Attendance % -->
                    <td class="col-att">
                        <div style="font-size: 8px; font-weight: 800;">{{ $st['overall_attendance'] }}%</div>
                        <div style="font-size: 6.5px;" class="{{ $st['status_badge'] }}">
                            {{ $st['status'] }}
                        </div>
                    </td>

                    <!-- Class Rank -->
                    <td style="background-color: #fffbeb;">
                        @if($st['class_rank'] === 1)
                            <span class="rank-badge rank-top1">Rank 1</span>
                        @elseif($st['class_rank'] === 2)
                            <span class="rank-badge rank-top2">Rank 2</span>
                        @elseif($st['class_rank'] === 3)
                            <span class="rank-badge rank-top3">Rank 3</span>
                        @elseif($st['class_rank'] !== null)
                            <span class="rank-badge rank-general">{{ $st['class_rank'] }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ (count($subjects) * 5) + 7 }}" style="padding: 20px; text-align: center; color: #64748b;">
                        No students found in this classroom.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Institutional Signatures -->
    <table class="footer-signatures">
        <tr>
            <td>
                ______________________________________<br>
                <strong>{{ $classroom['tutor_name'] ?: 'Class Tutor / Advisor' }}</strong><br>
                Class Tutor / Faculty Advisor
            </td>
            <td>
                ______________________________________<br>
                <strong>Head of the Department</strong><br>
                Dept. of {{ $classroom['branch_name'] }}
            </td>
            <td>
                ______________________________________<br>
                <strong>Principal</strong><br>
                Carmel Polytechnic College, Alappuzha
            </td>
        </tr>
    </table>

</body>
</html>
