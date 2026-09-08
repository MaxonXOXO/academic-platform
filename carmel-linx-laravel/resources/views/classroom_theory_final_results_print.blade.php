<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Final CIE & Results Report - {{ $subject->subject_code }}</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --border: #94a3b8;
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.4;
            font-size: 11px;
        }

        .a4-container {
            width: 297mm; /* Landscape for comprehensive register */
            min-height: 210mm;
            padding: 12mm 15mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.15);
            z-index: 50;
            display: flex;
            gap: 10px;
            border: 1px solid #e2e8f0;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-close {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .college-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sub-header {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-top: 1px;
        }

        .report-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 5px;
            text-transform: uppercase;
            background: #f1f5f9;
            display: inline-block;
            padding: 3px 16px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 12px;
            background: var(--bg-light);
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .meta-item {
            display: flex;
            gap: 6px;
        }

        .meta-label {
            font-weight: 700;
            color: var(--text-muted);
        }

        .meta-value {
            font-weight: 600;
            color: var(--text-main);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 16px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: center;
        }

        th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.3px;
        }

        .group-header {
            background-color: #f1f5f9;
            font-weight: 800;
            font-size: 10px;
        }

        .student-name {
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .student-reg {
            font-family: monospace;
            font-weight: 700;
            font-size: 10px;
        }

        .total-cie-cell {
            font-weight: 800;
            color: #1e3a8a;
            background-color: #eff6ff;
            font-size: 11px;
        }

        .badge-eligible {
            color: #059669;
            font-weight: 700;
            font-size: 9px;
        }

        .badge-shortage {
            color: #dc2626;
            font-weight: 700;
            font-size: 9px;
        }

        .footer {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            padding: 0 30px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 180px;
            border-top: 1.5px solid #475569;
            margin-bottom: 4px;
        }

        .signature-title {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        @media print {
            body {
                background: white;
            }
            .a4-container {
                width: 100%;
                min-height: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            .print-controls {
                display: none;
            }
            @page {
                size: A4 landscape;
                margin: 10mm 10mm 10mm 10mm;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            Print Marksheet
        </button>
        <button class="btn-close" onclick="window.close()">Close</button>
    </div>

    <div class="a4-container">
        <div class="header">
            <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
            <div class="sub-header">Department of {{ $fullDepartment }}</div>
            <div class="report-title">Continuous Internal Evaluation (CIE) & Final Result Register - Revision 2021</div>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">Course:</span>
                <span class="meta-value">{{ $subject->subject_name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Code:</span>
                <span class="meta-value">{{ $subject->subject_code }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Semester:</span>
                <span class="meta-value">Semester {{ $subject->semester }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Class Batch:</span>
                <span class="meta-value">{{ $cleanedBatch }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Faculty:</span>
                <span class="meta-value">{{ $lecturerName }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Enrolled:</span>
                <span class="meta-value">{{ $totalStudents }} Students</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Revision:</span>
                <span class="meta-value">Revision 2021</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Report Date:</span>
                <span class="meta-value">{{ $currentDate }}</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 32px;">Sl</th>
                    <th rowspan="2" style="width: 40px;">Roll</th>
                    <th rowspan="2" style="width: 85px;">Reg No</th>
                    <th rowspan="2">Student Name</th>
                    <th colspan="5" class="group-header">Continuous Assignments (Max 20)</th>
                    <th colspan="5" class="group-header">Summative Written Tests (Max 20)</th>
                    <th colspan="2" class="group-header">Attendance</th>
                    <th rowspan="2" style="width: 55px;" class="total-cie-cell">Total CIE (50)</th>
                    <th rowspan="2" style="width: 70px;">Eligibility Status</th>
                </tr>
                <tr>
                    <th style="width: 34px;">CO1</th>
                    <th style="width: 34px;">CO2</th>
                    <th style="width: 34px;">CO3</th>
                    <th style="width: 34px;">CO4</th>
                    <th style="width: 38px; font-weight: bold; background: #e2e8f0;">Avg</th>
                    <th style="width: 34px;">CO1</th>
                    <th style="width: 34px;">CO2</th>
                    <th style="width: 34px;">CO3</th>
                    <th style="width: 34px;">CO4</th>
                    <th style="width: 38px; font-weight: bold; background: #e2e8f0;">Avg</th>
                    <th style="width: 45px;">%</th>
                    <th style="width: 38px;">Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $s->roll_no ?? ($index + 1) }}</strong></td>
                        <td class="student-reg">{{ $s->reg_no }}</td>
                        <td class="student-name">{{ $s->name }}</td>
                        <td>{{ $s->co_assign['CO1'] ?? '-' }}</td>
                        <td>{{ $s->co_assign['CO2'] ?? '-' }}</td>
                        <td>{{ $s->co_assign['CO3'] ?? '-' }}</td>
                        <td>{{ $s->co_assign['CO4'] ?? '-' }}</td>
                        <td style="font-weight: 700; background: #f8fafc;">{{ $s->assign_avg }}</td>
                        <td>{{ $s->co_summative['CO1'] ?? '-' }}</td>
                        <td>{{ $s->co_summative['CO2'] ?? '-' }}</td>
                        <td>{{ $s->co_summative['CO3'] ?? '-' }}</td>
                        <td>{{ $s->co_summative['CO4'] ?? '-' }}</td>
                        <td style="font-weight: 700; background: #f8fafc;">{{ $s->summ_avg }}</td>
                        <td>{{ $s->att_percent }}%</td>
                        <td style="font-weight: 700;">{{ $s->att_marks }}</td>
                        <td class="total-cie-cell">{{ $s->total_cie }}</td>
                        <td>
                            @if($s->status === 'ELIGIBLE')
                                <span class="badge-eligible">ELIGIBLE</span>
                            @else
                                <span class="badge-shortage">{{ $s->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" style="padding: 24px; text-align: center; color: #94a3b8;">
                            No students enrolled in this classroom batch.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">Faculty In-Charge</div>
                <div style="font-size: 10px; font-weight: 600; margin-top: 2px;">{{ $lecturerName }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">Head of Department</div>
                <div style="font-size: 10px; font-weight: 600; margin-top: 2px;">Dept. of {{ $fullDepartment }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">Principal</div>
                <div style="font-size: 10px; font-weight: 600; margin-top: 2px;">Carmel Polytechnic College</div>
            </div>
        </div>
    </div>

</body>
</html>
