<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Lesson Plan Timeline - {{ $batchSubject->subject_code }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
            background-color: #f8fafc;
        }
        .print-controls {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 16px;
            max-width: 1060px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-print {
            background-color: #0284c7;
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .btn-print:hover {
            background-color: #0369a1;
        }
        .btn-close {
            background-color: #475569;
            color: #ffffff;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-close:hover {
            background-color: #334155;
        }
        .report-sheet {
            background: #ffffff;
            padding: 20px 24px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 1060px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0 0 3px 0;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header h2 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 11px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 4px;
            vertical-align: middle;
        }
        .meta-label {
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }
        table.content-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 6px;
        }
        table.content-table th, table.content-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: center;
        }
        table.content-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.3px;
        }
        table.content-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-left {
            text-align: left !important;
        }
        .font-mono-bold {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
        }
        .badge-completed {
            display: inline-block;
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .badge-pending {
            display: inline-block;
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            page-break-inside: avoid;
        }
        .signature-line {
            border-top: 1.5px solid #0f172a;
            width: 220px;
            text-align: center;
            padding-top: 6px;
            font-weight: 700;
            color: #0f172a;
        }
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .print-controls {
                display: none !important;
            }
            .report-sheet {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            table.content-table th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            <span class="material-symbols-rounded" style="font-size: 16px;">print</span>
            <span>Print Document</span>
        </button>
        <button class="btn-close" onclick="window.close()">
            <span class="material-symbols-rounded" style="font-size: 16px;">close</span>
            <span>Close</span>
        </button>
    </div>

    <div class="report-sheet">
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>Practical Course Plan &amp; Laboratory Lesson Planner — SBTE Kerala (Revision 2026)</h2>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 14%;" class="meta-label">Course Title:</td>
                <td style="width: 40%;" class="meta-val">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</td>
                <td style="width: 14%;" class="meta-label">Classroom / Batch:</td>
                <td style="width: 32%;" class="meta-val">Semester {{ $classroom->current_semester ?? $batchSubject->semester }} • {{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
            </tr>
            <tr>
                <td class="meta-label">Faculty in Charge:</td>
                <td class="meta-val">{{ $facultyNames ?? ($batchSubject->getAssignedFacultyNames() ?: 'Faculty In Charge') }}</td>
                <td class="meta-label">Academic Year:</td>
                <td class="meta-val">2026-2027 &bull; Rev 2026 Regulation</td>
            </tr>
        </table>

        <table class="content-table">
            <thead>
                <tr>
                    <th style="width: 45px;">Day #</th>
                    <th style="width: 80px;">Batch</th>
                    <th class="text-left">Topic Content / Lab Experiment Details</th>
                    <th style="width: 55px;">CO</th>
                    <th style="width: 50px;">Hours</th>
                    <th style="width: 88px;">Proposed Date</th>
                    <th style="width: 88px;">Actual Date</th>
                    <th style="width: 90px;">Pedagogy</th>
                    <th style="width: 75px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessonPlans as $lp)
                @php
                    $actualDate = $lp->actual_date;
                    if (!$actualDate && isset($classLogs[$lp->id])) {
                        $actualDate = $classLogs[$lp->id]->date;
                    }
                    $isCompleted = ($lp->status === 'Completed' || !empty($actualDate));
                @endphp
                <tr>
                    <td class="font-mono-bold">{{ $lp->day_no }}</td>
                    <td><strong>{{ $lp->sub_batch ?? 'Full Batch' }}</strong></td>
                    <td class="text-left" style="font-weight: 600;">{{ $lp->topic_content }}</td>
                    <td class="font-mono-bold">{{ $lp->co_id ?: 'CO1' }}</td>
                    <td class="font-mono-bold">{{ $lp->allocated_hours ?: 1 }}</td>
                    <td class="font-mono-bold">
                        {{ $lp->proposed_date ? (preg_match('/^\d{4}-\d{2}-\d{2}$/', $lp->proposed_date) ? date('d/m/Y', strtotime($lp->proposed_date)) : $lp->proposed_date) : '—' }}
                    </td>
                    <td class="font-mono-bold" style="{{ $actualDate ? 'color: #047857; font-weight: 800;' : 'color: #64748b;' }}">
                        {{ $actualDate ? (preg_match('/^\d{4}-\d{2}-\d{2}$/', $actualDate) ? date('d/m/Y', strtotime($actualDate)) : $actualDate) : '—' }}
                    </td>
                    <td>{{ $lp->pedagogy ?: 'Practical' }}</td>
                    <td>
                        @if($isCompleted)
                            <span class="badge-completed">Completed</span>
                        @else
                            <span class="badge-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding: 24px; text-align: center; color: #64748b; font-weight: bold;">
                        No laboratory lesson planner generated yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $staffList = $assignedStaffList ?? ($batchSubject->getAssignedFacultyList() ?? []);
        @endphp
        <div class="footer">
            @if(count($staffList) > 1)
                @foreach($staffList as $staffMember)
                    <div class="signature-line">
                        {{ $staffMember }}<br>
                        <span style="font-size: 9px; font-weight: normal; text-transform: uppercase; color: #475569;">Faculty In-Charge</span>
                    </div>
                @endforeach
            @else
                <div class="signature-line">
                    {{ !empty($staffList) ? $staffList[0] : ($facultyNames ?? 'Faculty In Charge') }}<br>
                    <span style="font-size: 9px; font-weight: normal; text-transform: uppercase; color: #475569;">Faculty In Charge</span>
                </div>
            @endif
            <div class="signature-line">
                Head of Department<br>
                <span style="font-size: 9px; font-weight: normal; text-transform: uppercase; color: #475569;">Seal &amp; Signature</span>
            </div>
        </div>
    </div>

</body>
</html>
