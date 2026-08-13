<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA CO-PO Attainment Report (11 POs) - {{ $batchSubject->subject_name }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            background-color: #f8fafc;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px 0;
        }

        .a4-page {
            width: 210mm;
            max-width: 95%;
            margin: 0 auto;
            background: #ffffff;
            padding: 15mm 15mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background: #0f172a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 4px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-meta td {
            padding: 5px 8px;
            font-size: 12px;
            border: 1px solid #000;
        }
        
        .header-meta td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 18%;
        }
        
        .header-meta td.value {
            font-weight: normal;
            width: 32%;
        }

        .report-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
            vertical-align: middle;
        }

        .report-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f1f5f9;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer-signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            padding: 0 10px;
            page-break-inside: avoid;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
            html, body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            .a4-page {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .print-controls {
                display: none !important;
            }
            .header-meta td.label, .report-table th {
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Attainment Report</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering</h1>
            <h2>Department of {{ function_exists('getFullBranchName') ? getFullBranchName($classroom->branch ?? $classroom->department ?? '') : ($classroom->branch ?? '') }}</h2>
            <h3>NBA accreditation course file - CO-PO Attainment Report</h3>
        </div>

        <table class="header-meta">
            <tr>
                <td class="label">Course Title:</td>
                <td class="value">{{ $batchSubject->subject_name }}</td>
                <td class="label">Course Code:</td>
                <td class="value">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
                <td class="label">Semester:</td>
                <td class="value">Semester {{ $classroom->current_semester ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Academic Year:</td>
                <td class="value">2026 Revision</td>
                <td class="label">Report Date:</td>
                <td class="value font-bold">{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <!-- SECTION 1: COURSE OUTCOME ATTAINMENT LEVELS -->
        <div class="report-section">
            <div class="section-title">1. Course Outcome (CO) Attainment Summary</div>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Course Outcome (CO)</th>
                        <th class="text-center">Direct Attainment Level<br><span style="font-size: 9px; font-weight: normal;">(CIA & ESE - 80% Weight)</span></th>
                        <th class="text-center">Indirect Attainment Level<br><span style="font-size: 9px; font-weight: normal;">(Course Exit Survey - 20% Weight)</span></th>
                        <th class="text-center">Combined CO Attainment Level<br><span style="font-size: 9px; font-weight: normal;">(Max: 3.00)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <tr>
                            <td class="font-bold">{{ $coTag }}</td>
                            <td class="text-center font-mono">{{ number_format($directStats[$coTag]['level'], 1) }} <span style="font-size: 9px; color: #555;">({{ $directStats[$coTag]['met_percent'] }}% met)</span></td>
                            <td class="text-center font-mono">{{ number_format($indirectStats[$coTag]['level'], 2) }}</td>
                            <td class="text-center font-mono font-bold" style="background-color: #f8fafc;">{{ number_format($combinedStats[$coTag], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- SECTION 2: CO-PO MAPPING MATRIX -->
        <div class="report-section">
            <div class="section-title">2. CO-PO Correlation Matrix (11 PO Standards)</div>
            <table class="report-table text-center">
                <thead>
                    <tr>
                        <th style="text-align: left;">CO / PO</th>
                        <th>PO1</th>
                        <th>PO2</th>
                        <th>PO3</th>
                        <th>PO4</th>
                        <th>PO5</th>
                        <th>PO6</th>
                        <th>PO7</th>
                        <th>PO8</th>
                        <th>PO9</th>
                        <th>PO10</th>
                        <th>PO11</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <tr>
                            <td style="text-align: left;" class="font-bold">{{ $coTag }}</td>
                            @for($p = 1; $p <= 11; $p++)
                                @php
                                    $val = $mappings[$coTag]['PO'.$p] ?? 0;
                                @endphp
                                <td class="font-mono">{{ $val ?: '-' }}</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <span style="font-size: 10px; font-style: italic; color: #444;">* Correlation levels: 1 = Low, 2 = Medium, 3 = High, '-' = No Correlation.</span>
        </div>

        <!-- SECTION 3: FINAL PO ATTAINMENTS -->
        <div class="report-section">
            <div class="section-title">3. Final Program Outcome (PO) Attainments</div>
            <table class="report-table text-center">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>PO1</th>
                        <th>PO2</th>
                        <th>PO3</th>
                        <th>PO4</th>
                        <th>PO5</th>
                        <th>PO6</th>
                        <th>PO7</th>
                        <th>PO8</th>
                        <th>PO9</th>
                        <th>PO10</th>
                        <th>PO11</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-bold" style="text-align: left;">Attainment Level</td>
                        @for($p = 1; $p <= 11; $p++)
                            @php
                                $val = $poAttainments['PO'.$p]['value'] ?? 0.0;
                            @endphp
                            <td class="font-mono font-bold" style="background-color: #f8fafc; font-size: 12px;">
                                {{ $val > 0 ? number_format($val, 2) : '-' }}
                            </td>
                        @endfor
                    </tr>
                </tbody>
            </table>
            <p style="font-size: 10px; line-height: 1.4; color: #333;">
                <strong>Formula:</strong> PO Attainment = &sum; (Combined CO Attainment &times; Correlation Level) / &sum; Correlation Levels for mapped COs.
            </p>
        </div>

        <div class="footer-signatures">
            <div>Faculty Signature</div>
            <div>NBA Coordinator Signature</div>
            <div>Head of Department Signature</div>
        </div>

    </div>

</body>
</html>
