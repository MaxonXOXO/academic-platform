<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated Student Continuous Evaluation (CE) Report — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
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

  table.data-table { width: 100%; border-collapse: collapse; font-size: 10.5px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 5px 6px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 8px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 30px; padding-top: 12px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 10.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 landscape; margin: 15mm 12mm 15mm 12mm; }
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
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Consolidated CE Report</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ getFullBranchName($classroom->department ?? $classroom->branch ?? '') }}</h2>
    <p>Virtual Drawing Hall & CAD Laboratory — Continuous Evaluation (CE) Ledger</p>
    <div class="report-title">Consolidated Student Continuous Evaluation (CE) Mark Sheet</div>
  </div>

  <table class="meta-table">
    <tr>
      <td class="lbl">Course Code & Name:</td>
      <td class="val">{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} — {{ $batchSubject->subject_name }}</td>
      <td class="lbl">Classroom / Batch:</td>
      <td class="val">{{ $classroom->classroom_name ?? $batchSubject->classroom_id }} (Sem {{ $batchSubject->semester }})</td>
    </tr>
    <tr>
      <td class="lbl">Scheme / Regulation:</td>
      <td class="val">Revision 2026 (Outcome Based Education)</td>
      <td class="lbl">CIE Scale:</td>
      <td class="val">Average Rubric Score (50) Scaled to 30 CIE Marks</td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 45px;">Roll</th>
        <th style="width: 90px;">Register No</th>
        <th style="text-align: left; padding-left: 8px;">Student Name</th>
        @foreach($exercises as $ex)
        <th style="width: 60px;" title="{{ $ex['title'] }}">{{ $ex['exercise_no'] }}</th>
        @endforeach
        <th style="width: 75px; background: #0284c7; color: #fff;">CE Avg (50)</th>
        <th style="width: 80px; background: #16a34a; color: #fff;">CIE Marks (30)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($studentCeData as $row)
      <tr>
        <td style="font-weight: 700; color: #1e3a5f;">{{ $row['roll_no'] }}</td>
        <td><small style="font-family: monospace;">{{ $row['reg_no'] }}</small></td>
        <td class="td-left">{{ $row['name'] }}</td>
        @foreach($exercises as $ex)
        @php $sc = $row['ex_scores'][$ex['exercise_no']] ?? null; @endphp
        <td style="font-weight: 600;">
          @if($sc !== null)
            {{ number_format($sc, 1) }}
          @else
            <span style="color: #94a3b8;">-</span>
          @endif
        </td>
        @endforeach
        <td style="font-weight: 800; color: #0284c7; font-size: 11px;">{{ number_format($row['avg_50'], 2) }}</td>
        <td style="font-weight: 800; color: #15803d; font-size: 11.5px;">{{ number_format($row['cie_30'], 2) }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="{{ 5 + count($exercises) }}" style="text-align: center; padding: 20px; color: #64748b;">No student evaluation records found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="sig-row">
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Faculty In-Charge</div>
      <div style="font-size: 10px; color: #64748b;">{{ $assignedStaff->first()->name ?? 'Subject Teacher' }}</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Internal Verifier</div>
      <div style="font-size: 10px; color: #64748b;">Academic Quality Cell</div>
    </div>
    <div class="sig-box">
      <div class="sig-line"></div>
      <div class="sig-lbl">Head of Department</div>
      <div style="font-size: 10px; color: #64748b;">{{ $hod->name ?? 'HOD' }}</div>
    </div>
  </div>
</div>

<script>
  window.onload = function() {
    setTimeout(function() {
      window.print();
    }, 500);
  };
</script>
</body>
</html>
