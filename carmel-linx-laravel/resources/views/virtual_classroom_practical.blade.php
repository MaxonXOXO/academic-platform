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

            <a href="/classroom/practical/{{ $batchSubject->id }}/report/print" target="_blank" class="px-3 py-1.5 bg-sky-500/10 border border-sky-500/30 hover:bg-sky-500/20 text-sky-400 text-xs rounded-lg font-medium transition flex items-center gap-1.5">
                <i class="fa-solid fa-print text-[11px]"></i> <span class="hidden sm:inline">Print CIA Report</span>
            </a>

            <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shadow-amber-500/20" title="Return to Dashboard">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Return to Dashboard</span>
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
        <!-- Return Button directly inside the Tab Bar container -->
        <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer no-underline shadow-md shadow-amber-500/20 shrink-0" title="Return to Dashboard">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Return to Dashboard</span>
        </a>

        <!-- Horizontal Tabs (Drawing Hall 2026 Style) -->
        <div class="drawing-hall-tab-bar rounded-xl flex-1">
            <button onclick="switchTab('table22')" id="btn-table22" class="drawing-hall-tab-btn tab-btn active">
                <i class="fa-solid fa-pen-ruler text-sky-400"></i>
                <span>Continuous Eval (CE 30M)</span>
            </button>

            <button onclick="switchTab('table23')" id="btn-table23" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-lightbulb text-amber-400"></i>
                <span>Open-Ended (10M)</span>
            </button>

            <button onclick="switchTab('table31')" id="btn-table31" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-pen-to-square text-blue-400"></i>
                <span>Practical Tests (15M)</span>
            </button>

            <button onclick="switchTab('summary')" id="btn-summary" class="drawing-hall-tab-btn tab-btn">
                <i class="fa-solid fa-chart-pie text-purple-400"></i>
                <span>Consolidated CIE & Reports (60M)</span>
            </button>
        </div>

        <!-- Inline Lab Batch Filters -->
        <div class="flex items-center gap-1 text-xs">
            <span class="text-[10px] uppercase font-bold text-slate-500 me-1 hidden md:inline">Batch:</span>
            <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-blue-500 text-blue-400 font-medium text-[11px] transition">
                All
            </button>
            <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Unassigned
            </button>
            <button onclick="filterLabBatch('Batch A')" id="batch-filter-A" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Batch A
            </button>
            <button onclick="filterLabBatch('Batch B')" id="batch-filter-B" class="batch-filter-btn px-2.5 py-1 rounded-md bg-slate-900 border border-slate-800 text-slate-400 font-medium text-[11px] transition">
                Batch B
            </button>
        </div>
    </div>

    <!-- Main Workspace Workspace -->
    <main class="flex-grow p-2 transition-premium relative z-30">

        <!-- TAB 1: Continuous Lab Work Evaluation (Table 2.2) -->
        <div id="tab-table22" class="tab-content glass-panel p-4">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-vials text-blue-400 text-xs"></i> Lab Work Evaluation (Table 2.2)
                    </h2>
                    <p class="text-[11px] text-slate-400">Day-to-day continuous evaluation out of 50 (scales to 30 CIA marks).</p>
                </div>

                <div class="flex items-center gap-2">
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
                            <th class="text-center w-24">Score (/50)</th>
                            <th class="text-center w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                            $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td class="text-center text-cyan-400 font-mono text-xs">{{ $student->roll_no ?? ($index + 1) }}</td>
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ $student->reg_no }}
                                </span>
                            </td>
                            <td class="text-white text-xs font-medium">{{ $student->name }}</td>
                            <td class="text-center">
                                <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="bg-slate-950 border border-slate-800 text-[11px] text-slate-300 rounded px-1.5 py-0.5 focus:outline-none focus:border-blue-500">
                                    <option value="" {{ $batchDesignation == 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                                    <option value="Batch A" {{ $batchDesignation == 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                    <option value="Batch B" {{ $batchDesignation == 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-blue-400">
                                    {{ $expLog ? floatval($expLog->total_score_50) : '0' }} / 50
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

        <!-- TAB 2: Open-Ended Experiment / Project (Table 2.3) -->
        <div id="tab-table23" class="tab-content glass-panel p-4 hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-400 text-xs"></i> Open-Ended Project (Table 2.3)
                    </h2>
                    <p class="text-[11px] text-slate-400">Originality & execution score out of 50 (scales to 10 CIA marks).</p>
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
                            <th class="text-center w-24">Score (/50)</th>
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
                                    {{ $student->reg_no }}
                                </span>
                            </td>
                            <td class="text-white text-xs font-medium">{{ $student->name }}</td>
                            <td>
                                <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-2 py-0.5 bg-slate-950 border border-slate-800 rounded text-[11px] text-white focus:outline-none focus:border-amber-500 w-full">
                            </td>
                            <td class="text-center">
                                <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-amber-400">
                                    {{ $openLog ? floatval($openLog->total_score_50) : '0' }} / 50
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

        <!-- TAB 3: Practical Series Examination (Table 3.1) -->
        <div id="tab-table31" class="tab-content glass-panel p-4 hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-purple-400 text-xs"></i> Series Practical Test (Table 3.1)
                    </h2>
                    <p class="text-[11px] text-slate-400">Practical exam score out of 40 (average scales to 15 CIA marks).</p>
                </div>

                <div class="flex items-center gap-2">
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
                                    {{ $student->reg_no }}
                                </span>
                            </td>
                            <td class="text-white text-xs font-medium">{{ $student->name }}</td>
                            <td class="text-center">
                                <span id="score-text-series-{{ $student->reg_no }}" class="font-mono text-xs font-semibold text-purple-400"
                                      data-s1="{{ $series1Log ? floatval($series1Log->total_score_40) : '0' }}"
                                      data-s2="{{ $series2Log ? floatval($series2Log->total_score_40) : '0' }}">
                                    {{ $series1Log ? floatval($series1Log->total_score_40) : '0' }} / 40
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

        <!-- TAB 4: Consolidated CIA Summary (60 Marks) -->
        <div id="tab-summary" class="tab-content glass-panel p-4 hidden">
            <div class="pb-3 border-b border-slate-800/80">
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-award text-sky-400 text-xs"></i> Lab CIA Consolidated Summary Sheet
                </h2>
                <p class="text-[11px] text-slate-400">Real-time consolidated continuous internal assessment breakdown (out of 60 marks).</p>
            </div>

            <!-- Student High-Density Table -->
            <div class="mt-3 overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="w-28">Register No</th>
                            <th>Student Name</th>
                            <th class="text-center w-24">Batch</th>
                            <th class="text-center w-28">Lab Work (30M)</th>
                            <th class="text-center w-28">Series (15M)</th>
                            <th class="text-center w-28">Open Ended (10M)</th>
                            <th class="text-center w-24">Attendance (5M)</th>
                            <th class="text-center w-28">Total CIA (60M)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $score = $consolidatedScores[$student->reg_no] ?? [];
                            $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                        @endphp
                        <tr class="student-row" data-reg-no="{{ $student->reg_no }}" data-batch="{{ $batchDesignation }}">
                            <td>
                                <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded">
                                    {{ $student->reg_no }}
                                </span>
                            </td>
                            <td class="text-white text-xs font-medium">{{ $student->name }}</td>
                            <td class="text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $batchDesignation == 'Batch A' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : ($batchDesignation == 'Batch B' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-900 text-slate-400') }}">
                                    {{ $batchDesignation }}
                                </span>
                            </td>
                            <td class="text-center font-mono text-blue-400 text-xs" id="cia-lab-work-{{ $student->reg_no }}">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-purple-400 text-xs" id="cia-series-{{ $student->reg_no }}">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-amber-400 text-xs" id="cia-open-{{ $student->reg_no }}">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                            <td class="text-center font-mono text-sky-400 text-xs">{{ $attendanceMarks[$student->reg_no]['mark'] ?? 5 }}</td>
                            <td class="text-center font-mono font-bold text-xs text-cyan-300" id="cia-total-{{ $student->reg_no }}">{{ $score['total_cia_60'] ?? '0.00' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

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
                c1: parseFloat(expLog.prep_punctuality),
                c2: parseFloat(expLog.setup_procedure),
                c3: parseFloat(expLog.observation_recording),
                c4: parseFloat(expLog.analysis_interpretation),
                c5: parseFloat(expLog.viva_voce),
                c6: parseFloat(expLog.teamwork_discipline)
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

            const activeBtnMap = { 'All': 'All', 'Unassigned': 'Unassigned', 'Batch A': 'A', 'Batch B': 'B' };
            const selectBtnId = activeBtnMap[batch] === 'All' ? 'All' : (activeBtnMap[batch] === 'Unassigned' ? 'Unassigned' : activeBtnMap[batch]);
            const targetBtn = document.getElementById(`batch-filter-${selectBtnId}`);
            if (targetBtn) {
                targetBtn.classList.remove('border-slate-800', 'text-slate-400');
                targetBtn.classList.add('border-blue-500', 'text-blue-400');
            }

            document.querySelectorAll('.student-row').forEach(row => {
                const studentBatch = row.getAttribute('data-batch') || 'Unassigned';
                if (batch === 'All') {
                    row.classList.remove('hidden');
                } else if (batch === 'Unassigned' && studentBatch === 'Unassigned') {
                    row.classList.remove('hidden');
                } else if (studentBatch === batch) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // Assign a student to Batch A/B via API
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
                    filterLabBatch(activeBatchFilter);
                } else {
                    alert(data.message);
                }
            } catch(e) {
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
                    scoreText.innerText = `${total.toFixed(2)} / 40`;
                }
            });
        }

        // Open Overlay Grading Modal for individual student evaluation
        function openGradingModal(regNo, tabType) {
            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            currentStudentIndex = studentList.findIndex(s => s.reg_no === regNo);

            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.reg_no;

            // Generate HTML range sliders based on active tab rubrics
            const container = document.getElementById('modalSlidersContainer');
            container.innerHTML = '';

            let rubrics = [];
            if (tabType === 'table22') {
                rubrics = [
                    { label: '1. Prep & Punctuality (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Procedure (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Observation & Recording (Max 5)', key: 'c3', max: 5, step: 0.5 },
                    { label: '4. Analysis & Results (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Viva Voce & Understanding (Max 10)', key: 'c5', max: 10, step: 0.5 },
                    { label: '6. Workmanship & Attitude (Max 5)', key: 'c6', max: 5, step: 0.5 }
                ];
            } else if (tabType === 'table23') {
                rubrics = [
                    { label: '1. Originality & Idea (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Plan & Objectives (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Execution & Safety (Max 10)', key: 'c3', max: 10, step: 0.5 },
                    { label: '4. Analysis & Results (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Teamwork & Innovation (Max 10)', key: 'c5', max: 10, step: 0.5 }
                ];
            } else if (tabType === 'table31') {
                rubrics = [
                    { label: '1. Procedure & Write-up (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Execution (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Observations & Output (Max 8)', key: 'c3', max: 8, step: 0.5 },
                    { label: '4. Viva Voce (Max 8)', key: 'c4', max: 8, step: 0.5 },
                    { label: '5. Record Completion (Max 4)', key: 'c5', max: 4, step: 0.5 }
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
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 40`;
            } else if (tabType === 'table23') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0);
                const textEl = document.getElementById(`score-text-open-${regNo}`);
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 50`;
            } else if (tabType === 'table22') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0) + (scores.c6||0);
                const textEl = document.getElementById(`score-text-exp-${regNo}`);
                if (textEl) textEl.innerText = `${total.toFixed(2)} / 50`;
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

        // Live client-side recalculation of consolidated 60M CIA sheet
        function recalculateCIA(regNo) {
            // 1. Lab Work (30 M)
            const expScores = scoresState.table22[regNo] || {};
            const expTotal = (expScores.c1||0) + (expScores.c2||0) + (expScores.c3||0) + (expScores.c4||0) + (expScores.c5||0) + (expScores.c6||0);
            const scaledLabWork30 = (expTotal / 50) * 30;
            const labEl = document.getElementById(`cia-lab-work-${regNo}`);
            if (labEl) labEl.innerText = scaledLabWork30.toFixed(2);

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
            const att = attendanceMarks[regNo] ? attendanceMarks[regNo].mark : 5;

            // CIA Total out of 60
            const totalCIA = scaledLabWork30 + scaledOpen10 + scaledSeries15 + att;
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

        function handleVirtualLabBack(e) {
            if (e) e.preventDefault();
            if (window.opener && !window.opener.closed) {
                try {
                    window.close();
                    return false;
                } catch(err) {}
            }
            if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                window.location.href = document.referrer;
                return false;
            }
            window.location.href = '{{ $dashboardUrl }}';
            return false;
        }
    </script>
</body>
</html>
