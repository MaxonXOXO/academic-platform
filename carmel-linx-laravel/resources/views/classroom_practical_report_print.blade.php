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
        <h3>CONTINUOUS INTERNAL ASSESSMENT (CIA 75 MARKS) EVALUATION REGISTER (REVISION 2021)</h3>
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
                <th style="width: 9%">PRN (SBTE)</th>
                <th style="width: 14%">Name</th>
                <th style="width: 4.5%">Rough<br>(5)</th>
                <th style="width: 4.5%">Fair<br>(7.5)</th>
                <th style="width: 4.5%">Obs/Rec<br>(7.5)</th>
                <th style="width: 4.5%">Proc/Punct<br>(7.5)</th>
                <th style="width: 4.5%">Viva<br>(10)</th>
                <th style="width: 6.5%; background-color: #f0f7ff;">Lab Work<br>(37.5)</th>
                <th style="width: 5%">Open End<br>(7.5)</th>
                <th style="width: 5%">Attend.<br>(15)</th>
                <th style="width: 4.5%">Test 1<br>(15)</th>
                <th style="width: 4.5%">Test 2<br>(15)</th>
                <th style="width: 5%">Test Avg<br>(15)</th>
                <th style="width: 7%; background-color: #e6fffa; font-weight: bold;">Final CIA<br>(75)</th>
                <th style="width: 6.5%; background-color: #e6f0ff; font-weight: bold;">ESE Grade<br>(50)</th>
                <th style="width: 7.5%; background-color: #f7e6ff; font-weight: bold;">Final Result<br>(125 / Grade)</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Grade conversion helper for SBTE R2021
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
            @endphp
            @foreach($students as $student)
                @php
                    $boardVal = $student->board_exam_marks !== null ? $student->board_exam_marks : null;
                    $boardNumeric = $gradeToNumeric($boardVal, 50);
                    $eseDisplay = '-';
                    $finalResultDisplay = '-';

                    if ($boardVal !== null && $boardVal !== '') {
                        if (is_numeric($boardVal)) {
                            $gradeLetter = $calcGrade($boardVal, 50);
                            $eseDisplay = number_format($boardVal, 1) . " ({$gradeLetter})";
                            $totalScore = $student->total_internal + (float)$boardVal;
                            $finalGrade = $calcGrade($totalScore, 125);
                            $finalResultDisplay = number_format($totalScore, 1) . " ({$finalGrade})";
                        } else {
                            $gradeLetter = strtoupper(trim($boardVal));
                            $eseDisplay = "Grade {$gradeLetter}";
                            if ($boardNumeric !== null) {
                                $totalScore = $student->total_internal + $boardNumeric;
                                $finalGrade = $calcGrade($totalScore, 125);
                                $finalResultDisplay = number_format($totalScore, 1) . " ({$finalGrade})";
                            } else {
                                $finalResultDisplay = "Grade {$gradeLetter}";
                            }
                        }
                    }

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
                    <td>{{ number_format($student->avg_viva_voce ?? 0, 2) }}</td>
                    <td style="font-weight: bold; background-color: #f8fbff;">{{ number_format($student->avg_lab_work, 2) }}</td>
                    <td>{{ number_format($student->micro_project ?? $student->open_ended ?? 0, 1) }}</td>
                    <td>{{ number_format($student->attendance_marks, 1) }}</td>
                    <td>{{ $t1Val }}</td>
                    <td>{{ $t2Val }}</td>
                    <td>{{ number_format($student->tests['average'] ?? 0, 2) }}</td>
                    <td style="font-weight: bold; background-color: #fafdfc; color: #0f766e;">{{ number_format($student->total_internal, 2) }}</td>
                    <td style="font-weight: bold; background-color: #fafdff; color: #1d4ed8;">{{ $eseDisplay }}</td>
                    <td style="font-weight: bold; background-color: #fffafd; color: #6b21a8;">{{ $finalResultDisplay }}</td>
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
