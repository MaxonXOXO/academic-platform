<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Class Log & Attendance | Carmel Linx</title>
  
  <!-- Google Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.3);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(99, 102, 241, 0.3);
      border-radius: 99px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(99, 102, 241, 0.5);
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col antialiased pb-12">

  @php
    $role = session('userRole');
    $defaultBackLink = '/dashboard/lecturer';
    if ($role === 'HOD') $defaultBackLink = '/dashboard/hod';
    if ($role === 'Demonstrator') $defaultBackLink = '/dashboard/demonstrator';
    if ($role === 'Trade_Instructor') $defaultBackLink = '/dashboard/tradeinstructor';
    if ($role === 'Workshop_Superintendent') $defaultBackLink = '/dashboard/workshop';
    if ($role === 'Tutor') $defaultBackLink = '/dashboard/tutor';
    if ($role === 'General_Coordinator_SF') $defaultBackLink = '/dashboard/general-coordinator-sf';
    if ($role === 'General_Coordinator_Aided') $defaultBackLink = '/dashboard/general-coordinator-aided';
    if ($role === 'Principal') $defaultBackLink = '/dashboard/principal';

    $backLink = $defaultBackLink;
    $rawReturnTo = request('return_to');
    if (!empty($rawReturnTo)) {
      // Validate that return_to is a safe relative path starting with '/'
      if (str_starts_with($rawReturnTo, '/') && !str_starts_with($rawReturnTo, '//') && !str_starts_with($rawReturnTo, '/\\')) {
        $backLink = $rawReturnTo;
      }
    } elseif (request('from') === 'formative' && request()->has('subject_id')) {
      $sid = request('subject_id');
      $tab = request('tab', 'lab_evaluation');
      $backLink = $defaultBackLink . '?subject_id=' . $sid . '&tab=' . $tab;
    }
  @endphp

  <!-- Top Navigation Header -->
  <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-30 px-4 sm:px-6 py-2.5 shadow-md">
    <div class="max-w-xl lg:max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <a id="attendanceLogBackBtn" href="{{ $backLink }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition-colors flex items-center justify-center shadow-sm cursor-pointer no-underline" title="Back">
          <span class="material-symbols-rounded text-base">arrow_back</span>
        </a>
        <div>
          <div class="flex items-center gap-2">
            <h1 class="font-extrabold text-white text-sm sm:text-base tracking-tight leading-none">Class Log & Attendance</h1>
            <span class="hidden sm:inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-wide">Staff Desktop</span>
          </div>
          <p class="text-[11px] text-slate-400 font-medium leading-none mt-1">Record class syllabus topics covered and student attendance.</p>
        </div>
      </div>
      <div class="flex items-center gap-2.5">
        <div class="hidden md:flex flex-col text-right">
          <span class="text-xs font-bold text-slate-200 leading-none">{{ session('userName', 'Faculty Staff') }}</span>
          <span class="text-[10px] text-slate-400 font-medium leading-none mt-1">{{ session('userRole', 'Staff') }}</span>
        </div>
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-black rounded-lg w-8 h-8 flex items-center justify-center shadow text-xs">CL</div>
      </div>
    </div>
  </header>

  <!-- Notification Banner -->
  <div id="globalAlert" class="hidden max-w-xl lg:max-w-7xl mx-auto mt-2 px-4 py-2 rounded-lg text-xs font-bold text-center border shadow-md animate-pulse"></div>

  <!-- Main Container -->
  <main class="max-w-xl lg:max-w-7xl mx-auto w-full px-3 sm:px-4 lg:px-6 mt-3 lg:mt-4 flex-grow">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

      <!-- LEFT COLUMN: Class Log Setup Panel (lg:col-span-5) -->
      <div class="space-y-3 lg:col-span-5">

        <div class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 shadow-lg space-y-3">
          
          <!-- Card Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-rounded text-indigo-400 text-base">school</span>
              <h2 class="font-bold text-xs text-slate-200 uppercase tracking-wider">Class & Session Log</h2>
            </div>
            <div>
              <span id="logNextSlNoPointer" class="inline-block px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 rounded-full text-[10px] font-mono font-bold text-emerald-400">Next Entry: #1</span>
            </div>
          </div>

          <!-- Active Edit Mode Banner -->
          <div id="attEditingBanner" class="hidden p-2.5 bg-sky-500/15 border border-sky-500/40 rounded-xl flex items-center justify-between gap-2 shadow-sm">
            <div class="flex items-center gap-2 min-w-0">
              <span class="material-symbols-rounded text-sky-400 text-xl shrink-0">edit_note</span>
              <div class="min-w-0">
                <div class="text-xs font-bold text-sky-200 truncate flex items-center gap-1.5">
                  <span>Editing <strong id="editBannerSlNo" class="text-white font-mono">Log #1</strong></span>
                  <span class="text-sky-400">•</span>
                  <span id="editBannerDate" class="text-slate-200"></span>
                  <span class="text-sky-400">•</span>
                  <span id="editBannerPeriods" class="text-sky-300"></span>
                </div>
                <div class="text-[10px] text-sky-300/80 truncate mt-0.5" id="editBannerTopic"></div>
              </div>
            </div>
            <button type="button" onclick="cancelDesktopEditingLog()" class="shrink-0 px-2.5 py-1 text-[11px] font-bold rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-600 transition cursor-pointer" title="Cancel editing and revert to new entry">
              Cancel Edit
            </button>
          </div>

          <!-- Subject Selector -->
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Class Subject / Batch</label>
            <select id="subjectSelect" onchange="onSubjectChange()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-2 text-xs text-slate-200 outline-none focus:border-indigo-500 cursor-pointer transition">
              <option value="" disabled selected>-- Choose Subject --</option>
            </select>
          </div>

          <!-- Sub-Batch Selector (Labs Only, Compact) -->
          <div id="subBatchCard" class="hidden bg-slate-900/60 border border-slate-800/80 rounded-lg p-2 space-y-1">
            <div class="flex items-center justify-between">
              <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Lab Sub-Batch</label>
              <button type="button" onclick="openLabBatchSetupModalFromAttendance()" class="text-[10px] font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1 transition" title="Configure Lab Batch Division (Full vs Split & Student Cutoff)">
                <span class="material-symbols-rounded text-xs">tune</span> Batch split setup
              </button>
            </div>
            <div class="grid grid-cols-3 gap-1.5">
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="Whole" checked onchange="filterStudentsByBatch()" class="sr-only peer">
                <div class="py-1 text-center rounded-md border border-slate-700 bg-slate-900 text-xs font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
                  Whole Class
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="1" onchange="filterStudentsByBatch()" class="sr-only peer">
                <div id="batch1Text" class="py-1 text-center rounded-md border border-slate-700 bg-slate-900 text-xs font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
                  Batch 1
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="2" onchange="filterStudentsByBatch()" class="sr-only peer">
                <div id="batch2Text" class="py-1 text-center rounded-md border border-slate-700 bg-slate-900 text-xs font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
                  Batch 2
                </div>
              </label>
            </div>
          </div>

          <!-- Details Section: Date, Periods, Syllabus, Topics -->
          <div id="classLogCard" class="space-y-2.5 pt-1 border-t border-slate-800/60">
            
            <!-- Date & Periods -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1">Date</label>
                <input type="date" id="logDate" onchange="checkExistingAttendance()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 outline-none focus:border-indigo-500 transition" value="{{ date('Y-m-d') }}">
              </div>
              <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1">Period / Hour</label>
                <!-- Lab Timetable Continuous Presets -->
                <div id="labPeriodPresets" class="hidden flex flex-wrap items-center gap-1 mb-1">
                  <button type="button" onclick="selectPeriodPreset([1,2,3])" class="px-1.5 py-0.5 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 rounded text-[10px] font-bold cursor-pointer transition">
                    P1–P3
                  </button>
                  <button type="button" onclick="selectPeriodPreset([4,5,6])" class="px-1.5 py-0.5 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 rounded text-[10px] font-bold cursor-pointer transition">
                    P4–P6
                  </button>
                  <button type="button" onclick="selectPeriodPreset([1,2])" class="px-1.5 py-0.5 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded text-[10px] font-bold cursor-pointer transition">
                    P1–P2
                  </button>
                </div>
                <div class="flex flex-wrap gap-1">
                  @for ($p = 1; $p <= 7; $p++)
                    <label class="cursor-pointer">
                      <input type="checkbox" name="logPeriods" value="{{ $p }}" onchange="checkExistingAttendance()" class="sr-only peer">
                      <div class="px-2 py-1 rounded-md border border-slate-700 bg-slate-900 text-xs font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
                        P{{ $p }}
                      </div>
                    </label>
                  @endfor
                </div>
              </div>
            </div>

            <!-- Auto Session Attendance Linked Notice -->
            <div id="existingSessionNotice" class="hidden p-2 bg-amber-500/10 border border-amber-500/30 rounded-lg text-amber-300 text-[11px] flex items-start gap-1.5">
              <span class="material-symbols-rounded text-sm text-amber-400 shrink-0 mt-0.5">sync_saved_locally</span>
              <div class="space-y-0.5 leading-tight">
                <strong class="font-bold block text-amber-200 text-[11px]">Session Attendance Linked</strong>
                <p class="text-[10px] text-amber-300/90 leading-normal" id="existingSessionNoticeText"></p>
              </div>
            </div>

            <div class="relative">
              <div class="flex items-center justify-between mb-1">
                <label class="block text-[11px] font-bold text-slate-400">Subject Log</label>
                <span id="selectedExpDesktopCount" class="text-[10px] font-mono font-bold text-indigo-400">0 Selected</span>
              </div>
              
              <!-- Dropdown Trigger Button -->
              <button type="button" id="expDropdownToggleBtn" onclick="toggleExpDropdown()" class="w-full flex items-center justify-between bg-slate-900 border border-slate-700 hover:border-slate-600 rounded-lg px-2.5 py-2 text-xs text-slate-200 outline-none focus:border-indigo-500 cursor-pointer transition">
                <span id="expDropdownToggleText" class="truncate text-slate-400">-- Choose Topic / Experiment (or Manual Entry below) --</span>
                <span class="material-symbols-rounded text-base text-slate-400 shrink-0 ml-1 transition-transform" id="expDropdownArrow">expand_more</span>
              </button>

              <!-- Dropdown Menu / Checkbox List -->
              <div id="expDropdownMenu" class="hidden absolute left-0 right-0 top-full mt-1 z-50 bg-slate-950 border border-slate-700/90 rounded-xl shadow-2xl p-2 space-y-1.5 backdrop-blur-md">
                <div class="px-1 pt-0.5">
                  <input type="text" id="expSearchDesktopInput" oninput="filterDesktopExpList()" placeholder="Search topic or experiment..." class="w-full bg-slate-900 border border-slate-700 rounded-md px-2 py-1 text-xs text-slate-200 placeholder-slate-500 outline-none focus:border-indigo-500">
                </div>
                <div id="desktopExpCheckboxContainer" class="max-h-56 overflow-y-auto custom-scrollbar space-y-1 p-0.5">
                  <div class="text-center py-3 text-slate-500 text-xs font-mono">Select a class subject first</div>
                </div>
                <div class="pt-1.5 border-t border-slate-800/80 flex items-center justify-between px-1">
                  <button type="button" onclick="clearSelectedExperiments()" class="text-[10px] font-bold text-slate-400 hover:text-rose-400 transition cursor-pointer">Clear All</button>
                  <button type="button" onclick="closeExpDropdown()" class="text-[10px] font-bold px-2 py-0.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded cursor-pointer transition">Done</button>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-[11px] font-bold text-slate-400 mb-1">Topics Covered (Editable)</label>
              <textarea id="topicsCovered" rows="2" placeholder="Describe the topics covered in class today..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 outline-none focus:border-indigo-500 resize-none transition"></textarea>
            </div>

          </div>

        </div>

      </div>

      <!-- RIGHT COLUMN: Attendance Workspace (lg:col-span-7) -->
      <div class="space-y-3 lg:col-span-7">

        <!-- ATTENDANCE ENTRY PANEL -->
        <div id="attendanceCard" class="bg-slate-950 border border-slate-800 rounded-xl p-3.5 shadow-lg space-y-3">
          
          <!-- Attendance Header -->
          <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-slate-800/60">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-indigo-400 text-lg">fact_check</span>
              <div>
                <h2 class="font-bold text-xs text-slate-200 uppercase tracking-wider">Attendance Panel</h2>
                <div id="studentCountLabel" class="text-[11px] text-slate-400 font-medium">Select subject to load students</div>
              </div>
            </div>
            
            <div class="flex items-center gap-2">
              <!-- View Mode Switch -->
              <div class="flex bg-slate-900 border border-slate-800 rounded-lg p-0.5">
                <button type="button" onclick="switchMode('list')" id="btnModeList" class="px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-600 text-white transition-all cursor-pointer">List</button>
                <button type="button" onclick="switchMode('grid')" id="btnModeGrid" class="px-2.5 py-1 text-xs font-bold rounded-md text-slate-400 hover:text-slate-200 transition-all cursor-pointer">Grid</button>
              </div>

              <!-- Mark All Toggle -->
              <button type="button" onclick="toggleAllCheckboxes()" id="btnCheckAll" class="px-2.5 py-1 text-xs font-bold rounded-md bg-slate-900 border border-slate-700 text-indigo-400 hover:text-indigo-300 hover:bg-slate-800 transition-all cursor-pointer">Mark All Absent</button>

              <!-- Quick Save Button for Desktop in Header -->
              <button type="button" id="btnQuickSave" onclick="saveAttendanceAndLog()" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white rounded-md font-bold text-xs flex items-center gap-1 shadow cursor-pointer transition">
                <span class="material-symbols-rounded text-sm">save</span> Save
              </button>
            </div>
          </div>

          <!-- MODE 1: LIST VIEW -->
          <div id="attendanceModeList" class="space-y-2">
            <!-- Compact, Roomy Table on Desktop (max-h-[380px] xl:max-h-[440px]) -->
            <div class="max-h-[380px] xl:max-h-[440px] overflow-y-auto custom-scrollbar border border-slate-800/80 rounded-lg bg-slate-900/30">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="bg-slate-900/90 backdrop-blur-sm text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[10px] font-black sticky top-0 z-10">
                    <th class="p-2 w-14 text-center">Roll No</th>
                    <th class="p-2">Student Name</th>
                    <th class="p-2 w-16 text-center">Present</th>
                  </tr>
                </thead>
                <tbody id="studentListContainer" class="divide-y divide-slate-800/40">
                  <tr>
                    <td colspan="3" class="py-12 text-center text-slate-400 font-medium">
                      <div class="flex flex-col items-center justify-center gap-2">
                        <span class="material-symbols-rounded text-3xl text-indigo-400/60">touch_app</span>
                        <span class="text-xs font-bold text-slate-300">No Subject Selected</span>
                        <span class="text-[11px] text-slate-500">Choose a class subject from the left panel to load students.</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- MODE 2: GRID VIEW (Roll numbers only) -->
          <div id="attendanceModeGrid" class="hidden space-y-2">
            <div class="flex justify-between items-center px-1">
              <p class="text-[11px] text-slate-400">Tap to toggle <strong class="text-rose-400">Absent</strong> / <strong class="text-emerald-400">Present</strong>.</p>
              <button type="button" onclick="toggleAllGrid(true)" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 cursor-pointer">Reset All Present</button>
            </div>

            <!-- Responsive Grid: 5 cols on mobile, 8-10 cols on desktop for zero-scroll matrix -->
            <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-8 xl:grid-cols-10 gap-1.5 p-1 max-h-[380px] xl:max-h-[440px] overflow-y-auto custom-scrollbar" id="studentGridContainer">
              <div class="col-span-full py-12 text-center text-slate-400 font-medium">
                <div class="flex flex-col items-center justify-center gap-2">
                  <span class="material-symbols-rounded text-3xl text-indigo-400/60">touch_app</span>
                  <span class="text-xs font-bold text-slate-300">No Subject Selected</span>
                  <span class="text-[11px] text-slate-500">Choose a class subject from the left panel to load students.</span>
                </div>
              </div>
            </div>
          </div>

          <!-- BOTTOM ACTION BAR -->
          <div class="pt-2 border-t border-slate-800/60">
            <button type="button" id="btnBottomSave" onclick="saveAttendanceAndLog()" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.99] text-white rounded-lg font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg transition-all cursor-pointer">
              <span class="material-symbols-rounded text-base">check_circle</span> Save Log & Attendance
            </button>
          </div>

        </div>

      </div>

    </div>

    <!-- PAST ATTENDANCE LOGS & VERIFICATION TABLE -->
    <div id="desktopPastLogsSection" class="mt-6 bg-slate-950 border border-slate-800 rounded-xl p-4 shadow-lg hidden">
      <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-800/80 mb-3">
        <div class="flex items-center gap-2.5">
          <span class="material-symbols-rounded text-indigo-400 text-xl">history_edu</span>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="font-bold text-xs sm:text-sm text-slate-200 uppercase tracking-wider">Recorded Class Attendance Logs</h3>
              <span id="pastLogsCountBadge" class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">0 Records</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-0.5">Verify, edit, or delete previously recorded attendance logs directly without switching back and forth to Reports.</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button type="button" onclick="loadDesktopPastLogs()" class="px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-900 hover:bg-slate-800 border border-slate-700 rounded-lg flex items-center gap-1.5 transition cursor-pointer" title="Refresh recorded logs">
            <span class="material-symbols-rounded text-sm">refresh</span> Refresh Logs
          </button>
        </div>
      </div>

      <div id="desktopPastLogsTableContainer" class="overflow-x-auto">
        <div class="text-center py-8 text-slate-500 text-xs font-mono">Select a class subject to view recorded logs.</div>
      </div>
    </div>

  </main>

  <!-- Javascript Logic -->
  <script>
    let activeMode = 'list'; // 'list' or 'grid'
    let currentStudents = [];
    let classroomId = '';
    let isAllChecked = true;
    window.attPastLogsCache = [];
    window.attEditingLog = null;
    window.attEditingLogIds = null;
    window.pendingEditLogIds = null;

    document.addEventListener('DOMContentLoaded', () => {
      // Store return_to if passed in URL query parameter, or restore from sessionStorage if navigating within log
      const urlParams = new URLSearchParams(window.location.search);
      const returnToParam = urlParams.get('return_to');
      if (returnToParam && returnToParam.startsWith('/') && !returnToParam.startsWith('//') && !returnToParam.startsWith('/\\')) {
        sessionStorage.setItem('attendance_log_return_url', returnToParam);
        const backBtn = document.getElementById('attendanceLogBackBtn');
        if (backBtn) backBtn.href = returnToParam;
      } else if (!returnToParam) {
        // If no return_to was passed (e.g. from sidebar), clear any stale return url
        sessionStorage.removeItem('attendance_log_return_url');
      }

      const editLogIdsParam = urlParams.get('edit_log_ids');
      if (editLogIdsParam) {
        window.pendingEditLogIds = editLogIdsParam;
      }

      loadSubjects();
    });

    function showMessage(msg, isError = false) {
      const banner = document.getElementById('globalAlert');
      if (!banner) return;
      banner.classList.remove('hidden');
      if (isError) {
        banner.className = "max-w-xl lg:max-w-7xl mx-auto mt-4 px-4 py-3 rounded-xl text-sm font-bold text-center border bg-red-950/50 text-red-400 border-red-900 block shadow-md animate-pulse";
      } else {
        banner.className = "max-w-xl lg:max-w-7xl mx-auto mt-4 px-4 py-3 rounded-xl text-sm font-bold text-center border bg-emerald-950/50 text-emerald-400 border-emerald-900 block shadow-md animate-pulse";
      }
      banner.innerText = msg;
      window.scrollTo({ top: 0, behavior: 'smooth' });
      setTimeout(() => banner.classList.add('hidden'), 5000);
    }

    function loadSubjects() {
      fetch('/api/staff/attendance/subjects')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const select = document.getElementById('subjectSelect');
            data.subjects.forEach(sub => {
              const opt = document.createElement('option');
              opt.value = sub.id;
              opt.innerText = `${sub.classroom_id} - ${sub.subject_name} (${sub.subject_code})`;
              select.appendChild(opt);
            });

            // Automatically select subject if provided via URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const targetSubjectId = urlParams.get('subject_id');
            if (targetSubjectId && data.subjects.some(s => String(s.id) === String(targetSubjectId))) {
              select.value = targetSubjectId;
              onSubjectChange();
            }
          } else {
            showMessage(data.message || "Failed to load subjects", true);
          }
        })
        .catch(err => {
          console.error("Error loading subjects:", err);
          showMessage("Failed to connect to server.", true);
        });
    }

     function onSubjectChange() {
      const subjectId = document.getElementById('subjectSelect').value;

      if (!subjectId) {
        document.getElementById('subBatchCard').classList.add('hidden');
        document.getElementById('desktopPastLogsSection')?.classList.add('hidden');
        window.attPastLogsCache = [];
        cancelDesktopEditingLog();
        const container = document.getElementById('studentListContainer');
        if (container) container.innerHTML = '<tr><td colspan="3" class="py-12 text-center text-slate-400 font-medium"><div class="flex flex-col items-center justify-center gap-2"><span class="material-symbols-rounded text-3xl text-indigo-400/60">touch_app</span><span class="text-xs font-bold text-slate-300">No Subject Selected</span><span class="text-[11px] text-slate-500">Choose a class subject from the left panel to load students.</span></div></td></tr>';
        const gridContainer = document.getElementById('studentGridContainer');
        if (gridContainer) gridContainer.innerHTML = '<div class="col-span-full py-12 text-center text-slate-400 font-medium"><div class="flex flex-col items-center justify-center gap-2"><span class="material-symbols-rounded text-3xl text-indigo-400/60">touch_app</span><span class="text-xs font-bold text-slate-300">No Subject Selected</span><span class="text-[11px] text-slate-500">Choose a class subject from the left panel to load students.</span></div></div>';
        const countLabel = document.getElementById('studentCountLabel');
        if (countLabel) countLabel.innerText = 'Select subject to load students';
        return;
      }

      // Reset any active editing log session when changing subject
      cancelDesktopEditingLog();

      // Immediate UI reset to prevent data bleeding between subjects
      const resetPointer = document.getElementById('logNextSlNoPointer');
      if (resetPointer) resetPointer.innerText = 'Next Entry: #0';
      const resetTopics = document.getElementById('topicsCovered');
      if (resetTopics) resetTopics.value = '';
      window.selectedDesktopExpIds = [];
      window.selectedDesktopLpIds = [];
      const btnText = document.getElementById('expDropdownToggleText');
      if (btnText) {
        btnText.innerText = '-- Choose Topic / Experiment (or Manual Entry below) --';
        btnText.className = 'truncate text-slate-400';
      }
      const countLabel = document.getElementById('selectedExpDesktopCount');
      if (countLabel) countLabel.innerText = '0 Selected';
      const container = document.getElementById('desktopExpCheckboxContainer');
      if (container) container.innerHTML = '<div class="text-center py-3 text-slate-500 text-xs font-mono">Loading...</div>';
      document.getElementById('classLogCard').classList.remove('hidden');
      document.getElementById('attendanceCard').classList.remove('hidden');

      fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentStudents = data.students;
            classroomId = data.classroom_id;

            // Check if Lab or Practical
            const hasExperiments = (data.experiments && data.experiments.length > 0);
            const isLab = hasExperiments || (data.subject_type && (
              data.subject_type.toLowerCase().includes('lab') ||
              data.subject_type.toLowerCase().includes('practical') ||
              data.subject_type.toLowerCase().includes('practicum') ||
              data.subject_type.toLowerCase().includes('drawing') ||
              data.subject_type.toLowerCase().includes('workshop')
            ));
            window.isCurrentDesktopLab = isLab;
            window.desktopSubjectExperiments = data.experiments || [];
            window.desktopSubjectLessonPlans = data.lesson_plans || [];
            window.selectedDesktopExpIds = [];
            window.selectedDesktopLpIds = [];

            const subBatchCard = document.getElementById('subBatchCard');
            const labPresets = document.getElementById('labPeriodPresets');
            if (labPresets) {
              if (isLab) labPresets.classList.remove('hidden');
              else labPresets.classList.add('hidden');
            }

            if (isLab) {
              subBatchCard.classList.remove('hidden');
              const bSummary = data.batch_split_summary;
              if (data.lab_batch_mode === 'full') {
                document.getElementById('batch1Text').innerText = 'Batch 1';
                document.getElementById('batch2Text').innerText = 'Batch 2';
                const wholeRadio = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
                if (wholeRadio) wholeRadio.checked = true;
              } else if (bSummary && bSummary.b1_range && bSummary.b2_range) {
                document.getElementById('batch1Text').innerText = `Batch 1 (${bSummary.b1_range})`;
                document.getElementById('batch2Text').innerText = `Batch 2 (${bSummary.b2_range})`;
              } else {
                const half = Math.ceil(currentStudents.length / 2);
                document.getElementById('batch1Text').innerText = `Batch 1 (1-${half})`;
                document.getElementById('batch2Text').innerText = `Batch 2 (${half + 1}+)`;
              }
            } else {
              subBatchCard.classList.add('hidden');
              const wholeRadio = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
              if (wholeRadio) wholeRadio.checked = true;
            }

            // Load student count & serial number tracking
            const nextSlNo = typeof data.next_log_sl_no !== 'undefined' ? data.next_log_sl_no : 0;
            const nextPointer = document.getElementById('logNextSlNoPointer');
            if (nextPointer) nextPointer.innerText = `Next Entry: #${nextSlNo}`;

            const filtered = getFilteredStudents();
            document.getElementById('studentCountLabel').innerText = `Total Students: ${filtered.length}`;

            // Reset present state (all present by default)
            currentStudents.forEach(s => s.present = true);
            isAllChecked = true;
            const btnCheckAll = document.getElementById('btnCheckAll');
            if (btnCheckAll) btnCheckAll.innerText = "Mark All Absent";

            // Populate dropdown based on practical/virtual lab vs theory
            const searchInput = document.getElementById('expSearchDesktopInput');
            if (isLab) {
              if (searchInput) searchInput.placeholder = "Search experiment name or number...";
              renderDesktopExpCheckboxes();
            } else {
              if (searchInput) searchInput.placeholder = "Search lesson plan topic or CO...";
              renderDesktopLpCheckboxes();
            }

            // Reset topics textarea
            document.getElementById('topicsCovered').value = '';

            // Check if attendance already recorded for this slot
            checkExistingAttendance();

            // Render views
            renderList();
            renderGrid();

            // Load and display recorded past attendance logs table below the form
            document.getElementById('desktopPastLogsSection')?.classList.remove('hidden');
            loadDesktopPastLogs();
          } else {
            showMessage(data.message || "Failed to load subject details", true);
          }
        });
    }

    function toggleExpDropdown() {
      const menu = document.getElementById('expDropdownMenu');
      const arrow = document.getElementById('expDropdownArrow');
      if (menu) {
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
          menu.classList.remove('hidden');
          if (arrow) arrow.style.transform = 'rotate(180deg)';
          const search = document.getElementById('expSearchDesktopInput');
          if (search) {
            search.value = '';
            filterDesktopExpList();
            search.focus();
          }
        } else {
          closeExpDropdown();
        }
      }
    }

    function closeExpDropdown() {
      const menu = document.getElementById('expDropdownMenu');
      const arrow = document.getElementById('expDropdownArrow');
      if (menu) menu.classList.add('hidden');
      if (arrow) arrow.style.transform = 'rotate(0deg)';
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
      const btn = document.getElementById('expDropdownToggleBtn');
      const menu = document.getElementById('expDropdownMenu');
      if (menu && !menu.classList.contains('hidden')) {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
          closeExpDropdown();
        }
      }
    });

    function renderDesktopExpCheckboxes() {
      const container = document.getElementById('desktopExpCheckboxContainer');
      if (!container) return;

      const exps = window.desktopSubjectExperiments || [];
      if (exps.length === 0) {
        container.innerHTML = '<div class="text-center py-3 text-slate-500 text-xs font-mono">No practical experiments defined for this subject.</div>';
        updateDesktopExpSelectedDisplay();
        return;
      }

      let html = '';
      exps.forEach(exp => {
        const isChecked = (window.selectedDesktopExpIds || []).includes(exp.id);
        const expNo = exp.experiment_no;
        const fullTopic = `Exp ${expNo}: ${exp.title}`;
        const searchTerms = `${expNo} ${exp.title} ${exp.co_tag || ''}`.toLowerCase();

        html += `
        <label class="desktop-exp-row flex items-start gap-2 p-1.5 rounded-lg border border-slate-800/80 hover:border-slate-700 bg-slate-900/60 hover:bg-slate-800/80 cursor-pointer select-none transition ${isChecked ? 'bg-indigo-950/40 border-indigo-500/50' : ''}" data-search="${searchTerms}">
          <input type="checkbox" value="${exp.id}" ${isChecked ? 'checked' : ''} onchange="onDesktopExpCheckboxChange(this)" class="mt-0.5 w-3.5 h-3.5 rounded border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 cursor-pointer">
          <div class="flex-1 min-w-0 leading-tight">
            <div class="flex items-center justify-between gap-1 mb-0.5">
              <span class="text-[10px] font-mono font-black text-indigo-300">Exp ${expNo}</span>
              ${exp.co_tag ? `<span class="text-[9px] font-mono text-slate-400 bg-slate-800 px-1 py-0.2 rounded">${exp.co_tag}</span>` : ''}
            </div>
            <div class="text-[11px] font-medium text-slate-200 truncate" title="${fullTopic}">${exp.title}</div>
          </div>
        </label>`;
      });

      container.innerHTML = html;
      updateDesktopExpSelectedDisplay();
    }

    function onDesktopExpCheckboxChange(checkbox) {
      const expId = parseInt(checkbox.value);
      if (!window.selectedDesktopExpIds) window.selectedDesktopExpIds = [];

      if (checkbox.checked) {
        if (!window.selectedDesktopExpIds.includes(expId)) {
          window.selectedDesktopExpIds.push(expId);
        }
      } else {
        window.selectedDesktopExpIds = window.selectedDesktopExpIds.filter(id => id !== expId);
      }

      const label = checkbox.closest('label');
      if (label) {
        if (checkbox.checked) {
          label.classList.add('bg-indigo-950/40', 'border-indigo-500/50');
        } else {
          label.classList.remove('bg-indigo-950/40', 'border-indigo-500/50');
        }
      }

      updateDesktopExpSelectedDisplay();
      syncDesktopSelectedExpsToTopics();
    }

    function updateDesktopExpSelectedDisplay() {
      const selected = window.selectedDesktopExpIds || [];
      const countLabel = document.getElementById('selectedExpDesktopCount');
      if (countLabel) countLabel.innerText = `${selected.length} Selected`;

      const btnText = document.getElementById('expDropdownToggleText');
      if (btnText) {
        if (selected.length === 0) {
          btnText.innerText = '-- Choose Experiments (or Manual Entry below) --';
          btnText.className = 'truncate text-slate-400';
        } else if (selected.length === 1) {
          const exp = (window.desktopSubjectExperiments || []).find(e => e.id === selected[0]);
          btnText.innerText = exp ? `Exp ${exp.experiment_no}: ${exp.title}` : `1 Experiment Selected`;
          btnText.className = 'truncate text-indigo-300 font-bold';
        } else {
          const expNos = selected.map(id => {
            const exp = (window.desktopSubjectExperiments || []).find(e => e.id === id);
            return exp ? `Exp ${exp.experiment_no}` : '';
          }).filter(Boolean);
          btnText.innerText = `${selected.length} Experiments (${expNos.join(', ')})`;
          btnText.className = 'truncate text-indigo-300 font-bold';
        }
      }
    }

    function syncDesktopSelectedExpsToTopics() {
      const selected = window.selectedDesktopExpIds || [];
      const exps = window.desktopSubjectExperiments || [];
      const topicsElem = document.getElementById('topicsCovered');
      if (!topicsElem) return;

      if (selected.length === 0) {
        topicsElem.value = '';
        topicsElem.placeholder = 'Describe the topics covered in class today...';
        return;
      }

      const list = selected.map(id => {
        const exp = exps.find(e => e.id === id);
        return exp ? `Exp ${exp.experiment_no}: ${exp.title}` : '';
      }).filter(Boolean);

      topicsElem.value = list.join(' & ');
    }

    function renderDesktopLpCheckboxes() {
      const container = document.getElementById('desktopExpCheckboxContainer');
      if (!container) return;

      const lps = window.desktopSubjectLessonPlans || [];
      if (lps.length === 0) {
        container.innerHTML = '<div class="text-center py-3 text-slate-500 text-xs font-mono">No lesson plan topics defined for this subject.</div>';
        updateDesktopLpSelectedDisplay();
        return;
      }

      let html = '';
      lps.forEach((lp, idx) => {
        const isChecked = (window.selectedDesktopLpIds || []).includes(lp.id);
        const lpNo = idx + 1;
        const searchTerms = `${lpNo} ${lp.topic_content || ''} ${lp.co_id || ''} ${lp.status || ''}`.toLowerCase();
        const cleanTopic = (lp.topic_content || '').replace(/"/g, '&quot;');

        html += `
        <label class="desktop-exp-row flex items-start gap-2 p-1.5 rounded-lg border border-slate-800/80 hover:border-slate-700 bg-slate-900/60 hover:bg-slate-800/80 cursor-pointer select-none transition ${isChecked ? 'bg-indigo-950/40 border-indigo-500/50' : ''}" data-search="${searchTerms}">
          <input type="checkbox" value="${lp.id}" ${isChecked ? 'checked' : ''} onchange="onDesktopLpCheckboxChange(this)" class="mt-0.5 w-3.5 h-3.5 rounded border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 cursor-pointer">
          <div class="flex-1 min-w-0 leading-tight">
            <div class="flex items-center justify-between gap-1 mb-0.5">
              <span class="text-[10px] font-mono font-black text-indigo-300">#${lpNo}</span>
              <div class="flex items-center gap-1">
                ${lp.co_id ? `<span class="text-[9px] font-mono text-slate-400 bg-slate-800 px-1 py-0.2 rounded">${lp.co_id}</span>` : ''}
                ${lp.status ? `<span class="text-[9px] font-mono ${lp.status === 'Completed' ? 'text-emerald-400 bg-emerald-950/50' : 'text-slate-400 bg-slate-800'} px-1 py-0.2 rounded">${lp.status}</span>` : ''}
              </div>
            </div>
            <div class="text-[11px] font-medium text-slate-200 line-clamp-2" title="${cleanTopic}">${lp.topic_content || ''}</div>
          </div>
        </label>`;
      });

      container.innerHTML = html;
      updateDesktopLpSelectedDisplay();
    }

    function onDesktopLpCheckboxChange(checkbox) {
      const lpId = parseInt(checkbox.value);
      if (!window.selectedDesktopLpIds) window.selectedDesktopLpIds = [];

      if (checkbox.checked) {
        if (!window.selectedDesktopLpIds.includes(lpId)) {
          window.selectedDesktopLpIds.push(lpId);
        }
      } else {
        window.selectedDesktopLpIds = window.selectedDesktopLpIds.filter(id => id !== lpId);
      }

      const label = checkbox.closest('label');
      if (label) {
        if (checkbox.checked) {
          label.classList.add('bg-indigo-950/40', 'border-indigo-500/50');
        } else {
          label.classList.remove('bg-indigo-950/40', 'border-indigo-500/50');
        }
      }

      updateDesktopLpSelectedDisplay();
      syncDesktopSelectedLpsToTopics();
    }

    function updateDesktopLpSelectedDisplay() {
      const selected = window.selectedDesktopLpIds || [];
      const countLabel = document.getElementById('selectedExpDesktopCount');
      if (countLabel) countLabel.innerText = `${selected.length} Selected`;

      const btnText = document.getElementById('expDropdownToggleText');
      if (btnText) {
        if (selected.length === 0) {
          btnText.innerText = '-- Choose Lesson Plan Topic (or Manual Entry below) --';
          btnText.className = 'truncate text-slate-400';
        } else if (selected.length === 1) {
          const lps = window.desktopSubjectLessonPlans || [];
          const idx = lps.findIndex(l => l.id === selected[0]);
          const lp = lps[idx];
          const noStr = idx >= 0 ? `#${idx + 1}. ` : '';
          btnText.innerText = lp ? `${noStr}${lp.topic_content}` : '1 Topic Selected';
          btnText.className = 'truncate text-indigo-300 font-bold';
        } else {
          btnText.innerText = `${selected.length} Lesson Topics Selected`;
          btnText.className = 'truncate text-indigo-300 font-bold';
        }
      }
    }

    function syncDesktopSelectedLpsToTopics() {
      const selected = window.selectedDesktopLpIds || [];
      const lps = window.desktopSubjectLessonPlans || [];
      const topicsElem = document.getElementById('topicsCovered');
      if (!topicsElem) return;

      if (selected.length === 0) {
        topicsElem.value = '';
        topicsElem.placeholder = 'Describe the topics covered in class today...';
        return;
      }

      const list = selected.map(id => {
        const lp = lps.find(l => l.id === id);
        return lp ? lp.topic_content : '';
      }).filter(Boolean);

      topicsElem.value = list.join(' & ');
    }

    function clearSelectedExperiments() {
      if (window.isCurrentDesktopLab) {
        window.selectedDesktopExpIds = [];
        const checkboxes = document.querySelectorAll('#desktopExpCheckboxContainer input[type="checkbox"]');
        checkboxes.forEach(cb => {
          cb.checked = false;
          const label = cb.closest('label');
          if (label) label.classList.remove('bg-indigo-950/40', 'border-indigo-500/50');
        });
        updateDesktopExpSelectedDisplay();
        syncDesktopSelectedExpsToTopics();
      } else {
        window.selectedDesktopLpIds = [];
        const checkboxes = document.querySelectorAll('#desktopExpCheckboxContainer input[type="checkbox"]');
        checkboxes.forEach(cb => {
          cb.checked = false;
          const label = cb.closest('label');
          if (label) label.classList.remove('bg-indigo-950/40', 'border-indigo-500/50');
        });
        updateDesktopLpSelectedDisplay();
        syncDesktopSelectedLpsToTopics();
      }
    }

    function filterDesktopExpList() {
      const query = (document.getElementById('expSearchDesktopInput')?.value || '').trim().toLowerCase();
      const rows = document.querySelectorAll('.desktop-exp-row');
      rows.forEach(r => {
        const s = r.getAttribute('data-search') || '';
        if (!query || s.includes(query)) {
          r.classList.remove('hidden');
        } else {
          r.classList.add('hidden');
        }
      });
    }

    function switchMode(mode) {
      activeMode = mode;
      const btnList = document.getElementById('btnModeList');
      const btnGrid = document.getElementById('btnModeGrid');
      const divList = document.getElementById('attendanceModeList');
      const divGrid = document.getElementById('attendanceModeGrid');

      if (mode === 'list') {
        btnList.className = "px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-600 text-white transition-all cursor-pointer";
        btnGrid.className = "px-2.5 py-1 text-xs font-bold rounded-md text-slate-400 hover:text-slate-200 transition-all cursor-pointer";
        divList.classList.remove('hidden');
        divGrid.classList.add('hidden');
        renderList();
      } else {
        btnGrid.className = "px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-600 text-white transition-all cursor-pointer";
        btnList.className = "px-2.5 py-1 text-xs font-bold rounded-md text-slate-400 hover:text-slate-200 transition-all cursor-pointer";
        divGrid.classList.remove('hidden');
        divList.classList.add('hidden');
        renderGrid();
      }
    }

    function getFilteredStudents() {
      if (document.getElementById('subBatchCard').classList.contains('hidden')) {
        return currentStudents;
      }
      const selectedRadio = document.querySelector('input[name="subBatchSelect"]:checked');
      const val = selectedRadio ? selectedRadio.value : 'Whole';
      if (val === 'Whole') {
        return currentStudents;
      }
      const hasAssignedBatches = currentStudents.some(s => s.lab_batch);
      if (hasAssignedBatches) {
        return currentStudents.filter(s => String(s.lab_batch) === String(val));
      }
      const half = Math.ceil(currentStudents.length / 2);
      if (val === '1') {
        return currentStudents.slice(0, half);
      } else {
        return currentStudents.slice(half);
      }
    }

    function updateAttendanceStats() {
      const filtered = getFilteredStudents();
      const presentCount = filtered.filter(s => s.present).length;
      const absentCount = filtered.length - presentCount;
      const countLabel = document.getElementById('studentCountLabel');
      if (countLabel) {
        countLabel.innerHTML = `Total: <span class="text-slate-200 font-bold">${filtered.length}</span> <span class="text-slate-600 mx-1">•</span> <span class="text-emerald-400 font-bold">${presentCount} Present</span> <span class="text-slate-600 mx-1">•</span> <span class="text-rose-400 font-bold">${absentCount} Absent</span>`;
      }
    }

    function filterStudentsByBatch() {
      renderList();
      renderGrid();
      checkExistingAttendance();
      updateAttendanceStats();
    }

    function selectPeriodPreset(periods) {
      document.querySelectorAll('input[name="logPeriods"]').forEach(cb => {
        cb.checked = periods.includes(parseInt(cb.value));
      });
      checkExistingAttendance();
    }

    let isSessionAttendanceLoaded = false;

    function checkExistingAttendance() {
      // Do not run auto-overwrite if user is actively editing a recorded log
      if (window.attEditingLogIds && window.attEditingLogIds.length > 0) {
        return;
      }

      const subjectSelect = document.getElementById('subjectSelect');
      const subjectId = subjectSelect ? subjectSelect.value : '';
      const dateSelect = document.getElementById('logDate');
      const date = dateSelect ? dateSelect.value : '';
      const checkedPeriods = Array.from(document.querySelectorAll('input[name="logPeriods"]:checked')).map(el => parseInt(el.value));
      const subBatchCard = document.getElementById('subBatchCard');
      const selectedSubBatchRadio = document.querySelector('input[name="subBatchSelect"]:checked');
      const subBatchVal = (subBatchCard && !subBatchCard.classList.contains('hidden') && selectedSubBatchRadio)
        ? selectedSubBatchRadio.value 
        : 'Whole';

      const notice = document.getElementById('existingSessionNotice');
      const noticeText = document.getElementById('existingSessionNoticeText');

      if (!subjectId || !date || checkedPeriods.length === 0) {
        if (notice) notice.classList.add('hidden');
        isSessionAttendanceLoaded = false;
        return;
      }

      const params = new URLSearchParams({
        batch_subject_id: subjectId,
        date: date,
        periods: checkedPeriods.join(','),
        sub_batch: subBatchVal
      });

      fetch(`/api/staff/attendance/session-check?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.exists) {
            isSessionAttendanceLoaded = true;
            if (notice && noticeText) {
              const pCount = (data.present_students || []).length;
              const aCount = (data.absent_students || []).length;
              const periodNames = checkedPeriods.map(p => 'P' + p).join(', ');
              let topicsSnippet = '';
              let displayNoticeDate = date || '—';
              if (date && date.includes('-')) {
                const dp = date.split('-');
                if (dp.length === 3 && dp[0].length === 4) displayNoticeDate = `${dp[2]}-${dp[1]}-${dp[0]}`;
              }
              noticeText.innerHTML = `Attendance for <strong>${periodNames}</strong> on <strong>${displayNoticeDate}</strong> (${pCount} Present, ${aCount} Absent) was already logged and has been automatically loaded.${topicsSnippet} Saving an additional entry now will record the new log without multiplying attendance hours.`;
              notice.classList.remove('hidden');
            }

            if (currentStudents && currentStudents.length > 0 && (data.present_students.length > 0 || data.absent_students.length > 0)) {
              const presentSet = new Set(data.present_students || []);
              const absentSet = new Set(data.absent_students || []);

              currentStudents.forEach(s => {
                if (absentSet.has(s.reg_no)) {
                  s.present = false;
                } else if (presentSet.has(s.reg_no)) {
                  s.present = true;
                }
              });
              renderList();
              renderGrid();
            }
          } else {
            isSessionAttendanceLoaded = false;
            if (notice) notice.classList.add('hidden');
          }
        })
        .catch(err => {
          console.error("Session attendance check error:", err);
        });
    }

    function renderList() {
      const container = document.getElementById('studentListContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<tr><td colspan="3" class="p-6 text-center text-slate-400 font-medium">No students registered in this class.</td></tr>';
        updateAttendanceStats();
        return;
      }

      filtered.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.className = "hover:bg-slate-900/50 transition-colors cursor-pointer select-none";
        const isPres = !!student.present;
        tr.innerHTML = `
          <td class="py-2 px-2 text-center font-bold font-mono text-xs ${isPres ? 'text-indigo-400' : 'text-rose-400'}">${student.roll_no || index + 1}</td>
          <td class="py-2 px-2 font-semibold text-slate-200 text-xs">
            <div class="flex items-center justify-between">
              <span>${student.name}</span>
              <span class="text-[10px] text-slate-500 font-mono hidden md:inline">${student.reg_no || ''}</span>
            </div>
          </td>
          <td class="py-2 px-2 text-center" onclick="event.stopPropagation()">
            <input type="checkbox" onchange="toggleStudentPresent('${student.reg_no}', this.checked)" ${isPres ? 'checked' : ''} class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-500 focus:ring-indigo-600 cursor-pointer">
          </td>
        `;
        tr.onclick = (e) => {
          if (e.target.tagName !== 'INPUT') {
            const cb = tr.querySelector('input[type="checkbox"]');
            if (cb) {
              cb.checked = !cb.checked;
              toggleStudentPresent(student.reg_no, cb.checked);
              const rollTd = tr.querySelector('td:first-child');
              if (rollTd) {
                rollTd.className = `py-2 px-2 text-center font-bold font-mono text-xs ${cb.checked ? 'text-indigo-400' : 'text-rose-400'}`;
              }
            }
          }
        };
        container.appendChild(tr);
      });
      updateAttendanceStats();
    }

    function renderGrid() {
      const container = document.getElementById('studentGridContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<div class="col-span-full p-6 text-center text-slate-400">No students registered.</div>';
        updateAttendanceStats();
        return;
      }

      filtered.forEach((student, index) => {
        const roll = student.roll_no || index + 1;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.onclick = () => {
          student.present = !student.present;
          renderGrid();
          const listCb = document.querySelector(`input[onchange*="${student.reg_no}"]`);
          if (listCb) listCb.checked = student.present;
        };
        
        if (student.present) {
          btn.className = "py-3 lg:py-3.5 px-2 rounded-xl font-bold bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-sm text-center cursor-pointer hover:bg-emerald-600/30 transition-all shadow-inner shadow-emerald-500/10 flex flex-col items-center justify-center gap-0.5";
          btn.innerHTML = `<span class="text-sm font-mono font-black">${roll}</span><span class="text-[9px] uppercase tracking-wider font-semibold opacity-75">P</span>`;
        } else {
          btn.className = "py-3 lg:py-3.5 px-2 rounded-xl font-bold bg-rose-600/20 text-rose-400 border border-rose-500/30 text-sm text-center cursor-pointer hover:bg-rose-600/30 transition-all shadow-inner shadow-rose-500/10 flex flex-col items-center justify-center gap-0.5";
          btn.innerHTML = `<span class="text-sm font-mono font-black">${roll}</span><span class="text-[9px] uppercase tracking-wider font-semibold opacity-75">A</span>`;
        }
        btn.title = `${student.name} (${student.reg_no || roll})`;
        container.appendChild(btn);
      });
      updateAttendanceStats();
    }

    function toggleStudentPresent(regNo, isPresent) {
      const student = currentStudents.find(s => s.reg_no === regNo);
      if (student) {
        student.present = isPresent;
      }
      updateAttendanceStats();
    }

    function toggleAllCheckboxes() {
      isAllChecked = !isAllChecked;
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isAllChecked);
      const btn = document.getElementById('btnCheckAll');
      if (btn) btn.innerText = isAllChecked ? "Mark All Absent" : "Mark All Present";
      renderList();
      renderGrid();
    }

    function toggleAllGrid(isPresent) {
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isPresent);
      isAllChecked = isPresent;
      const btn = document.getElementById('btnCheckAll');
      if (btn) btn.innerText = isAllChecked ? "Mark All Absent" : "Mark All Present";
      renderList();
      renderGrid();
    }

    function saveAttendanceAndLog() {
      const subjectSelect = document.getElementById('subjectSelect');
      const subjectId = subjectSelect ? subjectSelect.value : '';
      const dateSelect = document.getElementById('logDate');
      const date = dateSelect ? dateSelect.value : '';
      
      const checkedPeriods = Array.from(document.querySelectorAll('input[name="logPeriods"]:checked')).map(el => parseInt(el.value));
      const topicsElem = document.getElementById('topicsCovered');
      const topics = topicsElem ? topicsElem.value.trim() : '';

      if (topicsElem) topicsElem.classList.remove('border-red-500');

      if (!subjectId) {
        showMessage("Please select a class subject / batch first.", true);
        return;
      }
      if (checkedPeriods.length === 0) {
        showMessage("Please select at least one Period / Hour (e.g. P1, P2).", true);
        return;
      }
      if (!topics) {
        if (topicsElem) {
          topicsElem.classList.add('border-red-500');
          topicsElem.focus();
        }
        showMessage(window.isCurrentDesktopLab ? "Please select experiment(s) or enter manual topics covered in class today." : "Please select lesson plan topic(s) or enter manual topics covered in class today.", true);
        return;
      }

      const present = [];
      const absent = [];
      const filtered = getFilteredStudents();
      filtered.forEach(s => {
        if (s.present) {
          present.push(s.reg_no);
        } else {
          absent.push(s.reg_no);
        }
      });

      const subBatchCard = document.getElementById('subBatchCard');
      const selectedSubBatchRadio = document.querySelector('input[name="subBatchSelect"]:checked');
      const subBatchVal = (subBatchCard && !subBatchCard.classList.contains('hidden') && selectedSubBatchRadio)
        ? selectedSubBatchRadio.value 
        : 'Whole';

      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfMeta ? csrfMeta.content : '';

      const isLab = window.isCurrentDesktopLab;
      const selectedExpIds = window.selectedDesktopExpIds || [];
      const practicalExpId = (isLab && selectedExpIds.length > 0) ? selectedExpIds[0] : null;

      const selectedLpIds = window.selectedDesktopLpIds || [];
      const lessonPlanIdVal = (!isLab && selectedLpIds.length > 0) ? selectedLpIds[0] : null;

      const payload = {
        batch_subject_id: subjectId,
        date: date,
        periods: checkedPeriods,
        lesson_plan_id: lessonPlanIdVal,
        lesson_plan_ids: selectedLpIds,
        practical_experiment_id: practicalExpId,
        practical_experiment_ids: selectedExpIds,
        topics_covered: topics,
        present_students: present,
        absent_students: absent,
        sub_batch: subBatchVal,
        is_additional_log: isSessionAttendanceLoaded
      };

      if (window.attEditingLogIds && window.attEditingLogIds.length > 0) {
        payload.log_ids = window.attEditingLogIds;
      }

      fetch('/api/staff/attendance/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(payload)
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(errData => {
            throw new Error(errData.message || `Server returned status ${res.status}`);
          }).catch(() => {
            throw new Error(`HTTP error ${res.status}: ${res.statusText}`);
          });
        }
        return res.json();
      })
      .then(data => {
        if (data.status === 'SUCCESS') {
          const wasEditing = !!window.attEditingLogIds;
          showMessage(data.message || (wasEditing ? "Class log and attendance updated successfully!" : "Class log and attendance recorded successfully!"), false);
          if (wasEditing) {
            cancelDesktopEditingLog();
          } else {
            checkExistingAttendance();
          }
          loadDesktopPastLogs();
          // Also refresh subject details to update next_log_sl_no
          fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
            .then(r => r.json())
            .then(d => {
              if (d.status === 'SUCCESS' && typeof d.next_log_sl_no !== 'undefined') {
                const nextPointer = document.getElementById('logNextSlNoPointer');
                if (nextPointer) nextPointer.innerText = `Next Entry: #${d.next_log_sl_no}`;
              }
            }).catch(() => {});
        } else {
          showMessage(data.message || "Failed to save attendance log.", true);
        }
      })
      .catch(err => {
        console.error('Attendance Save Error:', err);
        showMessage(err.message || "Error saving log and attendance.", true);
      });
    }

    function formatDisplayDate(dateStr) {
      if (!dateStr || dateStr === '-' || dateStr === '—') return '—';
      if (typeof dateStr !== 'string') return String(dateStr);
      const m = dateStr.trim().match(/^(\d{4})-(\d{2})-(\d{2})/);
      if (m) {
        return `${m[3]}-${m[2]}-${m[1]}`;
      }
      const parts = dateStr.split('-');
      if (parts.length === 3 && parts[0].length === 4) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
      }
      return dateStr;
    }

    function loadDesktopPastLogs() {
      const subjectSelect = document.getElementById('subjectSelect');
      const subjectId = subjectSelect ? subjectSelect.value : '';
      const container = document.getElementById('desktopPastLogsTableContainer');
      const badge = document.getElementById('pastLogsCountBadge');
      if (!subjectId || !container) return;

      container.innerHTML = `
        <div class="text-center py-8 text-slate-400 text-xs font-mono">
          <span class="material-symbols-rounded text-2xl mb-1 block animate-spin text-indigo-400">progress_activity</span>
          Loading recorded class logs...
        </div>
      `;

      fetch(`/api/staff/attendance/subjects/${subjectId}/reports`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            window.attPastLogsCache = data.logs || [];
            if (badge) badge.innerText = `${window.attPastLogsCache.length} Records`;
            renderDesktopPastLogsTable();

            // Check if there is an edit request pending from URL
            if (window.pendingEditLogIds) {
              const targetIds = String(window.pendingEditLogIds).split(',').map(id => id.trim());
              const targetIdx = window.attPastLogsCache.findIndex(l => {
                if (l.log_ids && l.log_ids.some(id => targetIds.includes(String(id)))) return true;
                return targetIds.includes(String(l.id));
              });
              if (targetIdx >= 0) {
                editDesktopLog(targetIdx);
              }
              window.pendingEditLogIds = null;
            }
          } else {
            container.innerHTML = `<div class="text-center py-6 text-rose-400 text-xs font-semibold">${data.message || 'Failed to load recorded logs.'}</div>`;
          }
        })
        .catch(err => {
          console.error('Error loading past logs:', err);
          container.innerHTML = '<div class="text-center py-6 text-rose-400 text-xs font-semibold">Error connecting to server.</div>';
        });
    }

    function renderDesktopPastLogsTable() {
      const container = document.getElementById('desktopPastLogsTableContainer');
      if (!container) return;
      const logs = window.attPastLogsCache || [];

      if (logs.length === 0) {
        container.innerHTML = `
          <div class="text-center py-8 text-slate-400 font-medium">
            <span class="material-symbols-rounded text-3xl text-slate-600 mb-1.5 block">folder_open</span>
            <span class="text-xs font-bold text-slate-300">No Class Logs Recorded Yet</span>
            <p class="text-[11px] text-slate-500 mt-0.5">Use the form above to record attendance and class syllabus logs.</p>
          </div>
        `;
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/80 rounded-xl bg-slate-900/30">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-950/70 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[10px] font-black">
                <th class="p-3 whitespace-nowrap">Date & Log #</th>
                <th class="p-3 text-center whitespace-nowrap">Period</th>
                <th class="p-3">Topics Covered</th>
                <th class="p-3 text-center whitespace-nowrap">Present</th>
                <th class="p-3 text-center whitespace-nowrap">Absent</th>
                <th class="p-3 text-center whitespace-nowrap">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
      `;

      logs.forEach((log, idx) => {
        const periodDisplay = log.period_display || (log.periods && log.periods.length > 1 ? `Periods ${log.periods.join(', ')}` : `P${log.period}`);
        const subBatchBadge = log.sub_batch && log.sub_batch !== 'Whole' 
            ? `<span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Batch ${log.sub_batch}</span>` 
            : '';
        const staffText = log.staff_name ? `<div class="text-[10px] text-slate-500 mt-0.5 font-mono">Recorded by: ${log.staff_name}</div>` : '';
        const logIdsStr = log.log_ids ? log.log_ids.join(',') : (log.id || '');
        const formattedLogDate = formatDisplayDate(log.date);
        const isCurrentlyEditing = window.attEditingLogIds && (
          (log.log_ids && log.log_ids.some(id => window.attEditingLogIds.includes(id))) ||
          window.attEditingLogIds.includes(log.id)
        );

        html += `
          <tr class="hover:bg-slate-900/60 transition ${isCurrentlyEditing ? 'bg-sky-950/30 border-l-2 border-l-sky-400' : ''}">
            <td class="p-3 font-mono font-bold text-slate-300 whitespace-nowrap">
              <div class="flex items-center gap-1.5">
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-black bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">#${log.sl_no || '-'}</span>
                <span>${formattedLogDate}</span>
                ${subBatchBadge}
              </div>
            </td>
            <td class="p-3 text-center font-bold text-slate-400 whitespace-nowrap">${periodDisplay}</td>
            <td class="p-3 text-slate-200 min-w-[220px]">
              <div class="font-medium line-clamp-2">${log.topics_covered || '-'}</div>
              ${staffText}
            </td>
            <td class="p-3 text-center font-bold text-emerald-400">${log.present_count}</td>
            <td class="p-3 text-center font-bold text-rose-400">${log.absent_count}</td>
            <td class="p-3 text-center whitespace-nowrap">
              <button type="button" onclick="editDesktopLog(${idx})" class="px-2.5 py-1 rounded text-xs font-bold text-sky-400 bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/30 transition mr-1.5 cursor-pointer" title="Edit this log entry and student attendance">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
              </button>
              <button type="button" onclick="deleteDesktopLog('${logIdsStr}', '${log.sl_no || ''}', '${log.date}', '${periodDisplay}')" class="px-2.5 py-1 rounded text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 transition cursor-pointer" title="Delete accidental or duplicate log entry">
                <i class="fa-solid fa-trash-can mr-1"></i> Delete
              </button>
            </td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function editDesktopLog(idx) {
      const log = (window.attPastLogsCache || [])[idx];
      if (!log) return;

      window.attEditingLog = log;
      window.attEditingLogIds = log.log_ids || [log.id];

      // 1. Set Date
      if (log.date) {
        const dateInput = document.getElementById('logDate');
        if (dateInput) dateInput.value = log.date;
      }

      // 2. Set Periods
      const pArr = (log.periods && log.periods.length > 0)
        ? log.periods
        : String(log.period).split(',').map(p => parseInt(p.trim())).filter(p => !isNaN(p));

      document.querySelectorAll('input[name="logPeriods"]').forEach(chk => {
        chk.checked = pArr.includes(parseInt(chk.value));
      });

      // 3. Set Sub-batch
      if (log.sub_batch === '1') {
        const r1 = document.querySelector('input[name="subBatchSelect"][value="1"]');
        if (r1) r1.checked = true;
      } else if (log.sub_batch === '2') {
        const r2 = document.querySelector('input[name="subBatchSelect"][value="2"]');
        if (r2) r2.checked = true;
      } else {
        const rw = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
        if (rw) rw.checked = true;
      }

      // 4. Set Topics & Select Experiment / Lesson Plan in dropdown
      const isLab = window.isCurrentDesktopLab;
      const cleanTopic = (log.topics_covered || '').trim().toLowerCase();

      if (isLab) {
        window.selectedDesktopExpIds = [];
        const exps = window.desktopSubjectExperiments || [];
        let matchedExp = null;
        if (log.practical_experiment_id) {
          matchedExp = exps.find(e => e.id == log.practical_experiment_id);
        }
        if (!matchedExp) {
          matchedExp = exps.find(e => {
            const fullTopic = `exp ${e.experiment_no}: ${e.title}`.toLowerCase();
            return cleanTopic.includes(`exp ${e.experiment_no}:`) || cleanTopic.includes(`exp ${e.experiment_no} `) || cleanTopic === fullTopic;
          });
        }
        if (matchedExp) {
          window.selectedDesktopExpIds = [matchedExp.id];
        }
        renderDesktopExpCheckboxes();
      } else {
        window.selectedDesktopLpIds = [];
        const lps = window.desktopSubjectLessonPlans || [];
        let matchedLp = null;
        if (log.lesson_plan_id) {
          matchedLp = lps.find(l => l.id == log.lesson_plan_id);
        }
        if (!matchedLp) {
          matchedLp = lps.find(l => cleanTopic.includes((l.topic_content || '').trim().toLowerCase()));
        }
        if (matchedLp) {
          window.selectedDesktopLpIds = [matchedLp.id];
        }
        renderDesktopLpCheckboxes();
      }

      const topicsElem = document.getElementById('topicsCovered');
      if (topicsElem) {
        topicsElem.value = log.topics_covered || '';
      }

      // 5. Restore Student Attendance states
      let presentArr = [];
      try {
        presentArr = typeof log.present_students === 'string' ? JSON.parse(log.present_students || '[]') : (log.present_students || []);
      } catch (e) {
        presentArr = [];
      }
      const presentSet = new Set(presentArr);
      currentStudents.forEach(s => {
        s.present = presentSet.has(s.reg_no);
      });
      renderList();
      renderGrid();

      // 6. Update and show Active Edit Banner
      const banner = document.getElementById('attEditingBanner');
      if (banner) {
        const slNoElem = document.getElementById('editBannerSlNo');
        if (slNoElem) slNoElem.textContent = log.sl_no ? `Log #${log.sl_no}` : `Log #${idx + 1}`;

        const dateElem = document.getElementById('editBannerDate');
        if (dateElem) dateElem.textContent = formatDisplayDate(log.date);

        const periodsElem = document.getElementById('editBannerPeriods');
        if (periodsElem) periodsElem.textContent = log.period_display || ('Periods ' + log.period);

        const topicElem = document.getElementById('editBannerTopic');
        if (topicElem) topicElem.textContent = log.topics_covered ? `Topic: ${log.topics_covered}` : '';

        banner.classList.remove('hidden');
      }

      // 7. Update Save Buttons text & styling
      const quickSave = document.getElementById('btnQuickSave');
      if (quickSave) {
        quickSave.innerHTML = '<span class="material-symbols-rounded text-sm">edit_note</span> Update';
        quickSave.className = "px-3 py-1 bg-sky-600 hover:bg-sky-500 active:scale-95 text-white rounded-md font-bold text-xs flex items-center gap-1 shadow cursor-pointer transition";
      }
      const bottomSave = document.getElementById('btnBottomSave');
      if (bottomSave) {
        bottomSave.innerHTML = '<span class="material-symbols-rounded text-base">check_circle</span> Update Log & Attendance';
        bottomSave.className = "w-full py-2.5 bg-sky-600 hover:bg-sky-500 active:scale-[0.99] text-white rounded-lg font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg transition-all cursor-pointer";
      }

      // Re-render past logs table to highlight active editing row
      renderDesktopPastLogsTable();

      // Smooth scroll to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
      showMessage(`Editing Log #${log.sl_no || (idx + 1)} for ${formatDisplayDate(log.date)}. Adjust details and click Update.`, false);
    }

    function cancelDesktopEditingLog() {
      window.attEditingLog = null;
      window.attEditingLogIds = null;

      const banner = document.getElementById('attEditingBanner');
      if (banner) banner.classList.add('hidden');

      // Reset Date to Today
      const dateInput = document.getElementById('logDate');
      if (dateInput) dateInput.value = new Date().toISOString().split('T')[0];

      // Reset periods to none
      document.querySelectorAll('input[name="logPeriods"]').forEach(cb => {
        cb.checked = false;
      });

      // Reset Sub-batch to Whole
      const wholeRadio = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
      if (wholeRadio) wholeRadio.checked = true;

      // Clear topics and selections
      clearSelectedExperiments();
      const topicsElem = document.getElementById('topicsCovered');
      if (topicsElem) topicsElem.value = '';

      // Reset all students present
      currentStudents.forEach(s => s.present = true);
      isAllChecked = true;
      const btnCheckAll = document.getElementById('btnCheckAll');
      if (btnCheckAll) btnCheckAll.innerText = "Mark All Absent";

      renderList();
      renderGrid();

      // Reset Save Buttons
      const quickSave = document.getElementById('btnQuickSave');
      if (quickSave) {
        quickSave.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save';
        quickSave.className = "px-3 py-1 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white rounded-md font-bold text-xs flex items-center gap-1 shadow cursor-pointer transition";
      }
      const bottomSave = document.getElementById('btnBottomSave');
      if (bottomSave) {
        bottomSave.innerHTML = '<span class="material-symbols-rounded text-base">check_circle</span> Save Log & Attendance';
        bottomSave.className = "w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.99] text-white rounded-lg font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg transition-all cursor-pointer";
      }

      // Re-render past logs table to clear highlight
      renderDesktopPastLogsTable();
    }

    function deleteDesktopLog(logIdsStr, slNo, date, periods) {
      const subjectSelect = document.getElementById('subjectSelect');
      const subjectId = subjectSelect ? subjectSelect.value : '';
      if (!subjectId || !logIdsStr) {
        showMessage("Subject or log ID missing.", true);
        return;
      }
      const logIds = logIdsStr.split(',').map(id => parseInt(id.trim())).filter(id => !isNaN(id));
      if (logIds.length === 0) {
        showMessage("Invalid log IDs.", true);
        return;
      }

      const slText = slNo ? `Log #${slNo}` : 'this log entry';
      const promptMsg = `Are you sure you want to permanently delete ${slText} on ${formatDisplayDate(date)} (${periods})?\n\nIf this was an accidental duplicate entry, the original surviving log and student attendance will be preserved safely.`;
      if (!confirm(promptMsg)) return;

      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfMeta ? csrfMeta.content : '';

      fetch('/api/staff/attendance/delete-log', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          batch_subject_id: subjectId,
          log_ids: logIds
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showMessage(data.message || 'Log entry removed successfully.', false);
          // If the log being edited was just deleted, cancel edit mode
          if (window.attEditingLogIds && logIds.some(id => window.attEditingLogIds.includes(id))) {
            cancelDesktopEditingLog();
          }
          loadDesktopPastLogs();
          // Also refresh subject details to update next_log_sl_no
          fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
            .then(r => r.json())
            .then(d => {
              if (d.status === 'SUCCESS' && typeof d.next_log_sl_no !== 'undefined') {
                const nextPointer = document.getElementById('logNextSlNoPointer');
                if (nextPointer) nextPointer.innerText = `Next Entry: #${d.next_log_sl_no}`;
              }
            }).catch(() => {});
        } else {
          showMessage(data.message || 'Failed to delete log entry.', true);
        }
      })
      .catch(err => {
        console.error('Delete Log Error:', err);
        showMessage('Error connecting to server.', true);
      });
    }

    function openLabBatchSetupModalFromAttendance() {
      const sid = document.getElementById('subjectSelect') ? document.getElementById('subjectSelect').value : null;
      if (!sid) {
        alert("Please select a subject first.");
        return;
      }
      if (typeof openLabBatchSetupModal === 'function') {
        openLabBatchSetupModal(sid, function(res) {
          onSubjectChange();
        });
      }
    }
  </script>

  @include('partials.lab_batch_setup_modal')
</body>
</html>
