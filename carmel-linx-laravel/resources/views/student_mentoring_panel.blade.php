<div id="panelMentoring" class="hidden fade-up space-y-6">
  
  <!-- Student Quick Info Header Card -->
  <div class="bg-gradient-to-r from-slate-950/60 to-indigo-950/20 border border-slate-800/80 p-5 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-5 shadow-xl fade-up">
    <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left w-full md:w-auto">
      <div class="flex-shrink-0">
        <div id="diaryStudentPhotoContainer">
          <!-- Student View: Direct session photo or fallback -->
          @if(session('userPhoto'))
            <img src="{{ session('userPhoto') }}" class="w-16 h-16 rounded-2xl border-2 border-indigo-500/40 object-cover shadow-2xl">
          @else
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center font-black text-xl text-white shadow-lg border border-indigo-500/30">
              {{ strtoupper(substr(session('userName', 'S'), 0, 2)) }}
            </div>
          @endif
        </div>
      </div>
      <div class="flex-grow space-y-1">
        <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-2">
          <h2 class="font-black text-white text-lg tracking-tight" id="diaryHeaderStudentName">
            {{ session('userName') }}
          </h2>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] text-xs font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 self-center">
            Active Student
          </span>
        </div>
        <div class="flex flex-wrap justify-center sm:justify-start items-center gap-x-4 gap-y-1 text-xs text-slate-400 font-semibold">
          <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-sm text-indigo-400">badge</span> <span id="diaryHeaderStudentSbteLabel">{{ session('sbteRegNo') ? 'PRN No:' : 'Reg No:' }}</span> <strong class="text-slate-200 font-mono" id="diaryHeaderStudentSbteNo">{{ session('sbteRegNo') ?: session('userId') }}</strong></span>
          <span class="hidden sm:inline text-slate-600">&bull;</span>
          <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-sm text-indigo-400">auto_stories</span> Sem: <strong class="text-slate-200" id="diaryHeaderStudentSem">S{{ session('userSemester', session('semester', '1')) }}</strong></span>
          <span class="hidden sm:inline text-slate-600">&bull;</span>
          <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-sm text-indigo-400">school</span> Branch: <strong class="text-slate-200" id="diaryHeaderStudentBranch">{{ session('userBranch', '-') }}</strong></span>
          <span class="hidden sm:inline text-slate-600">&bull;</span>
          <span class="flex items-center gap-1.5"><span class="material-symbols-rounded text-sm text-indigo-400">meeting_room</span> Batch: <strong class="text-slate-200" id="diaryHeaderStudentBatch">{{ session('classroomId', '-') }}</strong></span>
        </div>
      </div>
    </div>
    <!-- Quick Action Buttons -->
    <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto justify-center sm:justify-end border-t md:border-t-0 border-slate-800/80 pt-3 md:pt-0">
      <button onclick="downloadMentoringPdf()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow border border-slate-700 text-[10px] text-xs">
        <span class="material-symbols-rounded text-sm">download</span> Download PDF
      </button>
      <button onclick="saveStudentMentoringData()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-md text-[10px] text-xs">
        <span class="material-symbols-rounded text-sm">save</span> Save Changes
      </button>
    </div>
  </div>

  <!-- Mentoring Horizontal Tabs Header -->
  <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-2 flex items-center gap-2 overflow-x-auto no-scrollbar shadow-inner">
    <button onclick="switchStudentMentoringTab('smdProfile')" id="tabBtn_smdProfile" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab bg-slate-800/80 text-blue-400 text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">person</span> Personal Info
    </button>
    <button onclick="switchStudentMentoringTab('smdFamily')" id="tabBtn_smdFamily" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">family_restroom</span> Family Details
    </button>
    <button onclick="switchStudentMentoringTab('smdEducation')" id="tabBtn_smdEducation" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">school</span> Prior Education
    </button>
    <button onclick="switchStudentMentoringTab('smdAcademic')" id="tabBtn_smdAcademic" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">monitoring</span> Academic Progress
    </button>
    <button onclick="switchStudentMentoringTab('smdBoard')" id="tabBtn_smdBoard" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">assignment</span> Board Exams
    </button>
    <button onclick="switchStudentMentoringTab('smdExtra')" id="tabBtn_smdExtra" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">workspace_premium</span> Extracurricular
    </button>
    <button onclick="switchStudentMentoringTab('smdLeave')" id="tabBtn_smdLeave" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">event_busy</span> Leave Records
    </button>
    <button onclick="switchStudentMentoringTab('smdMeetings')" id="tabBtn_smdMeetings" class="px-4 py-2.5 font-bold rounded-xl transition-premium smd-tab text-slate-400 hover:bg-slate-900/60 hover:text-white text-xs whitespace-nowrap cursor-pointer flex items-center gap-2">
      <span class="material-symbols-rounded text-base">forum</span> Mentor Meetings
    </button>
  </div>

  <!-- Full-Width Tab Content -->
  <div class="w-full bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 min-h-[400px]">
      
      <!-- Personal Info Tab -->
      <div id="smdProfile" class="smd-content-pane space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Additional Personal Info</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Annual Income</label>
            <input type="text" id="smd_annual_income" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. ₹2,00,000">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Residential Status</label>
            <select id="smd_residential_status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
              <option value="Day Scholar">Day Scholar</option>
              <option value="Hosteller">Hosteller</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Scholarships (if any)</label>
            <input type="text" id="smd_scholarships" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm" placeholder="e.g. E-Grantz">
          </div>
          <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" id="smd_fee_waiver" class="rounded bg-slate-900 border-slate-700 text-blue-500 focus:ring-blue-500 focus:ring-2">
            <label class="text-slate-300 font-bold text-sm">Fee Waiver Student</label>
          </div>
        </div>

        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 mt-8 text-sm">Guardian Details</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Guardian Name</label>
            <input type="text" id="smd_guardian_name" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Relationship</label>
            <input type="text" id="smd_guardian_relationship" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div>
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Mobile No</label>
            <input type="text" id="smd_guardian_mobile" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 text-sm">
          </div>
          <div class="md:col-span-2">
            <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-sm">Permanent Address</label>
            <textarea id="smd_guardian_address" rows="3" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-3 py-2 text-slate-200 outline-none focus:border-blue-500 resize-none text-sm"></textarea>
          </div>
        </div>
      </div>

      <!-- Family Details Tab -->
      <div id="smdFamily" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Family Members</h4>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Name</th>
                <th class="p-3">Relationship</th>
                <th class="p-3">Education</th>
                <th class="p-3">Occupation</th>
                <th class="p-3">Contact</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody id="smdFamilyList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
        <button onclick="addFamilyRow()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded font-bold cursor-pointer text-sm">+ Add Family Member</button>
      </div>

      <!-- Prior Education Tab -->
      <div id="smdEducation" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Educational Background</h4>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Course/Standard</th>
                <th class="p-3">Institution</th>
                <th class="p-3">Year</th>
                <th class="p-3">Total % / Grade</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody id="smdEducationList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
        <button onclick="addEducationRow()" class="mt-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded font-bold cursor-pointer text-sm">+ Add Education Record</button>
      </div>

      <!-- Academic Progress Tab -->
      <div id="smdAcademic" class="smd-content-pane hidden space-y-4">
        <h4 class="font-bold text-white border-b border-slate-800/60 pb-2 mb-4 text-sm">Internal Progress Report</h4>
        <p class="text-slate-400 mb-4 text-sm">These marks are generated automatically from your classroom assessments.</p>
        <div id="smdAcademicReport" class="space-y-6">
          <!-- JS rendered academic tables (CO tests, assignments) -->
        </div>
      </div>

      <!-- Board Exams Tab -->
      <div id="smdBoard" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-center border-b border-slate-800/60 pb-2 mb-4">
          <h4 class="text-sm font-bold text-white">Board Exam Results</h4>
          <div class="flex items-center gap-2">
            <label class="text-sm text-slate-400 font-bold uppercase tracking-wider">Select Semester:</label>
            <select id="smdBoardSemSelect" class="bg-slate-900 border border-slate-700 rounded px-3 py-1.5 text-sm text-white font-bold outline-none focus:border-blue-500" onchange="renderStudentBoardExams()">
              <option value="">-- Choose --</option>
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>
        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-xs border-collapse min-w-[700px]">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60 uppercase tracking-wider text-xs font-bold">
                <th class="p-3 w-28">Sub Code</th>
                <th class="p-3">Subject Name</th>
                <th class="p-3 w-36">Exam Month/Yr</th>
                <th class="p-3 w-20">Grade</th>
                <th class="p-3 w-24">Passed</th>
                <th class="p-3 w-24">Chances</th>
              </tr>
            </thead>
            <tbody id="smdSubjectBoardList">
              <tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>
            </tbody>
          </table>
        </div>
        <div class="flex justify-end mt-4">
          <button onclick="saveStudentMentoringData()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-2 shadow-lg text-sm">
            <span class="material-symbols-rounded text-sm">save</span> Save Board Exam Results
          </button>
        </div>
      </div>

      <!-- Extracurricular Tab -->
      <div id="smdExtra" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <h4 class="text-xs font-bold text-white">Extracurricular Achievements</h4>
            <button onclick="openStudentActivityModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1"><span class="material-symbols-rounded text-xs">add</span> Add Activity</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="text-xs font-black text-slate-200">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="studentActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between text-xs font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="text-base font-black text-amber-400" id="studentTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="studentActivitySplitList">
                <div class="text-xs text-slate-500 py-1">Loading...</div>
              </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-800/60">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-900/40 text-slate-400 border-b border-slate-800/60">
                <th class="p-3">Sem</th>
                <th class="p-3 w-1/3">Activity Name</th>
                <th class="p-3">Level / Segment</th>
                <th class="p-3">Pts Claimed</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Action</th>
              </tr>
            </thead>
            <tbody id="smdExtraList">
              <!-- JS rendered -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Leave Records Tab -->
      <div id="smdLeave" class="smd-content-pane hidden space-y-4">
        <div class="flex justify-between items-center border-b border-slate-800/60 pb-2 mb-4">
            <h4 class="font-bold text-white text-sm">Leave Records</h4>
            <button onclick="openLeaveModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-sm">
              <span class="material-symbols-rounded text-sm">add</span> Log Leave
            </button>
        </div>
        <div class="overflow-x-auto bg-slate-900/50 border border-slate-700 rounded-xl">
          <table class="w-full text-left text-sm">
            <thead class="bg-slate-800/80 text-slate-400 font-black uppercase">
              <tr>
                <th class="p-3">Semester</th>
                <th class="p-3">Date</th>
                <th class="p-3">Reason</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="smdLeavesTable" class="text-slate-300">
              <tr><td colspan="5" class="p-6 text-center text-slate-500">No leave records.</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Mentor Meetings Tab -->
      <div id="smdMeetings" class="smd-content-pane hidden space-y-4">
        <h4 class="text-sm font-bold text-white border-b border-slate-800/60 pb-2 mb-4">Mentor Remarks</h4>
        <p class="text-sm text-slate-400 mb-4">These logs are maintained by your mentor.</p>
        <div id="smdMeetingsList" class="space-y-4">
          <!-- JS rendered meetings -->
        </div>
      </div>

    </div>
