<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Tutor Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- SheetJS for Excel (.xlsx/.xls) template download & parsing -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
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
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Compact Sidebar Navigation Sizing Standard (Enforcing Principal Desk Density) */
    @media (min-width: 768px) {
      aside nav {
        padding: 0.75rem !important;
      }
      aside nav > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0.125rem !important;
      }
      aside nav a, aside nav button {
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

    /* MOBILE-SPECIFIC SIDEBAR & CARD FIXES (MD breakpoint is 768px) */
    @media (max-width: 767px) {
      html, body {
        font-size: 15px !important;
      }
      p, span, a, button, input, select, textarea, td, th {
        font-size: 14px !important;
      }
      h1, .text-2xl {
        font-size: 20px !important;
      }
      h2, .text-xl {
        font-size: 18px !important;
      }
      h3, .text-lg {
        font-size: 16px !important;
      }

      /* Sidebar changes: multi-row horizontal block on mobile */
      aside {
        width: 100% !important;
        position: relative !important;
        border-right: none !important;
        border-bottom: 1px solid #1e293b !important;
        flex-direction: column !important; /* Stack rows vertically */
        align-items: stretch !important;
        padding: 0.75rem 1rem 0.5rem !important;
        gap: 0.75rem !important;
      }
      
      /* Make sidebar brand logo header container visible inline on Row 1 */
      aside > div.border-b {
        display: flex !important;
        border-bottom: none !important;
        padding: 0 !important;
        margin: 0 !important;
        align-items: center !important;
        gap: 0.5rem !important;
      }

      aside > div.border-b img {
        width: 2.25rem !important;
        height: 2.25rem !important;
      }

      aside > div.border-b h2 {
        font-size: 18px !important;
        font-weight: 900 !important;
      }

      aside > div.border-b span {
        display: none !important; /* Hide subtitle to keep Row 1 clean */
      }
      
      /* Make logout block sit inline on Row 1 (far right) with extra top offset spacing */
      aside > div.border-t {
        border-top: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        width: auto !important;
        position: absolute !important;
        right: 1rem !important;
        top: 0.85rem !important;
      }
      
      aside > div.border-t a {
        padding: 0.4rem 0.65rem !important;
        border-radius: 0.5rem !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        background-color: rgba(239, 68, 68, 0.18) !important;
        color: #f87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
      }

      /* Convert vertical nav list to an inline horizontal row on Row 2 with a dark gradient */
      aside nav {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
        padding: 0.4rem 0.5rem !important;
        margin: 0 !important;
        justify-content: space-between !important;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.8) 100%) !important;
        border: 1px solid rgba(51, 65, 85, 0.4) !important;
        border-radius: 0.75rem !important;
      }
      
      /* Reset standard padding on links/buttons for inline fit */
      aside nav a, aside nav button {
        padding: 0.4rem 0.65rem !important;
        margin: 0 !important;
        border-radius: 0.5rem !important;
        font-size: 13px !important; /* compact font to fit */
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        width: auto !important;
        border-left: none !important; /* Remove custom vertical border indicators */
      }
      
      /* Hide all links in mobile navigation except those explicitly marked as mobile-link */
      aside nav > :not(.mobile-link) {
        display: none !important;
      }
      
      /* Active profile avatar banner is too large on mobile - hide */
      #sidebarAvatarContainer, aside > div:nth-child(2) {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-x-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl md:sticky md:top-0 md:h-screen">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg">
      <div>
        <h2 class="font-black tracking-tight leading-tight text-white" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.3px; background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Carmel Linx</h2>
        <span class="text-slate-400 font-bold uppercase tracking-wider">Tutor Panel</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold block truncate text-slate-200 text-[10px] text-xs">{{ session('userName') }}</span>
        <span class="font-bold text-green-400 block uppercase tracking-wider">{{ session('userBranch') }} Tutor</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navRoster" onclick="switchPanel('roster')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">group</span> Supervised Class Roster
      </button>
      <button id="navRollNumbers" onclick="switchPanel('rollNumbers')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">format_list_numbered</span> Student Roll Numbers
      </button>

      <button id="navMentoring" onclick="switchPanel('mentoring')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">diversity_3</span> Mentoring Batches
      </button>
      <button id="navLeaveApproval" onclick="switchPanel('leaveApproval')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">approval</span> Leave Approval & Reports
      </button>
      <button id="navActivity" onclick="switchPanel('activity')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">verified</span> Activity Points
      </button>

      <a href="/staff/attendance-log" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs mobile-link">
         <span class="material-symbols-rounded text-lg">co_present</span> Class Attendance Log
      </a>

      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs mobile-link">
         <span class="material-symbols-rounded text-lg">event_note</span> My Leave & Attendance Log
      </a>

      @php
        $role = session('userRole');
        $backLink = '/dashboard/lecturer';
        if ($role === 'HOD') $backLink = '/dashboard/hod';
        if ($role === 'Demonstrator') $backLink = '/dashboard/demonstrator';
        if ($role === 'Trade_Instructor') $backLink = '/dashboard/tradeinstructor';
        if ($role === 'Workshop_Superintendent') $backLink = '/dashboard/workshop';
        if ($role === 'Gen_Dept_Coordinator_Aided') $backLink = '/dashboard/general-coordinator-aided';
        if ($role === 'Gen_Dept_Coordinator_Self_Finance') $backLink = '/dashboard/general-coordinator-sf';
      @endphp
      <a href="{{ $backLink }}" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mt-4 border border-slate-800 text-xs mobile-link">
        <span class="material-symbols-rounded text-lg">arrow_back</span> Back to Staff Console
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>

      <!-- Support Badge -->
      <div onclick="openStaffSupportModal()" class="p-2 bg-slate-950/60 hover:bg-slate-900 border border-slate-800/80 rounded-xl text-center select-none cursor-pointer transition-premium" title="Click to Request Remote Support Assist">
        <div class="flex items-center justify-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
          <span class="material-symbols-rounded text-xs text-blue-400">headset_mic</span> Live Assist
        </div>
        <div class="text-[11px] font-black text-slate-200 mt-0.5">Dhanush.A</div>
        <div class="text-[9px] text-slate-400 font-medium">Dept. of Electronics</div>
      </div>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow min-w-0 flex flex-col overflow-hidden relative min-h-screen">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-30 sticky top-0">
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Supervised Class Roster</h1>
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <div id="loadingIndicator" class="hidden items-center gap-2 text-slate-400 text-[10px] text-xs">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-4 sm:p-6 md:p-8 space-y-6 max-w-full">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl font-bold transition-premium border text-[10px] text-xs"></div>

      <!-- PANEL 1: ROSTER -->
      <div id="panelRoster" class="space-y-6">
        
        <!-- Directory Header -->
        <div onclick="toggleRoster()" class="flex justify-between items-center bg-slate-950/30 hover:bg-slate-900/60 border border-slate-800/40 p-4 rounded-2xl cursor-pointer transition-premium">
          <div class="flex items-center gap-3">
            <span id="rosterIcon" class="material-symbols-rounded text-blue-400 transition-transform duration-300 rotate-180">expand_more</span>
            <div>
              <h3 id="supervisedClassroomTitle" class="font-black text-slate-200 text-lg">Supervised Classroom Directory</h3>
              <p class="text-slate-400 mt-0.5 text-sm">Manage and review lifecycle states of students in your assigned classroom.</p>
            </div>
          </div>
        </div>

        <div id="rosterContent" class="space-y-6">
          <!-- Filters Console -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 sm:p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end max-w-full">
            <!-- Search input -->
            <div class="lg:col-span-4">
              <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Search Student</label>
              <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-xs" placeholder="Name, Register No, Mobile...">
            </div>
            <!-- Classroom filter -->
            <div id="classroomFilterContainer" class="lg:col-span-4">
              <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Assigned Classroom</label>
              <select id="filterClassroom" onchange="onSupervisedClassroomChange()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs cursor-pointer font-bold">
                <option value="">Loading classroom...</option>
              </select>
            </div>
            <!-- Semester filter -->
            <div class="lg:col-span-2">
              <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Filter Semester</label>
              <select id="filterSemester" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs cursor-pointer">
                <option value="">All Semesters</option>
                <option value="S1">Semester 1 (S1)</option>
                <option value="S2">Semester 2 (S2)</option>
                <option value="S3">Semester 3 (S3)</option>
                <option value="S4">Semester 4 (S4)</option>
                <option value="S5">Semester 5 (S5)</option>
                <option value="S6">Semester 6 (S6)</option>
              </select>
            </div>
            <!-- Status select -->
            <div class="lg:col-span-2">
              <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Account Status</label>
              <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs cursor-pointer">
                <option value="">All Statuses</option>
                <option value="Approved">Approved</option>
                <option value="Pending">Pending</option>
                <option value="Suspended">Suspended</option>
              </select>
            </div>
            <!-- Print Report Selector & Action Buttons -->
            <div class="lg:col-span-12 pt-2 border-t border-slate-800/40 flex flex-wrap items-center justify-between gap-2">
              <label class="block text-slate-400 font-bold uppercase tracking-wider text-xs">Class Roster Actions & Import</label>
              <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                <select id="printSemesterSelect" class="w-14 shrink-0 bg-slate-900 border border-slate-800 rounded-lg px-2 py-1.5 text-white focus:border-blue-500 outline-none text-xs font-semibold cursor-pointer">
                  <option value="S1">S1</option>
                  <option value="S2">S2</option>
                  <option value="S3" selected>S3</option>
                  <option value="S4">S4</option>
                  <option value="S5">S5</option>
                  <option value="S6">S6</option>
                </select>
                <button onclick="printClassRegister()" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-xs flex items-center gap-1 transition-premium cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-[15px]">print</span> Print
                </button>
                <button onclick="printStudentCredentials()" class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg font-semibold text-xs flex items-center gap-1 transition-premium cursor-pointer shadow-sm" title="Print Student First Login Credentials List">
                  <span class="material-symbols-rounded text-[15px]">key</span> Credentials
                </button>
                <button onclick="openBulkImportModal()" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-xs flex items-center gap-1 transition-premium cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-[15px]">upload_file</span> Import
                </button>
              </div>
            </div>
          </div>

        <!-- Users Table Grid -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden max-w-full">
          <div class="overflow-x-auto overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse relative text-xs">
              <thead class="sticky top-0 z-10 shadow-md">
                <tr class="bg-slate-900 border-b border-slate-800/60 text-slate-400 font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">
                  <th class="p-2.5">Name</th>
                  <th class="p-2.5">Reg No</th>
                  <th class="p-2.5">SBTE Reg No. / Edit</th>
                  <th class="p-2.5">Branch</th>
                  <th class="p-2.5">Sem</th>
                  <th class="p-2.5">Role</th>
                  <th class="p-2.5">Status</th>
                  <th class="p-2.5">Enrolled Status</th>
                  <th class="p-2.5 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <tr><td colspan="9" class="p-8 text-center text-slate-500 font-medium font-sans">No classroom students found.</td></tr>
                <!-- User rows render dynamically via JS -->
              </tbody>
            </table>
          </div>
        </div>
        </div> <!-- End rosterContent -->
      </div>

      <!-- PANEL: STUDENT ROLL NUMBERS -->
      <div id="panelRollNumbers" class="hidden space-y-6">
        <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-6 shadow-lg">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="font-black text-white text-lg">Assign Class Roll Numbers</h3>
              <p class="text-xs text-slate-400">Set the serial roll numbers for students in your supervised classroom.</p>
            </div>
            <div class="flex items-center gap-2">
              <button onclick="autoFillRollNumbers()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 rounded-xl font-bold text-sm flex items-center gap-1.5 transition-premium cursor-pointer">
                <span class="material-symbols-rounded text-sm">auto_awesome</span> Auto-Fill (A-Z)
              </button>
              <button onclick="saveRollNumbers()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm flex items-center gap-2 cursor-pointer transition-premium">
                <span class="material-symbols-rounded text-sm">save</span> Save Roll Numbers
              </button>
            </div>
          </div>
          <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-850 uppercase tracking-wider text-xs font-black">
                  <th class="p-4 w-16 text-center">No.</th>
                  <th class="p-4 w-40">Reg No</th>
                  <th class="p-4 w-48">SBTE Exam No</th>
                  <th class="p-4">Student Name</th>
                  <th class="p-4 w-32 text-center">Roll Number</th>
                </tr>
              </thead>
              <tbody id="tutorRollNumberList">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 2: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Classroom Audit Trail</h3>
            <p class="text-slate-400 mt-1 text-[10px] text-xs">Lifecycle events, password resets, and approval actions involving students in your classroom.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <!-- Audit Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left border-collapse text-[10px] text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target Student (ID)</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">IP Address</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="auditTableBody">
                <!-- Audit logs render dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: MY PROFILE -->
      <div id="panelProfile" class="hidden space-y-6">
        @include('partials.staff_profile_panel')
      </div>

      <!-- PANEL 4: MENTORING BATCHES -->
      <div id="panelMentoring" class="hidden space-y-6">
        <!-- Dashboard Header -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Mentoring Batches & Splitter</h3>
            <p class="text-slate-400 mt-1 text-[10px] text-xs">Split students between yourself and the second mentor.</p>
          </div>
          <div class="flex items-center gap-2">
            <select id="mentorClassroomSelect" onchange="loadMentoringData()" class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none text-[10px] text-xs">
              <option value="">Loading classrooms...</option>
            </select>
            <button onclick="loadMentoringData()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-2">
              <span class="material-symbols-rounded text-sm">sync</span> Refresh
            </button>
            <button onclick="printStudentCredentials()" class="px-4 py-2 bg-amber-600/90 hover:bg-amber-500 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-2 text-xs" title="Print Student First Login Credentials List">
              <span class="material-symbols-rounded text-sm">key</span> Credentials
            </button>
            <button onclick="generateBacklogReport()" class="px-4 py-2 bg-blue-900/50 hover:bg-blue-800/70 text-blue-300 border border-blue-800/50 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-2">
              <span class="material-symbols-rounded text-sm">summarize</span> Backlog Report
            </button>
          </div>
        </div>

        <!-- Collapsible Batch Assignment Panel -->
        <div class="bg-slate-950/20 border border-slate-800/40 rounded-2xl overflow-hidden mb-6">
          <div onclick="toggleBatchAssignment()" class="p-4 bg-slate-900/40 flex justify-between items-center cursor-pointer hover:bg-slate-900/60 transition-premium">
            <div class="flex items-center gap-2">
              <span id="batchAssignIcon" class="material-symbols-rounded text-indigo-400 transition-transform duration-300">expand_more</span>
              <h4 class="font-black text-xs text-slate-200 uppercase tracking-wider">Batch Assignment & Mentorship Splitter Settings</h4>
            </div>
            <span class="text-[10px] text-slate-500 font-medium">Click to configure Batch A & B assignments / unassigned students</span>
          </div>
          <div id="batchAssignmentContent" class="hidden p-6 border-t border-slate-800/40">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Unassigned Students -->
              <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-slate-800/60 bg-slate-900/40 flex justify-between items-center">
                  <div>
                    <h4 class="font-black text-xs text-slate-200 flex items-center gap-2"><span class="material-symbols-rounded text-amber-400 text-xs">person_off</span> Unassigned Students</h4>
                    <p class="text-xs text-slate-500">Students without a mentor.</p>
                  </div>
                  <span id="unassignedCountBadge" class="bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
                </div>
                <div class="flex-grow max-h-[300px] overflow-y-auto scrollbar-hidden">
                  <table class="w-full text-left text-xs">
                    <tbody id="unassignedList">
                      <tr><td class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Mentors Split View -->
              <div class="space-y-6">
                <!-- Mentor A (Tutor) -->
                <div class="bg-slate-950/30 border border-sky-900/40 rounded-2xl flex flex-col overflow-hidden">
                  <div class="p-4 border-b border-sky-900/60 bg-sky-950/20 flex justify-between items-center">
                    <div>
                      <h4 class="font-black text-xs text-sky-400 flex items-center gap-2"><span class="material-symbols-rounded text-xs">person_pin</span> Batch A (Tutor)</h4>
                      <p id="mentorAInfo" class="text-xs text-slate-400">Loading...</p>
                    </div>
                    <span id="batchACountBadge" class="bg-sky-500/20 text-sky-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
                  </div>
                  <div class="flex-grow max-h-[180px] overflow-y-auto scrollbar-hidden">
                    <table class="w-full text-left text-xs">
                      <tbody id="batchAList"></tbody>
                    </table>
                  </div>
                </div>

                <!-- Mentor B -->
                <div class="bg-slate-950/30 border border-emerald-900/40 rounded-2xl flex flex-col overflow-hidden">
                  <div class="p-4 border-b border-emerald-900/60 bg-emerald-950/20 flex justify-between items-center">
                    <div>
                      <h4 class="font-black text-xs text-emerald-400 flex items-center gap-2"><span class="material-symbols-rounded text-xs">supervisor_account</span> Batch B (Mentor)</h4>
                      <p id="mentorBInfo" class="text-xs text-slate-400">Loading...</p>
                    </div>
                    <span id="batchBCountBadge" class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
                  </div>
                  <div class="flex-grow max-h-[180px] overflow-y-auto scrollbar-hidden">
                    <table class="w-full text-left text-xs">
                      <tbody id="batchBList"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mentoring Caseload -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl space-y-4">
          <div class="flex items-center gap-3 border-b border-slate-800/60 pb-3 justify-between">
            <div class="flex items-center gap-3">
              <span class="material-symbols-rounded text-indigo-400 text-base">school</span>
              <h3 class="text-sm font-black text-slate-200">Mentoring Caseload (Data View)</h3>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              <span class="bg-cyan-500/10 text-cyan-400 text-xs px-2.5 py-1 rounded-full font-bold border border-cyan-500/20">📱 Mobile Parent Portal SMS Enabled</span>
              <p class="text-xs text-slate-400">Tutors see the full class; Mentors see only their batch.</p>
            </div>
          </div>
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                  <th class="p-3">Student</th>
                  <th class="p-3">Reg No</th>
                  <th class="p-3">Batch Assigned</th>
                  <th class="p-3">Diary Logs</th>
                  <th class="p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="myMentoringStudentsList">
                <tr><td colspan="5" class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL: ACTIVITY POINTS VERIFICATION -->
      <div id="panelActivity" class="hidden space-y-6">
        
        <!-- Activity Header -->
        <div onclick="toggleActivity()" class="flex justify-between items-center bg-slate-950/30 hover:bg-slate-900/60 border border-slate-800/40 p-5 rounded-2xl cursor-pointer transition-premium">
          <div class="flex items-center gap-3">
            <span id="activityIcon" class="material-symbols-rounded text-blue-400 transition-transform duration-300" style="transform: rotate(180deg);">expand_less</span>
            <div>
              <h3 class="text-sm font-black text-slate-200">Activity Points Verification</h3>
              <p class="text-sm text-slate-400 mt-0.5">Review and verify extracurricular claims submitted by students in your batch.</p>
            </div>
          </div>
          <button onclick="event.stopPropagation(); loadActivityClaims()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-bold transition-premium flex items-center gap-1">
            <span class="material-symbols-rounded text-xs">refresh</span> Refresh
          </button>
        </div>

        <div id="activityContent" class="space-y-6 transition-all duration-300 origin-top" style="max-height: 0px; opacity: 0; overflow: hidden;">
          <div class="overflow-x-auto rounded-xl border border-slate-800/40">
            <table class="w-full text-left text-sm border-collapse whitespace-nowrap">
              <thead>
                <tr class="bg-slate-900 border-b border-slate-800/60 text-slate-400 font-bold uppercase tracking-wider text-sm">
                  <th class="p-3">Submitted On</th>
                  <th class="p-3">Student</th>
                  <th class="p-3">Segment</th>
                  <th class="p-3">Activity & Level</th>
                  <th class="p-3">Evidence</th>
                  <th class="p-3 text-center">Claimed</th>
                  <th class="p-3 text-center">Action</th>
                </tr>
              </thead>
              <tbody id="tutorActivityTableBody" class="divide-y divide-slate-800/40">
                <!-- Claims loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL: LEAVE APPROVAL & REPORTS -->
      <div id="panelLeaveApproval" class="hidden space-y-6">
        <!-- Header -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Leave Approval & Student Reports</h3>
            <p class="text-slate-400 mt-1 text-[10px] text-xs">Review leave applications from students and view classroom reports.</p>
          </div>
          <div class="flex items-center gap-2">
            <select id="leaveClassroomSelect" onchange="loadClassroomLeaves()" class="bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white outline-none text-sm font-bold md:text-base cursor-pointer">
              <option value="">Loading classrooms...</option>
            </select>
            <button onclick="loadClassroomLeaves()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-sm">sync</span> Refresh
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <!-- Leave Table (col-span-2) -->
          <div class="xl:col-span-2 bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 space-y-4">
            <h4 class="font-black text-xs text-indigo-400 uppercase tracking-wider flex items-center gap-2"><span class="material-symbols-rounded text-sm">approval</span> Pending & Recent Leaves</h4>
            <div class="overflow-x-auto scrollbar-hidden">
              <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                    <th class="p-3">Student</th>
                    <th class="p-3">Semester</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Days</th>
                    <th class="p-3">Reason</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="classroomLeavesTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                  <tr><td colspan="7" class="p-4 text-center text-slate-500">Select a classroom to load leaves.</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mentorship Reports Card -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 space-y-4 flex flex-col justify-between">
            <div>
              <h4 class="font-black text-xs text-emerald-400 uppercase tracking-wider flex items-center gap-2"><span class="material-symbols-rounded text-sm">summarize</span> Classroom Reports</h4>
              <p class="text-slate-400 text-xs mt-1">Generate summary reports of student records for parents or administration.</p>
              <div class="space-y-4 mt-6">
                <div>
                  <label class="block text-slate-400 font-bold mb-1.5 text-[10px] text-xs">Select Student</label>
                  <select id="reportStudentSelect" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-white text-xs outline-none focus:border-emerald-500">
                    <option value="">Select student...</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="space-y-2 mt-6">
              <button onclick="printStudentFullDiary()" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-xs flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/10">
                <span class="material-symbols-rounded text-base">print</span> Print Student Diary Report
              </button>
              <button onclick="printStudentLeaveReport()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10">
                <span class="material-symbols-rounded text-base">summarize</span> Print Student Leave Report
              </button>
              <button onclick="printCondonationReport()" class="w-full py-2.5 bg-rose-950/40 hover:bg-rose-900 border border-rose-900/40 text-rose-300 rounded-xl font-bold transition-premium cursor-pointer text-xs flex items-center justify-center gap-2">
                <span class="material-symbols-rounded text-base">gavel</span> Print Condonation & Shortage Report
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  @include('mentoring_diary_modal')

  <!-- BACKLOG REPORT MODAL -->
  <div id="backlogReportModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium overflow-y-auto">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-4xl p-6 shadow-2xl space-y-4 my-8 relative">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3 sticky top-0 bg-slate-900 z-10 pt-2">
        <div>
          <h2 class="text-lg font-black text-white">Backlog Report</h2>
          <p class="text-xs text-slate-400 mt-0.5">Students with and without backlogs over the 3-year diploma.</p>
        </div>
        <button onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Without Backlog -->
        <div class="bg-slate-950/40 rounded-xl border border-emerald-900/40 overflow-hidden flex flex-col h-[500px]">
          <div class="bg-emerald-950/40 p-3 border-b border-emerald-900/40 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-emerald-400 flex items-center gap-2"><span class="material-symbols-rounded text-sm">check_circle</span> Completed Without Backlog</h3>
            <span id="noBacklogCount" class="bg-emerald-900/50 text-emerald-300 px-2 py-0.5 rounded text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2">
            <table class="w-full text-left text-xs">
              <tbody id="noBacklogList" class="divide-y divide-slate-800/40">
                <tr><td class="p-4 text-center text-slate-500">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- With Backlog -->
        <div class="bg-slate-950/40 rounded-xl border border-rose-900/40 overflow-hidden flex flex-col h-[500px]">
          <div class="bg-rose-950/40 p-3 border-b border-rose-900/40 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-rose-400 flex items-center gap-2"><span class="material-symbols-rounded text-sm">warning</span> With Backlog</h3>
            <span id="withBacklogCount" class="bg-rose-900/50 text-rose-300 px-2 py-0.5 rounded text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2">
            <table class="w-full text-left text-xs">
              <tbody id="withBacklogList" class="divide-y divide-slate-800/40">
                <tr><td class="p-4 text-center text-slate-500">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="flex justify-end pt-4 border-t border-slate-800">
        <button onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer">
          Close Report
        </button>
      </div>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xs">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE STUDENT -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xs">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-955/80 border-b border-slate-800 text-slate-400 font-bold">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex pt-2">
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Close Window</button>
      </div>
    </div>
  </div>

  <!-- REGISTER MODAL -->

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "roster";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      // Check if routed directly to mentoring
      if (sessionStorage.getItem('openMentoring') === 'true') {
        sessionStorage.removeItem('openMentoring');
        activePanel = 'mentoring';
      }

      loadSupervisedClassroomHeader();
      switchPanel(activePanel);
    });

    function loadSupervisedClassroomHeader(selectedId = null) {
      let url = '/api/tutor/classroom/me';
      if (selectedId) {
        url += `?classroom_id=${encodeURIComponent(selectedId)}`;
      }
      fetch(url)
        .then(res => res.json())
        .then(data => {
          const select = document.getElementById('filterClassroom');
          const container = document.getElementById('classroomFilterContainer');

          if (data.status === 'SUCCESS') {
            window.supervisedClassroomId = data.classroomId;
            window.supervisedBatchYear = data.batchYear;
            window.supervisedCurrentSemester = data.currentSemester || 1;
            window.supervisedIsClassTutor = data.isClassTutor;
            window.supervisedTutorName = data.tutorName || 'Not Assigned';
            window.supervisedMentorName = data.mentorName || 'Not Assigned';

            const titleEl = document.getElementById('supervisedClassroomTitle');
            if (titleEl) {
              titleEl.innerText = `Supervised Classroom Directory — ${data.classroomId} (Semester S-${data.currentSemester || 1})`;
            }
            
            const printSemSelect = document.getElementById('printSemesterSelect');
            if (printSemSelect && data.currentSemester) {
              printSemSelect.value = 'S' + data.currentSemester;
            }

            if (select) {
              if (data.classrooms && data.classrooms.length > 0) {
                const hasRealOptions = Array.from(select.options).some(opt => opt.value && opt.value !== "");
                if (!hasRealOptions || selectedId === null || select.options.length <= 1) {
                  select.innerHTML = '';
                  data.classrooms.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.classroom_id;
                    opt.innerText = `${c.classroom_id} (Admission ${c.batch_year})`;
                    if (c.classroom_id === data.classroomId) opt.selected = true;
                    select.appendChild(opt);
                  });
                }
                select.value = data.classroomId;
              } else {
                select.innerHTML = `<option value="${data.classroomId || ''}">${data.classroomId || 'No classroom available'}</option>`;
              }
            }

            if (container) {
              container.classList.remove('hidden');
            }

            if (typeof activePanel !== 'undefined' && activePanel === 'roster') {
              loadUsers();
            }
          } else {
            if (select) {
              select.innerHTML = `<option value="">${data.message || 'No supervised classrooms'}</option>`;
            }
          }
        })
        .catch(err => {
          console.error("Error loading header:", err);
          const select = document.getElementById('filterClassroom');
          if (select) {
            select.innerHTML = `<option value="">Error loading classroom</option>`;
          }
        });
    }

    function onSupervisedClassroomChange() {
      const select = document.getElementById('filterClassroom');
      if (select && select.value) {
        loadSupervisedClassroomHeader(select.value);
        loadUsers();
      }
    }

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      
      const panels = ['roster', 'rollNumbers', 'audit', 'profile', 'mentoring', 'activity', 'leaveApproval'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mobile-link";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'roster': 'Supervised Class Roster',
        'rollNumbers': 'Student Roll Numbers',
        'audit': 'Classroom Audit Trail',
        'profile': 'My Tutor Profile',
        'mentoring': 'Mentoring Batches',
        'activity': 'Activity Points Verification',
        'leaveApproval': 'Leave Approval & Mentorship Reports'
      };
      const titleEl = document.getElementById('panelTitle');
      if (titleEl && titles[panelId]) {
        titleEl.innerText = titles[panelId];
      }

      if (panelId === 'roster') loadUsers();
      if (panelId === 'rollNumbers') loadTutorStudents();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'profile') loadSelfSecurityLogs();
      if (panelId === 'mentoring') initMentoringPanel();
      if (panelId === 'activity') loadActivityClaims();
      if (panelId === 'leaveApproval') loadClassroomLeaves();
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      if (!alert) return;
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function toggleRoster() {
      const content = document.getElementById('rosterContent');
      const icon = document.getElementById('rosterIcon');
      if (content) content.classList.toggle('hidden');
      if (icon) icon.classList.toggle('rotate-180');
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const searchInput = document.getElementById('filterSearch');
      const statusInput = document.getElementById('filterStatus');
      const semesterInput = document.getElementById('filterSemester');
      const classroomInput = document.getElementById('filterClassroom');

      const search = searchInput ? searchInput.value : '';
      const status = statusInput ? statusInput.value : '';
      const semester = semesterInput ? semesterInput.value : '';
      const classroomId = (classroomInput && classroomInput.value) ? classroomInput.value : (window.supervisedClassroomId || '');

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=student&status=${status}&semester=${semester}&classroom_id=${encodeURIComponent(classroomId)}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          } else {
            const tbody = document.getElementById('usersTableBody');
            if (tbody) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="9" class="p-8 text-center text-red-400 font-medium font-sans">
                    ${data.message || 'Failed to load classroom roster.'}
                  </td>
                </tr>
              `;
            }
          }
        })
        .catch(err => {
          if (indicator) indicator.classList.add('hidden');
          console.error("Error loading roster:", err);
        });
    }

    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="9" class="p-8 text-center text-slate-500 font-medium font-sans">
              No classroom students found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium whitespace-nowrap text-xs";

        let statusBadge = `<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded-md text-[11px] font-semibold text-white transition-premium cursor-pointer shadow-sm">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded-md text-[11px] font-semibold text-red-300 transition-premium cursor-pointer shadow-sm">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded-md text-[11px] font-semibold text-white transition-premium cursor-pointer shadow-sm">
              Activate
            </button>
          `;
        }

        tr.innerHTML = `
          <td class="p-2 flex items-center gap-2.5">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-7 h-7 rounded-full object-cover border border-slate-800 shadow-sm shrink-0">
            <div class="min-w-0">
              <span class="font-semibold text-slate-100 block text-xs truncate">${user.name}</span>
              <span class="text-[11px] text-slate-400 block truncate">${user.email}</span>
            </div>
          </td>
          <td class="p-2 font-mono font-semibold text-slate-300 text-xs">${user.id}</td>
          <td class="p-2">
            <button onclick="editSbteRegNo('${user.id}', '${user.sbte_reg_no || ''}')" class="text-blue-400 hover:text-blue-300 underline font-mono cursor-pointer font-semibold text-xs" title="Click to Edit SBTE No">
              ${user.sbte_reg_no || '[Add SBTE No]'}
            </button>
          </td>
          <td class="p-2"><span class="font-semibold font-mono text-[11px] bg-slate-800/80 text-slate-300 px-1.5 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-2">
            ${user.type === 'student' ? `
              <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-indigo-400 hover:text-indigo-300 underline font-semibold text-xs cursor-pointer" title="Click to Edit Semester">
                ${user.semester || 'S1'}
              </button>
            ` : '<span class="text-slate-500 font-semibold text-xs">N/A</span>'}
          </td>
          <td class="p-2 text-xs text-slate-300">${user.role}</td>
          <td class="p-2 text-xs">${statusBadge}</td>
          <td class="p-2">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-slate-900 border border-slate-700 rounded-md px-1.5 py-0.5 text-[11px] outline-none focus:border-blue-500 font-semibold cursor-pointer ${
                user.academic_status === 'Active' ? 'text-green-400 border-green-500/20' :
                user.academic_status === 'Discontinued' ? 'text-amber-400 border-amber-500/20' :
                'text-red-400 border-red-500/20'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-500 font-semibold text-xs">N/A</span>'}
          </td>
          <td class="p-2 text-right space-x-1 text-xs">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-md text-[11px] font-semibold transition-premium cursor-pointer shadow-sm">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-700 text-slate-300 rounded-md text-[11px] font-semibold transition-premium cursor-pointer shadow-sm" title="View Audit Trail">
              Audit
            </button>
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded-md text-[11px] font-semibold transition-premium cursor-pointer shadow-sm" title="Delete Student">
              Delete
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function changeStatus(userId, userType, newStatus) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/toggle-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to update status.', true);
      });
    }

    function editSbteRegNo(regNo, currentVal) {
      let newSbte = prompt("Enter new SBTE Registration Number for " + regNo + ":", currentVal);
      if (newSbte === null) return;
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ sbte_reg_no: newSbte })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('SBTE Register Number updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
    }

    function editStudentSemester(regNo, currentSem) {
      let newSemStr = prompt("Enter new Semester (1-6) for student " + regNo + ":", currentSem.replace('S', ''));
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 6) {
        alert("Invalid semester! Please enter a number between 1 and 6.");
        return;
      }
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student semester updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
    }

    function updateAcademicStatusDirectly(regNo, newVal) {
      let note = prompt("Enter remarks / reason for changing enrollment status to " + newVal + " (optional):");
      if (note === null) {
        loadUsers(); // User clicked cancel, refresh to restore dropdown selection
        return;
      }

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ academic_status: newVal, status_notes: note })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student enrollment status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
          loadUsers(); // refresh to reset selection
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        loadUsers();
      });
    }

    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      document.getElementById('pwdResetName').innerText = userName;
      document.getElementById('pwdResetId').innerText = userId;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      
      const modal = document.getElementById('passwordModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closePasswordModal() {
      const modal = document.getElementById('passwordModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      selectedUserForReset = null;
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Password must be at least 4 characters long.";
        pwdAlert.classList.remove('hidden');
        return;
      }

      fetch('/api/admin/user/reset-password', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          userId: selectedUserForReset.userId,
          userType: selectedUserForReset.userType,
          newPassword: pwd
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Password reset successfully.');
          closePasswordModal();
        } else {
          pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying classroom audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No classroom audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-xs text-blue-400 font-mono">${log.target_id}</span></td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 font-mono text-slate-400">${log.ip_address || '-'}</td>
                <td class="p-4 text-slate-300 font-sans leading-relaxed">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`;
        });
    }

    function viewUserAudit(userId, userName) {
      document.getElementById('auditProfileName').innerText = userName;
      document.getElementById('auditProfileId').innerText = userId;
      
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">Retrieving profile logs...</td></tr>`;

      const modal = document.getElementById('auditModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/audit-logs?targetId=${userId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`;
        });
    }

    function closeAuditModal() {
      const modal = document.getElementById('auditModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');

        fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({ targetId: userId, userType })
        })
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            showGlobalMessage('Student profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete student profile.', true);
        });
      }
    }


    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }


    // ==========================================
    // ACTIVITY POINTS LOGIC
    // ==========================================
    

    let activityExpanded = false;
    function toggleActivity() {
      const content = document.getElementById('activityContent');
      const icon = document.getElementById('activityIcon');
      if (activityExpanded) {
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.style.maxHeight = '1000px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => content.style.overflow = 'visible', 300);
      }
      activityExpanded = !activityExpanded;
    }

    function loadActivityClaims() {
      fetch('/api/tutor/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('tutorActivityTableBody');
            let html = '';
            
            data.claims.forEach(c => {
              let actionsHtml = '';
              let submittedDate = c.created_at ? new Date(c.created_at) : null;
              let submittedHtml = submittedDate 
                ? `<span class="block text-xs font-bold text-slate-300">${submittedDate.toLocaleDateString()}</span><span class="block text-xs text-slate-500">${submittedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>`
                : `<span class="text-xs text-slate-500">N/A</span>`;

              if (c.status === 'Pending') {
                actionsHtml = `
                  <div class="flex items-center justify-center gap-2">
                    <input type="number" id="award_${c.id}" min="0" max="${c.points_claimed}" value="${c.points_claimed}" class="w-16 bg-slate-900 border border-slate-700 rounded px-2 py-1 text-xs text-white">
                    <button onclick="verifyClaim('${c.id}', 'Verified')" class="px-2 py-1 bg-teal-600 hover:bg-teal-500 rounded text-white text-xs font-bold">Approve</button>
                    <button onclick="verifyClaim('${c.id}', 'Rejected')" class="px-2 py-1 bg-red-950 text-red-400 border border-red-900 rounded text-xs font-bold">Reject</button>
                  </div>
                `;
              } else {
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight bg-rose-950/30 p-1 rounded border border-rose-900/30 text-left">Note: ${c.rejection_note}</div>`;
                }
                actionsHtml = `
                  <div class="flex flex-col items-center">
                    <span class="font-bold ${c.status === 'Verified' ? 'text-teal-400' : 'text-red-400'}">${c.status} (${c.points_awarded} pts)</span>
                    ${verifiedDateStr ? `<span class="text-xs text-slate-500 mt-0.5">On: ${verifiedDateStr}</span>` : ''}
                    ${noteHtml}
                  </div>
                `;
              }

              html += `
                <tr class="hover:bg-slate-900/50 transition-premium">
                  <td class="p-3">${submittedHtml}</td>
                  <td class="p-3">
                    <span class="font-bold text-slate-300 block text-xs">${c.student.name}</span>
                    <span class="text-xs text-slate-500 font-mono">${c.reg_no}</span>
                  </td>
                  <td class="p-3 text-xs font-bold text-slate-400">${c.activity_segment}</td>
                  <td class="p-3">
                    <span class="block text-xs text-slate-300">${c.activity_name}</span>
                    <span class="block text-xs text-slate-500">${c.level}</span>
                  </td>
                  <td class="p-3 text-xs text-slate-500 whitespace-normal min-w-[150px]">${c.document_reference || 'N/A'}</td>
                  <td class="p-3 text-center text-xs font-bold text-slate-300">${c.points_claimed}</td>
                  <td class="p-3 text-center">${actionsHtml}</td>
                </tr>
              `;
            });
            
            if (data.claims.length === 0) {
              html = `<tr><td colspan="6" class="p-6 text-center text-slate-500 text-xs">No pending activity claims found for your classroom.</td></tr>`;
            }
            
            tbody.innerHTML = html;
          }
        });
    }

    function verifyClaim(id, status) {
      let awarded = 0;
      let note = '';
      if (status === 'Verified') {
        awarded = document.getElementById(`award_${id}`).value;
      } else if (status === 'Rejected') {
        note = prompt("Enter a reason for rejection (optional):");
        if (note === null) return; // User cancelled
      }
      
      fetch(`/api/tutor/activity-points/${id}/verify`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ status: status, points_awarded: awarded, rejection_note: note })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Claim marked as ${status}.`);
          loadActivityClaims();
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    // ==========================================
    // MENTORING BATCHES LOGIC
    // ==========================================

    let mentoringDataCache = null;
    let selectedMentoringClassroomId = null;

    function ensureMentoringClassroomsLoaded(callback) {
      const select = document.getElementById('mentorClassroomSelect');
      const leaveSelect = document.getElementById('leaveClassroomSelect');
      
      if (select && select.options.length > 0 && select.value !== "" && select.value !== "Loading...") {
        if (callback) callback();
        return;
      }
      
      select.innerHTML = '<option value="">Loading...</option>';
      if (leaveSelect) leaveSelect.innerHTML = '<option value="">Loading...</option>';
      
      fetch('/api/mentoring/my-batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '';
            if (leaveSelect) leaveSelect.innerHTML = '';
            if (data.batches.length === 0) {
              select.innerHTML = '<option value="">No mentored classrooms</option>';
              if (leaveSelect) leaveSelect.innerHTML = '<option value="">No mentored classrooms</option>';
              return;
            }

            data.batches.forEach(b => {
              const opt = document.createElement('option');
              opt.value = b.classroom_id;
              const isGraduated = (b.current_semester || 1) > 6;
              opt.innerText = `${b.classroom_id} (Admission ${b.batch_year})${isGraduated ? ' (Graduated)' : ''}`;
              select.appendChild(opt);

              if (leaveSelect) {
                const opt2 = opt.cloneNode(true);
                leaveSelect.appendChild(opt2);
              }
            });
            
            selectedMentoringClassroomId = select.value;
            if (callback) callback();
          } else {
            select.innerHTML = '<option value="">Failed to load</option>';
          }
        })
        .catch(() => {
          select.innerHTML = '<option value="">Error</option>';
        });
    }

    function initMentoringPanel() {
      ensureMentoringClassroomsLoaded(() => {
        loadMentoringData();
      });
    }

    function generateBacklogReport() {
      if (!selectedMentoringClassroomId) {
        showGlobalMessage("Please select a classroom first.", true);
        return;
      }
      
      const modal = document.getElementById('backlogReportModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      
      document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('noBacklogCount').innerText = '0';
      document.getElementById('withBacklogCount').innerText = '0';
      
      fetch(`/api/mentoring/backlog-report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const noBacklogs = data.no_backlogs || [];
            const withBacklogs = data.with_backlogs || [];
            
            document.getElementById('noBacklogCount').innerText = noBacklogs.length;
            document.getElementById('withBacklogCount').innerText = withBacklogs.length;
            
            let noHtml = '';
            if (noBacklogs.length === 0) {
              noHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              noBacklogs.forEach(s => {
                noHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('noBacklogList').innerHTML = noHtml;
            
            let withHtml = '';
            if (withBacklogs.length === 0) {
              withHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              withBacklogs.forEach(s => {
                withHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                    <td class="p-3 text-right">
                      <span class="bg-rose-900/40 text-rose-400 px-2 py-1 rounded text-xs font-bold border border-rose-800/50">${s.backlog_count} Backlogs</span>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('withBacklogList').innerHTML = withHtml;
          } else {
            document.getElementById('noBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
            document.getElementById('withBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
          }
        })
        .catch(err => {
          console.error(err);
          document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
          document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
        });
    }

    function loadMentoringData() {
      const select = document.getElementById('mentorClassroomSelect');
      selectedMentoringClassroomId = select.value;
      if (!selectedMentoringClassroomId) return;

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch(`/api/mentoring/report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            mentoringDataCache = data;
            renderMentoringUI(data);
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to load mentoring data.', true);
        });
    }

    function renderMentoringUI(data) {
      document.getElementById('mentorAInfo').innerText = data.mentor1.name + ' (' + data.mentor1.mobile + ')';
      document.getElementById('mentorBInfo').innerText = data.mentor2.name + ' (' + data.mentor2.mobile + ')';

      const unassignedList = document.getElementById('unassignedList');
      const batchAList = document.getElementById('batchAList');
      const batchBList = document.getElementById('batchBList');
      const myList = document.getElementById('myMentoringStudentsList');

      document.getElementById('unassignedCountBadge').innerText = data.unassigned.length;
      document.getElementById('batchACountBadge').innerText = data.batch_a.length;
      document.getElementById('batchBCountBadge').innerText = data.batch_b.length;

      // Check if current user is Tutor (Mentor 1)
      const isTutor = (data.mentor1.mobile == '{{ session('userId') }}');
      const isMentor2 = (data.mentor2.mobile == '{{ session('userId') }}');

      // Helper to create assignment buttons
      const getActionButtons = (regNo, currentBatch) => {
        if (!isTutor) return ''; // Only Tutor can reassign
        
        if (currentBatch === null) {
          return `
            <button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded text-xs font-bold mr-1">To A</button>
            <button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-xs font-bold">To B</button>
          `;
        } else if (currentBatch === 'A') {
          return `<button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 border border-emerald-600 text-emerald-400 hover:bg-emerald-950 rounded text-xs font-bold">Move to B</button>`;
        } else if (currentBatch === 'B') {
          return `<button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 border border-sky-600 text-sky-400 hover:bg-sky-950 rounded text-xs font-bold">Move to A</button>`;
        }
      };

      // Unassigned List
      unassignedList.innerHTML = '';
      if (data.unassigned.length === 0) unassignedList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">No unassigned students.</td></tr>';
      data.unassigned.forEach(s => {
        unassignedList.innerHTML += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-800/40">
            <td class="p-3 font-bold text-slate-200">${s.name}</td>
            <td class="p-3 font-mono text-slate-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, null)}</td>
          </tr>
        `;
      });

      // Batch A List
      batchAList.innerHTML = '';
      if (data.batch_a.length === 0) batchAList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_a.forEach(s => {
        batchAList.innerHTML += `
          <tr class="border-b border-sky-900/40 hover:bg-sky-900/20">
            <td class="p-3 font-bold text-sky-100">${s.name}</td>
            <td class="p-3 font-mono text-sky-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'A')}</td>
          </tr>
        `;
      });

      // Batch B List
      batchBList.innerHTML = '';
      if (data.batch_b.length === 0) batchBList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_b.forEach(s => {
        batchBList.innerHTML += `
          <tr class="border-b border-emerald-900/40 hover:bg-emerald-900/20">
            <td class="p-3 font-bold text-emerald-100">${s.name}</td>
            <td class="p-3 font-mono text-emerald-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'B')}</td>
          </tr>
        `;
      });

      // Mentoring Caseload
      myList.innerHTML = '';
      let myStudents = [];
      if (isTutor) {
        // Tutor sees everyone
        myStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
      } else if (isMentor2) {
        // Mentor 2 sees only Batch B
        myStudents = data.batch_b;
      }
      
      if (myStudents.length === 0) {
        myList.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-slate-500">You have no students in your caseload.</td></tr>';
      } else {
        myStudents.forEach(s => {
          let batchName = s.batch_label ? `Batch ${s.batch_label}` : 'Unassigned';
          let batchColor = s.batch_label === 'A' ? 'sky' : (s.batch_label === 'B' ? 'emerald' : 'amber');
          
          myList.innerHTML += `
            <tr class="border-b border-slate-800/40 hover:bg-slate-800/20">
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3">
                <span class="px-2 py-0.5 rounded text-xs font-bold bg-${batchColor}-500/10 text-${batchColor}-400 border border-${batchColor}-500/20">
                  ${batchName}
                </span>
              </td>
              <td class="p-3 font-bold text-slate-300">
                ${s.diary_count || 0} entries
              </td>
              <td class="p-3 text-right whitespace-nowrap">
                <a href="sms:${s.guardian_mobile || s.phone || ''}?body=${encodeURIComponent('Carmel Poly: View your ward (' + s.name + ') live attendance & status portal: ' + window.location.origin + '/parent/dashboard/' + s.reg_no)}" class="inline-block px-2.5 py-1 bg-cyan-700 hover:bg-cyan-600 text-white rounded text-xs font-bold transition-premium cursor-pointer shadow-md no-underline me-1" title="Send SMS Link to Parent">
                  📱 SMS Portal
                </a>
                <a href="/tutor/mentoring-diary/${s.reg_no}" class="inline-block px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-bold transition-premium cursor-pointer shadow-md no-underline">
                  View Diary
                </a>
              </td>
            </tr>
          `;
        });
      }

      // Populate reportStudentSelect
      const reportStudentSelect = document.getElementById('reportStudentSelect');
      if (reportStudentSelect) {
        reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
        const allStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
        allStudents.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.reg_no;
          opt.innerText = `${s.name} (${s.reg_no})`;
          reportStudentSelect.appendChild(opt);
        });
      }
    }

    function viewStudentDiary(regNo, name) { window.location.href = '/tutor/mentoring-diary/' + regNo; }

    function closeDiaryModal() { closeFullMentoringDiaryModal(); }

    function assignStudentBatch(regNo, batchLabel) {
      fetch('/api/mentoring/assign-batch', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          classroom_id: selectedMentoringClassroomId,
          reg_no: regNo,
          batch_label: batchLabel
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          loadMentoringData(); // Refresh UI
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => showGlobalMessage('Failed to assign student.', true));
    }

    function toggleBatchAssignment() {
      const content = document.getElementById('batchAssignmentContent');
      const icon = document.getElementById('batchAssignIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = '';
      }
    }

    // --- LEAVE APPROVAL & REPORTS TAB LOGIC ---
    let selectedLeaveClassroomId = '';

    function loadClassroomLeaves() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      if (!selectEl || selectEl.options.length === 0 || selectEl.value === "" || selectEl.value === "Loading...") {
        ensureMentoringClassroomsLoaded(() => {
          loadClassroomLeaves();
        });
        return;
      }
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) return;
      selectedLeaveClassroomId = classroomId;

      fetch(`/api/mentoring/classroom/${classroomId}/leaves`, {
        headers: getHeaders()
      })
      .then(res => res.json())
      .then(resData => {
        const tbody = document.getElementById('classroomLeavesTableBody');
        tbody.innerHTML = '';
        if (resData.status === 'SUCCESS' && resData.data.length > 0) {
          resData.data.forEach(lv => {
            const statColor = lv.status === 'Approved' ? 'text-green-400' : (lv.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
            const parentInformed = lv.parent_informed ? '<span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px]">Informed</span>' : '';
            
            let actionHtml = '';
            if (lv.status === 'Pending') {
              actionHtml = `
                <button onclick="tutorApproveLeave(${lv.id}, 'Approved')" class="px-2 py-1 bg-green-700/30 text-green-400 hover:bg-green-600 hover:text-white rounded text-[10px] font-bold mr-1 transition-premium cursor-pointer">Approve</button>
                <button onclick="tutorApproveLeave(${lv.id}, 'Rejected')" class="px-2 py-1 bg-red-700/30 text-red-400 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition-premium cursor-pointer">Reject</button>
              `;
            } else {
              actionHtml = `<span class="text-xs text-slate-500 font-bold">${lv.status}</span>`;
            }

            tbody.innerHTML += `
              <tr class="border-b border-slate-800/40 hover:bg-slate-900/50">
                <td class="p-3 font-bold text-slate-200">${lv.student_name} <span class="text-[10px] text-slate-500 font-mono block">${lv.reg_no}</span></td>
                <td class="p-3 text-slate-300 font-bold">${lv.semester}</td>
                <td class="p-3 text-slate-300">${lv.leave_date}</td>
                <td class="p-3 text-slate-300 font-bold">${lv.no_of_days} day(s) ${parentInformed}</td>
                <td class="p-3 max-w-[150px] truncate" title="${lv.reason || ''}">${lv.reason || '-'}</td>
                <td class="p-3 font-bold ${statColor}">${lv.status}</td>
                <td class="p-3 text-right whitespace-nowrap">${actionHtml}</td>
              </tr>
            `;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-500">No leave records found for this classroom.</td></tr>';
        }

        // Populate reportStudentSelect dropdown for this classroom
        const reportStudentSelect = document.getElementById('reportStudentSelect');
        if (reportStudentSelect) {
          reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
          if (resData.status === 'SUCCESS' && resData.students) {
            resData.students.forEach(s => {
              const opt = document.createElement('option');
              opt.value = s.reg_no;
              opt.innerText = `${s.name} (${s.reg_no})`;
              reportStudentSelect.appendChild(opt);
            });
          }
        }
      });
    }

    function tutorApproveLeave(leaveId, decision) {
      if (!confirm('Are you sure you want to ' + decision.toLowerCase() + ' this leave request?')) return;
      fetch('/api/mentoring/leave/approve', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ id: leaveId, status: decision })
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          loadClassroomLeaves();
        } else {
          alert('Error: ' + resData.message);
        }
      });
    }

    function printStudentFullDiary() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/print`, '_blank');
    }

    function printStudentLeaveReport() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/leave-report`, '_blank');
    }

    function printCondonationReport() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) {
        alert('Please select a classroom first.');
        return;
      }
      window.open(`/classroom/${classroomId}/condonation-report`, '_blank');
    }

    function loadTutorStudents() {
      const list = document.getElementById('tutorRollNumberList');
      list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400">Loading students...</td></tr>';
      fetch('/api/tutor/attendance/students')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            let html = '';
            if (data.students.length === 0) {
              list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400">No students in your classroom.</td></tr>';
              return;
            }
            data.students.forEach((s, idx) => {
              html += `
                <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium student-roll-row" data-reg="${s.reg_no}">
                  <td class="p-4 text-center font-bold text-slate-500 text-sm">${idx+1}</td>
                  <td class="p-4 font-mono font-bold text-slate-300 text-sm">${s.reg_no}</td>
                  <td class="p-4 font-mono font-bold text-teal-400 text-sm">${s.sbte_reg_no || '-'}</td>
                  <td class="p-4 font-bold text-white text-sm">${s.name}</td>
                  <td class="p-2 text-center">
                    <input type="number" class="w-24 bg-slate-950 border border-slate-800 rounded px-3 py-1.5 text-center font-bold text-white roll-no-input text-sm" value="${s.roll_no || ''}" min="1" placeholder="-">
                  </td>
                </tr>
              `;
            });
            list.innerHTML = html;
          } else {
            list.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-red-400">${data.message || 'Failed to load students.'}</td></tr>`;
          }
        });
    }

    function autoFillRollNumbers() {
      const rows = Array.from(document.querySelectorAll('.student-roll-row'));
      if (rows.length === 0) return;

      // Sort rows alphabetically by student name
      rows.sort((a, b) => {
        const nameA = a.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        const nameB = b.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        return nameA.localeCompare(nameB);
      });

      // Update the roll number inputs sequentially on screen
      rows.forEach((row, index) => {
        const input = row.querySelector('.roll-no-input');
        if (input) {
          input.value = index + 1;
        }
      });
      
      showGlobalMessage('Roll numbers auto-filled alphabetically (1 to ' + rows.length + '). Review and click Save.');
    }

    function saveRollNumbers() {
      const rows = document.querySelectorAll('.student-roll-row');
      const rollNumbers = [];
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const rollNoVal = row.querySelector('.roll-no-input').value.trim();
        rollNumbers.push({
          reg_no: regNo,
          roll_no: rollNoVal ? parseInt(rollNoVal) : null
        });
      });

      fetch('/api/tutor/attendance/roll-numbers', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ roll_numbers: rollNumbers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message);
          loadTutorStudents();
        } else {
          showGlobalMessage(data.message || "Failed to update roll numbers.", true);
        }
      })
      .catch(err => {
        console.error(err);
        showGlobalMessage("Error saving roll numbers.", true);
      });
    }

    function printStudentCredentials() {
      const activeClass = window.supervisedClassroomId || (document.getElementById('filterClassroom') ? document.getElementById('filterClassroom').value : '');
      if (activeClass) {
        window.open(`/hod/batches/${activeClass}/credentials/print`, '_blank');
        return;
      }
      fetch(`/api/tutor/classroom/me`)
        .then(res => res.json())
        .then(data => {
          const cId = data.classroomId || (data.classroom ? data.classroom.classroom_id : null);
          if (data.status === 'SUCCESS' && cId) {
            window.open(`/hod/batches/${cId}/credentials/print`, '_blank');
          } else {
            alert('Classroom batch record not found to generate print roster.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Could not determine classroom batch ID.');
        });
    }

    function printClassRegister() {
      const semSelect = document.getElementById('printSemesterSelect');
      if (!semSelect) return;
      const targetSem = semSelect.value; // e.g. "S3"
      const targetSemNum = parseInt(targetSem.replace('S', ''));

      const activeClass = window.supervisedClassroomId || (document.getElementById('filterClassroom') ? document.getElementById('filterClassroom').value : '');
      
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/tutor/classroom/me${activeClass ? '?classroom_id=' + encodeURIComponent(activeClass) : ''}`)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status !== 'SUCCESS') {
            alert('Failed to retrieve classroom data: ' + data.message);
            return;
          }

          const students = data.students || [];
          
          // 1. Group students
          const activeList = [];
          const discontinuedList = [];

          students.forEach(s => {
            const isInactive = s.academic_status === 'Discontinued' || s.academic_status === 'TC Issued';
            const studentSemNum = parseInt(String(s.semester || 'S1').replace('S', ''));

            if (isInactive && studentSemNum < targetSemNum) {
              // Discontinued in a prior semester
              discontinuedList.push(s);
            } else if (!isInactive) {
              // Active student in target semester
              activeList.push(s);
            }
          });

          // 2. Sort active students alphabetically by name
          activeList.sort((a, b) => a.name.localeCompare(b.name));

          // 3. Build Print HTML
          const printWindow = window.open('', '_blank');
          if (!printWindow) {
            alert('Popup blocker blocked the print preview. Please allow popups.');
            return;
          }

          const branchName = "{{ session('userBranch') }}".toUpperCase();
          const batchYear = window.supervisedBatchYear || data.batchYear || 'N/A';
          const batchEnd = parseInt(batchYear) ? parseInt(batchYear) + 3 : 'N/A';
          const tutorName = window.supervisedTutorName || data.tutorName || 'Not Assigned';
          const mentorName = window.supervisedMentorName || data.mentorName || 'Not Assigned';

          const printDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

          let activeRows = '';
          activeList.forEach((s, idx) => {
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            activeRows += `
              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.sbte_reg_no || '-'}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status || 'Active'}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          if (activeList.length === 0) {
            activeRows = `<tr><td colspan="8" style="text-align:center; padding:15px; color:#555;">No active students in this semester.</td></tr>`;
          }

          let discontinuedRows = '';
          discontinuedList.forEach((s, idx) => {
            const leftSem = s.semester || 'S1';
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            discontinuedRows += `
              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          let discontinuedSection = '';
          if (discontinuedList.length > 0) {
            discontinuedSection = `
              <div style="margin-top: 30px;">
                <h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #334155; padding-bottom: 5px; margin-bottom: 10px; color: #1e293b;">
                  Discontinued / TC Issued Students (Prior to ${targetSem})
                </h3>
                <table class="report-table">
                  <thead>
                    <tr>
                      <th style="width: 5%;">No.</th>
                      <th>Student Name</th>
                      <th style="width: 15%;">Register No</th>
                      <th style="width: 12%;">Adm Year</th>
                      <th style="width: 8%;">Sem</th>
                      <th style="width: 15%;">Enrolled Status</th>
                      <th style="width: 25%;">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${discontinuedRows}
                  </tbody>
                </table>
              </div>
            `;
          }

          const html = `
            <!DOCTYPE html>
            <html>
            <head>
              <title>Class Register - ${data.classroomId} (${targetSem})</title>
              <style>
                @media print {
                  @page {
                    size: A4 landscape;
                    margin: 1.5cm;
                  }
                  body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                  }
                }
                body {
                  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                  color: #0f172a;
                  margin: 0;
                  padding: 10px;
                  background-color: #fff;
                }
                .header-container {
                  text-align: center;
                  border-bottom: 3px double #000;
                  padding-bottom: 12px;
                  margin-bottom: 20px;
                }
                .college-name {
                  font-size: 20px;
                  font-weight: 900;
                  letter-spacing: 1px;
                  margin: 0;
                  color: #000;
                }
                .dept-name {
                  font-size: 13px;
                  font-weight: bold;
                  margin: 4px 0 0 0;
                  color: #334155;
                  letter-spacing: 0.5px;
                }
                .report-title {
                  font-size: 15px;
                  font-weight: 800;
                  margin: 8px 0 0 0;
                  text-transform: uppercase;
                  color: #000;
                  background: #f1f5f9;
                  display: inline-block;
                  padding: 4px 16px;
                  border-radius: 4px;
                }
                .meta-grid {
                  display: grid;
                  grid-template-columns: repeat(4, 1fr);
                  gap: 10px;
                  margin-bottom: 20px;
                  font-size: 12px;
                  background-color: #f8fafc;
                  border: 1px solid #e2e8f0;
                  padding: 12px;
                  border-radius: 8px;
                }
                .meta-item {
                  display: flex;
                  flex-direction: column;
                }
                .meta-label {
                  font-weight: bold;
                  color: #64748b;
                  text-transform: uppercase;
                  font-size: 9px;
                  margin-bottom: 2px;
                }
                .meta-value {
                  font-weight: bold;
                  color: #0f172a;
                  font-size: 12px;
                }
                .report-table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 10px;
                  font-size: 12px;
                }
                .report-table th, .report-table td {
                  border: 1px solid #cbd5e1;
                  padding: 8px 10px;
                  text-align: left;
                }
                .report-table th {
                  background-color: #f1f5f9;
                  font-weight: bold;
                  color: #1e293b;
                  text-transform: uppercase;
                  font-size: 10px;
                }
                .report-table tr:nth-child(even) {
                  background-color: #f8fafc;
                }
                .footer-signatures {
                  margin-top: 50px;
                  display: flex;
                  justify-content: space-between;
                  font-size: 12px;
                  font-weight: bold;
                  padding: 0 20px;
                }
                .sig-line {
                  border-top: 1.5px solid #000;
                  width: 200px;
                  text-align: center;
                  padding-top: 5px;
                  margin-top: 40px;
                }
              </style>
            </head>
            <body>
              <div class="header-container" style="position: relative;">
                <div style="position: absolute; right: 0; top: 0; font-size: 11px; font-weight: bold; color: #475569;">
                  Print Date: ${printDate}
                </div>
                <div class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</div>
                <div class="dept-name">DEPARTMENT OF ${branchName} ENGINEERING</div>
                <div class="report-title">Class Register - Admission ${batchYear}</div>
              </div>

              <div class="meta-grid">
                <div class="meta-item">
                  <span class="meta-label">Classroom ID</span>
                  <span class="meta-value">${data.classroomId}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Academic Semester</span>
                  <span class="meta-value">${targetSem}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Tutor</span>
                  <span class="meta-value">${tutorName}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Mentor</span>
                  <span class="meta-value">${mentorName}</span>
                </div>
              </div>

              <table class="report-table">
                <thead>
                  <tr>
                    <th style="width: 5%;">Roll No.</th>
                    <th>Student Name</th>
                    <th style="width: 15%;">Register No</th>
                    <th style="width: 15%;">SBTE Exam No</th>
                    <th style="width: 10%;">Adm Year</th>
                    <th style="width: 8%;">Sem</th>
                    <th style="width: 12%;">Enrolled Status</th>
                    <th style="width: 25%;">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  ${activeRows}
                </tbody>
              </table>

              ${discontinuedSection}

              <div class="footer-signatures">
                <div class="sig-line">Class Tutor</div>
                <div class="sig-line">Class Mentor</div>
                <div class="sig-line">Head of Department</div>
              </div>

              <script>
                window.onload = function() {
                  window.print();
                };
              <\/script>
            </body>
            </html>
          `;

          printWindow.document.open();
          printWindow.document.write(html);
          printWindow.document.close();
        })
        .catch(err => {
          if (indicator) indicator.classList.add('hidden');
          console.error(err);
          alert('Error preparing print preview.');
        });
    }   // end printClassRegister

    // Bulk Import JS handlers
    function openBulkImportModal() {
      const modal = document.getElementById('bulkImportModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.getElementById('bulkImportAlert').classList.add('hidden');
      document.getElementById('bulkImportFileInput').value = '';
    }

    function closeBulkImportModal() {
      const modal = document.getElementById('bulkImportModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function downloadStudentExcelTemplate() {
      if (typeof XLSX !== 'undefined') {
        const templateData = [
          ["Name", "Admission_No", "Branch", "Admission_Year", "Admission_Type", "Semester", "Email", "SBTE_Reg_No"],
          ["Arun Kumar", "ADM24CT01", "CT", 2024, "Regular", "S1", "", ""],
          ["Beena S", "ADM24ECL02", "EL", 2024, "LET", "S3", "beena@carmelpoly.in", "2403210451"]
        ];
        const ws = XLSX.utils.aoa_to_sheet(templateData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Student_Roster");
        XLSX.writeFile(wb, "Student_Bulk_Import_Template.xlsx");
      } else {
        window.location.href = "/api/students/template/download";
      }
    }

    function submitBulkImport() {
      const fileInput = document.getElementById('bulkImportFileInput');
      const alertEl = document.getElementById('bulkImportAlert');

      if (!fileInput.files || fileInput.files.length === 0) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
        alertEl.innerText = 'Please select an Excel (.xlsx, .xls) or CSV file to upload.';
        return;
      }

      const file = fileInput.files[0];
      alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-blue-950/40 text-blue-400 border border-blue-900/60 block';
      alertEl.innerText = 'Reading file and preparing import...';

      if (typeof XLSX !== 'undefined') {
        const reader = new FileReader();
        reader.onload = function(e) {
          try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            if (!rows || rows.length < 2) {
              alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
              alertEl.innerText = 'The uploaded file is empty or missing student rows.';
              return;
            }

            alertEl.innerText = 'Uploading and processing ' + (rows.length - 1) + ' student records...';

            fetch('/api/students/bulk-import', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ rows: rows })
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'SUCCESS') {
                alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 block';
                alertEl.innerText = data.message || 'Bulk import completed successfully!';
                setTimeout(() => {
                  closeBulkImportModal();
                  if (typeof loadUsers === 'function') loadUsers();
                }, 2000);
              } else {
                alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
                alertEl.innerText = data.message || 'Bulk import failed.';
              }
            })
            .catch(() => {
              alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
              alertEl.innerText = 'Network error during bulk import. Please try again.';
            });
          } catch (err) {
            console.error(err);
            alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
            alertEl.innerText = 'Could not parse file. Please ensure it is a valid Excel (.xlsx, .xls) or CSV document.';
          }
        };
        reader.readAsArrayBuffer(file);
      } else {
        const formData = new FormData();
        formData.append('file', file);

        fetch('/api/students/bulk-import', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 block';
            alertEl.innerText = data.message || 'Bulk import completed successfully!';
            setTimeout(() => {
              closeBulkImportModal();
              if (typeof loadUsers === 'function') loadUsers();
            }, 2000);
          } else {
            alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
            alertEl.innerText = data.message || 'Bulk import failed.';
          }
        })
        .catch(() => {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-rose-950/40 text-rose-400 border border-rose-900/60 block';
          alertEl.innerText = 'Network error during file upload. Please check connection.';
        });
      }
    }
  </script>

  <!-- BULK IMPORT STUDENTS MODAL -->
  <div id="bulkImportModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-400 text-base">upload_file</span> Bulk Import Student Roster
        </h3>
        <button onclick="closeBulkImportModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <p class="text-xs text-slate-400 leading-relaxed">
        Upload an Excel (.xlsx / .xls) or CSV file containing student roster details. Accounts will be auto-created with a common default password (<code class="text-amber-300 font-mono bg-slate-950 px-1.5 py-0.5 rounded">carmel2026</code>) and marked for mandatory profile verification upon first login.
      </p>

      <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 space-y-3">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-300">Download Excel Template</span>
          <button onclick="downloadStudentExcelTemplate()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-emerald-400 rounded-lg text-xs font-bold transition-premium flex items-center gap-1 border border-slate-700 cursor-pointer">
            <span class="material-symbols-rounded text-xs">download</span> Excel Template (.xlsx)
          </button>
        </div>
        <div class="text-[11px] text-slate-400">
          <strong>Excel / CSV Columns:</strong> <code class="text-slate-300">Name, Admission_No, Branch, Admission_Year, Admission_Type, Semester, Email, SBTE_Reg_No</code>
        </div>
      </div>

      <div>
        <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-2">Select Roster File (.xlsx, .xls, .csv)</label>
        <input type="file" id="bulkImportFileInput" accept=".xlsx, .xls, .csv" class="w-full text-xs text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 cursor-pointer">
      </div>

      <div id="bulkImportAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeBulkImportModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitBulkImport()" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-600/10">
          <span class="material-symbols-rounded text-xs">cloud_upload</span> Start Import
        </button>
      </div>
    </div>
  </div>

  @include('partials.support_desk_overlay')
</body>
</html>
