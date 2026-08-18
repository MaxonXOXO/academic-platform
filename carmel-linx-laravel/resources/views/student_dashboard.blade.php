<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Student Portal</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    @media (max-width: 1440px) {
      html, body {
        font-size: 15px !important;
      }
      .p-6 {
        padding: 1rem !important;
      }
      .p-8 {
        padding: 1.25rem !important;
      }
      .gap-6 {
        gap: 1rem !important;
      }
      .gap-8 {
        gap: 1.25rem !important;
      }
      .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .text-nowrap {
        white-space: nowrap !important;
      }
    }
    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    body { font-family: 'Inter', system-ui, sans-serif; }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
    }
    nav.space-y-1\.5 > :not([hidden]) ~ :not([hidden]) {
      margin-top: 0.25rem !important;
    }
    nav.space-y-1\.5 a, nav.space-y-1\.5 button {
      padding-top: 0.5rem !important;
      padding-bottom: 0.5rem !important;
    }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.4s ease both; }
    
    /* Premium scrollbar styles */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.3);
    }
    ::-webkit-scrollbar-thumb {
      background: rgba(147, 51, 234, 0.4);
      border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: rgba(147, 51, 234, 0.6);
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 h-screen flex flex-col md:flex-row overflow-hidden">

  <!-- Sidebar Backdrop (Mobile only) -->
  <div id="sidebarBackdrop" class="fixed inset-0 bg-black/60 z-20 hidden transition-opacity duration-300 ease-in-out" onclick="toggleMobileSidebar()"></div>

  <!-- Sidebar -->
  <aside id="sidebarMenu" class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-950 flex-shrink-0 flex flex-col border-r border-slate-800/80 shadow-xl transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0 md:sticky md:top-0 md:h-screen overflow-y-auto">
    <!-- Branding -->
    <div class="p-5 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight text-white" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.3px; background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Student Portal</span>
      </div>
    </div>

    <!-- Profile Card -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40">
      <div class="flex items-center gap-3" id="sidebarAvatarContainer">
        @if(session('userPhoto'))
          <img id="sidebarStudentImg" src="{{ session('userPhoto') }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
        @else
          <div id="sidebarStudentPlaceholder" class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-sky-700 flex items-center justify-center font-black shadow text-sm">
            {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
          </div>
        @endif
        <div class="overflow-hidden">
          <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
          <span class="text-xs font-bold text-teal-400 block font-mono">{{ session('userId') }}</span>
          <span class="text-xs text-slate-500 font-semibold">{{ session('userBranch') }} &bull; Student</span>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-grow p-4 space-y-1">
      <button id="navExams" onclick="switchPanel('exams')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500   text-sm">
        <span class="material-symbols-rounded text-lg">checklist</span> Works To Do
      </button>
      <button id="navMarks" onclick="switchPanel('marks')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer   text-sm">
        <span class="material-symbols-rounded text-lg">bar_chart_4_bars</span> Academic Stats
      </button>
      <button id="navProfile" onclick="switchPanel('profile')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer   text-sm">
        <span class="material-symbols-rounded text-lg">manage_accounts</span> My Profile
      </button>
      <a id="navMentoring" href="/student/mentoring-diary" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer   text-sm">
        <span class="material-symbols-rounded text-lg">menu_book</span> Mentoring Diary
      </a>
      <button id="navActivity" onclick="switchPanel('activity')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer   text-sm">
        <span class="material-symbols-rounded text-lg">star</span> Activity Points
      </button>
      <button id="navSeminar" onclick="switchPanel('seminar')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer   text-sm">
        <span class="material-symbols-rounded text-lg">co_present</span> My Seminar
      </button>
      <a id="navAttendance" href="/student/attendance" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-emerald-300 hover:bg-emerald-950/20 cursor-pointer text-sm no-underline">
        <span class="material-symbols-rounded text-lg text-emerald-400">how_to_reg</span> Attendance Review
      </a>
      <a id="navMockTest" href="/student/mock-test" target="_blank" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-teal-300 hover:bg-blue-950/20 cursor-pointer text-sm no-underline">
        <span class="material-symbols-rounded text-lg text-teal-400 animate-pulse">rocket_launch</span> Mock Practice Test
      </a>
    </nav>

    <!-- Logout & Mobile Preview -->
    <div class="p-4 border-t border-slate-800/80 space-y-2">
      <a href="/student/mobile" class="w-full py-2 bg-cyan-950/40 hover:bg-cyan-900/60 text-cyan-300 border border-cyan-500/30 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center transition-premium text-xs">
        <span class="material-symbols-rounded text-base">smartphone</span> Switch to Mobile View
      </a>
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to logout?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-xs">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-grow flex flex-col overflow-hidden">

    <!-- Top Header -->
    <header class="bg-slate-950/40 border-b border-slate-800/80 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 z-10 shadow-lg">
        <div class="flex items-center gap-3">
          <button onclick="toggleMobileSidebar()" class="md:hidden p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition-colors focus:outline-none flex items-center justify-center">
            <span class="material-symbols-rounded">menu</span>
          </button>
          <div>
            <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Works To Do</h1>
            <p class="font-bold text-slate-400 mt-0.5" id="panelSubtitle">Manage your pending assignments and active tests.</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <div class="bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2 font-black uppercase tracking-wider text-slate-400 flex flex-wrap gap-4 text-xs">
            <span>Branch: <strong class="text-slate-200">{{ session('userBranch', '-') }}</strong></span>
            <span>Batch: <strong class="text-slate-200">{{ session('classroomId', '-') }}</strong>
              @if(str_contains(session('classroomId', ''), '_LET'))
                <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-[10px] px-1.5 py-0.5 rounded ml-1 uppercase">LET</span>
              @endif
            </span>
            <span id="headerSemesterText">Sem: <strong class="text-teal-400" id="headerSemValue">...</strong></span>
          </div>
          <a href="/student/mobile" class="px-3 py-2 bg-slate-800 hover:bg-cyan-950/40 text-cyan-400 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/40 rounded-xl font-bold text-xs flex items-center gap-1.5 no-underline transition-premium" title="Switch to Student Mobile Web App">
            <span class="material-symbols-rounded text-sm">smartphone</span> Mobile View
          </a>
        </div>
      </header>

    <!-- Panels -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8">

      <!-- PRE-CLASS ACADEMIC READINESS & LEARNING VAULT ALERT BANNER -->
      <div id="vlmPreClassAlertBanner" class="mb-6 bg-gradient-to-r from-amber-950/70 via-slate-900 to-indigo-950/70 border-2 border-amber-500/60 rounded-2xl p-5 shadow-2xl relative overflow-hidden hidden fade-up">
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
          <div class="flex items-start gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center flex-shrink-0 shadow-lg text-amber-400 animate-pulse">
              <span class="material-symbols-rounded text-2xl">campaign</span>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[11px] font-black uppercase tracking-wider">⚡ Pre-Class Evening Preparation Alert</span>
                <span id="vlmAlertTargetDate" class="text-xs font-mono text-slate-300"></span>
              </div>
              <h3 id="vlmAlertTitle" class="font-black text-white text-base mt-1"></h3>
              <p id="vlmAlertInstruction" class="text-xs text-amber-200/90 mt-1 max-w-2xl"></p>
            </div>
          </div>

          <div class="flex items-center gap-2 flex-shrink-0 w-full md:w-auto justify-end">
            <button onclick="openVlmVaultModal()" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-xl text-xs transition-all shadow-lg flex items-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-base">folder_special</span>
              Open Study Materials Vault
            </button>
            <button onclick="acknowledgeVlmNotice()" id="btnAckVlm" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-700 cursor-pointer flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span>
              Acknowledge
            </button>
          </div>
        </div>
      </div>

      <!-- PANEL: ACTIVE EXAMS -->
      <div id="panelExams" class="fade-up">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Assignments Card -->
            <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-4 flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 flex-shrink-0">
                <span class="material-symbols-rounded text-blue-400 text-lg">assignment</span>
              </div>
              <div class="flex-grow">
                <p class="font-bold text-slate-200 text-sm mb-1">Assignments</p>
                <div class="flex justify-between items-center text-sm gap-2">
                  <span class="text-slate-400">Active: <strong class="text-white text-base" id="statActiveAssign">0</strong></span>
                  <span class="text-slate-400">Done: <strong class="text-teal-400 text-base" id="statAssignDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Written Tests Card -->
            <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-4 flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center border border-amber-500/20 flex-shrink-0">
                <span class="material-symbols-rounded text-amber-400 text-lg">edit_document</span>
              </div>
              <div class="flex-grow">
                <p class="font-bold text-slate-200 text-sm mb-1">Written Tests</p>
                <div class="flex justify-between items-center text-sm gap-2">
                  <span class="text-slate-400">Active: <strong class="text-white text-base" id="statWrittenTests">0</strong></span>
                  <span class="text-slate-400">Done: <strong class="text-indigo-400 text-base" id="statWrittenTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- MCQ Tests Card -->
            <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-4 flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 flex-shrink-0">
                <span class="material-symbols-rounded text-purple-400 text-lg">quiz</span>
              </div>
              <div class="flex-grow">
                <p class="font-bold text-slate-200 text-sm mb-1">MCQ Tests</p>
                <div class="flex justify-between items-center text-sm gap-2">
                  <span class="text-slate-400">Active: <strong class="text-white text-base" id="statActiveTests">0</strong></span>
                  <span class="text-slate-400">Done: <strong class="text-emerald-400 text-base" id="statTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Overall Tasks Card -->
            <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-4 flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20 flex-shrink-0">
                <span class="material-symbols-rounded text-rose-400 text-lg">pending_actions</span>
              </div>
              <div class="flex-grow">
                <p class="font-bold text-slate-200 text-sm mb-1">Overall Tasks</p>
                <div class="flex justify-between items-center text-sm gap-2">
                  <span class="text-slate-400">Total Active: <strong class="text-rose-400 text-base" id="statPendingTotal">0</strong></span>
                  <span class="text-slate-400">Total Done: <strong class="text-teal-400 text-base" id="statOverallDone">0</strong></span>
                </div>
              </div>
            </div>
        </div>

        <div class="mb-6 bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
          <h3 class="font-black text-slate-200 uppercase tracking-widest mb-1 text-base">My Pending Work</h3>
          <p class="text-slate-400 text-sm">View your active assignments, upcoming tests, and deadlines.</p>
          
          <div id="pendingGridContainer" class="grid grid-cols-1 gap-6 mt-6">
            <!-- Surveys Container (Spans full width above columns if active) -->
            <div id="studentSurveysContainer" class="col-span-full hidden">
              <!-- Rendered dynamically inside -->
            </div>

            <!-- Column 1: MCQ Tests Section -->
            <div id="mcqTestsSection" class="hidden">
              <h4 class="font-black text-purple-400 uppercase tracking-wider text-sm mb-3 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-lg">quiz</span> Online MCQ Tests
              </h4>
              <div id="studentActiveTestsList" class="flex flex-col gap-3">
                <div class="py-12 text-center text-slate-500 font-bold animate-pulse text-sm">Loading active tests...</div>
              </div>
            </div>
            
            <!-- Column 2: Assignments & Written Tests Section -->
            <div id="assignmentsSection" class="hidden">
              <h4 class="font-black text-blue-400 uppercase tracking-wider text-sm mb-3 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-lg">assignment</span> Assignments & Written Tests
              </h4>
              <div id="studentActiveTasksContainer" class="flex flex-col gap-3">
                <div class="py-12 text-center text-slate-500 font-bold animate-pulse text-sm">Loading active tasks...</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Subject Class Progress Cards (Two Column) -->
        <div class="mb-6 bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
          <h3 class="font-black text-slate-200 uppercase tracking-widest mb-1 text-base">Semester Subject Progress</h3>
          <p id="subjectProgressSubtitle" class="text-slate-400 text-sm mb-6">Track total completed sessions and syllabus coverage based on classroom logs.</p>
          
          <div id="subjectProgressGrid" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
            <div class="col-span-full py-8 text-center text-slate-500 font-bold animate-pulse text-sm">Loading syllabus coverage and progress...</div>
          </div>
        </div>
      </div>

      <!-- PANEL: ACADEMIC MARKS -->
      <div id="panelMarks" class="hidden fade-up space-y-6">
        
        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 shadow-xl items-stretch">
          <!-- Circular GPA Gauge -->
          <div class="flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.15)] rounded-xl h-full">
            <p class="font-black text-slate-300 uppercase tracking-widest text-sm mb-3">Cumulative GPA</p>
            <div class="relative w-32 h-32 flex items-center justify-center my-auto">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="rgba(15, 23, 42, 0.8)" stroke-width="8" fill="transparent" />
                <circle id="cgpaGaugeProgress" cx="50" cy="50" r="40" stroke="url(#gpaGradient)" stroke-width="8" fill="transparent"
                        stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                <defs>
                  <linearGradient id="gpaGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#22d3ee" />
                    <stop offset="100%" stop-color="#0284c7" />
                  </linearGradient>
                </defs>
              </svg>
              <div class="absolute flex flex-col items-center leading-none">
                <span id="overallCgpa" class="text-3xl font-black text-cyan-400">0.00</span>
                <div class="w-10 h-[2px] bg-slate-800 my-1.5"></div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">10.0</span>
              </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-400">
              Class: <span id="diplomaClassification" class="text-cyan-400 font-bold">--</span>
            </div>
          </div>

          <!-- Circular Activity Points Gauge -->
          <div id="activityPointsCard" class="flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-purple-500/30 shadow-[0_0_20px_rgba(168,85,247,0.15)] rounded-xl h-full">
            <p class="font-black text-slate-300 uppercase tracking-widest text-sm mb-3">Activity Points</p>
            <div class="relative w-32 h-32 flex items-center justify-center my-auto">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="rgba(15, 23, 42, 0.8)" stroke-width="8" fill="transparent" />
                <circle id="activityGaugeProgress" cx="50" cy="50" r="40" stroke="url(#activityGradient)" stroke-width="8" fill="transparent"
                        stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                <defs>
                  <linearGradient id="activityGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#a855f7" />
                    <stop offset="100%" stop-color="#6366f1" />
                  </linearGradient>
                </defs>
              </svg>
              <div class="absolute flex flex-col items-center leading-none">
                <span id="overallActivityPoints" class="text-3xl font-black text-purple-400">0</span>
                <div class="w-10 h-[2px] bg-slate-800 my-1.5"></div>
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">160</span>
              </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-400">
              Min Required: <span class="text-emerald-400">60 Points</span>
            </div>
          </div>

          <!-- Circular Attendance Gauge -->
          <div class="flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.15)] rounded-xl h-full">
            <p class="font-black text-slate-300 uppercase tracking-widest text-sm mb-3">Overall Attendance</p>
            <div class="relative w-32 h-32 flex items-center justify-center my-auto">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="rgba(15, 23, 42, 0.8)" stroke-width="8" fill="transparent" />
                <circle id="attendanceGaugeProgress" cx="50" cy="50" r="40" stroke="url(#attendanceGradient)" stroke-width="8" fill="transparent"
                        stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                <defs>
                  <linearGradient id="attendanceGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#10b981" />
                    <stop offset="100%" stop-color="#059669" />
                  </linearGradient>
                </defs>
              </svg>
              <div class="absolute flex flex-col items-center leading-none">
                <span id="overallAttendancePct" class="text-3xl font-black text-emerald-400">0%</span>
              </div>
            </div>
            <div class="mt-4 text-sm font-semibold text-slate-400">
              Present Hours: <span id="attendanceHoursDetail" class="text-slate-200">0 / 0</span>
            </div>
          </div>

          <!-- Line Chart for Trends -->
          <div class="flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.15)] rounded-xl h-full">
            <p class="font-black text-slate-300 uppercase tracking-wider text-sm mb-2">SGPA Trend</p>
            <div class="w-full h-32 flex items-center justify-center my-auto">
              <canvas id="cgpaChart"></canvas>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-400">
              Semester-wise SGPA
            </div>
          </div>
        </div>

        <!-- Semester Switcher -->
        <div class="flex justify-between items-center bg-slate-900/50 p-2 rounded-xl border border-slate-800">
          <div id="semesterTabsContainer" class="flex gap-1 overflow-x-auto scrollbar-hidden">
             <!-- Rendered dynamically -->
          </div>
        </div>

        <!-- Academic Report Content (God Table) -->
        <div id="academicReportContent" class="space-y-4 pb-12 overflow-x-auto">
           <div class="text-slate-500 italic text-center p-4 text-[10px] text-xs">Loading stats...</div>
        </div>
      </div>

      <!-- PANEL: MY PROFILE -->
      <div id="panelProfile" class="hidden fade-up">
        <div class="max-w-2xl mx-auto space-y-6">

          <!-- Profile Header Card -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-5">
            <div class="relative group">
              <div id="studentAvatarWrapper" class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center shadow-lg relative">
                @if(session('userPhoto'))
                  <img id="studentProfileImg" src="{{ session('userPhoto') }}" class="w-full h-full object-cover">
                @else
                  <div id="studentProfilePlaceholder" class="w-full h-full bg-gradient-to-br from-blue-600 to-sky-700 flex items-center justify-center font-black text-2xl text-white">
                    {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
                  </div>
                @endif
              </div>
              <label for="photoUploadInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-2xl text-white text-sm font-bold text-center gap-1 p-1">
                <span class="material-symbols-rounded text-base">photo_camera</span>
                <span>Change</span>
              </label>
              <input type="file" id="photoUploadInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(event)">
            </div>
            <div class="text-center sm:text-left">
              <h3 class="font-black text-white text-sm">{{ session('userName') }}</h3>
              <p class="text-sm text-slate-400 font-semibold mt-0.5">{{ session('userId') }} &bull; {{ session('userBranch') }}</p>
              <span class="mt-2 inline-block px-2.5 py-0.5 rounded-full font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20 text-sm">Student</span>
              <div id="photoUploadStatus" class="text-sm font-bold mt-2 hidden"></div>
            </div>
          </div>

          <!-- Info Grid -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6">
            <h3 class="font-black text-slate-300 uppercase tracking-wider border-b border-slate-800/60 pb-3 mb-4 text-[10px] text-xs">Academic Information</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-slate-400 font-black uppercase tracking-wider">Register Number</dt>
                <dd class="font-mono font-bold text-white mt-1">{{ session('userId') }}</dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-slate-400 font-black uppercase tracking-wider">Branch</dt>
                <dd class="font-bold text-white mt-1">{{ session('userBranch') }}</dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-slate-400 font-black uppercase tracking-wider">Classroom ID</dt>
                <dd class="font-mono font-bold text-white mt-1">
                  {{ session('classroomId', '-') }}
                  @if(str_contains(session('classroomId', ''), '_LET'))
                    <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-[10px] px-2 py-0.5 rounded ml-2 uppercase">Lateral Entry</span>
                  @endif
                </dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-slate-400 font-black uppercase tracking-wider">Role</dt>
                <dd class="font-bold text-teal-400 mt-1">Student</dd>
              </div>
            </dl>
          </div>

          <!-- Email Address Update Card -->
          <div class="bg-slate-950/30 border border-blue-500/20 rounded-2xl p-6">
            <div class="flex items-center gap-2 border-b border-slate-800/60 pb-3 mb-4">
              <span class="material-symbols-rounded text-blue-400 text-xs">mail</span>
              <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider">Email Address</h3>
            </div>
            <?php $studentEmail = session('userEmail', ''); ?>
            <p class="text-xs text-slate-400 mb-4 font-medium">Keep your primary contact email address up to date for official notices and password recovery.</p>
            <div class="flex gap-3">
              <input type="email" id="studentEmailInput" value="{{ str_ends_with($studentEmail, '@carmelpoly.in') ? '' : $studentEmail }}" placeholder="e.g. student@gmail.com" class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 outline-none">
              <button onclick="updateStudentEmail()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-xs transition-premium flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-xs">save</span> Update Email
              </button>
            </div>
            <div id="emailAlert" class="hidden p-3 rounded-xl text-xs font-bold border mt-3"></div>
          </div>

          <!-- SBTE Register Number Card -->
          <div class="bg-slate-950/30 border border-amber-500/20 rounded-2xl p-6">
            <div class="flex items-center gap-2 border-b border-slate-800/60 pb-3 mb-4">
              <span class="material-symbols-rounded text-amber-400 text-xs">badge</span>
              <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider">SBTE Examination Register No</h3>
            </div>
            <?php
              $sbteNo = session('sbteRegNo', '');
            ?>
            @if($sbteNo)
              <div class="flex items-center justify-between bg-slate-900/60 rounded-xl p-4 border border-amber-500/20">
                <div>
                  <p class="text-xs text-amber-400 font-bold uppercase tracking-wider mb-1">Confirmed</p>
                  <p class="font-mono font-black text-white text-xs">{{ $sbteNo }}</p>
                </div>
                <span class="material-symbols-rounded text-amber-400 text-base">verified</span>
              </div>
              <p class="text-xs text-slate-500 mt-3 font-semibold">Contact your tutor or HOD if you need to correct this number.</p>
            @else
              <p class="text-xs text-slate-400 mb-4 font-medium">Your SBTE Exam Register Number is assigned after commencement of class and SBTE registration. Enter it here once you receive it.</p>
              <div class="flex gap-3">
                <input type="text" id="sbteRegNoInput" placeholder="e.g. 25EL001" class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white font-mono focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 outline-none">
                <button onclick="updateSbteRegNo()" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-bold text-xs transition-premium flex items-center gap-1.5 cursor-pointer">
                  <span class="material-symbols-rounded text-xs">save</span> Save
                </button>
              </div>
              <div id="sbteAlert" class="hidden p-3 rounded-xl text-xs font-bold border mt-3"></div>
            @endif
          </div>

          <!-- Change Password Section -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6">
            <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider border-b border-slate-800/60 pb-3 mb-4">Change Password</h3>
            <div class="space-y-3">
              <div>
                <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Current Password</label>
                <input type="password" id="oldPwd" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 outline-none" placeholder="Enter current password">
              </div>
              <div>
                <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
                <input type="password" id="newPwd" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 outline-none" placeholder="At least 6 characters">
              </div>
              <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>
              <button onclick="changePassword()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Update Password</button>
            </div>
          </div>

        </div>
      </div>
      <!-- END PANEL: MY PROFILE -->

        @php
          $isLet = session('userAdmissionType') === 'LET';
          $activityGoal = $isLet ? 40 : 60;
        @endphp
        <!-- PANEL: ACTIVITY POINTS -->
        <div id="panelActivity" class="hidden fade-up space-y-6">
          <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-b border-slate-800/60 pb-6">
              <div class="md:col-span-2 space-y-3">
                <div>
                  <h3 class="text-xs font-black text-slate-200">Activity Points Goal Tracker</h3>
                  <p class="text-xs text-slate-400 mt-0.5">Track your progress towards the {{ $activityGoal }}-point diploma requirement.</p>
                </div>
                <div class="relative w-full h-3 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                  <div id="activityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-red-500 to-amber-500 transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-xs font-bold text-slate-500">
                  <span>0</span>
                  <span>Goal: {{ $activityGoal }}</span>
                </div>
              </div>
              
              <div class="bg-slate-950/40 rounded-xl p-4 border border-slate-800/60 flex flex-col justify-between">
                <div class="text-right">
                  <span class="block text-xs text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                  <span class="text-base font-black text-amber-400" id="verifiedActivityTotal">0</span>
                </div>
                <div class="mt-3 border-t border-slate-800/40 pt-2" id="activitySplitList">
                  <!-- Split loads here -->
                </div>
              </div>
            </div>

          <div class="mb-4">
            <h3 class="text-xs font-bold text-slate-200">Submit New Claim</h3>
          </div>
          
          <form id="activityClaimForm" onsubmit="submitActivityClaim(event)" class="bg-slate-950/40 border border-slate-800/40 p-4 rounded-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="lg:col-span-1">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
              <select name="semester" required class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="1">Sem 1</option>
                <option value="2">Sem 2</option>
                <option value="3">Sem 3</option>
                <option value="4">Sem 4</option>
                <option value="5">Sem 5</option>
                <option value="6">Sem 6</option>
              </select>
            </div>
            <div class="lg:col-span-1">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Segment</label>
              <select name="activity_segment" required class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="">Select...</option>
                <option value="NCC">NCC</option>
                <option value="NSS">NSS</option>
                <option value="Sports & Games">Sports & Games</option>
                <option value="Cultural Activities">Cultural Activities</option>
                <option value="Professional Self Initiatives">Prof. Self Initiatives</option>
                <option value="Entrepreneurship and Innovation">Entrepreneurship & Innovation</option>
                <option value="Leadership & Management">Leadership & Management</option>
                <option value="Disaster Management">Disaster Management</option>
              </select>
            </div>
            <div class="lg:col-span-1">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Activity Name</label>
              <input type="text" name="activity_name" required placeholder="e.g. First Prize in Arts" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
            </div>
            <div class="lg:col-span-1">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Level</label>
              <select name="level" required class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="">Select Level...</option>
                <option value="Level I - College">Level I - College</option>
                <option value="Level II - Zonal">Level II - Zonal</option>
                <option value="Level III - State/Univ">Level III - State/Univ</option>
                <option value="Level IV - National">Level IV - National</option>
                <option value="Level V - International">Level V - International</option>
              </select>
            </div>
            <div class="lg:col-span-1">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Points Claimed</label>
              <input type="number" name="points_claimed" required min="1" max="50" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
            </div>
            <div class="lg:col-span-1 flex flex-col justify-end">
              <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold text-xs transition-premium shadow-lg shadow-blue-500/20">Submit Claim</button>
            </div>
            <div class="lg:col-span-6">
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Document Evidence (Describe what you are submitting to Tutor)</label>
              <input type="text" name="document_reference" placeholder="e.g. Hardcopy of State Arts Certificate" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
            </div>
          </form>

          <div class="overflow-x-auto rounded-xl border border-slate-800/40">
            <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
              <thead>
                <tr class="bg-slate-950/80 border-b border-slate-800/60 text-slate-400 font-bold uppercase tracking-wider text-xs">
                  <th class="p-3">Submitted On</th>
                  <th class="p-3">Segment</th>
                  <th class="p-3">Activity</th>
                  <th class="p-3">Level</th>
                  <th class="p-3">Evidence</th>
                  <th class="p-3 text-center">Claimed</th>
                  <th class="p-3 text-center">Awarded</th>
                  <th class="p-3 text-right">Status</th>
                </tr>
              </thead>
              <tbody id="activityClaimsTableBody" class="divide-y divide-slate-800/40">
                <!-- Claims will be loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- END PANEL: ACTIVITY POINTS -->

      <!-- PANEL: MY SEMINAR -->
      <div id="panelSeminar" class="hidden space-y-5 fade-up">
        <!-- Inline toast -->
        <div id="seminarToast" class="hidden max-w-3xl mx-auto px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2"></div>

        <div class="max-w-3xl mx-auto space-y-5">

          <!-- Status banner (shown when registered) -->
          <div id="seminarStatusBanner" class="hidden bg-emerald-950/60 border border-emerald-600/30 rounded-2xl p-5">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-start gap-3">
                <span class="material-symbols-rounded text-emerald-400 text-2xl mt-0.5">verified</span>
                <div>
                  <p id="semStatusBadgeTitle" class="text-sm font-black text-emerald-300">Seminar Registered</p>
                  <p id="semStatusTopic" class="text-white font-extrabold text-base mt-0.5">-</p>
                  <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                    <span class="text-xs text-slate-400">Guide: <span id="semStatusGuide" class="text-slate-200 font-bold">-</span></span>
                    <span class="text-xs text-slate-400">Date: <span id="semStatusDate" class="text-slate-200 font-bold">-</span></span>
                    <span class="text-xs text-slate-400">Avg Score: <span id="semStatusScore" class="text-teal-400 font-black">- / 75</span></span>
                    <span class="text-xs text-slate-400">Assessments: <span id="semStatusAssessments" class="text-slate-200 font-bold">0</span></span>
                  </div>
                </div>
              </div>
              <button onclick="showSeminarEditForm()" class="shrink-0 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-1 transition-premium cursor-pointer">
                <span class="material-symbols-rounded text-sm">edit</span> Edit
              </button>
            </div>
          </div>

          <!-- Registration / Edit form -->
          <div id="seminarFormCard" class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <h3 id="semFormTitle" class="font-black text-slate-200 text-base flex items-center gap-2">
              <span class="material-symbols-rounded text-blue-400">co_present</span> Register Seminar Details
            </h3>
            <p class="text-xs text-slate-400 leading-relaxed">Fill in your seminar topic, proposed date, and assign a faculty guide from your department. You can update this later before the presentation date.</p>

            <form id="seminarRegistrationForm" onsubmit="submitSeminarRegistration(event)" class="space-y-4 pt-2">
              <input type="hidden" id="semRegSubject">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Seminar Presentation Topic</label>
                <input type="text" id="semRegTopic" required placeholder="e.g. Artificial Intelligence in Health Diagnostics"
                  class="w-full bg-slate-900 border border-slate-700/60 rounded-xl px-3.5 py-3 text-sm text-white focus:border-blue-500 outline-none transition-colors">
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Proposed Presentation Date</label>
                  <input type="date" id="semRegDate" required
                    class="w-full bg-slate-900 border border-slate-700/60 rounded-xl px-3.5 py-3 text-sm text-white focus:border-blue-500 outline-none transition-colors">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Seminar Guide / Faculty</label>
                  <select id="semRegGuide" required
                    class="w-full bg-slate-900 border border-slate-700/60 rounded-xl px-3.5 py-3 text-sm text-white focus:border-blue-500 outline-none transition-colors">
                    <option value="">Loading guides...</option>
                  </select>
                </div>
              </div>
              <div class="pt-4 border-t border-slate-900 flex justify-between items-center gap-3">
                <button type="button" id="semCancelEditBtn" onclick="cancelSeminarEdit()" class="hidden px-5 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 rounded-xl font-bold text-sm transition-premium cursor-pointer">
                  Cancel
                </button>
                <button type="submit" id="semSubmitBtn" class="ml-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm transition-premium shadow-lg shadow-blue-500/20 flex items-center gap-2 cursor-pointer">
                  <span class="material-symbols-rounded text-base">save</span> Save Registration
                </button>
              </div>
            </form>
          </div>

          <!-- No seminar subject alert (shown if HOD hasn't assigned one) -->
          <div id="seminarNoSubjectAlert" class="hidden bg-amber-950/40 border border-amber-600/30 rounded-2xl p-5 flex items-start gap-3">
            <span class="material-symbols-rounded text-amber-400 text-xl mt-0.5">warning</span>
            <div>
              <p class="text-sm font-bold text-amber-300">No Seminar Subject Assigned</p>
              <p class="text-xs text-slate-400 mt-1">Your department HOD has not yet assigned a Seminar subject for your batch/semester. Please contact your HOD or tutor to have it added.</p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>

  <script>
    function toggleMobileSidebar() {
      const sidebar = document.getElementById('sidebarMenu');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
    }

    function switchPanel(panelId) {
      // Close mobile sidebar if open (only on mobile)
      const sidebar = document.getElementById('sidebarMenu');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (sidebar && window.innerWidth < 768 && !sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }

      // Sync browser history state safely
      window.history.replaceState({}, '', '?tab=' + panelId);

      const panels = ['exams', 'marks', 'profile', 'activity', 'seminar'];
      
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        if (id === panelId) {
          if (el) { el.classList.remove('hidden'); el.classList.add('fade-up'); }
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (el) el.classList.add('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
        }
      });

      const titles = { exams: 'Works To Do', marks: 'Academic Stats', profile: 'My Profile', activity: 'Activity Points', seminar: 'My Seminar' };
      const subtitles = { 
        exams: 'Manage your pending assignments and active tests.', 
        marks: 'Your semester-wise academic progress.', 
        profile: 'Your personal and academic details.',
        activity: 'Track and claim your extracurricular points.',
        seminar: 'Register and view your seminar topics details.'
      };
      if (titles[panelId]) document.getElementById('panelTitle').innerText = titles[panelId];
      if (subtitles[panelId]) document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'activity') {
        loadActivityPoints();
      } else if (panelId === 'seminar') {
        loadSeminarRegistration();
      }
    }

      document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab && ['exams', 'marks', 'profile', 'activity', 'seminar'].includes(tab)) {
          switchPanel(tab);
        }
        loadStudentTests();
        if (!academicReportLoaded) loadAcademicReport();
      });

    let academicReportLoaded = false;
    let mentoringLoaded = false;
    let academicData = null;
    let currentActiveSem = 1;
    let cgpaChartInstance = null;
    
    let currentTaskStats = {
       assignments_active: 0,
       assignments_submitted: 0,
       written_tests_active: 0,
       written_tests_submitted: 0,
       online_tests_active: 0,
       online_tests_submitted: 0
    };

    function updateStatsHeader(acStats, tStats) {
       if (acStats) {
          currentTaskStats.assignments_active = acStats.assignments_active || 0;
          currentTaskStats.assignments_submitted = acStats.assignments_submitted || 0;
          currentTaskStats.written_tests_active = acStats.written_tests_active || 0;
          currentTaskStats.written_tests_submitted = acStats.written_tests_submitted || 0;
       }
       if (tStats) {
          currentTaskStats.online_tests_active = tStats.online_tests_active || 0;
          currentTaskStats.online_tests_submitted = tStats.online_tests_submitted || 0;
       }
        document.getElementById('statActiveTests').innerText = currentTaskStats.online_tests_active;
        document.getElementById('statActiveAssign').innerText = currentTaskStats.assignments_active;
        document.getElementById('statWrittenTests').innerText = currentTaskStats.written_tests_active;
        document.getElementById('statTestsDone').innerText = currentTaskStats.online_tests_submitted;
        document.getElementById('statAssignDone').innerText = currentTaskStats.assignments_submitted;
        document.getElementById('statWrittenTestsDone').innerText = currentTaskStats.written_tests_submitted;
        document.getElementById('statPendingTotal').innerText = currentTaskStats.online_tests_active + currentTaskStats.assignments_active + currentTaskStats.written_tests_active;
        document.getElementById('statOverallDone').innerText = currentTaskStats.online_tests_submitted + currentTaskStats.assignments_submitted + currentTaskStats.written_tests_submitted;
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            const cgpaVal = parseFloat(overall.cgpa) || 0;
            const activityPointsVal = parseInt(overall.activity_points) || 0;

            document.getElementById('overallCgpa').innerText = cgpaVal > 0 ? cgpaVal.toFixed(2) : '0.00';
            document.getElementById('diplomaClassification').innerText = overall.classification || '--';

            // Update GPA Gauge offset (radius r = 40, circumference = 251.2)
            const cgpaPercent = Math.min(1.0, Math.max(0.0, cgpaVal / 10.0));
            const cgpaOffset = 251.2 - (cgpaPercent * 251.2);
            document.getElementById('cgpaGaugeProgress').style.strokeDashoffset = cgpaOffset;

            // Update Activity Points Gauge offset & colors dynamically (radius r = 40, circumference = 251.2)
            const activityPercent = Math.min(1.0, Math.max(0.0, activityPointsVal / 160.0));
            const activityOffset = 251.2 - (activityPercent * 251.2);
            const actGauge = document.getElementById('activityGaugeProgress');
            const actText = document.getElementById('overallActivityPoints');
            const cardEl = document.getElementById('activityPointsCard');
            
            actGauge.style.strokeDashoffset = activityOffset;
            actText.innerText = activityPointsVal;

            let strokeColor = '#ef4444'; // critical red
            let textClass = 'text-3xl font-black text-rose-500';
            
            if (activityPointsVal >= 60) {
              strokeColor = '#22c55e'; // green
              textClass = 'text-3xl font-black text-emerald-400';
              if (cardEl) {
                cardEl.className = "flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.15)] rounded-xl h-full";
              }
            } else if (activityPointsVal >= 30) {
              strokeColor = '#f97316'; // orange/golden
              textClass = 'text-3xl font-black text-orange-400';
              if (cardEl) {
                cardEl.className = "flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-orange-500/30 shadow-[0_0_20px_rgba(249,115,22,0.15)] rounded-xl h-full";
              }
            } else {
              strokeColor = '#ef4444'; // critical red
              textClass = 'text-3xl font-black text-rose-500';
              if (cardEl) {
                cardEl.className = "flex flex-col justify-between items-center text-center p-5 bg-slate-950/80 border border-rose-500/30 shadow-[0_0_20px_rgba(244,63,94,0.15)] rounded-xl h-full";
              }
            }
            actGauge.setAttribute('stroke', strokeColor);
            actText.className = textClass;

            // Update Semester Attendance Gauge offset (radius r = 40, circumference = 251.2)
            const attendance = data.current_sem_attendance || { total_hours: 0, present_hours: 0, percentage: 0 };
            const attendancePct = (attendance.total_hours > 0) ? (parseFloat(attendance.percentage) || 0) : 0;
            const attGauge = document.getElementById('attendanceGaugeProgress');
            const attText = document.getElementById('overallAttendancePct');
            
            document.getElementById('overallAttendancePct').innerText = (attendance.total_hours > 0 ? attendancePct + '%' : '0%');
            document.getElementById('attendanceHoursDetail').innerText = `${attendance.present_hours} / ${attendance.total_hours}`;
            
            if (attendance.total_hours === 0) {
              attText.className = 'text-3xl font-black text-slate-400';
              if (attGauge) attGauge.setAttribute('stroke', '#64748b');
            } else if (attendancePct >= 75) {
              attText.className = 'text-3xl font-black text-emerald-400';
              if (attGauge) attGauge.setAttribute('stroke', 'url(#attGradient)');
            } else if (attendancePct >= 65) {
              attText.className = 'text-3xl font-black text-amber-400';
              if (attGauge) attGauge.setAttribute('stroke', '#f59e0b');
            } else {
              attText.className = 'text-3xl font-black text-rose-400';
              if (attGauge) attGauge.setAttribute('stroke', '#f43f5e');
            }

            const attendancePercent = Math.min(1.0, Math.max(0.0, attendancePct / 100.0));
            const attendanceOffset = 251.2 - (attendancePercent * 251.2);
            if (attGauge) attGauge.style.strokeDashoffset = attendanceOffset;

            // Always update sem in header
            document.getElementById('headerSemValue').innerText = 'Sem ' + (overall.current_semester || '?');
            currentActiveSem = overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || [], data.active_surveys || []);
            renderCgpaChart(data.semesters || []);
            renderSemesterTabs(data.semesters || []);
            renderGodTable(currentActiveSem);
            renderSubjectProgress(data.subject_progress || []);
          } else {
            // API returned an error — show it gracefully
            const errMsg = data.message || 'Failed to load academic data.';
            console.error('Academic report error:', errMsg);
            const c = document.getElementById('studentActiveTasksContainer');
            if (c) c.innerHTML = `<div class="col-span-full py-8 text-center text-rose-400 font-bold text-sm">⚠️ ${errMsg}</div>`;
          }
        })
        .catch(err => {
          console.error('Network error loading academic report:', err);
          const c = document.getElementById('studentActiveTasksContainer');
          if (c) c.innerHTML = `<div class="col-span-full py-8 text-center text-rose-400 font-bold text-sm">⚠️ Network error — please refresh.</div>`;
        });
    }


    function adjustPendingGridColumns() {
      const mcqActive = (document.getElementById('mcqTestsSection') && !document.getElementById('mcqTestsSection').classList.contains('hidden'));
      const assignmentsActive = (document.getElementById('assignmentsSection') && !document.getElementById('assignmentsSection').classList.contains('hidden'));
      const pendingGrid = document.getElementById('pendingGridContainer');
      
      if (mcqActive && assignmentsActive) {
        pendingGrid.classList.remove('grid-cols-1');
        pendingGrid.classList.add('lg:grid-cols-2');
      } else {
        pendingGrid.classList.remove('lg:grid-cols-2');
        pendingGrid.classList.add('grid-cols-1');
      }
    }

    function renderSubjectProgress(progressList) {
      const container = document.getElementById('subjectProgressGrid');
      const subtitle = document.getElementById('subjectProgressSubtitle');
      if (!container) return;

      if (!progressList || progressList.length === 0) {
        container.innerHTML = `
          <div class="col-span-full py-8 text-center text-slate-500 font-bold text-sm">
            No current semester subjects or progress logs found.
          </div>
        `;
        return;
      }

      // Update Card Header Subtitle with Batch, Year, Semester
      const classroomId = "{{ session('classroomId', '-') }}";
      const branch = "{{ session('userBranch', '-') }}";
      const semester = currentActiveSem ? 'Semester ' + currentActiveSem : '...';
      if (subtitle) {
        subtitle.innerText = `${branch} • Batch: ${classroomId} • ${semester}`;
      }

      // Split into two columns: Left half (Col 1) and Right half (Col 2)
      const mid = Math.ceil(progressList.length / 2);
      const col1 = progressList.slice(0, mid);
      const col2 = progressList.slice(mid);

      const renderListHtml = (list) => list.map((item, index) => `
        <div class="py-2 ${index > 0 ? 'border-t border-slate-800/40' : ''}">
          <div class="flex justify-between items-start gap-2 flex-wrap mb-1">
            <span class="font-extrabold text-slate-100 text-sm">${item.subject_code} - ${item.subject_name}</span>
            <span class="text-slate-400 text-sm font-semibold flex items-center gap-1">
              <span class="material-symbols-rounded text-sm">person</span>
              ${item.staff_name}
            </span>
          </div>

          <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: ${item.percentage}%"></div>
          </div>

          <div class="flex justify-between text-sm font-semibold text-slate-500 mt-1">
            <span>Sessions: <strong class="text-slate-300">${item.completed_sessions}</strong> / ${item.total_sessions} taught</span>
            <span class="text-teal-400">${item.percentage}%</span>
          </div>
        </div>
      `).join('');

      container.innerHTML = `
        <div class="flex flex-col">
          ${renderListHtml(col1)}
        </div>
        <div class="flex flex-col border-t md:border-t-0 md:border-l border-slate-800/60 pt-3 md:pt-0 md:pl-6">
          ${renderListHtml(col2)}
        </div>
      `;
    }

    function renderActiveTasks(tasks, surveys) {
      const tasksContainer = document.getElementById('studentActiveTasksContainer');
      const surveysContainer = document.getElementById('studentSurveysContainer');
      const assignmentsSection = document.getElementById('assignmentsSection');

      // 1. Render Active Surveys
      if (surveys && surveys.length > 0) {
        let surveysHtml = `<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 col-span-full mb-4">`;
        surveys.forEach((srv) => {
          const isExit = srv.type === 'Course Exit';
          const title = isExit ? 'Course Exit Feedback Survey Active' : 'Mid-Semester Feedback Survey Active';
          const link = isExit ? `/student/course-exit/${srv.survey_id}` : `/student/survey/${srv.survey_id}`;
          const themeBg = isExit ? 'bg-teal-950/20 border-teal-500/30' : 'bg-amber-950/20 border-amber-500/30';
          const themeText = isExit ? 'text-teal-300' : 'text-amber-300';
          const themeBtn = isExit ? 'bg-teal-500/15 hover:bg-teal-500/30 border-teal-500/40 text-teal-300' : 'bg-amber-500/15 hover:bg-amber-500/30 border-amber-500/40 text-amber-300';
          const themeIcon = isExit ? 'text-teal-400' : 'text-amber-400';
          
          surveysHtml += `
            <div class="${themeBg} border-2 rounded-xl p-3 shadow-lg relative overflow-hidden flex flex-col justify-between">
              <div class="absolute top-0 right-0 h-12 w-12 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
              <div>
                <div class="flex items-start gap-2.5">
                  <span class="material-symbols-rounded ${themeIcon} text-base mt-0.5 animate-bounce">rate_review</span>
                  <div class="flex-grow leading-tight">
                    <h4 class="font-extrabold text-sm ${themeText} uppercase tracking-wide">${title}</h4>
                    <p class="text-sm font-bold text-slate-300 mt-0.5">${srv.subject_code} — ${srv.subject_name}</p>
                    <p class="text-sm text-slate-400 mt-1 leading-normal">Please complete this feedback form to help calculate Course Outcome (CO) attainment parameters.</p>
                  </div>
                </div>
              </div>
              <div class="mt-2 pt-2 border-t border-slate-800/40 flex justify-end">
                <a href="${link}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 ${themeBtn} rounded-lg text-sm font-bold transition-premium no-underline cursor-pointer">
                  <span class="material-symbols-rounded text-base">rate_review</span> Take Survey Feedback <span class="material-symbols-rounded text-sm">arrow_forward</span>
                </a>
              </div>
            </div>
          `;
        });
        surveysHtml += `</div>`;
        surveysContainer.innerHTML = surveysHtml;
        surveysContainer.classList.remove('hidden');
      } else {
        surveysContainer.innerHTML = '';
        surveysContainer.classList.add('hidden');
      }

      // 2. Render Active Tasks (Assignments & Written Tests)
      if (tasks && tasks.length > 0) {
        let tasksHtml = '';
        tasks.forEach((t, index) => {
          const isExp = t.status === 'Expired' || t.status === 'Completed';
          const stCol = isExp ? 'text-rose-400 bg-rose-500/10 border-rose-500/20' : 'text-teal-400 bg-teal-500/10 border-teal-500/20';
          const icon = t.type === 'Assignment' ? 'assignment' : 'edit_document';

          let qHtml = '';
          if (t.questions && t.questions.length > 0) {
            qHtml = `<div class="mt-4 pt-4 border-t border-slate-800 hidden" id="taskQ_${index}">
              <h4 class="text-sm uppercase font-black text-slate-400 mb-2">Assignment Questions</h4>
              <ul class="space-y-2 text-sm text-slate-300 font-medium list-disc pl-4">
                ${t.questions.map(q => `<li>${q}</li>`).join('')}
              </ul>
            </div>`;
          }

          let actionBtn = '';
          if (t.type === 'Assignment' && !isExp) {
            actionBtn = `<button onclick="markManualTaskSubmitted('${t.subject_code}', '${t.co_tag}', 'Assignment')" class="mt-3 w-full py-2 bg-blue-600/80 hover:bg-blue-500 text-white rounded font-bold text-sm transition-premium">Mark as Submitted</button>`;
          }

          tasksHtml += `
            <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl overflow-hidden mb-1">
            <!-- Collapsible Header -->
            <div onclick="document.getElementById('co_task_${index}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_task_${index}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                 class="px-4 py-3.5 bg-slate-950/40 hover:bg-slate-950/70 border-b border-slate-800/60 flex justify-between items-center cursor-pointer transition-premium">
              <div class="flex items-center gap-3">
                <span class="material-symbols-rounded text-blue-400 text-base">${icon}</span>
                <div>
                  <h4 class="font-bold text-sm text-slate-200 uppercase">${t.type} - ${t.co_tag}</h4>
                  <p class="text-sm font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject}</p>
                </div>
              </div>
              <span class="material-symbols-rounded text-slate-500 text-sm arrow-icon">expand_more</span>
            </div>
            <!-- Collapsible Content -->
            <div id="co_task_${index}" class="hidden p-4 bg-slate-950/10 border-t border-slate-800/40">
              <div class="flex items-center gap-2 mb-3">
                  <span class="px-2 py-0.5 rounded text-sm font-black uppercase tracking-widest ${stCol}">${t.status}</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-slate-400 font-semibold">
                <div class="space-y-1">
                  <div>Start: <span class="text-slate-200 font-bold">${t.start ? new Date(t.start).toLocaleDateString() : '-'}</span></div>
                </div>
                <div class="space-y-1">
                  <div>Deadline: <span class="text-slate-200 font-bold font-mono">${t.deadline ? new Date(t.deadline).toLocaleDateString() : '-'}</span></div>
                </div>
              </div>
              ${qHtml ? `<button onclick="document.getElementById('taskQ_${index}').classList.toggle('hidden')" class="w-full mt-2 py-2 text-sm font-bold text-blue-400 hover:text-blue-300 bg-blue-500/5 rounded-xl transition-premium flex justify-center items-center gap-1"><span class="material-symbols-rounded text-sm">visibility</span> View Questions</button>` : ''}
              ${qHtml}
              ${actionBtn}
            </div>
          </div>
          `;
        });
        tasksContainer.innerHTML = tasksHtml;
        assignmentsSection.classList.remove('hidden');
      } else {
        tasksContainer.innerHTML = '';
        assignmentsSection.classList.add('hidden');
      }

      adjustPendingGridColumns();
    }

    function markManualTaskSubmitted(subjectCode, coTag, category) {
      if (!confirm("Are you sure you want to mark this task as submitted?")) return;
      fetch('/api/student/tasks/submit', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ subject_code: subjectCode, co_tag: coTag, category: category, status: 'Submitted' })
      })
      .then(res => res.json())
      .then(data => {
          if (data.status === 'SUCCESS') {
              alert(data.message);
              loadAcademicReport(); // reload tasks
          } else {
              alert(data.message || "Failed to submit.");
          }
      });
    }

    function renderCgpaChart(semesters) {
      const ctx = document.getElementById('cgpaChart').getContext('2d');
      if (cgpaChartInstance) cgpaChartInstance.destroy();

      const labels = semesters.map(s => `S${s.semester}`);
      const data = semesters.map(s => s.sgpa || 0);

      cgpaChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'SGPA',
            data: data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#fff',
            pointRadius: 4,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
            y: { 
              grid: { color: 'rgba(30,41,59,0.5)' }, 
              ticks: { color: '#64748b', font: { size: 10 } },
              min: 0, max: 10
            }
          }
        }
      });
    }

    function renderSemesterTabs(semesters) {
      const container = document.getElementById('semesterTabsContainer');
      let html = '';
      semesters.forEach(s => {
        const isActive = s.semester == currentActiveSem;
        const isCurrent = s.is_current === true;
        const cls = isActive 
          ? 'bg-blue-600/20 text-blue-400 border-blue-500/20' 
          : 'bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
        const badge = isCurrent ? `<span class="ml-1 text-[8px] bg-teal-500/20 text-teal-400 px-1 py-0.5 rounded font-black">NOW</span>` : '';
        html += `
          <button onclick="renderGodTable(${s.semester})" id="btnSemTab_${s.semester}" class="sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border ${cls}">
            Semester ${s.semester}${badge}
          </button>
        `;
      });
      container.innerHTML = html;
    }

    function renderGodTable(semId) {
      currentActiveSem = semId;
      document.querySelectorAll('.sem-tab').forEach(btn => {
        btn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
      });
      const actBtn = document.getElementById(`btnSemTab_${semId}`);
      if(actBtn) actBtn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-blue-600/20 text-blue-400 border-blue-500/20';

      const container = document.getElementById('academicReportContent');
      const semData = academicData.semesters.find(s => s.semester == semId);
      if (!semData || !semData.subjects || semData.subjects.length === 0) {
        container.innerHTML = `<div class="py-12 text-center text-slate-500 font-bold text-xs border border-slate-800/50 rounded-2xl bg-slate-900/30">No academic data available for Semester ${semId}.</div>`;
        return;
      }

      let rows = '';
      semData.subjects.forEach(sub => {
        const trClass = "border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium";
        rows += `
          <tr class="${trClass}">
            <td class="p-4 whitespace-nowrap max-w-[170px]">
              <div class="font-black text-slate-200 text-sm">${sub.subject_code}</div>
              <div class="text-xs text-slate-400 font-bold truncate max-w-[160px]" title="${sub.subject_name}">${sub.subject_name}</div>
            </td>
            <td class="p-4 text-center text-base font-mono font-bold text-slate-300">${sub.CO1 !== null ? Math.round(sub.CO1) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO2 !== null ? Math.round(sub.CO2) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-bold text-slate-300">${sub.CO3 !== null ? Math.round(sub.CO3) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO4 !== null ? Math.round(sub.CO4) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-bold text-blue-400 border-l border-slate-800">
              ${sub.Assg1 !== null ? Math.round(sub.Assg1) : (sub.Assg1_status === 'Submitted' ? '<span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-xs text-amber-400 font-bold tracking-wider animate-pulse">SUBMITTED</span>' : '-')}
            </td>
            <td class="p-4 text-center text-base font-mono font-bold text-blue-400">
              ${sub.Assg2 !== null ? Math.round(sub.Assg2) : (sub.Assg2_status === 'Submitted' ? '<span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-xs text-amber-400 font-bold tracking-wider animate-pulse">SUBMITTED</span>' : '-')}
            </td>
            <td class="p-4 text-center text-base font-mono font-bold text-blue-400">
              ${sub.Assg3 !== null ? Math.round(sub.Assg3) : (sub.Assg3_status === 'Submitted' ? '<span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-xs text-amber-400 font-bold tracking-wider animate-pulse">SUBMITTED</span>' : '-')}
            </td>
            <td class="p-4 text-center text-base font-mono font-bold text-blue-400">
              ${sub.Assg4 !== null ? Math.round(sub.Assg4) : (sub.Assg4_status === 'Submitted' ? '<span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-xs text-amber-400 font-bold tracking-wider animate-pulse">SUBMITTED</span>' : '-')}
            </td>
            <td class="p-4 text-center text-base font-mono font-black text-emerald-400 border-l border-slate-800">${sub.WT1 !== null ? Math.round(sub.WT1) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-emerald-400">${sub.WT2 !== null ? Math.round(sub.WT2) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-emerald-400">${sub.WT3 !== null ? Math.round(sub.WT3) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-emerald-400">${sub.WT4 !== null ? Math.round(sub.WT4) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-purple-400 border-l border-slate-800">${sub.OT1 !== null ? Math.round(sub.OT1) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-purple-400">${sub.OT2 !== null ? Math.round(sub.OT2) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-purple-400">${sub.OT3 !== null ? Math.round(sub.OT3) : '-'}</td>
            <td class="p-4 text-center text-base font-mono font-black text-purple-400">${sub.OT4 !== null ? Math.round(sub.OT4) : '-'}</td>
            <td class="p-4 text-center text-base font-black border-l border-slate-800 ${sub.attendance_percentage < 75 ? 'text-rose-400' : 'text-slate-300'}">
              ${sub.attendance_percentage}%
            </td>
            <td class="p-4 text-center text-base font-mono font-black border-l border-slate-800 text-sky-400 uppercase">
              ${sub.board_grade ? sub.board_grade.toUpperCase() : '-'}
            </td>
          </tr>
        `;
      });

      container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
          <div class="flex gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-sm">stars</span>
              <span class="text-sm text-slate-400 font-bold uppercase tracking-widest">SGPA:</span>
              <span class="text-sm font-black text-white">${semData.sgpa || '-'}</span>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-sm">local_activity</span>
              <span class="text-sm text-slate-400 font-bold uppercase tracking-widest">Points:</span>
              <span class="text-sm font-black text-white">${semData.activity_points || '-'}</span>
            </div>
          </div>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-x-auto shadow-2xl">
          <table class="w-full text-left border-collapse min-w-[1150px]">
            <thead>
              <tr class="bg-slate-900/80 border-b border-slate-800 text-sm uppercase tracking-wider font-black text-slate-400">
                <th class="p-4 font-black max-w-[170px]">Subject</th>
                <th class="p-4 text-center" colspan="4">Sum COs</th>
                <th class="p-4 text-center border-l border-slate-800 text-blue-400" colspan="4">Assignments</th>
                <th class="p-4 text-center border-l border-slate-800 text-emerald-400" colspan="4">Written Tests</th>
                <th class="p-4 text-center border-l border-slate-800 text-purple-400" colspan="4">Online Tests</th>
                <th class="p-4 text-center border-l border-slate-800">Attend.</th>
                <th class="p-4 text-center border-l border-slate-800 text-sky-400">Board Exam</th>
              </tr>
              <tr class="bg-slate-900/40 border-b border-slate-800/50 text-xs uppercase font-bold text-slate-500">
                <th class="p-2 max-w-[170px]"></th>
                <th class="p-2 text-center w-10 border-l border-slate-800/50">C1</th><th class="p-2 text-center w-10 bg-slate-950/20">C2</th><th class="p-2 text-center w-10">C3</th><th class="p-2 text-center w-10 bg-slate-950/20">C4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">A1</th><th class="p-2 text-center w-10">A2</th><th class="p-2 text-center w-10">A3</th><th class="p-2 text-center w-10">A4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">W1</th><th class="p-2 text-center w-10">W2</th><th class="p-2 text-center w-10">W3</th><th class="p-2 text-center w-10">W4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">O1</th><th class="p-2 text-center w-10">O2</th><th class="p-2 text-center w-10">O3</th><th class="p-2 text-center w-10">O4</th>
                <th class="p-2 text-center w-16 border-l border-slate-800">%</th>
                <th class="p-2 text-center w-24 border-l border-slate-800">Grade</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
              ${rows}
            </tbody>
          </table>
        </div>
      `;
    }

    function updateSbteRegNo() {
      const val = document.getElementById('sbteRegNoInput').value.trim();
      const alertEl = document.getElementById('sbteAlert');
      if (!val) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Please enter your SBTE Register Number.';
        return;
      }
      fetch('/api/student/update-sbte-reg', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sbteRegNo: val })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block';
          alertEl.innerText = 'SBTE Register Number saved! Reload the page to see it confirmed.';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
          alertEl.innerText = data.message || 'Failed to save. Please try again.';
        }
      })
      .catch(() => {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Network error. Please try again.';
      });
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value.trim();
      const newPwd = document.getElementById('newPwd').value.trim();
      const alert = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Please fill in both fields.";
        return;
      }
      if (newPwd.length < 6) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "New password must be at least 6 characters.";
        return;
      }
      fetch('/api/student/change-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ oldPassword: oldPwd, newPassword: newPwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Password updated successfully.";
          document.getElementById('oldPwd').value = '';
          document.getElementById('newPwd').value = '';
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message || 'Password change failed.';
        }
      })
      .catch(() => {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = 'Request failed. Please try again.';
      });
    }

    function updateStudentEmail() {
      const email = document.getElementById('studentEmailInput').value.trim();
      const alertEl = document.getElementById('emailAlert');
      if (!email) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
        alertEl.innerText = 'Please enter your email address.';
        return;
      }
      fetch('/api/student/update-email', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email: email })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 block';
          alertEl.innerText = 'Email address updated successfully!';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
          alertEl.innerText = data.message || 'Failed to update email.';
        }
      })
      .catch(() => {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
        alertEl.innerText = 'Network error. Please try again.';
      });
    }

    function handlePhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('photoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-xs font-bold mt-2 text-blue-400";
      statusEl.innerText = "Analyzing & processing image...";

      // Client-side MIME check
      const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
      if (!validTypes.includes(file.type.toLowerCase())) {
        statusEl.className = "text-xs font-bold mt-2 text-rose-400";
        statusEl.innerText = "Photo restricted: Please upload a valid JPG, PNG, or WebP image file.";
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
          const w = img.width;
          const h = img.height;
          const aspectRatio = w / h;

          // Enforce passport style clear face aspect ratio restriction
          // Rejects full body tall vertical shots or wide horizontal group/landscape shots
          if (aspectRatio < 0.65 || aspectRatio > 1.35) {
            statusEl.className = "text-xs font-bold mt-2 text-rose-400 leading-snug";
            statusEl.innerText = "Photo restricted: Please upload a close-up clear face photo (passport style). Full-body photos or wide landscape shots are not allowed.";
            return;
          }

          // Auto-crop to 1:1 passport face square canvas & downscale (400x400)
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          const minDim = Math.min(w, h);
          canvas.width = 400;
          canvas.height = 400;

          // Center crop around upper head/face area
          const cropX = (w - minDim) / 2;
          const cropY = Math.max(0, (h - minDim) * 0.2);

          ctx.drawImage(img, cropX, cropY, minDim, minDim, 0, 0, 400, 400);

          canvas.toBlob((blob) => {
            if (!blob) {
              statusEl.className = "text-xs font-bold mt-2 text-rose-400";
              statusEl.innerText = "Error processing image.";
              return;
            }

            statusEl.innerText = "Uploading photo...";
            const formData = new FormData();
            formData.append('photo', blob, 'passport_photo.jpg');

            fetch('/api/student/profile/upload-photo', {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: formData
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'SUCCESS') {
                statusEl.className = "text-xs font-bold mt-2 text-emerald-400";
                statusEl.innerText = "Passport photo updated successfully!";

                const imgEl = document.getElementById('studentProfileImg');
                if (imgEl) {
                  imgEl.src = data.photo_url;
                } else {
                  const wrapper = document.getElementById('studentAvatarWrapper');
                  wrapper.innerHTML = `<img id="studentProfileImg" src="${data.photo_url}" class="w-full h-full object-cover">`;
                }

                const sidebarImg = document.getElementById('sidebarStudentImg');
                if (sidebarImg) {
                  sidebarImg.src = data.photo_url;
                } else {
                  const sidebarWrapper = document.getElementById('sidebarAvatarContainer');
                  if (sidebarWrapper) {
                    sidebarWrapper.innerHTML = `<img id="sidebarStudentImg" src="${data.photo_url}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">`;
                  }
                }

                setTimeout(() => statusEl.classList.add('hidden'), 3000);
              } else {
                statusEl.className = "text-xs font-bold mt-2 text-rose-400";
                statusEl.innerText = data.message || "Upload failed.";
              }
            })
            .catch(() => {
              statusEl.className = "text-xs font-bold mt-2 text-rose-400";
              statusEl.innerText = "Network error. Please try again.";
            });
          }, 'image/jpeg', 0.88);
        };
        img.onerror = function() {
          statusEl.className = "text-xs font-bold mt-2 text-rose-400";
          statusEl.innerText = "Image format mismatch or file damaged.";
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }

    // Init stub stats and load tests
    document.addEventListener('DOMContentLoaded', () => {
      loadStudentTests();
      loadAcademicReport();
    });

    // TEST ENGINE LOGIC
    let currentTestId = null;
    let timerInterval = null;
    let endTimeMs = null;

    function loadStudentTests() {
      fetch('/api/student/online-tests')
        .then(res => res.json())
        .then(data => {
          let container = document.getElementById('studentActiveTestsList');
          let mcqSection = document.getElementById('mcqTestsSection');

          if (data.status === 'SUCCESS' && data.tests && data.tests.length > 0) {
            mcqSection.classList.remove('hidden');

            let html = '';
            data.tests.forEach(t => {
              let actionHtml = '';
              if (t.can_take) {
                actionHtml = `<button onclick="startOnlineTest('${t.test_id}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded font-bold text-sm transition-premium">Start Test</button>`;
              } else if (t.status_message && t.status_message.startsWith('Starts')) {
                actionHtml = `<button disabled class="w-full py-2 bg-slate-800/40 text-slate-400 rounded font-bold text-sm text-center border border-slate-700/50 mb-2 cursor-not-allowed flex items-center justify-center gap-2"><span class="material-symbols-rounded text-sm">lock</span> ${t.status_message}</button>`;
              } else if (t.my_attempts > 0) {
                actionHtml = `<div class="w-full py-2 bg-emerald-900/40 text-emerald-400 rounded font-bold text-sm text-center border border-emerald-800/50 mb-2">Best Score: ${t.best_score || 0}</div>`;
              } else {
                actionHtml = `<div class="w-full py-2 bg-slate-800/40 text-slate-400 rounded font-bold text-sm text-center border border-slate-700/50 mb-2">${t.status_message || 'Expired'}</div>`;
              }

              let hasEnded = false;
              if (t.end_time) {
                let et = new Date(t.end_time.replace(' ', 'T'));
                hasEnded = (new Date() >= et);
              }
              if (hasEnded && t.my_attempts > 0) {
                actionHtml += `<button onclick="viewAnswerKey('${t.test_id}')" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded font-bold text-sm transition-premium">View Answer Key</button>`;
              } else if (t.my_attempts > 0 && !t.can_take) {
                let formattedEndTime = new Date(t.end_time).toLocaleString();
                actionHtml += `<div class="text-sm text-center text-slate-400 font-semibold mt-1 bg-slate-950/30 p-1.5 rounded border border-slate-800/50">Answer key unlocks after test ends: <br/>${formattedEndTime}</div>`;
              }

              html += `
                <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl overflow-hidden mb-1">
                  <!-- Collapsible Header -->
                  <div onclick="document.getElementById('co_exam_${t.test_id}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_exam_${t.test_id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                       class="px-4 py-3.5 bg-slate-950/40 hover:bg-slate-950/70 border-b border-slate-800/60 flex justify-between items-center cursor-pointer transition-premium">
                    <div class="flex items-center gap-3">
                      <span class="material-symbols-rounded text-purple-400 text-sm">quiz</span>
                      <div>
                        <h4 class="font-bold text-sm text-slate-200">${t.test_name}</h4>
                        <p class="text-sm font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject_name || t.subject_code}</p>
                      </div>
                    </div>
                    <span class="material-symbols-rounded text-slate-500 text-sm arrow-icon">expand_more</span>
                  </div>
                  <!-- Collapsible Content -->
                  <div id="co_exam_${t.test_id}" class="hidden p-4 bg-slate-950/10 border-t border-slate-800/40">
                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-slate-400 font-semibold">
                      <div class="space-y-1">
                        <div>Duration: <span class="text-slate-200 font-bold">${t.duration} Mins</span></div>
                        <div>Total Questions: <span class="text-slate-200 font-bold">${t.mcq_count} MCQs</span></div>
                      </div>
                      <div class="space-y-1">
                        <div>Attempts: <span class="text-slate-200 font-bold">${t.my_attempts}/${t.max_attempts}</span></div>
                        <div>Deadline: <span class="text-slate-200 font-bold font-mono">${t.end_time ? new Date(t.end_time).toLocaleString() : 'No Limit'}</span></div>
                      </div>
                    </div>
                    <div class="mt-3">
                      ${actionHtml}
                    </div>
                  </div>
                </div>
              `;
            });
            container.innerHTML = html;
            container.className = "flex flex-col gap-3";
          } else {
            mcqSection.classList.add('hidden');
            container.innerHTML = '';
            container.className = "";
          }

          adjustPendingGridColumns();
          if (data.stats) updateStatsHeader(null, data.stats);
        });
    }

    function startOnlineTest(testId) {
      if(!confirm("Are you sure you want to start this test? The timer will begin immediately.")) return;
      
      fetch(`/api/student/online-tests/${testId}/start`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentTestId = testId;
          renderTestEngine(data.questions, data.duration);
        } else {
          alert(data.message || "Could not start test.");
        }
      });
    }

    function renderTestEngine(questions, durationMins) {
      document.getElementById('testEngineModal').classList.remove('hidden');

      let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
      questions.forEach((q, idx) => {
        let optionsHtml = '';
        q.options.forEach((opt, oIdx) => {
          optionsHtml += `
            <label class="flex items-center gap-3 p-4 rounded-lg border border-slate-700/50 bg-slate-900/50 cursor-pointer hover:border-purple-500/50 hover:bg-slate-800 transition-premium">
              <input type="radio" name="q_${idx}" value="${opt}" class="w-5 h-5 text-purple-500 bg-slate-950 border-slate-600 focus:ring-purple-600">
              <span class="text-sm text-slate-200 leading-snug">${opt}</span>
            </label>
          `;
        });
        html += `
          <div class="question-container bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
             <div class="flex items-start gap-4 mb-5">
               <span class="flex-shrink-0 w-9 h-9 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-sm border border-purple-500/20">${idx+1}</span>
               <h4 class="text-base font-bold text-slate-100 mt-1 leading-relaxed">${q.q}</h4>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-13">
               ${optionsHtml}
             </div>
          </div>
        `;
      });
      html += '</div>';
      document.getElementById('testQuestionsContainer').innerHTML = html;

      // Start Timer
      endTimeMs = Date.now() + (durationMins * 60 * 1000);
      timerInterval = setInterval(updateTimer, 1000);
      updateTimer();
    }

    function updateTimer() {
      let now = Date.now();
      let diff = endTimeMs - now;
      if (diff <= 0) {
        clearInterval(timerInterval);
        document.getElementById('liveTimer').innerText = "00:00:00";
        alert("Time is up! Auto-submitting your test.");
        submitTest();
        return;
      }
      
      let h = Math.floor(diff / (1000 * 60 * 60));
      let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      let s = Math.floor((diff % (1000 * 60)) / 1000);
      
      document.getElementById('liveTimer').innerText = 
        (h < 10 ? '0'+h : h) + ':' + 
        (m < 10 ? '0'+m : m) + ':' + 
        (s < 10 ? '0'+s : s);
    }

    function cancelTest() {
      if(!confirm("Are you sure? Your progress will be lost.")) return;
      document.getElementById('testEngineModal').classList.add('hidden');
    }

    function submitTest() {
      if(!currentTestId) return;
      document.getElementById('testEngineModal').classList.add('hidden');
      
      const formContainers = document.getElementById('testQuestionsContainer').querySelectorAll('.question-container');
      let answers = {};
      formContainers.forEach((container, idx) => {
        let checked = container.querySelector(`input[name="q_${idx}"]:checked`);
        answers[idx] = checked ? checked.value : null;
      });

      fetch(`/api/student/online-tests/${currentTestId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ answers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Hide engine, show result modal
          document.getElementById('testEngineModal').classList.add('hidden');
          document.getElementById('testResultModal').classList.remove('hidden');
          setTimeout(() => document.getElementById('resultModalBox').classList.remove('scale-95'), 50);

          document.getElementById('resultScore').innerText = `${data.summary.score}/${data.summary.total}`;
          document.getElementById('resultPercent').innerText = `${data.summary.percentage}%`;
        } else {
          alert(data.message || "Submission failed.");
        }
      });
    }

      function closeResultModal() {
        document.getElementById('testResultModal').classList.add('hidden');
        loadStudentTests(); // refresh the list
        loadAcademicReport(); // refresh academic stats so new marks show up
      }

    function viewAnswerKey(testId) {
      fetch(`/api/student/online-tests/${testId}/answer-key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('answerKeyTestName').innerText = data.test_name;
            document.getElementById('answerKeyScoreInfo').innerText = `Best Score: ${data.score}/${data.total} (${data.percentage}%)`;
            
            let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
            data.details.forEach((q, idx) => {
              let optionsHtml = '';
              q.options.forEach((opt, oIdx) => {
                let badgeHtml = '';
                let borderClass = 'border-slate-700/50 bg-slate-900/50';
                
                // Color code options
                if (opt === q.correct_ans) {
                  borderClass = 'border-green-500/50 bg-green-950/20';
                  badgeHtml = '<span class="text-xs bg-green-500/20 text-green-400 font-bold px-2 py-0.5 rounded ml-auto">Correct Answer</span>';
                } else if (opt === q.student_ans) {
                  borderClass = 'border-red-500/50 bg-red-950/20';
                  badgeHtml = '<span class="text-xs bg-red-500/20 text-red-400 font-bold px-2 py-0.5 rounded ml-auto">Your Answer</span>';
                }

                optionsHtml += `
                  <div class="flex items-center gap-3 p-3 rounded-lg border ${borderClass} transition-premium">
                    <span class="text-xs text-slate-300">${opt}</span>
                    ${badgeHtml}
                  </div>
                `;
              });

              let correctBadge = q.is_correct 
                ? '<span class="bg-green-500/10 text-green-400 text-xs font-bold px-2.5 py-1 rounded-full border border-green-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-xs">check_circle</span> Correct</span>'
                : `<span class="bg-red-500/10 text-red-400 text-xs font-bold px-2.5 py-1 rounded-full border border-red-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-xs">cancel</span> Incorrect</span>`;

              html += `
                <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
                   <div class="flex items-start justify-between gap-4 mb-4">
                     <div class="flex items-start gap-4">
                       <span class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center font-black text-xs border border-slate-700/20">${idx+1}</span>
                       <div>
                         <h4 class="text-xs font-bold text-slate-100 mt-1">${q.q}</h4>
                         <span class="text-xs text-slate-500 font-mono">CO Tag: ${q.co}</span>
                       </div>
                     </div>
                     ${correctBadge}
                   </div>
                   <div class="grid grid-cols-1 gap-3 pl-12">
                     ${optionsHtml}
                   </div>
                </div>
              `;
            });
            html += '</div>';
            
            document.getElementById('answerKeyQuestionsContainer').innerHTML = html;
            document.getElementById('answerKeyModal').classList.remove('hidden');
          } else {
            alert(data.message || "Could not retrieve answer key.");
          }
        });
    }

    function closeAnswerKeyModal() {
      document.getElementById('answerKeyModal').classList.add('hidden');
    }

    function loadActivityPoints() {
      fetch('/api/student/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('overallActivityPoints').innerText = data.total_points || 0;
            document.getElementById('verifiedActivityTotal').innerText = data.total_points || 0;
            
            // Progress Bar
            let pts = data.total_points || 0;
            let goal = {{ $activityGoal }};
            let percent = Math.min(100, Math.round((pts / goal) * 100));
            
            const pBar = document.getElementById('activityProgressBar');
            pBar.style.width = percent + '%';
            
            if (percent >= 100) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-1000 ease-out";
            } else if (percent >= 50) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out";
            } else {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-red-500 to-rose-400 transition-all duration-1000 ease-out";
            }

            // Split
            let splitHtml = '';
            if (data.split && Object.keys(data.split).length > 0) {
              for (const [segment, pts] of Object.entries(data.split)) {
                splitHtml += `
                  <div class="flex justify-between items-center py-1">
                    <span class="text-xs text-slate-300">${segment}</span>
                    <span class="text-xs font-bold text-emerald-400">${pts}</span>
                  </div>
                `;
              }
            } else {
              splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
            }
            document.getElementById('activitySplitList').innerHTML = splitHtml;

            // Claims Table
            const tbody = document.getElementById('activityClaimsTableBody');
            if (data.claims && data.claims.length > 0) {
              let html = '';
              data.claims.forEach(c => {
                let statusClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                if (c.status === 'Verified') statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if (c.status === 'Rejected') statusClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                
                let dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString() : 'N/A';
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight">Reason: ${c.rejection_note}</div>`;
                }
                if (c.status !== 'Pending' && verifiedDateStr) {
                  noteHtml += `<div class="mt-0.5 text-xs text-slate-500">On: ${verifiedDateStr}</div>`;
                }
                
                html += `
                  <tr class="hover:bg-slate-900/50 transition-colors border-b border-slate-800/40">
                    <td class="p-3 text-xs text-slate-400">${dateStr}</td>
                    <td class="p-3 text-xs font-bold text-slate-300">${c.activity_segment}</td>
                    <td class="p-3 text-xs text-slate-300">${c.activity_name}</td>
                    <td class="p-3 text-xs text-slate-400">${c.level}</td>
                    <td class="p-3">
                      ${c.document_reference ? `<a href="${c.document_reference}" target="_blank" class="text-blue-400 hover:text-blue-300 text-xs underline flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">link</span> View</a>` : '<span class="text-xs text-slate-600">None</span>'}
                    </td>
                    <td class="p-3 text-center text-xs font-bold text-slate-300">${c.points_claimed}</td>
                    <td class="p-3 text-center text-xs font-bold ${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}">${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class="p-3 text-right max-w-[120px]">
                      <span class="px-2 py-0.5 rounded border text-xs font-bold uppercase tracking-wider ${statusClass} inline-block">${c.status}</span>
                      ${noteHtml}
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-xs">No activity claims submitted yet.</td></tr>`;
            }
          }
        });
    }

    function submitActivityClaim(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      fetch('/api/student/activity-points', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          form.reset();
          loadActivityPoints();
        } else {
          alert(resData.message || 'Failed to submit claim.');
        }
      });
    }

    let _seminarGuides = []; // cache for guide list

    function loadSeminarRegistration() {
      const guideSelect = document.getElementById('semRegGuide');
      const subjectInput = document.getElementById('semRegSubject');
      const statusBanner = document.getElementById('seminarStatusBanner');
      const formCard = document.getElementById('seminarFormCard');
      const noSubjectAlert = document.getElementById('seminarNoSubjectAlert');

      // Reset UI
      statusBanner.classList.add('hidden');
      noSubjectAlert.classList.add('hidden');
      subjectInput.value = '';

      // 1. Fetch guides
      guideSelect.innerHTML = '<option value="">Loading guides...</option>';
      fetch('/api/student/seminar/guides')
      .then(r => r.json())
      .then(res => {
        _seminarGuides = res.guides || [];
        guideSelect.innerHTML = '<option value="">Select Guide...</option>';
        _seminarGuides.forEach(g => {
          const opt = document.createElement('option');
          opt.value = g.mobile_no;
          opt.innerText = g.name;
          guideSelect.appendChild(opt);
        });
      });

      // 2. Find seminar subject from academic report
      // API returns: { status, overall: { current_semester }, semesters: [{semester, subjects:[...]}, ...] }
      fetch('/api/student/academic-report')
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'SUCCESS') return;

        // Correct keys: overall.current_semester and semesters (array)
        const currentSem = res.overall?.current_semester;
        const semestersArr = res.semesters || [];

        // Find current semester entry first, then fall back to any semester with a Seminar subject
        let semSubj = null;
        const currentSemData = semestersArr.find(s => s.semester == currentSem);
        if (currentSemData) {
          semSubj = (currentSemData.subjects || []).find(s => s.subject_type === 'Seminar');
        }
        // Fallback: search all semesters
        if (!semSubj) {
          for (const semData of semestersArr) {
            semSubj = (semData.subjects || []).find(s => s.subject_type === 'Seminar');
            if (semSubj) break;
          }
        }

        if (!semSubj) {
          noSubjectAlert.classList.remove('hidden');
          formCard.classList.add('hidden');
          return;
        }

        formCard.classList.remove('hidden');
        subjectInput.value = semSubj.batch_subject_id;
        fetchSeminarDetails(semSubj.batch_subject_id);
      })
      .catch(() => {
        showSeminarToast('Failed to load seminar data. Please refresh.', 'error');
      });
    }

    function fetchSeminarDetails(subjectId) {
      const regNo = "{{ session('userId') }}";
      fetch(`/api/classroom/${subjectId}/seminar/evaluations`)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'SUCCESS') return;
        const student = res.data.find(s => s.reg_no === regNo);
        const statusBanner = document.getElementById('seminarStatusBanner');
        const formCard = document.getElementById('seminarFormCard');
        const cancelBtn = document.getElementById('semCancelEditBtn');
        const submitBtn = document.getElementById('semSubmitBtn');
        const formTitle = document.getElementById('semFormTitle');

        if (student && student.topic) {
          // Show registered banner
          document.getElementById('semStatusTopic').innerText = student.topic;
          document.getElementById('semStatusGuide').innerText = student.guide_name || '-';
          document.getElementById('semStatusDate').innerText = student.presentation_date || '-';
          document.getElementById('semStatusScore').innerText = `${student.average_score} / 75`;
          
          const assessmentsCount = student.evaluators_count || 0;
          document.getElementById('semStatusAssessments').innerText = `${assessmentsCount} assessors`;

          const titleEl = document.getElementById('semStatusBadgeTitle');
          const bannerEl = document.getElementById('seminarStatusBanner');
          if (assessmentsCount > 0) {
            titleEl.innerText = "Seminar Completed ✅";
            bannerEl.className = "max-w-3xl mx-auto p-5 bg-gradient-to-r from-emerald-950/80 to-teal-950/80 border border-emerald-600/40 rounded-2xl";
          } else {
            titleEl.innerText = "Seminar Registered ⚠️";
            bannerEl.className = "max-w-3xl mx-auto p-5 bg-slate-900/60 border border-slate-800/60 rounded-2xl";
          }
          statusBanner.classList.remove('hidden');

          // Pre-fill form (hidden until Edit clicked)
          document.getElementById('semRegTopic').value = student.topic;
          document.getElementById('semRegDate').value = student.presentation_date
            ? student.presentation_date.split('-').reverse().join('-')
            : '';
          // Set guide select after guides are loaded
          setTimeout(() => {
            const gs = document.getElementById('semRegGuide');
            if (student.guide_mobile_no) gs.value = student.guide_mobile_no;
          }, 600);

          // Switch form to edit/update mode (hidden)
          formTitle.innerHTML = '<span class="material-symbols-rounded text-blue-400">edit</span> Update Seminar Details';
          submitBtn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Update Registration';
          cancelBtn.classList.remove('hidden');
          formCard.classList.add('hidden'); // hidden until Edit clicked
        } else {
          // Not yet registered - show form openly
          statusBanner.classList.add('hidden');
          formCard.classList.remove('hidden');
          cancelBtn.classList.add('hidden');
          formTitle.innerHTML = '<span class="material-symbols-rounded text-blue-400">co_present</span> Register Seminar Details';
          submitBtn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Save Registration';
        }
      });
    }

    function showSeminarEditForm() {
      document.getElementById('seminarFormCard').classList.remove('hidden');
      // Scroll to form
      document.getElementById('seminarFormCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function cancelSeminarEdit() {
      document.getElementById('seminarFormCard').classList.add('hidden');
    }

    function showSeminarToast(msg, type = 'success') {
      const toast = document.getElementById('seminarToast');
      toast.className = `max-w-3xl mx-auto px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mb-1 ${
        type === 'success'
          ? 'bg-emerald-950/80 border border-emerald-600/40 text-emerald-300'
          : 'bg-red-950/80 border border-red-600/40 text-red-300'
      }`;
      toast.innerHTML = `<span class="material-symbols-rounded text-base">${type === 'success' ? 'check_circle' : 'error'}</span> ${msg}`;
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 4000);
    }

    function submitSeminarRegistration(e) {
      e.preventDefault();
      const subjectId = document.getElementById('semRegSubject').value;
      const topic = document.getElementById('semRegTopic').value.trim();
      const date = document.getElementById('semRegDate').value;
      const guide = document.getElementById('semRegGuide').value;
      const btn = document.getElementById('semSubmitBtn');

      if (!subjectId) {
        showSeminarToast('No seminar subject found for your batch. Contact HOD.', 'error');
        return;
      }
      if (!topic || !date || !guide) {
        showSeminarToast('Please fill all fields before saving.', 'error');
        return;
      }

      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-base animate-spin">sync</span> Saving...';

      fetch('/api/student/seminar/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          batch_subject_id: subjectId,
          topic,
          presentation_date: date,
          guide_mobile_no: guide
        })
      })
      .then(r => r.json())
      .then(res => {
        btn.disabled = false;
        if (res.status === 'SUCCESS') {
          showSeminarToast('Seminar details saved successfully! Invitations sent to all department staff.', 'success');
          document.getElementById('seminarFormCard').classList.add('hidden');
          loadSeminarRegistration();
        } else {
          showSeminarToast(res.message || 'Failed to save.', 'error');
          btn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Save Registration';
        }
      })
      .catch(() => {
        btn.disabled = false;
        showSeminarToast('Network error. Please try again.', 'error');
        btn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Save Registration';
      });
    }
  </script>

  <!-- LIVE TEST ENGINE MODAL (Hidden by default) -->
  <div id="testEngineModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
    <!-- Top Bar -->
    <div class="h-14 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
      <div class="flex items-center gap-3">
        <span class="material-symbols-rounded text-purple-500 text-base">devices</span>
        <div>
          <h3 id="liveTestName" class="font-bold text-xs text-white leading-tight">Test Name</h3>
          <span class="text-xs text-slate-400 font-mono" id="liveTestReg">{{ session('userId') }}</span>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <div class="bg-slate-950 border border-slate-800 px-4 py-1.5 rounded-full flex items-center gap-2 text-xs font-bold shadow-inner">
          <span class="material-symbols-rounded text-red-400 text-xs">timer</span>
          <span id="liveTimer" class="text-red-400 font-mono tracking-widest">00:00:00</span>
        </div>
        <button onclick="submitTest()" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-1.5 rounded-full font-bold text-xs transition-premium shadow-lg shadow-purple-600/20">Submit Final</button>
      </div>
    </div>

    <!-- Question Area -->
    <div class="flex-grow overflow-y-auto p-6 md:p-12" id="testQuestionsContainer">
       <!-- Render questions here -->
    </div>
  </div>

  <!-- TEST RESULT MODAL (Hidden by default) -->
  <div id="testResultModal" class="hidden fixed inset-0 z-50 bg-slate-900/95 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-950 border border-slate-800 rounded-3xl p-8 max-w-md w-full shadow-2xl text-center transform scale-95 transition-premium" id="resultModalBox">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 mb-4 border border-emerald-500/20">
        <span class="material-symbols-rounded text-xl">verified</span>
      </div>
      <h2 class="text-base font-black text-white mb-1">Test Completed!</h2>
      <p class="text-xs text-slate-400 mb-6">Your responses have been saved securely.</p>
      
      <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
          <span class="text-xs uppercase font-black tracking-wider text-slate-500 block mb-1">Total Score</span>
          <span class="text-base font-black text-emerald-400" id="resultScore">0/0</span>
        </div>
        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
          <span class="text-xs uppercase font-black tracking-wider text-slate-500 block mb-1">Percentage</span>
          <span class="text-base font-black text-blue-400" id="resultPercent">0%</span>
        </div>
      </div>

      <button onclick="closeResultModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-xs transition-premium">Return to Dashboard</button>
    </div>
  </div>

  <!-- ANSWER KEY VIEW MODAL (Hidden by default) -->
  <div id="answerKeyModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
    <!-- Top Bar -->
    <div class="h-14 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
      <div class="flex items-center gap-3">
        <span class="material-symbols-rounded text-blue-400 text-base">menu_book</span>
        <div>
          <h3 id="answerKeyTestName" class="font-bold text-xs text-white leading-tight">Answer Key Review</h3>
          <span class="text-xs text-slate-400 font-mono block" id="answerKeyScoreInfo">Score: â</span>
        </div>
      </div>
      <button onclick="closeAnswerKeyModal()" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-1.5 rounded-full font-bold text-xs transition-premium">Close</button>
    </div>

    <!-- Content Area -->
    <div class="flex-grow overflow-y-auto p-6 md:p-12 animate-fade-in" id="answerKeyQuestionsContainer">
       <!-- Render questions, student answers, and correct answers here -->
    </div>
  </div>

  <!-- STUDY MATERIALS & PRE-CLASS VAULT MODAL -->
  <div id="vlmVaultModal" class="hidden fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex flex-col">
    <!-- Modal Header -->
    <div class="h-16 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
          <span class="material-symbols-rounded text-xl">folder_special</span>
        </div>
        <div>
          <h3 class="font-black text-sm text-white leading-tight">Digital Learning Vault & Pre-Class Materials</h3>
          <span class="text-xs text-slate-400 block">Access pre-class topic notes, lab rough record guides, tutorial clips, and references published by faculty.</span>
        </div>
      </div>
      <button onclick="closeVlmVaultModal()" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
        <span class="material-symbols-rounded text-lg">close</span>
      </button>
    </div>

    <!-- Modal Content Grid -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-4">
      <div id="vlmVaultList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Dynamic Material Cards -->
      </div>
    </div>
  </div>

  <script>
    let activeVlmNoticeId = null;

    document.addEventListener('DOMContentLoaded', function() {
      loadStudentPreClassAlerts();
    });

    function loadStudentPreClassAlerts() {
      fetch('/api/student/materials/pre-class-notices')
        .then(res => res.json())
        .then(data => {
          if (data.success && data.notices && data.notices.length > 0) {
            const notice = data.notices[0];
            activeVlmNoticeId = notice.id;
            document.getElementById('vlmAlertTitle').innerText = (notice.subject_code ? notice.subject_code + ': ' : '') + notice.title;
            document.getElementById('vlmAlertInstruction').innerText = notice.description || 'Pre-class material published for upcoming session.';
            document.getElementById('vlmAlertTargetDate').innerText = 'Target: ' + (notice.target_class_date || 'Upcoming Class');
            document.getElementById('vlmPreClassAlertBanner').classList.remove('hidden');
          }
        })
        .catch(err => console.error('VLM Alert fetch error:', err));
    }

    function acknowledgeVlmNotice() {
      if (!activeVlmNoticeId) return;
      const btn = document.getElementById('btnAckVlm');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch(`/api/student/materials/${activeVlmNoticeId}/read`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('vlmPreClassAlertBanner').classList.add('hidden');
        }
      })
      .catch(err => console.error(err))
      .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Acknowledge';
      });
    }

    function openVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.remove('hidden');
      fetchVlmVaultMaterials();
    }

    function closeVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.add('hidden');
    }

    function fetchVlmVaultMaterials() {
      const container = document.getElementById('vlmVaultList');
      container.innerHTML = '<div class="col-span-full text-center text-slate-400 py-8"><span class="material-symbols-rounded animate-spin text-2xl">sync</span><p class="mt-2 text-xs">Loading learning materials vault...</p></div>';

      fetch('/api/student/materials/pre-class-notices')
        .then(res => res.json())
        .then(data => {
          if (!data.success || !data.notices || data.notices.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center text-slate-400 py-12 bg-slate-900/50 rounded-2xl border border-slate-800"><span class="material-symbols-rounded text-3xl text-slate-500 mb-2">folder_open</span><p class="text-sm font-semibold">No materials published yet.</p><p class="text-xs text-slate-500 mt-1">Study notes and pre-class guides uploaded by your teachers will appear here.</p></div>';
            return;
          }

          let html = '';
          data.notices.forEach(m => {
            let icon = 'description';
            let badgeColor = 'bg-blue-500/20 text-blue-300 border-blue-500/30';
            if (m.resource_type === 'video') {
              icon = 'smart_display';
              badgeColor = 'bg-rose-500/20 text-rose-300 border-rose-500/30';
            } else if (m.resource_type === 'image') {
              icon = 'image';
              badgeColor = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
            } else if (m.resource_type === 'link') {
              icon = 'link';
              badgeColor = 'bg-purple-500/20 text-purple-300 border-purple-500/30';
            }

            let linkAttr = '';
            if (m.resource_type === 'link') {
              linkAttr = `href="${m.external_url}" target="_blank"`;
            } else if (m.file_path) {
              linkAttr = `href="/storage/${m.file_path}" target="_blank"`;
            } else if (m.external_url) {
              linkAttr = `href="${m.external_url}" target="_blank"`;
            } else {
              linkAttr = `href="#"`;
            }

            html += `
              <div class="bg-slate-900 border ${m.is_urgent ? 'border-amber-500/50' : 'border-slate-800'} rounded-2xl p-4 flex flex-col justify-between hover:border-slate-700 transition-all shadow-lg">
                <div>
                  <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="px-2 py-0.5 rounded-full ${badgeColor} border text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">${icon}</span> ${m.resource_type}
                    </span>
                    ${m.is_urgent ? '<span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-extrabold uppercase">Urgent Alert</span>' : ''}
                  </div>
                  <h4 class="font-bold text-white text-sm mb-1 line-clamp-2">${m.title}</h4>
                  <p class="text-xs text-slate-400 line-clamp-3 mb-3">${m.description || 'No additional instructions provided.'}</p>
                </div>
                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                  <span class="font-mono text-[11px]">${m.target_class_date ? 'Date: ' + m.target_class_date : 'Uploaded: ' + (m.created_at ? m.created_at.substring(0,10) : '')}</span>
                  <a ${linkAttr} onclick="markMaterialAsRead(${m.id})" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 font-bold rounded-lg border border-blue-500/30 transition-all flex items-center gap-1 no-underline">
                    <span>View File</span>
                    <span class="material-symbols-rounded text-xs">open_in_new</span>
                  </a>
                </div>
              </div>
            `;
          });
          container.innerHTML = html;
        })
        .catch(err => {
          container.innerHTML = '<div class="col-span-full text-center text-rose-400 py-6 text-xs font-semibold">Failed to load learning materials.</div>';
        });
    }

    function markMaterialAsRead(id) {
      fetch(`/api/student/materials/${id}/read`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      }).catch(err => console.error(err));
    }
  </script>

  <!-- COMPULSORY FIRST LOGIN SETUP MODAL -->
  @if(session('must_update_profile'))
  <div id="firstLoginProfileModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] flex items-center justify-center p-4 transition-all">
    <div class="bg-slate-900 border border-slate-700/80 rounded-3xl w-full max-w-lg p-6 sm:p-8 shadow-2xl space-y-5 fade-up">
      <div class="text-center space-y-1.5 border-b border-slate-800 pb-4">
        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-amber-500/20">
          <span class="material-symbols-rounded text-3xl text-white">lock_reset</span>
        </div>
        <h3 class="font-black text-xl text-white tracking-tight">Complete Student Profile Setup</h3>
        <p class="text-xs text-slate-400">
          Welcome to Carmel Linx! Because your account was initialized with the common default password, please update your details to activate your student portal.
        </p>
      </div>

      <form id="firstLoginProfileForm" onsubmit="handleFirstLoginProfileSetup(event)" class="space-y-4">
        <!-- New Password & Confirmation -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password *</label>
            <input type="password" id="setupNewPassword" required minlength="6" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="Min 6 characters">
          </div>
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Confirm Password *</label>
            <input type="password" id="setupConfirmPassword" required minlength="6" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="Re-enter new password">
          </div>
        </div>

        <!-- Email & Mobile -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address *</label>
            <input type="email" id="setupEmail" required value="{{ session('userEmail') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="student@gmail.com">
          </div>
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile Number</label>
            <input type="text" id="setupPhone" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="10-digit mobile number">
          </div>
        </div>

        <!-- SBTE Register Number & Photo Upload -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">SBTE Register No</label>
            <input type="text" id="setupSbteReg" value="{{ session('sbteRegNo') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="e.g. 2601004613">
          </div>
          <div>
            <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Profile Photo</label>
            <input type="file" id="setupPhoto" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-2 py-1.5 text-xs text-slate-300 focus:border-amber-500 outline-none">
          </div>
        </div>

        <div id="setupAlert" class="hidden p-3.5 rounded-xl text-xs font-bold border"></div>

        <button type="submit" id="btnSubmitFirstSetup" class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2 cursor-pointer mt-2">
          <span class="material-symbols-rounded text-lg">verified_user</span> Save Credentials &amp; Unlock Dashboard
        </button>
      </form>
    </div>
  </div>

  <script>
    function handleFirstLoginProfileSetup(e) {
      e.preventDefault();
      const pass = document.getElementById('setupNewPassword').value.trim();
      const confirmPass = document.getElementById('setupConfirmPassword').value.trim();
      const email = document.getElementById('setupEmail').value.trim();
      const alertDiv = document.getElementById('setupAlert');
      const submitBtn = document.getElementById('btnSubmitFirstSetup');

      if (pass !== confirmPass) {
        alertDiv.className = "p-3.5 rounded-xl text-xs font-bold bg-rose-950/60 text-rose-300 border border-rose-800 block";
        alertDiv.innerText = "Passwords do not match. Please re-enter carefully.";
        alertDiv.classList.remove('hidden');
        return;
      }

      if (pass === 'carmel2026') {
        alertDiv.className = "p-3.5 rounded-xl text-xs font-bold bg-rose-950/60 text-rose-300 border border-rose-800 block";
        alertDiv.innerText = "New password cannot be the default password 'carmel2026'.";
        alertDiv.classList.remove('hidden');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span class="material-symbols-rounded text-lg animate-spin">sync</span> Saving Profile Setup...`;

      const formData = new FormData();
      formData.append('new_password', pass);
      formData.append('email', email);
      formData.append('phone', document.getElementById('setupPhone').value.trim());
      formData.append('sbte_reg_no', document.getElementById('setupSbteReg').value.trim());
      
      const photoInput = document.getElementById('setupPhoto');
      if (photoInput.files && photoInput.files[0]) {
        formData.append('photo', photoInput.files[0]);
      }

      fetch('/api/student/complete-first-login-profile', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-rounded text-lg">verified_user</span> Save Credentials & Unlock Dashboard`;

        if (data.status === 'SUCCESS') {
          alertDiv.className = "p-3.5 rounded-xl text-xs font-bold bg-emerald-950/60 text-emerald-300 border border-emerald-800 block";
          alertDiv.innerText = "✓ " + data.message;
          alertDiv.classList.remove('hidden');

          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          alertDiv.className = "p-3.5 rounded-xl text-xs font-bold bg-rose-950/60 text-rose-300 border border-rose-800 block";
          alertDiv.innerText = "Error: " + data.message;
          alertDiv.classList.remove('hidden');
        }
      })
      .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-rounded text-lg">verified_user</span> Save Credentials & Unlock Dashboard`;
        alertDiv.className = "p-3.5 rounded-xl text-xs font-bold bg-rose-950/60 text-rose-300 border border-rose-800 block";
        alertDiv.innerText = "Connection error: " + err.message;
        alertDiv.classList.remove('hidden');
      });
    }
  </script>
  @endif
</body>
</html>

