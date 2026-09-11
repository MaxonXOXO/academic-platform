<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Virtual Lab - {{ $batchSubject->subject_name }}</title>

    <!-- Google Fonts & Tailwind CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
            font-size: 0.75rem;
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 0.75rem;
        }

        .slider-accent {
            accent-color: #3b82f6;
        }

        /* High Density Table Optimization */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.72rem;
            line-height: 1.2;
        }

        .table-custom th {
            background-color: #111827;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.03em;
            padding: 0.35rem 0.5rem;
            border-bottom: 1px solid #1f2937;
            white-space: nowrap;
        }

        .table-custom td {
            background-color: rgba(17, 24, 39, 0.4);
            border-bottom: 1px solid rgba(31, 41, 55, 0.6);
            vertical-align: middle;
            padding: 0.3rem 0.5rem;
            font-weight: 500;
        }

        .table-custom tr:hover td {
            background-color: rgba(30, 41, 59, 0.5);
        }

        /* Large touch range sliders for modal */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #1f2937;
            outline: none;
            cursor: pointer;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.6);
            transition: transform 0.15s ease;
        }

        input[type=range]::-webkit-slider-thumb:active {
            transform: scale(1.2);
            background: #60a5fa;
        }
        
        .transition-premium {
            transition: all 0.2s ease-in-out;
        }
