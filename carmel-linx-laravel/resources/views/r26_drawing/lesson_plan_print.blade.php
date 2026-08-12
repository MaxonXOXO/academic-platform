<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Plan - {{ $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 10mm;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 8px;
            color: #000;
            background: #fff;
            font-size: 10px;
            line-height: 1.25;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 800;
        }

        .header h2 {
            margin: 3px 0 0 0;
            font-size: 12px;
            color: #111;
            font-weight: 700;
            text-transform: uppercase;
        }

        .header h3 {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #222;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .meta-table td {
            border: 1px solid #000 !important;
            padding: 5px 8px;
            background-color: #fcfcfc;
            vertical-align: middle;
        }

        table.plan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-top: 4px;
        }

        table.plan-table, table.plan-table th, table.plan-table td {
            border: 1px solid #000 !important;
        }

        table.plan-table th, table.plan-table td {
            padding: 4px 6px;
            vertical-align: middle;
        }

        table.plan-table th {
            background-color: #e5e7eb;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .bold { font-weight: 700; }

        .topic-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-weight: normal;
            font-size: 9.5px;
            line-height: 1.3;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            page-break-inside: avoid;
        }

        .signature-line {
            border-top: 1.5px solid #000;
            width: 210px;
            text-align: center;
            padding-top: 4px;
            margin-top: 35px;
            font-weight: 600;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            table.plan-table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 10px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #0284c7; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px;">
            🖨️ Print Lesson Plan (A4 Landscape)
        </button>
    </div>

    <!-- Header Title Section -->
    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>Department of {{ strtoupper($classroom?->branch ?? $batchSubject?->branch ?? 'Mechanical') }} Engineering</h2>
        <h3>LESSON PLAN - {{ $batchSubject->syllabus_revision_code ?? 'REVISION 2026' }} ({{ $drawingCourseFile?->contact_hours ?? 45 }} CONTACT HOURS)</h3>
    </div>

    <!-- Metadata Grid Table -->
    <table class="meta-table">
        <tr>
            <td style="width: 38%;"><strong>College Name:</strong> Carmel Polytechnic College, Alappuzha</td>
            <td style="width: 32%;"><strong>Department:</strong> {{ $classroom?->branch ?? $batchSubject?->branch ?? 'Mechanical' }} Engineering</td>
            <td style="width: 30%;"><strong>Assessment Year:</strong> 2026 - 2027</td>
        </tr>
        <tr>
            <td><strong>Subject Code & Name:</strong> {{ $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</td>
            <td><strong>Batch:</strong> {{ !empty($classroom?->batch_year) ? $classroom->batch_year.'-'.($classroom->batch_year+3) : ($classroom?->classroom_id ?? $batchSubject->classroom_id ?? '2026-2029') }}</td>
            <td><strong>Semester:</strong> Semester {{ $classroom?->current_semester ?? $batchSubject->semester ?? 'I' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Faculty In Charge:</strong> {{ $staff->name ?? 'Lecturer In Charge' }}</td>
            <td><strong>Total Duration:</strong> {{ $drawingCourseFile?->contact_hours ?? 45 }} Hours</td>
        </tr>
    </table>

    <!-- Main Lesson Plan Grid Table -->
    <table class="plan-table">
        <thead>
            <tr>
                <th style="width: 40px;">Hr #</th>
                <th style="width: 80px;">Proposed Date</th>
                <th style="width: 80px;">Actual Date</th>
                <th class="text-left">Topic & Practical Exercise Content</th>
                <th style="width: 40px;">CO</th>
                <th style="width: 45px;">Hrs</th>
                <th style="width: 150px;">Pedagogy / Assessment</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessonPlans as $lp)
            <tr>
                <td class="text-center bold">#{{ $lp->day_no }}</td>
                <td class="text-center">{{ $lp->proposed_date ?: ($lp->planned_date ?: '-') }}</td>
                <td class="text-center">{{ $lp->actual_date ?: '-' }}</td>
                <td class="text-left topic-cell">{{ $lp->topic_content }}</td>
                <td class="text-center bold">{{ $lp->co_tag ?: ($lp->co_id ?: 'CO1') }}</td>
                <td class="text-center">{{ $lp->allocated_hours ?: 1 }}</td>
                <td>{{ $lp->pedagogy }}</td>
                <td class="text-center" style="font-weight: bold; color: {{ $lp->status == 'Completed' ? '#16a34a' : '#d97706' }};">
                    {{ $lp->status }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 15px; text-align: center; color: #777;">No planner generated yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-line">
            Faculty In Charge
        </div>
        <div class="signature-line">
            Head of Department
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
