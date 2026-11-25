<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Carmel Polytechnic College — Open Elective Allocation Analysis List (A4 Portrait)</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 10mm 8mm;
    }
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10px;
      color: #111;
      margin: 0;
      padding: 0;
      line-height: 1.3;
    }
    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 6px;
      margin-bottom: 10px;
    }
    .college-name {
      font-size: 15px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }
    .college-sub {
      font-size: 9.5px;
      color: #333;
      margin-top: 2px;
    }
    .doc-title {
      font-size: 12px;
      font-weight: bold;
      margin-top: 5px;
      text-decoration: underline;
      text-transform: uppercase;
    }
    .doc-sub {
      font-size: 9.5px;
      color: #475569;
      margin-top: 2px;
    }
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
      font-size: 10px;
      table-layout: fixed;
    }
    .meta-table td {
      padding: 3px 5px;
      border: 1px solid #cbd5e1;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }
    .meta-table .label {
      font-weight: bold;
      background: #f8fafc;
      width: 19%;
    }
    .meta-table .val {
      width: 31%;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
      font-size: 9.5px;
      table-layout: fixed;
    }
    .summary-table th, .summary-table td {
      border: 1px solid #333;
      padding: 3.5px 4px;
      text-align: center;
      word-wrap: break-word;
    }
    .summary-table th {
      background: #f1f5f9;
      font-weight: bold;
      font-size: 8.5px;
      text-transform: uppercase;
    }
    .roster-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9px;
      table-layout: fixed;
    }
    .roster-table thead {
      display: table-header-group;
    }
    .roster-table tr {
      page-break-inside: avoid;
      break-inside: avoid;
    }
    .roster-table th, .roster-table td {
      border: 1px solid #333;
      padding: 3.5px 4px;
      text-align: left;
      vertical-align: top;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }
    .roster-table th {
      background: #f1f5f9;
      font-weight: bold;
      font-size: 8.5px;
      text-transform: uppercase;
    }
    .center { text-align: center !important; }
    .right { text-align: right !important; }
    .bold { font-weight: bold; }
    .font-mono {
      font-family: "Courier New", Courier, monospace;
      font-weight: bold;
    }
    .branch-badge {
      display: inline-block;
      padding: 1px 4px;
      border: 1px solid #94a3b8;
      border-radius: 2px;
      font-size: 8.5px;
      font-weight: bold;
      background: #f8fafc;
    }
    .opt-code {
      font-family: "Courier New", Courier, monospace;
      font-weight: bold;
      font-size: 8.5px;
    }
    .opt-title {
      display: block;
      font-size: 8.5px;
      color: #334155;
      line-height: 1.2;
    }
    .src-badge {
      display: inline-block;
      padding: 1px 3px;
      border-radius: 2px;
      font-size: 8px;
      font-weight: bold;
      border: 1px solid #cbd5e1;
      background: #f8fafc;
    }
    .src-opt1 { background: #ecfdf5; border-color: #6ee7b7; color: #065f46; }
    .src-opt2 { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }
    .src-other { background: #fefce8; border-color: #fde047; color: #854d0e; }
    .src-none { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }
    .section-title {
      font-weight: bold;
      font-size: 9.5px;
      text-transform: uppercase;
      margin: 6px 0 4px 0;
      color: #0f172a;
    }
    .signatures {
      margin-top: 28px;
      display: flex;
      justify-content: space-between;
      width: 100%;
      page-break-inside: avoid;
      break-inside: avoid;
    }
    .sig-col {
      width: 29%;
      text-align: center;
      border-top: 1px solid #000;
      padding-top: 4px;
      font-size: 9.5px;
      font-weight: bold;
    }
    .sig-sub {
      font-weight: normal;
      font-size: 8.5px;
      color: #475569;
    }
    .page-break {
      page-break-after: always;
      break-after: page;
    }
    @media print {
      .no-print { display: none !important; }
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>

  <!-- Screen Action Bar -->
  <div class="no-print" style="background:#0f172a;color:#fff;padding:10px 18px;display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
    <div>
      <span style="font-weight:bold;font-size:13px;color:#38bdf8;">Pre-Approval FCFS Allocation Analysis List</span>
      <span style="color:#94a3b8;font-size:11px;margin-left:8px;">&bull; A4 Portrait &bull; Revision 2021 Semester 6 Open Elective</span>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="/admin/open-elective" style="background:#334155;color:#fff;text-decoration:none;padding:6px 14px;border-radius:6px;font-size:11px;font-weight:bold;">&larr; Back to Allocation Desk</a>
      <button onclick="window.print()" style="background:#0284c7;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;font-weight:bold;font-size:11px;">Print Report (A4 Portrait)</button>
    </div>
  </div>

  @if(!$courseId && count($coursePools) > 1)
    <!-- Overall Allocation Summary Page (When Printing All Courses) -->
    <div class="page-break">
      <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; Approved by AICTE &amp; Affiliated to SBTE Kerala</div>
        <div class="doc-title">Open Elective FCFS Allocation &mdash; Consolidated Analysis Summary</div>
        <div class="doc-sub">
          Pre-Approval Review &bull; Semester 6 ({{ $settings->academic_year ?? '2026-2027' }}) &bull; Generated: {{ now()->format('d M Y, h:i A') }}
        </div>
      </div>

      <table class="meta-table">
        <tr>
          <td class="label">Total Registered:</td>
          <td class="val"><strong>{{ $totalRegistered }}</strong> Students</td>
          <td class="label">Total Allotted:</td>
          <td class="val"><strong>{{ $totalAllotted }}</strong> Students</td>
        </tr>
        <tr>
          <td class="label">Unallotted / Pending:</td>
          <td class="val"><strong>{{ $unallottedStudents->count() }}</strong> Students</td>
          <td class="label">Approval Status:</td>
          <td class="val">
            <strong>{{ $settings->is_released_to_hods ? 'Approved & Released to HODs' : 'Pending Principal Approval (Analysis Stage)' }}</strong>
          </td>
        </tr>
      </table>

      <div class="section-title">Course-Wise Seat Allocation &amp; Option Breakdown</div>
      <table class="summary-table">
        <thead>
          <tr>
            <th style="width: 7%;">Dept</th>
            <th style="width: 9%;">Code</th>
            <th style="width: 34%; text-align: left;">Course Title</th>
            <th style="width: 9%;">Seats</th>
            <th style="width: 10%;">Allotted</th>
            <th style="width: 10%;">1st Opt</th>
            <th style="width: 11%;">Rollover</th>
            <th style="width: 10%;">Vacant</th>
          </tr>
        </thead>
        <tbody>
          @php
            $sumSeats = 0; $sumAllotted = 0; $sumOpt1 = 0; $sumRollover = 0;
          @endphp
          @foreach($coursePools as $pool)
            @php
              $c = $pool['course'];
              $cnt = $pool['total_count'];
              $opt1Cnt = $pool['sourceSummary']['option_1_fcfs'] ?? 0;
              $rollCnt = $cnt - $opt1Cnt;
              $vacant = max(0, (int)$c->max_capacity - $cnt);
              $sumSeats += (int)$c->max_capacity;
              $sumAllotted += $cnt;
              $sumOpt1 += $opt1Cnt;
              $sumRollover += $rollCnt;
            @endphp
            <tr>
              <td class="bold">{{ $c->offering_branch }}</td>
              <td class="font-mono">{{ $c->course_code }}</td>
              <td style="text-align: left;"><strong>{{ $c->course_title }}</strong></td>
              <td>{{ $c->max_capacity }}</td>
              <td class="bold">{{ $cnt }}</td>
              <td>{{ $opt1Cnt }}</td>
              <td>{{ $rollCnt }}</td>
              <td>{{ $vacant }}</td>
            </tr>
          @endforeach
          <tr style="background:#f1f5f9; font-weight:bold;">
            <td colspan="3" style="text-align: right;">TOTAL</td>
            <td>{{ $sumSeats }}</td>
            <td>{{ $sumAllotted }}</td>
            <td>{{ $sumOpt1 }}</td>
            <td>{{ $sumRollover }}</td>
            <td>{{ max(0, $sumSeats - $sumAllotted) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="signatures">
        <div class="sig-col">
          Open Elective Coordinator<br>
          <span class="sig-sub">FCFS Allocation Analysis</span>
        </div>
        <div class="sig-col">
          Academic Coordinator / Dean<br>
          <span class="sig-sub">Verification</span>
        </div>
        <div class="sig-col">
          Principal<br>
          <span class="sig-sub">Approval for HOD Handover</span>
        </div>
      </div>
    </div>
  @endif

  @foreach($coursePools as $pool)
    @php
      $course = $pool['course'];
      $students = $pool['students'];
      $summary = $pool['branchSummary'];
      $total = $pool['total_count'];
      $branchFullName = \App\Models\OeCourse::getBranchFullName($course->offering_branch);
      $hasMoreSections = !$loop->last || $unallottedStudents->isNotEmpty();
    @endphp

    <div class="{{ $hasMoreSections ? 'page-break' : '' }}">
      <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; Approved by AICTE &amp; Affiliated to SBTE Kerala</div>
        <div class="doc-title">Open Elective Allocation Analysis List ({{ $settings->is_released_to_hods ? 'Approved' : 'Pre-Approval' }})</div>
        <div class="doc-sub">
          Revision 2021 &bull; Semester 6 ({{ $settings->academic_year ?? '2026-2027' }}) &bull; Generated: {{ now()->format('d M Y, h:i A') }}
        </div>
      </div>

      <table class="meta-table">
        <tr>
          <td class="label">Offering Dept:</td>
          <td class="val"><strong>{{ $branchFullName }}</strong> ({{ $course->offering_branch }})</td>
          <td class="label">Course Code:</td>
          <td class="val font-mono">{{ $course->course_code }}</td>
        </tr>
        <tr>
          <td class="label">Course Title:</td>
          <td class="val"><strong>{{ $course->course_title }}</strong></td>
          <td class="label">Allotted / Seats:</td>
          <td class="val"><strong>{{ $total }}</strong> / {{ $course->max_capacity }} Seats</td>
        </tr>
      </table>

      <!-- Branch-Wise Allotted Summary -->
      <table class="summary-table">
        <thead>
          <tr>
            <th>AU</th>
            <th>CE</th>
            <th>CT</th>
            <th>EEE</th>
            <th>EL</th>
            <th>ME</th>
            <th style="background:#e0f2fe;">Total Allotted</th>
            <th style="background:#ecfdf5;">1st Option</th>
            <th style="background:#eff6ff;">2nd+ / Override</th>
          </tr>
        </thead>
        <tbody>
          @php
            $opt1Count = $pool['sourceSummary']['option_1_fcfs'] ?? 0;
            $otherCount = $total - $opt1Count;
          @endphp
          <tr>
            <td>{{ $summary['AU'] ?? 0 }}</td>
            <td>{{ $summary['CE'] ?? 0 }}</td>
            <td>{{ $summary['CT'] ?? 0 }}</td>
            <td>{{ $summary['EEE'] ?? 0 }}</td>
            <td>{{ $summary['EL'] ?? 0 }}</td>
            <td>{{ $summary['ME'] ?? 0 }}</td>
            <td class="bold" style="background:#f0f9ff;">{{ $total }}</td>
            <td class="bold" style="background:#f0fdf4;">{{ $opt1Count }}</td>
            <td class="bold" style="background:#eff6ff;">{{ $otherCount }}</td>
          </tr>
        </tbody>
      </table>

      <div class="section-title">Allotted Student List with 1st &amp; 2nd Options (Sorted by Branch &amp; SBTE Number)</div>
      <table class="roster-table">
        <thead>
          <tr>
            <th class="center" style="width: 5%;">Sl</th>
            <th class="center" style="width: 7%;">Branch</th>
            <th style="width: 15%;">SBTE Number</th>
            <th style="width: 23%;">Student Name</th>
            <th style="width: 20%;">First Option</th>
            <th style="width: 20%;">Second Option</th>
            <th class="center" style="width: 10%;">Allot Type</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $idx => $st)
            @php
              $src = $st->allocation_source ?? '';
              $srcLabel = 'Allotted';
              $srcClass = 'src-other';
              if ($src === 'option_1_fcfs') {
                $srcLabel = '1st Opt';
                $srcClass = 'src-opt1';
              } elseif ($src === 'option_2_rollover') {
                $srcLabel = '2nd Opt';
                $srcClass = 'src-opt2';
              } elseif ($src === 'option_3_rollover') {
                $srcLabel = '3rd Opt';
              } elseif ($src === 'option_4_rollover') {
                $srcLabel = '4th Opt';
              } elseif ($src === 'option_5_rollover') {
                $srcLabel = '5th Opt';
              } elseif ($src === 'admin_override') {
                $srcLabel = 'Override';
              }
            @endphp
            <tr>
              <td class="center font-mono">{{ $idx + 1 }}</td>
              <td class="center"><span class="branch-badge">{{ $st->parent_branch }}</span></td>
              <td class="font-mono">{{ $st->sbte_reg_no }}</td>
              <td class="bold">{{ strtoupper($st->student_name) }}</td>
              <td>
                @if($st->option1Course)
                  <span class="opt-code">{{ $st->option1Course->offering_branch }} &bull; {{ $st->option1Course->course_code }}</span>
                  <span class="opt-title">{{ $st->option1Course->course_title }}</span>
                @else
                  -
                @endif
              </td>
              <td>
                @if($st->option2Course)
                  <span class="opt-code">{{ $st->option2Course->offering_branch }} &bull; {{ $st->option2Course->course_code }}</span>
                  <span class="opt-title">{{ $st->option2Course->course_title }}</span>
                @else
                  -
                @endif
              </td>
              <td class="center">
                <span class="src-badge {{ $srcClass }}">{{ $srcLabel }}</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="center" style="padding: 16px; color: #64748b; font-style: italic;">
                No students allotted to this course yet. Run FCFS Auto-Allocate first.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="signatures">
        <div class="sig-col">
          Open Elective Coordinator<br>
          <span class="sig-sub">Allocation Verification</span>
        </div>
        <div class="sig-col">
          HOD ({{ $course->offering_branch }})<br>
          <span class="sig-sub">Course Review</span>
        </div>
        <div class="sig-col">
          Principal<br>
          <span class="sig-sub">Approval Signature</span>
        </div>
      </div>
    </div>
  @endforeach

  @if($unallottedStudents->isNotEmpty())
    <div>
      <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; Approved by AICTE &amp; Affiliated to SBTE Kerala</div>
        <div class="doc-title">Unallotted / Pending Students List (Requires Manual Review)</div>
        <div class="doc-sub">
          Total Unallotted: {{ $unallottedStudents->count() }} Students &bull; Generated: {{ now()->format('d M Y, h:i A') }}
        </div>
      </div>

      <table class="roster-table">
        <thead>
          <tr>
            <th class="center" style="width: 5%;">Sl</th>
            <th class="center" style="width: 7%;">Branch</th>
            <th style="width: 15%;">SBTE Number</th>
            <th style="width: 23%;">Student Name</th>
            <th style="width: 20%;">First Option</th>
            <th style="width: 20%;">Second Option</th>
            <th class="center" style="width: 10%;">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($unallottedStudents as $idx => $st)
            <tr>
              <td class="center font-mono">{{ $idx + 1 }}</td>
              <td class="center"><span class="branch-badge">{{ $st->parent_branch }}</span></td>
              <td class="font-mono">{{ $st->sbte_reg_no }}</td>
              <td class="bold">{{ strtoupper($st->student_name) }}</td>
              <td>
                @if($st->option1Course)
                  <span class="opt-code">{{ $st->option1Course->offering_branch }} &bull; {{ $st->option1Course->course_code }}</span>
                  <span class="opt-title">{{ $st->option1Course->course_title }}</span>
                @else
                  -
                @endif
              </td>
              <td>
                @if($st->option2Course)
                  <span class="opt-code">{{ $st->option2Course->offering_branch }} &bull; {{ $st->option2Course->course_code }}</span>
                  <span class="opt-title">{{ $st->option2Course->course_title }}</span>
                @else
                  -
                @endif
              </td>
              <td class="center"><span class="src-badge src-none">Unallotted</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

</body>
</html>
