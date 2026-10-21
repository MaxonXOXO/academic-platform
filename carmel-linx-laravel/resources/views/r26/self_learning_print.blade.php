<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Self-Learning Assessment Report - {{ $batchSubject->subject_code }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 landscape;
            margin: 10mm 10mm;
        }

        body {
            background-color: #f8fafc;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
        }

        .a4-page {
            width: 277mm;
            min-height: 190mm;
            padding: 10mm;
            margin: 10px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #0f172a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 13px;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 6px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .divider-double {
            border-top: 3px double #000;
            margin-bottom: 10px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .meta-table td {
            padding: 4px;
            border: none;
            font-size: 12px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }

        .content-table th {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            font-size: 10px;
        }

        .text-left {
            text-align: left !important;
        }

        .footer-sig {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            font-size: 12px;
        }

        .sig-block {
            text-align: center;
            width: 200px;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }

        @media print {
            body {
                background-color: transparent;
            }
            .print-controls {
                display: none;
            }
            .a4-page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" style="background:#64748b;" onclick="window.close()">Close Window</button>
    </div>

    <div class="a4-page">
        @php
            $deptDisplay = $departmentName ?? (function_exists('getFullBranchName') ? getFullBranchName($classroom->branch ?? '') : ($classroom->branch ?? ''));
            if (empty($deptDisplay) || strtoupper($deptDisplay) === 'ENGINEERING') {
                $deptDisplay = 'Automobile Engineering';
            }
        @endphp
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>Department of {{ $deptDisplay }}</h2>
            <h3 style="font-size: 13px; font-weight: normal; margin-top: 2px;">Continuous Internal Assessment (CIE) Evaluation Report - Self-Learning Activities (CO-wise)</h3>
        </div>

        <div class="divider-double"></div>

        <table class="meta-table">
            <tr>
                <td style="width: 15%;"><strong>Program:</strong></td>
                <td style="width: 35%;">Diploma in {{ $deptDisplay }}</td>
                <td style="width: 15%;"><strong>Academic Year:</strong></td>
                <td style="width: 35%;">2026-2027</td>
            </tr>
            <tr>
                <td><strong>Course Title:</strong></td>
                <td>{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</td>
                <td><strong>Semester/Batch:</strong></td>
                <td>Semester {{ $batchSubject->semester }} / {{ $classroom->classroom_name ?? ($classroom->name ?? $batchSubject->classroom_id) }}</td>
            </tr>
        </table>

        <table class="content-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Roll No</th>
                    <th style="width: 14%;">Reg No</th>
                    <th style="width: 28%;" class="text-left">Student Name</th>
                    <th style="width: 13%;">M1 / CA1 (15M)</th>
                    <th style="width: 13%;">M2 / CA2 (15M)</th>
                    <th style="width: 13%;">M3 / CA3 (15M)</th>
                    <th style="width: 13%;">M4 / CA4 (15M)</th>
                    <th style="width: 14%;" class="font-bold">SL Total (15M)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentCiaData as $sc)
                    <tr>
                        <td>{{ $sc['roll_no'] ?: '—' }}</td>
                        <td style="font-family: monospace;">{{ $sc['sbte_reg_no'] ?? $sc['reg_no'] }}</td>
                        <td class="text-left">{{ $sc['name'] }}</td>
                        <td>{{ $sc['ca1_15'] !== null ? number_format($sc['ca1_15'], 1) : '-' }}</td>
                        <td>{{ $sc['ca2_15'] !== null ? number_format($sc['ca2_15'], 1) : '-' }}</td>
                        <td>{{ $sc['ca3_15'] !== null ? number_format($sc['ca3_15'], 1) : '-' }}</td>
                        <td>{{ $sc['ca4_15'] !== null ? number_format($sc['ca4_15'], 1) : '-' }}</td>
                        <td style="font-weight: bold; background-color: #f8fafc;">{{ number_format($sc['self_learning_15'] ?? $sc['self_learning_marks'] ?? 0, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-sig">
            <div class="sig-block">
                <div class="sig-line">Faculty Member Signature</div>
            </div>
            <div class="sig-block">
                <div class="sig-line">Head of Department (HOD)</div>
            </div>
        </div>
    </div>

</body>
</html>
