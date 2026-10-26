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
            size: A4 {{ in_array($reportType, ['consolidated', 'cia']) ? 'landscape' : 'portrait' }};
            margin: 12mm 10mm;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #111827;
            font-size: 13.5px;
            line-height: 1.45;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .college-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            color: #0f172a;
        }
        .dept-subtitle {
            margin: 4px 0 0 0;
            font-size: 14.5px;
            font-weight: 600;
            color: #334155;
        }
        .report-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 14px;
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .meta-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
        }
        .meta-col {
            line-height: 1.65;
        }
        .meta-col strong {
            color: #1e293b;
            display: inline-block;
            min-width: 95px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 7px 6px;
            text-align: center;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
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
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 220px;
        }
        .signature-line {
            border-top: 1.5px solid #334155;
            padding-top: 6px;
            font-weight: 700;
            color: #1e293b;
        }
        .signature-role {
            font-size: 11px;
            color: #64748b;
        }
        .no-print {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
            gap: 8px;
        }
        .print-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 12px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .highlight-col { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .meta-box { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
                    <th style="width: 45px;">Sl No</th>
                    <th style="width: 60px;">Roll No</th>
                    <th style="width: 120px;">Register No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 80px;">Batch</th>
                    <th style="width: 100px;">TEAMS Log %</th>
                    <th style="width: 100px;">Override %</th>
                    <th style="width: 110px;">Effective %</th>
                    <th style="width: 110px;" class="highlight-col">Atten Mark (5M)</th>
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
                    <th style="width: 45px;">Sl No</th>
                    <th style="width: 60px;">Roll No</th>
                    <th style="width: 120px;">Register No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 80px;">Batch</th>
                    <th style="width: 110px;">Series 1 (40M)</th>
                    <th style="width: 110px;">Series 2 (40M)</th>
                    <th style="width: 110px;">Average (40M)</th>
                    <th style="width: 130px;" class="highlight-col">Scaled Series Total (15M)</th>
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
                    <th style="width: 45px;">Sl No</th>
                    <th style="width: 60px;">Roll No</th>
                    <th style="width: 120px;">Register No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 80px;">Batch</th>
                    <th style="width: 110px;">Daywork Avg (50M)</th>
                    <th style="width: 110px;">Lab Work (30M)</th>
                    <th style="width: 110px;">Open-Ended (50M)</th>
                    <th style="width: 110px;">Open-Ended (10M)</th>
                    <th style="width: 130px;" class="highlight-col">CE Total (40M)</th>
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
                    <th style="width: 40px;">Sl</th>
                    <th style="width: 50px;">Roll</th>
                    <th style="width: 105px;">Reg No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 65px;">Batch</th>
                    <th style="width: 70px;">Lab (30M)</th>
                    <th style="width: 70px;">Open (10M)</th>
                    <th style="width: 75px;">CE (40M)</th>
                    <th style="width: 75px;">Series (15M)</th>
                    <th style="width: 70px;">Atten (5M)</th>
                    <th style="width: 80px;" class="highlight-col">CIA (60M)</th>
                    <th style="width: 75px;">ESE (40M)</th>
                    <th style="width: 85px;" class="highlight-col">Total (100M)</th>
                    <th style="width: 55px;">Grade</th>
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
                    <th style="width: 45px;">Sl No</th>
                    <th style="width: 60px;">Roll No</th>
                    <th style="width: 120px;">Register No</th>
                    <th class="text-left">Student Name</th>
                    <th style="width: 75px;">Batch</th>
                    <th style="width: 90px;">CE Total (40M)</th>
                    <th style="width: 90px;">Series (15M)</th>
                    <th style="width: 90px;">Attendance (5M)</th>
                    <th style="width: 100px;" class="highlight-col">CIA Total (60M)</th>
                    <th style="width: 90px;">ESE (40M)</th>
                    <th style="width: 100px;" class="highlight-col">Grand Total (100M)</th>
                    <th style="width: 65px;">Grade</th>
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
                    <td class="font-mono">{{ number_format($sc['ce_total_40'] ?? 0, 2) }}</td>
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
