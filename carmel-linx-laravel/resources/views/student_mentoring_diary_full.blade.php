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
        font-size: 13px !important;
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
    html {
      font-size: 90%;
    }
    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    body { font-family: 'Inter', system-ui, sans-serif; }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
    }
    .text-lg {
      font-size: 1.05rem !important;
    }
    .text-base {
      font-size: 0.875rem !important;
    }
    nav.space-y-1\.5 > :not([hidden]) ~ :not([hidden]) {
      margin-top: 0.125rem !important;
    }
    nav.space-y-1\.5 a, nav.space-y-1\.5 button {
      padding-top: 0.375rem !important;
      padding-bottom: 0.375rem !important;
    }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }

    /* Compact Sidebar Navigation Sizing Standard (Matching Staff Console) */
    @media (min-width: 768px) {
      aside nav {
        padding: 0.75rem !important;
      }
      aside nav > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0.125rem !important;
      }
      aside nav a, aside nav button, aside nav div {
        padding-top: 0.375rem !important;
        padding-bottom: 0.375rem !important;
        padding-left: 0.875rem !important;
        padding-right: 0.875rem !important;
        font-size: 11px !important;
        gap: 0.625rem !important;
      }
      aside nav span.material-symbols-rounded {
        font-size: 16px !important;
      }
    }

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
@php
  $isLet = session('userAdmissionType') === 'LET';
  $activityGoal = $isLet ? 40 : 60;
