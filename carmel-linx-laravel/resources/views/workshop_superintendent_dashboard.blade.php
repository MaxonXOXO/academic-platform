<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Workshop Superintendent Dashboard</title>
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
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* High-Contrast, Clearly Visible Vertical Scrollbar */
    .custom-scrollbar::-webkit-scrollbar,
    ::-webkit-scrollbar {
      width: 10px !important;
      height: 10px !important;
    }
    .custom-scrollbar::-webkit-scrollbar-track,
    ::-webkit-scrollbar-track {
      background: #090d16 !important;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb,
    ::-webkit-scrollbar-thumb {
      background: #475569 !important;
      border-radius: 6px !important;
      border: 2px solid #090d16 !important;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover,
    ::-webkit-scrollbar-thumb:hover {
      background: #64748b !important;
    }

    /* Firefox scrollbar */
    * {
      scrollbar-width: thin;
      scrollbar-color: #475569 #090d16;
    }

    /* Force vertical scrollbar track to stay always visible for reliable scrolling */
    #mainPanelContainer {
      overflow-y: scroll !important;
      -webkit-overflow-scrolling: touch;
    }

    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
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

      /* Hide support badge on mobile inside sidebar header row */
      #sidebarSupportBadge {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 h-screen w-full flex flex-col md:flex-row overflow-hidden antialiased">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl md:h-screen md:max-h-screen overflow-hidden">
    <!-- Brand / Logo -->
    <div class="p-5 border-b border-slate-800/60 flex items-center gap-3 shrink-0">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#60a5fa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Workshop Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3 shrink-0" id="sidebarAvatarContainer">
      <div class="relative shrink-0">
        <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full shadow-sm ring-1 ring-emerald-400/40" title="Online"></span>
      </div>
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-amber-400 block uppercase tracking-wider">Workshop Superintendent</span>
      </div>
    </div>

    <!-- Navigation Menus (Scrollable inside sidebar if needed) -->
    <nav class="flex-grow p-4 space-y-1.5 overflow-y-auto min-h-0 custom-scrollbar">
      <button id="navOverview" onclick="switchPanel('overview')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-semibold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link cursor-pointer">
        <span class="material-symbols-rounded text-lg">dashboard</span> Assigned Batch Classes
      </button>
      <button id="navStaff" onclick="switchPanel('staff')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mobile-link">
        <span class="material-symbols-rounded text-lg">badge</span> Workshop Staff Directory
      </button>

      @php
        $mobileNo = session('userId');
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNo);
        $isTutor = \App\Models\ClassManagement::where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('tutor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('tutor_mobile_no', $cleanMobile);
        })->exists() || \DB::table('r26_class_management')->where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('tutor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('tutor_mobile_no', $cleanMobile);
        })->exists();

        $isMentor = \App\Models\ClassManagement::where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('mentor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('mentor_mobile_no', $cleanMobile);
        })->exists() || \DB::table('r26_class_management')->where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('mentor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('mentor_mobile_no', $cleanMobile);
        })->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif

      <a href="/staff/attendance-log" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-lg">co_present</span> Class Attendance Log
      </a>

      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block mobile-link">
         <span class="material-symbols-rounded text-lg">event_note</span> My Leave & Attendance Log
      </a>

      <a href="/staff/professional-activities" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">school</span> Professional Activities
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-4 mobile-link">
        <span class="material-symbols-rounded text-lg">manage_accounts</span> My Profile & Security
      </button>
    </nav>

    <!-- Sidebar Bottom Logout Block (Pinned to bottom via shrink-0 mt-auto) -->
    <div class="p-4 border-t border-slate-800/80 space-y-2 shrink-0 mt-auto bg-slate-950/90">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of Carmel Linx Workshop Desk?')" class="w-full py-2.5 bg-red-950/40 hover:bg-red-900/60 border border-red-800/60 hover:border-red-600 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-red-300 hover:text-white transition-premium shadow-sm">
        <span class="material-symbols-rounded text-sm">logout</span> Sign Out
      </a>

      <!-- Support Badge -->
      <div id="sidebarSupportBadge" onclick="openStaffSupportModal()" class="p-2 bg-slate-950/60 hover:bg-slate-900 border border-slate-800/80 rounded-xl text-center select-none cursor-pointer transition-premium" title="Click to Request Remote Support Assist">
        <div class="flex items-center justify-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
          <span class="material-symbols-rounded text-xs text-blue-400">headset_mic</span> Live Assist
        </div>
        <div class="text-[11px] font-black text-slate-200 mt-0.5">Dhanush.A</div>
        <div class="text-[9px] text-slate-400 font-medium">Dept. of Electronics</div>
      </div>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col h-full min-w-0 min-h-0 overflow-hidden relative">
    
    <!-- Top Header Bar with Sticky Layout & Prominent Sign Out Button -->
    <header class="h-16 shrink-0 border-b border-slate-800/60 bg-slate-900/90 backdrop-blur-md flex items-center justify-between px-4 md:px-8 z-30 sticky top-0 shadow-sm">
      <div class="flex items-center gap-3 min-w-0">
        <span class="material-symbols-rounded text-blue-400 text-2xl shrink-0">precision_manufacturing</span>
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-base md:text-lg truncate">Assigned Batch Classes</h1>
      </div>
      <div class="flex items-center gap-2.5 md:gap-3 shrink-0">
        @include('partials.fullscreen_btn')
        
        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
          <span class="hidden sm:inline">Syncing...</span>
        </div>

        <!-- Prominent Top Header Sign Out Button -->
        <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of Carmel Linx Workshop Desk?')" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/30 hover:border-red-500/60 rounded-xl text-xs font-bold transition-premium cursor-pointer no-underline shadow-sm" title="Sign Out of Carmel Linx">
          <span class="material-symbols-rounded text-base">logout</span>
          <span class="font-bold hidden sm:inline">Sign Out</span>
        </a>
      </div>
    </header>

    <!-- Main Panel Container (Fixed Height + Always Visible Scrollbar + min-h-0) -->
    <div id="mainPanelContainer" class="flex-grow min-h-0 overflow-y-auto p-4 md:p-8 space-y-6 custom-scrollbar" style="height: calc(100vh - 4rem); max-height: calc(100vh - 4rem); scroll-behavior: smooth;">

      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl font-bold transition-premium border text-xs"></div>

      <!-- PANEL 1: OVERVIEW & ASSIGNED BATCH CARDS -->
      <div id="panelOverview" class="space-y-6">
        
        @php
          $allAssignments = $assignments ?? collect();
          $r26Count = $allAssignments->filter(function($a) {
              return ($a->syllabus_revision_code === 'REV2026' || str_contains($a->classroom_id, '2026'));
          })->count();
          $totalBatches = $allAssignments->count();
        @endphp

        <!-- Metric Cards Row (Staff & Batch Operations Focus) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
          <!-- Card 1: Rev 2026 Workshop Labs -->
          <div class="bg-gradient-to-br from-slate-950/80 via-slate-900/60 to-emerald-950/30 border border-emerald-500/30 p-5 rounded-2xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] text-emerald-400 uppercase font-black tracking-wider block">Revision 2026 Workshop Labs</span>
              <div class="text-2xl font-black text-white mt-0.5 flex items-baseline gap-2">
                <span>{{ $r26Count }}</span>
                <span class="text-xs font-bold text-emerald-400/80 font-mono">Batches</span>
              </div>
              <span class="text-[11px] text-slate-400 mt-1 block">Strict Lab Scheme: CIA 60M | ESE 40M</span>
            </div>
            <div class="bg-emerald-500/10 text-emerald-400 p-3.5 rounded-2xl border border-emerald-500/20 shadow-inner">
              <span class="material-symbols-rounded text-2xl">science</span>
            </div>
          </div>

          <!-- Card 2: Total Assigned Batches -->
          <div class="bg-gradient-to-br from-slate-950/80 via-slate-900/60 to-blue-950/30 border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[10px] text-blue-400 uppercase font-black tracking-wider block">Total Assigned Batches</span>
              <div class="text-2xl font-black text-white mt-0.5 flex items-baseline gap-2">
                <span>{{ $totalBatches }}</span>
                <span class="text-xs font-bold text-blue-400/80 font-mono">Classes</span>
              </div>
              <span class="text-[11px] text-slate-400 mt-1 block">Across All Assigned Departments</span>
            </div>
            <div class="bg-blue-500/10 text-blue-400 p-3.5 rounded-2xl border border-blue-500/20 shadow-inner">
              <span class="material-symbols-rounded text-2xl">school</span>
            </div>
          </div>

          <!-- Card 3: Workshop Staff (Trade Instructors) -->
          <div onclick="switchPanel('staff')" class="bg-gradient-to-br from-slate-950/80 via-slate-900/60 to-amber-950/30 border border-amber-500/30 p-5 rounded-2xl flex items-center justify-between shadow-sm cursor-pointer hover:border-amber-400/60 transition-premium group">
            <div>
              <span class="text-[10px] text-amber-400 uppercase font-black tracking-wider block">Workshop Staff Directory</span>
              <div class="text-2xl font-black text-white mt-0.5 flex items-baseline gap-2">
                <span id="statWorkshopStaff">—</span>
                <span class="text-xs font-bold text-amber-400/80 font-mono">Instructors</span>
              </div>
              <span class="text-[11px] text-slate-400 mt-1 block group-hover:text-amber-300 transition-colors">Manage Trade Instructors &rarr;</span>
            </div>
            <div class="bg-amber-500/10 text-amber-400 p-3.5 rounded-2xl border border-amber-500/20 shadow-inner group-hover:scale-105 transition-transform">
              <span class="material-symbols-rounded text-2xl">handyman</span>
            </div>
          </div>
        </div>

        <!-- Section Header & Filter Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h2 class="text-base font-black text-white flex items-center gap-2">
              <span class="material-symbols-rounded text-cyan-400 text-xl">view_cozy</span> Assigned Departmental Batch Cards
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
              Direct access as assigned staff to all workshop & practical batches. Revision 2026 workshop courses are strictly treated as <strong class="text-emerald-400">Lab Virtual Classes</strong>.
            </p>
          </div>

          <!-- Filter Pills & Search -->
          <div class="flex flex-wrap items-center gap-2.5">
            <div class="flex items-center bg-slate-900/80 p-1 rounded-xl border border-slate-800">
              <button type="button" id="pillAll" onclick="filterBatchCards('all')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-premium bg-blue-600 text-white shadow-sm cursor-pointer">
                All ({{ $totalBatches }})
              </button>
              <button type="button" id="pillR26" onclick="filterBatchCards('r26')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white cursor-pointer">
                Rev 2026 Labs ({{ $r26Count }})
              </button>
              <button type="button" id="pillR21" onclick="filterBatchCards('r21')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white cursor-pointer">
                Rev 2021 ({{ $totalBatches - $r26Count }})
              </button>
            </div>

            <!-- Search box -->
            <div class="relative">
              <span class="material-symbols-rounded absolute left-3 top-2.5 text-slate-500 text-base">search</span>
              <input type="text" id="batchSearchInput" oninput="searchBatchCards(this.value)" placeholder="Search batch, branch, code..." class="bg-slate-900 border border-slate-800 rounded-xl pl-9 pr-3 py-1.5 text-xs text-white placeholder-slate-500 focus:border-blue-500 outline-none w-48 md:w-56">
            </div>
          </div>
        </div>

        <!-- BATCH CARDS GRID -->
        <div id="batchCardsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @forelse($allAssignments as $asg)
            @php
              $isR26 = ($asg->syllabus_revision_code === 'REV2026' || str_contains($asg->classroom_id, '2026'));
              $codeUpper = strtoupper($asg->subject_code ?? '');
              $nameLower = strtolower($asg->subject_name ?? '');
              $typeLower = strtolower($asg->subject_type ?? '');
              $isTheory = ($typeLower === 'theory');
              
              // Revision 2021 routing
              $isDrawing = (str_contains($nameLower, 'drawing') || str_contains($nameLower, 'graphics'));
              $isSeminar = (str_contains($typeLower, 'seminar') || str_contains($nameLower, 'seminar'));
              $isProject = (str_contains($typeLower, 'project') || str_contains($nameLower, 'project'));
              $isMachineShop = (str_contains($codeUpper, '5027') || str_contains($nameLower, 'machine shop') || str_contains($nameLower, 'shop practice'));
              $isWorkshop = !$isMachineShop && (str_contains($codeUpper, '2009') || str_contains($nameLower, 'workshop') || in_array($codeUpper, ['3027', '3038']));

              if ($isTheory) {
                  $r21Url = "/dashboard/lecturer?" . http_build_query([
                      'subject_id' => $asg->subject_id,
                      'classroom_id' => $asg->classroom_id,
                      'subject_name' => $asg->subject_name,
                      'subject_code' => $asg->subject_code,
                      'revision' => $asg->syllabus_revision_code ?? 'REV2021',
                      'type' => 'Theory'
                  ]);
              } elseif ($isWorkshop) {
                  $r21Url = "/r21/classroom/workshop/{$asg->subject_id}";
              } elseif ($isDrawing) {
                  $r21Url = "/r21/classroom/drawing/{$asg->subject_id}";
              } elseif ($isSeminar) {
                  $r21Url = "/r21/classroom/seminar/{$asg->subject_id}";
              } elseif ($isProject) {
                  $r21Url = "/r21/classroom/project/{$asg->subject_id}";
              } else {
                  $r21Url = "/classroom/practical/{$asg->subject_id}";
              }

              // Branch color schemes
              $branch = strtoupper($asg->branch ?? 'GEN');
              $branchBadgeClass = match($branch) {
                'AU' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
                'CE' => 'bg-orange-500/15 text-orange-300 border-orange-500/30',
                'CT' => 'bg-cyan-500/15 text-cyan-300 border-cyan-500/30',
                'EEE' => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
                'EL' => 'bg-purple-500/15 text-purple-300 border-purple-500/30',
                'ME' => 'bg-rose-500/15 text-rose-300 border-rose-500/30',
                default => 'bg-blue-500/15 text-blue-300 border-blue-500/30'
              };

              // Co-faculty list for this subject
              $facultyList = $coFaculty->get($asg->subject_id, collect());
            @endphp

            <div class="batch-card bg-slate-950/50 border {{ $isR26 ? 'border-emerald-500/30 hover:border-emerald-400/60' : 'border-slate-800/80 hover:border-slate-700' }} rounded-2xl p-5 flex flex-col justify-between transition-premium shadow-md hover:shadow-xl relative overflow-hidden group"
                 data-rev="{{ $isR26 ? 'r26' : 'r21' }}"
                 data-branch="{{ $branch }}"
                 data-code="{{ $asg->subject_code }}"
                 data-name="{{ $asg->subject_name }}"
                 data-classroom="{{ $asg->classroom_id }}">
              
              <!-- Subtle top accent line -->
              <div class="absolute top-0 left-0 right-0 h-1 {{ $isR26 ? 'bg-gradient-to-r from-teal-400 via-emerald-500 to-cyan-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}"></div>

              <!-- Card Header Row -->
              <div>
                <div class="flex items-center justify-between gap-2 mb-2.5">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-black uppercase tracking-wider border {{ $branchBadgeClass }}">
                      {{ $branch }}
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[11px] font-mono font-bold bg-slate-900 text-slate-300 border border-slate-800">
                      {{ $asg->classroom_id }}
                    </span>
                  </div>

                  @if($isR26)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 flex items-center gap-1 shadow-sm">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> REV 2026 · S{{ $asg->semester }}
                    </span>
                  @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/15 text-indigo-300 border border-indigo-500/30">
                      REV 2021 · S{{ $asg->semester }}
                    </span>
                  @endif
                </div>

                <!-- Subject Code & Title -->
                <div class="font-mono text-xs font-bold text-slate-400 tracking-wide">{{ $asg->subject_code }}</div>
                <h3 class="text-base font-black text-white group-hover:text-cyan-300 transition-colors mt-0.5 leading-snug line-clamp-2" title="{{ $asg->subject_name }}">
                  {{ $asg->subject_name }}
                </h3>

                <!-- Scheme & Course Badge -->
                @if($isR26)
                  <div class="mt-3 p-2.5 rounded-xl bg-gradient-to-r from-emerald-950/40 to-teal-950/20 border border-emerald-500/30 flex items-start gap-2">
                    <span class="material-symbols-rounded text-emerald-400 text-lg mt-0.5 shrink-0">science</span>
                    <div class="text-[11px] leading-tight">
                      <div class="font-bold text-emerald-200 flex items-center gap-1.5">
                        <span>Lab Virtual Class Scheme</span>
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">CIA 60M | ESE 40M</span>
                      </div>
                      <div class="text-[10px] text-slate-400 mt-1">
                        CE 50&rarr;30 &bull; Practical Test 40&rarr;15 &bull; Open-Ended 50&rarr;10 &bull; Atten 5
                      </div>
                    </div>
                  </div>
                @elseif($isTheory)
                  <div class="mt-3 p-2.5 rounded-xl bg-gradient-to-r from-blue-950/40 to-indigo-950/30 border border-blue-500/30 flex items-start gap-2">
                    <span class="material-symbols-rounded text-blue-400 text-lg mt-0.5 shrink-0">menu_book</span>
                    <div class="text-[11px] leading-tight">
                      <div class="font-bold text-blue-200 flex items-center gap-1.5">
                        <span>Theory Virtual Classroom</span>
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30">REV 2021 Theory</span>
                      </div>
                      <div class="text-[10px] text-slate-400 mt-0.5">
                        Course Structure &bull; Planner &bull; Assignments &bull; Series Exams &bull; Reports
                      </div>
                    </div>
                  </div>
                @else
                  <div class="mt-3 p-2.5 rounded-xl bg-slate-900/40 border border-slate-800/70 flex items-start gap-2">
                    <span class="material-symbols-rounded text-blue-400 text-lg mt-0.5 shrink-0">engineering</span>
                    <div class="text-[11px] leading-tight">
                      <div class="font-bold text-slate-200">
                        {{ $asg->subject_type ?? 'Practical / Lab' }}
                      </div>
                      <div class="text-[10px] text-slate-400 mt-0.5">
                        Revision 2021 Curriculum Standards
                      </div>
                    </div>
                  </div>
                @endif

                <!-- Assigned Faculty & Co-Workers Section -->
                <div class="mt-3.5 pt-3 border-t border-slate-800/60">
                  <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs text-amber-400">group</span> Assigned Staff ({{ $facultyList->count() }})
                    </span>
                    @if($facultyList->count() > 1)
                      <span class="text-[10px] text-cyan-400 font-bold">Shared Class</span>
                    @endif
                  </div>

                  <div class="flex flex-wrap gap-1.5 max-h-20 overflow-y-auto pr-1 scrollbar-hidden">
                    @forelse($facultyList as $fac)
                      @php
                        $isMe = ($fac->mobile_no == session('userId'));
                      @endphp
                      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $isMe ? 'bg-blue-500/20 text-blue-300 border border-blue-500/40 shadow-sm' : 'bg-slate-900 text-slate-300 border border-slate-800' }}" title="{{ $fac->designation }} &bull; {{ $fac->branch }}">
                        <span class="material-symbols-rounded text-[11px] {{ $isMe ? 'text-blue-400' : 'text-slate-400' }}">
                          {{ $isMe ? 'person' : 'person_outline' }}
                        </span>
                        <span>{{ $fac->name }}{{ $isMe ? ' (You)' : '' }}</span>
                      </span>
                    @empty
                      <span class="text-[10px] text-slate-500 italic">No co-workers assigned</span>
                    @endforelse
                  </div>
                </div>
              </div>

              <!-- Action Button to Enter Virtual Classroom -->
              <div class="mt-4 pt-3 border-t border-slate-800/80">
                @if($isR26)
                  <!-- Primary Action: Open Lab Virtual Class -->
                  <a href="/r26/classroom/practical/{{ $asg->subject_id }}" target="_blank" class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 no-underline transition-premium cursor-pointer group-hover:scale-[1.01]">
                    <span class="material-symbols-rounded text-base">login</span> Enter Lab Virtual Classroom
                  </a>
                @elseif($isTheory)
                  <!-- Primary Action: Open R21 Theory Virtual Class -->
                  <a href="{{ $r21Url }}" target="_blank" class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 no-underline transition-premium cursor-pointer group-hover:scale-[1.01]">
                    <span class="material-symbols-rounded text-base">menu_book</span> Enter Theory Virtual Classroom
                  </a>
                @else
                  <!-- Primary Action: Open R21 Virtual Class -->
                  <a href="{{ $r21Url }}" target="_blank" class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 no-underline transition-premium cursor-pointer group-hover:scale-[1.01]">
                    <span class="material-symbols-rounded text-base">login</span> Enter Virtual Classroom
                  </a>
                @endif
              </div>

            </div>
          @empty
            <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-12 rounded-2xl text-center">
              <span class="material-symbols-rounded text-4xl text-slate-600">hourglass_empty</span>
              <h3 class="text-base font-bold text-slate-300 mt-2">No Subject Allocations Found</h3>
              <p class="text-xs text-slate-500 mt-1">Contact the Department HOD or Academic Coordinator to assign workshop or lab batches.</p>
            </div>
          @endforelse
        </div>

      </div>

      <!-- PANEL 2: WORKSHOP STAFF DIRECTORY -->
      <div id="panelStaff" class="hidden space-y-6">
        
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Workshop Staff — Trade Instructors (All Branches)</h3>
            <p class="text-xs text-slate-400 mt-0.5">Manage approval, suspension, password reset, and registration of Trade Instructor accounts across all departments.</p>
          </div>
          <button onclick="openRegisterStaffModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-xs">
            <span class="material-symbols-rounded text-sm">person_add</span> Register Instructor
          </button>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
            <input type="text" id="staffSearch" oninput="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="Name or Mobile No...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="staffStatus" onchange="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile ID</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Designation</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="staffTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: MY PROFILE & SECURITY -->
      <div id="panelSecurity" class="hidden space-y-6">
        @include('partials.staff_profile_panel')
      </div>

    </div>

    <!-- Scroll to Top Floating Button -->
    <button id="scrollToTopBtn" onclick="document.getElementById('mainPanelContainer').scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-6 right-6 z-40 hidden p-3 rounded-full bg-blue-600 hover:bg-blue-500 text-white shadow-xl border border-blue-400/40 transition-premium cursor-pointer" title="Scroll to top">
      <span class="material-symbols-rounded text-lg">arrow_upward</span>
    </button>
  </main>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>
      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>
      <div id="pwdAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>
      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>
      <div class="space-y-3">
        <p class="text-xs text-slate-400">History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).</p>
        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-950/80 border-b border-slate-800 text-slate-400 font-bold">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody"></tbody>
          </table>
        </div>
      </div>
      <div class="flex pt-2">
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- REGISTER INSTRUCTOR MODAL -->
  <div id="registerStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register Trade Instructor
        </h3>
        <button onclick="closeRegisterStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="registerStaffForm" onsubmit="handleRegisterStaff(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="regStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="regStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
            <input type="text" id="regStaffMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="10-digit number">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="regStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
              <option value="EL">Electronics (EL)</option>
              <option value="ME">Mechanical (ME)</option>
              <option value="CE">Civil (CE)</option>
              <option value="EEE">Electrical (EEE)</option>
              <option value="CT">Computer (CT)</option>
              <option value="AU">Automobile (AU)</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="regStaffPassword" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="e.g. trade123">
        </div>
        <div id="regStaffAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-xs">
            <span>Register Instructor</span>
            <div id="regStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = 'overview';
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadStats();

      // Scroll to top button visibility tracking
      const panel = document.getElementById('mainPanelContainer');
      const topBtn = document.getElementById('scrollToTopBtn');
      if (panel && topBtn) {
        panel.addEventListener('scroll', () => {
          if (panel.scrollTop > 240) {
            topBtn.classList.remove('hidden');
          } else {
            topBtn.classList.add('hidden');
          }
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
      const panels = ['overview', 'staff', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-semibold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link cursor-pointer";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mobile-link";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'overview': 'Assigned Batch Classes',
        'staff': 'Workshop Staff Directory',
        'security': 'My Profile & Security'
      };
      if (document.getElementById('panelTitle')) {
        document.getElementById('panelTitle').innerText = titles[panelId] || 'Workshop Desk';
      }

      // Scroll smoothly to top on panel change
      const container = document.getElementById('mainPanelContainer');
      if (container) container.scrollTo({ top: 0, behavior: 'smooth' });

      if (panelId === 'staff') loadStaff();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      if (!alert) return;
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm text-xs";
      } else {
        alert.className = "p-4 rounded-xl font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm text-xs";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function filterBatchCards(filter) {
      const cards = document.querySelectorAll('.batch-card');
      const pillAll = document.getElementById('pillAll');
      const pillR26 = document.getElementById('pillR26');
      const pillR21 = document.getElementById('pillR21');

      // Update pill classes
      const activeClass = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium bg-blue-600 text-white shadow-sm cursor-pointer";
      const inactiveClass = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white cursor-pointer";

      if (pillAll) pillAll.className = (filter === 'all') ? activeClass : inactiveClass;
      if (pillR26) pillR26.className = (filter === 'r26') ? activeClass : inactiveClass;
      if (pillR21) pillR21.className = (filter === 'r21') ? activeClass : inactiveClass;

      cards.forEach(card => {
        const rev = card.getAttribute('data-rev');
        if (filter === 'all') {
          card.classList.remove('hidden');
        } else if (filter === 'r26') {
          if (rev === 'r26') card.classList.remove('hidden');
          else card.classList.add('hidden');
        } else if (filter === 'r21') {
          if (rev === 'r21') card.classList.remove('hidden');
          else card.classList.add('hidden');
        }
      });
    }

    function searchBatchCards(query) {
      const q = (query || '').toLowerCase().trim();
      const cards = document.querySelectorAll('.batch-card');

      cards.forEach(card => {
        const branch = (card.getAttribute('data-branch') || '').toLowerCase();
        const code = (card.getAttribute('data-code') || '').toLowerCase();
        const name = (card.getAttribute('data-name') || '').toLowerCase();
        const classroom = (card.getAttribute('data-classroom') || '').toLowerCase();
        const cardText = card.innerText.toLowerCase();

        if (!q || branch.includes(q) || code.includes(q) || name.includes(q) || classroom.includes(q) || cardText.includes(q)) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    }

    function loadStats() {
      // Count workshop staff (Trade Instructors)
      fetch('/api/admin/users?role=Trade_Instructor')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const el = document.getElementById('statWorkshopStaff');
            if (el) el.innerText = data.users.length;
          }
        })
        .catch(() => {});
    }

    function loadStaff() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');
      const search = document.getElementById('staffSearch')?.value || '';
      const status = document.getElementById('staffStatus')?.value || '';

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&role=Trade_Instructor&status=${status}`)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') renderStaffTable(data.users);
        })
        .catch(() => {
          if (indicator) indicator.classList.add('hidden');
        });
    }

    function renderStaffTable(users) {
      const tbody = document.getElementById('staffTableBody');
      if (!tbody) return;
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-medium">No Trade Instructors found.</td></tr>`;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        else if (user.status === 'Suspended') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">Approve</button>`;
        } else if (user.status === 'Approved') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded text-[10px] font-bold text-red-300 transition-premium cursor-pointer">Suspend</button>`;
        } else if (user.status === 'Suspended') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">Activate</button>`;
        }

        tr.innerHTML = `
          <td class="p-4 flex items-center gap-3">
            <div class="relative shrink-0">
              <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
              ${user.is_online ? `<span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-slate-900 rounded-full shadow-sm ring-1 ring-emerald-400/40" title="Online"></span>` : ''}
            </div>
            <div>
              <span class="font-bold text-slate-100 block">${user.name}</span>
              <span class="text-[10px] text-slate-500 block">${user.email}</span>
            </div>
          </td>
          <td class="p-4 font-mono font-bold text-slate-300">${user.id}</td>
          <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-4 text-slate-300 text-[10px]">${user.role}</td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', 'staff', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Reset Pwd</button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Audit</button>
            <button onclick="confirmDeleteUser('${user.id}', 'staff', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded text-[10px] font-bold transition-premium cursor-pointer">Delete</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function changeStatus(userId, userType, newStatus) {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');
      fetch('/api/admin/user/toggle-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Status updated successfully.');
          if (activePanel === 'staff') loadStaff();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => { 
        if (indicator) indicator.classList.add('hidden'); 
        showGlobalMessage('Failed to update status.', true); 
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
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
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
        body: JSON.stringify({ userId: selectedUserForReset.userId, userType: selectedUserForReset.userType, newPassword: pwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') { showGlobalMessage('Password reset successfully.'); closePasswordModal(); }
        else { pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = data.message; pwdAlert.classList.remove('hidden'); }
      })
      .catch(() => { pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = "Request failed."; pwdAlert.classList.remove('hidden'); });
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Permanently delete profile of ${userName} (${userId})? This cannot be undone.`)) {
        const indicator = document.getElementById('loadingIndicator');
        if (indicator) indicator.classList.remove('hidden');
        fetch('/api/admin/user/delete', { method: 'POST', headers: getHeaders(), body: JSON.stringify({ targetId: userId, userType }) })
          .then(res => res.json())
          .then(data => {
            if (indicator) indicator.classList.add('hidden');
            if (data.status === 'SUCCESS') { showGlobalMessage('Profile deleted successfully.'); if (activePanel === 'staff') loadStaff(); }
            else showGlobalMessage(data.message, true);
          })
          .catch(() => { 
            if (indicator) indicator.classList.add('hidden'); 
            showGlobalMessage('Failed to delete.', true); 
          });
      }
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
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-3 text-slate-400 font-mono">${date}</td><td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td><td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-3 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`);
    }

    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      if (!tbody) return;
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;
      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-3 text-slate-400 font-mono">${date}</td><td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-3 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`);
    }

    function openRegisterStaffModal() {
      document.getElementById('registerStaffForm').reset();
      document.getElementById('regStaffAlert').classList.add('hidden');
      const modal = document.getElementById('registerStaffModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterStaffModal() {
      document.getElementById('registerStaffModal').classList.add('hidden');
      document.getElementById('registerStaffModal').classList.remove('flex');
    }

    function handleRegisterStaff(e) {
      e.preventDefault();
      const alert = document.getElementById('regStaffAlert');
      const spinner = document.getElementById('regStaffSpinner');
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const formData = new FormData();
      formData.append('name', document.getElementById('regStaffName').value);
      formData.append('email', document.getElementById('regStaffEmail').value);
      formData.append('mobileNo', document.getElementById('regStaffMobile').value);
      formData.append('branch', document.getElementById('regStaffBranch').value);
      formData.append('designation', 'Trade_Instructor');
      formData.append('password', document.getElementById('regStaffPassword').value);

      fetch('/register/staff', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Trade Instructor registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => { closeRegisterStaffModal(); loadStaff(); }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }
  </script>
  @include('partials.support_desk_overlay')
  @include('partials.birthday_wish_modal')
</body>
</html>
