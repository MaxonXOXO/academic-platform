<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carmel Linx - [{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Program Core Practicum - {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Tailwind CSS Browser CDN (matching all other Carmel Linx modules) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr for dd/mm/yyyy Date Selection -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .material-symbols-rounded {
            font-family: 'Material Symbols Rounded', sans-serif;
            font-weight: normal;
            font-style: normal;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        :root {
            --bg-primary: #0f172a;       /* slate-900 (Lecturer Dashboard standard) */
            --bg-secondary: #020617;     /* slate-950 */
            --bg-card: #0f172a;          /* slate-900 */
            --bg-card-hover: #1e293b;    /* slate-800 */
            --border-color: #1e293b;     /* slate-800 */
            --border-color-glow: rgba(59, 130, 246, 0.35);
            --accent-cyan: #06b6d4;
            --accent-blue: #3b82f6;
            --accent-blue-light: #60a5fa;
            --accent-sky: #0ea5e9;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --text-main: #f1f5f9;        /* slate-100 */
            --text-muted: #94a3b8;       /* slate-400 */
        }

        /* Active Attendance Override Styling */
        .cell-att-override.has-override {
            border-color: #f59e0b !important;
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fbbf24 !important;
            box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.35) !important;
        }
        /* Strictly Hide Up/Down Arrows / Spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button,
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type="number"], .no-spinners {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Universal Anti-Glow and Crisp Font Rendering */
        *, *::before, *::after, h1, h2, h3, h4, h5, h6, .font-heading, .brand-font, span, p, label, button, a, th, td, div, strong, b, input, select, textarea {
            text-shadow: none !important;
            filter: none !important;
            -webkit-filter: none !important;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, .brand-font {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.01em;
            font-weight: 600 !important;
            text-shadow: none !important;
            filter: none !important;
        }

        .font-extrabold, .font-black {
            font-weight: 700 !important;
        }

        .glass-panel {
            background: rgba(2, 6, 23, 0.94);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(59, 130, 246, 0.35);
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.45);
        }

        /* Arch Shape Tab Navigation (Rev 2026 Standard - Strict Single Row) */
        .tab-nav-container {
            position: sticky;
            top: 76px;
            z-index: 35;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .tab-nav-container nav {
            overflow: hidden !important;
        }

        #practicum-tab-nav {
            overflow: hidden !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        #practicum-tab-nav::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        body.light .tab-nav-container {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }

        .tab-btn, .subtab-btn {
            position: relative;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .subtab-btn:hover {
            color: #e2e8f0;
        }

        .subtab-btn.active {
            color: #60a5fa !important;
            border-color: #60a5fa !important;
            border-bottom-color: transparent !important;
            background-color: rgba(59, 130, 246, 0.05) !important;
            box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.2) !important;
            font-weight: 700 !important;
        }

        body.light .subtab-btn.active {
            color: #2563eb !important;
            border-color: #3b82f6 !important;
            border-bottom-color: transparent !important;
            background-color: rgba(59, 130, 246, 0.08) !important;
            box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.15) !important;
        }

        body.light .subtab-btn:not(.active) {
            color: #64748b !important;
        }

        body.light .subtab-btn:not(.active):hover {
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
            background-color: rgba(241, 245, 249, 0.8) !important;
        }

        /* Dark Mode Form Inputs & Select Controls */
        body.dark input, body.dark select, body.dark textarea,
        body:not(.light) input, body:not(.light) select, body:not(.light) textarea {
            background-color: #020617 !important; /* slate-950 */
            border: 1px solid #1e293b !important; /* border-slate-800 */
            color: #f1f5f9 !important;            /* text-slate-100 */
            border-radius: 8px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        body.dark input:focus, body.dark select:focus, body.dark textarea:focus,
        body:not(.light) input:focus, body:not(.light) select:focus, body:not(.light) textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }

        /* Light Mode Full System Overrides (Clean, Contrast-Optimized) */
        body.light {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        body.light .glass-panel,
        body.light header {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            color: #0f172a !important;
        }

        body.light .glass-card {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.05) !important;
            color: #0f172a !important;
        }

        body.light .glass-card:hover {
            border-color: #cbd5e1 !important;
        }

        body.light #theme-toggle-btn {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #334155 !important;
        }

        body.light #theme-toggle-btn:hover {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        body.light .text-white {
            color: #0f172a !important;
        }

        body.light .text-slate-100,
        body.light .text-slate-200,
        body.light .text-slate-300 {
            color: #1e293b !important;
        }

        body.light .text-slate-400 {
            color: #64748b !important;
        }

        body.light .text-slate-500 {
            color: #94a3b8 !important;
        }

        body.light .bg-slate-950,
        body.light .bg-slate-900,
        body.light .bg-slate-900\/50,
        body.light .bg-slate-950\/70,
        body.light .bg-slate-950\/90 {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }

        body.light .bg-slate-800 {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #334155 !important;
        }

        body.light .bg-slate-800\/40,
        body.light .bg-slate-800\/80,
        body.light .bg-slate-800\/90 {
            background-color: #f8fafc !important;
        }

        body.light .border-slate-800,
        body.light .border-slate-800\/80,
        body.light .border-slate-700,
        body.light .border-slate-700\/80,
        body.light .border-slate-700\/60 {
            border-color: #e2e8f0 !important;
        }

        body.light .divide-slate-800,
        body.light .divide-slate-800\/80 {
            border-color: #f1f5f9 !important;
        }

        /* Light Mode Table Design */
        body.light table {
            border-color: #e2e8f0 !important;
        }

        body.light table thead tr,
        body.light table thead tr.bg-slate-950,
        body.light table thead tr.bg-slate-950\/90 {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }

        body.light table thead th {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #334155 !important;
        }

        body.light table tbody {
            background-color: #ffffff !important;
        }

        body.light table tbody tr {
            border-color: #f1f5f9 !important;
        }

        body.light table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        body.light table td {
            border-color: #f1f5f9 !important;
            color: #1e293b !important;
        }

        /* Light Mode Pastel Headers for Series & Sections */
        body.light th.bg-blue-950\/30, body.light th.bg-blue-950\/20, body.light th.bg-blue-950\/40 {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        body.light th.bg-indigo-950\/30, body.light th.bg-indigo-950\/40, body.light th.bg-indigo-950\/20 {
            background-color: #eef2ff !important;
            color: #4338ca !important;
        }
        body.light th.bg-teal-950\/20, body.light th.bg-teal-950\/40 {
            background-color: #f0fdfa !important;
            color: #0f766e !important;
        }
        body.light th.bg-cyan-950\/30, body.light th.bg-cyan-950\/40, body.light th.bg-cyan-950\/20 {
            background-color: #ecfeff !important;
            color: #0e7490 !important;
        }
        body.light th.bg-emerald-950\/30, body.light th.bg-emerald-950\/40, body.light th.bg-emerald-950\/20 {
            background-color: #f0fdf4 !important;
            color: #15803d !important;
        }
        body.light th.bg-amber-950\/20, body.light th.bg-amber-950\/40 {
            background-color: #fffbeb !important;
            color: #b45309 !important;
        }
        body.light th.bg-purple-950\/30, body.light th.bg-purple-950\/40, body.light th.bg-purple-950\/20 {
            background-color: #faf5ff !important;
            color: #7e22ce !important;
        }
        body.light th.bg-sky-950\/40, body.light th.bg-sky-950\/20 {
            background-color: #f0f9ff !important;
            color: #0369a1 !important;
        }

        /* Light Mode Form Inputs & Textareas */
        body.light input,
        body.light select,
        body.light textarea {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 8px !important;
        }

        body.light input:focus,
        body.light select:focus,
        body.light textarea:focus {
            border-color: #3b82f6 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }

        body.light input::placeholder,
        body.light textarea::placeholder {
            color: #94a3b8 !important;
        }

        /* Light Mode Table Badges and Rows */
        body.light .st-s1-tot, body.light .st-s2-tot {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        body.light .sp-s1-tot, body.light .sp-s2-tot {
            background-color: #ecfeff !important;
            color: #0e7490 !important;
        }
        body.light .cell-cie-grand-total {
            background-color: #dcfce7 !important;
            border-color: #86efac !important;
            color: #15803d !important;
        }
        body.light a.bg-slate-900\/80, body.light a.bg-slate-900 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        body.light a.bg-slate-900\/80:hover, body.light a.bg-slate-900:hover {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        /* Flatpickr dark & light theme adjustments */
        body.dark .flatpickr-calendar,
        body:not(.light) .flatpickr-calendar {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.7) !important;
            border-radius: 12px !important;
        }
        body.dark .flatpickr-calendar .flatpickr-months,
        body:not(.light) .flatpickr-calendar .flatpickr-months {
            background: #0f172a !important;
        }

        body.light .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px !important;
        }
        body.light .flatpickr-calendar .flatpickr-months {
            background: #ffffff !important;
        }
        body.light .flatpickr-day {
            color: #0f172a !important;
        }
        body.light .flatpickr-day.today {
            border-color: #3b82f6 !important;
        }
        body.light .flatpickr-day.selected {
            background: #3b82f6 !important;
            color: #ffffff !important;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #020617;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        body.light .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        body.light .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }
        body.light .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media print {
            .no-print, header, .tab-nav-container, .floating-widget-btn, #carmieFloatingBtn {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .tab-panel {
                display: block !important;
            }
        }
    </style>
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('practicum_theme') || 'dark';
                if (savedTheme === 'light') {
                    document.documentElement.classList.add('light');
                } else {
                    document.documentElement.classList.remove('light');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col font-sans antialiased selection:bg-blue-600 selection:text-white">
<script>
    (function() {
        try {
            const savedTheme = localStorage.getItem('practicum_theme') || 'dark';
            if (savedTheme === 'light') {
                document.body.classList.remove('dark');
                document.body.classList.add('light');
            } else {
                document.body.classList.remove('light');
                document.body.classList.add('dark');
            }
        } catch(e) {}
    })();
</script>

    <!-- 1. TOP HEADER & SUBJECT IDENTITY -->
    <header class="glass-panel border-b border-slate-800/80 sticky top-0 z-40 px-3 sm:px-6 md:px-8 py-3 flex items-center justify-between gap-3">
        <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
            <a href="{{ $dashboardUrl ?? '/dashboard/lecturer' }}" class="flex items-center gap-2.5 shrink-0 no-underline text-white group" title="Return to Lecturer Dashboard">
                <img src="/logo.jpg" class="w-8 h-8 rounded-lg object-cover shadow-sm group-hover:scale-105 transition-transform" onerror="this.style.display='none'">
                <div class="flex flex-col">
                    <span class="text-xs font-black uppercase tracking-wider text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/30 leading-none">Carmel Linx</span>
                    <span class="text-[10px] text-slate-400 font-bold tracking-tight">Virtual Practicum</span>
                </div>
            </a>
            <div class="h-8 w-px bg-slate-800 hidden sm:block shrink-0 mx-1"></div>
            <div class="min-w-0">
                <div class="flex items-center space-x-2 flex-wrap">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-blue-500/10 text-blue-400 border border-blue-500/20">
                        {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'REV-2021' : 'REV-2026' }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        PROGRAM CORE PRACTICUM
                    </span>
                    <span class="font-mono text-xs font-semibold text-slate-400">[{{ $batchSubject->subject_code }}]</span>
                </div>
                <h1 class="text-sm sm:text-base md:text-lg font-bold text-white tracking-tight truncate mt-0.5" title="{{ $batchSubject->subject_name }}">
                    {{ $batchSubject->subject_name }}
                </h1>
            </div>
        </div>

        <!-- Faculty Info & Right Controls -->
        <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
            <div class="hidden md:flex items-center space-x-2 text-slate-300">
                <div class="p-1 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center shrink-0 w-6 h-6">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="text-xs font-semibold max-w-[200px] truncate">
                    <span class="text-slate-400">Faculty:</span>
                    <span class="text-blue-400 font-bold">
                        {{ Session::get('userName') ?? 'Faculty In-Charge' }}
                        @if(isset($assignedStaff) && count($assignedStaff) > 0)
                            @foreach(($assignedStaff ?? []) as $stf)
                                @if($stf->name !== Session::get('userName'))
                                    , {{ $stf->name }}
                                @endif
                            @endforeach
                        @endif
                    </span>
                </div>
            </div>

            <!-- Dark / Light Theme Toggle (Desktop Only) -->
            <button type="button" onclick="toggleTheme()" class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all cursor-pointer border border-slate-700/60 shadow-xs" title="Toggle Dark / Light Mode" id="theme-toggle-btn">
                <span id="theme-icon" class="material-symbols-rounded text-sm">light_mode</span>
                <span id="theme-label" class="text-xs font-semibold hidden md:inline">Theme</span>
            </button>

            <!-- Back Button -->
            <a href="javascript:void(0)" onclick="window.close(); setTimeout(function() { let ref = document.referrer; if (ref && (ref.includes('/dashboard/') || ref.includes('/classroom/'))) { window.location.href = ref; } else { window.location.href = '{{ $dashboardUrl ?? '/dashboard/lecturer' }}'; } }, 150);" class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 font-bold transition-all flex items-center space-x-1.5 border border-rose-500/30 shrink-0 cursor-pointer no-underline text-xs" title="Go Back">
                <span class="material-symbols-rounded text-sm">arrow_back</span>
                <span>Back</span>
            </a>
        </div>
    </header>

    <!-- 2. SUB-HEADER METADATA RIBBON -->
    <div class="max-w-[99%] mx-auto px-2 sm:px-4 md:px-6 mt-3">
        <div class="glass-card p-3 sm:p-4 rounded-xl border border-slate-800 flex items-center justify-between flex-wrap gap-3">
            <div class="space-y-1">
                <p class="text-slate-400 leading-tight mb-0 flex items-center flex-wrap gap-2 text-xs sm:text-sm">
                    <span>Batch Code: <span class="text-amber-300 font-bold font-mono text-xs sm:text-sm px-2 py-0.5 rounded bg-amber-500/15 border border-amber-500/35">{{ $batchSubject->classroom_id }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Department: <span class="text-blue-300 font-semibold">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch) }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Semester: <span class="text-white font-semibold">{{ $practicumCourseFile->semester ?? $batchSubject->semester }}</span></span>
                </p>
                <div class="flex items-center space-x-3 flex-wrap gap-y-1 text-slate-300 text-xs sm:text-sm">
                    <span>Theory: <span class="font-bold text-blue-400">45 Hrs</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Practical: <span class="font-bold text-emerald-400">45 Hrs</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Total Contact: <span class="font-bold text-sky-400">90 Hrs</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>CIE: <span class="font-bold text-amber-400">40M</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>ESE: <span class="font-bold text-sky-400">{{ $practicumCourseFile->ese_marks ?? 100 }}M</span> <span class="text-slate-500">(Total: <span class="text-emerald-400 font-bold">{{ 40 + ($practicumCourseFile->ese_marks ?? 100) }}M</span>)</span></span>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center space-x-2 flex-wrap gap-y-1.5">
                <button onclick="openSyllabusModal()" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white font-semibold transition-all flex items-center space-x-1 text-xs shadow-xs cursor-pointer">
                    <span class="material-symbols-rounded text-sm">upload_file</span>
                    <span>Upload Syllabus</span>
                </button>
                @if($practicumCourseFile->syllabus_pdf_path)
                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-blue-400 font-semibold transition-all flex items-center space-x-1 text-xs shadow-xs no-underline">
                    <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                    <span>View Syllabus PDF</span>
                </a>
                @endif
                <button onclick="toggleFullscreen()" class="p-1.5 rounded-lg bg-slate-800/90 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/80 transition-all flex items-center justify-center cursor-pointer shadow-xs" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-base">fullscreen</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. UNIFIED PRACTICUM TAB NAVIGATION BAR (STRICT SINGLE ROW FIT TO MONITOR) -->
    <main class="max-w-[99%] mx-auto px-2 sm:px-4 md:px-6 mt-3 flex-1 pb-16 w-full">
        
        <div class="tab-nav-container bg-slate-950/90 border border-slate-800/80 p-1 rounded-2xl shadow-lg mb-4 backdrop-blur-md">
            <!-- Tabs Nav List (Strictly Single Row Responsive 12 Tabs) -->
            <nav class="flex items-stretch w-full gap-0.5 sm:gap-1 overflow-hidden" id="practicum-tab-nav">
                <!-- 1. Modules & COs -->
                <button onclick="switchPracticumTab('outline')" id="ptab-outline" class="tab-btn subtab-btn active flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-bold text-blue-400 bg-blue-500/10 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)] transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">import_contacts</span>
                    <span class="truncate block">Modules</span>
                </button>
                <!-- 2. Lesson Plan -->
                <button onclick="switchPracticumTab('planner')" id="ptab-planner" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">calendar_month</span>
                    <span class="truncate block">Lesson Plan</span>
                </button>
                <!-- 3. Self Learning -->
                <button onclick="switchPracticumTab('sl')" id="ptab-sl" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">local_library</span>
                    <span class="truncate block">Self Learn</span>
                </button>
                <!-- 4. Theory Series -->
                <button onclick="switchPracticumTab('theoryseries')" id="ptab-theoryseries" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">history_edu</span>
                    <span class="truncate block">Theory Series</span>
                </button>
                <!-- 5. Lab Series -->
                <button onclick="switchPracticumTab('labseries')" id="ptab-labseries" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">science</span>
                    <span class="truncate block">Lab Series</span>
                </button>
                <!-- 6. Continuous Evaluation -->
                <button onclick="switchPracticumTab('continuouseval')" id="ptab-continuouseval" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">assignment_turned_in</span>
                    <span class="truncate block">Cont. Eval</span>
                </button>
                <!-- 7. Lab Sessions -->
                <button onclick="switchPracticumTab('sessions')" id="ptab-sessions" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">biotech</span>
                    <span class="truncate block">Sessions</span>
                </button>
                <!-- 8. CIA Register -->
                <button onclick="switchPracticumTab('cie')" id="ptab-cie" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">format_list_numbered</span>
                    <span class="truncate block">CIA Register</span>
                </button>
                <!-- 9. ESE Evaluation -->
                <button onclick="switchPracticumTab('ese')" id="ptab-ese" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">workspace_premium</span>
                    <span class="truncate block">ESE Eval</span>
                </button>
                <!-- 10. Attainment & Surveys -->
                <button onclick="switchPracticumTab('attainment')" id="ptab-attainment" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">analytics</span>
                    <span class="truncate block">Attainment</span>
                </button>
                <!-- 11. Reports -->
                <button onclick="switchPracticumTab('reports')" id="ptab-reports" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">print</span>
                    <span class="truncate block">Reports</span>
                </button>
                <!-- 12. Pre-Class Hub -->
                <button onclick="switchPracticumTab('materials')" id="ptab-materials" class="tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px">
                    <span class="material-symbols-rounded text-base xl:text-lg shrink-0">folder_open</span>
                    <span class="truncate block">Pre-Class</span>
                </button>
            </nav>
        </div>

        <!-- WORKSPACE TAB PANELS CONTAINER (RESPONSIVE FULL-WIDTH) -->
        <div id="classroom-tab-contents" class="w-full space-y-4">
            <!-- ========================================================================= -->
            <!-- TAB 1: MODULES & COURSE OUTCOMES (OUTLINE)                                -->
            <!-- ========================================================================= -->
            <div id="tab-outline" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-6 w-full max-w-full overflow-hidden">
                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-blue-400">import_contacts</span>
                            <span>Course Outline &amp; Syllabus Specification</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Revision 2026 Diploma Curriculum &bull; 90 Contact Hours (45 Theory + 45 Practical) &bull; 4 Credits
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($practicumCourseFile->syllabus_pdf_path)
                            <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline">
                                <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                                <span>Preview Syllabus PDF</span>
                            </a>
                        @endif
                        <button onclick="openSyllabusModal()" class="px-3 py-1.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">upload_file</span>
                            <span>Upload New Syllabus</span>
                        </button>
                    </div>
                </div>

                <!-- 4 METRIC STAT CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <div class="bg-slate-950/80 border border-slate-800/90 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Course Identifier</span>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-bold text-sky-400">{{ $batchSubject->subject_code }}</span>
                            <span class="text-slate-600 text-xs">&bull;</span>
                            <span class="text-xs font-semibold text-slate-200">Sem {{ $batchSubject->semester }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-950/80 border border-slate-800/90 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Contact Hours &amp; Credits</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-400">90 Hours (45 Th + 45 Pr)</span>
                            <span class="text-slate-600 text-xs">&bull;</span>
                            <span class="text-xs font-semibold text-slate-200">4 Credits</span>
                        </div>
                    </div>
                    <div class="bg-slate-950/80 border border-slate-800/90 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Evaluation Weightage</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-amber-400">CIE: 40M</span>
                            <span class="text-slate-600 text-xs">&bull;</span>
                            <span class="text-xs font-bold text-blue-400">ESE: {{ $practicumCourseFile->ese_marks ?? 100 }}M</span>
                            <span class="text-slate-600 text-xs">&bull;</span>
                            <span class="text-xs font-bold text-purple-400">Total: {{ 40 + ($practicumCourseFile->ese_marks ?? 100) }}M</span>
                        </div>
                    </div>
                    <div class="bg-slate-950/80 border border-slate-800/90 rounded-xl p-3.5 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Faculty &amp; Department</span>
                        <div class="text-xs font-semibold text-slate-200 truncate" title="{{ Session::get('userName') ?? 'Faculty In-Charge' }}">
                            {{ Session::get('userName') ?? 'Faculty In-Charge' }}
                            @if(isset($hod) && $hod)
                                <span class="text-slate-400 block text-[11px] font-normal truncate">HOD: {{ $hod->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- MODULES (M1 - M4) GRID -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-rounded text-sky-400 text-sm">view_module</span>
                        <span>Course Modules (M1 &ndash; M4)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @php
                            $parsedModules = $practicumCourseFile->parsed_modules ?: [];
                            $defaultMods = [
                                ['module_no' => 1, 'module_id' => 'I', 'title' => 'Module I: Fundamental Concepts & Laboratory Setup', 'hours' => 22, 'topics' => 'Core concepts, theoretical foundations, preliminary laboratory safety, basic apparatus and procedures.'],
                                ['module_no' => 2, 'module_id' => 'II', 'title' => 'Module II: Core Techniques & Practical Workflows', 'hours' => 23, 'topics' => 'Intermediate methodologies, hands-on experimentation, component selection, analytical verification.'],
                                ['module_no' => 3, 'module_id' => 'III', 'title' => 'Module III: Advanced Applications & Simulation', 'hours' => 23, 'topics' => 'Comprehensive workflows, simulated test benches, system integrations, experimental testing.'],
                                ['module_no' => 4, 'module_id' => 'IV', 'title' => 'Module IV: Capstone Implementation & Troubleshooting', 'hours' => 22, 'topics' => 'End-to-end design verification, troubleshooting techniques, performance tuning, and technical documentation.']
                            ];
                            $displayMods = count($parsedModules) > 0 ? $parsedModules : $defaultMods;
                        @endphp
                        @foreach($displayMods as $idx => $mod)
                            <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        MODULE {{ $mod['module_id'] ?? ($mod['module_no'] ?? ($idx + 1)) }}
                                    </span>
                                    <span class="text-[11px] font-mono text-slate-400">{{ $mod['hours'] ?? 22 }} Contact Hours</span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-200">{{ $mod['title'] ?? ('Module ' . ($idx + 1)) }}</h4>
                                <p class="text-[11px] text-slate-400 leading-relaxed">{{ $mod['topics'] ?? ($mod['content'] ?? 'Syllabus content as prescribed by SBTE curriculum specifications.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- COURSE OUTCOMES (CO1 - CO4) GRID -->
                <div class="space-y-3 pt-2">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-rounded text-emerald-400 text-sm">psychology</span>
                        <span>Course Outcomes (CO1 &ndash; CO4)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5">
                        @php
                            $rawCos = $practicumCourseFile->parsed_cos ?: [];
                            $coMap = [];
                            foreach ($rawCos as $k => $c) {
                                if (is_array($c)) {
                                    $cId = $c['id'] ?? $k;
                                    $cStmt = $c['description'] ?? ($c['statement'] ?? '');
                                    $cLevel = isset($c['cognitive_level']) ? ('Cognitive Level: ' . $c['cognitive_level']) : ($c['level'] ?? '');
                                    $coMap[$cId] = ['statement' => $cStmt, 'level' => $cLevel];
                                }
                            }
                            $defaultCos = [
                                'CO1' => ['statement' => 'Recall, explain and apply foundational theoretical principles and basic laboratory methodologies.', 'level' => 'Cognitive Level: Understand / Apply (R/U/Ap)'],
                                'CO2' => ['statement' => 'Formulate, design, execute and record core practical procedures with systematic verification.', 'level' => 'Cognitive Level: Apply / Analyze (Ap/An)'],
                                'CO3' => ['statement' => 'Analyze experimental parameters, troubleshoot configurations and derive empirical findings.', 'level' => 'Cognitive Level: Analyze / Evaluate (An/Ev)'],
                                'CO4' => ['statement' => 'Design comprehensive solutions, implement verified prototypes, and document findings professionally.', 'level' => 'Cognitive Level: Apply / Create (Ap/Cr)']
                            ];
                        @endphp
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coKey)
                            @php
                                $coInfo = $coMap[$coKey] ?? $defaultCos[$coKey];
                            @endphp
                            <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-4 space-y-2 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ $coKey }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-300 leading-relaxed">{{ $coInfo['statement'] ?? $defaultCos[$coKey]['statement'] }}</p>
                                </div>
                                <div class="pt-2 border-t border-slate-900 text-[10px] text-slate-400 font-mono">
                                    {{ $coInfo['level'] ?? $defaultCos[$coKey]['level'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- CO-PO CORRELATION MATRIX -->
                <div class="space-y-3 pt-2">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-rounded text-purple-400 text-base">grid_on</span>
                                <span>CO-PO Correlation Matrix</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Correlation Levels: 3 = Substantial (High), 2 = Moderate (Medium), 1 = Slight (Low), - = No Correlation</p>
                        </div>
                        <button type="button" onclick="savePracticumCoPoMatrix()" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save CO-PO Matrix</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-slate-800 rounded-xl">
                        <table class="w-full text-center border-collapse text-xs sm:text-sm" id="copo-matrix-table">
                            <thead>
                                <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                    <th class="p-2.5 text-left border-r border-slate-800 w-24">Course Outcome</th>
                                    @for($i = 1; $i <= 11; $i++)
                                        <th class="p-2 border-r border-slate-800">PO{{ $i }}</th>
                                    @endfor
                                    <th class="p-2 border-r border-slate-800">PSO1</th>
                                    <th class="p-2">PSO2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                                @php
                                    $poKeys = ['PO1','PO2','PO3','PO4','PO5','PO6','PO7','PO8','PO9','PO10','PO11','PSO1','PSO2'];
                                @endphp
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                                    <tr class="hover:bg-slate-800/40 transition-colors">
                                        <td class="p-2.5 text-left font-mono font-bold text-blue-400 border-r border-slate-800 text-xs sm:text-sm">{{ $co }}</td>
                                        @foreach($poKeys as $pKey)
                                            @php
                                                $curVal = $mappings[$co][$pKey] ?? '-';
                                            @endphp
                                            <td class="p-1.5 border-r border-slate-800">
                                                <select class="copo-cell w-14 text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-bold text-white focus:border-blue-500 outline-none shadow-xs" data-co="{{ $co }}" data-po="{{ $pKey }}">
                                                    <option value="-" {{ $curVal == '-' ? 'selected' : '' }}>-</option>
                                                    <option value="1" {{ $curVal == '1' ? 'selected' : '' }}>1</option>
                                                    <option value="2" {{ $curVal == '2' ? 'selected' : '' }}>2</option>
                                                    <option value="3" {{ $curVal == '3' ? 'selected' : '' }}>3</option>
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 2: UNIFIED LESSON PLANNER (SINGLE UNIFIED PLAN - 90 CONTACT HOURS)    -->
            <!-- ========================================================================= -->
            <div id="tab-planner" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-blue-400">calendar_month</span>
                            <span>Unified Subject Lesson Plan (90 Contact Hours)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Unified delivery schedule encompassing integrated theoretical modules and hands-on laboratory sessions &bull; Single unified plan for the entire subject
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" onclick="syncLessonPlanDates()" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs" title="Synchronize actual dates from classroom attendance logs">
                            <span class="material-symbols-rounded text-sm">sync</span>
                            <span>Sync with Attendance Logs</span>
                        </button>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Lesson Plan</span>
                        </a>
                        <button type="button" onclick="saveAllLessonPlans()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-md">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save All Changes</span>
                        </button>
                    </div>
                </div>

                <!-- Live Summary Pill & Action Bar -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-950/60 p-3 rounded-xl border border-slate-800/80">
                    <div class="flex items-center gap-3 text-xs flex-wrap">
                        <span class="text-slate-400 font-medium">Scheduled Hours: <strong class="text-white font-mono" id="lp-total-count">{{ $lessonPlans->count() }}</strong></span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="text-slate-400 font-medium">Completed: <strong class="text-emerald-400 font-mono" id="lp-completed-count">{{ $lessonPlans->whereNotNull('actual_date')->count() }}</strong></span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="text-slate-400 font-medium">Pending: <strong class="text-amber-400 font-mono" id="lp-pending-count">{{ $lessonPlans->whereNull('actual_date')->count() }}</strong></span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="text-slate-400 font-mono text-[11px]">Standard: 45 Theory (L) + 45 Practical (P)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="addCustomLessonPlanRow('lp-unified-tbody', 'Lecture')" class="px-3 py-1 bg-blue-600/20 hover:bg-blue-600/35 text-blue-300 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">add</span>
                            <span>Add Row Below</span>
                        </button>
                    </div>
                </div>

                <!-- TABLE: UNIFIED LESSON PLAN -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="unified-lesson-plan-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800">Hr #</th>
                                <th class="p-2.5 min-w-[280px] border-r border-slate-800">Planned Topic / Curriculum Content</th>
                                <th class="p-2.5 text-center w-24 border-r border-slate-800">CO</th>
                                <th class="p-2.5 text-center w-40 border-r border-slate-800">Mode / Pedagogy</th>
                                <th class="p-2.5 text-center w-36 border-r border-slate-800">Proposed Date</th>
                                <th class="p-2.5 text-center w-36 border-r border-slate-800">Actual Date</th>
                                <th class="p-2.5 min-w-[160px] border-r border-slate-800">Remarks</th>
                                <th class="p-2.5 text-center w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="lp-unified-tbody" class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @forelse($lessonPlans as $idx => $plan)
                                @php
                                    $pDate = $plan->proposed_date ? \Carbon\Carbon::parse($plan->proposed_date)->format('Y-m-d') : '';
                                    $aDate = $plan->actual_date ? \Carbon\Carbon::parse($plan->actual_date)->format('Y-m-d') : '';
                                    $ped = $plan->pedagogy ?? ($plan->mode === 'P' ? 'Practical / Lab (P)' : 'Lecture (L)');
                                @endphp
                                <tr class="lp-row hover:bg-slate-800/40 transition-colors" data-id="{{ $plan->id }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 lp-seq-cell text-xs sm:text-sm">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <textarea class="lp-topic w-full bg-slate-950 border border-slate-800 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-blue-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter planned topic..." oninput="autoGrowTextarea(this)" onchange="lpAutoSave('{{ $plan->id }}')">{{ $plan->topic_content }}</textarea>
                                    </td>
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <select class="lp-co w-full text-center bg-slate-950 border border-slate-800 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-emerald-400 focus:border-blue-500 outline-none" onchange="lpAutoSave('{{ $plan->id }}')">
                                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $c)
                                                <option value="{{ $c }}" {{ ($plan->co_id ?? 'CO1') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <select class="lp-mode w-full bg-slate-950 border border-slate-800 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-blue-500 outline-none" onchange="lpAutoSave('{{ $plan->id }}')">
                                            <option value="Lecture (L)" {{ stripos($ped, 'Lecture') !== false ? 'selected' : '' }}>Lecture (L)</option>
                                            <option value="Practical / Lab (P)" {{ stripos($ped, 'Practical') !== false || stripos($ped, 'Lab') !== false ? 'selected' : '' }}>Practical / Lab (P)</option>
                                            <option value="Series Exam ST" {{ stripos($ped, 'Series Exam') !== false || stripos($ped, 'ST') !== false ? 'selected' : '' }}>Series Exam (ST)</option>
                                            <option value="Practical Test SP" {{ stripos($ped, 'Test') !== false || stripos($ped, 'SP') !== false ? 'selected' : '' }}>Practical Test (SP)</option>
                                            <option value="Tutorial (T)" {{ stripos($ped, 'Tutorial') !== false ? 'selected' : '' }}>Tutorial (T)</option>
                                        </select>
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <input type="date" class="lp-prop-date w-full bg-slate-950 border border-slate-800 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-300 font-mono text-center focus:border-blue-500 outline-none" value="{{ $pDate }}" onchange="lpAutoSave('{{ $plan->id }}')">
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <input type="date" class="lp-act-date w-full bg-slate-950 border border-slate-800 rounded px-2 py-1.5 text-xs sm:text-sm text-emerald-400 font-mono text-center focus:border-emerald-500 outline-none" value="{{ $aDate }}" onchange="lpAutoSave('{{ $plan->id }}')">
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <input type="text" class="lp-remarks w-full bg-slate-950 border border-slate-800 rounded px-2.5 py-1.5 text-xs sm:text-sm text-slate-300 focus:border-blue-500 outline-none" value="{{ $plan->remarks }}" placeholder="Optional notes..." onchange="lpAutoSave('{{ $plan->id }}')">
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button type="button" onclick="addLessonPlanRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs font-bold transition-all cursor-pointer" title="Add row directly below">+</button>
                                            <button type="button" onclick="confirmDeleteLessonPlanRow('{{ $plan->id }}')" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer" title="Delete this topic row">&times;</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="lp-empty-row">
                                    <td colspan="8" class="p-8 text-center text-slate-500">
                                        No lesson plan topics created yet. Click "+ Add Row Below" above to start populating the 90-hour delivery plan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Action Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <button type="button" onclick="addCustomLessonPlanRow('lp-unified-tbody', 'Lecture')" class="px-3.5 py-2 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <span class="material-symbols-rounded text-sm">add_circle</span>
                        <span>+ Add Lesson Plan Hour Row</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <span id="lp-autosave-badge" class="text-xs text-slate-400 font-mono hidden">
                            <span class="material-symbols-rounded text-xs text-emerald-400 animate-spin">sync</span> Saving...
                        </span>
                        <button type="button" onclick="saveAllLessonPlans()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">check_circle</span>
                            <span>Save All 90 Hours</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 3: SELF LEARNING ACTIVITIES (MODULES 1–4 &bull; MAX 5 CIA)            -->
            <!-- ========================================================================= -->
            <div id="tab-sl" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-emerald-400">local_library</span>
                            <span>Self Learning Activities (SLA &bull; Modules 1, 2, 3 &amp; 4)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Module assignments &amp; MCQ quizzes (10M each &bull; Total 40M) &bull; Average (/10) converted to <strong>5 CIA Marks</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" onclick="openSlConfigModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <span class="material-symbols-rounded text-sm">settings</span>
                            <span>Configure Modules</span>
                        </button>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Summary Report</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">assignment</span>
                            <span>Itemized Split-Up</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: SELF LEARNING ENTRY -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="sla-marks-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[200px] border-r border-slate-800">Student Name</th>
                                <th class="p-2.5 text-center w-28 bg-blue-950/20 text-blue-300 font-bold border-r border-slate-800">
                                    Module 1<br><span class="text-[10px] font-normal text-blue-400/80">(Max 10)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-blue-950/20 text-blue-300 font-bold border-r border-slate-800">
                                    Module 2<br><span class="text-[10px] font-normal text-blue-400/80">(Max 10)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-blue-950/20 text-blue-300 font-bold border-r border-slate-800">
                                    Module 3<br><span class="text-[10px] font-normal text-blue-400/80">(Max 10)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-blue-950/20 text-blue-300 font-bold border-r border-slate-800">
                                    Module 4<br><span class="text-[10px] font-normal text-blue-400/80">(Max 10)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-slate-950 text-slate-300 font-bold border-r border-slate-800">
                                    Raw Avg<br><span class="text-[10px] font-normal text-slate-400">(/10)</span>
                                </th>
                                <th class="p-2.5 text-center w-32 bg-emerald-950/30 text-emerald-300 font-bold">
                                    SLA CIE<br><span class="text-[10px] font-normal text-emerald-400">(/5 Marks)</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $m1 = $student['m1'] !== null ? $student['m1'] : '';
                                    $m2 = $student['m2'] !== null ? $student['m2'] : '';
                                    $m3 = $student['m3'] !== null ? $student['m3'] : '';
                                    $m4 = $student['m4'] !== null ? $student['m4'] : '';
                                    $rawAvg = !empty($student['sl_raw_score']) ? number_format((float)$student['sl_raw_score'], 2) : '0.00';
                                    $sl5 = !empty($student['sl_marks']) ? number_format((float)$student['sl_marks'], 2) : '0.00';
                                @endphp
                                <tr class="sla-student-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- M1 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="10" class="sla-input w-20 bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-mod="1" value="{{ $m1 }}" oninput="handlePracticumSlaChange('{{ $reg }}', 1, this.value)">
                                    </td>
                                    <!-- M2 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="10" class="sla-input w-20 bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-mod="2" value="{{ $m2 }}" oninput="handlePracticumSlaChange('{{ $reg }}', 2, this.value)">
                                    </td>
                                    <!-- M3 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="10" class="sla-input w-20 bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-mod="3" value="{{ $m3 }}" oninput="handlePracticumSlaChange('{{ $reg }}', 3, this.value)">
                                    </td>
                                    <!-- M4 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="10" class="sla-input w-20 bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-mod="4" value="{{ $m4 }}" oninput="handlePracticumSlaChange('{{ $reg }}', 4, this.value)">
                                    </td>
                                    <!-- Raw Avg -->
                                    <td class="p-2 text-center font-mono font-bold text-slate-300 border-r border-slate-800 cell-sla-raw sla-raw-avg text-sm sm:text-base">
                                        {{ $rawAvg }}
                                    </td>
                                    <!-- SLA CIE (/5) -->
                                    <td class="p-2 text-center">
                                        <span class="cell-sla-5 sla-row-cie px-3 py-1.5 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-mono font-bold text-sm sm:text-base inline-block min-w-[55px]">
                                            {{ $sl5 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 4: THEORY SERIES EXAMS (SERIES 1 & 2 &bull; MAX 10 CIA)               -->
            <!-- ========================================================================= -->
            <div id="tab-theoryseries" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-sky-400">history_edu</span>
                            <span>Theory Series Examinations (Series 1 &amp; Series 2)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Series 1 (CO1 + CO2 &bull; Max <strong>50M</strong>) &bull; Series 2 (CO3 + CO4 &bull; Max <strong>50M</strong>) &bull; Average (/50) converted to <strong>10 CIA Marks</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="st-autosave-indicator" class="text-xs text-sky-400 font-bold flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing
                        </span>
                        <!-- QP Generator Modals -->
                        <button type="button" onclick="openQpPreviewModal('Series 1', 'CO1', 'pattern')" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <span class="material-symbols-rounded text-sm">quiz</span>
                            <span>Series 1 QP</span>
                        </button>
                        <button type="button" onclick="openQpPreviewModal('Series 2', 'CO3', 'pattern')" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <span class="material-symbols-rounded text-sm">quiz</span>
                            <span>Series 2 QP</span>
                        </button>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-series-report" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Report</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: THEORY SERIES ENTRY -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="theory-series-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800" rowspan="2">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800" rowspan="2">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[180px] border-r border-slate-800" rowspan="2">Student Name</th>
                                <th class="p-2 text-center bg-blue-950/30 text-blue-300 font-bold border-r border-slate-800" colspan="3">
                                    Series 1 Exam (Max 50M)
                                </th>
                                <th class="p-2 text-center bg-indigo-950/30 text-indigo-300 font-bold border-r border-slate-800" colspan="3">
                                    Series 2 Exam (Max 50M)
                                </th>
                                <th class="p-2.5 text-center w-24 bg-slate-950 text-slate-300 font-bold border-r border-slate-800" rowspan="2">
                                    Th Avg<br><span class="text-[10px] font-normal text-slate-400">(/50)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-sky-950/40 text-sky-300 font-bold" rowspan="2">
                                    Th CIE<br><span class="text-[10px] font-normal text-sky-400">(/10 Marks)</span>
                                </th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-xs border-b border-slate-800">
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO1 (25)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO2 (25)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800 bg-blue-950/40 text-blue-300 font-bold">Total (50)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO3 (25)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO4 (25)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800 bg-indigo-950/40 text-indigo-300 font-bold">Total (50)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $co1 = $student['co1_theory_mark'] > 0 ? $student['co1_theory_mark'] : '';
                                    $co2 = $student['co2_theory_mark'] > 0 ? $student['co2_theory_mark'] : '';
                                    $s1Tot = number_format((float)($student['series1_theory_total'] ?? 0), 1);
                                    $co3 = $student['co3_theory_mark'] > 0 ? $student['co3_theory_mark'] : '';
                                    $co4 = $student['co4_theory_mark'] > 0 ? $student['co4_theory_mark'] : '';
                                    $s2Tot = number_format((float)($student['series2_theory_total'] ?? 0), 1);
                                    $thAvg = number_format((float)($student['series_theory_avg'] ?? 0), 1);
                                    $th10 = number_format((float)($student['series_theory_marks'] ?? 0), 2);
                                @endphp
                                <tr class="theory-series-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- Series 1: CO1 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="25" class="st-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO1" value="{{ $co1 }}" oninput="autoSaveSeriesTheory(this)">
                                    </td>
                                    <!-- Series 1: CO2 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="25" class="st-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO2" value="{{ $co2 }}" oninput="autoSaveSeriesTheory(this)">
                                    </td>
                                    <!-- Series 1 Total -->
                                    <td class="p-2 text-center font-mono font-bold text-blue-300 border-r border-slate-800 bg-blue-950/10 st-s1-tot text-sm sm:text-base">
                                        {{ $s1Tot }}
                                    </td>
                                    <!-- Series 2: CO3 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="25" class="st-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO3" value="{{ $co3 }}" oninput="autoSaveSeriesTheory(this)">
                                    </td>
                                    <!-- Series 2: CO4 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="25" class="st-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-blue-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO4" value="{{ $co4 }}" oninput="autoSaveSeriesTheory(this)">
                                    </td>
                                    <!-- Series 2 Total -->
                                    <td class="p-2 text-center font-mono font-bold text-indigo-300 border-r border-slate-800 bg-indigo-950/10 st-s2-tot text-sm sm:text-base">
                                        {{ $s2Tot }}
                                    </td>
                                    <!-- Th Avg (/50) -->
                                    <td class="p-2 text-center font-mono font-bold text-slate-300 border-r border-slate-800 st-avg-tot text-sm sm:text-base">
                                        {{ $thAvg }}
                                    </td>
                                    <!-- Th CIE (/10) -->
                                    <td class="p-2 text-center">
                                        <span class="st-row-cie px-2.5 py-1 rounded-lg bg-sky-500/15 border border-sky-500/30 text-sky-400 font-mono font-bold text-sm sm:text-base inline-block min-w-[50px]">
                                            {{ $th10 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 5: LAB SERIES EXAMS (SERIES 1 & 2 &bull; MAX 10 CIA)                  -->
            <!-- ========================================================================= -->
            <div id="tab-labseries" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-cyan-400">science</span>
                            <span>Laboratory Series Examinations (Lab Test 1 &amp; Lab Test 2)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Lab Test 1 (CO1 + CO2 &bull; Max <strong>40M</strong>) &bull; Lab Test 2 (CO3 + CO4 &bull; Max <strong>40M</strong>) &bull; Average (/40) converted to <strong>10 CIA Marks</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="sp-autosave-indicator" class="text-xs text-cyan-400 font-bold flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing
                        </span>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-series-report" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Report</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: LAB SERIES ENTRY -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="lab-series-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800" rowspan="2">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800" rowspan="2">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[180px] border-r border-slate-800" rowspan="2">Student Name</th>
                                <th class="p-2 text-center bg-cyan-950/30 text-cyan-300 font-bold border-r border-slate-800" colspan="3">
                                    Lab Test 1 (Max 40M)
                                </th>
                                <th class="p-2 text-center bg-teal-950/30 text-teal-300 font-bold border-r border-slate-800" colspan="3">
                                    Lab Test 2 (Max 40M)
                                </th>
                                <th class="p-2.5 text-center w-24 bg-slate-950 text-slate-300 font-bold border-r border-slate-800" rowspan="2">
                                    Lab Avg<br><span class="text-[10px] font-normal text-slate-400">(/40)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-cyan-950/40 text-cyan-300 font-bold" rowspan="2">
                                    Lab CIE<br><span class="text-[10px] font-normal text-cyan-400">(/10 Marks)</span>
                                </th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-xs border-b border-slate-800">
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO1 (20)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO2 (20)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800 bg-cyan-950/40 text-cyan-300 font-bold">Total (40)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO3 (20)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">CO4 (20)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800 bg-teal-950/40 text-teal-300 font-bold">Total (40)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $co1p = $student['co1_practical_mark'] > 0 ? $student['co1_practical_mark'] : '';
                                    $co2p = $student['co2_practical_mark'] > 0 ? $student['co2_practical_mark'] : '';
                                    $sp1Tot = number_format((float)($student['series1_practical_total'] ?? 0), 1);
                                    $co3p = $student['co3_practical_mark'] > 0 ? $student['co3_practical_mark'] : '';
                                    $co4p = $student['co4_practical_mark'] > 0 ? $student['co4_practical_mark'] : '';
                                    $sp2Tot = number_format((float)($student['series2_practical_total'] ?? 0), 1);
                                    $prAvg = number_format((float)($student['series_practical_avg'] ?? 0), 1);
                                    $pr10 = number_format((float)($student['series_practical_marks'] ?? 0), 2);
                                @endphp
                                <tr class="lab-series-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- Lab 1: CO1 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="20" class="sp-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-cyan-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO1" value="{{ $co1p }}" oninput="autoSaveSeriesPractical(this)">
                                    </td>
                                    <!-- Lab 1: CO2 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="20" class="sp-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-cyan-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO2" value="{{ $co2p }}" oninput="autoSaveSeriesPractical(this)">
                                    </td>
                                    <!-- Lab 1 Total -->
                                    <td class="p-2 text-center font-mono font-bold text-cyan-300 border-r border-slate-800 bg-cyan-950/10 sp-s1-tot text-sm sm:text-base">
                                        {{ $sp1Tot }}
                                    </td>
                                    <!-- Lab 2: CO3 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="20" class="sp-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-cyan-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO3" value="{{ $co3p }}" oninput="autoSaveSeriesPractical(this)">
                                    </td>
                                    <!-- Lab 2: CO4 -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="20" class="sp-input w-20 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-bold font-mono text-center text-white focus:border-cyan-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" data-series="CO4" value="{{ $co4p }}" oninput="autoSaveSeriesPractical(this)">
                                    </td>
                                    <!-- Lab 2 Total -->
                                    <td class="p-2 text-center font-mono font-bold text-teal-300 border-r border-slate-800 bg-teal-950/10 sp-s2-tot text-sm sm:text-base">
                                        {{ $sp2Tot }}
                                    </td>
                                    <!-- Lab Avg (/40) -->
                                    <td class="p-2 text-center font-mono font-bold text-slate-300 border-r border-slate-800 sp-avg-tot text-sm sm:text-base">
                                        {{ $prAvg }}
                                    </td>
                                    <!-- Lab CIE (/10) -->
                                    <td class="p-2 text-center">
                                        <span class="sp-row-cie px-2.5 py-1 rounded-lg bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 font-mono font-bold text-sm sm:text-base inline-block min-w-[50px]">
                                            {{ $pr10 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 6: CONTINUOUS PRACTICAL EVALUATION (MODULES 1–4 COMBINED &bull; MAX 10 CIA)-->
            <!-- ========================================================================= -->
            <div id="tab-continuouseval" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-teal-400">assignment_turned_in</span>
                            <span>Continuous Practical Evaluation (Modules I–IV Combined)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Combined evaluation score for all modules (Max <strong>50 Marks</strong>) &bull; Converted to <strong>10 CIA Marks</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="ce-autosave-indicator" class="text-xs text-teal-400 font-bold flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing
                        </span>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiments-log" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Log</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: CONTINUOUS EVALUATION ENTRY -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="continuous-eval-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[200px] border-r border-slate-800">Student Name</th>
                                <th class="p-2.5 text-center w-52 bg-teal-950/20 text-teal-300 font-bold border-r border-slate-800">
                                    Continuous Practical Evaluation<br>
                                    <span class="text-[10px] font-normal text-teal-400/80">(Modules I, II, III &amp; IV Combined &bull; Max 50M)</span>
                                </th>
                                <th class="p-2.5 text-center w-44 bg-emerald-950/40 text-emerald-300 font-bold">
                                    CE LAB CIA<br>
                                    <span class="text-[10px] font-normal text-emerald-400">(/10 Marks &bull; CIE Register)</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $ce50Val = $student['ce_50'] !== null ? number_format((float)$student['ce_50'], 1) : '';
                                    $ce10Val = $student['continuous_eval_marks'] !== null ? number_format((float)$student['continuous_eval_marks'], 2) : '0.00';
                                @endphp
                                <tr class="ce-student-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- Single Mark /50 Input -->
                                    <td class="p-2 text-center border-r border-slate-800 bg-teal-950/10">
                                        <div class="flex items-center justify-center gap-2">
                                            <input type="number" step="0.5" min="0" max="50" class="ce50-input w-28 bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-sm sm:text-base font-mono text-center text-teal-200 font-bold focus:border-teal-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" value="{{ $ce50Val }}" placeholder="0.0 - 50.0" oninput="onPracticumCe50Input('{{ $reg }}', this.value)">
                                            <span class="text-xs sm:text-sm font-mono text-slate-400 font-semibold">/ 50</span>
                                        </div>
                                    </td>
                                    <!-- Converted 10 CIA Marks -->
                                    <td class="p-2 text-center">
                                        <span class="ce10-badge px-3 py-1.5 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-mono font-bold text-sm sm:text-base inline-block min-w-[60px]" id="ce10-badge-{{ $reg }}">
                                            {{ $ce10Val }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 7: LAB SESSIONS & EXPERIMENT ROSTER (+ / X ROWS)                      -->
            <!-- ========================================================================= -->
            <div id="tab-sessions" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-indigo-400">biotech</span>
                            <span>Laboratory Practical Sessions &amp; Experiment Roster</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Prescribed laboratory practical sessions &amp; experimental schedule &bull; Total Practical Hours: <strong>45 Contact Hours</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/report/experiments-list" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Experiment List</span>
                        </a>
                        <button type="button" onclick="saveCustomExperimentsRoster()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-md">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Experiment List</span>
                        </button>
                    </div>
                </div>

                <!-- TABLE: LAB SESSIONS ROSTER -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="exp-roster-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-14 border-r border-slate-800">Exp #</th>
                                <th class="p-2.5 min-w-[320px] border-r border-slate-800">Experiment / Practical Session Title</th>
                                <th class="p-2.5 text-center w-24 border-r border-slate-800">CO</th>
                                <th class="p-2.5 text-center w-28 border-r border-slate-800">Module</th>
                                <th class="p-2.5 text-center w-28 border-r border-slate-800">Allocated Hrs</th>
                                <th class="p-2.5 text-center w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="exp-roster-tbody" class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @php
                                $experiments = $practicumCourseFile->parsed_experiments ?: [];
                            @endphp
                            @forelse($experiments as $idx => $exp)
                                @php
                                    $expNum = $exp['exp_no'] ?? ($idx + 1);
                                    $expTitle = $exp['title'] ?? ($exp['name'] ?? ('Practical Session ' . $expNum));
                                    $expCo = $exp['co'] ?? ($exp['co_id'] ?? 'CO1');
                                    $expMod = $exp['module'] ?? ($exp['module_no'] ?? 1);
                                    $expHours = $exp['hours'] ?? 3;
                                @endphp
                                <tr class="exp-row hover:bg-slate-800/40 transition-colors">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 exp-seq text-xs sm:text-sm">
                                        {{ $expNum }}
                                    </td>
                                    <td class="p-1.5 border-r border-slate-800">
                                        <textarea class="exp-title w-full bg-slate-950 border border-slate-800 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-indigo-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter practical session title..." oninput="autoGrowTextarea(this);updateExpRosterSummary()">{{ $expTitle }}</textarea>
                                    </td>
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <select class="exp-co w-full text-center bg-slate-950 border border-slate-800 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-cyan-400 focus:border-indigo-500 outline-none">
                                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                                                <option value="{{ $co }}" {{ $expCo === $co ? 'selected' : '' }}>{{ $co }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <select class="exp-mod w-full text-center bg-slate-950 border border-slate-800 rounded px-1.5 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-indigo-500 outline-none">
                                            @for($m = 1; $m <= 4; $m++)
                                                <option value="{{ $m }}" {{ $expMod == $m ? 'selected' : '' }}>Module {{ $m }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <div class="flex items-center justify-center gap-1">
                                            <input type="number" min="1" max="12" class="exp-hours w-16 bg-slate-950 border border-slate-800 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-center text-white focus:border-indigo-500 outline-none" value="{{ $expHours }}" oninput="updateExpRosterSummary()">
                                            <span class="text-xs text-slate-400 font-medium">Hrs</span>
                                        </div>
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button type="button" onclick="addExperimentRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-bold transition-all cursor-pointer" title="Add experiment row directly below">+</button>
                                            <button type="button" onclick="removeExperimentRow(this)" title="Remove experiment row" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer">&times;</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="exp-empty-row">
                                    <td colspan="6" class="p-8 text-center text-slate-500">
                                        No practical sessions configured yet. Click "+ Add Experiment Row" below to define the laboratory schedule.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Bottom Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="addExperimentRow()" class="px-3.5 py-2 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/30 rounded-lg text-xs font-semibold shadow transition-all flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">add_circle</span>
                            <span>+ Add Experiment Row</span>
                        </button>
                        <span id="exp-roster-summary" class="text-xs text-slate-400 font-mono"></span>
                    </div>
                    <button type="button" onclick="saveCustomExperimentsRoster()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">check_circle</span>
                        <span>Save Experiment List</span>
                    </button>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 8: CONSOLIDATED CONTINUOUS INTERNAL ASSESSMENT (CIE REGISTER - 40M)   -->
            <!-- ========================================================================= -->
            <div id="tab-cie" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-amber-400">format_list_numbered</span>
                            <span>Consolidated Continuous Internal Assessment (CIE Register &bull; 40 Marks)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Attendance (5M) + Self Learning (5M) + Continuous Evaluation (10M) + Theory Series (10M) + Lab Series (10M) = <strong>40 Marks Total CIE</strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" onclick="promptEnableAttendanceOverride()" id="btn-toggle-att-override" class="px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs" title="Click to enable manual attendance override">
                            <span class="material-symbols-rounded text-sm" id="att-override-lock-icon">lock</span>
                            <span id="att-override-lock-text">Enable Override</span>
                        </button>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-cia-register" target="_blank" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 no-underline shadow-md">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CIA Register</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">co_present</span>
                            <span>Attendance Sheet</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: CIE REGISTER -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="cie-register-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800" rowspan="2">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800" rowspan="2">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[180px] border-r border-slate-800" rowspan="2">Student Name</th>
                                <th class="p-2 text-center bg-amber-950/30 text-amber-300 font-bold border-r border-slate-800" colspan="3">
                                    Attendance (Max 5M)
                                </th>
                                <th class="p-2.5 text-center w-24 border-r border-slate-800 bg-emerald-950/20 text-emerald-300 font-bold" rowspan="2">
                                    SLA<br><span class="text-[10px] font-normal text-emerald-400">(/5M)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 border-r border-slate-800 bg-teal-950/20 text-teal-300 font-bold" rowspan="2">
                                    Continuous Eval<br><span class="text-[10px] font-normal text-teal-400">(/10M)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 border-r border-slate-800 bg-sky-950/20 text-sky-300 font-bold" rowspan="2">
                                    Theory Series<br><span class="text-[10px] font-normal text-sky-400">(/10M)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 border-r border-slate-800 bg-cyan-950/20 text-cyan-300 font-bold" rowspan="2">
                                    Lab Series<br><span class="text-[10px] font-normal text-cyan-400">(/10M)</span>
                                </th>
                                <th class="p-2.5 text-center w-36 bg-emerald-950/40 text-emerald-300 font-bold" rowspan="2">
                                    Total CIE<br><span class="text-[10px] font-normal text-emerald-400">(Round /40M)</span>
                                </th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-xs border-b border-slate-800">
                                <th class="p-1.5 text-center w-24 border-r border-slate-800">Teams %</th>
                                <th class="p-1.5 text-center w-28 border-r border-slate-800">
                                    <span class="inline-flex items-center justify-center gap-1 cursor-pointer select-none" onclick="promptEnableAttendanceOverride()" title="Click to unlock or lock manual override">
                                        <span>Override %</span>
                                        <span class="material-symbols-rounded text-xs text-amber-400/80" id="header-override-lock">lock</span>
                                    </span>
                                </th>
                                <th class="p-1.5 text-center w-24 border-r border-slate-800 bg-amber-950/20 text-amber-300 font-bold">
                                    Att Mark (/5)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $rawAtt = $student['raw_att_percentage'];
                                    $overAtt = $student['attendance_override_pct'];
                                    $attMarks = (int)round((float)($student['att_marks'] ?? 0));
                                    $sl5 = number_format((float)($student['sl_marks'] ?? 0), 2);
                                    $ce10 = number_format((float)($student['continuous_eval_marks'] ?? 0), 2);
                                    $th10 = number_format((float)($student['series_theory_marks'] ?? 0), 2);
                                    $pr10 = number_format((float)($student['series_practical_marks'] ?? 0), 2);
                                    $totCie = (int)round((float)($student['total_cia_marks'] ?? 0));
                                @endphp
                                <tr class="student-cie-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- Teams Attendance % -->
                                    <td class="p-2 text-center font-mono font-semibold border-r border-slate-800 text-sm sm:text-base {{ ($rawAtt ?? 0) < 75.0 ? 'text-rose-400' : 'text-slate-300' }}">
                                        {{ $rawAtt !== null ? number_format((float)$rawAtt, 1) . '%' : '-' }}
                                    </td>
                                    <!-- Override Attendance % Field (Protected: Disabled until Enable Override is confirmed) -->
                                    <td class="p-1.5 text-center border-r border-slate-800" onclick="if(this.querySelector('input')?.disabled) promptEnableAttendanceOverride();">
                                        <input type="number" step="0.1" min="0" max="100" 
                                               class="att-override-field w-20 text-center rounded px-1.5 py-1 text-sm sm:text-base font-mono font-bold outline-none transition-all cursor-not-allowed opacity-75 {{ ($overAtt !== null && $overAtt !== '') ? 'border-2 border-amber-400 bg-amber-500/15 text-amber-300' : 'border border-slate-700 bg-slate-950 text-slate-300 focus:border-amber-400' }}"
                                               value="{{ ($overAtt !== null && $overAtt !== '') ? number_format((float)$overAtt, 1) : '' }}"
                                               placeholder="--"
                                               data-reg="{{ $reg }}"
                                               disabled
                                               title="Click 'Enable Override' to edit"
                                               oninput="handleAttOverrideChange(this, '{{ $reg }}')"
                                               onchange="saveAttOverrideInline('{{ $reg }}', this.value)">
                                    </td>
                                    <!-- Attendance Mark (/5) -->
                                    <td class="p-2 text-center font-mono font-bold text-sm sm:text-base text-amber-400 border-r border-slate-800 cell-att-mark" id="cie-cell-att-{{ $reg }}">
                                        {{ $attMarks }}
                                    </td>
                                    <!-- SLA /5 -->
                                    <td class="p-2 text-center font-mono font-bold text-sm sm:text-base text-emerald-400 border-r border-slate-800 cell-sl-total" id="cie-cell-sl-{{ $reg }}">
                                        {{ $sl5 }}
                                    </td>
                                    <!-- CE /10 -->
                                    <td class="p-2 text-center font-mono font-bold text-sm sm:text-base text-teal-400 border-r border-slate-800 cell-ce-total" id="cie-cell-ce-{{ $reg }}">
                                        {{ $ce10 }}
                                    </td>
                                    <!-- Theory Series /10 -->
                                    <td class="p-2 text-center font-mono font-bold text-sm sm:text-base text-sky-400 border-r border-slate-800 cell-st-total" id="cie-cell-th-{{ $reg }}">
                                        {{ $th10 }}
                                    </td>
                                    <!-- Lab Series /10 -->
                                    <td class="p-2 text-center font-mono font-bold text-sm sm:text-base text-cyan-400 border-r border-slate-800 cell-sp-total" id="cie-cell-pr-{{ $reg }}">
                                        {{ $pr10 }}
                                    </td>
                                    <!-- Total CIE /40 in Large Bold Green Badge (Rounded Off Integer, No Decimals) -->
                                    <td class="p-2 text-center">
                                        <span class="cell-cie-grand-total inline-block px-3.5 py-1.5 rounded-xl bg-emerald-500/20 border-2 border-emerald-500/40 text-emerald-400 font-mono font-black text-base sm:text-lg tracking-wide shadow-sm" id="cie-cell-tot-{{ $reg }}">
                                            {{ $totCie }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 9: END SEMESTER EVALUATION (ESE &bull; THEORY GRADE &amp; LAB MARKS)   -->
            <!-- ========================================================================= -->
            <div id="tab-ese" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-rose-400">workspace_premium</span>
                            <span>End Semester Examination (ESE Theory Grade &amp; Lab Practical Mark)</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Theory ESE: Grade awarded by SBTE (S/A/B/C/D/E/P &bull; Converted to 60M) &bull; Lab ESE: Practical Exam Mark (Max <strong>40M</strong>) &bull; Total Course Marks (<strong>140M</strong>)
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="ese-autosave-indicator" class="text-xs text-rose-400 font-bold flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Change
                        </span>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-ese-report" target="_blank" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 no-underline shadow-md">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print ESE Marksheet</span>
                        </a>
                    </div>
                </div>

                <!-- TABLE: ESE ENTRY -->
                <div class="overflow-x-auto border border-slate-800 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm" id="ese-evaluation-table">
                        <thead>
                            <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                <th class="p-2.5 text-center w-12 border-r border-slate-800" rowspan="2">Roll</th>
                                <th class="p-2.5 w-32 border-r border-slate-800" rowspan="2">SBTE Reg No</th>
                                <th class="p-2.5 min-w-[180px] border-r border-slate-800" rowspan="2">Student Name</th>
                                <th class="p-2.5 text-center w-28 bg-emerald-950/20 text-emerald-300 font-bold border-r border-slate-800" rowspan="2">
                                    CIE Total<br><span class="text-[10px] font-normal text-emerald-400">(/40 Marks)</span>
                                </th>
                                <th class="p-2 text-center bg-blue-950/30 text-blue-300 font-bold border-r border-slate-800" colspan="2">
                                    ESE Theory (SBTE Written)
                                </th>
                                <th class="p-2 text-center bg-cyan-950/30 text-cyan-300 font-bold border-r border-slate-800" colspan="2">
                                    ESE Practical (Lab Exam)
                                </th>
                                <th class="p-2.5 text-center w-24 bg-slate-950 text-slate-300 font-bold border-r border-slate-800" rowspan="2">
                                    Total ESE<br><span class="text-[10px] font-normal text-slate-400">(/100)</span>
                                </th>
                                <th class="p-2.5 text-center w-28 bg-purple-950/30 text-purple-300 font-bold border-r border-slate-800" rowspan="2">
                                    Course Total<br><span class="text-[10px] font-normal text-purple-400">(/140)</span>
                                </th>
                                <th class="p-2.5 text-center w-24" rowspan="2">Result</th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-xs border-b border-slate-800">
                                <th class="p-1.5 text-center w-28 border-r border-slate-800">Grade Awarded</th>
                                <th class="p-1.5 text-center w-24 border-r border-slate-800">Marks (/60)</th>
                                <th class="p-1.5 text-center w-28 border-r border-slate-800">Practical (/40)</th>
                                <th class="p-1.5 text-center w-20 border-r border-slate-800">Lab Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                            @foreach($studentResults as $student)
                                @php
                                    $reg = $student['reg_no'];
                                    $cieVal = (int)round((float)($student['total_cia_marks'] ?? 0));
                                    $thGrade = trim($student['ese_theory_grade'] ?? '-');
                                    $thMarks = number_format((float)($student['ese_theory'] ?? 0), 1);
                                    $prMarks = number_format((float)($student['ese_practical'] ?? 0), 1);
                                    $totEse = number_format((float)($student['total_ese'] ?? 0), 1);
                                    $courseTot = (int)round((float)($student['total_course_marks'] ?? 0));
                                    $isPassed = !empty($student['is_passed']);

                                    // Compute lab grade from marks /40
                                    $pRaw = (float)($student['ese_practical'] ?? 0);
                                    $pPct = ($pRaw / 40.0) * 100.0;
                                    if ($pRaw <= 0) $prGrade = '-';
                                    elseif ($pPct >= 90) $prGrade = 'S';
                                    elseif ($pPct >= 80) $prGrade = 'A';
                                    elseif ($pPct >= 70) $prGrade = 'B';
                                    elseif ($pPct >= 60) $prGrade = 'C';
                                    elseif ($pPct >= 50) $prGrade = 'D';
                                    elseif ($pPct >= 40) $prGrade = 'E';
                                    else $prGrade = 'F';
                                @endphp
                                <tr class="ese-student-row hover:bg-slate-800/40 transition-colors" data-reg-no="{{ $reg }}">
                                    <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['roll_no'] }}
                                    </td>
                                    <td class="p-2 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">
                                        {{ $student['sbte_reg_no'] }}
                                    </td>
                                    <td class="p-2 font-semibold text-slate-200 border-r border-slate-800 truncate text-xs sm:text-sm" title="{{ $student['name'] }}">
                                        {{ $student['name'] }}
                                    </td>
                                    <!-- CIE /40 (Integer, No .00) -->
                                    <td class="p-2 text-center font-mono font-bold text-emerald-400 border-r border-slate-800 bg-emerald-950/10 text-sm sm:text-base">
                                        {{ $cieVal }}
                                    </td>
                                    <!-- Theory Grade Dropdown -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <select class="ese-theory-grade w-24 text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-mono font-bold text-blue-400 focus:border-blue-500 outline-none shadow-xs" data-reg="{{ $reg }}" onchange="autoSaveEseTheoryGrade(this)">
                                            <option value="-" {{ $thGrade === '-' ? 'selected' : '' }}>-</option>
                                            <option value="S" {{ $thGrade === 'S' ? 'selected' : '' }}>S (90-100%)</option>
                                            <option value="A" {{ $thGrade === 'A' ? 'selected' : '' }}>A (80-89%)</option>
                                            <option value="B" {{ $thGrade === 'B' ? 'selected' : '' }}>B (70-79%)</option>
                                            <option value="C" {{ $thGrade === 'C' ? 'selected' : '' }}>C (60-69%)</option>
                                            <option value="D" {{ $thGrade === 'D' ? 'selected' : '' }}>D (50-59%)</option>
                                            <option value="E" {{ $thGrade === 'E' ? 'selected' : '' }}>E (40-49%)</option>
                                            <option value="P" {{ $thGrade === 'P' ? 'selected' : '' }}>P (Pass)</option>
                                            <option value="F" {{ $thGrade === 'F' ? 'selected' : '' }}>F (Fail)</option>
                                            <option value="FE" {{ $thGrade === 'FE' ? 'selected' : '' }}>FE (Absent)</option>
                                        </select>
                                    </td>
                                    <!-- Theory Converted Marks (/60) -->
                                    <td class="p-2 text-center font-mono font-bold text-blue-300 border-r border-slate-800 ese-th-marks text-sm sm:text-base">
                                        {{ $thMarks }}
                                    </td>
                                    <!-- Lab Mark /40 Input -->
                                    <td class="p-1.5 text-center border-r border-slate-800">
                                        <input type="number" step="0.5" min="0" max="40" class="ese-lab-marks w-22 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-sm sm:text-base font-mono text-center text-cyan-300 font-bold focus:border-cyan-500 outline-none nav-input shadow-xs" data-reg="{{ $reg }}" value="{{ $student['ese_practical'] > 0 ? $student['ese_practical'] : '' }}" placeholder="0-40" oninput="autoSaveEseLabMark(this)">
                                    </td>
                                    <!-- Lab Grade -->
                                    <td class="p-2 text-center font-mono font-bold text-cyan-400 border-r border-slate-800 ese-lab-grade text-sm sm:text-base">
                                        {{ $prGrade }}
                                    </td>
                                    <!-- Total ESE (/100) -->
                                    <td class="p-2 text-center font-mono font-bold text-slate-200 border-r border-slate-800 ese-tot-marks text-sm sm:text-base">
                                        {{ $totEse }}
                                    </td>
                                    <!-- Course Total (/140) -->
                                    <td class="p-2 text-center font-mono font-bold text-purple-300 border-r border-slate-800 bg-purple-950/10 ese-course-tot text-sm sm:text-base">
                                        {{ $courseTot }}
                                    </td>
                                    <!-- Result -->
                                    <td class="p-2 text-center ese-result-cell">
                                        @if($isPassed)
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">PASS</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-rose-500/15 text-rose-400 border border-rose-500/30">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 10: ATTAINMENT & SURVEYS                                              -->
            <!-- ========================================================================= -->
            <div id="tab-attainment" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-6 hidden w-full max-w-full overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-rounded text-purple-400">analytics</span>
                            <span>Course Outcome &amp; Program Outcome Attainment</span>
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Direct Attainment (80% from CIE &amp; ESE) &bull; Indirect Attainment (20% from Surveys) &bull; PO Correlation Mapping
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" onclick="openMidsemInitModal()" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/35 text-blue-300 border border-blue-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <span class="material-symbols-rounded text-sm">poll</span>
                            <span>Mid-Sem Survey</span>
                        </button>
                        <button type="button" onclick="openExitInitModal()" class="px-3 py-1.5 bg-purple-600/20 hover:bg-purple-600/35 text-purple-300 border border-purple-500/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                            <span class="material-symbols-rounded text-sm">rate_review</span>
                            <span>Exit Survey</span>
                        </button>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/report/attainment" target="_blank" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 no-underline shadow-xs">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Attainment Dossier</span>
                        </a>
                    </div>
                </div>

                <!-- 1. CO ATTAINMENT SUMMARY TABLE -->
                <div class="space-y-3">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-rounded text-emerald-400 text-base">verified</span>
                        <span>Course Outcome (CO) Attainment (80:20 Direct:Indirect Model)</span>
                    </h3>
                    <div class="overflow-x-auto border border-slate-800 rounded-xl">
                        <table class="w-full text-center border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                    <th class="p-2.5 text-left border-r border-slate-800 w-24">CO</th>
                                    <th class="p-2.5 border-r border-slate-800">CIE Attainment (Level)</th>
                                    <th class="p-2.5 border-r border-slate-800">Students Attained %</th>
                                    <th class="p-2.5 border-r border-slate-800 bg-blue-950/20 text-blue-300 font-bold">Direct (80%)</th>
                                    <th class="p-2.5 border-r border-slate-800 bg-purple-950/20 text-purple-300 font-bold">Indirect (20%)</th>
                                    <th class="p-2.5 border-r border-slate-800 bg-emerald-950/30 text-emerald-300 font-bold">Overall (80:20)</th>
                                    <th class="p-2.5">Target &amp; Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                                    @php
                                        $dObj = $directStats[$co] ?? [];
                                        $dLevel = is_array($dObj) ? (float)($dObj['level'] ?? 2.5) : (float)$dObj;
                                        $dPct   = is_array($dObj) ? (float)($dObj['percentage'] ?? 75.0) : 75.0;

                                        $iObj = $indirectStats[$co] ?? [];
                                        $iLevel = is_array($iObj) ? (float)($iObj['level'] ?? 2.5) : (float)$iObj;
                                        $iPct   = is_array($iObj) ? (float)($iObj['percentage'] ?? 83.3) : 83.3;

                                        $combObj = $combinedStats[$co] ?? round(($dLevel * 0.8) + ($iLevel * 0.2), 2);
                                        $comb = is_array($combObj) ? (float)($combObj['overall'] ?? 2.5) : (float)$combObj;
                                    @endphp
                                    <tr class="hover:bg-slate-800/40 transition-colors">
                                        <td class="p-2.5 text-left font-mono font-bold text-blue-400 border-r border-slate-800 text-xs sm:text-sm">{{ $co }}</td>
                                        <td class="p-2.5 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">{{ number_format($dLevel, 2) }}</td>
                                        <td class="p-2.5 font-mono text-slate-300 border-r border-slate-800 text-xs sm:text-sm">{{ number_format($dPct, 1) }}%</td>
                                        <td class="p-2.5 font-mono font-bold text-blue-300 border-r border-slate-800 bg-blue-950/10 text-xs sm:text-sm">{{ number_format($dLevel, 2) }}</td>
                                        <td class="p-2.5 font-mono font-bold text-purple-300 border-r border-slate-800 bg-purple-950/10 text-xs sm:text-sm">{{ number_format($iLevel, 2) }}</td>
                                        <td class="p-2.5 font-mono font-black text-emerald-400 border-r border-slate-800 bg-emerald-950/20 text-xs sm:text-sm">{{ number_format($comb, 2) }}</td>
                                        <td class="p-2.5">
                                            <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">ATTAINED</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. PO ATTAINMENT MATRIX -->
                <div class="space-y-3 pt-2">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-rounded text-sky-400 text-base">stars</span>
                        <span>Program Outcomes (PO) Attainment Matrix</span>
                    </h3>
                    <div class="overflow-x-auto border border-slate-800 rounded-xl">
                        <table class="w-full text-center border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-slate-950 text-slate-300 font-bold border-b border-slate-800 text-xs">
                                    <th class="p-2.5 text-left border-r border-slate-800 w-24">Metric</th>
                                    @for($i = 1; $i <= 11; $i++)
                                        <th class="p-2 border-r border-slate-800">PO{{ $i }}</th>
                                    @endfor
                                    <th class="p-2 border-r border-slate-800">PSO1</th>
                                    <th class="p-2">PSO2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80 bg-slate-900/50">
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="p-2.5 text-left font-semibold text-slate-200 border-r border-slate-800 text-xs sm:text-sm">Attainment</td>
                                    @php
                                        $poList = ['PO1','PO2','PO3','PO4','PO5','PO6','PO7','PO8','PO9','PO10','PO11','PSO1','PSO2'];
                                    @endphp
                                    @foreach($poList as $p)
                                        @php
                                            $rawPo = $poAttainments[$p] ?? 2.5;
                                            $val = is_array($rawPo) ? (float)($rawPo['value'] ?? 2.5) : (float)$rawPo;
                                        @endphp
                                        <td class="p-2 font-mono font-bold text-sky-400 border-r border-slate-800 text-xs sm:text-sm">
                                            {{ number_format($val, 2) }}
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 11: REPORTS & CONSOLIDATED MARKS REGISTER                              -->
            <!-- ========================================================================= -->
            <div id="tab-reports" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-5 hidden w-full max-w-full overflow-hidden">
                <!-- Top Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/80 pb-4 gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                <span class="material-symbols-rounded text-lg block">description</span>
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-white">Official Academic &amp; Evaluation Reports</h2>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Direct institutional print registers, CIE marks registers (40M), End-Semester documents (100M/140M), and Revision 2026 course dossiers.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-800 text-slate-400 text-xs font-mono font-medium">
                            <strong class="text-sky-400">{{ count($students) }}</strong> Enrolled Students
                        </span>
                    </div>
                </div>

                <!-- QUICK REPORT SHORTCUTS TOOLBAR -->
                <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-2.5 flex flex-wrap items-center justify-between gap-2 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-slate-400 text-sm">print_connect</span>
                        <span class="text-[11px] font-bold text-slate-300">Quick Shortcuts:</span>
                    </div>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-cia-register" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-amber-400">format_list_numbered</span>
                            <span>CIA Register</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-internal-marks" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-emerald-400">badge</span>
                            <span>Internal Marks</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-series-report" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-sky-400">edit_note</span>
                            <span>Series Tests</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-blue-400">co_present</span>
                            <span>Attendance Sheet</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-log-report" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-indigo-400">history_edu</span>
                            <span>Subject Log (A4)</span>
                        </a>
                        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/practicum/' . $batchSubject->id . '?tab=reports') }}" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-purple-400">edit_calendar</span>
                            <span>Log Desk</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-teal-400">calendar_month</span>
                            <span>Lesson Plan</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiments-log" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-cyan-400">science</span>
                            <span>Lab Log</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-course-file" target="_blank" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-lg text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                            <span class="material-symbols-rounded text-xs text-rose-400">picture_as_pdf</span>
                            <span>Course File PDF</span>
                        </a>
                    </div>
                </div>

                <!-- 4 GROUPED REPORTS SECTIONS (Matching Common Virtual Classroom Standard) -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    
                    <!-- Group 1: Evaluation & Academic Marksheets -->
                    <div class="glass-card p-4 sm:p-5 rounded-xl border border-slate-800 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-800/80">
                                <span class="material-symbols-rounded text-amber-400 text-lg">fact_check</span>
                                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Evaluation Marksheets</h4>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed mb-3">Continuous Internal Assessment (CIE), Series Exam marksheets, and final End-Semester Results.</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-cia-register" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-amber-400">badge</span>
                                    <span>Consolidated CIA Register (40M)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-internal-marks" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-emerald-400">grade</span>
                                    <span>Internal Mark Report</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-series-report" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-sky-400">edit_note</span>
                                    <span>Series Report (Theory &amp; Lab 20M)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-ese-report" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-rose-400">school</span>
                                    <span>ESE Results (Theory &amp; Practical)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Group 2: Attendance & Execution Logs -->
                    <div class="glass-card p-4 sm:p-5 rounded-xl border border-slate-800 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-800/80">
                                <span class="material-symbols-rounded text-blue-400 text-lg">how_to_reg</span>
                                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Attendance &amp; Class Logs</h4>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed mb-3">Unified course attendance register, daily classroom teaching execution logs, and live desk.</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-blue-400">co_present</span>
                                    <span>Subject Attendance Report</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-log-report" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-indigo-400">history_edu</span>
                                    <span>Log-Wise Teaching Register</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-cyan-400">view_list</span>
                                    <span>Course-Wise Detailed Matrix (A4)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/practicum/' . $batchSubject->id . '?tab=reports') }}" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-purple-400">edit_calendar</span>
                                    <span>Attendance &amp; Log Entry Desk</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Group 3: Curriculum Delivery & Lab Planning -->
                    <div class="glass-card p-4 sm:p-5 rounded-xl border border-slate-800 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-800/80">
                                <span class="material-symbols-rounded text-teal-400 text-lg">science</span>
                                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Curriculum &amp; Lab Planning</h4>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed mb-3">Planned semester syllabus delivery, laboratory experiments roster, and practical evaluation.</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-teal-400">calendar_month</span>
                                    <span>Unified 90-Hr Lesson Plan</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiment-list" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-indigo-400">biotech</span>
                                    <span>Lab Experiments List</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiments-log" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-cyan-400">assignment_turned_in</span>
                                    <span>Continuous Practical Log (10M)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-timetable" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-slate-200">schedule</span>
                                    <span>Batch Timetable Schedule</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Group 4: Self-Learning & Course Files -->
                    <div class="glass-card p-4 sm:p-5 rounded-xl border border-slate-800 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-800/80">
                                <span class="material-symbols-rounded text-emerald-400 text-lg">folder_zip</span>
                                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Self-Learning &amp; Course Dossier</h4>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed mb-3">Self learning activities, itemized split-up, NBA course file desk, and archival PDF.</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-emerald-400">local_library</span>
                                    <span>Self-Learning Summary (5M)</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-emerald-400">assignment</span>
                                    <span>Self-Learning Itemized Split-Up</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-amber-400">edit_note</span>
                                    <span>Course File Preparation Desk</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>

                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-course-file" target="_blank" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm no-underline group">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-rounded text-sm text-slate-400 group-hover:text-rose-400">picture_as_pdf</span>
                                    <span>Official Course File PDF</span>
                                </span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ========================================================================= -->
            <!-- TAB 12: PRE-CLASS HUB & STUDY MATERIALS (CANONICAL MODEL)                 -->
            <!-- ========================================================================= -->
            <div id="tab-materials" class="tab-panel glass-card p-3 sm:p-5 rounded-2xl border border-slate-800 space-y-4 hidden w-full max-w-full overflow-hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum'])
            </div>
        </div> <!-- End of classroom-tab-contents -->
    </main>
    <!-- ========================================================================= -->
    <!-- MODALS & OVERLAYS                                                         -->
    <!-- ========================================================================= -->

    <!-- 1. SYLLABUS UPLOAD MODAL -->
    <div id="syllabus-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-400">upload_file</span>
                    <span>Upload Syllabus Curriculum PDF</span>
                </h3>
                <button onclick="closeSyllabusModal()" class="text-slate-400 hover:text-white text-lg cursor-pointer">&times;</button>
            </div>
            <form id="syllabusUploadForm" onsubmit="uploadSyllabusPdf(event)" enctype="multipart/form-data" class="space-y-4">
                <div class="border-2 border-dashed border-slate-700 hover:border-blue-500 rounded-xl p-6 text-center cursor-pointer transition-colors" onclick="document.getElementById('syllabus_file_input').click()">
                    <span class="material-symbols-rounded text-4xl text-blue-400">cloud_upload</span>
                    <p class="text-xs text-slate-300 mt-2 font-medium">Click or drag &amp; drop Revision 2026 Practicum PDF here</p>
                    <p class="text-[11px] text-slate-500 mt-1">Maximum file size: 10MB (PDF only)</p>
                    <input type="file" name="syllabus_file" id="syllabus_file_input" accept=".pdf" class="hidden" onchange="document.getElementById('selected-file-name').innerText = this.files[0]?.name || ''">
                    <div id="selected-file-name" class="text-xs font-mono text-emerald-400 mt-2 font-bold"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeSyllabusModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold cursor-pointer">Cancel</button>
                    <button type="submit" id="btn-upload-syllabus" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold shadow-md cursor-pointer">Upload &amp; Parse</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. QUESTION PAPER GENERATOR MODAL -->
    <div id="qp-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 backdrop-blur-md hidden p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2" id="qp-modal-title">
                    <span class="material-symbols-rounded text-sky-400">quiz</span>
                    <span>Series Examination Question Paper &amp; Scheme Generator</span>
                </h3>
                <button onclick="closeQpModal()" class="text-slate-400 hover:text-white text-lg cursor-pointer">&times;</button>
            </div>
            <div class="flex-1 overflow-y-auto space-y-4 pr-1" id="qp-editor-container">
                <!-- Dynamically populated by JS -->
            </div>
            <div class="flex justify-between items-center pt-3 border-t border-slate-800">
                <div class="flex gap-2">
                    <button type="button" onclick="printFromQpModal('qp')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">print</span> Print Question Paper
                    </button>
                    <button type="button" onclick="printFromQpModal('scheme')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">assignment</span> Valuation Scheme
                    </button>
                    <button type="button" onclick="printFromQpModal('answer_key')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">key</span> Answer Key
                    </button>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeQpModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold cursor-pointer">Close</button>
                    <button type="button" onclick="saveQpFromModal()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-md cursor-pointer">Save Question Paper</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. SLA CONFIGURATION MODAL -->
    <div id="sl-config-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-emerald-400">settings</span>
                    <span>Self-Learning Assessment (SLA) Settings</span>
                </h3>
                <button onclick="closeSlConfigModal()" class="text-slate-400 hover:text-white text-lg cursor-pointer">&times;</button>
            </div>
            <form onsubmit="saveSlConfig(event)" class="space-y-4">
                <p class="text-xs text-slate-400">Configure evaluation components enabled for each course module outcome.</p>
                <div class="space-y-3">
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                        @php
                            $cfg = $slConfigs[$co] ?? ['assignment' => true, 'mcq' => true];
                        @endphp
                        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-400 font-mono">{{ $co }}</span>
                            <div class="flex items-center gap-4 text-xs">
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" name="cfg_{{ $co }}_assignment" {{ !empty($cfg['assignment']) ? 'checked' : '' }} class="rounded border-slate-700 text-blue-600">
                                    <span class="text-slate-300">Assignment (5M)</span>
                                </label>
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" name="cfg_{{ $co }}_mcq" {{ !empty($cfg['mcq']) ? 'checked' : '' }} class="rounded border-slate-700 text-blue-600">
                                    <span class="text-slate-300">Quiz / MCQ (5M)</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-md cursor-pointer">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. MID-SEMESTER SURVEY INITIALIZE MODAL -->
    <div id="midsem-init-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-400">poll</span>
                    <span>Initialize Mid-Semester Feedback Survey</span>
                </h3>
                <button onclick="closeMidsemInitModal()" class="text-slate-400 hover:text-white text-lg cursor-pointer">&times;</button>
            </div>
            <form onsubmit="submitPracticumMidsemInit(event)" class="space-y-4">
                <p class="text-xs text-slate-300 leading-relaxed">
                    Activate the student mid-semester feedback survey. Students will be able to access the questionnaire through their student portal.
                </p>
                <div class="space-y-2">
                    <label class="text-xs text-slate-400 font-semibold block">Survey Expiry Date</label>
                    <input type="date" name="expiry_date" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white outline-none" value="{{ date('Y-m-d', strtotime('+14 days')) }}">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeMidsemInitModal()" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-lg text-xs cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold cursor-pointer">Launch Survey</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. EXIT SURVEY INITIALIZE MODAL -->
    <div id="exit-init-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm hidden p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-purple-400">rate_review</span>
                    <span>Initialize Course Exit Survey</span>
                </h3>
                <button onclick="closeExitInitModal()" class="text-slate-400 hover:text-white text-lg cursor-pointer">&times;</button>
            </div>
            <form onsubmit="submitPracticumExitInit(event)" class="space-y-4">
                <p class="text-xs text-slate-300 leading-relaxed">
                    Activate the formal Course Exit Survey. Student responses feed directly into the indirect course outcome attainment calculations (20% weightage).
                </p>
                <div class="space-y-2">
                    <label class="text-xs text-slate-400 font-semibold block">Survey Expiry Date</label>
                    <input type="date" name="expiry_date" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white outline-none" value="{{ date('Y-m-d', strtotime('+21 days')) }}">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeExitInitModal()" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-lg text-xs cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg text-xs font-bold cursor-pointer">Launch Survey</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- JAVASCRIPT CONTROLLERS & DATA HANDLING                                    -->
    <!-- ========================================================================= -->
    <script>
        const subjectId = {{ $batchSubject->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // All 12 Standard Tabs
        const allPracticumTabs = [
            'outline',
            'planner',
            'sl',
            'theoryseries',
            'labseries',
            'continuouseval',
            'sessions',
            'cie',
            'ese',
            'attainment',
            'reports',
            'materials'
        ];

        // -------------------------------------------------------------
        // DARK / LIGHT THEME TOGGLE (SIMPLE DESKTOP SWITCHER)
        // -------------------------------------------------------------
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');
            const themeLabel = document.getElementById('theme-label');
            const isLight = body.classList.contains('light');

            if (isLight) {
                body.classList.remove('light');
                body.classList.add('dark');
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                if (themeIcon) themeIcon.innerText = 'light_mode';
                if (themeLabel) themeLabel.innerText = 'Dark';
                localStorage.setItem('practicum_theme', 'dark');
            } else {
                body.classList.remove('dark');
                body.classList.add('light');
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                if (themeIcon) themeIcon.innerText = 'dark_mode';
                if (themeLabel) themeLabel.innerText = 'Light';
                localStorage.setItem('practicum_theme', 'light');
            }
        }

        function syncThemeIcon() {
            const isLight = document.body.classList.contains('light');
            const themeIcon = document.getElementById('theme-icon');
            const themeLabel = document.getElementById('theme-label');
            if (themeIcon) themeIcon.innerText = isLight ? 'dark_mode' : 'light_mode';
            if (themeLabel) themeLabel.innerText = isLight ? 'Light' : 'Dark';
        }

        // Tab Switching
        function switchPracticumTab(tab) {
            const tabAlias = {
                'overview': 'outline',
                'courseoutline': 'outline',
                'modules': 'outline',
                'cos': 'outline',
                'planner': 'planner',
                'lessonplan': 'planner',
                'sl': 'sl',
                'selflearning': 'sl',
                'theoryseries': 'theoryseries',
                'series': 'theoryseries',
                'labseries': 'labseries',
                'continuouseval': 'continuouseval',
                'ce': 'continuouseval',
                'evaluation': 'continuouseval',
                'sessions': 'sessions',
                'experiments': 'sessions',
                'roster': 'sessions',
                'cie': 'cie',
                'cia': 'cie',
                'cieroot': 'cie',
                'ese': 'ese',
                'endsem': 'ese',
                'attainment': 'attainment',
                'surveys': 'attainment',
                'reports': 'reports',
                'materials': 'materials',
                'preclass': 'materials',
                'hub': 'materials'
            };
            let targetTab = tabAlias[tab] || tab;
            if (!allPracticumTabs.includes(targetTab)) {
                targetTab = 'outline';
            }

            allPracticumTabs.forEach(t => {
                const btn = document.getElementById('ptab-' + t);
                const panel = document.getElementById('tab-' + t);
                if (btn) {
                    if (t === targetTab) {
                        btn.className = "tab-btn subtab-btn active flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-bold text-blue-400 bg-blue-500/10 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)] transition-all cursor-pointer -mb-px";
                    } else {
                        btn.className = "tab-btn subtab-btn flex-1 min-w-0 flex flex-col xl:flex-row items-center justify-center gap-0.5 xl:gap-1 py-1 sm:py-1.5 px-0.5 sm:px-1 text-center text-[10px] xl:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl transition-all cursor-pointer -mb-px";
                    }
                }
                if (panel) {
                    panel.classList.toggle('hidden', t !== targetTab);
                }
            });

            localStorage.setItem('r26_practicum_active_tab', targetTab);

            if (targetTab === 'materials' && typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
            }
        }

        // Auto-growing textarea helper
        function autoGrowTextarea(el) {
            if (!el) return;
            el.style.height = 'auto';
            el.style.height = Math.max(38, el.scrollHeight) + 'px';
        }

        // Restore tab on load
        document.addEventListener('DOMContentLoaded', function() {
            syncThemeIcon();
            const savedTab = localStorage.getItem('r26_practicum_active_tab') || 'outline';
            switchPracticumTab(savedTab);
            updateExpRosterSummary();
            initKeyboardNavigation();
            document.querySelectorAll('textarea.lp-topic, textarea.exp-title').forEach(autoGrowTextarea);
            if (typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
            }
        });

        // -------------------------------------------------------------
        // KEYBOARD NAVIGATION (Arrow Up / Down / Enter)
        // -------------------------------------------------------------
        function initKeyboardNavigation() {
            document.querySelectorAll('table').forEach(table => {
                table.addEventListener('keydown', function(e) {
                    const target = e.target;
                    if (!target.classList.contains('nav-input') && target.tagName !== 'INPUT') return;

                    const currentTd = target.closest('td');
                    const currentRow = target.closest('tr');
                    if (!currentTd || !currentRow) return;

                    const colIndex = Array.from(currentRow.children).indexOf(currentTd);

                    if (e.key === 'ArrowDown' || e.key === 'Enter') {
                        e.preventDefault();
                        const nextRow = currentRow.nextElementSibling;
                        if (nextRow) {
                            const nextCell = nextRow.children[colIndex];
                            const input = nextCell?.querySelector('input, select');
                            if (input) {
                                input.focus();
                                if (input.select) input.select();
                            }
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevRow = currentRow.previousElementSibling;
                        if (prevRow) {
                            const prevCell = prevRow.children[colIndex];
                            const input = prevCell?.querySelector('input, select');
                            if (input) {
                                input.focus();
                                if (input.select) input.select();
                            }
                        }
                    }
                });
            });
        }

        // -------------------------------------------------------------
        // 1. SELF LEARNING ASSESSMENT (SLA) AUTO-SAVE & LIVE CALC
        // -------------------------------------------------------------
        let slDebounceTimer = null;
        function handlePracticumSlaChange(regNo, moduleNum, val) {
            const row = document.querySelector(`.sla-student-row[data-reg-no="${regNo}"]`);
            if (!row) return;

            const i1 = row.querySelector('input[data-mod="1"]')?.value.trim() ?? '';
            const i2 = row.querySelector('input[data-mod="2"]')?.value.trim() ?? '';
            const i3 = row.querySelector('input[data-mod="3"]')?.value.trim() ?? '';
            const i4 = row.querySelector('input[data-mod="4"]')?.value.trim() ?? '';

            const m1 = i1 !== '' ? parseFloat(i1) : null;
            const m2 = i2 !== '' ? parseFloat(i2) : null;
            const m3 = i3 !== '' ? parseFloat(i3) : null;
            const m4 = i4 !== '' ? parseFloat(i4) : null;

            const entered = [m1, m2, m3, m4].filter(v => v !== null && !isNaN(v));
            let rawAvg = 0;
            let sl5 = 0;
            if (entered.length > 0) {
                rawAvg = entered.reduce((a, b) => a + b, 0) / entered.length;
                sl5 = Math.min(5, Math.round(((rawAvg / 10.0) * 5.0) * 2) / 2);
            }

            const avgCell = row.querySelector('.cell-sla-raw') || row.querySelector('.sla-raw-avg');
            if (avgCell) avgCell.textContent = rawAvg.toFixed(2);

            const cieBadge = row.querySelector('.cell-sla-5') || row.querySelector('.sla-row-cie');
            if (cieBadge) cieBadge.textContent = sl5.toFixed(2);

            // Sync to CIE Register
            const cieCell = document.getElementById('cie-cell-sl-' + regNo);
            if (cieCell) cieCell.textContent = sl5.toFixed(2);
            recalculatePracticumCieRow(regNo);

            // Trigger Debounced Auto-Save
            clearTimeout(slDebounceTimer);
            const indicator = document.getElementById('sl-autosave-indicator');
            if (indicator) indicator.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin text-amber-400">sync</span> <span class="text-amber-400">Saving...</span>';

            slDebounceTimer = setTimeout(() => {
                savePracticumSelfLearningMarks(true, regNo);
            }, 450);
        }

        async function savePracticumSelfLearningMarks(isAutoSave = false, singleRegNo = null) {
            const rows = document.querySelectorAll('.sla-student-row');
            const marksData = [];

            rows.forEach(row => {
                const reg = row.getAttribute('data-reg-no');
                if (singleRegNo && reg !== singleRegNo) return;

                const i1 = row.querySelector('input[data-mod="1"]')?.value.trim() ?? '';
                const i2 = row.querySelector('input[data-mod="2"]')?.value.trim() ?? '';
                const i3 = row.querySelector('input[data-mod="3"]')?.value.trim() ?? '';
                const i4 = row.querySelector('input[data-mod="4"]')?.value.trim() ?? '';

                marksData.push({
                    reg_no: reg,
                    m1: i1 !== '' ? parseFloat(i1) : null,
                    m2: i2 !== '' ? parseFloat(i2) : null,
                    m3: i3 !== '' ? parseFloat(i3) : null,
                    m4: i4 !== '' ? parseFloat(i4) : null
                });
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/self-learning/marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks_data: marksData })
                });
                const res = await resp.json();
                const indicator = document.getElementById('sl-autosave-indicator');
                if (indicator) {
                    indicator.innerHTML = '<span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span> <span class="text-emerald-400">Saved</span>';
                    setTimeout(() => {
                        indicator.innerHTML = '<span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing';
                    }, 2500);
                }
                if (!isAutoSave) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: 'Self learning marks saved successfully.', timer: 1500, showConfirmButton: false });
                }
            } catch (err) {
                console.error(err);
            }
        }

        // -------------------------------------------------------------
        // 2. THEORY SERIES AUTO-SAVE & LIVE CALC
        // -------------------------------------------------------------
        let thDebounceTimer = null;
        function autoSaveSeriesTheory(inputEl) {
            const regNo = inputEl.getAttribute('data-reg');
            const seriesNo = inputEl.getAttribute('data-series');
            const row = document.querySelector(`.theory-series-row[data-reg-no="${regNo}"]`);
            if (!row) return;

            const co1 = parseFloat(row.querySelector('input[data-series="CO1"]')?.value) || 0;
            const co2 = parseFloat(row.querySelector('input[data-series="CO2"]')?.value) || 0;
            const s1Tot = Math.min(50, co1 + co2);

            const co3 = parseFloat(row.querySelector('input[data-series="CO3"]')?.value) || 0;
            const co4 = parseFloat(row.querySelector('input[data-series="CO4"]')?.value) || 0;
            const s2Tot = Math.min(50, co3 + co4);

            const thAvg = (s1Tot + s2Tot) / 2.0;
            const th10 = Math.round(((thAvg / 50.0) * 10.0) * 2) / 2;

            row.querySelector('.st-s1-tot').textContent = s1Tot.toFixed(1);
            row.querySelector('.st-s2-tot').textContent = s2Tot.toFixed(1);
            row.querySelector('.st-avg-tot').textContent = thAvg.toFixed(1);
            row.querySelector('.st-row-cie').textContent = th10.toFixed(2);

            // Sync to CIE Register
            const cieCell = document.getElementById('cie-cell-th-' + regNo);
            if (cieCell) cieCell.textContent = th10.toFixed(2);
            recalculatePracticumCieRow(regNo);

            // Debounced Save
            clearTimeout(thDebounceTimer);
            const indicator = document.getElementById('st-autosave-indicator');
            if (indicator) indicator.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin text-amber-400">sync</span> <span class="text-amber-400">Saving...</span>';

            thDebounceTimer = setTimeout(async () => {
                try {
                    await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/series-theory`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            series_no: seriesNo,
                            marks_data: [{ reg_no: regNo, total_score_50: parseFloat(inputEl.value) || 0 }]
                        })
                    });
                    if (indicator) {
                        indicator.innerHTML = '<span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span> <span class="text-emerald-400">Saved</span>';
                        setTimeout(() => {
                            indicator.innerHTML = '<span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing';
                        }, 2500);
                    }
                } catch (err) { console.error(err); }
            }, 450);
        }

        // -------------------------------------------------------------
        // 3. LAB SERIES AUTO-SAVE & LIVE CALC
        // -------------------------------------------------------------
        let spDebounceTimer = null;
        function autoSaveSeriesPractical(inputEl) {
            const regNo = inputEl.getAttribute('data-reg');
            const seriesNo = inputEl.getAttribute('data-series');
            const row = document.querySelector(`.lab-series-row[data-reg-no="${regNo}"]`);
            if (!row) return;

            const co1 = parseFloat(row.querySelector('input[data-series="CO1"]')?.value) || 0;
            const co2 = parseFloat(row.querySelector('input[data-series="CO2"]')?.value) || 0;
            const s1Tot = Math.min(40, co1 + co2);

            const co3 = parseFloat(row.querySelector('input[data-series="CO3"]')?.value) || 0;
            const co4 = parseFloat(row.querySelector('input[data-series="CO4"]')?.value) || 0;
            const s2Tot = Math.min(40, co3 + co4);

            const prAvg = (s1Tot + s2Tot) / 2.0;
            const pr10 = Math.round(((prAvg / 40.0) * 10.0) * 2) / 2;

            row.querySelector('.sp-s1-tot').textContent = s1Tot.toFixed(1);
            row.querySelector('.sp-s2-tot').textContent = s2Tot.toFixed(1);
            row.querySelector('.sp-avg-tot').textContent = prAvg.toFixed(1);
            row.querySelector('.sp-row-cie').textContent = pr10.toFixed(2);

            // Sync to CIE Register
            const cieCell = document.getElementById('cie-cell-pr-' + regNo);
            if (cieCell) cieCell.textContent = pr10.toFixed(2);
            recalculatePracticumCieRow(regNo);

            // Debounced Save
            clearTimeout(spDebounceTimer);
            const indicator = document.getElementById('sp-autosave-indicator');
            if (indicator) indicator.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin text-amber-400">sync</span> <span class="text-amber-400">Saving...</span>';

            spDebounceTimer = setTimeout(async () => {
                try {
                    await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/series-practical`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            series_no: seriesNo,
                            marks_data: [{ reg_no: regNo, total_score_40: parseFloat(inputEl.value) || 0 }]
                        })
                    });
                    if (indicator) {
                        indicator.innerHTML = '<span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span> <span class="text-emerald-400">Saved</span>';
                        setTimeout(() => {
                            indicator.innerHTML = '<span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing';
                        }, 2500);
                    }
                } catch (err) { console.error(err); }
            }, 450);
        }

        // -------------------------------------------------------------
        // 4. CONTINUOUS EVALUATION AUTO-SAVE & LIVE CALC
        // -------------------------------------------------------------
        let ceDebounceTimer = null;
        function onPracticumCe50Input(regNo, val) {
            const score50 = Math.min(50, Math.max(0, parseFloat(val) || 0));
            const ce10 = Math.min(10, Math.round(((score50 / 50.0) * 10.0) * 2) / 2);

            const badge = document.getElementById('ce10-badge-' + regNo);
            if (badge) badge.textContent = ce10.toFixed(2);

            // Sync to CIE Register
            const cieCell = document.getElementById('cie-cell-ce-' + regNo);
            if (cieCell) cieCell.textContent = ce10.toFixed(2);
            recalculatePracticumCieRow(regNo);

            // Debounced Save
            clearTimeout(ceDebounceTimer);
            const indicator = document.getElementById('ce-autosave-indicator');
            if (indicator) indicator.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin text-amber-400">sync</span> <span class="text-amber-400">Saving...</span>';

            ceDebounceTimer = setTimeout(async () => {
                try {
                    await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/experiment`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            experiment_no: 'Continuous Evaluation',
                            experiment_title: 'Continuous Practical Evaluation (Modules I–IV)',
                            marks_data: [{ reg_no: regNo, total_score_50: score50 }]
                        })
                    });
                    if (indicator) {
                        indicator.innerHTML = '<span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span> <span class="text-emerald-400">Saved</span>';
                        setTimeout(() => {
                            indicator.innerHTML = '<span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Typing';
                        }, 2500);
                    }
                } catch (err) { console.error(err); }
            }, 450);
        }

        // -------------------------------------------------------------
        // 5. CIE ROW RECALCULATION
        // -------------------------------------------------------------
        function recalculatePracticumCieRow(regNo) {
            const cieRow = document.querySelector(`.student-cie-row[data-reg-no="${regNo}"]`);
            if (!cieRow) return;

            const attMark = parseFloat(cieRow.querySelector('.cell-att-mark')?.textContent) || 0;
            const slMark = parseFloat(document.getElementById('cie-cell-sl-' + regNo)?.textContent) || 0;
            const ceMark = parseFloat(document.getElementById('cie-cell-ce-' + regNo)?.textContent) || 0;
            const thMark = parseFloat(document.getElementById('cie-cell-th-' + regNo)?.textContent) || 0;
            const prMark = parseFloat(document.getElementById('cie-cell-pr-' + regNo)?.textContent) || 0;

            const totalCie = Math.min(40, attMark + slMark + ceMark + thMark + prMark);

            const totBadge = document.getElementById('cie-cell-tot-' + regNo);
            if (totBadge) totBadge.textContent = Math.round(totalCie);

            // Also sync to ESE tab CIE cell
            const eseRow = document.querySelector(`.ese-student-row[data-reg-no="${regNo}"]`);
            if (eseRow) {
                const eseCieCell = eseRow.querySelector('td:nth-child(4)');
                if (eseCieCell) eseCieCell.textContent = totalCie.toFixed(2);
                recalculateEseRow(regNo);
            }
        }

        // -------------------------------------------------------------
        // 6. ESE AUTO-SAVE & LIVE CALC
        // -------------------------------------------------------------
        function convertGradeToMarks(grade) {
            switch(grade.toUpperCase().trim()) {
                case 'S': return 57.0;
                case 'A': return 51.0;
                case 'B': return 45.0;
                case 'C': return 39.0;
                case 'D': return 33.0;
                case 'E': return 27.0;
                case 'P': return 24.0;
                default: return 0.0;
            }
        }

        let eseDebounceTimer = null;
        function autoSaveEseTheoryGrade(selectEl) {
            const regNo = selectEl.getAttribute('data-reg');
            recalculateEseRow(regNo);
            triggerEseDebouncedSave(regNo);
        }

        function autoSaveEseLabMark(inputEl) {
            const regNo = inputEl.getAttribute('data-reg');
            recalculateEseRow(regNo);
            triggerEseDebouncedSave(regNo);
        }

        function recalculateEseRow(regNo) {
            const row = document.querySelector(`.ese-student-row[data-reg-no="${regNo}"]`);
            if (!row) return;

            const grade = row.querySelector('.ese-theory-grade')?.value || '-';
            const thMarks = convertGradeToMarks(grade);
            const labMarks = Math.min(40, Math.max(0, parseFloat(row.querySelector('.ese-lab-marks')?.value) || 0));

            // Derive lab grade from /40
            let prGrade = '-';
            const pPct = (labMarks / 40.0) * 100.0;
            if (labMarks > 0) {
                if (pPct >= 90) prGrade = 'S';
                else if (pPct >= 80) prGrade = 'A';
                else if (pPct >= 70) prGrade = 'B';
                else if (pPct >= 60) prGrade = 'C';
                else if (pPct >= 50) prGrade = 'D';
                else if (pPct >= 40) prGrade = 'E';
                else prGrade = 'F';
            }

            const totalEse = thMarks + labMarks;
            const cieRowVal = parseFloat(document.getElementById('cie-cell-tot-' + regNo)?.textContent) || 0;
            const courseTotal = Math.round(cieRowVal + totalEse);

            // Pass criteria: ESE Theory >= 24 (or S..P) AND Course Total >= 56 (40% of 140)
            const passTheory = (thMarks >= 24.0 || ['S','A','B','C','D','E','P'].includes(grade.toUpperCase()));
            const passCombined = (courseTotal >= 56.0);
            const isPassed = (passTheory && passCombined);

            row.querySelector('.ese-th-marks').textContent = thMarks.toFixed(1);
            row.querySelector('.ese-lab-grade').textContent = prGrade;
            row.querySelector('.ese-tot-marks').textContent = totalEse.toFixed(1);
            row.querySelector('.ese-course-tot').textContent = courseTotal.toFixed(1);

            const resultCell = row.querySelector('.ese-result-cell');
            if (resultCell) {
                if (isPassed) {
                    resultCell.innerHTML = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">PASS</span>';
                } else {
                    resultCell.innerHTML = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">FAIL</span>';
                }
            }
        }

        function triggerEseDebouncedSave(regNo) {
            clearTimeout(eseDebounceTimer);
            const indicator = document.getElementById('ese-autosave-indicator');
            if (indicator) indicator.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin text-amber-400">sync</span> <span class="text-amber-400">Saving...</span>';

            eseDebounceTimer = setTimeout(async () => {
                const row = document.querySelector(`.ese-student-row[data-reg-no="${regNo}"]`);
                if (!row) return;

                const grade = row.querySelector('.ese-theory-grade')?.value || '-';
                const labMarks = parseFloat(row.querySelector('.ese-lab-marks')?.value) || 0;

                try {
                    await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/ese`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            marks_data: [{
                                reg_no: regNo,
                                ese_theory_grade: grade,
                                ese_practical_marks: labMarks,
                                theory_absent: grade === 'FE'
                            }]
                        })
                    });
                    if (indicator) {
                        indicator.innerHTML = '<span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span> <span class="text-emerald-400">Saved</span>';
                        setTimeout(() => {
                            indicator.innerHTML = '<span class="material-symbols-rounded text-sm">bolt</span> Auto-Saves on Change';
                        }, 2500);
                    }
                } catch (err) { console.error(err); }
            }, 450);
        }

        async function saveAllEseTheoryGradesFromTable() {
            const rows = document.querySelectorAll('.ese-student-row');
            const marksData = [];
            rows.forEach(row => {
                const reg = row.getAttribute('data-reg-no');
                const grade = row.querySelector('.ese-theory-grade')?.value || '-';
                const labMarks = parseFloat(row.querySelector('.ese-lab-marks')?.value) || 0;
                marksData.push({
                    reg_no: reg,
                    ese_theory_grade: grade,
                    ese_practical_marks: labMarks,
                    theory_absent: grade === 'FE'
                });
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/ese`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks_data: marksData })
                });
                const res = await resp.json();
                Swal.fire({ icon: 'success', title: 'Saved!', text: 'All ESE grades and marks saved successfully.', timer: 1500, showConfirmButton: false });
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to save ESE evaluation records.', 'error');
            }
        }

        // -------------------------------------------------------------
        // 7. LAB SESSIONS / EXPERIMENT ROSTER CONTROLS
        // -------------------------------------------------------------
        function updateExpRosterSummary() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            let totalHours = 0;
            rows.forEach(r => {
                totalHours += parseFloat(r.querySelector('.exp-hours')?.value) || 3;
            });
            const summary = document.getElementById('exp-roster-summary');
            if (summary) {
                summary.textContent = `Total Experiments: ${rows.length} • Allocated Hours: ${totalHours} Hrs`;
            }
        }

        function reindexExperimentRows() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            rows.forEach((r, idx) => {
                const seq = r.querySelector('.exp-seq');
                if (seq) seq.textContent = idx + 1;
            });
            updateExpRosterSummary();
        }

        function addExperimentRow() {
            const emptyRow = document.getElementById('exp-empty-row');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('exp-roster-tbody');
            const count = tbody.querySelectorAll('tr.exp-row').length + 1;

            const tr = document.createElement('tr');
            tr.className = 'exp-row hover:bg-slate-800/40 transition-colors';
            tr.innerHTML = `
                <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 exp-seq text-xs sm:text-sm">${count}</td>
                <td class="p-1.5 border-r border-slate-800">
                    <textarea class="exp-title w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-indigo-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter practical session title..." oninput="autoGrowTextarea(this);updateExpRosterSummary()"></textarea>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="exp-co w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-cyan-400 focus:border-indigo-500 outline-none">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="exp-mod w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-indigo-500 outline-none">
                        <option value="1">Module 1</option><option value="2">Module 2</option><option value="3">Module 3</option><option value="4">Module 4</option>
                    </select>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" min="1" max="12" class="exp-hours w-16 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-center text-white focus:border-indigo-500 outline-none" value="3" oninput="updateExpRosterSummary()">
                        <span class="text-xs text-slate-400 font-medium">Hrs</span>
                    </div>
                </td>
                <td class="p-1.5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="addExperimentRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-bold transition-all cursor-pointer" title="Add experiment row directly below">+</button>
                        <button type="button" onclick="removeExperimentRow(this)" title="Remove experiment row" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer">&times;</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
            reindexExperimentRows();
            autoGrowTextarea(tr.querySelector('textarea.exp-title'));
        }

        function addExperimentRowBelow(btn) {
            const currentRow = btn.closest('tr');
            if (!currentRow) return;

            const tr = document.createElement('tr');
            tr.className = 'exp-row hover:bg-slate-800/40 transition-colors';
            tr.innerHTML = `
                <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 exp-seq text-xs sm:text-sm"></td>
                <td class="p-1.5 border-r border-slate-800">
                    <textarea class="exp-title w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-indigo-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter practical session title..." oninput="autoGrowTextarea(this);updateExpRosterSummary()"></textarea>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="exp-co w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-cyan-400 focus:border-indigo-500 outline-none">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="exp-mod w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-indigo-500 outline-none">
                        <option value="1">Module 1</option><option value="2">Module 2</option><option value="3">Module 3</option><option value="4">Module 4</option>
                    </select>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <div class="flex items-center justify-center gap-1">
                        <input type="number" min="1" max="12" class="exp-hours w-16 bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-center text-white focus:border-indigo-500 outline-none" value="3" oninput="updateExpRosterSummary()">
                        <span class="text-xs text-slate-400 font-medium">Hrs</span>
                    </div>
                </td>
                <td class="p-1.5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="addExperimentRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-bold transition-all cursor-pointer" title="Add experiment row directly below">+</button>
                        <button type="button" onclick="removeExperimentRow(this)" title="Remove experiment row" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer">&times;</button>
                    </div>
                </td>
            `;
            currentRow.insertAdjacentElement('afterend', tr);
            reindexExperimentRows();
            updateExpRosterSummary();
            autoGrowTextarea(tr.querySelector('textarea.exp-title'));
        }

        function removeExperimentRow(btn) {
            const tr = btn.closest('tr');
            if (tr) {
                tr.remove();
                reindexExperimentRows();
                updateExpRosterSummary();
            }
        }

        async function saveCustomExperimentsRoster() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            const experiments = [];

            rows.forEach((r, idx) => {
                const title = r.querySelector('.exp-title')?.value.trim() || `Practical Session ${idx + 1}`;
                const co = r.querySelector('.exp-co')?.value || 'CO1';
                const mod = parseInt(r.querySelector('.exp-mod')?.value) || 1;
                const hours = parseFloat(r.querySelector('.exp-hours')?.value) || 3;

                experiments.push({
                    exp_no: idx + 1,
                    title: title,
                    name: title,
                    co: co,
                    co_id: co,
                    module: mod,
                    module_no: mod,
                    hours: hours
                });
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/experiments/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiments: experiments })
                });
                const res = await resp.json();
                if (res.status === 'SUCCESS') {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: res.message || 'Experiment roster saved successfully.', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire('Error', res.message || 'Failed to save experiments.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to communicate with server.', 'error');
            }
        }

        // -------------------------------------------------------------
        // 8. UNIFIED LESSON PLAN CONTROLS & AUTO-SAVE
        // -------------------------------------------------------------
        function resequenceAllLessonPlanRows() {
            const rows = document.querySelectorAll('#lp-unified-tbody tr.lp-row');
            rows.forEach((r, idx) => {
                const seq = r.querySelector('.lp-seq-cell');
                if (seq) seq.textContent = idx + 1;
            });
            const totalCount = document.getElementById('lp-total-count');
            if (totalCount) totalCount.textContent = rows.length;
        }

        function addLessonPlanRowBelow(btn) {
            const currentRow = btn.closest('tr');
            const newRow = document.createElement('tr');
            newRow.className = 'lp-row hover:bg-slate-800/40 transition-colors';
            newRow.setAttribute('data-id', 'new_' + Date.now());

            newRow.innerHTML = `
                <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 lp-seq-cell text-xs sm:text-sm"></td>
                <td class="p-1.5 border-r border-slate-800">
                    <textarea class="lp-topic w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-blue-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter planned topic..." oninput="autoGrowTextarea(this)"></textarea>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="lp-co w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-emerald-400 focus:border-blue-500 outline-none">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <select class="lp-mode w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-blue-500 outline-none">
                        <option value="Lecture (L)">Lecture (L)</option>
                        <option value="Practical / Lab (P)">Practical / Lab (P)</option>
                        <option value="Series Exam ST">Series Exam (ST)</option>
                        <option value="Practical Test SP">Practical Test (SP)</option>
                        <option value="Tutorial (T)">Tutorial (T)</option>
                    </select>
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="date" class="lp-prop-date w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-300 font-mono text-center focus:border-blue-500 outline-none">
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="date" class="lp-act-date w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-emerald-400 font-mono text-center focus:border-emerald-500 outline-none">
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="text" class="lp-remarks w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-slate-300 focus:border-blue-500 outline-none" placeholder="Optional notes...">
                </td>
                <td class="p-1.5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="addLessonPlanRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs font-bold transition-all cursor-pointer">+</button>
                        <button type="button" onclick="confirmDeleteLessonPlanRow(this.closest('tr').getAttribute('data-id'))" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer">&times;</button>
                    </div>
                </td>
            `;

            currentRow.parentNode.insertBefore(newRow, currentRow.nextSibling);
            resequenceAllLessonPlanRows();
            autoGrowTextarea(newRow.querySelector('textarea.lp-topic'));
        }

        function addCustomLessonPlanRow(tbodyId, defaultMode) {
            const emptyRow = document.getElementById('lp-empty-row');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById(tbodyId);
            const tr = document.createElement('tr');
            tr.className = 'lp-row hover:bg-slate-800/40 transition-colors';
            tr.setAttribute('data-id', 'new_' + Date.now());

            tr.innerHTML = `
                <td class="p-2 text-center font-mono font-bold text-slate-400 border-r border-slate-800 lp-seq-cell text-xs sm:text-sm"></td>
                <td class="p-1.5 border-r border-slate-800">
                    <textarea class="lp-topic w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-white focus:border-blue-500 outline-none resize-y min-h-[38px] overflow-hidden" rows="1" placeholder="Enter planned topic..." oninput="autoGrowTextarea(this)"></textarea>
                </td>
                <td class="p-1.5 text-center border-r border-slate-800">
                    <select class="lp-co w-full text-center bg-slate-950 border border-slate-700/80 rounded px-1.5 py-1.5 text-xs sm:text-sm font-mono font-semibold text-emerald-400 focus:border-blue-500 outline-none">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <select class="lp-mode w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-200 focus:border-blue-500 outline-none">
                        <option value="Lecture (L)" ${defaultMode === 'Lecture' ? 'selected' : ''}>Lecture (L)</option>
                        <option value="Practical / Lab (P)" ${defaultMode !== 'Lecture' ? 'selected' : ''}>Practical / Lab (P)</option>
                        <option value="Series Exam ST">Series Exam (ST)</option>
                        <option value="Practical Test SP">Practical Test (SP)</option>
                        <option value="Tutorial (T)">Tutorial (T)</option>
                    </select>
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="date" class="lp-prop-date w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-slate-300 font-mono text-center focus:border-blue-500 outline-none">
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="date" class="lp-act-date w-full bg-slate-950 border border-slate-700/80 rounded px-2 py-1.5 text-xs sm:text-sm text-emerald-400 font-mono text-center focus:border-emerald-500 outline-none">
                </td>
                <td class="p-1.5 border-r border-slate-800">
                    <input type="text" class="lp-remarks w-full bg-slate-950 border border-slate-700/80 rounded px-2.5 py-1.5 text-xs sm:text-sm text-slate-300 focus:border-blue-500 outline-none" placeholder="Optional notes...">
                </td>
                <td class="p-1.5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="addLessonPlanRowBelow(this)" class="w-7 h-7 flex items-center justify-center rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs font-bold transition-all cursor-pointer">+</button>
                        <button type="button" onclick="confirmDeleteLessonPlanRow(this.closest('tr').getAttribute('data-id'))" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold transition-all cursor-pointer">&times;</button>
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
            resequenceAllLessonPlanRows();
            autoGrowTextarea(tr.querySelector('textarea.lp-topic'));
        }

        async function confirmDeleteLessonPlanRow(planId) {
            const confirmed = await Swal.fire({
                title: 'Delete Lesson Plan Topic?',
                text: 'Are you sure you want to delete this schedule row?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Yes, Delete Row'
            });
            if (!confirmed.isConfirmed) return;

            if (String(planId).startsWith('new_')) {
                const tr = document.querySelector(`tr[data-id="${planId}"]`);
                if (tr) tr.remove();
                resequenceAllLessonPlanRows();
                return;
            }

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/lesson-plan/delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ ids: [planId] })
                });
                const res = await resp.json();
                if (res.status === 'SUCCESS') {
                    const tr = document.querySelector(`tr[data-id="${planId}"]`);
                    if (tr) tr.remove();
                    resequenceAllLessonPlanRows();
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Topic deleted successfully.', timer: 1200, showConfirmButton: false });
                }
            } catch (err) { console.error(err); }
        }

        async function lpAutoSave(planId) {
            if (String(planId).startsWith('new_')) return;
            const tr = document.querySelector(`tr[data-id="${planId}"]`);
            if (!tr) return;

            const badge = document.getElementById('lp-autosave-badge');
            if (badge) badge.classList.remove('hidden');

            const payload = {
                plan_id: planId,
                topic_content: tr.querySelector('.lp-topic')?.value || '',
                co_id: tr.querySelector('.lp-co')?.value || 'CO1',
                mode: tr.querySelector('.lp-mode')?.value || 'Lecture (L)',
                proposed_date: tr.querySelector('.lp-prop-date')?.value || null,
                actual_date: tr.querySelector('.lp-act-date')?.value || null,
                remarks: tr.querySelector('.lp-remarks')?.value || ''
            };

            try {
                await fetch(`/api/r26/classroom/practicum/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });
                if (badge) {
                    badge.innerHTML = '<span class="material-symbols-rounded text-xs text-emerald-400">check_circle</span> Saved';
                    setTimeout(() => badge.classList.add('hidden'), 2000);
                }
            } catch (err) { console.error(err); }
        }

        async function saveAllLessonPlans() {
            const rows = document.querySelectorAll('#lp-unified-tbody tr.lp-row');
            const plans = [];

            rows.forEach((r, idx) => {
                const id = r.getAttribute('data-id');
                plans.push({
                    id: id,
                    hour_no: idx + 1,
                    topic_content: r.querySelector('.lp-topic')?.value || '',
                    co_id: r.querySelector('.lp-co')?.value || 'CO1',
                    mode: r.querySelector('.lp-mode')?.value || 'Lecture (L)',
                    pedagogy: r.querySelector('.lp-mode')?.value || 'Lecture (L)',
                    proposed_date: r.querySelector('.lp-prop-date')?.value || null,
                    actual_date: r.querySelector('.lp-act-date')?.value || null,
                    remarks: r.querySelector('.lp-remarks')?.value || ''
                });
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/lesson-plan/save-all`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const res = await resp.json();
                if (res.status === 'SUCCESS') {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: 'All lesson plan topics saved successfully.', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire('Error', res.message || 'Failed to save lesson plans.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to communicate with server.', 'error');
            }
        }

        async function syncLessonPlanDates() {
            const confirmed = await Swal.fire({
                title: 'Sync Dates with Attendance?',
                text: 'This will update actual delivery dates based on logged classroom attendance records.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Sync Dates'
            });
            if (!confirmed.isConfirmed) return;

            try {
                const resp = await fetch(`/api/classroom/${subjectId}/lesson-plans/sync-dates`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const res = await resp.json();
                Swal.fire({ icon: 'success', title: 'Synchronized!', text: res.message || 'Dates synchronized successfully.', timer: 1500, showConfirmButton: false }).then(() => {
                    location.reload();
                });
            } catch (err) { console.error(err); }
        }

        // -------------------------------------------------------------
        // 9. ATTENDANCE OVERRIDE LOCK & HANDLER
        // -------------------------------------------------------------
        window.attOverrideEnabled = false;

        function promptEnableAttendanceOverride() {
            if (window.attOverrideEnabled) {
                // If already enabled, lock it back
                window.attOverrideEnabled = false;
                document.querySelectorAll('.att-override-field').forEach(input => {
                    input.disabled = true;
                    input.classList.add('cursor-not-allowed', 'opacity-75');
                });
                const btn = document.getElementById('btn-toggle-att-override');
                if (btn) {
                    btn.innerHTML = `<span class="material-symbols-rounded text-sm" id="att-override-lock-icon">lock</span><span id="att-override-lock-text">Enable Override</span>`;
                    btn.classList.remove('bg-amber-600', 'text-white');
                    btn.classList.add('bg-amber-500/15', 'text-amber-300');
                }
                const headerLock = document.getElementById('header-override-lock');
                if (headerLock) {
                    headerLock.innerText = 'lock';
                    headerLock.classList.remove('text-emerald-400');
                    headerLock.classList.add('text-amber-400/80');
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Attendance Override Locked',
                        text: 'Manual editing has been disabled.',
                        timer: 1400,
                        showConfirmButton: false,
                        background: document.body.classList.contains('light') ? '#ffffff' : '#0f172a',
                        color: document.body.classList.contains('light') ? '#0f172a' : '#f8fafc'
                    });
                }
                return;
            }

            // Not enabled: show warning confirmation dialog
            const isLight = document.body.classList.contains('light');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Enable Attendance Override?',
                    html: `
                        <div class="text-left space-y-2 text-xs sm:text-sm">
                            <p class="text-slate-300">
                                <strong>Warning:</strong> Manual attendance override supersedes the official Teams biometric/system percentage for calculating CIE marks.
                            </p>
                            <p class="text-amber-400 font-semibold">
                                Any override entered will be permanently saved into the SBTE continuous assessment record. Do you wish to enable manual data entry?
                            </p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Enable Editing',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#334155',
                    background: isLight ? '#ffffff' : '#0f172a',
                    color: isLight ? '#0f172a' : '#f8fafc'
                }).then((result) => {
                    if (result.isConfirmed) {
                        enableAttendanceOverrideInputs();
                    }
                });
            } else {
                if (confirm('Warning: Manual attendance override supersedes official Teams calculation for CIE marks. Enable manual editing?')) {
                    enableAttendanceOverrideInputs();
                }
            }
        }

        function enableAttendanceOverrideInputs() {
            window.attOverrideEnabled = true;
            document.querySelectorAll('.att-override-field').forEach(input => {
                input.disabled = false;
                input.classList.remove('cursor-not-allowed', 'opacity-75');
            });
            const btn = document.getElementById('btn-toggle-att-override');
            if (btn) {
                btn.innerHTML = `<span class="material-symbols-rounded text-sm" id="att-override-lock-icon">lock_open</span><span id="att-override-lock-text">Lock Override</span>`;
                btn.classList.remove('bg-amber-500/15', 'text-amber-300');
                btn.classList.add('bg-amber-600', 'text-white');
            }
            const headerLock = document.getElementById('header-override-lock');
            if (headerLock) {
                headerLock.innerText = 'lock_open';
                headerLock.classList.remove('text-amber-400/80');
                headerLock.classList.add('text-emerald-400');
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Override Unlocked',
                    text: 'You may now enter or edit attendance override percentages.',
                    timer: 1600,
                    showConfirmButton: false,
                    background: document.body.classList.contains('light') ? '#ffffff' : '#0f172a',
                    color: document.body.classList.contains('light') ? '#0f172a' : '#f8fafc'
                });
            }
        }

        function handleAttOverrideChange(input, regNo) {
            const val = input.value.trim();
            if (val !== '' && !isNaN(val) && parseFloat(val) >= 0) {
                input.className = "att-override-field w-20 text-center rounded px-1.5 py-1 text-sm sm:text-base font-mono font-bold outline-none transition-all border-2 border-amber-400 bg-amber-500/15 text-amber-300";
            } else {
                input.className = "att-override-field w-20 text-center rounded px-1.5 py-1 text-sm sm:text-base font-mono font-bold outline-none transition-all border border-slate-700 bg-slate-950 text-slate-300 focus:border-amber-400";
            }
        }

        async function saveAttOverrideInline(regNo, val) {
            const overrideVal = (val !== '' && !isNaN(val)) ? parseFloat(val) : null;
            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/attendance-override`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, override_percentage: overrideVal })
                });
                const res = await resp.json();
                if (res.status === 'SUCCESS') {
                    let newAttMark = 0;
                    if (res.att_mark !== undefined) {
                        newAttMark = res.att_mark;
                    } else if (overrideVal !== null) {
                        if (overrideVal >= 90) newAttMark = 5;
                        else if (overrideVal >= 80) newAttMark = 4;
                        else if (overrideVal >= 75) newAttMark = 3;
                        else if (overrideVal >= 70) newAttMark = 2;
                        else if (overrideVal >= 65) newAttMark = 1;
                    }
                    const attCell = document.getElementById('cie-cell-att-' + regNo);
                    if (attCell) attCell.innerText = newAttMark;

                    recalculateCieRow(regNo, newAttMark);
                }
            } catch (err) {
                console.error('Failed to update attendance override inline', err);
            }
        }

        function recalculateCieRow(regNo, currentAttMark) {
            const att = currentAttMark !== undefined ? currentAttMark : (parseFloat(document.getElementById('cie-cell-att-' + regNo)?.innerText) || 0);
            const sl = parseFloat(document.getElementById('cie-cell-sl-' + regNo)?.innerText) || 0;
            const ce = parseFloat(document.getElementById('cie-cell-ce-' + regNo)?.innerText) || 0;
            const th = parseFloat(document.getElementById('cie-cell-th-' + regNo)?.innerText) || 0;
            const pr = parseFloat(document.getElementById('cie-cell-pr-' + regNo)?.innerText) || 0;

            const tot = Math.round(att + sl + ce + th + pr);
            const totBadge = document.getElementById('cie-cell-tot-' + regNo);
            if (totBadge) {
                totBadge.innerText = tot;
            }
        }

        async function saveAttendanceOverrideApi(regNo, pct) {
            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/attendance-override`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, override_percentage: pct !== null ? parseFloat(pct) : null })
                });
                const res = await resp.json();
                Swal.fire({ icon: 'success', title: 'Updated!', text: res.message || 'Attendance updated.', timer: 1200, showConfirmButton: false }).then(() => {
                    location.reload();
                });
            } catch (err) { console.error(err); }
        }

        // -------------------------------------------------------------
        // 10. CO-PO MATRIX SAVE
        // -------------------------------------------------------------
        async function savePracticumCoPoMatrix() {
            const selects = document.querySelectorAll('.copo-cell');
            const mappings = { 'CO1': {}, 'CO2': {}, 'CO3': {}, 'CO4': {} };

            selects.forEach(s => {
                const co = s.getAttribute('data-co');
                const po = s.getAttribute('data-po');
                mappings[co][po] = s.value;
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/copo-matrix/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mappings: mappings })
                });
                const res = await resp.json();
                Swal.fire({ icon: 'success', title: 'Saved!', text: 'CO-PO correlation matrix saved successfully.', timer: 1500, showConfirmButton: false });
            } catch (err) { console.error(err); }
        }

        // -------------------------------------------------------------
        // 11. GENERAL MODAL OPEN/CLOSE HELPERS
        // -------------------------------------------------------------
        function openSyllabusModal() { document.getElementById('syllabus-modal').classList.remove('hidden'); }
        function closeSyllabusModal() { document.getElementById('syllabus-modal').classList.add('hidden'); }
        function openSlConfigModal() { document.getElementById('sl-config-modal').classList.remove('hidden'); }
        function closeSlConfigModal() { document.getElementById('sl-config-modal').classList.add('hidden'); }
        function openMidsemInitModal() { document.getElementById('midsem-init-modal').classList.remove('hidden'); }
        function closeMidsemInitModal() { document.getElementById('midsem-init-modal').classList.add('hidden'); }
        function openExitInitModal() { document.getElementById('exit-init-modal').classList.remove('hidden'); }
        function closeExitInitModal() { document.getElementById('exit-init-modal').classList.add('hidden'); }
        function closeQpModal() { document.getElementById('qp-modal').classList.add('hidden'); }

        async function openQpPreviewModal(seriesNo, coTag, mode) {
            document.getElementById('qp-modal-title').innerHTML = `<span class="material-symbols-rounded text-sky-400">quiz</span><span>${seriesNo} Question Paper &amp; Valuation Scheme</span>`;
            document.getElementById('qp-editor-container').innerHTML = `
                <div class="p-6 text-center text-slate-400 space-y-3">
                    <p class="text-xs">Question Paper module active for <strong>${seriesNo}</strong> (Revision 2026 Diploma Scheme).</p>
                    <div class="flex justify-center gap-3">
                        <a href="/r26/classroom/practicum/${subjectId}/series-qp-print?series=${encodeURIComponent(seriesNo)}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-semibold no-underline">Print Official QP</a>
                        <a href="/r26/classroom/practicum/${subjectId}/series-scheme-print?series=${encodeURIComponent(seriesNo)}" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold no-underline">Print Valuation Scheme</a>
                    </div>
                </div>
            `;
            document.getElementById('qp-modal').classList.remove('hidden');
        }

        async function uploadSyllabusPdf(e) {
            e.preventDefault();
            const fileInput = document.getElementById('syllabus_file_input');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                Swal.fire('Warning', 'Please select a syllabus PDF file first.', 'warning');
                return;
            }
            const form = document.getElementById('syllabusUploadForm');
            const formData = new FormData(form);
            const btn = document.getElementById('btn-upload-syllabus');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">sync</span> Parsing...';

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/upload-syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const res = await resp.json();
                if (res.status === 'SUCCESS') {
                    Swal.fire({ icon: 'success', title: 'Parsed!', text: res.message || 'Syllabus PDF uploaded and parsed successfully.' }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed to parse syllabus PDF.', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to upload syllabus PDF. Please check server logs.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Upload & Parse';
            }
        }

        async function saveSlConfig(e) {
            e.preventDefault();
            const form = e.target;
            const configs = { 'CO1': {}, 'CO2': {}, 'CO3': {}, 'CO4': {} };
            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                configs[co] = {
                    assignment: form[`cfg_${co}_assignment`]?.checked ?? false,
                    mcq: form[`cfg_${co}_mcq`]?.checked ?? false
                };
            });

            try {
                const resp = await fetch(`/api/r26/classroom/practicum/${subjectId}/evaluate/self-learning/configs`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ configs: configs })
                });
                const res = await resp.json();
                closeSlConfigModal();
                Swal.fire({ icon: 'success', title: 'Saved', text: 'SLA settings saved.', timer: 1200, showConfirmButton: false });
            } catch (err) { console.error(err); }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        function printSubtabReport(title, containerId) {
            window.print();
        }
    </script>

    <!-- CARMIE ASSISTANT & SUPPORT DESK OVERLAYS -->
    @include('partials.carmie_assistant')
    @include('partials.support_desk_overlay')
    @include('partials.birthday_wish_modal')

</body>
</html>
