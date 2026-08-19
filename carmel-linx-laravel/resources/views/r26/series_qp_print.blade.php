<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->exam_name }} - Question Paper</title>
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
            margin: 15mm 15mm;
        }

        body {
            background-color: #fff;
            color: #000;
            font-size: 13px;
            line-height: 1.4;
            padding: 10px;
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
            font-size: 13px;
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
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 13px;
            font-weight: normal;
            margin-bottom: 4px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-meta td {
            padding: 6px 10px;
            font-size: 13px;
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

        .part-header {
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 3px;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .questions-table th, .questions-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 13px;
            vertical-align: middle;
        }

        .questions-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f1f5f9;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            .print-controls {
                display: none !important;
            }
            .header-meta td.label, .questions-table th {
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <label style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; color: #334155;">Select Exam Date:</label>
        <input type="date" id="print-exam-date" onchange="document.getElementById('display-exam-date').innerText = this.value ? new Date(this.value).toLocaleDateString('en-GB') : 'N/A'" class="print-input" style="padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px; margin-right: 15px; outline: none; font-family: Arial, sans-serif;">
        <button class="btn-print" onclick="window.print()">Print Paper</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering</h1>
            <h2>Department of {{ getFullBranchName($classroom->branch ?? '') }}</h2>
            <h3>Revision 2026 Scheme - Internal Assessment Examination</h3>
            <h2 style="font-size: 15px; margin-top: 4px; letter-spacing: 0.5px;">{{ $exam->exam_name }}</h2>
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
                <td class="label">Max Marks:</td>
                <td class="value" style="font-weight: bold;">{{ $exam->max_marks }} Marks</td>
                <td class="label">Duration:</td>
                <td class="value">{{ $exam->duration_minutes }} Minutes</td>
            </tr>
            <tr>
                <td class="label">Academic Year:</td>
                <td class="value">2026-2027</td>
                <td class="label">Exam Date:</td>
                <td class="value" id="display-exam-date" style="font-weight: bold;">N/A</td>
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
                    {{ $partName }} (Answer all questions, each carries {{ $defaultMarks }} marks)
                </div>

                <table class="questions-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;">Q.No</th>
                            <th style="text-align: left;">Question Description</th>
                            <th style="width: 10%;">CO Tag</th>
                            <th style="width: 15%;">BT Level</th>
                            <th style="width: 12%;">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partQ as $idx => $q)
                            <tr>
                                <td class="text-center" style="font-weight: bold;">{{ $idx + 1 }}</td>
                                <td>{{ $q['question'] }}</td>
                                <td class="text-center" style="font-weight: bold;">{{ $q['co_tag'] ?? 'CO1' }}</td>
                                <td class="text-center">{{ $q['bt_level'] ?? 'Understand' }}</td>
                                <td class="text-center" style="font-weight: bold;">{{ $q['marks'] ?? $defaultMarks }}M</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

    </div>

</body>
</html>
