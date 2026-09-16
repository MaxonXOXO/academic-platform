<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Evaluation Card - {{ $student->name }} ({{ $student->sbte_reg_no ?: $student->reg_no }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm 12mm 10mm;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1e293b;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            line-height: 1.35;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            font-weight: 800;
        }
        .header h2 {
            font-size: 12px;
            margin: 0 0 3px 0;
            font-weight: 600;
            color: #334155;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            color: #0369a1;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .meta-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .meta-card td {
            padding: 4px 8px;
            font-size: 10.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .meta-label {
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 9.5px;
            width: 15%;
        }
        .meta-val {
            color: #0f172a;
            font-weight: 600;
            width: 35%;
        }
        .badge {
            display: inline-block;
            padding: 1px 6px;
            font-size: 9.5px;
            font-weight: 700;
            border-radius: 4px;
        }
        .badge-batch {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .badge-present {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .badge-absent {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            margin: 12px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #0284c7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cia-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            table-layout: fixed;
        }
        .cia-grid th, .cia-grid td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: center;
            font-size: 10px;
        }
        .cia-grid th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .cia-score-row td {
            font-weight: 700;
            font-size: 11px;
            font-family: monospace;
        }
        .cia-max-row td {
            font-size: 9px;
            color: #64748b;
            background: #fafafa;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            table-layout: fixed;
        }
        .report-table th, .report-table td {
            border: 1px solid #94a3b8;
            padding: 4px 3px;
            text-align: center;
            font-size: 9.5px;
            word-wrap: break-word;
        }
        .report-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 5px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .tot-row td {
            background-color: #f1f5f9 !important;
            font-weight: 800;
            font-size: 10px;
            border-top: 1.5px solid #475569;
        }
        .avg-row td {
            background-color: #e0f2fe !important;
            color: #0369a1;
            font-weight: 900;
            font-size: 10.5px;
            border-bottom: 2px solid #0284c7;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 35px;
            font-weight: 700;
            font-size: 10.5px;
            color: #1e293b;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 12px;
        }
        .print-btn {
            background-color: #0284c7;
            color: white;
            border: none;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .print-btn:hover {
            background-color: #0369a1;
        }
        .close-btn {
            background-color: #64748b;
            color: white;
            border: none;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
        }
        .close-btn:hover {
            background-color: #475569;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .meta-card, .cia-grid, .report-table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print action-bar">
        <button class="print-btn" onclick="window.print()">
            <svg style="width: 12px; height: 12px; vertical-align: -1px; margin-right: 4px; fill: currentColor; display: inline-block;" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            Print Student Report
        </button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>Individual Student Practical Continuous Evaluation & Attendance Card</h3>
    </div>

    <!-- Student and Subject Metadata -->
    <table class="meta-card">
        <tr>
            <td class="meta-label">Student Name:</td>
            <td class="meta-val" style="font-size: 12px; font-weight: 800; color: #0f172a;">{{ $student->name }}</td>
            <td class="meta-label">Roll No:</td>
            <td class="meta-val"><strong style="font-family: monospace; font-size: 12px;">{{ $student->roll_no ?: '-' }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Register No (PRN):</td>
            <td class="meta-val" style="font-family: monospace; color: #0284c7; font-weight: 700;">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
            <td class="meta-label">Lab Batch:</td>
            <td class="meta-val">
                <span class="badge badge-batch">{{ !empty($labBatch) ? ('Batch ' . $labBatch) : 'Whole Class' }}</span>
            </td>
        </tr>
        <tr>
            <td class="meta-label">Subject / Course:</td>
            <td class="meta-val">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</td>
            <td class="meta-label">Class &amp; Semester:</td>
            <td class="meta-val">{{ $cleanedBatch }} &bull; Semester {{ $batchSubject->semester }}</td>
        </tr>
        <tr>
            <td class="meta-label">Curriculum / Scheme:</td>
            <td class="meta-val">Revision 2021 (Outcome Based Education)</td>
            <td class="meta-label">Conducted Exps:</td>
            <td class="meta-val">
                <strong>{{ $attendedCount }} Done</strong> / {{ $totalCompletedExps }} Total Conducted ({{ $totalExperiments }} in syllabus)
            </td>
        </tr>
    </table>

    <!-- Consolidated Continuous Internal Assessment (CIA) Summary Card -->
    <div class="section-title">
        <span>Continuous Internal Assessment (CIA 75 Marks) — Formative &amp; Summative Summary</span>
        <span style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: none;">Divisor: {{ $totalCompletedExps }} completed experiments</span>
    </div>

    <table class="cia-grid">
        <thead>
            <tr>
                <th colspan="6" style="background-color: #f0f9ff; color: #0369a1; border-bottom: 2px solid #0284c7;">Continuous Lab Work Rubrics (Max 37.5)</th>
                <th rowspan="2" style="width: 10%; background-color: #fffbeb; color: #b45309;">Open Ended<br>Project</th>
                <th rowspan="2" style="width: 11%; background-color: #ecfdf5; color: #047857;">Lab Work<br>Attendance</th>
                <th colspan="3" style="background-color: #faf5ff; color: #7e22ce;">Practical Tests (Max 15)</th>
                <th rowspan="2" style="width: 11%; background-color: #f0fdf4; color: #15803d; border-left: 2px solid #22c55e;">Total CIA<br>Marks</th>
            </tr>
            <tr>
                <th style="width: 6%;">Rough<br>Rec.</th>
                <th style="width: 6%;">Fair<br>Rec.</th>
                <th style="width: 6%;">Obs &amp;<br>Prep</th>
                <th style="width: 6%;">Proc &amp;<br>Punct</th>
                <th style="width: 6.5%;">Viva<br>Voce</th>
                <th style="width: 9%; background-color: #e0f2fe; color: #0369a1;">Lab Work<br>Avg</th>
                <th style="width: 6%;">Test 1<br>(15)</th>
                <th style="width: 6%;">Test 2<br>(15)</th>
                <th style="width: 6.5%; background-color: #f3e8ff;">Test Avg<br>(15)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="cia-max-row">
                <td>5.0</td>
                <td>7.5</td>
                <td>7.5</td>
                <td>7.5</td>
                <td>10.0</td>
                <td style="font-weight: bold; background: #e0f2fe; color: #0369a1;">37.5</td>
                <td>7.5</td>
                <td>15.0</td>
                <td>15.0</td>
                <td>15.0</td>
                <td style="font-weight: bold; background: #f3e8ff; color: #7e22ce;">15.0</td>
                <td style="font-weight: 800; background: #dcfce7; color: #15803d;">75.0</td>
            </tr>
            <tr class="cia-score-row">
                <td style="color: #d97706;">{{ number_format($avgRoughRecord, 2) }}</td>
                <td style="color: #059669;">{{ number_format($avgFairRecord, 2) }}</td>
                <td style="color: #0284c7;">{{ number_format($avgObsPrep, 2) }}</td>
                <td style="color: #7c3aed;">{{ number_format($avgProcPunct, 2) }}</td>
                <td style="color: #e11d48;">{{ number_format($avgVivaVoce, 2) }}</td>
                <td style="background-color: #e0f2fe; color: #0369a1; font-weight: 800; font-size: 12px;">{{ number_format($avgLabWork, 2) }}</td>
                <td style="color: #b45309;">{{ number_format($microProject, 1) }}</td>
                <td style="color: #047857;">
                    {{ number_format($attendanceMarks, 1) }}
                    <span style="font-size: 8.5px; display: block; font-family: sans-serif; color: #64748b;">({{ $attendancePercentage }}%)</span>
                </td>
                <td style="color: #6b21a8;">{{ number_format($scoreT1, 1) }}</td>
                <td style="color: #6b21a8;">{{ number_format($scoreT2, 1) }}</td>
                <td style="background-color: #f3e8ff; color: #7e22ce; font-weight: 800;">{{ number_format($avgTests, 2) }}</td>
                <td style="background-color: #dcfce7; color: #15803d; font-weight: 900; font-size: 13px; border-left: 2px solid #22c55e;">
                    {{ number_format($totalInternal, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    @if(!empty($openEndedTopic))
    <div style="margin-top: -6px; margin-bottom: 10px; font-size: 10px; color: #475569; background: #fffbeb; border: 1px dashed #fde68a; padding: 4px 8px; border-radius: 4px;">
        <strong style="color: #92400e;">Open-Ended Project / Micro-Project Topic:</strong> {{ $openEndedTopic }}
    </div>
    @endif

    <!-- Detailed Experiment Log Table -->
    <div class="section-title">
        <span>Day-to-Day Practical Experiment Marks &amp; Attendance Log</span>
        <span style="font-size: 10px; color: #64748b; font-weight: 600; text-transform: none;">Total Experiments: {{ count($expRecords) }} &bull; Conducted: {{ $conductedCount }}</span>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%;">Exp #</th>
                <th style="width: 32%;">Experiment Title</th>
                <th style="width: 5%;">CO</th>
                <th style="width: 8%;">Conducted<br>Date</th>
                <th style="width: 8%;">Attended /<br>Eval Date</th>
                <th style="width: 7%;">Attend.</th>
                <th style="width: 5.5%;">Rough<br>(5)</th>
                <th style="width: 5.5%;">Fair<br>(7.5)</th>
                <th style="width: 5.5%;">Obs<br>(7.5)</th>
                <th style="width: 5.5%;">Proc<br>(7.5)</th>
                <th style="width: 6%;">Viva<br>(10)</th>
                <th style="width: 7%; background-color: #f0f7ff;">Total<br>(37.5)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expRecords as $r)
            <tr>
                <td style="font-family: monospace; font-weight: bold;">{{ $r['experiment_no'] }}</td>
                <td class="align-left" title="{{ $r['title'] }}">
                    {{ \Illuminate\Support\Str::limit($r['title'], 55) }}
                </td>
                <td style="font-family: monospace; font-size: 8.5px; color: #64748b;">{{ $r['co_tag'] }}</td>
                <td style="font-family: monospace; font-size: 8.5px;">{{ $r['conducted_date'] ? date('d-m-Y', strtotime($r['conducted_date'])) : '-' }}</td>
                <td style="font-family: monospace; font-size: 8.5px;">
                    @if($r['evaluation_date'])
                        {{ date('d-m-Y', strtotime($r['evaluation_date'])) }}
                    @else
                        <span style="color: #94a3b8;">-</span>
                    @endif
                </td>
                <td>
                    @if($r['is_attended'])
                        <span class="badge badge-present">Present</span>
                    @else
                        <span class="badge badge-absent">Absent</span>
                    @endif
                </td>
                <td style="font-family: monospace;">{{ $r['has_score'] ? number_format($r['rough_record'], 2) : '-' }}</td>
                <td style="font-family: monospace;">{{ $r['has_score'] ? number_format($r['fair_record'], 2) : '-' }}</td>
                <td style="font-family: monospace;">{{ $r['has_score'] ? number_format($r['obs_prep'], 2) : '-' }}</td>
                <td style="font-family: monospace;">{{ $r['has_score'] ? number_format($r['proc_punct'], 2) : '-' }}</td>
                <td style="font-family: monospace;">{{ $r['has_score'] ? number_format($r['viva_voce'], 2) : '-' }}</td>
                <td style="font-family: monospace; font-weight: bold; background-color: #f0f7ff;">
                    {{ $r['has_score'] ? number_format($r['total_mark'], 2) : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="padding: 12px; color: #64748b;">No experiments found for this subject.</td>
            </tr>
            @endforelse

            <!-- Total Sum across all graded experiments -->
            <tr class="tot-row">
                <td colspan="6" class="align-left">
                    TOTAL MARKS OBTAINED (Across {{ $gradedCount }} Graded Experiments)
                </td>
                <td style="font-family: monospace;">{{ number_format($sumRough, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($sumFair, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($sumObs, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($sumProc, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($sumViva, 2) }}</td>
                <td style="font-family: monospace; font-size: 10.5px;">{{ number_format($sumTotal, 2) }}</td>
            </tr>

            <!-- Consolidated Formative Rubric Averages -->
            <tr class="avg-row">
                <td colspan="6" class="align-left">
                    CONSOLIDATED FORMATIVE AVERAGE (Divided by {{ $totalCompletedExps }} Conducted Experiments)
                </td>
                <td style="font-family: monospace;">{{ number_format($avgRoughRecord, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($avgFairRecord, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($avgObsPrep, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($avgProcPunct, 2) }}</td>
                <td style="font-family: monospace;">{{ number_format($avgVivaVoce, 2) }}</td>
                <td style="font-family: monospace; font-size: 11.5px; font-weight: 900;">{{ number_format($avgLabWork, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Final Result Status Box -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #fafafa; border: 1px solid #cbd5e1; border-radius: 6px;">
        <tr>
            <td style="padding: 6px 10px; width: 33%; border-right: 1px solid #e2e8f0;">
                <span style="font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: 700; display: block;">Continuous Internal Assessment (CIA):</span>
                <strong style="font-size: 13px; color: #15803d; font-family: monospace;">{{ number_format($totalInternal, 2) }} / 75.00</strong>
            </td>
            <td style="padding: 6px 10px; width: 33%; border-right: 1px solid #e2e8f0;">
                <span style="font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: 700; display: block;">Board Exam (ESE - 50M):</span>
                <strong style="font-size: 13px; color: #0284c7; font-family: monospace;">{{ $eseDisplay }}</strong>
            </td>
            <td style="padding: 6px 10px; width: 34%;">
                <span style="font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: 700; display: block;">Total Result (125M) &amp; Grade:</span>
                <strong style="font-size: 13px; color: #7e22ce; font-family: monospace;">{{ $finalResultDisplay }}</strong>
            </td>
        </tr>
    </table>

    <!-- Signature Block -->
    <table class="footer-signatures">
        <tr>
            <td>
                ____________________________<br>
                Signature of Student
            </td>
            <td>
                ____________________________<br>
                Signature of Lab Assessor / Faculty
            </td>
            <td>
                ____________________________<br>
                Head of Department
            </td>
        </tr>
    </table>

</body>
</html>
