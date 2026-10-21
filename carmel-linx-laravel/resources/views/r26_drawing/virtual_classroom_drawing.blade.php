<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carmel Linx - [R-2026] Virtual Drawing Hall - {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</title>

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
            --bg-primary: #0f172a;       /* slate-900 */
            --bg-secondary: #020617;     /* slate-950 */
            --bg-card: #0f172a;          /* slate-900 */
            --bg-card-hover: #1e293b;    /* slate-800 */
            --border-color: #1e293b;     /* slate-800 */
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

        /* Arched Tab Navigation Bar */
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
            color: #60a5fa !important;
            border-color: #60a5fa !important;
            border-bottom-color: transparent !important;
            background-color: rgba(59, 130, 246, 0.05) !important;
            box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.2) !important;
            font-weight: 700 !important;
        }

        /* Form Inputs & Select Controls (Strictly No Spinners & Direct Keyboard Entry) */
        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            background-color: #020617 !important;
            border: 1px solid #1e293b !important;
            color: #f1f5f9 !important;
            border-radius: 8px !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="number"]:focus, select:focus, textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
            outline: none !important;
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
        }

        /* Compact Table Styling */
        table th {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.6rem !important;
        }

        table td {
            font-size: 0.8125rem !important;
            padding: 0.4rem 0.6rem !important;
        }

        /* Growable textarea */
        .growable-textarea {
            resize: none;
            overflow-y: hidden;
            min-height: 38px;
            line-height: 1.4;
            font-size: 0.8125rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            width: 100%;
        }

        /* Custom Badges */
        .badge-cyan { background-color: rgba(6, 182, 212, 0.15); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.4); font-size: 0.72rem; padding: 0.2em 0.5em; border-radius: 6px; }
        .badge-emerald { background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 0.72rem; padding: 0.2em 0.5em; border-radius: 6px; }
        .badge-amber { background-color: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 0.72rem; padding: 0.2em 0.5em; border-radius: 6px; }
        .badge-rose { background-color: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.4); font-size: 0.72rem; padding: 0.2em 0.5em; border-radius: 6px; }

        /* Auto-save status indicator */
        .save-indicator {
            transition: opacity 0.3s ease;
        }

        /* Active Attendance Override Styling */
        .cell-att-override.has-override {
            border-color: #f59e0b !important;
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fbbf24 !important;
            box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.4) !important;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">

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

    <!-- 1. TOP HEADER NAVIGATION BAR -->
    <header class="glass-panel sticky top-0 z-40 border-b border-slate-800">
        <div class="max-w-[98%] mx-auto px-4 md:px-8 py-2.5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-500 flex items-center justify-center shadow-md">
                    <span class="material-symbols-rounded text-white text-xl">palette</span>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-white flex items-center gap-2 mb-0">
                        <span>Virtual Drawing Hall</span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-cyan-500/15 text-cyan-300 border border-cyan-500/30">
                            Revision 2026
                        </span>
                    </h1>
                    <p class="text-[11px] text-slate-400 mb-0">
                        {{ $drawingCourseFile->course_code ?? $batchSubject->subject_code }} • {{ $drawingCourseFile->course_title ?? $batchSubject->subject_name }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Auto-Save Pulse Indicator -->
                <div id="global-save-pill" class="hidden items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span id="save-status-text">Saved</span>
                </div>

                <!-- Fullscreen Toggle -->
                <button onclick="toggleFullscreen()" class="p-1.5 rounded-lg bg-slate-800/90 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 transition-all cursor-pointer" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-sm">fullscreen</span>
                </button>

                <!-- Dashboard Return -->
                <a href="javascript:void(0)" onclick="closeTabOrGoDashboard()" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 font-bold transition-all flex items-center space-x-1.5 border border-rose-500/30 cursor-pointer no-underline text-[11px]" title="Return to Dashboard">
                    <span class="material-symbols-rounded text-xs">arrow_back</span>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. SUB-HEADER METADATA STRIP -->
    <div class="max-w-[98%] mx-auto px-4 md:px-8 mt-3">
        <div class="glass-card p-3.5 sm:p-4 rounded-xl border border-slate-800 flex items-center justify-between flex-wrap gap-3">
            <div class="space-y-1">
                <p class="text-slate-400 leading-tight mb-0 flex items-center flex-wrap gap-2 text-xs sm:text-sm">
                    <span>Batch: <span class="text-amber-300 font-bold font-mono px-2 py-0.5 rounded bg-amber-500/15 border border-amber-500/35">{{ $batchSubject->classroom_id }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Branch: <span class="text-blue-300 font-semibold">{{ $classroom->department ?? $classroom->branch }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Semester: <span class="text-white font-semibold">{{ $drawingCourseFile->semester ?? 'I' }}</span></span>
                </p>
                <div class="flex items-center space-x-3 flex-wrap gap-y-1 text-slate-300 text-xs">
                    <span>Teaching Scheme: <span class="font-bold text-blue-400">{{ $drawingCourseFile->teaching_scheme ?? '0:0:3:0' }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Contact Hours: <span class="font-bold text-emerald-400">{{ $drawingCourseFile->contact_hours ?? 45 }} Hrs</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Credits: <span class="font-bold text-sky-400">{{ $drawingCourseFile->credits ?? 1.5 }}</span></span>
                    <span class="text-slate-600 font-bold">•</span>
                    <span>Scheme Marks: <span class="font-bold text-amber-400">CIE 40M</span> <span class="text-slate-500">|</span> <span class="font-bold text-sky-400">ESE 60M</span> <span class="text-slate-500">|</span> <span class="font-bold text-emerald-400">Total 100M</span></span>
                </div>
            </div>

            <!-- Header Quick Action Buttons -->
            <div class="flex items-center space-x-2 flex-wrap gap-y-1.5">
                <button onclick="openSyllabusModal()" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white font-semibold transition-all flex items-center space-x-1.5 text-xs cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm">cloud_upload</span>
                    <span>Upload Syllabus</span>
                </button>
                @if(!empty($drawingCourseFile->syllabus_pdf_path))
                <a href="/storage/{{ str_replace('/storage/', '', $drawingCourseFile->syllabus_pdf_path) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-cyan-300 hover:text-cyan-200 font-semibold transition-all flex items-center space-x-1.5 text-xs no-underline shadow-sm">
                    <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                    <span>Syllabus PDF</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. MAIN ARCHED TABBED NAVIGATION (7 SPECIFIED TABS) -->
    <main class="max-w-[98%] mx-auto px-4 md:px-8 mt-4">
        
        <div class="tab-nav-container bg-slate-950/80 border border-slate-800/80 p-2 rounded-2xl shadow-lg mb-4">
            <nav class="flex flex-wrap md:flex-nowrap items-center gap-1.5 overflow-x-auto scrollbar-none no-scrollbar">
                
                <!-- Tab: Course Outline -->
                <button onclick="switchTab('outline')" id="tab-btn-outline" class="subtab-btn active px-3.5 py-2 text-[11px] md:text-xs font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)] flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">menu_book</span>
                    <span>Course Outline</span>
                </button>

                <!-- Tab: Lesson Plan -->
                <button onclick="switchTab('lessonplan')" id="tab-btn-lessonplan" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">calendar_month</span>
                    <span>Lesson Plan</span>
                </button>

                <!-- Tab: CIA Register -->
                <button onclick="switchTab('cia')" id="tab-btn-cia" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">fact_check</span>
                    <span>CIA Register</span>
                </button>

                <!-- Tab: Series Test -->
                <button onclick="switchTab('series')" id="tab-btn-series" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">edit_note</span>
                    <span>Series Test</span>
                </button>

                <!-- Tab: Attainment Calculator -->
                <button onclick="switchTab('attainment')" id="tab-btn-attainment" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">analytics</span>
                    <span>Attainment Calculator</span>
                </button>

                <!-- Tab: Reports -->
                <button onclick="switchTab('reports')" id="tab-btn-reports" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">print</span>
                    <span>Reports</span>
                </button>

                <!-- Tab: Study Material & Preclass Hub -->
                <button onclick="switchTab('materials')" id="tab-btn-materials" class="subtab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">folder_open</span>
                    <span>Study Material &amp; Preclass Hub</span>
                </button>

            </nav>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 1: COURSE OUTLINE                                                     -->
        <!-- ========================================================================= -->
        <div id="tab-content-outline" class="tab-pane-content space-y-5">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                
                <!-- Modules & Content (Col Span 2) -->
                <div class="lg:col-span-2 space-y-3">
                    <div class="glass-card p-4 border border-slate-800">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                            <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">format_list_bulleted</span>
                                <span>Course Modules &amp; Syllabus Topics</span>
                            </h3>
                            <span class="text-xs text-slate-400 font-mono">{{ $drawingCourseFile->contact_hours ?? 45 }} Hours Syllabus</span>
                        </div>

                        <div class="space-y-3">
                            @forelse(($drawingCourseFile->parsed_modules ?? []) as $mod)
                            <div class="p-3 rounded-lg bg-slate-950/70 border border-slate-800">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-xs text-blue-300">Module {{ $mod['module_id'] }}: {{ $mod['title'] }}</h4>
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        {{ $mod['hours'] ?? 9 }} Hours
                                    </span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed mb-0">{{ $mod['content'] }}</p>
                            </div>
                            @empty
                            <p class="text-xs text-slate-400 italic">No parsed module syllabus available yet. Upload the syllabus PDF above.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- CO-PO Articulation Matrix -->
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-amber-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">grid_on</span>
                            <span>CO - PO Articulation Matrix</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-center border-collapse">
                                <thead>
                                    <tr class="bg-slate-950/80 text-slate-400">
                                        <th class="border border-slate-800 p-1.5 text-xs text-left">Course Outcome</th>
                                        @for($p = 1; $p <= 11; $p++)
                                        <th class="border border-slate-800 p-1.5 text-xs font-mono">PO{{ $p }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                    <tr class="hover:bg-slate-800/40">
                                        <td class="border border-slate-800 p-1.5 text-xs font-bold text-left text-cyan-300">{{ $coTag }}</td>
                                        @for($p = 1; $p <= 11; $p++)
                                        @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                        <td class="border border-slate-800 p-1.5 text-xs {{ $val != '-' ? 'font-bold text-emerald-400' : 'text-slate-600' }}">{{ $val }}</td>
                                        @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Course Outcomes & Reference Material (Col Span 1) -->
                <div class="space-y-4">
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-cyan-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">target</span>
                            <span>Course Outcomes (COs)</span>
                        </h3>
                        <div class="space-y-2.5">
                            @foreach(($drawingCourseFile->parsed_cos ?? []) as $co)
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

                    <!-- Recommended Textbooks / References -->
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-emerald-400 mb-2.5 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">auto_stories</span>
                            <span>References &amp; Textbooks</span>
                        </h3>
                        <ul class="text-xs text-slate-300 space-y-1.5 pl-4 list-disc">
                            @foreach(($drawingCourseFile->parsed_textbooks ?? [
                                'Engineering Graphics with AUTOCAD - P I Varghese (VIP Publishers)',
                                'Engineering Graphics - K. C John (PHI Learning)',
                                'Engineering Drawing with CAD Applications - N. D. Bhatt & V. M. Panchal'
                            ]) as $tb)
                            <li>{{ $tb }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: LESSON PLAN                                                        -->
        <!-- ========================================================================= -->
        <div id="tab-content-lessonplan" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-4 border border-slate-800">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-amber-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">calendar_month</span>
                            <span>45-Hour Drawing Lesson Plan &amp; Execution Log</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">Module I–IV Practical drawing exercises, CAD practice, and Series Exam timelines.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button onclick="generateLessonTimeline()" class="px-2.5 py-1 rounded bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/40 text-blue-300 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">refresh</span>
                            <span>Generate</span>
                        </button>
                        <a href="/r26/classroom/drawing/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Plan</span>
                        </a>
                        <button onclick="saveLessonPlannerBulk()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
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
                                <th class="border border-slate-800">Topic / Practical Exercise Content</th>
                                <th class="border border-slate-800 text-center w-20">CO</th>
                                <th class="border border-slate-800 text-center w-16">Hrs</th>
                                <th class="border border-slate-800 w-44">Pedagogy / Activity</th>
                                <th class="border border-slate-800 text-center w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody id="lesson-plan-rows-container">
                            @forelse($lessonPlans as $lp)
                            <tr class="lesson-plan-row hover:bg-slate-800/30" data-id="{{ $lp->id }}">
                                <td class="border border-slate-800 text-center font-bold text-cyan-400 lp-day-no">#{{ $lp->day_no }}</td>
                                <td class="border border-slate-800 p-1.5">
                                    <input type="date" value="{{ $lp->proposed_date ?: $lp->planned_date }}" class="lp-proposed w-full px-2 py-1 text-xs">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <input type="date" value="{{ $lp->actual_date }}" class="lp-actual w-full px-2 py-1 text-xs">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <textarea class="growable-textarea lp-topic px-2 py-1 text-xs" rows="1" oninput="autoGrow(this)">{{ $lp->topic_content }}</textarea>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="lp-co w-full px-1.5 py-1 text-xs text-center font-bold text-amber-300">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                        <option value="{{ $coId }}" {{ ($lp->co_tag ?: $lp->co_id) == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <input type="number" min="1" max="6" value="{{ $lp->allocated_hours ?: 3 }}" class="lp-hours no-spinners w-full text-center px-1 py-1 text-xs font-bold">
                                </td>
                                <td class="border border-slate-800 p-1.5">
                                    <select class="lp-pedagogy w-full px-2 py-1 text-xs">
                                        <option value="Drawing Lab Practical (P)" {{ $lp->pedagogy == 'Drawing Lab Practical (P)' ? 'selected' : '' }}>Drawing Practical (P)</option>
                                        <option value="CAD Lab Practice (P)" {{ $lp->pedagogy == 'CAD Lab Practice (P)' ? 'selected' : '' }}>CAD Lab Practice (P)</option>
                                        <option value="Series Test 1 (CA1)" {{ $lp->pedagogy == 'Series Test 1 (CA1)' ? 'selected' : '' }}>Series Test 1 (CA1)</option>
                                        <option value="Series Test 2 (CA2)" {{ $lp->pedagogy == 'Series Test 2 (CA2)' ? 'selected' : '' }}>Series Test 2 (CA2)</option>
                                        <option value="Revision / Remedial" {{ $lp->pedagogy == 'Revision / Remedial' ? 'selected' : '' }}>Revision / Remedial</option>
                                    </select>
                                </td>
                                <td class="border border-slate-800 p-1.5 text-center">
                                    <select class="lp-status w-full px-2 py-1 text-xs font-semibold">
                                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-amber-400">Pending</option>
                                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-emerald-400">Completed</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-400 py-6 italic">No lesson plan loaded yet. Click Generate to auto-populate the 45-hour syllabus schedule.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-800">
                    <button onclick="addLessonPlanRow()" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-cyan-400 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-rounded text-sm">add</span>
                        <span>Add Row</span>
                    </button>
                    <button onclick="saveLessonPlannerBulk()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save Lesson Planner</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: CIA REGISTER (PRIMARY CONSOLIDATED MARK ENTRY)                     -->
        <!-- ========================================================================= -->
        <div id="tab-content-cia" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-4 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">fact_check</span>
                            <span>Continuous Internal Assessment (CIA) Register - Rev 2026</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            Scheme: Attendance (Max 5M) + Self-Learning CA1–CA4 (Avg/Scaled 15M) + Series Tests (Comb 20M) = <strong class="text-amber-400">CIE 40 Marks</strong> | ESE (60M)
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/print/cia-register" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CIA Register</span>
                        </a>
                        <button onclick="triggerConsolidatedManualSave()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save All Changes</span>
                        </button>
                    </div>
                </div>

                <!-- Rubric Hint Banner -->
                <div class="p-2.5 rounded-lg bg-slate-950/70 border border-slate-800 text-[11px] text-slate-300 flex items-center justify-between flex-wrap gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-blue-400 text-sm">info</span>
                        <span><strong>Attendance Slabs (5M):</strong> &ge;90%: 5 | 80-89%: 4 | 75-79%: 3 | 70-74%: 2 | 65-69%: 1 | &lt;65%: 0</span>
                    </div>
                    <span class="text-emerald-400 font-semibold">⚡ Auto-saves on typing (No spinners, direct input)</span>
                </div>

                <!-- Consolidated Marks Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left" id="consolidatedCiaTable">
                        <thead>
                            <tr class="bg-slate-950/80 text-slate-300 text-xs">
                                <th rowspan="2" class="border border-slate-800 text-center w-10">Roll</th>
                                <th rowspan="2" class="border border-slate-800 w-28">Reg No</th>
                                <th rowspan="2" class="border border-slate-800 min-w-[140px]">Student Name</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-blue-950/40 text-blue-300">Attendance (5M)</th>
                                <th colspan="5" class="border border-slate-800 text-center bg-amber-950/40 text-amber-300">Self-Learning CA (15M)</th>
                                <th colspan="7" class="border border-slate-800 text-center bg-sky-950/40 text-sky-300">Series Exams (20M)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-16 bg-emerald-950/50 text-emerald-300">Total<br>CIE (40)</th>
                                <th colspan="3" class="border border-slate-800 text-center bg-purple-950/40 text-purple-300">ESE (60M)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-16 bg-slate-950 text-amber-300">Grand<br>Total (100)</th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-[11px]">
                                <th class="border border-slate-800 text-center w-14" title="Raw % from Teams attendance log">Teams %</th>
                                <th class="border border-slate-800 text-center w-16" title="Override % to fix parsing errors (Amber border when active)">Override %</th>
                                <th class="border border-slate-800 text-center w-12 font-bold text-blue-300">Mark (5)</th>
                                <th class="border border-slate-800 text-center w-14" title="Module 1 Self-Learning out of 15">M1 (15)</th>
                                <th class="border border-slate-800 text-center w-14" title="Module 2 Self-Learning out of 15">M2 (15)</th>
                                <th class="border border-slate-800 text-center w-14" title="Module 3 Self-Learning out of 15">M3 (15)</th>
                                <th class="border border-slate-800 text-center w-14" title="Module 4 Self-Learning out of 15">M4 (15)</th>
                                <th class="border border-slate-800 text-center w-14 font-bold text-amber-300 bg-amber-950/20" title="Average / Converted to 15">SL (15)</th>
                                <th class="border border-slate-800 text-center w-12" title="Series 1 CO1 out of 20">CO1 (20)</th>
                                <th class="border border-slate-800 text-center w-12" title="Series 1 CO2 out of 20">CO2 (20)</th>
                                <th class="border border-slate-800 text-center w-14 font-semibold text-slate-300" title="Series 1 Total (CO1+CO2) out of 40">S1 (40)</th>
                                <th class="border border-slate-800 text-center w-12" title="Series 2 CO3 out of 20">CO3 (20)</th>
                                <th class="border border-slate-800 text-center w-12" title="Series 2 CO4 out of 20">CO4 (20)</th>
                                <th class="border border-slate-800 text-center w-14 font-semibold text-slate-300" title="Series 2 Total (CO3+CO4) out of 40">S2 (40)</th>
                                <th class="border border-slate-800 text-center w-14 font-bold text-sky-300 bg-sky-950/20" title="Combined into 20">Ser (20)</th>
                                <th class="border border-slate-800 text-center w-14" title="End Sem Exam Marks out of 60">Marks (60)</th>
                                <th class="border border-slate-800 text-center w-12" title="Board Grade (S, A, B, C, D, E, F)">Grade</th>
                                <th class="border border-slate-800 text-center w-10" title="Absent Toggle">AB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="student-eval-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <!-- Roll -->
                                <td class="border border-slate-800 text-center font-mono text-cyan-400 text-xs">{{ $st['roll_no'] ?: '-' }}</td>
                                
                                <!-- Reg No -->
                                <td class="border border-slate-800 font-mono text-xs">
                                    <span class="text-slate-300">{{ $st['reg_no'] }}</span>
                                </td>

                                <!-- Name -->
                                <td class="border border-slate-800 text-xs font-semibold text-white truncate max-w-[150px]" title="{{ $st['name'] }}">
                                    {{ $st['name'] }}
                                </td>

                                <!-- Attendance: Teams Raw % -->
                                <td class="border border-slate-800 text-center text-xs font-mono text-slate-400">
                                    {{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) . '%' : '-' }}
                                </td>

                                <!-- Attendance: Override % Input -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="100" 
                                           value="{{ $st['attendance_override_pct'] !== null ? $st['attendance_override_pct'] : '' }}" 
                                           data-prev-val="{{ $st['attendance_override_pct'] !== null ? $st['attendance_override_pct'] : '' }}"
                                           data-max="100"
                                           data-field-name="Attendance Override %"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="{{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) : '-' }}"
                                           class="cell-att-override no-spinners w-14 text-center px-1 py-1 text-xs font-mono {{ $st['attendance_override_pct'] !== null ? 'has-override' : '' }}" 
                                           title="{{ $st['attendance_override_pct'] !== null ? 'Active Override: ' . $st['attendance_override_pct'] . '%' : 'Override percentage to correct parsing errors' }}"
                                           onfocus="this.dataset.prevVal = this.value;"
                                           onchange="handleAttendanceOverrideChange(this, '{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}');">
                                </td>

                                <!-- Attendance: Mark out of 5 -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-blue-300 cell-att-mark">
                                    {{ number_format($st['att_marks'] ?? 0, 1) }}
                                </td>

                                <!-- Self Learning CA1 (Module 1, out of 15) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.5" min="0" max="15" 
                                           value="{{ $st['ca1_15'] !== null ? $st['ca1_15'] : '' }}" 
                                           data-prev-val="{{ $st['ca1_15'] !== null ? $st['ca1_15'] : '' }}"
                                           data-max="15"
                                           data-field-name="Self Learning CA1 (Module 1)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-ca1 no-spinners w-12 text-center px-1 py-1 text-xs" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Self Learning CA2 (Module 2, out of 15) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.5" min="0" max="15" 
                                           value="{{ $st['ca2_15'] !== null ? $st['ca2_15'] : '' }}" 
                                           data-prev-val="{{ $st['ca2_15'] !== null ? $st['ca2_15'] : '' }}"
                                           data-max="15"
                                           data-field-name="Self Learning CA2 (Module 2)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-ca2 no-spinners w-12 text-center px-1 py-1 text-xs" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Self Learning CA3 (Module 3, out of 15) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.5" min="0" max="15" 
                                           value="{{ $st['ca3_15'] !== null ? $st['ca3_15'] : '' }}" 
                                           data-prev-val="{{ $st['ca3_15'] !== null ? $st['ca3_15'] : '' }}"
                                           data-max="15"
                                           data-field-name="Self Learning CA3 (Module 3)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-ca3 no-spinners w-12 text-center px-1 py-1 text-xs" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Self Learning CA4 (Module 4, out of 15) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.5" min="0" max="15" 
                                           value="{{ $st['ca4_15'] !== null ? $st['ca4_15'] : '' }}" 
                                           data-prev-val="{{ $st['ca4_15'] !== null ? $st['ca4_15'] : '' }}"
                                           data-max="15"
                                           data-field-name="Self Learning CA4 (Module 4)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-ca4 no-spinners w-12 text-center px-1 py-1 text-xs" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Self Learning Scaled / Averaged out of 15 -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-amber-300 bg-amber-950/20 cell-sl-total">
                                    {{ number_format($st['self_learning_15'] ?? 0, 1) }}
                                </td>

                                <!-- Series 1 CO1 (Module 1, out of 20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_1_co1_20'] !== null ? $st['series_1_co1_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_1_co1_20'] !== null ? $st['series_1_co1_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 1 CO1 (Module 1)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-s1-co1 no-spinners w-11 text-center px-1 py-1 text-xs" 
                                           title="Series 1 CO1 (Module 1) out of 20"
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Series 1 CO2 (Module 2, out of 20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_1_co2_20'] !== null ? $st['series_1_co2_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_1_co2_20'] !== null ? $st['series_1_co2_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 1 CO2 (Module 2)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-s1-co2 no-spinners w-11 text-center px-1 py-1 text-xs" 
                                           title="Series 1 CO2 (Module 2) out of 20"
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Series 1 Total (Mod 1&2, out of 40) -->
                                <td class="border border-slate-800 text-center text-xs font-mono text-slate-300 cell-s1-total">
                                    {{ $st['series_1_40'] !== null ? number_format($st['series_1_40'], 1) : '-' }}
                                </td>

                                <!-- Series 2 CO3 (Module 3, out of 20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_2_co3_20'] !== null ? $st['series_2_co3_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_2_co3_20'] !== null ? $st['series_2_co3_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 2 CO3 (Module 3)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-s2-co3 no-spinners w-11 text-center px-1 py-1 text-xs" 
                                           title="Series 2 CO3 (Module 3) out of 20"
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Series 2 CO4 (Module 4, out of 20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_2_co4_20'] !== null ? $st['series_2_co4_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_2_co4_20'] !== null ? $st['series_2_co4_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 2 CO4 (Module 4)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-s2-co4 no-spinners w-11 text-center px-1 py-1 text-xs" 
                                           title="Series 2 CO4 (Module 4) out of 20"
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Series 2 Total (Mod 3&4, out of 40) -->
                                <td class="border border-slate-800 text-center text-xs font-mono text-slate-300 cell-s2-total">
                                    {{ $st['series_2_40'] !== null ? number_format($st['series_2_40'], 1) : '-' }}
                                </td>

                                <!-- Combined Series out of 20 -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-sky-300 bg-sky-950/20 cell-series-total">
                                    {{ number_format($st['series_20'] ?? 0, 1) }}
                                </td>

                                <!-- Total CIE (40) -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-emerald-400 bg-emerald-950/30 cell-cie-total">
                                    {{ number_format($st['total_cie_marks'] ?? 0, 1) }}
                                </td>

                                <!-- ESE Marks (60) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.5" min="0" max="60" 
                                           value="{{ $st['ese_mark_60'] !== null ? $st['ese_mark_60'] : '' }}" 
                                           data-prev-val="{{ $st['ese_mark_60'] !== null ? $st['ese_mark_60'] : '' }}"
                                           data-max="60"
                                           data-field-name="ESE Marks (End Semester Exam)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           placeholder="-"
                                           class="cell-ese-marks no-spinners w-14 text-center px-1 py-1 text-xs" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- ESE Board Grade -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="text" maxlength="2" 
                                           value="{{ $st['ese_grade'] ?: '' }}" 
                                           placeholder="Grade"
                                           class="cell-ese-grade uppercase w-10 text-center px-1 py-1 text-xs font-bold" 
                                           oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- ESE Absent Toggle -->
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="cell-ese-absent cursor-pointer" 
                                           {{ !empty($st['ese_absent']) ? 'checked' : '' }} 
                                           onchange="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                                </td>

                                <!-- Grand Total (100) -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-amber-300 bg-slate-950 cell-grand-total">
                                    {{ number_format($st['total_course_marks'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="23" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-800">
                    <span class="text-xs text-slate-400">Total Students: <strong class="text-white">{{ $studentResults->count() }}</strong></span>
                    <button onclick="triggerConsolidatedManualSave()" class="px-4 py-1.5 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save All Evaluation Records</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 4: SERIES TEST (DEDICATED ENTRY & QP BLUEPRINTS)                      -->
        <!-- ========================================================================= -->
        <div id="tab-content-series" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-4 border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-sm text-sky-400 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">edit_note</span>
                            <span>Series Examinations (CA5 &amp; CA6 - 20 CIE Marks Combined)</span>
                        </h3>
                        <p class="text-xs text-slate-400 mb-0">
                            Series Exam 1 (Modules 1 &amp; 2 - Max 40M &rarr; 20M) &bull; Series Exam 2 (Modules 3 &amp; 4 - Max 40M &rarr; 20M).
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/print/series-register" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series Register</span>
                        </a>
                        <a href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1" target="_blank" class="px-2.5 py-1 rounded bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/40 text-blue-300 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                            <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                            <span>Series 1 QP</span>
                        </a>
                        <a href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2" target="_blank" class="px-2.5 py-1 rounded bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/40 text-blue-300 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                            <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                            <span>Series 2 QP</span>
                        </a>
                        <button onclick="triggerConsolidatedManualSave()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Series Marks</span>
                        </button>
                    </div>
                </div>

                <!-- Series Dedicated Entry Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left" id="seriesDedicatedTable">
                        <thead>
                            <tr class="bg-slate-950/80 text-slate-300 text-xs">
                                <th rowspan="2" class="border border-slate-800 text-center w-12">#</th>
                                <th rowspan="2" class="border border-slate-800 w-28">Reg No</th>
                                <th rowspan="2" class="border border-slate-800 min-w-[160px]">Student Name</th>
                                <th colspan="5" class="border border-slate-800 text-center bg-blue-950/40 text-blue-300">Series Exam 1 (Modules 1 &amp; 2 - Max 40M)</th>
                                <th colspan="5" class="border border-slate-800 text-center bg-sky-950/40 text-sky-300">Series Exam 2 (Modules 3 &amp; 4 - Max 40M)</th>
                                <th rowspan="2" class="border border-slate-800 text-center w-28 bg-emerald-950/40 text-emerald-300">Combined<br>Series (20M)</th>
                            </tr>
                            <tr class="bg-slate-950/90 text-slate-400 text-[11px]">
                                <th class="border border-slate-800 text-center w-16" title="Module 1 / CO1 (Max 20)">CO1 (20)</th>
                                <th class="border border-slate-800 text-center w-16" title="Module 2 / CO2 (Max 20)">CO2 (20)</th>
                                <th class="border border-slate-800 text-center w-16 font-semibold text-slate-300" title="Total S1 Marks (Max 40)">Total (40)</th>
                                <th class="border border-slate-800 text-center w-14">Absent</th>
                                <th class="border border-slate-800 text-center w-16 text-blue-300">Conv (20)</th>
                                <th class="border border-slate-800 text-center w-16" title="Module 3 / CO3 (Max 20)">CO3 (20)</th>
                                <th class="border border-slate-800 text-center w-16" title="Module 4 / CO4 (Max 20)">CO4 (20)</th>
                                <th class="border border-slate-800 text-center w-16 font-semibold text-slate-300" title="Total S2 Marks (Max 40)">Total (40)</th>
                                <th class="border border-slate-800 text-center w-14">Absent</th>
                                <th class="border border-slate-800 text-center w-16 text-sky-300">Conv (20)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $index => $st)
                            <tr class="series-eval-row hover:bg-slate-800/40" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="border border-slate-800 text-center text-xs text-slate-400">{{ $index + 1 }}</td>
                                <td class="border border-slate-800 font-mono text-xs text-slate-300">{{ $st['reg_no'] }}</td>
                                <td class="border border-slate-800 text-xs font-semibold text-white">{{ $st['name'] }}</td>
                                
                                <!-- S1 CO1 Marks (20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_1_co1_20'] !== null ? $st['series_1_co1_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_1_co1_20'] !== null ? $st['series_1_co1_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 1 CO1 (Module 1)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-co-type="co1"
                                           placeholder="-" 
                                           class="series-tab-s1-co1 no-spinners w-14 text-center px-1 py-1 text-xs" 
                                           title="Series 1 CO1 (Module 1) out of 20"
                                           oninput="syncSeriesToMain('{{ $st['reg_no'] }}', 'co1', this.value); queueAutoSave();">
                                </td>

                                <!-- S1 CO2 Marks (20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_1_co2_20'] !== null ? $st['series_1_co2_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_1_co2_20'] !== null ? $st['series_1_co2_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 1 CO2 (Module 2)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-co-type="co2"
                                           placeholder="-" 
                                           class="series-tab-s1-co2 no-spinners w-14 text-center px-1 py-1 text-xs" 
                                           title="Series 1 CO2 (Module 2) out of 20"
                                           oninput="syncSeriesToMain('{{ $st['reg_no'] }}', 'co2', this.value); queueAutoSave();">
                                </td>

                                <!-- S1 Total (40) -->
                                <td class="border border-slate-800 text-center font-mono text-xs text-slate-300 series-tab-s1-total">
                                    {{ $st['series_1_40'] !== null ? number_format($st['series_1_40'], 1) : '-' }}
                                </td>

                                <!-- S1 Absent -->
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="series-tab-s1-abs cursor-pointer" 
                                           {{ !empty($st['series_1_absent']) ? 'checked' : '' }} 
                                           onchange="syncSeriesAbsToMain('{{ $st['reg_no'] }}', 's1', this.checked); queueAutoSave();">
                                </td>

                                <!-- S1 Converted (20) -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-blue-300 series-tab-s1-20">
                                    {{ $st['series_1_absent'] ? 'AB' : ($st['series_1_40'] !== null ? number_format($st['series_1_20'] ?? 0, 1) : '-') }}
                                </td>

                                <!-- S2 CO3 Marks (20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_2_co3_20'] !== null ? $st['series_2_co3_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_2_co3_20'] !== null ? $st['series_2_co3_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 2 CO3 (Module 3)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-co-type="co3"
                                           placeholder="-" 
                                           class="series-tab-s2-co3 no-spinners w-14 text-center px-1 py-1 text-xs" 
                                           title="Series 2 CO3 (Module 3) out of 20"
                                           oninput="syncSeriesToMain('{{ $st['reg_no'] }}', 'co3', this.value); queueAutoSave();">
                                </td>

                                <!-- S2 CO4 Marks (20) -->
                                <td class="border border-slate-800 p-1 text-center">
                                    <input type="number" step="0.1" min="0" max="20" 
                                           value="{{ $st['series_2_co4_20'] !== null ? $st['series_2_co4_20'] : '' }}" 
                                           data-prev-val="{{ $st['series_2_co4_20'] !== null ? $st['series_2_co4_20'] : '' }}"
                                           data-max="20"
                                           data-field-name="Series 2 CO4 (Module 4)"
                                           data-reg-no="{{ $st['reg_no'] }}"
                                           data-student-name="{{ addslashes($st['name']) }}"
                                           data-co-type="co4"
                                           placeholder="-" 
                                           class="series-tab-s2-co4 no-spinners w-14 text-center px-1 py-1 text-xs" 
                                           title="Series 2 CO4 (Module 4) out of 20"
                                           oninput="syncSeriesToMain('{{ $st['reg_no'] }}', 'co4', this.value); queueAutoSave();">
                                </td>

                                <!-- S2 Total (40) -->
                                <td class="border border-slate-800 text-center font-mono text-xs text-slate-300 series-tab-s2-total">
                                    {{ $st['series_2_40'] !== null ? number_format($st['series_2_40'], 1) : '-' }}
                                </td>

                                <!-- S2 Absent -->
                                <td class="border border-slate-800 text-center">
                                    <input type="checkbox" class="series-tab-s2-abs cursor-pointer" 
                                           {{ !empty($st['series_2_absent']) ? 'checked' : '' }} 
                                           onchange="syncSeriesAbsToMain('{{ $st['reg_no'] }}', 's2', this.checked); queueAutoSave();">
                                </td>

                                <!-- S2 Converted (20) -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-sky-300 series-tab-s2-20">
                                    {{ $st['series_2_absent'] ? 'AB' : ($st['series_2_40'] !== null ? number_format($st['series_2_20'] ?? 0, 1) : '-') }}
                                </td>

                                <!-- Combined Series (20M) -->
                                <td class="border border-slate-800 text-center font-bold text-xs text-emerald-400 bg-emerald-950/20 series-tab-comb-20">
                                    {{ number_format($st['series_20'] ?? 0, 1) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 5: ATTAINMENT CALCULATOR                                              -->
        <!-- ========================================================================= -->
        <div id="tab-content-attainment" class="tab-pane-content hidden space-y-5">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                
                <!-- Target Settings & Direct Attainment (Col Span 2) -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Threshold Controls Card -->
                    <div class="glass-card p-4 border border-slate-800">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                            <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                                <span class="material-symbols-rounded text-base">tune</span>
                                <span>Attainment Threshold Configuration</span>
                            </h3>
                            <button onclick="saveAttainmentSettingsApi()" class="px-3 py-1 rounded bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-rounded text-sm">save</span>
                                <span>Save Thresholds</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <label class="block text-slate-400 mb-1">CIE Threshold (% Target)</label>
                                <input type="number" id="cfg-cie-threshold" value="{{ $cieThreshold ?? 50.0 }}" class="no-spinners w-full px-2 py-1.5 text-xs font-bold text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">Target Students (% Level 3)</label>
                                <input type="number" id="cfg-target-student" value="{{ $targetStudentPercent ?? 70.0 }}" class="no-spinners w-full px-2 py-1.5 text-xs font-bold text-white">
                            </div>
                            <div>
                                <label class="block text-slate-400 mb-1">Level 2 Min %</label>
                                <input type="number" id="cfg-level2" value="{{ $lvl2Val ?? 60.0 }}" class="no-spinners w-full px-2 py-1.5 text-xs font-bold text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Direct & Overall Attainment Summary Table -->
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-amber-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">award_star</span>
                            <span>Course Outcome (CO) Attainment Levels</span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse text-center text-xs">
                                <thead>
                                    <tr class="bg-slate-950/80 text-slate-300">
                                        <th class="border border-slate-800 p-2 text-left">CO Tag</th>
                                        <th class="border border-slate-800 p-2">Attained Students</th>
                                        <th class="border border-slate-800 p-2">Attained %</th>
                                        <th class="border border-slate-800 p-2 text-cyan-300">Direct Level (80%)</th>
                                        <th class="border border-slate-800 p-2 text-amber-300">Indirect Level (20%)</th>
                                        <th class="border border-slate-800 p-2 text-emerald-400 font-bold">Overall Attainment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                    <tr class="hover:bg-slate-800/30">
                                        <td class="border border-slate-800 p-2 font-bold text-left text-cyan-300">{{ $coTag }}</td>
                                        <td class="border border-slate-800 p-2 font-mono">{{ $directStats[$coTag]['count'] ?? 0 }} / {{ max(1, $studentResults->count()) }}</td>
                                        <td class="border border-slate-800 p-2 font-mono font-bold">{{ $directStats[$coTag]['percentage'] ?? 0 }}%</td>
                                        <td class="border border-slate-800 p-2 font-bold text-cyan-400">Level {{ number_format($directStats[$coTag]['level'] ?? 0, 1) }}</td>
                                        <td class="border border-slate-800 p-2 font-semibold text-amber-300">Level {{ number_format($indirectStats[$coTag]['level'] ?? 2.5, 1) }}</td>
                                        <td class="border border-slate-800 p-2 font-extrabold text-sm text-emerald-400 bg-emerald-950/20">
                                            {{ number_format($combinedStats[$coTag] ?? 0.0, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Program Outcome (PO) Attainments (Col Span 1) -->
                <div class="space-y-4">
                    <div class="glass-card p-4 border border-slate-800">
                        <h3 class="font-bold text-sm text-emerald-400 mb-3 flex items-center gap-2">
                            <span class="material-symbols-rounded text-base">bar_chart</span>
                            <span>Program Outcome (PO) Attainment</span>
                        </h3>
                        <div class="space-y-2">
                            @for($p = 1; $p <= 11; $p++)
                            @php 
                                $pName = "PO{$p}";
                                $pVal = $poAttainments[$pName]['value'] ?? 0.0;
                                $pWeight = $poAttainments[$pName]['weight'] ?? 0;
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded bg-slate-950/70 border border-slate-800 text-xs">
                                <span class="font-bold text-slate-300 font-mono">{{ $pName }}</span>
                                @if($pWeight > 0)
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-800 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, ($pVal / 3.0) * 100) }}%;"></div>
                                    </div>
                                    <span class="font-mono font-bold text-emerald-400">{{ number_format($pVal, 2) }}</span>
                                </div>
                                @else
                                <span class="text-slate-600 font-mono">-</span>
                                @endif
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 6: REPORTS                                                            -->
        <!-- ========================================================================= -->
        <div id="tab-content-reports" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-4 border border-slate-800">
                <div class="mb-4 pb-2 border-b border-slate-800">
                    <h3 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base">print</span>
                        <span>Official Printable Registers &amp; Academic Reports</span>
                    </h3>
                    <p class="text-xs text-slate-400 mb-0">Generate print-ready single-click A4 documents for NBA/SBTE Audits and Department Archiving.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    <!-- Report 1: Consolidated CIA Register -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-blue-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">fact_check</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">1. Consolidated CIA Register (40M)</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">Full table featuring Attendance (5M), Self-Learning (15M), Series (20M) and total 40 Marks.</p>
                        <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/print/cia-register" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CIA Register</span>
                        </a>
                    </div>

                    <!-- Report 2: Series Examination Register -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-sky-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-sky-600/20 text-sky-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">edit_note</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">2. Series Examination Register</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">Official breakdown of Series 1 (Mod 1&amp;2) &amp; Series 2 (Mod 3&amp;4) converted into 20 Marks.</p>
                        <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/print/series-register" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series Register</span>
                        </a>
                    </div>

                    <!-- Report 3: Lesson Plan Schedule -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-amber-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-amber-600/20 text-amber-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">calendar_month</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">3. Drawing Lesson Plan Schedule</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">45-Hour day-wise syllabus plan, proposed and actual completion dates with signatures.</p>
                        <a href="/r26/classroom/drawing/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Lesson Plan</span>
                        </a>
                    </div>

                    <!-- Report 4: Question Paper Series 1 -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-emerald-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600/20 text-emerald-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">quiz</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">4. Series 1 Question Paper &amp; Scheme</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">Standardized question paper blueprint, evaluation scheme and question bank for Series 1.</p>
                        <a href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series 1 QP</span>
                        </a>
                    </div>

                    <!-- Report 5: Question Paper Series 2 -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-purple-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-purple-600/20 text-purple-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">quiz</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">5. Series 2 Question Paper &amp; Scheme</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">Standardized question paper blueprint, evaluation scheme and question bank for Series 2.</p>
                        <a href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series 2 QP</span>
                        </a>
                    </div>

                    <!-- Report 6: Consolidated Attendance Report -->
                    <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2 hover:border-cyan-500/40 transition-all">
                        <div class="w-9 h-9 rounded-lg bg-cyan-600/20 text-cyan-400 flex items-center justify-center font-bold">
                            <span class="material-symbols-rounded">rule</span>
                        </div>
                        <h4 class="font-bold text-xs text-white">6. Consolidated Attendance Log</h4>
                        <p class="text-[11px] text-slate-400 leading-snug">Session-wise attendance sheet with overall percentages and CIA attendance marks (5M).</p>
                        <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Attendance Log</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 7: STUDY MATERIAL & PRECLASS HUB                                      -->
        <!-- ========================================================================= -->
        <div id="tab-content-materials" class="tab-pane-content hidden space-y-4">
            @include('partials.virtual_learning_hub_tab', ['roomType' => 'Drawing'])
        </div>

    </main>

    <!-- SYLLABUS UPLOAD MODAL -->
    <div id="syllabusModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-card max-w-md w-full p-5 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                <h4 class="font-bold text-sm text-cyan-400 flex items-center gap-2">
                    <span class="material-symbols-rounded text-base">cloud_upload</span>
                    <span>Upload Drawing Syllabus PDF</span>
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
                    <button type="submit" id="uploadBtn" class="px-3 py-1.5 rounded bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-rounded text-sm">upload</span>
                        <span>Upload &amp; Parse</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = {{ $batchSubject->id }};

        // Tab Switching Logic
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane-content').forEach(pane => pane.classList.add('hidden'));
            document.querySelectorAll('.subtab-btn').forEach(btn => {
                btn.classList.remove('active', 'text-blue-400', 'bg-blue-500/5', 'border-blue-400');
                btn.classList.add('text-slate-400', 'border-transparent');
            });

            const targetPane = document.getElementById(`tab-content-${tabId}`);
            const targetBtn = document.getElementById(`tab-btn-${tabId}`);

            if (targetPane) targetPane.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.remove('text-slate-400', 'border-transparent');
                targetBtn.classList.add('active', 'text-blue-400', 'bg-blue-500/5', 'border-blue-400');
            }

            // Sync textareas if opened lesson plan
            if (tabId === 'lessonplan') {
                document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));
            }
        }

        // Auto Grow Textarea Helper
        function autoGrow(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        // Close Tab / Back to Dashboard
        function closeTabOrGoDashboard() {
            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }
            window.close();
            setTimeout(function() {
                let ref = document.referrer;
                if (ref && (ref.includes('/dashboard/') || ref.includes('/classroom/'))) {
                    window.location.href = ref;
                } else {
                    window.location.href = '{{ $dashboardUrl }}';
                }
            }, 150);
        }

        // Fullscreen Toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {});
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Modal Controls
        function openSyllabusModal() {
            const m = document.getElementById('syllabusModal');
            if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
        }
        function closeSyllabusModal() {
            const m = document.getElementById('syllabusModal');
            if (m) { m.classList.remove('flex'); m.classList.add('hidden'); }
        }

        // Attendance Slabs Function:
        // >= 90% -> 5 | 80-89% -> 4 | 75-79% -> 3 | 70-74% -> 2 | 65-69% -> 1 | <65% -> 0
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

        // Interactive Attendance Percentage Override with User Confirmation & Amber Border
        async function handleAttendanceOverrideChange(input, regNo, studentName) {
            const prevVal = input.dataset.prevVal !== undefined ? input.dataset.prevVal.trim() : '';
            const newVal = input.value.trim();

            if (newVal === prevVal) return;

            if (newVal !== '') {
                const parsedNew = parseFloat(newVal);
                if (isNaN(parsedNew) || parsedNew < 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Percentage',
                        text: 'Attendance override percentage cannot be negative.'
                    });
                    input.value = prevVal;
                    return;
                }

                if (parsedNew > 100) {
                    const result = await Swal.fire({
                        title: 'Percentage Exceeds Maximum!',
                        html: `The entered attendance override <strong class="text-rose-400 font-mono text-base">${parsedNew}%</strong> exceeds the fixed maximum limit of <strong class="text-emerald-400 font-mono text-base">100.0%</strong> for <strong>${studentName}</strong> (${regNo}).<br><br>
                               <div class="text-xs text-slate-300 p-2.5 rounded bg-slate-950/80 border border-slate-800 text-left space-y-1">
                                 <div>Student: <strong class="text-white">${studentName}</strong> (${regNo})</div>
                                 <div>Entered Percentage: <span class="text-rose-400 font-bold">${parsedNew}%</span></div>
                                 <div>Fixed Maximum Allowed: <span class="text-emerald-400 font-bold">100.0%</span></div>
                               </div>
                               <span class="text-[11px] text-amber-400 mt-2 block font-semibold">⚠️ Do you want to confirm and allow the fixed maximum percentage of 100.0%?</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, Allow Fixed Maximum (100%)',
                        cancelButtonText: 'Cancel & Revert',
                        allowOutsideClick: false
                    });

                    if (result.isConfirmed) {
                        input.value = '100.0';
                        input.dataset.prevVal = '100.0';
                        input.classList.add('has-override');
                        recalculateRow(regNo);
                        queueAutoSave();
                    } else {
                        input.value = prevVal;
                        if (prevVal !== '') {
                            input.classList.add('has-override');
                        } else {
                            input.classList.remove('has-override');
                        }
                    }
                    return;
                }

                const teamPct = input.getAttribute('placeholder') || '-';
                const result = await Swal.fire({
                    title: 'Confirm Attendance Override?',
                    html: `Are you sure you want to override the attendance for <strong>${studentName}</strong> (${regNo})?<br><br>
                           <div class="text-xs text-slate-300 p-2.5 rounded bg-slate-950/80 border border-slate-800 text-left space-y-1">
                             <div>Teams %: <strong class="text-white">${teamPct !== '-' ? teamPct + '%' : 'None recorded'}</strong></div>
                             <div>New Override %: <strong class="text-amber-400 font-bold">${parsedNew.toFixed(1)}%</strong></div>
                           </div>
                           <span class="text-[11px] text-amber-400 mt-2 block font-semibold">⚠️ This will recalculate attendance marks (Max 5M) and apply an amber border indicator.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Apply Override',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    input.value = parsedNew.toFixed(1);
                    input.dataset.prevVal = input.value;
                    input.classList.add('has-override');
                    recalculateRow(regNo);
                    queueAutoSave();
                } else {
                    input.value = prevVal;
                    if (prevVal !== '') {
                        input.classList.add('has-override');
                    } else {
                        input.classList.remove('has-override');
                    }
                }
            } else {
                const result = await Swal.fire({
                    title: 'Remove Attendance Override?',
                    html: `Remove attendance override for <strong>${studentName}</strong> (${regNo}) and revert to raw <strong>Teams %</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Revert to Teams %',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    input.value = '';
                    input.dataset.prevVal = '';
                    input.classList.remove('has-override');
                    recalculateRow(regNo);
                    queueAutoSave();
                } else {
                    input.value = prevVal;
                    if (prevVal !== '') {
                        input.classList.add('has-override');
                    }
                }
            }
        }

        // Global Prompting Flag to Prevent Duplicate Modals
        let isPromptingMax = false;

        // Universal Fixed Maximum Confirmation Dialog
        async function validateAndConfirmMax(input, maxLimit, fieldLabel, regNo, studentName, syncCoType = null) {
            if (isPromptingMax) return;

            const rawVal = input.value.trim();
            if (rawVal === '') {
                input.dataset.prevVal = '';
                input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                if (syncCoType) {
                    syncSeriesToMain(regNo, syncCoType, '');
                } else {
                    recalculateRow(regNo);
                }
                queueAutoSave();
                return;
            }

            const numVal = parseFloat(rawVal);
            const prevVal = input.dataset.prevVal !== undefined ? input.dataset.prevVal.trim() : '';

            if (isNaN(numVal)) {
                input.value = prevVal;
                input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                return;
            }

            if (numVal < 0) {
                isPromptingMax = true;
                await Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Mark',
                    text: `${fieldLabel} cannot be negative. Value set to 0.`
                });
                isPromptingMax = false;
                input.value = 0;
                input.dataset.prevVal = '0';
                input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                if (syncCoType) syncSeriesToMain(regNo, syncCoType, '0');
                else recalculateRow(regNo);
                queueAutoSave();
                return;
            }

            if (numVal > maxLimit) {
                isPromptingMax = true;
                const result = await Swal.fire({
                    title: 'Mark Exceeds Maximum Limit',
                    html: `The entered mark <strong class="text-rose-400 font-mono text-base">${numVal}</strong> exceeds the fixed maximum limit of <strong class="text-emerald-400 font-mono text-base">${maxLimit}</strong> for <strong>${fieldLabel}</strong>.<br><br>
                           <div class="text-xs text-slate-300 p-2.5 rounded bg-slate-950/80 border border-slate-800 text-left space-y-1">
                             <div>Student: <strong class="text-white">${studentName}</strong> (${regNo})</div>
                             <div>Entered Mark: <span class="text-rose-400 font-bold">${numVal}</span></div>
                             <div>Fixed Maximum Allowed: <span class="text-emerald-400 font-bold">${maxLimit}</span></div>
                           </div>
                           <span class="text-[11px] text-amber-400 mt-2 block font-semibold">⚠️ Do you want to confirm and allow the fixed maximum number (${maxLimit})?</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: `Yes, Allow Fixed Maximum (${maxLimit})`,
                    cancelButtonText: 'Cancel & Revert',
                    allowOutsideClick: false
                });
                isPromptingMax = false;

                if (result.isConfirmed) {
                    input.value = maxLimit;
                    input.dataset.prevVal = String(maxLimit);
                    input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                    if (syncCoType) syncSeriesToMain(regNo, syncCoType, String(maxLimit));
                    else recalculateRow(regNo);
                    queueAutoSave();
                } else {
                    input.value = prevVal;
                    input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                    if (syncCoType) syncSeriesToMain(regNo, syncCoType, prevVal);
                    else recalculateRow(regNo);
                    queueAutoSave();
                }
            } else {
                // Valid mark <= maxLimit
                input.dataset.prevVal = input.value;
                input.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                if (syncCoType) syncSeriesToMain(regNo, syncCoType, input.value);
                else recalculateRow(regNo);
                queueAutoSave();
            }
        }

        // Attach event listeners for real-time visual feedback and validation
        function attachMarkInputValidation() {
            document.querySelectorAll('input[data-max]').forEach(inp => {
                // Track previous valid value on focus
                inp.addEventListener('focus', function() {
                    this.dataset.prevVal = this.value;
                });

                // Real-time visual feedback on input
                inp.addEventListener('input', function() {
                    const max = parseFloat(this.dataset.max);
                    const val = parseFloat(this.value.trim());
                    if (!isNaN(val) && val > max) {
                        this.classList.add('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                        this.title = `Exceeds fixed maximum of ${max}!`;
                    } else {
                        this.classList.remove('border-rose-500', 'bg-rose-950/40', 'text-rose-300', 'ring-1', 'ring-rose-500');
                        this.removeAttribute('title');
                    }
                });

                // On change (blur, Enter, or focus departure): prompt confirmation if exceeds max
                inp.addEventListener('change', function() {
                    if (this.classList.contains('cell-att-override')) return; // handled by handleAttendanceOverrideChange

                    const max = parseFloat(this.dataset.max);
                    const fieldName = this.dataset.fieldName || 'Marks';
                    const regNo = this.dataset.regNo;
                    const studentName = this.dataset.studentName || regNo;
                    const coType = this.dataset.coType || null;
                    validateAndConfirmMax(this, max, fieldName, regNo, studentName, coType);
                });
            });
        }

        // Dynamic Row Recalculation for Consolidated CIA & ESE
        function recalculateRow(regNo) {
            const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!row) return;

            // Attendance
            const overrideInput = row.querySelector('.cell-att-override');
            const overrideVal = overrideInput.value.trim();
            let effectivePct = null;

            if (overrideVal !== '') {
                effectivePct = Math.min(100.0, Math.max(0.0, parseFloat(overrideVal)));
                overrideInput.classList.add('has-override');
            } else {
                overrideInput.classList.remove('has-override');
                const placeholder = overrideInput.getAttribute('placeholder');
                if (placeholder && placeholder !== '-' && !isNaN(parseFloat(placeholder))) {
                    effectivePct = parseFloat(placeholder);
                } else {
                    effectivePct = null;
                }
            }
            const attMark = effectivePct !== null ? calculateAttMarks(effectivePct) : 0.0;
            const attMarkCell = row.querySelector('.cell-att-mark');
            if (attMarkCell) attMarkCell.textContent = attMark.toFixed(1);

            // Self Learning CA1-CA4 (out of 15 each)
            const ca1 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca1').value) || 0));
            const ca2 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca2').value) || 0));
            const ca3 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca3').value) || 0));
            const ca4 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca4').value) || 0));

            const caInputs = [row.querySelector('.cell-ca1').value.trim(), row.querySelector('.cell-ca2').value.trim(), row.querySelector('.cell-ca3').value.trim(), row.querySelector('.cell-ca4').value.trim()];
            let enteredCount = caInputs.filter(v => v !== '').length;

            let slTotal15 = 0.0;
            if (enteredCount > 0) {
                slTotal15 = Math.round(((ca1 + ca2 + ca3 + ca4) / 4.0) * 2) / 2;
            }
            if (slTotal15 > 15.0) slTotal15 = 15.0;
            row.querySelector('.cell-sl-total').textContent = slTotal15.toFixed(1);

            // Series 1: CO1 (20) & CO2 (20) -> Total (40) -> Conv (20)
            const co1Val = row.querySelector('.cell-s1-co1').value.trim();
            const co2Val = row.querySelector('.cell-s1-co2').value.trim();
            const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
            const s1Abs = seriesRow ? seriesRow.querySelector('.series-tab-s1-abs').checked : false;
            const s2Abs = seriesRow ? seriesRow.querySelector('.series-tab-s2-abs').checked : false;

            let s1Raw = null;
            let s1_20 = 0.0;
            if (co1Val !== '' || co2Val !== '') {
                const c1 = Math.min(20.0, Math.max(0.0, parseFloat(co1Val) || 0));
                const c2 = Math.min(20.0, Math.max(0.0, parseFloat(co2Val) || 0));
                s1Raw = Math.min(40.0, c1 + c2);
                row.querySelector('.cell-s1-total').textContent = s1Raw.toFixed(1);
                s1_20 = !s1Abs ? Math.round((s1Raw / 2.0) * 2) / 2 : 0.0;
            } else {
                row.querySelector('.cell-s1-total').textContent = '-';
            }

            // Series 2: CO3 (20) & CO4 (20) -> Total (40) -> Conv (20)
            const co3Val = row.querySelector('.cell-s2-co3').value.trim();
            const co4Val = row.querySelector('.cell-s2-co4').value.trim();
            let s2Raw = null;
            let s2_20 = 0.0;
            if (co3Val !== '' || co4Val !== '') {
                const c3 = Math.min(20.0, Math.max(0.0, parseFloat(co3Val) || 0));
                const c4 = Math.min(20.0, Math.max(0.0, parseFloat(co4Val) || 0));
                s2Raw = Math.min(40.0, c3 + c4);
                row.querySelector('.cell-s2-total').textContent = s2Raw.toFixed(1);
                s2_20 = !s2Abs ? Math.round((s2Raw / 2.0) * 2) / 2 : 0.0;
            } else {
                row.querySelector('.cell-s2-total').textContent = '-';
            }

            let seriesComb20 = 0.0;
            if (s1Raw !== null || s2Raw !== null) {
                seriesComb20 = Math.round(((s1_20 + s2_20) / 2.0) * 2) / 2;
            }
            if (seriesComb20 > 20.0) seriesComb20 = 20.0;
            row.querySelector('.cell-series-total').textContent = seriesComb20.toFixed(1);

            // Total CIE (Max 40M) = Attendance (5) + Self-Learning (15) + Series (20)
            let totalCie40 = Math.round((attMark + slTotal15 + seriesComb20) * 2) / 2;
            if (totalCie40 > 40.0) totalCie40 = 40.0;
            row.querySelector('.cell-cie-total').textContent = totalCie40.toFixed(1);

            // ESE (60M)
            const eseAbsent = row.querySelector('.cell-ese-absent').checked;
            const eseMarksInput = row.querySelector('.cell-ese-marks').value.trim();
            const eseMarks = (!eseAbsent && eseMarksInput !== '') ? Math.min(60.0, Math.max(0.0, parseFloat(eseMarksInput))) : 0.0;

            // Grand Total (100)
            const grandTotal = Math.round((totalCie40 + eseMarks) * 2) / 2;
            row.querySelector('.cell-grand-total').textContent = grandTotal.toFixed(1);

            // Synchronize dedicated Series tab row
            syncMainToSeries(regNo, co1Val, co2Val, s1Raw, s1Abs, s1_20, co3Val, co4Val, s2Raw, s2Abs, s2_20, seriesComb20);
        }

        // Bidirectional sync between Tab 3 and Tab 4
        function syncSeriesToMain(regNo, coType, val) {
            const mainRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
            if (!mainRow) return;

            if (coType === 'co1') {
                mainRow.querySelector('.cell-s1-co1').value = val;
            } else if (coType === 'co2') {
                mainRow.querySelector('.cell-s1-co2').value = val;
            } else if (coType === 'co3') {
                mainRow.querySelector('.cell-s2-co3').value = val;
            } else if (coType === 'co4') {
                mainRow.querySelector('.cell-s2-co4').value = val;
            }
            recalculateRow(regNo);
        }

        function syncSeriesAbsToMain(regNo, seriesType, isChecked) {
            recalculateRow(regNo);
        }

        function syncMainToSeries(regNo, co1Val, co2Val, s1Raw, s1Abs, s1_20, co3Val, co4Val, s2Raw, s2Abs, s2_20, comb20) {
            const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
            if (!seriesRow) return;

            seriesRow.querySelector('.series-tab-s1-co1').value = co1Val;
            seriesRow.querySelector('.series-tab-s1-co2').value = co2Val;
            seriesRow.querySelector('.series-tab-s1-total').textContent = s1Raw !== null ? s1Raw.toFixed(1) : '-';
            seriesRow.querySelector('.series-tab-s1-20').textContent = s1Abs ? 'AB' : (s1Raw !== null ? s1_20.toFixed(1) : '-');

            seriesRow.querySelector('.series-tab-s2-co3').value = co3Val;
            seriesRow.querySelector('.series-tab-s2-co4').value = co4Val;
            seriesRow.querySelector('.series-tab-s2-total').textContent = s2Raw !== null ? s2Raw.toFixed(1) : '-';
            seriesRow.querySelector('.series-tab-s2-20').textContent = s2Abs ? 'AB' : (s2Raw !== null ? s2_20.toFixed(1) : '-');

            seriesRow.querySelector('.series-tab-comb-20').textContent = comb20.toFixed(1);
        }

        // Debounced Auto-Save Mechanism
        let autoSaveTimer = null;
        function queueAutoSave() {
            showSaveStatus('Saving...', true);
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                executeConsolidatedSave(true);
            }, 1200);
        }

        function showSaveStatus(text, isSaving = false) {
            const pill = document.getElementById('global-save-pill');
            const statusText = document.getElementById('save-status-text');
            if (!pill || !statusText) return;

            statusText.textContent = text;
            pill.classList.remove('hidden');
            pill.classList.add('flex');

            if (isSaving) {
                pill.classList.remove('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
                pill.classList.add('bg-blue-500/10', 'border-blue-500/30', 'text-blue-400');
            } else {
                pill.classList.remove('bg-blue-500/10', 'border-blue-500/30', 'text-blue-400');
                pill.classList.add('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
                setTimeout(() => {
                    pill.classList.add('hidden');
                    pill.classList.remove('flex');
                }, 3000);
            }
        }

        // Save Consolidated Evaluations (AJAX)
        async function executeConsolidatedSave(isAuto = false) {
            const marksData = [];
            document.querySelectorAll('.student-eval-row').forEach(row => {
                const regNo = row.dataset.regNo;
                const attOverride = row.querySelector('.cell-att-override').value.trim();
                const ca1 = row.querySelector('.cell-ca1').value.trim();
                const ca2 = row.querySelector('.cell-ca2').value.trim();
                const ca3 = row.querySelector('.cell-ca3').value.trim();
                const ca4 = row.querySelector('.cell-ca4').value.trim();

                const co1 = row.querySelector('.cell-s1-co1').value.trim();
                const co2 = row.querySelector('.cell-s1-co2').value.trim();
                const co3 = row.querySelector('.cell-s2-co3').value.trim();
                const co4 = row.querySelector('.cell-s2-co4').value.trim();

                const eseMarks = row.querySelector('.cell-ese-marks').value.trim();
                const eseGrade = row.querySelector('.cell-ese-grade').value.trim();
                const eseAbsent = row.querySelector('.cell-ese-absent').checked;

                // Series absent flags from series tab if available
                const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
                const s1Abs = seriesRow ? seriesRow.querySelector('.series-tab-s1-abs').checked : false;
                const s2Abs = seriesRow ? seriesRow.querySelector('.series-tab-s2-abs').checked : false;

                marksData.push({
                    reg_no: regNo,
                    attendance_override_pct: attOverride !== '' ? Math.min(100.0, Math.max(0.0, parseFloat(attOverride))) : null,
                    ca1_15: ca1 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca1))) : null,
                    ca2_15: ca2 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca2))) : null,
                    ca3_15: ca3 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca3))) : null,
                    ca4_15: ca4 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca4))) : null,
                    series_1_co1_20: co1 !== '' ? Math.min(20.0, Math.max(0.0, parseFloat(co1))) : null,
                    series_1_co2_20: co2 !== '' ? Math.min(20.0, Math.max(0.0, parseFloat(co2))) : null,
                    series_1_absent: s1Abs ? 1 : 0,
                    series_2_co3_20: co3 !== '' ? Math.min(20.0, Math.max(0.0, parseFloat(co3))) : null,
                    series_2_co4_20: co4 !== '' ? Math.min(20.0, Math.max(0.0, parseFloat(co4))) : null,
                    series_2_absent: s2Abs ? 1 : 0,
                    ese_mark_60: eseMarks !== '' ? Math.min(60.0, Math.max(0.0, parseFloat(eseMarks))) : null,
                    ese_grade: eseGrade !== '' ? eseGrade : null,
                    ese_absent: eseAbsent ? 1 : 0
                });
            });

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/consolidated-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ marks_data: marksData })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    showSaveStatus('Saved', false);
                    if (!isAuto) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Marks Saved',
                            text: 'All consolidated evaluation records successfully saved!',
                            timer: 1600,
                            showConfirmButton: false
                        });
                    }
                } else {
                    showSaveStatus('Save Failed', false);
                    if (!isAuto) Swal.fire('Error', data.message || 'Failed to save marks.', 'error');
                }
            } catch (err) {
                showSaveStatus('Offline / Retry', false);
                if (!isAuto) Swal.fire('Error', 'Network error while saving evaluations.', 'error');
            }
        }

        function triggerConsolidatedManualSave() {
            executeConsolidatedSave(false);
        }

        // Save Attainment Threshold Settings
        async function saveAttainmentSettingsApi() {
            const cieTh = document.getElementById('cfg-cie-threshold').value;
            const target = document.getElementById('cfg-target-student').value;
            const lvl2 = document.getElementById('cfg-level2').value;

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/attainment/save-settings`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        cie_threshold_percent: cieTh,
                        target_student_percent: target,
                        level2_percent: lvl2
                    })
                });
                const d = await res.json();
                if (d.status === 'SUCCESS') {
                    Swal.fire({ icon: 'success', title: 'Thresholds Updated', timer: 1400, showConfirmButton: false });
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    Swal.fire('Error', d.message, 'error');
                }
            } catch(e) {
                Swal.fire('Error', 'Network error updating thresholds', 'error');
            }
        }

        // Lesson Planner Scripts
        function addLessonPlanRow() {
            const container = document.getElementById('lesson-plan-rows-container');
            const currentRows = container.querySelectorAll('.lesson-plan-row').length;
            const nextNo = currentRows + 1;

            const tr = document.createElement('tr');
            tr.className = 'lesson-plan-row hover:bg-slate-800/30';
            tr.setAttribute('data-id', 'new_' + Date.now());
            tr.innerHTML = `
                <td class="border border-slate-800 text-center font-bold text-cyan-400 lp-day-no">#${nextNo}</td>
                <td class="border border-slate-800 p-1.5"><input type="date" class="lp-proposed w-full px-2 py-1 text-xs"></td>
                <td class="border border-slate-800 p-1.5"><input type="date" class="lp-actual w-full px-2 py-1 text-xs"></td>
                <td class="border border-slate-800 p-1.5"><textarea class="growable-textarea lp-topic px-2 py-1 text-xs" rows="1" oninput="autoGrow(this)" placeholder="Enter exercise title..."></textarea></td>
                <td class="border border-slate-800 p-1.5 text-center">
                    <select class="lp-co w-full px-1.5 py-1 text-xs text-center font-bold text-amber-300">
                        <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option>
                    </select>
                </td>
                <td class="border border-slate-800 p-1.5 text-center"><input type="number" min="1" max="6" value="3" class="lp-hours no-spinners w-full text-center px-1 py-1 text-xs font-bold"></td>
                <td class="border border-slate-800 p-1.5">
                    <select class="lp-pedagogy w-full px-2 py-1 text-xs">
                        <option value="Drawing Lab Practical (P)">Drawing Practical (P)</option>
                        <option value="CAD Lab Practice (P)">CAD Lab Practice (P)</option>
                        <option value="Series Test 1 (CA1)">Series Test 1 (CA1)</option>
                        <option value="Series Test 2 (CA2)">Series Test 2 (CA2)</option>
                        <option value="Revision / Remedial">Revision / Remedial</option>
                    </select>
                </td>
                <td class="border border-slate-800 p-1.5 text-center">
                    <select class="lp-status w-full px-2 py-1 text-xs font-semibold">
                        <option value="Pending" class="text-amber-400">Pending</option>
                        <option value="Completed" class="text-emerald-400">Completed</option>
                    </select>
                </td>
            `;
            container.appendChild(tr);
        }

        async function saveLessonPlannerBulk() {
            const plans = {};
            document.querySelectorAll('#lesson-plan-table tbody tr.lesson-plan-row').forEach(tr => {
                const id = tr.dataset.id;
                plans[id] = {
                    day_no: tr.querySelector('.lp-day-no').textContent.replace('#', '').trim(),
                    proposed_date: tr.querySelector('.lp-proposed').value,
                    actual_date: tr.querySelector('.lp-actual').value,
                    topic_content: tr.querySelector('.lp-topic').value,
                    co_tag: tr.querySelector('.lp-co').value,
                    allocated_hours: tr.querySelector('.lp-hours').value,
                    pedagogy: tr.querySelector('.lp-pedagogy').value,
                    status: tr.querySelector('.lp-status').value
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans })
                });
                const d = await res.json();
                if (d.status === 'SUCCESS') {
                    Swal.fire({ icon: 'success', title: 'Lesson Plan Saved', timer: 1400, showConfirmButton: false });
                } else {
                    Swal.fire('Error', d.message, 'error');
                }
            } catch(e) {
                Swal.fire('Error', 'Network error saving lesson plan', 'error');
            }
        }

        async function generateLessonTimeline() {
            if (!confirm('Regenerate 45-Hour Drawing Lesson Plan? This will re-index all sessions based on the current syllabus modules.')) return;
            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: 'single' })
                });
                const d = await res.json();
                if (d.status === 'SUCCESS') {
                    window.location.reload();
                } else {
                    alert('Error: ' + d.message);
                }
            } catch(e) {
                alert('Generation failed: ' + e.message);
            }
        }

        // Upload Syllabus PDF Form
        document.getElementById('uploadSyllabusForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('uploadBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">refresh</span><span>Parsing PDF...</span>';

            const formData = new FormData(this);
            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert('Syllabus uploaded and parsed successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(err) {
                alert('Upload failed: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-rounded text-sm">upload</span><span>Upload &amp; Parse</span>';
                closeSyllabusModal();
            }
        });

        // Vertical Row-by-Row Keyboard Navigation (Tab & Enter move down to same CO column)
        function setupVerticalKeyboardNavigation() {
            const tableIds = ['consolidatedCiaTable', 'seriesDedicatedTable'];

            tableIds.forEach(tableId => {
                const table = document.getElementById(tableId);
                if (!table) return;

                // Auto-select text on focus for effortless overwrite
                table.querySelectorAll('input:not([type="checkbox"])').forEach(inp => {
                    inp.addEventListener('focus', function() {
                        if (typeof this.select === 'function') {
                            this.select();
                        }
                    });
                });

                table.addEventListener('keydown', function(e) {
                    const isTab = e.key === 'Tab';
                    const isEnter = e.key === 'Enter';
                    const isArrowDown = e.key === 'ArrowDown';
                    const isArrowUp = e.key === 'ArrowUp';

                    if (!isTab && !isEnter && !isArrowDown && !isArrowUp) return;

                    const input = e.target;
                    if (!input || input.tagName !== 'INPUT' || input.type === 'checkbox') return;

                    const row = input.closest('tr');
                    if (!row) return;

                    const tbody = row.closest('tbody');
                    if (!tbody) return;

                    // Identify the target input class (e.g. cell-s1-co1, cell-ca1, series-tab-s1-co1)
                    const targetClass = Array.from(input.classList).find(c => 
                        c.startsWith('cell-') || c.startsWith('series-tab-')
                    );
                    if (!targetClass) return;

                    const isReverse = e.shiftKey || isArrowUp;

                    e.preventDefault();

                    // If input value exceeds fixed maximum or is changed attendance override, blur to prompt confirmation
                    if (input.dataset.max) {
                        const maxVal = parseFloat(input.dataset.max);
                        const currVal = parseFloat(input.value.trim());
                        if (!isNaN(currVal) && currVal > maxVal) {
                            input.blur();
                            return;
                        }
                    }

                    if (input.classList.contains('cell-att-override') && input.value !== (input.dataset.prevVal || '')) {
                        input.blur();
                        return;
                    }

                    const rows = Array.from(tbody.querySelectorAll('tr.student-eval-row, tr.series-eval-row'));
                    const currentIndex = rows.indexOf(row);
                    if (currentIndex === -1) return;

                    const nextIndex = isReverse ? currentIndex - 1 : currentIndex + 1;
                    if (nextIndex >= 0 && nextIndex < rows.length) {
                        const targetRow = rows[nextIndex];
                        const nextInput = targetRow.querySelector('.' + targetClass);
                        if (nextInput) {
                            nextInput.focus();
                            if (typeof nextInput.select === 'function') {
                                nextInput.select();
                            }
                        }
                    }
                });
            });
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));
            setupVerticalKeyboardNavigation();
            attachMarkInputValidation();
        });
    </script>

</body>
</html>
