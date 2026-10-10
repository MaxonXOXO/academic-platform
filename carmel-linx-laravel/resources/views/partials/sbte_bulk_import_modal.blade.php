<!-- SBTE Subject Log & Attendance Bulk Import Modal -->
<div id="sbteImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden p-4">
  <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
    
    <!-- Modal Header -->
    <div class="px-5 py-4 bg-slate-950 border-b border-slate-800 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center text-white shadow-md">
          <span class="material-symbols-rounded text-lg">cloud_upload</span>
        </div>
        <div>
          <h3 class="font-extrabold text-sm sm:text-base text-white">Upload TEAMS Attendance</h3>
          <p class="text-[11px] text-slate-400">Upload class attendance Excel spreadsheet (.xlsx, .xls, .csv) or PDF from TEAMS portal.</p>
        </div>
      </div>
      <button type="button" onclick="closeSbteImportModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer">
        <span class="material-symbols-rounded text-xl">close</span>
      </button>
    </div>

    <!-- Modal Body -->
    <form id="sbteImportForm" onsubmit="submitSbteImport(event)" class="p-5 overflow-y-auto space-y-4 flex-grow custom-scrollbar">
      
      <!-- Target Subject Info -->
      <div class="bg-slate-950 border border-slate-800 rounded-xl p-3">
        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Target Course / Subject</label>
        <div id="sbteTargetSubjectDisplay" class="text-xs font-semibold text-blue-300 flex items-center gap-2">
          <span class="material-symbols-rounded text-sm text-blue-400">school</span>
          <span id="sbteTargetSubjectName">Select a subject first</span>
        </div>
        <input type="hidden" id="sbteBatchSubjectId" name="batch_subject_id" value="">
      </div>

      <!-- Batch Allocation Mode Selector -->
      <div class="space-y-2">
        <label class="block text-[11px] font-bold text-slate-300">Class / Lab Batch Allocation Mode</label>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="sbteRadioGroup">
          <!-- Split Batch Card -->
          <label id="sbteSplitCard" class="relative flex items-start gap-2.5 p-2.5 rounded-xl border border-blue-500/60 bg-blue-950/30 hover:border-blue-400 transition cursor-pointer group">
            <input type="radio" name="lab_upload_mode" id="sbteModeSplit" value="split" checked onchange="onSbteUploadModeChange()" class="mt-0.5 text-blue-600 focus:ring-blue-500 bg-slate-900 border-slate-700">
            <div class="space-y-0.5">
              <div class="flex items-center gap-1.5">
                <span class="block text-xs font-bold text-white group-hover:text-blue-300 transition">Split Batch Lab</span>
                <span id="sbteSplitBadge" class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-500/20 text-blue-300 uppercase">Recommended</span>
              </div>
              <p class="text-[10px] text-slate-400 leading-snug" id="sbteSplitDesc">Batches 1 &amp; 2 conducted separately based on Virtual Lab Setup. Conducted hours calculated per batch.</p>
            </div>
          </label>

          <!-- Full Batch Card -->
          <label id="sbteFullCard" class="relative flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-blue-500/70 transition cursor-pointer group">
            <input type="radio" name="lab_upload_mode" id="sbteModeFull" value="full" onchange="onSbteUploadModeChange()" class="mt-0.5 text-blue-600 focus:ring-blue-500 bg-slate-900 border-slate-700">
            <div class="space-y-0.5">
              <span class="block text-xs font-bold text-white group-hover:text-blue-300 transition">Full Batch / Theory</span>
              <p class="text-[10px] text-slate-400 leading-snug">Whole class attended together. Total hours in uploaded file applies to all students.</p>
            </div>
          </label>
        </div>

        <!-- Optional Sub-batch Scope Dropdown -->
        <div id="sbteSubBatchScopeWrapper" class="pt-1">
          <label class="block text-[10px] font-semibold text-slate-400 mb-1">File Content Scope:</label>
          <select id="sbteSubBatch" name="sub_batch" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 outline-none focus:border-blue-500">
            <option value="Whole" selected>Both Batches Combined in this file (All Dates)</option>
            <option value="1">Only Batch 1 sessions in this file (Roll 1 to Cutoff)</option>
            <option value="2">Only Batch 2 sessions in this file (Roll Cutoff+1 to End)</option>
          </select>
        </div>
      </div>

      <!-- File Upload Zone -->
      <div>
        <div class="flex items-center justify-between mb-1">
          <label class="block text-[11px] font-bold text-slate-300">Upload Class Attendance from TEAMS</label>
          <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
            <span class="material-symbols-rounded text-xs">table_view</span> Excel / Spreadsheet (Recommended)
          </span>
        </div>
        <div id="sbteDropZone" onclick="document.getElementById('sbteFileInput').click()" class="border-2 border-dashed border-slate-700 hover:border-emerald-500 bg-slate-950/60 rounded-xl p-5 text-center cursor-pointer transition flex flex-col items-center justify-center gap-2">
          <input type="file" id="sbteFileInput" name="file" accept=".xlsx,.xls,.csv,.html,.htm,.pdf" class="hidden" onchange="handleSbteFileSelect(this)">
          <div class="flex items-center gap-2 text-emerald-400">
            <span class="material-symbols-rounded text-3xl">table_chart</span>
            <span class="text-xs text-slate-500 font-bold">or</span>
            <span class="material-symbols-rounded text-2xl text-blue-400">picture_as_pdf</span>
          </div>
          <div class="text-xs font-bold text-slate-200" id="sbteFileLabel">Click or drag &amp; drop Excel (.xlsx, .xls, .csv) or PDF from TEAMS here</div>
          <div class="text-[10px] text-slate-400">Official "ATTENDANCE STATEMENT" or "SUBJECT LOG" exported from TEAMS portal</div>
          <div class="flex items-center gap-2 mt-1">
            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-950/60 text-emerald-400 border border-emerald-800/40">Excel: Exact Parsing</span>
            <span class="px-2 py-0.5 rounded text-[9px] font-medium bg-slate-800 text-slate-400">PDF: Supported</span>
          </div>
          <div id="sbteSelectedFileInfo" class="hidden mt-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-xs text-emerald-300 font-mono font-medium"></div>
        </div>
      </div>

      <!-- Smart Options -->
      <div class="space-y-2 pt-1">
        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-300 select-none">
          <input type="checkbox" id="sbteAutoFillLp" name="auto_fill_lesson_plan" value="1" checked class="mt-0.5 rounded border-slate-700 text-blue-600 focus:ring-blue-500">
          <div>
            <span class="font-bold text-white">Auto-fill blank topics from Lesson Plan / Lab Experiments</span>
            <p class="text-[11px] text-slate-400">When the uploaded file has blank topics, automatically assigns the next pending topic in syllabus sequence and marks it completed.</p>
          </div>
        </label>
      </div>

      <!-- Calculation Notice Box -->
      <div class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-start gap-2.5 text-[11px] text-amber-300/90 leading-relaxed">
        <span class="material-symbols-rounded text-base text-amber-400 shrink-0 mt-0.5">info</span>
        <div>
          <strong>Exact Hour Calculations:</strong> If a date has multiple periods (like <code class="bg-amber-500/20 px-1 py-0.5 rounded text-[10px] font-mono">Hours: 4,6</code>), separate period entries are created for each hour so that student attendance percentages and conducted hours are 100% accurate.
        </div>
      </div>

      <!-- Error / Status Alert -->
      <div id="sbteImportError" class="hidden p-3 rounded-lg text-xs font-semibold bg-rose-500/15 border border-rose-500/30 text-rose-300"></div>

      <!-- Success Result Box -->
      <div id="sbteImportSuccess" class="hidden p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-200 space-y-3">
        <div class="flex items-center gap-2 text-sm font-bold text-emerald-400">
          <span class="material-symbols-rounded text-lg">check_circle</span>
          <span id="sbteSuccessTitle">Import Successful!</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
          <div class="bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/20">
            <div class="text-[10px] text-slate-400 uppercase font-bold">Sessions</div>
            <div id="resSessions" class="text-base font-extrabold text-white mt-0.5">0</div>
          </div>
          <div class="bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/20">
            <div class="text-[10px] text-slate-400 uppercase font-bold">Total Hours</div>
            <div id="resHours" class="text-base font-extrabold text-white mt-0.5">0</div>
          </div>
          <div class="bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/20">
            <div class="text-[10px] text-slate-400 uppercase font-bold">Topics Synced</div>
            <div id="resLp" class="text-base font-extrabold text-white mt-0.5">0</div>
          </div>
          <div class="bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/20">
            <div class="text-[10px] text-slate-400 uppercase font-bold">Students Marked</div>
            <div id="resStudents" class="text-base font-extrabold text-white mt-0.5">0</div>
          </div>
        </div>
        <p class="text-[11px] text-emerald-300/80">All logs are now live in your Class Log table below. You can click "Edit" on any session to adjust topics or mark absentees.</p>
      </div>

      <!-- Modal Footer -->
      <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
        <button type="button" onclick="closeSbteImportModal()" class="px-4 py-2 text-xs font-bold rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition cursor-pointer">
          Close
        </button>
        <button type="submit" id="sbteSubmitBtn" class="px-5 py-2 text-xs font-bold rounded-lg bg-blue-600 hover:bg-blue-500 text-white transition flex items-center gap-1.5 shadow-md cursor-pointer">
          <span class="material-symbols-rounded text-sm">cloud_upload</span>
          <span id="sbteSubmitText">Upload TEAMS Attendance</span>
        </button>
      </div>

    </form>
  </div>
