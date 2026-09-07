<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Series Examination Marksheet - {{ $subject->subject_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm 15mm 12mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
            max-width: 210mm;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px double #333;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 17px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 13px;
            margin: 0 0 4px 0;
            font-weight: normal;
        }
        .header h3 {
            font-size: 12px;
            margin: 0;
            color: #444;
            font-weight: bold;
            text-transform: uppercase;
        }
        .meta-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-info td {
            padding: 4px 6px;
            border: 1px solid #ccc;
        }
        .meta-info td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 18%;
        }
        .meta-info td.value {
            width: 32%;
        }
        .scheme-box {
            border: 1px dashed #666;
            background: #fdfdfd;
            padding: 6px 10px;
            margin-bottom: 15px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px 5px;
            text-align: center;
            font-size: 10.5px;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 6px;
        }
        .font-mono {
            font-family: monospace, Courier, monospace;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 35px;
            font-weight: bold;
            font-size: 11px;
            border-top: none;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
        }
        .print-btn {
            background-color: #0f172a;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .close-btn {
            background-color: #64748b;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .action-bar {
                display: none;
            }
            .report-table th {
                background-color: #f1f5f9 !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="print-btn" onclick="window.print()">🖨️ Print Series Marksheet</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>PRACTICAL SERIES EXAMINATION MARKSHEET (REVISION 2021)</h3>
    </div>

    <table class="meta-info">
        <tr>
            <td class="label">Course Title:</td>
            <td class="value" style="font-weight: bold;">{{ $subject->subject_name }}</td>
            <td class="label">Course Code:</td>
            <td class="value font-mono" style="font-weight: bold;">{{ $subject->subject_code }}</td>
        </tr>
        <tr>
            <td class="label">Class / Batch:</td>
            <td class="value">{{ $cleanedBatch }}</td>
            <td class="label">Semester:</td>
            <td class="value">Semester {{ $subject->semester }}</td>
        </tr>
        <tr>
            <td class="label">Max Series Marks:</td>
            <td class="value"><strong>15 Marks</strong> (Avg of Test 1 &amp; Test 2)</td>
            <td class="label">Date of Report:</td>
            <td class="value font-mono">{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="scheme-box">
        <div><strong>Test 1 Scheme (15 Marks):</strong> CO1 &amp; CO2 Evaluation (Choice 1 out of 2)</div>
        <div><strong>Test 2 Scheme (15 Marks):</strong> CO3 &amp; CO4 Evaluation (Choice 1 out of 2)</div>
        <div><strong>Final Series CIA:</strong> Average of Test 1 and Test 2 (/15)</div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%">Sl No</th>
                <th style="width: 6%">Roll</th>
                <th style="width: 15%">PRN (SBTE)</th>
                <th>Student Name</th>
                <th style="width: 14%">Series Test 1<br>(15 Marks)</th>
                <th style="width: 14%">Series Test 2<br>(15 Marks)</th>
                <th style="width: 16%; background-color: #e6fffa;">Test Average<br>(15 Marks)</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $student)
                @php
                    $t1 = isset($student->tests['Test 1']['total']) ? (float)$student->tests['Test 1']['total'] : 0.0;
                    $t2 = isset($student->tests['Test 2']['total']) ? (float)$student->tests['Test 2']['total'] : 0.0;
                    $avg = isset($student->tests['average']) ? (float)$student->tests['average'] : round(($t1 + $t2) / 2, 2);
                    $status = $avg >= 6.0 ? 'Satisfactory' : 'Needs Practice';
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="font-mono font-bold">{{ $student->roll_no ?? '-' }}</td>
                    <td class="font-mono">{{ $student->sbte_reg_no ?? $student->reg_no }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                    <td class="font-mono font-bold">{{ number_format($t1, 1) }}</td>
                    <td class="font-mono font-bold">{{ number_format($t2, 1) }}</td>
                    <td class="font-mono font-bold" style="background-color: #f0fdfa; color: #0f766e; font-size: 11.5px;">{{ number_format($avg, 2) }}</td>
                    <td style="font-weight: 600; color: {{ $avg >= 6.0 ? '#047857' : '#b91c1c' }};">{{ $status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 20px; color: #777;">No students enrolled.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td style="border-top: 1px solid #333;">Name &amp; Signature of Lab Assessor</td>
            <td style="border-top: 1px solid #333;">Name &amp; Signature of Coordinator</td>
            <td style="border-top: 1px solid #333;">Head of Department</td>
        </tr>
    </table>

</body>
</html>
