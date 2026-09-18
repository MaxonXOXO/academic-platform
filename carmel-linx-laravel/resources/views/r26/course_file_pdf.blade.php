<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course File Report - REV-2026</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #555;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .meta-label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 25%;
        }
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .checklist-table th, .checklist-table td {
            padding: 8px;
            border: 1px solid #000;
        }
        .checklist-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .status-verified {
            color: green;
            font-weight: bold;
        }
        .status-pending {
            color: red;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .footer td {
            width: 50%;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>

    @php
        $deptDisplay = $departmentName ?? (function_exists('getFullBranchName') ? getFullBranchName($classroom->branch ?? '') : ($classroom->branch ?? ''));
        if (empty($deptDisplay) || strtoupper($deptDisplay) === 'ENGINEERING') {
            $deptDisplay = 'Automobile Engineering';
        }
    @endphp
    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Department of {{ $deptDisplay }}</h2>
        <h3>Course File Index & Attainment Checklist (REV-2026)</h3>
        <div style="font-size: 10px; color: #777;">Academic Year: {{ $courseFile->academic_year }} | Status: {{ $courseFile->status }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Subject Code / Name:</td>
            <td>{{ $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</td>
            <td class="meta-label">Semester:</td>
            <td>Semester {{ $batchSubject->semester }}</td>
        </tr>
        <tr>
            <td class="meta-label">Classroom ID:</td>
            <td>{{ $batchSubject->classroom_id }}</td>
            <td class="meta-label">Date Generated:</td>
            <td>{{ date('d-m-Y H:i A') }}</td>
        </tr>
    </table>

    <h3>Index of Documents</h3>
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">No.</th>
                <th style="width: 50%;">Document Description</th>
                <th style="width: 15%;" class="text-center">Status</th>
                <th style="width: 27%;">Remarks / Action Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
                <tr>
                    <td class="text-center">{{ sprintf('%02d', $doc->document_number) }}</td>
                    <td>{{ $doc->document_name }}</td>
                    <td class="text-center">
                        @if($doc->is_checked)
                            <span class="status-verified">Verified</span>
                        @else
                            <span class="status-pending">Pending</span>
                        @endif
                    </td>
                    <td>{{ $doc->remarks ?: 'No remarks' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                <p>Prepared by:</p>
                <div class="signature-line"></div>
                <p>Subject Faculty</p>
            </td>
            <td style="text-align: right;">
                <p>Approved by:</p>
                <div class="signature-line"></div>
                <p>Head of Department (HOD)</p>
            </td>
        </tr>
    </table>

</body>
</html>
