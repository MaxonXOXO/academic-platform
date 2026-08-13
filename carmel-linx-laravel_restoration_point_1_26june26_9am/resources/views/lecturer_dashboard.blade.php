<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Lecturer Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <!-- Flatpickr for premium Date/Time selection -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  
  <style>
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center text-lg shadow-lg shadow-blue-500/20">CL</div>
      <div>
        <h2 class="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Lecturer Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-xs block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-[9px] font-bold text-teal-400 block uppercase tracking-wider">{{ session('userBranch') }} Lecturer</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">grid_view</span> My Batches
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif
      
      <a href="/course-files" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30 hover:text-amber-300 cursor-pointer no-underline">
         <span class="material-symbols-rounded text-lg">folder_open</span> Course Files
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-4">
        <span class="material-symbols-rounded text-lg">security</span> My Security Log
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="/logout" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-lg font-extrabold text-slate-100 tracking-tight">My Batches</h1>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD (BATCH CARDS) -->
      <div id="panelDashboard" class="space-y-6">
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-xs font-black text-slate-200">My Assigned Batches & Classrooms</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Select a subject to enter the virtual classroom for assignments and assessments.</p>
          </div>
        </div>
        
        <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading batches...</div>
        </div>
      </div>

      <!-- PANEL: VIRTUAL CLASSROOM -->
      <div id="panelClassroom" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between">
          <div>
            <button onclick="switchPanel('dashboard')" class="text-[10px] font-bold text-slate-400 hover:text-white uppercase tracking-wider flex items-center gap-1 transition-premium mb-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Dashboard
            </button>
            <h3 id="vcTitle" class="text-sm font-black text-slate-200 flex items-center gap-2 mt-1">
              <span class="material-symbols-rounded text-blue-400 text-lg">meeting_room</span> VIrtual theory classroom  R-2021
            </h3>
            <p id="vcSubtitle" class="text-[10px] text-slate-400 mt-0.5 font-mono">Loading...</p>
          </div>
          <button id="vcViewStudentsBtn" onclick="alert('Student list coming soon for this classroom!')" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-md border border-slate-700/60">
            <span class="material-symbols-rounded text-sm">groups</span> View Students
          </button>
        </div>

        <!-- Top Banner: Course File Actions -->
        <div class="flex flex-col md:flex-row gap-6 mb-6">
             <!-- Syllabus Setup Card -->
             <div class="flex-grow bg-slate-950/40 border border-slate-800/60 p-4 rounded-2xl relative overflow-hidden group flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <div id="syllabusUploadBox" class="border border-dashed border-slate-700/60 rounded-xl px-4 py-2 text-center hover:border-blue-500/50 hover:bg-slate-900/40 transition-premium cursor-pointer relative z-10 flex items-center gap-3" onclick="document.getElementById('syllabusFileInput').click()">
                    <span class="material-symbols-rounded text-3xl text-slate-500">picture_as_pdf</span>
                    <div class="text-left">
                      <p class="text-xs font-bold text-slate-300">Upload Syllabus PDF</p>
                      <p class="text-[9px] text-slate-500">Max 10MB</p>
                    </div>
                    <input type="file" id="syllabusFileInput" class="hidden" accept="application/pdf" onchange="handleSyllabusUpload(this)">
                  </div>
                  
                  <div id="syllabusUploadProgress" class="hidden relative z-10 flex-col justify-center min-w-[200px]">
                    <div class="flex justify-between text-[10px] font-bold text-blue-400 mb-1">
                      <span>Extracting...</span>
                      <span id="syllabusProgressText" class="animate-pulse">Processing</span>
                    </div>
                    <div class="w-full bg-slate-900 rounded-full h-1.5 border border-slate-800 overflow-hidden">
                      <div class="bg-gradient-to-r from-blue-600 to-sky-400 h-1.5 rounded-full w-full animate-[progress_2s_ease-in-out_infinite]"></div>
                    </div>
                  </div>
                </div>
                <span id="parseStatusBadge" class="text-[10px] font-bold px-3 py-1.5 rounded-md bg-slate-800/80 text-slate-400 border border-slate-700/50 whitespace-nowrap">Waiting for upload</span>
             </div>

             <!-- Download Active Syllabus Card -->
             <div id="activeSyllabusCard" class="hidden bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl flex items-center gap-4 transition-premium border-l-2 border-l-emerald-500 min-w-[250px]">
                <div class="bg-emerald-500/10 p-2 rounded-lg flex-shrink-0">
                  <span class="material-symbols-rounded text-emerald-400 text-lg block">check_circle</span>
                </div>
                <div class="flex-grow">
                  <h4 class="text-xs font-black text-slate-200">Active Syllabus</h4>
                  <p class="text-[9px] text-slate-400">Parsed & ready</p>
                </div>
                <a id="downloadSyllabusBtn" href="#" target="_blank" class="text-slate-400 hover:text-blue-400 transition-premium bg-slate-900/50 p-1.5 rounded-lg border border-slate-800 hover:border-blue-500/50">
                  <span class="material-symbols-rounded text-base block">download</span>
                </a>
             </div>
        </div>
        
        <!-- Toggle Buttons -->
        <div class="flex items-center gap-4 border-b border-slate-800/60 pb-3 mb-4">
            <button onclick="toggleClassroomTab('structure')" id="tabStructure" class="text-sm font-black text-blue-400 flex items-center gap-1.5 transition-premium border-b-2 border-blue-500 pb-1">
              <span class="material-symbols-rounded text-lg">account_tree</span> Course Structure
            </button>
            <button onclick="toggleClassroomTab('planner')" id="tabPlanner" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-lg">calendar_month</span> Lesson Planner
            </button>
            <button onclick="toggleClassroomTab('assessment')" id="tabAssessment" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-lg">assignment_turned_in</span> Formative Assessment
            </button>
            <button onclick="toggleClassroomTab('summative')" id="tabSummative" class="text-sm font-bold text-slate-500 hover:text-slate-300 flex items-center gap-1.5 transition-premium pb-1 border-b-2 border-transparent hover:border-slate-600">
              <span class="material-symbols-rounded text-lg">school</span> Summative Assessment
            </button>
        </div>

        <!-- Parsed Data View (Full Width) -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl min-h-[400px] flex flex-col w-full">
            <div id="courseStructureContent" class="space-y-6 flex-grow overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-4xl text-slate-600">inventory_2</span>
                </div>
                <p class="text-xs font-bold text-slate-400">No syllabus loaded.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
              </div>
            </div>
            
            <div id="coursePlannerContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-4xl text-slate-600">event_note</span>
                </div>
                <p class="text-xs font-bold text-slate-400">Planner not generated.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
              </div>
            </div>

            <div id="formativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-4xl text-slate-600">quiz</span>
                </div>
                <p class="text-xs font-bold text-slate-400">No students or COs available.</p>
                <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks.</p>
              </div>
            </div>

            <div id="summativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-4xl text-slate-600">school</span>
                </div>
                <p class="text-xs font-bold text-slate-400">Loading summative assessments...</p>
              </div>
            </div>
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG -->
      <div id="panelSecurity" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-sm font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Profile Security Audit trail
          </h3>
          <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                  <th class="p-4">Time</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="securityLogsTable">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      loadLecturerBatches();
      if (activePanel === 'security') loadSecurityLogs();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security', 'classroom'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile Security Log',
        'classroom': 'VIrtual theory classroom  R-2021'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Lecturer Console';

      if (panelId === 'security') loadSecurityLogs();
      if (panelId === 'dashboard') loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs animate-pulse">Loading batches...</div>';

      fetch('/api/lecturer/my-batches', {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-xs">${data.message}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-xs">Error loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
            <span class="material-symbols-rounded text-5xl text-slate-700 mb-3">sentiment_dissatisfied</span>
            <p class="font-bold text-slate-300 text-sm">No batches assigned</p>
            <p class="text-xs text-slate-500 mt-1">You have not been assigned as a Tutor, Mentor, or Subject Staff for any batches yet.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let color = 'slate';
          if (r === 'Tutor') color = 'sky';
          if (r === 'Mentor') color = 'emerald';
          if (r === 'Subject Staff') color = 'violet';
          rolesHtml += `<span class="px-2 py-0.5 rounded text-[9px] font-bold bg-${color}-500/10 text-${color}-400 border border-${color}-500/20">${r}</span>`;
        });

        let subjectsHtml = '';
        if (b.subjects && b.subjects.length > 0) {
          b.subjects.forEach(s => {
            subjectsHtml += `
              <button onclick="openClassroom('${b.classroom_id}', '${s.id}', '${s.name} (${s.code})')" class="w-full text-left px-3 py-2 bg-slate-900/60 hover:bg-slate-800 border border-slate-800/60 hover:border-blue-500/50 rounded-xl transition-premium cursor-pointer group flex justify-between items-center">
                <div>
                  <div class="text-xs font-bold text-slate-200 group-hover:text-blue-400 transition-premium">${s.name}</div>
                  <div class="text-[9px] text-slate-500 font-mono">Sem ${s.semester} • ${s.type} • ${s.code}</div>
                </div>
                <span class="material-symbols-rounded text-slate-600 group-hover:text-blue-500 text-sm transition-premium">open_in_new</span>
              </button>
            `;
          });
        } else {
          subjectsHtml = `<div class="text-xs text-slate-500 italic px-2 py-2">No subjects assigned in this batch.</div>`;
        }

        const card = document.createElement('div');
        card.className = "bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-hidden flex flex-col transition-premium hover:shadow-xl hover:shadow-black/50 hover:border-slate-700/60";
        card.innerHTML = `
          <div class="p-4 border-b border-slate-800/60 bg-slate-900/40">
            <div class="flex justify-between items-start mb-2">
              <div>
                <h4 class="font-black text-slate-200 text-sm tracking-tight">${b.classroom_id}</h4>
                <div class="text-[10px] text-slate-400 font-mono mt-0.5">${b.branch} • Year ${b.batch_year}</div>
              </div>
              <div class="flex flex-wrap gap-1 justify-end max-w-[50%]">
                ${rolesHtml}
              </div>
            </div>
          </div>
          
          <div class="p-4 flex-grow space-y-3 bg-slate-950/20">
            <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><span class="material-symbols-rounded text-xs">book</span> Assigned Subjects</h5>
            <div class="space-y-2">
              ${subjectsHtml}
            </div>
          </div>
        `;
        grid.appendChild(card);
      });
    }

    let currentSubjectId = null;

    function openClassroom(batchId, subjectId, subjectName) {
      currentSubjectId = subjectId;
      document.getElementById('vcTitle').innerHTML = `<span class="material-symbols-rounded text-blue-400 text-lg">meeting_room</span> VIrtual theory classroom  R-2021`;
      document.getElementById('vcSubtitle').innerText = `${subjectName} • Batch: ${batchId}`;
      switchPanel('classroom');
      loadCourseDetails(subjectId);
    }

    function handleSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      if (!currentSubjectId) return;

      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      document.getElementById('syllabusUploadBox').classList.add('hidden');
      document.getElementById('syllabusUploadProgress').classList.remove('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Extracting...';
      document.getElementById('parseStatusBadge').className = 'text-[9px] font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      fetch(`/api/classroom/${currentSubjectId}/syllabus`, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        if (data.status === 'SUCCESS') {
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          document.getElementById('activeSyllabusCard').classList.remove('hidden');
          alert('Syllabus successfully parsed!');
        } else {
          alert(data.message);
          document.getElementById('parseStatusBadge').innerText = 'Upload Failed';
          document.getElementById('parseStatusBadge').className = 'text-[9px] font-bold px-2.5 py-1 rounded-md bg-red-900/30 text-red-400 border border-red-500/30';
        }
      })
      .catch(err => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        alert('Failed to upload syllabus.');
      });
    }

    function toggleClassroomTab(tabName) {
      const tabs = [
        { id: 'structure', btn: 'tabStructure', content: 'courseStructureContent' },
        { id: 'planner', btn: 'tabPlanner', content: 'coursePlannerContent' },
        { id: 'assessment', btn: 'tabAssessment', content: 'formativeAssessmentContent' },
        { id: 'summative', btn: 'tabSummative', content: 'summativeAssessmentContent' }
      ];

      tabs.forEach(t => {
        const btn = document.getElementById(t.btn);
        const content = document.getElementById(t.content);
        
        if (t.id === tabName) {
          btn.classList.add('border-blue-500', 'text-blue-400');
          btn.classList.remove('border-transparent', 'text-slate-500', 'hover:border-slate-600', 'hover:text-slate-300');
          
          content.classList.remove('hidden');
          if (t.id !== 'structure') content.classList.add('flex');
        } else {
          btn.classList.remove('border-blue-500', 'text-blue-400');
          btn.classList.add('border-transparent', 'text-slate-500', 'hover:border-slate-600', 'hover:text-slate-300');
          
          content.classList.add('hidden');
          if (t.id !== 'structure') content.classList.remove('flex');
        }
      });
    }

    let currentDeadlines = {};
    let currentQuestions = {};
    let currentSummativeTests = {};
    let currentSubjectName = '';
    let currentSubjectCode = '';

    function loadCourseDetails(subjectId) {
      document.getElementById('courseStructureContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-xs font-bold text-slate-400">Loading course data...</p>
        </div>
      `;
      document.getElementById('coursePlannerContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-xs font-bold text-slate-400">Loading planner...</p>
        </div>
      `;
      document.getElementById('activeSyllabusCard').classList.add('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Syncing...';
      document.getElementById('parseStatusBadge').className = 'text-[9px] font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
          currentDeadlines = data.data.assignment_deadlines || {};
          currentQuestions = data.data.assignment_questions || {};
          currentSummativeTests = data.data.summative_manual_tests || {};
          currentSubjectName = data.data.subject_name || '';
          currentSubjectCode = data.data.subject_code || '';
          currentCos = data.data.cos || [];
          
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          renderFormativeAssessment(data.data.students || []);
          renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);
          
          if (Object.keys(currentQuestions).length > 0) {
            renderAIQuestionsList(currentQuestions, subjectId);
          }

          if (data.data.syllabus_pdf_path) {
            document.getElementById('activeSyllabusCard').classList.remove('hidden');
            document.getElementById('downloadSyllabusBtn').href = data.data.syllabus_pdf_path;
          }
        } else {
          currentCos = [];
          document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
          document.getElementById('parseStatusBadge').className = 'text-[9px] font-bold px-2.5 py-1 rounded-md bg-slate-800/80 text-slate-400 border border-slate-700/50';
          document.getElementById('courseStructureContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-4xl text-slate-600">inventory_2</span>
              </div>
              <p class="text-xs font-bold text-slate-400">No syllabus loaded.</p>
              <p class="text-[10px] mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
            </div>
          `;
          renderCoursePlanner([]);
        }
      });
    }

    let currentCos = [];

    function autoGrowTextarea(element) {
      if (!element) return;
      element.style.height = 'auto';
      element.style.height = Math.max(38, element.scrollHeight) + 'px';
    }

    function renderCoursePlanner(lessonPlans) {
      if (!lessonPlans) lessonPlans = [];
      
      let totalHours = lessonPlans.reduce((sum, lp) => sum + (parseInt(lp.allocated_hours) || 0), 0);

      let availableCos = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'];
      if (currentCos && currentCos.length > 0) {
        currentCos.forEach(c => {
          if (c.id && !availableCos.includes(c.id)) availableCos.push(c.id);
        });
      }

      let html = `
        <div class="flex justify-between items-end mb-4">
          <div>
            <h4 class="text-sm font-black text-slate-200">Interactive Lesson Planner</h4>
            <p class="text-[10px] text-slate-500 mt-1">Set proposed dates, select COs, edit multi-line topics, and record actual dates.</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 bg-slate-900/50 px-3 py-1.5 rounded-lg border border-slate-800/50">
            Total Est. Hours: <span id="lpTotalHoursDisplay" class="text-emerald-400 ml-1 text-xs font-mono">${totalHours}</span>
          </div>
        </div>
        
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="bg-slate-900/80 text-[9px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                    <th class="p-3 w-16 text-center">Day No</th>
                    <th class="p-3 w-32">Proposed Date</th>
                    <th class="p-3 w-24">CO</th>
                    <th class="p-3">Topic / Content</th>
                    <th class="p-3 text-center w-20">Hours</th>
                    <th class="p-3 w-32">Actual Date</th>
                    <th class="p-3 w-32">Pedagogy</th>
                    <th class="p-3 w-36">Remarks</th>
                    <th class="p-3 w-12 text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="lessonPlanTbody">
      `;

      if (lessonPlans.length === 0) {
        lessonPlans = [{ day_no: 1, proposed_date: '', co_id: 'CO1', topic_content: '', allocated_hours: 1, actual_date: '', pedagogy: 'Lecture', remarks: '' }];
      }

      lessonPlans.forEach((lp, index) => {
        let proposed = lp.proposed_date ? lp.proposed_date : '';
        let actual = lp.actual_date ? lp.actual_date : '';
        let pedagogy = lp.pedagogy || 'Lecture';
        let remarks = lp.remarks || '';
        let dayNo = lp.day_no || (index + 1);
        let coVal = lp.co_id || 'CO1';

        let coSelectOptions = `<option value="">-</option>`;
        let coFound = false;
        availableCos.forEach(co => {
          let sel = (coVal === co) ? 'selected' : '';
          if (coVal === co) coFound = true;
          coSelectOptions += `<option value="${co}" ${sel}>${co}</option>`;
        });
        if (!coFound && coVal) {
          coSelectOptions += `<option value="${coVal}" selected>${coVal}</option>`;
        }

        html += `
          <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]">
            <td class="p-2.5 text-center">
              <input type="number" value="${dayNo}" class="lp-day-no w-12 bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-slate-300 text-[10px] text-center font-bold focus:outline-none focus:border-blue-500/50 font-mono">
            </td>
            <td class="p-2.5">
              <input type="date" value="${proposed}" class="lp-proposed-date w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 font-mono">
            </td>
            <td class="p-2.5">
              <select class="lp-co w-full bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-blue-400 text-[10px] font-bold focus:outline-none focus:border-blue-500/50">
                ${coSelectOptions}
              </select>
            </td>
            <td class="p-2.5">
              <textarea class="lp-topic w-full bg-slate-900/80 border border-slate-700/60 rounded p-2 text-slate-200 text-[11px] focus:outline-none focus:border-blue-500/50 leading-relaxed resize-none overflow-hidden" rows="2" oninput="autoGrowTextarea(this)" placeholder="Enter topic content...">${lp.topic_content || ''}</textarea>
            </td>
            <td class="p-2.5 text-center">
              <input type="number" min="1" value="${lp.allocated_hours || 1}" class="lp-hours w-full bg-slate-900/80 border border-slate-700/60 rounded px-1 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center font-mono" onchange="recalculateTotalHours()">
            </td>
            <td class="p-2.5">
              <input type="date" value="${actual}" class="lp-actual-date w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 font-mono text-emerald-400">
            </td>
            <td class="p-2.5">
              <input type="text" value="${pedagogy}" class="lp-pedagogy w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Lecture...">
            </td>
            <td class="p-2.5">
              <input type="text" value="${remarks}" class="lp-remarks w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Remarks...">
            </td>
            <td class="p-2.5 text-center">
              <button onclick="deleteLessonPlanRow(this)" class="p-1 rounded text-slate-500 hover:text-red-400 hover:bg-red-950/40 transition-premium cursor-pointer" title="Remove Row">
                <span class="material-symbols-rounded text-sm block">delete</span>
              </button>
            </td>
          </tr>
        `;
      });

      html += `
                </tbody>
              </table>
          </div>

          <!-- Bottom Action Controls (Add Row & Save Buttons) -->
          <div class="p-4 bg-slate-900/60 border-t border-slate-800/60 flex items-center justify-between">
            <button onclick="addLessonPlanRow()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-blue-400 hover:text-blue-300 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 border border-slate-700/60 cursor-pointer shadow-md">
              <span class="material-symbols-rounded text-base">add</span> Add Row
            </button>

            <button onclick="saveLessonPlans()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-lg shadow-emerald-900/20">
              <span class="material-symbols-rounded text-base">save</span> Save Lesson Plan
            </button>
          </div>
        </div>
      `;

      document.getElementById('coursePlannerContent').innerHTML = html;

      setTimeout(() => {
        document.querySelectorAll('#lessonPlanTbody textarea.lp-topic').forEach(ta => autoGrowTextarea(ta));
      }, 50);
    }

    function addLessonPlanRow() {
      const tbody = document.getElementById('lessonPlanTbody');
      if (!tbody) return;
      const rowCount = tbody.querySelectorAll('tr').length;
      const dayNo = rowCount + 1;
      
      let availableCos = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'];
      if (currentCos && currentCos.length > 0) {
        currentCos.forEach(c => {
          if (c.id && !availableCos.includes(c.id)) availableCos.push(c.id);
        });
      }
      let coOptions = `<option value="">-</option>`;
      availableCos.forEach(co => {
        coOptions += `<option value="${co}">${co}</option>`;
      });

      const tr = document.createElement('tr');
      tr.className = "border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]";
      tr.innerHTML = `
        <td class="p-2.5 text-center">
          <input type="number" value="${dayNo}" class="lp-day-no w-12 bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-slate-300 text-[10px] text-center font-bold focus:outline-none focus:border-blue-500/50 font-mono">
        </td>
        <td class="p-2.5">
          <input type="date" value="" class="lp-proposed-date w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 font-mono">
        </td>
        <td class="p-2.5">
          <select class="lp-co w-full bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-blue-400 text-[10px] font-bold focus:outline-none focus:border-blue-500/50">${coOptions}</select>
        </td>
        <td class="p-2.5">
          <textarea class="lp-topic w-full bg-slate-900/80 border border-slate-700/60 rounded p-2 text-slate-200 text-[11px] focus:outline-none focus:border-blue-500/50 leading-relaxed resize-none overflow-hidden" rows="2" oninput="autoGrowTextarea(this)" placeholder="Enter topic content..."></textarea>
        </td>
        <td class="p-2.5 text-center">
          <input type="number" min="1" value="1" class="lp-hours w-full bg-slate-900/80 border border-slate-700/60 rounded px-1 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center font-mono" onchange="recalculateTotalHours()">
        </td>
        <td class="p-2.5">
          <input type="date" value="" class="lp-actual-date w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 font-mono text-emerald-400">
        </td>
        <td class="p-2.5">
          <input type="text" value="Lecture" class="lp-pedagogy w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Lecture...">
        </td>
        <td class="p-2.5">
          <input type="text" value="" class="lp-remarks w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50" placeholder="Remarks...">
        </td>
        <td class="p-2.5 text-center">
          <button onclick="deleteLessonPlanRow(this)" class="p-1 rounded text-slate-500 hover:text-red-400 hover:bg-red-950/40 transition-premium cursor-pointer" title="Remove Row">
            <span class="material-symbols-rounded text-sm block">delete</span>
          </button>
        </td>
      `;
      tbody.appendChild(tr);
      recalculateTotalHours();
      const newTa = tr.querySelector('textarea.lp-topic');
      if (newTa) {
        autoGrowTextarea(newTa);
        newTa.focus();
      }
    }

    function deleteLessonPlanRow(btn) {
      const tr = btn.closest('tr');
      if (tr) {
        tr.remove();
        recalculateTotalHours();
      }
    }

    function recalculateTotalHours() {
      const hourInputs = document.querySelectorAll('#lessonPlanTbody .lp-hours');
      let total = 0;
      hourInputs.forEach(inp => {
        total += (parseInt(inp.value) || 0);
      });
      const totalEl = document.getElementById('lpTotalHoursDisplay');
      if (totalEl) totalEl.innerText = total;
    }

    function saveLessonPlans(subjectId) {
      if (!subjectId) subjectId = currentSubjectId;
      if (!subjectId) {
        alert("No active subject selected.");
        return;
      }

      const rows = document.querySelectorAll('#lessonPlanTbody tr');
      let plansPayload = [];

      rows.forEach(tr => {
        const dayNo = tr.querySelector('.lp-day-no')?.value;
        const proposedDate = tr.querySelector('.lp-proposed-date')?.value;
        const coId = tr.querySelector('.lp-co')?.value;
        const topicContent = tr.querySelector('.lp-topic')?.value;
        const allocatedHours = tr.querySelector('.lp-hours')?.value;
        const actualDate = tr.querySelector('.lp-actual-date')?.value;
        const pedagogy = tr.querySelector('.lp-pedagogy')?.value;
        const remarks = tr.querySelector('.lp-remarks')?.value;

        if (topicContent && topicContent.trim() !== '') {
          plansPayload.push({
            day_no: dayNo,
            proposed_date: proposedDate,
            co_id: coId,
            topic_content: topicContent,
            allocated_hours: allocatedHours,
            actual_date: actualDate,
            pedagogy: pedagogy,
            remarks: remarks
          });
        }
      });

      if (plansPayload.length === 0) {
        alert("No lesson plan topics entered to save.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-lesson-plans`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ lesson_plans: plansPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Lesson Plan saved successfully!");
          loadCourseDetails(subjectId);
        } else {
          alert(data.message || "Failed to save lesson plan.");
        }
      })
      .catch(err => {
        alert("Error saving lesson plan.");
      });
    }

    function renderFormativeAssessment(students) {
      let html = `
        <div class="flex items-center justify-between mb-4">
          <div>
            <h4 class="text-sm font-black text-slate-200">Formative Assessment (Assignments)</h4>
            <p class="text-[10px] text-slate-500 mt-1">Generate AI questions for each CO and record 10-mark evaluations.</p>
          </div>
          <button onclick="generateAIQuestions('${currentSubjectId}')" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-900/20">
            <span class="material-symbols-rounded text-sm">smart_toy</span> AI Generate Questions
          </button>
        </div>

        <div id="aiQuestionsContainer" class="hidden grid-cols-1 md:grid-cols-2 gap-4 mb-6"></div>

        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between">
            <div class="font-bold text-[11px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-sky-400">group</span> Enrolled Students
            </div>
            <button onclick="saveAssignmentMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600/80 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-premium">
              Save Marks
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
              <thead>
                <tr class="bg-slate-900/40 text-[9px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-16">Reg No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-center w-24">CO1 (10)</th>
                  <th class="p-3 text-center w-24">CO2 (10)</th>
                  <th class="p-3 text-center w-24">CO3 (10)</th>
                  <th class="p-3 text-center w-24">CO4 (10)</th>
                </tr>
              </thead>
              <tbody id="markEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach(student => {
          let m = student.assignment_marks || {};
          html += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]" data-reg="${student.reg_no}">
              <td class="p-3 font-mono text-slate-500">${student.reg_no}</td>
              <td class="p-3 font-bold text-slate-300">${student.name}</td>
              <td class="p-3"><input type="number" max="10" min="0" value="${m.CO1 !== null ? m.CO1 : ''}" class="co-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" max="10" min="0" value="${m.CO2 !== null ? m.CO2 : ''}" class="co-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" max="10" min="0" value="${m.CO3 !== null ? m.CO3 : ''}" class="co-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" max="10" min="0" value="${m.CO4 !== null ? m.CO4 : ''}" class="co-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        html += `<tr><td colspan="6" class="p-6 text-center text-slate-500 text-xs font-bold">No students found in this classroom.</td></tr>`;
      }

      html += `</tbody></table></div></div>`;
      document.getElementById('formativeAssessmentContent').innerHTML = html;
    }

    function renderAIQuestionsList(questionsData, subjectId) {
      document.getElementById('aiQuestionsContainer').classList.remove('hidden');
      let html = '';
      for (const [co, qs] of Object.entries(questionsData)) {
        let qList = qs.map(q => `<li class="text-[10px] text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
        let schedule = currentDeadlines[co] || { start: '', due: '', locked: false };
        if (typeof schedule === 'string') schedule = { start: '', due: schedule, locked: false }; // Legacy fallback
        
        let isLocked = schedule.locked;
        let lockStr = isLocked ? `<span class="material-symbols-rounded text-[10px] text-amber-500 ml-1" title="Locked">lock</span>` : '';
        let disabledAttr = isLocked ? 'disabled' : '';
        let regenBtn = isLocked ? '' : `
                <button onclick="generateAIQuestions('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-blue-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Regenerate Questions">
                  <span class="material-symbols-rounded text-[14px] block">refresh</span>
                </button>
        `;
        let lockBtn = isLocked ? '' : `
                <button onclick="toggleAssignmentLock('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
                  <span class="material-symbols-rounded text-[14px] block">lock</span>
                </button>
        `;

        html += `
          <div class="bg-slate-900/50 border border-slate-800/60 p-4 rounded-xl relative overflow-hidden group ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-premium pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-3 border-b border-slate-800/60 pb-2 relative z-10">
              <h5 class="text-xs font-black text-blue-400 flex items-center gap-1">
                <span class="px-1.5 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-[9px] mr-1">${co}</span> Assignment ${lockStr}
              </h5>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[9px] text-slate-500 font-bold uppercase">Start</span>
                  <input type="date" value="${schedule.start || ''}" ${disabledAttr} class="bg-transparent text-[9px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'start', this.value)">
                </div>
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[9px] text-slate-500 font-bold uppercase">Due</span>
                  <input type="date" value="${schedule.due || ''}" ${disabledAttr} class="bg-transparent text-[9px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'due', this.value)">
                </div>
                ${regenBtn}
                ${lockBtn}
              </div>
            </div>
            
            <ul id="questions-list-${co}" class="list-none m-0 p-0 relative z-10 min-h-[60px]">${qList}</ul>
          </div>
        `;
      }
      document.getElementById('aiQuestionsContainer').innerHTML = html;
    }

    function generateAIQuestions(subjectId, coTag = null) {
      if (!coTag) {
        document.getElementById('aiQuestionsContainer').classList.remove('hidden');
        document.getElementById('aiQuestionsContainer').innerHTML = `<div class="col-span-full text-center py-4 text-xs font-bold text-blue-400 animate-pulse">AI is generating questions...</div>`;
      } else {
        const ul = document.getElementById(`questions-list-${coTag}`);
        if(ul) ul.innerHTML = `<li class="text-[10px] text-blue-400 animate-pulse">Regenerating...</li>`;
      }
      
      let url = `/api/classroom/${subjectId}/generate-questions?_t=${Date.now()}`;
      if (coTag) url += `&co_tag=${coTag}`;

      fetch(url)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (!coTag) {
             currentQuestions = data.data;
             renderAIQuestionsList(currentQuestions, subjectId);
          } else {
             // Only update the specific CO list
             currentQuestions[coTag] = data.data[coTag];
             const ul = document.getElementById(`questions-list-${coTag}`);
             if (ul && data.data[coTag]) {
               ul.innerHTML = data.data[coTag].map(q => `<li class="text-[10px] text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
             }
          }
        }
      });
    }

    function updateAssignmentSchedule(subjectId, coTag, type, dateValue) {
      let payload = { co_tag: coTag };
      if (type === 'start') payload.start_date = dateValue;
      if (type === 'due') payload.due_date = dateValue;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           if (type === 'start') currentDeadlines[coTag].start = dateValue;
           if (type === 'due') currentDeadlines[coTag].due = dateValue;
           console.log(`Schedule for ${coTag} updated.`);
        } else {
           alert(data.message);
        }
      });
    }

    function toggleAssignmentLock(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} questions? This cannot be easily undone.`)) return;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           currentDeadlines[coTag].locked = true;
           renderAIQuestionsList(currentQuestions, subjectId);
        } else {
           alert(data.message);
        }
      });
    }

    function saveAssignmentMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#markEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.co-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-assignment-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updateProposedDate(lessonPlanId, dateValue) {
        console.log("Updating lesson plan", lessonPlanId, "with date", dateValue);
    }

    function renderCourseStructure(cos, modules, textbooks, copo) {
      document.getElementById('parseStatusBadge').innerText = 'Parsed Successfully';
      document.getElementById('parseStatusBadge').className = 'text-[9px] font-bold px-2.5 py-1 rounded-md bg-emerald-900/30 text-emerald-400 border border-emerald-500/30';
      
      let html = '';

      if (cos && cos.length > 0) {
        let cosList = cos.map(co => `
          <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]">
            <td class="p-3 font-bold text-blue-400 whitespace-nowrap">${co.id}</td>
            <td class="p-3 text-slate-300 leading-relaxed">${co.description}</td>
            <td class="p-3 text-center text-slate-400">${co.duration ? co.duration + ' hrs' : '-'}</td>
            <td class="p-3 text-emerald-400 font-mono">${co.cognitive_level || '-'}</td>
          </tr>
        `).join('');
        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-[11px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-sky-400">target</span> Course Outcomes
            </div>
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/40 text-[9px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-16">CO</th>
                  <th class="p-3">Description</th>
                  <th class="p-3 text-center w-20">Duration</th>
                  <th class="p-3 w-32">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>
                ${cosList}
              </tbody>
            </table>
          </div>
        `;
      }

      if (copo && Object.keys(copo).length > 0) {
        let copoList = Object.keys(copo).map(coKey => {
            let mapping = copo[coKey];
            let poCells = '';
            for(let i = 1; i <= 12; i++) {
                let val = mapping['PO' + i] || '-';
                poCells += `<td class="p-2 text-center text-slate-400 ${val !== '-' ? 'font-bold text-emerald-400' : ''}">${val}</td>`;
            }
            return `
              <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]">
                <td class="p-2 font-bold text-blue-400 whitespace-nowrap border-r border-slate-800/60">${coKey}</td>
                ${poCells}
              </tr>
            `;
        }).join('');
        
        let poHeaders = '';
        for(let i=1; i<=12; i++) {
            poHeaders += `<th class="p-2 text-center">PO${i}</th>`;
        }

        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-[11px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-amber-400">grid_on</span> CO-PO Mapping Matrix
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-900/40 text-[9px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                      <th class="p-2 w-16 border-r border-slate-800/60">CO</th>
                      ${poHeaders}
                    </tr>
                  </thead>
                  <tbody>
                    ${copoList}
                  </tbody>
                </table>
            </div>
          </div>
        `;
      }

      if (html === '') {
        html = `<div class="p-6 text-center text-xs text-slate-500 border border-dashed border-slate-700/50 rounded-xl">Could not extract structured data. The syllabus might have an unparseable format.</div>`;
      }

      document.getElementById('courseStructureContent').innerHTML = html;
    }

    function renderSummativeAssessment(cos, students) {
      let html = `
        <div class="flex items-center justify-between mb-4 no-print">
          <div>
            <h4 class="text-sm font-black text-slate-200">Summative Assessment (Manual Tests)</h4>
            <p class="text-[10px] text-slate-500 mt-1">Configure and generate precise Cognitive Level based question papers for each CO.</p>
          </div>
        </div>
      `;

      // Build the marks entry table FIRST so it's at the top
      let marksEntryHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('manualMarksWrapper').classList.toggle('hidden'); document.getElementById('marksToggleIcon').innerText = document.getElementById('manualMarksWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-[11px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-emerald-400">edit_document</span> Enter Manual Marks
              <span id="marksToggleIcon" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
            </div>
            <button onclick="event.stopPropagation(); saveSummativeMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600/80 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-premium">
              Save Written Marks
            </button>
          </div>
          <div id="manualMarksWrapper" class="hidden overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
              <thead>
                <tr class="bg-slate-900/40 text-[9px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-16">Reg No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-center w-24">CO1</th>
                  <th class="p-3 text-center w-24">CO2</th>
                  <th class="p-3 text-center w-24">CO3</th>
                  <th class="p-3 text-center w-24">CO4</th>
                </tr>
              </thead>
              <tbody id="summativeMarkEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach(student => {
          marksEntryHtml += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-[11px]" data-reg="${student.reg_no}">
              <td class="p-3 font-mono text-slate-500">${student.reg_no}</td>
              <td class="p-3 font-bold text-slate-300">${student.name}</td>
              <td class="p-3"><input type="number" min="0" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" min="0" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" min="0" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" min="0" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-blue-500/50 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        marksEntryHtml += `<tr><td colspan="6" class="p-6 text-center text-slate-500 text-xs font-bold">No students found.</td></tr>`;
      }
      marksEntryHtml += `</tbody></table></div></div>`;

      html += marksEntryHtml;

      html += `
        <div id="summativePapersContainer" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6 no-print" style="display: grid;">
      `;

      if (cos && cos.length > 0) {
        cos.forEach(co => {
          let testData = currentSummativeTests[co.id] || null;
          let generatedContent = '';
          
          if (testData) {
            let partAStr = testData.part_a ? testData.part_a.questions.map(q => `<li class="mb-1"><span class="font-mono text-[9px] text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-[9px] text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partBStr = testData.part_b ? testData.part_b.questions.map(q => `<li class="mb-1"><span class="font-mono text-[9px] text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-[9px] text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partCStr = testData.part_c ? testData.part_c.questions.map(q => `<li class="mb-1"><span class="font-mono text-[9px] text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-[9px] text-slate-500">(${q.marks})</span></li>`).join('') : '';

            generatedContent = `
              <div class="mt-4 pt-4 border-t border-slate-800/60" id="paper-${co.id}">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Generated Question Paper</span>
                  <div class="flex items-center gap-2">
                    <button onclick="printSummativePaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1 text-[10px] bg-blue-700/30 hover:bg-blue-600 border border-blue-600/40 px-2 py-1 rounded text-blue-300 hover:text-white transition-premium">
                      <span class="material-symbols-rounded text-[12px]">print</span> Print Q Paper
                    </button>
                    <button onclick="printAnswerKey('${co.id}', ${testData.total_marks})" class="flex items-center gap-1 text-[10px] bg-amber-700/30 hover:bg-amber-600 border border-amber-600/40 px-2 py-1 rounded text-amber-300 hover:text-white transition-premium">
                      <span class="material-symbols-rounded text-[12px]">assignment</span> Print Answer Key
                    </button>
                  </div>
                </div>
                <div class="text-[10px] text-slate-300 bg-slate-950/50 p-3 rounded-lg border border-slate-800/40">
                  ${partAStr ? `<div class="font-bold mb-1 text-slate-400">PART A (Short Answers)</div><ul class="list-decimal pl-4 mb-3">${partAStr}</ul>` : ''}
                  ${partBStr ? `<div class="font-bold mb-1 text-slate-400">PART B (Medium Answers)</div><ul class="list-decimal pl-4 mb-3">${partBStr}</ul>` : ''}
                  ${partCStr ? `<div class="font-bold mb-1 text-slate-400">PART C (Long Answers)</div><ul class="list-decimal pl-4 mb-1">${partCStr}</ul>` : ''}
                </div>
              </div>
            `;
          }

          let isLocked = testData && testData.is_locked ? true : false;
          let disabledAttr = isLocked ? 'disabled' : '';
          let lockStr = isLocked ? `<span class="material-symbols-rounded text-[10px] text-amber-500 ml-1" title="Locked">lock</span>` : '';
          let dateStr = testData && testData.date_of_exam ? testData.date_of_exam : '';

          let qA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qA : (testData?.part_a?.q_count || '');
          let mA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mA : (testData?.part_a?.marks_per_q || '');
          let qB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qB : (testData?.part_b?.q_count || '');
          let mB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mB : (testData?.part_b?.marks_per_q || '');
          let qC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qC : (testData?.part_c?.q_count || '');
          let mC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mC : (testData?.part_c?.marks_per_q || '');

          let lockBtn = isLocked || !testData ? '' : `
            <button onclick="lockSummativeTest('${currentSubjectId}', '${co.id}')" class="p-1 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
              <span class="material-symbols-rounded text-[14px] block">lock</span>
            </button>
          `;

          let genBtn = isLocked ? '' : `
              <button id="gen_btn_${co.id}" onclick="generateSummativePaper('${currentSubjectId}', '${co.id}')" class="w-full py-1.5 bg-blue-600/20 hover:bg-blue-600 border border-blue-500/30 text-blue-400 hover:text-white rounded text-[10px] font-bold transition-premium mt-2">
                ${testData ? 'Regenerate Question Paper' : 'Generate AI Question Paper'}
              </button>
          `;
          
          let dateInputStr = `
            <div class="flex items-center gap-1 bg-slate-800 px-2 py-1 rounded border border-slate-700/80 shadow-inner">
              <span class="text-[9px] text-slate-400 font-bold uppercase"><span class="material-symbols-rounded text-[10px] align-middle mr-0.5">calendar_today</span>Date</span>
              <input type="date" id="summ_date_${co.id}" value="${dateStr}" ${disabledAttr} onchange="saveSummativeConfig('${currentSubjectId}', '${co.id}')" class="bg-slate-900 text-[10px] text-slate-200 font-mono outline-none w-[90px] px-1 py-0.5 rounded border border-slate-700 focus:border-blue-500">
            </div>
          `;

          html += `
            <div class="bg-slate-900/50 border border-slate-800/60 p-4 rounded-xl relative ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
              <div class="flex items-center justify-between mb-3 border-b border-slate-800/60 pb-2 cursor-pointer hover:opacity-80 transition-premium" onclick="document.getElementById('co_body_${co.id}').classList.toggle('hidden'); document.getElementById('co_icon_${co.id}').innerText = document.getElementById('co_body_${co.id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
                <h5 class="text-xs font-black text-blue-400 flex items-center gap-1">
                  <span id="co_icon_${co.id}" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
                  ${co.id} Written Test ${lockStr}
                </h5>
                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                  ${dateInputStr}
                  ${lockBtn}
                </div>
              </div>

              <div id="co_body_${co.id}" class="hidden pt-2">

              <div class="flex items-center gap-3 mb-3 mt-1 text-[9px] font-bold text-slate-500 bg-slate-950/50 p-1.5 rounded-lg border border-slate-800/40 w-max">
                 <label class="flex items-center gap-1 cursor-pointer hover:text-blue-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="ai" checked onchange="toggleSummativeMode('${co.id}')" class="text-blue-500 focus:ring-blue-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   AI Generation
                 </label>
                 <label class="flex items-center gap-1 cursor-pointer hover:text-emerald-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="manual" onchange="toggleSummativeMode('${co.id}')" class="text-emerald-500 focus:ring-emerald-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   Manual Entry
                 </label>
              </div>
              
              <div class="space-y-2 mb-3">
                <div class="flex justify-between text-[10px] text-slate-400 font-bold mb-1"><span class="w-16">Part</span><span>Q. Count</span><span>Marks/Q</span></div>
                <div class="flex items-center justify-between gap-2">
                  <span class="text-[10px] text-slate-500 font-bold w-16">PART A</span>
                  <input type="number" id="summ_q_A_${co.id}" value="${qA}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-xs font-bold">x</span>
                  <input type="number" id="summ_m_A_${co.id}" value="${mA}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-2">
                  <span class="text-[10px] text-slate-500 font-bold w-16">PART B</span>
                  <input type="number" id="summ_q_B_${co.id}" value="${qB}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-xs font-bold">x</span>
                  <input type="number" id="summ_m_B_${co.id}" value="${mB}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-2">
                  <span class="text-[10px] text-slate-500 font-bold w-16">PART C</span>
                  <input type="number" id="summ_q_C_${co.id}" value="${qC}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-xs font-bold">x</span>
                  <input type="number" id="summ_m_C_${co.id}" value="${mC}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1 text-[10px] text-slate-200 outline-none focus:border-blue-500">
                </div>
              </div>

              <div class="flex items-center justify-between mb-3 border-t border-slate-800/40 pt-2">
                <label class="flex items-center gap-1.5 cursor-pointer text-[9px] text-slate-400 hover:text-slate-200 transition-premium">
                  <input type="checkbox" id="sync_pattern_${co.id}" ${disabledAttr} onchange="if(this.checked) applySummativePatternToAll('${co.id}')" class="rounded border-slate-700 bg-slate-900 text-blue-500 focus:ring-blue-500/30">
                  <span>Apply pattern to all COs</span>
                </label>
                <div class="text-[10px] font-bold text-slate-300 bg-slate-800/50 px-2 py-0.5 rounded border border-slate-700/50">
                  Total Marks: <span id="summ_total_${co.id}" class="${testData ? 'text-emerald-400' : 'text-blue-400'}">${testData ? testData.total_marks : '0'}</span>
                </div>
              </div>
              
              ${genBtn}

              ${generatedContent}
              </div> <!-- close co_body -->
            </div>
          `;
        });
      }

      html += `</div>`;

      // Online MCQ Test Setup (Collapsible)
      let onlineTestHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('onlineTestWrapper').classList.toggle('hidden'); document.getElementById('onlineTestIcon').innerText = document.getElementById('onlineTestWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-[11px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-purple-400">devices</span> Online MCQ Tests Setup
              <span id="onlineTestIcon" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
            </div>
          </div>
          <div id="onlineTestWrapper" class="hidden p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Configuration Form -->
              <div class="col-span-2 bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-xs font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Publish New Online Test</h5>
                <div class="grid grid-cols-2 gap-3 mb-3">
                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Target COs (Multiple)</label>
                    <select id="online_test_cos" multiple class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500 h-[60px]">
                      ${cos ? cos.map(co => `<option value="${co.id}">${co.id}</option>`).join('') : ''}
                    </select>
                  </div>
                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Max Attempts</label>
                    <input type="number" id="online_test_attempts" value="1" min="1" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                    <label class="block text-[9px] text-slate-500 font-bold mt-2 mb-1 uppercase">Duration (Minutes)</label>
                    <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Number of Questions</label>
                  <input type="number" id="online_test_q_count" value="10" min="1" max="50" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Start Time</label>
                    <input type="text" id="online_test_start" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="e.g. Midterm Test 1">
                </div>
                <button onclick="publishOnlineTest('${currentSubjectId}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded-lg text-xs font-bold transition-premium flex items-center justify-center gap-2">
                  <span class="material-symbols-rounded text-sm">rocket_launch</span> Generate & Publish to Students
                </button>
              </div>
              
              <!-- Active Tests Dashboard -->
              <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-xs font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Active Online Tests</h5>
                <div id="activeOnlineTestsList" class="space-y-2 text-[10px] text-slate-400">
                   <div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      html += onlineTestHtml;

      html += `
        <div id="printableExamArea" class="hidden no-print"></div>
      `;

      document.getElementById('summativeAssessmentContent').innerHTML = html;

      // Initialize Flatpickr
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#online_test_start", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
        flatpickr("#online_test_end", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
      }
    }

    function syncSummativeInputs(sourceCoId) {
      calcSummativeTotal(sourceCoId);
      if(document.getElementById(`sync_pattern_${sourceCoId}`)?.checked) {
         applySummativePatternToAll(sourceCoId);
      }
    }

    function calcSummativeTotal(coId) {
      let total = 0;
      ['A', 'B', 'C'].forEach(p => {
        let q = parseInt(document.getElementById(`summ_q_${p}_${coId}`).value) || 0;
        let m = parseInt(document.getElementById(`summ_m_${p}_${coId}`).value) || 0;
        total += (q * m);
      });
      const tEl = document.getElementById(`summ_total_${coId}`);
      if (tEl) {
        tEl.innerText = total;
        tEl.classList.remove('text-emerald-400');
        tEl.classList.add('text-blue-400');
      }
    }

    function applySummativePatternToAll(sourceCoId) {
      const qA = document.getElementById(`summ_q_A_${sourceCoId}`).value;
      const mA = document.getElementById(`summ_m_A_${sourceCoId}`).value;
      const qB = document.getElementById(`summ_q_B_${sourceCoId}`).value;
      const mB = document.getElementById(`summ_m_B_${sourceCoId}`).value;
      const qC = document.getElementById(`summ_q_C_${sourceCoId}`).value;
      const mC = document.getElementById(`summ_m_C_${sourceCoId}`).value;

      document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => { if(el.id !== `summ_q_A_${sourceCoId}`) el.value = qA; });
      document.querySelectorAll('[id^="summ_m_A_"]').forEach(el => { if(el.id !== `summ_m_A_${sourceCoId}`) el.value = mA; });
      document.querySelectorAll('[id^="summ_q_B_"]').forEach(el => { if(el.id !== `summ_q_B_${sourceCoId}`) el.value = qB; });
      document.querySelectorAll('[id^="summ_m_B_"]').forEach(el => { if(el.id !== `summ_m_B_${sourceCoId}`) el.value = mB; });
      document.querySelectorAll('[id^="summ_q_C_"]').forEach(el => { if(el.id !== `summ_q_C_${sourceCoId}`) el.value = qC; });
      document.querySelectorAll('[id^="summ_m_C_"]').forEach(el => { if(el.id !== `summ_m_C_${sourceCoId}`) el.value = mC; });

      // Uncheck all other checkboxes to avoid conflict
      document.querySelectorAll('[id^="sync_pattern_"]').forEach(el => {
         if(el.id !== `sync_pattern_${sourceCoId}`) el.checked = false;
         
         // Trigger recalculation on all modified cards
         let c_id = el.id.replace('sync_pattern_', '');
         calcSummativeTotal(c_id);
      });
    }

    function saveSummativeConfig(subjectId, coTag) {
      let dateValue = document.getElementById(`summ_date_${coTag}`).value;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, date_of_exam: dateValue })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') console.log('Saved date');
      });
    }

    function lockSummativeTest(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} test? This cannot be easily undone.`)) return;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') loadCourseDetails(subjectId);
        else alert(data.message);
      });
    }

    let tempSummativePatterns = {};

    function saveSummativePatterns() {
       document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => {
          let coTag = el.id.replace('summ_q_A_', '');
          tempSummativePatterns[coTag] = {
             qA: document.getElementById(`summ_q_A_${coTag}`)?.value || '',
             mA: document.getElementById(`summ_m_A_${coTag}`)?.value || '',
             qB: document.getElementById(`summ_q_B_${coTag}`)?.value || '',
             mB: document.getElementById(`summ_m_B_${coTag}`)?.value || '',
             qC: document.getElementById(`summ_q_C_${coTag}`)?.value || '',
             mC: document.getElementById(`summ_m_C_${coTag}`)?.value || '',
          };
       });
    }

    function toggleSummativeMode(coId) {
       const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`).value === 'manual';
       const btn = document.getElementById(`gen_btn_${coId}`);
       if(btn) {
          if(isManual) {
             btn.innerText = 'Spawn Custom Question Fields';
             btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
             btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
             btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
             btn.classList.replace('text-blue-400', 'text-emerald-400');
          } else {
             btn.innerText = 'Generate AI Question Paper';
             btn.classList.replace('bg-emerald-600/20', 'bg-blue-600/20');
             btn.classList.replace('hover:bg-emerald-600', 'hover:bg-blue-600');
             btn.classList.replace('border-emerald-500/30', 'border-blue-500/30');
             btn.classList.replace('text-emerald-400', 'text-blue-400');
          }
       }
    }

    function spawnManualFields(coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;

      let html = `<div id="manual_form_${coTag}" class="mt-4 pt-4 border-t border-slate-800/60">`;
      html += `<div class="text-[10px] text-slate-300 bg-slate-950/50 p-3 rounded-lg border border-slate-800/40 space-y-4">`;
      
      const buildFields = (count, partName, prefix) => {
         let fHtml = '';
         if(count > 0) fHtml += `<div class="font-bold text-slate-400 border-b border-slate-800 pb-1">${partName}</div><div class="space-y-2 mt-2">`;
         for(let i=0; i<count; i++) {
            fHtml += `
              <div class="flex gap-2 items-start">
                 <span class="text-slate-500 mt-1 font-mono">${i+1}.</span>
                 <textarea id="man_q_${prefix}_${coTag}_${i}" class="w-full bg-slate-900 border border-slate-700 rounded p-2 text-slate-300 outline-none focus:border-emerald-500 text-[10px]" rows="2" placeholder="Enter question ${i+1}..."></textarea>
                 <select id="man_lvl_${prefix}_${coTag}_${i}" class="bg-slate-900 border border-slate-700 rounded p-1 text-slate-300 text-[10px] w-16 outline-none focus:border-emerald-500">
                    <option value="U">U</option>
                    <option value="R">R</option>
                    <option value="A">A</option>
                 </select>
              </div>
            `;
         }
         if(count > 0) fHtml += `</div>`;
         return fHtml;
      };

      html += buildFields(qA, 'PART A', 'A');
      html += buildFields(qB, 'PART B', 'B');
      html += buildFields(qC, 'PART C', 'C');
      html += `</div></div>`;

      let paperContainer = document.getElementById(`paper-${coTag}`);
      if(paperContainer) paperContainer.outerHTML = html;
      else {
         const card = document.getElementById(`summ_date_${coTag}`).closest('.bg-slate-900\\/50');
         card.insertAdjacentHTML('beforeend', html);
      }
      
      const btn = document.getElementById(`gen_btn_${coTag}`);
      btn.innerText = 'Save Custom Questions';
    }

    function saveManualSummativePaper(subjectId, coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let mA = parseInt(document.getElementById(`summ_m_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let mB = parseInt(document.getElementById(`summ_m_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;
      let mC = parseInt(document.getElementById(`summ_m_C_${coTag}`).value) || 0;

      let gather = (count, marks, prefix) => {
         let questions = [];
         for(let i=0; i<count; i++) {
            let elQ = document.getElementById(`man_q_${prefix}_${coTag}_${i}`);
            let elL = document.getElementById(`man_lvl_${prefix}_${coTag}_${i}`);
            if(elQ) questions.push({ q: elQ.value, level: elL.value, marks: marks });
         }
         return { q_count: count, marks_per_q: marks, total_marks: count * marks, questions: questions };
      };

      saveSummativePatterns();

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ 
           co_tag: coTag, 
           manual_mode: true,
           manual_part_a: gather(qA, mA, 'A'),
           manual_part_b: gather(qB, mB, 'B'),
           manual_part_c: gather(qC, mC, 'C')
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function generateSummativePaper(subjectId, coTag) {
      const isManual = document.querySelector(`input[name="summ_mode_${coTag}"]:checked`).value === 'manual';
      
      if(isManual) {
         if (document.getElementById(`manual_form_${coTag}`)) {
             saveManualSummativePaper(subjectId, coTag);
         } else {
             spawnManualFields(coTag);
         }
         return;
      }

      saveSummativePatterns();

      let partA = { q_count: document.getElementById(`summ_q_A_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_A_${coTag}`).value };
      let partB = { q_count: document.getElementById(`summ_q_B_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_B_${coTag}`).value };
      let partC = { q_count: document.getElementById(`summ_q_C_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_C_${coTag}`).value };

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, part_a: partA, part_b: partB, part_c: partC })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          // Soft re-render the whole summative tab using existing data
          // We don't have cos/students cached globally in a variable cleanly, so let's reload.
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function loadActiveOnlineTests(subjectId) {
      fetch(`/api/classroom/${subjectId}/active-online-tests`)
        .then(res => res.json())
        .then(data => {
          let listDiv = document.getElementById('activeOnlineTestsList');
          if (!listDiv) return;
          if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              html += `
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800/80 mb-2">
                  <div class="flex justify-between items-start mb-1">
                    <h6 class="font-bold text-purple-400 text-[11px]">${t.test_name}</h6>
                    <span class="bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded text-[9px] font-bold">${t.duration} Mins</span>
                  </div>
                  <div class="text-[9px] text-slate-500 mb-2">
                    Start: ${t.start_time ? new Date(t.start_time).toLocaleString() : 'Now'}<br>
                    Live Students: <span class="text-emerald-400 font-bold">${t.student_count || 0}</span> | Completed: <span class="text-blue-400 font-bold">${t.completed_count || 0}</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[11px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[11px]">print</span> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('${t.test_id}', '${subjectId}')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                      </button>
                    </div>
                </div>
              `;
            });
            listDiv.innerHTML = html;
          } else {
            listDiv.innerHTML = `<div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>`;
          }
        });
    }

    function publishOnlineTest(subjectId) {
      const selectElement = document.getElementById('online_test_cos');
      const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
      const attempts = document.getElementById('online_test_attempts').value;
      const duration = document.getElementById('online_test_duration').value;
      const start = document.getElementById('online_test_start').value;
      const end = document.getElementById('online_test_end').value;

      if (selectedCos.length === 0) {
        alert("Please select at least one CO.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/publish-online-test`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Online Test successfully published!");
          loadActiveOnlineTests(subjectId);
          
          // Clear inputs
          selectElement.selectedIndex = -1;
          if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
          if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
        } else {
          alert(data.message || "Failed to publish test.");
        }
      });
    }

    function generateOnlineTestReport(testId) {
      fetch(`/api/test-engine/report/${testId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const test = data.test_info;
            const attempts = data.report;
            
            let tableRows = '';
            if(attempts && attempts.length > 0) {
              attempts.forEach(a => {
                 let start = new Date(a.start_time);
                 let end = new Date(a.end_time);
                 let timeTakenStr = '-';
                 if(a.start_time && a.end_time) {
                    let diffMs = end - start;
                    let diffMins = Math.floor(diffMs / 60000);
                    let diffSecs = Math.floor((diffMs % 60000) / 1000);
                    timeTakenStr = `${diffMins}m ${diffSecs}s`;
                 }
                 
                 tableRows += `
                   <tr>
                     <td style="padding: 8px; border: 1px solid #ddd; font-family: monospace;">${a.reg_no}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">${a.name}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${a.attempt_number}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${timeTakenStr}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; font-size: 14px;">${a.total_score}</td>
                   </tr>
                 `;
              });
            } else {
              tableRows = `<tr><td colspan="5" style="padding: 16px; text-align: center; border: 1px solid #ddd;">No completed attempts yet.</td></tr>`;
            }

            const html = `<!DOCTYPE html>
            <html>
            <head>
              <title>${test.test_name} - Report</title>
              <style>
                body { font-family: system-ui, -apple-system, sans-serif; padding: 40px; color: #111; }
                h2 { text-align: center; margin-bottom: 5px; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; display: inline-block; }
                .meta { text-align: center; font-size: 14px; color: #555; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
                th { background: #f0f0f0; padding: 10px 8px; border: 1px solid #ddd; text-align: left; }
                .center { text-align: center; }
              </style>
            </head>
            <body>
              <div style="text-align: center;">
                <h2>Online Test Evaluation Report</h2>
                <div class="meta">
                  <strong>Test Name:</strong> ${test.test_name} <br>
                  <strong>Subject Code:</strong> ${test.subject_code} <br>
                  <strong>Total MCQs:</strong> ${test.mcq_count} | <strong>Duration:</strong> ${test.duration} Mins<br>
                  <strong>Generated On:</strong> ${new Date().toLocaleString()}
                </div>
              </div>
              
              <table>
                <thead>
                  <tr>
                    <th>Reg No</th>
                    <th>Student Name</th>
                    <th class="center">Attempts Used</th>
                    <th class="center">Time Taken</th>
                    <th class="center">Marks Obtained</th>
                  </tr>
                </thead>
                <tbody>
                  ${tableRows}
                </tbody>
              </table>
              <script>
                window.onload = () => { window.print(); window.close(); }
              <\/script>
            </body>
            </html>`;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
          } else {
            alert(data.message || "Failed to generate report.");
          }
        });
    }

    function saveSummativeMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#summativeMarkEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.summ-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-written-test-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Written Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function printSummativePaper(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) =>
          `<tr>
            <td style="width:28px;vertical-align:top;padding:4px 2px;">${i+1}.</td>
            <td style="vertical-align:top;padding:4px 6px;">${q.q}</td>
            <td style="width:95px;text-align:right;vertical-align:top;padding:4px 2px;white-space:nowrap;">${q.marks} Marks &nbsp;<strong>[${q.level}]</strong></td>
          </tr>`
        ).join('');
      };

      let bodyHtml = '';

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:18px 0 6px;">PART A &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_a.q_count} × ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_a)}</table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:20px 0 6px;">PART B &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_b.q_count} × ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_b)}</table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="text-align:center;font-weight:bold;margin:20px 0 6px;">PART C &nbsp;<small style="font-weight:normal;font-size:12px;">(${data.part_c.q_count} × ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          <p style="text-align:center;font-style:italic;font-size:12px;margin:0 0 10px;">Answer all questions.</p>
          <table style="width:100%;border-collapse:collapse;font-size:13px;">${buildRows(data.part_c)}</table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Question Paper - ${coTag}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 3px; vertical-align: top; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; Written Test&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function printAnswerKey(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRubricHtml = (rubric, marks) => {
        // Fallback for older generated papers that don't have a rubric saved
        if (!rubric || rubric.length === 0) {
            if (marks <= 2) rubric = [{desc: 'Correct definition / answer', mark: marks}];
            else if (marks <= 4) rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Explanation / relevant points', mark: marks - 1}];
            else rubric = [{desc: 'Definition / Concept statement', mark: 1}, {desc: 'Explanation with supporting points', mark: Math.floor(marks/2)}, {desc: 'Diagram / Application', mark: marks - Math.floor(marks/2) - 1}];
        }

        return `<table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 4px; background: #fafafa;">
          ${rubric.map(r => `<tr>
            <td style="padding: 3px 6px; border: 1px solid #ddd;">${r.desc}</td>
            <td style="padding: 3px 6px; text-align: center; width: 50px; border: 1px solid #ddd; font-weight: bold; color: #444;">${r.mark}</td>
          </tr>`).join('')}
        </table>`;
      };

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let ansHtml = '';
          if (q.ans && q.ans.length > 0) {
            ansHtml = `<div style="margin-bottom: 8px; font-size: 12px; color: #333;">
              <ul style="margin: 0; padding-left: 16px;">
                ${q.ans.map(a => `<li style="margin-bottom: 3px;">${a}</li>`).join('')}
              </ul>
            </div>`;
          }
          
          return `<tr>
            <td style="width: 40px; text-align: center; vertical-align: top; padding: 10px 5px; border: 1px solid #000; font-weight: bold;">${i+1}</td>
            <td style="vertical-align: top; padding: 10px; border: 1px solid #000;">
              <div style="font-weight: 500; margin-bottom: 6px; font-size: 13px;">${q.q}</div>
              ${ansHtml}
              <div style="font-size: 11px; font-weight: bold; color: #555; margin-bottom: 2px; margin-top: 6px;">Marking Scheme / Answer Pointers:</div>
              ${buildRubricHtml(q.rubric, q.marks)}
            </td>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 14px; font-weight: bold;">${q.marks}</td>
            <td style="width: 60px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 11px;">[${q.level}]</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      const tableHeader = `
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 40px;">Q.No</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee;">Question & Expected Answer Key</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 80px;">Marks</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 60px;">Level</th>
            </tr>
          </thead>
          <tbody>
      `;

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART A <small style="font-weight:normal; font-size:12px;">(${data.part_a.q_count} × ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_a)}</tbody></table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART B <small style="font-weight:normal; font-size:12px;">(${data.part_b.q_count} × ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_b)}</tbody></table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART C <small style="font-weight:normal; font-size:12px;">(${data.part_c.q_count} × ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_c)}</tbody></table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Answer Key - ${coTag}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 3px; vertical-align: top; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; ANSWER KEY & RUBRIC&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }
  
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(`/api/classroom/online-tests/${testId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(`/api/classroom/online-tests/${testId}/key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const deptMap = {
              'EL': 'ELECTRONICS ENGINEERING',
              'CS': 'COMPUTER SCIENCE AND ENGINEERING',
              'CE': 'CIVIL ENGINEERING',
              'ME': 'MECHANICAL ENGINEERING',
              'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
              'IT': 'INFORMATION TECHNOLOGY',
              'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
            };
            const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
            const subjectName = currentSubjectName;
            const subjectCode = currentSubjectCode;
            const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
            const testName = data.test_name || 'Online Test';
            const totalQ = data.total || 0;

            let html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Online MCQ Test - ${testName}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    .q-block { margin-bottom: 15px; page-break-inside: avoid; }
    .q-text { font-weight: bold; margin-bottom: 5px; }
    .options { list-style-type: lower-alpha; margin: 0; padding-left: 20px; }
    .options li { margin-bottom: 3px; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${testName} &ndash; Answer Key&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Total Questions:</strong> ${totalQ}</span>
    </div>
  </div>`;

            data.details.forEach((q, i) => {
              html += `<div class="q-block">
                <div class="q-text">${i+1}. ${q.q} &nbsp; <em>[${q.co}]</em></div>
                <ul class="options">`;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                if (isCorrect) {
                  html += `<li><strong>${opt} &nbsp; &#10004;</strong></li>`;
                } else {
                  html += `<li>${opt}</li>`;
                }
              });
              html += `</ul></div>`;
            });

            html += `</body></html>`;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }
</script>
</body>
</html>
