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
            background-color: #030712; /* Darker slate-950 background */
            color: #f3f4f6;
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.25rem;
        }

        .slider-accent {
            accent-color: #3b82f6;
        }

        /* Large touch range sliders */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 12px;
            border-radius: 6px;
            background: #1f2937;
            outline: none;
            cursor: pointer;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            box-shadow: 0 0 12px rgba(59, 130, 246, 0.6);
            transition: transform 0.15s ease, background-color 0.15s ease;
        }

        input[type=range]::-webkit-slider-thumb:active {
            transform: scale(1.3);
            background: #60a5fa;
        }
        
        .transition-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col overflow-x-hidden">

    <!-- Top Navigation Header -->
    <header class="glass-panel mx-4 mt-4 px-6 py-4 flex items-center justify-between shadow-2xl relative z-40">
        <div class="flex items-center gap-4">
            <a href="/dashboard/lecturer" class="p-3 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white transition-premium">
                <i class="fa-solid fa-arrow-left text-base"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-[11px] font-black tracking-wider rounded-md bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        VIRTUAL LAB ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }})
                    </span>
                    <span class="text-xs text-slate-500 font-mono font-bold">{{ $batchSubject->subject_code }}</span>
                </div>
                <h1 class="text-xl font-black text-white tracking-tight mt-1">{{ $batchSubject->subject_name }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Fullscreen Toggle -->
            <button onclick="toggleFullscreen()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-350 text-xs rounded-xl font-bold border border-slate-800 hover:border-slate-700 transition flex items-center gap-2">
                <i class="fa-solid fa-expand"></i> <span class="hidden sm:inline">Fullscreen</span>
            </button>
            
            <!-- Sidebar Toggle -->
            <button onclick="toggleSidebar()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-350 text-xs rounded-xl font-bold border border-slate-800 hover:border-slate-700 transition flex items-center gap-2">
                <i class="fa-solid fa-bars"></i> <span class="hidden sm:inline">Toggle Sidebar</span>
            </button>

            <a href="/classroom/practical/{{ $batchSubject->id }}/report/print" target="_blank" class="px-4 py-2 bg-emerald-600/20 border border-emerald-500/30 hover:bg-emerald-600/35 text-emerald-400 text-xs rounded-xl font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> <span class="hidden sm:inline">Print CIA Report</span>
            </a>
        </div>
    </header>

    <!-- Main Workspace Layout -->
    <div class="flex-grow flex flex-col lg:flex-row gap-6 p-4 md:p-6 transition-premium relative z-30">

        <!-- Left Sidebar: Tab controls & configurations -->
        <aside id="workspaceSidebar" class="w-full lg:w-80 space-y-4 transition-premium flex-shrink-0">
            
            <!-- Navigation Modules Card -->
            <div class="glass-panel p-4 space-y-2">
                <h2 class="text-xs font-bold text-slate-455 uppercase tracking-widest px-2 mb-3">Evaluation Sheets</h2>

                <button onclick="switchTab('table22')" id="btn-table22" class="tab-btn w-full flex items-center justify-between p-3.5 rounded-xl bg-blue-600/20 border border-blue-500/40 text-blue-400 font-bold text-sm transition-premium">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-flask"></i>
                        <span>Lab Work Log</span>
                    </span>
                    <span class="text-[10px] bg-blue-500/20 px-2 py-0.5 rounded text-blue-300 font-mono">Table 2.2</span>
                </button>

                <button onclick="switchTab('table23')" id="btn-table23" class="tab-btn w-full flex items-center justify-between p-3.5 rounded-xl hover:bg-slate-900/60 text-slate-400 font-bold text-sm transition-premium">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-lightbulb"></i>
                        <span>Open-Ended Projects</span>
                    </span>
                    <span class="text-[10px] bg-slate-800 px-2 py-0.5 rounded text-slate-350 font-mono">Table 2.3</span>
                </button>

                <button onclick="switchTab('table31')" id="btn-table31" class="tab-btn w-full flex items-center justify-between p-3.5 rounded-xl hover:bg-slate-900/60 text-slate-400 font-bold text-sm transition-premium">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Series Practical Tests</span>
                    </span>
                    <span class="text-[10px] bg-slate-800 px-2 py-0.5 rounded text-slate-355 font-mono">Table 3.1</span>
                </button>

                <button onclick="switchTab('summary')" id="btn-summary" class="tab-btn w-full flex items-center justify-between p-3.5 rounded-xl hover:bg-slate-900/60 text-slate-400 font-bold text-sm transition-premium">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-award"></i>
                        <span>CIA Consolidated</span>
                    </span>
                    <span class="text-[10px] bg-emerald-500/20 px-2 py-0.5 rounded text-emerald-400 font-mono font-bold">60 M</span>
                </button>
            </div>

            <!-- Lab Splits configuration -->
            <div class="glass-panel p-4 space-y-3">
                <h3 class="text-xs font-bold text-slate-455 uppercase tracking-widest px-1">Lab Batches Filter</h3>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn py-2 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 border border-blue-500 text-blue-400 font-extrabold transition-premium">
                        All Classes
                    </button>
                    <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn py-2 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 font-extrabold transition-premium">
                        Unassigned
                    </button>
                    <button onclick="filterLabBatch('Batch A')" id="batch-filter-A" class="batch-filter-btn py-2 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 font-extrabold transition-premium">
                        Batch A
                    </button>
                    <button onclick="filterLabBatch('Batch B')" id="batch-filter-B" class="batch-filter-btn py-2 px-3 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 font-extrabold transition-premium">
                        Batch B
                    </button>
                </div>
            </div>

            <!-- R2026 Practical Regulations -->
            <div class="glass-panel p-4 border-l-4 border-blue-500 bg-blue-950/10">
                <h4 class="text-sm font-black text-blue-400 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i> R2026 Practical CIA Specs
                </h4>
                <p class="text-xs text-slate-355 leading-relaxed mt-2">
                    Evaluation breakdown according to Kerala SBTE standard rules:
                </p>
                <div class="grid grid-cols-2 gap-2 text-[11px] font-mono mt-3 text-slate-300">
                    <div class="bg-slate-900/60 p-1.5 rounded border border-slate-800"><span class="block text-slate-500">Day Work</span><b>30 Marks</b></div>
                    <div class="bg-slate-900/60 p-1.5 rounded border border-slate-800"><span class="block text-slate-500">Series Exam</span><b>15 Marks</b></div>
                    <div class="bg-slate-900/60 p-1.5 rounded border border-slate-800"><span class="block text-slate-500">Open-ended</span><b>10 Marks</b></div>
                    <div class="bg-slate-900/60 p-1.5 rounded border border-slate-800"><span class="block text-slate-500">Attendance</span><b>5 Marks</b></div>
                </div>
            </div>

        </aside>

        <!-- Right Content Panels Workspace -->
        <section id="workspaceContent" class="flex-grow transition-premium min-w-0">

            <!-- TAB 1: Continuous Lab Work Evaluation (Table 2.2) -->
            <div id="tab-table22" class="tab-content glass-panel p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="fa-solid fa-vials text-blue-400"></i> Lab Work Evaluation (Table 2.2)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Grade day-to-day experiments out of 50. Total averages scale to 30 internal marks.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div>
                            <label class="block text-[10px] uppercase font-black text-slate-500 tracking-wider mb-1">Exp No.</label>
                            <input type="text" id="exp_no" value="Exp 1" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-blue-500 font-mono w-24">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-slate-500 tracking-wider mb-1">Experiment Title</label>
                            <input type="text" id="exp_title" placeholder="e.g. Verification of Ohm's Law" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-blue-500 w-48 sm:w-64">
                        </div>
                    </div>
                </div>

                <!-- Student List -->
                <div class="mt-6 space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                        $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchDesignation }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-400 font-black text-sm flex items-center justify-center border border-blue-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-cyan-400 font-bold">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <!-- Batch assignment inline select -->
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black uppercase text-slate-500">Lab Batch:</span>
                                    <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="bg-slate-950 border border-slate-800 text-xs rounded-lg py-1 px-2 text-slate-300 focus:outline-none focus:border-blue-500">
                                        <option value="" {{ $batchDesignation == 'Unassigned' ? 'selected' : '' }}>Unassigned</option>
                                        <option value="Batch A" {{ $batchDesignation == 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                        <option value="Batch B" {{ $batchDesignation == 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                    </select>
                                </div>

                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table22')" class="px-4 py-2 bg-blue-600/15 border border-blue-500/20 hover:bg-blue-600/25 hover:border-blue-500/35 text-blue-400 text-xs font-black rounded-xl transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Score</span>
                                    <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-sm font-bold text-blue-400">
                                        {{ $expLog ? floatval($expLog->total_score_50) : '0' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitExpMarks()" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/15 transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Continuous Log
                    </button>
                </div>
            </div>

            <!-- TAB 2: Open-Ended Experiment / Project (Table 2.3) -->
            <div id="tab-table23" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800">
                    <h2 class="text-lg font-black text-white flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-400"></i> Open-Ended Project (Table 2.3)
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Assess originality and execution of open-ended experiments out of 50. Normalized to 10 marks.</p>
                </div>

                <div class="mt-6 space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                        $openLog = $openEndedLogs->get($student->reg_no);
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchDesignation }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 font-black text-sm flex items-center justify-center border border-amber-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-cyan-400 font-bold">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500 w-44">

                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table23')" class="px-4 py-2 bg-amber-500/15 border border-amber-500/20 hover:bg-amber-500/25 hover:border-amber-500/35 text-amber-400 text-xs font-black rounded-xl transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Score</span>
                                    <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-sm font-bold text-amber-400">
                                        {{ $openLog ? floatval($openLog->total_score_50) : '0' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitOpenEndedMarks()" class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm rounded-xl shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Open-Ended Log
                    </button>
                </div>
            </div>

            <!-- TAB 3: Practical Series Examination (Table 3.1) -->
            <div id="tab-table31" class="tab-content glass-panel p-6 hidden">
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-purple-400"></i> Series Exam Evaluation (Table 3.1)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Practical examinations out of 40. Consolidated average represents 15 CIA marks.</p>
                    </div>
                    <select id="series_no" onchange="switchSeriesExam(this.value)" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-purple-500 font-bold">
                        <option value="Series 1">Series Exam 1</option>
                        <option value="Series 2">Series Exam 2</option>
                    </select>
                </div>

                <div class="mt-6 space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                        $series1Log = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                        $series2Log = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchDesignation }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-400 font-black text-sm flex items-center justify-center border border-purple-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-100">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-cyan-400 font-bold">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table31')" class="px-4 py-2 bg-purple-600/15 border border-purple-500/20 hover:bg-purple-600/25 hover:border-purple-500/35 text-purple-400 text-xs font-black rounded-xl transition flex items-center gap-1.5">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Score</span>
                                    <span id="score-text-series-{{ $student->reg_no }}" class="font-mono text-sm font-bold text-purple-400"
                                          data-s1="{{ $series1Log ? floatval($series1Log->total_score_40) : '0' }}"
                                          data-s2="{{ $series2Log ? floatval($series2Log->total_score_40) : '0' }}">
                                        {{ $series1Log ? floatval($series1Log->total_score_40) : '0' }} / 40
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitSeriesMarks()" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm rounded-xl shadow-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save Series Exam Marks
                    </button>
                </div>
            </div>

            <!-- TAB 4: Consolidated CIA Summary (60 Marks) -->
            <div id="tab-summary" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="fa-solid fa-award text-emerald-400"></i> R2026 Lab CIA Summary Sheet
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Real-time consolidated continuous assessment totals out of 60 marks.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-350 border-collapse">
                        <thead>
                            <tr class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                                <th class="p-3">Roll / Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Batch</th>
                                <th class="p-3 text-center">Lab Work (30M)</th>
                                <th class="p-3 text-center">Series Exam (15M)</th>
                                <th class="p-3 text-center">Open Ended (10M)</th>
                                <th class="p-3 text-center">Attendance (5M)</th>
                                <th class="p-3 text-center font-bold text-white">Total CIA (60M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($students as $student)
                            @php
                                $score = $consolidatedScores[$student->reg_no] ?? [];
                                $batchDesignation = $labBatches->get($student->reg_no)->lab_batch ?? 'Unassigned';
                            @endphp
                            <tr class="student-row hover:bg-slate-900/20 transition-premium" 
                                data-reg-no="{{ $student->reg_no }}" 
                                data-batch="{{ $batchDesignation }}">
                                <td class="p-3 font-mono text-slate-450">{{ $student->reg_no }}</td>
                                <td class="p-3 font-bold text-white">{{ $student->name }}</td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $batchDesignation == 'Batch A' ? 'bg-indigo-500/10 text-indigo-400' : ($batchDesignation == 'Batch B' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-slate-850 text-slate-500') }}">
                                        {{ $batchDesignation }}
                                    </span>
                                </td>
                                <td class="p-3 text-center font-mono text-blue-400" id="cia-lab-work-{{ $student->reg_no }}">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-purple-400" id="cia-series-{{ $student->reg_no }}">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-amber-400" id="cia-open-{{ $student->reg_no }}">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-emerald-400">{{ $attendanceMarks[$student->reg_no]['mark'] ?? 5 }}</td>
                                <td class="p-3 text-center font-mono font-bold text-base text-emerald-400" id="cia-total-{{ $student->reg_no }}">{{ $score['total_cia_60'] ?? '0.00' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </div>

    <!-- MOBILE INDIVIDUAL STUDENT GRADING OVERLAY MODAL -->
    <div id="gradingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col justify-end sm:justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
            
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <div>
                    <h3 id="modalStudentName" class="font-black text-white text-base">Student Name</h3>
                    <span id="modalStudentReg" class="text-xs font-mono text-cyan-400 font-bold">Reg No</span>
                </div>
                <button onclick="closeGradingModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Rubrics Input Container -->
            <div id="modalSlidersContainer" class="space-y-4 max-h-[50vh] overflow-y-auto pr-1">
                <!-- Dynamically populated via JS -->
            </div>

            <!-- Stepper bottom actions -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-800 gap-3">
                <button onclick="navigateStudent(-1)" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </button>

                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Total</span>
                    <span id="modalTotalScore" class="font-mono text-base font-black text-blue-400">0.00</span>
                </div>

                <button onclick="navigateStudent(1)" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    Next <i class="fa-solid fa-chevron-right"></i>
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
                el.classList.remove('bg-blue-600/20', 'border-blue-500/40', 'text-blue-400');
                el.classList.add('hover:bg-slate-900/60', 'text-slate-400');
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabId);
            btn.classList.remove('hover:bg-slate-900/60', 'text-slate-400');
            btn.classList.add('bg-blue-600/20', 'border-blue-500/40', 'text-blue-400');
        }

        // Toggle Sidebar display
        function toggleSidebar() {
            const sidebar = document.getElementById('workspaceSidebar');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('lg:block');
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
            document.getElementById(`batch-filter-${selectBtnId}`).classList.add('border-blue-500', 'text-blue-400');

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
                    // Refresh view batch designations
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
                document.getElementById(`score-text-series-${reg}`).innerText = `${total.toFixed(2)} / 40`;
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
                    <div class="bg-slate-800/40 p-4 rounded-2xl border border-slate-800">
                        <div class="flex justify-between font-bold text-sm mb-1.5">
                            <span class="text-slate-300 text-xs tracking-wide">${r.label}</span>
                            <span class="text-blue-400 font-mono text-sm" id="modal-val-${r.key}">${currentVal}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="stepSlider('slider-${r.key}', -${r.step})" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-black text-sm flex items-center justify-center">-</button>
                            <input type="range" id="slider-${r.key}" min="0" max="${r.max}" step="${r.step}" value="${currentVal}" oninput="syncModalSlider('${regNo}', '${r.key}', '${tabType}')" class="flex-1 slider-accent">
                            <button type="button" onclick="stepSlider('slider-${r.key}', ${r.step})" class="w-8 h-8 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-black text-sm flex items-center justify-center">+</button>
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
                document.getElementById(`score-text-series-${regNo}`).innerText = `${total.toFixed(2)} / 40`;
            } else if (tabType === 'table23') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0);
                document.getElementById(`score-text-open-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
            } else if (tabType === 'table22') {
                const scores = scoresState[tabType][regNo] || {};
                total = (scores.c1||0) + (scores.c2||0) + (scores.c3||0) + (scores.c4||0) + (scores.c5||0) + (scores.c6||0);
                document.getElementById(`score-text-exp-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
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
            if (activeRow.classList.contains('hidden')) {
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
            document.getElementById(`cia-lab-work-${regNo}`).innerText = scaledLabWork30.toFixed(2);

            // 2. Open Ended (10 M)
            const openScores = scoresState.table23[regNo] || {};
            const openTotal = (openScores.c1||0) + (openScores.c2||0) + (openScores.c3||0) + (openScores.c4||0) + (openScores.c5||0);
            const scaledOpen10 = (openTotal / 50) * 10;
            document.getElementById(`cia-open-${regNo}`).innerText = scaledOpen10.toFixed(2);

            // 3. Series (15 M)
            const s1Scores = scoresState.table31[regNo]['Series 1'] || {};
            const s2Scores = scoresState.table31[regNo]['Series 2'] || {};
            const s1Total = (s1Scores.c1||0) + (s1Scores.c2||0) + (s1Scores.c3||0) + (s1Scores.c4||0) + (s1Scores.c5||0);
            const s2Total = (s2Scores.c1||0) + (s2Scores.c2||0) + (s2Scores.c3||0) + (s2Scores.c4||0) + (s2Scores.c5||0);
            const avgSeries40 = (s1Total + s2Total) / 2;
            const scaledSeries15 = (avgSeries40 / 40) * 15;
            document.getElementById(`cia-series-${regNo}`).innerText = scaledSeries15.toFixed(2);

            // 4. Attendance
            const att = attendanceMarks[regNo] ? attendanceMarks[regNo].mark : 5;

            // CIA Total out of 60
            const totalCIA = scaledLabWork30 + scaledOpen10 + scaledSeries15 + att;
            document.getElementById(`cia-total-${regNo}`).innerText = totalCIA.toFixed(2);
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
                marks[reg] = {
                    title: document.getElementById(`open-title-${reg}`).value || 'Open-ended Project',
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
    </script>
</body>
</html>
