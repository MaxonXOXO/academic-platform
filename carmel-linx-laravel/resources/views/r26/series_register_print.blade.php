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
            padding: 4px 5px;
            text-align: center;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            font-size: 9.5px;
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
                window.location.href = "{{ url('/r26/classroom/theory/' . $batchSubject->id . '?tab=series') }}";
            }
        }
        function handlePrintClose() {
            window.close();
            setTimeout(function() {
                window.location.href = "{{ url('/r26/classroom/theory/' . $batchSubject->id . '?tab=series') }}";
            }, 300);
        }
    </script>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Series Register</button>
        <button class="back-btn" onclick="handlePrintBack()">⬅ Back to Classroom</button>
        <button class="close-btn" onclick="handlePrintClose()">✖ Close Window</button>
    </div>

    <div class="report-container">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>{{ $departmentFullName ?? 'DEPARTMENT OF POLYTECHNIC STUDIES' }}</h2>
            <h3>Continuous Assessment (CA5 &amp; CA6) - Series Examination Register</h3>
            <h4>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</h4>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 25%;"><strong>Department:</strong> {{ $classroom->department ?? $classroom->branch ?? 'General' }}</td>
                <td style="width: 25%;"><strong>Semester:</strong> Semester {{ $batchSubject->semester ?? 1 }}</td>
                <td style="width: 25%;"><strong>Academic Year:</strong> {{ $assessmentYear ?? '2026 - 2027' }}</td>
                <td style="width: 25%;"><strong>Report Date:</strong> {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 25%;"><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
                <td style="width: 25%;"><strong>Exam Mode:</strong> Series 1 (CO1+2) &bull; Series 2 (CO3+4)</td>
                <td style="width: 25%;"><strong>Raw Total:</strong> 50 Marks Each</td>
                <td style="width: 25%;"><strong>Scaled to CIE:</strong> 20 Max Marks</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Course Faculty:</strong> {{ $assignedStaff->pluck('name')->implode(', ') ?: 'Course Faculty' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 24px;">#</th>
                    <th rowspan="2" style="width: 32px;">Roll</th>
                    <th rowspan="2" style="width: 90px;">Reg No / PRN</th>
                    <th rowspan="2">Student Name</th>
                    <th colspan="4" style="background: #e0f2fe;">Series Exam 1 (Max 50M)</th>
                    <th colspan="4" style="background: #f0fdf4;">Series Exam 2 (Max 50M)</th>
                    <th rowspan="2" style="width: 50px; background: #fef3c7;">Combined<br>Series (20M)</th>
                </tr>
                <tr>
                    <th style="width: 35px;">CO1 (25)</th>
                    <th style="width: 35px;">CO2 (25)</th>
                    <th style="width: 38px;">Total (50)</th>
                    <th style="width: 35px; background: #e0f2fe;">S1 (20)</th>
                    <th style="width: 35px;">CO3 (25)</th>
                    <th style="width: 35px;">CO4 (25)</th>
                    <th style="width: 38px;">Total (50)</th>
                    <th style="width: 35px; background: #f0fdf4;">S2 (20)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $st)
                @php
                    $ev = $evaluations->get($st->reg_no);
                    $s1Absent = $ev ? (bool)$ev->series_1_absent : false;
                    $s2Absent = $ev ? (bool)$ev->series_2_absent : false;
                    $co1 = $ev && $ev->series_1_co1_25 !== null ? number_format($ev->series_1_co1_25, 1) : '-';
                    $co2 = $ev && $ev->series_1_co2_25 !== null ? number_format($ev->series_1_co2_25, 1) : '-';
                    $s1_50 = $ev ? ($s1Absent ? 'AB' : ($ev->series_1_50 !== null ? number_format($ev->series_1_50, 1) : '-')) : '-';
                    $s1_20 = $ev ? ($s1Absent ? 'AB' : ($ev->series_1_50 !== null ? number_format((($ev->series_1_50 / 50.0) * 20.0), 1) : '-')) : '-';

                    $co3 = $ev && $ev->series_2_co3_25 !== null ? number_format($ev->series_2_co3_25, 1) : '-';
                    $co4 = $ev && $ev->series_2_co4_25 !== null ? number_format($ev->series_2_co4_25, 1) : '-';
                    $s2_50 = $ev ? ($s2Absent ? 'AB' : ($ev->series_2_50 !== null ? number_format($ev->series_2_50, 1) : '-')) : '-';
                    $s2_20 = $ev ? ($s2Absent ? 'AB' : ($ev->series_2_50 !== null ? number_format((($ev->series_2_50 / 50.0) * 20.0), 1) : '-')) : '-';

                    $comb20 = $ev ? number_format($ev->series_20, 1) : '0.0';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: 700;">{{ $st->roll_no ?? '-' }}</td>
                    <td style="font-family: monospace; font-size: 9.5px;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
                    <td class="text-left" style="font-weight: 600;">{{ $st->name }}</td>
                    <td>{{ $co1 }}</td>
                    <td>{{ $co2 }}</td>
                    <td style="font-weight: 700;">{{ $s1_50 }}</td>
                    <td style="font-weight: 700; background: #e0f2fe; color: #0284c7;">{{ $s1_20 }}</td>
                    <td>{{ $co3 }}</td>
                    <td>{{ $co4 }}</td>
                    <td style="font-weight: 700;">{{ $s2_50 }}</td>
                    <td style="font-weight: 700; background: #f0fdf4; color: #16a34a;">{{ $s2_20 }}</td>
                    <td style="font-weight: 800; background: #fef3c7; color: #b45309;">{{ $comb20 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="padding: 16px; color: #64748b;">No students found for this classroom.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 10px; font-size: 9px; color: #64748b; font-style: italic;">
            Note: Series 1 covers CO1 &amp; CO2 (50 Marks, 2 Hours). Series 2 covers CO3 &amp; CO4 (50 Marks, 2 Hours). The combined score is scaled to 20 Marks for CIE.
        </div>

        <div class="signatures">
            <div class="sig-box">
                Signature of Course Faculty
            </div>
            <div class="sig-box">
                Signature of HOD
            </div>
            <div class="sig-box">
                Signature of Principal
            </div>
        </div>
    </div>

</body>
</html>
