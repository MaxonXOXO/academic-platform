<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Theory ESE & Final Results - {{ $batchSubject->subject_name }}</title>
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
            background: #ffffff;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            border: 1px solid #cbd5e1;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-print {
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background: #334155;
        }

        .btn-close {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-close:hover {
            background: #b91c1c;
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
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .header h3 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
            text-decoration: underline;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11px;
        }

        .header-meta td {
            padding: 5px 8px;
            border: 1px solid #000;
        }
        
        .header-meta td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 16%;
        }
        
        .header-meta td.value {
            font-weight: normal;
            width: 34%;
        }

        .grading-scale-card {
            border: 1px solid #000;
            padding: 6px 10px;
            margin-bottom: 12px;
            background-color: #fafafa;
        }

        .grading-scale-card h4 {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        .grading-scale-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 10px;
        }

        .grading-scale-table th, .grading-scale-table td {
            border: 1px solid #999;
            padding: 3px 5px;
        }

        .grading-scale-table th {
            background-color: #f1f5f9;
            font-weight: bold;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 10px 0;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 6px 6px;
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
            font-family: monospace, Courier, monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .stats-grid {
            margin-top: 15px;
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 15px;
            page-break-inside: avoid;
        }

        .stats-card {
            border: 1px solid #000;
            padding: 10px;
            background-color: #fafafa;
        }

        .stats-card h4 {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table td {
            padding: 4px 6px;
            font-size: 11px;
        }

        .footer-signatures {
            margin-top: 45px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            padding: 0 15px;
            page-break-inside: avoid;
        }

        .footer-signatures div {
            text-align: center;
            width: 28%;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 4px;
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
            .header-meta td.label, .marks-table th, .grading-scale-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">🖨️ Print Report</button>
        <button class="btn-close" onclick="window.close()">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering & Technology</h1>
            <h2>Department of {{ strtoupper(getFullBranchName($classroom->branch ?? $classroom->department ?? '')) }}</h2>
            <h3>Revision 2026 Scheme - Theory ESE & Consolidated Final Results</h3>
        </div>

        <table class="header-meta">
            <tr>
                <td class="label">Course Title:</td>
                <td class="value font-bold">{{ $batchSubject->subject_name }}</td>
                <td class="label">Course Code:</td>
                <td class="value font-mono font-bold">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
                <td class="label">Semester / Scheme:</td>
                <td class="value">Semester {{ $classroom->current_semester ?? 'N/A' }} (Rev 2026)</td>
            </tr>
            <tr>
                <td class="label">Faculty In-Charge:</td>
                <td class="value">{{ Session::get('userName') ?? 'Faculty In-Charge' }}</td>
                <td class="label">Date of Report:</td>
                <td class="value font-bold">{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <!-- Official Revision 2026 Grade Scale Legend -->
        <div class="grading-scale-card">
            <h4>Official Revision 2026 Grading System Standard</h4>
            <table class="grading-scale-table">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>S</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-bold">Percentage</td>
                        <td>≥ 90%</td>
                        <td>80 – 89%</td>
                        <td>70 – 79%</td>
                        <td>60 – 69%</td>
                        <td>50 – 59%</td>
                        <td>40 – 49%</td>
                        <td>&lt; 40%</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Performance</td>
                        <td>Outstanding</td>
                        <td>Excellent</td>
                        <td>Very Good</td>
                        <td>Good</td>
                        <td>Average</td>
                        <td>Satisfactory</td>
                        <td>Reappearance</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Grade Points</td>
                        <td>10</td>
                        <td>9</td>
                        <td>8</td>
                        <td>7</td>
                        <td>6</td>
                        <td>5</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="report-title">
            CONSOLIDATED STUDENT RESULTS SHEET (CIA 40M + ESE 60M)
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sl No</th>
                    <th style="width: 6%;">Roll</th>
                    <th style="width: 14%;">SBTE Reg No</th>
                    <th>Student Name</th>
                    <th class="text-center" style="width: 11%;">Attendance %</th>
                    <th class="text-center" style="width: 10%;">CIA<br>(40M Max)</th>
                    <th class="text-center" style="width: 10%;">Theory ESE<br>(60M Max)</th>
                    <th class="text-center" style="width: 10%;">Grand Total<br>(100M Max)</th>
                    <th class="text-center" style="width: 9%;">Grade</th>
                    <th class="text-center" style="width: 13%;">Result Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $slNo = 1;
                    $passCount = 0;
                    $failCount = 0;
                    $grades = ['S' => 0, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0];
                @endphp
                @forelse($studentCiaData as $sc)
                    @php
                        $tot = $sc['total_cia'] + $sc['ese_marks'];
                        
                        // Strict Revision 2026 Grade Scale Enforcement: S, A, B, C, D, E, F
                        if ($tot >= 90) { $grade = 'S'; }
                        elseif ($tot >= 80) { $grade = 'A'; }
                        elseif ($tot >= 70) { $grade = 'B'; }
                        elseif ($tot >= 60) { $grade = 'C'; }
                        elseif ($tot >= 50) { $grade = 'D'; }
                        elseif ($tot >= 40) { $grade = 'E'; }
                        else { $grade = 'F'; }

                        // ESE Passing threshold: ≥24/60 in ESE and Total ≥ 40/100
                        if ($tot >= 40 && $sc['ese_marks'] >= 24) {
                            $remark = 'PASSED';
                            $passCount++;
                        } else {
                            $remark = 'REAPPEARANCE';
                            $failCount++;
                            $grade = 'F';
                        }
                        $grades[$grade]++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $slNo++ }}</td>
                        <td class="text-center font-mono font-bold">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="font-mono text-center">{{ $sc['sbte_reg_no'] ?: ($sc['reg_no'] ?? 'Unassigned') }}</td>
                        <td class="font-bold">{{ $sc['name'] }}</td>
                        <td class="text-center font-mono">{{ $sc['attendance_percent'] }}%</td>
                        <td class="text-center font-mono">{{ number_format($sc['total_cia'], 1) }}</td>
                        <td class="text-center font-mono">{{ number_format($sc['ese_marks'], 1) }}</td>
                        <td class="text-center font-mono font-bold">{{ number_format($tot, 1) }}</td>
                        <td class="text-center font-bold">{{ $grade }}</td>
                        <td class="text-center font-bold {{ $remark === 'PASSED' ? 'text-emerald-700' : 'text-rose-700' }}" style="color: {{ $remark === 'PASSED' ? '#047857' : '#b91c1c' }};">
                            {{ $remark }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center italic" style="padding: 15px;">No student result records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $totalStudents = $passCount + $failCount;
            $passRate = $totalStudents > 0 ? ($passCount / $totalStudents) * 100 : 0.0;
        @endphp

        <div class="stats-grid">
            <div class="stats-card">
                <h4>Overall Performance Summary</h4>
                <table class="stats-table">
                    <tr>
                        <td class="font-bold">Total Students Evaluated:</td>
                        <td class="text-center font-mono font-bold">{{ $totalStudents }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold" style="color: #047857;">Passed:</td>
                        <td class="text-center font-mono font-bold" style="color: #047857;">{{ $passCount }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold" style="color: #b91c1c;">Reappearance Required:</td>
                        <td class="text-center font-mono font-bold" style="color: #b91c1c;">{{ $failCount }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td class="font-bold">Class Pass Percentage:</td>
                        <td class="text-center font-mono font-bold" style="font-size: 13px;">{{ number_format($passRate, 1) }}%</td>
                    </tr>
                </table>
            </div>

            <div class="stats-card">
                <h4>Grade Distribution (Revision 2026)</h4>
                <table class="stats-table" style="text-align: center;">
                    <thead>
                        <tr style="border-bottom: 1px solid #000; background-color: #f1f5f9;">
                            <th class="font-bold" style="padding: 3px;">S</th>
                            <th class="font-bold" style="padding: 3px;">A</th>
                            <th class="font-bold" style="padding: 3px;">B</th>
                            <th class="font-bold" style="padding: 3px;">C</th>
                            <th class="font-bold" style="padding: 3px;">D</th>
                            <th class="font-bold" style="padding: 3px;">E</th>
                            <th class="font-bold" style="padding: 3px; color: #b91c1c;">F</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['S'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['A'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['B'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['C'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['D'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px;">{{ $grades['E'] }}</td>
                            <td class="font-mono font-bold" style="padding: 6px; color: #b91c1c;">{{ $grades['F'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-signatures">
            <div>Signature of Faculty In-Charge</div>
            <div>Signature of Head of Department</div>
            <div>Signature of Principal</div>
        </div>

        <div class="footer-note">
            Generated via Academic Management Portal • Carmel College of Engineering & Technology, Alappuzha
        </div>

    </div>

</body>
</html>
