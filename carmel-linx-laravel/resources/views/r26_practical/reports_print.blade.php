<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIA Consolidated Register - {{ $batchSubject->subject_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #555;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Continuous Internal Evaluation (CIA) Mark Register - Revision 2026</h2>
        <h3 style="margin: 5px 0 0 0; font-size:14px; text-transform:uppercase;">Practical / Laboratory Course</h3>
    </div>

    <div class="meta-info">
        <div>
            <strong>Subject:</strong> {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})<br>
            <strong>Class/Sem:</strong> Sem I - Diploma in Engineering
        </div>
        <div style="text-align: right;">
            <strong>Academic Year:</strong> 2026-2027<br>
            <strong>Faculty:</strong> Lecturer In Charge
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">Roll No</th>
                <th style="width: 110px;">Register No</th>
                <th class="text-left">Student Name</th>
                <th style="width: 70px;">Batch</th>
                <th style="width: 80px;">Lab Work (30M)</th>
                <th style="width: 80px;">Series Test (15M)</th>
                <th style="width: 80px;">Open Ended (10M)</th>
                <th style="width: 80px;">Attendance (5M)</th>
                <th style="width: 80px; font-weight: bold;">CIA Total (60M)</th>
                <th style="width: 80px; font-weight: bold;">ESE Mark (40M)</th>
                <th style="width: 80px; font-weight: bold;">Grand Total (100M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            @php
                $score = $consolidatedScores[$student->reg_no] ?? [];
                $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
            @endphp
            <tr>

                <td>{{ $student->roll_no ?? ($index + 1) }}</td>
                <td style="font-family: monospace;">{{ $student->reg_no }}</td>
                <td class="text-left bold">{{ $student->name }}</td>
                <td>{{ $batchVal }}</td>
                <td>{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                <td>{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                <td>{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                <td>{{ $attendanceMarks[$student->reg_no]['mark'] ?? 0 }}</td>
                <td class="bold">{{ $score['total_cia_60'] ?? '0.00' }}</td>
                <td>{{ $score['ese_score_40'] ?? '0.00' }}</td>
                <td class="bold" style="background-color: #fcfcfc;">{{ $score['grand_total_100'] ?? '0.00' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-line">
            Faculty In Charge
        </div>
        <div class="signature-line">
            Head of Department
        </div>
        <div class="signature-line">
            Principal
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
