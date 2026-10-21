<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Workshop Attendance Register & Class Log — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10px; background: #f1f5f9; color: #1e293b; padding: 20px 14px; }
  .report-container { max-width: 1050px; margin: 0 auto; background: #ffffff; padding: 22px 26px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 10px; background: #1e3a5f; border-radius: 8px; margin-bottom: 16px; max-width: 1050px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 7px 20px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 6px; margin-bottom: 10px; }
  .institution-header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 12px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 10px; color: #475569; margin-top: 1px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 4px; margin-top: 4px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 4px 6px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 18%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 32%; }

  .sub-heading { font-size: 12px; font-weight: 700; color: #1e3a5f; margin-top: 16px; margin-bottom: 8px; border-bottom: 1.5px solid #cbd5e1; padding-bottom: 4px; }

  table.data-table { width: 100%; border-collapse: collapse; font-size: 9.5px; border: 1px solid #64748b; margin-bottom: 16px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 6px 4px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 4px 5px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 6px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 24px; padding-top: 10px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 9.5px; }
  .sig-line { border-bottom: 1px solid #475569; height: 30px; margin-bottom: 3px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 portrait; margin: 10mm; }
    body { background: #fff; padding: 0; color: #000; }
    .report-container { border: none; box-shadow: none; padding: 0; width: 100%; max-width: none; }
    .no-print { display: none !important; }
    .institution-header h1, .report-title, table.data-table th, .meta-table td.lbl { color: #000 !important; background: none !important; }
    table.data-table th { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; }
    table.data-table td, .meta-table td { border: 1px solid #000 !important; color: #000 !important; }
  }
</style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Attendance &amp; Class Log</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Mechanical Workshop &bull; Attendance Register &amp; Session Log Report</div>
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
      <td class="lbl">Attendance Weightage</td>
      <td class="val"><strong>15.0 Marks</strong> (Converted via Kerala SBTE R21 Slab Formula)</td>
    </tr>
  </table>

  <!-- PART 1: CLASS PRACTICAL SESSIONS CONDUCTED LOG -->
  <div class="sub-heading">
    <i class="fa-solid fa-calendar-check me-1"></i> Part I: Workshop Practical Sessions &amp; Class Log
  </div>

  @if($logs->isNotEmpty())
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 35px;">Sl</th>
          <th style="width: 80px;">Date</th>
          <th style="width: 55px;">Period</th>
          <th style="width: 80px;">Batch / Group</th>
          <th class="td-left">Practical Topics / Exercises Covered</th>
          <th style="width: 60px;">Present</th>
          <th style="width: 60px;">Absent</th>
          <th style="width: 80px;">Faculty</th>
        </tr>
      </thead>
      <tbody>
        @foreach($logs as $lIdx => $lg)
          @php
            $pCount = count(json_decode($lg->present_students ?? '[]', true) ?: []);
            $aCount = count(json_decode($lg->absent_students ?? '[]', true) ?: []);
          @endphp
          <tr>
            <td>{{ $lIdx + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($lg->date)->format('d-m-Y') }}</td>
            <td>P{{ $lg->period }}</td>
            <td>{{ $lg->sub_batch ?? 'Whole' }}</td>
            <td class="td-left">{{ $lg->topics_covered ?? 'Mechanical Workshop Practical Job' }}</td>
            <td style="color: #15803d; font-weight: 700;">{{ $pCount }}</td>
            <td style="color: #b91c1c; font-weight: 700;">{{ $aCount }}</td>
            <td>{{ $lg->recorded_by ?? 'Staff' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="color: #64748b; font-style: italic; margin-bottom: 16px;">
      No session class logs recorded yet. Regular daily attendance roll-calls recorded via mobile or web will appear here.
    </p>
  @endif

  <!-- PART 2: STUDENT OFFICIAL ATTENDANCE & CONVERTED MARKS -->
  <div class="sub-heading">
    <i class="fa-solid fa-user-check me-1"></i> Part II: Student Official Attendance &amp; 15-Mark Evaluation Register
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 30px;">Sl</th>
        <th style="width: 35px;">Roll</th>
        <th style="width: 90px;">Register No</th>
        <th class="td-left" style="min-width: 180px;">Student Name</th>
        <th style="width: 50px;">Batch</th>
        <th style="width: 100px;">Official Attendance %</th>
        <th style="width: 100px;">Attendance Mark (/15M)</th>
        <th style="width: 90px;">Eligibility Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $idx => $st)
        @php
          $sc = $calculatedScores[$st->reg_no] ?? [];
          $attPct = (float)($sc['att_pct'] ?? 85.0);
          $attMark = (float)($sc['att_mark_15'] ?? 15.0);
          $isEligible = $attPct >= 75.0;
        @endphp
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td>{{ $st->roll_no ?? '-' }}</td>
          <td class="font-monospace">{{ $st->reg_no }}</td>
          <td class="td-left">{{ $st->name }}</td>
          <td>B{{ $sc['batch_no'] ?? '1' }}</td>
          <td><strong>{{ number_format($attPct, 1) }}%</strong></td>
          <td style="font-weight: 800; color: #1e3a5f;">{{ number_format($attMark, 2) }}</td>
          <td>
            @if($isEligible)
              <span style="color: #15803d; font-weight: 700;">Eligible (&ge;75%)</span>
            @elseif($attPct >= 65.0)
              <span style="color: #d97706; font-weight: 700;">Condonation (65-74%)</span>
            @else
              <span style="color: #b91c1c; font-weight: 700;">Shortage (&lt;65%)</span>
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
