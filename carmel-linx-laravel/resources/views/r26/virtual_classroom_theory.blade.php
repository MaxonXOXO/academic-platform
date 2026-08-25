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
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
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
  </style>
</head>
<body class="dark min-h-screen p-4 custom-scrollbar">

  @php
    $copoData = json_decode($courseFile->parsed_copo, true) ?: [];
    $cieMarks = $copoData['cie_marks'] ?? 40;
    $eseMarks = $copoData['ese_marks'] ?? 60;
    $credit = $copoData['credit'] ?? 3;
    $ltpr = $copoData['l_t_p_r'] ?? '3:0:0:0';
    $totalHours = $copoData['total_hours'] ?? 60;
    $mappings = $copoData['mappings'] ?? [];
    $cosList = json_decode($courseFile->parsed_cos, true) ?: [];
    $modulesList = json_decode($courseFile->parsed_modules, true) ?: [];
  @endphp

  <!-- TOP COMPACT BANNER -->
  <div class="w-full max-w-none px-6 space-y-4">
    
    <!-- TOP LOGO & CONTROLS HEADER (COMPACT) -->
    <div class="flex flex-wrap justify-between items-center bg-panel border rounded-xl px-3.5 py-2 gap-2.5 shadow-sm">
      <!-- Left: Logo & App Title -->
      <div class="flex items-center gap-2.5">
        <img src="/logo.jpg" class="w-8 h-8 rounded-lg object-cover shadow-sm">
        <div>
          <div class="text-sm font-bold tracking-tight text-title flex items-center gap-1.5">
            <span>Carmel Linx</span>
            <span class="text-[10px] font-bold font-mono px-1.5 py-0.2 bg-sky-500/15 text-sky-400 border border-sky-500/30 rounded">{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}</span>
            @php $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled(); @endphp
            @if($isAiActive)
              <span class="px-2 py-0.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded-md text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs" title="Gemini AI Active"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span> AI Active</span>
            @else
              <span class="px-2 py-0.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded-md text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>
            @endif
          </div>
          <p class="text-[10px] text-muted font-bold uppercase tracking-wider leading-none">Lecturer Console</p>
        </div>
      </div>

      <!-- Right: Mode Toggle, Lecturer Profile & Back Button -->
      <div class="flex items-center gap-1.5">
        <!-- Dark/Light Mode Toggle -->
        <button onclick="toggleTheme()" class="p-0.5 px-1 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all cursor-pointer border border-slate-700/50" title="Toggle Dark/Light Mode">
          <span id="theme-icon" class="material-symbols-rounded text-[11px] block">light_mode</span>
        </button>

        <!-- Lecturer Profile -->
        <div class="flex items-center gap-1.5 border-l border-slate-700/60 pl-1.5">
          @if(Session::get('userPhoto'))
            <img src="{{ Session::get('userPhoto') }}" class="w-6 h-6 rounded-full object-cover border border-slate-700 shadow-xs" alt="{{ Session::get('userName', 'Antony Varghese') }}">
          @else
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100" class="w-6 h-6 rounded-full object-cover border border-slate-700 shadow-xs" alt="{{ Session::get('userName', 'Antony Varghese') }}">
          @endif
          <div class="hidden sm:block text-left">
            <p class="text-[10px] font-bold text-title leading-tight">{{ Session::get('userName', 'Antony Varghese') }}</p>
            <p class="text-[9px] text-muted leading-none">Subject Staff</p>
          </div>
        </div>

        <button onclick="toggleSidebarWideMode()" id="btn-fullscreen-toggle" class="p-1 px-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all border border-slate-700/60 cursor-pointer flex items-center justify-center" title="Fullscreen">
          <span class="material-symbols-rounded text-[12px]">fullscreen</span>
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
          <span class="material-symbols-rounded text-[12px] text-rose-400">arrow_back</span>
        </a>
      </div>
    </div>

    <!-- SUBJECT META CARD / TITLE PANEL & EVALUATION STRIP (COMPACT & SIMPLE) -->
    <div class="bg-panel border rounded-xl px-4 py-2 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
      <div class="flex flex-wrap items-center gap-2 text-xs">
        <h1 class="text-sm font-bold text-title flex items-center gap-1.5">
          <span>Virtual Classroom (Theory)</span>
        </h1>
        <span class="text-muted text-xs">•</span>
        <span class="px-3 py-1 bg-slate-800/90 text-slate-100 font-bold text-sm sm:text-base rounded-md border border-slate-700/80 shadow-xs tracking-tight">{{ $batchSubject->subject_name }}</span>
        <span class="px-1.5 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded font-mono text-[11px] font-semibold">{{ $batchSubject->subject_code }}</span>
        <span class="text-muted text-xs">•</span>
        <span class="font-bold text-slate-100 text-xs px-2 py-0.5 bg-slate-800/80 rounded border border-slate-700/60">Sem {{ $batchSubject->semester }}</span>
        <span class="text-muted text-xs">•</span>
        <span class="px-2 py-0.5 bg-amber-500/15 text-amber-300 border border-amber-500/30 rounded font-mono text-[11px] font-bold shadow-xs">{{ $batchSubject->classroom_id }}</span>
      </div>

      <!-- COMPACT EVALUATION METRICS -->
      <div class="flex flex-wrap items-center gap-1.5 text-[11px] font-mono text-muted">
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded text-slate-300">CIA: <strong class="text-title">{{ $cieMarks }}M</strong></span>
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded text-slate-300">ESE: <strong class="text-title">{{ $eseMarks }}M</strong></span>
        <span class="px-2 py-0.5 bg-slate-900/60 border border-slate-800 rounded text-slate-300">Credits: <strong class="text-title">{{ $credit }}</strong></span>
        <span class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 rounded text-emerald-400"><strong>{{ $totalHours }} Hrs</strong></span>
      </div>
    </div>

    <!-- TOP HORIZONTAL TAB NAVIGATION BAR WITH CHEVRON SCROLL CONTROLS -->
    <div class="relative w-full bg-panel border-2 border-sky-500/50 dark:border-sky-500/40 shadow-md shadow-sky-500/10 rounded-xl p-1.5 flex items-center group">
      <!-- Left Chevron Scroll Button -->
      <button id="tab-scroll-left" onclick="scrollTabs(-240)" class="hidden sm:flex absolute left-2 z-20 p-1 rounded-lg bg-slate-900/90 text-slate-300 hover:text-white hover:bg-indigo-600 border border-slate-700/80 shadow-md backdrop-blur-md transition-all cursor-pointer items-center justify-center opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:pointer-events-none" title="Scroll Left">
        <span class="material-symbols-rounded text-xs">chevron_left</span>
      </button>

      <!-- Left Fade Mask -->
      <div id="fade-left" class="pointer-events-none absolute left-0 top-0 bottom-0 w-6 bg-gradient-to-r from-slate-950/80 to-transparent z-10 rounded-l-xl opacity-0 transition-opacity"></div>

      <!-- Scrollable Tab Container (Scrollbar hidden via CSS) -->
      <div id="tab-scroll-container" onscroll="checkTabScrollOverflow()" class="w-full flex items-center gap-1 overflow-x-auto no-scrollbar scroll-smooth px-1 py-0.5">
        <button onclick="switchTab('outline')" id="btn-outline" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all bg-sky-500/15 text-sky-400 border border-sky-500/40 cursor-pointer shadow-xs shrink-0">
          <span class="material-symbols-rounded text-xs">import_contacts</span>
          Course Outline
        </button>
        
        <button onclick="switchTab('planner')" id="btn-planner" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">calendar_month</span>
          Lesson Planner
        </button>
        
        <button onclick="switchTab('cia')" id="btn-cia" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">fact_check</span>
          Continuous Assessment
        </button>
        
        <button onclick="switchTab('roster')" id="btn-roster" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">group</span>
          Student Roster ({{ $students->count() }})
        </button>

        <button onclick="switchTab('series')" id="btn-series" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">quiz</span>
          Series Exams
        </button>

        <button onclick="switchTab('internals')" id="btn-internals" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">assignment_turned_in</span>
          Internal Marks
        </button>

        <button onclick="switchTab('attainment')" id="btn-attainment" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">equalizer</span>
          Course Attainment & Surveys
        </button>

        <button onclick="switchTab('materials')" id="btn-materials" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0">
          <span class="material-symbols-rounded text-xs">folder_special</span>
          Study Materials & Pre-Class Hub
        </button>

        <a href="/r26/classroom/course-file/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer no-underline shrink-0 ml-auto">
          <span class="material-symbols-rounded text-xs">folder_open</span>
          Course File Prep R2026
        </a>
      </div>

      <!-- Right Fade Mask -->
      <div id="fade-right" class="pointer-events-none absolute right-0 top-0 bottom-0 w-6 bg-gradient-to-l from-slate-950/80 to-transparent z-10 rounded-r-xl opacity-0 transition-opacity"></div>

      <!-- Right Chevron Scroll Button -->
      <button id="tab-scroll-right" onclick="scrollTabs(240)" class="hidden sm:flex absolute right-2 z-20 p-1 rounded-lg bg-slate-900/90 text-slate-300 hover:text-white hover:bg-indigo-600 border border-slate-700/80 shadow-md backdrop-blur-md transition-all cursor-pointer items-center justify-center opacity-0 group-hover:opacity-100 disabled:opacity-0 disabled:pointer-events-none" title="Scroll Right">
        <span class="material-symbols-rounded text-xs">chevron_right</span>
      </button>
    </div>

    <!-- MAIN FULL-WIDTH WORKSPACE -->
    <div id="main-classroom-workspace" class="w-full space-y-4">
      
      <!-- DETAILS PANEL COLUMN (100% FULL WIDTH) -->
      <div id="details-panel-column" class="w-full transition-all duration-300">
        
        <!-- TAB: COURSE OUTLINE -->
        <div id="tab-outline" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4">
          <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-emerald-400">import_contacts</span>
                Syllabus & Course Outline
              </h3>
            </div>
            <div class="flex items-center gap-1.5">
              @if($courseFile->syllabus_pdf_path)
                <a href="/storage/{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="px-2 py-1 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">picture_as_pdf</span>
                  Preview PDF
                </a>
              @endif
              <button onclick="document.getElementById('syllabusFileInput').click()" class="px-2 py-1 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded text-xs">upload_file</span>
                Upload PDF
              </button>
            </div>
            <input type="file" id="syllabusFileInput" accept="application/pdf" class="hidden" onchange="performSyllabusUpload(this)">
          </div>

          <!-- PARSED CONTENTS TABLES -->
          <div class="space-y-6">
            <!-- CO Details Table -->
            <div class="bg-panel border border-card rounded-2xl p-6 space-y-4 shadow-xl">
              <h4 class="font-black text-title text-xl md:text-2xl flex items-center gap-2.5 border-b border-slate-800 pb-3">
                <span class="material-symbols-rounded text-emerald-400 text-2xl md:text-3xl">stars</span>
                Course Outcomes (COs)
              </h4>
              <div class="border border-card rounded-xl overflow-hidden bg-slate-950/40">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/80 text-sm md:text-base font-black text-slate-200 uppercase tracking-wider border-b border-card">
                      <th class="py-4 px-4 pl-5 w-[14%] text-center">Outcome ID</th>
                      <th class="py-4 px-4 w-[20%] text-center">Cognitive Level</th>
                      <th class="py-4 px-4 w-[16%] text-center">Duration</th>
                      <th class="py-4 px-4 pr-5">Description</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-800">
                    @foreach($cosList as $co)
                      <tr class="hover:bg-slate-900/50 transition-colors">
                        <td class="p-4 pl-5 font-black text-emerald-400 text-center align-middle">
                          <span class="px-4 py-1.5 bg-emerald-500/20 border-2 border-emerald-500/40 rounded-xl font-mono text-base md:text-lg font-black text-emerald-400 shadow-md inline-block">{{ $co['id'] }}</span>
                        </td>
                        <td class="p-4 text-center align-middle">
                          <span class="px-4 py-1.5 bg-slate-800 text-slate-100 rounded-xl border border-slate-700 text-sm md:text-base font-bold shadow-md inline-block">{{ $co['cognitive_level'] ?? 'Understanding' }}</span>
                        </td>
                        <td class="p-4 text-center align-middle font-mono">
                          <span class="px-4 py-1.5 bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 rounded-xl text-sm md:text-base font-bold shadow-md inline-block">{{ $co['duration'] ?? '12' }} Periods</span>
                        </td>
                        <td class="p-4 pr-5 text-slate-100 leading-relaxed font-bold text-base md:text-xl align-middle">{{ $co['description'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Modules Table (Major Topics) -->
            <div class="bg-panel border border-card rounded-2xl p-6 space-y-4 shadow-xl">
              <h4 class="font-black text-title text-xl md:text-2xl flex items-center gap-2.5 border-b border-slate-800 pb-3">
                <span class="material-symbols-rounded text-emerald-400 text-2xl md:text-3xl">collections_bookmark</span>
                Course Modules & Major Topics
              </h4>
              <div class="border border-card rounded-xl overflow-hidden bg-slate-950/40">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/80 text-sm md:text-base font-black text-slate-200 uppercase tracking-wider border-b border-card">
                      <th class="py-4 px-4 pl-5 w-[15%] text-center">Module No</th>
                      <th class="py-4 px-4 w-[20%] text-center">Instructional Hours</th>
                      <th class="py-4 px-4 pr-5">Major Topics Description</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-800">
                    @foreach($modulesList as $mod)
                      <tr class="hover:bg-slate-900/50 transition-colors">
                        <td class="p-4 pl-5 font-bold text-title text-center align-middle">
                          <span class="px-3.5 py-1.5 bg-slate-800 border border-slate-700 rounded-xl text-slate-100 font-black uppercase tracking-wide text-sm md:text-base inline-block">Module {{ $mod['module_id'] }}</span>
                        </td>
                        <td class="p-4 text-center align-middle font-mono">
                          <span class="px-3.5 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-xl text-sm md:text-base font-bold inline-block">{{ $mod['hours'] ?? floor($totalHours / 4) }} Hours</span>
                        </td>
                        <td class="p-4 pr-5 text-slate-100 leading-relaxed font-bold text-base md:text-lg align-middle">{{ $mod['content'] ?? '' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- CO-PO Mapping Matrix -->
          <div class="bg-panel border border-card rounded-2xl p-6 space-y-4 shadow-xl">
            <h4 class="font-black text-title text-xl md:text-2xl flex items-center gap-2.5 border-b border-slate-800 pb-3">
              <span class="material-symbols-rounded text-indigo-400 text-2xl md:text-3xl">grid_on</span>
              CO-PO Correlation Matrix
            </h4>
            <div class="border border-card rounded-xl overflow-hidden bg-slate-950/40">
              <table class="w-full text-center border-collapse">
                <thead>
                  <tr class="bg-slate-900/80 text-sm md:text-base font-black text-slate-200 uppercase tracking-wider border-b border-card">
                    <th class="py-4 px-4 text-left pl-5 w-[16%]">Course Outcome</th>
                    @for($p = 1; $p <= 11; $p++)
                      <th class="py-4 px-2 text-center">PO{{ $p }}</th>
                    @endfor
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                  @foreach($cosList as $co)
                    @php
                      $coId = $co['id'];
                      $m = $mappings[$coId] ?? [];
                    @endphp
                    <tr class="hover:bg-slate-900/50 transition-colors">
                      <td class="p-4 text-left font-black text-title pl-5 align-middle">
                        <span class="px-3.5 py-1.5 bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 rounded-xl font-mono text-base md:text-lg font-black shadow-md inline-block">{{ $coId }}</span>
                      </td>
                      @for($p = 1; $p <= 11; $p++)
                        @php $val = $m["PO$p"] ?? '-'; @endphp
                        <td class="p-3.5 font-black font-mono align-middle">
                          @if($val == '3')
                            <span class="px-3 py-1 bg-emerald-500/25 text-emerald-300 border border-emerald-500/50 rounded-lg text-sm md:text-base font-black shadow-sm">3</span>
                          @elseif($val == '2')
                            <span class="px-3 py-1 bg-sky-500/25 text-sky-300 border border-sky-500/50 rounded-lg text-sm md:text-base font-black shadow-sm">2</span>
                          @elseif($val == '1')
                            <span class="px-3 py-1 bg-amber-500/25 text-amber-300 border border-amber-500/50 rounded-lg text-sm md:text-base font-black shadow-sm">1</span>
                          @else
                            <span class="text-slate-500 font-bold text-base md:text-lg">-</span>
                          @endif
                        </td>
                      @endfor
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB: LESSON PLANNER -->
        <div id="tab-planner" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
            <div>
              <h3 class="text-base font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400">calendar_month</span>
                Academic Lesson Planner
              </h3>
            </div>
            <div class="flex items-center gap-1">
              <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded text-[11px]">print</span>
                Print Lesson Plan
              </a>
              <button id="btnSaveTemplate" onclick="saveAsTemplate()" class="px-2 py-0.5 bg-violet-500/10 hover:bg-violet-500/20 text-violet-400 border border-violet-500/30 rounded text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded text-[11px]">bookmark</span>
                Save as Template
              </button>
              <button id="btnSavePlanner" onclick="saveLessonPlanEdits()" class="px-2 py-0.5 bg-indigo-600/80 hover:bg-indigo-600 text-white border border-indigo-500/30 rounded text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                <span class="material-symbols-rounded text-[11px]">save</span>
                Save Changes
              </button>
            </div>
          </div>

          <!-- PLANNER TABLE -->
          <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[980px]">
              <thead>
                <tr class="bg-slate-900/30 text-[10px] font-semibold text-muted uppercase tracking-wider border-b border-card">
                  <th class="py-1.5 px-2 w-[5%] text-center">Period</th>
                  <th class="py-1.5 px-2 w-[7%] text-center">CO Tag</th>
                  <th class="py-1.5 px-2 w-[28%]">Topic / Content Scheduled (Full Preview)</th>
                  <th class="py-1.5 px-2 w-[9%]">Pedagogy</th>
                  <th class="py-1.5 px-2 w-[9%]">Taxonomy</th>
                  <th class="py-1.5 px-2 w-[12%]">Proposed Date</th>
                  <th class="py-1.5 px-2 w-[12%]">Actual Date</th>
                  <th class="py-1.5 px-2 w-[5%] text-center">Hours</th>
                  <th class="py-1.5 px-2 w-[13%]">Status</th>
                </tr>
              </thead>
              <tbody id="plannerTableBody" class="divide-y divide-card text-sm font-normal">
                @forelse($lessonPlans as $lp)
                  <tr data-lp-id="{{ $lp->id }}" class="bg-card-hover transition-all font-normal">
                    <td class="p-2 font-mono text-center text-title period-no-display">{{ $lp->day_no }}</td>
                    <td class="p-2 text-center">
                      <select data-field="co_id" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sky-400 focus:border-sky-500 outline-none font-semibold text-xs text-center">
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
                    <td class="p-2">
                      <textarea data-field="topic_content" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs resize-y">{{ $lp->topic_content }}</textarea>
                    </td>
                    <td class="p-2">
                      <select data-field="pedagogy" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal text-xs">
                        <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                        <option value="Tutorial" {{ $lp->pedagogy == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                        <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                      </select>
                    </td>
                    <td class="p-2">
                      <input type="text" data-field="taxonomy" value="{{ $lp->taxonomy }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1.5 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs" placeholder="Taxonomy Level...">
                    </td>
                    <td class="p-2">
                      <input type="date" data-field="proposed_date" value="{{ $lp->proposed_date }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs">
                    </td>
                    <td class="p-2">
                      <input type="date" data-field="actual_date" value="{{ $lp->actual_date }}" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs">
                    </td>
                    <td class="p-2">
                      <input type="number" data-field="allocated_hours" value="{{ $lp->allocated_hours ?: 1 }}" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-1 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-center text-xs">
                    </td>
                    <td class="p-2">
                      <select data-field="status" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal text-xs min-w-[75px]">
                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                      </select>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="p-6 text-center text-muted italic font-normal">No lesson plan topics registered yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- BOTTOM ACTION BAR FOR PLANNER TABLE -->
          <div class="flex justify-between items-center pt-2">
            <button type="button" onclick="addLessonPlanRow()" class="px-2.5 py-1.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
              <span class="material-symbols-rounded text-xs">add_circle</span>
              Add Period / Row
            </button>
            <span class="text-[10px] text-muted font-mono italic">Click 'Save Changes' to commit new rows</span>
          </div>
        </div>

        <!-- TAB: CONTINUOUS INTERNAL ASSESSMENT -->
        <div id="tab-cia" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          
          <!-- SUB-VIEW 1: THREE CARDS VIEW (DEFAULT) -->
          <div id="cia-cards-view" class="space-y-4">
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-violet-400">fact_check</span>
                  Continuous Internal Assessment (CIA)
                </h3>
                <p class="text-xs text-muted mt-1">Select an assessment category to manage details individually or view the consolidated marksheet.</p>
              </div>
              <button onclick="toggleCiaView('consolidated')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs">assessment</span>
                View Consolidated Marksheet
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Attendance Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Attendance</span>
                  <span class="text-xs bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-1.5 py-0.5 rounded font-bold">5M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Automatically evaluated based on student class logs attendance metrics.</p>
                <button class="w-full py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold border border-slate-750 transition-all cursor-pointer">
                  View Logs
                </button>
              </div>

              <!-- Self Learning Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Self-Learning</span>
                  <span class="text-xs bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 px-1.5 py-0.5 rounded font-bold">15M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Average of self-learning modules, quizzes, and micro-tasks across modules.</p>
                <button onclick="toggleCiaView('self-learning')" class="w-full py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                  Assignments
                </button>
              </div>

              <!-- Series Exams Card -->
              <div class="bg-panel border border-card rounded-xl p-4 space-y-2">
                <div class="flex justify-between items-center border-b border-card pb-1.5">
                  <span class="font-medium text-title text-xs">Series Exams</span>
                  <span class="text-xs bg-purple-500/10 text-purple-400 border border-purple-500/20 px-1.5 py-0.5 rounded font-bold">20M Max</span>
                </div>
                <p class="text-xs text-muted leading-relaxed">Two written examinations covering all defined course outcomes (COs).</p>
                <button onclick="switchTab('series')" class="w-full py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm">
                  Manage Exams
                </button>
              </div>
            </div>
          </div>

          <!-- SUB-VIEW 3: CO-WISE SELF-LEARNING ACTIVITIES MARKSHEET (HIDDEN BY DEFAULT) -->
          <div id="cia-self-learning-view" class="space-y-4 hidden">
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-indigo-400">local_library</span>
                  Self-Learning Activities Marksheet (CO-wise)
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed max-w-3xl">
                  Assign self-learning marks (Max 15 per CO) for each Course Outcome.<br>
                  The average of all 4 CO marks will automatically determine the final Self-Learning Marks (out of 15 max) in the consolidated marksheet.
                </p>
              </div>
              <div class="flex items-center gap-1.5">
                <button onclick="toggleCiaView('cards')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to Categories
                </button>
                <a href="/r26/classroom/self-learning/print/{{ $batchSubject->id }}" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">print</span>
                  Print Report
                </a>
                <button id="btnSaveSelfLearning" onclick="saveSelfLearningMarks()" class="px-2 py-1 bg-indigo-600/80 hover:bg-indigo-600 text-white border border-indigo-500/30 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center gap-1 shadow-xs">
                  <span class="material-symbols-rounded text-xs">save</span>
                  Save Self-Learning
                </button>
              </div>
            </div>

            <div class="flex gap-1.5 border-b border-card pb-2">
              <button type="button" onclick="switchSelfLearningTab('CO1')" id="tabbtn-sl-CO1" class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all bg-indigo-500/15 text-indigo-400 border border-indigo-500/30">CO1 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO2')" id="tabbtn-sl-CO2" class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all text-muted hover:bg-slate-900/40">CO2 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO3')" id="tabbtn-sl-CO3" class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all text-muted hover:bg-slate-900/40">CO3 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('CO4')" id="tabbtn-sl-CO4" class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all text-muted hover:bg-slate-900/40">CO4 Self-Study</button>
              <button type="button" onclick="switchSelfLearningTab('Summary')" id="tabbtn-sl-Summary" class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all text-muted hover:bg-slate-900/40">Summary Sheet</button>
            </div>

            <!-- Max Marks Configuration Panels -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-config-{{ $coTag }}" class="sl-config-panel bg-slate-900/30 border border-slate-800 rounded-xl p-4 space-y-4">
                <!-- Header Row -->
                <div class="flex justify-between items-center border-b border-slate-850 pb-3 flex-wrap gap-2">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-400 text-base">settings</span>
                    <h5 class="font-bold text-title text-xs uppercase tracking-wider">{{ $coTag }} Marks Allocation Setup (Total: 15 Marks)</h5>
                  </div>
                  <div class="flex items-center gap-3">
                    <span id="cfg-{{ $coTag }}-status" class="font-bold text-emerald-500 text-xs"></span>
                    <button type="button" onclick="openAssignmentModal('{{ $coTag }}')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-sm flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">assignment</span>
                      Manage Assignments
                    </button>
                  </div>
                </div>

                <!-- Grid of 5 Activities -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3.5">
                  <!-- Activity 1 -->
                  <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 1 (Assignment)</label>
                    <div class="flex items-center gap-2">
                      <input type="number" step="0.5" id="cfg-{{ $coTag }}-assignment" value="{{ $selfLearningConfigs[$coTag]['assignment'] ?? 5.0 }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2.5 font-bold text-slate-100 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                      <span class="text-xs text-slate-400">M</span>
                    </div>
                  </div>

                  <!-- Activity 2 -->
                  <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 2 (MCQ Test)</label>
                    <div class="flex items-center gap-2">
                      <input type="number" step="0.5" id="cfg-{{ $coTag }}-mcq" value="{{ $selfLearningConfigs[$coTag]['mcq'] ?? 5.0 }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2.5 font-bold text-slate-100 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                      <span class="text-xs text-slate-400">M</span>
                    </div>
                  </div>

                  <!-- Activity 3 -->
                  <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 3 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act3_mode" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2 text-slate-100 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act3_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act3" value="{{ $selfLearningConfigs[$coTag]['act3'] ?? 5.0 }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2.5 font-bold text-slate-100 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>

                  <!-- Activity 4 -->
                  <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 4 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act4_mode" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2 text-slate-100 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act4_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act4" value="{{ $selfLearningConfigs[$coTag]['act4'] ?? 0.0 }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2.5 font-bold text-slate-100 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>

                  <!-- Activity 5 -->
                  <div class="bg-slate-950/40 border border-slate-850 rounded-xl p-3 space-y-2">
                    <label class="block text-xs font-bold text-slate-400">Activity 5 (Type & Marks)</label>
                    <div class="flex flex-col gap-2">
                      <select id="cfg-{{ $coTag }}-act5_mode" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2 text-slate-100 text-xs font-semibold focus:border-indigo-500 outline-none">
                        <option value="Case Study" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Case Study' ? 'selected' : '' }}>Case Study</option>
                        <option value="Activity" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Activity' ? 'selected' : '' }}>Activity/Seminar</option>
                        <option value="Minor Project" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Minor Project' ? 'selected' : '' }}>Minor Project</option>
                        <option value="Exercises" {{ ($selfLearningConfigs[$coTag]['act5_mode'] ?? '') == 'Exercises' ? 'selected' : '' }}>Exercises</option>
                      </select>
                      <div class="flex items-center gap-2">
                        <input type="number" step="0.5" id="cfg-{{ $coTag }}-act5" value="{{ $selfLearningConfigs[$coTag]['act5'] ?? 0.0 }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg py-1 px-2.5 font-bold text-slate-100 text-xs text-center focus:border-indigo-500 outline-none" oninput="validateConfigSum('{{ $coTag }}')">
                        <span class="text-xs text-slate-400">M</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

            <!-- CO-wise Entry Sheets -->
            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
              <div id="sl-table-container-{{ $coTag }}" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar hidden">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                  <thead>
                    <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                      <th class="p-3 w-[6%] text-center">Roll No</th>
                      <th class="p-3 w-[12%]">SBTE Reg No</th>
                      <th class="p-3 w-[22%]">Student Name</th>
                      <th class="p-3 w-[12%] text-center">Assignment</th>
                      <th class="p-3 w-[12%] text-center">MCQ Test</th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act3-{{ $coTag }}">Act 3</span></th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act4-{{ $coTag }}">Act 4</span></th>
                      <th class="p-3 w-[12%] text-center"><span class="cfg-label-act5-{{ $coTag }}">Act 5</span></th>
                      <th class="p-3 w-[8%] text-center">Total (15M)</th>
                    </tr>
                  </thead>
                  <tbody id="selfLearningTableBody-{{ $coTag }}" class="divide-y divide-card text-sm font-normal">
                    @forelse($studentCiaData as $sc)
                      <tr data-reg-no="{{ $sc['reg_no'] }}" class="bg-card-hover transition-all font-normal">
                        <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                        <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                        
                        <td class="p-2.5 text-center relative">
                          @if(($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted')
                            <div class="absolute top-1 right-2 flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500" title="Assignment Submitted - Grade Now"></span>
                            </div>
                          @endif
                          <input type="number" step="0.5" min="0" data-field="assignment" value="{{ $sc['co_details'][$coTag]['assignment'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border {{ ($sc['co_details'][$coTag]['submission_status'] ?? '') === 'Submitted' ? 'border-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]' : 'border-slate-800' }} rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="mcq" value="{{ $sc['co_details'][$coTag]['mcq'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act3" value="{{ $sc['co_details'][$coTag]['act3'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act4" value="{{ $sc['co_details'][$coTag]['act4'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center">
                          <input type="number" step="0.5" min="0" data-field="act5" value="{{ $sc['co_details'][$coTag]['act5'] ?? 0.0 }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs" oninput="calculateSelfLearningRow(this, '{{ $coTag }}')">
                        </td>
                        <td class="p-2.5 text-center font-mono text-emerald-400 font-bold text-base" data-field="co_total">
                          {{ $sc['co_details'][$coTag]['total'] ?? 0.0 }}
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="9" class="p-6 text-center text-muted italic font-normal">No student records enrolled.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            @endforeach

            <!-- Summary Sheet View -->
            <div id="sl-table-container-Summary" class="sl-table-container border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar hidden">
              <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">SBTE Reg No</th>
                    <th class="p-3 w-[26%]">Student Name</th>
                    <th class="p-3 w-[12%] text-center">CO1 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO2 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO3 (15M)</th>
                    <th class="p-3 w-[12%] text-center">CO4 (15M)</th>
                    <th class="p-3 w-[10%] text-center">Average (15M)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO1">{{ $sc['co_details']['CO1']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO2">{{ $sc['co_details']['CO2']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO3">{{ $sc['co_details']['CO3']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-title" id="summary-{{ $sc['reg_no'] }}-CO4">{{ $sc['co_details']['CO4']['total'] ?? 0.0 }}</td>
                      <td class="p-2.5 text-center font-mono text-emerald-450 font-bold text-base" id="summary-{{ $sc['reg_no'] }}-avg">{{ $sc['self_learning_marks'] }}</td>
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

          <!-- SUB-VIEW 2: CONSOLIDATED MARKSHEET (HIDDEN BY DEFAULT) -->
          <div id="cia-consolidated-view" class="space-y-4 hidden">
            <div class="flex justify-between items-center border-b border-slate-800/30 pb-3">
              <div>
                <h3 class="text-base font-bold text-title flex items-center gap-2">
                  <span class="material-symbols-rounded text-violet-400">table_chart</span>
                  Consolidated CIA Marks Sheet
                </h3>
                <p class="text-xs text-muted mt-1">
                  Attendance is fetched from class logs. Marks are mapped out of 5 based on Table 2.1 (90%+ = 5M, 80%-90% = 4M, 75%-80% = 3M, 70%-75% = 2M, 65%-70% = 1M, &lt;65% = 0M).
                </p>
              </div>
              <div class="flex items-center gap-2">
                <button onclick="toggleCiaView('cards')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-all border border-slate-750 cursor-pointer flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">arrow_back</span>
                  Back to Categories
                </button>
                <button id="btnSaveCia" onclick="saveCiaMarks()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition-all cursor-pointer shadow-sm">
                  Save CIA Marks
                </button>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[12%]">SBTE Reg No</th>
                    <th class="p-3 w-[20%]">Student Name</th>
                    <th class="p-3 w-[10%] text-center">Attendance %</th>
                    <th class="p-3 w-[10%] text-center">Attendance Marks (5M)</th>
                    <th class="p-3 w-[15%] text-center">Eligibility / Status</th>
                    <th class="p-3 w-[12%] text-center">Self Learning (15M)</th>
                    <th class="p-3 w-[12%] text-center">Series Exams (20M)</th>
                    <th class="p-3 w-[10%] text-center">Total CIA (40M)</th>
                  </tr>
                </thead>
                <tbody id="ciaTableBody" class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr data-reg-no="{{ $sc['reg_no'] }}" class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title font-bold">{{ $sc['sbte_reg_no'] ?: $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold" data-val-attendance="{{ $sc['attendance_marks'] }}">
                        {{ $sc['attendance_marks'] }}
                      </td>
                      <td class="p-2.5 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-bold" style="color: {{ $sc['attendance_color'] === 'emerald-450' ? '#10b981' : ($sc['attendance_color'] === 'amber-500' ? '#f59e0b' : ($sc['attendance_color'] === 'purple-400' ? '#c084fc' : '#f43f5e')) }}; background-color: {{ $sc['attendance_color'] === 'emerald-450' ? 'rgba(16,185,129,0.1)' : ($sc['attendance_color'] === 'amber-500' ? 'rgba(245,158,11,0.1)' : ($sc['attendance_color'] === 'purple-400' ? 'rgba(192,132,252,0.1)' : 'rgba(244,63,94,0.1)')) }}; border: 1px solid currentColor;">
                          {{ $sc['attendance_status'] }}
                        </span>
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="15" data-field="self_learning" value="{{ $sc['self_learning_marks'] }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="20" data-field="series_exam" value="{{ $sc['series_exam_marks'] }}" class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal" oninput="calculateRowCia(this)">
                      </td>
                      <td class="p-2.5 text-center font-mono text-indigo-400 font-bold text-base" data-field="total_cia">
                        {{ $sc['total_cia'] }}
                      </td>
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

        <!-- TAB: STUDENT ROSTER -->
        <div id="tab-roster" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-800/30 pb-3">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400">group</span>
              Student Enrollment Directory
            </h3>
          </div>

          <div class="border border-card rounded-xl overflow-hidden bg-slate-950/10">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                  <th class="p-3">Roll No</th>
                  <th class="p-3">SBTE Reg No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-card text-xs">
                @forelse($students as $student)
                  <tr class="bg-card-hover transition-all">
                    <td class="p-3 font-mono font-bold text-muted">{{ $student->roll_no ?? '-' }}</td>
                    <td class="p-3 font-mono font-bold text-title">{{ $student->sbte_reg_no ?: $student->reg_no }}</td>
                    <td class="p-3 font-bold text-title">{{ $student->name }}</td>
                    <td class="p-3 text-right">
                      <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-550 border border-emerald-500/20 rounded-md text-xs font-bold select-none">{{ $student->academic_status }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="p-6 text-center text-muted italic">No students assigned to this classroom yet.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- SERIES EXAMS TAB PANEL -->
        <div id="tab-series" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          <div class="border-b border-slate-800/30 pb-3 flex justify-between items-center">
            <h3 class="text-base font-bold text-title flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400">quiz</span>
              Series Examinations (Theory)
            </h3>
            @if(!$seriesExams->isEmpty())
              <button onclick="resetSeriesExamsConfig()" class="px-2.5 py-1 bg-rose-600/10 hover:bg-rose-600/20 text-rose-450 border border-rose-500/20 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                <span class="material-symbols-rounded text-xs">restart_alt</span> Reconfigure Pattern
              </button>
            @endif
          </div>

          @if($seriesExams->isEmpty())
            <!-- Unconfigured Pattern State -->
            <div class="bg-slate-900/10 border border-card rounded-xl p-6 text-center space-y-4 max-w-2xl mx-auto my-8">
              <span class="material-symbols-rounded text-4xl text-sky-450">tune</span>
              <h4 class="font-bold text-title text-sm">Configure Series Examination Pattern</h4>
              <p class="text-xs text-muted leading-relaxed">
                Please select the examination pattern according to the syllabus requirements. You can conduct 4 independent single-CO tests (25 marks each) or 2 combined-CO tests (50 marks each).
              </p>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-slate-950/20 space-y-2">
                  <input type="radio" name="series-mode-select" value="single_co" checked class="text-sky-500 focus:ring-sky-500">
                  <span class="font-bold text-title text-xs block">4 Single-CO Tests (25M each)</span>
                  <span class="text-[11px] text-muted block leading-snug">
                    Conduct one separate exam for each CO (CO1 to CO4). Exam duration is 1 hour. Total marks scaled to 20.
                  </span>
                </label>
                
                <label class="border border-card hover:border-sky-500/30 rounded-xl p-4 cursor-pointer block text-left bg-slate-950/20 space-y-2">
                  <input type="radio" name="series-mode-select" value="combined_co" class="text-sky-500 focus:ring-sky-500">
                  <span class="font-bold text-title text-xs block">2 Combined-CO Tests (50M each)</span>
                  <span class="text-[11px] text-muted block leading-snug">
                    Conduct two series exams combining two COs (CO1+CO2 & CO3+CO4). Exam duration is 2 hours. Total marks scaled to 20.
                  </span>
                </label>
              </div>

              <button onclick="initializeSeriesPattern()" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md inline-flex items-center gap-1.5">
                <span class="material-symbols-rounded text-sm">settings_suggest</span>
                Initialize Pattern Configuration
              </button>
            </div>
          @else
            <!-- Configured Exams State -->
            <div class="space-y-6">
              
              <!-- QP and Schemes Panel -->
              <div class="space-y-3">
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">Scheduled Series Examinations</h4>
                <div class="grid grid-cols-1 gap-3.5">
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
                        $borderColor = 'border-l-indigo-500';
                        $bgColor = 'bg-indigo-500/10';
                        $textColor = 'text-indigo-600 dark:text-indigo-400';
                      } elseif ($firstCo === 'CO4') {
                        $borderColor = 'border-l-purple-500';
                        $bgColor = 'bg-purple-500/10';
                        $textColor = 'text-purple-600 dark:text-purple-400';
                      }
                    @endphp
                    <div class="bg-white dark:bg-slate-900/30 border border-slate-200 dark:border-slate-800 border-l-4 {{ $borderColor }} rounded-xl p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-sm">
                      
                      <!-- Left: Exam Info -->
                      <div class="flex items-center gap-3">
                        <div class="px-3 py-1 rounded-lg {{ $bgColor }} {{ $textColor }} font-bold text-xs tracking-wider uppercase">
                          {{ implode(' + ', $exam->co_tags) }}
                        </div>
                        <div>
                          <h5 class="font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $exam->exam_name }}</h5>
                          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Marks: <strong class="text-slate-700 dark:text-slate-350 font-bold">{{ $exam->max_marks }} Marks</strong> | Duration: <strong class="text-slate-700 dark:text-slate-350 font-bold">{{ $exam->duration_minutes }} min</strong>
                          </p>
                        </div>
                      </div>

                      <!-- Right: Status and Actions -->
                      <div class="flex flex-wrap items-center gap-3">
                        @if($exam->locked)
                          <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-lg text-xs font-bold flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">lock</span> Locked & Published
                          </span>
                        @else
                          <span class="px-2.5 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 rounded-lg text-xs font-bold flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">edit_note</span> Drafting Mode
                          </span>
                        @endif

                        <div class="flex gap-2">
                          <button onclick='openSeriesBuilderModal({{ $exam->id }}, "{{ addslashes($exam->exam_name) }}", "{{ $exam->mode }}", {{ json_encode($exam->co_tags) }}, {{ $exam->max_marks }})' class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">edit_document</span> Build QP
                          </button>
                          <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-qp" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">print</span> Print QP
                          </a>
                          <a href="/r26/classroom/series-exams/{{ $exam->id }}/print-scheme" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-rounded text-xs">description</span> Print Scheme
                          </a>
                          @if(!$exam->locked)
                            <button onclick="lockAndPublishSeries({{ $exam->id }})" class="px-3 py-1.5 bg-violet-600 hover:bg-violet-750 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                              <span class="material-symbols-rounded text-xs">publish</span> Lock & Notify
                            </button>
                          @endif
                        </div>
                      </div>

                    </div>
                  @endforeach
                </div>
              </div>

              <!-- Marks Entry Panel -->
              <div class="space-y-3">
                <div class="flex justify-between items-center">
                  <h4 class="font-bold text-title text-xs uppercase tracking-wider">Series Exam detailed marksheet</h4>
                  <div class="flex items-center gap-2">
                    <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                      <span class="material-symbols-rounded text-xs">print</span> Print Marks Report
                    </a>
                    <button id="btnSaveSeriesMarks" onclick="saveSeriesExamMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer shadow-md flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs font-bold">save</span> Save Series Marks
                    </button>
                  </div>
                </div>

                <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
                  <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                      <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                        <th class="p-3 w-[6%] text-center">Roll No</th>
                        <th class="p-3 w-[15%]">Register No</th>
                        <th class="p-3">Student Name</th>
                        @foreach($seriesExams as $exam)
                          <th class="p-3 text-center w-[15%]">{{ $exam->exam_name }} ({{ $exam->max_marks }}M)</th>
                        @endforeach
                        <th class="p-3 text-center w-[12%]">Scaled Score (20M)</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-card text-xs" id="seriesMarksTableBody">
                      @foreach($studentCiaData as $sc)
                        <tr class="bg-card-hover transition-all" data-reg-no="{{ $sc['reg_no'] }}">
                          <td class="p-3 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                          <td class="p-3 font-mono text-title">{{ $sc['reg_no'] }}</td>
                          <td class="p-3 text-title font-bold">{{ $sc['name'] }}</td>
                          @foreach($seriesExams as $exam)
                            <td class="p-3 text-center">
                              <input type="number" step="0.5" min="0" max="{{ $exam->max_marks }}" 
                                     data-exam-id="{{ $exam->id }}" 
                                     value="{{ $sc['exam_marks'][$exam->id] ?? 0.0 }}" 
                                     class="w-20 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs series-mark-input"
                                     oninput="recalculateSeriesRow(this)">
                            </td>
                          @endforeach
                          <td class="p-3 text-center font-mono text-emerald-400 font-bold text-base" data-field="series-scaled-total">
                            {{ $sc['series_exam_marks'] }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          @endif
        </div>

        <!-- TAB: CONSOLIDATED INTERNAL MARKS (NEW) -->
        <div id="tab-internals" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
          
          <!-- Sub-Tab Navigation Header -->
          <div class="flex border-b border-slate-800 pb-2 mb-4 gap-4">
            <button onclick="switchInternalsSubtab('cie_marks')" id="subbtn-cie_marks" class="text-sm font-bold text-emerald-400 border-b-2 border-emerald-500 pb-1 cursor-pointer transition-all">
              1. CIA Marks (40M)
            </button>
            <button onclick="switchInternalsSubtab('ese_results')" id="subbtn-ese_results" class="text-sm font-bold text-slate-400 hover:text-slate-200 pb-1 cursor-pointer transition-all">
              2. ESE Marks & Final Results (100M)
            </button>
            <button onclick="switchInternalsSubtab('nba_attainment')" id="subbtn-nba_attainment" class="text-sm font-bold text-slate-400 hover:text-slate-200 pb-1 cursor-pointer transition-all">
              3. NBA Attainment (Surveys & CO-PO)
            </button>
          </div>

          <!-- SUBTAB 1: CIE MARKS -->
          <div id="subtab-cie_marks" class="space-y-4">
            <div class="flex justify-between items-center">
              <div>
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">CIA Consolidated Marksheet</h4>
                <p class="text-xs text-muted mt-0.5">Scale: Attendance (5M), Self Learning (15M), Series Exam (20M). Total out of 40M.</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="/r26/classroom/{{ $batchSubject->id }}/series-exams/print-marks" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print Series Report
                </a>
                <a href="/r26/classroom/{{ $batchSubject->id }}/internals/print-cie" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print CIA Marksheet
                </a>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[15%]">Register No</th>
                    <th class="p-3">Student Name</th>
                    <th class="p-3 w-[12%] text-center">Attendance %</th>
                    <th class="p-3 w-[12%] text-center">Attendance (5M)</th>
                    <th class="p-3 w-[15%] text-center">Self Learning / Assignment (15M)</th>
                    <th class="p-3 w-[15%] text-center">Series Exam (20M)</th>
                    <th class="p-3 w-[12%] text-center">Total CIA (40M)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['attendance_percent'] }}%</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold">{{ $sc['attendance_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['self_learning_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-title">{{ $sc['series_exam_marks'] }}</td>
                      <td class="p-2.5 text-center font-mono text-indigo-400 font-bold text-base">{{ $sc['total_cia'] }}</td>
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
          <div id="subtab-ese_results" class="space-y-4 hidden">
            <div class="flex justify-between items-center">
              <div>
                <h4 class="font-bold text-title text-xs uppercase tracking-wider">End Semester Exam (ESE) Marks entry & Grades</h4>
                <p class="text-xs text-muted mt-0.5">Enter ESE marks (out of 60) below to view consolidated final scores (CIA 40M + ESE 60M = 100M total).</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="/r26/classroom/{{ $batchSubject->id }}/final-results/print" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs">print</span> Print Final Marksheet
                </a>
                <button onclick="saveEseMarks()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1 shadow-sm">
                  <span class="material-symbols-rounded text-xs font-bold">save</span> Save ESE Marks
                </button>
              </div>
            </div>

            <div class="border border-card rounded-xl overflow-x-auto bg-slate-950/10 custom-scrollbar">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
                    <th class="p-3 w-[6%] text-center">Roll No</th>
                    <th class="p-3 w-[15%]">Register No</th>
                    <th class="p-3">Student Name</th>
                    <th class="p-3 w-[12%] text-center">CIA Marks (40M)</th>
                    <th class="p-3 w-[15%] text-center">ESE Marks (60M)</th>
                    <th class="p-3 w-[12%] text-center">Total (100M)</th>
                    <th class="p-3 w-[12%] text-center">Grade</th>
                    <th class="p-3 w-[12%] text-center">Remark</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-card text-sm font-normal">
                  @forelse($studentCiaData as $sc)
                    <tr class="bg-card-hover transition-all font-normal student-ese-row" data-reg-no="{{ $sc['reg_no'] }}">
                      <td class="p-2.5 font-mono text-center text-title">{{ $sc['roll_no'] ?: '—' }}</td>
                      <td class="p-2.5 font-mono text-title">{{ $sc['reg_no'] }}</td>
                      <td class="p-2.5 text-title font-medium">{{ $sc['name'] }}</td>
                      <td class="p-2.5 text-center font-mono text-emerald-500 font-bold" data-val-cie="{{ $sc['total_cia'] }}">{{ $sc['total_cia'] }}</td>
                      <td class="p-2.5 text-center">
                        <input type="number" step="0.5" min="0" max="60" value="{{ $sc['ese_marks'] ?? 0.0 }}" class="w-24 bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-slate-200 text-center focus:border-indigo-500 outline-none font-normal text-xs ese-mark-input" oninput="calculateEseRow(this)">
                      </td>
                      <td class="p-2.5 text-center font-mono text-title font-bold" data-field="total_score">{{ $sc['grand_total'] }}</td>
                      <td class="p-2.5 text-center font-bold" data-field="grade_display">-</td>
                      <td class="p-2.5 text-center font-bold" data-field="remark_display">-</td>
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

          <!-- SUBTAB 3: NBA ATTAINMENT -->
          <div id="subtab-nba_attainment" class="space-y-4 hidden">
             <!-- Surveys Control Panel -->
            <div class="flex flex-col gap-6">
              <div class="bg-slate-900/30 border border-slate-800 rounded-2xl p-7 space-y-4">
                <div class="flex justify-between items-start md:items-center flex-wrap gap-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center flex-shrink-0">
                      <span class="material-symbols-rounded text-lg">rate_review</span>
                    </div>
                    <div>
                      <h4 class="font-bold text-title text-base">Mid-Semester Online Survey</h4>
                      <p class="text-xs text-muted mt-0.5">SAR Criterion 2 Evaluation</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3 flex-wrap">
                    <button id="btn-initiate-midsem" onclick="document.getElementById('modal-midsem-survey-init').classList.remove('hidden')" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                    <button id="btn-close-midsem" onclick="controlSurvey('midsem', 'close')" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                    <span id="status-midsem" class="text-sm font-bold text-muted bg-slate-950/40 border border-slate-800 rounded-lg px-3 py-1.5 flex items-center">Checking status...</span>
                  </div>
                </div>
                <p class="text-sm text-slate-300 leading-relaxed border-t border-slate-800/40 pt-3">
                  Allows students to submit feedback online. Captures direct feedback on course delivery, syllabus coverage, and early corrective action points.
                </p>
              </div>

              <div class="bg-slate-900/30 border border-slate-800 rounded-2xl p-7 space-y-4">
                <div class="flex justify-between items-start md:items-center flex-wrap gap-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center flex-shrink-0">
                      <span class="material-symbols-rounded text-lg">assignment_turned_in</span>
                    </div>
                    <div>
                      <h4 class="font-bold text-title text-base">Course Exit Survey (Indirect CO)</h4>
                      <p class="text-xs text-muted mt-0.5">Indirect Attainment Assessment</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3 flex-wrap">
                    <button id="btn-initiate-exit" onclick="document.getElementById('modal-exit-survey-init').classList.remove('hidden')" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                    <button id="btn-close-exit" onclick="controlSurvey('exit', 'close')" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                    <span id="status-exit" class="text-sm font-bold text-muted bg-slate-950/40 border border-slate-800 rounded-lg px-3 py-1.5 flex items-center">Checking status...</span>
                  </div>
                </div>
                <p class="text-sm text-slate-300 leading-relaxed border-t border-slate-800/40 pt-3">
                  Evaluates indirect Course Outcome (CO) attainment parameters at semester-end. Necessary for final PO mapping calculations.
                </p>
              </div>
            </div>

            <!-- NBA Attainment Reports -->
            <div class="bg-slate-900/10 border border-slate-800 rounded-xl p-4 space-y-3">
              <div class="flex justify-between items-center">
                <div>
                  <h4 class="font-bold text-title text-sm flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-indigo-400">equalizer</span>
                    NBA 2026 Direct/Indirect CO-PO Attainment Calculation (11 POs)
                  </h4>
                  <p class="text-xs text-muted mt-0.5">Calculated using 80% Direct Attainment (CIA & ESE) + 20% Indirect Attainment (Course Exit Survey).</p>
                </div>
                <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-md">
                  <span class="material-symbols-rounded text-sm">print</span>
                  Print Final NBA Attainment Report
                </a>
              </div>
            </div>

          </div>

        </div>

        <!-- TAB: COURSE ATTAINMENT & SURVEYS (NEW) -->
        <div id="tab-attainment" class="tab-panel bg-panel border rounded-xl p-5 shadow-md space-y-4 hidden">
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

          <div class="flex flex-col gap-6">
            <!-- Mid Sem Survey -->
            <div class="bg-slate-900/30 border-2 border-indigo-500/40 rounded-2xl p-7 space-y-4 shadow-[0_0_15px_rgba(99,102,241,0.12)]">
              <div class="flex justify-between items-start md:items-center flex-wrap gap-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-rounded text-lg">rate_review</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-title text-base">Mid-Semester Online Survey</h4>
                    <p class="text-xs text-muted mt-0.5">SAR Criterion 2 Evaluation</p>
                  </div>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                  <button id="btn-initiate-midsem-tab" onclick="document.getElementById('modal-midsem-survey-init').classList.remove('hidden')" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                  <button id="btn-close-midsem-tab" onclick="controlSurvey('midsem', 'close')" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                  <span id="status-midsem-tab" class="text-sm font-bold text-muted bg-slate-950/40 border border-slate-800 rounded-lg px-3 py-1.5 flex items-center">Checking status...</span>
                </div>
              </div>
              <p class="text-sm text-slate-300 leading-relaxed border-t border-slate-800/40 pt-3">
                Allows students to submit feedback online. Captures direct feedback on course delivery, syllabus coverage, and early corrective action points.
              </p>
            </div>

            <!-- Course Exit Survey -->
            <div class="bg-slate-900/30 border-2 border-indigo-500/40 rounded-2xl p-7 space-y-4 shadow-[0_0_15px_rgba(99,102,241,0.12)]">
              <div class="flex justify-between items-start md:items-center flex-wrap gap-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-rounded text-lg">assignment_turned_in</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-title text-base">Course Exit Survey (Indirect CO)</h4>
                    <p class="text-xs text-muted mt-0.5">Indirect Attainment Assessment</p>
                  </div>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                  <button id="btn-initiate-exit-tab" onclick="document.getElementById('modal-exit-survey-init').classList.remove('hidden')" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer shadow-sm">Open Survey</button>
                  <button id="btn-close-exit-tab" onclick="controlSurvey('exit', 'close')" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-bold transition-all cursor-pointer hidden shadow-sm">Close & Lock</button>
                  <span id="status-exit-tab" class="text-sm font-bold text-muted bg-slate-950/40 border border-slate-800 rounded-lg px-3 py-1.5 flex items-center">Checking status...</span>
                </div>
              </div>
              <p class="text-sm text-slate-300 leading-relaxed border-t border-slate-800/40 pt-3">
                Evaluates indirect Course Outcome (CO) attainment parameters at semester-end. Necessary for final PO mapping calculations.
              </p>
            </div>

            <!-- NBA Attainment Calculations -->
            <div class="bg-slate-900/10 border-2 border-emerald-500/40 rounded-2xl p-6 space-y-4 shadow-[0_0_15px_rgba(16,185,129,0.12)]">
              <div class="flex justify-between items-center">
                <div>
                  <h4 class="font-bold text-title text-sm flex items-center gap-1.5">
                    <span class="material-symbols-rounded text-emerald-400">equalizer</span>
                    NBA 2026 Direct/Indirect CO-PO Attainment Calculation (11 POs)
                  </h4>
                  <p class="text-xs text-muted mt-0.5">Calculated using 80% Direct Attainment (CIA & ESE) + 20% Indirect Attainment (Course Exit Survey).</p>
                </div>
                <a href="/r26/classroom/{{ $batchSubject->id }}/nba/attainment-report" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-md">
                  <span class="material-symbols-rounded text-sm">print</span>
                  Print Final NBA Attainment Report
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL: MID-SEM SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-midsem-survey-init" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200">
          <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto" style="background-color: #0f172a !important;">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400">rate_review</span>
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
                  <input type="text" id="ms-q5" value="The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q2. CO1 - Syllabus Delivery Pace</label>
                  <input type="text" id="ms-q6" value="The pace, speed, and coverage of the syllabus completed so far is appropriate." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q3. CO2 - Concept Clarity & Application</label>
                  <input type="text" id="ms-q7" value="The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q4. CO2 - Effectiveness of ICT/PPT Tools</label>
                  <input type="text" id="ms-q8" value="The use of teaching tools, animations, PPTs, model demonstrations, or ICT tools is effective." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q5. CO3 - Doubt Clearing & Interaction</label>
                  <input type="text" id="ms-q9" value="The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q6. CO3 - Test & Assignment Relevance</label>
                  <input type="text" id="ms-q10" value="Internal assessment test questions and assignments match the topics taught in class." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q7. CO4 - Fairness in Evaluation</label>
                  <input type="text" id="ms-q11" value="Evaluation of mid-semester tests or submissions is fair, timely, and transparent." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q8. CO4 - Guidance for Slow Learners</label>
                  <input type="text" id="ms-q12" value="The teacher provides extra guidance, remedial tips, or support to slow learners." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-midsem-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-xs transition-all cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-bold text-xs transition-all cursor-pointer">Activate & Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        <!-- MODAL: COURSE EXIT SURVEY INITIATION PREVIEW & EDIT -->
        <div id="modal-exit-survey-init" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden text-slate-200">
          <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl p-6 space-y-4 shadow-2xl max-h-[85vh] overflow-y-auto" style="background-color: #0f172a !important;">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
              <h3 class="text-sm font-bold text-title flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400">rate_review</span>
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
                  <input type="text" id="ex-q1" value="How well did the course help you understand and remember the core academic principles, models, and structural fundamentals?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q2. CO1 - Outcome Mapping</label>
                  <input type="text" id="ex-q2" value="How clearly were the course objectives, scope, and basic terms aligned with the class presentations?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q3. CO2 - Analytical Ability</label>
                  <input type="text" id="ex-q3" value="How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q4. CO2 - Design & Analysis</label>
                  <input type="text" id="ex-q4" value="To what extent can you design models, troubleshoot bugs, or draft structural layouts based on class lessons?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q5. CO3 - Practical Skills</label>
                  <input type="text" id="ex-q5" value="How confident are you in operating laboratory kits, executing computer programs, or handling workshop machines?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q6. CO3 - Industry Standards</label>
                  <input type="text" id="ex-q6" value="How clearly do you understand safety regulations, instrumentation limits, and standard protocols?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q7. CO4 - Evaluation Standards</label>
                  <input type="text" id="ex-q7" value="To what extent did assignments, written internal exams, and presentations evaluate your skills thoroughly?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q8. CO4 - Professional Ethics</label>
                  <input type="text" id="ex-q8" value="How effectively did the course emphasize engineering ethics, environmental issues, and professional conduct?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q9. CO4 - Lifelong Learning</label>
                  <input type="text" id="ex-q9" value="How strongly has this course inspired you to self-learn, explore external publications, or research modern field advancements?" class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-250 mb-1">Q10. Overall Course Rating</label>
                  <input type="text" id="ex-q10" value="Rate your overall satisfaction with the course syllabus delivery, faculty guidance, and academic outcomes." class="w-full bg-slate-950/60 border border-slate-800 rounded p-2.5 text-slate-200 text-sm focus:border-indigo-500 outline-none font-normal">
                </div>
              </div>

              <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-exit-survey-init').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-xs transition-all cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white rounded font-bold text-xs transition-all cursor-pointer">Activate & Publish Survey</button>
              </div>
            </form>
          </div>
        </div>

        @include('partials.virtual_learning_hub_tab', ['roomType' => 'Theory'])

      </div>

    </div>

    </div>

  </div>

  <script>
    function scrollTabs(amount) {
      const container = document.getElementById('tab-scroll-container');
      if (container) {
        container.scrollBy({ left: amount, behavior: 'smooth' });
        setTimeout(checkTabScrollOverflow, 350);
      }
    }

    function checkTabScrollOverflow() {
      const container = document.getElementById('tab-scroll-container');
      const btnLeft = document.getElementById('tab-scroll-left');
      const btnRight = document.getElementById('tab-scroll-right');
      const fadeLeft = document.getElementById('fade-left');
      const fadeRight = document.getElementById('fade-right');

      if (!container) return;

      const maxScroll = container.scrollWidth - container.clientWidth;
      const currentScroll = container.scrollLeft;

      if (maxScroll > 5) {
        if (currentScroll > 10) {
          if (btnLeft) btnLeft.removeAttribute('disabled');
          if (fadeLeft) fadeLeft.style.opacity = '1';
        } else {
          if (btnLeft) btnLeft.setAttribute('disabled', 'true');
          if (fadeLeft) fadeLeft.style.opacity = '0';
        }

        if (currentScroll < maxScroll - 10) {
          if (btnRight) btnRight.removeAttribute('disabled');
          if (fadeRight) fadeRight.style.opacity = '1';
        } else {
          if (btnRight) btnRight.setAttribute('disabled', 'true');
          if (fadeRight) fadeRight.style.opacity = '0';
        }
      } else {
        if (btnLeft) btnLeft.setAttribute('disabled', 'true');
        if (btnRight) btnRight.setAttribute('disabled', 'true');
        if (fadeLeft) fadeLeft.style.opacity = '0';
        if (fadeRight) fadeRight.style.opacity = '0';
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(checkTabScrollOverflow, 100);
      window.addEventListener('resize', checkTabScrollOverflow);
    });

    function switchTab(tabId) {
      localStorage.setItem('activeClassroomTab', tabId);
      document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      const targetPanel = document.getElementById('tab-' + tabId);
      if (targetPanel) targetPanel.classList.remove('hidden');

      const tabs = ['outline', 'planner', 'cia', 'roster', 'series', 'internals', 'attainment', 'materials'];
      tabs.forEach(id => {
        const btn = document.getElementById('btn-' + id);
        if (!btn) return;
        if (id === tabId) {
          btn.className = "px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all bg-sky-500/15 text-sky-400 border border-sky-500/40 cursor-pointer shadow-xs shrink-0";
          btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
          btn.className = "px-2 py-1 rounded-md font-semibold text-[10px] flex items-center gap-1 whitespace-nowrap transition-all text-muted hover:bg-slate-800/80 hover:text-slate-200 cursor-pointer shrink-0";
        }
      });
      setTimeout(checkTabScrollOverflow, 350);
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
          window.location.reload();
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
    function addLessonPlanRow() {
      const tbody = document.getElementById('plannerTableBody');
      const trs = tbody.querySelectorAll('tr[data-lp-id]');
      let nextPeriod = 1;
      trs.forEach(tr => {
        const periodEl = tr.querySelector('.period-no-display');
        const periodInput = tr.querySelector('[data-field="day_no"]');
        const pStr = periodInput ? periodInput.value : (periodEl ? periodEl.innerText : '0');
        const pNum = parseInt(pStr, 10);
        if (!isNaN(pNum) && pNum >= nextPeriod) {
          nextPeriod = pNum + 1;
        }
      });

      const newId = 'new_' + Date.now() + '_' + (newRowCounter++);
      const tr = document.createElement('tr');
      tr.setAttribute('data-lp-id', newId);
      tr.className = "bg-sky-500/5 hover:bg-sky-500/10 transition-all font-normal";
      tr.innerHTML = `
        <td class="p-2 text-center">
          <input type="number" data-field="day_no" value="${nextPeriod}" min="1" max="200" class="w-12 bg-slate-950/50 border border-sky-500/40 rounded px-1 py-0.5 text-center text-sky-400 focus:border-sky-500 outline-none font-bold text-xs">
        </td>
        <td class="p-2 text-center">
          <select data-field="co_id" class="bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sky-400 focus:border-sky-500 outline-none font-semibold text-xs">
            <option value="CO1">CO1</option>
            <option value="CO2">CO2</option>
            <option value="CO3">CO3</option>
            <option value="CO4">CO4</option>
          </select>
        </td>
        <td class="p-2">
          <textarea data-field="topic_content" rows="2" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs resize-y" placeholder="Enter topic content..."></textarea>
        </td>
        <td class="p-2">
          <select data-field="pedagogy" class="w-full bg-slate-950 border border-slate-800 rounded px-1.5 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal text-xs">
            <option value="Lecture" selected>Lecture</option>
            <option value="Tutorial">Tutorial</option>
            <option value="Practical">Practical</option>
            <option value="Exam">Exam</option>
          </select>
        </td>
        <td class="p-2">
          <input type="text" data-field="taxonomy" value="Understand" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs" placeholder="Taxonomy Level...">
        </td>
        <td class="p-2">
          <input type="date" data-field="proposed_date" value="" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs">
        </td>
        <td class="p-2">
          <input type="date" data-field="actual_date" value="" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs">
        </td>
        <td class="p-2">
          <input type="number" data-field="allocated_hours" value="1" min="1" max="10" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-1 text-slate-200 focus:border-indigo-500 outline-none font-normal text-xs">
        </td>
        <td class="p-2 flex items-center gap-1">
          <select data-field="status" class="w-full bg-slate-950 border border-slate-800 rounded px-1 py-1 text-slate-300 focus:border-indigo-500 outline-none font-normal text-xs">
            <option value="Pending" selected>Pending</option>
            <option value="Completed">Completed</option>
          </select>
          <button type="button" onclick="this.closest('tr').remove()" class="p-1 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded transition-all" title="Remove Row">
            <span class="material-symbols-rounded text-xs">delete</span>
          </button>
        </td>
      `;

      const emptyTd = tbody.querySelector('td[colspan="9"]');
      if (emptyTd) {
        emptyTd.closest('tr').remove();
      }

      tbody.appendChild(tr);
      tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function saveLessonPlanEdits() {
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
        
        const topic = tr.querySelector('[data-field="topic_content"]').value;
        const pedagogy = tr.querySelector('[data-field="pedagogy"]').value;
        const taxonomy = tr.querySelector('[data-field="taxonomy"]').value;
        const proposed = tr.querySelector('[data-field="proposed_date"]').value || null;
        const actual = tr.querySelector('[data-field="actual_date"]').value || null;
        const hours = tr.querySelector('[data-field="allocated_hours"]').value || 1;
        const status = tr.querySelector('[data-field="status"]').value;
        
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
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/lesson-plans/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Lesson planner updated successfully!');
          window.location.reload();
        } else {
          alert('Failed to save changes: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving planner: ' + err.message);
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

    function calculateRowCia(input) {
      const tr = input.closest('tr');
      const attVal = parseFloat(tr.querySelector('[data-val-attendance]').getAttribute('data-val-attendance')) || 0;
      const selfLearningVal = parseFloat(tr.querySelector('[data-field="self_learning"]').value) || 0;
      const seriesExamVal = parseFloat(tr.querySelector('[data-field="series_exam"]').value) || 0;
      
      const total = attVal + selfLearningVal + seriesExamVal;
      tr.querySelector('[data-field="total_cia"]').innerText = total.toFixed(1);
    }

    function saveCiaMarks() {
      const rows = [];
      const trs = document.querySelectorAll('#ciaTableBody tr');
      trs.forEach(tr => {
        const regNo = tr.getAttribute('data-reg-no');
        if (!regNo) return;
        
        const selfLearning = tr.querySelector('[data-field="self_learning"]').value;
        const seriesExam = tr.querySelector('[data-field="series_exam"]').value;
        
        rows.push({
          reg_no: regNo,
          self_learning_marks: selfLearning,
          series_exam_marks: seriesExam
        });
      });

      const btn = document.getElementById('btnSaveCia');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/cia-marks/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Continuous Internal Assessment (CIA) marks saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save CIA marks: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving CIA marks: ' + err.message);
      });
    }

    function toggleCiaView(view) {
      localStorage.setItem('activeCiaView', view);
      const cardsView = document.getElementById('cia-cards-view');
      const consolidatedView = document.getElementById('cia-consolidated-view');
      const selfLearningView = document.getElementById('cia-self-learning-view');
      
      cardsView.classList.add('hidden');
      consolidatedView.classList.add('hidden');
      selfLearningView.classList.add('hidden');
      
      if (view === 'consolidated') {
        consolidatedView.classList.remove('hidden');
      } else if (view === 'self-learning') {
        selfLearningView.classList.remove('hidden');
      } else {
        cardsView.classList.remove('hidden');
      }
    }

    let currentSelfLearningTab = 'CO1';

    function switchSelfLearningTab(co) {
      localStorage.setItem('activeSelfLearningTab', co);
      currentSelfLearningTab = co;
      
      // Hide all tables & config panels
      document.querySelectorAll('.sl-table-container').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.sl-config-panel').forEach(el => el.classList.add('hidden'));
      
      // Show target table
      document.getElementById('sl-table-container-' + co).classList.remove('hidden');
      
      // If not summary, show config panel and update column headers
      if (co !== 'Summary') {
        document.getElementById('sl-config-' + co).classList.remove('hidden');
        updateActivityHeaders(co);
      }
      
      // Update sub-tab styles
      ['CO1', 'CO2', 'CO3', 'CO4', 'Summary'].forEach(item => {
        const btn = document.getElementById('tabbtn-sl-' + item);
        if (item === co) {
          btn.className = "px-2 py-1 rounded-md text-[10px] font-semibold transition-all bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 cursor-pointer";
        } else {
          btn.className = "px-2 py-1 rounded-md text-[10px] font-semibold transition-all text-muted hover:bg-slate-900/40 cursor-pointer";
        }
      });
    }

    function updateActivityHeaders(co) {
      const act3Mode = document.getElementById('cfg-' + co + '-act3_mode').value;
      const act4Mode = document.getElementById('cfg-' + co + '-act4_mode').value;
      const act5Mode = document.getElementById('cfg-' + co + '-act5_mode').value;
      
      const act3Max = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const act4Max = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const act5Max = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      document.querySelectorAll('.cfg-label-act3-' + co).forEach(el => el.innerText = act3Mode + ' (' + act3Max + 'M)');
      document.querySelectorAll('.cfg-label-act4-' + co).forEach(el => el.innerText = act4Mode + ' (' + act4Max + 'M)');
      document.querySelectorAll('.cfg-label-act5-' + co).forEach(el => el.innerText = act5Mode + ' (' + act5Max + 'M)');
    }

    function validateConfigSum(co) {
      const assignment = parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0;
      const mcq = parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0;
      const act3 = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const act4 = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const act5 = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      const total = assignment + mcq + act3 + act4 + act5;
      const statusEl = document.getElementById('cfg-' + co + '-status');
      
      if (total === 15) {
        statusEl.innerText = "✓ Valid (15 Marks)";
        statusEl.className = "font-bold text-emerald-500 text-xs";
        updateActivityHeaders(co);
        return true;
      } else {
        statusEl.innerText = "⚠ Warning: Sum is " + total + " Marks (Must be 15)";
        statusEl.className = "font-bold text-rose-500 text-xs animate-pulse";
        return false;
      }
    }

    function calculateSelfLearningRow(input, co) {
      const tr = input.closest('tr');
      const regNo = tr.getAttribute('data-reg-no');
      
      // Get configured max values
      const maxAssignment = parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0;
      const maxMcq = parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0;
      const maxAct3 = parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0;
      const maxAct4 = parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0;
      const maxAct5 = parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0;
      
      // Validate inputs do not exceed max configurations
      const field = input.getAttribute('data-field');
      let val = parseFloat(input.value) || 0;
      let limit = 0;
      
      if (field === 'assignment') limit = maxAssignment;
      else if (field === 'mcq') limit = maxMcq;
      else if (field === 'act3') limit = maxAct3;
      else if (field === 'act4') limit = maxAct4;
      else if (field === 'act5') limit = maxAct5;
      
      if (val > limit) {
        alert("Mark cannot exceed the maximum configured marks of " + limit + "M for this activity.");
        input.value = limit;
        val = limit;
      }
      
      // Compute total for this CO row
      const assignment = parseFloat(tr.querySelector('[data-field="assignment"]').value) || 0;
      const mcq = parseFloat(tr.querySelector('[data-field="mcq"]').value) || 0;
      const act3 = parseFloat(tr.querySelector('[data-field="act3"]').value) || 0;
      const act4 = parseFloat(tr.querySelector('[data-field="act4"]').value) || 0;
      const act5 = parseFloat(tr.querySelector('[data-field="act5"]').value) || 0;
      
      const rowTotal = assignment + mcq + act3 + act4 + act5;
      tr.querySelector('[data-field="co_total"]').innerText = rowTotal.toFixed(2);
      
      // Update Summary Sheet cells
      const summaryCoCell = document.getElementById('summary-' + regNo + '-' + co);
      if (summaryCoCell) {
        summaryCoCell.innerText = rowTotal.toFixed(2);
      }
      
      // Update Summary Sheet Average
      const co1Val = parseFloat(document.getElementById('summary-' + regNo + '-CO1').innerText) || 0;
      const co2Val = parseFloat(document.getElementById('summary-' + regNo + '-CO2').innerText) || 0;
      const co3Val = parseFloat(document.getElementById('summary-' + regNo + '-CO3').innerText) || 0;
      const co4Val = parseFloat(document.getElementById('summary-' + regNo + '-CO4').innerText) || 0;
      
      const avg = (co1Val + co2Val + co3Val + co4Val) / 4;
      const summaryAvgCell = document.getElementById('summary-' + regNo + '-avg');
      if (summaryAvgCell) {
        summaryAvgCell.innerText = avg.toFixed(2);
      }
    }

    function saveSelfLearningMarks() {
      // Validate all CO config sums are exactly 15 first
      let allValid = true;
      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        if (!validateConfigSum(co)) {
          allValid = false;
        }
      });
      
      if (!allValid) {
        alert("Please correct the Max Marks configurations. The sum of max marks for each CO must equal exactly 15.");
        return;
      }

      // Compile configurations
      const configs = {};
      ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
        configs[co] = {
          assignment: parseFloat(document.getElementById('cfg-' + co + '-assignment').value) || 0,
          mcq: parseFloat(document.getElementById('cfg-' + co + '-mcq').value) || 0,
          act3: parseFloat(document.getElementById('cfg-' + co + '-act3').value) || 0,
          act3_mode: document.getElementById('cfg-' + co + '-act3_mode').value,
          act4: parseFloat(document.getElementById('cfg-' + co + '-act4').value) || 0,
          act4_mode: document.getElementById('cfg-' + co + '-act4_mode').value,
          act5: parseFloat(document.getElementById('cfg-' + co + '-act5').value) || 0,
          act5_mode: document.getElementById('cfg-' + co + '-act5_mode').value,
        };
      });

      // Compile student rows
      const rows = [];
      const students = @json($studentCiaData);
      
      students.forEach(st => {
        const regNo = st.reg_no;
        const coDetails = {};
        
        ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
          const tableRow = document.querySelector('#selfLearningTableBody-' + co + ' tr[data-reg-no="' + regNo + '"]');
          if (tableRow) {
            coDetails[co] = {
              assignment: tableRow.querySelector('[data-field="assignment"]').value || 0,
              mcq: tableRow.querySelector('[data-field="mcq"]').value || 0,
              act3: tableRow.querySelector('[data-field="act3"]').value || 0,
              act4: tableRow.querySelector('[data-field="act4"]').value || 0,
              act5: tableRow.querySelector('[data-field="act5"]').value || 0,
            };
          }
        });

        rows.push({
          reg_no: regNo,
          co_details: coDetails
        });
      });

      const btn = document.getElementById('btnSaveSelfLearning');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch('/api/r26/classroom/{{ $batchSubject->id }}/self-learning/bulk-update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ configs, rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Self-learning detailed activities evaluation logs saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save self-learning: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error saving marks: ' + err.message);
      });
    }

    // Initialize default tabs & labels on page load
    document.addEventListener("DOMContentLoaded", function() {
      switchTab('outline');
      toggleCiaView('cards');
      switchSelfLearningTab('CO1');

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
      document.getElementById('modal-assignment-due-date').value = deadline;
      
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
        btnLock.className = "px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm";
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
        tr.innerHTML = `
          <td class="p-2.5 font-mono text-center text-slate-350">${idx + 1}</td>
          <td class="p-2.5 text-slate-100 font-medium leading-relaxed text-left text-base">${q.question}</td>
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
    }

    function addQuestionToModalList() {
      const text = document.getElementById('modal-q-text').value.trim();
      const marks = parseFloat(document.getElementById('modal-q-marks').value) || 5;
      const bt = document.getElementById('modal-q-bt').value;
      const scheme = document.getElementById('modal-q-scheme').value.trim();
      
      if (!text) {
        alert("Please enter question text.");
        return;
      }
      
      modalQuestionsList.push({
        question: text,
        marks: marks,
        bt_level: bt,
        scheme: scheme
      });
      
      renderModalQuestionsList();
      
      // Clear inputs
      document.getElementById('modal-q-text').value = '';
      document.getElementById('modal-q-scheme').value = '';
    }

    function deleteModalQuestion(idx) {
      modalQuestionsList.splice(idx, 1);
      renderModalQuestionsList();
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
          window.location.reload();
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
            <span class="material-symbols-rounded text-indigo-400">assignment</span>
            Manage Assignment - <span id="assignment-modal-co-title">CO1</span>
          </h3>
          <span id="modal-lock-badge" class="ml-2 px-2 py-0.5 bg-emerald-500/10 text-emerald-450 border border-emerald-500/20 text-xs font-bold rounded flex items-center gap-0.5 hidden">
            <span class="material-symbols-rounded text-xs">lock</span> Published & Locked
          </span>
        </div>
        <div class="flex items-center gap-3">
          <button type="button" id="btn-notify-assignment" onclick="notifyStudentsAssignment()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
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
            <label class="block text-xs text-slate-400 mb-1.5 font-bold">Question Description:</label>
            <textarea id="modal-q-text" rows="4" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2.5 text-slate-100 text-sm focus:border-indigo-500 outline-none font-normal" placeholder="Type assignment question description here..."></textarea>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Max Marks:</label>
              <input type="number" id="modal-q-marks" value="5" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm text-center focus:border-indigo-500 outline-none font-normal">
            </div>
            <div>
              <label class="block text-xs text-slate-400 mb-1.5 font-bold">Taxonomy Level:</label>
              <select id="modal-q-bt" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm focus:border-indigo-500 outline-none font-normal">
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
              <input type="date" id="modal-assignment-due-date" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-slate-100 text-sm focus:border-indigo-500 outline-none font-normal">
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-bold">Scheme of Evaluation / Rubrics / Hints:</label>
            <textarea id="modal-q-scheme" rows="2" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2.5 text-slate-100 text-sm focus:border-indigo-500 outline-none font-normal" placeholder="Specify evaluation guidelines here (e.g., Correct formula: 2 Marks, Steps and explanation: 3 Marks)"></textarea>
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
        <button type="button" onclick="saveAssignmentQuestions()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
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
          <button type="button" id="btn-lock-series" onclick="lockActiveSeries()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer border-0 shadow-sm">
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
              <span class="material-symbols-rounded text-sm text-indigo-400">filter_1</span> Part A (1 Mark Each)
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
              <input type="text" id="series-q-text-PartA" placeholder="Enter Part A Question Description..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartA" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartA" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember" selected>Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part A')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part A')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PART B SECTION -->
        <div class="border border-slate-850 rounded-xl p-4 bg-slate-950/40 space-y-3">
          <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-indigo-400">filter_2</span> Part B (3 Marks Each)
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
              <input type="text" id="series-q-text-PartB" placeholder="Enter Part B Question Description..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartB" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartB" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember">Remember</option>
                <option value="Understand" selected>Understand</option>
                <option value="Apply">Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part B')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part B')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PART C SECTION -->
        <div class="border border-slate-850 rounded-xl p-4 bg-slate-950/40 space-y-3">
          <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
            <h4 class="font-bold text-title text-xs uppercase tracking-wider inline-flex items-center gap-1.5 align-middle">
              <span class="material-symbols-rounded text-sm text-indigo-400">filter_3</span> Part C (7 Marks Each)
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
              <input type="text" id="series-q-text-PartC" placeholder="Enter Part C Question Description..." class="flex-1 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-indigo-500">
              <select id="series-q-co-PartC" class="w-24 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <!-- Populated dynamically -->
              </select>
              <select id="series-q-bt-PartC" class="w-28 bg-slate-900 border border-slate-800 text-slate-100 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-indigo-500">
                <option value="Remember">Remember</option>
                <option value="Understand">Understand</option>
                <option value="Apply" selected>Apply</option>
                <option value="Analyze">Analyze</option>
                <option value="Evaluate">Evaluate</option>
              </select>
              <div class="flex gap-1.5">
                <button type="button" onclick="autoGenPartQuestion('Part C')" title="Suggest from Q-Bank" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-all"><span class="material-symbols-rounded text-xs">psychology</span></button>
                <button type="button" onclick="addSeriesQuestionDirect('Part C')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-0.5"><span class="material-symbols-rounded text-xs">add</span> Add</button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Footer Actions -->
      <div class="flex justify-end gap-2 border-t border-slate-800 pt-3">
        <button type="button" onclick="closeSeriesModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-350 rounded-lg text-xs font-bold transition-all cursor-pointer border-0">Cancel</button>
        <button type="button" id="btn-save-series-qp" onclick="saveSeriesExamQuestions()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all cursor-pointer border-0 shadow-sm">Save Questions</button>
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
          window.location.reload();
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
            window.location.reload();
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

      renderSeriesQuestionsList();

      document.getElementById('series-modal').classList.remove('hidden');
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
        btnLock.disabled = true;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Locked`;
        btnLock.className = "px-3 py-1.5 bg-emerald-600/10 text-emerald-550 border border-emerald-500/20 rounded text-xs font-medium cursor-not-allowed border-0";
        if (btnSave) btnSave.classList.add('hidden');
        if (lockBadge) {
          lockBadge.classList.remove('hidden');
          lockBadge.style.display = 'inline-flex';
        }
      } else {
        btnLock.disabled = false;
        btnLock.innerHTML = `<span class="material-symbols-rounded text-xs">lock</span> Lock & Notify`;
        btnLock.className = "px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-xs font-medium transition-all cursor-pointer border-0 shadow-sm";
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
          tr.innerHTML = `
            <td class="p-2 font-mono text-center text-slate-350">${idx + 1}</td>
            <td class="p-2 text-slate-100 font-medium leading-relaxed text-left text-base">${q.question}</td>
            <td class="p-2 text-center text-slate-200 font-medium series-co-cell" ${isSingle ? 'style="display:none;"' : ''}>${q.co_tag}</td>
            <td class="p-2 text-center text-slate-200 font-medium">${q.bt_level}</td>
            <td class="p-2 text-center font-mono text-emerald-450 font-bold">${q.marks}M</td>
            <td class="p-2 text-center">
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

      if (!text) {
        alert("Please enter a question description.");
        return;
      }

      seriesQuestionsList[partName].push({
        question: text,
        marks: marks,
        co_tag: co,
        bt_level: bt,
        scheme: ''
      });

      renderSeriesQuestionsList();

      // Clear inputs
      document.getElementById('series-q-text-' + key).value = '';
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
          window.location.reload();
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

    function saveSeriesExamMarks() {
      const rows = [];
      document.querySelectorAll('#seriesMarksTableBody tr').forEach(tr => {
        const regNo = tr.getAttribute('data-reg-no');
        const examMarks = {};
        
        tr.querySelectorAll('.series-mark-input').forEach(inp => {
          const examId = inp.getAttribute('data-exam-id');
          examMarks[examId] = parseFloat(inp.value) || 0.0;
        });

        rows.push({
          reg_no: regNo,
          exam_marks: examMarks
        });
      });

      const btn = document.getElementById('btnSaveSeriesMarks');
      const originalText = btn.innerText;
      btn.disabled = true;
      btn.innerText = 'Saving...';

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/series-exams/marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rows })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;
        if (data.status === 'SUCCESS') {
          alert('Series examinations scores saved successfully!');
          window.location.reload();
        } else {
          alert('Failed to save marks: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = originalText;
        alert('Error: ' + err.message);
      });
    }

    // Consolidated Internals Tab Sub-navigation
    function switchInternalsSubtab(subTabId) {
      const subTabs = ['cie_marks', 'ese_results', 'nba_attainment'];
      subTabs.forEach(id => {
        const btn = document.getElementById('subbtn-' + id);
        const pane = document.getElementById('subtab-' + id);
        if (id === subTabId) {
          btn.className = "text-sm font-bold text-emerald-400 border-b-2 border-emerald-500 pb-1 cursor-pointer transition-all";
          pane.classList.remove('hidden');
        } else {
          btn.className = "text-sm font-bold text-slate-400 hover:text-slate-200 pb-1 cursor-pointer transition-all";
          pane.classList.add('hidden');
        }
      });
      if (subTabId === 'ese_results') {
        document.querySelectorAll('.ese-mark-input').forEach(input => calculateEseRow(input));
      }
    }

    function calculateEseRow(input) {
      const row = input.closest('tr');
      const cie = parseFloat(row.querySelector('[data-val-cie]').innerText) || 0;
      const ese = parseFloat(input.value) || 0;
      const total = cie + ese;
      row.querySelector('[data-field="total_score"]').innerText = total.toFixed(1);

      let grade = 'F';
      if (total >= 90) grade = 'S';
      else if (total >= 80) grade = 'A';
      else if (total >= 70) grade = 'B';
      else if (total >= 60) grade = 'C';
      else if (total >= 50) grade = 'D';
      else if (total >= 40) grade = 'E';
      
      let remark = 'FAIL';
      if (total >= 40 && ese >= 24) {
        remark = 'PASS';
      } else {
        grade = 'F';
      }

      const gDisp = row.querySelector('[data-field="grade_display"]');
      gDisp.innerText = grade;
      if (grade === 'F') {
        gDisp.className = "p-2.5 text-center font-bold text-rose-500";
      } else {
        gDisp.className = "p-2.5 text-center font-bold text-emerald-400";
      }

      const rDisp = row.querySelector('[data-field="remark_display"]');
      rDisp.innerText = remark;
      if (remark === 'PASS') {
        rDisp.className = "p-2.5 text-center font-bold text-emerald-400";
      } else {
        rDisp.className = "p-2.5 text-center font-bold text-rose-500";
      }
    }

    function saveEseMarks() {
      const marks = {};
      document.querySelectorAll('.student-ese-row').forEach(row => {
        const regNo = row.getAttribute('data-reg-no');
        const val = parseFloat(row.querySelector('.ese-mark-input').value) || 0;
        marks[regNo] = val;
      });

      fetch(`/api/r26/classroom/{{ $batchSubject->id }}/ese-marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ marks: marks })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("ESE Marks saved successfully!");
        } else {
          alert("Error saving ESE Marks: " + data.message);
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

    // Run surveys status checks on page load
    checkSurveyStatuses();
  </script>
</body>
</html>
