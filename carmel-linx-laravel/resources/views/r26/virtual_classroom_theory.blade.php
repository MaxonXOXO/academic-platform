<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Virtual Classroom (Theory) - {{ $batchSubject->subject_name }}</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons & Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- KaTeX for Mathematical Expressions ($...$ and $$...$$) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>

  <!-- Flatpickr for dd/mm/yyyy Date Selection -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    /* Active Attendance Override Styling - border only on entry field, no td border */
    .cell-att-override.has-override {
      border-color: #f59e0b !important;
      background-color: rgba(245, 158, 11, 0.15) !important;
      color: #fbbf24 !important;
      box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.35) !important;
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
      appearance: textfield !important;
    }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Flatpickr dark theme adjustments */
    .flatpickr-calendar {
      background: #0f172a !important;
      border: 1px solid #334155 !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.7) !important;
      border-radius: 12px !important;
    }
    .flatpickr-calendar .flatpickr-months {
      background: #0f172a !important;
      border-bottom: 1px solid #1e293b !important;
    }
    .flatpickr-calendar .flatpickr-current-month {
      color: #f1f5f9 !important;
      font-weight: 700 !important;
    }
    .flatpickr-calendar .flatpickr-weekday {
      color: #94a3b8 !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-day {
      color: #e2e8f0 !important;
      border-radius: 8px !important;
    }
    .flatpickr-calendar .flatpickr-day:hover {
      background: #1e293b !important;
      color: #38bdf8 !important;
    }
    .flatpickr-calendar .flatpickr-day.selected {
      background: #0284c7 !important;
      border-color: #0284c7 !important;
      color: #ffffff !important;
      font-weight: 700 !important;
    }
    .flatpickr-calendar .flatpickr-day.today {
      border-color: #38bdf8 !important;
    }
    /* Hide up/down spinner arrows on number inputs */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type="number"] {
      -moz-appearance: textfield;
      appearance: textfield;
    }
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
    h1, h2, h3, h4, h5, h6, .font-heading, span, p, label, button, a, th, td {
      text-shadow: none !important;
      filter: none !important;
    }
    body.dark {
      background-color: #0b0f19;
      color: #f1f5f9;
    }
    body.dark .bg-panel {
      background-color: rgba(15, 23, 42, 0.4);
      border-color: rgba(30, 41, 59, 0.8);
    }
    body.dark .text-title {
      color: #f1f5f9;
    }
    body.dark .text-muted {
      color: #94a3b8;
    }
    body.dark .border-card {
      border-color: rgba(30, 41, 59, 0.6);
    }
    body.dark .bg-card-hover:hover {
      background-color: rgba(15, 23, 42, 0.6);
    }

    /* Light Mode */
    body.light {
      background-color: #f8fafc;
      color: #0f172a;
    }
    body.light .bg-panel {
      background-color: #ffffff;
      border-color: #e2e8f0;
      box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
    }
    body.light .text-title {
      color: #0f172a;
    }
    body.light .text-muted {
      color: #475569;
    }
    body.light .border-card {
      border-color: #e2e8f0;
    }
    body.light .bg-card-hover:hover {
      background-color: #f1f5f9;
    }

    /* Custom input/select styles for Light/Dark mode consistency */
    body.dark input, body.dark select, body.dark textarea {
      background-color: #0f172a !important;
      color: #f1f5f9 !important;
      border-color: #334155 !important;
    }
    body.light input, body.light select, body.light textarea {
      background-color: #ffffff !important;
      color: #0f172a !important;
      border-color: #cbd5e1 !important;
    }

    input, select, textarea {
      font-size: 0.8125rem !important; /* 13px compact font */
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.1);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.3);
      border-radius: 9999px;
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    @keyframes gentle-attention {
      0%, 100% { 
        transform: scale(1); 
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
      }
      50% { 
        transform: scale(1.04); 
        filter: drop-shadow(0 0 4px var(--pulse-color, rgba(14, 165, 233, 0.4)));
      }
    }
    .animate-attention {
      animation: gentle-attention 2s infinite ease-in-out;
      --pulse-color: rgba(56, 189, 248, 0.6);
    }
    #btn-fullscreen-toggle.bg-amber-600 {
      --pulse-color: rgba(245, 158, 11, 0.6);
    }

    /* Static Header and Border Tab Navigation */
    header.bg-static-header {
      background-color: #0b0f19;
      border-color: rgba(30, 41, 59, 0.8);
    }
    body.light header.bg-static-header {
      background-color: #ffffff;
      border-color: #e2e8f0;
    }
    .tab-btn {
      position: relative;
      white-space: nowrap;
    }
    body.light .tab-nav-container {
      background-color: #ffffff !important;
      border-color: #e2e8f0 !important;
    }
    body.light .tab-btn.border-blue-400 {
      color: #2563eb !important;
      border-color: #3b82f6 !important;
      background-color: rgba(59, 130, 246, 0.08) !important;
      box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.15) !important;
    }
    body.light .tab-btn.border-transparent {
      color: #64748b !important;
    }
    body.light .tab-btn.border-transparent:hover {
      color: #0f172a !important;
      border-color: #cbd5e1 !important;
      background-color: rgba(241, 245, 249, 0.8) !important;
    }
  </style>
