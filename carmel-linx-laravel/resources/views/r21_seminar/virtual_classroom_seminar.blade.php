<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[R-2021] Virtual Seminar Room - {{ $batchSubject->subject_name }}</title>

    <!-- Google Fonts & Tailwind CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            font-size: 0.74rem;
            line-height: 1.25;
        }

        .table-custom th {
            background-color: #0f172a;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.67rem;
            letter-spacing: 0.03em;
            padding: 0.5rem 0.6rem;
            border-bottom: 1px solid #1e293b;
            white-space: nowrap;
        }

        .table-custom td {
            background-color: #111a2e;
            border-bottom: 1px solid #1a2744;
            vertical-align: middle;
            padding: 0.45rem 0.6rem;
            font-weight: 500;
        }

        .table-custom tr:hover td {
            background-color: #17233d;
        }

        /* Compact, Ergonomic Flat Slider */
        input[type=range] {
            -webkit-appearance: none;
            appearance: none;
            height: 7px;
            border-radius: 4px;
            background: #1e293b;
            outline: none;
            cursor: pointer;
            touch-action: pan-y;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #2563eb;
            border: 2px solid #ffffff;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            transition: transform 0.1s ease, background-color 0.15s ease;
        }

        input[type=range]::-webkit-slider-thumb:hover,
        input[type=range]::-webkit-slider-thumb:active {
            background: #1d4ed8;
            transform: scale(1.15);
        }

        input[type=range]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #2563eb;
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
        <div class="px-4 py-3.5 sm:px-6 sm:py-4 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            
            <!-- Left: Back Navigation, Title & Classroom Badges -->
            <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
                <button type="button" onclick="returnToParent()" class="px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-bold text-xs sm:text-sm flex items-center gap-2 cursor-pointer transition shadow-sm shrink-0" title="Return to Caller / Dashboard">
                    <span class="material-symbols-rounded text-lg">arrow_back</span>
                    <span>Back</span>
                </button>

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

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap w-full lg:w-auto justify-start lg:justify-end">
                <!-- Class Attendance & Log Button -->
                <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" 
                   class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs sm:text-sm transition flex items-center gap-2 cursor-pointer no-underline shadow-sm" 
                   title="Open Class Attendance & Log (Returns here on exit)">
                    <span class="material-symbols-rounded text-base text-emerald-400">calendar_month</span>
                    <span>Attendance &amp; Log</span>
                </a>

                <!-- Syllabus Button -->
                <div class="flex items-center rounded-xl border border-slate-700 bg-slate-900 overflow-hidden shadow-sm">
                    <button type="button" onclick="openSyllabusModal()" class="px-3.5 py-2 text-slate-200 hover:bg-slate-800 font-semibold text-xs sm:text-sm transition flex items-center gap-1.5 cursor-pointer" title="Upload Syllabus PDF">
                        <span class="material-symbols-rounded text-base text-blue-400">cloud_upload</span>
                        <span>Syllabus</span>
                    </button>
                    @if(!empty($courseFile->syllabus_pdf_path))
                        <a href="{{ $courseFile->syllabus_pdf_path }}" target="_blank" id="headerViewSyllabusBtn" class="px-2.5 py-2 bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold text-xs transition flex items-center gap-1 cursor-pointer no-underline border-l border-slate-700" title="View Uploaded Syllabus PDF">
                            <span class="material-symbols-rounded text-sm">visibility</span>
                        </a>
                    @else
                        <a href="#" target="_blank" id="headerViewSyllabusBtn" class="hidden px-2.5 py-2 bg-slate-800 hover:bg-slate-700 text-blue-400 font-bold text-xs transition items-center gap-1 cursor-pointer no-underline border-l border-slate-700" title="View Uploaded Syllabus PDF">
                            <span class="material-symbols-rounded text-sm">visibility</span>
                        </a>
                    @endif
                </div>

                <!-- Print Consolidated Report -->
                <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-200 hover:text-white font-semibold text-xs sm:text-sm transition flex items-center gap-2 cursor-pointer no-underline shadow-sm" title="Print Consolidated Seminar Report">
                    <span class="material-symbols-rounded text-base text-slate-400">print</span>
                    <span>Print 75M Sheet</span>
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

    <!-- Sub-toolbar: Tabs, Batch Filter & Search -->
    <div class="bg-[#0f172a] border-b border-slate-800 px-4 py-2.5 sm:px-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        
        <!-- Tabs -->
        <div class="flex items-center gap-1.5 bg-slate-950 p-1.5 rounded-xl border border-slate-800 overflow-x-auto shrink-0">
            <button onclick="switchTab('evaluation')" id="tabBtn-evaluation" class="tab-btn px-3.5 py-2 rounded-lg font-bold text-xs transition flex items-center gap-1.5 cursor-pointer bg-blue-600 text-white shadow-sm">
                <span class="material-symbols-rounded text-base">assignment</span>
                <span>Evaluation Register (75M)</span>
            </button>
            <button onclick="switchTab('schedule')" id="tabBtn-schedule" class="tab-btn px-3.5 py-2 rounded-lg font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer text-slate-400 hover:text-white">
                <span class="material-symbols-rounded text-base">event_note</span>
                <span>Schedule &amp; Log</span>
            </button>
            <button onclick="switchTab('grades')" id="tabBtn-grades" class="tab-btn px-3.5 py-2 rounded-lg font-semibold text-xs transition flex items-center gap-1.5 cursor-pointer text-slate-400 hover:text-white">
                <span class="material-symbols-rounded text-base">grade</span>
                <span>Consolidated CIA &amp; Grades</span>
            </button>
        </div>

        <!-- Right Side: Batch Filters & Search -->
        <div class="flex items-center gap-2.5 flex-wrap justify-between md:justify-end">
            <!-- Inline Batch Filters -->
            <div class="flex items-center gap-1.5 text-xs flex-wrap">
                <span class="text-[10px] uppercase font-bold text-slate-400 me-1 hidden sm:inline">Batch:</span>
                <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-blue-500 text-blue-400 font-bold transition">
                    All (<span id="bFilterAllCount">{{ $totalStudents }}</span>)
                </button>
                <button onclick="filterLabBatch('1')" id="batch-filter-1" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition">
                    Batch 1 (<span id="bFilter1Count">{{ $batch1Count }}</span>)
                </button>
                <button onclick="filterLabBatch('2')" id="batch-filter-2" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition">
                    Batch 2 (<span id="bFilter2Count">{{ $batch2Count }}</span>)
                </button>
                <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 font-medium transition">
                    Unassigned (<span id="bFilterUnCount">{{ $unassignedCount }}</span>)
                </button>
                <button type="button" onclick="openLabBatchSetupModal('{{ $batchSubject->id }}')" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-700 text-slate-300 font-bold transition flex items-center gap-1 cursor-pointer shadow-sm" title="Configure Student Lab Batch Division">
                    <span class="material-symbols-rounded text-sm text-blue-400">tune</span>
                    <span>Batch split setup</span>
                </button>
            </div>

            <!-- Quick Student Search Input -->
            <div class="relative min-w-[170px] sm:min-w-[220px]">
                <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-base">search</span>
                <input type="text" id="studentSearchInput" placeholder="Search student..." oninput="onStudentSearch(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-lg pl-9 pr-3 py-1.5 text-xs text-white focus:outline-none focus:border-blue-500 transition">
            </div>
        </div>

    </div>

    <!-- Main Workspace -->
    <main class="flex-grow p-3 sm:p-5 relative z-10">

        <!-- ========================================== -->
        <!-- TAB 1: Seminar Evaluation Register (75 Marks) -->
        <!-- ========================================== -->
        <div id="tabContent-evaluation" class="tab-pane block">
            
            <!-- Rubric Guide Banner (Clause 11.2.6) -->
            <div class="mb-3 p-3 rounded-xl bg-[#111a2e] border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-lg">gavel</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white flex items-center gap-2">
                            <span>Clause 11.2.6 Assessment Rubrics (100% = 75 Marks Total)</span>
                            <span class="px-2 py-0.2 rounded bg-slate-900 text-slate-300 text-[10px] font-mono border border-slate-700">Assessed by Committee of 2 Faculty, Averaged</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5 flex flex-wrap gap-x-3 gap-y-1">
                            <span>1. Relevance: <strong class="text-slate-200">7.5M</strong> (10%)</span>
                            <span>2. Literature: <strong class="text-slate-200">7.5M</strong> (10%)</span>
                            <span>3. Presentation: <strong class="text-slate-200">37.5M</strong> (50%)</span>
                            <span>4. Interaction: <strong class="text-slate-200">7.5M</strong> (10%)</span>
                            <span>5. Report: <strong class="text-slate-200">7.5M</strong> (10%)</span>
                            <span>6. Attendance: <strong class="text-slate-200">7.5M</strong> (10%)</span>
                        </div>
                    </div>
                </div>
                <div class="text-[11px] text-slate-300 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 shrink-0">
                    Current Assessor: <strong class="text-white">{{ $activeStaff->name ?? Session::get('userName') ?? 'Faculty' }}</strong>
                </div>
            </div>

            <!-- Student Table -->
            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom" id="evaluationTable">
                        <thead>
                            <tr>
                                <th class="text-center w-10">Roll</th>
                                <th class="w-28">Reg No</th>
                                <th class="min-w-[140px]">Student Name</th>
                                <th class="w-16 text-center">Batch</th>
                                <th class="min-w-[190px]">Seminar Topic &amp; Guide</th>
                                <th class="text-center w-14" title="Relevance of the topic (Max 7.5)">Relevance<br><span class="text-[9px] text-blue-400 font-mono">7.5M</span></th>
                                <th class="text-center w-14" title="Literature survey (Max 7.5)">Literature<br><span class="text-[9px] text-blue-400 font-mono">7.5M</span></th>
                                <th class="text-center w-16" title="Presentation slides & delivery (Max 37.5)">Presentation<br><span class="text-[9px] text-blue-400 font-mono">37.5M</span></th>
                                <th class="text-center w-14" title="Interaction & discussion (Max 7.5)">Discussion<br><span class="text-[9px] text-blue-400 font-mono">7.5M</span></th>
                                <th class="text-center w-14" title="Seminar Report (Max 7.5)">Report<br><span class="text-[9px] text-blue-400 font-mono">7.5M</span></th>
                                <th class="text-center w-14" title="Attendance (Max 7.5)">Attendance<br><span class="text-[9px] text-blue-400 font-mono">7.5M</span></th>
                                <th class="text-center w-16" title="Score recorded by currently logged in assessor">My Score<br><span class="text-[9px] text-slate-400 font-mono">75M</span></th>
                                <th class="text-center w-20" title="Committee Averaged Score (Click to view individual faculty marks)">Committee Avg<br><span class="text-[9px] text-blue-400 font-mono">75M</span></th>
                                <th class="text-center w-16">SBTE Grade</th>
                                <th class="text-center w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $st)
                            <tr class="student-row" 
                                id="row-eval-{{ $st['reg_no'] }}"
                                data-reg="{{ $st['reg_no'] }}"
                                data-roll="{{ $st['roll_no'] }}"
                                data-name="{{ strtolower($st['name']) }}"
                                data-batch="{{ $st['batch'] }}">
                                
                                <td class="text-center font-bold text-slate-300">{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono text-slate-300 font-semibold text-[11px]">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                                <td>
                                    <div class="font-bold text-white leading-snug">{{ $st['name'] }}</div>
                                    <div class="text-[10px] text-slate-400">Class Attendance: <span class="text-slate-300 font-semibold">{{ $st['att_percentage'] }}%</span></div>
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

                                <!-- Seminar Topic & Guide with Direct Edit Button -->
                                <td>
                                    <div class="text-[11px] text-slate-200 font-semibold truncate max-w-[240px] col-row-topic" title="{{ $st['topic'] ?? 'No topic assigned yet' }}">
                                        {{ $st['topic'] ?? '—' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span>Guide: <strong class="text-slate-300 col-row-guide">{{ $st['guide_name'] ?? 'Not Assigned' }}</strong></span>
                                        @if($st['presentation_date_formatted'])
                                            <span>• <span class="text-slate-400 font-mono col-row-date">{{ $st['presentation_date_formatted'] }}</span></span>
                                        @endif
                                    </div>
                                    <button type="button" onclick="openScheduleModal('{{ $st['reg_no'] }}')" class="mt-1 text-[10px] font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 cursor-pointer">
                                        <span class="material-symbols-rounded text-xs">edit_note</span>
                                        <span>{{ !empty($st['topic']) ? 'Edit Topic / Guide' : '+ Add Topic & Guide' }}</span>
                                    </button>
                                </td>

                                <!-- Averaged Rubrics -->
                                <td class="text-center font-mono text-slate-300 col-avg-relevance">{{ $st['avg_relevance'] !== null ? number_format($st['avg_relevance'], 1) : '—' }}</td>
                                <td class="text-center font-mono text-slate-300 col-avg-literature">{{ $st['avg_literature'] !== null ? number_format($st['avg_literature'], 1) : '—' }}</td>
                                <td class="text-center font-mono text-slate-300 col-avg-presentation">{{ $st['avg_presentation'] !== null ? number_format($st['avg_presentation'], 1) : '—' }}</td>
                                <td class="text-center font-mono text-slate-300 col-avg-interaction">{{ $st['avg_interaction'] !== null ? number_format($st['avg_interaction'], 1) : '—' }}</td>
                                <td class="text-center font-mono text-slate-300 col-avg-report">{{ $st['avg_report'] !== null ? number_format($st['avg_report'], 1) : '—' }}</td>
                                <td class="text-center font-mono text-slate-300 col-avg-attendance">{{ $st['avg_attendance'] !== null ? number_format($st['avg_attendance'], 1) : '—' }}</td>

                                <!-- My Score -->
                                <td class="text-center font-mono font-bold text-slate-200 col-my-score">
                                    {{ $st['my_evaluation'] ? number_format($st['my_evaluation']['total_score'], 1) : '—' }}
                                </td>

                                <!-- Committee Average Score with Clickable Breakdown -->
                                <td class="text-center col-final-score">
                                    @if($st['eval_count'] > 0)
                                        <button type="button" onclick="showFacultyBreakdown('{{ $st['reg_no'] }}')" class="font-mono font-bold text-sm {{ $st['final_score'] >= 30.0 ? 'text-emerald-400' : 'text-rose-400' }} hover:underline cursor-pointer" title="Click to view all faculty marks">
                                            {{ number_format($st['final_score'], 1) }}
                                        </button>
                                        <div class="text-[9px] text-slate-400 font-normal">
                                            <button type="button" onclick="showFacultyBreakdown('{{ $st['reg_no'] }}')" class="text-blue-400 hover:text-blue-300 underline">
                                                {{ $st['eval_count'] }} {{ $st['eval_count'] == 1 ? 'Faculty' : 'Faculty' }}
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-500 font-mono">—</span>
                                    @endif
                                </td>

                                <!-- SBTE Grade -->
                                <td class="text-center col-grade">
                                    @if($st['letter_grade'] !== '-')
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-black {{ $st['letter_grade'] === 'S' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($st['letter_grade'] === 'A' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/40' : ($st['letter_grade'] === 'F' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40' : 'bg-slate-800 text-slate-200 border border-slate-700')) }}">
                                            Grade {{ $st['letter_grade'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-500 font-mono text-[10px]">Pending</span>
                                    @endif
                                </td>

                                <!-- Action Button -->
                                <td class="text-center">
                                    <button type="button" 
                                            onclick="openEvaluationModal('{{ $st['reg_no'] }}')" 
                                            class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-[11px] transition flex items-center gap-1 mx-auto cursor-pointer shadow-sm">
                                        <span class="material-symbols-rounded text-xs">{{ $st['my_evaluation'] ? 'edit' : 'add' }}</span>
                                        <span>{{ $st['my_evaluation'] ? 'Edit' : 'Evaluate' }}</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="15" class="text-center py-8 text-slate-500">
                                    <span class="material-symbols-rounded text-4xl block mb-2 opacity-50">school</span>
                                    No students enrolled in this classroom.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                <div class="text-xs text-slate-300">
                    Total Scheduled: <strong class="text-blue-400">{{ $studentResults->whereNotNull('presentation_date')->count() }}</strong> / {{ $totalStudents }}
                </div>
            </div>

            <!-- Schedule Table -->
            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom" id="scheduleTable">
                        <thead>
                            <tr>
                                <th class="text-center w-12">Roll</th>
                                <th class="w-28">Reg No</th>
                                <th class="min-w-[150px]">Student Name</th>
                                <th class="w-16 text-center">Batch</th>
                                <th class="w-36 text-center">Presentation Date</th>
                                <th class="min-w-[240px]">Approved Seminar Topic</th>
                                <th class="min-w-[180px]">Seminar Guide</th>
                                <th class="text-center w-24">Status</th>
                                <th class="text-center w-20">Action</th>
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
                                <td class="font-mono text-slate-300 font-semibold text-[11px]">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
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
                                <td class="text-center">
                                    <button type="button" 
                                            onclick="openScheduleModal('{{ $st['reg_no'] }}')" 
                                            class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 hover:text-white font-bold text-[11px] transition flex items-center gap-1 mx-auto cursor-pointer shadow-sm">
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
                <div class="px-4 py-2.5 bg-slate-900 border-b border-slate-800 flex items-center justify-between">
                    <div class="font-bold text-xs text-white">Consolidated CIA Mark Register (Treated as ESE Mark - Max 75 Marks)</div>
                    <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print" target="_blank" class="text-blue-400 hover:text-blue-300 text-[11px] font-bold flex items-center gap-1">
                        <span class="material-symbols-rounded text-sm">print</span>
                        Print Formal Register
                    </a>
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
                            <tr class="student-row" data-batch="{{ $st['batch'] }}" data-reg="{{ $st['reg_no'] }}" data-name="{{ strtolower($st['name']) }}">
                                <td class="text-center font-bold text-slate-300">{{ $st['roll_no'] ?? '-' }}</td>
                                <td class="font-mono text-slate-300 font-semibold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                                <td class="font-bold text-white">{{ $st['name'] }}</td>
                                <td class="text-center font-mono">{{ $st['avg_relevance'] !== null ? number_format($st['avg_relevance'], 1) : '—' }}</td>
                                <td class="text-center font-mono">{{ $st['avg_literature'] !== null ? number_format($st['avg_literature'], 1) : '—' }}</td>
                                <td class="text-center font-mono">{{ $st['avg_presentation'] !== null ? number_format($st['avg_presentation'], 1) : '—' }}</td>
                                <td class="text-center font-mono">{{ $st['avg_interaction'] !== null ? number_format($st['avg_interaction'], 1) : '—' }}</td>
                                <td class="text-center font-mono">{{ $st['avg_report'] !== null ? number_format($st['avg_report'], 1) : '—' }}</td>
                                <td class="text-center font-mono">{{ $st['avg_attendance'] !== null ? number_format($st['avg_attendance'], 1) : '—' }}</td>
                                <td class="text-center font-mono font-bold text-sm {{ $st['final_score'] >= 30.0 ? 'text-emerald-400' : ($st['eval_count'] > 0 ? 'text-rose-400' : 'text-slate-500') }}">
                                    {{ $st['eval_count'] > 0 ? number_format($st['final_score'], 1) : '—' }}
                                </td>
                                <td class="text-center">
                                    @if($st['letter_grade'] !== '-')
                                        <span class="font-bold text-xs {{ $st['letter_grade'] === 'S' ? 'text-amber-400' : ($st['letter_grade'] === 'F' ? 'text-rose-400' : 'text-slate-200') }}">
                                            {{ $st['letter_grade'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="text-center font-mono text-slate-300">{{ $st['grade_point'] }}</td>
                                <td class="text-center">
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

    </main>

    <!-- ========================================================== -->
    <!-- ========================================================== -->
    <!-- MODAL 1: Seminar Evaluation Modal (75M) + Topic & Guide    -->
    <!-- Full-Screen on Desktop (Zero Scroll) & Responsive Mobile   -->
    <!-- ========================================================== -->
    <div id="evaluationModal" class="fixed inset-0 z-[80] hidden bg-[#0b0f19] flex-col w-screen h-screen overflow-hidden">
        
        <!-- Sticky Modal Header (Zero-scroll desktop, compact height: ~52px) -->
        <div class="px-4 py-2.5 sm:px-6 bg-[#0c1322] border-b border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-lg sm:text-xl">rate_review</span>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <div class="text-sm sm:text-base font-extrabold text-white leading-tight" id="evalModalStudentName">Student Evaluation</div>
                    <div class="text-xs text-slate-400 font-mono px-2 py-0.5 rounded bg-slate-900 border border-slate-800" id="evalModalStudentMeta">
                        Reg: - • Roll: -
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Live Header Score Pill -->
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-900 border border-slate-800 font-mono text-xs">
                    <span class="text-slate-400 font-medium">Live Total:</span>
                    <span class="font-bold text-blue-400 text-sm" id="evalHeaderScoreVal">0.0</span>
                    <span class="text-slate-500">/ 75</span>
                </div>

                <button type="button" onclick="closeEvaluationModal()" class="text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-slate-800/80 transition cursor-pointer" title="Close Modal (Esc)">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>
        </div>

        <!-- Form wrapping Body & Footer with 100% viewport fill -->
        <form id="evaluationForm" onsubmit="submitEvaluationForm(event)" class="flex flex-col flex-grow overflow-hidden">
            <input type="hidden" id="evalRegNo" name="reg_no">

            <!-- Body: On Desktop (lg:), it fits 100% without scroll (overflow-y-auto lg:overflow-y-hidden). On mobile/small screens, smooth vertical scroll -->
            <div class="p-3 sm:p-4 lg:p-5 flex-grow overflow-y-auto lg:overflow-y-hidden flex flex-col justify-between space-y-2.5 lg:space-y-3">
                
                <!-- Row 1: Assessor Selector, Topic, Assigned Guide & Presentation Date in 1 Sleek Horizontal Bar on Desktop -->
                <div class="p-2.5 sm:p-3 rounded-xl bg-slate-900/90 border border-slate-800 shrink-0 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 sm:gap-3 items-center">
                        <!-- Assessor Selector (Committee Member) -->
                        <div class="lg:col-span-3 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-300 truncate">Evaluating Assessor</label>
                                <span class="text-[10px] text-slate-400 font-mono hidden lg:inline" id="currentAssessorDisplay">{{ $activeStaff->name ?? 'Faculty' }}</span>
                            </div>
                            <select id="evalAssessorMobile" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-xs focus:border-blue-500 outline-none" onchange="onAssessorChange(this.value)">
                                @foreach($guides as $g)
                                    <option value="{{ $g->mobile_no }}" {{ ($activeStaff && $activeStaff->mobile_no == $g->mobile_no) ? 'selected' : '' }}>
                                        {{ $g->name }} ({{ $g->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Approved Seminar Topic -->
                        <div class="lg:col-span-4 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-300 truncate">Approved Seminar Topic</label>
                            <input type="text" id="evalTopicInput" placeholder="Enter approved seminar topic title..." class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-xs focus:border-blue-500 outline-none">
                        </div>

                        <!-- Assigned Guide -->
                        <div class="lg:col-span-3 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-300 truncate">Assigned Guide</label>
                            <select id="evalGuideSelect" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-xs focus:border-blue-500 outline-none">
                                <option value="">— Select Faculty Guide —</option>
                                @foreach($guides as $g)
                                    <option value="{{ $g->mobile_no }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Presentation Date -->
                        <div class="lg:col-span-2 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-300 truncate">Presentation Date</label>
                            <input type="date" id="evalPresentationDateInput" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-xs font-mono focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Committee Breakdown Box (Compact horizontal bar if multiple faculty evaluated) -->
                <div id="evalCommitteeBreakdownBox" class="hidden px-3 py-2 rounded-xl bg-slate-900/90 border border-blue-900/50 shrink-0">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-base text-blue-400">group</span>
                            <span>Recorded Committee Scores</span>
                        </span>
                        <span class="text-xs font-semibold text-blue-300" id="evalBreakdownAvgText">Average: 0 / 75</span>
                    </div>
                    <div id="evalBreakdownList" class="mt-1.5 space-y-1 text-xs"></div>
                </div>

                <!-- Rubric Heading & Badge -->
                <div class="flex items-center justify-between shrink-0">
                    <div class="text-xs sm:text-sm font-extrabold text-white flex items-center gap-2">
                        <span class="material-symbols-rounded text-base text-blue-400">gavel</span>
                        <span>Evaluation Rubrics (Clause 11.2.6 &bull; Total 75 Marks)</span>
                    </div>
                    <span class="text-[11px] text-slate-400 hidden sm:inline font-mono">Compact sliders or direct numeric score</span>
                </div>

                <!-- 6 Evaluation Criteria in 2 Balanced Columns on Desktop (Zero Vertical Scroll) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-2.5 lg:gap-3 flex-grow">
                    
                    <!-- Column 1: Criteria 1, 2, 3 -->
                    <div class="flex flex-col justify-between gap-2 lg:gap-2.5">
                        
                        <!-- Criterion 1: Relevance of Topic (Max 7.5) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                                <div class="truncate">
                                    <span class="font-bold text-slate-100 text-xs sm:text-sm">Relevance of Topic</span>
                                    <span class="text-slate-400 text-[11px] ml-1">(10%)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="7.5" step="0.25" id="range_relevance" class="w-24 sm:w-28" oninput="syncEvalInput('relevance')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_relevance" class="w-14 bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-center text-white font-bold text-xs sm:text-sm focus:border-blue-500 outline-none" oninput="syncEvalSlider('relevance')">
                                    <span class="text-slate-500 text-xs">/ 7.5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Criterion 2: Literature Survey (Max 7.5) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                                <div class="truncate">
                                    <span class="font-bold text-slate-100 text-xs sm:text-sm">Literature Survey</span>
                                    <span class="text-slate-400 text-[11px] ml-1">(10%)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="7.5" step="0.25" id="range_literature" class="w-24 sm:w-28" oninput="syncEvalInput('literature')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_literature" class="w-14 bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-center text-white font-bold text-xs sm:text-sm focus:border-blue-500 outline-none" oninput="syncEvalSlider('literature')">
                                    <span class="text-slate-500 text-xs">/ 7.5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Criterion 3: Presentation: Slides & Delivery (Max 37.5 - 50% Hero) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-blue-950/50 to-slate-900 border border-blue-500/50 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-sm">3</span>
                                <div class="truncate">
                                    <span class="font-extrabold text-white text-xs sm:text-sm">Presentation (Slides &amp; Delivery)</span>
                                    <span class="px-1.5 py-0.2 rounded bg-blue-600/30 text-blue-300 text-[10px] font-bold border border-blue-500/40 ml-1">50%</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="37.5" step="0.5" id="range_presentation" class="w-28 sm:w-36" oninput="syncEvalInput('presentation')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="37.5" id="input_presentation" class="w-16 bg-slate-950 border border-blue-500 rounded-lg px-1.5 py-1 text-center text-white font-black text-xs sm:text-sm focus:border-blue-400 outline-none" oninput="syncEvalSlider('presentation')">
                                    <span class="text-blue-400 font-bold text-xs sm:text-sm">/ 37.5</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Column 2: Criteria 4, 5, 6 -->
                    <div class="flex flex-col justify-between gap-2 lg:gap-2.5">
                        
                        <!-- Criterion 4: Interaction / Discussion (Max 7.5) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">4</span>
                                <div class="truncate">
                                    <span class="font-bold text-slate-100 text-xs sm:text-sm">Interaction / Discussion</span>
                                    <span class="text-slate-400 text-[11px] ml-1">(10%)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="7.5" step="0.25" id="range_interaction" class="w-24 sm:w-28" oninput="syncEvalInput('interaction')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_interaction" class="w-14 bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-center text-white font-bold text-xs sm:text-sm focus:border-blue-500 outline-none" oninput="syncEvalSlider('interaction')">
                                    <span class="text-slate-500 text-xs">/ 7.5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Criterion 5: Seminar Report (Max 7.5) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">5</span>
                                <div class="truncate">
                                    <span class="font-bold text-slate-100 text-xs sm:text-sm">Seminar Report</span>
                                    <span class="text-slate-400 text-[11px] ml-1">(10%)</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="7.5" step="0.25" id="range_report" class="w-24 sm:w-28" oninput="syncEvalInput('report')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_report" class="w-14 bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-center text-white font-bold text-xs sm:text-sm focus:border-blue-500 outline-none" oninput="syncEvalSlider('report')">
                                    <span class="text-slate-500 text-xs">/ 7.5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Criterion 6: Attendance (Max 7.5) -->
                        <div class="px-3.5 py-2.5 rounded-xl bg-slate-950/70 border border-slate-800 flex items-center justify-between gap-3 shadow-sm flex-grow">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">6</span>
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="font-bold text-slate-100 text-xs sm:text-sm">Attendance</span>
                                    <button type="button" onclick="applySuggestedAttendance()" id="btnApplySuggestedAtt" class="px-2 py-0.5 bg-slate-900 hover:bg-slate-800 border border-emerald-600/50 text-emerald-400 rounded-lg font-bold text-[10px] transition flex items-center gap-1 cursor-pointer shrink-0" title="Apply Auto-Calculated Attendance Score">
                                        <span class="material-symbols-rounded text-xs">auto_fix_high</span>
                                        <span>Auto (<span id="modalSuggestedAttVal">7.5</span>M)</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <input type="range" min="0" max="7.5" step="0.25" id="range_attendance" class="w-24 sm:w-28" oninput="syncEvalInput('attendance')">
                                <div class="flex items-center gap-1 font-mono">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_attendance" class="w-14 bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1 text-center text-white font-bold text-xs sm:text-sm focus:border-blue-500 outline-none" oninput="syncEvalSlider('attendance')">
                                    <span class="text-slate-500 text-xs">/ 7.5</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Sticky Modal Footer (Pinned at bottom, compact ~56px) -->
            <div class="px-4 py-2.5 sm:px-6 sm:py-3 bg-[#0c1322] border-t border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shrink-0 shadow-lg">
                <div class="flex items-center justify-between sm:justify-start gap-4">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Total Score (Clause 11.2.6)</div>
                        <div class="text-xl sm:text-2xl font-black text-white font-mono leading-tight mt-0.5" id="evalLiveTotal">
                            0.0 <span class="text-xs text-slate-500 font-normal">/ 75.0</span>
                        </div>
                    </div>
                    <div class="border-l border-slate-800 pl-4">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Projected SBTE Grade</div>
                        <div class="text-sm sm:text-base font-extrabold mt-0.5" id="evalLiveGrade">—</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button type="button" onclick="closeEvaluationModal()" class="flex-1 sm:flex-none px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs sm:text-sm transition cursor-pointer min-h-[40px]">
                        Cancel (Esc)
                    </button>
                    <button type="submit" id="btnSaveEval" class="flex-1 sm:flex-none px-6 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-sm cursor-pointer min-h-[40px]">
                        <span class="material-symbols-rounded text-lg">save</span>
                        <span>Save Evaluation</span>
                    </button>
                </div>
            </div>

        </form>
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
        function returnToParent() {
            // 1. If opened by a parent window (window.open), close and focus caller
            if (window.opener && !window.opener.closed) {
                window.opener.focus();
                window.close();
                return;
            }
            // 2. If referrer exists and is not this same URL, navigate there
            if (document.referrer && document.referrer !== window.location.href) {
                window.location.href = document.referrer;
                return;
            }
            // 3. Fallback to browser history back
            if (window.history.length > 1) {
                window.history.back();
                return;
            }
            // 4. Default to dashboard
            window.location.href = '{{ $dashboardUrl }}';
        }

        // ---------------- TAB SWITCHING ----------------
        function switchTab(tabKey) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                btn.classList.add('text-slate-400');
            });

            const targetPane = document.getElementById(`tabContent-${tabKey}`);
            const targetBtn = document.getElementById(`tabBtn-${tabKey}`);
            if (targetPane) targetPane.classList.remove('hidden');
            if (targetBtn) {
                targetBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                targetBtn.classList.remove('text-slate-400');
            }
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

                // Attendance help
                setText('modalSuggestedAttVal', st.suggested_att_mark);
                setText('modalAttHelpText', `Class attendance percentage: ${st.att_percentage}% -> Auto Suggested: ${st.suggested_att_mark} / 7.5 M`);

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
            for (let c in criteriaConfig) {
                let val = evalObj ? evalObj[c] : (c === 'attendance' ? st.suggested_att_mark : 0);
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
            avgText.textContent = `Committee Average: ${st.final_score.toFixed(1)} / 75 (Grade ${st.letter_grade})`;

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
                            <span class="text-xs font-bold text-blue-400 font-mono">${ev.total_score.toFixed(1)} M</span>
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
                        row.querySelector('.col-my-score').textContent = Number(data.data.my_total).toFixed(1);
                        const finalEl = row.querySelector('.col-final-score');
                        finalEl.innerHTML = `
                            <button type="button" onclick="showFacultyBreakdown('${payload.reg_no}')" class="font-mono font-bold text-sm ${data.data.average_score >= 30 ? 'text-emerald-400' : 'text-rose-400'} hover:underline cursor-pointer">
                                ${Number(data.data.average_score).toFixed(1)}
                            </button>
                            <div class="text-[9px] text-slate-400 font-normal">
                                <button type="button" onclick="showFacultyBreakdown('${payload.reg_no}')" class="text-blue-400 hover:text-blue-300 underline">
                                    ${data.data.eval_count} Faculty
                                </button>
                            </div>
                        `;

                        const gradeEl = row.querySelector('.col-grade');
                        gradeEl.innerHTML = `<span class="inline-block px-2 py-0.5 rounded text-[11px] font-black ${data.data.letter_grade === 'S' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : (data.data.letter_grade === 'A' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/40' : (data.data.letter_grade === 'F' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40' : 'bg-slate-800 text-slate-200 border border-slate-700'))}">Grade ${data.data.letter_grade}</span>`;

                        if (data.data.topic) row.querySelector('.col-row-topic').textContent = data.data.topic;
                        if (data.data.guide_name) row.querySelector('.col-row-guide').textContent = data.data.guide_name;
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
            document.getElementById('breakdownFinalAvg').innerHTML = `${st.final_score.toFixed(1)} <span class="text-xs text-slate-400 font-normal">/ 75.0</span>`;
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
                                <span class="font-bold text-sm text-blue-400 font-mono">${ev.total_score.toFixed(1)} / 75</span>
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
</body>
</html>
