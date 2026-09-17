<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Experiments List - {{ $batchSubject->subject_code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm 12mm 10mm;
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
            border-bottom: 2px solid #047857;
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
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 2px 14px;
            border-radius: 4px;
            margin-top: 4px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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

        .exp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9.5px;
        }

        .exp-table th,
        .exp-table td {
            border: 1px solid #64748b;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .exp-table thead {
            display: table-header-group;
        }

        .exp-table tr {
            page-break-inside: avoid;
        }

        .exp-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            font-size: 9px;
            text-transform: uppercase;
            text-align: center;
        }

        .exp-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }

        .co-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 700;
            font-size: 9px;
            border: 1px solid #bae6fd;
        }

        .sig-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 30px;
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
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
        }

        .footer-note {
            margin-top: 14px;
            padding: 6px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.4;
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
        <button onclick="window.print()" class="btn btn-print">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Experiment List (A4)
        </button>
        <button onclick="window.close()" class="btn btn-close">Close</button>
    </div>

    <!-- College Header -->
    <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Government Aided Polytechnic College • Approved by AICTE • Affiliated to SBTE Kerala</div>
        <div class="report-badge">List of Practical Experiments &amp; Lab Sessions — Revision 2026 Practicum</div>
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
            <td class="lbl">Total Experiments:</td>
            <td class="val"><strong>{{ count($experiments) }}</strong> Experiments Configured</td>
            <td class="lbl">Practical Hours:</td>
            <td class="val"><strong>{{ $totalPracticalHours }}</strong> Allocated Lab Hours</td>
        </tr>
        <tr>
            <td class="lbl">Date Generated:</td>
            <td class="val"><strong>{{ date('d/m/Y') }}</strong></td>
            <td class="lbl">Academic Scheme:</td>
            <td class="val">Curriculum Revision 2026 Practicum</td>
        </tr>
    </table>

    <!-- Experiments Table -->
    <table class="exp-table">
        <thead>
            <tr>
                <th style="width: 30px;">Sl</th>
                <th style="width: 75px;">Session Code</th>
                <th style="width: 75px;">Code</th>
                <th>Experiment Title / Description</th>
                <th style="width: 65px;">Mapped CO</th>
                <th style="width: 75px;">Duration</th>
            </tr>
        </thead>
        <tbody>
            @forelse($experiments as $idx => $exp)
            <tr>
                <td class="text-center" style="font-weight: 700;">{{ $idx + 1 }}</td>
                <td class="text-center font-mono" style="color: #047857; font-weight: 700;">
                    {{ $exp['session_code'] ?? ('Sess ' . ($idx + 1)) }}
                </td>
                <td class="text-center font-mono" style="color: #0284c7; font-weight: 700;">
                    {{ $exp['code'] ?? ($exp['experiment_no'] ?? ('EXP-' . sprintf('%02d', $idx + 1))) }}
                </td>
                <td style="font-weight: 600; color: #1e293b;">
                    {{ $exp['title'] ?? 'Experiment' }}
                </td>
                <td class="text-center">
                    <span class="co-badge">{{ $exp['co_id'] ?? 'CO1' }}</span>
                </td>
                <td class="text-center font-mono" style="font-weight: 700;">
                    {{ $exp['hours'] ?? 3 }} Hours
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 16px; color: #64748b;">
                    No experiments configured for this practicum course yet.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(count($experiments) > 0)
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: 800;">
                <td colspan="3" class="text-center">TOTAL SUMMARY</td>
                <td>{{ count($experiments) }} Lab Experiments / Practicum Modules</td>
                <td class="text-center">CO1 - CO4</td>
                <td class="text-center font-mono">{{ $totalPracticalHours }} Hours</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer-note">
        <strong>Curricular Compliance:</strong> Practical experiments are structured into modular lab sessions under SBTE Revision 2026 guidelines. Continuous evaluation (CE) marks out of 10 marks are mapped from rubric evaluations across these experiments.
    </div>

    <!-- Signatures Grid -->
    <div class="sig-grid">
        <div class="sig-box">
            <div class="sig-name">{{ $lecturerName }}</div>
            <div class="sig-title">Faculty In-Charge</div>
        </div>
        <div class="sig-box">
            <div class="sig-name">&nbsp;</div>
            <div class="sig-title">Lab / Workshop Superintendent</div>
        </div>
        <div class="sig-box">
            <div class="sig-name">{{ $hod->name ?? 'Head of Department' }}</div>
            <div class="sig-title">Head of Department (HOD)</div>
        </div>
    </div>

</body>
</html>
