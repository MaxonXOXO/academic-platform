<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Admin Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
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
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-hidden">
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
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Admin Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-teal-400 block uppercase tracking-wider">Academic Admin</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">shield_person</span> Admin Overview
      </button>
      <button id="navCourseFileLibrary" onclick="switchPanel('courseFileLibrary')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">library_books</span> Course File Library
      </button>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">security</span> My Security Log
      </button>

      <a href="/sf-attendance/attendance-report" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-cyan-300 cursor-pointer no-underline block border border-cyan-900/40 bg-cyan-950/20">
         <span class="material-symbols-rounded text-lg text-cyan-400">how_to_reg</span> SF Staff Attendance Log
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
      <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Admin Overview</h1>
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
        <!-- Theme Toggle Button -->
        <button id="themeToggleBtn" onclick="toggleTheme()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm" title="Toggle Light/Dark Mode">
          <span id="themeToggleIcon" class="material-symbols-rounded text-base text-amber-400">light_mode</span>
          <span id="themeToggleText" class="hidden sm:inline">Light Mode</span>
        </button>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD -->
      <div id="panelDashboard" class="space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
          <span class="material-symbols-rounded text-blue-500 block mb-3 text-5xl">shield_person</span>
          <h3 class="font-black text-slate-200 text-lg">Academic Admin Console Connected</h3>
          <p class="text-slate-400 text-[10px] mt-2 font-medium text-sm">
            This workspace is ready. You can manage course allocation schemas, view system-wide statistics, and verify academic term calendars.
          </p>
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG -->
      <div id="panelSecurity" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
            <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Profile Security Audit trail
          </h3>
          <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                  <th class="p-4">Time</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="securityLogsTable">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
      </div>

      <!-- PANEL: COURSE FILE LIBRARY -->
      <div id="panelCourseFileLibrary" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between">
          <div>
            <h3 class="text-[10px] font-black text-slate-200 flex items-center gap-2 mt-1 text-sm">
              <span class="material-symbols-rounded text-amber-400 text-lg">library_books</span> Course File Library
            </h3>
            <p class="text-[10px] text-slate-400 font-medium">Browse and download finalized NBA Course Files for all batches.</p>
          </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 text-center text-slate-400">
            <span class="material-symbols-rounded text-amber-500/50 block mb-3 text-5xl">construction</span>
            <h3 class="font-bold text-slate-200 text-lg">Library Under Construction</h3>
            <p class="text-[10px] mt-2 text-[10px] text-xs">The Admin viewer will be fully implemented in a future update to allow batch-wise filtering.</p>
        </div>
      </div>

</main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (activePanel === 'security') loadSecurityLogs();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security'];
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
        'dashboard': 'Admin Overview',
        'security': 'My Profile Security Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'security') loadSecurityLogs();
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-[10px] hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
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

    document.addEventListener('DOMContentLoaded', initTheme);
  </script>
</body>
</html>
