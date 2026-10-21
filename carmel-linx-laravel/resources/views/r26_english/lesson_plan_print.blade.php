<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Plan (1001) - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 14mm 12mm 14mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 16px;
            color: #0f172a;
            background: #f1f5f9;
            font-size: 10px;
            line-height: 1.35;
        }
        .report-container {
            max-width: 1180px;
            margin: 0 auto;
            background: #ffffff;
            padding: 22px 26px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .no-print {
            text-align: center;
            padding: 10px;
            background: #1e3a5f;
            border-radius: 8px;
            margin-bottom: 16px;
            max-width: 1180px;
            margin-left: auto;
            margin-right: auto;
        }
        .no-print button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 22px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 5px;
        }
        .no-print button.back-btn { background: #dc2626; }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 8px;
        }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; font-weight: 800; color: #1e3a5f; }
        .header h2 { margin: 3px 0 0 0; font-size: 12.5px; color: #334155; font-weight: 700; text-transform: uppercase; }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #ffffff;
            background: #1e3a5f;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
        }
        table.plan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 8px;
            border: 1.5px solid #1e3a5f;
        }
        table.plan-table th, table.plan-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            vertical-align: middle;
        }
        table.plan-table th {
            background-color: #1e3a5f;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 9.5px;
        }
        .text-center { text-align: center; }
        .signatures {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .sig-box {
            text-align: center;
            width: 210px;
            border-top: 1.5px solid #1e3a5f;
            padding-top: 6px;
            font-weight: 700;
            color: #1e3a5f;
        }
        @media print {
            body { padding: 0; background: #fff; }
            .report-container { border: none; padding: 0; max-width: 100%; }
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
        <button onclick="window.print()">🖨️ Print Lesson Plan (1001)</button>
        <button class="back-btn" onclick="handlePrintBack()">⬅ Back to Classroom</button>
    </div>

    <div class="report-container">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>{{ $departmentFullName ?? 'DEPARTMENT OF ENGINEERING' }}</h2>
            <h3>Course Lesson Plan &amp; Execution Log (Revision 2026) — {{ $batchSubject->subject_code }}: {{ $batchSubject->subject_name }}</h3>
        </div>

        <table class="plan-table">
            <thead>
                <tr>
                    <th style="width: 35px;">#</th>
                    <th style="width: 85px;">Proposed Date</th>
                    <th style="width: 85px;">Actual Date</th>
                    <th>Topic / Practicum Activity / Assessment</th>
                    <th style="width: 55px;">Module</th>
                    <th style="width: 55px;">CO</th>
                    <th style="width: 45px;">Hours</th>
                    <th style="width: 165px;">Mode / Pedagogy</th>
                    <th style="width: 75px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lessonPlans as $lp)
                <tr>
                    <td class="text-center" style="font-weight: 700;">{{ $lp->day_no }}</td>
                    <td class="text-center">{{ $lp->proposed_date ? date('d-m-Y', strtotime($lp->proposed_date)) : '-' }}</td>
                    <td class="text-center">{{ $lp->actual_date ? date('d-m-Y', strtotime($lp->actual_date)) : '-' }}</td>
                    <td>{{ $lp->topic_content }}</td>
                    <td class="text-center" style="font-weight: 700;">{{ $lp->module_id }}</td>
                    <td class="text-center" style="font-weight: 700;">{{ $lp->co_tag }}</td>
                    <td class="text-center">{{ $lp->allocated_hours }}</td>
                    <td class="text-center">{{ $lp->pedagogy }}</td>
                    <td class="text-center" style="font-weight: 700;">{{ $lp->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-box">Course Faculty ({{ $assignedStaff->pluck('name')->implode(', ') ?: 'Faculty' }})</div>
            <div class="sig-box">Head of Department ({{ $hod->name ?? 'HOD' }})</div>
            <div class="sig-box">Principal</div>
        </div>
    </div>
</body>
</html>
