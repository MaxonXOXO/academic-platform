<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Workload Commencement Report - {{ $department }}</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    body {
      font-family: 'Inter', Arial, sans-serif;
      background-color: #0b0f19;
      color: #f1f5f9;
      margin: 0;
      padding: 20px 10px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* Screen A4 Outer Box */
    .a4-page-frame {
      background-color: #111827;
      border: 3px double #38bdf8;
      border-radius: 8px;
      padding: 16px;
      max-width: 980px;
      margin: 0 auto;
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.7);
    }

    /* Inner Framing Border on Page */
    .a4-inner-border {
      border: 2px solid #334155;
      padding: 20px 24px;
      border-radius: 4px;
      background-color: #151d30;
      position: relative;
    }

    /* Meta Info Grid */
    .meta-box {
      border: 1px solid #2a3754;
      background-color: #0f172a;
      border-radius: 4px;
      padding: 8px 14px;
      margin-top: 10px;
      margin-bottom: 14px;
    }

    /* Professional Table Styling */
    table.workload-tbl {
      border-collapse: collapse;
      width: 100%;
      margin-top: 6px;
      font-size: 11px;
      border: 2px solid #334155;
    }

    table.workload-tbl th {
      background-color: #1e293b;
      color: #f8fafc;
      border: 1.5px solid #334155;
      padding: 7px 8px;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-size: 9.5px;
      font-weight: 700;
      text-align: center;
    }

    table.workload-tbl td {
      border: 1px solid #334155;
      padding: 6px 8px;
      vertical-align: middle;
      font-size: 10.5px;
      color: #cbd5e1;
    }

    .subtotal-row td {
      background-color: #0f172a !important;
      font-weight: 700 !important;
      color: #e2e8f0 !important;
      border-top: 1.5px solid #475569 !important;
      font-size: 10.5px !important;
    }

    .grandtotal-row td {
      background-color: #0f172a !important;
      font-weight: 900 !important;
      color: #ffffff !important;
      border-top: 2.5px double #38bdf8 !important;
      border-bottom: 2px solid #38bdf8 !important;
      font-size: 11px !important;
    }

    .section-title {
      background-color: #1e293b;
      color: #38bdf8;
      border: 1px solid #334155;
      font-weight: 800;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 4px 10px;
      margin-top: 12px;
      margin-bottom: 4px;
    }

    .badge-ext {
      background-color: #451a03;
      color: #fde68a;
      border: 1px solid #78350f;
      font-size: 8.5px;
      padding: 1px 4px;
      border-radius: 3px;
      font-weight: 700;
      margin-left: 5px;
      display: inline-block;
    }

    /* Print Format: Strict A4 Frame Border & Formatting */
    @media print {
      .no-print {
        display: none !important;
      }
      @page {
        size: A4 portrait;
        margin: 8mm;
      }
      body {
        background-color: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 10px !important;
      }
      .a4-page-frame {
        background-color: #ffffff !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        max-width: 100% !important;
        margin: 0 !important;
      }
      /* FULL A4 SHEET OUTER FRAME BORDER */
      .a4-inner-border {
        border: 2.5px solid #000000 !important;
        padding: 12px 14px !important;
        border-radius: 0 !important;
        background-color: #ffffff !important;
        min-height: 275mm !important;
        box-sizing: border-box !important;
      }
      .meta-box {
        background-color: #ffffff !important;
        border: 1.5px solid #000000 !important;
        border-radius: 0 !important;
        padding: 6px 10px !important;
        margin-top: 8px !important;
        margin-bottom: 10px !important;
      }
      .header-line {
        border-bottom: 3px double #000000 !important;
      }
      .section-title {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border: 1.5px solid #000000 !important;
        font-weight: 800 !important;
      }
      table.workload-tbl {
        border: 2px solid #000000 !important;
        font-size: 9.5px !important;
      }
      table.workload-tbl th {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border: 1.5px solid #000000 !important;
        padding: 5px 6px !important;
        font-weight: 800 !important;
      }
      table.workload-tbl td {
        border: 1px solid #000000 !important;
        color: #000000 !important;
        padding: 4px 6px !important;
        font-size: 10px !important;
      }
      .subtotal-row td {
        background-color: #f8fafc !important;
        color: #000000 !important;
        border-top: 1.5px solid #000000 !important;
        font-weight: 800 !important;
      }
      .grandtotal-row td {
        background-color: #e2e8f0 !important;
        color: #000000 !important;
        border-top: 2.5px double #000000 !important;
        border-bottom: 2px solid #000000 !important;
        font-weight: 900 !important;
      }
      .badge-ext {
        display: none !important;
      }
      .text-slate-400, .text-slate-300, .text-slate-200, .text-slate-100, .text-white, strong, span, h1, h2, h3, p, td, th {
        color: #000000 !important;
      }
      .meta-box strong, .meta-box span {
        color: #000000 !important;
      }
      .border-dashed {
        border-style: dashed !important;
        border-color: #000000 !important;
      }
    }
  </style>
