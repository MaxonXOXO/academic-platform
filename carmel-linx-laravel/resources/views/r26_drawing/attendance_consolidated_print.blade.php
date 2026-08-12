<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated A4 Attendance Sheet — {{ $batchSubject->subject_name }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; background: #fff; color: #1e293b; padding: 24px; }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 8px 24px; font-size: 13px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 16px; }
  .institution-header h1 { font-size: 17px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 13px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 11px; color: #475569; margin-top: 2px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 12px; font-weight: 700; padding: 4px 16px; border-radius: 4px; margin-top: 6px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 5px 10px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 18%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 32%; }

  .summary-boxes { display: flex; gap: 10px; margin-bottom: 16px; }
  .box { flex: 1; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px; background: #f8fafc; text-align: center; }
  .box .num { font-size: 17px; font-weight: 800; color: #1e3a5f; }
  .box .title { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; }

  table.consolidated-table { width: 100%; border-collapse: collapse; font-size: 11px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.consolidated-table th { background: #1e3a5f; color: #fff; padding: 7px 8px; text-align: center; font-weight: 700; font-size: 10.5px; border: 1px solid #334155; }
  table.consolidated-table th.tleft { text-align: left; padding-left: 10px; }
  table.consolidated-table td { border: 1px solid #cbd5e1; padding: 5px 8px; text-align: center; white-space: nowrap; }
  table.consolidated-table td.tdleft { text-align: left; padding-left: 10px; }
  table.consolidated-table tr:nth-child(even) { background: #f8fafc; }

  .pct-ok { color: #15803d; font-weight: 700; }
  .pct-warn { color: #d97706; font-weight: 700; }
  .pct-danger { color: #dc2626; font-weight: 800; }
  tr.short-row { background: #fef2f2 !important; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 40px; padding-top: 15px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 11px; }
  .sig-line { border-bottom: 1px solid #475569; height: 40px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    .no-print { display: none !important; }
    body { padding: 0; font-size: 11px; }
    @page { size: A4 portrait; margin: 12mm; }
    tr { page-break-inside: avoid; }
  }
</style>
<script>
function goBackToDrawingLab() {
  if (window.opener && !window.opener.closed) {
    window.close();
  } else if (document.referrer && document.referrer.length > 0) {
    window.location.href = document.referrer;
  } else {
    window.location.href = "{{ url('/r26/classroom/drawing/' . $batchSubject->id) }}";
  }
}
</script>
</head>
<body>

<div class="no-print">
  <button class="back-btn" onclick="goBackToDrawingLab()">&#8592; Back to Drawing Lab</button>
  <button onclick="window.print()">&#128424; Print / Save A4 Sheet PDF</button>
</div>

<div class="institution-header">
  <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
  <h2>DEPARTMENT OF {{ strtoupper($classroom->department ?? $classroom->branch ?? 'MECHANICAL ENGINEERING') }}</h2>
  <p>Affiliated to SBTE Kerala &nbsp;|&nbsp; Approved by AICTE, New Delhi</p>
  <div class="report-title">CONSOLIDATED A4 ATTENDANCE SUMMARY & CIA SHEET</div>
</div>

<table class="meta-table">
  <tr>
    <td class="lbl">Course Name</td>
    <td class="val">{{ $batchSubject->subject_name }}</td>
    <td class="lbl">Course Code</td>
    <td class="val">{{ $batchSubject->subject_code }}</td>
  </tr>
  <tr>
    <td class="lbl">Semester</td>
    <td class="val">{{ $classroom->current_semester ?? $batchSubject->semester ?? 'S1' }}</td>
    <td class="lbl">Batch / Class</td>
    <td class="val">{{ $classroom->batch ?? $classroom->division ?? $classroom->classroom_id }}</td>
  </tr>
  <tr>
    <td class="lbl">Faculty In-Charge</td>
    <td class="val">{{ $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : 'Faculty In-Charge' }}</td>
    <td class="lbl">Head of Department</td>
    <td class="val">{{ $hod ? $hod->name : 'HOD' }}</td>
  </tr>
  <tr>
    <td class="lbl">Total Conducted Sessions</td>
    <td class="val">{{ $lessonPlans->count() }} Sessions (45 Contact Hours)</td>
    <td class="lbl">Academic Year</td>
    <td class="val">{{ date('Y') . '–' . (date('Y') + 1) }}</td>
  </tr>
</table>

@php
  $totalStuds = max(1, $students->count());
  $shortageCount = 0;
  $sumPct = 0;
  $evaluatedStuds = 0;

  foreach ($students as $st) {
      $tot = $attendanceTotals[$st->reg_no]['total'] ?? 0;
      $pres = $attendanceTotals[$st->reg_no]['present'] ?? 0;
      if ($tot > 0) {
          $pct = round(($pres / $tot) * 100, 1);
          $sumPct += $pct;
          $evaluatedStuds++;
          if ($pct < 75) $shortageCount++;
      }
  }
  $avgPct = $evaluatedStuds > 0 ? round($sumPct / $evaluatedStuds, 1) : 100.0;
@endphp

<div class="summary-boxes">
  <div class="box">
    <div class="num">{{ $students->count() }}</div>
    <div class="title">Enrolled Students</div>
  </div>
  <div class="box">
    <div class="num">{{ $lessonPlans->count() }}</div>
    <div class="title">Total Sessions Conducted</div>
  </div>
  <div class="box">
    <div class="num" style="color: {{ $avgPct >= 75 ? '#15803d' : '#dc2626' }};">{{ $avgPct }}%</div>
    <div class="title">Class Avg Attendance</div>
  </div>
  <div class="box">
    <div class="num" style="color: #dc2626;">{{ $shortageCount }}</div>
    <div class="title">Shortage (&lt; 75%)</div>
  </div>
  <div class="box">
    <div class="num" style="color: #15803d;">{{ $students->count() - $shortageCount }}</div>
    <div class="title">Eligible (&ge; 75%)</div>
  </div>
</div>

<table class="consolidated-table">
  <thead>
    <tr>
      <th style="width: 5%;">Roll</th>
      <th style="width: 18%;">Reg. Number</th>
      <th class="tleft" style="width: 32%;">Student Name</th>
      <th style="width: 10%;">Conducted</th>
      <th style="width: 10%;">Attended</th>
      <th style="width: 10%;">Att. %</th>
      <th style="width: 15%;">Status</th>
      <th style="width: 12%; background: #15803d;">CIA Marks (Max 5)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($students as $st)
      @php
        $tot = $attendanceTotals[$st->reg_no]['total'] ?? 0;
        $pres = $attendanceTotals[$st->reg_no]['present'] ?? 0;
        $pct = $attendanceTotals[$st->reg_no]['percentage'];
        $cia = $attendanceTotals[$st->reg_no]['cia_marks'];
        $isShortage = ($pct !== null && $pct < 75);

        $pctClass = 'pct-ok';
        if ($pct !== null) {
            if ($pct < 65) $pctClass = 'pct-danger';
            elseif ($pct < 75) $pctClass = 'pct-warn';
        }
      @endphp
      <tr class="{{ $isShortage ? 'short-row' : '' }}">
        <td style="font-weight: 700;">{{ $st->roll_no }}</td>
        <td style="font-family: monospace; font-weight: 600;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
        <td class="tdleft" style="font-weight: 600;">
          {{ $st->name }}
          @if($isShortage) <span style="color: #dc2626; font-size: 9px; font-weight: 800;">⚠ SHORTAGE</span> @endif
        </td>
        <td style="font-weight: 600;">{{ $tot > 0 ? $tot : '—' }}</td>
        <td style="font-weight: 600;">{{ $tot > 0 ? $pres : '—' }}</td>
        <td class="{{ $pctClass }}">{{ $pct !== null ? $pct.'%' : '—' }}</td>
        <td style="font-weight: 750; color: {{ $isShortage ? '#dc2626' : '#15803d' }};">
          {{ $pct === null ? '—' : ($isShortage ? 'SHORTAGE' : 'ELIGIBLE') }}
        </td>
        <td style="font-weight: 800; color: #15803d; background: rgba(22, 163, 74, 0.06);">
          {{ $tot > 0 ? $cia . ' / 5' : '—' }}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="sig-row">
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Staff In-Charge Signature</div>
    <div style="font-weight:700;margin-top:2px;">{{ $assignedStaff->count() ? $assignedStaff->first()->name : 'Faculty In-Charge' }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Head of Department (HOD)</div>
    <div style="font-weight:700;margin-top:2px;">{{ $hod ? $hod->name : 'Head of Department' }}</div>
  </div>
  <div class="sig-box">
    <div class="sig-line"></div>
    <div class="sig-lbl">Principal Signature</div>
    <div style="font-weight:700;margin-top:2px;">Principal, Carmel Polytechnic</div>
  </div>
</div>

</body>
</html>
