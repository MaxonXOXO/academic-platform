<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Evaluation Scheme - {{ $coTag }}</title>
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

        .header h3 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .divider-double {
            border-top: 3px double #000;
            margin-bottom: 12px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .meta-table td {
            padding: 6px 8px;
            border: 1px solid #000;
            font-size: 12px;
        }

        .meta-label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 18%;
        }

        .scheme-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .scheme-table th, .scheme-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
            vertical-align: top;
        }

        .scheme-table th {
            background-color: #f8fafc;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .footer-sig {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 180px;
            text-align: center;
            padding-top: 5px;
        }

        @media print {
            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Scheme</button>
        <button class="btn-print" style="background:#64748b;" onclick="window.close()">Close</button>
    </div>

    <div class="a4-page">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>Department of {{ function_exists('getFullBranchName') ? getFullBranchName($classroom->branch ?? '') : ($classroom->branch ?? '') }}</h2>
            <h3>Assignment Evaluation Scheme & Hints ({{ $coTag }})</h3>
        </div>

        <div class="divider-double"></div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Course Title:</td>
                <td>{{ $batchSubject->subject_name }}</td>
                <td class="meta-label">Course Code:</td>
                <td>{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td class="meta-label">Semester / Batch:</td>
                <td>Semester {{ $batchSubject->semester }}</td>
                <td class="meta-label">Batch ID:</td>
                <td style="font-family: monospace; font-weight: bold;">{{ $batchSubject->classroom_id }}</td>
            </tr>
            <tr>
                <td class="meta-label">Academic Year:</td>
                <td>2026-2027</td>
                <td class="meta-label">Max Marks:</td>
                <td>15 Marks</td>
            </tr>
            <tr>
                <td class="meta-label">Date Created:</td>
                <td>{{ $courseFile && $courseFile->updated_at ? $courseFile->updated_at->format('d-m-Y') : date('d-m-Y') }}</td>
                <td class="meta-label">Due Date:</td>
                <td style="font-weight: bold; color: #000;">
                    {{ $courseFile && isset($courseFile->assignment_deadlines[$coTag]['deadline']) && $courseFile->assignment_deadlines[$coTag]['deadline'] ? date('d-m-Y', strtotime($courseFile->assignment_deadlines[$coTag]['deadline'])) : 'Not Specified' }}
                </td>
            </tr>
        </table>

        <table class="scheme-table">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">Q. No.</th>
                    <th style="width: 45%; font-weight: bold;" class="text-left">Question Description</th>
                    <th style="font-weight: bold;" class="text-left">Scheme of Evaluation / Answer Hints</th>
                    <th style="width: 10%;" class="text-center">Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $idx => $q)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-left">{{ $q['question'] }}</td>
                        <td class="text-left">{{ $q['scheme'] ?: '—' }}</td>
                        <td class="text-center">{{ $q['marks'] ?? 5 }}M</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px; font-style: italic;">No evaluation scheme defined for this assignment yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-sig">
            <div class="sig-line">Faculty In-charge</div>
            <div class="sig-line">Head of Department</div>
        </div>
    </div>

</body>
</html>