</div>

<script>
  function onSbteUploadModeChange() {
    const isSplit = document.getElementById('sbteModeSplit')?.checked;
    const splitCard = document.getElementById('sbteSplitCard');
    const fullCard = document.getElementById('sbteFullCard');
    const scopeWrapper = document.getElementById('sbteSubBatchScopeWrapper');

    if (isSplit) {
      if (splitCard) {
        splitCard.className = "relative flex items-start gap-2.5 p-2.5 rounded-xl border border-blue-500/80 bg-blue-950/40 hover:border-blue-400 transition cursor-pointer group shadow-sm";
      }
      if (fullCard) {
        fullCard.className = "relative flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-slate-600 transition cursor-pointer group";
      }
      if (scopeWrapper) scopeWrapper.classList.remove('hidden');
    } else {
      if (fullCard) {
        fullCard.className = "relative flex items-start gap-2.5 p-2.5 rounded-xl border border-blue-500/80 bg-blue-950/40 hover:border-blue-400 transition cursor-pointer group shadow-sm";
      }
      if (splitCard) {
        splitCard.className = "relative flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-slate-600 transition cursor-pointer group";
      }
      if (scopeWrapper) scopeWrapper.classList.add('hidden');
    }
  }

  function openSbteImportModal() {
    const subjectSelect = document.getElementById('subjectSelect');
    const subjectId = subjectSelect ? subjectSelect.value : '';
    const errorDiv = document.getElementById('sbteImportError');
    const successDiv = document.getElementById('sbteImportSuccess');
    if (errorDiv) errorDiv.classList.add('hidden');
    if (successDiv) successDiv.classList.add('hidden');

    if (!subjectId) {
      alert("Please select a Class Subject / Batch first before importing.");
      return;
    }

    const selOption = subjectSelect.options[subjectSelect.selectedIndex];
    document.getElementById('sbteTargetSubjectName').innerText = selOption ? selOption.text : 'Selected Subject';
    document.getElementById('sbteBatchSubjectId').value = subjectId;

    // Reset file input
    const fileInput = document.getElementById('sbteFileInput');
    if (fileInput) fileInput.value = '';
    const fileInfo = document.getElementById('sbteSelectedFileInfo');
    if (fileInfo) fileInfo.classList.add('hidden');
    const fileLabel = document.getElementById('sbteFileLabel');
    if (fileLabel) fileLabel.innerText = 'Click or drag & drop Excel (.xlsx, .xls, .csv) or PDF from TEAMS here';

    // Auto-detect Virtual Lab Setup Range (Cutoff & Split vs Full Mode)
    fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
      .then(res => res.json())
      .then(dt => {
        if (dt.status === 'SUCCESS') {
          const isPractical = dt.subject_type && dt.subject_type !== 'Theory';
          const isSplitConfigured = isPractical && (dt.lab_batch_mode === 'split' || dt.lab_batch_cutoff || dt.batch_split_summary?.is_configured);
          
          const splitRadio = document.getElementById('sbteModeSplit');
          const fullRadio = document.getElementById('sbteModeFull');
          const splitDesc = document.getElementById('sbteSplitDesc');
          const splitBadge = document.getElementById('sbteSplitBadge');
          const splitCard = document.getElementById('sbteSplitCard');

          if (!isPractical) {
            // Theory Subject
            if (fullRadio) fullRadio.checked = true;
            if (splitCard) splitCard.classList.add('opacity-40');
            if (splitBadge) splitBadge.classList.add('hidden');
          } else {
            // Practical Subject
            if (splitCard) splitCard.classList.remove('opacity-40');
            if (splitBadge) splitBadge.classList.remove('hidden');

            if (isSplitConfigured) {
              if (splitRadio) splitRadio.checked = true;
              const summary = dt.batch_split_summary || {};
              if (splitDesc) {
                const b1R = summary.b1_range || (dt.lab_batch_cutoff ? '1-' + dt.lab_batch_cutoff : '1-25');
                const b2R = summary.b2_range || (dt.lab_batch_cutoff ? (parseInt(dt.lab_batch_cutoff)+1)+'+' : '26+');
                splitDesc.innerText = `Virtual Lab Setup: Batch 1 (Roll ${b1R}) & Batch 2 (Roll ${b2R}). Calculates attendance against each batch's actual conducted sessions.`;
              }
            } else {
              if (fullRadio) fullRadio.checked = true;
            }
          }
          onSbteUploadModeChange();
        }
      })
      .catch(() => {
        onSbteUploadModeChange();
      });

    document.getElementById('sbteImportModal').classList.remove('hidden');
  }

  function closeSbteImportModal() {
    document.getElementById('sbteImportModal').classList.add('hidden');
  }

  function handleSbteFileSelect(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const fileInfo = document.getElementById('sbteSelectedFileInfo');
      const fileLabel = document.getElementById('sbteFileLabel');
      if (fileInfo) {
        fileInfo.innerText = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        fileInfo.classList.remove('hidden');
      }
      if (fileLabel) fileLabel.innerText = 'File Selected:';
    }
  }

  // Support Drag and Drop
  const dropZone = document.getElementById('sbteDropZone');
  if (dropZone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-500/10');
      }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-500/10');
      }, false);
    });
    dropZone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files && files.length > 0) {
        const fileInput = document.getElementById('sbteFileInput');
        fileInput.files = files;
        handleSbteFileSelect(fileInput);
      }
    }, false);
  }

  function submitSbteImport(e) {
    e.preventDefault();
    const form = document.getElementById('sbteImportForm');
    const fileInput = document.getElementById('sbteFileInput');
    const errorDiv = document.getElementById('sbteImportError');
    const successDiv = document.getElementById('sbteImportSuccess');
    const submitBtn = document.getElementById('sbteSubmitBtn');
    const submitText = document.getElementById('sbteSubmitText');

    if (!fileInput.files || fileInput.files.length === 0) {
      errorDiv.innerText = "Please select a TEAMS class attendance Excel (.xlsx, .xls, .csv) or PDF file to upload.";
      errorDiv.classList.remove('hidden');
      return;
    }

    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    errorDiv.classList.add('hidden');
    submitBtn.disabled = true;
    submitText.innerText = "Importing & Syncing...";

    fetch('/api/staff/attendance/import-sbte-pdf', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      submitBtn.disabled = false;
      submitText.innerText = "Upload TEAMS Attendance";

      if (data.status === 'SUCCESS') {
        successDiv.classList.remove('hidden');
        document.getElementById('sbteSuccessTitle').innerText = data.message || "Import Successful!";
        const d = data.data || {};
        document.getElementById('resSessions').innerText = d.imported_sessions || 0;
        document.getElementById('resHours').innerText = d.total_hours || 0;
        document.getElementById('resLp').innerText = d.mapped_lesson_plans || 0;
        document.getElementById('resStudents').innerText = d.students_count || 0;

        // Auto reload recorded logs and subject details
        if (typeof loadDesktopPastLogs === 'function') {
          loadDesktopPastLogs();
        }
        if (typeof onSubjectChange === 'function') {
          // Refresh subject details without clearing past logs
          const sid = document.getElementById('subjectSelect')?.value;
          if (sid) {
            fetch(`/api/staff/attendance/subjects/${sid}/details`)
              .then(res => res.json())
              .then(dt => {
                if (dt.status === 'SUCCESS' && typeof dt.next_log_sl_no !== 'undefined') {
                  const pointer = document.getElementById('logNextSlNoPointer');
                  if (pointer) pointer.innerText = `Next Entry: #${dt.next_log_sl_no}`;
                }
              }).catch(() => {});
          }
        }
      } else {
        errorDiv.innerText = data.message || "Failed to import TEAMS attendance.";
        errorDiv.classList.remove('hidden');
      }
    })
    .catch(err => {
      console.error("SBTE Import Error:", err);
      submitBtn.disabled = false;
      submitText.innerText = "Import Logs & Attendance";
      errorDiv.innerText = "Error connecting to server. Please try again.";
      errorDiv.classList.remove('hidden');
    });
  }
</script>
