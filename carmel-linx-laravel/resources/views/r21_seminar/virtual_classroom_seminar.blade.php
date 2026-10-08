<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[R-2021] Virtual Seminar Room - {{ $batchSubject->subject_name }}</title>

    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS Browser CDN (matching all other Carmel Linx modules) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
            font-size: 0.78rem;
        }

        .glass-panel {
            background: #111a2e;
            border: 1px solid #1e2c47;
            border-radius: 0.75rem;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.72rem;
            line-height: 1.25;
        }

        .table-custom th {
            background-color: #0f172a;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.02em;
            padding: 0.45rem 0.35rem;
            border-bottom: 1px solid #1e293b;
            white-space: nowrap;
        }

        .table-custom td {
            background-color: #111a2e;
            border-bottom: 1px solid #1a2744;
            vertical-align: middle;
            padding: 0.35rem 0.4rem;
            font-weight: 500;
        }

        .table-custom tr:hover td {
            background-color: #17233d !important;
        }

        /* Sticky Action Column pinned to right so Evaluate button is NEVER flooded out */
        .sticky-col-action {
            position: sticky;
            right: 0;
            z-index: 10;
            background-color: #111a2e;
            border-left: 1px solid #1e2c47;
            box-shadow: -5px 0 10px rgba(0, 0, 0, 0.45);
        }
        .table-custom th.sticky-col-action {
            background-color: #0f172a;
            z-index: 20;
        }
        .table-custom tr:hover td.sticky-col-action {
            background-color: #17233d !important;
        }

        /* Remove up/down spinners from number inputs (No Up Down Arrows) */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Compact, Ergonomic Flat Slider */
        input[type=range] {
            -webkit-appearance: none;
            appearance: none;
            height: 5px;
            border-radius: 4px;
            background: #1e293b;
            outline: none;
            cursor: pointer;
            touch-action: pan-y;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid #ffffff;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            transition: transform 0.1s ease, background-color 0.15s ease;
        }

        input[type=range]::-webkit-slider-thumb:hover,
        input[type=range]::-webkit-slider-thumb:active {
            background: #2563eb;
            transform: scale(1.15);
        }

        input[type=range]::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid #ffffff;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #0f172a;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#0b0f19] text-slate-100">
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

    <!-- Sticky Top Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#0e1628] border-b border-slate-800/90 shadow-md">
        <!-- Main Spacious Title Bar -->
        <div class="px-4 py-3 sm:px-6 sm:py-3.5 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3.5">
            
            <!-- Left: Title & Classroom Badges -->
            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-950/80 border border-blue-800/70 text-blue-300 font-bold text-xs uppercase tracking-wider shadow-sm">
                            <span class="material-symbols-rounded text-base text-blue-400">record_voice_over</span>
                            Virtual Seminar Room (R-2021)
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 font-mono text-xs font-bold shadow-sm">
                            {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-950/60 border border-emerald-800/60 text-emerald-300 font-bold text-xs shadow-sm">
                            Sem {{ $classroom->current_semester ?? $batchSubject->semester ?? 'V' }} • CIA = ESE (75 Marks)
                        </span>
                    </div>
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <h1 class="text-lg sm:text-xl md:text-2xl font-black text-white tracking-tight">
                            {{ $batchSubject->subject_name }}
                        </h1>
                        <span class="text-slate-400 font-medium text-xs sm:text-sm flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-base text-slate-500">meeting_room</span>
                            {{ $classroom->classroom_name ?? $batchSubject->classroom_id }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Logged-in Faculty Name, Syllabus, Fullscreen & Direct Dashboard Back Button -->
            <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap w-full lg:w-auto justify-start lg:justify-end">
                <!-- Logged-in Faculty Name Badge (Matching 2021 Theory & Lab) -->
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700/80 shadow-sm shrink-0" title="Logged in Faculty In-Charge">
                    <span class="material-symbols-rounded text-sky-400 text-sm">person</span>
                    <span class="text-[11px] text-slate-400 font-medium">Faculty:</span>
                    <strong class="text-xs text-white font-bold">{{ $activeStaff->name ?? Session::get('userName') ?? 'Faculty' }}</strong>
                </div>

                <!-- Syllabus Button -->
                <div class="flex items-center rounded-lg border border-slate-700 bg-slate-900 overflow-hidden shadow-sm shrink-0">
                    <button type="button" onclick="openSyllabusModal()" class="px-2.5 py-1.5 text-slate-200 hover:bg-slate-800 font-medium text-xs transition flex items-center gap-1.5 cursor-pointer" title="Upload Syllabus PDF">
                        <span class="material-symbols-rounded text-sm text-blue-400">cloud_upload</span>
                        <span class="hidden sm:inline">Syllabus</span>
                    </button>
                    @if(!empty($courseFile->syllabus_pdf_path))
                        <a href="{{ $courseFile->syllabus_pdf_path }}" target="_blank" id="headerViewSyllabusBtn" class="px-2 py-1.5 bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold text-xs transition flex items-center cursor-pointer no-underline border-l border-slate-700" title="View Uploaded Syllabus PDF">
                            <span class="material-symbols-rounded text-xs">visibility</span>
                        </a>
                    @else
                        <a href="#" target="_blank" id="headerViewSyllabusBtn" class="hidden px-2 py-1.5 bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold text-xs transition items-center cursor-pointer no-underline border-l border-slate-700" title="View Uploaded Syllabus PDF">
                            <span class="material-symbols-rounded text-xs">visibility</span>
                        </a>
                    @endif
                </div>

                <!-- Fullscreen Toggle Button -->
                <button type="button" onclick="toggleFullscreen()" class="px-2.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white font-medium text-xs transition flex items-center gap-1.5 cursor-pointer shadow-sm shrink-0" title="Toggle Fullscreen">
                    <span class="material-symbols-rounded text-sm text-slate-400">fullscreen</span>
                    <span class="hidden sm:inline">Fullscreen</span>
                </button>

                <!-- Direct Dashboard Return Button (Amber Pill, matching Virtual Lab & Drawing Room) -->
                <a href="{{ $dashboardUrl ?? '/dashboard/lecturer' }}" onclick="handleSeminarBack(event)" class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shadow-amber-500/20 shrink-0" title="Return to Faculty Dashboard">
                    <span class="material-symbols-rounded text-sm">arrow_back</span>
                    <span>Dashboard</span>
                </a>
            </div>

        </div>

        <!-- Secondary Stat Strip (Spacious & Clean, no crowding) -->
        <div class="bg-[#0b101d] border-t border-slate-800/80 px-4 py-2.5 sm:px-6 flex items-center justify-between gap-3 overflow-x-auto custom-scrollbar">
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 me-1 hidden sm:inline">Overview:</span>
                
                <!-- Total -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 flex items-center gap-2 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-slate-400 text-base">groups</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Total:</span>
                        <span class="text-xs sm:text-sm font-bold text-white" id="statTotalCount">{{ $totalStudents }}</span>
                    </div>
                </div>

                <!-- Evaluated -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 flex items-center gap-2 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-emerald-400 text-base">check_circle</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Evaluated:</span>
                        <span class="text-xs sm:text-sm font-bold text-emerald-400">
                            <span id="statCompletedCount">{{ $completedCount }}</span> / {{ $totalStudents }}
                        </span>
                    </div>
                </div>

                <!-- Pending -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 flex items-center gap-2 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-amber-400 text-base">hourglass_empty</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Pending:</span>
                        <span class="text-xs sm:text-sm font-bold text-amber-400" id="statPendingCount">{{ $pendingCount }}</span>
                    </div>
                </div>

                <!-- Class Average -->
                <div class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 flex items-center gap-2 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-blue-400 text-base">analytics</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Class Avg:</span>
                        <span class="text-xs sm:text-sm font-bold text-blue-300">
                            <span id="statClassAvg">{{ $classAvg }}</span> / 75
                        </span>
                    </div>
                </div>
            </div>

            <!-- Assessor Info Indicator -->
            <div class="flex items-center gap-2 text-xs text-slate-400 shrink-0">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Active Assessor: <strong class="text-slate-200">{{ $activeStaff->name ?? Session::get('userName') ?? 'Faculty' }}</strong></span>
            </div>
        </div>
    </header>

    <!-- CARD: Professional Horizontal Tab Strip Navigation Container (Virtual Theory Classroom R-2021 Model) -->
    <div class="bg-slate-950/80 border border-slate-800/80 p-2 rounded-2xl shadow-lg my-3 mx-4 sm:mx-6">
        <nav class="flex flex-wrap md:flex-nowrap items-center gap-1.5 overflow-x-auto scrollbar-none">
            <button onclick="switchTab('evaluation')" id="tabBtn-evaluation" class="tab-btn px-4 py-2.5 text-xs md:text-sm font-bold border-2 border-blue-500 bg-blue-600/10 text-white rounded-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap shadow-[0_0_15px_rgba(59,130,246,0.25)]">
                <span class="material-symbols-rounded text-base text-blue-400">school</span>
                <span>Seminar Evaluation (75M)</span>
            </button>
            <button onclick="switchTab('schedule')" id="tabBtn-schedule" class="tab-btn px-4 py-2.5 text-xs md:text-sm font-medium text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-800/80 hover:bg-slate-900 rounded-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <span class="material-symbols-rounded text-base text-emerald-400">calendar_month</span>
                <span>Presentation Schedule &amp; Log</span>
            </button>
            <button onclick="switchTab('grades')" id="tabBtn-grades" class="tab-btn px-4 py-2.5 text-xs md:text-sm font-medium text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-800/80 hover:bg-slate-900 rounded-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <span class="material-symbols-rounded text-base text-amber-400">emoji_events</span>
                <span>Grades &amp; Results</span>
            </button>
            <button onclick="switchTab('reports')" id="tabBtn-reports" class="tab-btn px-4 py-2.5 text-xs md:text-sm font-medium text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-800/80 hover:bg-slate-900 rounded-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <span class="material-symbols-rounded text-base text-cyan-400">assessment</span>
                <span>Reports Hub</span>
            </button>
            <button onclick="switchTab('survey')" id="tabBtn-survey" class="tab-btn px-4 py-2.5 text-xs md:text-sm font-medium text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-800/80 hover:bg-slate-900 rounded-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap">
                <span class="material-symbols-rounded text-base text-purple-400">rate_review</span>
                <span>Course Attainment &amp; Survey</span>
            </button>
        </nav>
    </div>

    <!-- Main Workspace -->
    <main class="flex-grow p-3 sm:p-5 relative z-10">

        <!-- ========================================== -->
        <!-- TAB 1: Seminar Continuous Assessment (75M) -->
        <!-- ========================================== -->
        <div id="tabContent-evaluation" class="tab-pane block space-y-4">
            
            <!-- ENTER CONTINUOUS MARKS CARD -->
            <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
                <!-- Card Header: Title & Action Controls -->
                <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between flex-wrap gap-2.5">
                    <div class="font-bold text-sm text-slate-300 flex items-center gap-2 tracking-wider uppercase shrink-0">
                        <span class="material-symbols-rounded text-base text-emerald-400">edit_document</span> ENTER CONTINUOUS MARKS
                        <span class="text-xs text-slate-500 font-normal normal-case hidden sm:inline">(Clause 11.2.6 &bull; CIA = ESE 75M)</span>
                    </div>
                    
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <!-- Batch Filter Buttons -->
                        <div class="flex items-center gap-1.5 text-xs flex-wrap">
                            <span class="text-[10px] uppercase font-bold text-slate-400 me-1">Batch:</span>
                            <button type="button" onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-blue-500 text-blue-400 font-bold transition cursor-pointer">
                                Full Batch (<span id="bFilterAllCount">{{ $totalStudents }}</span>)
                            </button>
                            <button type="button" onclick="filterLabBatch('1')" id="batch-filter-1" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition cursor-pointer">
                                Batch 1 (<span id="bFilter1Count">{{ $batch1Count }}</span>)
                            </button>
                            <button type="button" onclick="filterLabBatch('2')" id="batch-filter-2" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition cursor-pointer">
                                Batch 2 (<span id="bFilter2Count">{{ $batch2Count }}</span>)
                            </button>
                            <button type="button" onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition cursor-pointer">
                                Unassigned (<span id="bFilterUnCount">{{ $unassignedCount }}</span>)
                            </button>
                            <button type="button" onclick="openLabBatchSetupModal('{{ $batchSubject->id }}')" class="px-2.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 font-bold transition flex items-center gap-1 cursor-pointer shadow-sm text-xs" title="Configure Student Lab Batch Division">
                                <span class="material-symbols-rounded text-xs text-blue-400">tune</span>
                                <span>Split setup</span>
                            </button>
                        </div>

                        <!-- Student Search -->
                        <div class="relative min-w-[150px] sm:min-w-[180px]">
                            <span class="material-symbols-rounded absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
                            <input type="text" id="studentSearchInput" placeholder="Search student..." oninput="onStudentSearch(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-lg pl-8 pr-3 py-1.5 text-xs text-white focus:outline-none focus:border-blue-500 transition">
                        </div>

                        <!-- Print Register Link -->
                        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm no-underline cursor-pointer" title="Print Consolidated Evaluation Register">
                            <span class="material-symbols-rounded text-sm">print</span>
                            <span>Print Report</span>
                        </a>

                        <!-- Save Marks Button -->
                        <button type="button" id="btnSaveSeminarMarksTop" onclick="saveAllSeminarMarks()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Save All Marks">
                            <span class="material-symbols-rounded text-sm">save</span>
                            <span>Save Marks</span>
                        </button>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse min-w-[1100px] seminar-input-table" id="evaluationTable">
                        <thead>
                            <tr class="bg-slate-900/60 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/80">
                                <th class="p-3 text-center w-12 text-slate-400 whitespace-nowrap">Roll</th>
                                <th class="p-3 text-center w-28 text-slate-400 font-mono whitespace-nowrap">SBTE No</th>
                                <th class="p-3 text-left min-w-[220px] text-slate-200 whitespace-nowrap">Name</th>
                                <th class="p-3 text-center w-16 text-slate-400 whitespace-nowrap">Batch</th>
                                <th class="p-3 text-left min-w-[180px] max-w-[240px] text-slate-400 whitespace-nowrap">Topic</th>
                                <th class="p-3 text-left min-w-[120px] max-w-[160px] text-slate-400 whitespace-nowrap">Guide</th>
                                <th class="p-3 text-center w-20 text-blue-400 whitespace-nowrap">Rel<span class="block text-[10px] text-slate-400 font-normal">7.5M</span></th>
                                <th class="p-3 text-center w-20 text-blue-400 whitespace-nowrap">Lit<span class="block text-[10px] text-slate-400 font-normal">7.5M</span></th>
                                <th class="p-3 text-center w-24 text-blue-400 whitespace-nowrap">Pres<span class="block text-[10px] text-slate-400 font-normal">37.5M</span></th>
                                <th class="p-3 text-center w-20 text-blue-400 whitespace-nowrap">Interctn<span class="block text-[10px] text-slate-400 font-normal">7.5M</span></th>
                                <th class="p-3 text-center w-20 text-blue-400 whitespace-nowrap">Report<span class="block text-[10px] text-slate-400 font-normal">7.5M</span></th>
                                <th class="p-3 text-center w-18 text-slate-400 whitespace-nowrap">Attn %<span class="block text-[10px] text-slate-500 font-normal">TEAMS</span></th>
                                <th class="p-3 text-center w-20 text-cyan-400 whitespace-nowrap">Attn Mark<span class="block text-[10px] text-cyan-300/70 font-normal">7.5M</span></th>
                                <th class="p-3 text-center w-24 text-emerald-400 bg-emerald-500/10 whitespace-nowrap">CIA<span class="block text-[9px] text-emerald-300 font-normal">Max 75M</span></th>
                                <th class="p-3 text-center w-20 text-slate-300 whitespace-nowrap">Grade</th>
                                <th class="p-3 text-center w-24 sticky-col-action text-slate-400 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody id="seminarMarksTbody">
                            @forelse($studentResults as $st)
                            <tr class="student-row border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium" 
                                id="row-eval-{{ $st['reg_no'] }}"
                                data-reg="{{ $st['reg_no'] }}"
                                data-roll="{{ $st['roll_no'] }}"
                                data-name="{{ strtolower($st['name']) }}"
                                data-batch="{{ $st['batch'] }}">
                                
                                <!-- 1. Roll -->
                                <td class="p-3 text-center font-bold text-slate-400 text-sm sm:text-base whitespace-nowrap">{{ $st['roll_no'] ?? '-' }}</td>
                                
                                <!-- 2. SBTE Reg No -->
                                <td class="p-3 text-center font-mono text-slate-200 font-bold text-sm sm:text-base whitespace-nowrap">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                                
                                <!-- 3. Name -->
                                <td class="p-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-100 text-sm sm:text-base whitespace-nowrap tracking-wide">{{ $st['name'] }}</div>
                                </td>
                                
                                <!-- 4. Batch -->
                                <td class="p-3 text-center whitespace-nowrap">
                                    @if($st['batch'] === '1')
                                        <span class="px-2 py-0.5 rounded-md bg-blue-900/40 border border-blue-500/40 text-blue-300 text-xs font-bold">B1</span>
                                    @elseif($st['batch'] === '2')
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-900/40 border border-emerald-500/40 text-emerald-300 text-xs font-bold">B2</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 text-xs">Unassigned</span>
                                    @endif
                                </td>
                                
                                <!-- 5. Topic -->
                                <td class="p-3">
                                    <div class="text-xs sm:text-sm text-slate-200 font-semibold truncate max-w-[220px] col-row-topic" title="{{ $st['topic'] ?? 'No topic assigned yet' }}">
                                        {{ $st['topic'] ?? '—' }}
                                    </div>
                                    <button type="button" onclick="openScheduleModal('{{ $st['reg_no'] }}')" class="mt-1 text-[10px] font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-0.5 cursor-pointer">
                                        <span class="material-symbols-rounded text-xs">edit_note</span>
                                        <span>{{ !empty($st['topic']) ? 'Edit Topic' : '+ Assign Topic' }}</span>
                                    </button>
                                </td>
                                
                                <!-- 6. Guide -->
                                <td class="p-3">
                                    <div class="text-xs sm:text-sm text-slate-300 font-semibold col-row-guide truncate max-w-[150px]">
                                        {{ $st['guide_name'] ?? 'Not Assigned' }}
                                    </div>
                                </td>
                                
                                <!-- 7. Relevance (7.5M) -->
                                <td class="p-2">
                                    <input type="number" 
                                           step="0.5" 
                                           min="0" 
                                           max="7.5" 
                                           value="{{ $st['my_evaluation'] ? $st['my_evaluation']['relevance'] : ($st['avg_relevance'] !== null ? $st['avg_relevance'] : '') }}" 
                                           placeholder="-" 
                                           class="seminar-mark mark-rel w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-2 py-2 text-slate-100 font-bold text-base focus:outline-none focus:border-blue-500 text-center" 
                                           data-reg="{{ $st['reg_no'] }}" 
                                           data-rubric="relevance" 
                                           onfocus="this.select()" 
                                           oninput="onSeminarMarkInput(this)" 
                                           onchange="onSeminarMarkChange(this)" 
                                           onblur="onSeminarMarkBlur(this)" 
                                           onkeydown="handleSeminarMarkKeyDown(event, this)">
                                </td>
                                
                                <!-- 8. Literature (7.5M) -->
                                <td class="p-2">
                                    <input type="number" 
                                           step="0.5" 
                                           min="0" 
                                           max="7.5" 
                                           value="{{ $st['my_evaluation'] ? $st['my_evaluation']['literature'] : ($st['avg_literature'] !== null ? $st['avg_literature'] : '') }}" 
                                           placeholder="-" 
                                           class="seminar-mark mark-lit w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-2 py-2 text-slate-100 font-bold text-base focus:outline-none focus:border-blue-500 text-center" 
                                           data-reg="{{ $st['reg_no'] }}" 
                                           data-rubric="literature" 
                                           onfocus="this.select()" 
                                           oninput="onSeminarMarkInput(this)" 
                                           onchange="onSeminarMarkChange(this)" 
                                           onblur="onSeminarMarkBlur(this)" 
                                           onkeydown="handleSeminarMarkKeyDown(event, this)">
                                </td>
                                
                                <!-- 9. Presentation (37.5M) -->
                                <td class="p-2">
                                    <input type="number" 
                                           step="0.5" 
                                           min="0" 
                                           max="37.5" 
                                           value="{{ $st['my_evaluation'] ? $st['my_evaluation']['presentation'] : ($st['avg_presentation'] !== null ? $st['avg_presentation'] : '') }}" 
                                           placeholder="-" 
                                           class="seminar-mark mark-pres w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-2 py-2 text-slate-100 font-bold text-base focus:outline-none focus:border-blue-500 text-center" 
                                           data-reg="{{ $st['reg_no'] }}" 
                                           data-rubric="presentation" 
                                           onfocus="this.select()" 
                                           oninput="onSeminarMarkInput(this)" 
                                           onchange="onSeminarMarkChange(this)" 
                                           onblur="onSeminarMarkBlur(this)" 
                                           onkeydown="handleSeminarMarkKeyDown(event, this)">
                                </td>
                                
                                <!-- 10. Interaction (7.5M) -->
                                <td class="p-2">
                                    <input type="number" 
                                           step="0.5" 
                                           min="0" 
                                           max="7.5" 
                                           value="{{ $st['my_evaluation'] ? $st['my_evaluation']['interaction'] : ($st['avg_interaction'] !== null ? $st['avg_interaction'] : '') }}" 
                                           placeholder="-" 
                                           class="seminar-mark mark-interctn w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-2 py-2 text-slate-100 font-bold text-base focus:outline-none focus:border-blue-500 text-center" 
                                           data-reg="{{ $st['reg_no'] }}" 
                                           data-rubric="interaction" 
                                           onfocus="this.select()" 
                                           oninput="onSeminarMarkInput(this)" 
                                           onchange="onSeminarMarkChange(this)" 
                                           onblur="onSeminarMarkBlur(this)" 
                                           onkeydown="handleSeminarMarkKeyDown(event, this)">
                                </td>
                                
                                <!-- 11. Report (7.5M) -->
                                <td class="p-2">
                                    <input type="number" 
                                           step="0.5" 
                                           min="0" 
                                           max="7.5" 
                                           value="{{ $st['my_evaluation'] ? $st['my_evaluation']['report'] : ($st['avg_report'] !== null ? $st['avg_report'] : '') }}" 
                                           placeholder="-" 
                                           class="seminar-mark mark-report w-full bg-slate-900/80 border border-slate-700/60 rounded-lg px-2 py-2 text-slate-100 font-bold text-base focus:outline-none focus:border-blue-500 text-center" 
                                           data-reg="{{ $st['reg_no'] }}" 
                                           data-rubric="report" 
                                           onfocus="this.select()" 
                                           oninput="onSeminarMarkInput(this)" 
                                           onchange="onSeminarMarkChange(this)" 
                                           onblur="onSeminarMarkBlur(this)" 
                                           onkeydown="handleSeminarMarkKeyDown(event, this)">
                                </td>
                                
                                <!-- 12. Attn % -->
                                <td class="p-3 text-center">
                                    <span class="font-bold text-slate-300 text-sm">{{ $st['att_percentage'] }}%</span>
                                </td>
                                
                                <!-- 13. Attn Mark (7.5M) -->
                                <td class="p-3 text-center text-cyan-400 font-mono font-bold text-base" data-attn="{{ $st['attendance_mark'] }}">
                                    {{ number_format($st['attendance_mark'], 1) }}
                                </td>
                                
                                <!-- 14. CIA (75M) -->
                                <td class="p-3 text-center bg-emerald-500/5">
                                    <span class="col-row-cia font-mono font-black text-lg {{ $st['final_score'] >= 30.0 ? 'text-emerald-400' : ($st['eval_count'] > 0 ? 'text-rose-400' : 'text-slate-500') }}">
                                        {{ $st['eval_count'] > 0 ? round($st['final_score']) : '—' }}
                                    </span>
                                </td>
                                
                                <!-- 15. Grade -->
                                <td class="p-3 text-center col-row-grade">
                                    @if($st['letter_grade'] !== '-')
                                        <span class="grade-badge-cell font-black text-xs {{ $st['letter_grade'] === 'S' ? 'text-amber-400' : ($st['letter_grade'] === 'F' ? 'text-rose-400' : 'text-slate-200') }}">
                                            Grade {{ $st['letter_grade'] }}
                                        </span>
                                    @else
                                        <span class="grade-badge-cell font-bold text-slate-500 text-xs">—</span>
                                    @endif
                                </td>
                                
                                <!-- 16. Action -->
                                <td class="p-3 text-center sticky-col-action">
                                    <button type="button" 
                                            onclick="openEvaluationModal('{{ $st['reg_no'] }}')" 
                                            class="px-2.5 py-1.5 rounded-lg bg-blue-600/80 hover:bg-blue-600 text-white font-bold text-xs transition flex items-center gap-1 mx-auto cursor-pointer shadow-sm whitespace-nowrap" 
                                            title="Open evaluation details / breakdown modal">
                                        <span class="material-symbols-rounded text-sm">tune</span>
                                        <span>Details</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="16" class="p-6 text-center text-slate-500 text-sm font-bold">
                                    No students enrolled in this classroom.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Action Bar -->
                <div class="px-4 py-3 bg-slate-900/80 border-t border-slate-800/60 flex items-center justify-between flex-wrap gap-2.5">
                    <div class="flex items-center gap-2">
                        <span id="seminarSaveStatus" class="text-xs text-slate-400 flex items-center gap-1.5 font-medium">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> Auto-save ready
                        </span>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm no-underline cursor-pointer">
                            <span class="material-symbols-rounded text-sm">print</span> Print Report
                        </a>
                        <button type="button" id="btnSaveSeminarMarksBottom" onclick="saveAllSeminarMarks()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-sm">save</span> Save Marks
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 2: Seminar Presentation Schedule & Log -->
        <!-- ========================================== -->
        <div id="tabContent-schedule" class="tab-pane hidden">
            
            <div class="mb-3 p-3 rounded-xl bg-[#111a2e] border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-lg">event_note</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Student Seminar Presentation Log &amp; Guide Allocations</div>
                        <div class="text-[11px] text-slate-400">Track presentation dates, approved seminar topics, and supervising faculty guides.</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=schedule" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-blue-950/80 hover:bg-blue-900 border border-blue-700/60 text-blue-300 hover:text-white font-semibold text-[11px] transition flex items-center gap-1.5 cursor-pointer no-underline shadow-sm" title="Print Presentation Schedule & Log">
                        <span class="material-symbols-rounded text-sm">print</span>
                        <span>Print Schedule</span>
                    </a>
                    <div class="text-xs text-slate-300 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800">
                        Total Scheduled: <strong class="text-blue-400">{{ $studentResults->whereNotNull('presentation_date')->count() }}</strong> / {{ $totalStudents }}
                    </div>
                </div>
            </div>

            <!-- Schedule Table -->
            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom" id="scheduleTable">
                        <thead>
                            <tr>
                                <th class="text-center w-8 sm:w-12">Roll</th>
                                <th class="w-20 sm:w-24">Reg No</th>
                                <th class="min-w-[140px]">Student Name</th>
                                <th class="w-12 text-center">Batch</th>
                                <th class="w-32 text-center">Presentation Date</th>
                                <th class="min-w-[200px]">Approved Seminar Topic</th>
                                <th class="min-w-[150px]">Seminar Guide</th>
                                <th class="text-center w-20">Status</th>
                                <th class="text-center w-24 sticky-col-action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="student-row" 
                                id="row-sched-{{ $st['reg_no'] }}"
                                data-reg="{{ $st['reg_no'] }}"
                                data-roll="{{ $st['roll_no'] }}"
                                data-name="{{ strtolower($st['name']) }}"
                                data-batch="{{ $st['batch'] }}">
                                
                                <td class="text-center font-bold text-slate-300">{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono text-slate-300 font-semibold text-[10px]">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                                <td>
                                    <div class="font-bold text-white">{{ $st['name'] }}</div>
                                </td>
                                <td class="text-center">
                                    @if($st['batch'] === '1')
                                        <span class="px-1.5 py-0.5 rounded bg-blue-900/40 border border-blue-500/40 text-blue-300 text-[10px] font-bold">B1</span>
                                    @elseif($st['batch'] === '2')
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-900/40 border border-emerald-500/40 text-emerald-300 text-[10px] font-bold">B2</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 text-[10px]">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Presentation Date -->
                                <td class="text-center col-sched-date">
                                    @if($st['presentation_date_formatted'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-900 border border-slate-700 font-mono text-[11px] font-bold text-slate-200">
                                            <span class="material-symbols-rounded text-xs text-blue-400">calendar_today</span>
                                            {{ $st['presentation_date_formatted'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-500 font-mono text-[11px] italic">Not Scheduled</span>
                                    @endif
                                </td>

                                <!-- Approved Seminar Topic -->
                                <td class="col-sched-topic">
                                    <div class="font-semibold text-slate-200 text-[11px]">
                                        {{ $st['topic'] ?? '— No topic registered yet —' }}
                                    </div>
                                </td>

                                <!-- Guide -->
                                <td class="col-sched-guide">
                                    @if($st['guide_name'])
                                        <div class="font-bold text-slate-200 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-xs text-blue-400">supervisor_account</span>
                                            {{ $st['guide_name'] }}
                                        </div>
                                    @else
                                        <span class="text-slate-500 italic">Not Assigned</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="text-center col-sched-status">
                                    @if($st['is_completed'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-bold text-[10px]">
                                            <span class="material-symbols-rounded text-xs">check_circle</span>
                                            Completed
                                        </span>
                                    @elseif($st['presentation_date'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-500/15 border border-blue-500/30 text-blue-400 font-bold text-[10px]">
                                            <span class="material-symbols-rounded text-xs">schedule</span>
                                            Scheduled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-500/15 border border-amber-500/30 text-amber-400 font-bold text-[10px]">
                                            <span class="material-symbols-rounded text-xs">pending</span>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Button -->
                                <td class="text-center sticky-col-action">
                                    <button type="button" 
                                            onclick="openScheduleModal('{{ $st['reg_no'] }}')" 
                                            class="px-2 sm:px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 hover:text-white font-bold text-[11px] transition flex items-center gap-1 mx-auto cursor-pointer shadow-sm whitespace-nowrap">
                                        <span class="material-symbols-rounded text-xs text-blue-400">edit_calendar</span>
                                        <span>Update</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-slate-500">
                                    No students enrolled.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 3: Consolidated CIA & SBTE Grades      -->
        <!-- ========================================== -->
        <div id="tabContent-grades" class="tab-pane hidden">
            
            <!-- Grade Distribution Cards -->
            @php
                $sGradeCount = $studentResults->where('letter_grade', 'S')->count();
                $aGradeCount = $studentResults->where('letter_grade', 'A')->count();
                $bGradeCount = $studentResults->where('letter_grade', 'B')->count();
                $cGradeCount = $studentResults->where('letter_grade', 'C')->count();
                $dGradeCount = $studentResults->where('letter_grade', 'D')->count();
                $eGradeCount = $studentResults->where('letter_grade', 'E')->count();
                $fGradeCount = $studentResults->where('letter_grade', 'F')->count();
                $passedCount = $studentResults->where('result', 'Pass')->count();
                $passRate = $completedCount > 0 ? round(($passedCount / $completedCount) * 100, 1) : 0.0;
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2 mb-4">
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">S (90%+)</div>
                    <div class="text-base font-bold text-amber-400 mt-0.5">{{ $sGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">10 Pts</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">A (80-89%)</div>
                    <div class="text-base font-bold text-blue-400 mt-0.5">{{ $aGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">9 Pts</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">B (70-79%)</div>
                    <div class="text-base font-bold text-sky-400 mt-0.5">{{ $bGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">8 Pts</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">C (60-69%)</div>
                    <div class="text-base font-bold text-teal-400 mt-0.5">{{ $cGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">7 Pts</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">D (50-59%)</div>
                    <div class="text-base font-bold text-emerald-400 mt-0.5">{{ $dGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">6 Pts</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">E (40-49%)</div>
                    <div class="text-base font-bold text-slate-300 mt-0.5">{{ $eGradeCount }}</div>
                    <div class="text-[9px] text-slate-500">5 Pts (Pass)</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">F (&lt;40%)</div>
                    <div class="text-base font-bold text-rose-400 mt-0.5">{{ $fGradeCount }}</div>
                    <div class="text-[9px] text-rose-400">Failed</div>
                </div>
                <div class="p-2.5 rounded-xl bg-[#111a2e] border border-slate-800 text-center">
                    <div class="text-[10px] uppercase font-bold text-slate-400">Pass Rate</div>
                    <div class="text-base font-bold text-white mt-0.5">{{ $passRate }}%</div>
                    <div class="text-[9px] text-slate-500">{{ $passedCount }}/{{ $completedCount }} Pass</div>
                </div>
            </div>

            <!-- Consolidated Table -->
            <div class="glass-panel overflow-hidden">
                <div class="px-4 py-2.5 bg-slate-900 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5">
                    <div>
                        <div class="font-bold text-xs text-white">Consolidated CIA Mark Register (Treated as ESE Mark - Max 75 Marks)</div>
                        <div class="text-[10.5px] text-slate-400">Statutory Clause 11.2.6: Seminar has CIA only. These continuous assessment marks constitute the final grade.</div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=cia_submission" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-emerald-950/80 hover:bg-emerald-900 border border-emerald-600/60 text-emerald-300 hover:text-white text-[11px] font-bold flex items-center gap-1.5 no-underline shadow-sm transition" title="Print Official SBTE Final Mark Entry Statement">
                            <span class="material-symbols-rounded text-sm text-emerald-400">verified</span>
                            <span>Print SBTE Marksheet</span>
                        </a>
                        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 hover:text-white text-[11px] font-bold flex items-center gap-1.5 no-underline shadow-sm transition" title="Print 6-Rubric Detailed Register">
                            <span class="material-symbols-rounded text-sm text-blue-400">print</span>
                            <span>Print Full Register</span>
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th class="text-center w-10">Roll</th>
                                <th class="w-28">Reg No</th>
                                <th>Student Name</th>
                                <th class="text-center w-14">Relevance<br><span class="text-[9px] text-slate-500">7.5</span></th>
                                <th class="text-center w-14">Literature<br><span class="text-[9px] text-slate-500">7.5</span></th>
                                <th class="text-center w-16">Presentation<br><span class="text-[9px] text-slate-500">37.5</span></th>
                                <th class="text-center w-14">Discussion<br><span class="text-[9px] text-slate-500">7.5</span></th>
                                <th class="text-center w-14">Report<br><span class="text-[9px] text-slate-500">7.5</span></th>
                                <th class="text-center w-14">Attendance<br><span class="text-[9px] text-slate-500">7.5</span></th>
                                <th class="text-center w-16">Final (75M)</th>
                                <th class="text-center w-14">Grade</th>
                                <th class="text-center w-12">Points</th>
                                <th class="text-center w-16">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr id="row-grade-{{ $st['reg_no'] }}" class="student-row" data-batch="{{ $st['batch'] }}" data-reg="{{ $st['reg_no'] }}" data-name="{{ strtolower($st['name']) }}">
                                <td class="text-center font-bold text-slate-300">{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono text-slate-300 font-semibold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                                <td class="font-bold text-white">{{ $st['name'] }}</td>
                                <td class="text-center font-mono col-grade-relevance">{{ $st['avg_relevance'] !== null ? number_format($st['avg_relevance'], 1) : '—' }}</td>
                                <td class="text-center font-mono col-grade-literature">{{ $st['avg_literature'] !== null ? number_format($st['avg_literature'], 1) : '—' }}</td>
                                <td class="text-center font-mono col-grade-presentation">{{ $st['avg_presentation'] !== null ? number_format($st['avg_presentation'], 1) : '—' }}</td>
                                <td class="text-center font-mono col-grade-interaction">{{ $st['avg_interaction'] !== null ? number_format($st['avg_interaction'], 1) : '—' }}</td>
                                <td class="text-center font-mono col-grade-report">{{ $st['avg_report'] !== null ? number_format($st['avg_report'], 1) : '—' }}</td>
                                <td class="text-center font-mono col-grade-attendance">{{ $st['avg_attendance'] !== null ? number_format($st['avg_attendance'], 1) : '—' }}</td>
                                <td class="text-center font-mono font-bold text-sm col-grade-final {{ $st['final_score'] >= 30.0 ? 'text-emerald-400' : ($st['eval_count'] > 0 ? 'text-rose-400' : 'text-slate-500') }}">
                                    {{ $st['eval_count'] > 0 ? round($st['final_score']) : '—' }}
                                </td>
                                <td class="text-center col-grade-letter">
                                    @if($st['letter_grade'] !== '-')
                                        <span class="font-bold text-xs {{ $st['letter_grade'] === 'S' ? 'text-amber-400' : ($st['letter_grade'] === 'F' ? 'text-rose-400' : 'text-slate-200') }}">
                                            {{ $st['letter_grade'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="text-center font-mono text-slate-300 col-grade-point">{{ $st['grade_point'] }}</td>
                                <td class="text-center col-grade-result">
                                    @if($st['result'] === 'Pass')
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold text-[10px]">PASS</span>
                                    @elseif($st['result'] === 'Failed')
                                        <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold text-[10px]">FAILED</span>
                                    @else
                                        <span class="text-slate-500 text-[10px]">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="text-center py-6 text-slate-500">No student records available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- ========================================== -->
        <!-- TAB 4: Statutory Reports & Print Hub       -->
        <!-- ========================================== -->
        <div id="tabContent-reports" class="tab-pane hidden space-y-4">
            
            <div class="bg-slate-950/80 border border-slate-800/80 rounded-2xl p-5 shadow-lg space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center">
                            <span class="material-symbols-rounded text-xl">print</span>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-white">Reports &amp; Print Hub</h2>
                            <p class="text-xs text-slate-400">Official Seminar Statements &bull; Revision 2021 (Clause 11.2.6)</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" 
                           class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 hover:text-white font-bold text-xs transition flex items-center gap-1.5 cursor-pointer no-underline">
                            <span class="material-symbols-rounded text-sm text-emerald-400">calendar_month</span>
                            <span>Class Attendance Log</span>
                        </a>
                    </div>
                </div>

                <!-- Clean Print Buttons Grid (No Big Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    
                    <!-- 1. CIA Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=cia" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-emerald-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">verified</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-emerald-300 transition-colors flex items-center justify-between">
                                <span>CIA Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-emerald-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">SBTE Final CIA Entry Statement (75M)</div>
                        </div>
                    </a>

                    <!-- 2. Attendance Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=attendance" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-cyan-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-cyan-600/20 border border-cyan-500/40 text-cyan-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">how_to_reg</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-cyan-300 transition-colors flex items-center justify-between">
                                <span>Attendance Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-cyan-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">TEAMS Log &amp; Attendance Marks (7.5M)</div>
                        </div>
                    </a>

                    <!-- 3. Consolidated Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-blue-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">assignment</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-blue-300 transition-colors flex items-center justify-between">
                                <span>Consolidated Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-blue-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">Full 6-Rubrics Clause 11.2.6 Register (75M)</div>
                        </div>
                    </a>

                    <!-- 4. Seminar Topic Splitup Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=topic_splitup" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-purple-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-purple-600/20 border border-purple-500/40 text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">category</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-purple-300 transition-colors flex items-center justify-between">
                                <span>Topic Splitup Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-purple-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">Seminar Topic Allocation &amp; Technical Domain</div>
                        </div>
                    </a>

                    <!-- 5. ESE Grade Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=ese" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-amber-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-amber-600/20 border border-amber-500/40 text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">emoji_events</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-amber-300 transition-colors flex items-center justify-between">
                                <span>ESE Grade Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-amber-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">Final SBTE Grades (S–F) &amp; Result Statement</div>
                        </div>
                    </a>

                    <!-- 6. Print Schedule Report -->
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=schedule" target="_blank" 
                       class="p-4 rounded-xl bg-slate-900/90 hover:bg-slate-800/90 border border-slate-800 hover:border-rose-500/60 transition group flex items-center gap-3.5 no-underline shadow-sm">
                        <div class="w-11 h-11 rounded-xl bg-rose-600/20 border border-rose-500/40 text-rose-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-rounded text-2xl">calendar_month</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs sm:text-sm font-bold text-white group-hover:text-rose-300 transition-colors flex items-center justify-between">
                                <span>Print Schedule Report</span>
                                <span class="material-symbols-rounded text-sm text-slate-500 group-hover:text-rose-400 transition-colors">open_in_new</span>
                            </div>
                            <div class="text-[11px] text-slate-400 truncate mt-0.5">Presentation Schedule &amp; Guide Log Register</div>
                        </div>
                    </a>

                </div>
            </div>

        </div>

        <!-- TAB 5: Course Exit Survey & Attainment -->
        <div id="tabContent-survey" class="tab-pane hidden space-y-5">
            <!-- Header Panel -->
            <div class="glass-panel p-4 border border-purple-500/30 bg-gradient-to-r from-slate-900/95 via-purple-950/20 to-slate-900/95 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800/80 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                            <span class="material-symbols-rounded text-xl">assignment_turned_in</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-white">End Semester Exit Survey (Indirect Attainment - 20% Weightage)</h3>
                                <span id="seminarSurveyStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">Checking...</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">Collect student feedback on CO1–CO3 seminar outcomes to calculate indirect attainment for accreditation.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button id="btnOpenSeminarExitSurvey" onclick="initiateSeminarExitSurvey()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md cursor-pointer">
                            <span class="material-symbols-rounded text-sm">play_arrow</span> Open Survey
                        </button>
                        <button id="btnCloseSeminarExitSurvey" onclick="closeSeminarExitSurvey()" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md cursor-pointer hidden">
                            <span class="material-symbols-rounded text-sm">lock</span> Close &amp; Lock
                        </button>
                        <button id="btnCopySeminarSurveyLink" onclick="copySeminarSurveyLink()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer hidden">
                            <span class="material-symbols-rounded text-sm">content_copy</span> Copy Student Link
                        </button>
                        <a id="btnTestSeminarSurveyLink" href="#" target="_blank" class="px-3.5 py-1.5 bg-sky-600/20 hover:bg-sky-600/30 text-sky-300 border border-sky-500/30 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all hidden">
                            <span class="material-symbols-rounded text-sm">open_in_new</span> Test Form
                        </a>
                        <a id="btnPrintSeminarSurveyReport" href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                            <span class="material-symbols-rounded text-sm">print</span> Survey Report
                        </a>
                    </div>
                </div>

                <!-- Live Survey Response Stats & URL Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <div class="bg-slate-900/70 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Responses</span>
                        <div class="flex items-center justify-between mt-1">
                            <span id="seminarSurveyResponseStat" class="text-sm font-bold text-white font-mono">0 / {{ $totalStudents }} Submitted</span>
                            <span id="seminarSurveyResponsePct" class="text-xs text-sky-400 font-mono font-bold">0%</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 mt-1.5 overflow-hidden">
                            <div id="seminarSurveyProgressBar" class="bg-sky-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="bg-slate-900/70 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">NBA Attainment Weightage Rule</span>
                        <div class="text-slate-200 mt-1 font-mono text-[11px] flex items-center gap-1.5">
                            <span class="text-emerald-400 font-bold">80% Direct CIE</span> + <span class="text-purple-400 font-bold">20% Indirect Exit</span> = <span class="text-white font-bold">100% Overall</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Direct: Continuous Internal Assessment (75M). Indirect: Survey rating average (1 to 3 scale).</p>
                    </div>
                    <div class="bg-slate-900/70 p-3 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Survey URL</span>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="text" id="seminarSurveyUrlInput" readonly class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-[11px] text-slate-300 font-mono select-all" value="Initiate survey to generate student link">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attainment Matrix Table -->
            <div class="glass-panel overflow-hidden border border-slate-700/80 shadow-lg">
                <div class="px-5 py-3 bg-[#0f172a] border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-base text-blue-400">analytics</span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-200">CO Attainment Matrix (Direct + Indirect)</h4>
                    </div>
                    <div class="text-xs font-mono text-slate-400" id="seminarAttainmentSummaryText">
                        Loading Attainment...
                    </div>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom" id="seminarAttainmentTable">
                        <thead>
                            <tr>
                                <th class="w-16">CO Code</th>
                                <th>Course Outcome Description</th>
                                <th class="text-center w-28">Direct CIE Level<br><span class="text-slate-400 font-normal">80% Weight</span></th>
                                <th class="text-center w-28">Indirect Survey<br><span class="text-slate-400 font-normal">20% Weight</span></th>
                                <th class="text-center w-28 bg-purple-950/30 text-purple-300 font-bold">Overall Level<br><span class="font-normal">(Max 3.0)</span></th>
                            </tr>
                        </thead>
                        <tbody id="seminarAttainmentTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-6 text-slate-400">Click "Course Exit Survey &amp; Attainment" to load attainment data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- ========================================================== -->
    <!-- MODAL 1: Compact, Professional Seminar Evaluation Dialog  -->
    <!-- Centered Card Modal (Max 780px wide, User-Friendly Colors) -->
    <!-- ========================================================== -->
    <div id="evaluationModal" class="fixed inset-0 z-[80] hidden bg-slate-950/80 backdrop-blur-md items-center justify-center p-3 sm:p-4 overflow-y-auto">
        
        <div class="bg-[#111a2e] border border-slate-700/80 rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden flex flex-col my-auto max-h-[94vh]">

            <!-- Modal Header -->
            <div class="px-5 py-3 bg-[#0c1322] border-b border-slate-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-lg">rate_review</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm sm:text-base font-extrabold text-white leading-tight" id="evalModalStudentName">Student Evaluation</span>
                            <span class="text-[11px] text-slate-400 font-mono px-2 py-0.2 rounded bg-slate-900 border border-slate-800" id="evalModalStudentMeta">
                                Reg: - • Roll: -
                            </span>
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Clause 11.2.6 Evaluation Rubrics &bull; Max 75 Marks</div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <!-- Live Header Score Pill -->
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-900 border border-slate-800 font-mono text-xs">
                        <span class="text-slate-400 font-medium">Total:</span>
                        <span class="font-bold text-blue-400 text-sm" id="evalHeaderScoreVal">0.0</span>
                        <span class="text-slate-500 text-[10px]">/ 75</span>
                    </div>

                    <button type="button" onclick="closeEvaluationModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer" title="Close Modal (Esc)">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>
            </div>

            <!-- Form wrapping Body & Footer -->
            <form id="evaluationForm" onsubmit="submitEvaluationForm(event)" class="flex flex-col overflow-hidden flex-grow">
                <input type="hidden" id="evalRegNo" name="reg_no">

                <!-- Scrollable Body (clean padding, no excess height) -->
                <div class="p-3.5 sm:p-4 overflow-y-auto custom-scrollbar space-y-3">
                    
                    <!-- Metadata Strip: Topic, Assessor, Guide & Date -->
                    <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800 space-y-2.5">
                        
                        <!-- Row 1: Approved Seminar Topic -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 mb-1">Approved Seminar Topic</label>
                            <input type="text" id="evalTopicInput" placeholder="Enter or edit seminar topic..." class="w-full bg-slate-950 border border-slate-700/80 rounded-lg px-3 py-1.5 text-white text-xs focus:border-blue-500 outline-none transition">
                        </div>

                        <!-- Row 2: Assessor, Guide & Presentation Date (3 columns) -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Evaluating Assessor</label>
                                <select id="evalAssessorMobile" class="w-full bg-slate-950 border border-slate-700/80 rounded-lg px-2.5 py-1.5 text-white text-xs focus:border-blue-500 outline-none transition" onchange="onAssessorChange(this.value)">
                                    @foreach($guides as $g)
                                        <option value="{{ $g->mobile_no }}" {{ ($activeStaff && $activeStaff->mobile_no == $g->mobile_no) ? 'selected' : '' }}>
                                            {{ $g->name }} ({{ $g->designation }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="hidden" id="currentAssessorDisplay">{{ $activeStaff->name ?? 'Faculty' }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Assigned Guide</label>
                                <select id="evalGuideSelect" class="w-full bg-slate-950 border border-slate-700/80 rounded-lg px-2.5 py-1.5 text-white text-xs focus:border-blue-500 outline-none transition">
                                    <option value="">— Select Guide —</option>
                                    @foreach($guides as $g)
                                        <option value="{{ $g->mobile_no }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Presentation Date</label>
                                <input type="date" id="evalPresentationDateInput" class="w-full bg-slate-950 border border-slate-700/80 rounded-lg px-2.5 py-1.5 text-white text-xs font-mono focus:border-blue-500 outline-none transition">
                            </div>
                        </div>

                    </div>

                    <!-- Committee Breakdown Box (Shown if other faculty evaluated) -->
                    <div id="evalCommitteeBreakdownBox" class="hidden px-3 py-2 rounded-xl bg-slate-900/90 border border-blue-900/50">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-base text-blue-400">group</span>
                                <span>Recorded Committee Scores</span>
                            </span>
                            <span class="text-xs font-semibold text-blue-300" id="evalBreakdownAvgText">Average: 0 / 75</span>
                        </div>
                        <div id="evalBreakdownList" class="mt-1.5 space-y-1 text-xs"></div>
                    </div>

                    <!-- 6 Rubric Criteria in a Clean, Compact 2-Column Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Criterion 1: Relevance of Topic (Max 7.5) -->
                        <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold flex items-center justify-center shrink-0">1</span>
                                    <span class="font-bold text-slate-200 text-xs truncate">Relevance of Topic</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_relevance" class="w-14 bg-slate-950 border border-slate-700 rounded px-1.5 py-0.5 text-center text-blue-400 font-bold text-xs focus:border-blue-400 outline-none" oninput="syncEvalSlider('relevance')">
                                    <span class="text-slate-500 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_relevance" class="w-full cursor-pointer" oninput="syncEvalInput('relevance')">
                        </div>

                        <!-- Criterion 3 (HERO): Presentation Delivery (Max 37.5 - 50%) -->
                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-blue-950/40 to-slate-900 border border-blue-600/50 shadow-sm space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0 shadow-sm">3</span>
                                    <span class="font-extrabold text-white text-xs truncate">Presentation Delivery</span>
                                    <span class="px-1.5 py-0.2 rounded bg-blue-600/30 text-blue-300 text-[9px] font-bold border border-blue-500/40">50%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="37.5" id="input_presentation" class="w-16 bg-slate-950 border border-blue-500 rounded px-1.5 py-0.5 text-center text-blue-300 font-black text-xs focus:border-blue-400 outline-none" oninput="syncEvalSlider('presentation')">
                                    <span class="text-blue-400 font-bold text-[10px]">/ 37.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="37.5" step="0.5" id="range_presentation" class="w-full cursor-pointer accent-blue-500" oninput="syncEvalInput('presentation')">
                        </div>

                        <!-- Criterion 2: Literature Survey (Max 7.5) -->
                        <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold flex items-center justify-center shrink-0">2</span>
                                    <span class="font-bold text-slate-200 text-xs truncate">Literature Survey</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_literature" class="w-14 bg-slate-950 border border-slate-700 rounded px-1.5 py-0.5 text-center text-blue-400 font-bold text-xs focus:border-blue-400 outline-none" oninput="syncEvalSlider('literature')">
                                    <span class="text-slate-500 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_literature" class="w-full cursor-pointer" oninput="syncEvalInput('literature')">
                        </div>

                        <!-- Criterion 4: Interaction / Discussion (Max 7.5) -->
                        <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold flex items-center justify-center shrink-0">4</span>
                                    <span class="font-bold text-slate-200 text-xs truncate">Interaction / Viva</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_interaction" class="w-14 bg-slate-950 border border-slate-700 rounded px-1.5 py-0.5 text-center text-blue-400 font-bold text-xs focus:border-blue-400 outline-none" oninput="syncEvalSlider('interaction')">
                                    <span class="text-slate-500 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_interaction" class="w-full cursor-pointer" oninput="syncEvalInput('interaction')">
                        </div>

                        <!-- Criterion 5: Seminar Report (Max 7.5) -->
                        <div class="p-2.5 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 transition space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-bold flex items-center justify-center shrink-0">5</span>
                                    <span class="font-bold text-slate-200 text-xs truncate">Seminar Report</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_report" class="w-14 bg-slate-950 border border-slate-700 rounded px-1.5 py-0.5 text-center text-blue-400 font-bold text-xs focus:border-blue-400 outline-none" oninput="syncEvalSlider('report')">
                                    <span class="text-slate-500 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_report" class="w-full cursor-pointer" oninput="syncEvalInput('report')">
                        </div>

                        <!-- Criterion 6: Attendance (Max 7.5 - Authoritative TEAMS Attendance) -->
                        <div class="p-2.5 rounded-xl bg-emerald-950/30 border border-emerald-800/60 transition space-y-1.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-emerald-700 text-white text-[10px] font-bold flex items-center justify-center shrink-0">6</span>
                                    <span class="font-bold text-emerald-300 text-xs">Attendance (10% = 7.5M)</span>
                                    <span class="px-1.5 py-0.2 rounded bg-emerald-900/60 border border-emerald-700/60 text-emerald-300 text-[9px] font-mono font-bold" id="modalAttBadge">TEAMS Log</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_attendance" readonly class="w-14 bg-slate-950 border border-emerald-600/50 rounded px-1.5 py-0.5 text-center text-emerald-300 font-black text-xs outline-none cursor-not-allowed">
                                    <span class="text-slate-500 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <span id="modalAttHelpText">Calculated from official TEAMS attendance log</span>
                                <span class="font-bold text-emerald-400 font-mono" id="modalSuggestedAttVal">7.5 M</span>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_attendance" class="w-full cursor-not-allowed accent-emerald-500 opacity-60" disabled>
                        </div>

                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-5 py-3 bg-[#0c1322] border-t border-slate-800 flex items-center justify-between gap-3 shrink-0 shadow-lg">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div>
                            <div class="text-[9px] uppercase font-bold text-slate-400">Total Score</div>
                            <div class="text-lg sm:text-xl font-black text-white font-mono leading-tight" id="evalLiveTotal">
                                0.0 <span class="text-[11px] text-slate-500 font-normal">/ 75.0</span>
                            </div>
                        </div>
                        <div class="border-l border-slate-800 pl-3">
                            <div class="text-[9px] uppercase font-bold text-slate-400">Grade</div>
                            <div class="text-xs sm:text-sm font-extrabold" id="evalLiveGrade">—</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeEvaluationModal()" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="btnSaveEval" class="px-5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-rounded text-base">save</span>
                            <span>Save Score</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: Seminar Schedule & Log Modal     -->
    <!-- ========================================== -->
    <div id="scheduleModal" class="fixed inset-0 z-[80] hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-0 sm:p-4">
        <div class="bg-[#111a2e] border border-slate-700 sm:rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col h-full sm:h-auto sm:max-h-[90vh]">
            
            <!-- Modal Header -->
            <div class="px-5 py-4 bg-slate-900 border-b border-slate-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">calendar_month</span>
                    </div>
                    <div>
                        <div class="text-sm sm:text-base font-bold text-white" id="schedModalStudentName">Seminar Topic &amp; Presentation Schedule</div>
                        <div class="text-xs text-slate-400 font-mono" id="schedModalStudentMeta">Reg: -</div>
                    </div>
                </div>
                <button type="button" onclick="closeScheduleModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="scheduleForm" onsubmit="submitScheduleForm(event)" class="p-4 sm:p-6 space-y-4 text-xs sm:text-sm overflow-y-auto custom-scrollbar flex-grow">
                <input type="hidden" id="schedRegNo" name="reg_no">

                <!-- Topic -->
                <div class="space-y-1.5">
                    <label class="block text-slate-200 font-bold">Approved Seminar Topic</label>
                    <textarea id="schedTopic" rows="3" required placeholder="Enter approved seminar topic title..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 outline-none custom-scrollbar text-xs sm:text-sm"></textarea>
                </div>

                <!-- Guide -->
                <div class="space-y-1.5">
                    <label class="block text-slate-200 font-bold">Assigned Faculty Guide</label>
                    <select id="schedGuideMobile" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs sm:text-sm">
                        <option value="">— Select Faculty Guide —</option>
                        @foreach($guides as $g)
                            <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Presentation Date -->
                <div class="space-y-1.5">
                    <label class="block text-slate-200 font-bold">Presentation Date</label>
                    <input type="date" id="schedPresentationDate" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white font-mono focus:border-blue-500 outline-none text-xs sm:text-sm">
                    <p class="text-[11px] text-slate-400">Date on which student delivers the seminar presentation before the committee.</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-3">
                    <button type="button" onclick="closeScheduleModal()" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition cursor-pointer min-h-[44px]">
                        Cancel
                    </button>
                    <button type="submit" id="btnSaveSchedule" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer min-h-[44px]">
                        <span class="material-symbols-rounded text-base">save</span>
                        <span>Save Topic &amp; Schedule</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODAL 3: Faculty Evaluators Breakdown Modal               -->
    <!-- ========================================================== -->
    <div id="facultyBreakdownModal" class="fixed inset-0 z-[85] hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-0 sm:p-4">
        <div class="bg-[#111a2e] border border-slate-700 sm:rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col h-full sm:h-auto sm:max-h-[90vh]">
            
            <div class="px-5 py-4 bg-slate-900 border-b border-slate-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">groups</span>
                    </div>
                    <div>
                        <div class="text-sm sm:text-base font-bold text-white" id="breakdownModalStudentName">Faculty Evaluation Breakdown</div>
                        <div class="text-xs text-slate-400 font-mono" id="breakdownModalStudentMeta">Reg: -</div>
                    </div>
                </div>
                <button type="button" onclick="closeFacultyBreakdownModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            <div class="p-4 sm:p-6 space-y-3.5 text-xs sm:text-sm overflow-y-auto max-h-[70vh] custom-scrollbar flex-grow">
                <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-sm">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Final Averaged CIA Mark</div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono" id="breakdownFinalAvg">0.0 <span class="text-xs sm:text-sm text-slate-400 font-normal">/ 75.0</span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] uppercase font-bold text-slate-400">SBTE Grade</div>
                        <div class="text-base sm:text-lg font-extrabold text-white mt-0.5" id="breakdownFinalGrade">—</div>
                    </div>
                </div>

                <div class="text-xs font-bold text-slate-300">Individual Faculty Assessor Marks:</div>
                <div id="breakdownCardsContainer" class="space-y-2"></div>
            </div>

            <div class="px-5 py-3.5 bg-slate-900 border-t border-slate-800 flex justify-end shrink-0">
                <button type="button" onclick="closeFacultyBreakdownModal()" class="w-full sm:w-auto px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold transition min-h-[44px]">
                    Close
                </button>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 4: Syllabus Upload Modal            -->
    <!-- ========================================== -->
    <div id="syllabusModal" class="fixed inset-0 z-[80] hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-0 sm:p-4">
        <div class="bg-[#111a2e] border border-slate-700 sm:rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col h-full sm:h-auto">
            
            <div class="px-5 py-4 bg-slate-900 border-b border-slate-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-rounded text-xl">cloud_upload</span>
                    </div>
                    <div class="text-sm sm:text-base font-bold text-white">Upload Seminar Syllabus PDF</div>
                </div>
                <button type="button" onclick="closeSyllabusModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            <form id="syllabusForm" onsubmit="submitSyllabusForm(event)" class="p-4 sm:p-6 space-y-4 text-xs sm:text-sm">
                <div class="space-y-2">
                    <label class="block text-slate-300 font-bold">Select Official Syllabus File (PDF, Max 15MB)</label>
                    <input type="file" id="syllabusFileInput" name="syllabus_file" accept=".pdf,application/pdf" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer cursor-pointer">
                </div>

                <div id="syllabusUploadMsg" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeSyllabusModal()" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition min-h-[44px]">
                        Cancel
                    </button>
                    <button type="submit" id="btnUploadSyllabus" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer min-h-[44px]">
                        <span class="material-symbols-rounded text-base">upload</span>
                        <span>Upload File</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Practical Batch Setup Modal Include -->
    @include('partials.lab_batch_setup_modal')

    <!-- Student Data JSON Cache for JavaScript -->
    <script>
        const subjectId = {{ $batchSubject->id }};
        const currentLoggedInMobile = "{{ $activeStaff->mobile_no ?? Session::get('userId') }}";
        const studentDataset = @json($studentResults);
        let activeBatchFilter = 'All';

        // ---------------- BACK NAVIGATION ----------------
        function handleSeminarBack(e) {
            if (e) e.preventDefault();
            // 1. If opened by a parent window (window.open), close and focus caller
            if (window.opener && !window.opener.closed) {
                window.close();
                return;
            }
            // 2. Direct clean return to Faculty Dashboard
            window.location.href = "{{ $dashboardUrl ?? '/dashboard/lecturer' }}";
        }

        function returnToParent() {
            handleSeminarBack();
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error("Fullscreen request failed: ", err);
                });
            } else {
                document.exitFullscreen();
            }
        }

        // ---------------- PRINT REPORTS DROPDOWN ----------------
        function togglePrintDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('printDropdownMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const container = document.getElementById('printReportsDropdownContainer');
            const menu = document.getElementById('printDropdownMenu');
            if (container && menu && !container.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // ---------------- TAB SWITCHING ----------------
        function switchTab(tabKey) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-2', 'border-blue-500', 'bg-blue-600/10', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.25)]', 'font-bold');
                btn.classList.add('border-transparent', 'text-slate-400', 'font-medium');
            });

            const targetPane = document.getElementById(`tabContent-${tabKey}`);
            const targetBtn = document.getElementById(`tabBtn-${tabKey}`);
            if (targetPane) targetPane.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.add('border-2', 'border-blue-500', 'bg-blue-600/10', 'text-white', 'shadow-[0_0_15px_rgba(59,130,246,0.25)]', 'font-bold');
                targetBtn.classList.remove('border-transparent', 'text-slate-400', 'font-medium');
            }

            if (tabKey === 'survey') {
                loadSeminarSurveyData();
            }
        }

        // ---------------- COURSE EXIT SURVEY & ATTAINMENT ----------------
        let activeSeminarSurveyId = null;
        let activeSeminarSurveyUrl = null;

        function loadSeminarSurveyData() {
            fetch(`/r21/classroom/seminar/${subjectId}/attainment/summary`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS' && res.data) {
                    renderSeminarAttainment(res.data);
                    if (res.data.survey) {
                        updateSeminarSurveyUi(res.data.survey);
                    }
                }
            })
            .catch(err => console.error('Error loading seminar attainment/survey:', err));
        }

        function updateSeminarSurveyUi(survey) {
            if (!survey) return;
            activeSeminarSurveyId = survey.id;
            activeSeminarSurveyUrl = survey.student_url;

            const badge = document.getElementById('seminarSurveyStatusBadge');
            const btnOpen = document.getElementById('btnOpenSeminarExitSurvey');
            const btnClose = document.getElementById('btnCloseSeminarExitSurvey');
            const btnCopy = document.getElementById('btnCopySeminarSurveyLink');
            const btnTest = document.getElementById('btnTestSeminarSurveyLink');
            const urlInput = document.getElementById('seminarSurveyUrlInput');
            const statText = document.getElementById('seminarSurveyResponseStat');
            const pctText = document.getElementById('seminarSurveyResponsePct');
            const progBar = document.getElementById('seminarSurveyProgressBar');

            const responded = survey.responded_count || 0;
            const total = survey.total_students || 1;
            const pct = Math.round((responded / total) * 100);

            if (statText) statText.innerText = `${responded} / ${total} Submitted`;
            if (pctText) pctText.innerText = `${pct}%`;
            if (progBar) progBar.style.width = `${pct}%`;

            if (survey.status === 'Active') {
                if (badge) {
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/40';
                    badge.innerText = 'Survey Active';
                }
                if (btnOpen) btnOpen.classList.add('hidden');
                if (btnClose) btnClose.classList.remove('hidden');
                if (btnCopy) btnCopy.classList.remove('hidden');
                if (btnTest) {
                    btnTest.classList.remove('hidden');
                    btnTest.href = survey.student_url || '#';
                }
                if (urlInput) urlInput.value = survey.student_url || '';
            } else if (survey.status === 'Completed') {
                if (badge) {
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-purple-500/20 text-purple-400 border border-purple-500/40';
                    badge.innerText = 'Survey Closed & Locked';
                }
                if (btnOpen) {
                    btnOpen.classList.remove('hidden');
                    btnOpen.innerHTML = '<span class="material-symbols-rounded text-sm">restart_alt</span> Re-Open Survey';
                }
                if (btnClose) btnClose.classList.add('hidden');
                if (btnCopy) btnCopy.classList.add('hidden');
                if (btnTest) btnTest.classList.add('hidden');
                if (urlInput) urlInput.value = 'Survey Closed and Locked';
            } else {
                if (badge) {
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700';
                    badge.innerText = 'Not Initiated';
                }
                if (btnOpen) {
                    btnOpen.classList.remove('hidden');
                    btnOpen.innerHTML = '<span class="material-symbols-rounded text-sm">play_arrow</span> Open Survey';
                }
                if (btnClose) btnClose.classList.add('hidden');
                if (btnCopy) btnCopy.classList.add('hidden');
                if (btnTest) btnTest.classList.add('hidden');
                if (urlInput) urlInput.value = 'Initiate survey to generate student link';
            }
        }

        function initiateSeminarExitSurvey() {
            if (!confirm('Open End Semester Course Exit Survey for all enrolled Seminar students?')) return;

            fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Course Exit Survey initiated successfully! Students can now submit their feedback.');
                    loadSeminarSurveyData();
                } else {
                    alert('Notice: ' + res.message);
                    loadSeminarSurveyData();
                }
            })
            .catch(err => alert('Network error while initiating survey.'));
        }

        function closeSeminarExitSurvey() {
            if (!confirm('Are you sure you want to close and lock this survey? Student submissions will be locked and indirect attainment finalized.')) return;

            fetch(`/api/classroom/${subjectId}/course-exit/close`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Course Exit Survey locked and finalized successfully.');
                    loadSeminarSurveyData();
                } else {
                    alert('Error: ' + res.message);
                }
            })
            .catch(err => alert('Network error while closing survey.'));
        }

        function copySeminarSurveyLink() {
            const urlInput = document.getElementById('seminarSurveyUrlInput');
            if (!urlInput || !urlInput.value || urlInput.value.indexOf('http') === -1) {
                alert('No active survey URL to copy.');
                return;
            }
            navigator.clipboard.writeText(urlInput.value).then(() => {
                alert('Survey Link copied to clipboard!\n\n' + urlInput.value);
            }).catch(() => {
                urlInput.select();
                document.execCommand('copy');
                alert('Survey Link copied to clipboard!');
            });
        }

        function renderSeminarAttainment(data) {
            const summaryText = document.getElementById('seminarAttainmentSummaryText');
            if (summaryText) {
                summaryText.innerHTML = `Direct CIE: <strong class="text-emerald-400">${data.average_direct}</strong> | Indirect Exit: <strong class="text-purple-400">${data.average_indirect}</strong> | Overall: <strong class="text-white">${data.average_overall}</strong> / 3.0`;
            }

            const tbody = document.getElementById('seminarAttainmentTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            const matrix = data.matrix || [];
            matrix.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="font-bold text-sky-400 font-mono">${row.co_tag}</td>
                    <td class="text-slate-200 text-xs">${row.description}</td>
                    <td class="text-center font-mono font-bold text-emerald-400">${row.cie_level}</td>
                    <td class="text-center font-mono font-bold text-purple-400">${row.indirect_attainment}</td>
                    <td class="text-center font-mono font-bold bg-purple-950/20 text-white text-sm">${row.overall_attainment}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // ---------------- BATCH FILTERING ----------------
        function filterLabBatch(batch) {
            activeBatchFilter = batch;
            document.querySelectorAll('.batch-filter-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-400', 'font-bold');
                btn.classList.add('border-slate-800', 'text-slate-400', 'font-medium');
            });

            const activeBtnId = (batch === 'All') ? 'All' : (batch === 'Unassigned' ? 'Unassigned' : (batch === '1' ? '1' : '2'));
            const targetBtn = document.getElementById(`batch-filter-${activeBtnId}`);
            if (targetBtn) {
                targetBtn.classList.remove('border-slate-800', 'text-slate-400', 'font-medium');
                targetBtn.classList.add('border-blue-500', 'text-blue-400', 'font-bold');
            }

            applyFilters();
        }

        // ---------------- SEARCH FILTERING ----------------
        function onStudentSearch(query) {
            applyFilters();
        }

        function applyFilters() {
            const query = (document.getElementById('studentSearchInput').value || '').trim().toLowerCase();

            document.querySelectorAll('.student-row').forEach(row => {
                const sbRaw = row.getAttribute('data-batch') || 'Unassigned';
                const sb = (sbRaw === '1' || sbRaw === 'Batch 1') ? '1' : ((sbRaw === '2' || sbRaw === 'Batch 2') ? '2' : 'Unassigned');
                const reg = (row.getAttribute('data-reg') || '').toLowerCase();
                const roll = (row.getAttribute('data-roll') || '').toLowerCase();
                const name = (row.getAttribute('data-name') || '').toLowerCase();

                let matchBatch = (activeBatchFilter === 'All') || (activeBatchFilter === sb);
                let matchSearch = !query || reg.includes(query) || roll.includes(query) || name.includes(query);

                if (matchBatch && matchSearch) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // ---------------- EVALUATION MODAL & LIVE MATH ----------------
        const criteriaConfig = {
            relevance: { max: 7.5 },
            literature: { max: 7.5 },
            presentation: { max: 37.5 },
            interaction: { max: 7.5 },
            report: { max: 7.5 },
            attendance: { max: 7.5 }
        };

        function openEvaluationModal(regNo) {
            try {
                const st = studentDataset.find(s => s.reg_no === regNo);
                if (!st) {
                    console.error("Student record not found for regNo:", regNo);
                    return;
                }

                const setVal = (id, val) => {
                    const el = document.getElementById(id);
                    if (el) el.value = val;
                };
                const setText = (id, txt) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = txt;
                };

                setVal('evalRegNo', st.reg_no);
                setText('evalModalStudentName', st.name);
                setText('evalModalStudentMeta', `Reg: ${st.sbte_reg_no || st.reg_no} | Roll: ${st.roll_no || '-'}`);
                
                // Topic, Guide, Date inputs inside evaluation modal
                setVal('evalTopicInput', st.topic || '');
                setVal('evalGuideSelect', st.guide_mobile_no || '');
                setVal('evalPresentationDateInput', st.presentation_date || '');

                // Attendance help & value
                const attVal = st.attendance_mark !== undefined ? st.attendance_mark : st.suggested_att_mark;
                setText('modalSuggestedAttVal', `${Number(attVal).toFixed(1)} M`);
                setText('modalAttHelpText', `TEAMS class attendance: ${st.att_percentage}% → Authoritative: ${Number(attVal).toFixed(1)} / 7.5 M`);

                // Reset Assessor Selector to current logged-in user or first assessor
                const assessorSel = document.getElementById('evalAssessorMobile');
                if (assessorSel && currentLoggedInMobile) {
                    assessorSel.value = currentLoggedInMobile;
                }

                // Render committee breakdown box if other faculty evaluated
                renderModalCommitteeBreakdown(st);

                // Populate rubric inputs for selected assessor
                populateRubricsForAssessor(st, currentLoggedInMobile);

                calculateLiveTotal();

                const modal = document.getElementById('evaluationModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            } catch (err) {
                console.error("Error opening evaluation modal:", err);
            }
        }

        function onAssessorChange(selectedAssessorMobile) {
            const regNo = document.getElementById('evalRegNo').value;
            const st = studentDataset.find(s => s.reg_no === regNo);
            if (!st) return;
            populateRubricsForAssessor(st, selectedAssessorMobile);
            calculateLiveTotal();
        }

        function populateRubricsForAssessor(st, assessorMobile) {
            const evalObj = (st.assessors_list || []).find(e => e.assessor_mobile === assessorMobile);
            const attVal = st.attendance_mark !== undefined ? st.attendance_mark : st.suggested_att_mark;
            for (let c in criteriaConfig) {
                let val = (c === 'attendance') ? attVal : (evalObj ? evalObj[c] : 0);
                const inp = document.getElementById(`input_${c}`);
                const rng = document.getElementById(`range_${c}`);
                if (inp) inp.value = val;
                if (rng) rng.value = val;
            }
        }

        function renderModalCommitteeBreakdown(st) {
            const box = document.getElementById('evalCommitteeBreakdownBox');
            const list = document.getElementById('evalBreakdownList');
            const avgText = document.getElementById('evalBreakdownAvgText');
            if (!box || !list || !avgText) return;

            if (!st.assessors_list || st.assessors_list.length === 0) {
                box.classList.add('hidden');
                return;
            }

            box.classList.remove('hidden');
            avgText.textContent = `Committee Average: ${Math.round(st.final_score)} / 75 (Grade ${st.letter_grade})`;

            let html = '';
            st.assessors_list.forEach((ev, idx) => {
                html += `
                    <div class="p-2 rounded bg-slate-950 border border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-white">${ev.assessor_name}</span>
                            <span class="text-slate-500 text-[10px] ml-1">(${ev.designation})</span>
                            <div class="text-[10px] text-slate-400">
                                Rel: ${ev.relevance} | Lit: ${ev.literature} | Pres: ${ev.presentation} | Disc: ${ev.interaction} | Rep: ${ev.report} | Att: ${ev.attendance}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-blue-400 font-mono">${Math.round(ev.total_score)} M</span>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        function closeEvaluationModal() {
            const modal = document.getElementById('evaluationModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function syncEvalSlider(c) {
            const inp = document.getElementById(`input_${c}`);
            const rng = document.getElementById(`range_${c}`);
            let val = parseFloat(inp.value) || 0;
            if (val > criteriaConfig[c].max) val = criteriaConfig[c].max;
            if (val < 0) val = 0;
            inp.value = val;
            rng.value = val;
            calculateLiveTotal();
        }

        function syncEvalInput(c) {
            const inp = document.getElementById(`input_${c}`);
            const rng = document.getElementById(`range_${c}`);
            inp.value = rng.value;
            calculateLiveTotal();
        }

        function applySuggestedAttendance() {
            const regNo = document.getElementById('evalRegNo').value;
            const st = studentDataset.find(s => s.reg_no === regNo);
            if (!st) return;
            document.getElementById('input_attendance').value = st.suggested_att_mark;
            document.getElementById('range_attendance').value = st.suggested_att_mark;
            calculateLiveTotal();
        }

        function calculateLiveTotal() {
            let total = 0;
            for (let c in criteriaConfig) {
                total += parseFloat(document.getElementById(`input_${c}`).value) || 0;
            }
            if (total > 75.0) total = 75.0;
            document.getElementById('evalLiveTotal').innerHTML = `${total.toFixed(1)} <span class="text-xs text-slate-400 font-normal">/ 75.0</span>`;

            // Calculate Grade
            const pct = (total / 75.0) * 100.0;
            let grade = 'F';
            let color = 'text-rose-400';
            if (pct >= 90) { grade = 'S (Outstanding)'; color = 'text-amber-400'; }
            else if (pct >= 80) { grade = 'A (Excellent)'; color = 'text-blue-400'; }
            else if (pct >= 70) { grade = 'B (Very Good)'; color = 'text-sky-400'; }
            else if (pct >= 60) { grade = 'C (Good)'; color = 'text-teal-400'; }
            else if (pct >= 50) { grade = 'D (Satisfactory)'; color = 'text-emerald-400'; }
            else if (pct >= 40) { grade = 'E (Pass)'; color = 'text-slate-300'; }
            else { grade = 'F (Failed)'; color = 'text-rose-400'; }

            const gradeEl = document.getElementById('evalLiveGrade');
            gradeEl.textContent = grade;
            gradeEl.className = `text-sm sm:text-base font-extrabold mt-0.5 ${color}`;

            const headerScore = document.getElementById('evalHeaderScoreVal');
            if (headerScore) headerScore.textContent = total.toFixed(1);
        }

        async function submitEvaluationForm(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveEval');
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Saving...`;

            const payload = {
                reg_no: document.getElementById('evalRegNo').value,
                assessor_mobile_no: document.getElementById('evalAssessorMobile').value,
                topic: document.getElementById('evalTopicInput').value,
                guide_mobile_no: document.getElementById('evalGuideSelect').value,
                presentation_date: document.getElementById('evalPresentationDateInput').value,
                relevance: parseFloat(document.getElementById('input_relevance').value) || 0,
                literature: parseFloat(document.getElementById('input_literature').value) || 0,
                presentation: parseFloat(document.getElementById('input_presentation').value) || 0,
                interaction: parseFloat(document.getElementById('input_interaction').value) || 0,
                report: parseFloat(document.getElementById('input_report').value) || 0,
                attendance: parseFloat(document.getElementById('input_attendance').value) || 0,
            };

            try {
                const res = await fetch(`/r21/classroom/seminar/${subjectId}/evaluate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    // Update dataset cache
                    const st = studentDataset.find(s => s.reg_no === payload.reg_no);
                    if (st) {
                        st.my_evaluation = { ...payload, total_score: data.data.my_total };
                        st.final_score = data.data.average_score;
                        st.letter_grade = data.data.letter_grade;
                        st.eval_count = data.data.eval_count;
                        st.is_completed = true;
                        st.assessors_list = data.data.assessors_list || st.assessors_list;
                        if (data.data.topic) st.topic = data.data.topic;
                        if (data.data.guide_name) st.guide_name = data.data.guide_name;
                        if (data.data.guide_mobile_no) st.guide_mobile_no = data.data.guide_mobile_no;
                        if (data.data.presentation_date) st.presentation_date = data.data.presentation_date;
                        if (data.data.presentation_date_formatted) st.presentation_date_formatted = data.data.presentation_date_formatted;
                    }

                    // Update UI Row in Evaluation Table
                    const row = document.getElementById(`row-eval-${payload.reg_no}`);
                    if (row) {
                        const relInput = row.querySelector('.mark-rel');
                        if (relInput) relInput.value = payload.relevance;
                        const litInput = row.querySelector('.mark-lit');
                        if (litInput) litInput.value = payload.literature;
                        const presInput = row.querySelector('.mark-pres');
                        if (presInput) presInput.value = payload.presentation;
                        const intInput = row.querySelector('.mark-interctn');
                        if (intInput) intInput.value = payload.interaction;
                        const repInput = row.querySelector('.mark-report');
                        if (repInput) repInput.value = payload.report;

                        const ciaEl = row.querySelector('.col-row-cia');
                        if (ciaEl) {
                            ciaEl.textContent = Number(data.data.average_score).toFixed(1);
                            ciaEl.className = `col-row-cia font-mono font-black text-lg ${data.data.average_score >= 30.0 ? 'text-emerald-400' : 'text-rose-400'}`;
                        }

                        const gradeEl = row.querySelector('.col-row-grade');
                        if (gradeEl) {
                            const colorClass = data.data.letter_grade === 'S' ? 'text-amber-400' : (data.data.letter_grade === 'F' ? 'text-rose-400' : 'text-slate-200');
                            gradeEl.innerHTML = `<span class="grade-badge-cell font-black text-xs ${colorClass}">Grade ${data.data.letter_grade}</span>`;
                        }

                        if (data.data.topic && row.querySelector('.col-row-topic')) row.querySelector('.col-row-topic').textContent = data.data.topic;
                        if (data.data.guide_name && row.querySelector('.col-row-guide')) row.querySelector('.col-row-guide').textContent = data.data.guide_name;
                        if (data.data.presentation_date_formatted && row.querySelector('.col-row-date')) row.querySelector('.col-row-date').textContent = data.data.presentation_date_formatted;
                    }

                    // Also update Schedule Table row if exists
                    const schedRow = document.getElementById(`row-sched-${payload.reg_no}`);
                    if (schedRow) {
                        if (data.data.topic) schedRow.querySelector('.col-sched-topic').textContent = data.data.topic;
                        if (data.data.guide_name) schedRow.querySelector('.col-sched-guide').innerHTML = `<div class="font-bold text-slate-200 flex items-center gap-1"><span class="material-symbols-rounded text-xs text-blue-400">supervisor_account</span> ${data.data.guide_name}</div>`;
                        if (data.data.presentation_date_formatted) {
                            schedRow.querySelector('.col-sched-date').innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-900 border border-slate-700 font-mono text-[11px] font-bold text-slate-200"><span class="material-symbols-rounded text-xs text-blue-400">calendar_today</span> ${data.data.presentation_date_formatted}</span>`;
                        }
                    }

                    // Also update Grades & Results Table row if exists
                    const gradeRow = document.getElementById(`row-grade-${payload.reg_no}`);
                    if (gradeRow) {
                        if (data.data.avg_relevance !== undefined) gradeRow.querySelector('.col-grade-relevance').textContent = Number(data.data.avg_relevance).toFixed(1);
                        if (data.data.avg_literature !== undefined) gradeRow.querySelector('.col-grade-literature').textContent = Number(data.data.avg_literature).toFixed(1);
                        if (data.data.avg_presentation !== undefined) gradeRow.querySelector('.col-grade-presentation').textContent = Number(data.data.avg_presentation).toFixed(1);
                        if (data.data.avg_interaction !== undefined) gradeRow.querySelector('.col-grade-interaction').textContent = Number(data.data.avg_interaction).toFixed(1);
                        if (data.data.avg_report !== undefined) gradeRow.querySelector('.col-grade-report').textContent = Number(data.data.avg_report).toFixed(1);
                        if (data.data.attendance_mark !== undefined) gradeRow.querySelector('.col-grade-attendance').textContent = Number(data.data.attendance_mark).toFixed(1);

                        const finalScoreEl = gradeRow.querySelector('.col-grade-final');
                        if (finalScoreEl) {
                            finalScoreEl.textContent = Number(data.data.average_score).toFixed(1);
                            finalScoreEl.className = `text-center font-mono font-bold text-sm col-grade-final ${data.data.average_score >= 30.0 ? 'text-emerald-400' : 'text-rose-400'}`;
                        }

                        const letterEl = gradeRow.querySelector('.col-grade-letter');
                        if (letterEl) {
                            const colorClass = data.data.letter_grade === 'S' ? 'text-amber-400' : (data.data.letter_grade === 'F' ? 'text-rose-400' : 'text-slate-200');
                            letterEl.innerHTML = `<span class="font-bold text-xs ${colorClass}">${data.data.letter_grade}</span>`;
                        }

                        const pointEl = gradeRow.querySelector('.col-grade-point');
                        if (pointEl) pointEl.textContent = data.data.grade_point;

                        const resultEl = gradeRow.querySelector('.col-grade-result');
                        if (resultEl) {
                            if (data.data.result === 'Pass') {
                                resultEl.innerHTML = `<span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold text-[10px]">PASS</span>`;
                            } else if (data.data.result === 'Failed') {
                                resultEl.innerHTML = `<span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold text-[10px]">FAILED</span>`;
                            } else {
                                resultEl.innerHTML = `<span class="text-slate-500 text-[10px]">Pending</span>`;
                            }
                        }
                    }

                    // Update stat counter
                    if (data.data.completed_count) {
                        document.getElementById('statCompletedCount').textContent = data.data.completed_count;
                        const total = parseInt(document.getElementById('statTotalCount').textContent) || 0;
                        document.getElementById('statPendingCount').textContent = Math.max(0, total - data.data.completed_count);
                    }

                    closeEvaluationModal();
                } else {
                    alert(data.message || 'Failed to save evaluation.');
                }
            } catch (err) {
                console.error(err);
                alert('Server error saving seminar evaluation.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<span class="material-symbols-rounded text-sm">save</span><span>Save Evaluation</span>`;
            }
        }

        // ---------------- FACULTY BREAKDOWN POPUP ----------------
        function showFacultyBreakdown(regNo) {
            const st = studentDataset.find(s => s.reg_no === regNo);
            if (!st) return;

            document.getElementById('breakdownModalStudentName').textContent = st.name;
            document.getElementById('breakdownModalStudentMeta').textContent = `Reg: ${st.sbte_reg_no || st.reg_no} | Roll: ${st.roll_no || '-'}`;
            document.getElementById('breakdownFinalAvg').innerHTML = `${Math.round(st.final_score)} <span class="text-xs text-slate-400 font-normal">/ 75</span>`;
            document.getElementById('breakdownFinalGrade').textContent = `Grade ${st.letter_grade} (${st.result})`;

            const container = document.getElementById('breakdownCardsContainer');
            if (!st.assessors_list || st.assessors_list.length === 0) {
                container.innerHTML = `<div class="p-3 text-center text-slate-400 bg-slate-900 rounded-lg">No assessor marks recorded yet.</div>`;
            } else {
                let html = '';
                st.assessors_list.forEach((ev, idx) => {
                    html += `
                        <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-white text-xs">${ev.assessor_name}</span>
                                    <span class="text-slate-400 text-[10px] ml-1">(${ev.designation})</span>
                                </div>
                                <span class="font-bold text-sm text-blue-400 font-mono">${Math.round(ev.total_score)} / 75</span>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-1 text-[10px] text-center pt-1 border-t border-slate-800">
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Relevance</span><span class="font-bold text-white">${ev.relevance}</span></div>
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Literature</span><span class="font-bold text-white">${ev.literature}</span></div>
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Presentation</span><span class="font-bold text-white">${ev.presentation}</span></div>
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Discussion</span><span class="font-bold text-white">${ev.interaction}</span></div>
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Report</span><span class="font-bold text-white">${ev.report}</span></div>
                                <div class="bg-slate-950 p-1 rounded"><span class="text-slate-400 block">Attendance</span><span class="font-bold text-white">${ev.attendance}</span></div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            document.getElementById('facultyBreakdownModal').classList.remove('hidden');
        }

        function closeFacultyBreakdownModal() {
            document.getElementById('facultyBreakdownModal').classList.add('hidden');
        }

        // ---------------- SCHEDULE & LOG MODAL ----------------
        function openScheduleModal(regNo) {
            const st = studentDataset.find(s => s.reg_no === regNo);
            if (!st) return;

            document.getElementById('schedRegNo').value = st.reg_no;
            document.getElementById('schedModalStudentName').textContent = st.name;
            document.getElementById('schedModalStudentMeta').textContent = `Reg: ${st.sbte_reg_no || st.reg_no} | Roll: ${st.roll_no || '-'}`;
            document.getElementById('schedPresentationDate').value = st.presentation_date || '';
            document.getElementById('schedTopic').value = st.topic || '';
            document.getElementById('schedGuideMobile').value = st.guide_mobile_no || '';

            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        async function submitScheduleForm(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveSchedule');
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Saving...`;

            const payload = {
                reg_no: document.getElementById('schedRegNo').value,
                presentation_date: document.getElementById('schedPresentationDate').value,
                topic: document.getElementById('schedTopic').value,
                guide_mobile_no: document.getElementById('schedGuideMobile').value
            };

            try {
                const res = await fetch(`/r21/classroom/seminar/${subjectId}/schedule`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    // Update dataset cache
                    const st = studentDataset.find(s => s.reg_no === payload.reg_no);
                    if (st) {
                        st.topic = data.data.topic;
                        st.presentation_date = data.data.presentation_date;
                        st.presentation_date_formatted = data.data.presentation_date_formatted;
                        st.guide_name = data.data.guide_name;
                        st.guide_mobile_no = data.data.guide_mobile_no;
                    }

                    // Update Row in Evaluation Table
                    const evalRow = document.getElementById(`row-eval-${payload.reg_no}`);
                    if (evalRow) {
                        evalRow.querySelector('.col-row-topic').textContent = data.data.topic;
                        evalRow.querySelector('.col-row-guide').textContent = data.data.guide_name;
                        if (data.data.presentation_date_formatted && evalRow.querySelector('.col-row-date')) {
                            evalRow.querySelector('.col-row-date').textContent = data.data.presentation_date_formatted;
                        }
                    }

                    // Update Row in Schedule Table
                    const row = document.getElementById(`row-sched-${payload.reg_no}`);
                    if (row) {
                        row.querySelector('.col-sched-topic').innerHTML = `<div class="font-semibold text-slate-200 text-[11px]">${data.data.topic}</div>`;
                        row.querySelector('.col-sched-guide').innerHTML = `<div class="font-bold text-slate-200 flex items-center gap-1"><span class="material-symbols-rounded text-xs text-blue-400">supervisor_account</span> ${data.data.guide_name}</div>`;
                        if (data.data.presentation_date_formatted) {
                            row.querySelector('.col-sched-date').innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-900 border border-slate-700 font-mono text-[11px] font-bold text-slate-200"><span class="material-symbols-rounded text-xs text-blue-400">calendar_today</span> ${data.data.presentation_date_formatted}</span>`;
                        }
                    }

                    closeScheduleModal();
                } else {
                    alert(data.message || 'Failed to update schedule.');
                }
            } catch (err) {
                console.error(err);
                alert('Server error updating schedule.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<span class="material-symbols-rounded text-sm">save</span><span>Save Topic &amp; Schedule</span>`;
            }
        }

        // ---------------- SYLLABUS MODAL ----------------
        function openSyllabusModal() {
            document.getElementById('syllabusUploadMsg').classList.add('hidden');
            document.getElementById('syllabusModal').classList.remove('hidden');
        }

        function closeSyllabusModal() {
            document.getElementById('syllabusModal').classList.add('hidden');
        }

        async function submitSyllabusForm(e) {
            e.preventDefault();
            const fileInput = document.getElementById('syllabusFileInput');
            if (!fileInput.files || fileInput.files.length === 0) return;

            const btn = document.getElementById('btnUploadSyllabus');
            const msg = document.getElementById('syllabusUploadMsg');
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Uploading...`;

            const formData = new FormData();
            formData.append('syllabus_file', fileInput.files[0]);

            try {
                const res = await fetch(`/r21/classroom/seminar/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    msg.className = 'p-3 rounded-xl bg-slate-900 border border-emerald-500/40 text-emerald-300 block';
                    msg.textContent = 'Syllabus PDF uploaded successfully!';
                    
                    // Show view button in header
                    const viewBtn = document.getElementById('headerViewSyllabusBtn');
                    if (viewBtn) {
                        viewBtn.href = data.path;
                        viewBtn.classList.remove('hidden');
                        viewBtn.classList.add('flex');
                    }

                    setTimeout(() => closeSyllabusModal(), 1200);
                } else {
                    msg.className = 'p-3 rounded-xl bg-slate-900 border border-rose-500/40 text-rose-300 block';
                    msg.textContent = data.message || 'Upload failed.';
                }
            } catch (err) {
                console.error(err);
                msg.className = 'p-3 rounded-xl bg-slate-900 border border-rose-500/40 text-rose-300 block';
                msg.textContent = 'Server error uploading syllabus.';
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<span class="material-symbols-rounded text-sm">upload</span><span>Upload File</span>`;
            }
        }

        // ---------------- SEMINAR DIRECT TABLE EDITING & AUTO-SAVE ----------------
        const autoSaveTimers = {};

        function onSeminarMarkInput(input) {
            validateSeminarMark(input);
            calculateRowCia(input);
            triggerSeminarAutoSave(input, false);
        }

        function onSeminarMarkChange(input) {
            validateSeminarMark(input);
            calculateRowCia(input);
            triggerSeminarAutoSave(input, true);
        }

        function onSeminarMarkBlur(input) {
            if (input.value !== '') {
                const num = parseFloat(input.value);
                if (!isNaN(num)) {
                    input.value = num;
                }
            }
            triggerSeminarAutoSave(input, true);
        }

        function validateSeminarMark(input) {
            const rubric = input.getAttribute('data-rubric');
            const maxVal = (rubric === 'presentation') ? 37.5 : 7.5;
            let val = parseFloat(input.value);
            if (!isNaN(val)) {
                if (val > maxVal) input.value = maxVal;
                if (val < 0) input.value = 0;
            }
        }

        function calculateRowCia(input) {
            const regNo = input.getAttribute('data-reg');
            const row = document.getElementById(`row-eval-${regNo}`);
            if (!row) return;

            const rel = parseFloat(row.querySelector('.mark-rel')?.value) || 0;
            const lit = parseFloat(row.querySelector('.mark-lit')?.value) || 0;
            const pres = parseFloat(row.querySelector('.mark-pres')?.value) || 0;
            const interctn = parseFloat(row.querySelector('.mark-interctn')?.value) || 0;
            const rep = parseFloat(row.querySelector('.mark-report')?.value) || 0;
            
            // Attendance mark from authoritative TEAMS attendance cell
            const attnCell = row.querySelector('[data-attn]');
            const attn = parseFloat(attnCell?.getAttribute('data-attn') || attnCell?.textContent) || 0;

            const total = Math.min(75.0, Math.round((rel + lit + pres + interctn + rep + attn) * 10) / 10);

            // Update row CIA
            const ciaEl = row.querySelector('.col-row-cia');
            if (ciaEl) {
                ciaEl.textContent = total.toFixed(1);
                ciaEl.className = `col-row-cia font-mono font-black text-lg ${total >= 30.0 ? 'text-emerald-400' : 'text-rose-400'}`;
            }

            // Calculate letter grade
            const pct = (total / 75.0) * 100.0;
            let grade = 'F';
            let colorClass = 'text-rose-400';
            if (pct >= 90) { grade = 'S'; colorClass = 'text-amber-400'; }
            else if (pct >= 80) { grade = 'A'; colorClass = 'text-blue-400'; }
            else if (pct >= 70) { grade = 'B'; colorClass = 'text-sky-400'; }
            else if (pct >= 60) { grade = 'C'; colorClass = 'text-teal-400'; }
            else if (pct >= 50) { grade = 'D'; colorClass = 'text-emerald-400'; }
            else if (pct >= 40) { grade = 'E'; colorClass = 'text-slate-200'; }
            else { grade = 'F'; colorClass = 'text-rose-400'; }

            const gradeCell = row.querySelector('.col-row-grade');
            if (gradeCell) {
                gradeCell.innerHTML = `<span class="grade-badge-cell font-black text-xs ${colorClass}">Grade ${grade}</span>`;
            }
        }

        function setAutoSaveStatus(status, text) {
            const statusEl = document.getElementById('seminarSaveStatus');
            if (!statusEl) return;
            if (status === 'saving') {
                statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-amber-400"><span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span><span>Saving changes...</span></span>`;
            } else if (status === 'saved') {
                statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-emerald-400"><span class="material-symbols-rounded text-sm">check_circle</span><span>${text || 'All changes saved'}</span></span>`;
            } else if (status === 'error') {
                statusEl.innerHTML = `<span class="inline-flex items-center gap-1.5 text-rose-400"><span class="material-symbols-rounded text-sm">error</span><span>${text || 'Failed to auto-save'}</span></span>`;
            } else {
                statusEl.innerHTML = `<span class="text-slate-400">Auto-save ready</span>`;
            }
        }

        function triggerSeminarAutoSave(input, immediate = false) {
            const regNo = input.getAttribute('data-reg');
            if (!regNo) return;

            setAutoSaveStatus('saving');

            if (autoSaveTimers[regNo]) {
                clearTimeout(autoSaveTimers[regNo]);
            }

            if (immediate) {
                saveStudentSeminarMarks(regNo);
            } else {
                autoSaveTimers[regNo] = setTimeout(() => {
                    saveStudentSeminarMarks(regNo);
                }, 750);
            }
        }

        async function saveStudentSeminarMarks(regNo) {
            const row = document.getElementById(`row-eval-${regNo}`);
            if (!row) return;

            const rel = parseFloat(row.querySelector('.mark-rel')?.value) || 0;
            const lit = parseFloat(row.querySelector('.mark-lit')?.value) || 0;
            const pres = parseFloat(row.querySelector('.mark-pres')?.value) || 0;
            const interctn = parseFloat(row.querySelector('.mark-interctn')?.value) || 0;
            const rep = parseFloat(row.querySelector('.mark-report')?.value) || 0;
            
            const attnCell = row.querySelector('[data-attn]');
            const attn = parseFloat(attnCell?.getAttribute('data-attn') || attnCell?.textContent) || 0;

            const st = studentDataset.find(s => s.reg_no === regNo);

            const payload = {
                reg_no: regNo,
                assessor_mobile_no: currentLoggedInMobile,
                topic: st?.topic || null,
                guide_mobile_no: st?.guide_mobile_no || null,
                presentation_date: st?.presentation_date || null,
                relevance: rel,
                literature: lit,
                presentation: pres,
                interaction: interctn,
                report: rep,
                attendance: attn
            };

            try {
                const res = await fetch(`/r21/classroom/seminar/${subjectId}/evaluate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    setAutoSaveStatus('saved', 'All changes saved');

                    // Update cached dataset
                    if (st) {
                        st.my_evaluation = { ...payload, total_score: data.data.my_total };
                        st.final_score = data.data.average_score;
                        st.letter_grade = data.data.letter_grade;
                        st.eval_count = data.data.eval_count;
                        st.is_completed = true;
                        st.assessors_list = data.data.assessors_list || st.assessors_list;
                    }

                    // Update Grades table row if it exists
                    syncGradeRow(payload.reg_no, data.data);

                    // Update stats
                    if (data.data.completed_count) {
                        document.getElementById('statCompletedCount').textContent = data.data.completed_count;
                        const total = parseInt(document.getElementById('statTotalCount').textContent) || 0;
                        document.getElementById('statPendingCount').textContent = Math.max(0, total - data.data.completed_count);
                    }
                } else {
                    setAutoSaveStatus('error', data.message || 'Auto-save failed');
                }
            } catch (err) {
                console.error('Auto-save error:', err);
                setAutoSaveStatus('error', 'Network error saving marks');
            }
        }

        async function saveAllSeminarMarks() {
            const rows = document.querySelectorAll('.student-row');
            if (!rows.length) return;

            const btnTop = document.getElementById('btnSaveAllSeminar');
            const btnBtm = document.getElementById('btnSaveAllSeminarBottom');
            
            const setButtonsLoading = (isLoading) => {
                [btnTop, btnBtm].forEach(btn => {
                    if (!btn) return;
                    btn.disabled = isLoading;
                    if (isLoading) {
                        btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span><span>Saving All...</span>`;
                    } else {
                        btn.innerHTML = `<span class="material-symbols-rounded text-sm">save</span><span>Save Marks</span>`;
                    }
                });
            };

            setButtonsLoading(true);
            setAutoSaveStatus('saving');

            const evaluations = [];
            rows.forEach(row => {
                const regNo = row.getAttribute('data-reg');
                if (!regNo) return;

                const rel = parseFloat(row.querySelector('.mark-rel')?.value) || 0;
                const lit = parseFloat(row.querySelector('.mark-lit')?.value) || 0;
                const pres = parseFloat(row.querySelector('.mark-pres')?.value) || 0;
                const interctn = parseFloat(row.querySelector('.mark-interctn')?.value) || 0;
                const rep = parseFloat(row.querySelector('.mark-report')?.value) || 0;
                
                const attnCell = row.querySelector('[data-attn]');
                const attn = parseFloat(attnCell?.getAttribute('data-attn') || attnCell?.textContent) || 0;

                const st = studentDataset.find(s => s.reg_no === regNo);

                evaluations.push({
                    reg_no: regNo,
                    topic: st?.topic || null,
                    guide_mobile_no: st?.guide_mobile_no || null,
                    presentation_date: st?.presentation_date || null,
                    relevance: rel,
                    literature: lit,
                    presentation: pres,
                    interaction: interctn,
                    report: rep,
                    attendance: attn
                });
            });

            try {
                const res = await fetch(`/r21/classroom/seminar/${subjectId}/evaluate-batch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        assessor_mobile_no: currentLoggedInMobile,
                        evaluations: evaluations
                    })
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    setAutoSaveStatus('saved', `All ${data.saved_count || evaluations.length} students saved successfully!`);
                    alert(`✓ Successfully saved marks for ${data.saved_count || evaluations.length} students.`);
                    location.reload();
                } else {
                    setAutoSaveStatus('error', data.message || 'Failed to save batch marks.');
                    alert(data.message || 'Failed to save batch marks.');
                }
            } catch (err) {
                console.error('Batch save error:', err);
                setAutoSaveStatus('error', 'Server error saving marks.');
                alert('Server error saving batch marks.');
            } finally {
                setButtonsLoading(false);
            }
        }

        function syncGradeRow(regNo, data) {
            const gradeRow = document.getElementById(`row-grade-${regNo}`);
            if (!gradeRow) return;

            if (data.avg_relevance !== undefined) gradeRow.querySelector('.col-grade-relevance').textContent = Number(data.avg_relevance).toFixed(1);
            if (data.avg_literature !== undefined) gradeRow.querySelector('.col-grade-literature').textContent = Number(data.avg_literature).toFixed(1);
            if (data.avg_presentation !== undefined) gradeRow.querySelector('.col-grade-presentation').textContent = Number(data.avg_presentation).toFixed(1);
            if (data.avg_interaction !== undefined) gradeRow.querySelector('.col-grade-interaction').textContent = Number(data.avg_interaction).toFixed(1);
            if (data.avg_report !== undefined) gradeRow.querySelector('.col-grade-report').textContent = Number(data.avg_report).toFixed(1);
            if (data.attendance_mark !== undefined) gradeRow.querySelector('.col-grade-attendance').textContent = Number(data.attendance_mark).toFixed(1);

            const finalScoreEl = gradeRow.querySelector('.col-grade-final');
            if (finalScoreEl) {
                finalScoreEl.textContent = Number(data.average_score).toFixed(1);
                finalScoreEl.className = `text-center font-mono font-bold text-sm col-grade-final ${data.average_score >= 30.0 ? 'text-emerald-400' : 'text-rose-400'}`;
            }

            const letterEl = gradeRow.querySelector('.col-grade-letter');
            if (letterEl) {
                const colorClass = data.letter_grade === 'S' ? 'text-amber-400' : (data.letter_grade === 'F' ? 'text-rose-400' : 'text-slate-200');
                letterEl.innerHTML = `<span class="font-bold text-xs ${colorClass}">${data.letter_grade}</span>`;
            }

            const pointEl = gradeRow.querySelector('.col-grade-point');
            if (pointEl) pointEl.textContent = data.grade_point;

            const resultEl = gradeRow.querySelector('.col-grade-result');
            if (resultEl) {
                if (data.result === 'Pass') {
                    resultEl.innerHTML = `<span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold text-[10px]">PASS</span>`;
                } else if (data.result === 'Failed') {
                    resultEl.innerHTML = `<span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold text-[10px]">FAILED</span>`;
                } else {
                    resultEl.innerHTML = `<span class="text-slate-500 text-[10px]">Pending</span>`;
                }
            }
        }

        // ---------------- RAPID KEYBOARD NAVIGATION ----------------
        function handleSeminarMarkKeyDown(event, input) {
            const rubric = input.getAttribute('data-rubric');
            const currentRow = input.closest('tr');
            if (!currentRow) return;

            if (event.key === 'ArrowDown' || event.key === 'Enter') {
                event.preventDefault();
                let nextRow = currentRow.nextElementSibling;
                while (nextRow && (nextRow.classList.contains('hidden') || !nextRow.classList.contains('student-row'))) {
                    nextRow = nextRow.nextElementSibling;
                }
                if (nextRow) {
                    const target = nextRow.querySelector(`[data-rubric="${rubric}"]`);
                    if (target) {
                        target.focus();
                        target.select();
                    }
                }
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                let prevRow = currentRow.previousElementSibling;
                while (prevRow && (prevRow.classList.contains('hidden') || !prevRow.classList.contains('student-row'))) {
                    prevRow = prevRow.previousElementSibling;
                }
                if (prevRow) {
                    const target = prevRow.querySelector(`[data-rubric="${rubric}"]`);
                    if (target) {
                        target.focus();
                        target.select();
                    }
                }
            }
        }

        // Dummy stubs for legacy calls if any
        function toggleRubricColumns() {}
        function applyRubricVisibility() {}

        document.addEventListener('DOMContentLoaded', function() {
            loadSeminarSurveyData();
        });

        // Global keydown handler for Escape key modal dismissal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                const evalModal = document.getElementById('evaluationModal');
                if (evalModal && !evalModal.classList.contains('hidden')) {
                    closeEvaluationModal();
                    return;
                }
                const schedModal = document.getElementById('scheduleModal');
                if (schedModal && !schedModal.classList.contains('hidden')) {
                    closeScheduleModal();
                    return;
                }
                const bkModal = document.getElementById('facultyBreakdownModal');
                if (bkModal && !bkModal.classList.contains('hidden')) {
                    closeFacultyBreakdownModal();
                    return;
                }
                const sylModal = document.getElementById('syllabusModal');
                if (sylModal && !sylModal.classList.contains('hidden')) {
                    closeSyllabusModal();
                    return;
                }
            }
        });
    </script>

    @include('partials.carmie_assistant')
</body>
</html>
