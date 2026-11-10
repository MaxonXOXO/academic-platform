<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Carmel Linx — Open Elective Option Chart ({{ $student->sbte_reg_no }})</title>
  <style>
    @page { size: A4 portrait; margin: 15mm 12mm; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 0; line-height: 1.35; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; }
    .college-name { font-size: 16px; font-weight: bold; text-transform: uppercase; }
    .college-sub { font-size: 10px; color: #444; }
    .doc-title { font-size: 13px; font-weight: bold; margin-top: 6px; text-decoration: underline; text-transform: uppercase; }
    .meta-box { width: 100%; border: 1px solid #333; margin-bottom: 15px; border-collapse: collapse; }
    .meta-box td { padding: 5px 8px; font-size: 11px; border: 1px solid #ccc; }
    .bold { font-weight: bold; }
    .chart-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10.5px; }
    .chart-table th, .chart-table td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
    .chart-table th { background: #f0f0f0; text-transform: uppercase; font-size: 10px; }
    .center { text-align: center; }
    .badge { display: inline-block; padding: 2px 6px; font-weight: bold; font-size: 9px; border-radius: 3px; }
    .opt-1 { background: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
    .allotted-box { margin-top: 15px; padding: 12px; border: 2px solid #059669; background: #ecfdf5; border-radius: 4px; }
    .pending-box { margin-top: 15px; padding: 10px; border: 1px dashed #d97706; background: #fffbeb; }
    .signatures { margin-top: 50px; display: flex; justify-content: space-between; }
    .sig-col { width: 40%; text-align: center; border-top: 1px solid #000; padding-top: 5px; font-size: 10.5px; }
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>

  <div class="no-print" style="background:#222;color:#fff;padding:8px 15px;display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
    <span>Student Registration Slip — Revision 2021 Semester 6 Open Elective</span>
    <button onclick="window.print()" style="background:#0284c7;color:#fff;border:none;padding:6px 15px;border-radius:4px;cursor:pointer;font-weight:bold;">Print Slip</button>
  </div>

  <div class="header">
    <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
    <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; SBTE Diploma Curriculum (Rev 2021)</div>
    <div class="doc-title">Open Elective Registration &amp; Option Chart Slip</div>
  </div>

  <table class="meta-box">
    <tr>
      <td width="20%" class="bold">Student Name:</td>
      <td width="30%"><strong>{{ strtoupper($student->name) }}</strong></td>
      <td width="20%" class="bold">SBTE PRN / Reg No:</td>
      <td width="30%" class="bold">{{ $student->sbte_reg_no ?? $student->reg_no }}</td>
    </tr>
    <tr>
      <td class="bold">Parent Department:</td>
      <td><strong>{{ $student->branch }}</strong></td>
      <td class="bold">Admission No:</td>
      <td>{{ $student->adm_no }}</td>
    </tr>
    <tr>
      <td class="bold">Registration Token:</td>
      <td><strong>OE-2026-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
      <td class="bold">Submission Timestamp:</td>
      <td>{{ $registration->registered_at->format('d M Y, h:i A') }}</td>
    </tr>
  </table>

  <h4 style="margin: 10px 0 6px 0; text-transform: uppercase; font-size: 11px;">Ranked Choice Option Chart:</h4>
  <table class="chart-table">
    <thead>
      <tr>
        <th width="8%" class="center">Priority</th>
        <th width="14%">Offering Dept</th>
        <th width="14%">Course Code</th>
        <th width="32%">Course Title</th>
        <th width="32%">Course Outcomes / Description</th>
      </tr>
    </thead>
    <tbody>
      @php
        $optionsList = [
          ['rank' => 'Option 1', 'cls' => 'opt-1', 'course' => $registration->option1Course],
          ['rank' => 'Option 2', 'cls' => '', 'course' => $registration->option2Course],
          ['rank' => 'Option 3', 'cls' => '', 'course' => $registration->option3Course],
          ['rank' => 'Option 4', 'cls' => '', 'course' => $registration->option4Course],
          ['rank' => 'Option 5', 'cls' => '', 'course' => $registration->option5Course],
        ];
      @endphp

      @foreach($optionsList as $opt)
        <tr>
          <td class="center bold">
            <span class="badge {{ $opt['cls'] }}">{{ $opt['rank'] }}</span>
          </td>
          <td class="bold">{{ $opt['course']->offering_branch ?? 'N/A' }}</td>
          <td class="bold">{{ $opt['course']->course_code ?? 'N/A' }}</td>
          <td class="bold">{{ $opt['course']->course_title ?? 'N/A' }}</td>
          <td style="font-size: 9.5px; color: #333;">
            {{ $opt['course']->course_outcomes ?: ($opt['course']->course_description ?: 'General curriculum outcomes') }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Allotment Status Box -->
  @if($registration->allottedCourse)
    <div class="allotted-box">
      <div style="font-size: 12px; font-weight: bold; color: #065f46; text-transform: uppercase; margin-bottom: 3px;">
        &check; Officially Allotted Course for Semester 6:
      </div>
      <div style="font-size: 13px; font-weight: bold;">
        {{ $registration->allottedCourse->course_title }} (Code: {{ $registration->allottedCourse->course_code }})
      </div>
      <div style="margin-top: 3px; font-size: 11px; color: #047857;">
        Offering Department: <strong>Department of {{ $registration->allottedCourse->offering_branch }}</strong> &bull;
        Classroom: <strong>{{ $registration->allottedCourse->classroom_id ?: 'OE_2024_' . $registration->allottedCourse->offering_branch }}</strong>
      </div>
    </div>
  @else
    <div class="pending-box">
      <strong>Status:</strong> Ranked preferences recorded. Central seat allocation on First-Come, First-Served basis is being processed by the Principal Office.
    </div>
  @endif

  <div class="signatures">
    <div class="sig-col">
      <strong>Student Signature</strong>
      <div>Name: {{ $student->name }}</div>
    </div>
    <div class="sig-col">
      <strong>Carmel Linx Central Verification</strong>
      <div>Date: {{ date('d-m-Y') }}</div>
    </div>
  </div>

</body>
</html>
