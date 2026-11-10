<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>S6 Open Elective Management Desk — Carmel Linx</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html { font-size: 90%; }
    .font-extrabold, .font-black { font-weight: 700 !important; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.3); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(56, 189, 248, 0.3); border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(56, 189, 248, 0.6); }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased custom-scrollbar">

  <!-- Top Sticky Navigation Bar -->
  <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800/80 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-lg">
    <div class="flex items-center gap-3">
      <a href="{{ session('userRole') === 'Super_Admin' ? '/dashboard/superadmin' : '/dashboard/admin' }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-premium text-xs font-bold no-underline">
        <span class="material-symbols-rounded text-sm">arrow_back</span> Return to Control Desk
      </a>
      <div class="h-5 w-px bg-slate-700 mx-1"></div>
      <div>
        <h1 class="text-base sm:text-lg font-black tracking-tight flex items-center gap-2">
          <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">Semester 6 Open Elective Desk</span>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">Rev 2021</span>
        </h1>
        <p class="text-[11px] text-slate-400 font-medium">Principal &amp; Super Admin Central Allocation &amp; Virtual Classroom Handover</p>
      </div>
    </div>

    <!-- Executive Status Badge -->
    <div class="flex items-center gap-3">
      @if($settings->is_released_to_hods)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Handed Over to HODs
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-500/20 text-amber-400 border border-amber-500/30">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span> Restricted to Principal &amp; Admin
        </span>
      @endif

      <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-slate-800 text-right">
        <div>
          <span class="block text-xs font-bold text-slate-200">{{ session('userName') }}</span>
          <span class="block text-[10px] text-cyan-400 uppercase font-black tracking-wider">{{ str_replace('_', ' ', session('userRole')) }}</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Container -->
  <main class="max-w-7xl mx-auto px-4 sm:px-8 py-6 space-y-6">

    <!-- KPI Metric Cards -->
    <section class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <!-- Target S5 Cohort -->
      <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-md">
        <div class="flex items-center justify-between text-slate-400 mb-1">
          <span class="text-xs font-bold uppercase tracking-wider">Target S5 Cohort</span>
          <span class="material-symbols-rounded text-cyan-400 text-xl">groups</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-white">{{ $totalS5Students }}</div>
        <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
          <span>Branches: AU, CE, CT, EEE, EL, ME</span>
        </div>
      </div>

      <!-- Total Registered -->
      <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-md">
        <div class="flex items-center justify-between text-slate-400 mb-1">
          <span class="text-xs font-bold uppercase tracking-wider">Students Registered</span>
          <span class="material-symbols-rounded text-blue-400 text-xl">how_to_reg</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-white flex items-baseline gap-2">
          {{ $registeredCount }}
          <span class="text-xs font-bold text-slate-400">/ {{ $totalS5Students }}</span>
        </div>
        @php
          $regPct = $totalS5Students > 0 ? round(($registeredCount / $totalS5Students) * 100) : 0;
        @endphp
        <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2 overflow-hidden">
          <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $regPct }}%"></div>
        </div>
      </div>

      <!-- Allotted Students -->
      <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-md">
        <div class="flex items-center justify-between text-slate-400 mb-1">
          <span class="text-xs font-bold uppercase tracking-wider">Roster Allotted</span>
          <span class="material-symbols-rounded text-emerald-400 text-xl">task_alt</span>
        </div>
        <div class="text-2xl sm:text-3xl font-black text-emerald-400 flex items-baseline gap-2">
          {{ $allottedCount }}
          <span class="text-xs font-bold text-slate-400">fixed</span>
        </div>
        <div class="text-[11px] text-slate-400 mt-1">
          Pending: <strong class="text-amber-400">{{ $pendingCount }}</strong>
        </div>
      </div>

      <!-- Registration Window Status -->
      <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-md">
        <div class="flex items-center justify-between text-slate-400 mb-1">
          <span class="text-xs font-bold uppercase tracking-wider">Window Status</span>
          <span class="material-symbols-rounded {{ $settings->is_registration_open ? 'text-emerald-400' : 'text-slate-500' }} text-xl">
            {{ $settings->is_registration_open ? 'timer' : 'timer_off' }}
          </span>
        </div>
        <div class="text-lg font-black {{ $settings->is_registration_open ? 'text-emerald-400' : 'text-slate-400' }}">
          {{ $settings->is_registration_open ? 'REGISTRATION LIVE' : 'WINDOW CLOSED' }}
        </div>
        <div id="liveCountdownWidget" class="text-[11px] font-mono text-cyan-400 mt-1">
          Loading timer...
        </div>
      </div>
    </section>

    <!-- HOD Setup Readiness Banner -->
    <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex flex-wrap items-center justify-between gap-4 shadow-md">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="material-symbols-rounded text-cyan-400 text-lg">fact_check</span>
          <h2 class="text-sm font-extrabold text-white">Department HOD Course Setup Status</h2>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $submittedHodsCount >= 6 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
            {{ $submittedHodsCount }} of 6 Departments Ready
          </span>
        </div>
        <p class="text-xs text-slate-400">
          HODs configure Course Code, Course Title, Syllabus PDF, and Course Outcomes from their HOD desk.
        </p>
      </div>

      <!-- Branch Badges -->
      <div class="flex flex-wrap gap-2">
        @foreach(['AU', 'CE', 'CT', 'EEE', 'EL', 'ME'] as $br)
          @php
            $brCourse = $courses->firstWhere('offering_branch', $br);
            $isSubmitted = $brCourse && ($brCourse->is_hod_submitted || !empty($brCourse->course_title));
          @endphp
          <div class="px-3 py-1.5 rounded-xl border text-xs font-bold flex items-center gap-2 {{ $isSubmitted ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' : 'bg-slate-950 border-slate-800 text-slate-400' }}">
            <span class="w-2 h-2 rounded-full {{ $isSubmitted ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
            <span>{{ $br }}</span>
            @if($isSubmitted)
              <span class="text-[10px] font-mono text-slate-300">({{ $brCourse->course_code }})</span>
            @else
              <span class="text-[10px] text-amber-400">pending</span>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-800 gap-2 overflow-x-auto pb-1">
      <button onclick="switchTab('tab-courses')" id="btn-tab-courses" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition-premium bg-cyan-500/10 text-cyan-400 border border-cyan-500/30">
        <span class="material-symbols-rounded text-base">school</span> 1. Department Offerings (Review / Override)
      </button>
      <button onclick="switchTab('tab-window')" id="btn-tab-window" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white">
        <span class="material-symbols-rounded text-base">schedule_send</span> 2. Window Timers &amp; Push Alert
      </button>
      <button onclick="switchTab('tab-allocation')" id="btn-tab-allocation" class="tab-btn px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white">
        <span class="material-symbols-rounded text-base">fact_check</span> 3. Course Roster &amp; Allocation Desk
      </button>
    </div>

    <!-- TAB 1: Department Offerings Review & Admin Override -->
    <div id="tab-courses" class="tab-content space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-base font-bold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-cyan-400">subject</span> Departmental Open Elective Offerings
          </h2>
          <p class="text-xs text-slate-400">Offerings configured by HODs. Admin can inspect or edit details below.</p>
        </div>
        <button onclick="openCourseModal()" class="px-3.5 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-cyan-900/30 transition-premium">
          <span class="material-symbols-rounded text-sm">add_circle</span> Add / Override Course
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($courses as $course)
          @php
            $stat = $courseStats[$course->id] ?? null;
            $allotted = $stat['allotted_count'] ?? 0;
            $opt1 = $stat['opt1_count'] ?? 0;
            $cap = $course->max_capacity;
            $pct = $cap > 0 ? min(100, round(($allotted / $cap) * 100)) : 0;
          @endphp
          <div class="p-5 rounded-2xl bg-slate-900/70 border border-slate-800 hover:border-slate-700 transition-premium shadow-md relative group flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/15 text-cyan-300 border border-cyan-500/25">
                  Dept: {{ $course->offering_branch }}
                </span>
                <span class="text-xs font-mono font-bold text-slate-400">Code: {{ $course->course_code }}</span>
              </div>
              <h3 class="text-sm font-extrabold text-white leading-snug">{{ $course->course_title }}</h3>
              <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $course->course_description ?: 'No syllabus description provided.' }}</p>

              @if($course->course_outcomes)
                <div class="mt-2 text-[10px] text-cyan-300/90 line-clamp-2 italic bg-slate-950/40 p-1.5 rounded-lg border border-slate-800/60">
                  <strong>COs:</strong> {{ $course->course_outcomes }}
                </div>
              @endif

              @if($course->syllabus_pdf_path)
                <div class="mt-2">
                  <a href="{{ $course->syllabus_pdf_path }}" target="_blank" class="text-[11px] font-bold text-cyan-400 hover:underline inline-flex items-center gap-1">
                    <span class="material-symbols-rounded text-sm">picture_as_pdf</span> Syllabus PDF
                  </a>
                </div>
              @endif

              <!-- Faculty Details -->
              <div class="mt-3 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                <span>Faculty: <strong class="text-slate-200">{{ $course->faculty_name ?: 'Not Assigned' }}</strong></span>
                <span>{{ $course->faculty_phone }}</span>
              </div>

              <!-- Demand & Capacity Meters -->
              <div class="mt-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 space-y-1.5">
                <div class="flex justify-between text-[11px]">
                  <span class="text-slate-400">1st Choice Demand:</span>
                  <span class="font-bold text-cyan-400">{{ $opt1 }} students</span>
                </div>
                <div class="flex justify-between text-[11px]">
                  <span class="text-slate-400">Allotted Seats:</span>
                  <span class="font-bold {{ $allotted >= $cap ? 'text-emerald-400' : 'text-slate-200' }}">
                    {{ $allotted }} / {{ $cap }}
                  </span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                  <div class="h-1.5 rounded-full {{ $allotted >= $cap ? 'bg-amber-400' : 'bg-cyan-500' }}" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            </div>

            <!-- Card Actions -->
            <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
              <a href="/admin/open-elective/export-roster/{{ $course->id }}" target="_blank" class="text-xs font-bold text-slate-400 hover:text-cyan-400 flex items-center gap-1 no-underline">
                <span class="material-symbols-rounded text-sm">print</span> Nominal Roll
              </a>
              <div class="flex items-center gap-1.5">
                <button onclick='editCourse(@json($course))' class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-premium">
                  Edit
                </button>
                <button onclick="deleteCourse({{ $course->id }}, '{{ addslashes($course->course_title) }}')" class="px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-bold transition-premium">
                  Delete
                </button>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full p-8 text-center bg-slate-900/40 rounded-2xl border border-dashed border-slate-800">
            <span class="material-symbols-rounded text-4xl text-slate-600 mb-2">library_books</span>
            <p class="text-sm font-bold text-slate-400">No Open Elective courses configured yet.</p>
          </div>
        @endforelse
      </div>
    </div>

    <!-- TAB 2: Window Timers & Push Alert -->
    <div id="tab-window" class="tab-content hidden space-y-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Schedule & Window Form -->
        <div class="p-6 rounded-2xl bg-slate-900/70 border border-slate-800 shadow-md space-y-4">
          <h2 class="text-base font-bold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-cyan-400">event_available</span> Registration Window &amp; Countdown Timers
          </h2>
          <p class="text-xs text-slate-400">Set start and end dates. When active, students will see the countdown timer and registration cards on their mobile app.</p>

          <form id="formSettings" onsubmit="handleSaveSettings(event)" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Registration Start Date &amp; Time</label>
              <input type="datetime-local" id="reg_start_at" name="reg_start_at" value="{{ $settings->reg_start_at ? $settings->reg_start_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Registration Closing Date &amp; Time</label>
              <input type="datetime-local" id="reg_end_at" name="reg_end_at" value="{{ $settings->reg_end_at ? $settings->reg_end_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
            </div>

            <!-- Master Toggle Switch -->
            <div class="p-3.5 rounded-xl bg-slate-950/80 border border-slate-800 flex items-center justify-between">
              <div>
                <span class="block text-xs font-bold text-white">Open Registration Immediately</span>
                <span class="text-[11px] text-slate-400">Allows S5 students to register through mobile app</span>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="is_registration_open" name="is_registration_open" value="1" {{ $settings->is_registration_open ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
              </label>
            </div>

            <button type="submit" id="btnSaveSettings" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition-premium">
              Save Window Settings
            </button>
          </form>
        </div>

        <!-- Push Notification Broadcast Desk -->
        <div class="p-6 rounded-2xl bg-slate-900/70 border border-slate-800 shadow-md space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
              <span class="material-symbols-rounded text-blue-400">notifications_active</span> Broadcast Web Push to S5 Cohort
            </h2>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30">Target: {{ $totalS5Students }} Students</span>
          </div>
          <p class="text-xs text-slate-400">Once HODs have set up their courses, dispatch push notifications to 5th semester students with direct registration link.</p>

          <form id="formPush" onsubmit="handleSendPush(event)" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Notification Title</label>
              <input type="text" id="push_title" name="title" value="{{ $settings->push_title }}" required class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-blue-500 font-medium">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1">Notification Body</label>
              <textarea id="push_body" name="body" rows="3" required class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-blue-500 font-medium">{{ $settings->push_body }}</textarea>
            </div>

            <div class="p-3 rounded-xl bg-slate-950 text-[11px] text-slate-400 font-mono">
              Link URL: <span class="text-cyan-400 font-bold">/student/open-elective</span> (Requires SBTE PRN login)
            </div>

            <button type="submit" id="btnSendPush" class="w-full py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow-md transition-premium flex items-center justify-center gap-2">
              <span class="material-symbols-rounded text-base">send</span> Broadcast Alert Now
            </button>
          </form>

          <div id="pushStatusFeedback" class="hidden p-3 rounded-xl text-xs font-medium"></div>
        </div>
      </div>

      <!-- Trial Run & Testing Sandbox -->
      <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-900/90 via-slate-900 to-indigo-950/40 border border-indigo-500/30 shadow-xl space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-black">
              <span class="material-symbols-rounded text-lg">science</span>
            </span>
            <div>
              <h3 class="text-sm font-extrabold text-white">Trial Run &amp; Simulation Sandbox</h3>
              <p class="text-xs text-slate-400">Test the student registration and FCFS allocation workflow safely before sending actual push alerts to students.</p>
            </div>
          </div>

          <button onclick="handleResetTrialData()" id="btnResetTrialData" class="px-4 py-2 rounded-xl bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 text-xs font-bold flex items-center gap-1.5 transition-premium shadow-sm">
            <span class="material-symbols-rounded text-sm">restart_alt</span> Reset All Trial Registrations
          </button>
        </div>

        <!-- 3-Step Safety Workflow Guide -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <div class="p-3.5 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
            <span class="text-[10px] uppercase font-black text-cyan-400">1. Silent Activation</span>
            <p class="text-xs text-slate-300">Turn on <em>Open Registration Immediately</em> above and save. This enables the portal <strong>without</strong> sending any push notifications.</p>
          </div>
          <div class="p-3.5 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
            <span class="text-[10px] uppercase font-black text-indigo-400">2. Test Registration</span>
            <p class="text-xs text-slate-300">Click a branch button below to simulate registration as that branch's student, or log in with sample credentials in an incognito window.</p>
          </div>
          <div class="p-3.5 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
            <span class="text-[10px] uppercase font-black text-rose-400">3. Safe Reset</span>
            <p class="text-xs text-slate-300">After testing FCFS auto-allocation, click <em>Reset All Trial Registrations</em>. All test records wipe cleanly back to zero.</p>
          </div>
        </div>

        <!-- One-Click Quick Preview by Branch -->
        <div>
          <span class="block text-xs font-bold text-slate-300 mb-2">One-Click Branch Simulator (Admin Preview):</span>
          <div class="flex flex-wrap gap-2">
            <a href="/student/open-elective?preview_branch=CE" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-cyan-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-cyan-400"></span> Simulate Civil (CE) Student
            </a>
            <a href="/student/open-elective?preview_branch=ME" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-amber-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-amber-400"></span> Simulate Mechanical (ME) Student
            </a>
            <a href="/student/open-elective?preview_branch=CT" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-emerald-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Simulate Computer (CT) Student
            </a>
            <a href="/student/open-elective?preview_branch=EEE" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-purple-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-purple-400"></span> Simulate Electrical (EEE) Student
            </a>
            <a href="/student/open-elective?preview_branch=EL" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-pink-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-pink-400"></span> Simulate Electronics (EL) Student
            </a>
            <a href="/student/open-elective?preview_branch=AU" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-blue-300 border border-slate-700 text-xs font-bold inline-flex items-center gap-1.5 no-underline transition-premium">
              <span class="w-2 h-2 rounded-full bg-blue-400"></span> Simulate Automobile (AU) Student
            </a>
          </div>
          <span class="text-[11px] text-slate-400 mt-1 block">Each link opens the exact student registration view with that department's course excluded.</span>
        </div>

        <!-- Sample Student Credentials Table for Incognito Testing -->
        <div class="p-3.5 rounded-xl bg-slate-950/80 border border-slate-800/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm text-cyan-400">phonelink_lock</span>
              Sample S5 Student Credentials (For Mobile / Incognito Login)
            </span>
            <span class="text-[10px] text-slate-400 font-mono">Default Password: <strong class="text-emerald-400 font-bold">carmel2026</strong></span>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 text-[11px]">
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">CE &bull; Aswathi Raj</span>
              <span class="font-mono text-cyan-400 font-bold">24CE11629</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">ME &bull; Arjun S Pillai</span>
              <span class="font-mono text-cyan-400 font-bold">24ME12500</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">CT &bull; Ahammed Yasir</span>
              <span class="font-mono text-cyan-400 font-bold">24CT4072</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">EEE &bull; Aravind K B</span>
              <span class="font-mono text-cyan-400 font-bold">24EEE12509</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">EL &bull; Abin Salichan</span>
              <span class="font-mono text-cyan-400 font-bold">24EL4073</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-900 border border-slate-800">
              <span class="text-slate-400 block font-bold">AU &bull; Dany Gomas</span>
              <span class="font-mono text-cyan-400 font-bold">24AU3966</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 3: Course Roster & Allocation Desk -->
    <div id="tab-allocation" class="tab-content hidden space-y-5">
      <!-- Allocation Actions & Controls -->
      <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 flex flex-wrap items-center justify-between gap-4 shadow-md">
        <div>
          <h2 class="text-base font-bold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-emerald-400">rule</span> Allocation &amp; Admission 2024 Class Setup Desk
          </h2>
          <p class="text-xs text-slate-400">Run algorithmic FCFS allocation, fine-tune manual overrides, and setup official Admission 2024 Open Elective Classrooms for HODs.</p>
        </div>

        <div class="flex items-center gap-2.5">
          <button onclick="handleResetTrialData()" id="btnResetTrialTab3" class="px-3.5 py-2 rounded-xl bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 font-bold text-xs flex items-center gap-1.5 transition-premium shadow-sm">
            <span class="material-symbols-rounded text-sm">restart_alt</span> Reset Trial Data
          </button>

          <button onclick="handleAutoAllocate()" id="btnAutoAllocate" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md transition-premium">
            <span class="material-symbols-rounded text-sm">auto_fix_high</span> Run FCFS Auto-Allocate
          </button>

          <button onclick="handleReleaseToHods()" id="btnReleaseToHods" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md shadow-emerald-900/40 transition-premium">
            <span class="material-symbols-rounded text-sm">send_and_archive</span> Approve &amp; Setup Classrooms for HODs
          </button>
        </div>
      </div>

      <!-- Course Tabs & Live Rosters -->
      <div class="bg-slate-900/70 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="p-3 bg-slate-950/60 border-b border-slate-800 flex gap-2 overflow-x-auto">
          @foreach($courses as $idx => $course)
            <button onclick="switchCourseRoster({{ $course->id }})" id="btn-roster-course-{{ $course->id }}" class="roster-tab-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-premium {{ $idx === 0 ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-slate-400 hover:bg-slate-800' }}">
              {{ $course->offering_branch }} — {{ $course->course_code }}
            </button>
          @endforeach
        </div>

        <div id="rosterContainer" class="p-4 sm:p-6">
          <div class="text-center py-12 text-slate-500">
            <span class="material-symbols-rounded text-3xl animate-spin mb-2">progress_activity</span>
            <p class="text-xs">Loading course pools &amp; rosters...</p>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Add / Edit Course Modal -->
  <div id="courseModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg p-6 space-y-4 shadow-2xl">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <h3 id="courseModalTitle" class="text-base font-extrabold text-white">Add / Override Course Offering</h3>
        <button onclick="closeCourseModal()" class="text-slate-400 hover:text-white material-symbols-rounded">close</button>
      </div>

      <form id="formCourse" onsubmit="handleSaveCourse(event)" class="space-y-3.5">
        <input type="hidden" id="course_id" name="id">

        <div class="grid grid-cols-2 gap-3.5">
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1">Offering Department</label>
            <select id="course_offering_branch" name="offering_branch" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">Select Branch</option>
              <option value="AU">AU — Automobile</option>
              <option value="CE">CE — Civil</option>
              <option value="CT">CT — Computer</option>
              <option value="EEE">EEE — Electrical</option>
              <option value="EL">EL — Electronics</option>
              <option value="ME">ME — Mechanical</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1">SBTE Course Code</label>
            <input type="text" id="course_code" name="course_code" placeholder="e.g. 6001" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-mono font-bold">
          </div>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-200 mb-1">Course Title</label>
          <input type="text" id="course_title" name="course_title" placeholder="e.g. Concept of IoT" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-bold">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-200 mb-1">Max Capacity (Seats)</label>
          <input type="number" id="course_max_capacity" name="max_capacity" value="60" min="10" max="120" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-mono font-bold">
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-200 mb-1">Course Description</label>
          <textarea id="course_description" name="course_description" rows="2" placeholder="Brief outline..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 leading-relaxed"></textarea>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-200 mb-1">Course Outcomes (COs)</label>
          <textarea id="course_outcomes" name="course_outcomes" rows="3" placeholder="CO1: ...&#10;CO2: ..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-mono leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3.5">
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1">Faculty In-Charge Name</label>
            <input type="text" id="course_faculty_name" name="faculty_name" placeholder="Lecturer name" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1">Faculty Mobile No</label>
            <input type="text" id="course_faculty_phone" name="faculty_phone" placeholder="10-digit mobile" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-sm text-white focus:outline-none focus:border-cyan-500 font-mono">
          </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
          <button type="button" onclick="closeCourseModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-premium">
            Cancel
          </button>
          <button type="submit" id="btnSaveCourse" class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold transition-premium shadow-md">
            Save Course
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Manual Student Reassign Modal -->
  <div id="reassignModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 space-y-4 shadow-2xl">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <h3 class="text-base font-extrabold text-white">Reassign Student Course</h3>
        <button onclick="closeReassignModal()" class="text-slate-400 hover:text-white material-symbols-rounded">close</button>
      </div>

      <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs space-y-1">
        <div>Student: <strong id="reassignStudentName" class="text-white"></strong></div>
        <div>PRN: <span id="reassignStudentPrn" class="font-mono text-cyan-400"></span> &bull; Parent Dept: <strong id="reassignParentBranch" class="text-amber-400"></strong></div>
      </div>

      <form id="formReassign" onsubmit="handleManualReassign(event)" class="space-y-3">
        <input type="hidden" id="reassign_reg_id">
        <input type="hidden" id="reassign_force" value="0">

        <div>
          <label class="block text-xs font-bold text-slate-300 mb-1">Target Course</label>
          <select id="reassign_target_course_id" required class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
            @foreach($courses as $c)
              <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch }}">
                {{ $c->offering_branch }} — {{ $c->course_code }} ({{ $c->course_title }})
              </option>
            @endforeach
          </select>
        </div>

        <div id="reassignWarning" class="hidden p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs"></div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
          <button type="button" onclick="closeReassignModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-premium">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-premium shadow-md">
            Confirm Reassignment
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let allocationData = null;
    let activeCourseId = {{ $courses->first()->id ?? 0 }};

    // Countdown Timer
    function updateCountdown() {
      const el = document.getElementById('liveCountdownWidget');
      const endStr = '{{ $settings->reg_end_at ? $settings->reg_end_at->toIso8601String() : "" }}';
      const isOpen = {{ $settings->is_registration_open ? 'true' : 'false' }};

      if (!endStr) {
        el.innerText = 'No deadline set';
        return;
      }

      const now = new Date().getTime();
      const end = new Date(endStr).getTime();
      const diff = end - now;

      if (!isOpen) {
        el.innerText = 'Registration paused';
        return;
      }
      if (diff <= 0) {
        el.innerText = 'Registration ended';
        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const secs = Math.floor((diff % (1000 * 60)) / 1000);

      el.innerText = `Closes in: ${days}d ${hours}h ${mins}m ${secs}s`;
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    function switchTab(tabId) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-cyan-500/10', 'text-cyan-400', 'border', 'border-cyan-500/30');
        btn.classList.add('text-slate-400');
      });

      document.getElementById(tabId).classList.remove('hidden');
      const activeBtn = document.getElementById('btn-' + tabId);
      activeBtn.classList.remove('text-slate-400');
      activeBtn.classList.add('bg-cyan-500/10', 'text-cyan-400', 'border', 'border-cyan-500/30');

      if (tabId === 'tab-allocation') {
        loadAllocationData();
      }
    }

    function openCourseModal() {
      document.getElementById('formCourse').reset();
      document.getElementById('course_id').value = '';
      document.getElementById('courseModalTitle').innerText = 'Add / Override Course Offering';
      document.getElementById('courseModal').classList.remove('hidden');
      document.getElementById('courseModal').classList.add('flex');
    }
    function closeCourseModal() {
      document.getElementById('courseModal').classList.add('hidden');
      document.getElementById('courseModal').classList.remove('flex');
    }

    function editCourse(c) {
      document.getElementById('course_id').value = c.id;
      document.getElementById('course_offering_branch').value = c.offering_branch;
      document.getElementById('course_code').value = c.course_code;
      document.getElementById('course_title').value = c.course_title;
      document.getElementById('course_max_capacity').value = c.max_capacity;
      document.getElementById('course_description').value = c.course_description || '';
      document.getElementById('course_outcomes').value = c.course_outcomes || '';
      document.getElementById('course_faculty_name').value = c.faculty_name || '';
      document.getElementById('course_faculty_phone').value = c.faculty_phone || '';
      document.getElementById('courseModalTitle').innerText = 'Edit Course Offering (' + c.offering_branch + ')';
      document.getElementById('courseModal').classList.remove('hidden');
      document.getElementById('courseModal').classList.add('flex');
    }

    async function handleSaveCourse(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSaveCourse');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      const formData = new FormData(document.getElementById('formCourse'));

      try {
        const res = await fetch('/admin/open-elective/courses/save', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert('Course saved successfully!');
          window.location.reload();
        } else {
          alert(data.message || 'Error saving course.');
        }
      } catch (err) {
        alert('Server communication error.');
      } finally {
        btn.disabled = false;
        btn.innerText = 'Save Course';
      }
    }

    async function deleteCourse(id, title) {
      if (!confirm(`Are you sure you want to delete course "${title}"?`)) return;

      try {
        const res = await fetch(`/admin/open-elective/courses/${id}/delete`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert('Course deleted.');
          window.location.reload();
        } else {
          alert(data.message || 'Failed to delete course.');
        }
      } catch (err) {
        alert('Server communication error.');
      }
    }

    async function handleSaveSettings(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSaveSettings');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      const formData = new FormData(document.getElementById('formSettings'));

      try {
        const res = await fetch('/admin/open-elective/settings/save', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert('Window settings saved successfully!');
          window.location.reload();
        } else {
          alert(data.message || 'Error saving settings.');
        }
      } catch (err) {
        alert('Server error.');
      } finally {
        btn.disabled = false;
        btn.innerText = 'Save Window Settings';
      }
    }

    async function handleSendPush(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSendPush');
      const feedback = document.getElementById('pushStatusFeedback');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Broadcasting...';
      feedback.classList.add('hidden');

      const formData = new FormData(document.getElementById('formPush'));

      try {
        const res = await fetch('/admin/open-elective/broadcast-notification', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });
        const data = await res.json();
        feedback.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          feedback.className = 'p-3 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold';
          feedback.innerText = data.message;
        } else {
          feedback.className = 'p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold';
          feedback.innerText = data.message || 'Push dispatch failed.';
        }
      } catch (err) {
        feedback.classList.remove('hidden');
        feedback.className = 'p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold';
        feedback.innerText = 'Communication error.';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">send</span> Broadcast Alert Now';
      }
    }

    async function loadAllocationData() {
      const container = document.getElementById('rosterContainer');
      try {
        const res = await fetch('/admin/open-elective/allocation-data');
        allocationData = await res.json();
        if (allocationData.status === 'SUCCESS') {
          renderCourseRoster(activeCourseId);
        } else {
          container.innerHTML = '<div class="text-rose-400 text-xs p-4">Failed to load allocation data.</div>';
        }
      } catch (err) {
        container.innerHTML = '<div class="text-rose-400 text-xs p-4">Error loading roster data.</div>';
      }
    }

    function switchCourseRoster(courseId) {
      activeCourseId = courseId;
      document.querySelectorAll('.roster-tab-btn').forEach(btn => {
        btn.classList.remove('bg-cyan-500/20', 'text-cyan-300', 'border', 'border-cyan-500/30');
        btn.classList.add('text-slate-400');
      });
      const currentBtn = document.getElementById('btn-roster-course-' + courseId);
      if (currentBtn) {
        currentBtn.classList.remove('text-slate-400');
        currentBtn.classList.add('bg-cyan-500/20', 'text-cyan-300', 'border', 'border-cyan-500/30');
      }
      renderCourseRoster(courseId);
    }

    function renderCourseRoster(courseId) {
      const container = document.getElementById('rosterContainer');
      if (!allocationData || !allocationData.coursePools) return;

      const pool = allocationData.coursePools[courseId];
      if (!pool) {
        container.innerHTML = '<div class="text-slate-500 text-xs p-4">Course pool not found.</div>';
        return;
      }

      const course = pool.course;
      const allotted = pool.allotted_students || [];
      const opt1 = pool.opt1_applicants || [];

      let html = `
        <div class="space-y-4">
          <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 rounded-xl bg-slate-950/80 border border-slate-800">
            <div>
              <div class="text-xs font-black text-cyan-400">${course.offering_branch} Dept &bull; ${course.course_code}</div>
              <h4 class="text-sm font-extrabold text-white">${course.course_title}</h4>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs text-slate-400">Allotted: <strong class="text-emerald-400 text-sm">${allotted.length}</strong> / ${course.max_capacity}</span>
              <span class="text-xs text-slate-400">1st Choices: <strong class="text-cyan-400 text-sm">${opt1.length}</strong></span>
              <a href="/admin/open-elective/export-roster/${course.id}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold flex items-center gap-1 no-underline transition-premium">
                <span class="material-symbols-rounded text-sm">print</span> Print Nominal Roll
              </a>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-950 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-800">
                <tr>
                  <th class="py-2.5 px-3">#</th>
                  <th class="py-2.5 px-3">Student Name</th>
                  <th class="py-2.5 px-3">SBTE PRN</th>
                  <th class="py-2.5 px-3">Parent Dept</th>
                  <th class="py-2.5 px-3">Allocation Source</th>
                  <th class="py-2.5 px-3">Choice Hierarchy</th>
                  <th class="py-2.5 px-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800/60 font-medium">
      `;

      if (allotted.length === 0) {
        html += `
          <tr>
            <td colspan="7" class="py-8 text-center text-slate-500">
              No students currently allotted to this course. Run Auto-Allocate or manually assign students.
            </td>
          </tr>
        `;
      } else {
        allotted.forEach((st, idx) => {
          let badgeColor = 'bg-blue-500/20 text-blue-300';
          if (st.allocation_source === 'option_1_fcfs') badgeColor = 'bg-emerald-500/20 text-emerald-300';
          if (st.allocation_source === 'admin_override') badgeColor = 'bg-purple-500/20 text-purple-300';

          html += `
            <tr class="hover:bg-slate-800/40 transition-premium">
              <td class="py-2 px-3 text-slate-500 font-mono">${idx + 1}</td>
              <td class="py-2 px-3 font-bold text-white">${st.student_name}</td>
              <td class="py-2 px-3 font-mono text-cyan-400">${st.sbte_reg_no}</td>
              <td class="py-2 px-3">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-slate-800 text-amber-300 border border-slate-700">
                  ${st.parent_branch}
                </span>
              </td>
              <td class="py-2 px-3">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${badgeColor}">
                  ${(st.allocation_source || 'allotted').replace(/_/g, ' ')}
                </span>
              </td>
              <td class="py-2 px-3 text-slate-400 text-[11px]">
                1: ${st.option1_course ? st.option1_course.course_code : '-'} &bull; 2: ${st.option2_course ? st.option2_course.course_code : '-'}
              </td>
              <td class="py-2 px-3 text-right">
                <button onclick='openReassignModal(${JSON.stringify(st)})' class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-[11px] font-bold transition-premium">
                  Reassign
                </button>
              </td>
            </tr>
          `;
        });
      }

      html += `
              </tbody>
            </table>
          </div>
        </div>
      `;

      container.innerHTML = html;
    }

    async function handleAutoAllocate() {
      if (!confirm('Run algorithmic FCFS allocation? This will allot students to Option 1 first, and rollover excess students to Option 2.')) return;

      const btn = document.getElementById('btnAutoAllocate');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Allocating...';

      try {
        const res = await fetch('/admin/open-elective/auto-allocate', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ preserve_overrides: true })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert(data.message);
          window.location.reload();
        } else {
          alert(data.message || 'Allocation failed.');
        }
      } catch (err) {
        alert('Communication error.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">auto_fix_high</span> Run FCFS Auto-Allocate';
      }
    }

    function openReassignModal(st) {
      document.getElementById('reassign_reg_id').value = st.id;
      document.getElementById('reassignStudentName').innerText = st.student_name;
      document.getElementById('reassignStudentPrn').innerText = st.sbte_reg_no;
      document.getElementById('reassignParentBranch').innerText = st.parent_branch;
      document.getElementById('reassign_target_course_id').value = st.allotted_course_id || activeCourseId;
      document.getElementById('reassignWarning').classList.add('hidden');
      document.getElementById('reassign_force').value = '0';
      document.getElementById('reassignModal').classList.remove('hidden');
      document.getElementById('reassignModal').classList.add('flex');
    }
    function closeReassignModal() {
      document.getElementById('reassignModal').classList.add('hidden');
      document.getElementById('reassignModal').classList.remove('flex');
    }

    async function handleManualReassign(e) {
      e.preventDefault();
      const regId = document.getElementById('reassign_reg_id').value;
      const targetCourseId = document.getElementById('reassign_target_course_id').value;
      const force = document.getElementById('reassign_force').value === '1';

      try {
        const res = await fetch('/admin/open-elective/manual-override', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            registration_id: regId,
            target_course_id: targetCourseId,
            force_override: force
          })
        });
        const data = await res.json();

        if (data.status === 'WARNING' && data.requires_confirmation) {
          const warningEl = document.getElementById('reassignWarning');
          warningEl.innerText = data.message;
          warningEl.classList.remove('hidden');
          document.getElementById('reassign_force').value = '1';
          return;
        }

        if (data.status === 'SUCCESS') {
          alert(data.message);
          closeReassignModal();
          loadAllocationData();
        } else {
          alert(data.message || 'Failed to reassign.');
        }
      } catch (err) {
        alert('Server communication error.');
      }
    }

    async function handleReleaseToHods() {
      if (!confirm('Are you ready to officially approve and setup the Admission 2024 Open Elective Classrooms for Department HODs? This will create the virtual class theory batches and notify students.')) return;

      const btn = document.getElementById('btnReleaseToHods');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Setting up Classrooms...';

      try {
        const res = await fetch('/admin/open-elective/release-to-hods', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert(data.message);
          window.location.reload();
        } else {
          alert(data.message || 'Failed to setup classrooms.');
        }
      } catch (err) {
        alert('Server communication error.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">send_and_archive</span> Approve &amp; Setup Classrooms for HODs';
      }
    }

    async function handleResetTrialData() {
      const confirmText = prompt('WARNING: This will reset all trial student registrations and test classroom allocations back to 0 so you have a clean slate. Course offerings and syllabi uploaded by HODs will NOT be deleted.\n\nType "RESET" to confirm:');
      if (confirmText !== 'RESET') {
        if (confirmText !== null) alert('Reset cancelled. You must type "RESET" in capital letters to confirm.');
        return;
      }

      const btn1 = document.getElementById('btnResetTrialData');
      const btn2 = document.getElementById('btnResetTrialTab3');
      if (btn1) {
        btn1.disabled = true;
        btn1.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Resetting...';
      }
      if (btn2) {
        btn2.disabled = true;
        btn2.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Resetting...';
      }

      try {
        const res = await fetch('/admin/open-elective/reset-trial-data', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert(data.message);
          window.location.reload();
        } else {
          alert(data.message || 'Failed to reset trial data.');
        }
      } catch (err) {
        alert('Server communication error.');
      } finally {
        if (btn1) {
          btn1.disabled = false;
          btn1.innerHTML = '<span class="material-symbols-rounded text-sm">restart_alt</span> Reset All Trial Registrations';
        }
        if (btn2) {
          btn2.disabled = false;
          btn2.innerHTML = '<span class="material-symbols-rounded text-sm">restart_alt</span> Reset Trial Data';
        }
      }
    }
  </script>
</body>
</html>
