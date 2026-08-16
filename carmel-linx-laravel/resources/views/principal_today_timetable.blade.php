<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Today's Master Timetable Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html {
      font-size: 90%;
    }
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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

    /* Print Optimization Styles */
    @media print {
      body {
        background-color: #ffffff !important;
        color: #000000 !important;
      }
      .no-print {
        display: none !important;
      }
      .print-card {
        border: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
        background: #ffffff !important;
        color: #000000 !important;
        break-inside: avoid;
        page-break-inside: avoid;
      }
      .print-text-dark {
        color: #0f172a !important;
      }
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col font-sans antialiased selection:bg-indigo-500 selection:text-white">

  <!-- Top Navigation Bar -->
  <header class="bg-slate-900/80 border-b border-slate-800/80 backdrop-blur-md sticky top-0 z-30 px-4 md:px-8 py-3 flex flex-wrap items-center justify-between gap-4 no-print">
    <div class="flex items-center gap-3">
      <a href="/dashboard/principal" class="p-2 bg-slate-800 hover:bg-slate-750 text-slate-300 rounded-xl transition-premium flex items-center justify-center cursor-pointer no-underline" title="Return to Principal Desk">
        <span class="material-symbols-rounded text-lg">arrow_back</span>
      </a>
      <img src="{{ asset('logo.jpg') }}" class="w-9 h-9 rounded-xl object-cover border border-slate-750 shadow-md">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="font-black text-slate-100 tracking-tight text-base leading-tight">Today's Master Institutional Timetable</h1>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">Live Executive Desk</span>
        </div>
        <p class="text-xs text-slate-400">All 6 Departments • Active Semesters • Staff Allocation &amp; Real-Time Attendance</p>
      </div>
    </div>

    <!-- Actions & Controls Bar -->
    <div class="flex items-center gap-3 flex-wrap">
      <!-- Target Date Picker -->
      <div class="flex items-center gap-1.5 bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 shadow-inner">
        <span class="material-symbols-rounded text-xs text-indigo-400">calendar_month</span>
        <input type="date" id="timetableDateInput" value="{{ $date }}" onchange="loadTimetableData()" class="bg-transparent text-xs text-white outline-none cursor-pointer">
      </div>

      <!-- Active Day Order Badge -->
      <div id="dayOrderBadge" class="px-3 py-1.5 bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/40 text-amber-300 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-rounded text-sm text-amber-400">today</span>
        <span id="dayOrderText">Active Day Order: {{ $activeDayOrder }}</span>
      </div>

      <!-- Refresh / Sync Button -->
      <button onclick="loadTimetableData()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer" title="Refresh Timetable Data">
        <span class="material-symbols-rounded text-sm" id="refreshIcon">sync</span>
        <span>Refresh</span>
      </button>
    </div>
  </header>


  <!-- Main Container -->
  <main class="flex-grow p-4 md:p-8 space-y-6 max-w-[1750px] mx-auto w-full">

    <!-- Top KPI Summary Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 no-print">
      <!-- Total Scheduled Slots -->
      <div class="bg-slate-900/60 border border-slate-800/80 p-4 rounded-2xl flex items-center justify-between shadow-xl">
        <div>
          <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Today's Class Slots</span>
          <span id="kpiScheduled" class="font-black text-2xl text-white leading-tight block mt-0.5">--</span>
          <span class="text-[10px] text-slate-500 mt-0.5 block">Periods 1-6 across all 6 departments</span>
        </div>
        <div class="p-3 bg-blue-500/10 text-blue-400 rounded-xl border border-blue-500/20">
          <span class="material-symbols-rounded text-2xl">schedule</span>
        </div>
      </div>

      <!-- Conducted Sessions -->
      <div class="bg-slate-900/60 border border-slate-800/80 p-4 rounded-2xl flex items-center justify-between shadow-xl">
        <div>
          <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Conducted &amp; Logged</span>
          <span id="kpiConducted" class="font-black text-2xl text-emerald-400 leading-tight block mt-0.5">--</span>
          <span id="kpiCoveragePct" class="text-[10px] text-emerald-400 font-bold mt-0.5 block">0% Logged</span>
        </div>
        <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
          <span class="material-symbols-rounded text-2xl">task_alt</span>
        </div>
      </div>

      <!-- Overall Student Attendance -->
      <div class="bg-slate-900/60 border border-slate-800/80 p-4 rounded-2xl flex items-center justify-between shadow-xl">
        <div>
          <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Student Attendance Rate</span>
          <span id="kpiAttendancePct" class="font-black text-2xl text-sky-400 leading-tight block mt-0.5">--%</span>
          <span class="text-[10px] text-slate-500 mt-0.5 block">Real-time present ratio</span>
        </div>
        <div class="p-3 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20">
          <span class="material-symbols-rounded text-2xl">groups</span>
        </div>
      </div>

      <!-- Target Departments -->
      <div class="bg-slate-900/60 border border-slate-800/80 p-4 rounded-2xl flex items-center justify-between shadow-xl">
        <div>
          <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block">Departments Covered</span>
          <span class="font-black text-2xl text-indigo-400 leading-tight block mt-0.5">6 Depts</span>
          <span class="text-[10px] text-slate-500 mt-0.5 block">CT, EL, ME, CE, EEE, AU</span>
        </div>
        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
          <span class="material-symbols-rounded text-2xl">domain</span>
        </div>
      </div>
    </div>

    <!-- Department Selector Tabs -->
    <div class="flex items-center justify-between bg-slate-900/40 border border-slate-800/60 p-2.5 rounded-2xl overflow-x-auto custom-scrollbar no-print gap-2">
      <div class="flex items-center gap-2">
        <button onclick="filterBranch('ALL')" id="tab_ALL" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium bg-indigo-600 text-white shadow-lg cursor-pointer whitespace-nowrap">
          All 6 Departments
        </button>
        <button onclick="filterBranch('CT')" id="tab_CT" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Computer (CT)
        </button>
        <button onclick="filterBranch('EL')" id="tab_EL" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Electronics (EL)
        </button>
        <button onclick="filterBranch('ME')" id="tab_ME" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Mechanical (ME)
        </button>
        <button onclick="filterBranch('CE')" id="tab_CE" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Civil (CE)
        </button>
        <button onclick="filterBranch('EEE')" id="tab_EEE" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Electrical (EEE)
        </button>
        <button onclick="filterBranch('AU')" id="tab_AU" class="px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap">
          Automobile (AU)
        </button>
      </div>

      <div class="text-xs text-slate-400 font-bold px-2 whitespace-nowrap">
        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block mr-1.5"></span> Live Attendance &amp; Log Tracker
      </div>
    </div>

    <!-- Print Title Banner (Only visible during print) -->
    <div class="hidden print:block mb-4 text-center border-b border-slate-300 pb-3">
      <h1 class="text-xl font-bold text-slate-900">CARMEL COLLEGE OF ENGINEERING &amp; TECHNOLOGY</h1>
      <h2 class="text-sm font-bold text-slate-700 uppercase tracking-widest mt-0.5">Today's Master Institutional Timetable Report</h2>
      <p class="text-xs text-slate-600 mt-1">Date: <span id="printReportDate">--</span> | Active Day Order: <span id="printReportDayOrder">--</span></p>
    </div>

    <!-- Timetable Grid Render Container -->
    <div id="timetableGridContainer" class="space-y-8">
      <div class="p-12 text-center text-slate-500 flex flex-col items-center justify-center bg-slate-900/30 border border-slate-800/40 rounded-3xl">
        <div class="w-8 h-8 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin mb-3"></div>
        <p class="font-bold text-sm text-slate-300">Loading institutional timetable data...</p>
      </div>
    </div>

  </main>

  <script>
    let activeBranchFilter = 'ALL';
    let cachedTimetableData = null;

    document.addEventListener('DOMContentLoaded', () => {
      loadTimetableData();
    });


    async function loadTimetableData() {
      const dateVal = document.getElementById('timetableDateInput').value;
      const refreshIcon = document.getElementById('refreshIcon');
      refreshIcon.classList.add('animate-spin');

      try {
        const response = await fetch(`/api/principal/today-timetable?date=${dateVal}`);
        const data = await response.json();

        if (data.success) {
          cachedTimetableData = data;
          renderTimetableDesk(data);
        } else {
          document.getElementById('timetableGridContainer').innerHTML = `
            <div class="p-8 bg-rose-950/20 border border-rose-800/40 rounded-2xl text-rose-300 font-bold text-center">
              Failed to load timetable data: ${data.message || 'Unknown error'}
            </div>
          `;
        }
      } catch (err) {
        console.error('Error fetching timetable data:', err);
        document.getElementById('timetableGridContainer').innerHTML = `
          <div class="p-8 bg-rose-950/20 border border-rose-800/40 rounded-2xl text-rose-300 font-bold text-center">
            Network or server connection error.
          </div>
        `;
      } finally {
        refreshIcon.classList.remove('animate-spin');
      }
    }

    function filterBranch(branchCode) {
      activeBranchFilter = branchCode;
      ['ALL', 'CT', 'EL', 'ME', 'CE', 'EEE', 'AU'].forEach(b => {
        const btn = document.getElementById(`tab_${b}`);
        if (btn) {
          if (b === branchCode) {
            btn.className = "px-4 py-2 rounded-xl text-xs font-bold transition-premium bg-indigo-600 text-white shadow-lg cursor-pointer whitespace-nowrap";
          } else {
            btn.className = "px-4 py-2 rounded-xl text-xs font-bold transition-premium text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer whitespace-nowrap";
          }
        }
      });

      if (cachedTimetableData) {
        renderTimetableDesk(cachedTimetableData);
      }
    }

    function renderTimetableDesk(data) {
      // Update Top KPIs
      document.getElementById('kpiScheduled').textContent = data.summary.total_scheduled_slots;
      document.getElementById('kpiConducted').textContent = data.summary.total_conducted_slots;
      document.getElementById('kpiCoveragePct').textContent = `${data.summary.coverage_percentage}% Logged Today`;
      document.getElementById('kpiAttendancePct').textContent = `${data.summary.overall_attendance_percentage}%`;
      
      document.getElementById('dayOrderText').textContent = `Active Day Order: ${data.active_day_order}`;
      
      // Print elements
      const printDate = document.getElementById('printReportDate');
      const printDayOrder = document.getElementById('printReportDayOrder');
      if (printDate) printDate.textContent = data.date;
      if (printDayOrder) printDayOrder.textContent = data.active_day_order;

      const container = document.getElementById('timetableGridContainer');
      container.innerHTML = '';

      const targetBranches = activeBranchFilter === 'ALL' 
        ? ['CT', 'EL', 'ME', 'CE', 'EEE', 'AU'] 
        : [activeBranchFilter];

      let renderedCount = 0;

      targetBranches.forEach(bCode => {
        const branchObj = data.branches[bCode];
        if (!branchObj || !branchObj.classrooms || branchObj.classrooms.length === 0) return;

        renderedCount++;

        const deptSection = document.createElement('div');
        deptSection.className = 'bg-slate-900/50 border border-slate-800/80 rounded-3xl p-5 md:p-6 space-y-5 shadow-2xl print-card';

        // Department Title Banner
        const branchColors = {
          'CT': 'from-purple-500/20 to-indigo-500/10 text-purple-300 border-purple-500/30',
          'EL': 'from-amber-500/20 to-yellow-500/10 text-amber-300 border-amber-500/30',
          'ME': 'from-emerald-500/20 to-teal-500/10 text-emerald-300 border-emerald-500/30',
          'CE': 'from-pink-500/20 to-rose-500/10 text-pink-300 border-pink-500/30',
          'EEE': 'from-rose-500/20 to-orange-500/10 text-rose-300 border-rose-500/30',
          'AU': 'from-indigo-500/20 to-blue-500/10 text-indigo-300 border-indigo-500/30'
        };

        const deptNames = {
          'CT': 'Department of Computer Engineering',
          'EL': 'Department of Electronics Engineering',
          'ME': 'Department of Mechanical Engineering',
          'CE': 'Department of Civil Engineering',
          'EEE': 'Department of Electrical & Electronics Engineering',
          'AU': 'Department of Automobile Engineering'
        };

        const badgeStyle = branchColors[bCode] || 'from-blue-500/20 to-sky-500/10 text-blue-300 border-blue-500/30';

        deptSection.innerHTML = `
          <div class="flex items-center justify-between border-b border-slate-800/60 pb-4">
            <div class="flex items-center gap-3">
              <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r ${badgeStyle} border shadow-inner">
                ${bCode}
              </span>
              <div>
                <h2 class="font-extrabold text-slate-100 text-lg leading-tight print-text-dark">${deptNames[bCode] || bCode}</h2>
                <span class="text-xs text-slate-400">Classroom Timetable Grid for ${data.active_day_order}</span>
              </div>
            </div>
            <a href="/dashboard/principal/department/${bCode}" target="_blank" class="no-print px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm">open_in_new</span> Override Desk
            </a>
          </div>
        `;

        // Render Classrooms under this department
        const classroomsGrid = document.createElement('div');
        classroomsGrid.className = 'space-y-6';

        branchObj.classrooms.forEach(cRoom => {
          const semCard = document.createElement('div');
          semCard.className = 'bg-slate-950/60 border border-slate-800/60 rounded-2xl overflow-hidden print-card';

          let periodsHtml = '';
          for (let p = 1; p <= 6; p++) {
            const pData = cRoom.periods[p];
            
            let statusBadge = '';
            let attHtml = '';
            let topicHtml = '';

            if (pData.subject_code === 'FREE') {
              statusBadge = `<span class="px-2 py-0.5 bg-slate-800/80 text-slate-400 rounded-md text-[10px] font-bold">Free Period</span>`;
              attHtml = `<span class="text-slate-500 text-xs italic">--</span>`;
              topicHtml = `<span class="text-slate-600 text-xs italic">No Session Scheduled</span>`;
            } else if (pData.is_marked) {
              const isHigh = pData.attendance_percentage >= 75;
              const badgeBg = isHigh 
                ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' 
                : 'bg-rose-500/20 text-rose-400 border-rose-500/30';

              statusBadge = `<span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-md text-[10px] font-bold flex items-center gap-1 w-fit"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Conducted</span>`;

              attHtml = `
                <div class="flex items-center gap-1.5 mt-1">
                  <span class="px-2 py-0.5 ${badgeBg} border rounded-full font-mono text-xs font-bold">
                    ${pData.present_count} / ${pData.enrolled_count} (${pData.attendance_percentage}%)
                  </span>
                </div>
              `;

              topicHtml = `
                <div class="text-xs text-slate-300 font-medium line-clamp-2 mt-1 print-text-dark" title="${pData.topic_covered}">
                  <span class="text-emerald-400 font-bold">Topic:</span> ${pData.topic_covered}
                </div>
              `;
            } else {
              statusBadge = `<span class="px-2 py-0.5 bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-md text-[10px] font-bold">Log Pending</span>`;
              attHtml = `<span class="text-amber-400/80 text-xs font-bold">Attendance Pending</span>`;
              topicHtml = `<span class="text-slate-500 text-xs italic">Topic Log Pending</span>`;
            }

            periodsHtml += `
              <div class="p-3.5 bg-slate-900/40 border border-slate-800/40 rounded-xl space-y-2 flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                  <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="text-[10px] font-black uppercase text-indigo-400 tracking-wider">Period ${p}</span>
                    ${statusBadge}
                  </div>
                  <h4 class="font-bold text-slate-200 text-xs leading-snug print-text-dark truncate" title="${pData.subject_name}">
                    ${pData.subject_code !== 'FREE' ? `<span class="text-indigo-300 font-mono">[${pData.subject_code}]</span> ` : ''}${pData.subject_name}
                  </h4>
                  <div class="flex items-center gap-1.5 text-xs text-slate-400 mt-1">
                    <span class="material-symbols-rounded text-sm text-slate-500">person</span>
                    <span class="truncate font-medium text-slate-300 print-text-dark">${pData.staff_assigned}</span>
                  </div>
                </div>
                <div class="pt-2 border-t border-slate-800/40 space-y-1">
                  ${attHtml}
                  ${topicHtml}
                </div>
              </div>
            `;
          }

          semCard.innerHTML = `
            <div class="p-4 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between flex-wrap gap-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400 text-lg">school</span>
                <h3 class="font-black text-slate-100 text-sm print-text-dark">Semester ${cRoom.semester} (${cRoom.classroom_id})</h3>
                <span class="text-xs text-slate-400 font-mono">(${cRoom.enrolled_students} Enrolled Students)</span>
              </div>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
              ${periodsHtml}
            </div>
          `;

          classroomsGrid.appendChild(semCard);
        });

        deptSection.appendChild(classroomsGrid);
        container.appendChild(deptSection);
      });

      if (renderedCount === 0) {
        container.innerHTML = `
          <div class="p-12 text-center text-slate-500 bg-slate-900/30 border border-slate-800/40 rounded-3xl">
            No classroom timetable data found for the selected department/filter.
          </div>
        `;
      }
    }
  </script>
</body>
</html>
