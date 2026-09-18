<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->exam_name }} - Evaluation Scheme & Answer Key</title>
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
            background-color: #fff;
            color: #000;
            font-size: 13px;
            line-height: 1.4;
        }

        .a4-page {
            width: 100%;
            margin: 0 auto;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
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
            font-size: 13px;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .header-meta td {
            padding: 4px 8px;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #000;
        }

        .part-header {
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 3px;
            font-size: 14px;
        }

        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .questions-table th, .questions-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
            vertical-align: top;
        }

        .questions-table th {
            font-weight: bold;
            text-align: center;
        }

        @media print {
            .print-controls {
                display: none !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js" onload="renderMathInElement(document.body, {delimiters: [{left: '$$', right: '$$', display: true},{left: '$', right: '$', display: false},{left: '\\(', right: '\\)', display: false},{left: '\\[', right: '\\]', display: true}]});"></script>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Scheme</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        @php
            $deptDisplay = $departmentName ?? (function_exists('getFullBranchName') ? getFullBranchName($classroom->branch ?? '') : ($classroom->branch ?? ''));
            if (empty($deptDisplay) || strtoupper($deptDisplay) === 'ENGINEERING') {
                $deptDisplay = 'Automobile Engineering';
            }
        @endphp
        <div class="header">
            <h1>Carmel Polytechnic College</h1>
            <h2>Department of {{ $deptDisplay }}</h2>
            <h2>Revision 2026 Scheme - Theory Examination</h2>
            <h2 style="font-weight: bold; margin-top: 8px;">{{ $exam->exam_name }}</h2>
            <h2 style="font-weight: bold; color: #475569;">EVALUATION SCHEME & ANSWER KEY</h2>
        </div>

        <table class="header-meta">
            <tr>
                <td style="width: 25%;">Course Title:</td>
                <td style="width: 45%; font-weight: normal;">{{ $batchSubject->subject_name }}</td>
                <td style="width: 15%;">Course Code:</td>
                <td style="width: 15%; font-weight: normal;">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td>Batch ID / Classroom:</td>
                <td style="font-weight: normal;">{{ $classroom->classroom_name ?? ($batchSubject->classroom_id ?? 'N/A') }}</td>
                <td>Max Marks:</td>
                <td style="font-weight: normal;">{{ $exam->max_marks }} Marks</td>
            </tr>
            <tr>
                <td>Academic Year:</td>
                <td style="font-weight: normal;">2026-2027</td>
                <td>Duration:</td>
                <td style="font-weight: normal;">{{ $exam->duration_minutes }} Minutes</td>
            </tr>
        </table>

        @php
            $parts = ['Part A' => 1, 'Part B' => 3, 'Part C' => 7];
            $questions = is_string($exam->questions) ? json_decode($exam->questions, true) : $exam->questions;
        @endphp

        @foreach($parts as $partName => $defaultMarks)
            @php
                $partQ = $questions[$partName] ?? [];
            @endphp
            @if(count($partQ) > 0)
                <div class="part-header">
                    {{ $partName }} Evaluation Rubrics
                </div>

                <table class="questions-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 40%;">Question</th>
                            <th style="width: 40%;">Scheme of Evaluation / Answer Key Hints</th>
                            <th style="width: 15%;">Max Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partQ as $idx => $q)
                            <tr>
                                <td style="text-align: center; font-weight: bold; vertical-align: top;">{{ $idx + 1 }}</td>
                                <td style="vertical-align: top; line-height: 1.4;">
                                    <div>{{ $q['question'] }}</div>
                                    @if(!empty($q['image_url']))
                                        <div style="margin-top: 6px; margin-bottom: 4px; text-align: center;">
                                            <img src="{{ $q['image_url'] }}" style="max-height: 160px; max-width: 95%; border: 1px solid #cbd5e1; padding: 2px; object-fit: contain;" alt="Figure">
                                        </div>
                                    @endif
                                </td>
                                <td style="vertical-align: top;">{{ $q['scheme'] ?: 'Award marks based on correctness of key steps.' }}</td>
                                <td style="text-align: center; font-weight: bold; vertical-align: top;">{{ $q['marks'] ?? $defaultMarks }} Marks</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

    </div>

</body>
</html>
