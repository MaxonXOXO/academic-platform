<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated Attendance Report — {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; background: #f1f5f9; color: #1e293b; padding: 20px 14px; }
  .report-container { max-width: 1150px; margin: 0 auto; background: #ffffff; padding: 24px 28px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 16px; }
  .institution-header h1 { font-size: 17px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; }
  .institution-header p { font-size: 11px; color: #475569; margin-top: 2px; }
  .report-title { font-size: 12px; font-weight: 800; margin-top: 5px; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px 16px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; }
  .meta-label { font-weight: 600; color: #475569; font-size: 10px; text-transform: uppercase; }
  .meta-val { font-weight: 700; color: #1e293b; font-size: 12px; }
  .table-wrap { margin-bottom: 18px; overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: 11px; }
  thead th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; font-size: 10px; border: 1px solid #2d5a8a; }
  thead th.tleft { text-align: left; padding-left: 8px; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tbody td { border: 1px solid #cbd5e1; padding: 5px 4px; text-align: center; font-size: 11px; }
  tbody td.tdleft { text-align: left; padding-left: 8px; }
  .pct-h { color: #15803d; font-weight: 700; }
  .pct-m { color: #d97706; font-weight: 700; }
  .pct-l { color: #dc2626; font-weight: 700; }
  tr.short-row { background: #fef2f2 !important; }
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 1150px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 8px 24px; font-size: 13px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }
  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 40px; border-top: 1px solid #cbd5e1; padding-top: 16px; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 11px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 600; color: #475569; font-size: 10px; }
  @media print {
    @page { size: A4 landscape; margin: 10mm 10mm 10mm 10mm; }
    .no-print { display: none !important; }
    body { background: #fff; padding: 0; margin: 0; }
    .report-container { border: none; box-shadow: none; padding: 2mm 3mm; max-width: 100%; width: 100%; }
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
  <div class="report-title">CONSOLIDATED ATTENDANCE REGISTER &mdash; REVISION 2026 PRACTICUM</div>
</div>

<div class="meta-grid">
  <div><div class="meta-label">Course / Subject</div><div class="meta-val"><strong>{{ $batchSubject->subject_code }}</strong> &bull; {{ $batchSubject->subject_name }}</div></div>
  <div><div class="meta-label">Semester &amp; Batch</div><div class="meta-val">Semester {{ $classroom->current_semester ?? $batchSubject->semester ?? 'N/A' }} ({{ $classroom->batch ?? $classroom->division ?? $classroom->classroom_id }})</div></div>
  <div><div class="meta-label">Department</div><div class="meta-val">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch ?? 'N/A') }}</div></div>
  <div><div class="meta-label">Faculty In-Charge</div><div class="meta-val">{{ $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : 'Faculty Member' }}</div></div>
  <div><div class="meta-label">Academic Scheme</div><div class="meta-val">Curriculum Revision 2026 (Practicum Course)</div></div>
  <div><div class="meta-label">Total Students</div><div class="meta-val">{{ $students->count() }} Students Enrolled</div></div>
  <div><div class="meta-label">Date Generated</div><div class="meta-val">{{ date('d/m/Y') }}</div></div>
  <div><div class="meta-label">Academic Year</div><div class="meta-val">{{ date('Y') . '–' . (date('Y') + 1) }}</div></div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th rowspan="2" style="width: 4%">Roll</th>
        <th rowspan="2" style="width: 12%">Reg. No.</th>
        <th class="tleft" rowspan="2" style="width: 22%">Student Name</th>
        <th colspan="4" style="background: #1e3a8a;">📖 Theory Lecture Hours</th>
        <th colspan="4" style="background: #15803d;">🔬 Practical Lab Hours</th>
        <th colspan="4" style="background: #0369a1;">📊 Consolidated Total Hours</th>
        <th rowspan="2" style="background: #7c2d12; width: 6%">CIA Attn<br>(/5M)</th>
        <th rowspan="2" style="background: #991b1b; width: 8%">Eligibility<br>Status</th>
      </tr>
      <tr>
        <!-- Theory -->
        <th style="background: #2563eb; width: 4.5%">Cond</th>
        <th style="background: #2563eb; width: 4.5%">Pres</th>
        <th style="background: #2563eb; width: 4.5%">Abs</th>
        <th style="background: #2563eb; width: 5.5%">%</th>
        <!-- Lab -->
        <th style="background: #16a34a; width: 4.5%">Cond</th>
        <th style="background: #16a34a; width: 4.5%">Pres</th>
        <th style="background: #16a34a; width: 4.5%">Abs</th>
        <th style="background: #16a34a; width: 5.5%">%</th>
        <!-- Combined Total -->
        <th style="background: #0284c7; width: 4.5%">Cond</th>
        <th style="background: #0284c7; width: 4.5%">Pres</th>
        <th style="background: #0284c7; width: 4.5%">Abs</th>
        <th style="background: #0284c7; width: 5.5%">%</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $st)
        @php
          // Theory Calculations
          $tTot  = $theoryTotals[$st->reg_no]['total']   ?? 0;
          $tPres = $theoryTotals[$st->reg_no]['present'] ?? 0;
          $tAbs  = max(0, $tTot - $tPres);
          $tPct  = $tTot > 0 ? round(($tPres / $tTot) * 100, 1) : 100.0;

          // Lab Calculations
          $lTot  = $labTotals[$st->reg_no]['total']   ?? 0;
          $lPres = $labTotals[$st->reg_no]['present'] ?? 0;
          $lAbs  = max(0, $lTot - $lPres);
          $lPct  = $lTot > 0 ? round(($lPres / $lTot) * 100, 1) : 100.0;

          // Consolidated Overall Hours
          $totCond = $tTot + $lTot;
          $totPres = $tPres + $lPres;
          $totAbs  = $tAbs + $lAbs;
          $overallPct = $totCond > 0 ? round(($totPres / $totCond) * 100, 1) : 100.0;

          // CIA Attendance Marks (Proportional out of 5 Marks as per Table 6.4)
          $ciaAttnMark = round(($overallPct / 100.0) * 5.0, 1);

          // SBTE Eligibility Status
          $short = ($overallPct < 75.0);
          $status = 'ELIGIBLE';
          $statusColor = '#15803d';
          if ($overallPct < 65.0) {
              $status = 'SHORTAGE';
              $statusColor = '#dc2626';
          } elseif ($overallPct < 75.0) {
              $status = 'CONDONATION';
              $statusColor = '#d97706';
          }

          // Visual classes
          $tClass = $tPct >= 75.0 ? 'pct-h' : ($tPct >= 65.0 ? 'pct-m' : 'pct-l');
          $lClass = $lPct >= 75.0 ? 'pct-h' : ($lPct >= 65.0 ? 'pct-m' : 'pct-l');
          $oClass = $overallPct >= 75.0 ? 'pct-h' : ($overallPct >= 65.0 ? 'pct-m' : 'pct-l');
        @endphp
        <tr class="{{ $short ? 'short-row' : '' }}">
          <td>{{ $st->roll_no }}</td>
          <td style="font-family: monospace; font-weight: 600;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
          <td class="tdleft">
            <strong>{{ $st->name }}</strong>
            @if($short) <span style="color:#dc2626; font-size:10px;">&#9888;</span>@endif
          </td>
          
          <!-- Theory Summary -->
          <td>{{ $tTot }}</td>
          <td style="font-weight: 600; color: #15803d;">{{ $tPres }}</td>
          <td style="color: {{ $tAbs > 0 ? '#dc2626' : '#64748b' }};">{{ $tAbs }}</td>
          <td class="{{ $tClass }}">{{ $tTot > 0 ? $tPct.'%' : '—' }}</td>

          <!-- Lab Summary -->
          <td>{{ $lTot }}</td>
          <td style="font-weight: 600; color: #15803d;">{{ $lPres }}</td>
          <td style="color: {{ $lAbs > 0 ? '#dc2626' : '#64748b' }};">{{ $lAbs }}</td>
          <td class="{{ $lClass }}">{{ $lTot > 0 ? $lPct.'%' : '—' }}</td>

          <!-- Consolidated Total Summary -->
          <td style="font-weight: 700;">{{ $totCond }}</td>
          <td style="font-weight: 700; color: #15803d;">{{ $totPres }}</td>
          <td style="font-weight: 700; color: {{ $totAbs > 0 ? '#dc2626' : '#64748b' }};">{{ $totAbs }}</td>
          <td class="{{ $oClass }}" style="font-size: 11.5px;">{{ $totCond > 0 ? $overallPct.'%' : '—' }}</td>

          <!-- CIA Marks & Status -->
          <td style="font-weight: 800; color: #1e3a5f;">{{ number_format($ciaAttnMark, 1) }}</td>
          <td style="font-weight: 800; font-size: 10px; color: {{ $statusColor }};">
            {{ $status }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Footer Signatures -->
<div class="sig-row">
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Staff In-Charge</div>
    <div style="font-weight:700;font-size:11px; margin-top: 2px;">{{ $assignedStaff->count() ? $assignedStaff->first()->name : 'Faculty Member' }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Head of Department</div>
    <div style="font-weight:700;font-size:11px; margin-top: 2px;">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch ?? 'Department Head') }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Principal</div>
    <div style="font-weight:700;font-size:11px; margin-top: 2px;">Carmel Polytechnic College</div>
  </div>
</div>

</div>

</body>
</html>