</head>
<body class="min-h-screen flex flex-col bg-slate-950 text-slate-100">
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
        $isRev2021Subject = (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21') || empty($batchSubject->syllabus_revision_code));
        $isDemonstratorRev21 = ($role === 'Demonstrator' && $isRev2021Subject);
        $vlBackTitle = $isDemonstratorRev21 ? 'Return to Demonstrator Dashboard' : 'Return to Virtual Lab';
        $vlBackText  = $isDemonstratorRev21 ? 'Return to Dashboard' : 'Return to Virtual Lab';
    @endphp

    <!-- Top Compact Header (Sticky Top) -->
    <header class="glass-panel px-4 py-2.5 flex items-center justify-between shadow-xl sticky top-0 z-50 bg-slate-950/95 backdrop-blur-md border-b border-slate-800 rounded-none">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-graduation-cap text-sky-400 text-base"></i>
                <span class="font-extrabold text-white text-sm tracking-tight">Carmel Linx</span>
                <span class="text-slate-600 font-bold">|</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 text-[10px] font-extrabold tracking-wider rounded-md bg-sky-500/15 text-sky-300 border border-sky-500/30 shadow-sm">
                        VIRTUAL LAB ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }})
                    </span>
                    <span class="text-[11px] text-cyan-400 font-mono font-bold">{{ $batchSubject->subject_code }}</span>
                </div>
                <h1 class="text-base font-bold text-white tracking-tight mt-0.5">{{ $batchSubject->subject_name }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="toggleFullscreen()" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs rounded-lg font-medium border border-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-expand text-[11px]"></i> <span class="hidden sm:inline">Fullscreen</span>
            </button>

            <a href="/classroom/practical/{{ $batchSubject->id }}/experiments/print" target="_blank" class="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/30 hover:bg-emerald-500/20 text-emerald-400 text-xs rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm" title="Print Completed Practical Experiments & Sessions Log">
                <i class="fa-solid fa-clipboard-check text-[11px]"></i> <span class="hidden sm:inline">Exps Log</span>
            </a>

            <a href="/classroom/practical/{{ $batchSubject->id }}/series-report/print" target="_blank" class="px-3 py-1.5 bg-purple-500/10 border border-purple-500/30 hover:bg-purple-500/20 text-purple-400 text-xs rounded-lg font-medium transition flex items-center gap-1.5" title="Print Practical Series Examination Marksheet (15M)">
                <i class="fa-solid fa-pen-to-square text-[11px]"></i> <span class="hidden sm:inline">Series (15M)</span>
            </a>

            <a href="/classroom/practical/{{ $batchSubject->id }}/report/print" target="_blank" class="px-3 py-1.5 bg-sky-500/10 border border-sky-500/30 hover:bg-sky-500/20 text-sky-400 text-xs rounded-lg font-medium transition flex items-center gap-1.5" title="Print Continuous Internal Assessment (CIA 75M) Evaluation Register">
                <i class="fa-solid fa-print text-[11px]"></i> <span class="hidden sm:inline">CIA (75M)</span>
            </a>

            <a href="/classroom/practical/{{ $batchSubject->id }}/final-results/print" target="_blank" class="px-3 py-1.5 bg-indigo-500/10 border border-indigo-500/30 hover:bg-indigo-500/20 text-indigo-400 text-xs rounded-lg font-medium transition flex items-center gap-1.5" title="Print End-Semester Exam & Consolidated Final Results (125M)">
                <i class="fa-solid fa-graduation-cap text-[11px]"></i> <span class="hidden sm:inline">Final ESE (125M)</span>
            </a>

            <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shadow-amber-500/20" title="{{ $vlBackTitle }}">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>{{ $vlBackText }}</span>
            </a>
        </div>
    </header>

    <style>
        .drawing-hall-tab-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            overflow-x: auto;
            padding: 8px 12px;
            background: #090d16;
            border: 1px solid rgba(51, 65, 85, 0.7);
        }
        .drawing-hall-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            background: transparent;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .drawing-hall-tab-btn:hover {
            color: #f1f5f9;
            background: rgba(30, 41, 59, 0.6);
        }
        .drawing-hall-tab-btn.active {
            color: #ffffff !important;
            background: #0f172a !important;
            border: 1.5px solid #38bdf8 !important;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.25) !important;
            font-weight: 700 !important;
        }
    </style>

    <!-- Top Navigation & Batch Filter Bar (Exact Drawing Hall 2026 Style) -->
    <div class="glass-panel mx-2 mt-2 p-2 flex flex-wrap items-center justify-between gap-2 z-30">
        <div class="flex items-center gap-2 shrink-0">
            <!-- Return Button directly inside the Tab Bar container -->
            <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shadow-amber-500/20 shrink-0" title="{{ $vlBackTitle }}">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>{{ $vlBackText }}</span>
            </a>
            <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" class="px-3 py-1.5 rounded-xl bg-emerald-600/25 hover:bg-emerald-600/40 border border-emerald-500/40 text-emerald-300 hover:text-white font-bold text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shrink-0" title="Open Class Attendance & Log for this subject">
                <i class="fa-solid fa-clipboard-user text-xs text-emerald-400"></i>
                <span>Class Attendance &amp; Log</span>
            </a>
        </div>

        <!-- Horizontal Tabs (Drawing Hall 2026 Style) -->
        <div class="drawing-hall-tab-bar rounded-xl flex-1">
            <button onclick="switchTab('table22')" id="btn-table22" class="drawing-hall-tab-btn tab-btn active">
                <i class="fa-solid fa-pen-ruler text-sky-400"></i>
                <span>Continuous Eval (Lab Work 37.5M)</span>
            </button>

            <button onclick="switchTab('table23')" id="btn-table23" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-lightbulb text-amber-400"></i>
                <span>Open-Ended (7.5M)</span>
            </button>

            <button onclick="switchTab('table31')" id="btn-table31" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-pen-to-square text-blue-400"></i>
                <span>Practical Tests (15M)</span>
            </button>

            <button onclick="switchTab('summary')" id="btn-summary" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-chart-pie text-purple-400"></i>
                <span>Consolidated CIA &amp; Reports (75M)</span>
            </button>
        </div>

        <!-- Inline Lab Batch Filters -->
        <div class="flex items-center gap-1 text-xs flex-wrap">
            <span class="text-[10px] uppercase font-bold text-slate-500 me-1 hidden md:inline">Batch:</span>
            <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-blue-500 text-blue-400 font-medium text-[11px] transition">
                All
            </button>
            <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Unassigned
            </button>
            <button onclick="filterLabBatch('1')" id="batch-filter-1" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Batch 1 (<span id="batch1BtnCount">{{ $labBatchConfig['b1_count'] ?? 0 }}</span>)
            </button>
            <button onclick="filterLabBatch('2')" id="batch-filter-2" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Batch 2 (<span id="batch2BtnCount">{{ $labBatchConfig['b2_count'] ?? 0 }}</span>)
            </button>
            <button type="button" onclick="openLabBatchSetupModal('{{ $batchSubject->id }}')" class="ms-1.5 px-2.5 py-1 rounded-md bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 font-semibold text-[11px] transition flex items-center gap-1.5 cursor-pointer shadow" title="Configure Lab Batch Division (Full vs Split & Cutoff Roll No)">
                <span class="material-symbols-rounded text-xs">tune</span>
                <span>Batch split setup</span>
            </button>
        </div>
    </div>

    <!-- Main Workspace Workspace -->
    <main class="flex-grow p-2 transition-premium relative z-30">

        <!-- TAB 1: Continuous Lab Work Evaluation (37.5 Marks) -->
        <div id="tab-table22" class="tab-content glass-panel p-4">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-vials text-blue-400 text-xs"></i> Continuous Lab Work Evaluation (37.5 Marks)
                    </h2>
                    <p class="text-[11px] text-slate-400">Day-to-day continuous evaluation across 5 rubrics (Max 37.5 Marks).</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" class="px-2.5 py-1 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow no-underline" title="Open Class Attendance & Log for this subject">
                        <i class="fa-solid fa-clipboard-user text-emerald-400 text-xs"></i>
                        <span>Class Attendance &amp; Log</span>
                    </a>
                    <button type="button" onclick="openCompletedExperimentsModal()" class="px-2.5 py-1 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                        <span>Completed: <strong id="headerCompletedExpsCount">{{ $conductedCount ?? 0 }}</strong>/{{ $totalExperiments ?? 0 }}</span>
                    </button>
                    <button type="button" onclick="openManageExperimentsModal(event)" class="px-2.5 py-1 bg-teal-600/20 hover:bg-teal-600/30 border border-teal-500/40 text-teal-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow">
                        <i class="fa-solid fa-gear text-teal-400 text-[10px]"></i> Manage Exps
                    </button>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Exp:</span>
                        <input type="text" id="exp_no" value="Exp 1" class="px-2 py-1 bg-slate-900 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-blue-500 font-mono w-20">
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Title:</span>
                        <input type="text" id="exp_title" placeholder="e.g. Ohm's Law Verification" class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-blue-500 w-44 sm:w-60">
                    </div>
                    <button onclick="submitExpMarks()" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white font-medium text-xs rounded-lg shadow transition flex items-center gap-1">
                        <i class="fa-solid fa-floppy-disk text-[10px]"></i> Save
                    </button>
                </div>
            </div>

            <!-- Student High-Density Table -->
            <div class="mt-3 overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center w-12">Roll</th>
                            <th class="w-28">Register No</th>
                            <th>Student Name</th>
                            <th class="text-center w-28">Lab Batch Split</th>
                            <th class="text-center w-24">Score (/37.5)</th>
                            <th class="text-center w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $rawB = $labBatches->get($student->reg_no)->lab_batch ?? '';
                            $batchDesignation = in_array($rawB, ['1', 'Batch 1', 'Batch A']) ? '1' : (in_array($rawB, ['2', 'Batch 2', 'Batch B']) ? '2' : 'Unassigned');
                            $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td class="text-center text-cyan-400 font-mono text-xs">{{ $student->roll_no ?? ($index + 1) }}</td>
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}
                                </span>
                            </td>
                            <td>
                                <button type="button" onclick="openStudentDetailModal('{{ $student->reg_no }}')"
                                    class="text-white text-xs font-semibold hover:text-sky-400 transition-colors hover:underline underline-offset-2 text-left cursor-pointer bg-transparent border-0 p-0 flex items-center gap-1.5 group">
                                    <span>{{ $student->name }}</span>
                                    <i class="fa-solid fa-up-right-from-square text-[9px] text-slate-500 group-hover:text-sky-400 opacity-60 transition"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="bg-slate-950 border border-slate-800 text-[11px] text-slate-300 rounded px-1.5 py-0.5 focus:outline-none focus:border-blue-500">
                                    <option value="" {{ $batchDesignation === 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                                    <option value="1" {{ $batchDesignation === '1' ? 'selected' : '' }}>Batch 1</option>
                                    <option value="2" {{ $batchDesignation === '2' ? 'selected' : '' }}>Batch 2</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-blue-400">
                                    {{ $expLog ? floatval($expLog->total_score_50) : '0' }} / 37.5
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table22')" class="px-2.5 py-1 bg-blue-600/20 border border-blue-500/30 hover:bg-blue-600/30 text-blue-400 text-[11px] font-medium rounded transition flex items-center justify-center gap-1 mx-auto">
                                    <i class="fa-solid fa-sliders text-[10px]"></i> Grade
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: Open-Ended Experiment / Micro-Project (7.5 Marks) -->
        <div id="tab-table23" class="tab-content glass-panel p-4 hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-400 text-xs"></i> Open-Ended Project (7.5 Marks)
                    </h2>
                    <p class="text-[11px] text-slate-400">Problem solving &amp; micro-project evaluation (Max 7.5 Marks).</p>
                </div>

                <button onclick="submitOpenEndedMarks()" class="px-3 py-1 bg-amber-600 hover:bg-amber-500 text-white font-medium text-xs rounded-lg shadow transition flex items-center gap-1">
                    <i class="fa-solid fa-floppy-disk text-[10px]"></i> Save Log
                </button>
            </div>

            <!-- Student High-Density Table -->
            <div class="mt-3 overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center w-12">Roll</th>
                            <th class="w-28">Register No</th>
                            <th>Student Name</th>
                            <th class="w-56">Project Title</th>
                            <th class="text-center w-24">Score (/7.5)</th>
                            <th class="text-center w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                            $openLog = $openEndedLogs->get($student->reg_no);
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td class="text-center text-amber-400 font-mono text-xs">{{ $student->roll_no ?? ($index + 1) }}</td>
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}
                                </span>
                            </td>
                            <td>
                                <button type="button" onclick="openStudentDetailModal('{{ $student->reg_no }}')"
                                    class="text-white text-xs font-semibold hover:text-sky-400 transition-colors hover:underline underline-offset-2 text-left cursor-pointer bg-transparent border-0 p-0 flex items-center gap-1.5 group">
                                    <span>{{ $student->name }}</span>
                                    <i class="fa-solid fa-up-right-from-square text-[9px] text-slate-500 group-hover:text-sky-400 opacity-60 transition"></i>
                                </button>
                            </td>
                            <td>
                                <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-2 py-0.5 bg-slate-950 border border-slate-800 rounded text-[11px] text-white focus:outline-none focus:border-amber-500 w-full">
                            </td>
                            <td class="text-center">
                                <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-amber-400">
                                    {{ $openLog ? floatval($openLog->total_score_50) : '0' }} / 7.5
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table23')" class="px-2.5 py-1 bg-amber-500/20 border border-amber-500/30 hover:bg-amber-500/30 text-amber-400 text-[11px] font-medium rounded transition flex items-center justify-center gap-1 mx-auto">
                                    <i class="fa-solid fa-sliders text-[10px]"></i> Grade
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: Practical Series Examination (15 Marks) -->
        <div id="tab-table31" class="tab-content glass-panel p-4 hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-purple-400 text-xs"></i> Practical Series Tests (15 Marks)
                    </h2>
                    <p class="text-[11px] text-slate-400">Practical series tests (Test 1 [15M] &amp; Test 2 [15M] -> Average 15 Marks).</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="openManageTestsModal(event)" class="px-2.5 py-1 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/40 text-blue-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow">
                        <i class="fa-solid fa-sliders text-blue-400 text-[10px]"></i> Configure Tests
                    </button>
                    <select id="series_no" onchange="switchSeriesExam(this.value)" class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-purple-500 font-medium">
                        <option value="Series 1">Series Exam 1</option>
                        <option value="Series 2">Series Exam 2</option>
                    </select>
                    <button onclick="submitSeriesMarks()" class="px-3 py-1 bg-purple-600 hover:bg-purple-500 text-white font-medium text-xs rounded-lg shadow transition flex items-center gap-1">
                        <i class="fa-solid fa-floppy-disk text-[10px]"></i> Save
                    </button>
                </div>
            </div>

            <!-- Student High-Density Table -->
            <div class="mt-3 overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center w-12">Roll</th>
                            <th class="w-28">Register No</th>
                            <th>Student Name</th>
                            <th class="text-center w-28">Active Score (/40)</th>
                            <th class="text-center w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                            $series1Log = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                            $series2Log = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td class="text-center text-purple-400 font-mono text-xs">{{ $student->roll_no ?? ($index + 1) }}</td>
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}
                                </span>
                            </td>
                            <td>
                                <button type="button" onclick="openStudentDetailModal('{{ $student->reg_no }}')"
                                    class="text-white text-xs font-semibold hover:text-sky-400 transition-colors hover:underline underline-offset-2 text-left cursor-pointer bg-transparent border-0 p-0 flex items-center gap-1.5 group">
                                    <span>{{ $student->name }}</span>
                                    <i class="fa-solid fa-up-right-from-square text-[9px] text-slate-500 group-hover:text-sky-400 opacity-60 transition"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <span id="score-text-series-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-purple-400"
                                      data-s1="{{ $series1Log ? floatval($series1Log->total_score_40) : '0' }}"
                                      data-s2="{{ $series2Log ? floatval($series2Log->total_score_40) : '0' }}">
                                    {{ $series1Log ? floatval($series1Log->total_score_40) : '0' }} / 15
                                </span>
                            </td>
                            <td class="text-center">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table31')" class="px-2.5 py-1 bg-purple-600/20 border border-purple-500/30 hover:bg-purple-600/30 text-purple-400 text-[11px] font-medium rounded transition flex items-center justify-center gap-1 mx-auto">
                                    <i class="fa-solid fa-sliders text-[10px]"></i> Grade
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: Consolidated CIA Summary (75 Marks) -->
        <div id="tab-summary" class="tab-content glass-panel p-4 hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-award text-sky-400 text-xs"></i> Lab CIA Consolidated Summary Sheet
                    </h2>
                    <p class="text-[11px] text-slate-400">Real-time consolidated assessment — click any student name to view per-experiment grades and attendance log.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="openCompletedExperimentsModal()" class="px-2.5 py-1 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                        <span>Completed Experiments: <strong id="summaryCompletedExpsCount">{{ $conductedCount ?? 0 }}</strong>/{{ $totalExperiments ?? 0 }}</span>
                    </button>
                    <a href="/classroom/practical/{{ $batchSubject->id }}/series-report/print" target="_blank" class="px-2.5 py-1 bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/40 text-purple-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 shadow">
                        <i class="fa-solid fa-pen-to-square text-xs"></i> <span>Series (15M)</span>
                    </a>
                    <a href="/classroom/practical/{{ $batchSubject->id }}/report/print" target="_blank" class="px-2.5 py-1 bg-sky-600/20 hover:bg-sky-600/30 border border-sky-500/40 text-sky-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 shadow">
                        <i class="fa-solid fa-print text-xs"></i> <span>CIA Register (75M)</span>
                    </a>
                    <a href="/classroom/practical/{{ $batchSubject->id }}/final-results/print" target="_blank" class="px-2.5 py-1 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 font-semibold text-xs rounded-lg transition flex items-center gap-1.5 shadow">
                        <i class="fa-solid fa-graduation-cap text-xs"></i> <span>Final Results (125M)</span>
                    </a>
                </div>
            </div>

            <!-- Student High-Density Table -->
            <div class="mt-3 overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="w-28">Register No</th>
                            <th>Student Name</th>
                            <th class="text-center w-24">Batch</th>
                            <th class="text-center w-28">Exps Graded</th>
                            <th class="text-center w-28">Lab Work (37.5M)</th>
                            <th class="text-center w-28">Tests (15M)</th>
                            <th class="text-center w-28">Open Ended (7.5M)</th>
                            <th class="text-center w-24">Attendance (15M)</th>
                            <th class="text-center w-28">Total CIA (75M)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $score = $consolidatedScores[$student->reg_no] ?? [];
                            $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                            $graded = $gradedCount[$student->reg_no] ?? 0;
                            $totalExp = (isset($conductedCount) && $conductedCount > 0) ? $conductedCount : ($totalExperiments ?? 0);
                            $gradedColor = $graded === 0 ? 'text-red-400' : ($graded < $totalExp ? 'text-amber-400' : 'text-emerald-400');
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}
                                </span>
                            </td>
                            <td>
                                <button type="button" onclick="openStudentDetailModal('{{ $student->reg_no }}')"
                                    class="text-white text-xs font-semibold hover:text-sky-400 transition-colors hover:underline underline-offset-2 text-left cursor-pointer bg-transparent border-0 p-0 flex items-center gap-1.5 group">
                                    <span>{{ $student->name }}</span>
                                    <i class="fa-solid fa-up-right-from-square text-[9px] text-slate-500 group-hover:text-sky-400 opacity-60 transition"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $batchDesignation == 'Batch A' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : ($batchDesignation == 'Batch B' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-900 text-slate-400') }}">
                                    {{ $batchDesignation }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-mono text-xs font-bold {{ $gradedColor }}" id="graded-count-{{ $student->reg_no }}" title="{{ $graded }} Attended / {{ $totalExp }} Conducted ({{ $totalExperiments }} in syllabus)">{{ $graded }} / {{ $totalExp }}</span>
                            </td>
                            <td class="text-center font-mono text-blue-400 text-xs" id="cia-lab-work-{{ $student->reg_no }}">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-purple-400 text-xs" id="cia-series-{{ $student->reg_no }}">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-amber-400 text-xs" id="cia-open-{{ $student->reg_no }}">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-sky-400 text-xs" id="cia-att-{{ $student->reg_no }}">{{ $attendanceMarks[$student->reg_no]['mark'] ?? 0 }}</td>
                            <td class="text-center font-mono font-bold text-xs text-cyan-300" id="cia-total-{{ $student->reg_no }}">{{ $score['total_cia_75'] ?? $score['total_cia_60'] ?? '0.00' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- STUDENT DETAIL MODAL — Experiment breakdown + CIA + Attendance Log -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div id="studentDetailModal" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-[60] hidden items-start justify-center p-4 overflow-y-auto">
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl w-full max-w-5xl shadow-2xl my-4">

            <!-- Modal Header -->
            <div class="px-5 py-3.5 bg-slate-950/70 border-b border-slate-800 flex items-center justify-between rounded-t-2xl">
                <div>
                    <h3 id="detailModalStudentName" class="text-sm font-black text-white">Student Name</h3>
                    <span id="detailModalStudentReg" class="text-xs font-mono text-cyan-400 font-semibold">Reg No</span>
                    <span id="detailModalGradedBadge" class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded bg-amber-500/15 text-amber-400 border border-amber-500/30"></span>
                </div>
                <button onclick="closeStudentDetailModal()" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition flex items-center justify-center text-xs cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-4 space-y-4">

                <!-- Experiment Marks Table -->
                <div>
                    <h4 class="text-[11px] uppercase font-bold text-slate-400 mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-vials text-blue-400"></i> Lab Work — Experiment Marks
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-slate-800">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-950/80 text-slate-400 uppercase text-[10px] font-bold">
                                    <th class="p-2 w-12 text-center">Exp</th>
                                    <th class="p-2">Title</th>
                                    <th class="p-2 text-center w-16">Rough<br><span class="text-[9px] normal-case">/5</span></th>
                                    <th class="p-2 text-center w-16">Fair<br><span class="text-[9px] normal-case">/7.5</span></th>
                                    <th class="p-2 text-center w-16">Obs<br><span class="text-[9px] normal-case">/7.5</span></th>
                                    <th class="p-2 text-center w-16">Proc<br><span class="text-[9px] normal-case">/7.5</span></th>
                                    <th class="p-2 text-center w-16">Viva<br><span class="text-[9px] normal-case">/10</span></th>
                                    <th class="p-2 text-center w-20">Total<br><span class="text-[9px] normal-case">/37.5</span></th>
                                    <th class="p-2 text-center w-20">Action</th>
                                </tr>
                            </thead>
                            <tbody id="detailExpTableBody" class="divide-y divide-slate-800/60">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CIA Summary & Evaluation (Editable) -->
                <div class="border border-slate-800 rounded-xl p-3.5 bg-slate-950/60 shadow-inner">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2.5 pb-2 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-award text-amber-400"></i> Continuous Internal Assessment (CIA Summary)
                            </h4>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="detailCiaSaveMsg" class="text-[11px] font-semibold text-emerald-400 transition-opacity opacity-0"></span>
                            <button type="button" onclick="saveStudentCiaSummaryDesktop()" class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-lg shadow transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-floppy-disk text-[11px]"></i> Save CIA Summary
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                        <!-- 1. Open-Ended -->
                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-amber-400 font-bold text-xs">1. Open-Ended</span>
                                <span class="text-[10px] text-slate-400 font-mono">Max 7.5M</span>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-0.5">Score (/7.5)</label>
                                <input type="number" step="0.5" min="0" max="7.5" id="detailInputOpenEnded" oninput="recalcDesktopModalCIA()" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded-lg text-amber-400 font-mono font-bold text-xs focus:outline-none focus:border-amber-500 text-center">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-0.5">Project Topic</label>
                                <input type="text" id="detailInputOpenTopic" placeholder="Project Topic/Title..." class="w-full px-2 py-1 bg-slate-950 border border-slate-800 rounded-lg text-slate-300 text-[11px] focus:outline-none focus:border-amber-500">
                            </div>
                        </div>

                        <!-- 2. Summative Tests -->
                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-purple-400 font-bold text-xs">2. Lab Tests</span>
                                <span class="text-[10px] text-purple-300 font-mono font-bold" id="detailCalcTestScaled">0.00 / 15M</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-slate-400 block mb-0.5">Test 1 (/40)</label>
                                    <input type="number" step="0.5" min="0" max="40" id="detailInputTest1" oninput="recalcDesktopModalCIA()" class="w-full px-1.5 py-1 bg-slate-950 border border-slate-700 rounded-lg text-purple-300 font-mono font-bold text-xs focus:outline-none focus:border-purple-500 text-center">
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-400 block mb-0.5">Test 2 (/40)</label>
                                    <input type="number" step="0.5" min="0" max="40" id="detailInputTest2" oninput="recalcDesktopModalCIA()" class="w-full px-1.5 py-1 bg-slate-950 border border-slate-700 rounded-lg text-purple-300 font-mono font-bold text-xs focus:outline-none focus:border-purple-500 text-center">
                                </div>
                            </div>
                            <div class="text-[11px] text-slate-400 font-mono text-center pt-0.5">
                                Avg: <span id="detailCalcTestAvg" class="text-purple-300 font-bold">0.00</span> / 40
                            </div>
                        </div>

                        <!-- 3. Attendance -->
                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <span class="text-sky-400 font-bold text-xs">3. Attendance</span>
                                <span class="text-[10px] text-sky-300 font-mono" id="detailAttSuggested">Sugg: 0</span>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-0.5">Mark (/15)</label>
                                <input type="number" step="0.5" min="0" max="15" id="detailInputAttMark" oninput="recalcDesktopModalCIA()" class="w-full px-2 py-1 bg-slate-950 border border-slate-700 rounded-lg text-sky-400 font-mono font-bold text-xs focus:outline-none focus:border-sky-500 text-center">
                            </div>
                            <div class="text-[11px] text-slate-400 font-mono text-center pt-1" id="detailAttStats">
                                0% (0/0 sessions)
                            </div>
                        </div>

                        <!-- 4. Total CIA -->
                        <div class="bg-gradient-to-br from-sky-950/40 to-slate-900 border border-sky-500/40 rounded-xl p-3 flex flex-col justify-between">
                            <div>
                                <span class="text-cyan-400 font-bold text-xs block">4. Total CIA (/75)</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    Lab (<span id="detailLabWorkAvg" class="font-mono text-blue-400 font-bold">0.00</span>) + OE + Tests + Att
                                </span>
                            </div>
                            <div class="py-2 text-center">
                                <span class="font-mono font-black text-cyan-300 text-2xl tracking-tight" id="detailTotalCIA">0.00</span>
                                <span class="text-slate-500 text-xs font-mono"> / 75</span>
                            </div>
                            <div class="text-[10px] text-center text-slate-400 font-medium">
                                Real-time dynamic total
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Log (collapsible) -->
                <div class="border border-slate-800 rounded-xl overflow-hidden">
                    <button onclick="toggleAttLog()" class="w-full px-4 py-2.5 flex items-center justify-between bg-slate-950/50 hover:bg-slate-950/80 transition text-left cursor-pointer">
                        <span class="text-[11px] font-bold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-emerald-400 text-xs"></i>
                            Attendance Log
                            <span id="attLogSummary" class="text-slate-500 font-normal"></span>
                        </span>
                        <i id="attLogChevron" class="fa-solid fa-chevron-down text-slate-500 text-xs transition-transform"></i>
                    </button>
                    <div id="attLogBody" class="hidden overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-950/80 text-slate-400 text-[10px] font-bold uppercase">
                                    <th class="p-2 w-28">Date</th>
                                    <th class="p-2 w-16 text-center">Period</th>
                                    <th class="p-2">Topic / Experiment</th>
                                    <th class="p-2 w-20 text-center">Sub-batch</th>
                                    <th class="p-2 w-24 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="attLogTableBody" class="divide-y divide-slate-800/60">
                                <tr><td colspan="5" class="p-4 text-center text-slate-500">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3 bg-slate-950/40 border-t border-slate-800 flex items-center justify-between rounded-b-2xl">
                <button onclick="navigateDetailModal(-1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> Prev Student
                </button>
                <span class="text-[10px] text-slate-500 font-mono" id="detailModalPosition"></span>
                <button onclick="navigateDetailModal(1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition flex items-center gap-1">
                    Next Student <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- COMPACT OVERLAY GRADING MODAL -->
    <div id="gradingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col justify-end sm:justify-center p-3">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-4 shadow-2xl space-y-3 mx-auto">
            
            <div class="flex justify-between items-center border-b border-slate-800 pb-2">
                <div>
                    <h3 id="modalStudentName" class="font-bold text-white text-sm">Student Name</h3>
                    <span id="modalStudentReg" class="text-xs font-mono text-cyan-400 font-medium">Reg No</span>
                </div>
                <button onclick="closeGradingModal()" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition flex items-center justify-center text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Rubrics Input Container -->
            <div id="modalSlidersContainer" class="space-y-3 max-h-[55vh] overflow-y-auto pr-1">
                <!-- Dynamically populated via JS -->
            </div>

            <!-- Stepper bottom actions -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-800 gap-2">
                <button onclick="navigateStudent(-1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-medium rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> Prev
                </button>

                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Total</span>
                    <span id="modalTotalScore" class="font-mono text-sm font-bold text-blue-400">0.00</span>
                </div>

                <button onclick="navigateStudent(1)" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-medium rounded-lg transition flex items-center gap-1">
                    Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- COMPLETED EXPERIMENTS DETAILS MODAL -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div id="completedExperimentsModal" onclick="if(event.target === this) closeCompletedExperimentsModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-2 sm:p-3">
        <div id="completedExperimentsModalDialog" class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-[98vw] xl:max-w-[1700px] h-[95vh] max-h-[95vh] flex flex-col overflow-hidden shadow-2xl transition-all">
            <!-- Modal Header -->
            <div class="px-5 py-3.5 bg-slate-950/90 border-b border-slate-800 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-teal-400 shrink-0">
                        <i class="fa-solid fa-flask-vial text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm sm:text-base font-black text-white leading-tight">Completed Practical Experiments &amp; Sessions</h3>
                            <span class="px-2 py-0.5 rounded bg-teal-500/20 border border-teal-500/30 text-teal-300 text-[10px] font-bold uppercase tracking-wider hidden sm:inline">Workspace</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5 leading-tight" id="completedExpsModalSubtitle">{{ $batchSubject->subject_name }} &bull; Normalized Timetable Continuous Sessions</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a id="btnPrintCompletedExps" href="/classroom/practical/{{ $batchSubject->id }}/experiments/print" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow shadow-blue-500/20">
                        <i class="fa-solid fa-print text-xs"></i>
                        <span>Print Report</span>
                    </a>
                    <button type="button" onclick="toggleCompletedExperimentsFullscreen()" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold rounded-lg border border-slate-700/80 transition flex items-center gap-1.5 cursor-pointer" title="Toggle Fullscreen">
                        <span class="material-symbols-rounded text-base" id="completedExpsFullscreenIcon">fullscreen</span>
                        <span class="hidden sm:inline">Fullscreen</span>
                    </button>
                    <button type="button" onclick="closeCompletedExperimentsModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-200 hover:text-white text-xs font-bold rounded-lg border border-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-sm" title="Close">
                        <span class="material-symbols-rounded text-sm">close</span>
                        <span>Close</span>
                    </button>
                </div>
            </div>

            <!-- KPI Overview Cards & Table -->
            <div class="p-4 sm:p-5 overflow-y-auto space-y-4 flex-grow custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    <div class="p-3.5 bg-slate-950/70 border border-slate-800/80 rounded-xl flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Syllabus Experiments</span>
                            <span class="text-2xl font-mono font-bold text-white mt-1 block" id="kpiTotalSyllabusExps">{{ $totalExperiments ?? 0 }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-center text-slate-300">
                            <span class="material-symbols-rounded text-xl">menu_book</span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-slate-950/70 border border-slate-800/80 rounded-xl flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Sessions Conducted</span>
                            <span class="text-2xl font-mono font-bold text-indigo-300 mt-1 block" id="kpiCompletedExpsCount">{{ $conductedCount ?? 0 }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <span class="material-symbols-rounded text-xl">task_alt</span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-slate-950/70 border border-slate-800/80 rounded-xl flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Actual Lab Hours</span>
                            <span class="text-2xl font-mono font-bold text-white mt-1 block" id="kpiActualLabHours">{{ $actualLabHours ?? (($conductedCount ?? 0) * 3) }} hrs</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-800/70 border border-slate-700/60 flex items-center justify-center text-slate-300">
                            <span class="material-symbols-rounded text-xl">schedule</span>
                        </div>
                    </div>
                    <div class="p-3.5 bg-slate-950/70 border border-slate-800/80 rounded-xl flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Syllabus Coverage</span>
                            <span class="text-2xl font-mono font-bold text-emerald-400 mt-1 block" id="kpiCoveragePercent">{{ ($totalExperiments ?? 0) > 0 ? round((($conductedCount ?? 0) / ($totalExperiments ?? 0)) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <span class="material-symbols-rounded text-xl">verified</span>
                        </div>
                    </div>
                </div>

                <!-- Completed Experiments Table -->
                <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl overflow-hidden shadow-inner">
                    <div class="px-4 py-3 bg-slate-900/90 border-b border-slate-800/80 flex flex-wrap justify-between items-center gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-clipboard-check text-sm text-teal-400"></i> Completed Experiments Log Details
                            </span>
                            <span class="text-[11px] font-mono text-slate-400" id="completedExpsTableCounter">Showing {{ count($conductedDetails ?? []) }} completed session records</span>
                        </div>
                        <!-- Cohort Filter Tabs -->
                        <div class="inline-flex rounded-lg p-0.5 bg-slate-950 border border-slate-800 text-[11px]">
                            <button type="button" onclick="filterCompletedExpsCohort('all')" id="cohortTab_all" class="px-2.5 py-1 rounded-md font-bold transition bg-indigo-600 text-white cursor-pointer">All</button>
                            <button type="button" onclick="filterCompletedExpsCohort('1')" id="cohortTab_1" class="px-2.5 py-1 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 1</button>
                            <button type="button" onclick="filterCompletedExpsCohort('2')" id="cohortTab_2" class="px-2.5 py-1 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 2</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold uppercase text-[10px] bg-slate-900/60 whitespace-nowrap">
                                    <th class="p-2.5 w-10 text-center">#</th>
                                    <th class="p-2.5 w-20 text-center">Exp No</th>
                                    <th class="p-2.5">Title / Topics Covered</th>
                                    <th class="p-2.5 text-center w-28">Date</th>
                                    <th class="p-2.5 text-center w-28">Hours (Periods)</th>
                                    <th class="p-2.5 text-center w-24">Batch</th>
                                    <th class="p-2.5 text-center w-28">Attendance (%)</th>
                                    <th class="p-2.5 text-center w-20">Absent</th>
                                    <th class="p-2.5 text-center w-40">Absent Roll Nos</th>
                                </tr>
                            </thead>
                            <tbody id="completedExperimentsTableBody" class="divide-y divide-slate-800/40 text-xs">
                                @php $currentCompExpBatch = null; @endphp
                                @forelse($conductedDetails ?? [] as $idx => $item)
                                @php
                                    $rawDate = $item['date'] ?? '';
                                    $d = $rawDate ?: '—';
                                    if (!empty($rawDate) && str_contains($rawDate, '-')) {
                                        $parts = explode('-', $rawDate);
                                        if (count($parts) === 3 && strlen($parts[0]) === 4) {
                                            $d = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                                        }
                                    }
                                    $sb = (string)($item['sub_batch'] ?? 'Whole');
                                    $batchName = $item['batch'] ?? 'Whole Class';
                                    $bColor = ($sb === '1')
                                        ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/25'
                                        : (($sb === '2') ? 'bg-purple-500/10 text-purple-300 border border-purple-500/25' : 'bg-slate-800 text-slate-300 border border-slate-700/80');
                                    $abCount = $item['absent_count'] ?? max(0, ($item['total_count'] ?? 0) - ($item['present_count'] ?? 0));
                                    $abRolls = $item['absent_roll_nos'] ?? '-';
                                @endphp

                                @if($currentCompExpBatch !== $batchName)
                                    @php $currentCompExpBatch = $batchName; @endphp
                                    <tr class="batch-section-banner bg-slate-950 border-y border-slate-800" data-batch-row="{{ $sb }}">
                                        <td colspan="9" class="py-2.5 px-4 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full {{ $sb === '1' ? 'bg-indigo-400' : 'bg-purple-400' }}"></span>
                                                <span class="{{ $sb === '1' ? 'text-indigo-300' : 'text-purple-300' }} font-bold uppercase tracking-wider text-xs">{{ $batchName }}</span>
                                                <span class="text-slate-500 text-[11px] font-normal">• Practical Sessions &amp; Conducted Log Records</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                <tr class="border-b border-slate-800/50 hover:bg-slate-800/30 transition text-xs" data-batch-row="{{ $sb }}">
                                    <td class="p-3 text-center font-mono text-slate-400 whitespace-nowrap">{{ $idx + 1 }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-200 whitespace-nowrap">{{ $item['experiment_no'] ?? ('Exp ' . ($idx + 1)) }}</td>
                                    <td class="p-3 text-slate-200">
                                        <div class="inline-flex items-center gap-2 max-w-full">
                                            <span class="font-medium text-slate-100">{{ $item['title'] ?? '' }}</span>
                                            @if(!empty($item['co_tag']))
                                                <span class="px-1.5 py-0.5 bg-slate-800/90 border border-slate-700/80 rounded text-[10px] font-mono text-slate-400 shrink-0">{{ $item['co_tag'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center gap-1.5 text-slate-300 font-mono text-xs">
                                            <span class="material-symbols-rounded text-slate-500 text-sm">calendar_today</span>
                                            <span>{{ $d }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <span class="px-2 py-0.5 bg-slate-800/70 border border-slate-700/60 rounded text-slate-300 font-mono text-[11px]">{{ $item['hours_text'] ?? '3 hrs (Lab)' }}</span>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold whitespace-nowrap inline-block {{ $bColor }}">{{ $batchName }}</span>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                            <span class="font-mono font-bold text-slate-100 text-xs">{{ isset($item['present_count']) ? ($item['present_count'] . '/' . ($item['total_count'] ?? count($students))) : 'Conducted' }}</span>
                                            @if(!empty($item['attendance_pct']))
                                                <span class="px-1.5 py-0.5 rounded {{ (float)$item['attendance_pct'] >= 75 ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-300 border border-amber-500/20' }} font-mono text-[11px] font-semibold">{{ $item['attendance_pct'] }}%</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if($abCount > 0)
                                            <span class="px-2 py-0.5 rounded bg-rose-500/10 border border-rose-500/20 text-rose-400 font-mono font-bold text-xs">{{ $abCount }}</span>
                                        @else
                                            <span class="text-slate-500 font-mono text-xs">0</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if(!empty($abRolls) && $abRolls !== 'None' && $abRolls !== '-')
                                            <span class="px-2.5 py-0.5 rounded bg-rose-500/10 border border-rose-500/25 text-rose-300 font-mono font-semibold text-xs tracking-wide">{{ $abRolls }}</span>
                                        @else
                                            <span class="text-slate-500 text-xs font-normal">None</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="p-6 text-center text-slate-500 font-bold">No completed experiments recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-2.5 bg-slate-950/90 border-t border-slate-800 flex justify-between items-center shrink-0">
                <span class="text-xs text-slate-500 font-mono hidden sm:inline">Press ESC or click outside to dismiss</span>
                <button type="button" onclick="closeCompletedExperimentsModal()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-200 hover:text-white text-xs font-bold rounded-lg border border-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-sm ml-auto">
                    <span class="material-symbols-rounded text-sm">close</span>
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Experiment Date Modal -->
    <div id="editExpDateModal" onclick="if(event.target === this) closeEditExpDateModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden justify-center items-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl flex flex-col">
            <!-- Modal Header -->
            <div class="px-5 py-4 bg-slate-950/70 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-teal-400 text-xl">edit_calendar</span>
                    <h3 class="text-sm font-bold text-white">Edit Experiment Date</h3>
                </div>
                <button onclick="closeEditExpDateModal()" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-4">
                <div id="editModalFeedback" class="hidden py-2 px-3 rounded-xl text-xs font-bold border"></div>
                <!-- Exp Info -->
                <div class="p-3 bg-slate-950/50 border border-slate-800/80 rounded-xl space-y-2.5">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1 bg-slate-900 border border-slate-700/80 rounded-lg px-2 py-1 shadow-inner">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Exp No:</span>
                            <input type="text" id="editModalExpNoInput" class="w-12 bg-transparent text-xs font-mono font-bold text-teal-300 outline-none text-center" title="Edit Experiment Number (e.g. 1, 2, 2A)">
                        </div>
                        <span id="editModalExpTitle" class="text-xs font-semibold text-slate-200 line-clamp-1 flex-1">Experiment Title</span>
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center justify-between pt-1 border-t border-slate-800/60">
                        <span>Currently Recorded Date:</span>
                        <span id="editModalCurrentDate" class="font-mono text-amber-300 font-bold">—</span>
                    </div>
                </div>

                <!-- New Date Input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-rounded text-sm text-teal-400">calendar_month</span> New Conducted Date
                    </label>
                    <input type="date" id="editModalNewDateInput" class="w-full px-3 py-2 bg-slate-950 border border-slate-700/80 rounded-xl text-white font-mono text-sm [color-scheme:dark] focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>

                <!-- Update Scope -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-300">Application Scope</label>
                    <div class="grid grid-cols-1 gap-2">
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-950/80 cursor-pointer transition">
                            <input type="radio" name="editExpDateScope" value="universal" checked class="mt-0.5 text-teal-500 focus:ring-teal-500">
                            <div>
                                <span class="text-xs font-bold text-white block">Universal (All Batches &amp; Master Syllabus)</span>
                                <span class="text-[10px] text-slate-400 block">Fix this experiment's date across all batches, syllabus master, and evaluations.</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-950/80 cursor-pointer transition">
                            <input type="radio" name="editExpDateScope" value="batch" class="mt-0.5 text-teal-500 focus:ring-teal-500">
                            <div>
                                <span class="text-xs font-bold text-white block" id="editModalBatchLabel">This Batch Only</span>
                                <span class="text-[10px] text-slate-400 block">Update evaluation logs and student attendance specifically for this lab batch.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Attendance Sync Checkbox -->
                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="editModalSyncAttendance" checked class="rounded border-slate-700 text-teal-500 focus:ring-teal-500">
                        <span class="text-xs text-slate-300">Synchronize student lab attendance records to this new date</span>
                    </label>
                    <p class="text-[10px] text-slate-500 ml-5 mt-0.5">Moves class log continuous sessions (3 hrs) &amp; student attendance from old date to new date.</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3.5 bg-slate-950/70 border-t border-slate-800 flex justify-end items-center gap-2.5">
                <button type="button" onclick="closeEditExpDateModal()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" id="btnConfirmEditExpDate" onclick="submitEditExpDate()" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold rounded-lg transition flex items-center gap-1.5 cursor-pointer shadow-sm shadow-teal-500/20">
                    <span class="material-symbols-rounded text-sm">check</span>
                    <span>Save &amp; Update</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Manage Experiments Modal -->
    <div id="manageExperimentsModal" onclick="if(event.target === this) closeManageExperimentsModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
            <!-- Modal Header (Fixed/Stable) -->
            <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-base font-black text-white">Experiments List</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Setup the experiments syllabus for day-to-day continuous evaluation.</p>
                </div>
                <button onclick="closeManageExperimentsModal()" class="text-slate-400 hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Add Experiment Form (Fixed/Stable at top, Single-row on desktop) -->
            <div class="p-5 bg-slate-900/40 border-b border-slate-800/80 shrink-0">
                <form onsubmit="savePracticalExperiment(event)" class="bg-slate-950/40 border border-slate-800/60 p-3.5 rounded-xl">
                    <input type="hidden" id="expEditId">
                    <!-- All fields in one row on desktop -->
                    <div class="flex flex-col md:flex-row items-end gap-3">
                        <div class="w-full md:w-20 shrink-0">
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Exp No.</label>
                            <input type="text" id="expFormNo" required maxlength="2" pattern="[0-9]{1,2}" inputmode="numeric" placeholder="01" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-2 text-sm font-bold text-slate-200 focus:border-blue-500 outline-none text-center font-mono">
                        </div>
                        <div class="w-full flex-1 min-w-0">
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Experiment Title / Objective</label>
                            <input type="text" id="expFormTitle" required placeholder="Enter experiment title or detailed objective..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 focus:border-blue-500 outline-none">
                        </div>
                        <div class="w-full md:w-28 shrink-0">
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Map CO</label>
                            <select id="expFormCo" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 focus:border-blue-500 outline-none cursor-pointer">
                                <option value="CO1">CO1</option>
                                <option value="CO2">CO2</option>
                                <option value="CO3">CO3</option>
                                <option value="CO4">CO4</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto shrink-0 flex items-center gap-2">
                            <button type="submit" id="btnSaveExp" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap shadow shadow-blue-600/20">
                                <i class="fa-solid fa-plus text-xs" id="btnSaveExpIcon"></i>
                                <span id="btnSaveExpLabel">Add Experiment</span>
                                <span id="btnSaveExpSpinner" class="hidden w-3 h-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </button>
                            <button type="button" id="btnCancelExpEdit" onclick="cancelExperimentEdit()" class="hidden px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg text-xs font-bold transition cursor-pointer whitespace-nowrap">
                                Cancel
                            </button>
                            <button type="button" id="btnImportDatabank" onclick="importFromDatabank()" class="hidden px-3.5 py-2 bg-amber-600/10 hover:bg-amber-600 border border-amber-500/20 hover:border-amber-500 text-amber-400 hover:text-white rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer whitespace-nowrap">
                                <i class="fa-solid fa-database text-xs"></i> Import
                            </button>
                        </div>
                    </div>
                    <!-- Inline feedback banner -->
                    <div id="expSaveFeedback" class="hidden mt-2.5 px-3 py-1.5 rounded-lg text-xs font-bold border"></div>
                </form>
            </div>

            <!-- Experiments List Table (Dedicated Scroll Container) -->
            <div id="manageExpsTableScrollContainer" class="p-6 flex-1 overflow-y-auto min-h-0">
                <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-950/20 shadow-inner">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase z-10 shadow-sm">
                            <tr>
                                <th class="p-3 w-16 text-center">No.</th>
                                <th class="p-3">Title / Objective</th>
                                <th class="p-3 w-20 text-center">CO</th>
                                <th class="p-3 w-28 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="manageExpsTableBody" class="divide-y divide-slate-850">
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-500">No experiments set up yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Tests Modal -->
    <div id="manageTestsModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-xl md:max-w-4xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
            <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-black text-white">Configure Model Tests Questions</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Design the question paper scheme for Test 1 and Test 2.</p>
                </div>
                <button onclick="closeManageTestsModal()" class="text-slate-400 hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form onsubmit="savePracticalTestQuestions(event)" class="flex-grow flex flex-col overflow-hidden">
                <div class="p-6 overflow-y-auto space-y-5 flex-grow">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Select Model Test</label>
                        <select id="designTestName" onchange="renderTestQuestionsFields()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
                            <option value="Test 1">Model Test 1 (CO1 &amp; CO2)</option>
                            <option value="Test 2">Model Test 2 (CO3 &amp; CO4)</option>
                        </select>
                    </div>

                    <div id="testQuestionsFieldsContainer" class="space-y-4">
                        <!-- Inputs generated dynamically -->
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-950/60 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-md">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Save Test Config
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JS Logic -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const batchSubjectId = "{{ $batchSubject->id }}";
        
        // Memory logs cache for calculations
        const studentList = @json($students);
        const labBatches = @json($labBatches);
        const experimentLogs = @json($experimentLogs);
        const openEndedLogs = @json($openEndedLogs);
        const seriesExamLogs = @json($seriesExamLogs);
        const attendanceMarks = @json($attendanceMarks);
        const consolidatedScores = @json($consolidatedScores);

        // Per-student experiment detail data (for Student Detail Modal)
        const studentExpDetail = @json($studentExpDetail);
        const gradedCounts     = @json($gradedCount);
        const experimentsList  = @json($experiments);    // full experiment list
        const totalExpCount    = {{ $totalExperiments ?? 0 }};

        // Active state variables
        let activeTab = 'table22';
        let activeBatchFilter = 'All';
        let currentStudentIndex = 0;

        // In-memory model of changes before saving
        const scoresState = {
            table22: {},
            table23: {},
            table31: {}
        };

        // Populate in-memory state with existing data
        studentList.forEach(s => {
            const reg = s.reg_no;

            // Load Exp 1 standard
            const expLog = experimentLogs['Exp 1'] ? experimentLogs['Exp 1'].find(x => x.reg_no === reg) : null;
            scoresState.table22[reg] = expLog ? {
                c1: Math.min(5, parseFloat(expLog.prep_punctuality || 0)),
                c2: Math.min(7.5, parseFloat(expLog.setup_procedure || 0)),
                c3: Math.min(7.5, parseFloat(expLog.observation_recording || 0)),
                c4: Math.min(7.5, parseFloat(expLog.analysis_interpretation || 0)),
                c5: Math.min(10, parseFloat(expLog.viva_voce || 0)),
                c6: 0
            } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

            // Load Open-ended
            const openLog = openEndedLogs[reg];
            scoresState.table23[reg] = openLog ? {
                c1: parseFloat(openLog.originality_relevance),
                c2: parseFloat(openLog.objectives_plan),
                c3: parseFloat(openLog.execution_recording),
                c4: parseFloat(openLog.analysis_presentation),
                c5: parseFloat(openLog.teamwork_innovation)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0 };

            // Load Series standard (Series 1 active by default)
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

        // Tab selection logic
        function switchTab(tabId) {
            activeTab = tabId;
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabId);
            if (btn) btn.classList.add('active');
        }

        // Enable standard HTML5 Fullscreen mode
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error("Fullscreen request failed: ", err);
                });
            } else {
                document.exitFullscreen();
            }
        }

        // Filter student grid dynamically by Lab Batch designations
        function filterLabBatch(batch) {
            activeBatchFilter = batch;
            document.querySelectorAll('.batch-filter-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-400');
                btn.classList.add('border-slate-800', 'text-slate-400');
            });

            const activeBtnId = (batch === 'All') ? 'All' : (batch === 'Unassigned' ? 'Unassigned' : (batch === '1' || batch === 'Batch 1' || batch === 'Batch A' ? '1' : '2'));
            const targetBtn = document.getElementById(`batch-filter-${activeBtnId}`);
            if (targetBtn) {
                targetBtn.classList.remove('border-slate-800', 'text-slate-400');
                targetBtn.classList.add('border-blue-500', 'text-blue-400');
            }

            document.querySelectorAll('.student-row').forEach(row => {
                const sbRaw = row.getAttribute('data-batch') || 'Unassigned';
                const sb = (sbRaw === '1' || sbRaw === 'Batch 1' || sbRaw === 'Batch A') ? '1' : ((sbRaw === '2' || sbRaw === 'Batch 2' || sbRaw === 'Batch B') ? '2' : 'Unassigned');

                if (batch === 'All') {
                    row.classList.remove('hidden');
                } else if (batch === 'Unassigned' && sb === 'Unassigned') {
                    row.classList.remove('hidden');
                } else if (batch === '1' && sb === '1') {
                    row.classList.remove('hidden');
                } else if (batch === '2' && sb === '2') {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // Assign a student to Batch 1/2 via API
        async function updateLabBatch(regNo, value) {
            try {
                const res = await fetch(`/classroom/practical/${batchSubjectId}/lab-batch`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, lab_batch: value })
                });
                const data = await res.json();
                if (data.success) {
                    // Update frontend state & re-apply filtering
                    document.querySelectorAll(`.student-row[data-reg-no="${regNo}"]`).forEach(row => {
                        row.setAttribute('data-batch', value || 'Unassigned');
                    });
                    const allRows = document.querySelectorAll('.student-row');
                    let b1 = 0, b2 = 0;
                    allRows.forEach(r => {
                        const b = r.getAttribute('data-batch');
                        if (b === '1') b1++;
                        else if (b === '2') b2++;
                    });
                    const b1El = document.getElementById('batch1BtnCount');
                    const b2El = document.getElementById('batch2BtnCount');
                    if (b1El) b1El.innerText = b1;
                    if (b2El) b2El.innerText = b2;

                    filterLabBatch(activeBatchFilter);
                } else {
                    alert(data.message);
                }
                console.error(e);
                alert("Failed to update lab batch split.");
            }
        }

        // Dynamic stepper/slider adjustments inside modal
        function stepSlider(sliderId, step) {
            const input = document.getElementById(sliderId);
            if (!input) return;
            let val = parseFloat(input.value) + step;
            val = Math.max(parseFloat(input.min), Math.min(parseFloat(input.max), val));
            input.value = val;
            input.dispatchEvent(new Event('input'));
        }

        // Switch displayed values in Series tab when exam dropdown changes
        function switchSeriesExam(seriesName) {
            studentList.forEach(s => {
                const reg = s.reg_no;
                const scoreObj = scoresState.table31[reg][seriesName] || { c1:0, c2:0, c3:0, c4:0, c5:0 };
                const total = scoreObj.c1 + scoreObj.c2 + scoreObj.c3 + scoreObj.c4 + scoreObj.c5;
                const scoreText = document.getElementById(`score-text-series-${reg}`);
                if (scoreText) {
                    scoreText.innerText = `${total.toFixed(2)} / 15`;
                }
            });
        }

        // Open Overlay Grading Modal for individual student evaluation
        function openGradingModal(regNo, tabType) {
            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            currentStudentIndex = studentList.findIndex(s => s.reg_no === regNo);

            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = (student.sbte_reg_no && student.sbte_reg_no.trim() !== '') ? student.sbte_reg_no : student.reg_no;

            // Generate HTML range sliders based on active tab rubrics
            const container = document.getElementById('modalSlidersContainer');
            container.innerHTML = '';

            let rubrics = [];
            if (tabType === 'table22') {
                rubrics = [
                    { label: '1. Rough Record (Max 5)',            key: 'c1', max: 5,    step: 0.5 },
                    { label: '2. Fair Record (Max 7.5)',           key: 'c2', max: 7.5,  step: 0.5 },
                    { label: '3. Observation & Recording (Max 7.5)', key: 'c3', max: 7.5,  step: 0.5 },
                    { label: '4. Procedure & Punctuality (Max 7.5)', key: 'c4', max: 7.5,  step: 0.5 },
                    { label: '5. Viva Voce (Max 10)',              key: 'c5', max: 10,   step: 0.5 },
                ];
            } else if (tabType === 'table23') {
                rubrics = [
                    { label: 'Open-Ended Evaluation (Max 7.5)', key: 'c1', max: 7.5, step: 0.5 }
                ];
            } else if (tabType === 'table31') {
                rubrics = [
                    { label: '1. Procedure & Write-up (Max 5)', key: 'c1', max: 5, step: 0.5 },
                    { label: '2. Setup & Execution (Max 5)', key: 'c2', max: 5, step: 0.5 },
                    { label: '3. Viva & Result (Max 5)', key: 'c3', max: 5, step: 0.5 }
                ];
            }

            // Get standard scores for student
            const studentScores = tabType === 'table31' 
                ? (scoresState[tabType][regNo][document.getElementById('series_no').value] || {})
                : (scoresState[tabType][regNo] || {});

            rubrics.forEach(r => {
                const currentVal = studentScores[r.key] || 0;
                
                const rubricHtml = `
                    <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800">
                        <div class="flex justify-between font-medium text-xs mb-1">
                            <span class="text-slate-300 text-[11px]">${r.label}</span>
                            <span class="text-blue-400 font-mono text-xs font-bold" id="modal-val-${r.key}">${currentVal}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="stepSlider('slider-${r.key}', -${r.step})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold text-xs flex items-center justify-center">-</button>
                            <input type="range" id="slider-${r.key}" min="0" max="${r.max}" step="${r.step}" value="${currentVal}" oninput="syncModalSlider('${regNo}', '${r.key}', '${tabType}')" class="flex-1 slider-accent">
                            <button type="button" onclick="stepSlider('slider-${r.key}', ${r.step})" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold text-xs flex items-center justify-center">+</button>
                        </div>
                    </div>
                `;
                container.innerHTML += rubricHtml;
            });

            updateModalTotal(regNo, tabType);

            document.getElementById('gradingModal').classList.remove('hidden');
            document.getElementById('gradingModal').classList.add('flex');
        }

        // Sync slider values with in-memory scoresState and recalculate
        function syncModalSlider(regNo, key, tabType) {
            const val = parseFloat(document.getElementById(`slider-${key}`).value) || 0;
            document.getElementById(`modal-val-${key}`).innerText = val;

            if (tabType === 'table31') {
                const activeSeries = document.getElementById('series_no').value;
                scoresState[tabType][regNo][activeSeries][key] = val;
            } else {
                scoresState[tabType][regNo][key] = val;
            }

            updateModalTotal(regNo, tabType);
        }

        // Calculate and update the modal total live display
        function updateModalTotal(regNo, tabType) {
            let total = 0;
            if (tabType === 'table31') {
                const activeSeries = document.getElementById('series_no').value;
                const scores = scoresState[tabType][regNo][activeSeries] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0);
                const textEl = document.getElementById(`score-text-series-${regNo}`);
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 15`;
            } else if (tabType === 'table23') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0);
                const textEl = document.getElementById(`score-text-open-${regNo}`);
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 7.5`;
            } else if (tabType === 'table22') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0);
                const textEl = document.getElementById(`score-text-exp-${regNo}`);
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 37.5`;
            }

            document.getElementById('modalTotalScore').innerText = total.toFixed(2);
            
            // Recalculate consolidated sheet values locally
            recalculateCIA(regNo);
        }

        // Walk through student list using "Prev" & "Next" stepper buttons
        function navigateStudent(direction) {
            let newIndex = currentStudentIndex + direction;
            
            // Loop navigation bounds
            if (newIndex >= studentList.length) newIndex = 0;
            if (newIndex < 0) newIndex = studentList.length - 1;

            const student = studentList[newIndex];
            const activeRow = document.querySelector(`.student-row[data-reg-no="${student.reg_no}"]`);
            
            // Check if matching student is currently filtered/visible
            if (activeRow && activeRow.classList.contains('hidden')) {
                // Skip to next recursively
                currentStudentIndex = newIndex;
                navigateStudent(direction);
                return;
            }

            closeGradingModal();
            openGradingModal(student.reg_no, activeTab);
        }

        function closeGradingModal() {
            document.getElementById('gradingModal').classList.add('hidden');
            document.getElementById('gradingModal').classList.remove('flex');
        }

        // Live client-side recalculation of consolidated CIA sheet
        function recalculateCIA(regNo) {
            // 1. Lab Work (37.5M) — 5 components: Rough(5)+Fair(7.5)+Obs(7.5)+Proc(7.5)+Viva(10) = 37.5 raw = 37.5 CIA marks
            const expScores = scoresState.table22[regNo] || {};
            const expTotal = (expScores.c1||0) + (expScores.c2||0) + (expScores.c3||0) + (expScores.c4||0) + (expScores.c5||0);
            const scaledLabWork375 = expTotal; // raw max 37.5 = CIA 37.5M directly, no scaling needed
            const labEl = document.getElementById(`cia-lab-work-${regNo}`);
            if (labEl) labEl.innerText = scaledLabWork375.toFixed(2);

            // 2. Open Ended (10 M)
            const openScores = scoresState.table23[regNo] || {};
            const openTotal = (openScores.c1||0) + (openScores.c2||0) + (openScores.c3||0) + (openScores.c4||0) + (openScores.c5||0);
            const scaledOpen10 = (openTotal / 50) * 10;
            const openEl = document.getElementById(`cia-open-${regNo}`);
            if (openEl) openEl.innerText = scaledOpen10.toFixed(2);

            // 3. Series (15 M)
            const s1Scores = scoresState.table31[regNo]['Series 1'] || {};
            const s2Scores = scoresState.table31[regNo]['Series 2'] || {};
            const s1Total = (s1Scores.c1||0) + (s1Scores.c2||0) + (s1Scores.c3||0) + (s1Scores.c4||0) + (s1Scores.c5||0);
            const s2Total = (s2Scores.c1||0) + (s2Scores.c2||0) + (s2Scores.c3||0) + (s2Scores.c4||0) + (s2Scores.c5||0);
            const avgSeries40 = (s1Total + s2Total) / 2;
            const scaledSeries15 = (avgSeries40 / 40) * 15;
            const seriesEl = document.getElementById(`cia-series-${regNo}`);
            if (seriesEl) seriesEl.innerText = scaledSeries15.toFixed(2);

            // 4. Attendance
            const att = (attendanceMarks[regNo] && attendanceMarks[regNo].mark !== undefined) ? parseFloat(attendanceMarks[regNo].mark) : 0;

            // CIA Total out of 75 (37.5 Lab + 7.5 Open-Ended + 15 Series + 15 Attendance)
            const totalCIA = scaledLabWork375 + scaledOpen10 + scaledSeries15 + att;
            const totalEl = document.getElementById(`cia-total-${regNo}`);
            if (totalEl) totalEl.innerText = totalCIA.toFixed(2);
        }

        // AJAX submit wrappers
        async function submitExpMarks() {
            const expNo = document.getElementById('exp_no').value;
            const title = document.getElementById('exp_title').value;
            const marks = scoresState.table22;

            try {
                const res = await fetch(`/classroom/practical/${batchSubjectId}/experiment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiment_no: expNo, title: title, marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Saved successfully!');
            } catch(e) {
                alert('Failed to save experiment marks.');
            }
        }

        async function submitOpenEndedMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                const titleInput = document.getElementById(`open-title-${reg}`);
                marks[reg] = {
                    title: titleInput ? titleInput.value : 'Open-ended Project',
                    ...scoresState.table23[reg]
                };
            });

            try {
                const res = await fetch(`/classroom/practical/${batchSubjectId}/open-ended`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Saved successfully!');
            } catch(e) {
                alert('Failed to save open ended project marks.');
            }
        }

        async function submitSeriesMarks() {
            const seriesNo = document.getElementById('series_no').value;
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = scoresState.table31[reg][seriesNo] || { c1:0, c2:0, c3:0, c4:0, c5:0 };
            });

            try {
                const res = await fetch(`/classroom/practical/${batchSubjectId}/series-exam`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ series_no: seriesNo, marks: marks })
                });
                const data = await res.json();
                alert(data.message || 'Saved successfully!');
            } catch(e) {
                alert('Failed to save series exam marks.');
            }
        }

        let currentSubjectId = "{{ $batchSubject->id }}";
        let labExperimentsData = @json($experiments ?? []);
        let labTestsData = @json($tests ?? []);
        window.conductedExpsDetails = @json($conductedDetails ?? []);
        window.actualLabHoursConducted = {{ $actualLabHours ?? (($conductedCount ?? 0) * 3) }};
        window.conductedExpsCount = {{ $conductedCount ?? 0 }};
        window.totalSyllabusExps = {{ $totalExperiments ?? 0 }};

        function updateSyllabusCounters(totalCount) {
            window.totalSyllabusExps = totalCount;
            const kpiTotal = document.getElementById('kpiTotalSyllabusExps');
            if (kpiTotal) kpiTotal.innerText = totalCount;
            const doneCount = (window.conductedExpsCount !== undefined) ? window.conductedExpsCount : 0;
            const pct = totalCount > 0 ? Math.round((doneCount / totalCount) * 100) : 0;
            const kpiPct = document.getElementById('kpiCoveragePercent');
            if (kpiPct) kpiPct.innerText = `${pct}%`;
            const hdrCount = document.getElementById('headerCompletedExpsCount');
            if (hdrCount) {
                const parent = hdrCount.closest('button');
                if (parent) {
                    const span = parent.querySelector('span');
                    if (span) span.innerHTML = `Completed: <strong id="headerCompletedExpsCount">${doneCount}</strong>/${totalCount}`;
                }
            }
            const sumCount = document.getElementById('summaryCompletedExpsCount');
            if (sumCount) {
                const parent = sumCount.closest('button');
                if (parent) {
                    const span = parent.querySelector('span');
                    if (span) span.innerHTML = `Completed Experiments: <strong id="summaryCompletedExpsCount">${doneCount}</strong>/${totalCount}`;
                }
            }
        }

        async function fetchPracticalEvaluationsData() {
            if (!currentSubjectId) return;
            try {
                const res = await fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`);
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    labExperimentsData = data.experiments || [];
                    labTestsData = data.tests || [];
                    if (data.conducted_experiments_details) {
                        window.conductedExpsDetails = data.conducted_experiments_details;
                    }
                    if (data.actual_hours_conducted !== undefined) {
                        window.actualLabHoursConducted = data.actual_hours_conducted;
                    }
                    if (data.conducted_experiments_count !== undefined) {
                        window.conductedExpsCount = data.conducted_experiments_count;
                        const hdrCount = document.getElementById('headerCompletedExpsCount');
                        if (hdrCount) hdrCount.innerText = data.conducted_experiments_count;
                        const sumCount = document.getElementById('summaryCompletedExpsCount');
                        if (sumCount) sumCount.innerText = data.conducted_experiments_count;
                    }
                }
            } catch(err) {
                console.error("Error fetching practical evaluations:", err);
            }
        }
        document.addEventListener('DOMContentLoaded', fetchPracticalEvaluationsData);

        function openCompletedExperimentsModal() {
            const modal = document.getElementById('completedExperimentsModal');
            if (!modal) return;

            const list = window.conductedExpsDetails || [];
            const totalSyllabus = window.totalSyllabusExps || {{ $totalExperiments ?? 0 }};
            const doneCount = (window.conductedExpsCount !== undefined) ? window.conductedExpsCount : list.length;
            const hoursCount = (window.actualLabHoursConducted !== undefined && window.actualLabHoursConducted > 0)
                ? window.actualLabHoursConducted
                : (doneCount * 3);
            const pct = totalSyllabus > 0 ? Math.round((doneCount / totalSyllabus) * 100) : 0;

            const kpiTotal = document.getElementById('kpiTotalSyllabusExps');
            if (kpiTotal) kpiTotal.innerText = totalSyllabus;
            const kpiDone = document.getElementById('kpiCompletedExpsCount');
            if (kpiDone) kpiDone.innerText = doneCount;
            const kpiHours = document.getElementById('kpiActualLabHours');
            if (kpiHours) kpiHours.innerText = `${hoursCount} hrs`;
            const kpiPct = document.getElementById('kpiCoveragePercent');
            if (kpiPct) kpiPct.innerText = `${pct}%`;

            const counter = document.getElementById('completedExpsTableCounter');
            if (counter) counter.innerText = `Showing ${list.length} completed session records`;

            renderCompletedExperimentsTable(list);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        window.openCompletedExperimentsModal = openCompletedExperimentsModal;

        function toggleCompletedExperimentsFullscreen() {
            const dialog = document.getElementById('completedExperimentsModalDialog');
            const icon = document.getElementById('completedExpsFullscreenIcon');
            if (!dialog) return;

            if (dialog.classList.contains('is-fullscreen')) {
                dialog.classList.remove('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
                dialog.classList.add('rounded-2xl', 'max-w-[98vw]', 'xl:max-w-[1700px]', 'h-[95vh]', 'max-h-[95vh]');
                if (icon) icon.innerText = 'fullscreen';
            } else {
                dialog.classList.add('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
                dialog.classList.remove('rounded-2xl', 'max-w-[98vw]', 'xl:max-w-[1700px]', 'h-[95vh]', 'max-h-[95vh]');
                if (icon) icon.innerText = 'fullscreen_exit';
            }
        }
        window.toggleCompletedExperimentsFullscreen = toggleCompletedExperimentsFullscreen;

        function closeCompletedExperimentsModal() {
            const modal = document.getElementById('completedExperimentsModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
        window.closeCompletedExperimentsModal = closeCompletedExperimentsModal;

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const compModal = document.getElementById('completedExperimentsModal');
                if (compModal && !compModal.classList.contains('hidden')) {
                    closeCompletedExperimentsModal();
                }
            }
        });

        window.completedExperimentsRawList = @json($conductedDetails ?? []);
        window.currentCompExpsCohort = 'all';

        function filterCompletedExpsCohort(cohort) {
            window.currentCompExpsCohort = cohort || 'all';
            
            ['all', '1', '2'].forEach(c => {
                const btn = document.getElementById('cohortTab_' + c);
                if (btn) {
                    if (c === window.currentCompExpsCohort) {
                        btn.className = "px-2.5 py-1 rounded-md font-bold transition bg-indigo-600 text-white cursor-pointer";
                    } else {
                        btn.className = "px-2.5 py-1 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer";
                    }
                }
            });

            renderCompletedExperimentsTable(window.completedExperimentsRawList);
        }
        window.filterCompletedExpsCohort = filterCompletedExpsCohort;

        function renderCompletedExperimentsTable(list) {
            if (list) window.completedExperimentsRawList = list;
            const allItems = window.completedExperimentsRawList || [];
            const tbody = document.getElementById('completedExperimentsTableBody');
            const counter = document.getElementById('completedExpsTableCounter');
            if (!tbody) return;

            const filtered = allItems.filter(item => {
                if (!window.currentCompExpsCohort || window.currentCompExpsCohort === 'all') return true;
                const sb = String(item.sub_batch || 'Whole');
                return sb === String(window.currentCompExpsCohort);
            });

            if (counter) {
                counter.innerText = `Showing ${filtered.length} of ${allItems.length} completed session records`;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="p-6 text-center text-slate-500 font-bold">No completed experiments recorded for this selection.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            let currentGroup = null;

            filtered.forEach((item, idx) => {
                let rawDate = '';
                let dateStr = item.date || '—';
                if (item.date && item.date.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    rawDate = item.date;
                    const dParts = item.date.split('-');
                    dateStr = `${dParts[2]}-${dParts[1]}-${dParts[0]}`;
                } else if (item.date && item.date.includes('-')) {
                    const dParts = item.date.split('-');
                    if (dParts.length === 3 && dParts[0].length === 4) dateStr = `${dParts[2]}-${dParts[1]}-${dParts[0]}`;
                }
                const sb = String(item.sub_batch || 'Whole');
                const batchName = item.batch || (sb === '1' ? 'Batch 1' : (sb === '2' ? 'Batch 2' : 'Whole Class'));
                const batchColor = (sb === '1')
                    ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/25'
                    : ((sb === '2') ? 'bg-purple-500/10 text-purple-300 border border-purple-500/25' : 'bg-slate-800 text-slate-300 border border-slate-700/80');
                const abCount = (item.absent_count !== undefined) ? item.absent_count : Math.max(0, (item.total_count || 0) - (item.present_count || 0));
                const abRolls = item.absent_roll_nos || '-';

                // Insert section banner when viewing "All" and batch group changes
                if ((!window.currentCompExpsCohort || window.currentCompExpsCohort === 'all') && currentGroup !== batchName) {
                    currentGroup = batchName;
                    const bannerTr = document.createElement('tr');
                    bannerTr.className = "batch-section-banner bg-slate-950 border-y border-slate-800";
                    bannerTr.innerHTML = `
                        <td colspan="9" class="py-2.5 px-4 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full ${sb === '1' ? 'bg-indigo-400' : 'bg-purple-400'}"></span>
                                <span class="${sb === '1' ? 'text-indigo-300' : 'text-purple-300'} font-bold uppercase tracking-wider text-xs">${batchName}</span>
                                <span class="text-slate-500 text-[11px] font-normal">• Practical Sessions &amp; Conducted Log Records</span>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(bannerTr);
                }

                const tr = document.createElement('tr');
                tr.className = "border-b border-slate-800/50 hover:bg-slate-800/30 transition text-xs";
                tr.innerHTML = `
                    <td class="p-3 text-center font-mono text-slate-400 whitespace-nowrap">${idx + 1}</td>
                    <td class="p-3 text-center font-mono font-bold text-slate-200 whitespace-nowrap">${item.experiment_no || ('Exp ' + (idx + 1))}</td>
                    <td class="p-3 text-slate-200">
                        <div class="inline-flex items-center gap-2 max-w-full">
                            <span class="font-medium text-slate-100">${item.title || ''}</span>
                            ${item.co_tag ? `<span class="px-1.5 py-0.5 bg-slate-800/90 border border-slate-700/80 rounded text-[10px] font-mono text-slate-400 shrink-0">${item.co_tag}</span>` : ''}
                        </div>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <div class="inline-flex items-center justify-center gap-1.5 text-slate-300 font-mono text-xs">
                            <span class="material-symbols-rounded text-slate-500 text-sm">calendar_today</span>
                            <span>${dateStr}</span>
                        </div>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <span class="px-2 py-0.5 bg-slate-800/70 border border-slate-700/60 rounded text-slate-300 font-mono text-[11px]">${item.hours_text || '3 hrs (Lab)'}</span>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold whitespace-nowrap inline-block ${batchColor}">${batchName}</span>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                            <span class="font-mono font-bold text-slate-100 text-xs">${item.present_count !== undefined ? `${item.present_count}/${item.total_count || ''}` : 'Conducted'}</span>
                            ${item.attendance_pct ? `<span class="px-1.5 py-0.5 rounded ${parseFloat(item.attendance_pct) >= 75 ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-300 border border-amber-500/20'} font-mono text-[11px] font-semibold">${item.attendance_pct}%</span>` : ''}
                        </div>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        ${abCount > 0 ? `<span class="px-2 py-0.5 rounded bg-rose-500/10 border border-rose-500/20 text-rose-400 font-mono font-bold text-xs">${abCount}</span>` : `<span class="text-slate-500 font-mono text-xs">0</span>`}
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        ${(abRolls && abRolls !== 'None' && abRolls !== '-') ? `<span class="px-2.5 py-0.5 rounded bg-rose-500/10 border border-rose-500/25 text-rose-300 font-mono font-semibold text-xs tracking-wide">${abRolls}</span>` : `<span class="text-slate-500 text-xs font-normal">None</span>`}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        let editingExpContext = null;

        function openEditExpDateModal(expId, currentDate, subBatch, expNo, title, newDateToPreselect) {
            if (!expId) return;
            editingExpContext = { expId, currentDate, subBatch, expNo, title };
            
            const elExpNo = document.getElementById('editModalExpNo');
            const elExpNoInput = document.getElementById('editModalExpNoInput');
            const elTitle = document.getElementById('editModalExpTitle');
            const elCurDate = document.getElementById('editModalCurrentDate');
            const elInput = document.getElementById('editModalNewDateInput');
            const elBatchLabel = document.getElementById('editModalBatchLabel');

            const rawExpNoClean = (expNo || '').replace(/^Exp\s*/i, '');
            if (elExpNo) elExpNo.innerText = expNo || `Exp`;
            if (elExpNoInput) elExpNoInput.value = rawExpNoClean || '';
            if (elTitle) elTitle.innerText = title || '';
            
            let displayCurDate = currentDate || 'None';
            if (currentDate && currentDate.includes('-')) {
                const parts = currentDate.split('-');
                if (parts.length === 3 && parts[0].length === 4) displayCurDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            if (elCurDate) elCurDate.innerText = displayCurDate;
            if (elInput) elInput.value = newDateToPreselect || currentDate || '';
            
            const batchLabel = (subBatch === '1' || subBatch === 1) ? 'Batch 1 Only' : ((subBatch === '2' || subBatch === 2) ? 'Batch 2 Only' : 'Whole Class Only');
            if (elBatchLabel) elBatchLabel.innerText = batchLabel;
            
            const fb = document.getElementById('editModalFeedback');
            if (fb) {
                fb.classList.add('hidden');
                fb.textContent = '';
            }

            const modal = document.getElementById('editExpDateModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        window.openEditExpDateModal = openEditExpDateModal;

        function closeEditExpDateModal() {
            const modal = document.getElementById('editExpDateModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            editingExpContext = null;
        }
        window.closeEditExpDateModal = closeEditExpDateModal;

        function promptEditExpDate(expId, oldDate, newDate, subBatch, expNo, title) {
            if (!expId || !newDate || oldDate === newDate) return;
            openEditExpDateModal(expId, oldDate, subBatch, expNo, title, newDate);
        }
        window.promptEditExpDate = promptEditExpDate;

        function submitEditExpDate() {
            if (!editingExpContext) return;
            const fb = document.getElementById('editModalFeedback');
            if (fb) fb.classList.add('hidden');

            const newDate = document.getElementById('editModalNewDateInput').value;
            if (!newDate) {
                if (fb) {
                    fb.className = 'py-2 px-3 rounded-xl text-xs font-bold border bg-rose-500/10 border-rose-500/30 text-rose-400 block';
                    fb.textContent = 'Please select a valid date.';
                    fb.classList.remove('hidden');
                }
                return;
            }
            
            const newExpNo = document.getElementById('editModalExpNoInput') ? document.getElementById('editModalExpNoInput').value.trim() : null;
            const scopeEl = document.querySelector('input[name="editExpDateScope"]:checked');
            const scope = scopeEl ? scopeEl.value : 'universal';
            const syncAtt = document.getElementById('editModalSyncAttendance') ? document.getElementById('editModalSyncAttendance').checked : true;

            const btn = document.getElementById('btnConfirmEditExpDate');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Updating...';
            }

            const subjId = currentSubjectId || "{{ $batchSubject->id ?? '' }}";

            fetch(`/api/classroom/${subjId}/practical/experiment-date`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    experiment_id: editingExpContext.expId,
                    old_date: editingExpContext.currentDate,
                    new_date: newDate,
                    new_experiment_no: newExpNo,
                    scope: scope,
                    sub_batch: editingExpContext.subBatch,
                    update_attendance: syncAtt
                })
            })
            .then(res => res.json())
            .then(res => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-rounded text-sm">check</span> Save & Update';
                }
                if (res.status === 'SUCCESS') {
                    closeEditExpDateModal();
                    if (typeof fetchPracticalEvaluationsData === 'function') {
                        fetchPracticalEvaluationsData().then(() => {
                            openCompletedExperimentsModal();
                        });
                    }
                } else {
                    if (fb) {
                        fb.className = 'py-2 px-3 rounded-xl text-xs font-bold border bg-rose-500/10 border-rose-500/30 text-rose-400 block';
                        fb.textContent = res.message || 'Failed to update experiment date.';
                        fb.classList.remove('hidden');
                    }
                }
            })
            .catch(err => {
                console.error(err);
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-rounded text-sm">check</span> Save & Update';
                }
                if (fb) {
                    fb.className = 'py-2 px-3 rounded-xl text-xs font-bold border bg-rose-500/10 border-rose-500/30 text-rose-400 block';
                    fb.textContent = 'An error occurred while updating experiment date.';
                    fb.classList.remove('hidden');
                }
            });
        }
        window.submitEditExpDate = submitEditExpDate;

        // Manage Experiments Modal Controllers
        function openManageExperimentsModal(e) {
            if (e) e.preventDefault();
            if (!currentSubjectId) currentSubjectId = "{{ $batchSubject->id }}";

            fetch(`/api/classroom/${currentSubjectId}/practical/experiments/databank`)
            .then(res => res.json())
            .then(res => {
                const importBtn = document.getElementById('btnImportDatabank');
                if (importBtn) {
                    if (res.status === 'SUCCESS' && res.databank && res.databank.length > 0) {
                        importBtn.classList.remove('hidden');
                    } else {
                        importBtn.classList.add('hidden');
                    }
                }
            })
            .catch(err => console.error(err));

            renderManageExperimentsList();

            const modal = document.getElementById('manageExperimentsModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        window.openManageExperimentsModal = openManageExperimentsModal;

        function closeManageExperimentsModal() {
            const modal = document.getElementById('manageExperimentsModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function renderManageExperimentsList() {
            const tbody = document.getElementById('manageExpsTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (labExperimentsData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-500 font-bold">
                            No experiments set up yet. Create experiments using the form above.
                        </td>
                    </tr>
                `;
                return;
            }

            labExperimentsData.forEach(exp => {
                const tr = document.createElement('tr');
                tr.className = "border-b border-slate-800/40 hover:bg-slate-900/10";
                tr.innerHTML = `
                    <td class="p-3 text-center font-bold text-slate-400 font-mono">${exp.experiment_no}</td>
                    <td class="p-3 text-slate-200 font-medium text-sm whitespace-pre-wrap leading-relaxed">${exp.title}</td>
                    <td class="p-3 text-center font-bold text-blue-400">${exp.co_tag}</td>
                    <td class="p-3 text-center whitespace-nowrap space-x-2">
                        <button type="button" onclick="editExperiment(${exp.id}, '${exp.experiment_no}', '${exp.title.replace(/'/g, "\\'")}', '${exp.co_tag}')" class="px-2.5 py-1 bg-slate-800 text-slate-300 hover:text-white rounded font-bold cursor-pointer">Edit</button>
                        <button type="button" onclick="deleteExperiment(${exp.id})" class="px-2.5 py-1 bg-red-950/40 text-red-400 hover:text-red-300 rounded font-bold cursor-pointer border border-red-900/30">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function savePracticalExperiment(event) {
            event.preventDefault();
            const expId = document.getElementById('expEditId').value;
            const no = document.getElementById('expFormNo').value.trim();
            const title = document.getElementById('expFormTitle').value.trim();
            const co = document.getElementById('expFormCo').value;

            const btn = document.getElementById('btnSaveExp');
            const icon = document.getElementById('btnSaveExpIcon');
            const label = document.getElementById('btnSaveExpLabel');
            const spinner = document.getElementById('btnSaveExpSpinner');
            const feedback = document.getElementById('expSaveFeedback');

            // Duplicate check to keep number repetition safe
            const isDuplicate = (labExperimentsData || []).some(e => {
                if (expId) {
                    return String(e.id) !== String(expId) && String(e.experiment_no).trim() === String(no).trim();
                } else {
                    return String(e.experiment_no).trim() === String(no).trim();
                }
            });

            if (isDuplicate) {
                if (feedback) {
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-amber-500/10 border-amber-500/30 text-amber-400';
                    feedback.textContent = `⚠ Exp No. ${no} already exists! Duplicate numbers are not allowed.`;
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 3500);
                } else {
                    alert(`Exp No. ${no} already exists! Duplicate numbers are not allowed.`);
                }
                return;
            }

            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            if (icon) icon.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
            if (label) label.textContent = 'Saving...';
            feedback.classList.add('hidden');

            try {
                const res = await fetch(`/api/classroom/${currentSubjectId}/practical/experiments/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id: expId, experiment_no: no, title: title, co_tag: co })
                });
                const data = await res.json();

                if (data.status === 'SUCCESS') {
                    // Reset form
                    cancelExperimentEdit();

                    // 1. Immediately update experiments array from server response
                    if (data.experiments && Array.isArray(data.experiments)) {
                        labExperimentsData = data.experiments;
                    } else if (data.data) {
                        const idx = labExperimentsData.findIndex(e => e.id == data.data.id || e.experiment_no == data.data.experiment_no);
                        if (idx >= 0) {
                            labExperimentsData[idx] = data.data;
                        } else {
                            labExperimentsData.push(data.data);
                        }
                    }

                    // 2. Render UI list IMMEDIATELY (< 10ms)
                    renderManageExperimentsList();
                    updateSyllabusCounters(labExperimentsData.length);

                    // 3. Scroll table to bottom to confirm entry and see the last number
                    const scrollContainer = document.getElementById('manageExpsTableScrollContainer');
                    if (scrollContainer) {
                        setTimeout(() => {
                            scrollContainer.scrollTo({
                                top: scrollContainer.scrollHeight,
                                behavior: 'smooth'
                            });
                        }, 60);
                    }

                    // 4. Show success banner
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-emerald-500/10 border-emerald-500/30 text-emerald-400';
                    feedback.textContent = '✓ ' + (data.message || 'Experiment saved successfully!');
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 2500);

                    // 5. Re-enable button immediately
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    if (spinner) spinner.classList.add('hidden');
                    if (icon) icon.classList.remove('hidden');

                    // 6. Background sync for marks and evaluation without blocking modal
                    fetchPracticalEvaluationsData();
                } else {
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-red-500/10 border-red-500/30 text-red-400';
                    feedback.textContent = '✗ ' + (data.message || 'Failed to save experiment.');
                    feedback.classList.remove('hidden');
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    if (spinner) spinner.classList.add('hidden');
                    if (icon) icon.classList.remove('hidden');
                }
            } catch(err) {
                feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-red-500/10 border-red-500/30 text-red-400';
                feedback.textContent = '✗ Network error. Please try again.';
                feedback.classList.remove('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-60', 'cursor-not-allowed');
                if (spinner) spinner.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
            }
        }

        function editExperiment(id, no, title, co) {
            document.getElementById('expEditId').value = id;
            document.getElementById('expFormNo').value = no;
            document.getElementById('expFormTitle').value = title;
            document.getElementById('expFormCo').value = co;
            const icon = document.getElementById('btnSaveExpIcon');
            const label = document.getElementById('btnSaveExpLabel');
            if (icon) { icon.className = 'fa-solid fa-floppy-disk text-xs'; icon.classList.remove('hidden'); }
            if (label) label.textContent = 'Update';
            const cancelBtn = document.getElementById('btnCancelExpEdit');
            if (cancelBtn) cancelBtn.classList.remove('hidden');
            document.getElementById('expFormNo').focus();
        }

        function cancelExperimentEdit() {
            document.getElementById('expEditId').value = '';
            document.getElementById('expFormNo').value = '';
            document.getElementById('expFormTitle').value = '';
            document.getElementById('expFormCo').value = 'CO1';
            const icon = document.getElementById('btnSaveExpIcon');
            const label = document.getElementById('btnSaveExpLabel');
            if (icon) { icon.className = 'fa-solid fa-plus text-xs'; icon.classList.remove('hidden'); }
            if (label) label.textContent = 'Add Experiment';
            const cancelBtn = document.getElementById('btnCancelExpEdit');
            if (cancelBtn) cancelBtn.classList.add('hidden');
        }
        window.cancelExperimentEdit = cancelExperimentEdit;

        async function deleteExperiment(id) {
            if (!confirm('Are you sure you want to delete this experiment? All graded marks for this experiment will be permanently deleted!')) return;

            // OPTIMISTIC INSTANT REMOVAL: Remove from table immediately for zero-lag response!
            const prevList = [...labExperimentsData];
            labExperimentsData = labExperimentsData.filter(exp => exp.id !== id);
            renderManageExperimentsList();
            updateSyllabusCounters(labExperimentsData.length);

            const feedback = document.getElementById('expSaveFeedback');
            try {
                const res = await fetch(`/api/classroom/${currentSubjectId}/practical/experiments/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    if (data.experiments && Array.isArray(data.experiments)) {
                        labExperimentsData = data.experiments;
                        renderManageExperimentsList();
                        updateSyllabusCounters(labExperimentsData.length);
                    }
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-red-500/10 border-red-500/30 text-red-400';
                    feedback.textContent = data.message || 'Experiment deleted successfully.';
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 2500);

                    // Background sync evaluations
                    fetchPracticalEvaluationsData();
                } else {
                    // Revert if server rejected
                    labExperimentsData = prevList;
                    renderManageExperimentsList();
                    updateSyllabusCounters(labExperimentsData.length);
                    alert(data.message || 'Failed to delete experiment.');
                }
            } catch(err) {
                console.error('Delete error:', err);
                labExperimentsData = prevList;
                renderManageExperimentsList();
                updateSyllabusCounters(labExperimentsData.length);
                alert('Network error deleting experiment.');
            }
        }

        async function importFromDatabank() {
            if (!confirm('This will import the standard list of experiments configured for this subject code. Proceed?')) return;

            const feedback = document.getElementById('expSaveFeedback');
            try {
                const res = await fetch(`/api/classroom/${currentSubjectId}/practical/experiments/import`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    if (data.experiments && Array.isArray(data.experiments)) {
                        labExperimentsData = data.experiments;
                        renderManageExperimentsList();
                        updateSyllabusCounters(labExperimentsData.length);
                    }
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-emerald-500/10 border-emerald-500/30 text-emerald-400';
                    feedback.textContent = data.message || 'Experiments imported successfully.';
                    feedback.classList.remove('hidden');
                    setTimeout(() => feedback.classList.add('hidden'), 2500);

                    // Background sync evaluations
                    fetchPracticalEvaluationsData();
                } else {
                    feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-red-500/10 border-red-500/30 text-red-400';
                    feedback.textContent = data.message || 'Import failed.';
                    feedback.classList.remove('hidden');
                }
            } catch(err) {
                feedback.className = 'px-3 py-2 rounded-lg text-xs font-bold border bg-red-500/10 border-red-500/30 text-red-400';
                feedback.textContent = 'Import failed. Please try again.';
                feedback.classList.remove('hidden');
            }
        }

        // Manage Tests Modal Controllers
        function openManageTestsModal(e) {
            if (e) e.preventDefault();
            if (!currentSubjectId) currentSubjectId = "{{ $batchSubject->id }}";

            const testSelect = document.getElementById('designTestName');
            if (testSelect) testSelect.value = 'Test 1';
            renderTestQuestionsFields();

            const modal = document.getElementById('manageTestsModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        window.openManageTestsModal = openManageTestsModal;

        function closeManageTestsModal() {
            const modal = document.getElementById('manageTestsModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function renderTestQuestionsFields() {
            const activeTestDesign = document.getElementById('designTestName').value;
            const container = document.getElementById('testQuestionsFieldsContainer');
            if (!container) return;
            container.innerHTML = '';

            const test = labTestsData.find(t => t.test_name === activeTestDesign);
            const existingQ = test ? test.questions : {};

            const cos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

            cos.forEach(co => {
                const coQ = existingQ[co] || ['', ''];
                const card = document.createElement('div');
                card.className = "bg-slate-950/40 border border-slate-800 p-4 rounded-xl space-y-3";
                card.innerHTML = `
                    <h4 class="text-sm font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="px-2.5 py-0.5 bg-blue-500/10 text-blue-400 rounded text-xs">${co}</span> Questions (Choice of 1 out of 2)
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option A (7.5 Marks)</label>
                            <textarea name="q_${co}_0" placeholder="Enter question description..." required rows="2" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-slate-200 font-normal text-sm outline-none focus:border-blue-500 resize-y">${coQ[0] || ''}</textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option B (7.5 Marks)</label>
                            <textarea name="q_${co}_1" placeholder="Enter question description..." required rows="2" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-slate-200 font-normal text-sm outline-none focus:border-blue-500 resize-y">${coQ[1] || ''}</textarea>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function savePracticalTestQuestions(event) {
            event.preventDefault();
            const testName = document.getElementById('designTestName').value;
            const cos = testName === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

            const questions = {};
            cos.forEach(co => {
                const q0 = document.querySelector(`textarea[name="q_${co}_0"]`).value;
                const q1 = document.querySelector(`textarea[name="q_${co}_1"]`).value;
                questions[co] = [q0, q1];
            });

            fetch(`/api/classroom/${currentSubjectId}/practical/tests/save`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ test_name: testName, questions })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    alert('Test config saved successfully.');
                    fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
                    .then(r => r.json())
                    .then(innerRes => {
                        if (innerRes.status === 'SUCCESS') {
                            labTestsData = innerRes.tests || [];
                            closeManageTestsModal();
                        }
                    });
                } else {
                    alert(res.message || 'Failed to save test config.');
                }
            })
            .catch(() => alert('Failed to save test configuration.'));
        }

        function handleVirtualLabBack(e) {
            if (e) e.preventDefault();

            @if($isDemonstratorRev21)
                if (window.opener && !window.opener.closed) {
                    try {
                        if (window.opener.location.href.includes('/dashboard/demonstrator')) {
                            window.opener.focus();
                            window.close();
                            return false;
                        }
                    } catch(err) {}
                }
                window.location.href = '/dashboard/demonstrator';
                return false;
            @endif

            if (window.opener && !window.opener.closed) {
                try {
                    if (typeof window.opener.toggleClassroomTab === 'function') {
                        window.opener.toggleClassroomTab('lab_evaluation');
                    }
                    window.opener.focus();
                } catch(err) {}
            }

            // Always attempt to close the tab directly since Virtual Lab was already open in main window
            window.close();

            // Fallback only if window.close() is blocked (e.g., opened URL directly)
            setTimeout(() => {
                const returnUrl = '{{ $dashboardUrl ?? "/dashboard/lecturer" }}?subject_id={{ $batchSubject->id }}&classroom_id={{ $classroom->id ?? "" }}&subject_name={{ urlencode($batchSubject->subject_name) }}&revision={{ $batchSubject->syllabus_revision_code ?? "REV2021" }}&type=Practical&tab=lab_evaluation';
                window.location.href = returnUrl;
            }, 150);

            return false;
        }

        // ══════════════════════════════════════════════════════════════════════
        // STUDENT DETAIL MODAL — JS
        // ══════════════════════════════════════════════════════════════════════

        let detailCurrentRegNo = null;
        let detailStudentList  = studentList.map(s => s.reg_no); // ordered reg_no list
        let attLogLoaded       = false;
        let attLogCache        = null;
        let attLogOpen         = false;

        function openStudentDetailModal(regNo) {
            detailCurrentRegNo = regNo;
            attLogLoaded = false;
            attLogOpen = false;

            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            const graded = gradedCounts[regNo] ?? 0;
            document.getElementById('detailModalStudentName').innerText = student.name;
            document.getElementById('detailModalStudentReg').innerText  = (student.sbte_reg_no && student.sbte_reg_no.trim() !== '') ? student.sbte_reg_no : student.reg_no;
            document.getElementById('detailModalGradedBadge').innerText = `${graded} / ${totalExpCount} Exps Graded`;
            document.getElementById('detailModalGradedBadge').className =
                `ml-2 px-2 py-0.5 text-[10px] font-bold rounded border ${
                    graded === 0 ? 'bg-red-500/15 text-red-400 border-red-500/30'
                    : graded < totalExpCount ? 'bg-amber-500/15 text-amber-400 border-amber-500/30'
                    : 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30'}`;

            const idx = detailStudentList.indexOf(regNo);
            document.getElementById('detailModalPosition').innerText =
                idx >= 0 ? `${idx + 1} / ${detailStudentList.length}` : '';

            renderDetailExpTable(regNo);
            renderDetailCIASummary(regNo);

            // Reset att log
            document.getElementById('attLogBody').classList.add('hidden');
            document.getElementById('attLogChevron').style.transform = '';
            document.getElementById('attLogSummary').innerText = '';

            const modal = document.getElementById('studentDetailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.scrollTop = 0;
        }

        function closeStudentDetailModal() {
            const modal = document.getElementById('studentDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            detailCurrentRegNo = null;
        }

        function navigateDetailModal(dir) {
            if (!detailCurrentRegNo) return;
            const idx = detailStudentList.indexOf(detailCurrentRegNo);
            let next = idx + dir;
            if (next < 0) next = detailStudentList.length - 1;
            if (next >= detailStudentList.length) next = 0;
            openStudentDetailModal(detailStudentList[next]);
        }

        function renderDetailExpTable(regNo) {
            const tbody = document.getElementById('detailExpTableBody');
            tbody.innerHTML = '';
            const expData = studentExpDetail[regNo] ?? {};

            experimentsList.forEach(exp => {
                const m = expData[exp.id];
                const isGraded = m && m.graded;
                const tr = document.createElement('tr');
                tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20';

                if (isGraded) {
                    tr.innerHTML = `
                        <td class="p-2 text-center font-bold text-slate-400 font-mono text-[11px]">${exp.experiment_no}</td>
                        <td class="p-2 text-slate-200 font-medium text-[11px]">${exp.title ?? '—'}</td>
                        <td class="p-2 text-center font-mono text-blue-300 text-[11px]">${(m.rough ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center font-mono text-blue-300 text-[11px]">${(m.fair ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center font-mono text-blue-300 text-[11px]">${(m.obs ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center font-mono text-blue-300 text-[11px]">${(m.proc ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center font-mono text-blue-300 text-[11px]">${(m.viva ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center font-mono font-bold text-sky-300 text-[11px]">${(m.total ?? 0).toFixed(1)}</td>
                        <td class="p-2 text-center">
                            <button onclick="editExpFromModal('${regNo}', '${exp.experiment_no}', '${(exp.title ?? '').replace(/'/g, "\\'")}', ${exp.id})"
                                class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-bold cursor-pointer transition">
                                Edit
                            </button>
                        </td>`;
                } else {
                    tr.innerHTML = `
                        <td class="p-2 text-center font-bold text-slate-500 font-mono text-[11px]">${exp.experiment_no}</td>
                        <td class="p-2 text-slate-400 font-medium text-[11px]">${exp.title ?? '—'}</td>
                        <td colspan="6" class="p-2 text-center">
                            <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded text-[10px] font-bold">— Pending —</span>
                        </td>
                        <td class="p-2 text-center">
                            <button onclick="editExpFromModal('${regNo}', '${exp.experiment_no}', '${(exp.title ?? '').replace(/'/g, "\\'")}', ${exp.id})"
                                class="px-2 py-0.5 bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/30 text-blue-400 rounded text-[10px] font-bold cursor-pointer transition">
                                Grade
                            </button>
                        </td>`;
                }
                tbody.appendChild(tr);
            });

            if (experimentsList.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="p-6 text-center text-slate-500">No experiments configured yet.</td></tr>`;
            }
        }

        function renderDetailCIASummary(regNo) {
            const cia  = consolidatedScores[regNo] ?? {};
            const att  = attendanceMarks[regNo]    ?? {};
            const eval_ = openEndedLogs[regNo]     ?? {};

            // 1. Open-ended
            const oeVal = parseFloat(cia.open_ended_mark ?? cia.scaled_open_ended_10 ?? 0);
            document.getElementById('detailInputOpenEnded').value = oeVal > 0 ? oeVal : '';
            const titleInput = document.getElementById(`open-title-${regNo}`);
            document.getElementById('detailInputOpenTopic').value = titleInput ? titleInput.value : (eval_.project_title || '');

            // 2. Tests
            const t1 = parseFloat(cia.test1_score ?? 0);
            const t2 = parseFloat(cia.test2_score ?? 0);
            document.getElementById('detailInputTest1').value = t1 > 0 ? t1 : '';
            document.getElementById('detailInputTest2').value = t2 > 0 ? t2 : '';

            // 3. Attendance
            document.getElementById('detailInputAttMark').value = att.mark !== undefined ? att.mark : 0;
            document.getElementById('detailAttSuggested').innerText = `Sugg: ${att.suggested_mark ?? att.mark ?? 0}`;
            document.getElementById('detailAttStats').innerText = `${att.percentage ?? 0}% (${att.present_classes ?? 0}/${att.total_classes ?? 0} sessions)`;

            // 4. Lab Work avg
            const labAvg = parseFloat(cia.avg_lab_work_375 ?? cia.scaled_lab_work_30 ?? 0);
            document.getElementById('detailLabWorkAvg').innerText = labAvg.toFixed(2);

            const msgEl = document.getElementById('detailCiaSaveMsg');
            if (msgEl) {
                msgEl.innerText = '';
                msgEl.className = 'text-[11px] font-semibold text-emerald-400 transition-opacity opacity-0';
            }

            recalcDesktopModalCIA();
        }

        function recalcDesktopModalCIA() {
            const oe = parseFloat(document.getElementById('detailInputOpenEnded').value) || 0;
            const t1 = parseFloat(document.getElementById('detailInputTest1').value) || 0;
            const t2 = parseFloat(document.getElementById('detailInputTest2').value) || 0;
            const att = parseFloat(document.getElementById('detailInputAttMark').value) || 0;
            const lab = parseFloat(document.getElementById('detailLabWorkAvg').innerText) || 0;

            const avgT = (t1 + t2) / 2;
            const scaledT = (avgT / 40) * 15;

            document.getElementById('detailCalcTestAvg').innerText = avgT.toFixed(2);
            document.getElementById('detailCalcTestScaled').innerText = `${scaledT.toFixed(2)} / 15M`;

            const total = lab + oe + scaledT + att;
            document.getElementById('detailTotalCIA').innerText = total.toFixed(2);
        }

        async function saveStudentCiaSummaryDesktop() {
            if (!detailCurrentRegNo) return;
            const regNo = detailCurrentRegNo;

            const oe = parseFloat(document.getElementById('detailInputOpenEnded').value) || 0;
            const topic = document.getElementById('detailInputOpenTopic').value;
            const t1 = document.getElementById('detailInputTest1').value !== '' ? parseFloat(document.getElementById('detailInputTest1').value) : null;
            const t2 = document.getElementById('detailInputTest2').value !== '' ? parseFloat(document.getElementById('detailInputTest2').value) : null;
            const att = document.getElementById('detailInputAttMark').value !== '' ? parseFloat(document.getElementById('detailInputAttMark').value) : null;

            const msgEl = document.getElementById('detailCiaSaveMsg');
            if (msgEl) {
                msgEl.innerText = 'Saving...';
                msgEl.className = 'text-[11px] font-semibold text-sky-400 opacity-100';
            }

            try {
                const res = await fetch(`/api/classroom/${batchSubjectId}/practical/cia-summary`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        reg_no: regNo,
                        open_ended_mark: oe,
                        open_ended_topic: topic,
                        test1: t1,
                        test2: t2,
                        attendance_mark: att
                    })
                });
                const resp = await res.json();
                if (resp.success) {
                    const d = resp.data;

                    // Update memory state
                    if (!consolidatedScores[regNo]) consolidatedScores[regNo] = {};
                    consolidatedScores[regNo].open_ended_mark = d.open_ended_mark;
                    consolidatedScores[regNo].scaled_open_ended_10 = d.open_ended_mark;
                    consolidatedScores[regNo].test1_score = d.test1_score;
                    consolidatedScores[regNo].test2_score = d.test2_score;
                    consolidatedScores[regNo].avg_test_40 = d.avg_test_40;
                    consolidatedScores[regNo].scaled_series_15 = d.scaled_series_15;
                    consolidatedScores[regNo].att_mark_15 = d.att_mark_15;
                    consolidatedScores[regNo].total_cia_75 = d.total_cia_75;
                    consolidatedScores[regNo].total_cia_60 = d.total_cia_75;

                    if (!attendanceMarks[regNo]) attendanceMarks[regNo] = {};
                    attendanceMarks[regNo].mark = d.att_mark_15;

                    // Update Tab 4 (Consolidated CIA Summary table)
                    const elOpen = document.getElementById(`cia-open-${regNo}`);
                    if (elOpen) elOpen.innerText = d.open_ended_mark.toFixed(2);

                    const elSeries = document.getElementById(`cia-series-${regNo}`);
                    if (elSeries) elSeries.innerText = d.scaled_series_15.toFixed(2);

                    const elAtt = document.getElementById(`cia-att-${regNo}`);
                    if (elAtt) elAtt.innerText = d.att_mark_15;

                    const elTotal = document.getElementById(`cia-total-${regNo}`);
                    if (elTotal) elTotal.innerText = d.total_cia_75.toFixed(2);

                    // Update Tab 2 project title & score if exists
                    const titleInp = document.getElementById(`open-title-${regNo}`);
                    if (titleInp) titleInp.value = d.open_ended_topic;
                    const scoreTextOpen = document.getElementById(`score-text-open-${regNo}`);
                    if (scoreTextOpen) scoreTextOpen.innerText = `${(d.open_ended_mark * (50 / 7.5)).toFixed(1)} / 50`;

                    if (msgEl) {
                        msgEl.innerText = 'Saved ✓';
                        msgEl.className = 'text-[11px] font-semibold text-emerald-400 opacity-100';
                        setTimeout(() => {
                            if (msgEl) msgEl.className = 'text-[11px] font-semibold text-emerald-400 opacity-0';
                        }, 3000);
                    }
                } else {
                    alert(resp.message || 'Error saving CIA summary.');
                    if (msgEl) msgEl.innerText = '';
                }
            } catch(e) {
                console.error(e);
                alert('Failed to save CIA summary.');
                if (msgEl) msgEl.innerText = '';
            }
        }

        function editExpFromModal(regNo, expNo, expTitle, expId) {
            // Pre-load experiment selector with the chosen experiment, then open grade modal
            const expNoInput = document.getElementById('exp_no');
            const expTitleInput = document.getElementById('exp_title');
            if (expNoInput) expNoInput.value = expNo;
            if (expTitleInput) expTitleInput.value = expTitle;

            // Switch to lab work tab so the grading modal context is correct
            switchTab('table22');
            activeTab = 'table22';

            // Close detail modal, then open grading modal
            closeStudentDetailModal();
            setTimeout(() => openGradingModal(regNo, 'table22'), 150);
        }

        function toggleAttLog() {
            attLogOpen = !attLogOpen;
            const body    = document.getElementById('attLogBody');
            const chevron = document.getElementById('attLogChevron');

            if (attLogOpen) {
                body.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                if (!attLogLoaded) loadAttendanceLog(detailCurrentRegNo);
            } else {
                body.classList.add('hidden');
                chevron.style.transform = '';
            }
        }

        async function loadAttendanceLog(regNo) {
            const tbody  = document.getElementById('attLogTableBody');
            const summary = document.getElementById('attLogSummary');
            tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-500">Loading...</td></tr>`;

            try {
                if (!attLogCache) {
                    const res  = await fetch(`/api/classroom/${batchSubjectId}/practical/attendance-log`);
                    const data = await res.json();
                    attLogCache = data.status === 'SUCCESS' ? data.logs : [];
                }

                const logs = attLogCache;
                let present = 0, absent = 0;
                tbody.innerHTML = '';

                if (logs.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-500">No attendance logs found.</td></tr>`;
                    return;
                }

                logs.forEach(log => {
                    const isPresent = log.present.includes(regNo);
                    const isAbsent  = log.absent.includes(regNo);
                    if (isPresent) present++;
                    if (!isPresent) absent++;

                    const tr = document.createElement('tr');
                    tr.className = `border-b border-slate-800/40 ${isPresent ? '' : 'bg-red-950/10'}`;
                    tr.innerHTML = `
                        <td class="p-2 font-mono text-slate-300 text-[11px]">${log.date}</td>
                        <td class="p-2 text-center font-mono text-slate-400 text-[11px]">${log.period}</td>
                        <td class="p-2 text-slate-300 text-[11px]">${log.topic}</td>
                        <td class="p-2 text-center text-slate-500 text-[10px]">${log.sub_batch ?? 'Whole'}</td>
                        <td class="p-2 text-center">
                            ${isPresent
                                ? '<span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded text-[10px] font-bold">✓ Present</span>'
                                : '<span class="px-2 py-0.5 bg-red-500/10 text-red-400 border border-red-500/20 rounded text-[10px] font-bold">✗ Absent</span>'}
                        </td>`;
                    tbody.appendChild(tr);
                });

                const total = logs.length;
                const pct   = total > 0 ? Math.round((present / total) * 100) : 0;
                summary.innerText = `(${present} / ${total} sessions — ${pct}%)`;
                attLogLoaded = true;

            } catch(err) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-400">Failed to load attendance log.</td></tr>`;
                console.error(err);
            }
        }
    </script>

    @include('partials.lab_batch_setup_modal')

    @if(!($labBatchConfig['is_configured'] ?? false))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof openLabBatchSetupModal === 'function') {
                    openLabBatchSetupModal('{{ $batchSubject->id }}');
                }
            }, 600);
        });
    </script>
    @endif
</body>
</html>
