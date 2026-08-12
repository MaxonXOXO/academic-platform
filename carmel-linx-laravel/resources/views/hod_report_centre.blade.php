<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Centre - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-gradient {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-amber-500/30">

  <!-- Sticky Header -->
  <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-2xl">
    <div class="px-6 h-12 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="/dashboard/hod" class="flex items-center gap-1.5 px-2.5 py-1 bg-amber-500/15 hover:bg-amber-500/30 border border-amber-500/40 hover:border-amber-400 text-amber-400 hover:text-amber-300 rounded-lg font-bold transition-premium no-underline text-xs">
          <span class="material-symbols-rounded text-xs">arrow_back</span>
          <span>Back</span>
        </a>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white font-black rounded-md w-6 h-6 flex items-center justify-center text-[10px] shadow-lg shadow-amber-500/20">RC</div>
        <div>
          <h1 class="font-extrabold text-slate-100 tracking-wide text-xs flex items-center gap-2 m-0">
            Report Centre
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest bg-slate-800/50 px-1.5 py-0.5 rounded border border-slate-700/50">{{ session('userBranch') }} Dept</span>
          </h1>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <span class="px-2 py-0.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-md font-mono text-xs">
          {{ session('userBranch') }}
        </span>
      </div>
    </div>
  </header>

  <!-- Main Content Space -->
  <main class="flex-grow p-3.5 lg:p-5 max-w-[85rem] mx-auto w-full space-y-4">
    
    <!-- Hero Banner Section -->
    <div class="bg-gradient-to-r from-amber-500/10 via-orange-600/5 to-slate-950 border border-amber-500/20 rounded-xl p-3.5 md:p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-xl">
      <div class="space-y-0.5">
        <h2 class="text-white text-sm font-bold tracking-tight m-0">Centralized Analytical Report Engine</h2>
        <p class="text-slate-400 max-w-2xl leading-relaxed text-xs m-0">
          Pull historical records, download mentoring logs, track academic performance analytics, and view audit reports. All departmental intelligence is gathered here in real-time.
        </p>
      </div>
      <div class="flex-shrink-0">
        <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
          <span class="material-symbols-rounded text-lg">insights</span>
        </div>
      </div>
    </div>

    <!-- Reports Directory Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
      
      <!-- Card 1: Attendance & Log Analysis -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-sky-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-sky-400 text-lg">co_present</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-sky-500/15 border border-sky-500/30 text-sky-400 font-bold text-xs">1</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Attendance, Log, Condonation</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Consolidated reports of daily class logs, lesson plan coverage rates, and student attendance percentages by batch.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Live Coverage Logs</span>
          <button onclick="openAttendanceModal()" class="px-2.5 py-1 bg-sky-500/15 hover:bg-sky-500/30 text-sky-400 hover:text-sky-300 border border-sky-500/30 hover:border-sky-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            Compile Logs
          </button>
        </div>
      </div>

      <!-- Card 2: Remedial Session Analysis -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-purple-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-purple-400 text-lg">psychology</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-purple-500/15 border border-purple-500/30 text-purple-400 font-bold text-xs">2</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Remedial Coaching Analytics</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Track diagnostics, active coaching rooms, weakness analysis, and student improvement outcomes for slower learners.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Diagnostic Reports</span>
          <button onclick="openRemedialModal()" class="px-2.5 py-1 bg-purple-500/15 hover:bg-purple-500/30 text-purple-400 hover:text-purple-300 border border-purple-500/30 hover:border-purple-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            Analyze Data
          </button>
        </div>
      </div>

      <!-- Card 3: Faculty Workload Report -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-500 text-lg">pending_actions</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-400 font-bold text-xs">3</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Faculty Workload & Timetables</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Consolidated workload hours for classroom lectures and laboratories per week across all department timetables.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Commencement Week</span>
          <a href="/hod/report-centre/workload-panel" class="px-2.5 py-1 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-lg font-bold transition-premium cursor-pointer text-xs no-underline inline-block">
            View Panel
          </a>
        </div>
      </div>

      <!-- Card 4: Extra-Curricular Claims -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-rose-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-rose-400 text-lg">emoji_events</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-400 font-bold text-xs">4</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Extra-Curricular Claims</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Aggregate student activity point verifications, pending claims logs, and approved co-curricular achievement statuses.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Activity Analytics</span>
          <button onclick="openActivityPointsModal()" class="px-2.5 py-1 bg-rose-500/15 hover:bg-rose-500/30 text-rose-400 hover:text-rose-300 border border-rose-500/30 hover:border-rose-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            View Claims
          </button>
        </div>
      </div>

      <!-- Card 5: Department Course Files -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-emerald-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-emerald-400 text-lg">folder_zip</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-bold text-xs">5</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Department Course Files</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Consolidated audits of subject syllabus progress, CO-PO mappings, assignment plans, and lesson plan compliance.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Curriculum Compliance</span>
          <button onclick="openCourseFilesModal()" class="px-2.5 py-1 bg-emerald-500/15 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            Check Status
          </button>
        </div>
      </div>

      <!-- Card 6: Mentoring Diaries -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-400 text-lg">book</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-400 font-bold text-xs">6</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Student Mentoring Diaries</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Generate and export complete cumulative mentoring diaries, counselor notes, and personal records for students.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">PDF / Print Format</span>
          <button onclick="alert('Feature coming soon: Dynamic Mentoring Export will pull active records.')" class="px-2.5 py-1 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            Access Logs
          </button>
        </div>
      </div>

      <!-- Card 7: SBTE Audit Console -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-sky-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-sky-400 text-lg">verified_user</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-sky-500/15 border border-sky-500/30 text-sky-400 font-bold text-xs">7</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">SBTE Annual Compliance Audit</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Manage mandatory annual audit documentation, AICTE approval letters, affiliation orders, and board result registries.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">SBTE Accreditation</span>
          <a href="/hod/sbte-audit" class="px-2.5 py-1 bg-sky-500/15 hover:bg-sky-500/30 text-sky-400 hover:text-sky-300 border border-sky-500/30 hover:border-sky-400 rounded-lg font-bold transition-premium cursor-pointer text-xs no-underline inline-block">
            View Console
          </a>
        </div>
      </div>

      <!-- Card 8: NBA Criteria Audit Console -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-rose-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-rose-400 text-lg">menu_book</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-400 font-bold text-xs">8</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">NBA Criteria Accreditation</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Organize and review academic audit files and related documentation across NBA Criteria 1 to 10.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">NBA Criteria Audit</span>
          <a href="/hod/nba-audit" class="px-2.5 py-1 bg-rose-500/15 hover:bg-rose-500/30 text-rose-400 hover:text-rose-300 border border-rose-500/30 hover:border-rose-400 rounded-lg font-bold transition-premium cursor-pointer text-xs no-underline inline-block">
            View Console
          </a>
        </div>
      </div>

      <!-- Card 9: Academic Calendar Preparation -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-amber-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-amber-400 text-lg">calendar_month</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-400 font-bold text-xs">9</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Academic Calendar Prep</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            All semester department academic calendar details will be managed, scheduled, and configured here.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Academic Planning</span>
          <a href="/hod/academic-calendar" class="px-2.5 py-1 bg-amber-500/15 hover:bg-amber-500/30 text-amber-400 hover:text-amber-300 border border-amber-500/30 hover:border-amber-400 rounded-lg font-bold transition-premium cursor-pointer text-xs no-underline inline-block">
            Open Planner
          </a>
        </div>
      </div>

      <!-- Card 10: Security & Operations Audit -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-violet-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-violet-400 text-lg">receipt_long</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-violet-500/15 border border-violet-500/30 text-violet-400 font-bold text-xs">10</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Security & Operations Audit</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Detailed department timeline of actions, password resets, registration changes, and critical security audits.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Audit History</span>
          <button onclick="alert('Feature coming soon: Security Log Exports.')" class="px-2.5 py-1 bg-violet-500/15 hover:bg-violet-500/30 text-violet-400 hover:text-violet-300 border border-violet-500/30 hover:border-violet-400 rounded-lg font-bold transition-premium cursor-pointer text-xs">
            Extract Logs
          </button>
        </div>
      </div>

      <!-- Card 11: Staff Leave Master Ledger & Audit Reports -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3 space-y-2 hover:border-emerald-500/40 transition-premium shadow-md flex flex-col justify-between">
        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <span class="material-symbols-rounded text-emerald-400 text-lg">event_note</span>
            <div class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
              <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-bold text-xs">11</span>
            </div>
          </div>
          <h3 class="text-white text-xs font-bold m-0">Staff Leave Master Ledger</h3>
          <p class="text-slate-400 text-[11px] leading-snug m-0">
            Consolidated staff leave applications, multi-stage approval logs, formal leave PDFs, and CL/CCL/DL/ML summary reports.
          </p>
        </div>
        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between mt-1">
          <span class="text-[10px] text-slate-500">Printable Ledger</span>
          <a href="/staff/leave/reports" target="_blank" class="px-2.5 py-1 bg-emerald-500/15 hover:bg-emerald-500/30 text-emerald-400 hover:text-emerald-300 border border-emerald-500/30 hover:border-emerald-400 rounded-lg font-bold transition-premium cursor-pointer text-xs no-underline inline-flex items-center gap-1">
            <span class="material-symbols-rounded text-xs">print</span> Open Ledger
          </a>
        </div>
      </div>

    </div>

  </main>

  <!-- ATTENDANCE MODAL -->
  <div id="attendanceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-sky-400 text-base">co_present</span> Attendance Summary
        </h3>
        <button onclick="closeAttendanceModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated class attendance summary, lesson plan coverage rates, and condonation list.
        </p>
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
            <select id="selectAttendanceBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Report Type</label>
            <select id="selectAttendanceReportType" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              <option value="coverage">Course Coverage Rates & Hours Conducted</option>
              <option value="roster">Student Attendance Roster & Deficiencies</option>
              <option value="condonation">Condonation Students List (SBTE No)</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAttendanceModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printAttendanceSummary()" class="flex-1 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Summary
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- REMEDIAL MODAL -->
  <div id="remedialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-purple-400 text-base">psychology</span> Remedial Analysis
        </h3>
        <button onclick="closeRemedialModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated Remedial Session Analytics, conducted hours, and registered students list.
        </p>
        <div>
          <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
          <select id="selectRemedialBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRemedialModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printRemedialReport()" class="flex-1 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- COURSE FILES MODAL -->
  <div id="courseFilesModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-400 text-base">folder_zip</span> Course Files Status
        </h3>
        <button onclick="closeCourseFilesModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Select a semester batch to generate the consolidated syllabus registry, CO-PO mapping, and NBA Course File compliance status report.
        </p>
        <div>
          <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
          <select id="selectCourseFilesBatch" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
            @foreach($batches as $batch)
              <option value="{{ $batch->classroom_id }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester }})</option>
            @endforeach
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeCourseFilesModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printCourseFilesReport()" class="flex-1 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ACTIVITY POINTS MODAL -->
  <div id="activityPointsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-400 text-base">emoji_events</span> Activity Points Report
        </h3>
        <button onclick="closeActivityPointsModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Generate semester-wise or batch-wise student activity points audits showing target thresholds for course completion.
        </p>
        
        <div class="space-y-3">
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Batch</label>
            <select id="selectActivityBatch" onchange="updateActivitySemesterOptions()" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
              @foreach($batches as $batch)
                <option value="{{ $batch->classroom_id }}" data-semester="{{ $batch->current_semester ?? 1 }}">{{ $batch->classroom_id }} (Sem {{ $batch->current_semester ?? 1 }})</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 font-bold">Select Semester Scope</label>
            <select id="selectActivitySemester" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-2 text-white outline-none text-sm">
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeActivityPointsModal()" class="flex-1 py-2 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="button" onclick="printActivityPointsReport()" class="flex-1 py-2 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span class="material-symbols-rounded text-sm">print</span> Print Report
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Footer -->
  <footer class="bg-slate-900 border-t border-slate-800/80 py-4 text-center text-slate-500 text-xs mt-auto">
    <p>&copy; 2026 Carmel Linx - Report Centre Engine. All rights reserved.</p>
  </footer>

  <script>
    function openAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printAttendanceSummary() {
      const batchId = document.getElementById('selectAttendanceBatch').value;
      const reportType = document.getElementById('selectAttendanceReportType').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeAttendanceModal();
      window.open('/hod/attendance-summary/print?classroom_id=' + encodeURIComponent(batchId) + '&report_type=' + encodeURIComponent(reportType), '_blank');
    }

    function openRemedialModal() {
      const modal = document.getElementById('remedialModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRemedialModal() {
      const modal = document.getElementById('remedialModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printRemedialReport() {
      const batchId = document.getElementById('selectRemedialBatch').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeRemedialModal();
      window.open('/hod/remedial-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }

    function openCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printCourseFilesReport() {
      const batchId = document.getElementById('selectCourseFilesBatch').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeCourseFilesModal();
      window.open('/hod/course-files-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }

    function updateActivitySemesterOptions() {
      const batchSelect = document.getElementById('selectActivityBatch');
      const semSelect = document.getElementById('selectActivitySemester');
      if (!batchSelect || !semSelect) return;

      const selectedOpt = batchSelect.options[batchSelect.selectedIndex];
      if (!selectedOpt) return;

      const rawSem = parseInt(selectedOpt.getAttribute('data-semester') || '1', 10);
      const maxSem = Math.max(1, Math.min(isNaN(rawSem) ? 1 : rawSem, 6));

      semSelect.innerHTML = '<option value="all">All Semesters (Cumulative)</option>';
      for (let s = 1; s <= maxSem; s++) {
        const opt = document.createElement('option');
        opt.value = s;
        opt.textContent = `Semester ${s}`;
        semSelect.appendChild(opt);
      }
    }

    function openActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      updateActivitySemesterOptions();
    }

    function closeActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function printActivityPointsReport() {
      const batchId = document.getElementById('selectActivityBatch').value;
      const sem = document.getElementById('selectActivitySemester').value;
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeActivityPointsModal();
      window.open('/hod/activity-points-report/print?classroom_id=' + encodeURIComponent(batchId) + '&semester=' + encodeURIComponent(sem), '_blank');
    }
  </script>

</body>
</html>
