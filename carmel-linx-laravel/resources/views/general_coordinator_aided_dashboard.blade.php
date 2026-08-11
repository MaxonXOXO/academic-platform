<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - General Coordinator (Aided) Dashboard</title>
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
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Coordinator (Aided)</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-blue-400 block uppercase tracking-wider">General Aided Coord</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-3 space-y-1">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs mobile-link">
        <span class="material-symbols-rounded text-base">dashboard</span> Overview
      </button>
      <a href="/dashboard/lecturer" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">school</span> My Batches
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
      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-purple-400 hover:bg-purple-900/30 cursor-pointer no-underline block text-xs mobile-link">
        <span class="material-symbols-rounded text-base">event_note</span> My Leave & Attendance Log
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="{{ url('/logout') }}" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-[10px] flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-[10px] text-xs">
        <span class="material-symbols-rounded text-[10px] text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Overview</h1>
      <div id="loadingIndicator" class="hidden items-center gap-2 text-[10px] text-slate-400 text-[10px] text-xs">
        <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span>Syncing...</span>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
          <span class="material-symbols-rounded text-blue-400 block mb-3 text-5xl">verified_user</span>
          <h3 class="font-black text-slate-200 text-lg">General Department Coordinator (Aided) Console Connected</h3>
          <p class="text-slate-400 text-[10px] mt-2 font-medium text-sm">
            Welcome! As the selected Aided General Department Coordinator, you have HOD-like coordinates over academic staff assigned to General Department Aided.
          </p>
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

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (activePanel === 'directory') loadUsers();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory'];
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

      document.getElementById('panelTitle').innerText = panelId === 'dashboard' ? 'Overview' : 'User Directory';
      if (panelId === 'directory') loadUsers();
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('filterSearch').value;
      const role = document.getElementById('filterRole').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&branch=GEN_AIDED&role=${role}`)
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
</body>
</html>
