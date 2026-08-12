<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }} - Virtual Drawing Hall ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021' : 'R2026' }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: #1f2937;
            --bg-card-hover: #374151;
            --border-color: #374151;
            --accent-cyan: #06b6d4;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-purple: #8b5cf6;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            font-size: 0.85rem;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .navbar-custom {
            background-color: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .glass-card {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .glass-card h2 {
            font-size: 1.15rem !important;
        }

        .glass-card h5 {
            font-size: 0.92rem !important;
        }

        .stat-card {
            padding: 0.6rem 0.85rem;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.8) 0%, rgba(17, 24, 39, 0.9) 100%);
            border: 1px solid var(--border-color);
        }

        .stat-card .stat-val {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .nav-tabs-custom {
            border-bottom: 1px solid var(--border-color);
            gap: 0.35rem;
        }

        .nav-tabs-custom .nav-link {
            color: var(--text-muted);
            border: 1px solid transparent;
            border-radius: 6px 6px 0 0;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--text-main);
            background: rgba(55, 65, 81, 0.4);
        }

        .nav-tabs-custom .nav-link.active {
            color: #fff;
            background: var(--bg-card);
            border-color: var(--border-color) var(--border-color) transparent;
            border-top: 2.5px solid var(--accent-cyan);
        }

        .table-custom {
            color: var(--text-main);
            border-color: var(--border-color);
            font-size: 0.72rem;
            line-height: 1.25;
        }

        .table-custom th {
            background-color: #111827;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.03em;
            padding: 0.35rem 0.4rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .table-custom td {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            vertical-align: middle;
            padding: 0.25rem 0.4rem;
            font-weight: 500;
        }

        .form-control-custom {
            background-color: #111827;
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 6px;
            font-size: 0.82rem;
        }

        .form-control-custom:focus {
            background-color: #1f2937;
            border-color: var(--accent-cyan);
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.25);
        }

        .growable-textarea {
            resize: none;
            overflow-y: hidden;
            min-height: 38px;
            line-height: 1.4;
            font-size: 0.82rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 6px;
            background-color: #111827;
            color: #fff;
            border: 1px solid var(--border-color);
            width: 100%;
        }

        .growable-textarea:focus {
            background-color: #1f2937;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.25);
            color: #fff;
            outline: none;
        }

        .badge-cyan { background-color: rgba(6, 182, 212, 0.15); color: var(--accent-cyan); border: 1px solid var(--accent-cyan); font-size: 0.72rem; padding: 0.25em 0.55em; }
        .badge-emerald { background-color: rgba(16, 185, 129, 0.15); color: var(--accent-emerald); border: 1px solid var(--accent-emerald); font-size: 0.72rem; padding: 0.25em 0.55em; }
        .badge-amber { background-color: rgba(245, 158, 11, 0.15); color: var(--accent-amber); border: 1px solid var(--accent-amber); font-size: 0.72rem; padding: 0.25em 0.55em; }
        .badge-rose { background-color: rgba(244, 63, 94, 0.15); color: var(--accent-rose); border: 1px solid var(--accent-rose); font-size: 0.72rem; padding: 0.25em 0.55em; }
        .badge-purple { background-color: rgba(139, 92, 246, 0.15); color: var(--accent-purple); border: 1px solid var(--accent-purple); font-size: 0.72rem; padding: 0.25em 0.55em; }

        .mark-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            min-width: 70px;
        }

        .rubric-input::-webkit-inner-spin-button,
        .rubric-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .rubric-input {
            -moz-appearance: textfield;
            width: 44px;
            text-align: center;
            font-weight: 500;
            font-size: 0.72rem;
            padding: 0.1rem 0.15rem;
            height: 22px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background-color: #111827;
            color: #fff;
        }

        .mark-slider {
            width: 100%;
            accent-color: var(--accent-cyan);
            height: 4px;
            background: #374151;
            border-radius: 3px;
            cursor: pointer;
            margin: 1px 0 0 0;
        }

        .mark-slider::-webkit-slider-thumb {
            width: 14px;
            height: 14px;
            background: var(--accent-cyan);
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(6, 182, 212, 0.8);
            cursor: pointer;
        }

        .btn-cyan {
            background-color: var(--accent-cyan);
            color: #000;
            font-weight: 600;
            border: none;
            font-size: 0.82rem;
            padding: 0.35rem 0.75rem;
        }
        .btn-cyan:hover {
            background-color: #22d3ee;
            color: #000;
        }

        .btn-outline-cyan {
            background-color: rgba(6, 182, 212, 0.15);
            color: #22d3ee !important;
            border: 1px solid #06b6d4 !important;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 0.35rem 0.75rem;
        }
        .btn-outline-cyan:hover {
            background-color: #06b6d4 !important;
            color: #000 !important;
        }

        @media (max-width: 768px) {
            .nav-tabs-custom {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }
            .nav-tabs-custom .nav-link {
                white-space: nowrap;
            }
            .mark-cell {
                min-width: 110px;
            }
            .mark-slider {
                height: 8px; /* Thicker touch target on mobile */
            }
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-2">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/dashboard/lecturer">
                <i class="fa-solid fa-drafting-compass text-info fs-4"></i>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-bold brand-font text-white" style="font-size: 1rem;">Carmel Linx</span>
                    <span class="text-secondary brand-font d-none d-sm-inline" style="font-size: 0.9rem;">|</span>
                    <span class="fw-bold brand-font px-2.5 py-1 rounded-2 shadow-sm" style="font-size: 0.85rem; background: linear-gradient(135deg, rgba(14, 165, 233, 0.25), rgba(6, 182, 212, 0.15)); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35); display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-palette text-info fs-6"></i>
                        Virtual Drawing Hall ({{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }})
                    </span>
                </div>
            </a>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end d-none d-md-block">
                    <span class="badge bg-dark text-info border border-info px-2 py-1" style="font-size: 0.72rem; font-weight: 600;">
                        <i class="fa-solid fa-graduation-cap me-1"></i> {{ $classroom->classroom_id }} | Sem {{ $classroom->current_semester ?? 'I' }}
                    </span>
                </div>
                <button onclick="toggleFullscreen()" class="btn btn-sm btn-outline-light px-2.5 py-1 fw-bold text-white shadow-sm" id="fullscreenToggleBtn" title="Toggle Fullscreen" style="font-size: 0.8rem; background: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.2);">
                    <i class="fa-solid fa-expand me-1 text-info"></i> <span class="d-none d-sm-inline">Fullscreen</span>
                </button>
                <a href="/dashboard/lecturer" class="btn btn-sm btn-outline-info px-3 py-1 fw-bold text-info shadow-sm" style="font-size: 0.8rem; background: rgba(6, 182, 212, 0.12); border-color: rgba(56, 189, 248, 0.5);"><i class="fa-solid fa-arrow-left me-1.5"></i> Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid px-4 py-3">

        <!-- Top Banner -->
        <div class="glass-card p-3 mb-3">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                        @php
                            $courseType = $drawingCourseFile->type_of_course ?? 'Drawing';
                            $isLab = str_contains(strtolower($courseType), 'lab') || str_contains(strtolower($courseType), 'practical');
                        @endphp
                        <span class="badge {{ $isLab ? 'badge-cyan' : 'badge-purple' }} px-2 py-0.5" style="font-size: 0.68rem;">
                            <i class="fa-solid {{ $isLab ? 'fa-flask' : 'fa-pen-ruler' }} me-1"></i> {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R2021' : 'R2026' }} {{ $courseType }} Paper
                        </span>
                        
                        <!-- Batch Badge -->
                        <span class="badge badge-emerald px-2 py-0.5" style="font-size: 0.68rem;">
                            <i class="fa-solid fa-users me-1"></i> Batch: {{ $classroom->batch_year ?? 'R26' }} ({{ $batchSubject->classroom_id }})
                        </span>

                        <!-- Assigned Faculty Badge -->
                        <span class="badge badge-purple px-2 py-0.5" style="font-size: 0.68rem;">
                            <i class="fa-solid fa-user-tie me-1"></i> Faculty: 
                            @if(isset($assignedStaff) && count($assignedStaff) > 0)
                                {{ $assignedStaff->pluck('name')->implode(', ') }}
                            @else
                                {{ Session::get('userName') ?? 'Faculty In-Charge' }}
                            @endif
                        </span>

                        <!-- AI Status Badge -->
                        @php
                            $isAiActive = \App\Http\Controllers\SystemSettingController::isAiEnabled();
                        @endphp
                        @if($isAiActive)
                            <span class="badge badge-cyan px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;" title="AI Support API Active">
                                <span class="rounded-circle bg-emerald-400 d-inline-block" style="width:5px; height:5px;"></span>
                                <span>AI Active</span>
                            </span>
                        @else
                            <span class="badge bg-dark text-warning border border-warning-subtle px-2 py-0.5 d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;" title="AI is deactivated. Generating content from structured syllabus database and offline banks.">
                                <span class="rounded-circle bg-warning d-inline-block" style="width:5px; height:5px;"></span>
                                <span>AI Offline (Local DB)</span>
                            </span>
                        @endif

                        <span class="badge badge-amber px-2 py-0.5" style="font-size: 0.68rem;">
                            <i class="fa-solid fa-clock me-1"></i> {{ $drawingCourseFile->contact_hours ?? 45 }} Hours
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1 text-white" style="font-size: 1.05rem;">
                        <span class="text-info me-1.5">[{{ $batchSubject->formatted_subject_code ?? ($drawingCourseFile->course_code ?? $batchSubject->subject_code) }}]</span>
                        <span>{{ $drawingCourseFile->course_title ?? $batchSubject->subject_name }}</span>
                    </h5>
                    <p class="mb-0" style="color: #cbd5e1; font-size: 0.8rem;">
                        <span style="color: #94a3b8;">Scheme L:T:P:R:</span> <strong style="color: #38bdf8; font-weight: 700;">{{ $drawingCourseFile->teaching_scheme ?? '0:0:3:0' }}</strong> &nbsp;|&nbsp; 
                        <span style="color: #94a3b8;">Credits:</span> <strong style="color: #38bdf8; font-weight: 700;">{{ $drawingCourseFile->credits ?? 1.5 }}</strong> &nbsp;|&nbsp; 
                        <span style="color: #94a3b8;">Contact Hours:</span> <strong style="color: #fbbf24; font-weight: 700;">{{ $drawingCourseFile->contact_hours ?? 45 }} Hrs</strong>
                    </p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-card text-center py-2 px-2 rounded-lg" style="background: rgba(6, 182, 212, 0.12); border: 1px solid var(--accent-cyan);">
                                <div class="fw-bold text-uppercase" style="font-size: 0.68rem; color: #38bdf8; letter-spacing: 0.2px;">Continuous Assessment (CIE)</div>
                                <span class="stat-val d-block fw-bold text-white mt-0.5" style="font-size: 1rem;">{{ $drawingCourseFile->cie_marks ?? 60 }} Marks</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card text-center py-2 px-2 rounded-lg" style="background: rgba(245, 158, 11, 0.12); border: 1px solid var(--accent-amber);">
                                <div class="fw-bold text-uppercase" style="font-size: 0.68rem; color: #fbbf24; letter-spacing: 0.2px;">End Semester Exam (ESE)</div>
                                <span class="stat-val d-block fw-bold text-white mt-0.5" style="font-size: 1rem;">{{ $drawingCourseFile->ese_marks ?? 40 }} Marks</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="drawingHallTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tab-syllabus-link" data-bs-toggle="tab" data-bs-target="#tab-syllabus" type="button"><i class="fa-solid fa-file-pdf me-2 text-info"></i>Syllabus & Parser</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-lessonplan-link" data-bs-toggle="tab" data-bs-target="#tab-lessonplan" type="button"><i class="fa-solid fa-calendar-days me-2 text-warning"></i>Lesson Plan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ce-link" data-bs-toggle="tab" data-bs-target="#tab-ce" type="button"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Eval (CE 30M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ca-link" data-bs-toggle="tab" data-bs-target="#tab-ca" type="button"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Tests (15M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-oee-link" data-bs-toggle="tab" data-bs-target="#tab-oee" type="button"><i class="fa-solid fa-lightbulb me-2 text-amber"></i>Open-Ended (10M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ese-link" data-bs-toggle="tab" data-bs-target="#tab-ese" type="button"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Sem Exam ({{ $drawingCourseFile->ese_marks ?? 40 }}M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-cie-link" data-bs-toggle="tab" data-bs-target="#tab-cie" type="button"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated CIE & Reports</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-materials-link" data-bs-toggle="tab" data-bs-target="#tab-materials" type="button"><i class="fa-solid fa-folder-open me-2 text-warning"></i>Study Materials & Pre-Class Hub</button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="drawingHallTabContent">

            <!-- TAB 1: SYLLABUS & PARSER -->
            <div class="tab-pane fade show active" id="tab-syllabus" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-cloud-arrow-up me-2 text-info"></i>Upload Drawing Syllabus PDF</h5>
                            <form id="uploadSyllabusForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Select Syllabus PDF File</label>
                                    <input type="file" class="form-control form-control-custom" name="syllabus_file" accept=".pdf" required>
                                </div>
                                <button type="submit" class="btn btn-cyan w-100" id="uploadBtn">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload, Parse & Extract Syllabus
                                </button>
                            </form>
                            @if(!empty($drawingCourseFile->syllabus_pdf_path))
                            <div class="mt-3">
                                <a href="/storage/{{ str_replace('/storage/', '', $drawingCourseFile->syllabus_pdf_path) }}" target="_blank" class="btn btn-outline-info btn-sm w-100 fw-bold py-2 shadow-sm">
                                    <i class="fa-solid fa-file-pdf me-1.5 text-danger fs-6"></i> View Uploaded Syllabus PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="glass-card p-4 mb-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-bullseye me-2 text-info"></i>Course Outcomes (COs)</h5>
                            <div class="row g-3">
                                @foreach($drawingCourseFile->parsed_cos ?? [] as $co)
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: #111827; border: 1px solid var(--border-color);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-cyan fw-bold">{{ $co['id'] }}</span>
                                            <span class="badge badge-purple">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                                        </div>
                                        <p class="small mb-0 text-light">{{ $co['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CO-PO Matrix -->
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-table-cells me-2 text-warning"></i>CO-PO Articulation Matrix</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            @for($p=1; $p<=11; $p++) <th>PO{{ $p }}</th> @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            @for($p=1; $p<=11; $p++)
                                                @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                                <td class="{{ $val != '-' ? 'fw-bold text-success' : 'text-muted' }}">{{ $val }}</td>
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

            <!-- TAB 2: LESSON PLANNER -->
            <div class="tab-pane fade" id="tab-lessonplan" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom border-secondary">
                        <div>
                            <h5 class="fw-bold mb-1 text-warning"><i class="fa-solid fa-list-check me-2"></i>{{ $drawingCourseFile->contact_hours ?? 45 }}-Hour {{ $drawingCourseFile->type_of_course ?? 'Drawing' }} Lesson Plan</h5>
                            <small class="text-muted">Single Batch (Whole Class) practical sessions covering Manual Drawing & CAD Drafting, Series Exams & OEE Project</small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <input type="hidden" id="lesson_planner_mode" value="single">
                            <button onclick="generateLessonTimeline()" class="btn btn-sm btn-outline-primary px-2.5 py-1 fw-bold">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Generate
                            </button>
                            <a href="/r26/classroom/drawing/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="btn btn-sm btn-outline-light px-2.5 py-1 fw-bold">
                                <i class="fa-solid fa-print me-1"></i> Print Plan
                            </a>
                            <button onclick="saveLessonPlannerBulk()" class="btn btn-sm btn-success px-3 py-1 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Planner
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-custom table-hover align-middle mb-0 w-100" id="lesson-plan-table" style="table-layout: auto;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Hour</th>
                                    <th style="width: 125px;">Proposed Date</th>
                                    <th style="width: 125px;">Actual Date</th>
                                    <th>Topic & Exercise Content (Growable Field)</th>
                                    <th style="width: 85px;">Mapped CO</th>
                                    <th style="width: 55px;">Hrs</th>
                                    <th style="width: 145px;">Pedagogy / Activity</th>
                                    <th style="width: 100px;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="lesson-plan-rows-container">
                                @forelse($lessonPlans as $lp)
                                <tr class="lesson-plan-row" data-id="{{ $lp->id }}">
                                    <td class="fw-bold text-center text-info lp-day-no">#{{ $lp->day_no }}</td>
                                    <td>
                                        <input type="date" value="{{ $lp->proposed_date ?: $lp->planned_date }}" class="form-control form-control-custom form-control-sm lp-proposed">
                                    </td>
                                    <td>
                                        <input type="date" value="{{ $lp->actual_date }}" class="form-control form-control-custom form-control-sm lp-actual">
                                    </td>
                                    <td>
                                        <textarea class="growable-textarea lp-topic" rows="1" oninput="autoGrow(this); updateLpHoursTotal();">{{ $lp->topic_content }}</textarea>
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm lp-co">
                                            @foreach(['CO1', 'CO2', 'CO3', 'CO4', 'CO5'] as $coId)
                                            <option value="{{ $coId }}" {{ ($lp->co_tag ?: $lp->co_id) == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $lp->allocated_hours ?: 1 }}" class="form-control form-control-custom form-control-sm text-center lp-hours" inputmode="numeric" oninput="updateLpHoursTotal()">
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm lp-pedagogy">
                                            <option value="Drawing Lab Practical (P)" {{ $lp->pedagogy == 'Drawing Lab Practical (P)' ? 'selected' : '' }}>Drawing Lab Practical (P)</option>
                                            <option value="Series Test Examination (CA1)" {{ $lp->pedagogy == 'Series Test Examination (CA1)' ? 'selected' : '' }}>Series Test Examination (CA1)</option>
                                            <option value="Series Test Examination (CA2)" {{ $lp->pedagogy == 'Series Test Examination (CA2)' ? 'selected' : '' }}>Series Test Examination (CA2)</option>
                                            <option value="Open-Ended Project (OEE)" {{ $lp->pedagogy == 'Open-Ended Project (OEE)' ? 'selected' : '' }}>Open-Ended Project (OEE)</option>
                                            <option value="Drawing Lab Revision (P)" {{ $lp->pedagogy == 'Drawing Lab Revision (P)' ? 'selected' : '' }}>Drawing Lab Revision (P)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-control-custom form-select-sm fw-bold lp-status">
                                            <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-warning">Pending</option>
                                            <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-success">Completed</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4 italic">No planner generated yet. Click "Generate" or "Add Row" to start building schedule.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Action Controls -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-3 pt-3 border-top border-secondary">
                        <button onclick="addLessonPlanRow()" class="btn btn-outline-info btn-sm px-3 py-1 fw-bold">
                            <i class="fa-solid fa-plus me-1"></i> Add Row
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark text-warning border border-warning px-2.5 py-1.5 fs-6" id="lpTotalHoursBadge">
                                Total: {{ $lessonPlans->reject(fn($lp) => empty(trim($lp->topic_content)))->sum('allocated_hours') }} Hours
                            </span>
                            <button onclick="saveLessonPlannerBulk()" class="btn btn-success btn-sm px-3 py-1 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Planner
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CONTINUOUS EVALUATION (CE - 30 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ce" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Practical Evaluation (CE)</h5>
                            <small class="text-muted">Split rubric scoring via slider controls (Max 50 -> Converted to 30 CIE Marks)</small>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <select class="form-select form-control-custom" id="ceExerciseSelect" style="max-width: 320px;">
                                @foreach($drawingCourseFile->parsed_exercises ?? [] as $ex)
                                <option value="{{ $ex['exercise_no'] }}">{{ $ex['exercise_no'] }}: {{ $ex['title'] }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-cyan btn-sm" data-bs-toggle="modal" data-bs-target="#addExerciseModal">
                                <i class="fa-solid fa-plus me-1"></i> Add Exercise
                            </button>
                            <a href="/r26/classroom/drawing/exercises/print/{{ $batchSubject->id }}" target="_blank" class="btn btn-outline-light btn-sm">
                                <i class="fa-solid fa-print me-1"></i> Print List
                            </a>
                            <a href="/r26/classroom/drawing/ce-consolidated/print/{{ $batchSubject->id }}" target="_blank" class="btn btn-outline-warning btn-sm">
                                <i class="fa-solid fa-file-invoice me-1"></i> CE Report (50M)
                            </a>
                            <button class="btn btn-cyan btn-sm" id="saveCeBtn">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save CE
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="ceTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th title="Attendance & Pre-lab (10)">Prep (10)</th>
                                    <th title="Setup & Procedure (10)">Setup (10)</th>
                                    <th title="Observation & Recording (5)">Obs (5)</th>
                                    <th title="Analysis & Dimensioning (10)">Anal (10)</th>
                                    <th title="Viva Voce (10)">Viva (10)</th>
                                    <th title="Workmanship & Line Quality (5)">Work (5)</th>
                                    <th style="width: 80px;">Total (50)</th>
                                </tr>
                            </thead>
                             <tbody>
                                @php
                                    $firstExNo = isset($drawingCourseFile->parsed_exercises[0]) ? $drawingCourseFile->parsed_exercises[0]['exercise_no'] : 'EXE-01';
                                @endphp
                                @forelse($students as $st)
                                @php
                                    $stEvals = isset($slotEvals[$st->reg_no]) ? $slotEvals[$st->reg_no] : collect();
                                    $stEval = $stEvals->firstWhere('exercise_no', $firstExNo) ?? $stEvals->first();
                                    $v1 = $stEval->prep_punctuality ?? 0;
                                    $v2 = $stEval->setup_procedure ?? 0;
                                    $v3 = $stEval->observation_recording ?? 0;
                                    $v4 = $stEval->analysis_interpretation ?? 0;
                                    $v5 = $stEval->viva_voce ?? 0;
                                    $v6 = $stEval->workmanship_discipline ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="text-center text-cyan" style="font-size: 0.72rem; font-weight: 500;">{{ $st->roll_no }}</td>
                                    <td><span class="badge bg-dark border border-cyan text-cyan font-monospace px-1.5 py-0.5" style="font-size: 0.7rem; font-weight: 500;">{{ $st->reg_no }}</span></td>
                                    <td class="text-white" style="font-size: 0.72rem; font-weight: 500;">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p1" value="{{ $v1 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v1 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p2" value="{{ $v2 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v2 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p3" value="{{ $v3 }}" max="5" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v3 }}" max="5" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p4" value="{{ $v4 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v4 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p5" value="{{ $v5 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v5 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input p6" value="{{ $v6 }}" max="5" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $v6 }}" max="5" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="text-info total-50 text-center" style="font-size: 0.75rem; font-weight: 600;">{{ number_format($v1+$v2+$v3+$v4+$v5+$v6, 2) }}</td>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">No students enrolled in this classroom batch yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: PRACTICAL SERIES TESTS (CA1 & CA2 - 15 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ca" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-4">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Series Tests (CA1 & CA2)</h5>
                            <small class="text-muted">Interactive sliders & automated QP, Scheme & Answer Key generation</small>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-control-custom" id="caTestSelect" onchange="loadCaTestData()">
                                <option value="CA1">CA1: Manual Drawing (Modules I & II - Max 40)</option>
                                <option value="CA2">CA2: CAD Exam (Modules III & IV - Max 40)</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-1 flex-wrap">
                            <button class="btn btn-outline-warning btn-sm" onclick="openQuestionBankModal()">
                                <i class="fa-solid fa-edit me-1"></i> Question Bank & Edit QP
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-print me-1"></i> Print Papers
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><h6 class="dropdown-header">Series Test 1 (Modules I & II)</h6></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=qp" target="_blank">📄 QP Only (Strict 1 A4 Page)</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=scheme" target="_blank">📊 Valuation Scheme</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=key" target="_blank">🔑 Model Answer Key</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/1?doc_type=all" target="_blank">📚 Complete Package</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Series Test 2 (Modules III & IV)</h6></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=qp" target="_blank">📄 QP Only (Strict 1 A4 Page)</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=scheme" target="_blank">📊 Valuation Scheme</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=key" target="_blank">🔑 Model Answer Key</a></li>
                                    <li><a class="dropdown-item" href="/r26/classroom/drawing/series-test/print/{{ $batchSubject->id }}/2?doc_type=all" target="_blank">📚 Complete Package</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-cyan btn-sm" id="saveCaBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save Marks</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="caTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Procedure / Writeup (10)</th>
                                    <th>Execution / Setup (10)</th>
                                    <th>Output / Drawing (8)</th>
                                    <th>Viva Voce (8)</th>
                                    <th>Record Completion (4)</th>
                                    <th style="width: 80px;">Total (40)</th>
                                    <th style="width: 60px;">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stTests = isset($practicalTests[$st->reg_no]) ? $practicalTests[$st->reg_no] : collect();
                                    $ca1 = $stTests->where('test_no', 'CA1')->first();
                                    $cw = $ca1->writeup_procedure ?? 0;
                                    $cs = $ca1->setup_execution ?? 0;
                                    $co = $ca1->observation_result ?? 0;
                                    $cv = $ca1->viva_voce ?? 0;
                                    $cr = $ca1->record_completion ?? 0;
                                    $isAbs = $ca1->is_absent ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="text-center text-cyan" style="font-size: 0.72rem; font-weight: 500;">{{ $st->roll_no }}</td>
                                    <td><span class="badge bg-dark border border-cyan text-cyan font-monospace px-1.5 py-0.5" style="font-size: 0.7rem; font-weight: 500;">{{ $st->reg_no }}</span></td>
                                    <td class="text-white" style="font-size: 0.72rem; font-weight: 500;">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ca-w" value="{{ $cw }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cw }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ca-s" value="{{ $cs }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cs }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ca-o" value="{{ $co }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $co }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ca-v" value="{{ $cv }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cv }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ca-r" value="{{ $cr }}" max="4" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $cr }}" max="4" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="text-warning ca-total-40 text-center" style="font-size: 0.75rem; font-weight: 600;">{{ number_format($cw+$cs+$co+$cv+$cr, 2) }}</td>
                                    <td class="text-center"><input type="checkbox" class="form-check-input ca-absent" {{ $isAbs ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: OPEN-ENDED EXPERIMENT (OEE - 10 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-oee" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-lightbulb me-2 text-amber"></i>Open-Ended Experiment (OEE)</h5>
                            <small class="text-muted">Slider entry for CAD mini-project criteria (Max 50 -> Converted to 10 CIE Marks)</small>
                        </div>
                        <button class="btn btn-cyan" id="saveOeeBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save OEE Marks</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="oeeTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Originality & Innovation (10)</th>
                                    <th>Objectives & Planning (10)</th>
                                    <th>Execution & CAD Drafting (10)</th>
                                    <th>Analysis & Dimensioning (10)</th>
                                    <th>Teamwork & Viva (10)</th>
                                    <th style="width: 80px;">Total (50)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stOee = $oeeEvals[$st->reg_no] ?? null;
                                    $m1 = $stOee->originality_relevance ?? 0;
                                    $m2 = $stOee->objectives_plan ?? 0;
                                    $m3 = $stOee->execution_recording ?? 0;
                                    $m4 = $stOee->analysis_presentation ?? 0;
                                    $m5 = $stOee->teamwork_innovation ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="text-center text-cyan" style="font-size: 0.72rem; font-weight: 500;">{{ $st->roll_no }}</td>
                                    <td><span class="badge bg-dark border border-cyan text-cyan font-monospace px-1.5 py-0.5" style="font-size: 0.7rem; font-weight: 500;">{{ $st->reg_no }}</span></td>
                                    <td class="text-white" style="font-size: 0.72rem; font-weight: 500;">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input oee-m1" value="{{ $m1 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m1 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input oee-m2" value="{{ $m2 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m2 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input oee-m3" value="{{ $m3 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m3 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input oee-m4" value="{{ $m4 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m4 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input oee-m5" value="{{ $m5 }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $m5 }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="text-amber oee-total-50 text-center" style="font-size: 0.75rem; font-weight: 600;">{{ number_format($m1+$m2+$m3+$m4+$m5, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: END SEMESTER EXAM (ESE - 40 MARKS) WITH SLIDER INPUTS -->
            <div class="tab-pane fade" id="tab-ese" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Semester CAD Practical Exam (ESE)</h5>
                            <small class="text-muted">Board CAD Practical Exam split marks via sliders: Part A MCQ (10) + Part B CAD (18) + Part C Viva (8) + Part D Record (4) = 40 Marks</small>
                        </div>
                        <button class="btn btn-cyan" id="saveEseBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save ESE Marks</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="eseTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 90px;">Reg No</th>
                                    <th>Student Name</th>
                                    <th>Part A: MCQ (10)</th>
                                    <th>Part B: CAD Drafting (18)</th>
                                    <th>Part C: Viva Voce (8)</th>
                                    <th>Part D: Record (4)</th>
                                    <th style="width: 80px;">Total ESE (40)</th>
                                    <th style="width: 60px;">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stEse = $eseMarks[$st->reg_no] ?? null;
                                    $pa = $stEse->part_a_mcq ?? 0;
                                    $pb = $stEse->part_b_cad ?? 0;
                                    $pc = $stEse->part_c_viva ?? 0;
                                    $pd = $stEse->part_d_record ?? 0;
                                    $isAbsEse = $stEse->is_absent ?? 0;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="text-center text-cyan" style="font-size: 0.72rem; font-weight: 500;">{{ $st->roll_no }}</td>
                                    <td><span class="badge bg-dark border border-cyan text-cyan font-monospace px-1.5 py-0.5" style="font-size: 0.7rem; font-weight: 500;">{{ $st->reg_no }}</span></td>
                                    <td class="text-white" style="font-size: 0.72rem; font-weight: 500;">{{ $st->name }}</td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ese-pa" value="{{ $pa }}" max="10" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pa }}" max="10" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ese-pb" value="{{ $pb }}" max="18" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pb }}" max="18" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ese-pc" value="{{ $pc }}" max="8" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pc }}" max="8" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mark-cell">
                                            <input type="text" inputmode="decimal" class="rubric-input ese-pd" value="{{ $pd }}" max="4" min="0" step="0.5">
                                            <input type="range" class="mark-slider" value="{{ $pd }}" max="4" min="0" step="0.5">
                                        </div>
                                    </td>
                                    <td class="text-danger ese-total-40 text-center" style="font-size: 0.75rem; font-weight: 600;">{{ number_format($pa+$pb+$pc+$pd, 2) }}</td>
                                    <td class="text-center"><input type="checkbox" class="form-check-input ese-absent" {{ $isAbsEse ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 7: CONSOLIDATED CIE, SURVEYS & REPORTS -->
            <div class="tab-pane fade" id="tab-cie" role="tabpanel">
                <div class="glass-card p-4 mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated Course Score Sheet</h5>
                        <div class="btn-group">
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-report" target="_blank" class="btn btn-outline-info btn-sm fw-bold">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Register Matrix
                            </a>
                            <a href="/r26/classroom/drawing/{{ $batchSubject->id }}/attendance-consolidated" target="_blank" class="btn btn-info btn-sm fw-bold text-dark">
                                <i class="fa-solid fa-file-contract me-1"></i> Consolidated A4 Sheet
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Att (5)</th>
                                    <th>CE (30)</th>
                                    <th>Tests (15)</th>
                                    <th>OEE (10)</th>
                                    <th>Total CIE (60)</th>
                                    <th>ESE CAD (40)</th>
                                    <th>Total Marks (100)</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                <tr>
                                    <td class="text-center text-cyan" style="font-size: 0.72rem; font-weight: 500;">{{ $r['roll_no'] }}</td>
                                    <td><span class="badge bg-dark border border-cyan text-cyan font-monospace px-1.5 py-0.5" style="font-size: 0.7rem; font-weight: 500;">{{ $r['reg_no'] }}</span></td>
                                    <td class="text-white" style="font-size: 0.72rem; font-weight: 500;">{{ $r['name'] }}</td>
                                    <td style="font-size: 0.72rem;">{{ $r['att_marks'] }}</td>
                                    <td style="font-size: 0.72rem;">{{ $r['ce_marks'] }}</td>
                                    <td style="font-size: 0.72rem;">{{ $r['practical_test_marks'] }}</td>
                                    <td style="font-size: 0.72rem;">{{ $r['oee_marks'] }}</td>
                                    <td class="text-info" style="font-size: 0.75rem; font-weight: 600;">{{ $r['total_cie_marks'] }}</td>
                                    <td class="text-warning" style="font-size: 0.75rem; font-weight: 600;">{{ $r['total_ese'] }}</td>
                                    <td class="text-light" style="font-size: 0.75rem; font-weight: 600;">{{ $r['total_course_marks'] }}</td>
                                    <td>
                                        <span class="badge {{ $r['is_passed'] ? 'badge-emerald' : 'badge-rose' }}" style="font-size: 0.68rem; font-weight: 500;">
                                            {{ $r['is_passed'] ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Surveys & CO-PO Attainment -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3 text-info"><i class="fa-solid fa-poll me-2"></i>Indirect Attainment via Surveys</h5>
                            
                            <!-- Course Exit Survey Box -->
                            <div class="p-3 rounded mb-3" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                    <div>
                                        <span class="fw-bold text-white fs-6">Course Exit Survey</span>
                                        <span class="badge {{ $exitSurvey ? ($exitSurvey->status == 'Active' ? 'badge-cyan' : 'badge-emerald') : 'badge-amber' }} ms-2">
                                            {{ $exitSurvey ? ($exitSurvey->status == 'Active' ? 'Active / Open' : 'Completed') : 'Not Initiated' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-outline-info" onclick="openExitInitModalDrawing()">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit & Preview Questionnaire
                                        </button>
                                        @if(!$exitSurvey || $exitSurvey->status != 'Active')
                                        <button class="btn btn-sm btn-cyan" onclick="openExitInitModalDrawing()">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Initiate & Notify
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-danger" onclick="closeSurveyAction('exit')">
                                            <i class="fa-solid fa-lock me-1"></i> Close
                                        </button>
                                        @endif
                                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="btn btn-sm btn-outline-light">
                                            <i class="fa-solid fa-file-pdf me-1"></i> Report
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-secondary">
                                    <span class="fw-bold text-info" style="font-size: 0.88rem;">
                                        <i class="fa-solid fa-users me-1 text-cyan"></i> Responses Collected: 
                                        <span class="badge badge-cyan px-2 py-1 fs-6 ms-1">{{ $exitSurveyResponses->count() }}</span> / {{ $students->count() }} Enrolled
                                    </span>
                                    <small class="text-light" style="font-size: 0.75rem;"><i class="fa-solid fa-bell me-1 text-warning"></i> Auto-notifies student panel</small>
                                </div>
                            </div>

                            <!-- Mid-Semester Survey Box -->
                            <div class="p-3 rounded" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                    <div>
                                        <span class="fw-bold text-white fs-6">Mid-Semester Survey</span>
                                        <span class="badge {{ $midSemSurvey ? ($midSemSurvey->status == 'Active' ? 'badge-cyan' : 'badge-emerald') : 'badge-amber' }} ms-2">
                                            {{ $midSemSurvey ? ($midSemSurvey->status == 'Active' ? 'Active / Open' : 'Completed') : 'Not Initiated' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-outline-info" onclick="previewSurveyModal('midsem')">
                                            <i class="fa-solid fa-eye me-1"></i> Preview
                                        </button>
                                        @if(!$midSemSurvey || $midSemSurvey->status != 'Active')
                                        <button class="btn btn-sm btn-cyan" onclick="initiateSurveyAction('midsem')">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Initiate & Notify
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-danger" onclick="closeSurveyAction('midsem')">
                                            <i class="fa-solid fa-lock me-1"></i> Close
                                        </button>
                                        @endif
                                        <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="btn btn-sm btn-outline-light">
                                            <i class="fa-solid fa-file-pdf me-1"></i> Report
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-secondary">
                                    <span class="fw-bold text-info" style="font-size: 0.88rem;">
                                        <i class="fa-solid fa-users me-1 text-cyan"></i> Responses Collected: 
                                        <span class="badge badge-cyan px-2 py-1 fs-6 ms-1">{{ $midSemResponses->count() }}</span> / {{ $students->count() }} Enrolled
                                    </span>
                                    <small class="text-light" style="font-size: 0.75rem;"><i class="fa-solid fa-bell me-1 text-warning"></i> Auto-notifies student panel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-award me-2 text-warning"></i>CO Attainment Levels (80% Direct + 20% Indirect)</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            <th>Direct (80%)</th>
                                            <th>Indirect (20%)</th>
                                            <th>Final Attainment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            <td>L{{ number_format($directStats[$coTag]['level'] ?? 0.0, 1) }}</td>
                                            <td>
                                                <span class="badge {{ ($indirectStats[$coTag]['level'] ?? 0) >= 3 ? 'badge-emerald' : (($indirectStats[$coTag]['level'] ?? 0) >= 2 ? 'badge-amber' : 'badge-rose') }}">
                                                    {{ $indirectStats[$coTag]['rating'] ?? 'High (L3)' }} ({{ number_format($indirectStats[$coTag]['avg_score'] ?? 2.5, 2) }}/3.0)
                                                </span>
                                            </td>
                                            <td class="fw-bold text-success">{{ number_format($combinedStats[$coTag] ?? 0.0, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 p-2 rounded bg-dark border border-secondary text-light text-center" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-info-circle me-1 text-cyan"></i>
                                <strong>Indirect Attainment Scaling (3-Point Likert Scale):</strong>
                                <span class="text-success ms-2"><strong>3 = High</strong> (&ge;70%)</span> |
                                <span class="text-warning ms-1"><strong>2 = Medium</strong> (60-69%)</span> |
                                <span class="text-danger ms-1"><strong>1 = Low</strong> (50-59%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 8: STUDY MATERIALS & PRE-CLASS HUB -->
            <div class="tab-pane fade" id="tab-materials" role="tabpanel">
                @include('partials.virtual_learning_hub_tab', ['roomType' => 'Drawing'])
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = {{ $batchSubject->id }};
        const practicalTestsAll = @json($practicalTests);

        // Auto Grow Textarea Helper
        function autoGrow(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        // Initialize Slider & Number Input Bidirectional Sync
        function initSliderSync() {
            document.querySelectorAll('.mark-cell').forEach(cell => {
                const numInput = cell.querySelector('.rubric-input') || cell.querySelector('input[type="number"]');
                const sliderInput = cell.querySelector('input[type="range"]');
                if (!numInput || !sliderInput) return;

                sliderInput.addEventListener('input', () => {
                    numInput.value = sliderInput.value;
                    numInput.dispatchEvent(new Event('input', { bubbles: true }));
                });

                numInput.addEventListener('input', () => {
                    sliderInput.value = numInput.value;
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Auto grow all growable textareas
            document.querySelectorAll('.growable-textarea').forEach(el => autoGrow(el));

            // Initialize slider sync
            initSliderSync();

            // Auto sum listeners for CE Table
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.total-50').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for CA Table
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.ca-total-40').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for OEE Table
            document.querySelectorAll('#oeeTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.oee-total-50').textContent = sum.toFixed(2);
                    });
                });
            });

            // Auto sum listeners for ESE Table
            document.querySelectorAll('#eseTable tbody tr').forEach(tr => {
                tr.querySelectorAll('.rubric-input').forEach(input => {
                    input.addEventListener('input', () => {
                        let sum = 0;
                        tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                        tr.querySelector('.ese-total-40').textContent = sum.toFixed(2);
                    });
                });
            });
        });

        // Dynamic Loading for CA1 & CA2 Series Tests
        function loadCaTestData() {
            const selectedTest = document.getElementById('caTestSelect').value;
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                const regNo = tr.dataset.regNo;
                const studentTests = practicalTestsAll[regNo] || [];
                const testObj = studentTests.find(t => t.test_no === selectedTest) || {};

                const w = testObj.writeup_procedure || 0;
                const s = testObj.setup_execution || 0;
                const o = testObj.observation_result || 0;
                const v = testObj.viva_voce || 0;
                const r = testObj.record_completion || 0;
                const abs = testObj.is_absent ? true : false;

                const inputW = tr.querySelector('.ca-w');
                const inputS = tr.querySelector('.ca-s');
                const inputO = tr.querySelector('.ca-o');
                const inputV = tr.querySelector('.ca-v');
                const inputR = tr.querySelector('.ca-r');
                const chkAbs = tr.querySelector('.ca-absent');

                if (inputW) { inputW.value = w; inputW.dispatchEvent(new Event('input')); }
                if (inputS) { inputS.value = s; inputS.dispatchEvent(new Event('input')); }
                if (inputO) { inputO.value = o; inputO.dispatchEvent(new Event('input')); }
                if (inputV) { inputV.value = v; inputV.dispatchEvent(new Event('input')); }
                if (inputR) { inputR.value = r; inputR.dispatchEvent(new Event('input')); }
                if (chkAbs) { chkAbs.checked = abs; }
            });
        }

        // Upload Syllabus PDF
        document.getElementById('uploadSyllabusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('uploadBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Parsing PDF...';

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if(data.status === 'SUCCESS') {
                    this.reset();
                    alert('Syllabus uploaded and parsed successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(err) {
                alert('Parsing failed: ' + err.message);
            } finally {
                this.reset();
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload, Parse & Extract Syllabus';
            }
        });

        // Dynamic CE Evaluation Store & Switch Handler
        let allSlotEvalsStore = @json($slotEvals);

        document.getElementById('ceExerciseSelect')?.addEventListener('change', function() {
            const selectedEx = this.value;
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                const regNo = tr.dataset.regNo;
                const stEvals = allSlotEvalsStore[regNo] || [];
                const evalRec = stEvals.find(e => e.exercise_no === selectedEx);

                const v1 = evalRec ? (evalRec.prep_punctuality ?? 0) : 0;
                const v2 = evalRec ? (evalRec.setup_procedure ?? 0) : 0;
                const v3 = evalRec ? (evalRec.observation_recording ?? 0) : 0;
                const v4 = evalRec ? (evalRec.analysis_interpretation ?? 0) : 0;
                const v5 = evalRec ? (evalRec.viva_voce ?? 0) : 0;
                const v6 = evalRec ? (evalRec.workmanship_discipline ?? 0) : 0;

                const p1 = tr.querySelector('.p1');
                const p2 = tr.querySelector('.p2');
                const p3 = tr.querySelector('.p3');
                const p4 = tr.querySelector('.p4');
                const p5 = tr.querySelector('.p5');
                const p6 = tr.querySelector('.p6');

                if (p1) { p1.value = v1; if (p1.nextElementSibling) p1.nextElementSibling.value = v1; }
                if (p2) { p2.value = v2; if (p2.nextElementSibling) p2.nextElementSibling.value = v2; }
                if (p3) { p3.value = v3; if (p3.nextElementSibling) p3.nextElementSibling.value = v3; }
                if (p4) { p4.value = v4; if (p4.nextElementSibling) p4.nextElementSibling.value = v4; }
                if (p5) { p5.value = v5; if (p5.nextElementSibling) p5.nextElementSibling.value = v5; }
                if (p6) { p6.value = v6; if (p6.nextElementSibling) p6.nextElementSibling.value = v6; }

                const totEl = tr.querySelector('.total-50');
                if (totEl) {
                    const tot = (parseFloat(v1)||0) + (parseFloat(v2)||0) + (parseFloat(v3)||0) + (parseFloat(v4)||0) + (parseFloat(v5)||0) + (parseFloat(v6)||0);
                    totEl.textContent = tot.toFixed(2);
                }
            });
        });

        // Save CE
        document.getElementById('saveCeBtn').addEventListener('click', async () => {
            const exNo = document.getElementById('ceExerciseSelect').value;
            const marksData = [];
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    prep_punctuality: tr.querySelector('.p1').value,
                    setup_procedure: tr.querySelector('.p2').value,
                    observation_recording: tr.querySelector('.p3').value,
                    analysis_interpretation: tr.querySelector('.p4').value,
                    viva_voce: tr.querySelector('.p5').value,
                    workmanship_discipline: tr.querySelector('.p6').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/slot`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ exercise_no: exNo, marks_data: marksData })
            });
            const data = await res.json();

            if (data.status === 'SUCCESS') {
                // Update local store
                marksData.forEach(row => {
                    if (!allSlotEvalsStore[row.reg_no]) allSlotEvalsStore[row.reg_no] = [];
                    let rec = allSlotEvalsStore[row.reg_no].find(e => e.exercise_no === exNo);
                    if (!rec) {
                        rec = { exercise_no: exNo };
                        allSlotEvalsStore[row.reg_no].push(rec);
                    }
                    rec.prep_punctuality = parseFloat(row.prep_punctuality);
                    rec.setup_procedure = parseFloat(row.setup_procedure);
                    rec.observation_recording = parseFloat(row.observation_recording);
                    rec.analysis_interpretation = parseFloat(row.analysis_interpretation);
                    rec.viva_voce = parseFloat(row.viva_voce);
                    rec.workmanship_discipline = parseFloat(row.workmanship_discipline);
                });
            }
            alert(data.message);
        });

        // Save Practical Test (CA1 & CA2)
        document.getElementById('saveCaBtn').addEventListener('click', async () => {
            const testNo = document.getElementById('caTestSelect').value;
            const marksData = [];
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    writeup_procedure: tr.querySelector('.ca-w').value,
                    setup_execution: tr.querySelector('.ca-s').value,
                    observation_result: tr.querySelector('.ca-o').value,
                    viva_voce: tr.querySelector('.ca-v').value,
                    record_completion: tr.querySelector('.ca-r').value,
                    is_absent: tr.querySelector('.ca-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/practical-test`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ test_no: testNo, marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save OEE
        document.getElementById('saveOeeBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#oeeTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    originality_relevance: tr.querySelector('.oee-m1').value,
                    objectives_plan: tr.querySelector('.oee-m2').value,
                    execution_recording: tr.querySelector('.oee-m3').value,
                    analysis_presentation: tr.querySelector('.oee-m4').value,
                    teamwork_innovation: tr.querySelector('.oee-m5').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/oee`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save ESE
        document.getElementById('saveEseBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#eseTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    part_a_mcq: tr.querySelector('.ese-pa').value,
                    part_b_cad: tr.querySelector('.ese-pb').value,
                    part_c_viva: tr.querySelector('.ese-pc').value,
                    part_d_record: tr.querySelector('.ese-pd').value,
                    is_absent: tr.querySelector('.ese-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/ese`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Generate Lesson Plan Timeline (Single Batch)
        async function generateLessonTimeline() {
            if (!confirm(`Regenerate full 45-hour Drawing Lab lesson plan for single batch? Existing customized dates will be reset.`)) return;

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: 'single' })
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') {
                    window.location.reload();
                }
            } catch (e) {
                alert('Generation error: ' + e.message);
            }
        }

        // Add New Lesson Plan Row at Bottom
        function addLessonPlanRow() {
            const tbody = document.getElementById('lesson-plan-rows-container');
            if (!tbody) return;
            if (tbody.querySelector('td[colspan]')) {
                tbody.innerHTML = '';
            }
            const rowCount = tbody.querySelectorAll('.lesson-plan-row').length + 1;
            const newId = 'new_' + Date.now();

            const tr = document.createElement('tr');
            tr.className = 'lesson-plan-row';
            tr.dataset.id = newId;
            tr.innerHTML = `
                <td class="fw-bold text-center text-info lp-day-no">#${rowCount}</td>
                <td>
                    <input type="date" class="form-control form-control-custom form-control-sm lp-proposed">
                </td>
                <td>
                    <input type="date" class="form-control form-control-custom form-control-sm lp-actual">
                </td>
                <td>
                    <textarea class="growable-textarea lp-topic" rows="1" oninput="autoGrow(this); updateLpHoursTotal();" placeholder="Enter exercise topic or lesson content..."></textarea>
                </td>
                <td>
                    <select class="form-select form-control-custom form-select-sm lp-co">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                    </select>
                </td>
                <td>
                    <input type="text" value="1" class="form-control form-control-custom form-control-sm text-center lp-hours" inputmode="numeric" oninput="updateLpHoursTotal()">
                </td>
                <td>
                    <select class="form-select form-control-custom form-select-sm lp-pedagogy">
                        <option value="Drawing Lab Practical (P)">Drawing Lab Practical (P)</option>
                        <option value="Series Test Examination (CA1)">Series Test Examination (CA1)</option>
                        <option value="Series Test Examination (CA2)">Series Test Examination (CA2)</option>
                        <option value="Open-Ended Project (OEE)">Open-Ended Project (OEE)</option>
                        <option value="Drawing Lab Revision (P)">Drawing Lab Revision (P)</option>
                    </select>
                </td>
                <td>
                    <select class="form-select form-control-custom form-select-sm fw-bold lp-status">
                        <option value="Pending" class="text-warning">Pending</option>
                        <option value="Completed" class="text-success">Completed</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);

            tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            const textarea = tr.querySelector('.lp-topic');
            if (textarea) textarea.focus();
            updateLpHoursTotal();
        }

        // Recalculate Total Hours Badge (Only Count Rows With Content)
        function updateLpHoursTotal() {
            let total = 0;
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const topic = (row.querySelector('.lp-topic')?.value || '').trim();
                if (topic) {
                    const hrsInput = row.querySelector('.lp-hours');
                    total += parseInt(hrsInput?.value) || 0;
                }
            });
            const badge = document.getElementById('lpTotalHoursBadge');
            if (badge) {
                badge.innerText = `Total: ${total} Hours`;
            }
        }

        // Save Bulk Lesson Planner Entries (Discard Blank Rows)
        async function saveLessonPlannerBulk() {
            const plans = {};
            let count = 1;
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const topic = (row.querySelector('.lp-topic')?.value || '').trim();
                // Discard blank text rows
                if (!topic) return;

                const id = row.dataset.id;
                plans[id] = {
                    day_no: count,
                    proposed_date: row.querySelector('.lp-proposed').value,
                    actual_date: row.querySelector('.lp-actual').value,
                    topic_content: topic,
                    co_tag: row.querySelector('.lp-co').value,
                    allocated_hours: row.querySelector('.lp-hours').value,
                    pedagogy: row.querySelector('.lp-pedagogy').value,
                    status: row.querySelector('.lp-status').value
                };
                count++;
            });

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/lesson-plan/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message);
            } catch (e) {
                alert('Save error: ' + e.message);
            }
        }

        // Preview Survey Questionnaire Modal Handler
        function previewSurveyModal(type) {
            const title = type === 'exit' ? 'Course Exit Survey Questionnaire (CO Mapped - R26 Drawing Lab)' : 'Mid-Semester Feedback Questionnaire (R26 Drawing Lab)';
            document.getElementById('surveyPreviewTitle').innerHTML = `<i class="fa-solid fa-clipboard-question me-2"></i>${title}`;
            
            let html = `
                <div class="mb-3 p-3 rounded" style="background: rgba(6,182,212,0.12); border: 1px solid var(--accent-cyan);">
                    <div class="fw-bold text-info mb-1"><i class="fa-solid fa-circle-info me-1"></i> CO-Mapped Questionnaire Standard</div>
                    <small class="text-light">Students score each outcome on a 3-Point Likert Scale: <strong>3 = High / Excellent</strong>, <strong>2 = Moderate / Good</strong>, <strong>1 = Low / Basic</strong>.</small>
                </div>
                <div class="list-group">
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-cyan mb-1"><i class="fa-solid fa-compass me-1"></i> CO1: Manual Geometrical Drawing & Constructions</div>
                        <div class="ps-3 border-start border-cyan small">
                            <div class="mb-1">1. Rate your ability to manually construct regular polygons, conic sections, and developments.</div>
                            <div>2. Rate instructor step-by-step guidance and demonstration during manual sheet exercises.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-warning mb-1"><i class="fa-solid fa-cube me-1"></i> CO2: Orthographic Projections & Sectional Views</div>
                        <div class="ps-3 border-start border-warning small">
                            <div class="mb-1">3. Rate your clarity on 1st & 3rd angle projection principles and sectional views.</div>
                            <div>4. Rate the timeliness of feedback during continuous slot evaluation of drawing sheets.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary mb-2 rounded">
                        <div class="fw-bold text-success mb-1"><i class="fa-solid fa-laptop-code me-1"></i> CO3: CAD Software Interface & Commands</div>
                        <div class="ps-3 border-start border-success small">
                            <div class="mb-1">5. Rate your proficiency in using CAD draw/modify tools, layer management, and dimensioning.</div>
                            <div>6. Rate the availability and performance of CAD workstation hardware/software facilities.</div>
                        </div>
                    </div>
                    <div class="list-group-item bg-dark text-light border-secondary rounded">
                        <div class="fw-bold text-danger mb-1"><i class="fa-solid fa-draw-polygon me-1"></i> CO4: 2D Component Drafting & Sectional Plotting</div>
                        <div class="ps-3 border-start border-danger small">
                            <div class="mb-1">7. Rate your confidence in generating 2D orthographic component drawings & sectional views in CAD.</div>
                            <div>8. Rate overall satisfaction with the 45-hour Drawing Lab curriculum delivery and outcomes.</div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('surveyPreviewBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('surveyPreviewModal'));
            modal.show();
        }

        // Initiate Survey Action & Send Notification to Student Panel
        async function initiateSurveyAction(type) {
            const url = type === 'exit' ? `/api/r26/classroom/${subjectId}/exit-survey/initiate` : `/api/r26/classroom/${subjectId}/midsem-survey/initiate`;
            const label = type === 'exit' ? 'Course Exit Survey' : 'Mid-Semester Survey';
            
            if (!confirm(`Initiate ${label}? The survey notification will immediately appear on all student dashboard panels.`)) return;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    }
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error initiating survey: ' + e.message);
            }
        }

        // Close Active Survey Action
        async function closeSurveyAction(type) {
            const url = type === 'exit' ? `/api/r26/classroom/${subjectId}/exit-survey/close` : `/api/r26/classroom/${subjectId}/midsem-survey/close`;
            const label = type === 'exit' ? 'Course Exit Survey' : 'Mid-Semester Survey';
            
            if (!confirm(`Close ${label}? Student panel notifications will be closed and response collection finalized.`)) return;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    }
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error closing survey: ' + e.message);
            }
        }

        // Open Editable Drawing Course Exit Survey Modal
        function openExitInitModalDrawing() {
            const modalElement = document.getElementById('drawingExitSurveyInitModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            }
        }

        // Submit Edited Drawing Exit Survey Questions & Initiate
        async function submitDrawingExitInit(event) {
            event.preventDefault();
            const questions = {
                q1: document.getElementById('drg-ex-q1').value.trim(),
                q2: document.getElementById('drg-ex-q2').value.trim(),
                q3: document.getElementById('drg-ex-q3').value.trim(),
                q4: document.getElementById('drg-ex-q4').value.trim(),
                q5: document.getElementById('drg-ex-q5').value.trim(),
                q6: document.getElementById('drg-ex-q6').value.trim(),
                q7: document.getElementById('drg-ex-q7').value.trim(),
                q8: document.getElementById('drg-ex-q8').value.trim()
            };

            if (!confirm('Initiate Course Exit Survey with these edited questions? Student notifications will be sent immediately.')) return;

            try {
                const res = await fetch(`/api/r26/classroom/${subjectId}/exit-survey/initiate`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify({ questions })
                });
                const data = await res.json();
                alert(data.message);
                if (data.status === 'SUCCESS') window.location.reload();
            } catch (e) {
                alert('Error initiating survey: ' + e.message);
            }
        }
    </script>

    <!-- Survey Questionnaire Preview Modal -->
    <div class="modal fade" id="surveyPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-card border-secondary text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-info" id="surveyPreviewTitle"><i class="fa-solid fa-clipboard-question me-2"></i>Survey Questionnaire Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="surveyPreviewBody">
                    <!-- Dynamic Preview Content -->
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close Preview</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Editable Drawing Course Exit Survey Questionnaire Modal -->
    <div class="modal fade" id="drawingExitSurveyInitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content glass-card border-secondary text-light">
                <div class="modal-header border-secondary">
                    <div>
                        <h5 class="modal-title fw-bold text-cyan"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Course Exit Survey Questionnaire (CO-Mapped)</h5>
                        <small class="text-muted">Faculty can edit and customize all 8 CO questions before publishing to students.</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="drawingExitSurveyForm" onsubmit="submitDrawingExitInit(event)">
                        <div class="mb-3 p-3 rounded" style="background: rgba(6,182,212,0.12); border: 1px solid var(--accent-cyan);">
                            <div class="fw-bold text-info mb-1"><i class="fa-solid fa-circle-info me-1"></i> CO-Mapped Questionnaire Standard</div>
                            <small class="text-light">Customize or edit question wording below before initiating. Students evaluate each CO question on a 3-Point Likert Scale (3 = High, 2 = Medium, 1 = Low).</small>
                        </div>

                        <div class="row g-3">
                            <!-- CO1 Questions -->
                            <div class="col-12">
                                <div class="p-3 rounded bg-dark border border-secondary">
                                    <div class="fw-bold text-cyan mb-2"><i class="fa-solid fa-compass me-1"></i> CO1: Manual Geometrical Drawing & Constructions</div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold mb-1">Question 1 (CO1 - Manual Constructions)</label>
                                        <input type="text" id="drg-ex-q1" class="form-control bg-dark text-light border-secondary form-control-sm" value="1. Rate your ability to manually construct regular polygons, conic sections, and developments." required>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small fw-bold mb-1">Question 2 (CO1 - Step-by-Step Guidance)</label>
                                        <input type="text" id="drg-ex-q2" class="form-control bg-dark text-light border-secondary form-control-sm" value="2. Rate instructor step-by-step guidance and demonstration during manual sheet exercises." required>
                                    </div>
                                </div>
                            </div>

                            <!-- CO2 Questions -->
                            <div class="col-12">
                                <div class="p-3 rounded bg-dark border border-secondary">
                                    <div class="fw-bold text-warning mb-2"><i class="fa-solid fa-cube me-1"></i> CO2: Orthographic Projections & Sectional Views</div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold mb-1">Question 3 (CO2 - Projection Principles)</label>
                                        <input type="text" id="drg-ex-q3" class="form-control bg-dark text-light border-secondary form-control-sm" value="3. Rate your clarity on 1st & 3rd angle projection principles and sectional views." required>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small fw-bold mb-1">Question 4 (CO2 - Slot Feedback)</label>
                                        <input type="text" id="drg-ex-q4" class="form-control bg-dark text-light border-secondary form-control-sm" value="4. Rate the timeliness of feedback during continuous slot evaluation of drawing sheets." required>
                                    </div>
                                </div>
                            </div>

                            <!-- CO3 Questions -->
                            <div class="col-12">
                                <div class="p-3 rounded bg-dark border border-secondary">
                                    <div class="fw-bold text-success mb-2"><i class="fa-solid fa-laptop-code me-1"></i> CO3: CAD Software Interface & Commands</div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold mb-1">Question 5 (CO3 - CAD Tools)</label>
                                        <input type="text" id="drg-ex-q5" class="form-control bg-dark text-light border-secondary form-control-sm" value="5. Rate your proficiency in using CAD draw/modify tools, layer management, and dimensioning." required>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small fw-bold mb-1">Question 6 (CO3 - Workstation Facilities)</label>
                                        <input type="text" id="drg-ex-q6" class="form-control bg-dark text-light border-secondary form-control-sm" value="6. Rate the availability and performance of CAD workstation hardware/software facilities." required>
                                    </div>
                                </div>
                            </div>

                            <!-- CO4 Questions -->
                            <div class="col-12">
                                <div class="p-3 rounded bg-dark border border-secondary">
                                    <div class="fw-bold text-danger mb-2"><i class="fa-solid fa-draw-polygon me-1"></i> CO4: 2D Component Drafting & Sectional Plotting</div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold mb-1">Question 7 (CO4 - 2D Component Drafting)</label>
                                        <input type="text" id="drg-ex-q7" class="form-control bg-dark text-light border-secondary form-control-sm" value="7. Rate your confidence in generating 2D orthographic component drawings & sectional views in CAD." required>
                                    </div>
                                    <div>
                                        <label class="form-label text-muted small fw-bold mb-1">Question 8 (CO4 - Overall Satisfaction)</label>
                                        <input type="text" id="drg-ex-q8" class="form-control bg-dark text-light border-secondary form-control-sm" value="8. Rate overall satisfaction with the 45-hour Drawing Lab curriculum delivery and outcomes." required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-cyan font-bold px-4">
                                <i class="fa-solid fa-paper-plane me-1"></i> Initiate & Send to Student Portal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Bank & Question Paper Editor Modal -->
    <div class="modal fade" id="questionBankModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-secondary text-light" style="background-color: #0f172a;">
                <div class="modal-header border-secondary py-2 px-3 align-items-center" style="background-color: #1e293b;">
                    <h6 class="modal-title fw-bold text-warning mb-0" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-pen-to-square me-1"></i>Question Bank & Series Test Paper Manager
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" style="background-color: #0f172a;">
                    <!-- Ultra-Compact High-Contrast Header Controls -->
                    <div class="row g-2 mb-3 align-items-center p-2 rounded" style="background-color: #1e293b; border: 1px solid #334155;">
                        <div class="col-md-3">
                            <label class="form-label text-cyan fw-bold mb-1" style="font-size: 0.75rem;">Series Test Exam</label>
                            <select class="form-select form-select-sm text-light fw-semibold" id="qbModalTestNoSelect" style="background-color: #0f172a; border: 1px solid #0284c7; font-size: 0.8rem;" onchange="loadQuestionBankData(this.value)">
                                <option value="1">Series Test 1 (Manual Drawing)</option>
                                <option value="2">Series Test 2 (CAD Exam)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-cyan fw-bold mb-1" style="font-size: 0.75rem;">Paper Title</label>
                            <input type="text" class="form-control form-control-sm text-light fw-semibold" id="qbTestTitleInput" style="background-color: #0f172a; border: 1px solid #334155; font-size: 0.8rem;">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-cyan fw-bold mb-1" style="font-size: 0.75rem;">Instructions to Candidates</label>
                            <input type="text" class="form-control form-control-sm text-light" id="qbInstructionsInput" style="background-color: #0f172a; border: 1px solid #334155; font-size: 0.8rem;">
                        </div>
                    </div>

                    <div id="qbQuestionsEditorContainer">
                        <!-- Dynamic Question Cards -->
                    </div>
                </div>
                <div class="modal-footer border-secondary py-2 px-3 justify-content-between" style="background-color: #1e293b;">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-cyan btn-sm py-1 px-3 fw-bold" onclick="saveQuestionBankData()">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save to Question Bank & Update QP
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQpData = null;
        let currentTestNo = 1;

        async function openQuestionBankModal() {
            const testNo = document.getElementById('caTestSelect').value === 'CA2' ? 2 : 1;
            currentTestNo = testNo;
            document.getElementById('qbModalTestNoSelect').value = testNo;
            await loadQuestionBankData(testNo);
            const modal = new bootstrap.Modal(document.getElementById('questionBankModal'));
            modal.show();
        }

        async function loadQuestionBankData(testNo) {
            currentTestNo = testNo;
            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/series-qp/${testNo}`);
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    currentQpData = data.data;
                    renderQuestionBankEditor();
                }
            } catch (e) {
                alert('Failed to load Question Bank data: ' + e.message);
            }
        }

        function renderQuestionBankEditor() {
            if (!currentQpData) return;
            document.getElementById('qbTestTitleInput').value = currentQpData.test_title || '';
            document.getElementById('qbInstructionsInput').value = currentQpData.instructions || '';
            
            let html = '';

            const partsList = (currentQpData.parts && currentQpData.parts.length > 0) 
                ? currentQpData.parts 
                : [{ part_name: 'QUESTIONS', part_instructions: '', total_marks: currentQpData.max_marks || 40, questions: currentQpData.questions || [] }];

            partsList.forEach((part, partIndex) => {
                if (currentQpData.parts) {
                    html += `
                        <div class="p-2 mb-3 rounded fw-bold text-cyan font-monospace d-flex justify-content-between align-items-center" style="background: #0f172a; border-left: 4px solid #06b6d4; border-top: 1px solid #1e293b; border-right: 1px solid #1e293b; border-bottom: 1px solid #1e293b;">
                            <span><i class="fa-solid fa-layer-group me-2"></i>${part.part_name} (${part.total_marks} MARKS)</span>
                            <span class="text-muted small fw-normal">${part.part_instructions}</span>
                        </div>
                    `;
                }

                part.questions.forEach((q, qIndex) => {
                    const isCompulsory = q.option_a && q.option_b && 
                        (q.option_a.title === q.option_b.title || q.option_a.title.toLowerCase().includes('compulsory'));

                    html += `
                        <div class="mb-4 p-3 rounded" style="background: #1e293b; border-left: 4px solid ${isCompulsory ? '#10b981' : '#0284c7'};">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                                <h6 class="fw-bold text-info mb-0">${q.q_no} — [${q.module} | ${q.co}] (Max ${q.total_marks} Marks)</h6>
                                ${isCompulsory ? '<span class="badge bg-success font-monospace">COMPULSORY QUESTION</span>' : '<span class="badge bg-primary font-monospace">EITHER / OR CHOICE</span>'}
                            </div>
                            
                            <!-- Option A -->
                            <div class="mb-3">
                                ${!isCompulsory ? `
                                    <div class="fw-bold text-cyan mb-2"><i class="fa-solid fa-file-circle-check me-1"></i> OPTION A (Choice 1)</div>
                                    <div class="mb-2">
                                        <label class="form-label text-light small fw-semibold">Heading / Title</label>
                                        <input type="text" class="form-control form-control-sm text-light" style="background-color: #0f172a; border: 1px solid #334155;"
                                            value="${escapeHtml(q.option_a.title)}" onchange="updateQpData(${partIndex}, ${qIndex}, 'option_a', 'title', null, this.value)">
                                    </div>
                                ` : ''}
                                ${q.option_a.sub_questions.map((sub, sIndex) => `
                                    <div class="mb-3 p-3 rounded" style="background: #0f172a;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-cyan small">Sub-Question ${sub.sub_no}</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="text-muted small">Marks:</span>
                                                <input type="number" class="form-control form-control-sm text-light text-center" style="width: 60px; background-color: #1e293b; border: 1px solid #475569;"
                                                    value="${sub.marks}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_a', ${sIndex}, 'marks', this.value)">
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small mb-0">Question Statement</label>
                                            <textarea class="form-control form-control-sm text-light" rows="2" style="background-color: #1e293b; border: 1px solid #475569;"
                                                onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_a', ${sIndex}, 'text', this.value)">${escapeHtml(sub.text)}</textarea>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-0">Valuation Scheme</label>
                                                <input type="text" class="form-control form-control-sm text-light" style="background-color: #1e293b; border: 1px solid #475569;"
                                                    value="${escapeHtml(sub.scheme)}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_a', ${sIndex}, 'scheme', this.value)">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-0">Model Answer Key</label>
                                                <input type="text" class="form-control form-control-sm text-light" style="background-color: #1e293b; border: 1px solid #475569;"
                                                    value="${escapeHtml(sub.answer_key)}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_a', ${sIndex}, 'answer_key', this.value)">
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>

                            ${!isCompulsory ? `
                                <!-- Choice Divider -->
                                <div class="text-center font-monospace text-warning fw-bold my-3 small">--- OR (EITHER OPTION A OR OPTION B) ---</div>

                                <!-- Option B -->
                                <div>
                                    <div class="fw-bold text-warning mb-2"><i class="fa-solid fa-file-circle-check me-1"></i> OPTION B (Choice 2)</div>
                                    <div class="mb-2">
                                        <label class="form-label text-light small fw-semibold">Heading / Title</label>
                                        <input type="text" class="form-control form-control-sm text-light" style="background-color: #0f172a; border: 1px solid #334155;"
                                            value="${escapeHtml(q.option_b.title)}" onchange="updateQpData(${partIndex}, ${qIndex}, 'option_b', 'title', null, this.value)">
                                    </div>
                                    ${q.option_b.sub_questions.map((sub, sIndex) => `
                                        <div class="mb-3 p-3 rounded" style="background: #0f172a;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold text-warning small">Sub-Question ${sub.sub_no}</span>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-muted small">Marks:</span>
                                                    <input type="number" class="form-control form-control-sm text-light text-center" style="width: 60px; background-color: #1e293b; border: 1px solid #475569;"
                                                        value="${sub.marks}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_b', ${sIndex}, 'marks', this.value)">
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label text-muted small mb-0">Question Statement</label>
                                                <textarea class="form-control form-control-sm text-light" rows="2" style="background-color: #1e293b; border: 1px solid #475569;"
                                                    onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_b', ${sIndex}, 'text', this.value)">${escapeHtml(sub.text)}</textarea>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-0">Valuation Scheme</label>
                                                    <input type="text" class="form-control form-control-sm text-light" style="background-color: #1e293b; border: 1px solid #475569;"
                                                        value="${escapeHtml(sub.scheme)}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_b', ${sIndex}, 'scheme', this.value)">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-0">Model Answer Key</label>
                                                    <input type="text" class="form-control form-control-sm text-light" style="background-color: #1e293b; border: 1px solid #475569;"
                                                        value="${escapeHtml(sub.answer_key)}" onchange="updateSubQpData(${partIndex}, ${qIndex}, 'option_b', ${sIndex}, 'answer_key', this.value)">
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
            });
            document.getElementById('qbQuestionsEditorContainer').innerHTML = html;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function updateQpData(partIndex, qIndex, optKey, field, subIndex, val) {
            if (currentQpData) {
                const targetQ = currentQpData.parts 
                    ? currentQpData.parts[partIndex].questions[qIndex] 
                    : currentQpData.questions[partIndex]; // fallback when no parts
                if (targetQ) {
                    targetQ[optKey][field] = val;
                }
            }
        }

        function updateSubQpData(partIndex, qIndex, optKey, subIndex, field, val) {
            if (currentQpData) {
                const targetQ = currentQpData.parts 
                    ? currentQpData.parts[partIndex].questions[qIndex] 
                    : currentQpData.questions[partIndex]; // fallback when no parts
                if (targetQ) {
                    if (field === 'marks') val = parseFloat(val) || 0;
                    targetQ[optKey].sub_questions[subIndex][field] = val;
                }
            }
        }

        async function saveQuestionBankData() {
            if (!currentQpData) return;
            currentQpData.test_title = document.getElementById('qbTestTitleInput').value;
            currentQpData.instructions = document.getElementById('qbInstructionsInput').value;

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/series-qp/save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        test_no: currentTestNo,
                        payload: currentQpData
                    })
                });
                const data = await res.json();
                alert(data.message);
            } catch (e) {
                alert('Error saving Question Bank: ' + e.message);
            }
        }

        // Fullscreen Toggle Logic
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    updateFullscreenBtnUI();
                }).catch(err => {
                    console.error('Error attempting to enable fullscreen:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        updateFullscreenBtnUI();
                    });
                }
            }
        }

        function updateFullscreenBtnUI() {
            const btn = document.getElementById('fullscreenToggleBtn');
            if (!btn) return;
            if (document.fullscreenElement) {
                btn.innerHTML = '<i class="fa-solid fa-compress me-1 text-warning"></i> <span class="d-none d-sm-inline">Exit Fullscreen</span>';
            } else {
                btn.innerHTML = '<i class="fa-solid fa-expand me-1 text-info"></i> <span class="d-none d-sm-inline">Fullscreen</span>';
            }
        }

        document.addEventListener('fullscreenchange', updateFullscreenBtnUI);

        // Add New Exercise Handler
        document.getElementById('confirmAddExBtn')?.addEventListener('click', function() {
            const title = document.getElementById('newExTitle').value.trim();
            const module = document.getElementById('newExModule').value;
            const co_id = document.getElementById('newExCo').value;
            const hours = document.getElementById('newExHours').value;

            if (!title) {
                alert('Please enter an exercise title.');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';

            fetch('/api/r26/classroom/drawing/{{ $batchSubject->id }}/exercises/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title, module, co_id, hours })
            })
            .then(res => res.json())
            .then(data => {
                this.disabled = false;
                this.innerHTML = '<i class="fa-solid fa-check me-1"></i> Add & Select';
                if (data.status === 'SUCCESS') {
                    const select = document.getElementById('ceExerciseSelect');
                    const newEx = data.new_exercise;
                    const opt = document.createElement('option');
                    opt.value = newEx.exercise_no;
                    opt.textContent = `${newEx.exercise_no}: ${newEx.title}`;
                    opt.selected = true;
                    select.appendChild(opt);

                    const modalEl = document.getElementById('addExerciseModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    document.getElementById('addExerciseForm').reset();

                    select.dispatchEvent(new Event('change'));
                    alert('Exercise added successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                this.disabled = false;
                this.innerHTML = '<i class="fa-solid fa-check me-1"></i> Add & Select';
                alert('Network error adding exercise.');
            });
        });
    </script>

    <!-- MODAL: ADD NEW DRAWING EXERCISE -->
    <div class="modal fade" id="addExerciseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content text-light border-0 shadow-lg" style="background: #0f172a; border-radius: 12px;">
                <div class="modal-header border-bottom border-secondary px-4 py-3" style="background: #1e293b; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title fw-bold text-info"><i class="fa-solid fa-plus-circle me-2"></i>Add New Drawing Exercise / CAD Sheet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="addExerciseForm">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold mb-1">Exercise Title / Task Description</label>
                            <textarea class="form-control text-light" id="newExTitle" rows="3" placeholder="e.g. Drawing Sheet 10 - Isometric Projections of Truncated Solids" style="background-color: #1e293b; color: #f8fafc; border: 1.5px solid #475569; resize: vertical;" required></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-light fw-bold mb-1">Module</label>
                                <select class="form-select text-light" id="newExModule" style="background-color: #1e293b; color: #f8fafc; border: 1.5px solid #475569;">
                                    <option value="Module I" style="background: #0f172a; color: #fff;">Module I</option>
                                    <option value="Module II" style="background: #0f172a; color: #fff;">Module II</option>
                                    <option value="Module III" style="background: #0f172a; color: #fff;">Module III</option>
                                    <option value="Module IV" style="background: #0f172a; color: #fff;">Module IV</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light fw-bold mb-1">Mapped CO</label>
                                <select class="form-select text-light" id="newExCo" style="background-color: #1e293b; color: #f8fafc; border: 1.5px solid #475569;">
                                    <option value="CO1" style="background: #0f172a; color: #fff;">CO1</option>
                                    <option value="CO2" style="background: #0f172a; color: #fff;">CO2</option>
                                    <option value="CO3" style="background: #0f172a; color: #fff;">CO3</option>
                                    <option value="CO4" style="background: #0f172a; color: #fff;">CO4</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light fw-bold mb-1">Duration (Hours)</label>
                                <input type="number" class="form-control text-light" id="newExHours" value="3.0" step="0.5" min="1" style="background-color: #1e293b; color: #f8fafc; border: 1.5px solid #475569;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top border-secondary px-4 py-3" style="background: #1e293b; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-cyan font-bold" id="confirmAddExBtn"><i class="fa-solid fa-check me-1"></i> Add & Select</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
