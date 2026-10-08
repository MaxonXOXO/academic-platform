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
  table.data-table th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 5px 4px; text-align: center; }
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
    <div class="report-title">Summative Series Tests Evaluation Register</div>
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
      <td class="val">Semester {{ $courseFile->semester ?? '3' }} &bull; Revision 2021 (Practical / Drawing Lab)</td>
      <td class="lbl">Series Tests Weightage</td>
      <td class="val"><strong>20% of CIA (15.0 Marks Max - Average of 2 Tests)</strong></td>
    </tr>
    <tr>
      <td class="lbl">Evaluation Policy</td>
      <td class="val" colspan="3">
        Series examinations conducted covering course outcomes. Final series mark is the average of Test 1 and Test 2 scaled to Max 15.0 Marks.
      </td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 35px;">#</th>
        <th style="width: 65px;">Roll No</th>
        <th style="width: 90px;">Reg No</th>
        <th class="td-left">Student Name</th>
        <th style="width: 110px; background: #1e40af;">Test 1 (Max 15M)</th>
        <th style="width: 110px; background: #5b21b6;">Test 2 (Max 15M)</th>
        <th style="width: 120px; background: #0f2e59;">Series Average<br><small>(Max 15.0 Marks)</small></th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $idx => $st)
        @php
          $stTests = $seriesTests->get($st->reg_no, collect());
          $t1 = $stTests->where('test_no', 'Test 1')->first();
          $t2 = $stTests->where('test_no', 'Test 2')->first();

          $t1Score15 = ($t1 && !$t1->is_absent && $t1->total_score_100 !== null) ? round((floatval($t1->total_score_100) / 100.0) * 15.0, 2) : null;
          $t2Score15 = ($t2 && !$t2->is_absent && $t2->total_score_100 !== null) ? round((floatval($t2->total_score_100) / 100.0) * 15.0, 2) : null;

          if ($t1Score15 !== null && $t2Score15 !== null) {
              $avgSeries15 = round(($t1Score15 + $t2Score15) / 2.0, 2);
          } elseif ($t1Score15 !== null) {
              $avgSeries15 = $t1Score15;
          } elseif ($t2Score15 !== null) {
              $avgSeries15 = $t2Score15;
          } else {
              $avgSeries15 = 0.00;
          }
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td><span style="font-family: monospace; font-weight: 700;">{{ $st->reg_no }}</span></td>
          <td class="td-left">{{ $st->name }}</td>
          <td style="font-weight: 700; color: #1e40af;">
            @if($t1 && $t1->is_absent)
              <span style="color: #dc2626;">ABS</span>
            @elseif($t1Score15 !== null)
              {{ $t1Score15 }}
            @else
              -
            @endif
          </td>
          <td style="font-weight: 700; color: #5b21b6;">
            @if($t2 && $t2->is_absent)
              <span style="color: #dc2626;">ABS</span>
            @elseif($t2Score15 !== null)
              {{ $t2Score15 }}
            @else
              -
            @endif
          </td>
          <td style="font-weight: 900; font-size: 11.5px; color: #0f172a; background: #f8fafc;">
            {{ $avgSeries15 }}
          </td>
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
