<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>End-Semester Examination (ESE) & Final Results — {{ $batchSubject->subject_code }} ({{ $batchSubject->subject_name }})</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: #f1f5f9; color: #1e293b; padding: 18px 12px; }
  .report-container { max-width: 1150px; margin: 0 auto; background: #ffffff; padding: 22px 26px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 14px; }
  .institution-header h1 { font-size: 17px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; }
  .institution-header p { font-size: 11px; color: #475569; margin-top: 2px; }
  .report-title { font-size: 12.5px; font-weight: 800; margin-top: 5px; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px 14px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 9px 12px; margin-bottom: 14px; }
  .meta-label { font-weight: 600; color: #475569; font-size: 10px; text-transform: uppercase; }
  .meta-val { font-weight: 700; color: #1e293b; font-size: 11.5px; }
  .table-wrap { margin-bottom: 18px; overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 11px; }
  thead th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; font-size: 10px; border: 1px solid #2d5a8a; }
  thead th.tleft { text-align: left; padding-left: 8px; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tbody td { border: 1px solid #cbd5e1; padding: 5px 4px; text-align: center; font-size: 11px; }
  tbody td.tdleft { text-align: left; padding-left: 8px; }
  .no-print { text-align: center; padding: 10px; background: #1e3a5f; border-radius: 6px; margin-bottom: 16px; max-width: 1150px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 7px 22px; font-size: 12px; font-weight: 700; border-radius: 5px; cursor: pointer; margin: 0 5px; }
  .no-print button.back-btn { background: #475569; }
  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 36px; border-top: 1px solid #cbd5e1; padding-top: 14px; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 11px; }
  .sig-line { border-bottom: 1px solid #475569; height: 32px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 600; color: #475569; font-size: 10px; }
  @media print {
    @page { size: A4 landscape; margin: 10mm; }
    .no-print { display: none !important; }
    body { background: #fff; padding: 0; margin: 0; }
    .report-container { border: none; box-shadow: none; padding: 0; max-width: 100%; width: 100%; }
    tr { page-break-inside: avoid; }
  }
</style>
<script>
function goBackToClassroom() {
  if (window.opener && !window.opener.closed) {
    window.close();
  } else if (document.referrer && document.referrer.length > 0) {
    window.location.href = document.referrer;
  } else {
    window.location.href = "{{ url('/r26/classroom/practicum/' . $batchSubject->id) }}";
  }
}
</script>
</head>
<body>

<div class="no-print">
  <button class="back-btn" onclick="goBackToClassroom()">&#8592; Back to Classroom</button>
  <button onclick="window.print()">&#128424; Print / Save as PDF</button>
</div>

<div class="report-container">

<div class="institution-header">
  <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
  <p>Affiliated to SBTE Kerala &nbsp;|&nbsp; Approved by AICTE, New Delhi &nbsp;|&nbsp; Government Aided Institution</p>
  <div class="report-title">END-SEMESTER EXAMINATION (ESE) &amp; CONSOLIDATED RESULTS REGISTER &mdash; REVISION 2026</div>
</div>

<div class="meta-grid">
  <div><div class="meta-label">Course / Subject</div><div class="meta-val"><strong>{{ $batchSubject->subject_code }}</strong> &bull; {{ $batchSubject->subject_name }}</div></div>
  <div><div class="meta-label">Semester &amp; Batch</div><div class="meta-val">Semester {{ $classroom->current_semester ?? $batchSubject->semester ?? 'N/A' }} ({{ $batchName ?? $classroom->batch ?? $classroom->division ?? $classroom->classroom_id }})</div></div>
  <div><div class="meta-label">Department</div><div class="meta-val">{{ $departmentName ?? (function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? '') : ($classroom->department ?? 'N/A')) }}</div></div>
  <div><div class="meta-label">Faculty In-Charge</div><div class="meta-val">{{ $lecturerName ?? ($assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : 'Faculty Member') }}</div></div>
  <div><div class="meta-label">Curriculum Revision</div><div class="meta-val">Revision 2026 &bull; Practicum Examination Model</div></div>
  <div><div class="meta-label">Total Course Marks</div><div class="meta-val"><strong style="color: #0369a1;">{{ $studentResults->first()['max_course_marks'] ?? 100 }} Marks</strong> (CIE 40M + ESE)</div></div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th style="width: 4%">Sl</th>
        <th style="width: 5%">Roll</th>
        <th style="width: 14%">Reg. No. (SBTE)</th>
        <th class="tleft" style="width: 25%">Student Name</th>
        <th style="background: #15803d; width: 11%">CIE Marks<br>(/40)</th>
        <th style="background: #2563eb; width: 11%">Theory ESE<br>(/60)</th>
        <th style="background: #1d4ed8; width: 8%">Theory<br>Grade</th>
        @if(!$isBasicScience)
        <th style="background: #7c3aed; width: 10%">Practical ESE</th>
        @endif
        <th style="background: #0369a1; width: 12%">Grand Total<br>(/{{ $studentResults->first()['max_course_marks'] ?? 100 }})</th>
        <th style="background: #0f172a; width: 10%">Final Result</th>
      </tr>
    </thead>
    <tbody>
      @php $slNo = 1; @endphp
      @foreach($studentResults as $st)
        @php
          $pass = $st['is_passed'];
        @endphp
        <tr>
          <td>{{ $slNo++ }}</td>
          <td><strong>{{ $st['roll_no'] ?: '-' }}</strong></td>
          <td style="font-family: monospace; font-weight: 600;">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
          <td class="tdleft"><strong>{{ $st['name'] }}</strong></td>

          <!-- CIE 40M - Whole Number -->
          <td style="background: #dcfce7; font-weight: 800; font-size: 11.5px; color: #166534;">
            {{ number_format(round($st['total_cia_marks'] ?? 0)) }}
          </td>

          <!-- Theory ESE (60M) -->
          <td style="font-weight: 600; color: #1e3a8a;">
            {{ number_format($st['ese_theory'] ?? 0, 1) }}
          </td>

          <!-- Grade -->
          <td style="font-weight: 800; font-family: monospace; font-size: 12px; color: {{ ($st['ese_theory_grade'] === 'F') ? '#dc2626' : '#1e3a8a' }};">
            {{ $st['ese_theory_grade'] ?: '-' }}
          </td>

          @if(!$isBasicScience)
          <td>
            {{ number_format($st['ese_practical'] ?? 0, 1) }}
          </td>
          @endif

          <!-- Grand Total - Whole Number -->
          <td style="background: #fef3c7; font-weight: 800; font-size: 12px; color: #92400e;">
            {{ number_format(round($st['total_course_marks'] ?? 0)) }}
          </td>

          <!-- Result -->
          <td style="font-weight: 800; font-size: 10.5px; color: {{ $pass ? '#15803d' : '#dc2626' }};">
            {{ $pass ? 'PASSED' : 'FAILED' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="sig-row">
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Course Faculty Signature</div>
    <div style="font-weight:700; font-size:11px; margin-top:2px;">{{ $lecturerName ?? ($assignedStaff->count() ? $assignedStaff->first()->name : 'Faculty Member') }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Head of Department (HOD)</div>
    <div style="font-weight:700; font-size:11px; margin-top:2px;">{{ $departmentName ?? 'Department Head' }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Principal / Academic Head</div>
    <div style="font-weight:700; font-size:11px; margin-top:2px;">Carmel Polytechnic College</div>
  </div>
</div>

</div>

</body>
</html>
