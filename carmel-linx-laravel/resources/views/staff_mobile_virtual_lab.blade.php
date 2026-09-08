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
            padding: 10px 12px;
            margin-bottom: 8px;
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

        select option {
            font-size: 0.70rem !important;
            padding: 4px 6px !important;
            background-color: #0f172a !important;
            color: #ffffff !important;
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
                <select id="selectedExpId" class="form-select form-select-sm bg-dark text-white border-secondary font-mono" style="width: auto; font-size: 0.70rem; padding-top: 3px; padding-bottom: 3px;" onchange="changeActiveExp(this.value)">
                    @foreach($experiments as $exp)
                        <option value="{{ $exp->id }}" style="font-size: 0.70rem;">Exp {{ $exp->experiment_no }}: {{ Str::limit($exp->title, 18) }}</option>
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
                            $expScore = ($expMark && $expMark['total'] !== null) ? $expMark['total'] : 0;
                            $graded = $student['graded_count'] ?? 0;
                            $totalExp = $student['total_exp_count'] ?? count($experiments);
                            $gradedBadgeClass = $graded === 0 ? 'bg-danger' : ($graded < $totalExp ? 'bg-warning text-dark' : 'bg-success');
                        @endphp
                        <div class="student-card student-row-exp" data-reg="{{ $student['reg_no'] }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <span class="badge badge-cyan font-mono me-1" style="font-size: 0.68rem;">Roll #{{ $student['roll_no'] ?? '-' }}</span>
                                    <button onclick="openStudentDetailMobile('{{ $student['reg_no'] }}')"
                                        class="text-white fw-bold border-0 bg-transparent p-0 text-decoration-underline" style="font-size: 0.80rem; cursor: pointer;">
                                        {{ $student['name'] }}
                                    </button>
                                    <small class="d-block text-slate-300 font-mono" style="font-size: 0.68rem; color: #cbd5e1 !important;">{{ !empty($student['sbte_reg_no']) ? $student['sbte_reg_no'] : $student['reg_no'] }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $gradedBadgeClass }} font-mono mb-1" style="font-size: 0.65rem;">{{ $graded }} / {{ $totalExp }} Graded</span>
                                    <span class="d-block font-mono text-cyan fw-bold text-exp-total-{{ $student['reg_no'] }}" style="font-size: 0.85rem; color: #38bdf8 !important;">
                                        {{ $expScore !== null ? number_format($expScore, 1) : '—' }} / 37.5
                                    </span>
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-2.5 py-0.5 mt-1 text-white fw-semibold" style="font-size: 0.70rem;" onclick="openGradingModal('{{ $student['reg_no'] }}')">
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
                                <button onclick="openStudentDetailMobile('{{ $student['reg_no'] }}')" class="text-white fw-bold border-0 bg-transparent p-0 text-decoration-underline text-start" style="font-size: 0.88rem; cursor: pointer;">{{ $student['name'] }}</button>
                                <small class="d-block text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ !empty($student['sbte_reg_no']) ? $student['sbte_reg_no'] : $student['reg_no'] }}</small>
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
                                <button onclick="openStudentDetailMobile('{{ $student['reg_no'] }}')" class="text-white fw-bold border-0 bg-transparent p-0 text-decoration-underline text-start" style="font-size: 0.88rem; cursor: pointer;">{{ $student['name'] }}</button>
                                <small class="d-block text-slate-300 font-mono" style="font-size: 0.74rem; color: #cbd5e1 !important;">{{ !empty($student['sbte_reg_no']) ? $student['sbte_reg_no'] : $student['reg_no'] }}</small>
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
                    <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">System calculated attendance score out of 15 (proportional to attendance %)</small>
                </div>
                <button class="btn btn-sm btn-success fw-bold rounded-pill px-3 text-dark" style="font-size: 0.75rem;" onclick="saveAllAttendanceMarks()">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Save Marks
                </button>
            </div>

            <div id="attendanceStudentList">
                @foreach($studentsData as $student)
                    @php $attPct = $student['att_pct']; @endphp
                    <div class="student-card mb-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <button onclick="openStudentDetailMobile('{{ $student['reg_no'] }}')" class="text-white fw-bold border-0 bg-transparent p-0 text-decoration-underline text-start d-block" style="font-size: 0.80rem; cursor: pointer;">{{ $student['name'] }}</button>
                                <small class="text-slate-300 font-mono" style="font-size: 0.68rem; color: #cbd5e1 !important;">{{ !empty($student['sbte_reg_no']) ? $student['sbte_reg_no'] : $student['reg_no'] }}</small>
                                <div class="mt-1 d-flex gap-1.5 flex-wrap align-items-center">
                                    <span class="badge bg-slate-800 text-cyan font-mono" style="font-size: 0.65rem; color: #38bdf8 !important;">
                                        {{ $student['att_pct'] }}% ({{ $student['att_present'] }}/{{ $student['att_total'] }})
                                    </span>
                                    <span class="badge {{ $attPct >= 90 ? 'bg-success' : ($attPct >= 75 ? 'bg-warning text-dark' : 'bg-danger') }} font-mono" style="font-size: 0.65rem;">
                                        Suggested: {{ $student['att_slab_mark'] }} / 15
                                    </span>
                                </div>
                            </div>
                            <div class="text-end" style="width: 95px;">
                                <label class="text-slate-200 d-block fw-semibold mb-0.5" style="font-size: 0.65rem; color: #e2e8f0 !important;">Override (/15)</label>
                                <input type="number" step="0.5" min="0" max="15" class="form-control form-control-sm bg-dark text-success font-mono fw-bold text-center border-secondary input-att-marks" style="font-size: 0.78rem;" data-reg="{{ $student['reg_no'] }}" value="{{ $student['attendance_marks'] }}">
                            </div>
                        </div>
                        <!-- Attendance Log Toggle -->
                        <button class="btn btn-sm btn-outline-secondary rounded-2 mt-2 w-100" style="font-size: 0.68rem; padding: 2px 6px;"
                            onclick="toggleMobileAttLog('{{ $student['reg_no'] }}', this)">
                            <i class="fa-solid fa-calendar-days me-1"></i>Show Attendance Log
                        </button>
                        <div id="attlog-{{ $student['reg_no'] }}" class="d-none mt-2 rounded-3 overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1);">
                            <table class="table table-sm table-dark mb-0" style="font-size: 0.67rem;">
                                <thead><tr class="text-info">
                                    <th class="py-1 px-1.5">Date</th>
                                    <th class="py-1 px-1.5 text-center">Period</th>
                                    <th class="py-1 px-1.5">Topic</th>
                                    <th class="py-1 px-1.5 text-center">Status</th>
                                </tr></thead>
                                <tbody id="attlog-body-{{ $student['reg_no'] }}">
                                    <tr><td colspan="4" class="text-center text-muted py-2">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TAB 5: CIA SUMMARY -->
        <div id="tab-summary" class="tab-panel">
            <div class="mb-3">
                <h6 class="text-white fw-bold mb-0"><i class="fa-solid fa-award text-warning me-1.5"></i>CIA Summary</h6>
                <small class="text-slate-300" style="font-size: 0.74rem; color: #cbd5e1 !important;">Consolidated Internal Assessment — tap name for details</small>
            </div>
            <div class="overflow-auto" style="border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.1);">
                <table class="table table-sm table-dark mb-0" style="font-size: 0.72rem; min-width: 520px;">
                    <thead><tr class="text-info text-center">
                        <th class="py-1 px-2 text-start">Student</th>
                        <th class="py-1 px-2">Exps</th>
                        <th class="py-1 px-2">Lab<br>/37.5</th>
                        <th class="py-1 px-2">OE<br>/7.5</th>
                        <th class="py-1 px-2">Tests<br>/15</th>
                        <th class="py-1 px-2">Att<br>/15</th>
                        <th class="py-1 px-2 text-warning">CIA<br>/75</th>
                    </tr></thead>
                    <tbody>
                        @foreach($studentsData as $student)
                        @php
                            $g = $student['graded_count'];
                            $t = $student['total_exp_count'];
                            $gClass = $g === 0 ? 'text-danger' : ($g < $t ? 'text-warning' : 'text-success');
                        @endphp
                        <tr>
                            <td class="py-1 px-2">
                                <button onclick="openStudentDetailMobile('{{ $student['reg_no'] }}')"
                                    class="text-white border-0 bg-transparent p-0 text-decoration-underline text-start fw-semibold" style="font-size: 0.72rem; cursor: pointer;">
                                    {{ $student['name'] }}
                                </button>
                                <small class="d-block text-muted font-mono">{{ !empty($student['sbte_reg_no']) ? $student['sbte_reg_no'] : $student['reg_no'] }}</small>
                            </td>
                            <td class="py-1 px-2 text-center font-mono fw-bold {{ $gClass }}">{{ $g }}/{{ $t }}</td>
                            <td class="py-1 px-2 text-center font-mono text-info">{{ number_format($student['avg_lab_work'], 1) }}</td>
                            <td class="py-1 px-2 text-center font-mono text-warning">{{ number_format($student['open_ended_marks'], 1) }}</td>
                            <td class="py-1 px-2 text-center font-mono text-purple" style="color: #c084fc !important;">{{ number_format($student['scaled_tests_15'], 1) }}</td>
                            <td class="py-1 px-2 text-center font-mono text-success">{{ $student['attendance_marks'] }}</td>
                            <td class="py-1 px-2 text-center font-mono fw-bold text-warning">{{ number_format($student['total_cia'], 1) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav" style="display: grid; grid-template-columns: repeat(5, 1fr);">
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
        <button class="nav-item-btn" onclick="switchMobileTab('summary', this)">
            <i class="fa-solid fa-award"></i>
            <span>Summary</span>
        </button>
    </div>


    <!-- Student Detail Modal (fullscreen on mobile) -->
    <div class="modal fade" id="studentDetailModalMobile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-lg">
            <div class="modal-content text-white" style="background-color: #0f172a !important; border: 1px solid rgba(255,255,255,0.15) !important;">
                <div class="modal-header py-2.5 px-3" style="border-bottom: 1px solid rgba(255,255,255,0.12) !important;">
                    <div>
                        <h6 class="modal-title fw-black text-white mb-0" id="mdetailName" style="font-size: 0.95rem;">Student</h6>
                        <small class="font-mono text-cyan fw-semibold" style="color: #38bdf8 !important;" id="mdetailReg"></small>
                        <span id="mdetailGraded" class="ms-2 badge bg-warning text-dark" style="font-size: 0.65rem;"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 overflow-auto">

                    <!-- Experiment cards -->
                    <p class="text-info fw-bold mb-2" style="font-size: 0.78rem;"><i class="fa-solid fa-vials me-1"></i>Lab Work — Experiment Marks</p>
                    <div id="mdetailExpCards"></div>

                    <!-- CIA Summary strip (Editable) -->
                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <p class="text-info fw-bold mb-0" style="font-size: 0.78rem;"><i class="fa-solid fa-award me-1"></i>CIA Summary & Evaluation (/75)</p>
                        <button type="button" onclick="saveStudentCiaSummaryMobile()" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3 py-0.5 shadow-sm" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Save CIA
                        </button>
                    </div>

                    <div class="row g-2 mb-3">
                        <!-- Open-Ended -->
                        <div class="col-6">
                            <div class="rounded-3 p-2" style="background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25);">
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 0.65rem;">
                                    <span class="text-warning fw-bold">1. Open-Ended</span>
                                    <span>/7.5</span>
                                </div>
                                <input type="number" step="0.5" min="0" max="7.5" id="minputOE" oninput="recalcMobileModalCIA()" class="form-control form-control-sm bg-dark text-warning font-mono fw-bold text-center border-secondary py-1" style="font-size: 0.82rem;">
                                <input type="text" id="minputOETopic" placeholder="Topic..." class="form-control form-control-sm bg-dark text-white border-secondary mt-1 py-0.5" style="font-size: 0.68rem;">
                            </div>
                        </div>

                        <!-- Tests -->
                        <div class="col-6">
                            <div class="rounded-3 p-2" style="background: rgba(192,132,252,0.1); border: 1px solid rgba(192,132,252,0.25);">
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 0.65rem;">
                                    <span class="fw-bold" style="color: #c084fc;">2. Tests</span>
                                    <span class="text-purple font-mono" id="mcalcTestScaled" style="color: #c084fc !important;">0/15</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <input type="number" step="0.5" min="0" max="40" id="minputT1" placeholder="T1" oninput="recalcMobileModalCIA()" class="form-control form-control-sm bg-dark text-info font-mono fw-bold text-center border-secondary py-1" style="font-size: 0.75rem;">
                                    <input type="number" step="0.5" min="0" max="40" id="minputT2" placeholder="T2" oninput="recalcMobileModalCIA()" class="form-control form-control-sm bg-dark text-info font-mono fw-bold text-center border-secondary py-1" style="font-size: 0.75rem;">
                                </div>
                                <div class="text-center text-muted font-mono mt-1" style="font-size: 0.62rem;">Avg: <span id="mcalcTestAvg" class="text-white fw-bold">0</span>/40</div>
                            </div>
                        </div>

                        <!-- Attendance -->
                        <div class="col-6">
                            <div class="rounded-3 p-2" style="background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25);">
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 0.65rem;">
                                    <span class="text-success fw-bold">3. Attendance</span>
                                    <span id="mdetailAttSuggested" class="text-muted">Sugg: 0</span>
                                </div>
                                <input type="number" step="0.5" min="0" max="15" id="minputAtt" oninput="recalcMobileModalCIA()" class="form-control form-control-sm bg-dark text-success font-mono fw-bold text-center border-secondary py-1" style="font-size: 0.82rem;">
                                <div class="text-center text-muted font-mono mt-1" style="font-size: 0.62rem;" id="mdetailAttStats">0% (0/0)</div>
                            </div>
                        </div>

                        <!-- Total CIA -->
                        <div class="col-6">
                            <div class="rounded-3 p-2 text-center" style="background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.3);">
                                <div class="text-info fw-bold" style="font-size: 0.65rem;">4. Total CIA (/75)</div>
                                <div class="text-white fw-black font-mono my-1" style="font-size: 1.15rem;" id="mdetailCIA">0.0</div>
                                <div class="text-muted" style="font-size: 0.6rem;">Lab: <span id="mdetailLabAvg" class="text-cyan fw-bold">0</span>/37.5</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer py-2 px-3 justify-content-between" style="border-top: 1px solid rgba(255,255,255,0.12) !important;">
                    <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="navigateMobileDetail(-1)">
                        <i class="fa-solid fa-chevron-left me-1"></i>Prev
                    </button>
                    <span class="text-muted font-mono" style="font-size: 0.72rem;" id="mdetailPos"></span>
                    <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="navigateMobileDetail(1)">
                        Next<i class="fa-solid fa-chevron-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
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
                    
                    <!-- Experiment & Evaluation Date Picker -->
                    <div class="mb-2.5 p-2.5 rounded-3" style="background-color: #1e293b !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-slate-300 mb-0 fw-bold d-flex align-items-center gap-1.5" style="font-size:0.78rem; color: #cbd5e1 !important;">
                                <i class="fa-regular fa-calendar-check text-cyan"></i> Experiment / Evaluation Date:
                            </label>
                            <span class="badge bg-dark text-cyan font-mono" style="font-size: 0.72rem; color: #38bdf8 !important;" id="modalExpBadge">Exp</span>
                        </div>
                        <input type="date" id="modalExpDate" class="form-control form-control-sm bg-dark text-white border-secondary font-mono fw-bold" style="background-color: #020617 !important; color: #ffffff !important; font-size: 0.82rem;" title="Evaluation / Conducted Date">
                    </div>

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
        let detailModalMobileObj = null;

        document.addEventListener('DOMContentLoaded', () => {
            gradingModalObj = new bootstrap.Modal(document.getElementById('gradingModal'));
            detailModalMobileObj = new bootstrap.Modal(document.getElementById('studentDetailModalMobile'));
        });

        function switchMobileTab(tabId, btn) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-item-btn').forEach(el => el.classList.remove('active'));
            
            const target = document.getElementById('tab-' + tabId);
            if (target) target.classList.add('active');
            if (btn) btn.classList.add('active');
        }

        function changeActiveExp(expId) {
            activeExpId = expId;
            // Refresh list total scores display for active experiment
            studentsData.forEach(st => {
                const mark = st.exp_marks ? st.exp_marks[expId] : null;
                const total = (mark && mark.total !== null) ? mark.total : 0;
                const el = document.querySelector(`.text-exp-total-${st.reg_no}`);
                if (el) el.innerText = `${total.toFixed(1)} / 37.5`;
            });
        }

        function openGradingModal(regNo) {
            currentStudentIdx = studentsData.findIndex(s => s.reg_no === regNo);
            if (currentStudentIdx === -1) return;

            const student = studentsData[currentStudentIdx];
            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = (student.sbte_reg_no && student.sbte_reg_no.trim() !== '') ? student.sbte_reg_no : student.reg_no;
            document.getElementById('modalRegNo').value = student.reg_no;

            const expMark = (student.exp_marks && activeExpId) ? student.exp_marks[activeExpId] : null;

            // Set experiment badge and date picker (autofilled with current date by default, or existing evaluation date)
            const curExp = experimentsData ? experimentsData.find(e => e.id == activeExpId) : null;
            const expBadge = document.getElementById('modalExpBadge');
            if (expBadge && curExp) {
                expBadge.innerText = `Exp ${curExp.experiment_no}`;
            }

            const dateInput = document.getElementById('modalExpDate');
            if (dateInput) {
                let defaultDate = '';
                if (expMark && expMark.evaluation_date) {
                    defaultDate = expMark.evaluation_date;
                } else if (student.exp_detail) {
                    const ed = student.exp_detail.find(e => e.exp_id == activeExpId);
                    if (ed && ed.evaluation_date) {
                        defaultDate = ed.evaluation_date;
                    }
                }
                if (!defaultDate && curExp && curExp.conducted_date) {
                    defaultDate = curExp.conducted_date;
                }
                if (!defaultDate) {
                    defaultDate = new Date().toISOString().split('T')[0];
                }
                dateInput.value = defaultDate;
            }

            const rough = expMark ? (expMark.rough_record ?? 0) : 0;
            const fair  = expMark ? (expMark.fair_record ?? 0) : 0;
            const obs   = expMark ? (expMark.obs_prep ?? expMark.prerequisites ?? 0) : 0;
            const proc  = expMark ? (expMark.proc_punct ?? expMark.work_done ?? 0) : 0;
            const viva  = expMark ? (expMark.viva ?? expMark.result ?? 0) : 0;

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
            const evalDate = document.getElementById('modalExpDate') ? document.getElementById('modalExpDate').value : new Date().toISOString().split('T')[0];

            const payload = {
                reg_no: regNo,
                experiments: {
                    [activeExpId]: {
                        rough_record: rough,
                        fair_record: fair,
                        obs_prep: obs,
                        proc_punct: proc,
                        output: viva,
                        date: evalDate,
                        evaluation_date: evalDate
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
                    const student = studentsData[currentStudentIdx];
                    if (!student.exp_marks) student.exp_marks = {};
                    
                    const wasGraded = student.exp_marks[activeExpId] && student.exp_marks[activeExpId].graded;
                    student.exp_marks[activeExpId] = {
                        rough_record: rough, fair_record: fair, obs_prep: obs, proc_punct: proc, viva: viva, total: total, graded: true, evaluation_date: evalDate
                    };
                    if (!wasGraded) {
                        student.graded_count = (student.graded_count || 0) + 1;
                    }

                    // Update exp_detail item
                    if (student.exp_detail) {
                        const ed = student.exp_detail.find(e => e.exp_id == activeExpId);
                        if (ed) {
                            ed.rough = rough; ed.fair = fair; ed.obs = obs; ed.proc = proc; ed.viva = viva; ed.total = total; ed.graded = true; ed.evaluation_date = evalDate;
                        }
                    }

                    // Recalculate student averages
                    let sumTotals = 0;
                    let countG = 0;
                    Object.values(student.exp_marks).forEach(m => {
                        if (m && m.graded && m.total !== null) {
                            sumTotals += m.total;
                            countG++;
                        }
                    });
                    const avgLab = countG > 0 ? (sumTotals / countG) : 0;
                    student.avg_lab_work = avgLab;
                    student.total_cia = avgLab + (student.open_ended_marks || 0) + (student.scaled_tests_15 || 0) + (parseFloat(student.attendance_marks) || 0);

                    const el = document.querySelector(`.text-exp-total-${regNo}`);
                    if (el) el.innerText = `${total.toFixed(1)} / 37.5`;

                    // If mobile detail modal is currently showing this student, update it
                    if (mobileDetailCurrentRegNo === regNo) {
                        renderMobileDetailExpCards(student);
                        renderMobileDetailCIASummary(student);
                    }

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

        // ══════════════════════════════════════════════════════════════════════
        // MOBILE STUDENT DETAIL MODAL & ATTENDANCE LOG
        // ══════════════════════════════════════════════════════════════════════

        let mobileDetailCurrentRegNo = null;

        function openStudentDetailMobile(regNo) {
            mobileDetailCurrentRegNo = regNo;
            const student = studentsData.find(s => s.reg_no === regNo);
            if (!student) return;

            const idx = studentsData.findIndex(s => s.reg_no === regNo);
            document.getElementById('mdetailName').innerText = student.name;
            document.getElementById('mdetailReg').innerText = (student.sbte_reg_no && student.sbte_reg_no.trim() !== '') ? student.sbte_reg_no : student.reg_no;
            const g = student.graded_count ?? 0;
            const t = student.total_exp_count ?? experimentsData.length;
            const badge = document.getElementById('mdetailGraded');
            badge.innerText = `${g} / ${t} Graded`;
            badge.className = `ms-2 badge ${g === 0 ? 'bg-danger' : (g < t ? 'bg-warning text-dark' : 'bg-success')}`;

            document.getElementById('mdetailPos').innerText = `${idx + 1} / ${studentsData.length}`;

            renderMobileDetailExpCards(student);
            renderMobileDetailCIASummary(student);

            detailModalMobileObj.show();
        }

        function navigateMobileDetail(dir) {
            const idx = studentsData.findIndex(s => s.reg_no === mobileDetailCurrentRegNo);
            if (idx === -1) return;
            let next = idx + dir;
            if (next < 0) next = studentsData.length - 1;
            if (next >= studentsData.length) next = 0;
            openStudentDetailMobile(studentsData[next].reg_no);
        }

        function renderMobileDetailExpCards(student) {
            const container = document.getElementById('mdetailExpCards');
            container.innerHTML = '';
            const details = student.exp_detail || [];

            if (details.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-2" style="font-size: 0.75rem;">No experiments found.</div>';
                return;
            }

            details.forEach(exp => {
                const isGraded = exp.graded;
                const card = document.createElement('div');
                card.className = 'rounded-3 p-2.5 mb-2';
                card.style.background = '#1e293b';
                card.style.border = isGraded ? '1px solid rgba(255,255,255,0.1)' : '1px solid rgba(245,158,11,0.3)';

                if (isGraded) {
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="badge bg-dark text-cyan font-mono" style="font-size: 0.7rem;">Exp ${exp.exp_no}</span>
                                <strong class="text-white ms-1" style="font-size: 0.8rem;">${exp.title || 'Experiment'}</strong>
                            </div>
                            <span class="badge bg-success" style="font-size: 0.65rem;">Graded</span>
                        </div>
                        <div class="row g-1 text-center font-mono my-1" style="font-size: 0.68rem;">
                            <div class="col"><span class="text-muted d-block">Rough</span><span class="text-cyan">${(exp.rough || 0).toFixed(1)}/5</span></div>
                            <div class="col"><span class="text-muted d-block">Fair</span><span class="text-cyan">${(exp.fair || 0).toFixed(1)}/7.5</span></div>
                            <div class="col"><span class="text-muted d-block">Obs</span><span class="text-cyan">${(exp.obs || 0).toFixed(1)}/7.5</span></div>
                            <div class="col"><span class="text-muted d-block">Proc</span><span class="text-cyan">${(exp.proc || 0).toFixed(1)}/7.5</span></div>
                            <div class="col"><span class="text-muted d-block">Viva</span><span class="text-cyan">${(exp.viva || 0).toFixed(1)}/10</span></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1 pt-1" style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <span class="font-mono text-cyan fw-bold" style="font-size: 0.8rem;">Total: ${(exp.total || 0).toFixed(1)} / 37.5</span>
                            <button class="btn btn-sm btn-outline-info rounded-pill px-2.5 py-0.5" style="font-size: 0.68rem;"
                                onclick="editExpFromMobileDetail('${student.reg_no}', ${exp.exp_id})">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </button>
                        </div>
                    `;
                } else {
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="badge bg-dark text-muted font-mono" style="font-size: 0.7rem;">Exp ${exp.exp_no}</span>
                                <strong class="text-white ms-1" style="font-size: 0.8rem;">${exp.title || 'Experiment'}</strong>
                            </div>
                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Pending</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted font-mono" style="font-size: 0.72rem;">Not graded yet</span>
                            <button class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-2.5 py-0.5" style="font-size: 0.68rem;"
                                onclick="editExpFromMobileDetail('${student.reg_no}', ${exp.exp_id})">
                                <i class="fa-solid fa-sliders me-1"></i>Grade Now
                            </button>
                        </div>
                    `;
                }
                container.appendChild(card);
            });
        }

        function renderMobileDetailCIASummary(student) {
            document.getElementById('minputOE').value = student.open_ended_marks > 0 ? student.open_ended_marks : '';
            document.getElementById('minputOETopic').value = student.open_ended_topic || '';
            document.getElementById('minputT1').value = student.score_t1 > 0 ? student.score_t1 : '';
            document.getElementById('minputT2').value = student.score_t2 > 0 ? student.score_t2 : '';
            document.getElementById('minputAtt').value = student.attendance_marks !== undefined ? student.attendance_marks : (student.att_slab_mark || 0);

            document.getElementById('mdetailAttSuggested').innerText = `Sugg: ${student.att_slab_mark || 0}`;
            document.getElementById('mdetailAttStats').innerText = `${student.att_pct}% (${student.att_present}/${student.att_total})`;
            document.getElementById('mdetailLabAvg').innerText = (student.avg_lab_work || 0).toFixed(1);

            recalcMobileModalCIA();
        }

        function recalcMobileModalCIA() {
            const oe = parseFloat(document.getElementById('minputOE').value) || 0;
            const t1 = parseFloat(document.getElementById('minputT1').value) || 0;
            const t2 = parseFloat(document.getElementById('minputT2').value) || 0;
            const att = parseFloat(document.getElementById('minputAtt').value) || 0;
            const lab = parseFloat(document.getElementById('mdetailLabAvg').innerText) || 0;

            const avgT = (t1 + t2) / 2;
            const scaledT = (avgT / 40) * 15;

            document.getElementById('mcalcTestAvg').innerText = avgT.toFixed(1);
            document.getElementById('mcalcTestScaled').innerText = `${scaledT.toFixed(1)}/15`;

            const total = lab + oe + scaledT + att;
            document.getElementById('mdetailCIA').innerText = total.toFixed(1);
        }

        async function saveStudentCiaSummaryMobile() {
            if (!mobileDetailCurrentRegNo) return;
            const regNo = mobileDetailCurrentRegNo;
            const student = studentsData.find(s => s.reg_no === regNo);

            const oe = parseFloat(document.getElementById('minputOE').value) || 0;
            const topic = document.getElementById('minputOETopic').value;
            const t1 = document.getElementById('minputT1').value !== '' ? parseFloat(document.getElementById('minputT1').value) : null;
            const t2 = document.getElementById('minputT2').value !== '' ? parseFloat(document.getElementById('minputT2').value) : null;
            const att = document.getElementById('minputAtt').value !== '' ? parseFloat(document.getElementById('minputAtt').value) : null;

            try {
                const res = await fetch(`/api/classroom/${subjectId}/practical/cia-summary`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        reg_no: regNo,
                        open_ended_mark: oe,
                        open_ended_topic: topic,
                        test1: t1,
                        test2: t2,
                        attendance_mark: att
                    })
                });
                const resp = await res.json();
                if (resp.success) {
                    const d = resp.data;

                    if (student) {
                        student.open_ended_marks = d.open_ended_mark;
                        student.open_ended_topic = d.open_ended_topic;
                        student.score_t1 = d.test1_score;
                        student.score_t2 = d.test2_score;
                        student.avg_test_40 = d.avg_test_40;
                        student.scaled_tests_15 = d.scaled_series_15;
                        student.attendance_marks = d.att_mark_15;
                        student.total_cia = d.total_cia;

                        // Also update inputs in Tab 2 (Open-Ended), Tab 3 (Tests), Tab 4 (Attendance) if rendered
                        const inpOpen = document.querySelector(`.input-open-score[data-reg="${regNo}"]`);
                        if (inpOpen) inpOpen.value = d.open_ended_mark;
                        const inpTopic = document.querySelector(`.input-open-topic[data-reg="${regNo}"]`);
                        if (inpTopic) inpTopic.value = d.open_ended_topic;

                        const inpT1 = document.querySelector(`.input-test1[data-reg="${regNo}"]`);
                        if (inpT1) inpT1.value = d.test1_score > 0 ? d.test1_score : '';
                        const inpT2 = document.querySelector(`.input-test2[data-reg="${regNo}"]`);
                        if (inpT2) inpT2.value = d.test2_score > 0 ? d.test2_score : '';

                        const inpAtt = document.querySelector(`.input-att-marks[data-reg="${regNo}"]`);
                        if (inpAtt) inpAtt.value = d.att_mark_15;
                    }

                    alert('CIA Summary saved successfully!');
                } else {
                    alert(resp.message || 'Failed to save CIA summary.');
                }
            } catch(e) {
                console.error(e);
                alert('Error saving CIA summary.');
            }
        }

        function editExpFromMobileDetail(regNo, expId) {
            detailModalMobileObj.hide();
            setTimeout(() => {
                changeActiveExp(expId);
                const sel = document.getElementById('selectedExpId');
                if (sel) sel.value = expId;
                openGradingModal(regNo);
            }, 300);
        }

        let mobileAttLogCache = null;

        async function toggleMobileAttLog(regNo, btn) {
            const container = document.getElementById(`attlog-${regNo}`);
            const tbody = document.getElementById(`attlog-body-${regNo}`);
            if (!container || !tbody) return;

            if (!container.classList.contains('d-none')) {
                container.classList.add('d-none');
                btn.innerHTML = '<i class="fa-solid fa-calendar-days me-1"></i>Show Attendance Log';
                return;
            }

            container.classList.remove('d-none');
            btn.innerHTML = '<i class="fa-solid fa-chevron-up me-1"></i>Hide Attendance Log';

            try {
                if (!mobileAttLogCache) {
                    const res = await fetch(`/api/classroom/${subjectId}/practical/attendance-log`);
                    const data = await res.json();
                    mobileAttLogCache = data.status === 'SUCCESS' ? data.logs : [];
                }

                tbody.innerHTML = '';
                if (mobileAttLogCache.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-2">No logs found.</td></tr>';
                    return;
                }

                mobileAttLogCache.forEach(log => {
                    const isPresent = log.present && log.present.includes(regNo);
                    let displayDate = log.date || '—';
                    if (log.date && log.date.includes('-')) {
                        const dParts = log.date.split('-');
                        if (dParts.length === 3 && dParts[0].length === 4) {
                            displayDate = `${dParts[2]}-${dParts[1]}-${dParts[0]}`;
                        }
                    }
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="py-1 px-1.5 font-mono" style="font-size:0.67rem;">${displayDate}</td>
                        <td class="py-1 px-1.5 text-center font-mono" style="font-size:0.67rem;">${log.period}</td>
                        <td class="py-1 px-1.5" style="font-size:0.67rem; line-height:1.25;">${log.topic}</td>
                        <td class="py-1 px-1.5 text-center">
                            ${isPresent 
                                ? '<span class="badge bg-success" style="font-size:0.60rem;">✓ Present</span>' 
                                : '<span class="badge bg-danger" style="font-size:0.60rem;">✗ Absent</span>'}
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch(e) {
                console.error(e);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-2">Failed to load log.</td></tr>';
            }
        }
    </script>
</body>
</html>
