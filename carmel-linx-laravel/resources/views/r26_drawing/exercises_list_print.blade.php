<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Drawing Exercises & CAD Tasks List — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; background: #f1f5f9; color: #1e293b; padding: 24px 16px; }
  .report-container { max-width: 950px; margin: 0 auto; background: #ffffff; padding: 28px 32px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 950px; margin-left: auto; margin-right: auto; }
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

  table.data-table { width: 100%; border-collapse: collapse; font-size: 11.5px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 8px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 7px 10px; font-size: 11px; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 40px; padding-top: 15px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 11px; }
  .sig-line { border-bottom: 1px solid #475569; height: 40px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 portrait; margin: 15mm 12mm 15mm 12mm; }
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
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Exercise List</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ getFullBranchName($classroom->department ?? $classroom->branch ?? '') }}</h2>
    <p>Virtual Drawing Hall & CAD Laboratory — Syllabus Exercise Schedule</p>
    <div class="report-title">List of Approved Drawing Sheets & CAD Tasks</div>
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
      <td class="lbl">Teaching Scheme:</td>
      <td class="val">L:T:P:R = 0:0:3:0 | Credits: 1.5</td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 10%;">Ex. No</th>
        <th style="width: 15%;">Module</th>
        <th style="text-align: left; padding-left: 12px;">Drawing Sheet / CAD Practical Title</th>
        <th style="width: 12%;">Mapped CO</th>
        <th style="width: 12%;">Duration (Hrs)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($exercises as $ex)
      <tr>
        <td style="text-align: center; font-weight: 700; color: #1e3a5f;">{{ $ex['exercise_no'] }}</td>
        <td style="text-align: center; font-weight: 600;">{{ $ex['module'] ?? 'Module I' }}</td>
        <td style="font-weight: 600; padding-left: 12px;">{{ $ex['title'] }}</td>
        <td style="text-align: center; font-weight: 700; color: #0284c7;">{{ $ex['co_id'] ?? 'CO1' }}</td>
        <td style="text-align: center;">{{ number_format($ex['hours'] ?? 3, 1) }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align: center; padding: 20px; color: #64748b;">No drawing exercises configured yet.</td>
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
