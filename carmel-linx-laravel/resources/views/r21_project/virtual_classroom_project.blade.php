<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[R-2021] Virtual Major Project Room - {{ $batchSubject->subject_name }}</title>

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
            font-size: 0.8rem;
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
            font-size: 0.76rem;
            line-height: 1.3;
        }
        .table-custom th {
            background-color: #0f172a;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.03em;
            padding: 0.6rem 0.7rem;
            border-bottom: 1px solid #1e293b;
            white-space: nowrap;
        }
        .table-custom td {
            background-color: #111a2e;
            border-bottom: 1px solid #1a2744;
            vertical-align: middle;
            padding: 0.5rem 0.7rem;
            font-weight: 500;
        }
        .table-custom tr:hover td {
            background-color: #17233d;
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
        .tab-btn.active {
            color: #38bdf8;
            border-bottom-color: #38bdf8;
            background-color: rgba(56, 189, 248, 0.08);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#0b0f19] text-slate-100">
    @php
        $role = Session::get('userRole');
        $dashboardUrl = '/dashboard/lecturer';
        if ($role === 'HOD') $dashboardUrl = '/dashboard/hod';
        elseif ($role === 'Principal') $dashboardUrl = '/dashboard/principal';
        elseif ($role === 'Super_Admin') $dashboardUrl = '/dashboard/superadmin';
        elseif ($role === 'Admin') $dashboardUrl = '/dashboard/admin';
        elseif ($role === 'Demonstrator') $dashboardUrl = '/dashboard/demonstrator';
        elseif ($role === 'Trade_Instructor') $dashboardUrl = '/dashboard/tradeinstructor';
        elseif ($role === 'Workshop_Superintendent') $dashboardUrl = '/dashboard/workshop';
        elseif ($role === 'Tutor') $dashboardUrl = '/dashboard/tutor';
    @endphp

    <!-- Top Navigation Header -->
    <header class="bg-[#0f172a] border-b border-slate-800 px-5 py-3 flex flex-wrap items-center justify-between gap-3 sticky top-0 z-40 shadow-lg">
        <div class="flex items-center gap-3">
            <button type="button" onclick="returnToParent()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg transition-all cursor-pointer flex items-center gap-1.5 border border-slate-700/60" title="Return to Caller / Dashboard">
                <span class="material-symbols-rounded text-lg">arrow_back</span>
                <span class="text-xs font-bold hidden sm:inline">Back</span>
            </button>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded text-xs font-bold uppercase tracking-wider">R-2021 Regulation</span>
                    <span class="px-2 py-0.5 bg-sky-500/10 border border-sky-500/30 text-sky-400 rounded text-xs font-bold">Clause 11.2.5 & 11.3.4</span>
                    <h1 class="text-base md:text-lg font-extrabold text-white tracking-tight">{{ $batchSubject->subject_name }}</h1>
                    <span class="text-xs px-2 py-0.5 bg-slate-800 text-slate-300 rounded font-mono font-bold">{{ $batchSubject->subject_code }}</span>
                </div>
                <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                    <span>{{ $classroom->department ?? 'Engineering' }}</span>
                    <span>•</span>
                    <span>Semester VI</span>
                    <span>•</span>
                    <span class="text-emerald-400 font-semibold">CIA: 75 Marks | ESE: 50 Marks | Total: 125 Marks</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button onclick="openGroupModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                <span class="material-symbols-rounded text-base text-amber-400">group</span> Project Groups
            </button>
            <button onclick="openEseDualModal()" class="px-3 py-1.5 bg-amber-600/20 hover:bg-amber-600/30 border border-amber-500/30 text-amber-300 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                <span class="material-symbols-rounded text-base text-amber-400">grading</span> Dual ESE Entry
            </button>
            <button onclick="openAttainmentModal()" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/30 text-indigo-300 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                <span class="material-symbols-rounded text-base text-indigo-400">insights</span> Attainment
            </button>
            <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print" target="_blank" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm">
                <span class="material-symbols-rounded text-base">print</span> Print Register
            </a>
        </div>
    </header>

    <!-- Stats Quick Strip -->
    <div class="px-5 py-3 bg-[#0c1322] border-b border-slate-800 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400">
                <span class="material-symbols-rounded text-lg">people</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Enrolled</div>
                <div class="text-base font-extrabold text-white">{{ $totalStudents }}</div>
            </div>
        </div>

        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                <span class="material-symbols-rounded text-lg">check_circle</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Evaluated</div>
                <div class="text-base font-extrabold text-emerald-400">{{ $evaluatedCount }}</div>
            </div>
        </div>

        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                <span class="material-symbols-rounded text-lg">hourglass_top</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Pending</div>
                <div class="text-base font-extrabold text-amber-400">{{ $pendingCount }}</div>
            </div>
        </div>

        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                <span class="material-symbols-rounded text-lg">verified</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Passed (Min 40%)</div>
                <div class="text-base font-extrabold text-indigo-300">{{ $passedCount }}</div>
            </div>
        </div>

        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <span class="material-symbols-rounded text-lg">speed</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Class Avg CIA</div>
                <div class="text-base font-extrabold text-cyan-400">{{ $avgCia }} <span class="text-xs text-slate-500 font-normal">/75</span></div>
            </div>
        </div>

        <div class="glass-panel p-2.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                <span class="material-symbols-rounded text-lg">bar_chart</span>
            </div>
            <div>
                <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Class Avg ESE</div>
                <div class="text-base font-extrabold text-purple-400">{{ $avgEse }} <span class="text-xs text-slate-500 font-normal">/50</span></div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Strip -->
    <div class="px-5 bg-[#0f172a] border-b border-slate-800 flex items-center gap-1 overflow-x-auto">
        <button onclick="switchTab('tab-register')" id="btn-tab-register" class="tab-btn active px-4 py-2.5 text-xs font-bold border-b-2 border-transparent hover:text-sky-400 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-rounded text-base">table_chart</span> Consolidated Marksheet
        </button>
        <button onclick="switchTab('tab-group-report')" id="btn-tab-group-report" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-400 hover:text-sky-400 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-rounded text-base">format_list_bulleted</span> Group-Wise Breakdown
        </button>
        <button onclick="switchTab('tab-groups')" id="btn-tab-groups" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-400 hover:text-sky-400 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-rounded text-base">workspaces</span> Project Groups (Batches)
        </button>
        <button onclick="switchTab('tab-attainment')" id="btn-tab-attainment" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-400 hover:text-sky-400 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-rounded text-base">analytics</span> CO Attainment Analysis
        </button>
        <button onclick="switchTab('tab-rubrics')" id="btn-tab-rubrics" class="tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-400 hover:text-sky-400 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-rounded text-base">menu_book</span> SBTE Guidelines & Rubrics
        </button>
    </div>

    <!-- Main Content Container -->
    <main class="flex-1 p-5 overflow-y-auto">

        <!-- TAB 1: Consolidated Marksheet -->
        <div id="tab-register" class="tab-content block space-y-4">
            <div class="glass-panel p-4 flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64">
                        <span class="material-symbols-rounded absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
                        <input type="text" id="filterInput" onkeyup="filterTable()" placeholder="Search roll, reg no, student, title..." class="w-full pl-9 pr-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-sky-500">
                    </div>
                    <select id="groupFilter" onchange="filterTable()" class="bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-sky-500">
                        <option value="">All Groups</option>
                        @foreach($projectGroups as $grp)
                            <option value="{{ $grp['name'] ?? $grp['id'] }}">{{ $grp['name'] ?? 'Group ' . $grp['id'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Passed (&ge;40%)
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500 ml-2"></span> Failed (&lt;40%)
                </div>
            </div>

            <!-- Marksheet Table -->
            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="table-custom" id="mainMarksheetTable">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Roll / Reg No</th>
                                <th>Student Name</th>
                                <th>Group</th>
                                <th>Project Title</th>
                                <th class="text-center" title="Weekly Diary / Activity (Max 30 Marks)">Diary<br><span class="text-slate-400 font-normal">30M</span></th>
                                <th class="text-center" title="Department Evaluation (Max 30 Marks)">Dept Eval<br><span class="text-slate-400 font-normal">30M</span></th>
                                <th class="text-center" title="Attendance (Max 15 Marks)">Attd<br><span class="text-slate-400 font-normal">15M</span></th>
                                <th class="text-center bg-slate-900 text-sky-400 font-bold" title="Total CIA = Diary + Dept + Attd">CIA Tot<br><span class="font-normal">75M</span></th>
                                <th class="text-center bg-amber-950/40 text-amber-300 font-bold" title="External & Internal ESE (Max 50 Marks)">ESE Tot<br><span class="font-normal">50M</span></th>
                                <th class="text-center">Grade</th>
                                <th class="text-center bg-blue-950/40 text-blue-300 font-bold">Grand Tot<br><span class="font-normal">125M</span></th>
                                <th class="text-center">Result</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentResults as $idx => $st)
                                <tr id="row-{{ $st['reg_no'] }}" class="student-row" data-group="{{ $st['group_name'] }}">
                                    <td class="text-center text-slate-400">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="font-bold text-white">{{ $st['sbte_reg_no'] }}</div>
                                        @if($st['roll_no']) <div class="text-[0.68rem] text-slate-400">Roll: {{ $st['roll_no'] }}</div> @endif
                                    </td>
                                    <td>
                                        <div class="font-bold text-slate-200">{{ $st['name'] }}</div>
                                    </td>
                                    <td>
                                        <span class="px-2 py-0.5 rounded text-[0.68rem] font-bold bg-slate-800 text-slate-300">{{ $st['group_name'] }}</span>
                                    </td>
                                    <td class="max-w-xs truncate text-[0.72rem] text-slate-300" title="{{ $st['project_title'] ?: 'No title set' }}">
                                        {{ $st['project_title'] ?: '-' }}
                                    </td>
                                    <td class="text-center font-mono">{{ number_format($st['formative_diary_marks'], 1) }}</td>
                                    <td class="text-center font-mono">{{ number_format($st['summative_dept_marks'], 1) }}</td>
                                    <td class="text-center font-mono" title="{{ $st['att_percentage'] }}% attendance">
                                        {{ number_format($st['attendance_marks'], 1) }}
                                    </td>
                                    <td class="text-center font-mono font-bold bg-slate-900/80 text-sky-300">
                                        {{ number_format($st['total_cia_75'], 1) }}
                                    </td>
                                    <td class="text-center font-mono font-bold bg-amber-950/20 text-amber-300">
                                        {{ number_format($st['total_ese_50'], 1) }}
                                    </td>
                                    <td class="text-center font-bold">
                                        @if($st['ese_grade'])
                                            <span class="px-2 py-0.5 rounded text-xs {{ in_array($st['ese_grade'], ['S','A','B','C']) ? 'bg-emerald-500/20 text-emerald-400' : (in_array($st['ese_grade'], ['D','E']) ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400') }}">
                                                {{ $st['ese_grade'] }}
                                            </span>
                                        @else
                                            <span class="text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center font-mono font-bold bg-blue-950/20 text-blue-300">
                                        {{ number_format($st['grand_total_125'], 1) }}
                                    </td>
                                    <td class="text-center">
                                        @if($st['has_eval'])
                                            <span class="px-2 py-0.5 rounded text-[0.68rem] font-bold {{ $st['passed'] ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                                {{ $st['passed'] ? 'Pass' : 'Fail' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[0.68rem] bg-slate-800 text-slate-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button onclick='openEvalModal(@json($st))' class="px-2.5 py-1 bg-sky-600/20 hover:bg-sky-600/30 text-sky-300 border border-sky-500/30 rounded text-xs font-bold transition-all flex items-center gap-1 mx-auto">
                                            <span class="material-symbols-rounded text-sm">edit</span> Evaluate
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-8 text-slate-400">No students enrolled in this classroom.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB: Group-Wise Report & Split-up Breakdown -->
        <div id="tab-group-report" class="tab-content hidden space-y-6">
            <div class="glass-panel p-4 flex flex-col md:flex-row items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-rounded text-emerald-400 text-base">format_list_bulleted</span>
                        Group-Wise Project Evaluation & Attendance Split-up
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Official R-2021 breakdown: Weekly Diary (30M) + Dept Presentation (30M) + Attendance (15M) = CIA (75M) | ESE 8-Rubrics (50M) | Grand Total (125M).</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print" target="_blank" class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md cursor-pointer">
                        <span class="material-symbols-rounded text-base">print</span> Print Official Group Register
                    </a>
                </div>
            </div>

            @php
                $groupedResults = $studentResults->groupBy('group_name');
            @endphp

            @forelse($groupedResults as $grpName => $members)
                @php
                    $firstM = $members->first();
                    $evalCount = $members->where('has_eval', true)->count();
                    $avgGroupCia = $evalCount > 0 ? round($members->where('has_eval', true)->avg('total_cia_75'), 1) : 0.0;
                    $avgGroupEse = $evalCount > 0 ? round($members->where('has_eval', true)->avg('total_ese_50'), 1) : 0.0;
                    $avgGroupTotal = $evalCount > 0 ? round($members->where('has_eval', true)->avg('grand_total_125'), 1) : 0.0;
                    $passCount = $members->where('passed', true)->count();
                @endphp
                <div class="glass-panel overflow-hidden border border-slate-700/80 shadow-lg">
                    <!-- Group Header Strip -->
                    <div class="px-5 py-3 bg-[#0f172a] border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-300 font-bold rounded text-xs uppercase tracking-wide">
                                {{ $grpName }}
                            </span>
                            <div>
                                <span class="text-xs text-slate-400 font-semibold">Project Title:</span>
                                <strong class="text-white text-xs ml-1">{{ $firstM['project_title'] ?: 'Pending Title' }}</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 font-semibold">Guide:</span>
                                <strong class="text-sky-300 ml-1">{{ $firstM['guide_name'] ?: 'Not Assigned' }}</strong>
                            </div>
                            <span class="text-slate-500">•</span>
                            <div class="text-slate-300">
                                <strong>{{ count($members) }}</strong> Students
                            </div>
                        </div>
                    </div>

                    <!-- Split-up Table -->
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="w-8 text-center">#</th>
                                    <th rowspan="2">Roll / Reg No</th>
                                    <th rowspan="2">Student Name</th>
                                    <th colspan="2" class="text-center bg-sky-950/30 text-sky-400">Attendance</th>
                                    <th colspan="4" class="text-center bg-slate-900 text-slate-300">CIA Split-up (75M)</th>
                                    <th colspan="9" class="text-center bg-amber-950/30 text-amber-300">ESE Split-up (50M)</th>
                                    <th rowspan="2" class="text-center bg-blue-950/30 text-blue-300 font-bold">Grand<br>125M</th>
                                    <th rowspan="2" class="text-center">Result</th>
                                    <th rowspan="2" class="text-center">Action</th>
                                </tr>
                                <tr>
                                    <th class="text-center bg-sky-950/10 text-sky-300 text-[0.62rem]">%</th>
                                    <th class="text-center bg-sky-950/10 text-sky-300 text-[0.62rem]" title="Attendance Mark (15M)">15M</th>
                                    
                                    <th class="text-center text-[0.62rem]" title="Weekly Activity Diary (30M)">Diary<br>30M</th>
                                    <th class="text-center text-[0.62rem]" title="Department Review (30M)">Dept<br>30M</th>
                                    <th class="text-center text-[0.62rem]">Attd<br>15M</th>
                                    <th class="text-center bg-slate-900 font-bold text-sky-400 text-[0.65rem]">CIA<br>75M</th>

                                    <th class="text-center text-[0.6rem]" title="Prototype (10M)">Proto<br>10M</th>
                                    <th class="text-center text-[0.6rem]" title="Modern Tools (5M)">Tools<br>5M</th>
                                    <th class="text-center text-[0.6rem]" title="Presentation (7.5M)">Pres<br>7.5</th>
                                    <th class="text-center text-[0.6rem]" title="Innovation (2.5M)">Inno<br>2.5</th>
                                    <th class="text-center text-[0.6rem]" title="Viva (7.5M)">Viva<br>7.5</th>
                                    <th class="text-center text-[0.6rem]" title="Individual (7.5M)">Indiv<br>7.5</th>
                                    <th class="text-center text-[0.6rem]" title="Group Activity (5M)">Grp<br>5M</th>
                                    <th class="text-center text-[0.6rem]" title="Report (5M)">Rep<br>5M</th>
                                    <th class="text-center bg-amber-950/20 font-bold text-amber-300 text-[0.65rem]">ESE<br>50M</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $mIdx => $st)
                                    <tr>
                                        <td class="text-center text-slate-400">{{ $mIdx + 1 }}</td>
                                        <td>
                                            <div class="font-mono font-bold text-white">{{ $st['sbte_reg_no'] }}</div>
                                            @if($st['roll_no']) <div class="text-[0.65rem] text-slate-400">Roll: {{ $st['roll_no'] }}</div> @endif
                                        </td>
                                        <td>
                                            <div class="font-bold text-slate-200">{{ $st['name'] }}</div>
                                        </td>

                                        <!-- Attendance -->
                                        <td class="text-center font-bold {{ $st['att_percentage'] >= 75 ? 'text-emerald-400' : ($st['att_percentage'] >= 65 ? 'text-amber-400' : 'text-rose-400') }}">
                                            {{ $st['att_percentage'] }}%
                                        </td>
                                        <td class="text-center font-mono font-bold text-emerald-400 bg-emerald-950/10">
                                            {{ number_format($st['attendance_marks'], 1) }}
                                        </td>

                                        <!-- CIA Split-up -->
                                        <td class="text-center font-mono">{{ number_format($st['formative_diary_marks'], 1) }}</td>
                                        <td class="text-center font-mono">{{ number_format($st['summative_dept_marks'], 1) }}</td>
                                        <td class="text-center font-mono">{{ number_format($st['attendance_marks'], 1) }}</td>
                                        <td class="text-center font-mono font-bold text-sky-300 bg-slate-900/60">
                                            {{ number_format($st['total_cia_75'], 1) }}
                                        </td>

                                        <!-- ESE Rubrics -->
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_prototype'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_modern_tools'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_presentation'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_innovativeness'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_viva'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_individual_contrib'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_group_activity'], 1) }}</td>
                                        <td class="text-center font-mono text-[0.7rem]">{{ number_format($st['ese_project_report'], 1) }}</td>
                                        <td class="text-center font-mono font-bold text-amber-300 bg-amber-950/20">
                                            {{ number_format($st['total_ese_50'], 1) }}
                                            @if($st['ese_grade'])
                                                <span class="text-[0.65rem] px-1 py-0.5 rounded ml-1 bg-slate-800 text-amber-400 font-bold">({{ $st['ese_grade'] }})</span>
                                            @endif
                                        </td>

                                        <!-- Grand Total & Result -->
                                        <td class="text-center font-mono font-bold text-blue-300 bg-blue-950/20">
                                            {{ number_format($st['grand_total_125'], 1) }}
                                        </td>
                                        <td class="text-center">
                                            @if($st['has_eval'])
                                                <span class="px-2 py-0.5 rounded text-[0.68rem] font-bold {{ $st['passed'] ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border border-rose-500/30' }}">
                                                    {{ $st['passed'] ? 'Pass' : 'Fail' }}
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[0.68rem] bg-slate-800 text-slate-400">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button onclick='openEvalModal(@json($st))' class="px-2 py-0.5 bg-sky-600/20 hover:bg-sky-600/30 text-sky-300 border border-sky-500/30 rounded text-[0.7rem] font-bold transition-all inline-flex items-center gap-1">
                                                <span class="material-symbols-rounded text-xs">edit</span> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Group Average Footer Bar -->
                    <div class="px-5 py-2.5 bg-slate-950/80 border-t border-slate-800 flex flex-wrap items-center justify-between text-xs">
                        <div class="text-slate-400 font-semibold">Group Summary:</div>
                        <div class="flex items-center gap-6 font-mono text-xs">
                            <div>CIA Avg: <strong class="text-sky-300">{{ $avgGroupCia }}</strong> / 75</div>
                            <div>ESE Avg: <strong class="text-amber-300">{{ $avgGroupEse }}</strong> / 50</div>
                            <div>Grand Avg: <strong class="text-blue-300">{{ $avgGroupTotal }}</strong> / 125</div>
                            <div class="text-slate-300 font-sans">
                                Passed: <strong class="text-emerald-400 font-mono">{{ $passCount }}</strong> / {{ count($members) }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel p-8 text-center text-slate-400">
                    No project groups found. Please allocate groups in the <strong>"Project Groups (Batches)"</strong> tab.
                </div>
            @endforelse
        </div>

        <!-- TAB 2: Project Groups & Batches -->
        <div id="tab-groups" class="tab-content hidden space-y-4">
            <div class="glass-panel p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-white">Project Groups Allocation</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Organize student teams, allocate project titles, and assign faculty mentors/guides.</p>
                </div>
                <button onclick="addGroupRow()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-rounded text-sm">add</span> New Project Group
                </button>
            </div>

            <div id="groupsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($projectGroups as $idx => $grp)
                    <div class="glass-panel p-4 group-card" data-idx="{{ $idx }}">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-amber-400">folder_special</span>
                                <span class="font-bold text-white text-sm">{{ $grp['name'] ?? 'Group ' . ($idx + 1) }}</span>
                            </div>
                            <button type="button" onclick="promptDeleteGroup(this)" class="text-rose-400 hover:text-rose-300 p-1 rounded hover:bg-rose-500/10 transition-all cursor-pointer" title="Delete this project group">
                                <span class="material-symbols-rounded text-base">delete</span>
                            </button>
                        </div>
                        <div class="space-y-2.5">
                            <div>
                                <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Group Name</label>
                                <input type="text" class="grp-name w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" value="{{ $grp['name'] ?? 'Group ' . ($idx + 1) }}">
                            </div>
                            <div>
                                <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Project Title</label>
                                <input type="text" class="grp-title w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" value="{{ $grp['title'] ?? '' }}" placeholder="Enter major project title...">
                            </div>
                            <div>
                                <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Faculty Guide</label>
                                <select class="grp-guide w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white">
                                    <option value="">Select Department Guide...</option>
                                    @foreach($guides as $g)
                                        <option value="{{ $g->mobile_no }}" {{ ($grp['guide_mobile_no'] ?? '') == $g->mobile_no ? 'selected' : '' }}>
                                            {{ $g->name }} ({{ $g->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Select Group Members</label>
                                <div class="max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded p-2 custom-scrollbar space-y-1">
                                    @foreach($students as $s)
                                        <label class="flex items-center gap-2 text-xs text-slate-300 hover:bg-slate-800 p-1 rounded cursor-pointer">
                                            <input type="checkbox" class="grp-member" value="{{ $s->reg_no }}" {{ in_array($s->reg_no, $grp['members'] ?? []) ? 'checked' : '' }}>
                                            <span>{{ $s->sbte_reg_no }} - {{ $s->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="noGroupsNotice" class="col-span-2 text-center py-8 glass-panel text-slate-400">
                        No groups configured yet. Click <strong>"New Project Group"</strong> above to organize your class.
                    </div>
                @endforelse
            </div>

            <div class="flex justify-end">
                <button onclick="saveProjectGroups()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md">
                    <span class="material-symbols-rounded text-base">save</span> Save All Project Groups
                </button>
            </div>
        </div>

        <!-- TAB 3: Attainment Analysis -->
        <div id="tab-attainment" class="tab-content hidden space-y-4">
            <div class="glass-panel p-4 flex flex-col md:flex-row items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-white">Major Project Outcome (CO) Attainment Analysis</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Calculated using 30% Academic CIE (Formative 30M + Summative 30M, Attendance Excluded) + 70% ESE (50M) + 20% Course Exit Survey.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                        <span class="material-symbols-rounded text-sm">print</span> Print Register
                    </a>
                    <button onclick="loadAttainmentData()" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md">
                        <span class="material-symbols-rounded text-sm">refresh</span> Refresh Attainment
                    </button>
                </div>
            </div>

            <!-- End Semester Exit Survey (Indirect Attainment Panel) -->
            <div class="glass-panel p-4 border border-indigo-500/30 bg-gradient-to-r from-slate-900/95 via-indigo-950/20 to-slate-900/95 space-y-3">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800/80 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                            <span class="material-symbols-rounded text-xl">assignment_turned_in</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-white">End Semester Exit Survey (Indirect Attainment - 20% Weightage)</h3>
                                <span id="surveyStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">Checking...</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-0.5">Collect student feedback on CO1–CO5 outcomes to calculate indirect attainment for course accreditation.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button id="btnOpenExitSurvey" onclick="initiateExitSurvey()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md cursor-pointer">
                            <span class="material-symbols-rounded text-sm">play_arrow</span> Open Survey
                        </button>
                        <button id="btnCloseExitSurvey" onclick="closeExitSurvey()" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md cursor-pointer hidden">
                            <span class="material-symbols-rounded text-sm">lock</span> Close & Lock
                        </button>
                        <button id="btnCopySurveyLink" onclick="copySurveyLink()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer hidden">
                            <span class="material-symbols-rounded text-sm">content_copy</span> Copy Student Link
                        </button>
                        <a id="btnTestSurveyLink" href="#" target="_blank" class="px-3.5 py-1.5 bg-sky-600/20 hover:bg-sky-600/30 text-sky-300 border border-sky-500/30 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all hidden">
                            <span class="material-symbols-rounded text-sm">open_in_new</span> Test Form
                        </a>
                        <a id="btnPrintSurveyReport" href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all">
                            <span class="material-symbols-rounded text-sm">print</span> Survey Report
                        </a>
                    </div>
                </div>

                <!-- Live Survey Response Stats & URL Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <div class="bg-slate-900/70 p-2.5 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Responses</span>
                        <div class="flex items-center justify-between mt-1">
                            <span id="surveyResponseStat" class="text-sm font-bold text-white font-mono">0 / {{ $totalStudents }} Submitted</span>
                            <span id="surveyResponsePct" class="text-xs text-sky-400 font-mono font-bold">0%</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 mt-1.5 overflow-hidden">
                            <div id="surveyProgressBar" class="bg-sky-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="bg-slate-900/70 p-2.5 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">NBA Attainment Weightage Rule</span>
                        <div class="text-slate-200 mt-1 font-mono text-[11px] flex items-center gap-1.5">
                            <span class="text-emerald-400 font-bold">80% Direct</span> + <span class="text-purple-400 font-bold">20% Indirect</span> = <span class="text-white font-bold">100% Overall</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Direct: 30% CIE + 70% ESE. Indirect: Survey rating average (1 to 3 scale).</p>
                    </div>
                    <div class="bg-slate-900/70 p-2.5 rounded-lg border border-slate-800">
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Survey URL</span>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="text" id="surveyUrlInput" readonly class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-[11px] text-slate-300 font-mono select-all" value="Initiate survey to generate student link">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attainment Table Container -->
            <div class="glass-panel overflow-hidden" id="attainmentContainer">
                <div class="p-6 text-center text-slate-400">
                    <span class="material-symbols-rounded text-3xl animate-spin text-sky-400">progress_activity</span>
                    <div class="mt-2 text-xs">Computing CO Attainment matrix...</div>
                </div>
            </div>
        </div>

        <!-- TAB 4: SBTE Kerala Guidelines -->
        <div id="tab-rubrics" class="tab-content hidden space-y-4">
            <div class="glass-panel p-5 space-y-4">
                <div class="border-b border-slate-800 pb-3">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-rounded text-emerald-400">verified_user</span>
                        SBTE Kerala Diploma Regulation 2021: Major Project Evaluation Scheme
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Official evaluation rules as laid out in Clauses 11.2.5 and 11.3.4 (Total 125 Marks, Ratio 3:2).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- CIA Breakdown -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <span class="font-bold text-sky-400 text-xs uppercase tracking-wide">Continuous Internal Assessment (CIA)</span>
                            <span class="px-2 py-0.5 bg-sky-500/10 border border-sky-500/30 text-sky-300 font-mono font-bold rounded text-xs">75 Marks (60%)</span>
                        </div>
                        <ul class="text-xs text-slate-300 space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-rounded text-emerald-400 text-sm mt-0.5">check_circle</span>
                                <div><strong>Formative Assessment (40%):</strong> 30 Marks. Weekly Activity Report / Student Diary maintained continuously and verified by Project Guide.</div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-rounded text-emerald-400 text-sm mt-0.5">check_circle</span>
                                <div><strong>Summative Assessment (40%):</strong> 30 Marks. Department level presentation & review evaluation by committee.</div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="material-symbols-rounded text-amber-400 text-sm mt-0.5">warning</span>
                                <div><strong>Attendance & Punctuality (20%):</strong> 15 Marks. <em class="text-amber-300">Strictly excluded from CO attainment calculations per Clause 11.2.1.</em></div>
                            </li>
                        </ul>
                    </div>

                    <!-- ESE Breakdown -->
                    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <span class="font-bold text-amber-400 text-xs uppercase tracking-wide">End Semester Examination (ESE)</span>
                            <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-300 font-mono font-bold rounded text-xs">50 Marks (40%)</span>
                        </div>
                        <ul class="text-xs text-slate-300 space-y-1.5">
                            <li class="flex justify-between"><span>1. Working Model / Prototype (20%)</span><span class="font-mono font-bold text-white">10.0 M</span></li>
                            <li class="flex justify-between"><span>2. Use of Modern Tools & Tech (10%)</span><span class="font-mono font-bold text-white">5.0 M</span></li>
                            <li class="flex justify-between"><span>3. Presentation Quality (15%)</span><span class="font-mono font-bold text-white">7.5 M</span></li>
                            <li class="flex justify-between"><span>4. Innovativeness & Originality (5%)</span><span class="font-mono font-bold text-white">2.5 M</span></li>
                            <li class="flex justify-between"><span>5. Viva Voce Examination (15%)</span><span class="font-mono font-bold text-white">7.5 M</span></li>
                            <li class="flex justify-between"><span>6. Individual Contribution (15%)</span><span class="font-mono font-bold text-white">7.5 M</span></li>
                            <li class="flex justify-between"><span>7. Group Activity & Teamwork (10%)</span><span class="font-mono font-bold text-white">5.0 M</span></li>
                            <li class="flex justify-between"><span>8. Project Report Documentation (10%)</span><span class="font-mono font-bold text-white">5.0 M</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL: DELETE GROUP CONFIRMATION -->
    <div id="modalDeleteGroupConfirm" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-[#111a2e] border border-rose-500/40 rounded-xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-5 py-4 bg-[#0f172a] border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                        <span class="material-symbols-rounded text-lg">warning</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Delete Project Group / Batch</h3>
                        <p class="text-[0.68rem] text-slate-400">Confirmation Required</p>
                    </div>
                </div>
                <button type="button" onclick="closeDeleteModal()" class="text-slate-400 hover:text-white p-1">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>

            <div class="p-5 space-y-3">
                <p class="text-xs text-slate-300 leading-relaxed">
                    Are you sure you want to delete <span id="delGroupNameTarget" class="font-bold text-white bg-slate-800 px-2 py-0.5 rounded">Group</span>?
                </p>
                <div id="delGroupMemberWarning" class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-lg text-xs text-amber-300 hidden">
                    <!-- Warning text injected dynamically -->
                </div>
                <div class="text-[0.7rem] text-slate-400 bg-slate-900/50 p-2.5 rounded border border-slate-800 flex items-start gap-2">
                    <span class="material-symbols-rounded text-xs text-sky-400 mt-0.5">info</span>
                    <span>Deleting this card will release any assigned students to become unassigned. Remember to click <strong>"Save All Project Groups"</strong> afterwards to commit changes.</span>
                </div>
            </div>

            <div class="px-5 py-3.5 bg-[#0f172a] border-t border-slate-800 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="button" onclick="confirmDeleteGroup()" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-md shadow-rose-950/40 cursor-pointer">
                    <span class="material-symbols-rounded text-sm">delete_forever</span> Yes, Delete Group
                </button>
            </div>
        </div>
    </div>

    <!-- EVALUATION MODAL -->
    <div id="evalModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-[#111a2e] border border-slate-700 rounded-xl w-full max-w-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="px-5 py-3.5 bg-[#0f172a] border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white" id="modalStudentName">Student Evaluation</h3>
                    <div class="text-xs text-slate-400 font-mono" id="modalStudentReg">REG_NO</div>
                </div>
                <button onclick="closeEvalModal()" class="text-slate-400 hover:text-white p-1">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>

            <form id="evalForm" onsubmit="saveStudentEval(event)" class="p-5 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                <input type="hidden" id="modalRegNo" name="reg_no">

                <!-- Project Info -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Group</label>
                        <input type="text" id="modalGroupId" name="group_id" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" readonly>
                    </div>
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Project Title</label>
                        <input type="text" id="modalProjectTitle" name="project_title" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" placeholder="Title of project">
                    </div>
                </div>

                <!-- CIA Breakdown (75 Marks) -->
                <div class="border border-slate-800 rounded-lg p-3 bg-slate-900/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-sky-400 uppercase">Part A: Continuous Internal Assessment (Max 75M)</span>
                        <span class="text-xs font-mono font-bold text-sky-300" id="liveCiaTotal">0.0 / 75</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-[0.68rem] text-slate-400">Weekly Diary (30M)</label>
                            <input type="number" step="0.1" min="0" max="30" id="modalDiary" name="formative_diary_marks" oninput="computeLiveTotals()" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono" required>
                        </div>
                        <div>
                            <label class="text-[0.68rem] text-slate-400">Dept Review (30M)</label>
                            <input type="number" step="0.1" min="0" max="30" id="modalDept" name="summative_dept_marks" oninput="computeLiveTotals()" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono" required>
                        </div>
                        <div>
                            <label class="text-[0.68rem] text-slate-400">Attendance (15M)</label>
                            <input type="number" step="0.1" min="0" max="15" id="modalAttd" name="attendance_marks" oninput="computeLiveTotals()" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono" required>
                        </div>
                    </div>
                </div>

                <!-- ESE Rubrics (50 Marks) -->
                <div class="border border-slate-800 rounded-lg p-3 bg-slate-900/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-400 uppercase">Part B: End Semester Exam Rubrics (Max 50M)</span>
                        <span class="text-xs font-mono font-bold text-amber-300" id="liveEseTotal">0.0 / 50</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2.5 text-xs">
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Prototype (10M)</label>
                            <input type="number" step="0.1" min="0" max="10" id="mProto" name="ese_prototype" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Modern Tools (5M)</label>
                            <input type="number" step="0.1" min="0" max="5" id="mTools" name="ese_modern_tools" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Presentation (7.5M)</label>
                            <input type="number" step="0.1" min="0" max="7.5" id="mPres" name="ese_presentation" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Innovation (2.5M)</label>
                            <input type="number" step="0.1" min="0" max="2.5" id="mInnov" name="ese_innovativeness" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Viva Voce (7.5M)</label>
                            <input type="number" step="0.1" min="0" max="7.5" id="mViva" name="ese_viva" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Individual (7.5M)</label>
                            <input type="number" step="0.1" min="0" max="7.5" id="mIndiv" name="ese_individual_contrib" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Group Activity (5M)</label>
                            <input type="number" step="0.1" min="0" max="5" id="mGroup" name="ese_group_activity" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.65rem] text-slate-400">Report (5M)</label>
                            <input type="number" step="0.1" min="0" max="5" id="mRep" name="ese_project_report" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                    </div>

                    <!-- Direct ESE / Grade Sync -->
                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-800">
                        <div>
                            <label class="text-[0.68rem] text-slate-400">Direct ESE Mark (50M)</label>
                            <input type="number" step="0.1" min="0" max="50" id="modalEseTotal" name="total_ese_50" oninput="onModalEseDirectInput()" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono">
                        </div>
                        <div>
                            <label class="text-[0.68rem] text-slate-400">Letter Grade</label>
                            <select id="modalEseGrade" name="ese_grade" onchange="onModalGradeSelect()" class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono font-bold">
                                <option value="">-- Auto Calculate --</option>
                                <option value="S">S (&ge;90%)</option>
                                <option value="A">A (80-89%)</option>
                                <option value="B">B (70-79%)</option>
                                <option value="C">C (60-69%)</option>
                                <option value="D">D (50-59%)</option>
                                <option value="E">E (40-49%)</option>
                                <option value="F">F (&lt;40%)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grand Total & Result Bar -->
                <div class="p-3 bg-blue-950/20 border border-blue-900/40 rounded-lg flex items-center justify-between">
                    <div>
                        <div class="text-[0.68rem] text-slate-400 uppercase font-semibold">Grand Total (CIA + ESE)</div>
                        <div class="text-base font-extrabold text-blue-300 font-mono" id="modalGrandTotal">0.0 / 125</div>
                    </div>
                    <div id="modalPassBadge" class="px-3 py-1 rounded text-xs font-bold bg-slate-800 text-slate-400">
                        Pending
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEvalModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-md">
                        <span class="material-symbols-rounded text-base">save</span> Save Evaluation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DUAL ESE ENTRY MODAL -->
    <div id="eseDualModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-[#111a2e] border border-slate-700 rounded-xl w-full max-w-3xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="px-5 py-3.5 bg-[#0f172a] border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white">Dual ESE Marks & Grade Entry (Clause 11.3.4)</h3>
                    <div class="text-xs text-slate-400">Enter raw numeric marks (Max 50M) or SBTE Letter Grade with live two-way synchronization.</div>
                </div>
                <button onclick="closeEseDualModal()" class="text-slate-400 hover:text-white p-1">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>

            <!-- Attainment Threshold Quick Bar -->
            <div class="px-5 py-2.5 bg-slate-900/70 border-b border-slate-800 grid grid-cols-4 gap-3 text-xs">
                <div>
                    <label class="text-[0.65rem] text-slate-400">Target Grade</label>
                    <select id="eseTargetGrade" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-xs text-white">
                        <option value="S">S (&ge;90%)</option>
                        <option value="A">A (&ge;80%)</option>
                        <option value="B">B (&ge;70%)</option>
                        <option value="C">C (&ge;60%)</option>
                        <option value="D" selected>D (&ge;50%)</option>
                        <option value="E">E (&ge;40%)</option>
                    </select>
                </div>
                <div>
                    <label class="text-[0.65rem] text-slate-400">Level 3 % (&ge;)</label>
                    <input type="number" id="eseLvl3" value="70" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-xs text-white font-mono">
                </div>
                <div>
                    <label class="text-[0.65rem] text-slate-400">Level 2 % (&ge;)</label>
                    <input type="number" id="eseLvl2" value="60" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-xs text-white font-mono">
                </div>
                <div>
                    <label class="text-[0.65rem] text-slate-400">Level 1 % (&ge;)</label>
                    <input type="number" id="eseLvl1" value="50" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded text-xs text-white font-mono">
                </div>
            </div>

            <div class="p-4 flex-1 overflow-y-auto custom-scrollbar">
                <table class="table-custom" id="dualEseTable">
                    <thead>
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th>Reg No</th>
                            <th>Student Name</th>
                            <th class="w-32 text-center">Marks (Max 50M)</th>
                            <th class="w-28 text-center">SBTE Grade</th>
                            <th class="w-24 text-center">Attainment</th>
                        </tr>
                    </thead>
                    <tbody id="dualEseTableBody">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 bg-[#0f172a] border-t border-slate-800 flex items-center justify-between">
                <div class="text-xs text-slate-400" id="dualEseStats">
                    Appeared: 0 | Met Target: 0 (0%) | Level: -
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="closeEseDualModal()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold">Cancel</button>
                    <button onclick="saveDualEseMarks()" class="px-5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-base">save</span> Save ESE Marks
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const subjectId = {{ $batchSubject->id }};
        const maxEseMarks = 50.0;
        const maxCiaMarks = 75.0;

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

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            const target = document.getElementById(tabId);
            if (target) target.classList.remove('hidden');

            const btn = document.getElementById('btn-' + tabId);
            if (btn) btn.classList.add('active');

            if (tabId === 'tab-attainment') {
                loadAttainmentData();
            }
        }

        function filterTable() {
            const query = document.getElementById('filterInput').value.toLowerCase();
            const groupFilter = document.getElementById('groupFilter').value;
            const rows = document.querySelectorAll('#mainMarksheetTable tbody tr.student-row');

            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                const group = r.getAttribute('data-group') || '';
                const matchesText = text.includes(query);
                const matchesGroup = !groupFilter || group === groupFilter;
                r.style.display = (matchesText && matchesGroup) ? '' : 'none';
            });
        }

        // Student Eval Modal
        let activeStudent = null;
        function openEvalModal(student) {
            activeStudent = student;
            document.getElementById('modalRegNo').value = student.reg_no;
            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.sbte_reg_no || student.reg_no;
            document.getElementById('modalGroupId').value = student.group_name || '';
            document.getElementById('modalProjectTitle').value = student.project_title || '';

            document.getElementById('modalDiary').value = student.formative_diary_marks || 0;
            document.getElementById('modalDept').value = student.summative_dept_marks || 0;
            document.getElementById('modalAttd').value = student.attendance_marks || student.suggested_att_mark || 0;

            document.getElementById('mProto').value = student.ese_prototype || 0;
            document.getElementById('mTools').value = student.ese_modern_tools || 0;
            document.getElementById('mPres').value = student.ese_presentation || 0;
            document.getElementById('mInnov').value = student.ese_innovativeness || 0;
            document.getElementById('mViva').value = student.ese_viva || 0;
            document.getElementById('mIndiv').value = student.ese_individual_contrib || 0;
            document.getElementById('mGroup').value = student.ese_group_activity || 0;
            document.getElementById('mRep').value = student.ese_project_report || 0;

            document.getElementById('modalEseTotal').value = student.total_ese_50 || 0;
            document.getElementById('modalEseGrade').value = student.ese_grade || '';

            computeLiveTotals();
            document.getElementById('evalModal').classList.remove('hidden');
        }

        function closeEvalModal() {
            document.getElementById('evalModal').classList.add('hidden');
        }

        function computeLiveTotals() {
            const diary = parseFloat(document.getElementById('modalDiary').value) || 0;
            const dept = parseFloat(document.getElementById('modalDept').value) || 0;
            const attd = parseFloat(document.getElementById('modalAttd').value) || 0;
            const ciaTot = Math.min(75.0, diary + dept + attd);
            document.getElementById('liveCiaTotal').innerText = ciaTot.toFixed(1) + ' / 75';

            const proto = parseFloat(document.getElementById('mProto').value) || 0;
            const tools = parseFloat(document.getElementById('mTools').value) || 0;
            const pres = parseFloat(document.getElementById('mPres').value) || 0;
            const innov = parseFloat(document.getElementById('mInnov').value) || 0;
            const viva = parseFloat(document.getElementById('mViva').value) || 0;
            const indiv = parseFloat(document.getElementById('mIndiv').value) || 0;
            const grp = parseFloat(document.getElementById('mGroup').value) || 0;
            const rep = parseFloat(document.getElementById('mRep').value) || 0;

            const rubricSum = Math.min(50.0, proto + tools + pres + innov + viva + indiv + grp + rep);

            let eseTot = parseFloat(document.getElementById('modalEseTotal').value) || 0;
            if (rubricSum > 0) {
                eseTot = rubricSum;
                document.getElementById('modalEseTotal').value = eseTot.toFixed(1);
            }
            document.getElementById('liveEseTotal').innerText = eseTot.toFixed(1) + ' / 50';

            // Auto-grade
            const grade = calcGradeFromMarks(eseTot, 50.0);
            document.getElementById('modalEseGrade').value = grade;

            const grand = ciaTot + eseTot;
            document.getElementById('modalGrandTotal').innerText = grand.toFixed(1) + ' / 125';

            const passBadge = document.getElementById('modalPassBadge');
            const passed = (ciaTot >= 30.0 && eseTot >= 20.0 && grand >= 50.0);
            if (passed) {
                passBadge.className = 'px-3 py-1 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30';
                passBadge.innerText = 'Pass (Eligible)';
            } else {
                passBadge.className = 'px-3 py-1 rounded text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30';
                passBadge.innerText = 'Fail (<40% in CIA/ESE)';
            }
        }

        function onModalEseDirectInput() {
            const m = parseFloat(document.getElementById('modalEseTotal').value) || 0;
            document.getElementById('modalEseGrade').value = calcGradeFromMarks(m, 50.0);
            computeLiveTotals();
        }

        function onModalGradeSelect() {
            const g = document.getElementById('modalEseGrade').value;
            if (g) {
                const m = calcMarksFromGrade(g, 50.0);
                document.getElementById('modalEseTotal').value = m.toFixed(1);
            }
            computeLiveTotals();
        }

        function calcGradeFromMarks(marks, max) {
            const pct = (marks / max) * 100;
            if (pct >= 90) return 'S';
            if (pct >= 80) return 'A';
            if (pct >= 70) return 'B';
            if (pct >= 60) return 'C';
            if (pct >= 50) return 'D';
            if (pct >= 40) return 'E';
            return 'F';
        }

        function calcMarksFromGrade(grade, max) {
            switch(grade.toUpperCase()) {
                case 'S': return max * 0.95;
                case 'A': return max * 0.85;
                case 'B': return max * 0.75;
                case 'C': return max * 0.65;
                case 'D': return max * 0.55;
                case 'E': return max * 0.45;
                default: return 0;
            }
        }

        function saveStudentEval(e) {
            e.preventDefault();
            const form = document.getElementById('evalForm');
            const data = new FormData(form);

            fetch(`/r21/classroom/project/${subjectId}/save-evaluation`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Evaluation saved successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (res.message || 'Failed to save.'));
                }
            })
            .catch(err => {
                alert('Network error while saving evaluation.');
            });
        }

        // Project Groups Handling
        function openGroupModal() {
            switchTab('tab-groups');
        }

        function addGroupRow() {
            const container = document.getElementById('groupsContainer');
            const noNotice = document.getElementById('noGroupsNotice');
            if (noNotice) noNotice.remove();

            const idx = container.querySelectorAll('.group-card').length;
            const card = document.createElement('div');
            card.className = 'glass-panel p-4 group-card';
            card.dataset.idx = idx;
            card.innerHTML = `
                <div class="flex items-center justify-between mb-3 border-b border-slate-800 pb-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-amber-400">folder_special</span>
                        <span class="font-bold text-white text-sm">Group ${idx + 1}</span>
                    </div>
                    <button type="button" onclick="promptDeleteGroup(this)" class="text-rose-400 hover:text-rose-300 p-1 rounded hover:bg-rose-500/10 transition-all cursor-pointer" title="Delete this project group">
                        <span class="material-symbols-rounded text-base">delete</span>
                    </button>
                </div>
                <div class="space-y-2.5">
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Group Name</label>
                        <input type="text" class="grp-name w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" value="Group ${idx + 1}">
                    </div>
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Project Title</label>
                        <input type="text" class="grp-title w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white" placeholder="Enter major project title...">
                    </div>
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Faculty Guide</label>
                        <select class="grp-guide w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded text-xs text-white">
                            <option value="">Select Department Guide...</option>
                            @foreach($guides as $g)
                                <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[0.68rem] text-slate-400 uppercase font-semibold">Select Group Members</label>
                        <div class="max-h-36 overflow-y-auto bg-slate-900 border border-slate-700 rounded p-2 custom-scrollbar space-y-1">
                            @foreach($students as $s)
                                <label class="flex items-center gap-2 text-xs text-slate-300 hover:bg-slate-800 p-1 rounded cursor-pointer">
                                    <input type="checkbox" class="grp-member" value="{{ $s->reg_no }}">
                                    <span>{{ $s->sbte_reg_no }} - {{ $s->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        }

        let targetCardToDelete = null;

        function promptDeleteGroup(btn) {
            targetCardToDelete = btn.closest('.group-card');
            if (!targetCardToDelete) return;

            const name = targetCardToDelete.querySelector('.grp-name')?.value.trim() || 'this group';
            const checkedMembers = targetCardToDelete.querySelectorAll('.grp-member:checked');
            const memberCount = checkedMembers.length;

            const nameTarget = document.getElementById('delGroupNameTarget');
            if (nameTarget) nameTarget.innerText = `"${name}"`;

            const warnBox = document.getElementById('delGroupMemberWarning');
            if (warnBox) {
                if (memberCount > 0) {
                    warnBox.innerHTML = `⚠️ <strong>${memberCount} student(s)</strong> are currently allocated to this group. Deleting it will release them to become unassigned.`;
                    warnBox.classList.remove('hidden');
                } else {
                    warnBox.classList.add('hidden');
                }
            }

            const modal = document.getElementById('modalDeleteGroupConfirm');
            if (modal) modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('modalDeleteGroupConfirm');
            if (modal) modal.classList.add('hidden');
            targetCardToDelete = null;
        }

        function confirmDeleteGroup() {
            if (targetCardToDelete) {
                const name = targetCardToDelete.querySelector('.grp-name')?.value.trim() || 'Group';
                targetCardToDelete.remove();
                targetCardToDelete = null;
                closeDeleteModal();

                // If container is now empty, show notice
                const container = document.getElementById('groupsContainer');
                const remaining = container ? container.querySelectorAll('.group-card').length : 0;
                if (remaining === 0 && container && !document.getElementById('noGroupsNotice')) {
                    container.innerHTML = `
                        <div id="noGroupsNotice" class="col-span-2 text-center py-8 glass-panel text-slate-400">
                            No groups configured yet. Click <strong>"New Project Group"</strong> above to organize your class.
                        </div>
                    `;
                }

                alert(`"${name}" removed from view. Please click "Save All Project Groups" below to permanently commit this change.`);
            }
        }

        function saveProjectGroups() {
            const cards = document.querySelectorAll('.group-card');
            const groups = [];

            cards.forEach((c, i) => {
                const name = c.querySelector('.grp-name').value.trim() || `Group ${i + 1}`;
                const title = c.querySelector('.grp-title').value.trim();
                const guideSelect = c.querySelector('.grp-guide');
                const guideMobile = guideSelect.value;
                const guideName = guideSelect.options[guideSelect.selectedIndex]?.text || '';
                const members = [];
                c.querySelectorAll('.grp-member:checked').forEach(cb => members.push(cb.value));

                groups.push({
                    id: (i + 1).toString(),
                    name: name,
                    title: title,
                    guide_mobile_no: guideMobile,
                    guide_name: guideName,
                    members: members
                });
            });

            fetch(`/r21/classroom/project/${subjectId}/save-groups`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ groups: groups })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Project groups saved successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            });
        }

        // Dual ESE Entry
        let dualEseStudents = [];
        function openEseDualModal() {
            fetch(`/r21/classroom/project/${subjectId}/ese-marks`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'SUCCESS') {
                        dualEseStudents = res.data.students || [];
                        renderDualEseTable();
                        document.getElementById('eseDualModal').classList.remove('hidden');
                    }
                });
        }

        function closeEseDualModal() {
            document.getElementById('eseDualModal').classList.add('hidden');
        }

        function renderDualEseTable() {
            const tbody = document.getElementById('dualEseTableBody');
            tbody.innerHTML = '';

            let appeared = 0;
            let met = 0;
            const targetGrade = document.getElementById('eseTargetGrade').value || 'D';

            dualEseStudents.forEach((st, idx) => {
                const tr = document.createElement('tr');
                const m = st.mark !== null ? st.mark : '';
                const g = st.grade || '';

                if (m !== '' || g !== '') appeared++;
                const isMet = (g && isGradeMet(g, targetGrade));
                if (isMet) met++;

                tr.innerHTML = `
                    <td class="text-center text-slate-400">${idx + 1}</td>
                    <td class="font-mono text-white font-bold">${st.reg_no}</td>
                    <td>${st.name}</td>
                    <td class="text-center">
                        <input type="number" step="0.1" min="0" max="50" class="w-24 px-2 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-mono text-center ese-mark-input" data-reg="${st.reg_no}" value="${m}" oninput="onDualMarkChange('${st.reg_no}')">
                    </td>
                    <td class="text-center">
                        <select class="w-20 px-1 py-1 bg-slate-900 border border-slate-700 rounded text-xs text-white font-bold ese-grade-select" data-reg="${st.reg_no}" onchange="onDualGradeChange('${st.reg_no}')">
                            <option value="">-</option>
                            <option value="S" ${g === 'S' ? 'selected' : ''}>S</option>
                            <option value="A" ${g === 'A' ? 'selected' : ''}>A</option>
                            <option value="B" ${g === 'B' ? 'selected' : ''}>B</option>
                            <option value="C" ${g === 'C' ? 'selected' : ''}>C</option>
                            <option value="D" ${g === 'D' ? 'selected' : ''}>D</option>
                            <option value="E" ${g === 'E' ? 'selected' : ''}>E</option>
                            <option value="F" ${g === 'F' ? 'selected' : ''}>F</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <span class="px-2 py-0.5 rounded text-[0.68rem] font-bold ${isMet ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400'}">
                            ${isMet ? 'Met' : 'Not Met'}
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            const pct = appeared > 0 ? Math.round((met / appeared) * 100) : 0;
            document.getElementById('dualEseStats').innerText = `Appeared: ${appeared} | Met Target (${targetGrade}): ${met} (${pct}%)`;
        }

        function onDualMarkChange(regNo) {
            const markInput = document.querySelector(`.ese-mark-input[data-reg="${regNo}"]`);
            const gradeSelect = document.querySelector(`.ese-grade-select[data-reg="${regNo}"]`);
            const val = parseFloat(markInput.value);
            if (!isNaN(val)) {
                gradeSelect.value = calcGradeFromMarks(val, 50.0);
            }
        }

        function onDualGradeChange(regNo) {
            const markInput = document.querySelector(`.ese-mark-input[data-reg="${regNo}"]`);
            const gradeSelect = document.querySelector(`.ese-grade-select[data-reg="${regNo}"]`);
            const g = gradeSelect.value;
            if (g) {
                markInput.value = calcMarksFromGrade(g, 50.0).toFixed(1);
            }
        }

        function isGradeMet(studentGrade, targetGrade) {
            const rank = { 'S': 6, 'A': 5, 'B': 4, 'C': 3, 'D': 2, 'E': 1, 'F': 0 };
            return (rank[studentGrade] || 0) >= (rank[targetGrade] || 0);
        }

        function saveDualEseMarks() {
            const marks = {};
            const grades = {};
            document.querySelectorAll('.ese-mark-input').forEach(inp => {
                marks[inp.dataset.reg] = inp.value;
            });
            document.querySelectorAll('.ese-grade-select').forEach(sel => {
                grades[sel.dataset.reg] = sel.value;
            });

            const eseConfig = {
                ese_threshold_grade: document.getElementById('eseTargetGrade').value,
                level3_percent: parseFloat(document.getElementById('eseLvl3').value) || 70,
                level2_percent: parseFloat(document.getElementById('eseLvl2').value) || 60,
                level1_percent: parseFloat(document.getElementById('eseLvl1').value) || 50,
                max_marks: 50.0
            };

            fetch(`/r21/classroom/project/${subjectId}/ese-marks/bulk-update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    marks: marks,
                    grades: grades,
                    max_marks: 50.0,
                    ese_config: eseConfig
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Major project ESE marks & board grades updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            });
        }

        // Attainment Matrix loader
        function openAttainmentModal() {
            switchTab('tab-attainment');
        }

        let activeSurveyId = null;
        let activeSurveyUrl = null;

        function updateSurveyUi(survey) {
            if (!survey) return;
            activeSurveyId = survey.id;
            activeSurveyUrl = survey.student_url;

            const badge = document.getElementById('surveyStatusBadge');
            const btnOpen = document.getElementById('btnOpenExitSurvey');
            const btnClose = document.getElementById('btnCloseExitSurvey');
            const btnCopy = document.getElementById('btnCopySurveyLink');
            const btnTest = document.getElementById('btnTestSurveyLink');
            const urlInput = document.getElementById('surveyUrlInput');
            const statText = document.getElementById('surveyResponseStat');
            const pctText = document.getElementById('surveyResponsePct');
            const progBar = document.getElementById('surveyProgressBar');

            const responded = survey.responded_count || 0;
            const total = survey.total_students || 1;
            const pct = Math.round((responded / total) * 100);

            if (statText) statText.innerText = `${responded} / ${total} Submitted`;
            if (pctText) pctText.innerText = `${pct}%`;
            if (progBar) progBar.style.width = `${Math.min(100, pct)}%`;

            if (survey.status === 'Active') {
                if (badge) {
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                    badge.innerText = 'Active (Accepting Responses)';
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
                    badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30';
                    badge.innerText = 'Completed & Locked';
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

        function initiateExitSurvey() {
            if (!confirm('Open End Semester Course Exit Survey for all enrolled Major Project students?')) return;

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
                    loadAttainmentData();
                } else {
                    alert('Notice: ' + res.message);
                    loadAttainmentData();
                }
            })
            .catch(err => {
                alert('Network error while initiating survey.');
            });
        }

        function closeExitSurvey() {
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
                    loadAttainmentData();
                } else {
                    alert('Error: ' + res.message);
                }
            })
            .catch(err => {
                alert('Network error while closing survey.');
            });
        }

        function copySurveyLink() {
            const urlInput = document.getElementById('surveyUrlInput');
            if (!urlInput || !urlInput.value || urlInput.value.indexOf('http') === -1) {
                alert('No active survey URL to copy.');
                return;
            }
            navigator.clipboard.writeText(urlInput.value).then(() => {
                alert('Student survey URL copied to clipboard!\nYou can share this with project students via WhatsApp, SMS, or classroom.');
            }).catch(() => {
                urlInput.select();
                document.execCommand('copy');
                alert('URL copied to clipboard!');
            });
        }

        function loadAttainmentData() {
            const container = document.getElementById('attainmentContainer');
            container.innerHTML = `
                <div class="p-6 text-center text-slate-400">
                    <span class="material-symbols-rounded text-3xl animate-spin text-sky-400">progress_activity</span>
                    <div class="mt-2 text-xs">Computing CO Attainment matrix...</div>
                </div>
            `;

            fetch(`/r21/classroom/project/${subjectId}/attainment-summary`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'SUCCESS') {
                        const d = res.data;
                        if (d.survey) {
                            updateSurveyUi(d.survey);
                        }

                        let rowsHtml = '';
                        (d.matrix || []).forEach(m => {
                            rowsHtml += `
                                <tr>
                                    <td class="font-bold text-sky-400 font-mono">${m.co_tag}</td>
                                    <td class="text-slate-300 text-xs">${m.description}</td>
                                    <td class="text-center font-mono">${m.cie_assessed}</td>
                                    <td class="text-center font-mono">${m.cie_met_pct}%</td>
                                    <td class="text-center font-mono font-bold text-cyan-300">${m.cie_level}</td>
                                    <td class="text-center font-mono font-bold text-amber-300">${m.ese_level}</td>
                                    <td class="text-center font-mono font-bold text-emerald-400">${m.direct_attainment}</td>
                                    <td class="text-center font-mono text-purple-300 font-semibold">${m.indirect_attainment} <span class="text-[10px] text-slate-500 font-normal">(${m.indirect_pct || 0}%)</span></td>
                                    <td class="text-center font-mono font-extrabold text-white bg-blue-950/30">${m.overall_attainment}</td>
                                    <td class="text-center text-xs font-semibold ${m.rating === 'High' ? 'text-emerald-400' : (m.rating === 'Medium' ? 'text-amber-400' : 'text-slate-400')}">${m.rating || 'N/A'}</td>
                                </tr>
                            `;
                        });

                        container.innerHTML = `
                            <div class="p-4 bg-slate-900/50 border-b border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                <div class="glass-panel p-2">
                                    <div class="text-[0.65rem] text-slate-400 uppercase">Average Direct Attainment (80%)</div>
                                    <div class="text-lg font-extrabold text-emerald-400 font-mono">${d.average_direct} <span class="text-xs text-slate-500 font-normal">/ 3.0</span></div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">30% CIE Level + 70% ESE Level</div>
                                </div>
                                <div class="glass-panel p-2">
                                    <div class="text-[0.65rem] text-slate-400 uppercase">Average Indirect Attainment (20%)</div>
                                    <div class="text-lg font-extrabold text-purple-400 font-mono">${d.average_indirect} <span class="text-xs text-slate-500 font-normal">/ 3.0</span></div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">End Semester Student Survey Rating</div>
                                </div>
                                <div class="glass-panel p-2 bg-blue-950/20 border-blue-800/40">
                                    <div class="text-[0.65rem] text-slate-400 uppercase font-bold text-sky-300">Overall Course Attainment (100%)</div>
                                    <div class="text-lg font-extrabold text-white font-mono">${d.average_overall} <span class="text-xs text-slate-400 font-normal">/ 3.0</span></div>
                                    <div class="text-[10px] text-sky-400/80 mt-0.5">80% Direct + 20% Indirect</div>
                                </div>
                            </div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="table-custom">
                                    <thead>
                                        <tr>
                                            <th class="w-16">CO Tag</th>
                                            <th>Course Outcome Description</th>
                                            <th class="text-center">Assessed</th>
                                            <th class="text-center">CIE Met %</th>
                                            <th class="text-center">CIE Level</th>
                                            <th class="text-center">ESE Level</th>
                                            <th class="text-center text-emerald-400">Direct (30:70)</th>
                                            <th class="text-center text-purple-400">Indirect (Survey)</th>
                                            <th class="text-center text-white bg-blue-950/40 font-bold">Overall (80:20)</th>
                                            <th class="text-center">NBA Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${rowsHtml}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }
                });
        }
    </script>
</body>
</html>
