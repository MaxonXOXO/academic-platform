<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($type === 'attendance') Consolidated Attendance Register
        @elseif($type === 'experiments') List of Experiments
        @elseif($type === 'planner') Lesson Plan Register
        @elseif($type === 'projects') Open-Ended Project Allocations
        @endif
        - {{ $subject->subject_name }}
    </title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 10mm 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0 auto;
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px double #333;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 13px;
            margin: 0 0 5px 0;
            font-weight: normal;
        }
        .header h3 {
            font-size: 11px;
            margin: 0;
            color: #555;
            text-transform: uppercase;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .meta-info td {
            padding: 3px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: center;
            font-size: 9px;
            word-break: break-all;
        }
        .report-table th {
            background-color: #f2f2f2;
            font-size: 9px;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
            word-break: normal;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33%;
            text-align: center;
            padding-top: 40px;
            font-weight: bold;
            font-size: 11px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .close-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="no-print action-bar">
        <button class="print-btn" onclick="window.print()">Print Report</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $fullDepartment }}</h2>
        <h3>
            @if($type === 'attendance') Consolidated Practical Attendance Register
            @elseif($type === 'experiments') List of Practical Experiments (CO Mapping)
            @elseif($type === 'planner') Practical Lesson Plan Register
            @elseif($type === 'projects') Open-Ended Project Allocations
            @endif
        </h3>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%">Batch / Class:</td>
            <td width="35%" style="color: #111;">
                {{ $cleanedBatch }}
                @if(str_contains($subject->classroom_id, 'LET'))
                    <span style="background:#f3e8ff; border:1px solid #c084fc; color:#6b21a8; font-weight:bold; font-size:10px; padding:1px 4px; border-radius:3px; margin-left:5px;">LATERAL ENTRY (LET)</span>
                @endif
            </td>
            <td width="15%">Semester:</td>
            <td width="35%">Semester {{ $subject->semester }}</td>
        </tr>
        <tr>
            <td>Subject Name:</td>
            <td>{{ $subject->subject_name }} ({{ $subject->subject_code }})</td>
            <td>Date of Report:</td>
            <td>{{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <!-- SUB-REPORT: CONSOLIDATED ATTENDANCE MATRIX -->
    @if($type === 'attendance')
        @php
            $dayColumns = [];
            $groupedByDate = $attendanceLogs->groupBy('date');
            foreach ($groupedByDate as $date => $dayLogs) {
                $periods = $dayLogs->pluck('period')->unique()->sort()->values()->all();
                $periodStr = !empty($periods) ? 'P' . implode(',', $periods) : 'P1,2,3';

                $batches = $dayLogs->pluck('sub_batch')->unique()->filter()->values()->all();
                $batchLabels = array_map(function($b) {
                    if ($b === '1' || $b === 1) return 'B1';
                    if ($b === '2' || $b === 2) return 'B2';
                    return (string)$b;
                }, $batches);
                $batchStr = !empty($batchLabels) ? implode(', ', $batchLabels) : '';

                $presentSet = [];
                $absentSet = [];
                foreach ($dayLogs as $l) {
                    $p = json_decode($l->present_students ?? '[]', true) ?: [];
                    $a = json_decode($l->absent_students ?? '[]', true) ?: [];
                    foreach ($p as $r) $presentSet[$r] = true;
                    foreach ($a as $r) $absentSet[$r] = true;
                }

                $dayColumns[] = [
                    'date'           => $date,
                    'date_formatted' => date('d/m', strtotime($date)),
                    'period_str'     => $periodStr,
                    'batch_str'      => $batchStr,
                    'present_set'    => $presentSet,
                    'absent_set'     => $absentSet,
                ];
            }
            $activeCols = $dayColumns;
            $numCols = count($activeCols);
            $colWidth = $numCols > 0 ? (66 / $numCols) : 3.3;
        @endphp
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 4%">Roll</th>
                    <th style="width: 10%">Reg No</th>
                    <th style="width: 20%">Student Name</th>
                    @foreach($activeCols as $col)
                        <th style="font-size:7px; font-weight:normal; width: {{ round($colWidth, 2) }}%">
                            {{ $col['date_formatted'] }}<br>{{ $col['period_str'] }}<br>
                            @if(!empty($col['batch_str']))
                                <span style="font-size:6px; color:#555;">({{ $col['batch_str'] }})</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->roll_no ?? '-' }}</td>
                        <td style="font-family:monospace;">{{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}</td>
                        <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                        @foreach($activeCols as $col)
                            @php
                                $statusText = '-';
                                if (isset($col['present_set'][$student->reg_no])) {
                                    $statusText = 'P';
                                } elseif (isset($col['absent_set'][$student->reg_no])) {
                                    $statusText = 'A';
                                }
                            @endphp
                            <td style="font-weight: bold; color: {{ $statusText === 'A' ? 'red' : ($statusText === 'P' ? 'green' : '#777') }};">
                                {{ $statusText }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    <!-- SUB-REPORT: LIST OF EXPERIMENTS -->
    @elseif($type === 'experiments')
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 8%">Exp No</th>
                    <th style="width: 15%">CO Mapping</th>
                    <th style="width: 77%">Experiment Title &amp; Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experiments as $exp)
                    <tr>
                        <td style="font-weight:bold; font-size:11px;">{{ $exp->experiment_no }}</td>
                        <td><span style="padding:2px 6px; border:1px solid #777; background:#eee; font-weight:bold; font-size:9px; border-radius:3px;">{{ $exp->co_tag }}</span></td>
                        <td class="align-left" style="font-size:11px; font-weight:bold; color:#111;">{{ $exp->title }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding:20px; font-weight:bold; color:#888;">No practical experiments configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    <!-- SUB-REPORT: LESSON PLAN REGISTER -->
    @elseif($type === 'planner')
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 7%">Day No</th>
                    <th style="width: 10%">Batch</th>
                    <th style="width: 13%">Proposed Date</th>
                    <th style="width: 40%">Topic / Content Covered</th>
                    <th style="width: 8%">CO Map</th>
                    <th style="width: 12%">Actual Date</th>
                    <th style="width: 10%">Pedagogy</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessonPlans as $lp)
                    <tr>
                        <td style="font-weight:bold;">{{ $lp->day_no }}</td>
                        <td style="font-weight:bold; color:#0056b3;">{{ $lp->sub_batch ?? 'Full Batch' }}</td>
                        <td style="font-family:monospace;">{{ $lp->proposed_date ? date('d-m-Y', strtotime($lp->proposed_date)) : '-' }}</td>
                        <td class="align-left" style="font-weight:bold; color:#111;">{{ $lp->topic_content }}</td>
                        <td style="font-weight:bold; color:#0056b3;">{{ $lp->co_id ?? '-' }}</td>
                        <td style="font-family:monospace;">{{ $lp->actual_date ? date('d-m-Y', strtotime($lp->actual_date)) : '-' }}</td>
                        <td>{{ $lp->pedagogy ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:20px; font-weight:bold; color:#888;">No lesson planner entries generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    <!-- SUB-REPORT: OPEN-ENDED PROJECTS -->
    @elseif($type === 'projects')
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 6%">Roll No</th>
                    <th style="width: 14%">Reg No</th>
                    <th style="width: 25%">Student Name</th>
                    <th style="width: 45%">Assigned Open-Ended Project Topic</th>
                    <th style="width: 10%">Project Mark (7.5)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php
                        $eval = $evaluations->where('reg_no', $student->reg_no)->first();
                        $topic = $eval ? $eval->open_ended_topic : '';
                        $mark = $eval ? $eval->micro_project : 0.00;
                    @endphp
                    <tr>
                        <td>{{ $student->roll_no ?? '-' }}</td>
                        <td style="font-family:monospace;">{{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}</td>
                        <td class="align-left" style="font-weight: bold;">{{ $student->name }}</td>
                        <td class="align-left" style="color:#111; font-weight:500;">{{ $topic ? $topic : 'No topic assigned' }}</td>
                        <td style="font-weight:bold; font-size:11px;">{{ number_format($mark, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="footer-signatures">
        <tr>
            <td>Name & Signature of Lab Assessor</td>
            <td>Name & Signature of Coordinator</td>
            <td>Head of Department</td>
        </tr>
    </table>

</body>
</html>
