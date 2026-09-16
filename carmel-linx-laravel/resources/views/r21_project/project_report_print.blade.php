<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Major Project Group-Wise Report & Evaluation Register (R-2021) - {{ $subject->subject_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 6mm 8mm 6mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111;
            margin: 0 auto;
            padding: 8px;
            font-size: 9px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #222;
            padding-bottom: 4px;
        }
        .header h1 {
            font-size: 13.5px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 11px;
            margin: 0 0 2px 0;
            font-weight: 600;
        }
        .header h3 {
            font-size: 9.5px;
            margin: 0;
            font-weight: bold;
            color: #333;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 8.5px;
            font-weight: 600;
        }
        .meta-table td {
            padding: 1.5px 0;
        }
        .group-card {
            margin-bottom: 12px;
            border: 1px solid #333;
            page-break-inside: avoid;
        }
        .group-header {
            background-color: #0f172a;
            color: #fff;
            padding: 4px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            font-weight: bold;
        }
        .group-header .title {
            color: #38bdf8;
            font-weight: normal;
        }
        .group-header .guide {
            color: #fde047;
            font-weight: normal;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #444;
            padding: 3px 2px;
            text-align: center;
            font-size: 8px;
        }
        .report-table th {
            background-color: #f1f3f5;
            font-size: 7.2px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .group-summary-bar {
            background-color: #e2e8f0;
            padding: 3px 8px;
            font-size: 8px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #333;
        }
        .attainment-box {
            margin-top: 15px;
            border: 1.5px solid #0f172a;
            page-break-inside: avoid;
        }
        .attainment-header {
            background-color: #1e293b;
            color: #fff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 9.5px;
            display: flex;
            justify-content: space-between;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 25%;
            text-align: center;
            padding-top: 35px;
            font-weight: bold;
            font-size: 9px;
            vertical-align: bottom;
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            background: #f8fafc;
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .btn {
            background-color: #0284c7;
            color: #fff;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn:hover {
            background-color: #0369a1;
        }
        .btn-outline {
            background-color: transparent;
            color: #0f172a;
            border: 1px solid #94a3b8;
        }
        .btn-outline:hover {
            background-color: #e2e8f0;
        }
        .btn-outline.active {
            background-color: #0f172a;
            color: #fff;
            border-color: #0f172a;
        }
        @media print {
            .action-bar {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .group-card {
                break-inside: avoid;
            }
            .attainment-box {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Print / View Navigation Controls -->
    <div class="action-bar">
        <div style="display: flex; gap: 6px; align-items: center;">
            <span style="font-weight: bold; font-size: 10px; margin-right: 4px;">Display Mode:</span>
            <button class="btn btn-outline active" id="btnViewGroup" onclick="setViewMode('group')">
                Group-Wise Detailed Report
            </button>
            <button class="btn btn-outline" id="btnViewSerial" onclick="setViewMode('serial')">
                Consolidated Serial Register
            </button>
        </div>
        <div style="display: flex; gap: 6px;">
            <button class="btn" onclick="window.print()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print Report (A4 Landscape)
            </button>
            <button class="btn" style="background-color: #475569;" onclick="window.close()">Close Window</button>
        </div>
    </div>

    <!-- Official SBTE Header -->
    <div class="header">
        <h1>STATE BOARD OF TECHNICAL EDUCATION, KERALA</h1>
        <h2>CONSOLIDATED MAJOR PROJECT EVALUATION REGISTER (REVISION 2021)</h2>
        <h3>Clauses 11.2.5 (CIA - 75 Marks) & 11.3.4 (ESE - 50 Marks) | Total: 125 Marks (Ratio 3:2)</h3>
    </div>

    <!-- Metadata Strip -->
    <table class="meta-table">
        <tr>
            <td style="width: 25%;"><strong>Programme:</strong> Diploma in {{ $department ?: 'Engineering' }}</td>
            <td style="width: 25%;"><strong>Course Code:</strong> {{ $subject->subject_code }}</td>
            <td style="width: 35%;"><strong>Course Title:</strong> {{ $subject->subject_name }}</td>
            <td style="width: 15%; text-align: right;"><strong>Semester:</strong> VI</td>
        </tr>
        <tr>
            <td><strong>Academic Year:</strong> {{ $currentYear }}</td>
            <td><strong>Total Groups:</strong> {{ count($groupedProjects) }} Groups</td>
            <td><strong>Total Students:</strong> {{ $totalStudents }}</td>
            <td style="text-align: right;"><strong>Credits:</strong> 4.0 (Max: 125M)</td>
        </tr>
    </table>

    <!-- VIEW 1: GROUP-WISE COMPREHENSIVE REPORT -->
    <div id="viewGroupWise">
        @foreach($groupedProjects as $gIdx => $grp)
            <div class="group-card">
                <div class="group-header">
                    <div>
                        <span>{{ strtoupper($grp['name']) }}</span>
                        <span class="title">&nbsp;|&nbsp; <strong>Title:</strong> {{ $grp['title'] ?: 'Pending Title' }}</span>
                    </div>
                    <div>
                        <span class="guide"><strong>Guide:</strong> {{ $grp['guide_name'] ?: 'Not Assigned' }}</span>
                        <span style="margin-left: 10px; color: #94a3b8;">({{ count($grp['students']) }} Members)</span>
                    </div>
                </div>

                <table class="report-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 2.5%;">#</th>
                            <th rowspan="2" style="width: 5%;">Roll</th>
                            <th rowspan="2" style="width: 8%;">Reg No</th>
                            <th rowspan="2" style="width: 14%;">Student Name</th>
                            
                            <!-- Split-up Attendance -->
                            <th colspan="4" style="background-color: #e0f2fe; color: #0369a1;">Attendance Split-up (Cl. 11.2.5)</th>
                            
                            <!-- Split-up CIA -->
                            <th colspan="3" style="background-color: #f1f5f9; color: #334155;">CIA Split-up (75M)</th>
                            <th rowspan="2" style="width: 5.5%; background-color: #cbd5e1; color: #0f172a; font-weight: bold;">Total CIA<br>(75M)</th>
                            
                            <!-- Split-up ESE Rubrics -->
                            <th colspan="8" style="background-color: #fef3c7; color: #92400e;">ESE Rubrics Split-up (Clause 11.3.4 - 50M)</th>
                            <th rowspan="2" style="width: 4.5%; background-color: #fde68a; color: #78350f; font-weight: bold;">ESE Tot<br>(50M)</th>
                            <th rowspan="2" style="width: 4%;">Grade</th>
                            
                            <!-- Combined -->
                            <th rowspan="2" style="width: 5.5%; background-color: #dbeafe; color: #1e3a8a; font-weight: bold;">Grand<br>(125M)</th>
                            <th rowspan="2" style="width: 5%;">Result</th>
                        </tr>
                        <tr>
                            <!-- Attendance Sub-headers -->
                            <th style="width: 3.5%; background-color: #f0f9ff;">Cond</th>
                            <th style="width: 3.5%; background-color: #f0f9ff;">Attd</th>
                            <th style="width: 4%; background-color: #f0f9ff;">%</th>
                            <th style="width: 4%; background-color: #e0f2fe;">15M</th>

                            <!-- CIA Sub-headers -->
                            <th style="width: 4.5%;">Diary<br>(30M)</th>
                            <th style="width: 4.5%;">Dept<br>(30M)</th>
                            <th style="width: 4%;">Attd<br>(15M)</th>

                            <!-- ESE Rubrics Sub-headers -->
                            <th style="width: 3.5%;" title="Working Model / Prototype (10M)">Proto<br>10M</th>
                            <th style="width: 3%;" title="Modern Tools & Tech (5M)">Tool<br>5M</th>
                            <th style="width: 3.5%;" title="Presentation (7.5M)">Pres<br>7.5</th>
                            <th style="width: 3%;" title="Innovativeness (2.5M)">Inno<br>2.5</th>
                            <th style="width: 3.5%;" title="Viva Voce (7.5M)">Viva<br>7.5</th>
                            <th style="width: 3.5%;" title="Individual Contribution (7.5M)">Indiv<br>7.5</th>
                            <th style="width: 3%;" title="Group Activity (5M)">Grp<br>5M</th>
                            <th style="width: 3%;" title="Project Report (5M)">Rep<br>5M</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grp['students'] as $sIdx => $st)
                            <tr>
                                <td>{{ $sIdx + 1 }}</td>
                                <td style="font-weight: bold;">{{ $st['roll_no'] ?: '-' }}</td>
                                <td style="font-weight: bold;">{{ $st['sbte_reg_no'] }}</td>
                                <td class="align-left" style="font-weight: 600;">{{ $st['name'] }}</td>

                                <!-- Attendance -->
                                <td>{{ $st['conducted'] }}</td>
                                <td>{{ $st['attended'] }}</td>
                                <td style="font-weight: bold; color: {{ $st['att_percentage'] >= 75 ? '#15803d' : ($st['att_percentage'] >= 65 ? '#b45309' : '#b91c1c') }}">
                                    {{ $st['att_percentage'] }}%
                                </td>
                                <td style="font-weight: bold; background-color: #f0fdf4;">{{ number_format($st['attendance_marks'], 1) }}</td>

                                <!-- CIA -->
                                <td>{{ number_format($st['formative_diary'], 1) }}</td>
                                <td>{{ number_format($st['summative_dept'], 1) }}</td>
                                <td>{{ number_format($st['attendance_marks'], 1) }}</td>
                                <td style="font-weight: bold; background-color: #f1f5f9;">{{ number_format($st['total_cia_75'], 1) }}</td>

                                <!-- ESE Rubrics -->
                                <td>{{ number_format($st['ese_prototype'], 1) }}</td>
                                <td>{{ number_format($st['ese_modern_tools'], 1) }}</td>
                                <td>{{ number_format($st['ese_presentation'], 1) }}</td>
                                <td>{{ number_format($st['ese_innovativeness'], 1) }}</td>
                                <td>{{ number_format($st['ese_viva'], 1) }}</td>
                                <td>{{ number_format($st['ese_individual_contrib'], 1) }}</td>
                                <td>{{ number_format($st['ese_group_activity'], 1) }}</td>
                                <td>{{ number_format($st['ese_project_report'], 1) }}</td>

                                <!-- ESE Total & Grade -->
                                <td style="font-weight: bold; background-color: #fffbeb;">{{ number_format($st['total_ese_50'], 1) }}</td>
                                <td style="font-weight: bold;">{{ $st['ese_grade'] }}</td>

                                <!-- Grand Total & Result -->
                                <td style="font-weight: bold; background-color: #eff6ff;">{{ number_format($st['grand_total_125'], 1) }}</td>
                                <td style="font-weight: bold; color: {{ $st['result'] === 'Passed' ? '#15803d' : ($st['result'] === 'Failed' ? '#b91c1c' : '#64748b') }}">
                                    {{ $st['result'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="23">No students assigned to this group yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="group-summary-bar">
                    <span>Group Statistics:</span>
                    <span>Average CIA: <span style="color: #0369a1;">{{ $grp['avg_cia'] }} / 75</span></span>
                    <span>Average ESE: <span style="color: #92400e;">{{ $grp['avg_ese'] }} / 50</span></span>
                    <span>Average Grand Total: <span style="color: #1e3a8a;">{{ $grp['avg_total'] }} / 125</span></span>
                    <span>Pass Status: {{ $grp['students']->where('passed', true)->count() }} / {{ count($grp['students']) }} Passed</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- VIEW 2: CONSOLIDATED SERIAL REGISTER -->
    <div id="viewSerialRegister" style="display: none;">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 3%;">#</th>
                    <th style="width: 7%;">Roll / Reg No</th>
                    <th style="width: 14%;">Student Name</th>
                    <th style="width: 8%;">Group</th>
                    <th style="width: 18%;">Project Title</th>
                    <th style="width: 5%;">Attd %</th>
                    <th style="width: 6%;">Diary (30)</th>
                    <th style="width: 6%;">Dept (30)</th>
                    <th style="width: 5%;">Attd (15)</th>
                    <th style="width: 7%; background-color: #f1f5f9;">Total CIA (75)</th>
                    <th style="width: 7%; background-color: #fef3c7;">Total ESE (50)</th>
                    <th style="width: 4%;">Grade</th>
                    <th style="width: 6%; background-color: #dbeafe;">Grand Tot (125)</th>
                    <th style="width: 5%;">Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $idx => $st)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight: bold;">{{ $st['sbte_reg_no'] }}</td>
                        <td class="align-left" style="font-weight: 600;">{{ $st['name'] }}</td>
                        <td>{{ $st['group_name'] }}</td>
                        <td class="align-left" style="font-size: 7.5px;">{{ $st['project_title'] }}</td>
                        <td style="font-weight: bold; color: {{ $st['att_percentage'] >= 75 ? '#15803d' : '#b91c1c' }}">{{ $st['att_percentage'] }}%</td>
                        <td>{{ number_format($st['formative_diary'], 1) }}</td>
                        <td>{{ number_format($st['summative_dept'], 1) }}</td>
                        <td>{{ number_format($st['attendance_marks'], 1) }}</td>
                        <td style="font-weight: bold; background-color: #f1f5f9;">{{ number_format($st['total_cia_75'], 1) }}</td>
                        <td style="font-weight: bold; background-color: #fffbeb;">{{ number_format($st['total_ese_50'], 1) }}</td>
                        <td style="font-weight: bold;">{{ $st['ese_grade'] }}</td>
                        <td style="font-weight: bold; background-color: #eff6ff;">{{ number_format($st['grand_total_125'], 1) }}</td>
                        <td style="font-weight: bold; color: {{ $st['result'] === 'Passed' ? '#15803d' : '#b91c1c' }}">{{ $st['result'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- SECTION: COURSE OUTCOME (CO) ATTAINMENT ANALYSIS -->
    @if(!empty($attainmentSummary['matrix']))
        <div class="attainment-box">
            <div class="attainment-header">
                <span>COURSE OUTCOME (CO) ATTAINMENT SUMMARY - MAJOR PROJECT (R-2021)</span>
                <span>Direct (30% CIE + 70% ESE) | Indirect (Exit Survey) | Overall (80% Direct + 20% Indirect)</span>
            </div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">CO Tag</th>
                        <th style="width: 36%;">Course Outcome Description</th>
                        <th style="width: 8%;">CIE Assessed</th>
                        <th style="width: 8%;">CIE Met %</th>
                        <th style="width: 8%;">CIE Level (0-3)</th>
                        <th style="width: 8%;">ESE Level (0-3)</th>
                        <th style="width: 8%; background-color: #dcfce7; color: #166534; font-weight: bold;">Direct Attainment<br>(30:70)</th>
                        <th style="width: 8%; background-color: #f3e8ff; color: #6b21a8; font-weight: bold;">Indirect<br>(Exit Survey)</th>
                        <th style="width: 8%; background-color: #dbeafe; color: #1e40af; font-weight: bold;">Overall Attainment<br>(80:20)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attainmentSummary['matrix'] as $co)
                        <tr>
                            <td style="font-weight: bold; font-family: monospace;">{{ $co['co_tag'] }}</td>
                            <td class="align-left" style="font-size: 7.5px;">{{ $co['description'] }}</td>
                            <td>{{ $co['cie_assessed'] }}</td>
                            <td style="font-weight: bold;">{{ $co['cie_met_pct'] }}%</td>
                            <td style="font-weight: bold;">{{ $co['cie_level'] }}</td>
                            <td style="font-weight: bold;">{{ $co['ese_level'] }}</td>
                            <td style="font-weight: bold; background-color: #f0fdf4;">{{ number_format($co['direct_attainment'], 2) }}</td>
                            <td style="font-weight: bold; background-color: #faf5ff;">{{ number_format($co['indirect_attainment'], 2) }}</td>
                            <td style="font-weight: bold; background-color: #eff6ff; font-size: 8.5px;">{{ number_format($co['overall_attainment'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8fafc; font-weight: bold;">
                        <td colspan="6" style="text-align: right; padding-right: 8px;">OVERALL COURSE ATTAINMENT AVERAGE:</td>
                        <td style="background-color: #dcfce7; color: #166534;">{{ $attainmentSummary['average_direct'] ?? '-' }}</td>
                        <td style="background-color: #f3e8ff; color: #6b21a8;">{{ $attainmentSummary['average_indirect'] ?? '-' }}</td>
                        <td style="background-color: #dbeafe; color: #1e40af; font-size: 9px;">{{ $attainmentSummary['average_overall'] ?? '-' }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    <!-- Official Signatures -->
    <table class="footer-signatures">
        <tr>
            <td>
                ____________________________<br>
                Project Faculty Guide(s)
            </td>
            <td>
                ____________________________<br>
                Internal Examiner
            </td>
            <td>
                ____________________________<br>
                External Examiner
            </td>
            <td>
                ____________________________<br>
                Head of the Department
            </td>
        </tr>
    </table>

    <script>
        function setViewMode(mode) {
            const vGroup = document.getElementById('viewGroupWise');
            const vSerial = document.getElementById('viewSerialRegister');
            const btnGroup = document.getElementById('btnViewGroup');
            const btnSerial = document.getElementById('btnViewSerial');

            if (mode === 'group') {
                vGroup.style.display = 'block';
                vSerial.style.display = 'none';
                btnGroup.classList.add('active');
                btnSerial.classList.remove('active');
            } else {
                vGroup.style.display = 'none';
                vSerial.style.display = 'block';
                btnSerial.classList.add('active');
                btnGroup.classList.remove('active');
            }
        }
    </script>
</body>
</html>
