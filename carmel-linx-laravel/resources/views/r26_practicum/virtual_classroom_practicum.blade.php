<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practicum Virtual Classroom - {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.tailwindcss.com"></script>
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
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: #1f2937;
            --bg-card-hover: #374151;
            --border-color: #374151;
            --border-color-glow: rgba(6, 182, 212, 0.35);
            --accent-cyan: #06b6d4;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-purple: #8b5cf6;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            background-image: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 60%);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, .brand-font {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.01em;
            text-shadow: none !important;
            filter: none !important;
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        }

        .glass-card {
            background: rgba(31, 41, 55, 0.65);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: var(--border-color-glow);
            box-shadow: 0 10px 36px rgba(6, 182, 212, 0.08);
        }

        .mode-btn {
            background: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .mode-btn:hover {
            color: #ffffff;
            background: rgba(55, 65, 81, 0.6);
            border-color: rgba(6, 182, 212, 0.3);
        }

        .mode-btn.active {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.55) 0%, rgba(15, 23, 42, 0.85) 100%);
            color: #93c5fd !important;
            border: 1px solid rgba(59, 130, 246, 0.45);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.5);
        }

        .subtab-btn {
            background: transparent;
            color: var(--text-muted);
            border-bottom: 2px solid transparent;
            transition: all 0.25s ease;
            font-size: 13.5px !important;
            padding: 0.5rem 0.85rem;
            border-radius: 6px 6px 0 0;
        }

        .subtab-btn:hover {
            color: #ffffff;
            background: rgba(55, 65, 81, 0.3);
        }

        .subtab-btn.active {
            color: #60a5fa !important;
            border-bottom-color: #3b82f6;
            background: rgba(30, 58, 138, 0.25);
            font-weight: 700;
        }

        /* Form Inputs & Select Controls */
        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            background-color: #111827 !important;
            border: 1px solid var(--border-color) !important;
            color: #f9fafb !important;
            border-radius: 8px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: var(--accent-cyan) !important;
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.2) !important;
            outline: none !important;
        }

        /* Strict Table Styling & High Contrast Grid Lines */
        table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100%;
        }

        table th {
            background-color: #111827 !important;
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
            background-color: rgba(55, 65, 81, 0.35) !important;
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
            font-size: 0.8125rem !important;
            padding: 0.35rem 0.65rem !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .header-btn:hover {
            transform: translateY(-1px);
        }

        .header-btn span, .header-btn svg {
            font-size: 0.8125rem !important;
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0b0f19;
        }
        ::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 6px;
            border: 2px solid #0b0f19;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #374151;
        }
    </style>
