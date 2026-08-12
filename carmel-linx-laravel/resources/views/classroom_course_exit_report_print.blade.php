<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Exit Survey Report — {{ $subjectCode }}</title>
    <style>
        :root {
            --primary: #0f766e;
            --primary-dark: #0d5e58;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.4;
            font-size: 13px;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 16mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: var(--primary-dark);
        }

        .header {
            text-align: center;
            border-bottom: 2.5px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            color: var(--primary-dark);
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header h2 {
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .header h3 {
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 16px;
            border: 1px solid var(--border);
            padding: 10px 12px;
            border-radius: 6px;
            background-color: var(--bg-light);
        }

        .details-item {
            display: flex;
            flex-direction: column;
        }

        .details-label {
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .details-val {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 1px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 5px 10px;
            font-size: 12.5px;
            font-weight: 800;
            margin-top: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-radius: 4px;
            letter-spacing: 0.3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: var(--bg-light);
            color: var(--primary-dark);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 2px solid var(--border);
            padding: 7px 8px;
            text-align: left;
        }

        td {
            padding: 6.5px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            color: var(--text-main);
        }

        .score-badge {
            background-color: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 800;
            border: 1px solid var(--border);
            font-size: 11.5px;
        }

        .attainment-badge {
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
        }

        .level-high { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .level-med  { background: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
        .level-low  { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
        .level-nil  { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        /* SVG Bar Chart Styling */
        .chart-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            background: white;
            margin-bottom: 16px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .chart-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
        }

        .chart-legend {
            display: flex;
            gap: 12px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 2px;
        }

        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .sig-box {
            border-top: 1.5px dashed var(--border);
            text-align: center;
            padding-top: 6px;
            font-size: 10.5px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 25px;
        }

        @media print {
            body {
                background: white;
                color: black;
            }
            .a4-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
            }
            .print-controls {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">🖨️ Print A4 Report</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">Close</button>
    </div>

    <div class="a4-container">
        <!-- Header -->
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>DEPARTMENT OF {{ strtoupper($branch) }}</h2>
            <h3>COURSE EXIT SURVEY & INDIRECT CO ATTAINMENT REPORT ({{ $revision }})</h3>
        </div>

        <!-- Institutional Details Grid -->
        <div class="details-grid">
            <div class="details-item" style="grid-column: span 2;">
                <span class="details-label">Subject & Code</span>
                <span class="details-val">{{ $subjectCode }} — {{ $subject->subject_name }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Faculty Name</span>
                <span class="details-val">{{ $facultyName }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Batch Year</span>
                <span class="details-val">{{ $batchYear }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Academic Year / Sem</span>
                <span class="details-val">{{ $academicYear }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Branch / Department</span>
                <span class="details-val">{{ $branch }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Student Participation</span>
                <span class="details-val">{{ $respondedCount }} / {{ $totalStudents }} Students ({{ $totalStudents > 0 ? round(($respondedCount / $totalStudents) * 100, 1) : 0 }}%)</span>
            </div>
            <div class="details-item">
                <span class="details-label">Report Date</span>
                <span class="details-val">{{ date('d-m-Y') }}</span>
            </div>
        </div>

        <!-- Section 1: Indirect CO Attainment Summary -->
        <div class="section-title">1. Course Outcome (CO) Indirect Attainment Summary & 3-2-1 Scale Analysis</div>

        <div style="margin-bottom: 10px; padding: 6px 10px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 11px; color: #334155;">
            <strong>NBA 3-Point Attainment Benchmark Scale:</strong> &nbsp;
            <span style="display: inline-block; margin-right: 12px;"><strong>Level 3 (High):</strong> &ge; 70% (&ge; 2.10/3.0)</span>
            <span style="display: inline-block; margin-right: 12px;"><strong>Level 2 (Moderate):</strong> 60% &ndash; 69% (1.80 &ndash; 2.09)</span>
            <span style="display: inline-block; margin-right: 12px;"><strong>Level 1 (Low):</strong> 50% &ndash; 59% (1.50 &ndash; 1.79)</span>
            <span style="display: inline-block;"><strong>Level 0 (Nil):</strong> &lt; 50% (&lt; 1.50)</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">CO Code</th>
                    <th style="width: 48%;">Course Outcome (CO) Description</th>
                    <th style="width: 14%; text-align: center;">Average Score (1-3)</th>
                    <th style="width: 13%; text-align: center;">Attainment (%)</th>
                    <th style="width: 15%; text-align: center;">NBA Attainment Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coAttainments as $coKey => $coData)
                <tr>
                    <td style="font-weight: 800; color: var(--primary-dark);">{{ $coKey }}</td>
                    <td style="font-weight: 600;">{{ $coData['name'] }}</td>
                    <td style="text-align: center;">
                        <span class="score-badge">
                            {{ number_format($coData['avg'], 2) }} / 3.00
                        </span>
                    </td>
                    <td style="text-align: center; font-weight: 800; color: var(--primary-dark);">{{ $coData['percent'] }}%</td>
                    <td style="text-align: center;">
                        <span class="attainment-badge {{ $coData['level'] == 3 ? 'level-high' : ($coData['level'] == 2 ? 'level-med' : ($coData['level'] == 1 ? 'level-low' : 'level-nil')) }}">
                            Level {{ $coData['level'] }} ({{ $coData['level'] == 3 ? 'High' : ($coData['level'] == 2 ? 'Moderate' : ($coData['level'] == 1 ? 'Low' : 'Nil')) }})
                        </span>
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid var(--border);">
                    <td style="color: var(--primary-dark);">OVERALL</td>
                    <td>Overall Course Outcome Exit Survey Indirect Attainment Average</td>
                    <td style="text-align: center;">
                        <span class="score-badge" style="background: #0f766e; color: white;">
                            {{ number_format($overallAvg, 2) }} / 3.00
                        </span>
                    </td>
                    <td style="text-align: center; color: var(--primary-dark); font-size: 13px;">{{ $overallPct }}%</td>
                    <td style="text-align: center;">
                        <span class="attainment-badge {{ $overallLevel == 3 ? 'level-high' : ($overallLevel == 2 ? 'level-med' : 'level-low') }}">
                            Level {{ $overallLevel }} Overall
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Section 2: Visual Chart / Graph -->
        <div class="section-title">2. Graphical Representation of Indirect CO Attainments</div>
        <div class="chart-card">
            <div class="chart-header">
                <span class="chart-title">Indirect Course Outcome Attainment Bar Chart (Scale 0.0 - 3.0)</span>
                <div class="chart-legend">
                    <div class="legend-item"><div class="legend-dot" style="background:#10b981;"></div> Level 3 (&ge;70%)</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div> Level 2 (60-69%)</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#ef4444;"></div> Level 1 (&lt;60%)</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#0f766e;"></div> Overall Average</div>
                </div>
            </div>

            <!-- Crisp SVG Vector Chart -->
            <svg width="100%" height="160" viewBox="0 0 520 160" style="overflow: visible;">
                <!-- Grid Lines -->
                <line x1="40" y1="20" x2="500" y2="20" stroke="#e2e8f0" stroke-dasharray="3,3" />
                <text x="32" y="24" font-size="9" fill="#64748b" text-anchor="end">3.0</text>

                <line x1="40" y1="53" x2="500" y2="53" stroke="#e2e8f0" stroke-dasharray="3,3" />
                <text x="32" y="57" font-size="9" fill="#64748b" text-anchor="end">2.25</text>

                <line x1="40" y1="86" x2="500" y2="86" stroke="#0f766e" stroke-width="1.5" stroke-dasharray="4,4" />
                <text x="32" y="90" font-size="9" fill="#0f766e" font-weight="bold" text-anchor="end">1.8 (60%)</text>

                <line x1="40" y1="120" x2="500" y2="120" stroke="#cbd5e1" stroke-width="1" />
                <text x="32" y="124" font-size="9" fill="#64748b" text-anchor="end">0.0</text>

                @php
                    $chartItems = array_merge($coAttainments, [
                        'OVERALL' => [
                            'avg' => $overallAvg,
                            'percent' => $overallPct,
                            'level' => $overallLevel
                        ]
                    ]);
                    $startX = 70;
                    $stepX = 85;
                    $barWidth = 36;
                    $maxH = 100; // Height corresponding to 3.0 score
                @endphp

                @foreach($chartItems as $label => $cData)
                    @php
                        $score = $cData['avg'];
                        $h = ($score / 3.0) * $maxH;
                        $y = 120 - $h;
                        $color = $label === 'OVERALL' ? '#0f766e' : ($cData['level'] == 3 ? '#10b981' : ($cData['level'] == 2 ? '#f59e0b' : '#ef4444'));
                    @endphp
                    <!-- Bar -->
                    <rect x="{{ $startX }}" y="{{ $y }}" width="{{ $barWidth }}" height="{{ $h }}" fill="{{ $color }}" rx="4" ry="4" />
                    <!-- Score Label on Top of Bar -->
                    <text x="{{ $startX + ($barWidth / 2) }}" y="{{ $y - 5 }}" font-size="9.5" font-weight="bold" fill="#0f172a" text-anchor="middle">
                        {{ number_format($score, 2) }}
                    </text>
                    <!-- X-Axis Label -->
                    <text x="{{ $startX + ($barWidth / 2) }}" y="138" font-size="10" font-weight="bold" fill="#334155" text-anchor="middle">
                        {{ $label }}
                    </text>
                    <!-- Percentage Label below -->
                    <text x="{{ $startX + ($barWidth / 2) }}" y="150" font-size="8.5" fill="#64748b" text-anchor="middle">
                        ({{ $cData['percent'] }}%)
                    </text>
                    @php $startX += $stepX; @endphp
                @endforeach
            </svg>
        </div>

        <!-- Section 3: Question-wise 3-2-1 Scale Response Breakdown -->
        <div class="section-title">3. Detailed Question-wise Scale Responses Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 6%;">No.</th>
                    <th style="width: 50%;">Evaluation Criterion Question Context</th>
                    <th style="width: 8%; text-align: center;">3 (High)</th>
                    <th style="width: 8%; text-align: center;">2 (Med)</th>
                    <th style="width: 8%; text-align: center;">1 (Low)</th>
                    <th style="width: 10%; text-align: center;">Mean Score</th>
                    <th style="width: 10%; text-align: center;">Satisfaction</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $qContexts = [
                        'co1_q1' => 'Q1 (CO1): Course knowledge, core academic principles & fundamentals.',
                        'co1_q2' => 'Q2 (CO1): Outcome mapping, scope definitions, and basic terms.',
                        'co2_q3' => 'Q3 (CO2): Analytical reasoning and logical analysis capabilities.',
                        'co2_q4' => 'Q4 (CO2): Troubleshooting models and drafting sub-system designs.',
                        'co3_q5' => 'Q5 (CO3): Practical operations, laboratory kits & programming labs.',
                        'co3_q6' => 'Q6 (CO3): Safety standards, limits, and instrumentation regulations.',
                        'co4_q7' => 'Q7 (CO4): Thorough continuous assessments, assignments & exams.',
                        'co4_q8' => 'Q8 (CO4): Professional ethics, environmental & social concerns.',
                        'co4_q9' => 'Q9 (CO4): Motivation for self-learning and modern advancements.',
                        'co_overall_q10' => 'Q10 (Overall): Overall course delivery satisfaction & guidance.'
                    ];
                    $idx = 1;
                @endphp
                @foreach($qContexts as $fieldKey => $descText)
                <tr>
                    <td style="font-weight: 800;">{{ $idx++ }}</td>
                    <td style="font-weight: 500;">{{ $descText }}</td>
                    <td style="text-align: center; color: #166534; font-weight: 700;">{{ $scaleCounts[$fieldKey]['high'] ?? 0 }}</td>
                    <td style="text-align: center; color: #854d0e; font-weight: 700;">{{ $scaleCounts[$fieldKey]['med'] ?? 0 }}</td>
                    <td style="text-align: center; color: #991b1b; font-weight: 700;">{{ $scaleCounts[$fieldKey]['low'] ?? 0 }}</td>
                    <td style="text-align: center;"><span class="score-badge">{{ number_format($averages[$fieldKey], 2) }}</span></td>
                    <td style="text-align: center; font-weight: 800; color: var(--primary-dark);">{{ $satisfaction[$fieldKey] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Signatures block -->
        <div class="signature-section">
            <div class="sig-box">
                Signature of Course Faculty<br>
                <span style="font-size: 9px; font-weight: 600; text-transform: none;">Name: {{ $facultyName }}</span>
            </div>
            <div class="sig-box">
                Signature of HOD<br>
                <span style="font-size: 9px; font-weight: 600;">Department of {{ $branch }}</span>
            </div>
            <div class="sig-box">
                Academic Quality Chair / Principal<br>
                <span style="font-size: 9px; font-weight: 600;">Carmel Polytechnic College</span>
            </div>
        </div>
    </div>

</body>
</html>
