<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Experiments Conducted Log - {{ $batchSubject->subject_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 0 auto;
            padding: 12px;
            font-size: 11px;
            line-height: 1.4;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px double #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 13px;
            margin: 0 0 4px 0;
            font-weight: 600;
            color: #2b2b2b;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            color: #444;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .meta-info td {
            padding: 4px 6px;
            font-size: 11px;
        }
        .meta-label {
            font-weight: bold;
            color: #444;
            width: 18%;
        }
        .meta-val {
            font-weight: 600;
            color: #111;
            width: 32%;
        }

        /* Summary Stats Cards */
        .summary-container {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }
        .summary-card .num {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }
        .summary-card .lbl {
            font-size: 9.5px;
            color: #4b5563;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px 6px;
            font-size: 10px;
            vertical-align: middle;
        }
        .report-table th {
            background-color: #f3f4f6;
            font-size: 9.5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #111;
            text-align: center;
        }
        .report-table td.align-center {
            text-align: center;
        }
        .report-table td.align-left {
            text-align: left;
        }
        .badge-co {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-batch {
            display: inline-block;
            background: #f1f5f9;
            color: #334155;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-status {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }

        .footer-signatures {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 50px;
            font-weight: bold;
            font-size: 11px;
            color: #222;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .summary-card {
                background-color: #f9fafb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .report-table th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-co {
                background: #e0e7ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-status {
                background: #dcfce7 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 16px;
        }
        .print-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .print-btn:hover {
            background-color: #1d4ed8;
        }
        .close-btn {
            background-color: #64748b;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }
        .close-btn:hover {
            background-color: #475569;
        }
    </style>
</head>
<body>

    <div class="no-print action-bar">
        <button class="print-btn" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Report
        </button>
        <button class="close-btn" onclick="window.close()">Close</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>Practical Experiments Conducted &amp; Session Log Report (Revision 2021)</h3>
    </div>

    <table class="meta-info">
        <tr>
            <td class="meta-label">Batch / Class:</td>
            <td class="meta-val">{{ $cleanedBatch }}</td>
            <td class="meta-label">Semester:</td>
            <td class="meta-val">Semester {{ $batchSubject->semester ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Course / Subject:</td>
            <td class="meta-val">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</td>
            <td class="meta-label">Report Date:</td>
            <td class="meta-val">{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="summary-container">
        <div class="summary-card">
            <div class="num">{{ $conductedCount }}</div>
            <div class="lbl">Experiments Conducted</div>
        </div>
        <div class="summary-card">
            <div class="num">{{ $totalExperiments }}</div>
            <div class="lbl">Total Syllabus Experiments</div>
        </div>
        <div class="summary-card">
            <div class="num">{{ $coveragePct }}%</div>
            <div class="lbl">Syllabus Coverage</div>
        </div>
        <div class="summary-card">
            <div class="num">{{ $actualLabHours }} hrs</div>
            <div class="lbl">Actual Lab Hours Covered</div>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%">Sl.</th>
                <th style="width: 10%">Exp No</th>
                <th style="width: 32%">Title &amp; Topics Covered</th>
                <th style="width: 8%">CO</th>
                <th style="width: 13%">Conducted Date</th>
                <th style="width: 14%">Hours / Periods</th>
                <th style="width: 10%">Batch</th>
                <th style="width: 8%">Attendance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($conductedDetails as $idx => $exp)
                @php
                    $formattedDate = '-';
                    if (!empty($exp['date']) && $exp['date'] !== 'Conducted') {
                        $ts = strtotime($exp['date']);
                        $formattedDate = $ts ? date('d-m-Y', $ts) : $exp['date'];
                    } elseif ($exp['date'] === 'Conducted') {
                        $formattedDate = 'Conducted';
                    }
                @endphp
                <tr>
                    <td class="align-center" style="font-weight: bold;">{{ $idx + 1 }}</td>
                    <td class="align-center" style="font-weight: bold; color: #1e3a8a;">{{ $exp['experiment_no'] }}</td>
                    <td class="align-left" style="font-weight: 600; color: #111;">{{ $exp['title'] }}</td>
                    <td class="align-center">
                        <span class="badge-co">{{ $exp['co_tag'] ?? 'CO1' }}</span>
                    </td>
                    <td class="align-center" style="font-weight: bold; font-family: monospace; font-size: 10.5px;">
                        {{ $formattedDate }}
                    </td>
                    <td class="align-center" style="font-size: 9.5px;">
                        {{ $exp['hours_text'] }}
                    </td>
                    <td class="align-center">
                        <span class="badge-batch">{{ $exp['batch'] }}</span>
                    </td>
                    <td class="align-center" style="font-size: 9.5px;">
                        {{ $exp['present_count'] }}/{{ $exp['total_count'] }}<br>
                        <span style="color: #166534; font-weight: bold;">({{ $exp['attendance_pct'] }}%)</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="align-center" style="padding: 20px; color: #6b7280; font-style: italic;">
                        No conducted practical experiments or sessions logged yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>Name &amp; Signature of Faculty In-Charge</td>
            <td>Name &amp; Signature of Lab Coordinator</td>
            <td>Head of Department</td>
        </tr>
    </table>

</body>
</html>
