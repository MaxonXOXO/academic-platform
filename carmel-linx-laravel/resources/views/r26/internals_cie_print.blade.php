<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Internal Assessment Marks (CIA) - {{ $batchSubject->subject_name }}</title>
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
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            background-color: #f8fafc;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px 0;
        }

        .a4-page {
            width: 210mm;
            max-width: 95%;
            margin: 0 auto;
            background: #ffffff;
            padding: 15mm 15mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
            z-index: 50;
            display: flex;
            align-items: center;
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
            font-size: 12px;
            font-weight: bold;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 4px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-meta td {
            padding: 5px 8px;
            font-size: 12px;
            border: 1px solid #000;
        }
        
        .header-meta td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 18%;
        }
        
        .header-meta td.value {
            font-weight: normal;
            width: 32%;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 15px 0 10px 0;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
            vertical-align: middle;
        }

        .marks-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f1f5f9;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer-signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            padding: 0 10px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
            html, body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            .a4-page {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .print-controls {
                display: none !important;
            }
            .header-meta td.label, .marks-table th {
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Marksheet</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering</h1>
            <h2>Department of {{ getFullBranchName($classroom->branch ?? '') }}</h2>
            <h3>Revision 2026 Scheme - Consolidated Internal Marks Report</h3>
        </div>

        <table class="header-meta">
            <tr>
                <td class="label">Course Title:</td>
                <td class="value">{{ $batchSubject->subject_name }}</td>
                <td class="label">Course Code:</td>
                <td class="value">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
                <td class="label">Semester:</td>
                <td class="value">Semester {{ $classroom->current_semester ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Academic Year:</td>
                <td class="value">2026 Revision</td>
                <td class="label">Report Date:</td>
                <td class="value font-bold">{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <div class="report-title">
            CONSOLIDATED INTERNAL ASSESSMENT MARKSHEET (CIA)
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Roll No</th>
                    <th style="width: 14%;">Register No</th>
                    <th>Student Name</th>
                    <th class="text-center" style="width: 12%;">Attendance %</th>
                    <th class="text-center" style="width: 12%;">Attendance<br>(5M Max)</th>
                    <th class="text-center" style="width: 15%;">Assignment /<br>Self-Learning (15M Max)</th>
                    <th class="text-center" style="width: 15%;">Series Exam<br>(20M Max)</th>
                    <th class="text-center font-bold" style="width: 12%;">CIA Marks<br>Awarded (40M Max)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentCiaData as $sc)
                    <tr>
                        <td class="text-center font-mono font-bold">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="font-mono text-center">{{ $sc['reg_no'] }}</td>
                        <td class="font-bold">{{ $sc['name'] }}</td>
                        <td class="text-center font-mono">{{ $sc['attendance_percent'] }}%</td>
                        <td class="text-center font-mono">{{ number_format($sc['attendance_marks'], 1) }}</td>
                        <td class="text-center font-mono">{{ number_format($sc['self_learning_marks'], 1) }}</td>
                        <td class="text-center font-mono">{{ number_format($sc['series_exam_marks'], 1) }}</td>
                        <td class="text-center font-mono font-bold" style="font-size: 12px; background-color: #f8fafc;">{{ number_format($sc['total_cia'], 1) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center italic">No student internal marks records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-signatures">
            <div>Faculty Signature</div>
            <div>Head of Department Signature</div>
            <div>Principal Signature</div>
        </div>

    </div>

</body>
</html>
