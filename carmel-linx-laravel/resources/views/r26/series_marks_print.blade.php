<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theory Series Examinations Report - {{ $batchSubject->subject_name }}</title>
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
            margin: 12mm 12mm 12mm 12mm;
        }

        body {
            background-color: #f8fafc;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px 0;
        }

        .a4-page {
            width: 297mm;
            max-width: 96%;
            margin: 0 auto;
            background: #ffffff;
            padding: 12mm 12mm;
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
            margin-bottom: 15px;
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

        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 12px 0 10px 0;
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
            font-family: monospace, Courier, monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .stats-card {
            border: 1px solid #000;
            padding: 10px 14px;
            margin-top: 15px;
            background-color: #fafafa;
        }

        .stats-card h4 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .stats-table td {
            padding: 4px 8px;
            border: none;
        }

        .footer-signatures {
            margin-top: 50px;
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
                size: A4 landscape;
                margin: 12mm 12mm 12mm 12mm;
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
            <h3>Revision 2026 Scheme - Theory Series Examinations Mark Register</h3>
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
            <tr>
                <td class="label">Series Tests Count:</td>
                <td class="value">{{ count($seriesExams) }} Series Tests (50 Marks Each)</td>
                <td class="label">CIA Weightage:</td>
                <td class="value font-bold">Scaled to 20.00 CIA Marks</td>
            </tr>
        </table>

        <div class="report-title">
            THEORY SERIES EXAMINATIONS MARK SHEET
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sl No</th>
                    <th style="width: 7%;">Roll No</th>
                    <th style="width: 14%;">SBTE Reg No</th>
                    <th>Student Name</th>
                    @foreach($seriesExams as $index => $exam)
                        <th class="text-center" style="width: 13%;">
                            {{ $exam->exam_name }}<br>
                            <span style="font-size: 9px; font-weight: normal;">
                                @if(!empty($exam->co_tags))
                                    ({{ is_array($exam->co_tags) ? implode('+', $exam->co_tags) : $exam->co_tags }}) - 
                                @endif
                                {{ $exam->max_marks }}M
                            </span>
                        </th>
                    @endforeach
                    <th class="text-center font-bold" style="width: 12%;">Average<br><span style="font-size: 9px; font-weight: normal;">(Out of 50M)</span></th>
                    <th class="text-center font-bold" style="width: 14%;">Converted CIA<br><span style="font-size: 9px; font-weight: normal;">Scaled to 20M</span></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $slNo = 1;
                    $totalStudents = count($studentCiaData);
                    $sumAvgScore = 0.0;
                    $sumCiaScore = 0.0;
                    $highestCia = 0.0;
                    $lowestCia = 999.0;
                    $passedCount = 0;
                @endphp
                @forelse($studentCiaData as $sc)
                    @php
                        $avgScore = 0.0;
                        if (count($seriesExams) > 0) {
                            $examSum = 0.0;
                            foreach ($seriesExams as $ex) {
                                $examSum += (float)($sc['exam_marks'][$ex->id] ?? 0.0);
                            }
                            $avgScore = $examSum / count($seriesExams);
                        }
                        $ciaScore = (float)($sc['series_exam_marks'] ?? 0.0);

                        $sumAvgScore += $avgScore;
                        $sumCiaScore += $ciaScore;
                        if ($ciaScore > $highestCia) { $highestCia = $ciaScore; }
                        if ($ciaScore < $lowestCia && $totalStudents > 0) { $lowestCia = $ciaScore; }
                        if ($ciaScore >= 8.0) { $passedCount++; } // ≥40% of 20M
                    @endphp
                    <tr>
                        <td class="text-center">{{ $slNo++ }}</td>
                        <td class="text-center font-mono font-bold">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="font-mono text-center font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                        <td class="font-bold">{{ $sc['name'] }}</td>
                        @foreach($seriesExams as $exam)
                            <td class="text-center font-mono">
                                {{ isset($sc['exam_marks'][$exam->id]) ? number_format($sc['exam_marks'][$exam->id], 1) : '-' }}
                            </td>
                        @endforeach
                        <td class="text-center font-mono font-bold">{{ number_format($avgScore, 1) }}</td>
                        <td class="text-center font-mono font-bold">{{ number_format($ciaScore, 2) }} / 20.00</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 6 + count($seriesExams) }}" class="text-center italic" style="padding: 15px;">No student marks records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            if ($totalStudents === 0) { $lowestCia = 0.0; }
            $classAvgCia = $totalStudents > 0 ? ($sumCiaScore / $totalStudents) : 0.0;
            $classAvgRaw = $totalStudents > 0 ? ($sumAvgScore / $totalStudents) : 0.0;
            $passPercentage = $totalStudents > 0 ? (($passedCount / $totalStudents) * 100) : 0.0;
        @endphp

        <div class="stats-card">
            <h4>Class Performance Summary</h4>
            <table class="stats-table">
                <tr>
                    <td style="width: 25%;"><strong>Total Students Evaluated:</strong> {{ $totalStudents }}</td>
                    <td style="width: 25%;"><strong>Class Avg (Raw 50M):</strong> {{ number_format($classAvgRaw, 1) }}</td>
                    <td style="width: 25%;"><strong>Class Avg (CIA 20M):</strong> {{ number_format($classAvgCia, 2) }} / 20.00</td>
                    <td style="width: 25%;"><strong>Highest CIA Mark:</strong> {{ number_format($highestCia, 2) }} / 20.00</td>
                </tr>
                <tr>
                    <td><strong>Lowest CIA Mark:</strong> {{ number_format($lowestCia, 2) }} / 20.00</td>
                    <td><strong>Qualifying Students (≥40%):</strong> {{ $passedCount }} / {{ $totalStudents }}</td>
                    <td colspan="2"><strong>Overall Qualifying Pass Rate:</strong> {{ number_format($passPercentage, 1) }}%</td>
                </tr>
            </table>
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
