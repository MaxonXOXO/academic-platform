<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIE Register - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 10mm 12mm;
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
            max-width: 1050px;
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
            max-width: 1050px;
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
            font-size: 18px;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header h2 {
            margin: 2px 0 0 0;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 4px 0 0 0;
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }
        .header h4 {
            margin: 2px 0 0 0;
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .meta-table td {
            padding: 4px 6px;
            font-size: 10.5px;
            border: 1px solid #e2e8f0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #475569;
            padding: 4px 3px;
            text-align: center;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .data-table td.text-left { text-align: left; padding-left: 5px; }
        .data-table tr:nth-child(even) td { background-color: #fafafa; }
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
                window.location.href = "{{ url('/r26/classroom/practicum/' . $batchSubject->id . '?tab=cie') }}";
            }
        }
        function handlePrintClose() {
            window.close();
            setTimeout(function() {
                window.location.href = "{{ url('/r26/classroom/practicum/' . $batchSubject->id . '?tab=cie') }}";
            }, 300);
        }
    </script>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print CIE Register</button>
        <button class="back-btn" onclick="handlePrintBack()">⬅ Back to Classroom</button>
        <button class="close-btn" onclick="handlePrintClose()">✖ Close Window</button>
    </div>

    <div class="report-container">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>{{ $departmentFullName ?? 'DEPARTMENT OF POLYTECHNIC STUDIES' }}</h2>
            <h3>Continuous Internal Assessment (CIE) Register - Revision 2026</h3>
            <h4>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</h4>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 25%;"><strong>Department:</strong> {{ $classroom->department ?? $classroom->branch ?? 'General' }}</td>
                <td style="width: 25%;"><strong>Semester:</strong> Semester {{ $batchSubject->semester ?? 1 }}</td>
                <td style="width: 25%;"><strong>Assessment Year:</strong> {{ $assessmentYear ?? '2026 - 2027' }}</td>
                <td style="width: 25%;"><strong>Report Date:</strong> {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="width: 25%;"><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
                <td style="width: 25%;"><strong>Course Type:</strong> Practicum Course (Revision 2026)</td>
                <td style="width: 25%;"><strong>Total CIE:</strong> 40 Max Marks</td>
                <td style="width: 25%;"><strong>ESE Max Marks:</strong> {{ $practicumCourseFile->ese_marks ?? 60 }} Marks</td>
            </tr>
            <tr>
                <td colspan="4"><strong>Course Faculty:</strong> {{ $assignedStaff->pluck('name')->implode(', ') ?: 'Course Faculty' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 24px;">#</th>
                    <th rowspan="2" style="width: 32px;">Roll No</th>
                    <th rowspan="2" style="width: 85px;">Reg No / PRN</th>
                    <th rowspan="2">Student Name</th>
                    <th colspan="2">Attendance</th>
                    <th colspan="5">Self-Learning Continuous Assessment (CA1)</th>
                    <th rowspan="2" style="width: 44px; background: #e0f2fe;">CE Lab<br>(10M)</th>
                    <th rowspan="2" style="width: 44px; background: #e0e7ff;">Th Tests<br>CA4/5 (10M)</th>
                    <th rowspan="2" style="width: 44px; background: #ede9fe;">Pr Tests<br>CA2/3 (10M)</th>
                    <th rowspan="2" style="width: 48px; background: #dcfce7; font-weight: 800;">Total CIE<br>(40M)</th>
                    <th colspan="2">ESE</th>
                    <th rowspan="2" style="width: 44px; background: #fef3c7;">Grand Total</th>
                </tr>
                <tr>
                    <th style="width: 38px;">%</th>
                    <th style="width: 32px;">M (5)</th>
                    <th style="width: 28px;">M1 (10)</th>
                    <th style="width: 28px;">M2 (10)</th>
                    <th style="width: 28px;">M3 (10)</th>
                    <th style="width: 28px;">M4 (10)</th>
                    <th style="width: 36px; background: #fef3c7;">SL (5)</th>
                    <th style="width: 36px;">Marks</th>
                    <th style="width: 30px;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentResults as $idx => $st)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $st['roll_no'] ?: '-' }}</strong></td>
                    <td style="font-family: monospace;">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                    <td class="text-left"><strong>{{ $st['name'] }}</strong></td>
                    <td>{{ $st['att_percentage'] !== null ? number_format($st['att_percentage'], 1) . '%' : '-' }}</td>
                    <td><strong>{{ number_format($st['att_marks'] ?? 0, 1) }}</strong></td>
                    <td>{{ $st['m1'] !== null ? number_format($st['m1'], 1) : '-' }}</td>
                    <td>{{ $st['m2'] !== null ? number_format($st['m2'], 1) : '-' }}</td>
                    <td>{{ $st['m3'] !== null ? number_format($st['m3'], 1) : '-' }}</td>
                    <td>{{ $st['m4'] !== null ? number_format($st['m4'], 1) : '-' }}</td>
                    <td style="background: #fef3c7; font-weight: bold;">{{ number_format($st['sl_marks'] ?? 0, 1) }}</td>
                    <td style="background: #f0f9ff; font-weight: 600;">{{ number_format($st['continuous_eval_marks'] ?? 0, 1) }}</td>
                    <td style="background: #eef2ff; font-weight: 600;">{{ number_format($st['series_theory_marks'] ?? 0, 1) }}</td>
                    <td style="background: #faf5ff; font-weight: 600;">{{ number_format($st['series_practical_marks'] ?? 0, 1) }}</td>
                    <td style="background: #dcfce7; font-weight: 800; font-size: 11px; color: #166534;">{{ number_format(round($st['total_cia_marks'] ?? 0)) }}</td>
                    <td>{{ number_format($st['ese_theory'] ?? 0, 1) }}</td>
                    <td><strong>{{ $st['ese_theory_grade'] ?: '-' }}</strong></td>
                    <td style="background: #fef3c7; font-weight: 800;">{{ number_format(round($st['total_course_marks'] ?? 0)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="18" style="text-align: center; padding: 20px; color: #64748b;">No students found enrolled in this classroom.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signatures">
            <div class="sig-box">Course Faculty Signature</div>
            <div class="sig-box">Head of Department (HOD)</div>
            <div class="sig-box">Principal / Academic Head</div>
        </div>
    </div>

</body>
</html>
