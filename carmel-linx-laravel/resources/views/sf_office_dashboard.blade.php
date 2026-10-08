<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Carmel Linx - SF Office Administration Console</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Tailwind CSS Browser CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

  <style>
    :root {
      --app-bg: #090d16;
      --panel-bg: rgba(15, 23, 42, 0.85);
      --card-bg: rgba(15, 23, 42, 0.95);
      --card-border: rgba(255, 255, 255, 0.08);
      --text-main: #f1f5f9;
      --text-muted: #94a3b8;
      --table-th-bg: #0f172a;
      --table-tr-hover: rgba(30, 41, 59, 0.5);
      --input-bg: rgba(15, 23, 42, 0.9);
      --input-border: rgba(255, 255, 255, 0.15);
      --sidebar-bg: #060911;
      --sidebar-border: rgba(255, 255, 255, 0.08);
      --header-bg: rgba(9, 13, 22, 0.92);
      --header-border: rgba(255, 255, 255, 0.08);
    }

    [data-theme="light"] {
      --app-bg: #f8fafc;
      --panel-bg: #ffffff;
      --card-bg: #ffffff;
      --card-border: #e2e8f0;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --table-th-bg: #f1f5f9;
      --table-tr-hover: #f8fafc;
      --input-bg: #ffffff;
      --input-border: #cbd5e1;
      --sidebar-bg: #ffffff;
      --sidebar-border: #e2e8f0;
      --header-bg: rgba(255, 255, 255, 0.95);
      --header-border: #e2e8f0;
    }

    html, body {
      background-color: var(--app-bg);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 13px !important;
      min-height: 100vh;
      overflow-x: hidden;
      transition: background-color 0.25s ease, color 0.25s ease;
    }

    .font-mono-code {
      font-family: 'JetBrains Mono', monospace;
    }

    .brand-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 900 !important;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, #38bdf8 0%, #6366f1 50%, #ec4899 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    [data-theme="light"] .brand-title {
      background: linear-gradient(135deg, #0284c7 0%, #4f46e5 50%, #db2777 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .theme-card {
      background-color: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 0.75rem;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .theme-input {
      background-color: var(--input-bg) !important;
      border: 1px solid var(--input-border) !important;
      color: var(--text-main) !important;
      font-size: 12px !important;
      border-radius: 0.5rem;
      padding: 0.45rem 0.65rem;
      outline: none;
      transition: border-color 0.15s ease;
    }
    .theme-input:focus {
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    /* Compact Tables */
    .table-compact {
      width: 100%;
      border-collapse: collapse;
      font-size: 11.5px;
    }
    .table-compact th {
      background-color: var(--table-th-bg);
      color: var(--text-muted);
      font-weight: 700;
      text-transform: uppercase;
      font-size: 10px;
      letter-spacing: 0.04em;
      padding: 7px 10px;
      border-bottom: 1px solid var(--card-border);
      white-space: nowrap;
    }
    .table-compact td {
      padding: 6px 10px;
      border-bottom: 1px solid var(--card-border);
      vertical-align: middle;
    }
    .table-compact tbody tr:hover td {
      background-color: var(--table-tr-hover);
    }

    .transition-premium {
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Print styles */
    @media print {
      body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 10pt !important;
      }
      aside, header, #sidebarNav, .no-print, button, input, select {
        display: none !important;
      }
      main, .theme-card, .table-compact {
        width: 100% !important;
        background: #ffffff !important;
        border: none !important;
        box-shadow: none !important;
      }
      .print-only {
        display: block !important;
      }
    }
  </style>
</head>
<body class="flex flex-col min-h-screen">

  <!-- ============================================================== -->
  <!-- TOP TITLE BAR (Standard Carmel Linx with Logo & Theme Switcher) -->
  <!-- ============================================================== -->
  <header class="min-h-[76px] md:h-20 border-b z-30 sticky top-0 px-5 md:px-7 flex items-center justify-between shadow-sm backdrop-blur-md"
          style="background-color: var(--header-bg); border-color: var(--header-border);">
    
    <!-- Left: Logo & Portal Title -->
    <div class="flex items-center gap-3.5">
      <img src="{{ asset('logo.jpg') }}" alt="Carmel Linx" class="w-11 h-11 md:w-12 md:h-12 rounded-xl object-cover shadow border border-slate-700/40 shrink-0">
      
      <div class="flex items-center gap-2.5 flex-wrap">
        <span class="brand-title text-lg md:text-xl leading-tight">Carmel Linx</span>
        <span class="text-slate-500 font-bold hidden sm:inline">|</span>
        <h1 class="text-sm md:text-base font-extrabold tracking-tight m-0" style="color: var(--text-main);">
          SF Office Administration Desk
        </h1>
        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-purple-500/15 text-purple-400 border border-purple-500/30 hidden lg:inline-flex">
          Self-Financing Stream
        </span>
      </div>
    </div>

    <!-- Right: Header Controls -->
    <div class="flex items-center gap-2 md:gap-3">
      <!-- Sync Status Indicator -->
      <div id="syncIndicator" class="hidden items-center gap-1.5 text-xs text-slate-400">
        <div class="w-3.5 h-3.5 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span class="hidden sm:inline text-[11px]">Syncing...</span>
      </div>

      <!-- Quick Sync Button -->
      <button onclick="refreshCurrentTab()" class="px-2.5 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition-premium"
              style="background-color: var(--card-bg); border-color: var(--card-border); color: var(--text-muted);" title="Refresh live data">
        <span class="material-symbols-rounded text-sm">sync</span>
        <span class="hidden md:inline text-[11px]">Refresh</span>
      </button>

      <!-- Theme Switcher (Dark / Light) -->
      <button id="themeToggleBtn" onclick="toggleTheme()" class="px-2.5 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition-premium"
              style="background-color: var(--card-bg); border-color: var(--card-border); color: var(--text-muted);" title="Toggle Dark/Light Mode">
        <span class="material-symbols-rounded text-sm" id="themeIcon">light_mode</span>
        <span class="hidden md:inline text-[11px]" id="themeLabel">Light</span>
      </button>

      <!-- Fullscreen Button -->
      <button onclick="toggleFullscreen()" class="px-2.5 py-1.5 rounded-lg border text-xs font-semibold flex items-center gap-1.5 transition-premium hidden sm:flex"
              style="background-color: var(--card-bg); border-color: var(--card-border); color: var(--text-muted);" title="Toggle Fullscreen">
        <span class="material-symbols-rounded text-sm">fullscreen</span>
      </button>

      <!-- Return / Sign Out Button (Standard Carmel Linx Red Button) -->
      <a href="{{ url('/logout') }}" class="px-3 py-1.5 rounded-lg font-bold text-xs flex items-center gap-1.5 transition-premium no-underline bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/40 shadow-sm"
         title="Sign Out of SF Office Console">
        <span class="material-symbols-rounded text-sm">logout</span>
        <span class="hidden sm:inline">Sign Out</span>
      </a>

      <!-- Profile Avatar with Online Green Dot -->
      <div class="relative shrink-0 flex items-center pl-1 cursor-pointer" onclick="switchPanel('security')" title="SF Office Desk (9000000009)">
        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-700 object-cover shadow-sm">
        <!-- Online Green Dot (User Request Compliance) -->
        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 rounded-full shadow-sm ring-1 ring-emerald-400/40 animate-pulse"
              style="border-color: var(--header-bg);" title="Status: Online"></span>
      </div>
    </div>
  </header>

  <!-- ============================================================== -->
  <!-- MAIN WORKSPACE: SIDEBAR + CONTENT AREA                         -->
  <!-- ============================================================== -->
  <div class="flex flex-1 overflow-hidden">
    
    <!-- SIDEBAR NAVIGATION (Desktop & Tablet) -->
    <aside id="sidebarNav" class="w-64 md:w-68 border-r shrink-0 flex flex-col justify-between overflow-y-auto scrollbar-hidden z-20"
           style="background-color: var(--sidebar-bg); border-color: var(--sidebar-border);">
      
      <div>
        <!-- Profile Mini Badge -->
        <div class="p-3.5 border-b flex items-center gap-3" style="border-color: var(--card-border);">
          <div class="relative shrink-0">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150" alt="Avatar" class="w-10 h-10 rounded-full border border-slate-700 object-cover shadow-inner">
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 rounded-full shadow-sm ring-1 ring-emerald-400/50"
                  style="border-color: var(--sidebar-bg);" title="Online"></span>
          </div>
          <div class="overflow-hidden">
            <span class="font-extrabold text-sm block truncate" style="color: var(--text-main);">SF Office Desk</span>
            <span class="text-xs font-mono-code text-cyan-400 block font-semibold truncate">9000000009</span>
            <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Office Administrator</span>
          </div>
        </div>

        <!-- Navigation Links (Enhanced Legibility) -->
        <nav class="p-2.5 space-y-1">
          <div class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Main Console</div>
          
          <button id="navBtn-overview" onclick="switchPanel('overview')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-blue-400 bg-blue-500/10 border-l-2 border-blue-500 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">dashboard</span>
              <span>Overview & Stats</span>
            </div>
          </button>

          <button id="navBtn-approvals" onclick="switchPanel('approvals')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">event_available</span>
              <span>Leave Management</span>
            </div>
            <span id="badgePendingLeaves" class="hidden px-1.5 py-0.2 rounded-full text-[9px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30">0</span>
          </button>

          <button id="navBtn-punches" onclick="switchPanel('punches')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">schedule</span>
              <span>Mobile Punch Logs</span>
            </div>
          </button>

          <div class="pt-2 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Reports & Audit</div>

          <button id="navBtn-monthly" onclick="switchPanel('monthly')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">calendar_month</span>
              <span>Monthly Leave Report</span>
            </div>
          </button>

          <button id="navBtn-yearly" onclick="switchPanel('yearly')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">event_note</span>
              <span>Annual Ledger (Apr-Mar)</span>
            </div>
          </button>

          <button id="navBtn-directory" onclick="switchPanel('directory')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">groups</span>
              <span>SF Staff Balances</span>
            </div>
            <span class="text-[9.5px] font-bold text-cyan-400 font-mono" id="sidebarStaffCountBadge">31</span>
          </button>

          <div class="pt-2 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">System & Finance</div>

          <button id="navBtn-settings" onclick="switchPanel('settings')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">tune</span>
              <span>Office Rules & Limits</span>
            </div>
          </button>

          <button id="navBtn-salary" onclick="switchPanel('salary')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">receipt_long</span>
              <span>Salary & ITR Docs</span>
            </div>
            <span class="text-[9.5px] font-bold text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded">Soon</span>
          </button>

          <button id="navBtn-security" onclick="switchPanel('security')" class="nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer">
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-rounded text-lg">lock_reset</span>
              <span>Change Password</span>
            </div>
          </button>
        </nav>
      </div>

      <!-- Sidebar Footer -->
      <div class="p-3 border-t text-center text-[10px] text-slate-500" style="border-color: var(--card-border);">
        <span>Carmel Linx • SF Office v2.1</span>
      </div>
    </aside>

    <!-- CONTENT DISPLAY AREA -->
    <main class="flex-1 overflow-y-auto p-4 md:p-6 space-y-5">
      
      <!-- Global Flash / Toast Message Banner -->
      <div id="globalToast" class="hidden p-3 rounded-xl font-bold text-xs transition-premium shadow-md flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-base" id="toastIcon">info</span>
          <span id="toastMessage">Notification message</span>
        </div>
        <button onclick="hideToast()" class="text-slate-400 hover:text-white">
          <span class="material-symbols-rounded text-sm">close</span>
        </button>
      </div>

      <!-- ============================================================== -->
      <!-- PANEL 1: OVERVIEW & LIVE METRICS                               -->
      <!-- ============================================================== -->
      <div id="panel-overview" class="space-y-5">
        
        <!-- Welcome Strip -->
        <div class="theme-card p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-xl">admin_panel_settings</span>
            </div>
            <div>
              <h2 class="font-extrabold text-sm md:text-base leading-tight" style="color: var(--text-main);">
                Self-Financing Staff Leave & Punch Administration Desk
              </h2>
              <p class="text-[11px] mt-0.5" style="color: var(--text-muted);">
                Managing all 31 SF staff profiles (Electronics, Computer, Automobile & Gen SF). Live 3-tier approvals, mobile face punches, CCL holiday credits, and April 1–March 31 annual accounts.
              </p>
            </div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <button onclick="switchPanel('backfill')" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-1.5 transition-premium shadow-sm">
              <span class="material-symbols-rounded text-sm">add_circle</span>
              <span>Backfill Past Leave</span>
            </button>
            <button onclick="switchPanel('approvals')" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs flex items-center gap-1.5 transition-premium shadow-sm">
              <span class="material-symbols-rounded text-sm">pending_actions</span>
              <span>Review Queue</span>
            </button>
          </div>
        </div>

        <!-- 6 Executive Compact Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
          <!-- Stat 1: Total SF Staff -->
          <div class="theme-card p-3 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider">SF Staff Total</span>
              <span class="material-symbols-rounded text-blue-400 text-base">badge</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-blue-400 font-mono-code" id="statStaffTotal">31</div>
              <div class="text-[9.5px] text-slate-400 mt-0.5">EL, AU, CT & GEN SF</div>
            </div>
          </div>

          <!-- Stat 2: Punches Today -->
          <div class="theme-card p-3 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider">Punched Today</span>
              <span class="material-symbols-rounded text-emerald-400 text-base">how_to_reg</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-emerald-400 font-mono-code" id="statPunchedToday">--</div>
              <div class="text-[9.5px] text-slate-400 mt-0.5">Mobile Face Punch</div>
            </div>
          </div>

          <!-- Stat 3: Staff On Leave Today -->
          <div class="theme-card p-3 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider">On Leave Today</span>
              <span class="material-symbols-rounded text-purple-400 text-base">person_off</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-purple-400 font-mono-code" id="statOnLeaveToday">--</div>
              <div class="text-[9.5px] text-slate-400 mt-0.5">Approved Sanctions</div>
            </div>
          </div>

          <!-- Stat 4: Pending Office Review -->
          <div onclick="switchPanel('approvals')" class="theme-card p-3 flex flex-col justify-between border-amber-500/40 cursor-pointer hover:border-amber-400 transition-premium" title="Open Stage 1 Office Review Queue">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400">Office Queue</span>
              <span class="material-symbols-rounded text-amber-400 text-base">pending_actions</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-amber-300 font-mono-code" id="statPendingOffice">--</div>
              <div class="text-[9.5px] text-amber-400/80 mt-0.5">Stage 1 Approvals</div>
            </div>
          </div>

          <!-- Stat 5: Active CCL Balance Pool -->
          <div onclick="switchPanel('ccl')" class="theme-card p-3 flex flex-col justify-between cursor-pointer hover:border-cyan-400 transition-premium" title="Open CCL Holiday Credit Desk">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider">Active CCL Pool</span>
              <span class="material-symbols-rounded text-cyan-400 text-base">beach_access</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-cyan-300 font-mono-code" id="statActiveCcl">--</div>
              <div class="text-[9.5px] text-slate-400 mt-0.5">Valid < 60 days</div>
            </div>
          </div>

          <!-- Stat 6: Backfill Records -->
          <div onclick="switchPanel('backfill')" class="theme-card p-3 flex flex-col justify-between cursor-pointer hover:border-indigo-400 transition-premium" title="Open Past Leaves Backfill Desk">
            <div class="flex items-center justify-between text-slate-400">
              <span class="text-[10px] font-extrabold uppercase tracking-wider">Past Backfills</span>
              <span class="material-symbols-rounded text-rose-400 text-base">history</span>
            </div>
            <div class="mt-2">
              <div class="text-xl font-black text-rose-300 font-mono-code" id="statBackfillsCount">--</div>
              <div class="text-[9.5px] text-slate-400 mt-0.5">Mid-Year Logged</div>
            </div>
          </div>
        </div>

        <!-- 2 Column Overview Section: Recent Punches & Urgent Approvals -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          
          <!-- Recent Mobile Time Punch Stream -->
          <div class="theme-card p-4 space-y-3">
            <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--card-border);">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-base text-emerald-400">schedule</span>
                <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Recent Mobile Face Punch Logs</h3>
              </div>
              <button onclick="switchPanel('punches')" class="text-[11px] font-bold text-blue-400 hover:text-blue-300">View Full Log →</button>
            </div>
            
            <div class="overflow-x-auto scrollbar-hidden">
              <table class="table-compact">
                <thead>
                  <tr>
                    <th>Staff Name</th>
                    <th>Dept</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Work Duration</th>
                    <th>GPS In</th>
                  </tr>
                </thead>
                <tbody id="overviewPunchesTableBody">
                  <tr><td colspan="6" class="text-center py-4 text-slate-400">Loading today's punch stream...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pending Office Clearances -->
          <div class="theme-card p-4 space-y-3">
            <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--card-border);">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-base text-amber-400">pending_actions</span>
                <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Leave Applications Awaiting Office Action</h3>
              </div>
              <button onclick="switchPanel('approvals')" class="text-[11px] font-bold text-amber-400 hover:text-amber-300">Open Queue →</button>
            </div>
            
            <div class="overflow-x-auto scrollbar-hidden">
              <table class="table-compact">
                <thead>
                  <tr>
                    <th>Staff Name</th>
                    <th>Type</th>
                    <th>Dates</th>
                    <th>Days</th>
                    <th>Office Tier</th>
                    <th class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody id="overviewPendingTableBody">
                  <tr><td colspan="6" class="text-center py-4 text-slate-400">Checking pending requests...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 2: LEAVE MANAGEMENT & SPECIAL ACTIONS                    -->
      <!-- ============================================================== -->
      <div id="panel-approvals" class="hidden space-y-4">
        
        <!-- Segmented Sub-Tab Switcher: Regular Approvals vs Special Cases -->
        <div class="flex flex-wrap items-center justify-between gap-3 p-2 rounded-xl border" style="background-color: var(--card-bg); border-color: var(--card-border);">
          <div class="flex flex-wrap items-center gap-2">
            <!-- Tab 1: Regular Leave Approvals Queue -->
            <button id="leaveSubTabBtn-queue" onclick="switchLeaveSubTab('queue')" class="px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition-premium bg-blue-600 text-white shadow-sm cursor-pointer">
              <span class="material-symbols-rounded text-base">fact_check</span>
              <span>Leave Approvals (3-Tier Queue)</span>
              <span id="subtabPendingBadge" class="hidden px-1.5 py-0.2 rounded-full text-[9px] font-black bg-white/20 text-white">0</span>
            </button>

            <!-- Tab 2: Past Leaves Backfill (Special Case) -->
            <button id="leaveSubTabBtn-backfill" onclick="switchLeaveSubTab('backfill')" class="px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition-premium text-slate-400 hover:text-white hover:bg-slate-800/50 cursor-pointer">
              <span class="material-symbols-rounded text-base">history_edu</span>
              <span>Past Leaves Backfill</span>
              <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-300">Special Setup</span>
            </button>

            <!-- Tab 3: CCL Holiday Duty Credits (Special Case) -->
            <button id="leaveSubTabBtn-ccl" onclick="switchLeaveSubTab('ccl')" class="px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition-premium text-slate-400 hover:text-white hover:bg-slate-800/50 cursor-pointer">
              <span class="material-symbols-rounded text-base">beach_access</span>
              <span>CCL Holiday Credit Desk</span>
              <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300">60d Rule</span>
            </button>
          </div>

          <div class="text-[11px] text-slate-400 font-medium px-2 hidden lg:block">
            <span class="text-slate-300 font-bold">Leave Financial Cycle:</span> <span class="font-mono text-cyan-400">Apr 1 – Mar 31</span>
          </div>
        </div>

        <!-- SUBVIEW 1: REGULAR LEAVE APPROVALS QUEUE -->
        <div id="leaveSubView-queue" class="space-y-4">
          <!-- Workflow Guidance Banner -->
        <div class="theme-card p-3.5 bg-gradient-to-r from-amber-500/10 via-blue-500/10 to-indigo-500/10 border-amber-500/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <span class="material-symbols-rounded text-amber-400 text-2xl">account_tree</span>
            <div>
              <h3 class="font-extrabold text-xs text-amber-300">SF Staff Leave Approval Workflow (3-Tier Clearance)</h3>
              <p class="text-[11px] text-slate-400 mt-0.5">
                Staff applies <span class="text-slate-200">→</span>
                <strong class="text-amber-300">Stage 1: SF Office Review</strong> (Verify balance & dates) <span class="text-slate-200">→</span>
                <strong class="text-blue-300">Stage 2: SF Academic Coordinator</strong> <span class="text-slate-200">→</span>
                <strong class="text-emerald-300">Stage 3: Principal Final Sanction</strong>.
              </p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <select id="leaveFilterStatus" onchange="loadLeaveRequests()" class="theme-input text-xs">
              <option value="pending_office">Pending Office Review</option>
              <option value="approved">Approved / In Progress</option>
              <option value="rejected">Rejected Applications</option>
              <option value="all">All Applications (Cycle)</option>
            </select>
            <select id="leaveFilterDept" onchange="loadLeaveRequests()" class="theme-input text-xs">
              <option value="">All Branches</option>
              <option value="EL">Electronics (EL)</option>
              <option value="AU">Automobile (AU)</option>
              <option value="CT">Computer (CT)</option>
              <option value="GEN_SF">General SF (GEN_SF)</option>
            </select>
          </div>
        </div>

        <!-- Approvals Table Card -->
        <div class="theme-card overflow-hidden">
          <div class="p-3 border-b flex items-center justify-between" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-blue-400">fact_check</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Staff Leave Applications</h3>
              <span id="leaveQueueCountBadge" class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30">0</span>
            </div>
            <button onclick="loadLeaveRequests()" class="theme-input text-xs py-1 px-2.5 flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">refresh</span> Refresh
            </button>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Code / Submitted</th>
                  <th>Staff Member</th>
                  <th>Dept</th>
                  <th>Category</th>
                  <th>Leave Dates</th>
                  <th>Days</th>
                  <th>Reason & Remarks</th>
                  <th>Office Review (T1)</th>
                  <th>Coordinator (T2)</th>
                  <th>Principal (T3)</th>
                  <th>Overall</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody id="leaveRequestsTableBody">
                <tr><td colspan="12" class="text-center py-6 text-slate-400">Loading leave requests...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!-- End SUBVIEW 1: leaveSubView-queue -->

      <!-- ============================================================== -->
      <!-- SUBVIEW 2: PAST LEAVES BACKFILL (Special Case)                 -->
      <!-- ============================================================== -->
      <div id="leaveSubView-backfill" class="hidden space-y-4">
        
        <!-- Explanation Banner -->
        <div class="theme-card p-3.5 bg-indigo-500/10 border-indigo-500/20 flex items-start gap-3">
          <span class="material-symbols-rounded text-indigo-400 text-2xl shrink-0">history_edu</span>
          <div>
            <h3 class="font-extrabold text-xs text-indigo-300">Mid-Year Implementation: Past Leave Backfill Console</h3>
            <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">
              Because this portal is implemented during the academic year, staff leaves taken prior to today (from April 1st onward) can be directly entered here by the office. Once entered, these leaves are automatically credited to the staff member's ledger and subtracted from their remaining CL balance, ensuring that all upcoming applications display 100% accurate remaining leaves.
            </p>
          </div>
        </div>

        <!-- Backfill Input Form -->
        <div class="theme-card p-4 space-y-4">
          <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-base text-indigo-400">post_add</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Enter Past Leave Record for SF Staff</h3>
            </div>
            <span class="text-[10px] text-slate-400">All fields marked with * are required</span>
          </div>

          <form id="backfillForm" onsubmit="submitPastLeaveEntry(event)" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <!-- Staff Select -->
            <div class="md:col-span-2">
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Select SF Staff Member *</label>
              <select id="bf_staff_mobile" required class="theme-input w-full text-xs">
                <option value="">-- Choose SF Staff Member (31 Staff) --</option>
              </select>
            </div>

            <!-- Leave Category -->
            <div>
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Leave Category *</label>
              <select id="bf_leave_type" required class="theme-input w-full text-xs">
                <option value="Casual Leave">Casual Leave (CL)</option>
                <option value="Compensatory Casual Leave">Compensatory CL (CCL)</option>
                <option value="Duty Leave">Duty Leave (DL)</option>
                <option value="Official Duty Leave">Official Duty Leave (ODL)</option>
                <option value="Medical Leave">Medical Leave (ML)</option>
                <option value="Loss of Pay">Loss of Pay (LOP)</option>
                <option value="Special Leave">Special Leave</option>
              </select>
            </div>

            <!-- Session Type -->
            <div>
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Session *</label>
              <select id="bf_session_type" onchange="autoCalculateBackfillDays()" required class="theme-input w-full text-xs">
                <option value="Full Day">Full Day</option>
                <option value="FN">Forenoon (FN - 0.5 Day)</option>
                <option value="AN">Afternoon (AN - 0.5 Day)</option>
              </select>
            </div>

            <!-- From Date -->
            <div>
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">From Date *</label>
              <input type="date" id="bf_from_date" required onchange="autoCalculateBackfillDays()" class="theme-input w-full text-xs">
            </div>

            <!-- To Date -->
            <div>
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">To Date *</label>
              <input type="date" id="bf_to_date" required onchange="autoCalculateBackfillDays()" class="theme-input w-full text-xs">
            </div>

            <!-- Total Days (Auto calculated) -->
            <div>
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Total Days *</label>
              <input type="number" id="bf_total_days" step="0.5" min="0.5" max="30" value="1.0" required class="theme-input w-full text-xs font-mono font-bold">
            </div>

            <!-- Reason / Remarks -->
            <div class="md:col-span-2">
              <label class="block text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Reason / Office Remarks</label>
              <input type="text" id="bf_reason" placeholder="e.g. Past leave record as per office physical muster" class="theme-input w-full text-xs">
            </div>

            <!-- Submit Button -->
            <div class="md:col-span-1 flex items-end">
              <button type="submit" id="bfSubmitBtn" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">save</span>
                <span>Save to Ledger</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Backfilled Records Ledger -->
        <div class="theme-card overflow-hidden">
          <div class="p-3 border-b flex items-center justify-between" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-indigo-400">history</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Historical Backfilled Leaves Ledger</h3>
              <span id="backfillCountBadge" class="px-2 py-0.5 rounded-full text-[9px] font-black bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">0</span>
            </div>
            <button onclick="loadBackfilledRecords()" class="theme-input text-xs py-1 px-2.5 flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">refresh</span> Refresh
            </button>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Log Code</th>
                  <th>Staff Member</th>
                  <th>Branch</th>
                  <th>Leave Type</th>
                  <th>From Date</th>
                  <th>To Date</th>
                  <th>Days</th>
                  <th>Session</th>
                  <th>Reason / Remarks</th>
                  <th>Logged On</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody id="backfillTableBody">
                <tr><td colspan="11" class="text-center py-6 text-slate-400">Loading historical backfill logs...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!-- End SUBVIEW 2: leaveSubView-backfill -->

      <!-- ============================================================== -->
      <!-- SUBVIEW 3: CCL HOLIDAY DUTY CREDITS (Special Case)             -->
      <!-- ============================================================== -->
      <div id="leaveSubView-ccl" class="hidden space-y-4">
        
        <!-- Rule Card -->
        <div class="theme-card p-3.5 bg-emerald-500/10 border-emerald-500/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <span class="material-symbols-rounded text-emerald-400 text-2xl shrink-0">beach_access</span>
            <div>
              <h3 class="font-extrabold text-xs text-emerald-300">Compensatory Casual Leave (CCL) Desk</h3>
              <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">
                When SF staff performs duty on <strong>Saturdays, Sundays or Public Holidays</strong>, they earn CCL.
                Full day duty (&ge; 5.5 hours) earns <strong>1.0 CCL</strong>. Half day duty earns <strong>0.5 CCL</strong>.
                Rule: CCL must be availed within <strong>60 days (2 months)</strong> from the duty date.
              </p>
            </div>
          </div>
          <button onclick="openManualCclModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 transition-premium shadow-sm shrink-0 cursor-pointer">
            <span class="material-symbols-rounded text-sm">add_task</span>
            <span>Manual CCL Credit</span>
          </button>
        </div>

        <!-- Eligible Holiday Duty Punches Detected by System -->
        <div class="theme-card overflow-hidden">
          <div class="p-3 border-b flex items-center justify-between" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-emerald-400">holiday_village</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Holiday & Saturday Mobile Punches (CCL Eligible Extra Work)</h3>
            </div>
            <button onclick="loadCclEligiblePunches()" class="theme-input text-xs py-1 px-2.5 flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">refresh</span> Refresh
            </button>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Punch Date</th>
                  <th>Day</th>
                  <th>Staff Name</th>
                  <th>Dept</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Work Duration</th>
                  <th>Geo Distance</th>
                  <th>CCL Earned</th>
                  <th class="text-right">Credit Status / Action</th>
                </tr>
              </thead>
              <tbody id="cclEligiblePunchesTableBody">
                <tr><td colspan="10" class="text-center py-6 text-slate-400">Scanning holiday & weekend punches...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Active CCL Ledger -->
        <div class="theme-card overflow-hidden">
          <div class="p-3 border-b flex items-center justify-between" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-cyan-400">account_balance_wallet</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">CCL Credits & Availed Status Ledger</h3>
            </div>
            <select id="cclLedgerStatusFilter" onchange="loadCclLedger()" class="theme-input text-xs py-1">
              <option value="">All Credits</option>
              <option value="Active">Active & Valid</option>
              <option value="Consumed">Fully Availed</option>
              <option value="Expired">Expired (> 60 Days)</option>
            </select>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Staff Name</th>
                  <th>Duty Date</th>
                  <th>Session</th>
                  <th>Earned Days</th>
                  <th>Used Days</th>
                  <th>Remaining</th>
                  <th>Valid Until (60 Days)</th>
                  <th>Status</th>
                  <th>Credited By</th>
                </tr>
              </thead>
              <tbody id="cclLedgerTableBody">
                <tr><td colspan="9" class="text-center py-6 text-slate-400">Loading CCL credit ledger...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!-- End SUBVIEW 3: leaveSubView-ccl -->

    </div> <!-- End PANEL 2: panel-approvals (Leave Management) -->

    <!-- ============================================================== -->
    <!-- PANEL 3: MOBILE TIME PUNCH LOGS                                -->
    <!-- ============================================================== -->
    <div id="panel-punches" class="hidden space-y-4">
        
        <!-- Filter Bar -->
        <div class="theme-card p-3.5 flex flex-wrap items-center justify-between gap-3">
          <div class="flex flex-wrap items-center gap-2.5">
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">From Date</label>
              <input type="date" id="punchDateFrom" class="theme-input text-xs">
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">To Date</label>
              <input type="date" id="punchDateTo" class="theme-input text-xs">
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Branch</label>
              <select id="punchBranchFilter" class="theme-input text-xs">
                <option value="">All Branches</option>
                <option value="EL">Electronics (EL)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="CT">Computer (CT)</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Quick Date</label>
              <div class="flex items-center gap-1 mt-0.5">
                <button type="button" onclick="setPunchQuickDate('today')" class="theme-input py-1 px-2 text-[10px] font-bold">Today</button>
                <button type="button" onclick="setPunchQuickDate('7days')" class="theme-input py-1 px-2 text-[10px] font-bold">7 Days</button>
                <button type="button" onclick="setPunchQuickDate('30days')" class="theme-input py-1 px-2 text-[10px] font-bold">30 Days</button>
              </div>
            </div>
            <div class="flex items-center gap-1.5 mt-3 pt-1">
              <input type="checkbox" id="punchHolidayOnly" class="rounded accent-blue-500">
              <label for="punchHolidayOnly" class="text-xs font-bold text-slate-300 cursor-pointer">Holiday/Saturday Only</label>
            </div>
          </div>

          <div class="flex items-end">
            <button onclick="loadPunchLogs()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
              <span class="material-symbols-rounded text-sm">search</span>
              <span>Filter Logs</span>
            </button>
          </div>
        </div>

        <!-- Punch Logs Table -->
        <div class="theme-card overflow-hidden">
          <div class="p-3 border-b flex items-center justify-between" style="border-color: var(--card-border);">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-blue-400">schedule</span>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Mobile Face Punch Register</h3>
              <span id="punchLogsCountBadge" class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30">0</span>
            </div>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Date &amp; Day</th>
                  <th>Staff Member</th>
                  <th>Branch / Role</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Work Duration</th>
                  <th>GPS In Distance</th>
                  <th>GPS Out Distance</th>
                  <th>Day Type</th>
                  <th>Punch Status</th>
                </tr>
              </thead>
              <tbody id="punchLogsTableBody">
                <tr><td colspan="10" class="text-center py-6 text-slate-400">Loading mobile punch logs...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 6: MONTHLY LEAVE REPORT                                  -->
      <!-- ============================================================== -->
      <div id="panel-monthly" class="hidden space-y-4">
        
        <!-- Controls & Filter Strip -->
        <div class="theme-card p-3.5 flex flex-wrap items-center justify-between gap-3 no-print">
          <div class="flex flex-wrap items-center gap-3">
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Select Month</label>
              <select id="monthlyReportMonth" class="theme-input text-xs font-bold">
                @for($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ $m == (int)date('m') ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                  </option>
                @endfor
              </select>
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Year</label>
              <select id="monthlyReportYear" class="theme-input text-xs font-bold">
                @for($y = (int)date('Y') - 1; $y <= (int)date('Y') + 1; $y++)
                  <option value="{{ $y }}" {{ $y == (int)date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Department</label>
              <select id="monthlyReportDept" class="theme-input text-xs">
                <option value="">All SF Departments</option>
                <option value="EL">Electronics (EL)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="CT">Computer (CT)</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div class="pt-3">
              <button onclick="loadMonthlyReport()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">analytics</span>
                <span>Generate Report</span>
              </button>
            </div>
          </div>

          <div class="pt-3">
            <button onclick="window.print()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-premium cursor-pointer">
              <span class="material-symbols-rounded text-sm">print</span>
              <span>Print Monthly Report</span>
            </button>
          </div>
        </div>

        <!-- Monthly Report Statement Card -->
        <div class="theme-card p-4 space-y-3">
          <div class="border-b pb-3 flex items-center justify-between" style="border-color: var(--card-border);">
            <div>
              <h2 class="text-sm font-black" style="color: var(--text-main);" id="monthlyReportTitle">
                Monthly Staff Leave Report - {{ date('F Y') }}
              </h2>
              <span class="text-[11px] text-slate-400">Self-Financing Faculty Leave Ledger Summary</span>
            </div>
            <div class="text-right">
              <span class="text-[10px] text-slate-400 block">Generated by Carmel Linx SF Office</span>
              <span class="text-[10px] font-mono-code text-cyan-400" id="monthlyGeneratedTimestamp">--</span>
            </div>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact text-center">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Staff Name</th>
                  <th>Dept</th>
                  <th>Designation</th>
                  <th class="text-cyan-400">CL (Casual)</th>
                  <th class="text-emerald-400">CCL (Compensatory)</th>
                  <th class="text-indigo-400">DL (Duty)</th>
                  <th class="text-blue-400">ODL (Official Duty)</th>
                  <th class="text-amber-400">ML (Medical)</th>
                  <th class="text-rose-400">LOP (Loss of Pay)</th>
                  <th>Others</th>
                  <th class="text-purple-400 font-black">Total Leaves</th>
                </tr>
              </thead>
              <tbody id="monthlyReportTableBody">
                <tr><td colspan="12" class="text-center py-6 text-slate-400">Select month and click 'Generate Report'...</td></tr>
              </tbody>
              <tfoot id="monthlyReportTableFoot" class="font-extrabold border-t-2" style="border-color: var(--card-border); background-color: var(--table-th-bg);">
                <!-- Grand totals populated via JS -->
              </tfoot>
            </table>
          </div>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 7: CUMULATIVE & ANNUAL LEAVE REPORT (Apr 1 – Mar 31)     -->
      <!-- ============================================================== -->
      <div id="panel-yearly" class="hidden space-y-4">
        
        <!-- Controls Bar -->
        <div class="theme-card p-3.5 flex flex-wrap items-center justify-between gap-3 no-print">
          <div class="flex flex-wrap items-center gap-3">
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Leave Financial Year</label>
              <select id="yearlyReportYearSelect" onchange="loadYearlyReport()" class="theme-input text-xs font-bold font-mono">
                <option value="2026">2026 - 2027 (Active Cycle)</option>
                <option value="2025">2025 - 2026</option>
              </select>
            </div>
            <div>
              <label class="block text-[9.5px] font-bold text-slate-400 uppercase">Department</label>
              <select id="yearlyReportDept" onchange="loadYearlyReport()" class="theme-input text-xs">
                <option value="">All SF Branches</option>
                <option value="EL">Electronics (EL)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="CT">Computer (CT)</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div class="pt-3">
              <button onclick="loadYearlyReport()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">sync</span>
                <span>Refresh Statement</span>
              </button>
            </div>
          </div>

          <div class="pt-3">
            <button onclick="window.print()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-premium cursor-pointer">
              <span class="material-symbols-rounded text-sm">print</span>
              <span>Print Annual Ledger</span>
            </button>
          </div>
        </div>

        <!-- Annual Statement Card -->
        <div class="theme-card p-4 space-y-3">
          <div class="border-b pb-3 flex items-center justify-between" style="border-color: var(--card-border);">
            <div>
              <h2 class="text-sm font-black" style="color: var(--text-main);" id="yearlyReportTitle">
                Annual Staff Leave Statement (April 1 to March 31)
              </h2>
              <span class="text-[11px] text-slate-400">Complete Master Quotas, Availed Leaves &amp; Live Remaining Balances</span>
            </div>
            <div class="text-right">
              <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">Official Annual Ledger</span>
            </div>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact text-center">
              <thead>
                <tr>
                  <th class="text-left">#</th>
                  <th class="text-left">Faculty Name</th>
                  <th>Dept</th>
                  <th>Annual CL Quota</th>
                  <th class="text-amber-400">CL Availed</th>
                  <th class="text-emerald-400 font-black">Remaining CL</th>
                  <th class="text-cyan-400">CCL Earned</th>
                  <th class="text-cyan-300">CCL Availed</th>
                  <th class="text-indigo-400">Duty Leaves</th>
                  <th class="text-rose-400">Medical / LOP</th>
                  <th class="text-purple-400 font-black">Total Availed</th>
                </tr>
              </thead>
              <tbody id="yearlyReportTableBody">
                <tr><td colspan="11" class="text-center py-6 text-slate-400">Loading annual leave ledger...</td></tr>
              </tbody>
              <tfoot id="yearlyReportTableFoot" class="font-extrabold border-t-2" style="border-color: var(--card-border); background-color: var(--table-th-bg);">
                <!-- Grand totals populated via JS -->
              </tfoot>
            </table>
          </div>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 8: SF STAFF DIRECTORY & LIVE BALANCES                    -->
      <!-- ============================================================== -->
      <div id="panel-directory" class="hidden space-y-4">
        
        <div class="theme-card p-3.5 flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center">
              <span class="material-symbols-rounded text-lg">groups</span>
            </div>
            <div>
              <h3 class="font-extrabold text-xs" style="color: var(--text-main);">Self-Financing Staff Profiles &amp; Balances</h3>
              <p class="text-[11px] text-slate-400">View live Casual Leave and Compensatory CL balances for each staff member.</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <input type="text" id="staffDirectorySearch" oninput="filterStaffDirectory()" placeholder="Search staff name or mobile..." class="theme-input text-xs w-48 md:w-64">
            <select id="staffDirectoryBranch" onchange="filterStaffDirectory()" class="theme-input text-xs">
              <option value="">All Branches</option>
              <option value="EL">Electronics</option>
              <option value="AU">Automobile</option>
              <option value="CT">Computer</option>
              <option value="GEN_SF">Gen SF</option>
            </select>
          </div>
        </div>

        <div class="theme-card overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="table-compact">
              <thead>
                <tr>
                  <th>Staff Profile</th>
                  <th>Mobile / Login ID</th>
                  <th>Branch</th>
                  <th>Designation</th>
                  <th>Online Status</th>
                  <th>CL Quota</th>
                  <th>CL Taken</th>
                  <th>Remaining CL</th>
                  <th>Active CCL Balance</th>
                  <th>Total Taken</th>
                  <th class="text-right">Action</th>
                </tr>
              </thead>
              <tbody id="staffDirectoryTableBody">
                <tr><td colspan="11" class="text-center py-6 text-slate-400">Loading SF staff directory...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 9: OFFICE RULES & SYSTEM SETTINGS                        -->
      <!-- ============================================================== -->
      <div id="panel-settings" class="hidden space-y-4">
        
        <div class="theme-card p-4 space-y-4 max-w-3xl">
          <div class="border-b pb-3 flex items-center gap-3" style="border-color: var(--card-border);">
            <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
              <span class="material-symbols-rounded text-xl">tune</span>
            </div>
            <div>
              <h3 class="font-extrabold text-sm" style="color: var(--text-main);">Office Leave Policies &amp; Cycle Configuration</h3>
              <p class="text-[11px] text-slate-400 mt-0.5">Configure institutional leave limits, cycle calculation dates, and CCL validity periods.</p>
            </div>
          </div>

          <form id="officeSettingsForm" onsubmit="saveOfficeSettings(event)" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Annual CL Quota -->
              <div class="theme-card p-3 space-y-1">
                <label class="block text-xs font-extrabold" style="color: var(--text-main);">Annual Casual Leave (CL) Quota *</label>
                <span class="text-[10.5px] text-slate-400 block mb-1">Standard number of allowed casual leaves per financial cycle.</span>
                <input type="number" id="setting_annual_cl_quota" min="1" max="50" step="1" required class="theme-input w-full font-mono font-bold text-sm">
                <span class="text-[9.5px] text-slate-500">Default: 15 Days</span>
              </div>

              <!-- CCL Validity Days -->
              <div class="theme-card p-3 space-y-1">
                <label class="block text-xs font-extrabold" style="color: var(--text-main);">CCL Validity Duration (Days) *</label>
                <span class="text-[10.5px] text-slate-400 block mb-1">Number of calendar days a compensatory leave remains valid before expiry.</span>
                <input type="number" id="setting_ccl_validity_days" min="7" max="365" step="1" required class="theme-input w-full font-mono font-bold text-sm">
                <span class="text-[9.5px] text-slate-500">Default: 60 Days (2 Months institutional rule)</span>
              </div>

              <!-- Leave Year Start Date (MM-DD) -->
              <div class="theme-card p-3 space-y-1">
                <label class="block text-xs font-extrabold" style="color: var(--text-main);">Financial Cycle Start (MM-DD) *</label>
                <span class="text-[10.5px] text-slate-400 block mb-1">Month and day when the annual leave cycle resets.</span>
                <input type="text" id="setting_leave_year_start" pattern="^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$" placeholder="04-01" required class="theme-input w-full font-mono text-sm">
                <span class="text-[9.5px] text-slate-500">Format: MM-DD (Default: 04-01 for April 1st)</span>
              </div>

              <!-- Leave Year End Date (MM-DD) -->
              <div class="theme-card p-3 space-y-1">
                <label class="block text-xs font-extrabold" style="color: var(--text-main);">Financial Cycle End (MM-DD) *</label>
                <span class="text-[10.5px] text-slate-400 block mb-1">Month and day when the annual leave cycle closes.</span>
                <input type="text" id="setting_leave_year_end" pattern="^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$" placeholder="03-31" required class="theme-input w-full font-mono text-sm">
                <span class="text-[9.5px] text-slate-500">Format: MM-DD (Default: 03-31 for March 31st)</span>
              </div>
            </div>

            <div class="flex items-center justify-between pt-2">
              <span class="text-[11px] text-slate-400" id="settingsSavedNotice"></span>
              <button type="submit" id="saveSettingsBtn" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">save</span>
                <span>Save Office Rules</span>
              </button>
            </div>
          </form>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 10: SALARY SLIP, ACCOUNTS & ITR DOCS (Future Expansion)  -->
      <!-- ============================================================== -->
      <div id="panel-salary" class="hidden space-y-4">
        
        <div class="theme-card p-6 text-center space-y-4 max-w-2xl mx-auto">
          <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center mx-auto border border-amber-500/20 shadow-sm">
            <span class="material-symbols-rounded text-3xl">receipt_long</span>
          </div>
          <div>
            <h2 class="text-base font-black" style="color: var(--text-main);">Staff Salary Slips &amp; ITR Documentation Centre</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40 mt-2 inline-block">
              Scheduled For Deployment (Phase 2)
            </span>
          </div>
          <p class="text-xs text-slate-400 leading-relaxed max-w-lg mx-auto">
            This module will provide self-financing faculty with digital pay slips, annual provident fund (PF) statements, Form 16 generators, and Income Tax Return (ITR) computation archives directly from the SF Office console.
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 text-left">
            <div class="theme-card p-3 border-dashed">
              <span class="material-symbols-rounded text-blue-400 text-lg">payments</span>
              <h4 class="font-bold text-xs mt-1" style="color: var(--text-main);">Monthly Salary Slips</h4>
              <p class="text-[10px] text-slate-500 mt-0.5">Automated salary credit statements &amp; deductions.</p>
            </div>
            <div class="theme-card p-3 border-dashed">
              <span class="material-symbols-rounded text-emerald-400 text-lg">account_balance</span>
              <h4 class="font-bold text-xs mt-1" style="color: var(--text-main);">Account &amp; Bank Logs</h4>
              <p class="text-[10px] text-slate-500 mt-0.5">Bank disbursement records and account verification.</p>
            </div>
            <div class="theme-card p-3 border-dashed">
              <span class="material-symbols-rounded text-purple-400 text-lg">description</span>
              <h4 class="font-bold text-xs mt-1" style="color: var(--text-main);">ITR &amp; Form 16</h4>
              <p class="text-[10px] text-slate-500 mt-0.5">Annual tax worksheets and downloadable Form 16.</p>
            </div>
          </div>
        </div>

      </div>

      <!-- ============================================================== -->
      <!-- PANEL 11: MY PROFILE & PASSWORD CHANGE                         -->
      <!-- ============================================================== -->
      <div id="panel-security" class="hidden space-y-4">
        
        <div class="theme-card p-5 max-w-2xl mx-auto space-y-5">
          <div class="border-b pb-3 flex items-center gap-3" style="border-color: var(--card-border);">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
              <span class="material-symbols-rounded text-2xl">manage_accounts</span>
            </div>
            <div>
              <h3 class="font-extrabold text-sm" style="color: var(--text-main);">SF Office Profile &amp; Password Settings</h3>
              <p class="text-[11px] text-slate-400 mt-0.5">Update login password for user <strong>9000000009</strong>.</p>
            </div>
          </div>

          <!-- Account Identity Card -->
          <div class="theme-card p-3.5 bg-slate-800/30 flex items-center gap-3">
            <div class="relative shrink-0">
              <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150" alt="Avatar" class="w-12 h-12 rounded-full border border-slate-700 object-cover shadow-sm">
              <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 rounded-full shadow-sm ring-1 ring-emerald-400/40"
                    style="border-color: var(--card-bg);" title="Online"></span>
            </div>
            <div>
              <span class="font-black text-sm block" style="color: var(--text-main);">SF Office Desk</span>
              <span class="text-xs font-mono-code text-cyan-400 font-bold block">9000000009</span>
              <span class="text-[10px] text-slate-400 block">Designation: SF_Office • Department: GEN_SF</span>
            </div>
          </div>

          <!-- Change Password Form -->
          <form id="changePasswordForm" onsubmit="submitChangePassword(event)" class="space-y-3">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Current Password *</label>
              <input type="password" id="pw_old" required placeholder="Enter current password (default: admin123)" class="theme-input w-full text-xs">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">New Password *</label>
              <input type="password" id="pw_new" minlength="4" required placeholder="Enter new password" class="theme-input w-full text-xs">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Confirm New Password *</label>
              <input type="password" id="pw_confirm" minlength="4" required placeholder="Re-enter new password" class="theme-input w-full text-xs">
            </div>

            <div class="pt-2">
              <button type="submit" id="pwSubmitBtn" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">key</span>
                <span>Update Password</span>
              </button>
            </div>
          </form>
        </div>

      </div>

    </main>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL: LEAVE APPROVAL ACTION (APPROVE / REJECT)                -->
  <!-- ============================================================== -->
  <div id="leaveActionModal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="theme-card max-w-md w-full p-5 space-y-4 shadow-2xl">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--card-border);">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-lg text-amber-400" id="modalActionIcon">fact_check</span>
          <h3 class="font-extrabold text-sm" id="modalActionTitle" style="color: var(--text-main);">Process Leave Review</h3>
        </div>
        <button onclick="closeLeaveActionModal()" class="text-slate-400 hover:text-white">
          <span class="material-symbols-rounded text-base">close</span>
        </button>
      </div>

      <div class="space-y-2 text-xs">
        <div class="p-2.5 rounded-lg bg-slate-800/40 border border-slate-700/50 space-y-1">
          <div class="flex justify-between text-slate-400 text-[11px]">
            <span>Staff Member:</span>
            <strong class="text-slate-200" id="modalStaffName">--</strong>
          </div>
          <div class="flex justify-between text-slate-400 text-[11px]">
            <span>Leave Category:</span>
            <strong class="text-cyan-400" id="modalLeaveType">--</strong>
          </div>
          <div class="flex justify-between text-slate-400 text-[11px]">
            <span>Duration:</span>
            <strong class="text-emerald-400" id="modalLeaveDuration">--</strong>
          </div>
        </div>

        <div>
          <label class="block text-[10.5px] font-bold text-slate-400 uppercase tracking-wider mb-1">Office Remarks / Verification Notes</label>
          <textarea id="modalOfficeRemarks" rows="3" placeholder="Enter remarks (e.g. Leave quota verified. Forwarded to SF Coordinator.)" class="theme-input w-full text-xs"></textarea>
        </div>
      </div>

      <input type="hidden" id="modalLeaveId">
      <input type="hidden" id="modalDecisionAction">

      <div class="flex items-center justify-end gap-2 pt-2 border-t" style="border-color: var(--card-border);">
        <button onclick="closeLeaveActionModal()" class="px-3 py-1.5 rounded-lg border text-xs font-bold text-slate-400 hover:text-white cursor-pointer" style="border-color: var(--card-border);">Cancel</button>
        <button id="modalConfirmBtn" onclick="submitOfficeApprovalAction()" class="px-4 py-1.5 rounded-lg font-bold text-xs text-white transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
          Confirm Action
        </button>
      </div>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- MODAL: MANUAL CCL CREDIT FORM                                  -->
  <!-- ============================================================== -->
  <div id="manualCclModal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="theme-card max-w-md w-full p-5 space-y-4 shadow-2xl">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--card-border);">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-lg text-emerald-400">add_task</span>
          <h3 class="font-extrabold text-sm" style="color: var(--text-main);">Credit Compensatory CL (CCL)</h3>
        </div>
        <button onclick="closeManualCclModal()" class="text-slate-400 hover:text-white">
          <span class="material-symbols-rounded text-base">close</span>
        </button>
      </div>

      <form id="manualCclForm" onsubmit="submitManualCclCredit(event)" class="space-y-3 text-xs">
        <div>
          <label class="block text-[10.5px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select SF Staff Member *</label>
          <select id="mccl_staff_mobile" required class="theme-input w-full text-xs">
            <option value="">-- Choose SF Staff --</option>
          </select>
        </div>

        <div>
          <label class="block text-[10.5px] font-bold text-slate-400 uppercase tracking-wider mb-1">Duty Date (Holiday / Saturday) *</label>
          <input type="date" id="mccl_duty_date" required class="theme-input w-full text-xs">
        </div>

        <div>
          <label class="block text-[10.5px] font-bold text-slate-400 uppercase tracking-wider mb-1">Duty Session *</label>
          <select id="mccl_session_type" required class="theme-input w-full text-xs">
            <option value="Full Day">Full Day Duty (&ge; 5.5h) &rarr; 1.0 CCL</option>
            <option value="Half Day">Half Day Duty &rarr; 0.5 CCL</option>
          </select>
        </div>

        <div>
          <label class="block text-[10.5px] font-bold text-slate-400 uppercase tracking-wider mb-1">Office Remarks / Reason</label>
          <input type="text" id="mccl_remarks" placeholder="e.g. Conducted Weekend Lab Session / SBTE Exam Duty" class="theme-input w-full text-xs">
        </div>

        <div class="p-2.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-[10.5px] text-emerald-300">
          <span class="material-symbols-rounded text-xs align-middle">info</span>
          Credit will be valid for <strong>60 days (2 months)</strong> from the duty date.
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t" style="border-color: var(--card-border);">
          <button type="button" onclick="closeManualCclModal()" class="px-3 py-1.5 rounded-lg border text-xs font-bold text-slate-400 hover:text-white cursor-pointer" style="border-color: var(--card-border);">Cancel</button>
          <button type="submit" id="mcclSubmitBtn" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-premium shadow-sm flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-sm">check_circle</span>
            <span>Credit CCL</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- JAVASCRIPT LOGIC & ASYNC CONTROLLERS                           -->
  <!-- ============================================================== -->
  <script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentPanel = 'overview';
    let allSfStaff = [];

    // Theme Management (Dark / Light)
    function initTheme() {
      const savedTheme = localStorage.getItem('cl_theme') || 'dark';
      document.documentElement.setAttribute('data-theme', savedTheme);
      updateThemeUI(savedTheme);
    }

    function toggleTheme() {
      const current = document.documentElement.getAttribute('data-theme') || 'dark';
      const newTheme = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('cl_theme', newTheme);
      updateThemeUI(newTheme);
    }

    function updateThemeUI(theme) {
      const icon = document.getElementById('themeIcon');
      const label = document.getElementById('themeLabel');
      if (theme === 'light') {
        icon.innerText = 'dark_mode';
        label.innerText = 'Dark';
      } else {
        icon.innerText = 'light_mode';
        label.innerText = 'Light';
      }
    }

    // Toast Notification helper
    function showToast(message, type = 'success') {
      const banner = document.getElementById('globalToast');
      const msgElem = document.getElementById('toastMessage');
      const iconElem = document.getElementById('toastIcon');

      msgElem.innerText = message;
      banner.className = 'p-3 rounded-xl font-bold text-xs transition-premium shadow-md flex items-center justify-between mb-4 ' +
        (type === 'success' ? 'bg-emerald-950/90 text-emerald-200 border border-emerald-500/50' :
         type === 'error' ? 'bg-rose-950/90 text-rose-200 border border-rose-500/50' :
         'bg-blue-950/90 text-blue-200 border border-blue-500/50');

      iconElem.innerText = (type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info');
      banner.classList.remove('hidden');

      setTimeout(() => {
        banner.classList.add('hidden');
      }, 5000);
    }

    function hideToast() {
      document.getElementById('globalToast').classList.add('hidden');
    }

    // Leave Sub-Tab Switcher (Inside Leave Management Panel)
    let currentLeaveSubTab = 'queue';

    function switchLeaveSubTab(subTab) {
      currentLeaveSubTab = subTab;
      const subTabs = ['queue', 'backfill', 'ccl'];

      subTabs.forEach(tab => {
        const view = document.getElementById(`leaveSubView-${tab}`);
        const btn = document.getElementById(`leaveSubTabBtn-${tab}`);
        if (view) {
          if (tab === subTab) {
            view.classList.remove('hidden');
          } else {
            view.classList.add('hidden');
          }
        }
        if (btn) {
          if (tab === subTab) {
            btn.className = 'px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition-premium bg-blue-600 text-white shadow-sm cursor-pointer';
          } else {
            btn.className = 'px-3.5 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition-premium text-slate-400 hover:text-white hover:bg-slate-800/50 cursor-pointer';
          }
        }
      });

      // Lazy load subtab data
      if (subTab === 'queue') {
        loadLeaveRequests();
      } else if (subTab === 'backfill') {
        loadBackfilledRecords();
      } else if (subTab === 'ccl') {
        loadCclEligiblePunches();
        loadCclLedger();
      }
    }

    // Panel Switcher
    function switchPanel(panelId) {
      // Special-case redirects to Leave Management subtabs
      if (panelId === 'backfill') {
        switchPanel('approvals');
        switchLeaveSubTab('backfill');
        return;
      }
      if (panelId === 'ccl') {
        switchPanel('approvals');
        switchLeaveSubTab('ccl');
        return;
      }

      currentPanel = panelId;
      const panels = ['overview', 'approvals', 'punches', 'monthly', 'yearly', 'directory', 'settings', 'salary', 'security'];
      
      panels.forEach(p => {
        const el = document.getElementById(`panel-${p}`);
        const btn = document.getElementById(`navBtn-${p}`);
        if (el) {
          if (p === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
        if (btn) {
          if (p === panelId) {
            btn.className = 'nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-blue-400 bg-blue-500/10 border-l-2 border-blue-500 cursor-pointer';
          } else {
            btn.className = 'nav-item w-full text-left px-3.5 py-2.5 rounded-xl font-bold text-[13.5px] flex items-center justify-between transition-premium text-slate-400 hover:text-white hover:bg-slate-800/40 cursor-pointer';
          }
        }
      });

      // Lazy load data for selected panel
      if (panelId === 'overview') loadOverviewData();
      else if (panelId === 'approvals') switchLeaveSubTab(currentLeaveSubTab);
      else if (panelId === 'punches') loadPunchLogs();
      else if (panelId === 'monthly') loadMonthlyReport();
      else if (panelId === 'yearly') loadYearlyReport();
      else if (panelId === 'directory') renderStaffDirectory(allSfStaff);
      else if (panelId === 'settings') loadOfficeSettings();
    }

    function refreshCurrentTab() {
      switchPanel(currentPanel);
    }

    function toggleFullscreen() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => console.log(err));
      } else {
        document.exitFullscreen().catch(err => console.log(err));
      }
    }

    // ============================================================
    // API CALLS: OVERVIEW & STATS
    // ============================================================
    async function loadOverviewData() {
      showSync(true);
      try {
        const res = await fetch('/api/sf-office/stats');
        const json = await res.json();
        if (json.status === 'SUCCESS') {
          const d = json.data;
          document.getElementById('statStaffTotal').innerText = d.total_staff || 31;
          document.getElementById('statPunchedToday').innerText = d.today_punches || 0;
          document.getElementById('statOnLeaveToday').innerText = d.on_leave_today || 0;
          document.getElementById('statPendingOffice').innerText = d.pending_office || 0;
          document.getElementById('statActiveCcl').innerText = (d.active_ccl_pool || 0) + 'd';
          document.getElementById('statBackfillsCount').innerText = d.past_backfills_count || 0;

          const cycleLabelEl = document.getElementById('topBarCycleLabel');
          if (cycleLabelEl && d.cycle && d.cycle.label) {
            cycleLabelEl.innerText = d.cycle.label;
          }

          const badge = document.getElementById('badgePendingLeaves');
          const subBadge = document.getElementById('subtabPendingBadge');
          if (d.pending_office > 0) {
            badge.innerText = d.pending_office;
            badge.classList.remove('hidden');
            if (subBadge) {
              subBadge.innerText = d.pending_office;
              subBadge.classList.remove('hidden');
            }
          } else {
            badge.classList.add('hidden');
            if (subBadge) subBadge.classList.add('hidden');
          }
        }
      } catch (e) {
        console.error('Failed to load stats', e);
      }

      // Load Mini Feeds
      loadOverviewRecentPunches();
      loadOverviewUrgentPending();
      showSync(false);
    }

    async function loadOverviewRecentPunches() {
      try {
        const today = new Date().toISOString().split('T')[0];
        const res = await fetch(`/api/sf-office/punches?date_from=${today}&date_to=${today}`);
        const json = await res.json();
        const tbody = document.getElementById('overviewPunchesTableBody');
        if (json.status === 'SUCCESS' && json.logs.length > 0) {
          tbody.innerHTML = json.logs.slice(0, 6).map(p => `
            <tr>
              <td class="font-bold text-slate-200">${p.staff_name}</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${p.branch}</span></td>
              <td class="font-mono-code text-emerald-400 font-bold">${p.in_time}</td>
              <td class="font-mono-code text-cyan-400">${p.out_time}</td>
              <td class="font-bold">${p.duration}</td>
              <td class="text-slate-400">${p.distance_in}</td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-slate-500">No punches recorded yet today.</td></tr>`;
        }
      } catch (e) {
        console.error(e);
      }
    }

    async function loadOverviewUrgentPending() {
      try {
        const res = await fetch('/api/sf-office/leaves?status=pending_office');
        const json = await res.json();
        const tbody = document.getElementById('overviewPendingTableBody');
        if (json.status === 'SUCCESS' && json.leaves.length > 0) {
          tbody.innerHTML = json.leaves.slice(0, 5).map(l => `
            <tr>
              <td class="font-bold text-slate-200">${l.staff_name}</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-blue-500/15 text-blue-300">${l.leave_type}</span></td>
              <td class="font-mono-code text-[11px]">${l.from_date}</td>
              <td class="font-bold text-amber-400">${l.total_days}d</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-500/20 text-amber-300">Pending</span></td>
              <td class="text-right">
                <button onclick="openLeaveActionModal(${l.id}, '${l.staff_name}', '${l.leave_type}', '${l.from_date} (${l.total_days}d)', 'Approved')" class="px-2 py-0.5 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px]">
                  Review
                </button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-emerald-500 font-semibold">✓ No leave applications pending in office queue!</td></tr>`;
        }
      } catch (e) {
        console.error(e);
      }
    }

    // ============================================================
    // API CALLS: LEAVE APPLICATIONS & 3-TIER APPROVALS
    // ============================================================
    async function loadLeaveRequests() {
      showSync(true);
      const status = document.getElementById('leaveFilterStatus').value;
      const dept = document.getElementById('leaveFilterDept').value;
      
      try {
        const res = await fetch(`/api/sf-office/leaves?status=${status}&department=${dept}`);
        const json = await res.json();
        const tbody = document.getElementById('leaveRequestsTableBody');
        const badge = document.getElementById('leaveQueueCountBadge');

        if (json.status === 'SUCCESS') {
          badge.innerText = json.leaves.length;
          if (json.leaves.length === 0) {
            tbody.innerHTML = `<tr><td colspan="12" class="text-center py-6 text-slate-400">No leave requests found matching filters.</td></tr>`;
            showSync(false);
            return;
          }

          tbody.innerHTML = json.leaves.map(l => {
            const isPendingOffice = (l.office_status === 'Pending');
            return `
              <tr>
                <td>
                  <span class="font-mono-code text-[10.5px] text-slate-300 font-bold block">${l.leave_code || '--'}</span>
                  <span class="text-[9.5px] text-slate-500 block">${l.submitted_at || ''}</span>
                </td>
                <td>
                  <span class="font-bold text-slate-200 block">${l.staff_name}</span>
                  <span class="text-[9.5px] text-slate-400 font-mono-code">${l.staff_mobile}</span>
                </td>
                <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${l.department}</span></td>
                <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-blue-500/15 text-blue-300">${l.leave_type}</span></td>
                <td>
                  <span class="font-mono-code text-[10.5px] text-slate-300 block">${l.from_date} &rarr; ${l.to_date}</span>
                  <span class="text-[9.5px] text-slate-400">${l.session_type}</span>
                </td>
                <td class="font-bold text-amber-400">${l.total_days}d</td>
                <td class="max-w-xs truncate" title="${l.reason || ''}">${l.reason || '--'}</td>
                
                <!-- Stage 1: Office -->
                <td>
                  <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${
                    l.office_status === 'Approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                    l.office_status === 'Rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' :
                    'bg-amber-500/20 text-amber-300 border border-amber-500/30'
                  }">${l.office_status}</span>
                </td>

                <!-- Stage 2: Coordinator -->
                <td>
                  <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${
                    l.coordinator_status === 'Approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                    l.coordinator_status === 'Rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' :
                    'bg-slate-800 text-slate-400'
                  }">${l.coordinator_status}</span>
                </td>

                <!-- Stage 3: Principal -->
                <td>
                  <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${
                    l.principal_status === 'Approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                    l.principal_status === 'Rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' :
                    'bg-slate-800 text-slate-400'
                  }">${l.principal_status}</span>
                </td>

                <!-- Overall -->
                <td>
                  <span class="px-1.5 py-0.5 rounded text-[9.5px] font-black ${
                    l.overall_status === 'Approved' ? 'bg-emerald-600/30 text-emerald-300' :
                    l.overall_status === 'Rejected' ? 'bg-rose-600/30 text-rose-300' :
                    'bg-amber-600/30 text-amber-300'
                  }">${l.overall_status}</span>
                </td>

                <td class="text-right">
                  ${isPendingOffice ? `
                    <div class="flex items-center justify-end gap-1">
                      <button onclick="openLeaveActionModal(${l.id}, '${l.staff_name}', '${l.leave_type}', '${l.from_date} (${l.total_days}d)', 'Approved')" class="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] cursor-pointer" title="Approve & Forward to Coordinator">
                        Approve
                      </button>
                      <button onclick="openLeaveActionModal(${l.id}, '${l.staff_name}', '${l.leave_type}', '${l.from_date} (${l.total_days}d)', 'Rejected')" class="px-2 py-1 rounded bg-rose-600 hover:bg-rose-500 text-white font-bold text-[10px] cursor-pointer" title="Reject Application">
                        Reject
                      </button>
                    </div>
                  ` : `
                    <span class="text-slate-500 text-[10px] font-semibold">Processed</span>
                  `}
                </td>
              </tr>
            `;
          }).join('');
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    function openLeaveActionModal(id, staffName, leaveType, duration, action) {
      document.getElementById('modalLeaveId').value = id;
      document.getElementById('modalDecisionAction').value = action;
      document.getElementById('modalStaffName').innerText = staffName;
      document.getElementById('modalLeaveType').innerText = leaveType;
      document.getElementById('modalLeaveDuration').innerText = duration;

      const title = document.getElementById('modalActionTitle');
      const icon = document.getElementById('modalActionIcon');
      const confirmBtn = document.getElementById('modalConfirmBtn');
      const remarks = document.getElementById('modalOfficeRemarks');

      if (action === 'Approved') {
        title.innerText = 'Approve & Forward to SF Academic Coordinator';
        title.className = 'font-extrabold text-sm text-emerald-400';
        icon.innerText = 'verified';
        icon.className = 'material-symbols-rounded text-lg text-emerald-400';
        confirmBtn.className = 'px-4 py-1.5 rounded-lg font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-500 transition-premium shadow-sm cursor-pointer';
        confirmBtn.innerText = 'Approve & Forward (Tier 1)';
        remarks.value = 'Leave quota verified. Forwarded to SF Academic Coordinator.';
      } else {
        title.innerText = 'Reject Leave Application';
        title.className = 'font-extrabold text-sm text-rose-400';
        icon.innerText = 'cancel';
        icon.className = 'material-symbols-rounded text-lg text-rose-400';
        confirmBtn.className = 'px-4 py-1.5 rounded-lg font-bold text-xs text-white bg-rose-600 hover:bg-rose-500 transition-premium shadow-sm cursor-pointer';
        confirmBtn.innerText = 'Reject Application';
        remarks.value = 'Rejected by SF Office due to quota/scheduling constraints.';
      }

      document.getElementById('leaveActionModal').classList.remove('hidden');
    }

    function closeLeaveActionModal() {
      document.getElementById('leaveActionModal').classList.add('hidden');
    }

    async function submitOfficeApprovalAction() {
      const id = document.getElementById('modalLeaveId').value;
      const action = document.getElementById('modalDecisionAction').value;
      const remarks = document.getElementById('modalOfficeRemarks').value;
      const btn = document.getElementById('modalConfirmBtn');

      btn.disabled = true;
      btn.innerText = 'Processing...';

      try {
        const res = await fetch('/api/sf-office/leaves/approve', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify({
            leave_id: id,
            action: action,
            remarks: remarks
          })
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          closeLeaveActionModal();
          loadLeaveRequests();
          loadOverviewData();
        } else {
          showToast(json.message || 'Operation failed', 'error');
        }
      } catch (e) {
        showToast('Server communication error', 'error');
      }
      btn.disabled = false;
    }

    // ============================================================
    // API CALLS: PAST LEAVES BACKFILL (Mid-Year Implementation)
    // ============================================================
    function autoCalculateBackfillDays() {
      const from = document.getElementById('bf_from_date').value;
      const to = document.getElementById('bf_to_date').value;
      const session = document.getElementById('bf_session_type').value;

      if (!from || !to) return;
      if (session === 'FN' || session === 'AN') {
        document.getElementById('bf_total_days').value = 0.5;
        return;
      }

      const d1 = new Date(from);
      const d2 = new Date(to);
      if (d2 >= d1) {
        const diffDays = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('bf_total_days').value = diffDays;
      }
    }

    async function submitPastLeaveEntry(e) {
      e.preventDefault();
      const btn = document.getElementById('bfSubmitBtn');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      const payload = {
        staff_mobile: document.getElementById('bf_staff_mobile').value,
        leave_type: document.getElementById('bf_leave_type').value,
        session_type: document.getElementById('bf_session_type').value,
        from_date: document.getElementById('bf_from_date').value,
        to_date: document.getElementById('bf_to_date').value,
        total_days: document.getElementById('bf_total_days').value,
        reason: document.getElementById('bf_reason').value,
      };

      try {
        const res = await fetch('/api/sf-office/past-leaves', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          document.getElementById('bf_reason').value = '';
          loadBackfilledRecords();
          loadOverviewData();
        } else {
          showToast(json.message || 'Failed to save record', 'error');
        }
      } catch (err) {
        showToast('Error saving past leave entry', 'error');
      }

      btn.disabled = false;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span><span>Save to Ledger</span>';
    }

    async function loadBackfilledRecords() {
      showSync(true);
      try {
        const res = await fetch('/api/sf-office/leaves?is_historical=1');
        const json = await res.json();
        const tbody = document.getElementById('backfillTableBody');
        const badge = document.getElementById('backfillCountBadge');

        if (json.status === 'SUCCESS') {
          badge.innerText = json.leaves.length;
          if (json.leaves.length === 0) {
            tbody.innerHTML = `<tr><td colspan="11" class="text-center py-6 text-slate-400">No historical backfill records logged yet this cycle.</td></tr>`;
            showSync(false);
            return;
          }

          tbody.innerHTML = json.leaves.map(l => `
            <tr>
              <td class="font-mono-code text-[11px] text-indigo-400 font-bold">${l.leave_code}</td>
              <td class="font-bold text-slate-200">${l.staff_name}</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${l.department}</span></td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-indigo-500/15 text-indigo-300">${l.leave_type}</span></td>
              <td class="font-mono-code">${l.from_date}</td>
              <td class="font-mono-code">${l.to_date}</td>
              <td class="font-bold text-amber-400">${l.total_days}d</td>
              <td>${l.session_type}</td>
              <td class="max-w-xs truncate" title="${l.reason}">${l.reason}</td>
              <td class="text-slate-400 text-[10px]">${l.submitted_at}</td>
              <td class="text-right">
                <button onclick="deletePastLeaveRecord(${l.id})" class="p-1 rounded text-rose-400 hover:text-white hover:bg-rose-900/50 cursor-pointer" title="Delete Entry">
                  <span class="material-symbols-rounded text-sm">delete</span>
                </button>
              </td>
            </tr>
          `).join('');
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    async function deletePastLeaveRecord(id) {
      if (!confirm('Are you sure you want to delete this historical backfilled leave entry? This will adjust the staff leave balance immediately.')) {
        return;
      }

      try {
        const res = await fetch(`/api/sf-office/past-leaves/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
          }
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          loadBackfilledRecords();
          loadOverviewData();
        } else {
          showToast(json.message || 'Delete failed', 'error');
        }
      } catch (e) {
        showToast('Error deleting record', 'error');
      }
    }

    // ============================================================
    // API CALLS: CCL HOLIDAY CREDIT DESK
    // ============================================================
    async function loadCclEligiblePunches() {
      showSync(true);
      try {
        const res = await fetch('/api/sf-office/punches?holiday_only=1');
        const json = await res.json();
        const tbody = document.getElementById('cclEligiblePunchesTableBody');

        if (json.status === 'SUCCESS') {
          if (json.logs.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-6 text-slate-400">No holiday/Saturday punches detected.</td></tr>`;
            showSync(false);
            return;
          }

          tbody.innerHTML = json.logs.map(p => `
            <tr>
              <td class="font-mono-code font-bold text-slate-200">${p.punch_date}</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-black bg-amber-500/15 text-amber-300">${p.day_name}</span></td>
              <td class="font-bold text-slate-200">${p.staff_name}</td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${p.branch}</span></td>
              <td class="font-mono-code text-emerald-400">${p.in_time}</td>
              <td class="font-mono-code text-cyan-400">${p.out_time}</td>
              <td class="font-bold">${p.duration}</td>
              <td class="text-slate-400">${p.distance_in}</td>
              <td class="font-bold text-emerald-400">${p.ccl_days} Day(s)</td>
              <td class="text-right">
                ${p.ccl_credited ? `
                  <span class="px-2 py-0.5 rounded text-[9.5px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">✓ Credited</span>
                ` : `
                  <button onclick="creditCclFromPunch('${p.staff_id}', '${p.punch_date}', '${p.ccl_days >= 1.0 ? 'Full Day' : 'Half Day'}', ${p.id})" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] cursor-pointer">
                    Credit ${p.ccl_days} CCL
                  </button>
                `}
              </td>
            </tr>
          `).join('');
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    async function creditCclFromPunch(staffMobile, date, sessionType, punchId) {
      try {
        const res = await fetch('/api/sf-office/ccl/credit', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify({
            staff_mobile: staffMobile,
            duty_date: date,
            session_type: sessionType,
            source_punch_id: punchId,
            remarks: `Mobile face punch on ${date} (${sessionType})`
          })
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          loadCclEligiblePunches();
          loadCclLedger();
          loadOverviewData();
        } else {
          showToast(json.message || 'Credit failed', 'error');
        }
      } catch (e) {
        showToast('Error crediting CCL', 'error');
      }
    }

    async function loadCclLedger() {
      const status = document.getElementById('cclLedgerStatusFilter').value;
      try {
        const res = await fetch(`/api/sf-office/ccl/ledger?status=${status}`);
        const json = await res.json();
        const tbody = document.getElementById('cclLedgerTableBody');

        if (json.status === 'SUCCESS') {
          if (json.credits.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center py-6 text-slate-400">No CCL credit records in ledger.</td></tr>`;
            return;
          }

          tbody.innerHTML = json.credits.map(c => `
            <tr>
              <td class="font-bold text-slate-200">${c.staff_name}</td>
              <td class="font-mono-code">${c.duty_date}</td>
              <td>${c.session_type}</td>
              <td class="font-bold text-cyan-400">${c.earned_days}d</td>
              <td class="font-bold text-slate-400">${c.used_days}d</td>
              <td class="font-bold text-emerald-400 font-mono-code">${c.balance}d</td>
              <td class="font-mono-code text-[11px] text-amber-400">${c.valid_until}</td>
              <td>
                <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${
                  c.status === 'Active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                  c.status === 'Expired' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' :
                  'bg-slate-800 text-slate-400'
                }">${c.status}</span>
              </td>
              <td class="text-slate-400 text-[10px]">${c.credited_by || 'Office'}</td>
            </tr>
          `).join('');
        }
      } catch (e) {
        console.error(e);
      }
    }

    function openManualCclModal() {
      document.getElementById('manualCclModal').classList.remove('hidden');
    }
    function closeManualCclModal() {
      document.getElementById('manualCclModal').classList.add('hidden');
    }

    async function submitManualCclCredit(e) {
      e.preventDefault();
      const btn = document.getElementById('mcclSubmitBtn');
      btn.disabled = true;

      const payload = {
        staff_mobile: document.getElementById('mccl_staff_mobile').value,
        duty_date: document.getElementById('mccl_duty_date').value,
        session_type: document.getElementById('mccl_session_type').value,
        remarks: document.getElementById('mccl_remarks').value,
      };

      try {
        const res = await fetch('/api/sf-office/ccl/credit', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          closeManualCclModal();
          loadCclLedger();
          loadOverviewData();
        } else {
          showToast(json.message || 'Credit failed', 'error');
        }
      } catch (err) {
        showToast('Error submitting CCL credit', 'error');
      }
      btn.disabled = false;
    }

    // ============================================================
    // API CALLS: MOBILE TIME PUNCH LOGS (Privacy: NO Images)
    // ============================================================
    function setPunchQuickDate(range) {
      const today = new Date();
      const fmt = d => d.toISOString().split('T')[0];

      if (range === 'today') {
        document.getElementById('punchDateFrom').value = fmt(today);
        document.getElementById('punchDateTo').value = fmt(today);
      } else if (range === '7days') {
        const past = new Date();
        past.setDate(past.getDate() - 7);
        document.getElementById('punchDateFrom').value = fmt(past);
        document.getElementById('punchDateTo').value = fmt(today);
      } else if (range === '30days') {
        const past = new Date();
        past.setDate(past.getDate() - 30);
        document.getElementById('punchDateFrom').value = fmt(past);
        document.getElementById('punchDateTo').value = fmt(today);
      }
      loadPunchLogs();
    }

    async function loadPunchLogs() {
      showSync(true);
      const from = document.getElementById('punchDateFrom').value;
      const to = document.getElementById('punchDateTo').value;
      const branch = document.getElementById('punchBranchFilter').value;
      const holidayOnly = document.getElementById('punchHolidayOnly').checked ? 1 : 0;

      try {
        const res = await fetch(`/api/sf-office/punches?date_from=${from}&date_to=${to}&branch=${branch}&holiday_only=${holidayOnly}`);
        const json = await res.json();
        const tbody = document.getElementById('punchLogsTableBody');
        const badge = document.getElementById('punchLogsCountBadge');

        if (json.status === 'SUCCESS') {
          badge.innerText = json.total;
          if (json.logs.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-6 text-slate-400">No punch records matching criteria.</td></tr>`;
            showSync(false);
            return;
          }

          tbody.innerHTML = json.logs.map(p => `
            <tr>
              <td>
                <span class="font-mono-code font-bold text-slate-200 block">${p.punch_date}</span>
                <span class="text-[9.5px] text-slate-500">${p.day_name}</span>
              </td>
              <td>
                <span class="font-bold text-slate-200 block">${p.staff_name}</span>
                <span class="text-[9.5px] text-slate-400 font-mono-code">${p.staff_id}</span>
              </td>
              <td>
                <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300 block w-max">${p.branch}</span>
                <span class="text-[9.5px] text-slate-500">${p.designation}</span>
              </td>
              <td class="font-mono-code text-emerald-400 font-bold">${p.in_time}</td>
              <td class="font-mono-code text-cyan-400 font-bold">${p.out_time}</td>
              <td class="font-bold">${p.duration}</td>
              <td class="text-slate-300">
                <span>${p.distance_in}</span>
                <span class="text-[9px] text-slate-500 block">${p.premises_status}</span>
              </td>
              <td class="text-slate-300">
                <span>${p.distance_out}</span>
              </td>
              <td>
                <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${p.is_holiday ? 'bg-amber-500/20 text-amber-300' : 'bg-slate-800 text-slate-400'}">
                  ${p.is_holiday ? 'Weekend/Holiday' : 'Weekday'}
                </span>
              </td>
              <td>
                <span class="px-1.5 py-0.5 rounded text-[9.5px] font-black bg-emerald-500/20 text-emerald-300">
                  ${p.punch_status}
                </span>
              </td>
            </tr>
          `).join('');
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    // ============================================================
    // API CALLS: MONTHLY LEAVE REPORT
    // ============================================================
    async function loadMonthlyReport() {
      showSync(true);
      const m = document.getElementById('monthlyReportMonth').value;
      const y = document.getElementById('monthlyReportYear').value;
      const dept = document.getElementById('monthlyReportDept').value;

      try {
        const res = await fetch(`/api/sf-office/reports/monthly?month=${m}&year=${y}&department=${dept}`);
        const json = await res.json();
        const tbody = document.getElementById('monthlyReportTableBody');
        const tfoot = document.getElementById('monthlyReportTableFoot');
        const title = document.getElementById('monthlyReportTitle');
        const stamp = document.getElementById('monthlyGeneratedTimestamp');

        if (json.status === 'SUCCESS') {
          title.innerText = `Monthly Staff Leave Matrix - ${json.month_name}`;
          stamp.innerText = new Date().toLocaleString();

          if (json.rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="12" class="text-center py-6 text-slate-400">No faculty records found.</td></tr>`;
            tfoot.innerHTML = '';
            showSync(false);
            return;
          }

          tbody.innerHTML = json.rows.map((r, i) => `
            <tr>
              <td class="text-left font-mono-code text-slate-500">${i + 1}</td>
              <td class="text-left font-bold text-slate-200">
                <span>${r.name}</span>
                <span class="text-[9.5px] text-slate-400 font-mono-code block">${r.mobile_no}</span>
              </td>
              <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${r.branch}</span></td>
              <td class="text-slate-400 text-[10px]">${r.designation}</td>
              <td class="font-bold text-cyan-400">${r.breakdown.CL > 0 ? r.breakdown.CL : '-'}</td>
              <td class="font-bold text-emerald-400">${r.breakdown.CCL > 0 ? r.breakdown.CCL : '-'}</td>
              <td class="font-bold text-indigo-400">${r.breakdown.DL > 0 ? r.breakdown.DL : '-'}</td>
              <td class="font-bold text-blue-400">${r.breakdown.ODL > 0 ? r.breakdown.ODL : '-'}</td>
              <td class="font-bold text-amber-400">${r.breakdown.ML > 0 ? r.breakdown.ML : '-'}</td>
              <td class="font-bold text-rose-400">${r.breakdown.LOP > 0 ? r.breakdown.LOP : '-'}</td>
              <td class="text-slate-400">${r.breakdown.OTHERS > 0 ? r.breakdown.OTHERS : '-'}</td>
              <td class="font-black text-purple-400 text-xs">${r.breakdown.TOTAL > 0 ? r.breakdown.TOTAL : '0'}</td>
            </tr>
          `).join('');

          // Footer Grand Totals
          const gt = json.grand_totals;
          tfoot.innerHTML = `
            <tr>
              <td colspan="4" class="text-left font-black uppercase text-slate-300">Total SF Staff Days</td>
              <td class="font-black text-cyan-400">${gt.CL}</td>
              <td class="font-black text-emerald-400">${gt.CCL}</td>
              <td class="font-black text-indigo-400">${gt.DL}</td>
              <td class="font-black text-blue-400">${gt.ODL}</td>
              <td class="font-black text-amber-400">${gt.ML}</td>
              <td class="font-black text-rose-400">${gt.LOP}</td>
              <td class="font-black text-slate-400">${gt.OTHERS}</td>
              <td class="font-black text-purple-300 text-sm">${gt.TOTAL}</td>
            </tr>
          `;
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    // ============================================================
    // API CALLS: ANNUAL CUMULATIVE REPORT (Apr 1 - Mar 31)
    // ============================================================
    async function loadYearlyReport() {
      showSync(true);
      const year = document.getElementById('yearlyReportYearSelect').value;
      const dept = document.getElementById('yearlyReportDept').value;

      try {
        const res = await fetch(`/api/sf-office/reports/yearly?year=${year}&department=${dept}`);
        const json = await res.json();
        const tbody = document.getElementById('yearlyReportTableBody');
        const tfoot = document.getElementById('yearlyReportTableFoot');
        const title = document.getElementById('yearlyReportTitle');

        if (json.status === 'SUCCESS') {
          title.innerText = `Annual Staff Leave Statement (${json.cycle.label}: ${json.cycle.start} to ${json.cycle.end})`;

          if (json.rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="11" class="text-center py-6 text-slate-400">No staff records found.</td></tr>`;
            tfoot.innerHTML = '';
            showSync(false);
            return;
          }

          tbody.innerHTML = json.rows.map((r, i) => {
            const rem = r.cl_remaining;
            const remColor = (rem >= 5 ? 'text-emerald-400' : rem >= 1 ? 'text-amber-400' : 'text-rose-400');
            return `
              <tr>
                <td class="text-left font-mono-code text-slate-500">${i + 1}</td>
                <td class="text-left font-bold text-slate-200">
                  <span>${r.name}</span>
                  <span class="text-[9.5px] text-slate-400 font-mono-code block">${r.mobile_no}</span>
                </td>
                <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${r.branch}</span></td>
                <td class="font-mono-code text-slate-300">${r.cl_quota}</td>
                <td class="font-bold text-amber-400">${r.cl_taken}</td>
                <td class="font-black text-sm ${remColor}">${r.cl_remaining}</td>
                <td class="font-bold text-cyan-400">${r.ccl_earned}</td>
                <td class="font-bold text-cyan-300">${r.ccl_taken}</td>
                <td class="font-bold text-indigo-400">${r.dl_taken}</td>
                <td class="font-bold text-rose-400">${r.ml_taken + r.lop_taken}</td>
                <td class="font-black text-purple-300 text-xs">${r.total_taken}</td>
              </tr>
            `;
          }).join('');

          const gt = json.grand_totals;
          tfoot.innerHTML = `
            <tr>
              <td colspan="3" class="text-left font-black uppercase text-slate-300">Grand Institution Totals</td>
              <td class="font-black text-slate-300">-</td>
              <td class="font-black text-amber-400">${gt.CL_TAKEN}</td>
              <td class="font-black text-emerald-400">${gt.REMAINING_CL}</td>
              <td class="font-black text-cyan-400">${gt.CCL_EARNED}</td>
              <td class="font-black text-cyan-300">${gt.CCL_TAKEN}</td>
              <td class="font-black text-indigo-400">${gt.DL_TAKEN}</td>
              <td class="font-black text-rose-400">${gt.ML_TAKEN + gt.LOP_TAKEN}</td>
              <td class="font-black text-purple-300 text-sm">${gt.TOTAL_TAKEN}</td>
            </tr>
          `;
        }
      } catch (e) {
        console.error(e);
      }
      showSync(false);
    }

    // ============================================================
    // API CALLS: STAFF DIRECTORY
    // ============================================================
    async function loadStaffDirectoryData() {
      try {
        const res = await fetch('/api/sf-office/staff');
        const json = await res.json();
        if (json.status === 'SUCCESS') {
          allSfStaff = json.staff;
          populateStaffSelectDropdowns(allSfStaff);
          document.getElementById('sidebarStaffCountBadge').innerText = allSfStaff.length;
        }
      } catch (e) {
        console.error(e);
      }
    }

    function populateStaffSelectDropdowns(staffList) {
      const bfSelect = document.getElementById('bf_staff_mobile');
      const mcclSelect = document.getElementById('mccl_staff_mobile');

      const options = staffList.map(s => `
        <option value="${s.mobile_no}">[${s.branch}] ${s.name} (${s.mobile_no})</option>
      `).join('');

      bfSelect.innerHTML = '<option value="">-- Choose SF Staff Member --</option>' + options;
      mcclSelect.innerHTML = '<option value="">-- Choose SF Staff Member --</option>' + options;
    }

    function filterStaffDirectory() {
      const query = document.getElementById('staffDirectorySearch').value.toLowerCase();
      const branch = document.getElementById('staffDirectoryBranch').value;

      const filtered = allSfStaff.filter(s => {
        const matchesQuery = !query || s.name.toLowerCase().includes(query) || s.mobile_no.includes(query);
        const matchesBranch = !branch || s.branch === branch;
        return matchesQuery && matchesBranch;
      });

      renderStaffDirectory(filtered);
    }

    function renderStaffDirectory(staffList) {
      const tbody = document.getElementById('staffDirectoryTableBody');
      if (!staffList || staffList.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center py-6 text-slate-400">No staff members found.</td></tr>`;
        return;
      }

      tbody.innerHTML = staffList.map(s => `
        <tr>
          <td>
            <div class="flex items-center gap-2">
              <div class="relative shrink-0">
                <img src="${s.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'}" class="w-7 h-7 rounded-full object-cover border border-slate-700">
                ${s.is_online ? '<span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 rounded-full border border-slate-900 ring-1 ring-emerald-400" title="Online"></span>' : ''}
              </div>
              <span class="font-bold text-slate-200">${s.name}</span>
            </div>
          </td>
          <td class="font-mono-code text-cyan-400">${s.mobile_no}</td>
          <td><span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-slate-800 text-slate-300">${s.branch}</span></td>
          <td class="text-slate-400 text-[10px]">${s.designation}</td>
          <td>
            <span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold ${s.is_online ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-800 text-slate-500'}">
              ${s.is_online ? '● Online' : 'Offline'}
            </span>
          </td>
          <td class="font-mono-code text-slate-300">${s.cl_quota}</td>
          <td class="font-bold text-amber-400">${s.cl_taken}</td>
          <td class="font-black text-emerald-400 font-mono-code">${s.cl_remaining}</td>
          <td class="font-bold text-cyan-300 font-mono-code">${s.ccl_balance}d</td>
          <td class="font-bold text-purple-300">${s.total_taken}</td>
          <td class="text-right">
            <button onclick="prefillBackfill('${s.mobile_no}')" class="px-2 py-0.5 rounded bg-indigo-600/30 hover:bg-indigo-600 text-indigo-300 hover:text-white text-[10px] font-bold cursor-pointer">
              Backfill
            </button>
          </td>
        </tr>
      `).join('');
    }

    function prefillBackfill(mobileNo) {
      switchPanel('approvals');
      switchLeaveSubTab('backfill');
      document.getElementById('bf_staff_mobile').value = mobileNo;
    }

    // ============================================================
    // API CALLS: OFFICE SETTINGS & RULES
    // ============================================================
    async function loadOfficeSettings() {
      try {
        const res = await fetch('/api/sf-office/settings');
        const json = await res.json();
        if (json.status === 'SUCCESS') {
          const s = json.settings;
          document.getElementById('setting_annual_cl_quota').value = s.annual_cl_quota || 15;
          document.getElementById('setting_ccl_validity_days').value = s.ccl_validity_days || 60;
          document.getElementById('setting_leave_year_start').value = s.leave_year_start || '04-01';
          document.getElementById('setting_leave_year_end').value = s.leave_year_end || '03-31';
        }
      } catch (e) {
        console.error(e);
      }
    }

    async function saveOfficeSettings(e) {
      e.preventDefault();
      const btn = document.getElementById('saveSettingsBtn');
      btn.disabled = true;

      const payload = {
        annual_cl_quota: document.getElementById('setting_annual_cl_quota').value,
        ccl_validity_days: document.getElementById('setting_ccl_validity_days').value,
        leave_year_start: document.getElementById('setting_leave_year_start').value,
        leave_year_end: document.getElementById('setting_leave_year_end').value,
      };

      try {
        const res = await fetch('/api/sf-office/settings', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify(payload)
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast(json.message, 'success');
          document.getElementById('settingsSavedNotice').innerText = '✓ Settings successfully saved.';
          setTimeout(() => document.getElementById('settingsSavedNotice').innerText = '', 4000);
          loadOverviewData();
        } else {
          showToast(json.message || 'Save failed', 'error');
        }
      } catch (err) {
        showToast('Error saving settings', 'error');
      }
      btn.disabled = false;
    }

    // ============================================================
    // API CALLS: PROFILE & CHANGE PASSWORD
    // ============================================================
    async function submitChangePassword(e) {
      e.preventDefault();
      const oldPw = document.getElementById('pw_old').value;
      const newPw = document.getElementById('pw_new').value;
      const confirmPw = document.getElementById('pw_confirm').value;

      if (newPw !== confirmPw) {
        showToast('New passwords do not match!', 'error');
        return;
      }

      const btn = document.getElementById('pwSubmitBtn');
      btn.disabled = true;

      try {
        const res = await fetch('/api/staff/change-password', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify({
            oldPassword: oldPw,
            newPassword: newPw
          })
        });

        const json = await res.json();
        if (json.status === 'SUCCESS') {
          showToast('Password updated successfully! Please keep your new password safe.', 'success');
          document.getElementById('changePasswordForm').reset();
        } else {
          showToast(json.message || 'Password update failed', 'error');
        }
      } catch (err) {
        showToast('Error changing password', 'error');
      }
      btn.disabled = false;
    }

    function showSync(show) {
      const el = document.getElementById('syncIndicator');
      if (el) {
        if (show) el.classList.remove('hidden');
        else el.classList.add('hidden');
      }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      loadStaffDirectoryData();
      loadOverviewData();
      setPunchQuickDate('7days');
    });
  </script>
</body>
</html>