</head>
<body class="min-h-screen pb-12">
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
            
            <!-- Left: Noticeable Back Button & Subject Details -->
            <div class="flex items-center space-x-3.5 w-full xl:w-auto">
                <a href="javascript:void(0)" onclick="window.close(); setTimeout(function() { let ref = document.referrer; if (ref && (ref.includes('/dashboard/') || ref.includes('/classroom/'))) { window.location.href = ref; } else { window.location.href = '{{ $dashboardUrl }}'; } }, 150);" class="px-4 py-2 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 hover:text-white font-semibold shadow-md transition-all flex items-center space-x-2 border border-slate-700 hover:border-cyan-500/50 flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back</span>
                </a>
                
                <div class="space-y-0.5">
                    <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ $batchSubject->subject_name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-semibold whitespace-nowrap">
                            Practicum Course ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }})
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
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-800/90 text-slate-400 border border-slate-700 text-xs font-medium whitespace-nowrap flex items-center space-x-1.5" title="AI Support API Deactivated">
                                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                <span>AI Off</span>
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-slate-400 text-xs header-subtitle">
                        Subject Code: <span class="text-white font-semibold">{{ $batchSubject->subject_code }}</span> | 
                        Batch Code: <span class="text-amber-400 font-bold font-mono">{{ $batchSubject->classroom_id }}</span> | 
                        Branch: <span class="text-blue-300 font-semibold">{{ function_exists('getFullBranchName') ? getFullBranchName($classroom->department ?? $classroom->branch ?? '') : ($classroom->department ?? $classroom->branch) }}</span> | 
                        Semester: <span class="text-white font-semibold">{{ $practicumCourseFile->semester }}</span>
                    </p>
                </div>
            </div>

            <!-- Right: Logged-In & Assigned Faculty Info -->
            <div class="px-3 py-1.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-slate-300 flex items-center space-x-2.5 flex-shrink-0 header-subtitle">
                <div class="p-1 rounded-lg bg-purple-500/20 text-purple-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="text-white font-semibold">
                    Faculty: <span class="text-purple-300 font-bold">
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
        </div>
    </header>

    <!-- 2. SUB-HEADER CONTROL CONSOLE BAR -->
    <div class="max-w-[98%] mx-auto px-4 md:px-8 mt-3">
        <div class="glass-card p-3.5 rounded-xl border border-slate-800 flex items-center justify-between flex-wrap gap-3">
            
            <!-- Hours & Assessment Details (Dynamic from Uploaded Syllabus) -->
            <div class="flex items-center space-x-3.5 flex-wrap gap-y-1.5 text-slate-300 header-subtitle">
                <span>Theory: <span class="font-bold text-blue-400">{{ $theoryHours ?? 45 }} Hrs</span> (L)</span>
                <span class="text-slate-600 font-bold">•</span>
                <span>Practical: <span class="font-bold text-emerald-400">{{ $practicalHours ?? 45 }} Hrs</span> (P)</span>
                <span class="text-slate-600 font-bold">•</span>
                <span>Total Schedule: <span class="font-bold text-purple-400">{{ $practicumCourseFile->contact_hours ?? (($theoryHours ?? 45) + ($practicalHours ?? 45)) }} Hrs</span></span>
                <span class="text-slate-600 font-bold">•</span>
                <span>CIE: <span class="font-bold text-amber-400">{{ $practicumCourseFile->cie_marks ?? 40 }}M</span> <span class="text-slate-500">|</span> ESE: <span class="font-bold text-indigo-400">{{ $practicumCourseFile->ese_marks ?? 60 }}M</span></span>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center space-x-2.5 flex-wrap gap-y-2">
                
                <!-- Upload Syllabus -->
                <button onclick="openSyllabusModal()" class="header-btn px-3.5 py-2 rounded-lg bg-blue-600/20 hover:bg-blue-600/35 border border-blue-500/40 text-blue-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload Syllabus</span>
                </button>

                <!-- View Syllabus PDF -->
                @if($practicumCourseFile->syllabus_pdf_path)
                <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" class="header-btn px-3.5 py-2 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 border border-emerald-500/40 text-emerald-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>View Syllabus PDF</span>
                </a>
                @endif

                <!-- Course File Console -->
                <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" class="header-btn px-3.5 py-2 rounded-lg bg-purple-600/20 hover:bg-purple-600/35 border border-purple-500/40 text-purple-300 font-bold transition-all flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Course File Console</span>
                </a>

                <!-- Fullscreen Button -->
                <button onclick="toggleFullscreen()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition-all flex items-center space-x-1" title="Toggle Fullscreen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
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
            <div class="glass-card p-1.5 rounded-xl flex items-center space-x-1.5 overflow-x-auto">
                <button onclick="switchTheorySubtab('overview')" id="theory-tab-overview" class="subtab-btn active px-2.5 py-1.5 rounded-lg font-semibold whitespace-nowrap">📘 Modules & COs</button>
                <button onclick="switchTheorySubtab('planner')" id="theory-tab-planner" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Lesson Plan</button>
                <button onclick="switchTheorySubtab('sl')" id="theory-tab-sl" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📝 Self-Learning</button>
                <button onclick="switchTheorySubtab('series')" id="theory-tab-series" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">✍️ Theory Series</button>
                <button onclick="switchTheorySubtab('ese')" id="theory-tab-ese" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🏆 Theory ESE</button>
                <button onclick="switchTheorySubtab('surveys')" id="theory-tab-surveys" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📊 Surveys</button>
                <button onclick="switchTheorySubtab('attendance')" id="theory-tab-attendance" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Attendance</button>
                <button onclick="switchTheorySubtab('materials')" id="theory-tab-materials" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📁 Study Materials & Pre-Class Hub</button>
            </div>

            <!-- Subtab 1: Theory Modules, COs & CO-PO Mapping Table -->
            <div id="theory-subcontent-overview" class="space-y-5">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="lg:col-span-2 space-y-4">
                        @foreach(($practicumCourseFile->parsed_modules ?? []) as $mod)
                        <div class="glass-card p-4 rounded-xl border border-slate-800">
                            <div class="flex items-center justify-between mb-1.5">
                                <h3 class="font-bold text-blue-400">Module {{ $mod['module_id'] }}: {{ $mod['title'] }}</h3>
                                <span class="px-2.5 py-0.5 rounded bg-blue-500/10 text-blue-300 font-semibold text-xs">{{ $mod['hours'] ?? 15 }} Lecture Hours</span>
                            </div>
                            <p class="text-slate-300 leading-relaxed">{{ $mod['content'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4">
                        <div class="glass-card p-4 rounded-xl border border-slate-800 space-y-3">
                            <div class="flex items-center justify-between gap-2 whitespace-nowrap">
                                <h3 class="font-bold text-emerald-400 text-base whitespace-nowrap">🔬 Practical Lab Experiments Summary</h3>
                                <span class="text-xs px-2.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 font-semibold border border-emerald-500/20 whitespace-nowrap flex-shrink-0">{{ $practicalHours ?? 45 }} P Hours</span>
                            </div>
                            <div class="space-y-2 max-h-[520px] overflow-y-auto pr-1">
                                @foreach(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                                <div class="p-3 rounded-xl bg-slate-900/70 border border-slate-800/80">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-emerald-400 text-xs">{{ $exp['experiment_no'] }}</span>
                                        <span class="px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 text-xs font-semibold border border-purple-500/20">{{ $exp['co_id'] }}</span>
                                    </div>
                                    <p class="text-slate-200 text-sm font-medium leading-snug">{{ $exp['title'] }}</p>
                                    <div class="text-slate-400 text-xs mt-1 font-semibold">{{ $exp['hours'] ?? 3 }} Hours Session</div>
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
                            <span>🎯 Course Articulation Matrix (CO-PO Mapping)</span>
                        </h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-slate-400 text-xs font-medium">Correlation: 3 = High, 2 = Med, 1 = Low</span>
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
                                    <th class="p-2.5 w-12 font-bold text-indigo-400">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach(($practicumCourseFile->parsed_cos ?? []) as $co)
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3 text-left font-bold text-amber-400">{{ $co['id'] }}</td>
                                    <td class="p-3 text-left text-slate-300 text-sm">{{ $co['description'] }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                        @php
                                            $val = $mappings[$co['id']]['PO' . $p] ?? '-';
                                        @endphp
                                        <td class="p-2.5 font-bold {{ $val !== '-' ? 'text-emerald-400 bg-emerald-500/10' : 'text-slate-500' }}">
                                            {{ $val }}
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
                        <button onclick="addCustomLessonPlanRow('lp-theory-tbody', 'L')" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
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
 
                <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                    <table class="w-full text-left border-collapse lp-table">
                        <thead class="sticky top-0 z-10 bg-slate-900 shadow">
                            <tr class="border-b border-slate-800 text-slate-400 font-normal text-xs uppercase tracking-wider">
                                <th class="p-3 w-16 text-center">Day/Hr</th>
                                <th class="p-3 w-36">Pedagogy</th>
                                <th class="p-3 w-32">Proposed Date</th>
                                <th class="p-3 w-32">Actual Date</th>
                                <th class="p-3 w-[40%]">Topic & Content Description</th>
                                <th class="p-3 w-28 text-center">CO</th>
                                <th class="p-3 w-32">Sub-Batch</th>
                                <th class="p-3 w-24 text-center">Hours Needed</th>
                                <th class="p-3 w-32">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="lp-theory-tbody" class="divide-y divide-slate-800/60 text-sm">
                            @foreach($lessonPlans->whereIn('mode', ['L', 'ST']) as $plan)
                            <tr id="lp-row-{{ $plan->id }}" data-plan-id="{{ $plan->id }}" class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2.5 font-normal text-center text-white">{{ $plan->day_no }}</td>
                                <td class="p-2.5">
                                    <select id="lp-pedagogy-{{ $plan->id }}" onchange="onPedagogyChange({{ $plan->id }}, this.value)" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs w-full {{ $plan->mode === 'L' ? 'text-blue-400' : ($plan->mode === 'P' ? 'text-emerald-400' : 'text-purple-400') }}">
                                        <option value="Lecture (L)" {{ ($plan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($plan->mode === 'L' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                        <option value="Practical Lab (P)" {{ ($plan->pedagogy ?? '') === 'Practical Lab (P)' || ($plan->mode === 'P' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                        <option value="Theory Series Exam (ST)" {{ ($plan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($plan->mode === 'ST' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                        <option value="Practical Series Exam (SP)" {{ ($plan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($plan->mode === 'SP' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                        <option value="PPT Presentation" {{ ($plan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                        <option value="Demonstration" {{ ($plan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Group Activity" {{ ($plan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                    </select>
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-prop-{{ $plan->id }}" value="{{ $plan->proposed_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-act-{{ $plan->id }}" value="{{ $plan->actual_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <textarea id="lp-topic-{{ $plan->id }}" rows="2" class="bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-sm font-normal w-full focus:border-blue-500 outline-none resize-y leading-snug" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $plan->topic_content }}</textarea>
                                </td>
                                <td class="p-2.5 text-center">
                                    <select id="lp-co-{{ $plan->id }}" class="bg-slate-900 border border-amber-500/40 rounded px-2 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOpt)
                                            <option value="{{ $coOpt }}" {{ ($plan->co_id ?? 'CO1') === $coOpt ? 'selected' : '' }} style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="lp-batch-td-{{ $plan->id }}" class="p-2.5">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        <select id="lp-batch-{{ $plan->id }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs text-emerald-400 w-full">
                                            <option value="Batch A & B" {{ ($plan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                            <option value="Batch A" {{ ($plan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                            <option value="Batch B" {{ ($plan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                        </select>
                                    @else
                                        <span class="px-2.5 py-1 rounded bg-slate-900/80 text-slate-400 font-normal text-xs border border-slate-800 inline-block">
                                            All Students
                                        </span>
                                        <input type="hidden" id="lp-batch-{{ $plan->id }}" value="All Students">
                                    @endif
                                </td>
                                <td id="lp-hours-td-{{ $plan->id }}" class="p-2.5 text-center font-normal">
                                    @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-normal">3 Hours</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-normal">1 Hour</span>
                                    @endif
                                </td>
                                <td class="p-2.5">
                                    <input type="text" id="lp-remarks-{{ $plan->id }}" value="{{ $plan->remarks }}" placeholder="Status/Remarks" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-400 text-xs w-full">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                    <button type="button" onclick="addCustomLessonPlanRow('lp-theory-tbody', 'L')" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 rounded-lg text-xs font-semibold shadow transition-all flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Row (Customization)</span>
                    </button>
                    <span class="text-xs text-slate-400">Add custom lesson topics or extra hours as needed. All CO fields are fully editable.</span>
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
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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

                <!-- QP Generator Panel — 4 Cards -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 no-print">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 flex-wrap">
                                <span>📄 Series Exam QP Generator</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold">
                                    {{ $subjectType['label'] ?? '💻 Program Core - ESE 100M' }}
                                </span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-1">
                                @if(($subjectType['pattern'] ?? '') === 'table_4_2_design')
                                    Table 4.2 Design Paper: Part A (6×5=30M) + Part B (2×10=20M) = 50 Marks | 2 Hours
                                @else
                                    Single CO Test: Part A (2×1=2M) + Part B (3×3=9M) + Part C (answer any 2 of 3 × 7=14M) = 25 Marks | 1½ Hours
                                @endif
                                | Scaled to 10 CIA Marks
                            </p>
                        </div>
                    </div>

                    <!-- 4 Series Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Series 1' => 'CO1', 'Series 2' => 'CO2', 'Series 3' => 'CO3', 'Series 4' => 'CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-900/15' : 'border-slate-700 bg-slate-800/50' }} p-3 flex flex-col gap-2">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white text-sm">{{ $series }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $savedQp ? 'bg-emerald-600/30 text-emerald-300' : 'bg-slate-700 text-slate-400' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-400 font-semibold">✅ QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col gap-1.5 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 transition-all text-center">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 transition-all text-center">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-700/50 pt-2 flex flex-col gap-1.5">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-indigo-950/40 hover:bg-indigo-900/60 border border-indigo-500/30 text-indigo-300 text-center block">
                                    🖨️ Print QP
                                </a>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-750 text-slate-300 text-center block">
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
                            <h3 class="text-base font-semibold text-slate-200">Theory Series Examinations</h3>
                            <p class="text-slate-400 text-xs mt-0.5">4 Series Tests (CO1, CO2, CO3, CO4 - 2 Hours each out of 50 marks), averaged and scaled to 10 CIA marks</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory Series Examinations Report', 'theory-subcontent-series')" class="header-btn px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openSeriesTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory Series Marks</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-medium bg-slate-900/60 text-sm">
                                    <th class="p-3">Roll</th>
                                    <th class="p-3">SBTE Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3 text-center">Test 1 (CO1)</th>
                                    <th class="p-3 text-center">Test 2 (CO2)</th>
                                    <th class="p-3 text-center">Test 3 (CO3)</th>
                                    <th class="p-3 text-center">Test 4 (CO4)</th>
                                    <th class="p-3 text-center">Avg (/50)</th>
                                    <th class="p-3 text-center">Converted CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                @php
                                    $stEvals = $seriesTheoryEvals->get($res['reg_no'], collect());
                                    $s1 = $stEvals->whereIn('series_no', ['Series 1', 'CO1'])->first();
                                    $s2 = $stEvals->whereIn('series_no', ['Series 2', 'CO2'])->first();
                                    $s3 = $stEvals->whereIn('series_no', ['Series 3', 'CO3'])->first();
                                    $s4 = $stEvals->whereIn('series_no', ['Series 4', 'CO4'])->first();
                                @endphp
                                <tr>
                                    <td class="p-3 text-slate-300 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-300 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center text-slate-300 font-normal">{{ $s1 ? number_format($s1->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-300 font-normal">{{ $s2 ? number_format($s2->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-300 font-normal">{{ $s3 ? number_format($s3->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-300 font-normal">{{ $s4 ? number_format($s4->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-300 font-normal">{{ number_format($res['series_theory_marks'] * 5, 2) }}</td>
                                    <td class="p-3 text-center text-slate-400 font-normal">{{ number_format($res['series_theory_marks'], 2) }} / 10.00</td>
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
            <div id="theory-subcontent-ese" class="space-y-5 hidden">
                <div class="glass-card p-5 rounded-xl border border-slate-800">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-white">Written Theory End Semester Exam (60 Marks)</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Board Theory ESE Grades evaluated per Official Revision 2026 Grading System Standard</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory ESE & Overall Results Report', 'theory-subcontent-ese')" class="header-btn px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openEseTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory ESE Grades</button>
                        </div>
                    </div>

                    <!-- Official R2026 Grade Scale Legend Box -->
                    <div class="p-3 mb-4 rounded-xl bg-slate-900/80 border border-slate-800 text-xs">
                        <div class="font-bold text-slate-300 mb-2 uppercase tracking-wide">Revision 2026 Official Grading System Standard (Theory ESE)</div>
                        <div class="grid grid-cols-7 gap-1 text-center font-mono">
                            <div class="p-1.5 rounded bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                <div class="font-bold text-sm">S</div>
                                <div class="text-[10px] opacity-80">≥90%</div>
                                <div class="text-[10px] text-slate-400">GP: 10</div>
                            </div>
                            <div class="p-1.5 rounded bg-blue-500/10 border border-blue-500/30 text-blue-400">
                                <div class="font-bold text-sm">A</div>
                                <div class="text-[10px] opacity-80">80–89%</div>
                                <div class="text-[10px] text-slate-400">GP: 9</div>
                            </div>
                            <div class="p-1.5 rounded bg-indigo-500/10 border border-indigo-500/30 text-indigo-400">
                                <div class="font-bold text-sm">B</div>
                                <div class="text-[10px] opacity-80">70–79%</div>
                                <div class="text-[10px] text-slate-400">GP: 8</div>
                            </div>
                            <div class="p-1.5 rounded bg-purple-500/10 border border-purple-500/30 text-purple-400">
                                <div class="font-bold text-sm">C</div>
                                <div class="text-[10px] opacity-80">60–69%</div>
                                <div class="text-[10px] text-slate-400">GP: 7</div>
                            </div>
                            <div class="p-1.5 rounded bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                <div class="font-bold text-sm">D</div>
                                <div class="text-[10px] opacity-80">50–59%</div>
                                <div class="text-[10px] text-slate-400">GP: 6</div>
                            </div>
                            <div class="p-1.5 rounded bg-orange-500/10 border border-orange-500/30 text-orange-400">
                                <div class="font-bold text-sm">E</div>
                                <div class="text-[10px] opacity-80">40–49%</div>
                                <div class="text-[10px] text-slate-400">GP: 5</div>
                            </div>
                            <div class="p-1.5 rounded bg-rose-500/10 border border-rose-500/30 text-rose-400">
                                <div class="font-bold text-sm">F</div>
                                <div class="text-[10px] opacity-80">&lt;40%</div>
                                <div class="text-[10px] text-slate-400">GP: 0</div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs table-compact-header">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/80">
                                    <th class="p-2.5 w-12 text-center">Roll</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Board Theory ESE Grade</th>
                                    <th class="p-2.5 text-center">Pass / Fail Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-xs">
                                @foreach($studentResults as $res)
                                @php
                                    $grade = strtoupper($res['ese_theory_grade'] ?? '-');
                                    if ($grade === 'S') { $gc = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30'; }
                                    elseif ($grade === 'A') { $gc = 'text-blue-400 bg-blue-500/10 border-blue-500/30'; }
                                    elseif ($grade === 'B') { $gc = 'text-indigo-400 bg-indigo-500/10 border-indigo-500/30'; }
                                    elseif ($grade === 'C') { $gc = 'text-purple-400 bg-purple-500/10 border-purple-500/30'; }
                                    elseif ($grade === 'D') { $gc = 'text-amber-400 bg-amber-500/10 border-amber-500/30'; }
                                    elseif ($grade === 'E') { $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                    elseif (in_array($grade, ['F', 'FE', 'ABSENT', 'ABS'])) { $gc = 'text-rose-400 bg-rose-500/10 border-rose-500/30'; }
                                    else { $gc = 'text-slate-400 bg-slate-800 border-slate-700'; }

                                    $isFail = in_array($grade, ['F', 'FE', 'ABSENT', 'ABS']);
                                @endphp
                                <tr class="hover:bg-slate-800/30 transition-all">
                                    <td class="p-2.5 text-center text-slate-300">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center font-bold">
                                        <span class="px-3 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $grade !== '-' ? $grade : 'Not Entered' }}</span>
                                    </td>
                                    <td class="p-2.5 text-center font-semibold {{ !$isFail && $grade !== '-' ? 'text-emerald-400' : ($isFail ? 'text-rose-400' : 'text-slate-400') }}">
                                        {{ $grade === '-' ? '-' : (!$isFail ? 'PASSED' : 'REAPPEAR / FAIL') }}
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
                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
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
                                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center flex-shrink-0">
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
                        <span class="font-bold text-indigo-400 uppercase tracking-wide">Attainment Scaling Standard:</span>
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
                        <span class="material-symbols-rounded text-indigo-400">grid_on</span>
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
                <div class="glass-card p-6 rounded-xl border border-slate-800 space-y-4">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                            <span>📅 Course Attendance Reports</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-1">Select and print the detailed session attendance register or the consolidated final attendance report.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1: Detailed Register -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-cyan-500/20 hover:border-cyan-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-cyan-400 transition-all">Detailed Attendance Register</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the complete, session-by-session student attendance grid with specific dates, hourly remarks, percentage logs, and marks calculation.</p>
                                </div>
                                <span class="material-symbols-rounded text-cyan-400 bg-cyan-500/10 p-3 rounded-xl text-2xl flex-shrink-0">view_list</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-report" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-800 hover:bg-slate-700 text-cyan-300 hover:text-white border border-cyan-500/40 hover:border-cyan-400 transition-all shadow-md no-underline block">
                                Open Detailed Register
                            </a>
                        </div>

                        <!-- Card 2: Consolidated Report -->
                        <div class="p-5 rounded-2xl bg-slate-900/80 border border-emerald-500/20 hover:border-emerald-500/50 shadow-lg transition-all duration-300 group flex flex-col justify-between space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <h4 class="text-base font-bold text-slate-100 group-hover:text-emerald-400 transition-all">Consolidated Attendance Report</h4>
                                    <p class="text-slate-400 text-xs leading-relaxed">View and print the consolidated A4 report showing the total theory conducted/present, practical conducted/present, and the final average attendance percentage for CIA preparation.</p>
                                </div>
                                <span class="material-symbols-rounded text-emerald-400 bg-emerald-500/10 p-3 rounded-xl text-2xl flex-shrink-0">analytics</span>
                            </div>
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="w-full text-center px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-800 hover:bg-slate-700 text-emerald-300 hover:text-white border border-emerald-500/40 hover:border-emerald-400 transition-all shadow-md no-underline block">
                                Open Consolidated Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtab 8: Study Materials & Pre-Class Hub -->
            <div id="theory-subcontent-materials" class="hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum'])
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: VIRTUAL LAB (PRACTICUM)                                           -->
        <!-- ========================================================================= -->
        <div id="mode-lab-container" class="space-y-5 hidden">
            
            <!-- Lab Sub-Tabs Navigation -->
            <div class="glass-card p-1.5 rounded-xl flex items-center space-x-1.5 overflow-x-auto">
                <button onclick="switchLabSubtab('roster')" id="lab-tab-roster" class="subtab-btn active px-2.5 py-1.5 rounded-lg font-semibold whitespace-nowrap">🧪 Lab Sessions</button>
                <button onclick="switchLabSubtab('planner')" id="lab-tab-planner" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📅 Lab Planner</button>
                <button onclick="switchLabSubtab('eval')" id="lab-tab-eval" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🔬 Lab Eval</button>
                <button onclick="switchLabSubtab('series')" id="lab-tab-series" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📝 Lab Series</button>
                <button onclick="switchLabSubtab('ese')" id="lab-tab-ese" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">🏆 Lab ESE</button>
                <button onclick="switchLabSubtab('materials')" id="lab-tab-materials" class="subtab-btn px-2.5 py-1.5 rounded-lg font-semibold text-slate-300 hover:text-white whitespace-nowrap">📁 Pre-Lab Materials</button>
            </div>

            <div id="lab-subcontent-materials" class="hidden">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practicum Lab'])
            </div>

            <!-- Subtab 1: 3-Hour Session Experiments Roster -->
            <div id="lab-subcontent-roster" class="glass-card p-5 rounded-xl border border-slate-800">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-white mb-1">Practical Experiments Roster (3-Hour Session Blocks)</h3>
                        <p class="text-slate-400 text-xs">All practical topics automatically divided into 3-hour lab sessions as per Revision 2026 rules.</p>
                    </div>
                    <button onclick="printSubtabReport('Practical Experiments Roster Report', 'lab-subcontent-roster')" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print flex items-center space-x-1.5">
                        <span>🖨️ Print Report</span>
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/60">
                                <th class="p-3">Session Code</th>
                                <th class="p-3">Experiment Title</th>
                                <th class="p-3">Mapped CO</th>
                                <th class="p-3">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                            <tr class="hover:bg-slate-800/30 transition-all">
                                <td class="p-3 font-bold text-emerald-400">{{ $exp['experiment_no'] }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $exp['title'] }}</td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded bg-purple-500/10 text-purple-300 font-semibold border border-purple-500/20 text-xs">{{ $exp['co_id'] }}</span></td>
                                <td class="p-3 text-slate-300 font-semibold">{{ $exp['hours'] ?? 3 }} Hours (1 Session)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
 
                <div class="overflow-x-auto max-h-[650px] overflow-y-auto">
                    <table class="w-full text-left border-collapse lp-table">
                        <thead class="sticky top-0 z-10 bg-slate-900 shadow">
                            <tr class="border-b border-slate-800 text-slate-400 font-normal text-xs uppercase tracking-wider">
                                <th class="p-3 w-16 text-center">Day/Hr</th>
                                <th class="p-3 w-36">Pedagogy</th>
                                <th class="p-3 w-32">Proposed Date</th>
                                <th class="p-3 w-32">Actual Date</th>
                                <th class="p-3 w-[40%]">Topic & Content Description</th>
                                <th class="p-3 w-24 text-center">CO</th>
                                <th class="p-3 w-32">Sub-Batch</th>
                                <th class="p-3 w-24 text-center">Hours Needed</th>
                                <th class="p-3 w-32">Remarks</th>
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
                                <td class="p-2.5 font-normal text-center text-white">Session {{ $sIdx + 1 }}</td>
                                <td class="p-2.5">
                                    <select id="lp-pedagogy-{{ $firstPlan->id }}" onchange="onPedagogyChange({{ $firstPlan->id }}, this.value)" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs w-full text-emerald-400">
                                        <option value="Practical Lab (P)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Lab (P)' || ($firstPlan->mode === 'P' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                        <option value="Practical Series Exam (SP)" {{ ($firstPlan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($firstPlan->mode === 'SP' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                        <option value="Lecture (L)" {{ ($firstPlan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($firstPlan->mode === 'L' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                        <option value="Theory Series Exam (ST)" {{ ($firstPlan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($firstPlan->mode === 'ST' && !isset($firstPlan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                        <option value="PPT Presentation" {{ ($firstPlan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                        <option value="Demonstration" {{ ($firstPlan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Group Activity" {{ ($firstPlan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                    </select>
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-prop-{{ $firstPlan->id }}" value="{{ $firstPlan->proposed_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <input type="date" id="lp-act-{{ $firstPlan->id }}" value="{{ $firstPlan->actual_date }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                                </td>
                                <td class="p-2.5">
                                    <textarea id="lp-topic-{{ $firstPlan->id }}" rows="2" class="bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-sm font-normal w-full focus:border-emerald-500 outline-none resize-y leading-snug" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $cleanTopic }}</textarea>
                                </td>
                                <td class="p-2.5 text-center">
                                    <select id="lp-co-{{ $firstPlan->id }}" class="bg-slate-900 border border-amber-500/40 rounded px-2 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOpt)
                                            <option value="{{ $coOpt }}" {{ ($firstPlan->co_id ?? 'CO1') === $coOpt ? 'selected' : '' }} style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="lp-batch-td-{{ $firstPlan->id }}" class="p-2.5">
                                    <select id="lp-batch-{{ $firstPlan->id }}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs text-emerald-400 w-full">
                                        <option value="Batch A & B" {{ ($firstPlan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                        <option value="Batch A" {{ ($firstPlan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                        <option value="Batch B" {{ ($firstPlan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                    </select>
                                </td>
                                <td id="lp-hours-td-{{ $firstPlan->id }}" class="p-2.5 text-center font-normal">
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-normal">3 Hours</span>
                                </td>
                                <td class="p-2.5">
                                    <input type="text" id="lp-remarks-{{ $firstPlan->id }}" value="{{ $firstPlan->remarks }}" placeholder="Status/Remarks" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-400 text-xs w-full">
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="p-5 text-center text-slate-400 font-normal">No practical hours scheduled yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 3: Continuous Practical Evaluation -->
            <div id="lab-subcontent-eval" class="glass-card p-5 rounded-xl border border-slate-800 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-normal text-white">Continuous Practical Evaluation (CE - 10 CIA Marks)</h3>
                        <p class="text-slate-400 text-xs mt-0.5 font-normal">Table 2.2 Rubrics (Criteria 1 to 6 out of 50 Marks) converted to 10 CIA marks</p>
                    </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Continuous Lab Evaluation (CE - 10M) Report', 'lab-subcontent-eval')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openExperimentEvalModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs shadow-sm transition-all">Evaluate Experiment</button>
                        </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-normal uppercase bg-slate-900/80">
                                <th class="p-2.5 w-12 text-center">Roll</th>
                                <th class="p-2.5">SBTE Reg No</th>
                                <th class="p-2.5">Student Name</th>
                                <th class="p-2.5 text-center">Prep (10M)</th>
                                <th class="p-2.5 text-center">Setup (10M)</th>
                                <th class="p-2.5 text-center">Obs (5M)</th>
                                <th class="p-2.5 text-center">Analysis (10M)</th>
                                <th class="p-2.5 text-center">Viva (10M)</th>
                                <th class="p-2.5 text-center">Work (5M)</th>
                                <th class="p-2.5 text-center">Total Avg (/50)</th>
                                <th class="p-2.5 text-center">Converted CIA (/10M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-normal text-xs">
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
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2.5 text-center text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-2.5 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2.5 text-white">{{ $res['name'] }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgPrep, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgSetup, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgObs, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgAnalysis, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgViva, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($avgWorkmanship, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-200">{{ number_format($totalAvg50, 2) }}</td>
                                <td class="p-2.5 text-center text-amber-300">{{ number_format($res['continuous_eval_marks'], 1) }} / 10.0</td>
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
                        <h3 class="text-base font-semibold text-slate-200">Practical Series Examinations</h3>
                    </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Practical Series Examinations Report', 'lab-subcontent-series')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openSeriesPracticalModal()" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all">Enter Lab Series Test Marks</button>
                        </div>
                </div>
 
                <!-- Practical QP Generator Panel -->
                <div class="glass-card p-5 rounded-xl border border-slate-800 space-y-4 mb-5 no-print">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400">
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
                                    class="w-full py-2 rounded-lg text-xs font-semibold bg-indigo-950/40 hover:bg-indigo-900/60 border border-indigo-500/30 text-indigo-300 text-center block no-underline">
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
                    <table class="w-full text-left border-collapse text-xs table-compact-header">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-normal uppercase bg-slate-900/80">
                                <th class="p-2.5 w-12 text-center">Roll</th>
                                <th class="p-2.5">SBTE Reg No</th>
                                <th class="p-2.5">Student Name</th>
                                <th class="p-2.5 text-center">Writeup (10M)</th>
                                <th class="p-2.5 text-center">Setup (10M)</th>
                                <th class="p-2.5 text-center">Obs/Result (8M)</th>
                                <th class="p-2.5 text-center">Viva (8M)</th>
                                <th class="p-2.5 text-center">Record (4M)</th>
                                <th class="p-2.5 text-center">Test 1 (/40)</th>
                                <th class="p-2.5 text-center">Test 2 (/40)</th>
                                <th class="p-2.5 text-center">Avg (/40)</th>
                                <th class="p-2.5 text-center">Converted CIA (/10M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-normal text-xs">
                            @foreach($studentResults as $res)
                            @php
                                $spEvals = $seriesPracticalEvals->get($res['reg_no'], collect());
                                $sp1 = $spEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)'])->first();
                                $sp2 = $spEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)'])->first();
                                $spCount = $spEvals->count();
                                $spWriteup = $spCount > 0 ? $spEvals->avg('writeup_procedure') : 0;
                                $spSetup = $spCount > 0 ? $spEvals->avg('setup_execution') : 0;
                                $spObs = $spCount > 0 ? $spEvals->avg('observation_result') : 0;
                                $spViva = $spCount > 0 ? $spEvals->avg('viva_voce') : 0;
                                $spRecord = $spCount > 0 ? $spEvals->avg('record_completion') : 0;
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-all">
                                <td class="p-2.5 text-center text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-2.5 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2.5 text-white">{{ $res['name'] }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($spWriteup, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($spSetup, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($spObs, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($spViva, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-300">{{ number_format($spRecord, 1) }}</td>
                                <td class="p-2.5 text-center text-slate-200">{{ $sp1 ? number_format($sp1->total_score_40, 2) : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-200">{{ $sp2 ? number_format($sp2->total_score_40, 2) : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-200">{{ number_format($res['series_practical_marks'] * 4, 2) }}</td>
                                <td class="p-2.5 text-center text-blue-300">{{ number_format($res['series_practical_marks'], 1) }} / 10.0</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Subtab 5: Practical ESE -->
            <div id="lab-subcontent-ese" class="glass-card p-5 rounded-xl border border-blue-600/40 bg-gradient-to-br from-slate-900 via-slate-900/95 to-blue-950/20 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-blue-300 flex items-center space-x-2">
                            <span>🏆 Institutional Practical End Semester Exam (40 Marks)</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-0.5">
                            Rubrics splitup: Procedure (10M) + Setup (10M) + Result (8M) + Viva (8M) + Record (4M) = 40 Marks
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="printSubtabReport('Practical End Semester Exam (ESE) Report', 'lab-subcontent-ese')" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                        <button onclick="openEsePracticalModal()" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs shadow-sm transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Enter Practical ESE Marks</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold bg-slate-900/80">
                                <th class="p-2.5 w-12 text-center">Roll</th>
                                <th class="p-2.5">SBTE Reg No</th>
                                <th class="p-2.5">Student Name</th>
                                <th class="p-2.5 text-center">Writeup (10M)</th>
                                <th class="p-2.5 text-center">Setup (10M)</th>
                                <th class="p-2.5 text-center">Obs/Result (8M)</th>
                                <th class="p-2.5 text-center">Viva (8M)</th>
                                <th class="p-2.5 text-center">Record (4M)</th>
                                <th class="p-2.5 text-center">Practical ESE Total</th>
                                <th class="p-2.5 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60" id="ese-practical-table-body">
                            @foreach($studentResults as $res)
                            @php
                                $score = $res['ese_practical'] ?? 0;
                                $pct = ($score / 40) * 100;
                                if ($pct >= 90) { $g = 'S'; $gc = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30'; }
                                elseif ($pct >= 80) { $g = 'A'; $gc = 'text-blue-400 bg-blue-500/10 border-blue-500/30'; }
                                elseif ($pct >= 70) { $g = 'B'; $gc = 'text-indigo-400 bg-indigo-500/10 border-indigo-500/30'; }
                                elseif ($pct >= 60) { $g = 'C'; $gc = 'text-purple-400 bg-purple-500/10 border-purple-500/30'; }
                                elseif ($pct >= 50) { $g = 'D'; $gc = 'text-amber-400 bg-amber-500/10 border-amber-500/30'; }
                                elseif ($pct >= 40) { $g = 'E'; $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                else { $g = 'F'; $gc = 'text-rose-400 bg-rose-500/10 border-rose-500/30'; }
                                
                                $wInit = number_format(($score / 40.0) * 10.0, 1);
                                $sInit = number_format(($score / 40.0) * 10.0, 1);
                                $rInit = number_format(($score / 40.0) * 8.0, 1);
                                $vInit = number_format(($score / 40.0) * 8.0, 1);
                                $recInit = number_format(($score / 40.0) * 4.0, 1);
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-all" id="ese-row-{{ $res['reg_no'] }}">
                                <td class="p-2.5 text-center text-slate-300">{{ $res['roll_no'] }}</td>
                                <td class="p-2.5 font-mono text-slate-300 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-2.5 text-center text-slate-300 ese-val-writeup">{{ $score > 0 ? $wInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-300 ese-val-setup">{{ $score > 0 ? $sInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-300 ese-val-result">{{ $score > 0 ? $rInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-300 ese-val-viva">{{ $score > 0 ? $vInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-300 ese-val-record">{{ $score > 0 ? $recInit : '-' }}</td>
                                <td class="p-2.5 text-center font-bold text-blue-400 ese-val-total">{{ round($score) }}</td>
                                <td class="p-2.5 text-center ese-val-grade">
                                    <span class="px-2.5 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $g }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                            <input type="checkbox" name="configs[{{ $coTag }}][{{ $actKey }}]" value="1" class="rounded bg-slate-800 border-slate-700 text-blue-500 focus:ring-0">
                            <span>{{ $actLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold hover:bg-slate-700 text-xs">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Save Activities Config</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enter Self-Learning Marks Modal (CA1 Activity-Wise Sliders) -->
    <div id="sl-marks-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-3xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white">Continuous Assessment Activity Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Adjust sliders or tap +/- steppers to evaluate activity-wise splitup for each student.</p>
                </div>
                <button onclick="closeSlMarksModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevSlStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="sl-student-select" onchange="loadSlStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-1.5 font-bold text-sm text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextSlStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 p-3 rounded-xl border border-indigo-500/30 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-medium">Selected Student Average:</span>
                    <span id="sl-student-total-raw" class="font-extrabold text-amber-400 text-sm ml-1.5">0.00 / 15.00 M</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-slate-400 font-medium">Converted CA1 CIA:</span>
                    <span id="sl-student-converted-cia" class="font-black text-emerald-400 text-base ml-1.5 px-2.5 py-0.5 rounded bg-emerald-500/20 border border-emerald-500/30">0.00 / 5.00 M</span>
                </div>
            </div>

            <!-- Scrollable Activity Sliders Container -->
            <div id="sl-sliders-container" class="overflow-y-auto space-y-4 flex-1 pr-1">
                <!-- Dynamically populated by JS loadSlStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSlMarksModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSlStudent()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSlMarks()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Practical ESE Evaluator Modal (Blue Theme Sliders) -->
    <div id="ese-practical-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-3xl w-full p-5 rounded-2xl border border-blue-500/40 shadow-2xl space-y-4 max-h-[92vh] flex flex-col bg-slate-950">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-blue-300 flex items-center space-x-2">
                        <span>🏆 Institutional Practical ESE Evaluator</span>
                    </h3>
                    <p class="text-slate-400 text-xs mt-0.5">Adjust rubrics sliders or steppers for procedure, setup, result, viva, and record.</p>
                </div>
                <button onclick="closeEsePracticalModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-slate-950 border border-blue-500/40 rounded-lg px-3 py-1.5 font-bold text-sm text-blue-200 outline-none focus:border-blue-400">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextEseStudent()" class="px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-gradient-to-r from-slate-900 via-blue-950/40 to-slate-900 p-3 rounded-xl border border-blue-500/40 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-medium">Practical ESE Score:</span>
                    <span id="ese-student-total-raw" class="font-extrabold text-blue-400 text-base ml-1.5">0.00 / 40.00 Marks</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="text-slate-400 font-medium">Evaluated Grade:</span>
                    <span id="ese-student-grade-badge" class="font-black text-blue-300 text-base px-3 py-0.5 rounded-full bg-blue-500/20 border border-blue-500/30">S</span>
                </div>
            </div>

            <!-- Scrollable Rubric Sliders Container -->
            <div id="ese-sliders-container" class="overflow-y-auto space-y-3.5 flex-1 pr-1">
                <!-- Dynamically populated by JS loadEseStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeEsePracticalModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs border border-slate-700">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseMarks()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-sm">Save All ESE Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Theory ESE Grade Entry Modal -->
    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-3xl w-full p-5 rounded-2xl border border-indigo-500/40 shadow-2xl space-y-4 max-h-[92vh] flex flex-col bg-slate-950">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-indigo-300">Board Theory ESE Grade Entry</h3>
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
                                <select id="ese-theory-grade-{{ $res['reg_no'] }}" class="bg-slate-900 border border-slate-700 rounded px-3 py-1 text-xs font-bold text-indigo-300 outline-none focus:border-indigo-400">
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
                <button type="button" onclick="saveAllEseTheoryGrades()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Save Theory Grades</button>
            </div>
        </div>
    </div>

    <!-- JavaScript Switching & Handlers -->
    <script>
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
            ['overview', 'planner', 'sl', 'series', 'ese', 'surveys', 'attendance', 'materials'].forEach(t => {
                document.getElementById('theory-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('theory-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('theory-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('theory-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_theory_subtab', tab);
            if (tab === 'materials' && typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
            }
        }

        function switchLabSubtab(tab) {
            ['roster', 'planner', 'eval', 'series', 'ese', 'materials'].forEach(t => {
                document.getElementById('lab-subcontent-' + t)?.classList.add('hidden');
                document.getElementById('lab-tab-' + t)?.classList.remove('active', 'text-white');
            });
            document.getElementById('lab-subcontent-' + tab)?.classList.remove('hidden');
            document.getElementById('lab-tab-' + tab)?.classList.add('active', 'text-white');
            localStorage.setItem('active_lab_subtab', tab);
            if (tab === 'materials' && typeof loadSubjectMaterials === 'function') {
                loadSubjectMaterials();
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

            formData.forEach((val, key) => {
                const matches = key.match(/configs\[(.*?)\]\[(.*?)\]/);
                if (matches) {
                    const co = matches[1];
                    const act = matches[2];
                    if (!configs[co]) configs[co] = { assignment: true, mcq: true };
                    configs[co][act] = true;
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
                    Swal.fire('Configured!', data.message, 'success');
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

            let html = '';
            const cos = ['CO1', 'CO2', 'CO3', 'CO4'];

            cos.forEach(co => {
                const activeActs = slConfigs[co] || { assignment: true, mcq: true };
                const actKeys = Object.keys(activeActs).filter(k => activeActs[k]);

                html += `
                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-amber-400 text-sm flex items-center space-x-2">
                                <span>${co} Assessment Activities</span>
                                <span class="text-xs text-slate-400 font-normal">(${actKeys.length} Active)</span>
                            </h4>
                            <span id="co-sum-${co}" class="text-xs font-bold text-slate-300 bg-slate-800 px-2 py-0.5 rounded border border-slate-700">
                                Avg: 0.0 / 15.0
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                `;

                actKeys.forEach(actKey => {
                    const label = activityLabels[actKey] || actKey.toUpperCase();
                    const currentVal = slSplitupState[regNo][co] ? (slSplitupState[regNo][co][actKey] || 0) : 0;

                    html += `
                        <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-200 text-xs uppercase">${label}</span>
                                <span id="badge-${co}-${actKey}" class="px-2.5 py-0.5 rounded bg-amber-500/15 text-amber-300 font-mono text-xs font-bold border border-amber-500/20">
                                    ${parseFloat(currentVal).toFixed(1)} / 15.0
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', -0.5)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">-</button>
                                <input type="range" id="slider-${co}-${actKey}" min="0" max="15" step="0.5" value="${currentVal}" oninput="syncSlSlider('${regNo}', '${co}', '${actKey}', this.value)" class="flex-1 accent-emerald-400 h-2 bg-slate-800 rounded-lg cursor-pointer">
                                <button type="button" onclick="stepSlSlider('${regNo}', '${co}', '${actKey}', 0.5)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">+</button>
                            </div>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            calculateSlLiveTotal(regNo);
        }

        function syncSlSlider(regNo, co, actKey, val) {
            const num = parseFloat(val) || 0;
            if (!slSplitupState[regNo]) slSplitupState[regNo] = {};
            if (!slSplitupState[regNo][co]) slSplitupState[regNo][co] = {};
            slSplitupState[regNo][co][actKey] = num;

            const badge = document.getElementById(`badge-${co}-${actKey}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / 15.0`;

            calculateSlLiveTotal(regNo);
        }

        function stepSlSlider(regNo, co, actKey, delta) {
            const slider = document.getElementById(`slider-${co}-${actKey}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(15, current + delta));
            slider.value = next;
            syncSlSlider(regNo, co, actKey, next);
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

        function addCustomLessonPlanRow(tbodyId, defaultMode) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            const newId = 'new_' + Date.now();
            const rowCount = tbody.querySelectorAll('tr').length + 1;
            const label = defaultMode === 'L' ? `Hr ${rowCount}` : `Session ${rowCount}`;

            const tr = document.createElement('tr');
            tr.id = `lp-row-${newId}`;
            tr.setAttribute('data-plan-id', newId);
            tr.className = 'hover:bg-slate-800/30 transition-all bg-indigo-950/20';

            tr.innerHTML = `
                <td class="p-2.5 font-normal text-center text-white">${label}</td>
                <td class="p-2.5">
                    <select id="lp-pedagogy-${newId}" onchange="onPedagogyChange('${newId}', this.value)" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs w-full text-blue-400">
                        <option value="Lecture (L)" ${defaultMode === 'L' ? 'selected' : ''}>Lecture (L)</option>
                        <option value="Practical Lab (P)" ${defaultMode === 'P' ? 'selected' : ''}>Practical Lab (P)</option>
                        <option value="Theory Series Exam (ST)">Theory Series Exam (ST)</option>
                        <option value="Practical Series Exam (SP)">Practical Series Exam (SP)</option>
                        <option value="PPT Presentation">PPT Presentation</option>
                        <option value="Demonstration">Demonstration</option>
                        <option value="Group Activity">Group Activity</option>
                    </select>
                </td>
                <td class="p-2.5">
                    <input type="date" id="lp-prop-${newId}" value="" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                </td>
                <td class="p-2.5">
                    <input type="date" id="lp-act-${newId}" value="" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-200 text-xs w-full">
                </td>
                <td class="p-2.5">
                    <textarea id="lp-topic-${newId}" rows="2" placeholder="Enter custom lesson topic description..." class="bg-slate-900 border border-slate-700 rounded p-2 text-slate-100 text-sm font-normal w-full focus:border-blue-500 outline-none resize-y leading-snug"></textarea>
                </td>
                <td class="p-2.5 text-center">
                    <select id="lp-co-${newId}" class="bg-slate-900 border border-amber-500/40 rounded px-2 py-1 font-mono text-xs font-bold text-amber-300 w-full focus:border-amber-400 outline-none cursor-pointer" style="background-color:#0f172a !important; color:#fcd34d !important;">
                        <option value="CO1" selected style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO1</option>
                        <option value="CO2" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO2</option>
                        <option value="CO3" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO3</option>
                        <option value="CO4" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO4</option>
                        <option value="CO5" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO5</option>
                        <option value="CO6" style="background-color:#0f172a; color:#fcd34d; font-weight:bold;">CO6</option>
                    </select>
                </td>
                <td id="lp-batch-td-${newId}" class="p-2.5">
                    <select id="lp-batch-${newId}" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 font-normal text-xs text-slate-300 w-full">
                        <option value="All Students" selected>All Students</option>
                        <option value="Batch A & B">Batch A & B (Combined)</option>
                        <option value="Batch A">Batch A</option>
                        <option value="Batch B">Batch B</option>
                    </select>
                </td>
                <td id="lp-hours-td-${newId}" class="p-2.5 text-center font-normal">
                    <span class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30 text-xs font-normal">1 Hour</span>
                </td>
                <td class="p-2.5">
                    <div class="flex items-center space-x-1">
                        <input type="text" id="lp-remarks-${newId}" value="" placeholder="Status/Remarks" class="bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-400 text-xs w-full">
                        <button type="button" onclick="document.getElementById('lp-row-${newId}').remove()" class="text-rose-400 hover:text-rose-300 text-xs font-bold px-1.5 py-1" title="Remove Row">&times;</button>
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
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
                const topic = document.getElementById('lp-topic-' + planId)?.value || '';
                const coId = document.getElementById('lp-co-' + planId)?.value || 'CO1';
                const batch = document.getElementById('lp-batch-' + planId)?.value || '';
                const remarks = document.getElementById('lp-remarks-' + planId)?.value || '';

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

        function onPedagogyChange(planId, val) {
            const batchTd = document.getElementById('lp-batch-td-' + planId);
            const hoursTd = document.getElementById('lp-hours-td-' + planId);
            const select = document.getElementById('lp-pedagogy-' + planId);
            if (!batchTd) return;

            const isLab = val.includes('Practical') || val.includes('Lab') || val.includes('(P)') || val.includes('(SP)');

            if (select) {
                select.className = "bg-slate-900 border border-slate-700 rounded px-2 py-1 font-bold text-xs w-full " + 
                    (isLab ? "text-emerald-400" : (val.includes('Series') ? "text-purple-400" : "text-blue-400"));
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
                    ? "px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all"
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
                    <span class="font-bold text-indigo-300 text-sm">${partLabel}</span>
                    <button onclick="addQpRow('${partKey}','${defaultMark}')" class="text-xs px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all">+ Add Question</button>
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
                    <td class="p-2"><input type="text" value="${q.choice_group||''}" placeholder="e.g. Set A" onchange="updateQpField('${partKey}',${idx},'choice_group',this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-1.5 py-1 text-xs text-purple-300"></td>
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
    // ESE Theory Grade Modal System
    // =====================================================================
    const eseGradesState = {};
    studentsList.forEach(s => {
        eseGradesState[s.reg_no] = {
            ese_theory_grade: s.ese_theory_grade === '-' ? '' : s.ese_theory_grade,
            theory_absent: (s.ese_theory_grade === 'FE')
        };
    });

    function openEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.remove('hidden');
        const sel = document.getElementById('ese-student-select');
        if (sel && sel.value) {
            loadEseStudent(sel.value);
        }
    }

    function closeEseTheoryModal() {
        document.getElementById('ese-theory-modal').classList.add('hidden');
    }

    function loadEseStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;

        document.getElementById('ese-student-reg').innerText = student.reg_no;
        document.getElementById('ese-student-name').innerText = student.name;

        const state = eseGradesState[regNo] || { ese_theory_grade: '', theory_absent: false };
        const gradeSelect = document.getElementById('ese-grade-select');
        const absentCheck = document.getElementById('ese-absent-check');

        gradeSelect.value = state.ese_theory_grade;
        absentCheck.checked = state.theory_absent;
        gradeSelect.disabled = state.theory_absent;

        updateEseLiveDisplay(state.ese_theory_grade);
    }

    function onEseGradeChange(grade) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].ese_theory_grade = grade;
        eseGradesState[regNo].theory_absent = (grade === 'FE');

        document.getElementById('ese-absent-check').checked = (grade === 'FE');
        updateEseLiveDisplay(grade);
    }

    function toggleEseAbsent(isAbsent) {
        const sel = document.getElementById('ese-student-select');
        const regNo = sel.value;
        if (!regNo) return;

        if (!eseGradesState[regNo]) eseGradesState[regNo] = {};
        eseGradesState[regNo].theory_absent = isAbsent;

        const gradeSelect = document.getElementById('ese-grade-select');
        if (isAbsent) {
            eseGradesState[regNo].ese_theory_grade = 'FE';
            gradeSelect.value = 'FE';
            gradeSelect.disabled = true;
            updateEseLiveDisplay('FE');
        } else {
            eseGradesState[regNo].ese_theory_grade = '';
            gradeSelect.value = '';
            gradeSelect.disabled = false;
            updateEseLiveDisplay('');
        }
    }

    function updateEseLiveDisplay(grade) {
        const score = convertGradeToScore(grade);
        document.getElementById('ese-mapped-score').innerText = `${score.toFixed(2)} / 60.00`;

        const isPass = (score >= 24.0 || ['S','A','B','C','D','P'].includes(String(grade).toUpperCase().trim()));
        const statusEl = document.getElementById('ese-pass-status');
        if (isPass) {
            statusEl.innerText = 'PASSED';
            statusEl.className = 'font-bold text-emerald-400';
        } else {
            statusEl.innerText = grade ? 'REAPPEAR' : '-';
            statusEl.className = 'font-bold text-rose-400';
        }
    }

    function convertGradeToScore(grade) {
        switch (String(grade).toUpperCase().trim()) {
            case 'S': return 57.0;
            case 'A': return 51.0;
            case 'B': return 45.0;
            case 'C': return 39.0;
            case 'D': return 33.0;
            case 'P': return 27.0;
            default: return 0.0;
        }
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
        nextEseStudent();
    }

    function saveAllEseGrades() {
        const marksData = [];
        Object.keys(eseGradesState).forEach(regNo => {
            const student = studentsList.find(s => s.reg_no === regNo);
            marksData.push({
                reg_no: regNo,
                ese_theory_grade: eseGradesState[regNo].ese_theory_grade,
                theory_absent: eseGradesState[regNo].theory_absent,
                practical_absent: false,
                ese_practical_marks: parseFloat(student ? (student.ese_practical || 0) : 0)
            });
        });

        Swal.fire({
            title: 'Saving ESE Grades...',
            text: 'Updating board theory grades for all students',
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

    document.addEventListener('DOMContentLoaded', () => {
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
                <button type="button" onclick="switchQpEditorTab('qp')" id="qp-edit-btn-qp" class="px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all">📝 1. Edit Questions (QP)</button>
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
                    <button id="qp-save-btn" onclick="saveQpFromModal()" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg transition-all">
                        💾 Save &amp; Add to Question Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory ESE Grades Modal
    ================================================================= -->
    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-2xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">End Semester Exam (ESE) Theory Grade Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select ESE Grade for each student. Mapped score and status will update automatically.</p>
                </div>
                <button onclick="closeEseTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Grading Content Card -->
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Reg No:</span>
                    <span id="ese-student-reg" class="font-mono text-xs font-bold text-slate-200"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Student Name:</span>
                    <span id="ese-student-name" class="text-xs font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-800/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Theory ESE Grade:</label>
                        <select id="ese-grade-select" onchange="onEseGradeChange(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 font-bold text-sm text-amber-400 outline-none">
                            <option value="">- Select Grade -</option>
                            <option value="S">S (90% - 100%)</option>
                            <option value="A">A (80% - 89%)</option>
                            <option value="B">B (70% - 79%)</option>
                            <option value="C">C (60% - 69%)</option>
                            <option value="D">D (50% - 59%)</option>
                            <option value="P">P (40% - 49% - Pass)</option>
                            <option value="F">F (Fail)</option>
                            <option value="FE">FE (Absent / Shortage)</option>
                            <option value="I">I (Incomplete)</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="ese-absent-check" onchange="toggleEseAbsent(this.checked)" class="rounded bg-slate-800 border-slate-700 text-rose-500 focus:ring-0">
                        <label for="ese-absent-check" class="text-xs text-slate-300 font-semibold cursor-pointer">Mark Student as Absent (Grade FE)</label>
                    </div>
                </div>

                <!-- Live Conversion Display -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Equivalent Marks:</span>
                        <span id="ese-mapped-score" class="font-bold text-indigo-400">0.00 / 60.00</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Theory Pass Status:</span>
                        <span id="ese-pass-status" class="font-bold text-rose-400">REAPPEAR</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeEseTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseGrades()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save ESE Grades</button>
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
                        <option value="Series 1">Test 1 (CO1)</option>
                        <option value="Series 2">Test 2 (CO2)</option>
                        <option value="Series 3">Test 3 (CO3)</option>
                        <option value="Series 4">Test 4 (CO4)</option>
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
                    <button type="button" onclick="saveAndNextSeriesTheoryStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesTheoryMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Continuous Lab Experiment Evaluation Modal
    ================================================================= -->
    <div id="experiment-eval-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-2xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Continuous Lab Work Evaluator (Table 2.2)</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Grade the student on 6 criteria. Total is out of 50, automatically scaled to 10 CIA marks.</p>
                </div>
                <button onclick="closeExperimentEvalModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>
 
            <!-- Selectors and Steppers -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 space-y-3 flex-shrink-0">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                    <label class="text-slate-300 text-xs font-semibold whitespace-nowrap">Select Experiment:</label>
                    <select id="eval-exp-select" onchange="onEvalExpChange(this.value)" class="bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 font-normal text-xs text-emerald-400 outline-none w-full sm:max-w-xs focus:border-emerald-500">
                        @foreach(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                        <option value="{{ $exp['experiment_no'] }}">{{ $exp['experiment_no'] }} - {{ $exp['title'] }}</option>
                        @endforeach
                    </select>
                </div>
 
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevExpStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>◀ Prev</span>
                    </button>
 
                    <div class="flex-1">
                        <select id="eval-student-select" onchange="loadExpStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <button type="button" onclick="nextExpStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>
 
            <!-- Rubrics Form Card -->
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-4 flex-1 overflow-y-auto" id="exp-rubrics-container">
                <!-- Javascript will populate sliders here -->
            </div>
 
            <!-- Live Converted Result Display -->
            <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-semibold block">Total Evaluation Score:</span>
                    <span id="exp-live-total" class="font-bold text-emerald-400 text-sm">0.00 / 50.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 font-semibold block">CIA Marks:</span>
                    <span id="exp-live-cia" class="font-bold text-amber-400 text-sm">0.00 / 10.00 M</span>
                </div>
            </div>
 
            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeExperimentEvalModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextExpStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllExpMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <!-- ================================================================
         Practical Series Exam Evaluation Modal
    ================================================================= -->
    <div id="series-practical-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center hidden p-3 sm:p-5">
        <div class="glass-card max-w-2xl w-full p-5 rounded-2xl border border-slate-700 shadow-2xl space-y-4 max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Practical Series Test Marks Evaluator (Table 3.1)</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Grade the student on 5 practical criteria. Total is out of 40, automatically scaled to 10 CIA marks.</p>
                </div>
                <button onclick="closeSeriesPracticalModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>
 
            <!-- Series and Student Selection -->
            <div class="bg-slate-900/90 p-3 rounded-xl border border-slate-800 space-y-3 flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-slate-300 text-xs font-semibold">Select Series Test:</label>
                    <select id="series-pr-test-select" onchange="onSeriesPrTestChange(this.value)" class="bg-slate-950 border border-slate-700 rounded px-2.5 py-1.5 text-xs text-amber-400 font-bold outline-none focus:border-amber-500">
                        <option value="Series 1">Practical Test 1 (CO1+CO2)</option>
                        <option value="Series 2">Practical Test 2 (CO3+CO4)</option>
                    </select>
                </div>
 
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesPrStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>◀ Prev</span>
                    </button>
 
                    <div class="flex-1">
                        <select id="series-pr-student-select" onchange="loadSeriesPrStudent(this.value)" class="w-full bg-slate-950 border border-slate-700 rounded px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <button type="button" onclick="nextSeriesPrStudent()" class="header-btn px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>
 
            <!-- Rubrics Form Card -->
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-4 flex-1 overflow-y-auto" id="series-pr-rubrics-container">
                <!-- Javascript will populate sliders here -->
            </div>
 
            <!-- Live Converted Result Display -->
            <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-400 font-semibold block">Total Exam Score:</span>
                    <span id="series-pr-live-total" class="font-bold text-emerald-400 text-sm">0.00 / 40.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-400 font-semibold block">CIA Marks:</span>
                    <span id="series-pr-live-cia" class="font-bold text-blue-400 text-sm">0.00 / 10.00 M</span>
                </div>
            </div>
 
            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-800 flex-shrink-0">
                <button type="button" onclick="closeSeriesPracticalModal()" class="header-btn px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs hover:bg-slate-700">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSeriesPrStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesPrMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
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
 
    // Initialize state
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        experimentEvalsState[regNo] = {};
        
        // Populate from DB if exists
        const dbList = experimentEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            experimentEvalsState[regNo][rec.experiment_no] = {
                prep_punctuality: parseFloat(rec.prep_punctuality) || 0,
                setup_procedure: parseFloat(rec.setup_procedure) || 0,
                observation_recording: parseFloat(rec.observation_recording) || 0,
                analysis_interpretation: parseFloat(rec.analysis_interpretation) || 0,
                viva_voce: parseFloat(rec.viva_voce) || 0,
                workmanship_discipline: parseFloat(rec.workmanship_discipline) || 0,
                total_score_50: parseFloat(rec.total_score_50) || 0
            };
        });
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
 
    function onEvalExpChange(expNo) {
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }
 
    function loadExpStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;
 
        // Ensure state exists for this student/experiment
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
            <div class="mb-2 text-xs text-slate-400 font-semibold uppercase">Grading criteria for: ${student.name}</div>
        `;
 
        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800 space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-slate-350">${c.label} (Max ${c.max})</span>
                        <span class="text-emerald-400 font-mono font-bold text-sm bg-slate-900 px-2 py-0.5 rounded" id="exp-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustExpVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-750 font-black text-white flex items-center justify-center transition-all">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="exp-slider-${c.key}" oninput="syncExpSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-emerald-500 bg-slate-900 border border-slate-750 rounded-lg h-2 outline-none">
                        <button type="button" onclick="adjustExpVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-black text-white flex items-center justify-center transition-all">+</button>
                    </div>
                </div>
            `;
        });
 
        container.innerHTML = html;
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function syncExpSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('eval-student-select').value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;
 
        experimentEvalsState[regNo][expNo][key] = num;
 
        const badge = document.getElementById(`exp-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function adjustExpVal(key, delta, max) {
        const slider = document.getElementById(`exp-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncExpSlider(key, next, max);
    }
 
    function updateExpLiveDisplay(regNo, expNo) {
        const state = experimentEvalsState[regNo][expNo];
        if (!state) return;
 
        const total = (state.prep_punctuality || 0) +
                      (state.setup_procedure || 0) +
                      (state.observation_recording || 0) +
                      (state.analysis_interpretation || 0) +
                      (state.viva_voce || 0) +
                      (state.workmanship_discipline || 0);
 
        state.total_score_50 = total;
 
        const cia = Math.round(((total / 50.0) * 10.0) * 2) / 2;
 
        document.getElementById('exp-live-total').innerText = `${total.toFixed(1)} / 50.0 M`;
        document.getElementById('exp-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
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
            if (sNo === 'Test 1 (CO1+CO2)') mapped = 'Series 1';
            if (sNo === 'Test 2 (CO3+CO4)') mapped = 'Series 2';
 
            if (seriesPracticalEvalsState[regNo][mapped]) {
                seriesPracticalEvalsState[regNo][mapped] = {
                    writeup_procedure: parseFloat(rec.writeup_procedure) || 0,
                    setup_execution: parseFloat(rec.setup_execution) || 0,
                    observation_result: parseFloat(rec.observation_result) || 0,
                    viva_voce: parseFloat(rec.viva_voce) || 0,
                    record_completion: parseFloat(rec.record_completion) || 0,
                    total_score_40: parseFloat(rec.total_score_40) || 0,
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
 
    function onSeriesPrTestChange(test) {
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }
 
    function loadSeriesPrStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;
 
        const state = seriesPracticalEvalsState[regNo][test];
        const container = document.getElementById('series-pr-rubrics-container');
 
        const criteria = [
            { label: '1. Write-up / Procedure (Aim, Circuit/Flowchart, Stepwise procedure)', key: 'writeup_procedure', max: 10, step: 0.5 },
            { label: '2. Experiment Setup & Execution (Connections, Handling, Accuracy)', key: 'setup_execution', max: 10, step: 0.5 },
            { label: '3. Observation & Result / Output (Tabulation, Calculations, Outcome)', key: 'observation_result', max: 8, step: 0.5 },
            { label: '4. Viva Voce (Conceptual understanding, Theory knowledge)', key: 'viva_voce', max: 8, step: 0.5 },
            { label: '5. Record (Completion & neatness, Faculty certification)', key: 'record_completion', max: 4, step: 0.5 }
        ];
 
        let html = `
            <div class="mb-2 text-xs text-slate-400 font-semibold uppercase">Grading criteria for: ${student.name}</div>
        `;
 
        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800 space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-slate-350">${c.label} (Max ${c.max})</span>
                        <span class="text-indigo-400 font-mono font-bold text-sm bg-slate-900 px-2 py-0.5 rounded" id="series-pr-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-750 font-black text-white flex items-center justify-center transition-all">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="series-pr-slider-${c.key}" oninput="syncSeriesPrSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-indigo-500 bg-slate-900 border border-slate-750 rounded-lg h-2 outline-none">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-black text-white flex items-center justify-center transition-all">+</button>
                    </div>
                </div>
            `;
        });
 
        container.innerHTML = html;
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function syncSeriesPrSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;
 
        seriesPracticalEvalsState[regNo][test][key] = num;
 
        const badge = document.getElementById(`series-pr-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function adjustSeriesPrVal(key, delta, max) {
        const slider = document.getElementById(`series-pr-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncSeriesPrSlider(key, next, max);
    }
 
    function updateSeriesPrLiveDisplay(regNo, test) {
        const state = seriesPracticalEvalsState[regNo][test];
        if (!state) return;
 
        const total = (state.writeup_procedure || 0) +
                      (state.setup_execution || 0) +
                      (state.observation_result || 0) +
                      (state.viva_voce || 0) +
                      (state.record_completion || 0);
 
        state.total_score_40 = total;
 
        const cia = Math.round(((total / 40.0) * 10.0) * 2) / 2;
 
        document.getElementById('series-pr-live-total').innerText = `${total.toFixed(1)} / 40.0 M`;
        document.getElementById('series-pr-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
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
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }
        const eseSplitupState = {};
        
        studentsList.forEach(st => {
            const currentTotal = parseFloat(st.ese_practical || 0);
            if (currentTotal > 0) {
                const factor = currentTotal / 40.0;
                eseSplitupState[st.reg_no] = {
                    writeup: Math.round(10 * factor * 2) / 2,
                    setup: Math.round(10 * factor * 2) / 2,
                    result: Math.round(8 * factor * 2) / 2,
                    viva: Math.round(8 * factor * 2) / 2,
                    record: Math.round(4 * factor * 2) / 2
                };
            } else {
                eseSplitupState[st.reg_no] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }
        });

        function openEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.remove('hidden');
            const sel = document.getElementById('ese-student-select');
            if (sel && sel.value) {
                loadEseStudent(sel.value);
            }
        }

        function closeEsePracticalModal() {
            document.getElementById('ese-practical-modal').classList.add('hidden');
        }

        const eseRubrics = [
            { key: 'writeup', label: 'Procedure & Writeup', max: 10 },
            { key: 'setup', label: 'Setup & Circuit Execution', max: 10 },
            { key: 'result', label: 'Observation & Result', max: 8 },
            { key: 'viva', label: 'Viva-Voce Examination', max: 8 },
            { key: 'record', label: 'Record & Logbook', max: 4 }
        ];

        function loadEseStudent(regNo) {
            const container = document.getElementById('ese-sliders-container');
            if (!container) return;

            if (!eseSplitupState[regNo]) {
                eseSplitupState[regNo] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }

            let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';

            eseRubrics.forEach(rub => {
                const currentVal = eseSplitupState[regNo][rub.key] || 0;
                html += `
                    <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-200 text-xs">${rub.label}</span>
                            <span id="ese-badge-${rub.key}" class="px-2.5 py-0.5 rounded bg-emerald-500/15 text-emerald-300 font-mono text-xs font-bold border border-emerald-500/20">
                                ${parseFloat(currentVal).toFixed(1)} / ${rub.max}.0
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', -0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">-</button>
                            <input type="range" id="ese-slider-${rub.key}" min="0" max="${rub.max}" step="0.5" value="${currentVal}" oninput="syncEseSlider('${regNo}', '${rub.key}', this.value, ${rub.max})" class="flex-1 accent-emerald-400 h-2 bg-slate-800 rounded-lg cursor-pointer">
                            <button type="button" onclick="stepEseSlider('${regNo}', '${rub.key}', 0.5, ${rub.max})" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 font-extrabold text-white text-base shadow flex items-center justify-center">+</button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
            calculateEseLiveTotal(regNo);
        }

        function syncEseSlider(regNo, key, val, maxVal) {
            const num = Math.min(maxVal, Math.max(0, parseFloat(val) || 0));
            if (!eseSplitupState[regNo]) eseSplitupState[regNo] = {};
            eseSplitupState[regNo][key] = num;

            const badge = document.getElementById(`ese-badge-${key}`);
            if (badge) badge.innerText = `${num.toFixed(1)} / ${maxVal}.0`;

            calculateEseLiveTotal(regNo);
        }

        function stepEseSlider(regNo, key, delta, maxVal) {
            const slider = document.getElementById(`ese-slider-${key}`);
            if (!slider) return;

            let current = parseFloat(slider.value) || 0;
            let next = Math.max(0, Math.min(maxVal, current + delta));
            slider.value = next;
            syncEseSlider(regNo, key, next, maxVal);
        }

        function calculateEseLiveTotal(regNo) {
            const data = eseSplitupState[regNo] || {};
            const total = (data.writeup || 0) + (data.setup || 0) + (data.result || 0) + (data.viva || 0) + (data.record || 0);

            const rawElem = document.getElementById('ese-student-total-raw');
            const gradeBadge = document.getElementById('ese-student-grade-badge');

            if (rawElem) rawElem.innerText = `${Math.round(total)} / 40 Marks`;

            const pct = (total / 40) * 100;
            let grade = 'F';
            let gClass = 'text-rose-400 bg-rose-500/20 border-rose-500/30';
            if (pct >= 90) { grade = 'S'; gClass = 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30'; }
            else if (pct >= 80) { grade = 'A'; gClass = 'text-blue-400 bg-blue-500/20 border-blue-500/30'; }
            else if (pct >= 70) { grade = 'B'; gClass = 'text-indigo-400 bg-indigo-500/20 border-indigo-500/30'; }
            else if (pct >= 60) { grade = 'C'; gClass = 'text-purple-400 bg-purple-500/20 border-purple-500/30'; }
            else if (pct >= 50) { grade = 'D'; gClass = 'text-amber-400 bg-amber-500/20 border-amber-500/30'; }
            else if (pct >= 40) { grade = 'E'; gClass = 'text-orange-400 bg-orange-500/20 border-orange-500/30'; }

            if (gradeBadge) {
                gradeBadge.innerText = grade;
                gradeBadge.className = `font-black text-base px-3 py-0.5 rounded-full border ${gClass}`;
            }

            const row = document.getElementById(`ese-row-${regNo}`);
            if (row) {
                const w = row.querySelector('.ese-val-writeup'); if (w) w.innerText = (data.writeup || 0).toFixed(1);
                const s = row.querySelector('.ese-val-setup'); if (s) s.innerText = (data.setup || 0).toFixed(1);
                const r = row.querySelector('.ese-val-result'); if (r) r.innerText = (data.result || 0).toFixed(1);
                const v = row.querySelector('.ese-val-viva'); if (v) v.innerText = (data.viva || 0).toFixed(1);
                const rec = row.querySelector('.ese-val-record'); if (rec) rec.innerText = (data.record || 0).toFixed(1);
                const tot = row.querySelector('.ese-val-total'); if (tot) tot.innerText = Math.round(total);
                const gr = row.querySelector('.ese-val-grade');
                if (gr) gr.innerHTML = `<span class="px-2.5 py-0.5 rounded-full border text-xs font-bold ${gClass}">${grade}</span>`;
            }
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
            if (sel.selectedIndex < sel.options.length - 1) {
                sel.selectedIndex++;
                loadEseStudent(sel.value);
            } else {
                Swal.fire('End of List', 'Reached last student in list.', 'info');
            }
        }

        function saveAllEseMarks() {
            const marksData = studentsList.map(st => {
                const splitup = eseSplitupState[st.reg_no] || { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
                const totalScore = (splitup.writeup || 0) + (splitup.setup || 0) + (splitup.result || 0) + (splitup.viva || 0) + (splitup.record || 0);
                return {
                    reg_no: st.reg_no,
                    ese_practical_marks: totalScore
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
            
            // Remove non-printable elements, buttons, inputs, dropdowns, QP generator cards
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

            const printWin = window.open('', '_blank', 'width=1150,height=850');
            printWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${reportTitle} - ${subjectName}</title>
                    <style>
                        @page {
                            size: A4 landscape;
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
                        /* FORCE CLEAN BLACK AND WHITE FOR PRINT CONTENT */
                        .print-content * {
                            box-shadow: none !important;
                            text-shadow: none !important;
                        }
                        .print-content div {
                            border-radius: 0 !important;
                            background: transparent !important;
                            border: none !important;
                        }
                        .print-content p, .print-content span, .print-content h3, .print-content h4 {
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
            <span class="material-symbols-rounded text-indigo-400">rate_review</span>
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
              <input type="text" id="p-ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
              <input type="text" id="p-ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q3. CO2 - Concept Clarity & Application</label>
              <input type="text" id="p-ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q4. CO2 - Effectiveness of Teaching & Lab Demonstrations</label>
              <input type="text" id="p-ms-q8" value="The use of teaching tools, animations, lab demonstrations, or ICT tools is effective." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q5. CO3 - Doubt Clearing & Classroom Interaction</label>
              <input type="text" id="p-ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q6. CO3 - Test & Practical Assignment Relevance</label>
              <input type="text" id="p-ms-q10" value="Internal assessment test questions and practical assignments match the topics taught in class." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q7. CO4 - Fairness in Evaluation</label>
              <input type="text" id="p-ms-q11" value="Evaluation of mid-semester tests or practical submissions is fair, timely, and transparent." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q8. CO4 - Guidance & Support for Students</label>
              <input type="text" id="p-ms-q12" value="The teacher provides extra guidance, remedial tips, or support to students needing assistance." class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
            <button type="button" onclick="closeMidsemInitModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-xs shadow-md">Activate & Publish Survey</button>
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
              <input type="text" id="p-ex-q1" value="How well did the course help you understand and remember core academic principles, models, and structural fundamentals?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q2. CO1 - Outcome & Syllabus Alignment</label>
              <input type="text" id="p-ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with class lectures and lab demonstrations?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q3. CO2 - Analytical Ability & Logic</label>
              <input type="text" id="p-ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q4. CO2 - Design & Troubleshooting Skills</label>
              <input type="text" id="p-ex-q4" value="To what extent can you design models, troubleshoot bugs, or conduct lab experiments based on class lessons?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q5. CO3 - Modern Tools & Practical Execution</label>
              <input type="text" id="p-ex-q5" value="How confident are you in using modern software, lab apparatus, or engineering software for tasks?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q6. CO3 - Problem Solving in Field & Lab</label>
              <input type="text" id="p-ex-q6" value="How effectively can you apply core theoretical principles to solve practical or field problems?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q7. CO4 - Ethics, Teamwork & Professional Conduct</label>
              <input type="text" id="p-ex-q7" value="Did the course foster professional ethics, group collaboration, and responsible work habits?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Q8. CO4 - Communication & Report Writing</label>
              <input type="text" id="p-ex-q8" value="How well did the course improve your technical documentation, presentation skills, and report writing?" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-slate-200 text-xs focus:border-indigo-500 outline-none">
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
