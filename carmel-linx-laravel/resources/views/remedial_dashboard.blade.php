<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remedial Sessions Workspace - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }

    /* Custom elegant scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #020617;
    }
    ::-webkit-scrollbar-thumb {
      background: #1e293b;
      border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #334155;
    }

    /* Clean, desktop-friendly typography conforming to minimum font standards */
    html {
      font-size: 100%; /* standard size */
    }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-purple-500/30">

  <!-- Fixed Top Header -->
  <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-xl">
    <div class="px-6 h-16 flex items-center justify-between max-w-7xl mx-auto w-full">
      <div class="flex items-center gap-4">
        @php
          $backUrl = (session('userRole') === 'Demonstrator') ? '/dashboard/demonstrator' : '/dashboard/lecturer';
        @endphp
        <a href="{{ $backUrl }}" class="flex items-center gap-2 px-3.5 py-1.5 bg-slate-800/60 hover:bg-purple-500/10 border border-slate-700/60 hover:border-purple-500/30 text-slate-300 hover:text-purple-300 rounded-lg font-semibold text-sm transition-premium no-underline">
          <span class="material-symbols-rounded text-lg">arrow_back</span>
          <span>Back</span>
        </a>
        <div class="h-6 w-[1px] bg-slate-800"></div>
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-bold rounded-lg w-8 h-8 flex items-center justify-center text-sm shadow-md shadow-purple-500/10">RS</div>
        <div>
          <h1 class="font-bold text-base text-white tracking-wide">Remedial Sessions</h1>
          <p class="text-sm font-semibold text-slate-400 tracking-wide uppercase" style="font-size: 0.65rem;">Coaching & Diagnostics</p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        @include('partials.fullscreen_btn')
      </div>
    </div>
  </header>

  <main class="flex-grow p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-6">

    <!-- Dashboard Tabs -->
    <div class="flex gap-2 border-b border-slate-800/60 pb-3">
      <button onclick="switchTab('roomsList')" id="tab_roomsList" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20 hover:border-purple-500/30 transition-premium cursor-pointer">Active Rooms</button>
      <button onclick="switchTab('createRoom')" id="tab_createRoom" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer">Create New Room</button>
    </div>

    <!-- Active Rooms Panel -->
    <div id="panel_roomsList" class="space-y-6">
      <div id="roomsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <!-- Loaded via JS -->
      </div>
    </div>

    <!-- Create Room Panel -->
    <div id="panel_createRoom" class="hidden space-y-6">
      <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-6 shadow-xl max-w-4xl mx-auto w-full">
        <h2 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
          <span class="bg-purple-500/10 text-purple-400 w-6 h-6 rounded-md flex items-center justify-center text-sm font-bold border border-purple-500/20">1</span>
          Select Subject
        </h2>
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
          <div class="flex-grow">
            <select id="subjectSelect" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-purple-500/80 outline-none transition-premium">
              <option value="">Select a Subject...</option>
            </select>
          </div>
          <button onclick="fetchStudentPerformance()" class="bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-semibold text-sm px-6 py-2.5 transition-premium shadow-md shadow-purple-500/15 cursor-pointer">Analyze Performance</button>
        </div>
        
        <div id="performanceSection" class="hidden space-y-4 pt-4 border-t border-slate-800/80">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h2 class="text-base font-semibold text-white flex items-center gap-2">
              <span class="bg-purple-500/10 text-purple-400 w-6 h-6 rounded-md flex items-center justify-center text-sm font-bold border border-purple-500/20">2</span>
              Identify Weak Students
            </h2>
            <div class="flex items-center gap-3 bg-slate-950/80 border border-slate-800 rounded-lg p-2">
              <label class="text-sm text-slate-400 font-medium">Auto-Select Below Marks:</label>
              <input type="number" id="thresholdMark" value="20" class="w-16 bg-slate-900 border border-slate-800 rounded px-2.5 py-1 text-sm text-white outline-none focus:border-purple-500 text-center">
              <button onclick="applyThreshold()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 rounded px-3 py-1 text-sm font-semibold transition-premium cursor-pointer border border-slate-700/60">Apply</button>
            </div>
          </div>
          
          <div class="overflow-x-auto rounded-lg border border-slate-800/80">
            <table class="w-full text-left text-sm border-collapse bg-slate-950/20">
              <thead>
                <tr class="bg-slate-950/85 border-b border-slate-800/80 text-slate-400 font-semibold">
                  <th class="p-3 w-12 text-center">
                    <input type="checkbox" id="selectAllStudents" onchange="toggleAllStudents()" class="w-4 h-4 text-purple-500 rounded bg-slate-900 border-slate-800 focus:ring-0">
                  </th>
                  <th class="p-3">Reg No</th>
                  <th class="p-3">Name</th>
                  <th class="p-3 text-right">Total Marks</th>
                </tr>
              </thead>
              <tbody id="performanceTableBody" class="divide-y divide-slate-800/50">
              </tbody>
            </table>
          </div>

          <div class="flex justify-end pt-4">
            <button onclick="provisionRoom()" class="bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-sm px-8 py-2.5 transition-premium shadow-md shadow-emerald-500/15 cursor-pointer">Provision Remedial Room</button>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- View Room Modal -->
  <div id="roomModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4 md:p-6 overflow-hidden">
    <div class="bg-slate-900 border border-slate-800/80 w-full max-w-6xl h-full max-h-[90vh] rounded-xl shadow-2xl flex flex-col overflow-hidden transition-all duration-300">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-slate-900 border-b border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0 shadow-md">
        <div class="flex items-start gap-3">
          <div class="p-2 bg-purple-500/10 text-purple-400 rounded-lg border border-purple-500/20 shrink-0">
            <span class="material-symbols-rounded text-xl block">school</span>
          </div>
          <div>
            <h3 id="modalRoomTitle" class="font-bold text-base text-white tracking-wide leading-tight">Remedial Class Room</h3>
            <div id="modalRoomSub" class="mt-1"></div>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 self-start md:self-center">
          <!-- Room Status Selector -->
          <div class="flex items-center gap-2 bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5">
            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Status:</span>
            <select id="modalRoomStatus" onchange="updateRoomStatus()" class="bg-transparent text-sm font-semibold text-emerald-400 focus:outline-none border-none cursor-pointer">
              <option value="active" class="bg-slate-900 text-emerald-400">Active</option>
              <option value="archived" class="bg-slate-900 text-amber-500">Archived</option>
            </select>
          </div>

          <!-- Delete Room Button -->
          <button id="btnDeleteRoom" onclick="confirmDeleteRoom(this)" class="bg-rose-500/10 hover:bg-rose-600/20 border border-rose-500/30 hover:border-rose-500/50 text-rose-400 hover:text-rose-300 px-4 py-2 rounded-lg font-semibold text-sm transition-premium flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-sm">delete</span> Delete Room
          </button>

          <!-- Close Button -->
          <button onclick="closeRoomModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded-lg font-semibold text-sm transition-premium border border-slate-700/60 flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-sm">close</span> Close
          </button>
        </div>
      </div>

      <!-- Modal Body (Scrollable) -->
      <div class="flex-grow overflow-y-auto p-5 md:p-6 space-y-6 bg-slate-950/20">
        <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Foldable Students Panel -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
          <div onclick="toggleStudents()" class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-850/50 transition-premium">
            <h4 class="font-semibold text-white text-sm flex items-center gap-2"><span class="material-symbols-rounded text-purple-400">group</span> Enrolled Students</h4>
            <span id="studentsIcon" class="material-symbols-rounded text-slate-400 transition-transform">expand_more</span>
          </div>
          <div id="studentsContent" class="hidden border-t border-slate-800/60 p-4 bg-slate-950/20 space-y-4">
            <!-- Add Student Form -->
            <div class="flex flex-col sm:flex-row gap-3 bg-slate-900/65 p-3 rounded-lg border border-slate-800">
              <div class="flex-grow">
                <input type="text" id="addStudentRegNo" placeholder="Enter Registration Number (e.g. 23010203)" class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700 focus:border-purple-500/50 rounded-lg px-4 py-2 text-sm text-white outline-none transition-premium">
              </div>
              <button onclick="addStudentToRoom()" class="bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-semibold text-sm px-6 py-2 transition-premium shadow-md shadow-purple-500/10 flex items-center justify-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">person_add</span> Add Student
              </button>
            </div>
            
            <ul id="roomStudentsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm pt-2">
            </ul>
          </div>
        </div>

        <!-- Room Tabs -->
        <div class="flex gap-2 border-b border-slate-800/60 pb-3">
          <button onclick="switchRoomTab('logs')" id="rtab_logs" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:border-blue-500/30 transition-premium cursor-pointer">Session Logs</button>
          <button onclick="switchRoomTab('assessments')" id="rtab_assessments" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer">Assessments</button>
        </div>

        <!-- Session Logs Panel -->
        <div id="rpanel_logs" class="space-y-4">
          <div class="flex justify-between items-center">
            <h4 class="font-semibold text-white text-sm">Class Logs</h4>
            <div class="flex items-center gap-2">
              <button onclick="printRemedialAnalysisReport()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700/60 px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-premium flex items-center gap-1 cursor-pointer"><span class="material-symbols-rounded text-sm">analytics</span> Print Analysis Report</button>
              <button onclick="printRemedialAttendanceReport()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700/60 px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-premium flex items-center gap-1 cursor-pointer"><span class="material-symbols-rounded text-sm">print</span> Print Attendance Log</button>
              <button onclick="toggleLogForm()" class="bg-blue-600 hover:bg-blue-500 text-white px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-premium flex items-center gap-1 shadow-md shadow-blue-500/10 cursor-pointer"><span class="material-symbols-rounded text-sm">add</span> New Log</button>
            </div>
          </div>

          <div id="logFormContainer" class="hidden bg-slate-900 border border-slate-800 rounded-lg p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Date</label>
                <input type="date" id="logDate" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-blue-500 outline-none transition-premium">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Start Time</label>
                <input type="time" id="logStartTime" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-blue-500 outline-none transition-premium">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Duration (Mins)</label>
                <input type="number" id="logDuration" value="60" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-blue-500 outline-none transition-premium">
              </div>
              <div class="col-span-1 md:col-span-3">
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Topic Covered</label>
                <input type="text" id="logTopic" placeholder="e.g. Kirchhoff's Laws Revision" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-blue-500 outline-none transition-premium">
              </div>
            </div>
            
            <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Attendance (Check Present)</label>
            <div id="logAttendanceGrid" class="grid grid-cols-2 md:grid-cols-3 gap-2.5 mb-4 max-h-40 overflow-y-auto bg-slate-950/50 p-3 rounded-lg border border-slate-800">
            </div>

            <button onclick="saveLog()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-sm py-2.5 transition-premium shadow-md shadow-emerald-500/10 cursor-pointer">Save Session Log</button>
          </div>

          <div class="overflow-hidden rounded-lg border border-slate-800/80 shadow-sm">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-950 border-b border-slate-800/80 text-slate-400 font-semibold">
                  <th class="p-3 w-8"></th>
                  <th class="p-3">Date</th>
                  <th class="p-3">Start Time</th>
                  <th class="p-3">Duration</th>
                  <th class="p-3 w-1/3">Topic</th>
                  <th class="p-3 text-right">Attendance</th>
                </tr>
              </thead>
              <tbody id="roomLogsList" class="divide-y divide-slate-800/40">
                <!-- Loaded via JS -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Assessments Panel -->
        <div id="rpanel_assessments" class="hidden space-y-4">
          <div class="flex justify-between items-center">
            <h4 class="font-semibold text-white text-sm">Remedial Assessments</h4>
            <button onclick="toggleAssessmentForm()" class="bg-amber-600 hover:bg-amber-500 text-white px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-premium flex items-center gap-1 shadow-md shadow-amber-500/10 cursor-pointer"><span class="material-symbols-rounded text-sm">add</span> Create Test</button>
          </div>

          <div id="assessmentFormContainer" class="hidden bg-slate-900 border border-slate-800 rounded-lg p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Type</label>
                <select id="assessType" onchange="toggleAssessFormFields()" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-500 outline-none transition-premium">
                  <option value="Written Test">Written Test (with COs)</option>
                  <option value="Online Test">Online Test (Linked)</option>
                  <option value="Assignment">Assignment (Manual Entry)</option>
                </select>
              </div>
              
              <div id="assessLinkContainer" class="hidden col-span-1 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Link Online Test</label>
                <select id="assessLinkTest" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-500 outline-none transition-premium">
                  <option value="">Select Test to Link...</option>
                </select>
              </div>

              <div id="assessMaxMarksContainer" class="col-span-1 md:col-span-2">
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Max Marks (If Assignment)</label>
                <input type="number" id="assessMaxMarks" value="20" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-500 outline-none transition-premium">
              </div>

              <div class="col-span-1 md:col-span-3">
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Test Title</label>
                <input type="text" id="assessTitle" placeholder="e.g. Weekly Improvement Test 1" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-amber-500 outline-none transition-premium">
              </div>

              <div id="assessCOContainer" class="col-span-1 md:col-span-3 bg-slate-950/50 p-4 rounded-lg border border-slate-800">
                <label class="block text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Define CO Max Marks (Leave blank if not applicable)</label>
                <div class="grid grid-cols-5 gap-3">
                  <div>
                    <span class="text-sm text-slate-400 font-semibold block mb-1">CO1</span>
                    <input type="number" id="co1_marks" class="w-full bg-slate-900 border border-slate-800 rounded px-2 py-1 text-sm text-white outline-none focus:border-amber-500 text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-sm text-slate-400 font-semibold block mb-1">CO2</span>
                    <input type="number" id="co2_marks" class="w-full bg-slate-900 border border-slate-800 rounded px-2 py-1 text-sm text-white outline-none focus:border-amber-500 text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-sm text-slate-400 font-semibold block mb-1">CO3</span>
                    <input type="number" id="co3_marks" class="w-full bg-slate-900 border border-slate-800 rounded px-2 py-1 text-sm text-white outline-none focus:border-amber-500 text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-sm text-slate-400 font-semibold block mb-1">CO4</span>
                    <input type="number" id="co4_marks" class="w-full bg-slate-900 border border-slate-800 rounded px-2 py-1 text-sm text-white outline-none focus:border-amber-500 text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-sm text-slate-400 font-semibold block mb-1">CO5</span>
                    <input type="number" id="co5_marks" class="w-full bg-slate-900 border border-slate-800 rounded px-2 py-1 text-sm text-white outline-none focus:border-amber-500 text-center" placeholder="-">
                  </div>
                </div>
              </div>
            </div>
            <button onclick="saveAssessment()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-sm py-2.5 transition-premium shadow-md shadow-emerald-500/10 cursor-pointer">Create Assessment</button>
          </div>

          <!-- Gradebook View -->
          <div id="gradebookContainer" class="hidden bg-slate-900 border border-slate-800 rounded-lg p-5 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
              <div>
                <h4 id="gradebookTitle" class="font-semibold text-amber-400 text-sm">Enter Scores</h4>
                <p id="gradebookSub" class="text-sm text-slate-400 font-mono mt-0.5"></p>
              </div>
              <div class="flex flex-wrap items-center gap-2">
                <button id="btnPrintRemedialReport" onclick="printRemedialReport()" class="bg-purple-600/20 text-purple-400 hover:bg-purple-500 hover:text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition-premium cursor-pointer border border-purple-500/10">Print Report</button>
                <button id="btnSyncScores" onclick="syncOnlineScores()" class="hidden bg-blue-600/20 text-blue-400 hover:bg-blue-500 hover:text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition-premium border border-blue-500/10">Sync Online Scores</button>
                <button onclick="closeGradebook()" class="text-slate-500 hover:text-white transition-colors p-1 cursor-pointer"><span class="material-symbols-rounded">close</span></button>
              </div>
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-slate-800/80 mb-4">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr id="gradebookTableHead" class="bg-slate-950/85 border-b border-slate-800/80 text-slate-400 font-semibold">
                    <!-- Dynamic -->
                  </tr>
                </thead>
                <tbody id="gradebookTableBody" class="divide-y divide-slate-800/40">
                </tbody>
              </table>
            </div>

            <button id="btnSaveScores" onclick="saveScores()" class="w-full bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-semibold text-sm py-2.5 transition-premium shadow-md shadow-purple-500/10 cursor-pointer">Save All Scores</button>
          </div>

          <div id="assessmentsList" class="space-y-4">
            <!-- Loaded via JS -->
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <script>
    let assignedSubjects = [];
    let currentStudentPerformance = [];
    let currentRoomId = null;
    let currentRoomStudents = [];

    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };

    window.onload = () => {
      loadAssignedSubjects();
      loadRooms();
    };

    function switchTab(tabId) {
      document.getElementById('panel_roomsList').classList.add('hidden');
      document.getElementById('panel_createRoom').classList.add('hidden');
      
      document.getElementById('tab_roomsList').className = "px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer";
      document.getElementById('tab_createRoom').className = "px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer";

      document.getElementById('panel_' + tabId).classList.remove('hidden');
      document.getElementById('tab_' + tabId).className = "px-5 py-2.5 rounded-lg text-sm font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20 hover:border-purple-500/30 transition-premium cursor-pointer";
    }

    function loadAssignedSubjects() {
      fetch('/api/remedial/assigned-subjects')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            assignedSubjects = data.subjects;
            const select = document.getElementById('subjectSelect');
            let html = '<option value="">Select a Subject...</option>';
            data.subjects.forEach((s, idx) => {
              html += `<option value="${idx}">${s.subject_code} - ${s.subject_name} (${s.batch_name})</option>`;
            });
            select.innerHTML = html;
          }
        });
    }

    function fetchStudentPerformance() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject first');
      const subj = assignedSubjects[idx];

      fetch(`/api/remedial/student-performance?classroom_id=${subj.classroom_id}&subject_code=${subj.subject_code}`)
        .then(res => res.json())
        .then(data => {
          if(data.status === 'SUCCESS') {
            currentStudentPerformance = data.students;
            renderPerformanceGrid();
            document.getElementById('performanceSection').classList.remove('hidden');
          }
        });
    }

    function renderPerformanceGrid() {
      const tbody = document.getElementById('performanceTableBody');
      let html = '';
      currentStudentPerformance.forEach((s, i) => {
        html += `
          <tr class="hover:bg-slate-900/50 transition-colors">
            <td class="p-3 text-center">
              <input type="checkbox" value="${s.reg_no}" class="student-checkbox w-4 h-4 text-purple-500 rounded bg-slate-900 border-slate-850">
            </td>
            <td class="p-3 text-sm text-slate-400 font-mono">${s.reg_no}</td>
            <td class="p-3 text-sm font-semibold text-slate-200">${s.name}</td>
            <td class="p-3 text-right text-sm font-semibold ${s.total_marks < 20 ? 'text-rose-400' : 'text-emerald-400'}">${s.total_marks}</td>
          </tr>
        `;
      });
      tbody.innerHTML = html;
    }

    function toggleAllStudents() {
      const isChecked = document.getElementById('selectAllStudents').checked;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = isChecked);
    }

    function applyThreshold() {
      const threshold = parseFloat(document.getElementById('thresholdMark').value) || 0;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
      currentStudentPerformance.forEach((s, i) => {
        if (s.total_marks < threshold) {
          const cb = document.querySelector(`.student-checkbox[value="${s.reg_no}"]`);
          if(cb) cb.checked = true;
        }
      });
    }

    function provisionRoom() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject');
      const subj = assignedSubjects[idx];

      const selected = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
      if (selected.length === 0) return alert('Select at least one student.');

      fetch('/api/remedial/rooms', {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          classroom_id: subj.classroom_id,
          subject_code: subj.subject_code,
          students: selected
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Room Provisioned!');
          loadRooms();
          switchTab('roomsList');
        }
      });
    }

    function loadRooms() {
      fetch('/api/remedial/rooms')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const container = document.getElementById('roomsContainer');
            if (data.rooms.length === 0) {
              container.innerHTML = `<div class="col-span-full py-10 text-center text-slate-500 font-bold text-sm">No active remedial rooms.</div>`;
              return;
            }

            let html = '';
            data.rooms.forEach(r => {
              html += `
                <div class="group bg-slate-900/60 border border-slate-800/80 hover:border-purple-500/40 rounded-xl p-5 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/5 cursor-pointer relative overflow-hidden flex flex-col justify-between" onclick="openRoom('${r.room_id}')">
                  <div class="absolute top-0 left-0 w-[3px] h-full bg-gradient-to-b from-purple-500 to-indigo-500 transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300"></div>
                  
                  <div>
                    <div class="flex justify-between items-start gap-2 mb-3">
                      <span class="px-2.5 py-0.5 text-sm font-semibold rounded-md bg-slate-800 border border-slate-700/60 text-slate-300 truncate max-w-[170px]" title="${r.department}">${r.department}</span>
                      <span class="px-2.5 py-0.5 rounded-md text-sm font-semibold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        ${r.status}
                      </span>
                    </div>

                    <div class="space-y-1 mb-4">
                      <div class="text-sm font-semibold text-purple-400 uppercase tracking-wide font-mono">${r.subject_code}</div>
                      <h3 class="font-bold text-white text-base leading-snug group-hover:text-purple-300 transition-colors">${r.subject_name}</h3>
                    </div>
                  </div>

                  <div class="border-t border-slate-800/60 pt-4 mt-1 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-500 text-lg">person</span>
                        Lecturer:
                      </span>
                      <span class="font-semibold text-slate-200">${r.lecturer_name}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-500 text-lg">layers</span>
                        Classroom:
                      </span>
                      <span class="font-semibold text-slate-200 font-mono">${r.batch_name}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-500 text-lg">group</span>
                        Enrolled:
                      </span>
                      <span class="px-2.5 py-0.5 text-sm font-bold rounded bg-purple-500/10 border border-purple-500/25 text-purple-400">
                        ${r.student_count} Students
                      </span>
                    </div>
                  </div>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    let currentAvailableTests = [];

    function openRoom(roomId) {
      currentRoomId = roomId;
      document.getElementById('logFormContainer').classList.add('hidden');
      document.getElementById('assessmentFormContainer').classList.add('hidden');
      document.getElementById('gradebookContainer').classList.add('hidden');
      switchRoomTab('logs');
      
      fetch(`/api/remedial/rooms/${roomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const r = data.room;
            document.getElementById('modalRoomTitle').innerText = `${r.subject_code} - ${r.subject_name}`;
            document.getElementById('modalRoomSub').innerHTML = `
              <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-sm text-slate-400">
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-500 text-base">domain</span> <strong>Dept:</strong> ${r.department}</span>
                <span class="hidden md:inline text-slate-600">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-500 text-base">person</span> <strong>Lecturer:</strong> ${r.lecturer_name}</span>
                <span class="hidden md:inline text-slate-600">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-500 text-base">layers</span> <strong>Batch:</strong> ${r.batch_name} (Sem ${r.semester})</span>
                <span class="hidden md:inline text-slate-600">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-500 text-base">calendar_today</span> <strong>Year:</strong> ${r.batch_year}</span>
              </div>
            `;
            
            // Set status select value
            const statusSelect = document.getElementById('modalRoomStatus');
            if (statusSelect) {
              statusSelect.value = r.status || 'active';
              if (r.status === 'archived') {
                statusSelect.className = "bg-transparent text-sm font-semibold text-amber-500 focus:outline-none border-none cursor-pointer";
              } else {
                statusSelect.className = "bg-transparent text-sm font-semibold text-emerald-400 focus:outline-none border-none cursor-pointer";
              }
            }
            currentRoomStudents = r.students;
            currentAvailableTests = r.available_tests || [];

            // Populate Test Dropdown
            let testHtml = '<option value="">Select Test to Link...</option>';
            currentAvailableTests.forEach(t => {
              testHtml += `<option value="${t.test_id}">${t.test_name} (${t.duration}m)</option>`;
            });
            document.getElementById('assessLinkTest').innerHTML = testHtml;

            // Render Students
            let sHtml = '';
            r.students.forEach(s => {
              sHtml += `<li class="p-3 bg-slate-900 border border-slate-800/80 rounded-lg flex justify-between items-center hover:border-purple-500/30 transition-premium">
                <div>
                  <p class="font-semibold text-slate-200 text-sm">${s.name}</p>
                  <p class="text-sm font-mono text-slate-400 mt-0.5">${s.reg_no}</p>
                </div>
                <button onclick="confirmRemoveStudent(this, '${s.reg_no}')" class="text-sm font-semibold text-rose-450 hover:text-white hover:bg-rose-600 px-2.5 py-1 rounded transition-premium cursor-pointer border border-rose-500/20">Remove</button>
              </li>`;
            });
            document.getElementById('roomStudentsList').innerHTML = sHtml;

            // Render Logs (Foldable Table)
            let lHtml = '';
            if (r.logs.length === 0) lHtml = '<tr><td colspan="6" class="p-4 text-center text-slate-500 text-sm">No sessions logged yet.</td></tr>';
            r.logs.forEach((l, idx) => {
              let attCount = (l.attendance_data || []).length;
              lHtml += `
                <tr class="hover:bg-slate-900/50 transition-colors cursor-pointer" onclick="toggleLogDetails(${idx})">
                  <td class="p-3 w-8 text-center text-slate-500"><span id="logIcon_${idx}" class="material-symbols-rounded text-sm transition-transform">expand_more</span></td>
                  <td class="p-3 font-semibold text-blue-400">${l.session_date}</td>
                  <td class="p-3 text-slate-300">${l.start_time || '--:--'}</td>
                  <td class="p-3 text-slate-400">${l.duration_minutes}m</td>
                  <td class="p-3 text-slate-300 truncate max-w-[150px]" title="${l.topic_covered}">${l.topic_covered || 'No topic specified'}</td>
                  <td class="p-3 text-right"><span class="text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded font-semibold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>${attCount} Present</span></td>
                </tr>
                <tr id="logDetails_${idx}" class="hidden bg-slate-950/40">
                  <td colspan="6" class="p-4 border-t border-slate-800/60">
                    <p class="text-sm font-semibold text-slate-400 uppercase mb-2">Students Present:</p>
                    <div class="flex flex-wrap gap-2">
                      ${(l.attendance_data||[]).map(reg => {
                        let st = r.students.find(s => s.reg_no === reg);
                        let nameToShow = st ? st.name : reg;
                        return `<span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded text-sm text-slate-300 font-medium">${nameToShow}</span>`;
                      }).join('')}
                    </div>
                  </td>
                </tr>
              `;
            });
            document.getElementById('roomLogsList').innerHTML = lHtml;

            // Prep Attendance form
            let attHtml = '';
            r.students.forEach(s => {
              attHtml += `<label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="${s.reg_no}" class="log-att-checkbox w-3 h-3 text-emerald-500 rounded bg-slate-900 border-slate-700" checked><span class="text-sm text-slate-400">${s.reg_no} - ${s.name}</span></label>`;
            });
            document.getElementById('logAttendanceGrid').innerHTML = attHtml;
            document.getElementById('logDate').valueAsDate = new Date();

            loadAssessments();

            document.getElementById('roomModal').classList.remove('hidden');
          }
        });
    }

    function toggleLogDetails(idx) {
      const el = document.getElementById(`logDetails_${idx}`);
      const icon = document.getElementById(`logIcon_${idx}`);
      if(el.classList.contains('hidden')){
        el.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        el.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function toggleStudents() {
      const content = document.getElementById('studentsContent');
      const icon = document.getElementById('studentsIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function switchRoomTab(tabId) {
      document.getElementById('rpanel_logs').classList.add('hidden');
      document.getElementById('rpanel_assessments').classList.add('hidden');
      
      document.getElementById('rtab_logs').className = "px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer";
      document.getElementById('rtab_assessments').className = "px-5 py-2.5 rounded-lg text-sm font-semibold bg-slate-900/60 text-slate-400 border border-slate-800/80 hover:bg-slate-800/50 hover:text-slate-200 transition-premium cursor-pointer";

      document.getElementById('rpanel_' + tabId).classList.remove('hidden');
      
      let tabColor = tabId === 'logs' ? 'blue' : 'amber';
      document.getElementById('rtab_' + tabId).className = `px-5 py-2.5 rounded-lg text-sm font-semibold bg-${tabColor}-500/10 text-${tabColor}-405 border border-${tabColor}-500/20 hover:border-${tabColor}-500/30 transition-premium cursor-pointer`;
    }

    function closeRoomModal() {
      document.getElementById('roomModal').classList.add('hidden');
    }

    function confirmRemoveStudent(btn, regNo) {
      if (btn.innerText === "Remove") {
        btn.innerText = "Confirm?";
        btn.classList.add('bg-rose-600', 'text-white');
        setTimeout(() => {
          if (btn && btn.innerText === "Confirm?") {
            btn.innerText = "Remove";
            btn.classList.remove('bg-rose-600', 'text-white');
          }
        }, 3000);
      } else {
        removeStudent(regNo);
      }
    }

    function removeStudent(regNo) {
      fetch(`/api/remedial/rooms/${currentRoomId}/students`, {
        method: 'DELETE',
        headers: headers,
        body: JSON.stringify({ reg_no: regNo })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') openRoom(currentRoomId);
      });
    }

    function toggleLogForm() {
      document.getElementById('logFormContainer').classList.toggle('hidden');
    }

    function saveLog() {
      const date = document.getElementById('logDate').value;
      const start = document.getElementById('logStartTime').value;
      const duration = document.getElementById('logDuration').value;
      const topic = document.getElementById('logTopic').value;
      const att = Array.from(document.querySelectorAll('.log-att-checkbox:checked')).map(cb => cb.value);

      if (!date || !topic) return alert('Date and Topic are required.');

      fetch(`/api/remedial/rooms/${currentRoomId}/logs`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          session_date: date,
          start_time: start,
          duration_minutes: duration,
          topic_covered: topic,
          attendance: att
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          openRoom(currentRoomId);
        }
      });
    }

    let currentAssessments = [];
    let currentAssessmentId = null;

    function loadAssessments() {
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAssessments = data.assessments;
            const container = document.getElementById('assessmentsList');
            let html = '';
            if (currentAssessments.length === 0) html = '<p class="text-slate-500 text-sm">No assessments created yet.</p>';
            
            currentAssessments.forEach((a, idx) => {
              let gradedCount = (a.scores || []).length;
              html += `
                <div class="bg-slate-900 border border-slate-800/80 rounded-lg p-4 flex justify-between items-center hover:border-amber-500/30 transition-premium">
                  <div>
                    <span class="px-2.5 py-0.5 rounded border text-sm font-semibold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20 mb-2 inline-block">${a.type}</span>
                    <h5 class="text-sm font-semibold text-white">${a.title}</h5>
                    <p class="text-sm text-slate-400 font-mono mt-1">Max Marks: ${a.max_marks} | Graded: ${gradedCount}/${currentRoomStudents.length}</p>
                  </div>
                  <button onclick="openGradebook(${idx})" class="bg-slate-850 hover:bg-slate-800 text-white rounded-lg font-semibold text-sm px-4 py-2 transition-premium border border-slate-700/60 shadow-md cursor-pointer">Enter Marks</button>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    function toggleAssessmentForm() {
      document.getElementById('assessmentFormContainer').classList.toggle('hidden');
      toggleAssessFormFields();
    }

    function toggleAssessFormFields() {
      const type = document.getElementById('assessType').value;
      const coCont = document.getElementById('assessCOContainer');
      const linkCont = document.getElementById('assessLinkContainer');
      const marksCont = document.getElementById('assessMaxMarksContainer');

      if (type === 'Online Test') {
        coCont.classList.add('hidden');
        linkCont.classList.remove('hidden');
        marksCont.classList.add('hidden');
      } else if (type === 'Written Test') {
        coCont.classList.remove('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      } else {
        coCont.classList.add('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      }
    }

    function saveAssessment() {
      const type = document.getElementById('assessType').value;
      const marks = document.getElementById('assessMaxMarks').value;
      const title = document.getElementById('assessTitle').value;
      const link = document.getElementById('assessLinkTest').value;

      if (!title) return alert('Title is required.');

      let coStructure = null;
      if (type === 'Written Test') {
        coStructure = {};
        let hasCo = false;
        ['co1','co2','co3','co4','co5'].forEach(co => {
          let v = document.getElementById(co+'_marks').value;
          if(v) { coStructure[co.toUpperCase()] = parseFloat(v); hasCo = true; }
        });
        if(!hasCo) coStructure = null;
      }

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ 
          type: type, 
          max_marks: type === 'Online Test' ? 100 : marks, 
          title: title,
          linked_test_id: type === 'Online Test' ? link : null,
          co_structure: coStructure
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          document.getElementById('assessTitle').value = '';
          document.getElementById('assessmentFormContainer').classList.add('hidden');
          loadAssessments();
        }
      });
    }

    function openGradebook(idx) {
      const a = currentAssessments[idx];
      currentAssessmentId = a.assessment_id;
      
      document.getElementById('gradebookTitle').innerText = a.title;
      document.getElementById('gradebookSub').innerText = `${a.type} - Max Marks: ${a.max_marks}`;
      
      const isOnline = a.type === 'Online Test';
      const hasCOs = a.co_structure && Object.keys(a.co_structure).length > 0;
      
      // Controls
      if (isOnline) {
        document.getElementById('btnSyncScores').classList.remove('hidden');
        document.getElementById('btnSaveScores').classList.add('hidden');
      } else {
        document.getElementById('btnSyncScores').classList.add('hidden');
        document.getElementById('btnSaveScores').classList.remove('hidden');
      }

      // Headers
      let headHtml = '<th class="p-3 w-12">S.No.</th><th class="p-3">Name</th><th class="p-3 w-28">Admission No</th><th class="p-3 w-32">SBTE Reg No</th>';
      if (hasCOs && !isOnline) {
        Object.keys(a.co_structure).forEach(co => {
          headHtml += `<th class="p-3 w-16 text-center">${co} (${a.co_structure[co]})</th>`;
        });
      }
      headHtml += `<th class="p-3 w-24 text-right">Total Score</th>`;
      document.getElementById('gradebookTableHead').innerHTML = headHtml;

      // Build Score Map for fast lookup
      let scoreMap = {};
      if(a.scores) a.scores.forEach(s => { scoreMap[s.reg_no] = { score: s.score, cos: s.co_scores || {} }; });

      let bodyHtml = '';
      currentRoomStudents.forEach((s, index) => {
        let sc = scoreMap[s.reg_no] || { score: '', cos: {} };
        
        bodyHtml += `<tr class="hover:bg-slate-900/50 transition-colors">
            <td class="p-3 text-sm text-slate-500 font-semibold">${index + 1}</td>
            <td class="p-3 text-sm font-semibold text-slate-200">${s.name}</td>
            <td class="p-3 text-sm text-slate-400 font-mono">${s.reg_no}</td>
            <td class="p-3 text-sm text-slate-400 font-mono">${s.sbte_reg_no || '-'}</td>`;
        
        if (hasCOs && !isOnline) {
          Object.keys(a.co_structure).forEach(co => {
            let val = sc.cos[co] !== undefined ? sc.cos[co] : '';
            bodyHtml += `<td class="p-3 text-center"><input type="number" data-reg="${s.reg_no}" data-co="${co}" value="${val}" max="${a.co_structure[co]}" class="co-input w-12 bg-slate-950 border border-slate-800 rounded px-1 py-1 text-sm text-white outline-none focus:border-amber-500 text-center"></td>`;
          });
        }

        let inputAttr = isOnline ? 'disabled' : '';
        let classStr = isOnline ? 'w-20 bg-slate-800 text-emerald-400 font-bold border-transparent' : 'score-input w-20 bg-slate-950 border border-slate-800 focus:border-amber-500';

        bodyHtml += `<td class="p-3 text-right">
              <input type="number" data-reg="${s.reg_no}" value="${sc.score}" max="${a.max_marks}" class="${classStr} rounded-lg px-3 py-1.5 text-sm text-white outline-none text-center" ${inputAttr}>
            </td>
          </tr>`;
      });
      
      document.getElementById('gradebookTableBody').innerHTML = bodyHtml;
      document.getElementById('gradebookContainer').classList.remove('hidden');
    }

    function closeGradebook() {
      document.getElementById('gradebookContainer').classList.add('hidden');
      currentAssessmentId = null;
    }

    function syncOnlineScores() {
      if(!currentAssessmentId) return;
      document.getElementById('btnSyncScores').innerText = "Syncing...";
      
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/sync`, {
        method: 'POST',
        headers: headers
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('btnSyncScores').innerText = "Sync Online Scores";
        if(data.status === 'SUCCESS') {
          loadAssessments();
          setTimeout(() => openGradebook(currentAssessments.findIndex(a => a.assessment_id === currentAssessmentId)), 500);
        } else {
          alert(data.message || 'Error syncing');
        }
      });
    }

    function saveScores() {
      if(!currentAssessmentId) return;
      
      let payloadMap = {};
      
      // Collect Total Scores
      document.querySelectorAll('.score-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {} };
          payloadMap[reg].score = parseFloat(inp.value);
        }
      });

      // Collect CO Scores
      document.querySelectorAll('.co-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          let co = inp.getAttribute('data-co');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {}, score: 0 };
          payloadMap[reg].co_scores[co] = parseFloat(inp.value);
        }
      });

      let payload = Object.values(payloadMap);

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/scores`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ scores: payload })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Scores Saved!');
          closeGradebook();
          loadAssessments();
        }
      });
    }

    function printRemedialReport() {
      if (!currentRoomId || !currentAssessmentId) return;
      window.open(`/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/report`, '_blank');
    }

    function printRemedialAttendanceReport() {
      if (!currentRoomId) return;
      window.open(`/remedial/rooms/${currentRoomId}/attendance/report`, '_blank');
    }

    function printRemedialAnalysisReport() {
      if (!currentRoomId) return;
      window.open(`/remedial/rooms/${currentRoomId}/analysis/report`, '_blank');
    }

    function confirmDeleteRoom(btn) {
      if (btn.innerText.includes("Delete Room")) {
        btn.innerHTML = `<span class="material-symbols-rounded text-sm">warning</span> Confirm?`;
        btn.className = "bg-rose-600 hover:bg-rose-500 border border-rose-500 text-white px-4 py-2 rounded-xl font-bold text-sm transition-premium flex items-center gap-1.5";
        setTimeout(() => {
          if (btn && btn.innerText.includes("Confirm")) {
            btn.innerHTML = `<span class="material-symbols-rounded text-sm">delete</span> Delete Room`;
            btn.className = "bg-rose-500/15 hover:bg-rose-600/30 border border-rose-500/30 hover:border-rose-500 text-rose-400 hover:text-rose-300 px-4 py-2 rounded-xl font-bold text-sm transition-premium flex items-center gap-1.5";
          }
        }, 4000);
      } else {
        deleteRoom();
      }
    }

    function deleteRoom() {
      if (!currentRoomId) return;
      fetch(`/api/remedial/rooms/${currentRoomId}`, {
        method: 'DELETE',
        headers: headers
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Room deleted successfully!');
          closeRoomModal();
          loadRooms();
        } else {
          alert(data.message || 'Error deleting room');
        }
      });
    }

    function updateRoomStatus() {
      if (!currentRoomId) return;
      const status = document.getElementById('modalRoomStatus').value;
      fetch(`/api/remedial/rooms/${currentRoomId}/status`, {
        method: 'PATCH',
        headers: headers,
        body: JSON.stringify({ status: status })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          loadRooms();
          const selectEl = document.getElementById('modalRoomStatus');
          if (status === 'archived') {
            selectEl.className = "bg-transparent text-sm font-semibold text-amber-500 focus:outline-none border-none cursor-pointer";
          } else {
            selectEl.className = "bg-transparent text-sm font-semibold text-emerald-400 focus:outline-none border-none cursor-pointer";
          }
        } else {
          alert(data.message || 'Error updating status');
        }
      });
    }

    function addStudentToRoom() {
      if (!currentRoomId) return;
      const regNo = document.getElementById('addStudentRegNo').value.trim();
      if (!regNo) return alert('Enter a valid registration number');

      fetch(`/api/remedial/rooms/${currentRoomId}/students`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ reg_no: regNo })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          document.getElementById('addStudentRegNo').value = '';
          openRoom(currentRoomId);
        } else {
          alert(data.message || 'Error adding student');
        }
      });
    }
  </script>
</body>
</html>
