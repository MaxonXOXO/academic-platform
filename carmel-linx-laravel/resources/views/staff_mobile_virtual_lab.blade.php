<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Virtual Lab - {{ $batchSubject->subject_code }}</title>
    
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: #0f172a;
            --border-color: rgba(255, 255, 255, 0.12);
            --accent-cyan: #06b6d4;
            --accent-purple: #8b5cf6;
            --accent-amber: #f59e0b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: #f8fafc;
            padding-bottom: 75px;
            user-select: none;
            -webkit-user-select: none;
        }

        .sticky-top-bar {
            background-color: rgba(9, 13, 22, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1040;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid var(--border-color);
            z-index: 1050;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
        }

        .nav-item-btn {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 0.7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-item-btn i {
            font-size: 1.1rem;
        }

        .nav-item-btn.active {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
        }

        .student-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
        }

        /* Custom range slider styling */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #334155;
            outline: none;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #38bdf8;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.6);
            border: 2px solid #ffffff;
        }

        .badge-cyan {
            background-color: rgba(6, 182, 212, 0.18);
            color: #38bdf8 !important;
            border: 1px solid rgba(6, 182, 212, 0.4);
        }

        .badge-purple {
            background-color: rgba(139, 92, 246, 0.18);
            color: #c084fc !important;
            border: 1px solid rgba(139, 92, 246, 0.4);
        }

        .badge-amber {
            background-color: rgba(245, 158, 11, 0.18);
            color: #fbbf24 !important;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }
    </style>
</head>
<body>

    <!-- Sticky Header -->
    <div class="sticky-top p-3 sticky-top-bar">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <a href="/staff/mobile" class="btn btn-sm btn-dark rounded-circle me-1" style="width: 34px; height: 34px; padding: 5px;">
                    <i class="fa-solid fa-arrow-left text-white"></i>
                </a>
                <div class="text-truncate">
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="badge badge-cyan font-mono" style="font-size: 0.65rem;">R2021 VIRTUAL LAB</span>
                        <span class="badge bg-slate-800 text-info font-mono" style="font-size: 0.65rem;">{{ $batchSubject->subject_code }}</span>
                    </div>
                    <h6 class="mb-0 text-white font-bold text-truncate" style="font-size: 0.9rem;">{{ $batchSubject->subject_name }}</h6>
                </div>
            </div>
            <span class="badge bg-secondary font-mono flex-shrink-0" style="font-size: 0.7rem;">{{ $batchSubject->classroom_id }}</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-fluid px-3 pt-3">

        <!-- TAB 1: LAB WORK EVALUATION -->
        <div id="tab-labwork" class="tab-panel active">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-white fw-bold mb-0"><i class="fa-solid fa-vials text-info me-1.5"></i>Lab Work Evaluation</h6>
                    <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">Continuous Day-to-Day Practical Marks</small>
                </div>
                @if(count($experiments) > 0)
                <select id="selectedExpId" class="form-select form-select-sm bg-dark text-white border-secondary font-mono" style="width: auto; font-size: 0.78rem;" onchange="changeActiveExp(this.value)">
                    @foreach($experiments as $exp)
                        <option value="{{ $exp->id }}">Exp {{ $exp->experiment_no }}: {{ Str::limit($exp->title, 18) }}</option>
                    @endforeach
                </select>
                @endif
            </div>

            @if(count($experiments) == 0)
                <div class="alert alert-warning text-center rounded-3 p-3" style="font-size: 0.82rem;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> No experiments configured for this subject yet.
                </div>
            @else
                <div id="labWorkStudentList">
                    @foreach($studentsData as $student)
                        @php
                            $firstExpId = $experiments->first()->id;
                            $expMark = $student['exp_marks'][$firstExpId] ?? null;
                            $expScore = $expMark ? $expMark['total'] : 0;
                        @endphp
                        <div class="student-card student-row-exp" data-reg="{{ $student['reg_no'] }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <span class="badge badge-cyan font-mono me-1">Roll #{{ $student['roll_no'] ?? '-' }}</span>
                                    <strong class="text-white" style="font-size: 0.88rem;">{{ $student['name'] }}</strong>
                                    <small class="d-block text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ $student['reg_no'] }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="d-block font-mono text-cyan fw-bold text-exp-total-{{ $student['reg_no'] }}" style="font-size: 0.95rem; color: #38bdf8 !important;">
                                        {{ number_format($expScore, 1) }} / 37.5
                                    </span>
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 mt-1 text-white fw-semibold" style="font-size: 0.74rem;" onclick="openGradingModal('{{ $student['reg_no'] }}')">
                                        <i class="fa-solid fa-sliders me-1 text-info"></i>Grade
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- TAB 2: OPEN-ENDED EVALUATION -->
        <div id="tab-openended" class="tab-panel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-white fw-bold mb-0"><i class="fa-solid fa-lightbulb text-warning me-1.5"></i>Open-Ended Evaluation</h6>
                    <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">Micro-project / Open-ended marks (Max 7.5M)</small>
                </div>
                <button class="btn btn-sm btn-warning fw-bold rounded-pill px-3 text-dark" style="font-size: 0.75rem;" onclick="saveAllOpenEnded()">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Save All
                </button>
            </div>

            <div id="openEndedStudentList">
                @foreach($studentsData as $student)
                    <div class="student-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="badge badge-amber font-mono me-1">Roll #{{ $student['roll_no'] ?? '-' }}</span>
                                <strong class="text-white" style="font-size: 0.88rem;">{{ $student['name'] }}</strong>
                                <small class="d-block text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ $student['reg_no'] }}</small>
                            </div>
                            <div class="text-end" style="width: 95px;">
                                <label class="text-slate-200 d-block fw-semibold mb-0.5" style="font-size: 0.68rem; color: #e2e8f0 !important;">Score (/7.5)</label>
                                <input type="number" step="0.5" min="0" max="7.5" class="form-control form-control-sm bg-dark text-warning font-mono fw-bold text-center border-secondary input-open-score" data-reg="{{ $student['reg_no'] }}" value="{{ $student['open_ended_marks'] }}">
                            </div>
                        </div>
                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary input-open-topic" style="font-size: 0.78rem;" placeholder="Project Topic / Title..." data-reg="{{ $student['reg_no'] }}" value="{{ $student['open_ended_topic'] }}">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TAB 3: LAB TESTS EVALUATION -->
        <div id="tab-labtests" class="tab-panel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-white fw-bold mb-0"><i class="fa-solid fa-pen-to-square text-purple me-1.5" style="color: #c084fc;"></i>Lab Test Evaluation</h6>
                    <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">Summative Series Tests (Test 1 & Test 2)</small>
                </div>
                <button class="btn btn-sm btn-purple text-white fw-bold rounded-pill px-3" style="background: #8b5cf6; font-size: 0.75rem;" onclick="saveAllBulkTests()">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Save Tests
                </button>
            </div>

            <div id="labTestStudentList">
                @foreach($studentsData as $student)
                    <div class="student-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <span class="badge badge-purple font-mono me-1">Roll #{{ $student['roll_no'] ?? '-' }}</span>
                                <strong class="text-white" style="font-size: 0.88rem;">{{ $student['name'] }}</strong>
                                <small class="d-block text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ $student['reg_no'] }}</small>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="text-slate-200 d-block fw-semibold mb-1" style="font-size: 0.7rem; color: #e2e8f0 !important;">Test 1 (CO1 & CO2)</label>
                                <input type="number" step="0.5" min="0" max="40" class="form-control form-control-sm bg-dark text-info font-mono fw-bold text-center border-secondary input-test1" data-reg="{{ $student['reg_no'] }}" value="{{ $student['score_t1'] > 0 ? $student['score_t1'] : '' }}" placeholder="Marks /40">
                            </div>
                            <div class="col-6">
                                <label class="text-slate-200 d-block fw-semibold mb-1" style="font-size: 0.7rem; color: #e2e8f0 !important;">Test 2 (CO3 & CO4)</label>
                                <input type="number" step="0.5" min="0" max="40" class="form-control form-control-sm bg-dark text-info font-mono fw-bold text-center border-secondary input-test2" data-reg="{{ $student['reg_no'] }}" value="{{ $student['score_t2'] > 0 ? $student['score_t2'] : '' }}" placeholder="Marks /40">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TAB 4: ATTENDANCE OVERRIDE -->
        <div id="tab-attendance" class="tab-panel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="text-white fw-bold mb-0"><i class="fa-solid fa-chart-line text-success me-1.5"></i>Lab Attendance Marks</h6>
                    <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">System calculated attendance score out of 15</small>
                </div>
                <button class="btn btn-sm btn-success fw-bold rounded-pill px-3 text-dark" style="font-size: 0.75rem;" onclick="saveAllAttendanceMarks()">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Save Marks
                </button>
            </div>

            <div id="attendanceStudentList">
                @foreach($studentsData as $student)
                    <div class="student-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $student['name'] }}</strong>
                                <small class="text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ $student['reg_no'] }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-slate-800 text-cyan font-mono" style="font-size: 0.7rem; color: #38bdf8 !important;">
                                        {{ $student['att_pct'] }}% Attended ({{ $student['att_present'] }}/{{ $student['att_total'] }})
                                    </span>
                                </div>
                            </div>
                            <div class="text-end" style="width: 100px;">
                                <label class="text-slate-200 d-block fw-semibold mb-0.5" style="font-size: 0.68rem; color: #e2e8f0 !important;">Marks (/15)</label>
                                <input type="number" step="0.5" min="0" max="15" class="form-control form-control-sm bg-dark text-success font-mono fw-bold text-center border-secondary input-att-marks" data-reg="{{ $student['reg_no'] }}" value="{{ $student['attendance_marks'] }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <button class="nav-item-btn active" onclick="switchMobileTab('labwork', this)">
            <i class="fa-solid fa-vials"></i>
            <span>Lab Work</span>
        </button>
        <button class="nav-item-btn" onclick="switchMobileTab('openended', this)">
            <i class="fa-solid fa-lightbulb"></i>
            <span>Open-Ended</span>
        </button>
        <button class="nav-item-btn" onclick="switchMobileTab('labtests', this)">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Lab Tests</span>
        </button>
        <button class="nav-item-btn" onclick="switchMobileTab('attendance', this)">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Attendance</span>
        </button>
    </div>

    <!-- Grading Modal (Slide-up Sheet) -->
    <div class="modal fade" id="gradingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-secondary text-white rounded-4 shadow-lg" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.15) !important;">
                <div class="modal-header border-secondary py-2.5 px-3" style="border-bottom-color: rgba(255, 255, 255, 0.12) !important;">
                    <div>
                        <h6 class="modal-title fw-bold text-white mb-0" id="modalStudentName" style="font-size: 0.95rem;">Student Name</h6>
                        <small class="text-cyan font-mono fw-semibold" style="font-size: 0.75rem; color: #38bdf8 !important;" id="modalStudentReg">Reg No</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" id="modalRegNo">
                    
                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-white fw-semibold" style="font-size: 0.82rem; color: #f8fafc !important;">1. Rough Record</span>
                            <span class="badge bg-dark text-cyan font-mono fw-bold fs-6 px-2 py-0.5" style="color: #38bdf8 !important;" id="val_rough">0</span>
                        </div>
                        <input type="range" min="0" max="5" step="0.5" id="range_rough" value="0" oninput="updateSliderVal('rough', this.value)">
                    </div>

                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-white fw-semibold" style="font-size: 0.82rem; color: #f8fafc !important;">2. Fair Record</span>
                            <span class="badge bg-dark text-cyan font-mono fw-bold fs-6 px-2 py-0.5" style="color: #38bdf8 !important;" id="val_fair">0</span>
                        </div>
                        <input type="range" min="0" max="7.5" step="0.5" id="range_fair" value="0" oninput="updateSliderVal('fair', this.value)">
                    </div>

                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-white fw-semibold" style="font-size: 0.82rem; color: #f8fafc !important;">3. Observation & Prep</span>
                            <span class="badge bg-dark text-cyan font-mono fw-bold fs-6 px-2 py-0.5" style="color: #38bdf8 !important;" id="val_obs">0</span>
                        </div>
                        <input type="range" min="0" max="7.5" step="0.5" id="range_obs" value="0" oninput="updateSliderVal('obs', this.value)">
                    </div>

                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-white fw-semibold" style="font-size: 0.82rem; color: #f8fafc !important;">4. Procedure & Punctuality</span>
                            <span class="badge bg-dark text-cyan font-mono fw-bold fs-6 px-2 py-0.5" style="color: #38bdf8 !important;" id="val_proc">0</span>
                        </div>
                        <input type="range" min="0" max="7.5" step="0.5" id="range_proc" value="0" oninput="updateSliderVal('proc', this.value)">
                    </div>

                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-white fw-semibold" style="font-size: 0.82rem; color: #f8fafc !important;">5. Viva / Output</span>
                            <span class="badge bg-dark text-cyan font-mono fw-bold fs-6 px-2 py-0.5" style="color: #38bdf8 !important;" id="val_viva">0</span>
                        </div>
                        <input type="range" min="0" max="10" step="0.5" id="range_viva" value="0" oninput="updateSliderVal('viva', this.value)">
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-2.5 rounded-3 mt-3" style="background-color: rgba(6, 182, 212, 0.15) !important; border: 1px solid rgba(6, 182, 212, 0.3) !important;">
                        <strong class="text-white" style="font-size: 0.9rem;">Total Mark (/37.5):</strong>
                        <span class="text-cyan font-mono fw-black fs-4" style="color: #38bdf8 !important;" id="modalTotalMark">0.0</span>
                    </div>
                </div>
                <div class="modal-footer border-secondary p-2.5 d-flex justify-content-between" style="border-top-color: rgba(255, 255, 255, 0.12) !important;">
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="navigateStudentModal(-1)">
                        <i class="fa-solid fa-chevron-left me-1"></i>Prev
                    </button>
                    <button type="button" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-4" onclick="saveExpMarkModal()">
                        <i class="fa-solid fa-check me-1"></i>Save & Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const subjectId = "{{ $subjectId }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const studentsData = @json($studentsData);
        const experimentsData = @json($experiments);

        let activeExpId = experimentsData.length > 0 ? experimentsData[0].id : null;
        let currentStudentIdx = 0;
        let gradingModalObj = null;

        document.addEventListener('DOMContentLoaded', () => {
            gradingModalObj = new bootstrap.Modal(document.getElementById('gradingModal'));
        });

        function switchMobileTab(tabId, btn) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-item-btn').forEach(el => el.classList.remove('active'));
            
            document.getElementById('tab-' + tabId).classList.add('active');
            btn.classList.add('active');
        }

        function changeActiveExp(expId) {
            activeExpId = expId;
            // Refresh list total scores display for active experiment
            studentsData.forEach(st => {
                const mark = st.exp_marks[expId];
                const total = mark ? mark.total : 0;
                const el = document.querySelector(`.text-exp-total-${st.reg_no}`);
                if (el) el.innerText = `${total.toFixed(1)} / 37.5`;
            });
        }

        function openGradingModal(regNo) {
            currentStudentIdx = studentsData.findIndex(s => s.reg_no === regNo);
            if (currentStudentIdx === -1) return;

            const student = studentsData[currentStudentIdx];
            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.reg_no;
            document.getElementById('modalRegNo').value = student.reg_no;

            const expMark = (student.exp_marks && activeExpId) ? student.exp_marks[activeExpId] : null;

            const rough = expMark ? expMark.rough_record : 0;
            const fair = expMark ? expMark.fair_record : 0;
            const obs = expMark ? expMark.prerequisites : 0;
            const proc = expMark ? expMark.work_done : 0;
            const viva = expMark ? expMark.result : 0;

            setSlider('rough', rough);
            setSlider('fair', fair);
            setSlider('obs', obs);
            setSlider('proc', proc);
            setSlider('viva', viva);

            calcModalTotal();
            gradingModalObj.show();
        }

        function setSlider(key, val) {
            document.getElementById(`range_${key}`).value = val;
            document.getElementById(`val_${key}`).innerText = val;
        }

        function updateSliderVal(key, val) {
            document.getElementById(`val_${key}`).innerText = val;
            calcModalTotal();
        }

        function calcModalTotal() {
            const rough = parseFloat(document.getElementById('range_rough').value) || 0;
            const fair = parseFloat(document.getElementById('range_fair').value) || 0;
            const obs = parseFloat(document.getElementById('range_obs').value) || 0;
            const proc = parseFloat(document.getElementById('range_proc').value) || 0;
            const viva = parseFloat(document.getElementById('range_viva').value) || 0;

            const total = rough + fair + obs + proc + viva;
            document.getElementById('modalTotalMark').innerText = total.toFixed(1);
        }

        async function saveExpMarkModal() {
            const regNo = document.getElementById('modalRegNo').value;
            if (!activeExpId || !regNo) return;

            const rough = parseFloat(document.getElementById('range_rough').value) || 0;
            const fair = parseFloat(document.getElementById('range_fair').value) || 0;
            const obs = parseFloat(document.getElementById('range_obs').value) || 0;
            const proc = parseFloat(document.getElementById('range_proc').value) || 0;
            const viva = parseFloat(document.getElementById('range_viva').value) || 0;

            const payload = {
                reg_no: regNo,
                experiments: {
                    [activeExpId]: {
                        rough_record: rough,
                        fair_record: fair,
                        obs_prep: obs,
                        proc_punct: proc,
                        output: viva
                    }
                }
            };

            try {
                const res = await fetch(`/api/classroom/${subjectId}/practical/evaluate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    // Update local state
                    const total = rough + fair + obs + proc + viva;
                    if (!studentsData[currentStudentIdx].exp_marks) studentsData[currentStudentIdx].exp_marks = {};
                    studentsData[currentStudentIdx].exp_marks[activeExpId] = {
                        rough_record: rough, fair_record: fair, prerequisites: obs, work_done: proc, result: viva, total: total
                    };
                    const el = document.querySelector(`.text-exp-total-${regNo}`);
                    if (el) el.innerText = `${total.toFixed(1)} / 37.5`;

                    // Navigate next if possible
                    if (currentStudentIdx < studentsData.length - 1) {
                        openGradingModal(studentsData[currentStudentIdx + 1].reg_no);
                    } else {
                        gradingModalObj.hide();
                    }
                } else {
                    alert(data.message || "Failed to save marks.");
                }
            } catch(e) {
                console.error(e);
                alert("Error saving experiment marks.");
            }
        }

        function navigateStudentModal(direction) {
            const nextIdx = currentStudentIdx + direction;
            if (nextIdx >= 0 && nextIdx < studentsData.length) {
                openGradingModal(studentsData[nextIdx].reg_no);
            }
        }

        async function saveAllOpenEnded() {
            const scores = document.querySelectorAll('.input-open-score');
            let promises = [];
            scores.forEach(inp => {
                const regNo = inp.getAttribute('data-reg');
                const val = inp.value !== '' ? parseFloat(inp.value) : null;
                const topicInput = document.querySelector(`.input-open-topic[data-reg="${regNo}"]`);
                const topic = topicInput ? topicInput.value : '';

                if (val !== null || topic !== '') {
                    promises.push(fetch(`/api/classroom/${subjectId}/practical/evaluate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ reg_no: regNo, micro_project: val, open_ended_project_topic: topic })
                    }));
                }
            });

            try {
                await Promise.all(promises);
                alert("Open-ended project evaluation saved successfully!");
            } catch(e) {
                console.error(e);
                alert("Error saving open-ended marks.");
            }
        }

        async function saveAllBulkTests() {
            let evals = [];
            studentsData.forEach(st => {
                const regNo = st.reg_no;
                const t1Inp = document.querySelector(`.input-test1[data-reg="${regNo}"]`);
                const t2Inp = document.querySelector(`.input-test2[data-reg="${regNo}"]`);

                evals.push({
                    reg_no: regNo,
                    series1: t1Inp && t1Inp.value !== '' ? parseFloat(t1Inp.value) : null,
                    series2: t2Inp && t2Inp.value !== '' ? parseFloat(t2Inp.value) : null
                });
            });

            try {
                const res = await fetch(`/api/classroom/${subjectId}/practical/evaluate-bulk`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ evaluations: evals })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert("Lab tests evaluation saved successfully!");
                } else {
                    alert(data.message || "Failed to save test marks.");
                }
            } catch(e) {
                console.error(e);
                alert("Error saving test marks.");
            }
        }

        async function saveAllAttendanceMarks() {
            const inputs = document.querySelectorAll('.input-att-marks');
            let promises = [];
            inputs.forEach(inp => {
                const regNo = inp.getAttribute('data-reg');
                const val = inp.value !== '' ? parseFloat(inp.value) : null;
                if (val !== null) {
                    promises.push(fetch(`/api/classroom/${subjectId}/practical/evaluate`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ reg_no: regNo, attendance_marks: val })
                    }));
                }
            });

            try {
                await Promise.all(promises);
                alert("Attendance marks saved successfully!");
            } catch(e) {
                console.error(e);
                alert("Error saving attendance marks.");
            }
        }
    </script>
</body>
</html>
