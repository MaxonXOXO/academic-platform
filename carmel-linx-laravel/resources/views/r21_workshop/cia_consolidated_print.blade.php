<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated CIA Register (75M) — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} {{ $batchSubject->subject_name }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: #f1f5f9; color: #1e293b; padding: 24px 16px; }
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

  table.data-table { width: 100%; border-collapse: collapse; font-size: 10px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 5px 5px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 8px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .rule-note { background: #eff6ff; border: 1px solid #bfdbfe; padding: 8px 12px; border-radius: 6px; font-size: 10px; color: #1e40af; margin-bottom: 14px; }

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
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Consolidated CIA Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Mechanical Workshop &bull; Continuous Internal Assessment (CIA 75M) Register</div>
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
      <td class="lbl">Scheme &amp; Type</td>
      <td class="val">Revision 2021 (Mechanical Workshop Practice)</td>
    </tr>
    <tr>
      <td class="lbl">CIA Maximum</td>
      <td class="val"><strong style="color: #1e3a5f;">75 Marks</strong></td>
      <td class="lbl">ESE Maximum</td>
      <td class="val"><strong style="color: #1e3a5f;">50 Marks (Total Course: 125 Marks)</strong></td>
    </tr>
    <tr>
      <td class="lbl">Assessment Split-up</td>
      <td class="val" colspan="3">
        Continuous Work (6 Works across 3 Trades): <strong>37.5M</strong> &bull; 
        Open-Ended Record (Rough 10 + Fair 10): <strong>7.5M</strong> &bull; 
        Trade Tests Average (3 Tests): <strong>15.0M</strong> &bull; 
        Attendance: <strong>15.0M</strong>
      </td>
    </tr>
  </table>

  <div class="rule-note">
    <strong>Assessment Policy (Revision 2021):</strong> In Workshop Practice, students are split into 2 batches, each undergoing 3 trades/sections in a semester. Each trade evaluates 2 continuous works (total 6 works /20 $\rightarrow$ scaled to 37.5M), Rough & Fair Records (/20 $\rightarrow$ scaled to 7.5M), and 3 Trade Tests (/15 each $\rightarrow$ avg 15M). Minimum eligibility mark for course pass in CIA is 40% (30 Marks out of 75).
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 35px;">Sl</th>
        <th style="width: 40px;">Roll</th>
        <th style="width: 100px;">Register No</th>
        <th class="td-left" style="min-width: 180px;">Student Name</th>
        <th style="width: 50px;">Batch</th>
        <th style="min-width: 160px;">Assigned Sections</th>
        <th style="width: 80px;">Work Eval<br>(37.5M)</th>
        <th style="width: 80px;">Record<br>(7.5M)</th>
        <th style="width: 80px;">Tests Avg<br>(15.0M)</th>
        <th style="width: 80px;">Attendance<br>(15.0M)</th>
        <th style="width: 75px;">Total CIA<br>(75M)</th>
        <th style="width: 65px;">Status</th>
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
          $stSecs = $sectionEvals->get($st->reg_no, collect());
          $stSummary = $ciaSummaries->get($st->reg_no);

          // Work 37.5
          $workScores = [];
          foreach ($assignedSecs as $sec) {
              $w = $stWorks->get($sec);
              $workScores[] = $w ? (float)$w->work_1_mark : 0.0;
              $workScores[] = $w ? (float)$w->work_2_mark : 0.0;
          }
          $avgW20 = count($workScores) > 0 ? (array_sum($workScores) / count($workScores)) : 0.0;
          $work375 = ($stSummary && $stSummary->override_work_375 !== null) ? (float)$stSummary->override_work_375 : round(($avgW20 / 20.0) * 37.5, 2);

          // Record 7.5
          $recScores = [];
          foreach ($assignedSecs as $sec) {
              $s = $stSecs->get($sec);
              $recScores[] = $s ? ((float)$s->rough_record_mark + (float)$s->fair_record_mark) : 0.0;
          }
          $avgR20 = count($recScores) > 0 ? (array_sum($recScores) / count($recScores)) : 0.0;
          $rec75 = ($stSummary && $stSummary->override_open_ended_75 !== null) ? (float)$stSummary->override_open_ended_75 : round(($avgR20 / 20.0) * 7.5, 2);

          // Test 15
          $testScores = [];
          foreach ($assignedSecs as $sec) {
              $s = $stSecs->get($sec);
              $testScores[] = ($s && !$s->is_test_absent) ? (float)$s->test_mark : 0.0;
          }
          $avgT15 = count($testScores) > 0 ? (array_sum($testScores) / count($testScores)) : 0.0;
          $test15 = ($stSummary && $stSummary->override_test_15 !== null) ? (float)$stSummary->override_test_15 : round($avgT15, 2);

          // Attendance 15
          $att = $attEvals->get($st->reg_no);
          $att15 = $att ? (float)($att->override_mark ?? $att->final_attendance_mark) : 15.0;

          // Total CIA 75
          $totalCia = ($stSummary && $stSummary->override_total_cia !== null) ? (int)$stSummary->override_total_cia : (int)round($work375 + $rec75 + $test15 + $att15);
          $passed = $totalCia >= 30;
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>Batch {{ $bNo }}</td>
          <td class="td-left" style="font-size: 9px;">{{ implode(', ', $assignedSecs) }}</td>
          <td>{{ number_format($work375, 2) }}</td>
          <td>{{ number_format($rec75, 2) }}</td>
          <td>{{ number_format($test15, 2) }}</td>
          <td>{{ number_format($att15, 2) }}</td>
          <td><strong>{{ $totalCia }}</strong></td>
          <td style="font-weight: 700; color: {{ $passed ? '#15803d' : '#b91c1c' }};">
            {{ $passed ? 'Passed' : 'Failed' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Instructor / Faculty</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Workshop Superintendent / HOD</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Principal</div>
    </div>
  </div>
</div>

</body>
</html>
