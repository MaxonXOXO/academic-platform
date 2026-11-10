<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Carmel Polytechnic College — Open Elective Nominal Roll ({{ $course->offering_branch }})</title>
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
      line-height: 1.3;
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
      padding: 3px 6px;
    }
    .roster-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10.5px;
    }
    .roster-table th, .roster-table td {
      border: 1px solid #333;
      padding: 4.5px 6px;
      text-align: left;
    }
    .roster-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 9.5px;
    }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .summary-box {
      margin-top: 10px;
      padding: 6px 10px;
      background: #f9f9f9;
      border: 1px dashed #666;
      font-size: 10.5px;
    }
    .signatures {
      margin-top: 35px;
      display: flex;
      justify-content: space-between;
      width: 100%;
    }
    .sig-col {
      text-align: center;
      width: 30%;
      border-top: 1px solid #222;
      padding-top: 5px;
      font-size: 10.5px;
    }
    @media print {
      .no-print { display: none; }
      body { -webkit-print-color-adjust: exact; }
    }
  </style>
</head>
<body>

  <div class="no-print" style="background:#222;color:#fff;padding:8px 15px;display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
    <span>Official SBTE Print Document — Revision 2021 Semester 6 Open Elective</span>
    <button onclick="window.print()" style="background:#0284c7;color:#fff;border:none;padding:6px 15px;border-radius:4px;cursor:pointer;font-weight:bold;">Print / Save as PDF</button>
  </div>

  <div class="header">
    <div class="college-name">Carmel Polytechnic College, Alappuzha</div>
    <div class="college-sub">Punnapra, Alappuzha - 688004 &bull; Approved by AICTE &amp; Affiliated to SBTE Kerala</div>
    <div class="doc-title">Open Elective Course Nominal Roll &mdash; Revision 2021</div>
  </div>

  <table class="meta-table">
    <tr>
      <td width="15%" class="bold">Offering Dept:</td>
      <td width="35%">Department of {{ $course->offering_branch }}</td>
      <td width="15%" class="bold">Course Code:</td>
      <td width="35%" class="bold">{{ $course->course_code }}</td>
    </tr>
    <tr>
      <td class="bold">Course Title:</td>
      <td><strong>{{ $course->course_title }}</strong></td>
      <td class="bold">Academic Term:</td>
      <td>Semester 6 ({{ $course->academic_year }})</td>
    </tr>
    <tr>
      <td class="bold">Faculty In-Charge:</td>
      <td>{{ $course->faculty_name ?: '_____________________________' }}</td>
      <td class="bold">Total Enrolled:</td>
      <td><strong>{{ $students->count() }}</strong> / {{ $course->max_capacity }} Seats</td>
    </tr>
  </table>

  <table class="roster-table">
    <thead>
      <tr>
        <th width="5%" class="center">Sl No</th>
        <th width="15%">Admission No</th>
        <th width="20%">SBTE Register No (PRN)</th>
        <th width="40%">Student Full Name</th>
        <th width="20%" class="center">Parent Department</th>
      </tr>
    </thead>
    <tbody>
      @forelse($students as $idx => $st)
        <tr>
          <td class="center">{{ $idx + 1 }}</td>
          <td>{{ $st->student_adm_no }}</td>
          <td class="bold">{{ $st->sbte_reg_no }}</td>
          <td class="bold">{{ strtoupper($st->student_name) }}</td>
          <td class="center bold">{{ $st->parent_branch }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="center" style="padding: 20px;">No students enrolled yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <!-- Branch Summary -->
  <div class="summary-box">
    <strong>Inter-Departmental Composition Summary:</strong> &nbsp;
    @foreach($branchSummary as $br => $cnt)
      <span>{{ $br }}: <strong>{{ $cnt }}</strong></span> &nbsp;&bull;&nbsp;
    @endforeach
    <span>Total Students: <strong>{{ $students->count() }}</strong></span>
  </div>

  <div class="signatures">
    <div class="sig-col">
      <strong>Course Faculty In-Charge</strong>
      <div>Date: _______________</div>
    </div>
    <div class="sig-col">
      <strong>Head of Department ({{ $course->offering_branch }})</strong>
      <div>Signature &amp; Seal</div>
    </div>
    <div class="sig-col">
      <strong>Principal / Executive Authority</strong>
      <div>Carmel Polytechnic College</div>
    </div>
  </div>

</body>
</html>
