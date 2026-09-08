<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Plan Printout - {{ $subject->subject_code }}</title>
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
            background-color: #f1f5f9;
            color: #000;
            font-size: 12px;
            line-height: 1.3;
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
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #374151;
        }

        .header h3 {
            font-size: 12px;
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
            font-size: 12px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: bold;
            width: 15%;
        }

        .meta-value {
            width: 35%;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
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
            font-size: 11px;
            vertical-align: middle;
        }

        .content-table th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            text-align: left;
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
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            margin-top: 40px;
        }

        .co-tag {
            display: inline-block;
            padding: 2px 4px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
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
        <button class="btn-print" onclick="window.print()">Print Document</button>
        <button class="btn-print" style="background:#4b5563;" onclick="window.close()">Close Window</button>
    </div>

    <div class="a4-page">
        <div class="header">
            <h1>Carmel Polytechnic College</h1>
            <h2>Department of {{ $fullDepartment }}</h2>
            <h3>Syllabus Planner &amp; Lesson Scheduler</h3>
        </div>

        <div class="divider-double"></div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Subject Code:</td>
                <td class="meta-value">{{ $subject->subject_code }}</td>
                <td class="meta-label">Semester:</td>
                <td class="meta-value">S{{ $subject->semester }}</td>
            </tr>
            <tr>
                <td class="meta-label">Subject Name:</td>
                <td class="meta-value">{{ $subject->subject_name }}</td>
                <td class="meta-label">Classroom:</td>
                <td class="meta-value">
                    {{ $subject->classroom->name ?? $subject->classroom_id }}
                    @if(str_contains($subject->classroom_id, 'LET'))
                        <span style="background:#f3e8ff; border:1px solid #c084fc; color:#6b21a8; font-weight:bold; font-size:10px; padding:1px 4px; border-radius:3px; margin-left:5px;">LATERAL ENTRY (LET)</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="meta-label">Faculty:</td>
                <td class="meta-value">{{ $staffName ?: 'Assigned Faculty' }}</td>
                <td class="meta-label">Report Date:</td>
                <td class="meta-value">{{ $currentDate }}</td>
            </tr>
        </table>

        <div class="report-title">Lesson Planner &amp; Execution Report</div>

        @php
            $hasSubBatches = $plans->contains(function($p) {
                return !empty($p->sub_batch) && $p->sub_batch !== 'Whole';
            }) || ($subject->subject_type ?? '') === 'practical' || (str_contains(strtolower($subject->subject_code ?? ''), 'p'));
        @endphp

        <table class="content-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 6%;">Day #</th>
                    @if($hasSubBatches)
                        <th class="text-center" style="width: 10%;">Batch</th>
                    @endif
                    <th class="text-center" style="width: 8%;">CO</th>
                    <th style="width: {{ $hasSubBatches ? '38%' : '45%' }};">Topic / Content Covered</th>
                    <th class="text-center" style="width: 13%;">Proposed Date</th>
                    <th class="text-center" style="width: 13%;">Actual Date</th>
                    <th class="text-center" style="width: 6%;">Hours</th>
                    <th class="text-center" style="width: 6%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                    @php
                        $actualDate = $plan->actual_date;
                        if (!$actualDate && isset($classLogs[$plan->id])) {
                            $actualDate = $classLogs[$plan->id]->date;
                        }
                    @endphp
                    <tr>
                        <td class="text-center font-bold">{{ $plan->day_no }}</td>
                        @if($hasSubBatches)
                            <td class="text-center font-bold" style="color:#0284c7;">{{ $plan->sub_batch ?: 'Full Batch' }}</td>
                        @endif
                        <td class="text-center">
                            @if($plan->co_id)
                                <span class="co-tag">{{ $plan->co_id }}</span>
                            @else
                                <span style="color:#6b7280;">—</span>
                            @endif
                        </td>
                        <td>{{ $plan->topic_content }}</td>
                        <td class="text-center font-mono">
                            {{ $plan->proposed_date ? date('d-m-Y', strtotime($plan->proposed_date)) : '—' }}
                        </td>
                        <td class="text-center font-mono" style="{{ $actualDate ? 'color:#15803d; font-weight:bold;' : '' }}">
                            {{ $actualDate ? date('d-m-Y', strtotime($actualDate)) : '—' }}
                        </td>
                        <td class="text-center font-mono">{{ $plan->allocated_hours ?: 1 }}</td>
                        <td class="text-center font-bold" style="text-transform:uppercase; font-size:9px; color: {{ $plan->status === 'Completed' || $actualDate ? '#16a34a' : '#4b5563' }};">
                            {{ ($plan->status === 'Completed' || $actualDate) ? 'Done' : 'Pending' }}
                        </td>
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
