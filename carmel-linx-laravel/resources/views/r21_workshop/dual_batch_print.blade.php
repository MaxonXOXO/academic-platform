<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dual Batch Workshop Assessment Register — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10px; background: #f1f5f9; color: #1e293b; padding: 20px 14px; }
  .report-container { max-width: 1200px; margin: 0 auto; background: #ffffff; padding: 22px 26px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 10px; background: #1e3a5f; border-radius: 8px; margin-bottom: 16px; max-width: 1200px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 7px 20px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 6px; margin-bottom: 10px; }
  .institution-header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 12px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 10px; color: #475569; margin-top: 1px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 4px; margin-top: 4px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 4px 6px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 16%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 34%; }

  .batch-header-bar { background: #1e293b; color: #fff; padding: 6px 12px; border-radius: 5px; font-size: 11px; font-weight: 700; margin-top: 14px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
  .batch-1-bar { background: #0369a1; }
  .batch-2-bar { background: #6b21a8; }

  table.data-table { width: 100%; border-collapse: collapse; font-size: 9.5px; border: 1px solid #64748b; margin-bottom: 14px; }
  table.data-table th { background: #0f172a; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 4px 4px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 6px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .badge-pass { background: #dcfce7; color: #166534; font-weight: 700; padding: 2px 5px; border-radius: 3px; border: 1px solid #86efac; }
  .badge-fail { background: #fee2e2; color: #991b1b; font-weight: 700; padding: 2px 5px; border-radius: 3px; border: 1px solid #fca5a5; }

  .sig-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 24px; padding-top: 10px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 9.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 30px; margin-bottom: 3px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 8mm; }
    body { background: #fff; padding: 0; color: #000; }
    .report-container { border: none; box-shadow: none; padding: 0; width: 100%; max-width: none; }
    .no-print { display: none !important; }
    .institution-header h1, .report-title, table.data-table th, .meta-table td.lbl { color: #000 !important; background: none !important; }
    .batch-header-bar { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; }
    table.data-table th { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; }
    table.data-table td, .meta-table td { border: 1px solid #000 !important; color: #000 !important; }
    .page-break { page-break-before: always; }
  }
</style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Dual Batch Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Dual Batch (B1 &amp; B2) Workshop Internal Assessment Comparative Register</div>
  </div>

  <table class="meta-table">
    <tr>
      <td class="lbl">Course Title</td>
      <td class="val"><strong>{{ $batchSubject->subject_name }}</strong></td>
      <td class="lbl">Course Code</td>
      <td class="val"><strong>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</strong></td>
    </tr>
    <tr>
      <td class="lbl">Semester &amp; Class</td>
      <td class="val">Semester {{ $classroom->current_semester ?? $courseFile->semester ?? '3' }} ({{ $classroom->classroom_id ?? $batchSubject->classroom_id }})</td>
      <td class="lbl">CIA Scheme</td>
      <td class="val">Max CIA: <strong>75 Marks</strong> &bull; Work 37.5M + Record 7.5M + Test 15.0M + Att 15.0M</td>
    </tr>
  </table>

  <!-- BATCH 1 SECTION -->
  @php
    $b1Trades = $batchAllocation['1'] ?? ['Fitting', 'Smithy', 'Welding'];
    $b2Trades = $batchAllocation['2'] ?? ['Machine shop', 'Sheet metal', 'Foundry'];
  @endphp
  <div class="batch-header-bar batch-1-bar">
    <span><i class="fa-solid fa-users me-1"></i> BATCH 1 (B1) — Allocated Trades: {{ implode(' &bull; ', $b1Trades) }}</span>
    <span>Count: {{ $b1Students->count() }} Students</span>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 30px;">Sl</th>
        <th style="width: 35px;">Roll</th>
        <th style="width: 85px;">Reg No</th>
        <th class="td-left" style="min-width: 170px;">Student Name</th>
        <th style="width: 75px;">{{ $b1Trades[0] ?? 'Sec 1' }}<br><small>(Work /20)</small></th>
        <th style="width: 75px;">{{ $b1Trades[1] ?? 'Sec 2' }}<br><small>(Work /20)</small></th>
        <th style="width: 75px;">{{ $b1Trades[2] ?? 'Sec 3' }}<br><small>(Work /20)</small></th>
        <th style="width: 65px;">Works<br>(37.5M)</th>
        <th style="width: 60px;">Record<br>(7.5M)</th>
        <th style="width: 60px;">Tests<br>(15M)</th>
        <th style="width: 60px;">Att<br>(15M)</th>
        <th style="width: 65px;">Total CIA<br>(75M)</th>
        <th style="width: 55px;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($b1Students as $idx => $st)
        @php
          $sc = $calculatedScores[$st->reg_no] ?? [];
          $isPass = ($sc['total_cia_75'] ?? 0) >= 30;
          $wScores = $sc['work_scores'] ?? [0, 0, 0];
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>{{ number_format($wScores[0] ?? 0, 1) }}</td>
          <td>{{ number_format($wScores[1] ?? 0, 1) }}</td>
          <td>{{ number_format($wScores[2] ?? 0, 1) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['work_score_375'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['open_ended_75'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['test_avg_15'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['att_mark_15'] ?? 0, 2) }}</td>
          <td style="font-weight: 800; color: #1e3a5f; font-size: 10.5px;">{{ $sc['total_cia_75'] ?? 0 }}</td>
          <td>
            @if($isPass) <span class="badge-pass">PASS</span>
            @else <span class="badge-fail">FAIL</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="13" style="padding: 12px; color: #64748b;">No students allocated to Batch 1.</td></tr>
      @endforelse
    </tbody>
  </table>

  <!-- BATCH 2 SECTION -->
  <div class="batch-header-bar batch-2-bar" style="margin-top: 18px;">
    <span><i class="fa-solid fa-users me-1"></i> BATCH 2 (B2) — Allocated Trades: {{ implode(' &bull; ', $b2Trades) }}</span>
    <span>Count: {{ $b2Students->count() }} Students</span>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 30px;">Sl</th>
        <th style="width: 35px;">Roll</th>
        <th style="width: 85px;">Reg No</th>
        <th class="td-left" style="min-width: 170px;">Student Name</th>
        <th style="width: 75px;">{{ $b2Trades[0] ?? 'Sec 1' }}<br><small>(Work /20)</small></th>
        <th style="width: 75px;">{{ $b2Trades[1] ?? 'Sec 2' }}<br><small>(Work /20)</small></th>
        <th style="width: 75px;">{{ $b2Trades[2] ?? 'Sec 3' }}<br><small>(Work /20)</small></th>
        <th style="width: 65px;">Works<br>(37.5M)</th>
        <th style="width: 60px;">Record<br>(7.5M)</th>
        <th style="width: 60px;">Tests<br>(15M)</th>
        <th style="width: 60px;">Att<br>(15M)</th>
        <th style="width: 65px;">Total CIA<br>(75M)</th>
        <th style="width: 55px;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($b2Students as $idx => $st)
        @php
          $sc = $calculatedScores[$st->reg_no] ?? [];
          $isPass = ($sc['total_cia_75'] ?? 0) >= 30;
          $wScores = $sc['work_scores'] ?? [0, 0, 0];
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>{{ number_format($wScores[0] ?? 0, 1) }}</td>
          <td>{{ number_format($wScores[1] ?? 0, 1) }}</td>
          <td>{{ number_format($wScores[2] ?? 0, 1) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['work_score_375'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['open_ended_75'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['test_avg_15'] ?? 0, 2) }}</td>
          <td style="font-weight: 700;">{{ number_format($sc['att_mark_15'] ?? 0, 2) }}</td>
          <td style="font-weight: 800; color: #1e3a5f; font-size: 10.5px;">{{ $sc['total_cia_75'] ?? 0 }}</td>
          <td>
            @if($isPass) <span class="badge-pass">PASS</span>
            @else <span class="badge-fail">FAIL</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="13" style="padding: 12px; color: #64748b;">No students allocated to Batch 2.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Instructor(s)</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Superintendent</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Head of Department</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Principal</div>
    </div>
  </div>
</div>

</body>
</html>
