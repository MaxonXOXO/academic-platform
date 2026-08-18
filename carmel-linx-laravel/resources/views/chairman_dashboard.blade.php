<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Chairman Control Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html {
      font-size: 90%;
    }
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
    input, select, textarea {
      font-size: 0.875rem !important;
    }
    .text-lg {
      font-size: 1.05rem !important;
    }
    .text-base {
      font-size: 0.875rem !important;
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
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-amber-500/30">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#fbbf24,#f59e0b);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-amber-400 font-bold uppercase tracking-widest">Chairman Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <div class="relative group shrink-0">
        <div id="staffAvatarWrapper" class="w-11 h-11 rounded-full overflow-hidden border border-amber-500/40 bg-slate-800 flex items-center justify-center shadow-inner relative">
          <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150' }}" class="w-full h-full object-cover">
        </div>
        <label for="staffPhotoUploadInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-sm font-bold text-center p-0.5">
          <span class="material-symbols-rounded text-sm">photo_camera</span>
        </label>
        <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
      </div>
      <div class="overflow-hidden">
        <span class="font-bold text-sm block truncate text-slate-100">{{ session('userName', 'Chairman') }}</span>
        <span class="text-xs font-bold text-amber-400 block uppercase tracking-wider">Executive Management</span>
        <div id="staffPhotoUploadStatus" class="text-sm font-bold text-green-400 hidden"></div>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500">
        <span class="material-symbols-rounded text-lg">dashboard</span> Executive Overview
      </button>
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">group</span> Personnel Directory
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">receipt_long</span> Audit Trail Log
      </button>
      <button onclick="openExecutiveProfileModal()" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">manage_accounts</span> Profile Settings
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of Chairman Control Desk?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <div class="flex items-center gap-3 md:gap-4">
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Executive Overview</h1>
        
        <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-300 border border-amber-500/20">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
          <span>Chairman Desk Connected</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <!-- Theme Toggle Button -->
        <button id="themeToggleBtn" onclick="toggleTheme()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm" title="Toggle Light/Dark Mode">
          <span id="themeToggleIcon" class="material-symbols-rounded text-base text-amber-400">light_mode</span>
          <span id="themeToggleText" class="hidden sm:inline">Light Mode</span>
        </button>

        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-amber-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-xs font-bold transition-premium border"></div>

      <!-- PANEL 1: DASHBOARD OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Row (Top Executive Circular KPI Cards) -->
        <div class="bg-slate-950/60 border border-slate-800/80 p-4 rounded-2xl shadow-xl backdrop-blur-md">
          <div class="flex items-center justify-between border-b border-slate-800/60 pb-2 mb-3">
            <span class="text-[11px] font-extrabold text-slate-300 uppercase tracking-widest flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm text-amber-400">donut_large</span> Executive Overview Metrics
            </span>
            <span class="text-[10px] text-slate-500 font-mono font-bold">Real-time Platform Sync</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 items-center justify-items-center">
            <!-- Total Staff -->
            <div class="flex flex-col items-center group cursor-pointer w-full" onclick="openStaffCampusModal()">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-amber-500/40 bg-slate-900/90 hover:border-amber-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-amber-500/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-amber-500/15 to-transparent opacity-60"></div>
                <span class="material-symbols-rounded text-amber-400 text-base mb-0.5 group-hover:scale-110 transition-transform">badge</span>
                <span id="statTotalStaff" class="font-black text-white text-base sm:text-lg leading-none">0</span>
              </div>
              <span class="text-[10px] text-slate-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Total Staff</span>
            </div>

            <!-- Total Students -->
            <div class="flex flex-col items-center group cursor-pointer w-full" onclick="openStudentCampusModal()">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-sky-500/40 bg-slate-900/90 hover:border-sky-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-sky-500/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-sky-500/15 to-transparent opacity-60"></div>
                <span class="material-symbols-rounded text-sky-400 text-base mb-0.5 group-hover:scale-110 transition-transform">school</span>
                <span id="statTotalStudents" class="font-black text-white text-base sm:text-lg leading-none">0</span>
              </div>
              <span class="text-[10px] text-slate-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Total Students</span>
            </div>

            <!-- Pending Approvals -->
            <div class="flex flex-col items-center group cursor-pointer w-full" onclick="openPendingApprovalsModal()">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-blue-500/40 bg-slate-900/90 hover:border-blue-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-blue-500/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-blue-500/15 to-transparent opacity-60"></div>
                <span class="material-symbols-rounded text-blue-400 text-base mb-0.5 group-hover:scale-110 transition-transform">pending_actions</span>
                <span id="statPendingApprovals" class="font-black text-blue-300 text-base sm:text-lg leading-none">0</span>
              </div>
              <span class="text-[10px] text-slate-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Pending Approvals</span>
            </div>

            <!-- Classrooms -->
            <div class="flex flex-col items-center group cursor-pointer w-full">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-emerald-500/40 bg-slate-900/90 hover:border-emerald-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-emerald-500/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/15 to-transparent opacity-60"></div>
                <span class="material-symbols-rounded text-emerald-400 text-base mb-0.5 group-hover:scale-110 transition-transform">meeting_room</span>
                <span id="statTotalClassrooms" class="font-black text-white text-base sm:text-lg leading-none">0</span>
              </div>
              <span class="text-[10px] text-slate-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Classrooms</span>
            </div>

            <!-- Events Today -->
            <div class="flex flex-col items-center group cursor-pointer w-full" onclick="openTodayEventsModal()">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-indigo-500/40 bg-slate-900/90 hover:border-indigo-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-indigo-500/10 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/15 to-transparent opacity-60"></div>
                <span class="material-symbols-rounded text-indigo-400 text-base mb-0.5 group-hover:scale-110 transition-transform">event</span>
                <span id="statEventsToday" class="font-black text-indigo-300 text-base sm:text-lg leading-none">0</span>
              </div>
              <span class="text-[10px] text-slate-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Events Today</span>
            </div>

            <!-- Day Order (Right End with Light Coloured Background & Highlight Text) -->
            <div class="flex flex-col items-center group cursor-pointer w-full" onclick="openDepartmentTimetables()">
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-amber-300 bg-gradient-to-br from-amber-100 via-rose-100 to-amber-200 hover:border-amber-400 hover:scale-105 transition-all duration-300 flex flex-col items-center justify-center shadow-lg shadow-amber-500/30 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-white/40 to-transparent opacity-80"></div>
                <span class="text-[8px] font-black uppercase tracking-wider text-rose-900 bg-rose-200/90 px-1.5 py-0.5 rounded-full mb-0.5 shadow-xs z-10">TODAY</span>
                <span id="statDayOrder" class="font-black text-slate-900 text-xs sm:text-sm leading-none truncate max-w-[90%] text-center z-10">Day 1</span>
              </div>
              <span class="text-[10px] text-amber-300 uppercase font-extrabold tracking-wider mt-1.5 text-center leading-tight">Day Order</span>
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
                  <span class="p-1 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">domain</span>
                  </span> HOD Console Supervision
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm">Direct Supervision</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Directly inspect and supervise the HOD Control Desk for any department branch.
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
              <button onclick="openFlashNoticeModal()" class="flex-1 px-3 py-1.5 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 rounded-lg font-bold text-slate-950 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 shadow-md">
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
              <span class="p-1.5 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-sm">analytics</span>
              </span>
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                  <h3 class="font-black text-slate-200 text-sm">Previous Semester Branch Academic Pass Matrix</h3>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-300 border border-amber-500/20">3 Semesters per Dept</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Department semester pass percentages (S1/S3/S5 or S2/S4/S6) uploaded by HODs.</p>
              </div>
            </div>
            <div class="flex items-center gap-2.5 self-end sm:self-auto shrink-0">
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

      <!-- PANEL 2: PERSONNEL DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-base font-bold text-slate-200">Personnel Accounts Registry</h3>
            <p class="text-xs text-slate-400 mt-0.5">View personnel profiles, designations, and account status across all departments.</p>
          </div>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search input -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Branch select -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Code</label>
            <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
              <option value="">All Branches</option>
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
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
              <option value="Trade_Instructor">Trade Instructors</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
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
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-2.5 md:p-3">Profile</th>
                  <th class="p-2.5 md:p-3">Mobile / Reg No</th>
                  <th class="p-2.5 md:p-3">Branch</th>
                  <th class="p-2.5 md:p-3">Role Designation</th>
                  <th class="p-2.5 md:p-3">Account Status</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: AUDIT TRAIL LOG -->
      <div id="panelAudit" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">System Audit Trail Log</h3>
            <p class="text-xs text-slate-400 mt-1">Lifecycle events, password resets, status changes, and registration records.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse">
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
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">edit</span> Edit Staff Details
        </h3>
        <button onclick="closeEditStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Administration">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
            <option value="Principal">Principal</option>
            <option value="Chairman">Chairman</option>
            <option value="HOD">Head of Department (HOD)</option>
            <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
            <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
            <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Demonstrator">Demonstrator</option>
            <option value="Trade_Instructor">Trade Instructor</option>
            <option value="Super_Admin">Super Admin</option>
            <option value="Admin">Admin</option>
          </select>
        </div>

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer text-sm flex items-center justify-center gap-1.5">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-950 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-amber-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-amber-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
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
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
          </div>
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <select id="directRegStudentBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" value="2026">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
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

        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="Mobile / Login ID">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
                <option value="HOD">Head of Department (HOD)</option>
                <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
                <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Principal">Principal</option>
                <option value="Chairman">Chairman</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="directRegStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-955 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. chairman">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-xs">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-950 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "dashboard";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'audit'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500 text-xs";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-white hover:bg-slate-800 cursor-pointer text-xs";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Executive Overview',
        'directory': 'Personnel Directory',
        'audit': 'Audit Trail Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Chairman Desk';

      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
    }

    function showLoading(show) {
      const el = document.getElementById('loadingIndicator');
      if (el) {
        if (show) el.classList.remove('hidden'); else el.classList.add('hidden');
      }
    }

    var currentStatsData = null;

    function loadStats() {
      showLoading(true);
      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          if (data.status === 'SUCCESS') {
            currentStatsData = data.stats;

            document.getElementById('statTotalStaff').innerText = data.stats.totalStaff;
            if (document.getElementById('subStatStaffCampus')) {
              document.getElementById('subStatStaffCampus').innerText = `${data.stats.staffInCampusToday || 0} in campus • ${data.stats.staffOnLeaveToday || 0} on leave`;
            }

            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            if (document.getElementById('subStatStudentCampus')) {
              document.getElementById('subStatStudentCampus').innerText = `${data.stats.studentsInCampusToday || 0} in campus`;
            }

            document.getElementById('statPendingApprovals').innerText = data.stats.pendingApprovals;
            document.getElementById('statTotalClassrooms').innerText = data.stats.totalClassrooms;
            if (document.getElementById('statDayOrder')) {
              document.getElementById('statDayOrder').innerText = data.stats.dayOrder || 'Day 1';
            }
            if (document.getElementById('statEventsToday')) {
              document.getElementById('statEventsToday').innerText = data.stats.eventsToday || 0;
            }
          }
        })
        .catch(() => showLoading(false));
    }

    function loadUsers() {
      showLoading(true);
      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const query = new URLSearchParams({ search, branch, role, status }).toString();
      fetch(`/api/admin/users?${query}`)
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('usersTableBody');
          tbody.innerHTML = "";

          if (data.status === 'SUCCESS' && data.users.length > 0) {
            data.users.forEach(user => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              
              const statusBadge = user.status === 'Approved' 
                ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`
                : (user.status === 'Pending' 
                  ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`
                  : `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">${user.status}</span>`);

              const defaultPhoto = user.type === 'student'
                ? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'
                : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100';

              tr.innerHTML = `
                <td class="p-3 flex items-center gap-3">
                  <img src="${user.photo_url || defaultPhoto}" class="w-8 h-8 rounded-full border border-slate-700 object-cover">
                  <div>
                    <span class="font-bold text-slate-100 block">${user.name}</span>
                    <span class="text-[10px] text-slate-400 block">${user.email}</span>
                  </div>
                </td>
                <td class="p-3 font-mono text-slate-300">${user.id}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${user.branch || 'N/A'}</span></td>
                <td class="p-3 font-semibold text-amber-400">${user.role}</td>
                <td class="p-3">${statusBadge}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">No personnel records found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function toggleUserStatus(userId, userType, newStatus) {
      showLoading(true);
      fetch('/api/admin/users/toggle-status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadStats();
        } else {
          alert(data.message || 'Status update failed.');
        }
      })
      .catch(() => showLoading(false));
    }

    function openPasswordModal(id, name, type) {
      selectedUserForReset = { id, name, type };
      document.getElementById('pwdResetName').innerText = name;
      document.getElementById('pwdResetId').innerText = id;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('hidden');
      document.getElementById('passwordModal').classList.add('flex');
    }

    function closePasswordModal() {
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
    }

    function submitPasswordReset() {
      const newPassword = document.getElementById('newPasswordInput').value.trim();
      const alertEl = document.getElementById('pwdAlert');
      if (newPassword.length < 4) {
        alertEl.innerText = "Password must be at least 4 characters long.";
        alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
        alertEl.classList.remove('hidden');
        return;
      }

      showLoading(true);
      fetch('/api/admin/users/reset-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          userId: selectedUserForReset.id,
          userType: selectedUserForReset.type,
          newPassword: newPassword
        })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Password reset successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(closePasswordModal, 1200);
        } else {
          alertEl.innerText = data.message || "Failed to reset password.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function openAuditModal(id, name) {
      document.getElementById('auditProfileName').innerText = name;
      document.getElementById('auditProfileId').innerText = id;
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">Querying audit logs...</td></tr>`;
      document.getElementById('auditModal').classList.remove('hidden');
      document.getElementById('auditModal').classList.add('flex');

      fetch(`/api/audit-logs?targetId=${id}`)
        .then(res => res.json())
        .then(data => {
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">No profile audit logs recorded.</td></tr>`;
          }
        });
    }

    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function loadAuditTrail() {
      showLoading(true);
      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('auditTableBody');
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3 font-mono text-amber-400">${log.target_id || 'N/A'}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 font-mono text-slate-400 text-[10px]">${log.ip_address || '127.0.0.1'}</td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500">No system audit logs found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
      document.getElementById('registerModal').classList.add('flex');
    }

    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
      document.getElementById('registerModal').classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      if (type === 'student') {
        document.getElementById('directStudentFields').classList.remove('hidden');
        document.getElementById('directStaffFields').classList.add('hidden');
      } else {
        document.getElementById('directStudentFields').classList.add('hidden');
        document.getElementById('directStaffFields').classList.remove('hidden');
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const type = document.getElementById('regType').value;
      const name = document.getElementById('directRegName').value;
      const email = document.getElementById('directRegEmail').value;
      const password = document.getElementById('directRegPassword').value;
      const alertEl = document.getElementById('directRegAlert');

      let url = '/register/student';
      let payload = {};

      if (type === 'student') {
        payload = {
          name, email, password,
          reg_no: document.getElementById('directRegStudentId').value,
          adm_no: document.getElementById('directRegStudentAdm').value,
          branch: document.getElementById('directRegStudentBranch').value,
          admission_year: document.getElementById('directRegStudentYear').value,
          semester: document.getElementById('directRegStudentSem').value
        };
      } else {
        url = '/register/staff';
        payload = {
          name, email, password,
          mobile_no: document.getElementById('directRegStaffMobile').value,
          designation: document.getElementById('directRegStaffDesig').value,
          branch: document.getElementById('directRegStaffBranch').value
        };
      }

      showLoading(true);
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Profile registered successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
            loadStats();
          }, 1200);
        } else {
          alertEl.innerText = data.message || "Registration failed.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('photo', file);

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.innerText = 'Uploading...';
        statusEl.classList.remove('hidden');
      }

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (statusEl) {
            statusEl.innerText = 'Updated!';
            setTimeout(() => statusEl.classList.add('hidden'), 2000);
          }
          if (data.photo_url) {
            document.getElementById('sidebarStaffImg').src = data.photo_url;
          }
        } else {
          if (statusEl) {
            statusEl.innerText = 'Failed';
            statusEl.className = 'text-sm font-bold text-red-400';
          }
        }
      })
      .catch(() => {
        if (statusEl) {
          statusEl.innerText = 'Error';
          statusEl.className = 'text-sm font-bold text-red-400';
        }
      });
    }

    function loadExecutiveMetrics() {
      fetch('/api/admin/executive-kpis')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const inCampusStaff = data.leave_breakdown.total_staff_in_campus || ((data.leave_breakdown.total_staff || 89) - (data.leave_breakdown.total_on_leave || 0));
            document.getElementById('execStaffLeaveTotal').innerText = `${inCampusStaff} in campus • ${data.leave_breakdown.total_on_leave || 0} on leave`;

            if (document.getElementById('execStudentAttPct')) {
              const stdInCampus = data.students_in_campus || data.total_students || 0;
              const stdOnLeave = data.students_on_leave || 0;
              document.getElementById('execStudentAttPct').innerText = `${stdInCampus} in campus • ${stdOnLeave} on leave`;
            }

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
          <button type="submit" id="fnSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-slate-950 font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg">
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
          <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center">
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
            <img id="execModalAvatarPrev" src="/storage/avatars/default.png" onerror="this.src='/storage/avatars/default.png'" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-500/40 shadow-md">
            <label for="execModalPhotoInput" class="absolute inset-0 bg-black/60 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold cursor-pointer transition">
              <span class="material-symbols-rounded text-sm">photo_camera</span>
            </label>
          </div>
          <div class="flex-grow space-y-1">
            <span class="text-xs font-bold text-slate-200 block">Profile Picture</span>
            <p class="text-[11px] text-slate-400">PNG, JPG or GIF (Max 2MB)</p>
            <input type="file" id="execModalPhotoInput" accept="image/*" class="hidden" onchange="previewExecAvatar(this)">
            <button type="button" onclick="document.getElementById('execModalPhotoInput').click()" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-amber-300 rounded-lg text-[11px] font-bold border border-slate-700 transition cursor-pointer">
              Choose New Photo
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Full Name</label>
            <input type="text" id="execModalName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Login ID / Mobile No.</label>
            <input type="text" id="execModalMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white font-mono outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</label>
            <input type="email" id="execModalEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">New Password (Leave blank to keep unchanged)</label>
            <input type="password" id="execModalPassword" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition" placeholder="Minimum 4 characters">
          </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-800">
          <button type="button" onclick="closeExecutiveProfileModal()" class="px-4 py-2 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl font-bold text-xs transition cursor-pointer">
            Cancel
          </button>
          <button type="submit" id="execProfileSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black rounded-xl text-xs transition shadow-lg shadow-amber-500/20 cursor-pointer flex items-center gap-1.5">
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

    function openDepartmentTimetables() {
      window.open('/dashboard/principal/today-timetable', '_blank');
    }

    function openPendingApprovalsModal() {
      const modal = document.getElementById('pendingApprovalsModal');
      const loading = document.getElementById('pendingApprovalsModalLoading');
      const list = document.getElementById('pendingApprovalsList');
      const empty = document.getElementById('pendingApprovalsEmptyState');
      
      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      if (loading) loading.classList.remove('hidden');
      if (list) list.classList.add('hidden');
      if (empty) empty.classList.add('hidden');

      fetch('/api/admin/users?status=Pending')
        .then(res => res.json())
        .then(data => {
          if (loading) loading.classList.add('hidden');
          if (data.status === 'SUCCESS' && data.users && data.users.length > 0) {
            renderPendingUsersList(data.users);
            if (list) list.classList.remove('hidden');
            if (document.getElementById('pendingCountBadge')) {
              document.getElementById('pendingCountBadge').innerText = `${data.users.length} Application(s) Pending`;
            }
          } else {
            if (empty) empty.classList.remove('hidden');
            if (document.getElementById('pendingCountBadge')) {
              document.getElementById('pendingCountBadge').innerText = `0 Applications Pending`;
            }
          }
        })
        .catch(() => {
          if (loading) loading.classList.add('hidden');
          if (empty) empty.classList.remove('hidden');
        });
    }

    function closePendingApprovalsModal() {
      const modal = document.getElementById('pendingApprovalsModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function renderPendingUsersList(users) {
      const container = document.getElementById('pendingApprovalsList');
      if (!container) return;
      container.innerHTML = '';

      users.forEach(user => {
        const card = document.createElement('div');
        card.className = "p-4 rounded-xl bg-slate-950/70 border border-slate-800/80 hover:border-amber-500/40 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-md";

        const badgeColor = user.type === 'staff' ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' : 'bg-sky-500/10 text-sky-400 border-sky-500/20';

        card.innerHTML = `
          <div class="flex items-start gap-3 min-w-0">
            <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0 text-slate-300 font-bold">
              <span class="material-symbols-rounded text-lg">${user.type === 'staff' ? 'badge' : 'person'}</span>
            </div>
            <div class="min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-extrabold text-sm text-white truncate">${user.name}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase border ${badgeColor}">${user.type} - ${user.role}</span>
              </div>
              <p class="text-xs text-slate-400 font-mono mt-0.5 flex items-center gap-2 truncate">
                <span>ID: <strong class="text-slate-200">${user.id}</strong></span>
                <span>•</span>
                <span>Dept: <strong class="text-slate-200">${user.branch || 'General'}</strong></span>
              </p>
              <p class="text-[11px] text-slate-500 mt-0.5 truncate">${user.email || user.mobile_no || 'No Contact Info'}</p>
            </div>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 pt-2 sm:pt-0 border-t sm:border-0 border-slate-800/60 justify-end">
            <button onclick="approveUserFromModal('${user.id}', '${user.type}')" class="px-3.5 py-1.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">check_circle</span> Approve Login
            </button>
            <button onclick="rejectUserFromModal('${user.id}', '${user.type}')" class="px-3 py-1.5 bg-slate-900 hover:bg-red-950/80 border border-slate-800 hover:border-red-800 text-slate-400 hover:text-red-300 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">cancel</span> Reject
            </button>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function openStaffCampusModal() {
      const modal = document.getElementById('staffCampusModal');
      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if (currentStatsData) {
        if (document.getElementById('modalTotalStaffCount')) {
          document.getElementById('modalTotalStaffCount').innerText = currentStatsData.totalStaff || 0;
        }
        if (document.getElementById('modalStaffInCampusCount')) {
          document.getElementById('modalStaffInCampusCount').innerText = currentStatsData.staffInCampusToday || 0;
        }
        if (document.getElementById('modalStaffOnLeaveCount')) {
          document.getElementById('modalStaffOnLeaveCount').innerText = currentStatsData.staffOnLeaveToday || 0;
        }

        const list = document.getElementById('modalStaffOnLeaveList');
        if (list) {
          list.innerHTML = '';
          if (currentStatsData.staffOnLeaveDetails && currentStatsData.staffOnLeaveDetails.length > 0) {
            currentStatsData.staffOnLeaveDetails.forEach(s => {
              const card = document.createElement('div');
              card.className = "p-3.5 rounded-xl bg-slate-950/70 border border-slate-800/80 flex items-center justify-between gap-3 text-xs";
              card.innerHTML = `
                <div>
                  <span class="font-extrabold text-white text-sm block">${s.name}</span>
                  <span class="text-slate-400 font-mono text-[11px]">Dept: ${s.dept}</span>
                </div>
                <div class="text-right">
                  <span class="px-2.5 py-0.5 rounded-full font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] uppercase">${s.leave_type}</span>
                  <span class="text-slate-500 font-mono text-[10px] block mt-0.5">${s.from_date} to ${s.to_date}</span>
                </div>
              `;
              list.appendChild(card);
            });
          } else {
            list.innerHTML = `
              <div class="p-6 text-center text-slate-400 bg-slate-950/40 rounded-xl border border-slate-800">
                <span class="material-symbols-rounded text-emerald-400 text-2xl block mb-1">sentiment_satisfied</span>
                <span class="text-xs font-bold text-slate-300">All staff members are present in campus today!</span>
              </div>
            `;
          }
        }
      }
    }

    function closeStaffCampusModal() {
      const modal = document.getElementById('staffCampusModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function openStudentCampusModal() {
      const modal = document.getElementById('studentCampusModal');
      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if (currentStatsData) {
        if (document.getElementById('modalTotalStudentsCount')) {
          document.getElementById('modalTotalStudentsCount').innerText = currentStatsData.totalStudents || 0;
        }
        if (document.getElementById('modalStudentsInCampusCount')) {
          document.getElementById('modalStudentsInCampusCount').innerText = currentStatsData.studentsInCampusToday || 0;
        }
        if (document.getElementById('modalStudentsOnLeaveCount')) {
          document.getElementById('modalStudentsOnLeaveCount').innerText = currentStatsData.studentsOnLeaveToday || 0;
        }
      }
    }

    function closeStudentCampusModal() {
      const modal = document.getElementById('studentCampusModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function approveUserFromModal(userId, userType) {
      if (typeof changeStatus === 'function') {
        changeStatus(userId, userType, 'Approved');
        setTimeout(() => {
          openPendingApprovalsModal();
          if (typeof loadStats === 'function') loadStats();
        }, 400);
      }
    }

    function rejectUserFromModal(userId, userType) {
      if (confirm(`Reject pending registration application for ${userId}?`)) {
        if (typeof changeStatus === 'function') {
          changeStatus(userId, userType, 'Suspended');
          setTimeout(() => {
            openPendingApprovalsModal();
            if (typeof loadStats === 'function') loadStats();
          }, 400);
        }
      }
    }

    document.addEventListener('DOMContentLoaded', initTheme);
  </script>

  <!-- PENDING APPROVALS POPUP MODAL -->
  <div id="pendingApprovalsModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-200">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-slate-950/90 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20">
            <span class="material-symbols-rounded text-xl">pending_actions</span>
          </div>
          <div>
            <h3 class="text-base font-black text-white leading-tight">Pending Login Approvals</h3>
            <p class="text-xs text-slate-400 font-medium">Review and verify staff and student registration applications</p>
          </div>
        </div>
        <button onclick="closePendingApprovalsModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- Modal Content (Scrollable Container) -->
      <div class="p-6 overflow-y-auto space-y-4">
        <div id="pendingApprovalsModalLoading" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-amber-500 border-t-transparent"></div>
          <p class="text-xs text-slate-400 mt-2 font-semibold">Loading pending user applications...</p>
        </div>

        <div id="pendingApprovalsList" class="space-y-3 hidden">
          <!-- Rendered pending user cards will be injected here -->
        </div>

        <div id="pendingApprovalsEmptyState" class="hidden text-center py-12 space-y-3">
          <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto border border-emerald-500/20">
            <span class="material-symbols-rounded text-2xl">verified_user</span>
          </div>
          <h4 class="text-sm font-bold text-slate-200">All Logins Approved!</h4>
          <p class="text-xs text-slate-400 max-w-sm mx-auto">There are currently no pending registration requests awaiting administrative verification.</p>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-3.5 bg-slate-950/80 border-t border-slate-800 flex items-center justify-between text-xs">
        <span class="text-slate-400 font-mono text-[11px]" id="pendingCountBadge">0 Application(s) Pending</span>
        <button onclick="closePendingApprovalsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold transition-colors cursor-pointer">
          Close Window
        </button>
      </div>

  <!-- STAFF CAMPUS PRESENCE & LEAVE STATUS MODAL -->
  <div id="staffCampusModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-200">
      
      <!-- Header -->
      <div class="px-6 py-4 bg-slate-950/90 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20">
            <span class="material-symbols-rounded text-xl">badge</span>
          </div>
          <div>
            <h3 class="text-base font-black text-white leading-tight">Staff Campus Presence &amp; Leave Status</h3>
            <p class="text-xs text-slate-400 font-medium">Real-time daily staff presence and leave breakdown</p>
          </div>
        </div>
        <button onclick="closeStaffCampusModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- KPI Summary Cards Grid inside Modal -->
      <div class="p-6 overflow-y-auto space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-slate-400 font-extrabold uppercase tracking-wider mb-1">Total Registered Staff</span>
            <span id="modalTotalStaffCount" class="text-2xl font-black text-white">0</span>
          </div>
          <div class="p-4 rounded-xl bg-emerald-950/20 border border-emerald-500/30 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-emerald-400 font-extrabold uppercase tracking-wider mb-1">Staff In Campus Today</span>
            <span id="modalStaffInCampusCount" class="text-2xl font-black text-emerald-300">0</span>
          </div>
          <div class="p-4 rounded-xl bg-amber-950/20 border border-amber-500/30 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-amber-400 font-extrabold uppercase tracking-wider mb-1">Staff On Leave Today</span>
            <span id="modalStaffOnLeaveCount" class="text-2xl font-black text-amber-300">0</span>
          </div>
        </div>

        <!-- Staff On Leave Detail Roster -->
        <div>
          <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Staff Members On Leave Today
          </h4>
          <div id="modalStaffOnLeaveList" class="space-y-2.5">
            <!-- Staff leave entries rendered dynamically -->
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-3.5 bg-slate-950/80 border-t border-slate-800 flex items-center justify-end">
        <button onclick="closeStaffCampusModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold text-xs transition-colors cursor-pointer">
          Close Window
        </button>
      </div>

    </div>
  </div>


  <!-- STUDENT CAMPUS PRESENCE MODAL -->
  <div id="studentCampusModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-200">
      
      <!-- Header -->
      <div class="px-6 py-4 bg-slate-950/90 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-sky-500/10 text-sky-400 rounded-xl flex items-center justify-center border border-sky-500/20">
            <span class="material-symbols-rounded text-xl">school</span>
          </div>
          <div>
            <h3 class="text-base font-black text-white leading-tight">Student Campus Presence</h3>
            <p class="text-xs text-slate-400 font-medium">Real-time daily student enrollment and campus attendance summary</p>
          </div>
        </div>
        <button onclick="closeStudentCampusModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800/60 transition-colors cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- KPI Summary Cards Grid inside Modal -->
      <div class="p-6 overflow-y-auto space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-slate-400 font-extrabold uppercase tracking-wider mb-1">Total Enrolled Students</span>
            <span id="modalTotalStudentsCount" class="text-2xl font-black text-white">0</span>
          </div>
          <div class="p-4 rounded-xl bg-emerald-950/20 border border-emerald-500/30 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-emerald-400 font-extrabold uppercase tracking-wider mb-1">Students In Campus Today</span>
            <span id="modalStudentsInCampusCount" class="text-2xl font-black text-emerald-300">0</span>
          </div>
          <div class="p-4 rounded-xl bg-sky-950/20 border border-sky-500/30 flex flex-col items-center justify-center text-center">
            <span class="text-xs text-sky-400 font-extrabold uppercase tracking-wider mb-1">On Leave / Absent Today</span>
            <span id="modalStudentsOnLeaveCount" class="text-2xl font-black text-sky-300">0</span>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-3.5 bg-slate-950/80 border-t border-slate-800 flex items-center justify-end">
        <button onclick="closeStudentCampusModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold text-xs transition-colors cursor-pointer">
          Close Window
        </button>
      </div>

    </div>
  </div>

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
</body>
</html>
