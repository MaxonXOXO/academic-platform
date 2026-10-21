<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Workshop CIA Summary & Performance Analysis — {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; background: #f1f5f9; color: #1e293b; padding: 24px 20px; }
  .report-container { max-width: 900px; margin: 0 auto; background: #ffffff; padding: 28px 32px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06); }
  
  .no-print { text-align: center; padding: 12px; background: #1e3a5f; border-radius: 8px; margin-bottom: 20px; max-width: 900px; margin-left: auto; margin-right: auto; }
  .no-print button { background: #2563eb; color: #fff; border: none; padding: 8px 24px; font-size: 13px; font-weight: 700; border-radius: 6px; cursor: pointer; margin: 0 6px; }
  .no-print button.back-btn { background: #475569; }

  .institution-header { text-align: center; border-bottom: 2.5px solid #1e3a5f; padding-bottom: 8px; margin-bottom: 14px; }
  .institution-header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; text-transform: uppercase; letter-spacing: 0.5px; }
  .institution-header h2 { font-size: 12px; font-weight: 700; color: #334155; margin-top: 2px; }
  .institution-header p { font-size: 10.5px; color: #475569; margin-top: 1px; }
  .report-title { display: inline-block; background: #1e3a5f; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 14px; border-radius: 4px; margin-top: 4px; text-transform: uppercase; }

  .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; font-size: 10.5px; border: 1.5px solid #1e3a5f; }
  .meta-table td { padding: 5px 8px; border: 1px solid #cbd5e1; }
  .meta-table td.lbl { font-weight: 700; color: #1e3a5f; background: #f1f5f9; width: 22%; }
  .meta-table td.val { font-weight: 600; color: #0f172a; width: 28%; }

  .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
  .stat-card { background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 6px; padding: 10px; text-align: center; }
  .stat-card.highlight { background: #eff6ff; border-color: #3b82f6; }
  .stat-card.success { background: #f0fdf4; border-color: #22c55e; }
  .stat-card.warning { background: #fefce8; border-color: #eab308; }
  .stat-card.danger { background: #fef2f2; border-color: #ef4444; }
  .stat-val { font-size: 20px; font-weight: 800; margin-top: 4px; color: #0f172a; }
  .stat-lbl { font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }

  table.data-table { width: 100%; border-collapse: collapse; font-size: 10.5px; border: 1px solid #64748b; margin-bottom: 20px; }
  table.data-table th { background: #1e3a5f; color: #fff; padding: 7px 6px; text-align: center; font-weight: 700; border: 1px solid #334155; }
  table.data-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; }
  table.data-table td.td-left { text-align: left; padding-left: 10px; font-weight: 600; }
  table.data-table tr:nth-child(even) { background: #f8fafc; }

  .sig-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 36px; padding-top: 14px; border-top: 1px solid #94a3b8; page-break-inside: avoid; }
  .sig-box { text-align: center; font-size: 10px; }
  .sig-line { border-bottom: 1px solid #475569; height: 35px; margin-bottom: 4px; }
  .sig-lbl { font-weight: 700; color: #334155; }

  @media print {
    @page { size: A4 portrait; margin: 12mm; }
    body { background: #fff; padding: 0; color: #000; }
    .report-container { border: none; box-shadow: none; padding: 0; width: 100%; max-width: none; }
    .no-print { display: none !important; }
    .institution-header h1, .report-title, table.data-table th, .meta-table td.lbl { color: #000 !important; background: none !important; }
    .report-title { border: 1.5px solid #000; padding: 2px 8px; }
    table.data-table th { background: #e2e8f0 !important; color: #000 !important; border: 1px solid #000 !important; }
    table.data-table td, .meta-table td { border: 1px solid #000 !important; color: #000 !important; }
    .stat-card { border: 1px solid #000 !important; background: none !important; }
  }
</style>
</head>
<body>

<div class="no-print">
  <button onclick="window.print()"><i class="fa-solid fa-print"></i> Print Summary Report</button>
  <button class="back-btn" onclick="window.close()"><i class="fa-solid fa-xmark"></i> Close</button>
</div>

<div class="report-container">
  <div class="institution-header">
    <h1>Carmel Polytechnic College, Alappuzha</h1>
    <h2>Department of {{ $classroom->department ?? 'Mechanical Engineering' }}</h2>
    <p>State Board of Technical Education, Kerala &bull; Revision 2021 (R-2021)</p>
    <div class="report-title">Mechanical Workshop &bull; CIA Summary &amp; Academic Performance Analysis</div>
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
      <td class="lbl">Scheme &amp; Credit</td>
      <td class="val">Revision 2021 &bull; Credits: 1.5 &bull; Scheme 0:0:3:0</td>
    </tr>
    <tr>
      <td class="lbl">Assessment Split</td>
      <td class="val" colspan="3">
        Work Evaluation: <strong>37.5M</strong> &bull; Record (Rough + Fair): <strong>7.5M</strong> &bull; Trade Tests Avg: <strong>15.0M</strong> &bull; Attendance: <strong>15.0M</strong> (Total: <strong>75M</strong>)
      </td>
    </tr>
  </table>

  <!-- Metric Statistics Cards -->
  <div class="stats-grid">
    <div class="stat-card highlight">
      <div class="stat-lbl">Total Enrolled</div>
      <div class="stat-val text-primary">{{ $totalStudents }}</div>
    </div>
    <div class="stat-card success">
      <div class="stat-lbl">Eligible / Passed (>=30)</div>
      <div class="stat-val text-success">{{ $passedCount }} <small style="font-size: 11px; font-weight: normal;">({{ $passRate }}%)</small></div>
    </div>
    <div class="stat-card danger">
      <div class="stat-lbl">Below Minimum (&lt;30)</div>
      <div class="stat-val text-danger">{{ $failedCount }}</div>
    </div>
    <div class="stat-card warning">
      <div class="stat-lbl">Class Average CIA</div>
      <div class="stat-val">{{ number_format($avgScore, 1) }} <small style="font-size: 11px; font-weight: normal;">/ 75M</small></div>
    </div>
  </div>

  <!-- Dual Batch Division Overview -->
  <h4 style="font-size: 12px; font-weight: 700; color: #1e3a5f; margin-bottom: 8px;">1. Batch Division &amp; Section Allocation Overview</h4>
  <table class="data-table">
    <thead>
      <tr>
        <th>Batch Name</th>
        <th>Allocated Trade Sections</th>
        <th>Students Enrolled</th>
        <th>Eligibility Criteria</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-weight: 700; color: #0284c7;">Batch 1 (B1)</td>
        <td class="td-left">{{ implode(', ', $batchAllocation['1'] ?? ['Fitting', 'Smithy', 'Welding']) }}</td>
        <td style="font-weight: 700;">{{ $b1Count }}</td>
        <td>Min 30 Marks out of 75 CIA</td>
      </tr>
      <tr>
        <td style="font-weight: 700; color: #9333ea;">Batch 2 (B2)</td>
        <td class="td-left">{{ implode(', ', $batchAllocation['2'] ?? ['Machine shop', 'Sheet metal', 'Foundry']) }}</td>
        <td style="font-weight: 700;">{{ $b2Count }}</td>
        <td>Min 30 Marks out of 75 CIA</td>
      </tr>
    </tbody>
  </table>

  <!-- CIA Grade Bands Distribution Table -->
  <h4 style="font-size: 12px; font-weight: 700; color: #1e3a5f; margin-bottom: 8px;">2. CIA Grade Bands &amp; Distribution Analysis</h4>
  <table class="data-table">
    <thead>
      <tr>
        <th class="td-left">Performance Bracket (Marks Range)</th>
        <th style="width: 140px;">Percentage Bracket</th>
        <th style="width: 130px;">Number of Students</th>
        <th style="width: 120px;">Percentage (%)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($gradeBands as $band => $cnt)
        @php
          $pct = $totalStudents > 0 ? round(($cnt / $totalStudents) * 100, 1) : 0;
          $isF = str_contains($band, '< 30M');
        @endphp
        <tr style="{{ $isF ? 'background: #fef2f2; color: #991b1b; font-weight: 600;' : '' }}">
          <td class="td-left">{{ $band }}</td>
          <td>
            @if(str_contains($band, '>= 68M')) &ge; 90%
            @elseif(str_contains($band, '60 - 67M')) 80% - 89%
            @elseif(str_contains($band, '53 - 59M')) 70% - 79%
            @elseif(str_contains($band, '45 - 52M')) 60% - 69%
            @elseif(str_contains($band, '38 - 44M')) 50% - 59%
            @elseif(str_contains($band, '30 - 37M')) 40% - 49%
            @else &lt; 40%
            @endif
          </td>
          <td style="font-weight: 700;">{{ $cnt }}</td>
          <td>{{ $pct }}%</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Highest and Lowest Stats -->
  <div style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 10.5px;">
    <strong>Highest Score:</strong> <span style="font-weight: 700; color: #15803d;">{{ $maxScore }} / 75 Marks</span> &bull; 
    <strong>Lowest Score:</strong> <span style="font-weight: 700; color: #b91c1c;">{{ $minScore }} / 75 Marks</span> &bull; 
    <strong>Continuous Assessment Minimum Requirement:</strong> 30 Marks (40% of 75M).
  </div>

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
