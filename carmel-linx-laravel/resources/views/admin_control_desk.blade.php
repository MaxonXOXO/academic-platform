<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Admin Control Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html {
      font-size: 90%;
    }
    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
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
  </style>

  <style>
    /* Universal Typography & Card Styles standard overrides */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
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

    /* Light Theme Styling Overrides */
    body.light-theme {
      background-color: #f8fafc !important;
      color: #0f172a !important;
    }
    body.light-theme aside {
      background-color: #ffffff !important;
      border-right-color: #cbd5e1 !important;
      color: #0f172a !important;
    }
    body.light-theme aside div {
      border-color: #e2e8f0 !important;
    }
    body.light-theme aside nav button, body.light-theme aside nav a {
      color: #334155 !important;
    }
    body.light-theme aside nav button:hover, body.light-theme aside nav a:hover {
      background-color: #f1f5f9 !important;
      color: #0f172a !important;
    }
    body.light-theme header {
      background-color: rgba(255, 255, 255, 0.95) !important;
      border-bottom-color: #cbd5e1 !important;
      color: #0f172a !important;
    }
    body.light-theme header h1, body.light-theme header span:not(.bg-blue-500):not(.bg-rose-600):not(.bg-amber-400):not(.bg-amber-500) {
      color: #0f172a !important;
    }
    body.light-theme main {
      background-color: #f8fafc !important;
    }
    body.light-theme .bg-slate-950\/40, 
    body.light-theme .bg-slate-950\/30, 
    body.light-theme .bg-slate-950,
    body.light-theme .bg-slate-900\/40, 
    body.light-theme .bg-slate-900\/60, 
    body.light-theme .bg-slate-900\/30,
    body.light-theme .bg-slate-900 {
      background-color: #ffffff !important;
      border-color: #cbd5e1 !important;
      color: #0f172a !important;
    }
    body.light-theme .text-slate-100, 
    body.light-theme .text-slate-200, 
    body.light-theme .text-slate-300, 
    body.light-theme .text-white {
      color: #0f172a !important;
    }
    body.light-theme .text-slate-400, 
    body.light-theme .text-slate-500 {
      color: #475569 !important;
    }
    body.light-theme .border-slate-800, 
    body.light-theme .border-slate-800\/80,
    body.light-theme .border-slate-800\/60, 
    body.light-theme .border-slate-800\/40, 
    body.light-theme .border-slate-700 {
      border-color: #e2e8f0 !important;
    }
    body.light-theme table tr {
      border-color: #e2e8f0 !important;
    }
    body.light-theme table tr:hover {
      background-color: #f1f5f9 !important;
    }
    body.light-theme input, body.light-theme select, body.light-theme textarea {
      background-color: #ffffff !important;
      color: #0f172a !important;
      border-color: #cbd5e1 !important;
    }
    body.light-theme .bg-slate-800 {
      background-color: #f1f5f9 !important;
      color: #0f172a !important;
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 h-screen flex flex-col md:flex-row overflow-hidden">
  <script>
    (function() {
      if (localStorage.getItem('carmel_theme') === 'light') {
        document.body.classList.add('light-theme');
      }
    })();
  </script>

  <meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-5 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#60a5fa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Control Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div onclick="openExecutiveProfileModal()" class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3 cursor-pointer hover:bg-slate-800/60 transition-premium" title="Click to view My Profile & Security Settings">
      <div class="relative group shrink-0">
        <div id="staffAvatarWrapper" class="w-11 h-11 rounded-full overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center shadow-inner relative">
          <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-full h-full object-cover">
        </div>
        <label for="staffPhotoUploadInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-sm font-bold text-center p-0.5" onclick="event.stopPropagation();">
          <span class="material-symbols-rounded text-sm">photo_camera</span>
        </label>
        <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
      </div>
      <div class="overflow-hidden">
        <span class="font-bold text-sm block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-blue-400 block uppercase tracking-wider">{{ str_replace('_', ' ', session('userRole')) }}</span>
        <div id="staffPhotoUploadStatus" class="text-sm font-bold text-green-400 hidden"></div>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">dashboard</span> Dashboard Overview
      </button>

      <a href="/dashboard/lecturer" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-950/30 hover:text-sky-300 cursor-pointer no-underline block border border-sky-900/40 bg-sky-950/20">
         <span class="material-symbols-rounded text-lg text-sky-400">grid_view</span> My Batches & Virtual Classes
      </a>
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">group</span> User Directory
      </button>
      <button id="navBackups" onclick="switchPanel('backups')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">database</span> Drive Backups
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">receipt_long</span> Audit Trail
      </button>
      <button id="navProfile" onclick="openExecutiveProfileModal()" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">manage_accounts</span> My Profile & Security
      </button>
      <button id="navSettings" onclick="switchPanel('settings')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">settings</span> System Settings
      </button>

      @if(session('userRole') === 'Super_Admin')
      <a href="/superadmin/show-users" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block border border-slate-800 bg-slate-900/40">
         <span class="material-symbols-rounded text-lg">key</span> User Credentials Table
      </a>
      @endif

      <a href="/staff/professional-activities" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">school</span> Professional Activities
      </a>

      <a href="/staff/leave/reports" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">event_note</span> All-Dept Master Leave Ledger
      </a>

      <!-- Self-Financing Staff Attendance Master Ledger Link -->
      <a href="/sf-attendance/attendance-report" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-cyan-300 cursor-pointer no-underline block border border-cyan-900/40 bg-cyan-950/20">
         <span class="material-symbols-rounded text-lg text-cyan-400">how_to_reg</span> SF Staff Attendance Master Log
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of Carmel Linx Control Desk?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
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
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <div class="flex items-center gap-3 md:gap-4">
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Dashboard Overview</h1>
        
        <!-- AI System Status Badge -->
        <div id="topAiStatusBadge" onclick="switchPanel('settings')" class="hidden items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-300 border border-slate-700 transition-all cursor-pointer group" title="Click to manage AI System Settings">
          <span id="topAiStatusDot" class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span id="topAiStatusText">AI Active</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')

        <!-- Theme Toggle Button -->
        <button id="themeToggleBtn" onclick="toggleTheme()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm" title="Toggle Light/Dark Mode">
          <span id="themeToggleIcon" class="material-symbols-rounded text-base text-amber-400">light_mode</span>
          <span id="themeToggleText" class="hidden sm:inline">Light Mode</span>
        </button>

        <button onclick="toggleAdminSupportDeskDrawer()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-blue-500/40 bg-blue-500/10 hover:bg-blue-500/20 text-blue-300 font-bold text-xs transition-premium cursor-pointer shadow-md" title="Click to open Live Remote Support Desk">
          <span class="material-symbols-rounded text-base text-blue-400">desktop_windows</span>
          <span>Support Desk</span>
          <span id="adminPendingSupportBadge" class="hidden px-1.5 py-0.2 bg-rose-600 text-white rounded-full text-[10px] font-black animate-pulse">0</span>
        </button>
        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-orange-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-[10px] font-bold transition-premium border text-[10px] text-xs"></div>

      <!-- PANEL 1: DASHBOARD OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Grid (Top Row - 5 KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
          <!-- Total Staff -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-slate-700 transition">
            <div class="bg-blue-500/10 text-blue-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">badge</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Staff</span>
              <span id="statTotalStaff" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Total Students -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-slate-700 transition">
            <div class="bg-sky-500/10 text-sky-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">school</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Students</span>
              <span id="statTotalStudents" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Pending Approvals -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-slate-700 transition">
            <div class="bg-amber-500/10 text-amber-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">pending_actions</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Pending Approvals</span>
              <span id="statPendingApprovals" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Classrooms -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-slate-700 transition">
            <div class="bg-emerald-500/10 text-emerald-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">meeting_room</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Classrooms</span>
              <span id="statTotalClassrooms" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Academic Pass Rate (Moved to Top Row!) -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-slate-700 transition">
            <div class="bg-indigo-500/10 text-indigo-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">insights</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Academic Pass Rate</span>
              <span id="execAcademicPassRate" class="font-black text-indigo-300 text-lg leading-tight block">91.4% Overall</span>
            </div>
          </div>
        </div>

        <!-- EXECUTIVE DAILY OPERATIONAL STATUS ROW (Compact 3 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
          <!-- Daily Staff Leave Snapshot Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-slate-700/70 transition-all duration-200 relative">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                  <span class="material-symbols-rounded text-xs">event_busy</span>
                </span> Staff On Leave Today
              </span>
              <span id="execStaffLeaveTotal" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm">0 Active</span>
            </div>
            
            <!-- All Leave Types in Single Row Grid with Hover Tooltip Popups -->
            <div class="grid grid-cols-6 gap-1 text-xs w-full">
              <!-- CL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-amber-500/40 transition block text-center truncate shadow-inner">
                  CL: <strong id="execLeaveCL" class="text-amber-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-amber-400">Casual Leave (CL)</span>
                    <span id="popupCountCL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListCL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on CL today</span>
                  </div>
                </div>
              </div>

              <!-- CCL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-amber-500/40 transition block text-center truncate shadow-inner">
                  CCL: <strong id="execLeaveCCL" class="text-amber-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-amber-400">Compensatory CL (CCL)</span>
                    <span id="popupCountCCL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListCCL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on CCL today</span>
                  </div>
                </div>
              </div>

              <!-- DL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-sky-500/40 transition block text-center truncate shadow-inner">
                  DL: <strong id="execLeaveDL" class="text-sky-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-sky-400">Duty Leave (DL)</span>
                    <span id="popupCountDL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListDL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on DL today</span>
                  </div>
                </div>
              </div>

              <!-- ML Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-rose-500/40 transition block text-center truncate shadow-inner">
                  ML: <strong id="execLeaveML" class="text-rose-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-rose-400">Medical Leave (ML)</span>
                    <span id="popupCountML" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListML" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on ML today</span>
                  </div>
                </div>
              </div>

              <!-- LOP Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-purple-500/40 transition block text-center truncate shadow-inner">
                  LOP: <strong id="execLeaveLOP" class="text-purple-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full right-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-purple-400">Loss of Pay (LOP)</span>
                    <span id="popupCountLOP" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListLOP" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on LOP today</span>
                  </div>
                </div>
              </div>

              <!-- OTHERS Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-emerald-500/40 transition block text-center truncate shadow-inner">
                  Oth: <strong id="execLeaveOTHERS" class="text-emerald-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full right-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-emerald-400">Other Leaves</span>
                    <span id="popupCountOTHERS" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListOTHERS" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on other leaves today</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Daily Student Attendance Rate Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-slate-700/70 transition-all duration-200">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0">
                  <span class="material-symbols-rounded text-xs">how_to_reg</span>
                </span> Student Attendance
              </span>
              <span id="execStudentAttPct" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm">94.8% Active</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-300">
              <span class="text-slate-400 text-[11px]">Real-Time Campus Ratio</span>
              <span class="font-bold text-slate-200 text-[11px]">Institution Average</span>
            </div>
          </div>

          <!-- Today's Campus & Academic Events Card -->
          <div onclick="openTodayEventsModal()" class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-sky-500/40 hover:bg-slate-900/70 transition-all duration-200 cursor-pointer group">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-sky-500/20">
                  <span class="material-symbols-rounded text-xs">calendar_month</span>
                </span> Today's Events
              </span>
              <span id="execEventsCountBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm group-hover:border-sky-400">Scheduled</span>
            </div>
            <div id="execTodayEventsList" class="space-y-1 text-xs text-slate-300 overflow-hidden">
              <div class="flex items-center gap-1.5 truncate">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 shrink-0"></span>
                <span class="truncate font-medium text-[11px]">SITTTR Academic Schedule</span>
              </div>
              <div class="flex items-center gap-1.5 truncate">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                <span class="truncate text-slate-400 text-[11px]">Department CIA Audits</span>
              </div>
            </div>
            <div class="mt-2 pt-1 border-t border-slate-800/40 flex items-center justify-between text-[10px] text-sky-400 font-bold group-hover:text-sky-300">
              <span>View events by categories</span>
              <span class="material-symbols-rounded text-xs group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </div>
          </div>
        </div>

        <!-- Executive Dashboard Actions & Broadcast Desks (3 Cards Row) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Department HOD Dashboard Overrides Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-blue-500/10 text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">admin_panel_settings</span>
                  </span> HOD Dashboard Overrides
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 shadow-sm">Direct Supervision</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Directly access and supervise any department HOD console to manage batch allocations &amp; curriculum updates.
              </p>
              <!-- 8 Compact Branch Buttons Grid -->
              <div class="grid grid-cols-4 gap-2">
                <a href="/dashboard/principal/department/EL" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-amber-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-amber-400 group-hover:scale-110 transition-premium">settings_input_component</span>
                  <span class="font-extrabold text-xs text-slate-200">EL</span>
                </a>
                <a href="/dashboard/principal/department/ME" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-emerald-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-emerald-400 group-hover:scale-110 transition-premium">precision_manufacturing</span>
                  <span class="font-extrabold text-xs text-slate-200">ME</span>
                </a>
                <a href="/dashboard/principal/department/CE" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-pink-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-pink-400 group-hover:scale-110 transition-premium">domain</span>
                  <span class="font-extrabold text-xs text-slate-200">CE</span>
                </a>
                <a href="/dashboard/principal/department/EEE" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-rose-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-rose-400 group-hover:scale-110 transition-premium">bolt</span>
                  <span class="font-extrabold text-xs text-slate-200">EEE</span>
                </a>
                <a href="/dashboard/principal/department/CT" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-purple-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-purple-400 group-hover:scale-110 transition-premium">computer</span>
                  <span class="font-extrabold text-xs text-slate-200">CT</span>
                </a>
                <a href="/dashboard/principal/department/AU" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-indigo-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-indigo-400 group-hover:scale-110 transition-premium">directions_car</span>
                  <span class="font-extrabold text-xs text-slate-200">AU</span>
                </a>
                <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-teal-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-teal-400 group-hover:scale-110 transition-premium">calculate</span>
                  <span class="font-extrabold text-xs text-slate-200">Gen-A</span>
                </a>
                <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-cyan-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-cyan-400 group-hover:scale-110 transition-premium">functions</span>
                  <span class="font-extrabold text-xs text-slate-200">Gen-SF</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Institutional Flash Notice Broadcast Desk Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">campaign</span>
                  </span> Flash Notice Broadcast Desk
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm">Executive Broadcast</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Instantly broadcast official notices or circulars with attachments to staff and students immediately or on schedule.
              </p>
              <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatSent" class="block font-black text-slate-200 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Broadcasted</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatSched" class="block font-black text-amber-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Scheduled</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatUrgent" class="block font-black text-rose-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Urgent</span>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap gap-2 pt-1">
              <button onclick="openFlashNoticeModal()" class="flex-1 px-3 py-1.5 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-500 hover:to-blue-500 rounded-lg font-bold text-white transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 shadow-md">
                <span class="material-symbols-rounded text-sm">send</span> Broadcast Notice
              </button>
              <button onclick="openFlashNoticeHistoryModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-300 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 border border-slate-700">
                <span class="material-symbols-rounded text-sm">history</span> Log
              </button>
            </div>
          </div>

          <!-- College Targeted Event Scheduler Desk Card (NEW MODULE) -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-emerald-500/10 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">event_available</span>
                  </span> College Event Scheduler
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-sm">Targeted Dispatch</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Schedule institutional events for College, Depts, Staff, Students, or Special Groups (Placement, NSS/NCC, Sports).
              </p>
              <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatCollege" class="block font-black text-emerald-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Campus Wide</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatDept" class="block font-black text-sky-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Dept/Staff</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatSpecial" class="block font-black text-purple-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Special Groups</span>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap gap-2 pt-1">
              <button onclick="openPrincipalScheduleEventModal()" class="flex-1 px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-lg font-bold text-white transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 shadow-md">
                <span class="material-symbols-rounded text-sm">edit_calendar</span> Schedule Event
              </button>
              <button onclick="openPrincipalScheduleEventHistoryModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-300 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 border border-slate-700">
                <span class="material-symbols-rounded text-sm">view_list</span> Event Log
              </button>
            </div>
          </div>
        </div>

        <!-- EXECUTIVE OPTION 2: COMPACT 3-SEMESTER ACADEMIC PASS MATRIX -->
        <details class="group bg-slate-900/50 border border-slate-800/80 rounded-2xl shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
          <summary class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden gap-3">
            <div class="flex items-center gap-3">
              <span class="p-1.5 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-sm">analytics</span>
              </span>
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                  <h3 class="font-black text-slate-200 text-sm">Previous Semester Branch Academic Pass Matrix</h3>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">3 Semesters per Dept</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Department semester pass percentages (S1/S3/S5 or S2/S4/S6) uploaded by HODs.</p>
              </div>
            </div>
            <div class="flex items-center gap-2.5 self-end sm:self-auto shrink-0">
              <a href="/admin/executive-digest/pdf?print=true" target="_blank" onclick="event.stopPropagation()" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-amber-300 font-bold rounded-lg text-[11px] transition border border-slate-700 no-underline flex items-center gap-1 shrink-0 shadow-sm">
                <span class="material-symbols-rounded text-xs">print</span> Board Report A4
              </a>
              <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-800/80 hover:bg-slate-700/80 rounded-lg border border-slate-700/80 text-slate-300 text-xs font-bold transition">
                <span class="group-open:hidden">Expand Matrix</span>
                <span class="hidden group-open:inline">Fold Matrix</span>
                <span class="material-symbols-rounded text-base transition-transform duration-200 group-open:rotate-180">expand_more</span>
              </div>
            </div>
          </summary>

          <div class="p-4 sm:p-5 pt-0 space-y-3 border-t border-slate-800/40">
            <!-- Ultra-Compact High-Density Table -->
            <div class="overflow-x-auto pt-3">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/80 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <th class="py-2 px-3">Branch Code &amp; Name</th>
                    <th class="py-2 px-3 text-center">Sem 1 / 2</th>
                    <th class="py-2 px-3 text-center">Sem 3 / 4</th>
                    <th class="py-2 px-3 text-center">Sem 5 / 6</th>
                    <th class="py-2 px-3 text-center">Dept Avg</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-medium">
                  <!-- EL -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-amber-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/30 rounded text-[10px] font-mono">EL</span>
                      <span class="text-slate-200 text-xs">Electronics Engg</span>
                    </td>
                    <td id="sem_EL_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">91.6%</td>
                    <td id="sem_EL_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.5%</td>
                    <td id="sem_EL_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">92.7%</td>
                    <td id="sem_EL_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">91.3%</td>
                  </tr>

                  <!-- ME -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-emerald-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 rounded text-[10px] font-mono">ME</span>
                      <span class="text-slate-200 text-xs">Mechanical Engg</span>
                    </td>
                    <td id="sem_ME_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.1%</td>
                    <td id="sem_ME_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">88.3%</td>
                    <td id="sem_ME_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.9%</td>
                    <td id="sem_ME_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">87.8%</td>
                  </tr>

                  <!-- CE -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-pink-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-pink-500/10 border border-pink-500/30 rounded text-[10px] font-mono">CE</span>
                      <span class="text-slate-200 text-xs">Civil Engg</span>
                    </td>
                    <td id="sem_CE_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.6%</td>
                    <td id="sem_CE_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">91.0%</td>
                    <td id="sem_CE_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.1%</td>
                    <td id="sem_CE_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">89.9%</td>
                  </tr>

                  <!-- EEE -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-rose-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-rose-500/10 border border-rose-500/30 rounded text-[10px] font-mono">EEE</span>
                      <span class="text-slate-200 text-xs">Electrical Engg</span>
                    </td>
                    <td id="sem_EEE_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">90.9%</td>
                    <td id="sem_EEE_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">90.7%</td>
                    <td id="sem_EEE_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">92.3%</td>
                    <td id="sem_EEE_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">91.3%</td>
                  </tr>

                  <!-- CT -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-purple-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-purple-500/10 border border-purple-500/30 rounded text-[10px] font-mono">CT</span>
                      <span class="text-slate-200 text-xs">Computer Engg</span>
                    </td>
                    <td id="sem_CT_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">93.7%</td>
                    <td id="sem_CT_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">95.1%</td>
                    <td id="sem_CT_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">95.0%</td>
                    <td id="sem_CT_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">94.6%</td>
                  </tr>

                  <!-- AU -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-indigo-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/30 rounded text-[10px] font-mono">AU</span>
                      <span class="text-slate-200 text-xs">Automobile Engg</span>
                    </td>
                    <td id="sem_AU_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">88.0%</td>
                    <td id="sem_AU_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.5%</td>
                    <td id="sem_AU_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.1%</td>
                    <td id="sem_AU_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">88.2%</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Secondary Compliance Indicators Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 border-t border-slate-800/60">
              <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-2.5">
                <span class="material-symbols-rounded text-indigo-400 text-lg">workspace_premium</span>
                <span class="text-xs text-slate-400">Faculty FDPs &amp; Workshops: <strong id="execFdpCount" class="text-white font-bold">12 Verified</strong></span>
              </div>
              <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-2.5">
                <span class="material-symbols-rounded text-emerald-400 text-lg">assignment_turned_in</span>
                <span class="text-xs text-slate-400">NBA Attainment Average: <strong id="execCoPoAvg" class="text-white font-bold">88.5% CO-PO</strong></span>
              </div>
            </div>
          </div>
        </details>
      </div>

      <!-- PANEL 2: USER DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-base font-bold text-slate-200">Registered Accounts</h3>
            <p class="text-sm text-slate-400 mt-0.5">Filter, search, audit, and manage profile lifecycle states.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-sm">
            <span class="material-symbols-rounded text-sm">person_add</span> Register User
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search input -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Branch select -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Branch Code</label>
            <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
              <option value="">All Branches</option>
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="GEN">General Science (GEN)</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Super_Admin">Super Admin</option>
              <option value="Chairman">Chairman</option>
              <option value="Admin">Admin</option>
              <option value="Principal">Principal</option>
              <option value="HOD">Head of Department (HOD)</option>
              <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
              <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
              <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
              <option value="Lecturer">Lecturers</option>
              <option value="Demonstrator">Demonstrators</option>
              <option value="Physical_Instructor">Physical Instructors</option>
              <option value="Trade_Instructor">Trade Instructors</option>
              <option value="Tradesman">Tradesmen</option>
              <option value="Laboratory_Assistant">Laboratory Assistants</option>
              <option value="Workshop_Instructor">Workshop Instructors</option>
              <option value="Workshop_Superintendent">Workshop Superintendent</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="max-h-[calc(100vh-320px)] overflow-y-auto overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-xs md:text-sm">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-450 font-bold">
                  <th class="p-2.5 md:p-3">Profile</th>
                  <th class="p-2.5 md:p-3">Mobile / Reg No</th>
                  <th class="p-2.5 md:p-3">Branch</th>
                  <th class="p-2.5 md:p-3">Role Designation</th>
                  <th class="p-2.5 md:p-3">Account Status</th>
                  <th class="p-2.5 md:p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- User rows render dynamically via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: DRIVE BACKUPS -->
      <div id="panelBackups" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl max-w-xl mx-auto space-y-5">
          <div class="text-center space-y-2">
            <span class="material-symbols-rounded text-blue-500 text-5xl">cloud_upload</span>
            <h3 class="font-black text-slate-200 text-lg">Google Drive Sync Desk</h3>
            <p class="text-[10px] text-slate-400 leading-relaxed text-[10px] text-xs">
              Compile a complete `.sql` schema and table rows database dump to save locally and sync immediately to your Google Drive backup folder.
            </p>
          </div>

          <div class="border-t border-slate-800/60 pt-4 space-y-3">
            <div class="flex justify-between items-center text-[10px] border-b border-slate-800/30 pb-3 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">MySQL Connection</span>
              <span class="font-bold text-green-400">127.0.0.1 (Active)</span>
            </div>
            <div class="flex justify-between items-center text-[10px] border-b border-slate-800/30 pb-3 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">Backup Target Database</span>
              <span class="font-bold text-slate-200">carmel_linx_db</span>
            </div>
            <div class="flex justify-between items-center text-[10px] pb-1 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">Drive backup target ID</span>
              <span class="font-bold text-slate-400 truncate max-w-[200px]" title="{{ env('GOOGLE_DRIVE_FOLDER_ID') ?: 'Not configured' }}">
                {{ env('GOOGLE_DRIVE_FOLDER_ID') ?: 'Not configured' }}
              </span>
            </div>
          </div>

          <div id="backupAlert" class="hidden p-4 rounded-xl text-[10px] font-bold transition-premium border text-[10px] text-xs"></div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a href="/api/system/backup/download" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition-premium flex items-center justify-center gap-2 text-xs no-underline">
              <span class="material-symbols-rounded text-base text-emerald-400">download</span>
              <span>Download Instant SQL File</span>
            </a>

            <button id="btnTriggerBackup" onclick="runBackup()" class="w-full py-3 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold transition-premium flex items-center justify-center gap-2 shadow-lg shadow-blue-500/15 cursor-pointer text-xs">
              <span id="btnBackupText">Initialize Drive Sync</span>
              <div id="backupSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
            </button>
          </div>
        </div>
      </div>

      <!-- PANEL 4: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-[10px] text-sm">System Audit Trail</h3>
            <p class="text-[10px] text-slate-400 mt-1 text-[10px] text-xs">Lifecycle events, password resets, status changes, and registration records.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-[10px] text-sm">sync</span> Refresh Log
          </button>
        </div>

        <!-- Audit Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
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

      <!-- PANEL 5: SYSTEM SETTINGS -->
      <div id="panelSettings" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">System Settings &amp; API Controls</h3>
            <p class="text-xs text-slate-400 mt-1">Configure global API integrations, AI credits saving switches, and local fallbacks.</p>
          </div>
          <button onclick="switchPanel('dashboard')" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold text-xs transition flex items-center gap-1.5 cursor-pointer border border-slate-700">
            <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Dashboard
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 space-y-6">
          <div class="flex items-center justify-between p-4 bg-slate-900/40 border border-slate-800/60 rounded-xl">
            <div class="space-y-1 pr-4">
              <h4 class="font-bold text-slate-200 text-sm flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400 text-lg">auto_awesome</span> Gemini AI Generation
              </h4>
              <p class="text-xs text-slate-400 leading-relaxed">
                Toggle Gemini 2.5 Flash AI integration across the portal. When deactivated (Offline Mode), all syllabus planners, MCQs, and question generation operations will read strictly from local databases and question banks to save API credit costs.
              </p>
            </div>
            <div class="shrink-0 flex items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="settingAiEnabled" class="sr-only peer" onchange="saveSystemSettings()">
                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-300 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>
          </div>
          
          <div id="settingsSaveAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>
        </div>
      </div>

    </div>
  </main>

  <!-- EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">edit</span> Edit Staff Details
        </h3>
        <button onclick="closeEditStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Admin">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
            <option value="Principal">Principal</option>
            <option value="HOD">Head of Department (HOD)</option>
            <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
            <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
            <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Demonstrator">Demonstrator</option>
            <option value="Physical_Instructor">Physical Instructor</option>
            <option value="Trade_Instructor">Trade Instructor</option>
            <option value="Tradesman">Tradesman</option>
            <option value="Laboratory_Assistant">Laboratory Assistant</option>
            <option value="Workshop_Instructor">Workshop Instructor</option>
            <option value="Workshop_Superintendent">Workshop Superintendent</option>
            <option value="Super_Admin">Super Admin</option>
            <option value="Chairman">Chairman</option>
            <option value="Admin">Admin</option>
          </select>
        </div>

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-sm flex items-center justify-center gap-1.5">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-[10px] text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer text-[10px] text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
            <thead>
              <tr class="bg-slate-950/80 border-b border-slate-800 text-slate-400 font-bold">
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
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <select id="directRegStudentBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
              </select>
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" value="2026">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
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
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
                <option value="HOD">Head of Department (HOD)</option>
                <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
                <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Physical_Instructor">Physical Instructor</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
                <option value="Workshop_Superintendent">Workshop Superintendent</option>
                <option value="Principal">Principal</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="directRegStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Admin">Administration</option>
            </select>
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-955 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-[10px] text-xs">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "dashboard";
    let selectedUserForReset = null;

    // Load initial data on mount
    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
      loadUsers();
      loadSettings(); // Loads AI generation status immediately for top header badge
      if (activePanel === 'audit') loadAuditTrail();
    });



    // CSRF Token Helper
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    // Switch view panel
    function switchPanel(panelId) {
      activePanel = panelId;
      
      const panels = ['dashboard', 'directory', 'backups', 'audit', 'settings'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-white hover:bg-slate-800 cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      // Update Header Title
      const titles = {
        'dashboard': 'Dashboard Overview',
        'directory': 'User Accounts Directory',
        'backups': 'Database Sync & Backup',
        'audit': 'System Audit Trail',
        'settings': 'System Settings & Controls'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'dashboard') loadStats();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'settings') loadSettings();
    }

    // Update top header AI status badge
    function updateAiStatusBadge(enabled) {
      const badge = document.getElementById('topAiStatusBadge');
      const dot = document.getElementById('topAiStatusDot');
      const text = document.getElementById('topAiStatusText');
      const checkbox = document.getElementById('settingAiEnabled');

      if (checkbox) checkbox.checked = !!enabled;
      if (!badge || !text) return;

      badge.classList.remove('hidden');
      badge.classList.add('flex');

      if (enabled) {
        badge.className = "flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-300 border border-slate-700 transition-all cursor-pointer group";
        if (dot) dot.className = "w-2 h-2 rounded-full bg-emerald-400";
        text.innerText = "AI Active";
      } else {
        badge.className = "flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-400 border border-slate-700 transition-all cursor-pointer group";
        if (dot) dot.className = "w-2 h-2 rounded-full bg-slate-500";
        text.innerText = "AI Off";
      }
    }

    // Load settings from backend
    function loadSettings() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/admin/settings')
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const isEnabled = !!data.settings.ai_generation_enabled;
            updateAiStatusBadge(isEnabled);
          }
        })
        .catch(() => {
          if (indicator) indicator.classList.add('hidden');
        });
    }

    // Save settings to backend
    function saveSystemSettings() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');
      
      const aiEnabled = document.getElementById('settingAiEnabled').checked;
      const alert = document.getElementById('settingsSaveAlert');
      if (alert) alert.classList.add('hidden');

      fetch('/api/admin/settings', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ ai_generation_enabled: aiEnabled })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          updateAiStatusBadge(aiEnabled);
          if (alert) {
            alert.className = "p-3 rounded-xl bg-green-950/40 text-green-400 border border-green-900/60 block text-xs font-bold";
            alert.innerText = data.message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
          }
        } else {
          if (alert) {
            alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-xs font-bold";
            alert.innerText = data.message;
            alert.classList.remove('hidden');
          }
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        if (alert) {
          alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-xs font-bold";
          alert.innerText = "Failed to save settings.";
          alert.classList.remove('hidden');
        }
      });
    }

    // Display messages
    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    // Load Stats
    function loadStats() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStaff').innerText = data.stats.totalStaff;
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPendingApprovals').innerText = data.stats.pendingApprovals;
            document.getElementById('statTotalClassrooms').innerText = data.stats.totalClassrooms;
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    // Load Users
    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&branch=${branch}&role=${role}&status=${status}`;

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

    // Render table rows
    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="p-8 text-center text-slate-500 font-medium font-sans">
              No matching registered profiles found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        // Status Badge Styling
        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;
        }

        // Action Options depending on current status
        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded-lg text-xs font-bold text-white transition-premium cursor-pointer">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded-lg text-xs font-bold text-red-300 transition-premium cursor-pointer">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs font-bold text-white transition-premium cursor-pointer">
              Activate
            </button>
          `;
        }

        // Role Designation selector (for Staff members only)
        let roleCol = `<span class="text-xs font-bold text-slate-300">${user.role}</span>`;
        if (user.type === 'staff') {
          roleCol = `
            <select onchange="updateDesignation('${user.id}', this.value)" class="bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-xs text-white outline-none cursor-pointer max-w-[150px] truncate">
              <option value="Super_Admin" ${user.role === 'Super_Admin' ? 'selected' : ''}>Super Admin</option>
              <option value="Admin" ${user.role === 'Admin' ? 'selected' : ''}>Admin</option>
              <option value="Principal" ${user.role === 'Principal' ? 'selected' : ''}>Principal</option>
              <option value="HOD" ${user.role === 'HOD' ? 'selected' : ''}>HOD</option>
              <option value="Gen_Dept_Coordinator_Aided" ${user.role === 'Gen_Dept_Coordinator_Aided' ? 'selected' : ''}>Gen Dept Coordinator Aided</option>
              <option value="Gen_Dept_Coordinator_Self_Finance" ${user.role === 'Gen_Dept_Coordinator_Self_Finance' ? 'selected' : ''}>Gen Dept Coordinator Self Finance</option>
              <option value="Tutor" ${user.role === 'Tutor' ? 'selected' : ''}>Tutor</option>
              <option value="Lecturer" ${user.role === 'Lecturer' ? 'selected' : ''}>Lecturer</option>
              <option value="Demonstrator" ${user.role === 'Demonstrator' ? 'selected' : ''}>Demonstrator</option>
              <option value="Physical_Instructor" ${user.role === 'Physical_Instructor' || user.role === 'Physical Instructor' ? 'selected' : ''}>Physical Instructor</option>
              <option value="Trade_Instructor" ${user.role === 'Trade_Instructor' ? 'selected' : ''}>Trade Instructor</option>
              <option value="Tradesman" ${user.role === 'Tradesman' ? 'selected' : ''}>Tradesman</option>
              <option value="Laboratory_Assistant" ${user.role === 'Laboratory_Assistant' ? 'selected' : ''}>Laboratory Assistant</option>
              <option value="Workshop_Instructor" ${user.role === 'Workshop_Instructor' ? 'selected' : ''}>Workshop Instructor</option>
              <option value="Workshop_Superintendent" ${user.role === 'Workshop_Superintendent' ? 'selected' : ''}>Workshop Superintendent</option>
            </select>
          `;
        }

        let idColumnHtml = `<span class="font-mono font-bold text-slate-300 text-xs">${user.id}</span>`;
        if (user.type === 'staff') {
          idColumnHtml = `
            <a href="javascript:void(0)" 
               onclick="openEditStaffModal('${user.id}', '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.branch}', '${user.role}')" 
               class="text-blue-400 hover:text-blue-300 underline font-mono font-bold text-xs transition-premium" 
               title="Modify details for ${user.name}">
              ${user.id}
            </a>
          `;
        }

        tr.innerHTML = `
          <td class="p-2.5 md:p-3 flex items-center gap-2.5">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-7 h-7 rounded-full object-cover border border-slate-800 shadow shrink-0">
            <div class="min-w-0 overflow-hidden">
              <span class="font-bold text-slate-100 block text-xs md:text-sm truncate max-w-[140px] lg:max-w-[180px]">${user.name}</span>
              <span class="text-[11px] text-slate-500 block truncate max-w-[140px] lg:max-w-[180px]">${user.email}</span>
            </div>
          </td>
          <td class="p-2.5 md:p-3 font-mono text-xs md:text-sm shrink-0">${idColumnHtml}</td>
          <td class="p-2.5 md:p-3"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-2.5 md:p-3">${roleCol}</td>
          <td class="p-2.5 md:p-3">${statusBadge}</td>
          <td class="p-2.5 md:p-3 text-right">
            <div class="flex items-center justify-end gap-1">
              ${toggleButton}
              <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Reset Pwd
              </button>
              <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer" title="View Audit Trail">
                Audit
              </button>
              <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded-lg text-xs font-bold transition-premium cursor-pointer" title="Delete User">
                Delete
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Toggle User Status AJAX
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

    // Update Designation AJAX
    function updateDesignation(userId, newRole) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/change-role', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, newRole })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Staff designation promoted successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to change staff designation.', true);
      });
    }

    // Password reset modal triggers
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

    // Edit Staff Modal JS handlers
    function openEditStaffModal(mobileNo, name, email, branch, designation) {
      document.getElementById('editStaffMobile').value = mobileNo;
      document.getElementById('editStaffName').value = name;
      document.getElementById('editStaffEmail').value = email;
      document.getElementById('editStaffBranch').value = branch;
      document.getElementById('editStaffDesig').value = designation;
      document.getElementById('editStaffAlert').classList.add('hidden');

      const modal = document.getElementById('editStaffModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditStaffModal() {
      const modal = document.getElementById('editStaffModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function submitStaffEdit(e) {
      e.preventDefault();
      const mobileNo = document.getElementById('editStaffMobile').value;
      const name = document.getElementById('editStaffName').value.trim();
      const email = document.getElementById('editStaffEmail').value.trim();
      const branch = document.getElementById('editStaffBranch').value;
      const designation = document.getElementById('editStaffDesig').value;

      const alert = document.getElementById('editStaffAlert');
      const spinner = document.getElementById('editStaffSpinner');

      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      fetch(`/api/admin/user/update-staff/${mobileNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ name, email, branch, designation })
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl bg-green-950/40 text-green-400 border border-green-900/60 block text-sm";
          alert.innerText = "Staff profile updated successfully!";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeEditStaffModal();
            loadUsers();
          }, 1000);
        } else {
          alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-sm";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-sm";
        alert.innerText = "Connection error. Request failed.";
        alert.classList.remove('hidden');
      });
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
          pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    // Google Drive Backup AJAX
    function runBackup() {
      const btn = document.getElementById('btnTriggerBackup');
      const spinner = document.getElementById('backupSpinner');
      const text = document.getElementById('btnBackupText');
      const alert = document.getElementById('backupAlert');

      btn.disabled = true;
      spinner.classList.remove('hidden');
      text.innerText = "Syncing SQL dump to Google Drive...";
      alert.classList.add('hidden');

      fetch('/api/system/backup', {
        method: 'POST',
        headers: getHeaders()
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        spinner.classList.add('hidden');
        text.innerText = "Initialize Google Drive Backup";
        
        if (data.status === 'SUCCESS') {
          alert.className = "p-4 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        } else {
          alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        btn.disabled = false;
        spinner.classList.add('hidden');
        text.innerText = "Initialize Google Drive Backup";
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        alert.innerText = "Google Drive backup failed. Verify API configuration keys.";
        alert.classList.remove('hidden');
      });
    }

    // Load Global Audit Trail
    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No system audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-[10px] text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-[10px] text-blue-400 font-mono">${log.target_id}</span></td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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

    // View Audit Log Modal for Single Profile
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
              tr.className = "border-b border-slate-800/40 text-[10px]";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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

    // Confirm Delete User
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

    // Register User Modals
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
        formData.append('admissionType', 'Regular');
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
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "User registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
      });
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-sm font-bold mt-2 text-blue-400";
      statusEl.innerText = "Uploading...";

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
          statusEl.innerText = "Updated!";

          // Update sidebar picture
          const sidebarImg = document.getElementById('sidebarStaffImg');
          if (sidebarImg) {
            sidebarImg.src = data.photo_url;
          }

          setTimeout(() => statusEl.classList.add('hidden'), 3000);
        } else {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = data.message || "Failed";
        }
      })
      .catch(() => {
        statusEl.className = "text-sm font-bold mt-2 text-rose-450";
        statusEl.innerText = "Error";
      });
    }

    function loadExecutiveMetrics() {
      fetch('/api/admin/executive-kpis')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('execStaffLeaveTotal').innerText = `${data.leave_breakdown.total_on_leave} Active`;
            if (document.getElementById('execLeaveCL')) document.getElementById('execLeaveCL').innerText = data.leave_breakdown.CL || 0;
            if (document.getElementById('execLeaveCCL')) document.getElementById('execLeaveCCL').innerText = data.leave_breakdown.CCL || 0;
            if (document.getElementById('execLeaveDL')) document.getElementById('execLeaveDL').innerText = data.leave_breakdown.DL || 0;
            if (document.getElementById('execLeaveML')) document.getElementById('execLeaveML').innerText = data.leave_breakdown.ML || 0;
            if (document.getElementById('execLeaveLOP')) document.getElementById('execLeaveLOP').innerText = data.leave_breakdown.LOP || 0;
            if (document.getElementById('execLeaveOTHERS')) document.getElementById('execLeaveOTHERS').innerText = data.leave_breakdown.OTHERS || 0;

            // Populate hover popup lists with staff names & department
            if (data.leave_breakdown.staff_by_type) {
              ['CL', 'CCL', 'DL', 'ML', 'LOP', 'OTHERS'].forEach(t => {
                const listEl = document.getElementById(`popupList${t}`);
                const countEl = document.getElementById(`popupCount${t}`);
                const staffArr = data.leave_breakdown.staff_by_type[t] || [];

                if (countEl) countEl.innerText = `${staffArr.length} Staff`;

                if (listEl) {
                  if (staffArr.length > 0) {
                    listEl.innerHTML = staffArr.map(s => `
                      <div class="flex items-center justify-between gap-1 border-b border-slate-800/60 pb-1">
                        <span class="font-bold text-slate-100 truncate text-[10px]">${s.name}</span>
                        <span class="text-[9px] text-sky-400 font-mono shrink-0">${s.dept}</span>
                      </div>
                    `).join('');
                  } else {
                    listEl.innerHTML = `<span class="text-slate-500 italic block text-[10px]">No staff on ${t} today</span>`;
                  }
                }
              });
            }

            if (data.today_events && data.today_events.length > 0) {
              allTodayEventsCache = data.today_events;
              todayEventCountsCache = data.event_counts || {};

              const badge = document.getElementById('execEventsCountBadge');
              if (badge) badge.innerText = `${data.today_events.length} Scheduled`;

              const modalTotalBadge = document.getElementById('modalEventsTotalBadge');
              if (modalTotalBadge) modalTotalBadge.innerText = `${data.today_events.length} Total`;
              
              const listContainer = document.getElementById('execTodayEventsList');
              if (listContainer) {
                listContainer.innerHTML = data.today_events.slice(0, 2).map(ev => `
                  <div class="flex items-center gap-1.5 truncate">
                    <span class="w-1.5 h-1.5 rounded-full ${ev.type === 'Holiday' ? 'bg-amber-400' : (ev.type === 'Exam' ? 'bg-rose-400' : 'bg-sky-400')} shrink-0"></span>
                    <span class="truncate font-medium text-slate-200 text-[11px]" title="${ev.title}">${ev.title}</span>
                  </div>
                `).join('');
              }

              // Update counter badges on modal tabs
              const counts = data.event_counts || {};
              const total = data.today_events.length;
              if (document.getElementById('evtCnt_ALL')) document.getElementById('evtCnt_ALL').innerText = total;

              ['Departments', 'College', 'NSS', 'NCC', 'IEDC', 'Placement Cell', 'Others'].forEach(cat => {
                const cntEl = document.getElementById(`evtCnt_${cat}`);
                if (cntEl) cntEl.innerText = counts[cat] || 0;
              });
            }
          }
        }).catch(() => {});

      fetch('/api/admin/executive-compliance')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('execFdpCount').innerText = `${data.total_fdps} Verified`;
            if (data.three_sem_matrix) {
              ['EL', 'ME', 'CE', 'EEE', 'CT', 'AU'].forEach(b => {
                if (data.three_sem_matrix[b]) {
                  const m = data.three_sem_matrix[b];
                  if (m.semesters) {
                    const keys = Object.keys(m.semesters);
                    if (keys[0] && document.getElementById(`sem_${b}_S1`)) document.getElementById(`sem_${b}_S1`).innerText = `${m.semesters[keys[0]]}%`;
                    if (keys[1] && document.getElementById(`sem_${b}_S3`)) document.getElementById(`sem_${b}_S3`).innerText = `${m.semesters[keys[1]]}%`;
                    if (keys[2] && document.getElementById(`sem_${b}_S5`)) document.getElementById(`sem_${b}_S5`).innerText = `${m.semesters[keys[2]]}%`;
                  }
                  if (document.getElementById(`sem_${b}_avg`)) document.getElementById(`sem_${b}_avg`).innerText = `${m.branch_avg}%`;
                }
              });
            }
          }
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadExecutiveMetrics();
      loadFlashNoticeStats();
      loadPrincipalEventStats();
    });

    // PRINCIPAL EVENT SCHEDULER DESK FUNCTIONS
    function openPrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.remove('hidden');
    }

    function closePrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.add('hidden');
      document.getElementById('principalScheduleEventForm').reset();
      togglePrincipalEventTargetFields();
    }

    function togglePrincipalEventTargetFields() {
      const scope = document.getElementById('peTargetAudience').value;
      const deptWrapper = document.getElementById('peDeptWrapper');
      const semWrapper = document.getElementById('peSemWrapper');
      const roleWrapper = document.getElementById('peRoleWrapper');
      const specialGroupWrapper = document.getElementById('peSpecialGroupWrapper');

      if (deptWrapper) deptWrapper.style.display = (scope === 'DEPT_SPECIFIC' || scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (roleWrapper) roleWrapper.style.display = (scope === 'STAFF_ONLY') ? 'block' : 'none';
      if (specialGroupWrapper) specialGroupWrapper.style.display = (scope === 'SPECIAL_GROUP') ? 'block' : 'none';
    }

    function submitPrincipalScheduleEvent(e) {
      e.preventDefault();
      const btn = document.getElementById('peSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-base">sync</span> Scheduling...';

      const formData = new FormData(document.getElementById('principalScheduleEventForm'));

      fetch('/api/principal/events/schedule', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">event_available</span> Schedule & Broadcast Event';
        if (data.status === 'SUCCESS') {
          alert(data.message);
          closePrincipalScheduleEventModal();
          loadPrincipalEventStats();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">event_available</span> Schedule & Broadcast Event';
        alert('Failed to schedule event. Please try again.');
      });
    }

    function loadPrincipalEventStats() {
      fetch('/api/principal/events')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.stats) {
            if (document.getElementById('principalEventStatCollege')) document.getElementById('principalEventStatCollege').innerText = data.stats.college_wide;
            if (document.getElementById('principalEventStatDept')) document.getElementById('principalEventStatDept').innerText = (data.stats.dept_specific + data.stats.staff_only);
            if (document.getElementById('principalEventStatSpecial')) document.getElementById('principalEventStatSpecial').innerText = data.stats.special_groups;
          }
        }).catch(() => {});
    }

    function openPrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('hidden');
      const body = document.getElementById('principalEventHistoryBody');
      body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-500">Loading events...</td></tr>';

      fetch('/api/principal/events')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.events.length > 0) {
            body.innerHTML = data.events.map(ev => `
              <tr class="hover:bg-slate-900/50">
                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-400">
                  ${ev.event_date ? ev.event_date.split('T')[0] : ''}<br>
                  <span class="text-[10px] text-emerald-400 font-bold">${ev.start_time || 'All Day'} ${ev.end_time ? '- ' + ev.end_time : ''}</span>
                </td>
                <td class="py-2.5 px-3">
                  <span class="font-bold text-slate-100 block">${ev.title}</span>
                  <span class="px-1.5 py-0.2 text-[9px] rounded font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">${ev.event_category}</span>
                  ${ev.venue ? `<span class="text-[10px] text-slate-400 block mt-0.5">📍 ${ev.venue}</span>` : ''}
                </td>
                <td class="py-2.5 px-3 text-[11px] text-slate-300">
                  <span class="font-mono text-emerald-400 font-bold">${ev.target_audience}</span>
                  ${ev.target_department !== 'ALL' ? `<span class="text-slate-400 block">Dept: ${ev.target_department}</span>` : ''}
                  ${ev.special_group_name ? `<span class="text-purple-300 block font-bold">Group: ${ev.special_group_name}</span>` : ''}
                </td>
                <td class="py-2.5 px-3 text-center">
                  ${ev.requires_rsvp ? '<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Yes</span>' : '<span class="text-slate-500 text-[10px]">No</span>'}
                </td>
                <td class="py-2.5 px-3">
                  ${ev.attachment_path ? `<a href="/storage/${ev.attachment_path}" target="_blank" class="text-emerald-400 underline font-mono text-[11px] flex items-center gap-1"><span class="material-symbols-rounded text-xs">attach_file</span> ${ev.attachment_type.toUpperCase()}</a>` : '<span class="text-slate-500 font-mono text-[11px]">None</span>'}
                </td>
                <td class="py-2.5 px-3 text-right">
                  <button onclick="revokePrincipalScheduledEvent(${ev.id})" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded font-bold text-[10px] transition cursor-pointer">Cancel</button>
                </td>
              </tr>
            `).join('');
          } else {
            body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-500">No scheduled events found.</td></tr>';
          }
        }).catch(() => {
          body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-rose-400">Failed to load events.</td></tr>';
        });
    }

    function closePrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.add('hidden');
    }

    function revokePrincipalScheduledEvent(id) {
      if (!confirm('Are you sure you want to cancel and delete this scheduled event?')) return;
      fetch(`/api/principal/events/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          openPrincipalScheduleEventHistoryModal();
          loadPrincipalEventStats();
        } else {
          alert('Error: ' + data.message);
        }
      });
    }

    // FLASH NOTICE BROADCAST DESK FUNCTIONS
    function openFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.remove('hidden');
    }

    function closeFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.add('hidden');
      document.getElementById('flashNoticeForm').reset();
      toggleNoticeTargetFields();
      toggleNoticeScheduleTime();
    }

    function toggleNoticeTargetFields() {
      const scope = document.getElementById('fnTargetAudience').value;
      const deptWrapper = document.getElementById('fnDeptWrapper');
      const semWrapper = document.getElementById('fnSemWrapper');

      if (scope === 'STAFF_DEPT' || scope === 'STUDENTS_DEPT_SEM') {
        deptWrapper.style.display = 'block';
      } else {
        deptWrapper.style.display = 'block';
      }

      if (scope === 'STUDENTS_DEPT_SEM') {
        semWrapper.style.display = 'block';
      } else {
        semWrapper.style.display = 'block';
      }
    }

    function toggleNoticeScheduleTime() {
      const dispatch = document.querySelector('input[name="dispatch_type"]:checked').value;
      const timeWrapper = document.getElementById('fnScheduleTimeWrapper');
      if (dispatch === 'scheduled') {
        timeWrapper.classList.remove('hidden');
      } else {
        timeWrapper.classList.add('hidden');
      }
    }

    function submitFlashNotice(e) {
      e.preventDefault();
      const btn = document.getElementById('fnSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-base">sync</span> Sending...';

      const formData = new FormData(document.getElementById('flashNoticeForm'));

      fetch('/api/admin/flash-notices/broadcast', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">send</span> Broadcast Notice';
        if (data.status === 'SUCCESS') {
          alert(data.message);
          closeFlashNoticeModal();
          loadFlashNoticeStats();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">send</span> Broadcast Notice';
        alert('Failed to send notice. Please try again.');
      });
    }

    function loadFlashNoticeStats() {
      fetch('/api/admin/flash-notices')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            if (document.getElementById('flashNoticeStatSent')) document.getElementById('flashNoticeStatSent').innerText = data.stats.total_sent;
            if (document.getElementById('flashNoticeStatSched')) document.getElementById('flashNoticeStatSched').innerText = data.stats.scheduled_count;
            if (document.getElementById('flashNoticeStatUrgent')) document.getElementById('flashNoticeStatUrgent').innerText = data.stats.urgent_count;
          }
        }).catch(() => {});
    }

    function openFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.remove('hidden');
      const body = document.getElementById('flashNoticeHistoryBody');
      body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-500">Loading history...</td></tr>';

      fetch('/api/admin/flash-notices')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.notices.length > 0) {
            body.innerHTML = data.notices.map(n => `
              <tr class="hover:bg-slate-900/50">
                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-400">${new Date(n.created_at).toLocaleString()}</td>
                <td class="py-2.5 px-3">
                  <span class="font-bold text-slate-100 block">${n.title}</span>
                  <span class="px-1.5 py-0.2 text-[9px] rounded font-bold ${n.priority === 'Urgent' ? 'bg-rose-500/20 text-rose-300' : 'bg-slate-800 text-slate-300'}">${n.priority}</span>
                </td>
                <td class="py-2.5 px-3 text-[11px] text-slate-300">
                  <span class="font-mono text-sky-400 font-bold">${n.target_audience}</span>
                  <span class="text-slate-400 block">${n.target_department} | Sem: ${n.target_semester}</span>
                </td>
                <td class="py-2.5 px-3">
                  ${n.attachment_path ? `<a href="/storage/${n.attachment_path}" target="_blank" class="text-sky-400 underline font-mono text-[11px] flex items-center gap-1"><span class="material-symbols-rounded text-xs">attach_file</span> View ${n.attachment_type.toUpperCase()}</a>` : '<span class="text-slate-500 font-mono text-[11px]">None</span>'}
                </td>
                <td class="py-2.5 px-3 text-right">
                  <button onclick="revokeFlashNotice(${n.id})" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded font-bold text-[10px] transition">Revoke</button>
                </td>
              </tr>
            `).join('');
          } else {
            body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-500">No broadcast history recorded yet.</td></tr>';
          }
        }).catch(() => {
          body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-rose-400">Failed to load history.</td></tr>';
        });
    }

    function closeFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.add('hidden');
    }

    function revokeFlashNotice(id) {
      if (!confirm('Are you sure you want to revoke this notice? It will be deleted permanently.')) return;
      fetch(`/api/admin/flash-notices/revoke/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          openFlashNoticeHistoryModal();
          loadFlashNoticeStats();
        } else {
          alert('Error: ' + data.message);
        }
      });
    }

    // Prevent back-button viewing after logout (session out)
    window.addEventListener('pageshow', function (event) {
      if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
        window.location.reload(true);
      }
    });
  </script>

  <!-- FLASH NOTICE BROADCAST MODAL -->
  <div id="flashNoticeModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-5 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400">
            <span class="material-symbols-rounded text-xl">campaign</span>
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Broadcast Executive Flash Notice</h3>
            <p class="text-xs text-slate-400">Dispatch notice to Staff (All/Dept) &amp; Students (All/Dept/Sem)</p>
          </div>
        </div>
        <button onclick="closeFlashNoticeModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <form id="flashNoticeForm" onsubmit="submitFlashNotice(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-slate-300 font-bold mb-1">Notice Title / Subject <span class="text-rose-400">*</span></label>
            <input type="text" id="fnTitle" name="title" required placeholder="e.g., Special Working Day &amp; Exam Valuation Notice" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Priority / Type <span class="text-rose-400">*</span></label>
            <select id="fnPriority" name="priority" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium">
              <option value="Normal">Normal Announcement</option>
              <option value="Urgent">Urgent Flash Warning</option>
              <option value="Circular">Official Circular</option>
            </select>
          </div>
        </div>

        <div class="p-3.5 bg-slate-950/60 border border-slate-800 rounded-xl space-y-3">
          <span class="block text-slate-200 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sky-400 text-sm">groups</span> Target Audience &amp; Scope
          </span>
          
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-slate-400 mb-1 font-semibold">Recipient Group</label>
              <select id="fnTargetAudience" name="target_audience" onchange="toggleNoticeTargetFields()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL_CAMPUS">🌐 ALL Campus (Staff &amp; Students)</option>
                <option value="STAFF_ALL">👨‍🏫 Staff - All Departments</option>
                <option value="STAFF_DEPT">🏫 Staff - Specific Department</option>
                <option value="STUDENTS_ALL">🎓 Students - All Batches</option>
                <option value="STUDENTS_DEPT_SEM">📚 Students - Dept &amp; Semester</option>
              </select>
            </div>

            <div id="fnDeptWrapper">
              <label class="block text-slate-400 mb-1 font-semibold">Department Branch</label>
              <select id="fnTargetDepartment" name="target_department" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>

            <div id="fnSemWrapper">
              <label class="block text-slate-400 mb-1 font-semibold">Semester Level</label>
              <select id="fnTargetSemester" name="target_semester" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="1">Semester 1 (S1)</option>
                <option value="2">Semester 2 (S2)</option>
                <option value="3">Semester 3 (S3)</option>
                <option value="4">Semester 4 (S4)</option>
                <option value="5">Semester 5 (S5)</option>
                <option value="6">Semester 6 (S6)</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1">Notice Description / Content <span class="text-rose-400">*</span></label>
          <textarea id="fnContent" name="content" required rows="4" placeholder="Enter detailed notice message, instructions, or official directive text..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
              <span class="material-symbols-rounded text-amber-400 text-sm">attach_file</span> Attach Image or PDF <span class="text-slate-500 font-normal">(Optional)</span>
            </label>
            <input type="file" id="fnAttachment" name="attachment" accept="image/jpeg,image/png,image/webp,application/pdf" class="w-full text-slate-300 bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30">
            <p class="text-[10px] text-slate-400 mt-1">Supports JPG, PNG, WEBP images or PDF files (Max 10MB).</p>
          </div>

          <div>
            <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
              <span class="material-symbols-rounded text-emerald-400 text-sm">schedule</span> Dispatch Timing
            </label>
            <div class="flex items-center gap-2 mb-2">
              <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
                <input type="radio" name="dispatch_type" value="immediate" checked onchange="toggleNoticeScheduleTime()" class="accent-sky-500">
                <span class="font-bold text-sky-400">⚡ Immediate Now</span>
              </label>
              <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer ml-2">
                <input type="radio" name="dispatch_type" value="scheduled" onchange="toggleNoticeScheduleTime()" class="accent-amber-500">
                <span class="font-bold text-amber-400">⏰ Scheduled</span>
              </label>
            </div>
            <div id="fnScheduleTimeWrapper" class="hidden">
              <input type="datetime-local" id="fnScheduledAt" name="scheduled_at" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-amber-500 font-mono">
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button type="button" onclick="closeFlashNoticeModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition">Cancel</button>
          <button type="submit" id="fnSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-500 hover:to-blue-500 text-white font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg">
            <span class="material-symbols-rounded text-base">send</span> Broadcast Notice
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- FLASH NOTICE HISTORY LOG MODAL -->
  <div id="flashNoticeHistoryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-4 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h3 class="font-extrabold text-slate-100 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-sky-400">history</span> Executive Flash Notice Broadcast History
        </h3>
        <button onclick="closeFlashNoticeHistoryModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
              <th class="py-2.5 px-3">Date &amp; Time</th>
              <th class="py-2.5 px-3">Title &amp; Type</th>
              <th class="py-2.5 px-3">Target Scope</th>
              <th class="py-2.5 px-3">Attachment</th>
              <th class="py-2.5 px-3 text-right">Action</th>
            </tr>
          </thead>
          <tbody id="flashNoticeHistoryBody" class="divide-y divide-slate-800/60 font-medium text-slate-300">
            <tr>
              <td colspan="5" class="py-6 text-center text-slate-500">Loading broadcast history...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- PRINCIPAL SCHEDULE EVENT MODAL -->
  <div id="principalScheduleEventModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-5 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
            <span class="material-symbols-rounded text-xl">event_available</span>
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Schedule College Institutional Event</h3>
            <p class="text-xs text-slate-400">Target College, Department, Staff, Students, or Special Groups</p>
          </div>
        </div>
        <button onclick="closePrincipalScheduleEventModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <form id="principalScheduleEventForm" onsubmit="submitPrincipalScheduleEvent(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-slate-300 font-bold mb-1">Event Title <span class="text-rose-400">*</span></label>
            <input type="text" id="peTitle" name="title" required placeholder="e.g., Annual Sports Meet 2026 / Placement Drive" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Event Category <span class="text-rose-400">*</span></label>
            <select id="peCategory" name="event_category" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
              <option value="Academic">Academic Schedule</option>
              <option value="Exam">Examination &amp; Audit</option>
              <option value="Meeting">Executive Meeting</option>
              <option value="Cultural">Cultural Event</option>
              <option value="Sports">Sports Meet</option>
              <option value="Workshop">Workshop &amp; FDP</option>
              <option value="Holiday">Official Holiday</option>
              <option value="Other">Other Event</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1">Event Date <span class="text-rose-400">*</span></label>
            <input type="date" id="peDate" name="event_date" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Start Time</label>
            <input type="time" id="peStartTime" name="start_time" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">End Time</label>
            <input type="time" id="peEndTime" name="end_time" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
        </div>

        <div class="p-3.5 bg-slate-950/60 border border-slate-800 rounded-xl space-y-3">
          <span class="block text-slate-200 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-emerald-400 text-sm">groups</span> Target Scope &amp; Audience
          </span>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-400 mb-1 font-semibold">Target Audience</label>
              <select id="peTargetAudience" name="target_audience" onchange="togglePrincipalEventTargetFields()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL_CAMPUS">🌐 College Wide (All Staff &amp; Students)</option>
                <option value="DEPT_SPECIFIC">🏫 Department Specific</option>
                <option value="STAFF_ONLY">👨‍🏫 Staff Only</option>
                <option value="STUDENTS_ONLY">🎓 Students Only</option>
                <option value="SPECIAL_GROUP">⭐ Special Group</option>
              </select>
            </div>

            <div id="peDeptWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Target Department</label>
              <select id="peTargetDepartment" name="target_department" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>

            <div id="peSemWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Semester Level</label>
              <select id="peTargetSemester" name="target_semester" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="S1">Semester 1 (S1)</option>
                <option value="S2">Semester 2 (S2)</option>
                <option value="S3">Semester 3 (S3)</option>
                <option value="S4">Semester 4 (S4)</option>
                <option value="S5">Semester 5 (S5)</option>
                <option value="S6">Semester 6 (S6)</option>
              </select>
            </div>

            <div id="peRoleWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Staff Designation</label>
              <select id="peTargetRole" name="target_role" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL">All Staff</option>
                <option value="HOD">HODs Only</option>
                <option value="Lecturer">Lecturers</option>
                <option value="Demonstrator">Demonstrators</option>
                <option value="Trade_Instructor">Trade Instructors</option>
              </select>
            </div>

            <div id="peSpecialGroupWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Special Group Name</label>
              <select id="peSpecialGroupName" name="special_group_name" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="Placement Cell">Placement &amp; Training Cell</option>
                <option value="NSS / NCC">NSS / NCC Units</option>
                <option value="Sports Council">Sports &amp; Athletics Council</option>
                <option value="IQAC & Audit">IQAC &amp; Quality Audit Team</option>
                <option value="Anti-Ragging Cell">Anti-Ragging &amp; Disciplinary Cell</option>
                <option value="Student Council">Student Union Council</option>
                <option value="Alumni Association">Alumni Association</option>
              </select>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1">Venue / Location</label>
            <input type="text" id="peVenue" name="venue" placeholder="e.g., Main Auditorium / Seminar Hall" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div class="flex items-center gap-4 pt-4">
            <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
              <input type="checkbox" id="peIsFullDay" name="is_full_day" value="1" class="accent-emerald-500 w-4 h-4 rounded">
              <span class="font-bold text-slate-300">Full Day Event</span>
            </label>
            <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
              <input type="checkbox" id="peRequiresRsvp" name="requires_rsvp" value="1" class="accent-amber-500 w-4 h-4 rounded">
              <span class="font-bold text-amber-400">RSVP / Attendance Required</span>
            </label>
          </div>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1">Event Description &amp; Details</label>
          <textarea id="peDescription" name="description" rows="3" placeholder="Enter details about event objectives, schedule, guest speakers, instructions..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium leading-relaxed"></textarea>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
            <span class="material-symbols-rounded text-emerald-400 text-sm">attach_file</span> Attach Flyer / Document <span class="text-slate-500 font-normal">(Optional PDF or Image)</span>
          </label>
          <input type="file" id="peAttachment" name="attachment" accept="image/jpeg,image/png,image/webp,application/pdf" class="w-full text-slate-300 bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button type="button" onclick="closePrincipalScheduleEventModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition">Cancel</button>
          <button type="submit" id="peSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg">
            <span class="material-symbols-rounded text-base">event_available</span> Schedule &amp; Broadcast Event
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PRINCIPAL EVENT HISTORY LOG MODAL -->
  <div id="principalScheduleEventHistoryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-4 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h3 class="font-extrabold text-slate-100 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-400">event_available</span> Scheduled Events Audit Log
        </h3>
        <button onclick="closePrincipalScheduleEventHistoryModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
              <th class="py-2.5 px-3">Date &amp; Time</th>
              <th class="py-2.5 px-3">Title &amp; Category</th>
              <th class="py-2.5 px-3">Target Scope</th>
              <th class="py-2.5 px-3 text-center">RSVP</th>
              <th class="py-2.5 px-3">Attachment</th>
              <th class="py-2.5 px-3 text-right">Action</th>
            </tr>
          </thead>
          <tbody id="principalEventHistoryBody" class="divide-y divide-slate-800/60 font-medium text-slate-300">
            <tr>
              <td colspan="6" class="py-6 text-center text-slate-500">Loading events...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- EXECUTIVE PROFILE SETTINGS MODAL -->
  <div id="executiveProfileModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden items-center justify-center p-4 transition-all duration-300">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-5 relative">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-lg">manage_accounts</span>
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Executive Profile Settings</h3>
            <p class="text-xs text-slate-400">Update account credentials, login ID, and profile picture</p>
          </div>
        </div>
        <button onclick="closeExecutiveProfileModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <div id="execProfileAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <form id="execProfileForm" onsubmit="saveExecutiveProfile(event)" class="space-y-4">
        <div class="flex items-center gap-4 p-3 bg-slate-950/60 border border-slate-800/80 rounded-2xl">
          <div class="relative group shrink-0">
            <img id="execModalAvatarPrev" src="/storage/avatars/default.png" onerror="this.src='/storage/avatars/default.png'" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-500/40 shadow-md">
            <label for="execModalPhotoInput" class="absolute inset-0 bg-black/60 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold cursor-pointer transition">
              <span class="material-symbols-rounded text-sm">photo_camera</span>
            </label>
          </div>
          <div class="flex-grow space-y-1">
            <span class="text-xs font-bold text-slate-200 block">Profile Picture</span>
            <p class="text-[11px] text-slate-400">PNG, JPG or GIF (Max 2MB)</p>
            <input type="file" id="execModalPhotoInput" accept="image/*" class="hidden" onchange="previewExecAvatar(this)">
            <button type="button" onclick="document.getElementById('execModalPhotoInput').click()" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-blue-300 rounded-lg text-[11px] font-bold border border-slate-700 transition cursor-pointer">
              Choose New Photo
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Full Name</label>
            <input type="text" id="execModalName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Login ID / Mobile No.</label>
            <input type="text" id="execModalMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white font-mono outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</label>
            <input type="email" id="execModalEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">New Password (Leave blank to keep unchanged)</label>
            <input type="password" id="execModalPassword" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="Minimum 4 characters">
          </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-800">
          <button type="button" onclick="closeExecutiveProfileModal()" class="px-4 py-2 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl font-bold text-xs transition cursor-pointer">
            Cancel
          </button>
          <button type="submit" id="execProfileSubmitBtn" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl text-xs transition shadow-lg shadow-blue-500/20 cursor-pointer flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sm">save</span> Save Profile Settings
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openExecutiveProfileModal() {
      const modal = document.getElementById('executiveProfileModal');
      const alertBox = document.getElementById('execProfileAlert');
      if (alertBox) alertBox.classList.add('hidden');

      fetch('/api/executive/profile/details')
        .then(r => r.json())
        .then(res => {
          if (res.status === 'SUCCESS' && res.data) {
            document.getElementById('execModalName').value = res.data.name || '';
            document.getElementById('execModalMobile').value = res.data.mobile_no || '';
            document.getElementById('execModalEmail').value = res.data.email || '';
            document.getElementById('execModalPassword').value = '';
            if (res.data.photo_url) {
              document.getElementById('execModalAvatarPrev').src = res.data.photo_url;
            }
          }
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        })
        .catch(err => {
          console.error(err);
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        });
    }

    function closeExecutiveProfileModal() {
      const modal = document.getElementById('executiveProfileModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function previewExecAvatar(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('execModalAvatarPrev').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function saveExecutiveProfile(e) {
      e.preventDefault();
      const alertBox = document.getElementById('execProfileAlert');
      const btn = document.getElementById('execProfileSubmitBtn');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      const formData = new FormData();
      formData.append('name', document.getElementById('execModalName').value.trim());
      formData.append('mobile_no', document.getElementById('execModalMobile').value.trim());
      formData.append('email', document.getElementById('execModalEmail').value.trim());
      
      const pwd = document.getElementById('execModalPassword').value.trim();
      if (pwd) {
        formData.append('new_password', pwd);
      }

      const fileInput = document.getElementById('execModalPhotoInput');
      if (fileInput && fileInput.files[0]) {
        formData.append('photo', fileInput.files[0]);
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      fetch('/api/executive/profile/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken || ''
        },
        body: formData
      })
      .then(r => r.json())
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save Profile Settings';
        
        alertBox.classList.remove('hidden');
        if (res.status === 'SUCCESS') {
          alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-emerald-500/10 text-emerald-300 border-emerald-500/30 mb-3';
          alertBox.innerText = res.message || 'Profile settings updated successfully!';
          setTimeout(() => {
            location.reload();
          }, 1200);
        } else {
          alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-500/10 text-rose-300 border-rose-500/30 mb-3';
          alertBox.innerText = res.message || 'Failed to update profile settings.';
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save Profile Settings';
        alertBox.classList.remove('hidden');
        alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-500/10 text-rose-300 border-rose-500/30 mb-3';
        alertBox.innerText = 'Network error: ' + err.message;
      });
    }

    /* Theme Toggle Logic */
    function initTheme() {
      const savedTheme = localStorage.getItem('carmel_theme') || 'dark';
      if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        updateThemeToggleUI('light');
      } else {
        document.body.classList.remove('light-theme');
        updateThemeToggleUI('dark');
      }
    }

    function toggleTheme() {
      const isLight = document.body.classList.toggle('light-theme');
      const theme = isLight ? 'light' : 'dark';
      localStorage.setItem('carmel_theme', theme);
      updateThemeToggleUI(theme);
    }

    function updateThemeToggleUI(theme) {
      const icon = document.getElementById('themeToggleIcon');
      const text = document.getElementById('themeToggleText');
      const btn = document.getElementById('themeToggleBtn');
      if (!icon || !btn) return;
      if (theme === 'light') {
        icon.innerText = 'dark_mode';
        icon.className = 'material-symbols-rounded text-base text-indigo-600';
        if (text) text.innerText = 'Dark Mode';
        btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-premium cursor-pointer shadow-sm';
      } else {
        icon.innerText = 'light_mode';
        icon.className = 'material-symbols-rounded text-base text-amber-400';
        if (text) text.innerText = 'Light Mode';
        btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm';
      }
    }

    // =========================================================================
    // TODAY'S EVENTS MODAL LOGIC
    // =========================================================================
    let allTodayEventsCache = [];
    let todayEventCountsCache = {};
    let activeEventCategoryFilter = 'ALL';

    function openTodayEventsModal() {
      activeEventCategoryFilter = 'ALL';
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();

      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeTodayEventsModal() {
      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function filterEventsByCategory(cat) {
      activeEventCategoryFilter = cat;
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();
    }

    function updateCategoryFilterTabsUI() {
      const tabs = document.querySelectorAll('.evt-cat-tab');
      tabs.forEach(tab => {
        const tabCat = tab.id.replace('evtCatTab_', '');
        if (tabCat === activeEventCategoryFilter) {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-500/20 text-sky-300 border border-sky-500/40 shadow-sm';
        } else {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-slate-600 cursor-pointer';
        }
      });
    }

    function renderTodayEventsModalList() {
      const container = document.getElementById('modalEventsListContainer');
      if (!container) return;

      const events = allTodayEventsCache || [];
      const catFilter = activeEventCategoryFilter || 'ALL';

      const filtered = catFilter === 'ALL' 
        ? events 
        : events.filter(ev => (ev.organizer || 'College') === catFilter);

      const showingEl = document.getElementById('modalShowingCount');
      if (showingEl) showingEl.innerText = filtered.length;

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="p-8 text-center text-slate-500 bg-slate-950/40 rounded-xl border border-slate-800/60">
            <span class="material-symbols-rounded text-3xl block text-slate-600 mb-2">event_busy</span>
            <span class="font-bold text-xs text-slate-400">No scheduled events found under ${catFilter === 'ALL' ? 'today' : '\'' + catFilter + '\''} category.</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(ev => {
        const org = ev.organizer || 'College';
        let badgeClass = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
        let iconName   = 'school';

        if (org === 'Departments') {
          badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
          iconName   = 'domain';
        } else if (org === 'NSS') {
          badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
          iconName   = 'volunteer_activism';
        } else if (org === 'NCC') {
          badgeClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
          iconName   = 'military_tech';
        } else if (org === 'IEDC') {
          badgeClass = 'bg-purple-500/10 text-purple-400 border-purple-500/20';
          iconName   = 'lightbulb';
        } else if (org === 'Placement Cell') {
          badgeClass = 'bg-teal-500/10 text-teal-400 border-teal-500/20';
          iconName   = 'work';
        } else if (org === 'Others') {
          badgeClass = 'bg-slate-800 text-slate-300 border-slate-700';
          iconName   = 'event';
        }

        return `
          <div class="bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 p-4 rounded-xl transition flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div class="space-y-1.5 flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border uppercase tracking-wider ${badgeClass} flex items-center gap-1 shrink-0">
                  <span class="material-symbols-rounded text-xs">${iconName}</span>
                  ${org}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700 shrink-0">
                  ${ev.type || 'Event'}
                </span>
                ${ev.branch && ev.branch !== 'ALL' ? `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-800 text-sky-400 border border-slate-700 shrink-0">${ev.branch}</span>` : ''}
              </div>
              <h4 class="font-bold text-slate-100 text-sm sm:text-base leading-snug pt-0.5 break-words">${ev.title}</h4>
            </div>
            <div class="shrink-0 space-y-1 md:text-right text-xs text-slate-400 border-t md:border-t-0 border-slate-800/60 pt-2.5 md:pt-0">
              <div class="flex items-center md:justify-end gap-1.5 font-mono text-slate-200 font-semibold text-xs">
                <span class="material-symbols-rounded text-sm text-sky-400">schedule</span>
                ${ev.time || '09:30 AM - 04:30 PM'}
              </div>
              <div class="flex items-center md:justify-end gap-1.5 text-slate-400 text-xs">
                <span class="material-symbols-rounded text-sm text-amber-400">location_on</span>
                ${ev.venue || 'Campus Main Grounds'}
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    document.addEventListener('DOMContentLoaded', initTheme);
  </script>

  <!-- TODAY'S EVENTS LIST MODAL BY CATEGORIES -->
  <div id="todayEventsModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-6xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      <!-- Header -->
      <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between bg-slate-950/60">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20 flex items-center justify-center">
            <span class="material-symbols-rounded text-xl">event_available</span>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-100 flex items-center gap-2">
              Today's Campus &amp; Academic Events
              <span id="modalEventsTotalBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/20 text-sky-300 border border-sky-500/30">0 Total</span>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Categorized by Departments, College, NSS, NCC, IEDC, Placement Cell &amp; Others</p>
          </div>
        </div>
        <button onclick="closeTodayEventsModal()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- Category Filter Tabs Bar -->
      <div class="p-3.5 bg-slate-950/40 border-b border-slate-800 overflow-x-auto scrollbar-hidden flex items-center gap-2">
        <button onclick="filterEventsByCategory('ALL')" id="evtCatTab_ALL" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-500/20 text-sky-300 border border-sky-500/40">
          <span>All Events</span>
          <span id="evtCnt_ALL" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-sky-500/30 text-sky-200">0</span>
        </button>
        <button onclick="filterEventsByCategory('Departments')" id="evtCatTab_Departments" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-amber-500/40">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
          <span>Departments</span>
          <span id="evtCnt_Departments" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('College')" id="evtCatTab_College" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-sky-500/40">
          <span class="w-2 h-2 rounded-full bg-sky-400"></span>
          <span>College / Academic</span>
          <span id="evtCnt_College" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('NSS')" id="evtCatTab_NSS" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-emerald-500/40">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span>NSS</span>
          <span id="evtCnt_NSS" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('NCC')" id="evtCatTab_NCC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-rose-500/40">
          <span class="w-2 h-2 rounded-full bg-rose-400"></span>
          <span>NCC</span>
          <span id="evtCnt_NCC" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('IEDC')" id="evtCatTab_IEDC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-purple-500/40">
          <span class="w-2 h-2 rounded-full bg-purple-400"></span>
          <span>IEDC</span>
          <span id="evtCnt_IEDC" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('Placement Cell')" id="evtCatTab_Placement Cell" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-teal-500/40">
          <span class="w-2 h-2 rounded-full bg-teal-400"></span>
          <span>Placement Cell</span>
          <span id="evtCnt_Placement Cell" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('Others')" id="evtCatTab_Others" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-slate-500">
          <span class="w-2 h-2 rounded-full bg-slate-400"></span>
          <span>Others</span>
          <span id="evtCnt_Others" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
      </div>

      <!-- Modal Events List Container -->
      <div class="p-5 overflow-y-auto flex-grow space-y-3" id="modalEventsListContainer">
        <!-- Loaded dynamically -->
      </div>

      <!-- Footer Summary Badges -->
      <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between flex-wrap gap-2 text-xs">
        <div class="text-slate-400 text-[11px] font-medium">
          Displaying <span id="modalShowingCount" class="text-slate-200 font-bold">0</span> scheduled event(s)
        </div>
        <button onclick="closeTodayEventsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs transition border border-slate-700 cursor-pointer">
          Close Window
        </button>
      </div>
    </div>
  </div>

  @include('partials.admin_support_desk_window')
  @include('partials.support_desk_overlay')

  <script>
    window.addEventListener('pageshow', function (event) {
      if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
        fetch('/api/system/session-check', { method: 'GET', cache: 'no-store' })
          .then(r => r.json())
          .then(data => {
            if (!data || data.status !== 'ACTIVE') {
              window.location.replace('/');
            }
          })
          .catch(() => {
            window.location.replace('/');
          });
      }
    });
  </script>
</body>
</html>
