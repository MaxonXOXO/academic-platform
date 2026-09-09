<!-- Practical Lab Batch Setup Modal -->
<div id="labBatchSetupModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] hidden items-center justify-center p-2 sm:p-3 lg:p-4 transition-all duration-200">
    <div id="labBatchSetupModalDialog" class="bg-slate-900 border border-slate-700/80 rounded-2xl w-full max-w-[96vw] xl:max-w-[1500px] h-[95vh] max-h-[95vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 transition-all">
        
        <!-- Header -->
        <div class="px-5 py-3.5 bg-slate-950/95 border-b border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-xl">group_work</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight">Practical Lab Batch Setup</h3>
                        <span class="px-2 py-0.5 rounded-md bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-[10px] font-bold uppercase tracking-wider hidden sm:inline">Workspace</span>
                    </div>
                    <p class="text-[11px] text-slate-400 leading-tight mt-0.5" id="batchSetupSubjectSubtitle">Configure Full vs Split Batch &amp; Cutoff Roll Numbers</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" onclick="toggleLabBatchSetupFullscreen()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition flex items-center justify-center cursor-pointer" title="Toggle Fullscreen View">
                    <span class="material-symbols-rounded text-lg" id="batchSetupFullscreenIcon">fullscreen</span>
                </button>
                <button type="button" onclick="closeLabBatchSetupModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition flex items-center justify-center cursor-pointer" title="Close">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
            </div>
        </div>

        <!-- Body: 2 Columns on Desktop (lg:) -->
        <div class="flex-1 overflow-hidden p-3 sm:p-4 lg:p-5 flex flex-col lg:flex-row gap-4 lg:gap-6 min-h-0 text-xs">

            <!-- LEFT COLUMN: Setup Configuration Controls -->
            <div class="lg:w-[420px] xl:w-[460px] shrink-0 flex flex-col overflow-y-auto custom-scrollbar space-y-4 pr-1">
                
                <!-- Notice Alert Box -->
                <div id="batchSetupNoticeBox" class="p-3.5 rounded-xl bg-indigo-950/40 border border-indigo-500/30 text-indigo-200 flex items-start gap-2.5 shrink-0 shadow-sm">
                    <span class="material-symbols-rounded text-indigo-400 text-lg shrink-0 mt-0.5">info</span>
                    <div class="text-[11px] leading-relaxed">
                        <span class="font-bold block text-indigo-100 mb-0.5">Faculty Batch Division Authority</span>
                        Faculty have complete control to set this practical as <strong>Full Batch</strong> (whole class) or <strong>Split Batch</strong> (2 batches) based on lab space, workstation availability, and syllabus requirements.
                    </div>
                </div>

                <!-- Mode Selection: Split vs Full -->
                <div class="space-y-1.5 shrink-0">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Select Lab Mode</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Split Batch Card -->
                        <label class="relative flex items-start gap-2.5 p-3 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-indigo-500/70 transition cursor-pointer group">
                            <input type="radio" name="labBatchModeRadio" value="split" checked onchange="onLabBatchModeChange()" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 bg-slate-900 border-slate-700">
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-white group-hover:text-indigo-300 transition">Split Batch</span>
                                <p class="text-[10px] text-slate-400 leading-snug">Divide into Batch 1 &amp; Batch 2 for practical slots.</p>
                            </div>
                        </label>

                        <!-- Full Batch Card -->
                        <label class="relative flex items-start gap-2.5 p-3 rounded-xl border border-slate-700/80 bg-slate-950/70 hover:border-indigo-500/70 transition cursor-pointer group">
                            <input type="radio" name="labBatchModeRadio" value="full" onchange="onLabBatchModeChange()" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 bg-slate-900 border-slate-700">
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-white group-hover:text-indigo-300 transition">Full Batch</span>
                                <p class="text-[10px] text-slate-400 leading-snug">Whole class conducts practicals together.</p>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- Split Configuration Controls (Hidden when Full Batch selected) -->
                <div id="splitBatchConfigArea" class="space-y-4 pt-3 border-t border-slate-800/80">
                    
                    <!-- Cutoff Input & Quick Presets -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-[11px] font-bold text-slate-300">Batch 1 Cutoff Roll Number</label>
                            <span class="text-[10.5px] text-slate-400 font-mono">(Roll 1 to X)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                            <!-- Stepper Container -->
                            <div class="sm:col-span-6 flex items-center rounded-xl border border-slate-700 bg-slate-950 focus-within:border-indigo-500 shadow-inner transition overflow-hidden">
                                <button type="button" onclick="stepCutoff(-1)" class="w-10 h-9 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer select-none" title="Decrease Roll Cutoff">
                                    <span class="material-symbols-rounded text-lg">remove</span>
                                </button>
                                <input type="number" id="batchSetupCutoffInput" min="1" max="200" placeholder="25" oninput="onCutoffInputChange()" class="w-full bg-transparent text-center py-1.5 text-sm text-white font-mono font-bold outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="stepCutoff(1)" class="w-10 h-9 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer select-none" title="Increase Roll Cutoff">
                                    <span class="material-symbols-rounded text-lg">add</span>
                                </button>
                            </div>

                            <!-- Quick Preset Buttons -->
                            <div class="sm:col-span-6 flex items-center gap-1.5">
                                <button type="button" onclick="applyEqualSplit()" class="flex-1 px-2 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-xl text-[10.5px] font-bold transition cursor-pointer flex items-center justify-center gap-1 shadow-sm">
                                    <span class="material-symbols-rounded text-xs">pie_chart</span> 50/50
                                </button>
                                <button type="button" id="btnPreset25" onclick="setPresetCutoff(25)" class="flex-1 px-2 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-xl text-[10.5px] font-bold transition cursor-pointer flex items-center justify-center shadow-sm">
                                    1-25 &amp; 26+
                                </button>
                                <button type="button" onclick="resetToInitialCutoff()" class="px-2 py-2 text-slate-400 hover:text-slate-200 text-[10.5px] font-semibold transition cursor-pointer">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Batch Summary Cards -->
                    <div class="grid grid-cols-2 gap-3 p-3 bg-slate-950/80 border border-slate-800 rounded-xl shadow-sm">
                        <div class="p-2.5 bg-indigo-950/40 border border-indigo-700/40 rounded-xl text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 block">Batch 1</span>
                            <div class="text-base font-bold text-white font-mono mt-0.5">
                                <span id="setupB1CountText">0</span> <span class="text-[11px] font-normal text-indigo-300">Students</span>
                            </div>
                            <div class="text-[10.5px] text-slate-400 font-mono mt-0.5" id="setupB1RangeText">Roll 1 - 25</div>
                        </div>
                        <div class="p-2.5 bg-purple-950/40 border border-purple-700/40 rounded-xl text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-400 block">Batch 2</span>
                            <div class="text-base font-bold text-white font-mono mt-0.5">
                                <span id="setupB2CountText">0</span> <span class="text-[11px] font-normal text-purple-300">Students</span>
                            </div>
                            <div class="text-[10.5px] text-slate-400 font-mono mt-0.5" id="setupB2RangeText">Roll 26 - 51</div>
                        </div>
                    </div>

                </div>

                <!-- Apply to all subjects checkbox (sticks neatly at bottom of left column) -->
                <div class="pt-3 border-t border-slate-800/80 mt-auto">
                    <label class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 cursor-pointer select-none transition">
                        <input type="checkbox" id="batchSetupApplyAllCheckbox" checked class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 bg-slate-900 border-slate-700 shrink-0">
                        <div class="space-y-0.5">
                            <span class="text-xs font-semibold text-slate-200 block">Apply to all practicals in this semester</span>
                            <p class="text-[10.5px] text-slate-400 leading-snug">Synchronizes Batch 1 &amp; Batch 2 across all practical subjects in this classroom so student groupings remain identical.</p>
                        </div>
                    </label>
                </div>

            </div>

            <!-- RIGHT COLUMN: High-Density Student Roster Workspace -->
            <div class="flex-1 flex flex-col min-h-0 bg-slate-950/70 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
                
                <!-- Roster Toolbar -->
                <div class="p-3 bg-slate-900/90 border-b border-slate-800 flex flex-wrap items-center justify-between gap-2.5 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <span class="font-bold text-slate-200 text-xs flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-indigo-400 text-base">badge</span>
                            Student Roster &amp; Batch Assignments
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 font-mono text-[10.5px] font-bold" id="rosterTotalCountBadge">0 Students</span>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Filter Tabs -->
                        <div class="inline-flex rounded-lg p-0.5 bg-slate-950 border border-slate-800 text-[11px]">
                            <button type="button" onclick="filterBatchSetupRosterTab('all')" id="rosterTab_all" class="px-2.5 py-0.5 rounded-md font-bold transition bg-indigo-600 text-white cursor-pointer">All</button>
                            <button type="button" onclick="filterBatchSetupRosterTab('1')" id="rosterTab_1" class="px-2.5 py-0.5 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 1</button>
                            <button type="button" onclick="filterBatchSetupRosterTab('2')" id="rosterTab_2" class="px-2.5 py-0.5 rounded-md font-semibold transition text-slate-400 hover:text-slate-200 cursor-pointer">Batch 2</button>
                        </div>

                        <!-- Search Box -->
                        <div class="relative">
                            <input type="text" id="batchSetupStudentSearch" placeholder="Search roll, name..." oninput="filterBatchSetupStudentList()" class="bg-slate-950 border border-slate-700 rounded-lg pl-7 pr-2.5 py-1 text-xs text-white placeholder-slate-500 outline-none focus:border-indigo-500 w-36 sm:w-48 transition">
                            <span class="material-symbols-rounded text-xs text-slate-500 absolute left-2 top-1.5 pointer-events-none">search</span>
                        </div>
                    </div>
                </div>

                <!-- High-Density Student Table: Fills full available height -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-slate-400 font-bold uppercase text-[10px] tracking-wider z-10 shadow-sm">
                            <tr>
                                <th class="py-2.5 px-3 w-16 text-center">Roll</th>
                                <th class="py-2.5 px-3 w-32">Register No</th>
                                <th class="py-2.5 px-3">Student Name</th>
                                <th class="py-2.5 px-3 text-center w-36">Current Batch</th>
                                <th class="py-2.5 px-3 text-center w-28">Quick Action</th>
                            </tr>
                        </thead>
                        <tbody id="batchSetupStudentTbody" class="divide-y divide-slate-800/40">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Roster Bottom Status Bar -->
                <div class="px-3.5 py-2 bg-slate-900/80 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-400 shrink-0">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-indigo-400 text-xs">touch_app</span>
                        <span>Click <strong class="text-indigo-300">B1</strong> or <strong class="text-purple-300">B2</strong> to manually toggle any individual student.</span>
                    </div>
                    <div id="rosterShowingCounter" class="font-mono text-slate-400">Showing 51 students</div>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="px-5 py-3 bg-slate-950/95 border-t border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 text-xs text-slate-400 hidden sm:flex">
                <span class="material-symbols-rounded text-emerald-400 text-sm">verified</span>
                <span>Configuring this batch updates practical attendance and continuous evaluation tables instantly.</span>
            </div>
            <div class="flex items-center gap-2 ms-auto">
                <button type="button" onclick="closeLabBatchSetupModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" id="btnSaveLabBatchSetup" onclick="saveLabBatchSetup()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30 cursor-pointer">
                    <span class="material-symbols-rounded text-base">check</span>
                    <span>Save Batch Configuration</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    window.labBatchSetupState = {
        subjectId: null,
        mode: 'split',
        cutoff: null,
        students: [],
        initialCutoff: null,
        onSavedCallback: null
    };

    window.activeRosterTab = 'all';

    window.openLabBatchSetupModal = async function(subjectId, callback) {
        const sid = subjectId || (typeof currentSubjectId !== 'undefined' ? currentSubjectId : null) || (document.getElementById('subjectSelect') ? document.getElementById('subjectSelect').value : null);
        if (!sid) {
            alert("Please select a subject first.");
            return;
        }

        window.labBatchSetupState.subjectId = sid;
        window.labBatchSetupState.onSavedCallback = callback || null;

        const modal = document.getElementById('labBatchSetupModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fetch current setup from API
        try {
            const res = await fetch(`/api/classroom/${sid}/practical/batch-setup`);
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                window.labBatchSetupState.mode = data.lab_batch_mode || 'split';
                window.labBatchSetupState.cutoff = data.lab_batch_cutoff || null;
                window.labBatchSetupState.initialCutoff = data.lab_batch_cutoff || null;
                window.labBatchSetupState.students = (data.students || []).map(s => ({
                    reg_no: s.reg_no,
                    name: s.name,
                    roll_no: s.roll_no,
                    lab_batch: String(s.lab_batch || '1')
                }));

                // Set Subtitle
                const subEl = document.getElementById('batchSetupSubjectSubtitle');
                if (subEl) {
                    subEl.innerText = `${data.subject_name || 'Practical Lab'} • ${data.total_students || 0} Students enrolled`;
                }

                // If no cutoff configured, calculate default 50/50 cutoff
                if (!window.labBatchSetupState.cutoff && window.labBatchSetupState.students.length > 0) {
                    const mid = Math.ceil(window.labBatchSetupState.students.length / 2);
                    const midStudent = window.labBatchSetupState.students[mid - 1];
                    window.labBatchSetupState.cutoff = midStudent && midStudent.roll_no ? parseInt(midStudent.roll_no) : mid;
                }

                initLabBatchSetupUI();
            } else {
                alert(data.message || "Failed to load lab batch configuration.");
            }
        } catch (err) {
            console.error("Error loading lab batch setup:", err);
            alert("Could not load lab batch details.");
        }
    };

    window.closeLabBatchSetupModal = function() {
        const modal = document.getElementById('labBatchSetupModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.toggleLabBatchSetupFullscreen = function() {
        const dialog = document.getElementById('labBatchSetupModalDialog');
        const icon = document.getElementById('batchSetupFullscreenIcon');
        if (!dialog) return;

        if (dialog.classList.contains('is-fullscreen')) {
            dialog.classList.remove('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
            dialog.classList.add('rounded-2xl', 'max-w-[96vw]', 'xl:max-w-[1500px]', 'h-[95vh]', 'max-h-[95vh]');
            if (icon) icon.innerText = 'fullscreen';
        } else {
            dialog.classList.add('is-fullscreen', '!w-full', '!h-full', '!max-w-none', '!max-h-none', '!rounded-none');
            dialog.classList.remove('rounded-2xl', 'max-w-[96vw]', 'xl:max-w-[1500px]', 'h-[95vh]', 'max-h-[95vh]');
            if (icon) icon.innerText = 'fullscreen_exit';
        }
    };

    function initLabBatchSetupUI() {
        const state = window.labBatchSetupState;
        
        // Set Mode Radio
        const modeRadios = document.querySelectorAll('input[name="labBatchModeRadio"]');
        modeRadios.forEach(r => {
            r.checked = (r.value === state.mode);
        });

        // Set Cutoff Input
        const cutoffInput = document.getElementById('batchSetupCutoffInput');
        if (cutoffInput) {
            cutoffInput.value = state.cutoff || '';
        }

        onLabBatchModeChange();
        recalculateBatchesFromCutoff();
    }

    window.onLabBatchModeChange = function() {
        const selected = document.querySelector('input[name="labBatchModeRadio"]:checked');
        const mode = selected ? selected.value : 'split';
        window.labBatchSetupState.mode = mode;

        const splitArea = document.getElementById('splitBatchConfigArea');
        if (splitArea) {
            if (mode === 'full') {
                splitArea.classList.add('hidden');
            } else {
                splitArea.classList.remove('hidden');
            }
        }
    };

    window.onCutoffInputChange = function() {
        const val = document.getElementById('batchSetupCutoffInput').value;
        const cutoff = parseInt(val);
        if (!isNaN(cutoff) && cutoff > 0) {
            window.labBatchSetupState.cutoff = cutoff;
            recalculateBatchesFromCutoff();
        }
    };

    window.stepCutoff = function(delta) {
        const inp = document.getElementById('batchSetupCutoffInput');
        if (!inp) return;
        let cur = parseInt(inp.value) || 25;
        cur = Math.max(1, cur + delta);
        inp.value = cur;
        window.labBatchSetupState.cutoff = cur;
        recalculateBatchesFromCutoff();
    };

    window.setPresetCutoff = function(num) {
        window.labBatchSetupState.cutoff = num;
        const inp = document.getElementById('batchSetupCutoffInput');
        if (inp) inp.value = num;
        recalculateBatchesFromCutoff();
    };

    window.applyEqualSplit = function() {
        const students = window.labBatchSetupState.students;
        if (!students || students.length === 0) return;
        const mid = Math.ceil(students.length / 2);
        const midStudent = students[mid - 1];
        const cutoff = (midStudent && midStudent.roll_no) ? parseInt(midStudent.roll_no) : mid;
        setPresetCutoff(cutoff);
    };

    window.resetToInitialCutoff = function() {
        if (window.labBatchSetupState.initialCutoff) {
            setPresetCutoff(window.labBatchSetupState.initialCutoff);
        } else {
            applyEqualSplit();
        }
    };

    function recalculateBatchesFromCutoff() {
        const state = window.labBatchSetupState;
        const cutoff = state.cutoff;
        if (!cutoff) return;

        state.students.forEach((s, idx) => {
            if (s.roll_no !== null && s.roll_no !== undefined) {
                s.lab_batch = (parseInt(s.roll_no) <= cutoff) ? '1' : '2';
            } else {
                s.lab_batch = (idx < cutoff) ? '1' : '2';
            }
        });

        renderBatchSetupRoster();
        updateBatchSetupCounters();
    }

    function updateBatchSetupCounters() {
        const students = window.labBatchSetupState.students;
        const b1 = students.filter(s => s.lab_batch === '1');
        const b2 = students.filter(s => s.lab_batch === '2');

        const b1CountEl = document.getElementById('setupB1CountText');
        const b2CountEl = document.getElementById('setupB2CountText');
        if (b1CountEl) b1CountEl.innerText = b1.length;
        if (b2CountEl) b2CountEl.innerText = b2.length;

        const b1RangeEl = document.getElementById('setupB1RangeText');
        const b2RangeEl = document.getElementById('setupB2RangeText');

        if (b1.length > 0 && b1RangeEl) {
            const minR = Math.min(...b1.map(s => s.roll_no ? parseInt(s.roll_no) : 1));
            const maxR = Math.max(...b1.map(s => s.roll_no ? parseInt(s.roll_no) : b1.length));
            b1RangeEl.innerText = `Roll ${minR} - ${maxR}`;
        }
        if (b2.length > 0 && b2RangeEl) {
            const minR = Math.min(...b2.map(s => s.roll_no ? parseInt(s.roll_no) : (b1.length + 1)));
            const maxR = Math.max(...b2.map(s => s.roll_no ? parseInt(s.roll_no) : students.length));
            b2RangeEl.innerText = `Roll ${minR} - ${maxR}`;
        }
    }

    function renderBatchSetupRoster() {
        const tbody = document.getElementById('batchSetupStudentTbody');
        if (!tbody) return;

        const students = window.labBatchSetupState.students;
        tbody.innerHTML = '';

        const badgeEl = document.getElementById('rosterTotalCountBadge');
        if (badgeEl) badgeEl.innerText = `${students.length} Students`;

        students.forEach((s, idx) => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-800/40 hover:bg-slate-900/50 transition";
            tr.id = `batchSetupRow_${s.reg_no}`;
            tr.setAttribute('data-batch', s.lab_batch);

            const isB1 = (s.lab_batch === '1');
            tr.innerHTML = `
                <td class="py-2.5 px-3 text-center font-bold font-mono ${isB1 ? 'text-indigo-400' : 'text-purple-400'}">${s.roll_no || (idx + 1)}</td>
                <td class="py-2.5 px-3 text-slate-400 font-mono text-[11px]">${s.reg_no}</td>
                <td class="py-2.5 px-3 font-medium text-slate-200">
                    <span class="text-white font-semibold">${s.name}</span>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold ${isB1 ? 'bg-indigo-950/70 text-indigo-300 border border-indigo-700/50' : 'bg-purple-950/70 text-purple-300 border border-purple-700/50'}">
                        <span class="w-1.5 h-1.5 rounded-full ${isB1 ? 'bg-indigo-400' : 'bg-purple-400'}"></span>
                        ${isB1 ? 'Batch 1' : 'Batch 2'}
                    </span>
                </td>
                <td class="py-2.5 px-3 text-center">
                    <div class="inline-flex rounded-lg p-0.5 bg-slate-900 border border-slate-800 shadow-sm">
                        <button type="button" onclick="toggleStudentLabBatch('${s.reg_no}', '1')" class="px-2.5 py-1 rounded text-[10.5px] font-bold transition cursor-pointer ${isB1 ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800'}">B1</button>
                        <button type="button" onclick="toggleStudentLabBatch('${s.reg_no}', '2')" class="px-2.5 py-1 rounded text-[10.5px] font-bold transition cursor-pointer ${!isB1 ? 'bg-purple-600 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800'}">B2</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        filterBatchSetupStudentList();
    }

    window.toggleStudentLabBatch = function(regNo, newBatch) {
        const student = window.labBatchSetupState.students.find(s => s.reg_no === regNo);
        if (student) {
            student.lab_batch = newBatch;
            renderBatchSetupRoster();
            updateBatchSetupCounters();
        }
    };

    window.filterBatchSetupRosterTab = function(tab) {
        window.activeRosterTab = tab;
        ['all', '1', '2'].forEach(t => {
            const btn = document.getElementById(`rosterTab_${t}`);
            if (btn) {
                if (t === tab) {
                    btn.classList.add('bg-indigo-600', 'text-white');
                    btn.classList.remove('text-slate-400');
                } else {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('text-slate-400');
                }
            }
        });
        filterBatchSetupStudentList();
    };

    window.filterBatchSetupStudentList = function() {
        const q = (document.getElementById('batchSetupStudentSearch').value || '').toLowerCase().trim();
        const tab = window.activeRosterTab || 'all';
        const rows = document.querySelectorAll('#batchSetupStudentTbody tr');
        let visibleCount = 0;

        rows.forEach(r => {
            const rowBatch = r.getAttribute('data-batch') || '';
            const text = r.innerText.toLowerCase();
            const matchesSearch = !q || text.includes(q);
            const matchesTab = (tab === 'all') || (rowBatch === tab);

            if (matchesSearch && matchesTab) {
                r.style.display = '';
                visibleCount++;
            } else {
                r.style.display = 'none';
            }
        });

        const counter = document.getElementById('rosterShowingCounter');
        if (counter) counter.innerText = `Showing ${visibleCount} students`;
    };

    window.saveLabBatchSetup = async function() {
        const state = window.labBatchSetupState;
        if (!state.subjectId) return;

        const btn = document.getElementById('btnSaveLabBatchSetup');
        const origHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Saving...`;
        }

        const applyAll = document.getElementById('batchSetupApplyAllCheckbox') ? document.getElementById('batchSetupApplyAllCheckbox').checked : true;

        const payload = {
            mode: state.mode,
            cutoff_roll: state.mode === 'split' ? state.cutoff : null,
            apply_to_classroom: applyAll,
            assignments: state.students.map(s => ({
                reg_no: s.reg_no,
                lab_batch: state.mode === 'full' ? '1' : s.lab_batch
            }))
        };

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const res = await fetch(`/api/classroom/${state.subjectId}/practical/batch-setup`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                closeLabBatchSetupModal();
                if (typeof state.onSavedCallback === 'function') {
                    state.onSavedCallback(data);
                } else {
                    window.location.reload();
                }
            } else {
                alert(data.message || "Failed to save batch division.");
            }
        } catch (err) {
            console.error("Error saving lab batch division:", err);
            alert("An error occurred while saving lab batch division.");
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }
    };

})();
</script>
