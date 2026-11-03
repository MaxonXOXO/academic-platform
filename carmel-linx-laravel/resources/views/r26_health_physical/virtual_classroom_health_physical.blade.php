<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[R-2026] Health & Physical Education - {{ $hpCourseFile->course_title ?? 'Health and Physical Education' }}</title>
    
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
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
            box-shadow: none !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .glass-panel {
            background: rgba(2, 6, 23, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(51, 65, 85, 0.6);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.90);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(51, 65, 85, 0.6);
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
            box-shadow: none !important;
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

        .growable-textarea {
            resize: none;
            overflow: hidden;
            min-height: 38px;
            line-height: 1.35;
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

        .mark-entry-main {
            font-size: 1.15rem !important;
            font-weight: 900 !important;
            padding: 0.4rem 0.5rem !important;
            text-align: center !important;
        }

        .mark-entry-sub {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            padding: 0.3rem 0.4rem !important;
            text-align: center !important;
        }

        .student-name-lg {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em;
        }

        .reg-no-lg {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
        }

        /* Fixed table styling */
        table th {
            font-size: 0.75rem !important;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen antialiased">

    <!-- 1. TOP HEADER BAR -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-800/80 px-4 md:px-8 py-3.5">
        <div class="max-w-[99%] mx-auto flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <a href="{{ $dashboardUrl ?? '/dashboard/lecturer' }}" class="flex items-center gap-2 shrink-0 no-underline text-white group" title="Return to Dashboard">
                    <span class="material-symbols-rounded text-sky-400 text-xl group-hover:scale-105 transition-transform">school</span>
                    <span class="font-extrabold text-white text-base tracking-tight group-hover:text-sky-300 transition-colors">Carmel Linx</span>
                    <span class="text-slate-600 font-bold">|</span>
                </a>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h1 class="text-lg md:text-xl font-bold font-display tracking-tight text-white">
                            {{ $hpCourseFile->course_title ?? 'Health and Physical Education' }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            REV 2026 • S1 Unique Paper
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Course Code: <span class="font-mono text-slate-200 font-bold">{{ $batchSubject->subject_code ?: '1009' }}</span> | 
                        Semester: <span class="text-sky-400 font-bold">{{ $hpCourseFile->semester ?? 'I' }}</span> | 
                        Contact Hours: <span class="text-slate-200 font-bold">30 Hours</span> | 
                        Credits: <span class="text-slate-200 font-bold">1.0</span> | 
                        <span class="text-emerald-400 font-bold">60 CIE + 40 ESE = 100 Marks</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Class Switcher -->
                @if(isset($allHpClasses) && $allHpClasses->count() > 1)
                <div class="flex items-center space-x-2 bg-slate-900/90 border border-slate-800 rounded-xl px-3 py-1.5 shadow-sm">
                    <span class="text-xs text-sky-400 font-semibold flex items-center gap-1">
                        <i class="fa-solid fa-layer-group"></i> Class:
                    </span>
                    <select onchange="if(this.value) window.location.href='/r26/classroom/health-physical/' + this.value" class="bg-transparent text-xs text-white font-bold font-mono focus:outline-none cursor-pointer">
                        @foreach($allHpClasses as $cls)
                            <option value="{{ $cls->id }}" class="bg-slate-900 text-white" {{ $cls->id == $batchSubject->id ? 'selected' : '' }}>
                                {{ explode('_', $cls->classroom_id ?? '')[0] }} ({{ $cls->subject_code ?: '1009' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Assigned Faculty Badge -->
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs shadow-sm">
                    <span class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-[10px]">
                        <i class="fa-solid fa-user-tie"></i>
                    </span>
                    <div class="text-left">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 block leading-tight font-semibold">Faculty</span>
                        <span class="font-bold text-slate-200 leading-tight block text-xs">
                            {{ $assignedStaff->count() ? $assignedStaff->pluck('name')->implode(', ') : (Session::get('userName') ?? 'Jaick Joseph') }}
                        </span>
                    </div>
                </div>

                <!-- Global Auto-Save Status Badge -->
                <span id="globalSaveStatusBadge" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5 transition-all shadow-sm">
                    <span class="material-symbols-rounded text-sm">cloud_done</span>
                    <span>All changes saved</span>
                </span>

                <!-- Return to Dashboard -->
                <a href="{{ $dashboardUrl ?? '/dashboard/lecturer' }}" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 hover:text-white font-bold text-xs transition border border-rose-500/30 flex items-center space-x-1.5 no-underline shadow-sm" title="Return to Dashboard">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. MAIN ARCHED TABBED NAVIGATION (7 EXACT TABS) -->
    <main class="max-w-[99%] mx-auto px-4 md:px-8 mt-4">

        <div class="tab-nav-container bg-slate-950/90 border border-slate-800/80 p-2.5 rounded-2xl shadow-lg mb-4">
            <nav class="flex flex-wrap items-center gap-1.5">

                <!-- 1. Course Outline -->
                <button onclick="switchTab('outline')" id="tab-btn-outline" class="subtab-btn active px-3.5 py-2 text-xs font-bold border-t-2 border-x-2 border-b-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">menu_book</span>
                    <span>Course Outline</span>
                </button>

                <!-- 2. Lesson Plan -->
                <button onclick="switchTab('lessonplan')" id="tab-btn-lessonplan" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">calendar_month</span>
                    <span>Lesson Plan</span>
                </button>

                <!-- 3. Continuous Evaluation -->
                <button onclick="switchTab('continuous')" id="tab-btn-continuous" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">assignment_turned_in</span>
                    <span>Continuous Evaluation (50M)</span>
                </button>

                <!-- 4. Series Test -->
                <button onclick="switchTab('seriestest')" id="tab-btn-seriestest" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">edit_note</span>
                    <span>Series Test</span>
                    <span class="px-1.5 py-0.5 bg-amber-500/20 text-amber-300 text-[10px] rounded font-mono font-bold">15M Support</span>
                </button>

                <!-- 5. CIE Register -->
                <button onclick="switchTab('cieregister')" id="tab-btn-cieregister" class="subtab-btn px-3.5 py-2 text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px">
                    <span class="material-symbols-rounded text-sm">fact_check</span>
                    <span>CIE Register (60M)</span>
                </button>

                <!-- 6. Attainment & Survey -->
                <button onclick="switchTab('attainment')" id="tab-btn-attainment" class="subtab-btn px-3.5 py-2 text-xs font-bold text-purple-300 hover:text-purple-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px bg-purple-950/20">
                    <span class="material-symbols-rounded text-sm text-purple-400">analytics</span>
                    <span>Attainment & Survey</span>
                    <span class="px-1.5 py-0.5 bg-purple-500/30 text-purple-200 text-[10px] rounded font-mono font-bold">CO-PO</span>
                </button>

                <!-- 7. Reports -->
                <button onclick="switchTab('reports')" id="tab-btn-reports" class="subtab-btn px-3.5 py-2 text-xs font-bold text-emerald-300 hover:text-emerald-200 border-t-2 border-x-2 border-b-transparent border-transparent rounded-t-xl flex items-center gap-1.5 transition-all cursor-pointer whitespace-nowrap -mb-px bg-emerald-950/20">
                    <span class="material-symbols-rounded text-sm text-emerald-400">print</span>
                    <span>Reports</span>
                    <span class="px-1.5 py-0.5 bg-emerald-500/30 text-emerald-200 text-[10px] rounded font-mono font-bold">A4 Print</span>
                </button>

            </nav>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 1: COURSE OUTLINE                                                     -->
        <!-- ========================================================================= -->
        <div id="tab-content-outline" class="tab-pane-content space-y-5">

            <!-- Official Assessment Scheme Table -->
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-slate-800 pb-3 gap-2">
                    <h3 class="font-bold text-sm text-slate-200 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base text-slate-400">table_chart</span>
                        <span>Official Assessment Methodology — Lab (1009: Health and Physical Education — Rev 2026)</span>
                    </h3>
                    <div class="flex items-center gap-2">
                        @if(!empty($hpCourseFile->syllabus_pdf_path))
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/syllabus/view" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 transition no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                            <span>View Syllabus PDF</span>
                        </a>
                        @endif
                        <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center gap-1.5 transition shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">upload_file</span>
                            <span>{{ !empty($hpCourseFile->syllabus_pdf_path) ? 'Re-upload Syllabus' : 'Upload Syllabus' }}</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-center border border-slate-800 border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 font-bold uppercase">
                                <th class="border border-slate-800 p-2.5">Mode</th>
                                <th class="border border-slate-800 p-2.5">Attendance</th>
                                <th class="border border-slate-800 p-2.5">Continuous Evaluation (CE)</th>
                                <th class="border border-slate-800 p-2.5">Series Test 1 (CA1)</th>
                                <th class="border border-slate-800 p-2.5">Series Test 2 (CA2)</th>
                                <th class="border border-slate-800 p-2.5 bg-sky-950/40 text-sky-300">Total CIE</th>
                                <th class="border border-slate-800 p-2.5">End Sem Exam (ESE)</th>
                                <th class="border border-slate-800 p-2.5 bg-emerald-950/40 text-emerald-300 font-extrabold">Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-slate-300">
                                <td class="border border-slate-800 p-2 font-semibold text-slate-400">Exam Mark</td>
                                <td class="border border-slate-800 p-2 font-mono">-</td>
                                <td class="border border-slate-800 p-2 font-mono font-bold text-amber-300">50 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono">40 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono">40 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono font-bold text-sky-400 bg-sky-950/20">-</td>
                                <td class="border border-slate-800 p-2 font-mono font-bold text-slate-200">40 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono font-bold text-emerald-400 bg-emerald-950/20">-</td>
                            </tr>
                            <tr class="text-white font-bold bg-slate-900/60">
                                <td class="border border-slate-800 p-2 font-semibold text-slate-400">CIE Weightage</td>
                                <td class="border border-slate-800 p-2 font-mono text-cyan-300">5 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono text-amber-300">25 Marks <span class="text-[10px] text-slate-400 block font-normal">(Scaled from 50M)</span></td>
                                <td class="border border-slate-800 p-2 font-mono text-purple-300">15 Marks <span class="text-[10px] text-slate-400 block font-normal">(Conv / 15M Direct)</span></td>
                                <td class="border border-slate-800 p-2 font-mono text-purple-300">15 Marks <span class="text-[10px] text-slate-400 block font-normal">(Converted to 15M)</span></td>
                                <td class="border border-slate-800 p-2 font-mono font-extrabold text-sky-300 bg-sky-950/40 text-sm">60 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono text-slate-200">40 Marks</td>
                                <td class="border border-slate-800 p-2 font-mono font-extrabold text-emerald-300 bg-emerald-950/40 text-sm">100 Marks</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Course Info Box -->
                <div class="glass-card p-5 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3.5 flex items-center">
                            <span class="material-symbols-rounded text-sky-400 text-base mr-2">info</span>
                            <span>Course Specifications</span>
                        </h3>
                        <dl class="space-y-2.5 text-xs">
                            <div class="flex justify-between py-1.5 border-b border-slate-800">
                                <dt class="text-slate-400">Course Code</dt>
                                <dd class="font-mono text-sky-300 font-bold">{{ $batchSubject->subject_code ?: ($hpCourseFile->course_code ?? '1009') }}</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-800">
                                <dt class="text-slate-400">Teaching Scheme (L:T:P:R)</dt>
                                <dd class="font-mono text-slate-200">0:0:2:0</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-800">
                                <dt class="text-slate-400">Total Instructional Hours</dt>
                                <dd class="font-bold text-white">30 Hours</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-800">
                                <dt class="text-slate-400">Credits</dt>
                                <dd class="font-bold text-sky-400">1.0</dd>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-slate-800">
                                <dt class="text-slate-400">Continuous Internal Eval (CIE)</dt>
                                <dd class="font-bold text-sky-300">60 Marks</dd>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <dt class="text-slate-400">End Semester Exam (ESE)</dt>
                                <dd class="font-bold text-emerald-400">40 Marks</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-800">
                        <span class="text-[11px] text-slate-400 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-emerald-400 text-sm">verified</span>
                            <span>Syllabus verified: Revision 2026 Diploma Curriculum</span>
                        </span>
                    </div>
                </div>

                <!-- Continuous Evaluation Criteria from Syllabus PDF -->
                <div class="lg:col-span-2 glass-card p-5 border border-slate-800">
                    <div class="flex items-center justify-between mb-3.5">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                            <span class="material-symbols-rounded text-sky-400 text-base mr-2">tune</span>
                            <span>Continuous Evaluation Criteria (Extracted from Syllabus)</span>
                        </h3>
                        <span class="text-xs bg-sky-500/20 text-sky-300 px-2.5 py-0.5 rounded-full font-bold border border-sky-500/30">
                            Total: 50 Marks
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($evalScheme['day_work'] as $crit)
                        <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-sky-400 font-mono uppercase">{{ strtoupper($crit['key']) }}</span>
                                <h4 class="text-xs font-semibold text-slate-200 mt-0.5">{{ $crit['title'] }}</h4>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-sky-950 text-sky-300 border border-sky-800/50 shrink-0">
                                {{ $crit['max_marks'] }} Marks
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Course Outcomes Table -->
            <div class="glass-card p-5 border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">school</span>
                    <span>Course Outcomes (COs)</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800 font-bold">
                                <th class="p-2.5 w-20">CO ID</th>
                                <th class="p-2.5">Course Outcome Description</th>
                                <th class="p-2.5 w-32 text-center">Cognitive Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($hpCourseFile->parsed_cos as $co)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-2.5 font-bold font-mono text-sky-400">{{ $co['id'] }}</td>
                                <td class="p-2.5 text-slate-300">{{ $co['description'] }}</td>
                                <td class="p-2.5 text-center font-medium">
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-200 text-xs font-semibold">
                                        {{ $co['cognitive_level'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CO-PO Articulation Matrix -->
            <div class="glass-card p-5 border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">grid_on</span>
                    <span>CO-PO Articulation Matrix</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-slate-800 border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 font-bold border-b border-slate-800">
                                <th class="p-2.5 text-left">CO / PO</th>
                                @for($p=1; $p<=11; $p++)
                                <th class="p-2 text-center">PO{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                            <tr>
                                <td class="p-2.5 font-bold font-mono text-sky-400 bg-slate-900/50">{{ $coTag }}</td>
                                @for($p=1; $p<=11; $p++)
                                @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                <td class="p-2 text-center font-mono font-semibold {{ $val !== '-' ? 'text-sky-300 bg-sky-950/20' : 'text-slate-600' }}">
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

        <!-- ========================================================================= -->
        <!-- TAB 2: LESSON PLAN (30-HOUR SCHEDULE WITH INLINE AUTOSAVE)                 -->
        <!-- ========================================================================= -->
        <div id="tab-content-lessonplan" class="tab-pane-content space-y-5 hidden">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-slate-800 pb-3 gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-rounded text-sky-400 text-base">calendar_month</span>
                            <span>30-Hour Physical Activity Schedule & Lesson Planner</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Editable table with instant debounced autosave. All 30 hours mapped to CO1–CO4 as per syllabus.</p>
                    </div>
                    <div class="flex items-center space-x-2 shrink-0">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex items-center space-x-1.5 no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Schedule</span>
                        </a>
                        <button type="button" onclick="saveLessonPlanManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Planner</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse" id="lessonPlanTable">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800 font-bold">
                                <th class="p-2.5 w-16 text-center">Hour</th>
                                <th class="p-2.5 w-24 text-center">CO Tag</th>
                                <th class="p-2.5">Topic / Physical Activity Description</th>
                                <th class="p-2.5 w-36 text-center">Proposed Date</th>
                                <th class="p-2.5 w-36 text-center">Actual Date</th>
                                <th class="p-2.5 w-28 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800" id="lessonPlanTableBody">
                            @foreach($lessonPlans as $lp)
                            @php $isTest = str_contains(strtolower($lp->topic_content), 'series test'); @endphp
                            <tr class="lesson-row hover:bg-slate-900/50 {{ $isTest ? 'bg-sky-950/20' : '' }}" data-lp-id="{{ $lp->id }}">
                                <td class="p-2.5 text-center font-bold font-mono text-sky-400">#{{ $lp->day_no }}</td>
                                <td class="p-2.5 text-center">
                                    <select id="co_{{ $lp->id }}" onchange="queueLessonPlanAutoSave({{ $lp->id }})" class="w-20 px-2 py-1 text-xs font-bold font-mono text-sky-300 bg-slate-900 border border-slate-700 rounded-lg">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coOpt)
                                            <option value="{{ $coOpt }}" {{ ($lp->co_id ?? $lp->co_tag) == $coOpt ? 'selected' : '' }}>{{ $coOpt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2.5">
                                    <textarea id="topic_{{ $lp->id }}" oninput="autoGrow(this); queueLessonPlanAutoSave({{ $lp->id }});" class="growable-textarea w-full px-2.5 py-1.5 text-xs text-slate-200 bg-slate-900 border border-slate-700 rounded-lg" rows="1">{{ $lp->topic_content }}</textarea>
                                </td>
                                <td class="p-2.5 text-center">
                                    <input type="text" id="pdate_{{ $lp->id }}" value="{{ $lp->proposed_date ? \Carbon\Carbon::parse($lp->proposed_date)->format('d/m/Y') : '' }}" onchange="queueLessonPlanAutoSave({{ $lp->id }})" placeholder="DD/MM/YYYY" class="hp-datepicker w-32 px-2 py-1 text-xs text-center font-mono text-slate-300 bg-slate-900 border border-slate-700 rounded-lg">
                                </td>
                                <td class="p-2.5 text-center">
                                    <input type="text" id="adate_{{ $lp->id }}" value="{{ $lp->actual_date ? \Carbon\Carbon::parse($lp->actual_date)->format('d/m/Y') : '' }}" onchange="queueLessonPlanAutoSave({{ $lp->id }})" placeholder="DD/MM/YYYY" class="hp-datepicker w-32 px-2 py-1 text-xs text-center font-mono text-emerald-300 bg-slate-900 border border-slate-700 rounded-lg">
                                </td>
                                <td class="p-2.5 text-center">
                                    <select id="status_{{ $lp->id }}" onchange="queueLessonPlanAutoSave({{ $lp->id }})" class="w-24 px-2 py-1 text-xs font-bold {{ $lp->status === 'Completed' ? 'text-emerald-400' : 'text-amber-400' }} bg-slate-900 border border-slate-700 rounded-lg">
                                        <option value="Pending" {{ $lp->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Completed" {{ $lp->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                    <span class="text-xs text-slate-400">Total Instructional Sessions: <strong class="text-white">{{ $lessonPlans->count() }} Hours</strong></span>
                    <button type="button" onclick="saveLessonPlanManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save Lesson Planner</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: CONTINUOUS EVALUATION (50M SINGLE FIELD ENTRY - NO SLIDERS)        -->
        <!-- ========================================================================= -->
        <div id="tab-content-continuous" class="tab-pane-content space-y-5 hidden">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-slate-800 pb-3 gap-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-rounded text-sky-400 text-base">assignment_turned_in</span>
                                <span>Continuous Fitness & Activity Evaluation (50 Marks)</span>
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Single 50M Entry • Auto-Split Active
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Enter the whole total mark out of 50 in the <strong>Total (50M)</strong> field. It instantly auto-splits across all criteria, <strong class="text-amber-300">giving preference / full mark to the Record Book</strong>. All criteria fields remain individually editable whole integers!
                        </p>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/activity-log" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex items-center space-x-1.5 no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Marksheet</span>
                        </a>
                        <button type="button" onclick="saveContinuousEvaluationManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Continuous Marks</span>
                        </button>
                    </div>
                </div>

                <!-- Info Alert Card: Clean Entry Workflow -->
                <div class="p-3 rounded-xl bg-slate-900/90 border border-sky-500/30 mb-4 flex items-center justify-between flex-wrap gap-2 text-xs">
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="material-symbols-rounded text-sky-400 text-lg">lightbulb</span>
                        <span>
                            <strong>Speed Tip for Faculty:</strong> Enter student total mark (e.g. <span class="font-bold text-amber-300">45</span>) directly into the Total (50M) box. The system automatically splits it into whole numbers [<span class="text-sky-300">c1: 9, c2: 12, c3 (Record): 10, c4: 9, c5: 5</span>].
                        </span>
                    </div>
                    <span class="text-emerald-400 font-semibold font-mono text-[11px]">Zero Sliders • Clean Integer Entry</span>
                </div>

                <!-- Continuous Evaluation Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-slate-800 border-collapse" id="continuousTable">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center text-xs">#</th>
                                <th class="p-3 w-32 font-mono text-xs">Reg No</th>
                                <th class="p-3 w-48 text-xs">Student Name</th>
                                <!-- Highlighted Single Total Entry Box Column -->
                                <th class="p-3 text-center bg-sky-950/60 text-sky-300 border-x-2 border-sky-500/50 w-36">
                                    <span class="block text-xs font-black">TOTAL (50M)</span>
                                    <span class="text-[10px] text-sky-400 font-normal">Single Field Entry</span>
                                </th>
                                @foreach($evalScheme['day_work'] as $crit)
                                <th class="p-2.5 text-center text-xs" title="{{ $crit['title'] }}">
                                    <span>{{ $crit['title'] }}</span><br>
                                    <span class="text-sky-400 font-bold font-mono">({{ $crit['max_marks'] }}M)</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $idx => $student)
                            @php
                                $stEval = $activityEvals->get($student->reg_no, collect())->first();
                                $currentTot = $stEval ? round(floatval($stEval->total_score_50)) : 0;
                            @endphp
                            <tr class="hover:bg-slate-900/60 transition continuous-row" data-reg-no="{{ $student->reg_no }}">
                                <td class="p-3 text-center text-slate-500 font-mono text-xs">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-bold text-sky-400 reg-no-lg">{{ $student->reg_no }}</td>
                                <td class="p-3 text-white font-bold student-name-lg">{{ $student->name }}</td>
                                
                                <!-- Prominent Single Total Input Field (Whole Numbers Only, Big Font) -->
                                <td class="p-3 text-center bg-sky-950/30 border-x-2 border-sky-500/40">
                                    <input type="number" step="1" min="0" max="50"
                                           id="total_input_{{ $student->reg_no }}"
                                           value="{{ $currentTot > 0 ? $currentTot : '' }}"
                                           placeholder="0"
                                           oninput="handleTotalInput('{{ $student->reg_no }}', this.value)"
                                           class="mark-entry-main w-24 h-11 text-center font-mono font-black text-lg text-sky-300 bg-slate-900 border-2 border-sky-500/50 rounded-xl focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40 shadow-inner no-spinners">
                                </td>

                                <!-- Editable Sub-Criteria Inputs (Whole Integers, Record Book Prioritized) -->
                                @foreach($evalScheme['day_work'] as $crit)
                                @php
                                    $k = $crit['key'];
                                    $val = $stEval ? round(floatval($stEval->$k ?? 0)) : 0;
                                    $isRecord = str_contains(strtolower($crit['title']), 'record') || str_contains(strtolower($crit['title']), 'logbook');
                                @endphp
                                <td class="p-2.5 text-center min-w-[100px] {{ $isRecord ? 'bg-amber-950/20' : '' }}">
                                    <input type="number" step="1" min="0" max="{{ $crit['max_marks'] }}"
                                           id="crit_{{ $student->reg_no }}_{{ $k }}"
                                           value="{{ $val > 0 ? $val : '' }}"
                                           placeholder="0"
                                           data-max="{{ $crit['max_marks'] }}"
                                           data-key="{{ $k }}"
                                           data-is-record="{{ $isRecord ? '1' : '0' }}"
                                           oninput="handleCriteriaInput('{{ $student->reg_no }}')"
                                           class="mark-entry-sub w-16 h-9 text-center font-mono font-bold text-sm text-slate-100 bg-slate-900 border border-slate-700 rounded-lg focus:border-sky-500 crit-input-{{ $student->reg_no }} no-spinners {{ $isRecord ? 'border-amber-500/40 text-amber-200' : '' }}"
                                           title="{{ $crit['title'] }} (Max {{ $crit['max_marks'] }}M)">
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                    <span class="text-xs text-slate-400">Total Enrolled Students: <strong class="text-white">{{ $students->count() }}</strong></span>
                    <button type="button" onclick="saveContinuousEvaluationManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save Continuous Marks</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 4: SERIES TEST (WITH ACCIDENTAL 15M ENTRY SUPPORT LOGIC)              -->
        <!-- ========================================================================= -->
        <div id="tab-content-seriestest" class="tab-pane-content space-y-5 hidden">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-slate-800 pb-3 gap-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-rounded text-sky-400 text-base">edit_note</span>
                                <span>Physical Fitness & Skill Tests (Series Test CA1 & CA2)</span>
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                CA1 (15M) + CA2 (15M) = 30M
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Series tests conducted across the semester. Supports accidental 15-mark entry logic so entered marks are preserved without downscaling.
                        </p>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/fitness-tests" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex items-center space-x-1.5 no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Series Report</span>
                        </a>
                        <button type="button" onclick="saveSeriesMarksManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Series Marks</span>
                        </button>
                    </div>
                </div>

                <!-- ACCIDENTAL 15-MARK SUPPORT BANNER & MODE SELECTOR -->
                <div class="p-4 rounded-xl bg-amber-950/30 border border-amber-500/40 mb-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="flex items-start space-x-3">
                            <span class="material-symbols-rounded text-amber-400 text-xl mt-0.5 shrink-0">warning</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Series 1 Test: Accidental 15-Mark Conducted Test Support Logic</h4>
                                <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">
                                    Staff (Jaick Joseph) conducted Series 1 out of <strong class="text-white">15 marks</strong> accidentally instead of 40 as per rule.
                                    In <strong class="text-amber-300">Direct 15M Mode</strong>, marks entered out of 15 are retained directly as the full converted 15M score, preventing any unfair downscaling!
                                </p>
                            </div>
                        </div>

                        <!-- Mode Selector Pill -->
                        <div class="flex items-center space-x-2 bg-slate-900/90 p-1 rounded-xl border border-slate-700 shrink-0">
                            <button type="button" id="btn-mode-direct15" onclick="setSeries1Mode('direct_15')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ ($series1Mode ?? 'direct_15') === 'direct_15' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'text-slate-400 hover:text-white' }}">
                                <span class="material-symbols-rounded text-xs mr-1 align-middle">check_circle</span>Direct 15M Mode
                            </button>
                            <button type="button" id="btn-mode-scaled40" onclick="setSeries1Mode('scaled_40')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ ($series1Mode ?? 'direct_15') === 'scaled_40' ? 'bg-sky-500 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                                Standard 40M Mode
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Series Marks Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-slate-800 border-collapse" id="seriesTable">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th rowspan="2" class="p-3 w-12 text-center text-xs border-r border-slate-800">#</th>
                                <th rowspan="2" class="p-3 w-32 font-mono text-xs border-r border-slate-800">Reg No</th>
                                <th rowspan="2" class="p-3 w-48 text-xs border-r border-slate-800">Student Name</th>
                                <!-- CA1 Group -->
                                <th colspan="3" class="p-2.5 text-center bg-purple-950/40 text-purple-300 border-r border-slate-800">
                                    <span class="text-xs font-bold">SERIES 1 (CA1): FITNESS TEST</span>
                                    <span id="ca1ModeBadge" class="ml-2 px-2 py-0.5 rounded-full text-[10px] bg-amber-500/20 text-amber-300 border border-amber-500/40 font-mono">
                                        {{ ($series1Mode ?? 'direct_15') === 'direct_15' ? 'Direct 15M Mode' : 'Standard 40M Mode' }}
                                    </span>
                                </th>
                                <!-- CA2 Group -->
                                <th colspan="3" class="p-2.5 text-center bg-sky-950/40 text-sky-300 border-r border-slate-800">
                                    <span class="text-xs font-bold">SERIES 2 (CA2): SKILL DEMO</span>
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-[10px] bg-sky-500/20 text-sky-300 border border-sky-500/40 font-mono">
                                        Max 40M &rarr; Conv 15M
                                    </span>
                                </th>
                                <!-- Total Series Column -->
                                <th rowspan="2" class="p-3 text-center bg-emerald-950/40 text-emerald-300 font-extrabold w-32">
                                    TOTAL SERIES<br><span class="text-xs font-mono">(MAX 30M)</span>
                                </th>
                            </tr>
                            <tr class="bg-slate-950 text-slate-400 text-xs font-semibold">
                                <!-- CA1 subheaders -->
                                <th class="p-2 text-center w-28 border-r border-slate-800">Raw Mark (<span id="ca1MaxHeader">15</span>M)</th>
                                <th class="p-2 text-center w-16 border-r border-slate-800">Absent</th>
                                <th class="p-2 text-center w-24 border-r border-slate-800 text-purple-300 font-bold">Conv (15M)</th>
                                <!-- CA2 subheaders -->
                                <th class="p-2 text-center w-28 border-r border-slate-800">Raw Mark (40M)</th>
                                <th class="p-2 text-center w-16 border-r border-slate-800">Absent</th>
                                <th class="p-2 text-center w-24 border-r border-slate-800 text-sky-300 font-bold">Conv (15M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($studentResults as $idx => $st)
                            <tr class="hover:bg-slate-900/60 transition series-row" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="p-3 text-center text-slate-500 font-mono text-xs border-r border-slate-800">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-bold text-sky-400 reg-no-lg border-r border-slate-800">{{ $st['reg_no'] }}</td>
                                <td class="p-3 text-white font-bold student-name-lg border-r border-slate-800">{{ $st['name'] }}</td>

                                <!-- CA1 Raw Score -->
                                <td class="p-2 text-center border-r border-slate-800 bg-purple-950/10">
                                    <input type="number" step="0.5" min="0" max="{{ ($series1Mode ?? 'direct_15') === 'direct_15' ? '15' : '40' }}"
                                           id="ca1_raw_{{ $st['reg_no'] }}"
                                           value="{{ $st['ca1_absent'] ? '' : ($st['ca1_raw'] > 0 ? $st['ca1_raw'] : '') }}"
                                           placeholder="0"
                                           oninput="calcSeriesRow('{{ $st['reg_no'] }}')"
                                           class="mark-entry-main w-20 h-10 text-center font-mono font-bold text-base text-purple-200 bg-slate-900 border border-purple-500/40 rounded-lg focus:border-purple-400 no-spinners">
                                </td>
                                <!-- CA1 Absent Checkbox -->
                                <td class="p-2 text-center border-r border-slate-800 bg-purple-950/10">
                                    <input type="checkbox" id="ca1_abs_{{ $st['reg_no'] }}"
                                           {{ $st['ca1_absent'] ? 'checked' : '' }}
                                           onchange="toggleAbsent('ca1', '{{ $st['reg_no'] }}')"
                                           class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-rose-500 focus:ring-rose-500 cursor-pointer">
                                </td>
                                <!-- CA1 Converted Mark (/15) -->
                                <td class="p-2 text-center font-mono font-black text-sm text-purple-300 border-r border-slate-800 bg-purple-950/20" id="ca1_conv_{{ $st['reg_no'] }}">
                                    {{ $st['ca1_absent'] ? 'ABS' : number_format($st['ca1_conv'], 1) }}
                                </td>

                                <!-- CA2 Raw Score -->
                                <td class="p-2 text-center border-r border-slate-800 bg-sky-950/10">
                                    <input type="number" step="0.5" min="0" max="40"
                                           id="ca2_raw_{{ $st['reg_no'] }}"
                                           value="{{ $st['ca2_absent'] ? '' : ($st['ca2_raw'] > 0 ? $st['ca2_raw'] : '') }}"
                                           placeholder="0"
                                           oninput="calcSeriesRow('{{ $st['reg_no'] }}')"
                                           class="mark-entry-main w-20 h-10 text-center font-mono font-bold text-base text-sky-200 bg-slate-900 border border-sky-500/40 rounded-lg focus:border-sky-400 no-spinners">
                                </td>
                                <!-- CA2 Absent Checkbox -->
                                <td class="p-2 text-center border-r border-slate-800 bg-sky-950/10">
                                    <input type="checkbox" id="ca2_abs_{{ $st['reg_no'] }}"
                                           {{ $st['ca2_absent'] ? 'checked' : '' }}
                                           onchange="toggleAbsent('ca2', '{{ $st['reg_no'] }}')"
                                           class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-rose-500 focus:ring-rose-500 cursor-pointer">
                                </td>
                                <!-- CA2 Converted Mark (/15) -->
                                <td class="p-2 text-center font-mono font-black text-sm text-sky-300 border-r border-slate-800 bg-sky-950/20" id="ca2_conv_{{ $st['reg_no'] }}">
                                    {{ $st['ca2_absent'] ? 'ABS' : number_format($st['ca2_conv'], 1) }}
                                </td>

                                <!-- Total Series (30M) -->
                                <td class="p-2 text-center font-mono font-black text-base text-emerald-300 bg-emerald-950/30" id="series_tot_{{ $st['reg_no'] }}">
                                    {{ number_format($st['test_marks'], 1) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                    <span class="text-xs text-slate-400">Total Enrolled Students: <strong class="text-white">{{ count($studentResults) }}</strong></span>
                    <button type="button" onclick="saveSeriesMarksManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save Series Marks</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 5: CIE REGISTER (CONSOLIDATED 60M CIE WITH ATTENDANCE OVERRIDE MODAL) -->
        <!-- ========================================================================= -->
        <div id="tab-content-cieregister" class="tab-pane-content space-y-5 hidden">
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 border-b border-slate-800 pb-3 gap-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-rounded text-sky-400 text-base">fact_check</span>
                                <span>Continuous Internal Evaluation (CIE) Register — 60 Marks</span>
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                                5M Att + 25M CE + 30M Series = 60M CIE
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Official consolidated register. Displays TEAMS % attendance, manual override with confirmation protection, continuous evaluation, and series tests.
                        </p>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0 flex-wrap gap-2">
                        <!-- Enable Attendance Override Button with Warning Prompt -->
                        <button type="button" id="btnToggleAttOverride" onclick="requestEnableAttendanceOverride()" class="px-3.5 py-1.5 rounded-xl bg-amber-600/30 hover:bg-amber-600/50 text-amber-300 hover:text-amber-200 border border-amber-500/40 text-xs font-bold transition flex items-center space-x-1.5 cursor-pointer shadow-sm">
                            <span class="material-symbols-rounded text-sm">lock_open</span>
                            <span id="attOverrideBtnText">Enable Attendance Override</span>
                        </button>

                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/cia" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex items-center space-x-1.5 no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print CIA (60M)</span>
                        </a>

                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/consolidated" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-300 font-bold text-xs border border-emerald-500/40 flex items-center space-x-1.5 no-underline shadow-sm">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Consolidated (100M)</span>
                        </a>

                        <button type="button" onclick="saveCieRegisterManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save CIE Register</span>
                        </button>
                    </div>
                </div>

                <!-- Statutory Attendance Slabs Info Bar -->
                <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 mb-4 flex items-center justify-between flex-wrap gap-2 text-xs">
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="material-symbols-rounded text-sky-400 text-base">info</span>
                        <span><strong>Statutory Attendance Slabs (5M):</strong> &ge;90%: 5M | 80–89.9%: 4M | 75–79.9%: 3M | 70–74.9%: 2M | 65–69.9%: 1M | &lt;65%: 0M</span>
                    </div>
                    <span id="overrideModeIndicator" class="text-slate-400 font-semibold font-mono text-[11px] flex items-center gap-1">
                        <span class="material-symbols-rounded text-xs">lock</span> <span>Override inputs locked</span>
                    </span>
                </div>

                <!-- Consolidated CIE Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-slate-800 border-collapse" id="cieRegisterTable">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800 text-xs">
                                <th rowspan="2" class="p-2.5 w-10 text-center border-r border-slate-800">#</th>
                                <th rowspan="2" class="p-2.5 w-28 font-mono border-r border-slate-800">Reg No</th>
                                <th rowspan="2" class="p-2.5 w-44 border-r border-slate-800">Student Name</th>
                                <!-- Main Title: Attendance (5M) -->
                                <th colspan="3" class="p-2.5 text-center bg-cyan-950/40 text-cyan-300 border-r border-slate-800">
                                    ATTENDANCE (MAX 5M)
                                </th>
                                <!-- Main Title: Continuous Evaluation (25M) -->
                                <th colspan="2" class="p-2.5 text-center bg-amber-950/40 text-amber-300 border-r border-slate-800">
                                    CONTINUOUS EVAL (MAX 25M)
                                </th>
                                <!-- Main Title: Series Tests (30M) -->
                                <th colspan="3" class="p-2.5 text-center bg-purple-950/40 text-purple-300 border-r border-slate-800">
                                    SERIES TESTS (MAX 30M)
                                </th>
                                <!-- Total CIE (60M) -->
                                <th rowspan="2" class="p-2.5 text-center bg-sky-950/60 text-sky-300 font-extrabold w-28 border-r border-slate-800">
                                    TOTAL CIE<br><span class="text-[11px] font-mono">(MAX 60M)</span>
                                </th>
                                <!-- ESE (40M) -->
                                <th rowspan="2" class="p-2.5 text-center bg-slate-900 text-slate-300 w-24 border-r border-slate-800">
                                    ESE<br><span class="text-[11px] font-mono">(MAX 40M)</span>
                                </th>
                                <!-- Grand Total (100M) -->
                                <th rowspan="2" class="p-2.5 text-center bg-emerald-950/50 text-emerald-300 font-extrabold w-28 border-r border-slate-800">
                                    GRAND TOTAL<br><span class="text-[11px] font-mono">(100M)</span>
                                </th>
                                <!-- Status -->
                                <th rowspan="2" class="p-2.5 text-center w-20">Status</th>
                            </tr>
                            <tr class="bg-slate-950 text-slate-400 text-[11px] font-semibold border-b border-slate-800">
                                <!-- Attendance subheaders -->
                                <th class="p-2 text-center w-24 border-r border-slate-800" title="Official TEAMS / Log Attendance Percentage">TEAMS %</th>
                                <th class="p-2 text-center w-28 border-r border-slate-800" title="Manual Override Percentage">Override %</th>
                                <th class="p-2 text-center w-16 border-r border-slate-800 text-cyan-300 font-bold">Mark (5)</th>
                                <!-- CE subheaders -->
                                <th class="p-2 text-center w-20 border-r border-slate-800">Tot (50M)</th>
                                <th class="p-2 text-center w-24 border-r border-slate-800 text-amber-300 font-bold">Conv (25M)</th>
                                <!-- Series subheaders -->
                                <th class="p-2 text-center w-20 border-r border-slate-800">CA1 (15M)</th>
                                <th class="p-2 text-center w-20 border-r border-slate-800">CA2 (15M)</th>
                                <th class="p-2 text-center w-24 border-r border-slate-800 text-purple-300 font-bold">Tot (30M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($studentResults as $idx => $st)
                            @php
                                $hasOverride = ($st['attendance_override_pct'] !== null && $st['attendance_override_pct'] !== '');
                            @endphp
                            <tr class="hover:bg-slate-900/60 transition cie-student-row" data-reg-no="{{ $st['reg_no'] }}">
                                <td class="p-2.5 text-center text-slate-500 font-mono text-xs border-r border-slate-800">{{ $idx + 1 }}</td>
                                <td class="p-2.5 font-mono font-bold text-sky-400 reg-no-lg border-r border-slate-800">{{ $st['reg_no'] }}</td>
                                <td class="p-2.5 text-white font-bold student-name-lg border-r border-slate-800">{{ $st['name'] }}</td>

                                <!-- 1. Teams Uploaded % -->
                                <td class="p-2 text-center text-xs font-mono text-slate-300 border-r border-slate-800 bg-cyan-950/10">
                                    {{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) . '%' : '-' }}
                                </td>

                                <!-- 2. Attendance Override % (Locked by default; amber when active) -->
                                <td class="p-2 text-center border-r border-slate-800 bg-cyan-950/10 cell-att-td">
                                    <div class="flex items-center justify-center gap-1">
                                        <input type="number" step="0.1" min="0" max="100"
                                               id="att_override_{{ $st['reg_no'] }}"
                                               value="{{ $hasOverride ? $st['attendance_override_pct'] : '' }}"
                                               placeholder="{{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) : '-' }}"
                                               disabled
                                               data-prev-val="{{ $hasOverride ? $st['attendance_override_pct'] : '' }}"
                                               onfocus="this.dataset.prevVal = this.value;"
                                               oninput="calcCieRow('{{ $st['reg_no'] }}')"
                                               onchange="handleAttOverrideChange(this, '{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')"
                                               class="cell-att-override mark-entry-sub w-20 h-8 text-center font-mono font-bold text-xs bg-slate-900 border border-slate-700 rounded-lg no-spinners opacity-60 {{ $hasOverride ? 'has-override opacity-100' : '' }}"
                                               title="{{ $hasOverride ? 'Active Override: ' . $st['attendance_override_pct'] . '%' : 'Enable override mode to edit' }}">
                                        @if($hasOverride)
                                        <button type="button" onclick="clearAttOverride('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-clear-att text-amber-400 hover:text-rose-400 transition p-1 cursor-pointer" title="Revert override">
                                            <span class="material-symbols-rounded text-xs">close</span>
                                        </button>
                                        @else
                                        <button type="button" onclick="promptAttOverrideDirect('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-prompt-att text-slate-500 hover:text-amber-400 transition p-1 cursor-pointer" title="Override attendance %">
                                            <span class="material-symbols-rounded text-xs">edit</span>
                                        </button>
                                        @endif
                                    </div>
                                </td>

                                <!-- 3. Attendance Mark (5) -->
                                <td class="p-2 text-center font-mono font-black text-sm text-cyan-300 border-r border-slate-800 bg-cyan-950/20" id="att_mark_{{ $st['reg_no'] }}">
                                    {{ number_format($st['att_marks'], 1) }}
                                </td>

                                <!-- 4. CE Total (50M) -->
                                <td class="p-2 text-center font-mono text-xs text-slate-300 border-r border-slate-800 bg-amber-950/10" id="ce_tot50_{{ $st['reg_no'] }}">
                                    {{ $st['activity_total_50'] }}
                                </td>

                                <!-- 5. CE Converted (25M) -->
                                <td class="p-2 text-center font-mono font-black text-sm text-amber-300 border-r border-slate-800 bg-amber-950/20" id="ce_conv25_{{ $st['reg_no'] }}">
                                    {{ number_format($st['activity_marks'], 1) }}
                                </td>

                                <!-- 6. CA1 (15M) -->
                                <td class="p-2 text-center font-mono text-xs text-purple-300 border-r border-slate-800 bg-purple-950/10" id="cie_ca1_{{ $st['reg_no'] }}">
                                    {{ $st['ca1_absent'] ? 'ABS' : number_format($st['ca1_conv'], 1) }}
                                </td>

                                <!-- 7. CA2 (15M) -->
                                <td class="p-2 text-center font-mono text-xs text-sky-300 border-r border-slate-800 bg-purple-950/10" id="cie_ca2_{{ $st['reg_no'] }}">
                                    {{ $st['ca2_absent'] ? 'ABS' : number_format($st['ca2_conv'], 1) }}
                                </td>

                                <!-- 8. Series Total (30M) -->
                                <td class="p-2 text-center font-mono font-black text-sm text-purple-300 border-r border-slate-800 bg-purple-950/20" id="cie_seriestot_{{ $st['reg_no'] }}">
                                    {{ number_format($st['test_marks'], 1) }}
                                </td>

                                <!-- 9. Total CIE (60M) -->
                                <td class="p-2 text-center font-mono font-black text-base text-sky-300 border-r border-slate-800 bg-sky-950/30" id="cie_total60_{{ $st['reg_no'] }}">
                                    {{ number_format($st['total_cie_marks'], 1) }}
                                </td>

                                <!-- 10. ESE Mark (40M) -->
                                <td class="p-2 text-center border-r border-slate-800">
                                    <input type="number" step="0.5" min="0" max="40"
                                           id="ese_{{ $st['reg_no'] }}"
                                           value="{{ $st['total_ese'] > 0 ? $st['total_ese'] : '' }}"
                                           placeholder="0"
                                           oninput="calcCieRow('{{ $st['reg_no'] }}')"
                                           class="mark-entry-sub w-16 h-8 text-center font-mono font-bold text-xs bg-slate-900 border border-slate-700 rounded-lg focus:border-emerald-400 no-spinners">
                                </td>

                                <!-- 11. Grand Total (100M) -->
                                <td class="p-2 text-center font-mono font-black text-base text-emerald-300 border-r border-slate-800 bg-emerald-950/20" id="grand_total_{{ $st['reg_no'] }}">
                                    {{ number_format($st['total_course_marks'], 1) }}
                                </td>

                                <!-- 12. Status -->
                                <td class="p-2 text-center" id="pass_status_{{ $st['reg_no'] }}">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $st['is_passed'] ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                        {{ $st['is_passed'] ? 'PASS' : 'FAIL' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                    <span class="text-xs text-slate-400">Total Enrolled Students: <strong class="text-white">{{ count($studentResults) }}</strong></span>
                    <button type="button" onclick="saveCieRegisterManual()" class="px-4 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex items-center space-x-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-rounded text-sm">save</span>
                        <span>Save CIE Register</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 6: ATTAINMENT & SURVEY (CO-PO DIRECT & INDIRECT ATTAINMENT)           -->
        <!-- ========================================================================= -->
        <div id="tab-content-attainment" class="tab-pane-content space-y-5 hidden">
            <!-- Dual Survey Activation & Preview Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Mid-Semester Survey Card -->
                <div class="glass-card p-5 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                                <span class="material-symbols-rounded text-amber-400 text-base mr-2">forum</span>
                                <span>Mid-Semester Survey</span>
                            </h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                {{ isset($midSemSurvey) && $midSemSurvey ? $midSemSurvey->status : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Mid-Term feedback on physical exercises, playground facilities, sports equipment, and safety practices.</p>
                    </div>
                    <div class="flex items-center space-x-2 pt-3 border-t border-slate-800">
                        <button type="button" onclick="openPreviewModal('previewMidSemModal')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex-1 flex items-center justify-center space-x-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">visibility</span>
                            <span>Preview Questionnaire</span>
                        </button>
                        @if(isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active')
                            <button type="button" onclick="closeMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex-1 cursor-pointer">
                                Close Survey
                            </button>
                        @else
                            <button type="button" onclick="initiateMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex-1 cursor-pointer">
                                Activate Survey
                            </button>
                        @endif
                    </div>
                </div>

                <!-- End-Semester Course Exit Survey Card -->
                <div class="glass-card p-5 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                                <span class="material-symbols-rounded text-sky-400 text-base mr-2">school</span>
                                <span>End-Semester Course Exit Survey</span>
                            </h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                {{ isset($exitSurvey) && $exitSurvey ? $exitSurvey->status : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">Comprehensive end-of-course survey evaluated on High (L3), Medium (L2), Low (L1) scale for Indirect CO Attainment (20%).</p>
                    </div>
                    <div class="flex items-center space-x-2 pt-3 border-t border-slate-800">
                        <button type="button" onclick="openPreviewModal('previewExitSurveyModal')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 flex-1 flex items-center justify-center space-x-1 cursor-pointer">
                            <span class="material-symbols-rounded text-sm">visibility</span>
                            <span>Preview Questionnaire</span>
                        </button>
                        @if(isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active')
                            <button type="button" onclick="closeExitSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs flex-1 cursor-pointer">
                                Close Survey
                            </button>
                        @else
                            <button type="button" onclick="initiateExitSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs flex-1 cursor-pointer">
                                Activate Survey
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Direct CO Attainment Table -->
            <div class="glass-card p-5 border border-slate-800">
                <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                        <span class="material-symbols-rounded text-sky-400 text-base mr-2">analytics</span>
                        <span>Direct Course Outcome Attainment (80% Weightage)</span>
                    </h3>
                    <span class="text-xs font-mono text-slate-400">Target Threshold: 50% Marks</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800 border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-2.5 w-24">CO Tag</th>
                                <th class="p-2.5">Course Outcome Description</th>
                                <th class="p-2.5 text-center w-36">Students Attained</th>
                                <th class="p-2.5 text-center w-36">Percentage (%)</th>
                                <th class="p-2.5 text-center w-36">Attainment Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($directStats as $coTag => $dObj)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-2.5 font-bold font-mono text-sky-400">{{ $coTag }}</td>
                                <td class="p-2.5 text-slate-300">
                                    @php $coDesc = collect($hpCourseFile->parsed_cos)->firstWhere('id', $coTag)['description'] ?? 'Physical practical outcome'; @endphp
                                    {{ $coDesc }}
                                </td>
                                <td class="p-2.5 text-center font-mono">{{ $dObj['count'] }} / {{ count($studentResults) }}</td>
                                <td class="p-2.5 text-center font-mono font-bold text-sky-300">{{ $dObj['percentage'] }}%</td>
                                <td class="p-2.5 text-center font-bold">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono {{ $dObj['level'] >= 2.5 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                        Level {{ $dObj['level'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Indirect CO Attainment Graphical Distribution -->
            <div class="glass-card p-5 border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">bar_chart</span>
                    <span>Indirect CO Attainment (Course Exit Survey Feedback)</span>
                </h3>
                <div class="space-y-4">
                    @foreach(['CO1' => 'CO1 - Personal Health, BMI & Posture Principles', 'CO2' => 'CO2 - Fitness Drills & Cardiovascular Activities', 'CO3' => 'CO3 - Major Games & Athletics Skills', 'CO4' => 'CO4 - Yogic Asanas, First Aid & Stress Relief'] as $cKey => $cTitle)
                    @php
                        $indObj = $indirectStats[$cKey] ?? ['avg_score' => 2.5, 'percentage' => 83.3, 'level' => 3.0];
                        $cPct = $indObj['percentage'];
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                            <span>{{ $cTitle }}</span>
                            <span class="font-mono text-sky-400">{{ $indObj['avg_score'] }} / 3.0 ({{ $cPct }}%) — Level {{ $indObj['level'] }}</span>
                        </div>
                        <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-sky-500 to-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ $cPct }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Program Outcome (PO) Attainment Summary Table -->
            <div class="glass-card p-5 border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">military_tech</span>
                    <span>Program Outcome (PO) Attainment Summary</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800 border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                @for($p=1; $p<=11; $p++)
                                <th class="p-2.5 text-center">PO{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @for($p=1; $p<=11; $p++)
                                @php $poVal = $poAttainments["PO{$p}"]['value'] ?? 0.0; @endphp
                                <td class="p-2.5 text-center font-bold font-mono text-sm {{ $poVal > 0 ? 'text-sky-300 bg-sky-950/30' : 'text-slate-600' }}">
                                    {{ number_format($poVal, 2) }}
                                </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- TAB 7: REPORTS (A4 PRINT HUB)                                             -->
        <!-- ========================================================================= -->
        <div id="tab-content-reports" class="tab-pane-content hidden space-y-4">
            <div class="glass-card p-5 border border-slate-800">
                <div class="mb-4 pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-sm text-slate-200 flex items-center gap-2">
                        <span class="material-symbols-rounded text-base text-slate-400">print</span>
                        <span>Official Printable Registers &amp; Academic Reports — {{ $hpCourseFile->course_title ?? 'Health and Physical Education' }}</span>
                    </h3>
                    <p class="text-xs text-slate-400 mb-0">Select any official register or report below to open its A4 print view.</p>
                </div>

                @php
                    $hpReportItems = [
                        ['slug' => 'cia',           'icon' => 'fact_check',           'title' => 'CIA Register (Internal Mark Consolidated — 60M)',        'sub' => 'Attendance (5M), Continuous Evaluation (25M), Series Tests CA1 & CA2 (30M), Total CIA (60M)'],
                        ['slug' => 'consolidated',  'icon' => 'workspace_premium',    'title' => 'Consolidated Marks Report (CIA 60M + ESE 40M = 100M)',   'sub' => 'Official final marksheet with CIE (60M), ESE (40M), grand total (100M), and PASS/FAIL status'],
                        ['slug' => 'attendance',    'icon' => 'how_to_reg',           'title' => 'Attendance Percentage & Statutory Marks Report (5M)',    'sub' => 'Total sessions (30 Hrs), attended sessions, attendance %, statutory CIE marks (5M), and eligibility'],
                        ['slug' => 'lesson-plan',   'icon' => 'calendar_month',       'title' => 'Course Log & 30-Hour Physical Activity Schedule',        'sub' => 'Session-wise proposed/actual dates, topics, physical drills, CO tags, and status'],
                        ['slug' => 'activity-log',  'icon' => 'assignment_turned_in', 'title' => 'Continuous Fitness Split-Up Marksheet (50M → 25M)',       'sub' => 'Criteria c1–c5 breakdown, Record Book marks, total score out of 50, and converted mark (25M)'],
                        ['slug' => 'fitness-tests', 'icon' => 'edit_note',            'title' => 'Physical Fitness & Skill Tests (CA1 & CA2: 30M)',        'sub' => 'CA1 Fitness Test (15M) and CA2 Skill Demo (15M) marks totaling 30 Marks'],
                        ['slug' => 'attainment',    'icon' => 'analytics',            'title' => 'CO & PO Attainment Report (Revision 2026 Model)',       'sub' => 'Outcome matrix CO1–CO4, Direct Attainment (80%) + Course Exit Survey Indirect Attainment (20%)'],
                    ];
                @endphp

                <div class="divide-y divide-slate-800/80 border border-slate-800 rounded-xl overflow-hidden bg-slate-950/60">
                    @foreach($hpReportItems as $rItem)
                    <div class="px-4 py-2.5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 hover:bg-slate-900/60 transition-all">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="material-symbols-rounded text-slate-400 text-base shrink-0">{{ $rItem['icon'] }}</span>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-200 truncate">{{ $rItem['title'] }}</div>
                                <div class="text-[11px] text-slate-400 truncate">{{ $rItem['sub'] }}</div>
                            </div>
                        </div>
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/{{ $rItem['slug'] }}" target="_blank"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs no-underline transition-all">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Report</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </main>

    <!-- Upload Syllabus PDF Modal -->
    <div id="uploadSyllabusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-2">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">upload_file</span>
                    <span>Upload Syllabus PDF</span>
                </h3>
                <button onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="text-slate-400 hover:text-white cursor-pointer">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>
            
            <form id="uploadSyllabusForm" onsubmit="uploadSyllabusPdf(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Select Official Syllabus PDF</label>
                    <input type="file" name="syllabus_file" accept=".pdf" required class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-600 file:text-white hover:file:bg-sky-500 bg-slate-900 border border-slate-800 rounded-xl cursor-pointer">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold shadow-lg shadow-sky-600/20 cursor-pointer">
                        Upload & Extract Criteria
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Mid-Sem Survey Modal -->
    <div id="previewMidSemModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-xl w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-2">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <span class="material-symbols-rounded text-amber-400 text-base mr-2">forum</span>
                    <span>Mid-Semester Survey Questionnaire</span>
                </h3>
                <button onclick="closePreviewModal('previewMidSemModal')" class="text-slate-400 hover:text-white cursor-pointer">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2 text-xs">
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-amber-400 font-bold font-mono">Q1:</span> Pace of coverage for physical fitness sessions and posture correction drills.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-amber-400 font-bold font-mono">Q2:</span> Clarity of practical demonstrations & athletic exercise techniques by staff.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-amber-400 font-bold font-mono">Q3:</span> Availability of playground facilities, sports equipment, and safety measures.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-amber-400 font-bold font-mono">Q4:</span> Overall satisfaction with Health & Physical Education practical sessions.
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="button" onclick="closePreviewModal('previewMidSemModal')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 cursor-pointer">Close</button>
            </div>
        </div>
    </div>

    <!-- Preview End-Semester Exit Survey Modal -->
    <div id="previewExitSurveyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-2xl w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b border-slate-800 pb-2">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <span class="material-symbols-rounded text-sky-400 text-base mr-2">school</span>
                    <span>End-Semester Course Exit Survey Questionnaire</span>
                </h3>
                <button onclick="closePreviewModal('previewExitSurveyModal')" class="text-slate-400 hover:text-white cursor-pointer">
                    <span class="material-symbols-rounded text-base">close</span>
                </button>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2 text-xs">
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO1 (Q1):</span> How well did you understand personal health, hygiene, and physical fitness principles?
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO1 (Q2):</span> Rate your ability to calculate BMI and analyze posture alignment.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO2 (Q3):</span> How effectively can you execute warming-up protocols and calisthenics?
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO2 (Q4):</span> Rate your performance in cardiovascular endurance and track drills.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO3 (Q5):</span> How confident are you in executing skills and rules of major sports (Volleyball/Football)?
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO3 (Q6):</span> Rate your understanding of athletic track events and relay techniques.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO4 (Q7):</span> How effectively can you perform yogic asanas and relaxation techniques for stress relief?
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO4 (Q8):</span> Rate your competence in first aid procedures and sports injury management.
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="button" onclick="closePreviewModal('previewExitSurveyModal')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 cursor-pointer">Close</button>
            </div>
        </div>
    </div>

    <!-- Confirm Enable Attendance Override Modal -->
    <div id="confirmOverrideModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/85 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-amber-500/50 max-w-lg w-full mx-4 shadow-2xl">
            <div class="flex items-start space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-2xl">warning</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Confirm Attendance Override Mode</h3>
                    <p class="text-xs text-amber-200 mt-1">Caution: Modifying statutory attendance percentages directly alters official calculation based on TEAMS and attendance logs.</p>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-300 space-y-2 mb-4 leading-relaxed">
                <p>• You are enabling <strong>manual attendance percentage entry</strong> for students in this class.</p>
                <p>• Overridden percentages will directly update the statutory 5M Attendance Marks in the CIE Register.</p>
                <p>• Please ensure you have institutional justification for overriding official attendance figures.</p>
            </div>

            <div class="flex justify-end space-x-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="closeConfirmOverrideModal()" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 cursor-pointer">Cancel</button>
                <button type="button" onclick="executeEnableAttendanceOverride()" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-slate-950 text-xs font-bold shadow-lg shadow-amber-600/20 cursor-pointer">
                    Yes, Enable Override Mode
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC & HANDLERS -->
    <script>
        const subjectId = {{ $batchSubject->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const evalScheme = @json($evalScheme['day_work']);
        let currentSeries1Mode = '{{ $series1Mode ?? 'direct_15' }}';
        let isAttOverrideEnabled = false;

        // 1. Tab Switching Function
        function switchTab(tabId) {
            document.querySelectorAll('.tab-pane-content').forEach(pane => {
                pane.classList.add('hidden');
            });
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

            try {
                const url = new URL(window.location);
                url.searchParams.set('tab', tabId);
                window.history.replaceState({}, '', url);
            } catch (e) {}

            if (tabId === 'lessonplan') {
                setTimeout(() => {
                    document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));
                }, 20);
            }
        }

        function autoGrow(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        function setGlobalSaveStatus(msg, isSaving = false) {
            const badge = document.getElementById('globalSaveStatusBadge');
            if (!badge) return;
            if (isSaving) {
                badge.className = "px-3 py-1.5 rounded-xl text-xs font-bold bg-sky-500/10 text-sky-300 border border-sky-500/30 flex items-center gap-1.5 transition-all shadow-sm";
                badge.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">sync</span><span>${msg}</span>`;
            } else {
                badge.className = "px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5 transition-all shadow-sm";
                badge.innerHTML = `<span class="material-symbols-rounded text-sm">cloud_done</span><span>${msg}</span>`;
            }
        }

        // =========================================================================
        // 2. CONTINUOUS EVALUATION AUTO-SPLIT ALGORITHM
        // =========================================================================
        /**
         * Auto-splits total T in [0..50] into whole integers across criteria c1..c5,
         * strictly prioritizing Record Book (c3) with full or high marks.
         * Guaranteed: sum(c_i) === T exactly.
         */
        function splitTotalToCriteria(T) {
            T = Math.max(0, Math.min(50, Math.round(Number(T) || 0)));
            if (T <= 0) {
                return { c1: 0, c2: 0, c3: 0, c4: 0, c5: 0 };
            }
            if (T >= 50) {
                return { c1: 10, c2: 15, c3: 10, c4: 10, c5: 5 };
            }

            // c3 is Record Book (Max 10) - strictly prefer full / high mark
            let c3;
            if (T >= 35) {
                c3 = 10; // Full mark in record book for good scores
            } else if (T >= 25) {
                c3 = 9;
            } else if (T >= 15) {
                c3 = 8;
            } else {
                c3 = Math.max(1, Math.round(T * 0.25));
            }
            c3 = Math.min(10, Math.min(T, c3));

            let rem = T - c3;

            // c5 is Sportsmanship & Discipline (Max 5)
            let c5;
            if (rem >= 30) {
                c5 = 5;
            } else if (rem >= 20) {
                c5 = 4;
            } else if (rem >= 10) {
                c5 = 3;
            } else {
                c5 = Math.max(0, Math.min(5, Math.round(rem * 0.15)));
            }
            c5 = Math.min(5, Math.min(rem, c5));

            rem = rem - c5;

            // Remaining criteria: c1 (Max 10), c2 (Max 15), c4 (Max 10) -> Total weight 35
            let c1 = Math.round(rem * (10 / 35));
            let c2 = Math.round(rem * (15 / 35));
            let c4 = Math.round(rem * (10 / 35));

            c1 = Math.max(0, Math.min(10, c1));
            c2 = Math.max(0, Math.min(15, c2));
            c4 = Math.max(0, Math.min(10, c4));

            let diff = rem - (c1 + c2 + c4);
            while (diff !== 0) {
                if (diff > 0) {
                    if (c2 < 15) c2 += 1;
                    else if (c1 < 10) c1 += 1;
                    else if (c4 < 10) c4 += 1;
                    else if (c3 < 10) c3 += 1;
                    else if (c5 < 5) c5 += 1;
                    else break;
                    diff -= 1;
                } else {
                    if (c2 > 0) c2 -= 1;
                    else if (c4 > 0) c4 -= 1;
                    else if (c1 > 0) c1 -= 1;
                    else if (c5 > 0) c5 -= 1;
                    else if (c3 > 0) c3 -= 1;
                    else break;
                    diff += 1;
                }
            }

            return { c1, c2, c3, c4, c5 };
        }

        function handleTotalInput(regNo, rawVal) {
            if (rawVal === '' || rawVal === null) {
                evalScheme.forEach(crit => {
                    const el = document.getElementById(`crit_${regNo}_${crit.key}`);
                    if (el) el.value = '';
                });
                syncToCieRegister(regNo, 0);
                queueContinuousAutoSave();
                return;
            }

            let num = Math.max(0, Math.min(50, Math.round(parseFloat(rawVal) || 0)));
            const split = splitTotalToCriteria(num);

            evalScheme.forEach(crit => {
                const el = document.getElementById(`crit_${regNo}_${crit.key}`);
                if (el) {
                    el.value = split[crit.key] !== undefined ? split[crit.key] : 0;
                }
            });

            syncToCieRegister(regNo, num);
            queueContinuousAutoSave();
        }

        function handleCriteriaInput(regNo) {
            let sum = 0;
            evalScheme.forEach(crit => {
                const el = document.getElementById(`crit_${regNo}_${crit.key}`);
                if (el && el.value !== '') {
                    const max = parseFloat(el.dataset.max) || 10;
                    let v = Math.round(parseFloat(el.value) || 0);
                    if (v > max) { v = max; el.value = max; }
                    if (v < 0) { v = 0; el.value = 0; }
                    sum += v;
                }
            });

            sum = Math.min(50, sum);
            const totEl = document.getElementById(`total_input_${regNo}`);
            if (totEl) {
                totEl.value = sum > 0 ? sum : '';
            }

            syncToCieRegister(regNo, sum);
            queueContinuousAutoSave();
        }

        function syncToCieRegister(regNo, total50) {
            const ceTotEl = document.getElementById(`ce_tot50_${regNo}`);
            const ceConvEl = document.getElementById(`ce_conv25_${regNo}`);
            const conv25 = Math.round(((total50 / 50.0) * 25.0) * 10) / 10;

            if (ceTotEl) ceTotEl.innerText = total50;
            if (ceConvEl) ceConvEl.innerText = conv25.toFixed(1);

            calcCieRow(regNo);
        }

        let continuousSaveTimer = null;
        function queueContinuousAutoSave() {
            setGlobalSaveStatus('Saving continuous marks...', true);
            if (continuousSaveTimer) clearTimeout(continuousSaveTimer);
            continuousSaveTimer = setTimeout(() => saveContinuousEvaluation(true), 800);
        }

        async function saveContinuousEvaluation(isSilent = false) {
            const rows = document.querySelectorAll('.continuous-row');
            const payload = [];

            rows.forEach(row => {
                const regNo = row.dataset.regNo;
                const totEl = document.getElementById(`total_input_${regNo}`);
                const totVal = totEl && totEl.value !== '' ? parseInt(totEl.value, 10) : 0;

                const item = { reg_no: regNo, total_score_50: totVal };
                evalScheme.forEach(crit => {
                    const el = document.getElementById(`crit_${regNo}_${crit.key}`);
                    item[crit.key] = el && el.value !== '' ? parseInt(el.value, 10) : 0;
                });
                payload.push(item);
            });

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/activity`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        activity_no: 'ACT-LOG',
                        activity_title: 'Continuous Fitness & Activity Evaluation',
                        marks_data: payload
                    })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    setGlobalSaveStatus(`Continuous marks saved (${timeStr})`, false);
                    if (!isSilent) Swal.fire({ icon: 'success', title: 'Saved!', text: 'Continuous evaluation marks saved successfully!' });
                } else {
                    setGlobalSaveStatus('Save error', false);
                    if (!isSilent) Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            } catch (err) {
                setGlobalSaveStatus('Network error', false);
                if (!isSilent) Swal.fire({ icon: 'error', title: 'Error', text: err.message });
            }
        }

        function saveContinuousEvaluationManual() {
            saveContinuousEvaluation(false);
        }

        // =========================================================================
        // 3. SERIES TEST LOGIC & ACCIDENTAL 15M MODE
        // =========================================================================
        function setSeries1Mode(mode) {
            currentSeries1Mode = mode;
            const btnDirect = document.getElementById('btn-mode-direct15');
            const btnScaled = document.getElementById('btn-mode-scaled40');
            const ca1Badge = document.getElementById('ca1ModeBadge');
            const ca1MaxHeader = document.getElementById('ca1MaxHeader');

            if (mode === 'direct_15') {
                btnDirect.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-amber-500 text-slate-950 shadow-sm";
                btnScaled.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-400 hover:text-white";
                if (ca1Badge) ca1Badge.innerText = "Direct 15M Mode";
                if (ca1MaxHeader) ca1MaxHeader.innerText = "15";
            } else {
                btnScaled.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-sky-500 text-white shadow-sm";
                btnDirect.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-400 hover:text-white";
                if (ca1Badge) ca1Badge.innerText = "Standard 40M Mode";
                if (ca1MaxHeader) ca1MaxHeader.innerText = "40";
            }

            document.querySelectorAll('.series-row').forEach(row => {
                const regNo = row.dataset.regNo;
                const ca1Inp = document.getElementById(`ca1_raw_${regNo}`);
                if (ca1Inp) {
                    ca1Inp.max = mode === 'direct_15' ? '15' : '40';
                }
                calcSeriesRow(regNo);
            });

            queueSeriesAutoSave();
        }

        function toggleAbsent(testKey, regNo) {
            const chk = document.getElementById(`${testKey}_abs_${regNo}`);
            const rawInp = document.getElementById(`${testKey}_raw_${regNo}`);
            if (chk && chk.checked) {
                if (rawInp) {
                    rawInp.value = '';
                    rawInp.disabled = true;
                }
            } else {
                if (rawInp) rawInp.disabled = false;
            }
            calcSeriesRow(regNo);
            queueSeriesAutoSave();
        }

        function calcSeriesRow(regNo) {
            const ca1Inp = document.getElementById(`ca1_raw_${regNo}`);
            const ca1Abs = document.getElementById(`ca1_abs_${regNo}`)?.checked;
            const ca1ConvEl = document.getElementById(`ca1_conv_${regNo}`);

            let ca1Conv = 0.0;
            if (ca1Abs) {
                if (ca1ConvEl) ca1ConvEl.innerText = 'ABS';
            } else {
                let ca1Raw = parseFloat(ca1Inp?.value || 0);
                if (currentSeries1Mode === 'direct_15') {
                    ca1Conv = Math.min(15.0, Math.max(0, ca1Raw));
                } else {
                    ca1Conv = Math.round(((Math.min(40.0, Math.max(0, ca1Raw)) / 40.0) * 15.0) * 10) / 10;
                }
                if (ca1ConvEl) ca1ConvEl.innerText = ca1Conv.toFixed(1);
            }

            const ca2Inp = document.getElementById(`ca2_raw_${regNo}`);
            const ca2Abs = document.getElementById(`ca2_abs_${regNo}`)?.checked;
            const ca2ConvEl = document.getElementById(`ca2_conv_${regNo}`);

            let ca2Conv = 0.0;
            if (ca2Abs) {
                if (ca2ConvEl) ca2ConvEl.innerText = 'ABS';
            } else {
                let ca2Raw = parseFloat(ca2Inp?.value || 0);
                ca2Conv = Math.round(((Math.min(40.0, Math.max(0, ca2Raw)) / 40.0) * 15.0) * 10) / 10;
                if (ca2ConvEl) ca2ConvEl.innerText = ca2Conv.toFixed(1);
            }

            const seriesTot = Math.round((ca1Conv + ca2Conv) * 10) / 10;
            const totEl = document.getElementById(`series_tot_${regNo}`);
            if (totEl) totEl.innerText = seriesTot.toFixed(1);

            // Sync into CIE Register
            const cieCa1 = document.getElementById(`cie_ca1_${regNo}`);
            const cieCa2 = document.getElementById(`cie_ca2_${regNo}`);
            const cieSerTot = document.getElementById(`cie_seriestot_${regNo}`);

            if (cieCa1) cieCa1.innerText = ca1Abs ? 'ABS' : ca1Conv.toFixed(1);
            if (cieCa2) cieCa2.innerText = ca2Abs ? 'ABS' : ca2Conv.toFixed(1);
            if (cieSerTot) cieSerTot.innerText = seriesTot.toFixed(1);

            calcCieRow(regNo);
        }

        let seriesSaveTimer = null;
        function queueSeriesAutoSave() {
            setGlobalSaveStatus('Saving series marks...', true);
            if (seriesSaveTimer) clearTimeout(seriesSaveTimer);
            seriesSaveTimer = setTimeout(() => saveSeriesMarks(true), 800);
        }

        async function saveSeriesMarks(isSilent = false) {
            const rows = document.querySelectorAll('.series-row');
            let ca1Data = [], ca2Data = [];

            rows.forEach(row => {
                const regNo = row.dataset.regNo;
                const ca1Raw = parseFloat(document.getElementById(`ca1_raw_${regNo}`)?.value || 0);
                const ca1Abs = !!document.getElementById(`ca1_abs_${regNo}`)?.checked;
                const ca2Raw = parseFloat(document.getElementById(`ca2_raw_${regNo}`)?.value || 0);
                const ca2Abs = !!document.getElementById(`ca2_abs_${regNo}`)?.checked;

                ca1Data.push({ reg_no: regNo, raw_score: ca1Raw, is_absent: ca1Abs });
                ca2Data.push({ reg_no: regNo, raw_score: ca2Raw, is_absent: ca2Abs });
            });

            try {
                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ test_no: 'CA1', mode: currentSeries1Mode, marks_data: ca1Data })
                });

                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ test_no: 'CA2', mode: 'scaled_40', marks_data: ca2Data })
                });

                const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                setGlobalSaveStatus(`Series test marks saved (${timeStr})`, false);
                if (!isSilent) Swal.fire({ icon: 'success', title: 'Saved!', text: 'Series test scores saved successfully!' });
            } catch (err) {
                setGlobalSaveStatus('Save error', false);
                if (!isSilent) Swal.fire({ icon: 'error', title: 'Error', text: err.message });
            }
        }

        function saveSeriesMarksManual() {
            saveSeriesMarks(false);
        }

        // =========================================================================
        // 4. CIE REGISTER & ATTENDANCE OVERRIDE MODAL LOGIC
        // =========================================================================
        function requestEnableAttendanceOverride() {
            if (isAttOverrideEnabled) {
                // Toggle off
                isAttOverrideEnabled = false;
                document.querySelectorAll('.cell-att-override').forEach(inp => {
                    inp.disabled = true;
                    if (!inp.classList.contains('has-override')) inp.classList.add('opacity-60');
                });
                document.getElementById('attOverrideBtnText').innerText = "Enable Attendance Override";
                document.getElementById('overrideModeIndicator').innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> <span>Override inputs locked</span>`;
                return;
            }

            // Open warning confirmation modal
            document.getElementById('confirmOverrideModal').classList.remove('hidden');
        }

        function closeConfirmOverrideModal() {
            document.getElementById('confirmOverrideModal').classList.add('hidden');
        }

        function executeEnableAttendanceOverride() {
            closeConfirmOverrideModal();
            isAttOverrideEnabled = true;

            document.querySelectorAll('.cell-att-override').forEach(inp => {
                inp.disabled = false;
                inp.classList.remove('opacity-60');
            });

            document.getElementById('attOverrideBtnText').innerText = "Override Mode Active (Unlocked)";
            document.getElementById('overrideModeIndicator').innerHTML = `<span class="material-symbols-rounded text-xs text-amber-400">lock_open</span> <span class="text-amber-400 font-bold">Override inputs enabled for data entry</span>`;

            Swal.fire({
                icon: 'warning',
                title: 'Override Mode Enabled',
                text: 'Attendance override percentage fields are now editable. Enter values between 0 and 100.',
                timer: 2500,
                showConfirmButton: false
            });
        }

        async function promptAttOverrideDirect(regNo, studentName) {
            const input = document.getElementById(`att_override_${regNo}`);
            const currentVal = input ? (input.value.trim() || input.placeholder) : '';

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
                if (input) {
                    input.value = parseFloat(newPct).toFixed(1);
                    handleAttOverrideChange(input, regNo, studentName);
                }
            }
        }

        function clearAttOverride(regNo, studentName) {
            const input = document.getElementById(`att_override_${regNo}`);
            if (!input) return;
            input.value = '';
            handleAttOverrideChange(input, regNo, studentName);
        }

        async function handleAttOverrideChange(input, regNo, studentName) {
            const newVal = input.value.trim();
            const prevVal = input.dataset.prevVal !== undefined ? input.dataset.prevVal.trim() : '';
            if (newVal === prevVal) return;

            if (newVal !== '') {
                const num = parseFloat(newVal);
                if (isNaN(num) || num < 0 || num > 100) {
                    Swal.fire({ icon: 'warning', title: 'Invalid Percentage', text: 'Percentage must be between 0 and 100.' });
                    input.value = prevVal;
                    return;
                }
                input.value = num.toFixed(1);
                input.dataset.prevVal = input.value;
                input.classList.add('has-override');
            } else {
                input.dataset.prevVal = '';
                input.classList.remove('has-override');
            }

            calcCieRow(regNo);

            // Save individual override immediately
            try {
                await fetch(`/api/r26/classroom/health-physical/${subjectId}/attendance/override`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        reg_no: regNo,
                        override_percentage: newVal !== '' ? parseFloat(newVal) : null
                    })
                });
                const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                setGlobalSaveStatus(`Attendance override updated (${timeStr})`, false);
            } catch (e) {
                console.error(e);
            }
        }

        function calcAttendanceMark(pct) {
            if (pct === null || isNaN(pct) || pct === '') return 0.0;
            if (pct >= 90) return 5.0;
            if (pct >= 80) return 4.0;
            if (pct >= 75) return 3.0;
            if (pct >= 70) return 2.0;
            if (pct >= 65) return 1.0;
            return 0.0;
        }

        function calcCieRow(regNo) {
            // 1. Attendance
            const overrideInp = document.getElementById(`att_override_${regNo}`);
            let effectivePct = null;
            if (overrideInp && overrideInp.value.trim() !== '') {
                effectivePct = parseFloat(overrideInp.value.trim());
            } else if (overrideInp && overrideInp.placeholder !== '' && overrideInp.placeholder !== '-') {
                effectivePct = parseFloat(overrideInp.placeholder);
            }

            const attMark = calcAttendanceMark(effectivePct);
            const attMarkEl = document.getElementById(`att_mark_${regNo}`);
            if (attMarkEl) attMarkEl.innerText = attMark.toFixed(1);

            // 2. CE
            const ceConvEl = document.getElementById(`ce_conv25_${regNo}`);
            const ceConv = parseFloat(ceConvEl?.innerText || 0);

            // 3. Series Tests
            const serTotEl = document.getElementById(`cie_seriestot_${regNo}`);
            const serTot = parseFloat(serTotEl?.innerText || 0);

            // 4. Total CIE (60M)
            const cieTot = Math.min(60.0, Math.round((attMark + ceConv + serTot) * 10) / 10);
            const cieTotEl = document.getElementById(`cie_total60_${regNo}`);
            if (cieTotEl) cieTotEl.innerText = cieTot.toFixed(1);

            // 5. ESE (40M)
            const eseInp = document.getElementById(`ese_${regNo}`);
            const eseVal = parseFloat(eseInp?.value || 0);

            // 6. Grand Total (100M)
            const grandTot = Math.round((cieTot + eseVal) * 10) / 10;
            const grandTotEl = document.getElementById(`grand_total_${regNo}`);
            if (grandTotEl) grandTotEl.innerText = grandTot.toFixed(1);

            // 7. Pass Status
            const isPassed = (eseVal >= 16.0 && cieTot >= 24.0 && grandTot >= 40.0);
            const statusEl = document.getElementById(`pass_status_${regNo}`);
            if (statusEl) {
                statusEl.innerHTML = `<span class="px-2 py-0.5 rounded-full text-[11px] font-bold ${isPassed ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'}">${isPassed ? 'PASS' : 'FAIL'}</span>`;
            }
        }

        async function saveCieRegisterManual() {
            setGlobalSaveStatus('Saving CIE register...', true);
            const rows = [];
            document.querySelectorAll('.cie-student-row').forEach(tr => {
                const regNo = tr.dataset.regNo;
                const overrideInp = document.getElementById(`att_override_${regNo}`);
                const eseInp = document.getElementById(`ese_${regNo}`);

                rows.push({
                    reg_no: regNo,
                    attendance_override_pct: overrideInp ? overrideInp.value.trim() : null,
                    total_ese: eseInp && eseInp.value !== '' ? parseFloat(eseInp.value) : 0
                });
            });

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/cie-register/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ rows: rows })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    setGlobalSaveStatus(`CIE Register saved (${timeStr})`, false);
                    Swal.fire({ icon: 'success', title: 'Saved!', text: 'CIE Register updated successfully!' });
                } else {
                    setGlobalSaveStatus('Save error', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            } catch (err) {
                setGlobalSaveStatus('Network error', false);
                Swal.fire({ icon: 'error', title: 'Error', text: err.message });
            }
        }

        // =========================================================================
        // 5. LESSON PLAN AUTOSAVE & SYLLABUS UPLOAD
        // =========================================================================
        let lpAutoSaveTimer = null;
        function queueLessonPlanAutoSave(lpId) {
            setGlobalSaveStatus('Auto-saving lesson plan...', true);
            if (lpAutoSaveTimer) clearTimeout(lpAutoSaveTimer);
            lpAutoSaveTimer = setTimeout(() => saveLessonPlan(true), 750);
        }

        async function saveLessonPlan(isSilent = false) {
            const rows = document.querySelectorAll('.lesson-row');
            const plans = {};

            rows.forEach(row => {
                const id = row.dataset.lpId;
                const topic = document.getElementById(`topic_${id}`)?.value || '';
                const pdate = document.getElementById(`pdate_${id}`)?.value || '';
                const adate = document.getElementById(`adate_${id}`)?.value || '';
                const coTag = document.getElementById(`co_${id}`)?.value || 'CO1';
                const status = document.getElementById(`status_${id}`)?.value || 'Pending';

                plans[id] = {
                    topic_content: topic,
                    proposed_date: pdate,
                    actual_date: adate,
                    co_tag: coTag,
                    status: status
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                setGlobalSaveStatus(`Lesson plan saved (${timeStr})`, false);
                if (!isSilent) Swal.fire({ icon: 'success', title: 'Saved!', text: 'Lesson plan updated successfully!' });
            } catch (err) {
                setGlobalSaveStatus('Lesson plan save error', false);
                if (!isSilent) Swal.fire({ icon: 'error', title: 'Error', text: err.message });
            }
        }

        function saveLessonPlanManual() {
            saveLessonPlan(false);
        }

        async function uploadSyllabusPdf(e) {
            e.preventDefault();
            const form = document.getElementById('uploadSyllabusForm');
            const formData = new FormData(form);

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/syllabus`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Syllabus Uploaded',
                        text: 'Syllabus parsed and continuous evaluation criteria updated successfully!'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Upload Failed', text: err.message });
            }
        }

        function openPreviewModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closePreviewModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        async function initiateMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                Swal.fire({ icon: 'success', title: 'Activated', text: data.message }).then(() => window.location.reload());
            } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: err.message }); }
        }

        async function closeMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                Swal.fire({ icon: 'info', title: 'Closed', text: data.message }).then(() => window.location.reload());
            } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: err.message }); }
        }

        async function initiateExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                Swal.fire({ icon: 'success', title: 'Activated', text: data.message }).then(() => window.location.reload());
            } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: err.message }); }
        }

        async function closeExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                Swal.fire({ icon: 'info', title: 'Closed', text: data.message }).then(() => window.location.reload());
            } catch (err) { Swal.fire({ icon: 'error', title: 'Error', text: err.message }); }
        }

        // =========================================================================
        // DOM READY INITIALIZATION
        // =========================================================================
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Flatpickr
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".hp-datepicker", {
                    dateFormat: "d/m/Y",
                    allowInput: true
                });
            }

            // Grow textareas
            document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));

            // Deep link handling (?tab=continuous etc.)
            try {
                const urlParams = new URLSearchParams(window.location.search);
                const reqTab = urlParams.get('tab');
                if (reqTab && document.getElementById(`tab-content-${reqTab}`)) {
                    switchTab(reqTab);
                }
            } catch (e) {}
        });
    </script>
</body>
</html>