<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Carmel Linx - Academic Coordinator Portal</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts & FontAwesome -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Tailwind CSS CDN for Desktop -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

  <style>
    :root {
      --app-bg: #090d16;
      --card-bg: rgba(15, 23, 42, 0.92);
      --card-border: rgba(255, 255, 255, 0.08);
      --accent-cyan: #06b6d4;
      --accent-emerald: #10b981;
      --accent-amber: #f59e0b;
      --accent-rose: #f43f5e;
      --accent-purple: #8b5cf6;
      --accent-blue: #3b82f6;
    }

    body {
      background-color: var(--app-bg);
      color: #f3f4f6;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* MOBILE STYLES (< 768px) */
    @media (max-width: 767.98px) {
      .desktop-layout { display: none !important; }
      
      body {
        padding-bottom: 90px;
        -webkit-tap-highlight-color: transparent;
      }

      .mobile-container {
        max-width: 520px;
        margin: 0 auto;
        min-height: 100vh;
        background-color: var(--app-bg);
        position: relative;
      }

      .mobile-header {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--card-border);
        position: sticky;
        top: 0;
        z-index: 100;
        padding: 14px 16px;
      }

      .brand-title {
        font-weight: 900 !important;
        letter-spacing: -0.3px;
        background: linear-gradient(135deg, #38bdf8 0%, #a855f7 50%, #f43f5e 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 2px 8px rgba(56, 189, 248, 0.4));
      }

      .app-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 18px;
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
        backdrop-filter: blur(12px);
      }

      .stat-card {
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 10px;
      }

      .badge-app {
        font-size: 0.76rem;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: 700;
      }

      .avatar-mobile {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid var(--accent-cyan);
        object-fit: cover;
      }

      .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 520px;
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--card-border);
        display: flex;
        justify-content: space-around;
        padding: 10px 4px;
        z-index: 1000;
      }

      .nav-link-mobile {
        color: #94a3b8;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.72rem;
        font-weight: 700;
        gap: 4px;
        flex: 1;
        text-align: center;
        transition: all 0.2s ease;
      }

      .nav-link-mobile.active {
        color: var(--accent-cyan);
      }

      .nav-link-mobile i {
        font-size: 1.15rem;
      }

      .form-control, .form-select {
        font-size: 0.85rem !important;
        padding: 8px 12px;
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
      }
    }

    /* DESKTOP STYLES (>= 768px) */
    @media (min-width: 768px) {
      .mobile-layout { display: none !important; }
      body { overflow: hidden; height: 100vh; }
      .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
      .scrollbar-hidden::-webkit-scrollbar { display: none; }
      .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    }
  </style>
