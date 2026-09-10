<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Summative Series Tests Evaluation Register (R-2021) — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: #f1f5f9; color: #1e293b; padding: 24px 16px; }
  .report-container { max-width: 1200px; margin: 0 auto; background: #ffffff; padding: 24px 28px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 1200px; margin-left: auto; margin-right: auto; }
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
  table.data-table th { background: #1e3a5f; color: #fff; padding: 5px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 4px 4px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 6px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 30px; padding-top: 12px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 10.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 12mm 10mm 12mm 10mm; }
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
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Summative Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $courseFile->program ?? 'Technical Education' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Summative Assessment Evaluation Register (Series Tests)</div>
  </div>

  <table class="meta-table">
    <tr>
      <td class="lbl">Course Title</td>
      <td class="val"><strong>{{ $batchSubject->subject_name }}</strong></td>
      <td class="lbl">Course Code</td>
      <td class="val">{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</td>
    </tr>
    <tr>
      <td class="lbl">Semester & Scheme</td>
      <td class="val">Semester {{ $courseFile->semester ?? 'I' }} &bull; Revision 2021 (R-2021)</td>
      <td class="lbl">Summative Weightage</td>
      <td class="val"><strong>40% of CIA ({{ round(($courseFile->cia_marks ?: 50) * 0.40, 1) }} Marks)</strong></td>
    </tr>
    <tr>
      <td class="lbl">Evaluation Rubric</td>
      <td class="val" colspan="3">Procedure of Drawing (40%) + Final Drawing (30%) + Dimensioning (20%) + Neatness (10%) = Max 100 per test</td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th rowspan="2" style="width: 30px;">#</th>
        <th rowspan="2" style="width: 60px;">Roll No</th>
        <th rowspan="2" style="width: 85px;">Reg No</th>
        <th rowspan="2" class="td-left" style="width: 170px;">Student Name</th>
        <th colspan="5" style="background: #1e40af;">Summative Test 1 (Modules I & II)</th>
        <th colspan="5" style="background: #5b21b6;">Summative Test 2 (Modules III & IV)</th>
        <th rowspan="2" style="width: 60px;">Avg (100)</th>
        <th rowspan="2" style="width: 65px;">Summative ({{ round(($courseFile->cia_marks ?: 50) * 0.40, 1) }}M)</th>
      </tr>
      <tr>
        <th style="width: 32px; font-size: 8px;">Proc (40)</th>
        <th style="width: 32px; font-size: 8px;">Fin (30)</th>
        <th style="width: 32px; font-size: 8px;">Dim (20)</th>
        <th style="width: 32px; font-size: 8px;">Neat (10)</th>
        <th style="width: 36px; font-weight: 800; font-size: 8px;">Tot (100)</th>

        <th style="width: 32px; font-size: 8px;">Proc (40)</th>
        <th style="width: 32px; font-size: 8px;">Fin (30)</th>
        <th style="width: 32px; font-size: 8px;">Dim (20)</th>
        <th style="width: 32px; font-size: 8px;">Neat (10)</th>
        <th style="width: 36px; font-weight: 800; font-size: 8px;">Tot (100)</th>
      </tr>
    </thead>
    <tbody>
      @php
        $summativeMax = round(($courseFile->cia_marks ?: 50) * 0.40, 2);
      @endphp
      @foreach($students as $idx => $st)
        @php
          $stTests = $seriesTests->get($st->reg_no, collect());
          $t1 = $stTests->where('test_no', 'Test 1')->first();
          $t2 = $stTests->where('test_no', 'Test 2')->first();

          $t1Score = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : null;
          $t2Score = ($t2 && !$t2->is_absent) ? floatval($t2->total_score_100) : null;

          if ($t1Score !== null && $t2Score !== null) {
              $avg = ($t1Score + $t2Score) / 2.0;
          } elseif ($t1Score !== null) {
              $avg = $t1Score;
          } elseif ($t2Score !== null) {
              $avg = $t2Score;
          } else {
              $avg = 0.00;
          }
          $sumMark = round((($avg / 100.0) * $summativeMax) * 2) / 2;
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td>{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          
          <!-- Test 1 -->
          @if($t1)
            @if($t1->is_absent)
              <td colspan="5" style="color: #dc2626; font-weight: bold;">ABSENT</td>
            @else
              <td>{{ floatval($t1->procedure_drawing) }}</td>
              <td>{{ floatval($t1->final_drawing) }}</td>
              <td>{{ floatval($t1->dimensioning) }}</td>
              <td>{{ floatval($t1->neatness) }}</td>
              <td style="font-weight: 700;">{{ floatval($t1->total_score_100) }}</td>
            @endif
          @else
            <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
          @endif

          <!-- Test 2 -->
          @if($t2)
            @if($t2->is_absent)
              <td colspan="5" style="color: #dc2626; font-weight: bold;">ABSENT</td>
            @else
              <td>{{ floatval($t2->procedure_drawing) }}</td>
              <td>{{ floatval($t2->final_drawing) }}</td>
              <td>{{ floatval($t2->dimensioning) }}</td>
              <td>{{ floatval($t2->neatness) }}</td>
              <td style="font-weight: 700;">{{ floatval($t2->total_score_100) }}</td>
            @endif
          @else
            <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
          @endif

          <td><strong>{{ round($avg, 1) }}</strong></td>
          <td style="font-weight: 800; color: #7c3aed;">{{ $sumMark }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Course Faculty Signature</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Head of Department (HOD)</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Principal / Academic Head</div>
    </div>
  </div>
</div>

</body>
</html>
