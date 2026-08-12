<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subject-Wise Attendance & CIA Report — {{ $batchSubject->subject_name }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; background: #f1f5f9; color: #1e293b; padding: 20px 16px; }
  .report-container { max-width: 1380px; margin: 0 auto; background: #ffffff; padding: 24px 28px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 1380px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 8px 24px; font-size: 13px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 14px; }
  .institution-header h1 { font-size: 17px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 13px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 11px; color: #475569; margin-top: 2px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 12px; font-weight: 700; padding: 4px 16px; border-radius: 4px; margin-top: 6px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 5px 10px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 15%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 35%; }

  .summary-boxes { display: flex; gap: 12px; margin-bottom: 16px; }
  .box { flex: 1; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; background: #f8fafc; text-align: center; }
  .box .num { font-size: 18px; font-weight: 800; color: #1e3a5f; }
  .box .title { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; }

  .section-hdr { background: #1e3a5f; color: #fff; font-size: 12px; font-weight: 700; padding: 6px 12px; margin-bottom: 8px; border-radius: 4px; }

  .att-table-wrap { overflow-x: auto; margin-bottom: 18px; }
  table.matrix-table { width: 100%; border-collapse: collapse; font-size: 10.5px; border: 1px solid #94a3b8; }
  table.matrix-table th { background: #1e3a5f; color: #fff; padding: 5px 4px; text-align: center; font-weight: 700; font-size: 9.5px; border: 1px solid #334155; }
  table.matrix-table th.tleft { text-align: left; padding-left: 6px; }
  table.matrix-table td { border: 1px solid #cbd5e1; padding: 4px 3px; text-align: center; white-space: nowrap; }
  table.matrix-table td.tdleft { text-align: left; padding-left: 6px; }
  table.matrix-table tr:nth-child(even) { background: #f8fafc; }
  
  .sP { color: #15803d; font-weight: 800; }
  .sL { color: #d97706; font-weight: 800; }
  .sA { color: #dc2626; font-weight: 800; }
  .sN { color: #94a3b8; }

  .pct-ok { color: #15803d; font-weight: 700; }
  .pct-warn { color: #d97706; font-weight: 700; }
  .pct-danger { color: #dc2626; font-weight: 800; }
  tr.short-row { background: #fef2f2 !important; }

  .rubric-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 10.5px; border: 1px solid #cbd5e1; }
  .rubric-table th { background: #475569; color: #fff; padding: 5px 8px; font-size: 10px; text-align: center; }
  .rubric-table td { padding: 4px 8px; border: 1px solid #e2e8f0; text-align: center; font-weight: 600; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 35px; padding-top: 15px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 11px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 10mm 12mm 10mm 12mm; }
    .no-print { display: none !important; }
    body { background: #fff; padding: 0; margin: 0; font-size: 11px; }
    .report-container { border: none; box-shadow: none; padding: 3mm 4mm; max-width: 100%; width: 100%; }
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
  <button onclick="window.print()">&#128424; Print / Save Attendance PDF</button>
</div>

<div class="report-container">

<div class="institution-header">
  <h1>CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
  <h2>DEPARTMENT OF {{ strtoupper($classroom->department ?? $classroom->branch ?? 'MECHANICAL ENGINEERING') }}</h2>
  <p>Affiliated to SBTE Kerala &nbsp;|&nbsp; Approved by AICTE, New Delhi</p>
  <div class="report-title">SUBJECT-WISE ATTENDANCE REGISTER & CIA ATTENDANCE REPORT</div>
</div>

<table class="meta-table">
  <tr>
    <td class="lbl">Course Name</td>
    <td class="val">{{ $batchSubject->subject_name }}</td>
    <td class="lbl">Course Code</td>
    <td class="val">{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</td>
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
    <td class="lbl">Total Conducted Slots</td>
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
    <div class="title">Class Average Attendance</div>
  </div>
  <div class="box">
    <div class="num" style="color: #dc2626;">{{ $shortageCount }}</div>
    <div class="title">Shortage Students (&lt; 75%)</div>
  </div>
  <div class="box">
    <div class="num" style="color: #15803d;">{{ $students->count() - $shortageCount }}</div>
    <div class="title">Good Standing (&ge; 75%)</div>
  </div>
</div>

<div class="section-hdr">
  📄 1. STUDENT ATTENDANCE REGISTER MATRIX & CIA MARKS (TABLE 2.1 - MAX 5 MARKS)
</div>

<div class="att-table-wrap">
  <table class="matrix-table">
    <thead>
      <tr>
        <th class="tleft" rowspan="2" style="width:35px;">Roll</th>
        <th class="tleft" rowspan="2" style="width:90px;">Reg. No.</th>
        <th class="tleft" rowspan="2" style="min-width:140px;">Student Name</th>
        @foreach($lessonPlans as $plan)
          <th style="min-width:26px;">S{{ $plan->day_no }}<br><span style="font-size:8px;font-weight:400;">{{ $plan->co_id }}</span></th>
        @endforeach
        <th rowspan="2" style="background:#0f2942;">Pres.</th>
        <th rowspan="2" style="background:#0f2942;">Total</th>
        <th rowspan="2" style="background:#0f2942;">Att. %</th>
        <th rowspan="2" style="background:#0f2942;">Status</th>
        <th rowspan="2" style="background:#15803d;color:#fff;">CIA Marks (Max 5)</th>
      </tr>
      <tr>
        @foreach($lessonPlans as $plan)
          <th style="font-size:8px;color:#a7c8f0;background:#1e3a5f;font-weight:400;">
            @if($plan->actual_date) {{ \Carbon\Carbon::parse($plan->actual_date)->format('d/m') }}
            @elseif($plan->proposed_date) {{ \Carbon\Carbon::parse($plan->proposed_date)->format('d/m') }}
            @else --
            @endif
          </th>
        @endforeach
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
          <td class="tdleft" style="font-weight:700;">{{ $st->roll_no }}</td>
          <td class="tdleft" style="font-family:monospace;font-size:10px;">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
          <td class="tdleft" style="font-weight:600;">
            {{ $st->name }}
            @if($isShortage) <span style="color:#dc2626;font-size:9px;font-weight:800;">[SHORTAGE]</span> @endif
          </td>
          @foreach($lessonPlans as $plan)
            @php $stVal = $attendanceMatrix[$st->reg_no][$plan->id] ?? null; @endphp
            <td class="s{{ $stVal ? $stVal[0] : 'N' }}">{{ $stVal ? $stVal[0] : '—' }}</td>
          @endforeach
          <td style="font-weight:700;color:#1e3a5f;">{{ $tot > 0 ? $pres : '—' }}</td>
          <td style="font-weight:700;color:#1e3a5f;">{{ $tot > 0 ? $tot : '—' }}</td>
          <td class="{{ $pctClass }}">{{ $pct !== null ? $pct.'%' : '—' }}</td>
          <td style="font-weight:700;color:{{ $isShortage ? '#dc2626' : '#15803d' }};">
            {{ $pct === null ? '—' : ($isShortage ? 'SHORTAGE' : 'ELIGIBLE') }}
          </td>
          <td style="font-weight:800;color:#15803d;background:rgba(22,163,74,0.06);">
            {{ $tot > 0 ? $cia . ' / 5' : '—' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="section-hdr" style="margin-top:20px;">
  📊 2. CIA ATTENDANCE MARKS CALCULATION RUBRIC (TABLE 2.1 STANDARDS)
</div>

<table class="rubric-table">
  <thead>
    <tr>
      <th>Attendance Percentage Range</th>
      <th>&ge; 90%</th>
      <th>80% – 89%</th>
      <th>75% – 79%</th>
      <th>70% – 74%</th>
      <th>65% – 69%</th>
      <th>&lt; 65%</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="font-weight:700;background:#f8fafc;">Awarded CIA Marks (Max 5)</td>
      <td style="color:#15803d;font-size:12px;">5 Marks</td>
      <td style="color:#15803d;font-size:12px;">4 Marks</td>
      <td style="color:#15803d;font-size:12px;">3 Marks</td>
      <td style="color:#d97706;font-size:12px;">2 Marks</td>
      <td style="color:#d97706;font-size:12px;">1 Mark</td>
      <td style="color:#dc2626;font-size:12px;">0 Marks</td>
    </tr>
  </tbody>
</table>

<div class="section-hdr">
  📅 3. COMPREHENSIVE CLASS LOGS & LESSON PLAN SCHEDULE REGISTER
</div>

<table class="matrix-table" style="margin-bottom:20px;">
  <thead>
    <tr>
      <th style="width:45px;">Slot #</th>
      <th style="width:60px;">CO Tag</th>
      <th style="width:80px;">Proposed Date</th>
      <th style="width:80px;">Actual Date</th>
      <th class="tleft">Topic & Practical Experiment Description</th>
      <th style="width:70px;">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($lessonPlans as $plan)
      <tr>
        <td style="font-weight:700;">Slot {{ $plan->day_no }}</td>
        <td><span style="background:#1e3a5f;color:#fff;padding:1px 5px;border-radius:3px;font-size:9px;">{{ $plan->co_id }}</span></td>
        <td>{{ $plan->proposed_date ? \Carbon\Carbon::parse($plan->proposed_date)->format('d/m/Y') : '—' }}</td>
        <td>{{ $plan->actual_date ? \Carbon\Carbon::parse($plan->actual_date)->format('d/m/Y') : '—' }}</td>
        <td class="tdleft">{{ $plan->topic_content }}</td>
        <td>
          <span style="font-weight:700;color: {{ $plan->status === 'Completed' ? '#15803d' : '#d97706' }};">
            {{ $plan->status ?: 'Scheduled' }}
          </span>
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