</head>
<body class="dark h-screen flex flex-col overflow-hidden bg-[#0b0f19] text-[#f1f5f9]">

  @php
    $copoData = is_array($courseFile->parsed_copo) ? $courseFile->parsed_copo : (json_decode($courseFile->parsed_copo, true) ?: []);
    $cieMarks = $copoData['cie_marks'] ?? 40;
    $eseMarks = $copoData['ese_marks'] ?? 60;
    $credit = $copoData['credit'] ?? 4;
    $ltpr = $copoData['l_t_p_r'] ?? '3:1:0:0';
    $totalHours = $copoData['total_hours'] ?? 60;
    $mappings = $copoData['mappings'] ?? [];
    $cosList = is_array($courseFile->parsed_cos) ? $courseFile->parsed_cos : (json_decode($courseFile->parsed_cos, true) ?: []);
    $modulesList = is_array($courseFile->parsed_modules) ? $courseFile->parsed_modules : (json_decode($courseFile->parsed_modules, true) ?: []);
    $textbooksList = is_array($courseFile->parsed_textbooks) ? $courseFile->parsed_textbooks : (json_decode($courseFile->parsed_textbooks, true) ?: []);

    // Official Revision 2026 Diploma Curriculum Standards (Course 1002 / Theory R2026)
    $r26MathCos = [
      [
        'id' => 'CO1',
        'cognitive_level' => 'Apply',
        'duration' => 15,
        'description' => 'Apply the concepts of matrices and determinants to solve linear systems of equations involving two and three unknowns.'
      ],
      [
        'id' => 'CO2',
        'cognitive_level' => 'Apply',
        'duration' => 15,
        'description' => 'Use trigonometric identities and functions to solve trigonometric problems.'
      ],
      [
        'id' => 'CO3',
        'cognitive_level' => 'Apply',
        'duration' => 15,
        'description' => 'Interpret geometric problems related to straight lines and circles using Coordinate geometric concepts.'
      ],
      [
        'id' => 'CO4',
        'cognitive_level' => 'Apply',
        'duration' => 15,
        'description' => 'Evaluate limits and derivatives.'
      ]
    ];

    $r26MathModules = [
      [
        'module_id' => 'I',
        'title' => 'Matrices and Determinants',
        'hours' => 15,
        'lecture_hours' => 11,
        'tutorial_hours' => 4,
        'content' => 'Basic concept of a matrix, Types of matrices, Transpose, Algebra of matrices, Determinants, Minors, Cofactors, Adjoint and Inverse of second-order matrices, Solution of system of linear equations involving two and three unknowns using Cramer’s rule.'
      ],
      [
        'module_id' => 'II',
        'title' => 'Trigonometry',
        'hours' => 15,
        'lecture_hours' => 11,
        'tutorial_hours' => 4,
        'content' => 'Angles, Trigonometric ratios, ASTC rule, Reduction formula, Pythagorean relations, Trigonometric ratios of compound angles.'
      ],
      [
        'module_id' => 'III',
        'title' => 'Coordinate Geometry',
        'hours' => 15,
        'lecture_hours' => 11,
        'tutorial_hours' => 4,
        'content' => 'Straight lines: Slope, Slope-point form, Angle between lines, Parallel and perpendicular lines. Circles: Equation of a circle, Center and radius of a given circle (x² + y² + 2gx + 2fy + c = 0).'
      ],
      [
        'module_id' => 'IV',
        'title' => 'Differential Calculus',
        'hours' => 15,
        'lecture_hours' => 12,
        'tutorial_hours' => 3,
        'content' => 'Limits, Basic concept of differentiation, Standard results, Rules of differentiation, Second derivatives.'
      ]
    ];

    // If parsed COs are generic placeholders or empty, populate with official R2026 syllabus
    $isDefaultCo = empty($cosList) || (isset($cosList[0]['description']) && str_contains($cosList[0]['description'], 'core concepts of the subject'));
    if ($isDefaultCo && (str_contains(strtolower($batchSubject->subject_name), 'math') || $batchSubject->subject_code == '1002')) {
      $cosList = $r26MathCos;
    }

    $isDefaultMod = empty($modulesList) || (isset($modulesList[0]['content']) && str_contains($modulesList[0]['content'], 'Module 1 Course Contents'));
    if ($isDefaultMod && (str_contains(strtolower($batchSubject->subject_name), 'math') || $batchSubject->subject_code == '1002')) {
      $modulesList = $r26MathModules;
    }

    if (empty($mappings)) {
      $mappings = [
        'CO1' => ['PO1' => '3'],
        'CO2' => ['PO1' => '3'],
        'CO3' => ['PO1' => '3'],
        'CO4' => ['PO1' => '3']
      ];
    }
  @endphp

  <!-- TOP STATIC HEADER: FIXED/PINNED AT TOP -->
  <header class="shrink-0 z-30 w-full bg-static-header border-b border-slate-700/60 dark:border-slate-800/80 px-4 sm:px-6 pt-2 pb-2.5 shadow-sm space-y-2">
    
    <!-- TOP LOGO & CONTROLS HEADER BAR -->
    <div class="flex flex-wrap justify-between items-center bg-panel border rounded-xl px-3 py-1.5 gap-2 shadow-sm">
      <!-- Left: Logo & Virtual Theory Classroom - R 2026 -->
      <div class="flex items-center gap-2.5">
        <img src="/logo.jpg" class="w-8 h-8 rounded-lg object-cover shadow-sm">
        <div>
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-black uppercase tracking-wider text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/30">Carmel Linx</span>
            <span class="text-base sm:text-lg font-extrabold tracking-tight text-title">Virtual Theory Classroom R - 2026</span>
            @php $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled(); @endphp
            @if($isAiActive)
              <span class="px-1.5 py-0.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded text-[9.5px] font-bold inline-flex items-center gap-1 shadow-2xs" title="Gemini AI Active"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span> AI Active</span>
            @else
              <span class="px-1.5 py-0.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded text-[9.5px] font-bold inline-flex items-center gap-1 shadow-2xs" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline</span>
            @endif
          </div>
          <p class="text-[10px] text-muted font-bold uppercase tracking-wider leading-none mt-0.5">Lecturer Console • Carmel Polytechnic College</p>
        </div>
      </div>

      <!-- Right: Mode Toggle, Lecturer Profile & Back Button -->
      <div class="flex items-center gap-1.5">
        <!-- Auto-Save Pulse Indicator -->
        <div id="global-save-pill" class="hidden items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
          <span id="save-status-text">Saved</span>
        </div>

        <!-- Dark/Light Mode Toggle -->
        <button onclick="toggleTheme()" class="p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all cursor-pointer border border-slate-700/50" title="Toggle Dark/Light Mode">
          <span id="theme-icon" class="material-symbols-rounded text-xs block">light_mode</span>
        </button>

        <!-- Lecturer Profile -->
        <div class="flex items-center gap-1.5 border-l border-slate-700/60 pl-1.5">
          <div class="relative shrink-0">
            @if(Session::get('userPhoto'))
              <img src="{{ Session::get('userPhoto') }}" class="w-5 h-5 rounded-full object-cover border border-slate-700 shadow-xs" alt="{{ Session::get('userName', 'Antony Varghese') }}">
            @else
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100" class="w-5 h-5 rounded-full object-cover border border-slate-700 shadow-xs" alt="{{ Session::get('userName', 'Antony Varghese') }}">
            @endif
            <span class="absolute bottom-0 right-0 w-1.5 h-1.5 bg-emerald-500 border border-slate-900 rounded-full shadow-xs" title="Online"></span>
          </div>
          <div class="hidden sm:block text-left">
            <p class="text-[10px] font-bold text-title leading-tight">{{ Session::get('userName', 'Antony Varghese') }}</p>
          </div>
        </div>

        <button onclick="toggleSidebarWideMode()" id="btn-fullscreen-toggle" class="p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all border border-slate-700/60 cursor-pointer flex items-center justify-center" title="Fullscreen">
          <span class="material-symbols-rounded text-xs">fullscreen</span>
        </button>
        @php
          $role = Session::get('userRole');
          $backUrl = '/dashboard/lecturer';
          if ($role === 'HOD') $backUrl = '/dashboard/hod';
          elseif ($role === 'Admin') $backUrl = '/dashboard/admin';
          elseif ($role === 'Principal') $backUrl = '/dashboard/principal';
          elseif ($role === 'Super_Admin') $backUrl = '/dashboard/superadmin';
          elseif ($role === 'Gen_Dept_Coordinator_Aided') $backUrl = '/dashboard/general-coordinator-aided';
          elseif ($role === 'Gen_Dept_Coordinator_Self_Finance') $backUrl = '/dashboard/general-coordinator-sf';
        @endphp
        <a href="{{ $backUrl }}" onclick="localStorage.removeItem('classroomFullscreen'); window.close(); setTimeout(function(){ window.location.href = '{{ $backUrl }}'; }, 100); return false;" class="p-1 px-1.5 rounded-md bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 transition-all border border-rose-500/30 cursor-pointer flex items-center justify-center no-underline" title="Go Back">
          <span class="material-symbols-rounded text-xs text-rose-400">arrow_back</span>
        </a>
      </div>
    </div>

    <!-- ROW 2: SUBJECT META CARD & EVALUATION STRIP -->
    <div class="bg-panel border rounded-xl px-4 py-2.5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-2 min-h-[46px]">
      <div class="flex flex-wrap items-center gap-2">
        <span class="px-3 py-1 bg-slate-800/90 text-white font-extrabold text-sm sm:text-base rounded-lg border border-slate-700/80 shadow-xs tracking-tight">
          {{ $batchSubject->subject_name }}
          <span class="ml-1 text-sky-400 font-mono font-bold text-xs sm:text-sm">({{ $batchSubject->subject_code }})</span>
        </span>
        <span class="font-bold text-slate-200 text-xs px-2 py-0.5 bg-slate-800/80 rounded-md border border-slate-700/60">Sem {{ $batchSubject->semester }}</span>
        <span class="px-2 py-0.5 bg-amber-500/15 text-amber-300 border border-amber-500/30 rounded-md font-mono text-xs font-bold shadow-xs">{{ $batchSubject->classroom_id }}</span>
      </div>

      <!-- EVALUATION METRICS & ATTENDANCE LINK -->
      <div class="flex flex-wrap items-center gap-2 text-xs font-mono text-muted">
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded-md text-slate-300">CIA: <strong class="text-title text-emerald-400 font-bold">{{ $cieMarks }}M</strong></span>
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded-md text-slate-300">ESE: <strong class="text-title">{{ $eseMarks }}M</strong></span>
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded-md text-slate-300">Credits: <strong class="text-title">{{ $credit }}</strong></span>
        <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 rounded-md text-emerald-400"><strong>{{ $totalHours }} Hrs</strong></span>
        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" class="px-2.5 py-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 hover:text-white border border-blue-500/40 rounded-md text-xs font-semibold transition flex items-center gap-1.5 no-underline shadow-2xs shrink-0" title="Open Common Attendance & Subject Log">
          <span class="material-symbols-rounded text-sm text-blue-400">co_present</span>
          <span class="hidden sm:inline font-sans">Attendance &amp; Log</span>
        </a>
      </div>
    </div>

    @php
      $activeTab = request()->query('tab', 'outline');
      if ($activeTab === 'internals') $activeTab = 'reports';
      if (!in_array($activeTab, ['outline', 'planner', 'cia', 'series', 'selflearning', 'attainment', 'materials', 'reports'])) {
        $activeTab = 'outline';
      }
    @endphp

    <!-- ROW 3: Horizontal Tab Strip Navigation Container -->
    <div class="tab-nav-container bg-slate-950/80 border border-slate-800/80 p-2 rounded-2xl shadow-lg">
      <nav class="flex flex-wrap md:flex-nowrap items-center gap-1.5 overflow-x-auto scrollbar-none no-scrollbar">
        <button onclick="switchTab('outline')" id="btn-outline" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'outline' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">import_contacts</span> Course Outline
        </button>
        
        <button onclick="switchTab('planner')" id="btn-planner" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'planner' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">calendar_month</span> Lesson Planner
        </button>
        
        <button onclick="switchTab('cia')" id="btn-cia" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'cia' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">fact_check</span> CIE Register
        </button>

        <button onclick="switchTab('series')" id="btn-series" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'series' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">quiz</span> Series Exams
        </button>

        <button onclick="switchTab('selflearning')" id="btn-selflearning" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'selflearning' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm text-emerald-400">local_library</span> Self-Learning (SLA)
        </button>

        <button onclick="switchTab('attainment')" id="btn-attainment" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'attainment' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm text-amber-400">emoji_events</span> Course Attainment & Surveys
        </button>

        <button onclick="switchTab('materials')" id="btn-materials" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'materials' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">folder_special</span> Study Materials Hub
        </button>

        <button onclick="switchTab('reports')" id="btn-reports" class="tab-btn px-3.5 py-2 text-[11px] md:text-xs {{ $activeTab === 'reports' ? 'font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl shadow-[0_-4px_10px_rgba(59,130,246,0.2)]' : 'font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl' }} flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
          <span class="material-symbols-rounded text-sm">assessment</span> Reports
        </button>
      </nav>
    </div>
  </header>

  <!-- SCROLLABLE PAGE AREA (Vertical Scroll Only) -->
  <main class="flex-1 min-h-0 overflow-y-auto custom-scrollbar px-4 sm:px-6 py-4">
    
    <!-- MAIN FULL-WIDTH WORKSPACE -->
    <div id="main-classroom-workspace" class="w-full space-y-4">
      
      <!-- DETAILS PANEL COLUMN (100% FULL WIDTH) -->
      <div id="details-panel-column" class="w-full transition-all duration-300">
        
        <!-- TAB: COURSE OUTLINE -->
        <div id="tab-outline" class="tab-panel bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-5 shadow-md space-y-5 {{ $activeTab === 'outline' ? '' : 'hidden' }}">
          <!-- Top Header Strip -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/40 pb-3.5 gap-3">
            <div>
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400 text-lg">import_contacts</span>
                Syllabus & Course Outline
              </h3>
              <p class="text-xs text-muted mt-0.5">Revision 2026 Diploma Curriculum • 60 Contact Hours (4 Credits) • 40 CIE + 60 ESE</p>
            </div>
            <div class="flex items-center gap-2">
              @if($courseFile->syllabus_pdf_path)
                <a href="/storage/{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="px-2.5 py-1 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs no-underline">
                  <span class="material-symbols-rounded text-xs">picture_as_pdf</span>
                  Preview Syllabus PDF
                </a>
              @endif
              <button onclick="document.getElementById('syllabusFileInput').click()" class="px-2.5 py-1 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                <span class="material-symbols-rounded text-xs">upload_file</span>
                Upload Syllabus PDF
              </button>
            </div>
            <input type="file" id="syllabusFileInput" accept="application/pdf" class="hidden" onchange="performSyllabusUpload(this)">
          </div>

          <!-- COURSE META OVERVIEW RIBBON (4 STAT CARDS) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            <div class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 space-y-1 shadow-sm">
              <span class="text-[11px] font-bold text-muted uppercase tracking-wider block">Course Identifier</span>
              <div class="flex items-center gap-2">
                <span class="font-mono text-sm font-bold text-sky-400">{{ $batchSubject->subject_code }}</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-xs font-semibold text-slate-200">Semester {{ $batchSubject->semester }} (Theory)</span>
              </div>
              <p class="text-[11px] text-muted truncate">{{ $batchSubject->subject_name }}</p>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 space-y-1 shadow-sm">
              <span class="text-[11px] font-bold text-muted uppercase tracking-wider block">Instructional Scheme</span>
              <div class="flex items-center gap-2">
                <span class="font-mono text-sm font-bold text-emerald-400">{{ $totalHours }} Contact Hours</span>
              </div>
              <p class="text-[11px] text-muted">Teaching Scheme: <strong class="text-slate-300 font-mono">{{ $ltpr }}</strong> (L:T:P:R)</p>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 space-y-1 shadow-sm">
              <span class="text-[11px] font-bold text-muted uppercase tracking-wider block">Credits & Self-Study</span>
              <div class="flex items-center gap-2">
                <span class="font-mono text-sm font-bold text-blue-400">{{ $credit }} Credits</span>
                <span class="text-slate-600 text-xs">•</span>
                <span class="text-xs font-semibold text-slate-200">5.5 Hrs / Wk</span>
              </div>
              <p class="text-[11px] text-muted">Total 60 Hours Self-Learning Activities</p>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 space-y-1 shadow-sm">
              <span class="text-[11px] font-bold text-muted uppercase tracking-wider block">Examination Split</span>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-amber-400">{{ $cieMarks }}M CIE</span>
                <span class="text-slate-600 text-xs">+</span>
                <span class="text-xs font-bold text-sky-400">{{ $eseMarks }}M ESE</span>
                <span class="text-slate-600 text-xs">=</span>
                <span class="text-xs font-bold text-emerald-400 font-mono">100M Total</span>
              </div>
              <p class="text-[11px] text-muted">Min Passing: 40% in ESE & 40% Aggregate</p>
            </div>
          </div>

          <!-- COURSE OUTCOMES (COs) CARD -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 border-b border-slate-800/50 pb-2.5">
              <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-emerald-400 text-base">stars</span>
                Course Outcomes (COs)
              </h4>
              <span class="text-[11px] text-muted font-normal">Mapped to Bloom's Taxonomy cognitive domains and instructional periods</span>
            </div>
            <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/30">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/70 text-[11px] font-bold text-slate-300 uppercase tracking-wider border-b border-slate-800">
                    <th class="py-2.5 px-3.5 pl-4 w-[12%] text-center">Outcome ID</th>
                    <th class="py-2.5 px-3 w-[16%] text-center">Cognitive Level</th>
                    <th class="py-2.5 px-3 w-[14%] text-center">Duration</th>
                    <th class="py-2.5 px-3.5 pr-4">Course Outcome Statement</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                  @foreach($cosList as $co)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                      <td class="py-2.5 px-3.5 pl-4 text-center align-middle font-mono">
                        <span class="px-2.5 py-0.5 bg-emerald-500/15 border border-emerald-500/30 rounded-md text-emerald-400 font-bold text-xs shadow-2xs inline-block">{{ $co['id'] }}</span>
                      </td>
                      <td class="py-2.5 px-3 text-center align-middle">
                        <span class="px-2 py-0.5 bg-slate-800 text-slate-200 rounded border border-slate-700/80 text-xs font-medium inline-block">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                      </td>
                      <td class="py-2.5 px-3 text-center align-middle font-mono">
                        <span class="px-2 py-0.5 bg-blue-500/15 text-blue-300 border border-blue-500/30 rounded text-xs font-semibold inline-block">{{ $co['duration'] ?? '15' }} Periods</span>
                      </td>
                      <td class="py-2.5 px-3.5 pr-4 text-slate-200 leading-relaxed font-normal align-middle">{{ $co['description'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- COURSE MODULES & MAJOR TOPICS CARD -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 border-b border-slate-800/50 pb-2.5">
              <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-sky-400 text-base">collections_bookmark</span>
                Course Modules & Major Topics
              </h4>
              <span class="text-[11px] text-muted font-normal">Module-wise instructional breakdown (L: Lecture, T: Tutorial) across 4 modules</span>
            </div>
            <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/30">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/70 text-[11px] font-bold text-slate-300 uppercase tracking-wider border-b border-slate-800">
                    <th class="py-2.5 px-3.5 pl-4 w-[14%] text-center">Module No</th>
                    <th class="py-2.5 px-3 w-[16%] text-center">Instructional Hours</th>
                    <th class="py-2.5 px-3.5 pr-4">Major Topics Description & Detailed Syllabus</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                  @foreach($modulesList as $mod)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                      <td class="py-2.5 px-3.5 pl-4 text-center align-middle">
                        <span class="px-2.5 py-0.5 bg-slate-800 border border-slate-700 rounded-md text-slate-200 font-bold uppercase tracking-wide text-xs inline-block">Module {{ $mod['module_id'] }}</span>
                        @if(!empty($mod['title']))
                          <div class="text-[11px] text-sky-400 font-semibold mt-1">{{ $mod['title'] }}</div>
                        @endif
                      </td>
                      <td class="py-2.5 px-3 text-center align-middle font-mono">
                        <span class="px-2.5 py-0.5 bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 rounded-md text-xs font-bold inline-block">{{ $mod['hours'] ?? 15 }} Hours</span>
                        <div class="text-[10px] text-muted mt-0.5">
                          L: {{ $mod['lecture_hours'] ?? ($mod['module_id'] == 'IV' ? 12 : 11) }} • T: {{ $mod['tutorial_hours'] ?? ($mod['module_id'] == 'IV' ? 3 : 4) }}
                        </div>
                      </td>
                      <td class="py-2.5 px-3.5 pr-4 text-slate-200 leading-relaxed font-normal align-middle">{{ $mod['content'] ?? '' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- CO-PO ARTICULATION MATRIX CARD -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-800/50 pb-2.5">
              <div>
                <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-2">
                  <span class="material-symbols-rounded text-blue-400 text-base">grid_on</span>
                  CO-PO Articulation Matrix (Program Outcomes)
                </h4>
                <span class="text-[11px] text-muted font-normal">Mapping of Course Outcomes to National Board of Accreditation (NBA) Program Outcomes PO1–PO11</span>
              </div>
              <div class="flex items-center gap-2.5 self-end sm:self-auto">
                <span id="copoAutosaveBadge" class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Saved
                </span>
                <button type="button" onclick="saveCopoMatrix()" id="btnSaveCopo" class="px-2.5 py-1 rounded bg-blue-600/20 hover:bg-blue-600/40 text-sky-300 border border-blue-500/30 text-xs font-semibold flex items-center gap-1 transition-all shadow-xs cursor-pointer">
                  <span class="material-symbols-rounded text-sm">save</span>
                  <span>Save Matrix</span>
                </button>
              </div>
            </div>

            <div class="border border-slate-800 rounded-lg overflow-x-auto bg-slate-950/30">
              <table class="w-full text-center border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/70 text-[11px] font-bold text-slate-300 uppercase tracking-wider border-b border-slate-800">
                    <th class="py-2.5 px-3 text-left pl-4 w-[16%] min-w-[120px]">Course Outcome</th>
                    @for($p = 1; $p <= 11; $p++)
                      <th class="py-2.5 px-1.5 text-center min-w-[44px]">PO{{ $p }}</th>
                    @endfor
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70" id="copoMatrixTableBody">
                  @foreach($cosList as $rowIndex => $co)
                    @php
                      $coId = $co['id'];
                      $m = $mappings[$coId] ?? [];
                    @endphp
                    <tr class="hover:bg-slate-800/30 transition-colors" data-co-id="{{ $coId }}">
                      <td class="py-2.5 px-3 text-left font-bold text-title pl-4 align-middle">
                        <span class="px-2.5 py-0.5 bg-blue-500/15 text-blue-300 border border-blue-500/30 rounded-md font-mono text-xs font-bold inline-block">{{ $coId }}</span>
                      </td>
                      @for($p = 1; $p <= 11; $p++)
                        @php
                          $val = $m["PO$p"] ?? ($p == 1 ? '3' : '');
                          if ($val === '-') $val = '';
                        @endphp
                        <td class="py-1 px-1 align-middle text-center">
                          <input type="text"
                                 maxlength="1"
                                 value="{{ $val }}"
                                 placeholder="-"
                                 data-co="{{ $coId }}"
                                 data-po="PO{{ $p }}"
                                 data-row="{{ $rowIndex }}"
                                 data-col="{{ $p }}"
                                 oninput="handleCopoInput(this)"
                                 onkeydown="handleCopoKeyNav(event, this)"
                                 onfocus="this.select()"
                                 class="copo-matrix-cell w-9 h-8 bg-slate-900/80 border rounded text-center font-bold font-mono text-xs outline-none transition-all duration-150 {{ $val == '3' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40' : ($val == '2' ? 'bg-sky-500/20 text-sky-400 border-sky-500/40' : ($val == '1' ? 'bg-amber-500/20 text-amber-400 border-amber-500/40' : 'text-slate-500 border-slate-800 hover:border-slate-700')) }} focus:border-blue-400 focus:ring-1 focus:ring-blue-400/50">
                        </td>
                      @endfor
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="bg-slate-900/80 text-[11px] font-bold text-slate-300 border-t border-slate-800">
                    <td class="py-2 px-3 text-left pl-4 font-semibold text-slate-400">
                      Average Correlation
                    </td>
                    @for($p = 1; $p <= 11; $p++)
                      <td class="py-2 px-1 text-center font-mono text-xs font-bold text-slate-300" id="avg-PO{{ $p }}">-</td>
                    @endfor
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 text-[11px] text-muted pt-1">
              <div class="flex items-center gap-4">
                <span class="font-bold text-slate-300">Legends:</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> 3 - High Correlation</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-400"></span> 2 - Medium Correlation</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> 1 - Low Correlation</span>
                <span class="text-slate-500">- No Correlation</span>
              </div>
              <span class="text-[10px] text-muted font-mono italic">Changes autosave continuously as you type • Use arrow keys / Enter to navigate</span>
            </div>
          </div>

          <!-- REVISION 2026 ASSESSMENT METHODOLOGY REFERENCE CARD -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 border-b border-slate-800/50 pb-2.5">
              <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-amber-400 text-base">verified</span>
                Revision 2026 Assessment Methodology (Theory: 40 CIE + 60 ESE)
              </h4>
              <span class="text-[11px] text-muted font-normal">Standardized evaluation rules as per official Diploma Curriculum Revision 2026</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
              <!-- Attendance & Subject Log -->
              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2 flex flex-col justify-between">
                <div>
                  <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
                    <span class="text-xs font-bold text-slate-200">1. Attendance &amp; Subject Log</span>
                    <span class="px-1.5 py-0.2 rounded bg-blue-500/15 text-blue-400 border border-blue-500/30 text-[10px] font-mono font-bold">5 Marks</span>
                  </div>
                  <p class="text-[11px] text-muted leading-relaxed mt-1.5">Converted continuously from Table 2.1 at semester end:</p>
                  <div class="text-[10px] font-mono text-slate-300 space-y-0.5 bg-slate-950/40 p-2 rounded border border-slate-800/60 mt-1">
                    <div class="flex justify-between"><span>≥ 90%</span><span class="text-emerald-400 font-bold">5 Marks</span></div>
                    <div class="flex justify-between"><span>80% – 89%</span><span class="text-sky-400 font-bold">4 Marks</span></div>
                    <div class="flex justify-between"><span>75% – 79%</span><span class="text-blue-400 font-bold">3 Marks</span></div>
                    <div class="flex justify-between"><span>70% – 74%</span><span class="text-amber-400 font-bold">2 Marks</span></div>
                    <div class="flex justify-between"><span>65% – 69%</span><span class="text-orange-400 font-bold">1 Mark</span></div>
                    <div class="flex justify-between"><span class="text-rose-400">&lt; 65%</span><span class="text-rose-400 font-bold">0 Marks</span></div>
                  </div>
                </div>
                <div class="pt-1">
                  <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/theory/' . $batchSubject->id . '?tab=outline') }}" class="w-full py-1 px-2 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 hover:text-white border border-blue-500/30 rounded text-[10px] font-bold flex items-center justify-center gap-1 transition no-underline shadow-2xs">
                    <span class="material-symbols-rounded text-xs text-blue-400">co_present</span> Open Attendance &amp; Log
                  </a>
                </div>
              </div>

              <!-- Self-Learning Activities -->
              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2">
                <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
                  <span class="text-xs font-bold text-slate-200">2. Self-Learning (SLA)</span>
                  <span class="px-1.5 py-0.2 rounded bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[10px] font-mono font-bold">15 Marks</span>
                </div>
                <p class="text-[11px] text-muted leading-relaxed">4 Module Activities (Max 15M each) averaged to 15 CIE:</p>
                <div class="text-[10px] font-mono text-slate-300 space-y-1 bg-slate-950/40 p-2 rounded border border-slate-800/60">
                  <div class="flex justify-between"><span>CA1 (Mod 1)</span><span class="text-slate-400">15M • By 4th Wk</span></div>
                  <div class="flex justify-between"><span>CA2 (Mod 2)</span><span class="text-slate-400">15M • By 7th Wk</span></div>
                  <div class="flex justify-between"><span>CA3 (Mod 3)</span><span class="text-slate-400">15M • By 11th Wk</span></div>
                  <div class="flex justify-between"><span>CA4 (Mod 4)</span><span class="text-slate-400">15M • By 14th Wk</span></div>
                  <div class="border-t border-slate-800 pt-1 flex justify-between font-bold text-emerald-400">
                    <span>Average SLA</span><span>= 15 Marks</span>
                  </div>
                </div>
              </div>

              <!-- Series Examinations -->
              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2">
                <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
                  <span class="text-xs font-bold text-slate-200">3. Series Exams</span>
                  <span class="px-1.5 py-0.2 rounded bg-purple-500/15 text-purple-400 border border-purple-500/30 text-[10px] font-mono font-bold">20 Marks</span>
                </div>
                <p class="text-[11px] text-muted leading-relaxed">Two 2-hour written examinations of 50M each:</p>
                <div class="text-[10px] font-mono text-slate-300 space-y-1 bg-slate-950/40 p-2 rounded border border-slate-800/60">
                  <div class="flex justify-between"><span>CA5 (Series 1)</span><span class="text-slate-400">50M → 20M • 8th Wk</span></div>
                  <div class="flex justify-between"><span>CA6 (Series 2)</span><span class="text-slate-400">50M → 20M • 15th Wk</span></div>
                  <div class="text-[9px] text-muted mt-1 leading-normal">Modules 1 & 2 in Series 1, Modules 3 & 4 in Series 2</div>
                  <div class="border-t border-slate-800 pt-1 flex justify-between font-bold text-purple-400">
                    <span>Average Series</span><span>= 20 Marks</span>
                  </div>
                </div>
              </div>

              <!-- End Semester Exam -->
              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2">
                <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
                  <span class="text-xs font-bold text-slate-200">4. ESE Examination</span>
                  <span class="px-1.5 py-0.2 rounded bg-sky-500/15 text-sky-400 border border-sky-500/30 text-[10px] font-mono font-bold">60 Marks</span>
                </div>
                <p class="text-[11px] text-muted leading-relaxed">Written exam conducted by SBTE (2.5 hours):</p>
                <div class="text-[10px] font-mono text-slate-300 space-y-1 bg-slate-950/40 p-2 rounded border border-slate-800/60">
                  <div class="flex justify-between"><span>Part A</span><span class="text-slate-400">8 Qs × 1M = 8 Marks</span></div>
                  <div class="flex justify-between"><span>Part B</span><span class="text-slate-400">8 Qs × 3M = 24 Marks</span></div>
                  <div class="flex justify-between"><span>Part C</span><span class="text-slate-400">4 Qs × 7M = 28 Marks</span></div>
                  <div class="border-t border-slate-800 pt-1 flex justify-between font-bold text-sky-400">
                    <span>Total ESE</span><span>= 60 Marks</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- QUESTION PAPER PATTERNS REFERENCE CARD -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Series Question Paper Pattern -->
            <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-2.5">
              <div class="flex justify-between items-center border-b border-slate-800/50 pb-2">
                <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-purple-400 text-sm">quiz</span>
                  Series Examination Pattern (50 Marks • 2 Hours)
                </h4>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-purple-500/15 text-purple-300 border border-purple-500/30">2 Modules per Test</span>
              </div>
              <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/30">
                <table class="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr class="bg-slate-900/70 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                      <th class="py-2 px-3">Part</th>
                      <th class="py-2 px-3 text-center">Module 1</th>
                      <th class="py-2 px-3 text-center">Module 2</th>
                      <th class="py-2 px-3 text-center">Total Questions</th>
                      <th class="py-2 px-3 text-right">Marks</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-800/70 text-[11px] font-mono">
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part A (1 Mark)</td>
                      <td class="py-2 px-3 text-center text-slate-300">2 Qs</td>
                      <td class="py-2 px-3 text-center text-slate-300">2 Qs</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">4 Qs</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">4 M</td>
                    </tr>
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part B (3 Marks)</td>
                      <td class="py-2 px-3 text-center text-slate-300">3 Qs</td>
                      <td class="py-2 px-3 text-center text-slate-300">3 Qs</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">6 Qs</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">18 M</td>
                    </tr>
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part C (7 Marks With Choice)</td>
                      <td class="py-2 px-3 text-center text-slate-300">2 Qs</td>
                      <td class="py-2 px-3 text-center text-slate-300">2 Qs</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">4 Qs</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">28 M</td>
                    </tr>
                    <tr class="bg-slate-900/60 font-bold border-t border-slate-800">
                      <td colspan="3" class="py-2 px-3 text-slate-200">Total Question Paper Marks</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">14 Qs</td>
                      <td class="py-2 px-3 text-right text-purple-400 font-bold">50 M</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- ESE Question Paper Pattern -->
            <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-2.5">
              <div class="flex justify-between items-center border-b border-slate-800/50 pb-2">
                <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sky-400 text-sm">history_edu</span>
                  End Semester Examination Pattern (60 Marks • 2.5 Hours)
                </h4>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-sky-500/15 text-sky-300 border border-sky-500/30">All 4 Modules</span>
              </div>
              <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/30">
                <table class="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr class="bg-slate-900/70 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                      <th class="py-2 px-3">Part</th>
                      <th class="py-2 px-3">Question Distribution</th>
                      <th class="py-2 px-3 text-center">To Answer</th>
                      <th class="py-2 px-3 text-right">Marks</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-800/70 text-[11px] font-mono">
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part A (1 Mark)</td>
                      <td class="py-2 px-3 text-slate-300">2 Questions from each module</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">8 Questions</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">8 M</td>
                    </tr>
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part B (3 Marks)</td>
                      <td class="py-2 px-3 text-slate-300">3 Questions from each module (12 Qs)</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">2 of 3 per mod (8 Qs)</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">24 M</td>
                    </tr>
                    <tr class="hover:bg-slate-800/30">
                      <td class="py-2 px-3 font-semibold text-slate-200">Part C (7 Marks)</td>
                      <td class="py-2 px-3 text-slate-300">1 set with internal choice from each module</td>
                      <td class="py-2 px-3 text-center text-sky-400 font-bold">4 Sets</td>
                      <td class="py-2 px-3 text-right text-emerald-400 font-bold">28 M</td>
                    </tr>
                    <tr class="bg-slate-900/60 font-bold border-t border-slate-800">
                      <td colspan="3" class="py-2 px-3 text-slate-200">Total SBTE End Semester Exam Marks</td>
                      <td class="py-2 px-3 text-right text-sky-400 font-bold">60 M</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- LEARNING RESOURCES & TEXTBOOKS CARD -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 border-b border-slate-800/50 pb-2">
              <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-emerald-400 text-base">menu_book</span>
                Prescribed Textbooks & Academic References
              </h4>
              <span class="text-[11px] text-muted font-normal">SITTTR Kalamassery Syllabus Guidelines</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2">
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Official Text Book</span>
                <div class="p-2.5 rounded bg-slate-950/50 border border-slate-800/70 space-y-1">
                  <div class="font-bold text-slate-100 text-xs">Fundamentals of Engineering Mathematics</div>
                  <div class="text-[11px] text-muted">Publication: <strong class="text-slate-300">SITTTR Kalamassery</strong></div>
                </div>
              </div>

              <div class="bg-slate-900/40 border border-slate-800/80 rounded-lg p-3 space-y-2">
                <span class="text-[11px] font-bold text-blue-400 uppercase tracking-wider block">Standard Reference Literature</span>
                <div class="p-2.5 rounded bg-slate-950/50 border border-slate-800/70 space-y-1 text-[11px] text-slate-300 leading-relaxed">
                  <div>• <strong>Schaum's Outline Series</strong>: Linear Algebra, Trigonometry, Geometry, Calculus (McGraw Hill)</div>
                  <div>• <strong>Advanced Engineering Mathematics</strong> (Erwin Kreyszig, Wiley & Sons)</div>
                  <div>• <strong>A Textbook of Engineering Mathematics</strong> (N.P. Bali, Manish Goyal, Univ. Science Press)</div>
                  <div>• <strong>Higher Engineering Mathematics</strong> (B. S. Grewal, Mercury Learning)</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB: LESSON PLANNER -->
        <div id="tab-planner" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 {{ $activeTab === 'planner' ? '' : 'hidden' }}">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/40 pb-3 gap-2">
            <div>
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400 text-base">calendar_month</span>
                Academic Lesson Planner
              </h3>
              <p class="text-xs text-muted mt-0.5">Track topic scheduling, pedagogy, Bloom's taxonomy levels, and syllabus completion</p>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
              <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs no-underline">
                <span class="material-symbols-rounded text-xs">print</span>
                Print Lesson Plan
              </a>
              <span id="plannerAutosaveBadge" class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Changes Saved
              </span>
              <button id="btnSavePlanner" onclick="saveLessonPlanEdits()" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white border border-blue-500/30 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                <span class="material-symbols-rounded text-xs">save</span>
                Save Changes
              </button>
            </div>
          </div>

          <!-- PLANNER TABLE -->
          <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[980px]">
              <thead>
                <tr class="bg-slate-900/30 text-[10px] font-semibold text-muted uppercase tracking-wider border-b border-card">
                  <th class="py-1.5 px-1 w-[3.5%] text-center">Period</th>
                  <th class="py-1.5 px-1 w-[4.5%] text-center">CO Tag</th>
                  <th class="py-1.5 px-2.5 w-[52%]">Topic / Content Scheduled (Full Preview)</th>
                  <th class="py-1.5 px-1 w-[6%]">Pedagogy</th>
                  <th class="py-1.5 px-1 w-[6%]">Taxonomy</th>
                  <th class="py-1.5 px-1 w-[8%] text-center">Proposed Date</th>
                  <th class="py-1.5 px-1 w-[8%] text-center">Actual Date</th>
                  <th class="py-1.5 px-0.5 w-[3%] text-center">Hours</th>
                  <th class="py-1.5 px-1 w-[5%]">Status</th>
                  <th class="py-1.5 px-1 w-[4%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="plannerTableBody" class="text-sm font-normal">
                @forelse($lessonPlans as $lp)
                  <tr data-lp-id="{{ $lp->id }}" class="bg-card-hover transition-all font-normal">
                    <td class="p-1 font-mono text-center text-title period-no-display text-xs">{{ $lp->day_no }}</td>
                    <td class="p-1 text-center">
                      <select data-field="co_id" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sky-400 focus:border-sky-500 outline-none font-semibold text-xs text-center">
                        <option value="CO1" {{ $lp->co_id == 'CO1' ? 'selected' : '' }}>CO1</option>
                        <option value="CO2" {{ $lp->co_id == 'CO2' ? 'selected' : '' }}>CO2</option>
                        <option value="CO3" {{ $lp->co_id == 'CO3' ? 'selected' : '' }}>CO3</option>
                        <option value="CO4" {{ $lp->co_id == 'CO4' ? 'selected' : '' }}>CO4</option>
                        <option value="CO5" {{ $lp->co_id == 'CO5' ? 'selected' : '' }}>CO5</option>
                        @if(!in_array($lp->co_id, ['CO1', 'CO2', 'CO3', 'CO4', 'CO5']) && !empty($lp->co_id))
                          <option value="{{ $lp->co_id }}" selected>{{ $lp->co_id }}</option>
                        @endif
                      </select>
                    </td>
                    <td class="p-1">
                      <textarea data-field="topic_content" oninput="triggerPlannerAutosave()" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2.5 py-1.5 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs resize-y leading-relaxed">{{ $lp->topic_content }}</textarea>
                    </td>
                    <td class="p-1">
                      <select data-field="pedagogy" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-[11px]">
                        <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                        <option value="Tutorial" {{ $lp->pedagogy == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                        <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                      </select>
                    </td>
                    <td class="p-1">
                      <input type="text" data-field="taxonomy" oninput="triggerPlannerAutosave()" value="{{ $lp->taxonomy }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px]" placeholder="Taxonomy...">
                    </td>
                    <td class="p-1">
                      <input type="text" data-field="proposed_date" onchange="triggerPlannerAutosave()" value="{{ $lp->proposed_date ? (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $lp->proposed_date) ? $lp->proposed_date : \Carbon\Carbon::parse($lp->proposed_date)->format('d/m/Y')) : '' }}" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px] text-center">
                    </td>
                    <td class="p-1">
                      <input type="text" data-field="actual_date" onchange="triggerPlannerAutosave()" value="{{ $lp->actual_date ? (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $lp->actual_date) ? $lp->actual_date : \Carbon\Carbon::parse($lp->actual_date)->format('d/m/Y')) : '' }}" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px] text-center">
                    </td>
                    <td class="p-1">
                      <input type="number" data-field="allocated_hours" oninput="triggerPlannerAutosave()" value="{{ $lp->allocated_hours ?: 1 }}" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-0.5 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-center text-[11px]">
                    </td>
                    <td class="p-1">
                      <select data-field="status" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-[11px]">
                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                      </select>
                    </td>
                    <td class="p-1 text-center">
                      <div class="flex items-center justify-center gap-0.5">
                        <button type="button" onclick="addLessonPlanRowBelow(this)" class="text-sky-400 hover:text-sky-300 hover:bg-sky-500/20 p-1 rounded transition cursor-pointer" title="Add Row Below">
                          <span class="material-symbols-rounded text-xs block">add</span>
                        </button>
                        <button type="button" onclick="deleteLessonPlanRow(this)" class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 p-1 rounded transition cursor-pointer" title="Delete Row">
                          <span class="material-symbols-rounded text-xs block">close</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="p-6 text-center text-muted italic font-normal">No lesson plan topics registered yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- BOTTOM ACTION BAR FOR PLANNER TABLE -->
          <div class="flex justify-between items-center pt-1.5">
            <button type="button" onclick="addLessonPlanRow()" class="px-2 py-1 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-md text-[10.5px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
              <span class="material-symbols-rounded text-xs">add_circle</span>
              Add Period / Row
            </button>
            <span class="text-[10px] text-muted font-mono italic">Changes autosave continuously as you type</span>
          </div>
        </div>

        <!-- TAB: CONTINUOUS INTERNAL ASSESSMENT -->
        <div id="tab-cia" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 {{ $activeTab === 'cia' ? '' : 'hidden' }}">
          
          <!-- CONSOLIDATED CIA / CIE REGISTER MARKSHEET (PRIMARY VIEW) -->
          <div id="cia-consolidated-view" class="space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-2 pb-3 border-b border-slate-800">
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
                <a href="/r26/classroom/theory/{{ $batchSubject->id }}/print/cia-register" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                  <span class="material-symbols-rounded text-sm">print</span>
                  <span>Print CIA Register</span>
                </a>
                <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/theory/' . $batchSubject->id . '?tab=cia') }}" class="px-2.5 py-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 hover:text-white border border-blue-500/40 rounded-md text-xs font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs no-underline" title="Open Class Attendance & Subject Log">
                  <span class="material-symbols-rounded text-xs text-blue-400">co_present</span>
                  <span>Attendance &amp; Log</span>
                </a>
                <button type="button" onclick="switchTab('selflearning')" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                  <span class="material-symbols-rounded text-sm text-emerald-400">local_library</span>
                  <span>Self-Learning (SLA Tab) &rarr;</span>
                </button>
                <button onclick="triggerConsolidatedManualSave()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-sm">save</span>
                  <span>Save All Changes</span>
                </button>
              </div>
            </div>

            @if(empty($hasSubjectAttendance))
              <!-- Missing Subject Attendance Prompt Banner -->
              <div class="p-3 rounded-lg bg-amber-950/40 border border-amber-500/50 text-xs text-amber-200 flex items-start sm:items-center justify-between gap-3 mb-2 shadow-sm">
                <div class="flex items-start sm:items-center gap-2.5">
                  <span class="material-symbols-rounded text-amber-400 text-lg shrink-0 mt-0.5 sm:mt-0">warning</span>
                  <div>
                    <span class="font-bold text-amber-300">Subject Attendance Missing:</span>
                    <span>Attendance has not been entered manually or uploaded from Teams for this subject. In Revision 2026, subject CIE calculations strictly require staff attendance (manual logs or staff Teams upload). Tutor uploads are reserved strictly for total semester attendance &amp; exam eligibility.</span>
                  </div>
                </div>
                <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/theory/' . $batchSubject->id . '?tab=cia') }}" class="shrink-0 px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded text-[11px] transition no-underline flex items-center gap-1 shadow-xs">
                  <span class="material-symbols-rounded text-xs">edit_calendar</span> Enter Attendance
                </a>
              </div>
            @endif

            <!-- Rubric Hint Banner -->
            <div class="p-2.5 rounded-lg bg-slate-950/70 border border-slate-800 text-[11px] text-slate-300 flex items-center justify-between flex-wrap gap-2 mb-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400 text-sm">info</span>
                <span><strong>Attendance Slabs (5M):</strong> &ge;90%: 5 | 80-89%: 4 | 75-79%: 3 | 70-74%: 2 | 65-69%: 1 | &lt;65%: 0</span>
              </div>
              <span class="text-emerald-400 font-semibold">⚡ Auto-saves on typing &bull; Tab/Enter moves down in column</span>
            </div>

            <!-- Consolidated Marks Table -->
            <div class="overflow-x-auto border border-card rounded-xl bg-slate-950/10 custom-scrollbar">
              <table class="w-full border-collapse text-left" id="consolidatedCiaTable">
                <thead>
                  <tr class="bg-slate-950/80 text-slate-300 text-xs sm:text-sm font-bold">
                    <th rowspan="2" class="border border-slate-800 text-center w-12 py-2.5">Roll</th>
                    <th rowspan="2" class="border border-slate-800 w-28 py-2.5">Reg No</th>
                    <th rowspan="2" class="border border-slate-800 min-w-[170px] py-2.5">Student Name</th>
                    <th colspan="3" class="border border-slate-800 text-center bg-blue-950/40 text-blue-300 py-1.5">Attendance (5M)</th>
                    <th rowspan="2" class="border border-slate-800 text-center w-28 bg-amber-950/40 text-amber-300 py-2.5">
                      Self-Learning (15M)<br>
                      <button type="button" onclick="switchTab('selflearning')" class="mt-1 px-2 py-0.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 rounded text-[10px] font-semibold transition cursor-pointer">Edit SLA &rarr;</button>
                    </th>
                    <th colspan="3" class="border border-slate-800 text-center bg-sky-950/40 text-sky-300 py-1.5">Series Exams (20M)</th>
                    <th rowspan="2" class="border-2 border-emerald-500/60 text-center w-32 bg-emerald-950/70 text-emerald-300 py-2.5">Total<br><span class="text-emerald-400 font-black text-sm sm:text-base">CIE (40)</span></th>
                    <th colspan="3" class="border border-slate-800 text-center bg-purple-950/40 text-purple-300 py-1.5">ESE (60M)</th>
                    <th rowspan="2" class="border border-slate-800 text-center w-24 bg-slate-950 text-amber-300 py-2.5">Grand<br>Total (100)</th>
                  </tr>
                  <tr class="bg-slate-950/90 text-slate-400 text-xs">
                    <th class="border border-slate-800 text-center w-16 py-1.5" title="Raw % from Teams attendance log">Teams %</th>
                    <th class="border border-slate-800 text-center w-28 py-1.5" title="Override % to fix parsing errors (Amber border when active)">Override %</th>
                    <th class="border border-slate-800 text-center w-16 py-1.5 font-bold text-blue-300">Mark (5)</th>
                    <th class="border border-slate-800 text-center w-20 py-1.5 font-semibold text-slate-300" title="Series 1 Mark out of 50">S1 (50)</th>
                    <th class="border border-slate-800 text-center w-20 py-1.5 font-semibold text-slate-300" title="Series 2 Mark out of 50">S2 (50)</th>
                    <th class="border border-slate-800 text-center w-20 py-1.5 font-bold text-sky-300 bg-sky-950/20" title="Combined Series converted to 20">Ser (20)</th>
                    <th class="border border-slate-800 text-center w-20 py-1.5" title="End Sem Exam Marks out of 60">Marks (60)</th>
                    <th class="border border-slate-800 text-center w-14 py-1.5" title="Board Grade (S, A, B, C, D, E, F)">Grade</th>
                    <th class="border border-slate-800 text-center w-12 py-1.5" title="Absent Toggle">AB</th>
                  </tr>
                </thead>
                <tbody class="text-sm font-normal">
                  @forelse($studentResults as $st)
                    <tr class="student-eval-row hover:bg-slate-800/40 even:bg-slate-900/30 odd:bg-transparent transition-colors" data-reg-no="{{ $st['reg_no'] }}">
                      <td class="border-0 text-center font-mono text-cyan-400 font-bold text-xs sm:text-sm py-3 px-2">{{ $st['roll_no'] ?: '-' }}</td>
                      <td class="border-0 font-mono text-xs sm:text-sm font-semibold py-3 px-2">
                        <span class="text-slate-300">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</span>
                      </td>
                      <td class="border-0 text-sm sm:text-base font-bold text-white py-3 px-3" title="{{ $st['name'] }}">
                        {{ $st['name'] }}
                      </td>
                      <td class="border-0 text-center text-xs sm:text-sm font-mono text-slate-400 py-3 px-2">
                        @if($st['raw_att_percentage'] !== null)
                          {{ number_format($st['raw_att_percentage'], 1) . '%' }}
                        @else
                          <span class="text-amber-400/80 italic text-[11px]" title="Attendance not entered manually or from Teams">Not entered</span>
                        @endif
                      </td>
                      <td class="border-0 p-2 text-center cell-att-td">
                        <div class="flex items-center justify-center gap-1">
                          <input type="number" step="0.1" min="0" max="100" 
                                 value="{{ $st['attendance_override_pct'] !== null ? $st['attendance_override_pct'] : '' }}" 
                                 data-prev-val="{{ $st['attendance_override_pct'] !== null ? $st['attendance_override_pct'] : '' }}"
                                 data-max="100"
                                 data-field-name="Attendance Override %"
                                 data-reg-no="{{ $st['reg_no'] }}"
                                 data-student-name="{{ addslashes($st['name']) }}"
                                 placeholder="{{ $st['raw_att_percentage'] !== null ? number_format($st['raw_att_percentage'], 1) : '-' }}"
                                 class="cell-att-override no-spinners w-16 text-center px-1.5 py-1.5 text-sm font-bold font-mono rounded bg-slate-950 border border-slate-700 text-slate-100 focus:border-amber-400 outline-none transition-all {{ $st['attendance_override_pct'] !== null ? 'has-override' : '' }}" 
                                 title="{{ $st['attendance_override_pct'] !== null ? 'Active Override: ' . $st['attendance_override_pct'] . '%' : 'Click to override attendance percentage' }}"
                                 onfocus="this.dataset.prevVal = this.value;"
                                 onchange="handleAttendanceOverrideChange(this, '{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}');">
                          @if($st['attendance_override_pct'] !== null)
                            <button type="button" onclick="clearAttendanceOverride('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-clear-att-override p-1 rounded hover:bg-slate-800 text-amber-400 hover:text-rose-400 transition cursor-pointer" title="Revert to class log raw percentage">
                              <span class="material-symbols-rounded text-xs">close</span>
                            </button>
                          @else
                            <button type="button" onclick="promptAttendanceOverride('{{ $st['reg_no'] }}', '{{ addslashes($st['name']) }}')" class="btn-prompt-att-override p-1 rounded hover:bg-slate-800 text-slate-500 hover:text-amber-400 transition cursor-pointer" title="Click to override attendance %">
                              <span class="material-symbols-rounded text-xs">edit</span>
                            </button>
                          @endif
                        </div>
                      </td>
                      <td class="border-0 text-center font-extrabold text-sm sm:text-base text-blue-300 py-3 cell-att-mark">
                        {{ number_format($st['att_marks'] ?? 0, 1) }}
                      </td>
                      
                      <!-- Consolidated Self-Learning (15M) pulled from SLA tab -->
                      <td class="border-0 text-center py-3 px-2 bg-amber-950/20">
                        <span class="cell-sl-total text-sm sm:text-base font-extrabold text-amber-300 bg-amber-950/40 px-2.5 py-1 rounded border border-amber-500/40 inline-block font-mono min-w-[48px]">
                          {{ number_format($st['self_learning_15'] ?? 0, 1) }}
                        </span>
                        <input type="hidden" class="cell-ca1" value="{{ $st['ca1_15'] !== null ? $st['ca1_15'] : '' }}">
                        <input type="hidden" class="cell-ca2" value="{{ $st['ca2_15'] !== null ? $st['ca2_15'] : '' }}">
                        <input type="hidden" class="cell-ca3" value="{{ $st['ca3_15'] !== null ? $st['ca3_15'] : '' }}">
                        <input type="hidden" class="cell-ca4" value="{{ $st['ca4_15'] !== null ? $st['ca4_15'] : '' }}">
                      </td>

                      <!-- Series 1 (50) - Display Only (Entered in Series Tab) -->
                      <td class="border-0 text-center py-3 px-2 bg-slate-900/30">
                        <span class="cell-s1-50-display font-mono text-xs sm:text-sm font-bold text-slate-300 inline-block min-w-[36px]" title="Series 1 Total (50M) - Enter CO marks in Series Exam Tab">
                          {{ $st['series_1_50'] !== null ? number_format($st['series_1_50'], 1) : '-' }}
                        </span>
                        <input type="hidden" class="cell-s1-50" value="{{ $st['series_1_50'] !== null ? $st['series_1_50'] : '' }}">
                        <input type="hidden" class="cell-s1-co1" value="{{ $st['series_1_co1_25'] !== null ? $st['series_1_co1_25'] : '' }}">
                        <input type="hidden" class="cell-s1-co2" value="{{ $st['series_1_co2_25'] !== null ? $st['series_1_co2_25'] : '' }}">
                      </td>

                      <!-- Series 2 (50) - Display Only (Entered in Series Tab) -->
                      <td class="border-0 text-center py-3 px-2 bg-slate-900/30">
                        <span class="cell-s2-50-display font-mono text-xs sm:text-sm font-bold text-slate-300 inline-block min-w-[36px]" title="Series 2 Total (50M) - Enter CO marks in Series Exam Tab">
                          {{ $st['series_2_50'] !== null ? number_format($st['series_2_50'], 1) : '-' }}
                        </span>
                        <input type="hidden" class="cell-s2-50" value="{{ $st['series_2_50'] !== null ? $st['series_2_50'] : '' }}">
                        <input type="hidden" class="cell-s2-co3" value="{{ $st['series_2_co3_25'] !== null ? $st['series_2_co3_25'] : '' }}">
                        <input type="hidden" class="cell-s2-co4" value="{{ $st['series_2_co4_25'] !== null ? $st['series_2_co4_25'] : '' }}">
                      </td>

                      <!-- Combined Series (20M) -->
                      <td class="border-0 text-center font-bold text-sm sm:text-base text-sky-300 bg-sky-950/20 py-3 cell-series-total">
                        {{ number_format($st['series_20'] ?? 0, 1) }}
                      </td>

                      <!-- Total CIE (40M) - LARGE FONT GREEN TEXT AND NUMBERS -->
                      <td class="border-0 text-center py-3 bg-emerald-950/30">
                        <span class="cell-cie-total text-base sm:text-xl font-black text-emerald-400 bg-emerald-950/60 px-3 py-1.5 rounded-lg border border-emerald-500/50 shadow font-mono inline-block min-w-[55px]">
                          {{ number_format(round($st['total_cia_40'] ?? 0)) }}
                        </span>
                      </td>

                      <!-- ESE Marks (60) -->
                      <td class="border-0 p-2 text-center">
                        <input type="number" step="0.5" min="0" max="60" 
                               value="{{ $st['ese_mark_60'] !== null ? $st['ese_mark_60'] : '' }}" 
                               data-prev-val="{{ $st['ese_mark_60'] !== null ? $st['ese_mark_60'] : '' }}"
                               data-max="60"
                               data-field-name="ESE Marks (End Semester Exam)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-student-name="{{ addslashes($st['name']) }}"
                               placeholder="-"
                               class="cell-ese-marks no-spinners w-16 text-center px-1.5 py-1.5 text-sm font-semibold text-slate-100 bg-slate-950 border border-slate-700 rounded focus:border-blue-500 outline-none" 
                               oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                      </td>

                      <!-- ESE Grade -->
                      <td class="border-0 p-2 text-center">
                        <input type="text" maxlength="2" 
                               value="{{ $st['ese_grade'] ?: '' }}" 
                               placeholder="Grade"
                               class="cell-ese-grade uppercase w-12 text-center px-1 py-1.5 text-sm font-bold text-slate-100 bg-slate-950 border border-slate-700 rounded focus:border-blue-500 outline-none" 
                               oninput="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                      </td>

                      <!-- ESE Absent -->
                      <td class="border-0 text-center py-3">
                        <input type="checkbox" class="cell-ese-absent w-4 h-4 rounded cursor-pointer" 
                               {{ !empty($st['ese_absent']) ? 'checked' : '' }} 
                               onchange="recalculateRow('{{ $st['reg_no'] }}'); queueAutoSave();">
                      </td>

                      <!-- Grand Total (100) -->
                      <td class="border-0 text-center font-extrabold text-sm sm:text-base text-amber-300 bg-slate-950/60 py-3 cell-grand-total">
                        {{ number_format(round($st['grand_total'] ?? 0)) }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="15" class="text-center text-slate-400 py-6 italic">No enrolled students found.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-800">
              <span class="text-xs text-slate-400">Total Enrolled Students: <strong class="text-white">{{ $students->count() }}</strong></span>
              <button onclick="triggerConsolidatedManualSave()" class="px-4 py-1.5 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                <span class="material-symbols-rounded text-sm">save</span>
                <span>Save All Evaluation Records</span>
              </button>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: SELF-LEARNING (SLA) -->
        <div id="tab-selflearning" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 {{ $activeTab === 'selflearning' ? '' : 'hidden' }}">
          <div id="cia-self-learning-view" class="space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/40 pb-3 gap-2">
              <div>
                <h3 class="text-sm font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-blue-400 text-base">local_library</span>
                  Self-Learning Assessment (SLA) - Rev 2026
                </h3>
                <p class="text-xs text-muted mt-0.5">
                  Enter 4 marks: M1 (CA1), M2 (CA2), M3 (CA3), M4 (CA4) out of 15 each. Average calculates Self-Learning (15M) for CIE Register.
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="switchTab('cia')" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to CIE Register
                </button>
                <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[11px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs no-underline">
                  <span class="material-symbols-rounded text-xs">print</span>
                  Print Report
                </a>
                <span id="slAutosaveBadge" class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved
                </span>
                <button id="btnSaveSelfLearning" onclick="saveSelfLearningMarks()" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-md text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                  <span class="material-symbols-rounded text-xs">save</span>
                  Save Self-Learning
                </button>
              </div>
            </div>

            <!-- Single Clean Borderless Table: No eye-strain row borders -->
            <div class="overflow-x-auto rounded-xl bg-slate-950/20 custom-scrollbar border border-slate-800/60">
              <table class="w-full text-left border-collapse" id="selfLearningCleanTable">
                <thead>
                  <tr class="bg-slate-950/90 text-[11px] font-bold text-slate-300 uppercase tracking-wider border-b border-slate-800/80">
                    <th class="py-3 px-3 w-14 text-center">Roll</th>
                    <th class="py-3 px-3 w-32">SBTE Reg No</th>
                    <th class="py-3 px-3 min-w-[180px]">Student Name</th>
                    <th class="py-3 px-3 w-28 text-center text-blue-300 bg-blue-950/20">M1 / CA1 (15)</th>
                    <th class="py-3 px-3 w-28 text-center text-sky-300 bg-sky-950/20">M2 / CA2 (15)</th>
                    <th class="py-3 px-3 w-28 text-center text-cyan-300 bg-cyan-950/20">M3 / CA3 (15)</th>
                    <th class="py-3 px-3 w-28 text-center text-indigo-300 bg-indigo-950/20">M4 / CA4 (15)</th>
                    <th class="py-3 px-3 w-32 text-center text-amber-300 bg-amber-950/30">SL Total (15)</th>
                  </tr>
                </thead>
                <tbody class="text-sm font-normal">
                  @forelse($studentResults as $st)
                    <tr class="sla-student-row transition-colors hover:bg-slate-800/40 even:bg-slate-900/30 odd:bg-transparent" data-reg-no="{{ $st['reg_no'] }}">
                      <td class="py-3 px-3 font-mono text-center text-cyan-400 font-bold text-xs sm:text-sm border-0">{{ $st['roll_no'] ?: '-' }}</td>
                      <td class="py-3 px-3 font-mono text-xs sm:text-sm font-semibold text-slate-300 border-0">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                      <td class="py-3 px-3 text-sm sm:text-base font-bold text-white border-0">{{ $st['name'] }}</td>
                      
                      <!-- M1 (CA1) -->
                      <td class="py-2.5 px-3 text-center bg-blue-950/10 border-0">
                        <input type="number" step="0.5" min="0" max="15" 
                               value="{{ $st['ca1_15'] !== null ? $st['ca1_15'] : '' }}" 
                               data-prev-val="{{ $st['ca1_15'] !== null ? $st['ca1_15'] : '' }}"
                               data-max="15"
                               data-field-name="M1 / CA1 (Max 15M)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-module="1"
                               placeholder="-" 
                               class="cell-sla-m1 sla-m1 no-spinners w-20 sm:w-24 text-center px-2 py-1.5 text-sm font-bold font-mono text-slate-100 bg-slate-950/80 border border-slate-700/60 rounded-lg focus:border-blue-400 focus:ring-1 focus:ring-blue-400/40 outline-none transition-all"
                               oninput="handleSlaInputChange('{{ $st['reg_no'] }}', 1, this.value);">
                      </td>

                      <!-- M2 (CA2) -->
                      <td class="py-2.5 px-3 text-center bg-sky-950/10 border-0">
                        <input type="number" step="0.5" min="0" max="15" 
                               value="{{ $st['ca2_15'] !== null ? $st['ca2_15'] : '' }}" 
                               data-prev-val="{{ $st['ca2_15'] !== null ? $st['ca2_15'] : '' }}"
                               data-max="15"
                               data-field-name="M2 / CA2 (Max 15M)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-module="2"
                               placeholder="-" 
                               class="cell-sla-m2 sla-m2 no-spinners w-20 sm:w-24 text-center px-2 py-1.5 text-sm font-bold font-mono text-slate-100 bg-slate-950/80 border border-slate-700/60 rounded-lg focus:border-sky-400 focus:ring-1 focus:ring-sky-400/40 outline-none transition-all"
                               oninput="handleSlaInputChange('{{ $st['reg_no'] }}', 2, this.value);">
                      </td>

                      <!-- M3 (CA3) -->
                      <td class="py-2.5 px-3 text-center bg-cyan-950/10 border-0">
                        <input type="number" step="0.5" min="0" max="15" 
                               value="{{ $st['ca3_15'] !== null ? $st['ca3_15'] : '' }}" 
                               data-prev-val="{{ $st['ca3_15'] !== null ? $st['ca3_15'] : '' }}"
                               data-max="15"
                               data-field-name="M3 / CA3 (Max 15M)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-module="3"
                               placeholder="-" 
                               class="cell-sla-m3 sla-m3 no-spinners w-20 sm:w-24 text-center px-2 py-1.5 text-sm font-bold font-mono text-slate-100 bg-slate-950/80 border border-slate-700/60 rounded-lg focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/40 outline-none transition-all"
                               oninput="handleSlaInputChange('{{ $st['reg_no'] }}', 3, this.value);">
                      </td>

                      <!-- M4 (CA4) -->
                      <td class="py-2.5 px-3 text-center bg-indigo-950/10 border-0">
                        <input type="number" step="0.5" min="0" max="15" 
                               value="{{ $st['ca4_15'] !== null ? $st['ca4_15'] : '' }}" 
                               data-prev-val="{{ $st['ca4_15'] !== null ? $st['ca4_15'] : '' }}"
                               data-max="15"
                               data-field-name="M4 / CA4 (Max 15M)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-module="4"
                               placeholder="-" 
                               class="cell-sla-m4 sla-m4 no-spinners w-20 sm:w-24 text-center px-2 py-1.5 text-sm font-bold font-mono text-slate-100 bg-slate-950/80 border border-slate-700/60 rounded-lg focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400/40 outline-none transition-all"
                               oninput="handleSlaInputChange('{{ $st['reg_no'] }}', 4, this.value);">
                      </td>

                      <!-- SL Total (15) -->
                      <td class="py-2.5 px-3 text-center bg-amber-950/20 border-0">
                        <span class="sla-row-avg text-sm sm:text-base font-extrabold text-amber-300 bg-amber-950/50 px-3 py-1 rounded-lg border border-amber-500/40 inline-block font-mono min-w-[50px]">
                          {{ number_format($st['self_learning_15'] ?? 0, 1) }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- MODAL: STUDENT ROSTER DIRECTORY -->
        <div id="modal-student-roster" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
          <div class="bg-panel border border-slate-700/80 rounded-2xl max-w-3xl w-full p-5 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center border-b border-slate-800/80 pb-3">
              <div>
                <h3 class="text-sm font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-sky-400 text-base">group</span>
                  Student Enrollment Directory ({{ $students->count() }})
                </h3>
                <p class="text-xs text-muted mt-0.5">Enrolled students for {{ $batchSubject->subject_name }} ({{ $batchSubject->classroom_id }})</p>
              </div>
              <button type="button" onclick="closeRosterModal()" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all cursor-pointer text-xs font-bold">
                ✕ Close
              </button>
            </div>

            <div class="border border-slate-800 rounded-xl overflow-y-auto bg-slate-950/30 flex-1 custom-scrollbar">
              <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-slate-900 z-10">
                  <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                    <th class="p-3 w-[10%] text-center">Roll No</th>
                    <th class="p-3 w-[25%]">SBTE Reg No</th>
                    <th class="p-3">Student Name</th>
                    <th class="p-3 w-[20%] text-right">Academic Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                  @forelse($students as $student)
                    <tr class="hover:bg-slate-800/30 transition-all">
                      <td class="p-2.5 font-mono text-center font-bold text-muted">{{ $student->roll_no ?? '-' }}</td>
                      <td class="p-2.5 font-mono font-bold text-title">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                      <td class="p-2.5 font-bold text-title">{{ $student->name }}</td>
                      <td class="p-2.5 text-right">
                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-md text-xs font-bold select-none">{{ $student->academic_status }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="p-6 text-center text-muted italic">No students assigned to this classroom yet.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-800">
              <button type="button" onclick="closeRosterModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold transition-all cursor-pointer">
                Close
              </button>
            </div>
          </div>
        </div>

        <!-- SERIES EXAMS TAB PANEL -->
        <div id="tab-series" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 {{ $activeTab === 'series' ? '' : 'hidden' }}">
          <div class="border-b border-slate-800/30 pb-3 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-sky-400">quiz</span>
                <span>Series Examinations (Theory) - Rev 2026</span>
              </h3>
              <p class="text-xs text-slate-400 mb-0">
                Series Exam 1 (Modules 1 &amp; 2 - Max 50M &rarr; 20M) &bull; Series Exam 2 (Modules 3 &amp; 4 - Max 50M &rarr; 20M) &bull; <strong class="text-emerald-400">Combined Series (20M)</strong>
              </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              <a href="/r26/classroom/theory/{{ $batchSubject->id }}/print/series-register" target="_blank" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold transition-all flex items-center gap-1 no-underline">
                <span class="material-symbols-rounded text-sm">print</span>
                <span>Print Series Register</span>
              </a>
              @if(!$seriesExams->isEmpty())
                <button onclick="resetSeriesExamsConfig()" class="px-2.5 py-1 bg-rose-600/10 hover:bg-rose-600/20 text-rose-450 border border-rose-500/20 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">restart_alt</span> Reconfigure
                </button>
              @endif
              <button onclick="triggerConsolidatedManualSave()" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1 cursor-pointer shadow-sm">
                <span class="material-symbols-rounded text-sm">save</span>
                <span>Save Series Marks</span>
              </button>
            </div>
          </div>

          @if($seriesExams->isEmpty())
            <!-- Unconfigured Pattern State: Prominent Setup Banner -->
            <div class="bg-gradient-to-r from-sky-950/40 via-blue-950/30 to-slate-900/40 border border-sky-500/30 rounded-2xl p-5 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
              <div class="space-y-1">
                <h4 class="font-bold text-sky-400 text-sm flex items-center gap-2">
                  <span class="material-symbols-rounded text-lg">tune</span>
                  Configure Series Examination Pattern
                </h4>
                <p class="text-xs text-slate-300">
                  Revision 2026 Diploma Curriculum mandates 2 Combined-CO Series Exams (50M each: Series 1 = CO1+CO2, Series 2 = CO3+CO4). Total marks scaled to 20 CIE.
                </p>
              </div>
              <button type="button" onclick="quickSetupCombinedSeriesPattern()" class="shrink-0 px-4 py-2.5 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-500 hover:to-blue-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-rounded text-base">settings_suggest</span>
                Setup Series Exam Pattern (2 Combined Tests &bull; 50M)
              </button>
            </div>
          @else
            <!-- Configured Exams State: Question Papers & Schemes Cards -->
            <div class="space-y-3">
              <h4 class="font-bold text-title text-xs uppercase tracking-wider">Scheduled Series Examinations (QPs &amp; Schemes)</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                @foreach($seriesExams as $exam)
                  @php
                    $firstCo = $exam->co_tags[0] ?? 'CO1';
                    $borderColor = 'border-l-sky-500';
                    $bgColor = 'bg-sky-500/10';
                    $textColor = 'text-sky-600 dark:text-sky-400';
                    if ($firstCo === 'CO2') {
                      $borderColor = 'border-l-emerald-500';
                      $bgColor = 'bg-emerald-500/10';
                      $textColor = 'text-emerald-600 dark:text-emerald-400';
                    } elseif ($firstCo === 'CO3') {
                      $borderColor = 'border-l-blue-500';
                      $bgColor = 'bg-blue-500/10';
                      $textColor = 'text-blue-600 dark:text-blue-400';
                    } elseif ($firstCo === 'CO4') {
                      $borderColor = 'border-l-purple-500';
                      $bgColor = 'bg-purple-500/10';
                      $textColor = 'text-purple-600 dark:text-purple-400';
                    }
                  @endphp
                  <div class="bg-white dark:bg-slate-900/30 border border-slate-200 dark:border-slate-800 border-l-4 {{ $borderColor }} rounded-xl p-3.5 flex flex-col justify-between gap-3 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                      <div class="flex items-center gap-2.5">
                        <div class="px-2.5 py-0.5 rounded-md {{ $bgColor }} {{ $textColor }} font-bold text-xs tracking-wider uppercase">
                          {{ implode(' + ', $exam->co_tags) }}
                        </div>
                        <div>
                          <h5 class="font-bold text-slate-800 dark:text-slate-100 text-xs">{{ $exam->exam_name }}</h5>
                          <span class="text-[11px] text-slate-500 dark:text-slate-400">Max: {{ $exam->max_marks }}M &bull; {{ $exam->duration_minutes }} min</span>
                        </div>
                      </div>
                      @if($exam->locked)
                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded text-[10.5px] font-semibold flex items-center gap-1 select-none">
                          <span class="material-symbols-rounded text-xs">lock</span> Locked
                        </span>
                      @else
                        <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded text-[10.5px] font-semibold flex items-center gap-1 select-none">
                          <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Draft
                        </span>
                      @endif
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap pt-1 border-t border-slate-800/40">
                      @if($exam->locked)
                        <button onclick='openSeriesBuilderModal({{ $exam->id }}, "{{ addslashes($exam->exam_name) }}", "{{ $exam->mode }}", {{ json_encode($exam->co_tags) }}, {{ $exam->max_marks }})' class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded text-[11px] font-bold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                          <span class="material-symbols-rounded text-xs">visibility</span> View QP
                        </button>
                      @else
                        <button onclick='openSeriesBuilderModal({{ $exam->id }}, "{{ addslashes($exam->exam_name) }}", "{{ $exam->mode }}", {{ json_encode($exam->co_tags) }}, {{ $exam->max_marks }})' class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-bold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                          <span class="material-symbols-rounded text-xs">edit_document</span> Build QP
                        </button>
                      @endif
                      <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-qp" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded text-[11px] font-bold transition-all flex items-center gap-1 shadow-xs no-underline">
                        <span class="material-symbols-rounded text-xs">print</span> QP
                      </a>
                      <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-scheme" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded text-[11px] font-bold transition-all flex items-center gap-1 shadow-xs no-underline">
                        <span class="material-symbols-rounded text-xs">description</span> Scheme
                      </a>
                      @if(!$exam->locked)
                        <button onclick="lockAndPublishSeries({{ $exam->id }})" class="px-2 py-1 bg-violet-600 hover:bg-violet-700 text-white rounded text-[11px] font-bold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                          <span class="material-symbols-rounded text-xs">publish</span> Lock
                        </button>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Detailed Marksheet in Series Tab (Larger font text-sm, CO1-4 separate inputs) -->
          <div class="space-y-3 pt-2">
            <div class="flex flex-wrap justify-between items-center gap-2">
              <div>
                <h4 class="font-bold text-title text-sm uppercase tracking-wider flex items-center gap-2">
                  <span class="material-symbols-rounded text-sky-400 text-base">table_chart</span>
                  Series Exam Detailed Marksheet
                </h4>
                <p class="text-xs text-muted">Direct CO mark entry. Autosaves on typing with bidirectional sync to CIE Register.</p>
              </div>
              <div class="flex items-center gap-1.5 flex-wrap">
                <span id="seriesMarksAutosaveBadge" class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved
                </span>
                <button onclick="triggerConsolidatedManualSave()" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold transition cursor-pointer shadow-xs flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs font-bold">save</span> Save Marks
                </button>
              </div>
            </div>

            <!-- Series Dedicated Table -->
            <div class="overflow-x-auto border border-card rounded-xl bg-slate-950/10 custom-scrollbar">
              <table class="w-full border-collapse text-left" id="seriesDedicatedTable">
                <thead>
                  <tr class="bg-slate-950/80 text-slate-300 text-xs font-bold">
                    <th rowspan="2" class="border border-slate-800 text-center w-12">#</th>
                    <th rowspan="2" class="border border-slate-800 w-28">Reg No</th>
                    <th rowspan="2" class="border border-slate-800 min-w-[160px]">Student Name</th>
                    <th colspan="5" class="border border-slate-800 text-center bg-blue-950/40 text-blue-300">Series Exam 1 (Modules 1 &amp; 2 - Max 50M)</th>
                    <th colspan="5" class="border border-slate-800 text-center bg-sky-950/40 text-sky-300">Series Exam 2 (Modules 3 &amp; 4 - Max 50M)</th>
                    <th rowspan="2" class="border border-slate-800 text-center w-28 bg-emerald-950/40 text-emerald-300">Combined<br>Series (20M)</th>
                  </tr>
                  <tr class="bg-slate-950/90 text-slate-400 text-xs">
                    <th class="border border-slate-800 text-center w-16" title="Module 1 / CO1 (Max 25)">CO1 (25)</th>
                    <th class="border border-slate-800 text-center w-16" title="Module 2 / CO2 (Max 25)">CO2 (25)</th>
                    <th class="border border-slate-800 text-center w-16 font-semibold text-slate-300" title="Total S1 Marks (Max 50)">Total (50)</th>
                    <th class="border border-slate-800 text-center w-14">Absent</th>
                    <th class="border border-slate-800 text-center w-16 text-blue-300 font-bold">Conv (20)</th>
                    <th class="border border-slate-800 text-center w-16" title="Module 3 / CO3 (Max 25)">CO3 (25)</th>
                    <th class="border border-slate-800 text-center w-16" title="Module 4 / CO4 (Max 25)">CO4 (25)</th>
                    <th class="border border-slate-800 text-center w-16 font-semibold text-slate-300" title="Total S2 Marks (Max 50)">Total (50)</th>
                    <th class="border border-slate-800 text-center w-14">Absent</th>
                    <th class="border border-slate-800 text-center w-16 text-sky-300 font-bold">Conv (20)</th>
                  </tr>
                </thead>
                <tbody class="text-sm font-normal">
                  @forelse($studentResults as $index => $st)
                    <tr class="series-eval-row hover:bg-slate-800/40 even:bg-slate-900/30 odd:bg-transparent transition-colors" data-reg-no="{{ $st['reg_no'] }}">
                      <td class="border-0 text-center text-xs text-slate-400 py-3 px-2">{{ $index + 1 }}</td>
                      <td class="border-0 font-mono text-xs text-slate-300 py-3 px-2">{{ $st['sbte_reg_no'] ?: $st['reg_no'] }}</td>
                      <td class="border-0 text-xs font-semibold text-white py-3 px-2">{{ $st['name'] }}</td>

                      <!-- S1 CO1 Marks (25) -->
                      <td class="border-0 p-1.5 text-center">
                        <input type="text" inputmode="decimal" 
                               value="{{ $st['series_1_co1_25'] !== null ? (float)$st['series_1_co1_25'] : '' }}" 
                               data-prev-val="{{ $st['series_1_co1_25'] !== null ? (float)$st['series_1_co1_25'] : '' }}"
                               data-max="25"
                               data-field-name="Series 1 CO1 (Module 1)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-student-name="{{ addslashes($st['name']) }}"
                               data-co-type="co1"
                               placeholder="-" 
                               class="series-tab-s1-co1 no-spinners w-14 text-center px-1.5 py-1 text-sm font-semibold text-slate-100 bg-slate-950 border border-slate-700/60 rounded focus:border-blue-500 outline-none" 
                               title="Series 1 CO1 (Module 1) out of 25 (supports 1 decimal, e.g. 11.5)"
                               oninput="handleSeriesCoInput(this, '{{ $st['reg_no'] }}', 'co1')"
                               onblur="handleSeriesCoBlur(this, '{{ $st['reg_no'] }}', 'co1')">
                      </td>

                      <!-- S1 CO2 Marks (25) -->
                      <td class="border-0 p-1.5 text-center">
                        <input type="text" inputmode="decimal" 
                               value="{{ $st['series_1_co2_25'] !== null ? (float)$st['series_1_co2_25'] : '' }}" 
                               data-prev-val="{{ $st['series_1_co2_25'] !== null ? (float)$st['series_1_co2_25'] : '' }}"
                               data-max="25"
                               data-field-name="Series 1 CO2 (Module 2)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-student-name="{{ addslashes($st['name']) }}"
                               data-co-type="co2"
                               placeholder="-" 
                               class="series-tab-s1-co2 no-spinners w-14 text-center px-1.5 py-1 text-sm font-semibold text-slate-100 bg-slate-950 border border-slate-700/60 rounded focus:border-blue-500 outline-none" 
                               title="Series 1 CO2 (Module 2) out of 25 (supports 1 decimal, e.g. 11.5)"
                               oninput="handleSeriesCoInput(this, '{{ $st['reg_no'] }}', 'co2')"
                               onblur="handleSeriesCoBlur(this, '{{ $st['reg_no'] }}', 'co2')">
                      </td>

                      <!-- S1 Total (50) -->
                      <td class="border-0 text-center font-mono text-sm font-bold text-slate-300 py-3 cell-series-tab-s1-total series-tab-s1-total">
                        {{ $st['series_1_50'] !== null ? number_format($st['series_1_50'], 1) : '-' }}
                      </td>

                      <!-- S1 Absent -->
                      <td class="border-0 text-center py-3">
                        <input type="checkbox" class="series-tab-s1-abs cursor-pointer" 
                               {{ !empty($st['series_1_absent']) ? 'checked' : '' }} 
                               onchange="syncSeriesAbsToMain('{{ $st['reg_no'] }}', 's1', this.checked); queueAutoSave();">
                      </td>

                      <!-- S1 Converted (20) -->
                      <td class="border-0 text-center font-bold text-sm text-blue-300 py-3 cell-series-tab-s1-20 series-tab-s1-20">
                        {{ $st['series_1_absent'] ? 'AB' : ($st['series_1_50'] !== null ? number_format($st['series_1_20'] ?? 0, 1) : '-') }}
                      </td>

                      <!-- S2 CO3 Marks (25) -->
                      <td class="border-0 p-1.5 text-center">
                        <input type="text" inputmode="decimal" 
                               value="{{ $st['series_2_co3_25'] !== null ? (float)$st['series_2_co3_25'] : '' }}" 
                               data-prev-val="{{ $st['series_2_co3_25'] !== null ? (float)$st['series_2_co3_25'] : '' }}"
                               data-max="25"
                               data-field-name="Series 2 CO3 (Module 3)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-student-name="{{ addslashes($st['name']) }}"
                               data-co-type="co3"
                               placeholder="-" 
                               class="series-tab-s2-co3 no-spinners w-14 text-center px-1.5 py-1 text-sm font-semibold text-slate-100 bg-slate-950 border border-slate-700/60 rounded focus:border-blue-500 outline-none" 
                               title="Series 2 CO3 (Module 3) out of 25 (supports 1 decimal, e.g. 11.5)"
                               oninput="handleSeriesCoInput(this, '{{ $st['reg_no'] }}', 'co3')"
                               onblur="handleSeriesCoBlur(this, '{{ $st['reg_no'] }}', 'co3')">
                      </td>

                      <!-- S2 CO4 Marks (25) -->
                      <td class="border-0 p-1.5 text-center">
                        <input type="text" inputmode="decimal" 
                               value="{{ $st['series_2_co4_25'] !== null ? (float)$st['series_2_co4_25'] : '' }}" 
                               data-prev-val="{{ $st['series_2_co4_25'] !== null ? (float)$st['series_2_co4_25'] : '' }}"
                               data-max="25"
                               data-field-name="Series 2 CO4 (Module 4)"
                               data-reg-no="{{ $st['reg_no'] }}"
                               data-student-name="{{ addslashes($st['name']) }}"
                               data-co-type="co4"
                               placeholder="-" 
                               class="series-tab-s2-co4 no-spinners w-14 text-center px-1.5 py-1 text-sm font-semibold text-slate-100 bg-slate-950 border border-slate-700/60 rounded focus:border-blue-500 outline-none" 
                               title="Series 2 CO4 (Module 4) out of 25 (supports 1 decimal, e.g. 11.5)"
                               oninput="handleSeriesCoInput(this, '{{ $st['reg_no'] }}', 'co4')"
                               onblur="handleSeriesCoBlur(this, '{{ $st['reg_no'] }}', 'co4')">
                      </td>

                      <!-- S2 Total (50) -->
                      <td class="border-0 text-center font-mono text-sm font-bold text-slate-300 py-3 cell-series-tab-s2-total series-tab-s2-total">
                        {{ $st['series_2_50'] !== null ? number_format($st['series_2_50'], 1) : '-' }}
                      </td>

                      <!-- S2 Absent -->
                      <td class="border-0 text-center py-3">
                        <input type="checkbox" class="series-tab-s2-abs cursor-pointer" 
                               {{ !empty($st['series_2_absent']) ? 'checked' : '' }} 
                               onchange="syncSeriesAbsToMain('{{ $st['reg_no'] }}', 's2', this.checked); queueAutoSave();">
                      </td>

                      <!-- S2 Converted (20) -->
                      <td class="border-0 text-center font-bold text-sm text-sky-300 py-3 cell-series-tab-s2-20 series-tab-s2-20">
                        {{ $st['series_2_absent'] ? 'AB' : ($st['series_2_50'] !== null ? number_format($st['series_2_20'] ?? 0, 1) : '-') }}
                      </td>

                      <!-- Combined Series (20M) -->
                      <td class="border-0 text-center font-bold text-sm text-emerald-400 bg-emerald-950/20 py-3 cell-series-tab-comb-20 series-tab-comb-20">
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


        <!-- TAB: COURSE ATTAINMENT & SURVEYS (NEW) -->
        <div id="tab-attainment" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 {{ $activeTab === 'attainment' ? '' : 'hidden' }}">
          <div class="border-b border-slate-800/30 pb-3 flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-sky-400">equalizer</span>
                Course Attainment & Surveys
              </h3>
              <p class="text-xs text-muted mt-1">
                Access surveys and generate PO/CO attainment calculations for Revision 2026.
              </p>
            </div>
          </div>

          <div class="space-y-4">
            <!-- 2-COLUMN GRID FOR MID-SEM AND EXIT SURVEYS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <!-- Mid Sem Survey -->
              <div class="bg-slate-950 border border-slate-800 hover:border-slate-700 rounded-xl p-4 flex flex-col justify-between space-y-3 shadow-sm transition">
                <div>
                  <div class="flex items-center justify-between gap-2 flex-wrap mb-2">
                    <div class="flex items-center gap-2.5">
                      <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-rounded text-base">rate_review</span>
                      </div>
                      <div>
                        <h4 class="font-bold text-title text-sm">Mid-Semester Online Survey</h4>
                        <p class="text-[11px] text-muted">SAR Criterion 2 Evaluation</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <button id="btn-initiate-midsem-tab" onclick="document.getElementById('modal-midsem-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                      <button id="btn-close-midsem-tab" onclick="controlSurvey('midsem', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                      <span id="status-midsem-tab" class="text-xs font-bold text-muted bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
                    </div>
                  </div>
                  <p class="text-xs text-slate-300 leading-relaxed border-t border-slate-800/40 pt-2.5">
                    Allows students to submit feedback online. Captures direct feedback on course delivery, syllabus coverage, and early corrective action points.
                  </p>
                </div>
              </div>

              <!-- Course Exit Survey -->
              <div class="bg-slate-950 border border-slate-800 hover:border-slate-700 rounded-xl p-4 flex flex-col justify-between space-y-3 shadow-sm transition">
                <div>
                  <div class="flex items-center justify-between gap-2 flex-wrap mb-2">
                    <div class="flex items-center gap-2.5">
                      <div class="w-8 h-8 rounded-lg bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-rounded text-base">assignment_turned_in</span>
                      </div>
                      <div>
                        <h4 class="font-bold text-title text-sm">Course Exit Survey (Indirect CO)</h4>
                        <p class="text-[11px] text-muted">Indirect Attainment Assessment</p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <button id="btn-initiate-exit-tab" onclick="document.getElementById('modal-exit-survey-init').classList.remove('hidden')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                      <button id="btn-close-exit-tab" onclick="controlSurvey('exit', 'close')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                      <span id="status-exit-tab" class="text-xs font-bold text-muted bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1 flex items-center">Checking status...</span>
                    </div>
                  </div>
                  <p class="text-xs text-slate-300 leading-relaxed border-t border-slate-800/40 pt-2.5">
                    Evaluates indirect Course Outcome (CO) attainment parameters at semester-end. Necessary for final PO mapping calculations.
                  </p>
                </div>
              </div>
            </div>

            @php
              $attainmentSettings = is_array($courseFile->attainment_settings ?? null)
                  ? $courseFile->attainment_settings
                  : (json_decode($courseFile->attainment_settings ?? '', true) ?: []);
              $eseConfig = $attainmentSettings['ese_config'] ?? [];
              $selectedEseGrade = $eseConfig['ese_threshold_grade'] ?? $eseConfig['target_grade'] ?? 'D';
              $cieThreshold = $eseConfig['cie_threshold_percent'] ?? 50;
              $targetStudentPct = $eseConfig['target_student_percent'] ?? 70;
              $lvl3Pct = $eseConfig['level3_percent'] ?? $targetStudentPct;
              $lvl2Pct = $eseConfig['level2_percent'] ?? max(0, $targetStudentPct - 10);
              $lvl1Pct = $eseConfig['level1_percent'] ?? max(0, $targetStudentPct - 20);

              // Server-side precalculation of batch metrics
              $sbtePoints = ['S' => 10, 'A' => 9, 'B' => 8, 'C' => 7, 'D' => 6, 'E' => 5, 'F' => 0, 'FE' => 0];
              $minReqPts = $sbtePoints[$selectedEseGrade] ?? 6;
              $initTotalStudents = isset($students) ? $students->count() : count($studentCiaData ?? []);
              $initAppeared = 0;
              $initMet = 0;
              foreach (($studentCiaData ?? []) as $sc) {
                  $g = strtoupper(trim($sc['ese_grade'] ?? ''));
                  $m = (float)($sc['ese_marks'] ?? 0);
                  if (($g !== '' && $g !== 'FE') || $m > 0) {
                      $initAppeared++;
                      $pts = $sbtePoints[$g] ?? 0;
                      $markPct = ($m / 60.0) * 100.0;
                      if (($pts >= 5 && $pts >= $minReqPts) || ($m > 0 && $markPct >= ($minReqPts >= 6 ? 50 : 40))) {
                          $initMet++;
                      }
                  }
              }
              $initMetPct = $initAppeared > 0 ? round(($initMet / $initAppeared) * 100, 1) : 0.0;
              if ($initAppeared > 0) {
                  if ($initMetPct >= $lvl3Pct) {
                      $initLevelText = "Level 3 (High - {$initMetPct}%)";
                      $initLevelClass = "text-emerald-400";
                  } elseif ($initMetPct >= $lvl2Pct) {
                      $initLevelText = "Level 2 (Mod - {$initMetPct}%)";
                      $initLevelClass = "text-amber-400";
                  } elseif ($initMetPct >= $lvl1Pct) {
                      $initLevelText = "Level 1 (Low - {$initMetPct}%)";
                      $initLevelClass = "text-blue-400";
                  } else {
                      $initLevelText = "Level 0 (Nil - {$initMetPct}%)";
                      $initLevelClass = "text-rose-400";
                  }
              } else {
                  $initLevelText = "Pending ESE Data";
                  $initLevelClass = "text-slate-400";
              }
            @endphp

            <!-- NBA Attainment Threshold Config & Target Criteria Card (Matching 2021 Model) -->
            <div class="bg-slate-900/30 border border-slate-800 rounded-xl p-4 sm:p-5 space-y-4 shadow-sm">
              <div class="border-b border-slate-800 pb-3 flex justify-between items-center flex-wrap gap-3">
                <div>
                  <h4 class="font-bold text-title text-sm flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-emerald-400 text-base">tune</span>
                    <span>NBA Attainment Threshold Config & ESE Evaluation Settings</span>
                  </h4>
                  <p class="text-[11px] text-muted mt-0.5">Configure threshold marks/grades for CIE and ESE exams, target student percentage, and batch attainment criteria.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                  <button type="button" onclick="saveTheoryAttainmentSettings()" id="btnSaveThresholdSettings" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-[11px] font-semibold transition-all flex items-center gap-1 shadow-xs cursor-pointer">
                    <span class="material-symbols-rounded text-[13px]">save</span>
                    <span>Save Threshold Settings</span>
                  </button>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-[11px] font-semibold transition-all flex items-center gap-1 shadow-xs no-underline">
                    <span class="material-symbols-rounded text-[13px]">print</span>
                    <span>Print Final NBA Attainment Report</span>
                  </a>
                </div>
              </div>

              <!-- Threshold Config Grid (Exact 2021 Layout) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Card 1: Assessment Threshold Settings -->
                <div class="bg-slate-950/60 border border-slate-800/80 p-3 rounded-xl space-y-2">
                  <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
                    <span class="text-xs font-black text-slate-200 uppercase tracking-wider">1. Assessment Threshold Settings</span>
                    <span class="text-[10px] font-bold text-sky-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-700">CIE & ESE Targets</span>
                  </div>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                      <label class="block text-[10px] font-bold text-slate-400 mb-1">ESE Threshold Grade (SBTE)</label>
                      <select id="attainmentEseGrade" onchange="recalculateTheoryAttainmentStats(false)" class="w-full bg-slate-900 border border-slate-700 text-teal-400 font-bold text-xs px-2 py-1.5 rounded-lg outline-none focus:border-teal-500">
                        <option value="E" {{ $selectedEseGrade === 'E' ? 'selected' : '' }}>E Grade & Above (Pass - 40%+ | 5 GP)</option>
                        <option value="D" {{ $selectedEseGrade === 'D' ? 'selected' : '' }}>D Grade & Above (Average - 50%+ | 6 GP)</option>
                        <option value="C" {{ $selectedEseGrade === 'C' ? 'selected' : '' }}>C Grade & Above (Good - 60%+ | 7 GP)</option>
                        <option value="B" {{ $selectedEseGrade === 'B' ? 'selected' : '' }}>B Grade & Above (Very Good - 70%+ | 8 GP)</option>
                        <option value="A" {{ $selectedEseGrade === 'A' ? 'selected' : '' }}>A Grade & Above (Excellent - 80%+ | 9 GP)</option>
                        <option value="S" {{ $selectedEseGrade === 'S' ? 'selected' : '' }}>S Grade (Outstanding - 90%+ | 10 GP)</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-[10px] font-bold text-slate-400 mb-1">Internal (CIE) Threshold (%)</label>
                      <input type="number" id="attainmentCiePct" value="{{ $cieThreshold }}" min="30" max="90" step="1" oninput="recalculateTheoryAttainmentStats(false)" class="w-full bg-slate-900 border border-slate-700 text-sky-400 font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg outline-none focus:border-blue-500">
                    </div>
                  </div>
                </div>

                <!-- Card 2: Batch Target & Attainment Levels -->
                <div class="bg-slate-950/60 border border-slate-800/80 p-3 rounded-xl space-y-2">
                  <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
                    <span class="text-xs font-black text-slate-200 uppercase tracking-wider">2. Batch Target & Attainment Levels</span>
                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/50">NBA Criteria</span>
                  </div>

                  <div class="grid grid-cols-4 gap-2">
                    <div class="bg-slate-900/80 border border-emerald-500/40 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                      <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Target (T)</span>
                      <div class="flex items-center justify-center gap-0.5 mt-0.5">
                        <input type="number" id="attainmentTargetPct" value="{{ $targetStudentPct }}" min="30" max="100" step="1" oninput="recalculateTheoryAttainmentStats(true)" class="w-12 bg-transparent text-emerald-400 font-mono font-black text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                      </div>
                    </div>

                    <div class="bg-slate-900/80 border border-emerald-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                      <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Level 3 (High)</span>
                      <div class="flex items-center justify-center gap-0.5 mt-0.5">
                        <span class="text-[10px] text-emerald-300 font-bold">&ge;</span>
                        <input type="number" id="attainmentLevel3Pct" value="{{ $lvl3Pct }}" min="0" max="100" step="1" oninput="recalculateTheoryAttainmentStats(false)" class="w-10 bg-transparent text-emerald-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                      </div>
                    </div>

                    <div class="bg-slate-900/80 border border-amber-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                      <span class="block text-[9px] font-bold text-amber-400 uppercase tracking-tight">Level 2 (Mod)</span>
                      <div class="flex items-center justify-center gap-0.5 mt-0.5">
                        <span class="text-[10px] text-amber-300 font-bold">&ge;</span>
                        <input type="number" id="attainmentLevel2Pct" value="{{ $lvl2Pct }}" min="0" max="100" step="1" oninput="recalculateTheoryAttainmentStats(false)" class="w-10 bg-transparent text-amber-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                      </div>
                    </div>

                    <div class="bg-slate-900/80 border border-blue-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                      <span class="block text-[9px] font-bold text-blue-400 uppercase tracking-tight">Level 1 (Low)</span>
                      <div class="flex items-center justify-center gap-0.5 mt-0.5">
                        <span class="text-[10px] text-blue-300 font-bold">&ge;</span>
                        <input type="number" id="attainmentLevel1Pct" value="{{ $lvl1Pct }}" min="0" max="100" step="1" oninput="recalculateTheoryAttainmentStats(false)" class="w-10 bg-transparent text-blue-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <span class="text-[10px] text-slate-400 font-bold">%</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Summary & Batch Metrics Bar -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-slate-950/80 p-3.5 rounded-xl border border-slate-800/80 font-mono">
                <div>
                  <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Max Batch Students</span>
                  <span id="statTheoryTotalStudents" class="text-sm font-black text-slate-200">{{ $initTotalStudents }}</span>
                </div>
                <div>
                  <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Students Appeared</span>
                  <span id="statTheoryAppearedStudents" class="text-sm font-black text-blue-400">{{ $initAppeared }}</span>
                </div>
                <div>
                  <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">Met Target Threshold</span>
                  <span id="statTheoryMetTargetText" class="text-sm font-black text-emerald-400">{{ $initMet }} ({{ $initMetPct }}%)</span>
                </div>
                <div>
                  <span class="block text-[10px] font-bold text-slate-500 uppercase font-sans">ESE Attainment Level</span>
                  <span id="statTheoryEseLevelText" class="text-sm font-black {{ $initLevelClass }}">{{ $initLevelText }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL: MID-SEM SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-midsem-survey-init" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200">
          <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto" style="background-color: #0f172a !important;">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400">rate_review</span>
                Preview & Edit Mid-Semester Survey Questions
              </h3>
              <button type="button" onclick="document.getElementById('modal-midsem-survey-init').classList.add('hidden')" class="text-slate-400 hover:text-slate-200 cursor-pointer bg-transparent border-0">
                <span class="material-symbols-rounded">close</span>
              </button>
            </div>
            
            <p class="text-sm text-muted leading-relaxed">
              Review or customize the survey questions below before activating. Students will submit responses matching these descriptions.
            </p>

            <form id="form-midsem-init" onsubmit="submitMidsemInit(event)" class="space-y-4">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q1. CO1 - Course Outcomes Communication</label>
                  <input type="text" id="ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
                  <input type="text" id="ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q3. CO2 - Concept Clarity & Application</label>
                  <input type="text" id="ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q4. CO2 - Effectiveness of ICT/PPT Tools</label>
                  <input type="text" id="ms-q8" value="The use of teaching tools, animations, PPTs, model demonstrations, or ICT tools is effective." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q5. CO3 - Doubt Clearing & Interaction</label>
                  <input type="text" id="ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q6. CO3 - Test & Assignment Relevance</label>
                  <input type="text" id="ms-q10" value="Internal assessment test questions and assignments match the topics taught in class." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q7. CO4 - Fairness in Evaluation</label>
                  <input type="text" id="ms-q11" value="Evaluation of mid-semester tests or submissions is fair, timely, and transparent." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q8. CO4 - Guidance for Slow Learners</label>
                  <input type="text" id="ms-q12" value="The teacher provides extra guidance, remedial tips, or support to slow learners." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-midsem-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-xs transition-all cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-bold text-xs transition-all cursor-pointer">Activate & Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-exit-survey-init" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200">
          <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto" style="background-color: #0f172a !important;">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400">rate_review</span>
                Preview & Edit Course Exit Survey Questions
              </h3>
              <button type="button" onclick="document.getElementById('modal-exit-survey-init').classList.add('hidden')" class="text-slate-400 hover:text-slate-200 cursor-pointer bg-transparent border-0">
                <span class="material-symbols-rounded">close</span>
              </button>
            </div>
            
            <p class="text-sm text-muted leading-relaxed">
              Review or customize the survey questions below before activating. Students will submit responses matching these descriptions.
            </p>

            <form id="form-exit-init" onsubmit="submitExitInit(event)" class="space-y-4">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q1. CO1 - Subject Knowledge</label>
                  <input type="text" id="ex-q1" value="How well did the course help you understand and remember the core academic principles, models, and structural fundamentals?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q2. CO1 - Outcome Mapping</label>
                  <input type="text" id="ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with the class presentations?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q3. CO2 - Analytical Ability</label>
                  <input type="text" id="ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q4. CO2 - Design & Analysis</label>
                  <input type="text" id="ex-q4" value="To what extent can you design models, troubleshoot bugs, or draft structural layouts based on class lessons?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q5. CO3 - Practical Skills</label>
                  <input type="text" id="ex-q5" value="How confident are you in operating laboratory kits, executing computer programs, or handling workshop machines?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q6. CO3 - Industry Standards</label>
                  <input type="text" id="ex-q6" value="How clearly do you understand safety regulations, instrumentation limits, and standard protocols?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q7. CO4 - Evaluation Standards</label>
                  <input type="text" id="ex-q7" value="To what extent did assignments, written internal exams, and presentations evaluate your skills thoroughly?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q8. CO4 - Professional Ethics</label>
                  <input type="text" id="ex-q8" value="How effectively did the course emphasize engineering ethics, environmental issues, and professional conduct?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q9. CO4 - Lifelong Learning</label>
                  <input type="text" id="ex-q9" value="How strongly has this course inspired you to self-learn, explore external publications, or research modern field advancements?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q10. Overall Course Rating</label>
                  <input type="text" id="ex-q10" value="Rate your overall satisfaction with the course syllabus delivery, faculty guidance, and academic outcomes." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-blue-500 outline-none font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-exit-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-xs transition-all cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-bold text-xs transition-all cursor-pointer">Activate & Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        <div id="tab-materials" class="tab-panel {{ $activeTab === 'materials' ? '' : 'hidden' }} space-y-4">
          @include('partials.virtual_learning_hub_tab', ['roomType' => 'Theory'])
        </div>

        <!-- TAB: REPORTS & CONSOLIDATED MARKS REGISTER -->
        <div id="tab-reports" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-5 {{ $activeTab === 'reports' ? '' : 'hidden' }}">
          <!-- Top Header -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/40 pb-3.5 gap-3">
            <div>
              <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20">
                  <span class="material-symbols-rounded text-lg block">description</span>
                </span>
                <h3 class="text-sm font-bold text-title">Academic Reports & Consolidated Marks Register</h3>
              </div>
              <p class="text-xs text-muted mt-1">Official printable reports, Continuous Assessment (40M), End Semester Examination (60M), and Revision 2026 course documents.</p>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
              <span class="px-2.5 py-1 rounded bg-slate-900 border border-slate-800 text-[11px] font-mono text-slate-300">
                <strong class="text-sky-400">{{ count($students) }}</strong> Enrolled Students
              </span>
            </div>
          </div>

          <!-- PRIMARY REPORT CARDS GRID (Top 4 Cards) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Card 1: CIA Reports (40M) -->
            <div class="bg-slate-950/70 border border-slate-800 hover:border-blue-500/50 rounded-xl p-3.5 flex flex-col justify-between space-y-3 transition group shadow-sm">
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="p-1.5 rounded-md bg-blue-500/15 text-blue-400 border border-blue-500/30">
                    <span class="material-symbols-rounded text-base block">fact_check</span>
                  </span>
                  <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">40 Marks CIA</span>
                </div>
                <h4 class="text-xs font-bold text-slate-200 group-hover:text-blue-300 transition">CIA Reports</h4>
                <p class="text-[11px] text-muted leading-relaxed">Continuous Internal Assessment documents & marks registers (Attendance 5M, SLA 15M, Series Tests 20M).</p>
              </div>
              <div class="pt-2 border-t border-slate-800/80 flex flex-col gap-1.5">
                <a href="/r26/classroom/{{ $batchSubject->id }}/internals/print-cie" target="_blank" class="w-full px-2 py-1.5 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/40 text-blue-300 hover:text-blue-200 rounded text-[10.5px] font-semibold transition flex items-center justify-between shadow-xs no-underline">
                  <span class="flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-xs">print</span>
                    <span>CIA Mark Sheet (40M)</span>
                  </span>
                  <span class="material-symbols-rounded text-xs opacity-70">open_in_new</span>
                </a>
                <div class="grid grid-cols-2 gap-1.5">
                  <a href="/classroom/{{ $batchSubject->id }}/class-roster/print" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="Attendance Report (Class Roster)">
                    <span class="material-symbols-rounded text-[11px] text-blue-400">co_present</span>
                    <span class="truncate">Attendance Report</span>
                  </a>
                  <a href="/classroom/{{ $batchSubject->id }}/class-log/print" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="Attendance & Subject Log (A4 Printable)">
                    <span class="material-symbols-rounded text-[11px] text-sky-400">event_note</span>
                    <span class="truncate">Subject Log</span>
                  </a>
                </div>
                <div class="grid grid-cols-2 gap-1.5">
                  <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="Print Self-Learning Activities (SLA) 15M Report">
                    <span class="material-symbols-rounded text-[11px] text-emerald-400">assignment</span>
                    <span class="truncate">SLA Report (15M)</span>
                  </a>
                  <a href="/classroom/{{ $batchSubject->id }}/assignment-report" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="Print Assignment Consolidated Marksheet">
                    <span class="material-symbols-rounded text-[11px] text-amber-400">task</span>
                    <span class="truncate">Assignment Sheet</span>
                  </a>
                </div>
                <a href="#assignment-reports-section" class="w-full px-2 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline">
                  <span class="material-symbols-rounded text-[11px] text-purple-400">rule</span>
                  <span>Assignment QPs & Schemes ▾</span>
                </a>
              </div>
            </div>

            <!-- Card 2: Series Examination Reports (20M) -->
            <div class="bg-slate-950/70 border border-slate-800 hover:border-purple-500/50 rounded-xl p-3.5 flex flex-col justify-between space-y-3 transition group shadow-sm">
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="p-1.5 rounded-md bg-purple-500/15 text-purple-400 border border-purple-500/30">
                    <span class="material-symbols-rounded text-base block">quiz</span>
                  </span>
                  <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20">20 Marks Scaled</span>
                </div>
                <h4 class="text-xs font-bold text-slate-200 group-hover:text-purple-300 transition">Series Examination Reports</h4>
                <p class="text-[11px] text-muted leading-relaxed">Continuous test cycles record, consolidated marksheet, question papers, and evaluation keys.</p>
              </div>
              <div class="pt-2 border-t border-slate-800/80 flex flex-col gap-1.5">
                <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="w-full px-2 py-1.5 bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/40 text-purple-300 hover:text-purple-200 rounded text-[10.5px] font-semibold transition flex items-center justify-between shadow-xs no-underline">
                  <span class="flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-xs">table_view</span>
                    <span>Series Exam Marksheet</span>
                  </span>
                  <span class="material-symbols-rounded text-xs opacity-70">open_in_new</span>
                </a>
                @forelse($seriesExams as $exam)
                  <div class="grid grid-cols-2 gap-1.5">
                    <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-qp" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="{{ $exam->exam_name }} Question Paper">
                      <span class="material-symbols-rounded text-[11px] text-purple-400">help</span>
                      <span class="truncate">{{ $exam->exam_name }} QP</span>
                    </a>
                    <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-scheme" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="{{ $exam->exam_name }} Scheme & Answer Key">
                      <span class="material-symbols-rounded text-[11px] text-amber-400">key</span>
                      <span class="truncate">{{ $exam->exam_name }} Key</span>
                    </a>
                  </div>
                @empty
                  <div class="text-[10px] text-slate-500 italic text-center py-1">No series exams configured yet</div>
                @endforelse
              </div>
            </div>

            <!-- Card 3: Final Results & Grade Sheets (100M) -->
            <div class="bg-slate-950/70 border border-slate-800 hover:border-emerald-500/50 rounded-xl p-3.5 flex flex-col justify-between space-y-3 transition group shadow-sm">
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="p-1.5 rounded-md bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                    <span class="material-symbols-rounded text-base block">school</span>
                  </span>
                  <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">100 Marks Total</span>
                </div>
                <h4 class="text-xs font-bold text-slate-200 group-hover:text-emerald-300 transition">Final Results & Grade Sheets</h4>
                <p class="text-[11px] text-muted leading-relaxed">Final grade sheets, SBTE transcripts, CIA 40M final, and ESE 60M marksheet.</p>
              </div>
              <div class="pt-2 border-t border-slate-800/80 flex flex-col gap-1.5">
                <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print?type=grade_sheet" target="_blank" class="w-full px-2 py-1.5 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 hover:text-emerald-200 rounded text-[10.5px] font-semibold transition flex items-center justify-between shadow-xs no-underline">
                  <span class="flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-xs">workspace_premium</span>
                    <span>Final Grade Sheet (100M)</span>
                  </span>
                  <span class="material-symbols-rounded text-xs opacity-70">open_in_new</span>
                </a>
                <div class="grid grid-cols-2 gap-1.5">
                  <a href="/r26/classroom/{{ $batchSubject->id }}/internals/print-cie" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="CIA Final Sheet (40M)">
                    <span class="material-symbols-rounded text-[11px] text-blue-400">fact_check</span>
                    <span class="truncate">CIA Final (40M)</span>
                  </a>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print?type=ese" target="_blank" class="px-1.5 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1 shadow-xs no-underline truncate" title="ESE Final Sheet (60M)">
                    <span class="material-symbols-rounded text-[11px] text-amber-400">school</span>
                    <span class="truncate">ESE Final (60M)</span>
                  </a>
                </div>
                <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print" target="_blank" class="w-full px-2 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1.5 shadow-xs no-underline" title="Consolidated Results Marksheet (100M)">
                  <span class="material-symbols-rounded text-[11px] text-emerald-400">format_list_numbered</span>
                  <span>Consolidated Sheet (100M)</span>
                </a>
              </div>
            </div>

            <!-- Card 4: Course file Documents R 2026 -->
            <div class="bg-slate-950/70 border border-slate-800 hover:border-amber-500/50 rounded-xl p-3.5 flex flex-col justify-between space-y-3 transition group shadow-sm">
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <span class="p-1.5 rounded-md bg-amber-500/15 text-amber-400 border border-amber-500/30">
                    <span class="material-symbols-rounded text-base block">folder_zip</span>
                  </span>
                  <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">Audit Ready</span>
                </div>
                <h4 class="text-xs font-bold text-slate-200 group-hover:text-amber-300 transition">Course File Documents R 2026</h4>
                <p class="text-[11px] text-muted leading-relaxed">Complete course portfolio, syllabus, CO-PO, lesson plan, assessments & NBA compliance records.</p>
              </div>
              <div class="pt-2 border-t border-slate-800/80 flex flex-col gap-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                  <a href="/r26/classroom/course-file/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 rounded text-[10px] font-semibold transition flex items-center justify-center gap-1 shadow-xs no-underline" title="Prepare & Edit Course File">
                    <span class="material-symbols-rounded text-xs">edit_note</span>
                    <span>Prepare</span>
                  </a>
                  <a href="/r26/classroom/course-file/{{ $batchSubject->id }}/print-pdf" target="_blank" class="px-2 py-1 bg-amber-600/20 hover:bg-amber-600/30 border border-amber-500/40 text-amber-300 rounded text-[10px] font-semibold transition flex items-center justify-center gap-1 shadow-xs no-underline" title="Print Complete Course File PDF">
                    <span class="material-symbols-rounded text-xs">print</span>
                    <span>Print PDF</span>
                  </a>
                </div>
                <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="w-full px-2 py-1 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded text-[10px] font-medium transition flex items-center justify-center gap-1.5 shadow-xs no-underline" title="Print Complete Lesson Plan (60 Contact Hours)">
                  <span class="material-symbols-rounded text-[11px] text-sky-400">calendar_month</span>
                  <span>Lesson Plan Document</span>
                </a>
              </div>
            </div>
          </div>

          <!-- SECONDARY REGISTERS QUICK TOOLBAR -->
          <div class="bg-slate-950/70 border border-slate-800 rounded-xl p-2.5 flex flex-wrap items-center justify-between gap-2 shadow-sm">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-slate-400 text-sm">print_connect</span>
              <span class="text-[11px] font-bold text-slate-300">Quick Report Shortcuts:</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
              <a href="/classroom/{{ $batchSubject->id }}/class-roster/print" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline" title="Print Attendance Roster">
                <span class="material-symbols-rounded text-xs text-blue-400">co_present</span>
                <span>Attendance Report</span>
              </a>
              <a href="/classroom/{{ $batchSubject->id }}/class-log/print" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline" title="Print Subject Log (A4)">
                <span class="material-symbols-rounded text-xs text-sky-400">event_note</span>
                <span>Subject Log (A4)</span>
              </a>
              <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode('/r26/classroom/theory/' . $batchSubject->id . '?tab=reports') }}" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline" title="Open Class Attendance & Subject Log Desk">
                <span class="material-symbols-rounded text-xs text-indigo-400">edit_calendar</span>
                <span>Attendance Log Desk</span>
              </a>
              <a href="/classroom/{{ $batchSubject->id }}/assignment-report" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline" title="Print Assignment Consolidated Marksheet">
                <span class="material-symbols-rounded text-xs text-amber-400">task</span>
                <span>Assignment Marks</span>
              </a>
              <a href="#assignment-reports-section" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline" title="Jump to Assignment QPs & Rubrics">
                <span class="material-symbols-rounded text-xs text-purple-400">rule</span>
                <span>Assignment QPs & Rubrics</span>
              </a>
              <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                <span class="material-symbols-rounded text-xs text-sky-400">calendar_month</span>
                <span>Lesson Plan</span>
              </a>
              <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                <span class="material-symbols-rounded text-xs text-emerald-400">assignment</span>
                <span>Self-Learning</span>
              </a>
              <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded text-[10.5px] font-medium transition flex items-center gap-1 shadow-xs no-underline">
                <span class="material-symbols-rounded text-xs text-blue-400">equalizer</span>
                <span>NBA Attainment</span>
              </a>
            </div>
          </div>

          <!-- ASSIGNMENT QUESTION PAPERS, RUBRICS & EVALUATION SCHEMES SECTION -->
          <div id="assignment-reports-section" class="bg-slate-950/70 border border-slate-800 rounded-xl p-3.5 space-y-3 shadow-sm scroll-mt-6">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-800/80 pb-2.5">
              <div class="flex items-center gap-2">
                <span class="p-1 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">
                  <span class="material-symbols-rounded text-sm block">assignment</span>
                </span>
                <div>
                  <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Assignment Question Papers, Rubrics & Evaluation Schemes</h4>
                  <p class="text-[11px] text-muted">Official NBA course file documents: Question Papers (with Blooms levels & KaTeX equations), Scoring Rubrics & Evaluation Schemes.</p>
                </div>
              </div>
              <div class="flex items-center gap-2 flex-wrap">
                <a href="/classroom/{{ $batchSubject->id }}/assignment-report" target="_blank" class="px-2.5 py-1 bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 border border-amber-500/40 rounded text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Consolidated Assignment Marks Report">
                  <span class="material-symbols-rounded text-xs">format_list_numbered</span>
                  <span>Assignment Consolidated Marksheet</span>
                </a>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
              @php
                $coListForPrints = ['CO1', 'CO2', 'CO3', 'CO4'];
                if (!empty($courseFile->parsed_cos)) {
                    $decodedCos = json_decode($courseFile->parsed_cos, true);
                    if (is_array($decodedCos)) {
                        $extracted = [];
                        foreach ($decodedCos as $coItem) {
                            if (!empty($coItem['id'])) $extracted[] = $coItem['id'];
                        }
                        if (!empty($extracted)) $coListForPrints = $extracted;
                    }
                }
              @endphp
              @foreach($coListForPrints as $coTag)
                <div class="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-lg p-2.5 space-y-2">
                  <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-200">{{ $coTag }} Assignment</span>
                    <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">Active SLA</span>
                  </div>
                  <div class="grid grid-cols-2 gap-1.5">
                    <a href="/r26/classroom/assignment/{{ $batchSubject->id }}/print-qp/{{ $coTag }}" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700 rounded text-[10px] font-semibold transition flex items-center justify-center gap-1 shadow-xs no-underline" title="Print Assignment Question Paper with Blooms levels">
                      <span class="material-symbols-rounded text-xs text-purple-400">help</span>
                      <span>QP (Print)</span>
                    </a>
                    <a href="/r26/classroom/assignment/{{ $batchSubject->id }}/print-scheme/{{ $coTag }}" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700 rounded text-[10px] font-semibold transition flex items-center justify-center gap-1 shadow-xs no-underline" title="Print Evaluation Scheme, Rubrics & Answer Hints">
                      <span class="material-symbols-rounded text-xs text-amber-400">rule</span>
                      <span>Scheme & Rubrics</span>
                    </a>
                  </div>
                  <a href="/classroom/{{ $batchSubject->id }}/assignment-print/{{ $coTag }}" target="_blank" class="w-full px-2 py-0.5 bg-slate-950/60 hover:bg-slate-800 text-slate-400 hover:text-slate-200 border border-slate-800 rounded text-[9.5px] font-medium transition flex items-center justify-center gap-1 no-underline" title="Print Complete Assignment Pack (QP + Rubrics + Evaluation Scheme)">
                    <span class="material-symbols-rounded text-[11px] text-blue-400">article</span>
                    <span>Complete QP + Rubrics Pack</span>
                  </a>
                </div>
              @endforeach
            </div>
          </div>

          <!-- CONSOLIDATED MARKS ENTRY & LEDGER SECTION -->
          <div class="bg-panel border border-slate-700/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-4">
            <!-- Subtab Navigation -->
            <div class="flex flex-wrap items-center justify-between border-b border-slate-800 pb-2 gap-3">
              <div class="flex items-center gap-2">
                <button onclick="switchReportsSubtab('cie_marks')" id="reportsubbtn-cie_marks" class="px-3 py-1.5 text-xs font-bold border-b-2 border-emerald-500 text-emerald-400 bg-emerald-500/10 rounded-t-md transition-all cursor-pointer flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sm">fact_check</span>
                  <span>1. CIA Marks Register (40M)</span>
                </button>
                <button onclick="switchReportsSubtab('ese_results')" id="reportsubbtn-ese_results" class="px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700 rounded-t-md transition-all cursor-pointer flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sm">school</span>
                  <span>2. ESE Marks Entry & Final Scores (100M)</span>
                </button>
              </div>
              <div class="text-[11px] text-muted">
                Continuous sync with student gradebooks
              </div>
            </div>

            <!-- SUBTAB 1: CIE MARKS REGISTER -->
            <div id="subtab-cie_marks" class="space-y-3">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                  <h4 class="font-bold text-title text-xs uppercase tracking-wider">Continuous Internal Assessment (CIA) Master Register</h4>
                  <p class="text-xs text-muted mt-0.5">Scale: Attendance (5M) + Self-Learning (15M) + Series Exams (20M) = 40 Marks Total</p>
                </div>
                <div class="flex items-center gap-1.5 flex-wrap">
                  <a href="/classroom/{{ $batchSubject->id }}/class-roster/print" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Attendance Roster">
                    <span class="material-symbols-rounded text-xs text-blue-400">co_present</span> Attendance
                  </a>
                  <a href="/classroom/{{ $batchSubject->id }}/class-log/print" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Subject Log (A4)">
                    <span class="material-symbols-rounded text-xs text-sky-400">event_note</span> Subject Log
                  </a>
                  <a href="/classroom/{{ $batchSubject->id }}/assignment-report" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Assignment Consolidated Marksheet">
                    <span class="material-symbols-rounded text-xs text-amber-400">task</span> Assignment
                  </a>
                  <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Self-Learning 15M Report">
                    <span class="material-symbols-rounded text-xs text-emerald-400">assignment</span> SLA (15M)
                  </a>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="px-2 py-1 bg-purple-600/20 hover:bg-purple-600/30 text-purple-300 border border-purple-500/40 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline">
                    <span class="material-symbols-rounded text-xs">quiz</span> Series (20M)
                  </a>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/internals/print-cie" target="_blank" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline">
                    <span class="material-symbols-rounded text-xs">print</span> Print CIA Marksheet (40M)
                  </a>
                </div>
              </div>

              <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                  <thead>
                    <tr class="bg-slate-900/30 text-[10px] font-semibold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-2.5 w-[6%] text-center">Roll No</th>
                      <th class="p-2.5 w-[15%]">Register No</th>
                      <th class="p-2.5">Student Name</th>
                      <th class="p-2.5 w-[12%] text-center">Attendance %</th>
                      <th class="p-2.5 w-[12%] text-center">Attendance (5M)</th>
                      <th class="p-2.5 w-[15%] text-center">Self Learning / SLA (15M)</th>
                      <th class="p-2.5 w-[15%] text-center">Series Exam (20M)</th>
                      <th class="p-2.5 w-[12%] text-center">Total CIA (40M)</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-card text-xs font-normal">
                    @forelse($studentCiaData as $sc)
                      <tr class="bg-card-hover transition-all font-normal">
                        <td class="p-2 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="p-2 font-mono text-title">{{ $sc['reg_no'] }}</td>
                        <td class="p-2 text-title font-medium">{{ $sc['name'] }}</td>
                        <td class="p-2 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                        <td class="p-2 text-center font-mono text-emerald-500 font-bold">{{ $sc['attendance_marks'] }}</td>
                        <td class="p-2 text-center font-mono text-title">{{ $sc['self_learning_marks'] }}</td>
                        <td class="p-2 text-center font-mono text-title">{{ $sc['series_exam_marks'] }}</td>
                        <td class="p-2 text-center font-mono text-blue-400 font-bold text-sm">{{ round($sc['total_cia']) }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- SUBTAB 2: ESE MARKS & FINAL RESULTS -->
            <div id="subtab-ese_results" class="space-y-3 hidden">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                  <h4 class="font-bold text-title text-xs uppercase tracking-wider">End Semester Exam (ESE) Marks Entry & Final Grades</h4>
                  <p class="text-xs text-muted mt-0.5">Official SBTE Results Norms: Enter SBTE Grade (S, A, B, C, D, E, F) or ESE Marks (out of 60) with automatic synchronization and real-time autosave.</p>
                </div>
                <div class="flex items-center gap-1.5 flex-wrap">
                  <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print?type=grade_sheet" target="_blank" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Final Grade Sheet & SBTE Transcript">
                    <span class="material-symbols-rounded text-xs">workspace_premium</span> Grade Sheet
                  </a>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print" target="_blank" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print Consolidated Results Sheet (100M)">
                    <span class="material-symbols-rounded text-xs">print</span> Consolidated (100M)
                  </a>
                  <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print?type=ese" target="_blank" class="px-2 py-1 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 rounded-md text-[10.5px] font-semibold transition flex items-center gap-1 shadow-xs no-underline" title="Print ESE Final Marksheet (60M)">
                    <span class="material-symbols-rounded text-xs text-amber-400">school</span> ESE (60M)
                  </a>
                  <span id="eseAutosaveBadge" class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved
                  </span>
                  <button onclick="saveEseMarks()" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-[10.5px] font-semibold transition cursor-pointer flex items-center gap-1 shadow-xs">
                    <span class="material-symbols-rounded text-xs font-bold">save</span> Save ESE Marks
                  </button>
                </div>
              </div>

              <!-- SBTE Grade Legend Banner -->
              <div class="bg-blue-950/20 border border-blue-500/30 rounded-lg p-2.5 flex flex-wrap items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-rounded text-blue-400 text-base">info</span>
                  <span class="text-slate-300 text-[11px]"><strong>SBTE Grading Norms:</strong> Select the Grade from the official SBTE Result sheet. The system computes scaled ESE marks (60M) and consolidated 100M final grade automatically. All entries autosave continuously.</span>
                </div>
                <div class="flex items-center gap-1 text-[10px] font-mono flex-wrap">
                  <span class="px-1.5 py-0.5 bg-emerald-500/20 text-emerald-300 rounded border border-emerald-500/30">S: ≥90% (57M)</span>
                  <span class="px-1.5 py-0.5 bg-blue-500/20 text-blue-300 rounded border border-blue-500/30">A: 80-89% (51M)</span>
                  <span class="px-1.5 py-0.5 bg-cyan-500/20 text-cyan-300 rounded border border-cyan-500/30">B: 70-79% (45M)</span>
                  <span class="px-1.5 py-0.5 bg-yellow-500/20 text-yellow-300 rounded border border-yellow-500/30">C: 60-69% (39M)</span>
                  <span class="px-1.5 py-0.5 bg-amber-500/20 text-amber-300 rounded border border-amber-500/30">D: 50-59% (33M)</span>
                  <span class="px-1.5 py-0.5 bg-orange-500/20 text-orange-300 rounded border border-orange-500/30">E: 40-49% (27M)</span>
                  <span class="px-1.5 py-0.5 bg-rose-500/20 text-rose-300 rounded border border-rose-500/30">F: &lt;40% (0M)</span>
                </div>
              </div>

              <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[960px]">
                  <thead>
                    <tr class="bg-slate-900/30 text-[10px] font-semibold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-2.5 w-[5%] text-center">Roll</th>
                      <th class="p-2.5 w-[14%]">Register No</th>
                      <th class="p-2.5">Student Name</th>
                      <th class="p-2.5 w-[10%] text-center">CIA (40M)</th>
                      <th class="p-2.5 w-[16%] text-center">SBTE Grade</th>
                      <th class="p-2.5 w-[11%] text-center">ESE Marks (60M)</th>
                      <th class="p-2.5 w-[10%] text-center">Total (100M)</th>
                      <th class="p-2.5 w-[9%] text-center">Final Grade</th>
                      <th class="p-2.5 w-[12%] text-center">Result Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-card text-xs font-normal">
                    @forelse($studentCiaData as $sc)
                      <tr class="bg-card-hover transition-all font-normal student-ese-row" data-reg-no="{{ $sc['reg_no'] }}">
                        <td class="p-2 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="p-2 font-mono text-title text-[11px]">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                        <td class="p-2 text-title font-medium">{{ $sc['name'] }}</td>
                        <td class="p-2 text-center font-mono text-emerald-500 font-bold" data-val-cie="{{ round($sc['total_cia']) }}">{{ round($sc['total_cia']) }}</td>
                        <td class="p-2 text-center">
                          <select class="w-36 bg-slate-950/70 border border-slate-700 hover:border-slate-500 rounded px-2 py-1 text-slate-100 text-xs font-semibold focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none ese-grade-select cursor-pointer transition-colors" onchange="calculateEseRowFromGrade(this); triggerEseAutosave();">
                            <option value="" class="bg-slate-900 text-slate-400">-- Select Grade --</option>
                            <option value="S" {{ ($sc['ese_grade'] ?? '') === 'S' ? 'selected' : '' }} class="bg-slate-900 text-emerald-400 font-bold">S (≥90% | Outstanding)</option>
                            <option value="A" {{ ($sc['ese_grade'] ?? '') === 'A' ? 'selected' : '' }} class="bg-slate-900 text-blue-400 font-bold">A (80-89% | Excellent)</option>
                            <option value="B" {{ ($sc['ese_grade'] ?? '') === 'B' ? 'selected' : '' }} class="bg-slate-900 text-cyan-400 font-bold">B (70-79% | Very Good)</option>
                            <option value="C" {{ ($sc['ese_grade'] ?? '') === 'C' ? 'selected' : '' }} class="bg-slate-900 text-yellow-400 font-bold">C (60-69% | Good)</option>
                            <option value="D" {{ ($sc['ese_grade'] ?? '') === 'D' ? 'selected' : '' }} class="bg-slate-900 text-amber-400 font-bold">D (50-59% | Average)</option>
                            <option value="E" {{ ($sc['ese_grade'] ?? '') === 'E' ? 'selected' : '' }} class="bg-slate-900 text-orange-400 font-bold">E (40-49% | Pass)</option>
                            <option value="F" {{ ($sc['ese_grade'] ?? '') === 'F' ? 'selected' : '' }} class="bg-slate-900 text-rose-400 font-bold">F (&lt;40% | Reappearance)</option>
                            <option value="FE" {{ ($sc['ese_grade'] ?? '') === 'FE' ? 'selected' : '' }} class="bg-slate-900 text-rose-500 font-bold">FE (Absent / Withheld)</option>
                          </select>
                        </td>
                        <td class="p-2 text-center">
                          <input type="number" step="0.5" min="0" max="60" value="{{ $sc['ese_marks'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-1.5 py-1 text-slate-200 text-center focus:border-blue-500 outline-none font-normal text-xs ese-mark-input" onfocus="this.select()" oninput="calculateEseRowFromMarks(this); triggerEseAutosave();" onkeydown="handleR26EseMarkKeyDown(event, this)">
                        </td>
                        <td class="p-2 text-center font-mono text-title font-bold" data-field="total_score">{{ round($sc['grand_total'] ?? 0) }}</td>
                        <td class="p-2 text-center font-bold" data-field="grade_display">-</td>
                        <td class="p-2 text-center font-bold" data-field="remark_display">-</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </main>

  <script>
    function scrollTabs(amount) {}
    function checkTabScrollOverflow() {}

    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      let tabFromUrl = urlParams.get('tab');
      if (tabFromUrl === 'internals') tabFromUrl = 'reports';
      const validTabs = ['outline', 'planner', 'cia', 'series', 'attainment', 'materials', 'reports'];
      
      let targetTab = null;
      if (tabFromUrl && validTabs.includes(tabFromUrl)) {
        targetTab = tabFromUrl;
      } else {
        const savedTab = localStorage.getItem('activeClassroomTab');
        if (savedTab === 'internals') savedTab = 'reports';
        if (savedTab && validTabs.includes(savedTab)) {
          targetTab = savedTab;
        }
      }

      if (targetTab) {
        switchTab(targetTab);
      } else {
        switchTab('outline');
      }

      document.querySelectorAll('.student-ese-row').forEach(row => updateEseRowTotals(row));
      recalculateAllCopoAverages();
    });

    /* =========================================================================
       CO-PO ARTICULATION MATRIX FUNCTIONS (EDITABLE + AUTOSAVE)
       ========================================================================= */
    let copoAutosaveTimer = null;

    function handleCopoInput(input) {
      // Allow only numbers 1, 2, 3 or empty
      let val = input.value.replace(/[^1-3]/g, '');
      input.value = val;

      updateCopoCellStyle(input);
      recalculateAllCopoAverages();
      triggerCopoAutosave();

      // Auto-advance to next cell if 1, 2, or 3 was entered
      if (val !== '') {
        const row = parseInt(input.getAttribute('data-row'), 10);
        const col = parseInt(input.getAttribute('data-col'), 10);
        let next = document.querySelector(`.copo-matrix-cell[data-row="${row}"][data-col="${col + 1}"]`);
        if (!next) {
          next = document.querySelector(`.copo-matrix-cell[data-row="${row + 1}"][data-col="1"]`);
        }
        if (next) {
          next.focus();
          next.select();
        }
      }
    }

    function handleCopoKeyNav(e, input) {
      const row = parseInt(input.getAttribute('data-row'), 10);
      const col = parseInt(input.getAttribute('data-col'), 10);
      let target = null;

      if (e.key === 'ArrowRight') {
        target = document.querySelector(`.copo-matrix-cell[data-row="${row}"][data-col="${col + 1}"]`);
      } else if (e.key === 'ArrowLeft') {
        target = document.querySelector(`.copo-matrix-cell[data-row="${row}"][data-col="${col - 1}"]`);
      } else if (e.key === 'ArrowDown' || e.key === 'Enter') {
        e.preventDefault();
        target = document.querySelector(`.copo-matrix-cell[data-row="${row + 1}"][data-col="${col}"]`);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        target = document.querySelector(`.copo-matrix-cell[data-row="${row - 1}"][data-col="${col}"]`);
      } else if (e.key === 'Backspace' && input.value === '') {
        target = document.querySelector(`.copo-matrix-cell[data-row="${row}"][data-col="${col - 1}"]`);
        if (!target && row > 0) {
          target = document.querySelector(`.copo-matrix-cell[data-row="${row - 1}"][data-col="11"]`);
        }
      }

      if (target) {
        target.focus();
        target.select();
      }
    }

    function updateCopoCellStyle(input) {
      const val = input.value.trim();
      input.classList.remove(
        'bg-emerald-500/20', 'text-emerald-400', 'border-emerald-500/40',
        'bg-sky-500/20', 'text-sky-400', 'border-sky-500/40',
        'bg-amber-500/20', 'text-amber-400', 'border-amber-500/40',
        'text-slate-500', 'border-slate-800', 'hover:border-slate-700'
      );

      if (val === '3') {
        input.classList.add('bg-emerald-500/20', 'text-emerald-400', 'border-emerald-500/40');
      } else if (val === '2') {
        input.classList.add('bg-sky-500/20', 'text-sky-400', 'border-sky-500/40');
      } else if (val === '1') {
        input.classList.add('bg-amber-500/20', 'text-amber-400', 'border-amber-500/40');
      } else {
        input.classList.add('text-slate-500', 'border-slate-800', 'hover:border-slate-700');
      }
    }

    function recalculateAllCopoAverages() {
      for (let p = 1; p <= 11; p++) {
        const cells = document.querySelectorAll(`.copo-matrix-cell[data-po="PO${p}"]`);
        let sum = 0;
        let count = 0;
        cells.forEach(c => {
          const v = parseInt(c.value, 10);
          if (v && v >= 1 && v <= 3) {
            sum += v;
            count++;
          }
        });

        const avgEl = document.getElementById(`avg-PO${p}`);
        if (avgEl) {
          if (count > 0) {
            const avg = (sum / count).toFixed(1);
            avgEl.innerText = avg;
            avgEl.className = "py-2 px-1 text-center font-mono text-xs font-bold text-emerald-400";
          } else {
            avgEl.innerText = '-';
            avgEl.className = "py-2 px-1 text-center font-mono text-xs font-bold text-slate-500";
          }
        }
      }
    }

    function triggerCopoAutosave() {
      const badge = document.getElementById('copoAutosaveBadge');
      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Unsaved Changes';
        badge.className = "text-[10px] font-mono text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
      }

      clearTimeout(copoAutosaveTimer);
      copoAutosaveTimer = setTimeout(() => {
        saveCopoMatrix(true);
      }, 800);
    }

    function saveCopoMatrix(isAutoSave = false) {
      const btn = document.getElementById('btnSaveCopo');
      const badge = document.getElementById('copoAutosaveBadge');
      const originalBtnText = btn ? btn.innerHTML : '';

      if (!isAutoSave && btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">sync</span><span>Saving...</span>';
      }

      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span> Saving...';
        badge.className = "text-[10px] font-mono text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
      }

      // Collect matrix data
      const mappings = {};
      document.querySelectorAll('.copo-matrix-cell').forEach(cell => {
        const co = cell.getAttribute('data-co');
        const po = cell.getAttribute('data-po');
        const val = cell.value.trim();

        if (!mappings[co]) {
          mappings[co] = {};
        }
        mappings[co][po] = (val !== '' && val !== '-') ? val : '-';
      });

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/copo-matrix/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mappings: mappings })
      })
      .then(res => res.json())
      .then(data => {
        if (!isAutoSave && btn) {
          btn.disabled = false;
          btn.innerHTML = originalBtnText;
        }

        if (data.status === 'SUCCESS' || data.success) {
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Saved';
            badge.className = "text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
          }
          if (!isAutoSave && typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              title: 'Saved!',
              text: 'CO-PO Articulation Matrix saved successfully.',
              timer: 1500,
              showConfirmButton: false
            });
          }
        } else {
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Save Failed';
            badge.className = "text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
          }
          if (!isAutoSave) {
            alert('Failed to save matrix: ' + (data.message || 'Unknown error'));
          }
        }
      })
      .catch(err => {
        if (!isAutoSave && btn) {
          btn.disabled = false;
          btn.innerHTML = originalBtnText;
        }
        if (badge) {
          badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Error';
          badge.className = "text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
        }
        if (!isAutoSave) {
          alert('Network error saving matrix: ' + err.message);
        }
      });
    }

    function switchTab(tabId) {
      if (tabId === 'roster') tabId = 'outline';
      if (tabId === 'internals') tabId = 'reports';
      localStorage.setItem('activeClassroomTab', tabId);

      // Keep browser address bar in sync with active tab without reloading
      if (window.history && window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.replaceState({}, '', url);
      }

      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      const targetPanel = document.getElementById('tab-' + tabId);
      if (targetPanel) {
        targetPanel.classList.remove('hidden');
      }

      const tabs = ['outline', 'planner', 'cia', 'series', 'selflearning', 'attainment', 'materials', 'reports'];
      tabs.forEach(id => {
        const btn = document.getElementById('btn-' + id);
        if (!btn) return;
        if (id === tabId) {
          btn.className = "tab-btn px-3.5 py-2 text-[11px] md:text-xs font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px shadow-[0_-4px_10px_rgba(59,130,246,0.2)]";
        } else {
          btn.className = "tab-btn px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px";
        }
      });

      if (tabId === 'reports') {
        document.querySelectorAll('.student-ese-row').forEach(row => updateEseRowTotals(row));
      }
      if (tabId === 'materials' && typeof loadSubjectMaterials === 'function') {
        loadSubjectMaterials();
      }
    }

    function openRosterModal() {
      const m = document.getElementById('modal-student-roster');
      if (m) m.classList.remove('hidden');
    }

    function closeRosterModal() {
      const m = document.getElementById('modal-student-roster');
      if (m) m.classList.add('hidden');
    }

    function toggleTheme() {
      const body = document.body;
      const themeIcon = document.getElementById('theme-icon');
      if (body.classList.contains('dark')) {
        body.classList.remove('dark');
        body.classList.add('light');
        themeIcon.innerText = 'dark_mode';
      } else {
        body.classList.remove('light');
        body.classList.add('dark');
        themeIcon.innerText = 'light_mode';
      }
    }

    function performSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      
      // CSRF token
      const token = "{{ csrf_token() }}";
      formData.append('_token', token);

      const btnText = document.querySelector('button[onclick*="syllabusFileInput"]');
      const originalText = btnText.innerHTML;
      btnText.disabled = true;
      btnText.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">sync</span> Uploading...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/syllabus', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btnText.disabled = false;
        btnText.innerHTML = originalText;
        if (data.status === 'SUCCESS') {
          alert('Syllabus uploaded and parsed successfully!');
          localStorage.setItem('activeClassroomTab', 'outline');
          window.location.href = window.location.pathname + '?tab=outline';
        } else {
          alert('Upload failed: ' + data.message);
        }
      })
      .catch(err => {
        btnText.disabled = false;
        btnText.innerHTML = originalText;
        alert('Upload Error: ' + err.message);
      });
    }

    let newRowCounter = 1;
    let deletedLessonPlanIds = [];
    let plannerAutosaveTimer = null;

    function triggerPlannerAutosave() {
      const badge = document.getElementById('plannerAutosaveBadge');
      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Unsaved Changes';
        badge.className = "text-[10px] font-mono text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
      }
      clearTimeout(plannerAutosaveTimer);
      plannerAutosaveTimer = setTimeout(() => {
        saveLessonPlanEdits(true);
      }, 1000);
    }

    function deleteLessonPlanRow(btn) {
      const tr = btn.closest('tr');
      if (!tr) return;
      const periodEl = tr.querySelector('.period-no-display');
      const periodInput = tr.querySelector('[data-field="day_no"]');
      const periodText = periodInput ? periodInput.value : (periodEl ? periodEl.innerText.trim() : '');

      if (!confirm(`Are you sure you want to delete period ${periodText || 'this row'}? This action cannot be undone.`)) {
        return;
      }

      const lpId = tr.getAttribute('data-lp-id');
      if (lpId && !lpId.startsWith('new_')) {
        deletedLessonPlanIds.push(parseInt(lpId, 10));
      }
      tr.remove();

      renumberLessonPlanPeriods();
      triggerPlannerAutosave();
    }

    function renumberLessonPlanPeriods() {
      const trs = document.querySelectorAll('#plannerTableBody tr[data-lp-id]');
      trs.forEach((tr, index) => {
        const periodDisplay = tr.querySelector('.period-no-display');
        const periodInput = tr.querySelector('[data-field="day_no"]');
        if (periodDisplay) periodDisplay.innerText = index + 1;
        if (periodInput) periodInput.value = index + 1;
      });
    }

    function initLessonPlanDatePickers(container) {
      const root = container || document;
      if (typeof flatpickr !== 'undefined') {
        root.querySelectorAll('.lp-date-picker').forEach(el => {
          if (el._flatpickr) el._flatpickr.destroy();
          flatpickr(el, {
            dateFormat: "d/m/Y",
            allowInput: true,
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
              el.dispatchEvent(new Event('change'));
            }
          });
        });
      }
    }

    function addLessonPlanRow() {
      const tbody = document.getElementById('plannerTableBody');
      const trs = tbody.querySelectorAll('tr[data-lp-id]');
      const nextPeriod = trs.length + 1;

      const newId = 'new_' + Date.now() + '_' + (newRowCounter++);
      const tr = document.createElement('tr');
      tr.setAttribute('data-lp-id', newId);
      tr.className = "bg-sky-500/5 hover:bg-sky-500/10 transition-all font-normal";
      tr.innerHTML = `
        <td class="p-1.5 font-mono text-center text-title period-no-display text-xs">${nextPeriod}</td>
        <td class="p-1.5 text-center">
          <select data-field="co_id" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sky-400 focus:border-sky-500 outline-none font-semibold text-xs text-center">
            <option value="CO1">CO1</option>
            <option value="CO2">CO2</option>
            <option value="CO3">CO3</option>
            <option value="CO4">CO4</option>
            <option value="CO5">CO5</option>
          </select>
        </td>
        <td class="p-1.5">
          <textarea data-field="topic_content" oninput="triggerPlannerAutosave()" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs resize-y" placeholder="Enter topic content..."></textarea>
        </td>
        <td class="p-1.5">
          <select data-field="pedagogy" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-xs">
            <option value="Lecture" selected>Lecture</option>
            <option value="Tutorial">Tutorial</option>
            <option value="Practical">Practical</option>
            <option value="Exam">Exam</option>
          </select>
        </td>
        <td class="p-1.5">
          <input type="text" data-field="taxonomy" oninput="triggerPlannerAutosave()" value="Understand" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1.5 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs" placeholder="Taxonomy Level...">
        </td>
        <td class="p-1.5">
          <input type="text" data-field="proposed_date" onchange="triggerPlannerAutosave()" value="" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs text-center">
        </td>
        <td class="p-1.5">
          <input type="text" data-field="actual_date" onchange="triggerPlannerAutosave()" value="" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs text-center">
        </td>
        <td class="p-1.5">
          <input type="number" data-field="allocated_hours" oninput="triggerPlannerAutosave()" value="1" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-center text-xs">
        </td>
        <td class="p-1.5">
          <select data-field="status" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-xs min-w-[75px]">
            <option value="Pending" selected>Pending</option>
            <option value="Completed">Completed</option>
          </select>
        </td>
        <td class="p-1 text-center">
          <div class="flex items-center justify-center gap-0.5">
            <button type="button" onclick="addLessonPlanRowBelow(this)" class="text-sky-400 hover:text-sky-300 hover:bg-sky-500/20 p-1 rounded transition cursor-pointer" title="Add Row Below">
              <span class="material-symbols-rounded text-xs block">add</span>
            </button>
            <button type="button" onclick="deleteLessonPlanRow(this)" class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 p-1 rounded transition cursor-pointer" title="Delete Row">
              <span class="material-symbols-rounded text-xs block">close</span>
            </button>
          </div>
        </td>
      `;

      const emptyTd = tbody.querySelector('td[colspan="9"], td[colspan="10"]');
      if (emptyTd) {
        emptyTd.closest('tr').remove();
      }

      tbody.appendChild(tr);
      renumberLessonPlanPeriods();
      initLessonPlanDatePickers(tr);
      tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      triggerPlannerAutosave();
    }

    function addLessonPlanRowBelow(btn) {
      const currentTr = btn.closest('tr');
      if (!currentTr) return;
      const newId = 'new_' + Date.now() + '_' + (newRowCounter++);
      const tr = document.createElement('tr');
      tr.setAttribute('data-lp-id', newId);
      tr.className = "bg-sky-500/5 hover:bg-sky-500/10 transition-all font-normal";
      const currentCo = currentTr.querySelector('[data-field="co_id"]')?.value || 'CO1';
      tr.innerHTML = `
        <td class="p-1 font-mono text-center text-title period-no-display text-xs"></td>
        <td class="p-1 text-center">
          <select data-field="co_id" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sky-400 focus:border-sky-500 outline-none font-semibold text-xs text-center">
            <option value="CO1" ${currentCo === 'CO1' ? 'selected' : ''}>CO1</option>
            <option value="CO2" ${currentCo === 'CO2' ? 'selected' : ''}>CO2</option>
            <option value="CO3" ${currentCo === 'CO3' ? 'selected' : ''}>CO3</option>
            <option value="CO4" ${currentCo === 'CO4' ? 'selected' : ''}>CO4</option>
            <option value="CO5" ${currentCo === 'CO5' ? 'selected' : ''}>CO5</option>
          </select>
        </td>
        <td class="p-1">
          <textarea data-field="topic_content" oninput="triggerPlannerAutosave()" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2.5 py-1.5 text-slate-200 focus:border-blue-500 outline-none font-normal text-xs resize-y leading-relaxed" placeholder="Enter topic content..."></textarea>
        </td>
        <td class="p-1">
          <select data-field="pedagogy" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-[11px]">
            <option value="Lecture" selected>Lecture</option>
            <option value="Tutorial">Tutorial</option>
            <option value="Practical">Practical</option>
            <option value="Exam">Exam</option>
          </select>
        </td>
        <td class="p-1">
          <input type="text" data-field="taxonomy" oninput="triggerPlannerAutosave()" value="Understand" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px]" placeholder="Taxonomy...">
        </td>
        <td class="p-1">
          <input type="text" data-field="proposed_date" onchange="triggerPlannerAutosave()" value="" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px] text-center">
        </td>
        <td class="p-1">
          <input type="text" data-field="actual_date" onchange="triggerPlannerAutosave()" value="" placeholder="dd/mm/yyyy" class="lp-date-picker w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-[11px] text-center">
        </td>
        <td class="p-1">
          <input type="number" data-field="allocated_hours" oninput="triggerPlannerAutosave()" value="1" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-0.5 py-1 text-slate-200 focus:border-blue-500 outline-none font-normal text-center text-[11px]">
        </td>
        <td class="p-1">
          <select data-field="status" onchange="triggerPlannerAutosave()" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-blue-500 outline-none font-normal text-[11px]">
            <option value="Pending" selected>Pending</option>
            <option value="Completed">Completed</option>
          </select>
        </td>
        <td class="p-1 text-center">
          <div class="flex items-center justify-center gap-0.5">
            <button type="button" onclick="addLessonPlanRowBelow(this)" class="text-sky-400 hover:text-sky-300 hover:bg-sky-500/20 p-1 rounded transition cursor-pointer" title="Add Row Below">
              <span class="material-symbols-rounded text-xs block">add</span>
            </button>
            <button type="button" onclick="deleteLessonPlanRow(this)" class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 p-1 rounded transition cursor-pointer" title="Delete Row">
              <span class="material-symbols-rounded text-xs block">close</span>
            </button>
          </div>
        </td>
      `;
      currentTr.after(tr);
      renumberLessonPlanPeriods();
      initLessonPlanDatePickers(tr);
      triggerPlannerAutosave();
    }

    function saveLessonPlanEdits(isAutoSave = false) {
      const rows = [];
      const trs = document.querySelectorAll('#plannerTableBody tr[data-lp-id]');
      trs.forEach(tr => {
        const id = tr.getAttribute('data-lp-id');
        if (!id) return;
        
        const periodEl = tr.querySelector('.period-no-display');
        const periodInput = tr.querySelector('[data-field="day_no"]');
        const dayNo = periodInput ? periodInput.value : (periodEl ? periodEl.innerText.trim() : 1);

        const coEl = tr.querySelector('.co-tag-display');
        const coInput = tr.querySelector('[data-field="co_id"]');
        const coId = coInput ? coInput.value : (coEl ? coEl.innerText.trim() : 'CO1');
        
        const topicEl = tr.querySelector('[data-field="topic_content"]');
        const topic = topicEl ? topicEl.value : '';
        const pedagogyEl = tr.querySelector('[data-field="pedagogy"]');
        const pedagogy = pedagogyEl ? pedagogyEl.value : 'Lecture';
        const taxonomyEl = tr.querySelector('[data-field="taxonomy"]');
        const taxonomy = taxonomyEl ? taxonomyEl.value : 'Understand';
        const proposedEl = tr.querySelector('[data-field="proposed_date"]');
        const proposed = proposedEl ? (proposedEl.value || null) : null;
        const actualEl = tr.querySelector('[data-field="actual_date"]');
        const actual = actualEl ? (actualEl.value || null) : null;
        const hoursEl = tr.querySelector('[data-field="allocated_hours"]');
        const hours = hoursEl ? (hoursEl.value || 1) : 1;
        const statusEl = tr.querySelector('[data-field="status"]');
        const status = statusEl ? statusEl.value : 'Pending';
        
        rows.push({
          id,
          day_no: dayNo,
          co_id: coId,
          topic_content: topic,
          pedagogy,
          taxonomy,
          proposed_date: proposed,
          actual_date: actual,
          allocated_hours: hours,
          status
        });
      });

      const btn = document.getElementById('btnSavePlanner');
      const badge = document.getElementById('plannerAutosaveBadge');
      const originalText = btn ? btn.innerText : 'Save Changes';
      if (!isAutoSave && btn) {
        btn.disabled = true;
        btn.innerText = 'Saving...';
      }
      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span> Saving...';
        badge.className = "text-[10px] font-mono text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
      }

      const payload = {
        rows: rows,
        deleted_ids: deletedLessonPlanIds
      };

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/lesson-plans/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (!isAutoSave && btn) {
          btn.disabled = false;
          btn.innerText = originalText;
        }
        if (data.status === 'SUCCESS') {
          deletedLessonPlanIds = [];
          if (data.created_mappings) {
            Object.keys(data.created_mappings).forEach(tempId => {
              const matchedTr = document.querySelector(`#plannerTableBody tr[data-lp-id="${tempId}"]`);
              if (matchedTr) {
                matchedTr.setAttribute('data-lp-id', data.created_mappings[tempId]);
              }
            });
          }
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Changes Saved';
            badge.className = "text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
          }
          if (!isAutoSave) {
            alert('Lesson planner updated successfully!');
          }
        } else {
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Save Failed';
            badge.className = "text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
          }
          if (!isAutoSave) {
            alert('Failed to save changes: ' + data.message);
          }
        }
      })
      .catch(err => {
        if (!isAutoSave && btn) {
          btn.disabled = false;
          btn.innerText = originalText;
        }
        if (badge) {
          badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Save Error';
          badge.className = "text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs";
        }
        if (!isAutoSave) {
          alert('Error saving planner: ' + err.message);
        }
      });
    }

    function saveAsTemplate() {
      const btn = document.getElementById('btnSaveTemplate');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving Template...';

      fetch('/api/classroom/{{ $batchSubject->id }}/lesson-plans/save-as-template', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Lesson plan saved as a cross-batch template successfully!');
        } else {
          alert('Failed to save template: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving template: ' + err.message);
      });
    }

    // Attendance Override Handler with Confirmation & Amber Styling
    async function handleAttendanceOverrideChange(input, regNo, studentName) {
      const valStr = input.value.trim();
      const prevVal = input.dataset.prevVal || '';
      const td = input.closest('td');

      if (valStr === prevVal) return;

      if (valStr !== '') {
        const num = parseFloat(valStr);
        if (isNaN(num) || num < 0 || num > 100) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Invalid Percentage',
              text: 'Attendance percentage must be between 0 and 100%.',
              background: '#0f172a',
              color: '#f8fafc'
            });
          } else {
            alert('Attendance percentage must be between 0 and 100%.');
          }
          input.value = prevVal;
          return;
        }

        let confirmed = false;
        if (typeof Swal !== 'undefined') {
          const result = await Swal.fire({
            title: 'Confirm Attendance Override?',
            html: `You are overriding the attendance for <strong>${studentName}</strong> to <strong class="text-amber-400">${num}%</strong>.<br><small class="text-slate-400">This modifies the 5M CIE attendance score with an amber status border.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Yes, Apply Override',
            cancelButtonText: 'Cancel',
            background: '#0f172a',
            color: '#f8fafc'
          });
          confirmed = result.isConfirmed;
        } else {
          confirmed = confirm(`Confirm Attendance Override for ${studentName} to ${num}%?`);
        }

        if (!confirmed) {
          input.value = prevVal;
          return;
        }

        input.dataset.prevVal = valStr;
        input.classList.add('has-override');
        input.title = `Active Override: ${num}%`;

        const actionBtn = td ? td.querySelector('.btn-clear-att-override, .btn-prompt-att-override') : null;
        if (actionBtn) {
          actionBtn.className = 'btn-clear-att-override p-1 rounded hover:bg-slate-800 text-amber-400 hover:text-rose-400 transition cursor-pointer';
          actionBtn.title = 'Revert to class log raw percentage';
          actionBtn.onclick = () => clearAttendanceOverride(regNo, studentName);
          actionBtn.innerHTML = '<span class="material-symbols-rounded text-xs">close</span>';
        }
      } else {
        // Clearing override
        let confirmed = false;
        if (typeof Swal !== 'undefined') {
          const result = await Swal.fire({
            title: 'Remove Attendance Override?',
            html: `Revert attendance for <strong>${studentName}</strong> to class logs raw percentage?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Yes, Revert',
            cancelButtonText: 'Cancel',
            background: '#0f172a',
            color: '#f8fafc'
          });
          confirmed = result.isConfirmed;
        } else {
          confirmed = confirm(`Revert attendance for ${studentName} to class log percentage?`);
        }

        if (!confirmed) {
          input.value = prevVal;
          return;
        }

        input.dataset.prevVal = '';
        input.classList.remove('has-override');
        input.title = 'Click to override attendance percentage';

        const actionBtn = td ? td.querySelector('.btn-clear-att-override, .btn-prompt-att-override') : null;
        if (actionBtn) {
          actionBtn.className = 'btn-prompt-att-override p-1 rounded hover:bg-slate-800 text-slate-500 hover:text-amber-400 transition cursor-pointer';
          actionBtn.title = 'Click to override attendance %';
          actionBtn.onclick = () => promptAttendanceOverride(regNo, studentName);
          actionBtn.innerHTML = '<span class="material-symbols-rounded text-xs">edit</span>';
        }
      }

      recalculateRow(regNo);
      queueAutoSave();
    }

    function clearAttendanceOverride(regNo, studentName) {
      const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!row) return;
      const input = row.querySelector('.cell-att-override');
      if (!input) return;
      input.value = '';
      handleAttendanceOverrideChange(input, regNo, studentName);
    }

    async function promptAttendanceOverride(regNo, studentName) {
      const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!row) return;
      const input = row.querySelector('.cell-att-override');
      if (!input) return;
      
      const currentVal = input.value.trim() || input.placeholder;
      if (typeof Swal !== 'undefined') {
        const { value: newPct } = await Swal.fire({
          title: 'Override Attendance %',
          html: `Enter manual attendance percentage for <strong>${studentName}</strong>:`,
          input: 'number',
          inputValue: currentVal !== '-' ? currentVal : '',
          inputAttributes: {
            min: '0',
            max: '100',
            step: '0.1'
          },
          showCancelButton: true,
          confirmButtonColor: '#f59e0b',
          confirmButtonText: 'Apply Override',
          cancelButtonColor: '#475569',
          background: '#0f172a',
          color: '#f8fafc',
          inputValidator: (val) => {
            if (!val || isNaN(val) || val < 0 || val > 100) {
              return 'Please enter a valid percentage between 0 and 100';
            }
          }
        });
        if (newPct !== undefined && newPct !== null && newPct !== '') {
          input.value = newPct;
          handleAttendanceOverrideChange(input, regNo, studentName);
        }
      } else {
        input.focus();
        input.select();
      }
    }

    function handleS1Change(regNo, val) {
      const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!row) return;
      const co1Inp = row.querySelector('.cell-s1-co1');
      const co2Inp = row.querySelector('.cell-s1-co2');
      if (val.trim() !== '') {
        const num = Math.min(50.0, Math.max(0.0, parseFloat(val) || 0));
        if (co1Inp) co1Inp.value = (num / 2).toFixed(1);
        if (co2Inp) co2Inp.value = (num / 2).toFixed(1);
      } else {
        if (co1Inp) co1Inp.value = '';
        if (co2Inp) co2Inp.value = '';
      }
      recalculateRow(regNo);
      queueAutoSave();
    }

    function handleS2Change(regNo, val) {
      const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!row) return;
      const co3Inp = row.querySelector('.cell-s2-co3');
      const co4Inp = row.querySelector('.cell-s2-co4');
      if (val.trim() !== '') {
        const num = Math.min(50.0, Math.max(0.0, parseFloat(val) || 0));
        if (co3Inp) co3Inp.value = (num / 2).toFixed(1);
        if (co4Inp) co4Inp.value = (num / 2).toFixed(1);
      } else {
        if (co3Inp) co3Inp.value = '';
        if (co4Inp) co4Inp.value = '';
      }
      recalculateRow(regNo);
      queueAutoSave();
    }

    // Consolidated Row Recalculation (Rev 2026 Theory Standards)
    function recalculateRow(regNo) {
      const row = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!row) return;

      // Attendance: Check override first, else raw %
      const overrideVal = row.querySelector('.cell-att-override').value.trim();
      const rawPctText = row.querySelector('.cell-att-override').placeholder;
      let effectivePct = null;

      if (overrideVal !== '') {
        effectivePct = parseFloat(overrideVal);
      } else if (rawPctText !== '' && rawPctText !== '-') {
        effectivePct = parseFloat(rawPctText);
      }

      let attMark = 0.0;
      if (effectivePct !== null && !isNaN(effectivePct)) {
        if (effectivePct >= 90.0) attMark = 5.0;
        else if (effectivePct >= 80.0) attMark = 4.0;
        else if (effectivePct >= 75.0) attMark = 3.0;
        else if (effectivePct >= 70.0) attMark = 2.0;
        else if (effectivePct >= 65.0) attMark = 1.0;
        else attMark = 0.0;
      }
      row.querySelector('.cell-att-mark').textContent = attMark.toFixed(1);

      // Self-Learning Modules 1-4 (Max 15M each -> Average scaled to 15M)
      const ca1 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca1').value) || 0));
      const ca2 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca2').value) || 0));
      const ca3 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca3').value) || 0));
      const ca4 = Math.min(15.0, Math.max(0.0, parseFloat(row.querySelector('.cell-ca4').value) || 0));

      const caInputs = [
        row.querySelector('.cell-ca1').value.trim(),
        row.querySelector('.cell-ca2').value.trim(),
        row.querySelector('.cell-ca3').value.trim(),
        row.querySelector('.cell-ca4').value.trim()
      ];
      const enteredCount = caInputs.filter(v => v !== '').length;

      let slTotal15 = 0.0;
      if (enteredCount > 0) {
        slTotal15 = Math.round(((ca1 + ca2 + ca3 + ca4) / 4.0) * 2) / 2;
      }
      if (slTotal15 > 15.0) slTotal15 = 15.0;
      row.querySelector('.cell-sl-total').textContent = slTotal15.toFixed(1);

      // Series 1: Check S1 input (50) or CO1 (25) & CO2 (25) -> Conv to 20
      const s1Inp = row.querySelector('.cell-s1-50');
      const co1Inp = row.querySelector('.cell-s1-co1');
      const co2Inp = row.querySelector('.cell-s1-co2');
      const co1Val = co1Inp ? co1Inp.value.trim() : '';
      const co2Val = co2Inp ? co2Inp.value.trim() : '';

      const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
      const s1Abs = seriesRow ? seriesRow.querySelector('.series-tab-s1-abs').checked : false;
      const s2Abs = seriesRow ? seriesRow.querySelector('.series-tab-s2-abs').checked : false;

      let s1Raw = null;
      let s1_20 = 0.0;
      if (co1Val !== '' || co2Val !== '') {
        const c1 = (co1Val !== '' && co1Val !== '.') ? Math.min(25.0, Math.max(0.0, parseFloat(co1Val) || 0)) : 0;
        const c2 = (co2Val !== '' && co2Val !== '.') ? Math.min(25.0, Math.max(0.0, parseFloat(co2Val) || 0)) : 0;
        s1Raw = Math.min(50.0, c1 + c2);
        s1_20 = !s1Abs ? Math.round(((s1Raw / 50.0) * 20.0) * 2) / 2 : 0.0;
      } else if (s1Inp && s1Inp.value.trim() !== '') {
        s1Raw = Math.min(50.0, Math.max(0.0, parseFloat(s1Inp.value) || 0));
        s1_20 = !s1Abs ? Math.round(((s1Raw / 50.0) * 20.0) * 2) / 2 : 0.0;
      }
      if (s1Inp) s1Inp.value = s1Raw !== null ? s1Raw.toFixed(1) : '';
      const s1Disp = row.querySelector('.cell-s1-50-display');
      if (s1Disp) s1Disp.textContent = s1Raw !== null ? s1Raw.toFixed(1) : '-';

      // Series 2: Check CO3 (25) & CO4 (25) from Series Tab -> Conv to 20
      const s2Inp = row.querySelector('.cell-s2-50');
      const co3Inp = row.querySelector('.cell-s2-co3');
      const co4Inp = row.querySelector('.cell-s2-co4');
      const co3Val = co3Inp ? co3Inp.value.trim() : '';
      const co4Val = co4Inp ? co4Inp.value.trim() : '';

      let s2Raw = null;
      let s2_20 = 0.0;
      if (co3Val !== '' || co4Val !== '') {
        const c3 = (co3Val !== '' && co3Val !== '.') ? Math.min(25.0, Math.max(0.0, parseFloat(co3Val) || 0)) : 0;
        const c4 = (co4Val !== '' && co4Val !== '.') ? Math.min(25.0, Math.max(0.0, parseFloat(co4Val) || 0)) : 0;
        s2Raw = Math.min(50.0, c3 + c4);
        s2_20 = !s2Abs ? Math.round(((s2Raw / 50.0) * 20.0) * 2) / 2 : 0.0;
      } else if (s2Inp && s2Inp.value.trim() !== '') {
        s2Raw = Math.min(50.0, Math.max(0.0, parseFloat(s2Inp.value) || 0));
        s2_20 = !s2Abs ? Math.round(((s2Raw / 50.0) * 20.0) * 2) / 2 : 0.0;
      }
      if (s2Inp) s2Inp.value = s2Raw !== null ? s2Raw.toFixed(1) : '';
      const s2Disp = row.querySelector('.cell-s2-50-display');
      if (s2Disp) s2Disp.textContent = s2Raw !== null ? s2Raw.toFixed(1) : '-';

      let seriesComb20 = 0.0;
      if (s1Raw !== null || s2Raw !== null) {
        seriesComb20 = Math.round(((s1_20 + s2_20) / 2.0) * 2) / 2;
      }
      if (seriesComb20 > 20.0) seriesComb20 = 20.0;
      row.querySelector('.cell-series-total').textContent = seriesComb20.toFixed(1);

      // Total CIE (Max 40M) = Attendance (5) + Self-Learning (15) + Series (20)
      let totalCie40 = Math.round(attMark + slTotal15 + seriesComb20);
      if (totalCie40 > 40) totalCie40 = 40;
      row.querySelector('.cell-cie-total').textContent = totalCie40;

      // ESE (60M)
      const eseAbsent = row.querySelector('.cell-ese-absent').checked;
      const eseMarksInput = row.querySelector('.cell-ese-marks').value.trim();
      const eseMarks = (!eseAbsent && eseMarksInput !== '') ? Math.min(60.0, Math.max(0.0, parseFloat(eseMarksInput))) : 0.0;

      // Grand Total (100) - Whole number (no .0)
      const grandTotal = Math.round(totalCie40 + eseMarks);
      row.querySelector('.cell-grand-total').textContent = grandTotal;

      // Synchronize dedicated Series tab row
      syncMainToSeries(regNo, co1Val, co2Val, s1Raw, s1Abs, s1_20, co3Val, co4Val, s2Raw, s2Abs, s2_20, seriesComb20);
    }

    // Dedicated Input Handlers for Series Exam CO Marks (Supports 1 decimal point e.g. 11.5)
    function handleSeriesCoInput(input, regNo, coType) {
      let val = input.value;
      // Convert comma to dot
      val = val.replace(',', '.');
      // Strip any characters other than digits and decimal point
      val = val.replace(/[^0-9.]/g, '');
      // Allow only one decimal point
      const parts = val.split('.');
      if (parts.length > 2) {
        val = parts[0] + '.' + parts.slice(1).join('');
      }
      // Allow maximum 1 decimal place (e.g. 11.5)
      if (parts.length === 2 && parts[1].length > 1) {
        val = parts[0] + '.' + parts[1].substring(0, 1);
      }
      // Check maximum limit of 25M
      if (val !== '' && val !== '.') {
        const num = parseFloat(val);
        if (!isNaN(num) && num > 25.0) {
          val = '25';
        }
      }
      if (input.value !== val) {
        input.value = val;
      }

      syncSeriesToMain(regNo, coType, val);
      queueAutoSave();
    }

    function handleSeriesCoBlur(input, regNo, coType) {
      let val = input.value.trim();
      if (val === '' || val === '.') {
        input.value = '';
        syncSeriesToMain(regNo, coType, '');
        queueAutoSave();
        return;
      }
      let num = parseFloat(val);
      if (isNaN(num)) {
        input.value = '';
        syncSeriesToMain(regNo, coType, '');
      } else {
        num = Math.min(25.0, Math.max(0.0, num));
        // Keep 1 decimal if not whole integer (e.g. 11.5), otherwise clean integer (e.g. 11)
        const cleanVal = (Math.round(num * 10) / 10).toString();
        input.value = cleanVal;
        syncSeriesToMain(regNo, coType, cleanVal);
      }
      queueAutoSave();
    }

    // Bidirectional sync between CIE Register and Series Tab
    function syncSeriesToMain(regNo, coType, val) {
      const mainRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (!mainRow) return;

      if (coType === 'co1') {
        const inp = mainRow.querySelector('.cell-s1-co1');
        if (inp) inp.value = val;
      } else if (coType === 'co2') {
        const inp = mainRow.querySelector('.cell-s1-co2');
        if (inp) inp.value = val;
      } else if (coType === 'co3') {
        const inp = mainRow.querySelector('.cell-s2-co3');
        if (inp) inp.value = val;
      } else if (coType === 'co4') {
        const inp = mainRow.querySelector('.cell-s2-co4');
        if (inp) inp.value = val;
      }
      recalculateRow(regNo);
    }

    function syncSeriesAbsToMain(regNo, seriesType, isChecked) {
      recalculateRow(regNo);
    }

    function syncMainToSeries(regNo, co1Val, co2Val, s1Raw, s1Abs, s1_20, co3Val, co4Val, s2Raw, s2Abs, s2_20, comb20) {
      const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
      if (!seriesRow) return;

      const activeEl = document.activeElement;

      const inpCo1 = seriesRow.querySelector('.series-tab-s1-co1');
      if (inpCo1 && inpCo1 !== activeEl) inpCo1.value = co1Val;

      const inpCo2 = seriesRow.querySelector('.series-tab-s1-co2');
      if (inpCo2 && inpCo2 !== activeEl) inpCo2.value = co2Val;

      seriesRow.querySelector('.series-tab-s1-total').textContent = s1Raw !== null ? s1Raw.toFixed(1) : '-';
      seriesRow.querySelector('.series-tab-s1-20').textContent = s1Abs ? 'AB' : (s1Raw !== null ? s1_20.toFixed(1) : '-');

      const inpCo3 = seriesRow.querySelector('.series-tab-s2-co3');
      if (inpCo3 && inpCo3 !== activeEl) inpCo3.value = co3Val;

      const inpCo4 = seriesRow.querySelector('.series-tab-s2-co4');
      if (inpCo4 && inpCo4 !== activeEl) inpCo4.value = co4Val;

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
        
        const slaRow = document.querySelector(`.sla-student-row[data-reg-no="${regNo}"]`);
        const ca1 = (slaRow && slaRow.querySelector('.sla-m1')) ? slaRow.querySelector('.sla-m1').value.trim() : (row.querySelector('.cell-ca1') ? row.querySelector('.cell-ca1').value.trim() : '');
        const ca2 = (slaRow && slaRow.querySelector('.sla-m2')) ? slaRow.querySelector('.sla-m2').value.trim() : (row.querySelector('.cell-ca2') ? row.querySelector('.cell-ca2').value.trim() : '');
        const ca3 = (slaRow && slaRow.querySelector('.sla-m3')) ? slaRow.querySelector('.sla-m3').value.trim() : (row.querySelector('.cell-ca3') ? row.querySelector('.cell-ca3').value.trim() : '');
        const ca4 = (slaRow && slaRow.querySelector('.sla-m4')) ? slaRow.querySelector('.sla-m4').value.trim() : (row.querySelector('.cell-ca4') ? row.querySelector('.cell-ca4').value.trim() : '');

        const seriesRow = document.querySelector(`.series-eval-row[data-reg-no="${regNo}"]`);
        let co1 = (seriesRow && seriesRow.querySelector('.series-tab-s1-co1')) ? seriesRow.querySelector('.series-tab-s1-co1').value.trim() : (row.querySelector('.cell-s1-co1') ? row.querySelector('.cell-s1-co1').value.trim() : '');
        let co2 = (seriesRow && seriesRow.querySelector('.series-tab-s1-co2')) ? seriesRow.querySelector('.series-tab-s1-co2').value.trim() : (row.querySelector('.cell-s1-co2') ? row.querySelector('.cell-s1-co2').value.trim() : '');
        const s1Inp = row.querySelector('.cell-s1-50');
        if (s1Inp && s1Inp.value.trim() !== '' && co1 === '' && co2 === '') {
          const half = (parseFloat(s1Inp.value) / 2).toFixed(1);
          co1 = half;
          co2 = half;
        }

        let co3 = (seriesRow && seriesRow.querySelector('.series-tab-s2-co3')) ? seriesRow.querySelector('.series-tab-s2-co3').value.trim() : (row.querySelector('.cell-s2-co3') ? row.querySelector('.cell-s2-co3').value.trim() : '');
        let co4 = (seriesRow && seriesRow.querySelector('.series-tab-s2-co4')) ? seriesRow.querySelector('.series-tab-s2-co4').value.trim() : (row.querySelector('.cell-s2-co4') ? row.querySelector('.cell-s2-co4').value.trim() : '');
        const s2Inp = row.querySelector('.cell-s2-50');
        if (s2Inp && s2Inp.value.trim() !== '' && co3 === '' && co4 === '') {
          const half = (parseFloat(s2Inp.value) / 2).toFixed(1);
          co3 = half;
          co4 = half;
        }

        const eseMarks = row.querySelector('.cell-ese-marks').value.trim();
        const eseGrade = row.querySelector('.cell-ese-grade').value.trim();
        const eseAbsent = row.querySelector('.cell-ese-absent').checked;

        // Series absent flags from series tab
        const s1Abs = seriesRow ? seriesRow.querySelector('.series-tab-s1-abs').checked : false;
        const s2Abs = seriesRow ? seriesRow.querySelector('.series-tab-s2-abs').checked : false;

        marksData.push({
          reg_no: regNo,
          attendance_override_pct: attOverride !== '' ? Math.min(100.0, Math.max(0.0, parseFloat(attOverride))) : null,
          ca1_15: ca1 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca1))) : null,
          ca2_15: ca2 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca2))) : null,
          ca3_15: ca3 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca3))) : null,
          ca4_15: ca4 !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(ca4))) : null,
          series_1_co1_25: co1 !== '' ? Math.min(25.0, Math.max(0.0, parseFloat(co1))) : null,
          series_1_co2_25: co2 !== '' ? Math.min(25.0, Math.max(0.0, parseFloat(co2))) : null,
          series_1_absent: s1Abs ? 1 : 0,
          series_2_co3_25: co3 !== '' ? Math.min(25.0, Math.max(0.0, parseFloat(co3))) : null,
          series_2_co4_25: co4 !== '' ? Math.min(25.0, Math.max(0.0, parseFloat(co4))) : null,
          series_2_absent: s2Abs ? 1 : 0,
          ese_mark_60: eseMarks !== '' ? Math.min(60.0, Math.max(0.0, parseFloat(eseMarks))) : null,
          ese_grade: eseGrade !== '' ? eseGrade : null,
          ese_absent: eseAbsent ? 1 : 0
        });
      });

      try {
        const res = await fetch(`/api/r26/classroom/theory/{{ $batchSubject->id }}/evaluate/consolidated-save`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ marks_data: marksData })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          showSaveStatus('All Scores Saved', false);
          const badge = document.getElementById('seriesMarksAutosaveBadge');
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved';
            badge.className = 'text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
          }
          const slBadge = document.getElementById('slAutosaveBadge');
          if (slBadge) {
            slBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved';
            slBadge.className = 'text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
          }
          if (!isAuto) {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'success',
                title: 'Saved Successfully',
                text: 'All CIE, Self-Learning, and Series evaluation marks saved.',
                timer: 1500,
                showConfirmButton: false,
                background: '#0f172a',
                color: '#f8fafc'
              });
            } else {
              alert('All evaluation records saved successfully!');
            }
          }
        } else {
          showSaveStatus('Save Failed', true);
          if (!isAuto) alert('Failed to save marks: ' + (data.message || 'Server error'));
        }
      } catch (err) {
        showSaveStatus('Error saving', true);
        if (!isAuto) alert('Network error while saving: ' + err.message);
      }
    }

    function triggerConsolidatedManualSave() {
      executeConsolidatedSave(false);
    }

    // Quick Setup 2 Combined-CO Series Exams (50M each)
    function quickSetupCombinedSeriesPattern() {
      if (!confirm("Initialize 2 Combined-CO Series Examinations (50 Marks each: Series 1 = CO1+CO2, Series 2 = CO3+CO4)?")) {
        return;
      }

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/configure`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mode: 'combined_co' })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          localStorage.setItem('activeClassroomTab', 'series');
          window.location.href = window.location.pathname + '?tab=series';
        } else {
          alert('Failed to configure series pattern: ' + data.message);
        }
      })
      .catch(err => {
        alert('Network error configuring series pattern: ' + err.message);
      });
    }

    function toggleCiaView(view) {
      if (view === 'self-learning') {
        switchTab('selflearning');
      } else {
        switchTab('cia');
      }
    }

    // Realtime Self-Learning Module input handler (4 marks: M1..M4 all in 15M)
    function handleSlaInputChange(regNo, moduleNum, val) {
      const slaRow = document.querySelector(`.sla-student-row[data-reg-no="${regNo}"]`);
      if (!slaRow) return;

      const m1Inp = slaRow.querySelector('.sla-m1');
      const m2Inp = slaRow.querySelector('.sla-m2');
      const m3Inp = slaRow.querySelector('.sla-m3');
      const m4Inp = slaRow.querySelector('.sla-m4');

      const m1Val = m1Inp ? m1Inp.value.trim() : '';
      const m2Val = m2Inp ? m2Inp.value.trim() : '';
      const m3Val = m3Inp ? m3Inp.value.trim() : '';
      const m4Val = m4Inp ? m4Inp.value.trim() : '';

      const m1 = m1Val !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(m1Val) || 0)) : 0;
      const m2 = m2Val !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(m2Val) || 0)) : 0;
      const m3 = m3Val !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(m3Val) || 0)) : 0;
      const m4 = m4Val !== '' ? Math.min(15.0, Math.max(0.0, parseFloat(m4Val) || 0)) : 0;

      const entered = [m1Val, m2Val, m3Val, m4Val].filter(v => v !== '');
      const avg = entered.length > 0 ? Math.min(15.0, Math.round(((m1 + m2 + m3 + m4) / 4.0) * 2) / 2) : 0.0;

      const avgCell = slaRow.querySelector('.sla-row-avg');
      if (avgCell) {
        avgCell.textContent = avg.toFixed(1);
      }

      // Live synchronization to CIE Register row
      const mainRow = document.querySelector(`.student-eval-row[data-reg-no="${regNo}"]`);
      if (mainRow) {
        const caInp = mainRow.querySelector(`.cell-ca${moduleNum}`);
        if (caInp) caInp.value = val.trim();
        const slCell = mainRow.querySelector('.cell-sl-total');
        if (slCell) slCell.textContent = avg.toFixed(1);
        recalculateRow(regNo);
      }

      triggerSelfLearningAutosave();
    }

    let slAutosaveTimer = null;
    function triggerSelfLearningAutosave() {
      const badge = document.getElementById('slAutosaveBadge');
      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Unsaved scores...';
        badge.className = 'text-[10px] font-mono text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
      }
      clearTimeout(slAutosaveTimer);
      slAutosaveTimer = setTimeout(() => {
        saveSelfLearningMarks(true);
      }, 1200);
    }

    function saveSelfLearningMarks(isAutoSave = false) {
      const badge = document.getElementById('slAutosaveBadge');
      const btn = document.getElementById('btnSaveSelfLearning');
      const originalText = btn ? btn.innerHTML : 'Save Self-Learning';

      if (isAutoSave) {
        if (badge) {
          badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-spin"></span> Saving...';
          badge.className = 'text-[10px] font-mono text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
        }
      } else {
        if (btn) {
          btn.disabled = true;
          btn.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">sync</span> Saving...';
        }
      }

      executeConsolidatedSave(isAutoSave).then(() => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalText;
        }
      }).catch(() => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalText;
        }
      });
    }

    // Initialize default tabs & labels on page load
    document.addEventListener("DOMContentLoaded", function() {
      // Initialize Flatpickr for Lesson Plan dates
      initLessonPlanDatePickers();

      // Vertical Column-wise Tab, Enter & Arrow navigation for evaluation tables
      ['consolidatedCiaTable', 'seriesDedicatedTable', 'selfLearningCleanTable'].forEach(containerId => {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.addEventListener('keydown', function(e) {
          const isEnter = e.key === 'Enter';
          const isTab = e.key === 'Tab';
          const isArrowDown = e.key === 'ArrowDown';
          const isArrowUp = e.key === 'ArrowUp';

          if (!isEnter && !isTab && !isArrowDown && !isArrowUp) return;

          const input = e.target;
          if (!input || input.tagName !== 'INPUT' || input.type === 'checkbox') return;

          const row = input.closest('tr');
          if (!row) return;

          const tbody = row.closest('tbody');
          if (!tbody) return;

          // Identify target selector (e.g. .cell-s1-50, .cell-att-override, or .cell-sla-m1)
          let targetSelector = null;
          const targetClass = Array.from(input.classList).find(c => 
            c.startsWith('cell-') || c.startsWith('series-tab-') || c.startsWith('sla-')
          );
          if (targetClass) {
            targetSelector = '.' + targetClass;
          } else if (input.dataset.field) {
            targetSelector = `[data-field="${input.dataset.field}"]`;
          }
          if (!targetSelector) return;

          const isReverse = (isTab && e.shiftKey) || (isEnter && e.shiftKey) || isArrowUp;
          e.preventDefault();

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

          const rows = Array.from(tbody.querySelectorAll('tr.student-eval-row, tr.series-eval-row, tr.sla-student-row, tr[data-reg-no]'));
          const currentIndex = rows.indexOf(row);
          if (currentIndex === -1) return;

          const nextIndex = isReverse ? currentIndex - 1 : currentIndex + 1;
          if (nextIndex >= 0 && nextIndex < rows.length) {
            const targetRow = rows[nextIndex];
            const nextInput = targetRow.querySelector(targetSelector);
            if (nextInput) {
              nextInput.focus();
              if (typeof nextInput.select === 'function') {
                nextInput.select();
              }
            }
          }
        });
      });

      // Initialize Flatpickr for Assignment Due Date
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#modal-assignment-due-date", {
          dateFormat: "d/m/Y",
          allowInput: true,
          disableMobile: true
        });
      }

      // Restore Fullscreen State
      const isFullscreen = localStorage.getItem('classroomFullscreen') === 'true';
      if (isFullscreen) {
        const sidebar = document.getElementById('sidebar-panel-column');
        const details = document.getElementById('details-panel-column');
        const btn = document.getElementById('btn-fullscreen-toggle');
        
        sidebar.classList.add('hidden');
        details.className = "lg:col-span-4 transition-all duration-300";
        btn.innerHTML = `<span class="material-symbols-rounded text-[12px]">fullscreen_exit</span>`;
        btn.title = "Exit Fullscreen";
        btn.className = "p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-amber-400 transition-all border border-slate-700/60 cursor-pointer flex items-center justify-center";
      }

      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        validateConfigSum(co);
      });

      // Assignment modal image paste listener
      const qTa = document.getElementById('modal-q-text');
      if (qTa) {
        qTa.addEventListener('paste', function(e) {
          const items = (e.clipboardData || (e.originalEvent && e.originalEvent.clipboardData) || {}).items;
          if (items) {
            for (let i = 0; i < items.length; i++) {
              if (items[i].type && items[i].type.indexOf('image') !== -1) {
                e.preventDefault();
                const file = items[i].getAsFile();
                if (file) {
                  uploadR26QuestionImageBlob(file);
                }
                break;
              }
            }
          }
        });
      }
    });

    function toggleSidebarWideMode() {
      const btn = document.getElementById('btn-fullscreen-toggle');
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
          console.warn('Fullscreen request denied:', err);
        });
        btn.innerHTML = `<span class="material-symbols-rounded text-[12px]">fullscreen_exit</span>`;
        btn.title = "Exit Fullscreen";
        btn.className = "p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-amber-400 transition-all border border-slate-700/60 cursor-pointer flex items-center justify-center";
        localStorage.setItem('classroomFullscreen', 'true');
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        }
        btn.innerHTML = `<span class="material-symbols-rounded text-[12px]">fullscreen</span>`;
        btn.title = "Fullscreen";
        btn.className = "p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all border border-slate-700/60 cursor-pointer flex items-center justify-center";
        localStorage.setItem('classroomFullscreen', 'false');
      }
    }

    // Modal Control & Questions State
    let activeCoTag = 'CO1';
    let assignmentQuestions = @json($courseFile->assignment_questions ?? []);
    let assignmentDeadlines = @json($courseFile->assignment_deadlines ?? []);
    let modalQuestionsList = [];

    function openAssignmentModal(coTag) {
      activeCoTag = coTag;
      document.getElementById('assignment-modal-co-title').innerText = coTag;
      
      // Load existing questions
      modalQuestionsList = assignmentQuestions[coTag] || [];
      renderModalQuestionsList();

      // Load existing due date
      const deadline = (assignmentDeadlines[coTag] && assignmentDeadlines[coTag]['deadline']) ? assignmentDeadlines[coTag]['deadline'] : '';
      const dueDateInput = document.getElementById('modal-assignment-due-date');
      if (dueDateInput) {
        if (dueDateInput._flatpickr) {
          if (deadline) {
            let formatted = deadline;
            if (/^\d{4}-\d{2}-\d{2}$/.test(deadline)) {
              const parts = deadline.split('-');
              formatted = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else if (/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/.test(deadline)) {
              formatted = deadline.replace(/-/g, '/');
            }
            dueDateInput._flatpickr.setDate(formatted, false);
          } else {
            dueDateInput._flatpickr.clear();
          }
        } else {
          dueDateInput.value = deadline;
        }
      }
      
      // Apply Lock State
      const isLocked = !!(assignmentDeadlines[coTag] && assignmentDeadlines[coTag]['locked']);
      applyLockState(isLocked);

      // Update print link URLs
      document.getElementById('btn-print-qp').href = `/r26/classroom/assignment/{{ $batchSubject->id }}/print-qp/${coTag}`;
      document.getElementById('btn-print-scheme').href = `/r26/classroom/assignment/{{ $batchSubject->id }}/print-scheme/${coTag}`;
      
      document.getElementById('assignment-modal').classList.remove('hidden');
    }

    function closeAssignmentModal() {
      document.getElementById('assignment-modal').classList.add('hidden');
    }

    function applyLockState(isLocked) {
      const editor = document.querySelector('#assignment-modal .bg-slate-950\\/40');
      const btnLock = document.getElementById('btn-notify-assignment');
      const btnSave = document.querySelector('button[onclick="saveAssignmentQuestions()"]');
      const lockBadge = document.getElementById('modal-lock-badge');

      if (isLocked) {
        editor.classList.add('opacity-60', 'pointer-events-none');
        btnLock.disabled = true;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Locked`;
        btnLock.className = "px-3 py-1 bg-emerald-600/10 text-emerald-550 border border-emerald-500/20 rounded text-xs font-medium transition-all flex items-center gap-1 cursor-not-allowed border-0";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        editor.classList.remove('opacity-60', 'pointer-events-none');
        btnLock.disabled = false;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Lock & Notify`;
        btnLock.className = "px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm";
        if (btnSave) btnSave.classList.remove('hidden');
        if (lockBadge) {
          lockBadge.classList.add('hidden');
          lockBadge.style.display = 'none';
        }
      }
    }

    function renderModalQuestionsList() {
      const container = document.getElementById('modal-questions-table-body');
      container.innerHTML = '';
      
      if (modalQuestionsList.length === 0) {
        container.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500 italic font-normal">No questions added yet.</td></tr>';
        return;
      }

      const isLocked = !!(assignmentDeadlines[activeCoTag] && assignmentDeadlines[activeCoTag]['locked']);
      
      modalQuestionsList.forEach((q, idx) => {
        const tr = document.createElement('tr');
        tr.className = "bg-slate-900/10 hover:bg-slate-900/40 border-b border-slate-800 transition-all font-normal text-slate-200";
        let imgHtml = q.image_url ? `
          <div class="mt-2 mb-1">
            <a href="${q.image_url}" target="_blank" class="inline-block group/img" title="Click to view diagram">
              <img src="${q.image_url}" alt="Diagram" class="max-h-24 rounded border border-slate-700 bg-slate-950 p-1 object-contain shadow-sm hover:border-blue-500">
              <span class="text-[10px] text-blue-400 block mt-0.5">View Diagram</span>
            </a>
          </div>` : '';
        tr.innerHTML = `
          <td class="p-2.5 font-mono text-center text-slate-350">${idx + 1}</td>
          <td class="p-2.5 text-slate-100 font-medium leading-relaxed text-left text-sm">
            <div>${q.question}</div>
            ${imgHtml}
          </td>
          <td class="p-2.5 text-center text-slate-200 font-medium">${q.bt_level}</td>
          <td class="p-2.5 text-center font-mono text-emerald-450 font-bold">${q.marks}M</td>
          <td class="p-2.5 text-slate-350 font-normal leading-relaxed text-left">${q.scheme || '—'}</td>
          <td class="p-2.5 text-center">
            ${isLocked ? `<span class="text-slate-400 font-bold text-xs">Locked</span>` : `
            <button type="button" onclick="deleteModalQuestion(${idx})" class="text-rose-500 hover:text-rose-600 cursor-pointer border-0 bg-transparent">
              <span class="material-symbols-rounded text-sm">delete</span>
            </button>
            `}
          </td>
        `;
        container.appendChild(tr);
      });

      if (window.renderMathInElement) {
        renderMathInElement(container, {
          delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}],
          throwOnError: false
        });
      }
    }

    function addQuestionToModalList() {
      const text = document.getElementById('modal-q-text').value.trim();
      const marks = parseFloat(document.getElementById('modal-q-marks').value) || 5;
      const bt = document.getElementById('modal-q-bt').value;
      const scheme = document.getElementById('modal-q-scheme').value.trim();
      const imgInput = document.getElementById('modal-q-image-url');
      const imageUrl = imgInput ? imgInput.value.trim() : '';
      
      if (!text && !imageUrl) {
        alert("Please enter question text or attach a diagram.");
        return;
      }
      
      modalQuestionsList.push({
        question: text,
        marks: marks,
        bt_level: bt,
        scheme: scheme,
        image_url: imageUrl || null
      });
      
      renderModalQuestionsList();
      
      // Clear inputs
      document.getElementById('modal-q-text').value = '';
      document.getElementById('modal-q-scheme').value = '';
      if (imgInput) imgInput.value = '';
      const preview = document.getElementById('modal-q-image-preview');
      if (preview) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
      }
      const mathPrev = document.getElementById('modal-q-math-preview');
      if (mathPrev) mathPrev.classList.add('hidden');
    }

    function deleteModalQuestion(idx) {
      modalQuestionsList.splice(idx, 1);
      renderModalQuestionsList();
    }

    function handleR26QuestionImageUpload(fileInput) {
      if (fileInput.files && fileInput.files[0]) {
        uploadR26QuestionImageBlob(fileInput.files[0]);
        fileInput.value = '';
      }
    }

    function uploadR26QuestionImageBlob(file) {
      if (!file) return;
      const preview = document.getElementById('modal-q-image-preview');
      if (preview) {
        preview.classList.remove('hidden');
        preview.innerHTML = `<div class="text-xs text-blue-400 flex items-center gap-1.5 py-1 font-bold animate-pulse"><div class="w-3.5 h-3.5 border-2 border-blue-400 border-t-transparent rounded-full animate-spin"></div> Uploading diagram...</div>`;
      }

      const formData = new FormData();
      formData.append('image', file);

      fetch(`/api/classroom/{{ $batchSubject->id }}/upload-assignment-image`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.image_url) {
          document.getElementById('modal-q-image-url').value = data.image_url;
          renderR26ImagePreview(data.image_url);
        } else {
          alert(data.message || 'Image upload failed.');
          if (preview) preview.classList.add('hidden');
        }
      })
      .catch(err => {
        alert('Failed to upload image: ' + err.message);
        if (preview) preview.classList.add('hidden');
      });
    }

    function renderR26ImagePreview(imageUrl) {
      const preview = document.getElementById('modal-q-image-preview');
      if (!preview) return;
      if (!imageUrl) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
        return;
      }
      preview.classList.remove('hidden');
      preview.innerHTML = `
        <div class="relative inline-block mt-2">
          <img src="${imageUrl}" alt="Attached Diagram" class="max-h-28 rounded-lg border border-slate-700 bg-slate-900 object-contain p-1 shadow-md">
          <button type="button" onclick="removeR26Image()" class="absolute -top-2 -right-2 w-5 h-5 bg-rose-600 hover:bg-rose-500 text-white rounded-full flex items-center justify-center shadow transition-all cursor-pointer" title="Remove Diagram">
            <span class="material-symbols-rounded text-xs">close</span>
          </button>
        </div>
      `;
    }

    function removeR26Image() {
      document.getElementById('modal-q-image-url').value = '';
      const preview = document.getElementById('modal-q-image-preview');
      if (preview) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
      }
    }

    function updateR26MathPreview(textarea) {
      const preview = document.getElementById('modal-q-math-preview');
      const text = textarea.value || '';
      if (!preview) return;

      if (text.includes('$')) {
        preview.classList.remove('hidden');
        const renderEl = document.getElementById('modal-q-math-render');
        if (renderEl) {
          renderEl.textContent = text;
          if (window.renderMathInElement) {
            renderMathInElement(renderEl, {
              delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false}
              ],
              throwOnError: false
            });
          }
        }
      } else {
        preview.classList.add('hidden');
      }
    }

    function autoGenerateFromBank() {
      const mockQuestions = [
        { question: "Explain the fundamental principles and mapping of " + activeCoTag + " topics.", bt_level: "Understand", marks: 5, scheme: "Define core definitions (2M), explain with diagrams (3M)" },
        { question: "Solve the sample numeric evaluation problem relating to " + activeCoTag + " outline.", bt_level: "Apply", marks: 5, scheme: "Formula definition (1M), calculation steps (3M), final answer (1M)" },
        { question: "Compare and contrast the primary elements of " + activeCoTag + " syllabus.", bt_level: "Analyze", marks: 5, scheme: "List primary differences (3M), list similarities (2M)" }
      ];
      
      const randomQ = mockQuestions[Math.floor(Math.random() * mockQuestions.length)];
      document.getElementById('modal-q-text').value = randomQ.question;
      document.getElementById('modal-q-marks').value = randomQ.marks;
      document.getElementById('modal-q-bt').value = randomQ.bt_level;
      document.getElementById('modal-q-scheme').value = randomQ.scheme;
      
      alert("Suggested question populated from general question bank!");
    }

    function saveAssignmentQuestions() {
      const btn = document.querySelector('button[onclick="saveAssignmentQuestions()"]');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';
      
      const dueDate = document.getElementById('modal-assignment-due-date').value;
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/assignment/${activeCoTag}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions: modalQuestionsList, due_date: dueDate })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          assignmentQuestions[activeCoTag] = modalQuestionsList;
          assignmentDeadlines[activeCoTag] = { deadline: dueDate, locked: false };
          alert('Assignment details saved successfully!');
        } else {
          alert('Error saving details: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    function notifyStudentsAssignment() {
      if (!confirm("Are you sure you want to lock and publish this assignment to the student dashboards? Once locked, you cannot add, edit, or delete questions.")) {
        return;
      }
      const btn = document.getElementById('btn-notify-assignment');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Locking...';
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/assignment/${activeCoTag}/notify`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Assignment locked and notification successfully published to student dashboards!');
          localStorage.setItem('activeClassroomTab', 'cia');
          window.location.href = window.location.pathname + '?tab=cia';
        } else {
          alert('Failed to publish notifications: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }
  </script>

  <!-- ASSIGNMENT MODAL POPUP -->
  <div id="assignment-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 hidden">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-200" style="background-color: #0f172a !important;">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-sm font-bold text-title flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400">assignment</span>
            Manage Assignment - <span id="assignment-modal-co-title">CO1</span>
          </h3>
          <span id="modal-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-notify-assignment" onclick="notifyStudentsAssignment()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeAssignmentModal()" class="text-slate-400 hover:text-slate-200 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Stacked Editor Section (Full Width) -->
      <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-5 space-y-4">
        <h4 class="font-bold text-title text-xs uppercase tracking-wider">Add/Edit Question</h4>
        <div class="space-y-4">
          <div>
            <div class="flex justify-between items-center mb-1.5">
              <label class="block text-xs text-slate-300 font-bold">Question Description:</label>
              <span class="text-[10px] text-blue-400 font-mono bg-blue-500/10 px-2 py-0.5 rounded border border-blue-500/20">LaTeX Math ($...$) Enabled</span>
            </div>
            <textarea id="modal-q-text" rows="4" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2.5 text-slate-100 text-sm focus:border-blue-500 outline-none font-normal" placeholder="Type assignment question description (supports LaTeX formulas like $V=IR$ or $$\frac{a}{b}$$, Unicode symbols, and Ctrl+V image paste)..." oninput="updateR26MathPreview(this)"></textarea>
          </div>

          <!-- Live Math Expression Preview -->
          <div id="modal-q-math-preview" class="hidden p-2.5 bg-slate-900 border border-blue-500/30 rounded-lg text-slate-200 text-xs">
            <div class="text-[10px] font-bold uppercase text-blue-400 mb-1 flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">functions</span> Math Preview:
            </div>
            <div id="modal-q-math-render" class="leading-relaxed overflow-x-auto text-slate-100"></div>
          </div>

          <!-- Diagram Attachment Section -->
          <div class="bg-slate-900/50 border border-slate-800 rounded-lg p-2.5">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <input type="file" id="modal-q-image-file" accept="image/*" class="hidden" onchange="handleR26QuestionImageUpload(this)">
                <button type="button" onclick="document.getElementById('modal-q-image-file').click()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-blue-600 text-blue-300 hover:text-white text-xs font-bold border border-slate-700 transition-all flex items-center gap-1.5 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-sm">add_photo_alternate</span>
                  <span>Attach Diagram / Image</span>
                </button>
                <span class="text-[11px] text-slate-400">or press <strong>Ctrl+V</strong> in the box to paste screenshot</span>
              </div>
              <input type="hidden" id="modal-q-image-url" value="">
            </div>

            <!-- Preview box for attached diagram -->
            <div id="modal-q-image-preview" class="hidden mt-2"></div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Max Marks:</label>
              <input type="number" id="modal-q-marks" value="5" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm text-center focus:border-blue-500 outline-none font-normal">
            </div>
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Taxonomy Level:</label>
              <select id="modal-q-bt" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm focus:border-blue-500 outline-none font-normal">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
                <option value="Create">Create</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Due Date:</label>
              <input type="text" id="modal-assignment-due-date" placeholder="dd/mm/yyyy" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm focus:border-blue-500 outline-none font-normal">
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-bold">Scheme of Evaluation / Rubrics / Hints:</label>
            <textarea id="modal-q-scheme" rows="2" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2.5 text-slate-100 text-sm focus:border-blue-500 outline-none font-normal" placeholder="Specify evaluation guidelines here (e.g., Correct formula: 2 Marks, Steps and explanation: 3 Marks)"></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="autoGenerateFromBank()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold border border-slate-700 transition-all cursor-pointer flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">psychology</span> Suggest from Q-Bank
            </button>
            <button type="button" onclick="addQuestionToModalList()" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">add</span> Add to List
            </button>
          </div>
        </div>
      </div>

      <!-- Table Grid View for Questions (Full Width) -->
      <div class="space-y-3">
        <div class="flex justify-between items-center">
          <h4 class="font-bold text-title text-xs uppercase tracking-wider">Active Questions Table</h4>
          <!-- Print & Notify Action Panel -->
          <div class="flex gap-2">
            <a href="#" id="btn-print-qp" target="_blank" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold border border-slate-700 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">print</span> Print Assignment Questions
            </a>
            <a href="#" id="btn-print-scheme" target="_blank" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold border border-slate-700 transition-all flex items-center gap-1">
              <span class="material-symbols-rounded text-xs font-normal">description</span> Print Scheme
            </a>
          </div>
        </div>

        <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-950/20">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-900/40 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                <th class="p-3 w-[6%] text-center border-r border-slate-800">No.</th>
                <th class="p-3 border-r border-slate-800">Question Description</th>
                <th class="p-3 w-[15%] text-center border-r border-slate-800">Cognitive Level (BT)</th>
                <th class="p-3 w-[12%] text-center border-r border-slate-800">Marks</th>
                <th class="p-3 w-[25%] border-r border-slate-800">Evaluation Scheme</th>
                <th class="p-3 w-[8%] text-center">Action</th>
              </tr>
            </thead>
            <tbody id="modal-questions-table-body" class="divide-y divide-slate-850 text-sm font-normal text-slate-200">
              <!-- Rendered dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end gap-2 border-t border-slate-800 pt-3">
        <button type="button" onclick="closeAssignmentModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-350 rounded-lg text-xs font-bold transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" onclick="saveAssignmentQuestions()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
      </div>
    </div>
  </div>

  <!-- SERIES EXAMS BUILDER MODAL POPUP -->
  <div id="series-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 hidden">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-6xl p-6 shadow-2xl space-y-6 max-h-[95vh] overflow-y-auto custom-scrollbar text-slate-200" style="background-color: #0f172a !important;">
      
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2">
          <h3 class="text-sm font-bold text-title flex items-center gap-2">
            <span class="material-symbols-rounded text-sky-400">quiz</span>
            Build Series Exam - <span id="series-modal-title">Series Exam 1</span>
          </h3>
          <span id="series-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-lock-series" onclick="lockActiveSeries()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
            <span class="material-symbols-rounded text-xs">lock</span> Lock & Notify
          </button>
          <button type="button" onclick="closeSeriesModal()" class="text-slate-400 hover:text-slate-200 cursor-pointer border-0 bg-transparent flex items-center">
            <span class="material-symbols-rounded">close</span>
          </button>
        </div>
      </div>

      <!-- Unified Stacked Sections -->
      <div class="space-y-6">
        
        <!-- PART A SECTION -->
        <div class="border border-slate-850 rounded-xl p-4 bg-slate-950/40 space-y-3">
          <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-blue-400">filter_1</span> Part A (1 Mark Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-a-count-info">Questions required: 2 nos (2 Marks total) / 4 nos (Combined COs)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/20">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/40 text-xs font-bold text-slate-450 border-b border-slate-800">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartA" class="divide-y divide-slate-850 text-xs font-normal text-slate-200">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-800" id="editor-PartA">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartA" placeholder="Enter Part A Question (Ctrl+V to paste diagram)..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
              <select id="series-q-co-PartA" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartA" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <option value="Remember" selected>Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <input type="file" id="series-q-image-file-PartA" accept="image/*" class="hidden" onchange="handleSeriesQuestionImageUpload('Part A', this)">
                <input type="hidden" id="series-q-image-url-PartA" value="">
                <button type="button" onclick="document.getElementById('series-q-image-file-PartA').click()" title="Attach Diagram / Image" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">add_photo_alternate</span>
                </button>
                <button type="button" onclick="autoGenPartQuestion('Part A')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part A')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
            <div id="series-q-image-preview-PartA" class="hidden"></div>
          </div>
        </div>

        <!-- PART B SECTION -->
        <div class="border border-slate-850 rounded-xl p-4 bg-slate-950/40 space-y-3">
          <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-blue-400">filter_2</span> Part B (3 Marks Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-b-count-info">Questions required: 3 nos (9 Marks total) / 6 nos (18 Marks total)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/20">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/40 text-xs font-bold text-slate-455 border-b border-slate-800">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartB" class="divide-y divide-slate-850 text-xs font-normal text-slate-200">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-800" id="editor-PartB">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartB" placeholder="Enter Part B Question (Ctrl+V to paste diagram)..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
              <select id="series-q-co-PartB" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartB" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <option value="Remember">Remember</option>
                <option value="Understand" selected>Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <input type="file" id="series-q-image-file-PartB" accept="image/*" class="hidden" onchange="handleSeriesQuestionImageUpload('Part B', this)">
                <input type="hidden" id="series-q-image-url-PartB" value="">
                <button type="button" onclick="document.getElementById('series-q-image-file-PartB').click()" title="Attach Diagram / Image" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">add_photo_alternate</span>
                </button>
                <button type="button" onclick="autoGenPartQuestion('Part B')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part B')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
            <div id="series-q-image-preview-PartB" class="hidden"></div>
          </div>
        </div>

        <!-- PART C SECTION -->
        <div class="border border-slate-850 rounded-xl p-4 bg-slate-950/40 space-y-3">
          <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-blue-400">filter_3</span> Part C (7 Marks Each)
            </h4>
            <span class="text-xs font-medium text-slate-400" id="part-c-count-info">Questions required: 2 nos (14 Marks total) / 4 nos (28 Marks total)</span>
          </div>
          <!-- Table -->
          <div class="border border-slate-800 rounded-lg overflow-hidden bg-slate-950/20">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/40 text-xs font-bold text-slate-455 border-b border-slate-800">
                  <th class="p-2 w-[5%] text-center">No.</th>
                  <th class="p-2">Question Description</th>
                  <th class="p-2 w-[12%] text-center series-co-header">CO Tag</th>
                  <th class="p-2 w-[15%] text-center">BT Level</th>
                  <th class="p-2 w-[10%] text-center">Marks</th>
                  <th class="p-2 w-[8%] text-center">Action</th>
                </tr>
              </thead>
              <tbody id="series-questions-PartC" class="divide-y divide-slate-850 text-xs font-normal text-slate-200">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
          <!-- Inline Form -->
          <div class="grid grid-cols-1 gap-2 pt-2 border-t border-slate-800" id="editor-PartC">
            <div class="flex flex-col md:flex-row gap-2">
              <input type="text" id="series-q-text-PartC" placeholder="Enter Part C Question (Ctrl+V to paste diagram)..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-blue-500">
              <select id="series-q-co-PartC" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartC" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-blue-500">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply" selected>Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <input type="file" id="series-q-image-file-PartC" accept="image/*" class="hidden" onchange="handleSeriesQuestionImageUpload('Part C', this)">
                <input type="hidden" id="series-q-image-url-PartC" value="">
                <button type="button" onclick="document.getElementById('series-q-image-file-PartC').click()" title="Attach Diagram / Image" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">add_photo_alternate</span>
                </button>
                <button type="button" onclick="autoGenPartQuestion('Part C')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part C')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
            <div id="series-q-image-preview-PartC" class="hidden"></div>
          </div>
        </div>

      </div>

      <!-- Footer Actions -->
      <div class="flex justify-end gap-2 border-t border-slate-800 pt-3">
        <button type="button" onclick="closeSeriesModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-350 rounded-lg text-xs font-bold transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" id="btn-save-series-qp" onclick="saveSeriesExamQuestions()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
      </div>

    </div>
  </div>

  <script>
    // Series Exams Script State
    let dbSeriesExams = @json($seriesExams ?? []);
    let activeSeriesExamId = null;
    let activeSeriesPart = 'Part A';
    let seriesQuestionsList = { 'Part A': [], 'Part B': [], 'Part C': [] };
    let activeExamCoTags = [];
    let activeExamMaxMarks = 50;

    function initializeSeriesPattern() {
      const mode = document.querySelector('input[name="series-mode-select"]:checked').value;
      
      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/configure`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Series exam pattern configured successfully!');
          localStorage.setItem('activeClassroomTab', 'series');
          window.location.href = window.location.pathname + '?tab=series';
        } else {
          alert('Failed to configure pattern: ' + data.message);
        }
      });
    }

    function resetSeriesExamsConfig() {
      if (confirm("Are you sure you want to reset and reconfigure the series exam pattern? This will delete all current series exam papers and marks entered.")) {
        fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/configure?reset=1`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            localStorage.setItem('activeClassroomTab', 'series');
            window.location.href = window.location.pathname + '?tab=series';
          } else {
            alert('Failed to reset configuration: ' + data.message);
          }
        });
      }
    }

    function openSeriesBuilderModal(examId, name, mode, coTags, maxMarks) {
      activeSeriesExamId = examId;
      activeExamCoTags = coTags;
      activeExamMaxMarks = maxMarks;

      document.getElementById('series-modal-title').innerText = name;

      // Find the exam record from db list
      const examRecord = dbSeriesExams.find(ex => ex.id === examId);
      seriesQuestionsList = (examRecord && examRecord.questions) ? examRecord.questions : { 'Part A': [], 'Part B': [], 'Part C': [] };

      // Ensure lists are initialized
      if (!seriesQuestionsList['Part A']) seriesQuestionsList['Part A'] = [];
      if (!seriesQuestionsList['Part B']) seriesQuestionsList['Part B'] = [];
      if (!seriesQuestionsList['Part C']) seriesQuestionsList['Part C'] = [];

      // Update count requirements label based on mode
      const isSingle = (mode === 'single_co');
      document.getElementById('part-a-count-info').innerText = isSingle ? 'Questions required: 2 nos (2 Marks total)' : 'Questions required: 4 nos (4 Marks total)';
      document.getElementById('part-b-count-info').innerText = isSingle ? 'Questions required: 3 nos (9 Marks total)' : 'Questions required: 6 nos (18 Marks total)';
      document.getElementById('part-c-count-info').innerText = isSingle ? 'Questions required: 2 nos (14 Marks total)' : 'Questions required: 4 nos (28 Marks total)';

      // Hide or show CO table headers
      document.querySelectorAll('.series-co-header').forEach(el => {
        el.style.display = isSingle ? 'none' : '';
      });

      // Populate allowed CO selector for each Part
      ['Part A', 'Part B', 'Part C'].forEach(partName => {
        const key = partName.replace(' ', '');
        const coSelect = document.getElementById('series-q-co-' + key);
        if (coSelect) {
          if (isSingle) {
            coSelect.style.display = 'none';
          } else {
            coSelect.style.display = '';
            coSelect.innerHTML = '';
            coTags.forEach(co => {
              const opt = document.createElement('option');
              opt.value = co;
              opt.innerText = co;
              coSelect.appendChild(opt);
            });
          }
        }
      });

      // Apply locked states
      const isLocked = !!(examRecord && examRecord.locked);
      applySeriesLockState(isLocked);

      // Reset image attachment fields
      ['Part A', 'Part B', 'Part C'].forEach(partName => removeSeriesImage(partName));

      // Setup paste listener for diagram screenshot paste
      setupSeriesPasteListeners();

      renderSeriesQuestionsList();

      document.getElementById('series-modal').classList.remove('hidden');
    }

    let seriesPasteListenersInitialized = false;
    function setupSeriesPasteListeners() {
      if (seriesPasteListenersInitialized) return;
      ['PartA', 'PartB', 'PartC'].forEach(pKey => {
        const partName = pKey === 'PartA' ? 'Part A' : (pKey === 'PartB' ? 'Part B' : 'Part C');
        const inputEl = document.getElementById('series-q-text-' + pKey);
        if (inputEl) {
          inputEl.addEventListener('paste', function(e) {
            const items = (e.clipboardData || window.clipboardData).items;
            for (let i = 0; i < items.length; i++) {
              if (items[i].type.indexOf('image') !== -1) {
                const blob = items[i].getAsFile();
                uploadSeriesQuestionImageBlob(partName, blob);
                break;
              }
            }
          });
        }
      });
      seriesPasteListenersInitialized = true;
    }

    function handleSeriesQuestionImageUpload(partName, input) {
      if (input.files && input.files[0]) {
        uploadSeriesQuestionImageBlob(partName, input.files[0]);
      }
    }

    function uploadSeriesQuestionImageBlob(partName, file) {
      if (!file) return;
      const pKey = partName.replace(' ', '');
      const preview = document.getElementById('series-q-image-preview-' + pKey);
      if (preview) {
        preview.classList.remove('hidden');
        preview.innerHTML = `<div class="text-xs text-blue-400 flex items-center gap-1.5 py-1 font-bold animate-pulse"><div class="w-3.5 h-3.5 border-2 border-blue-400 border-t-transparent rounded-full animate-spin"></div> Uploading diagram...</div>`;
      }

      const formData = new FormData();
      formData.append('image', file);

      fetch(`/api/classroom/{{ $batchSubject->id }}/upload-assignment-image`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.image_url) {
          const urlInput = document.getElementById('series-q-image-url-' + pKey);
          if (urlInput) urlInput.value = data.image_url;
          renderSeriesImagePreview(partName, data.image_url);
        } else {
          alert(data.message || 'Image upload failed.');
          if (preview) preview.classList.add('hidden');
        }
      })
      .catch(err => {
        alert('Failed to upload diagram: ' + err.message);
        if (preview) preview.classList.add('hidden');
      });
    }

    function renderSeriesImagePreview(partName, imageUrl) {
      const pKey = partName.replace(' ', '');
      const preview = document.getElementById('series-q-image-preview-' + pKey);
      if (!preview) return;
      if (!imageUrl) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
        return;
      }
      preview.classList.remove('hidden');
      preview.innerHTML = `
        <div class="relative inline-block mt-1">
          <img src="${imageUrl}" alt="Attached Diagram" class="max-h-20 rounded border border-slate-700 bg-slate-900 object-contain p-1 shadow-md">
          <button type="button" onclick="removeSeriesImage('${partName}')" class="absolute -top-2 -right-2 w-4.5 h-4.5 bg-rose-600 hover:bg-rose-500 text-white rounded-full flex items-center justify-center shadow transition-all cursor-pointer" title="Remove Diagram">
            <span class="material-symbols-rounded text-xs">close</span>
          </button>
        </div>
      `;
    }

    function removeSeriesImage(partName) {
      const pKey = partName.replace(' ', '');
      const urlInput = document.getElementById('series-q-image-url-' + pKey);
      if (urlInput) urlInput.value = '';
      const fileInput = document.getElementById('series-q-image-file-' + pKey);
      if (fileInput) fileInput.value = '';
      renderSeriesImagePreview(partName, '');
    }

    function closeSeriesModal() {
      document.getElementById('series-modal').classList.add('hidden');
    }

    function applySeriesLockState(isLocked) {
      const btnLock = document.getElementById('btn-lock-series');
      const btnSave = document.getElementById('btn-save-series-qp');
      const lockBadge = document.getElementById('series-lock-badge');

      ['PartA', 'PartB', 'PartC'].forEach(key => {
        const editor = document.getElementById('editor-' + key);
        if (editor) {
          if (isLocked) {
            editor.classList.add('opacity-60', 'pointer-events-none');
          } else {
            editor.classList.remove('opacity-60', 'pointer-events-none');
          }
        }
      });

      if (isLocked) {
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock_open</span> Unlock`;
        btnLock.className = "px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs font-medium transition-all cursor-pointer border-0 shadow-sm";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Lock Paper`;
        btnLock.className = "px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium transition-all cursor-pointer border-0 shadow-sm";
        if (btnSave) btnSave.classList.remove('hidden');
        if (lockBadge) {
          lockBadge.classList.add('hidden');
          lockBadge.style.display = 'none';
        }
      }
    }

    function renderSeriesQuestionsList() {
      // Find lock status
      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isLocked = !!(examRecord && examRecord.locked);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      ['Part A', 'Part B', 'Part C'].forEach(partName => {
        const key = partName.replace(' ', '');
        const container = document.getElementById('series-questions-' + key);
        if (!container) return;
        container.innerHTML = '';

        const list = seriesQuestionsList[partName] || [];
        const colSpan = isSingle ? 5 : 6;
        if (list.length === 0) {
          container.innerHTML = `<tr><td colspan="${colSpan}" class="p-3 text-center text-slate-500 italic font-normal">No questions added to ${partName} yet.</td></tr>`;
          return;
        }

        list.forEach((q, idx) => {
          const tr = document.createElement('tr');
          tr.className = "bg-slate-900/10 hover:bg-slate-900/40 border-b border-slate-800 transition-all font-normal text-slate-200";
          const imgThumb = q.image_url ? `
            <div class="mt-1.5">
              <a href="${q.image_url}" target="_blank" class="inline-block" title="Click to view full diagram">
                <img src="${q.image_url}" class="max-h-16 rounded border border-slate-700 bg-slate-950 p-1 hover:border-blue-400 transition-all object-contain">
              </a>
            </div>` : '';

          tr.innerHTML = `
            <td class="p-2 font-mono text-center text-slate-350 align-top">${idx + 1}</td>
            <td class="p-2 text-slate-100 font-medium leading-relaxed text-left text-base">
              ${q.question}
              ${imgThumb}
            </td>
            <td class="p-2 text-center text-slate-200 font-medium series-co-cell align-top" ${isSingle ? 'style="display:none;"' : ''}>${q.co_tag}</td>
            <td class="p-2 text-center text-slate-200 font-medium align-top">${q.bt_level}</td>
            <td class="p-2 text-center font-mono text-emerald-450 font-bold align-top">${q.marks}M</td>
            <td class="p-2 text-center align-top">
              ${isLocked ? `<span class="text-slate-400 font-bold text-xs">Locked</span>` : `
              <button type="button" onclick="deleteSeriesQuestionDirect('${partName}', ${idx})" class="text-rose-500 hover:text-rose-600 cursor-pointer border-0 bg-transparent">
                <span class="material-symbols-rounded text-sm">delete</span>
              </button>
              `}
            </td>
          `;
          container.appendChild(tr);
        });
      });
    }

    function addSeriesQuestionDirect(partName) {
      const key = partName.replace(' ', '');
      const text = document.getElementById('series-q-text-' + key).value.trim();
      const coSelect = document.getElementById('series-q-co-' + key);
      const co = (coSelect && coSelect.style.display !== 'none' && coSelect.value) ? coSelect.value : (activeExamCoTags[0] || 'CO1');
      const bt = document.getElementById('series-q-bt-' + key).value;
      const imageUrl = document.getElementById('series-q-image-url-' + key)?.value || '';
      
      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      let maxQuestions = 0;
      if (isSingle) {
        if (partName === 'Part A') maxQuestions = 2;
        else if (partName === 'Part B') maxQuestions = 3;
        else if (partName === 'Part C') maxQuestions = 2;
      } else {
        if (partName === 'Part A') maxQuestions = 4;
        else if (partName === 'Part B') maxQuestions = 6;
        else if (partName === 'Part C') maxQuestions = 4;
      }

      const currentCount = (seriesQuestionsList[partName] || []).length;
      if (currentCount >= maxQuestions) {
        alert(`Cannot add more questions. ${partName} is restricted to a maximum of ${maxQuestions} questions in ${isSingle ? 'Single CO' : 'Combined COs'} mode.`);
        return;
      }

      let marks = 1;
      if (partName === 'Part B') marks = 3;
      else if (partName === 'Part C') marks = 7;

      if (!text && !imageUrl) {
        alert("Please enter a question description or attach a diagram.");
        return;
      }

      seriesQuestionsList[partName].push({
        question: text || '[Refer to attached diagram]',
        marks: marks,
        co_tag: co,
        bt_level: bt,
        scheme: '',
        image_url: imageUrl
      });

      renderSeriesQuestionsList();

      // Clear inputs
      document.getElementById('series-q-text-' + key).value = '';
      removeSeriesImage(partName);
    }

    function deleteSeriesQuestionDirect(partName, idx) {
      seriesQuestionsList[partName].splice(idx, 1);
      renderSeriesQuestionsList();
    }

    function autoGenPartQuestion(partName) {
      const key = partName.replace(' ', '');

      const examRecord = dbSeriesExams.find(ex => ex.id === activeSeriesExamId);
      const isSingle = examRecord ? (examRecord.mode === 'single_co') : false;

      let maxQuestions = 0;
      if (isSingle) {
        if (partName === 'Part A') maxQuestions = 2;
        else if (partName === 'Part B') maxQuestions = 3;
        else if (partName === 'Part C') maxQuestions = 2;
      } else {
        if (partName === 'Part A') maxQuestions = 4;
        else if (partName === 'Part B') maxQuestions = 6;
        else if (partName === 'Part C') maxQuestions = 4;
      }

      const currentCount = (seriesQuestionsList[partName] || []).length;
      if (currentCount >= maxQuestions) {
        alert(`Cannot suggest questions. ${partName} is restricted to a maximum of ${maxQuestions} questions in ${isSingle ? 'Single CO' : 'Combined COs'} mode.`);
        return;
      }

      const mockQuestions = [
        { question: "Define the fundamental concept and basic operations of " + partName + ".", bt_level: "Remember" },
        { question: "Explain the working architecture and execution flow for " + partName + " module.", bt_level: "Understand" },
        { question: "Develop a functional model based on guidelines defined in " + partName + ".", bt_level: "Apply" },
        { question: "Compare and contrast key components relative to " + partName + " outcomes.", bt_level: "Analyze" }
      ];

      const randomQ = mockQuestions[Math.floor(Math.random() * mockQuestions.length)];
      
      document.getElementById('series-q-text-' + key).value = randomQ.question;
      document.getElementById('series-q-bt-' + key).value = randomQ.bt_level;
      
      alert("Suggested question populated from Question Bank pool!");
    }

    function saveSeriesExamQuestions() {
      const btn = document.getElementById('btn-save-series-qp');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/${activeSeriesExamId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions: seriesQuestionsList })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          // Update local state list
          const exIdx = dbSeriesExams.findIndex(ex => ex.id === activeSeriesExamId);
          if (exIdx !== -1) {
            dbSeriesExams[exIdx].questions = seriesQuestionsList;
          }
          alert('Series exam questions saved successfully!');
        } else {
          alert('Error saving questions: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    function lockActiveSeries() {
      lockAndPublishSeries(activeSeriesExamId);
    }

    function lockAndPublishSeries(examId) {
      if (!confirm("Are you sure you want to lock and publish this series exam paper? Once locked, you cannot add, edit, or delete questions.")) {
        return;
      }

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/${examId}/lock`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Series exam locked and notification successfully published to student dashboards!');
          localStorage.setItem('activeClassroomTab', 'series');
          window.location.href = window.location.pathname + '?tab=series';
        } else {
          alert('Failed to lock exam: ' + data.message);
        }
      })
      .catch(err => {
        alert('Error: ' + err.message);
      });
    }

    function recalculateSeriesRow(input) {
      const tr = input.closest('tr');
      const regNo = tr.getAttribute('data-reg-no');
      
      let totalObtained = 0.0;
      let totalMax = 0;

      tr.querySelectorAll('.series-mark-input').forEach(inp => {
        const val = parseFloat(inp.value) || 0;
        const max = parseFloat(inp.getAttribute('max')) || 50;
        
        // Ensure input doesn't exceed max marks
        if (val > max) {
          alert("Mark cannot exceed the maximum max marks limit of " + max + "M.");
          inp.value = max;
        }

        totalObtained += parseFloat(inp.value) || 0;
        totalMax += max;
      });

      const scaledTotalCell = tr.querySelector('[data-field="series-scaled-total"]');
      if (scaledTotalCell && totalMax > 0) {
        const scaled = (totalObtained / totalMax) * 20;
        scaledTotalCell.innerText = scaled.toFixed(2);
      }
    }

    function handleR26SeriesMarkKeyDown(event, inputElem) {
      if (event.key === 'Enter' || event.key === 'Tab') {
        event.preventDefault();
        const examId = inputElem.getAttribute('data-exam-id');
        const currentRow = inputElem.closest('tr');
        if (!currentRow) return;

        if (event.shiftKey) {
          const prevRow = currentRow.previousElementSibling;
          if (prevRow) {
            const targetInput = prevRow.querySelector(`.series-mark-input[data-exam-id="${examId}"]`);
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          }
        } else {
          const nextRow = currentRow.nextElementSibling;
          if (nextRow) {
            const targetInput = nextRow.querySelector(`.series-mark-input[data-exam-id="${examId}"]`);
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          } else {
            saveSeriesExamMarks(true);
          }
        }
      }
    }
    window.handleR26SeriesMarkKeyDown = handleR26SeriesMarkKeyDown;

    let seriesMarksAutosaveTimer = null;
    function triggerSeriesMarksAutosave() {
      queueAutoSave();
    }

    function saveSeriesExamMarks(isAutoSave = false) {
      return executeConsolidatedSave(isAutoSave);
    }

    // Consolidated Reports Tab Sub-navigation (CIA vs ESE)
    function switchReportsSubtab(subTabId) {
      const subTabs = ['cie_marks', 'ese_results'];
      subTabs.forEach(id => {
        const btn = document.getElementById('reportsubbtn-' + id) || document.getElementById('subbtn-' + id);
        const pane = document.getElementById('subtab-' + id);
        if (id === subTabId) {
          if (btn) btn.className = "px-3 py-1.5 text-xs font-bold border-b-2 border-emerald-500 text-emerald-400 bg-emerald-500/10 rounded-t-md transition-all cursor-pointer flex items-center gap-1.5";
          if (pane) pane.classList.remove('hidden');
        } else {
          if (btn) btn.className = "px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700 rounded-t-md transition-all cursor-pointer flex items-center gap-1.5";
          if (pane) pane.classList.add('hidden');
        }
      });
      if (subTabId === 'ese_results') {
        document.querySelectorAll('.student-ese-row').forEach(row => updateEseRowTotals(row));
      }
    }
    function switchInternalsSubtab(subTabId) {
      switchReportsSubtab(subTabId);
    }

    const SBTE_GRADE_SCALE = {
      'S': 57.0, // 95% of 60
      'A': 51.0, // 85% of 60
      'B': 45.0, // 75% of 60
      'C': 39.0, // 65% of 60
      'D': 33.0, // 55% of 60
      'E': 27.0, // 45% of 60
      'F': 0.0,
      'FE': 0.0
    };

    function calculateEseRowFromGrade(gradeSelect) {
      const row = gradeSelect.closest('tr');
      const selectedGrade = gradeSelect.value.trim().toUpperCase();
      const markInput = row.querySelector('.ese-mark-input');

      if (selectedGrade in SBTE_GRADE_SCALE) {
        markInput.value = SBTE_GRADE_SCALE[selectedGrade].toFixed(1);
      }
      updateEseRowTotals(row);
    }

    function calculateEseRowFromMarks(markInput) {
      const row = markInput.closest('tr');
      const val = parseFloat(markInput.value);
      const gradeSelect = row.querySelector('.ese-grade-select');

      if (!isNaN(val) && markInput.value.trim() !== '') {
        const pct = (val / 60.0) * 100.0;
        let matchedGrade = 'F';
        if (pct >= 90) matchedGrade = 'S';
        else if (pct >= 80) matchedGrade = 'A';
        else if (pct >= 70) matchedGrade = 'B';
        else if (pct >= 60) matchedGrade = 'C';
        else if (pct >= 50) matchedGrade = 'D';
        else if (pct >= 40) matchedGrade = 'E';
        else matchedGrade = 'F';

        if (gradeSelect) gradeSelect.value = matchedGrade;
      }
      updateEseRowTotals(row);
    }

    function updateEseRowTotals(row) {
      if (!row) return;
      const cieElem = row.querySelector('[data-val-cie]');
      const cie = cieElem ? (parseFloat(cieElem.innerText) || 0) : 0;
      const markInput = row.querySelector('.ese-mark-input');
      const gradeSelect = row.querySelector('.ese-grade-select');
      const ese = markInput ? (parseFloat(markInput.value) || 0) : 0;
      const total = cie + ese;

      const totalElem = row.querySelector('[data-field="total_score"]');
      if (totalElem) totalElem.innerText = Math.round(total);

      const selectedGrade = gradeSelect ? gradeSelect.value.trim().toUpperCase() : '';

      // Revision 2026 Passing Norms:
      // Minimum 40% in ESE (>= 24/60) and Total >= 40/100
      let remark = 'REAPPEARANCE';
      let courseGrade = 'F';

      const isEsePass = (selectedGrade !== 'F' && selectedGrade !== 'FE' && ese >= 24);

      if (isEsePass && total >= 40) {
        remark = 'PASSED';
        if (total >= 90) courseGrade = 'S';
        else if (total >= 80) courseGrade = 'A';
        else if (total >= 70) courseGrade = 'B';
        else if (total >= 60) courseGrade = 'C';
        else if (total >= 50) courseGrade = 'D';
        else courseGrade = 'E';
      } else {
        remark = (selectedGrade === 'FE') ? 'ABSENT' : 'REAPPEARANCE';
        courseGrade = 'F';
      }

      const gDisp = row.querySelector('[data-field="grade_display"]');
      if (gDisp) {
        gDisp.innerText = courseGrade;
        if (courseGrade === 'F') {
          gDisp.className = "p-2 text-center font-bold text-rose-400 font-mono text-xs";
        } else {
          gDisp.className = "p-2 text-center font-bold text-emerald-400 font-mono text-xs";
        }
      }

      const rDisp = row.querySelector('[data-field="remark_display"]');
      if (rDisp) {
        if (remark === 'PASSED') {
          rDisp.innerHTML = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">PASSED</span>';
        } else {
          rDisp.innerHTML = '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">' + remark + '</span>';
        }
      }
    }

    function calculateEseRow(elem) {
      if (!elem) return;
      if (elem.classList.contains('ese-grade-select')) {
        calculateEseRowFromGrade(elem);
      } else if (elem.classList.contains('ese-mark-input')) {
        calculateEseRowFromMarks(elem);
      } else if (elem.classList.contains('student-ese-row')) {
        updateEseRowTotals(elem);
      } else {
        const row = elem.closest('tr');
        if (row) updateEseRowTotals(row);
      }
    }

    function handleR26EseMarkKeyDown(event, inputElem) {
      if (event.key === 'Enter' || event.key === 'Tab') {
        event.preventDefault();
        const currentRow = inputElem.closest('tr');
        if (!currentRow) return;

        if (event.shiftKey) {
          const prevRow = currentRow.previousElementSibling;
          if (prevRow) {
            const targetInput = prevRow.querySelector('.ese-mark-input');
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          }
        } else {
          const nextRow = currentRow.nextElementSibling;
          if (nextRow) {
            const targetInput = nextRow.querySelector('.ese-mark-input');
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          } else {
            triggerEseAutosave(true);
          }
        }
      }
    }
    window.handleR26EseMarkKeyDown = handleR26EseMarkKeyDown;

    let eseAutosaveTimer = null;
    function triggerEseAutosave() {
      const badge = document.getElementById('eseAutosaveBadge');
      if (badge) {
        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Unsaved scores...';
        badge.className = 'text-[10px] font-mono text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
      }
      clearTimeout(eseAutosaveTimer);
      eseAutosaveTimer = setTimeout(() => {
        saveEseMarks(true);
      }, 800);
    }

    function saveEseMarks(isAutoSave = false) {
      const marks = {};
      const grades = {};

      document.querySelectorAll('.student-ese-row').forEach(row => {
        const regNo = row.getAttribute('data-reg-no');
        const markInput = row.querySelector('.ese-mark-input');
        const gradeSelect = row.querySelector('.ese-grade-select');

        const markVal = markInput ? (parseFloat(markInput.value) || 0) : 0;
        const gradeVal = gradeSelect ? gradeSelect.value.trim().toUpperCase() : '';

        marks[regNo] = markVal;
        grades[regNo] = gradeVal;
      });

      const badge = document.getElementById('eseAutosaveBadge');
      if (badge) {
        badge.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Saving...';
        badge.className = 'text-[10px] font-mono text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
      }

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/ese-marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          marks: marks,
          grades: grades,
          entry_mode: 'grades'
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> All Scores Saved';
            badge.className = 'text-[10px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
          }
          if (!isAutoSave) {
            alert("ESE Marks & SBTE Grades saved successfully!");
          }
        } else {
          if (badge) {
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Save Failed';
            badge.className = 'text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
          }
          if (!isAutoSave) {
            alert("Error saving ESE Marks: " + data.message);
          }
        }
      })
      .catch(err => {
        if (badge) {
          badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Error saving';
          badge.className = 'text-[10px] font-mono text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded flex items-center gap-1 shadow-xs';
        }
        if (!isAutoSave) {
          alert("Error saving ESE Marks: " + err.message);
        }
      });
    }

    function checkSurveyStatuses() {
      fetch(`/api/classroom/{{ $batchSubject->id }}/survey/results`)
      .then(res => res.json())
      .then(data => {
        const statusSpans = [document.getElementById('status-midsem'), document.getElementById('status-midsem-tab')];
        const initBtns = [document.getElementById('btn-initiate-midsem'), document.getElementById('btn-initiate-midsem-tab')];
        const closeBtns = [document.getElementById('btn-close-midsem'), document.getElementById('btn-close-midsem-tab')];
        if (data.status === 'SUCCESS') {
          const srv = data.data.survey;
          if (srv.status === 'Active') {
            statusSpans.forEach(el => { if(el) { el.innerText = `Active (${data.data.responded_count} responses)`; el.className = "text-xs font-bold text-emerald-450 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.remove('hidden'));
          } else {
            statusSpans.forEach(el => { if(el) { el.innerText = `Completed & Locked`; el.className = "text-xs font-bold text-slate-400 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.add('hidden'));
          }
        } else {
          statusSpans.forEach(el => { if(el) { el.innerText = "Inactive"; el.className = "text-xs font-bold text-rose-450 flex items-center pl-2"; } });
          initBtns.forEach(el => el && el.classList.remove('hidden'));
          closeBtns.forEach(el => el && el.classList.add('hidden'));
        }
      });

      fetch(`/api/classroom/{{ $batchSubject->id }}/course-exit/results`)
      .then(res => res.json())
      .then(data => {
        const statusSpans = [document.getElementById('status-exit'), document.getElementById('status-exit-tab')];
        const initBtns = [document.getElementById('btn-initiate-exit'), document.getElementById('btn-initiate-exit-tab')];
        const closeBtns = [document.getElementById('btn-close-exit'), document.getElementById('btn-close-exit-tab')];
        if (data.status === 'SUCCESS') {
          const srv = data.data.survey;
          if (srv.status === 'Active') {
            statusSpans.forEach(el => { if(el) { el.innerText = `Active (${data.data.responded_count} responses)`; el.className = "text-xs font-bold text-emerald-450 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.remove('hidden'));
          } else {
            statusSpans.forEach(el => { if(el) { el.innerText = `Completed & Locked`; el.className = "text-xs font-bold text-slate-400 flex items-center pl-2"; } });
            initBtns.forEach(el => el && el.classList.add('hidden'));
            closeBtns.forEach(el => el && el.classList.add('hidden'));
          }
        } else {
          statusSpans.forEach(el => { if(el) { el.innerText = "Inactive"; el.className = "text-xs font-bold text-rose-450 flex items-center pl-2"; } });
          initBtns.forEach(el => el && el.classList.remove('hidden'));
          closeBtns.forEach(el => el && el.classList.add('hidden'));
        }
      });
    }

    function controlSurvey(type, action) {
      const endpoint = type === 'midsem' ? 'survey' : 'course-exit';
      const verb = action === 'initiate' ? 'initiate' : 'close';
      
      fetch(`/api/classroom/{{ $batchSubject->id }}/${endpoint}/${verb}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(`${type === 'midsem' ? 'Mid-Semester' : 'Course Exit'} survey updated successfully.`);
          checkSurveyStatuses();
        } else {
          alert(`Error updating survey: ` + data.message);
        }
      });
    }

    function submitMidsemInit(event) {
      event.preventDefault();
      const questions = {
        q5: document.getElementById('ms-q5').value.trim(),
        q6: document.getElementById('ms-q6').value.trim(),
        q7: document.getElementById('ms-q7').value.trim(),
        q8: document.getElementById('ms-q8').value.trim(),
        q9: document.getElementById('ms-q9').value.trim(),
        q10: document.getElementById('ms-q10').value.trim(),
        q11: document.getElementById('ms-q11').value.trim(),
        q12: document.getElementById('ms-q12').value.trim(),
      };

      fetch(`/api/classroom/{{ $batchSubject->id }}/survey/initiate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Mid-Semester survey initiated successfully with customized questions!');
          document.getElementById('modal-midsem-survey-init').classList.add('hidden');
          checkSurveyStatuses();
        } else {
          alert('Failed to initiate survey: ' + data.message);
        }
      });
    }

    // Submit customized Course Exit questions and activate
    function submitExitInit(event) {
      event.preventDefault();
      const questions = {
        q1: document.getElementById('ex-q1').value.trim(),
        q2: document.getElementById('ex-q2').value.trim(),
        q3: document.getElementById('ex-q3').value.trim(),
        q4: document.getElementById('ex-q4').value.trim(),
        q5: document.getElementById('ex-q5').value.trim(),
        q6: document.getElementById('ex-q6').value.trim(),
        q7: document.getElementById('ex-q7').value.trim(),
        q8: document.getElementById('ex-q8').value.trim(),
        q9: document.getElementById('ex-q9').value.trim(),
        q10: document.getElementById('ex-q10').value.trim(),
      };

      fetch(`/api/classroom/{{ $batchSubject->id }}/course-exit/initiate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ questions })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Course Exit survey initiated successfully with customized questions!');
          document.getElementById('modal-exit-survey-init').classList.add('hidden');
          checkSurveyStatuses();
        } else {
          alert('Failed to initiate survey: ' + data.message);
        }
      });
    }

    // NBA Attainment Threshold Calculation & Evaluation Settings (Revision 2026)
    function recalculateTheoryAttainmentStats(fromTarget = false) {
      const targetInput = document.getElementById('attainmentTargetPct');
      const lvl3Input = document.getElementById('attainmentLevel3Pct');
      const lvl2Input = document.getElementById('attainmentLevel2Pct');
      const lvl1Input = document.getElementById('attainmentLevel1Pct');
      const gradeSelect = document.getElementById('attainmentEseGrade');

      if (!targetInput || !lvl3Input || !lvl2Input || !lvl1Input || !gradeSelect) return;

      const targetVal = parseFloat(targetInput.value || 70);
      if (fromTarget) {
        lvl3Input.value = targetVal;
        lvl2Input.value = Math.max(0, targetVal - 10);
        lvl1Input.value = Math.max(0, targetVal - 20);
      }

      const lvl3Val = parseFloat(lvl3Input.value || targetVal);
      const lvl2Val = parseFloat(lvl2Input.value || (targetVal - 10));
      const lvl1Val = parseFloat(lvl1Input.value || (targetVal - 20));
      const eseThresholdGrade = gradeSelect.value || 'D';

      const SBTE_GRADE_POINTS = {
        'S': 10, 'A': 9, 'B': 8, 'C': 7, 'D': 6, 'E': 5, 'F': 0, 'FE': 0
      };
      const minRequiredPoints = SBTE_GRADE_POINTS[eseThresholdGrade] || 6;

      const theoryStudents = @json($studentCiaData ?? []);
      const totalStudents = theoryStudents.length;
      let appeared = 0;
      let metTarget = 0;

      theoryStudents.forEach(st => {
        const g = (st.ese_grade || '').trim().toUpperCase();
        const m = parseFloat(st.ese_marks || 0);
        const isGradeValid = g && g !== 'FE';
        const isMarkValid = m > 0;

        if (isGradeValid || isMarkValid) {
          appeared++;
          const pts = SBTE_GRADE_POINTS[g] || 0;
          const markPct = (m / 60.0) * 100.0;
          if ((pts >= 5 && pts >= minRequiredPoints) || (isMarkValid && markPct >= (minRequiredPoints >= 6 ? 50 : 40))) {
            metTarget++;
          }
        }
      });

      const metPercent = appeared > 0 ? ((metTarget / appeared) * 100).toFixed(1) : '0.0';
      let levelText = appeared === 0 ? 'Pending ESE Data' : `Level 0 (Nil - 0.0%)`;
      let levelClass = appeared === 0 ? 'text-slate-400' : 'text-rose-400';

      if (appeared > 0) {
        const pctNum = parseFloat(metPercent);
        if (pctNum >= lvl3Val) {
          levelText = `Level 3 (High - ${metPercent}%)`;
          levelClass = 'text-emerald-400';
        } else if (pctNum >= lvl2Val) {
          levelText = `Level 2 (Mod - ${metPercent}%)`;
          levelClass = 'text-amber-400';
        } else if (pctNum >= lvl1Val) {
          levelText = `Level 1 (Low - ${metPercent}%)`;
          levelClass = 'text-blue-400';
        } else {
          levelText = `Level 0 (Nil - ${metPercent}%)`;
          levelClass = 'text-rose-400';
        }
      }

      const elAppeared = document.getElementById('statTheoryAppearedStudents');
      const elMet = document.getElementById('statTheoryMetTargetText');
      const elLevel = document.getElementById('statTheoryEseLevelText');

      if (elAppeared) elAppeared.innerText = appeared;
      if (elMet) elMet.innerText = `${metTarget} (${metPercent}%)`;
      if (elLevel) {
        elLevel.innerText = levelText;
        elLevel.className = `text-sm font-black ${levelClass}`;
      }
    }

    function saveTheoryAttainmentSettings() {
      const btn = document.getElementById('btnSaveThresholdSettings');
      const originalHtml = btn ? btn.innerHTML : '';
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = `
          <svg class="w-3.5 h-3.5 animate-spin text-white inline mr-1" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>Saving...</span>
        `;
      }

      const payload = {
        entry_mode: 'grades',
        max_marks: 60,
        ese_threshold_grade: document.getElementById('attainmentEseGrade').value,
        ese_threshold_percent: 50.0,
        cie_threshold_percent: parseFloat(document.getElementById('attainmentCiePct').value || 50),
        target_student_percent: parseFloat(document.getElementById('attainmentTargetPct').value || 70),
        level3_percent: parseFloat(document.getElementById('attainmentLevel3Pct').value || 70),
        level2_percent: parseFloat(document.getElementById('attainmentLevel2Pct').value || 60),
        level1_percent: parseFloat(document.getElementById('attainmentLevel1Pct').value || 50),
      };

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/ese-marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          recalculateTheoryAttainmentStats(false);
          alert(data.message || 'Threshold settings saved successfully!');
        } else {
          alert('Failed to save settings: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(err => {
        console.error('Save error:', err);
        alert('Network error while saving threshold settings.');
      })
      .finally(() => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalHtml;
        }
      });
    }

    // Run surveys status checks on page load
    checkSurveyStatuses();
  </script>
  @include('partials.carmie_assistant')
</body>
</html>
