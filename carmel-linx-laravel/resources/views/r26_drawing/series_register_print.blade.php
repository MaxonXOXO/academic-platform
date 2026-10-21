<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series Exam Register - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 12mm 15mm 12mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #0f172a;
            background: #f8fafc;
            font-size: 11px;
            line-height: 1.35;
        }
        .report-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .no-print {
            text-align: center;
            padding: 10px;
            background: #1e293b;
            border-radius: 6px;
            margin-bottom: 16px;
            max-width: 900px;
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
            margin-bottom: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header h2 {
            margin: 3px 0 0 0;
            font-size: 13px;
            color: #0284c7;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h3 {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header h4 {
            margin: 6px 0 0 0;
            font-size: 11px;
            color: #ffffff;
            background: #0284c7;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 4px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .meta-table td {
            padding: 3px 6px;
        }
        .meta-table strong { color: #0f172a; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #475569;
            padding: 5px 6px;
            text-align: center;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            font-size: 10px;
        }
        .data-table td.text-left { text-align: left; }
        .signatures {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .sig-box {
            text-align: center;
            width: 200px;
            border-top: 1px dashed #475569;
            padding-top: 6px;
            font-size: 10px;
            font-weight: 600;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .report-container { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
    <script>
        function handlePrintBack() {
            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "{{ url('/r26/classroom/drawing/' . $batchSubject->id) }}";
            }
        }
        function handlePrintClose() {
            window.close();
            setTimeout(function() {
                window.location.href = "{{ url('/r26/classroom/drawing/' . $batchSubject->id) }}";
            }, 300);
        }
    </script>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Series Exam Register</button>
        <button class="back-btn" onclick="handlePrintBack()">⬅ Back to Classroom</button>
        <button class="close-btn" onclick="handlePrintClose()">✖ Close Window</button>
    </div>

    <div class="report-container">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>{{ $departmentFullName ?? 'DEPARTMENT OF CIVIL ENGINEERING' }}</h2>
            <h3>Series Examination Marks Register - Revision 2026</h3>
            <h4>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</h4>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 25%;"><strong>Department:</strong> {{ $departmentName ?? 'Civil Engineering' }}</td>
                <td style="width: 25%;"><strong>Semester:</strong> Semester {{ $semester ?? 1 }}</td>
                <td style="width: 25%;"><strong>Assessment Year:</strong> {{ $assessmentYear ?? '2026 - 2027' }}</td>
                <td style="width: 25%;"><strong>Report Date:</strong> {{ $reportDate ?? date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 25%;"><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
                <td style="width: 25%;"><strong>Series 1:</strong> Mod 1 &amp; 2 (CO1: 20M, CO2: 20M &rarr; 40M)</td>
                <td style="width: 25%;"><strong>Series 2:</strong> Mod 3 &amp; 4 (CO3: 20M, CO4: 20M &rarr; 40M)</td>
                <td style="width: 25%;"><strong>CIA Weightage:</strong> Series Comb = 20 Marks</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Course Faculty:</strong> {{ $assignedStaff->pluck('name')->implode(', ') ?: 'Course Incharge' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 26px;">#</th>
                    <th rowspan="2" style="width: 32px;">Roll No</th>
                    <th rowspan="2" style="width: 85px;">Reg No / PRN</th>
                    <th rowspan="2">Student Name</th>
                    <th colspan="4">Series Exam 1 (Mod 1 &amp; 2)</th>
                    <th colspan="4">Series Exam 2 (Mod 3 &amp; 4)</th>
                    <th rowspan="2" style="width: 55px; background: #e0e7ff;">Combined<br>CIA Series (20M)</th>
                </tr>
                <tr>
                    <th style="width: 40px;">CO1 (20)</th>
                    <th style="width: 40px;">CO2 (20)</th>
                    <th style="width: 40px;">Total (40)</th>
                    <th style="width: 40px; background: #fef3c7;">Conv (20)</th>
                    <th style="width: 40px;">CO3 (20)</th>
                    <th style="width: 40px;">CO4 (20)</th>
                    <th style="width: 40px;">Total (40)</th>
                    <th style="width: 40px; background: #fef3c7;">Conv (20)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $st)
                @php
                    $ev = $evaluations->get($st->reg_no);
                    $s1Absent = $ev ? (bool)$ev->series_1_absent : false;
                    $s2Absent = $ev ? (bool)$ev->series_2_absent : false;
                    $co1 = $ev && $ev->series_1_co1_20 !== null ? number_format($ev->series_1_co1_20, 1) : '-';
                    $co2 = $ev && $ev->series_1_co2_20 !== null ? number_format($ev->series_1_co2_20, 1) : '-';
                    $s1_40 = $ev ? ($s1Absent ? 'AB' : ($ev->series_1_40 !== null ? number_format($ev->series_1_40, 1) : '-')) : '-';
                    $s1_20 = $ev ? ($s1Absent ? 'AB' : ($ev->series_1_40 !== null ? number_format($ev->series_1_40 / 2.0, 1) : '-')) : '-';

                    $co3 = $ev && $ev->series_2_co3_20 !== null ? number_format($ev->series_2_co3_20, 1) : '-';
                    $co4 = $ev && $ev->series_2_co4_20 !== null ? number_format($ev->series_2_co4_20, 1) : '-';
                    $s2_40 = $ev ? ($s2Absent ? 'AB' : ($ev->series_2_40 !== null ? number_format($ev->series_2_40, 1) : '-')) : '-';
                    $s2_20 = $ev ? ($s2Absent ? 'AB' : ($ev->series_2_40 !== null ? number_format($ev->series_2_40 / 2.0, 1) : '-')) : '-';

                    $ser20 = $ev ? number_format($ev->series_20, 1) : '0.0';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: 700;">{{ $st->roll_no ?? '-' }}</td>
                    <td style="font-family: monospace; font-size: 9.5px;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
                    <td class="text-left" style="font-weight: 600;">{{ $st->name }}</td>
                    <td>{{ $co1 }}</td>
                    <td>{{ $co2 }}</td>
                    <td style="{{ $s1Absent ? 'color: #ef4444; font-weight: bold;' : 'font-weight: 600;' }}">{{ $s1_40 }}</td>
                    <td style="background: #fffbeb; font-weight: 600;">{{ $s1_20 }}</td>
                    <td>{{ $co3 }}</td>
                    <td>{{ $co4 }}</td>
                    <td style="{{ $s2Absent ? 'color: #ef4444; font-weight: bold;' : 'font-weight: 600;' }}">{{ $s2_40 }}</td>
                    <td style="background: #fffbeb; font-weight: 600;">{{ $s2_20 }}</td>
                    <td style="font-weight: 800; font-size: 11px; background: #e0e7ff; color: #1e3a8a;">{{ $ser20 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="padding: 16px; color: #64748b;">No students found for this classroom.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-box">
                Course Faculty / Incharge
            </div>
            <div class="sig-box">
                Head of Department
            </div>
            <div class="sig-box">
                Principal
            </div>
        </div>
    </div>

</body>
</html>