</div>

<!-- STUDENT ACTIVITY MODAL -->
<div id="addStudentActivityModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
  <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xs font-black text-white" id="studentActivityModalTitle">Add Activity</h3>
      <button onclick="closeStudentActivityModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
    </div>
    <form id="studentActivityForm" onsubmit="saveStudentActivity(event)">
      <input type="hidden" id="studentActivityId">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-bold text-slate-400 mb-1">Semester</label>
          <select id="studentActivitySemester" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-bold text-slate-400 mb-1">Segment</label>
          <select id="studentActivitySegment" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
            <option value="NCC">NCC</option>
            <option value="NSS">NSS</option>
            <option value="Sports & Games">Sports & Games</option>
            <option value="Cultural Activities">Cultural Activities</option>
            <option value="Professional Self Initiatives">Prof. Self Initiatives</option>
            <option value="Entrepreneurship and Innovation">Entrepreneurship & Innovation</option>
            <option value="Leadership & Management">Leadership & Management</option>
            <option value="Disaster Management">Disaster Management</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-400 mb-1">Activity Name</label>
          <input type="text" id="studentActivityName" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-400 mb-1">Level (e.g. State, College)</label>
          <input type="text" id="studentActivityLevel" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-400 mb-1">Points Claimed</label>
          <input type="number" id="studentActivityPtsClaimed" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500">
        </div>
        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-xs">Submit Activity for Verification</button>
      </div>
    </form>
  </div>
</div>

<!-- ADD LEAVE MODAL -->
<div id="addLeaveModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
  <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
    <div class="flex justify-between items-center mb-6">
      <h3 class="font-black text-white text-lg" id="leaveModalTitle">Add Leave Record</h3>
      <button onclick="closeLeaveModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
    </div>
    <form id="leaveForm" onsubmit="saveLeave(event)">
      <input type="hidden" id="leaveId">
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-sm">Semester</label>
            <input type="number" id="leaveSem" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-sm">From Date</label>
            <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-sm">To Date</label>
            <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-sm">No. of Days</label>
            <input type="number" step="0.5" id="leaveDays" placeholder="e.g. 1, 0.5" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
        </div>
        <div>
          <label class="block font-bold text-slate-400 mb-1 text-sm">Reason</label>
          <input type="text" id="leaveReason" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
        </div>
        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-600/20">Save Leave Record</button>
      </div>
    </form>
  </div>
</div>
