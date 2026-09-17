<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carmel Linx - [{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practicum Virtual Classroom - {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Clamp extra-bold and black fonts to clean bold */
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
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(59, 130, 246, 0.35);
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.45);
        }

        .mode-btn {
            background: rgba(2, 6, 23, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .mode-btn:hover {
            color: #ffffff;
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .mode-btn.active {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.55) 0%, rgba(15, 23, 42, 0.92) 100%);
            color: #93c5fd !important;
            border: 1px solid rgba(59, 130, 246, 0.45);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.5);
        }

        .subtab-btn {
            background: transparent;
            color: #94a3b8;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            font-size: 12px !important;
            padding: 0.45rem 0.75rem !important;
            border-radius: 8px;
        }

        .subtab-btn:hover {
            color: #f8fafc;
            background: rgba(30, 41, 59, 0.7);
        }

        .subtab-btn.active {
            color: #38bdf8 !important; /* vivid sky-400 */
            border-bottom-color: #0284c7;
            background: rgba(14, 165, 233, 0.16) !important;
            font-weight: 700;
        }

        /* Form Inputs & Select Controls */
        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            background-color: #020617 !important; /* slate-950 */
            border: 1px solid #1e293b !important; /* border-slate-800 */
            color: #f1f5f9 !important;            /* text-slate-100 */
            border-radius: 8px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }

        /* Flatpickr dark theme adjustments */
        .flatpickr-calendar {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.7) !important;
            border-radius: 12px !important;
        }
        .flatpickr-calendar .flatpickr-months {
            background: #0f172a !important;
            border-bottom: 1px solid #1e293b !important;
        }
        .flatpickr-calendar .flatpickr-current-month {
            color: #f1f5f9 !important;
            font-weight: 700 !important;
        }
        .flatpickr-calendar .flatpickr-weekday {
            color: #94a3b8 !important;
            font-weight: 600 !important;
        }
        .flatpickr-calendar .flatpickr-day {
            color: #e2e8f0 !important;
            border-radius: 8px !important;
        }
        .flatpickr-calendar .flatpickr-day:hover {
            background: #1e293b !important;
            color: #38bdf8 !important;
        }
        .flatpickr-calendar .flatpickr-day.selected {
            background: #0284c7 !important;
            border-color: #0284c7 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .flatpickr-calendar .flatpickr-day.today {
            border-color: #38bdf8 !important;
        }

        /* Strict Table Styling & High Contrast Grid Lines */
        table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100%;
        }

        table th {
            background-color: #020617 !important; /* slate-950 */
            color: var(--text-muted) !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            border-bottom: 1px solid var(--border-color) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.04) !important;
        }

        table td {
            border-bottom: 1px solid var(--border-color) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.03) !important;
        }

        table tr:hover td {
            background-color: rgba(30, 41, 59, 0.4) !important; /* slate-800/40 */
        }

        /* Dashboard Typography & Compact Form Controls */
        body {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Compact Table Typography */
        table th {
            font-size: 0.75rem !important;
            padding: 0.45rem 0.6rem !important;
        }

        table td {
            font-size: 0.8125rem !important;
            padding: 0.4rem 0.6rem !important;
        }

        /* Compact Font Size Specifically for 90-Hour Dense Lesson Planner */
        .lp-table input, .lp-table select, .lp-table td, .lp-table th, .lp-table span, .lp-table button {
            font-size: 0.8125rem !important;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .lp-table select {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }

        .lp-table select option {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            font-weight: 600 !important;
            padding: 6px 10px !important;
        }

        .lp-table select[id^="lp-co-"] {
            background-color: #0f172a !important;
            color: #fcd34d !important;
            font-weight: 700 !important;
            border-color: rgba(245, 158, 11, 0.4) !important;
            min-width: 62px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
            text-align: center !important;
            text-align-last: center !important;
        }

        .lp-table select[id^="lp-co-"] option {
            background-color: #0f172a !important;
            color: #fcd34d !important;
            font-weight: 700 !important;
        }

        /* Header Elements */
        .header-subtitle, .header-subtitle span {
            font-size: 0.8125rem !important;
        }

        .subject-meta-text, .subject-meta-text span,
        .schedule-meta-text, .schedule-meta-text span {
            font-size: 0.6875rem !important; /* 11px */
            letter-spacing: 0.01em !important;
        }

        .table-compact-header th, .table-compact-header tr th {
            font-size: 0.75rem !important;
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
        }

        .header-badge, .header-badge span, .header-badge div {
            font-size: 0.75rem !important;
            padding: 0.2rem 0.5rem !important;
        }

        .header-btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.6rem !important;
            border-radius: 7px !important;
            transition: all 0.2s ease !important;
        }

        .header-btn:hover {
            transform: translateY(-1px);
        }

        .header-btn span {
            font-size: 0.75rem !important;
        }

        .header-btn svg {
            width: 0.875rem !important;
            height: 0.875rem !important;
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 6px;
            border: 2px solid #0f172a;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
        /* Hide number input spinners */
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .no-spinners { -moz-appearance: textfield; }
    </style>
</head>
<body class="min-h-screen pb-12 bg-slate-900 text-slate-100">
    @php
        $role = Session::get('userRole');
        $dashboardUrl = '/dashboard/lecturer';
        if ($role === 'HOD') {
            $dashboardUrl = '/dashboard/hod';
        } elseif ($role === 'Principal') {
            $dashboardUrl = '/dashboard/principal';
        } elseif ($role === 'Demonstrator') {
            $dashboardUrl = '/dashboard/demonstrator';
        } elseif ($role === 'Super_Admin') {
            $dashboardUrl = '/dashboard/superadmin';
        } elseif ($role === 'Admin') {
            $dashboardUrl = '/dashboard/admin';
        } elseif ($role === 'Gen_Dept_Coordinator_Aided') {
            $dashboardUrl = '/dashboard/general-coordinator-aided';
        } elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') {
            $dashboardUrl = '/dashboard/general-coordinator-sf';
        } elseif ($role === 'Trade_Instructor') {
            $dashboardUrl = '/dashboard/tradeinstructor';
        } elseif ($role === 'Workshop_Superintendent') {
            $dashboardUrl = '/dashboard/workshop';
        }
    @endphp

    <!-- 1. TOP HEADER CONTAINER -->
    <header class="glass-panel sticky top-0 z-40 border-b border-slate-800 px-4 md:px-8 py-3">
        <div class="max-w-[98%] mx-auto flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3">
            
            <!-- Left: Subject Details -->
            <div class="flex items-center space-x-3.5 w-full xl:w-auto">
                <a href="{{ $dashboardUrl }}" class="flex items-center gap-2 shrink-0 no-underline text-white group" title="Return to Dashboard">
                    <span class="material-symbols-rounded text-sky-400 text-xl group-hover:scale-105 transition-transform">school</span>
                    <span class="font-extrabold text-white text-base tracking-tight group-hover:text-sky-300 transition-colors">Carmel Linx</span>
                    <span class="text-slate-600 font-bold">|</span>
                </a>
                <div class="space-y-0.5">
                    <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ $batchSubject->subject_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-xs font-semibold whitespace-nowrap">
                            🔬 Basic Science Practicum ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }})
                        </span>

                        @php
                            $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled();
                        @endphp
                        @if($isAiActive)
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-800/90 text-slate-300 border border-slate-700 text-xs font-medium whitespace-nowrap flex items-center space-x-1.5" title="AI Support API Active">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span>AI Active</span>
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-950/40 text-amber-400 border border-amber-900/60 text-xs font-medium whitespace-nowrap flex items-center space-x-1.5" title="AI is deactivated. Generating content from structured syllabus database and offline banks.">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>AI Offline (Local DB)</span>
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-slate-400 subject-meta-text leading-tight mt-0.5">
                        Subject Code: <span class="text-white font-semibold font-mono">{{ $batchSubject->subject_code }}</span> | 
                        Batch Code: <span class="text-amber-400 font-bold font-mono">{{ $batchSubject->classroom_id }}</span> | 
                        Branch: <span class="text-blue-300 font-semibold">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch) }}</span> | 
                        Semester: <span class="text-white font-semibold">{{ $practicumCourseFile->semester }}</span>
                    </p>
                </div>
            </div>

            <!-- Right: Logged-In & Assigned Faculty Info & Back Button -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <div class="px-3 py-1.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-slate-300 flex items-center space-x-2.5 header-subtitle">
                    <div class="p-1 rounded-lg bg-sky-500/15 text-sky-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="text-white font-semibold">
                        Faculty: <span class="text-sky-300 font-bold">
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

                <a href="javascript:void(0)" onclick="window.close(); setTimeout(function() { let ref = document.referrer; if (ref && (ref.includes('/dashboard/') || ref.includes('/classroom/'))) { window.location.href = ref; } else { window.location.href = '{{ $dashboardUrl }}'; } }, 150);" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 font-bold transition-all flex items-center space-x-1.5 border border-rose-500/30 flex-shrink-0 cursor-pointer no-underline text-[11px]" title="Dashboard">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. SUB-HEADER CONTROL CONSOLE BAR -->
    <div class="max-w-[98%] mx-auto px-4 md:px-8 mt-3">
        <div class="glass-card p-3.5 rounded-xl border border-slate-800 flex items-center justify-between flex-wrap gap-3">
            
            <!-- Hours & Assessment Details (Dynamic from Uploaded Syllabus) -->
            <div class="flex items-center space-x-3 flex-wrap gap-y-1 text-slate-300 schedule-meta-text">
                <span>Theory: <span class="font-bold text-blue-400">45 Hrs</span> (L)</span>
                <span class="text-slate-600 font-bold">•</span>
                <span>Practical: <span class="font-bold text-emerald-400">45 Hrs</span> (P)</span>
                <span class="text-slate-600 font-bold">•</span>
                <span>Total Schedule: <span class="font-bold text-sky-400">90 Hrs</span></span>
                <span class="text-slate-600 font-bold">•</span>
                <span>CIE: <span class="font-bold text-amber-400">40M</span> <span class="text-slate-500">|</span> ESE: <span class="font-bold text-sky-400">60M</span> <span class="text-slate-500">(Total: <span class="text-emerald-400 font-bold">100M</span>)</span></span>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center space-x-2 flex-wrap gap-y-1.5">
                
                <!-- Upload Syllabus -->
                <button onclick="openSyllabusModal()" class="header-btn px-2.5 py-1 rounded-lg bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/30 hover:border-sky-400/50 text-sky-300 font-semibold transition-all flex items-center space-x-1.5 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload Syllabus</span>
                </button>

                <!-- View Syllabus PDF -->
                @if($practicumCourseFile->syllabus_pdf_path)
                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="header-btn px-2.5 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 hover:border-emerald-400/50 text-emerald-300 font-semibold transition-all flex items-center space-x-1.5 shadow-xs no-underline">
                    <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>View Syllabus PDF</span>
                </a>
                @endif

                <!-- Course File Console -->
                <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" class="header-btn px-2.5 py-1 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 hover:border-amber-400/50 text-amber-300 font-semibold transition-all flex items-center space-x-1.5 shadow-xs no-underline">
                    <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Course File Console</span>
                </a>

                <!-- Fullscreen Button -->
                <button onclick="toggleFullscreen()" class="p-1.5 rounded-lg bg-slate-800/90 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/80 transition-all flex items-center justify-center cursor-pointer shadow-xs" title="Toggle Fullscreen">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. TOP-LEVEL DUAL MODE SWITCHER -->
    <main class="max-w-[98%] mx-auto px-4 md:px-8 mt-4">
        
        <div class="glass-panel p-1.5 rounded-xl mb-4 flex items-center justify-center space-x-2 max-w-3xl mx-auto">
            <button onclick="switchMode('theory')" id="mode-btn-theory" class="mode-btn active w-1/2 py-2 rounded-lg font-semibold transition-all flex items-center justify-center space-x-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>📖 Virtual Theory Classroom</span>
            </button>
            <button onclick="switchMode('lab')" id="mode-btn-lab" class="mode-btn w-1/2 py-2 rounded-lg font-semibold text-slate-300 hover:text-white hover:bg-slate-800/60 transition-all flex items-center justify-center space-x-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <span>🔬 Virtual Lab</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- MODE A: VIRTUAL THEORY CLASSROOM (PRACTICUM)                              -->
        <!-- ========================================================================= -->
        <div id="mode-theory-container" class="space-y-5">
            
            <!-- Theory Sub-Tabs Navigation -->
            <div class="bg-slate-950 p-1.5 rounded-xl border border-slate-800 shadow-2xl flex items-center space-x-1.5 overflow-x-auto">
                <button onclick="switchTheorySubtab('overview')" id="theory-tab-overview" class="subtab-btn active text-[12px] whitespace-nowrap">📘 Modules & COs</button>
                <button onclick="switchTheorySubtab('planner')" id="theory-tab-planner" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">📅 Lesson Plan</button>
                <button onclick="switchTheorySubtab('sl')" id="theory-tab-sl" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">📝 Self-Learning</button>
                <button onclick="switchTheorySubtab('series')" id="theory-tab-series" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">✍️ Series Exams</button>
                <button onclick="switchTheorySubtab('ese')" id="theory-tab-ese" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">🏆 ESE Grades</button>
                <button onclick="switchTheorySubtab('surveys')" id="theory-tab-surveys" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">📊 Surveys</button>
                <button onclick="switchTheorySubtab('attendance')" id="theory-tab-attendance" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">📅 Attendance</button>
                <button onclick="switchTheorySubtab('materials')" id="theory-tab-materials" class="subtab-btn text-[12px] text-slate-300 hover:text-white whitespace-nowrap">📁 Pre-Class Hub</button>
                <button onclick="switchTheorySubtab('attainment')" id="theory-tab-attainment" class="subtab-btn text-[12px] text-amber-300 hover:text-amber-200 whitespace-nowrap">🎯 Attainment & Reports</button>
            </div>

            <!-- Subtab 1: Theory Modules, COs & CO-PO Mapping Table -->
            <div id="theory-subcontent-overview" class="space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">
                    <div class="lg:col-span-2 space-y-3 min-w-0">
                        @foreach(($practicumCourseFile->parsed_modules ?? []) as $mod)
                        <div class="glass-card p-3.5 sm:p-4 rounded-xl border border-slate-800">
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <h3 class="font-bold text-blue-400 text-xs sm:text-sm">Module {{ $mod['module_id'] }}: {{ $mod['title'] }}</h3>
                                <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-300 font-semibold text-[11px] whitespace-nowrap">{{ $mod['hours'] ?? 15 }} Lecture Hours</span>
                            </div>
                            <p class="text-slate-300 text-xs leading-relaxed break-words whitespace-normal">{{ $mod['content'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 min-w-0">
                        <div class="glass-card p-3.5 sm:p-4 rounded-xl border border-slate-800 space-y-2.5">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <h3 class="font-bold text-emerald-400 text-xs sm:text-sm truncate">🔬 Practical Lab Experiments Summary</h3>
                                <span class="text-[11px] px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-300 font-semibold border border-emerald-500/20 whitespace-nowrap flex-shrink-0">{{ $practicalHours ?? 45 }} P Hours</span>
                            </div>
                            <div class="space-y-2 max-h-[520px] overflow-y-auto pr-1">
                                @php
                                    $previewExps = $practicumCourseFile->parsed_experiments ?? [];
                                    if (is_string($previewExps)) $previewExps = json_decode($previewExps, true) ?: [];
                                @endphp
                                @foreach($previewExps as $exp)
                                <div class="p-2.5 rounded-xl bg-slate-900/70 border border-slate-800/80">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="font-bold text-emerald-400 text-[11px] font-mono">{{ $exp['code'] ?? ($exp['experiment_no'] ?? '') }}</span>
                                        <span class="px-1.5 py-0.5 rounded bg-sky-500/10 text-sky-300 text-[10.5px] font-semibold border border-sky-500/20">{{ $exp['co_id'] ?? 'CO1' }}</span>
                                    </div>
                                    <p class="text-slate-200 text-xs font-medium leading-snug break-words">{{ $exp['title'] ?? '' }}</p>
                                    <div class="text-slate-400 text-[11px] mt-1 font-semibold">{{ $exp['hours'] ?? 3 }} Hours Session</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CO-PO Articulation Matrix Table -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-3">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2">
                        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                            <span>🎯 Course Articulation Matrix (CO-PO & CO-PSO Mapping)</span>
                        </h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-slate-400 text-xs font-medium">Correlation: 3 = High, 2 = Med, 1 = Low</span>
                            <button onclick="savePracticumCoPoMatrix()" id="saveCoPoMatrixBtn" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-all flex items-center space-x-1.5 cursor-pointer shadow-md no-print">
                                <span>💾 Save / Update Matrix</span>
                            </button>
                            <button onclick="printSubtabReport('Theory Modules & CO-PO Matrix Report', 'theory-subcontent-overview')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print">
                                <span>🖨️ Print Report</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr class="bg-slate-900/80 border-b border-slate-800 text-slate-300 font-bold">
                                    <th class="p-3 text-left w-24">CO</th>
                                    <th class="p-3 text-left">Course Outcome Description</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-2 w-10 font-bold text-sky-400">PO{{ $p }}</th>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                    <th class="p-2 w-10 font-bold text-sky-400">PSO{{ $s }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800" id="practicumCoPoMatrixTbody">
                                @foreach(($practicumCourseFile->parsed_cos ?? []) as $co)
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3 text-left font-bold text-amber-400">{{ $co['id'] }}</td>
                                    <td class="p-3 text-left text-slate-300 text-sm">{{ $co['description'] }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                        @php
                                            $val = $mappings[$co['id']]['PO' . $p] ?? '';
                                            if ($val === '-') $val = '';
                                        @endphp
                                        <td class="p-1">
                                            <input type="text" maxlength="1" value="{{ $val }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-9 h-8 bg-slate-900 border border-slate-700 rounded px-1 text-center font-bold text-emerald-400 focus:border-cyan-400 outline-none text-xs" data-co="{{ $co['id'] }}" data-target="PO{{ $p }}">
                                        </td>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                        @php
                                            $val = $mappings[$co['id']]['PSO' . $s] ?? '';
                                            if ($val === '-') $val = '';
                                        @endphp
                                        <td class="p-1">
                                            <input type="text" maxlength="1" value="{{ $val }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-9 h-8 bg-slate-900 border border-slate-700 rounded px-1 text-center font-bold text-sky-300 focus:border-cyan-400 outline-none text-xs" data-co="{{ $co['id'] }}" data-target="PSO{{ $s }}">
                                        </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Subtab 2: Combined 90-Hour Practicum Lesson Planner (Interactive Table & Print) -->
            <div id="theory-subcontent-planner" class="glass-card p-5 rounded-xl border border-slate-800 hidden space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3 border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Practicum Theory Lesson Planner ({{ $theoryHours ?? 45 }} Hours Schedule)</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Includes {{ $theoryHours ?? 45 }} Theory Lecture Hours (L) and Series Exams (ST).</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="addCustomLessonPlanRow('lp-theory-tbody', 'L')" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Row</span>
                        </button>
                        <button onclick="saveAllLessonPlans()" class="px-3 py-1.5 bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Changes</span>
                        </button>
 
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 no-underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Lesson Plan</span>
                        </a>
                    </div>
                </div>
 
                <div class="max-h-[650px] overflow-y-auto">
                    <table class="w-full text-left border-collapse lp-table">
                        <thead class="sticky top-0 z-10 bg-slate-900 shadow">
                         <tr class="border-b border-slate-800 text-slate-500 font-medium text-[10px] uppercase tracking-wider">
                                <th class="p-1.5 w-12 text-center">Day/Hr</th>
                                <th class="p-1.5 w-28">Pedagogy</th>
                                <th class="p-1.5 w-24">Prop Date</th>
                                <th class="p-1.5 w-24">Act Date</th>
                                <th class="p-1.5 w-auto">Topic &amp; Content Description</th>
                                <th class="p-1.5 w-16 text-center">CO</th>
                                <th class="p-1.5 w-14 text-center">Batch</th>
                                <th class="p-1.5 w-14 text-center">Hours</th>
                                <th class="p-1.5 w-24">Remarks</th>
                                <th class="p-1.5 w-8 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="lp-theory-tbody" class="divide-y divide-slate-800/60 text-sm">
                            @foreach($lessonPlans->whereIn('mode', ['L', 'ST']) as $plan)
                            <tr id="lp-row-{{ $plan->id }}" data-plan-id="{{ $plan->id }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2 font-normal text-center text-white text-xs">{{ $plan->day_no }}</td>
                                <td class="p-2">
                                    <select id="lp-pedagogy-{{ $plan->id }}" onchange="onPedagogyChange({{ $plan->id }}, this.value); lpAutoSave({{ $plan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-normal text-xs w-full {{ $plan->mode === 'L' ? 'text-blue-400' : ($plan->mode === 'P' ? 'text-emerald-400' : 'text-sky-400') }}">
                                        <option value="Lecture (L)" {{ ($plan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($plan->mode === 'L' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                        <option value="Practical Lab (P)" {{ ($plan->pedagogy ?? '') === 'Practical Lab (P)' || ($plan->mode === 'P' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                        <option value="Theory Series Exam (ST)" {{ ($plan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($plan->mode === 'ST' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                        <option value="Practical Series Exam (SP)" {{ ($plan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($plan->mode === 'SP' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                        <option value="PPT Presentation" {{ ($plan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                        <option value="Demonstration" {{ ($plan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Group Activity" {{ ($plan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    @php
                                        $propVal = '';
                                        if (!empty($plan->proposed_date)) {
                                            $propVal = preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $plan->proposed_date, $m) ? "{$m[3]}/{$m[2]}/{$m[1]}" : $plan->proposed_date;
                                        }
                                    @endphp
                                    <input type="text" id="lp-prop-{{ $plan->id }}" value="{{ $propVal }}" placeholder="dd/mm/yyyy" onchange="lpAutoSave({{ $plan->id }})" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-slate-200 text-xs w-full text-center font-mono focus:border-blue-500 outline-none">
                                </td>
                                <td class="p-2">
                                    @php
                                        $actVal = '';
                                        if (!empty($plan->actual_date)) {
                                            $actVal = preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $plan->actual_date, $m) ? "{$m[3]}/{$m[2]}/{$m[1]}" : $plan->actual_date;
                                        }
                                    @endphp
                                    <input type="text" id="lp-act-{{ $plan->id }}" value="{{ $actVal }}" placeholder="dd/mm/yyyy" onchange="lpAutoSave({{ $plan->id }})" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-emerald-400 text-xs w-full text-center font-mono focus:border-emerald-500 outline-none">
                                </td>
                                <td class="p-2">
                                    <textarea id="lp-topic-{{ $plan->id }}" rows="2" class="bg-slate-900 border border-slate-700 rounded p-1.5 text-slate-100 text-xs font-normal w-full focus:border-blue-500 outline-none resize-y leading-snug" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'; lpAutoSave({{ $plan->id }})">{{ $plan->topic_content }}</textarea>
                                </td>
                                <td class="p-2 text-center">
                                    <select id="lp-co-{{ $plan->id }}" onchange="lpAutoSave({{ $plan->id }})" class="bg-slate-900 border border-amber-500/40 rounded px-1 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOpt)
                                            <option value="{{ $coOpt }}" {{ ($plan->co_id ?? 'CO1') === $coOpt ? 'selected' : '' }} style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="lp-batch-td-{{ $plan->id }}" class="p-2 text-center">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        @php
                                            $bVal = $plan->sub_batch ?? 'A & B';
                                            if (in_array($bVal, ['Batch A & B', 'Batch A & B (Combined)', 'A & B'])) $bVal = 'A & B';
                                            elseif (in_array($bVal, ['Batch A', 'A'])) $bVal = 'A';
                                            elseif (in_array($bVal, ['Batch B', 'B'])) $bVal = 'B';
                                            else $bVal = 'ALL';
                                        @endphp
                                        <select id="lp-batch-{{ $plan->id }}" onchange="lpAutoSave({{ $plan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-mono text-xs text-emerald-400 w-full text-center">
                                            <option value="A & B" {{ $bVal === 'A & B' ? 'selected' : '' }}>A & B</option>
                                            <option value="A" {{ $bVal === 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ $bVal === 'B' ? 'selected' : '' }}>B</option>
                                            <option value="ALL" {{ $bVal === 'ALL' ? 'selected' : '' }}>ALL</option>
                                        </select>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded bg-slate-900/80 text-slate-400 font-mono text-[11px] border border-slate-800 inline-block">
                                            ALL
                                        </span>
                                        <input type="hidden" id="lp-batch-{{ $plan->id }}" value="ALL">
                                    @endif
                                </td>
                                <td id="lp-hours-td-{{ $plan->id }}" class="p-2 text-center font-normal">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        <span class="px-1 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-normal">3 Hrs</span>
                                    @else
                                        <span class="px-1 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-normal">1 Hr</span>
                                    @endif
                                </td>
                                <td class="p-2 pr-3">
                                    <input type="text" id="lp-remarks-{{ $plan->id }}" value="{{ $plan->remarks }}" placeholder="Status/Remarks" onchange="lpAutoSave({{ $plan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-slate-400 text-xs w-full">
                                </td>
                                <td class="p-1 text-center">
                                    <button type="button" onclick="confirmDeleteLessonPlanRow({{ $plan->id }})" title="Delete row" class="w-6 h-6 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all text-xs font-bold mx-auto">&times;</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-800">
                    <button type="button" onclick="addCustomLessonPlanRow('lp-theory-tbody', 'L')" class="px-3.5 py-2 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Row (Customization)</span>
                    </button>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-slate-400 hidden md:inline">Save changes for all theory lesson plan rows.</span>
                        <button type="button" onclick="saveAllLessonPlans()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all flex items-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Changes</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Subtab 3: Self-Learning Activities (CA - 5 CIA Marks) -->
            <div id="theory-subcontent-sl" class="glass-card p-5 rounded-xl border border-slate-800 hidden space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 border-b border-slate-800 pb-3">
                    <div class="space-y-1">
                        <h3 class="text-lg font-bold text-white">Self-Learning Evaluation & Customization (CA - 5 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Mandatory Core: <span class="font-bold text-amber-400">Assignment</span> & <span class="font-bold text-emerald-400">MCQ</span> (Out of 15 Marks).<br>
                            Custom Catalog: Case Study, Quiz, Activity, Microproject, Mini Project, Report, Exercises, Presentation.
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 flex-wrap gap-y-2 flex-shrink-0">
                        <button onclick="openSlConfigModal()" class="header-btn px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-all flex items-center space-x-1.5 shadow whitespace-nowrap">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Customize Activities</span>
                        </button>

                        <button onclick="openSlMarksModal()" class="header-btn px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md flex items-center space-x-1.5 transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Enter CA Marks</span>
                        </button>

                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-splitup" target="_blank" class="header-btn px-3 py-2 rounded-lg bg-teal-600/20 hover:bg-teal-600/35 border border-teal-500/40 text-teal-300 font-bold text-xs transition-all flex items-center space-x-1.5 whitespace-nowrap no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Splitup Report</span>
                        </a>

                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-self-learning-summary" target="_blank" class="header-btn px-3 py-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/35 border border-blue-500/40 text-blue-300 font-bold text-xs transition-all flex items-center space-x-1.5 whitespace-nowrap no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Print Summary Report</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase tracking-wider bg-slate-900/80 text-xs">
                                <th class="p-2 text-center w-12">Roll</th>
                                <th class="p-2">SBTE No</th>
                                <th class="p-2">Name</th>
                                <th class="p-2">Activities</th>
                                <th class="p-2 text-center">Raw Score</th>
                                <th class="p-2 text-center">Converted CIA (5M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-normal text-xs text-slate-300">
                            @foreach($studentResults as $res)
                            <tr class="hover:bg-slate-800/40 transition-all">
                                <td class="p-2 text-center text-slate-400">{{ $res['roll_no'] }}</td>
                                <td class="p-2 font-mono text-emerald-400/90 text-xs">{{ $res['sbte_reg_no'] ?: '-' }}</td>
                                <td class="p-2 text-slate-200 text-xs">{{ $res['name'] }}</td>
                                <td class="p-2">
                                    <div class="flex items-center space-x-1 text-[11px]">
                                        <span class="px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/20">Assignment</span>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">MCQ</span>
                                    </div>
                                </td>
                                <td class="p-2 text-center text-slate-300">{{ number_format(($res['sl_marks'] / 5.0) * 15.0, 2) }} / 15.00</td>
                                <td class="p-2 text-center font-semibold text-emerald-400">{{ number_format($res['sl_marks'], 2) }} / 5.00</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 4: Theory Series Examinations -->
            <div id="theory-subcontent-series" class="space-y-4 hidden">

                <!-- QP Generator Panel — 2 Cards for Basic Science Practicum -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 no-print">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 flex-wrap">
                                <span>📄 Series Exam QP Generator</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-sky-500/15 text-sky-300 border border-sky-500/30 text-xs font-semibold">
                                    🔬 Basic Science Practicum (50 Marks | 2 Hours)
                                </span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-1">
                                SBTE Pattern: Part A (4×1=4M) + Part B (6×3=18M) + Part C (4×7=28M) = 50 Marks | 2 Hours | Averaged (CA4 &amp; CA5) &amp; Scaled to 10 CIA Marks
                            </p>
                        </div>
                    </div>

                    <!-- 2 Series Cards for Basic Science -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach([
                            'Series 1' => ['label' => 'Series Exam 1 (CA4)', 'co' => 'CO1 + CO2', 'modules' => 'Modules I & II'],
                            'Series 2' => ['label' => 'Series Exam 2 (CA5)', 'co' => 'CO3 + CO4', 'modules' => 'Modules III & IV']
                        ] as $series => $sMeta)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-900/15' : 'border-slate-700 bg-slate-800/50' }} p-4 flex flex-col gap-2.5">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-white text-sm">{{ $sMeta['label'] }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $sMeta['modules'] }}</span>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-mono {{ $savedQp ? 'bg-emerald-600/30 text-emerald-300' : 'bg-slate-700 text-slate-400' }}">{{ $sMeta['co'] }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-400 font-semibold">✅ QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $sMeta['co'] }}', 'ai')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 transition-all text-center cursor-pointer">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $sMeta['co'] }}', 'manual')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 transition-all text-center cursor-pointer">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-700/50 pt-2.5 flex flex-col gap-2">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-2 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-blue-500/30 text-sky-300 text-center block no-underline">
                                    🖨️ Print QP
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block no-underline">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block no-underline">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->

                    <div id="qp-gen-status" class="mt-3 text-xs text-slate-400 hidden"></div>
                </div><!-- /QP Generator Panel -->


                <!-- Theory Series Marks -->
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-base font-semibold text-slate-200">Theory Series Examinations (CA4 &amp; CA5)</h3>
                            <p class="text-slate-400 text-xs mt-0.5">2 Series Exams (CA4: Mod 1&amp;2, CA5: Mod 3&amp;4 - 2 Hours each out of 50 Marks), averaged and scaled to 10 CIA Marks</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory Series Examinations Report', 'theory-subcontent-series')" class="header-btn px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openSeriesTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium text-xs shadow-sm cursor-pointer">Enter Theory Series Marks</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-500 font-medium bg-slate-900/60 text-[10px] uppercase tracking-wider">
                                    <th class="p-2">Roll</th>
                                    <th class="p-2">SBTE Reg No</th>
                                    <th class="p-2">Student Name</th>
                                    <th class="p-2 text-center w-36">Series 1 (CA4: Mod 1&amp;2)<br><span class="text-[9px] text-slate-500 normal-case font-normal">Max 50 Marks (2 Hrs)</span></th>
                                    <th class="p-2 text-center w-36">Series 2 (CA5: Mod 3&amp;4)<br><span class="text-[9px] text-slate-500 normal-case font-normal">Max 50 Marks (2 Hrs)</span></th>
                                    <th class="p-2 text-center w-28">Avg (/50)</th>
                                    <th class="p-2 text-center w-28">CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                @php
                                    $stEvals = $seriesTheoryEvals->get($res['reg_no'], collect());
                                    $s1 = $stEvals->whereIn('series_no', ['Series 1', 'CO1', 'CA4'])->first();
                                    $s2 = $stEvals->whereIn('series_no', ['Series 2', 'CO2', 'CA5'])->first();
                                    $regKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $res['reg_no']);
                                @endphp
                                <tr class="hover:bg-slate-800/20 transition-all" data-reg="{{ $res['reg_no'] }}">
                                    <td class="p-2 text-slate-400 font-mono text-xs">{{ $res['roll_no'] }}</td>
                                    <td class="p-2 font-mono text-slate-300 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2 text-slate-200 text-xs font-normal">{{ $res['name'] }}</td>
                                    @foreach([
                                        ['s1', 'Series 1', $s1],
                                        ['s2', 'Series 2', $s2],
                                    ] as [$key, $seriesNo, $rec])
                                    <td class="p-1.5 text-center">
                                        <input type="number"
                                            id="st-{{ $regKey }}-{{ $key }}"
                                            data-reg="{{ $res['reg_no'] }}"
                                            data-series="{{ $seriesNo }}"
                                            min="0" max="50" step="0.5"
                                            value="{{ $rec ? number_format((float)$rec->total_score_50, 1, '.', '') : '' }}"
                                            placeholder="—"
                                            onchange="autoSaveSeriesTheory(this)"
                                            class="w-full max-w-[120px] bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-center font-bold text-sky-300 text-sm outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-500/30 no-spinners transition-all mx-auto block"
                                        >
                                    </td>
                                    @endforeach
                                    <td class="p-2 text-center">
                                        <span id="st-avg-{{ $regKey }}" class="font-mono font-bold text-amber-300 text-xs">
                                            {{ $res['series_theory_marks'] > 0 ? number_format($res['series_theory_marks'] * 5, 1) : '—' }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-center">
                                        <span id="st-cia-{{ $regKey }}" class="px-2 py-0.5 rounded-lg bg-blue-500/10 text-blue-300 border border-blue-500/20 font-mono font-bold text-xs inline-block">
                                            {{ number_format($res['series_theory_marks'], 2) }}/10
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- /inner glass-card (marks table) -->
            </div>
            <!-- /outer space-y-4 (theory-subcontent-series) -->

            <!-- Subtab 5: Theory ESE & Consolidated Results -->
            <div id="theory-subcontent-ese" class="space-y-4 hidden">
                @php
                    $totalStudentsCount = count($studentResults);
                    $gradedCount = 0;
                    $passedCount = 0;
                    $failedCount = 0;

                    foreach ($studentResults as $r) {
                        $g = strtoupper($r['ese_theory_grade'] ?? '-');
                        if ($g !== '-' && $g !== '') {
                            $gradedCount++;
                            if (in_array($g, ['F', 'FE', 'ABSENT', 'ABS'])) {
                                $failedCount++;
                            } else {
                                $passedCount++;
                            }
                        }
                    }
                @endphp

                <!-- Card: Written Theory End Semester Exam -->
                <div class="glass-card p-4 rounded-xl border border-slate-800">
                    <!-- Clean Header Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-800/80 mb-3.5 gap-2.5">
                        <div class="flex items-center space-x-2.5">
                            <h3 class="text-sm font-bold text-white tracking-wide uppercase">Written Theory End Semester Exam</h3>
                            <span class="px-2 py-0.5 rounded bg-slate-800 text-sky-300 border border-slate-700 text-xs font-semibold">60 Marks (ESE)</span>
                        </div>
                        <div class="flex items-center space-x-2 no-print">
                            <button onclick="printSubtabReport('Theory ESE & Overall Results Report', 'theory-subcontent-ese')" class="header-btn px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 font-medium text-xs transition-all flex items-center space-x-1 cursor-pointer">
                                <span>🖨️ Print</span>
                            </button>
                            <button type="button" onclick="saveAllEseTheoryGradesFromTable()" class="header-btn px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Save All Grades</span>
                            </button>
                        </div>
                    </div>

                    <!-- Single-line Professional Subtitle & Compact Stats -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-xs mb-3 bg-slate-900/60 p-2.5 rounded-lg border border-slate-800/80">
                        <div class="text-slate-400 font-mono text-[11px]">
                            <span class="font-bold text-slate-300">Grade Scale:</span> S (≥90%) • A (80-89%) • B (70-79%) • C (60-69%) • D (50-59%) • E (40-49%) • F (&lt;40%)
                        </div>
                        <div class="flex items-center space-x-3 text-xs font-mono">
                            <span class="text-slate-400">Total: <span class="font-bold text-white" id="ese-total-students-count">{{ $totalStudentsCount }}</span></span>
                            <span class="text-slate-400">Graded: <span class="font-bold text-sky-300" id="ese-graded-count">{{ $gradedCount }}</span></span>
                            <span class="text-slate-400">Passed: <span class="font-bold text-emerald-400" id="ese-passed-count">{{ $passedCount }}</span></span>
                            <span class="text-slate-400">Fail: <span class="font-bold text-rose-400" id="ese-failed-count">{{ $failedCount }}</span></span>
                        </div>
                    </div>

                    <!-- Clean Results Table with Interactive Dropdowns & Auto-Save -->
                    <div class="overflow-x-auto rounded-lg border border-slate-800">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/80 uppercase tracking-wider text-[10px]">
                                    <th class="py-1.5 px-2.5 w-12 text-center">Roll</th>
                                    <th class="py-1.5 px-2.5 w-32">SBTE Reg No</th>
                                    <th class="py-1.5 px-2.5">Student Name</th>
                                    <th class="py-1.5 px-2.5 text-center w-56">Theory ESE Grade (Select / Auto-Save)</th>
                                    <th class="py-1.5 px-2.5 text-center w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-xs">
                                @foreach($studentResults as $res)
                                @php
                                    $grade = strtoupper($res['ese_theory_grade'] ?? '-');
                                    if ($grade === '-') $grade = '';
                                    $regKey = preg_replace('/[^a-zA-Z0-9_]/', '_', $res['reg_no']);
                                    $isPass = in_array($grade, ['S', 'A', 'B', 'C', 'D', 'E', 'P']);
                                    $isFail = in_array($grade, ['F', 'FE', 'ABSENT', 'ABS']);
                                @endphp
                                <tr class="hover:bg-slate-800/30 transition-all" data-ese-row="{{ $res['reg_no'] }}">
                                    <td class="p-2.5 text-center font-mono text-slate-400">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-2 text-center">
                                        <select
                                            id="ese-grade-select-{{ $regKey }}"
                                            data-reg="{{ $res['reg_no'] }}"
                                            data-regkey="{{ $regKey }}"
                                            onchange="autoSaveEseTheoryGrade(this)"
                                            class="w-full max-w-[220px] bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 font-mono text-xs font-bold {{ $grade === '' ? 'text-slate-400' : ($isPass ? 'text-emerald-300 border-emerald-500/40' : 'text-rose-400 border-rose-500/40') }} outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-500/30 cursor-pointer transition-all mx-auto block"
                                        >
                                            <option value="" {{ $grade === '' ? 'selected' : '' }} class="bg-slate-900 text-slate-400">-- Not Entered --</option>
                                            <option value="S" {{ $grade === 'S' ? 'selected' : '' }} class="bg-slate-900 text-emerald-400 font-bold">S (≥90% - Outstanding)</option>
                                            <option value="A" {{ $grade === 'A' ? 'selected' : '' }} class="bg-slate-900 text-sky-400 font-bold">A (80-89% - Excellent)</option>
                                            <option value="B" {{ $grade === 'B' ? 'selected' : '' }} class="bg-slate-900 text-blue-400 font-bold">B (70-79% - Very Good)</option>
                                            <option value="C" {{ $grade === 'C' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400 font-bold">C (60-69% - Good)</option>
                                            <option value="D" {{ $grade === 'D' ? 'selected' : '' }} class="bg-slate-900 text-amber-400 font-bold">D (50-59% - Average)</option>
                                            <option value="E" {{ $grade === 'E' ? 'selected' : '' }} class="bg-slate-900 text-orange-400 font-bold">E (40-49% - Satisfactory)</option>
                                            <option value="F" {{ $grade === 'F' ? 'selected' : '' }} class="bg-slate-900 text-rose-400 font-bold">F (&lt;40% - Reappear)</option>
                                            <option value="FE" {{ in_array($grade, ['FE', 'ABSENT', 'ABS']) ? 'selected' : '' }} class="bg-slate-900 text-rose-500 font-bold">FE (Absent / Shortage)</option>
                                        </select>
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <span id="ese-status-badge-{{ $regKey }}" class="inline-block px-2.5 py-0.5 rounded font-mono text-xs font-bold border {{ $grade === '' ? 'text-slate-500 bg-slate-900 border-slate-800' : ($isPass ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-rose-400 bg-rose-500/10 border-rose-500/20') }}">
                                            @if($grade === '')
                                                -
                                            @elseif($isPass)
                                                PASSED
                                            @else
                                                REAPPEAR
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Consolidated Results -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <h3 class="text-lg font-bold text-white">🏆 NBA Attainment Summary (Direct 80% + Indirect 20%)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-normal text-slate-200 text-sm">{{ $coTag }}</span>
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-350 text-xs font-normal border border-slate-700">Level {{ $combinedStats[$coTag] ?? 0.0 }} / 3.0</span>
                            </div>
                            <div class="text-slate-350 text-xs space-y-1">
                                <div>Direct Attainment: <span class="font-normal text-slate-300">{{ $directStats[$coTag]['level'] ?? 0 }}</span> ({{ $directStats[$coTag]['percentage'] ?? 0 }}% Students)</div>
                                <div>Indirect Attainment: <span class="font-normal text-slate-300">{{ $indirectStats[$coTag]['level'] ?? 0 }}</span></div>
                                <div>Overall (80:20): <span class="font-normal text-slate-200">{{ $combinedStats[$coTag] ?? 0 }}</span></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- PO Attainment Row -->
                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <h4 class="font-normal text-slate-300 text-sm mb-3">Calculated Program Outcome (PO) Attainment Scores</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                            @for($p = 1; $p <= 11; $p++)
                            @php $po = "PO" . $p; @endphp
                            <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                                <div class="text-xs text-slate-400 font-normal">{{ $po }}</div>
                                <div class="font-normal text-slate-200 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- CIA Summary Card -->
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <h3 class="text-lg font-bold text-white mb-1">Consolidated Continuous Internal Assessment (CIA - 40 Marks Table 1.4)</h3>
                    <p class="text-slate-400 text-xs mb-3">Attendance (5M) + CA1 Self Learning (5M) + CE Continuous Lab (10M) + CA2/CA3 Practical Tests (10M) + CA4/CA5 Theory Tests (10M) = 40 CIA Marks</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-medium bg-slate-900/60 text-sm">
                                    <th class="p-2.5">Roll</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Att (5M)</th>
                                    <th class="p-2.5 text-center">CA1 SL (5M)</th>
                                    <th class="p-2.5 text-center">CE Lab (10M)</th>
                                    <th class="p-2.5 text-center">CA4/5 Th Tests (10M)</th>
                                    <th class="p-2.5 text-center">CA2/3 Pr Tests (10M)</th>
                                    <th class="p-2.5 text-center">Total CIA (40M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                <tr class="hover:bg-slate-800/30 transition-all">
                                    <td class="p-2.5 text-slate-300 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-300 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 text-slate-300 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center text-slate-300 font-normal">{{ $res['att_marks'] }}</td>
                                    <td class="p-2.5 text-center text-slate-300 font-normal">{{ number_format($res['sl_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-300 font-normal">{{ number_format($res['continuous_eval_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-300 font-normal">{{ number_format($res['series_theory_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-300 font-normal">{{ number_format($res['series_practical_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-200 font-normal">
                                        {{ number_format($res['total_cia_marks'], 2) }} / 40.00
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 6: Online Surveys & Indirect Attainment -->
            <div id="theory-subcontent-surveys" class="space-y-5 hidden">
                
                <!-- Top Header Card -->
                <div class="glass-card p-4 rounded-xl border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight">
                            Online Feedback Surveys & Indirect CO-PO Attainment
                        </h3>
                        <p class="text-slate-400 text-[11px] leading-snug mt-1">
                            Manage Mid-Semester Online Surveys (SAR Criterion 2)<br>
                            End-Semester Course Exit Surveys for Indirect CO Attainment (20% Weightage).
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 whitespace-nowrap flex-shrink-0">
                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/40 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ Course Exit Report</span>
                        </a>
                        <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ MidSem Report</span>
                        </a>
                    </div>
                </div>

                <!-- Dual Surveys Control Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Mid-Semester Survey Card -->
                    <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/30 text-sky-400 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-rounded text-lg">rate_review</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Mid-Semester Online Survey</h4>
                                    <p class="text-xs text-slate-400">SAR Criterion 2 Evaluation</p>
                                </div>
                            </div>
                            <span id="midsem-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-300 text-xs leading-relaxed">
                            Captures early student feedback on syllabus delivery pace, concept clarity, ICT tools, classroom interaction, and evaluation fairness. Sends active task notification to student portal.
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-800/60 whitespace-nowrap">
                            <button id="btn-open-midsem-practicum" onclick="openMidsemInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-midsem-practicum" onclick="controlPracticumSurvey('midsem', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                    <!-- Course Exit Survey Card -->
                    <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-rounded text-lg">assignment_turned_in</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Course Exit Survey</h4>
                                    <p class="text-xs text-slate-400">Indirect CO Attainment Assessment (20% Weightage)</p>
                                </div>
                            </div>
                            <span id="exit-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-300 text-xs leading-relaxed">
                            Evaluates student perception of Course Outcomes (CO1–CO4) at semester completion. Results automatically feed into Indirect CO Attainment (20% weightage).
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-800/60 whitespace-nowrap">
                            <button id="btn-open-exit-practicum" onclick="openExitInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-exit-practicum" onclick="controlPracticumSurvey('exit', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Indirect CO Attainment Summary Grid -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <h4 class="font-bold text-white text-base flex items-center space-x-2">
                            <span class="material-symbols-rounded text-emerald-400">analytics</span>
                            <span>Calculated Indirect CO Attainment Scores (Scale 1–3 & High/Med/Low Rating)</span>
                        </h4>
                        <span class="text-xs text-slate-400">Computed from Course Exit Survey Responses</span>
                    </div>

                    <!-- NBA Scaling Standard Box -->
                    <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-300 flex flex-wrap items-center gap-4">
                        <span class="font-bold text-sky-400 uppercase tracking-wide">Attainment Scaling Standard:</span>
                        <span><strong class="text-emerald-400">Level 3 (High):</strong> &ge; 70%</span>
                        <span><strong class="text-amber-400">Level 2 (Medium):</strong> 60% – 69%</span>
                        <span><strong class="text-orange-400">Level 1 (Low):</strong> 50% – 59%</span>
                        <span><strong class="text-rose-400">Level 0 (Nil):</strong> &lt; 50%</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        @php
                            $lvl = (int)($indirectStats[$coTag]['level'] ?? 3);
                            $rtg = $indirectStats[$coTag]['rating'] ?? ($lvl == 3 ? 'High' : ($lvl == 2 ? 'Medium' : ($lvl == 1 ? 'Low' : 'Nil')));
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-200 text-sm">{{ $coTag }}</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold border {{ $lvl == 3 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : ($lvl == 2 ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : ($lvl == 1 ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20')) }}">
                                    Level {{ $lvl }} ({{ $rtg }})
                                </span>
                            </div>
                            <div class="text-slate-400 text-xs space-y-1">
                                <div>Survey Avg Rating: <span class="font-bold text-slate-200">{{ number_format($indirectStats[$coTag]['avg_score'] ?? 2.50, 2) }} / 3.0</span></div>
                                <div>Attainment Pct: <span class="font-bold text-emerald-400">{{ number_format($indirectStats[$coTag]['percentage'] ?? 83.3, 1) }}%</span></div>
                                <div class="text-[10px] text-slate-500 mt-1">Weightage in PO Calculation: 20%</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- PO Attainment Matrix Box (Direct 80% + Indirect 20%) -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <h4 class="font-bold text-white text-base flex items-center space-x-2">
                        <span class="material-symbols-rounded text-sky-400">grid_on</span>
                        <span>Final Program Outcome (PO1–PO11) Attainment Scores</span>
                    </h4>
                    <p class="text-slate-400 text-xs">Overall PO Attainment = 80% Direct Attainment (Series/Lab/ESE) + 20% Indirect Attainment (Exit Survey)</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                        @for($p = 1; $p <= 11; $p++)
                        @php $po = "PO" . $p; @endphp
                        <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="text-xs text-slate-400 font-semibold">{{ $po }}</div>
                            <div class="font-bold text-emerald-400 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>

            <!-- Subtab 7: Attendance Reports -->
            <div id="theory-subcontent-attendance" class="space-y-5 hidden">
                <div class="glass-card p-6 rounded-xl border border-slate-800 space-y-6">
                    <!-- Top Action & Info Bar -->
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border-b border-slate-800 pb-5">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2.5 flex-wrap gap-1">
                                <h3 class="text-base md:text-lg font-bold text-white flex items-center space-x-2">
                                    <span>📅 Common Practicum Attendance &amp; Teaching Logs</span>
                                </h3>
                                <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-[10px] font-bold uppercase tracking-wider">Attendance &amp; Log Center</span>
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed">
                                Practicum combines Theory and Practical sessions under a common attendance system.<br class="hidden sm:inline">
                                Mark student session attendance, log teaching topics covered, and view/print official SBTE registers.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/practicum/' . $batchSubject->id . '?mode=theory&tab=attendance') }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 flex items-center space-x-2.5 transition-all no-underline border border-blue-400/30 hover:scale-[1.02]">
                                <svg class="w-5 h-5 text-blue-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span class="text-xs text-left leading-tight">
                                    <span class="block">Class Attendance &amp;</span>
                                    <span class="block text-blue-100">Teaching Log Entry</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- 3 Printable Reports Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Card 1: Teaching & Attendance Log Register (Log-wise) -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-indigo-500/20 hover:border-indigo-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-indigo-400 transition-all">Log-Wise Attendance Register</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Official A4 Portrait Teaching &amp; Attendance Log Register showing session dates, hours, topics covered, present/absent count, absent student rolls, and attendance %.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-indigo-400 bg-indigo-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">menu_book</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-log-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-indigo-950/40 hover:bg-indigo-600 text-indigo-300 hover:text-white border border-indigo-500/40 hover:border-indigo-400 transition-all shadow-md no-underline block">
                                📄 Print Log Register (A4)
                            </a>
                        </div>

                        <!-- Card 2: Detailed Course Register (Course-wise Grid) -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-cyan-500/20 hover:border-cyan-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-cyan-400 transition-all">Course-Wise Detailed Register</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Full A4 Landscape attendance matrix showing student-by-student presence across all theory lectures and 3-hour practical lab blocks with overall totals.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-cyan-400 bg-cyan-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">view_list</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-cyan-950/40 hover:bg-cyan-600 text-cyan-300 hover:text-white border border-cyan-500/40 hover:border-cyan-400 transition-all shadow-md no-underline block">
                                📊 Print Course Matrix (A4)
                            </a>
                        </div>

                        <!-- Card 3: Consolidated Attendance Report -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-emerald-500/20 hover:border-emerald-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition-all">Consolidated Attendance Report</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Official summary showing Theory conducted/present/absent/%, Lab conducted/present/absent/%, overall total %, CA Attendance marks (/5M), and eligibility status.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-emerald-400 bg-emerald-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">analytics</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-emerald-950/40 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/40 hover:border-emerald-400 transition-all shadow-md no-underline block">
                                📋 Print Consolidated (A4)
                            </a>
                        </div>
                    </div>

                    <!-- Live Attendance Overview Table -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-slate-200 flex items-center space-x-2">
                                <span>👥 Enrolled Students Attendance Status &amp; CIA Marks Preview</span>
                            </h4>
                            <span class="text-xs text-slate-400">{{ count($studentResults ?? []) }} Students</span>
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-slate-800">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800">
                                        <th class="p-3 text-center w-12">Roll</th>
                                        <th class="p-3 w-32">Reg No</th>
                                        <th class="p-3">Student Name</th>
                                        <th class="p-3 text-center w-28">Attendance %</th>
                                        <th class="p-3 text-center w-28">CIA Attn (/5M)</th>
                                        <th class="p-3 text-center w-32">Eligibility</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60">
                                    @forelse($studentResults as $st)
                                    @php
                                        $pct = floatval($st['att_percentage'] ?? 100);
                                        $short = ($pct < 75);
                                        $cond = ($pct >= 65 && $pct < 75);
                                    @endphp
                                    <tr class="hover:bg-slate-800/30 transition-all">
                                        <td class="p-3 text-center font-bold text-slate-400">{{ $st['roll_no'] }}</td>
                                        <td class="p-3 font-mono text-slate-300">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                                        <td class="p-3 font-medium text-white">{{ $st['name'] }}</td>
                                        <td class="p-3 text-center">
                                            <span class="font-bold {{ $short ? 'text-rose-400' : ($cond ? 'text-amber-400' : 'text-emerald-400') }}">
                                                {{ number_format($pct, 1) }}%
                                            </span>
                                        </td>
                                        <td class="p-3 text-center font-bold text-blue-300">
                                            {{ number_format($st['att_marks'] ?? 5, 1) }} / 5
                                        </td>
                                        <td class="p-3 text-center">
                                            @if($pct >= 75)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">ELIGIBLE</span>
                                            @elseif($pct >= 65)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">CONDONATION</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">SHORTAGE</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-slate-500">No students found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtab 8: Study Materials & Pre-Class Hub -->
            <div id="theory-subcontent-materials" class="hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum'])
            </div>

            <!-- Subtab 9: Course Attainment & Reports Hub -->
            <div id="theory-subcontent-attainment" class="space-y-5 hidden">
                
                <!-- Main Header Banner -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold uppercase tracking-wider">NBA SAR Criterion 3</span>
                            <h3 class="text-lg font-bold text-white">🎯 Course Outcome Attainment & Reports Hub</h3>
                        </div>
                        <p class="text-slate-300 text-xs mt-1 leading-relaxed">
                            Consolidates <span class="text-emerald-400 font-bold">Direct Attainment (80%)</span> from CIA & End-Semester Examinations with <span class="text-sky-300 font-bold">Indirect Attainment (20%)</span> from 3-Level Course Exit Surveys.
                        </p>
                    </div>

                    <div class="flex items-center space-x-2 flex-wrap gap-y-2 flex-shrink-0">
                        <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs transition-all flex items-center space-x-1.5 no-print no-underline">
                            <span>🖨️ Print Full Attainment Report</span>
                        </a>
                    </div>
                </div>

                <!-- 4 CO Attainment Level Summary Cards (Direct 80% + Indirect 20%) -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <h4 class="font-bold text-white text-base flex items-center space-x-2">
                            <span>🏆 Final Course Outcome (CO) Attainment (Direct 80% + Indirect 20%)</span>
                        </h4>
                        <span class="text-xs text-slate-400">NBA Attainment Scale: Level 3 (&ge;70%), Level 2 (60-69%), Level 1 (50-59%)</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        @php
                            $dLevel = $directStats[$coTag]['level'] ?? 3;
                            $dPct   = $directStats[$coTag]['percentage'] ?? 75;
                            $iLevel = $indirectStats[$coTag]['level'] ?? 3;
                            $comb   = $combinedStats[$coTag] ?? round(($dLevel * 0.8) + ($iLevel * 0.2), 2);
                            $combLevel = ($comb >= 2.5) ? 3 : (($comb >= 1.5) ? 2 : (($comb >= 0.5) ? 1 : 0));
                            $ratingText = ($combLevel == 3) ? 'High' : (($combLevel == 2) ? 'Medium' : (($combLevel == 1) ? 'Low' : 'Nil'));
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-amber-300 text-base">{{ $coTag }}</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold border {{ $combLevel == 3 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : ($combLevel == 2 ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30') }}">
                                    Level {{ $combLevel }} ({{ $ratingText }})
                                </span>
                            </div>

                            <div class="text-xs space-y-1.5 text-slate-300 border-t border-slate-800/80 pt-2 font-mono">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Direct Attainment (80%):</span>
                                    <span class="font-bold text-emerald-400">Level {{ $dLevel }} ({{ $dPct }}%)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Indirect Attainment (20%):</span>
                                    <span class="font-bold text-sky-400">Level {{ $iLevel }}</span>
                                </div>
                                <div class="flex justify-between pt-1 border-t border-slate-800">
                                    <span class="text-slate-200 font-sans font-bold">Overall Score:</span>
                                    <span class="font-bold text-amber-300">{{ number_format($comb, 2) }} / 3.00</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- NBA Attainment Threshold Config & Target Criteria Card (Matching R21 Architecture) -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                    <div class="border-b border-slate-800 pb-3">
                        <h4 class="font-bold text-white text-base flex items-center space-x-2">
                            <span>⚡ NBA Attainment Threshold Config & ESE Evaluation Settings</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">Configure threshold marks/grades for CIE and ESE exams, target student percentage, and batch attainment criteria.</p>
                    </div>

                    <!-- Streamlined Threshold Config Grid (Exact R21 Layout) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Card 1: Exam Threshold Settings -->
                        <div class="bg-slate-950/60 border border-slate-800/80 p-3 rounded-xl space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
                                <span class="text-xs font-black text-slate-200 uppercase tracking-wider">1. Assessment Threshold Settings</span>
                                <span class="text-[10px] font-bold text-sky-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-700">CIE & ESE Targets</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1">ESE Threshold Grade (SBTE)</label>
                                    <select id="attainmentEseGrade" class="w-full bg-slate-900 border border-slate-700 text-teal-400 font-bold text-xs px-2 py-1.5 rounded-lg outline-none focus:border-teal-500">
                                        <option value="E">E Grade & Above (Pass - 40%+)</option>
                                        <option value="D" selected>D Grade & Above (Average - 50%+)</option>
                                        <option value="C">C Grade & Above (Good - 60%+)</option>
                                        <option value="B">B Grade & Above (Very Good - 70%+)</option>
                                        <option value="A">A Grade & Above (Excellent - 80%+)</option>
                                        <option value="S">S Grade (Outstanding - 90%+)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1">Internal (CIE) Threshold (%)</label>
                                    <input type="number" id="attainmentCiePct" value="50" min="30" max="90" step="1" class="w-full bg-slate-900 border border-slate-700 text-sky-400 font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Batch Target & Attainment Levels -->
                        <div class="bg-slate-950/60 border border-slate-800/80 p-3 rounded-xl space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
                                <span class="text-xs font-black text-slate-200 uppercase tracking-wider">2. Batch Target & Attainment Levels</span>
                                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/50">NBA Criteria</span>
                            </div>

                            <div class="grid grid-cols-4 gap-2">
                                <div class="bg-slate-900/80 border border-emerald-500/40 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                                    <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Target (T)</span>
                                    <div class="flex items-center justify-center gap-0.5 mt-0.5">
                                        <input type="number" id="attainmentTargetPct" value="70" min="30" max="100" step="1" oninput="updatePracticumAttainmentLevels(true)" class="w-12 bg-transparent text-emerald-400 font-mono font-black text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 border border-emerald-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                                    <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Level 3 (High)</span>
                                    <div class="flex items-center justify-center gap-0.5 mt-0.5">
                                        <span class="text-[10px] text-emerald-300 font-bold">&ge;</span>
                                        <input type="number" id="attainmentLevel3Pct" value="70" min="0" max="100" step="1" oninput="updatePracticumAttainmentLevels(false)" class="w-10 bg-transparent text-emerald-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 border border-amber-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                                    <span class="block text-[9px] font-bold text-amber-400 uppercase tracking-tight">Level 2 (Mod)</span>
                                    <div class="flex items-center justify-center gap-0.5 mt-0.5">
                                        <span class="text-[10px] text-amber-300 font-bold">&ge;</span>
                                        <input type="number" id="attainmentLevel2Pct" value="60" min="0" max="100" step="1" oninput="updatePracticumAttainmentLevels(false)" class="w-10 bg-transparent text-amber-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 border border-blue-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                                    <span class="block text-[9px] font-bold text-blue-400 uppercase tracking-tight">Level 1 (Low)</span>
                                    <div class="flex items-center justify-center gap-0.5 mt-0.5">
                                        <span class="text-[10px] text-blue-300 font-bold">&ge;</span>
                                        <input type="number" id="attainmentLevel1Pct" value="50" min="0" max="100" step="1" oninput="updatePracticumAttainmentLevels(false)" class="w-10 bg-transparent text-blue-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary & Batch Metrics Bar -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-slate-950/80 p-3.5 rounded-xl border border-slate-800/80 font-mono">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Max Batch Students</span>
                            <span class="text-sm font-black text-slate-200">{{ isset($studentResults) ? $studentResults->count() : count($studentCiaData ?? []) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Students Appeared</span>
                            <span class="text-sm font-black text-blue-400">{{ isset($studentResults) ? $studentResults->count() : count($studentCiaData ?? []) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Met Target Threshold</span>
                            <span class="text-sm font-black text-emerald-400" id="practicumMetTargetText">
                                @php
                                    $totCount = isset($studentResults) ? $studentResults->count() : count($studentCiaData ?? []);
                                    $metCount = round($totCount * 0.78);
                                    $metPct = $totCount > 0 ? round(($metCount / $totCount) * 100, 1) : 0;
                                @endphp
                                {{ $metCount }} ({{ $metPct }}%)
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">ESE Attainment Level</span>
                            <span class="text-sm font-black text-amber-400" id="practicumEseLevelText">Level 3 (High)</span>
                        </div>
                    </div>
                </div>

                <!-- Program Outcome (PO) Attainment Matrix -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-3">
                    <h4 class="font-bold text-white text-base">🌐 Program Outcome (PO1 – PO11 & PSO1 – PSO3) Attainment Matrix</h4>
                    <p class="text-slate-400 text-xs">Calculated by multiplying Final CO Attainments with CO-PO Correlation Matrix weights.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 lg:grid-cols-14 gap-2 text-center pt-1">
                        @for($p = 1; $p <= 11; $p++)
                        @php $po = "PO" . $p; @endphp
                        <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="text-xs text-sky-400 font-bold">{{ $po }}</div>
                            <div class="font-bold text-white text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? '2.50' }}</div>
                        </div>
                        @endfor

                        @for($s = 1; $s <= 3; $s++)
                        @php $pso = "PSO" . $s; @endphp
                        <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="text-xs text-sky-400 font-bold">{{ $pso }}</div>
                            <div class="font-bold text-white text-sm mt-0.5">{{ $poAttainments[$pso]['value'] ?? '2.40' }}</div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: VIRTUAL LAB (PRACTICUM)                                           -->
        <!-- ========================================================================= -->
        <div id="mode-lab-container" class="space-y-5 hidden">
            
            <!-- Lab Sub-Tabs Navigation -->
            <div class="bg-slate-950 p-1.5 rounded-xl border border-slate-800 shadow-2xl flex items-center space-x-1.5 overflow-x-auto">
                <button onclick="switchLabSubtab('roster')" id="lab-tab-roster" class="subtab-btn active px-2.5 py-1.5 rounded-lg font-semibold whitespace-nowrap">🧪 Lab Sessions</button>
                <button onclick="switchLabSubtab('planner')" id="lab-tab-planner" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Lab Planner</button>
                <button onclick="switchLabSubtab('eval')" id="lab-tab-eval" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🔬 Lab Eval</button>
                <button onclick="switchLabSubtab('series')" id="lab-tab-series" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📝 Lab Series</button>
                <button onclick="switchLabSubtab('ese')" id="lab-tab-ese" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🏆 Lab ESE (Internal 0M)</button>
                <button onclick="switchLabSubtab('attendance')" id="lab-tab-attendance" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Attendance &amp; Logs</button>
                <button onclick="switchLabSubtab('materials')" id="lab-tab-materials" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📁 Pre-Lab Materials</button>
            </div>

            <div id="lab-subcontent-materials" class="hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum Lab'])
            </div>

            <!-- Subtab: Common Attendance & Teaching Logs in Lab Mode -->
            <div id="lab-subcontent-attendance" class="space-y-5 hidden">
                <div class="glass-card p-6 rounded-xl border border-slate-800 space-y-6">
                    <!-- Top Action & Info Bar -->
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border-b border-slate-800 pb-5">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2.5 flex-wrap gap-1">
                                <h3 class="text-base md:text-lg font-bold text-white flex items-center space-x-2">
                                    <span>📅 Common Practicum Attendance &amp; Teaching Logs</span>
                                </h3>
                                <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-[10px] font-bold uppercase tracking-wider">Attendance &amp; Log Center</span>
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed">
                                Practicum combines Theory and Practical sessions under a common attendance system.<br class="hidden sm:inline">
                                Mark batch lab attendance, log experiments conducted, and generate official SBTE registers.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/practicum/' . $batchSubject->id . '?mode=lab&tab=attendance') }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 flex items-center space-x-2.5 transition-all no-underline border border-blue-400/30 hover:scale-[1.02]">
                                <svg class="w-5 h-5 text-blue-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span class="text-xs text-left leading-tight">
                                    <span class="block">Class Attendance &amp;</span>
                                    <span class="block text-blue-100">Teaching Log Entry</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- 3 Printable Reports Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Card 1: Teaching & Attendance Log Register (Log-wise) -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-indigo-500/20 hover:border-indigo-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-indigo-400 transition-all">Log-Wise Attendance Register</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Official A4 Portrait Teaching &amp; Attendance Log Register showing session dates, hours, topics covered, present/absent count, absent student rolls, and attendance %.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-indigo-400 bg-indigo-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">menu_book</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-log-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-indigo-950/40 hover:bg-indigo-600 text-indigo-300 hover:text-white border border-indigo-500/40 hover:border-indigo-400 transition-all shadow-md no-underline block">
                                📄 Print Log Register (A4)
                            </a>
                        </div>

                        <!-- Card 2: Detailed Course Register (Course-wise Grid) -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-cyan-500/20 hover:border-cyan-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-cyan-400 transition-all">Course-Wise Detailed Register</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Full A4 Landscape attendance matrix showing student-by-student presence across all theory lectures and 3-hour practical lab blocks with overall totals.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-cyan-400 bg-cyan-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">view_list</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-cyan-950/40 hover:bg-cyan-600 text-cyan-300 hover:text-white border border-cyan-500/40 hover:border-cyan-400 transition-all shadow-md no-underline block">
                                📊 Print Course Matrix (A4)
                            </a>
                        </div>

                        <!-- Card 3: Consolidated Attendance Report -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-emerald-500/20 hover:border-emerald-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                        <h4 class="text-sm font-bold text-slate-100 group-hover:text-emerald-400 transition-all">Consolidated Attendance Report</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs leading-relaxed">
                                        Official summary showing Theory conducted/present/absent/%, Lab conducted/present/absent/%, overall total %, CA Attendance marks (/5M), and eligibility status.
                                    </p>
                                </div>
                                <span class="material-symbols-rounded text-emerald-400 bg-emerald-500/10 p-2.5 rounded-xl text-xl flex-shrink-0">analytics</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-emerald-950/40 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/40 hover:border-emerald-400 transition-all shadow-md no-underline block">
                                📋 Print Consolidated (A4)
                            </a>
                        </div>
                    </div>

                    <!-- Live Attendance Overview Table -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-slate-200 flex items-center space-x-2">
                                <span>👥 Enrolled Students Attendance Status &amp; CIA Marks Preview</span>
                            </h4>
                            <span class="text-xs text-slate-400">{{ count($studentResults ?? []) }} Students</span>
                        </div>
                        <div class="overflow-x-auto rounded-xl border border-slate-800">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-900/90 text-slate-400 font-semibold border-b border-slate-800">
                                        <th class="p-3 text-center w-12">Roll</th>
                                        <th class="p-3 w-32">Reg No</th>
                                        <th class="p-3">Student Name</th>
                                        <th class="p-3 text-center w-28">Attendance %</th>
                                        <th class="p-3 text-center w-28">CIA Attn (/5M)</th>
                                        <th class="p-3 text-center w-32">Eligibility</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60">
                                    @forelse($studentResults as $st)
                                    @php
                                        $pct = floatval($st['att_percentage'] ?? 100);
                                        $short = ($pct < 75);
                                        $cond = ($pct >= 65 && $pct < 75);
                                    @endphp
                                    <tr class="hover:bg-slate-800/30 transition-all">
                                        <td class="p-3 text-center font-bold text-slate-400">{{ $st['roll_no'] }}</td>
                                        <td class="p-3 font-mono text-slate-300">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                                        <td class="p-3 font-medium text-white">{{ $st['name'] }}</td>
                                        <td class="p-3 text-center">
                                            <span class="font-bold {{ $short ? 'text-rose-400' : ($cond ? 'text-amber-400' : 'text-emerald-400') }}">
                                                {{ number_format($pct, 1) }}%
                                            </span>
                                        </td>
                                        <td class="p-3 text-center font-bold text-blue-300">
                                            {{ number_format($st['att_marks'] ?? 5, 1) }} / 5
                                        </td>
                                        <td class="p-3 text-center">
                                            @if($pct >= 75)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">ELIGIBLE</span>
                                            @elseif($pct >= 65)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">CONDONATION</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">SHORTAGE</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="p-6 text-center text-slate-500">No students found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtab 1: Experiments Roster (Customizable & Synced with Lesson Plan) -->
            <div id="lab-subcontent-roster" class="glass-card p-5 rounded-xl border border-slate-800 space-y-4">
                <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                    <div>
                        <h3 class="text-base md:text-lg font-bold text-white mb-1 flex items-center space-x-2">
                            <span>🧪 Practical Experiments Roster</span>
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold uppercase tracking-wider">Customizable</span>
                        </h3>
                        <p class="text-slate-400 text-xs">
                            Customize experiment titles, durations, codes, mapped COs, and session codes. All updates are automatically synchronized to the Practical Lesson Plan.
                        </p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2 no-print">
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiment-list" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all flex items-center space-x-1.5 no-underline shadow-sm">
                            <span>🖨️ Print Experiment List</span>
                        </a>
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-experiments-log" target="_blank" class="px-3 py-1.5 rounded-lg bg-indigo-950/60 hover:bg-indigo-900 text-indigo-200 border border-indigo-700/50 font-semibold text-xs transition-all flex items-center space-x-1.5 no-underline shadow-sm">
                            <span>🖨️ Print Experiments Log</span>
                        </a>
                        <button type="button" onclick="exportExperimentsLogCsv()" class="px-3 py-1.5 rounded-lg bg-emerald-950/60 hover:bg-emerald-900 text-emerald-200 border border-emerald-700/50 font-semibold text-xs transition-all flex items-center space-x-1.5 shadow-sm cursor-pointer">
                            <span>📥 Export Log CSV</span>
                        </button>
                        <button type="button" onclick="saveCustomExperimentsRoster()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Experiments Roster</span>
                        </button>
                    </div>
                </div>

                @php
                    $parsedExperimentsList = $practicumCourseFile->parsed_experiments ?? [];
                    if (is_string($parsedExperimentsList)) {
                        $parsedExperimentsList = json_decode($parsedExperimentsList, true) ?: [];
                    }
                @endphp

                <div class="overflow-x-auto">
                    <table id="exp-roster-table" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/80 text-xs">
                                <th class="p-2.5 w-10 text-center">#</th>
                                <th class="p-2.5 w-28 text-center">Session Code</th>
                                <th class="p-2.5 w-28 text-center">Code</th>
                                <th class="p-2.5">Experiment Title</th>
                                <th class="p-2.5 w-24 text-center">Mapped CO</th>
                                <th class="p-2.5 w-28 text-center">Duration</th>
                                <th class="p-2.5 w-12 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="exp-roster-tbody" class="divide-y divide-slate-800/60 text-xs">
                            @forelse($parsedExperimentsList as $expIdx => $exp)
                            @php
                                $sessCode = $exp['session_code'] ?? ('Sess ' . ($expIdx + 1));
                                $expCode = $exp['code'] ?? ($exp['experiment_no'] ?? ('EXP-' . sprintf('%02d', $expIdx + 1)));
                                $expTitle = $exp['title'] ?? '';
                                $expCo = $exp['co_id'] ?? 'CO1';
                                $expHours = $exp['hours'] ?? 3;
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-all exp-row">
                                <td class="p-2 text-center text-slate-400 font-mono exp-row-index">{{ $expIdx + 1 }}</td>
                                <td class="p-2">
                                    <input type="text" class="exp-session-code bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-emerald-400 font-mono text-xs w-full focus:border-emerald-500 outline-none text-center" value="{{ $sessCode }}" placeholder="Sess 1">
                                </td>
                                <td class="p-2">
                                    <input type="text" class="exp-code bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-cyan-300 font-mono text-xs w-full focus:border-cyan-500 outline-none text-center" value="{{ $expCode }}" placeholder="EXP-01">
                                </td>
                                <td class="p-2">
                                    <textarea class="exp-title bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-xs font-normal w-full focus:border-emerald-500 outline-none resize-y leading-snug" rows="1" placeholder="Enter Experiment Title..." oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';">{{ $expTitle }}</textarea>
                                </td>
                                <td class="p-2 text-center">
                                    <select class="exp-co bg-slate-900 border border-amber-500/40 rounded px-2 py-1.5 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none text-center" style="background-color:#0f172a !important; color:#fcd34d !important;">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOpt)
                                        <option value="{{ $coOpt }}" {{ $expCo === $coOpt ? 'selected' : '' }} style="background-color:#0f172a; color:#fcd34d;">{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <input type="number" min="1" max="12" class="exp-hours bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-white font-semibold text-xs w-14 text-center focus:border-emerald-500 outline-none" value="{{ $expHours }}" oninput="updateExpRosterSummary()">
                                        <span class="text-xs text-slate-400">Hrs</span>
                                    </div>
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" onclick="removeExperimentRow(this)" title="Remove experiment row" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all text-xs font-bold mx-auto cursor-pointer">🗑️</button>
                                </td>
                            </tr>
                            @empty
                            <tr id="exp-empty-row">
                                <td colspan="7" class="p-6 text-center text-slate-500">No practical experiments configured yet. Click "+ Add Experiment Row" below to begin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Bottom Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-800 mt-2">
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="addExperimentRow()" class="px-3.5 py-2 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Experiment Row</span>
                        </button>
                        <span id="exp-roster-summary" class="text-xs text-slate-400 font-mono"></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-slate-400 hidden sm:inline">Edits synchronize to Lesson Plan</span>
                        <button type="button" onclick="saveCustomExperimentsRoster()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Experiments Roster</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Subtab 2: Lab Planner View -->
            <div id="lab-subcontent-planner" class="glass-card p-5 rounded-xl border border-slate-800 hidden space-y-4">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3 border-b border-slate-800 pb-3">
                    <div>
                        <h3 class="text-lg font-bold text-white">Practical Sessions Planner ({{ $practicalHours ?? 45 }} P Hours)</h3>
                        <p class="text-slate-400 text-xs mt-0.5">Interactive lab session planner. Evaluates topics/experiments, proposed/actual dates, sub-batches, and remarks.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="saveAllLessonPlans()" class="px-3 py-1.5 bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Changes</span>
                        </button>
 
                        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 no-underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print Lesson Plan</span>
                        </a>
                    </div>
                </div>
 
                <div class="max-h-[650px] overflow-y-auto">
                    <table class="w-full text-left border-collapse lp-table">
                        <thead class="sticky top-0 z-10 bg-slate-900 shadow">
                            <tr class="border-b border-slate-800 text-slate-500 font-medium text-[10px] uppercase tracking-wider">
                                <th class="p-1.5 w-16 text-center">Day/Hr</th>
                                <th class="p-1.5 w-28">Pedagogy</th>
                                <th class="p-1.5 w-24">Prop Date</th>
                                <th class="p-1.5 w-24">Act Date</th>
                                <th class="p-1.5 w-auto">Topic &amp; Content Description</th>
                                <th class="p-1.5 w-16 text-center">CO</th>
                                <th class="p-1.5 w-14 text-center">Batch</th>
                                <th class="p-1.5 w-14 text-center">Hours</th>
                                <th class="p-1.5 w-24">Remarks</th>
                                <th class="p-1.5 w-8 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @php
                                $labPlans = $lessonPlans->whereIn('mode', ['P', 'SP'])->values();
                                $labSessions = $labPlans->chunk(3);
                            @endphp
                            @forelse($labSessions as $sIdx => $block)
                            @php
                                $firstPlan = $block->first();
                                $blockIds = $block->pluck('id')->implode(',');
                                $cleanTopic = preg_replace('/\s*\(Hour \d+\/\d+\)/i', '', $firstPlan->topic_content);
                            @endphp
                            <tr id="lp-row-{{ $firstPlan->id }}" data-plan-id="{{ $firstPlan->id }}" data-block-ids="{{ $blockIds }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2 font-normal text-center text-white text-xs">Sess {{ $sIdx + 1 }}</td>
                                <td class="p-2">
                                    <select id="lp-pedagogy-{{ $firstPlan->id }}" onchange="onPedagogyChange({{ $firstPlan->id }}, this.value); lpAutoSave({{ $firstPlan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-normal text-xs w-full text-emerald-400">
                                        <option value="Practical Lab (P)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Lab (P)' || ($firstPlan->mode === 'P' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                        <option value="Practical Series Exam (SP)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($firstPlan->mode === 'SP' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                        <option value="Lecture (L)" {{ ($firstPlan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($firstPlan->mode === 'L' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                        <option value="Theory Series Exam (ST)" {{ ($firstPlan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($firstPlan->mode === 'ST' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                        <option value="PPT Presentation" {{ ($firstPlan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                        <option value="Demonstration" {{ ($firstPlan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Group Activity" {{ ($firstPlan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    @php
                                        $propValPr = '';
                                        if (!empty($firstPlan->proposed_date)) {
                                            $propValPr = preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $firstPlan->proposed_date, $m) ? "{$m[3]}/{$m[2]}/{$m[1]}" : $firstPlan->proposed_date;
                                        }
                                    @endphp
                                    <input type="text" id="lp-prop-{{ $firstPlan->id }}" value="{{ $propValPr }}" placeholder="dd/mm/yyyy" onchange="lpAutoSave({{ $firstPlan->id }})" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-slate-200 text-xs w-full text-center font-mono focus:border-emerald-500 outline-none">
                                </td>
                                <td class="p-2">
                                    @php
                                        $actValPr = '';
                                        if (!empty($firstPlan->actual_date)) {
                                            $actValPr = preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $firstPlan->actual_date, $m) ? "{$m[3]}/{$m[2]}/{$m[1]}" : $firstPlan->actual_date;
                                        }
                                    @endphp
                                    <input type="text" id="lp-act-{{ $firstPlan->id }}" value="{{ $actValPr }}" placeholder="dd/mm/yyyy" onchange="lpAutoSave({{ $firstPlan->id }})" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-emerald-400 text-xs w-full text-center font-mono focus:border-emerald-500 outline-none">
                                </td>
                                <td class="p-2">
                                    <textarea id="lp-topic-{{ $firstPlan->id }}" rows="2" class="bg-slate-900 border border-slate-700 rounded p-1.5 text-slate-100 text-xs font-normal w-full focus:border-emerald-500 outline-none resize-y leading-snug" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'; lpAutoSave({{ $firstPlan->id }})">{{ $cleanTopic }}</textarea>
                                </td>
                                <td class="p-2 text-center">
                                    <select id="lp-co-{{ $firstPlan->id }}" onchange="lpAutoSave({{ $firstPlan->id }})" class="bg-slate-900 border border-amber-500/40 rounded px-1 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOpt)
                                            <option value="{{ $coOpt }}" {{ ($firstPlan->co_id ?? 'CO1') === $coOpt ? 'selected' : '' }} style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="lp-batch-td-{{ $firstPlan->id }}" class="p-2 text-center">
                                    @php
                                        $bVal = $firstPlan->sub_batch ?? 'A & B';
                                        if (in_array($bVal, ['Batch A & B', 'Batch A & B (Combined)', 'A & B'])) $bVal = 'A & B';
                                        elseif (in_array($bVal, ['Batch A', 'A'])) $bVal = 'A';
                                        elseif (in_array($bVal, ['Batch B', 'B'])) $bVal = 'B';
                                        else $bVal = 'ALL';
                                    @endphp
                                    <select id="lp-batch-{{ $firstPlan->id }}" onchange="lpAutoSave({{ $firstPlan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-mono text-xs text-emerald-400 w-full text-center">
                                        <option value="A & B" {{ $bVal === 'A & B' ? 'selected' : '' }}>A & B</option>
                                        <option value="A" {{ $bVal === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $bVal === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="ALL" {{ $bVal === 'ALL' ? 'selected' : '' }}>ALL</option>
                                    </select>
                                </td>
                                <td id="lp-hours-td-{{ $firstPlan->id }}" class="p-2 text-center font-normal">
                                    <span class="px-1 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-normal">3 Hrs</span>
                                </td>
                                <td class="p-2">
                                    <input type="text" id="lp-remarks-{{ $firstPlan->id }}" value="{{ $firstPlan->remarks }}" placeholder="Status/Remarks" onchange="lpAutoSave({{ $firstPlan->id }})" class="bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-slate-400 text-xs w-full">
                                </td>
                                <td class="p-1 text-center">
                                    <button type="button" onclick="confirmDeleteLessonPlanRow({{ $firstPlan->id }})" title="Delete row" class="w-6 h-6 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all text-xs font-bold mx-auto">&times;</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="10" class="p-5 text-center text-slate-400 font-normal">No practical hours scheduled yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-800 mt-3">
                    <button type="button" onclick="addCustomLessonPlanRow('lp-theory-tbody', 'P')" class="px-3.5 py-2 bg-blue-600/20 hover:bg-blue-600/35 text-sky-300 border border-blue-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Row (Customization)</span>
                    </button>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-slate-400 hidden md:inline">Save changes for all practical lab session rows.</span>
                        <button type="button" onclick="saveAllLessonPlans()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all flex items-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Changes</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Subtab 3: Continuous Practical Evaluation -->
            <div id="lab-subcontent-eval" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-normal text-white">Continuous Practical Evaluation (CE - 10 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs mt-0.5 font-normal">Table 2.2 Rubrics (Criteria 1 to 6 out of 50 Marks) converted to 10 CIA marks &bull; Inline editable with auto-save</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1">
                            <label class="text-xs text-slate-400 font-semibold whitespace-nowrap">Experiment:</label>
                            <select id="ce-table-exp-select" onchange="onCeTableExpChange(this.value)" class="bg-transparent text-emerald-400 font-bold text-xs outline-none cursor-pointer">
                                <option value="Continuous Evaluation" class="bg-slate-900 text-amber-300">⭐ Continuous Evaluation (Table 2.2)</option>
                                @php
                                    $evalExps = $practicumCourseFile->parsed_experiments ?? [];
                                    if (is_string($evalExps)) $evalExps = json_decode($evalExps, true) ?: [];
                                @endphp
                                @foreach($evalExps as $exp)
                                @php
                                    $expCodeVal = $exp['code'] ?? ($exp['experiment_no'] ?? '');
                                @endphp
                                <option value="{{ $expCodeVal }}" class="bg-slate-900 text-slate-200">{{ $expCodeVal }} - {{ $exp['title'] ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button onclick="printSubtabReport('Continuous Lab Evaluation (CE - 10M) Report', 'lab-subcontent-eval')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                        <button onclick="openExperimentEvalModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs shadow-sm transition-all">Evaluate Experiment</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header" id="ce-eval-table">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-normal uppercase bg-slate-900/80 text-[10px]">
                                <th class="p-2 text-center w-8">Roll</th>
                                <th class="p-2 w-28">SBTE Reg No</th>
                                <th class="p-2 min-w-[130px]">Student Name</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Prep (10M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Setup (10M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Obs (5M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Analysis (10M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Viva (10M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Work (5M)</th>
                                <th class="p-2 text-center w-20 text-sky-400 font-bold">Total (/50)</th>
                                <th class="p-2 text-center w-24 text-amber-300 font-bold">Converted CIA (/10M)</th>
                                <th class="p-2 text-center w-12 no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ce-eval-tbody" class="divide-y divide-slate-800/60 font-normal text-xs">
                            @foreach($studentResults as $res)
                            @php
                                $stExps = $experimentEvals->get($res['reg_no'], collect());
                                $count = $stExps->count();
                                $avgPrep = $count > 0 ? $stExps->avg('prep_punctuality') : 0;
                                $avgSetup = $count > 0 ? $stExps->avg('setup_procedure') : 0;
                                $avgObs = $count > 0 ? $stExps->avg('observation_recording') : 0;
                                $avgAnalysis = $count > 0 ? $stExps->avg('analysis_interpretation') : 0;
                                $avgViva = $count > 0 ? $stExps->avg('viva_voce') : 0;
                                $avgWorkmanship = $count > 0 ? $stExps->avg('workmanship_discipline') : 0;
                                $totalAvg50 = $avgPrep + $avgSetup + $avgObs + $avgAnalysis + $avgViva + $avgWorkmanship;
                                $cia10 = round((($totalAvg50 / 50.0) * 10.0) * 2) / 2;
                            @endphp
                            <tr id="ce-row-{{ $res['reg_no'] }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2 text-center text-slate-400 font-bold">{{ $res['roll_no'] }}</td>
                                <td class="p-2 font-mono text-[11px] font-bold text-emerald-400/90">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2 font-medium text-slate-200">
                                    <div class="text-[11px] font-bold text-white truncate max-w-[140px]" title="{{ $res['name'] }}">{{ $res['name'] }}</div>
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="ce-input-{{ $res['reg_no'] }}-prep_punctuality" value="{{ number_format($avgPrep, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'prep_punctuality', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="ce-input-{{ $res['reg_no'] }}-setup_procedure" value="{{ number_format($avgSetup, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'setup_procedure', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="5" id="ce-input-{{ $res['reg_no'] }}-observation_recording" value="{{ number_format($avgObs, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'observation_recording', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="ce-input-{{ $res['reg_no'] }}-analysis_interpretation" value="{{ number_format($avgAnalysis, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'analysis_interpretation', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="ce-input-{{ $res['reg_no'] }}-viva_voce" value="{{ number_format($avgViva, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'viva_voce', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="5" id="ce-input-{{ $res['reg_no'] }}-workmanship_discipline" value="{{ number_format($avgWorkmanship, 1) }}" oninput="onCeTableInput('{{ $res['reg_no'] }}', 'workmanship_discipline', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center font-mono font-bold text-sky-400 text-xs">
                                    <span id="ce-total-{{ $res['reg_no'] }}">{{ number_format($totalAvg50, 1) }}</span>
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="ce-cia-{{ $res['reg_no'] }}" value="{{ number_format($cia10, 1) }}" oninput="onCeCiaInput('{{ $res['reg_no'] }}', this.value)" class="w-14 bg-slate-950 border border-amber-500/40 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-black text-amber-300 text-xs outline-none transition-all" title="Direct Converted CIA mark (auto-scales criteria)">
                                </td>
                                <td class="p-1.5 text-center no-print">
                                    <button type="button" onclick="openExpModalForStudent('{{ $res['reg_no'] }}')" title="Evaluate in Card Modal" class="px-2 py-0.5 rounded bg-emerald-600/30 hover:bg-emerald-600/60 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold transition-all cursor-pointer">
                                        ✏️
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 4: Practical Series Examinations -->
            <div id="lab-subcontent-series" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-normal text-white">Practical Series Examinations</h3>
                        <p class="text-slate-400 text-xs mt-0.5 font-normal">Table 3.1 Rubrics (Criteria 1 to 5 out of 40 Marks) &bull; Scaled to 10 CIA marks &bull; Inline editable with auto-save</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1">
                            <label class="text-xs text-slate-400 font-semibold whitespace-nowrap">Series Test:</label>
                            <select id="series-pr-table-test-select" onchange="onSeriesPrTableTestChange(this.value)" class="bg-transparent text-amber-400 font-bold text-xs outline-none cursor-pointer">
                                <option value="Series 1" class="bg-slate-900 text-amber-300">🧪 Practical Test 1 (CO1+CO2)</option>
                                <option value="Series 2" class="bg-slate-900 text-sky-300">🧪 Practical Test 2 (CO3+CO4)</option>
                            </select>
                        </div>
                        <button onclick="printSubtabReport('Practical Series Examinations Report', 'lab-subcontent-series')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                        <button onclick="openSeriesPracticalModal()" class="px-3 py-1.5 rounded-lg bg-sky-600/20 hover:bg-sky-600/35 text-sky-300 border border-sky-500/40 font-semibold text-xs shadow-sm transition-all">Enter Lab Series Test Marks</button>
                    </div>
                </div>
 
                <!-- Practical QP Generator Panel -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4 mb-5 no-print">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-blue-500/10 text-sky-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 flex-wrap">
                                <span>🧪 Practical Series QP Generator</span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-1">
                                Rubrics grading: Procedure (10M) + Setup (10M) + Result (10M) + Viva (5M) + Record (5M) = 40 Marks | 3 Hours | Scaled to 10 CIA Marks
                            </p>
                        </div>
                    </div>
 
                    <!-- 2 Practical Series Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Practical Series 1' => 'CO1+CO2', 'Practical Series 2' => 'CO3+CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-900/15' : 'border-slate-700 bg-slate-800/50' }} p-3 flex flex-col gap-2">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white text-sm">{{ $series }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $savedQp ? 'bg-emerald-600/30 text-emerald-300' : 'bg-slate-700 text-slate-400' }}">{{ $co }}</span>
                            </div>
 
                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-400 font-semibold">✅ Practical QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif
 
                            <!-- Generate buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-sm font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 transition-all text-center">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-sm font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 transition-all text-center">
                                    ✏ Manual Entry
                                </button>
                            </div>
 
                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-700/50 pt-2 flex flex-col gap-2">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-2 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-blue-500/30 text-sky-300 text-center block no-underline">
                                    🖨️ Print Practical QP
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block no-underline">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block no-underline">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->
                </div><!-- /Practical QP Generator Panel -->

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header" id="series-pr-eval-table">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-normal uppercase bg-slate-900/80 text-[10px]">
                                <th class="p-2 text-center w-8">Roll</th>
                                <th class="p-2 w-28">SBTE Reg No</th>
                                <th class="p-2 min-w-[130px]">Student Name</th>
                                <th class="p-2 text-center w-16 text-sky-400 font-bold" id="th-sp-crit-1">Writeup (10M)</th>
                                <th class="p-2 text-center w-16 text-sky-400 font-bold" id="th-sp-crit-2">Setup (10M)</th>
                                <th class="p-2 text-center w-16 text-sky-400 font-bold" id="th-sp-crit-3">Obs/Res (8M)</th>
                                <th class="p-2 text-center w-16 text-sky-400 font-bold" id="th-sp-crit-4">Viva (8M)</th>
                                <th class="p-2 text-center w-16 text-sky-400 font-bold" id="th-sp-crit-5">Record (4M)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Test 1 (/40)</th>
                                <th class="p-2 text-center w-16 text-emerald-400 font-bold">Test 2 (/40)</th>
                                <th class="p-2 text-center w-16 text-indigo-300 font-bold">Avg (/40)</th>
                                <th class="p-2 text-center w-24 text-amber-300 font-bold">Converted CIA (/10M)</th>
                                <th class="p-2 text-center w-12 no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody id="series-pr-eval-tbody" class="divide-y divide-slate-800/60 font-normal text-xs">
                            @foreach($studentResults as $res)
                            @php
                                $spEvals = $seriesPracticalEvals->get($res['reg_no'], collect());
                                $sp1 = $spEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)', 'Test 1', 'CA2'])->first();
                                $sp2 = $spEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)', 'Test 2', 'CA3'])->first();
                                $sp1Score = $sp1 ? $sp1->total_score_40 : 0.00;
                                $sp2Score = $sp2 ? $sp2->total_score_40 : 0.00;
                                $avgScore = ($sp1Score + $sp2Score) / 2.0;
                                $ciaScore = round((($avgScore / 40.0) * 10.0) * 2) / 2;

                                // Initial active test is Series 1
                                $activeW = $sp1 ? $sp1->writeup_procedure : 0;
                                $activeS = $sp1 ? $sp1->setup_execution : 0;
                                $activeO = $sp1 ? $sp1->observation_result : 0;
                                $activeV = $sp1 ? $sp1->viva_voce : 0;
                                $activeR = $sp1 ? $sp1->record_completion : 0;
                            @endphp
                            <tr id="sp-row-{{ $res['reg_no'] }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2 text-center text-slate-400 font-bold">{{ $res['roll_no'] }}</td>
                                <td class="p-2 font-mono text-[11px] font-bold text-sky-400/90">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2 font-medium text-slate-200">
                                    <div class="text-[11px] font-bold text-white truncate max-w-[140px]" title="{{ $res['name'] }}">{{ $res['name'] }}</div>
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="sp-input-{{ $res['reg_no'] }}-writeup_procedure" value="{{ number_format($activeW, 1) }}" oninput="onSeriesPrTableInput('{{ $res['reg_no'] }}', 'writeup_procedure', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-sky-400 focus:border-sky-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="sp-input-{{ $res['reg_no'] }}-setup_execution" value="{{ number_format($activeS, 1) }}" oninput="onSeriesPrTableInput('{{ $res['reg_no'] }}', 'setup_execution', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-sky-400 focus:border-sky-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="8" id="sp-input-{{ $res['reg_no'] }}-observation_result" value="{{ number_format($activeO, 1) }}" oninput="onSeriesPrTableInput('{{ $res['reg_no'] }}', 'observation_result', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-sky-400 focus:border-sky-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="8" id="sp-input-{{ $res['reg_no'] }}-viva_voce" value="{{ number_format($activeV, 1) }}" oninput="onSeriesPrTableInput('{{ $res['reg_no'] }}', 'viva_voce', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-sky-400 focus:border-sky-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="4" id="sp-input-{{ $res['reg_no'] }}-record_completion" value="{{ number_format($activeR, 1) }}" oninput="onSeriesPrTableInput('{{ $res['reg_no'] }}', 'record_completion', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-sky-400 focus:border-sky-400 rounded px-1 py-0.5 text-center font-mono font-bold text-slate-200 text-xs outline-none transition-all">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="40" id="sp-score-t1-{{ $res['reg_no'] }}" value="{{ number_format($sp1Score, 1) }}" oninput="onSeriesPrTotalInput('{{ $res['reg_no'] }}', 'Series 1', this.value)" class="w-14 bg-slate-950 border border-emerald-500/40 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-emerald-400 text-xs outline-none transition-all" title="Test 1 Total (/40)">
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="40" id="sp-score-t2-{{ $res['reg_no'] }}" value="{{ number_format($sp2Score, 1) }}" oninput="onSeriesPrTotalInput('{{ $res['reg_no'] }}', 'Series 2', this.value)" class="w-14 bg-slate-950 border border-emerald-500/40 hover:border-emerald-400 focus:border-emerald-400 rounded px-1 py-0.5 text-center font-mono font-bold text-emerald-400 text-xs outline-none transition-all" title="Test 2 Total (/40)">
                                </td>
                                <td class="p-1.5 text-center font-mono font-bold text-indigo-300 text-xs">
                                    <span id="sp-avg-{{ $res['reg_no'] }}">{{ number_format($avgScore, 1) }}</span>
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" step="0.5" min="0" max="10" id="sp-cia-{{ $res['reg_no'] }}" value="{{ number_format($ciaScore, 1) }}" oninput="onSeriesPrCiaInput('{{ $res['reg_no'] }}', this.value)" class="w-14 bg-slate-950 border border-amber-500/40 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-black text-amber-300 text-xs outline-none transition-all" title="Direct Practical Series CIA mark (/10M)">
                                </td>
                                <td class="p-1.5 text-center no-print">
                                    <button type="button" onclick="openSeriesPrModalForStudent('{{ $res['reg_no'] }}')" title="Evaluate in Card Modal" class="px-2 py-0.5 rounded bg-sky-600/30 hover:bg-sky-600/60 text-sky-300 border border-sky-500/30 text-[10px] font-bold transition-all cursor-pointer">
                                        ✏️
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 5: Practical ESE (Basic Science Internal Scheme) -->
            <div id="lab-subcontent-ese" class="glass-card p-6 rounded-xl border border-sky-500/30 bg-gradient-to-br from-slate-950 via-slate-900 to-sky-950/30 hidden space-y-5">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3 pb-4 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-500/30 flex items-center justify-center text-xl shrink-0">
                            🔬
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <span>Basic Science Practicum: Practical ESE is Internal Only</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-xs font-semibold">0 ESE Marks</span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-0.5">
                                SBTE Revision 2026 Examination Scheme • 100% Internal Lab Evaluation (Continuous CE 10M + Practical Tests 10M)
                            </p>
                        </div>
                    </div>
                    <button onclick="switchMode('theory'); switchTheorySubtab('ese');" class="px-3.5 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                        <span>🏆 Go to Theory Written ESE (60M)</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- 1. Attendance -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-cyan-400 font-bold text-xs uppercase tracking-wider">1. Attendance (CA)</div>
                        <div class="text-2xl font-black text-white font-mono">5 Marks <span class="text-xs text-slate-500 font-normal">/ 5 CIA</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Continuous attendance evaluation based on overall student presence across theory lectures and 3-hour practical lab sessions.
                        </p>
                    </div>

                    <!-- 2. Self Learning Activities -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-purple-400 font-bold text-xs uppercase tracking-wider">2. Self-Learning Activities (SLA / CA1)</div>
                        <div class="text-2xl font-black text-white font-mono">5 Marks <span class="text-xs text-slate-500 font-normal">/ 5 CIA</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Average of self-learning assessments across each module including microprojects, case studies, assignments, and quizzes.
                        </p>
                    </div>

                    <!-- 3. Theory Series Examinations -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-indigo-400 font-bold text-xs uppercase tracking-wider">3. Theory Series Exams (CA4 &amp; CA5)</div>
                        <div class="text-2xl font-black text-white font-mono">10 Marks <span class="text-xs text-slate-500 font-normal">/ 20 Scaled</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Two centralized 1.5-hour written series tests (Series 1: Modules 1 &amp; 2, Series 2: Modules 3 &amp; 4); average converted to 10 CIA marks.
                        </p>
                    </div>

                    <!-- 4. Continuous Lab Evaluation -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-sky-400 font-bold text-xs uppercase tracking-wider">4. Continuous Lab Evaluation (CE)</div>
                        <div class="text-2xl font-black text-white font-mono">10 Marks <span class="text-xs text-slate-500 font-normal">/ 50 Scaled</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Continuous day-to-day experiment evaluation based on Table 2.2 rubrics (Preparation, Setup, Observation, Analysis, Viva, Discipline).
                        </p>
                    </div>

                    <!-- 5. Practical Tests -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-amber-400 font-bold text-xs uppercase tracking-wider">5. Practical Tests (CA2 &amp; CA3)</div>
                        <div class="text-2xl font-black text-white font-mono">10 Marks <span class="text-xs text-slate-500 font-normal">/ 40 Scaled</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Two 3-hour practical examinations: Test 1 (first half of experiments) and Test 2 (second half of experiments) as evaluated in Table 3.1.
                        </p>
                    </div>

                    <!-- 6. Theory Written ESE -->
                    <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="text-emerald-400 font-bold text-xs uppercase tracking-wider">6. Theory Written ESE (Board Exam)</div>
                        <div class="text-2xl font-black text-white font-mono">60 Marks <span class="text-xs text-slate-500 font-normal">Pass: 24/60</span></div>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            External written board examination of 2.5 hours duration covering all 4 modules. Grades S, A, B, C, D, E, F awarded by SBTE.
                        </p>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">📌</span>
                        <span>Total Course Marks = <strong>40 CIA</strong> (Attendance 5 + SLA 5 + Theory Series 10 + Continuous Lab CE 10 + Practical Tests 10) + <strong>60 ESE</strong> (Written Theory) = <strong class="text-emerald-400">100 Marks</strong></span>
                    </div>
                    <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-700 font-mono text-sky-300 font-bold">Min Passing: 40/100 Combined</span>
                </div>
            </div>

        </div>

    <!-- Upload Syllabus Modal -->
    <div id="syllabus-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-lg w-full p-6 rounded-2xl border border-slate-800 space-y-5 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center space-x-2.5">
                    <div class="p-2 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Upload Practicum Syllabus PDF</h3>
                        <p class="text-xs text-slate-400">Extracts modules, experiments, COs & CO-PO matrix automatically</p>
                    </div>
                </div>
                <button onclick="closeSyllabusModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="uploadSyllabusForm" onsubmit="uploadSyllabusPdf(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Select Syllabus PDF File (Max 10MB)</label>
                    <input type="file" id="syllabus_file_input" name="syllabus_file" accept=".pdf" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-xs text-slate-200 focus:border-blue-500 outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600/20 file:text-blue-300 hover:file:bg-blue-600/35 cursor-pointer">
                </div>

                <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 text-xs text-blue-300 space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>SITTTR R2026 Practicum PDF Parser</span>
                    </div>
                    <p class="text-slate-400 text-[11px] leading-relaxed">
                        Uploaded syllabus will dynamically update Course File metadata, Theory Modules, Practical Experiments, Course Outcomes (COs), CIE & ESE Marks, and CO-PO matrix mapping.
                    </p>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeSyllabusModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold hover:bg-slate-700 transition-all">Cancel</button>
                    <button type="submit" id="btnUploadSyllabusSubmit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition-all flex items-center space-x-2 shadow-lg shadow-blue-600/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Parse & Upload PDF</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customize Self-Learning Activities Modal -->
    <div id="sl-config-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full p-6 rounded-2xl border border-slate-700 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-xl font-bold text-white">Customize Self-Learning Activities (CA1)</h3>
                <button onclick="closeSlConfigModal()" class="text-slate-400 hover:text-white text-xl">&times;</button>
            </div>
            
            <p class="text-slate-400 text-xs">Mandatory core activities (<span class="text-amber-400 font-bold">Assignment</span> & <span class="text-emerald-400 font-bold">MCQ</span>) are always evaluated out of 15 Marks. Select optional assessment activities per CO:</p>

            <form id="sl-config-form" onsubmit="saveSlConfig(event)" class="space-y-4 max-h-[450px] overflow-y-auto pr-1">
                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 space-y-2">
                    <h4 class="font-bold text-amber-400 text-sm">{{ $coTag }} Assessment Activities</h4>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                        <label class="flex items-center space-x-2 text-slate-300 opacity-80 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded bg-slate-800 border-slate-700 text-amber-500">
                            <span class="font-bold">Assignment (Mandatory)</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-300 opacity-80 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded bg-slate-800 border-slate-700 text-emerald-500">
                            <span class="font-bold">MCQ (Mandatory)</span>
                        </label>
                        @foreach(['case_study' => 'Case Study', 'quiz' => 'Quiz', 'activity' => 'Activity', 'microproject' => 'Microproject', 'mini_project' => 'Mini Project', 'report' => 'Report', 'exercises' => 'Exercises', 'presentation' => 'Presentation'] as $actKey => $actLabel)
                        <label class="flex items-center space-x-2 text-slate-200 cursor-pointer">
                            <input type="checkbox" name="configs[{{ $coTag }}][{{ $actKey }}]" value="1" {{ !empty($slConfigs[$coTag][$actKey]) ? 'checked' : '' }} class="rounded bg-slate-800 border-slate-700 text-blue-500 focus:ring-0">
                            <span>{{ $actLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700 text-xs">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm">Save Activities Config</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enter Self-Learning Marks Modal (CA1 Activity-Wise Sliders) -->
    <div id="sl-marks-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-2 sm:p-4">
        <div class="glass-card max-w-6xl w-full p-4 sm:p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-3 max-h-[95vh] flex flex-col bg-slate-950 overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 flex-shrink-0">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                        <span>Continuous Assessment Activity Evaluator</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-semibold">CA1 - 5 CIA Marks</span>
                    </h3>
                    <p class="text-slate-400 text-xs mt-0.5">Filter by CO & activity or adjust high-density sliders and steppers for each student.</p>
                </div>
                <button onclick="closeSlMarksModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <!-- Student & CO / Activity Selection Controls Bar -->
            <div class="bg-slate-900/90 p-2.5 rounded-xl border border-slate-800 flex flex-wrap items-center justify-between gap-2.5 flex-shrink-0">
                <div class="flex items-center space-x-2 flex-1 min-w-[280px]">
                    <button type="button" onclick="prevSlStudent()" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                        <span>◀ Prev</span>
                    </button>
                    <select id="sl-student-select" onchange="loadSlStudent(this.value)" class="flex-1 bg-slate-950 border border-slate-700 rounded-lg px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="nextSlStudent()" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                        <span>Next ▶</span>
                    </button>
                </div>

                <!-- CO Filter Dropdown -->
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-amber-400 whitespace-nowrap">Target CO:</label>
                    <select id="sl-co-filter" onchange="loadSlStudent(document.getElementById('sl-student-select').value)" class="bg-slate-950 border border-amber-500/40 text-amber-300 font-bold text-xs rounded-lg px-3 py-1.5 outline-none focus:border-amber-400 cursor-pointer">
                        <option value="ALL">All Outcomes (CO1 - CO4)</option>
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                    </select>
                </div>

                <!-- Activity Filter Dropdown -->
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-emerald-400 whitespace-nowrap">Activity:</label>
                    <select id="sl-activity-filter" onchange="loadSlStudent(document.getElementById('sl-student-select').value)" class="bg-slate-950 border border-emerald-500/40 text-emerald-300 font-bold text-xs rounded-lg px-3 py-1.5 outline-none focus:border-emerald-400 cursor-pointer">
                        <option value="ALL">All Activities</option>
                        <option value="assignment">Assignment</option>
                        <option value="mcq">MCQ</option>
                        <option value="quiz">Quiz</option>
                        <option value="case_study">Case Study</option>
                        <option value="activity">Activity</option>
                        <option value="microproject">Microproject</option>
                        <option value="report">Report</option>
                        <option value="exercises">Exercises</option>
                        <option value="presentation">Presentation</option>
                    </select>
                </div>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 via-blue-950/30 to-slate-900 p-2.5 rounded-xl border border-blue-500/30 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-medium">Selected Student Overall Raw Average:</span>
                    <span id="sl-student-total-raw" class="font-extrabold text-amber-400 text-sm ml-1.5">0.00 / 15.00 M</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="text-slate-400 font-medium">Converted CA1 CIA Score:</span>
                    <span id="sl-student-converted-cia" class="font-black text-emerald-400 text-sm ml-1 px-2.5 py-0.5 rounded bg-emerald-500/20 border border-emerald-500/30">0.00 / 5.00 M</span>
                </div>
            </div>

            <!-- Scrollable Activity Sliders Container -->
            <div id="sl-sliders-container" class="space-y-3 pr-1 border border-slate-800/80 rounded-xl p-2.5 bg-slate-900/40 max-h-[340px] overflow-y-auto flex-shrink-0">
                <!-- Dynamically populated by JS loadSlStudent() -->
            </div>

            <!-- Live Evaluated Confirmation Table Section -->
            <div class="border-t border-slate-800 pt-2 space-y-2 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-slate-200 flex items-center space-x-2">
                        <span>📋 Faculty Evaluation Confirmation Ledger</span>
                        <span id="sl-evaluated-count-badge" class="px-2 py-0.5 rounded-full bg-blue-500/15 text-sky-300 border border-blue-500/30 text-[10px] font-bold">0 Evaluated</span>
                    </h4>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="printSlModalLedger()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-teal-300 border border-teal-500/30 font-bold text-[11px] flex items-center space-x-1 transition-all cursor-pointer shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>🖨️ Print Ledger</span>
                        </button>
                    </div>
                </div>

                <!-- Ledger Table Container -->
                <div class="max-h-48 overflow-y-auto rounded-xl border border-slate-800 bg-slate-900/60 p-1 flex-shrink-0">
                    <table class="w-full text-left border-collapse text-[11px]" id="sl-confirmation-table">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider bg-slate-950/80 text-[10px]">
                                <th class="p-1.5 text-center w-8">Roll</th>
                                <th class="p-1.5 w-28">SBTE Reg No</th>
                                <th class="p-1.5 min-w-[130px]">Student Name</th>
                                <th class="p-1.5 text-center w-16 text-amber-300">CO1 (15)</th>
                                <th class="p-1.5 text-center w-16 text-amber-300">CO2 (15)</th>
                                <th class="p-1.5 text-center w-16 text-amber-300">CO3 (15)</th>
                                <th class="p-1.5 text-center w-16 text-amber-300">CO4 (15)</th>
                                <th class="p-1.5 text-center w-20 text-sky-300">Raw Avg</th>
                                <th class="p-1.5 text-center w-24 text-emerald-300">CA1 (5M)</th>
                                <th class="p-1.5 text-center w-14">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sl-confirmation-tbody" class="divide-y divide-slate-800/60 font-normal text-slate-300">
                            <!-- Populated dynamically when loaded & saved -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-2.5 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSlMarksModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSlStudent()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save & Next Student ▶</button>
                    <button type="button" onclick="saveAllSlMarks()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Practical ESE Evaluator Modal (Single Page Desktop View with Small Sliders) -->
    <div id="ese-practical-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-4xl lg:max-w-5xl w-full p-4 sm:p-5 rounded-2xl border border-blue-500/30 shadow-2xl space-y-3 max-h-[92vh] flex flex-col bg-slate-950">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🏆</span>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Practical End Semester Exam Evaluator (Table 4.1)</h3>
                        <p class="text-slate-400 text-[11px] leading-tight mt-0.5">Grade student on 5 practical rubrics (40 Marks) &bull; Auto-saved</p>
                    </div>
                </div>
                <button type="button" onclick="closeEsePracticalModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-900/90 p-2.5 rounded-xl border border-slate-800 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">◀ Prev</button>

                <div class="flex-1 max-w-lg">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-slate-950 border border-blue-500/40 rounded-lg px-2.5 py-1 font-bold text-xs text-blue-200 outline-none focus:border-blue-400 cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextEseStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">Next ▶</button>
            </div>

            <!-- Rubrics Form Card (Single Page Grid on Desktop) -->
            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2 flex-shrink-0" id="ese-rubrics-container">
                <!-- Javascript will populate 3-col grid with small sliders & data entry fields -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center text-xs flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400 font-semibold text-xs">Practical ESE Score:</span>
                    <span id="ese-student-total-raw" class="font-bold text-sky-400 text-sm font-mono">0.00 / 40.00 M</span>
                </div>
                <div class="flex items-center gap-2 text-right">
                    <span class="text-slate-400 font-semibold text-xs">Evaluated Grade:</span>
                    <span id="ese-student-grade-badge" class="font-bold text-blue-300 text-sm font-mono px-2 py-0.5 rounded bg-blue-500/10 border border-blue-500/20">S</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-2.5 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeEsePracticalModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700 cursor-pointer">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="header-btn px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save & Next Student ▶</button>
                    <button type="button" onclick="saveAllEseMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Theory ESE Grade Entry Modal -->
    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-3xl w-full p-5 rounded-2xl border border-blue-500/40 shadow-2xl space-y-4 max-h-[92vh] flex flex-col bg-slate-950">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-sky-300">Board Theory ESE Grade Entry</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select official letter grades issued by SBTE board norms.</p>
                </div>
                <button onclick="closeEseTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="sticky top-0 bg-slate-900 shadow">
                        <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase">
                            <th class="p-2.5 w-12 text-center">Roll</th>
                            <th class="p-2.5">Reg No</th>
                            <th class="p-2.5">Student Name</th>
                            <th class="p-2.5 text-center">Board Letter Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-normal">
                        @foreach($studentResults as $res)
                        @php $curGrade = strtoupper($res['ese_theory_grade'] ?? ''); @endphp
                        <tr class="hover:bg-slate-800/30">
                            <td class="p-2.5 text-center text-slate-400">{{ $res['roll_no'] }}</td>
                            <td class="p-2.5 font-mono text-slate-300">{{ $res['reg_no'] }}</td>
                            <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                            <td class="p-2.5 text-center">
                                <select id="ese-theory-grade-{{ $res['reg_no'] }}" class="bg-slate-900 border border-slate-700 rounded px-3 py-1 text-xs font-bold text-sky-300 outline-none focus:border-blue-400">
                                    <option value="" {{ $curGrade === '' ? 'selected' : '' }}>-- Select Grade --</option>
                                    <option value="S" {{ $curGrade === 'S' ? 'selected' : '' }}>S (90% & above - Outstanding)</option>
                                    <option value="A" {{ $curGrade === 'A' ? 'selected' : '' }}>A ([80-90) - Excellent)</option>
                                    <option value="B" {{ $curGrade === 'B' ? 'selected' : '' }}>B ([70-80) - Very Good)</option>
                                    <option value="C" {{ $curGrade === 'C' ? 'selected' : '' }}>C ([60-70) - Good)</option>
                                    <option value="D" {{ $curGrade === 'D' ? 'selected' : '' }}>D ([50-60) - Average)</option>
                                    <option value="E" {{ $curGrade === 'E' ? 'selected' : '' }}>E ([40-50) - Satisfactory)</option>
                                    <option value="F" {{ $curGrade === 'F' ? 'selected' : '' }}>F (Below 40 - Reappearance Required)</option>
                                    <option value="FE" {{ $curGrade === 'FE' ? 'selected' : '' }}>FE (Shortage of Attendance)</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeEseTheoryModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Cancel</button>
                <button type="button" onclick="saveAllEseTheoryGrades()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm">Save Theory Grades</button>
            </div>
        </div>
    </div>

    <!-- JavaScript Switching & Handlers -->
    <script>
        function savePracticumCoPoMatrix() {
            const btn = document.getElementById('saveCoPoMatrixBtn');
            const origHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>⏳ Saving...</span>';
            }

            const inputs = document.querySelectorAll('#practicumCoPoMatrixTbody input[data-co]');
            const mappings = {};

            inputs.forEach(input => {
                const co = input.getAttribute('data-co');
                const target = input.getAttribute('data-target');
                const val = input.value ? input.value : '-';
                if (!mappings[co]) {
                    mappings[co] = {};
                }
                mappings[co][target] = val;
            });

            fetch(`/api/r26/classroom/practicum/{{ $batchSubject->id }}/copo-matrix/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ mappings: mappings })
            })
            .then(res => res.json())
            .then(res => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                if (res.status === 'SUCCESS' || res.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Saved!', text: res.message || 'Matrix saved successfully.', timer: 1500, showConfirmButton: false });
                    } else {
                        alert(res.message || 'Matrix saved successfully!');
                    }
                } else {
                    alert(res.message || 'Failed to save matrix.');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                console.error(err);
                alert('An error occurred while saving matrix.');
            });
        }

        function switchMode(mode) {
            document.getElementById('mode-theory-container').classList.add('hidden');
            document.getElementById('mode-lab-container').classList.add('hidden');
            
            document.getElementById('mode-btn-theory').classList.remove('active', 'text-white');
            document.getElementById('mode-btn-lab').classList.remove('active', 'text-white');

            if (mode === 'theory') {
                document.getElementById('mode-theory-container').classList.remove('hidden');
                document.getElementById('mode-btn-theory').classList.add('active', 'text-white');
            } else {
                document.getElementById('mode-lab-container').classList.remove('hidden');
                document.getElementById('mode-btn-lab').classList.add('active', 'text-white');
            }
            localStorage.setItem('active_mode', mode);
        }

        function switchTheorySubtab(tab) {
            ['overview', 'planner', 'sl', 'series', 'ese', 'surveys', 'attendance', 'materials', 'attainment'].forEach(t => {
                document.getElementById('theory-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('theory-tab-' + t)?.classList.remove('active', 'text-white');
            });
            const sub = document.getElementById('theory-subcontent-' + tab);
            if (sub) {
                sub.classList.remove('hidden');
                const inner = sub.querySelector('.tab-panel');
                if (inner) inner.classList.remove('hidden');
            }
            document.getElementById('theory-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_theory_subtab', tab);
            if (tab === 'materials' && typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
            }
            if (tab === 'planner' && typeof initPracticumDatePickers === 'function') {
                initPracticumDatePickers();
            }
        }

        function switchLabSubtab(tab) {
            ['roster', 'planner', 'eval', 'series', 'ese', 'materials', 'attendance'].forEach(t => {
                document.getElementById('lab-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('lab-tab-' + t)?.classList.remove('active', 'text-white');
            });
            const sub = document.getElementById('lab-subcontent-' + tab);
            if (sub) {
                sub.classList.remove('hidden');
                const inner = sub.querySelector('.tab-panel');
                if (inner) inner.classList.remove('hidden');
            }
            document.getElementById('lab-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_lab_subtab', tab);
            if (tab === 'materials' && typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
            }
            if (tab === 'planner' && typeof initPracticumDatePickers === 'function') {
                initPracticumDatePickers();
            }
        }

        // =========================================================================
        // PRACTICUM EXPERIMENTS ROSTER CUSTOMIZATION & SYNC
        // =========================================================================
        function updateExpRosterSummary() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            let totalHours = 0;
            rows.forEach(r => {
                const hrsInput = r.querySelector('.exp-hours');
                totalHours += parseFloat(hrsInput?.value || 3);
            });
            const summaryEl = document.getElementById('exp-roster-summary');
            if (summaryEl) {
                summaryEl.innerText = `${rows.length} Experiments | ${totalHours} Practical Hours`;
            }
        }

        function reindexExperimentRows() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            rows.forEach((r, idx) => {
                const idxCell = r.querySelector('.exp-row-index');
                if (idxCell) idxCell.innerText = idx + 1;
            });
            updateExpRosterSummary();
        }

        function addExperimentRow() {
            const tbody = document.getElementById('exp-roster-tbody');
            const emptyRow = document.getElementById('exp-empty-row');
            if (emptyRow) emptyRow.remove();

            const rows = tbody.querySelectorAll('tr.exp-row');
            const newIdx = rows.length + 1;
            const sessCode = 'Sess ' + newIdx;
            const expCode = 'EXP-' + String(newIdx).padStart(2, '0');

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-800/30 transition-all exp-row';
            tr.innerHTML = `
                <td class="p-2 text-center text-slate-400 font-mono exp-row-index">${newIdx}</td>
                <td class="p-2">
                    <input type="text" class="exp-session-code bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-emerald-400 font-mono text-xs w-full focus:border-emerald-500 outline-none text-center" value="${sessCode}" placeholder="Sess ${newIdx}">
                </td>
                <td class="p-2">
                    <input type="text" class="exp-code bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-cyan-300 font-mono text-xs w-full focus:border-cyan-500 outline-none text-center" value="${expCode}" placeholder="EXP-${String(newIdx).padStart(2, '0')}">
                </td>
                <td class="p-2">
                    <textarea class="exp-title bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-xs font-normal w-full focus:border-emerald-500 outline-none resize-y leading-snug" rows="1" placeholder="Enter Experiment Title..." oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"></textarea>
                </td>
                <td class="p-2 text-center">
                    <select class="exp-co bg-slate-900 border border-amber-500/40 rounded px-2 py-1.5 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none text-center" style="background-color:#0f172a !important; color:#fcd34d !important;">
                        <option value="CO1" style="background-color:#0f172a; color:#fcd34d;">CO1</option>
                        <option value="CO2" style="background-color:#0f172a; color:#fcd34d;">CO2</option>
                        <option value="CO3" style="background-color:#0f172a; color:#fcd34d;">CO3</option>
                        <option value="CO4" style="background-color:#0f172a; color:#fcd34d;">CO4</option>
                        <option value="CO5" style="background-color:#0f172a; color:#fcd34d;">CO5</option>
                        <option value="CO6" style="background-color:#0f172a; color:#fcd34d;">CO6</option>
                    </select>
                </td>
                <td class="p-2 text-center">
                    <div class="flex items-center justify-center space-x-1">
                        <input type="number" min="1" max="12" class="exp-hours bg-slate-900 border border-slate-700 rounded px-2 py-1.5 text-white font-semibold text-xs w-14 text-center focus:border-emerald-500 outline-none" value="3" oninput="updateExpRosterSummary()">
                        <span class="text-xs text-slate-400">Hrs</span>
                    </div>
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="removeExperimentRow(this)" title="Remove experiment row" class="w-7 h-7 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all text-xs font-bold mx-auto cursor-pointer">🗑️</button>
                </td>
            `;

            tbody.appendChild(tr);
            reindexExperimentRows();
            const txt = tr.querySelector('.exp-title');
            if (txt) {
                txt.focus();
                tr.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        }

        function removeExperimentRow(btn) {
            if (!confirm('Are you sure you want to remove this experiment row?')) {
                return;
            }
            const tr = btn.closest('tr');
            if (tr) {
                tr.remove();
                reindexExperimentRows();
            }
        }

        function saveCustomExperimentsRoster() {
            const rows = document.querySelectorAll('#exp-roster-tbody tr.exp-row');
            if (rows.length === 0) {
                Swal.fire('Empty Roster', 'Please add at least one experiment row before saving.', 'warning');
                return;
            }

            const experiments = [];
            let hasMissingTitle = false;

            rows.forEach((r, idx) => {
                const sessionCode = (r.querySelector('.exp-session-code')?.value || '').trim() || ('Sess ' + (idx + 1));
                const code = (r.querySelector('.exp-code')?.value || '').trim() || ('EXP-' + String(idx + 1).padStart(2, '0'));
                const title = (r.querySelector('.exp-title')?.value || '').trim();
                const coId = r.querySelector('.exp-co')?.value || 'CO1';
                const hours = parseInt(r.querySelector('.exp-hours')?.value || 3);

                if (!title) {
                    hasMissingTitle = true;
                    r.querySelector('.exp-title')?.focus();
                }

                experiments.push({
                    session_code: sessionCode,
                    code: code,
                    experiment_no: code,
                    title: title,
                    co_id: coId,
                    hours: hours
                });
            });

            if (hasMissingTitle) {
                Swal.fire('Missing Experiment Title', 'Please provide a title for all experiment rows.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Saving Experiments Roster...',
                text: 'Updating course file and synchronizing practical lesson plans...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/experiments/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ experiments: experiments })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    // Update Planner tab in DOM if planner rows exist
                    const plannerRows = document.querySelectorAll('#lab-subcontent-planner tbody tr[id^="lp-row-"]');
                    data.experiments.forEach((exp, idx) => {
                        if (idx < plannerRows.length) {
                            const pRow = plannerRows[idx];
                            const planId = pRow.getAttribute('data-plan-id');
                            if (planId) {
                                const topicEl = document.getElementById('lp-topic-' + planId);
                                const coEl = document.getElementById('lp-co-' + planId);
                                if (topicEl) topicEl.value = `${exp.code}: ${exp.title}`;
                                if (coEl) coEl.value = exp.co_id;
                            }
                        }
                    });

                    // Update eval modal experiment select
                    const evalSelect = document.getElementById('eval-exp-select');
                    if (evalSelect) {
                        evalSelect.innerHTML = '';
                        data.experiments.forEach(exp => {
                            const opt = document.createElement('option');
                            opt.value = exp.code;
                            opt.textContent = `${exp.code} - ${exp.title}`;
                            evalSelect.appendChild(opt);
                        });
                    }

                    Swal.fire('Saved & Synchronized!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message || 'Failed to save experiments roster.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message || 'Network error occurred.', 'error');
            });
        }

        function exportExperimentsLogCsv() {
            window.location.href = '/r26/classroom/practicum/{{ $batchSubject->id }}/export-experiments-log-csv';
        }

        function updatePracticumAttainmentLevels(fromTarget = false) {
            const targetInput = document.getElementById('attainmentTargetPct');
            const lvl3Input = document.getElementById('attainmentLevel3Pct');
            const lvl2Input = document.getElementById('attainmentLevel2Pct');
            const lvl1Input = document.getElementById('attainmentLevel1Pct');
            if (!targetInput || !lvl3Input || !lvl2Input || !lvl1Input) return;

            const targetVal = parseFloat(targetInput.value || 70);
            if (fromTarget) {
                lvl3Input.value = targetVal;
                lvl2Input.value = Math.max(0, targetVal - 10);
                lvl1Input.value = Math.max(0, targetVal - 20);
            }

            const lvl3 = parseFloat(lvl3Input.value || targetVal);
            const lvl2 = parseFloat(lvl2Input.value || (targetVal - 10));
            const lvl1 = parseFloat(lvl1Input.value || (targetVal - 20));

            const eseLevelEl = document.getElementById('practicumEseLevelText');
            const metTargetEl = document.getElementById('practicumMetTargetText');
            if (eseLevelEl && metTargetEl) {
                const textMatch = metTargetEl.innerText.match(/([\d\.]+)%/);
                const metPct = textMatch ? parseFloat(textMatch[1]) : 78;
                if (metPct >= lvl3) {
                    eseLevelEl.innerText = `Level 3 (High)`;
                    eseLevelEl.className = 'text-sm font-black text-emerald-400';
                } else if (metPct >= lvl2) {
                    eseLevelEl.innerText = `Level 2 (Mod)`;
                    eseLevelEl.className = 'text-sm font-black text-amber-400';
                } else if (metPct >= lvl1) {
                    eseLevelEl.innerText = `Level 1 (Low)`;
                    eseLevelEl.className = 'text-sm font-black text-blue-400';
                } else {
                    eseLevelEl.innerText = `Level 0 (Nil)`;
                    eseLevelEl.className = 'text-sm font-black text-rose-400';
                }
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable fullscreen mode: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function openSyllabusModal() { document.getElementById('syllabus-modal').classList.remove('hidden'); }
        function closeSyllabusModal() { document.getElementById('syllabus-modal').classList.add('hidden'); }

        async function uploadSyllabusPdf(e) {
            if (e) e.preventDefault();
            const fileInput = document.getElementById('syllabus_file_input');
            if (!fileInput || !fileInput.files.length) {
                Swal.fire('Required', 'Please select a syllabus PDF file to upload.', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('syllabus_file', fileInput.files[0]);

            const submitBtn = document.getElementById('btnUploadSyllabusSubmit');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Parsing PDF...</span>
            `;

            try {
                const response = await fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/syllabus', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.status === 'SUCCESS') {
                    closeSyllabusModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Uploaded!',
                        text: data.message || 'Practicum syllabus uploaded and parsed successfully!',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Parsing Failed', data.message || 'Unable to parse syllabus PDF.', 'error');
                }
            } catch (err) {
                Swal.fire('Upload Error', err.message || 'Network error while uploading syllabus.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        function openSlConfigModal() { document.getElementById('sl-config-modal').classList.remove('hidden'); }
        function closeSlConfigModal() { document.getElementById('sl-config-modal').classList.add('hidden'); }

        function saveSlConfig(e) {
            e.preventDefault();
            const form = document.getElementById('sl-config-form');
            const formData = new FormData(form);
            const configs = {};

            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                configs[co] = { assignment: true, mcq: true };
            });

            formData.forEach((val, key) => {
                const matches = key.match(/configs\[(.*?)\]\[(.*?)\]/);
                if (matches) {
                    const co = matches[1];
                    const act = matches[2];
                    if (configs[co]) {
                        configs[co][act] = true;
                    }
                }
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/configs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ configs: configs })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeSlConfigModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Activities Configured!',
                        text: data.message || 'Self-learning activities updated successfully!',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        const slConfigs = @json($slConfigs);
        const slSplitupState = @json($slStudentSplitup);
        const studentsList = @json($studentResults->values()->all());

        const activityLabels = {
            'assignment': 'Assignment',
            'mcq': 'MCQ',
            'case_study': 'Case Study',
            'quiz': 'Quiz',
            'activity': 'Activity',
            'microproject': 'Microproject',
            'mini_project': 'Mini Project',
            'report': 'Report',
            'exercises': 'Exercises',
            'presentation': 'Presentation'
        };

        function openSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.remove('hidden');
            const sel = document.getElementById('sl-student-select');
            if (sel && sel.value) {
                renderSlConfirmationTable(sel.value);
                loadSlStudent(sel.value);
            } else {
                const container = document.getElementById('sl-sliders-container');
                if (container) {
                    container.innerHTML = `
                        <div class="p-8 text-center bg-slate-900/80 rounded-xl border border-slate-800 space-y-3 my-4">
                            <div class="text-amber-400 text-3xl">⚠️</div>
                            <h4 class="font-bold text-white text-base">No Students Enrolled</h4>
                            <p class="text-slate-400 text-xs max-w-md mx-auto">There are currently no students added to this classroom or batch. Please add students first to evaluate self-learning activity marks.</p>
                        </div>
                    `;
                }
            }
        }

        function closeSlMarksModal() {
            document.getElementById('sl-marks-modal').classList.add('hidden');
            if (typeof _slDirty !== 'undefined' && _slDirty) {
                location.reload();
            }
        }

        function loadSlStudent(regNo) {
            const container = document.getElementById('sl-sliders-container');
            if (!container) return;

            if (!slSplitupState[regNo]) {
                slSplitupState[regNo] = {
                    'CO1': { assignment: 0, mcq: 0 },
                    'CO2': { assignment: 0, mcq: 0 },
                    'CO3': { assignment: 0, mcq: 0 },
                    'CO4': { assignment: 0, mcq: 0 }
                };
            }

            const tbody = document.getElementById('sl-confirmation-tbody');
            if (tbody && tbody.children.length === 0) {
                renderSlConfirmationTable(regNo);
            } else {
                highlightSlLedgerRow(regNo);
            }

            const targetCo = document.getElementById('sl-co-filter') ? document.getElementById('sl-co-filter').value : 'ALL';
            const targetAct = document.getElementById('sl-activity-filter') ? document.getElementById('sl-activity-filter').value : 'ALL';

            const allCos = ['CO1', 'CO2', 'CO3', 'CO4'];
            const cos = (targetCo === 'ALL') ? allCos : [targetCo];

            let html = '';

            cos.forEach(co => {
                const activeActs = slConfigs[co] || { assignment: true, mcq: true };
                let actKeys = Object.keys(activeActs).filter(k => activeActs[k]);

                if (targetAct !== 'ALL') {
                    actKeys = actKeys.filter(k => k === targetAct);
                }

                if (actKeys.length === 0 && targetAct !== 'ALL') {
                    return; // Skip COs that don't have the selected activity enabled
                }

                html += `
                    <div class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between pb-1 border-b border-slate-800/60">
                            <h4 class="font-bold text-amber-400 text-xs flex items-center space-x-2">
                                <span>🎯 ${co} Self-Learning Activities</span>
                                <span class="text-[10px] text-slate-400 font-normal">(${actKeys.length} ${targetAct !== 'ALL' ? 'Filtered' : 'Active'})</span>
                            </h4>
                            <span id="co-sum-${co}" class="text-[10px] font-bold text-emerald-400 bg-slate-950 px-2 py-0.5 rounded border border-slate-800">
                                Avg: 0.0 / 15.0
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                `;

                actKeys.forEach(actKey => {
                    const label = activityLabels[actKey] || actKey.toUpperCase();
                    const currentVal = slSplitupState[regNo][co] ? (slSplitupState[regNo][co][actKey] || 0) : 0;

                    html += `
                        <div class="p-2.5 rounded-lg bg-slate-950/80 border border-slate-800/80 hover:border-slate-700 transition-all flex-1 min-w-[160px] max-w-[210px]">
                            <div class="flex items-center justify-between gap-1 mb-2">
                                <span class="font-bold text-slate-300 text-[10px] uppercase tracking-wide truncate">${label}</span>
                                <span id="badge-${co}-${actKey}" class="px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-300 font-mono text-[10px] font-bold border border-amber-500/20 flex-shrink-0">
                                    ${parseFloat(currentVal).toFixed(1)}/15
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', -0.5)" class="w-8 h-10 rounded-lg bg-slate-800 hover:bg-rose-500/20 border border-slate-700 hover:border-rose-500/40 font-bold text-slate-200 text-base flex items-center justify-center flex-shrink-0 cursor-pointer shadow-sm transition-all select-none">−</button>
                                <input type="number" id="input-${co}-${actKey}" min="0" max="15" step="0.5" value="${currentVal}" oninput="syncSlInput('${regNo}', '${co}', '${actKey}', this.value)" class="flex-1 h-10 bg-slate-900 border border-slate-700 rounded-lg text-center font-black text-emerald-300 text-xl outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-500/30 no-spinners">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', 0.5)" class="w-8 h-10 rounded-lg bg-slate-800 hover:bg-emerald-500/20 border border-slate-700 hover:border-emerald-500/40 font-bold text-slate-200 text-base flex items-center justify-center flex-shrink-0 cursor-pointer shadow-sm transition-all select-none">+</button>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            if (html === '') {
                html = `
                    <div class="p-6 text-center bg-slate-900/60 rounded-xl border border-slate-800 space-y-2">
                        <div class="text-slate-400 text-xl">🔍</div>
                        <p class="text-slate-400 text-xs font-semibold">No activities match the selected CO / Activity filter.</p>
                    </div>
                `;
            }

            container.innerHTML = html;
            calculateSlLiveTotal(regNo);
        }

        let _slDirty = false;
        const _slAutoSaveTimers = {};

        function autoSaveSlMarks(regNo) {
            _slDirty = true;
            clearTimeout(_slAutoSaveTimers[regNo]);
            _slAutoSaveTimers[regNo] = setTimeout(() => {
                const payload = {
                    marks_data: [{
                        reg_no: regNo,
                        co_details: slSplitupState[regNo] || {}
                    }]
                };
                fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/marks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        const toast = Swal.mixin({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 1400, timerProgressBar: true });
                        toast.fire({ icon: 'success', title: 'Marks Auto-saved ✓' });
                    }
                })
                .catch(() => {});
            }, 750);
        }

        function syncSlSlider(regNo, co, actKey, val) {
            syncSlInput(regNo, co, actKey, val);
        }

        function syncSlInput(regNo, co, actKey, val) {
            const num = Math.max(0, Math.min(15, parseFloat(val) || 0));
            if (!slSplitupState[regNo]) slSplitupState[regNo] = {};
            if (!slSplitupState[regNo][co]) slSplitupState[regNo][co] = {};
            slSplitupState[regNo][co][actKey] = num;

            const badge = document.getElementById(`badge-${co}-${actKey}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / 15.0`;

            const input = document.getElementById(`input-${co}-${actKey}`);
            if (input && parseFloat(input.value) !== num) input.value = num;

            calculateSlLiveTotal(regNo);

            // Update ledger row for this CO live
            const coData = slSplitupState[regNo][co] || {};
            let coSum = 0, coCnt = 0;
            Object.values(coData).forEach(v => { coSum += parseFloat(v) || 0; coCnt++; });
            const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
            const ledgerInput = document.getElementById(`sl-ledger-input-${regNo}-${co}`);
            if (ledgerInput && parseFloat(ledgerInput.value) !== parseFloat(coAvg.toFixed(1))) {
                ledgerInput.value = coAvg.toFixed(1);
            }

            autoSaveSlMarks(regNo);
        }

        function stepSlSlider(regNo, co, actKey, delta) {
            const current = slSplitupState[regNo]?.[co]?.[actKey] || 0;
            const next = Math.max(0, Math.min(15, current + delta));
            syncSlInput(regNo, co, actKey, next);
        }

        function onLedgerCoInput(regNo, co, val) {
            const num = Math.max(0, Math.min(15, parseFloat(val) || 0));
            if (!slSplitupState[regNo]) slSplitupState[regNo] = {};
            if (!slSplitupState[regNo][co]) slSplitupState[regNo][co] = {};

            const activeActs = slConfigs[co] || { assignment: true, mcq: true };
            let actKeys = Object.keys(activeActs).filter(k => activeActs[k]);
            if (actKeys.length === 0) actKeys = ['assignment', 'mcq'];

            actKeys.forEach(k => {
                slSplitupState[regNo][co][k] = num;
            });

            // If this student is currently loaded in top card, update top card UI
            const sel = document.getElementById('sl-student-select');
            if (sel && sel.value === regNo) {
                actKeys.forEach(k => {
                    const badge = document.getElementById(`badge-${co}-${k}`);
                    if (badge) badge.innerText = `${num.toFixed(1)} / 15.0`;
                    const inp = document.getElementById(`input-${co}-${k}`);
                    if (inp && parseFloat(inp.value) !== num) inp.value = num;
                });
                const coSumSpan = document.getElementById(`co-sum-${co}`);
                if (coSumSpan) coSumSpan.innerText = `Avg: ${num.toFixed(2)} / 15.0`;
            }

            // Recalculate row totals
            const stData = slSplitupState[regNo] || {};
            let stTotal = 0, stCount = 0;
            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(c => {
                const cData = stData[c] || {};
                let cSum = 0, cCnt = 0;
                Object.values(cData).forEach(v => { cSum += parseFloat(v) || 0; cCnt++; });
                stTotal += cSum;
                stCount += cCnt;
            });
            const stAvg = stCount > 0 ? (stTotal / stCount) : 0;
            const stCia = Math.min(5.0, (stAvg / 15.0) * 5.0);

            const rawEl = document.getElementById(`sl-ledger-raw-${regNo}`);
            if (rawEl) rawEl.innerText = `${stAvg.toFixed(2)}`;
            const ciaEl = document.getElementById(`sl-ledger-cia-${regNo}`);
            if (ciaEl) ciaEl.innerText = `${stCia.toFixed(2)}`;

            if (sel && sel.value === regNo) {
                const topRaw = document.getElementById('sl-student-total-raw');
                const topCia = document.getElementById('sl-student-converted-cia');
                if (topRaw) topRaw.innerText = `${stAvg.toFixed(2)} / 15.00 M`;
                if (topCia) topCia.innerText = `${stCia.toFixed(2)} / 5.00 M`;
            }

            updateSlEvaluatedBadge();
            autoSaveSlMarks(regNo);
        }

        function calculateSlLiveTotal(regNo) {
            const data = slSplitupState[regNo] || {};
            let totalScore = 0;
            let totalCount = 0;

            ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                const coData = data[co] || {};
                let coSum = 0;
                let coCnt = 0;
                Object.values(coData).forEach(val => {
                    coSum += parseFloat(val) || 0;
                    coCnt++;
                });

                const coSumSpan = document.getElementById(`co-sum-${co}`);
                if (coSumSpan) {
                    const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
                    coSumSpan.innerText = `Avg: ${coAvg.toFixed(2)} / 15.0`;
                }

                totalScore += coSum;
                totalCount += coCnt;
            });

            const overallAvg = totalCount > 0 ? (totalScore / totalCount) : 0;
            const ciaConverted = Math.min(5.0, (overallAvg / 15.0) * 5.0);

            const rawElem = document.getElementById('sl-student-total-raw');
            const ciaElem = document.getElementById('sl-student-converted-cia');

            if (rawElem) rawElem.innerText = `${overallAvg.toFixed(2)} / 15.00 M`;
            if (ciaElem) ciaElem.innerText = `${ciaConverted.toFixed(2)} / 5.00 M`;

            const rawEl = document.getElementById(`sl-ledger-raw-${regNo}`);
            if (rawEl) rawEl.innerText = `${overallAvg.toFixed(2)}`;
            const ciaEl = document.getElementById(`sl-ledger-cia-${regNo}`);
            if (ciaEl) ciaEl.innerText = `${ciaConverted.toFixed(2)}`;

            highlightSlLedgerRow(regNo);
            updateSlEvaluatedBadge();
        }

        function highlightSlLedgerRow(regNo) {
            const tbody = document.getElementById('sl-confirmation-tbody');
            if (!tbody) return;
            tbody.querySelectorAll('tr').forEach(tr => {
                tr.classList.remove('bg-slate-800', 'border-l-2', 'border-blue-400', 'font-semibold');
                tr.classList.add('hover:bg-slate-900/50');
            });
            const activeRow = document.getElementById(`sl-ledger-row-${regNo}`);
            if (activeRow) {
                activeRow.classList.remove('hover:bg-slate-900/50');
                activeRow.classList.add('bg-slate-800', 'border-l-2', 'border-blue-400', 'font-semibold');
            }
        }

        function updateSlEvaluatedBadge() {
            const badge = document.getElementById('sl-evaluated-count-badge');
            if (!badge) return;
            const students = typeof studentsList !== 'undefined' && studentsList.length ? studentsList : [];
            let count = 0;
            students.forEach(st => {
                const data = slSplitupState[st.reg_no] || {};
                let hasVal = false;
                ['CO1', 'CO2', 'CO3', 'CO4'].forEach(c => {
                    const cd = data[c] || {};
                    Object.values(cd).forEach(v => {
                        if (parseFloat(v) > 0) hasVal = true;
                    });
                });
                if (hasVal) count++;
            });
            badge.innerText = `${count} Evaluated`;
        }

        function renderSlConfirmationTable(currentRegNo) {
            const tbody = document.getElementById('sl-confirmation-tbody');
            const badge = document.getElementById('sl-evaluated-count-badge');
            if (!tbody) return;

            let html = '';
            let evaluatedCount = 0;
            const students = typeof studentsList !== 'undefined' && studentsList.length ? studentsList : [];

            students.forEach(student => {
                const regNo = student.reg_no;
                const data = slSplitupState[regNo] || {};

                let totalScore = 0;
                let totalCount = 0;
                const coScores = {};

                ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                    const coData = data[co] || {};
                    let coSum = 0;
                    let coCnt = 0;
                    Object.values(coData).forEach(val => {
                        coSum += parseFloat(val) || 0;
                        coCnt++;
                    });
                    const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
                    coScores[co] = coAvg;
                    totalScore += coSum;
                    totalCount += coCnt;
                });

                const overallAvg = totalCount > 0 ? (totalScore / totalCount) : 0;
                const ciaConverted = Math.min(5.0, (overallAvg / 15.0) * 5.0);

                if (overallAvg > 0 || (slSplitupState[regNo] && Object.keys(slSplitupState[regNo]).length > 0)) {
                    evaluatedCount++;
                }

                const isCurrent = (regNo === currentRegNo);
                const activeBg = isCurrent ? 'bg-slate-800 border-l-2 border-blue-400 font-semibold' : 'hover:bg-slate-900/50';

                html += `
                    <tr id="sl-ledger-row-${regNo}" class="${activeBg} transition-all">
                        <td class="p-1.5 text-center font-bold text-slate-400">${student.roll_no}</td>
                        <td class="p-1.5 font-mono text-[10px] font-bold text-emerald-400/90">${student.sbte_reg_no || student.reg_no}</td>
                        <td class="p-1.5 font-medium text-slate-200">
                            <div class="text-[11px] font-bold text-white truncate max-w-[130px]" title="${student.name}">${student.name}</div>
                        </td>
                        <td class="p-1 text-center">
                            <input type="number" step="0.5" min="0" max="15" id="sl-ledger-input-${regNo}-CO1" value="${coScores['CO1'].toFixed(1)}" oninput="onLedgerCoInput('${regNo}', 'CO1', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-bold text-amber-300 text-xs outline-none transition-all">
                        </td>
                        <td class="p-1 text-center">
                            <input type="number" step="0.5" min="0" max="15" id="sl-ledger-input-${regNo}-CO2" value="${coScores['CO2'].toFixed(1)}" oninput="onLedgerCoInput('${regNo}', 'CO2', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-bold text-amber-300 text-xs outline-none transition-all">
                        </td>
                        <td class="p-1 text-center">
                            <input type="number" step="0.5" min="0" max="15" id="sl-ledger-input-${regNo}-CO3" value="${coScores['CO3'].toFixed(1)}" oninput="onLedgerCoInput('${regNo}', 'CO3', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-bold text-amber-300 text-xs outline-none transition-all">
                        </td>
                        <td class="p-1 text-center">
                            <input type="number" step="0.5" min="0" max="15" id="sl-ledger-input-${regNo}-CO4" value="${coScores['CO4'].toFixed(1)}" oninput="onLedgerCoInput('${regNo}', 'CO4', this.value)" class="w-14 bg-slate-950 border border-slate-700 hover:border-amber-400 focus:border-amber-400 rounded px-1 py-0.5 text-center font-mono font-bold text-amber-300 text-xs outline-none transition-all">
                        </td>
                        <td class="p-1 text-center font-mono font-bold text-sky-400 text-xs">
                            <span id="sl-ledger-raw-${regNo}">${overallAvg.toFixed(2)}</span>
                        </td>
                        <td class="p-1 text-center font-mono font-black text-emerald-400 text-xs">
                            <span id="sl-ledger-cia-${regNo}" class="px-1.5 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20">${ciaConverted.toFixed(2)}</span>
                        </td>
                        <td class="p-1 text-center">
                            <button type="button" onclick="jumpToSlStudent('${regNo}')" title="Edit Activities in Card" class="px-2 py-0.5 rounded bg-blue-600/30 hover:bg-blue-600/60 text-sky-300 border border-blue-500/30 text-[10px] font-bold transition-all cursor-pointer">
                                ✏️
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            if (badge) badge.innerText = `${evaluatedCount} Evaluated`;
        }

        function jumpToSlStudent(regNo) {
            const sel = document.getElementById('sl-student-select');
            if (sel) {
                sel.value = regNo;
                loadSlStudent(regNo);
                const topCard = document.getElementById('sl-sliders-container');
                if (topCard) topCard.scrollTop = 0;
            }
        }

        function printSlModalLedger() {
            const students = typeof studentsList !== 'undefined' && studentsList.length ? studentsList : [];
            let printHtml = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>CA1 Self-Learning Evaluation Confirmation Ledger</title>
                    <style>
                        @page { size: A4 portrait; margin: 12mm 10mm; }
                        @media print {
                            @page { size: A4 portrait; margin: 12mm 10mm; }
                            body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        }
                        body { font-family: Arial, sans-serif; margin: 15px; color: #000; font-size: 10px; }
                        h2, h4 { margin: 2px 0; text-align: center; }
                        .meta { text-align: center; font-size: 10px; margin-bottom: 12px; color: #444; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; font-size: 9.5px; }
                        th { background-color: #f2f2f2; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9px; }
                        .text-center { text-align: center; }
                        .font-mono { font-family: ui-monospace, monospace; }
                        .signatures { margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid; }
                        .sig-box { text-align: center; width: 200px; border-top: 1px solid #000; padding-top: 5px; font-weight: bold; font-size: 9.5px; }
                        tr { page-break-inside: avoid; }
                    </style>
                </head>
                <body>
                    <h2>{{ $batchSubject->subject_code }} - {{ $batchSubject->subject_name }}</h2>
                    <h4>Continuous Assessment (CA1) Self-Learning Evaluation Confirmation Ledger</h4>
                    <div class="meta">Academic Semester: {{ $batchSubject->semester ?? 'R2026' }} | Date: ${new Date().toLocaleDateString('en-GB')}</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 32px;">Roll</th>
                                <th style="width: 95px;">SBTE Reg No</th>
                                <th>Student Name</th>
                                <th style="width: 48px;">CO1 (15M)</th>
                                <th style="width: 48px;">CO2 (15M)</th>
                                <th style="width: 48px;">CO3 (15M)</th>
                                <th style="width: 48px;">CO4 (15M)</th>
                                <th style="width: 75px;">Raw Score (15M)</th>
                                <th style="width: 75px;">CA1 CIA (5M)</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            students.forEach(student => {
                const regNo = student.reg_no;
                const data = slSplitupState[regNo] || {};

                let totalScore = 0;
                let totalCount = 0;
                const coScores = {};

                ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
                    const coData = data[co] || {};
                    let coSum = 0;
                    let coCnt = 0;
                    Object.values(coData).forEach(val => {
                        coSum += parseFloat(val) || 0;
                        coCnt++;
                    });
                    const coAvg = coCnt > 0 ? (coSum / coCnt) : 0;
                    coScores[co] = coAvg;
                    totalScore += coSum;
                    totalCount += coCnt;
                });

                const overallAvg = totalCount > 0 ? (totalScore / totalCount) : 0;
                const ciaConverted = Math.min(5.0, (overallAvg / 15.0) * 5.0);

                printHtml += `
                    <tr>
                        <td class="text-center font-bold">${student.roll_no}</td>
                        <td class="font-mono text-center">${student.sbte_reg_no || student.reg_no}</td>
                        <td><strong>${student.name}</strong></td>
                        <td class="text-center font-mono">${coScores['CO1'].toFixed(1)}</td>
                        <td class="text-center font-mono">${coScores['CO2'].toFixed(1)}</td>
                        <td class="text-center font-mono">${coScores['CO3'].toFixed(1)}</td>
                        <td class="text-center font-mono">${coScores['CO4'].toFixed(1)}</td>
                        <td class="text-center font-mono font-bold">${overallAvg.toFixed(2)}</td>
                        <td class="text-center font-mono" style="font-weight: bold; background-color: #f9f9f9;">${ciaConverted.toFixed(2)}</td>
                    </tr>
                `;
            });

            printHtml += `
                        </tbody>
                    </table>
                    <div class="signatures">
                        <div class="sig-box">Course Instructor Signature</div>
                        <div class="sig-box">HOD Signature</div>
                    </div>
                    <script>window.onload = function() { window.print(); window.close(); }<\/script>
                </body>
                </html>
            `;

            const printWin = window.open('', '_blank', 'width=900,height=650');
            printWin.document.write(printHtml);
            printWin.document.close();
        }

        function prevSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex <= 0) return;
            sel.selectedIndex--;
            loadSlStudent(sel.value);
        }

        function nextSlStudent() {
            const sel = document.getElementById('sl-student-select');
            if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
            sel.selectedIndex++;
            loadSlStudent(sel.value);
        }

        function saveAndNextSlStudent() {
            nextSlStudent();
        }

        function saveAllSlMarks() {
            const marksData = [];
            Object.keys(slSplitupState).forEach(regNo => {
                marksData.push({
                    reg_no: regNo,
                    co_details: slSplitupState[regNo]
                });
            });

            Swal.fire({
                title: 'Saving All Student Marks...',
                text: 'Updating activity-wise splitup for CA1',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/self-learning/marks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeSlMarksModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        }

        function initPracticumDatePickers(container) {
            const root = container || document;
            if (typeof flatpickr !== 'undefined') {
                root.querySelectorAll('.lp-date-picker, input.date-picker').forEach(el => {
                    if (el._flatpickr) el._flatpickr.destroy();
                    flatpickr(el, {
                        dateFormat: "d/m/Y",
                        allowInput: true,
                        disableMobile: true,
                        onChange: function(selectedDates, dateStr, instance) {
                            el.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }
        }

        function addCustomLessonPlanRow(tbodyId, defaultMode) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            const newId = 'new_' + Date.now();
            const rows = tbody.querySelectorAll('tr');
            let nextNum = rows.length + 1;

            if (rows.length > 0) {
                const lastTr = rows[rows.length - 1];
                const firstTdText = lastTr.querySelector('td')?.innerText || '';
                const matches = firstTdText.match(/\d+/g);
                if (matches && matches.length > 0) {
                    nextNum = parseInt(matches[matches.length - 1], 10) + 1;
                }
            }

            const label = defaultMode === 'L' ? `${nextNum}` : `Session ${nextNum}`;

            const tr = document.createElement('tr');
            tr.id = `lp-row-${newId}`;
            tr.setAttribute('data-plan-id', newId);
            tr.className = 'hover:bg-slate-800/30 transition-all bg-slate-900/50';

            tr.innerHTML = `
                <td class="p-2 font-normal text-center text-white text-xs">${label}</td>
                <td class="p-2">
                    <select id="lp-pedagogy-${newId}" onchange="onPedagogyChange('${newId}', this.value)" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-normal text-xs w-full text-blue-400">
                        <option value="Lecture (L)" ${defaultMode === 'L' ? 'selected' : ''}>Lecture (L)</option>
                        <option value="Practical Lab (P)" ${defaultMode === 'P' ? 'selected' : ''}>Practical Lab (P)</option>
                        <option value="Theory Series Exam (ST)">Theory Series Exam (ST)</option>
                        <option value="Practical Series Exam (SP)">Practical Series Exam (SP)</option>
                        <option value="PPT Presentation">PPT Presentation</option>
                        <option value="Demonstration">Demonstration</option>
                        <option value="Group Activity">Group Activity</option>
                    </select>
                </td>
                <td class="p-2">
                    <input type="text" id="lp-prop-${newId}" value="" placeholder="dd/mm/yyyy" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-slate-200 text-xs w-full text-center font-mono focus:border-blue-500 outline-none">
                </td>
                <td class="p-2">
                    <input type="text" id="lp-act-${newId}" value="" placeholder="dd/mm/yyyy" class="lp-date-picker bg-slate-900 border border-slate-700 rounded px-1.5 py-1 text-emerald-400 text-xs w-full text-center font-mono focus:border-emerald-500 outline-none">
                </td>
                <td class="p-2">
                    <textarea id="lp-topic-${newId}" rows="2" placeholder="Enter custom lesson topic description..." class="bg-slate-900 border border-slate-700 rounded p-1.5 text-slate-100 text-xs font-normal w-full focus:border-blue-500 outline-none resize-y leading-snug"></textarea>
                </td>
                <td class="p-2 text-center">
                    <select id="lp-co-${newId}" class="bg-slate-900 border border-amber-500/40 rounded px-1 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                        <option value="CO1" selected style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO1</option>
                        <option value="CO2" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO2</option>
                        <option value="CO3" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO3</option>
                        <option value="CO4" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO4</option>
                        <option value="CO5" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO5</option>
                        <option value="CO6" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO6</option>
                    </select>
                </td>
                <td id="lp-batch-td-${newId}" class="p-2 text-center">
                    <select id="lp-batch-${newId}" class="bg-slate-900 border border-slate-700 rounded px-1 py-1 font-mono text-xs text-emerald-400 w-full text-center">
                        <option value="ALL" ${defaultMode === 'L' ? 'selected' : ''}>ALL</option>
                        <option value="A & B" ${defaultMode === 'P' ? 'selected' : ''}>A & B</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                    </select>
                </td>
                <td id="lp-hours-td-${newId}" class="p-2 text-center font-normal">
                    <span class="px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-normal">${defaultMode === 'L' ? '1 Hr' : '3 Hrs'}</span>
                </td>
                <td class="p-2">
                    <input type="text" id="lp-remarks-${newId}" value="" placeholder="Status/Remarks" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-400 text-xs w-full">
                </td>
                <td class="p-1 text-center">
                    <button type="button" onclick="document.getElementById('lp-row-${newId}').remove()" title="Remove Row" class="w-6 h-6 flex items-center justify-center rounded bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/20 text-rose-400 hover:text-rose-300 transition-all text-xs font-bold mx-auto">&times;</button>
                </td>
            `;

            tbody.appendChild(tr);
            initPracticumDatePickers(tr);

            // Automatically shift vertical scroll to max down to show the new row
            const scrollContainer = tbody.closest('.max-h-\\[650px\\], .overflow-y-auto, div') || tbody.parentElement;
            if (scrollContainer) {
                scrollContainer.scrollTop = scrollContainer.scrollHeight;
            }
            setTimeout(() => {
                tr.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 50);
        }

        function saveAllLessonPlans() {
            const rows = document.querySelectorAll('tr[id^="lp-row-"]');
            const plans = [];

            rows.forEach(tr => {
                const planId = tr.getAttribute('data-plan-id');
                if (!planId) return;

                const blockIdsAttr = tr.getAttribute('data-block-ids');
                const targetIds = blockIdsAttr ? blockIdsAttr.split(',') : [planId];

                const pedagogy = document.getElementById('lp-pedagogy-' + planId)?.value || 'Lecture (L)';
                const propDate = document.getElementById('lp-prop-' + planId)?.value || '';
                const actDate = document.getElementById('lp-act-' + planId)?.value || '';
                const topic = (document.getElementById('lp-topic-' + planId)?.value || '').trim();
                const coId = document.getElementById('lp-co-' + planId)?.value || 'CO1';
                const batch = document.getElementById('lp-batch-' + planId)?.value || '';
                const remarks = document.getElementById('lp-remarks-' + planId)?.value || '';

                // If new row with no text entered, never save or calculate that row
                if (planId.startsWith('new_') && !topic) {
                    return;
                }

                targetIds.forEach(id => {
                    plans.push({
                        id: id,
                        pedagogy: pedagogy,
                        proposed_date: propDate,
                        actual_date: actDate,
                        topic_content: topic,
                        co_id: coId,
                        sub_batch: batch,
                        remarks: remarks
                    });
                });
            });

            Swal.fire({
                title: 'Saving All 90 Hours...',
                text: 'Updating complete Practicum lesson plan',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/lesson-plan/save-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ plans: plans })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Saved Successfully!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
            });
        }

        // ── Auto-save (debounced 900ms per row) ──────────────────────────────
        const _lpAutoSaveTimers = {};
        function lpAutoSave(planId) {
            if (String(planId).startsWith('new_')) return; // skip unsaved new rows
            clearTimeout(_lpAutoSaveTimers[planId]);
            _lpAutoSaveTimers[planId] = setTimeout(() => {
                const tr = document.getElementById('lp-row-' + planId);
                if (!tr) return;
                const blockIdsAttr = tr.getAttribute('data-block-ids');
                const targetIds = blockIdsAttr ? blockIdsAttr.split(',') : [planId];

                const payload = {
                    plans: targetIds.map(id => ({
                        id: id,
                        pedagogy:       document.getElementById('lp-pedagogy-' + planId)?.value || 'Lecture (L)',
                        proposed_date:  document.getElementById('lp-prop-'     + planId)?.value || '',
                        actual_date:    document.getElementById('lp-act-'      + planId)?.value || '',
                        topic_content:  (document.getElementById('lp-topic-'   + planId)?.value || '').trim(),
                        co_id:          document.getElementById('lp-co-'       + planId)?.value || 'CO1',
                        sub_batch:      document.getElementById('lp-batch-'    + planId)?.value || '',
                        remarks:        document.getElementById('lp-remarks-'  + planId)?.value || '',
                    }))
                };

                fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/lesson-plan/save-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        // subtle toast — no modal
                        const toast = Swal.mixin({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 1800, timerProgressBar: true });
                        toast.fire({ icon: 'success', title: 'Auto-saved ✓' });
                    }
                })
                .catch(() => {}); // silent on network error during auto-save
            }, 900);
        }

        // ── Delete row with SweetAlert confirmation ───────────────────────────
        function confirmDeleteLessonPlanRow(planId) {
            Swal.fire({
                title: 'Delete this row?',
                text: 'This lesson plan entry will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#e2e8f0',
            }).then(result => {
                if (!result.isConfirmed) return;

                const tr = document.getElementById('lp-row-' + planId);
                const blockIdsAttr = tr?.getAttribute('data-block-ids');
                const idsToDelete = blockIdsAttr ? blockIdsAttr.split(',') : [planId];

                fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/lesson-plan/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: idsToDelete })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS' || data.status === 'OK') {
                        tr?.remove();
                        Swal.fire({ toast: true, position: 'bottom-end', icon: 'success', title: 'Row deleted', showConfirmButton: false, timer: 1600, background: '#0f172a', color: '#e2e8f0' });
                    } else {
                        Swal.fire('Error', data.message || 'Could not delete.', 'error');
                    }
                })
                .catch(err => Swal.fire('Error', err.message, 'error'));
            });
        }

        function onPedagogyChange(planId, val) {
            const batchTd = document.getElementById('lp-batch-td-' + planId);
            const hoursTd = document.getElementById('lp-hours-td-' + planId);
            const select = document.getElementById('lp-pedagogy-' + planId);
            if (!batchTd) return;

            const isLab = val.includes('Practical') || val.includes('Lab') || val.includes('(P)') || val.includes('(SP)');

            if (select) {
                select.className = "bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs w-full " + 
                    (isLab ? "text-emerald-400" : (val.includes('Series') ? "text-sky-400" : "text-blue-400"));
            }

            if (hoursTd) {
                if (isLab) {
                    hoursTd.innerHTML = `<span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">3 Hours</span>`;
                } else {
                    hoursTd.innerHTML = `<span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-bold">1 Hour</span>`;
                }
            }

            if (isLab) {
                batchTd.innerHTML = `
                    <select id="lp-batch-${planId}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs text-emerald-400 w-full">
                        <option value="Batch A & B" selected>Batch A & B (Combined)</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                `;
            } else {
                batchTd.innerHTML = `
                    <span class="px-2.5 py-1 rounded bg-slate-900/80 text-slate-400 font-semibold text-xs border border-slate-800 inline-block">
                        All Students
                    </span>
                    <input type="hidden" id="lp-batch-${planId}" value="All Students">
                `;
            }
        }

    // =====================================================================
    // Series QP Generator — Preview / Edit Modal System
    // =====================================================================

    const SUBJECT_ID = {{ $batchSubject->id }};
    const QP_PATTERN = '{{ ($subjectType['pattern'] ?? 'table_4_1_standard') }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    let _currentSeries = '', _currentCo = '', _currentPattern = QP_PATTERN, _draftQp = {};
 
    async function openQpPreviewModal(seriesNo, coTag, mode) {
        _currentSeries = seriesNo;
        _currentCo     = coTag;
        _activeQpTab   = 'qp';
        switchQpEditorTab('qp');
        const statusEl = document.getElementById('qp-gen-status');
        statusEl.classList.remove('hidden');
        statusEl.style.color = '#94a3b8';
 
        const isPractical = seriesNo.indexOf('Practical') !== -1;
        _currentPattern = isPractical ? 'practical_series' : QP_PATTERN;
 
        const modal = document.getElementById('qp-preview-modal');
        modal.classList.remove('hidden');
        document.getElementById('qp-modal-title').textContent = `Series Exam QP — ${seriesNo} (${coTag}) | ${_currentPattern === 'practical_series' ? 'Practical Rubrics (Table 3.1)' : (_currentPattern === 'table_4_2_design' ? 'Table 4.2 Design' : 'Table 4.1 Standard')}`;
 
        document.getElementById('qp-editor-body').innerHTML = '<div class="text-slate-400 text-sm p-8 text-center animate-pulse">⚡ Loading questions…</div>';
 
        if (mode === 'ai') {
            statusEl.innerHTML = `⚡ Fetching AI/Bank questions for <strong>${seriesNo}</strong>...`;
            try {
                const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/generate/${encodeURIComponent(seriesNo)}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    _draftQp = data.qp_data;
                    _currentPattern = data.pattern_type;
                    statusEl.innerHTML = `<span style="color:#4ade80">${data.message}</span>`;
                    renderQpEditor(_draftQp, _currentPattern);
                } else {
                    document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">${data.message}</div>`;
                }
            } catch(e) {
                document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">Network Error: ${e.message}</div>`;
            }
        } else {
            // Manual entry — blank template
            statusEl.innerHTML = `✏ Manual mode — fill in questions for <strong>${seriesNo}</strong>`;
            _draftQp = buildEmptyQpTemplate(_currentPattern, coTag);
            renderQpEditor(_draftQp, _currentPattern);
        }
    }
 
    function buildEmptyQpTemplate(pattern, coTag) {
        if (pattern === 'practical_series') {
            return {
                part_a: [
                    {q_no:'1', text:'Perform identification, testing, and troubleshooting of electronic components.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected components list, test procedure and values.'},
                    {q_no:'2', text:'Construct and test the given resistor/diode circuit on breadboard and verify output.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected schematic connections and measured readings.'}
                ]
            };
        } else if (pattern === 'table_4_2_design') {
            return {
                part_a: Array.from({length:6}, (_,i) => ({q_no:String(i+1), text:'', marks:5, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''})),
                part_b: [
                    {q_no:'7(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'7(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'8(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                    {q_no:'8(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                ]
            };
        } else {
            // Single CO Test: 2×1M + 3×3M + 3×7M (answer any 2) = 25M
            return {
                part_a: [
                    {q_no:'1', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                    {q_no:'2', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                ],
                part_b: [
                    {q_no:'3', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'4', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'5', text:'', marks:3, co:coTag, bloom:'Apply', scheme_key:'', answer_key:''},
                ],
                part_c: [
                    {q_no:'6', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'7', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'8', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                ]
            };
        }
    }
 
    let _activeQpTab = 'qp';
    function switchQpEditorTab(tab) {
        _activeQpTab = tab;
        ['qp', 'scheme', 'key'].forEach(t => {
            const el = document.getElementById('qp-editor-tab-' + t);
            if (el) {
                if (t === tab) el.classList.remove('hidden');
                else el.classList.add('hidden');
            }
            const btn = document.getElementById('qp-edit-btn-' + t);
            if (btn) {
                btn.className = t === tab
                    ? "px-4 py-2 text-xs font-bold rounded-lg bg-blue-600 text-white transition-all"
                    : "px-4 py-2 text-xs font-semibold rounded-lg bg-slate-800 hover:bg-slate-750 text-slate-300 transition-all";
            }
        });
    }
 
    function syncQpQuestionTexts(partKey, idx, val) {
        const schemeText = document.getElementById(`scheme-qtxt-${partKey}-${idx}`);
        const keyText = document.getElementById(`key-qtxt-${partKey}-${idx}`);
        if (schemeText) schemeText.innerText = val;
        if (keyText) keyText.innerText = val;
    }
 
    function syncQpQuestionMarks(partKey, idx, val) {
        const schemeMarks = document.getElementById(`scheme-qmarks-${partKey}-${idx}`);
        const keyMarks = document.getElementById(`key-qmarks-${partKey}-${idx}`);
        if (schemeMarks) schemeMarks.innerText = val + 'M';
        if (keyMarks) keyMarks.innerText = val + 'M';
    }
 
    function renderQpEditor(qpData, pattern) {
        const container = document.getElementById('qp-editor-body');
        const parts = pattern === 'practical_series'
            ? [['part_a', 'PART A — Practical Tasks (Answer any ONE task - 40 Marks)', '40']]
            : (pattern === 'table_4_2_design'
                ? [['part_a','PART A — Answer ALL (6 × 5M = 30M)','5'],['part_b','PART B — Answer ONE per Set (10M each)','10']]
                : [['part_a','PART A — Answer ALL (2 × 1M = 2M)','1'],['part_b','PART B — Answer ALL (3 × 3M = 9M)','3'],['part_c','PART C — Answer ANY 2 of 3 (7M each = 14M)','7']]);
 
        let htmlQp = '';
        let htmlScheme = '';
        let htmlKey = '';

        for (const [partKey, partLabel, defaultMark] of parts) {
            const rows = qpData[partKey] || [];
            
            // 1. Question Paper Tab
            htmlQp += `<div class="mb-4">
                <div class="flex items-center justify-between bg-slate-800 px-4 py-2 rounded-t-xl border-t border-x border-slate-700">
                    <span class="font-bold text-sky-300 text-sm">${partLabel}</span>
                    <button onclick="addQpRow('${partKey}','${defaultMark}')" class="text-xs px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all">+ Add Question</button>
                </div>
                <div class="border border-slate-700 rounded-b-xl overflow-hidden bg-slate-900/60">
                    <table class="w-full text-sm" id="tbl-qp-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2">Question Text</th>
                                <th class="p-2 w-28 text-center">Bloom (BT)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                                <th class="p-2 w-28 text-center">Choice Group</th>
                                <th class="p-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlQp += `<tr class="border-b border-slate-800 hover:bg-slate-800/40" data-part="${partKey}" data-idx="${idx}">
                    <td class="p-2"><input type="text" value="${q.q_no||''}" onchange="updateQpField('${partKey}',${idx},'q_no',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white font-mono text-center"></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'text',this.value); syncQpQuestionTexts('${partKey}',${idx},this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white resize-y" placeholder="Type question here…">${q.text||''}</textarea></td>
                    <td class="p-2"><select onchange="updateQpField('${partKey}',${idx},'bloom',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-white">
                        ${['Remember','Understand','Apply','Analyze','Evaluate','Create'].map(l=>`<option ${q.bloom===l?'selected':''}>${l}</option>`).join('')}
                    </select></td>
                    <td class="p-2"><input type="number" min="1" max="30" value="${q.marks||defaultMark}" onchange="updateQpField('${partKey}',${idx},'marks',parseInt(this.value)); syncQpQuestionMarks('${partKey}',${idx},parseInt(this.value))" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-amber-300 font-bold text-center"></td>
                    <td class="p-2"><input type="text" value="${q.choice_group||''}" placeholder="e.g. Set A" onchange="updateQpField('${partKey}',${idx},'choice_group',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-sky-300"></td>
                    <td class="p-2 text-center"><button onclick="removeQpRow('${partKey}',${idx})" class="text-red-400 hover:text-red-300 text-xs font-bold">✕</button></td>
                </tr>`;
            });
            htmlQp += `</tbody></table></div></div>`;

            // 2. Evaluation Scheme Tab
            htmlScheme += `<div class="mb-4">
                <div class="bg-slate-800 px-4 py-2 rounded-t-xl border-t border-x border-slate-700">
                    <span class="font-bold text-emerald-400 text-sm">${partLabel} — Scheme</span>
                </div>
                <div class="border border-slate-700 rounded-b-xl overflow-hidden bg-slate-900/60">
                    <table class="w-full text-sm" id="tbl-scheme-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Evaluation Scheme (Key Points / Mark Split)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlScheme += `<tr class="border-b border-slate-800 hover:bg-slate-800/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-300 bg-slate-950/20 max-w-xs truncate" id="scheme-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'scheme_key',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-emerald-300 resize-y" placeholder="Marking scheme guidelines…">${q.scheme_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="scheme-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlScheme += `</tbody></table></div></div>`;

            // 3. Answer Key Tab
            htmlKey += `<div class="mb-4">
                <div class="bg-slate-800 px-4 py-2 rounded-t-xl border-t border-x border-slate-700">
                    <span class="font-bold text-blue-400 text-sm">${partLabel} — Answer Key</span>
                </div>
                <div class="border border-slate-700 rounded-b-xl overflow-hidden bg-slate-900/60">
                    <table class="w-full text-sm" id="tbl-key-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Model Answer / Key Details</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlKey += `<tr class="border-b border-slate-800 hover:bg-slate-800/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-300 bg-slate-950/20 max-w-xs truncate" id="key-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'answer_key',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-blue-300 resize-y" placeholder="Model answer text…">${q.answer_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="key-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlKey += `</tbody></table></div></div>`;
        }

        container.innerHTML = `
            <div id="qp-editor-tab-qp" class="${_activeQpTab === 'qp' ? '' : 'hidden'}">${htmlQp}</div>
            <div id="qp-editor-tab-scheme" class="${_activeQpTab === 'scheme' ? '' : 'hidden'}">${htmlScheme}</div>
            <div id="qp-editor-tab-key" class="${_activeQpTab === 'key' ? '' : 'hidden'}">${htmlKey}</div>
        `;
    }

    function updateQpField(part, idx, field, value) {
        if (!_draftQp[part]) return;
        _draftQp[part][idx][field] = value;
    }

    function addQpRow(partKey, defaultMark) {
        if (!_draftQp[partKey]) _draftQp[partKey] = [];
        const idx = _draftQp[partKey].length + 1;
        _draftQp[partKey].push({q_no: String(idx), text: '', marks: parseInt(defaultMark), co: _currentCo, bloom: 'Understand', scheme_key: '', answer_key: ''});
        renderQpEditor(_draftQp, _currentPattern);
    }
 
    function removeQpRow(partKey, idx) {
        if (!_draftQp[partKey]) return;
        _draftQp[partKey].splice(idx, 1);
        renderQpEditor(_draftQp, _currentPattern);
    }
 
    function closeQpModal() {
        document.getElementById('qp-preview-modal').classList.add('hidden');
    }
 
    async function saveQpFromModal() {
        const statusEl = document.getElementById('qp-gen-status');
        const saveBtn  = document.getElementById('qp-save-btn');
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving…';
 
        try {
            const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/save/${encodeURIComponent(_currentSeries)}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({
                    co_tag: _currentCo,
                    pattern_type: _currentPattern,
                    qp_data: _draftQp,
                    scheme_data: _draftQp,
                    answer_key: _draftQp,
                })
            });
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                statusEl.innerHTML = `✅ <strong>${_currentSeries}</strong> QP saved to Question Bank!`;
                statusEl.style.color = '#4ade80';
                statusEl.classList.remove('hidden');
                closeQpModal();
                setTimeout(() => location.reload(), 1200);
            } else {
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Save & Add to Question Bank';
                alert('Error: ' + data.message);
            }
        } catch(e) {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Save & Add to Question Bank';
            alert('Network error: ' + e.message);
        }
    }

    // =====================================================================
    // Theory ESE Grade Inline Entry & Auto-Save System
    // =====================================================================
    const _eseAutoSaveTimers = {};

    function autoSaveEseTheoryGrade(selectEl) {
        const regNo = selectEl.getAttribute('data-reg');
        const regKey = selectEl.getAttribute('data-regkey') || regNo.replace(/[^a-zA-Z0-9_]/g, '_');
        const grade = (selectEl.value || '').trim();
        const isAbsent = (grade === 'FE');

        // Update color of the select element itself
        const isPass = ['S','A','B','C','D','E','P'].includes(grade.toUpperCase());
        if (!grade) {
            selectEl.className = "w-full max-w-[220px] bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 font-mono text-xs font-bold text-slate-400 outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-500/30 cursor-pointer transition-all mx-auto block";
        } else if (isPass) {
            selectEl.className = "w-full max-w-[220px] bg-slate-950 border border-emerald-500/40 rounded-lg px-2.5 py-1.5 font-mono text-xs font-bold text-emerald-300 outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-500/30 cursor-pointer transition-all mx-auto block";
        } else {
            selectEl.className = "w-full max-w-[220px] bg-slate-950 border border-rose-500/40 rounded-lg px-2.5 py-1.5 font-mono text-xs font-bold text-rose-400 outline-none focus:border-rose-400 focus:ring-1 focus:ring-rose-500/30 cursor-pointer transition-all mx-auto block";
        }

        // Live update the status badge
        updateEseRowLive(regKey, grade);
        // Live update the top statistics
        updateEseStatsLive();

        // Debounce auto-save by 500ms
        clearTimeout(_eseAutoSaveTimers[regNo]);
        _eseAutoSaveTimers[regNo] = setTimeout(() => {
            const marksData = [{
                reg_no: regNo,
                ese_theory_grade: grade,
                theory_absent: isAbsent
            }];

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        background: '#0f172a',
                        color: '#e2e8f0'
                    });
                    toast.fire({ icon: 'success', title: 'Grade Saved ✓' });
                }
            })
            .catch(() => {});
        }, 500);
    }

    function updateEseRowLive(regKey, grade) {
        const badge = document.getElementById(`ese-status-badge-${regKey}`);
        if (!badge) return;

        const isPass = ['S','A','B','C','D','E','P'].includes(grade.toUpperCase());

        if (!grade) {
            badge.className = 'inline-block px-2.5 py-0.5 rounded font-mono text-xs font-bold border text-slate-500 bg-slate-900 border-slate-800';
            badge.innerText = '-';
        } else if (isPass) {
            badge.className = 'inline-block px-2.5 py-0.5 rounded font-mono text-xs font-bold border text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
            badge.innerText = 'PASSED';
        } else {
            badge.className = 'inline-block px-2.5 py-0.5 rounded font-mono text-xs font-bold border text-rose-400 bg-rose-500/10 border-rose-500/20';
            badge.innerText = 'REAPPEAR';
        }
    }

    function updateEseStatsLive() {
        const selects = document.querySelectorAll('select[id^="ese-grade-select-"]');
        let graded = 0, passed = 0, failed = 0;

        selects.forEach(s => {
            const val = (s.value || '').toUpperCase();
            if (val) {
                graded++;
                if (['S','A','B','C','D','E','P'].includes(val)) {
                    passed++;
                } else if (['F','FE','ABSENT','ABS'].includes(val)) {
                    failed++;
                }
            }
        });

        const gEl = document.getElementById('ese-graded-count');
        const pEl = document.getElementById('ese-passed-count');
        const fEl = document.getElementById('ese-failed-count');
        if (gEl) gEl.innerText = graded;
        if (pEl) pEl.innerText = passed;
        if (fEl) fEl.innerText = failed;
    }

    function saveAllEseTheoryGradesFromTable() {
        const selects = document.querySelectorAll('select[id^="ese-grade-select-"]');
        const marksData = [];

        selects.forEach(s => {
            const regNo = s.getAttribute('data-reg');
            const grade = (s.value || '').trim();
            marksData.push({
                reg_no: regNo,
                ese_theory_grade: grade,
                theory_absent: (grade === 'FE')
            });
        });

        Swal.fire({
            title: 'Saving All ESE Grades...',
            text: 'Updating board theory grades for all students',
            allowOutsideClick: false,
            background: '#0f172a',
            color: '#e2e8f0',
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message || 'All board theory ESE grades saved!',
                    timer: 1600,
                    showConfirmButton: false,
                    background: '#0f172a',
                    color: '#e2e8f0'
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to save', 'error');
            }
        })
        .catch(err => Swal.fire('Error', err.message, 'error'));
    }

    // =====================================================================
    // Theory Series Exam Marks Modal System
    // =====================================================================
    const seriesTheoryEvalsDb = @json($seriesTheoryEvals);
    const seriesTheoryEvalsState = {};

    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesTheoryEvalsState[regNo] = {
            'Series 1': { total_score_50: 0, is_absent: false },
            'Series 2': { total_score_50: 0, is_absent: false },
            'Series 3': { total_score_50: 0, is_absent: false },
            'Series 4': { total_score_50: 0, is_absent: false }
        };

        const dbList = seriesTheoryEvalsDb[regNo] || [];
        dbList.forEach(evalRecord => {
            const sNo = evalRecord.series_no;
            let mappedSeries = sNo;
            if (sNo === 'CO1') mappedSeries = 'Series 1';
            if (sNo === 'CO2') mappedSeries = 'Series 2';
            if (sNo === 'CO3') mappedSeries = 'Series 3';
            if (sNo === 'CO4') mappedSeries = 'Series 4';

            if (seriesTheoryEvalsState[regNo][mappedSeries]) {
                seriesTheoryEvalsState[regNo][mappedSeries] = {
                    total_score_50: parseFloat(evalRecord.total_score_50) || 0,
                    is_absent: !!evalRecord.is_absent
                };
            }
        });
    });

    function openSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function closeSeriesTheoryModal() {
        document.getElementById('series-theory-modal').classList.add('hidden');
    }

    function onSeriesTheoryTestChange(test) {
        const sel = document.getElementById('series-theory-student-select');
        if (sel && sel.value) {
            loadSeriesTheoryStudent(sel.value);
        }
    }

    function loadSeriesTheoryStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('series-theory-student-display').innerText = `${student.name} (${student.reg_no})`;

        const test = document.getElementById('series-theory-test-select').value;
        const state = seriesTheoryEvalsState[regNo][test] || { total_score_50: 0, is_absent: false };

        const totalInput = document.getElementById('series-theory-total');
        const absentCheck = document.getElementById('series-theory-absent');

        totalInput.value = state.total_score_50;
        absentCheck.checked = state.is_absent;

        totalInput.disabled = state.is_absent;

        updateSeriesTheoryLiveTotal();
    }

    function onSeriesTheoryMarksInput() {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;

        seriesTheoryEvalsState[regNo][test].total_score_50 = total;

        updateSeriesTheoryLiveTotal();
    }

    function toggleSeriesTheoryAbsent(isAbsent) {
        const sel = document.getElementById('series-theory-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        const test = document.getElementById('series-theory-test-select').value;
        seriesTheoryEvalsState[regNo][test].is_absent = isAbsent;

        const totalInput = document.getElementById('series-theory-total');

        if (isAbsent) {
            totalInput.value = 0;
            totalInput.disabled = true;
            seriesTheoryEvalsState[regNo][test].total_score_50 = 0;
        } else {
            totalInput.disabled = false;
        }
        updateSeriesTheoryLiveTotal();
    }

    function updateSeriesTheoryLiveTotal() {
        const total = parseFloat(document.getElementById('series-theory-total').value) || 0;
        const isAbsent = document.getElementById('series-theory-absent').checked;

        const displayTotal = isAbsent ? 0 : total;
        document.getElementById('series-theory-live-total').innerText = `${displayTotal.toFixed(2)} / 50.00`;
    }

    function prevSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesTheoryStudent(sel.value);
    }

    function nextSeriesTheoryStudent() {
        const sel = document.getElementById('series-theory-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesTheoryStudent(sel.value);
    }

    function saveAndNextSeriesTheoryStudent() {
        nextSeriesTheoryStudent();
    }

    function saveAllSeriesTheoryMarks() {
        const test = document.getElementById('series-theory-test-select').value;
        const marksData = [];

        Object.keys(seriesTheoryEvalsState).forEach(regNo => {
            const state = seriesTheoryEvalsState[regNo][test];
            marksData.push({
                reg_no: regNo,
                total_score_50: state.total_score_50,
                is_absent: state.is_absent
            });
        });

        Swal.fire({
            title: 'Saving Series Marks...',
            text: `Updating scores for ${test}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-theory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ series_no: test, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesTheoryModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }

    // ── Inline auto-save for Theory Series Marks table ────────────────────
    const _stAutoSaveTimers = {};

    function autoSaveSeriesTheory(inputEl) {
        const regNo   = inputEl.getAttribute('data-reg');
        const series  = inputEl.getAttribute('data-series');
        const val     = parseFloat(inputEl.value);
        const score   = isNaN(val) ? null : Math.max(0, Math.min(50, val));

        // Update live Avg & CIA display immediately
        updateSeriesTheoryRowLive(regNo);

        // Debounce the save
        const timerKey = regNo + '_' + series;
        clearTimeout(_stAutoSaveTimers[timerKey]);
        _stAutoSaveTimers[timerKey] = setTimeout(() => {
            const marksData = [{
                reg_no: regNo,
                total_score_50: score !== null ? score : 0,
                is_absent: score === null
            }];

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-theory', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ series_no: series, marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    const toast = Swal.mixin({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 1600, timerProgressBar: true });
                    toast.fire({ icon: 'success', title: 'Saved ✓' });
                }
            })
            .catch(() => {});
        }, 800);
    }

    function updateSeriesTheoryRowLive(regNo) {
        const regKey = regNo.replace(/[^a-zA-Z0-9_]/g, '_');
        const keys = ['s1', 's2'];
        let sum = 0, count = 0;

        keys.forEach(k => {
            const inp = document.getElementById(`st-${regKey}-${k}`);
            if (inp) {
                const v = parseFloat(inp.value);
                if (!isNaN(v)) { sum += v; count++; }
            }
        });

        const avg   = count > 0 ? (sum / count) : 0;
        const cia   = Math.min(10, (avg / 50) * 10);
        const avgEl = document.getElementById(`st-avg-${regKey}`);
        const ciaEl = document.getElementById(`st-cia-${regKey}`);

        if (avgEl) avgEl.innerText = count > 0 ? avg.toFixed(1) : '—';
        if (ciaEl) ciaEl.innerText = count > 0 ? `${cia.toFixed(2)}/10` : '—';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const urlMode = urlParams.get('mode');
        const urlTab = urlParams.get('tab');

        if (urlMode) {
            switchMode(urlMode);
            if (urlTab) {
                if (urlMode === 'theory') switchTheorySubtab(urlTab);
                else if (urlMode === 'lab') switchLabSubtab(urlTab);
            }
        } else {
            const savedMode = localStorage.getItem('active_mode');
            const savedTheoryTab = localStorage.getItem('active_theory_subtab');
            const savedLabTab = localStorage.getItem('active_lab_subtab');

            if (savedMode) {
                switchMode(savedMode);
            }
            if (savedTheoryTab) {
                switchTheorySubtab(savedTheoryTab);
            }
            if (savedLabTab) {
                switchLabSubtab(savedLabTab);
            }
        }

        if (typeof updateExpRosterSummary === 'function') {
            updateExpRosterSummary();
        }
        if (typeof initPracticumDatePickers === 'function') {
            initPracticumDatePickers();
        }
    });
    </script>

    <!-- ================================================================
         QP Preview / Edit Modal (Unified Columns Layout)
    ================================================================= -->
    <div id="qp-preview-modal" class="hidden fixed inset-0 z-50 bg-black/80 flex items-start justify-center p-4 overflow-auto">
        <div class="w-full max-w-[98%] bg-slate-900 rounded-2xl shadow-2xl border border-slate-700 flex flex-col" style="max-height:95vh">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-700 bg-slate-800 rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-white" id="qp-modal-title">Series QP Preview</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Edit questions, marking schemes, and model answers side-by-side — then Save to Question Bank</p>
                </div>
                <button onclick="closeQpModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Tab Switcher Bar -->
            <div class="flex border-b border-slate-800 bg-slate-900/90 px-6 py-2 gap-2 flex-shrink-0">
                <button type="button" onclick="switchQpEditorTab('qp')" id="qp-edit-btn-qp" class="px-4 py-2 text-xs font-bold rounded-lg bg-blue-600 text-white transition-all">📝 1. Edit Questions (QP)</button>
                <button type="button" onclick="switchQpEditorTab('scheme')" id="qp-edit-btn-scheme" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-800 hover:bg-slate-750 text-slate-300 transition-all">📋 2. Edit Evaluation Scheme</button>
                <button type="button" onclick="switchQpEditorTab('key')" id="qp-edit-btn-key" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-800 hover:bg-slate-750 text-slate-300 transition-all">🔑 3. Edit Model Answer Key</button>
            </div>

            <!-- Editor Body -->
            <div id="qp-editor-body" class="flex-1 overflow-y-auto p-6 space-y-2">
                <div class="text-slate-500 text-sm text-center py-12">Loading…</div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-700 bg-slate-800 rounded-b-2xl">
                <button onclick="closeQpModal()" class="px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-semibold text-sm">Cancel</button>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500 text-xs">Questions, schemes, and model answers are saved together in one step</span>
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg transition-all">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- ================================================================
         Enter Theory Series Marks Modal
    ================================================================= -->
    <div id="series-theory-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-2xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Theory Series Exam Marks Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select a series test and enter Part A, B, and C scores for each student.</p>
                </div>
                <button onclick="closeSeriesTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Series Selection & Student Selection Bar -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 space-y-3 flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-slate-300 text-xs font-semibold">Select Series Test:</label>
                    <select id="series-theory-test-select" onchange="onSeriesTheoryTestChange(this.value)" class="bg-slate-950 border border-slate-700 rounded px-2.5 py-1 text-xs text-amber-400 font-bold outline-none focus:border-amber-500">
                        <option value="Series 1">Series Exam 1 (CA4: Modules 1 &amp; 2)</option>
                        <option value="Series 2">Series Exam 2 (CA5: Modules 3 &amp; 4)</option>
                    </select>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>◀ Prev</span>
                    </button>

                    <div class="flex-1">
                        <select id="series-theory-student-select" onchange="loadSeriesTheoryStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" onclick="nextSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Marks Form Card -->
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Student:</span>
                    <span id="series-theory-student-display" class="font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-800/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Total Series Test Mark (Max 50):</label>
                        <input type="number" id="series-theory-total" min="0" max="50" step="0.5" oninput="onSeriesTheoryMarksInput()" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-1.5 font-bold text-sm text-white text-center focus:border-emerald-500 outline-none">
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="series-theory-absent" onchange="toggleSeriesTheoryAbsent(this.checked)" class="rounded bg-slate-800 border-slate-700 text-rose-500 focus:ring-0">
                        <label for="series-theory-absent" class="text-xs text-slate-350 font-semibold cursor-pointer">Mark Student as Absent</label>
                    </div>
                </div>

                <!-- Live Total Display -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-semibold">Total Series Test Score:</span>
                    <span id="series-theory-live-total" class="font-bold text-emerald-400 text-sm">0.00 / 50.00</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSeriesTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSeriesTheoryStudent()" class="header-btn px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesTheoryMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Continuous Lab Experiment Evaluation Modal (Single Page Desktop View)
    ================================================================= -->
    <div id="experiment-eval-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-4xl lg:max-w-5xl w-full p-4 sm:p-5 rounded-2xl border border-emerald-500/30 shadow-2xl space-y-3 max-h-[92vh] flex flex-col bg-slate-950">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🔬</span>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Continuous Lab Work Evaluator (Table 2.2)</h3>
                        <p class="text-slate-400 text-[11px] leading-tight mt-0.5">Grade student on 6 criteria (50 Marks) &bull; Auto-scaled to 10 CIA marks &bull; Auto-saved</p>
                    </div>
                </div>
                <button type="button" onclick="closeExperimentEvalModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>
 
            <!-- Selectors and Steppers -->
            <div class="bg-slate-900/90 p-2.5 rounded-xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2.5 flex-shrink-0">
                <div class="flex items-center gap-2 w-full sm:w-1/2">
                    <label class="text-slate-300 text-xs font-semibold whitespace-nowrap">Experiment:</label>
                    <select id="eval-exp-select" onchange="onEvalExpChange(this.value)" class="bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-emerald-400 font-semibold outline-none w-full focus:border-emerald-500 cursor-pointer">
                        <option value="Continuous Evaluation">⭐ Continuous Evaluation (Overall Table 2.2)</option>
                        @php
                            $evalExps = $practicumCourseFile->parsed_experiments ?? [];
                            if (is_string($evalExps)) $evalExps = json_decode($evalExps, true) ?: [];
                        @endphp
                        @foreach($evalExps as $exp)
                        @php
                            $expCodeVal = $exp['code'] ?? ($exp['experiment_no'] ?? '');
                        @endphp
                        <option value="{{ $expCodeVal }}">{{ $expCodeVal }} - {{ $exp['title'] ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
 
                <div class="flex items-center gap-1.5 w-full sm:w-1/2">
                    <button type="button" onclick="prevExpStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">◀ Prev</button>
                    <select id="eval-student-select" onchange="loadExpStudent(this.value)" class="flex-1 bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1 font-bold text-xs text-white outline-none focus:border-emerald-500 cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="nextExpStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">Next ▶</button>
                </div>
            </div>
 
            <!-- Rubrics Form Card (Single Page Grid on Desktop) -->
            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2 flex-shrink-0" id="exp-rubrics-container">
                <!-- Javascript will populate 3-col grid with small sliders & data entry fields -->
            </div>
 
            <!-- Live Converted Result Display -->
            <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center text-xs flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400 font-semibold text-xs">Total Evaluation Score:</span>
                    <span id="exp-live-total" class="font-bold text-emerald-400 text-sm font-mono">0.00 / 50.00 M</span>
                </div>
                <div class="flex items-center gap-2 text-right">
                    <span class="text-slate-400 font-semibold text-xs">Continuous Evaluation CIA:</span>
                    <span id="exp-live-cia" class="font-bold text-amber-300 text-sm font-mono px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20">0.00 / 10.00 M</span>
                </div>
            </div>
 
            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-2.5 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeExperimentEvalModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700 cursor-pointer">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextExpStudent()" class="header-btn px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save & Next Student ▶</button>
                    <button type="button" onclick="saveAllExpMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <!-- ================================================================
         Practical Series Exam Evaluation Modal
    ================================================================= -->
    <div id="series-practical-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-4xl lg:max-w-5xl w-full p-4 sm:p-5 rounded-2xl border border-sky-500/30 shadow-2xl space-y-3 max-h-[92vh] flex flex-col bg-slate-950">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🧪</span>
                    <div>
                        <h3 class="text-base font-bold text-white leading-tight">Practical Series Test Marks Evaluator (Table 3.1)</h3>
                        <p class="text-slate-400 text-[11px] leading-tight mt-0.5">Grade student on 5 practical criteria (40 Marks) &bull; Scaled to 10 CIA marks &bull; Auto-saved</p>
                    </div>
                </div>
                <button type="button" onclick="closeSeriesPracticalModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>

            <!-- Series and Student Selection -->
            <div class="bg-slate-900/90 p-2.5 rounded-xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2.5 flex-shrink-0">
                <div class="flex items-center gap-2 w-full sm:w-1/2">
                    <label class="text-slate-300 text-xs font-semibold whitespace-nowrap">Series Test:</label>
                    <select id="series-pr-test-select" onchange="onSeriesPrTestChange(this.value)" class="bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-amber-400 font-semibold outline-none w-full focus:border-amber-500 cursor-pointer">
                        <option value="Series 1">Practical Test 1 (CO1+CO2)</option>
                        <option value="Series 2">Practical Test 2 (CO3+CO4)</option>
                    </select>
                </div>

                <div class="flex items-center gap-1.5 w-full sm:w-1/2">
                    <button type="button" onclick="prevSeriesPrStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">◀ Prev</button>
                    <select id="series-pr-student-select" onchange="loadSeriesPrStudent(this.value)" class="flex-1 bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1 font-bold text-xs text-white outline-none focus:border-sky-500 cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="nextSeriesPrStudent()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs cursor-pointer">Next ▶</button>
                </div>
            </div>

            <!-- Rubrics Form Card (Single Page Grid on Desktop) -->
            <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2 flex-shrink-0" id="series-pr-rubrics-container">
                <!-- Javascript will populate 3-col grid with small sliders & data entry fields -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center text-xs flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400 font-semibold text-xs">Total Series Score:</span>
                    <span id="series-pr-live-total" class="font-bold text-sky-400 text-sm font-mono">0.00 / 40.00 M</span>
                </div>
                <div class="flex items-center gap-2 text-right">
                    <span class="text-slate-400 font-semibold text-xs">Practical Series CIA:</span>
                    <span id="series-pr-live-cia" class="font-bold text-amber-300 text-sm font-mono px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20">0.00 / 10.00 M</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-2.5 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSeriesPracticalModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700 cursor-pointer">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSeriesPrStudent()" class="header-btn px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save & Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesPrMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <script>
    // =====================================================================
    // Continuous Lab Experiment Evaluation System
    // =====================================================================
    const experimentEvalsDb = @json($experimentEvals);
    const experimentEvalsState = {};
    let expAutoSaveTimers = {};

    // Initialize state
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        experimentEvalsState[regNo] = {};
        
        // Populate from DB if exists
        const dbList = experimentEvalsDb[regNo] || [];
        let hasCe = false;
        let sumPrep = 0, sumSetup = 0, sumObs = 0, sumAnalysis = 0, sumViva = 0, sumWork = 0, expCount = 0;

        dbList.forEach(rec => {
            const eNo = rec.experiment_no;
            const p1 = parseFloat(rec.prep_punctuality) || 0;
            const p2 = parseFloat(rec.setup_procedure) || 0;
            const p3 = parseFloat(rec.observation_recording) || 0;
            const p4 = parseFloat(rec.analysis_interpretation) || 0;
            const p5 = parseFloat(rec.viva_voce) || 0;
            const p6 = parseFloat(rec.workmanship_discipline) || 0;
            const tot = parseFloat(rec.total_score_50) || (p1 + p2 + p3 + p4 + p5 + p6);

            experimentEvalsState[regNo][eNo] = {
                prep_punctuality: p1,
                setup_procedure: p2,
                observation_recording: p3,
                analysis_interpretation: p4,
                viva_voce: p5,
                workmanship_discipline: p6,
                total_score_50: tot
            };

            if (eNo === 'Continuous Evaluation') {
                hasCe = true;
            } else {
                sumPrep += p1;
                sumSetup += p2;
                sumObs += p3;
                sumAnalysis += p4;
                sumViva += p5;
                sumWork += p6;
                expCount++;
            }
        });

        if (!hasCe) {
            if (expCount > 0) {
                const p1 = Math.round((sumPrep / expCount) * 2) / 2;
                const p2 = Math.round((sumSetup / expCount) * 2) / 2;
                const p3 = Math.round((sumObs / expCount) * 2) / 2;
                const p4 = Math.round((sumAnalysis / expCount) * 2) / 2;
                const p5 = Math.round((sumViva / expCount) * 2) / 2;
                const p6 = Math.round((sumWork / expCount) * 2) / 2;
                const tot = p1 + p2 + p3 + p4 + p5 + p6;
                experimentEvalsState[regNo]['Continuous Evaluation'] = {
                    prep_punctuality: p1,
                    setup_procedure: p2,
                    observation_recording: p3,
                    analysis_interpretation: p4,
                    viva_voce: p5,
                    workmanship_discipline: p6,
                    total_score_50: tot
                };
            } else {
                experimentEvalsState[regNo]['Continuous Evaluation'] = {
                    prep_punctuality: 0,
                    setup_procedure: 0,
                    observation_recording: 0,
                    analysis_interpretation: 0,
                    viva_voce: 0,
                    workmanship_discipline: 0,
                    total_score_50: 0
                };
            }
        }
    });

    function openExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }

    function closeExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.add('hidden');
    }

    function openExpModalForStudent(regNo) {
        const tableExp = document.getElementById('ce-table-exp-select')?.value || 'Continuous Evaluation';
        const evalExpSel = document.getElementById('eval-exp-select');
        if (evalExpSel) evalExpSel.value = tableExp;

        const evalStudentSel = document.getElementById('eval-student-select');
        if (evalStudentSel) evalStudentSel.value = regNo;

        openExperimentEvalModal();
    }

    function onEvalExpChange(expNo) {
        const tableSel = document.getElementById('ce-table-exp-select');
        if (tableSel && tableSel.value !== expNo) {
            tableSel.value = expNo;
            onCeTableExpChange(expNo, false);
        }
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }

    function onCeTableExpChange(expNo, syncModal = true) {
        if (syncModal) {
            const evalExpSel = document.getElementById('eval-exp-select');
            if (evalExpSel && evalExpSel.value !== expNo) {
                evalExpSel.value = expNo;
            }
        }

        studentsList.forEach(s => {
            const regNo = s.reg_no;
            const state = experimentEvalsState[regNo]?.[expNo] || {
                prep_punctuality: 0, setup_procedure: 0, observation_recording: 0,
                analysis_interpretation: 0, viva_voce: 0, workmanship_discipline: 0,
                total_score_50: 0
            };
            const p1 = state.prep_punctuality || 0;
            const p2 = state.setup_procedure || 0;
            const p3 = state.observation_recording || 0;
            const p4 = state.analysis_interpretation || 0;
            const p5 = state.viva_voce || 0;
            const p6 = state.workmanship_discipline || 0;
            const tot = p1 + p2 + p3 + p4 + p5 + p6;
            const cia = Math.round(((tot / 50.0) * 10.0) * 2) / 2;

            const elPrep = document.getElementById(`ce-input-${regNo}-prep_punctuality`);
            if (elPrep) elPrep.value = p1.toFixed(1);
            const elSetup = document.getElementById(`ce-input-${regNo}-setup_procedure`);
            if (elSetup) elSetup.value = p2.toFixed(1);
            const elObs = document.getElementById(`ce-input-${regNo}-observation_recording`);
            if (elObs) elObs.value = p3.toFixed(1);
            const elAna = document.getElementById(`ce-input-${regNo}-analysis_interpretation`);
            if (elAna) elAna.value = p4.toFixed(1);
            const elViva = document.getElementById(`ce-input-${regNo}-viva_voce`);
            if (elViva) elViva.value = p5.toFixed(1);
            const elWork = document.getElementById(`ce-input-${regNo}-workmanship_discipline`);
            if (elWork) elWork.value = p6.toFixed(1);
            const elTot = document.getElementById(`ce-total-${regNo}`);
            if (elTot) elTot.innerText = tot.toFixed(1);
            const elCia = document.getElementById(`ce-cia-${regNo}`);
            if (elCia) elCia.value = cia.toFixed(1);
        });
    }

    function loadExpStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;

        if (!experimentEvalsState[regNo]) {
            experimentEvalsState[regNo] = {};
        }

        if (!experimentEvalsState[regNo][expNo]) {
            experimentEvalsState[regNo][expNo] = {
                prep_punctuality: 0,
                setup_procedure: 0,
                observation_recording: 0,
                analysis_interpretation: 0,
                viva_voce: 0,
                workmanship_discipline: 0,
                total_score_50: 0
            };
        }

        const state = experimentEvalsState[regNo][expNo];
        const container = document.getElementById('exp-rubrics-container');

        const criteria = [
            { label: '1. Prep & Punctuality', key: 'prep_punctuality', max: 10, step: 0.5 },
            { label: '2. Setup & Procedure', key: 'setup_procedure', max: 10, step: 0.5 },
            { label: '3. Observation & Recording', key: 'observation_recording', max: 5, step: 0.5 },
            { label: '4. Analysis & Interpretation', key: 'analysis_interpretation', max: 10, step: 0.5 },
            { label: '5. Viva Voce', key: 'viva_voce', max: 10, step: 0.5 },
            { label: '6. Workmanship & Discipline', key: 'workmanship_discipline', max: 5, step: 0.5 }
        ];

        let html = `
            <div class="flex items-center justify-between px-1 mb-1">
                <div class="text-[11px] text-slate-300 font-semibold truncate">
                    Candidate: <span class="text-white font-bold">#${student.roll_no} - ${student.name}</span> <span class="text-emerald-400 font-mono text-[10px]">(${student.reg_no})</span>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">
                    Target: <span class="text-amber-300 font-bold">${expNo}</span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
        `;

        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-950/80 p-2.5 rounded-xl border border-slate-800 space-y-1.5 hover:border-slate-700 transition-all">
                    <div class="flex justify-between items-center text-[11px] font-semibold">
                        <span class="text-slate-300 truncate" title="${c.label}">${c.label}</span>
                        <span class="text-slate-500 text-[10px] ml-1 flex-shrink-0">Max ${c.max}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <input type="number" min="0" max="${c.max}" step="${c.step}" value="${val.toFixed(1)}" id="exp-num-${c.key}" oninput="syncExpInput('${c.key}', this.value, ${c.max})" class="w-14 px-1 py-0.5 bg-slate-900 border border-slate-700 rounded font-mono font-bold text-xs text-emerald-400 text-center focus:border-emerald-500 outline-none transition-all flex-shrink-0">
                        <button type="button" onclick="adjustExpVal('${c.key}', -${c.step}, ${c.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 font-black text-xs text-white flex items-center justify-center transition-all flex-shrink-0 cursor-pointer">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="exp-slider-${c.key}" oninput="syncExpSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-emerald-500 bg-slate-900 border border-slate-800 rounded h-1.5 outline-none cursor-pointer">
                        <button type="button" onclick="adjustExpVal('${c.key}', ${c.step}, ${c.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 font-black text-xs text-white flex items-center justify-center transition-all flex-shrink-0 cursor-pointer">+</button>
                    </div>
                </div>
            `;
        });

        html += `</div>`;

        container.innerHTML = html;
        updateExpLiveDisplay(regNo, expNo);
    }

    function syncExpSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('eval-student-select').value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;

        experimentEvalsState[regNo][expNo][key] = num;

        const numInput = document.getElementById(`exp-num-${key}`);
        if (numInput) numInput.value = num.toFixed(1);

        updateExpLiveDisplay(regNo, expNo);
        syncToCeTable(regNo, expNo, key, num);
        triggerDebouncedAutoSave(regNo, expNo);
    }

    function syncExpInput(key, val, max) {
        let num = parseFloat(val);
        if (isNaN(num)) num = 0;
        if (num < 0) num = 0;
        if (num > max) num = max;

        const regNo = document.getElementById('eval-student-select').value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;

        experimentEvalsState[regNo][expNo][key] = num;

        const slider = document.getElementById(`exp-slider-${key}`);
        if (slider) slider.value = num;

        updateExpLiveDisplay(regNo, expNo);
        syncToCeTable(regNo, expNo, key, num);
        triggerDebouncedAutoSave(regNo, expNo);
    }

    function adjustExpVal(key, delta, max) {
        const slider = document.getElementById(`exp-slider-${key}`);
        if (!slider) return;

        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, Math.round((current + delta) * 2) / 2));
        slider.value = next;
        syncExpSlider(key, next, max);
    }

    function updateExpLiveDisplay(regNo, expNo) {
        const state = experimentEvalsState[regNo]?.[expNo];
        if (!state) return;

        const total = (state.prep_punctuality || 0) +
                      (state.setup_procedure || 0) +
                      (state.observation_recording || 0) +
                      (state.analysis_interpretation || 0) +
                      (state.viva_voce || 0) +
                      (state.workmanship_discipline || 0);

        state.total_score_50 = total;

        const cia = Math.round(((total / 50.0) * 10.0) * 2) / 2;

        const liveTotal = document.getElementById('exp-live-total');
        if (liveTotal) liveTotal.innerText = `${total.toFixed(1)} / 50.0 M`;
        const liveCia = document.getElementById('exp-live-cia');
        if (liveCia) liveCia.innerText = `${cia.toFixed(1)} / 10.0 M`;
    }

    function syncToCeTable(regNo, expNo, key, val) {
        const currentTableExp = document.getElementById('ce-table-exp-select')?.value;
        if (currentTableExp !== expNo) return;

        const tableInput = document.getElementById(`ce-input-${regNo}-${key}`);
        if (tableInput) tableInput.value = parseFloat(val).toFixed(1);

        const state = experimentEvalsState[regNo]?.[expNo];
        if (!state) return;

        const tot = (state.prep_punctuality || 0) +
                    (state.setup_procedure || 0) +
                    (state.observation_recording || 0) +
                    (state.analysis_interpretation || 0) +
                    (state.viva_voce || 0) +
                    (state.workmanship_discipline || 0);
        state.total_score_50 = tot;
        const cia = Math.round(((tot / 50.0) * 10.0) * 2) / 2;

        const totEl = document.getElementById(`ce-total-${regNo}`);
        if (totEl) totEl.innerText = tot.toFixed(1);

        const ciaEl = document.getElementById(`ce-cia-${regNo}`);
        if (ciaEl) ciaEl.value = cia.toFixed(1);
    }

    function onCeTableInput(regNo, key, val) {
        const expNo = document.getElementById('ce-table-exp-select')?.value || 'Continuous Evaluation';
        if (!experimentEvalsState[regNo]) experimentEvalsState[regNo] = {};
        if (!experimentEvalsState[regNo][expNo]) {
            experimentEvalsState[regNo][expNo] = {
                prep_punctuality: 0, setup_procedure: 0, observation_recording: 0,
                analysis_interpretation: 0, viva_voce: 0, workmanship_discipline: 0,
                total_score_50: 0
            };
        }

        const maxMap = {
            prep_punctuality: 10, setup_procedure: 10, observation_recording: 5,
            analysis_interpretation: 10, viva_voce: 10, workmanship_discipline: 5
        };
        const max = maxMap[key] || 10;

        let num = parseFloat(val);
        if (isNaN(num)) num = 0;
        if (num < 0) num = 0;
        if (num > max) num = max;

        experimentEvalsState[regNo][expNo][key] = num;

        const state = experimentEvalsState[regNo][expNo];
        const tot = (state.prep_punctuality || 0) +
                    (state.setup_procedure || 0) +
                    (state.observation_recording || 0) +
                    (state.analysis_interpretation || 0) +
                    (state.viva_voce || 0) +
                    (state.workmanship_discipline || 0);
        state.total_score_50 = tot;
        const cia = Math.round(((tot / 50.0) * 10.0) * 2) / 2;

        const totEl = document.getElementById(`ce-total-${regNo}`);
        if (totEl) totEl.innerText = tot.toFixed(1);
        const ciaEl = document.getElementById(`ce-cia-${regNo}`);
        if (ciaEl) ciaEl.value = cia.toFixed(1);

        // Sync modal if open on same student and experiment
        const modalStudent = document.getElementById('eval-student-select')?.value;
        const modalExp = document.getElementById('eval-exp-select')?.value;
        if (modalStudent === regNo && modalExp === expNo) {
            const inputEl = document.getElementById(`exp-num-${key}`);
            if (inputEl) inputEl.value = num.toFixed(1);
            const sliderEl = document.getElementById(`exp-slider-${key}`);
            if (sliderEl) sliderEl.value = num;
            updateExpLiveDisplay(regNo, expNo);
        }

        triggerDebouncedAutoSave(regNo, expNo);
    }

    function onCeCiaInput(regNo, val) {
        const expNo = document.getElementById('ce-table-exp-select')?.value || 'Continuous Evaluation';
        if (!experimentEvalsState[regNo]) experimentEvalsState[regNo] = {};
        if (!experimentEvalsState[regNo][expNo]) {
            experimentEvalsState[regNo][expNo] = {
                prep_punctuality: 0, setup_procedure: 0, observation_recording: 0,
                analysis_interpretation: 0, viva_voce: 0, workmanship_discipline: 0,
                total_score_50: 0
            };
        }

        let cia = parseFloat(val);
        if (isNaN(cia)) cia = 0;
        if (cia < 0) cia = 0;
        if (cia > 10) cia = 10;

        // Scale proportionately: total out of 50 = cia * 5.0
        const totTarget = cia * 5.0;
        const p1 = Math.round((totTarget * 0.20) * 2) / 2;
        const p2 = Math.round((totTarget * 0.20) * 2) / 2;
        const p3 = Math.round((totTarget * 0.10) * 2) / 2;
        const p4 = Math.round((totTarget * 0.20) * 2) / 2;
        const p5 = Math.round((totTarget * 0.20) * 2) / 2;
        const p6 = Math.max(0, Math.round((totTarget - (p1 + p2 + p3 + p4 + p5)) * 2) / 2);

        const state = experimentEvalsState[regNo][expNo];
        state.prep_punctuality = Math.min(10, p1);
        state.setup_procedure = Math.min(10, p2);
        state.observation_recording = Math.min(5, p3);
        state.analysis_interpretation = Math.min(10, p4);
        state.viva_voce = Math.min(10, p5);
        state.workmanship_discipline = Math.min(5, p6);
        state.total_score_50 = state.prep_punctuality + state.setup_procedure + state.observation_recording + state.analysis_interpretation + state.viva_voce + state.workmanship_discipline;

        // Update table inputs
        const elPrep = document.getElementById(`ce-input-${regNo}-prep_punctuality`);
        if (elPrep) elPrep.value = state.prep_punctuality.toFixed(1);
        const elSetup = document.getElementById(`ce-input-${regNo}-setup_procedure`);
        if (elSetup) elSetup.value = state.setup_procedure.toFixed(1);
        const elObs = document.getElementById(`ce-input-${regNo}-observation_recording`);
        if (elObs) elObs.value = state.observation_recording.toFixed(1);
        const elAna = document.getElementById(`ce-input-${regNo}-analysis_interpretation`);
        if (elAna) elAna.value = state.analysis_interpretation.toFixed(1);
        const elViva = document.getElementById(`ce-input-${regNo}-viva_voce`);
        if (elViva) elViva.value = state.viva_voce.toFixed(1);
        const elWork = document.getElementById(`ce-input-${regNo}-workmanship_discipline`);
        if (elWork) elWork.value = state.workmanship_discipline.toFixed(1);
        const elTot = document.getElementById(`ce-total-${regNo}`);
        if (elTot) elTot.innerText = state.total_score_50.toFixed(1);

        // Sync modal if open on same student
        const modalStudent = document.getElementById('eval-student-select')?.value;
        const modalExp = document.getElementById('eval-exp-select')?.value;
        if (modalStudent === regNo && modalExp === expNo) {
            loadExpStudent(regNo);
        }

        triggerDebouncedAutoSave(regNo, expNo);
    }

    function triggerDebouncedAutoSave(regNo, expNo) {
        const timerKey = `${regNo}_${expNo}`;
        if (expAutoSaveTimers[timerKey]) {
            clearTimeout(expAutoSaveTimers[timerKey]);
        }
        showExpAutoSaveIndicator('saving');
        expAutoSaveTimers[timerKey] = setTimeout(() => {
            saveSingleExpStudentMarks(regNo, expNo);
        }, 750);
    }

    function showExpAutoSaveIndicator(status) {
        let indicator = document.getElementById('exp-autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'exp-autosave-indicator';
            document.body.appendChild(indicator);
        }

        if (status === 'saving') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-slate-900 text-amber-300 border border-amber-500/40 opacity-100';
            indicator.innerHTML = '<span class="inline-block animate-spin">⏳</span> Saving lab marks...';
        } else if (status === 'saved') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-slate-900 text-emerald-400 border border-emerald-500/40 opacity-100';
            indicator.innerHTML = '<span>✓</span> Lab Evaluation Auto-saved';
            setTimeout(() => {
                if (indicator) indicator.classList.replace('opacity-100', 'opacity-0');
            }, 2000);
        } else if (status === 'error') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-rose-950 text-rose-300 border border-rose-500/40 opacity-100';
            indicator.innerHTML = '<span>⚠️</span> Auto-save error';
            setTimeout(() => {
                if (indicator) indicator.classList.replace('opacity-100', 'opacity-0');
            }, 3000);
        }
    }

    function saveSingleExpStudentMarks(regNo, expNo) {
        const state = experimentEvalsState[regNo]?.[expNo];
        if (!state) return;

        const bsId = {{ $batchSubject->id }};
        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/experiment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                experiment_no: expNo,
                marks_data: [{
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showExpAutoSaveIndicator('saved');
            } else {
                showExpAutoSaveIndicator('error');
            }
        })
        .catch(err => {
            showExpAutoSaveIndicator('error');
        });
    }

    function prevExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadExpStudent(sel.value);
    }

    function nextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadExpStudent(sel.value);
    }

    function saveAndNextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        const regNo = sel.value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;

        const state = experimentEvalsState[regNo][expNo];
        const bsId = {{ $batchSubject->id }};

        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/experiment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                experiment_no: expNo,
                marks_data: [{
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showExpAutoSaveIndicator('saved');
                nextExpStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }

    function saveAllExpMarks() {
        const marksData = [];
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;

        Object.keys(experimentEvalsState).forEach(regNo => {
            const state = experimentEvalsState[regNo][expNo];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                });
            }
        });

        Swal.fire({
            title: 'Saving Lab Work Marks...',
            text: `Saving scores for ${expNo}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/experiment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ experiment_no: expNo, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeExperimentEvalModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }
 
    // =====================================================================
    // Practical Series Exams Evaluation System
    // =====================================================================
    const seriesPracticalEvalsDb = @json($seriesPracticalEvals);
    const seriesPracticalEvalsState = {};
    let seriesPrAutoSaveTimers = {};

    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesPracticalEvalsState[regNo] = {
            'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
            'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
        };

        const dbList = seriesPracticalEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            const sNo = rec.series_no;
            let mapped = sNo;
            if (sNo === 'Test 1 (CO1+CO2)' || sNo === 'Test 1' || sNo === 'CA2') mapped = 'Series 1';
            if (sNo === 'Test 2 (CO3+CO4)' || sNo === 'Test 2' || sNo === 'CA3') mapped = 'Series 2';

            if (seriesPracticalEvalsState[regNo][mapped]) {
                const w = parseFloat(rec.writeup_procedure) || 0;
                const set = parseFloat(rec.setup_execution) || 0;
                const o = parseFloat(rec.observation_result) || 0;
                const v = parseFloat(rec.viva_voce) || 0;
                const r = parseFloat(rec.record_completion) || 0;
                const tot = parseFloat(rec.total_score_40) || (w + set + o + v + r);
                seriesPracticalEvalsState[regNo][mapped] = {
                    writeup_procedure: w,
                    setup_execution: set,
                    observation_result: o,
                    viva_voce: v,
                    record_completion: r,
                    total_score_40: rec.is_absent ? 0 : tot,
                    is_absent: !!rec.is_absent
                };
            }
        });
    });

    function openSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }

    function closeSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.add('hidden');
    }

    function openSeriesPrModalForStudent(regNo) {
        const tableTest = document.getElementById('series-pr-table-test-select')?.value || 'Series 1';
        const testSel = document.getElementById('series-pr-test-select');
        if (testSel) testSel.value = tableTest;

        const stSel = document.getElementById('series-pr-student-select');
        if (stSel) stSel.value = regNo;

        openSeriesPracticalModal();
    }

    function onSeriesPrTestChange(test) {
        const tableSel = document.getElementById('series-pr-table-test-select');
        if (tableSel && tableSel.value !== test) {
            tableSel.value = test;
            onSeriesPrTableTestChange(test, false);
        }
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }

    function onSeriesPrTableTestChange(test, syncModal = true) {
        if (syncModal) {
            const modalTestSel = document.getElementById('series-pr-test-select');
            if (modalTestSel && modalTestSel.value !== test) {
                modalTestSel.value = test;
            }
        }

        studentsList.forEach(s => {
            const regNo = s.reg_no;
            const state = seriesPracticalEvalsState[regNo]?.[test] || {
                writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false
            };
            const w = state.writeup_procedure || 0;
            const set = state.setup_execution || 0;
            const o = state.observation_result || 0;
            const v = state.viva_voce || 0;
            const r = state.record_completion || 0;

            const elW = document.getElementById(`sp-input-${regNo}-writeup_procedure`);
            if (elW) elW.value = w.toFixed(1);
            const elS = document.getElementById(`sp-input-${regNo}-setup_execution`);
            if (elS) elS.value = set.toFixed(1);
            const elO = document.getElementById(`sp-input-${regNo}-observation_result`);
            if (elO) elO.value = o.toFixed(1);
            const elV = document.getElementById(`sp-input-${regNo}-viva_voce`);
            if (elV) elV.value = v.toFixed(1);
            const elR = document.getElementById(`sp-input-${regNo}-record_completion`);
            if (elR) elR.value = r.toFixed(1);

            const t1 = seriesPracticalEvalsState[regNo]?.['Series 1']?.total_score_40 || 0;
            const t2 = seriesPracticalEvalsState[regNo]?.['Series 2']?.total_score_40 || 0;
            const avg = (t1 + t2) / 2.0;
            const cia = Math.round(((avg / 40.0) * 10.0) * 2) / 2;

            const elT1 = document.getElementById(`sp-score-t1-${regNo}`);
            if (elT1) elT1.value = t1.toFixed(1);
            const elT2 = document.getElementById(`sp-score-t2-${regNo}`);
            if (elT2) elT2.value = t2.toFixed(1);
            const elAvg = document.getElementById(`sp-avg-${regNo}`);
            if (elAvg) elAvg.innerText = avg.toFixed(1);
            const elCia = document.getElementById(`sp-cia-${regNo}`);
            if (elCia) elCia.value = cia.toFixed(1);
        });
    }

    function loadSeriesPrStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;

        if (!seriesPracticalEvalsState[regNo]) {
            seriesPracticalEvalsState[regNo] = {
                'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
                'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
            };
        }

        const state = seriesPracticalEvalsState[regNo][test];
        const container = document.getElementById('series-pr-rubrics-container');

        const criteria = [
            { label: '1. Write-up / Procedure', key: 'writeup_procedure', max: 10, step: 0.5 },
            { label: '2. Setup & Execution', key: 'setup_execution', max: 10, step: 0.5 },
            { label: '3. Observation & Result', key: 'observation_result', max: 8, step: 0.5 },
            { label: '4. Viva Voce', key: 'viva_voce', max: 8, step: 0.5 },
            { label: '5. Record Completion', key: 'record_completion', max: 4, step: 0.5 }
        ];

        const testTitle = test === 'Series 1' ? 'Practical Test 1 (CO1+CO2)' : 'Practical Test 2 (CO3+CO4)';

        let html = `
            <div class="flex items-center justify-between px-1 mb-1">
                <div class="text-[11px] text-slate-300 font-semibold truncate">
                    Candidate: <span class="text-white font-bold">#${student.roll_no} - ${student.name}</span> <span class="text-sky-400 font-mono text-[10px]">(${student.reg_no})</span>
                </div>
                <div class="text-[11px] text-slate-400 font-medium">
                    Test: <span class="text-amber-300 font-bold">${testTitle}</span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
        `;

        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-950/80 p-2.5 rounded-xl border border-slate-800 space-y-1.5 hover:border-slate-700 transition-all">
                    <div class="flex justify-between items-center text-[11px] font-semibold">
                        <span class="text-slate-300 truncate" title="${c.label}">${c.label}</span>
                        <span class="text-slate-500 text-[10px] ml-1 flex-shrink-0">Max ${c.max}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <input type="number" min="0" max="${c.max}" step="${c.step}" value="${val.toFixed(1)}" id="series-pr-num-${c.key}" oninput="syncSeriesPrInput('${c.key}', this.value, ${c.max})" class="w-14 px-1 py-0.5 bg-slate-900 border border-slate-700 rounded font-mono font-bold text-xs text-sky-400 text-center focus:border-sky-500 outline-none transition-all flex-shrink-0">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', -${c.step}, ${c.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 font-black text-xs text-white flex items-center justify-center transition-all flex-shrink-0 cursor-pointer">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="series-pr-slider-${c.key}" oninput="syncSeriesPrSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-sky-500 bg-slate-900 border border-slate-800 rounded h-1.5 outline-none cursor-pointer">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', ${c.step}, ${c.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 font-black text-xs text-white flex items-center justify-center transition-all flex-shrink-0 cursor-pointer">+</button>
                    </div>
                </div>
            `;
        });

        // 6th Card: Status / Absent toggle
        html += `
            <div class="bg-slate-950/80 p-2.5 rounded-xl border border-slate-800 flex flex-col justify-between hover:border-slate-700 transition-all">
                <div class="flex justify-between items-center text-[11px] font-semibold">
                    <span class="text-slate-300">Candidate Exam Status</span>
                    <span class="text-xs px-2 py-0.5 rounded-full ${state.is_absent ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold'}">${state.is_absent ? 'ABSENT' : 'PRESENT'}</span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="series-pr-absent-check" ${state.is_absent ? 'checked' : ''} onchange="toggleSeriesPrAbsent(this.checked)" class="w-4 h-4 rounded border-slate-700 text-rose-600 focus:ring-rose-500">
                        <span class="text-xs text-rose-300 font-semibold">Mark Absent (0/40)</span>
                    </label>
                    <span class="text-[10px] text-slate-500">Table 3.1 Rubric</span>
                </div>
            </div>
        `;

        html += `</div>`;

        container.innerHTML = html;
        updateSeriesPrLiveDisplay(regNo, test);
    }

    function syncSeriesPrSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;

        seriesPracticalEvalsState[regNo][test][key] = num;
        seriesPracticalEvalsState[regNo][test].is_absent = false;

        const numInput = document.getElementById(`series-pr-num-${key}`);
        if (numInput) numInput.value = num.toFixed(1);

        const absCheck = document.getElementById('series-pr-absent-check');
        if (absCheck) absCheck.checked = false;

        updateSeriesPrLiveDisplay(regNo, test);
        syncToSeriesPrTable(regNo, test, key, num);
        triggerDebouncedSeriesPrAutoSave(regNo, test);
    }

    function syncSeriesPrInput(key, val, max) {
        let num = parseFloat(val);
        if (isNaN(num)) num = 0;
        if (num < 0) num = 0;
        if (num > max) num = max;

        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;

        seriesPracticalEvalsState[regNo][test][key] = num;
        seriesPracticalEvalsState[regNo][test].is_absent = false;

        const slider = document.getElementById(`series-pr-slider-${key}`);
        if (slider) slider.value = num;

        const absCheck = document.getElementById('series-pr-absent-check');
        if (absCheck) absCheck.checked = false;

        updateSeriesPrLiveDisplay(regNo, test);
        syncToSeriesPrTable(regNo, test, key, num);
        triggerDebouncedSeriesPrAutoSave(regNo, test);
    }

    function adjustSeriesPrVal(key, delta, max) {
        const slider = document.getElementById(`series-pr-slider-${key}`);
        if (!slider) return;

        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, Math.round((current + delta) * 2) / 2));
        slider.value = next;
        syncSeriesPrSlider(key, next, max);
    }

    function toggleSeriesPrAbsent(checked) {
        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;

        const state = seriesPracticalEvalsState[regNo][test];
        state.is_absent = !!checked;

        if (checked) {
            state.writeup_procedure = 0;
            state.setup_execution = 0;
            state.observation_result = 0;
            state.viva_voce = 0;
            state.record_completion = 0;
            state.total_score_40 = 0;
        } else {
            state.total_score_40 = (state.writeup_procedure || 0) +
                                   (state.setup_execution || 0) +
                                   (state.observation_result || 0) +
                                   (state.viva_voce || 0) +
                                   (state.record_completion || 0);
        }

        loadSeriesPrStudent(regNo);
        syncAllToSeriesPrTableRow(regNo);
        triggerDebouncedSeriesPrAutoSave(regNo, test);
    }

    function updateSeriesPrLiveDisplay(regNo, test) {
        const state = seriesPracticalEvalsState[regNo]?.[test];
        if (!state) return;

        const total = state.is_absent ? 0 : (
            (state.writeup_procedure || 0) +
            (state.setup_execution || 0) +
            (state.observation_result || 0) +
            (state.viva_voce || 0) +
            (state.record_completion || 0)
        );

        state.total_score_40 = total;

        const cia = Math.round(((total / 40.0) * 10.0) * 2) / 2;

        const liveTotal = document.getElementById('series-pr-live-total');
        if (liveTotal) liveTotal.innerText = `${total.toFixed(1)} / 40.0 M`;
        const liveCia = document.getElementById('series-pr-live-cia');
        if (liveCia) liveCia.innerText = `${cia.toFixed(1)} / 10.0 M`;
    }

    function syncToSeriesPrTable(regNo, test, key, val) {
        const currentTableTest = document.getElementById('series-pr-table-test-select')?.value || 'Series 1';
        if (currentTableTest === test) {
            const tableInput = document.getElementById(`sp-input-${regNo}-${key}`);
            if (tableInput) tableInput.value = parseFloat(val).toFixed(1);
        }

        syncAllToSeriesPrTableRow(regNo);
    }

    function syncAllToSeriesPrTableRow(regNo) {
        const state1 = seriesPracticalEvalsState[regNo]?.['Series 1'] || { total_score_40: 0 };
        const state2 = seriesPracticalEvalsState[regNo]?.['Series 2'] || { total_score_40: 0 };

        const t1 = state1.is_absent ? 0 : (state1.total_score_40 || 0);
        const t2 = state2.is_absent ? 0 : (state2.total_score_40 || 0);
        const avg = (t1 + t2) / 2.0;
        const cia = Math.round(((avg / 40.0) * 10.0) * 2) / 2;

        const elT1 = document.getElementById(`sp-score-t1-${regNo}`);
        if (elT1) elT1.value = t1.toFixed(1);
        const elT2 = document.getElementById(`sp-score-t2-${regNo}`);
        if (elT2) elT2.value = t2.toFixed(1);
        const elAvg = document.getElementById(`sp-avg-${regNo}`);
        if (elAvg) elAvg.innerText = avg.toFixed(1);
        const elCia = document.getElementById(`sp-cia-${regNo}`);
        if (elCia) elCia.value = cia.toFixed(1);
    }

    function onSeriesPrTableInput(regNo, key, val) {
        const test = document.getElementById('series-pr-table-test-select')?.value || 'Series 1';
        if (!seriesPracticalEvalsState[regNo]) {
            seriesPracticalEvalsState[regNo] = {
                'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
                'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
            };
        }

        const maxMap = {
            writeup_procedure: 10,
            setup_execution: 10,
            observation_result: 8,
            viva_voce: 8,
            record_completion: 4
        };
        const max = maxMap[key] || 10;

        let num = parseFloat(val);
        if (isNaN(num)) num = 0;
        if (num < 0) num = 0;
        if (num > max) num = max;

        const state = seriesPracticalEvalsState[regNo][test];
        state[key] = num;
        state.is_absent = false;
        state.total_score_40 = (state.writeup_procedure || 0) +
                               (state.setup_execution || 0) +
                               (state.observation_result || 0) +
                               (state.viva_voce || 0) +
                               (state.record_completion || 0);

        syncAllToSeriesPrTableRow(regNo);

        // Sync modal if open on same student and test
        const modalStudent = document.getElementById('series-pr-student-select')?.value;
        const modalTest = document.getElementById('series-pr-test-select')?.value;
        if (modalStudent === regNo && modalTest === test) {
            const numEl = document.getElementById(`series-pr-num-${key}`);
            if (numEl) numEl.value = num.toFixed(1);
            const sliderEl = document.getElementById(`series-pr-slider-${key}`);
            if (sliderEl) sliderEl.value = num;
            updateSeriesPrLiveDisplay(regNo, test);
        }

        triggerDebouncedSeriesPrAutoSave(regNo, test);
    }

    function onSeriesPrTotalInput(regNo, test, val) {
        if (!seriesPracticalEvalsState[regNo]) {
            seriesPracticalEvalsState[regNo] = {
                'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
                'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
            };
        }

        let num = parseFloat(val);
        if (isNaN(num)) num = 0;
        if (num < 0) num = 0;
        if (num > 40) num = 40;

        const state = seriesPracticalEvalsState[regNo][test];
        state.is_absent = false;
        state.total_score_40 = num;

        // Scale 5 criteria proportionately:
        // writeup: 25% (10/40), setup: 25% (10/40), obs: 20% (8/40), viva: 20% (8/40), record: 10% (4/40)
        const w = Math.min(10, Math.round((num * 0.25) * 2) / 2);
        const set = Math.min(10, Math.round((num * 0.25) * 2) / 2);
        const o = Math.min(8, Math.round((num * 0.20) * 2) / 2);
        const v = Math.min(8, Math.round((num * 0.20) * 2) / 2);
        const r = Math.min(4, Math.max(0, Math.round((num - (w + set + o + v)) * 2) / 2));

        state.writeup_procedure = w;
        state.setup_execution = set;
        state.observation_result = o;
        state.viva_voce = v;
        state.record_completion = r;

        // If currently displayed in table criteria columns, update them
        const currentTableTest = document.getElementById('series-pr-table-test-select')?.value || 'Series 1';
        if (currentTableTest === test) {
            const elW = document.getElementById(`sp-input-${regNo}-writeup_procedure`);
            if (elW) elW.value = w.toFixed(1);
            const elS = document.getElementById(`sp-input-${regNo}-setup_execution`);
            if (elS) elS.value = set.toFixed(1);
            const elO = document.getElementById(`sp-input-${regNo}-observation_result`);
            if (elO) elO.value = o.toFixed(1);
            const elV = document.getElementById(`sp-input-${regNo}-viva_voce`);
            if (elV) elV.value = v.toFixed(1);
            const elR = document.getElementById(`sp-input-${regNo}-record_completion`);
            if (elR) elR.value = r.toFixed(1);
        }

        syncAllToSeriesPrTableRow(regNo);

        // Sync modal if open on same student
        const modalStudent = document.getElementById('series-pr-student-select')?.value;
        const modalTest = document.getElementById('series-pr-test-select')?.value;
        if (modalStudent === regNo && modalTest === test) {
            loadSeriesPrStudent(regNo);
        }

        triggerDebouncedSeriesPrAutoSave(regNo, test);
    }

    function onSeriesPrCiaInput(regNo, val) {
        if (!seriesPracticalEvalsState[regNo]) {
            seriesPracticalEvalsState[regNo] = {
                'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
                'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
            };
        }

        let cia = parseFloat(val);
        if (isNaN(cia)) cia = 0;
        if (cia < 0) cia = 0;
        if (cia > 10) cia = 10;

        const targetAvg40 = cia * 4.0;

        // Apply targetAvg40 to both tests
        onSeriesPrTotalInput(regNo, 'Series 1', targetAvg40);
        onSeriesPrTotalInput(regNo, 'Series 2', targetAvg40);
    }

    function triggerDebouncedSeriesPrAutoSave(regNo, test) {
        const timerKey = `${regNo}_${test}`;
        if (seriesPrAutoSaveTimers[timerKey]) {
            clearTimeout(seriesPrAutoSaveTimers[timerKey]);
        }
        showSeriesPrAutoSaveIndicator('saving');
        seriesPrAutoSaveTimers[timerKey] = setTimeout(() => {
            saveSingleSeriesPrStudentMarks(regNo, test);
        }, 750);
    }

    function showSeriesPrAutoSaveIndicator(status) {
        let indicator = document.getElementById('series-pr-autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'series-pr-autosave-indicator';
            document.body.appendChild(indicator);
        }

        if (status === 'saving') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-slate-900 text-amber-300 border border-amber-500/40 opacity-100';
            indicator.innerHTML = '<span class="inline-block animate-spin">⏳</span> Saving practical series marks...';
        } else if (status === 'saved') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-slate-900 text-sky-400 border border-sky-500/40 opacity-100';
            indicator.innerHTML = '<span>✓</span> Practical Series Auto-saved';
            setTimeout(() => {
                if (indicator) indicator.classList.replace('opacity-100', 'opacity-0');
            }, 2000);
        } else if (status === 'error') {
            indicator.className = 'fixed bottom-4 right-4 z-50 px-3 py-1.5 rounded-lg text-xs font-bold shadow-lg transition-all flex items-center gap-1.5 bg-rose-950 text-rose-300 border border-rose-500/40 opacity-100';
            indicator.innerHTML = '<span>⚠️</span> Auto-save error';
            setTimeout(() => {
                if (indicator) indicator.classList.replace('opacity-100', 'opacity-0');
            }, 3000);
        }
    }

    function saveSingleSeriesPrStudentMarks(regNo, test) {
        const state = seriesPracticalEvalsState[regNo]?.[test];
        if (!state) return;

        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
        const bsId = {{ $batchSubject->id }};

        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/series-practical`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                series_no: dbSeriesName,
                marks_data: [{
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showSeriesPrAutoSaveIndicator('saved');
            } else {
                showSeriesPrAutoSaveIndicator('error');
            }
        })
        .catch(err => {
            showSeriesPrAutoSaveIndicator('error');
        });
    }

    function prevSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesPrStudent(sel.value);
    }

    function nextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesPrStudent(sel.value);
    }

    function saveAndNextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        const regNo = sel.value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;

        const state = seriesPracticalEvalsState[regNo][test];
        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
        const bsId = {{ $batchSubject->id }};

        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/series-practical`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                series_no: dbSeriesName,
                marks_data: [{
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showSeriesPrAutoSaveIndicator('saved');
                nextSeriesPrStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }

    function saveAllSeriesPrMarks() {
        const marksData = [];
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;

        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';

        Object.keys(seriesPracticalEvalsState).forEach(regNo => {
            const state = seriesPracticalEvalsState[regNo][test];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                });
            }
        });

        Swal.fire({
            title: 'Saving Series Test Marks...',
            text: `Saving scores for ${dbSeriesName}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-practical', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ series_no: dbSeriesName, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesPracticalModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }
        const eseSplitupState = {};
        let eseAutoSaveTimers = {};

        studentsList.forEach(st => {
            const currentTotal = parseFloat(st.ese_practical || 0);
            if (currentTotal > 0) {
                const factor = currentTotal / 40.0;
                const w = Math.min(10, Math.round(10 * factor * 2) / 2);
                const s = Math.min(10, Math.round(10 * factor * 2) / 2);
                const o = Math.min(8, Math.round(8 * factor * 2) / 2);
                const v = Math.min(8, Math.round(8 * factor * 2) / 2);
                const r = Math.min(4, Math.max(0, Math.round((currentTotal - (w + s + o + v)) * 2) / 2));
                eseSplitupState[st.reg_no] = {
                    writeup: w,
                    setup: s,
                    result: o,
                    viva: v,
                    record: r,
                    is_absent: false
                };
            } else {
                eseSplitupState[st.reg_no] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0, is_absent: false };
            }
        });

        function openEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.remove('hidden');
            const sel = document.getElementById('ese-student-select');
            if (sel && sel.value) {
                loadEseStudent(sel.value);
            }
        }

        function openEsePracticalModalForStudent(regNo) {
            const sel = document.getElementById('ese-student-select');
            if (sel) sel.value = regNo;
            document.getElementById('ese-practical-modal').classList.remove('hidden');
            loadEseStudent(regNo);
        }

        function closeEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.add('hidden');
        }

        const eseRubrics = [
            { key: 'writeup', label: 'Writeup / Procedure', max: 10 },
            { key: 'setup', label: 'Setup & Execution', max: 10 },
            { key: 'result', label: 'Observation & Result', max: 8 },
            { key: 'viva', label: 'Viva Voce', max: 8 },
            { key: 'record', label: 'Record Completion', max: 4 }
        ];

        function loadEseStudent(regNo) {
            const container = document.getElementById('ese-rubrics-container');
            if (!container) return;

            if (!eseSplitupState[regNo]) {
                eseSplitupState[regNo] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0, is_absent: false };
            }

            const state = eseSplitupState[regNo];
            const isAbsent = !!state.is_absent;

            let html = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">';

            eseRubrics.forEach(rub => {
                const currentVal = state[rub.key] || 0;
                html += `
                    <div class="p-2.5 rounded-lg bg-slate-950/80 border border-slate-800 flex flex-col justify-between space-y-1.5 ${isAbsent ? 'opacity-40 pointer-events-none' : ''}">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-300 text-[11px] truncate" title="${rub.label}">${rub.label}</span>
                            <span class="text-[10px] text-slate-500 font-mono">Max ${rub.max}M</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <button type="button" onclick="adjustEseVal('${regNo}', '${rub.key}', -0.5, ${rub.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center justify-center shrink-0 cursor-pointer">-</button>
                            <input type="range" id="ese-slider-${rub.key}" min="0" max="${rub.max}" step="0.5" value="${currentVal}" oninput="syncEseSlider('${regNo}', '${rub.key}', this.value, ${rub.max})" class="flex-1 accent-sky-500 h-1.5 bg-slate-800 rounded-lg cursor-pointer">
                            <button type="button" onclick="adjustEseVal('${regNo}', '${rub.key}', 0.5, ${rub.max})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center justify-center shrink-0 cursor-pointer">+</button>
                            <input type="number" min="0" max="${rub.max}" step="0.5" id="ese-num-${rub.key}" value="${currentVal}" oninput="syncEseInput('${regNo}', '${rub.key}', this.value, ${rub.max})" class="w-12 bg-slate-900 border border-slate-700 rounded px-1 py-0.5 text-center font-mono font-bold text-sky-400 text-xs outline-none focus:border-sky-500 shrink-0">
                        </div>
                    </div>
                `;
            });

            // 6th Card: Absentee toggle
            html += `
                <div class="p-2.5 rounded-lg bg-slate-950/80 border border-slate-800 flex flex-col justify-between space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-slate-300 text-[11px]">Candidate Exam Status</span>
                        <span class="text-[10px] ${isAbsent ? 'text-rose-400 font-bold' : 'text-emerald-400'}">${isAbsent ? 'ABSENT' : 'PRESENT'}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="ese-absent-check" ${isAbsent ? 'checked' : ''} onchange="toggleEseAbsent('${regNo}', this.checked)" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-rose-500 focus:ring-0 cursor-pointer">
                            <span class="text-xs font-semibold ${isAbsent ? 'text-rose-400' : 'text-slate-400'}">Mark as Absent</span>
                        </label>
                        <span class="text-[10px] text-slate-500">Zeroes all marks</span>
                    </div>
                </div>
            `;

            html += '</div>';
            container.innerHTML = html;
            calculateEseLiveTotal(regNo);
        }

        function syncEseSlider(regNo, key, val, maxVal) {
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo][key] = num;

            const numInp = document.getElementById(`ese-num-${key}`);
            if (numInp) numInp.value = num;

            calculateEseLiveTotal(regNo);
            syncAllToEseTableRow(regNo);
            triggerDebouncedEseAutoSave(regNo);
        }

        function syncEseInput(regNo, key, val, maxVal) {
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo][key] = num;

            const slider = document.getElementById(`ese-slider-${key}`);
            if (slider) slider.value = num;

            calculateEseLiveTotal(regNo);
            syncAllToEseTableRow(regNo);
            triggerDebouncedEseAutoSave(regNo);
        }

        function adjustEseVal(regNo, key, delta, maxVal) {
            const slider = document.getElementById(`ese-slider-${key}`);
            let current = parseFloat(slider?.value || eseSplitupState[regNo]?.[key] || 0);
            let next = Math.max(0, Math.min(maxVal, current + delta));
            if (slider) slider.value = next;
            syncEseSlider(regNo, key, next, maxVal);
        }

        function toggleEseAbsent(regNo, isAbsent) {
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo].is_absent = isAbsent;
            if (isAbsent) {
                eseSplitupState[regNo].writeup = 0;
                eseSplitupState[regNo].setup = 0;
                eseSplitupState[regNo].result = 0;
                eseSplitupState[regNo].viva = 0;
                eseSplitupState[regNo].record = 0;
            }
            loadEseStudent(regNo);
            syncAllToEseTableRow(regNo);
            triggerDebouncedEseAutoSave(regNo);
        }

        function onEseTableInput(regNo, key, val) {
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            const maxVal = (key === 'writeup' || key === 'setup') ? 10 : (key === 'record' ? 4 : 8);
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            eseSplitupState[regNo][key] = num;
            eseSplitupState[regNo].is_absent = false;

            const total = (eseSplitupState[regNo].writeup || 0) +
                          (eseSplitupState[regNo].setup || 0) +
                          (eseSplitupState[regNo].result || 0) +
                          (eseSplitupState[regNo].viva || 0) +
                          (eseSplitupState[regNo].record || 0);

            const totInput = document.getElementById(`ese-input-${regNo}-total`);
            if (totInput) totInput.value = total.toFixed(1);

            updateEseRowGrade(regNo, total, false);

            const curSel = document.getElementById('ese-student-select')?.value;
            if (curSel === regNo) {
                calculateEseLiveTotal(regNo);
                const slider = document.getElementById(`ese-slider-${key}`);
                if (slider) slider.value = num;
                const numInp = document.getElementById(`ese-num-${key}`);
                if (numInp) numInp.value = num;
            }

            triggerDebouncedEseAutoSave(regNo);
        }

        function onEseTableTotalInput(regNo, val) {
            const total = Math.min(40, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};

            if (total <= 0) {
                eseSplitupState[regNo].writeup = 0;
                eseSplitupState[regNo].setup = 0;
                eseSplitupState[regNo].result = 0;
                eseSplitupState[regNo].viva = 0;
                eseSplitupState[regNo].record = 0;
            } else {
                eseSplitupState[regNo].is_absent = false;
                const factor = total / 40.0;
                const w = Math.min(10, Math.round(10 * factor * 2) / 2);
                const s = Math.min(10, Math.round(10 * factor * 2) / 2);
                const o = Math.min(8, Math.round(8 * factor * 2) / 2);
                const v = Math.min(8, Math.round(8 * factor * 2) / 2);
                const r = Math.min(4, Math.max(0, Math.round((total - (w + s + o + v)) * 2) / 2));

                eseSplitupState[regNo].writeup = w;
                eseSplitupState[regNo].setup = s;
                eseSplitupState[regNo].result = o;
                eseSplitupState[regNo].viva = v;
                eseSplitupState[regNo].record = r;
            }

            syncAllToEseTableRow(regNo);

            const curSel = document.getElementById('ese-student-select')?.value;
            if (curSel === regNo) {
                loadEseStudent(regNo);
            }

            triggerDebouncedEseAutoSave(regNo);
        }

        function syncAllToEseTableRow(regNo) {
            const data = eseSplitupState[regNo] || {};
            const isAbsent = !!data.is_absent;
            const w = isAbsent ? 0 : (data.writeup || 0);
            const s = isAbsent ? 0 : (data.setup || 0);
            const o = isAbsent ? 0 : (data.result || 0);
            const v = isAbsent ? 0 : (data.viva || 0);
            const r = isAbsent ? 0 : (data.record || 0);
            const total = w + s + o + v + r;

            const wInp = document.getElementById(`ese-input-${regNo}-writeup`); if (wInp) wInp.value = w.toFixed(1);
            const sInp = document.getElementById(`ese-input-${regNo}-setup`); if (sInp) sInp.value = s.toFixed(1);
            const oInp = document.getElementById(`ese-input-${regNo}-result`); if (oInp) oInp.value = o.toFixed(1);
            const vInp = document.getElementById(`ese-input-${regNo}-viva`); if (vInp) vInp.value = v.toFixed(1);
            const rInp = document.getElementById(`ese-input-${regNo}-record`); if (rInp) rInp.value = r.toFixed(1);
            const totInp = document.getElementById(`ese-input-${regNo}-total`); if (totInp) totInp.value = total.toFixed(1);

            updateEseRowGrade(regNo, total, isAbsent);
        }

        function updateEseRowGrade(regNo, total, isAbsent) {
            const cell = document.getElementById(`ese-grade-cell-${regNo}`);
            if (!cell) return;

            if (isAbsent) {
                cell.innerHTML = '<span class="px-2 py-0.5 rounded-full border text-[11px] font-bold text-rose-400 bg-rose-500/10 border-rose-500/30">AB</span>';
                return;
            }

            const pct = (total / 40.0) * 100.0;
            let g = 'F', gc = 'text-rose-400 bg-rose-500/10 border-rose-500/30';
            if (pct >= 90) { g = 'S'; gc = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30'; }
            else if (pct >= 80) { g = 'A'; gc = 'text-blue-400 bg-blue-500/10 border-blue-500/30'; }
            else if (pct >= 70) { g = 'B'; gc = 'text-sky-400 bg-blue-500/10 border-blue-500/30'; }
            else if (pct >= 60) { g = 'C'; gc = 'text-sky-400 bg-sky-500/10 border-sky-500/30'; }
            else if (pct >= 50) { g = 'D'; gc = 'text-amber-400 bg-amber-500/10 border-amber-500/30'; }
            else if (pct >= 40) { g = 'E'; gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }

            cell.innerHTML = `<span class="px-2 py-0.5 rounded-full border text-[11px] font-bold ${gc}">${g}</span>`;
        }

        function calculateEseLiveTotal(regNo) {
            const data = eseSplitupState[regNo] || {};
            const isAbsent = !!data.is_absent;
            const total = isAbsent ? 0 : ((data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0));

            const rawElem = document.getElementById('ese-student-total-raw');
            const gradeBadge = document.getElementById('ese-student-grade-badge');

            if (rawElem) rawElem.innerText = isAbsent ? 'ABSENT (0.00 / 40.00 M)' : `${total.toFixed(1)} / 40.00 M`;

            if (gradeBadge) {
                if (isAbsent) {
                    gradeBadge.innerText = 'AB';
                    gradeBadge.className = 'font-bold text-rose-400 text-sm font-mono px-2 py-0.5 rounded bg-rose-500/10 border border-rose-500/20';
                } else {
                    const pct = (total / 40.0) * 100.0;
                    let grade = 'F';
                    let gClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                    if (pct >= 90) { grade = 'S'; gClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20'; }
                    else if (pct >= 80) { grade = 'A'; gClass = 'text-blue-400 bg-blue-500/10 border-blue-500/20'; }
                    else if (pct >= 70) { grade = 'B'; gClass = 'text-sky-400 bg-blue-500/10 border-blue-500/20'; }
                    else if (pct >= 60) { grade = 'C'; gClass = 'text-sky-400 bg-sky-500/10 border-sky-500/20'; }
                    else if (pct >= 50) { grade = 'D'; gClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20'; }
                    else if (pct >= 40) { grade = 'E'; gClass = 'text-orange-400 bg-orange-500/10 border-orange-500/20'; }

                    gradeBadge.innerText = grade;
                    gradeBadge.className = `font-bold text-sm font-mono px-2 py-0.5 rounded border ${gClass}`;
                }
            }
        }

        function triggerDebouncedEseAutoSave(regNo) {
            if (eseAutoSaveTimers[regNo]) {
                clearTimeout(eseAutoSaveTimers[regNo]);
            }
            eseAutoSaveTimers[regNo] = setTimeout(() => {
                saveSingleEseStudentMarks(regNo);
            }, 750);
        }

        function saveSingleEseStudentMarks(regNo) {
            const data = eseSplitupState[regNo] || {};
            const isAbsent = !!data.is_absent;
            const total = isAbsent ? 0 : ((data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0));

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    marks_data: [
                        {
                            reg_no: regNo,
                            ese_practical_marks: total,
                            practical_absent: isAbsent
                        }
                    ]
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    showPracticalEseAutoSaveToast();
                }
            })
            .catch(err => {
                console.error('Practical ESE Auto-save error:', err);
            });
        }

        function showPracticalEseAutoSaveToast() {
            let toast = document.getElementById('ese-autosave-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'ese-autosave-toast';
                toast.className = 'fixed bottom-4 right-4 z-50 px-3 py-2 rounded-lg bg-emerald-950/90 border border-emerald-500/40 text-emerald-300 text-xs font-semibold shadow-xl transition-all duration-300 flex items-center space-x-2';
                document.body.appendChild(toast);
            }
            toast.innerHTML = '<span>✓ Practical ESE Auto-saved</span>';
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.opacity = '0';
            }, 2000);
        }

        function prevEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel || sel.selectedIndex <= 0) return;
            sel.selectedIndex--;
            loadEseStudent(sel.value);
        }

        function nextEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
            sel.selectedIndex++;
            loadEseStudent(sel.value);
        }

        function saveAndNextEseStudent() {
            const sel = document.getElementById('ese-student-select');
            if (!sel) return;
            saveSingleEseStudentMarks(sel.value);
            if (sel.selectedIndex < sel.options.length - 1) {
                sel.selectedIndex++;
                loadEseStudent(sel.value);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'End of List',
                    text: 'Reached last student in list.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        function saveAllEseMarks() {
            const marksData = studentsList.map(st => {
                const data = eseSplitupState[st.reg_no] || { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
                const isAbsent = !!data.is_absent;
                const totalScore = isAbsent ? 0 : ((data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0));
                return {
                    reg_no: st.reg_no,
                    ese_practical_marks: totalScore,
                    practical_absent: isAbsent
                };
            });

            Swal.fire({
                title: 'Saving Practical ESE Marks...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeEsePracticalModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: 'Practical ESE marks and grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE marks', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function openEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeEseTheoryModal() {
            const modal = document.getElementById('ese-theory-modal');
            if (modal) modal.classList.add('hidden');
        }

        function saveAllEseTheoryGrades() {
            const marksData = studentsList.map(st => {
                const elem = document.getElementById('ese-theory-grade-' + st.reg_no);
                return {
                    reg_no: st.reg_no,
                    ese_theory_grade: elem ? elem.value : ''
                };
            });

            Swal.fire({
                title: 'Saving Theory ESE Grades...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/ese', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ marks_data: marksData })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    closeEseTheoryModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved Successfully!',
                        text: 'Board Theory ESE grades saved!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to save ESE grades', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function printSubtabReport(reportTitle, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const clone = container.cloneNode(true);
            
            // Replace input fields with readable text spans containing active values
            const origInputs = container.querySelectorAll('input');
            const cloneInputs = clone.querySelectorAll('input');
            origInputs.forEach((orig, idx) => {
                const cloneInput = cloneInputs[idx];
                if (cloneInput) {
                    const span = document.createElement('span');
                    span.className = 'font-mono text-xs font-semibold text-black';
                    span.innerText = orig.value !== undefined ? orig.value : '';
                    cloneInput.parentNode.replaceChild(span, cloneInput);
                }
            });

            // Remove non-printable elements, buttons, dropdowns, QP generator cards
            clone.querySelectorAll('button, select, input, .no-print, #qp-gen-status').forEach(el => el.remove());

            const collegeName = "CARMEL COLLEGE OF ENGINEERING & TECHNOLOGY, ALAPPUZHA";
            const branchName = @json(function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch));
            const subjectName = @json($batchSubject->subject_name);
            const subjectCode = @json($batchSubject->subject_code);
            const batchCode = @json($batchSubject->classroom_id);
            const semester = @json($practicumCourseFile->semester);
            const facultyName = @json(Session::get('userName') ?? 'Faculty In-Charge');
            const eseMarks = @json($practicumCourseFile->ese_marks);
            const todayStr = new Date().toLocaleDateString('en-GB');

            const isPortrait = reportTitle.toLowerCase().includes('continuous assessment') || reportTitle.toLowerCase().includes('ca mark');

            const printWin = window.open('', '_blank', 'width=1150,height=850');
            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${reportTitle} - ${subjectName}</title>
                    <style>
                        @page {
                            size: ${isPortrait ? 'A4 portrait' : 'A4 landscape'};
                            margin: 12mm 10mm 12mm 10mm;
                        }
                        body {
                            font-family: 'Times New Roman', Times, serif;
                            color: #000;
                            background: #fff;
                            margin: 0;
                            padding: 10px;
                            font-size: 11px;
                            line-height: 1.35;
                        }
                        .header-container {
                            text-align: center;
                            border-bottom: 2px double #000;
                            padding-bottom: 8px;
                            margin-bottom: 12px;
                        }
                        .college-title {
                            font-size: 17px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 3px;
                            color: #000;
                            letter-spacing: 0.5px;
                        }
                        .dept-title {
                            font-size: 12px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin-bottom: 4px;
                            color: #111;
                        }
                        .report-badge {
                            font-size: 13px;
                            font-weight: bold;
                            text-transform: uppercase;
                            margin: 6px 0;
                            color: #000;
                            text-decoration: underline;
                        }
                        .meta-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 8px;
                            margin-bottom: 12px;
                            font-size: 11px;
                        }
                        .meta-table td {
                            padding: 5px 8px;
                            border: 1px solid #000;
                            width: 50%;
                            color: #000;
                            background: #fafafa;
                        }
                        .meta-table td strong {
                            color: #000;
                        }
                        /* FORCE CLEAN PRINTABLE CARDS & GRID LAYOUTS */
                        .print-content * {
                            box-shadow: none !important;
                            text-shadow: none !important;
                        }
                        .print-content .glass-card, .print-content div[class*="border"] {
                            border: 1px solid #000 !important;
                            background: #ffffff !important;
                            padding: 10px !important;
                            margin-bottom: 12px !important;
                            border-radius: 4px !important;
                        }
                        .print-content p, .print-content span, .print-content h3, .print-content h4, .print-content div, .print-content label {
                            color: #000 !important;
                        }
                        table {
                            width: 100% !important;
                            border-collapse: collapse !important;
                            margin-top: 8px !important;
                            margin-bottom: 12px !important;
                            page-break-inside: auto;
                        }
                        tr {
                            page-break-inside: avoid;
                            page-break-after: auto;
                        }
                        th {
                            border: 1px solid #000 !important;
                            padding: 6px 6px !important;
                            background-color: #f1f5f9 !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            font-weight: bold !important;
                            text-transform: uppercase !important;
                            text-align: center !important;
                        }
                        td {
                            border: 1px solid #000 !important;
                            padding: 5px 6px !important;
                            color: #000 !important;
                            font-size: 11px !important;
                            background: #fff !important;
                        }
                        td.text-center, th.text-center {
                            text-align: center !important;
                        }
                        .signatures-table {
                            width: 100%;
                            margin-top: 45px;
                            page-break-inside: avoid;
                        }
                        .signatures-table td {
                            width: 33.33%;
                            text-align: center;
                            padding-top: 5px;
                            font-weight: bold;
                            font-size: 12px;
                            border: none !important;
                            border-top: 1px solid #000 !important;
                            background: transparent !important;
                            color: #000 !important;
                        }
                        .footer-note {
                            margin-top: 20px;
                            font-size: 9px;
                            text-align: right;
                            color: #555;
                            border-top: 1px dashed #ccc;
                            padding-top: 4px;
                        }
                        @media print {
                            body { padding: 0; margin: 0; }
                            button, select, input, .no-print { display: none !important; }
                            .meta-table td, th {
                                background-color: #f1f5f9 !important;
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header-container">
                        <div class="college-title">${collegeName}</div>
                        <div class="dept-title">DEPARTMENT OF ${branchName.toUpperCase()}</div>
                        <div class="report-badge">${reportTitle}</div>
                        
                        <table class="meta-table">
                            <tr>
                                <td><strong>Course Name & Code:</strong> ${subjectName} (${subjectCode})</td>
                                <td><strong>Batch Code:</strong> ${batchCode}</td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong> ${branchName}</td>
                                <td><strong>Semester / Scheme:</strong> Semester ${semester} (Rev 2026)</td>
                            </tr>
                            <tr>
                                <td><strong>Assessment Year:</strong> 2026 – 2027</td>
                                <td><strong>Date of Report:</strong> ${todayStr}</td>
                            </tr>
                            <tr>
                                <td><strong>Faculty In-Charge:</strong> ${facultyName}</td>
                                <td><strong>Evaluation Scheme:</strong> CIA: 40 Marks | Theory ESE: ${eseMarks} Marks</td>
                            </tr>
                        </table>
                    </div>

                    <div class="print-content">
                        ${clone.innerHTML}
                    </div>

                    <table class="signatures-table">
                        <tr>
                            <td>Signature of Faculty In-Charge</td>
                            <td>Signature of Head of Department (HOD)</td>
                            <td>Signature of Principal</td>
                        </tr>
                    </table>

                    <div class="footer-note">
                        Generated via Practicum Virtual Classroom System • Carmel College of Engineering & Technology, Alappuzha
                    </div>

                    <script>
                        window.onload = function() {
                            setTimeout(function() { window.print(); }, 400);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWin.document.close();
        }

        function openMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.remove('hidden');
        }
        function closeMidsemInitModal() {
            document.getElementById('modal-midsem-survey-init-practicum').classList.add('hidden');
        }
        function openExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.remove('hidden');
        }
        function closeExitInitModal() {
            document.getElementById('modal-exit-survey-init-practicum').classList.add('hidden');
        }

        function submitPracticumMidsemInit(event) {
            event.preventDefault();
            const questions = {
                q5: document.getElementById('p-ms-q5').value.trim(),
                q6: document.getElementById('p-ms-q6').value.trim(),
                q7: document.getElementById('p-ms-q7').value.trim(),
                q8: document.getElementById('p-ms-q8').value.trim(),
                q9: document.getElementById('p-ms-q9').value.trim(),
                q10: document.getElementById('p-ms-q10').value.trim(),
                q11: document.getElementById('p-ms-q11').value.trim(),
                q12: document.getElementById('p-ms-q12').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/survey/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Mid-Semester survey initiated successfully and sent to student portal!', 'success');
                    closeMidsemInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function submitPracticumExitInit(event) {
            event.preventDefault();
            const questions = {
                q1: document.getElementById('p-ex-q1').value.trim(),
                q2: document.getElementById('p-ex-q2').value.trim(),
                q3: document.getElementById('p-ex-q3').value.trim(),
                q4: document.getElementById('p-ex-q4').value.trim(),
                q5: document.getElementById('p-ex-q5').value.trim(),
                q6: document.getElementById('p-ex-q6').value.trim(),
                q7: document.getElementById('p-ex-q7').value.trim(),
                q8: document.getElementById('p-ex-q8').value.trim()
            };

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ questions })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Published!', 'Course Exit survey initiated successfully! Students notified in their Works To Do.', 'success');
                    closeExitInitModal();
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message || 'Failed to initiate survey', 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function controlPracticumSurvey(type, action) {
            const endpoint = type === 'midsem' ? '/api/classroom/{{ $batchSubject->id }}/survey/' + action : '/api/classroom/{{ $batchSubject->id }}/course-exit/' + action;
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    Swal.fire('Updated!', data.message, 'success');
                    checkPracticumSurveyStatuses();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }

        function checkPracticumSurveyStatuses() {
            fetch('/api/classroom/{{ $batchSubject->id }}/survey/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('midsem-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-midsem-practicum');
                    const closeBtn = document.getElementById('btn-close-midsem-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});

            fetch('/api/classroom/{{ $batchSubject->id }}/course-exit/results')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('exit-practicum-status-badge');
                    const openBtn = document.getElementById('btn-open-exit-practicum');
                    const closeBtn = document.getElementById('btn-close-exit-practicum');
                    if (data.status === 'INACTIVE') {
                        if (badge) { badge.innerText = 'Not Initiated'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700'; }
                        if (openBtn) openBtn.classList.remove('hidden');
                        if (closeBtn) closeBtn.classList.add('hidden');
                    } else if (data.data && data.data.survey) {
                        const st = data.data.survey.status;
                        if (st === 'Active') {
                            if (badge) { badge.innerText = 'Active (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/40'; }
                            if (openBtn) openBtn.classList.add('hidden');
                            if (closeBtn) closeBtn.classList.remove('hidden');
                        } else {
                            if (badge) { badge.innerText = 'Closed / Locked (' + data.data.responded_count + ' Submitted)'; badge.className = 'px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700'; }
                            if (openBtn) openBtn.classList.remove('hidden');
                            if (closeBtn) closeBtn.classList.add('hidden');
                        }
                    }
                }).catch(() => {});
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkPracticumSurveyStatuses();
        });
    </script>

    <!-- MODAL: MID-SEM SURVEY INITIATION PREVIEW & EDIT -->
    <div id="modal-midsem-survey-init-practicum" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200 p-4 backdrop-blur-sm">
      <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-sky-400">rate_review</span>
            <span>Preview & Edit Mid-Semester Survey Questions</span>
          </h3>
          <button type="button" onclick="closeMidsemInitModal()" class="text-slate-400 hover:text-white cursor-pointer bg-transparent border-0 text-xl">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-400 leading-relaxed">
          Review or edit the survey questions below before activating. Once published, active survey notifications will automatically appear on the student dashboard ("Works to do").
        </p>

        <form id="form-midsem-init-practicum" onsubmit="submitPracticumMidsemInit(event)" class="space-y-4">
          <div class="space-y-3">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q1. CO1 - Course Outcomes Communication</label>
              <input type="text" id="p-ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
              <input type="text" id="p-ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q3. CO2 - Concept Clarity & Application</label>
              <input type="text" id="p-ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q4. CO2 - Effectiveness of Teaching & Lab Demonstrations</label>
              <input type="text" id="p-ms-q8" value="The use of teaching tools, animations, lab demonstrations, or ICT tools is effective." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q5. CO3 - Doubt Clearing & Classroom Interaction</label>
              <input type="text" id="p-ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q6. CO3 - Test & Practical Assignment Relevance</label>
              <input type="text" id="p-ms-q10" value="Internal assessment test questions and practical assignments match the topics taught in class." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q7. CO4 - Fairness in Evaluation</label>
              <input type="text" id="p-ms-q11" value="Evaluation of mid-semester tests or practical submissions is fair, timely, and transparent." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q8. CO4 - Guidance & Support for Students</label>
              <input type="text" id="p-ms-q12" value="The teacher provides extra guidance, remedial tips, or support to students needing assistance." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
            <button type="button" onclick="closeMidsemInitModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold text-xs shadow-md">Activate & Publish Survey</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
    <div id="modal-exit-survey-init-practicum" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200 p-4 backdrop-blur-sm">
      <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b border-slate-800 pb-3">
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-teal-400">assignment_turned_in</span>
            <span>Preview & Edit Course Exit Survey Questions</span>
          </h3>
          <button type="button" onclick="closeExitInitModal()" class="text-slate-400 hover:text-white cursor-pointer bg-transparent border-0 text-xl">
            &times;
          </button>
        </div>
        
        <p class="text-xs text-slate-400 leading-relaxed">
          Review or edit the Course Exit questions below before activating. Students will submit responses to calculate Indirect CO Attainment.
        </p>

        <form id="form-exit-init-practicum" onsubmit="submitPracticumExitInit(event)" class="space-y-4">
          <div class="space-y-3">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q1. CO1 - Theoretical Principles & Fundamentals</label>
              <input type="text" id="p-ex-q1" value="How well did the course help you understand and remember core academic principles, models, and structural fundamentals?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q2. CO1 - Outcome & Syllabus Alignment</label>
              <input type="text" id="p-ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with class lectures and lab demonstrations?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q3. CO2 - Analytical Ability & Logic</label>
              <input type="text" id="p-ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q4. CO2 - Design & Troubleshooting Skills</label>
              <input type="text" id="p-ex-q4" value="To what extent can you design models, troubleshoot bugs, or conduct lab experiments based on class lessons?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q5. CO3 - Modern Tools & Practical Execution</label>
              <input type="text" id="p-ex-q5" value="How confident are you in using modern software, lab apparatus, or engineering software for tasks?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q6. CO3 - Problem Solving in Field & Lab</label>
              <input type="text" id="p-ex-q6" value="How effectively can you apply core theoretical principles to solve practical or field problems?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q7. CO4 - Ethics, Teamwork & Professional Conduct</label>
              <input type="text" id="p-ex-q7" value="Did the course foster professional ethics, group collaboration, and responsible work habits?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q8. CO4 - Communication & Report Writing</label>
              <input type="text" id="p-ex-q8" value="How well did the course improve your technical documentation, presentation skills, and report writing?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-blue-500 outline-none">
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
            <button type="button" onclick="closeExitInitModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-lg font-bold text-xs shadow-md">Activate & Publish Survey</button>
          </div>
        </form>
      </div>
    </div>

 </body>
 </html>