@endphp
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
          <img id="sidebarStudentImg" src="{{ session('userPhoto') }}" class="w-10 h-10 rounded-full border border-slate-700 object-cover shadow-inner">
        @else
          <div id="sidebarStudentPlaceholder" class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-sky-700 flex items-center justify-center font-black shadow text-xs">
            {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
          </div>
        @endif
        <div class="overflow-hidden">
          <span class="font-bold text-xs block truncate text-slate-200 leading-tight">{{ session('userName') }}</span>
          <span class="text-[10px] font-bold text-teal-400 block font-mono">{{ session('userId') }}</span>
          <span class="text-[10px] text-slate-500 font-semibold">{{ session('userBranch') }} &bull; Student</span>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-grow p-4 space-y-1.5">
      <a href="/dashboard/student?tab=exams" id="navExams" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">checklist</span> Works To Do
      </a>
      <a href="/dashboard/student?tab=marks" id="navMarks" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">bar_chart_4_bars</span> Academic Stats
      </a>
      <a href="/dashboard/student?tab=profile" id="navProfile" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">manage_accounts</span> My Profile
      </a>
      <div id="navMentoring" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs">
        <span class="material-symbols-rounded text-base">menu_book</span> Mentoring Diary
      </div>
      <a href="/dashboard/student?tab=activity" id="navActivity" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">star</span> Activity Points
      </a>
      <a href="/dashboard/student?tab=seminar" id="navSeminar" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">co_present</span> My Seminar
      </a>
      <a href="/student/mock-test" target="_blank" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-teal-300 hover:bg-blue-950/20 cursor-pointer text-xs no-underline">
        <span class="material-symbols-rounded text-base text-teal-400 animate-pulse">rocket_launch</span> Mock Practice Test
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="{{ url('/logout') }}" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-xs">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-grow flex flex-col overflow-hidden">

    <!-- Top Header -->
    <header class="bg-slate-950/40 border-b border-slate-800/80 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 z-10 shadow-lg">
        <div class="flex items-center gap-3">
          <a href="/dashboard/student" class="p-2 rounded-xl bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 border border-blue-500/30 flex items-center gap-1.5 font-bold text-xs no-underline transition-premium" title="Return to Student Dashboard">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Dashboard</span>
          </a>
          <button onclick="toggleMobileSidebar()" class="md:hidden p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition-colors focus:outline-none flex items-center justify-center">
            <span class="material-symbols-rounded">menu</span>
          </button>
          <div>
            <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Mentoring Diary</h1>
            <p class="font-bold text-slate-400 mt-0.5" id="panelSubtitle">View and update your complete mentoring profile.</p>
          </div>
        </div>
        <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto justify-between sm:justify-end">
          <div class="bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2 font-black uppercase tracking-wider text-slate-400 flex flex-wrap items-center gap-4 text-xs">
            <span>Branch: <strong class="text-slate-200">{{ session('userBranch', '-') }}</strong></span>
            <span>Batch: <strong class="text-slate-200">{{ session('classroomId', '-') }}</strong>
              @if(str_contains(session('classroomId', ''), '_LET'))
                <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-[10px] px-1.5 py-0.5 rounded ml-1 uppercase">LET</span>
              @endif
            </span>
            <span id="headerSemesterText" class="hidden">Sem: <strong class="text-slate-200" id="headerSemValue">-</strong></span>
          </div>
          <!-- Mobile Only Save Button near Branch & Batch title -->
          <button onclick="saveStudentMentoringData()" class="md:hidden px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg text-xs flex-shrink-0">
            <span class="material-symbols-rounded text-base">save</span>
            <span>Save</span>
          </button>
        </div>
      </header>

    
<!-- Mentoring Diary Panel -->
<div class="flex-grow overflow-y-auto p-6 md:p-8">
<div id="panelMentoring" class="fade-up space-y-6">
  
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl">
    <div>
      <h3 class="font-black text-slate-200 flex items-center gap-2 text-lg">
        <span class="material-symbols-rounded text-blue-400">menu_book</span> My Mentoring Diary
      </h3>
      <p class="text-slate-400 mt-1 text-sm">Keep your profile updated. Your mentor will verify these details.</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full md:w-auto justify-start md:justify-end">
      <button onclick="downloadMentoringPdf()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-2 shadow-lg text-sm">
        <span class="material-symbols-rounded text-sm">download</span> Download PDF
      </button>
      <button onclick="saveStudentMentoringData()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-2 shadow-lg text-sm">
        <span class="material-symbols-rounded text-sm">save</span> Save Changes
      </button>
    </div>
  </div>

  <!-- Student Quick Info Header Card -->
  <div class="bg-gradient-to-r from-slate-950/60 to-indigo-950/20 border border-slate-800/80 p-6 rounded-2xl flex flex-col sm:flex-row items-center gap-5 shadow-xl fade-up">
    <div class="flex-shrink-0">
      <div id="diaryStudentPhotoContainer">
        <!-- Student View: Direct session photo or fallback -->
        @if(session('userPhoto'))
          <img src="{{ session('userPhoto') }}" class="w-20 h-20 rounded-2xl border-2 border-indigo-500/40 object-cover shadow-2xl">
        @else
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center font-black text-2xl text-white shadow-lg border border-indigo-500/30">
            {{ strtoupper(substr(session('userName', 'S'), 0, 2)) }}
          </div>
        @endif
      </div>
    </div>
    <div class="text-center sm:text-left flex-grow space-y-1">
      <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-2">
        <h2 class="font-black text-white text-xl tracking-tight" id="diaryHeaderStudentName">
          {{ session('userName') }}
        </h2>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 self-center">
          Active Student
        </span>
      </div>
      <div class="flex flex-wrap justify-center sm:justify-start items-center gap-x-4 gap-y-1 text-sm text-slate-400 font-semibold">
        <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-base text-indigo-400">badge</span> <span id="diaryHeaderStudentSbteLabel">{{ session('sbteRegNo') ? 'PRN No:' : 'Reg No:' }}</span> <strong class="text-slate-200 font-mono" id="diaryHeaderStudentSbteNo">{{ session('sbteRegNo') ?: session('userId') }}</strong></span>
        <span class="hidden sm:inline text-slate-600">&bull;</span>
        <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-base text-indigo-400">auto_stories</span> Sem: <strong class="text-slate-200" id="diaryHeaderStudentSem">S{{ session('userSemester', session('semester', '1')) }}</strong></span>
        <span class="hidden sm:inline text-slate-600">&bull;</span>
        <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-base text-indigo-400">school</span> Branch: <strong class="text-slate-200" id="diaryHeaderStudentBranch">{{ session('userBranch', '-') }}</strong></span>
        <span class="hidden sm:inline text-slate-600">&bull;</span>
        <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-base text-indigo-400">meeting_room</span> Batch: <strong class="text-slate-200" id="diaryHeaderStudentBatch">{{ session('classroomId', '-') }}</strong></span>
      </div>
    </div>
    <!-- Mobile Save Button placed on Quick Info Card near Branch & Batch -->
    <div class="md:hidden w-full flex justify-center pt-2">
      <button onclick="saveStudentMentoringData()" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center justify-center gap-2 shadow-lg text-sm">
        <span class="material-symbols-rounded text-base">save</span> Save Changes
      </button>
    </div>
  </div>

  <!-- Mentoring Horizontal Tabs Header -->
  <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-2 flex items-center gap-2 overflow-x-auto no-scrollbar shadow-inner">
    <button onclick="switchStudentMentoringTab('smdProfile')" id="tabBtn_smdProfile" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab bg-slate-800/80 text-blue-400 text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">person</span> Personal Info
    </button>
    <button onclick="switchStudentMentoringTab('smdFamily')" id="tabBtn_smdFamily" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">family_restroom</span> Family Details
    </button>
    <button onclick="switchStudentMentoringTab('smdEducation')" id="tabBtn_smdEducation" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">school</span> Prior Education
    </button>
    <button onclick="switchStudentMentoringTab('smdAcademic')" id="tabBtn_smdAcademic" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">monitoring</span> Academic Progress
    </button>
    <button onclick="switchStudentMentoringTab('smdBoard')" id="tabBtn_smdBoard" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">assignment</span> Board Exams
    </button>
    <button onclick="switchStudentMentoringTab('smdExtra')" id="tabBtn_smdExtra" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">workspace_premium</span> Extracurricular
    </button>
    <button onclick="switchStudentMentoringTab('smdLeave')" id="tabBtn_smdLeave" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">event_busy</span> Leave Records
    </button>
    <button onclick="switchStudentMentoringTab('smdMeetings')" id="tabBtn_smdMeetings" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">forum</span> Mentor Meetings
    </button>
  </div>

  <!-- Full-Width Tab Content -->
  <div class="w-full bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 min-h-[400px]">
      
      <!-- Personal Info Tab -->
      <div id="smdProfile" class="smd-content-pane space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Additional Personal Info</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Annual Income</label>
            <input type="text" id="smd_annual_income" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. ?2,00,000">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Residential Status</label>
            <select id="smd_residential_status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="Day Scholar">Day Scholar</option>
              <option value="Hosteller">Hosteller</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Scholarships (if any)</label>
            <input type="text" id="smd_scholarships" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. E-Grantz">
          </div>
          <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" id="smd_fee_waiver" class="rounded bg-slate-900 border-slate-700 text-blue-500 focus:ring-blue-500 focus:ring-2">
            <label class="text-slate-300 font-bold text-sm text-sm">Fee Waiver Student</label>
          </div>
        </div>

        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 mt-8 text-sm">Guardian Details</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Guardian Name</label>
            <input type="text" id="smd_guardian_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Relationship</label>
            <input type="text" id="smd_guardian_relationship" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Mobile No</label>
            <input type="text" id="smd_guardian_mobile" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div class="md:col-span-2">
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Permanent Address</label>
            <textarea id="smd_guardian_address" rows="3" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 resize-none text-sm"></textarea>
          </div>
        </div>
      
        <!-- Extended Profile Info -->
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 mt-8 text-sm">Extended Profile Details</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Gender</label>
            <select id="mdGender" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Religion</label>
            <input type="text" id="mdReligion" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. Hindu">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Caste</label>
            <input type="text" id="mdCaste" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. General">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Special Category / Reservation</label>
            <input type="text" id="mdReservation" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. EWS">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Quota</label>
            <select id="mdQuota" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="">None</option>
              <option value="NCC">NCC</option>
              <option value="ITI">ITI</option>
              <option value="VHSE">VHSE</option>
              <option value="THSLC">THSLC</option>
              <option value="Armed Force">Armed Force</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Physically Disabled?</label>
            <select id="mdIsDisabled" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Disability Category</label>
            <input type="text" id="mdDisabilityCat" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="If yes, specify">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Guardian Occupation</label>
            <input type="text" id="mdGuardianOcc" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Monthly Family Income</label>
            <input type="text" id="mdFamilyIncome" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. 50000">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Vehicle Pass Holder?</label>
            <select id="mdVehiclePass" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Vehicle Pass ID</label>
            <input type="text" id="mdVehiclePassId" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="If yes, specify">
          </div>
          <div class="col-span-1 md:col-span-2 lg:col-span-2">
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Communication Address</label>
            <textarea id="mdCommAddress" rows="3" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 resize-none text-sm min-h-[80px]"></textarea>
          </div>
        </div>
      </div>

      <!-- Family Details Tab -->
      <div id="smdFamily" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Family Members</h4>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left border-collapse text-sm text-sm">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Name</th>
                <th class="p-3">Relationship</th>
                <th class="p-3">Education</th>
                <th class="p-3">Occupation</th>
                <th class="p-3">Contact</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody id="smdFamilyList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
        <button onclick="addFamilyRow()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded font-bold cursor-pointer">+ Add Family Member</button>
      </div>

      <!-- Prior Education Tab -->
      <div id="smdEducation" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Educational Background</h4>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left border-collapse text-sm text-sm">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Course/Standard</th>
                <th class="p-3">Institution</th>
                <th class="p-3">Year</th>
                <th class="p-3">Total % / Grade</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody id="smdEducationList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
        <button onclick="addEducationRow()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded font-bold cursor-pointer">+ Add Education Record</button>
      </div>

      <!-- Academic Progress Tab -->
      <div id="smdAcademic" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Internal Progress Report</h4>
        <p class="text-slate-400 mb-4 text-sm text-sm">These marks are generated automatically from your classroom assessments.</p>
        <div id="smdAcademicReport" class="space-y-6">
          <!-- JS rendered academic tables (CO tests, assignments) -->
        </div>
      </div>

      <!-- Board Exams Tab -->
      <div id="smdBoard" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-center border-b border-slate-800/60 pb-2 mb-4">
          <h4 class="text-sm font-bold text-white">Board Exam Results</h4>
          <div class="flex items-center gap-2">
            <label class="text-sm text-slate-400 font-bold uppercase tracking-wider">Select Semester:</label>
            <select id="smdBoardSemSelect" class="bg-slate-900 border border-slate-700 rounded px-3 py-1.5 text-sm text-white font-bold outline-none focus:border-blue-500" onchange="renderStudentBoardExams()">
              <option value="">-- Choose --</option>
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-sm border-collapse min-w-[700px]">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60 uppercase tracking-wider text-sm font-bold">
                <th class="p-3 w-28">Sub Code</th>
                <th class="p-3">Subject Name</th>
                <th class="p-3 w-36">Exam Month/Yr</th>
                <th class="p-3 w-20">Grade</th>
                <th class="p-3 w-24">Passed</th>
                <th class="p-3 w-24">Chances</th>
              </tr>
            </thead>
            <tbody id="smdSubjectBoardList">
              <tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Extracurricular Tab -->
      <div id="smdExtra" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <h4 class="text-sm font-bold text-white">Extracurricular Achievements</h4>
            <button onclick="openStudentActivityModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition-premium cursor-pointer flex items-center gap-1"><span class="material-symbols-rounded text-sm">add</span> Add Activity</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="text-sm font-black text-slate-200">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="studentActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between text-sm font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="text-base font-black text-amber-400" id="studentTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="studentActivitySplitList">
                <div class="text-sm text-slate-500 py-1">Loading...</div>
              </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Sem</th>
                <th class="p-3 w-1/3">Activity Name</th>
                <th class="p-3">Level / Segment</th>
                <th class="p-3">Pts Claimed</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
              </tr>
            </thead>
            <tbody id="smdExtraList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
      </div>

      
      <!-- Leave Records Tab -->
      <div id="smdLeave" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-center border-b border-slate-800/60 pb-2 mb-4">
            <h4 class="font-bold text-white text-sm">Leave Records</h4>
            <button onclick="openLeaveModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-sm text-sm">
              <span class="material-symbols-rounded text-sm">add</span> Log Leave
            </button>
        </div>
        <div class="overflow-x-auto bg-slate-900/50 border border-slate-700 rounded-xl">
          <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/80 text-slate-400 font-black uppercase">
              <tr>
                <th class="p-3">Semester</th>
                <th class="p-3">Date</th>
                <th class="p-3">Reason</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="smdLeavesTable" class="text-slate-300">
              <tr><td colspan="5" class="p-6 text-center text-slate-500">No leave records.</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Mentor Meetings Tab -->
      <div id="smdMeetings" class="smd-content-pane hidden space-y-4">
        <h4 class="text-sm font-bold text-white border-b border-slate-800/60 pb-2 mb-4">Mentor Remarks</h4>
        <p class="text-sm text-slate-400 mb-4">These logs are maintained by your mentor.</p>
        <div id="smdMeetingsList" class="space-y-4">
          <!-- JS rendered meetings -->
        </div>
      </div>

    </div>
  </div>
</div>


  <!-- STUDENT ACTIVITY MODAL -->
  <div id="addStudentActivityModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-sm font-black text-white" id="studentActivityModalTitle">Add Activity</h3>
        <button onclick="closeStudentActivityModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
      </div>
      <form id="studentActivityForm" onsubmit="saveStudentActivity(event)">
        <input type="hidden" id="studentActivityId">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Semester</label>
            <select id="studentActivitySemester" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Segment</label>
            <select id="studentActivitySegment" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
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
          <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Activity Name</label>
            <input type="text" id="studentActivityName" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Level (e.g. State, College)</label>
            <input type="text" id="studentActivityLevel" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Points Claimed</label>
            <input type="number" id="studentActivityPtsClaimed" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
          </div>
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-sm">Submit Activity for Verification</button>
        </div>
      </form>
    </div>
  </div>

</div>
</main>

  <script>
    function switchPanel(panelId, title) {
      const panels = ['exams', 'marks', 'profile', 'mentoring', 'activity'];
      
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

      const titles = { exams: 'Works To Do', marks: 'Academic Stats', profile: 'My Profile', mentoring: 'Mentoring Diary', activity: 'Activity Points' };
      const subtitles = { 
        exams: 'Manage your pending assignments and active tests.', 
        marks: 'Your semester-wise academic progress.', 
        profile: 'Your personal and academic details.',
        mentoring: 'Mentoring sessions and student data.',
        activity: 'Track and claim your extracurricular points.'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];
      document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'mentoring') {
        if (!mentoringLoaded) loadMentoringDiary();
      } else if (panelId === 'activity') {
        loadActivityPoints();
      }
    }

      document.addEventListener('DOMContentLoaded', () => {
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
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            document.getElementById('overallCgpa').innerText = overall.cgpa || '0';
            document.getElementById('overallActivityPoints').innerText = overall.activity_points || '0';
            if (overall.current_semester) {
              document.getElementById('headerSemesterText').classList.remove('hidden');
              document.getElementById('headerSemValue').innerText = overall.current_semester;
            }
            currentActiveSem = data.overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || []);
            renderCgpaChart(data.semesters);
            renderSemesterTabs(data.semesters);
            renderGodTable(currentActiveSem);
          }
        });
    }

    function renderActiveTasks(tasks) {
      const container = document.getElementById('studentActiveTasksContainer');
      if (!tasks || tasks.length === 0) {
        container.innerHTML = `<div class="col-span-full py-12 text-center text-slate-500 font-bold text-sm">No active assignments or tests at the moment.</div>`;
        return;
      }
      
      let html = '';
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

        html += `
          <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl overflow-hidden mb-1">
            <!-- Collapsible Header -->
            <div onclick="document.getElementById('co_task_${index}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_task_${index}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                 class="px-4 py-3.5 bg-slate-950/40 hover:bg-slate-950/70 border-b border-slate-800/60 flex justify-between items-center cursor-pointer transition-premium">
              <div class="flex items-center gap-3">
                <span class="material-symbols-rounded text-blue-400 text-sm">${icon}</span>
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
                  <div>Start Date: <span class="text-slate-200 font-bold">${t.start ? new Date(t.start).toLocaleDateString() : '-'}</span></div>
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
      container.innerHTML = html;
      container.className = "flex flex-col gap-1 mt-4 mb-6";
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
        const isActive = s.semester === currentActiveSem;
        const isCurrent = s.is_current === true;
        const cls = isActive 
          ? 'bg-blue-600/20 text-blue-400 border-blue-500/20' 
          : 'bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
        const badge = isCurrent ? `<span class="ml-1 text-[8px] bg-teal-500/20 text-teal-400 px-1 py-0.5 rounded font-black">NOW</span>` : '';
        html += `
          <button onclick="renderGodTable(${s.semester})" id="btnSemTab_${s.semester}" class="sem-tab px-4 py-2 rounded-lg text-sm font-black transition-premium border ${cls}">
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
        container.innerHTML = `<div class="py-12 text-center text-slate-500 font-bold text-sm border border-slate-800/50 rounded-2xl bg-slate-900/30">No academic data available for Semester ${semId}.</div>`;
        return;
      }

      let rows = '';
      semData.subjects.forEach(sub => {
        const trClass = "border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium";
        rows += `
          <tr class="${trClass}">
            <td class="p-4 whitespace-nowrap">
              <div class="font-black text-slate-200 text-sm">${sub.subject_code}</div>
              <div class="text-sm text-slate-500 font-bold truncate max-w-[150px]" title="${sub.subject_name}">${sub.subject_name}</div>
            </td>
            <td class="p-4 text-center text-sm font-mono font-bold text-slate-300">${sub.CO1 !== null ? sub.CO1 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO2 !== null ? sub.CO2 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-slate-300">${sub.CO3 !== null ? sub.CO3 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO4 !== null ? sub.CO4 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-blue-400 border-l border-slate-800">${sub.Assg1 !== null ? sub.Assg1 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-blue-400">${sub.Assg2 !== null ? sub.Assg2 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-blue-400">${sub.Assg3 !== null ? sub.Assg3 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-bold text-blue-400">${sub.Assg4 !== null ? sub.Assg4 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-emerald-400 border-l border-slate-800">${sub.WT1 !== null ? sub.WT1 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-emerald-400">${sub.WT2 !== null ? sub.WT2 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-emerald-400">${sub.WT3 !== null ? sub.WT3 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-emerald-400">${sub.WT4 !== null ? sub.WT4 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-purple-400 border-l border-slate-800">${sub.OT1 !== null ? sub.OT1 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-purple-400">${sub.OT2 !== null ? sub.OT2 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-purple-400">${sub.OT3 !== null ? sub.OT3 : '-'}</td>
            <td class="p-4 text-center text-sm font-mono font-black text-purple-400">${sub.OT4 !== null ? sub.OT4 : '-'}</td>
            <td class="p-4 text-center text-sm font-black border-l border-slate-800 ${sub.attendance_percentage < 75 ? 'text-rose-400' : 'text-slate-300'}">
              ${sub.attendance_percentage}%
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
          <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
              <tr class="bg-slate-900/80 border-b border-slate-800 text-sm uppercase tracking-wider font-black text-slate-400">
                <th class="p-4 font-black">Subject</th>
                <th class="p-4 text-center" colspan="4">Sum COs</th>
                <th class="p-4 text-center border-l border-slate-800 text-blue-400" colspan="4">Assignments</th>
                <th class="p-4 text-center border-l border-slate-800 text-emerald-400" colspan="4">Written Tests</th>
                <th class="p-4 text-center border-l border-slate-800 text-purple-400" colspan="4">Online Tests</th>
                <th class="p-4 text-center border-l border-slate-800">Attend.</th>
              </tr>
              <tr class="bg-slate-900/40 border-b border-slate-800/50 text-sm uppercase font-bold text-slate-500">
                <th class="p-2"></th>
                <th class="p-2 text-center w-10 border-l border-slate-800/50">C1</th><th class="p-2 text-center w-10 bg-slate-950/20">C2</th><th class="p-2 text-center w-10">C3</th><th class="p-2 text-center w-10 bg-slate-950/20">C4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">A1</th><th class="p-2 text-center w-10">A2</th><th class="p-2 text-center w-10">A3</th><th class="p-2 text-center w-10">A4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">W1</th><th class="p-2 text-center w-10">W2</th><th class="p-2 text-center w-10">W3</th><th class="p-2 text-center w-10">W4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">O1</th><th class="p-2 text-center w-10">O2</th><th class="p-2 text-center w-10">O3</th><th class="p-2 text-center w-10">O4</th>
                <th class="p-2 text-center w-16 border-l border-slate-800">%</th>
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
          if (!container) return;
          if (data.status === 'SUCCESS' && data.tests && data.tests.length > 0) {
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
                    <div class="grid grid-cols-2 gap-4 mb-4 text-slate-400 font-semibold">
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
            container.className = "flex flex-col gap-1 mt-4 mb-6";
          } else {
            container.innerHTML = `<div class="col-span-full p-4 bg-slate-900/60 border border-slate-800/60 rounded-xl text-center text-sm text-slate-500">No active tests available right now.</div>`;
            container.className = "mt-4 mb-6";
          }

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
            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-700/50 bg-slate-900/50 cursor-pointer hover:border-purple-500/50 hover:bg-slate-800 transition-premium">
              <input type="radio" name="q_${idx}" value="${opt}" class="w-4 h-4 text-purple-500 bg-slate-950 border-slate-600 focus:ring-purple-600">
              <span class="text-sm text-slate-300">${opt}</span>
            </label>
          `;
        });
        html += `
          <div class="question-container bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
             <div class="flex items-start gap-4 mb-4">
               <span class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-sm border border-purple-500/20">${idx+1}</span>
               <h4 class="text-sm font-bold text-slate-100 mt-1">${q.q}</h4>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-12">
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
                  badgeHtml = '<span class="text-sm bg-green-500/20 text-green-400 font-bold px-2 py-0.5 rounded ml-auto">Correct Answer</span>';
                } else if (opt === q.student_ans) {
                  borderClass = 'border-red-500/50 bg-red-950/20';
                  badgeHtml = '<span class="text-sm bg-red-500/20 text-red-400 font-bold px-2 py-0.5 rounded ml-auto">Your Answer</span>';
                }

                optionsHtml += `
                  <div class="flex items-center gap-3 p-3 rounded-lg border ${borderClass} transition-premium">
                    <span class="text-sm text-slate-300">${opt}</span>
                    ${badgeHtml}
                  </div>
                `;
              });

              let correctBadge = q.is_correct 
                ? '<span class="bg-green-500/10 text-green-400 text-sm font-bold px-2.5 py-1 rounded-full border border-green-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-sm">check_circle</span> Correct</span>'
                : `<span class="bg-red-500/10 text-red-400 text-sm font-bold px-2.5 py-1 rounded-full border border-red-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-sm">cancel</span> Incorrect</span>`;

              html += `
                <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
                   <div class="flex items-start justify-between gap-4 mb-4">
                     <div class="flex items-start gap-4">
                       <span class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center font-black text-sm border border-slate-700/20">${idx+1}</span>
                       <div>
                         <h4 class="text-sm font-bold text-slate-100 mt-1">${q.q}</h4>
                         <span class="text-sm text-slate-500 font-mono">CO Tag: ${q.co}</span>
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
                    <span class="text-sm text-slate-300">${segment}</span>
                    <span class="text-sm font-bold text-emerald-400">${pts}</span>
                  </div>
                `;
              }
            } else {
              splitHtml = '<div class="text-sm text-slate-500 py-1">No verified points yet.</div>';
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
                  noteHtml = `<div class="mt-1 text-sm text-rose-400/80 leading-tight">Reason: ${c.rejection_note}</div>`;
                }
                if (c.status !== 'Pending' && verifiedDateStr) {
                  noteHtml += `<div class="mt-0.5 text-sm text-slate-500">On: ${verifiedDateStr}</div>`;
                }
                
                html += `
                  <tr class="hover:bg-slate-900/50 transition-colors border-b border-slate-800/40">
                    <td class="p-3 text-sm text-slate-400">${dateStr}</td>
                    <td class="p-3 text-sm font-bold text-slate-300">${c.activity_segment}</td>
                    <td class="p-3 text-sm text-slate-300">${c.activity_name}</td>
                    <td class="p-3 text-sm text-slate-400">${c.level}</td>
                    <td class="p-3">
                      ${c.document_reference ? `<a href="${c.document_reference}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm underline flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">link</span> View</a>` : '<span class="text-sm text-slate-600">None</span>'}
                    </td>
                    <td class="p-3 text-center text-sm font-bold text-slate-300">${c.points_claimed}</td>
                    <td class="p-3 text-center text-sm font-bold ${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}">${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class="p-3 text-right max-w-[120px]">
                      <span class="px-2 py-0.5 rounded border text-sm font-bold uppercase tracking-wider ${statusClass} inline-block">${c.status}</span>
                      ${noteHtml}
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm">No activity claims submitted yet.</td></tr>`;
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
  </script>

  <!-- LIVE TEST ENGINE MODAL (Hidden by default) -->
  <div id="testEngineModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
    <!-- Top Bar -->
    <div class="h-14 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
      <div class="flex items-center gap-3">
        <span class="material-symbols-rounded text-purple-500 text-base">devices</span>
        <div>
          <h3 id="liveTestName" class="font-bold text-sm text-white leading-tight">Test Name</h3>
          <span class="text-sm text-slate-400 font-mono" id="liveTestReg">{{ session('userId') }}</span>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <div class="bg-slate-950 border border-slate-800 px-4 py-1.5 rounded-full flex items-center gap-2 text-sm font-bold shadow-inner">
          <span class="material-symbols-rounded text-red-400 text-sm">timer</span>
          <span id="liveTimer" class="text-red-400 font-mono tracking-widest">00:00:00</span>
        </div>
        <button onclick="submitTest()" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-1.5 rounded-full font-bold text-sm transition-premium shadow-lg shadow-purple-600/20">Submit Final</button>
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
      <p class="text-sm text-slate-400 mb-6">Your responses have been saved securely.</p>
      
      <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
          <span class="text-sm uppercase font-black tracking-wider text-slate-500 block mb-1">Total Score</span>
          <span class="text-base font-black text-emerald-400" id="resultScore">0/0</span>
        </div>
        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
          <span class="text-sm uppercase font-black tracking-wider text-slate-500 block mb-1">Percentage</span>
          <span class="text-base font-black text-blue-400" id="resultPercent">0%</span>
        </div>
      </div>

      <button onclick="closeResultModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm transition-premium">Return to Dashboard</button>
    </div>
  </div>

  <!-- ANSWER KEY VIEW MODAL (Hidden by default) -->
  <div id="answerKeyModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
    <!-- Top Bar -->
    <div class="h-14 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
      <div class="flex items-center gap-3">
        <span class="material-symbols-rounded text-blue-400 text-base">menu_book</span>
        <div>
          <h3 id="answerKeyTestName" class="font-bold text-sm text-white leading-tight">Answer Key Review</h3>
          <span class="text-sm text-slate-400 font-mono block" id="answerKeyScoreInfo">Score: â</span>
        </div>
      </div>
      <button onclick="closeAnswerKeyModal()" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-1.5 rounded-full font-bold text-sm transition-premium">Close</button>
    </div>

    <!-- Content Area -->
    <div class="flex-grow overflow-y-auto p-6 md:p-12 animate-fade-in" id="answerKeyQuestionsContainer">
       <!-- Render questions, student answers, and correct answers here -->
    </div>
  </div>

  @include('student_mentoring_scripts')


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

document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadStudentMentoringDiary === 'function') {
        loadStudentMentoringDiary();
    }
});
</script>

    <!-- Add Leave Modal -->
    <div id="addLeaveModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
      <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
          <h3 class="font-black text-white text-lg" id="leaveModalTitle">Add Leave Record</h3>
          <button onclick="closeLeaveModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
        </div>
        <form id="leaveForm" onsubmit="saveLeave(event)">
          <input type="hidden" id="leaveId">
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-sm text-sm">Semester</label>
                <input type="number" id="leaveSem" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
              </div>
              <div>
                  <label class="block font-bold text-slate-400 mb-1 text-sm text-sm">From Date</label>
                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-sm text-sm">To Date</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-sm text-sm">No. of Days</label>
                  <input type="number" step="0.5" id="leaveDays" placeholder="e.g. 1, 0.5" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
            </div>
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-sm text-sm">Reason</label>
              <input type="text" id="leaveReason" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
            </div>
            <input type="hidden" id="leaveStatus" value="Pending">
            <div class="flex items-center gap-2">
              <input type="checkbox" id="leaveParent" class="rounded bg-slate-950 border-slate-800 text-indigo-500">
              <label class="font-bold text-slate-400 text-sm text-sm">Parent/Guardian Informed?</label>
            </div>
          </div>
          <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="closeLeaveModal()" class="px-4 py-2 text-slate-400 font-bold hover:text-white transition-colors text-sm text-sm">Cancel</button>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-premium text-sm text-sm shadow-lg">Save Record</button>
          </div>
        </form>
      </div>
    </div>

</body>
</html>

