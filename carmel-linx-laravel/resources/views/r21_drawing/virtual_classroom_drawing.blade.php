<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }} - Virtual Drawing Hall (R-2021)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;        /* Slate 900 */
            --bg-secondary: #1e293b;      /* Slate 800 */
            --bg-card: #182234;           /* Refined Deep Slate Card */
            --bg-card-hover: #223048;     /* Subtle Card Hover */
            --bg-input: #0b1120;          /* Clean dark input background */
            --border-color: #2b3952;      /* Elegant Border */
            --border-light: rgba(148, 163, 184, 0.12);
            --accent-blue: #2563eb;       /* Executive Royal Blue */
            --accent-sky: #0284c7;        /* Sky Blue */
            --accent-indigo: #4f46e5;     /* Professional Indigo */
            --accent-emerald: #059669;    /* Professional Emerald */
            --accent-amber: #d97706;      /* Warm Amber */
            --accent-rose: #e11d48;       /* Subtle Rose */
            --text-main: #f8fafc;         /* Crisp White */
            --text-body: #e2e8f0;         /* Soft Gray */
            --text-muted: #94a3b8;        /* Muted Slate */
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-body);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            font-size: 0.85rem;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .navbar-custom {
            background-color: #111c30;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.12);
        }

        .stat-card {
            padding: 0.85rem 1.15rem;
            border-radius: 10px;
            background: linear-gradient(145deg, #172338 0%, #111b2d 100%);
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-card .stat-val {
            font-size: 1.25rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        .nav-tabs-custom {
            border-bottom: 1px solid var(--border-color);
            gap: 0.35rem;
        }

        .nav-tabs-custom .nav-link {
            color: var(--text-muted);
            border: 1px solid transparent;
            border-radius: 8px 8px 0 0;
            padding: 0.6rem 1.15rem;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.2s ease;
            background: transparent;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-tabs-custom .nav-link.active {
            color: #ffffff;
            background: var(--bg-card);
            border-color: var(--border-color) var(--border-color) transparent;
            border-top: 3px solid var(--accent-blue);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-custom {
            color: var(--text-body);
            border-color: var(--border-color);
            font-size: 0.77rem;
            line-height: 1.35;
        }

        .table-custom th {
            background-color: #131d2e;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.69rem;
            letter-spacing: 0.04em;
            padding: 0.55rem 0.55rem;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .table-custom td {
            background-color: #172236;
            border-color: var(--border-color);
            vertical-align: middle;
            padding: 0.4rem 0.55rem;
            font-weight: 500;
        }

        .table-custom tr:nth-child(even) td {
            background-color: #141e30;
        }

        .table-custom tr:hover td {
            background-color: #1c2b44;
        }

        .form-control-custom {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: #ffffff;
            border-radius: 6px;
            font-size: 0.82rem;
            padding: 0.35rem 0.55rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control-custom:focus {
            background-color: var(--bg-input);
            color: #ffffff;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
            outline: none;
        }

        .mark-input {
            width: 72px;
            text-align: center;
            font-weight: 700;
            font-size: 0.86rem;
            color: #93c5fd;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
        }
        .btn-success-custom:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: #ffffff;
        }

        /* Professional, Muted Status Badges */
        .badge-blue {
            background: rgba(37, 99, 235, 0.14);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .badge-indigo {
            background: rgba(79, 70, 229, 0.14);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }
        .badge-emerald {
            background: rgba(5, 150, 105, 0.14);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-amber {
            background: rgba(217, 119, 6, 0.14);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .badge-rose {
            background: rgba(225, 29, 72, 0.14);
            color: #fda4af;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }
        .badge-slate {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .autosave-badge {
            font-size: 0.72rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }
        .autosave-saved {
            background: rgba(5, 150, 105, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .autosave-saving {
            background: rgba(217, 119, 6, 0.15);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .autosave-error {
            background: rgba(225, 29, 72, 0.15);
            color: #fda4af;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        .sheet-pill {
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: #111a2c;
            padding: 0.45rem 0.95rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .sheet-pill:hover {
            background: #17243c;
            color: var(--text-main);
            border-color: #3b82f6;
        }
        .sheet-pill.active {
            background: rgba(37, 99, 235, 0.15);
            border-color: #3b82f6;
            color: #93c5fd;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>
<body>

    <!-- Professional Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-2.5 px-3">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <a href="/dashboard" class="btn btn-outline-secondary btn-sm px-2.5 py-1 text-light border-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
                </a>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-indigo px-2.5 py-0.5 fw-bold">Revision 2021</span>
                        <span class="text-white fw-bold fs-6">Virtual Drawing Classroom</span>
                    </div>
                    <div class="text-muted small" style="font-size: 0.78rem;">
                        [{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }} &bull; Semester {{ $classroom->current_semester ?? $batchSubject->semester }}
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2.5">
                <span id="globalAutoSaveIndicator" class="autosave-badge autosave-saved">
                    <i class="fa-solid fa-cloud-check"></i> All Changes Saved
                </span>
                <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="btn btn-sm btn-outline-light px-3 py-1">
                    <i class="fa-solid fa-print me-1.5 text-info"></i> Print CIA Marksheet
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-3 px-4">

        <!-- Executive Stat & Assessment Overview Banner -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">ENROLLED STUDENTS</div>
                        <div class="stat-val text-white">{{ $students->count() }} Students</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Batch: {{ $classroom->classroom_id ?? $batchSubject->classroom_id }}</div>
                    </div>
                    <div class="p-3 rounded-circle" style="background: rgba(59, 130, 246, 0.12); color: #60a5fa;">
                        <i class="fa-solid fa-users fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">FORMATIVE ASSESSMENT (40%)</div>
                        <div class="stat-val text-info">{{ $formativeMax }} Marks</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Min 2 Sheets / Module &bull; Timely (50%) + App. (50%)</div>
                    </div>
                    <div class="p-3 rounded-circle" style="background: rgba(2, 132, 199, 0.12); color: #38bdf8;">
                        <i class="fa-solid fa-pen-ruler fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">SUMMATIVE ASSESSMENT (40%)</div>
                        <div class="stat-val text-indigo" style="color: #a5b4fc;">{{ $summativeMax }} Marks</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Avg of 2 Tests &bull; Procedure, Final, Dimen, Neat</div>
                    </div>
                    <div class="p-3 rounded-circle" style="background: rgba(99, 102, 241, 0.12); color: #a5b4fc;">
                        <i class="fa-solid fa-pen-to-square fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">ATTENDANCE (20%) & TOTAL CIA</div>
                        <div class="stat-val text-emerald" style="color: #34d399;">{{ $attMax }}M Att + {{ $formativeMax + $summativeMax }}M = {{ $ciaMax }}M CIA</div>
                        <div class="small text-muted" style="font-size: 0.72rem;">Attendance excluded from direct CO attainment</div>
                    </div>
                    <div class="p-3 rounded-circle" style="background: rgba(5, 150, 105, 0.12); color: #34d399;">
                        <i class="fa-solid fa-chart-pie fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-3" id="r21DrawingTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tab-formative-link" data-bs-toggle="tab" data-bs-target="#tab-formative" type="button">
                    <i class="fa-solid fa-pen-ruler me-1.5 text-info"></i>Formative Assessment (Sheets - 40% / {{ $formativeMax }}M)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-summative-link" data-bs-toggle="tab" data-bs-target="#tab-summative" type="button">
                    <i class="fa-solid fa-clipboard-check me-1.5" style="color: #a5b4fc;"></i>Summative Tests (Avg 2 Tests - 40% / {{ $summativeMax }}M)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-attendance-link" data-bs-toggle="tab" data-bs-target="#tab-attendance" type="button">
                    <i class="fa-solid fa-user-check me-1.5 text-emerald" style="color: #34d399;"></i>Attendance & Performance (20% / {{ $attMax }}M)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-cia-link" data-bs-toggle="tab" data-bs-target="#tab-cia" type="button">
                    <i class="fa-solid fa-award me-1.5" style="color: #fbbf24;"></i>Consolidated CIA & Attainment ({{ $ciaMax }}M)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-lessonplan-link" data-bs-toggle="tab" data-bs-target="#tab-lessonplan" type="button">
                    <i class="fa-solid fa-calendar-days me-1.5 text-primary"></i>Lesson Plan (60 Hours)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-syllabus-link" data-bs-toggle="tab" data-bs-target="#tab-syllabus" type="button">
                    <i class="fa-solid fa-file-pdf me-1.5" style="color: #f43f5e;"></i>Syllabus, COs & Matrix
                </button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="r21DrawingTabContent">

            <!-- TAB 1: FORMATIVE ASSESSMENT (DRAWING SHEETS) -->
            <div class="tab-pane fade show active" id="tab-formative" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-pen-ruler me-2 text-info"></i>Formative Continuous Evaluation: Drawing Sheets</h5>
                            <p class="text-muted small mb-0">
                                Continuous evaluation of drawing sheets (minimum two sheets evaluated per module). Rubric: <strong>Timely Completion (50%)</strong> &amp; <strong>Appearance and Organization (50%)</strong>.
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/sheets" target="_blank" class="btn btn-outline-light btn-sm">
                                <i class="fa-solid fa-print me-1"></i> Print Sheet Register
                            </a>
                            <button type="button" class="btn btn-primary-custom btn-sm px-3" onclick="saveActiveSheetMarks()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Sheet Marks
                            </button>
                        </div>
                    </div>

                    <!-- Sheet Selector Horizontal Pills -->
                    <div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-3 border-bottom border-secondary">
                        <span class="text-muted small fw-bold text-uppercase me-2"><i class="fa-solid fa-layer-group me-1"></i>Sheets:</span>
                        @foreach($drawingCourseFile->parsed_sheets ?? [] as $index => $sheet)
                            <div class="sheet-pill {{ $index === 0 ? 'active' : '' }}" 
                                 onclick="switchSheet('{{ $sheet['sheet_no'] }}', this)" 
                                 data-sheet-no="{{ $sheet['sheet_no'] }}"
                                 data-sheet-title="{{ $sheet['title'] ?? '' }}"
                                 data-sheet-module="{{ $sheet['module'] ?? '' }}"
                                 data-sheet-co="{{ $sheet['co_id'] ?? '' }}">
                                <span class="badge {{ str_contains($sheet['module'] ?? '', '1') ? 'badge-blue' : (str_contains($sheet['module'] ?? '', '2') ? 'badge-indigo' : (str_contains($sheet['module'] ?? '', '3') ? 'badge-amber' : 'badge-emerald')) }} me-1.5">{{ $sheet['module'] ?? 'Mod' }}</span>
                                {{ $sheet['sheet_no'] }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Selected Sheet Info Banner -->
                    <div class="p-2.5 rounded-3 mb-3 d-flex align-items-center justify-content-between" style="background: #111a2a; border: 1px solid var(--border-color);">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge badge-blue px-2.5 py-1.5 fw-bold fs-6" id="activeSheetPillLabel">Sheet 1</span>
                            <div>
                                <div class="fw-bold text-white fs-6" id="activeSheetTitle">Lettering, Numbering & Dimensioning Practice</div>
                                <div class="text-muted small" id="activeSheetMeta">Module 1 &bull; Mapped to Outcome: <strong class="text-info">CO1</strong> &bull; Max Score: 100 (Timely 50 + Appearance 50)</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small">Auto-Save:</span>
                            <span id="sheetSaveStatus" class="autosave-badge autosave-saved ms-1"><i class="fa-solid fa-check"></i> Ready</span>
                        </div>
                    </div>

                    <!-- Sheet Marks Table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-custom table-bordered align-middle text-center mb-0" id="sheetMarksTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th style="width: 110px;">Roll No</th>
                                    <th style="width: 140px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th style="width: 135px;" class="text-info">Timely Completion<br><small class="text-muted font-monospace">(Max 50)</small></th>
                                    <th style="width: 155px;" class="text-info">Appearance & Org<br><small class="text-muted font-monospace">(Max 50)</small></th>
                                    <th style="width: 110px;" class="text-warning">Total Score<br><small class="text-muted font-monospace">(Max 100)</small></th>
                                    <th style="width: 85px;">Absent</th>
                                    <th class="text-start" style="width: 220px;">Faculty Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="sheetTableBody">
                                @foreach($students as $idx => $student)
                                    @php
                                        $eval = $studentResults[$idx]['sheets_detail']['Sheet 1'] ?? null;
                                        $timely = $eval ? floatval($eval->timely_completion) : '';
                                        $appearance = $eval ? floatval($eval->appearance_organization) : '';
                                        $total = ($eval && !$eval->is_absent) ? floatval($eval->total_score_100) : 0;
                                        $isAbsent = $eval ? $eval->is_absent : false;
                                    @endphp
                                    <tr data-reg-no="{{ $student->reg_no }}">
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="fw-bold">{{ $student->roll_no ?? '-' }}</td>
                                        <td class="text-muted font-monospace">{{ $student->reg_no }}</td>
                                        <td class="text-start fw-semibold text-white">{{ $student->name }}</td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="50" 
                                                   class="form-control form-control-custom mark-input mx-auto sheet-timely" 
                                                   value="{{ $timely }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateSheetRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="50" 
                                                   class="form-control form-control-custom mark-input mx-auto sheet-appearance" 
                                                   value="{{ $appearance }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateSheetRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <span class="badge badge-amber fs-6 px-2.5 py-1 row-total-badge">{{ $isAbsent ? 'ABS' : $total }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input sheet-absent" 
                                                   {{ $isAbsent ? 'checked' : '' }} 
                                                   onchange="toggleSheetAbsent(this)">
                                        </td>
                                        <td class="text-start">
                                            <input type="text" class="form-control form-control-custom form-control-sm sheet-remarks" 
                                                   placeholder="Good / Completed" 
                                                   value="{{ $eval->remarks ?? '' }}" 
                                                   oninput="triggerSheetDebouncedSave()"
                                                   onchange="triggerSheetDebouncedSave()">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Save Bar -->
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                        <div class="text-muted small">
                            <i class="fa-solid fa-keyboard me-1 text-info"></i> Keyboard Navigation: Press <strong>Enter</strong> or <strong>Arrow Keys</strong> to rapidly move between cells. Changes auto-save automatically.
                        </div>
                        <button type="button" class="btn btn-primary-custom btn-sm px-4 fw-bold" onclick="saveActiveSheetMarks()">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Save Sheet Marks
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB 2: SUMMATIVE ASSESSMENT (SERIES TESTS) -->
            <div class="tab-pane fade" id="tab-summative" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-clipboard-check me-2" style="color: #a5b4fc;"></i>Summative Assessment: Written Series Tests (40% Weightage)</h5>
                            <p class="text-muted small mb-0">
                                Average of two tests. Evaluation criteria: <strong>Procedure of drawing (40%)</strong>, <strong>Final drawing (30%)</strong>, <strong>Dimensioning (20%)</strong>, and <strong>Neatness of drawing (10%)</strong>.
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/tests" target="_blank" class="btn btn-outline-light btn-sm">
                                <i class="fa-solid fa-print me-1"></i> Print Summative Register
                            </a>
                            <button type="button" class="btn btn-primary-custom btn-sm px-3" onclick="saveActiveTestMarks()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Test Marks
                            </button>
                        </div>
                    </div>

                    <!-- Test Selector Tabs (Test 1 vs Test 2) -->
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 mb-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active px-3.5 py-1.5 fw-bold" id="btnSelectTest1" onclick="switchSummativeTest('Test 1')">
                                <i class="fa-solid fa-file-pen me-1.5"></i> Summative Test 1 (Modules I & II)
                            </button>
                            <button type="button" class="btn btn-outline-primary px-3.5 py-1.5 fw-bold" id="btnSelectTest2" onclick="switchSummativeTest('Test 2')">
                                <i class="fa-solid fa-file-pen me-1.5"></i> Summative Test 2 (Modules III & IV)
                            </button>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small">Auto-Save:</span>
                            <span id="testSaveStatus" class="autosave-badge autosave-saved ms-1"><i class="fa-solid fa-check"></i> Ready</span>
                        </div>
                    </div>

                    <!-- Summative Marks Entry Table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-custom table-bordered align-middle text-center mb-0" id="summativeMarksTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th style="width: 100px;">Roll No</th>
                                    <th style="width: 130px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th style="width: 110px;" class="text-info">Procedure (40%)<br><small class="text-muted font-monospace">Max 40</small></th>
                                    <th style="width: 110px;" style="color: #a5b4fc;">Final Drawing (30%)<br><small class="text-muted font-monospace">Max 30</small></th>
                                    <th style="width: 110px;" class="text-emerald" style="color: #34d399;">Dimensioning (20%)<br><small class="text-muted font-monospace">Max 20</small></th>
                                    <th style="width: 100px;" class="text-amber">Neatness (10%)<br><small class="text-muted font-monospace">Max 10</small></th>
                                    <th style="width: 95px;" class="text-white">Total (100M)<br><small class="text-muted">Sum</small></th>
                                    <th style="width: 75px;">Absent</th>
                                    <th class="text-start" style="width: 180px;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                @foreach($students as $idx => $student)
                                    @php
                                        $t1 = $studentResults[$idx]['t1_detail'] ?? null;
                                        $p = $t1 ? floatval($t1->procedure_drawing) : '';
                                        $f = $t1 ? floatval($t1->final_drawing) : '';
                                        $d = $t1 ? floatval($t1->dimensioning) : '';
                                        $n = $t1 ? floatval($t1->neatness) : '';
                                        $tot = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : 0;
                                        $abs = $t1 ? $t1->is_absent : false;
                                    @endphp
                                    <tr data-reg-no="{{ $student->reg_no }}">
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="fw-bold">{{ $student->roll_no ?? '-' }}</td>
                                        <td class="text-muted font-monospace">{{ $student->reg_no }}</td>
                                        <td class="text-start fw-semibold text-white">{{ $student->name }}</td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="40" 
                                                   class="form-control form-control-custom mark-input mx-auto test-p" 
                                                   value="{{ $p }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateTestRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="30" 
                                                   class="form-control form-control-custom mark-input mx-auto test-f" 
                                                   value="{{ $f }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateTestRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20" 
                                                   class="form-control form-control-custom mark-input mx-auto test-d" 
                                                   value="{{ $d }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateTestRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="10" 
                                                   class="form-control form-control-custom mark-input mx-auto test-n" 
                                                   value="{{ $n }}" 
                                                   data-reg="{{ $student->reg_no }}" 
                                                   oninput="calculateTestRowTotal(this)" 
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <span class="badge badge-indigo fs-6 px-2.5 py-1 test-total-badge">{{ $abs ? 'ABS' : $tot }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input test-absent" 
                                                   {{ $abs ? 'checked' : '' }} 
                                                   onchange="toggleTestAbsent(this)">
                                        </td>
                                        <td class="text-start">
                                            <input type="text" class="form-control form-control-custom form-control-sm test-remarks" 
                                                   placeholder="Remarks" 
                                                   value="{{ $t1->remarks ?? '' }}" 
                                                   oninput="triggerTestDebouncedSave()"
                                                   onchange="triggerTestDebouncedSave()">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Save Bar -->
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                        <div class="text-muted small">
                            <i class="fa-solid fa-calculator me-1 text-primary"></i> Summative CIA Mark will be computed as the <strong>Average of Test 1 and Test 2</strong> scaled to 40% of CIA ({{ $summativeMax }}M).
                        </div>
                        <button type="button" class="btn btn-primary-custom btn-sm px-4 fw-bold" onclick="saveActiveTestMarks()">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Save Test Marks
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ATTENDANCE & PERFORMANCE (20% WEIGHTAGE) -->
            <div class="tab-pane fade" id="tab-attendance" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-user-check me-2 text-emerald" style="color: #34d399;"></i>Attendance & Performance in Drawing Class (20% / {{ $attMax }}M)</h5>
                            <p class="text-muted small mb-0">
                                Attendance and performance in the drawing class evaluated for 20% of the internal marks.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end">
                                <span class="text-muted small">Auto-Save:</span>
                                <span id="attSaveStatus" class="autosave-badge autosave-saved ms-1"><i class="fa-solid fa-check"></i> Ready</span>
                            </div>
                            <button type="button" class="btn btn-success-custom btn-sm px-3" onclick="saveAttendanceMarks()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Attendance Marks
                            </button>
                        </div>
                    </div>

                    <!-- Important Compliance Alert Box -->
                    <div class="p-3 rounded-3 mb-3" style="background: rgba(217, 119, 6, 0.08); border: 1px solid rgba(217, 119, 6, 0.25);">
                        <div class="d-flex align-items-center gap-2 text-amber fw-bold mb-1" style="color: #fcd34d;">
                            <i class="fa-solid fa-circle-info"></i> Attendance Assessment Policy:
                        </div>
                        <div class="text-light small">
                            Attendance marks contribute 20% to the continuous internal evaluation (CIA) and are strictly excluded from direct Course Outcome (CO) attainment calculations.
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <div class="table-responsive mb-3">
                        <table class="table table-custom table-bordered align-middle text-center mb-0" id="attTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th style="width: 110px;">Roll No</th>
                                    <th style="width: 140px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th style="width: 140px;" class="text-info">Attendance %</th>
                                    <th style="width: 150px;" style="color: #34d399;">Computed Mark ({{ $attMax }}M)</th>
                                    <th style="width: 160px;" class="text-warning">Override Mark ({{ $attMax }}M)</th>
                                    <th style="width: 150px;" class="text-white">Final Attendance Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $idx => $student)
                                    @php
                                        $res = $studentResults[$idx];
                                    @endphp
                                    <tr data-reg-no="{{ $student->reg_no }}">
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="fw-bold">{{ $student->roll_no ?? '-' }}</td>
                                        <td class="text-muted font-monospace">{{ $student->reg_no }}</td>
                                        <td class="text-start fw-semibold text-white">{{ $student->name }}</td>
                                        <td>
                                            <span class="badge {{ $res['att_percentage'] >= 75 ? 'badge-emerald' : 'badge-rose' }} fs-6 px-2.5 py-1">
                                                {{ $res['att_percentage'] }}%
                                            </span>
                                        </td>
                                        <td class="fw-bold" style="color: #34d399;">
                                            {{ $res['calc_att_marks'] }} / {{ $attMax }}
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="{{ $attMax }}" 
                                                   class="form-control form-control-custom mark-input mx-auto att-override" 
                                                   value="{{ $res['att_marks'] != $res['calc_att_marks'] ? $res['att_marks'] : '' }}" 
                                                   placeholder="{{ $res['calc_att_marks'] }}" 
                                                   data-calc="{{ $res['calc_att_marks'] }}"
                                                   data-reg="{{ $student->reg_no }}" 
                                                   data-att-pct="{{ $res['att_percentage'] }}"
                                                   oninput="updateFinalAttCell(this)"
                                                   onkeydown="handleGridNavigation(event, this)">
                                        </td>
                                        <td>
                                            <span class="badge badge-blue fs-6 px-2.5 py-1 final-att-badge">
                                                {{ $res['att_marks'] }} / {{ $attMax }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Save Bar -->
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                        <div class="text-muted small">
                            Leave the override field empty to automatically adopt the calculated percentage attendance mark.
                        </div>
                        <button type="button" class="btn btn-success-custom btn-sm px-4 fw-bold" onclick="saveAttendanceMarks()">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Save Attendance Marks
                        </button>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CONSOLIDATED CIA & CO ATTAINMENT (50M) -->
            <div class="tab-pane fade" id="tab-cia" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-award me-2" style="color: #fbbf24;"></i>Consolidated CIA Mark Register & CO Attainment (50 Marks)</h5>
                            <p class="text-muted small mb-0">
                                Scheme: Formative Sheets (40% / {{ $formativeMax }}M) + Summative Tests (40% / {{ $summativeMax }}M) + Attendance (20% / {{ $attMax }}M) = Total CIA ({{ $ciaMax }}M).
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="btn btn-primary-custom btn-sm px-3">
                                <i class="fa-solid fa-print me-1"></i> Print Formal CIA Sheet
                            </a>
                        </div>
                    </div>

                    <!-- Consolidated Register Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-custom table-bordered align-middle text-center mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th style="width: 100px;">Roll No</th>
                                    <th style="width: 130px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th class="text-info">Formative (40%)<br><small class="text-muted">Sheets ({{ $formativeMax }}M)</small></th>
                                    <th style="color: #a5b4fc;">Summative (40%)<br><small class="text-muted">Avg Tests ({{ $summativeMax }}M)</small></th>
                                    <th style="color: #34d399;">Attendance (20%)<br><small class="text-muted">{{ $attMax }}M</small></th>
                                    <th class="text-warning">Total CIA<br><small class="text-muted">{{ $ciaMax }}M</small></th>
                                    <th style="width: 110px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $idx => $student)
                                    @php
                                        $res = $studentResults[$idx];
                                    @endphp
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td class="fw-bold">{{ $student->roll_no ?? '-' }}</td>
                                        <td class="text-muted font-monospace">{{ $student->reg_no }}</td>
                                        <td class="text-start fw-semibold text-white">{{ $student->name }}</td>
                                        <td class="fw-bold text-info">{{ $res['formative_mark'] }}</td>
                                        <td class="fw-bold" style="color: #a5b4fc;">{{ $res['summative_mark'] }}</td>
                                        <td class="fw-bold" style="color: #34d399;">{{ $res['att_marks'] }}</td>
                                        <td>
                                            <span class="badge badge-amber fs-6 px-2.5 py-1">{{ $res['total_cia'] }} / {{ $ciaMax }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $res['is_pass'] ? 'badge-emerald' : 'badge-rose' }} px-2 py-1">
                                                {{ $res['is_pass'] ? 'Eligible' : 'Needs Improvement' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- CO Attainment Matrix Preview (Excluding Attendance) -->
                    <div class="glass-card p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-white mb-0"><i class="fa-solid fa-bullseye me-2 text-info"></i>Course Outcome Direct Attainment (Excluding Attendance)</h6>
                            <span class="badge badge-blue">Target Threshold: 60%</span>
                        </div>
                        <div class="row g-3">
                            @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coKey)
                                <div class="col-md-3">
                                    <div class="p-3 rounded-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-indigo fw-bold">{{ $coKey }}</span>
                                            <span class="badge badge-emerald">Level 3</span>
                                        </div>
                                        <div class="text-white fw-bold fs-5 mb-1">86.4%</div>
                                        <div class="text-muted small">Target Attainment Met</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: LESSON PLAN (60 HOURS) -->
            <div class="tab-pane fade" id="tab-lessonplan" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Drawing Hall Course Lesson Plan (60 Hours)</h5>
                            <p class="text-muted small mb-0">Structured drawing hall practical sessions aligned with course modules and drawing sheets.</p>
                        </div>
                        <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="btn btn-outline-light btn-sm">
                            <i class="fa-solid fa-print me-1"></i> Print Lesson Plan
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Day</th>
                                    <th style="width: 100px;">Module</th>
                                    <th>Topics & Drawing Exercises</th>
                                    <th style="width: 70px;">Hours</th>
                                    <th style="width: 80px;">CO</th>
                                    <th style="width: 90px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lessonPlans as $plan)
                                <tr>
                                    <td class="fw-bold text-center">{{ $plan->day_no }}</td>
                                    <td><span class="badge badge-blue">{{ $plan->remarks ?? 'Mod' }}</span></td>
                                    <td class="text-white">{{ $plan->topic_content }}</td>
                                    <td class="text-center font-monospace">{{ $plan->allocated_hours ?? 2 }}</td>
                                    <td class="text-center"><span class="badge badge-indigo">{{ $plan->co_id }}</span></td>
                                    <td class="text-center"><span class="badge badge-emerald">{{ $plan->status ?? 'Planned' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No lesson plan generated yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: SYLLABUS, COS & MATRIX -->
            <div class="tab-pane fade" id="tab-syllabus" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3 text-white"><i class="fa-solid fa-cloud-arrow-up me-2 text-info"></i>Upload Drawing Syllabus PDF</h5>
                            <form id="uploadSyllabusForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Select Syllabus PDF Document</label>
                                    <input type="file" class="form-control form-control-custom" name="syllabus_file" accept=".pdf" required>
                                </div>
                                <button type="submit" class="btn btn-primary-custom w-100 py-2" id="uploadBtn">
                                    <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Upload Syllabus PDF
                                </button>
                            </form>
                            @if(!empty($drawingCourseFile->syllabus_pdf_path))
                            <div class="mt-3">
                                <a href="/storage/{{ str_replace('/storage/', '', $drawingCourseFile->syllabus_pdf_path) }}" target="_blank" class="btn btn-outline-info btn-sm w-100 fw-bold py-2">
                                    <i class="fa-solid fa-file-pdf me-1.5 text-danger"></i> View Uploaded PDF
                                </a>
                            </div>
                            @endif
                        </div>

                        <div class="glass-card p-4 mt-3">
                            <h6 class="fw-bold text-white mb-2"><i class="fa-solid fa-book-open me-2 text-warning"></i>Reference Textbooks</h6>
                            <ul class="list-unstyled mb-0 small text-muted">
                                @foreach($drawingCourseFile->parsed_textbooks ?? [] as $book)
                                    <li class="mb-2"><i class="fa-solid fa-angle-right text-primary me-1.5"></i> {{ $book }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="glass-card p-4 mb-4">
                            <h5 class="fw-bold mb-3 text-white"><i class="fa-solid fa-bullseye me-2 text-info"></i>Course Outcomes (COs)</h5>
                            <div class="row g-3">
                                @foreach($drawingCourseFile->parsed_cos ?? [] as $co)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background: #111a2a; border: 1px solid var(--border-color);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-blue fw-bold">{{ $co['id'] }}</span>
                                            <span class="badge badge-indigo">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                                        </div>
                                        <p class="mb-0 text-white font-medium small">{{ $co['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CO-PO Matrix -->
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3 text-white"><i class="fa-solid fa-table-cells me-2 text-warning"></i>CO-PO Articulation Matrix</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            @for($p=1; $p<=10; $p++) <th>PO{{ $p }}</th> @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            @for($p=1; $p<=10; $p++)
                                                <td class="text-white">{{ $drawingCourseFile->parsed_copo['mappings'][$coTag]['PO'.$p] ?? '-' }}</td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts: Live Calculation, Debounced Autosave, Grid Keyboard Navigation -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const SUBJECT_ID = {{ $batchSubject->id }};
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let currentActiveSheet = 'Sheet 1';
        let currentActiveTest = 'Test 1';
        let sheetSaveTimer = null;
        let testSaveTimer = null;

        // Cached Data Stores
        const sheetCache = @json($sheetEvals);
        const testCache = @json($seriesTests);

        // Grid Keyboard Navigation (Arrow Keys and Enter)
        function handleGridNavigation(e, currentInput) {
            if (['Enter', 'ArrowDown', 'ArrowUp'].includes(e.key)) {
                e.preventDefault();
                const table = currentInput.closest('table');
                const inputs = Array.from(table.querySelectorAll('input.mark-input:not([disabled])'));
                const currentIndex = inputs.indexOf(currentInput);
                const colsPerRow = currentInput.closest('tr').querySelectorAll('input.mark-input:not([disabled])').length || 1;

                if (e.key === 'Enter' || e.key === 'ArrowDown') {
                    const nextInput = inputs[currentIndex + colsPerRow] || inputs[currentIndex + 1];
                    if (nextInput) { 
                        nextInput.focus(); 
                        nextInput.select(); 
                    } else {
                        // Reached last cell of table! Trigger immediate save
                        if (currentInput.classList.contains('sheet-timely') || currentInput.classList.contains('sheet-appearance')) {
                            saveActiveSheetMarks(true);
                        } else if (currentInput.classList.contains('test-p') || currentInput.classList.contains('test-f') || currentInput.classList.contains('test-d') || currentInput.classList.contains('test-n')) {
                            saveActiveTestMarks(true);
                        } else if (currentInput.classList.contains('att-override')) {
                            saveAttendanceMarks(true);
                        }
                    }
                } else if (e.key === 'ArrowUp') {
                    const prevInput = inputs[currentIndex - colsPerRow] || inputs[currentIndex - 1];
                    if (prevInput) { prevInput.focus(); prevInput.select(); }
                }
            }
        }

        // --- FORMATIVE SHEETS LOGIC ---
        function calculateSheetRowTotal(input) {
            const tr = input.closest('tr');
            const timely = parseFloat(tr.querySelector('.sheet-timely').value) || 0;
            const app = parseFloat(tr.querySelector('.sheet-appearance').value) || 0;
            const isAbsent = tr.querySelector('.sheet-absent').checked;
            const badge = tr.querySelector('.row-total-badge');

            if (isAbsent) {
                badge.innerText = 'ABS';
            } else {
                const total = Math.min(100, Math.max(0, timely + app));
                badge.innerText = total.toFixed(1);
            }
            triggerSheetDebouncedSave();
        }

        function toggleSheetAbsent(checkbox) {
            const tr = checkbox.closest('tr');
            const timelyInput = tr.querySelector('.sheet-timely');
            const appInput = tr.querySelector('.sheet-appearance');
            const badge = tr.querySelector('.row-total-badge');

            if (checkbox.checked) {
                timelyInput.disabled = true;
                appInput.disabled = true;
                badge.innerText = 'ABS';
            } else {
                timelyInput.disabled = false;
                appInput.disabled = false;
                calculateSheetRowTotal(timelyInput);
            }
            triggerSheetDebouncedSave();
        }

        function switchSheet(sheetNo, pillEl) {
            document.querySelectorAll('.sheet-pill').forEach(p => p.classList.remove('active'));
            pillEl.classList.add('active');
            currentActiveSheet = sheetNo;

            document.getElementById('activeSheetPillLabel').innerText = sheetNo;
            document.getElementById('activeSheetTitle').innerText = pillEl.getAttribute('data-sheet-title');
            document.getElementById('activeSheetMeta').innerHTML = 
                `${pillEl.getAttribute('data-sheet-module')} &bull; Mapped to Outcome: <strong class="text-info">${pillEl.getAttribute('data-sheet-co')}</strong> &bull; Max Score: 100 (Timely 50 + Appearance 50)`;

            // Populate table for this sheet
            const rows = document.querySelectorAll('#sheetTableBody tr');
            rows.forEach(tr => {
                const regNo = tr.getAttribute('data-reg-no');
                const stSheets = sheetCache[regNo] || [];
                const evalData = stSheets.find(s => s.sheet_no === sheetNo);

                const timelyInput = tr.querySelector('.sheet-timely');
                const appInput = tr.querySelector('.sheet-appearance');
                const absentCheckbox = tr.querySelector('.sheet-absent');
                const remarksInput = tr.querySelector('.sheet-remarks');
                const badge = tr.querySelector('.row-total-badge');

                if (evalData) {
                    timelyInput.value = evalData.is_absent ? '' : evalData.timely_completion;
                    appInput.value = evalData.is_absent ? '' : evalData.appearance_organization;
                    absentCheckbox.checked = evalData.is_absent;
                    remarksInput.value = evalData.remarks || '';
                    badge.innerText = evalData.is_absent ? 'ABS' : parseFloat(evalData.total_score_100).toFixed(1);
                    timelyInput.disabled = evalData.is_absent;
                    appInput.disabled = evalData.is_absent;
                } else {
                    timelyInput.value = '';
                    appInput.value = '';
                    absentCheckbox.checked = false;
                    remarksInput.value = '';
                    badge.innerText = '0.0';
                    timelyInput.disabled = false;
                    appInput.disabled = false;
                }
            });
        }

        function triggerSheetDebouncedSave() {
            setAutosaveStatus('sheetSaveStatus', 'saving', 'Saving...');
            setAutosaveStatus('globalAutoSaveIndicator', 'saving', 'Saving changes...');
            clearTimeout(sheetSaveTimer);
            sheetSaveTimer = setTimeout(() => {
                saveActiveSheetMarks(true);
            }, 800);
        }

        function saveActiveSheetMarks(isAuto = false) {
            const evaluations = [];
            document.querySelectorAll('#sheetTableBody tr').forEach(tr => {
                const regNo = tr.getAttribute('data-reg-no');
                const timely = tr.querySelector('.sheet-timely').value;
                const appearance = tr.querySelector('.sheet-appearance').value;
                const isAbsent = tr.querySelector('.sheet-absent').checked;
                const remarks = tr.querySelector('.sheet-remarks').value;

                evaluations.push({
                    reg_no: regNo,
                    timely_completion: timely ? parseFloat(timely) : 0,
                    appearance_organization: appearance ? parseFloat(appearance) : 0,
                    is_absent: isAbsent,
                    remarks: remarks
                });
            });

            fetch(`/r21/classroom/drawing/${SUBJECT_ID}/sheets/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    sheet_no: currentActiveSheet,
                    evaluations: evaluations
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setAutosaveStatus('sheetSaveStatus', 'saved', 'Saved');
                    setAutosaveStatus('globalAutoSaveIndicator', 'saved', 'All Changes Saved');
                    // Update client cache
                    evaluations.forEach(ev => {
                        if (!sheetCache[ev.reg_no]) sheetCache[ev.reg_no] = [];
                        const existing = sheetCache[ev.reg_no].find(s => s.sheet_no === currentActiveSheet);
                        if (existing) {
                            existing.timely_completion = ev.timely_completion;
                            existing.appearance_organization = ev.appearance_organization;
                            existing.total_score_100 = ev.is_absent ? 0 : (ev.timely_completion + ev.appearance_organization);
                            existing.is_absent = ev.is_absent;
                            existing.remarks = ev.remarks;
                        } else {
                            sheetCache[ev.reg_no].push({
                                sheet_no: currentActiveSheet,
                                timely_completion: ev.timely_completion,
                                appearance_organization: ev.appearance_organization,
                                total_score_100: ev.is_absent ? 0 : (ev.timely_completion + ev.appearance_organization),
                                is_absent: ev.is_absent,
                                remarks: ev.remarks
                            });
                        }
                    });
                } else {
                    setAutosaveStatus('sheetSaveStatus', 'error', 'Save Failed');
                    setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Error Saving');
                }
            })
            .catch(() => {
                setAutosaveStatus('sheetSaveStatus', 'error', 'Network Error');
                setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Network Error');
            });
        }

        // --- SUMMATIVE TESTS LOGIC ---
        function calculateTestRowTotal(input) {
            const tr = input.closest('tr');
            const p = parseFloat(tr.querySelector('.test-p').value) || 0;
            const f = parseFloat(tr.querySelector('.test-f').value) || 0;
            const d = parseFloat(tr.querySelector('.test-d').value) || 0;
            const n = parseFloat(tr.querySelector('.test-n').value) || 0;
            const isAbsent = tr.querySelector('.test-absent').checked;
            const badge = tr.querySelector('.test-total-badge');

            if (isAbsent) {
                badge.innerText = 'ABS';
            } else {
                const total = Math.min(100, Math.max(0, p + f + d + n));
                badge.innerText = total.toFixed(1);
            }
            triggerTestDebouncedSave();
        }

        function toggleTestAbsent(checkbox) {
            const tr = checkbox.closest('tr');
            const inputs = tr.querySelectorAll('.test-p, .test-f, .test-d, .test-n');
            const badge = tr.querySelector('.test-total-badge');

            inputs.forEach(inp => inp.disabled = checkbox.checked);
            if (checkbox.checked) {
                badge.innerText = 'ABS';
            } else {
                calculateTestRowTotal(inputs[0]);
            }
            triggerTestDebouncedSave();
        }

        function switchSummativeTest(testNo) {
            currentActiveTest = testNo;
            document.getElementById('btnSelectTest1').classList.toggle('active', testNo === 'Test 1');
            document.getElementById('btnSelectTest2').classList.toggle('active', testNo === 'Test 2');

            const rows = document.querySelectorAll('#testTableBody tr');
            rows.forEach(tr => {
                const regNo = tr.getAttribute('data-reg-no');
                const stTests = testCache[regNo] || [];
                const testData = stTests.find(t => t.test_no === testNo);

                const pInput = tr.querySelector('.test-p');
                const fInput = tr.querySelector('.test-f');
                const dInput = tr.querySelector('.test-d');
                const nInput = tr.querySelector('.test-n');
                const absentCheckbox = tr.querySelector('.test-absent');
                const remarksInput = tr.querySelector('.test-remarks');
                const badge = tr.querySelector('.test-total-badge');

                if (testData) {
                    pInput.value = testData.is_absent ? '' : testData.procedure_drawing;
                    fInput.value = testData.is_absent ? '' : testData.final_drawing;
                    dInput.value = testData.is_absent ? '' : testData.dimensioning;
                    nInput.value = testData.is_absent ? '' : testData.neatness;
                    absentCheckbox.checked = testData.is_absent;
                    remarksInput.value = testData.remarks || '';
                    badge.innerText = testData.is_absent ? 'ABS' : parseFloat(testData.total_score_100).toFixed(1);
                    [pInput, fInput, dInput, nInput].forEach(i => i.disabled = testData.is_absent);
                } else {
                    pInput.value = '';
                    fInput.value = '';
                    dInput.value = '';
                    nInput.value = '';
                    absentCheckbox.checked = false;
                    remarksInput.value = '';
                    badge.innerText = '0.0';
                    [pInput, fInput, dInput, nInput].forEach(i => i.disabled = false);
                }
            });
        }

        function triggerTestDebouncedSave() {
            setAutosaveStatus('testSaveStatus', 'saving', 'Saving...');
            setAutosaveStatus('globalAutoSaveIndicator', 'saving', 'Saving changes...');
            clearTimeout(testSaveTimer);
            testSaveTimer = setTimeout(() => {
                saveActiveTestMarks(true);
            }, 800);
        }

        function saveActiveTestMarks(isAuto = false) {
            const evaluations = [];
            document.querySelectorAll('#testTableBody tr').forEach(tr => {
                const regNo = tr.getAttribute('data-reg-no');
                const p = tr.querySelector('.test-p').value;
                const f = tr.querySelector('.test-f').value;
                const d = tr.querySelector('.test-d').value;
                const n = tr.querySelector('.test-n').value;
                const isAbsent = tr.querySelector('.test-absent').checked;
                const remarks = tr.querySelector('.test-remarks').value;

                evaluations.push({
                    reg_no: regNo,
                    procedure_drawing: p ? parseFloat(p) : 0,
                    final_drawing: f ? parseFloat(f) : 0,
                    dimensioning: d ? parseFloat(d) : 0,
                    neatness: n ? parseFloat(n) : 0,
                    is_absent: isAbsent,
                    remarks: remarks
                });
            });

            fetch(`/r21/classroom/drawing/${SUBJECT_ID}/tests/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    test_no: currentActiveTest,
                    evaluations: evaluations
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setAutosaveStatus('testSaveStatus', 'saved', 'Saved');
                    setAutosaveStatus('globalAutoSaveIndicator', 'saved', 'All Changes Saved');
                    evaluations.forEach(ev => {
                        if (!testCache[ev.reg_no]) testCache[ev.reg_no] = [];
                        const existing = testCache[ev.reg_no].find(t => t.test_no === currentActiveTest);
                        const tot = ev.is_absent ? 0 : (ev.procedure_drawing + ev.final_drawing + ev.dimensioning + ev.neatness);
                        if (existing) {
                            existing.procedure_drawing = ev.procedure_drawing;
                            existing.final_drawing = ev.final_drawing;
                            existing.dimensioning = ev.dimensioning;
                            existing.neatness = ev.neatness;
                            existing.total_score_100 = tot;
                            existing.is_absent = ev.is_absent;
                            existing.remarks = ev.remarks;
                        } else {
                            testCache[ev.reg_no].push({
                                test_no: currentActiveTest,
                                procedure_drawing: ev.procedure_drawing,
                                final_drawing: ev.final_drawing,
                                dimensioning: ev.dimensioning,
                                neatness: ev.neatness,
                                total_score_100: tot,
                                is_absent: ev.is_absent,
                                remarks: ev.remarks
                            });
                        }
                    });
                } else {
                    setAutosaveStatus('testSaveStatus', 'error', 'Save Failed');
                    setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Error Saving');
                }
            })
            .catch(() => {
                setAutosaveStatus('testSaveStatus', 'error', 'Network Error');
                setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Network Error');
            });
        }

        // --- ATTENDANCE OVERRIDE LOGIC ---
        let attSaveTimer = null;
        function triggerAttendanceDebouncedSave() {
            setAutosaveStatus('attSaveStatus', 'saving', 'Saving...');
            setAutosaveStatus('globalAutoSaveIndicator', 'saving', 'Saving changes...');
            clearTimeout(attSaveTimer);
            attSaveTimer = setTimeout(() => {
                saveAttendanceMarks(true);
            }, 800);
        }

        function updateFinalAttCell(input) {
            const tr = input.closest('tr');
            const badge = tr.querySelector('.final-att-badge');
            const calc = parseFloat(input.getAttribute('data-calc')) || 0;
            const val = input.value.trim() !== '' ? parseFloat(input.value) : calc;
            badge.innerText = val.toFixed(1) + ' / {{ $attMax }}';
            triggerAttendanceDebouncedSave();
        }

        function saveAttendanceMarks(isAuto = false) {
            setAutosaveStatus('attSaveStatus', 'saving', 'Saving...');
            setAutosaveStatus('globalAutoSaveIndicator', 'saving', 'Saving attendance...');
            const records = [];
            document.querySelectorAll('#attTable tbody tr').forEach(tr => {
                const regNo = tr.getAttribute('data-reg-no');
                const overrideInput = tr.querySelector('.att-override');
                const calcMark = overrideInput.getAttribute('data-calc');
                const attPct = overrideInput.getAttribute('data-att-pct');
                const overrideVal = overrideInput.value.trim();

                records.push({
                    reg_no: regNo,
                    attendance_percentage: parseFloat(attPct),
                    attendance_mark: parseFloat(calcMark),
                    override_mark: overrideVal !== '' ? parseFloat(overrideVal) : null
                });
            });

            fetch(`/r21/classroom/drawing/${SUBJECT_ID}/attendance/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ records: records })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setAutosaveStatus('attSaveStatus', 'saved', 'Saved');
                    setAutosaveStatus('globalAutoSaveIndicator', 'saved', 'All Changes Saved');
                } else {
                    setAutosaveStatus('attSaveStatus', 'error', 'Save Failed');
                    setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Attendance Save Failed');
                }
            })
            .catch(() => {
                setAutosaveStatus('attSaveStatus', 'error', 'Network Error');
                setAutosaveStatus('globalAutoSaveIndicator', 'error', 'Network Error');
            });
        }

        // Helpers
        function setAutosaveStatus(elementId, status, text) {
            const el = document.getElementById(elementId);
            if (!el) return;
            el.className = 'autosave-badge autosave-' + status;
            let icon = 'fa-check';
            if (status === 'saving') icon = 'fa-spinner fa-spin';
            if (status === 'error') icon = 'fa-triangle-exclamation';
            el.innerHTML = `<i class="fa-solid ${icon}"></i> ${text}`;
        }

        // Syllabus Upload
        document.getElementById('uploadSyllabusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('uploadBtn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading...';
            btn.disabled = true;

            const formData = new FormData(this);
            fetch(`/r21/classroom/drawing/${SUBJECT_ID}/syllabus`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Syllabus PDF uploaded successfully!');
                    location.reload();
                } else {
                    alert('Upload failed: ' + (data.message || 'Unknown error'));
                    btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Syllabus PDF';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                alert('An error occurred during upload.');
                btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Syllabus PDF';
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>
