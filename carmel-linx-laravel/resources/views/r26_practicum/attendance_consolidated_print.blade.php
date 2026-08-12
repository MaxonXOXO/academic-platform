<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated Attendance Report — {{ $batchSubject->subject_name }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; background: #fff; color: #1e293b; padding: 30px; }
  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 20px; }
  .institution-header h1 { font-size: 18px; font-weight: 700; color: #1e3a5f; }
  .institution-header p { font-size: 12px; color: #475569; margin-top: 3px; }
  .meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px 20px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
  .meta-label { font-weight: 600; color: #475569; font-size: 11px; text-transform: uppercase; }
  .meta-val { font-weight: 700; color: #1e293b; font-size: 13px; }
  .table-wrap { margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; font-size: 11.5px; }
  thead th { background: #1e3a5f; color: #fff; padding: 8px 6px; text-align: center; font-weight: 600; font-size: 11px; border: 1px solid #2d5a8a; }
  thead th.tleft { text-align: left; padding-left: 10px; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tbody td { border: 1px solid #cbd5e1; padding: 6px; text-align: center; font-size: 11.5px; }
  tbody td.tdleft { text-align: left; padding-left: 10px; }
  .pct-h { color: #15803d; font-weight: 700; }
  .pct-m { color: #d97706; font-weight: 700; }
  .pct-l { color: #dc2626; font-weight: 700; }
  tr.short-row { background: #fef2f2 !important; }
  .no-print { text-align: center; padding: 14px; background: #1e3a5f; border-radius: 8px; margin-bottom: 25px; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 10px 30px; font-size: 14px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }
  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 50px; border-top: 1px solid #cbd5e1; padding-top: 20px; }
  .sig-box { text-align: center; font-size: 12px; }
  .sig-line { border-bottom: 1px solid #475569; height: 45px; margin-bottom: 5px; }
  .sig-lbl { font-weight: 600; color: #475569; font-size: 11px; }
  @media print {
    @page { size: A4 portrait; margin: 15mm; }
    .no-print { display: none !important; }
    body { padding: 0; }
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

<div class="institution-header">
  <h1>CARMEL POLYTECHNIC COLLEGE</h1>
  <p>Affiliated to SBTE Kerala &nbsp;|&nbsp; Approved by AICTE, New Delhi</p>
  <p style="font-size:13px;font-weight:700;margin-top:6px;color:#1e3a5f;">CONSOLIDATED ATTENDANCE REPORT &mdash; REVISION 2026 PRACTICUM</p>
</div>

<div class="meta-grid">
  <div><div class="meta-label">Subject</div><div class="meta-val">{{ $batchSubject->subject_name }}</div></div>
  <div><div class="meta-label">Subject Code</div><div class="meta-val">{{ $batchSubject->subject_code }}</div></div>
  <div><div class="meta-label">Semester</div><div class="meta-val">{{ $classroom->current_semester ?? $batchSubject->semester ?? 'N/A' }}</div></div>
  <div><div class="meta-label">Department</div><div class="meta-val">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch ?? 'N/A') }}</div></div>
  <div><div class="meta-label">Batch / Division</div><div class="meta-val">{{ $classroom->batch ?? $classroom->division ?? $classroom->classroom_id }}</div></div>
  <div><div class="meta-label">Faculty In-Charge</div><div class="meta-val">{{ $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : '—' }}</div></div>
  <div><div class="meta-label">Academic Year</div><div class="meta-val">{{ date('Y') . '–' . (date('Y') + 1) }}</div></div>
  <div><div class="meta-label">Total Students</div><div class="meta-val">{{ $students->count() }}</div></div>
  <div><div class="meta-label">Report Generated</div><div class="meta-val">{{ date('d M Y, h:i A') }}</div></div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th rowspan="2" style="width: 5%">Roll</th>
        <th rowspan="2" style="width: 15%">Reg. No.</th>
        <th class="tleft" rowspan="2" style="width: 30%">Student Name</th>
        <th colspan="3" style="background: #1e3a5f;">📖 Theory Lecture</th>
        <th colspan="3" style="background: #14532d;">🔬 Lab Practical</th>
        <th rowspan="2" style="background: #b45309; width: 10%">Combined Avg.</th>
        <th rowspan="2" style="background: #991b1b; width: 8%">Status</th>
      </tr>
      <tr>
        <th style="background: #2563eb; width: 7%">Cond.</th>
        <th style="background: #2563eb; width: 7%">Pres.</th>
        <th style="background: #2563eb; width: 8%">%</th>
        <th style="background: #16a34a; width: 7%">Cond.</th>
        <th style="background: #16a34a; width: 7%">Pres.</th>
        <th style="background: #16a34a; width: 8%">%</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $st)
        @php
          // Theory Calculations
          $tTot  = $theoryTotals[$st->reg_no]['total']   ?? 0;
          $tPres = $theoryTotals[$st->reg_no]['present']  ?? 0;
          $tPct  = $tTot > 0 ? round(($tPres / $tTot) * 100, 1) : 100.0;

          // Lab Calculations
          $lTot  = $labTotals[$st->reg_no]['total']   ?? 0;
          $lPres = $labTotals[$st->reg_no]['present']  ?? 0;
          $lPct  = $lTot > 0 ? round(($lPres / $lTot) * 100, 1) : 100.0;

          // Combined Average
          $avgPct = round(($tPct + $lPct) / 2, 1);
          $short = ($avgPct < 75.0);

          // Classes
          $tClass = $tPct >= 75.0 ? 'pct-h' : ($tPct >= 65.0 ? 'pct-m' : 'pct-l');
          $lClass = $lPct >= 75.0 ? 'pct-h' : ($lPct >= 65.0 ? 'pct-m' : 'pct-l');
          $avgClass = $avgPct >= 75.0 ? 'pct-h' : ($avgPct >= 65.0 ? 'pct-m' : 'pct-l');
        @endphp
        <tr class="{{ $short ? 'short-row' : '' }}">
          <td>{{ $st->roll_no }}</td>
          <td style="font-family: monospace; font-weight: 600;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
          <td class="tdleft">{{ $st->name }}@if($short) <span style="color:#dc2626; font-size:10px;">&#9888;</span>@endif</td>
          
          <!-- Theory Summary -->
          <td>{{ $tTot }}</td>
          <td>{{ $tPres }}</td>
          <td class="{{ $tClass }}">{{ $tTot > 0 ? $tPct.'%' : '—' }}</td>

          <!-- Lab Summary -->
          <td>{{ $lTot }}</td>
          <td>{{ $lPres }}</td>
          <td class="{{ $lClass }}">{{ $lTot > 0 ? $lPct.'%' : '—' }}</td>

          <!-- Combined Avg & Status -->
          <td style="font-weight: 700;" class="{{ $avgClass }}">{{ $avgPct }}%</td>
          <td style="font-weight: 750; color: {{ $short ? '#dc2626' : '#15803d' }};">
            {{ $short ? 'SHORTAGE' : 'ELIGIBLE' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="sig-row">
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">Staff In-Charge</div><div style="font-weight:700;font-size:12px;">{{ $assignedStaff->count() ? $assignedStaff->first()->name : '_________________' }}</div></div>
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">HOD Signature</div><div style="font-weight:700;font-size:12px;">Head of Department</div></div>
  <div class="sig-box"><div class="sig-line"></div><div class="sig-lbl">Principal Signature</div><div style="font-weight:700;font-size:12px;">Principal</div></div>
</div>

</body>
</html>

