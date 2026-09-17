<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Experiments &amp; Attendance Log - {{ $batchSubject->subject_code }}</title>
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

        .btn-csv {
            background: #047857;
            color: #fff;
        }

        .btn-back {
            background: #0284c7;
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
            font-size: 10.5px;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
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
            width: 15%;
        }

        .meta-table .val {
            color: #0f172a;
            font-weight: 600;
            width: 35%;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            font-size: 8.5px;
        }

        .stat-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            border-radius: 4px;
            text-align: center;
        }

        .stat-box .val {
            font-size: 13px;
            font-weight: 800;
            color: #1e3a8a;
        }

        .stat-box .desc {
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
        }

        .sig-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 25px;
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .sig-box {
            text-align: center;
            border-top: 1px solid #475569;
            padding-top: 6px;
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
    <script>
    function goBackToClassroom() {
        if (window.opener && !window.opener.closed) {
            window.close();
        } else if (document.referrer && document.referrer.length > 0) {
            window.location.href = document.referrer;
        } else {
            window.location.href = "{{ url('/r26/classroom/practicum/' . $batchSubject->id . '?mode=lab&tab=roster') }}";
        }
    }
    </script>
</head>
<body>

    <!-- Print Controls -->
    <div class="no-print">
        <button onclick="goBackToClassroom()" class="btn btn-back">&#8592; Back to Classroom</button>
        <a href="{{ url('/r26/classroom/practicum/' . $batchSubject->id . '/export-experiments-log-csv') }}" class="btn btn-csv">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Experiments Log (A4)
        </button>
        <button onclick="window.close()" class="btn btn-close">Close</button>
    </div>

    <!-- College Header -->
    <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Government Aided Polytechnic College • Approved by AICTE • Affiliated to SBTE Kerala</div>
        <div class="report-badge">Practical Experiments Conducted &amp; Attendance Log Register — Revision 2026</div>
    </div>

    <!-- Meta Details Grid -->
    <table class="meta-table">
        <tr>
            <td class="lbl">Department:</td>
            <td class="val">{{ $departmentName }}</td>
            <td class="lbl">Semester &amp; Batch:</td>
            <td class="val">Semester {{ $batchSubject->semester }} ({{ $batchName }})</td>
        </tr>
        <tr>
            <td class="lbl">Course:</td>
            <td class="val"><strong>{{ $batchSubject->subject_code }}</strong> - {{ $batchSubject->subject_name }}</td>
            <td class="lbl">Faculty In-Charge:</td>
            <td class="val">{{ $lecturerName }}</td>
        </tr>
        <tr>
            <td class="lbl">Practical Logs:</td>
            <td class="val"><strong>{{ $logs->count() }}</strong> Sessions Conducted</td>
            <td class="lbl">Enrolled Students:</td>
            <td class="val"><strong>{{ $totalEnrolled }}</strong> Students</td>
        </tr>
        <tr>
            <td class="lbl">Date Generated:</td>
            <td class="val"><strong>{{ date('d/m/Y') }}</strong></td>
            <td class="lbl">Academic Scheme:</td>
            <td class="val">Curriculum Revision 2026 Practicum</td>
        </tr>
    </table>

    <!-- Log Table -->
    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 25px;">Sl</th>
                <th style="width: 65px;">Date</th>
                <th style="width: 48px;">Period</th>
                <th style="width: 48px;">Batch</th>
                <th>Experiment / Practical Topic Covered</th>
                <th style="width: 65px;">Students Attended</th>
                <th style="width: 35px;">Absent</th>
                <th style="width: 110px;">Absentees Roll Nos</th>
                <th style="width: 38px;">Sign</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $idx => $log)
            <tr>
                <td class="text-center" style="font-weight: 700;">{{ $idx + 1 }}</td>
                <td class="text-center font-mono">{{ $log->formatted_date }}</td>
                <td class="text-center font-mono" style="color: #475569;">{{ $log->period_label }}</td>
                <td class="text-center font-mono" style="font-size: 8px;">{{ $log->sub_batch ?: 'All' }}</td>
                <td style="font-weight: 600; color: #1e293b;">{{ $log->topics_covered }}</td>
                <td class="text-center" style="font-weight: 700; color: #047857;">
                    {{ $log->present_count }} ({{ number_format($log->attendance_pct, 0) }}%)
                </td>
                <td class="text-center" style="font-weight: 700; color: {{ $log->absent_count > 0 ? '#b91c1c' : '#047857' }};">
                    {{ $log->absent_count }}
                </td>
                <td class="text-center font-mono" style="font-size: 8.5px; color: {{ $log->absent_display !== 'NIL' ? '#b91c1c' : '#047857' }}; font-weight: 700;">
                    {{ $log->absent_display }}
                </td>
                <td class="text-center" style="color: #64748b; font-size: 8px;">✓</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 16px; color: #64748b;">
                    No practical experiment logs recorded yet for this course.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Statistics Card -->
    <div class="summary-card">
        <div class="summary-title">Practical Sessions &amp; Attendance Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="val">{{ $logs->count() }}</div>
                <div class="desc">Sessions Conducted</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ $totalEnrolled }}</div>
                <div class="desc">Enrolled Students</div>
            </div>
            <div class="stat-box">
                <div class="val" style="color: {{ $avgAttnPct >= 75 ? '#047857' : '#b91c1c' }};">{{ $avgAttnPct }}%</div>
                <div class="desc">Overall Practical Attendance %</div>
            </div>
        </div>
    </div>

    <!-- Signatures Grid -->
    <div class="sig-grid">
        <div class="sig-box">
            <div class="sig-name">{{ $lecturerName }}</div>
            <div class="sig-title">Faculty In-Charge</div>
        </div>
        <div class="sig-box">
            <div class="sig-name">{{ $hod->name ?? 'Head of Department' }}</div>
            <div class="sig-title">Head of Department (HOD)</div>
        </div>
    </div>

</body>
</html>
