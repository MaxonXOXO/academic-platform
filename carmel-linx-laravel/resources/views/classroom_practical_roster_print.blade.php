<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Lab Internal Mark Report - {{ $subject->subject_code }}</title>
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
            width: 15%;
        }

        .meta-table .val {
            color: #0f172a;
            font-weight: 600;
            width: 35%;
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
            padding: 3.5px 2px;
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
            padding-left: 5px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 135px;
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

        .font-mono {
            font-family: 'Courier New', Courier, monospace;
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
            gap: 12px;
        }

        .sig-block {
            text-align: center;
            font-size: 9px;
            flex: 1;
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
        <div class="report-badge">Consolidated Lab Internal Mark Report</div>
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
            <td class="val"><strong>{{ $facultyNames ?? ($subject->getAssignedFacultyNames() ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="lbl">Conducted Hours:</td>
            <td class="val"><strong>{{ $conductedHoursText }}</strong></td>
            <td class="lbl">Date of Preparation:</td>
            <td class="val"><strong>{{ $dateOfPreparation }}</strong> ({{ $students->count() }} Candidates)</td>
        </tr>
    </table>

    <!-- Main Consolidated Register Table -->
    <table class="roster-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 22px;">Roll</th>
                <th rowspan="2" style="width: 135px;">Student Name</th>
                <th rowspan="2" style="width: 70px;">SBTE Reg No</th>
                <th colspan="3" class="grp-hdr" style="background:#e0e7ff;">Attendance (Max 15)</th>
                <th colspan="2" class="grp-hdr" style="background:#fef3c7;">Formative Assessment</th>
                <th colspan="3" class="grp-hdr" style="background:#e0f2fe;">Summative Tests</th>
                <th rowspan="2" class="grp-hdr" style="width: 48px; background:#dcfce7; color:#065f46;">Final CIA<br>(75 Marks)</th>
            </tr>
            <tr>
                <!-- Attendance Sub-headers (No TOT, No ABS) -->
                <th style="width: 26px; background:#eef2ff;" title="Attended Sessions">Pres</th>
                <th style="width: 36px; background:#eef2ff;" title="Official TEAMS Attendance Percentage">Attn %</th>
                <th style="width: 34px; background:#eef2ff; font-weight: bold; color: #047857;" title="Attendance Marks out of 15">Mark<br>(15M)</th>

                <!-- Formative Assessment Sub-headers -->
                <th style="width: 44px; background:#fffbeb;" title="Continuous Lab Work (5 Rubrics, Max 37.5)">Lab Work<br>(37.5)</th>
                <th style="width: 44px; background:#fffbeb;" title="Open Ended / Micro Project (Max 7.5)">Open Ended<br>(7.5)</th>

                <!-- Summative Tests Sub-headers -->
                <th style="width: 40px; background:#f0f9ff;" title="Practical Series Test 1 (CO1 & CO2, Max 15)">Test 1<br>(CO1 & 2)</th>
                <th style="width: 40px; background:#f0f9ff;" title="Practical Series Test 2 (CO3 & CO4, Max 15)">Test 2<br>(CO3 & 4)</th>
                <th style="width: 38px; background:#f0f9ff; font-weight: bold;" title="Average of Series Tests (Max 15)">Test Avg<br>(15M)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $stud)
            <tr>
                <td style="font-weight:700;">{{ $stud->roll_no ?: '-' }}</td>
                <td class="name-cell" title="{{ $stud->name }}">{{ $stud->name }}</td>
                <td class="reg-cell">{{ !empty($stud->sbte_reg_no) ? $stud->sbte_reg_no : $stud->reg_no }}</td>

                <!-- Attendance (Max 15) -->
                <td style="font-weight:600; color:#047857;">{{ $stud->present_classes }}</td>
                <td class="{{ (float)($stud->attendance_percentage ?? 100) < 75 ? 'pct-short' : 'pct-high' }}">
                    {{ number_format($stud->attendance_percentage ?? 100, 1) }}%
                </td>
                <td style="font-weight:700; color:#047857; background:#f0fdf4;">
                    {{ number_format($stud->attendance_marks ?? 0, 1) }}
                </td>

                <!-- Formative Assessment -->
                <td class="font-mono">{{ number_format($stud->avg_lab_work ?? 0, 2) }}</td>
                <td class="font-mono">{{ number_format($stud->micro_project ?? 0, 1) }}</td>

                <!-- Summative Tests -->
                <td class="font-mono">{{ (isset($stud->tests['Test 1']['total']) && $stud->tests['Test 1']['total'] > 0) ? number_format($stud->tests['Test 1']['total'], 1) : '-' }}</td>
                <td class="font-mono">{{ (isset($stud->tests['Test 2']['total']) && $stud->tests['Test 2']['total'] > 0) ? number_format($stud->tests['Test 2']['total'], 1) : '-' }}</td>
                <td class="font-mono" style="font-weight:700; background:#f8fafc;">{{ (isset($stud->tests['average']) && $stud->tests['average'] > 0) ? number_format($stud->tests['average'], 2) : '-' }}</td>

                <!-- Internal Assessment (Final CIA 75) -->
                <td style="font-weight:800; font-size:11px; color:#0f766e; background:#f0fdfa;">
                    {{ round($stud->total_internal) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="padding: 16px; text-align: center; color: #64748b;">
                    No enrolled students found for this classroom.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Consolidated Summary Card -->
    <div class="summary-card">
        <div class="summary-title">Consolidated Lab Outcome Attainment & Attendance Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="val">{{ $students->count() }}</div>
                <div class="desc">Total Enrolled</div>
            </div>
            <div class="stat-box">
                <div class="val">{{ $conductedHoursSummary }}</div>
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
    @php
        $staffList = $assignedStaffList ?? ($subject->getAssignedFacultyList() ?? []);
    @endphp
    <div class="signatures">
        @if(count($staffList) > 1)
            @foreach($staffList as $staffMember)
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $staffMember }}</div>
                <div class="sig-title">Faculty In-Charge</div>
            </div>
            @endforeach
        @else
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">{{ !empty($staffList) ? $staffList[0] : ($facultyNames ?? ($lecturerName ?? 'Faculty In-Charge')) }}</div>
                <div class="sig-title">Faculty In-Charge</div>
            </div>
        @endif
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
