<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carmel Linx - [R-2026] Virtual English Classroom (1001) - {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</title>

    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            --bg-primary: #0f172a;
            --bg-secondary: #020617;
            --bg-card: #0f172a;
            --bg-card-hover: #1e293b;
            --border-color: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        *, *::before, *::after, h1, h2, h3, h4, h5, h6, .font-heading, .brand-font, span, p, label, button, a, th, td, div, strong, b, input, select, textarea {
            text-shadow: none !important;
            filter: none !important;
            -webkit-filter: none !important;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, .brand-font {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.01em;
            font-weight: 600 !important;
        }

        .glass-panel {
            background: rgba(2, 6, 23, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.90);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.40);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tab-nav-container {
            position: sticky;
            top: 76px;
            z-index: 35;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .subtab-btn {
            position: relative;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .subtab-btn:hover {
            color: #f1f5f9;
        }

        .subtab-btn.active {
            color: #38bdf8 !important;
            border-color: #38bdf8 !important;
            border-bottom-color: transparent !important;
            background-color: rgba(56, 189, 248, 0.08) !important;
            box-shadow: 0 -4px 10px rgba(56, 189, 248, 0.18) !important;
            font-weight: 700 !important;
        }

        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            background-color: #020617 !important;
            border: 1px solid #334155 !important;
            color: #f1f5f9 !important;
            border-radius: 8px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.28) !important;
            outline: none !important;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button,
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type="number"], .no-spinners {
            -moz-appearance: textfield !important;
        }

        /* Enlarged Mark Entry Input Styling */
        .mark-entry-lg {
            width: 6.25rem !important; /* 100px */
            height: 2.35rem !important; /* 38px */
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            padding: 0.35rem 0.5rem !important;
            text-align: center !important;
        }

        .mark-entry-cia {
            width: 4.25rem !important; /* 68px */
            height: 2.15rem !important; /* 34px */
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            padding: 0.25rem 0.35rem !important;
            text-align: center !important;
        }

        table th {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.6rem !important;
        }

        table td {
            font-size: 0.825rem !important;
            padding: 0.45rem 0.6rem !important;
        }

        /* Growable Textarea for Lesson Plan Topic */
        .growable-textarea {
            resize: vertical;
            overflow-y: hidden;
            min-height: 44px;
            line-height: 1.45;
            font-size: 0.84rem !important;
            white-space: pre-wrap;
            word-wrap: break-word;
            width: 100%;
            display: block;
        }

        input[type="number"].cell-att-override.has-override,
        input[type="number"].cell-att-override.has-override:focus,
        .cell-att-override.has-override {
            border: 2px solid #f59e0b !important;
            border-color: #f59e0b !important;
            background-color: rgba(245, 158, 11, 0.22) !important;
            color: #fbbf24 !important;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.38) !important;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen pb-14">

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

    <!-- 1. TOP HEADER NAVIGATION BAR WITH CARMEL LINX LOGO & BRAND -->
    <header class="glass-panel sticky top-0 z-40 border-b border-slate-800 px-4 md:px-8 py-3.5 min-h-[76px] flex items-center">
        <div class="max-w-[99%] mx-auto flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3 w-full">

            <!-- Left: Carmel Linx Logo + Brand Name + Subject Title -->
            <div class="flex items-center space-x-3.5 w-full xl:w-auto">
                <div class="flex flex-col items-start shrink-0">
                    <a href="{{ $dashboardUrl }}" class="flex items-center gap-2 shrink-0 no-underline text-white group" title="Carmel Linx Academic Platform">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-sky-500 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-white text-xl">school</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-white text-base md:text-lg tracking-tight group-hover:text-emerald-300 transition-colors leading-none">Carmel Linx</span>
                            <span class="text-[10px] text-emerald-400 font-bold tracking-wider uppercase mt-0.5">Academic ERP</span>
                        </div>
                    </a>
                </div>

                <div class="h-10 w-px bg-slate-800 hidden sm:block shrink-0 mx-1"></div>

                <div class="flex flex-col">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-sky-500/20 text-sky-300 border border-sky-500/40 text-[11px] font-bold tracking-wide">
                            Revision 2026 • English Practicum Model (1001)
                        </span>
                        <h1 class="text-base md:text-xl font-bold text-white tracking-tight flex items-center flex-wrap gap-1.5 mb-0">
                            <span class="font-black text-amber-300 font-mono">{{ $batchSubject->subject_code }}</span>
                            <span class="text-slate-500 font-normal">|</span>
                            <span>{{ $batchSubject->subject_name }}</span>
                        </h1>
                    </div>
                    <p class="text-xs text-slate-400 mb-0 mt-0.5">
                        Virtual English Communication Classroom • Batch: <strong class="text-slate-200 font-mono">{{ $batchSubject->classroom_id }}</strong>
                    </p>
                </div>
            </div>

            <!-- Right: Faculty Name, Fullscreen & Dashboard -->
            <div class="flex items-center space-x-3 flex-wrap">
                <!-- Faculty Name Display -->
                <div class="flex items-center space-x-2 text-slate-300 text-xs sm:text-sm">
                    <div class="p-1 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center shrink-0" style="width: 26px; height: 26px;">
                        <span class="material-symbols-rounded text-base">person</span>
                    </div>
                    <div class="font-semibold">
                        <span class="text-blue-300">Faculty:</span>
                        <span class="text-blue-400 font-bold">
                            {{ $assignedStaff->pluck('name')->implode(', ') ?: (Session::get('userName') ?? 'Course Faculty') }}
                        </span>
                    </div>
                </div>

                <!-- Fullscreen Toggle -->
                <button onclick="toggleFullscreen()" class="p-2 rounded-lg bg-slate-800/90 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition-all cursor-pointer" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-base">fullscreen</span>
                </button>

                <!-- Dashboard Return -->
                <a href="javascript:void(0)" onclick="closeTabOrGoDashboard()" class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 font-bold transition-all flex items-center space-x-1.5 border border-rose-500/30 cursor-pointer no-underline text-xs" title="Return to Dashboard">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. ENLARGED SUB-HEADER TITLE CARD & METADATA CONSOLE -->
    <div class="max-w-[99%] mx-auto px-4 md:px-8 mt-4">
        <div class="glass-card p-5 sm:p-6 rounded-2xl border border-slate-800 min-h-[118px] flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5 text-sm sm:text-base text-slate-200">
                    <span class="text-amber-300 font-extrabold font-mono">{{ $batchSubject->classroom_id }}</span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span class="text-sky-300 font-semibold">{{ $classroom->department ?? $classroom->branch }}</span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span class="text-slate-300">Semester {{ $englishCourseFile->semester ?? 'I' }}</span>
                </div>

                <div class="flex items-center flex-wrap gap-x-3 gap-y-1 text-xs sm:text-sm text-slate-400">
                    <span>Theory: <strong class="text-slate-200">30 Hrs (L)</strong></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Practicum: <strong class="text-slate-200">30 Hrs (P)</strong></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Total Schedule: <strong class="text-slate-200">60 Hrs</strong></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>CIA: <strong class="text-slate-200">40M</strong> (Att: 5M, CA1: 5M, CE: 10M, CA2: 10M, CA4: 10M) <span class="text-slate-600">|</span> ESE Lab: <strong class="text-slate-200">60M</strong> (Total: <strong class="text-slate-200">100M</strong>)</span>
                </div>
            </div>

            <!-- Header Quick Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                <button onclick="openTeamsAttendanceModal()" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 border border-indigo-400/40 text-white font-bold transition-all flex items-center space-x-1.5 text-xs cursor-pointer shadow-md">
                    <span class="material-symbols-rounded text-base">upload_file</span>
                    <span>Upload TEAMS Attendance %</span>
                </button>
                <button onclick="openSyllabusModal()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 border border-sky-500 text-white font-bold transition-all flex items-center space-x-1.5 text-xs cursor-pointer shadow-md">
                    <span class="material-symbols-rounded text-base">cloud_upload</span>
                    <span>Upload Syllabus</span>
                </button>
                @if(!empty($englishCourseFile->syllabus_pdf_path))
                <a href="/storage/{{ str_replace('/storage/', '', $englishCourseFile->syllabus_pdf_path) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-cyan-300 hover:text-cyan-200 font-bold transition-all flex items-center space-x-1.5 text-xs no-underline shadow-md">
                    <span class="material-symbols-rounded text-base">picture_as_pdf</span>
                    <span>Syllabus PDF</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. MAIN ARCHED TABBED NAVIGATION (NO SERIAL NUMBERS IN TABS + REPORTS TAB) -->
    <main class="max-w-[99%] mx-auto px-4 md:px-8 mt-4">

        <div class="tab-nav-container bg-slate-950/90 border border-slate-800/80 p-2 rounded-2xl shadow-lg mb-4">
            <nav class="flex flex-wrap md:flex-nowrap items-center gap-1.5 overflow-x-auto scrollbar-none no-scrollbar">

                <!-- Course Outline -->
                <button onclick="switchTab('outline')" id="tab-btn-outline" class="subtab-btn active px-3.5 py-2 text-xs font-bold border-t-2 border-x-2 border-b-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">menu_book</span>
                    <span>Course Outline</span>
                </button>

                <!-- Lesson Planner -->
                <button onclick="switchTab('lessonplan')" id="tab-btn-lessonplan" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">calendar_month</span>
                    <span>Lesson Planner</span>
                </button>

                <!-- Self Learning (CA1) -->
                <button onclick="switchTab('selflearning')" id="tab-btn-selflearning" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">psychology</span>
                    <span>Self Learning (CA1)</span>
                </button>

                <!-- Continuous Evaluation (CE) -->
                <button onclick="switchTab('ce')" id="tab-btn-ce" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">assignment_turned_in</span>
                    <span>Continuous Evaluation (CE)</span>
                </button>

                <!-- Practical Test (CA2 & CA3) -->
                <button onclick="switchTab('practicaltest')" id="tab-btn-practicaltest" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">mic</span>
                    <span>Practical Test</span>
                </button>

                <!-- Series Test (CA4 & CA5) -->
                <button onclick="switchTab('seriestest')" id="tab-btn-seriestest" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">edit_note</span>
                    <span>Series Test</span>
                </button>

                <!-- CIA Register -->
                <button onclick="switchTab('cia')" id="tab-btn-cia" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">fact_check</span>
                    <span>CIA Register</span>
                </button>

                <!-- Reports -->
                <button onclick="switchTab('reports')" id="tab-btn-reports" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">print</span>
                    <span>Reports</span>
                </button>

            </nav>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: COURSE OUTLINE                                                       -->
        <!-- ========================================================================= -->
        <div id="tab-content-outline" class="tab-pane-content space-y-5">

            <!-- Official Assessment Methodology - Practicum Table -->
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                    <h3 class="font-bold text-sm text-amber-400 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base">table_chart</span>
                        <span>Assessment Methodology — Practicum (1001: English for Communication — Rev 2026)</span>
                    </h3>
                    <span class="text-xs font-mono text-emerald-400 font-bold">Total: 40 CIA + 60 ESE = 100 Marks</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-center text-xs">
                        <thead>
                            <tr class="bg-slate-950 text-slate-200 font-bold">
                                <th rowspan="2" class="border border-slate-700 p-2 w-28">Mode</th>
                                <th rowspan="2" class="border border-slate-700 p-2 bg-blue-950/30 text-blue-300">Attendance</th>
                                <th class="border border-slate-700 p-2 bg-amber-950/30 text-amber-300">CA1</th>
                                <th class="border border-slate-700 p-2 bg-teal-950/30 text-teal-300">CE</th>
                                <th class="border border-slate-700 p-2 bg-purple-950/30 text-purple-300">CA2</th>
                                <th class="border border-slate-700 p-2 bg-purple-950/30 text-purple-300">CA3</th>
                                <th class="border border-slate-700 p-2 bg-sky-950/30 text-sky-300">CA4</th>
                                <th class="border border-slate-700 p-2 bg-sky-950/30 text-sky-300">CA5</th>
                                <th colspan="2" class="border border-slate-700 p-2 bg-rose-950/30 text-rose-300">ESE</th>
                            </tr>
                            <tr class="bg-slate-900/90 text-slate-300 text-[11px]">
                                <th class="border border-slate-700 p-2">Average of Self learning activities from each module (M1–M4)</th>
                                <th class="border border-slate-700 p-2">Continuous evaluation (M1–M4)</th>
                                <th class="border border-slate-700 p-2">Practical test (first half of experiments)</th>
                                <th class="border border-slate-700 p-2">Practical test (second half of experiments)</th>
                                <th class="border border-slate-700 p-2">Series Exam (2 modules)</th>
                                <th class="border border-slate-700 p-2">Series Exam (2 modules)</th>
                                <th class="border border-slate-700 p-2 text-slate-500">Written Exam (theory)</th>
                                <th class="border border-slate-700 p-2 text-rose-300">Lab Examination (internal)</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-xs">
                            <tr class="hover:bg-slate-800/40">
                                <td class="border border-slate-700 p-2 font-sans font-bold text-slate-300 text-left">Duration</td>
                                <td class="border border-slate-700 p-2 text-slate-500">—</td>
                                <td class="border border-slate-700 p-2 text-slate-500">—</td>
                                <td class="border border-slate-700 p-2 text-slate-500">—</td>
                                <td class="border border-slate-700 p-2 text-purple-300">3 hours</td>
                                <td class="border border-slate-700 p-2 text-purple-300">3 hours</td>
                                <td class="border border-slate-700 p-2 text-sky-300">2 hours</td>
                                <td class="border border-slate-700 p-2 text-sky-300">2 hours</td>
                                <td class="border border-slate-700 p-2 text-slate-600">—</td>
                                <td class="border border-slate-700 p-2 text-rose-300">2.5 hours</td>
                            </tr>
                            <tr class="hover:bg-slate-800/40">
                                <td class="border border-slate-700 p-2 font-sans font-bold text-slate-300 text-left">Exam mark</td>
                                <td class="border border-slate-700 p-2 text-slate-500">—</td>
                                <td class="border border-slate-700 p-2 font-bold text-amber-300">10</td>
                                <td class="border border-slate-700 p-2 font-bold text-teal-300">50</td>
                                <td class="border border-slate-700 p-2 font-bold text-purple-300">40</td>
                                <td class="border border-slate-700 p-2 font-bold text-purple-300">40</td>
                                <td class="border border-slate-700 p-2 font-bold text-sky-300">50</td>
                                <td class="border border-slate-700 p-2 font-bold text-sky-300">50</td>
                                <td class="border border-slate-700 p-2 text-slate-600">—</td>
                                <td class="border border-slate-700 p-2 font-bold text-rose-300">60</td>
                            </tr>
                            <tr class="hover:bg-slate-800/40">
                                <td class="border border-slate-700 p-2 font-sans font-bold text-slate-300 text-left">Converted to</td>
                                <td class="border border-slate-700 p-2 text-slate-500">—</td>
                                <td class="border border-slate-700 p-2 font-bold text-amber-400">5</td>
                                <td class="border border-slate-700 p-2 font-bold text-teal-400">10</td>
                                <td class="border border-slate-700 p-2 font-bold text-purple-400">10</td>
                                <td class="border border-slate-700 p-2 font-bold text-purple-400">10</td>
                                <td class="border border-slate-700 p-2 font-bold text-sky-400">10</td>
                                <td class="border border-slate-700 p-2 font-bold text-sky-400">10</td>
                                <td class="border border-slate-700 p-2 text-slate-600">—</td>
                                <td class="border border-slate-700 p-2 font-bold text-rose-400">60</td>
                            </tr>
                            <tr class="bg-slate-950/60">
                                <td class="border border-slate-700 p-2 font-sans font-bold text-slate-200 text-left">Marks</td>
                                <td class="border border-slate-700 p-2 font-extrabold text-blue-400">5</td>
                                <td class="border border-slate-700 p-2 font-extrabold text-amber-400">5</td>
                                <td class="border border-slate-700 p-2 font-extrabold text-teal-400">10</td>
                                <td colspan="2" class="border border-slate-700 p-2 font-extrabold text-purple-400">10</td>
                                <td colspan="2" class="border border-slate-700 p-2 font-extrabold text-sky-400">10</td>
                                <td class="border border-slate-700 p-2 text-slate-600">—</td>
                                <td class="border border-slate-700 p-2 font-extrabold text-rose-400">60</td>
                            </tr>
                            <tr class="bg-emerald-950/25">
                                <td class="border border-slate-700 p-2 font-sans font-bold text-emerald-300 text-left">Total</td>
                                <td colspan="7" class="border border-slate-700 p-2 font-extrabold text-sm text-emerald-400">40</td>
                                <td colspan="2" class="border border-slate-700 p-2 font-extrabold text-sm text-rose-400">60</td>
                            </tr>
                            <tr class="text-[11px] text-slate-400 font-sans">
                                <td class="border border-slate-700 p-2 font-bold text-left">Tentative schedule</td>
                                <td class="border border-slate-700 p-2">End of semester</td>
                                <td class="border border-slate-700 p-2">By 4th week</td>
                                <td class="border border-slate-700 p-2">By 7th week</td>
                                <td class="border border-slate-700 p-2">By 11th week</td>
                                <td class="border border-slate-700 p-2">By 14th week</td>
                                <td class="border border-slate-700 p-2">8th week</td>
                                <td class="border border-slate-700 p-2">15th week</td>
                                <td colspan="2" class="border border-slate-700 p-2">End of semester</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Modules & Practicum Exercises (Col Span 2) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="glass-card p-4 border border-slate-800">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                            <h3 class="font-bold text-sm text-sky-400 flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">format_list_bulleted</span>
                                <span>Course Modules (M1 – M4)</span>
                            </h3>
                            <span class="text-xs text-slate-400 font-mono">{{ $englishCourseFile->contact_hours ?? 60 }} Hours Total</span>
                        </div>
                        <div class="space-y-3">
                            @foreach(($englishCourseFile->parsed_modules ?? []) as $mod)
                            <div class="p-3 rounded-lg bg-slate-950/70 border border-slate-800">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-xs text-sky-300">Module {{ $mod['module_id'] }}: {{ $mod['title'] }}</h4>
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                        {{ $mod['hours'] ?? 15 }} Hours
                                    </span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed mb-0">{{ $mod['content'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Language Lab Practicum Exercises -->
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-purple-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">science</span>
                            <span>Language Lab Practicum Experiments / Activities</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-left text-xs">
                                <thead>
                                    <tr class="bg-slate-950/80 text-slate-400">
                                        <th class="border border-slate-800 w-20 text-center">Exp No</th>
                                        <th class="border border-slate-800 w-24 text-center">Module</th>
                                        <th class="border border-slate-800">Practicum Activity Title</th>
                                        <th class="border border-slate-800 w-16 text-center">CO</th>
                                        <th class="border border-slate-800 w-16 text-center">Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(($englishCourseFile->parsed_exercises ?? []) as $ex)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="border border-slate-800 text-center font-mono font-bold text-purple-300">{{ $ex['exercise_no'] }}</td>
                                        <td class="border border-slate-800 text-center text-slate-300">{{ $ex['module'] ?? '-' }}</td>
                                        <td class="border border-slate-800 text-slate-200 font-medium">{{ $ex['title'] }}</td>
                                        <td class="border border-slate-800 text-center font-bold text-amber-300">{{ $ex['co_id'] }}</td>
                                        <td class="border border-slate-800 text-center font-mono">{{ $ex['hours'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Course Outcomes, CO-PO Matrix & Textbooks (Col Span 1) -->
                <div class="space-y-4">
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-cyan-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">target</span>
                            <span>Course Outcomes (CO1 – CO4)</span>
                        </h3>
                        <div class="space-y-2.5">
                            @foreach(($englishCourseFile->parsed_cos ?? []) as $co)
                            <div class="p-2.5 rounded-lg bg-slate-950/70 border border-slate-800 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-amber-300">{{ $co['id'] }}</span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-300">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                                </div>
                                <p class="text-slate-300 leading-snug mb-0">{{ $co['description'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-amber-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">grid_on</span>
                            <span>CO - PO Articulation Matrix</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-center border-collapse">
                                <thead>
                                    <tr class="bg-slate-950/80 text-slate-400">
                                        <th class="border border-slate-800 p-1.5 text-xs text-left">CO</th>
                                        @for($p = 1; $p <= 7; $p++)
                                        <th class="border border-slate-800 p-1.5 text-xs font-mono">PO{{ $p }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                    <tr class="hover:bg-slate-800/40">
                                        <td class="border border-slate-800 p-1.5 text-xs font-bold text-left text-cyan-300">{{ $coTag }}</td>
                                        @for($p = 1; $p <= 7; $p++)
                                        @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                        <td class="border border-slate-800 p-1.5 text-xs {{ $val != '-' ? 'font-bold text-emerald-400' : 'text-slate-600' }}">{{ $val }}</td>
                                        @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-emerald-400 mb-2.5 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">auto_stories</span>
                            <span>References &amp; Textbooks</span>
                        </h3>
                        <ul class="text-xs text-slate-300 space-y-1.5 pl-4 list-disc">
                            @foreach(($englishCourseFile->parsed_textbooks ?? []) as $tb)
                            <li>{{ $tb }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: LESSON PLANNER (GROWABLE TOPIC FIELD + AUTO-SAVE ON ALL FIELDS)      -->
        <!-- ========================================================================= -->
        <div id="tab-content-lessonplan" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-amber-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">calendar_month</span>
                            <span>60-Hour English for Communication (1001) Lesson Planner &amp; Course Log</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">All fields auto-save automatically on edit. Topic field expands automatically as you type.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button onclick="generateLessonTimeline()" class="px-3 py-1.5 rounded-lg bg-sky-600/30 hover:bg-sky-600/50 border border-sky-500/40 text-sky-300 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">refresh</span>
                            <span>Reset / Generate Default</span>
                        </button>
                        <button onclick="saveLessonPlannerBulk(true)" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Planner</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left" id="lesson-plan-table">
                        <thead>
                            <tr class="bg-slate-950/80 text-slate-400">
                                <th class="border border-slate-800 text-center w-12">#</th>
                                <th class="border border-slate-800 w-36">Proposed Date</th>
                                <th class="border border-slate-800 w-36">Actual Date</th>
                                <th class="border border-slate-800 min-w-[320px]">Topic / Practicum Activity / Assessment Schedule (Growable)</th>
                                <th class="border border-slate-800 text-center w-24">Module</th>
                                <th class="border border-slate-800 text-center w-24">CO</th>
                                <th class="border border-slate-800 text-center w-20">Hrs</th>
                                <th class="border border-slate-800 w-52">Mode / Pedagogy</th>
                                <th class="border border-slate-800 text-center w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody id="lesson-plan-rows-container">
                            @forelse($lessonPlans as $lp)
                            <tr class="lesson-plan-row hover:bg-slate-800/30" data-id="{{ $lp->id }}">
                                <td class="border border-slate-800 text-center font-bold text-sky-400 lp-day-no">#{{ $lp->day_no }}</td>
                                <td class="border border-slate-800 p-1.5">
                                    <input type="date" value="{{ $lp->proposed_date }}" class="lp-proposed w-full px-2.5 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <input type="date" value="{{ $lp->actual_date }}" class="lp-actual w-full px-2.5 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <textarea class="growable-textarea lp-topic px-2.5 py-1.5 text-xs" rows="2" oninput="autoGrow(this); queueLessonPlanAutoSave();">{{ $lp->topic_content }}</textarea>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="lp-module w-full px-2 py-1.5 text-xs text-center font-bold text-sky-300" onchange="queueLessonPlanAutoSave()">
                                        @foreach(['M1', 'M2', 'M3', 'M4'] as $mId)
                                        <option value="{{ $mId }}" {{ ($lp->module_id ?? 'M1') == $mId ? 'selected' : '' }}>{{ $mId }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="lp-co w-full px-2 py-1.5 text-xs text-center font-bold text-amber-300" onchange="queueLessonPlanAutoSave()">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                        <option value="{{ $coId }}" {{ ($lp->co_tag ?? 'CO1') == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <input type="number" min="1" max="6" value="{{ $lp->allocated_hours ?: 2 }}" class="lp-hours no-spinners w-16 text-center px-2 py-1.5 text-xs font-bold" oninput="queueLessonPlanAutoSave()">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <select class="lp-pedagogy w-full px-2 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()">
                                        @php
                                            $pedOptions = [
                                                'Interactive Lecture (L)',
                                                'Language Lab Practicum (P)',
                                                'Self-Learning Review (CA1)',
                                                'Continuous Evaluation (CE)',
                                                'Practical Test 1 (CA2)',
                                                'Practical Test 2 (CA3)',
                                                'Series Exam 1 (CA4)',
                                                'Series Exam 2 (CA5)',
                                            ];
                                        @endphp
                                        @foreach($pedOptions as $pOpt)
                                        <option value="{{ $pOpt }}" {{ $lp->pedagogy == $pOpt ? 'selected' : '' }}>{{ $pOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="lp-status w-full px-2 py-1.5 text-xs font-semibold" onchange="queueLessonPlanAutoSave()">
                                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-slate-400 py-6 italic">No lesson plan rows found. Click Reset / Generate Default above.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-800">
                    <button onclick="addLessonPlanRow()" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">add</span>
                        <span>Add Session Row</span>
                    </button>
                    <button onclick="saveLessonPlannerBulk(true)" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save Lesson Planner</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: SELF LEARNING (CA1: M1, M2, M3, M4 each out of 10 -> Conv to 5M)     -->
        <!-- ========================================================================= -->
        <div id="tab-content-selflearning" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-amber-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">psychology</span>
                            <span>CA1: Self-Learning Activities Assessment (M1, M2, M3, M4 — Each Out of 10 &rarr; Converted to 5 Marks)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            Tentative Schedule: <strong>By 4th Week</strong> • Enter Self-Learning marks for Module 1–4 (Max <strong>10 Marks</strong> each). Automatically computes Average (out of 10), converts to <strong>5 Marks</strong>, and auto-saves immediately.
                        </p>
                    </div>
                    <span class="text-xs text-emerald-400 font-bold">⚡ Auto-Saves on Typing</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-950/90 text-slate-300">
                                <th class="border border-slate-800 text-center w-12">Roll</th>
                                <th class="border border-slate-800 w-32">Reg No</th>
                                <th class="border border-slate-800 min-w-[180px]">Student Name</th>
                                <th class="border border-slate-800 text-center w-32 bg-amber-950/30 text-amber-300">M1 Self Learning<br><span class="text-[11px] font-mono">(Max 10)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-amber-950/30 text-amber-300">M2 Self Learning<br><span class="text-[11px] font-mono">(Max 10)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-amber-950/30 text-amber-300">M3 Self Learning<br><span class="text-[11px] font-mono">(Max 10)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-amber-950/30 text-amber-300">M4 Self Learning<br><span class="text-[11px] font-mono">(Max 10)</span></th>
                                <th class="border border-slate-800 text-center w-28 bg-slate-900 text-slate-200">Average<br><span class="text-[11px] font-mono">(Out of 10)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-emerald-950/40 text-emerald-300 font-bold">CA1 Converted<br><span class="text-[11px] font-mono">(Out of 5 Marks)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="sl-tab-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center font-mono text-sky-400">{{ $st['roll_no'] ?: '-' }}</td>
                                <td class="border border-slate-800 font-mono text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 font-semibold text-white">{{ $st['name'] }}</td>
                                @foreach(['m1' => 'M1', 'm2' => 'M2', 'm3' => 'M3', 'm4' => 'M4'] as $mKey => $mLabel)
                                @php $colName = "sl_{$mKey}_10"; @endphp
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="10"
                                           value="{{ $st[$colName] !== null ? $st[$colName] : '' }}"
                                           data-max="10"
                                           data-field-name="Self Learning {{ $mLabel }} (Out of 10)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-sl-{{ $mKey }}"
                                           placeholder="-"
                                           class="tab-sl-{{ $mKey }} mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-sl-{{ $mKey }}', this.value); queueAutoSave();">
                                </td>
                                @endforeach
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-slate-200 tab-sl-avg-10">
                                    {{ number_format($st['sl_avg_10'] ?? 0, 2) }}
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-extrabold text-base text-emerald-400 bg-emerald-950/20 tab-sl-conv-5">
                                    {{ number_format($st['sl_conv_5'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: CONTINUOUS EVALUATION (CE: M1, M2, M3, M4 each out of 50 -> 10M)     -->
        <!-- ========================================================================= -->
        <div id="tab-content-ce" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-teal-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">assignment_turned_in</span>
                            <span>CE: Continuous Evaluation (M1, M2, M3, M4 — Each Out of 50 &rarr; Converted to 10 Marks)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            Tentative Schedule: <strong>By 7th Week</strong> • Enter Continuous Evaluation marks for Module 1–4 (Max <strong>50 Marks</strong> each). Automatically computes Average (out of 50), converts to <strong>10 Marks</strong>, and auto-saves immediately.
                        </p>
                    </div>
                    <span class="text-xs text-emerald-400 font-bold">⚡ Auto-Saves on Typing</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-950/90 text-slate-300">
                                <th class="border border-slate-800 text-center w-12">Roll</th>
                                <th class="border border-slate-800 w-32">Reg No</th>
                                <th class="border border-slate-800 min-w-[180px]">Student Name</th>
                                <th class="border border-slate-800 text-center w-32 bg-teal-950/30 text-teal-300">M1 CE Mark<br><span class="text-[11px] font-mono">(Max 50)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-teal-950/30 text-teal-300">M2 CE Mark<br><span class="text-[11px] font-mono">(Max 50)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-teal-950/30 text-teal-300">M3 CE Mark<br><span class="text-[11px] font-mono">(Max 50)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-teal-950/30 text-teal-300">M4 CE Mark<br><span class="text-[11px] font-mono">(Max 50)</span></th>
                                <th class="border border-slate-800 text-center w-28 bg-slate-900 text-slate-200">Average<br><span class="text-[11px] font-mono">(Out of 50)</span></th>
                                <th class="border border-slate-800 text-center w-32 bg-emerald-950/40 text-emerald-300 font-bold">CE Converted<br><span class="text-[11px] font-mono">(Out of 10 Marks)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="ce-tab-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center font-mono text-sky-400">{{ $st['roll_no'] ?: '-' }}</td>
                                <td class="border border-slate-800 font-mono text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 font-semibold text-white">{{ $st['name'] }}</td>
                                @foreach(['m1' => 'M1', 'm2' => 'M2', 'm3' => 'M3', 'm4' => 'M4'] as $mKey => $mLabel)
                                @php $colName = "ce_{$mKey}_50"; @endphp
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="50"
                                           value="{{ $st[$colName] !== null ? $st[$colName] : '' }}"
                                           data-max="50"
                                           data-field-name="Continuous Evaluation {{ $mLabel }} (Out of 50)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-ce-{{ $mKey }}"
                                           placeholder="-"
                                           class="tab-ce-{{ $mKey }} mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-ce-{{ $mKey }}', this.value); queueAutoSave();">
                                </td>
                                @endforeach
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-slate-200 tab-ce-avg-50">
                                    {{ number_format($st['ce_avg_50'] ?? 0, 2) }}
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-extrabold text-base text-emerald-400 bg-emerald-950/20 tab-ce-conv-10">
                                    {{ number_format($st['ce_conv_10'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: PRACTICAL TEST (CA2 & CA3: Test 1 & Test 2 out of 40 -> Avg 10M)     -->
        <!-- ========================================================================= -->
        <div id="tab-content-practicaltest" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-purple-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">mic</span>
                            <span>CA2 &amp; CA3: Practical Tests (Test 1 &amp; Test 2 — Duration 3 Hours, Max 40 Marks Each &rarr; Average Converted to 10 Marks)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            <strong>CA2 (Practical Test 1)</strong>: First half of experiments (By 11th week, 40M &rarr; 10M) • <strong>CA3 (Practical Test 2)</strong>: Second half of experiments (By 14th week, 40M &rarr; 10M) • <strong>Average = 10 Marks CIA</strong>.
                        </p>
                    </div>
                    <span class="text-xs text-emerald-400 font-bold">⚡ Auto-Saves on Typing</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-950/90 text-slate-300">
                                <th rowspan="2" class="border border-slate-800 text-center w-12">Roll</th>
                                <th rowspan="2" class="border border-slate-800 w-32">Reg No</th>
                                <th rowspan="2" class="border border-slate-800 min-w-[180px]">Student Name</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-purple-950/35 text-purple-300">CA2: Practical Test 1 (1st Half — 3 Hrs)</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-indigo-950/35 text-indigo-300">CA3: Practical Test 2 (2nd Half — 3 Hrs)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-slate-900 text-slate-200">Average<br><span class="text-[11px] font-mono">(Out of 40)</span></th>
                                <th rowspan="2" class="border border-slate-800 text-center w-32 bg-emerald-950/40 text-emerald-300 font-bold">Final Practical Mark<br><span class="text-[11px] font-mono">(Converted to 10)</span></th>
                            </tr>
                            <tr class="bg-slate-950 text-slate-400 text-[11px]">
                                <th class="border border-slate-800 text-center w-32">Test 1 Mark (40)</th>
                                <th class="border border-slate-800 text-center w-14">AB</th>
                                <th class="border border-slate-800 text-center w-24 text-purple-300">Conv (10)</th>
                                <th class="border border-slate-800 text-center w-32">Test 2 Mark (40)</th>
                                <th class="border border-slate-800 text-center w-14">AB</th>
                                <th class="border border-slate-800 text-center w-24 text-indigo-300">Conv (10)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="pt-tab-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center font-mono text-sky-400">{{ $st['roll_no'] ?: '-' }}</td>
                                <td class="border border-slate-800 font-mono text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 font-semibold text-white">{{ $st['name'] }}</td>

                                <!-- Practical Test 1 (40) -->
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="40"
                                           value="{{ $st['prac_test1_40'] !== null ? $st['prac_test1_40'] : '' }}"
                                           data-max="40"
                                           data-field-name="Practical Test 1 (Out of 40)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-pt1-40"
                                           placeholder="-"
                                           class="tab-pt1-40 mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-pt1-40', this.value); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="tab-pt1-abs w-4 h-4 cursor-pointer"
                                           {{ !empty($st['prac_test1_absent']) ? 'checked' : '' }}
                                           onchange="syncCheckboxToCia('{{ $st['reg_no'] }}', '.cia-pt1-abs', this.checked); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-purple-300 tab-pt1-10">
                                    {{ $st['prac_test1_absent'] ? 'AB' : number_format($st['prac_test1_10'] ?? 0, 2) }}
                                </td>

                                <!-- Practical Test 2 (40) -->
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="40"
                                           value="{{ $st['prac_test2_40'] !== null ? $st['prac_test2_40'] : '' }}"
                                           data-max="40"
                                           data-field-name="Practical Test 2 (Out of 40)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-pt2-40"
                                           placeholder="-"
                                           class="tab-pt2-40 mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-pt2-40', this.value); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="tab-pt2-abs w-4 h-4 cursor-pointer"
                                           {{ !empty($st['prac_test2_absent']) ? 'checked' : '' }}
                                           onchange="syncCheckboxToCia('{{ $st['reg_no'] }}', '.cia-pt2-abs', this.checked); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-indigo-300 tab-pt2-10">
                                    {{ $st['prac_test2_absent'] ? 'AB' : number_format($st['prac_test2_10'] ?? 0, 2) }}
                                </td>

                                <!-- Average (40) & Final Converted (10) -->
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-slate-200 tab-pt-avg-40">
                                    {{ number_format($st['prac_avg_40'] ?? 0, 2) }}
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-extrabold text-base text-emerald-400 bg-emerald-950/20 tab-pt-conv-10">
                                    {{ number_format($st['prac_conv_10'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="11" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: SERIES TEST (CA4 & CA5: Series 1 & Series 2 out of 50 -> Avg 10M)    -->
        <!-- ========================================================================= -->
        <div id="tab-content-seriestest" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-sky-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">edit_note</span>
                            <span>CA4 &amp; CA5: Series Examinations (Series 1 &amp; Series 2 — Duration 2 Hours, Max 50 Marks Each &rarr; Average Converted to 10 Marks)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            <strong>CA4 (Series Exam 1)</strong>: Modules I &amp; II (8th week, 50M &rarr; 10M) • <strong>CA5 (Series Exam 2)</strong>: Modules III &amp; IV (15th week, 50M &rarr; 10M) • <strong>Average = 10 Marks CIA</strong>.
                        </p>
                    </div>
                    <span class="text-xs text-emerald-400 font-bold">⚡ Auto-Saves on Typing</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-950/90 text-slate-300">
                                <th rowspan="2" class="border border-slate-800 text-center w-12">Roll</th>
                                <th rowspan="2" class="border border-slate-800 w-32">Reg No</th>
                                <th rowspan="2" class="border border-slate-800 min-w-[180px]">Student Name</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-sky-950/35 text-sky-300">CA4: Series Exam 1 (Modules I &amp; II — 2 Hrs)</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-cyan-950/35 text-cyan-300">CA5: Series Exam 2 (Modules III &amp; IV — 2 Hrs)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-slate-900 text-slate-200">Average<br><span class="text-[11px] font-mono">(Out of 50)</span></th>
                                <th rowspan="2" class="border border-slate-800 text-center w-32 bg-emerald-950/40 text-emerald-300 font-bold">Final Series Mark<br><span class="text-[11px] font-mono">(Converted to 10)</span></th>
                            </tr>
                            <tr class="bg-slate-950 text-slate-400 text-[11px]">
                                <th class="border border-slate-800 text-center w-32">Series 1 Mark (50)</th>
                                <th class="border border-slate-800 text-center w-14">AB</th>
                                <th class="border border-slate-800 text-center w-24 text-sky-300">Conv (10)</th>
                                <th class="border border-slate-800 text-center w-32">Series 2 Mark (50)</th>
                                <th class="border border-slate-800 text-center w-14">AB</th>
                                <th class="border border-slate-800 text-center w-24 text-cyan-300">Conv (10)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="ser-tab-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center font-mono text-sky-400">{{ $st['roll_no'] ?: '-' }}</td>
                                <td class="border border-slate-800 font-mono text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 font-semibold text-white">{{ $st['name'] }}</td>

                                <!-- Series 1 (50) -->
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="50"
                                           value="{{ $st['series1_50'] !== null ? $st['series1_50'] : '' }}"
                                           data-max="50"
                                           data-field-name="Series Exam 1 (Out of 50)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-s1-50"
                                           placeholder="-"
                                           class="tab-s1-50 mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-s1-50', this.value); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="tab-s1-abs w-4 h-4 cursor-pointer"
                                           {{ !empty($st['series1_absent']) ? 'checked' : '' }}
                                           onchange="syncCheckboxToCia('{{ $st['reg_no'] }}', '.cia-s1-abs', this.checked); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-sky-300 tab-s1-10">
                                    {{ $st['series1_absent'] ? 'AB' : number_format($st['series1_10'] ?? 0, 2) }}
                                </td>

                                <!-- Series 2 (50) -->
                                <td class="border border-slate-800 p-2 text-center">
                                    <input type="number" step="0.5" min="0" max="50"
                                           value="{{ $st['series2_50'] !== null ? $st['series2_50'] : '' }}"
                                           data-max="50"
                                           data-field-name="Series Exam 2 (Out of 50)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-sync-target=".cia-s2-50"
                                           placeholder="-"
                                           class="tab-s2-50 mark-entry-lg no-spinners font-mono"
                                           oninput="syncInputToCia('{{ $st['reg_no'] }}', '.cia-s2-50', this.value); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="tab-s2-abs w-4 h-4 cursor-pointer"
                                           {{ !empty($st['series2_absent']) ? 'checked' : '' }}
                                           onchange="syncCheckboxToCia('{{ $st['reg_no'] }}', '.cia-s2-abs', this.checked); queueAutoSave();">
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-cyan-300 tab-s2-10">
                                    {{ $st['series2_absent'] ? 'AB' : number_format($st['series2_10'] ?? 0, 2) }}
                                </td>

                                <!-- Average (50) & Final Converted (10) -->
                                <td class="border border-slate-800 text-center font-mono font-bold text-sm text-slate-200 tab-ser-avg-50">
                                    {{ number_format($st['series_avg_50'] ?? 0, 2) }}
                                </td>
                                <td class="border border-slate-800 text-center font-mono font-extrabold text-base text-emerald-400 bg-emerald-950/20 tab-ser-conv-10">
                                    {{ number_format($st['series_conv_10'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="11" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: CONSOLIDATED CIA REGISTER (40M INTERNAL + 60M ESE = 100M TOTAL)      -->
        <!-- ========================================================================= -->
        <div id="tab-content-cia" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">fact_check</span>
                            <span>Consolidated CIA Register — 1001 English for Communication (Rev 2026)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            Attendance (<strong>5M</strong>) + CA1 Self-Learning (<strong>5M</strong>) + CE (<strong>10M</strong>) + CA2&amp;CA3 Practical Avg (<strong>10M</strong>) + CA4&amp;CA5 Series Avg (<strong>10M</strong>) = <strong class="text-emerald-400">40M CIA</strong> + ESE Lab (<strong>60M</strong>) = <strong class="text-amber-300">100M Total</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" class="px-3 py-1.5 rounded-lg bg-blue-600/25 hover:bg-blue-600/40 border border-blue-500/40 text-blue-300 hover:text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer no-underline">
                            <span class="material-symbols-rounded text-sm">co_present</span>
                            <span>Attendance &amp; Log</span>
                        </a>
                        <button onclick="openTeamsAttendanceModal()" class="px-3 py-1.5 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 border border-indigo-500/40 text-indigo-300 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">upload_file</span>
                            <span>Upload TEAMS Attendance</span>
                        </button>
                        <button onclick="switchTab('reports')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-amber-300 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Open Reports &amp; Print</span>
                        </button>
                    </div>
                </div>

                <!-- Rubric Hint Banner -->
                <div class="p-2.5 rounded-lg bg-slate-950/70 border border-slate-800 text-xs text-slate-300 flex items-center justify-between flex-wrap gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-blue-400 text-sm">info</span>
                        <span><strong>Attendance Slabs (5M):</strong> &ge;90%: 5 | 80–89.9%: 4 | 75–79.9%: 3 | 70–74.9%: 2 | 65–69.9%: 1 | &lt;65%: 0</span>
                    </div>
                    <span class="text-emerald-400 font-semibold">⚡ Override field highlights <span class="text-amber-400 font-bold">Amber</span> when entered • Auto-saves immediately</span>
                </div>

                <!-- Consolidated CIA & ESE Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left" id="consolidatedCiaTable">
                        <thead>
                            <tr class="bg-slate-950/90 text-slate-200 text-xs">
                                <th rowspan="2" class="border border-slate-800 text-center w-12">Roll</th>
                                <th rowspan="2" class="border border-slate-800 w-32">Reg No</th>
                                <th rowspan="2" class="border border-slate-800 min-w-[180px]">Student Name</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-blue-950/40 text-blue-300">Attendance (5M)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-amber-950/40 text-amber-300">
                                    CA1: Self Learning<br><span class="text-[11px] font-mono">(Max 5M)</span><br>
                                    <button type="button" onclick="switchTab('selflearning')" class="mt-1 px-2 py-0.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 rounded text-[10px] font-semibold transition cursor-pointer">Edit CA1 &rarr;</button>
                                </th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-teal-950/40 text-teal-300">
                                    CE: Continuous Eval<br><span class="text-[11px] font-mono">(Max 10M)</span><br>
                                    <button type="button" onclick="switchTab('ce')" class="mt-1 px-2 py-0.5 bg-teal-500/20 hover:bg-teal-500/30 text-teal-300 rounded text-[10px] font-semibold transition cursor-pointer">Edit CE &rarr;</button>
                                </th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-purple-950/40 text-purple-300">
                                    CA2: Practical Test<br><span class="text-[11px] font-mono">(Max 10M)</span><br>
                                    <button type="button" onclick="switchTab('practicaltest')" class="mt-1 px-2 py-0.5 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 rounded text-[10px] font-semibold transition cursor-pointer">Edit CA2 &rarr;</button>
                                </th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-sky-950/40 text-sky-300">
                                    CA4: Series Test<br><span class="text-[11px] font-mono">(Max 10M)</span><br>
                                    <button type="button" onclick="switchTab('seriestest')" class="mt-1 px-2 py-0.5 bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 rounded text-[10px] font-semibold transition cursor-pointer">Edit CA4 &rarr;</button>
                                </th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-emerald-950/60 text-emerald-300 font-extrabold">CIA Total<br><span class="text-[11px] font-mono">(Max 40M)</span></th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-rose-950/40 text-rose-300 font-bold">ESE Grade</th>
                            </tr>
                            <tr class="bg-slate-950 text-slate-400 text-[11px]">
                                <!-- Attendance -->
                                <th class="border border-slate-800 text-center w-24" title="Uploaded TEAMS / Log Attendance %">Attendance Upload %</th>
                                <th class="border border-slate-800 text-center w-28" title="Override % (Amber when active)">Override %</th>
                                <th class="border border-slate-800 text-center w-20 text-blue-300 font-bold">Marks (5)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            @php
                                $hasAttOverride = ($st['attendance_override_pct'] !== null && $st['attendance_override_pct'] !== '');
                            @endphp
                            <tr class="student-eval-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center font-mono text-sky-400 text-xs">{{ $st['roll_no'] ?: '-' }}</td>
                                <td class="border border-slate-800 font-mono text-xs text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 text-xs font-semibold text-white">{{ $st['name'] }}</td>

                                <!-- Attendance Uploaded % -->
                                <td class="border border-slate-800 text-center text-xs font-mono text-slate-300">
                                    @if($st['raw_att_percentage'] !== null)
                                        {{ number_format($st['raw_att_percentage'], 1) . '%' }}
                                    @else
                                        <span class="text-amber-400/80 italic text-[11px]" title="Attendance not entered manually or from Teams">Not entered</span>
                                    @endif
                                </td>

                                <!-- Attendance Override % (Amber when data entered) -->
                                <td class="border border-slate-800 p-1.5 text-center cell-att-td">
                                    <div class="flex items-center justify-center gap-1">
                                        <input type="number" step="0.1" min="0" max="100"
                                               value="{{ $hasAttOverride ? $st['attendance_override_pct'] : '' }}"
                                               data-prev-val="{{ $hasAttOverride ? $st['attendance_override_pct'] : '' }}"
                                               placeholder="{{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) : '-' }}"
                                               class="cell-att-override mark-entry-cia no-spinners font-mono {{ $hasAttOverride ? 'has-override' : '' }}"
                                               title="{{ $hasAttOverride ? 'Active Override: ' . $st['attendance_override_pct'] . '%' : 'Enter override attendance % (highlights Amber when active)' }}"
                                               onfocus="this.dataset.prevVal = this.value;"
                                               oninput="recalculateStudentRow('{{ $st['reg_no'] }}'); queueAutoSave();"
                                               onchange="handleAttendanceOverrideChange(this, '{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}');">
                                        @if($hasAttOverride)
                                            <button type="button" onclick="clearAttendanceOverride('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-att-override-action p-1 rounded hover:bg-slate-800 text-amber-400 hover:text-rose-400 transition cursor-pointer" title="Clear override and revert to uploaded attendance %">
                                                <span class="material-symbols-rounded text-xs">close</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="promptAttendanceOverride('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-att-override-action p-1 rounded hover:bg-slate-800 text-slate-500 hover:text-amber-400 transition cursor-pointer" title="Click to override attendance %">
                                                <span class="material-symbols-rounded text-xs">edit</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <!-- Attendance Mark (5) -->
                                <td class="border border-slate-800 text-center font-extrabold text-sm text-blue-300 font-mono cell-att-mark">
                                    {{ number_format($st['att_mark_5'] ?? 0, 1) }}
                                </td>

                                <!-- CA1 Self Learning Final Total (Out of 5) -->
                                <td class="border border-slate-800 text-center bg-amber-950/20 p-2">
                                    <span class="cia-sl-conv-5 text-sm font-extrabold text-amber-300 bg-amber-950/50 px-2.5 py-1 rounded border border-amber-500/40 inline-block font-mono min-w-[52px]">
                                        {{ number_format($st['sl_conv_5'] ?? 0, 1) }}
                                    </span>
                                    <span class="cia-sl-avg-10 hidden">{{ number_format($st['sl_avg_10'] ?? 0, 1) }}</span>
                                    @foreach(['m1', 'm2', 'm3', 'm4'] as $mKey)
                                    @php $colName = "sl_{$mKey}_10"; @endphp
                                    <input type="hidden" class="cia-sl-{{ $mKey }}" value="{{ $st[$colName] !== null ? $st[$colName] : '' }}">
                                    @endforeach
                                </td>

                                <!-- CE Continuous Eval Final Total (Out of 10) -->
                                <td class="border border-slate-800 text-center bg-teal-950/20 p-2">
                                    <span class="cia-ce-conv-10 text-sm font-extrabold text-teal-300 bg-teal-950/50 px-2.5 py-1 rounded border border-teal-500/40 inline-block font-mono min-w-[52px]">
                                        {{ number_format($st['ce_conv_10'] ?? 0, 1) }}
                                    </span>
                                    <span class="cia-ce-avg-50 hidden">{{ number_format($st['ce_avg_50'] ?? 0, 1) }}</span>
                                    @foreach(['m1', 'm2', 'm3', 'm4'] as $mKey)
                                    @php $colName = "ce_{$mKey}_50"; @endphp
                                    <input type="hidden" class="cia-ce-{{ $mKey }}" value="{{ $st[$colName] !== null ? $st[$colName] : '' }}">
                                    @endforeach
                                </td>

                                <!-- CA2 Practical Test Final Total (Out of 10) -->
                                <td class="border border-slate-800 text-center bg-purple-950/20 p-2">
                                    <span class="cia-pt-conv-10 text-sm font-extrabold text-purple-300 bg-purple-950/50 px-2.5 py-1 rounded border border-purple-500/40 inline-block font-mono min-w-[52px]">
                                        {{ number_format($st['prac_conv_10'] ?? 0, 1) }}
                                    </span>
                                    <span class="cia-pt-avg-40 hidden">{{ number_format($st['prac_avg_40'] ?? 0, 1) }}</span>
                                    <input type="hidden" class="cia-pt1-40" value="{{ $st['prac_test1_40'] !== null ? $st['prac_test1_40'] : '' }}">
                                    <input type="hidden" class="cia-pt2-40" value="{{ $st['prac_test2_40'] !== null ? $st['prac_test2_40'] : '' }}">
                                    <input type="checkbox" class="cia-pt1-abs hidden" {{ !empty($st['prac_test1_absent']) ? 'checked' : '' }}>
                                    <input type="checkbox" class="cia-pt2-abs hidden" {{ !empty($st['prac_test2_absent']) ? 'checked' : '' }}>
                                </td>

                                <!-- CA4 Series Test Final Total (Out of 10) -->
                                <td class="border border-slate-800 text-center bg-sky-950/20 p-2">
                                    <span class="cia-ser-conv-10 text-sm font-extrabold text-sky-300 bg-sky-950/50 px-2.5 py-1 rounded border border-sky-500/40 inline-block font-mono min-w-[52px]">
                                        {{ number_format($st['series_conv_10'] ?? 0, 1) }}
                                    </span>
                                    <span class="cia-ser-avg-50 hidden">{{ number_format($st['series_avg_50'] ?? 0, 1) }}</span>
                                    <input type="hidden" class="cia-s1-50" value="{{ $st['series1_50'] !== null ? $st['series1_50'] : '' }}">
                                    <input type="hidden" class="cia-s2-50" value="{{ $st['series2_50'] !== null ? $st['series2_50'] : '' }}">
                                    <input type="checkbox" class="cia-s1-abs hidden" {{ !empty($st['series1_absent']) ? 'checked' : '' }}>
                                    <input type="checkbox" class="cia-s2-abs hidden" {{ !empty($st['series2_absent']) ? 'checked' : '' }}>
                                </td>

                                <!-- TOTAL CIA (Out of 40 — Rounded Off) -->
                                <td class="border border-slate-800 text-center bg-emerald-950/35 p-2">
                                    <span class="cia-total-40 text-base font-black text-emerald-400 bg-emerald-950/60 px-3 py-1 rounded-lg border border-emerald-500/50 inline-block font-mono min-w-[58px]">
                                        {{ number_format(round($st['total_cia_40'] ?? 0)) }}
                                    </span>
                                </td>

                                <!-- ESE Grade Dropdown -->
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="cia-ese-grade w-20 h-9 px-2 py-1 text-xs font-bold text-center rounded-lg"
                                            onchange="recalculateStudentRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                        <option value="">- Grade -</option>
                                        @foreach(['S', 'A', 'B', 'C', 'D', 'E', 'P', 'F', 'AB'] as $grd)
                                        <option value="{{ $grd }}" {{ ($st['ese_grade'] ?? '') === $grd ? 'selected' : '' }}>{{ $grd }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" class="cia-ese-60" value="{{ $st['ese_mark_60'] !== null ? $st['ese_mark_60'] : '' }}">
                                    <input type="checkbox" class="cia-ese-abs hidden" {{ !empty($st['ese_absent']) ? 'checked' : '' }}>
                                    <span class="cia-grand-100 hidden">{{ number_format($st['total_marks_100'] ?? 0, 1) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center text-slate-400 py-6 italic">No enrolled students found in this classroom.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB: REPORTS (ALL OFFICIAL PRINTABLE REGISTERS & LOGS)                    -->
        <!-- ========================================================================= -->
        <div id="tab-content-reports" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="mb-4 pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base">print</span>
                        <span>Official Printable Registers &amp; Academic Reports — 1001 English for Communication</span>
                    </h3>
                    <p class="text-xs text-slate-400 mb-0">Generate A4 print-ready registers for Attendance, Course Log, Self Learning, CE, Practical Test, Series Test, CIA (Internal Mark Consolidated), and Consolidated Marks.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- 1. CIA (Internal Mark Consolidated 40M) -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">fact_check</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">CIA Register (Internal Mark Consolidated — 40M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Full internal assessment register with Attendance (5M), CA1 (5M), CE (10M), Practical Test Avg (10M), Series Test Avg (10M), and Total CIA (40M).</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/cia-register" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CIA Register</span>
                        </a>
                    </div>

                    <!-- 2. Consolidated Marks (CIA 40M + ESE Grade) -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">workspace_premium</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Consolidated Marks Report (CIA 40M + ESE Grade)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Complete semester result register combining 40M Internal CIA marks (rounded off) and ESE Grade.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/consolidated-marks" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Consolidated Marks</span>
                        </a>
                    </div>

                    <!-- 3. Attendance Report -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">how_to_reg</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Attendance Percentage &amp; Marks Report (5M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Official subject attendance report showing TEAMS uploaded %, manual override %, effective %, eligibility, and Attendance Marks out of 5.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/attendance" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Attendance Report</span>
                        </a>
                    </div>

                    <!-- 4. Course Log / Lesson Plan Schedule -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">calendar_month</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Course Log &amp; Lesson Planner Report</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">60-hour session-wise course execution log with proposed dates, actual completion dates, modules, COs, and faculty/HOD signatures.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/course-log" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Course Log</span>
                        </a>
                    </div>

                    <!-- 5. Continuous Evaluation (CE) Mark Report -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">assignment_turned_in</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Continuous Evaluation (CE) Mark Report (50 &rarr; 10M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Module-wise Continuous Evaluation marks (M1, M2, M3, M4 each out of 50), average out of 50, and converted mark out of 10.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/ce-marks" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CE Mark Report</span>
                        </a>
                    </div>

                    <!-- 6. Practical Test Mark Report (CA2 & CA3) -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">mic</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Practical Test Mark Report (CA2 &amp; CA3: 40 &rarr; 10M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Practical Test 1 (40M) &amp; Practical Test 2 (40M) marks, individual conversions out of 10, average out of 40, and final mark out of 10.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/practical-test" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Practical Test Report</span>
                        </a>
                    </div>

                    <!-- 7. Series Test Mark Report (CA4 & CA5) -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">edit_note</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Series Test Mark Report (CA4 &amp; CA5: 50 &rarr; 10M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Series Exam 1 (50M) &amp; Series Exam 2 (50M) marks, individual conversions out of 10, average out of 50, and final mark out of 10.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/series-test" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series Test Report</span>
                        </a>
                    </div>

                    <!-- 8. Self-Learning Mark Report (CA1) -->
                    <div class="p-4 rounded-xl bg-slate-950/75 border border-slate-800 space-y-2.5 hover:border-slate-700 transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-rounded">psychology</span>
                            </div>
                            <h4 class="font-bold text-xs text-white">Self-Learning Mark Report (CA1: 10 &rarr; 5M)</h4>
                            <p class="text-[11px] text-slate-400 leading-snug">Module 1–4 Self-Learning activity marks (each out of 10), average out of 10, and converted mark out of 5.</p>
                        </div>
                        <a href="/r26/classroom/english-1001/{{ $batchSubject->id }}/print/self-learning" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Self-Learning Report</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <!-- SYLLABUS UPLOAD MODAL -->
    <div id="syllabusModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-card max-w-md w-full p-5 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                <h4 class="font-bold text-sm text-sky-400 flex items-center gap-2">
                    <span class="material-symbols-rounded text-base">cloud_upload</span>
                    <span>Upload English (1001) Syllabus PDF</span>
                </h4>
                <button onclick="closeSyllabusModal()" class="text-slate-400 hover:text-white text-xs cursor-pointer">✕</button>
            </div>
            <form id="uploadSyllabusForm" enctype="multipart/form-data" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Select Syllabus PDF (Rev 2026)</label>
                    <input type="file" name="syllabus_file" accept=".pdf" required class="w-full text-xs px-2.5 py-2 border rounded-lg bg-slate-950 border-slate-800 text-slate-200">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeSyllabusModal()" class="px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold cursor-pointer">Cancel</button>
                    <button type="submit" id="uploadBtn" class="px-3 py-1.5 rounded bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-rounded text-sm">upload</span>
                        <span>Upload Syllabus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TEAMS SUBJECT ATTENDANCE UPLOAD MODAL -->
    <div id="teamsAttendanceModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-card max-w-md w-full p-5 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                <h4 class="font-bold text-sm text-indigo-400 flex items-center gap-2">
                    <span class="material-symbols-rounded text-base">upload_file</span>
                    <span>Upload TEAMS Official Subject Attendance</span>
                </h4>
                <button onclick="closeTeamsAttendanceModal()" class="text-slate-400 hover:text-white text-xs cursor-pointer">✕</button>
            </div>
            <form id="uploadTeamsAttendanceForm" enctype="multipart/form-data" class="space-y-3">
                <p class="text-xs text-slate-400 leading-relaxed mb-0">
                    Upload the official TEAMS attendance CSV / Excel / PDF file for <strong>{{ $batchSubject->subject_code }} ({{ $batchSubject->classroom_id }})</strong>.
                </p>
                <div>
                    <input type="file" name="attendance_file" accept=".csv,.xlsx,.xls,.pdf" required class="w-full text-xs px-2.5 py-2 border rounded-lg bg-slate-950 border-slate-800 text-slate-200">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeTeamsAttendanceModal()" class="px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold cursor-pointer">Cancel</button>
                    <button type="submit" id="uploadTeamsBtn" class="px-3 py-1.5 rounded bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-rounded text-sm">cloud_upload</span>
                        <span>Upload &amp; Apply</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = {{ $batchSubject->id }};

        // Tab Switching
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane-content').forEach(pane => pane.classList.add('hidden'));
            document.querySelectorAll('.subtab-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-slate-400', 'border-transparent');
            });

            const targetPane = document.getElementById(`tab-content-${tabId}`);
            const targetBtn = document.getElementById(`tab-btn-${tabId}`);

            if (targetPane) targetPane.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.remove('text-slate-400', 'border-transparent');
                targetBtn.classList.add('active');
            }

            if (tabId === 'lessonplan') {
                setTimeout(() => {
                    document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));
                }, 20);
            }
        }

        function autoGrow(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = Math.max(44, element.scrollHeight) + 'px';
        }

        function closeTabOrGoDashboard() {
            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }
            window.close();
            setTimeout(function() {
                window.location.href = '{{ $dashboardUrl }}';
            }, 150);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }

        function openSyllabusModal() {
            const m = document.getElementById('syllabusModal');
            if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        function closeSyllabusModal() {
            const m = document.getElementById('syllabusModal');
            if (m) { m.classList.remove('flex'); m.classList.add('hidden'); }
        }

        function openTeamsAttendanceModal() {
            const m = document.getElementById('teamsAttendanceModal');
            if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        function closeTeamsAttendanceModal() {
            const m = document.getElementById('teamsAttendanceModal');
            if (m) { m.classList.remove('flex'); m.classList.add('hidden'); }
        }

        // R2026 Attendance Slab (Max 5 Marks)
        function calculateAttMarks(pct) {
            if (pct === null || pct === undefined || isNaN(pct) || pct === '') return 0.0;
            const p = parseFloat(pct);
            if (p >= 90.0) return 5.0;
            if (p >= 80.0) return 4.0;
            if (p >= 75.0) return 3.0;
            if (p >= 70.0) return 2.0;
            if (p >= 65.0) return 1.0;
            return 0.0;
        }

        // Grade to Marks out of 60 fallback
        function gradeToMarks60(grade) {
            if (!grade) return null;
            const g = grade.trim().toUpperCase();
            const map = { 'S': 57, 'A': 51, 'B': 45, 'C': 39, 'D': 33, 'E': 27, 'P': 24, 'F': 0, 'AB': 0 };
            return map[g] !== undefined ? map[g] : null;
        }

        // Sync from Dedicated Tab into CIA Register Row & Recalculate
        function syncInputToCia(regNo, ciaSelector, value) {
            const ciaRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!ciaRow) return;
            const targetInput = ciaRow.querySelector(ciaSelector);
            if (targetInput) {
                targetInput.value = value;
            }
            recalculateStudentRow(regNo);
        }

        function syncCheckboxToCia(regNo, ciaSelector, checked) {
            const ciaRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!ciaRow) return;
            const targetCb = ciaRow.querySelector(ciaSelector);
            if (targetCb) {
                targetCb.checked = checked;
            }
            recalculateStudentRow(regNo);
        }

        // Core Real-Time Row Recalculation (Updates CIA Register + All Individual Assessment Tabs)
        function recalculateStudentRow(regNo) {
            const ciaRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!ciaRow) return;

            // 1. Attendance (Max 5)
            const overrideInput = ciaRow.querySelector('.cell-att-override');
            const overrideVal = overrideInput.value.trim();
            const attActionBtn = ciaRow.querySelector('.btn-att-override-action');
            let effectivePct = null;
            if (overrideVal !== '' && !isNaN(parseFloat(overrideVal))) {
                effectivePct = Math.min(100.0, Math.max(0.0, parseFloat(overrideVal)));
                overrideInput.classList.add('has-override');
                overrideInput.title = `Active Override: ${effectivePct.toFixed(1)}%`;
                if (attActionBtn) {
                    attActionBtn.className = 'btn-att-override-action p-1 rounded hover:bg-slate-800 text-amber-400 hover:text-rose-400 transition cursor-pointer';
                    attActionBtn.title = 'Clear override and revert to uploaded attendance %';
                    attActionBtn.innerHTML = '<span class="material-symbols-rounded text-xs">close</span>';
                }
            } else {
                overrideInput.classList.remove('has-override');
                overrideInput.title = 'Enter override attendance % (highlights Amber when active)';
                if (attActionBtn) {
                    attActionBtn.className = 'btn-att-override-action p-1 rounded hover:bg-slate-800 text-slate-500 hover:text-amber-400 transition cursor-pointer';
                    attActionBtn.title = 'Click to override attendance %';
                    attActionBtn.innerHTML = '<span class="material-symbols-rounded text-xs">edit</span>';
                }
                const ph = overrideInput.getAttribute('placeholder');
                if (ph && ph !== '-' && !isNaN(parseFloat(ph))) {
                    effectivePct = parseFloat(ph);
                }
            }
            const attMark5 = effectivePct !== null ? calculateAttMarks(effectivePct) : 0.0;
            ciaRow.querySelector('.cell-att-mark').textContent = attMark5.toFixed(1);

            // 2. CA1: Self Learning (M1..M4 out of 10 -> Avg 10 -> Conv 5)
            const slVals = ['m1', 'm2', 'm3', 'm4'].map(k => ciaRow.querySelector(`.cia-sl-${k}`).value.trim());
            const slHasAny = slVals.some(v => v !== '');
            const slNums = slVals.map(v => v !== '' ? Math.min(10.0, Math.max(0.0, parseFloat(v) || 0)) : 0.0);
            const slAvg10 = slHasAny ? ((slNums[0] + slNums[1] + slNums[2] + slNums[3]) / 4.0) : 0.0;
            const slConv5 = slHasAny ? Math.min(5.0, Math.round(((slAvg10 / 10.0) * 5.0) * 2) / 2) : 0.0;

            ciaRow.querySelector('.cia-sl-avg-10').textContent = slAvg10.toFixed(1);
            ciaRow.querySelector('.cia-sl-conv-5').textContent = slConv5.toFixed(1);

            // Sync Self Learning Tab
            const slTabRow = document.querySelector(`.sl-tab-row[data-reg-no="${regNo}"]`);
            if (slTabRow) {
                ['m1', 'm2', 'm3', 'm4'].forEach((k, idx) => {
                    const inp = slTabRow.querySelector(`.tab-sl-${k}`);
                    if (inp && document.activeElement !== inp) inp.value = slVals[idx];
                });
                slTabRow.querySelector('.tab-sl-avg-10').textContent = slAvg10.toFixed(2);
                slTabRow.querySelector('.tab-sl-conv-5').textContent = slConv5.toFixed(1);
            }

            // 3. CE: Continuous Evaluation (M1..M4 out of 50 -> Avg 50 -> Conv 10)
            const ceVals = ['m1', 'm2', 'm3', 'm4'].map(k => ciaRow.querySelector(`.cia-ce-${k}`).value.trim());
            const ceHasAny = ceVals.some(v => v !== '');
            const ceNums = ceVals.map(v => v !== '' ? Math.min(50.0, Math.max(0.0, parseFloat(v) || 0)) : 0.0);
            const ceAvg50 = ceHasAny ? ((ceNums[0] + ceNums[1] + ceNums[2] + ceNums[3]) / 4.0) : 0.0;
            const ceConv10 = ceHasAny ? Math.min(10.0, Math.round(((ceAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            ciaRow.querySelector('.cia-ce-avg-50').textContent = ceAvg50.toFixed(1);
            ciaRow.querySelector('.cia-ce-conv-10').textContent = ceConv10.toFixed(1);

            // Sync CE Tab
            const ceTabRow = document.querySelector(`.ce-tab-row[data-reg-no="${regNo}"]`);
            if (ceTabRow) {
                ['m1', 'm2', 'm3', 'm4'].forEach((k, idx) => {
                    const inp = ceTabRow.querySelector(`.tab-ce-${k}`);
                    if (inp && document.activeElement !== inp) inp.value = ceVals[idx];
                });
                ceTabRow.querySelector('.tab-ce-avg-50').textContent = ceAvg50.toFixed(2);
                ceTabRow.querySelector('.tab-ce-conv-10').textContent = ceConv10.toFixed(1);
            }

            // 4. CA2 & CA3: Practical Tests (Test 1 & Test 2 out of 40 -> Avg 40 -> Conv 10)
            const pt1Val = ciaRow.querySelector('.cia-pt1-40').value.trim();
            const pt2Val = ciaRow.querySelector('.cia-pt2-40').value.trim();
            const pt1Abs = ciaRow.querySelector('.cia-pt1-abs').checked;
            const pt2Abs = ciaRow.querySelector('.cia-pt2-abs').checked;

            const ptHasAny = (pt1Val !== '' || pt2Val !== '' || pt1Abs || pt2Abs);
            const pt1Eff40 = (!pt1Abs && pt1Val !== '') ? Math.min(40.0, Math.max(0.0, parseFloat(pt1Val) || 0)) : 0.0;
            const pt2Eff40 = (!pt2Abs && pt2Val !== '') ? Math.min(40.0, Math.max(0.0, parseFloat(pt2Val) || 0)) : 0.0;
            const pt1Conv10 = (!pt1Abs && pt1Val !== '') ? (pt1Eff40 / 40.0) * 10.0 : 0.0;
            const pt2Conv10 = (!pt2Abs && pt2Val !== '') ? (pt2Eff40 / 40.0) * 10.0 : 0.0;

            const pracAvg40 = ptHasAny ? ((pt1Eff40 + pt2Eff40) / 2.0) : 0.0;
            const pracConv10 = ptHasAny ? Math.min(10.0, Math.round(((pracAvg40 / 40.0) * 10.0) * 2) / 2) : 0.0;

            ciaRow.querySelector('.cia-pt-avg-40').textContent = pracAvg40.toFixed(1);
            ciaRow.querySelector('.cia-pt-conv-10').textContent = pracConv10.toFixed(1);

            // Sync Practical Test Tab
            const ptTabRow = document.querySelector(`.pt-tab-row[data-reg-no="${regNo}"]`);
            if (ptTabRow) {
                const t1Inp = ptTabRow.querySelector('.tab-pt1-40');
                const t2Inp = ptTabRow.querySelector('.tab-pt2-40');
                if (t1Inp && document.activeElement !== t1Inp) t1Inp.value = pt1Val;
                if (t2Inp && document.activeElement !== t2Inp) t2Inp.value = pt2Val;
                ptTabRow.querySelector('.tab-pt1-abs').checked = pt1Abs;
                ptTabRow.querySelector('.tab-pt2-abs').checked = pt2Abs;
                ptTabRow.querySelector('.tab-pt1-10').textContent = pt1Abs ? 'AB' : pt1Conv10.toFixed(2);
                ptTabRow.querySelector('.tab-pt2-10').textContent = pt2Abs ? 'AB' : pt2Conv10.toFixed(2);
                ptTabRow.querySelector('.tab-pt-avg-40').textContent = pracAvg40.toFixed(2);
                ptTabRow.querySelector('.tab-pt-conv-10').textContent = pracConv10.toFixed(1);
            }

            // 5. CA4 & CA5: Series Exams (Series 1 & Series 2 out of 50 -> Avg 50 -> Conv 10)
            const s1Val = ciaRow.querySelector('.cia-s1-50').value.trim();
            const s2Val = ciaRow.querySelector('.cia-s2-50').value.trim();
            const s1Abs = ciaRow.querySelector('.cia-s1-abs').checked;
            const s2Abs = ciaRow.querySelector('.cia-s2-abs').checked;

            const serHasAny = (s1Val !== '' || s2Val !== '' || s1Abs || s2Abs);
            const s1Eff50 = (!s1Abs && s1Val !== '') ? Math.min(50.0, Math.max(0.0, parseFloat(s1Val) || 0)) : 0.0;
            const s2Eff50 = (!s2Abs && s2Val !== '') ? Math.min(50.0, Math.max(0.0, parseFloat(s2Val) || 0)) : 0.0;
            const s1Conv10 = (!s1Abs && s1Val !== '') ? (s1Eff50 / 50.0) * 10.0 : 0.0;
            const s2Conv10 = (!s2Abs && s2Val !== '') ? (s2Eff50 / 50.0) * 10.0 : 0.0;

            const seriesAvg50 = serHasAny ? ((s1Eff50 + s2Eff50) / 2.0) : 0.0;
            const seriesConv10 = serHasAny ? Math.min(10.0, Math.round(((seriesAvg50 / 50.0) * 10.0) * 2) / 2) : 0.0;

            ciaRow.querySelector('.cia-ser-avg-50').textContent = seriesAvg50.toFixed(1);
            ciaRow.querySelector('.cia-ser-conv-10').textContent = seriesConv10.toFixed(1);

            // Sync Series Test Tab
            const serTabRow = document.querySelector(`.ser-tab-row[data-reg-no="${regNo}"]`);
            if (serTabRow) {
                const s1Inp = serTabRow.querySelector('.tab-s1-50');
                const s2Inp = serTabRow.querySelector('.tab-s2-50');
                if (s1Inp && document.activeElement !== s1Inp) s1Inp.value = s1Val;
                if (s2Inp && document.activeElement !== s2Inp) s2Inp.value = s2Val;
                serTabRow.querySelector('.tab-s1-abs').checked = s1Abs;
                serTabRow.querySelector('.tab-s2-abs').checked = s2Abs;
                serTabRow.querySelector('.tab-s1-10').textContent = s1Abs ? 'AB' : s1Conv10.toFixed(2);
                serTabRow.querySelector('.tab-s2-10').textContent = s2Abs ? 'AB' : s2Conv10.toFixed(2);
                serTabRow.querySelector('.tab-ser-avg-50').textContent = seriesAvg50.toFixed(2);
                serTabRow.querySelector('.tab-ser-conv-10').textContent = seriesConv10.toFixed(1);
            }

            // 6. Total CIA (Out of 40 — Rounded Off) = Att(5) + CA1(5) + CE(10) + Prac(10) + Series(10)
            const totalCia40 = Math.min(40, Math.round(attMark5 + slConv5 + ceConv10 + pracConv10 + seriesConv10));
            ciaRow.querySelector('.cia-total-40').textContent = totalCia40;

            // 7. ESE (Out of 60) + Grand Total (100)
            const eseAbs = ciaRow.querySelector('.cia-ese-abs').checked;
            const eseVal = ciaRow.querySelector('.cia-ese-60').value.trim();
            const eseGrade = ciaRow.querySelector('.cia-ese-grade').value;

            let eseEff60 = 0.0;
            if (!eseAbs) {
                if (eseVal !== '') {
                    eseEff60 = Math.min(60.0, Math.max(0.0, parseFloat(eseVal) || 0));
                } else if (eseGrade) {
                    const gMarks = gradeToMarks60(eseGrade);
                    if (gMarks !== null) eseEff60 = gMarks;
                }
            }
            const grandTotal100 = Math.round((totalCia40 + eseEff60) * 2) / 2;
            ciaRow.querySelector('.cia-grand-100').textContent = grandTotal100.toFixed(1);
        }

        // Attendance Override Handler
        async function handleAttendanceOverrideChange(input, regNo, studentName) {
            const prevVal = input.dataset.prevVal !== undefined ? input.dataset.prevVal.trim() : '';
            const newVal = input.value.trim();
            if (newVal === prevVal) return;

            const ciaRow = input.closest('tr');
            const attActionBtn = ciaRow ? ciaRow.querySelector('.btn-att-override-action') : null;

            if (newVal !== '') {
                const parsedNew = parseFloat(newVal);
                if (isNaN(parsedNew) || parsedNew < 0 || parsedNew > 100) {
                    Swal.fire({ icon: 'warning', title: 'Invalid Percentage', text: 'Attendance percentage must be between 0 and 100.' });
                    input.value = prevVal;
                    recalculateStudentRow(regNo);
                    return;
                }
                input.value = parsedNew.toFixed(1);
                input.dataset.prevVal = input.value;
                input.classList.add('has-override');
                if (attActionBtn) {
                    attActionBtn.onclick = () => clearAttendanceOverride(regNo, studentName);
                }
            } else {
                input.dataset.prevVal = '';
                input.classList.remove('has-override');
                if (attActionBtn) {
                    attActionBtn.onclick = () => promptAttendanceOverride(regNo, studentName);
                }
            }
            recalculateStudentRow(regNo);
            queueAutoSave();
        }

        function clearAttendanceOverride(regNo, studentName) {
            const ciaRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!ciaRow) return;
            const input = ciaRow.querySelector('.cell-att-override');
            if (!input) return;
            input.value = '';
            handleAttendanceOverrideChange(input, regNo, studentName);
        }

        async function promptAttendanceOverride(regNo, studentName) {
            const ciaRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!ciaRow) return;
            const input = ciaRow.querySelector('.cell-att-override');
            if (!input) return;

            const currentVal = input.value.trim() || input.placeholder;
            const { value: newPct } = await Swal.fire({
                title: 'Override Attendance %',
                html: `Enter manual attendance percentage for <strong>${studentName}</strong>:`,
                input: 'number',
                inputValue: currentVal !== '-' ? currentVal : '',
                inputAttributes: { min: '0', max: '100', step: '0.1' },
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Apply Override',
                cancelButtonColor: '#475569',
                inputValidator: (val) => {
                    if (val === '' || isNaN(val) || parseFloat(val) < 0 || parseFloat(val) > 100) {
                        return 'Please enter a valid percentage between 0 and 100';
                    }
                }
            });
            if (newPct !== undefined && newPct !== null && newPct !== '') {
                input.value = newPct;
                handleAttendanceOverrideChange(input, regNo, studentName);
            }
        }

        // Fixed Maximum Limit Validation
        let isPromptingMax = false;
        function attachMarkInputValidation() {
            document.querySelectorAll('input[data-max]').forEach(inp => {
                inp.addEventListener('focus', function() {
                    this.dataset.prevVal = this.value;
                });
                inp.addEventListener('change', async function() {
                    if (isPromptingMax) return;
                    const max = parseFloat(this.dataset.max);
                    const rawVal = this.value.trim();
                    if (rawVal === '') {
                        this.dataset.prevVal = '';
                        return;
                    }
                    const numVal = parseFloat(rawVal);
                    const prevVal = this.dataset.prevVal || '';
                    const regNo = this.dataset.regNo;
                    const studentName = this.dataset.studentName || regNo;
                    const fieldName = this.dataset.fieldName || 'Mark';
                    const syncTarget = this.dataset.syncTarget || null;

                    if (numVal < 0) {
                        this.value = 0;
                        if (syncTarget) syncInputToCia(regNo, syncTarget, '0');
                        else recalculateStudentRow(regNo);
                        queueAutoSave();
                        return;
                    }

                    if (numVal > max) {
                        isPromptingMax = true;
                        const result = await Swal.fire({
                            title: 'Mark Exceeds Maximum Limit',
                            html: `Entered mark <strong class="text-rose-400 font-mono">${numVal}</strong> exceeds maximum <strong class="text-emerald-400 font-mono">${max}</strong> for <strong>${fieldName}</strong> (${studentName}).<br><br>Set to maximum allowed (${max})?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#0284c7',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: `Yes, Set ${max}`,
                            cancelButtonText: 'Revert'
                        });
                        isPromptingMax = false;
                        this.value = result.isConfirmed ? max : prevVal;
                        this.dataset.prevVal = this.value;
                        if (syncTarget) syncInputToCia(regNo, syncTarget, this.value);
                        else recalculateStudentRow(regNo);
                        queueAutoSave();
                    } else {
                        this.dataset.prevVal = this.value;
                    }
                });
            });
        }

        // Status Indicator Helper
        function setSaveStatus(stateText, isSaving = false) {
            const txt = document.getElementById('save-status-text');
            const dot = document.getElementById('save-dot');
            if (txt) txt.textContent = stateText;
            if (dot) {
                if (isSaving) {
                    dot.classList.add('animate-ping', 'bg-amber-400');
                    dot.classList.remove('bg-emerald-400');
                } else {
                    dot.classList.remove('animate-ping', 'bg-amber-400');
                    dot.classList.add('bg-emerald-400');
                }
            }
        }

        // Auto-Save All Student Marks
        let autoSaveTimer = null;
        function queueAutoSave() {
            setSaveStatus('Auto-Saving Marks...', true);
            if (autoSaveTimer) clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => saveAllStudentMarks(false), 650);
        }

        async function saveAllStudentMarks(showToast = false) {
            const rowsPayload = [];
            document.querySelectorAll('.student-eval-row').forEach(row => {
                const regNo = row.dataset.regNo;
                rowsPayload.push({
                    reg_no: regNo,
                    attendance_override_pct: row.querySelector('.cell-att-override').value.trim(),
                    sl_m1_10: row.querySelector('.cia-sl-m1').value.trim(),
                    sl_m2_10: row.querySelector('.cia-sl-m2').value.trim(),
                    sl_m3_10: row.querySelector('.cia-sl-m3').value.trim(),
                    sl_m4_10: row.querySelector('.cia-sl-m4').value.trim(),
                    ce_m1_50: row.querySelector('.cia-ce-m1').value.trim(),
                    ce_m2_50: row.querySelector('.cia-ce-m2').value.trim(),
                    ce_m3_50: row.querySelector('.cia-ce-m3').value.trim(),
                    ce_m4_50: row.querySelector('.cia-ce-m4').value.trim(),
                    prac_test1_40: row.querySelector('.cia-pt1-40').value.trim(),
                    prac_test1_absent: row.querySelector('.cia-pt1-abs').checked ? 1 : 0,
                    prac_test2_40: row.querySelector('.cia-pt2-40').value.trim(),
                    prac_test2_absent: row.querySelector('.cia-pt2-abs').checked ? 1 : 0,
                    series1_50: row.querySelector('.cia-s1-50').value.trim(),
                    series1_absent: row.querySelector('.cia-s1-abs').checked ? 1 : 0,
                    series2_50: row.querySelector('.cia-s2-50').value.trim(),
                    series2_absent: row.querySelector('.cia-s2-abs').checked ? 1 : 0,
                    ese_mark_60: row.querySelector('.cia-ese-60').value.trim(),
                    ese_grade: row.querySelector('.cia-ese-grade').value,
                    ese_absent: row.querySelector('.cia-ese-abs').checked ? 1 : 0,
                });
            });

            try {
                const res = await fetch(`/api/r26/classroom/english-1001/${subjectId}/evaluate/consolidated-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ marks_data: rowsPayload })
                });
                const json = await res.json();
                if (json.status === 'SUCCESS') {
                    setSaveStatus('All Changes Saved ✓', false);
                    if (showToast) {
                        Swal.fire({ icon: 'success', title: 'Saved!', text: json.message, timer: 1300, showConfirmButton: false });
                    }
                } else {
                    setSaveStatus('Save Error', false);
                }
            } catch (err) {
                console.error(err);
                setSaveStatus('Offline / Retry', false);
            }
        }

        // Auto-Save Lesson Planner
        let lpAutoSaveTimer = null;
        function queueLessonPlanAutoSave() {
            setSaveStatus('Auto-Saving Planner...', true);
            if (lpAutoSaveTimer) clearTimeout(lpAutoSaveTimer);
            lpAutoSaveTimer = setTimeout(() => saveLessonPlannerBulk(false), 750);
        }

        function addLessonPlanRow() {
            const container = document.getElementById('lesson-plan-rows-container');
            const count = container.querySelectorAll('.lesson-plan-row').length + 1;
            const tr = document.createElement('tr');
            tr.className = 'lesson-plan-row hover:bg-slate-800/30';
            tr.dataset.id = 'new_' + Date.now();
            tr.innerHTML = `
                <td class="border border-slate-800 text-center font-bold text-sky-400 lp-day-no">#${count}</td>
                <td class="border border-slate-800 p-1.5"><input type="date" class="lp-proposed w-full px-2.5 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()"></td>
                <td class="border border-slate-800 p-1.5"><input type="date" class="lp-actual w-full px-2.5 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()"></td>
                <td class="border border-slate-800 p-1.5"><textarea class="growable-textarea lp-topic px-2.5 py-1.5 text-xs" rows="2" placeholder="Enter session topic..." oninput="autoGrow(this); queueLessonPlanAutoSave();"></textarea></td>
                <td class="border border-slate-800 p-1.5 text-center">
                    <select class="lp-module w-full px-2 py-1.5 text-xs text-center font-bold text-sky-300" onchange="queueLessonPlanAutoSave()">
                        <option value="M1">M1</option><option value="M2">M2</option><option value="M3">M3</option><option value="M4">M4</option>
                    </select>
                </td>
                <td class="border border-slate-800 p-1.5 text-center">
                    <select class="lp-co w-full px-2 py-1.5 text-xs text-center font-bold text-amber-300" onchange="queueLessonPlanAutoSave()">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="border border-slate-800 p-1.5 text-center"><input type="number" min="1" max="6" value="2" class="lp-hours no-spinners w-16 text-center px-2 py-1.5 text-xs font-bold" oninput="queueLessonPlanAutoSave()"></td>
                <td class="border border-slate-800 p-1.5">
                    <select class="lp-pedagogy w-full px-2 py-1.5 text-xs" onchange="queueLessonPlanAutoSave()">
                        <option value="Interactive Lecture (L)">Interactive Lecture (L)</option>
                        <option value="Language Lab Practicum (P)">Language Lab Practicum (P)</option>
                        <option value="Self-Learning Review (CA1)">Self-Learning Review (CA1)</option>
                        <option value="Continuous Evaluation (CE)">Continuous Evaluation (CE)</option>
                        <option value="Practical Test 1 (CA2)">Practical Test 1 (CA2)</option>
                        <option value="Practical Test 2 (CA3)">Practical Test 2 (CA3)</option>
                        <option value="Series Exam 1 (CA4)">Series Exam 1 (CA4)</option>
                        <option value="Series Exam 2 (CA5)">Series Exam 2 (CA5)</option>
                    </select>
                </td>
                <td class="border border-slate-800 p-1.5 text-center">
                    <select class="lp-status w-full px-2 py-1.5 text-xs font-semibold" onchange="queueLessonPlanAutoSave()">
                        <option value="Pending">Pending</option><option value="Completed">Completed</option>
                    </select>
                </td>
            `;
            container.appendChild(tr);
            const ta = tr.querySelector('.growable-textarea');
            if (ta) autoGrow(ta);
        }

        async function saveLessonPlannerBulk(showToast = false) {
            const plans = {};
            let idx = 1;
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const id = row.dataset.id || ('new_' + idx);
                plans[id] = {
                    day_no: idx,
                    proposed_date: row.querySelector('.lp-proposed').value,
                    actual_date: row.querySelector('.lp-actual').value,
                    topic_content: row.querySelector('.lp-topic').value,
                    module_id: row.querySelector('.lp-module').value,
                    co_tag: row.querySelector('.lp-co').value,
                    allocated_hours: row.querySelector('.lp-hours').value,
                    pedagogy: row.querySelector('.lp-pedagogy').value,
                    status: row.querySelector('.lp-status').value,
                };
                idx++;
            });

            try {
                const res = await fetch(`/api/r26/classroom/english-1001/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans })
                });
                const json = await res.json();
                if (json.status === 'SUCCESS') {
                    setSaveStatus('Planner Saved ✓', false);
                    if (showToast) {
                        Swal.fire({ icon: 'success', title: 'Lesson Planner Saved', text: json.message, timer: 1300, showConfirmButton: false });
                    }
                }
            } catch (e) {
                setSaveStatus('Planner Save Error', false);
            }
        }

        async function generateLessonTimeline() {
            const confirm = await Swal.fire({
                title: 'Regenerate Default 60-Hour Plan?',
                text: 'This will reset the lesson planner to the standard English for Communication (1001) schedule.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                confirmButtonText: 'Yes, Generate'
            });
            if (!confirm.isConfirmed) return;

            const res = await fetch(`/api/r26/classroom/english-1001/${subjectId}/lesson-plan/generate`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const json = await res.json();
            if (json.status === 'SUCCESS') {
                window.location.reload();
            }
        }

        // Syllabus Upload Form
        document.getElementById('uploadSyllabusForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('uploadBtn');
            btn.disabled = true;
            try {
                const res = await fetch(`/api/r26/classroom/english-1001/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const json = await res.json();
                if (json.status === 'SUCCESS') {
                    await Swal.fire({ icon: 'success', title: 'Uploaded!', text: json.message, timer: 1400, showConfirmButton: false });
                    window.location.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Upload Failed', text: json.message });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to upload syllabus.' });
            } finally {
                btn.disabled = false;
            }
        });

        // TEAMS Official Subject Attendance Upload Form
        document.getElementById('uploadTeamsAttendanceForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('uploadTeamsBtn');
            btn.disabled = true;
            try {
                const res = await fetch(`/api/classroom/subject/${subjectId}/upload-teams-attendance`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const json = await res.json();
                if (json.status === 'SUCCESS' || res.ok) {
                    await Swal.fire({ icon: 'success', title: 'Attendance Uploaded!', text: json.message || 'Official TEAMS attendance updated.', timer: 1500, showConfirmButton: false });
                    window.location.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Upload Error', text: json.message || 'Failed to parse attendance file.' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to upload attendance file.' });
            } finally {
                btn.disabled = false;
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            attachMarkInputValidation();
            document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));
        });
    </script>
</body>
</html>
