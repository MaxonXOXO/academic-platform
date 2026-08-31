<!DOCTYPE html>
<html lang="en">
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
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-5 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#60a5fa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Workshop Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-amber-400 block uppercase tracking-wider">Workshop Superintendent</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navOverview" onclick="switchPanel('overview')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-semibold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link">
        <span class="material-symbols-rounded text-lg">dashboard</span> Workshop Overview
      </button>
      <button id="navStaff" onclick="switchPanel('staff')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">badge</span> Workshop Staff
      </button>
      <button id="navStudents" onclick="switchPanel('students')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mobile-link">
        <span class="material-symbols-rounded text-lg">group</span> Student Roster
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">receipt_long</span> Audit Trail
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

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
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
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Workshop Overview</h1>
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <div id="loadingIndicator" class="hidden items-center gap-2 text-[10px] text-slate-400 text-[10px] text-xs">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">

      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-[10px] font-bold transition-premium border text-[10px] text-xs"></div>

      <!-- PANEL 1: OVERVIEW -->
      <div id="panelOverview" class="space-y-6">
        
        <!-- Info Metric Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-blue-500/10 text-blue-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">handyman</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Workshop Staff</span>
              <span id="statWorkshopStaff" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-sky-500/10 text-sky-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">group</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Total Students</span>
              <span id="statTotalStudents" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">pending_actions</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Pending Approvals</span>
              <span id="statPending" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
        </div>

        <!-- Welcome Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-amber-400 text-lg">factory</span> Workshop Superintendent Desk
            </h3>
            <p class="text-[10px] text-slate-400 leading-relaxed text-[10px] text-xs">
              As <strong class="text-slate-200">{{ session('userName') }}</strong>, you oversee all Mechanical Workshop activities across branches. You can manage Trade Instructor accounts, review student workshop rosters, authorize staff to sections, and view cross-branch audit records.
            </p>
            <div class="mt-4 flex gap-3 flex-wrap">
              <button onclick="switchPanel('staff')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-[10px] font-bold text-white transition-premium cursor-pointer">Manage Workshop Staff</button>
              <button onclick="switchPanel('students')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-[10px] font-bold text-slate-300 transition-premium cursor-pointer">View Student Roster</button>
            </div>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-blue-400 text-lg">info</span> Role Scope & Upcoming Features
            </h3>
            <ul class="text-[10px] text-slate-400 space-y-2 list-disc pl-4 leading-relaxed text-[10px] text-xs">
              <li>Cross-branch authority over all <strong class="text-slate-300">Trade Instructors</strong> — approve, suspend, reset passwords, or revoke access.</li>
              <li>Workshop Section Management — create and assign sections for each class batch (coming soon).</li>
              <li>Staff-to-Section allocation — authorize which instructor handles which batch section (coming soon).</li>
              <li>Evaluation & Test Reports from each batch per instructor (coming soon).</li>
              <li>View full cross-branch student roster for workshop tracking.</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- PANEL 2: WORKSHOP STAFF DIRECTORY -->
      <div id="panelStaff" class="hidden space-y-6">
        
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-[10px] font-black text-slate-200">Workshop Staff — Trade Instructors (All Branches)</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Manage approval, suspension, password reset, and deletion of Trade Instructor accounts across all departments.</p>
          </div>
          <button onclick="openRegisterStaffModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-[10px] text-xs">
            <span class="material-symbols-rounded text-[10px] text-sm">person_add</span> Register Instructor
          </button>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
            <input type="text" id="staffSearch" oninput="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Name or Mobile No...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="staffStatus" onchange="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
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

      <!-- PANEL 3: STUDENT ROSTER -->
      <div id="panelStudents" class="hidden space-y-6">

        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-[10px] font-black text-slate-200 text-[10px] text-xs">Student Workshop Roster (All Branches)</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">View and manage students assigned to workshop activities across all branches.</p>
          </div>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-4 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Student</label>
            <input type="text" id="studentSearch" oninput="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Name, Register No...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Filter</label>
            <select id="studentBranch" onchange="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Branches</option>
              <option value="EL">Electronics (EL)</option>
              <option value="ME">Mechanical (ME)</option>
              <option value="CE">Civil (CE)</option>
              <option value="EEE">Electrical (EEE)</option>
              <option value="CT">Computer (CT)</option>
              <option value="AU">Automobile (AU)</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester Filter</label>
            <select id="studentSemester" onchange="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Semesters</option>
              <option value="S1">Semester 1 (S1)</option>
              <option value="S2">Semester 2 (S2)</option>
              <option value="S3">Semester 3 (S3)</option>
              <option value="S4">Semester 4 (S4)</option>
              <option value="S5">Semester 5 (S5)</option>
              <option value="S6">Semester 6 (S6)</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="studentStatus" onchange="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Register No</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="studentTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 4: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-[10px] text-sm">Cross-Branch Workshop Audit Trail</h3>
            <p class="text-[10px] text-slate-400 mt-1 text-[10px] text-xs">All lifecycle events, status changes, and password resets across the system.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-[10px] text-sm">sync</span> Refresh Log
          </button>
        </div>

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

      <!-- PANEL 5: MY PROFILE & SECURITY -->
      <div id="panelSecurity" class="hidden space-y-6">
        @include('partials.staff_profile_panel')
      </div>

    </div>
  </main>

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
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).</p>
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
            <tbody id="modalAuditTableBody"></tbody>
          </table>
        </div>
      </div>
      <div class="flex pt-2">
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- REGISTER INSTRUCTOR MODAL -->
  <div id="registerStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register Trade Instructor
        </h3>
        <button onclick="closeRegisterStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="registerStaffForm" onsubmit="handleRegisterStaff(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="regStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="regStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
            <input type="text" id="regStaffMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="10-digit number">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="regStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
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
          <input type="text" id="regStaffPassword" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. trade123">
        </div>
        <div id="regStaffAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-[10px] text-xs">
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
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['overview', 'staff', 'students', 'audit', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-[10px] flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'overview': 'Workshop Overview',
        'staff': 'Workshop Staff â Trade Instructors',
        'students': 'Student Workshop Roster',
        'audit': 'Cross-Branch Audit Trail',
        'security': 'My Profile & Security'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'staff') loadStaff();
      if (panelId === 'students') loadStudents();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

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

    function loadStats() {
      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPending').innerText = data.stats.pendingApprovals;
          }
        });

      // Count workshop staff (Trade Instructors)
      fetch('/api/admin/users?role=Trade_Instructor')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('statWorkshopStaff').innerText = data.users.length;
          }
        });
    }

    function loadStaff() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('staffSearch').value;
      const status = document.getElementById('staffStatus').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&role=Trade_Instructor&status=${status}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') renderStaffTable(data.users);
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderStaffTable(users) {
      const tbody = document.getElementById('staffTableBody');
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
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
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

    function loadStudents() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('studentSearch').value;
      const branch = document.getElementById('studentBranch').value;
      const status = document.getElementById('studentStatus').value;
      const semester = document.getElementById('studentSemester')?.value || '';

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&role=student&branch=${branch}&status=${status}&semester=${semester}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') renderStudentTable(data.users);
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderStudentTable(users) {
      const tbody = document.getElementById('studentTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500 font-medium">No students found.</td></tr>`;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        else if (user.status === 'Suspended') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

        tr.innerHTML = `
          <td class="p-4 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
            <div>
              <span class="font-bold text-slate-100 block">${user.name}</span>
              <span class="text-[10px] text-slate-500 block">${user.email}</span>
            </div>
          </td>
          <td class="p-4 font-mono font-bold text-slate-300">${user.id}</td>
          <td class="p-4">
            <div class="flex items-center gap-1.5 flex-wrap">
              <span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span>
              <span class="font-bold font-mono text-[10px] bg-indigo-950 text-indigo-300 px-2 py-0.5 rounded border border-indigo-800/60">${user.semester || 'N/A'}</span>
            </div>
          </td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Audit</button>
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
          showGlobalMessage('Status updated successfully.');
          if (activePanel === 'staff') loadStaff();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => { indicator.classList.add('hidden'); showGlobalMessage('Failed to update status.', true); });
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
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
        else { pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = data.message; pwdAlert.classList.remove('hidden'); }
      })
      .catch(() => { pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = "Request failed."; pwdAlert.classList.remove('hidden'); });
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Permanently delete profile of ${userName} (${userId})? This cannot be undone.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');
        fetch('/api/admin/user/delete', { method: 'POST', headers: getHeaders(), body: JSON.stringify({ targetId: userId, userType }) })
          .then(res => res.json())
          .then(data => {
            indicator.classList.add('hidden');
            if (data.status === 'SUCCESS') { showGlobalMessage('Profile deleted successfully.'); if (activePanel === 'staff') loadStaff(); }
            else showGlobalMessage(data.message, true);
          })
          .catch(() => { indicator.classList.add('hidden'); showGlobalMessage('Failed to delete.', true); });
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
              tr.className = "border-b border-slate-800/40 text-[10px]";
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

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying audit logs...</td></tr>`;
      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No audit logs found.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-4 text-slate-400 font-mono">${date}</td><td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-[10px] text-slate-500 font-mono">${log.performed_by || ''}</span></td><td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-[10px] text-blue-400 font-mono">${log.target_id}</span></td><td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-4 font-mono text-slate-400">${log.ip_address || '-'}</td><td class="p-4 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`);
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;
      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-[10px]";
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
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Trade Instructor registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => { closeRegisterStaffModal(); loadStaff(); }, 1500);
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
        alert.classList.remove('hidden');
      });
    }
  </script>
  @include('partials.support_desk_overlay')
</body>
</html>
