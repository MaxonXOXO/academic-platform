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
    $backLink = '/dashboard/lecturer';
    if ($role === 'HOD') $backLink = '/dashboard/hod';
    if ($role === 'Demonstrator') $backLink = '/dashboard/demonstrator';
    if ($role === 'Trade_Instructor') $backLink = '/dashboard/tradeinstructor';
    if ($role === 'Workshop_Superintendent') $backLink = '/dashboard/workshop';
  @endphp

  <!-- Top Navigation Header -->
  <header class="bg-slate-950/60 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-30 px-6 py-4 flex items-center justify-between shadow-lg">
    <div class="flex items-center gap-3">
      <a href="{{ $backLink }}" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 transition-colors flex items-center justify-center">
        <span class="material-symbols-rounded">arrow_back</span>
      </a>
      <div>
        <h1 class="font-extrabold text-white text-base sm:text-lg tracking-tight">Class Log & Attendance</h1>
        <p class="text-sm text-slate-400 font-medium">Record today's class topics and attendance.</p>
      </div>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-black rounded-lg w-9 h-9 flex items-center justify-center shadow-lg text-sm">CL</div>
  </header>

  <!-- Notification Banner -->
  <div id="globalAlert" class="hidden max-w-xl mx-auto mt-4 px-4 py-3 rounded-xl text-sm font-bold text-center border shadow-md animate-pulse"></div>

  <main class="max-w-xl mx-auto w-full px-4 mt-6 flex-grow space-y-6">
    
    <!-- CLASS SELECTOR CARD -->
    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
      <div class="flex items-center gap-2 pb-2 border-b border-slate-800/60">
        <span class="material-symbols-rounded text-indigo-400 text-lg">school</span>
        <h2 class="font-bold text-sm text-slate-200">Select Batch & Subject</h2>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-400 mb-1.5">Class Subject / Batch</label>
        <select id="subjectSelect" onchange="onSubjectChange()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-3 text-sm text-slate-200 outline-none focus:border-indigo-500 cursor-pointer">
          <option value="" disabled selected>-- Choose Subject --</option>
        </select>
      </div>
    </div>

    <!-- SUB-BATCH SELECTOR CARD (LABS ONLY) -->
    <div id="subBatchCard" class="hidden bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
      <div class="flex items-center gap-2 pb-2 border-b border-slate-800/60">
        <span class="material-symbols-rounded text-indigo-400 text-lg">splitscreen</span>
        <h2 class="font-bold text-sm text-slate-200">Lab Sub-Batch Partitioning</h2>
      </div>
      <div>
        <label class="block text-sm font-bold text-slate-400 mb-2">Select Lab Sub-Batch</label>
        <div class="grid grid-cols-3 gap-3">
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="Whole" checked onchange="filterStudentsByBatch()" class="sr-only peer">
            <div class="p-3 text-center rounded-xl border border-slate-700 bg-slate-900 text-sm font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
              Whole Class
            </div>
          </label>
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="1" onchange="filterStudentsByBatch()" class="sr-only peer">
            <div id="batch1Text" class="p-3 text-center rounded-xl border border-slate-700 bg-slate-900 text-sm font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
              Batch 1
            </div>
          </label>
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="2" onchange="filterStudentsByBatch()" class="sr-only peer">
            <div id="batch2Text" class="p-3 text-center rounded-xl border border-slate-700 bg-slate-900 text-sm font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
              Batch 2
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- DAILY CLASS LOG DETAILS -->
    <div id="classLogCard" class="hidden bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
      <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-indigo-400 text-lg">edit_note</span>
          <h2 class="font-bold text-sm text-slate-200">Class Log Details</h2>
        </div>
        <div class="flex items-center gap-1.5 bg-slate-900 border border-indigo-500/30 rounded-full px-3 py-1">
          <span id="logLastSlNoBadge" class="text-xs font-mono font-black text-indigo-400">Last Log: Sl #0</span>
          <span class="text-slate-600 text-xs">•</span>
          <span id="logNextSlNoPointer" class="text-xs font-mono font-bold text-emerald-400">Next Entry: Log #1</span>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-bold text-slate-400 mb-1.5">Date</label>
          <input type="date" id="logDate" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-3 text-sm text-slate-200 outline-none focus:border-indigo-500" value="{{ date('Y-m-d') }}">
        </div>
        <div>
          <label class="block text-sm font-bold text-slate-400 mb-1.5">Period / Hour (Select multiple if Lab or Combined Class)</label>
          <div class="flex flex-wrap gap-2">
            @for ($p = 1; $p <= 7; $p++)
              <label class="cursor-pointer">
                <input type="checkbox" name="logPeriods" value="{{ $p }}" class="sr-only peer">
                <div class="px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 text-sm font-bold text-slate-300 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-500 hover:bg-slate-800 transition-all select-none">
                  P{{ $p }}
                </div>
              </label>
            @endfor
          </div>
        </div>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-400 mb-1.5">Syllabus / Lesson Plan Topic</label>
        <select id="lessonPlanSelect" onchange="onLessonPlanChange()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-indigo-500 cursor-pointer">
          <option value="">-- Manual Entry --</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-bold text-slate-400 mb-1.5">Topics Covered (Editable)</label>
        <textarea id="topicsCovered" rows="3" placeholder="Describe the topics covered in class today..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-3 text-sm text-slate-200 outline-none focus:border-indigo-500 resize-none"></textarea>
      </div>
    </div>

    <!-- ATTENDANCE ENTRY PANEL -->
    <div id="attendanceCard" class="hidden bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
      <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-indigo-400 text-lg">fact_check</span>
          <h2 class="font-bold text-sm text-slate-200">Attendance Panel</h2>
        </div>
        <!-- Mode Switch -->
        <div class="flex bg-slate-900 border border-slate-800 rounded-lg p-0.5">
          <button onclick="switchMode('list')" id="btnModeList" class="px-2.5 py-1 text-sm font-bold rounded-md bg-indigo-600 text-white transition-all">List</button>
          <button onclick="switchMode('grid')" id="btnModeGrid" class="px-2.5 py-1 text-sm font-bold rounded-md text-slate-400 transition-all">Grid</button>
        </div>
      </div>

      <!-- MODE 1: LIST VIEW -->
      <div id="attendanceModeList" class="space-y-3">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm text-slate-400 font-bold" id="studentCountLabel">Total Students: 0</span>
          <button onclick="toggleAllCheckboxes()" id="btnCheckAll" class="text-sm font-bold text-indigo-400 hover:text-indigo-300">Mark All Present</button>
        </div>
        
        <div class="max-h-[300px] overflow-y-auto custom-scrollbar border border-slate-850 rounded-xl bg-slate-900/10">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-900/60 text-slate-400 border-b border-slate-850 uppercase tracking-wider text-sm font-black sticky top-0">
                <th class="p-3 w-16 text-center">Roll No</th>
                <th class="p-3">Name</th>
                <th class="p-3 w-16 text-center">Present</th>
              </tr>
            </thead>
            <tbody id="studentListContainer">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- MODE 2: GRID VIEW (Roll numbers only) -->
      <div id="attendanceModeGrid" class="hidden space-y-4">
        <div class="flex justify-between items-center">
          <p class="text-sm text-slate-400">Tap buttons to toggle <strong class="text-red-400">Absent (Red)</strong> / <strong class="text-emerald-400">Present (Green)</strong>.</p>
          <button onclick="toggleAllGrid(true)" class="text-sm font-bold text-indigo-400 hover:text-indigo-300">Reset Present</button>
        </div>

        <div class="grid grid-cols-5 gap-3 p-1" id="studentGridContainer">
          <!-- Rendered via JS -->
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="pt-4 border-t border-slate-800/60">
        <button onclick="saveAttendanceAndLog()" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg transition-premium cursor-pointer">
          <span class="material-symbols-rounded">check_circle</span> Save Log & Attendance
        </button>
      </div>

    </div>

  </main>

  <!-- Javascript Logic -->
  <script>
    let activeMode = 'list'; // 'list' or 'grid'
    let currentStudents = [];
    let classroomId = '';
    let isAllChecked = true;

    document.addEventListener('DOMContentLoaded', () => {
      loadSubjects();
    });

    function showMessage(msg, isError = false) {
      const banner = document.getElementById('globalAlert');
      banner.classList.remove('hidden');
      if (isError) {
        banner.className = "max-w-xl mx-auto mt-4 px-4 py-3 rounded-xl text-sm font-bold text-center border bg-red-950/40 text-red-400 border-red-900 block shadow-md animate-pulse";
      } else {
        banner.className = "max-w-xl mx-auto mt-4 px-4 py-3 rounded-xl text-sm font-bold text-center border bg-green-950/40 text-green-400 border-green-900 block shadow-md animate-pulse";
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
          } else {
            showMessage(data.message || "Failed to load subjects", true);
          }
        });
    }

     function onSubjectChange() {
      const subjectId = document.getElementById('subjectSelect').value;
      if (!subjectId) return;

      // Show cards
      document.getElementById('classLogCard').classList.remove('hidden');
      document.getElementById('attendanceCard').classList.remove('hidden');

      fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentStudents = data.students;
            classroomId = data.classroom_id;

            // Check if Lab or Practical
            const isLab = (data.subject_type && (data.subject_type.toLowerCase().includes('lab') || data.subject_type.toLowerCase().includes('practical') || data.subject_type.toLowerCase().includes('practicum')));
            const subBatchCard = document.getElementById('subBatchCard');
            if (isLab) {
              subBatchCard.classList.remove('hidden');
              const half = Math.ceil(currentStudents.length / 2);
              document.getElementById('batch1Text').innerText = `Batch 1 (1-${half})`;
              document.getElementById('batch2Text').innerText = `Batch 2 (${half + 1}+)`;
            } else {
              subBatchCard.classList.add('hidden');
              const wholeRadio = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
              if (wholeRadio) wholeRadio.checked = true;
            }

            // Load student count & serial number tracking
            const lastSlNo = data.last_log_sl_no || 0;
            const nextSlNo = data.next_log_sl_no || (lastSlNo + 1);
            const lastBadge = document.getElementById('logLastSlNoBadge');
            const nextPointer = document.getElementById('logNextSlNoPointer');
            if (lastBadge) lastBadge.innerText = `Last Log: Sl #${lastSlNo}`;
            if (nextPointer) nextPointer.innerText = `Next Entry: Log #${nextSlNo}`;

            const filtered = getFilteredStudents();
            document.getElementById('studentCountLabel').innerText = `Total Students: ${filtered.length}`;

            // Reset present state (all present by default)
            currentStudents.forEach(s => s.present = true);

            // Populate Lesson Plans dropdown
            const lpSelect = document.getElementById('lessonPlanSelect');
            lpSelect.innerHTML = '<option value="">-- Manual Entry --</option>';
            data.lesson_plans.forEach((lp, idx) => {
              const opt = document.createElement('option');
              opt.value = lp.id;
              opt.dataset.topic = lp.topic_content || '';
              opt.innerText = `Sl #${idx + 1} | [${lp.co_id || 'CO'}] ${lp.topic_content} (${lp.status || 'Pending'})`;
              lpSelect.appendChild(opt);
            });

            // Reset topics textarea
            document.getElementById('topicsCovered').value = '';

            // Render views
            renderList();
            renderGrid();
          } else {
            showMessage(data.message || "Failed to load subject details", true);
          }
        });
    }

    function onLessonPlanChange() {
      const select = document.getElementById('lessonPlanSelect');
      const selectedOption = select.options[select.selectedIndex];
      if (selectedOption && select.value) {
        document.getElementById('topicsCovered').value = selectedOption.dataset.topic || selectedOption.innerText;
      } else {
        document.getElementById('topicsCovered').value = '';
      }
    }

    function switchMode(mode) {
      activeMode = mode;
      const btnList = document.getElementById('btnModeList');
      const btnGrid = document.getElementById('btnModeGrid');
      const divList = document.getElementById('attendanceModeList');
      const divGrid = document.getElementById('attendanceModeGrid');

      if (mode === 'list') {
        btnList.className = "px-2.5 py-1 text-sm font-bold rounded-md bg-indigo-600 text-white transition-all";
        btnGrid.className = "px-2.5 py-1 text-sm font-bold rounded-md text-slate-400 transition-all";
        divList.classList.remove('hidden');
        divGrid.classList.add('hidden');
        renderList();
      } else {
        btnGrid.className = "px-2.5 py-1 text-sm font-bold rounded-md bg-indigo-600 text-white transition-all";
        btnList.className = "px-2.5 py-1 text-sm font-bold rounded-md text-slate-400 transition-all";
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
      const half = Math.ceil(currentStudents.length / 2);
      if (val === '1') {
        return currentStudents.slice(0, half);
      } else {
        return currentStudents.slice(half);
      }
    }

    function filterStudentsByBatch() {
      const filtered = getFilteredStudents();
      document.getElementById('studentCountLabel').innerText = `Total Students: ${filtered.length}`;
      renderList();
      renderGrid();
    }

    function renderList() {
      const container = document.getElementById('studentListContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<tr><td colspan="3" class="p-6 text-center text-slate-400">No students registered in this class.</td></tr>';
        return;
      }

      filtered.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
        tr.innerHTML = `
          <td class="p-3 text-center font-bold font-mono text-slate-500">${student.roll_no || index + 1}</td>
          <td class="p-3 font-bold text-white">${student.name}</td>
          <td class="p-3 text-center">
            <input type="checkbox" onchange="toggleStudentPresent('${student.reg_no}', this.checked)" ${student.present ? 'checked' : ''} class="w-5 h-5 rounded bg-slate-950 border-slate-700 text-indigo-500 focus:ring-indigo-600 cursor-pointer">
          </td>
        `;
        container.appendChild(tr);
      });
    }

    function renderGrid() {
      const container = document.getElementById('studentGridContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<div class="col-span-full p-6 text-center text-slate-400">No students registered.</div>';
        return;
      }

      filtered.forEach((student, index) => {
        const roll = student.roll_no || index + 1;
        const btn = document.createElement('button');
        btn.onclick = () => {
          student.present = !student.present;
          renderGrid();
        };
        
        if (student.present) {
          btn.className = "py-3 rounded-xl font-bold bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-sm text-center cursor-pointer hover:bg-emerald-600/30 transition-premium shadow-inner shadow-emerald-500/10";
        } else {
          btn.className = "py-3 rounded-xl font-bold bg-rose-600/20 text-rose-400 border border-rose-500/30 text-sm text-center cursor-pointer hover:bg-rose-600/30 transition-premium shadow-inner shadow-rose-500/10";
        }
        btn.innerText = roll;
        container.appendChild(btn);
      });
    }

    function toggleStudentPresent(regNo, isPresent) {
      const student = currentStudents.find(s => s.reg_no === regNo);
      if (student) {
        student.present = isPresent;
      }
    }

    function toggleAllCheckboxes() {
      isAllChecked = !isAllChecked;
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isAllChecked);
      document.getElementById('btnCheckAll').innerText = isAllChecked ? "Mark All Absent" : "Mark All Present";
      renderList();
    }

    function toggleAllGrid(isPresent) {
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isPresent);
      renderGrid();
    }

    function saveAttendanceAndLog() {
      const subjectSelect = document.getElementById('subjectSelect');
      const subjectId = subjectSelect ? subjectSelect.value : '';
      const dateSelect = document.getElementById('logDate');
      const date = dateSelect ? dateSelect.value : '';
      
      const checkedPeriods = Array.from(document.querySelectorAll('input[name="logPeriods"]:checked')).map(el => parseInt(el.value));
      const lpSelect = document.getElementById('lessonPlanSelect');
      const lpId = lpSelect ? lpSelect.value : '';
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
        showMessage("Please describe the topics covered in class today.", true);
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

      fetch('/api/staff/attendance/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          batch_subject_id: subjectId,
          date: date,
          periods: checkedPeriods,
          lesson_plan_id: (lpId && !isNaN(parseInt(lpId))) ? parseInt(lpId) : null,
          topics_covered: topics,
          present_students: present,
          absent_students: absent,
          sub_batch: subBatchVal
        })
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
          showMessage(data.message || "Class log and attendance recorded successfully!", false);
          setTimeout(() => {
            window.location.href = "{{ $backLink }}";
          }, 1800);
        } else {
          showMessage(data.message || "Failed to save attendance log.", true);
        }
      })
      .catch(err => {
        console.error('Attendance Save Error:', err);
        showMessage(err.message || "Error saving log and attendance.", true);
      });
    }
  </script>
</body>
</html>
