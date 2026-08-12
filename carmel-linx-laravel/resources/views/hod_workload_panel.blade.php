<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Workload & Timetable Control Panel - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .transition-premium {
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-gradient {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-amber-500/30 text-xs">

  <!-- Header -->
  <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-xl">
    <div class="px-4 lg:px-6 h-11 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <a href="/hod/report-centre" class="flex items-center gap-1 px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700/80 rounded-md font-semibold transition-premium no-underline text-xs">
          <span class="material-symbols-rounded text-sm">arrow_back</span>
          <span>Back</span>
        </a>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white font-black rounded w-6 h-6 flex items-center justify-center text-[10px] shadow-sm">WP</div>
        <div>
          <h1 class="font-extrabold text-slate-100 text-xs tracking-wide flex items-center gap-2 m-0">
            Workload & Timetable Control Panel
            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/20">{{ $department }} Dept</span>
          </h1>
        </div>
      </div>
      <div class="flex items-center gap-2">
        @include('partials.fullscreen_btn')
      </div>
    </div>
  </header>

  <!-- Main Content Container (Compact & Dense Layout) -->
  <main class="flex-grow p-3 lg:p-5 max-w-5xl mx-auto w-full space-y-3.5">
    
    <!-- Executive Quick Stats Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
      <div class="bg-slate-900/60 border border-slate-800/80 rounded-lg p-2 flex items-center gap-2">
        <span class="material-symbols-rounded text-amber-400 text-base">groups</span>
        <div>
          <div class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">Active Batches</div>
          <div class="font-extrabold text-slate-200 text-xs">{{ count($batches) }} Batches</div>
        </div>
      </div>

      <div class="bg-slate-900/60 border border-slate-800/80 rounded-lg p-2 flex items-center gap-2">
        <span class="material-symbols-rounded text-violet-400 text-base">calendar_view_week</span>
        <div>
          <div class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">Semesters Covered</div>
          <div class="font-extrabold text-slate-200 text-xs">Semesters 1 - 6</div>
        </div>
      </div>

      <div class="bg-slate-900/60 border border-slate-800/80 rounded-lg p-2 flex items-center gap-2">
        <span class="material-symbols-rounded text-emerald-400 text-base">print</span>
        <div>
          <div class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">Print Format</div>
          <div class="font-extrabold text-slate-200 text-xs">A4 Landscape Grid</div>
        </div>
      </div>

      <div class="bg-slate-900/60 border border-slate-800/80 rounded-lg p-2 flex items-center gap-2">
        <span class="material-symbols-rounded text-sky-400 text-base">verified</span>
        <div>
          <div class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider">Laser Contrast</div>
          <div class="font-extrabold text-slate-200 text-xs">Monochrome Ready</div>
        </div>
      </div>
    </div>

    <!-- 2-Column Compact Control Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
      
      <!-- Card 1: Department Faculty Workload -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3.5 space-y-2.5 hover:border-amber-500/30 transition-premium flex flex-col justify-between shadow-lg">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-md flex items-center justify-center">
                <span class="material-symbols-rounded text-base">pending_actions</span>
              </div>
              <h2 class="text-slate-100 text-xs font-black m-0">1. Department Faculty Workload</h2>
            </div>
            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Official Report</span>
          </div>
          
          <p class="text-slate-400 text-[11px] leading-relaxed m-0">
            Generate the official weekly engaged hours report for all lecturers and demonstrators in the department, calculated dynamically from active timetables.
          </p>

          <!-- Specifications Tags -->
          <div class="flex flex-wrap gap-1 pt-1">
            <span class="text-[9.5px] font-semibold bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-800">Dynamic Hours</span>
            <span class="text-[9.5px] font-semibold bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-800">Theory & Lab Breakdown</span>
            <span class="text-[9.5px] font-semibold bg-slate-900 text-slate-400 px-1.5 py-0.5 rounded border border-slate-800">HOD Sign Block</span>
          </div>
        </div>

        <div class="pt-2.5 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-[10px] font-medium text-slate-500">Commencement Format</span>
          <a href="/hod/workload-report/print" target="_blank" class="px-3 py-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-lg transition-premium text-xs shadow-md no-underline flex items-center gap-1">
            <span class="material-symbols-rounded text-xs">print</span>
            <span>Print Workload</span>
          </a>
        </div>
      </div>

      <!-- Card 2: Individual Batch Timetable Printer -->
      <div class="card-gradient border border-slate-800/80 rounded-xl p-3.5 space-y-2.5 hover:border-violet-500/30 transition-premium flex flex-col justify-between shadow-lg">
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-md flex items-center justify-center">
                <span class="material-symbols-rounded text-base">calendar_today</span>
              </div>
              <h2 class="text-slate-100 text-xs font-black m-0">2. Individual Batch Timetable</h2>
            </div>
            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-violet-500/10 text-violet-400 border border-violet-500/20">Single A4 Sheet</span>
          </div>
          
          <p class="text-slate-400 text-[11px] leading-relaxed m-0">
            Select any department batch and semester to preview and print its finalized A4 landscape weekly timetable sheet.
          </p>
          
          <!-- Compact Controls Row -->
          <div class="grid grid-cols-2 gap-2 pt-0.5">
            <div class="space-y-0.5">
              <label class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider block">Classroom</label>
              <select id="singleBatchSelect" onchange="updateSemesterOptions()" class="w-full bg-slate-950 border border-slate-800 rounded-md h-7 px-2 text-xs text-slate-200 focus:border-violet-500 outline-none">
                @foreach ($batches as $b)
                  <option value="{{ $b->classroom_id }}" data-semester="{{ $b->current_semester ?? 1 }}">{{ $b->classroom_id }} (Sem {{ $b->current_semester ?? 1 }})</option>
                @endforeach
              </select>
            </div>
            <div class="space-y-0.5">
              <label class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider block">Semester</label>
              <select id="singleSemSelect" class="w-full bg-slate-950 border border-slate-800 rounded-md h-7 px-2 text-xs text-slate-200 focus:border-violet-500 outline-none">
              </select>
            </div>
          </div>
        </div>

        <div class="pt-2.5 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-[10px] font-medium text-slate-500">Laser-Black Preview Sheet</span>
          <button onclick="printSingleTimetable()" class="px-3 py-1 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-lg transition-premium text-xs cursor-pointer shadow-md flex items-center gap-1 border border-violet-500/30">
            <span class="material-symbols-rounded text-xs">print</span>
            <span>Print Timetable</span>
          </button>
        </div>
      </div>

    </div>

    <!-- Card 3: Multi-Batch Consolidated Timetable Sheet (Full Width Compact) -->
    <div class="card-gradient border border-slate-800/80 rounded-xl p-3.5 space-y-3 hover:border-emerald-500/30 transition-premium shadow-xl">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-md flex items-center justify-center">
            <span class="material-symbols-rounded text-base">dashboard_customize</span>
          </div>
          <div>
            <h2 class="text-slate-100 text-xs font-black m-0">3. Semester Consolidated Timetable (Clash Audit Sheet)</h2>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider m-0">Select 2 or 3 active classes</p>
          </div>
        </div>
        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Clash Audit Engine</span>
      </div>

      <p class="text-slate-400 text-[11px] leading-relaxed m-0">
        Select 2 or 3 active department classes to compile a consolidated side-by-side timetable sheet per period. Ideal for monitoring clash reviews across shared faculty or labs.
      </p>

      <form id="consolidatedForm" action="/hod/consolidated-timetable/print" method="GET" target="_blank" onsubmit="return validateConsolidatedForm(event)" class="space-y-2.5 pt-0.5">
        
        <!-- Compact Batch Checkboxes Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
          @forelse ($batches as $b)
            <label class="flex items-center gap-2 p-2 bg-slate-950/80 border border-slate-800 hover:border-emerald-500/40 rounded-lg transition-premium cursor-pointer select-none">
              <input type="checkbox" name="batches[]" value="{{ $b->classroom_id }}" class="w-3.5 h-3.5 rounded border-slate-800 text-emerald-600 focus:ring-emerald-500 bg-slate-900 accent-emerald-500 batch-checkbox" />
              <div class="min-w-0">
                <span class="text-[11px] font-bold text-slate-200 block truncate">{{ $b->classroom_id }}</span>
                <span class="text-[9.5px] text-slate-500 block truncate">Adm: {{ $b->batch_year }} • Sem {{ $b->current_semester ?? 1 }}</span>
              </div>
            </label>
          @empty
            <div class="col-span-full p-4 text-center text-slate-500 italic text-xs">No batches created for this department.</div>
          @endforelse
        </div>

        <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-[10.5px] font-medium text-slate-400" id="selectionStatus">0 of 3 batches selected</span>
          <button type="submit" class="px-3.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition-premium text-xs cursor-pointer shadow-md flex items-center gap-1 border border-emerald-500/30">
            <span class="material-symbols-rounded text-xs">grid_view</span>
            <span>Generate Consolidated Sheet</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Institutional Compliance Footer Note -->
    <div class="bg-slate-900/40 border border-slate-800/60 rounded-lg p-2.5 flex items-center justify-between text-[10.5px] text-slate-400">
      <div class="flex items-center gap-2">
        <span class="material-symbols-rounded text-amber-400 text-sm">info</span>
        <span>Institutional Standard: All printed reports format to single-page A4 landscape with high-contrast laser monochrome styling (`#000000` text).</span>
      </div>
      <span class="font-bold text-slate-500 text-[9.5px]">CARMEL LINX R26</span>
    </div>

  </main>

  <script>
    function updateSemesterOptions() {
      const batchSelect = document.getElementById('singleBatchSelect');
      const semSelect = document.getElementById('singleSemSelect');
      if (!batchSelect || !semSelect) return;

      const selectedOpt = batchSelect.options[batchSelect.selectedIndex];
      if (!selectedOpt) return;

      const rawSem = parseInt(selectedOpt.getAttribute('data-semester') || '1', 10);
      const maxSem = Math.max(1, Math.min(isNaN(rawSem) ? 1 : rawSem, 6));

      semSelect.innerHTML = '';
      for (let s = 1; s <= maxSem; s++) {
        const opt = document.createElement('option');
        opt.value = s;
        opt.textContent = `Semester ${s}`;
        if (s === maxSem) {
          opt.selected = true;
        }
        semSelect.appendChild(opt);
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      updateSemesterOptions();
    });
    // Max 3 validation for consolidated checkboxes
    const checkboxes = document.querySelectorAll('.batch-checkbox');
    const selectionStatus = document.getElementById('selectionStatus');

    checkboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
        if (checkedCount > 3) {
          cb.checked = false;
          alert('You can select a maximum of 3 batches for consolidated view.');
          return;
        }
        updateSelectionStatus();
      });
    });

    function updateSelectionStatus() {
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      selectionStatus.innerText = `${checkedCount} of 3 batches selected`;
    }

    function validateConsolidatedForm(e) {
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      if (checkedCount < 2) {
        alert('Please select at least 2 batches to generate a consolidated timetable.');
        e.preventDefault();
        return false;
      }
      return true;
    }

    // Individual Timetable printing logic
    function printSingleTimetable() {
      const classroomId = document.getElementById('singleBatchSelect').value;
      const sem = document.getElementById('singleSemSelect').value;
      if (!classroomId) {
        alert('No batch selected.');
        return;
      }

      // Fetch subjects and timetable then trigger printing window
      Promise.all([
        fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${sem}`).then(r => r.json()),
        fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/timetable`).then(r => r.json())
      ])
      .then(([subData, ttData]) => {
        if (subData.status !== 'SUCCESS' || ttData.status !== 'SUCCESS') {
          throw new Error('Failed to load batch specifications.');
        }

        const allocatedSubjects = subData.subjects || [];
        const timetableData = ttData.timetable || {};
        
        triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData);
      })
      .catch(err => {
        alert('Error preparing printout: ' + err.message);
      });
    }

    function triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData) {
      const printWindow = window.open('', '_blank');
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      let rowsHtml = '';
      const scheduledSubjects = new Set();

      function slotsEqual(slotA, slotB) {
        if (!slotA || !slotB) return false;
        return slotA.subject === slotB.subject;
      }

      days.forEach((day, index) => {
        const dayData = timetableData[day] || {};
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Collect scheduled subject codes
        [s1, s2, s3, s4, s5, s6].forEach(s => {
          if (s.subject) scheduledSubjects.add(s.subject);
        });

        let cellsHtml = `<td class="p-4 text-center font-bold bg-gray-100 day-cell">${day}</td>`;

        // Forenoon
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          cellsHtml += renderPrintCell(s1, 2);
          cellsHtml += renderPrintCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 2);
        } else {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 1);
          cellsHtml += renderPrintCell(s3, 1);
        }

        // Lunch Break (merged vertically)
        if (index === 0) {
          cellsHtml += `<td rowspan="5" class="p-4 text-center font-black lunch-cell text-base" style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); letter-spacing: 5px; vertical-align: middle; min-width: 50px;">LUNCH BREAK</td>`;
        }

        // Afternoon
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          cellsHtml += renderPrintCell(s4, 2);
          cellsHtml += renderPrintCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 2);
        } else {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 1);
          cellsHtml += renderPrintCell(s6, 1);
        }

        rowsHtml += `<tr class="border-b border-slate-800/40 print-row">${cellsHtml}</tr>`;
      });

      function renderPrintCell(slot, colspan = 1) {
        const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
        if (!slot.subject) {
          return `<td ${colspanAttr} class="p-1.5 text-center free-period">-- Free --</td>`;
        }
        
        const matchedSub = allocatedSubjects.find(s => s.subject_code === slot.subject);
        let subjectName = matchedSub ? matchedSub.subject_name : '';
        let staffDisplay = '';
        if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
          staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
        } else {
          staffDisplay = slot.staff || 'N/A';
        }

        return `
          <td ${colspanAttr} class="p-1 text-center">
            <div style="font-weight: 900; font-size: 10.5px; line-height: 1.1; color: #000000;">${slot.subject}</div>
            <div style="font-weight: 700; font-size: 9.5px; margin-top: 1px; line-height: 1.1; color: #000000;">${subjectName}</div>
            <div style="font-size: 8.5px; font-weight: 700; margin-top: 1px; line-height: 1.1; color: #000000;">${staffDisplay}</div>
          </td>
        `;
      }

      // Build Legend/Abbreviations List
      let legendHtml = '';
      scheduledSubjects.forEach(code => {
        const sub = allocatedSubjects.find(s => s.subject_code === code);
        const name = sub ? sub.subject_name : 'Unknown Subject';
        let staffDisplay = '';
        if (sub && sub.staff && sub.staff.length > 0) {
          staffDisplay = sub.staff.map(s => s.name).join(', ');
        }
        legendHtml += `
          <div class="flex items-center gap-1.5 text-xs p-1 border rounded legend-item bg-slate-900/40">
            <span class="font-mono font-black w-16 shrink-0 legend-code text-[9.5px]">${code}</span>
            <div class="flex-grow min-w-0">
              <span class="font-bold block truncate text-[10px]">${name}</span>
              <span class="legend-staff text-[9px] font-bold block truncate">Faculty: ${staffDisplay || 'Unassigned'}</span>
            </div>
          </div>
        `;
      });

      if (!legendHtml) {
        legendHtml = '<p class="text-xs text-gray-500 italic col-span-2 text-center">No subjects scheduled.</p>';
      }

      // Department Full Name Mapping
      const deptNames = {
        "EL": "Electronics Engineering",
        "CS": "Computer Engineering",
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
        "CH": "Chemical Engineering"
      };
      const deptShort = classroomId.split('_')[0];
      const fullDept = deptNames[deptShort.toUpperCase()] || deptShort;
      const currentYear = new Date().getFullYear();

      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Timetable - ${classroomId}</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <style>
            /* Screen Preview (White Page) Styles */
            body {
              font-family: Arial, sans-serif;
              padding: 20px;
              background-color: #cbd5e1;
              color: #000000;
            }
            .page-container {
              background-color: #ffffff;
              color: #000000;
              padding: 20px;
              border-radius: 12px;
              box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05);
              border: 1px solid #cbd5e1;
            }
            .header-border {
              border-color: #000000;
            }
            table {
              border-collapse: collapse;
              width: 100%;
              border: 2px solid #000000;
              background-color: #ffffff;
            }
            th {
              background-color: #f1f5f9;
              color: #000000;
              border: 1.5px solid #000000;
              padding: 6px;
              text-align: center;
              font-weight: 800;
            }
            td {
              border: 1.5px solid #000000;
              padding: 6px;
              text-align: center;
              vertical-align: middle;
              color: #000000;
              font-weight: 700;
            }
            .day-cell {
              background-color: #f1f5f9;
              font-weight: 800;
              color: #000000;
            }
            .lunch-cell {
              background-color: #e5e7eb;
              color: #000000;
              font-weight: 800;
            }
            .legend-box {
              background-color: #ffffff;
              border: 1.5px solid #000000;
            }
            .legend-grid {
              display: grid;
              grid-template-columns: repeat(2, minmax(0, 1fr));
              gap: 4px;
            }
            .legend-title {
              color: #000000;
              font-weight: 800;
            }
            .legend-item {
              border: 1.5px solid #000000;
              background-color: #f8fafc;
              color: #000000;
            }
            .legend-code {
              color: #000000;
              font-weight: 900;
            }
            .legend-staff {
              color: #000000;
              font-weight: 700;
            }
            .free-period {
              color: #000000;
              font-style: italic;
            }

            /* Print (Light Mode) Styles - LASER PRINTER HIGH CONTRAST PURE BLACK */
            @media print {
              * {
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
              }
              .no-print {
                display: none !important;
              }
              @page {
                size: A4 landscape;
                margin: 6mm 8mm;
              }
              html, body {
                background-color: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
              }
              .page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background-color: #ffffff !important;
                box-shadow: none !important;
                border: none !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
              }
              table {
                background-color: #ffffff !important;
                border: 2px solid #000000 !important;
                margin-top: 2px !important;
                margin-bottom: 2px !important;
                page-break-inside: avoid !important;
              }
              th {
                border: 1.5px solid #000000 !important;
                color: #000000 !important;
                background-color: #f1f5f9 !important;
                padding: 2.5px 3px !important;
                font-size: 9.5px !important;
                font-weight: 800 !important;
              }
              td {
                border: 1.5px solid #000000 !important;
                color: #000000 !important;
                background-color: #ffffff !important;
                padding: 1.5px 3px !important;
                font-size: 9.5px !important;
                font-weight: 700 !important;
              }
              .day-cell {
                background-color: #f1f5f9 !important;
                color: #000000 !important;
                font-weight: 800 !important;
              }
              .lunch-cell {
                background-color: #e5e7eb !important;
                color: #000000 !important;
                font-weight: 800 !important;
              }
              .legend-box {
                background-color: #ffffff !important;
                border: 1.5px solid #000000 !important;
                margin-top: 2px !important;
                padding: 3px 5px !important;
                page-break-inside: avoid !important;
              }
              .legend-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 2px 6px !important;
              }
              .legend-item {
                border: 1.5px solid #000000 !important;
                background-color: #ffffff !important;
                padding: 1.5px 4px !important;
                border-radius: 3px !important;
              }
              .print-header, .print-header h1, .print-header h2, .print-header div, .print-header strong, .print-header span, .meta-lbl, .meta-val, .legend-title, .legend-item, .legend-code, .legend-staff {
                color: #000000 !important;
                font-weight: 700 !important;
              }
              .print-header {
                border-bottom: 2px solid #000000 !important;
              }
              .free-period {
                color: #000000 !important;
              }
              .signature-footer {
                padding-top: 4px !important;
                margin-top: 2px !important;
                page-break-inside: avoid !important;
              }
              .signature-footer p {
                border-top: 1.5px solid #000000 !important;
                color: #000000 !important;
                font-weight: 800 !important;
              }
            }
          </style>
        </head>
        <body>
          <div class="max-w-6xl mx-auto space-y-2 page-container">
            
            <!-- Centered Header Section (BLACK TEXT IN PRINT) -->
            <div class="border-b-2 border-black pb-1.5 text-center relative header-border print-header space-y-0.5">
              <h1 class="text-xs font-black uppercase tracking-widest text-black">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
              <h2 class="text-base font-black text-black uppercase">WEEKLY CLASS TIMETABLE</h2>
              
              <div class="flex justify-center flex-wrap gap-x-6 gap-y-0.5 mt-1 text-[11px] font-black text-black">
                <div>Branch: <strong class="text-black font-black">${fullDept}</strong></div>
                <div>Sem: <strong class="text-black font-black">Semester ${sem}</strong></div>
                <div>Year: <strong class="text-black font-black">${currentYear} - ${currentYear + 1}</strong></div>
                <div>Batch: <strong class="text-black font-black">${classroomId}</strong></div>
              </div>

              <div class="no-print absolute top-0 right-0 flex gap-2">
                <button onclick="window.print()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs shadow transition duration-200">
                  Print Timetable
                </button>
                <button onclick="window.close()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs shadow transition duration-200">
                  Close Preview
                </button>
              </div>
            </div>
            
            <!-- Timetable Grid -->
            <table class="w-full text-left border">
              <thead>
                <tr class="text-slate-400 font-bold border-b header-border text-xs">
                  <th class="p-1.5 text-center w-16">Day</th>
                  <th class="p-1.5 text-center">Period 1<br><span class="text-[8.5px] font-normal meta-lbl">09:00 - 10:00</span></th>
                  <th class="p-1.5 text-center">Period 2<br><span class="text-[8.5px] font-normal meta-lbl">10:00 - 11:00</span></th>
                  <th class="p-1.5 text-center">Period 3<br><span class="text-[8.5px] font-normal meta-lbl">11:10 - 12:10</span></th>
                  <th class="p-1.5 text-center w-8">Lunch</th>
                  <th class="p-1.5 text-center">Period 4<br><span class="text-[8.5px] font-normal meta-lbl">01:00 - 02:00</span></th>
                  <th class="p-1.5 text-center">Period 5<br><span class="text-[8.5px] font-normal meta-lbl">02:00 - 03:00</span></th>
                  <th class="p-1.5 text-center">Period 6<br><span class="text-[8.5px] font-normal meta-lbl">03:00 - 04:00</span></th>
                </tr>
              </thead>
              <tbody>
                ${rowsHtml}
              </tbody>
            </table>
            
            <!-- Subject Legend / Abbreviations (STRICT 2 COLUMNS) -->
            <div class="mt-2 p-2 rounded-xl border legend-box">
              <h3 class="text-[10px] font-bold legend-title mb-1 uppercase tracking-wider text-center border-b pb-0.5">Course Legend & Assigned Faculty List</h3>
              <div class="grid grid-cols-2 gap-1 legend-grid">
                ${legendHtml}
              </div>
            </div>
            
            <!-- Signature Footer -->
            <div class="pt-2 grid grid-cols-3 text-center text-[9.5px] font-bold signature-footer text-slate-400">
              <div>
                <div class="h-4"></div>
                <p class="border-t border-slate-700 pt-0.5 mx-6">Staff Advisor</p>
              </div>
              <div>
                <div class="h-4"></div>
                <p class="border-t border-slate-700 pt-0.5 mx-6">Head of Department</p>
              </div>
              <div>
                <div class="h-4"></div>
                <p class="border-t border-slate-700 pt-0.5 mx-6">Principal / Academic Coordinator</p>
              </div>
            </div>
            
          </div>
        </body>
        </html>
      `);
      printWindow.document.close();
    }
  </script>
</body>
</html>
