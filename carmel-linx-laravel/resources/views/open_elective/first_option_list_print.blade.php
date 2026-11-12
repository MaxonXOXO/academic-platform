<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Carmel Polytechnic College — Open Elective 1st Option Applicant Roster</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 12mm 10mm;
    }
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #111;
      margin: 0;
      padding: 0;
      line-height: 1.35;
    }
    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 12px;
    }
    .college-name {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .college-sub {
      font-size: 10px;
      color: #333;
    }
    .doc-title {
      font-size: 13px;
      font-weight: bold;
      margin-top: 5px;
      text-decoration: underline;
      text-transform: uppercase;
    }
    .meta-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      font-size: 11px;
    }
    .meta-table td {
      padding: 3.5px 6px;
      border: 1px solid #ccc;
    }
    .meta-table .bold {
      font-weight: bold;
      background: #f8fafc;
      width: 20%;
    }
    .branch-summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
      font-size: 10.5px;
    }
    .branch-summary-table th, .branch-summary-table td {
      border: 1px solid #333;
      padding: 4px 6px;
      text-align: center;
    }
    .branch-summary-table th {
      background: #f1f5f9;
      font-weight: bold;
      font-size: 10px;
      text-transform: uppercase;
    }
    .roster-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }
    .roster-table th, .roster-table td {
      border: 1px solid #333;
      padding: 4px 5px;
      text-align: left;
    }
    .roster-table th {
      background: #f1f5f9;
      font-weight: bold;
      font-size: 9.5px;
      text-transform: uppercase;
    }
    .center {
      text-align: center;
    }
    .font-mono {
      font-family: "Courier New", Courier, monospace;
      font-weight: bold;
    }
    .badge {
      display: inline-block;
      padding: 1px 4px;
      border-radius: 3px;
      font-size: 9px;
      font-weight: bold;
      background: #e2e8f0;
      color: #0f172a;
    }
    .opt-tag {
      display: inline-block;
      padding: 1px 4px;
      font-size: 8.5px;
      border: 1px solid #cbd5e1;
      border-radius: 2px;
      background: #f8fafc;
      margin-right: 2px;
    }
    .signatures {
      margin-top: 35px;
      display: flex;
      justify-content: space-between;
      page-break-inside: avoid;
    }
    .sig-col {
      width: 28%;
      text-align: center;
      border-top: 1px solid #000;
      padding-top: 4px;
      font-size: 10px;
      font-weight: bold;
    }
    .page-break {
      page-break-after: always;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <!-- Top Action Bar (Screen Only) -->
  <div class="no-print" style="background:#0f172a;color:#fff;padding:10px 18px;display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
    <div>
      <span style="font-weight:bold;font-size:13px;color:#38bdf8;">Pre-Allocation First Option Status Dossier</span>
      <span style="color:#94a3b8;font-size:11px;margin-left:8px;">&bull; Revision 2021 Semester 6 Open Elective</span>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="/admin/open-elective" style="background:#334155;color:#fff;text-decoration:none;padding:6px 14px;border-radius:6px;font-size:11px;font-weight:bold;">&larr; Back to Open Elective Desk</a>
      <button onclick="window.print()" style="background:#0284c7;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;font-weight:bold;font-size:11px;">Print Roster (A4)</button>
    </div>
  </div>

  @foreach($coursePools as $pIdx => $pool)
    @php
      $course = $pool['course'];
      $applicants = $pool['applicants'];
      $summary = $pool['branchSummary'];
      $total = $pool['total_count'];
      $branchFullName = \App\Models\OeCourse::getBranchFullName($course->offering_branch);
    @endphp

    <div class="{{ !$loop->last ? 'page-break' : '' }}">
      <!-- Official Header -->
      <div class="header">
        <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
        <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; Government Aided Institution &bull; Approved by AICTE &amp; SBTE Kerala</div>
        <div class="doc-title">Open Elective Course &bull; Pre-Allocation First Option Applicant Status</div>
        <div style="font-size: 10px; color: #475569; margin-top: 3px;">
          Admission 2024 (Semester 6) &bull; Revision 2021 Diploma Curriculum &bull; Pre-Allocation Demand Audit
        </div>
      </div>

      <!-- Course & Schedule Metadata -->
      <table class="meta-table">
        <tr>
          <td class="bold">Offering Department:</td>
          <td><strong>{{ $branchFullName }}</strong> ({{ $course->offering_branch }})</td>
          <td class="bold">Course Code:</td>
          <td class="font-mono">{{ $course->course_code }}</td>
        </tr>
        <tr>
          <td class="bold">Open Elective Course:</td>
          <td><strong>{{ $course->course_title }}</strong></td>
          <td class="bold">Sanctioned Seats:</td>
          <td><strong>{{ $course->max_capacity }} Seats</strong></td>
        </tr>
        <tr>
          <td class="bold">Total 1st-Choice Applicants:</td>
          <td>
            <strong style="color: #0369a1; font-size: 12px;">{{ $total }} Students</strong>
            @if($course->max_capacity > 0)
              ({{ round(($total / $course->max_capacity) * 100, 1) }}% of Capacity)
            @endif
          </td>
          <td class="bold">Generated Timestamp:</td>
          <td>{{ now()->format('d M Y, h:i A') }}</td>
        </tr>
      </table>

      <!-- Branch-Wise First Option Distribution -->
      <div style="font-weight:bold; font-size:10px; text-transform:uppercase; margin-bottom:4px; color:#1e293b;">
        Department-Wise Distribution of 1st-Option Applicants:
      </div>
      <table class="branch-summary-table">
        <thead>
          <tr>
            <th>Automobile (AU)</th>
            <th>Civil (CE)</th>
            <th>Computer (CT)</th>
            <th>Electrical (EEE)</th>
            <th>Electronics (EL)</th>
            <th>Mechanical (ME)</th>
            <th style="background:#e0f2fe; color:#0369a1;">Total 1st Choices</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $summary['AU'] ?? 0 }}</td>
            <td>{{ $summary['CE'] ?? 0 }}</td>
            <td>{{ $summary['CT'] ?? 0 }}</td>
            <td>{{ $summary['EEE'] ?? 0 }}</td>
            <td>{{ $summary['EL'] ?? 0 }}</td>
            <td>{{ $summary['ME'] ?? 0 }}</td>
            <td style="font-weight:bold; background:#f0f9ff; color:#0284c7;">{{ $total }}</td>
          </tr>
        </tbody>
      </table>

      <!-- First-Option Applicants Table (Ordered by FCFS Timestamp) -->
      <div style="font-weight:bold; font-size:10px; text-transform:uppercase; margin-bottom:4px; color:#1e293b;">
        First-Option Priority Queue (Chronological First-Come, First-Served Order):
      </div>

      <table class="roster-table">
        <thead>
          <tr>
            <th class="center" style="width: 32px;">FCFS #</th>
            <th style="width: 120px;">Registration Time</th>
            <th style="width: 100px;">SBTE PRN</th>
            <th>Student Name</th>
            <th style="width: 70px;" class="center">Adm No</th>
            <th style="width: 80px;" class="center">Parent Dept</th>
            <th>Alternative Preferences (Options 2 &rarr; 5)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($applicants as $idx => $st)
            <tr>
              <td class="center font-mono">{{ $idx + 1 }}</td>
              <td style="font-size: 9.5px;">{{ $st->registered_at ? $st->registered_at->format('d/m/Y h:i:s A') : '-' }}</td>
              <td class="font-mono" style="color: #0369a1;">{{ $st->sbte_reg_no }}</td>
              <td><strong>{{ $st->student_name }}</strong></td>
              <td class="center font-mono">{{ $st->student_adm_no }}</td>
              <td class="center">
                <span class="badge">{{ $st->parent_branch }}</span>
              </td>
              <td>
                @if($st->option2Course)<span class="opt-tag" title="Option 2: {{ $st->option2Course->course_title }}">2: {{ $st->option2Course->offering_branch }} ({{ $st->option2Course->course_code }})</span>@endif
                @if($st->option3Course)<span class="opt-tag" title="Option 3: {{ $st->option3Course->course_title }}">3: {{ $st->option3Course->offering_branch }} ({{ $st->option3Course->course_code }})</span>@endif
                @if($st->option4Course)<span class="opt-tag" title="Option 4: {{ $st->option4Course->course_title }}">4: {{ $st->option4Course->offering_branch }} ({{ $st->option4Course->course_code }})</span>@endif
                @if($st->option5Course)<span class="opt-tag" title="Option 5: {{ $st->option5Course->course_title }}">5: {{ $st->option5Course->offering_branch }} ({{ $st->option5Course->course_code }})</span>@endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="center" style="padding: 18px; color: #64748b; font-style: italic;">
                No students have chosen this course as their first option yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <!-- Signatures Block -->
      <div class="signatures">
        <div class="sig-col">
          HOD ({{ $course->offering_branch }})<br>
          <span style="font-weight:normal; font-size:9px; color:#475569;">Head of Offering Department</span>
        </div>
        <div class="sig-col">
          Open Elective Coordinator<br>
          <span style="font-weight:normal; font-size:9px; color:#475569;">Admission 2024 Audit Desk</span>
        </div>
        <div class="sig-col">
          Principal<br>
          <span style="font-weight:normal; font-size:9px; color:#475569;">Carmel Polytechnic College</span>
        </div>
      </div>
    </div>
  @endforeach

</body>
</html>