<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated 3-Section Workshop Register — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10px; background: #f1f5f9; color: #1e293b; padding: 20px 14px; }
  .report-container { max-width: 1250px; margin: 0 auto; background: #ffffff; padding: 22px 24px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 10px; background: #1e3a5f; border-radius: 8px; margin-bottom: 16px; max-width: 1250px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 7px 20px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 6px; margin-bottom: 10px; }
  .institution-header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 12px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 10px; color: #475569; margin-top: 1px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 4px; margin-top: 4px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 4px 6px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 16%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 34%; }

  table.data-table { width: 100%; border-collapse: collapse; font-size: 9px; border: 1px solid #64748b; margin-bottom: 16px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 5px 3px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 4px 3px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 5px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .badge-pass { background: #dcfce7; color: #166534; font-weight: 700; padding: 2px 4px; border-radius: 3px; border: 1px solid #86efac; font-size: 8.5px; }
  .badge-fail { background: #fee2e2; color: #991b1b; font-weight: 700; padding: 2px 4px; border-radius: 3px; border: 1px solid #fca5a5; font-size: 8.5px; }

  .sig-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 24px; padding-top: 10px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 9.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 30px; margin-bottom: 3px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 8mm; }
    body { background: #fff; padding: 0; color: #000; font-size: 8.5px; }
    .report-container { border: none; box-shadow: none; padding: 0; width: 100%; max-width: none; }
    .no-print { display: none !important; }
    .institution-header h1, .report-title, table.data-table th, .meta-table td.lbl { color: #000 !important; background: none !important; }
    .report-title { border: 1.5px solid #000; padding: 2px 6px; }
    table.data-table th { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; font-size: 8px; }
    table.data-table td, .meta-table td { border: 1px solid #000 !important; color: #000 !important; font-size: 8px; }
    .meta-table { border: 1.5px solid #000 !important; }
  }
</style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print 3-Section Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Mechanical Workshop &bull; Consolidated 3-Section Continuous Evaluation Register</div>
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
      <td class="lbl">Assessment Scheme</td>
      <td class="val">Total CIA: <strong>75 Marks</strong> | Total ESE: <strong>50 Marks</strong> (Total: 125M)</td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th rowspan="2" style="width: 25px;">Sl</th>
        <th rowspan="2" style="width: 30px;">Roll</th>
        <th rowspan="2" style="width: 75px;">Reg No</th>
        <th rowspan="2" class="td-left" style="min-width: 140px;">Student Name</th>
        <th rowspan="2" style="width: 35px;">Batch</th>
        <th colspan="3">Assigned Trade 1</th>
        <th colspan="3">Assigned Trade 2</th>
        <th colspan="3">Assigned Trade 3</th>
        <th rowspan="2" style="width: 50px;">Works<br>(37.5M)</th>
        <th rowspan="2" style="width: 45px;">Record<br>(7.5M)</th>
        <th rowspan="2" style="width: 45px;">Test Avg<br>(15M)</th>
        <th rowspan="2" style="width: 45px;">Att<br>(15M)</th>
        <th rowspan="2" style="width: 50px;">Total CIA<br>(75M)</th>
        <th rowspan="2" style="width: 45px;">Result</th>
      </tr>
      <tr>
        <th style="width: 42px;">Work /20</th>
        <th style="width: 38px;">Rec /20</th>
        <th style="width: 38px;">Test /15</th>
        <th style="width: 42px;">Work /20</th>
        <th style="width: 38px;">Rec /20</th>
        <th style="width: 38px;">Test /15</th>
        <th style="width: 42px;">Work /20</th>
        <th style="width: 38px;">Rec /20</th>
        <th style="width: 38px;">Test /15</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $idx => $st)
        @php
          $rNo = $st->reg_no;
          $score = $calculatedScores[$rNo] ?? [];
          $bNo = $score['batch_no'] ?? '1';
          $assSecs = $score['assigned_sections'] ?? [];
          $stWorks = $workEvals->get($rNo, collect());
          $stSecs = $sectionEvals->get($rNo, collect());
          $isPass = ($score['total_cia_75'] ?? 0) >= 30;

          $secData = [];
          for ($i = 0; $i < 3; $i++) {
              $sName = $assSecs[$i] ?? null;
              if ($sName) {
                  $w = $stWorks->get($sName);
                  $s = $stSecs->get($sName);
                  $wAvg = $w ? (float)$w->work_avg_20 : 0.0;
                  $rTot = $s ? (float)$s->record_total_20 : 0.0;
                  $tMark = ($s && !$s->is_test_absent) ? (float)$s->test_mark : 0.0;
                  $secData[] = ['name' => $sName, 'w' => $wAvg, 'r' => $rTot, 't' => $tMark];
              } else {
                  $secData[] = ['name' => '-', 'w' => 0.0, 'r' => 0.0, 't' => 0.0];
              }
          }
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $rNo }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>B{{ $bNo }}</td>
          <!-- Trade 1 -->
          <td>{{ number_format($secData[0]['w'], 1) }}</td>
          <td>{{ number_format($secData[0]['r'], 1) }}</td>
          <td>{{ number_format($secData[0]['t'], 1) }}</td>
          <!-- Trade 2 -->
          <td>{{ number_format($secData[1]['w'], 1) }}</td>
          <td>{{ number_format($secData[1]['r'], 1) }}</td>
          <td>{{ number_format($secData[1]['t'], 1) }}</td>
          <!-- Trade 3 -->
          <td>{{ number_format($secData[2]['w'], 1) }}</td>
          <td>{{ number_format($secData[2]['r'], 1) }}</td>
          <td>{{ number_format($secData[2]['t'], 1) }}</td>
          <!-- CIA Summaries -->
          <td style="font-weight: 700;">{{ number_format($score['work_score_375'] ?? 0, 1) }}</td>
          <td style="font-weight: 700;">{{ number_format($score['open_ended_75'] ?? 0, 1) }}</td>
          <td style="font-weight: 700;">{{ number_format($score['test_avg_15'] ?? 0, 1) }}</td>
          <td style="font-weight: 700;">{{ number_format($score['att_mark_15'] ?? 0, 1) }}</td>
          <td style="font-weight: 800; color: #1e3a5f; font-size: 10px;">{{ $score['total_cia_75'] ?? 0 }}</td>
          <td>
            @if($isPass)
              <span class="badge-pass">PASS</span>
            @else
              <span class="badge-fail">FAIL</span>
            @endif
          </td>
        </tr>
      @endforeach
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
