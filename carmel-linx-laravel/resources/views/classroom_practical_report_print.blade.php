<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Evaluation Register - {{ $subject->subject_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 10mm 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px double #333;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 13px;
            margin: 0 0 5px 0;
            font-weight: normal;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            color: #555;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .meta-info td {
            padding: 3px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px 3px;
            text-align: center;
            font-size: 10px;
            word-break: break-word;
        }
        .report-table th {
            background-color: #f2f2f2;
            font-size: 9px;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33%;
            text-align: center;
            padding-top: 40px;
            font-weight: bold;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        .close-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .close-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <div class="no-print action-bar">
        <button class="print-btn" onclick="window.print()">Print Register</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>PRACTICAL EVALUATION REGISTER (REVISION 2021)</h3>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%">Batch / Class:</td>
            <td width="35%" style="color: #111;">{{ $cleanedBatch }}</td>
            <td width="15%">Semester:</td>
            <td width="35%">Semester {{ $subject->semester }}</td>
        </tr>
        <tr>
            <td>Subject Name:</td>
            <td>{{ $subject->subject_name }} ({{ $subject->subject_code }})</td>
            <td>Date of Report:</td>
            <td>{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 3%">Roll</th>
                <th style="width: 10%">PRN (SBTE)</th>
                <th style="width: 15%">Name</th>
                <th style="width: 5.5%">Rough Record<br>(7.5)</th>
                <th style="width: 5.5%">Fair Record<br>(10)</th>
                <th style="width: 5.5%">Obs &amp; Prep<br>(10)</th>
                <th style="width: 5.5%">Proc &amp; Punct.<br>(10)</th>
                <th style="width: 6.5%">Lab Work<br>(37.5)</th>
                <th style="width: 5.5%">Open Ended<br>(7.5)</th>
                <th style="width: 5.5%">Attend.<br>(15)</th>
                <th style="width: 5%">Test 1<br>(15)</th>
                <th style="width: 5%">Test 2<br>(15)</th>
                <th style="width: 5.5%">Test Avg<br>(15)</th>
                <th style="width: 7.5%; background-color: #e6fffa;">Final CIA<br>(75)</th>
                <th style="width: 5.5%; background-color: #e6f0ff;">Board Exam<br>(50)</th>
                <th style="width: 5.5%; background-color: #f7e6ff;">Total<br>(125)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $boardVal = $student->board_exam_marks !== null ? $student->board_exam_marks : null;
                    $totalScore = $boardVal !== null ? ($student->total_internal + $boardVal) : '-';
                    $t1Val = isset($student->tests['Test 1']['total']) ? number_format($student->tests['Test 1']['total'], 1) : '0.0';
                    $t2Val = isset($student->tests['Test 2']['total']) ? number_format($student->tests['Test 2']['total'], 1) : '0.0';
                @endphp
                <tr>
                    <td>{{ $student->roll_no ?? '-' }}</td>
                    <td class="font-mono">{{ $student->sbte_reg_no ?? $student->reg_no }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                    <td>{{ number_format($student->avg_rough_record ?? 0, 2) }}</td>
                    <td>{{ number_format($student->avg_fair_record ?? 0, 2) }}</td>
                    <td>{{ number_format($student->avg_obs_prep ?? 0, 2) }}</td>
                    <td>{{ number_format($student->avg_proc_punct ?? 0, 2) }}</td>
                    <td style="font-weight: bold;">{{ number_format($student->avg_lab_work, 2) }}</td>
                    <td>{{ number_format($student->micro_project ?? $student->open_ended ?? 0, 1) }}</td>
                    <td>{{ number_format($student->attendance_marks, 1) }}</td>
                    <td>{{ $t1Val }}</td>
                    <td>{{ $t2Val }}</td>
                    <td>{{ number_format($student->tests['average'] ?? 0, 2) }}</td>
                    <td style="font-weight: bold; background-color: #fafdfc;">{{ number_format($student->total_internal, 2) }}</td>
                    <td style="font-weight: bold; background-color: #fafdff;">{{ $boardVal !== null ? number_format($boardVal, 1) : '-' }}</td>
                    <td style="font-weight: bold; background-color: #fffafd;">{{ $totalScore }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>Name & Signature of Lab Assessor</td>
            <td>Name & Signature of Coordinator</td>
            <td>Head of Department</td>
        </tr>
    </table>

</body>
</html>
