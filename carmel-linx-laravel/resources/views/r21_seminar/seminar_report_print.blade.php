<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Seminar Evaluation Register (Clause 11.2.6) - {{ $subject->subject_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm 12mm 10mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111;
            margin: 0 auto;
            padding: 10px;
            font-size: 10.5px;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #222;
            padding-bottom: 6px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 12.5px;
            margin: 0 0 3px 0;
            font-weight: 600;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            font-weight: bold;
            color: #333;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .meta-table td {
            padding: 2px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #333;
            padding: 4.5px 3px;
            text-align: center;
            font-size: 9.5px;
        }
        .report-table th {
            background-color: #f1f3f5;
            font-size: 8.5px;
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
        .grade-badge {
            font-weight: bold;
            font-size: 9.5px;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 35px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 45px;
            font-weight: bold;
            font-size: 10.5px;
            vertical-align: bottom;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
        }
        .btn {
            padding: 6px 14px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            border: 1px solid #1d4ed8;
        }
        .btn-close {
            background-color: #475569;
            color: white;
            border: 1px solid #334155;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .report-table th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="no-print action-bar">
        <button class="btn btn-print" onclick="window.print()">Print Report</button>
        <button class="btn btn-close" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>CONSOLIDATED SEMINAR EVALUATION REGISTER (REGULATION CLAUSE 11.2.6 - REVISION 2021)</h3>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 14%">Course Title:</td>
            <td style="width: 36%"><strong>{{ $subject->subject_name }}</strong> ({{ $subject->formatted_subject_code ?? $subject->subject_code }})</td>
            <td style="width: 14%">Semester &amp; Batch:</td>
            <td style="width: 36%">Semester {{ $classroom->current_semester ?? $subject->semester }} • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
        </tr>
        <tr>
            <td>Evaluation Scheme:</td>
            <td>Continuous Assessment (CIA only, treated as ESE Mark - <strong>Max 75 Marks</strong>)</td>
            <td>Date of Generation:</td>
            <td>{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3.5%">Roll</th>
                <th rowspan="2" style="width: 10%">SBTE Reg No</th>
                <th rowspan="2" style="width: 14%">Student Name</th>
                <th rowspan="2" style="width: 16%">Seminar Topic</th>
                <th rowspan="2" style="width: 10%">Assigned Guide</th>
                <th rowspan="2" style="width: 7%">Pres. Date</th>
                <th colspan="6">Clause 11.2.6 Evaluation Rubrics (Averaged)</th>
                <th rowspan="2" style="width: 6.5%">Final Total<br>(75M)</th>
                <th rowspan="2" style="width: 5%">SBTE<br>Grade</th>
                <th rowspan="2" style="width: 5%">Result</th>
            </tr>
            <tr>
                <th style="width: 4.8%">Relevance<br>(7.5M)</th>
                <th style="width: 4.8%">Literature<br>(7.5M)</th>
                <th style="width: 5.5%">Presentation<br>(37.5M)</th>
                <th style="width: 4.8%">Discussion<br>(7.5M)</th>
                <th style="width: 4.8%">Report<br>(7.5M)</th>
                <th style="width: 4.8%">Attendance<br>(7.5M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $st)
                <tr>
                    <td>{{ $st['roll_no'] ?? '-' }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $st['sbte_reg_no'] }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $st['name'] }}</td>
                    <td class="align-left" style="font-size: 8.5px;">{{ $st['topic'] }}</td>
                    <td class="align-left">{{ $st['guide_name'] }}</td>
                    <td style="font-family: monospace; font-size: 8.5px;">{{ $st['presentation_date'] }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['relevance'], 1) : '—' }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['literature'], 1) : '—' }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['presentation'], 1) : '—' }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['interaction'], 1) : '—' }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['report'], 1) : '—' }}</td>
                    <td>{{ $st['eval_count'] > 0 ? number_format($st['attendance'], 1) : '—' }}</td>
                    <td style="font-weight: bold; background-color: #f8fafc; font-size: 10px;">
                        {{ $st['eval_count'] > 0 ? number_format($st['total_score'], 1) : '—' }}
                    </td>
                    <td>
                        <span class="grade-badge">{{ $st['letter_grade'] }}</span>
                    </td>
                    <td style="font-weight: bold; {{ $st['result'] === 'Pass' ? 'color: #047857;' : ($st['result'] === 'Failed' ? 'color: #b91c1c;' : 'color: #64748b;') }}">
                        {{ $st['result'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>
                Senior Faculty Member 1<br>
                (Seminar Committee Assessor)
            </td>
            <td>
                Senior Faculty Member 2<br>
                (Seminar Committee Assessor)
            </td>
            <td>
                Head of Department<br>
                (Seal &amp; Signature)
            </td>
        </tr>
    </table>

</body>
</html>
