<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical Lesson Plan Timeline - {{ $batchSubject->subject_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #555;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Practical Course Plan & Lesson Planner - Revision 2026</h2>
    </div>

    <div class="meta-info">
        <div>
            <strong>Subject:</strong> {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})<br>
            <strong>Class/Sem:</strong> Sem I - Diploma in Engineering
        </div>
        <div style="text-align: right;">
            <strong>Academic Year:</strong> 2026-2027<br>
            <strong>Faculty:</strong> {{ $facultyNames ?? ($batchSubject->getAssignedFacultyNames() ?? 'Faculty In Charge') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 60px;">Day No</th>
                <th style="width: 90px;">Sub-Batch</th>
                <th class="text-left">Topic Content / Lab Experiment Details</th>
                <th style="width: 70px;">Mapped CO</th>
                <th style="width: 70px;">Allocated Hours</th>
                <th style="width: 100px;">Pedagogy / Activity</th>
                <th style="width: 90px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessonPlans as $lp)
            <tr>
                <td>{{ $lp->day_no }}</td>
                <td><strong style="color: #444;">{{ $lp->sub_batch ?? 'Whole Class' }}</strong></td>
                <td class="text-left bold">{{ $lp->topic_content }}</td>
                <td>{{ $lp->co_id }}</td>
                <td>{{ $lp->allocated_hours }}</td>
                <td>{{ $lp->pedagogy }}</td>
                <td style="font-weight: bold; color: {{ $lp->status == 'Completed' ? 'green' : 'orange' }};">{{ $lp->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 20px; text-align: center; color: #777;">No planner generated yet.</td>
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
                    <span style="font-size: 8px; font-weight: normal; text-transform: uppercase;">Faculty In-Charge</span>
                </div>
            @endforeach
        @else
            <div class="signature-line">
                {{ !empty($staffList) ? $staffList[0] : ($facultyNames ?? 'Faculty In Charge') }}<br>
                <span style="font-size: 8px; font-weight: normal; text-transform: uppercase;">Faculty In Charge</span>
            </div>
        @endif
        <div class="signature-line">
            Head of Department
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
