<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Plan - {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
    <style>
            @page {
                size: A4 landscape;
                margin: 15mm 15mm 15mm 15mm;
            }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px 16px;
            color: #0f172a;
            background: #f1f5f9;
            font-size: 10px;
            line-height: 1.3;
        }

        .report-container {
            max-width: 1280px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .no-print {
            text-align: center;
            padding: 12px;
            background: #1e3a5f;
            border-radius: 8px;
            margin-bottom: 20px;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        .no-print button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 6px;
        }

        .no-print button.back-btn {
            background: #475569;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2.5px solid #1e3a5f;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 17px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 800;
            color: #1e3a5f;
        }

        .header h2 {
            margin: 3px 0 0 0;
            font-size: 13px;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
        }

        .header h3 {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #ffffff;
            background: #1e3a5f;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 4px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5px;
            border: 1.5px solid #1e3a5f;
        }

        .meta-table td {
            border: 1px solid #cbd5e1 !important;
            padding: 6px 10px;
            vertical-align: middle;
        }

        .meta-table td.lbl {
            font-weight: 700;
            color: #1e3a5f;
            background: #f1f5f9;
            width: 16%;
        }

        .meta-table td.val {
            font-weight: 600;
            color: #0f172a;
        }

        table.plan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 6px;
            border: 1.5px solid #1e3a5f;
        }

        table.plan-table th, table.plan-table td {
            border: 1px solid #cbd5e1 !important;
            padding: 5px 7px;
            vertical-align: middle;
        }

        table.plan-table th {
            background-color: #1e3a5f;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.3px;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .bold { font-weight: 700; }

        .topic-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-weight: 500;
            font-size: 10px;
            line-height: 1.35;
        }

        .footer {
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            page-break-inside: avoid;
            padding: 0 15px;
        }

        .signature-line {
            border-top: 1.5px solid #1e3a5f;
            width: 220px;
            text-align: center;
            padding-top: 5px;
            margin-top: 40px;
            font-weight: 700;
            color: #1e3a5f;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm 15mm 15mm 15mm;
            }

            body {
                padding: 0 !important;
                background: #ffffff !important;
            }

            .report-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: #ffffff !important;
            }

            .no-print {
                display: none !important;
            }

            table.plan-table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>

    <!-- No-Print Action Toolbar -->
    <div class="no-print">
        <button onclick="window.print()">
            🖨️ Print Lesson Plan (A4 Landscape)
        </button>
        <button onclick="window.close()" class="back-btn">
            ← Back to Classroom
        </button>
    </div>

    <!-- Main Report Container with Safety Margins -->
    <div class="report-container">
        <!-- Header Title Section -->
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h2>Department of {{ strtoupper($classroom?->branch ?? $batchSubject?->branch ?? 'Mechanical') }} Engineering</h2>
            <h3>LESSON PLAN - {{ $batchSubject->syllabus_revision_code ?? 'REVISION 2026' }} ({{ $drawingCourseFile?->contact_hours ?? 45 }} CONTACT HOURS)</h3>
        </div>

        <!-- Metadata Grid Table -->
        <table class="meta-table">
            <tr>
                <td class="lbl">College Name:</td>
                <td class="val" style="width: 34%;">Carmel Polytechnic College, Alappuzha</td>
                <td class="lbl">Department:</td>
                <td class="val" style="width: 34%;">{{ $classroom?->branch ?? $batchSubject?->branch ?? 'Mechanical' }} Engineering</td>
            </tr>
            <tr>
                <td class="lbl">Subject Code & Title:</td>
                <td class="val">[{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }}</td>
                <td class="lbl">Academic Batch:</td>
                <td class="val">{{ !empty($classroom?->batch_year) ? $classroom->batch_year.'-'.($classroom->batch_year+3) : ($classroom?->classroom_id ?? $batchSubject->classroom_id ?? '2026-2029') }}</td>
            </tr>
            <tr>
                <td class="lbl">Faculty In-Charge:</td>
                <td class="val">{{ $staff->name ?? 'Lecturer In Charge' }}</td>
                <td class="lbl">Semester / Duration:</td>
                <td class="val">Semester {{ $classroom?->current_semester ?? $batchSubject->semester ?? 'I' }} • {{ $drawingCourseFile?->contact_hours ?? 45 }} Contact Hours</td>
            </tr>
        </table>

        <!-- Main Lesson Plan Grid Table -->
        <table class="plan-table">
            <thead>
                <tr>
                    <th style="width: 45px;">Hr #</th>
                    <th style="width: 85px;">Proposed Date</th>
                    <th style="width: 85px;">Actual Date</th>
                    <th class="text-left">Topic & Practical Exercise Content</th>
                    <th style="width: 45px;">CO</th>
                    <th style="width: 45px;">Hrs</th>
                    <th style="width: 160px;">Pedagogy / Assessment</th>
                    <th style="width: 75px;">Status</th>
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
                Faculty In-Charge
            </div>
            <div class="signature-line">
                Head of Department (HOD)
            </div>
            <div class="signature-line">
                Principal, Carmel Polytechnic
            </div>
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
