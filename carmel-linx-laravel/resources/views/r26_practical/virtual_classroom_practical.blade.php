<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practical Virtual Lab - {{ $batchSubject->subject_name }}</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS Browser CDN -->
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
            --bg-base:       #0b1120;       /* deep rich slate */
            --bg-card:       #020617;       /* slate-950 */
            --bg-card-hover: #1e293b;       /* slate-800 */
            --border:        #334155;       /* slate-700 high visibility */
            --border-light:  #475569;       /* slate-600 */
            --text-primary:  #f8fafc;       /* slate-50 bright */
            --text-secondary:#cbd5e1;       /* slate-300 readable */
            --text-muted:    #94a3b8;       /* slate-400 */
            --accent:        #3b82f6;       /* blue-500 */
            --accent-hover:  #2563eb;       /* blue-600 */
            --accent-cyan:   #06b6d4;       /* cyan-500 */
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15.5px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.01em;
            text-shadow: none !important;
            filter: none !important;
            font-weight: 800;
        }

        /* ── Compact & Readable Table Typography ──────────── */
        table {
            font-size: 0.8125rem; /* ~13px compact professional */
            border-color: #334155 !important;
        }
        table th {
            font-size: 0.725rem !important; /* ~11.6px uppercase */
            font-weight: 800 !important;
            letter-spacing: 0.035em !important;
            text-transform: uppercase !important;
            color: #cbd5e1 !important;
            padding: 0.45rem 0.45rem !important;
            line-height: 1.25 !important;
        }
        table td {
            font-size: 0.8125rem !important; /* ~13px */
            color: #f1f5f9;
            padding: 0.4rem 0.45rem !important;
            line-height: 1.35 !important;
        }
        table input, table select, table textarea {
            font-size: 0.8125rem !important; /* ~13px */
            font-weight: 600 !important;
            padding: 0.25rem 0.4rem !important;
            line-height: 1.25 !important;
        }

        /* ── Sticky Arched Tabs Container ─────────────────── */
        .tab-nav-container {
            position: sticky;
            top: 72px;
            z-index: 35;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .subtab-btn {
            position: relative;
            white-space: nowrap;
            transition: all 0.2s ease;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            padding: 0.75rem 1.25rem !important;
            border-top-left-radius: 0.85rem !important;
            border-top-right-radius: 0.85rem !important;
            border-top: 2px solid transparent !important;
            border-left: 2px solid transparent !important;
            border-right: 2px solid transparent !important;
            border-bottom: 2px solid transparent !important;
            outline: none !important;
            color: #cbd5e1 !important;
            background: transparent !important;
            gap: 0.5rem !important;
        }

        .subtab-btn:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.06) !important;
        }

        .subtab-btn.active {
            color: #60a5fa !important;
            border-top-color: #60a5fa !important;
            border-left-color: #60a5fa !important;
            border-right-color: #60a5fa !important;
            border-bottom-color: transparent !important;
            background-color: rgba(59, 130, 246, 0.14) !important;
            box-shadow: 0 -4px 12px rgba(59, 130, 246, 0.25) !important;
            font-weight: 800 !important;
        }

        .subtab-btn .material-symbols-rounded {
            font-size: 1.35rem !important;
        }

        /* ── Cards & Panels ───────────────────────────────── */
        .glass-panel {
            background: rgba(2, 6, 23, 0.94);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
        }

        /* ── Attendance Override Input Styling ────────────── */
        .cell-att-override {
            background-color: #020617 !important;
            border: 1.5px solid #475569 !important;
            color: #f8fafc !important;
            border-radius: 6px !important;
            transition: all 0.2s ease;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            padding: 0.25rem 0.35rem !important;
        }
        .cell-att-override:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
            outline: none !important;
        }
        .cell-att-override.has-override {
            border-color: #f59e0b !important;
            background-color: rgba(245, 158, 11, 0.18) !important;
            color: #fbbf24 !important;
            font-weight: 800 !important;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.35) !important;
        }

        /* Hide number spin arrows */
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

        /* Custom Scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Tactile click feel */
        .exp-btn-touch {
            transition: transform 0.1s ease, filter 0.15s ease, box-shadow 0.15s ease;
            user-select: none;
        }
        .exp-btn-touch:hover { filter: brightness(1.15); }
        .exp-btn-touch:active { transform: scale(0.96); filter: brightness(0.9); }

        /* Preclass Hub font enlargement scoped to practical virtual lab */
        #tab-content-preclass_hub h3, #tab-content-preclass_hub h4 {
            font-size: 1.15rem !important;
        }
        #tab-content-preclass_hub p {
            font-size: 0.95rem !important;
        }
        #tab-content-preclass_hub label {
            font-size: 0.9rem !important;
            font-weight: 700 !important;
        }
        #tab-content-preclass_hub input, #tab-content-preclass_hub select, #tab-content-preclass_hub textarea {
            font-size: 0.95rem !important;
        }
        #tab-content-preclass_hub table th {
            font-size: 0.85rem !important;
        }
        #tab-content-preclass_hub table td {
            font-size: 0.95rem !important;
        }

        /* Hide classes for tabs */
        .tab-pane-content { display: block; }
        .tab-pane-content.hidden { display: none !important; }
        .inner-subtab-pane { display: block; }
        .inner-subtab-pane.hidden { display: none !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">

    <!-- ═══ 1. TOP APP HEADER ═════════════════════════════════════════════ -->
    <header id="vl-header" class="bg-slate-950 border-b border-slate-800 sticky top-0 z-40 shadow-xl">
        <!-- Row 1: Brand Logo · Title · Batch ID · Faculty · Return Action -->
        <div class="flex items-center justify-between gap-4 px-4 md:px-6 py-3 min-h-[76px]">
            <!-- Left: Logo & Subject Info -->
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ $dashboardUrl ?? '/dashboard/lecturer' }}" class="shrink-0 group" title="Carmel Linx Dashboard">
                    <img src="/logo.jpg" alt="Carmel Linx" class="h-12 w-12 rounded-xl object-contain bg-white/10 p-1 border border-slate-700 shadow-md group-hover:border-blue-400 transition">
                </a>

                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap mb-1">
                        <span class="px-3 py-1 rounded-md bg-blue-600 text-white font-black text-xs uppercase tracking-wider flex items-center gap-1.5 shrink-0 shadow-sm">
                            <i class="fa-solid fa-flask-vial text-xs"></i> VIRTUAL LAB · {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}
                        </span>
                        <span class="px-3 py-1 rounded-md font-mono text-sm font-black text-sky-400 bg-sky-500/15 border border-sky-500/30 shrink-0">
                            {{ $batchSubject->subject_code }}
                        </span>
                        <span class="px-3 py-1 rounded-md font-mono text-sm font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 shrink-0">
                            Batch ID: {{ $batchSubject->classroom_id }}
                        </span>
                    </div>

                    <h1 class="text-lg md:text-xl lg:text-2xl font-extrabold text-white truncate m-0 font-heading tracking-tight leading-tight">
                        {{ $practicalCourseFile?->course_title ?: $batchSubject->subject_name }}
                    </h1>
                </div>
            </div>

            <!-- Right: Faculty Name, Fullscreen, Return Button -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-sm text-slate-200 shadow-sm">
                    <i class="fa-solid fa-chalkboard-user text-blue-400 text-base"></i>
                    <span class="text-slate-300 font-medium">Faculty:</span>
                    <strong class="text-white font-bold">{{ $staff?->name ?? 'Faculty' }}</strong>
                </div>

                <button onclick="toggleFullscreen()" id="fullscreen-btn" class="exp-btn-touch px-3.5 py-2.5 rounded-xl bg-cyan-500/15 border border-cyan-500/40 text-cyan-300 text-sm font-bold flex items-center gap-2 cursor-pointer shadow-sm" title="Toggle Fullscreen">
                    <i class="fa-solid fa-expand text-sm"></i>
                    <span class="hidden md:inline">Fullscreen</span>
                </button>

                <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="exp-btn-touch px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm md:text-base font-black flex items-center gap-2 no-underline shadow-md cursor-pointer" title="Return to Staff Dashboard">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>

        <!-- Row 2: Subheader Bar (Branch - Batch Code, Sem) -->
        <div class="flex items-center gap-3 px-4 md:px-6 py-2.5 bg-slate-950/90 border-t border-slate-800 text-sm overflow-x-auto scrollbar-none">
            <span class="px-3.5 py-1.5 rounded-lg bg-blue-500/15 border border-blue-500/35 text-blue-300 font-bold flex items-center gap-2 shrink-0 text-sm shadow-sm">
                <i class="fa-solid fa-graduation-cap text-blue-400 text-base"></i>
                <span>{{ $classroom?->branch ?? 'Branch' }} - {{ $batchSubject->classroom_id }}, Sem {{ $batchSubject->semester }}</span>
            </span>

            <span class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 flex items-center gap-2 shrink-0 text-sm">
                <i class="fa-solid fa-calendar text-slate-400"></i>
                <span class="text-slate-300">Batch Year:</span>
                <strong class="text-white font-bold">{{ $classroom?->batch_year ?? '2026' }}</strong>
            </span>

            @if(isset($assignedStaffList) && count($assignedStaffList) > 0)
                @foreach($assignedStaffList as $st)
                <span class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 shrink-0 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-user-check text-emerald-400 text-xs"></i>
                    <span class="text-slate-300">{{ $st['designation'] }}:</span>
                    <strong class="text-white font-bold">{{ $st['name'] }}</strong>
                </span>
                @endforeach
            @endif

            <span class="ml-auto px-3.5 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-300 font-bold shrink-0 flex items-center gap-2 text-sm shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_8px_#f59e0b]"></span>
                <span class="text-amber-200">Parse:</span>
                <strong class="text-amber-100 font-bold">{{ $parseModeLabel }}</strong>
            </span>
        </div>
    </header>

    <!-- ═══ 2. MAIN WORKSPACE CONTAINER ═══════════════════════════════════ -->
    <main class="max-w-[99%] mx-auto px-3 md:px-6 mt-4 pb-12">

        <!-- ═══ 3. MAIN ARCHED TABBED NAVIGATION (STANDARDIZED) ═══════════ -->
        <div class="tab-nav-container bg-slate-950/95 border border-slate-800 p-2.5 rounded-2xl shadow-xl mb-5">
            <nav class="flex flex-wrap md:flex-nowrap items-center gap-2 overflow-x-auto scrollbar-none no-scrollbar">

                <!-- 1. Course Outline -->
                <button onclick="switchTab('outline')" id="tab-btn-outline" class="subtab-btn active px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">menu_book</span>
                    <span>Course Outline</span>
                </button>

                <!-- 2. Lesson Planner -->
                <button onclick="switchTab('lesson_plan')" id="tab-btn-lesson_plan" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">calendar_month</span>
                    <span>Lesson Planner</span>
                </button>

                <!-- 3. Continuous Evaluation (CE) -->
                <button onclick="switchTab('ce')" id="tab-btn-ce" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">assignment_turned_in</span>
                    <span>Continuous Evaluation (CE)</span>
                </button>

                <!-- 4. Series Test -->
                <button onclick="switchTab('series_test')" id="tab-btn-series_test" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">edit_note</span>
                    <span>Series Test</span>
                </button>

                <!-- 5. CIA Register -->
                <button onclick="switchTab('cia_register')" id="tab-btn-cia_register" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">fact_check</span>
                    <span>CIA Register</span>
                </button>

                <!-- 6. Attainment Table -->
                <button onclick="switchTab('attainment')" id="tab-btn-attainment" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">analytics</span>
                    <span>Attainment Table</span>
                </button>

                <!-- 7. Reports -->
                <button onclick="switchTab('reports')" id="tab-btn-reports" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">print</span>
                    <span>Reports</span>
                </button>

                <!-- 8. Preclass Hub -->
                <button onclick="switchTab('preclass_hub')" id="tab-btn-preclass_hub" class="subtab-btn px-4 md:px-5 py-2.5 md:py-3 text-sm md:text-base font-bold text-slate-300 hover:text-white border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-lg md:text-xl">hub</span>
                    <span>Preclass Hub</span>
                </button>

            </nav>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 1: COURSE OUTLINE                                                     -->
        <!-- ========================================================================= -->
        <div id="tab-content-outline" class="tab-pane-content space-y-5">
            <div class="glass-panel p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black flex items-center gap-2.5 bg-gradient-to-r from-sky-400 to-indigo-400 bg-clip-text text-transparent font-heading">
                            <i class="fa-solid fa-file-pdf text-sky-400"></i> Syllabus &amp; Course Articulation
                        </h2>
                        <p class="text-sm text-slate-300 mt-1.5 font-medium">Upload the practical syllabus PDF to automatically map Course Outcomes, Credits, Schemes, and Experiments.</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @if($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path)
                            <a href="/storage/{{ $practicalCourseFile->syllabus_pdf_path }}" target="_blank" class="exp-btn-touch px-4 py-2.5 rounded-xl bg-rose-500/20 border border-rose-500/50 text-rose-300 text-sm font-bold flex items-center gap-2 no-underline" title="View Syllabus PDF">
                                <i class="fa-solid fa-file-pdf text-rose-400 text-base"></i> View PDF
                            </a>
                        @endif
                        <input type="file" id="syllabus_pdf_input" accept=".pdf" class="hidden" onchange="uploadSyllabusPdf()">
                        <button id="syllabus-upload-btn" onclick="document.getElementById('syllabus_pdf_input').click()" class="exp-btn-touch px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white text-sm font-black flex items-center gap-2 cursor-pointer shadow-lg">
                            <i class="fa-solid fa-upload text-sm"></i> Upload Syllabus PDF
                        </button>
                    </div>
                </div>

                {{-- Upload Status Bar --}}
                <div id="syllabus-upload-status" class="hidden mt-4 p-3.5 rounded-xl text-sm font-semibold flex items-center gap-3"></div>

                {{-- Uploaded File Banner --}}
                @if($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path)
                <div class="mt-4 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3 text-sm">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                    <span class="text-emerald-400 font-black">Syllabus Loaded:</span>
                    <strong class="text-white text-base">{{ $practicalCourseFile->course_title ?: $batchSubject->subject_name }}</strong>
                </div>
                @endif

                <!-- Scheme parameters -->
                @php
                    $copoDecoded = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_copo, true) : [];
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3.5 mt-6">
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700">
                        <span class="text-xs text-slate-300 font-extrabold uppercase tracking-wider block">Credits</span>
                        <span class="text-xl md:text-2xl font-black text-sky-400 mt-1 block font-mono" id="meta-credits">{{ $copoDecoded['credit'] ?? '1' }}</span>
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700">
                        <span class="text-xs text-slate-300 font-extrabold uppercase tracking-wider block">Proposed Hours</span>
                        <span class="text-xl md:text-2xl font-black text-amber-400 mt-1 block font-mono" id="meta-hours">{{ $copoDecoded['total_hours'] ?? '30' }} hrs</span>
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700">
                        <span class="text-xs text-slate-300 font-extrabold uppercase tracking-wider block">L : T : P : R</span>
                        <span class="text-xl md:text-2xl font-black text-slate-100 mt-1 block font-mono" id="meta-ltpr">{{ $copoDecoded['l_t_p_r'] ?? '0:0:2:0' }}</span>
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700">
                        <span class="text-xs text-slate-300 font-extrabold uppercase tracking-wider block">CIE Marks</span>
                        <span class="text-xl md:text-2xl font-black text-emerald-400 mt-1 block font-mono" id="meta-cie">{{ $copoDecoded['cie_marks'] ?? '60' }} M</span>
                    </div>
                    <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-700">
                        <span class="text-xs text-slate-300 font-extrabold uppercase tracking-wider block">ESE Marks</span>
                        <span class="text-xl md:text-2xl font-black text-purple-400 mt-1 block font-mono" id="meta-ese">{{ $copoDecoded['ese_marks'] ?? '40' }} M</span>
                    </div>
                </div>

                <!-- Course Outcomes list -->
                <div class="mt-8">
                    <h3 class="text-sm md:text-base font-black text-slate-200 uppercase tracking-wider mb-3.5 flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-sky-400"></i> Course Outcomes (COs)
                    </h3>
                    <div class="space-y-3" id="co-outcomes-container">
                        @php
                            $cosList = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_cos, true) : [];
                        @endphp
                        @forelse($cosList as $co)
                        <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 flex gap-4 items-start">
                            <span class="px-3 py-1.5 bg-slate-800 text-sky-300 font-black font-mono text-sm rounded-lg border border-slate-700 shrink-0">{{ $co['id'] }}</span>
                            <div class="space-y-1 min-w-0">
                                <h4 class="text-sm md:text-base font-semibold text-slate-100 leading-relaxed">{{ $co['description'] }}</h4>
                                <span class="text-xs md:text-sm text-slate-400 font-mono block">Cognitive Level: <strong class="text-purple-300 font-bold">{{ $co['cognitive_level'] }}</strong></span>
                            </div>
                        </div>
                        @empty
                        <div class="text-sm text-slate-400 italic p-4 bg-slate-900/40 rounded-xl border border-slate-800">Syllabus outcomes not uploaded yet. Upload syllabus PDF to parse Course Outcomes.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Articulation matrix (CO-PO Mapping) -->
                <div class="mt-8 pt-6 border-t border-slate-800">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                        <h3 class="text-sm md:text-base font-black text-slate-200 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-table text-indigo-400"></i>
                            CO-PO &amp; CO-PSO Articulation Matrix
                            <span class="text-xs font-normal text-slate-400 hidden sm:inline">(1 = Low &nbsp;·&nbsp; 2 = Medium &nbsp;·&nbsp; 3 = High &nbsp;·&nbsp; - = No mapping)</span>
                        </h3>
                        <button onclick="savePracticalCoPoMatrixBtn()" id="savePracticalCoPoBtn" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm transition flex items-center gap-2 shadow-md cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-sm"></i> Save Matrix
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-slate-700">
                        <table class="w-full text-center text-xs md:text-sm text-slate-200 border-collapse">
                            <thead>
                                <tr class="bg-slate-950 font-bold text-slate-200 uppercase tracking-wider text-[11px] md:text-xs">
                                    <th class="py-2 px-2 border-b border-r border-slate-700 text-left w-14">CO</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="py-1.5 px-1 border-b border-r border-slate-700 text-center text-indigo-300 w-10 font-bold text-[11px]">PO{{ $p }}</th>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                    <th class="py-1.5 px-1 border-b border-r border-slate-700 text-center text-sky-300 w-10 font-bold text-[11px]">PSO{{ $s }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody id="practicalCoPoMatrixTbody">
                                @php
                                    $mappings = $copoDecoded['mappings'] ?? [];
                                @endphp
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                <tr class="hover:bg-slate-900/40 transition">
                                    <td class="py-1.5 px-2 border-b border-r border-slate-700 text-left font-bold font-mono text-amber-300 text-xs md:text-sm">{{ $coId }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                    @php
                                        $poVal = $mappings[$coId]["PO$p"] ?? '';
                                        if ($poVal === '-') $poVal = '';
                                    @endphp
                                    <td class="p-1 border-b border-r border-slate-700 text-center">
                                        <input type="text" maxlength="1" value="{{ $poVal }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-7 h-7 md:w-8 md:h-8 bg-slate-950 border border-slate-700 rounded-lg text-center font-bold text-emerald-400 focus:border-blue-500 outline-none text-xs md:text-sm" data-co="{{ $coId }}" data-target="PO{{ $p }}">
                                    </td>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                    @php
                                        $psoVal = $mappings[$coId]["PSO$s"] ?? '';
                                        if ($psoVal === '-') $psoVal = '';
                                    @endphp
                                    <td class="p-1 border-b border-r border-slate-700 text-center">
                                        <input type="text" maxlength="1" value="{{ $psoVal }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-7 h-7 md:w-8 md:h-8 bg-slate-950 border border-slate-700 rounded-lg text-center font-bold text-sky-300 focus:border-blue-500 outline-none text-xs md:text-sm" data-co="{{ $coId }}" data-target="PSO{{ $s }}">
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Course Experiments Inventory -->
                <div class="mt-9 pt-6 border-t border-slate-800">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-base md:text-lg font-black text-slate-100 uppercase tracking-wider flex items-center gap-2.5">
                                <i class="fa-solid fa-list-check text-sky-400"></i> Course Experiments Inventory
                                @if($practicalCourseFile && count($practicalCourseFile->getExperimentsArray()) > 0)
                                    <span class="exp-count-badge px-2.5 py-0.5 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 rounded-lg text-xs font-mono font-bold">
                                        {{ count($practicalCourseFile->getExperimentsArray()) }} experiments
                                    </span>
                                @endif
                            </h3>
                            <p class="text-xs md:text-sm text-slate-300 mt-1">Edit experiment titles and CO mappings. These synchronize with Daywork Log and Lesson Planner.</p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap shrink-0">
                            <button type="button" onclick="loadFromPdfExperiments()" class="exp-btn-touch px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold cursor-pointer flex items-center gap-1.5" title="Reload from PDF">
                                <i class="fa-solid fa-file-import text-xs text-sky-400"></i> Load from Syllabus
                            </button>
                            <button type="button" onclick="addNewExperimentRow()" class="exp-btn-touch px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold cursor-pointer flex items-center gap-1.5" title="Add Row">
                                <i class="fa-solid fa-plus text-xs text-emerald-400"></i> Add Expt
                            </button>
                            <button type="button" onclick="saveExperimentsInventory()" id="save-experiments-btn" class="exp-btn-touch px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black cursor-pointer flex items-center gap-1.5 shadow-md">
                                <i class="fa-solid fa-floppy-disk text-xs"></i> Save List
                            </button>
                        </div>
                    </div>

                    <div class="exp-pdf-status hidden"></div>

                    <div class="overflow-x-auto rounded-xl border border-slate-700 mt-3.5">
                        <table class="w-full text-left text-xs md:text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-slate-200 font-bold uppercase text-[11px] md:text-xs border-b border-slate-700">
                                    <th class="py-2 px-2.5 w-24">Expt No</th>
                                    <th class="py-2 px-2.5">Experiment Title</th>
                                    <th class="py-2 px-2.5">Description / Outcomes</th>
                                    <th class="py-2 px-2.5 w-24 text-center">CO</th>
                                    <th class="py-2 px-2.5 w-20 text-center">Hours</th>
                                    <th class="py-2 px-2.5 w-14 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="experiments-rows-container-outline" class="experiments-table-body">
                                @php
                                    $expList = $practicalCourseFile ? $practicalCourseFile->getExperimentsArray() : [];
                                @endphp
                                @forelse($expList as $index => $exp)
                                <tr class="border-b border-slate-800 hover:bg-slate-900/40 transition">
                                    <td class="p-2 text-center">
                                        <input type="text" class="exp-no w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-100 font-mono text-xs md:text-sm font-bold text-center" value="{{ $exp['expt_no'] ?? ('Expt ' . ($index + 1)) }}">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" class="exp-title w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-100 text-xs md:text-sm font-medium" value="{{ $exp['title'] ?? '' }}" placeholder="Experiment title...">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" class="exp-desc w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-300 text-xs md:text-sm" value="{{ $exp['description'] ?? '' }}" placeholder="Details...">
                                    </td>
                                    <td class="p-2 text-center">
                                        <select class="exp-co w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sky-400 font-bold text-xs md:text-sm text-center">
                                            @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $coOption)
                                            <option value="{{ $coOption }}" {{ ($exp['co'] ?? '') == $coOption ? 'selected' : '' }}>{{ $coOption }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2 text-center">
                                        <input type="number" min="1" max="6" class="exp-hours w-16 bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-center text-slate-100 text-xs md:text-sm font-mono font-bold" value="{{ $exp['hours'] ?? 2 }}">
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" onclick="removeExperimentRow(this)" class="w-7 h-7 rounded-lg bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 inline-flex items-center justify-center transition cursor-pointer text-xs" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="exp-empty-row" class="exp-empty-placeholder">
                                    <td colspan="6" class="p-6 text-center text-slate-400 italic text-xs md:text-sm">
                                        No experiments registered yet. Click <strong class="text-sky-400">Load from Syllabus</strong> or <strong class="text-emerald-400">Add Expt</strong> to populate the inventory.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 2: LESSON PLANNER                                                     -->
        <!-- ========================================================================= -->
        <div id="tab-content-lesson_plan" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black flex items-center gap-2.5 text-white font-heading">
                            <span class="material-symbols-rounded text-sky-400 text-2xl">calendar_month</span>
                            <span>Laboratory Lesson Planner</span>
                            <span class="px-3 py-1 rounded-lg bg-amber-500/15 border border-amber-500/30 text-amber-300 text-sm font-mono font-bold">
                                Target: {{ $copoDecoded['total_hours'] ?? 30 }} Hours
                            </span>
                        </h2>
                        <p class="text-sm text-slate-300 mt-1.5 font-medium">Manage proposed sessions, actual completion dates, and pedagogy. Auto-saves changes silently in the background.</p>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                        <div class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5">
                            <span class="text-xs uppercase font-extrabold text-slate-300">Gen:</span>
                            <select id="lesson_planner_mode" class="bg-transparent text-sm text-slate-100 outline-none font-bold">
                                <option value="smart">Smart Local Match</option>
                                <option value="balanced">Equal Distribution</option>
                            </select>
                            <select id="lesson_planner_batch" class="bg-transparent text-sm text-sky-400 font-black outline-none">
                                <option value="Full Batch">Full Batch</option>
                                <option value="Batch 1">Batch 1</option>
                                <option value="Batch 2">Batch 2</option>
                            </select>
                            <button onclick="generateLessonTimeline()" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm cursor-pointer shadow">
                                Generate
                            </button>
                        </div>

                        <button onclick="saveLessonPlannerBulk()" id="btnSavePlan" class="exp-btn-touch px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base font-black flex items-center gap-2 cursor-pointer shadow-lg">
                            <i class="fa-solid fa-floppy-disk text-sm"></i> Save Plan
                        </button>
                        <span id="lp-autosave-indicator" class="hidden"></span>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-700 mt-4">
                    <table class="w-full text-left text-xs md:text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-slate-200 font-bold uppercase text-[11px] md:text-xs border-b border-slate-700">
                                <th class="py-2 px-2 w-12 text-center">No</th>
                                <th class="py-2 px-2 w-28 text-center">Batch</th>
                                <th class="py-2 px-2 w-24 text-center">CO</th>
                                <th class="py-2 px-2 w-20 text-center">Hours</th>
                                <th class="py-2 px-2">Topic / Experiment Description</th>
                                <th class="py-2 px-2 w-36">Proposed Date</th>
                                <th class="py-2 px-2 w-36">Actual Date</th>
                                <th class="py-2 px-2 w-32">Pedagogy</th>
                                <th class="py-2 px-2 w-28 text-center">Status</th>
                                <th class="py-2 px-2 w-20 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="lesson-plan-rows-container">
                            @forelse($lessonPlans as $index => $plan)
                            @php
                                $sb = $plan->sub_batch ?? 'Full Batch';
                                $batchLabel = in_array($sb, ['Batch 1', 'Batch A', 'A']) ? 'Batch 1' : (in_array($sb, ['Batch 2', 'Batch B', 'B']) ? 'Batch 2' : 'Full Batch');
                            @endphp
                            <tr class="lesson-plan-row border-b border-slate-800 hover:bg-slate-900/40 transition" 
                                data-id="{{ $plan->id }}" 
                                data-sub-batch="{{ $batchLabel }}">
                                <td class="py-2 px-1.5 text-center font-mono font-bold text-slate-200 text-xs md:text-sm lp-day-no-cell">
                                    {{ $plan->day_no ?? ($index + 1) }}
                                </td>
                                <td class="p-1.5 text-center">
                                    <select class="lp-sub-batch bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sky-400 font-bold focus:outline-none w-full text-xs md:text-sm" onchange="triggerLpAutoSave()">
                                        <option value="Full Batch" {{ $batchLabel == 'Full Batch' ? 'selected' : '' }}>Full Batch</option>
                                        <option value="Batch 1" {{ $batchLabel == 'Batch 1' ? 'selected' : '' }}>Batch 1</option>
                                        <option value="Batch 2" {{ $batchLabel == 'Batch 2' ? 'selected' : '' }}>Batch 2</option>
                                    </select>
                                </td>
                                <td class="p-1.5 text-center">
                                    <select class="lp-co bg-slate-950 border border-slate-800 rounded-lg px-1.5 py-1 text-amber-300 font-bold focus:outline-none w-full text-xs md:text-sm text-center" onchange="triggerLpAutoSave()">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'] as $co)
                                        <option value="{{ $co }}" {{ ($plan->co_id ?? '') == $co ? 'selected' : '' }}>{{ $co }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-1.5 text-center">
                                    <input type="number" min="1" max="6" value="{{ $plan->allocated_hours ?? 1 }}" class="lp-hours w-16 bg-slate-950 border border-slate-800 rounded-lg px-1.5 py-1 text-slate-100 text-center text-xs md:text-sm font-mono font-bold" onchange="triggerLpAutoSave()">
                                </td>
                                <td class="p-1.5">
                                    <textarea rows="1" placeholder="Enter topic content..." class="lp-topic w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-100 text-xs md:text-sm font-medium focus:border-blue-500 outline-none resize-none leading-relaxed overflow-hidden" oninput="autoGrowTextarea(this); triggerLpAutoSave()">{{ $plan->topic_content }}</textarea>
                                </td>
                                <td class="p-1.5">
                                    <input type="date" value="{{ $plan->proposed_date }}" class="lp-proposed w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-200 text-xs md:text-sm font-mono font-medium" onchange="triggerLpAutoSave()">
                                </td>
                                <td class="p-1.5">
                                    <input type="date" value="{{ $plan->actual_date }}" class="lp-actual w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-emerald-400 text-xs md:text-sm font-mono font-bold" onchange="onActualDateChange(this)">
                                </td>
                                <td class="p-1.5">
                                    <select class="lp-pedagogy w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-200 text-xs md:text-sm font-medium" onchange="triggerLpAutoSave()">
                                        <option value="Practical" {{ ($plan->pedagogy ?? '') == 'Practical' ? 'selected' : '' }}>Practical</option>
                                        <option value="Demonstration" {{ ($plan->pedagogy ?? '') == 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                        <option value="Simulation" {{ ($plan->pedagogy ?? '') == 'Simulation' ? 'selected' : '' }}>Simulation</option>
                                        <option value="Lecture" {{ ($plan->pedagogy ?? '') == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                        <option value="Exam" {{ ($plan->pedagogy ?? '') == 'Exam' ? 'selected' : '' }}>Exam</option>
                                    </select>
                                </td>
                                <td class="p-1.5 text-center">
                                    <select class="lp-status w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs md:text-sm font-bold {{ ($plan->status ?? 'Pending') == 'Completed' ? 'text-emerald-400' : 'text-amber-400' }}" onchange="triggerLpAutoSave()">
                                        <option value="Pending" class="text-amber-400" {{ ($plan->status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" class="text-emerald-400" {{ ($plan->status ?? 'Pending') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                                <td class="p-1.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" onclick="addR26LessonPlanRow(this)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-sky-500/10 hover:bg-sky-500/25 border border-sky-500/30 text-sky-400 hover:text-sky-300 transition text-xs font-bold leading-none cursor-pointer" title="Insert row below">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                        </button>
                                        <button type="button" onclick="deleteR26LessonPlanRow(this, '{{ $plan->id }}')" class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 hover:text-rose-300 transition text-xs font-bold leading-none cursor-pointer" title="Delete row">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="lp-empty-row">
                                <td colspan="10" class="p-8 text-center text-slate-500 italic">
                                    No lesson plan generated yet. Upload a syllabus or click <strong class="text-blue-400">Generate</strong>.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 3: CONTINUOUS EVALUATION (CE)                                         -->
        <!-- ========================================================================= -->
        <div id="tab-content-ce" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6">
                <!-- Top Header & Subtabs -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5 font-heading">
                            <span class="material-symbols-rounded text-emerald-400 text-2xl">assignment_turned_in</span>
                            <span>Continuous Evaluation (CE) — 40 Marks Total</span>
                        </h2>
                        <p class="text-sm md:text-base text-slate-300 mt-1.5 font-medium">
                            Assesses day-to-day laboratory work (Daywork Log out of 50 $\to$ scaled to 30M) and Open-Ended Experiments (out of 50 $\to$ scaled to 10M).
                        </p>
                    </div>

                    <!-- Lab Batch Filter Pills -->
                    <div class="flex items-center gap-2 p-1.5 bg-slate-950 rounded-xl border border-slate-700 shrink-0">
                        <span class="text-xs uppercase font-extrabold text-slate-300 px-2.5">Batch:</span>
                        <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn px-3.5 py-1.5 rounded-lg text-sm font-black bg-blue-600 text-white cursor-pointer transition">All</button>
                        <button onclick="filterLabBatch('Batch 1')" id="batch-filter-1" class="batch-filter-btn px-3.5 py-1.5 rounded-lg text-sm font-bold text-slate-300 hover:text-white cursor-pointer transition">Batch 1</button>
                        <button onclick="filterLabBatch('Batch 2')" id="batch-filter-2" class="batch-filter-btn px-3.5 py-1.5 rounded-lg text-sm font-bold text-slate-300 hover:text-white cursor-pointer transition">Batch 2</button>
                    </div>
                </div>

                <!-- Laboratory Experiments Inventory Card (Load from Syllabus / Add Row / Save) -->
                <div class="p-5 rounded-2xl border border-slate-700 bg-slate-950/80 mb-5 mt-4 shadow-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center font-bold text-base">
                                <i class="fa-solid fa-list-check"></i>
                            </span>
                            <div>
                                <h3 class="text-base md:text-lg font-black text-white flex items-center gap-2.5">
                                    <span>Laboratory Experiments List</span>
                                    <span id="exp-count-badge" class="exp-count-badge px-2.5 py-0.5 bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 rounded-lg text-xs font-mono font-bold">
                                        {{ count($practicalCourseFile?->getExperimentsArray() ?? []) }} experiments
                                    </span>
                                </h3>
                                <p class="text-xs md:text-sm text-slate-300">Load experiments from uploaded syllabus or add experiments for continuous daywork evaluation.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <button type="button" onclick="loadFromPdfExperiments()" class="exp-btn-touch px-3 py-1.5 rounded-xl bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/40 text-blue-300 text-xs font-bold flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-file-import text-xs"></i> Load from Syllabus
                            </button>
                            <button type="button" onclick="addNewExperimentRow()" class="exp-btn-touch px-3 py-1.5 rounded-xl bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 text-xs font-bold flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-plus text-xs"></i> Add Experiment
                            </button>
                            <button type="button" onclick="saveExperimentsInventory()" class="exp-btn-touch px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black flex items-center gap-1.5 cursor-pointer shadow-md">
                                <i class="fa-solid fa-floppy-disk text-xs"></i> Save List
                            </button>
                        </div>
                    </div>

                    <!-- Scrollable table of experiments -->
                    <div class="mt-3.5 overflow-x-auto max-h-60 rounded-xl border border-slate-700">
                        <table class="w-full text-left text-xs md:text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-900 text-slate-200 font-bold uppercase text-[11px] md:text-xs">
                                    <th class="py-2 px-2.5 w-24">Exp No</th>
                                    <th class="py-2 px-2.5 w-72">Title</th>
                                    <th class="py-2 px-2.5">Description / Scope</th>
                                    <th class="py-2 px-2.5 w-24 text-center">CO</th>
                                    <th class="py-2 px-2.5 w-20 text-center">Hours</th>
                                    <th class="py-2 px-2.5 w-14 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="experiments-rows-container" class="experiments-table-body">
                                <!-- Populated dynamically by loadExperimentsInventoryTable() -->
                            </tbody>
                        </table>
                    </div>
                    <div id="exp-pdf-status" class="exp-pdf-status hidden mt-2 text-xs font-semibold"></div>
                </div>

                <!-- Subtab Switcher (Daywork Log vs Open Ended) -->
                <div class="flex items-center gap-2.5 mt-5 border-b border-slate-800 pb-2.5">
                    <button onclick="switchCeSubtab('daywork')" id="ce-subtab-btn-daywork" class="px-4 md:px-5 py-2 md:py-2.5 rounded-xl text-sm md:text-base font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30 flex items-center gap-2 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-flask text-sm"></i> 1. Daywork Log (Table 2.2 · 30M)
                    </button>
                    <button onclick="switchCeSubtab('open_ended')" id="ce-subtab-btn-open_ended" class="px-4 md:px-5 py-2 md:py-2.5 rounded-xl text-sm md:text-base font-bold text-slate-300 hover:text-white border border-transparent flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-lightbulb text-sm text-amber-400"></i> 2. Open-Ended Experiments (Table 2.3 · 10M)
                    </button>
                </div>

                <!-- SUB-PANE 1: DAYWORK LOG -->
                <div id="ce-subpane-daywork" class="inner-subtab-pane space-y-4 mt-5">
                    <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-slate-950/70 rounded-2xl border border-slate-700">
                        <div class="flex flex-wrap items-center gap-3.5">
                            <div>
                                <label class="block text-xs uppercase font-extrabold text-slate-300 tracking-wider mb-1">Select Experiment</label>
                                <select id="exp_select" onchange="switchActiveExperiment(this.value)" class="px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm md:text-base text-white font-mono font-bold outline-none focus:border-blue-500">
                                    <!-- Dynamic options -->
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-extrabold text-slate-300 tracking-wider mb-1">Experiment No</label>
                                <input type="text" id="exp_no" value="Exp 1" oninput="onManualExpNoChange()" class="px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm md:text-base text-white font-mono font-bold w-28 outline-none focus:border-blue-500 text-center">
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-extrabold text-slate-300 tracking-wider mb-1">Experiment Title</label>
                                <input type="text" id="exp_title" placeholder="e.g. Verification of Ohm's Law" class="px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm md:text-base text-white w-56 sm:w-80 outline-none focus:border-blue-500 font-medium">
                            </div>
                        </div>
                        <button onclick="submitExpMarks()" class="exp-btn-touch px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-black text-sm md:text-base rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-sm"></i> Save Daywork Log
                        </button>
                    </div>

                    <!-- Students List -->
                    <div class="space-y-3 max-h-[64vh] overflow-y-auto pr-1.5">
                        @foreach($students as $index => $student)
                        @php
                            $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                            $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch 1' : 'Batch 2');
                            $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                        @endphp
                        <div class="student-row p-4 rounded-2xl bg-slate-950/70 border border-slate-800 hover:border-slate-700 transition" 
                             data-reg-no="{{ $student->reg_no }}" 
                             data-batch="{{ $batchVal }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
                                <div class="flex items-center gap-3.5">
                                    <span class="w-10 h-10 rounded-xl bg-blue-500/15 text-blue-400 font-black text-sm md:text-base flex items-center justify-center border border-blue-500/30">
                                        {{ $student->roll_no ?? ($index + 1) }}
                                    </span>
                                    <div>
                                        <h4 class="text-base md:text-lg font-bold text-white">{{ $student->name }}</h4>
                                        <span class="text-xs md:text-sm font-mono font-semibold text-slate-400">{{ $student->reg_no }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3.5 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-extrabold uppercase text-slate-400">Batch:</span>
                                        <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="student-batch-select-{{ $student->reg_no }} bg-slate-900 border border-slate-700 text-sm font-bold rounded-lg py-1.5 px-2.5 text-slate-200 focus:outline-none">
                                            <option value="Batch 1" {{ in_array($batchVal, ['Batch 1', 'Batch A', 'A']) ? 'selected' : '' }}>Batch 1</option>
                                            <option value="Batch 2" {{ in_array($batchVal, ['Batch 2', 'Batch B', 'B']) ? 'selected' : '' }}>Batch 2</option>
                                        </select>
                                    </div>

                                    <button onclick="openGradingModal('{{ $student->reg_no }}', 'table22')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-black rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                                        <i class="fa-solid fa-sliders text-sm"></i> Evaluate
                                    </button>

                                    <div class="text-right min-w-[90px]">
                                        <span class="text-[11px] uppercase font-extrabold text-slate-400 block tracking-wider">Score</span>
                                        <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-sm md:text-base font-black text-emerald-400">
                                            {{ $expLog ? floatval($expLog->total_score_50) : '0.00' }} / 50
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- SUB-PANE 2: OPEN-ENDED EXPERIMENTS (OEE) -->
                <div id="ce-subpane-open_ended" class="inner-subtab-pane hidden space-y-4 mt-5">
                    <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-slate-950/70 rounded-2xl border border-slate-700">
                        <div>
                            <h4 class="text-base md:text-lg font-black text-amber-300 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-lightbulb text-amber-400"></i> Open-Ended Experiments (10 Marks Scale)
                            </h4>
                            <p class="text-sm text-slate-300 mt-1 font-medium">Assesses student autonomy, problem formulation, and innovative execution out of 50.</p>
                        </div>
                        <button onclick="submitOpenEndedMarks()" class="exp-btn-touch px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-black text-sm md:text-base rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-sm"></i> Save Open-Ended Marks
                        </button>
                    </div>

                    <!-- Students List -->
                    <div class="space-y-3 max-h-[64vh] overflow-y-auto pr-1.5">
                        @foreach($students as $index => $student)
                        @php
                            $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                            $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch 1' : 'Batch 2');
                            $openLog = $openEndedLogs->get($student->reg_no);
                        @endphp
                        <div class="student-row p-4 rounded-2xl bg-slate-950/70 border border-slate-800 hover:border-slate-700 transition" 
                             data-reg-no="{{ $student->reg_no }}" 
                             data-batch="{{ $batchVal }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
                                <div class="flex items-center gap-3.5">
                                    <span class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-400 font-black text-sm md:text-base flex items-center justify-center border border-amber-500/30">
                                        {{ $student->roll_no ?? ($index + 1) }}
                                    </span>
                                    <div>
                                        <h4 class="text-base md:text-lg font-bold text-white">{{ $student->name }}</h4>
                                        <span class="text-xs md:text-sm font-mono font-semibold text-slate-400">{{ $student->reg_no }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3.5 flex-wrap">
                                    <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:outline-none w-52 sm:w-72 font-medium">

                                    <button onclick="openGradingModal('{{ $student->reg_no }}', 'table23')" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-black rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                                        <i class="fa-solid fa-sliders text-sm"></i> Evaluate
                                    </button>

                                    <div class="text-right min-w-[90px]">
                                        <span class="text-[11px] uppercase font-extrabold text-slate-400 block tracking-wider">Score</span>
                                        <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-sm md:text-base font-black text-amber-300">
                                            {{ $openLog ? floatval($openLog->total_score_50) : '0.00' }} / 50
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 4: SERIES TEST                                                        -->
        <!-- ========================================================================= -->
        <div id="tab-content-series_test" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 pb-4 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5 font-heading">
                            <span class="material-symbols-rounded text-purple-400 text-2xl">edit_note</span>
                            <span>Practical Series Test — 15 Marks Total</span>
                        </h2>
                        <p class="text-sm md:text-base text-slate-300 mt-1.5 font-medium">Two series practical examinations out of 40. The average out of 40 is converted to 15 CIA Marks.</p>
                    </div>

                    <!-- Subtab switcher -->
                    <div class="flex items-center gap-2 border border-slate-700 p-1.5 rounded-xl bg-slate-950">
                        <button onclick="switchSeriesSubtab('qp')" id="series-subtab-btn-qp" class="px-4 py-2 rounded-lg text-sm font-black bg-purple-600 text-white cursor-pointer transition flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-question text-sm"></i> 1. QP Setup &amp; Scheme
                        </button>
                        <button onclick="switchSeriesSubtab('marks')" id="series-subtab-btn-marks" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-300 hover:text-white cursor-pointer transition flex items-center gap-2">
                            <i class="fa-solid fa-signature text-sm"></i> 2. Series Mark Entry
                        </button>
                    </div>
                </div>

                <!-- SUB-PANE 1: QP SETUP & SCHEME PREPARATION -->
                <div id="series-subpane-qp" class="inner-subtab-pane space-y-4 mt-5">
                    <div class="flex items-center justify-between p-4 bg-slate-950/70 rounded-2xl border border-slate-700">
                        <div class="flex items-center gap-3.5">
                            <span class="text-sm font-extrabold text-slate-300 uppercase tracking-wider">Select Exam:</span>
                            <select id="series_qp_select" class="px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm md:text-base text-white font-bold outline-none" onchange="loadSeriesQpConfig(this.value)">
                                <option value="Series 1">Practical Series Exam 1</option>
                                <option value="Series 2">Practical Series Exam 2</option>
                            </select>
                        </div>
                        <button onclick="saveSeriesQpOutline()" class="exp-btn-touch px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-black text-sm md:text-base rounded-xl shadow-lg cursor-pointer flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-sm"></i> Save Question Blueprint
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-4 md:col-span-1 bg-slate-950/70 p-5 rounded-2xl border border-slate-700">
                            <div>
                                <label class="block text-xs md:text-sm font-extrabold uppercase text-slate-300 tracking-wider mb-1.5">Max Marks</label>
                                <input type="number" id="series_qp_max_marks" value="40" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-base text-white font-mono font-bold">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-extrabold uppercase text-slate-300 tracking-wider mb-1.5">Duration (minutes)</label>
                                <input type="number" id="series_qp_duration" value="120" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-base text-white font-mono font-bold">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-extrabold uppercase text-slate-300 tracking-wider mb-1.5">Target Course Outcomes</label>
                                <div class="flex flex-wrap gap-2.5 mt-1" id="series_qp_co_checks">
                                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                    <label class="flex items-center gap-2 text-sm font-mono font-bold cursor-pointer select-none bg-slate-900 px-3 py-1.5 rounded-lg border border-slate-700 text-slate-200">
                                        <input type="checkbox" value="{{ $coId }}" class="co-checkbox text-blue-600 rounded"> {{ $coId }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-slate-950/70 p-5 rounded-2xl border border-slate-700">
                            <label class="block text-xs md:text-sm font-extrabold uppercase text-slate-300 tracking-wider mb-2">Scheme Preparation &amp; Practical Blueprint (Rubrics / Instructions)</label>
                            <textarea id="series_qp_outline" rows="8" placeholder="Enter practical experiment choices, setup specifications, and marking criteria..." class="w-full p-3.5 bg-slate-900 border border-slate-700 rounded-xl text-sm md:text-base text-slate-100 font-mono outline-none focus:border-purple-500 leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SUB-PANE 2: SERIES MARKS ENTRY (DIRECT TABLE - TWO SERIES FIELDS, NO SPLITUP) -->
                <div id="series-subpane-marks" class="inner-subtab-pane hidden space-y-4 mt-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 p-4 bg-slate-950/70 rounded-2xl border border-slate-700">
                        <div>
                            <span class="text-base md:text-lg font-black text-slate-100 uppercase tracking-wide flex items-center gap-2">
                                <i class="fa-solid fa-list-ol text-purple-400"></i> Direct Series Test Mark Entry (Table Entry)
                            </span>
                            <p class="text-sm text-slate-300 mt-1">
                                Enter <strong>Series 1 (40M)</strong> and <strong>Series 2 (40M)</strong>. Final score = Average (40M) converted to 15 CIA Marks (rounded off).
                            </p>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="flex items-center gap-2 p-1.5 bg-slate-900 rounded-xl border border-slate-700">
                                <span class="text-xs uppercase font-extrabold text-slate-300 px-2">Batch:</span>
                                <button onclick="filterLabBatch('All')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-black bg-blue-600 text-white cursor-pointer">All</button>
                                <button onclick="filterLabBatch('Batch 1')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-bold text-slate-300 hover:text-white cursor-pointer">1</button>
                                <button onclick="filterLabBatch('Batch 2')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-bold text-slate-300 hover:text-white cursor-pointer">2</button>
                            </div>
                            <button onclick="saveSeriesMarksBulkDirect()" id="btnSaveSeriesDirect" class="exp-btn-touch px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-black text-sm md:text-base rounded-xl shadow-lg cursor-pointer flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk text-sm"></i> Save Series Marks
                            </button>
                        </div>
                    </div>

                    <!-- Direct Series Table -->
                    <div class="overflow-x-auto rounded-xl border border-slate-700">
                        <table class="w-full text-left text-xs md:text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-slate-200 font-bold uppercase text-[11px] md:text-xs border-b border-slate-700">
                                    <th class="py-2 px-2 w-12 text-center">Roll</th>
                                    <th class="py-2 px-2 w-32">Register No</th>
                                    <th class="py-2 px-2">Student Name</th>
                                    <th class="py-2 px-2 w-28 text-center">Batch</th>
                                    <th class="py-2 px-2 w-36 text-center text-purple-300">Series 1 (40M)</th>
                                    <th class="py-2 px-2 w-36 text-center text-purple-300">Series 2 (40M)</th>
                                    <th class="py-2 px-2 w-32 text-center text-slate-200">Average (40M)</th>
                                    <th class="py-2 px-2 w-36 text-center text-amber-300 bg-purple-950/40">Series CIA (15M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach($students as $index => $student)
                                @php
                                    $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                                    $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch 1' : 'Batch 2');
                                    $series1Log = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                                    $series2Log = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                                    $s1Val = $series1Log ? floatval($series1Log->total_score_40) : null;
                                    $s2Val = $series2Log ? floatval($series2Log->total_score_40) : null;
                                    $sNums = array_filter([$s1Val, $s2Val], fn($v) => $v !== null);
                                    $avg40 = count($sNums) > 0 ? (array_sum($sNums) / count($sNums)) : 0;
                                    $scaled15 = round(($avg40 / 40) * 15, 2);
                                    $rounded15 = (int)round($scaled15);
                                @endphp
                                <tr class="student-row hover:bg-slate-900/50 transition" 
                                    data-reg-no="{{ $student->reg_no }}" 
                                    data-batch="{{ $batchVal }}">
                                    <td class="py-2 px-2 text-center font-mono text-slate-300 font-bold text-xs md:text-sm">{{ $student->roll_no ?? ($index + 1) }}</td>
                                    <td class="py-2 px-2 font-mono text-slate-300 font-bold text-xs md:text-sm">{{ $student->reg_no }}</td>
                                    <td class="py-2 px-2 font-semibold text-white text-xs md:text-sm">{{ $student->name }}</td>
                                    <td class="py-2 px-2 text-center">
                                        <span class="student-batch-badge-{{ $student->reg_no }} px-2 py-0.5 rounded-lg text-[11px] md:text-xs font-bold {{ $batchVal == 'Batch 1' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                                            {{ $batchVal }}
                                        </span>
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <input type="number" min="0" max="40" step="0.5" 
                                               class="series-input-s1 w-20 md:w-24 px-2 py-1 bg-slate-950 border border-slate-700 focus:border-purple-500 rounded-lg text-xs md:text-sm text-center font-mono font-bold text-purple-300 outline-none"
                                               data-reg-no="{{ $student->reg_no }}"
                                               value="{{ $s1Val !== null ? $s1Val : '' }}"
                                               placeholder="—"
                                               oninput="calcSeriesRow(this)">
                                    </td>
                                    <td class="p-1.5 text-center">
                                        <input type="number" min="0" max="40" step="0.5" 
                                               class="series-input-s2 w-20 md:w-24 px-2 py-1 bg-slate-950 border border-slate-700 focus:border-purple-500 rounded-lg text-xs md:text-sm text-center font-mono font-bold text-purple-300 outline-none"
                                               data-reg-no="{{ $student->reg_no }}"
                                               value="{{ $s2Val !== null ? $s2Val : '' }}"
                                               placeholder="—"
                                               oninput="calcSeriesRow(this)">
                                    </td>
                                    <td class="py-2 px-2 text-center font-mono text-slate-200 text-xs md:text-sm font-semibold" id="series-row-avg-{{ $student->reg_no }}">
                                        {{ number_format($avg40, 2) }}
                                    </td>
                                    <td class="py-2 px-2 text-center font-mono text-amber-300 bg-purple-950/40 text-xs md:text-sm font-bold" id="series-row-scaled-{{ $student->reg_no }}">
                                        {{ number_format($scaled15, 2) }} <span class="text-xs text-amber-400 font-normal">({{ $rounded15 }})</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 5: CIA REGISTER                                                       -->
        <!-- ========================================================================= -->
        <div id="tab-content-cia_register" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5 font-heading">
                            <span class="material-symbols-rounded text-blue-400 text-2xl">fact_check</span>
                            <span>Continuous Internal Assessment (CIA) Master Register</span>
                        </h2>
                        <p class="text-sm md:text-base text-slate-300 mt-1.5 font-medium">
                            Formula: <strong>Continuous Evaluation (40M)</strong> + <strong>Series Tests (15M)</strong> + <strong>Attendance (5M)</strong> = <strong>60M CIA Total (Rounded Whole Number)</strong> + <strong>ESE (40M)</strong> = <strong>100M Grand Total</strong>.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 flex-wrap shrink-0">
                        <!-- Batch Filter Pills -->
                        <div class="flex items-center gap-2 p-1.5 bg-slate-950 rounded-xl border border-slate-700">
                            <span class="text-xs uppercase font-extrabold text-slate-300 px-2">Batch:</span>
                            <button onclick="filterLabBatch('All')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-black bg-blue-600 text-white cursor-pointer">All</button>
                            <button onclick="filterLabBatch('Batch 1')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-bold text-slate-300 hover:text-white cursor-pointer">Batch 1</button>
                            <button onclick="filterLabBatch('Batch 2')" class="batch-filter-btn px-3 py-1 rounded-lg text-xs font-bold text-slate-300 hover:text-white cursor-pointer">Batch 2</button>
                        </div>

                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/cia" target="_blank" class="exp-btn-touch px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-black flex items-center gap-2 no-underline shadow-md">
                            <i class="fa-solid fa-print text-sm"></i> Print Register
                        </a>
                    </div>
                </div>

                <!-- CIA Master Register Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-700 mt-4">
                    <table class="w-full text-left text-xs md:text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-slate-200 font-bold uppercase text-[11px] md:text-xs border-b border-slate-700">
                                <th class="py-2 px-1.5 w-12 text-center">Roll</th>
                                <th class="py-2 px-2 w-32">Reg No</th>
                                <th class="py-2 px-2">Student Name</th>
                                <th class="py-2 px-1.5 w-24 text-center">Batch</th>
                                <th class="py-2 px-1.5 w-24 text-center">TEAMS %</th>
                                <th class="py-2 px-1.5 w-28 text-center text-amber-300">Atten Override %</th>
                                <th class="py-2 px-1.5 w-24 text-center text-emerald-400">Atten (5M)</th>
                                <th class="py-2 px-1.5 w-24 text-center">CE (40M)</th>
                                <th class="py-2 px-1.5 w-24 text-center text-purple-400">Series (15M)</th>
                                <th class="py-2 px-1.5 w-28 text-center font-bold text-white bg-slate-900">CIA Total (60M)</th>
                                <th class="py-2 px-1.5 w-24 text-center text-sky-400">ESE (40M)</th>
                                <th class="py-2 px-1.5 w-28 text-center text-emerald-400 font-bold">Total (100M)</th>
                                <th class="py-2 px-1.5 w-20 text-center text-amber-300 font-bold">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $index => $student)
                            @php
                                $score = $consolidatedScores[$student->reg_no] ?? [];
                                $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                                $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch 1' : 'Batch 2');
                                $attInfo = $attendanceMarks[$student->reg_no] ?? [];
                                $hasOverride = !empty($attInfo['override_percentage']);
                            @endphp
                            <tr class="student-row hover:bg-slate-900/50 transition" 
                                data-reg-no="{{ $student->reg_no }}" 
                                data-batch="{{ $batchVal }}">
                                <td class="py-2 px-1.5 text-center font-mono text-slate-300 font-bold text-xs md:text-sm">{{ $student->roll_no ?? ($index + 1) }}</td>
                                <td class="py-2 px-2 font-mono text-slate-300 font-bold text-xs md:text-sm">{{ $student->reg_no }}</td>
                                <td class="py-2 px-2 font-semibold text-white text-xs md:text-sm">{{ $student->name }}</td>
                                <td class="py-2 px-1.5 text-center">
                                    <span class="student-batch-badge-{{ $student->reg_no }} px-2 py-0.5 rounded-lg text-[11px] md:text-xs font-bold {{ $batchVal == 'Batch 1' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                                        {{ $batchVal }}
                                    </span>
                                </td>

                                <!-- TEAMS Uploaded % -->
                                <td class="py-2 px-1.5 text-center font-mono text-slate-200 text-xs md:text-sm font-semibold">
                                    {{ $attInfo['teams_percentage'] !== null ? number_format($attInfo['teams_percentage'], 1) . '%' : (isset($attInfo['raw_percentage']) ? number_format($attInfo['raw_percentage'], 1) . '%' : '—') }}
                                </td>

                                <!-- Attendance Override % (ONLY editable field) -->
                                <td class="p-1 text-center">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           value="{{ $attInfo['override_percentage'] ?? '' }}" 
                                           placeholder="—"
                                           data-reg-no="{{ $student->reg_no }}"
                                           data-prev-val="{{ $attInfo['override_percentage'] ?? '' }}"
                                           onchange="saveAttendanceOverrideAjax(this, '{{ $student->reg_no }}')"
                                           class="cell-att-override {{ $hasOverride ? 'has-override' : '' }} w-18 md:w-20 px-1.5 py-1 text-center text-xs md:text-sm font-mono font-bold">
                                </td>

                                <!-- Attendance Mark (5M) -->
                                <td class="py-2 px-1.5 text-center font-mono font-black text-emerald-400 text-xs md:text-sm" id="cia-att-mark-{{ $student->reg_no }}">
                                    {{ $score['attendance_mark_5'] ?? 5 }}
                                </td>

                                <!-- CE Total (40M) -->
                                <td class="py-2 px-1.5 text-center font-mono text-slate-100 text-xs md:text-sm font-bold" id="cia-ce-total-{{ $student->reg_no }}">
                                    {{ $score['ce_total_40'] ?? '0.00' }}
                                </td>

                                <!-- Series Total (15M) -->
                                <td class="py-2 px-1.5 text-center font-mono text-purple-300 text-xs md:text-sm font-bold" id="cia-series-{{ $student->reg_no }}">
                                    {{ $score['scaled_series_15'] ?? '0.00' }}
                                </td>

                                <!-- Total CIA (60M) rounded without decimal! -->
                                <td class="py-2 px-1.5 text-center font-mono font-black text-white bg-slate-900/90 text-sm shadow-inner" id="cia-total-{{ $student->reg_no }}">
                                    {{ $score['total_cia_60'] ?? 0 }}
                                </td>

                                <!-- ESE (40M) -->
                                <td class="py-2 px-1.5 text-center font-mono text-sky-300 text-xs md:text-sm font-semibold" id="cia-ese-{{ $student->reg_no }}">
                                    {{ number_format($score['ese_score_40'] ?? 0, 2) }}
                                </td>

                                <!-- Grand Total (100M) -->
                                <td class="py-2 px-1.5 text-center font-mono font-black text-emerald-400 text-sm" id="cia-grand-{{ $student->reg_no }}">
                                    {{ $score['grand_total_100'] ?? 0 }}
                                </td>

                                <!-- Grade -->
                                <td class="py-2 px-1.5 text-center font-black text-amber-300 text-sm font-mono" id="cia-grade-{{ $student->reg_no }}">
                                    {{ $score['grade'] ?? '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 6: ATTAINMENT TABLE                                                   -->
        <!-- ========================================================================= -->
        <div id="tab-content-attainment" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6 space-y-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5 font-heading">
                            <span class="material-symbols-rounded text-cyan-400 text-2xl">analytics</span>
                            <span>Course Outcome &amp; Program Outcome Attainment Engine</span>
                        </h2>
                        <p class="text-sm md:text-base text-slate-300 mt-1.5 font-medium">Direct assessment from CIA (CE + Series + ESE) combined with Indirect surveys (80% Direct + 20% Indirect).</p>
                    </div>

                    <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="exp-btn-touch px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-black flex items-center gap-2 no-underline shadow-md">
                        <i class="fa-solid fa-print text-sm"></i> Print Attainment
                    </a>
                </div>

                <!-- Surveys Console (Mid-Sem & Course Exit) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Mid-Sem Survey -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-700 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-hourglass-half text-amber-400"></i> Mid-Semester Feedback Survey
                            </h4>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">SAR Criterion 2</span>
                        </div>
                        <p class="text-sm text-slate-300">Student feedback on lab pacing, apparatus setup, and instruction clarity.</p>
                        <div class="flex items-center gap-2.5 pt-1">
                            <button onclick="manageSurvey('mid', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl cursor-pointer transition shadow">Initiate</button>
                            <button onclick="manageSurvey('mid', 'close')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl cursor-pointer transition border border-slate-700">Close</button>
                        </div>
                    </div>

                    <!-- Course Exit Survey -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-700 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i> Course Exit Survey (Indirect CO1–CO4)
                            </h4>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-emerald-400 border border-slate-700">
                                Responses: {{ $attainmentData['exitResponsesCount'] ?? 0 }} / {{ count($students) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-300">Student indirect outcome evaluation feeding into the 20% indirect attainment computation.</p>
                        <div class="flex items-center gap-2.5 pt-1">
                            <button onclick="manageSurvey('exit', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl cursor-pointer transition shadow">Initiate</button>
                            <button onclick="manageSurvey('exit', 'close')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl cursor-pointer transition border border-slate-700">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Attainment Summary Grid -->
                <div class="space-y-3.5">
                    <h3 class="text-xs md:text-sm font-bold text-slate-200 uppercase tracking-wider">CO Attainment Breakdown</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-700">
                        <table class="w-full text-center text-xs md:text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-slate-200 font-bold uppercase text-[11px] md:text-xs border-b border-slate-700">
                                    <th class="py-2 px-2.5 text-left">Course Outcome</th>
                                    <th class="py-2 px-2.5 text-center">Direct Met %</th>
                                    <th class="py-2 px-2.5 text-center text-sky-400">Direct Level (80%)</th>
                                    <th class="py-2 px-2.5 text-center text-amber-300">Indirect Level (20%)</th>
                                    <th class="py-2 px-2.5 text-center text-emerald-400 font-bold">Overall Attainment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @php
                                    $dStats = $attainmentData['directStats'] ?? [];
                                    $iStats = $attainmentData['indirectStats'] ?? [];
                                    $cStats = $attainmentData['combinedStats'] ?? [];
                                @endphp
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                <tr class="hover:bg-slate-900/50 transition">
                                    <td class="py-2 px-2.5 text-left font-bold font-mono text-amber-300 text-xs md:text-sm">{{ $coTag }}</td>
                                    <td class="py-2 px-2.5 text-center font-mono font-semibold text-slate-100 text-xs md:text-sm">{{ $dStats[$coTag]['met_percent'] ?? 0 }}%</td>
                                    <td class="py-2 px-2.5 text-center font-mono font-bold text-sky-400 text-xs md:text-sm">{{ $dStats[$coTag]['level'] ?? 0 }}</td>
                                    <td class="py-2 px-2.5 text-center font-mono font-bold text-amber-300 text-xs md:text-sm">{{ $iStats[$coTag]['level'] ?? 0 }}</td>
                                    <td class="py-2 px-2.5 text-center font-mono font-bold text-emerald-400 text-xs md:text-sm">{{ $cStats[$coTag] ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 7: REPORTS                                                            -->
        <!-- ========================================================================= -->
        <div id="tab-content-reports" class="tab-pane-content hidden space-y-4">
            <div class="glass-panel p-6 space-y-6">
                <!-- Header -->
                <div class="pb-4 border-b border-slate-800">
                    <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5 font-heading">
                        <span class="material-symbols-rounded text-sky-400 text-2xl">print</span>
                        <span>Laboratory Reports &amp; Printable Marksheets</span>
                    </h2>
                    <p class="text-sm md:text-base text-slate-300 mt-1.5 font-medium">Generate official printable PDFs with department letterheads, NBA rubrics, and faculty signatures.</p>
                </div>

                <!-- Report Cards Grid (All 8 Professional Lab Reports) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Report 1: Attendance Report -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-emerald-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">how_to_reg</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Subject Attendance Report</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Detailed attendance statement with TEAMS upload %, faculty overrides, and 5-mark scaling.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/attendance" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print Attendance
                        </a>
                    </div>

                    <!-- Report 2: Consolidated Marksheet (100M) -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-blue-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-blue-500/15 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">fact_check</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Consolidated Mark Sheet (100M)</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Complete register with Daywork (30M), Open-Ended (10M), Series (15M), Attendance (5M), CIA (60M), ESE (40M) and Grades.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/consolidated" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print Consolidated
                        </a>
                    </div>

                    <!-- Report 3: CIA Internal Marksheet (60M) -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-indigo-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">assignment_turned_in</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Internal Mark CIA Report (60M)</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Official internal mark register out of 60 rounded off without decimals for SBTE Kerala submission.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/cia" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print CIA (60M)
                        </a>
                    </div>

                    <!-- Report 4: Series Test Marksheet (15M) -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-purple-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">edit_note</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Series Test Mark Sheet (15M)</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Series Exam 1 (40M) &amp; Series 2 (40M), average calculation, and converted 15 Marks breakdown.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/series" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print Series (15M)
                        </a>
                    </div>

                    <!-- Report 5: Continuous Evaluation Marksheet (40M) -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-teal-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-teal-500/15 border border-teal-500/30 text-teal-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">science</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Continuous Evaluation Mark Sheet (40M)</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Comprehensive breakdown of day-to-day lab work (30M) and open-ended practicals (10M).</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/ce" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print CE (40M)
                        </a>
                    </div>

                    <!-- Report 6: Lesson Planner -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-emerald-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">calendar_month</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Lesson Planner &amp; Log</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Proposed vs actual timeline schedule with faculty workload allocation and syllabus tracking.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/plan" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print Plan
                        </a>
                    </div>

                    <!-- Report 7: Attainment -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-cyan-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">analytics</span>
                            </div>
                            <h3 class="text-base font-bold text-white">CO-PO Attainment Report</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Direct and indirect NBA course articulation summary with target thresholds and matrix weights.</p>
                        </div>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-print text-sm"></i> Print Attainment
                        </a>
                    </div>

                    <!-- Report 8: Course File Portfolio -->
                    <div class="p-5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col justify-between hover:border-amber-500/60 transition shadow-md">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 flex items-center justify-center mb-3 text-lg font-bold">
                                <span class="material-symbols-rounded">folder_open</span>
                            </div>
                            <h3 class="text-base font-bold text-white">Official Course File</h3>
                            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Complete 25-document statutory portfolio for SITTTR and NBA accreditation compliance audit.</p>
                        </div>
                        <a href="/r26/classroom/practical/course-file/{{ $batchSubject->id }}" target="_blank" class="mt-4 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm flex items-center justify-center gap-2 no-underline shadow">
                            <i class="fa-solid fa-folder-open text-sm"></i> View Portfolio
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- ========================================================================= -->
        <!-- TAB 8: PRECLASS HUB                                                       -->
        <!-- ========================================================================= -->
        <div id="tab-content-preclass_hub" class="tab-pane-content hidden space-y-4">
            @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practical'])
        </div>

    </main>


    <!-- ═══ 4. MOBILE / DESKTOP TOUCH SLIDEOVER GRADING MODAL ════════════ -->
    <div id="gradingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col justify-end sm:justify-center p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-5 mx-auto">
            
            <div class="flex justify-between items-center border-b border-slate-800 pb-3.5">
                <div>
                    <h3 id="modalStudentName" class="font-black text-white text-lg md:text-xl">Student Name</h3>
                    <span id="modalStudentReg" class="text-sm font-mono text-slate-300 font-semibold">Reg No</span>
                </div>
                <button onclick="closeGradingModal()" class="text-slate-400 hover:text-white p-2 text-2xl font-bold leading-none cursor-pointer">&times;</button>
            </div>

            <!-- Rubric Sliders Container -->
            <div id="modalSlidersContainer" class="space-y-3 max-h-[50vh] overflow-y-auto pr-1">
                <!-- Injected via JavaScript -->
            </div>

            <div class="flex justify-between items-center border-t border-slate-800 pt-3.5">
                <span class="text-sm uppercase font-extrabold text-slate-300 tracking-wider">Total Score:</span>
                <span id="modalTotalScore" class="font-mono text-2xl font-black text-emerald-400">0.00</span>
            </div>

            <div class="flex gap-3 pt-2">
                <button onclick="navigateStudent(-1)" class="w-1/2 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl transition flex items-center justify-center gap-2 cursor-pointer border border-slate-700">
                    <i class="fa-solid fa-chevron-left text-xs"></i> Prev Student
                </button>
                <button onclick="navigateStudent(1)" class="w-1/2 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-bold rounded-xl transition flex items-center justify-center gap-2 cursor-pointer border border-slate-700">
                    Next Student <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <button onclick="closeGradingModal()" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-black text-sm md:text-base rounded-xl shadow-lg cursor-pointer">
                Done
            </button>
        </div>
    </div>


    <!-- ═══ 5. JAVASCRIPT CONTROLS ════════════════════════════════════════ -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = "{{ $batchSubject->id }}";
        
        const studentList = @json($students);
        const labBatches = @json($labBatches);
        const experimentLogs = @json($experimentLogs);
        const openEndedLogs = @json($openEndedLogs);
        const seriesExamLogs = @json($seriesExamLogs);
        const eseMarks = @json($eseMarks);
        const attendanceMarks = @json($attendanceMarks);
        const consolidatedScores = @json($consolidatedScores);

        let activeTab = 'outline';
        let activeBatchFilter = 'All';
        let currentStudentIndex = 0;
        let gradingModalContext = 'table22';

        const scoresState = {
            table22: {},
            table23: {},
            table31: {}
        };

        // Populate local JS scores cache
        studentList.forEach(s => {
            const reg = s.reg_no;

            const expLog = experimentLogs['Exp 1'] ? experimentLogs['Exp 1'].find(x => x.reg_no === reg) : null;
            scoresState.table22[reg] = expLog ? {
                c1: parseFloat(expLog.prep_punctuality),
                c2: parseFloat(expLog.setup_procedure),
                c3: parseFloat(expLog.observation_recording),
                c4: parseFloat(expLog.analysis_interpretation),
                c5: parseFloat(expLog.viva_voce),
                c6: parseFloat(expLog.teamwork_discipline)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

            const openLog = openEndedLogs[reg];
            scoresState.table23[reg] = openLog ? {
                c1: parseFloat(openLog.originality_relevance),
                c2: parseFloat(openLog.objectives_plan),
                c3: parseFloat(openLog.execution_recording),
                c4: parseFloat(openLog.analysis_presentation),
                c5: parseFloat(openLog.teamwork_innovation)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0 };

            const s1Log = seriesExamLogs['Series 1'] ? seriesExamLogs['Series 1'].find(x => x.reg_no === reg) : null;
            const s2Log = seriesExamLogs['Series 2'] ? seriesExamLogs['Series 2'].find(x => x.reg_no === reg) : null;
            scoresState.table31[reg] = {
                'Series 1': s1Log ? {
                    c1: parseFloat(s1Log.writeup_procedure),
                    c2: parseFloat(s1Log.setup_execution),
                    c3: parseFloat(s1Log.observation_result),
                    c4: parseFloat(s1Log.viva_voce),
                    c5: parseFloat(s1Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 },
                'Series 2': s2Log ? {
                    c1: parseFloat(s2Log.writeup_procedure),
                    c2: parseFloat(s2Log.setup_execution),
                    c3: parseFloat(s2Log.observation_result),
                    c4: parseFloat(s2Log.viva_voce),
                    c5: parseFloat(s2Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 }
            };
        });

        // Tab Switching
        function switchTab(tabId) {
            activeTab = tabId;
            // Hide all tab panes
            document.querySelectorAll('.tab-pane-content').forEach(el => el.classList.add('hidden'));
            // De-activate all tab buttons
            document.querySelectorAll('.subtab-btn').forEach(el => el.classList.remove('active'));

            // Show target tab pane
            const panel = document.getElementById('tab-content-' + tabId);
            if (panel) panel.classList.remove('hidden');

            // Activate button
            const btn = document.getElementById('tab-btn-' + tabId);
            if (btn) btn.classList.add('active');

            if (tabId === 'series_test') {
                loadSeriesQpConfig(document.getElementById('series_qp_select')?.value || 'Series 1');
            }
            
            filterLabBatch(activeBatchFilter);
        }

        // Inner Subtab Switchers
        function switchCeSubtab(subId) {
            document.querySelectorAll('#tab-content-ce .inner-subtab-pane').forEach(el => el.classList.add('hidden'));
            const p = document.getElementById('ce-subpane-' + subId);
            if (p) p.classList.remove('hidden');

            document.getElementById('ce-subtab-btn-daywork')?.classList.remove('bg-blue-500/10', 'text-blue-400', 'border-blue-500/30');
            document.getElementById('ce-subtab-btn-daywork')?.classList.add('border-transparent', 'text-slate-400');
            document.getElementById('ce-subtab-btn-open_ended')?.classList.remove('bg-amber-500/10', 'text-amber-400', 'border-amber-500/30');
            document.getElementById('ce-subtab-btn-open_ended')?.classList.add('border-transparent', 'text-slate-400');

            if (subId === 'daywork') {
                const b = document.getElementById('ce-subtab-btn-daywork');
                b?.classList.add('bg-blue-500/10', 'text-blue-400', 'border-blue-500/30');
                b?.classList.remove('border-transparent', 'text-slate-400');
            } else {
                const b = document.getElementById('ce-subtab-btn-open_ended');
                b?.classList.add('bg-amber-500/10', 'text-amber-400', 'border-amber-500/30');
                b?.classList.remove('border-transparent', 'text-slate-400');
            }
        }

        function switchSeriesSubtab(subId) {
            document.querySelectorAll('#tab-content-series_test .inner-subtab-pane').forEach(el => el.classList.add('hidden'));
            const p = document.getElementById('series-subpane-' + subId);
            if (p) p.classList.remove('hidden');

            document.getElementById('series-subtab-btn-qp')?.classList.remove('bg-purple-600', 'text-white');
            document.getElementById('series-subtab-btn-qp')?.classList.add('text-slate-400');
            document.getElementById('series-subtab-btn-marks')?.classList.remove('bg-purple-600', 'text-white');
            document.getElementById('series-subtab-btn-marks')?.classList.add('text-slate-400');

            if (subId === 'qp') {
                const b = document.getElementById('series-subtab-btn-qp');
                b?.classList.add('bg-purple-600', 'text-white');
                b?.classList.remove('text-slate-400');
            } else {
                const b = document.getElementById('series-subtab-btn-marks');
                b?.classList.add('bg-purple-600', 'text-white');
                b?.classList.remove('text-slate-400');
            }
        }

        // Return button handler
        function handleVirtualLabBack(e) {
            if (e) e.preventDefault();
            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.focus();
                } catch(err) {}
                window.close();
                return false;
            }
            if (window.history.length > 1) {
                window.history.back();
                setTimeout(() => {
                    window.location.href = '{{ $dashboardUrl ?? "/dashboard/lecturer" }}';
                }, 200);
            } else {
                window.location.href = '{{ $dashboardUrl ?? "/dashboard/lecturer" }}';
            }
            return false;
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Lab Batch Filter
        function filterLabBatch(batch) {
            activeBatchFilter = batch;
            document.querySelectorAll('.batch-filter-btn').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('text-slate-400');
            });

            const btnMap = { 'All': 'All', 'Batch 1': '1', 'Batch 2': '2', 'Batch A': '1', 'Batch B': '2' };
            const id = btnMap[batch] || 'All';
            const activeBtns = document.querySelectorAll(`[id="batch-filter-${id}"]`);
            activeBtns.forEach(btn => {
                btn.classList.add('bg-blue-600', 'text-white');
                btn.classList.remove('text-slate-400');
            });

            document.querySelectorAll('.student-row').forEach(row => {
                const b = row.getAttribute('data-batch') || 'Batch 1';
                if (batch === 'All') {
                    row.classList.remove('hidden');
                } else if (batch === 'Batch 1' && (b === 'Batch 1' || b === 'Batch A' || b === 'A')) {
                    row.classList.remove('hidden');
                } else if (batch === 'Batch 2' && (b === 'Batch 2' || b === 'Batch B' || b === 'B')) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // Direct Series Test Row Calculation
        function calcSeriesRow(input) {
            const regNo = input.getAttribute('data-reg-no');
            const s1Input = document.querySelector(`.series-input-s1[data-reg-no="${regNo}"]`);
            const s2Input = document.querySelector(`.series-input-s2[data-reg-no="${regNo}"]`);
            const s1Val = s1Input && s1Input.value.trim() !== '' ? Math.min(40, Math.max(0, parseFloat(s1Input.value))) : null;
            const s2Val = s2Input && s2Input.value.trim() !== '' ? Math.min(40, Math.max(0, parseFloat(s2Input.value))) : null;

            let validScores = [];
            if (s1Val !== null) validScores.push(s1Val);
            if (s2Val !== null) validScores.push(s2Val);

            const avg40 = validScores.length > 0 ? (validScores.reduce((a, b) => a + b, 0) / validScores.length) : 0;
            const scaled15 = Math.round(((avg40 / 40) * 15) * 100) / 100;
            const roundedSeries = Math.round(scaled15);

            const avgEl = document.getElementById(`series-row-avg-${regNo}`);
            if (avgEl) avgEl.innerText = avg40.toFixed(2);

            const scaledEl = document.getElementById(`series-row-scaled-${regNo}`);
            if (scaledEl) scaledEl.innerHTML = `${scaled15.toFixed(2)} <span class="text-xs text-amber-400/80 font-normal">(${roundedSeries})</span>`;

            // Update in CIA register
            const ciaSeriesEl = document.getElementById(`cia-series-${regNo}`);
            if (ciaSeriesEl) ciaSeriesEl.innerText = scaled15.toFixed(2);

            recalculateCIA(regNo);
        }

        // Bulk Save Direct Series Marks
        async function saveSeriesMarksBulkDirect() {
            const marks = {};
            document.querySelectorAll('.series-input-s1').forEach(s1Input => {
                const regNo = s1Input.getAttribute('data-reg-no');
                const s2Input = document.querySelector(`.series-input-s2[data-reg-no="${regNo}"]`);
                marks[regNo] = {
                    series_1: s1Input.value.trim() !== '' ? parseFloat(s1Input.value) : null,
                    series_2: s2Input && s2Input.value.trim() !== '' ? parseFloat(s2Input.value) : null,
                };
            });

            const btn = document.getElementById('btnSaveSeriesDirect');
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...';
                btn.disabled = true;
            }

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/series-bulk`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ marks })
                });
                const data = await res.json();
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Saved!';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save Series Marks';
                        btn.disabled = false;
                    }, 1800);
                }
                if (data.status === 'SUCCESS' || data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Series test marks saved successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            } catch(err) {
                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save Series Marks';
                    btn.disabled = false;
                }
                console.error(err);
                alert('Failed to save series marks.');
            }
        }

        async function updateLabBatch(regNo, value) {
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lab-batch`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, lab_batch: value })
                });
                const data = await res.json();
                if (data.success) {
                    const displayVal = value || 'Batch 1';
                    document.querySelectorAll(`.student-row[data-reg-no="${regNo}"]`).forEach(row => {
                        row.setAttribute('data-batch', displayVal);
                    });
                    document.querySelectorAll(`.student-batch-select-${regNo}`).forEach(sel => {
                        sel.value = value;
                    });
                    document.querySelectorAll(`.student-batch-badge-${regNo}`).forEach(badge => {
                        badge.innerText = displayVal;
                        badge.className = `student-batch-badge-${regNo} px-2.5 py-1 rounded-lg text-xs font-bold ` + 
                            (displayVal === 'Batch 1' || displayVal === 'Batch A' ? 'bg-indigo-500/15 text-indigo-300' : 
                            (displayVal === 'Batch 2' || displayVal === 'Batch B' ? 'bg-cyan-500/15 text-cyan-300' : 'bg-slate-800 text-slate-400'));
                    });
                    filterLabBatch(activeBatchFilter);
                }
            } catch(e) {
                console.error(e);
            }
        }

        // Attendance Override Auto-Save (The ONLY editable field in CIA register!)
        async function saveAttendanceOverrideAjax(input, regNo) {
            const val = input.value.trim();
            const prevVal = input.getAttribute('data-prev-val') || '';
            if (val === prevVal) return;

            input.classList.add('opacity-50');
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/attendance-override`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        reg_no: regNo,
                        override_percentage: val === '' ? null : parseFloat(val)
                    })
                });
                const data = await res.json();
                input.classList.remove('opacity-50');
                if (data.status === 'SUCCESS' || data.success) {
                    input.setAttribute('data-prev-val', val);
                    if (val !== '') {
                        input.classList.add('has-override');
                    } else {
                        input.classList.remove('has-override');
                    }
                    // Update attendance mark in row
                    const mark = data.attendance_mark;
                    const attSpan = document.getElementById(`cia-att-mark-${regNo}`);
                    if (attSpan) attSpan.innerText = mark;
                    attendanceMarks[regNo] = attendanceMarks[regNo] || {};
                    attendanceMarks[regNo].mark = mark;
                    attendanceMarks[regNo].override_percentage = data.override_percentage;
                    attendanceMarks[regNo].effective_percentage = data.effective_percentage;

                    // Recalculate CIA
                    recalculateCIA(regNo);
                } else {
                    alert(data.message || 'Failed to save attendance override.');
                }
            } catch(e) {
                input.classList.remove('opacity-50');
                console.error(e);
                alert('Network error saving attendance override.');
            }
        }

        // Recalculate CIA Register client-side values live
        function recalculateCIA(regNo) {
            // Day Work 30M
            const s22 = scoresState.table22[regNo] || {};
            const sum22 = (s22.c1||0) + (s22.c2||0) + (s22.c3||0) + (s22.c4||0) + (s22.c5||0) + (s22.c6||0);
            const scaled22 = Math.round(((sum22 / 50) * 30) * 100) / 100;
            const labWorkEl = document.getElementById(`cia-lab-work-${regNo}`);
            if (labWorkEl) labWorkEl.innerText = scaled22.toFixed(2);

            // Open-ended 10M
            const s23 = scoresState.table23[regNo] || {};
            const sum23 = (s23.c1||0) + (s23.c2||0) + (s23.c3||0) + (s23.c4||0) + (s23.c5||0);
            const scaled23 = Math.round(((sum23 / 50) * 10) * 100) / 100;
            const openEl = document.getElementById(`cia-open-${regNo}`);
            if (openEl) openEl.innerText = scaled23.toFixed(2);

            // Combined CE 40M
            const ceTotal = Math.round((scaled22 + scaled23) * 100) / 100;
            const ceEl = document.getElementById(`cia-ce-total-${regNo}`);
            if (ceEl) ceEl.innerText = ceTotal.toFixed(2);

            // Series 15M (Checks direct inputs first, then fallback to scoresState)
            const s1Input = document.querySelector(`.series-input-s1[data-reg-no="${regNo}"]`);
            const s2Input = document.querySelector(`.series-input-s2[data-reg-no="${regNo}"]`);
            let sNums = [];
            if (s1Input && s1Input.value.trim() !== '') sNums.push(parseFloat(s1Input.value));
            if (s2Input && s2Input.value.trim() !== '') sNums.push(parseFloat(s2Input.value));

            let scaled31 = 0;
            if (sNums.length > 0) {
                const avg40 = sNums.reduce((a, b) => a + b, 0) / sNums.length;
                scaled31 = Math.round(((avg40 / 40) * 15) * 100) / 100;
            } else {
                const s31_1 = scoresState.table31[regNo]?.['Series 1'] || {};
                const s31_2 = scoresState.table31[regNo]?.['Series 2'] || {};
                const sum31_1 = (s31_1.c1||0) + (s31_1.c2||0) + (s31_1.c3||0) + (s31_1.c4||0) + (s31_1.c5||0);
                const sum31_2 = (s31_2.c1||0) + (s31_2.c2||0) + (s31_2.c3||0) + (s31_2.c4||0) + (s31_2.c5||0);
                const avg31 = (sum31_1 + sum31_2) / 2;
                scaled31 = Math.round(((avg31 / 40) * 15) * 100) / 100;
            }
            const seriesEl = document.getElementById(`cia-series-${regNo}`);
            if (seriesEl) seriesEl.innerText = scaled31.toFixed(2);

            // Attendance 5M
            const att = attendanceMarks[regNo] && attendanceMarks[regNo].mark !== undefined ? attendanceMarks[regNo].mark : 5;
            const attEl = document.getElementById(`cia-att-mark-${regNo}`);
            if (attEl) attEl.innerText = att;

            // Total CIA (60M) - strictly rounded without decimal!
            const cia = Math.round(scaled22 + scaled23 + scaled31 + att);
            const ciaEl = document.getElementById(`cia-total-${regNo}`);
            if (ciaEl) ciaEl.innerText = cia;

            // ESE 40M
            const eseEl = document.getElementById(`cia-ese-${regNo}`);
            const ese = eseEl ? (parseFloat(eseEl.innerText) || 0) : 0;

            // Grand Total (100M)
            const grand = Math.round(cia + ese);
            const grandEl = document.getElementById(`cia-grand-${regNo}`);
            if (grandEl) grandEl.innerText = grand;

            // SBTE Kerala Grade
            const gradeEl = document.getElementById(`cia-grade-${regNo}`);
            if (gradeEl) {
                let g = 'F';
                if (ese < 16.0 && ese > 0) g = 'F';
                else if (grand >= 90) g = 'S';
                else if (grand >= 80) g = 'A';
                else if (grand >= 70) g = 'B';
                else if (grand >= 60) g = 'C';
                else if (grand >= 50) g = 'D';
                else if (grand >= 40) g = 'E';
                else g = 'F';
                gradeEl.innerText = g;
            }
        }

        // Syllabus Upload
        async function uploadSyllabusPdf() {
            const input = document.getElementById('syllabus_pdf_input');
            if (input.files.length === 0) return;

            const btn = document.getElementById('syllabus-upload-btn');
            const status = document.getElementById('syllabus-upload-status');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading...';
            status.className = 'mt-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-blue-950/50 border border-blue-700/40 text-blue-300';
            status.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading syllabus PDF and parsing parameters...';
            status.classList.remove('hidden');

            const formData = new FormData();
            formData.append('syllabus_file', input.files[0]);
            formData.append('_token', csrfToken);

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    status.className = 'mt-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-emerald-950/50 border border-emerald-700/40 text-emerald-300';
                    status.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message} Reloading...`;
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    status.className = 'mt-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-red-950/50 border border-red-700/40 text-red-300';
                    status.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${data.message || 'Upload failed.'}`;
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload Syllabus PDF';
                }
            } catch(e) {
                status.className = 'mt-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2.5 bg-red-950/50 border border-red-700/40 text-red-300';
                status.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Network error: ${e.message}`;
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload Syllabus PDF';
            }
        }

        // CO-PO Matrix Save
        async function savePracticalCoPoMatrixBtn() {
            const btn = document.getElementById('savePracticalCoPoBtn');
            const origHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            }

            const inputs = document.querySelectorAll('#practicalCoPoMatrixTbody input[data-co]');
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

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/copo-mapping/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mappings: mappings })
                });
                const data = await res.json();
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                alert(data.message || 'Matrix saved successfully!');
            } catch(e) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                alert('Network error saving matrix.');
            }
        }

        // Experiments Inventory Management
        let localExperiments = @json($practicalCourseFile ? ($practicalCourseFile->getExperimentsArray()) : []);
        const pdfParsedExperiments = @json($practicalCourseFile ? (json_decode($practicalCourseFile->parsed_experiments, true) ?? []) : []);

        function initExperimentSelect() {
            const select = document.getElementById('exp_select');
            if (!select) return;
            select.innerHTML = '';
            
            const uniqueExps = new Set();
            if (typeof localExperiments !== 'undefined' && localExperiments && localExperiments.length > 0) {
                localExperiments.forEach(e => {
                    if (e.expt_no) uniqueExps.add(e.expt_no);
                });
            }
            if (typeof experimentLogs !== 'undefined' && experimentLogs) {
                Object.keys(experimentLogs).forEach(k => {
                    uniqueExps.add(k);
                });
            }
            if (uniqueExps.size === 0) uniqueExps.add("Exp 1");

            const sorted = Array.from(uniqueExps).sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' }));
            sorted.forEach(expNo => {
                const opt = document.createElement('option');
                opt.value = expNo;
                opt.textContent = expNo;
                select.appendChild(opt);
            });

            const activeExp = document.getElementById('exp_no')?.value || 'Exp 1';
            select.value = activeExp;
            updateExperimentTitleFromList(activeExp);
        }

        function updateExperimentTitleFromList(expNo) {
            const matched = (typeof localExperiments !== 'undefined' ? localExperiments : []).find(e => e.expt_no === expNo);
            if (matched && matched.title) {
                document.getElementById('exp_title').value = matched.title;
            } else {
                const logs = experimentLogs[expNo];
                if (logs && logs.length > 0 && logs[0].title) {
                    document.getElementById('exp_title').value = logs[0].title;
                } else {
                    document.getElementById('exp_title').value = '';
                }
            }
        }

        function switchActiveExperiment(expNo) {
            const expNoInput = document.getElementById('exp_no');
            if (expNoInput) expNoInput.value = expNo;
            updateExperimentTitleFromList(expNo);

            studentList.forEach(s => {
                const reg = s.reg_no;
                const expLog = experimentLogs[expNo] ? experimentLogs[expNo].find(x => x.reg_no === reg) : null;
                
                scoresState.table22[reg] = expLog ? {
                    c1: parseFloat(expLog.prep_punctuality) || 0,
                    c2: parseFloat(expLog.setup_procedure) || 0,
                    c3: parseFloat(expLog.observation_recording) || 0,
                    c4: parseFloat(expLog.analysis_interpretation) || 0,
                    c5: parseFloat(expLog.viva_voce) || 0,
                    c6: parseFloat(expLog.teamwork_discipline) || 0
                } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

                const sum = (scoresState.table22[reg].c1||0) + (scoresState.table22[reg].c2||0) + (scoresState.table22[reg].c3||0) + (scoresState.table22[reg].c4||0) + (scoresState.table22[reg].c5||0) + (scoresState.table22[reg].c6||0);
                const scoreTextSpan = document.getElementById(`score-text-exp-${reg}`);
                if (scoreTextSpan) {
                    scoreTextSpan.innerText = sum.toFixed(2) + ' / 50';
                }
                recalculateCIA(reg);
            });
        }

        function onManualExpNoChange() {
            const val = document.getElementById('exp_no').value;
            const select = document.getElementById('exp_select');
            if (select) {
                let exists = false;
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === val) {
                        select.value = val;
                        exists = true;
                        break;
                    }
                }
                if (!exists && val.trim() !== '') {
                    const opt = document.createElement('option');
                    opt.value = val;
                    opt.textContent = val;
                    select.appendChild(opt);
                    select.value = val;
                }
            }
            switchActiveExperiment(val);
        }

        function loadFromPdfExperiments() {
            const statusEls = document.querySelectorAll('.exp-pdf-status');
            if (!pdfParsedExperiments || pdfParsedExperiments.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Syllabus Experiments Found',
                    text: 'Please upload a syllabus PDF first from the Course Outline tab to automatically extract experiments.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            localExperiments = JSON.parse(JSON.stringify(pdfParsedExperiments));
            loadExperimentsInventoryTable();
            statusEls.forEach(el => {
                el.className = 'exp-pdf-status mt-3 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-950/40 border border-emerald-700/40 text-emerald-300';
                el.style.display = 'flex';
                el.innerHTML = `<i class="fa-solid fa-circle-check"></i> Loaded ${pdfParsedExperiments.length} experiments from syllabus.`;
            });
            document.querySelectorAll('.exp-count-badge').forEach(b => b.innerText = `${localExperiments.length} experiments`);
            initExperimentSelect();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Loaded ${pdfParsedExperiments.length} experiments from syllabus`,
                showConfirmButton: false,
                timer: 2000
            });
        }

        function loadExperimentsInventoryTable() {
            const containers = [
                document.getElementById('experiments-rows-container'),
                document.getElementById('experiments-rows-container-outline')
            ].filter(Boolean);

            if (containers.length === 0) return;

            const esc = str => String(str || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            let html = '';
            if (!localExperiments || localExperiments.length === 0) {
                html = `<tr class="exp-empty-placeholder"><td colspan="6" class="p-6 text-center text-slate-400 italic text-xs md:text-sm">No experiments loaded. Click &ldquo;Load from Syllabus&rdquo; or &ldquo;Add Experiment&rdquo; above.</td></tr>`;
            } else {
                localExperiments.forEach((exp, idx) => {
                    const coOptions = ['CO1','CO2','CO3','CO4','CO5','CO6'].map(c =>
                        `<option value="${c}" ${(exp.co||'CO1') === c ? 'selected' : ''}>${c}</option>`
                    ).join('');
                    html += `
                        <tr class="border-b border-slate-800 hover:bg-slate-900/40 transition">
                            <td class="p-2 text-center"><input type="text" class="exp-no w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-100 font-mono text-xs md:text-sm font-bold text-center" value="${esc(exp.expt_no || ('Exp ' + (idx + 1)))}"></td>
                            <td class="p-2"><input type="text" class="exp-title w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-100 text-xs md:text-sm font-medium" value="${esc(exp.title)}" placeholder="Experiment title..."></td>
                            <td class="p-2"><input type="text" class="exp-desc w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-300 text-xs md:text-sm" value="${esc(exp.description)}" placeholder="Description / scope..."></td>
                            <td class="p-2 text-center"><select class="exp-co w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sky-400 font-bold text-xs md:text-sm text-center">${coOptions}</select></td>
                            <td class="p-2 text-center"><input type="number" min="1" max="6" class="exp-hours w-16 bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-center text-slate-100 text-xs md:text-sm font-mono font-bold" value="${exp.hours || 2}"></td>
                            <td class="p-2 text-center">
                                <button type="button" onclick="removeExperimentRow(this)" class="w-7 h-7 rounded-lg bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 inline-flex items-center justify-center transition cursor-pointer text-xs" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </td>
                        </tr>`;
                });
            }

            containers.forEach(c => {
                c.innerHTML = html;
            });
        }

        function addNewExperimentRow() {
            const containers = [
                document.getElementById('experiments-rows-container'),
                document.getElementById('experiments-rows-container-outline')
            ].filter(Boolean);

            if (containers.length === 0) return;

            // Remove any empty placeholder rows
            document.querySelectorAll('.exp-empty-placeholder, #exp-empty-row').forEach(el => el.remove());

            // Determine next count from primary container (Continuous Evaluation tab)
            const primary = document.getElementById('experiments-rows-container') || containers[0];
            const currentRows = primary.querySelectorAll('tr:not(.exp-empty-placeholder)');
            const nextIndex = currentRows.length + 1;
            const expNoVal = `Exp ${nextIndex}`;

            containers.forEach(container => {
                const tr = document.createElement('tr');
                tr.className = 'border-b border-slate-800 hover:bg-slate-900/40 transition';
                tr.innerHTML = `
                    <td class="p-2 text-center"><input type="text" class="exp-no w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-100 font-mono text-xs md:text-sm font-bold text-center" value="${expNoVal}"></td>
                    <td class="p-2"><input type="text" class="exp-title w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-100 text-xs md:text-sm font-medium" value="" placeholder="Experiment title..."></td>
                    <td class="p-2"><input type="text" class="exp-desc w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-300 text-xs md:text-sm" value="" placeholder="Description / scope..."></td>
                    <td class="p-2 text-center">
                        <select class="exp-co w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sky-400 font-bold text-xs md:text-sm text-center">
                            <option value="CO1">CO1</option><option value="CO2">CO2</option><option value="CO3">CO3</option><option value="CO4">CO4</option><option value="CO5">CO5</option><option value="CO6">CO6</option>
                        </select>
                    </td>
                    <td class="p-2 text-center"><input type="number" min="1" max="6" class="exp-hours w-16 bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-center text-slate-100 text-xs md:text-sm font-mono font-bold" value="2"></td>
                    <td class="p-2 text-center">
                        <button type="button" onclick="removeExperimentRow(this)" class="w-7 h-7 rounded-lg bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 inline-flex items-center justify-center transition cursor-pointer text-xs" title="Delete">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </td>
                `;
                container.appendChild(tr);
            });

            // Update badge count
            const totalCount = primary.querySelectorAll('tr:not(.exp-empty-placeholder)').length;
            document.querySelectorAll('.exp-count-badge').forEach(b => b.innerText = `${totalCount} experiments`);

            // Auto-focus on the newly added row's title input in the primary container (Continuous Evaluation tab)
            const newlyAddedTr = primary.querySelector('tr:last-child');
            if (newlyAddedTr) {
                const titleInput = newlyAddedTr.querySelector('.exp-title');
                if (titleInput) {
                    titleInput.focus();
                    titleInput.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }

        function removeExperimentRow(btn) {
            const tr = btn.closest('tr');
            if (!tr) return;
            const container = tr.closest('tbody');
            tr.remove();

            if (container) {
                const rows = container.querySelectorAll('tr:not(.exp-empty-placeholder)');
                if (rows.length === 0) {
                    container.innerHTML = `<tr class="exp-empty-placeholder"><td colspan="6" class="p-6 text-center text-slate-400 italic text-xs md:text-sm">No experiments loaded. Click &ldquo;Load from Syllabus&rdquo; or &ldquo;Add Experiment&rdquo; above.</td></tr>`;
                }
                const totalCount = container.querySelectorAll('tr:not(.exp-empty-placeholder)').length;
                document.querySelectorAll('.exp-count-badge').forEach(b => b.innerText = `${totalCount} experiments`);
            }
        }

        async function saveExperimentsInventory() {
            // Read from Tab 3 primary container, fallback to Tab 1
            const targetContainer = document.getElementById('experiments-rows-container') || document.getElementById('experiments-rows-container-outline');
            if (!targetContainer) return;

            const rows = [];
            targetContainer.querySelectorAll('tr:not(.exp-empty-placeholder)').forEach(tr => {
                const noInput = tr.querySelector('.exp-no');
                if (!noInput) return;
                const noVal = noInput.value.trim();
                const titleVal = tr.querySelector('.exp-title')?.value.trim() || '';
                const descVal = tr.querySelector('.exp-desc')?.value.trim() || '';
                const coVal = tr.querySelector('.exp-co')?.value || 'CO1';
                const hoursVal = parseInt(tr.querySelector('.exp-hours')?.value || 2);
                if (noVal || titleVal) {
                    rows.push({
                        expt_no: noVal || 'Exp',
                        title: titleVal,
                        description: descVal,
                        co: coVal,
                        hours: hoursVal
                    });
                }
            });

            if (rows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty List',
                    text: 'Please add at least one experiment before saving.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            localExperiments = rows;
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/experiments`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiments: localExperiments })
                });
                const data = await res.json();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Experiments list saved successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
                loadExperimentsInventoryTable();
                initExperimentSelect();
                document.querySelectorAll('.exp-count-badge').forEach(b => b.innerText = `${localExperiments.length} experiments`);
            } catch (err) {
                console.error('Error saving experiments inventory:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: 'Could not save experiments list to server. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }

        // Lesson Planner: Row Insert (+), Row Delete (X), & Debounced Autosave
        let _lpAutoSaveTimer = null;
        function triggerLpAutoSave() {
            const indicator = document.getElementById('lp-autosave-indicator');
            if (indicator) indicator.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Saving...';

            clearTimeout(_lpAutoSaveTimer);
            _lpAutoSaveTimer = setTimeout(() => {
                saveLessonPlannerBulk(true);
            }, 800);
        }

        function onActualDateChange(input) {
            const row = input.closest('tr');
            if (row && input.value) {
                const statusSel = row.querySelector('.lp-status');
                if (statusSel && statusSel.value === 'Pending') {
                    statusSel.value = 'Completed';
                    statusSel.className = 'lp-status w-full bg-slate-950 border border-slate-800 rounded px-1.5 py-1 text-xs font-bold text-emerald-400';
                }
            }
            triggerLpAutoSave();
        }

        function resequenceLessonPlanRows() {
            document.querySelectorAll('#lesson-plan-rows-container tr.lesson-plan-row').forEach((tr, idx) => {
                const cell = tr.querySelector('.lp-day-no-cell');
                if (cell) cell.innerText = idx + 1;
            });
        }

        window._newLpSeq = window._newLpSeq || 0;

        function addR26LessonPlanRow(afterBtn = null) {
            const tbody = document.getElementById('lesson-plan-rows-container');
            if (!tbody) return;

            const emptyRow = document.getElementById('lp-empty-row');
            if (emptyRow) emptyRow.remove();

            const refRow = (afterBtn && typeof afterBtn.closest === 'function') ? afterBtn.closest('tr') : null;
            const tempId = 'new_' + Date.now() + '_' + (++window._newLpSeq);

            const defaultCo = refRow ? (refRow.querySelector('.lp-co')?.value || 'CO1') : 'CO1';
            const defaultHours = refRow ? (refRow.querySelector('.lp-hours')?.value || 1) : 1;
            const defaultBatch = refRow ? (refRow.querySelector('.lp-sub-batch')?.value || 'Full Batch') : 'Full Batch';

            const tr = document.createElement('tr');
            tr.className = 'lesson-plan-row border-b border-slate-800/60 hover:bg-slate-900/40 transition';
            tr.setAttribute('data-id', tempId);
            tr.setAttribute('data-sub-batch', defaultBatch);

            tr.innerHTML = `
                <td class="py-2 px-1.5 text-center font-mono font-bold text-slate-200 text-xs md:text-sm lp-day-no-cell">...</td>
                <td class="p-1.5 text-center">
                    <select class="lp-sub-batch bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-sky-400 font-bold focus:outline-none w-full text-xs md:text-sm" onchange="triggerLpAutoSave()">
                        <option value="Full Batch" ${defaultBatch === 'Full Batch' ? 'selected' : ''}>Full Batch</option>
                        <option value="Batch 1" ${defaultBatch === 'Batch 1' ? 'selected' : ''}>Batch 1</option>
                        <option value="Batch 2" ${defaultBatch === 'Batch 2' ? 'selected' : ''}>Batch 2</option>
                    </select>
                </td>
                <td class="p-1.5 text-center">
                    <select class="lp-co bg-slate-950 border border-slate-800 rounded-lg px-1.5 py-1 text-amber-300 font-bold focus:outline-none w-full text-xs md:text-sm text-center" onchange="triggerLpAutoSave()">
                        <option value="CO1" ${defaultCo === 'CO1' ? 'selected' : ''}>CO1</option>
                        <option value="CO2" ${defaultCo === 'CO2' ? 'selected' : ''}>CO2</option>
                        <option value="CO3" ${defaultCo === 'CO3' ? 'selected' : ''}>CO3</option>
                        <option value="CO4" ${defaultCo === 'CO4' ? 'selected' : ''}>CO4</option>
                        <option value="CO5" ${defaultCo === 'CO5' ? 'selected' : ''}>CO5</option>
                        <option value="CO6" ${defaultCo === 'CO6' ? 'selected' : ''}>CO6</option>
                    </select>
                </td>
                <td class="p-1.5 text-center">
                    <input type="number" min="1" max="6" value="${defaultHours}" class="lp-hours w-16 bg-slate-950 border border-slate-800 rounded-lg px-1.5 py-1 text-slate-100 text-center text-xs md:text-sm font-mono font-bold" onchange="triggerLpAutoSave()">
                </td>
                <td class="p-1.5">
                    <textarea rows="1" placeholder="Enter topic content..." class="lp-topic w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1 text-slate-100 text-xs md:text-sm font-medium focus:border-blue-500 outline-none resize-none leading-relaxed overflow-hidden" oninput="autoGrowTextarea(this); triggerLpAutoSave()"></textarea>
                </td>
                <td class="p-1.5">
                    <input type="date" value="" class="lp-proposed w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-200 text-xs md:text-sm font-mono font-medium" onchange="triggerLpAutoSave()">
                </td>
                <td class="p-1.5">
                    <input type="date" value="" class="lp-actual w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-emerald-400 text-xs md:text-sm font-mono font-bold" onchange="onActualDateChange(this)">
                </td>
                <td class="p-1.5">
                    <select class="lp-pedagogy w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-slate-200 text-xs md:text-sm font-medium" onchange="triggerLpAutoSave()">
                        <option value="Practical">Practical</option>
                        <option value="Demonstration">Demonstration</option>
                        <option value="Simulation">Simulation</option>
                        <option value="Lecture">Lecture</option>
                        <option value="Exam">Exam</option>
                    </select>
                </td>
                <td class="p-1.5 text-center">
                    <select class="lp-status w-full bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs md:text-sm font-bold text-amber-400" onchange="triggerLpAutoSave()">
                        <option value="Pending" class="text-amber-400">Pending</option>
                        <option value="Completed" class="text-emerald-400">Completed</option>
                    </select>
                </td>
                <td class="p-1.5 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <button type="button" onclick="addR26LessonPlanRow(this)" class="w-7 h-7 flex items-center justify-center rounded-lg bg-sky-500/10 hover:bg-sky-500/25 border border-sky-500/30 text-sky-400 hover:text-sky-300 transition text-xs font-bold leading-none cursor-pointer" title="Insert row below">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                        <button type="button" onclick="deleteR26LessonPlanRow(this, '${tempId}')" class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-500/10 hover:bg-rose-500/25 border border-rose-500/30 text-rose-400 hover:text-rose-300 transition text-xs font-bold leading-none cursor-pointer" title="Delete row">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>
                </td>
            `;

            if (refRow && refRow.parentNode === tbody) {
                refRow.insertAdjacentElement('afterend', tr);
            } else {
                tbody.appendChild(tr);
            }

            resequenceLessonPlanRows();
            const input = tr.querySelector('.lp-topic');
            if (input) input.focus();
            triggerLpAutoSave();
        }

        function autoGrowTextarea(el) {
            if (!el) return;
            el.style.height = 'auto';
            el.style.height = (el.scrollHeight) + 'px';
        }

        async function deleteR26LessonPlanRow(btn, lpId) {
            const tr = btn.closest('tr');
            if (!tr) return;

            if (lpId && !String(lpId).startsWith('new_')) {
                if (!confirm('Delete this lesson plan row?')) return;
                try {
                    await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plans/${lpId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                } catch(e) {
                    console.error(e);
                }
            }
            tr.remove();
            resequenceLessonPlanRows();
            triggerLpAutoSave();
        }

        async function saveLessonPlannerBulk(isAuto = false) {
            resequenceLessonPlanRows();
            const plans = {};
            let index = 1;
            document.querySelectorAll('#lesson-plan-rows-container tr.lesson-plan-row').forEach(row => {
                const id = row.getAttribute('data-id');
                plans[id] = {
                    day_no: index++,
                    sub_batch: row.querySelector('.lp-sub-batch') ? row.querySelector('.lp-sub-batch').value : 'Full Batch',
                    proposed_date: row.querySelector('.lp-proposed')?.value || null,
                    actual_date: row.querySelector('.lp-actual')?.value || null,
                    topic_content: row.querySelector('.lp-topic')?.value || '',
                    co_id: row.querySelector('.lp-co')?.value || 'CO1',
                    allocated_hours: parseInt(row.querySelector('.lp-hours')?.value || 1),
                    pedagogy: row.querySelector('.lp-pedagogy')?.value || 'Practical',
                    status: row.querySelector('.lp-status')?.value || 'Pending'
                };
            });

            const btn = document.getElementById('btnSavePlan');
            if (!isAuto && btn) {
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Saving...';
                btn.disabled = true;
            }

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plans/bulk-update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();

                // If new rows were created, update their client DOM IDs to real database IDs
                if (data.id_map) {
                    for (const [tempId, realId] of Object.entries(data.id_map)) {
                        const row = document.querySelector(`tr[data-id="${tempId}"]`);
                        if (row) {
                            row.setAttribute('data-id', realId);
                            const delBtn = row.querySelector('button[title="Delete row"]');
                            if (delBtn) delBtn.setAttribute('onclick', `deleteR26LessonPlanRow(this, '${realId}')`);
                        }
                    }
                }

                if (!isAuto) {
                    if (btn) {
                        btn.innerHTML = '<i class="fa-solid fa-check text-xs"></i> Saved!';
                        setTimeout(() => {
                            btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save Plan';
                            btn.disabled = false;
                        }, 1800);
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Lesson plan saved successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            } catch(e) {
                if (!isAuto && btn) {
                    btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Save Plan';
                    btn.disabled = false;
                    alert("Failed to save lesson plan.");
                }
            }
        }

        async function generateLessonTimeline() {
            const mode = document.getElementById('lesson_planner_mode').value;
            const batchEl = document.getElementById('lesson_planner_batch');
            const batch = batchEl ? batchEl.value : 'Full';
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: mode, batch: batch })
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                }
            } catch(e) {
                alert("Failed to generate plan.");
            }
        }

        // Touch Grading Modal
        function stepSlider(key, val) {
            const slider = document.getElementById(`slider-${key}`);
            if (!slider) return;
            let target = parseFloat(slider.value) + val;
            target = Math.max(parseFloat(slider.min), Math.min(parseFloat(slider.max), target));
            slider.value = target;
            slider.dispatchEvent(new Event('input'));
        }

        function openGradingModal(regNo, type) {
            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            gradingModalContext = type;
            currentStudentIndex = studentList.findIndex(s => s.reg_no === regNo);

            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.reg_no;

            const container = document.getElementById('modalSlidersContainer');
            container.innerHTML = '';

            let rubrics = [];
            if (type === 'table22') {
                rubrics = [
                    { label: '1. Prep & Punctuality (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Procedure (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Observation & Recording (Max 5)', key: 'c3', max: 5, step: 0.5 },
                    { label: '4. Analysis & Outcomes (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Viva Voce (Max 10)', key: 'c5', max: 10, step: 0.5 },
                    { label: '6. Workmanship & Safety (Max 5)', key: 'c6', max: 5, step: 0.5 }
                ];
            } else if (type === 'table23') {
                rubrics = [
                    { label: '1. Idea & Relevance (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Plan & Objectives (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Execution & Safety (Max 10)', key: 'c3', max: 10, step: 0.5 },
                    { label: '4. Analysis & Results (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Teamwork & Creativity (Max 10)', key: 'c5', max: 10, step: 0.5 }
                ];
            } else if (type === 'table31') {
                rubrics = [
                    { label: '1. Procedure Write-up (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Execution (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Output Results (Max 8)', key: 'c3', max: 8, step: 0.5 },
                    { label: '4. Viva Voce (Max 8)', key: 'c4', max: 8, step: 0.5 },
                    { label: '5. Record Book (Max 4)', key: 'c5', max: 4, step: 0.5 }
                ];
            }

            const stateScores = type === 'table31'
                ? (scoresState[type][regNo][document.getElementById('series_no')?.value || 'Series 1'] || {})
                : (scoresState[type][regNo] || {});

            rubrics.forEach(r => {
                const val = stateScores[r.key] || 0;
                const html = `
                    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-700">
                        <div class="flex justify-between font-bold text-sm mb-2">
                            <span class="text-slate-200 text-sm md:text-base font-semibold">${r.label}</span>
                            <span class="text-emerald-400 font-mono font-black text-base" id="modal-val-${r.key}">${val}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="stepSlider('${r.key}', -${r.step})" class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 text-white font-bold cursor-pointer text-base">-</button>
                            <input type="range" id="slider-${r.key}" min="0" max="${r.max}" step="${r.step}" value="${val}" oninput="syncModalSlider('${regNo}', '${r.key}', '${type}')" class="flex-grow h-2 bg-slate-700 rounded-lg cursor-pointer">
                            <button onclick="stepSlider('${r.key}', ${r.step})" class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 text-white font-bold cursor-pointer text-base">+</button>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });

            updateModalTotal(regNo, type);
            document.getElementById('gradingModal').classList.remove('hidden');
            document.getElementById('gradingModal').classList.add('flex');
        }

        function syncModalSlider(regNo, key, type) {
            const val = parseFloat(document.getElementById(`slider-${key}`).value) || 0;
            document.getElementById(`modal-val-${key}`).innerText = val;

            if (type === 'table31') {
                const sNo = document.getElementById('series_no')?.value || 'Series 1';
                scoresState[type][regNo][sNo][key] = val;
            } else {
                scoresState[type][regNo][key] = val;
            }
            updateModalTotal(regNo, type);
        }

        function updateModalTotal(regNo, type) {
            let total = 0;
            if (type === 'table31') {
                const sNo = document.getElementById('series_no')?.value || 'Series 1';
                const s = scoresState[type][regNo][sNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                const sp = document.getElementById(`score-text-series-${regNo}`);
                if (sp) sp.innerText = `${total.toFixed(2)} / 40`;
            } else if (type === 'table23') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                const sp = document.getElementById(`score-text-open-${regNo}`);
                if (sp) sp.innerText = `${total.toFixed(2)} / 50`;
            } else if (type === 'table22') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0) + (s.c6||0);
                const sp = document.getElementById(`score-text-exp-${regNo}`);
                if (sp) sp.innerText = `${total.toFixed(2)} / 50`;
            }

            document.getElementById('modalTotalScore').innerText = total.toFixed(2);
            recalculateCIA(regNo);
        }

        function navigateStudent(direction) {
            let nextIdx = currentStudentIndex + direction;
            if (nextIdx >= studentList.length) nextIdx = 0;
            if (nextIdx < 0) nextIdx = studentList.length - 1;

            const nextStud = studentList[nextIdx];
            const row = document.querySelector(`.student-row[data-reg-no="${nextStud.reg_no}"]`);
            if (row && row.classList.contains('hidden')) {
                currentStudentIndex = nextIdx;
                navigateStudent(direction);
                return;
            }

            closeGradingModal();
            openGradingModal(nextStud.reg_no, gradingModalContext);
        }

        function closeGradingModal() {
            document.getElementById('gradingModal').classList.add('hidden');
            document.getElementById('gradingModal').classList.remove('flex');
        }

        // Submissions
        async function submitExpMarks() {
            const expNoVal = document.getElementById('exp_no').value;
            const titleVal = document.getElementById('exp_title').value;
            const marks = scoresState.table22;

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/experiment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiment_no: expNoVal, title: titleVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Daywork log saved successfully!');
            } catch(e) {
                alert("Failed to save continuous log.");
            }
        }

        async function submitOpenEndedMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = {
                    title: document.getElementById(`open-title-${reg}`)?.value || 'Open-ended Project',
                    ...scoresState.table23[reg]
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/open-ended`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Open-ended marks saved successfully!');
            } catch(e) {
                alert("Failed to save open-ended marks.");
            }
        }

        async function submitSeriesMarks() {
            const sNoVal = document.getElementById('series_no')?.value || 'Series 1';
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = scoresState.table31[reg][sNoVal] || { c1:0, c2:0, c3:0, c4:0, c5:0 };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/series`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ series_no: sNoVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Series marks saved successfully!');
            } catch(e) {
                alert("Failed to save series marks.");
            }
        }

        // Series QP config
        const seriesQpConfigs = @json($seriesExams);

        function loadSeriesQpConfig(examName) {
            const conf = seriesQpConfigs[examName] || {};
            document.getElementById('series_qp_max_marks').value = conf.max_marks || 40;
            document.getElementById('series_qp_duration').value = conf.duration_minutes || 120;
            document.getElementById('series_qp_outline').value = typeof conf.question_outline === 'string' 
                ? conf.question_outline 
                : (conf.question_outline ? JSON.stringify(conf.question_outline, null, 2) : '');

            const coChecks = document.querySelectorAll('#series_qp_co_checks .co-checkbox');
            const activeCos = conf.co_tags || [];
            coChecks.forEach(chk => {
                chk.checked = activeCos.includes(chk.value);
            });
        }

        async function saveSeriesQpOutline() {
            const examName = document.getElementById('series_qp_select').value;
            const maxMarks = parseInt(document.getElementById('series_qp_max_marks').value || 40);
            const duration = parseInt(document.getElementById('series_qp_duration').value || 120);
            const outline = document.getElementById('series_qp_outline').value;

            const coTags = [];
            document.querySelectorAll('#series_qp_co_checks .co-checkbox').forEach(chk => {
                if (chk.checked) coTags.push(chk.value);
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/series-exams/configure`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        exam_name: examName,
                        max_marks: maxMarks,
                        duration_minutes: duration,
                        question_outline: outline,
                        co_tags: coTags
                    })
                });
                const data = await res.json();
                alert(data.message || 'Blueprint saved!');
            } catch(e) {
                alert("Failed to save blueprint.");
            }
        }

        function switchSeriesExam(val) {
            studentList.forEach(s => {
                const reg = s.reg_no;
                const sp = document.getElementById(`score-text-series-${reg}`);
                if (sp) {
                    const score = sp.getAttribute(val === 'Series 1' ? 'data-s1' : 'data-s2') || '0.00';
                    sp.innerText = `${parseFloat(score).toFixed(2)} / 40`;
                }
            });
        }

        async function manageSurvey(type, action) {
            const endpoint = `/api/r26/classroom/${subjectId}/${type === 'mid' ? 'midsem' : 'exit'}-survey/${action}`;
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                alert(data.message || 'Survey state updated successfully!');
            } catch(e) {
                alert("Failed to update survey.");
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            initExperimentSelect();
            loadExperimentsInventoryTable();
            filterLabBatch('All');
            document.querySelectorAll('.lp-topic').forEach(ta => {
                autoGrowTextarea(ta);
            });
        });
    </script>
    @include('partials.carmie_assistant')
</body>
</html>