</head>
<body>

  <!-- ========================================== -->
  <!-- MOBILE LAYOUT (< 768px)                   -->
  <!-- ========================================== -->
  <div class="mobile-layout">
    <div class="mobile-container">
      
      <!-- Mobile Header -->
      <header class="mobile-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('logo.jpg') }}" alt="Logo" style="width: 32px; height: 32px; border-radius: 10px;" class="shadow-sm">
          <div>
            <h5 class="brand-title mb-0" style="font-size: 1.18rem; font-weight: 900 !important;">Carmel Linx</h5>
            <span class="badge badge-app px-2 py-0.5" style="background-color: rgba(168, 85, 247, 0.2); color: #e9d5ff; border: 1px solid rgba(168, 85, 247, 0.45); font-size: 0.68rem; font-weight: 800; border-radius: 6px;">Academic Coordinator SF</span>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ url('/logout') }}" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;" title="Sign Out">
            <i class="fa-solid fa-right-from-bracket"></i> Sign Out
          </a>
        </div>
      </header>

      <!-- Main Body Content -->
      <div class="p-3">

        <!-- Identity Card -->
        <div class="app-card border-start border-2 border-info mb-3">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="avatar-mobile" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center; font-weight: 900; color: #fff; font-size: 1.1rem;">
              AC
            </div>
            <div class="overflow-hidden">
              <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 1.05rem;">{{ session('userName') ?: 'Academic Coordinator' }}</h6>
              <small class="text-info font-mono font-bold d-block" style="font-size: 0.78rem;">{{ session('userId') }}</small>
              <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">Academic Coordinator (SF)</span>
              </div>
            </div>
          </div>

          <div class="row g-2 text-center">
            <div class="col-4">
              <div class="stat-card">
                <span class="text-secondary uppercase d-block" style="font-size: 0.62rem; font-weight: 700;">Pending SF</span>
                <strong class="text-warning" id="mobilePendingBadge" style="font-size: 1.05rem;">0</strong>
              </div>
            </div>
            <div class="col-4">
              <div class="stat-card">
                <span class="text-secondary uppercase d-block" style="font-size: 0.62rem; font-weight: 700;">Stream</span>
                <strong class="text-cyan" style="font-size: 0.78rem;">EL • AU • CT • GEN</strong>
              </div>
            </div>
            <div class="col-4">
              <a href="#" onclick="switchMobileTab('reports'); return false;" class="stat-card d-block text-decoration-none">
                <span class="text-secondary uppercase d-block" style="font-size: 0.62rem; font-weight: 700;">Ledger</span>
                <strong class="text-emerald" style="font-size: 0.78rem;"><i class="fa-solid fa-file-invoice me-1"></i>Reports</strong>
              </a>
            </div>
          </div>
        </div>

        <!-- Global Alert Banner -->
        <div id="mobileGlobalAlert" class="alert d-none py-2 px-3 mb-3 font-bold text-xs rounded-3"></div>

        <!-- TAB 1: PENDING APPROVALS -->
        <div id="mobileTabApprovals" class="mobile-tab-content">
          <div class="app-card border-start border-2 border-warning mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-bold text-warning mb-0" style="font-size: 0.88rem;">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Pending Staff Leave Approvals
              </h6>
              <button onclick="loadPendingApprovals()" class="btn btn-sm btn-outline-warning py-0.5 px-2" style="font-size: 0.7rem;">
                <i class="fa-solid fa-rotate me-1"></i> Sync
              </button>
            </div>
            <small class="text-secondary d-block mb-3" style="font-size: 0.74rem;">
              Stage 2 of 3-tier hierarchy (HOD Approved &rarr; <strong>Academic Coordinator</strong> &rarr; Principal) for Self-Financing departments.
            </small>
            <div id="mobilePendingApprovalsContainer" class="space-y-2">
              <small class="text-secondary d-block">Loading pending approval queue...</small>
            </div>
          </div>
        </div>

        <!-- TAB 2: SF STAFF DIRECTORY -->
        <div id="mobileTabDirectory" class="mobile-tab-content d-none">
          <div class="app-card mb-3">
            <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">
              <i class="fa-solid fa-users me-1 text-cyan"></i> SF Staff Directory
            </h6>
            <div class="row g-2 mb-3">
              <div class="col-12">
                <input type="text" id="mobileFilterSearch" oninput="loadUsers()" class="form-control" placeholder="Search staff name or mobile...">
              </div>
              <div class="col-6">
                <select id="mobileFilterBranch" onchange="loadUsers()" class="form-select">
                  <option value="">All SF Depts</option>
                  <option value="EL">EL (Electronics)</option>
                  <option value="AU">AU (Automobile)</option>
                  <option value="CT">CT (Computer)</option>
                  <option value="GEN_SF">GEN SF</option>
                </select>
              </div>
              <div class="col-6">
                <select id="mobileFilterRole" onchange="loadUsers()" class="form-select">
                  <option value="">All Roles</option>
                  <option value="HOD">HOD</option>
                  <option value="Lecturer">Lecturer</option>
                  <option value="Demonstrator">Demonstrator</option>
                  <option value="Trade_Instructor">Trade Instructor</option>
                </select>
              </div>
            </div>
            <div id="mobileUsersContainer">
              <!-- Populated dynamically -->
            </div>
          </div>
        </div>

        <!-- TAB 3: STAFF LEAVE MASTER LEDGER & REPORTS -->
        <div id="mobileTabReports" class="mobile-tab-content d-none">
          <div class="app-card border-start border-2 border-success mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-bold text-success mb-0" style="font-size: 0.88rem;">
                <i class="fa-solid fa-file-invoice me-1"></i> Staff Leave Master Ledger & Report
              </h6>
              <a href="/staff/leave/reports" target="_blank" class="btn btn-sm btn-outline-success py-0.5 px-2" style="font-size: 0.7rem;">
                <i class="fa-solid fa-print me-1"></i> PDF Ledger
              </a>
            </div>
            <small class="text-secondary d-block mb-3" style="font-size: 0.74rem;">
              Comprehensive audit trail & leave balances across Self-Financing departments.
            </small>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <select id="mobileReportBranch" onchange="loadLeaveReports()" class="form-select">
                  <option value="">All SF Depts</option>
                  <option value="EL">EL (Electronics)</option>
                  <option value="AU">AU (Automobile)</option>
                  <option value="CT">CT (Computer)</option>
                  <option value="GEN_SF">GEN SF</option>
                </select>
              </div>
              <div class="col-6">
                <select id="mobileReportCategory" onchange="loadLeaveReports()" class="form-select">
                  <option value="">All Categories</option>
                  <option value="Casual Leave">Casual Leave (CL)</option>
                  <option value="Compensatory Casual Leave">Compensatory (CCL)</option>
                  <option value="Duty Leave">Duty Leave (DL)</option>
                  <option value="Medical Leave">Medical Leave (ML)</option>
                  <option value="Loss of Pay">Loss of Pay (LOP)</option>
                  <option value="Special Leave">Special Leave (SL)</option>
                </select>
              </div>
            </div>

            <!-- Summary Totals Pill Card -->
            <div class="p-2.5 rounded-3 bg-slate-900 border border-secondary border-opacity-20 mb-3">
              <span class="text-secondary d-block uppercase fw-bold mb-1" style="font-size:0.65rem;">Academic Year Totals Summary</span>
              <div class="d-flex flex-wrap gap-1 text-center" id="mobileReportSummary">
                <span class="badge bg-primary bg-opacity-20 text-primary">CL: 0d</span>
                <span class="badge bg-warning bg-opacity-20 text-warning">CCL: 0d</span>
                <span class="badge bg-info bg-opacity-20 text-info">DL: 0d</span>
                <span class="badge bg-danger bg-opacity-20 text-danger">LOP: 0d</span>
                <span class="badge bg-success bg-opacity-20 text-success">Total: 0d</span>
              </div>
            </div>

            <div id="mobileReportsContainer">
              <small class="text-secondary d-block">Loading ledger records...</small>
            </div>
          </div>
        </div>

        <!-- TAB 4: SECURITY LOG -->
        <div id="mobileTabSecurity" class="mobile-tab-content d-none">
          <div class="app-card mb-3">
            <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">
              <i class="fa-solid fa-shield-halved me-1 text-primary"></i> Profile Security Log
            </h6>
            <div id="mobileSecurityLogsContainer">
              <!-- Populated dynamically -->
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom Mobile Tab Bar -->
      <nav class="bottom-nav">
        <a href="#" onclick="switchMobileTab('approvals'); return false;" id="mobileNavApprovals" class="nav-link-mobile active">
          <i class="fa-solid fa-clock-rotate-left"></i>
          <span>Approvals</span>
        </a>
        <a href="#" onclick="switchMobileTab('directory'); return false;" id="mobileNavDirectory" class="nav-link-mobile">
          <i class="fa-solid fa-users"></i>
          <span>SF Directory</span>
        </a>
        <a href="#" onclick="switchMobileTab('reports'); return false;" id="mobileNavReports" class="nav-link-mobile">
          <i class="fa-solid fa-file-invoice"></i>
          <span>Ledger</span>
        </a>
        <a href="#" onclick="switchMobileTab('security'); return false;" id="mobileNavSecurity" class="nav-link-mobile">
          <i class="fa-solid fa-shield-halved"></i>
          <span>Security</span>
        </a>
      </nav>

    </div>
  </div>


  <!-- ========================================== -->
  <!-- DESKTOP LAYOUT (>= 768px)                  -->
  <!-- ========================================== -->
  <div class="desktop-layout flex min-h-screen bg-slate-900 text-slate-100">
    
    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
      <div class="p-5 border-b border-slate-800/60 flex items-center gap-3">
        <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
        <div>
          <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#38bdf8,#818cf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
          <span class="text-xs text-indigo-400 font-bold uppercase tracking-widest">Academic Coordinator</span>
        </div>
      </div>

      <!-- Active Profile Info -->
      <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
        <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
        <div class="overflow-hidden">
          <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
          <span class="text-xs font-bold text-indigo-400 block uppercase tracking-wider">Self-Financing Coordinator</span>
        </div>
      </div>

      <!-- Navigation Menus -->
      <nav class="flex-grow p-3 space-y-1">
        <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs">
          <span class="material-symbols-rounded text-base">dashboard</span> Overview & Approvals
        </button>
        
        <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
          <span class="material-symbols-rounded text-base">group</span> SF Staff Directory
        </button>

        <button id="navReports" onclick="switchPanel('reports')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
          <span class="material-symbols-rounded text-base">event_note</span> Master leave ledger
        </button>

        <a href="/sf-attendance/attendance-report" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer no-underline block">
          <span class="material-symbols-rounded text-base">how_to_reg</span> SF staff punching Log
        </a>

        <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-2">
          <span class="material-symbols-rounded text-base">manage_accounts</span> My Profile
        </button>
      </nav>

      <!-- Logout -->
      <div class="p-4 border-t border-slate-800/80 space-y-2.5">
        <a href="{{ url('/logout') }}" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
          <span class="material-symbols-rounded text-sm">logout</span> Sign Out
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
      <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-8 z-10">
        <div class="flex items-center gap-3">
          <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Academic Coordinator Overview</h1>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">Self-Financing Stream</span>
        </div>
        <div class="flex items-center gap-3">
          @include('partials.fullscreen_btn')
          <div id="aiStatusBadge" class="hidden"></div>
          <div id="loadingIndicator" class="hidden items-center gap-2 text-slate-400 text-xs">
            <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
            <span>Syncing...</span>
          </div>
        </div>
      </header>

      <!-- Panel Container -->
      <div class="flex-grow overflow-y-auto p-8 space-y-6">
        
        <!-- Alert Banner -->
        <div id="globalAlert" class="hidden p-4 rounded-xl font-bold transition-premium border text-xs"></div>

        <!-- PANEL 1: OVERVIEW & PENDING LEAVE APPROVALS -->
        <div id="panelDashboard" class="space-y-6">
          
          <!-- Metrics Row -->
          <div class="grid grid-cols-4 gap-5">
            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">approval</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Pending SF Approvals</span>
                <span id="statPendingLeave" class="text-xl font-black text-white mt-0.5">0</span>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-indigo-500/10 text-indigo-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">account_tree</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Supervised Stream</span>
                <span class="text-sm font-black text-indigo-300 mt-0.5 block">EL • AU • CT • GEN SF</span>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-emerald-500/10 text-emerald-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">event_note</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Master Ledger</span>
                <button onclick="switchPanel('reports')" class="text-xs font-bold text-emerald-400 hover:underline flex items-center gap-1 mt-0.5 bg-transparent border-0 p-0 cursor-pointer">
                  View Ledger & Reports <span class="material-symbols-rounded text-xs">arrow_forward</span>
                </button>
              </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
              <div class="bg-purple-500/10 text-purple-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">smartphone</span></div>
              <div>
                <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Mobile Portal</span>
                <a href="/staff/mobile?mode=mobile" class="text-xs font-bold text-purple-400 hover:underline flex items-center gap-1 mt-0.5">
                  My Leave Log & Portal <span class="material-symbols-rounded text-xs">arrow_forward</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Pending Leave Applications Section -->
          <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
              <div>
                <h3 class="font-black text-slate-100 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-amber-400 text-lg">pending_actions</span>
                  Staff Leave Applications Pending Academic Coordinator Approval
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Stage 2 of 3-tier hierarchy (HOD Approved → <strong>Academic Coordinator</strong> → Principal) for Self-Financing departments (EL, AU, CT, GEN SF).</p>
              </div>
              <button onclick="loadPendingApprovals()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm">sync</span> Refresh Queue
              </button>
            </div>

            <div class="overflow-x-auto scrollbar-hidden">
              <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="p-3">Staff Member</th>
                    <th class="p-3">Dept</th>
                    <th class="p-3">Leave Category</th>
                    <th class="p-3">Date(s) Needed</th>
                    <th class="p-3">Session</th>
                    <th class="p-3">Reason & Work Arrangement</th>
                    <th class="p-3">HOD Stage</th>
                    <th class="p-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="pendingLeaveTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                  <tr><td colspan="8" class="p-6 text-center text-slate-500 font-bold">Loading pending leave applications...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Supervised Department Quick Info -->
          <div class="grid grid-cols-3 gap-6">
            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Electronics (EL)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>

            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Automobile (AU)</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>

            <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
              <div class="flex justify-between items-center">
                <span class="font-bold text-slate-200 text-sm">Computer (CT) & GEN SF</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
              </div>
              <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
            </div>
          </div>

        </div>

        <!-- PANEL 2: SF STAFF DIRECTORY -->
        <div id="panelDirectory" class="hidden space-y-6">
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
              <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="Search name or mobile...">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Department Filter</label>
              <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
                <option value="">All SF Departments (EL, AU, CT, GEN SF)</option>
                <option value="EL">Electronics (EL)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="CT">Computer Engineering (CT)</option>
                <option value="GEN_SF">General SF (GEN_SF)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
              <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
                <option value="">All Roles</option>
                <option value="HOD">HOD</option>
                <option value="Lecturer">Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Trade_Instructor">Trade Instructor</option>
              </select>
            </div>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile ID</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Designation</th>
                  <th class="p-4 text-right">Account Status</th>
                </tr>
              </thead>
              <tbody id="usersTableBody" class="divide-y divide-slate-800/40">
              </tbody>
            </table>
          </div>
        </div>

        <!-- PANEL 3: STAFF LEAVE MASTER LEDGER & REPORTS -->
        <div id="panelReports" class="hidden space-y-6">
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
              <div>
                <h3 class="font-bold text-slate-100 text-sm flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-400 text-base">event_note</span>
                  Staff Leave Master Ledger & Report Center
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Filter leave applications, audit approvals, and generate printable reports.</p>
              </div>
              <a href="/staff/leave/reports" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-premium no-underline flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-rounded text-sm">print</span> Print Official Ledger PDF
              </a>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Department Filter</label>
                <select id="desktopReportBranch" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="">All SF Departments (EL, AU, CT, GEN SF)</option>
                  <option value="EL">Electronics (EL)</option>
                  <option value="AU">Automobile (AU)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="GEN_SF">General SF (GEN_SF)</option>
                </select>
              </div>

              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Leave Category</label>
                <select id="desktopReportCategory" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="">All Leave Categories</option>
                  <option value="Casual Leave">Casual Leave (CL)</option>
                  <option value="Compensatory Casual Leave">Compensatory (CCL)</option>
                  <option value="Duty Leave">Duty Leave (DL)</option>
                  <option value="Medical Leave">Medical Leave (ML)</option>
                  <option value="Loss of Pay">Loss of Pay (LOP)</option>
                  <option value="Special Leave">Special Leave (SL)</option>
                </select>
              </div>

              <div>
                <label class="block text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Academic Year</label>
                <select id="desktopReportYear" onchange="loadLeaveReports()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-2.5 py-1.5 text-white focus:border-blue-500 outline-none text-xs">
                  <option value="2026">Academic Year 2026</option>
                  <option value="2025">Academic Year 2025</option>
                  <option value="2024">Academic Year 2024</option>
                </select>
              </div>
            </div>

            <!-- Category Summary Cards -->
            <div class="grid grid-cols-7 gap-2.5 pt-1">
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">CL</span>
                <span id="summaryCL" class="text-xs font-bold text-blue-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">CCL</span>
                <span id="summaryCCL" class="text-xs font-bold text-amber-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">DL</span>
                <span id="summaryDL" class="text-xs font-bold text-sky-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">ML</span>
                <span id="summaryML" class="text-xs font-bold text-purple-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">LOP</span>
                <span id="summaryLOP" class="text-xs font-bold text-rose-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">SL</span>
                <span id="summarySL" class="text-xs font-bold text-teal-400">0d</span>
              </div>
              <div class="bg-slate-900 border border-slate-800 p-2 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">Total</span>
                <span id="summaryTOTAL" class="text-xs font-bold text-emerald-400">0d</span>
              </div>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
              <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                    <th class="p-3">Staff Name</th>
                    <th class="p-3">Dept</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Dates</th>
                    <th class="p-3">Days</th>
                    <th class="p-3">HOD Stage</th>
                    <th class="p-3">Coordinator Stage</th>
                    <th class="p-3">Principal Stage</th>
                    <th class="p-3 text-right">Status & PDF</th>
                  </tr>
                </thead>
                <tbody id="reportsTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                  <tr><td colspan="9" class="p-6 text-center text-slate-500 font-bold">Loading leave ledger records...</td></tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>

        <!-- PANEL 4: MY PROFILE & SECURITY -->
        <div id="panelSecurity" class="hidden space-y-6">
          @include('partials.staff_profile_panel')
        </div>

      </div>
    </main>
  </div>

  <!-- REJECTION REMARKS MODAL -->
  <div id="rejectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-400 text-lg">cancel</span> Reject Leave Application
        </h3>
        <button onclick="closeRejectModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <input type="hidden" id="rejectLeaveId">
      <div class="space-y-3">
        <p class="text-xs text-slate-400">Please enter rejection remarks for this leave request:</p>
        <textarea id="rejectRemarksInput" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white outline-none focus:border-rose-500" placeholder="Specify reason for rejection..."></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeRejectModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitRejection()" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Reject Leave</button>
      </div>
    </div>
  </div>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      loadPendingApprovals();
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      const mobileAlert = document.getElementById('mobileGlobalAlert');

      if (alert) {
        alert.classList.remove('hidden');
        alert.className = isError ? "p-4 rounded-xl font-bold bg-rose-950/40 text-rose-400 border-rose-900 block text-xs" : "p-4 rounded-xl font-bold bg-emerald-950/40 text-emerald-400 border-emerald-900 block text-xs";
        alert.innerText = msg;
        setTimeout(() => alert.classList.add('hidden'), 5000);
      }

      if (mobileAlert) {
        mobileAlert.classList.remove('d-none');
        mobileAlert.className = isError ? "alert alert-danger py-2 px-3 mb-3 font-bold text-xs rounded-3" : "alert alert-success py-2 px-3 mb-3 font-bold text-xs rounded-3";
        mobileAlert.innerText = msg;
        setTimeout(() => mobileAlert.classList.add('d-none'), 5000);
      }
    }

    function switchMobileTab(tabId) {
      const tabs = ['approvals', 'directory', 'reports', 'security'];
      tabs.forEach(t => {
        const el = document.getElementById('mobileTab' + t.charAt(0).toUpperCase() + t.slice(1));
        const nav = document.getElementById('mobileNav' + t.charAt(0).toUpperCase() + t.slice(1));
        if (t === tabId) {
          if (el) el.classList.remove('d-none');
          if (nav) nav.classList.add('active');
        } else {
          if (el) el.classList.add('d-none');
          if (nav) nav.classList.remove('active');
        }
      });

      if (tabId === 'approvals') loadPendingApprovals();
      if (tabId === 'directory') loadUsers();
      if (tabId === 'reports') loadLeaveReports();
      if (tabId === 'security') loadSelfSecurityLogs();
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'reports', 'security'];
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
        'dashboard': 'Academic Coordinator Overview',
        'directory': 'Self-Financing Staff Directory',
        'reports': 'Staff Leave Master Ledger & Reports',
        'security': 'My Profile Security Log'
      };
      if (document.getElementById('panelTitle')) document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'dashboard') loadPendingApprovals();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'reports') loadLeaveReports();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

    function loadPendingApprovals() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/pending-approvals')
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            if (document.getElementById('statPendingLeave')) document.getElementById('statPendingLeave').innerText = data.approvals.length;
            if (document.getElementById('mobilePendingBadge')) document.getElementById('mobilePendingBadge').innerText = data.approvals.length;
            renderPendingTable(data.approvals);
            renderMobilePendingCards(data.approvals);
          }
        })
        .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function renderPendingTable(items) {
      const tbody = document.getElementById('pendingLeaveTableBody');
      if (!tbody) return;
      tbody.innerHTML = '';

      if (items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-slate-500 font-bold">No pending leave applications requiring Academic Coordinator approval.</td></tr>`;
        return;
      }

      items.forEach(req => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium';

        let datesText = req.start_date;
        if (req.end_date && req.end_date !== req.start_date) {
          datesText += ` to ${req.end_date}`;
        }
        if (req.ccl_date) {
          datesText += `<br><span class="text-[10px] text-amber-400 font-mono">CCL Date: ${req.ccl_date}</span>`;
        }

        let sessionBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${req.session}</span>`;

        tr.innerHTML = `
          <td class="p-3 font-bold text-slate-100">
            ${req.staff_name}
            <span class="block text-[10px] font-normal text-slate-400">${req.designation}</span>
          </td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${req.department}</span></td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-bold text-[10px] bg-purple-500/10 text-purple-300 border border-purple-500/20">${req.leave_category} (${req.total_days}d)</span></td>
          <td class="p-3 font-mono text-slate-300 text-xs">${datesText}</td>
          <td class="p-3">${sessionBadge}</td>
          <td class="p-3 max-w-xs truncate">
            <span class="text-slate-200 block truncate" title="${req.reason}">${req.reason}</span>
            <span class="text-[10px] text-slate-400 block">${req.work_arrangement_status || 'Arrangement done'}</span>
          </td>
          <td class="p-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved by ${req.hod_name || 'HOD'}</span>
          </td>
          <td class="p-3 text-right space-x-1">
            <button onclick="approveLeave(${req.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Approve
            </button>
            <button onclick="openRejectModal(${req.id})" class="px-3 py-1.5 bg-rose-950/50 hover:bg-rose-900 border border-rose-800 text-rose-300 rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Reject
            </button>
            <a href="/staff/leave/${req.id}/pdf" target="_blank" class="px-2 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs transition-premium no-underline inline-flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">picture_as_pdf</span> PDF
            </a>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function renderMobilePendingCards(items) {
      const container = document.getElementById('mobilePendingApprovalsContainer');
      if (!container) return;

      if (items.length === 0) {
        container.innerHTML = `<small class="text-secondary d-block py-2">No pending leave requests in your approval queue.</small>`;
        return;
      }

      let html = '';
      items.forEach(req => {
        const cclText = req.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${req.ccl_date}</span>` : '';
        html += `
          <div class="p-2.5 rounded-3 border border-warning border-opacity-30 bg-slate-900 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <strong class="text-white small">${req.staff_name} (${req.department})</strong>
              <span class="badge bg-warning text-dark small">${req.leave_category}</span>
            </div>
            <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
              ${req.start_date} ${req.end_date && req.end_date !== req.start_date ? ' to ' + req.end_date : ''} (${req.session}) &bull; ${req.total_days} Day(s)${cclText}
            </small>
            <div class="text-slate-300 small italic mb-2" style="font-size:0.75rem;">"${req.reason}"</div>
            <div class="d-flex gap-2">
              <button onclick="approveLeave(${req.id})" class="btn btn-sm btn-success py-0.5 px-3 flex-grow-1" style="font-size:0.72rem;">
                <i class="fa-solid fa-check me-1"></i> Approve
              </button>
              <button onclick="openRejectModal(${req.id})" class="btn btn-sm btn-outline-danger py-0.5 px-2" style="font-size:0.72rem;">
                <i class="fa-solid fa-xmark me-1"></i> Reject
              </button>
              <a href="/staff/leave/${req.id}/pdf" target="_blank" class="btn btn-sm btn-outline-light py-0.5 px-2" style="font-size:0.72rem;">
                <i class="fa-solid fa-file-pdf"></i>
              </a>
            </div>
          </div>
        `;
      });
      container.innerHTML = html;
    }

    function approveLeave(leaveId) {
      if (!confirm("Are you sure you want to approve this leave application? It will move to Principal for final approval.")) return;

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Approved',
          remarks: 'Approved by Academic Coordinator'
        })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request successfully approved!');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Approval failed.', true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        showGlobalMessage('Network error processing approval.', true);
      });
    }

    function openRejectModal(leaveId) {
      document.getElementById('rejectLeaveId').value = leaveId;
      document.getElementById('rejectRemarksInput').value = '';
      document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').classList.add('hidden');
    }

    function submitRejection() {
      const leaveId = document.getElementById('rejectLeaveId').value;
      const remarks = document.getElementById('rejectRemarksInput').value.trim();

      if (!remarks) {
        alert("Please enter rejection remarks.");
        return;
      }

      closeRejectModal();
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Rejected',
          remarks: remarks
        })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request rejected.');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Rejection failed.', true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        showGlobalMessage('Network error processing rejection.', true);
      });
    }

    function loadLeaveReports() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const branch = document.getElementById('desktopReportBranch')?.value || document.getElementById('mobileReportBranch')?.value || '';
      const category = document.getElementById('desktopReportCategory')?.value || document.getElementById('mobileReportCategory')?.value || '';
      const year = document.getElementById('desktopReportYear')?.value || '2026';

      let url = `/staff/leave/reports?academic_year=${year}`;
      if (branch) url += `&department=${encodeURIComponent(branch)}`;
      if (category) url += `&leave_type=${encodeURIComponent(category)}`;

      fetch(url, {
        headers: { 'Accept': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          renderLeaveReports(data.leaves, data.summary);
        }
      })
      .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function renderLeaveReports(leaves, summary) {
      // Render summary counts
      if (summary) {
        if (document.getElementById('summaryCL')) document.getElementById('summaryCL').innerText = (summary.CL || 0) + 'd';
        if (document.getElementById('summaryCCL')) document.getElementById('summaryCCL').innerText = (summary.CCL || 0) + 'd';
        if (document.getElementById('summaryDL')) document.getElementById('summaryDL').innerText = (summary.DL || 0) + 'd';
        if (document.getElementById('summaryML')) document.getElementById('summaryML').innerText = (summary.ML || 0) + 'd';
        if (document.getElementById('summaryLOP')) document.getElementById('summaryLOP').innerText = (summary.LOP || 0) + 'd';
        if (document.getElementById('summarySL')) document.getElementById('summarySL').innerText = (summary.SL || 0) + 'd';
        if (document.getElementById('summaryTOTAL')) document.getElementById('summaryTOTAL').innerText = (summary.TOTAL_DAYS || 0) + 'd';

        const mobileSummary = document.getElementById('mobileReportSummary');
        if (mobileSummary) {
          mobileSummary.innerHTML = `
            <span class="badge bg-primary bg-opacity-20 text-primary">CL: ${summary.CL || 0}d</span>
            <span class="badge bg-warning bg-opacity-20 text-warning">CCL: ${summary.CCL || 0}d</span>
            <span class="badge bg-info bg-opacity-20 text-info">DL: ${summary.DL || 0}d</span>
            <span class="badge bg-danger bg-opacity-20 text-danger">LOP: ${summary.LOP || 0}d</span>
            <span class="badge bg-success bg-opacity-20 text-success">Total: ${summary.TOTAL_DAYS || 0}d</span>
          `;
        }
      }

      // Filter SF departments if no department filter selected
      const sfDepts = ['EL', 'AU', 'CT', 'GEN_SF', 'SF'];
      const filteredLeaves = leaves.filter(l => sfDepts.includes(l.department?.toUpperCase()));

      // Render Desktop Table
      const tbody = document.getElementById('reportsTableBody');
      if (tbody) {
        tbody.innerHTML = '';
        if (filteredLeaves.length === 0) {
          tbody.innerHTML = '<tr><td colspan="9" class="p-8 text-center text-slate-500 font-bold">No leave report records found for the selected filter.</td></tr>';
        } else {
          filteredLeaves.forEach(l => {
            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
            
            let dates = l.from_date;
            if (l.to_date && l.to_date !== l.from_date) dates += ` to ${l.to_date}`;

            let statusBadge = l.overall_status === 'Approved' ? 
              '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved</span>' :
              (l.overall_status === 'Rejected' ? 
                '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">Rejected</span>' :
                '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>');

            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-100">${l.staff_name || 'Staff'}</td>
              <td class="p-3"><span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${l.department}</span></td>
              <td class="p-3"><span class="px-2 py-0.5 rounded font-bold text-[10px] bg-purple-500/10 text-purple-300 border border-purple-500/20">${l.leave_type}</span></td>
              <td class="p-3 font-mono text-slate-300 text-xs">${dates}</td>
              <td class="p-3 font-bold text-slate-200">${l.total_days}d</td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.hod_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.hod_approval || 'Pending'}</span></td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.coordinator_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.coordinator_approval || 'Pending'}</span></td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.principal_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.principal_approval || 'Pending'}</span></td>
              <td class="p-3 text-right space-x-1">
                ${statusBadge}
                <a href="/staff/leave/${l.id}/pdf" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-[10px] no-underline inline-flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">picture_as_pdf</span> PDF
                </a>
              </td>
            `;
            tbody.appendChild(tr);
          });
        }
      }

      // Render Mobile Cards
      const mobileContainer = document.getElementById('mobileReportsContainer');
      if (mobileContainer) {
        mobileContainer.innerHTML = '';
        if (filteredLeaves.length === 0) {
          mobileContainer.innerHTML = '<small class="text-secondary d-block py-2">No leave report records found.</small>';
        } else {
          let html = '';
          filteredLeaves.forEach(l => {
            let dates = l.from_date;
            if (l.to_date && l.to_date !== l.from_date) dates += ` to ${l.to_date}`;
            const badgeClass = l.overall_status === 'Approved' ? 'bg-success' : (l.overall_status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark');
            
            html += `
              <div class="p-2.5 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <strong class="text-white small">${l.staff_name || 'Staff'} (${l.department})</strong>
                  <span class="badge ${badgeClass} small">${l.overall_status}</span>
                </div>
                <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
                  <span class="badge bg-purple bg-opacity-20 text-purple">${l.leave_type}</span> &bull; ${dates} (${l.total_days}d)
                </small>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-secondary border-opacity-10">
                  <small class="text-secondary" style="font-size:0.68rem;">HOD: ${l.hod_approval || 'P'} | Coord: ${l.coordinator_approval || 'P'} | Prin: ${l.principal_approval || 'P'}</small>
                  <a href="/staff/leave/${l.id}/pdf" target="_blank" class="btn btn-sm btn-outline-light py-0.2 px-2" style="font-size:0.68rem;">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                  </a>
                </div>
              </div>
            `;
          });
          mobileContainer.innerHTML = html;
        }
      }
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const search = (document.getElementById('filterSearch')?.value || document.getElementById('mobileFilterSearch')?.value || '');
      const branch = (document.getElementById('filterBranch')?.value || document.getElementById('mobileFilterBranch')?.value || '');
      const role = (document.getElementById('filterRole')?.value || document.getElementById('mobileFilterRole')?.value || '');

      let url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}`;
      if (branch) url += `&branch=${branch}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            const mobileContainer = document.getElementById('mobileUsersContainer');
            
            const sfDepts = ['EL', 'AU', 'CT', 'GEN_SF', 'SF'];
            const filteredUsers = branch ? data.users : data.users.filter(u => sfDepts.includes(u.branch?.toUpperCase()));

            // Desktop render
            if (tbody) {
              tbody.innerHTML = '';
              if (filteredUsers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500 font-bold">No Self-Financing staff members found.</td></tr>';
              } else {
                filteredUsers.forEach(user => {
                  const tr = document.createElement('tr');
                  tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
                  tr.innerHTML = `
                    <td class="p-4 flex items-center gap-3">
                      <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
                      <div>
                        <span class="font-bold text-slate-100 block">${user.name}</span>
                        <span class="text-[10px] text-slate-500 block">${user.email}</span>
                      </div>
                    </td>
                    <td class="p-4 font-mono text-slate-300 font-bold">${user.id}</td>
                    <td class="p-4"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                    <td class="p-4 text-slate-300 font-medium">${user.role}</td>
                    <td class="p-4 text-right"><span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">${user.status}</span></td>
                  `;
                  tbody.appendChild(tr);
                });
              }
            }

            // Mobile render
            if (mobileContainer) {
              mobileContainer.innerHTML = '';
              if (filteredUsers.length === 0) {
                mobileContainer.innerHTML = '<small class="text-secondary d-block py-2">No Self-Financing staff members found.</small>';
              } else {
                let html = '';
                filteredUsers.forEach(user => {
                  html += `
                    <div class="p-2.5 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2 d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                        <div class="overflow-hidden">
                          <strong class="text-white d-block text-truncate small">${user.name}</strong>
                          <small class="text-secondary d-block" style="font-size:0.7rem;">${user.id} &bull; ${user.role}</small>
                        </div>
                      </div>
                      <span class="badge bg-secondary bg-opacity-20 text-light font-mono small">${user.branch}</span>
                    </div>
                  `;
                });
                mobileContainer.innerHTML = html;
              }
            }

          }
        })
        .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      const mobileContainer = document.getElementById('mobileSecurityLogsContainer');

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            if (tbody) {
              tbody.innerHTML = "";
              if (data.logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500 font-bold">No profile security logs recorded.</td></tr>`;
              } else {
                data.logs.forEach(log => {
                  const tr = document.createElement('tr');
                  tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
                  const date = new Date(log.created_at).toLocaleString();
                  tr.innerHTML = `
                    <td class="p-4 text-slate-400 font-mono">${date}</td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                    <td class="p-4 text-slate-300">${log.details || ''}</td>
                  `;
                  tbody.appendChild(tr);
                });
              }
            }

            if (mobileContainer) {
              mobileContainer.innerHTML = "";
              if (data.logs.length === 0) {
                mobileContainer.innerHTML = `<small class="text-secondary d-block py-2">No security audit logs recorded.</small>`;
              } else {
                let html = '';
                data.logs.forEach(log => {
                  const date = new Date(log.created_at).toLocaleDateString();
                  html += `
                    <div class="p-2 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2">
                      <div class="d-flex justify-content-between text-secondary mb-1" style="font-size:0.7rem;">
                        <span class="font-mono">${date}</span>
                        <span class="badge bg-info text-dark" style="font-size:0.65rem;">${log.action}</span>
                      </div>
                      <small class="text-slate-300 d-block" style="font-size:0.75rem;">${log.details || ''}</small>
                    </div>
                  `;
                });
                mobileContainer.innerHTML = html;
              }
            }

          }
        });
    }
  </script>
  @include('partials.support_desk_overlay')
</body>
</html>
