@php
    $rType = $reportType ?? 'cia-register';
    $reportTitles = [
        'cia-register' => 'Continuous Internal Assessment (CIA) Register — Internal Marks Consolidated (40 Marks)',
        'consolidated-marks' => 'Consolidated Marks Register — CIA (40M) + ESE (60M) = Total (100 Marks)',
        'attendance' => 'Subject Attendance Percentage & Attendance Marks Register (Max 5 Marks)',
        'self-learning' => 'CA1: Self-Learning Activities Assessment Register (M1–M4 Out of 10 → Converted to 5 Marks)',
        'ce-marks' => 'CE: Continuous Evaluation Register (M1–M4 Out of 50 → Converted to 10 Marks)',
        'practical-test' => 'CA2 & CA3: Practical Test Marks Register (Test 1 & Test 2 Out of 40 → Average 10 Marks)',
        'series-test' => 'CA4 & CA5: Series Examination Marks Register (Series 1 & Series 2 Out of 50 → Average 10 Marks)',
        'attainment-report' => 'CO-PO Attainment & Indirect Survey Evaluation Report (Module-to-CO Matrix Model)',
    ];
    $reportSubTitle = $reportTitles[$rType] ?? $reportTitles['cia-register'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportSubTitle }} - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 10mm 8mm 10mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #0f172a;
            background: #f8fafc;
            font-size: 10px;
            line-height: 1.3;
        }
        .report-container {
            max-width: 1180px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px 24px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .no-print {
            text-align: center;
            padding: 10px;
            background: #1e293b;
            border-radius: 6px;
            margin-bottom: 14px;
            max-width: 1180px;
            margin-left: auto;
            margin-right: auto;
        }
        .no-print button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 7px 20px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
        }
        .no-print button.close-btn { background: #dc2626; }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header h2 {
            margin: 3px 0 0 0;
            font-size: 12.5px;
            color: #1e3a8a;
            font-weight: 800;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 3px 0 0 0;
            font-size: 11.5px;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header h4 {
            margin: 5px 0 0 0;
            font-size: 10.5px;
            color: #ffffff;
            background: #1e3a8a;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            font-size: 10px;
        }
        .meta-table td { padding: 3px 6px; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 9.5px;
        }
        .data-table th, .data-table td {
            border: 1px solid #475569;
            padding: 4px 5px;
            text-align: center;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            font-size: 9px;
        }
        .data-table td.text-left { text-align: left; }
        .signatures {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .sig-box {
            text-align: center;
            width: 210px;
            border-top: 1px dashed #475569;
            padding-top: 6px;
            font-size: 10px;
            font-weight: 600;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .report-container { border: none; box-shadow: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
    <script>
        function handlePrintBack() {
            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }
            window.location.href = "{{ url('/r26/classroom/english-1001/' . $batchSubject->id) }}";
        }
    </script>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Official Report</button>
        <button class="close-btn" onclick="handlePrintBack()">⬅ Back to Classroom</button>
    </div>

    <div class="report-container">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>{{ $departmentFullName ?? 'DEPARTMENT OF ENGINEERING' }}</h2>
            <h3>{{ $reportSubTitle }}</h3>
            <h4>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} — {{ $batchSubject->subject_name }} (Revision 2026)</h4>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 25%;"><strong>Department:</strong> {{ $departmentName ?? 'Engineering' }}</td>
                <td style="width: 25%;"><strong>Semester:</strong> Semester {{ $semester ?? 1 }}</td>
                <td style="width: 25%;"><strong>Assessment Year:</strong> {{ $assessmentYear ?? '2026 - 2027' }}</td>
                <td style="width: 25%;"><strong>Report Date:</strong> {{ $reportDate ?? date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 25%;"><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
                <td style="width: 25%;"><strong>Course Category:</strong> Basic Science &amp; Humanities Practicum</td>
                <td style="width: 25%;"><strong>CIA Max Marks:</strong> 40 Marks (5+5+10+10+10)</td>
                <td style="width: 25%;"><strong>ESE Lab Marks:</strong> 60 Marks (Total: 100M)</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Course Faculty:</strong> {{ $assignedStaff->pluck('name')->implode(', ') ?: 'Course Incharge' }}</td>
            </tr>
        </table>

        @if($rType === 'attendance')
            <!-- ATTENDANCE REPORT TABLE -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th style="width: 45px;">Roll No</th>
                        <th style="width: 110px;">Reg No / PRN</th>
                        <th>Student Name</th>
                        <th style="width: 110px;">Uploaded TEAMS %</th>
                        <th style="width: 110px;">Override %</th>
                        <th style="width: 110px;">Final Effective %</th>
                        <th style="width: 120px;">Attendance Marks (Max 5)</th>
                        <th style="width: 120px;">Eligibility Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php
                        $ev = $evaluations->get($student->reg_no);
                        $stOff = isset($subjectOfficialAttendance) ? ($subjectOfficialAttendance->get($student->reg_no) ?: (!empty($student->sbte_reg_no) ? $subjectOfficialAttendance->get($student->sbte_reg_no) : null)) : null;
                        $rawPct = $ev && $ev->attendance_pct !== null ? (float)$ev->attendance_pct : ($stOff ? ($stOff->teams_percentage ?? $stOff->final_percentage) : null);
                        $ovrPct = $ev && $ev->attendance_override_pct !== null ? (float)$ev->attendance_override_pct : ($stOff ? $stOff->override_percentage : null);
                        $effPct = $ovrPct !== null ? $ovrPct : $rawPct;
                        $attMark = $effPct !== null ? \App\Models\R26EnglishEvaluation::calculateAttendanceMarks($effPct) : ($ev ? $ev->attendance_mark_5 : 0.0);
                        $statusStr = $effPct === null ? 'Not Entered' : ($effPct >= 75 ? 'Eligible' : ($effPct >= 65 ? 'Condonation' : 'Shortage'));
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td>{{ $rawPct !== null ? number_format($rawPct, 1) . '%' : '-' }}</td>
                        <td>{{ $ovrPct !== null ? number_format($ovrPct, 1) . '%' : '-' }}</td>
                        <td style="font-weight: 700;">{{ $effPct !== null ? number_format($effPct, 1) . '%' : '-' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ number_format($attMark, 1) }}</td>
                        <td>{{ $statusStr }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($rType === 'self-learning')
            <!-- CA1 SELF LEARNING REPORT TABLE -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th style="width: 45px;">Roll No</th>
                        <th style="width: 110px;">Reg No / PRN</th>
                        <th>Student Name</th>
                        <th style="width: 90px;">Module 1 (Max 10)</th>
                        <th style="width: 90px;">Module 2 (Max 10)</th>
                        <th style="width: 90px;">Module 3 (Max 10)</th>
                        <th style="width: 90px;">Module 4 (Max 10)</th>
                        <th style="width: 100px;">Average (Out of 10)</th>
                        <th style="width: 120px;">CA1 Converted (Max 5)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php $ev = $evaluations->get($student->reg_no); @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td>{{ $ev && $ev->sl_m1_10 !== null ? number_format($ev->sl_m1_10, 1) : '-' }}</td>
                        <td>{{ $ev && $ev->sl_m2_10 !== null ? number_format($ev->sl_m2_10, 1) : '-' }}</td>
                        <td>{{ $ev && $ev->sl_m3_10 !== null ? number_format($ev->sl_m3_10, 1) : '-' }}</td>
                        <td>{{ $ev && $ev->sl_m4_10 !== null ? number_format($ev->sl_m4_10, 1) : '-' }}</td>
                        <td style="font-weight: 700;">{{ $ev ? number_format($ev->sl_avg_10, 2) : '0.00' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ $ev ? number_format($ev->sl_conv_5, 1) : '0.0' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($rType === 'ce-marks')
            <!-- CE CONTINUOUS EVALUATION REPORT TABLE (SINGLE ENTRY OUT OF 50 -> CONVERTED TO 10) -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th style="width: 45px;">Roll No</th>
                        <th style="width: 110px;">Reg No / PRN</th>
                        <th>Student Name</th>
                        <th style="width: 150px;">Continuous Evaluation (M1–M4)<br>(Max 50 Marks)</th>
                        <th style="width: 150px;">CE Converted<br>(Max 10 Marks)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php
                        $ev = $evaluations->get($student->reg_no);
                        $ceMark50 = $ev ? ($ev->ce_m1_50 !== null ? (float)$ev->ce_m1_50 : ($ev->ce_avg_50 > 0 ? (float)$ev->ce_avg_50 : null)) : null;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td style="font-weight: 700;">{{ $ceMark50 !== null ? number_format($ceMark50, 1) : '-' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ $ev ? number_format($ev->ce_conv_10, 1) : '0.0' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($rType === 'practical-test')
            <!-- CA2 & CA3 PRACTICAL TEST REPORT TABLE -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th style="width: 45px;">Roll No</th>
                        <th style="width: 110px;">Reg No / PRN</th>
                        <th>Student Name</th>
                        <th style="width: 110px;">CA2: Practical Test 1 (Max 40)</th>
                        <th style="width: 90px;">Test 1 Conv (Max 10)</th>
                        <th style="width: 110px;">CA3: Practical Test 2 (Max 40)</th>
                        <th style="width: 90px;">Test 2 Conv (Max 10)</th>
                        <th style="width: 100px;">Average (Out of 40)</th>
                        <th style="width: 120px;">Final Practical Mark (Max 10)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php $ev = $evaluations->get($student->reg_no); @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td>{{ $ev ? ($ev->prac_test1_absent ? 'AB' : ($ev->prac_test1_40 !== null ? number_format($ev->prac_test1_40, 1) : '-')) : '-' }}</td>
                        <td>{{ $ev ? ($ev->prac_test1_absent ? 'AB' : number_format($ev->prac_test1_10, 2)) : '0.00' }}</td>
                        <td>{{ $ev ? ($ev->prac_test2_absent ? 'AB' : ($ev->prac_test2_40 !== null ? number_format($ev->prac_test2_40, 1) : '-')) : '-' }}</td>
                        <td>{{ $ev ? ($ev->prac_test2_absent ? 'AB' : number_format($ev->prac_test2_10, 2)) : '0.00' }}</td>
                        <td style="font-weight: 700;">{{ $ev ? number_format($ev->prac_avg_40, 2) : '0.00' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ $ev ? number_format($ev->prac_conv_10, 1) : '0.0' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($rType === 'series-test')
            <!-- CA4 & CA5 SERIES EXAM REPORT TABLE -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th style="width: 45px;">Roll No</th>
                        <th style="width: 110px;">Reg No / PRN</th>
                        <th>Student Name</th>
                        <th style="width: 110px;">CA4: Series Exam 1 (Max 50)</th>
                        <th style="width: 90px;">Series 1 Conv (Max 10)</th>
                        <th style="width: 110px;">CA5: Series Exam 2 (Max 50)</th>
                        <th style="width: 90px;">Series 2 Conv (Max 10)</th>
                        <th style="width: 100px;">Average (Out of 50)</th>
                        <th style="width: 120px;">Final Series Mark (Max 10)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php $ev = $evaluations->get($student->reg_no); @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td>{{ $ev ? ($ev->series1_absent ? 'AB' : ($ev->series1_50 !== null ? number_format($ev->series1_50, 1) : '-')) : '-' }}</td>
                        <td>{{ $ev ? ($ev->series1_absent ? 'AB' : number_format($ev->series1_10, 2)) : '0.00' }}</td>
                        <td>{{ $ev ? ($ev->series2_absent ? 'AB' : ($ev->series2_50 !== null ? number_format($ev->series2_50, 1) : '-')) : '-' }}</td>
                        <td>{{ $ev ? ($ev->series2_absent ? 'AB' : number_format($ev->series2_10, 2)) : '0.00' }}</td>
                        <td style="font-weight: 700;">{{ $ev ? number_format($ev->series_avg_50, 2) : '0.00' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ $ev ? number_format($ev->series_conv_10, 1) : '0.0' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        @elseif($rType === 'attainment-report')
            <!-- 1. MODULE-TO-CO ATTAINMENT MATRIX (ENGLISH 1001 SPECIAL MODEL: EACH MODULE COVERS ALL 4 COs) -->
            <h4 style="margin: 12px 0 6px 0; font-size: 11px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                1. Module-Wise Direct Attainment &amp; Equal Assignment to CO1–CO4 (CIE Threshold: {{ $attainmentData['cie_threshold_percent'] ?? 50 }}%)
            </h4>
            <table class="data-table" style="margin-bottom: 14px;">
                <thead>
                    <tr>
                        <th style="width: 110px;">Module</th>
                        <th style="width: 120px;">Students Assessed</th>
                        <th style="width: 120px;">Students Met Target</th>
                        <th style="width: 100px;">Attained %</th>
                        <th style="width: 110px;">Module Level (0–3)</th>
                        <th>CO1 Level</th>
                        <th>CO2 Level</th>
                        <th>CO3 Level</th>
                        <th>CO4 Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['M1', 'M2', 'M3', 'M4'] as $mKey)
                    @php $ms = $attainmentData['module_stats'][$mKey] ?? []; @endphp
                    <tr>
                        <td style="font-weight: 700;">{{ $ms['module'] ?? $mKey }}</td>
                        <td>{{ $ms['assessed'] ?? 0 }} / {{ $ms['total'] ?? count($students) }}</td>
                        <td>{{ $ms['attained'] ?? 0 }}</td>
                        <td style="font-weight: 700;">{{ number_format($ms['percentage'] ?? 0, 1) }}%</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ number_format($ms['level'] ?? 0, 2) }}</td>
                        <td>{{ number_format($ms['co1'] ?? 0, 2) }}</td>
                        <td>{{ number_format($ms['co2'] ?? 0, 2) }}</td>
                        <td>{{ number_format($ms['co3'] ?? 0, 2) }}</td>
                        <td>{{ number_format($ms['co4'] ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background: #f1f5f9; font-weight: 800;">
                        <td colspan="5" style="text-align: right; padding-right: 12px;">Final Average CIA Direct Attainment (M1–M4 Average):</td>
                        <td>{{ number_format($attainmentData['avg_cia_direct_level'] ?? 0, 2) }}</td>
                        <td>{{ number_format($attainmentData['avg_cia_direct_level'] ?? 0, 2) }}</td>
                        <td>{{ number_format($attainmentData['avg_cia_direct_level'] ?? 0, 2) }}</td>
                        <td>{{ number_format($attainmentData['avg_cia_direct_level'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- 2. FINAL COURSE OUTCOME (CO) ATTAINMENT SUMMARY (DIRECT 80% + INDIRECT SURVEY 20%) -->
            <h4 style="margin: 12px 0 6px 0; font-size: 11px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                2. Final Course Outcome (CO) Attainment Summary (Direct 80% + Indirect Survey 20%)
            </h4>
            <table class="data-table" style="margin-bottom: 14px;">
                <thead>
                    <tr>
                        <th style="width: 90px;">CO Code</th>
                        <th>CIA Direct (Avg M1–M4)</th>
                        <th>ESE Direct (Grade &ge; {{ $attainmentData['ese_threshold_grade'] ?? 'D' }})</th>
                        <th>Total Direct Level (80% Wt)</th>
                        <th>Indirect Survey Level (20% Wt)</th>
                        <th>Survey Source</th>
                        <th style="width: 140px;">Final Overall CO Attainment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coKey)
                    @php $cs = $attainmentData['co_summary'][$coKey] ?? []; @endphp
                    <tr>
                        <td style="font-weight: 800;">{{ $coKey }}</td>
                        <td>{{ number_format($cs['cia_direct_level'] ?? 0, 2) }}</td>
                        <td>{{ ($cs['ese_direct_level'] ?? null) !== null ? number_format($cs['ese_direct_level'], 2) : 'Pending ESE' }}</td>
                        <td style="font-weight: 700;">{{ number_format($cs['direct_level'] ?? 0, 2) }}</td>
                        <td style="font-weight: 700;">{{ number_format($cs['indirect_level'] ?? 0, 2) }}</td>
                        <td>{{ $cs['indirect_source'] ?? '-' }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ number_format($cs['overall_level'] ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 3. PROGRAM OUTCOME (PO1–PO7) ATTAINMENT TABLE -->
            <h4 style="margin: 12px 0 6px 0; font-size: 11px; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                3. Program Outcome (PO1–PO7) Attainment Matrix
            </h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">CO / PO</th>
                        @for($p = 1; $p <= 7; $p++)
                        <th>PO{{ $p }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coKey)
                    <tr>
                        <td style="font-weight: 700;">{{ $coKey }} ({{ number_format($attainmentData['co_summary'][$coKey]['overall_level'] ?? 0, 2) }})</td>
                        @for($p = 1; $p <= 7; $p++)
                        @php $pKey = "PO{$p}"; @endphp
                        <td>{{ $mappings[$coKey][$pKey] ?? '-' }}</td>
                        @endfor
                    </tr>
                    @endforeach
                    <tr style="background: #f1f5f9; font-weight: 800;">
                        <td>PO Attainment</td>
                        @for($p = 1; $p <= 7; $p++)
                        @php
                            $pKey = "PO{$p}";
                            $pAtt = $attainmentData['po_attainments'][$pKey]['attainment'] ?? null;
                        @endphp
                        <td>{{ $pAtt !== null ? number_format($pAtt, 2) : '-' }}</td>
                        @endfor
                    </tr>
                </tbody>
            </table>

        @else
            <!-- CONSOLIDATED CIA & ESE GRADE REGISTER TABLE -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 32px;">#</th>
                        <th rowspan="2" style="width: 45px;">Roll No</th>
                        <th rowspan="2" style="width: 105px;">Reg No / PRN</th>
                        <th rowspan="2">Student Name</th>
                        <th colspan="2">Attendance (5M)</th>
                        <th rowspan="2" style="width: 90px;">CA1: Self Learning<br>(Max 5M)</th>
                        <th rowspan="2" style="width: 90px;">CE: Continuous Eval<br>(Max 10M)</th>
                        <th rowspan="2" style="width: 90px;">CA2: Practical Test<br>(Max 10M)</th>
                        <th rowspan="2" style="width: 90px;">CA4: Series Test<br>(Max 10M)</th>
                        <th rowspan="2" style="width: 95px;">Total CIA<br>(Max 40M)</th>
                        <th rowspan="2" style="width: 80px;">ESE Grade</th>
                    </tr>
                    <tr>
                        <th style="width: 65px;">%</th>
                        <th style="width: 65px;">Mark (5)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php
                        $ev = $evaluations->get($student->reg_no);
                        $stOff = isset($subjectOfficialAttendance) ? ($subjectOfficialAttendance->get($student->reg_no) ?: (!empty($student->sbte_reg_no) ? $subjectOfficialAttendance->get($student->sbte_reg_no) : null)) : null;
                        $rawPct = $ev && $ev->attendance_pct !== null ? (float)$ev->attendance_pct : ($stOff ? ($stOff->teams_percentage ?? $stOff->final_percentage) : null);
                        $ovrPct = $ev && $ev->attendance_override_pct !== null ? (float)$ev->attendance_override_pct : ($stOff ? $stOff->override_percentage : null);
                        $effPct = $ovrPct !== null ? $ovrPct : $rawPct;
                        $attMark = $effPct !== null ? \App\Models\R26EnglishEvaluation::calculateAttendanceMarks($effPct) : ($ev ? $ev->attendance_mark_5 : 0.0);
                        $sl5 = $ev ? (float)$ev->sl_conv_5 : 0.0;
                        $ce10 = $ev ? (float)$ev->ce_conv_10 : 0.0;
                        $prac10 = $ev ? (float)$ev->prac_conv_10 : 0.0;
                        $ser10 = $ev ? (float)$ev->series_conv_10 : 0.0;
                        $cia40 = min(40, round($attMark + $sl5 + $ce10 + $prac10 + $ser10));
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_no ?: '-' }}</td>
                        <td>{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                        <td class="text-left" style="font-weight: 600;">{{ $student->name }}</td>
                        <td>{{ $effPct !== null ? number_format($effPct, 1) . '%' : '-' }}</td>
                        <td style="font-weight: 700;">{{ number_format($attMark, 1) }}</td>
                        <td style="font-weight: 700;">{{ number_format($sl5, 1) }}</td>
                        <td style="font-weight: 700;">{{ number_format($ce10, 1) }}</td>
                        <td style="font-weight: 700;">{{ number_format($prac10, 1) }}</td>
                        <td style="font-weight: 700;">{{ number_format($ser10, 1) }}</td>
                        <td style="font-weight: 800; background: #f8fafc;">{{ number_format($cia40) }}</td>
                        <td style="font-weight: 700;">{{ $ev && $ev->ese_absent ? 'AB' : ($ev && $ev->ese_grade ? $ev->ese_grade : '-') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="signatures">
            <div class="sig-box">
                Course Faculty<br>
                <span style="font-weight: 400; color: #475569;">{{ $assignedStaff->pluck('name')->implode(', ') ?: 'Faculty In-Charge' }}</span>
            </div>
            <div class="sig-box">
                Head of Department / General Coordinator<br>
                <span style="font-weight: 400; color: #475569;">{{ $hod->name ?? 'HOD' }}</span>
            </div>
            <div class="sig-box">
                Principal<br>
                <span style="font-weight: 400; color: #475569;">Carmel Polytechnic College</span>
            </div>
        </div>
    </div>
</body>
</html>
