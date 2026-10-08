<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($reportType === 'group_dossier' || $reportType === 'group_breakdown')
            Major Project Group-Wise Breakdown (Filing Register) - {{ $subject->subject_name }}
        @elseif($reportType === 'cia_register')
            Continuous Internal Assessment (CIA) Register (75M) - {{ $subject->subject_name }}
        @elseif($reportType === 'sbte_submission')
            SBTE Final Mark Entry Statement (125M) - {{ $subject->subject_name }}
        @elseif($reportType === 'ese_rubrics')
            ESE 8-Rubric Assessment Sheet (Clause 11.3.4) - {{ $subject->subject_name }}
        @else
            Consolidated Major Project Evaluation Register (125M) - {{ $subject->subject_name }}
        @endif
    </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 8mm 8mm 8mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0 auto;
            padding: 10px 14px;
            font-size: 9.5px;
            line-height: 1.3;
            background-color: #f8fafc;
        }
        
        /* Single Page Per Group Dossier Card */
        .group-dossier-page {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 1140px;
            margin: 0 auto 20px auto;
            page-break-after: always;
            break-after: page;
        }
        .group-dossier-page:last-child {
            margin-bottom: 0;
            page-break-after: auto;
            break-after: auto;
        }

        .report-sheet {
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 1140px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
        }
        .header .institute-title {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header .dept-title {
            font-size: 11.5px;
            margin: 0 0 4px 0;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }
        .header .report-badge-title {
            display: inline-block;
            font-size: 10.5px;
            margin: 0;
            font-weight: 800;
            color: #0f172a;
            background: #e2e8f0;
            padding: 3px 12px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .meta-box {
            width: 100%;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 4px;
            vertical-align: middle;
        }
        .meta-label {
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 8.5px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #334155;
            padding: 3.5px 2px;
            text-align: center;
            font-size: 8.5px;
            vertical-align: middle;
        }
        .report-table th {
            background-color: #f1f5f9;
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            line-height: 1.25;
        }
        .report-table th.sub-th {
            background-color: #f8fafc;
            font-size: 7px;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
            padding-right: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        .font-mono-bold {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            font-size: 8.5px;
        }
        .grade-badge {
            font-weight: 800;
            font-size: 9px;
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
        }

        .stats-summary-grid {
            margin-top: 8px;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .stat-box {
            border: 1px solid #cbd5e1;
            padding: 3px 5px;
            text-align: center;
            background: #ffffff;
            border-radius: 4px;
        }
        .stat-box-title {
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }
        .stat-box-num {
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }

        .cert-statement {
            margin-top: 10px;
            padding: 6px 10px;
            background-color: #f8fafc;
            border: 1px dashed #94a3b8;
            font-size: 8px;
            color: #334155;
            line-height: 1.35;
            border-radius: 4px;
        }

        .footer-signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        .footer-signatures td {
            text-align: center;
            padding-top: 32px;
            font-weight: 700;
            font-size: 9px;
            vertical-align: bottom;
            color: #1e293b;
        }

        /* Interactive Action Bar (Screen Only) */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            background: #0e1628;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #1e293b;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 1140px;
            margin-left: auto;
            margin-right: auto;
        }
        .report-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }
        .nav-btn {
            padding: 5px 9px;
            font-size: 10.5px;
            font-weight: 600;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s;
            cursor: pointer;
            border: 1px solid #334155;
            color: #cbd5e1;
            background: #1e293b;
        }
        .nav-btn:hover {
            background: #334155;
            color: #ffffff;
        }
        .nav-btn.active {
            background: #0284c7;
            color: #ffffff;
            border-color: #38bdf8;
            font-weight: 700;
        }
        .group-filter-select {
            background: #1e293b;
            border: 1px solid #334155;
            color: #f1f5f9;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 10.5px;
            font-weight: 600;
            outline: none;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
            shrink-0;
        }
        .btn-print {
            background: #10b981;
            color: #ffffff;
            border: 1px solid #059669;
            padding: 5px 12px;
            font-size: 10.5px;
            font-weight: 700;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-print:hover {
            background: #059669;
        }
        .btn-close {
            background: #475569;
            color: #ffffff;
            border: 1px solid #334155;
            padding: 5px 10px;
            font-size: 10.5px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .btn-close:hover {
            background: #334155;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
                background: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .group-dossier-page, .report-sheet {
                padding: 0;
                box-shadow: none;
                max-width: 100%;
                margin-bottom: 0;
            }
            .report-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .report-table th.sub-th {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .stat-box, .cert-statement, .meta-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- SCREEN ONLY: Tool Bar, Report Switcher & Group Selector -->
    <div class="no-print action-bar">
        <div class="report-nav">
            <span style="font-size: 10.5px; font-weight: 700; color: #94a3b8; margin-right: 2px;">Report:</span>
            
            <a href="/r21/classroom/project/{{ $subject->id }}/report/print?type=group_breakdown&group_id={{ $selectedGroupId }}" class="nav-btn {{ ($reportType === 'group_dossier' || $reportType === 'group_breakdown') ? 'active' : '' }}" title="1 Page per Group for Filing">
                <span class="material-symbols-rounded" style="font-size: 13px;">folder_special</span>
                <span>1. Group-Wise Breakdown (1 Pg/Grp)</span>
            </a>

            <a href="/r21/classroom/project/{{ $subject->id }}/report/print?type=cia_register" class="nav-btn {{ $reportType === 'cia_register' ? 'active' : '' }}" title="Continuous Internal Assessment Register (75M)">
                <span class="material-symbols-rounded" style="font-size: 13px;">edit_note</span>
                <span>2. CIA Register (75M)</span>
            </a>

            <a href="/r21/classroom/project/{{ $subject->id }}/report/print?type=sbte_submission" class="nav-btn {{ $reportType === 'sbte_submission' ? 'active' : '' }}" title="Official SBTE Portal Mark Entry Statement">
                <span class="material-symbols-rounded" style="font-size: 13px;">verified</span>
                <span>3. SBTE Final Marksheet (125M)</span>
            </a>

            <a href="/r21/classroom/project/{{ $subject->id }}/report/print?type=ese_rubrics" class="nav-btn {{ $reportType === 'ese_rubrics' ? 'active' : '' }}" title="ESE 8-Rubric Score Sheet (Clause 11.3.4)">
                <span class="material-symbols-rounded" style="font-size: 13px;">gavel</span>
                <span>4. ESE 8-Rubric Score Sheet (50M)</span>
            </a>

            <a href="/r21/classroom/project/{{ $subject->id }}/report/print?type=consolidated" class="nav-btn {{ $reportType === 'consolidated' ? 'active' : '' }}" title="Complete Broad Register">
                <span class="material-symbols-rounded" style="font-size: 13px;">table_chart</span>
                <span>5. Consolidated Broad Register</span>
            </a>
        </div>

        @if($reportType === 'group_dossier' || $reportType === 'group_breakdown')
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="font-size: 10px; color: #94a3b8; font-weight: 600;">Filter:</span>
                <select class="group-filter-select" onchange="location.href='/r21/classroom/project/{{ $subject->id }}/report/print?type=group_breakdown&group_id=' + this.value">
                    <option value="all" {{ $selectedGroupId === 'all' ? 'selected' : '' }}>All Groups (Separate Pages)</option>
                    @foreach($groupedProjects as $g)
                        <option value="{{ $g['id'] }}" {{ $selectedGroupId == $g['id'] ? 'selected' : '' }}>{{ $g['name'] }} ({{ count($g['students']) }} members)</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="action-buttons">
            <button class="btn-print" onclick="window.print()">
                <span class="material-symbols-rounded" style="font-size: 14px;">print</span>
                <span>Print Document</span>
            </button>
            <button class="btn-close" onclick="window.close()">
                <span class="material-symbols-rounded" style="font-size: 13px;">close</span>
                <span>Close</span>
            </button>
        </div>
    </div>

    <!-- ======================================================================= -->
    <!-- REPORT TYPE 1: GROUP-WISE FILING BREAKDOWN (1 PAGE PER PROJECT GROUP)   -->
    <!-- ======================================================================= -->
    @if($reportType === 'group_dossier' || $reportType === 'group_breakdown')
        @php
            $displayGroups = $groupedProjects;
            if ($selectedGroupId !== 'all') {
                $displayGroups = array_filter($groupedProjects, function($g) use ($selectedGroupId) {
                    return (string)$g['id'] === (string)$selectedGroupId;
                });
            }
        @endphp

        @foreach($displayGroups as $grp)
            <div class="group-dossier-page">
                <div class="header">
                    <div class="institute-title">State Board of Technical Education, Kerala</div>
                    <div class="dept-title">Carmel Polytechnic College, Alappuzha (Institution Code: 043) — Department of {{ $fullDepartment }}</div>
                    <div class="report-badge-title">MAJOR PROJECT GROUP-WISE EVALUATION BREAKDOWN (REVISION 2021) — {{ strtoupper($grp['name']) }}</div>
                </div>

                <!-- Group & Examination Metadata -->
                <div class="meta-box">
                    <table class="meta-table">
                        <tr>
                            <td style="width: 14%" class="meta-label">Project Title:</td>
                            <td style="width: 46%" class="meta-val"><strong style="color: #0284c7; font-size: 10px;">{{ $grp['title'] ?: 'Pending Title' }}</strong></td>
                            <td style="width: 14%" class="meta-label">Course &amp; Sem:</td>
                            <td style="width: 26%" class="meta-val">{{ $subject->formatted_subject_code ?? $subject->subject_code }} — Sem VI</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Faculty Guide:</td>
                            <td class="meta-val"><strong>{{ $grp['guide_name'] ?: 'Not Assigned' }}</strong></td>
                            <td class="meta-label">Assessment Scheme:</td>
                            <td class="meta-val">Ratio 3:2 | CIA: 75M | ESE: 50M | Total: 125M</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Internal Examiner:</td>
                            <td class="meta-val">{{ $examiners['internal_name'] ?: 'Faculty Member' }} ({{ $examiners['internal_designation'] ?: $fullDepartment }})</td>
                            <td class="meta-label">Examination Date:</td>
                            <td class="meta-val">{{ $examiners['exam_date'] ?? date('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">External Examiner:</td>
                            <td class="meta-val">{{ $examiners['external_name'] ?: 'External Examiner' }} ({{ $examiners['external_designation'] ?: 'Appointed by CTE' }}{{ $examiners['external_college'] ? ', ' . $examiners['external_college'] : '' }})</td>
                            <td class="meta-label">Group Members:</td>
                            <td class="meta-val">{{ count($grp['students']) }} Candidates</td>
                        </tr>
                    </table>
                </div>

                <!-- Marks Table for this Group -->
                <table class="report-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 3%">Sl</th>
                            <th rowspan="2" style="width: 4%">Roll</th>
                            <th rowspan="2" style="width: 9%">SBTE Reg No</th>
                            <th rowspan="2" style="width: 14%">Candidate Name</th>
                            <th colspan="4" style="background-color: #f1f5f9;">Continuous Internal Assessment (CIA - 75M)</th>
                            <th colspan="8" style="background-color: #fef3c7; color: #92400e;">End Semester Evaluation (ESE - 50M - Clause 11.3.4)</th>
                            <th rowspan="2" style="width: 5.5%; background-color: #fde68a; font-weight: bold;">ESE Tot<br>(50M)</th>
                            <th rowspan="2" style="width: 5.5%; background-color: #dbeafe; font-weight: bold;">Grand<br>(125M)</th>
                            <th rowspan="2" style="width: 4.5%">Grade</th>
                            <th rowspan="2" style="width: 5%">Result</th>
                        </tr>
                        <tr>
                            <!-- CIA Subheaders -->
                            <th class="sub-th" style="width: 4.5%">Diary<br>(30M)</th>
                            <th class="sub-th" style="width: 4.5%">Dept<br>(30M)</th>
                            <th class="sub-th" style="width: 4%">Att.<br>(15M)</th>
                            <th class="sub-th" style="width: 5.5%; background-color: #e2e8f0; font-weight: bold;">CIA<br>(75M)</th>

                            <!-- ESE Subheaders -->
                            <th class="sub-th" style="width: 4%" title="Working Model / Prototype (10M)">Proto<br>(10M)</th>
                            <th class="sub-th" style="width: 3.5%" title="Modern Tools & Technology (5M)">Tools<br>(5M)</th>
                            <th class="sub-th" style="width: 3.5%" title="Presentation slides & delivery (7.5M)">Pres<br>(7.5)</th>
                            <th class="sub-th" style="width: 3.5%" title="Innovativeness (2.5M)">Inno<br>(2.5)</th>
                            <th class="sub-th" style="width: 3.5%" title="Viva Voce (7.5M)">Viva<br>(7.5)</th>
                            <th class="sub-th" style="width: 3.5%" title="Individual Contribution (7.5M)">Indiv<br>(7.5)</th>
                            <th class="sub-th" style="width: 3.5%" title="Group Activity (5M)">Grp<br>(5M)</th>
                            <th class="sub-th" style="width: 3.5%" title="Project Report & Documentation (5M)">Rep<br>(5M)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grp['students'] as $sIdx => $st)
                            <tr>
                                <td>{{ $sIdx + 1 }}</td>
                                <td>{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                                <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>

                                <!-- CIA -->
                                <td>{{ number_format($st['formative_diary'], 1) }}</td>
                                <td>{{ number_format($st['summative_dept'], 1) }}</td>
                                <td>{{ number_format($st['attendance_marks'], 1) }}</td>
                                <td class="font-mono-bold" style="background-color: #f1f5f9;">{{ round($st['total_cia_75']) }}</td>

                                <!-- ESE Rubrics -->
                                <td>{{ number_format($st['ese_prototype'], 1) }}</td>
                                <td>{{ number_format($st['ese_modern_tools'], 1) }}</td>
                                <td>{{ number_format($st['ese_presentation'], 1) }}</td>
                                <td>{{ number_format($st['ese_innovativeness'], 1) }}</td>
                                <td>{{ number_format($st['ese_viva'], 1) }}</td>
                                <td>{{ number_format($st['ese_individual_contrib'], 1) }}</td>
                                <td>{{ number_format($st['ese_group_activity'], 1) }}</td>
                                <td>{{ number_format($st['ese_project_report'], 1) }}</td>

                                <!-- ESE Total -->
                                <td class="font-mono-bold" style="background-color: #fef9c3;">{{ number_format($st['total_ese_50'], 1) }}</td>

                                <!-- Grand Total -->
                                <td class="font-mono-bold" style="background-color: #e0f2fe; font-size: 9.5px;">{{ number_format($st['grand_total_125'], 1) }}</td>

                                <!-- Grade & Result -->
                                <td><span class="grade-badge">{{ $st['final_grade'] }}</span></td>
                                <td style="font-weight: 700; {{ $st['passed'] ? 'color: #047857;' : 'color: #b91c1c;' }}">
                                    {{ $st['has_eval'] ? ($st['passed'] ? 'PASS' : 'FAILED') : 'PENDING' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px 8px; display: flex; justify-content: space-between; font-size: 8.5px; font-weight: 700;">
                    <span>Group Statistics:</span>
                    <span>CIA Average: {{ $grp['avg_cia'] }} / 75</span>
                    <span>ESE Average: {{ $grp['avg_ese'] }} / 50</span>
                    <span>Grand Total Average: {{ $grp['avg_total'] }} / 125</span>
                    <span style="color: #047857;">Passed: {{ $grp['students']->where('passed', true)->count() }} / {{ count($grp['students']) }}</span>
                </div>

                <div class="cert-statement">
                    <strong>EXAMINERS' STATUTORY CERTIFICATION:</strong><br>
                    Certified that the project work, prototype model, presentation slides, project report, and viva-voce examination of <strong>{{ strtoupper($grp['name']) }}</strong> (Title: <em>"{{ $grp['title'] }}"</em>) have been jointly conducted and assessed as per the statutory guidelines of the State Board of Technical Education, Kerala (Revision 2021) Clauses 11.2.5 (CIA - 75M) and 11.3.4 (ESE - 50M).
                </div>

                <table class="footer-signatures">
                    <tr>
                        <td style="width: 25%">
                            Project Guide<br>
                            ({{ $grp['guide_name'] ?: 'Signature' }})
                        </td>
                        <td style="width: 25%">
                            Internal Examiner<br>
                            ({{ $examiners['internal_name'] ?: 'Signature' }})
                        </td>
                        <td style="width: 25%">
                            External Examiner<br>
                            ({{ $examiners['external_name'] ?: 'Signature' }})
                        </td>
                        <td style="width: 25%">
                            Head of Department<br>
                            (Seal &amp; Signature)
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach

    <!-- ======================================================================= -->
    <!-- REPORT TYPE 2: CONTINUOUS INTERNAL ASSESSMENT (CIA 75M) REGISTER         -->
    <!-- ======================================================================= -->
    @elseif($reportType === 'cia_register')
        <div class="report-sheet">
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha (Institution Code: 043) — Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">CONTINUOUS INTERNAL ASSESSMENT (CIA) REGISTER (CLAUSE 11.2.5 - MAX 75 MARKS)</div>
            </div>

            <div class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td style="width: 14%" class="meta-label">Course Title:</td>
                        <td style="width: 36%" class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                        <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                        <td style="width: 36%" class="meta-val">Semester VI • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Statutory Scheme:</td>
                        <td class="meta-val">Formative 40% (30M) + Summative 40% (30M) + Attendance 20% (15M) = 75 Marks Total (Min 40% = 30M to Pass)</td>
                        <td class="meta-label">Date of Generation:</td>
                        <td class="meta-val">{{ date('d-m-Y') }}</td>
                    </tr>
                </table>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%">Sl</th>
                        <th rowspan="2" style="width: 4%">Roll</th>
                        <th rowspan="2" style="width: 9%">SBTE Reg No</th>
                        <th rowspan="2" style="width: 14%">Candidate Name</th>
                        <th rowspan="2" style="width: 6%">Group</th>
                        <th rowspan="2" style="width: 4%">Att.<br>%</th>
                        <th colspan="3" style="background-color: #f1f5f9; color: #334155;">CIA Statutory Assessment Split-up</th>
                        <th rowspan="2" style="width: 6.5%; background-color: #dbeafe; font-weight: bold;">Total CIA<br>(75M)</th>
                        <th rowspan="2" style="width: 16%">Total Marks in Words</th>
                        <th rowspan="2" style="width: 4%">CIA<br>Grade</th>
                        <th rowspan="2" style="width: 4%">Grade<br>Point</th>
                        <th rowspan="2" style="width: 5.5%">Result</th>
                        <th rowspan="2" style="width: 10%">Signature of Candidate</th>
                    </tr>
                    <tr>
                        <th class="sub-th" style="width: 5%" title="Attendance Mark (20% = 15M)">Attd.<br>(15M)</th>
                        <th class="sub-th" style="width: 5.5%" title="Weekly Diary / Activity (40% = 30M)">Diary (40%)<br>(30M)</th>
                        <th class="sub-th" style="width: 5.5%" title="Department Presentation & Review (40% = 30M)">Dept (40%)<br>(30M)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ciaPassCount = 0;
                        $ciaFailCount = 0;
                    @endphp
                    @foreach($students as $idx => $st)
                        @php
                            $ciaPass = ($st['total_cia_75'] >= 30.0);
                            if ($st['has_eval']) {
                                if ($ciaPass) $ciaPassCount++;
                                else $ciaFailCount++;
                            }
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] ?? '-' }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] ?? '-' }}</td>
                            <td>{{ $st['group_name'] ?? '-' }}</td>
                            <td>{{ $st['att_percentage'] ?? 0 }}%</td>
                            <td>{{ number_format($st['attendance_marks'] ?? 0, 1) }}</td>
                            <td>{{ number_format($st['formative_diary_marks'] ?? $st['formative_diary'] ?? 0, 1) }}</td>
                            <td>{{ number_format($st['summative_dept_marks'] ?? $st['summative_dept'] ?? 0, 1) }}</td>
                            <td class="font-mono-bold" style="background-color: #e0f2fe; font-size: 9.5px;">{{ round($st['total_cia_75'] ?? 0) }}</td>
                            <td class="align-left" style="font-size: 7.5px; font-weight: 600; text-transform: capitalize;">
                                {{ $st['cia_in_words'] ?? '—' }}
                            </td>
                            <td><span class="grade-badge">{{ $st['cia_grade'] ?? '—' }}</span></td>
                            <td>{{ $st['cia_points'] ?? '0' }}</td>
                            <td style="font-weight: 700; {{ $ciaPass ? 'color: #047857;' : 'color: #b91c1c;' }}">
                                {{ ($st['has_eval'] ?? false) ? ($ciaPass ? 'PASS' : 'FAILED') : 'PENDING' }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="stats-summary-grid">
                <div class="stat-box">
                    <div class="stat-box-title">Total Enrolled</div>
                    <div class="stat-box-num">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Evaluated</div>
                    <div class="stat-box-num">{{ $completedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Passed (&ge;30.0 M)</div>
                    <div class="stat-box-num" style="color: #047857;">{{ $ciaPassCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Failed (&lt;30.0 M)</div>
                    <div class="stat-box-num" style="color: #b91c1c;">{{ $ciaFailCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Class Average CIA</div>
                    <div class="stat-box-num">{{ $avgCiaOverall }} / 75</div>
                </div>
            </div>

            <table class="footer-signatures" style="margin-top: 35px;">
                <tr>
                    <td style="width: 33.3%">
                        Faculty Guide / Evaluator<br>
                        (Signature)
                    </td>
                    <td style="width: 33.3%">
                        Project Coordinator<br>
                        (Signature)
                    </td>
                    <td style="width: 33.3%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>
        </div>

    <!-- ======================================================================= -->
    <!-- REPORT TYPE 3: OFFICIAL SBTE FINAL CIA + ESE MARK ENTRY STATEMENT (125M) -->
    <!-- ======================================================================= -->
    @elseif($reportType === 'sbte_submission')
        <div class="report-sheet">
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha (Institution Code: 043) — Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">DIPLOMA EXAMINATION (REVISION 2021) — MAJOR PROJECT FINAL MARK STATEMENT (CIA 75M + ESE 50M)</div>
            </div>

            <div class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td style="width: 14%" class="meta-label">Course Title:</td>
                        <td style="width: 36%" class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                        <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                        <td style="width: 36%" class="meta-val">Semester VI • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Assessment Ratio:</td>
                        <td class="meta-val">Ratio 3:2 | CIA: 75 Marks | ESE: 50 Marks | Grand Total: 125 Marks</td>
                        <td class="meta-label">Date of Examination:</td>
                        <td class="meta-val">{{ $examiners['exam_date'] ?? date('d-m-Y') }}</td>
                    </tr>
                </table>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 3%">Sl</th>
                        <th style="width: 4%">Roll</th>
                        <th style="width: 9%">SBTE Reg Number</th>
                        <th style="width: 14%">Name of Candidate</th>
                        <th style="width: 6%">Group</th>
                        <th style="width: 4%">Att.<br>%</th>
                        <th style="width: 6%">CIA Mark<br>(75M)</th>
                        <th style="width: 6%">ESE Mark<br>(50M)</th>
                        <th style="width: 6.5%; background-color: #dbeafe; font-weight: bold;">Grand Total<br>(125M)</th>
                        <th style="width: 17%">Total Marks in Words</th>
                        <th style="width: 4%">Letter<br>Grade</th>
                        <th style="width: 4%">Grade<br>Point</th>
                        <th style="width: 6%">Result</th>
                        <th style="width: 10.5%">Signature of Candidate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td>{{ $st['group_name'] }}</td>
                            <td>{{ $st['att_percentage'] }}%</td>
                            <td>{{ round($st['total_cia_75']) }}</td>
                            <td>{{ number_format($st['total_ese_50'], 1) }}</td>
                            <td class="font-mono-bold" style="background-color: #e0f2fe; font-size: 9.5px;">{{ number_format($st['grand_total_125'], 1) }}</td>
                            <td class="align-left" style="font-size: 7.5px; font-weight: 600; text-transform: capitalize;">
                                {{ $st['score_in_words'] }}
                            </td>
                            <td><span class="grade-badge">{{ $st['final_grade'] }}</span></td>
                            <td>{{ $st['final_points'] }}</td>
                            <td style="font-weight: 700; {{ $st['passed'] ? 'color: #047857;' : 'color: #b91c1c;' }}">
                                {{ $st['has_eval'] ? ($st['passed'] ? 'PASS' : 'FAILED') : 'PENDING' }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="stats-summary-grid">
                <div class="stat-box">
                    <div class="stat-box-title">Total Registered</div>
                    <div class="stat-box-num">{{ $totalStudents }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Total Appeared</div>
                    <div class="stat-box-num">{{ $completedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Passed / Failed</div>
                    <div class="stat-box-num">{{ $passedCount }} / {{ $failedCount }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Pass Percentage</div>
                    <div class="stat-box-num">{{ $passRate }}%</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Average CIA / 75</div>
                    <div class="stat-box-num">{{ $avgCiaOverall }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Average ESE / 50</div>
                    <div class="stat-box-num">{{ $avgEseOverall }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-title">Average Total / 125</div>
                    <div class="stat-box-num">{{ $avgGrandOverall }}</div>
                </div>
            </div>

            <div class="cert-statement">
                <strong>STATUTORY CERTIFICATION &amp; DECLARATION:</strong><br>
                Certified that the Continuous Internal Assessment (CIA - 75 Marks) and End Semester Examination (ESE - 50 Marks) for the Major Project course have been evaluated strictly as per SBTE Kerala Diploma Curriculum (Revision 2021) Clauses 11.2.5 and 11.3.4. The marks and grades entered above are authentic and verified against the department project evaluation records.
            </div>

            <table class="footer-signatures">
                <tr>
                    <td style="width: 25%">
                        Course Coordinator / Guide<br>
                        (Signature)
                    </td>
                    <td style="width: 25%">
                        Internal Examiner<br>
                        ({{ $examiners['internal_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 25%">
                        External Examiner<br>
                        ({{ $examiners['external_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 25%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>
        </div>

    <!-- ======================================================================= -->
    <!-- REPORT TYPE 3: ESE 8-RUBRIC SCORE SHEET (CLAUSE 11.3.4 - 50 MARKS)       -->
    <!-- ======================================================================= -->
    @elseif($reportType === 'ese_rubrics')
        <div class="report-sheet">
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha — Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">MAJOR PROJECT END SEMESTER EVALUATION (ESE) 8-RUBRIC REGISTER (CLAUSE 11.3.4 - MAX 50 MARKS)</div>
            </div>

            <div class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td style="width: 14%" class="meta-label">Course:</td>
                        <td style="width: 36%" class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                        <td style="width: 14%" class="meta-label">Internal Examiner:</td>
                        <td style="width: 36%" class="meta-val">{{ $examiners['internal_name'] ?: 'Faculty Member' }} ({{ $examiners['internal_designation'] ?: $fullDepartment }})</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Statutory Rule:</td>
                        <td class="meta-val">Clause 11.3.4: Internal &amp; External Examiner shall jointly conduct ESE based on 8 criteria</td>
                        <td class="meta-label">External Examiner:</td>
                        <td class="meta-val">{{ $examiners['external_name'] ?: 'External Examiner' }} ({{ $examiners['external_designation'] ?: 'Appointed by CTE' }})</td>
                    </tr>
                </table>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%">Sl</th>
                        <th rowspan="2" style="width: 4%">Roll</th>
                        <th rowspan="2" style="width: 9%">SBTE Reg No</th>
                        <th rowspan="2" style="width: 13%">Candidate Name</th>
                        <th rowspan="2" style="width: 7%">Group</th>
                        <th colspan="8" style="background-color: #fef3c7; color: #92400e;">Clause 11.3.4 Statutory Evaluation Criteria (100% = 50 Marks)</th>
                        <th rowspan="2" style="width: 6%; background-color: #fde68a; font-weight: bold;">Total ESE<br>(50M)</th>
                        <th rowspan="2" style="width: 5%">Letter<br>Grade</th>
                        <th rowspan="2" style="width: 5%">Result</th>
                    </tr>
                    <tr>
                        <th class="sub-th" style="width: 5%" title="Development of prototype / Model (20% = 10M)">1. Model<br>(10M)</th>
                        <th class="sub-th" style="width: 4.5%" title="Usage of Modern Tool / Technology (10% = 5M)">2. Tools<br>(5M)</th>
                        <th class="sub-th" style="width: 5%" title="Presentation (slides, delivery) (15% = 7.5M)">3. Pres.<br>(7.5M)</th>
                        <th class="sub-th" style="width: 4.5%" title="Innovativeness (5% = 2.5M)">4. Inno.<br>(2.5M)</th>
                        <th class="sub-th" style="width: 5%" title="Viva Voce (15% = 7.5M)">5. Viva<br>(7.5M)</th>
                        <th class="sub-th" style="width: 5%" title="Individual contribution (15% = 7.5M)">6. Indiv.<br>(7.5M)</th>
                        <th class="sub-th" style="width: 4.5%" title="Group activity (10% = 5M)">7. Grp.<br>(5M)</th>
                        <th class="sub-th" style="width: 4.5%" title="Project report (10% = 5M)">8. Rep.<br>(5M)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td>{{ $st['group_name'] }}</td>
                            <td>{{ number_format($st['ese_prototype'], 1) }}</td>
                            <td>{{ number_format($st['ese_modern_tools'], 1) }}</td>
                            <td>{{ number_format($st['ese_presentation'], 1) }}</td>
                            <td>{{ number_format($st['ese_innovativeness'], 1) }}</td>
                            <td>{{ number_format($st['ese_viva'], 1) }}</td>
                            <td>{{ number_format($st['ese_individual_contrib'], 1) }}</td>
                            <td>{{ number_format($st['ese_group_activity'], 1) }}</td>
                            <td>{{ number_format($st['ese_project_report'], 1) }}</td>
                            <td class="font-mono-bold" style="background-color: #fef9c3; font-size: 9.5px;">{{ number_format($st['total_ese_50'], 1) }}</td>
                            <td><span class="grade-badge">{{ $st['ese_grade'] }}</span></td>
                            <td style="font-weight: 700; {{ $st['total_ese_50'] >= 20.0 ? 'color: #047857;' : 'color: #b91c1c;' }}">
                                {{ $st['has_ese'] ? ($st['total_ese_50'] >= 20.0 ? 'PASS' : 'FAILED') : 'PENDING' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-signatures" style="margin-top: 35px;">
                <tr>
                    <td style="width: 33.3%">
                        Internal Examiner<br>
                        ({{ $examiners['internal_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 33.3%">
                        External Examiner<br>
                        ({{ $examiners['external_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 33.3%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>
        </div>

    <!-- ======================================================================= -->
    <!-- REPORT TYPE 4: CONSOLIDATED BROAD REGISTER (SERIAL ORDER)                 -->
    <!-- ======================================================================= -->
    @else
        <div class="report-sheet">
            <div class="header">
                <div class="institute-title">State Board of Technical Education, Kerala</div>
                <div class="dept-title">Carmel Polytechnic College, Alappuzha — Department of {{ $fullDepartment }}</div>
                <div class="report-badge-title">CONSOLIDATED MAJOR PROJECT BROAD REGISTER (CLAUSES 11.2.5 &amp; 11.3.4 - TOTAL 125 MARKS)</div>
            </div>

            <div class="meta-box">
                <table class="meta-table">
                    <tr>
                        <td style="width: 14%" class="meta-label">Course Title:</td>
                        <td style="width: 36%" class="meta-val"><strong>{{ $subject->formatted_subject_code ?? $subject->subject_code }} — {{ $subject->subject_name }}</strong></td>
                        <td style="width: 14%" class="meta-label">Semester &amp; Batch:</td>
                        <td style="width: 36%" class="meta-val">Semester VI • {{ $classroom->classroom_name ?? $subject->classroom_id }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Assessment Scheme:</td>
                        <td class="meta-val">Continuous Assessment (75M) + ESE (50M) = 125 Marks</td>
                        <td class="meta-label">Date of Generation:</td>
                        <td class="meta-val">{{ date('d-m-Y') }}</td>
                    </tr>
                </table>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 3%">Sl</th>
                        <th style="width: 4%">Roll</th>
                        <th style="width: 8.5%">SBTE Reg No</th>
                        <th style="width: 13%">Student Name</th>
                        <th style="width: 6%">Group</th>
                        <th style="width: 14%">Project Title</th>
                        <th style="width: 4%">Att.<br>%</th>
                        <th style="width: 5%">CIA<br>(75M)</th>
                        <th style="width: 5%">ESE<br>(50M)</th>
                        <th style="width: 6%; background-color: #dbeafe; font-weight: bold;">Grand<br>(125M)</th>
                        <th style="width: 4.5%">Grade</th>
                        <th style="width: 4%">Point</th>
                        <th style="width: 5.5%">Result</th>
                        <th style="width: 9%">Guide</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $idx => $st)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $st['roll_no'] ?? '-' }}</td>
                            <td class="font-mono-bold">{{ $st['sbte_reg_no'] }}</td>
                            <td class="align-left" style="font-weight: 700;">{{ $st['name'] }}</td>
                            <td>{{ $st['group_name'] }}</td>
                            <td class="align-left" style="font-size: 8px;">{{ $st['project_title'] }}</td>
                            <td>{{ $st['att_percentage'] }}%</td>
                            <td>{{ round($st['total_cia_75']) }}</td>
                            <td>{{ number_format($st['total_ese_50'], 1) }}</td>
                            <td class="font-mono-bold" style="background-color: #e0f2fe;">{{ number_format($st['grand_total_125'], 1) }}</td>
                            <td><span class="grade-badge">{{ $st['final_grade'] }}</span></td>
                            <td>{{ $st['final_points'] }}</td>
                            <td style="font-weight: 700; {{ $st['passed'] ? 'color: #047857;' : 'color: #b91c1c;' }}">
                                {{ $st['has_eval'] ? ($st['passed'] ? 'PASS' : 'FAILED') : 'PENDING' }}
                            </td>
                            <td class="align-left" style="font-size: 8px;">{{ $st['guide_name'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="footer-signatures" style="margin-top: 35px;">
                <tr>
                    <td style="width: 25%">
                        Course Coordinator / Guide<br>
                        (Signature)
                    </td>
                    <td style="width: 25%">
                        Internal Examiner<br>
                        ({{ $examiners['internal_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 25%">
                        External Examiner<br>
                        ({{ $examiners['external_name'] ?: 'Signature' }})
                    </td>
                    <td style="width: 25%">
                        Head of Department<br>
                        (Seal &amp; Signature)
                    </td>
                </tr>
            </table>
        </div>
    @endif

</body>
</html>
