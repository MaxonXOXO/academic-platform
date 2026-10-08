<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consolidated CIA & ESE Evaluation Register (R-2021) — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: #f1f5f9; color: #1e293b; padding: 24px 16px; }
  .report-container { max-width: 1050px; margin: 0 auto; background: #ffffff; padding: 24px 28px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 1050px; margin-left: auto; margin-right: auto; }
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
    @page { size: A4 portrait; margin: 12mm 10mm 12mm 10mm; }
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
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Consolidated Register</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $courseFile->program ?? 'Technical Education' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Continuous Internal Assessment (CIA 75M) & End Semester Exam (ESE 50M) Register</div>
  </div>

  <table class="meta-table">
    <tr>
      <td class="lbl">Course Title</td>
      <td class="val"><strong>{{ $batchSubject->subject_name }}</strong></td>
      <td class="lbl">Course Code</td>
      <td class="val"><strong>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</strong></td>
    </tr>
    <tr>
      <td class="lbl">Semester & Class</td>
      <td class="val">Semester {{ $classroom->current_semester ?? $courseFile->semester ?? '3' }} ({{ $classroom->classroom_id ?? $batchSubject->classroom_id }})</td>
      <td class="lbl">Scheme & Type</td>
      <td class="val">Revision 2021 (Practical / Drawing Lab)</td>
    </tr>
    <tr>
      <td class="lbl">CIA Maximum</td>
      <td class="val"><strong style="color: #1e3a5f;">{{ $ciaMax }} Marks</strong></td>
      <td class="lbl">ESE Maximum</td>
      <td class="val"><strong style="color: #1e3a5f;">{{ $eseMax }} Marks (Total: 125 Marks)</strong></td>
    </tr>
    <tr>
      <td class="lbl">Assessment Split-up</td>
      <td class="val" colspan="3">
        Continuous Drawing Work: <strong>{{ $continuousMax }}M</strong> &bull; 
        Open-Ended: <strong>{{ $openEndedMax }}M</strong> &bull; 
        Series Tests Avg: <strong>{{ $summativeMax }}M</strong> &bull; 
        Attendance: <strong>{{ $attMax }}M</strong> (20% of 75 CIA)
      </td>
    </tr>
  </table>

  <div class="rule-note">
    <strong>Assessment Policy (Revision 2021):</strong> Continuous Internal Assessment (CIA) total is 75 Marks. Minimum eligibility mark for pass in CIA is 40% (30 Marks). Minimum pass mark in ESE is 40% (20 Marks out of 50). Minimum overall aggregate for course pass is 40% (50 Marks out of 125).
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 30px;">#</th>
        <th style="width: 55px;">Roll No</th>
        <th style="width: 85px;">Reg No</th>
        <th class="td-left">Student Name</th>
        <th style="width: 80px;">Continuous<br><small>(Max {{ $continuousMax }})</small></th>
        <th style="width: 75px;">Open-Ended<br><small>(Max {{ $openEndedMax }})</small></th>
        <th style="width: 75px;">Series Avg<br><small>(Max {{ $summativeMax }})</small></th>
        <th style="width: 75px;">Attendance<br><small>(Max {{ $attMax }})</small></th>
        <th style="width: 85px; background: #0f2e59;">Total CIA<br><small>(Max {{ $ciaMax }})</small></th>
        <th style="width: 75px;">ESE Mark<br><small>(Max {{ $eseMax }})</small></th>
        <th style="width: 80px;">Grand Total<br><small>(Max 125)</small></th>
        <th style="width: 75px;">Result</th>
      </tr>
    </thead>
    <tbody>
      @foreach($studentResults as $idx => $r)
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $r['student']->roll_no ?? '-' }}</td>
          <td><span style="font-family: monospace; font-weight: 700;">{{ $r['student']->reg_no }}</span></td>
          <td class="td-left">{{ $r['student']->name }}</td>
          <td style="color: #0284c7; font-weight: 700;">
            {{ $r['continuous_mark'] }}
            @if(!empty($r['is_overridden']))
              <small style="font-size: 8px; color: #f59e0b;" title="Overridden by Faculty">*</small>
            @endif
          </td>
          <td style="color: #d97706; font-weight: 700;">{{ $r['open_ended_mark'] }}</td>
          <td style="color: #7c3aed; font-weight: 700;">{{ $r['series_mark'] }}</td>
          <td style="color: #059669; font-weight: 700;">{{ $r['attendance_mark'] }}</td>
          <td style="font-weight: 900; font-size: 11.5px; background: #f0fdf4; color: #166534;">
            {{ round($r['total_cia']) }}
          </td>
          <td style="font-weight: 700; color: #475569;">
            {{ $r['ese_mark'] !== null ? $r['ese_mark'] : '-' }}
          </td>
          <td style="font-weight: 900; font-size: 11.5px; color: #0f172a;">
            {{ round($r['grand_total']) }}
          </td>
          <td>
            <span style="font-weight: 700; color: {{ $r['is_pass'] ? '#059669' : '#dc2626' }};">
              {{ $r['is_pass'] ? 'PASSED' : 'NEEDS IMP' }}
            </span>
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
