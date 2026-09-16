<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Teaching & Attendance Log Register - {{ $subject->subject_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 8mm 10mm 8mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            margin: 0 auto;
            padding: 4px;
            font-size: 10px;
            line-height: 1.35;
            max-width: 194mm;
            background: #fff;
        }

        .no-print {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 12px;
            padding: 8px 12px;
            background: #f1f5f9;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-print {
            background: #1e3a8a;
            color: #fff;
        }

        .btn-close {
            background: #e2e8f0;
            color: #334155;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .college-name {
            font-size: 15px;
            font-weight: 900;
            color: #1e3a8a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .college-sub {
            font-size: 10px;
            font-weight: 600;
            color: #475569;
            margin-top: 1px;
        }

        .report-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 2px 14px;
            border-radius: 4px;
            margin-top: 4px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 9.5px;
        }

        .meta-table td {
            padding: 3px 6px;
            border: 1px solid #cbd5e1;
        }

        .meta-table .lbl {
            background: #f8fafc;
            font-weight: 700;
            color: #334155;
            width: 14%;
        }

        .meta-table .val {
            color: #0f172a;
            font-weight: 600;
            width: 36%;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }

        .log-table th,
        .log-table td {
            border: 1px solid #64748b;
            padding: 4px 5px;
            vertical-align: middle;
        }

        .log-table thead {
            display: table-header-group;
        }

        .log-table tr {
            page-break-inside: avoid;
        }

        .log-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            font-size: 8.5px;
            text-transform: uppercase;
            text-align: center;
        }

        .log-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }

        .summary-card {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            padding: 6px 10px;
            margin-bottom: 12px;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .summary-title {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e3a8a;
            margin-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 2px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            font-size: 8.5px;
        }

        .stat-box {
            background: #fff;
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            text-align: center;
        }

        .stat-box .val {
            font-size: 11px;
            font-weight: 800;
            color: #1e3a8a;
        }

        .stat-box .desc {
            color: #64748b;
            font-size: 7.5px;
            text-transform: uppercase;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .sig-block {
            text-align: center;
            width: 28%;
            font-size: 9px;
        }

        .sig-line {
            border-top: 1px dashed #334155;
            margin-bottom: 4px;
            height: 1px;
        }

        .sig-name {
            font-weight: 800;
            color: #0f172a;
        }

        .sig-title {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Print Controls -->
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print A4 Log Register
        </button>
        <button onclick="window.close()" class="btn btn-close">Close</button>
    </div>

    <!-- College Header -->
    <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Government Aided Polytechnic College • Approved by AICTE • Affiliated to SBTE Kerala</div>
        <div class="report-badge">Official Classroom Teaching & Attendance Log Register</div>
    </div>

    <!-- Meta Details Grid -->
    <table class="meta-table">
        <tr>
            <td class="lbl">Department:</td>
            <td class="val">{{ $fullDepartment }}</td>
            <td class="lbl">Semester & Batch:</td>
            <td class="val">Semester {{ $subject->semester }} (Batch {{ $cleanedBatch }})</td>
        </tr>
        <tr>
            <td class="lbl">Course:</td>
            <td class="val"><strong>{{ $subject->subject_code }}</strong> - {{ $subject->subject_name }}</td>
            <td class="lbl">Faculty In-Charge:</td>
            <td class="val">{{ $lecturerName }}</td>
        </tr>
        <tr>
            <td class="lbl">Sessions Recorded:</td>
            <td class="val"><strong>{{ $logs->count() }}</strong> Logs ({{ $totalHours }} Conducted Hours)</td>
            <td class="lbl">Enrolled Students:</td>
            <td class="val"><strong>{{ $totalEnrolled }}</strong> Students</td>
        </tr>
    </table>

    <!-- Log Table -->
    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 25px;">Sl</th>
                <th style="width: 58px;">Date</th>
                <th style="width: 48px;">Period</th>
                <th>Syllabus Topic Covered / Log Entry</th>
                <th style="width: 32px;">Pres</th>
                <th style="width: 32px;">Abs</th>
                <th style="width: 42px;">Attn %</th>
                <th style="width: 42px;">Sign</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $idx => $log)
            <tr>
                <td class="text-center" style="font-weight: 700;">{{ $idx + 1 }}</td>
                <td class="text-center font-mono">{{ $log->formatted_date }}</td>
                <td class="text-center font-mono" style="color: #475569;">{{ $log->period_label }}</td>
                <td>{{ $log->topics_covered ?: 'Syllabus lecture session' }}</td>
                <td class="text-center" style="font-weight: 700; color: #047857;">{{ $log->present_count }}</td>
                <td class="text-center" style="color: #b91c1c;">{{ $log->absent_count }}</td>
                <td class="text-center" style="font-weight: 700; color: {{ $log->attendance_pct < 75 ? '#b91c1c' : '#047857' }};">
                    {{ number_format($log->attendance_pct, 1) }}%
                </td>
                <td class="text-center" style="color: #64748b; font-size: 8px;">✓</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 16px; color: #64748b;">
                    No class logs recorded yet for this subject.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Statistics Card -->
    <div class="summary-card">
        <div class="summary-title">Class Log & Coverage Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="val">{{ $logs->count() }}</div>
                <div class="desc">Sessions Recorded</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ $totalHours }}</div>
                <div class="desc">Total Hours Conducted</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ number_format($overallAvgAttn, 1) }}%</div>
                <div class="desc">Cumulative Attendance</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ $completedTopicsCount }} / {{ $totalPlannedTopics }}</div>
                <div class="desc">Syllabus Progress</div>
            </div>
        </div>
    </div>

    <!-- Official Signatures Row -->
    <div class="signatures">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-name">{{ $lecturerName }}</div>
            <div class="sig-title">Faculty In-Charge</div>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-name">Head of Department</div>
            <div class="sig-title">{{ $fullDepartment }}</div>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-name">Principal</div>
            <div class="sig-title">Carmel Polytechnic College</div>
        </div>
    </div>

</body>
</html>
