<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - General Coordinator (Self Finance) Dashboard</title>
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
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Coordinator (SF)</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-teal-400 block uppercase tracking-wider">General SF Coordinator</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-3 space-y-1">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs mobile-link">
        <span class="material-symbols-rounded text-base">dashboard</span> Overview
      </button>

      <a href="/dashboard/lecturer" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">grid_view</span> My Batches
      </a>

      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">group</span> User Directory
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">diversity_3</span> My Mentoring
      </a>
      @endif

      <a href="/staff/attendance-log" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">co_present</span> Class Attendance Log
      </a>

      <a href="/remedial-sessions" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">health_and_safety</span> Remedial Sessions
      </a>

      <a href="/course-files" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">folder_open</span> Course Files (2021)
      </a>

      <a href="/dashboard/academic-coordinator" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-indigo-400 hover:bg-indigo-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">verified_user</span> Academic Coordinator Desk
      </a>

      <a href="/sf-attendance/attendance-report" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-cyan-400 hover:bg-cyan-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">how_to_reg</span> SF Attendance Log
      </a>

      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-purple-400 hover:bg-purple-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">event_note</span> My Leave & Attendance Log
      </a>

      <a href="/staff/professional-activities" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-base">school</span> Professional Activities
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs mobile-link">
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
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Overview</h1>
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <div id="aiStatusBadge" class="hidden"></div>
        <div id="loadingIndicator" class="hidden items-center gap-2 text-[10px] text-slate-400 text-xs">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: OVERVIEW & MY BATCHES -->
      <div id="panelDashboard" class="space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl shadow-sm">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
              <h3 class="font-black text-slate-200 text-lg flex items-center gap-2">
                <span class="material-symbols-rounded text-teal-400">verified_user</span> General Department Coordinator (Self Finance) Console
              </h3>
              <p class="text-slate-400 text-xs mt-1">
                Assigned batches &amp; common subject teaching cards across Self Finance branches (R2021 &amp; R2026).
              </p>
            </div>

            <div class="flex items-center bg-slate-900 border border-slate-800 p-1 rounded-xl gap-1">
              <button id="btnActiveBatches" onclick="filterBatches('active')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-premium bg-teal-600 text-white shadow-sm">Active Batches</button>
              <button id="btnHistoricalBatches" onclick="filterBatches('historical')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white">Historical</button>
            </div>
          </div>

          <!-- Batch Grid -->
          <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading assigned batches...</div>
          </div>
        </div>
      </div>

      <!-- PANEL 2: DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Search staff name...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Roles</option>
              <option value="Lecturer">Lecturer</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Physical_Instructor">Physical Instructor</option>
              <option value="Trade_Instructor">Trade Instructor</option>
            </select>
          </div>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-4">Profile</th>
                <th class="p-4">Mobile</th>
                <th class="p-4">Branch</th>
                <th class="p-4">Role Designation</th>
                <th class="p-4 text-right">Account Status</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <!-- Dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- PANEL 3: MY PROFILE -->
      <div id="panelSecurity" class="hidden space-y-6">
        @include('partials.staff_profile_panel')
      </div>

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';
    let currentDashboardFilter = 'active';

    document.addEventListener("DOMContentLoaded", () => {
      loadLecturerBatches();
      if (activePanel === 'directory') loadUsers();
      if (activePanel === 'security' && typeof loadSelfSecurityLogs === 'function') loadSelfSecurityLogs();
    });

    function filterBatches(status) {
      currentDashboardFilter = status;
      const btnActive = document.getElementById('btnActiveBatches');
      const btnHist = document.getElementById('btnHistoricalBatches');
      if (status === 'active') {
        btnActive.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium bg-teal-600 text-white shadow-sm";
        btnHist.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white";
      } else {
        btnHist.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium bg-teal-600 text-white shadow-sm";
        btnActive.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-400 hover:text-white";
      }
      loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      if (!grid) return;
      grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading assigned batches...</div>';

      fetch(`/api/lecturer/my-batches?status=${currentDashboardFilter}`, {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-xs">${data.message}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-xs">Error loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      if (!grid) return;
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
            <span class="material-symbols-rounded text-5xl text-slate-700 mb-3">sentiment_dissatisfied</span>
            <p class="font-bold text-slate-300 text-sm">No batches assigned</p>
            <p class="text-xs text-slate-500 mt-1">You have not been assigned as a Tutor, Mentor, or Subject Staff for any batches yet.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let color = 'slate';
          if (r === 'Tutor') color = 'sky';
          if (r === 'Mentor') color = 'emerald';
          if (r === 'Subject Staff') color = 'violet';
          rolesHtml += `<span class="px-2 py-0.5 rounded text-[11px] font-bold bg-${color}-500/10 text-${color}-400 border border-${color}-500/20">${r}</span>`;
        });

        let subjectsHtml = '';
        if (b.subjects && b.subjects.length > 0) {
          b.subjects.forEach((s, idx) => {
            let topicsPct = s.total_topics > 0 ? Math.round((s.covered_topics / s.total_topics) * 100) : 0;
            let hoursPct  = s.total_hours  > 0 ? Math.round((s.engaged_hours  / s.total_hours)  * 100) : 0;
            let barPct    = topicsPct || hoursPct;
            let barColor  = barPct >= 80 ? 'from-emerald-500 to-teal-400' : barPct >= 50 ? 'from-blue-500 to-sky-400' : 'from-violet-500 to-indigo-400';
            let revCode   = s.syllabus_revision_code || (b.scheme === 'R2026' ? 'REV2026' : (b.classroom_id && b.classroom_id.includes('2026') ? 'REV2026' : 'REV2021'));
            let cleanName = (s.name || '').replace(/'/g, "\\'");

            subjectsHtml += `
              <div class="${idx > 0 ? 'pt-3' : ''} w-full">
                <div class="w-full px-3.5 py-3 bg-slate-900/80 border border-slate-800 rounded-xl transition-premium group hover:border-teal-500/50 hover:bg-slate-900 flex flex-col gap-2">
                  <div class="flex justify-between items-center cursor-pointer" onclick="openClassroom('${b.classroom_id}', '${s.id}', '${cleanName}', '${s.code}', '${revCode}', '${s.type}')">
                    <div class="flex-1 min-w-0 pr-2">
                      <div class="text-base font-extrabold text-slate-200 group-hover:text-teal-400 transition-premium truncate">${s.name}</div>
                      <div class="text-xs text-slate-400 font-mono mt-0.5">Sem ${s.semester} · ${s.type} · ${s.code}</div>
                    </div>
                    <span class="material-symbols-rounded text-slate-600 group-hover:text-teal-500 text-base transition-premium flex-shrink-0">open_in_new</span>
                  </div>
                  <div class="flex items-center gap-2 mt-1">
                    <div class="flex-1 bg-slate-950 rounded-full h-1.5 overflow-hidden border border-slate-900">
                      <div class="bg-gradient-to-r ${barColor} h-1.5 rounded-full transition-all duration-500" style="width: ${barPct}%"></div>
                    </div>
                    <span class="text-[11px] font-bold text-slate-400 whitespace-nowrap flex-shrink-0">${s.engaged_hours}/${s.total_hours} hrs</span>
                  </div>
                </div>
              </div>
            `;
          });
        } else {
          subjectsHtml = `<div class="text-xs text-slate-500 italic px-2 py-2">No subjects assigned in this batch.</div>`;
        }

        const card = document.createElement('div');
        let yearBorderColor = 'border-t-violet-500';
        if (b.batch_year % 3 === 0) yearBorderColor = 'border-t-sky-500';
        else if (b.batch_year % 3 === 1) yearBorderColor = 'border-t-emerald-500';
        
        card.className = `bg-slate-950/40 border border-slate-800/80 ${yearBorderColor} border-t-[3px] rounded-2xl overflow-hidden flex flex-col transition-premium hover:shadow-xl hover:shadow-black/50 hover:border-slate-700/60`;
        card.innerHTML = `
          <div class="p-4 border-b border-slate-800/60 bg-slate-900/40">
            <div class="flex justify-between items-start">
              <div>
                <div class="flex items-center gap-1.5 flex-wrap mb-1">
                  <h4 class="font-black text-slate-100 text-lg tracking-tight">Admission ${b.batch_year}</h4>
                  ${b.branch ? `<span class="px-2 py-0.5 bg-sky-500/15 text-sky-300 border border-sky-500/30 rounded text-xs font-bold font-mono tracking-wide">${b.branch}</span>` : ''}
                  <span class="px-2 py-0.5 bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 rounded text-xs font-bold font-mono tracking-wide">${b.scheme || (b.classroom_id && b.classroom_id.includes('2026') ? 'R2026' : 'R2021')}</span>
                  ${(b.current_semester || 1) > 6
                    ? `<span class="px-2.5 py-0.5 bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 rounded-lg font-bold text-sm select-none flex items-center gap-1"><span class="material-symbols-rounded" style="font-size:14px">school</span>Graduated</span>`
                    : `<span class="px-2.5 py-0.5 bg-indigo-600/80 text-white rounded-lg font-bold text-sm select-none">S-${b.current_semester || 1}</span>`
                  }
                </div>
                <span class="inline-block px-2.5 py-0.5 bg-slate-800 border border-slate-600/60 rounded-lg font-mono text-sm font-bold text-slate-300 tracking-wide">${b.classroom_id}</span>
              </div>
              <div class="flex flex-col items-end gap-1">
                <div class="flex flex-wrap gap-1 justify-end">${rolesHtml}</div>
                <span class="flex items-center gap-1 text-xs font-bold text-slate-400 mt-1">
                  <span class="material-symbols-rounded" style="font-size:13px">group</span>${b.student_count || 0} students
                </span>
              </div>
            </div>
          </div>
          
          <div class="p-4 flex-grow space-y-3 bg-slate-950/20">
            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><span class="material-symbols-rounded text-xs">book</span> Assigned Subjects</h5>
            <div class="space-y-3 divide-y divide-slate-800/80">
              ${subjectsHtml}
            </div>
          </div>
        `;

        grid.appendChild(card);
      });
    }

    function openClassroom(batchId, subjectId, subjectName, subjectCode, revision = 'REV2021', type = 'Theory') {
      const sTypeLower = (type || '').toLowerCase();
      const sNameLower = (subjectName || '').toLowerCase();
      const isR26 = revision === 'REV2026' || (batchId && batchId.includes('2026'));

      if (isR26) {
        if (sNameLower.includes('health') || sNameLower.includes('physical') || sTypeLower.includes('health') || sTypeLower.includes('physical')) {
          window.open(`/r26/classroom/health-physical/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('drawing') || sNameLower.includes('drawing') || sNameLower.includes('graphics') || sNameLower.includes('cad')) {
          window.open(`/r26/classroom/drawing/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('practicum') || type.includes('Practicum')) {
          window.open(`/r26/classroom/practicum/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('practical') || sTypeLower.includes('lab') || type.includes('Practical') || type.includes('Lab')) {
          window.open(`/r26/classroom/practical/${subjectId}`, '_blank');
          return;
        } else {
          window.open(`/r26/classroom/theory/${subjectId}`, '_blank');
          return;
        }
      } else {
        window.location.href = `/dashboard/lecturer?subject_id=${subjectId}`;
      }
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 mobile-link";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mobile-link";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Overview',
        'directory': 'User Directory',
        'security': 'My Profile & Security'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Overview';
      if (panelId === 'directory') loadUsers();
      if (panelId === 'security' && typeof loadSelfSecurityLogs === 'function') loadSelfSecurityLogs();
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('filterSearch').value;
      const role = document.getElementById('filterRole').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&branch=GEN_SF&role=${role}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            if (data.users.length === 0) {
              tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500">No staff found.</td></tr>';
              return;
            }
            data.users.forEach(user => {
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
                <td class="p-4 font-mono text-slate-300">${user.id}</td>
                <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                <td class="p-4 text-slate-300">${user.role}</td>
                <td class="p-4 text-right"><span class="px-2 py-0.5 rounded-full text-[10px] bg-green-500/10 text-green-400 border border-green-500/20">${user.status}</span></td>
              `;
              tbody.appendChild(tr);
            });
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }
  </script>
  @include('partials.support_desk_overlay')
</body>
</html>
