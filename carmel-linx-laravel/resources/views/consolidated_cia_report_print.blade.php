<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consolidated CIA Report - {{ $classroom['id'] ?? 'Classroom' }} (S{{ $classroom['semester'] ?? 1 }})</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
  <style>
    @page {
      size: A4 landscape;
      margin: 7mm 6mm 7mm 6mm;
    }
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      font-size: 10px;
      line-height: 1.25;
      color: #0f172a;
      background: #ffffff;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .print-container {
      width: 100%;
      max-width: 285mm;
      margin: 0 auto;
    }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .font-bold { font-weight: 700; }
    .font-black { font-weight: 900; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .uppercase { text-transform: uppercase; }

    /* Header */
    .header-box {
      border-bottom: 2px solid #0f172a;
      padding-bottom: 5px;
      margin-bottom: 6px;
      position: relative;
    }
    .college-name {
      font-size: 15px;
      font-weight: 900;
      letter-spacing: 0.5px;
      color: #0f172a;
    }
    .college-sub {
      font-size: 9.5px;
      font-weight: 600;
      color: #475569;
    }
    .dept-title {
      font-size: 12px;
      font-weight: 800;
      color: #0f172a;
      margin-top: 2px;
      text-transform: uppercase;
    }
    .report-title {
      font-size: 12.5px;
      font-weight: 900;
      letter-spacing: 0.8px;
      background: #0f172a;
      color: #ffffff;
      display: inline-block;
      padding: 2px 14px;
      border-radius: 4px;
      margin-top: 4px;
    }

    /* Meta Strip */
    .meta-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 4px;
      background: #f8fafc;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      padding: 4px 8px;
      margin-bottom: 6px;
      font-size: 9.5px;
    }
    .meta-item {
      display: flex;
      flex-direction: column;
    }
    .meta-label {
      font-size: 7.5px;
      font-weight: 800;
      text-transform: uppercase;
      color: #64748b;
      letter-spacing: 0.5px;
    }
    .meta-val {
      font-weight: 800;
      color: #0f172a;
    }

    /* Lock Stamp Banner */
    .lock-banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 3px 8px;
      border-radius: 4px;
      margin-bottom: 6px;
      font-size: 9px;
      font-weight: 800;
    }
    .lock-banner-locked {
      background: #ecfdf5;
      border: 1px solid #059669;
      color: #065f46;
    }
    .lock-banner-draft {
      background: #fffbeb;
      border: 1px solid #d97706;
      color: #92400e;
    }

    /* Main Table */
    table.cia-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: auto;
      font-size: 9px;
    }
    table.cia-table th, table.cia-table td {
      border: 1px solid #94a3b8;
      padding: 2.5px 3.5px;
      vertical-align: middle;
    }
    table.cia-table th {
      background: #f1f5f9;
      font-weight: 800;
      color: #0f172a;
      text-align: center;
    }
    table.cia-table th.subj-th {
      font-size: 8px;
      padding: 2px 2px;
      max-width: 68px;
      word-break: break-word;
      line-height: 1.15;
    }
    table.cia-table tr:nth-child(even) td {
      background-color: #f8fafc;
    }
    table.cia-table td.mark-cell {
      text-align: center;
      font-family: 'JetBrains Mono', monospace;
      font-weight: 700;
      font-size: 9.5px;
    }
    .mark-fail {
      color: #dc2626;
      font-weight: 900;
      text-decoration: underline;
    }
    .mark-pass {
      color: #0f172a;
    }
    .mark-pending {
      color: #94a3b8;
      font-weight: 500;
    }
    .status-pass {
      color: #166534;
      font-weight: 800;
      text-align: center;
      font-size: 8.5px;
    }
    .status-fail {
      color: #991b1b;
      font-weight: 800;
      text-align: center;
      font-size: 8.5px;
    }

    /* Faculty Section */
    .faculty-section {
      margin-top: 8px;
      border-top: 1px dashed #cbd5e1;
      padding-top: 6px;
      page-break-inside: avoid;
    }
    .faculty-title {
      font-size: 9px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #475569;
      margin-bottom: 4px;
    }
    .faculty-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 5px;
      font-size: 8.5px;
    }
    .faculty-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      padding: 3px 5px;
    }
    .faculty-name {
      font-weight: 800;
      color: #0f172a;
    }
    .faculty-subjs {
      color: #64748b;
      font-size: 7.5px;
      line-height: 1.1;
      margin-top: 1px;
    }

    /* Signature Area */
    .sig-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin-top: 18px;
      page-break-inside: avoid;
    }
    .sig-box {
      border-top: 1.5px solid #0f172a;
      padding-top: 4px;
      text-align: center;
    }
    .sig-role {
      font-size: 9.5px;
      font-weight: 800;
      color: #0f172a;
      text-transform: uppercase;
    }
    .sig-name {
      font-size: 8.5px;
      color: #475569;
      margin-top: 1px;
    }
    .sig-date {
      font-size: 7.5px;
      color: #64748b;
      margin-top: 1px;
    }

    /* Screen-only Print Bar */
    @media screen {
      body {
        background: #0f172a;
        padding: 20px;
      }
      .print-container {
        background: #ffffff;
        padding: 12mm 10mm;
        border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
      }
      .screen-bar {
        max-width: 285mm;
        margin: 0 auto 15px auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #1e293b;
        border: 1px solid #334155;
        padding: 10px 16px;
        border-radius: 8px;
        color: #ffffff;
      }
      .btn {
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
      }
      .btn-primary { background: #2563eb; color: #ffffff; }
      .btn-primary:hover { background: #1d4ed8; }
      .btn-secondary { background: #334155; color: #e2e8f0; }
      .btn-secondary:hover { background: #475569; }
      .btn-lock { background: #059669; color: #ffffff; }
      .btn-lock:hover { background: #047857; }
    }
    @media print {
      .screen-bar { display: none !important; }
      body { background: #ffffff !important; }
      .print-container { padding: 0 !important; box-shadow: none !important; }
    }
  </style>
</head>
<body>

  <!-- Screen Toolbar -->
  <div class="screen-bar">
    <div style="display: flex; align-items: center; gap: 10px;">
      <a href="javascript:window.close();" class="btn btn-secondary">← Close Window</a>
      <span style="font-weight: 800; font-size: 13px;">Consolidated CIA Mark Register (A4 Landscape)</span>
      @if($approval['is_locked'])
        <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.3);">
          🔒 LOCKED BY HOD
        </span>
      @else
        <span style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 800; border: 1px solid rgba(245, 158, 11, 0.3);">
          ✍️ DRAFT (STAFF EDITABLE)
        </span>
      @endif
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
      <button onclick="window.print()" class="btn btn-primary">🖨️ Print Document (A4 Landscape)</button>
    </div>
  </div>

  <div class="print-container">

    <!-- Header Section -->
    <div class="header-box text-center">
      <div class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</div>
      <div class="college-sub">Government Aided Institution • Approved by AICTE • Affiliated to State Board of Technical Education, Kerala</div>
      <div class="dept-title">DEPARTMENT OF {{ strtoupper($classroom['branch_name'] ?? 'ENGINEERING') }}</div>
      <div class="report-title">CONSOLIDATED CONTINUOUS INTERNAL ASSESSMENT (CIA) MARK REPORT</div>
    </div>

    <!-- Metadata Grid -->
    <div class="meta-grid">
      <div class="meta-item">
        <span class="meta-label">Curriculum / Scheme</span>
        <span class="meta-val">{{ $classroom['scheme_name'] ?? 'Revision 2021' }}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Classroom / Batch</span>
        <span class="meta-val">{{ $classroom['batch'] ?? $classroom['id'] }}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Semester</span>
        <span class="meta-val">Semester {{ $classroom['semester'] ?? 1 }} (S{{ $classroom['semester'] ?? 1 }})</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Class Tutor</span>
        <span class="meta-val">{{ $classroom['tutor_name'] ?? 'Class Tutor' }}</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Head of Dept (HOD)</span>
        <span class="meta-val">{{ $approval['hod_name'] ?? ($classroom['hod_name'] ?? 'HOD') }}</span>
      </div>
      <div class="meta-item text-right">
        <span class="meta-label">Compiled Date</span>
        <span class="meta-val font-mono">{{ $classroom['report_date'] ?? date('d-m-Y') }}</span>
      </div>
    </div>

    <!-- Lock Status Banner -->
    @if($approval['is_locked'])
      <div class="lock-banner lock-banner-locked">
        <div>
          <span>🔒 <strong>APPROVED & LOCKED BY HEAD OF DEPARTMENT</strong> — Official Sanction Granted on {{ $approval['approved_at'] ?? $approval['locked_at'] ?? date('d-m-Y') }} by {{ $approval['hod_name'] ?? 'Head of Department' }}.</span>
        </div>
        <div class="font-mono text-right">
          STATUS: SEALED & OFFICIAL
        </div>
      </div>
    @else
      <div class="lock-banner lock-banner-draft">
        <div>
          <span>⚠️ <strong>PROVISIONAL DRAFT REPORT</strong> — CIA marks are calculated live from current evaluation entries. Subject teachers can edit marks until final HOD approval and locking.</span>
        </div>
        <div class="font-mono text-right">
          STATUS: DRAFT COPY
        </div>
      </div>
    @endif

    <!-- Main Consolidated CIA Table -->
    <table class="cia-table">
      <thead>
        <tr>
          <th style="width: 22px;">Sl</th>
          <th style="width: 28px;">Roll</th>
          <th style="width: 70px;">Reg No</th>
          <th style="text-align: left; min-width: 140px;">Student Name</th>
          @foreach($subjects as $subj)
            <th class="subj-th" title="{{ $subj->subject_name }}">
              <div class="font-bold">{{ $subj->subject_code }}</div>
              <div style="font-size: 7px; color: #475569; max-height: 20px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $subj->subject_name }}</div>
              <div style="font-size: 7.5px; color: #0284c7; font-weight: 800;">[Max: {{ $subj->max_cia }}]</div>
            </th>
          @endforeach
          <th style="width: 44px;" title="SBTE Final Exam Eligibility Attendance % (including TEAMS Log & Sanctioned Duty Hours)">Attn (%)</th>
          <th style="width: 48px;">Result</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $idx => $st)
          <tr>
            <td class="text-center font-mono">{{ $idx + 1 }}</td>
            <td class="text-center font-mono font-bold">{{ $st['roll_no'] ?: '-' }}</td>
            <td class="text-center font-mono">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
            <td class="font-bold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $st['name'] }}</td>
            
            @foreach($subjects as $subj)
              @php
                $markData = $st['subject_marks'][$subj->id] ?? null;
                $val = $markData['mark'] ?? null;
                $isGen = $markData['is_generated'] ?? false;
                $isPass = $markData['is_pass'] ?? true;
              @endphp
              <td class="mark-cell">
                @if($isGen && $val !== null)
                  <span class="{{ $isPass ? 'mark-pass' : 'mark-fail' }}">{{ $val }}</span>
                @else
                  <span class="mark-pending">-</span>
                @endif
              </td>
            @endforeach

            <!-- Total Attendance % (SBTE Final Exam Eligibility) -->
            <td class="text-center font-mono font-bold" style="{{ $st['overall_attendance'] < 75.0 ? 'color: #dc2626;' : 'color: #059669;' }}">
              {{ number_format($st['overall_attendance'], 1) }}%
              @if(!empty($st['special_duty_hours']) && $st['special_duty_hours'] > 0)
                <span title="Includes +{{ $st['special_duty_hours'] }} hrs TEAMS / Sanctioned Duty Credit" style="color: #0284c7; font-size: 8px; vertical-align: super; font-weight: 800;">*</span>
              @endif
            </td>

            <!-- Overall Status -->
            <td>
              @if($st['overall_result'] === 'PASSED')
                <div class="status-pass">PASS</div>
              @elseif($st['overall_result'] === 'FAILED')
                <div class="status-fail">FAIL</div>
              @else
                <div class="text-center text-slate-400 font-mono">-</div>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ 6 + count($subjects) }}" class="text-center" style="padding: 15px; color: #64748b;">
              No students enrolled in this classroom.
            </td>
          </tr>
        @endforelse
      </tbody>

      <!-- Subject Summary Row -->
      <tfoot>
        <tr style="background: #f1f5f9; font-weight: 800;">
          <td colspan="4" class="text-right" style="padding-right: 6px; font-size: 8.5px; text-transform: uppercase;">
            Course Average CIA Mark:
          </td>
          @foreach($subjects as $subj)
            @php
              $allMarksForSubj = collect($students)->pluck('subject_marks.' . $subj->id . '.mark')->filter(fn($m) => $m !== null);
              $avgForSubj = $allMarksForSubj->count() > 0 ? round($allMarksForSubj->avg()) : '-';
            @endphp
            <td class="text-center font-mono" style="font-size: 8.5px; color: #0369a1;">
              {{ $avgForSubj }}
            </td>
          @endforeach
          <td class="text-center font-mono" style="font-size: 8.5px;">
            {{ count($students) > 0 ? number_format(collect($students)->avg('overall_attendance'), 1) . '%' : '-' }}
          </td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    @php
      $anyDutyCredited = collect($students)->contains(fn($s) => !empty($s['special_duty_hours']) && $s['special_duty_hours'] > 0);
    @endphp
    @if($anyDutyCredited)
      <div style="font-size: 8px; color: #475569; margin-top: 4px; margin-bottom: 6px; font-style: italic;">
        * Note: Marked Attendance % indicates final SBTE Exam Eligibility attendance as verified by Class Tutor from TEAMS Attendance Logs and sanctioned duty hours (SBTE Clause 10 / Rule 7). Individual course CIA marks are based strictly on academic assessments.
      </div>
    @endif

    <!-- Course & Assigned Faculty Directory -->
    <div class="faculty-section">
      <div class="faculty-title">Faculty In-Charge & Course Allocation Directory</div>
      <div class="faculty-grid">
        @foreach($staff_list as $fac)
          <div class="faculty-card">
            <div class="faculty-name">{{ $fac['name'] }} <span style="font-size: 7.5px; font-weight: 500; color: #64748b;">({{ $fac['designation'] }})</span></div>
            <div class="faculty-subjs">{{ implode(', ', $fac['subjects']) }}</div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Official Signatures Block -->
    <div class="sig-grid">
      <div class="sig-box">
        <div class="sig-role">Signature of Class Tutor</div>
        <div class="sig-name">{{ $classroom['tutor_name'] ?? 'Class Tutor' }}</div>
        <div class="sig-date">Date: {{ $approval['submitted_at'] ?? date('d-m-Y') }}</div>
      </div>
      <div class="sig-box">
        <div class="sig-role">Signature of Head of Department</div>
        <div class="sig-name">{{ $approval['hod_name'] ?? ($classroom['hod_name'] ?? 'Head of Department') }}</div>
        <div class="sig-date">
          @if($approval['is_locked'])
            Approved &amp; Locked: {{ $approval['approved_at'] ?? $approval['locked_at'] ?? date('d-m-Y') }}
          @else
            Pending Final Sanction
          @endif
        </div>
      </div>
      <div class="sig-box">
        <div class="sig-role">Principal / Head of Institution</div>
        <div class="sig-name">Carmel Polytechnic College</div>
        <div class="sig-date">Alappuzha, Kerala</div>
      </div>
    </div>

  </div>

</body>
</html>
