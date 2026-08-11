@php
  $activeBranch = $branchOverride ?? session('userBranch');
  $isPrincipalMode = isset($isPrincipalView) && $isPrincipalView;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - {{ $isPrincipalMode ? 'Principal view' : 'HOD Dashboard' }}</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
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
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.3);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(99, 102, 241, 0.3);
      border-radius: 99px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(99, 102, 241, 0.5);
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
      #sidebarAvatarContainer {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 h-screen w-full flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight text-white" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.3px; background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $isPrincipalMode ? 'Principal View' : 'HOD Console' }}</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-teal-400 block uppercase tracking-wider">{{ $activeBranch }} {{ $isPrincipalMode ? 'Batch Status' : 'HOD' }}</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-3 space-y-1">
      @if($isPrincipalMode)
      <a href="/dashboard/principal" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-amber-400 hover:bg-amber-950/30 hover:text-amber-300 cursor-pointer no-underline mb-2">
         <span class="material-symbols-rounded text-base">arrow_back</span> Return to Desk
      </a>
      @endif
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-base">group</span> User Directory
      </button>
      <button id="navBatches" onclick="switchPanel('batches')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link">
        <span class="material-symbols-rounded text-base">school</span> Batch Management
      </button>
      <button id="navSubjects" onclick="switchPanel('subjects')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-base">library_books</span> Subject Allocation
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-base">receipt_long</span> Department Audit Trail
      </button>
      <a href="/dashboard/lecturer" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">calendar_view_week</span> My Batches
      </a>
      <a href="/hod/report-centre" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">analytics</span> Report Centre
      </a>
      <a href="/staff/leave/reports" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">event_note</span> Staff Leave Ledger
      </a>
      <a href="/staff/attendance-log" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">co_present</span> Class Attendance Log
      </a>
      <a href="/remedial-sessions" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">health_and_safety</span> Remedial Sessions
      </a>
      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-base">event_note</span> My Leave & Attendance Log
      </a>
      <a href="/staff/professional-activities" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-base">school</span> Professional Activities
      </a>

      <button id="navProfile" onclick="switchPanel('profile')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-base">settings</span> My Profile
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
        <span class="material-symbols-rounded text-sm text-base">logout</span> Sign Out
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
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="font-bold text-slate-100 tracking-tight text-lg">Batch & Class Management</h1>
      <div class="flex items-center gap-3">
        <div id="aiStatusBadge" class="hidden"></div>
        <div id="loadingIndicator" class="hidden items-center gap-2 text-sm text-slate-400 text-sm">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-sm font-bold transition-premium border text-sm"></div>

      <!-- PANEL 1: USER DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-sm font-bold text-slate-200 text-sm">Department Registered Accounts ({{ $activeBranch }})</h3>
            <p class="text-sm text-slate-400 mt-0.5">Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-sm">
            <span class="material-symbols-rounded text-base">person_add</span> Register User
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
          <!-- Search input -->
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none text-sm">
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Lecturer">Lecturers Only</option>
              <option value="Demonstrator">Demonstrators Only</option>
              <option value="Physical_Instructor">Physical Instructors Only</option>
              <option value="Trade_Instructor">Trade Instructors Only</option>
              <option value="Tradesman">Tradesman Only</option>
              <option value="Laboratory_Assistant">Laboratory Assistants Only</option>
              <option value="Workshop_Instructor">Workshop Instructors Only</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none text-sm">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
          <!-- Search Button -->
          <div>
            <button onclick="loadUsers()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center justify-center gap-2 h-[38px] text-sm">
              <span class="material-symbols-rounded text-base">search</span> Load Directory
            </button>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden shadow-xl">
          <div class="max-h-[calc(100vh-320px)] overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[950px]">
              <thead>
                <tr class="bg-slate-900/80 border-b border-slate-800/80 text-slate-400 font-bold uppercase text-[11px] tracking-wider whitespace-nowrap">
                  <th class="p-3.5 align-middle">Profile</th>
                  <th class="p-3.5 align-middle">Mobile / Reg No</th>
                  <th class="p-3.5 align-middle">Branch</th>
                  <th class="p-3.5 align-middle">Sem / Batch</th>
                  <th class="p-3.5 align-middle">Role Designation</th>
                  <th class="p-3.5 align-middle">Account Status</th>
                  <th class="p-3.5 align-middle">Enrollment</th>
                  <th class="p-3.5 align-middle text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody" class="divide-y divide-slate-800/40">
                <tr><td colspan="8" class="p-8 text-center text-slate-500 font-medium text-sm">Use the filters and click "Load Directory" to view accounts.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 2: BATCH MANAGEMENT -->
      <div id="panelBatches" class="space-y-6">

        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-2">
          <!-- Populated dynamically -->
        </div>

        <!-- Panel Header -->
          <div class="flex justify-between items-start md:items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl flex-col md:flex-row gap-4">
            <div class="flex-1">
              <h3 class="text-sm font-bold text-slate-200 text-lg">Batch & Class Management ({{ session('userBranch') }})</h3>
              <p class="text-sm text-slate-400 mt-0.5">Create admission-year batches, assign a Tutor (class teacher) and Mentor for each batch.<br>Students auto-assign on registration.</p>
            </div>
            <div class="flex items-center gap-4 ml-auto">
            <div class="flex bg-slate-900 rounded-xl p-1 border border-slate-800">
              <button id="btnHodFilterActive" onclick="loadBatches('active')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-premium bg-violet-600/20 text-violet-400 text-sm">Current Batches</button>
              <button id="btnHodFilterHistorical" onclick="loadBatches('historical')" class="px-4 py-1.5 rounded-lg text-sm font-bold transition-premium text-slate-500 hover:text-slate-300 text-sm">Previous Batches</button>
            </div>
            <button onclick="openCreateBatchModal()" class="px-4 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-violet-500/10 text-sm">
              <span class="material-symbols-rounded text-base">add</span> Create Batch
            </button>
          </div>
        </div>

        <!-- Batch Alert -->
        <div id="batchGlobalAlert" class="hidden p-4 rounded-xl text-sm font-bold border text-sm"></div>

        <!-- Batch Cards Grid -->
        <div id="batchCardsGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- rendered by JS -->
        </div>

        <!-- Empty state -->
        <div id="batchEmptyState" class="hidden flex flex-col items-center justify-center py-16 text-center">
          <span class="material-symbols-rounded text-slate-700 mb-3 text-5xl">folder_open</span>
          <p class="text-slate-500 font-bold text-base">No batches created yet.</p>
          <p class="text-slate-600 text-sm mt-1 text-sm">Click "Create Batch" to set up your first admission year batch.</p>
        </div>

      </div>

      <!-- PANEL: SUBJECT ALLOCATION -->
      <div id="panelSubjects" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl">
          <div class="mb-4 pb-4 border-b border-slate-800/60">
            <h3 class="text-sm font-bold text-slate-200 text-sm">Subject & Staff Allocation</h3>
            <p class="text-sm text-slate-400 mt-0.5">Map curriculum subjects to batches per semester and assign staff across departments.</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Select Target Batch</label>
              <select id="subjectBatchSelect" onchange="loadSubjects()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
                <option value="">-- Choose a Classroom --</option>
                <!-- Loaded via JS -->
              </select>
            </div>
            <div class="flex-1">
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Select Semester</label>
              <select id="subjectSemesterSelect" onchange="loadSubjects()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
                <option value="1" selected>Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
                <option value="6">Semester 6</option>
              </select>
            </div>
            <div>
              <button onclick="openSubjectModal()" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center justify-center gap-1.5 shadow-lg shadow-emerald-500/10 h-[34px]">
                <span class="material-symbols-rounded text-sm">add_box</span> Add Subject
              </button>
            </div>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-4">Subject Code</th>
                <th class="p-4">Subject Name</th>
                <th class="p-4">Type</th>
                <th class="p-4">Assigned Staff</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="subjectsTableBody">
              <tr><td colspan="5" class="p-8 text-center text-slate-500">Select a batch to view its subjects.</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- PANEL 3: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-bold text-slate-200 text-sm">Department Audit Trail</h3>
            <p class="text-sm text-slate-400 mt-1">Lifecycle events, status updates, registrations, and actions performed within the {{ session('userBranch') }} branch.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <!-- Audit Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target User (ID)</th>
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

    </div>
  </main>

  <!-- CREATE BATCH MODAL -->
  <div id="createBatchModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-violet-400 text-xs">school</span> Create New Batch
        </h3>
        <button onclick="closeCreateBatchModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-4">
        <!-- Admission Year -->
        <div id="batchAdmYearContainer">
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission Year</label>
          <input type="number" id="batchAdmYear" min="2000" max="2100" value="2026"
            oninput="updateBatchPreview()"
            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-violet-500 focus:ring-1 focus:ring-violet-500 outline-none">
        </div>

        <!-- Batch Type -->
        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Batch Type</label>
          <select id="batchTypeSelect" onchange="toggleBatchCreationLetView(); updateBatchPreview();" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-violet-500 outline-none cursor-pointer">
            <option value="Regular" selected>Regular (Default 3-Year Batch)</option>
            <option value="LET">Lateral Entry (LET Batch - Copy Tutor/Mentor, Starts S3)</option>
          </select>
        </div>

        <!-- Preview -->
        <div class="bg-slate-950/60 border border-slate-800/60 rounded-xl p-3 flex items-center gap-3">
          <span class="material-symbols-rounded text-violet-400 text-sm">info</span>
          <div>
            <p class="text-sm text-slate-400">Classroom ID that will be created:</p>
            <p id="batchIdPreview" class="font-mono font-bold text-violet-300 text-sm">{{ session('userBranch') }}_2025_2028</p>
          </div>
        </div>

        <!-- Starting Semester -->
        <div id="batchStartSemesterContainer">
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Starting Semester</label>
          <select id="batchStartSemesterSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-violet-500 outline-none">
            <option value="1" selected>Semester 1 (S1)</option>
            <option value="2">Semester 2 (S2)</option>
            <option value="3">Semester 3 (S3)</option>
            <option value="4">Semester 4 (S4)</option>
            <option value="5">Semester 5 (S5)</option>
            <option value="6">Semester 6 (S6)</option>
          </select>
        </div>

        <!-- Optional Tutor -->
        <div id="batchTutorContainer">
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Assign Tutor (Optional)</label>
          <select id="batchTutorSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-violet-500 outline-none">
            <option value=""> Select Tutor (optional) </option>
          </select>
        </div>

        <!-- Optional Mentor -->
        <div id="batchMentorContainer">
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Assign Mentor (Optional)</label>
          <select id="batchMentorSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-violet-500 outline-none">
            <option value="">Select Mentor (optional) </option>
          </select>
        </div>
      </div>

      <div id="createBatchAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeCreateBatchModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitCreateBatch()" class="flex-1 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
          <span>Create Batch</span>
          <div id="createBatchSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
        </button>
      </div>
    </div>
  </div>

  <!-- BATCH DETAIL MODAL -->
  <div id="batchDetailModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-7xl shadow-2xl flex flex-col max-h-[95vh]">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-800 p-5 flex-shrink-0">
        <div>
          <h3 id="batchDetailTitle" class="font-black text-slate-100 text-sm">Batch Detail</h3>
          <p id="batchDetailSubtitle" class="text-sm text-slate-400 mt-0.5">Manage tutor, mentor, subjects, and enrolled students</p>
        </div>
        <div class="flex items-center gap-2">
          <!-- Graduate / Archive Batch button (NEW - purely additive) -->
          <button id="btnGraduateBatch" onclick="confirmGraduateBatch()" class="hidden px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center gap-1.5">
            <span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch
          </button>
          <!-- Delete Batch button -->
          <button id="btnDeleteBatch" onclick="confirmDeleteBatch()" class="hidden px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl text-sm font-bold transition-premium cursor-pointer flex items-center gap-1.5">
            <span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch
          </button>
          <button onclick="closeBatchDetailModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
        </div>
      </div>

      <!-- Tabs Navigation -->
      <div class="flex border-b border-slate-800/60 px-5 pt-3 gap-6">
         <button onclick="switchBatchTab('tutorMentor')" id="tabBtn_tutorMentor" class="pb-3 text-sm font-bold border-b-2 border-violet-500 text-white transition-premium cursor-pointer">Tutor &amp; Mentor</button>
         <button onclick="switchBatchTab('subjects')" id="tabBtn_subjects" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">Allocated Subjects</button>
         <button onclick="switchBatchTab('students')" id="tabBtn_students" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">Enrolled Students</button>
         <button onclick="switchBatchTab('timetable')" id="tabBtn_timetable" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">Time Table</button>
         <button onclick="switchBatchTab('semesterHistory')" id="tabBtn_semesterHistory" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">Semester History</button>
      </div>

      <div class="flex-grow overflow-y-auto p-5 relative">
        <!-- Tab: Tutor & Mentor -->
        <div id="batchTab_tutorMentor" class="block space-y-4 fade-up">

        <!-- Assignment Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <!-- Tutor Card -->
          <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400 text-xs">person_pin</span>
              <h4 class="font-black text-slate-200 text-sm">Class Tutor</h4>
            </div>
            <div id="tutorCurrentDisplay" class="text-sm text-slate-400">Not assigned</div>
            <div class="space-y-2">
              <select id="detailTutorSelect" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-sky-500 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignTutor()" class="w-full py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
                <span class="material-symbols-rounded text-sm">how_to_reg</span> Update Tutor
                <div id="assignTutorSpinner" class="hidden w-3 h-3 border-2 border-sky-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignTutorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>

          <!-- Mentor Card -->
          <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-emerald-400 text-xs">supervisor_account</span>
              <h4 class="font-black text-slate-200 text-sm">Class Mentor</h4>
            </div>
            <div id="mentorCurrentDisplay" class="text-sm text-slate-400">Not assigned</div>
            <div class="space-y-2">
              <select id="detailMentorSelect" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignMentor()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
                <span class="material-symbols-rounded text-sm">group_add</span> Update Mentor
                <div id="assignMentorSpinner" class="hidden w-3 h-3 border-2 border-emerald-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignMentorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>
        </div>
        </div>

        <!-- Tab: Subjects -->
        <div id="batchTab_subjects" class="hidden space-y-4 fade-up">
          <div class="flex items-center gap-4 mb-2">
            <div class="flex items-center gap-2">
              <label class="text-sm text-slate-400 font-bold uppercase tracking-wider">Select Semester:</label>
              <select id="modalSubjectSemester" onchange="loadModalSubjects()" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sm text-white focus:border-violet-500 outline-none">
                <option value="1" selected>Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
                <option value="6">Semester 6</option>
              </select>
            </div>
            <button onclick="openSubjectModalFromDetail()" class="ml-auto px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-lg font-bold text-sm transition-premium cursor-pointer flex items-center gap-1">
              <span class="material-symbols-rounded text-sm">add</span> Allocate Subject
            </button>
          </div>
          <div class="overflow-x-auto max-h-[450px] overflow-y-auto bg-slate-950/40 border border-slate-800/40 rounded-2xl">
            <table class="min-w-[950px] w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold sticky top-0 z-10">
                  <th class="p-3">Code</th>
                  <th class="p-3">Rev</th>
                  <th class="p-3">Subject Name</th>
                  <th class="p-3">Type</th>
                  <th class="p-3">Assigned Staff</th>
                  <th class="p-3">Course File</th>
                  <th class="p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="modalSubjectsTableBody">
                <tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab: Enrolled Students -->
        <div id="batchTab_students" class="hidden fade-up">
        <div class="bg-slate-950/40 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="p-4 border-b border-slate-800/60 flex items-center justify-between">
            <h4 class="font-black text-slate-200 text-sm flex items-center gap-2">
              <span class="material-symbols-rounded text-slate-400 text-sm">groups</span>
              Enrolled Students
              <span id="rosterCountBadge" class="px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full text-sm font-mono">0</span>
            </h4>
          </div>
          <div class="overflow-x-auto max-h-[450px] overflow-y-auto">
            <table class="min-w-[950px] w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold sticky top-0">
                  <th class="p-3">Name</th>
                  <th class="p-3">Reg No</th>
                  <th class="p-3">Adm No</th>
                  <th class="p-3">SBTE No</th>
                  <th class="p-3">Type</th>
                  <th class="p-3">Semester</th>
                  <th class="p-3">Status</th>
                  <th class="p-3"></th>
                </tr>
              </thead>
              <tbody id="batchRosterTableBody">
                <tr><td colspan="8" class="p-6 text-center text-slate-500">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Tab: Time Table -->
      <div id="batchTab_timetable" class="hidden space-y-4 fade-up">
        <div class="flex justify-between items-center bg-slate-950/20 border border-slate-800/60 p-4 rounded-xl">
          <div>
            <h4 class="text-sm font-bold text-white">Batch Weekly Timetable</h4>
            <p class="text-xs text-slate-400">Configure weekly lecture and lab hours. 3 periods forenoon, 3 periods afternoon.</p>
          </div>
          <div class="flex gap-2">
            <button onclick="printTimetable()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700 rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm">print</span> Print
            </button>
            <button id="btnEditTimetable" onclick="toggleTimetableEdit(true)" class="px-3.5 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-violet-600/10">
              <span class="material-symbols-rounded text-sm">edit</span> Edit Timetable
            </button>
            <button id="btnCancelTimetable" onclick="toggleTimetableEdit(false)" class="hidden px-3.5 py-2 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl font-bold text-sm transition-premium cursor-pointer">
              Cancel
            </button>
            <button id="btnSaveTimetable" onclick="submitTimetable()" class="hidden px-3.5 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-emerald-500/10">
              <span class="material-symbols-rounded text-sm">save</span> Save Changes
            </button>
          </div>
        </div>

        <!-- View Mode -->
        <div id="timetableDisplayArea" class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1<br><span class="text-xs text-slate-500">09:00 - 10:00</span></th>
                <th class="p-3 text-center">Period 2<br><span class="text-xs text-slate-500">10:00 - 11:00</span></th>
                <th class="p-3 text-center">Period 3<br><span class="text-xs text-slate-500">11:10 - 12:10</span></th>
                <th class="p-3 text-center bg-slate-900/20 w-16">Lunch</th>
                <th class="p-3 text-center">Period 4<br><span class="text-xs text-slate-500">01:00 - 02:00</span></th>
                <th class="p-3 text-center">Period 5<br><span class="text-xs text-slate-500">02:00 - 03:00</span></th>
                <th class="p-3 text-center">Period 6<br><span class="text-xs text-slate-500">03:00 - 04:00</span></th>
              </tr>
            </thead>
            <tbody id="timetableDisplayBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>

        <!-- Edit Mode (Form Grid) -->
        <div id="timetableEditArea" class="hidden bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-x-auto">
          <table class="w-full text-left text-sm border-collapse min-w-[800px]">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1</th>
                <th class="p-3 text-center">Period 2</th>
                <th class="p-3 text-center">Period 3</th>
                <th class="p-3 text-center bg-slate-900/20 w-16">Lunch</th>
                <th class="p-3 text-center">Period 4</th>
                <th class="p-3 text-center">Period 5</th>
                <th class="p-3 text-center">Period 6</th>
              </tr>
            </thead>
            <tbody id="timetableEditBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>
      </div>
      </div> <!-- Close flex-grow container -->
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- NEW: SEMESTER HISTORY TAB PANEL (purely additive, no existing code changed) -->
  <!-- This panel is part of the batchDetailModal flex-grow area but rendered as a hidden sibling -->
  <!-- Note: Panel is injected via JS into the flex-grow container on tab switch -->
  <!-- ============================================================ -->

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
        <p class="text-sm text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer">Save Changes</button>
      </div>
    </div>
  </div>



  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xs">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-sm text-slate-400">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-sm border-collapse">
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
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xs">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
          </div>
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission Type</label>
              <select id="directRegAdmType" onchange="handleAdmTypeChange()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
                <option value="Regular">Regular</option>
                <option value="LET">Lateral Entry (LET)</option>
              </select>
            </div>
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" value="2026">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <input type="text" id="directRegStudentBranch" readonly class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-400 focus:outline-none" value="{{ $activeBranch }}">
            </div>
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3" selected>S3</option>
                <option value="S4">S4</option>
                <option value="S5">S5</option>
                <option value="S6">S6</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Staff-Specific Fields -->
        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Physical_Instructor">Physical Instructor</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <input type="text" id="directRegStaffBranch" readonly class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-400 focus:outline-none" value="{{ $activeBranch }}">
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- SUBJECT MODAL (Add + Edit mode) -->
  <div id="subjectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 id="subjectModalTitle" class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span id="subjectModalIcon" class="material-symbols-rounded text-emerald-400 text-xs">add_box</span>
          <span id="subjectModalTitleText">Add Curriculum Subject</span>
        </h3>
        <button onclick="closeSubjectModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <form id="subjectForm" onsubmit="saveSubject(event)" class="space-y-4">
        <!-- Hidden: tracks which mode we are in. Empty = Add, filled = Edit (holds subject ID) -->
        <input type="hidden" id="modalEditSubjectId" value="">
        <input type="hidden" id="modalFormSubjectBatch">
        <input type="hidden" id="modalFormSubjectSemester">

        <div id="subjectBatchSemRow" class="p-3 bg-slate-950 border border-slate-800 rounded-xl mb-2 flex justify-between items-center text-sm">
          <span class="text-slate-400">Target Batch: <span id="displaySubjectBatch" class="font-bold text-slate-200"></span></span>
          <span class="text-slate-400">Semester: <span id="displaySubjectSemester" class="font-bold text-slate-200"></span></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Code</label>
            <div class="flex items-stretch rounded-xl overflow-hidden border border-slate-800 focus-within:border-emerald-500 bg-slate-950">
              <span id="subjectCodePrefix" class="hidden items-center px-3 bg-slate-900 text-emerald-400 font-bold font-mono text-sm border-r border-slate-800 select-none whitespace-nowrap"></span>
              <input type="text" id="subjectCodeRaw" class="flex-1 bg-transparent px-3 py-2 text-sm text-white outline-none" placeholder="e.g. ENG101">
            </div>
            <!-- Keep hidden field to maintain integration with save handlers -->
            <input type="hidden" id="subjectCode">
          </div>
          <div>
            <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Type</label>
            <select id="subjectType" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none">
              <option value="Theory">Theory</option>
              <option value="Practical / Lab">Practical / Lab</option>
              <option value="Practicum">Practicum</option>
              <option value="Project Based Theory">Project Based Theory</option>
              <option value="Seminar">Seminar</option>
              <option value="Project">Project</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Name</label>
          <input type="text" id="subjectName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none" placeholder="e.g. Engineering Mathematics">
        </div>

        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Syllabus Revision</label>
          <select id="subjectRevisionYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-emerald-500 outline-none">
            <option value="REV2026">REV2026 (Current)</option>
            <option value="REV2021">REV2021</option>
            <option value="REV2015">REV2015</option>
            <option value="REV2010">REV2010</option>
          </select>
        </div>

        <div id="subjectAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeSubjectModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" id="subjectSubmitBtn" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span id="subjectSubmitLabel">Add Subject</span>
            <div id="subjectSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ASSIGN STAFF MODAL -->
  <div id="assignStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xs">group_add</span> Assign Teaching Staff
        </h3>
        <button onclick="closeAssignStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <form id="assignStaffForm" onsubmit="assignStaff(event)" class="space-y-4">
        <input type="hidden" id="assignSubjectId">
        
        <p class="text-sm text-slate-400">Select one or more staff members to assign to <strong id="assignSubjectName" class="text-slate-200"></strong>.</p>
        
        <div>
          <label class="block text-sm text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Filter (For Inter-Department)</label>
          <select id="staffBranchFilter" onchange="renderAssignStaffList()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
            <option value="">All Branches</option>
            <option value="EL">Electronics (EL)</option>
            <option value="ME">Mechanical (ME)</option>
            <option value="CE">Civil (CE)</option>
            <option value="EEE">Electrical (EEE)</option>
            <option value="CT">Computer (CT)</option>
            <option value="AU">Automobile (AU)</option>
            <option value="GEN_AIDED">General (Aided)</option>
            <option value="GEN_SF">General (SF)</option>
          </select>
        </div>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl p-2 space-y-1" id="staffCheckboxList">
          <!-- Populated by JS -->
        </div>

        <div id="assignStaffAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAssignStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-sm text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span>Save Assignments</span>
            <div id="assignStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    window.isPrincipalView = @json($isPrincipalMode);
    window.branchOverride = @json($activeBranch);

    if (window.isPrincipalView && window.branchOverride) {
      const originalFetch = window.fetch;
      window.fetch = function(input, init) {
        let url = typeof input === 'string' ? input : input.url;
        if (url.startsWith('/api/')) {
          const separator = url.includes('?') ? '&' : '?';
          url = `${url}${separator}branch=${window.branchOverride}`;
        }
        if (typeof input === 'string') {
          return originalFetch(url, init);
        } else {
          const newRequest = new Request(url, input);
          return originalFetch(newRequest, init);
        }
      };
    }

    let activePanel = "batches";
    let selectedUserForReset = null;
    let activeBatchId = null;
    let deptStaffCache = [];

    function syncSubjectTypeOptions(revision, preselectedValue = null) {
      const typeSelect = document.getElementById('subjectType');
      if (!typeSelect) return;

      const r21Options = [
        { value: "Theory", text: "Theory" },
        { value: "Practical / Lab", text: "Practical / Lab" },
        { value: "Practicum", text: "Practicum" },
        { value: "Project Based Theory", text: "Project Based Theory" },
        { value: "Seminar", text: "Seminar" },
        { value: "Project", text: "Project" }
      ];

      const r26Options = [
        { value: "Theory Courses", text: "Theory Courses" },
        { value: "Project Based Learning", text: "Project Based Learning (PBL)" },
        { value: "Drawing Courses", text: "Drawing Courses" },
        { value: "Practicum Courses", text: "Practicum Courses" },
        { value: "Practicum Courses under Basic Science & Humanities category", text: "Practicum Courses (Basic Science & Humanities)" },
        { value: "Laboratory/Workshop Courses", text: "Laboratory/Workshop Courses" },
        { value: "Major Project-Phase II", text: "Major Project-Phase II" },
        { value: "Seminar / Minor Project / Major Project-Phase I", text: "Seminar / Minor Project / Major Project-Phase I" },
        { value: "Summer Internship/ Digital 101 Course (Skill Enhancement Course)", text: "Summer Internship/ Digital 101 Course" }
      ];

      typeSelect.innerHTML = '';
      const opts = (revision === 'REV2026') ? r26Options : r21Options;
      opts.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt.value;
        o.textContent = opt.text;
        typeSelect.appendChild(o);
      });

      if (preselectedValue) {
        typeSelect.value = preselectedValue;
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      switchPanel(activePanel);
      // Pre-load dept staff for batch modals
      loadDeptStaffCache();
      checkTodaySeminars();

      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          syncSubjectTypeOptions(this.value);
        });
      }
    });
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['directory', 'batches', 'subjects', 'audit', 'profile'];
      
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'directory': 'User Accounts Directory',
        'batches': 'Batch & Class Management',
        'subjects': 'Curriculum & Staff Allocation',
        'audit': 'Department Audit Trail',
        'profile': 'My HOD Profile'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Overview';

      // if (panelId === 'directory') loadUsers(); // Optional auto-load removed to prevent crowding
      if (panelId === 'batches') loadBatches();
      if (panelId === 'subjects') loadBatchesForSubjects();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'profile') loadSelfSecurityLogs();
    }

    function loadBatchesForSubjects() {
      // Just populate the dropdown if it's empty
      const select = document.getElementById('subjectBatchSelect');
      if (select && select.options.length > 1) {
        // Already loaded, just refresh the subjects table
        loadSubjects();
        return;
      }
      
      const p1 = fetch('/api/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch('/api/r26/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          select.innerHTML = '<option value="">-- Choose a Classroom --</option>';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          let combined = b1.concat(b2);
          
          combined.sort((x, y) => y.batch_year - x.batch_year);

          combined.forEach(b => {
            select.innerHTML += `<option value="${b.classroom_id}">${b.classroom_id} (Year ${b.batch_year})${b.is_r26 || b.batch_year === 2026 ? ' [REV2026]' : ''}</option>`;
          });
        });
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}&status=${status}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="p-8 text-center text-slate-500 font-medium font-sans">
              No matching registered profiles found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        // Prevent listing self or other HODs if needed (handled by backend, but safe-check)
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/40 transition-premium whitespace-nowrap align-middle";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 whitespace-nowrap">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20 whitespace-nowrap">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20 whitespace-nowrap">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.id !== "{{ session('userId') }}") {
          if (user.status === 'Pending') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap">
                Approve
              </button>
            `;
          } else if (user.status === 'Approved') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2.5 py-1 bg-rose-950 hover:bg-rose-900 border border-rose-800 text-rose-300 rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap">
                Suspend
              </button>
            `;
          } else if (user.status === 'Suspended') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap">
                Activate
              </button>
            `;
          }
        }

        let roleCol = user.role;

        tr.innerHTML = `
          <td class="p-3 align-middle whitespace-nowrap">
            <div class="flex items-center gap-2.5">
              <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-700 shadow shrink-0">
              <div class="min-w-0">
                <span class="font-bold text-slate-100 block text-xs md:text-sm truncate">${user.name}</span>
                <span class="text-[11px] text-slate-400 block truncate">${user.email}</span>
              </div>
            </div>
          </td>
          <td class="p-3 align-middle whitespace-nowrap font-mono font-bold text-slate-300 text-xs md:text-sm">${user.id}</td>
          <td class="p-3 align-middle whitespace-nowrap"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-3 align-middle whitespace-nowrap">
            ${user.type === 'student' ? `
              <div class="inline-flex items-center gap-1.5">
                <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-indigo-400 hover:text-indigo-300 font-bold text-xs cursor-pointer underline" title="Click to Edit Semester">
                  ${user.semester || 'S1'}
                </button>
                <button onclick="editStudentBatch('${user.id}', '${user.classroom_id || ''}')" class="text-xs px-1.5 py-0.5 bg-violet-600/20 text-violet-300 hover:bg-violet-600/40 rounded border border-violet-500/30 font-bold cursor-pointer transition-premium" title="Move Batch">
                  Move
                </button>
              </div>
            ` : '<span class="text-slate-500 font-bold text-xs">N/A</span>'}
          </td>
          <td class="p-3 align-middle whitespace-nowrap text-xs text-slate-200">${roleCol}</td>
          <td class="p-3 align-middle whitespace-nowrap text-xs">${statusBadge}</td>
          <td class="p-3 align-middle whitespace-nowrap">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs outline-none focus:border-blue-500 font-bold cursor-pointer ${
                user.academic_status === 'Active' ? 'text-green-400 border-green-500/20' :
                user.academic_status === 'Discontinued' ? 'text-amber-400 border-amber-500/20' :
                'text-red-400 border-red-500/20'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-500 font-bold text-xs">N/A</span>'}
          </td>
          <td class="p-3 align-middle whitespace-nowrap text-right">
            <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
              ${toggleButton}
              <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap">
                Reset Pwd
              </button>
              <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2.5 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-700 text-slate-300 hover:text-white rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap" title="View Audit Trail">
                Audit
              </button>
              ${user.id !== "{{ session('userId') }}" ? `
              <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-rose-950/40 hover:bg-rose-900 border border-rose-900/60 text-rose-400 rounded-lg text-xs font-bold transition-premium cursor-pointer whitespace-nowrap" title="Delete User">
                Delete
              </button>` : ''}
            </div>
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
          showGlobalMessage('User status updated successfully.');
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

    function editStudentBatch(regNo, currentBatch) {
      let newBatch = prompt("Enter new Classroom ID (Batch) for student " + regNo + ":", currentBatch || '');
      if (newBatch === null) return;
      newBatch = newBatch.trim();
      if (!newBatch) return;

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: newBatch })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student batch updated successfully.');
          loadUsers();
          if (typeof activeBatchId !== 'undefined' && activeBatchId) {
             loadBatchRoster(activeBatchId);
          }
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
      });
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
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
          pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying department audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No department audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-sm text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-sm text-blue-400 font-mono">${log.target_id}</span></td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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
              tr.className = "border-b border-slate-800/40 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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
            showGlobalMessage('Profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete profile.', true);
        });
      }
    }

    function openRegisterModal() {
      document.getElementById('directRegisterForm').reset();
      document.getElementById('directRegAlert').classList.add('hidden');
      toggleDirectRegisterFields('student');
      
      const modal = document.getElementById('registerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      const sFields = document.getElementById('directStudentFields');
      const fFields = document.getElementById('directStaffFields');
      if (type === 'student') {
        sFields.classList.remove('hidden');
        fFields.classList.add('hidden');
      } else {
        fFields.classList.remove('hidden');
        sFields.classList.add('hidden');
      }
    }

    function handleAdmTypeChange() {
      const admType = document.getElementById('directRegAdmType').value;
      const regNoInput = document.getElementById('directRegStudentId');
      if (admType === 'LET') {
        if (!regNoInput.value.startsWith('L')) {
          regNoInput.value = 'L' + regNoInput.value;
        }
        document.getElementById('directRegStudentSem').value = 'S3';
      } else {
        if (regNoInput.value.startsWith('L')) {
          regNoInput.value = regNoInput.value.substring(1);
        }
        document.getElementById('directRegStudentSem').value = 'S1';
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const alert = document.getElementById('directRegAlert');
      const spinner = document.getElementById('directRegSpinner');
      
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const type = document.getElementById('regType').value;
      const formData = new FormData();
      formData.append('name', document.getElementById('directRegName').value);
      formData.append('email', document.getElementById('directRegEmail').value);
      formData.append('password', document.getElementById('directRegPassword').value);

      let url = '/register/student';
      if (type === 'student') {
        formData.append('regNo', document.getElementById('directRegStudentId').value);
        formData.append('admNo', document.getElementById('directRegStudentAdm').value);
        formData.append('branch', document.getElementById('directRegStudentBranch').value);
        formData.append('admissionYear', document.getElementById('directRegStudentYear').value);
        formData.append('admissionType', document.getElementById('directRegAdmType').value);
      } else {
        url = '/register/staff';
        formData.append('mobileNo', document.getElementById('directRegStaffMobile').value);
        formData.append('branch', document.getElementById('directRegStaffBranch').value);
        formData.append('designation', document.getElementById('directRegStaffDesig').value);
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "User registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }

    // =========================================================================
    // BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    function loadDeptStaffCache() {
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
          }
        })
        .catch(() => {});
    }

    function populateStaffDropdowns() {
      const selectors = ['#batchTutorSelect', '#batchMentorSelect', '#detailTutorSelect', '#detailMentorSelect'];
      selectors.forEach(sel => {
        const el = document.querySelector(sel);
        if (!el) return;
        const firstOpt = el.options[0];
        el.innerHTML = '';
        el.appendChild(firstOpt.cloneNode(true));
        deptStaffCache.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.mobile_no;
          opt.textContent = `${s.name} (${s.designation.replace(/_/g,' ')})`;
          el.appendChild(opt);
        });
      });
    }

    function showBatchMessage(msg, isError = false) {
      const el = document.getElementById('batchGlobalAlert');
      el.className = isError
        ? 'p-4 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block'
        : 'p-4 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
      el.innerText = msg;
      el.classList.remove('hidden');
      setTimeout(() => el.classList.add('hidden'), 5000);
    }

    let currentBatchFilter = 'active';

    function loadBatches(status = 'active') {
      currentBatchFilter = status;
      const grid = document.getElementById('batchCardsGrid');
      const empty = document.getElementById('batchEmptyState');
      grid.innerHTML = `
        <div class="col-span-full flex items-center justify-center py-12 text-sm">
          <div class="flex items-center gap-3 text-slate-500 text-sm font-bold">
            <div class="w-5 h-5 border-2 border-slate-700 border-t-violet-400 rounded-full animate-spin"></div>
            Loading batches...
          </div>
        </div>
      `;
      empty.classList.add('hidden');

      // Update toggle UI
      if (status === 'active') {
        document.getElementById('btnHodFilterActive').className = 'px-4 py-1.5 rounded-lg text-sm font-bold transition-premium bg-violet-600/20 text-violet-400';
        document.getElementById('btnHodFilterHistorical').className = 'px-4 py-1.5 rounded-lg text-sm font-bold transition-premium text-slate-500 hover:text-slate-300';
      } else {
        document.getElementById('btnHodFilterHistorical').className = 'px-4 py-1.5 rounded-lg text-sm font-bold transition-premium bg-slate-800 text-slate-300';
        document.getElementById('btnHodFilterActive').className = 'px-4 py-1.5 rounded-lg text-sm font-bold transition-premium text-slate-500 hover:text-slate-300';
      }

      const p1 = fetch(`/api/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch(`/api/r26/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          grid.innerHTML = '';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          
          let combined = b1.concat(b2);
          
          // sort by batch_year desc, then classroom_id asc
          combined.sort((x, y) => {
            if (y.batch_year !== x.batch_year) {
              return y.batch_year - x.batch_year;
            }
            return x.classroom_id.localeCompare(y.classroom_id);
          });

          if (combined.length === 0) {
            empty.classList.remove('hidden');
            return;
          }
          combined.forEach(batch => renderBatchCard(batch));
        })
        .catch(() => {
          grid.innerHTML = `<div class="col-span-full p-8 text-center text-red-400 font-bold text-sm">Failed to load batches.</div>`;
        });
    }

    function renderBatchCard(batch) {
      const grid = document.getElementById('batchCardsGrid');
      
      const wrapper = document.createElement('div');
      wrapper.className = 'space-y-3';

      let yearColorClass = 'text-slate-100';
      let yearBadgeClass = 'bg-violet-500/10 text-violet-400 border-violet-500/20';
      let borderHoverClass = 'hover:border-violet-500/50';
      let progressColorClass = 'bg-violet-500';
      let iconColorClass = 'text-violet-400';
      let textAccentClass = 'text-violet-400';

      const isLetBatch = batch.classroom_id.includes('_LET');
      const isR26 = batch.is_r26 || batch.batch_year === 2026;

      if (isR26) {
        yearColorClass = 'text-emerald-400 font-extrabold';
        yearBadgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
        borderHoverClass = 'hover:border-emerald-500/50';
        progressColorClass = 'bg-emerald-500';
        iconColorClass = 'text-emerald-400';
        textAccentClass = 'text-emerald-400';
      } else if (isLetBatch) {
        yearColorClass = 'text-purple-450';
        yearBadgeClass = 'bg-purple-500/20 text-purple-300 border-purple-500/40';
        borderHoverClass = 'hover:border-purple-500/50';
        progressColorClass = 'bg-purple-500';
        iconColorClass = 'text-purple-400';
        textAccentClass = 'text-purple-400';
      } else if (batch.batch_year === 2024) {
        yearColorClass = 'text-amber-400';
        yearBadgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
        borderHoverClass = 'hover:border-amber-500/50';
        progressColorClass = 'bg-amber-500';
        iconColorClass = 'text-amber-400';
        textAccentClass = 'text-amber-400';
      } else if (batch.batch_year === 2025) {
        yearColorClass = 'text-sky-400';
        yearBadgeClass = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
        borderHoverClass = 'hover:border-sky-500/50';
        progressColorClass = 'bg-sky-500';
        iconColorClass = 'text-sky-400';
        textAccentClass = 'text-sky-400';
      }

      const card = document.createElement('div');
      card.className = isR26
        ? `bg-slate-950/45 border-2 border-emerald-500/80 rounded-2xl p-6 transition-premium hover:border-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.25)] flex flex-col xl:flex-row gap-6 min-h-[280px] w-full relative overflow-hidden`
        : (isLetBatch
          ? `bg-slate-950/40 border-2 border-purple-500/70 rounded-2xl p-6 transition-premium hover:border-purple-400 shadow-[0_0_20px_rgba(168,85,247,0.2)] flex flex-col xl:flex-row gap-6 min-h-[280px] w-full`
          : `bg-slate-950/40 border-2 border-slate-700/60 rounded-2xl p-6 transition-premium hover:border-slate-500 shadow-[0_0_15px_rgba(255,255,255,0.03)] flex flex-col xl:flex-row gap-6 min-h-[280px] w-full`);

      const tutorHtml = batch.tutor_name
        ? `<div class="flex items-center gap-2"><span class="material-symbols-rounded text-sky-400 text-sm">person_pin</span><span class="text-slate-300 font-medium">${batch.tutor_name}</span></div>`
        : `<div class="flex items-center gap-2"><span class="material-symbols-rounded text-slate-600 text-sm">person_off</span><span class="text-slate-650 italic">No tutor assigned</span></div>`;

      const mentorHtml = batch.mentor_name
        ? `<div class="flex items-center gap-2"><span class="material-symbols-rounded text-emerald-400 text-sm">supervisor_account</span><span class="text-slate-300 font-medium">${batch.mentor_name}</span></div>`
        : `<div class="flex items-center gap-2"><span class="material-symbols-rounded text-slate-600 text-sm">person_off</span><span class="text-slate-650 italic">No mentor assigned</span></div>`;

      // Subjects section builder
      let subjectsHtml = '';
      if (batch.subjects && batch.subjects.length > 0) {
        subjectsHtml = `
          <div class="flex-1 bg-slate-950/50 border border-slate-800/80 rounded-xl p-4 space-y-3 custom-scrollbar overflow-y-auto max-h-[220px]">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-900 pb-2">
              <span class="material-symbols-rounded text-sm ${iconColorClass}">menu_book</span>
              Active Subjects & Progress (S-${batch.current_semester || 1})
            </p>
            <div class="space-y-2">
              ${batch.subjects.map(subj => `
                <div class="bg-slate-900/40 border border-slate-850 rounded-lg p-2.5 space-y-1.5 hover:border-slate-800 transition-premium">
                  <div class="flex justify-between items-center gap-2">
                    <span class="text-slate-200 font-bold text-sm truncate" title="${subj.subject_name}">${subj.subject_name}</span>
                    <span class="text-xs font-bold ${textAccentClass} font-mono">${subj.progress}%</span>
                  </div>
                  
                  <div class="w-full bg-slate-950 rounded-full h-1.5 overflow-hidden">
                    <div class="${progressColorClass} h-1.5 rounded-full" style="width: ${subj.progress}%"></div>
                  </div>

                  <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span class="font-mono text-slate-500">${subj.subject_code}</span>
                    <span class="truncate max-w-[150px]" title="${subj.staff_list}">Staff: ${subj.staff_list}</span>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>
        `;
      } else {
        subjectsHtml = `
          <div class="flex-1 bg-slate-950/50 border border-slate-800/80 rounded-xl p-6 flex items-center justify-center text-center text-xs text-slate-500 italic">
            No subjects assigned for Semester ${batch.current_semester || 1} yet.
          </div>
        `;
      }

      card.innerHTML = `
        <div class="flex-1 flex flex-col justify-between space-y-4">
          <div class="space-y-3">
            <div class="flex items-center gap-2.5">
              <span class="px-2.5 py-1 border rounded-lg font-mono text-sm font-bold ${yearBadgeClass} whitespace-nowrap">${batch.classroom_id}</span>
              ${batch.classroom_id.includes('_LET') ? `<span class="bg-purple-950/80 border border-purple-500/40 text-purple-400 font-extrabold text-[10px] px-2 py-0.5 rounded uppercase select-none whitespace-nowrap">LET</span>` : ''}
              ${isR26 ? `<span class="bg-emerald-950/80 border border-emerald-500/40 text-emerald-450 font-extrabold text-[10px] px-2 py-0.5 rounded uppercase select-none tracking-wide animate-pulse whitespace-nowrap">Revision 2026</span>` : ''}
            </div>
            
            <div class="flex items-center justify-between gap-3">
              <div>
                <h4 class="font-bold text-xl ${yearColorClass}">Admission ${batch.batch_year}${isLetBatch ? ' (LET)' : ''}</h4>
                <p class="text-xs text-slate-500">${batch.batch_year} – ${batch.batch_year + 3} ${isLetBatch ? 'Lateral Entry ' : ''}Batch</p>
              </div>
              <div class="flex-shrink-0">
                ${(batch.current_semester || 1) > 6
                  ? `<span class="px-3 py-1 bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 rounded-xl font-bold text-sm tracking-wide flex items-center gap-1 select-none whitespace-nowrap"><span class="material-symbols-rounded" style="font-size:14px">school</span>Graduated</span>`
                  : `<span onclick="event.stopPropagation(); changeBatchSemesterPrompt('${batch.classroom_id}', ${batch.current_semester || 1})" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm tracking-wide cursor-pointer shadow-md select-none transition-premium whitespace-nowrap" title="Click to Change Batch Semester">S-${batch.current_semester || 1}</span>`
                }
              </div>
            </div>

            <div class="border-t border-slate-900 pt-3.5 space-y-2 text-sm">
              ${tutorHtml}
              ${mentorHtml}
            </div>
          </div>

          <div class="flex items-center justify-between border-t border-slate-900 pt-3">
            <div>
              <span class="text-lg font-black text-slate-200">${batch.student_count}</span>
              <span class="text-xs text-slate-500 ml-1">students</span>
            </div>
            <button onclick="openBatchDetail(${JSON.stringify(batch).replace(/"/g, '&quot;')})" class="px-4 py-2 bg-slate-800 hover:bg-violet-900 hover:text-white text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm">open_in_new</span> Manage Batch
            </button>
          </div>
        </div>
        ${subjectsHtml}
      `;

      grid.appendChild(card);
    }

    function changeBatchSemesterPrompt(classroomId, currentSem) {
      let newSemStr = prompt("Enter active Semester (1-8) for batch " + classroomId + ":", currentSem);
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 8) {
        alert("Invalid semester! Please enter a number between 1 and 8.");
        return;
      }

      fetch(`/api/hod/batches/${classroomId}/update-semester`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ current_semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Batch current semester updated successfully.');
          loadBatches(currentBatchFilter);
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    function toggleBatchCreationLetView() {
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const startSemesterContainer = document.getElementById('batchStartSemesterContainer');
      const tutorContainer = document.getElementById('batchTutorContainer');
      const mentorContainer = document.getElementById('batchMentorContainer');

      if (isLet) {
        startSemesterContainer.classList.add('hidden');
        tutorContainer.classList.add('hidden');
        mentorContainer.classList.add('hidden');
      } else {
        startSemesterContainer.classList.remove('hidden');
        tutorContainer.classList.remove('hidden');
        mentorContainer.classList.remove('hidden');
      }
    }

    function openCreateBatchModal() {
      document.getElementById('createBatchAlert').classList.add('hidden');
      document.getElementById('batchAdmYear').value = new Date().getFullYear();
      document.getElementById('batchTypeSelect').value = 'Regular';
      toggleBatchCreationLetView();
      updateBatchPreview();
      // Refresh staff cache then populate dropdowns
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
            populateStaffDropdowns();
          }
        });
      const modal = document.getElementById('createBatchModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeCreateBatchModal() {
      const modal = document.getElementById('createBatchModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function updateBatchPreview() {
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = parseInt(document.getElementById('batchAdmYear').value) || new Date().getFullYear();
      const branch = '{{ session("userBranch") }}';
      if (isLet) {
        const baseYear = year - 1;
        document.getElementById('batchIdPreview').innerText = `${branch}_${baseYear}_${baseYear + 3}_LET`;
      } else {
        document.getElementById('batchIdPreview').innerText = `${branch}_${year}_${year + 3}`;
      }
    }

    function submitCreateBatch() {
      const spinner = document.getElementById('createBatchSpinner');
      const alertEl = document.getElementById('createBatchAlert');
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = document.getElementById('batchAdmYear').value;

      if (!year) {
        alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Please enter an admission year.';
        alertEl.classList.remove('hidden');
        return;
      }

      let payload = {
        is_lateral_entry: isLet,
        admission_year: parseInt(year)
      };

      if (!isLet) {
        const tutor = document.getElementById('batchTutorSelect').value;
        const mentor = document.getElementById('batchMentorSelect').value;
        const semester = document.getElementById('batchStartSemesterSelect').value;
        payload.tutor_mobile_no = tutor || null;
        payload.mentor_mobile_no = mentor || null;
        payload.current_semester = parseInt(semester);
      }

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const url = (parseInt(year) === 2026) ? '/api/r26/hod/batches' : '/api/hod/batches';
      fetch(url, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeCreateBatchModal();
            loadBatches();
          }, 1800);
        } else {
          alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function openBatchDetail(batch) {
      activeBatchId = batch.classroom_id;
      switchBatchTab('tutorMentor'); // Reset to default tab

      document.getElementById('batchDetailTitle').innerText = `Batch ${batch.classroom_id}`;
      document.getElementById('batchDetailSubtitle').innerText = `Admission ${batch.batch_year} · ${batch.batch_year} - ${batch.batch_year + 3} Batch`;

      // Show current tutor/mentor
      document.getElementById('tutorCurrentDisplay').innerHTML = batch.tutor_name
        ? `<span class="font-bold text-sky-300">${batch.tutor_name}</span> <span class="text-slate-600 text-sm">(${batch.tutor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      document.getElementById('mentorCurrentDisplay').innerHTML = batch.mentor_name
        ? `<span class="font-bold text-emerald-300">${batch.mentor_name}</span> <span class="text-slate-600 text-sm">(${batch.mentor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      // Clear alerts
      document.getElementById('assignTutorAlert').classList.add('hidden');
      document.getElementById('assignMentorAlert').classList.add('hidden');

      // Populate dropdowns
      populateStaffDropdowns();

      // Pre-select current tutor/mentor
      if (batch.tutor_mobile_no) document.getElementById('detailTutorSelect').value = batch.tutor_mobile_no;
      if (batch.mentor_mobile_no) document.getElementById('detailMentorSelect').value = batch.mentor_mobile_no;

      // Load roster
      loadBatchRoster(batch.classroom_id);

      // Show Graduate button ONLY for S6 batches (final semester)
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) {
        if ((batch.current_semester || 1) === 6) {
          graduateBtn.classList.remove('hidden');
        } else {
          graduateBtn.classList.add('hidden');
        }
      }

      // Always show Delete Batch button for HOD
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.remove('hidden');

      const modal = document.getElementById('batchDetailModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBatchDetailModal() {
      const modal = document.getElementById('batchDetailModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      activeBatchId = null;
      // Hide graduate & delete buttons on close
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) graduateBtn.classList.add('hidden');
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.add('hidden');
    }

    // ============================================================
    // NEW: Graduate / Archive Batch — purely additive
    // ============================================================
    function confirmGraduateBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `Graduate / Archive Batch: ${title}\n\n` +
        `This will:\n` +
        `  • Set the batch status to Graduated (moves to Previous Batches)\n` +
        `  • Mark all Active students as Graduated\n\n` +
        `All historical data (attendance, marks, subjects) will remain accessible\n` +
        `in the Semester History tab.\n\n` +
        `Proceed?`
      );
      if (confirmed) doGraduateBatch();
    }

    function doGraduateBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnGraduateBatch');
      if (btn) { btn.disabled = true; btn.innerText = 'Archiving...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/graduate`, {
        method: 'PUT',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Batch graduated successfully. ${data.students_graduated} student(s) marked as Graduated.`);
          closeBatchDetailModal();
          loadBatches('historical'); // switch to Previous Batches so HOD sees the card there
        } else {
          alert(data.message || 'Failed to graduate batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Graduate / Archive Batch
    // ============================================================

    // ============================================================
    // DELETE BATCH
    // ============================================================
    function confirmDeleteBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `⚠️ DELETE BATCH: ${title}\n\n` +
        `This will PERMANENTLY delete:\n` +
        `  • The batch record\n` +
        `  • All allocated subjects\n` +
        `  • All staff assignments for this batch\n\n` +
        `NOTE: Batches with enrolled students CANNOT be deleted.\n\n` +
        `This action CANNOT be undone. Proceed?`
      );
      if (confirmed) doDeleteBatch();
    }

    function doDeleteBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnDeleteBatch');
      if (btn) { btn.disabled = true; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">hourglass_empty</span> Deleting...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message || 'Batch deleted successfully.');
          closeBatchDetailModal();
          loadBatches();
        } else {
          alert(data.message || 'Failed to delete batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Delete Batch
    // ============================================================

    // ============================================================
    // NEW: SEMESTER HISTORY TAB — purely additive, no existing functions modified
    // ============================================================

    // Extend switchBatchTab to handle the new 'semesterHistory' tab
    (function() {
      const _originalSwitchBatchTab = switchBatchTab;
      switchBatchTab = function(tab) {
        // For the new tab, we manage its panel manually
        if (tab === 'semesterHistory') {
          // Hide all existing tab panels
          ['tutorMentor', 'subjects', 'students', 'timetable'].forEach(t => {
            const el = document.getElementById('batchTab_' + t);
            const btn = document.getElementById('tabBtn_' + t);
            if (el) { el.classList.add('hidden'); el.classList.remove('block'); }
            if (btn) btn.className = 'pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
          });
          // Activate this tab button
          const btn = document.getElementById('tabBtn_semesterHistory');
          if (btn) btn.className = 'pb-3 text-sm font-bold border-b-2 border-violet-500 text-white transition-premium cursor-pointer';
          // Show or create the semester history panel
          _ensureSemesterHistoryPanel();
          return;
        }
        // Hide semester history panel if switching away
        const semPanel = document.getElementById('batchTab_semesterHistory');
        if (semPanel) semPanel.classList.add('hidden');
        const semBtn = document.getElementById('tabBtn_semesterHistory');
        if (semBtn) semBtn.className = 'pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
        // Call original
        _originalSwitchBatchTab(tab);
      };
    })();

    function _ensureSemesterHistoryPanel() {
      const flexContainer = document.querySelector('#batchDetailModal .flex-grow.overflow-y-auto');
      if (!flexContainer) return;
      let panel = document.getElementById('batchTab_semesterHistory');
      if (!panel) {
        panel = document.createElement('div');
        panel.id = 'batchTab_semesterHistory';
        panel.className = 'space-y-5';
        panel.innerHTML = `
          <!-- Semester Selector -->
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Select Semester:</span>
            ${[1,2,3,4,5,6].map(s => `
              <button id="semHistBtn_${s}" onclick="loadSemesterSnapshot(activeBatchId, ${s})"
                class="px-3 py-1.5 rounded-lg text-sm font-bold border border-slate-700 text-slate-400 hover:border-violet-500 hover:text-violet-300 transition-premium cursor-pointer bg-slate-950">
                S${s}
              </button>
            `).join('')}
          </div>

          <!-- Content area -->
          <div id="semHistContent">
            <div class="p-10 text-center text-slate-500 text-sm">Select a semester above to view its academic data.</div>
          </div>
        `;
        flexContainer.appendChild(panel);
      }
      panel.classList.remove('hidden');
    }

    function loadSemesterSnapshot(classroomId, semester) {
      if (!classroomId) return;

      // Highlight active semester button
      for (let s = 1; s <= 6; s++) {
        const btn = document.getElementById('semHistBtn_' + s);
        if (btn) {
          btn.className = s === semester
            ? 'px-3 py-1.5 rounded-lg text-sm font-bold border border-violet-500 text-violet-300 transition-premium cursor-pointer bg-violet-500/10'
            : 'px-3 py-1.5 rounded-lg text-sm font-bold border border-slate-700 text-slate-400 hover:border-violet-500 hover:text-violet-300 transition-premium cursor-pointer bg-slate-950';
        }
      }

      const content = document.getElementById('semHistContent');
      content.innerHTML = `<div class="p-10 text-center text-slate-500 text-sm flex items-center justify-center gap-3"><div class="w-5 h-5 border-2 border-slate-700 border-t-violet-400 rounded-full animate-spin"></div> Loading Semester ${semester} data...</div>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/semester/${semester}/snapshot`, {
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (data.status !== 'SUCCESS') {
          content.innerHTML = `<div class="p-8 text-center text-red-400 font-bold text-sm">${data.message || 'Failed to load semester data.'}</div>`;
          return;
        }
        _renderSemesterSnapshot(data, semester);
      })
      .catch(() => {
        content.innerHTML = `<div class="p-8 text-center text-red-400 font-bold text-sm">Error fetching semester data.</div>`;
      });
    }

    function _renderSemesterSnapshot(data, semester) {
      const content = document.getElementById('semHistContent');

      // ---- Section 1: Subjects & Staff Log ----
      let subjectsHtml = '';
      if (data.subjects && data.subjects.length > 0) {
        const rows = data.subjects.map(s => `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-3 font-mono text-slate-300 font-bold text-sm">${s.subject_code}</td>
            <td class="p-3 font-bold text-slate-200 text-sm">${s.subject_name}</td>
            <td class="p-3 text-slate-400 text-sm">${s.subject_type}</td>
            <td class="p-3 text-sm">${s.staff.length > 0 ? s.staff.map(n => `<span class="block text-slate-300 font-bold">${n}</span>`).join('') : '<span class="text-red-400 font-bold">Unassigned</span>'}</td>
            <td class="p-3 text-center text-sm font-bold text-sky-300">${s.classes_conducted}</td>
            <td class="p-3 text-center text-sm ${s.course_file_status === 'Submitted' ? 'text-emerald-400' : 'text-amber-400'} font-bold">${s.course_file_status}</td>
          </tr>
        `).join('');
        subjectsHtml = `
          <div class="bg-slate-950/30 border border-slate-600/40 rounded-2xl overflow-hidden">
            <div class="p-3 border-b border-slate-800/40 flex items-center gap-2">
              <span class="material-symbols-rounded text-violet-400" style="font-size:16px">menu_book</span>
              <span class="font-bold text-slate-200 text-sm">Subjects &amp; Staff Log — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-900/60 text-slate-400 font-bold text-xs uppercase tracking-wider">
                  <th class="p-3">Code</th><th class="p-3">Subject</th><th class="p-3">Type</th>
                  <th class="p-3">Assigned Staff</th><th class="p-3 text-center">Classes Taken</th><th class="p-3 text-center">Course File</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        subjectsHtml = `<div class="p-6 bg-slate-950/30 border border-slate-700/40 rounded-2xl text-slate-500 text-sm italic">No subjects found for Semester ${semester}.</div>`;
      }

      // ---- Section 2: Student Attendance ----
      let attendanceHtml = '';
      if (data.students && data.students.length > 0) {
        const rows = data.students.map(s => {
          const pct = s.overall_attendance_percent ?? '—';
          const pctClass = pct === '—' ? 'text-slate-500' : (pct >= 75 ? 'text-emerald-400' : (pct >= 60 ? 'text-amber-400' : 'text-red-400'));
          const bySubj = s.subject_attendance && s.subject_attendance.length > 0
            ? s.subject_attendance.map(a => `<span class="text-xs text-slate-400">${a.subject_code}: <span class="font-bold ${a.percent >= 75 ? 'text-emerald-400' : a.percent >= 60 ? 'text-amber-400' : 'text-red-400'}">${a.percent}%</span></span>`).join(' &nbsp;|&nbsp; ')
            : '<span class="text-slate-600 text-xs">No logs</span>';
          return `
            <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
              <td class="p-3 text-slate-400 text-sm font-mono">${s.roll_no || '—'}</td>
              <td class="p-3 font-bold text-slate-200 text-sm">${s.name}</td>
              <td class="p-3 text-center font-bold text-sm ${pctClass}">${pct !== '—' ? pct + '%' : '—'}</td>
              <td class="p-3 text-sm">${bySubj}</td>
              <td class="p-3 text-center"><span class="px-2 py-0.5 rounded-lg text-xs font-bold ${
                s.academic_status === 'Active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400'
              }">${s.academic_status}</span></td>
            </tr>`;
        }).join('');
        attendanceHtml = `
          <div class="bg-slate-950/30 border border-slate-600/40 rounded-2xl overflow-hidden">
            <div class="p-3 border-b border-slate-800/40 flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400" style="font-size:16px">groups</span>
              <span class="font-bold text-slate-200 text-sm">Student Attendance — Semester ${semester}</span>
              <span class="ml-auto text-xs text-slate-500">${data.students.length} students</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-900/60 text-slate-400 font-bold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Overall %</th><th class="p-3">Subject-wise</th><th class="p-3 text-center">Status</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        attendanceHtml = `<div class="p-6 bg-slate-950/30 border border-slate-700/40 rounded-2xl text-slate-500 text-sm italic">No student data found for Semester ${semester}.</div>`;
      }

      // ---- Section 3: Board Results / Marks ----
      let marksHtml = '';
      if (data.board_results && data.board_results.length > 0) {
        const rows = data.board_results.map(s => `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-3 text-slate-400 text-sm font-mono">${s.roll_no || '—'}</td>
            <td class="p-3 font-bold text-slate-200 text-sm">${s.name}</td>
            <td class="p-3 text-center font-bold text-sm ${s.result === 'Pass' ? 'text-emerald-400' : s.result === 'Fail' ? 'text-red-400' : 'text-slate-400'}">${s.result || '—'}</td>
            <td class="p-3 text-center font-bold text-sm text-amber-300">${s.sgpa || '—'}</td>
            <td class="p-3 text-center text-slate-400 text-sm">${s.board_marks || '—'}</td>
          </tr>
        `).join('');
        marksHtml = `
          <div class="bg-slate-950/30 border border-slate-600/40 rounded-2xl overflow-hidden">
            <div class="p-3 border-b border-slate-800/40 flex items-center gap-2">
              <span class="material-symbols-rounded text-amber-400" style="font-size:16px">emoji_events</span>
              <span class="font-bold text-slate-200 text-sm">Board Results — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-900/60 text-slate-400 font-bold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Result</th><th class="p-3 text-center">SGPA</th><th class="p-3 text-center">Board Marks</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        marksHtml = `<div class="p-6 bg-slate-950/30 border border-slate-700/40 rounded-2xl text-slate-500 text-sm italic">Board results not yet entered for Semester ${semester}.</div>`;
      }

      content.innerHTML = subjectsHtml + attendanceHtml + marksHtml;
    }

    // ============================================================
    // END: Semester History additions
    // ============================================================


    function switchBatchTabOriginalRef() {} // marker only

    function switchBatchTab(tab) {
      const tabs = ['tutorMentor', 'subjects', 'students', 'timetable'];
      tabs.forEach(t => {
        const el = document.getElementById('batchTab_' + t);
        const btn = document.getElementById('tabBtn_' + t);
        if (el) {
          el.classList.add('hidden');
          el.classList.remove('block');
        }
        if (btn) {
          btn.className = "pb-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-premium cursor-pointer";
        }
      });
      const targetEl = document.getElementById('batchTab_' + tab);
      const targetBtn = document.getElementById('tabBtn_' + tab);
      if (targetEl) {
        targetEl.classList.remove('hidden');
        targetEl.classList.add('block');
      }
      if (targetBtn) {
        targetBtn.className = "pb-3 text-sm font-bold border-b-2 border-violet-500 text-white transition-premium cursor-pointer";
      }
      
      if (tab === 'subjects') {
        loadModalSubjects();
      }
      if (tab === 'timetable') {
        loadTimetable();
      }
    }


    let currentTimetableData = {};
    let currentAllocatedSubjects = [];

    function loadTimetable() {
      if (!activeBatchId) return;
      
      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      
      const displayBody = document.getElementById('timetableDisplayBody');
      if (displayBody) displayBody.innerHTML = '<tr><td colspan="8" class="p-8 text-center text-slate-500">Loading timetable...</td></tr>';
      
      toggleTimetableEdit(false);

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/subjects?semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAllocatedSubjects = data.subjects || [];
            return fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/timetable`);
          } else {
            throw new Error(data.message || 'Failed to load batch subjects');
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentTimetableData = data.timetable || {};
            renderTimetable();
          } else {
            throw new Error(data.message || 'Failed to load timetable');
          }
        })
        .catch(err => {
          if (displayBody) displayBody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-red-400">Error: ${err.message}</td></tr>`;
        });
    }

    function slotsEqual(slotA, slotB) {
      if (!slotA || !slotB) return false;
      return slotA.subject === slotB.subject;
    }

    function renderTimetable() {
      const displayBody = document.getElementById('timetableDisplayBody');
      const editBody = document.getElementById('timetableEditBody');
      if (!displayBody || !editBody) return;

      displayBody.innerHTML = '';
      editBody.innerHTML = '';

      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
        
        // 1. Render Display Row with cell merging (colspan)
        const trDisp = document.createElement('tr');
        trDisp.className = 'border-b border-slate-800/40 hover:bg-slate-900/10 transition-premium';
        
        let dispCellsHtml = `<td class="p-4 text-center font-bold text-slate-200 bg-slate-900/40">${day}</td>`;
        
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Forenoon continuous slots (1, 2, 3) merging logic
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 2);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 1);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          dispCellsHtml += `<td rowspan="5" class="p-4 text-center bg-slate-950/60 font-bold text-slate-500 text-sm align-middle select-none border-l border-r border-slate-800/40" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon continuous slots (4, 5, 6) merging logic
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 2);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 1);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        }
        
        trDisp.innerHTML = dispCellsHtml;
        displayBody.appendChild(trDisp);

        // 2. Render Edit Row (always unmerged for individual slot selection)
        const trEdit = document.createElement('tr');
        trEdit.className = 'border-b border-slate-800/40';
        
        let editCellsHtml = `<td class="p-3 text-center font-bold text-slate-300 bg-slate-900/40">${day}</td>`;
        
        // Forenoon hours (1, 2, 3)
        for (let h = 1; h <= 3; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          editCellsHtml += `<td rowspan="5" class="p-3 text-center bg-slate-950/60 text-slate-600 font-bold text-sm align-middle select-none border-l border-r border-slate-850" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon hours (4, 5, 6)
        for (let h = 4; h <= 6; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        trEdit.innerHTML = editCellsHtml;
        editBody.appendChild(trEdit);
      });
    }

    function renderTimetableDisplayCell(slot, colspan = 1) {
      const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
      if (!slot.subject) {
        return `<td ${colspanAttr} class="p-4 text-center text-slate-600 italic text-sm">-- Free Period --</td>`;
      }

      // Automatically pull ALL staff members assigned to this subject (for labs/multi-lecturer classes)
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      let staffDisplay = '';
      if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
        staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
      } else {
        staffDisplay = slot.staff || 'N/A';
      }

      return `
        <td ${colspanAttr} class="p-4 text-center space-y-1">
          <div class="font-extrabold text-slate-100 text-base leading-snug">${slot.subject}</div>
          <div class="text-slate-400 text-sm">${staffDisplay}</div>
        </td>
      `;
    }

    function renderTimetableEditCell(day, hour, slot) {
      let subOptions = `<option value="">-- Free Period --</option>`;
      currentAllocatedSubjects.forEach(sub => {
        const isSelected = sub.subject_code === slot.subject ? 'selected' : '';
        subOptions += `<option value="${sub.subject_code}" ${isSelected}>${sub.subject_code} - ${sub.subject_name}</option>`;
      });

      let staffOptions = `<option value="">-- No Staff --</option>`;
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      if (matchedSub && matchedSub.staff) {
        matchedSub.staff.forEach(st => {
          const isSelected = st.name === slot.staff ? 'selected' : '';
          staffOptions += `<option value="${st.name}" ${isSelected}>${st.name}</option>`;
        });
      }

      return `
        <td class="p-2 w-44">
          <div class="space-y-1.5">
            <select onchange="updateTimetableStaffDropdown(this)" data-day="${day}" data-hour="${hour}" class="w-full bg-slate-900 border border-slate-800 rounded-lg p-1.5 text-sm text-white focus:border-violet-500 outline-none select-subject">
              ${subOptions}
            </select>
            <select data-day="${day}" data-hour="${hour}" class="w-full bg-slate-950 border border-slate-850 rounded-lg p-1 text-sm text-slate-300 focus:border-violet-500 outline-none select-staff">
              ${staffOptions}
            </select>
          </div>
        </td>
      `;
    }    function printTimetable() {
      if (!activeBatchId) return;

      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      const dept = activeBatchId ? activeBatchId.split('_')[0] : '{{ session("userBranch") }}';
      const currentYear = new Date().getFullYear();

      // Convert department codes to full names
      const deptNames = {
        "EL": "Electronics Engineering",
        "CS": "Computer Engineering",
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
        "CH": "Chemical Engineering"
      };
      const fullDept = deptNames[dept.toUpperCase()] || dept;

      const printWindow = window.open('', '_blank');
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      let rowsHtml = '';
      const scheduledSubjects = new Set();

      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Collect scheduled subject codes
        [s1, s2, s3, s4, s5, s6].forEach(s => {
          if (s.subject) scheduledSubjects.add(s.subject);
        });

        let cellsHtml = `<td class="p-4 text-center font-bold bg-gray-100 day-cell">${day}</td>`;

        // Forenoon
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          cellsHtml += renderPrintCell(s1, 2);
          cellsHtml += renderPrintCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 2);
        } else {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 1);
          cellsHtml += renderPrintCell(s3, 1);
        }

        // Lunch Break (merged vertically)
        if (index === 0) {
          cellsHtml += `<td rowspan="5" class="p-4 text-center font-black lunch-cell text-base" style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); letter-spacing: 5px; vertical-align: middle; min-width: 50px;">LUNCH BREAK</td>`;
        }

        // Afternoon
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          cellsHtml += renderPrintCell(s4, 2);
          cellsHtml += renderPrintCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 2);
        } else {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 1);
          cellsHtml += renderPrintCell(s6, 1);
        }

        rowsHtml += `<tr class="border-b border-slate-800/40 print-row">${cellsHtml}</tr>`;
      });

      function renderPrintCell(slot, colspan = 1) {
        const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
        if (!slot.subject) {
          return `<td ${colspanAttr} class="p-4 text-center free-period">-- Free --</td>`;
        }
        
        const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
        let subjectName = matchedSub ? matchedSub.subject_name : '';
        let staffDisplay = '';
        if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
          staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
        } else {
          staffDisplay = slot.staff || 'N/A';
        }

        return `
          <td ${colspanAttr} class="p-4 text-center">
            <div style="font-weight: 850; font-size: 15px;">${slot.subject}</div>
            <div style="font-weight: 600; font-size: 12px; margin-top: 2px;">${subjectName}</div>
            <div style="font-size: 11px; margin-top: 2px;">${staffDisplay}</div>
          </td>
        `;
      }

      // Build Legend/Abbreviations List
      let legendHtml = '';
      scheduledSubjects.forEach(code => {
        const sub = currentAllocatedSubjects.find(s => s.subject_code === code);
        const name = sub ? sub.subject_name : 'Unknown Subject';
        let staffDisplay = '';
        if (sub && sub.staff && sub.staff.length > 0) {
          staffDisplay = sub.staff.map(s => s.name).join(', ');
        }
        legendHtml += `
          <div class="flex gap-2 text-sm py-1.5 border-b legend-item">
            <span class="font-mono font-bold w-24 legend-code">${code}</span>
            <span class="flex-grow font-semibold">${name}</span>
            <span class="legend-staff font-medium">(${staffDisplay || 'No staff assigned'})</span>
          </div>
        `;
      });

      if (!legendHtml) {
        legendHtml = '<p class="text-sm text-gray-500 italic">No subjects scheduled.</p>';
      }

      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Timetable - ${activeBatchId}</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <style>
            /* Screen (Dark Mode) Styles */
            body {
              font-family: Arial, sans-serif;
              padding: 30px;
              background-color: #0b0f19;
              color: #f1f5f9;
            }
            .header-border {
              border-color: #1e293b;
            }
            .meta-val {
              color: #ffffff;
            }
            .meta-lbl {
              color: #94a3b8;
            }
            table {
              border-collapse: collapse;
              width: 100%;
              border: 2px solid #1e293b;
              background-color: #0f172a;
            }
            th {
              background-color: #1e293b;
              color: #f1f5f9;
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
            }
            td {
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
              vertical-align: middle;
            }
            .day-cell {
              background-color: #1e293b;
              font-weight: bold;
              color: #ffffff;
            }
            .lunch-cell {
              background-color: #090d16;
              color: #64748b;
              font-weight: 900;
            }
            .legend-box {
              background-color: #0f172a;
              border: 1px solid #1e293b;
            }
            .legend-title {
              color: #ffffff;
            }
            .legend-item {
              border-color: #1e293b;
              color: #cbd5e1;
            }
            .legend-code {
              color: #ffffff;
            }
            .legend-staff {
              color: #94a3b8;
            }
            .free-period {
              color: #475569;
              font-style: italic;
            }

            /* Print (Light Mode) Styles */
            @media print {
              .no-print {
                display: none;
              }
              @page {
                size: A4 landscape;
                margin: 0.5cm;
              }
              body {
                background-color: #ffffff;
                color: #000000;
                padding: 0;
                margin: 0;
              }
              table {
                background-color: #ffffff;
                border: 2px solid #000000 !important;
              }
              th, td {
                border: 2px solid #000000 !important;
                color: #000000 !important;
                background-color: #ffffff !important;
                padding: 6px !important;
              }
              .day-cell {
                background-color: #f3f4f6 !important;
              }
              .lunch-cell {
                background-color: #e5e7eb !important;
              }
              .legend-box {
                background-color: #ffffff !important;
                border: 1px solid #000000 !important;
                margin-top: 10px !important;
                padding: 8px !important;
              }
              .legend-title, .legend-item, .legend-code, .legend-staff {
                color: #000000 !important;
              }
              .free-period {
                color: #9ca3af !important;
              }
            }
          </style>
        </head>
        <body>
          <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Centered Header Section -->
            <div class="border-b pb-4 text-center relative header-border">
              <h1 class="text-lg font-bold meta-lbl uppercase tracking-widest text-slate-400">Carmel Polytechnic College</h1>
              <h2 class="text-2xl font-black text-white mt-1">Weekly Class Timetable</h2>
              
              <div class="flex justify-center gap-12 mt-4 text-sm meta-lbl">
                <div>Department: <strong class="meta-val">${fullDept}</strong></div>
                <div>Batch: <strong class="meta-val">${activeBatchId}</strong></div>
                <div>Semester: <strong class="meta-val">Semester ${sem}</strong></div>
                <div>Assessment Year: <strong class="meta-val">${currentYear}</strong></div>
              </div>

              <div class="no-print absolute top-0 right-0 flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow transition duration-200">
                  Print Timetable
                </button>
                <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-sm shadow transition duration-200">
                  Close Preview
                </button>
              </div>
            </div>
            
            <!-- Timetable Grid -->
            <table class="w-full text-left border">
              <thead>
                <tr class="text-slate-400 font-bold border-b header-border">
                  <th class="p-3 text-center w-24">Day</th>
                  <th class="p-3 text-center">Period 1<br><span class="text-xs font-normal meta-lbl">09:00 - 10:00</span></th>
                  <th class="p-3 text-center">Period 2<br><span class="text-xs font-normal meta-lbl">10:00 - 11:00</span></th>
                  <th class="p-3 text-center">Period 3<br><span class="text-xs font-normal meta-lbl">11:10 - 12:10</span></th>
                  <th class="p-3 text-center w-16">Lunch</th>
                  <th class="p-3 text-center">Period 4<br><span class="text-xs font-normal meta-lbl">01:00 - 02:00</span></th>
                  <th class="p-3 text-center">Period 5<br><span class="text-xs font-normal meta-lbl">02:00 - 03:00</span></th>
                  <th class="p-3 text-center">Period 6<br><span class="text-xs font-normal meta-lbl">03:00 - 04:00</span></th>
                </tr>
              </thead>
              <tbody>
                ${rowsHtml}
              </tbody>
            </table>
            
            <!-- Subject Legend / Abbreviations -->
            <div class="mt-6 p-4 rounded-xl border legend-box">
              <h3 class="text-sm font-bold legend-title mb-2 uppercase tracking-wider text-center">Subject Legend & Abbreviations</h3>
              <div class="space-y-1">
                ${legendHtml}
              </div>
            </div>
            
          </div>
        </body>
        </html>
      `);
      printWindow.document.close();
    }

    function updateTimetableStaffDropdown(subjectSelect) {
      const subjectCode = subjectSelect.value;
      const cell = subjectSelect.closest('td');
      const staffSelect = cell.querySelector('.select-staff');
      if (!staffSelect) return;

      staffSelect.innerHTML = `<option value="">-- No Staff --</option>`;
      if (!subjectCode) return;

      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === subjectCode);
      if (matchedSub && matchedSub.staff) {
        matchedSub.staff.forEach(st => {
          const opt = document.createElement('option');
          opt.value = st.name;
          opt.textContent = st.name;
          staffSelect.appendChild(opt);
        });
      }
    }

    function toggleTimetableEdit(isEdit) {
      const displayArea = document.getElementById('timetableDisplayArea');
      const editArea = document.getElementById('timetableEditArea');
      const btnEdit = document.getElementById('btnEditTimetable');
      const btnCancel = document.getElementById('btnCancelTimetable');
      const btnSave = document.getElementById('btnSaveTimetable');

      if (isEdit) {
        if (displayArea) displayArea.classList.add('hidden');
        if (editArea) editArea.classList.remove('hidden');
        if (btnEdit) btnEdit.classList.add('hidden');
        if (btnCancel) btnCancel.classList.remove('hidden');
        if (btnSave) btnSave.classList.remove('hidden');
      } else {
        if (displayArea) displayArea.classList.remove('hidden');
        if (editArea) editArea.classList.add('hidden');
        if (btnEdit) btnEdit.classList.remove('hidden');
        if (btnCancel) btnCancel.classList.add('hidden');
        if (btnSave) btnSave.classList.add('hidden');
      }
    }

    function submitTimetable() {
      if (!activeBatchId) return;

      const payload = {};
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      
      days.forEach(day => {
        payload[day] = {};
      });

      const editArea = document.getElementById('timetableEditBody');
      if (!editArea) return;

      const subjectSelects = editArea.querySelectorAll('.select-subject');
      subjectSelects.forEach(sel => {
        const day = sel.getAttribute('data-day');
        const hour = sel.getAttribute('data-hour');
        const subject = sel.value;
        
        const cell = sel.closest('td');
        const staffSel = cell.querySelector('.select-staff');
        const staff = staffSel ? staffSel.value : '';

        if (day && hour) {
          payload[day][hour] = { subject, staff };
        }
      });

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/timetable`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Timetable saved successfully!');
          loadTimetable();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        alert('Network Error: ' + err.message);
      });
    }

    function loadModalSubjects() {
      if (!activeBatchId) return;
      const sem = document.getElementById('modalSubjectSemester').value;
      const tbody = document.getElementById('modalSubjectsTableBody');
      tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-500">Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/subjects?semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-500">No subjects allocated for this semester yet.</td></tr>`;
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="block text-sm text-slate-400"><span class="font-bold text-slate-300">${s.name}</span> (${s.branch})</span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="text-red-400 text-sm font-bold">Unassigned</span>`;
              
              let courseFileBadge = subj.course_file_status === 'Submitted' 
                ? '<span class="px-2 py-0.5 rounded text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Submitted</span>'
                : '<span class="px-2 py-0.5 rounded text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>';

              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium cursor-help';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-300 font-bold">${subj.subject_code}</td>
                <td class="p-4 font-mono text-slate-500 text-sm">${subj.syllabus_revision_code || '2021'}</td>
                <td class="p-4 font-bold text-slate-200">${subj.subject_name}</td>
                <td class="p-4 text-slate-400 text-sm">${subj.subject_type}</td>
                <td class="p-4">${staffList}</td>
                <td class="p-4">${courseFileBadge}</td>
                <td class="p-4 text-right space-x-1.5">
                  <button onclick="openAssignStaffModalFromModal(event, this, ${subj.id}, '${currentStaffIds}')" data-subject-name="${subj.subject_name.replace(/"/g, '&quot;')}" class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg text-sm font-bold transition-premium border border-blue-500/20 cursor-pointer">Assign Staff</button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1.5 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded-lg text-sm font-bold transition-premium cursor-pointer" title="Delete Subject">
                    Delete
                  </button>
                </td>
              `;
              
              // Progress popup event listeners
              tr.addEventListener('mouseenter', (e) => {
                showSubjectProgressPopup(subj, e);
              });
              tr.addEventListener('mousemove', (e) => {
                positionSubjectProgressPopup(e);
              });
              tr.addEventListener('mouseleave', () => {
                hideSubjectProgressPopup();
              });
              tr.addEventListener('click', (e) => {
                e.stopPropagation();
                showSubjectProgressPopup(subj, e, true);
              });
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-red-400">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-red-400">Error fetching subjects.</td></tr>`;
        });
    }

    function openSubjectModalFromDetail() {
      const sem = document.getElementById('modalSubjectSemester').value;
      
      document.getElementById('subjectForm').reset();
      document.getElementById('subjectAlert').classList.add('hidden');

      document.getElementById('modalFormSubjectBatch').value = activeBatchId;
      document.getElementById('displaySubjectBatch').innerText = activeBatchId;
      document.getElementById('modalFormSubjectSemester').value = sem;
      document.getElementById('displaySubjectSemester').innerText = 'Semester ' + sem;
      
      const modal = document.getElementById('subjectModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function submitAssignTutor() {
      const spinner = document.getElementById('assignTutorSpinner');
      const alertEl = document.getElementById('assignTutorAlert');
      const mobile = document.getElementById('detailTutorSelect').value;

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch('/api/hod/batches/assign-tutor', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: activeBatchId, tutor_mobile_no: mobile })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('tutorCurrentDisplay').innerHTML = data.tutor_name 
            ? `<span class="font-bold text-sky-300">${data.tutor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function submitAssignMentor() {
      const spinner = document.getElementById('assignMentorSpinner');
      const alertEl = document.getElementById('assignMentorAlert');
      const mobile = document.getElementById('detailMentorSelect').value;

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch('/api/hod/batches/assign-mentor', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: activeBatchId, mentor_mobile_no: mobile })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('mentorCurrentDisplay').innerHTML = data.mentor_name
            ? `<span class="font-bold text-emerald-300">${data.mentor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function loadBatchRoster(classroomId) {
      const tbody = document.getElementById('batchRosterTableBody');
      const countBadge = document.getElementById('rosterCountBadge');
      tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm">Loading students...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/students`)
        .then(r => r.json())
        .then(data => {
          tbody.innerHTML = '';
          if (data.status !== 'SUCCESS' || data.students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-600 text-sm font-bold">No students enrolled in this batch yet.</td></tr>`;
            countBadge.innerText = '0';
            return;
          }
          countBadge.innerText = data.students.length;
          data.students.forEach(s => {
            let statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
            if (s.status === 'Approved') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
            else if (s.status === 'Suspended') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

            const admTypeBadge = s.admission_type === 'LET'
              ? `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">LET</span>`
              : `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-slate-700 text-slate-400">Regular</span>`;
              
            const sbteBadge = s.sbte_reg_no ? `<span class="font-mono text-slate-300 font-bold">${s.sbte_reg_no}</span>` : `<span class="text-sm text-slate-500 italic">Pending</span>`;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 transition-premium';
            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3 font-mono text-slate-500">${s.adm_no}</td>
              <td class="p-3">${sbteBadge}</td>
              <td class="p-3">${admTypeBadge}</td>
              <td class="p-3 font-bold text-indigo-400 font-mono">S${s.semester || '1'}</td>
              <td class="p-3">${statusBadge}</td>
              <td class="p-3 text-right space-x-1">
                <button onclick="openStudentDiary('${s.reg_no}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-teal-500/10 hover:bg-teal-500/20 border border-teal-500/20 text-teal-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">menu_book</span> Diary
                </button>
                <button onclick="editStudentBatch('${s.reg_no}', '${classroomId}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">swap_horiz</span> Move
                </button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-red-400 font-bold text-sm">Failed to load students.</td></tr>`;
        });
    }

    // =========================================================================
    // END BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    // =========================================================================
    // SUBJECT ALLOCATION FUNCTIONS
    // =========================================================================
    let allCollegeStaffCache = [];

    function loadSubjects() {
      const batchSelect = document.getElementById('subjectBatchSelect');
      const semSelect = document.getElementById('subjectSemesterSelect');
      const classroomId = batchSelect.value;
      const semester = semSelect.value;
      
      const tbody = document.getElementById('subjectsTableBody');
      if (!classroomId) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">Select a batch to view its subjects.</td></tr>`;
        return;
      }

      tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${semester}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">No subjects allocated for this semester yet.</td></tr>`;
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="block text-sm text-slate-400"><span class="font-bold text-slate-300">${s.name}</span> (${s.branch})</span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="text-red-400 text-sm font-bold">Unassigned</span>`;
              
              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-300 font-bold">${subj.subject_code}</td>
                <td class="p-4 font-bold text-slate-200">${subj.subject_name}</td>
                <td class="p-4 text-slate-400 text-sm">${subj.subject_type}</td>
                <td class="p-4">${staffList}</td>
                <td class="p-4 text-right space-x-2">
                  <button onclick="openEditSubjectModal(${JSON.stringify(subj).replace(/"/g, '&quot;')})" class="px-2.5 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 rounded-lg text-sm font-bold transition-premium border border-amber-500/20 cursor-pointer"><span class="material-symbols-rounded text-sm align-middle" style="font-size:14px">edit</span> Edit</button>
                  <button onclick="openAssignStaffModal(this, ${subj.id}, '${currentStaffIds}')" data-subject-name="${subj.subject_name.replace(/"/g, '&quot;')}" class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg text-sm font-bold transition-premium border border-blue-500/20 cursor-pointer">Assign Staff</button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-sm font-bold transition-premium border border-red-500/20 cursor-pointer">Delete</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-400">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-400">Error fetching subjects.</td></tr>`;
        });
    }

    // ---- Branch prefix helpers ----
    function _getBranchPrefix() {
      const modalBatch = document.getElementById('modalFormSubjectBatch');
      const displayBatch = document.getElementById('displaySubjectBatch');
      const batchSelect = document.getElementById('subjectBatchSelect');
      
      let val = '';
      if (modalBatch && modalBatch.value) {
        val = modalBatch.value;
      } else if (displayBatch && displayBatch.innerText) {
        val = displayBatch.innerText;
      } else if (batchSelect && batchSelect.value) {
        val = batchSelect.value;
      }
      
      if (!val) return '';
      // classroom_id format: EL_2026_2029 or EL
      return (val.split('_')[0] || '').toUpperCase();
    }

    function _applyCodePrefix(isRev2026) {
      const prefixEl  = document.getElementById('subjectCodePrefix');
      const rawInput  = document.getElementById('subjectCodeRaw');
      const hiddenEl  = document.getElementById('subjectCode');
      if (!prefixEl || !rawInput || !hiddenEl) return;

      if (isRev2026) {
        const prefix = _getBranchPrefix();
        prefixEl.innerText = prefix + '-';
        prefixEl.classList.remove('hidden');
        prefixEl.classList.add('flex');
        rawInput.placeholder = 'e.g. 1008';
        // Sync hidden field
        const raw = rawInput.value.trim();
        hiddenEl.value = raw ? (prefix + '-' + raw) : '';
      } else {
        prefixEl.classList.add('hidden');
        prefixEl.classList.remove('flex');
        rawInput.placeholder = 'e.g. ENG101';
        hiddenEl.value = rawInput.value.trim();
      }
    }

    // Keep hidden field in sync whenever the user types
    document.addEventListener('DOMContentLoaded', function() {
      const rawInput = document.getElementById('subjectCodeRaw');
      if (rawInput) {
        rawInput.addEventListener('input', function() {
          const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
          _applyCodePrefix(isRev2026);
        });
      }
      // Also re-sync when revision changes
      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          _applyCodePrefix(this.value === 'REV2026');
        });
      }
    });
    // ---- End branch prefix helpers ----

    function openSubjectModal() {
      try {
        const batchSelect = document.getElementById('subjectBatchSelect');
        const semSelect = document.getElementById('subjectSemesterSelect');
        if (!batchSelect || !batchSelect.value) {
          alert("Please select a target batch first.");
          return;
        }
        
        const formEl = document.getElementById('subjectForm');
        if (formEl) formEl.reset();

        // Reset raw code input & hidden field
        const rawInput = document.getElementById('subjectCodeRaw');
        if (rawInput) rawInput.value = '';
        const hiddenCode = document.getElementById('subjectCode');
        if (hiddenCode) hiddenCode.value = '';
        
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = batchSelect.value;
        
        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = batchSelect.value;
        
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = semSelect.value;
        
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem && semSelect) {
          displaySem.innerText = semSelect.options[semSelect.selectedIndex].text;
        }
        
        const modal = document.getElementById('subjectModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }

        const revisionSelect = document.getElementById('subjectRevisionYear');
        if (revisionSelect) {
          if (batchSelect.value.includes('2026') || batchSelect.value.includes('REV2026')) {
            revisionSelect.value = 'REV2026';
          } else {
            revisionSelect.value = 'REV2021';
          }
          syncSubjectTypeOptions(revisionSelect.value);
          _applyCodePrefix(revisionSelect.value === 'REV2026');
        }
      } catch (err) {
        alert("Error opening subject modal: " + err.message);
        console.error('[openSubjectModal] Error:', err);
      }
    }

    function closeSubjectModal() {
      const modal = document.getElementById('subjectModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
      // Reset to Add mode so next open starts fresh
      const editIdEl = document.getElementById('modalEditSubjectId');
      if (editIdEl) editIdEl.value = '';
      const iconEl = document.getElementById('subjectModalIcon');
      if (iconEl) { iconEl.innerText = 'add_box'; iconEl.className = 'material-symbols-rounded text-emerald-400 text-xs'; }
      const titleEl = document.getElementById('subjectModalTitleText');
      if (titleEl) titleEl.innerText = 'Add Curriculum Subject';
      const labelEl = document.getElementById('subjectSubmitLabel');
      if (labelEl) labelEl.innerText = 'Add Subject';
      const btnEl = document.getElementById('subjectSubmitBtn');
      if (btnEl) btnEl.className = 'flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';
    }

    /**
     * Opens the subject modal in EDIT mode, pre-filling existing values.
     * @param {object|string} subjData - The subject object (or JSON string) from the table row.
     */
    function openEditSubjectModal(subjData) {
      try {
        const subj = (typeof subjData === 'string') ? JSON.parse(subjData) : subjData;

        // Switch modal UI to Edit mode
        document.getElementById('subjectModalIcon').innerText = 'edit';
        document.getElementById('subjectModalIcon').className = 'material-symbols-rounded text-amber-400 text-xs';
        document.getElementById('subjectModalTitleText').innerText = 'Edit Subject Details';
        document.getElementById('subjectSubmitLabel').innerText = 'Save Changes';
        document.getElementById('subjectSubmitBtn').className = 'flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';

        // Store the subject ID
        document.getElementById('modalEditSubjectId').value = subj.id;

        // Set batch/semester context immediately (so prefix helper can read it)
        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = subj.classroom_id || '';
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = subj.semester || '';

        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = subj.classroom_id || '';
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem) displaySem.innerText = subj.semester ? 'S' + subj.semester : '';

        // Pre-fill fields
        const revEl = document.getElementById('subjectRevisionYear');
        if (revEl && subj.syllabus_revision_code) {
          revEl.value = subj.syllabus_revision_code;
        }
        
        const isRev2026Edit = (revEl ? revEl.value : '') === 'REV2026';
        const rawInput = document.getElementById('subjectCodeRaw');
        const hiddenCode = document.getElementById('subjectCode');
        
        if (isRev2026Edit) {
          // Extract the prefix and code (e.g. "EL-1008" -> "1008")
          const storedCode = subj.subject_code || '';
          const dashIndex = storedCode.indexOf('-');
          if (rawInput) {
            rawInput.value = dashIndex !== -1 ? storedCode.substring(dashIndex + 1) : storedCode;
          }
          if (hiddenCode) {
            hiddenCode.value = storedCode;
          }
        } else {
          if (rawInput) {
            rawInput.value = subj.subject_code || '';
          }
          if (hiddenCode) {
            hiddenCode.value = subj.subject_code || '';
          }
        }
        
        // Sync badge UI prefix display (reads displayBatch or modalBatch now)
        _applyCodePrefix(isRev2026Edit);
        
        document.getElementById('subjectName').value = subj.subject_name || '';
        syncSubjectTypeOptions(revEl ? revEl.value : 'REV2021', subj.subject_type || 'Theory');

        // Clear any previous alert
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modal = document.getElementById('subjectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      } catch (err) {
        alert('Error opening edit modal: ' + err.message);
        console.error('[openEditSubjectModal]', err);
      }
    }

    function saveSubject(e) {
      e.preventDefault();
      
      // Ensure prefix gets synchronized from the fields right before submit
      const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
      _applyCodePrefix(isRev2026);

      const editId = document.getElementById('modalEditSubjectId').value;
      if (editId) {
        // EDIT mode
        _doUpdateSubject(editId);
      } else {
        // ADD mode
        _doCreateSubject();
      }
    }

    function _doCreateSubject() {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        classroom_id: document.getElementById('modalFormSubjectBatch').value,
        semester: document.getElementById('modalFormSubjectSemester').value,
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
      };

      fetch('/api/hod/batches/subjects/create', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function _doUpdateSubject(subjectId) {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
      };

      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'PUT',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function deleteSubject(subjectId) {
      if(!confirm("Are you sure you want to delete this subject? This will also remove any staff assignments for it.")) return;
      
      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          loadSubjects();
          if (typeof loadModalSubjects === 'function') loadModalSubjects();
        }
        else alert(data.message);
      })
      .catch(() => alert('Failed to delete subject.'));
    }

    let currentAssignStaffIds = [];

    function openAssignStaffModal(btn, subjectId, currentStaffIds) {
      try {
        console.log('[openAssignStaffModal] subjectId:', subjectId, 'currentStaffIds:', currentStaffIds);
        const subjectName = btn.getAttribute('data-subject-name');
        
        const idEl = document.getElementById('assignSubjectId');
        if (idEl) idEl.value = subjectId;
        
        const nameEl = document.getElementById('assignSubjectName');
        if (nameEl) nameEl.innerText = subjectName;
        
        const filterEl = document.getElementById('staffBranchFilter');
        if (filterEl) {
          filterEl.value = window.branchOverride || "{{ session('userBranch') }}" || "";
        }
        
        currentAssignStaffIds = currentStaffIds ? currentStaffIds.split(',') : [];
        
        renderAssignStaffList();

        const alertEl = document.getElementById('assignStaffAlert');
        if (alertEl) alertEl.classList.add('hidden');
        
        const modal = document.getElementById('assignStaffModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      } catch (err) {
        alert("Error opening assign staff modal: " + err.message);
        console.error('[openAssignStaffModal] Error:', err);
      }
    }

    function closeAssignStaffModal() {
      const modal = document.getElementById('assignStaffModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function renderAssignStaffList() {
      const container = document.getElementById('staffCheckboxList');
      const branchFilter = document.getElementById('staffBranchFilter').value;
      
      container.innerHTML = '';
      
      let filteredStaff = allCollegeStaffCache;
      if (branchFilter) {
        filteredStaff = filteredStaff.filter(s => s.branch === branchFilter);
      }

      if (filteredStaff.length === 0) {
        container.innerHTML = '<div class="p-3 text-slate-500 text-sm text-center">No staff found for this branch.</div>';
        return;
      }

      filteredStaff.forEach(staff => {
        const isChecked = currentAssignStaffIds.includes(staff.mobile_no) ? 'checked' : '';
        const div = document.createElement('label');
        div.className = 'flex items-center gap-3 p-2 hover:bg-slate-800/40 rounded-lg cursor-pointer transition-premium border border-transparent hover:border-slate-700/50';
        div.innerHTML = `
          <input type="checkbox" name="assignStaffCb" value="${staff.mobile_no}" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500" ${isChecked}>
          <div class="flex-grow flex justify-between items-center">
            <span class="text-sm font-bold text-slate-200">${staff.name}</span>
            <span class="text-sm text-slate-500 font-mono">${staff.branch} - ${staff.designation}</span>
          </div>
        `;
        container.appendChild(div);
      });
    }

    function assignStaff(e) {
      e.preventDefault();
      const subjectId = document.getElementById('assignSubjectId').value;
      const checkboxes = document.querySelectorAll('input[name="assignStaffCb"]:checked');
      const staffNos = Array.from(checkboxes).map(cb => cb.value);

      const spinner = document.getElementById('assignStaffSpinner');
      const alertEl = document.getElementById('assignStaffAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch(`/api/hod/batches/subjects/${subjectId}/assign-staff`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ staff_mobile_nos: staffNos })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeAssignStaffModal();
          loadSubjects(); // refresh
          loadModalSubjects(); // refresh modal
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    // =========================================================================
    // END SUBJECT ALLOCATION
    // =========================================================================

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
              tr.className = "border-b border-slate-800 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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

    // =========================================================================
    // SUBJECT PROGRESS POPUP CARD LOGIC
    // =========================================================================
    let persistentPopupActive = false;

    function showSubjectProgressPopup(subj, event, isClick = false) {
      if (isClick) {
        persistentPopupActive = !persistentPopupActive;
      }
      
      const popup = document.getElementById('subjectProgressPopup');
      document.getElementById('popupSubjName').innerText = subj.subject_name;
      document.getElementById('popupSubjCode').innerText = subj.subject_code;
      document.getElementById('popupAllottedHours').innerText = (subj.total_hours_allotted || 0) + ' hrs';
      document.getElementById('popupCompletedHours').innerText = (subj.hours_completed || 0) + ' hrs';
      
      // Format Status Colors
      const formatStatus = (elId, status) => {
        const el = document.getElementById(elId);
        el.innerText = status || 'Not Initiated';
        if (!status || status === 'Not Initiated') {
          el.className = 'font-bold text-slate-500';
        } else if (status === 'Pending') {
          el.className = 'font-bold text-amber-400';
        } else {
          el.className = 'font-bold text-green-400';
        }
      };

      formatStatus('popupAssignmentStatus', subj.assignment_initiated);
      formatStatus('popupWrittenTestStatus', subj.written_test_initiated);
      formatStatus('popupMcqStatus', subj.mcq_status);
      formatStatus('popupMidSemStatus', subj.mid_sem_survey_status);
      formatStatus('popupEndSemStatus', subj.end_sem_survey_status);
      
      popup.classList.remove('hidden');
      positionSubjectProgressPopup(event);
    }

    function positionSubjectProgressPopup(event) {
      const popup = document.getElementById('subjectProgressPopup');
      let top = event.clientY + 15;
      let left = event.clientX + 15;
      
      const popupWidth = 288;
      const popupHeight = 240;
      
      if (left + popupWidth > window.innerWidth) {
        left = event.clientX - popupWidth - 15;
      }
      if (top + popupHeight > window.innerHeight) {
        top = event.clientY - popupHeight - 15;
      }
      
      popup.style.top = top + 'px';
      popup.style.left = left + 'px';
    }

    function hideSubjectProgressPopup() {
      if (!persistentPopupActive) {
        const popup = document.getElementById('subjectProgressPopup');
        popup.classList.add('hidden');
      }
    }
    
    // Clear persistent state on closures or transitions
    const originalCloseBatchDetailModal = closeBatchDetailModal;
    closeBatchDetailModal = function() {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalCloseBatchDetailModal === 'function') {
        originalCloseBatchDetailModal();
      }
    };

    const originalSwitchBatchTab = switchBatchTab;
    switchBatchTab = function(tab) {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalSwitchBatchTab === 'function') {
        originalSwitchBatchTab(tab);
      }
    };

    document.addEventListener('click', (e) => {
      const popup = document.getElementById('subjectProgressPopup');
      if (persistentPopupActive && !e.target.closest('tr')) {
        persistentPopupActive = false;
        popup.classList.add('hidden');
      }
    });

    function openStudentDiary(regNo) {
      window.open('/tutor/mentoring-diary/' + regNo, '_blank');
    }

    function openAssignStaffModalFromModal(event, btn, subjectId, currentStaffIds) {
      if (event) event.stopPropagation();
      openAssignStaffModal(btn, subjectId, currentStaffIds);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-sm font-bold mt-2 text-blue-400";
      statusEl.innerText = "Uploading photo...";

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          statusEl.className = "text-sm font-bold mt-2 text-green-400";
          statusEl.innerText = "Photo updated successfully!";

          // Update main profile picture
          const imgEl = document.getElementById('staffProfileImg');
          if (imgEl) {
            imgEl.src = data.photo_url;
          }

          // Update sidebar picture
          const sidebarImg = document.getElementById('sidebarStaffImg');
          if (sidebarImg) {
            sidebarImg.src = data.photo_url;
          }

          setTimeout(() => statusEl.classList.add('hidden'), 3000);
        } else {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = data.message || "Upload failed.";
        }
      })
      .catch(() => {
        statusEl.className = "text-sm font-bold mt-2 text-rose-400";
        statusEl.innerText = "Network error. Please try again.";
      });
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        if (!container) return;
        container.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          // Group by classroom_id
          const groups = {};
          res.data.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render a card for each group
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            const card = document.createElement('div');
            // Catchy glowing orange/purple notification card
            card.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-premium cursor-pointer group relative overflow-hidden";
            card.onclick = () => {
              window.location.href = `/dashboard/lecturer?subject_id=${first.batch_subject_id}&subject_name=${encodeURIComponent(first.subject_name || 'Seminar')}&classroom_id=${encodeURIComponent(cid)}`;
            };

            card.innerHTML = `
              <div class="flex items-center gap-3 min-w-0">
                <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                  <span class="material-symbols-rounded text-lg block">co_present</span>
                </div>
                <div class="min-w-0">
                  <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Seminar Day (${count})</h5>
                  <p class="text-[11px] text-slate-400 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                </div>
              </div>
              <span class="material-symbols-rounded text-slate-600 group-hover:text-blue-400 text-sm transition-premium flex-shrink-0">arrow_forward_ios</span>
            `;
            container.appendChild(card);
          });

          container.classList.remove('hidden');
        } else {
          container.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    // Live AI Status Indicator for HOD
    document.addEventListener("DOMContentLoaded", () => {
      fetch('/api/system/ai-status')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('aiStatusBadge');
          if (badge && data.status === 'SUCCESS') {
            badge.classList.remove('hidden');
            if (data.ai_generation_enabled) {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span> AI Active</span>`;
            } else {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>`;
            }
          }
        })
        .catch(err => console.error("Failed to load system AI status:", err));
    });
  </script>

  <!-- SUBJECT PROGRESS POPUP CARD -->
  <div id="subjectProgressPopup" class="fixed hidden bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-2xl z-[60] w-72 pointer-events-none transition-premium flex flex-col gap-3">
    <div class="flex justify-between items-center border-b border-slate-800 pb-2">
      <h4 id="popupSubjName" class="font-extrabold text-sm text-slate-100 truncate w-48">Subject Name</h4>
      <span id="popupSubjCode" class="font-mono text-xs font-bold text-violet-400 bg-violet-950/40 border border-violet-900/60 px-2 py-0.5 rounded">ENG101</span>
    </div>
    <div class="space-y-2 text-sm">
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">Allotted Hours:</span>
        <span id="popupAllottedHours" class="font-bold text-slate-200">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">Completed Hours:</span>
        <span id="popupCompletedHours" class="font-bold text-slate-200">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">Assignment Initiated:</span>
        <span id="popupAssignmentStatus" class="font-bold text-slate-500">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">Written Test Initiated:</span>
        <span id="popupWrittenTestStatus" class="font-bold text-slate-500">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">MCQ Status:</span>
        <span id="popupMcqStatus" class="font-bold text-slate-500">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">Mid-Sem Survey:</span>
        <span id="popupMidSemStatus" class="font-bold text-slate-500">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-400 text-sm">End-Sem Survey:</span>
        <span id="popupEndSemStatus" class="font-bold text-slate-500">Not Initiated</span>
      </div>
    </div>
  </div>

  @include('mentoring_diary_modal')
  @include('partials.support_desk_overlay')

</body>
</html>
