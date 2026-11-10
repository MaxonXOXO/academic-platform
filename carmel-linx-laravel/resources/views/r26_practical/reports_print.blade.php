<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $reportType = $type ?? 'cia';
        $reportTitles = [
            'cia'          => 'Continuous Internal Assessment (CIA) Mark Register',
            'consolidated' => 'Consolidated Academic Performance Register (100 Marks)',
            'attendance'   => 'Practical Course Attendance Statement (5 Marks)',
            'series'       => 'Practical Series Test Evaluation Register (15 Marks)',
            'ce'           => 'Continuous Evaluation (CE) Mark Sheet (40 Marks)',
        ];
        $currentTitle = $reportTitles[$reportType] ?? 'Laboratory Evaluation Report';
    @endphp
    <title>{{ $currentTitle }} - {{ $batchSubject->subject_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 6mm;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 8px 10px;
            color: #111827;
            font-size: 11px;
            line-height: 1.35;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .college-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            color: #0f172a;
        }
        .dept-subtitle {
            margin: 2px 0 0 0;
            font-size: 11.5px;
            font-weight: 600;
            color: #334155;
        }
        .report-badge {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 12px;
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .meta-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            padding: 6px 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 10.5px;
        }
        .meta-col {
            line-height: 1.5;
        }
        .meta-col strong {
            color: #1e293b;
            display: inline-block;
            min-width: 80px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 6px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 4px 3px;
            text-align: center;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.01em;
            line-height: 1.25;
        }
        tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace, Courier, sans-serif; }
        .bold { font-weight: 700; }
        .highlight-col { background-color: #f8fafc; font-weight: 700; }
        .footer {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            font-size: 10.5px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-line {
            border-top: 1.5px solid #334155;
            padding-top: 4px;
            font-weight: 700;
            color: #1e293b;
        }
        .signature-role {
            font-size: 10px;
            color: #64748b;
        }
        .no-print {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
            gap: 8px;
        }
        .print-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 11px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .highlight-col { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .meta-box { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
    </div>

    <!-- College Header -->
    <div class="header">
        <h1 class="college-title">Carmel Polytechnic College, Alappuzha</h1>
        <p class="dept-subtitle">Department of {{ $classroom?->branch ?? 'Diploma Engineering' }} · Revision 2026 Practical Curriculum</p>
        <div class="report-badge">{{ $currentTitle }}</div>
    </div>

    <!-- Metadata Panel -->
    <div class="meta-box">
        <div class="meta-col">
            <div><strong>Subject:</strong> {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</div>
            <div><strong>Batch / Branch:</strong> {{ $classroom?->branch ?? '—' }} - {{ $batchSubject->classroom_id }}</div>
            <div><strong>Semester:</strong> Semester {{ $batchSubject->semester }}</div>
        </div>
        <div class="meta-col" style="text-align: right;">
            <div><strong>Academic Year:</strong> 2026-2027</div>
            <div><strong>Total Students:</strong> {{ count($students) }}</div>
            <div><strong>Generated:</strong> {{ date('d-M-Y H:i') }}</div>
        </div>
    </div>

    @if($reportType === 'attendance')
        <!-- ═══ 1. ATTENDANCE STATEMENT (5M) ═══ -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">Sl</th>
                    <th style="width: 40px;">Roll</th>
                    <th style="width: 95px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 55px;">Batch</th>
                    <th style="width: 70px;">TEAMS %</th>
                    <th style="width: 70px;">Override %</th>
                    <th style="width: 70px;">Effective %</th>
                    <th style="width: 70px;" class="highlight-col">Atten (5M)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $att = $attendanceMarks[$student->reg_no] ?? [];
                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $student->roll_no ?? ($index + 1) }}</td>
                    <td class="font-mono bold">{{ $student->reg_no }}</td>
                    <td class="text-left bold">{{ $student->name }}</td>
                    <td>{{ $batchVal }}</td>
                    <td class="font-mono">{{ $att['teams_percentage'] !== null ? number_format($att['teams_percentage'], 1).'%' : (($att['raw_percentage'] ?? null) !== null ? number_format($att['raw_percentage'], 1).'%' : '—') }}</td>
                    <td class="font-mono">{{ $att['override_percentage'] !== null ? number_format($att['override_percentage'], 1).'%' : '—' }}</td>
                    <td class="font-mono bold">{{ isset($att['effective_percentage']) && $att['effective_percentage'] !== null ? number_format($att['effective_percentage'], 1).'%' : '—' }}</td>
                    <td class="highlight-col font-mono">{{ $att['mark'] ?? 0 }} / 5</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($reportType === 'series')
        <!-- ═══ 2. SERIES TEST MARKSHEET (15M) ═══ -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">Sl</th>
                    <th style="width: 40px;">Roll</th>
                    <th style="width: 95px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 55px;">Batch</th>
                    <th style="width: 70px;">Series 1 (40)</th>
                    <th style="width: 70px;">Series 2 (40)</th>
                    <th style="width: 70px;">Avg (40)</th>
                    <th style="width: 80px;" class="highlight-col">Series (15M)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $sc = $consolidatedScores[$student->reg_no] ?? [];
                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                    $s1 = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                    $s2 = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                    $s1Val = $s1 ? floatval($s1->total_score_40) : null;
                    $s2Val = $s2 ? floatval($s2->total_score_40) : null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $student->roll_no ?? ($index + 1) }}</td>
                    <td class="font-mono bold">{{ $student->reg_no }}</td>
                    <td class="text-left bold">{{ $student->name }}</td>
                    <td>{{ $batchVal }}</td>
                    <td class="font-mono">{{ $s1Val !== null ? number_format($s1Val, 2) : '—' }}</td>
                    <td class="font-mono">{{ $s2Val !== null ? number_format($s2Val, 2) : '—' }}</td>
                    <td class="font-mono">{{ number_format($sc['raw_series_avg'] ?? 0, 2) }}</td>
                    <td class="highlight-col font-mono bold">{{ number_format($sc['scaled_series_15'] ?? 0, 2) }} / 15</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($reportType === 'ce')
        <!-- ═══ 3. CONTINUOUS EVALUATION MARKSHEET (40M) ═══ -->
        <table>
            <thead>
                <tr>
                    <th style="width: 28px;">Sl</th>
                    <th style="width: 36px;">Roll</th>
                    <th style="width: 90px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 50px;">Batch</th>
                    <th style="width: 60px;">Daywork (50)</th>
                    <th style="width: 60px;">Lab (30)</th>
                    <th style="width: 60px;">Open (50)</th>
                    <th style="width: 60px;">Open (10)</th>
                    <th style="width: 65px;" class="highlight-col">CE (40M)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $sc = $consolidatedScores[$student->reg_no] ?? [];
                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $student->roll_no ?? ($index + 1) }}</td>
                    <td class="font-mono bold">{{ $student->reg_no }}</td>
                    <td class="text-left bold">{{ $student->name }}</td>
                    <td>{{ $batchVal }}</td>
                    <td class="font-mono">{{ number_format($sc['raw_exp_avg'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_lab_work_30'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['raw_open_ended'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_open_ended_10'] ?? 0, 2) }}</td>
                    <td class="highlight-col font-mono bold">{{ number_format($sc['ce_total_40'] ?? 0, 2) }} / 40</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($reportType === 'consolidated')
        <!-- ═══ 4. CONSOLIDATED REGISTER (100M) ═══ -->
        <table>
            <thead>
                <tr>
                    <th style="width: 26px;">Sl</th>
                    <th style="width: 32px;">Roll</th>
                    <th style="width: 78px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 46px;">Batch</th>
                    <th style="width: 44px;">Lab (30)</th>
                    <th style="width: 44px;">Open (10)</th>
                    <th style="width: 46px;">CE (40)</th>
                    <th style="width: 46px;">Series (15)</th>
                    <th style="width: 38px;">Atten (5)</th>
                    <th style="width: 48px;" class="highlight-col">CIA (60)</th>
                    <th style="width: 44px;">ESE (40)</th>
                    <th style="width: 48px;" class="highlight-col">Total (100)</th>
                    <th style="width: 36px;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $sc = $consolidatedScores[$student->reg_no] ?? [];
                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $student->roll_no ?? ($index + 1) }}</td>
                    <td class="font-mono bold">{{ $student->reg_no }}</td>
                    <td class="text-left bold">{{ $student->name }}</td>
                    <td>{{ $batchVal }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_lab_work_30'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_open_ended_10'] ?? 0, 2) }}</td>
                    <td class="font-mono bold">{{ number_format($sc['ce_total_40'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_series_15'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ $sc['attendance_mark_5'] ?? 0 }}</td>
                    <td class="highlight-col font-mono bold">{{ $sc['total_cia_60'] ?? 0 }}</td>
                    <td class="font-mono">{{ number_format($sc['ese_score_40'] ?? 0, 2) }}</td>
                    <td class="highlight-col font-mono bold">{{ $sc['grand_total_100'] ?? 0 }}</td>
                    <td class="bold font-mono">{{ $sc['grade'] ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <!-- ═══ 5. CIA INTERNAL MARK REGISTER (60M) ═══ -->
        <table>
            <thead>
                <tr>
                    <th style="width: 28px;">Sl</th>
                    <th style="width: 36px;">Roll</th>
                    <th style="width: 88px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 52px;">Batch</th>
                    <th style="width: 54px;">Daywork (30M)</th>
                    <th style="width: 54px;">Open-Ended (10M)</th>
                    <th style="width: 58px;">CE Total (40M)</th>
                    <th style="width: 58px;">Series (15M)</th>
                    <th style="width: 44px;">Atten (5M)</th>
                    <th style="width: 62px;" class="highlight-col">CIA Total (60M)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                @php
                    $sc = $consolidatedScores[$student->reg_no] ?? [];
                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $student->roll_no ?? ($index + 1) }}</td>
                    <td class="font-mono bold">{{ $student->reg_no }}</td>
                    <td class="text-left bold">{{ $student->name }}</td>
                    <td>{{ $batchVal }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_lab_work_30'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_open_ended_10'] ?? 0, 2) }}</td>
                    <td class="font-mono bold">{{ number_format($sc['ce_total_40'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ number_format($sc['scaled_series_15'] ?? 0, 2) }}</td>
                    <td class="font-mono">{{ $sc['attendance_mark_5'] ?? 0 }}</td>
                    <td class="highlight-col font-mono bold">{{ $sc['total_cia_60'] ?? 0 }} / 60</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Official Signatures Block -->
    <div class="footer">
        <div class="signature-box">
            <div class="signature-line">Staff In-Charge</div>
            <div class="signature-role">Lecturer / Instructor</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Head of Department</div>
            <div class="signature-role">Dept. of {{ $classroom?->branch ?? 'Diploma Engineering' }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Principal</div>
            <div class="signature-role">Carmel Polytechnic College</div>
        </div>
    </div>

</body>
</html>
