<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syllabus Lesson Planner - {{ $subject->subject_code }}</title>
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
            margin: 15mm 12mm;
        }

        body {
            background-color: #f8fafc;
            color: #000;
            font-size: 13px;
            line-height: 1.35;
        }

        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 12mm;
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
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: normal;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #1e293b;
        }

        .header h3 {
            font-size: 13px;
            font-weight: normal;
            margin-bottom: 6px;
        }

        .divider-double {
            border-top: 3px double #000;
            margin-bottom: 12px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .meta-table td {
            padding: 4px 6px;
            font-size: 13px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: normal;
            width: 15%;
        }

        .meta-value {
            width: 35%;
            font-weight: normal;
        }

        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 12px;
            text-decoration: underline;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
            vertical-align: middle;
            font-weight: normal;
        }

        .content-table th {
            background-color: #f1f5f9 !important;
            font-size: 11px;
            text-align: left;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center !important;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 220px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            margin-top: 40px;
        }

        .co-tag {
            display: inline-block;
            font-size: 11px;
        }

        @media print {
            body {
                background-color: white;
            }
            .a4-page {
                width: 100%;
                min-height: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            .print-controls {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Plan</button>
        <button class="btn-print" style="background:#475569;" onclick="window.close()">Close</button>
    </div>

    <div class="a4-page">
        @php
            $deptDisplay = $departmentName ?? $branchName ?? '';
            if (empty($deptDisplay) || strtoupper($deptDisplay) === 'ENGINEERING') {
                $deptDisplay = 'Automobile Engineering';
            }
        @endphp
        <div class="header">
            <h1>Carmel Polytechnic College</h1>
            <h2>Department of {{ $deptDisplay }}</h2>
            <h3>Syllabus Lesson Planner &amp; Execution Report (REV-2026)</h3>
        </div>

        <div class="divider-double"></div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Subject Code:</td>
                <td class="meta-value">{{ $subject->subject_code }}</td>
                <td class="meta-label">Semester:</td>
                <td class="meta-value">Semester {{ $subject->semester }}</td>
            </tr>
            <tr>
                <td class="meta-label">Subject Name:</td>
                <td class="meta-value">{{ $subject->subject_name }}</td>
                <td class="meta-label">Classroom:</td>
                <td class="meta-value">{{ $subject->classroom_id }}</td>
            </tr>
            <tr>
                <td class="meta-label">Faculty:</td>
                <td class="meta-value">{{ $lecturerName }}</td>
                <td class="meta-label">Report Date:</td>
                <td class="meta-value">{{ date('d-m-Y') }}</td>
            </tr>
        </table>

        <div class="report-title">Lesson Planner Details</div>

        <table class="content-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 7%;">Day</th>
                    <th class="text-center" style="width: 8%;">CO</th>
                    <th class="text-center" style="width: 10%;">Taxonomy</th>
                    <th style="width: 35%;">Topic / Content Scheduled</th>
                    <th class="text-center" style="width: 13%;">Proposed Date</th>
                    <th class="text-center" style="width: 13%;">Actual Date</th>
                    <th class="text-center" style="width: 7%;">Hours</th>
                    <th class="text-center" style="width: 7%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                    <tr>
                        <td class="text-center">{{ $plan->day_no }}</td>
                        <td class="text-center">
                            @if($plan->co_id)
                                <span class="co-tag">{{ $plan->co_id }}</span>
                            @else
                                <span>—</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $plan->taxonomy ?: '—' }}</td>
                        <td>{{ $plan->topic_content }}</td>
                        <td class="text-center">
                            {{ $plan->proposed_date ? date('d-m-Y', strtotime($plan->proposed_date)) : '—' }}
                        </td>
                        <td class="text-center">
                            {{ $plan->actual_date ? date('d-m-Y', strtotime($plan->actual_date)) : '—' }}
                        </td>
                        <td class="text-center">{{ $plan->allocated_hours ?: 1 }}</td>
                        <td class="text-center">{{ $plan->status ?: 'Pending' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <p>Signature of Faculty Member</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p>Signature of HOD</p>
            </div>
        </div>
    </div>

</body>
</html>
