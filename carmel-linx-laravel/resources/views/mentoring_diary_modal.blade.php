<!-- FULL MENTORING DIARY MODAL -->
<div id="fullMentoringDiaryModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[60] hidden items-center justify-center p-4 transition-premium overflow-hidden">
  <div class="bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-7xl h-[95vh] flex flex-col shadow-2xl relative">
    
    <!-- Header -->
    <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-950/80 rounded-t-3xl">
      <div class="flex items-center gap-4">
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-2 rounded-xl shadow-lg">
          <span class="material-symbols-rounded text-2xl">menu_book</span>
        </div>
        <div>
          <h2 class="font-black text-white tracking-tight flex items-center gap-2 text-xl">
            Student Mentoring Diary
            <span id="fmdStatusBadge" class="px-2 py-0.5 rounded font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
          </h2>
          <p class="text-slate-400 font-mono mt-0.5 text-[10px] text-xs">
            <span id="fmdStudentName" class="font-bold text-slate-200">Loading...</span> | 
            <span id="fmdStudentReg" class="text-indigo-400">Loading...</span>
          </p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <button id="fmdVerifyBtn" onclick="verifyStudentData()" class="px-3 py-1.5 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white border border-green-500/30 rounded-xl font-bold transition-premium flex items-center gap-1.5 cursor-pointer text-[10px] text-xs">
          <span class="material-symbols-rounded text-sm">verified</span> Verify Data
        </button>
        <button onclick="downloadMentorPdf()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-premium flex items-center gap-1.5 cursor-pointer border border-slate-700 text-[10px] text-xs">
          <span class="material-symbols-rounded text-sm">print</span> Print Diary PDF
        </button>
        <button onclick="closeFullMentoringDiaryModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer p-2 bg-slate-800/50 rounded-full hover:bg-slate-700">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
    </div>

    <!-- Body Layout -->
    <div class="flex flex-1 overflow-hidden">
      
      <!-- Sidebar Navigation (7 Tabs) -->
      <div class="w-64 bg-slate-950/50 border-r border-slate-800 p-4 flex flex-col gap-2 overflow-y-auto scrollbar-hidden shrink-0">
        <button onclick="switchDiaryTab('tab-profile')" id="btn-tab-profile" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">person</span> Personal & Family
        </button>
        <button onclick="switchDiaryTab('tab-education')" id="btn-tab-education" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">history_edu</span> Prior Education
        </button>
        <button onclick="switchDiaryTab('tab-academic')" id="btn-tab-academic" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">school</span> Academic Progress
        </button>
        <button onclick="switchDiaryTab('tab-board')" id="btn-tab-board" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">workspace_premium</span> Board Exams
        </button>
        <button onclick="switchDiaryTab('tab-extracurricular')" id="btn-tab-extracurricular" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">emoji_events</span> Extra-Curricular
        </button>
        <button onclick="switchDiaryTab('tab-meetings')" id="btn-tab-meetings" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">forum</span> Mentor Meetings
        </button>
        <button onclick="switchDiaryTab('tab-leaves')" id="btn-tab-leaves" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">event_busy</span> Leave Record
        </button>
        <button onclick="switchDiaryTab('tab-discipline')" id="btn-tab-discipline" class="diary-tab-btn w-full text-left px-4 py-3 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 text-[10px] text-xs">
          <span class="material-symbols-rounded text-lg">gavel</span> Disciplinary Actions
        </button>
      </div>

      <!-- Content Area -->
      <div class="flex-1 overflow-y-auto p-6 bg-slate-900 scrollbar-hidden relative">
        
        <!-- Loading Overlay for Data -->
        <div id="fmdLoading" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm flex items-center justify-center z-10 hidden">
          <div class="w-8 h-8 border-4 border-slate-700 border-t-indigo-500 rounded-full animate-spin"></div>
        </div>

        <!-- TAB 1: Personal & Family Profile -->
        <div id="tab-profile" class="diary-tab block space-y-6">
          <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Personal & Family Details</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 2 of physical diary</p>
            </div>
          </div>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-5 space-y-4">
              <h4 class="font-bold text-indigo-400 border-b border-slate-800 pb-2 text-sm">Student Particulars</h4>
              <div class="space-y-3 text-[10px] text-xs">
                <div class="flex justify-between"><span class="text-slate-500">Annual Income:</span> <span id="fmdIncome" class="font-bold text-white">--</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Residential Status:</span> <span id="fmdResidence" class="font-bold text-white">--</span></div>
              </div>
            </div>
            <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-5 space-y-4">
              <h4 class="font-bold text-indigo-400 border-b border-slate-800 pb-2 text-sm">Local Guardian (If Hosteller)</h4>
              <div class="space-y-3 text-[10px] text-xs">
                <div class="flex justify-between"><span class="text-slate-500">Name:</span> <span id="fmdGuardName" class="font-bold text-white">--</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Relation:</span> <span id="fmdGuardRel" class="font-bold text-white">--</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Mobile:</span> <span id="fmdGuardPhone" class="font-bold text-white">--</span></div>
                <div class="flex flex-col gap-1 mt-2">
                  <span class="text-slate-500">Address:</span>
                  <p id="fmdGuardAddress" class="text-white p-2 bg-slate-900 rounded-lg border border-slate-800 min-h-[40px]">--</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-slate-800 bg-slate-900/50 flex justify-between items-center">
              <h4 class="font-bold text-slate-200 text-sm">Family Members</h4>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Relationship</th>
                    <th class="p-3">Education</th>
                    <th class="p-3">Occupation</th>
                    <th class="p-3">Contact No.</th>
                  </tr>
                </thead>
                <tbody id="fmdFamilyTable" class="text-slate-300">
                  <tr><td colspan="6" class="p-6 text-center text-slate-600">No family records found.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 2: Prior Education -->
        <div id="tab-education" class="diary-tab hidden space-y-6">
          <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Prior Education & Fees</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 3 of physical diary</p>
            </div>
          </div>

          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-slate-800 bg-slate-900/50">
              <h4 class="font-bold text-slate-200 text-sm">Courses Studied (SSLC / +2 / ITI)</h4>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3">Course</th>
                    <th class="p-3">Institution</th>
                    <th class="p-3">Year</th>
                    <th class="p-3">Maths</th>
                    <th class="p-3">Physics</th>
                    <th class="p-3">Chemistry</th>
                    <th class="p-3">Total %</th>
                  </tr>
                </thead>
                <tbody id="fmdEducationTable" class="text-slate-300">
                  <tr><td colspan="7" class="p-6 text-center text-slate-600">No prior education records found.</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
             <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-5 space-y-4">
              <h4 class="font-bold text-indigo-400 border-b border-slate-800 pb-2 text-sm">Scholarships & Grants</h4>
              <p id="fmdScholarships" class="text-white p-3 bg-slate-900 rounded-xl border border-slate-800 min-h-[60px] text-[10px] text-xs">--</p>
              <div class="flex items-center gap-3 mt-4 pt-4 border-t border-slate-800">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] text-xs">Tuition Fee Waiver Beneficiary:</span>
                <span id="fmdFeeWaiver" class="px-2 py-1 bg-slate-800 text-white rounded font-bold border border-slate-700 text-[10px] text-xs">No</span>
              </div>
            </div>
            
            <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden flex flex-col">
              <div class="p-4 border-b border-slate-800 bg-slate-900/50">
                <h4 class="font-bold text-slate-200 text-sm">Fee Payment History</h4>
              </div>
              <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-[10px] text-xs">
                  <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                    <tr>
                      <th class="p-3">Year</th>
                      <th class="p-3">Fees to Pay</th>
                      <th class="p-3">Amt Paid</th>
                      <th class="p-3">Date</th>
                    </tr>
                  </thead>
                  <tbody id="fmdFeesTable" class="text-slate-300">
                    <tr><td colspan="5" class="p-6 text-center text-slate-600">No fee records.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB 3: Academic Progress -->
        <div id="tab-academic" class="diary-tab hidden space-y-6">
           <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Academic Performance</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 4 of physical diary</p>
            </div>
          </div>
          
          <div id="mentorAcademicContainer" class="space-y-8">
            <div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-6 text-center text-slate-400 text-sm">
              Loading academic data...
            </div>
          </div>
        </div>

        
        <!-- TAB Board Exams -->
        <div id="tab-board" class="diary-tab hidden space-y-6">
           <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Board Exam Results</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Semester-wise university results</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="font-black text-slate-200 text-[10px] text-xs">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="mentorActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="font-black text-amber-400 text-2xl" id="mentorTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="mentorActivitySplitList">
                <div class="text-slate-500 py-1">Loading...</div>
              </div>
            </div>
          </div>

          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3">Semester</th>
                    <th class="p-3">SGPA</th>
                    <th class="p-3">CGPA (Cumulative)</th>
                    <th class="p-3">Activity Points</th>
                  </tr>
                </thead>
                <tbody id="fmdBoardTable" class="text-slate-300">
                  <tr><td colspan="5" class="p-6 text-center text-slate-600">No board exam records.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 4: Extra-Curricular -->
        <div id="tab-extracurricular" class="diary-tab hidden space-y-6">
           <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Extra-Curricular Activities</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 7 of physical diary</p>
            </div>
            <button onclick="openActivityModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs"><span class="material-symbols-rounded text-sm">add</span> Add Activity</button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="font-black text-slate-200 text-[10px] text-xs">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="mentorActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="font-black text-amber-400 text-2xl" id="mentorTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="mentorActivitySplitList">
                <div class="text-slate-500 py-1">Loading...</div>
              </div>
            </div>
          </div>

          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3">Sem</th>
                    <th class="p-3 w-1/3">Activity Name</th>
                    <th class="p-3">Achievement</th>
                    <th class="p-3">Pts</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Action</th>
                  </tr>
                </thead>
                <tbody id="fmdExtraTable" class="text-slate-300">
                  <tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 5: Mentor Meetings -->
        <div id="tab-meetings" class="diary-tab hidden space-y-6">
          <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Mentor-Mentee Meeting Details</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 10 of physical diary</p>
            </div>
            <button onclick="toggleDiaryAddForm()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-2 shadow-lg shadow-indigo-500/20 text-[10px] text-xs">
              <span class="material-symbols-rounded text-sm">edit_square</span> Log New Meeting
            </button>
          </div>

          <!-- Add Entry Form (Hidden by Default) -->
          <div id="fmdAddMeetingForm" class="hidden bg-indigo-950/20 border border-indigo-500/30 p-5 rounded-2xl space-y-4 mb-6">
            <h4 class="font-bold text-indigo-300 mb-2 text-sm">Record Meeting Log</h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-indigo-300/70 font-bold uppercase tracking-wider mb-1.5">Date</label>
                <input type="date" id="fmdMeetingDate" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-indigo-500 text-[10px] text-xs">
              </div>
              <div>
                <label class="block text-indigo-300/70 font-bold uppercase tracking-wider mb-1.5">Topic / Category</label>
                <input type="text" id="fmdMeetingCategory" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-indigo-500 text-[10px] text-xs" placeholder="e.g., Evaluation of Series Test">
              </div>
            </div>
            <div>
              <label class="block text-indigo-300/70 font-bold uppercase tracking-wider mb-1.5">Description</label>
              <textarea id="fmdMeetingNotes" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-indigo-500 text-[10px] text-xs" placeholder="Details of the discussion..."></textarea>
            </div>
            <div>
              <label class="block text-indigo-300/70 font-bold uppercase tracking-wider mb-1.5">Suggestions / Action Taken</label>
              <textarea id="fmdMeetingAction" rows="1" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-indigo-500 text-[10px] text-xs" placeholder="What actions were recommended..."></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
              <button onclick="toggleDiaryAddForm()" class="px-4 py-2 font-bold text-slate-400 hover:text-white transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
              <button onclick="submitNewMeeting()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-[10px] text-xs">Save Entry</button>
            </div>
          </div>

          <!-- Meeting Logs Table -->
          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3 w-24">Date</th>
                    <th class="p-3 w-40">Topic</th>
                    <th class="p-3">Description & Actions</th>
                    <th class="p-3 w-24 text-right">Status</th>
                  </tr>
                </thead>
                <tbody id="fmdMeetingTable" class="text-slate-300">
                  <tr><td colspan="5" class="p-6 text-center text-slate-600">No meeting records found.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 6: Leave Record -->
        <div id="tab-leaves" class="diary-tab hidden space-y-6">
           <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-slate-200 text-lg">Leave Record</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 13 of physical diary</p>
            </div>
            <button onclick="openLeaveModal()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">
              <span class="material-symbols-rounded text-sm">add</span> Log Leave
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="md:col-span-2 space-y-3">
              <h3 class="font-black text-slate-200 text-[10px] text-xs">Activity Points Tracker</h3>
              <div class="relative w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/60 shadow-inner">
                <div id="mentorActivityProgressBar" class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out" style="width: 0%"></div>
              </div>
              <div class="flex justify-between font-bold text-slate-500">
                <span>0</span>
                <span>Goal: 100</span>
              </div>
            </div>
            
            <div class="bg-slate-950/40 rounded-xl p-3 border border-slate-800/60 flex flex-col justify-between">
              <div class="text-right">
                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Verified Total</span>
                <span class="font-black text-amber-400 text-2xl" id="mentorTotalActivityPoints">0</span>
              </div>
              <div class="mt-2 border-t border-slate-800/40 pt-2" id="mentorActivitySplitList">
                <div class="text-slate-500 py-1">Loading...</div>
              </div>
            </div>
          </div>

          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-500 uppercase">
                  <tr>
                    <th class="p-3 w-24">Date</th>
                    <th class="p-3 w-1/2">Reason for Leave</th>
                    <th class="p-3 text-center">Parent Informed</th>
                    <th class="p-3 text-right">Status</th>
                  </tr>
                </thead>
                <tbody id="fmdLeavesTable" class="text-slate-300">
                  <tr><td colspan="5" class="p-6 text-center text-slate-600">No leave records.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 7: Disciplinary -->
        <div id="tab-discipline" class="diary-tab hidden space-y-6">
           <div class="flex justify-between items-end border-b border-slate-800 pb-3">
            <div>
              <h3 class="font-black text-red-400 text-lg">Disciplinary Actions</h3>
              <p class="text-slate-500 uppercase tracking-widest mt-1">Page 16 of physical diary</p>
            </div>
            <button onclick="openDiscModal()" class="px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white border border-red-500/30 rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">
              <span class="material-symbols-rounded text-sm">warning</span> Record Incident
            </button>
          </div>
          <div class="bg-red-950/10 border border-red-900/30 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-red-950/30 border-b border-red-900/50 text-red-300/70 uppercase">
                  <tr>
                    <th class="p-3 w-24">Date</th>
                    <th class="p-3">Incident Description</th>
                    <th class="p-3 w-1/3">Action Taken</th>
                  </tr>
                </thead>
                <tbody id="fmdDisciplineTable" class="text-slate-300">
                  <tr><td colspan="4" class="p-6 text-center text-slate-600">Clean disciplinary record.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script>
  let currentMentoringRegNo = '';

  function openFullMentoringDiaryModal(regNo, name) {
    currentMentoringRegNo = regNo;
    document.getElementById('fmdStudentName').innerText = name;
    document.getElementById('fmdStudentReg').innerText = regNo;
    
    // Show modal
    const modal = document.getElementById('fullMentoringDiaryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Switch to first tab
    switchDiaryTab('tab-profile');
    
    // Fetch all data
    fetchFullMentoringData(regNo);
  }

  // --- ACTIVITY FUNCTIONS ---
  function openActivityModal() {
    document.getElementById("activityForm").reset();
    document.getElementById("activityId").value = "";
    document.getElementById("activityModalTitle").innerText = "Add Activity";
    document.getElementById("addActivityModal").classList.remove("hidden");
    document.getElementById("addActivityModal").classList.add("flex");
  }
  function editActivity(act) {
    document.getElementById("activityId").value = act.id || "";
    document.getElementById("activitySegment").value = act.activity_segment || "NCC";
    document.getElementById("activityName").value = act.activity_name || "";
    document.getElementById("activityLevel").value = act.level || "";
    document.getElementById("activityPtsClaimed").value = act.points_claimed || 0;
    document.getElementById("activityPtsAwarded").value = act.points_awarded || 0;
    document.getElementById("activityStatus").value = act.status || "Pending";
    document.getElementById("activityModalTitle").innerText = "Edit Activity";
    document.getElementById("addActivityModal").classList.remove("hidden");
    document.getElementById("addActivityModal").classList.add("flex");
  }
  function closeActivityModal() {
    document.getElementById("addActivityModal").classList.add("hidden");
    document.getElementById("addActivityModal").classList.remove("flex");
  }
  function saveActivity(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("activityId").value,
      reg_no: currentMentoringRegNo,
      activity_segment: document.getElementById("activitySegment").value,
      activity_name: document.getElementById("activityName").value,
      level: document.getElementById("activityLevel").value,
      points_claimed: document.getElementById("activityPtsClaimed").value,
      points_awarded: document.getElementById("activityPtsAwarded").value,
      status: document.getElementById("activityStatus").value
    };
    fetch("/api/mentoring/extra-curricular/save", {
      method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(res => res.json()).then(resData => {
      if(resData.status === "SUCCESS") { closeActivityModal(); fetchFullMentoringData(currentMentoringRegNo); }
      else showGlobalMessage(resData.message, true);
    });
  }

  // --- LEAVE FUNCTIONS ---
  function openLeaveModal() {
    document.getElementById("leaveForm").reset();
    document.getElementById("leaveId").value = "";
    document.getElementById("leaveModalTitle").innerText = "Add Leave Record";
    document.getElementById("addLeaveModal").classList.remove("hidden");
    document.getElementById("addLeaveModal").classList.add("flex");
  }
  function editLeave(lv) {
    document.getElementById("leaveId").value = lv.id || "";
    document.getElementById("leaveSem").value = lv.semester || 1;
    if(document.getElementById("leaveDays")) document.getElementById("leaveDays").value = lv.no_of_days || "";
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");
        document.getElementById("leaveDateFrom").value = dates[0] || "";
        if(document.getElementById("leaveDateTo")) {
            document.getElementById("leaveDateTo").value = dates[1] || "";
        }
    }
    document.getElementById("leaveReason").value = lv.reason || "";
    document.getElementById("leaveStatus").value = lv.status || "Pending";
    document.getElementById("leaveParent").checked = lv.parent_informed ? true : false;
    document.getElementById("leaveModalTitle").innerText = "Edit Leave Record";
    document.getElementById("addLeaveModal").classList.remove("hidden");
    document.getElementById("addLeaveModal").classList.add("flex");
  }
  function closeLeaveModal() {
    document.getElementById("addLeaveModal").classList.add("hidden");
    document.getElementById("addLeaveModal").classList.remove("flex");
  }
  function saveLeave(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("leaveId").value,
      reg_no: currentMentoringRegNo,
      semester: document.getElementById("leaveSem").value,
      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      no_of_days: document.getElementById("leaveDays").value,
      reason: document.getElementById("leaveReason").value,
      status: document.getElementById("leaveStatus").value,
      parent_informed: document.getElementById("leaveParent").checked
    };
    fetch("/api/mentoring/leave/save", {
      method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(res => res.json()).then(resData => {
      if(resData.status === "SUCCESS") { closeLeaveModal(); fetchFullMentoringData(currentMentoringRegNo); }
      else showGlobalMessage(resData.message, true);
    });
  }

  // --- DISCIPLINARY FUNCTIONS ---
  function openDiscModal() {
    document.getElementById("discForm").reset();
    document.getElementById("discId").value = "";
    document.getElementById("discModalTitle").innerText = "Add Disciplinary Action";
    document.getElementById("addDisciplinaryModal").classList.remove("hidden");
    document.getElementById("addDisciplinaryModal").classList.add("flex");
  }
  function editDisc(dc) {
    document.getElementById("discId").value = dc.id || "";
    document.getElementById("discDate").value = dc.date || "";
    document.getElementById("discDesc").value = dc.description || "";
    document.getElementById("discAction").value = dc.action_taken || "";
    document.getElementById("discModalTitle").innerText = "Edit Disciplinary Action";
    document.getElementById("addDisciplinaryModal").classList.remove("hidden");
    document.getElementById("addDisciplinaryModal").classList.add("flex");
  }
  function closeDiscModal() {
    document.getElementById("addDisciplinaryModal").classList.add("hidden");
    document.getElementById("addDisciplinaryModal").classList.remove("flex");
  }
  function saveDisciplinary(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("discId").value,
      reg_no: currentMentoringRegNo,
      date: document.getElementById("discDate").value,
      description: document.getElementById("discDesc").value,
      action_taken: document.getElementById("discAction").value
    };
    fetch("/api/mentoring/disciplinary/save", {
      method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(res => res.json()).then(resData => {
      if(resData.status === "SUCCESS") { closeDiscModal(); fetchFullMentoringData(currentMentoringRegNo); }
      else showGlobalMessage(resData.message, true);
    });
  }

  function closeFullMentoringDiaryModal() {
    const modal = document.getElementById('fullMentoringDiaryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentMentoringRegNo = '';
  }

  function switchDiaryTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.diary-tab').forEach(el => {
      el.classList.add('hidden');
      el.classList.remove('block');
    });
    // Show target tab
    document.getElementById(tabId).classList.remove('hidden');
    document.getElementById(tabId).classList.add('block');
    
    // Update button styles
    document.querySelectorAll('.diary-tab-btn').forEach(btn => {
      btn.classList.remove('bg-indigo-600/20', 'text-indigo-400', 'border-indigo-500/30');
      btn.classList.add('text-slate-400');
      btn.classList.remove('border');
    });
    const activeBtn = document.getElementById('btn-' + tabId);
    if(activeBtn) {
      activeBtn.classList.remove('text-slate-400');
      activeBtn.classList.add('bg-indigo-600/20', 'text-indigo-400', 'border', 'border-indigo-500/30');
    }
  }

  function fetchFullMentoringData(regNo) {
    const loader = document.getElementById('fmdLoading');
    loader.classList.remove('hidden');

    fetch(`/api/mentoring/full-diary/${regNo}`)
      .then(res => res.json())
      .then(data => {
        loader.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          renderMentoringData(data);
        } else {
          showGlobalMessage(data.message || 'Error loading diary data.', true);
        }
      })
      .catch(err => {
        loader.classList.add('hidden');
        showGlobalMessage('Failed to fetch diary data.', true);
      });

    fetch(`/api/mentor/student/${regNo}/activity-summary`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          document.getElementById('mentorTotalActivityPoints').innerText = data.total_points || 0;
          let percent = Math.min(100, data.total_points || 0);
          document.getElementById('mentorActivityProgressBar').style.width = percent + '%';
          
          let splitHtml = '';
          if (data.split && Object.keys(data.split).length > 0) {
            for (const [segment, pts] of Object.entries(data.split)) {
              splitHtml += `
                <div class="flex justify-between items-center py-1">
                  <span class="text-slate-400">${segment}</span>
                  <span class="font-bold text-emerald-400 text-[10px] text-xs">${pts}</span>
                </div>
              `;
            }
          } else {
            splitHtml = '<div class="text-slate-500 py-1">No verified points yet.</div>';
          }
          document.getElementById('mentorActivitySplitList').innerHTML = splitHtml;
        }
      });
  }

  function renderMentoringData(data) {
    // Profile Tab
        if (data.student) {
      if (document.getElementById('fmdStudentName')) {
        document.getElementById('fmdStudentName').innerText = data.student.name || currentMentoringRegNo;
      }
      if (document.getElementById('fmdStudentReg')) {
        const regText = data.student.sbte_reg_no ? `PRN: ${data.student.sbte_reg_no}` : `Reg: ${data.student.reg_no || currentMentoringRegNo}`;
        const semText = data.student.semester ? ` | Sem: S${data.student.semester}` : '';
        document.getElementById('fmdStudentReg').innerText = `${regText}${semText}`;
      }
      if (data.student.profile_verified_at) {
        document.getElementById('fmdVerifyBtn').innerHTML = '<span class="material-symbols-rounded text-sm">cancel</span> Unverify Data';
        document.getElementById('fmdVerifyBtn').className = "px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white border border-red-500/30 rounded-xl text-xs font-bold transition-premium flex items-center gap-2 cursor-pointer";
        document.getElementById('fmdVerifyBtn').dataset.action = "unverify";
      } else {
        document.getElementById('fmdVerifyBtn').innerHTML = '<span class="material-symbols-rounded text-sm">verified</span> Verify Data';
        document.getElementById('fmdVerifyBtn').className = "px-4 py-2 bg-green-600/20 text-green-400 hover:bg-green-600 hover:text-white border border-green-500/30 rounded-xl text-xs font-bold transition-premium flex items-center gap-2 cursor-pointer";
        document.getElementById('fmdVerifyBtn').dataset.action = "verify";
      }
      document.getElementById('fmdIncome').innerText = data.student.annual_income ? `? ${data.student.annual_income}` : '--';
      document.getElementById('fmdResidence').innerText = data.student.residential_status || '--';
      document.getElementById('fmdGuardName').innerText = data.student.guardian_name || '--';
      document.getElementById('fmdGuardRel').innerText = data.student.guardian_relationship || '--';
      document.getElementById('fmdGuardPhone').innerText = data.student.guardian_mobile || '--';
      document.getElementById('fmdGuardAddress').innerText = data.student.guardian_address || '--';
      
      document.getElementById('fmdScholarships').innerText = data.student.scholarships || 'None recorded.';
      document.getElementById('fmdFeeWaiver').innerText = data.student.is_fee_waiver ? 'Yes' : 'No';
      if(data.student.is_fee_waiver) {
          document.getElementById('fmdFeeWaiver').classList.replace('bg-slate-800', 'bg-green-600');
      } else {
          document.getElementById('fmdFeeWaiver').classList.replace('bg-green-600', 'bg-slate-800');
      }
    }

        // Academic Progress & Board Grades
    const mAcContainer = document.getElementById('mentorAcademicContainer');
    if (data.academics && Object.keys(data.academics).length > 0) {
      mAcContainer.innerHTML = '';
      for (const [semester, subjects] of Object.entries(data.academics)) {
        let tableRows = subjects.map(s => {
          return `
            <tr class="border-b border-slate-800/40 hover:bg-slate-800/20">
              <td class="p-3">
                <div class="font-bold text-white">${s.subject_name}</div>
                <div class="text-xs text-slate-500 font-mono">${s.subject_code}  ${s.type}</div>
              </td>
              <td class="p-3 text-center border-l border-slate-800/40">
                ${s.tests.CO1}/${s.tests.CO2}/${s.tests.CO3}/${s.tests.CO4}
              </td>
              <td class="p-3 text-center border-l border-slate-800/40">
                ${s.assignments.CO1}/${s.assignments.CO2}/${s.assignments.CO3}/${s.assignments.CO4}
              </td>
              <td class="p-3 text-center border-l border-slate-800/40">
                ${s.mcq.CO1}/${s.mcq.CO2}/${s.mcq.CO3}/${s.mcq.CO4}
              </td>
              <td class="p-3 text-center border-l border-slate-800/40">${s.attendance}</td>
              <td class="p-3 text-center border-l border-slate-800/40 font-bold text-indigo-400">${s.internal_mark}</td>
              <td class="p-3 text-center border-l border-slate-800/40 font-bold ${s.board_grade && s.board_grade !== 'F' ? 'text-green-400' : 'text-red-400'}">
                ${s.board_grade || '--'}
              </td>
            </tr>
          `;
        }).join('');

        mAcContainer.innerHTML += `
          <div class="bg-slate-950/50 border border-slate-800 rounded-2xl overflow-hidden mb-6">
            <div class="p-3 border-b border-slate-800 bg-slate-900/50 font-bold text-indigo-300 text-sm">
              Semester ${semester}
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-[10px] text-xs">
                <thead class="bg-slate-900 border-b border-slate-800 text-slate-400 uppercase tracking-wider">
                  <tr>
                    <th class="p-3 w-48">Subject</th>
                    <th class="p-3 text-center">Test (1/2/3/4)</th>
                    <th class="p-3 text-center">Assgn (1/2/3/4)</th>
                    <th class="p-3 text-center">MCQ (1/2/3/4)</th>
                    <th class="p-3 text-center">Att %</th>
                    <th class="p-3 text-center">Internal</th>
                    <th class="p-3 text-center w-24">Board Grade</th>
                  </tr>
                </thead>
                <tbody class="text-slate-300">
                  ${tableRows}
                </tbody>
              </table>
            </div>
          </div>
        `;
      }
    } else {
      mAcContainer.innerHTML = '<div class="bg-slate-950/50 border border-slate-800 rounded-2xl p-6 text-center text-slate-500">No subjects assigned yet.</div>';
    }

    // Family
    const fTbody = document.getElementById('fmdFamilyTable');
    if (data.family && data.family.length > 0) {
      fTbody.innerHTML = data.family.map((f, i) => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3">${i+1}</td>
          <td class="p-3 font-bold text-white">${f.name}</td>
          <td class="p-3">${f.relationship}</td>
          <td class="p-3">${f.education || '--'}</td>
          <td class="p-3">${f.occupation || '--'}</td>
          <td class="p-3">${f.contact_no || '--'}</td>
        </tr>
      `).join('');
    } else {
      fTbody.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-600">No family records found.</td></tr>';
    }

    // Education
    const eTbody = document.getElementById('fmdEducationTable');
    if (data.education && data.education.length > 0) {
      eTbody.innerHTML = data.education.map(e => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3 font-bold text-white">${e.course}</td>
          <td class="p-3">${e.institution}</td>
          <td class="p-3">${e.year_of_completion || '--'}</td>
          <td class="p-3">${e.maths_marks || '--'}</td>
          <td class="p-3">${e.physics_marks || '--'}</td>
          <td class="p-3">${e.chemistry_marks || '--'}</td>
          <td class="p-3 font-bold text-indigo-400">${e.total_percentage || '--'}%</td>
        </tr>
      `).join('');
    } else {
      eTbody.innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-600">No prior education records found.</td></tr>';
    }

    // Fees
    const feeTbody = document.getElementById('fmdFeesTable');
    if (data.fees && data.fees.length > 0) {
      feeTbody.innerHTML = data.fees.map(f => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3">${f.academic_year}</td>
          <td class="p-3 text-white">?${f.fees_to_pay || 0}</td>
          <td class="p-3 text-green-400">?${f.amount_paid || 0}</td>
          <td class="p-3">${f.date_paid || '--'}</td>
        </tr>
      `).join('');
    } else {
      feeTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-600">No fee records.</td></tr>';
    }

        // Board Exams
    const bTbody = document.getElementById('fmdBoardTable');
    if (data.board && data.board.length > 0) {
      bTbody.innerHTML = data.board.map(b => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3 font-bold text-white">S${b.semester}</td>
          <td class="p-3">${b.sgpa || '--'}</td>
          <td class="p-3 font-bold text-indigo-400">${b.cgpa || '--'}</td>
          <td class="p-3">${b.activity_points || '--'}</td>
        </tr>
      `).join('');
    } else {
      bTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-600">No board exam records.</td></tr>';
    }

    // Extracurricular
    const exTbody = document.getElementById('fmdExtraTable');
    if (data.extracurricular && data.extracurricular.length > 0) {
      exTbody.innerHTML = data.extracurricular.map(ex => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3">S${ex.semester || (ex.activity_segment ? '-' : '')}</td>
          <td class="p-3 text-white font-bold">${ex.activity_name}</td>
          <td class="p-3">${ex.achievement || ex.level || '--'}</td>
          <td class="p-3 text-indigo-400 font-bold">${ex.points_awarded}</td>
          <td class="p-3">${ex.status}</td>
          <td class="p-3 text-right">
            <button onclick='editActivity(${JSON.stringify(ex).replace(/'/g, "&apos;")})' class="text-indigo-400 hover:text-indigo-300 transition-colors"><span class="material-symbols-rounded text-sm">edit</span></button>
          </td>
        </tr>
      `).join('');
    } else {
      exTbody.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>';
    }

    // Leaves
    const lTbody = document.getElementById('fmdLeavesTable');
    if (data.leaves && data.leaves.length > 0) {
      lTbody.innerHTML = data.leaves.map(l => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3 font-mono text-slate-400">${l.leave_date}</td>
          <td class="p-3 text-white">${l.reason}</td>
          <td class="p-3 text-center">${l.parent_informed ? 'Yes' : 'No'}</td>
          <td class="p-3 text-right">${l.status}</td>
          <td class="p-3 text-right">
            <button onclick='editLeave(${JSON.stringify(l).replace(/'/g, "&apos;")})' class="text-indigo-400 hover:text-indigo-300 transition-colors"><span class="material-symbols-rounded text-sm">edit</span></button>
          </td>
        </tr>
      `).join('');
    } else {
      lTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-600">No leave records.</td></tr>';
    }

    // Discipline
    const dTbody = document.getElementById('fmdDisciplineTable');
    if (data.disciplinary && data.disciplinary.length > 0) {
      dTbody.innerHTML = data.disciplinary.map(d => `
        <tr class="border-b border-slate-800/40">
          <td class="p-3 font-mono text-slate-400">${d.date}</td>
          <td class="p-3 text-white">${d.description}</td>
          <td class="p-3">${d.action_taken || '--'}</td>
          <td class="p-3 text-right">
            <button onclick='editDisc(${JSON.stringify(d).replace(/'/g, "&apos;")})' class="text-indigo-400 hover:text-indigo-300 transition-colors"><span class="material-symbols-rounded text-sm">edit</span></button>
          </td>
        </tr>
      `).join('');
    } else {
      dTbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-600">Clean disciplinary record.</td></tr>';
    }

    // Meetings
    const mTbody = document.getElementById('fmdMeetingTable');
    if (data.meetings && data.meetings.length > 0) {
      mTbody.innerHTML = data.meetings.map(m => {
        let badge = m.approval_status === 'Approved' 
          ? `<span class="px-1.5 py-0.5 rounded font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`
          : `<span class="px-1.5 py-0.5 rounded font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${m.approval_status}</span>`;
        
        return `
        <tr class="border-b border-slate-800/40 text-[10px] text-xs">
          <td class="p-3 font-mono text-slate-400">${m.date}</td>
          <td class="p-3 font-bold text-slate-300">${m.category}<br><span class="font-mono text-slate-500">By: ${m.logged_by_name}</span></td>
          <td class="p-3 text-slate-300">
            <div class="mb-1"><strong class="text-slate-500">Notes:</strong> ${m.discussion_notes}</div>
            ${m.action_taken ? `<div><strong class="text-slate-500">Action:</strong> ${m.action_taken}</div>` : ''}
          </td>
          <td class="p-3 text-right">${badge}</td>
        </tr>
      `}).join('');
    } else {
      mTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-600">No meeting records found.</td></tr>';
    }
  }

  function toggleDiaryAddForm() {
    const form = document.getElementById('fmdAddMeetingForm');
    if(form.classList.contains('hidden')) {
      form.classList.remove('hidden');
      form.classList.add('block');
    } else {
      form.classList.add('hidden');
      form.classList.remove('block');
    }
  }

    function verifyStudentData() {
    if(!currentMentoringRegNo) return;
    
    const action = document.getElementById("fmdVerifyBtn").dataset.action || "verify";
    const endpoint = action === "verify" ? "/api/mentoring/verify-data" : "/api/mentoring/unverify-data";

    fetch(endpoint, {
      method: "POST",
      headers: getHeaders(),
      body: JSON.stringify({ reg_no: currentMentoringRegNo })
    })
    .then(res => res.json())
    .then(data => {
      if(data.status === "SUCCESS") {
        showGlobalMessage("Student data " + action + "ed successfully.");
        fetchFullMentoringData(currentMentoringRegNo);
      } else {
        showGlobalMessage("Failed to " + action + " data.", true);
      }
    })
    .catch(e => showGlobalMessage("Error communicating with server.", true));
  }

  function downloadMentorPdf() {
    window.open('/diary/' + currentMentoringRegNo + '/print', '_blank');
  }

  function submitNewMeeting() {
    const date = document.getElementById('fmdMeetingDate').value;
    const category = document.getElementById('fmdMeetingCategory').value;
    const notes = document.getElementById('fmdMeetingNotes').value;
    const action = document.getElementById('fmdMeetingAction').value;

    if (!date || !category || !notes) {
      showGlobalMessage('Please fill in Date, Category, and Description.', true);
      return;
    }

    fetch('/api/mentoring/diary/add', {
      method: 'POST',
      headers: getHeaders(),
      body: JSON.stringify({
        reg_no: currentMentoringRegNo,
        date: date,
        category: category,
        discussion_notes: notes,
        action_taken: action
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'SUCCESS') {
        showGlobalMessage('Meeting logged successfully.');
        toggleDiaryAddForm();
        document.getElementById('fmdMeetingCategory').value = '';
        document.getElementById('fmdMeetingNotes').value = '';
        document.getElementById('fmdMeetingAction').value = '';
        fetchFullMentoringData(currentMentoringRegNo); // reload
      } else {
        showGlobalMessage(data.message, true);
      }
    })
    .catch(() => showGlobalMessage('Failed to log meeting.', true));
  }
</script>

<!-- ACTIVITY MODAL -->
  <div id="addActivityModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
      <div class="flex justify-between items-center mb-6">
        <h3 class="font-black text-white text-lg" id="activityModalTitle">Add Activity</h3>
        <button onclick="closeActivityModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
      </div>
      <form id="activityForm" onsubmit="saveActivity(event)">
        <input type="hidden" id="activityId">
        <div class="space-y-4">
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Segment</label>
            <select id="activitySegment" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
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
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Activity Name</label>
            <input type="text" id="activityName" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Level (e.g. State, College)</label>
            <input type="text" id="activityLevel" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Points Claimed</label>
              <input type="number" id="activityPtsClaimed" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
            </div>
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Points Awarded</label>
              <input type="number" id="activityPtsAwarded" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
            </div>
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Status</label>
            <select id="activityStatus" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
              <option value="Verified">Verified</option>
              <option value="Pending">Pending</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-sm">Save Activity</button>
        </div>
      </form>
    </div>
  </div>

  <!-- LEAVE MODAL -->
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
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Semester</label>
              <input type="number" id="leaveSem" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
            </div>
            <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">From Date</label>
                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>
            <input type="text" id="leaveReason" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Status</label>
            <select id="leaveStatus" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" id="leaveParent" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-500">
            <label class="text-slate-300 text-[10px] text-xs">Parent Informed?</label>
          </div>
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-sm">Save Leave</button>
        </div>
      </form>
    </div>
  </div>

  <!-- DISCIPLINARY MODAL -->
  <div id="addDisciplinaryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
      <div class="flex justify-between items-center mb-6">
        <h3 class="font-black text-white text-lg" id="discModalTitle">Add Disciplinary Action</h3>
        <button onclick="closeDiscModal()" class="text-slate-400 hover:text-white"><span class="material-symbols-rounded">close</span></button>
      </div>
      <form id="discForm" onsubmit="saveDisciplinary(event)">
        <input type="hidden" id="discId">
        <div class="space-y-4">
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>
            <input type="date" id="discDate" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Description</label>
            <textarea id="discDesc" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 h-24 text-sm"></textarea>
          </div>
          <div>
            <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Action Taken (Optional)</label>
            <textarea id="discAction" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 h-20 text-sm"></textarea>
          </div>
          <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold text-sm">Save Action</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    /* Enlarge mentoring diary modal data fields for comfortable reading */
    #fullMentoringDiaryModal label {
      font-size: 14px !important;
      font-weight: bold !important;
    }
    #fullMentoringDiaryModal input,
    #fullMentoringDiaryModal select,
    #fullMentoringDiaryModal textarea,
    #fullMentoringDiaryModal td,
    #fullMentoringDiaryModal th,
    #fullMentoringDiaryModal li,
    #fullMentoringDiaryModal p {
      font-size: 14px !important;
    }
    #fullMentoringDiaryModal span:not(.material-symbols-rounded) {
      font-size: 14px !important;
    }
  </style>












