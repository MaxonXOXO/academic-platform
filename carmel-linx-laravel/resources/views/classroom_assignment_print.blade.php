<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Printout - {{ $coTag }} - {{ $subject->subject_code }}</title>
    <!-- KaTeX for Mathematical Expressions ($...$ and $$...$$) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" onload="renderMathInElement(document.body, { delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}], throwOnError: false });"></script>
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
            margin: 20mm 15mm;
        }

        body {
            background-color: #f1f5f9;
            color: #000;
            font-size: 13px;
            line-height: 1.4;
        }

        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 15mm;
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
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .divider-double {
            border-top: 3px double #000;
            margin-bottom: 15px;
        }

        .meta-section {
            width: 100%;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .meta-item {
            width: 48%;
        }

        .divider-single {
            border-top: 1px solid #000;
            margin-bottom: 20px;
        }

        .assignment-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .assignment-title h3 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .assignment-title h4 {
            font-size: 13px;
            font-style: italic;
            font-weight: normal;
        }

        .submission-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 13px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 13px;
        }

        table.data-table th {
            font-weight: bold;
            background-color: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .cog-analysis-title {
            font-weight: bold;
            text-align: center;
            margin: 30px 0 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-row {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
        }

        .signature-col {
            text-align: center;
            width: 28%;
            font-size: 12px;
        }

        .signature-line {
            border-top: 1px dashed #000;
            margin-bottom: 4px;
            padding-top: 5px;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        /* Rubrics Specific Styles */
        .rubrics-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        table.rubrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        table.rubrics-table th, table.rubrics-table td {
            border: 1px solid #000;
            padding: 12px 10px;
            vertical-align: top;
            line-height: 1.4;
            font-size: 12px;
        }

        table.rubrics-table th {
            text-align: center;
            font-weight: bold;
            background-color: #fafafa;
        }

        table.rubrics-table td.criteria-cell {
            font-weight: bold;
            vertical-align: middle;
            text-align: center;
            background-color: #fafafa;
            width: 20%;
            font-size: 13px;
        }

        table.rubrics-table td.desc-cell {
            width: 26.6%;
        }

        @media print {
            body {
                background-color: white;
                margin: 0;
                padding: 0;
            }
            .print-controls {
                display: none;
            }
            .a4-page {
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">Print Document</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">Close</button>
    </div>

    <!-- PAGE 1: QUESTION PAPER -->
    <div class="a4-page">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE | PUNNAPRA</h1>
            <h2>DEPARTMENT OF {{ strtoupper($fullDepartment) }}</h2>
        </div>

        <div class="divider-double"></div>

        <div class="meta-section">
            <div class="meta-row">
                <div class="meta-item"><strong>Subject Code & Name:</strong> {{ $subject->subject_code }} - {{ $subject->subject_name }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Semester:</strong> Semester {{ $romanSem }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Batch:</strong> {{ $cleanedBatch }} @if(str_contains($subject->classroom_id, 'LET')) <span style="background:#f3e8ff; border:1px solid #c084fc; color:#6b21a8; font-weight:bold; font-size:10px; padding:1px 4px; border-radius:3px; margin-left:5px;">LATERAL ENTRY (LET)</span> @endif</div>
                <div class="meta-item" style="text-align: right;"><strong>CO Number:</strong> {{ $coTag }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Assessment Year:</strong> {{ $assessmentYear }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Maximum Marks:</strong> 20</div>
            </div>
        </div>

        <div class="divider-single"></div>

        <div class="assignment-title">
            <h3>Assignment &ndash; {{ substr($coTag, 2) ?: '1' }} , {{ $coTag }}</h3>
            <h4>Topic: {{ $topicName }}</h4>
        </div>

        <div class="submission-row">
            <span>Last Date of Submission: {{ $dueDate }}</span>
            <span>Maximum Marks: 20</span>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">Q. No.</th>
                    <th style="width: 67%;">Questions</th>
                    <th style="width: 15%; text-align: center;">BT Level</th>
                    <th style="width: 10%; text-align: center;">Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $q['q_no'] }}.</td>
                        <td style="vertical-align: top; line-height: 1.5;">
                            <div class="q-text-body">{!! nl2br(e($q['question'])) !!}</div>
                            @if(!empty($q['image_url']))
                                <div class="q-figure-box" style="margin-top: 10px; text-align: center;">
                                    <img src="{{ asset($q['image_url']) }}" alt="Figure for Q. {{ $q['q_no'] }}" style="max-width: 85%; max-height: 240px; object-fit: contain; border: 1px solid #ccc; padding: 4px; background: #fff; border-radius: 4px; display: inline-block;">
                                    <div style="font-size: 11px; font-style: italic; color: #444; margin-top: 3px;">Figure for Q. {{ $q['q_no'] }}</div>
                                </div>
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: top; font-weight: bold;">{{ $q['bt_level'] }}</td>
                        <td class="text-center" style="vertical-align: top;">{{ $q['marks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px; color: #666;">No assignment questions generated for this outcome.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="cog-analysis-title">Cognitive level wise Question Analysis</div>
        <table class="data-table" style="width: 80%; margin: 10px auto;">
            <thead>
                <tr>
                    <th colspan="3" class="text-center" style="padding: 4px;">Cognitive Level</th>
                    <th rowspan="2" class="text-center" style="padding: 4px; vertical-align: middle; width: 30%;">No. of Questions</th>
                </tr>
                <tr>
                    <th class="text-center" style="padding: 4px; width: 23%;">Remember</th>
                    <th class="text-center" style="padding: 4px; width: 23%;">Understand</th>
                    <th class="text-center" style="padding: 4px; width: 23%;">Apply</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center" style="padding: 6px; font-weight: bold;">{{ $rememberCount > 0 ? $rememberCount : '' }}</td>
                    <td class="text-center" style="padding: 6px; font-weight: bold;">{{ $understandCount > 0 ? $understandCount : '' }}</td>
                    <td class="text-center" style="padding: 6px; font-weight: bold;">{{ $applyCount > 0 ? $applyCount : '' }}</td>
                    <td class="text-center" style="padding: 6px; font-weight: bold;">{{ $totalQuestions }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right" style="padding: 6px; font-weight: bold;">Total Marks =</td>
                    <td class="text-center" style="padding: 6px; font-weight: bold;">20</td>
                </tr>
            </tbody>
        </table>

        <div class="signature-row">
            <div class="signature-col">
                <div class="signature-line">Prepared By</div>
                <div>Course Coordinator</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">Verified By</div>
                <div>Module Coordinator</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">Approved By</div>
                <div>HOD</div>
            </div>
        </div>
    </div>

    <!-- PAGE 2: RUBRICS -->
    <div class="a4-page page-break">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE | PUNNAPRA</h1>
            <h2>DEPARTMENT OF {{ strtoupper($fullDepartment) }}</h2>
        </div>

        <div class="divider-double"></div>

        <div class="meta-section">
            <div class="meta-row">
                <div class="meta-item"><strong>Subject Code & Name:</strong> {{ $subject->subject_code }} - {{ $subject->subject_name }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Semester:</strong> Semester {{ $romanSem }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Batch:</strong> {{ $cleanedBatch }}</div>
                <div class="meta-item" style="text-align: right;"><strong>CO Number:</strong> {{ $coTag }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Assessment Year:</strong> {{ $assessmentYear }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Maximum Marks:</strong> 20</div>
            </div>
        </div>

        <div class="divider-single"></div>

        <div class="rubrics-title">RUBRICS</div>

        <table class="rubrics-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Criteria</th>
                    <th style="width: 26.6%;">Excellent<br>(20 marks)</th>
                    <th style="width: 26.6%;">Good<br>(15 &ndash; 19 marks)</th>
                    <th style="width: 26.6%;">Satisfactory<br>(10 &ndash; 14 marks)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="criteria-cell">Content</td>
                    <td class="desc-cell">Covers the given topic in depth with details and examples<br><strong>(10 marks)</strong></td>
                    <td class="desc-cell">Includes essential knowledge about the topic<br><strong>(7.5 marks)</strong></td>
                    <td class="desc-cell">Contents are minimal or with factual errors<br><strong>(5 marks)</strong></td>
                </tr>
                <tr>
                    <td class="criteria-cell">Organization</td>
                    <td class="desc-cell">Well organization of the contents<br><strong>(6 marks)</strong></td>
                    <td class="desc-cell">Contents organized partially<br><strong>(4.5 marks)</strong></td>
                    <td class="desc-cell">Not clearly organized<br><strong>(3 marks)</strong></td>
                </tr>
                <tr>
                    <td class="criteria-cell">Timely Submission</td>
                    <td class="desc-cell">Student submitted the assignment within the due date<br><strong>(4 marks)</strong></td>
                    <td class="desc-cell">Student submitted the assignment next day after the due date<br><strong>(3 marks)</strong></td>
                    <td class="desc-cell">Student submitted the assignment long days after the due date<br><strong>(2 marks)</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PAGE 3: SCHEME OF EVALUATION -->
    <div class="a4-page page-break">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE | PUNNAPRA</h1>
            <h2>DEPARTMENT OF {{ strtoupper($fullDepartment) }}</h2>
        </div>

        <div class="divider-double"></div>

        <div class="meta-section">
            <div class="meta-row">
                <div class="meta-item"><strong>Subject Code & Name:</strong> {{ $subject->subject_code }} - {{ $subject->subject_name }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Semester:</strong> Semester {{ $romanSem }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Batch:</strong> {{ $cleanedBatch }} @if(str_contains($subject->classroom_id, 'LET')) <span style="background:#f3e8ff; border:1px solid #c084fc; color:#6b21a8; font-weight:bold; font-size:10px; padding:1px 4px; border-radius:3px; margin-left:5px;">LATERAL ENTRY (LET)</span> @endif</div>
                <div class="meta-item" style="text-align: right;"><strong>CO Number:</strong> {{ $coTag }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-item"><strong>Assessment Year:</strong> {{ $assessmentYear }}</div>
                <div class="meta-item" style="text-align: right;"><strong>Maximum Marks:</strong> 20</div>
            </div>
        </div>

        <div class="divider-single"></div>

        <div class="rubrics-title" style="margin-bottom: 20px;">SCHEME OF EVALUATION</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%; text-align: center;">Q. No.</th>
                    <th style="width: 50%;">Answer Outline / Key Points</th>
                    <th style="width: 25%; text-align: center;">Detailed Mark Split-up</th>
                    <th style="width: 15%; text-align: center;">Total Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    <tr>
                        <td class="text-center" style="vertical-align: top; font-weight: bold;">{{ $q['q_no'] }}.</td>
                        <td style="vertical-align: top; line-height: 1.5;">
                            <strong>Key Points for question:</strong><br>
                            {{ $q['question'] }}<br><br>
                            - Correct definition, definition/explanation of key terms.<br>
                            - Relevant block diagram/circuit diagram/equations (where applicable).<br>
                            - Appropriate examples and descriptions.
                        </td>
                        <td style="vertical-align: top; line-height: 1.5;">
                            - Explanation/Theory: {{ $q['marks'] - 2 > 0 ? $q['marks'] - 2 : 1 }} Marks<br>
                            - Diagram/Equations: {{ $q['marks'] > 2 ? 2 : 0 }} Marks
                        </td>
                        <td class="text-center" style="vertical-align: top; font-weight: bold;">{{ $q['marks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px; color: #666;">No questions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-row" style="margin-top: 100px;">
            <div class="signature-col">
                <div class="signature-line">Prepared By</div>
                <div>Course Coordinator</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">Verified By</div>
                <div>Module Coordinator</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">Approved By</div>
                <div>HOD</div>
            </div>
        </div>
    </div>

</body>
</html>
