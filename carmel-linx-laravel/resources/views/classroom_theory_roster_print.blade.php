<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Roster & Attainment Register - {{ $subject->subject_code }}</title>
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

        .roster-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8.5px;
        }

        .roster-table th,
        .roster-table td {
            border: 1px solid #64748b;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .roster-table thead {
            display: table-header-group;
        }

        .roster-table tr {
            page-break-inside: avoid;
        }

        .roster-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            font-size: 8px;
            text-transform: uppercase;
            line-height: 1.15;
        }

        .roster-table th.grp-hdr {
            background-color: #e2e8f0;
            font-size: 8.5px;
            letter-spacing: 0.3px;
        }

        .roster-table td.name-cell {
            text-align: left;
            padding-left: 4px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }

        .roster-table td.reg-cell {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        .roster-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .pct-high {
            font-weight: 700;
            color: #047857;
        }

        .pct-short {
            font-weight: 800;
            color: #b91c1c;
            background-color: #fee2e2 !important;
        }

        .status-badge {
            font-size: 7.5px;
            font-weight: 800;
            padding: 1px 3px;
            border-radius: 2px;
            display: inline-block;
        }

        .status-eligible {
            background: #d1fae5;
            color: #065f46;
        }

        .status-shortage {
            background: #fee2e2;
            color: #991b1b;
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
            Print A4 Register
        </button>
        <button onclick="window.close()" class="btn btn-close">Close</button>
    </div>

    <!-- College Header -->
    <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Government Aided Polytechnic College • Approved by AICTE • Affiliated to SBTE Kerala</div>
        <div class="report-badge">Consolidated Theory Class Roster & Attainment Register</div>
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
            <td class="lbl">Conducted Hours:</td>
            <td class="val"><strong>{{ $totalConductedHours }}</strong> Hours (Total Sessions)</td>
            <td class="lbl">Students Enrolled:</td>
            <td class="val"><strong>{{ $students->count() }}</strong> Candidates</td>
        </tr>
    </table>

    <!-- Main Consolidated Register Table -->
    <table class="roster-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">Roll</th>
                <th rowspan="2" style="width: 110px;">Student Name</th>
                <th rowspan="2" style="width: 65px;">SBTE Reg No</th>
                <th colspan="4" class="grp-hdr" style="background:#e0e7ff;">Attendance Hours</th>
                <th colspan="4" class="grp-hdr" style="background:#fef3c7;">Assignment Marks (Max 20)</th>
                <th colspan="4" class="grp-hdr" style="background:#e0f2fe;">Summative Tests (Max 20)</th>
                <th colspan="2" class="grp-hdr" style="background:#dcfce7;">Attainment & Status</th>
            </tr>
            <tr>
                <!-- Attendance Sub-headers -->
                <th style="width: 24px; background:#eef2ff;">Tot</th>
                <th style="width: 24px; background:#eef2ff;">Pres</th>
                <th style="width: 24px; background:#eef2ff;">Abs</th>
                <th style="width: 28px; background:#eef2ff;">Attn %</th>

                <!-- Assignment Sub-headers -->
                <th style="width: 22px; background:#fffbeb;">CO1</th>
                <th style="width: 22px; background:#fffbeb;">CO2</th>
                <th style="width: 22px; background:#fffbeb;">CO3</th>
                <th style="width: 22px; background:#fffbeb;">CO4</th>

                <!-- Summative Tests Sub-headers -->
                <th style="width: 22px; background:#f0f9ff;">T1</th>
                <th style="width: 22px; background:#f0f9ff;">T2</th>
                <th style="width: 22px; background:#f0f9ff;">T3</th>
                <th style="width: 22px; background:#f0f9ff;">T4</th>

                <!-- Attainment & Status Sub-headers -->
                <th style="width: 32px; background:#f0fdf4;">CO Lvl</th>
                <th style="width: 48px; background:#f0fdf4;">CIE Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $stud)
            <tr>
                <td style="font-weight:700;">{{ $stud->roll_no ?: '-' }}</td>
                <td class="name-cell" title="{{ $stud->name }}">{{ $stud->name }}</td>
                <td class="reg-cell">{{ $stud->sbte_reg_no ?: $stud->reg_no }}</td>

                <!-- Attendance -->
                <td>{{ $stud->total_hours }}</td>
                <td style="font-weight:700; color:#047857;">{{ $stud->present_hours }}</td>
                <td style="color:#b91c1c;">{{ $stud->absent_hours }}</td>
                <td class="{{ $stud->att_percent < 75 ? 'pct-short' : 'pct-high' }}">
                    {{ number_format($stud->att_percent, 1) }}%
                </td>

                <!-- Assignment Marks -->
                <td>{{ $stud->assignments['CO1'] ?? '-' }}</td>
                <td>{{ $stud->assignments['CO2'] ?? '-' }}</td>
                <td>{{ $stud->assignments['CO3'] ?? '-' }}</td>
                <td>{{ $stud->assignments['CO4'] ?? '-' }}</td>

                <!-- Summative / Series Test Marks -->
                <td>{{ $stud->tests['T1'] ?? '-' }}</td>
                <td>{{ $stud->tests['T2'] ?? '-' }}</td>
                <td>{{ $stud->tests['T3'] ?? '-' }}</td>
                <td>{{ $stud->tests['T4'] ?? '-' }}</td>

                <!-- Attainment & Status -->
                <td style="font-weight:800; color:#1e3a8a;">
                    {{ $stud->attainment_level ?: '-' }}
                </td>
                <td>
                    @if($stud->att_percent >= 75)
                        <span class="status-badge status-eligible">ELIGIBLE</span>
                    @else
                        <span class="status-badge status-shortage">SHORTAGE</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="17" style="padding: 16px; text-align: center; color: #64748b;">
                    No enrolled students found for this classroom.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Consolidated Summary Card -->
    <div class="summary-card">
        <div class="summary-title">Class Roster & Outcome Attainment Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="val">{{ $students->count() }}</div>
                <div class="desc">Total Enrolled</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ $totalConductedHours }}</div>
                <div class="desc">Hours Conducted</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ number_format($overallAvgAttn, 1) }}%</div>
                <div class="desc">Avg Attendance</div>
            </div>
            <div class="stat-box">
                <div class="val" style="color: {{ $shortageCount > 0 ? '#b91c1c' : '#047857' }};">
                    {{ $eligibleCount }} / {{ $students->count() }}
                </div>
                <div class="desc">Eligible ({{ $shortageCount }} Shortage)</div>
            </div>
        </div>

        @if(!empty($coAttainmentSummary))
        <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed #cbd5e1; font-size: 8px; display: flex; justify-content: space-between; color: #334155;">
            <div><strong>Direct CO Attainment:</strong></div>
            <div>CO1: <strong>{{ $coAttainmentSummary['CO1'] ?? 'Level 3' }}</strong></div>
            <div>CO2: <strong>{{ $coAttainmentSummary['CO2'] ?? 'Level 3' }}</strong></div>
            <div>CO3: <strong>{{ $coAttainmentSummary['CO3'] ?? 'Level 3' }}</strong></div>
            <div>CO4: <strong>{{ $coAttainmentSummary['CO4'] ?? 'Level 3' }}</strong></div>
            <div>Program Outcome Mapping Level: <strong>{{ $poLevelAverage ?? 'Level 2.8' }} / 3.0</strong></div>
        </div>
        @endif
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