</head>
<body>
  
  <div class="a4-page-frame">
    
    <!-- Action Bar (Screen Only) -->
    <div class="no-print flex justify-between items-center bg-slate-900 p-2.5 rounded-lg border border-slate-800 mb-3">
      <div class="text-xs text-slate-300 flex items-center gap-2">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-sky-400 animate-pulse"></span>
        <span><strong>Official A4 Print Layout:</strong> 2.5px Solid Frame Border & Staff Rank Hierarchy active.</span>
      </div>
      <div class="flex gap-2">
        <button onclick="window.print()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded font-bold text-xs shadow transition flex items-center gap-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
          Print Official Sheet
        </button>
        <button onclick="window.close()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-semibold text-xs transition">
          Close
        </button>
      </div>
    </div>

    <!-- FULL A4 INNER PAGE FRAME BORDER -->
    <div class="a4-inner-border">

      <!-- Institution Header -->
      <div class="text-center pb-2.5 header-line border-b-2 border-slate-700">
        <h1 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Carmel Polytechnic College, Alappuzha</h1>
        <h2 class="text-base font-black text-white mt-0.5 uppercase tracking-tight">DEPARTMENT FACULTY WORKLOAD ALLOCATION REPORT</h2>
        <p class="text-[9.5px] text-slate-400 mt-0.5 tracking-wide uppercase font-semibold">Academic Session {{ $academicYear ?? ($currentYear . ' - ' . ($currentYear + 1)) }}</p>
      </div>
      
      <!-- Meta Information Grid Box -->
      <div class="meta-box">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
          <div>
            <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Department:</span><br>
            <strong class="text-white font-bold text-[11px]">
              {{ getFullBranchName($branchCode ?? $department) }}
            </strong>
          </div>
          <div>
            <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Academic Session / Year:</span><br>
            <strong class="text-white font-bold text-[11px]">{{ $academicYear ?? ($currentYear . ' - ' . ($currentYear + 1)) }}</strong>
          </div>
          <div>
            <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Batches & Semesters:</span><br>
            <strong class="text-white font-bold text-[11px]">{{ $batchSummary ?? 'All Department Batches' }}</strong>
          </div>
          <div class="text-right">
            <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Semester / Term:</span><br>
            <strong class="text-white font-bold text-[11px]">{{ $semTerm }}</strong>
          </div>
        </div>
      </div>

      @php
        $grandTheory = 0;
        $grandLab = 0;
        $grandTotal = 0;
        $slNo = 1;
      @endphp

      <!-- SECTION 1: HOME DEPARTMENT STAFF -->
      <div class="section-title">
        I. Home Department Faculty & Instructional Staff (Rank Ordered)
      </div>

      <table class="workload-tbl">
        <thead>
          <tr>
            <th class="w-10 text-center">Sl. No.</th>
            <th class="text-left">Faculty / Staff Name</th>
            <th class="text-left">Designation</th>
            <th class="w-36 text-center">Department</th>
            <th class="w-24 text-center">Theory Hours<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
            <th class="w-24 text-center">Lab Hours<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
            <th class="w-28 text-center">Total Load<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
          </tr>
        </thead>
        <tbody>
          @php
            $homeTheory = 0;
            $homeLab = 0;
            $homeTotal = 0;
          @endphp
          @forelse ($homeWorkload as $facultyName => $data)
            @php
              $homeTheory += $data['theory'];
              $homeLab += $data['lab'];
              $homeTotal += $data['total'];

              $grandTheory += $data['theory'];
              $grandLab += $data['lab'];
              $grandTotal += $data['total'];
            @endphp
            <tr>
              <td class="text-center font-mono text-slate-400 font-semibold">{{ $slNo++ }}</td>
              <td class="font-bold text-white">{{ $facultyName }}</td>
              <td>{{ str_replace('_', ' ', $data['designation']) }}</td>
              <td class="text-center font-semibold text-[10px]">{{ getFullBranchName($data['branch'] ?? $branchCode) }}</td>
              <td class="text-center font-semibold">{{ $data['theory'] }}</td>
              <td class="text-center font-semibold">{{ $data['lab'] }}</td>
              <td class="text-center font-bold text-white">{{ $data['total'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-4 text-center text-slate-400 italic">No home department staff profiles registered.</td>
            </tr>
          @endforelse

          @if (count($homeWorkload) > 0)
            <tr class="subtotal-row">
              <td colspan="4" class="text-right pr-4 uppercase tracking-wider font-bold text-[9.5px]">Home Dept Sub-Total Load</td>
              <td class="text-center font-bold text-xs">{{ $homeTheory }}</td>
              <td class="text-center font-bold text-xs">{{ $homeLab }}</td>
              <td class="text-center font-extrabold text-xs text-white">{{ $homeTotal }}</td>
            </tr>
          @endif
        </tbody>
      </table>

      <!-- SECTION 2: INTER-DEPARTMENT / VISITING FACULTY -->
      @if (count($interWorkload) > 0)
        <div class="section-title mt-4">
          II. Inter-Department / Visiting Faculty Allocated
        </div>

        <table class="workload-tbl">
          <thead>
            <tr>
              <th class="w-10 text-center">Sl. No.</th>
              <th class="text-left">Faculty / Staff Name</th>
              <th class="text-left">Designation</th>
              <th class="w-36 text-center">Department</th>
              <th class="w-24 text-center">Theory Hours<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
              <th class="w-24 text-center">Lab Hours<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
              <th class="w-28 text-center">Total Load<br><span class="text-[8px] font-normal lowercase">(hrs/week)</span></th>
            </tr>
          </thead>
          <tbody>
            @php
              $interTheory = 0;
              $interLab = 0;
              $interTotal = 0;
            @endphp
            @foreach ($interWorkload as $facultyName => $data)
              @php
                $interTheory += $data['theory'];
                $interLab += $data['lab'];
                $interTotal += $data['total'];

                $grandTheory += $data['theory'];
                $grandLab += $data['lab'];
                $grandTotal += $data['total'];
              @endphp
              <tr>
                <td class="text-center font-mono text-slate-400 font-semibold">{{ $slNo++ }}</td>
                <td class="font-bold text-white">
                  {{ $facultyName }}
                </td>
                <td>{{ str_replace('_', ' ', $data['designation']) }}</td>
                <td class="text-center font-semibold text-[10px]">{{ getFullBranchName($data['branch'] ?? 'External') }}</td>
                <td class="text-center font-semibold">{{ $data['theory'] }}</td>
                <td class="text-center font-semibold">{{ $data['lab'] }}</td>
                <td class="text-center font-bold text-white">{{ $data['total'] }}</td>
              </tr>
            @endforeach

            <tr class="subtotal-row">
              <td colspan="4" class="text-right pr-4 uppercase tracking-wider font-bold text-[9.5px]">Inter-Dept Sub-Total Load</td>
              <td class="text-center font-bold text-xs">{{ $interTheory }}</td>
              <td class="text-center font-bold text-xs">{{ $interLab }}</td>
              <td class="text-center font-extrabold text-xs text-white">{{ $interTotal }}</td>
            </tr>
          </tbody>
        </table>
      @endif

      <!-- GRAND TOTAL SUMMARY ROW -->
      <table class="workload-tbl mt-3">
        <tbody>
          <tr class="grandtotal-row">
            <td colspan="4" class="text-right pr-4 uppercase tracking-wider font-black text-[10px]">GRAND TOTAL DEPARTMENT WORKLOAD (HOME + INTER-DEPT)</td>
            <td class="w-24 text-center font-black text-xs">{{ $grandTheory }}</td>
            <td class="w-24 text-center font-black text-xs">{{ $grandLab }}</td>
            <td class="w-28 text-center font-black text-xs text-white">{{ $grandTotal }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Explanatory Legend / Footnote -->
      <div class="text-[9px] text-slate-400 pt-2 flex justify-between items-center">
        <div>
          * <strong>R26 Practicum Rule:</strong> Standalone 1-hour slots are credited to Lecturers only. 2+ consecutive slots are credited to both Lecturers and Support Staff.
        </div>
        <div class="font-mono text-[8.5px]">
          Generated: {{ date('d/m/Y h:i A') }}
        </div>
      </div>

      <!-- Signatures Section -->
      <div class="grid grid-cols-2 gap-12 pt-8 meta-lbl">
        <div class="text-center">
          <div class="h-12"></div>
          <p class="border-t border-dashed border-slate-500 pt-1 font-bold uppercase tracking-wider text-[9.5px] text-slate-300">Head of Department</p>
          <p class="text-[9px] text-slate-400 mt-0.5">
            Department of {{ getFullBranchName($branchCode ?? $department) }}
          </p>
        </div>
        <div class="text-center">
          <div class="h-12"></div>
          <p class="border-t border-dashed border-slate-500 pt-1 font-bold uppercase tracking-wider text-[9.5px] text-slate-300">Principal</p>
          <p class="text-[9px] text-slate-400 mt-0.5">Carmel Polytechnic College</p>
        </div>
      </div>

    </div><!-- End a4-inner-border -->

  </div><!-- End a4-page-frame -->

</body>
</html>
