<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seminar Evaluation Report - {{ $subject->subject_name }}</title>
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
        <button class="print-btn" onclick="window.print()">Print Report</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>CONSOLIDATED SEMINAR EVALUATION REPORT (REVISION 2021)</h3>
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
                <th rowspan="2" style="width: 5%">Roll No</th>
                <th rowspan="2" style="width: 12%">Reg No</th>
                <th rowspan="2" style="width: 18%">Student Name</th>
                <th rowspan="2" style="width: 20%">Seminar Topic</th>
                <th rowspan="2" style="width: 12%">Guide Name</th>
                <th colspan="6">Evaluation Criteria Marks (Averaged)</th>
                <th rowspan="2" style="width: 8%">Total Marks<br>(75)</th>
            </tr>
            <tr>
                <th style="font-size:8px">Relevance<br>(7.5)</th>
                <th style="font-size:8px">Literature<br>(7.5)</th>
                <th style="font-size:8px">Slides/Deliv<br>(37.5)</th>
                <th style="font-size:8px">Discuss<br>(7.5)</th>
                <th style="font-size:8px">Report<br>(7.5)</th>
                <th style="font-size:8px">Attend<br>(7.5)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->roll_no ?? '-' }}</td>
                    <td class="font-mono">{{ $student->sbte_reg_no ?? $student->reg_no }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                    <td class="align-left">{{ $student->seminar_details['topic'] }}</td>
                    <td class="align-left">{{ $student->seminar_details['guide_name'] }}</td>
                    <td>{{ $student->seminar_details['relevance'] }}</td>
                    <td>{{ $student->seminar_details['literature'] }}</td>
                    <td>{{ $student->seminar_details['presentation'] }}</td>
                    <td>{{ $student->seminar_details['interaction'] }}</td>
                    <td>{{ $student->seminar_details['report'] }}</td>
                    <td>{{ $student->seminar_details['attendance'] }}</td>
                    <td style="font-weight: bold; background-color: #f9f9f9;">{{ round($student->seminar_details['total_score']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>Name & Signature of Guide</td>
            <td>Name & Signature of Coordinator</td>
            <td>Head of Department</td>
        </tr>
    </table>

</body>
</html>
