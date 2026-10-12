<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Due Clearance Slip - {{ $student->name }} ({{ $student->sbte_reg_no ?: $student->reg_no }})</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        :root {
            --primary: #0f172a;
            --accent: #0284c7;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --success: #15803d;
            --warning: #b45309;
            --danger: #b91c1c;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            line-height: 1.4;
            font-size: 11px;
            padding: 16px;
        }
        .action-bar {
            max-width: 210mm;
            margin: 0 auto 14px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn {
            background-color: #0284c7;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn:hover { background-color: #0369a1; }
        .btn-secondary { background-color: #475569; }
        .btn-secondary:hover { background-color: #334155; }
        .a4-container {
            max-width: 210mm;
            min-height: 275mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px 28px;
            border: 1.5px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 17px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .header h2 {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 3px;
        }
        .header h3 {
            font-size: 12.5px;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .header p {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 600;
            margin-top: 2px;
        }
        .meta-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background: var(--bg-light);
            border: 1px solid var(--border);
        }
        .meta-grid td {
            padding: 5px 10px;
            font-size: 10.5px;
            border: 1px solid #e2e8f0;
        }
        .meta-grid td.label {
            font-weight: 700;
            color: #475569;
            width: 18%;
            background: #f1f5f9;
        }
        .meta-grid td.value {
            font-weight: 600;
            color: #0f172a;
            width: 32%;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .clearance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            border: 1px solid var(--border);
        }
        .clearance-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 7px 10px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        .clearance-table td {
            padding: 8px 10px;
            font-size: 10.5px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .clearance-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-cleared {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-pending {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .hall-ticket-box {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
            border: 2px solid;
            text-align: center;
        }
        .hall-ticket-eligible {
            background: #f0fdf4;
            border-color: #22c55e;
            color: #14532d;
        }
        .hall-ticket-pending {
            background: #fef2f2;
            border-color: #ef4444;
            color: #7f1d1d;
        }
        .hall-ticket-box h4 {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .hall-ticket-box p {
            font-size: 10px;
            line-height: 1.4;
            font-weight: 600;
        }
        .footer-signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }
        .footer-signatures td {
            width: 25%;
            text-align: center;
            vertical-align: bottom;
            font-size: 9.5px;
            padding: 0 6px;
            color: #334155;
            line-height: 1.4;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            font-weight: 900;
            color: rgba(15, 23, 42, 0.04);
            letter-spacing: 6px;
            pointer-events: none;
            text-transform: uppercase;
            white-space: nowrap;
        }
        @media print {
            body { background: none; padding: 0; }
            .a4-container { border: none; box-shadow: none; padding: 0; margin: 0; width: 100%; min-height: auto; }
            .action-bar { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <button class="btn" onclick="window.print()">🖨️ Print No Due Slip</button>
        <button class="btn btn-secondary" onclick="window.close()">Close</button>
    </div>

    <div class="a4-container">
        <div class="watermark">CARMEL LINX</div>

        <!-- College Header -->
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
            <h2>GOVERNMENT AIDED POLYTECHNIC COLLEGE &bull; PUNNAPRA, ALAPPUZHA - 688004</h2>
            <h3>NO DUE CLEARANCE SLIP FOR SBTE DIPLOMA EXAMINATION</h3>
            <p>Official Institutional Clearance for Issuance of End Semester Examination Hall Ticket</p>
        </div>

        <!-- Student Metadata -->
        <table class="meta-grid">
            <tr>
                <td class="label">Student Name:</td>
                <td class="value" style="font-weight: 800; font-size: 11.5px;">{{ $student->name }}</td>
                <td class="label">Admission No:</td>
                <td class="value font-mono">{{ $student->adm_no ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Register No (SBTE):</td>
                <td class="value font-mono" style="font-weight: 800; color: #0284c7;">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                <td class="label">Roll Number:</td>
                <td class="value font-mono">{{ $student->roll_no ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Programme / Branch:</td>
                <td class="value"><strong>{{ $classroom->branch ?? $student->branch }}</strong> — {{ $student->department ?? 'Engineering' }}</td>
                <td class="label">Semester / Scheme:</td>
                <td class="value"><strong>Semester {{ $classroom->current_semester ?? $student->semester }}</strong> ({{ $scheme ?? 'R21' }})</td>
            </tr>
            <tr>
                <td class="label">Classroom Batch:</td>
                <td class="value font-mono">{{ $classroom->classroom_id ?? '-' }}</td>
                <td class="label">Date of Verification:</td>
                <td class="value">{{ date('d-m-Y h:i A') }}</td>
            </tr>
        </table>

        <!-- Clearance Verification Table -->
        <table class="clearance-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 28%;">Department / Section</th>
                    <th style="width: 37%;">Verification Scope & Criteria</th>
                    <th style="width: 15%; text-align: center;">Status</th>
                    <th style="width: 15%; text-align: center;">Signature / Seal</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Office & Accounts -->
                <tr>
                    <td style="text-align: center; font-weight: 700;">1</td>
                    <td>
                        <strong>College Office & Accounts</strong><br>
                        <small style="color: #64748b;">Tuition & Examination Fees</small>
                    </td>
                    <td>
                        Semester tuition fee, exam registration fee, and institutional dues.<br>
                        <strong style="color: {{ $noDueData['fee_cleared'] ? '#15803d' : '#b91c1c' }};">
                            {{ $noDueData['fee_status'] }}
                        </strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $noDueData['fee_cleared'] ? 'badge-cleared' : 'badge-pending' }}">
                            {{ $noDueData['fee_cleared'] ? 'CLEARED' : 'PENDING' }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 8.5px; color: #64748b;">
                        Verified via SIS
                    </td>
                </tr>

                <!-- 2. Department Laboratories -->
                <tr>
                    <td style="text-align: center; font-weight: 700;">2</td>
                    <td>
                        <strong>Department Laboratories & Workshop</strong><br>
                        <small style="color: #64748b;">Practicals, Record & Breakages</small>
                    </td>
                    <td>
                        Completion of required laboratory experiments, submission of certified practical observation/records, and breakage clearance.<br>
                        <strong style="color: #15803d;">{{ $noDueData['dept_status'] }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $noDueData['dept_cleared'] ? 'badge-cleared' : 'badge-pending' }}">
                            {{ $noDueData['dept_cleared'] ? 'CLEARED' : 'PENDING' }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 8.5px; color: #64748b;">
                        Lab In-Charge
                    </td>
                </tr>

                <!-- 3. Central Library -->
                <tr>
                    <td style="text-align: center; font-weight: 700;">3</td>
                    <td>
                        <strong>College Central Library</strong><br>
                        <small style="color: #64748b;">Books Borrowed & Overdue Dues</small>
                    </td>
                    <td>
                        Return of all borrowed books, reference volumes, and clearance of any library fines.<br>
                        <strong style="color: #15803d;">{{ $noDueData['library_status'] }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $noDueData['library_cleared'] ? 'badge-cleared' : 'badge-pending' }}">
                            {{ $noDueData['library_cleared'] ? 'CLEARED' : 'PENDING' }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 8.5px; color: #64748b;">
                        Librarian
                    </td>
                </tr>

                <!-- 4. Class Tutor / Attendance -->
                <tr>
                    <td style="text-align: center; font-weight: 700;">4</td>
                    <td>
                        <strong>Class Tutor / Academic Advisor</strong><br>
                        <small style="color: #64748b;">Official Semester Attendance</small>
                    </td>
                    <td>
                        Official Semester Attendance: 
                        <strong style="font-size: 11px; color: #0f172a;">{{ number_format($attendancePct, 1) }}%</strong> 
                        ({{ $attendanceConducted }} Conducted / {{ $attendanceAttended }} Attended Hours).<br>
                        SBTE Exam Status: <strong>{{ $examEligibility['status'] }}</strong> ({{ $examEligibility['rule'] }}).
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $noDueData['tutor_cleared'] ? 'badge-cleared' : 'badge-pending' }}">
                            {{ $noDueData['tutor_cleared'] ? 'CLEARED' : 'PENDING' }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 8.5px; color: #64748b;">
                        Class Tutor
                    </td>
                </tr>

                <!-- 5. Hostel & Mess -->
                <tr>
                    <td style="text-align: center; font-weight: 700;">5</td>
                    <td>
                        <strong>Hostel & Mess Office</strong><br>
                        <small style="color: #64748b;">Room Rent & Mess Dues</small>
                    </td>
                    <td>
                        Clearance of mess dues and hostel charges.<br>
                        <strong style="color: #15803d;">{{ $noDueData['hostel_status'] }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $noDueData['hostel_cleared'] ? 'badge-cleared' : 'badge-pending' }}">
                            {{ $noDueData['hostel_cleared'] ? 'CLEARED' : 'PENDING' }}
                        </span>
                    </td>
                    <td style="text-align: center; font-size: 8.5px; color: #64748b;">
                        Warden / In-Charge
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Overall Hall Ticket Recommendation Box -->
        <div class="hall-ticket-box {{ $noDueData['overall_eligible'] ? 'hall-ticket-eligible' : 'hall-ticket-pending' }}">
            @if($noDueData['overall_eligible'])
                <h4>✓ CLEARED FOR SBTE EXAM HALL TICKET ISSUANCE</h4>
                <p>
                    The student has successfully satisfied all academic, laboratory, library, and institutional fee clearance criteria.<br>
                    <strong>Eligible to receive the End Semester Examination Hall Ticket from the Academic Office.</strong>
                </p>
            @else
                <h4>⚠ DUES PENDING — HALL TICKET ON HOLD</h4>
                <p>
                    Clearance is currently incomplete. Please resolve the marked pending dues with the respective department/office before the exam hall ticket distribution deadline.
                </p>
            @endif
        </div>

        <!-- Official Signatures -->
        <table class="footer-signatures">
            <tr>
                <td>
                    __________________________________<br>
                    <strong>Class Tutor / Mentor</strong><br>
                    {{ $tutor->name ?? 'Class Tutor' }}
                </td>
                <td>
                    __________________________________<br>
                    <strong>Head of Department</strong><br>
                    Dept. of {{ $classroom->branch ?? 'Engineering' }}
                </td>
                <td>
                    __________________________________<br>
                    <strong>Office Superintendent</strong><br>
                    Carmel Polytechnic College
                </td>
                <td>
                    __________________________________<br>
                    <strong>Principal</strong><br>
                    Head of Institution
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
