<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student First Login Credentials - {{ $classroom->classroom_id }}</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
      color: #0f172a;
    }
    body, table, td, th {
      font-size: 11px !important;
    }
    @media print {
      body {
        background-color: #ffffff;
        color: #000000;
        margin: 0;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
      @page {
        size: A4 portrait;
        margin: 0.8cm;
      }
      table {
        page-break-inside: auto;
      }
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
      thead {
        display: table-header-group;
      }
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Toolbar (Screen Only) -->
  <div class="max-w-6xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div class="bg-amber-500/10 text-amber-600 p-2.5 rounded-xl flex items-center justify-center">
        <span class="material-symbols-rounded">badge</span>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Student First Login Credentials Slip</h3>
        <p class="text-xs text-slate-500">Department Roster & Initial Portal Access List · Classroom ID: <span class="font-mono font-bold text-slate-700">{{ $classroom->classroom_id }}</span></p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-xs shadow-sm">
        <span class="material-symbols-rounded text-sm">print</span> Print Credentials List
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-xs border border-slate-200">
        <span class="material-symbols-rounded text-sm">close</span> Close
      </button>
    </div>
  </div>

  <!-- A4 Printable Container -->
  <div class="max-w-6xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-10 shadow-sm print:border-0 print:p-0 print:shadow-none">

    <!-- Header Section -->
    <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <img src="{{ asset('logo.jpg') }}" class="w-16 h-16 rounded-xl object-cover border border-slate-300 shadow-xs print:w-14 print:h-14" alt="Carmel Logo" onerror="this.style.display='none'">
        <div>
          <h1 class="text-slate-900 font-black tracking-tight text-xl leading-tight">CARMEL POLYTECHNIC COLLEGE</h1>
          <p class="text-slate-700 font-bold text-xs uppercase tracking-wider">PUNNAPRA, ALAPPUZHA, KERALA - 688004</p>
          <p class="text-amber-800 font-extrabold text-xs uppercase tracking-widest mt-0.5">
            DEPARTMENT OF {{ strtoupper($classroom->branch_full ?? $classroom->branch) }}
          </p>
        </div>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 font-bold text-xs uppercase tracking-wider">
          ACADEMIC PLATFORM
        </span>
        <p class="text-slate-500 text-[10px] font-bold mt-1">Date: {{ $currentDate }}</p>
      </div>
    </div>

    <!-- Document Title & Metadata Box -->
    <div class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-4 print:bg-transparent print:border-slate-300">
      <div class="text-center border-b border-slate-200 pb-3 mb-3">
        <h2 class="text-slate-900 font-black text-base uppercase tracking-wide">
          STUDENT FIRST LOGIN CREDENTIALS & INITIAL ACCESS ROSTER
        </h2>
        <p class="text-slate-600 font-medium text-xs mt-0.5">
          Official departmental credential distribution sheet for student portal onboarding.
        </p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Department</span>
          <span class="font-bold text-slate-800 text-xs">{{ $classroom->branch_full ?? $classroom->branch }}</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Admission Year</span>
          <span class="font-bold text-slate-800 text-xs">{{ $classroom->batch_year }}</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Batch ID / Classroom</span>
          <span class="font-mono font-bold text-amber-900 text-xs bg-amber-100/60 px-1.5 py-0.5 rounded border border-amber-200 inline-block">{{ $classroom->classroom_id }}</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Active Semester</span>
          <span class="font-bold text-slate-800 text-xs">Semester {{ $classroom->current_semester ?? 1 }} (S{{ $classroom->current_semester ?? 1 }})</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Class Tutor</span>
          <span class="font-bold text-slate-800 text-xs">{{ $tutor ? $tutor->name : 'Not Assigned' }}</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Class Mentor</span>
          <span class="font-bold text-slate-800 text-xs">{{ $mentor ? $mentor->name : 'Not Assigned' }}</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">Total Enrolled</span>
          <span class="font-bold text-slate-800 text-xs">{{ count($students) }} Students</span>
        </div>
        <div>
          <span class="text-slate-500 font-medium block uppercase text-[9px] tracking-wider">System Portal URL</span>
          <span class="font-mono font-bold text-slate-700 text-xs">carmellinx.in</span>
        </div>
      </div>
    </div>

    <!-- Credentials Table -->
    <div class="overflow-x-auto border border-slate-300 rounded-xl mb-6">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-100 border-b-2 border-slate-300 text-slate-800 font-extrabold uppercase text-[10px] tracking-wider">
            <th class="p-2.5 text-center border-r border-slate-300 w-12">No.</th>
            <th class="p-2.5 border-r border-slate-300 w-24">Admission Year</th>
            <th class="p-2.5 border-r border-slate-300 w-32">Batch ID</th>
            <th class="p-2.5 border-r border-slate-300">Student Name</th>
            <th class="p-2.5 border-r border-slate-300 w-36">Admission No. (Login Username)</th>
            <th class="p-2.5 border-r border-slate-300 w-32">Common Password</th>
            <th class="p-2.5 border-r border-slate-300 text-center w-20">Semester</th>
            <th class="p-2.5 text-center w-28">Student Signature</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($students as $index => $student)
            @php
              $displayPassword = (!empty($student->password) && !str_starts_with($student->password, '$2y$')) 
                                  ? $student->password 
                                  : 'carmel2026';
              $admNoLogin = $student->adm_no ?: ($student->reg_no ?: '-');
            @endphp
            <tr class="hover:bg-slate-50">
              <td class="p-2.5 text-center font-bold text-slate-700 border-r border-slate-200">
                {{ $index + 1 }}
              </td>
              <td class="p-2.5 font-medium text-slate-800 border-r border-slate-200">
                {{ $classroom->batch_year }}
              </td>
              <td class="p-2.5 font-mono text-slate-700 border-r border-slate-200 text-[10px]">
                {{ $classroom->classroom_id }}
              </td>
              <td class="p-2.5 font-bold text-slate-900 border-r border-slate-200">
                {{ $student->name }}
                @if($student->sbte_reg_no)
                  <span class="block text-[9px] text-slate-500 font-mono font-normal">SBTE: {{ $student->sbte_reg_no }}</span>
                @endif
              </td>
              <td class="p-2.5 font-mono font-bold text-amber-900 bg-amber-50/50 border-r border-slate-200">
                {{ $admNoLogin }}
              </td>
              <td class="p-2.5 font-mono font-bold text-slate-800 border-r border-slate-200">
                {{ $displayPassword }}
              </td>
              <td class="p-2.5 text-center font-bold text-slate-800 border-r border-slate-200">
                S{{ $student->semester ?? $classroom->current_semester ?? 1 }}
              </td>
              <td class="p-2.5 border-slate-200 text-center">
                <div class="h-6 border-b border-dashed border-slate-300 w-full"></div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="p-8 text-center text-slate-500 font-medium italic">
                No registered student accounts found in this batch classroom.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Onboarding Instructions Box -->
    <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-3.5 mb-8 text-xs text-amber-950">
      <div class="font-black flex items-center gap-1.5 uppercase text-[10px] tracking-wider mb-1 text-amber-900">
        <span class="material-symbols-rounded text-sm">info</span> First Login Instructions for Students:
      </div>
      <ol class="list-decimal list-inside space-y-0.5 text-[10.5px] font-medium text-amber-900">
        <li>Open the Carmel-Linx Academic Portal at <strong>https://carmellinx.in</strong> or scan institutional QR code.</li>
        <li>Enter your <strong>Admission Number</strong> as your Username and <strong>carmel2026</strong> as your default First Login Password.</li>
        <li>Upon initial sign-in, update your profile photograph, verify your email address, and set a new personal password.</li>
      </ol>
    </div>

    <!-- Signatures Panel -->
    <div class="grid grid-cols-3 gap-8 border-t-2 border-slate-300 pt-8 print:mt-8">
      <div class="text-center">
        <div class="h-12"></div>
        <p class="font-bold text-slate-900 text-xs">Class Tutor Signature</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Department of {{ $classroom->branch_full ?? $classroom->branch }}</p>
      </div>
      <div class="text-center">
        <div class="h-12"></div>
        <p class="font-bold text-slate-900 text-xs">Head of the Department (HOD)</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Department of {{ $classroom->branch_full ?? $classroom->branch }}</p>
      </div>
      <div class="text-center">
        <div class="h-12"></div>
        <p class="font-bold text-slate-900 text-xs">Principal Seal &amp; Signature</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</p>
      </div>
    </div>

  </div>

</body>
</html>
