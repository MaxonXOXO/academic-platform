<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NBA Audit Console - {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #090d16;
      background-image: 
        radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
        radial-gradient(at 50% 100%, rgba(15, 23, 42, 0.6) 0px, transparent 50%);
      background-attachment: fixed;
      color: #f1f5f9;
      font-size: 0.8125rem; /* 13px baseline executive font size */
    }
    
    .glass-card {
      background: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    .glass-card-hover {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card-hover:hover {
      border-color: rgba(99, 102, 241, 0.35);
      box-shadow: 0 10px 25px -10px rgba(99, 102, 241, 0.25);
    }

    .nba-table td, .nba-table th {
      padding: 0.55rem 0.85rem !important;
      font-size: 0.8125rem !important;
    }

    .nba-table th {
      font-size: 0.75rem !important;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.6);
    }
    ::-webkit-scrollbar-thumb {
      background: rgba(99, 102, 241, 0.35);
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: rgba(99, 102, 241, 0.6);
    }
  </style>
</head>
<body class="min-h-screen p-4 md:p-6 text-slate-200">

  <div class="w-full max-w-7xl mx-auto space-y-4">
    
    <!-- TOP NAVIGATION & HEADER -->
    <div class="glass-card rounded-2xl p-4 md:p-5 flex flex-wrap justify-between items-center gap-4 shadow-xl border border-slate-800/80">
      <div class="flex items-center space-x-3.5">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500/20 to-cyan-500/20 border border-sky-500/30 flex items-center justify-center text-sky-400 font-bold shadow-inner">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="flex items-center space-x-2">
            <span class="font-bold text-white text-base">Carmel Linx</span>
            <span class="px-2 py-0.5 rounded-full bg-sky-500/15 text-sky-300 border border-sky-500/30 text-[11px] font-bold tracking-wide">R2026 PRACTICUM</span>
          </div>
          <p class="text-xs text-slate-400 font-medium tracking-wide">NBA Audit Preparation & Course File Catalog Console</p>
        </div>
      </div>

      <div class="flex items-center space-x-2.5 flex-wrap gap-y-2">
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}" class="px-3.5 py-2 bg-sky-500/15 hover:bg-sky-500 text-sky-300 hover:text-white border border-sky-500/30 hover:border-sky-400 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 shadow-sm">
          <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          <span>Back to Virtual Classroom</span>
        </a>
        <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-course-file" target="_blank" class="px-4 py-2 bg-gradient-to-r from-sky-500 via-cyan-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white rounded-xl text-xs font-extrabold transition-all shadow-lg shadow-sky-500/25 flex items-center space-x-1.5 border border-sky-400/30">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Download Course File PDF</span>
        </a>
      </div>
    </div>

    <!-- COURSE CONSOLE SUMMARY HEADER & STAT WIDGETS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      
      <!-- Course Info Card -->
      <div class="lg:col-span-2 glass-card rounded-2xl p-5 border border-slate-800/80 flex flex-col justify-between space-y-3">
        <div>
          <div class="flex items-center space-x-2 mb-1">
            <span class="px-2.5 py-0.5 rounded bg-sky-500/10 text-sky-400 font-mono font-bold text-xs border border-sky-500/20">{{ $batchSubject->subject_code }}</span>
            <span class="text-slate-500 font-bold">•</span>
            <span class="text-xs font-medium text-slate-400">Semester {{ $batchSubject->semester }}</span>
            <span class="text-slate-500 font-bold">•</span>
            <span class="text-xs font-semibold text-cyan-400">Practicum Dual-Mode Scheme</span>
          </div>
          <h1 class="text-xl font-bold text-white tracking-tight">
            {{ $batchSubject->subject_name }}
          </h1>
          <p class="text-xs text-slate-400 mt-1 leading-relaxed">
            Consolidated NBA audit catalog containing 25 official course file artifacts, self-learning portfolios, series examination evaluations, and outcome attainment matrices.
          </p>
        </div>

        <!-- Audit Progress Bar -->
        @php
          $totalCount = $documents->count();
          $verifiedCount = $documents->where('is_checked', true)->count();
          $pct = $totalCount > 0 ? round(($verifiedCount / $totalCount) * 100) : 0;
        @endphp
        <div class="space-y-1.5 pt-2 border-t border-slate-800/80">
          <div class="flex justify-between items-center text-xs">
            <span class="font-bold text-slate-300">NBA Audit Readiness Status</span>
            <span class="font-mono font-bold text-sky-400" id="progress-text">{{ $verifiedCount }} / {{ $totalCount }} Verified ({{ $pct }}%)</span>
          </div>
          <div class="w-full h-2 bg-slate-900 rounded-full overflow-hidden border border-slate-800">
            <div id="progress-bar" class="h-full bg-gradient-to-r from-sky-500 via-cyan-400 to-emerald-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
          </div>
        </div>
      </div>

      <!-- Quick Metrics Grid -->
      <div class="grid grid-cols-2 gap-3">
        <div class="glass-card rounded-2xl p-4 border border-slate-800/80 flex flex-col justify-between">
          <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Artifacts</div>
          <div class="text-2xl font-bold text-white mt-2 font-mono">25</div>
          <div class="text-[11px] text-sky-400 font-medium mt-1">Standard NBA Catalog</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800/80 flex flex-col justify-between">
          <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Verified Items</div>
          <div class="text-2xl font-bold text-emerald-400 mt-2 font-mono" id="stat-verified">{{ $verifiedCount }}</div>
          <div class="text-[11px] text-emerald-400/80 font-medium mt-1">Audit Approved</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800/80 flex flex-col justify-between">
          <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Pending Review</div>
          <div class="text-2xl font-bold text-amber-400 mt-2 font-mono" id="stat-pending">{{ $totalCount - $verifiedCount }}</div>
          <div class="text-[11px] text-amber-400/80 font-medium mt-1">Action Required</div>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-slate-800/80 flex flex-col justify-between">
          <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Compliance</div>
          <div class="text-2xl font-bold text-sky-400 mt-2 font-mono" id="stat-pct">{{ $pct }}%</div>
          <div class="text-[11px] text-sky-400/80 font-medium mt-1">NBA Readiness Index</div>
        </div>
      </div>

    </div>

    <!-- DOCUMENT CHECKLIST CONSOLE TABLE -->
    <div class="glass-card rounded-2xl p-4 md:p-5 border border-slate-800/80 space-y-3.5 shadow-xl">
      
      <!-- Table Filter Bar -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-800/80 pb-3.5">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          </div>
          <div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">NBA Course File Catalog & Verification Index</h2>
            <p class="text-xs text-slate-400 font-normal">25 Mandatory Document Criteria for Internal & External Peer Audit</p>
          </div>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-64">
          <input type="text" id="docSearch" onkeyup="filterDocs()" placeholder="Search document title..." class="w-full bg-slate-900 border border-slate-700/80 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-200 focus:border-sky-500 outline-none transition-all">
          <svg class="w-4 h-4 text-slate-500 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-xl border border-slate-800/80 bg-slate-950/40">
        <table class="w-full text-left border-collapse nba-table">
          <thead>
            <tr class="bg-slate-900/90 text-slate-400 border-b border-slate-800 font-bold">
              <th class="w-14 text-center">Doc #</th>
              <th>Document Description</th>
              <th class="w-36 text-center">Audit Status</th>
              <th class="w-64">Faculty Remarks</th>
              <th class="w-24 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 text-xs" id="docTableBody">
            @foreach($documents as $doc)
              @php
                $num = $doc->document_number;
                $inspectUrl = null;

                if ($num == 1) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "/print-timetable";
                } elseif ($num == 3) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "/attendance-report";
                } elseif ($num == 4 && !empty($practicumCourseFile->syllabus_pdf_path)) {
                  $inspectUrl = asset($practicumCourseFile->syllabus_pdf_path);
                } elseif ($num == 8) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "/print-lesson-plan";
                } elseif ($num == 9) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "/attendance-consolidated";
                } elseif ($num == 14) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "/print-self-learning-splitup";
                } elseif ($num == 15) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "?tab=cia";
                } elseif ($num == 17) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "?tab=practical";
                } elseif ($num == 18) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "?tab=ese";
                } elseif ($num == 19 || $num == 20) {
                  $inspectUrl = "/r26/classroom/practicum/" . $batchSubject->id . "?tab=copo";
                }
              @endphp
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-900/40 transition-all doc-row" data-title="{{ strtolower($doc->document_name) }}">
                <td class="text-center font-mono font-bold">
                  <span class="px-2 py-0.5 rounded bg-sky-500/10 text-sky-300 border border-sky-500/20 text-[11px]">
                    #{{ sprintf('%02d', $doc->document_number) }}
                  </span>
                </td>
                <td class="font-medium text-slate-200">
                  {{ $doc->document_name }}
                </td>
                <td class="text-center">
                  <label class="inline-flex items-center space-x-2 cursor-pointer select-none">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} onchange="saveDocStatus({{ $doc->id }})" class="w-4 h-4 text-sky-500 bg-slate-900 border-slate-700 rounded focus:ring-sky-500/40 cursor-pointer accent-sky-400">
                    <span id="lbl-status-{{ $doc->id }}" class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider transition-all {{ $doc->is_checked ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </span>
                  </label>
                </td>
                <td>
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" onblur="saveDocStatus({{ $doc->id }})" placeholder="Add audit notes..." class="w-full bg-slate-900/80 border border-slate-800 rounded-lg px-2.5 py-1 text-xs text-slate-300 placeholder-slate-600 outline-none focus:border-sky-500 focus:bg-slate-900 transition-all">
                </td>
                <td class="text-center">
                  @if($inspectUrl)
                    <a href="{{ $inspectUrl }}" target="_blank" class="px-3 py-1 bg-sky-500/20 hover:bg-sky-500 text-sky-200 hover:text-white border border-sky-500/40 hover:border-sky-400 rounded-lg font-bold inline-flex items-center space-x-1.5 transition-all text-[11px] shadow-sm">
                      <svg class="w-3.5 h-3.5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <span>View</span>
                    </a>
                  @else
                    <button type="button" onclick="inspectDocDetails({{ $doc->id }}, '{{ addslashes($doc->document_name) }}')" class="px-3 py-1 bg-cyan-500/20 hover:bg-cyan-500 text-cyan-200 hover:text-white border border-cyan-500/40 hover:border-cyan-400 rounded-lg font-bold inline-flex items-center space-x-1.5 transition-all text-[11px] cursor-pointer shadow-sm">
                      <svg class="w-3.5 h-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      <span>Inspect</span>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    const totalDocsCount = {{ $documents->count() }};

    function saveDocStatus(docId) {
        const isChecked = document.getElementById('check-' + docId).checked;
        const remarks = document.getElementById('remarks-' + docId).value;
        const lbl = document.getElementById('lbl-status-' + docId);

        if (isChecked) {
            lbl.innerText = 'Verified';
            lbl.className = 'px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider transition-all bg-emerald-500/15 text-emerald-300 border border-emerald-500/30';
        } else {
            lbl.innerText = 'Pending';
            lbl.className = 'px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider transition-all bg-slate-800 text-slate-400 border border-slate-700';
        }

        // Recalculate summary metrics in real-time
        recalculateMetrics();

        fetch('/api/r26/classroom/practicum/course-file/{{ $batchSubject->id }}/save-doc', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                doc_id: docId,
                is_checked: isChecked,
                remarks: remarks
            })
        });
    }

    function recalculateMetrics() {
        let verified = 0;
        for (let i = 1; i <= totalDocsCount; i++) {
            const chk = document.getElementById('check-' + i);
            if (chk && chk.checked) {
                verified++;
            }
        }
        const pending = totalDocsCount - verified;
        const pct = totalDocsCount > 0 ? Math.round((verified / totalDocsCount) * 100) : 0;

        document.getElementById('stat-verified').innerText = verified;
        document.getElementById('stat-pending').innerText = pending;
        document.getElementById('stat-pct').innerText = pct + '%';
        document.getElementById('progress-text').innerText = `${verified} / ${totalDocsCount} Verified (${pct}%)`;
        document.getElementById('progress-bar').style.width = `${pct}%`;
    }

    function filterDocs() {
        const query = document.getElementById('docSearch').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.doc-row');
        rows.forEach(r => {
            const title = r.getAttribute('data-title') || '';
            if (title.includes(query)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    function inspectDocDetails(docId, docTitle) {
        const isChecked = document.getElementById('check-' + docId).checked;
        const remarks = document.getElementById('remarks-' + docId).value || 'No custom remarks recorded.';
        const statusText = isChecked ? '<span class="text-emerald-400 font-bold">Verified</span>' : '<span class="text-amber-400 font-bold">Pending Verification</span>';

        Swal.fire({
            title: `Inspect Document #${String(docId).padStart(2, '0')}`,
            html: `
                <div class="text-left space-y-3 text-sm">
                    <p class="font-bold text-white">${docTitle}</p>
                    <div class="p-3 bg-slate-900 rounded-lg border border-slate-800 space-y-1 text-xs">
                        <div><strong class="text-slate-400">Audit Status:</strong> ${statusText}</div>
                        <div><strong class="text-slate-400">Current Notes:</strong> <span class="text-slate-200">${remarks}</span></div>
                    </div>
                    <p class="text-xs text-slate-400">You can toggle the audit verification state or update notes directly in the console checklist row.</p>
                </div>
            `,
            icon: 'info',
            background: '#0f172a',
            color: '#f8fafc',
            confirmButtonColor: '#0284c7',
            confirmButtonText: 'Close Inspection'
        });
    }
  </script>
</body>
</html>
