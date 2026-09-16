<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Semester Attendance Register (Clause 10) - {{ $classroom->classroom_id }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111;
            margin: 0 auto;
            padding: 8px;
            font-size: 9.5px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #222;
            padding-bottom: 5px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 11.5px;
            margin: 0 0 2px 0;
            font-weight: 600;
        }
        .header h3 {
            font-size: 10px;
            margin: 0;
            font-weight: bold;
            color: #333;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 9px;
            font-weight: 600;
        }
        .meta-table td {
            padding: 1.5px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .report-table th, .report-table td {
            border: 1px solid #333;
            padding: 3.5px 2px;
            text-align: center;
            font-size: 8.5px;
        }
        .report-table th {
            background-color: #f1f3f5;
            font-size: 7.8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .report-table td.align-left {
            text-align: left;
            padding-left: 4px;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .status-eligible {
            color: #15803d;
            font-weight: bold;
        }
        .status-condonation {
            color: #b45309;
            font-weight: bold;
        }
        .status-detained {
            color: #b91c1c;
            font-weight: bold;
        }
        .footer-signatures {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .footer-signatures td {
            width: 33.33%;
            text-align: center;
            padding-top: 40px;
            font-weight: bold;
            font-size: 9.5px;
            vertical-align: bottom;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 12px;
        }
        .btn {
            background-color: #0284c7;
            color: #fff;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background-color: #0369a1;
        }
        @media print {
            .action-bar {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="btn" onclick="window.print()">Print Register (A4 Landscape)</button>
        <button class="btn" style="background-color: #475569;" onclick="window.close()">Close</button>
    </div>

    <div class="header">
        <h1>STATE BOARD OF TECHNICAL EDUCATION, KERALA</h1>
        <h2>CONSOLIDATED ATTENDANCE REGISTER & ESE ELIGIBILITY STATEMENT</h2>
        <h3>Regulation 2021 - Clause 10 (Minimum Attendance & Condonation Rules)</h3>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 28%;"><strong>Class / Batch:</strong> {{ $classroom->classroom_id }}</td>
            <td style="width: 28%;"><strong>Department:</strong> {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</td>
            <td style="width: 24%;"><strong>Semester:</strong> {{ $classroom->current_semester ?? 'Current' }}</td>
            <td style="width: 20%; text-align: right;"><strong>Date:</strong> {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><strong>Total Students:</strong> {{ $summary['total_students'] }}</td>
            <td><strong>Eligible (&ge;75%):</strong> <span class="status-eligible">{{ $summary['eligible_count'] }}</span></td>
            <td><strong>Condonation (65-74.9%):</strong> <span class="status-condonation">{{ $summary['condonation_count'] }}</span></td>
            <td style="text-align: right;"><strong>Detained (&lt;65%):</strong> <span class="status-detained">{{ $summary['detained_count'] }}</span></td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 2.5%;">#</th>
                <th style="width: 6%;">Roll No</th>
                <th style="width: 9%;">Reg No</th>
                <th style="width: 14%;">Student Name</th>
                @foreach($subjects as $subj)
                    <th title="{{ $subj->subject_name }}">
                        {{ $subj->subject_code }}<br>
                        <span style="font-weight: normal; font-size: 7px;">Att/Tot (%)</span>
                    </th>
                @endforeach
                <th style="width: 6%; background-color: #e2e8f0;">Tot Attd /<br>Conducted</th>
                <th style="width: 5%; background-color: #dbeafe;">Sem %</th>
                <th style="width: 7%;">Eligibility Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $st)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td style="font-weight: 600;">{{ $st['roll_no'] ?: '-' }}</td>
                    <td style="font-weight: 600;">{{ $st['sbte_reg_no'] }}</td>
                    <td class="align-left" style="font-weight: 600;">{{ $st['name'] }}</td>
                    @foreach($subjects as $subj)
                        @php
                            $sData = $st['subjects'][$subj->id] ?? null;
                        @endphp
                        <td>
                            @if($sData && $sData['conducted'] > 0)
                                {{ $sData['attended'] }}/{{ $sData['conducted'] }}<br>
                                <strong style="color: {{ $sData['percentage'] >= 75 ? '#15803d' : ($sData['percentage'] >= 65 ? '#b45309' : '#b91c1c') }}">
                                    {{ $sData['percentage'] }}%
                                </strong>
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td style="font-weight: bold; background-color: #f8fafc;">
                        {{ $st['total_attended'] }} / {{ $st['total_conducted'] }}
                    </td>
                    <td style="font-weight: bold; background-color: #eff6ff; font-size: 9px;">
                        {{ $st['overall_percentage'] }}%
                    </td>
                    <td>
                        @if($st['status'] === 'Eligible')
                            <span class="status-eligible">Eligible</span>
                        @elseif($st['status'] === 'Condonation')
                            <span class="status-condonation">Condonation</span>
                        @else
                            <span class="status-detained">Detained</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($subjects) + 7 }}">No students enrolled in this classroom.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td>
                ____________________________<br>
                Class Tutor / Advisor
            </td>
            <td>
                ____________________________<br>
                Head of the Department
            </td>
            <td>
                ____________________________<br>
                Principal
            </td>
        </tr>
    </table>

</body>
</html>
