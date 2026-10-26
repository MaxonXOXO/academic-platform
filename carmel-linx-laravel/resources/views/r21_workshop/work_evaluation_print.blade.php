<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Continuous Work Evaluation Register (37.5M) — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} {{ $batchSubject->subject_name }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5px; background: #f1f5f9; color: #1e293b; padding: 24px 16px; }
  .report-container { max-width: 1100px; margin: 0 auto; background: #ffffff; padding: 24px 28px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 1100px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 8px 24px; font-size: 13px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 14px; }
  .institution-header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 12px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 10.5px; color: #475569; margin-top: 1px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 14px; border-radius: 4px; margin-top: 4px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 4px 8px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 18%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 32%; }

  table.data-table { width: 100%; border-collapse: collapse; font-size: 9.5px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 6px 3px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 4px 4px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 6px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 30px; padding-top: 12px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 10.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 10mm; }
    body { background: #fff; padding: 0; color: #000; }
    .report-container { border: none; box-shadow: none; padding: 0; width: 100%; max-width: none; }
    .no-print { display: none !important; }
    .institution-header h1, .report-title, table.data-table th, .meta-table td.lbl { color: #000 !important; background: none !important; }
    .report-title { border: 1.5px solid #000; padding: 2px 8px; }
    table.data-table th { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; }
    table.data-table td, .meta-table td { border: 1px solid #000 !important; color: #000 !important; }
    .meta-table { border: 1.5px solid #000 !important; }
  }
</style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Continuous Work Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Mechanical Workshop &bull; Continuous Work Evaluation Register (37.5M)</div>
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
      <td class="lbl">Continuous Work Weightage</td>
      <td class="val"><strong>37.5 Marks</strong> (6 Works / 20M converted to 37.5M)</td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th rowspan="2" style="width: 28px;">Sl</th>
        <th rowspan="2" style="width: 32px;">Roll</th>
        <th rowspan="2" style="width: 80px;">Reg No</th>
        <th rowspan="2" class="td-left" style="min-width: 140px;">Student Name</th>
        <th rowspan="2" style="width: 35px;">Batch</th>
        <th colspan="3">Section / Trade 1</th>
        <th colspan="3">Section / Trade 2</th>
        <th colspan="3">Section / Trade 3</th>
        <th rowspan="2" style="width: 60px;">Avg (/20M)</th>
        <th rowspan="2" style="width: 65px;">Scaled (37.5M)</th>
      </tr>
      <tr>
        <th style="min-width: 65px;">Trade Name</th>
        <th style="min-width: 80px;">Works (20M Each)</th>
        <th style="width: 45px;">Avg /20</th>
        <th style="min-width: 65px;">Trade Name</th>
        <th style="min-width: 80px;">Works (20M Each)</th>
        <th style="width: 45px;">Avg /20</th>
        <th style="min-width: 65px;">Trade Name</th>
        <th style="min-width: 80px;">Works (20M Each)</th>
        <th style="width: 45px;">Avg /20</th>
      </tr>
    </thead>
    <tbody>
      @php
        $defaultBatches = $courseFile->batch_allocation ?? ['1' => ['Fitting', 'Smithy', 'Welding'], '2' => ['Machine shop', 'Sheet metal', 'Foundry']];
      @endphp
      @foreach($students as $idx => $st)
        @php
          $alloc = $allocations->get($st->reg_no);
          $bNo = $alloc->batch_no ?? '1';
          $assignedSecs = $alloc->assigned_sections ?? ($defaultBatches[$bNo] ?? ['Fitting', 'Smithy', 'Welding']);
          $stWorks = $workEvals->get($st->reg_no, collect());

          $secEntries = [];
          $secAvgs = [];
          foreach ($assignedSecs as $sec) {
              $w = $stWorks->get($sec);
              $w1 = $w ? (float)$w->work_1_mark : 0.0;
              $w2 = $w ? (float)$w->work_2_mark : 0.0;
              $w3 = $w ? (float)($w->work_3_mark ?? 0) : 0.0;
              $w4 = $w ? (float)($w->work_4_mark ?? 0) : 0.0;
              $alum = $w ? (float)($w->alum_work_mark ?? 0) : 0.0;

              if ($sec === 'Machine shop') {
                  $avg = round(($w1 + $w2 + $w3 + $w4) / 4.0, 2);
                  $desc = "W1:{$w1}, W2:{$w2}, W3:{$w3}, W4:{$w4}";
              } elseif ($sec === 'Sheet metal') {
                  $avg = ($alum > 0) ? round(($w1 + $w2 + $alum) / 3.0, 2) : round(($w1 + $w2) / 2.0, 2);
                  $desc = ($alum > 0) ? "W1:{$w1}, W2:{$w2}, Alum:{$alum}" : "W1:{$w1}, W2:{$w2}";
              } else {
                  $avg = round(($w1 + $w2) / 2.0, 2);
                  $desc = "W1:{$w1}, W2:{$w2}";
              }
              $secEntries[] = ['name' => $sec, 'desc' => $desc, 'avg' => $avg];
              $secAvgs[] = $avg;
          }

          while (count($secEntries) < 3) {
              $secEntries[] = ['name' => '-', 'desc' => '-', 'avg' => 0.0];
              $secAvgs[] = 0.0;
          }

          $overallAvg20 = count($secAvgs) > 0 ? round(array_sum($secAvgs) / count($secAvgs), 2) : 0.0;
          $stSummary = isset($ciaSummaries) ? $ciaSummaries->get($st->reg_no) : null;
          $scaled375 = ($stSummary && $stSummary->override_work_375 !== null) 
              ? (float)$stSummary->override_work_375 
              : round(($overallAvg20 / 20.0) * 37.5, 2);
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>B{{ $bNo }}</td>
          <!-- Trade 1 -->
          <td><strong>{{ $secEntries[0]['name'] }}</strong></td>
          <td style="font-size: 8.5px;">{{ $secEntries[0]['desc'] }}</td>
          <td><strong>{{ number_format($secEntries[0]['avg'], 1) }}</strong></td>
          <!-- Trade 2 -->
          <td><strong>{{ $secEntries[1]['name'] }}</strong></td>
          <td style="font-size: 8.5px;">{{ $secEntries[1]['desc'] }}</td>
          <td><strong>{{ number_format($secEntries[1]['avg'], 1) }}</strong></td>
          <!-- Trade 3 -->
          <td><strong>{{ $secEntries[2]['name'] }}</strong></td>
          <td style="font-size: 8.5px;">{{ $secEntries[2]['desc'] }}</td>
          <td><strong>{{ number_format($secEntries[2]['avg'], 1) }}</strong></td>
          <!-- Overall -->
          <td style="font-weight: 700;">{{ number_format($overallAvg20, 2) }}</td>
          <td style="font-weight: 800; color: #1e3a5f; font-size: 10px;">{{ number_format($scaled375, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Instructor</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Superintendent</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Principal</div>
    </div>
  </div>
</div>

</body>
</html>
