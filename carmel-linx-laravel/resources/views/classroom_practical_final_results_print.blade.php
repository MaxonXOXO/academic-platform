<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Practical ESE & Final Results - {{ $subject->subject_name }}</title>
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
            margin-bottom: 12px;
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
            margin-bottom: 12px;
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
        .grading-scale-card {
            border: 1px solid #000;
            padding: 6px 10px;
            margin-bottom: 12px;
            background-color: #fafafa;
        }
        .grading-scale-card h4 {
            font-size: 10px;
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
            font-size: 9.5px;
        }
        .grading-scale-table th, .grading-scale-table td {
            border: 1px solid #999;
            padding: 2px 4px;
        }
        .grading-scale-table th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            text-align: center;
            font-size: 10px;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 9.5px;
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
        .stats-grid {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 12px;
            page-break-inside: avoid;
        }
        .stats-card {
            border: 1px solid #000;
            padding: 8px 10px;
            background-color: #fafafa;
        }
        .stats-card h4 {
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .stats-table td {
            padding: 3px 4px;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 35px;
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
            .report-table th, .grading-scale-table th {
                background-color: #f1f5f9 !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="print-btn" onclick="window.print()">🖨️ Print Final Results</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>PRACTICAL END-SEMESTER EXAMINATION &amp; CONSOLIDATED FINAL RESULTS (REVISION 2021)</h3>
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
            <td class="label">Semester / Scheme:</td>
            <td class="value">Semester {{ $subject->semester }} (Revision 2021)</td>
        </tr>
        <tr>
            <td class="label">Max Mark Structure:</td>
            <td class="value"><strong>CIA: 75M + ESE: 50M = Total 125 Marks</strong></td>
            <td class="label">Date of Report:</td>
            <td class="value font-mono">{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <!-- Official Revision 2021 SBTE Grade Scale Standard -->
    <div class="grading-scale-card">
        <h4>Official SBTE Revision 2021 Grading Scale Standard</h4>
        <table class="grading-scale-table">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>S</th>
                    <th>A+</th>
                    <th>A</th>
                    <th>B+</th>
                    <th>B</th>
                    <th>C+</th>
                    <th>C</th>
                    <th>D+</th>
                    <th>D</th>
                    <th>P</th>
                    <th>F</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold;">Range</td>
                    <td>≥ 90%</td>
                    <td>85 – 89%</td>
                    <td>80 – 84%</td>
                    <td>75 – 79%</td>
                    <td>70 – 74%</td>
                    <td>65 – 69%</td>
                    <td>60 – 64%</td>
                    <td>55 – 59%</td>
                    <td>50 – 54%</td>
                    <td>40 – 49%</td>
                    <td>&lt; 40%</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Points</td>
                    <td>10</td>
                    <td>9</td>
                    <td>8.5</td>
                    <td>8</td>
                    <td>7.5</td>
                    <td>7</td>
                    <td>6.5</td>
                    <td>6</td>
                    <td>5.5</td>
                    <td>4</td>
                    <td>0</td>
                </tr>
            </tbody>
        </table>
    </div>

    @php
        $calcGrade = function($marks, $maxMarks) {
            if ($marks === null || $marks === '' || $marks === '-') return '-';
            if (!is_numeric($marks)) return strtoupper(trim($marks));
            $pct = ($marks / $maxMarks) * 100;
            if ($pct >= 90) return 'S';
            if ($pct >= 85) return 'A+';
            if ($pct >= 80) return 'A';
            if ($pct >= 75) return 'B+';
            if ($pct >= 70) return 'B';
            if ($pct >= 65) return 'C+';
            if ($pct >= 60) return 'C';
            if ($pct >= 55) return 'D+';
            if ($pct >= 50) return 'D';
            if ($pct >= 40) return 'P';
            return 'F';
        };

        $gradeToNumeric = function($grade, $maxMarks) {
            if ($grade === null || $grade === '' || $grade === '-') return null;
            if (is_numeric($grade)) return (float)$grade;
            $g = strtoupper(trim($grade));
            switch($g) {
                case 'S': return $maxMarks * 0.95;
                case 'A+': return $maxMarks * 0.875;
                case 'A': return $maxMarks * 0.825;
                case 'B+': return $maxMarks * 0.775;
                case 'B': return $maxMarks * 0.725;
                case 'C+': return $maxMarks * 0.675;
                case 'C': return $maxMarks * 0.625;
                case 'D+': return $maxMarks * 0.575;
                case 'D': return $maxMarks * 0.525;
                case 'P': return $maxMarks * 0.45;
                case 'F': return 0.0;
                default: return null;
            }
        };

        $passCount = 0;
        $failCount = 0;
        $gradesDist = ['S' => 0, 'A+' => 0, 'A' => 0, 'B+' => 0, 'B' => 0, 'C+' => 0, 'C' => 0, 'D+' => 0, 'D' => 0, 'P' => 0, 'F' => 0];
    @endphp

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%">Sl No</th>
                <th style="width: 5%">Roll</th>
                <th style="width: 14%">PRN (SBTE)</th>
                <th>Student Name</th>
                <th style="width: 9%">Attendance %</th>
                <th style="width: 10%; background-color: #e6fffa;">Final CIA<br>(75 Marks)</th>
                <th style="width: 11%; background-color: #e6f0ff;">Practical ESE<br>(50 Marks / Grade)</th>
                <th style="width: 11%; background-color: #f7e6ff;">Grand Total<br>(125 Marks)</th>
                <th style="width: 9%">Awarded Grade</th>
                <th style="width: 12%">Result Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $student)
                @php
                    $boardVal = $student->board_exam_marks !== null ? $student->board_exam_marks : null;
                    $boardNum = $gradeToNumeric($boardVal, 50);
                    $eseDisplay = '-';
                    $totalDisplay = '-';
                    $finalGrade = '-';
                    $resultStatus = '-';

                    if ($boardVal !== null && $boardVal !== '') {
                        if (is_numeric($boardVal)) {
                            $eseNum = (float)$boardVal;
                            $gLet = $calcGrade($eseNum, 50);
                            $eseDisplay = number_format($eseNum, 1) . " ({$gLet})";
                            $tot = $student->total_internal + $eseNum;
                            $totalDisplay = number_format($tot, 1);
                            $finalGrade = $calcGrade($tot, 125);
                            if ($tot >= 50 && $eseNum >= 20) {
                                $resultStatus = 'PASSED';
                                $passCount++;
                            } else {
                                $resultStatus = 'REAPPEARANCE';
                                $failCount++;
                                $finalGrade = 'F';
                            }
                        } else {
                            $gLet = strtoupper(trim($boardVal));
                            $eseDisplay = "Grade {$gLet}";
                            if ($boardNum !== null) {
                                $tot = $student->total_internal + $boardNum;
                                $totalDisplay = number_format($tot, 1);
                                $finalGrade = $calcGrade($tot, 125);
                                if ($tot >= 50 && $boardNum >= 20) {
                                    $resultStatus = 'PASSED';
                                    $passCount++;
                                } else {
                                    $resultStatus = 'REAPPEARANCE';
                                    $failCount++;
                                    $finalGrade = 'F';
                                }
                            } else {
                                $finalGrade = $gLet;
                                $resultStatus = ($gLet !== 'F' && $gLet !== 'ABS') ? 'PASSED' : 'REAPPEARANCE';
                                if ($resultStatus === 'PASSED') $passCount++; else $failCount++;
                            }
                        }
                        if (isset($gradesDist[$finalGrade])) {
                            $gradesDist[$finalGrade]++;
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="font-mono font-bold">{{ $student->roll_no ?? '-' }}</td>
                    <td class="font-mono">{{ $student->sbte_reg_no ?? $student->reg_no }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                    <td class="font-mono">{{ number_format($student->attendance_percentage ?? 100, 1) }}%</td>
                    <td class="font-mono font-bold" style="background-color: #f0fdfa; color: #0f766e;">{{ number_format($student->total_internal, 2) }}</td>
                    <td class="font-mono font-bold" style="background-color: #eff6ff; color: #1d4ed8;">{{ $eseDisplay }}</td>
                    <td class="font-mono font-bold" style="background-color: #faf5ff; color: #6b21a8; font-size: 11.5px;">{{ $totalDisplay }}</td>
                    <td style="font-weight: bold; font-size: 12px; color: {{ $finalGrade === 'F' ? '#b91c1c' : '#111' }};">{{ $finalGrade }}</td>
                    <td style="font-weight: bold; color: {{ $resultStatus === 'PASSED' ? '#047857' : ($resultStatus === 'REAPPEARANCE' ? '#b91c1c' : '#64748b') }};">
                        {{ $resultStatus }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="padding: 20px; color: #777;">No student evaluation records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $totalEvaluated = $passCount + $failCount;
        $passRate = $totalEvaluated > 0 ? ($passCount / $totalEvaluated) * 100 : 0.0;
    @endphp

    <div class="stats-grid">
        <div class="stats-card">
            <h4>Overall Performance Summary</h4>
            <table class="stats-table">
                <tr>
                    <td style="font-weight: bold;">Total Students Evaluated:</td>
                    <td class="font-mono font-bold" style="text-align: right;">{{ $totalEvaluated }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #047857;">Passed:</td>
                    <td class="font-mono font-bold" style="text-align: right; color: #047857;">{{ $passCount }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #b91c1c;">Reappearance Required:</td>
                    <td class="font-mono font-bold" style="text-align: right; color: #b91c1c;">{{ $failCount }}</td>
                </tr>
                <tr style="border-top: 1px solid #000;">
                    <td style="font-weight: bold;">Practical Pass Percentage:</td>
                    <td class="font-mono font-bold" style="text-align: right; font-size: 12px;">{{ number_format($passRate, 1) }}%</td>
                </tr>
            </table>
        </div>

        <div class="stats-card">
            <h4>SBTE Grade Distribution (Revision 2021)</h4>
            <table class="stats-table" style="text-align: center;">
                <thead>
                    <tr style="border-bottom: 1px solid #000; background-color: #f1f5f9; font-size: 9px;">
                        <th>S</th>
                        <th>A+</th>
                        <th>A</th>
                        <th>B+</th>
                        <th>B</th>
                        <th>C+</th>
                        <th>C</th>
                        <th>D+</th>
                        <th>D</th>
                        <th>P</th>
                        <th style="color: #b91c1c;">F</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="font-mono font-bold">
                        <td>{{ $gradesDist['S'] }}</td>
                        <td>{{ $gradesDist['A+'] }}</td>
                        <td>{{ $gradesDist['A'] }}</td>
                        <td>{{ $gradesDist['B+'] }}</td>
                        <td>{{ $gradesDist['B'] }}</td>
                        <td>{{ $gradesDist['C+'] }}</td>
                        <td>{{ $gradesDist['C'] }}</td>
                        <td>{{ $gradesDist['D+'] }}</td>
                        <td>{{ $gradesDist['D'] }}</td>
                        <td>{{ $gradesDist['P'] }}</td>
                        <td style="color: #b91c1c;">{{ $gradesDist['F'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <table class="footer-signatures">
        <tr>
            <td style="border-top: 1px solid #333;">Signature of Subject Faculty</td>
            <td style="border-top: 1px solid #333;">Signature of Lab Coordinator</td>
            <td style="border-top: 1px solid #333;">Signature of Head of Department</td>
        </tr>
    </table>

</body>
</html>
