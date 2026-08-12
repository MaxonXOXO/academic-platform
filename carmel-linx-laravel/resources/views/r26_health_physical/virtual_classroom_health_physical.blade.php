<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Health & Physical Virtual Class - {{ $hpCourseFile->course_title }}</title>
    
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
                        },
                        skyGlow: '#00f5a0'
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
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glow-sky {
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.25);
        }
        .mark-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            background: #1e293b;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 4px;
        }
        .mark-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: #38bdf8;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.8);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen antialiased">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-800/80 px-6 py-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-graduation-cap text-sky-400 text-base"></i>
                    <span class="font-extrabold text-white text-sm tracking-tight">Carmel Linx</span>
                    <span class="text-slate-600 font-bold">|</span>
                </div>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-xl font-bold font-display tracking-tight text-white">
                            {{ $hpCourseFile->course_title }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }} S1 Unique Paper
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Course Code: <span class="font-mono text-slate-200 font-medium">{{ $hpCourseFile->course_code }}</span> | 
                        Semester: <span class="text-sky-400 font-semibold">{{ $hpCourseFile->semester }}</span> | 
                        Credits: <span class="text-slate-200">{{ $hpCourseFile->credits }}</span> | 
                        CIE: 60M | ESE: 40M
                    </p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="document.getElementById('uploadSyllabusModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs flex items-center space-x-2 shadow-lg shadow-sky-600/20 transition">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Upload Syllabus PDF</span>
                </button>
                <a href="/dashboard/lecturer" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 font-bold text-[11px] transition border border-rose-500/30 flex items-center space-x-1.5 no-underline" title="Dashboard">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Workspace Container -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Tab Controls -->
        <div class="flex space-x-2 border-b border-slate-800 pb-3 mb-8 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-overview')" id="btn-tab-overview" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold transition bg-sky-500/20 text-sky-300 border border-sky-500/30">
                <i class="fa-solid fa-heart-pulse mr-2"></i>Course Overview & Rubrics
            </button>
            <button type="button" onclick="switchTab('tab-copo')" id="btn-tab-copo" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-table-cells mr-2"></i>CO-PO Matrix
            </button>
            <button type="button" onclick="switchTab('tab-lesson')" id="btn-tab-lesson" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-calendar-days mr-2"></i>30-Hour Plan
            </button>
            <button type="button" onclick="switchTab('tab-activity')" id="btn-tab-activity" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-person-running mr-2"></i>Continuous Fitness Log (30M)
            </button>
            <button type="button" onclick="switchTab('tab-fitness')" id="btn-tab-fitness" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-dumbbell mr-2"></i>Fitness & Skill Tests (15M)
            </button>
            <button type="button" onclick="switchTab('tab-summary')" id="btn-tab-summary" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-trophy mr-2"></i>Consolidated CIE & ESE (100M)
            </button>
            <button type="button" onclick="switchTab('tab-surveys')" id="btn-tab-surveys" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition">
                <i class="fa-solid fa-chart-pie mr-2"></i>Surveys & Attainment
            </button>
        </div>

        <!-- TAB 1: Overview & Dynamic Rubric Titles from PDF -->
        <div id="tab-overview" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Course Info Box -->
                <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                        <i class="fa-solid fa-circle-info text-sky-400 mr-2"></i>Course Specifications
                    </h3>
                    <dl class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Course Code</dt>
                            <dd class="font-mono text-sky-300 font-semibold">{{ $hpCourseFile->course_code }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Teaching Scheme (L:T:P:R)</dt>
                            <dd class="font-mono text-slate-200">{{ $hpCourseFile->teaching_scheme }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Total Instructional Hours</dt>
                            <dd class="font-bold text-white">{{ $hpCourseFile->contact_hours }} Hours</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800">
                            <dt class="text-slate-400">Credits</dt>
                            <dd class="font-bold text-sky-400">{{ $hpCourseFile->credits }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <dt class="text-slate-400">Assessment Breakdown</dt>
                            <dd class="text-slate-200 font-medium">60% CIE + 40% ESE</dd>
                        </div>
                    </dl>
                </div>

                <!-- Parsed Assessment Criteria (Dynamic PDF Split-Up) -->
                <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-800 glow-sky">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-sliders text-sky-400 mr-2"></i>Continuous Evaluation Criteria (Extracted from PDF)
                        </h3>
                        <span class="text-xs bg-sky-500/20 text-sky-300 px-2.5 py-1 rounded-full font-medium border border-sky-500/30">
                            Dynamic Table Headers Active
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($evalScheme['day_work'] as $crit)
                        <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-sky-400 font-mono uppercase">{{ strtoupper($crit['key']) }}</span>
                                <h4 class="text-xs font-medium text-slate-200 mt-0.5">{{ $crit['title'] }}</h4>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-sky-950 text-sky-300 border border-sky-800/50">
                                {{ $crit['max_marks'] }} Marks
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Course Outcomes Table -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center">
                    <i class="fa-solid fa-graduation-cap text-sky-400 mr-2"></i>Course Outcomes (COs)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800">
                                <th class="p-3 w-20">CO ID</th>
                                <th class="p-3">Course Outcome Description</th>
                                <th class="p-3 w-32 text-center">Cognitive Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($hpCourseFile->parsed_cos as $co)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 font-bold font-mono text-sky-400">{{ $co['id'] }}</td>
                                <td class="p-3 text-slate-300">{{ $co['description'] }}</td>
                                <td class="p-3 text-center font-medium">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-800 text-slate-200 text-xs">
                                        {{ $co['cognitive_level'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: CO-PO Matrix -->
        <div id="tab-copo" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">CO-PO Articulation Matrix</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 font-bold border-b border-slate-800">
                                <th class="p-3 text-left">CO / PO</th>
                                @for($p=1; $p<=11; $p++)
                                <th class="p-2 text-center">PO{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                            <tr>
                                <td class="p-3 font-bold font-mono text-sky-400 bg-slate-900/50">{{ $coTag }}</td>
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

        <!-- TAB 3: 30-Hour Plan -->
        <div id="tab-lesson" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">30-Hour Physical Activity Schedule</h3>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i><span>Print Schedule</span>
                        </a>
                        <button type="button" onclick="saveLessonPlan()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Plan Updates
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 uppercase border-b border-slate-800">
                                <th class="p-3 w-16 text-center">Hour</th>
                                <th class="p-3 w-24 text-center">CO Tag</th>
                                <th class="p-3">Topic / Activity Description</th>
                                <th class="p-3 w-36 text-center">Proposed Date</th>
                                <th class="p-3 w-36 text-center">Actual Date</th>
                                <th class="p-3 w-28 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($lessonPlans as $lp)
                            @php $isSeriesTest = str_contains(strtolower($lp->topic_content), 'series test'); @endphp
                            <tr class="{{ $isSeriesTest ? 'bg-sky-950/40 border-l-4 border-sky-400' : 'hover:bg-slate-900/50' }}">
                                <td class="p-3 text-center font-bold font-mono text-slate-300 text-sm">{{ $lp->day_no }}</td>
                                <td class="p-3 text-center font-mono text-sky-400 font-bold text-sm">
                                    {{ $lp->co_id }}
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center space-x-2">
                                        @if($isSeriesTest)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-sky-500/30 text-sky-200 uppercase border border-sky-400/40">Test</span>
                                        @endif
                                        <input type="text" value="{{ $lp->topic_content }}" id="topic_{{ $lp->id }}" class="w-full bg-slate-900 text-slate-200 border border-slate-800 rounded px-2.5 py-1 text-sm focus:border-sky-500 {{ $isSeriesTest ? 'font-bold text-sky-300' : '' }}">
                                    </div>
                                </td>
                                <td class="p-3 text-center">
                                    <input type="date" value="{{ $lp->proposed_date }}" id="pdate_{{ $lp->id }}" class="bg-slate-900 text-slate-200 border border-slate-800 rounded px-2 py-1 text-sm focus:border-sky-500 font-mono">
                                </td>
                                <td class="p-3 text-center">
                                    <input type="date" value="{{ $lp->actual_date }}" id="adate_{{ $lp->id }}" class="bg-slate-900 text-slate-200 border border-slate-800 rounded px-2 py-1 text-sm focus:border-sky-500 font-mono">
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $lp->status === 'Completed' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : ($isSeriesTest ? 'bg-sky-500/30 text-sky-200 border border-sky-400/50' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30') }}">
                                        {{ $lp->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: Continuous Fitness & Activity Log (Dynamic Titles from Uploaded PDF) -->
        <div id="tab-activity" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Continuous Activity & Fitness Log</h3>
                        <p class="text-xs text-slate-400 mt-1">Headers & Criteria titles are dynamically rendered from the uploaded syllabus PDF.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/activity-log" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i><span>Print Log</span>
                        </a>
                        <button type="button" onclick="saveActivityMarks()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Evaluation Marks
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3 w-48">Student Name</th>
                                @foreach($evalScheme['day_work'] as $crit)
                                <th class="p-3 text-center" title="{{ $crit['title'] }}">
                                    {{ $crit['title'] }} <br>
                                    <span class="text-sky-400 font-normal">({{ $crit['max_marks'] }}M)</span>
                                </th>
                                @endforeach
                                <th class="p-3 text-center bg-sky-950/50 text-sky-300 w-24">Total (50M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $idx => $student)
                            @php
                                $stEval = $activityEvals->get($student->reg_no, collect())->first();
                            @endphp
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-sky-400">{{ $student->reg_no }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $student->name }}</td>
                                @foreach($evalScheme['day_work'] as $crit)
                                @php $k = $crit['key']; $val = $stEval ? ($stEval->$k ?? 0) : 0; @endphp
                                <td class="p-3 text-center min-w-[140px]">
                                    <div class="flex flex-col items-center">
                                        <input type="number" step="0.5" max="{{ $crit['max_marks'] }}" min="0"
                                               id="m_{{ $student->reg_no }}_{{ $k }}"
                                               value="{{ $val }}"
                                               oninput="syncSlider('{{ $student->reg_no }}', '{{ $k }}', this.value)"
                                               onchange="calcTotal('{{ $student->reg_no }}')"
                                               class="w-16 bg-slate-900 text-center rounded border border-slate-700 text-sm py-1 font-mono text-slate-100 focus:border-sky-500 crit-input-{{ $student->reg_no }}"
                                               data-max="{{ $crit['max_marks'] }}">
                                        <input type="range" step="0.5" max="{{ $crit['max_marks'] }}" min="0"
                                               id="s_{{ $student->reg_no }}_{{ $k }}"
                                               value="{{ $val }}"
                                               oninput="syncInput('{{ $student->reg_no }}', '{{ $k }}', this.value)"
                                               class="mark-slider w-28">
                                    </div>
                                </td>
                                @endforeach
                                <td class="p-3 text-center font-bold text-sm text-sky-400 bg-sky-950/20" id="tot_{{ $student->reg_no }}">
                                    {{ $stEval ? number_format($stEval->total_score_50, 1) : '0.0' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 5: Physical Fitness Tests CA1 & CA2 -->
        <div id="tab-fitness" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Physical Fitness & Skill Tests (CA1 / CA2)</h3>
                    <div class="flex items-center space-x-2">
                        <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/fitness-tests" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i><span>Print Tests</span>
                        </a>
                        <button type="button" onclick="saveFitnessTestMarks()" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Test Scores
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center w-44">CA1 Fitness Test (40M)</th>
                                <th class="p-3 text-center w-44">CA2 Skill Demo (40M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($students as $idx => $student)
                            @php
                                $stTests = $fitnessTests->get($student->reg_no, collect());
                                $ca1 = $stTests->where('test_no', 'CA1')->first();
                                $ca2 = $stTests->where('test_no', 'CA2')->first();
                                $ca1Val = $ca1 ? $ca1->total_score_40 : 0;
                                $ca2Val = $ca2 ? $ca2->total_score_40 : 0;
                            @endphp
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-sky-400 text-sm">{{ $student->reg_no }}</td>
                                <td class="p-3 text-slate-200 font-medium text-sm">{{ $student->name }}</td>
                                <td class="p-3 text-center">
                                    <div class="flex flex-col items-center">
                                        <input type="number" step="0.5" max="40" min="0" id="ca1_{{ $student->reg_no }}" value="{{ $ca1Val }}" oninput="syncFitnessSlider('ca1', '{{ $student->reg_no }}', this.value)" class="w-20 bg-slate-900 text-center rounded border border-slate-700 py-1 text-sm text-slate-100 focus:border-sky-500 font-mono">
                                        <input type="range" step="0.5" max="40" min="0" id="s_ca1_{{ $student->reg_no }}" value="{{ $ca1Val }}" oninput="syncFitnessInput('ca1', '{{ $student->reg_no }}', this.value)" class="mark-slider w-28">
                                    </div>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex flex-col items-center">
                                        <input type="number" step="0.5" max="40" min="0" id="ca2_{{ $student->reg_no }}" value="{{ $ca2Val }}" oninput="syncFitnessSlider('ca2', '{{ $student->reg_no }}', this.value)" class="w-20 bg-slate-900 text-center rounded border border-slate-700 py-1 text-sm text-slate-100 focus:border-sky-500 font-mono">
                                        <input type="range" step="0.5" max="40" min="0" id="s_ca2_{{ $student->reg_no }}" value="{{ $ca2Val }}" oninput="syncFitnessInput('ca2', '{{ $student->reg_no }}', this.value)" class="mark-slider w-28">
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 6: Consolidated CIE & ESE Summary -->
        <div id="tab-summary" class="tab-content hidden">
            <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Consolidated CIE (60M) + ESE (40M) Marksheet</h3>
                    <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/consolidated" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex items-center space-x-1.5">
                        <i class="fa-solid fa-print"></i><span>Print Consolidated Register</span>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-800">
                        <thead>
                            <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                <th class="p-3 w-12 text-center">#</th>
                                <th class="p-3 w-32 font-mono">Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Att (5M)</th>
                                <th class="p-3 text-center">Continuous (30M)</th>
                                <th class="p-3 text-center">Tests (15M)</th>
                                <th class="p-3 text-center font-bold text-sky-400 bg-slate-900">Total CIE (60M)</th>
                                <th class="p-3 text-center">ESE (40M)</th>
                                <th class="p-3 text-center font-bold text-white bg-slate-900">Grand Total (100M)</th>
                                <th class="p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($studentResults as $idx => $res)
                            <tr class="hover:bg-slate-900/50">
                                <td class="p-3 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                <td class="p-3 font-mono font-medium text-sky-400">{{ $res['reg_no'] }}</td>
                                <td class="p-3 text-slate-200 font-medium">{{ $res['name'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['att_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['activity_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['test_marks'] }}</td>
                                <td class="p-3 text-center font-bold text-sky-400 font-mono bg-sky-950/20">{{ $res['total_cie_marks'] }}</td>
                                <td class="p-3 text-center text-slate-300 font-mono">{{ $res['total_ese'] }}</td>
                                <td class="p-3 text-center font-bold text-white font-mono bg-slate-900">{{ $res['total_course_marks'] }}</td>
                                <td class="p-3 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $res['is_passed'] ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                        {{ $res['is_passed'] ? 'PASS' : 'FAIL' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 7: Surveys & Indirect Attainment -->
        <div id="tab-surveys" class="tab-content hidden">
            <div class="space-y-6">
                <!-- Dual Survey Activation & Preview Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mid-Semester Survey Card -->
                    <div class="glass-panel p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                                    <i class="fa-solid fa-comments text-amber-400 mr-2"></i>Mid-Semester Survey
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                    {{ isset($midSemSurvey) && $midSemSurvey ? $midSemSurvey->status : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mb-4">Mid-Term feedback on teaching pace, practical demonstrations, equipment availability, and safety protocols.</p>
                        </div>
                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-800">
                            <button type="button" onclick="openPreviewModal('previewMidSemModal')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex-1 flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-eye"></i><span>Preview Questionnaire</span>
                            </button>
                            @if(isset($midSemSurvey) && $midSemSurvey && $midSemSurvey->status === 'Active')
                                <button type="button" onclick="closeMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-medium text-xs flex-1">
                                    <i class="fa-solid fa-lock mr-1"></i>Close Survey
                                </button>
                            @else
                                <button type="button" onclick="initiateMidSemSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs flex-1">
                                    <i class="fa-solid fa-paper-plane mr-1"></i>Activate Survey
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- End-Semester Course Exit Survey Card -->
                    <div class="glass-panel p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                                    <i class="fa-solid fa-graduation-cap text-sky-400 mr-2"></i>End-Semester Course Exit Survey
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                    {{ isset($exitSurvey) && $exitSurvey ? $exitSurvey->status : 'Inactive' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mb-4">Comprehensive end-of-course survey evaluated on High (L3), Medium (L2), Low (L1) scale for Indirect CO Attainment (20%).</p>
                        </div>
                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-800">
                            <button type="button" onclick="openPreviewModal('previewExitSurveyModal')" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex-1 flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-eye"></i><span>Preview Questionnaire</span>
                            </button>
                            @if(isset($exitSurvey) && $exitSurvey && $exitSurvey->status === 'Active')
                                <button type="button" onclick="closeExitSurvey()" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-medium text-xs flex-1">
                                    <i class="fa-solid fa-lock mr-1"></i>Close Survey
                                </button>
                            @else
                                <button type="button" onclick="initiateExitSurvey()" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs flex-1">
                                    <i class="fa-solid fa-paper-plane mr-1"></i>Activate Survey
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Anonymous Survey Results & Questionnaire Report -->
                <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                                <i class="fa-solid fa-chart-column text-sky-400 mr-2"></i>Anonymous Survey Response Breakdown & 3-Level Evaluation
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">Student names are excluded for strict anonymity. Response totals & 3-level scale ratings are summarized below.</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/survey-report" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium text-xs border border-slate-700 flex items-center space-x-1.5">
                                <i class="fa-solid fa-print"></i><span>Print Survey Report</span>
                            </a>
                            <a href="/r26/classroom/health-physical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-medium text-xs flex items-center space-x-1.5 shadow-lg shadow-sky-600/20">
                                <i class="fa-solid fa-file-invoice"></i><span>Print CO-PO Attainment</span>
                            </a>
                        </div>
                    </div>

                    <!-- Response Stats Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 text-center">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Enrolled Students</span>
                            <span class="text-lg font-bold text-white font-mono">{{ $students->count() }}</span>
                        </div>
                        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 text-center">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Responses Submitted</span>
                            <span class="text-lg font-bold text-sky-400 font-mono">{{ $exitSurveyResponses->count() }}</span>
                        </div>
                        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 text-center">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Response Rate</span>
                            <span class="text-lg font-bold text-emerald-400 font-mono">{{ $students->count() > 0 ? round(($exitSurveyResponses->count() / $students->count()) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 text-center">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Evaluation Scale</span>
                            <span class="text-xs font-bold text-sky-300 uppercase">3-Level (High/Med/Low)</span>
                        </div>
                    </div>

                    <!-- Direct (80%) vs Indirect (20%) Combined CO Attainment Table -->
                    <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">Direct (80%) + Indirect (20%) CO Attainment Level Matrix</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-xs text-left border border-slate-800">
                            <thead>
                                <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                    <th class="p-3">CO Tag</th>
                                    <th class="p-3 text-center">Direct Attainment (80%)</th>
                                    <th class="p-3 text-center">Indirect Attainment (20%)</th>
                                    <th class="p-3 text-center bg-sky-950/60 text-sky-300">Combined CO Level (1.0 - 3.0)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                @php
                                    $d = $directStats[$coTag] ?? ['level' => 0, 'percentage' => 0];
                                    $ind = $indirectStats[$coTag] ?? ['level' => 0, 'rating' => '-'];
                                    $comb = $combinedStats[$coTag] ?? 0;
                                @endphp
                                <tr class="hover:bg-slate-900/50">
                                    <td class="p-3 font-bold font-mono text-sky-400 text-sm">{{ $coTag }}</td>
                                    <td class="p-3 text-center font-mono text-slate-300">Level {{ $d['level'] }} ({{ $d['percentage'] }}%)</td>
                                    <td class="p-3 text-center font-mono text-slate-300">Level {{ $ind['level'] }} ({{ $ind['rating'] }})</td>
                                    <td class="p-3 text-center font-bold font-mono text-sky-300 text-sm bg-sky-950/30">{{ number_format($comb, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Visual CO Attainment Bar Graph -->
                    <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">Indirect CO Attainment Graphical Level Distribution</h4>
                    <div class="space-y-3 bg-slate-900/40 p-4 rounded-xl border border-slate-800">
                        @foreach(['CO1' => 'CO1 - Health & Posture Principles', 'CO2' => 'CO2 - Fitness & Warming-up Drills', 'CO3' => 'CO3 - Major Games & Athletic Skills', 'CO4' => 'CO4 - Yoga, Stress Relief & First Aid'] as $cKey => $cTitle)
                        @php
                            $indObj = $indirectStats[$cKey] ?? ['avg_score' => 2.5, 'percentage' => 83.3, 'level' => 3.0];
                            $cPct = $indObj['percentage'];
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                                <span>{{ $cTitle }}</span>
                                <span class="font-mono text-sky-400">{{ $indObj['avg_score'] }} / 3.0 ({{ $cPct }}%) - Level {{ $indObj['level'] }}</span>
                            </div>
                            <div class="w-full bg-slate-800 h-3 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-sky-500 to-sky-400 h-full rounded-full transition-all duration-500" style="width: {{ $cPct }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- PO Attainment Level Summary Table -->
                <div class="glass-panel p-6 rounded-2xl border border-slate-800">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Program Outcome (PO) Attainment Summary</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border border-slate-800">
                            <thead>
                                <tr class="bg-slate-900 text-slate-200 font-bold uppercase border-b border-slate-800">
                                    @for($p=1; $p<=11; $p++)
                                    <th class="p-3 text-center">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for($p=1; $p<=11; $p++)
                                    @php $poVal = $poAttainments["PO{$p}"]['value'] ?? 0.0; @endphp
                                    <td class="p-3 text-center font-bold font-mono text-sm {{ $poVal > 0 ? 'text-sky-300 bg-sky-950/30' : 'text-slate-600' }}">
                                        {{ number_format($poVal, 2) }}
                                    </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Upload Syllabus PDF Modal -->
    <div id="uploadSyllabusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-file-pdf text-sky-400 mr-2"></i>Upload Health & Physical PDF
                </h3>
                <button onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="uploadSyllabusForm" onsubmit="uploadSyllabusPdf(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Select Syllabus PDF File</label>
                    <input type="file" name="syllabus_file" accept=".pdf" required class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-600 file:text-white hover:file:bg-sky-500 bg-slate-900 border border-slate-800 rounded-xl">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('uploadSyllabusModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-medium hover:bg-slate-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-medium shadow-lg shadow-sky-600/20">
                        Upload & Extract Splitup Titles
                    </button>
                </div>
            </form>
    <!-- Preview Mid-Sem Survey Modal -->
    <div id="previewMidSemModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-xl w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-comments text-amber-400 mr-2"></i>Mid-Semester Survey Questionnaire
                </h3>
                <button onclick="closePreviewModal('previewMidSemModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
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
                <button type="button" onclick="closePreviewModal('previewMidSemModal')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-medium hover:bg-slate-700">Close</button>
            </div>
        </div>
    </div>

    <!-- Preview End-Semester Exit Survey Modal -->
    <div id="previewExitSurveyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 max-w-2xl w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-graduation-cap text-sky-400 mr-2"></i>End-Semester Course Exit Survey Questionnaire (High/Med/Low)
                </h3>
                <button onclick="closePreviewModal('previewExitSurveyModal')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
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
                    <span class="text-sky-400 font-bold font-mono">CO4 (Q7):</span> How effectively can you perform yogic asanas and relaxation techniques?
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO4 (Q8):</span> Rate your competence in first aid procedures and CPR fundamentals.
                </div>
                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-800">
                    <span class="text-sky-400 font-bold font-mono">CO4 (Q9):</span> Rate your overall improvement in physical fitness and logbook maintenance.
                </div>
            </div>
            <div class="flex justify-end pt-4">
                <button type="button" onclick="closePreviewModal('previewExitSurveyModal')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-medium hover:bg-slate-700">Close</button>
            </div>
        </div>
    </div>

    <!-- JavaScript Handlers -->
    <script>
        const subjectId = "{{ $batchSubject->id }}";

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = "tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-slate-200 transition";
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.className = "tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold transition bg-sky-500/20 text-sky-300 border border-sky-500/30";
        }

        function calcTotal(regNo) {
            let inputs = document.querySelectorAll('.crit-input-' + regNo);
            let sum = 0;
            inputs.forEach(inp => {
                sum += parseFloat(inp.value || 0);
            });
            document.getElementById('tot_' + regNo).innerText = sum.toFixed(1);
        }

        function syncSlider(regNo, key, val) {
            const slider = document.getElementById(`s_${regNo}_${key}`);
            if (slider) slider.value = val;
            calcTotal(regNo);
        }

        function syncInput(regNo, key, val) {
            const input = document.getElementById(`m_${regNo}_${key}`);
            if (input) input.value = val;
            calcTotal(regNo);
        }

        function syncFitnessSlider(testNo, regNo, val) {
            const slider = document.getElementById(`s_${testNo}_${regNo}`);
            if (slider) slider.value = val;
        }

        function syncFitnessInput(testNo, regNo, val) {
            const input = document.getElementById(`${testNo}_${regNo}`);
            if (input) input.value = val;
        }

        async function uploadSyllabusPdf(e) {
            e.preventDefault();
            const form = document.getElementById('uploadSyllabusForm');
            const formData = new FormData(form);

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/syllabus`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Upload failed: ' + err.message);
            }
        }

        async function saveActivityMarks() {
            const students = @json($students->pluck('reg_no'));
            const keys = @json(collect($evalScheme['day_work'])->pluck('key'));

            let marksData = [];
            students.forEach(regNo => {
                let row = { reg_no: regNo };
                keys.forEach(k => {
                    const el = document.getElementById(`m_${regNo}_${k}`);
                    row[k] = el ? parseFloat(el.value || 0) : 0;
                });
                marksData.push(row);
            });

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/activity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        activity_no: 'ACT-LOG',
                        activity_title: 'Continuous Fitness & Activity Evaluation',
                        marks_data: marksData
                    })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }

        async function saveFitnessTestMarks() {
            const students = @json($students->pluck('reg_no'));
            let ca1Data = [], ca2Data = [];

            students.forEach(regNo => {
                const ca1El = document.getElementById(`ca1_${regNo}`);
                const ca2El = document.getElementById(`ca2_${regNo}`);
                ca1Data.push({ reg_no: regNo, total_score_40: ca1El ? parseFloat(ca1El.value || 0) : 0 });
                ca2Data.push({ reg_no: regNo, total_score_40: ca2El ? parseFloat(ca2El.value || 0) : 0 });
            });

            try {
                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ test_no: 'CA1', marks_data: ca1Data })
                });

                await fetch(`/api/r26/classroom/health-physical/${subjectId}/evaluate/fitness-test`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ test_no: 'CA2', marks_data: ca2Data })
                });

                alert('Physical Fitness Test scores saved successfully!');
                window.location.reload();
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }

        async function saveLessonPlan() {
            const plans = {};
            @foreach($lessonPlans as $lp)
            plans[{{ $lp->id }}] = {
                topic_content: document.getElementById('topic_{{ $lp->id }}').value,
                proposed_date: document.getElementById('pdate_{{ $lp->id }}').value,
                actual_date: document.getElementById('adate_{{ $lp->id }}').value,
                co_tag: '{{ $lp->co_id }}'
            };
            @endforeach

            try {
                const res = await fetch(`/api/r26/classroom/health-physical/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message || 'Lesson plan updated!');
                window.location.reload();
            } catch (err) {
                alert('Save failed: ' + err.message);
            }
        }

        function openPreviewModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closePreviewModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        async function initiateMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function closeMidSemSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/mid-sem/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function initiateExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }

        async function closeExitSurvey() {
            try {
                const res = await fetch(`/api/classroom/${subjectId}/course-exit/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                alert(data.message);
                window.location.reload();
            } catch (err) { alert('Action failed: ' + err.message); }
        }
    </script>
</body>
</html>
