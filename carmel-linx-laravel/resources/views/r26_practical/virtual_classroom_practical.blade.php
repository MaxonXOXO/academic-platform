<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}] Practical Virtual Lab - {{ $batchSubject->subject_name }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-base:       #0f1117;
            --bg-card:       #1a1d27;
            --bg-card-hover: #1f2233;
            --bg-stripe:     #161923;
            --border:        #2a2d3e;
            --border-light:  #323552;
            --text-primary:  #e2e5f0;
            --text-secondary:#a0a7be;
            --text-muted:    #5a6180;
            --accent:        #4f6ef7;
            --accent-dim:    rgba(79,110,247,0.12);
            --accent-hover:  #5c7aff;
            --success:       #30d158;
            --warning:       #ffa340;
            --danger:        #ff5757;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.55;
            background-color: var(--bg-base);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6, .font-heading, span, p, label, button, a, th, td {
            text-shadow: none !important;
            filter: none !important;
        }

        /* ── Cards & Panels ───────────────────────────────── */
        .vl-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .vl-card-inner {
            background: var(--bg-stripe);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        /* backward compat – old glass-panel wrappers become flat cards */
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        /* ── Typography ───────────────────────────────────── */
        .vl-page-title   { font-size: 15px; font-weight: 600; color: var(--text-primary); letter-spacing: -.01em; }
        .vl-section-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted); }
        .vl-body          { font-size: 13px; color: var(--text-secondary); }
        .vl-caption       { font-size: 11px; color: var(--text-muted); }

        /* ── Header ───────────────────────────────────────── */
        #vl-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        #vl-header .row1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
        }
        #vl-header .row2 {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 7px 16px;
            border-top: 1px solid var(--border);
            background: var(--bg-stripe);
        }

        .vl-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
            white-space: nowrap;
        }
        .vl-badge-blue  { background: var(--accent); color: #fff; }
        .vl-badge-slate { background: #242740; border: 1px solid var(--border-light); color: var(--text-secondary); }
        .vl-badge-green { background: rgba(48,209,88,.12); border: 1px solid rgba(48,209,88,.25); color: #30d158; }

        /* ── Buttons ──────────────────────────────────────── */
        .vl-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s, border-color .15s;
            border: 1px solid var(--border-light);
            background: #242740;
            color: var(--text-secondary);
        }
        .vl-btn:hover { background: #2c3050; border-color: #444870; color: var(--text-primary); }
        .vl-btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .vl-btn-primary:hover { background: var(--accent-hover); border-color: var(--accent-hover); color: #fff; }
        .vl-btn-sm { padding: 5px 10px; font-size: 12px; }
        .vl-btn-icon { padding: 7px 10px; }

        /* ── Sidebar Navigation ───────────────────────────── */
        #workspaceSidebar {
            width: 260px;
            flex-shrink: 0;
            transition: width .25s ease, opacity .25s ease;
        }
        #workspaceSidebar.sidebar-collapsed {
            width: 0;
            opacity: 0;
            overflow: hidden;
            padding: 0;
        }

        .sidebar-nav-group {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .sidebar-nav-header {
            padding: 10px 14px;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            background: var(--bg-stripe);
        }
        .tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            background: transparent;
            border: 1.5px solid transparent;
            border-radius: 6px;
            margin-bottom: 3px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
        }
        .tab-btn:hover { background: rgba(30, 41, 59, 0.6); color: #f1f5f9; }
        .tab-btn.active {
            background: #0f172a !important;
            color: #ffffff !important;
            border: 1.5px solid #38bdf8 !important;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.25) !important;
            font-weight: 700 !important;
        }
        .tab-btn .tab-icon {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            font-size: 13px;
        }
        .tab-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 4px;
            background: #242740;
            color: var(--text-muted);
            border: 1px solid var(--border);
            flex-shrink: 0;
        }
        .tab-badge-green {
            background: rgba(48,209,88,.1);
            border-color: rgba(48,209,88,.2);
            color: #30d158;
        }

        /* ── Tab Content Panels ───────────────────────────── */
        .tab-content { display: none; }
        .tab-content.active-tab { display: block; }
        /* Override old Tailwind 'hidden' toggle */
        .tab-content:not(.hidden) { display: block; }
        .tab-content.hidden { display: none !important; }

        /* Tactile click feel for experiment inventory buttons */
        .exp-btn-touch {
            transition: transform 0.1s ease, filter 0.15s ease, box-shadow 0.15s ease;
            user-select: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .exp-btn-touch:hover {
            filter: brightness(1.18);
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }
        .exp-btn-touch:active {
            transform: scale(0.94);
            filter: brightness(0.9);
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* ── Tables ───────────────────────────────────────── */
        .vl-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .vl-table th {
            padding: 9px 12px;
            background: var(--bg-stripe);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            border: 1px solid var(--border);
            white-space: nowrap;
        }
        .vl-table td {
            padding: 9px 12px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            vertical-align: middle;
        }
        .vl-table tbody tr:nth-child(even) td { background: var(--bg-stripe); }
        .vl-table tbody tr:hover td { background: var(--bg-card-hover); }

        /* ── Stat Cards ───────────────────────────────────── */
        .vl-stat-card {
            background: var(--bg-stripe);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
        }
        .vl-stat-label { font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); }
        .vl-stat-value { font-size: 18px; font-weight: 600; color: var(--text-primary); margin-top: 2px; }

        /* ── CO-PO Matrix badges ──────────────────────────── */
        .copo-3 { background: rgba(255,87,87,.15); color: #ff8f8f; border: 1px solid rgba(255,87,87,.25); }
        .copo-2 { background: rgba(255,163,64,.15); color: #ffb96b; border: 1px solid rgba(255,163,64,.25); }
        .copo-1 { background: rgba(79,110,247,.15); color: #849df9; border: 1px solid rgba(79,110,247,.25); }
        .copo-dash { color: #3a3f5c; }

        /* ── Range Sliders ────────────────────────────────── */
        input[type=range] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: var(--border-light);
            outline: none;
            cursor: pointer;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--accent);
            cursor: pointer;
            box-shadow: none;
            transition: transform .12s;
        }
        input[type=range]::-webkit-slider-thumb:active { transform: scale(1.12); }

        /* ── Inputs / Selects ─────────────────────────────── */
        .vl-input {
            background: var(--bg-base);
            border: 1px solid var(--border-light);
            color: var(--text-primary);
            border-radius: 7px;
            padding: 7px 10px;
            font-size: 13px;
            font-family: inherit;
            width: 100%;
            transition: border-color .12s;
        }
        .vl-input:focus { outline: none; border-color: var(--accent); }

        /* ── Misc ─────────────────────────────────────────── */
        .vl-divider { border: none; border-top: 1px solid var(--border); margin: 16px 0; }
        .transition-premium { transition: all .25s cubic-bezier(.4,0,.2,1); }

        /* ── Mobile Responsive ────────────────────────────── */
        @media (max-width: 768px) {
            #workspaceSidebar {
                width: 100%;
            }
            #workspaceSidebar.sidebar-collapsed {
                width: 100%;
                max-height: 0;
                opacity: 0;
                overflow: hidden;
            }
            .vl-stat-value { font-size: 15px; }
            .tab-btn { padding: 12px 14px; font-size: 14px; } /* touch-friendly */
        }
    </style>
</head>
<body style="min-height:100vh;display:flex;flex-direction:column;overflow-x:hidden;">

    <!-- ═══ HEADER ═══════════════════════════════════════════════════════ -->
    <header id="vl-header">
        <!-- Row 1: Badge · Subject Name · Actions · Back Button -->
        <div class="row1">
            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                <div style="display:inline-flex;align-items:center;gap:6px;font-weight:800;color:#ffffff;font-size:15px;letter-spacing:-.01em;flex-shrink:0;">
                    <i class="fa-solid fa-graduation-cap" style="color:#38bdf8;font-size:16px;"></i>
                    <span>Carmel Linx</span>
                </div>
                <span style="color:#475569;font-weight:700;">|</span>
                <span class="vl-badge vl-badge-blue" style="font-size:13.5px;font-weight:800;letter-spacing:.06em;padding:5px 12px;border-radius:7px;">
                    <i class="fa-solid fa-flask-vial" style="font-size:14px;"></i> VIRTUAL LAB · {{ (str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), '2021') || str_contains(strtoupper($batchSubject->syllabus_revision_code ?? ''), 'R21')) ? 'R-2021' : 'R-2026' }}
                </span>

                <span class="vl-badge" id="header-subject-code" style="font-family:monospace;font-size:14px;font-weight:800;padding:5px 12px;border-radius:7px;color:#38bdf8;background:rgba(56,189,248,0.12);border:1px solid rgba(56,189,248,0.3);">
                    {{ $batchSubject->subject_code }}
                </span>

                <h1 id="header-subject-name" style="font-size:18px;font-weight:700;color:#ffffff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0;letter-spacing:-.01em;">
                    {{ $practicalCourseFile?->course_title ?: $batchSubject->subject_name }}
                </h1>
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <button onclick="toggleFullscreen()" id="fullscreen-btn" class="exp-btn-touch" style="padding:7px 13px;background:rgba(6,182,212,.2);border:1px solid rgba(6,182,212,.5);color:#67e8f9;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;" title="Toggle Fullscreen">
                    <i class="fa-solid fa-expand" id="fullscreen-icon" style="color:#22d3ee;"></i>
                    <span class="hide-xs">Fullscreen</span>
                </button>
                <button onclick="toggleSidebar()" id="sidebar-toggle-btn" class="exp-btn-touch" style="padding:7px 13px;background:rgba(147,51,234,.2);border:1px solid rgba(147,51,234,.5);color:#c084fc;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;" title="Toggle Sidebar">
                    <i class="fa-solid fa-table-columns" style="color:#a855f7;"></i>
                    <span class="hide-xs">Sidebar</span>
                </button>
                <a href="/r26/classroom/practical/course-file/{{ $batchSubject->id }}" target="_blank" class="exp-btn-touch" style="padding:7px 13px;background:rgba(245,158,11,.2);border:1px solid rgba(245,158,11,.5);color:#fcd34d;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;text-decoration:none;" title="Course File">
                    <i class="fa-solid fa-folder-open" style="color:#fbbf24;"></i>
                    <span class="hide-xs">Course File</span>
                </a>
                <a href="javascript:void(0)" onclick="handleVirtualLabBack(event)" class="exp-btn-touch" title="Dashboard / Close Tab" style="padding:5px 10px;background:#f59e0b;border:1px solid #fbbf24;color:#0f172a;border-radius:6px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:5px;text-decoration:none;box-shadow:0 2px 6px rgba(245,158,11,0.3);">
                    <i class="fa-solid fa-arrow-left" style="font-size:11px;color:#0f172a;"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Row 2: Lecturer · Branch · Batch · Semester · Parse Mode -->
        <!-- Row 2: Staff Assignments (Lecturer, Demonstrator, etc.) · Branch · Batch · Semester · Parse Mode -->
        <div class="row2">
            @if(isset($assignedStaffList) && count($assignedStaffList) > 0)
                @foreach($assignedStaffList as $st)
                <span class="vl-badge vl-badge-slate" style="border-left:2px solid var(--accent);">
                    <i class="fa-solid fa-chalkboard-user" style="color:var(--accent);"></i>
                    <span style="color:var(--text-muted);">{{ $st['designation'] }}:</span>
                    <strong style="color:var(--text-primary);font-weight:600;">{{ $st['name'] }}</strong>
                </span>
                @endforeach
            @else
                <span class="vl-badge vl-badge-slate">
                    <i class="fa-solid fa-chalkboard-user" style="color:var(--text-muted);"></i>
                    <span style="color:var(--text-muted);">Lecturer:</span>
                    <strong style="color:var(--text-primary);font-weight:600;">{{ $staff?->name ?? 'N/A' }}</strong>
                </span>
            @endif
            <span class="vl-badge vl-badge-slate">
                <i class="fa-solid fa-code-branch" style="color:var(--text-muted);"></i>
                <span style="color:var(--text-muted);">Branch:</span>
                <strong style="color:var(--text-primary);font-weight:600;">{{ $classroom?->branch ?? '—' }}</strong>
            </span>
            <span class="vl-badge vl-badge-slate">
                <i class="fa-solid fa-users" style="color:var(--text-muted);"></i>
                <span style="color:var(--text-muted);">Batch:</span>
                <strong style="color:var(--text-primary);font-weight:600;">{{ $classroom?->batch_year ?? '—' }}</strong>
            </span>
            <span class="vl-badge vl-badge-slate">
                <i class="fa-solid fa-calendar" style="color:var(--text-muted);"></i>
                <span style="color:var(--text-muted);">Sem:</span>
                <strong style="color:var(--text-primary);font-weight:600;">{{ $batchSubject->semester }}</strong>
            </span>
            <span class="vl-badge" style="margin-left:auto;background:rgba(245,158,11,0.14);border:1px solid rgba(245,158,11,0.4);color:#fbbf24;font-weight:700;display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:6px;box-shadow:0 0 10px rgba(245,158,11,0.15);" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks.">
                <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;box-shadow:0 0 8px #f59e0b;display:inline-block;"></span>
                <span style="color:#fcd34d;">Parse:</span>
                <strong style="color:#fef08a;font-weight:800;">{{ $parseModeLabel }}</strong>
            </span>
        </div>


    <!-- ═══ MAIN WORKSPACE ════════════════════════════════════════════════ -->

    <div style="display:flex;flex:1;gap:0;overflow:hidden;">

        <!-- ── Sidebar ──────────────────────────────────────── -->
        <aside id="workspaceSidebar" style="display:flex;flex-direction:column;gap:12px;padding:14px;overflow-y:auto;">

            <!-- Navigation -->
            <div class="sidebar-nav-group" style="border: 1.5px solid rgba(99, 102, 241, 0.45); border-left: 4px solid #6366f1; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                <div class="sidebar-nav-header" style="display:flex;align-items:center;gap:6px;color:#a5b4fc;font-weight:700;">
                    <i class="fa-solid fa-cubes"></i> Workspace Modules
                </div>

                <button type="button" onclick="switchTab('outline')" id="btn-outline" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-file-pdf tab-icon"></i> Course Outline / Syllabus
                    </span>
                </button>
                <button type="button" onclick="switchTab('experiments')" id="btn-experiments" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-vials tab-icon"></i> List of Experiments
                    </span>
                </button>
                <button type="button" onclick="switchTab('lesson_plan')" id="btn-lesson_plan" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-calendar-day tab-icon"></i> Lesson Planner
                    </span>
                </button>
                <button type="button" onclick="switchTab('table22')" id="btn-table22" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-flask tab-icon"></i> Daywork Log
                    </span>
                </button>
                <button type="button" onclick="switchTab('table23')" id="btn-table23" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-lightbulb tab-icon"></i> Open-Ended (OEE)
                    </span>
                </button>
                <button type="button" onclick="switchTab('series_qp')" id="btn-series_qp" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-clipboard-question tab-icon"></i> Series QP &amp; Scheme
                    </span>
                </button>
                <button type="button" onclick="switchTab('table31')" id="btn-table31" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-signature tab-icon"></i> Series Marks (CA)
                    </span>
                </button>
                <button type="button" onclick="switchTab('surveys')" id="btn-surveys" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-comment-medical tab-icon"></i> Surveys &amp; Feedback
                    </span>
                </button>
                <button type="button" onclick="switchTab('ese')" id="btn-ese" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-clipboard-check tab-icon"></i> ESE Mark Entry
                    </span>
                    <span class="tab-badge">40M</span>
                </button>
                <button type="button" onclick="switchTab('summary')" id="btn-summary" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-award tab-icon"></i> CIA Consolidated
                    </span>
                    <span class="tab-badge tab-badge-green">100M</span>
                </button>
                <button type="button" onclick="switchTab('materials')" id="btn-materials" class="tab-btn">
                    <span style="display:flex;align-items:center;gap:9px;">
                        <i class="fa-solid fa-folder-open tab-icon"></i> Learning Materials & Pre-Lab
                    </span>
                </button>
            </div>

            <!-- Lab Batch Filter -->
            <div class="sidebar-nav-group" style="border: 1.5px solid rgba(79, 110, 247, 0.45); border-left: 4px solid var(--accent); box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                <div class="sidebar-nav-header" style="display:flex;align-items:center;gap:6px;color:#818cf8;font-weight:700;">
                    <i class="fa-solid fa-filter"></i> Lab Batch Filter
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;padding:10px;">
                    <button onclick="filterLabBatch('All')" id="batch-filter-All" class="batch-filter-btn vl-btn vl-btn-primary" style="font-size:12px;justify-content:center;">All</button>
                    <button onclick="filterLabBatch('Unassigned')" id="batch-filter-Unassigned" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Unassigned</button>
                    <button onclick="filterLabBatch('Batch A')" id="batch-filter-A" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Batch A</button>
                    <button onclick="filterLabBatch('Batch B')" id="batch-filter-B" class="batch-filter-btn vl-btn" style="font-size:12px;justify-content:center;">Batch B</button>
                </div>
            </div>

            <!-- Assessment Specs -->
            <div class="vl-card" style="border-left:3px solid var(--accent);">
                <div style="padding:12px 14px;">
                    <div style="font-size:12px;font-weight:600;color:var(--text-secondary);display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-graduation-cap"></i> Assessment Specs (R2026)
                    </div>
                    <div style="margin-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Day Work</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">30 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Series Exams</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">15 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Open-ended</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">10 M</div>
                        </div>
                        <div class="vl-stat-card" style="padding:7px 10px;">
                            <div class="vl-stat-label">Attendance</div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">5 M</div>
                        </div>
                    </div>
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);">
                        ESE: <strong style="color:#30d158;">40 Marks</strong>
                    </div>
                </div>
            </div>

        </aside>

        <!-- ── Main Content ─────────────────────────────────── -->
        <section id="workspaceContent" style="flex:1;overflow-y:auto;padding:14px;min-width:0;">

            <!-- TAB: Course Outline & CO-PO -->
            <div id="tab-outline" class="tab-content glass-panel p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 style="font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;background:linear-gradient(90deg,#38bdf8,#818cf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            <i class="fa-solid fa-file-pdf" style="-webkit-text-fill-color:#38bdf8;color:#38bdf8;"></i> Syllabus &amp; Course Articulation
                        </h2>

                        <p class="text-xs text-slate-400 mt-1">Upload the practical syllabus PDF to automatically map Course Outcomes, Credits, and Schemes.</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        @if($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path)
                            <a href="/storage/{{ $practicalCourseFile->syllabus_pdf_path }}" target="_blank" class="exp-btn-touch" style="padding:8px 16px;background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.5);color:#fca5a5;border-radius:8px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;text-decoration:none;" title="View Syllabus PDF">
                                <i class="fa-solid fa-file-pdf" style="color:#ef4444;"></i> View PDF
                            </a>
                        @endif
                        <input type="file" id="syllabus_pdf_input" accept=".pdf" class="hidden" onchange="uploadSyllabusPdf()">
                        <button id="syllabus-upload-btn" onclick="document.getElementById('syllabus_pdf_input').click()" class="exp-btn-touch" style="padding:8px 16px;background:#3b5bdb;border:1px solid #4c6ef5;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-upload"></i> Upload Syllabus PDF
                        </button>
                    </div>
                </div>

                {{-- Upload Status Bar --}}
                <div id="syllabus-upload-status" class="hidden mt-4 p-3 rounded-xl text-sm font-semibold flex items-center gap-3"></div>

                {{-- Uploaded File Banner --}}
                @if($practicalCourseFile && $practicalCourseFile->syllabus_pdf_path)
                <div style="margin-top:12px;padding:10px 14px;background:rgba(48,209,88,.08);border:1px solid rgba(48,209,88,.25);border-radius:8px;display:flex;align-items:center;gap:8px;font-size:13px;">
                    <i class="fa-solid fa-circle-check" style="color:#30d158;font-size:15px;"></i>
                    <span style="color:#30d158;font-weight:600;">Syllabus Loaded:</span>
                    <strong style="color:var(--text-primary);">{{ $practicalCourseFile->course_title ?: $batchSubject->subject_name }}</strong>
                </div>
                @endif


                <!-- Scheme parameters -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-6">
                    @php
                        $copoDecoded = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_copo, true) : [];
                    @endphp
                    <div class="bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-800/60">
                        <span class="text-[11px] text-slate-600 font-medium block uppercase tracking-wide">Credits</span>
                        <span class="text-base font-medium text-slate-400 mt-0.5 block" id="meta-credits">{{ $copoDecoded['credit'] ?? '1' }}</span>
                    </div>
                    <div class="bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-800/60">
                        <span class="text-[11px] text-slate-600 font-medium block uppercase tracking-wide">Proposed Hours</span>
                        <span class="text-base font-medium text-slate-400 mt-0.5 block" id="meta-hours">{{ $copoDecoded['total_hours'] ?? '30' }} hrs</span>
                    </div>
                    <div class="bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-800/60">
                        <span class="text-[11px] text-slate-600 font-medium block uppercase tracking-wide">L:T:P:R</span>
                        <span class="text-base font-medium text-slate-400 mt-0.5 block" id="meta-ltpr">{{ $copoDecoded['l_t_p_r'] ?? '0:0:2:0' }}</span>
                    </div>
                    <div class="bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-800/60">
                        <span class="text-[11px] text-slate-600 font-medium block uppercase tracking-wide">CIE Marks</span>
                        <span class="text-base font-medium text-slate-400 mt-0.5 block" id="meta-cie">{{ $copoDecoded['cie_marks'] ?? '60' }} M</span>
                    </div>
                    <div class="bg-slate-900/40 px-4 py-3 rounded-xl border border-slate-800/60">
                        <span class="text-[11px] text-slate-600 font-medium block uppercase tracking-wide">ESE Marks</span>
                        <span class="text-base font-medium text-slate-400 mt-0.5 block" id="meta-ese">{{ $copoDecoded['ese_marks'] ?? '40' }} M</span>
                    </div>
                </div>

                <!-- Course Outcomes list -->
                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Course Outcomes (COs)</h3>
                    <div class="space-y-3" id="co-outcomes-container">
                        @php
                            $cosList = $practicalCourseFile ? json_decode($practicalCourseFile->parsed_cos, true) : [];
                        @endphp
                        @forelse($cosList as $co)
                        <div class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 flex gap-4 items-start shadow-sm">
                            <span class="px-3 py-1 bg-slate-800 text-sky-300 font-bold font-mono text-sm md:text-base rounded-lg border border-slate-700 h-fit shrink-0 shadow-xs">{{ $co['id'] }}</span>
                            <div class="space-y-1">
                                <h4 class="text-sm md:text-base font-semibold text-slate-100 leading-relaxed">{{ $co['description'] }}</h4>
                                <span class="text-xs md:text-sm text-slate-400 font-mono font-medium block">Cognitive Level: <strong class="text-purple-300">{{ $co['cognitive_level'] }}</strong></span>
                            </div>
                        </div>
                        @empty
                        <div class="text-xs text-slate-600 italic p-2">Syllabus outcomes not uploaded yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Articulation matrix (CO-PO Mapping) -->
                <div class="mt-8 pt-6 border-t border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-table text-slate-500"></i>
                            CO-PO & CO-PSO Articulation Matrix
                            <span class="text-[10px] font-normal text-slate-600 ml-2">(1 = Low &nbsp;·&nbsp; 2 = Medium &nbsp;·&nbsp; 3 = High &nbsp;·&nbsp; - = No mapping)</span>
                        </h3>
                        <button onclick="savePracticalCoPoMatrixBtn()" id="savePracticalCoPoBtn" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition-all flex items-center gap-1.5 shadow">
                            <i class="fa-solid fa-floppy-disk"></i> Save / Update Matrix
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-center text-xs text-slate-300 border border-slate-800 border-collapse rounded-xl overflow-hidden">
                            <thead>
                                <tr class="bg-slate-900 font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="p-3 border border-slate-800 text-left w-20">CO</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-2 border border-slate-800 text-center text-indigo-400 w-10">PO{{ $p }}</th>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                    <th class="p-2 border border-slate-800 text-center text-sky-400 w-10">PSO{{ $s }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody id="practicalCoPoMatrixTbody">
                                @php
                                    $mappings = $copoDecoded['mappings'] ?? [];
                                @endphp
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                <tr class="hover:bg-slate-900/20 transition">
                                    <td class="p-3 border border-slate-800 text-left font-semibold font-mono text-amber-400">{{ $coId }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                    @php
                                        $poVal = $mappings[$coId]["PO$p"] ?? '';
                                        if ($poVal === '-') $poVal = '';
                                    @endphp
                                    <td class="p-1 border border-slate-800">
                                        <input type="text" maxlength="1" value="{{ $poVal }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-8 h-8 bg-slate-950 border border-slate-800 rounded text-center font-bold text-emerald-400 focus:border-blue-500 outline-none text-xs" data-co="{{ $coId }}" data-target="PO{{ $p }}">
                                    </td>
                                    @endfor
                                    @for($s = 1; $s <= 3; $s++)
                                    @php
                                        $psoVal = $mappings[$coId]["PSO$s"] ?? '';
                                        if ($psoVal === '-') $psoVal = '';
                                    @endphp
                                    <td class="p-1 border border-slate-800">
                                        <input type="text" maxlength="1" value="{{ $psoVal }}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-8 h-8 bg-slate-950 border border-slate-800 rounded text-center font-bold text-sky-300 focus:border-blue-500 outline-none text-xs" data-co="{{ $coId }}" data-target="PSO{{ $s }}">
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Legend --}}
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                        <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded bg-red-500/20 border border-red-500/30 inline-block"></span> 3 = High Correlation</span>
                        <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded bg-amber-500/20 border border-amber-500/30 inline-block"></span> 2 = Medium</span>
                        <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded bg-blue-500/20 border border-blue-500/30 inline-block"></span> 1 = Low</span>
                        <span class="flex items-center gap-1.5"><span class="w-5 h-5 rounded bg-slate-900 border border-slate-800 inline-block"></span> - = None</span>
                    </div>
                </div>
            </div>


            <!-- TAB: List of Experiments -->
            <div id="tab-experiments" class="tab-content glass-panel p-6 hidden">
                {{-- Header --}}
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    <div>
                        <h2 style="font-size:16px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0;">
                            <i class="fa-solid fa-list-check" style="color:#818cf8;"></i> Course Experiments Inventory
                        </h2>
                        <p style="font-size:13px;color:var(--text-secondary);margin:4px 0 0 0;">
                            Edit experiment details inline and click Save List to update.
                            @if($practicalCourseFile && count($practicalCourseFile->getExperimentsArray()) > 0)
                                <span style="margin-left:8px;padding:2px 10px;background:rgba(48,209,88,.12);border:1px solid rgba(48,209,88,.25);color:#30d158;border-radius:6px;font-size:12px;font-weight:600;">
                                    {{ count($practicalCourseFile->getExperimentsArray()) }} experiments
                                </span>
                            @endif
                        </p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        <button id="btn-load-pdf" onclick="loadFromPdfExperiments()" class="exp-btn-touch" style="padding:8px 16px;background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.5);color:#fca5a5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-file-pdf"></i> Load from PDF
                        </button>
                        <button id="btn-add-row" onclick="addNewExperimentRow()" class="exp-btn-touch" style="padding:8px 16px;background:#3b5bdb;border:1px solid #4c6ef5;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-plus"></i> Add Row
                        </button>
                        <button id="save-experiments-btn" onclick="saveExperimentsInventory()" class="exp-btn-touch" style="padding:8px 18px;background:#087f5b;border:1px solid #0ca678;color:#fff;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-floppy-disk"></i> Save List
                        </button>
                    </div>
                </div>

                {{-- Status banner (shown by JS) --}}
                <div id="exp-pdf-status" style="display:none;margin-top:10px;padding:10px 14px;border-radius:8px;font-size:13px;font-weight:600;align-items:center;gap:8px;"></div>

                {{-- Table --}}
                <div style="margin-top:14px;overflow-x:auto;">
                    <table id="experiments-inventory-table" style="width:100%;border-collapse:collapse;font-size:13px;color:var(--text-primary);">
                        <thead>
                            <tr style="background:var(--bg-stripe);border-bottom:2px solid var(--border-light);">
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:90px;">Expt No</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Experiment Title</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Practical Outcomes / Details</th>
                                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:90px;">CO</th>
                                <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:70px;">Hrs</th>
                                <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;width:50px;">Del</th>
                            </tr>
                        </thead>
                        <tbody id="experiments-rows-container">
                            @php
                                $expList  = $practicalCourseFile ? $practicalCourseFile->getExperimentsArray() : [];
                                $coOptions = ['CO1','CO2','CO3','CO4','CO5','CO6'];
                                $inputStyle = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';
                            @endphp
                            @if(count($expList) > 0)
                                @foreach($expList as $idx => $exp)
                                <tr style="border-bottom:1px solid var(--border);transition:background .1s;" onmouseover="this.style.background='var(--bg-card-hover)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-no" style="{{ $inputStyle }}width:80px;font-family:monospace;" value="{{ $exp['expt_no'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-title" style="{{ $inputStyle }}" value="{{ $exp['title'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <input type="text" class="exp-desc" style="{{ $inputStyle }}color:#a0a7be;" value="{{ $exp['description'] ?? '' }}">
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <select class="exp-co" style="{{ $inputStyle }}">
                                            @foreach($coOptions as $co)
                                            <option value="{{ $co }}" {{ ($exp['co'] ?? '') === $co ? 'selected' : '' }}>{{ $co }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding:6px 8px;text-align:center;">
                                        <input type="number" class="exp-hours" style="{{ $inputStyle }}width:60px;text-align:center;" value="{{ $exp['hours'] ?? 2 }}" min="1" max="6">
                                    </td>
                                    <td style="padding:6px 8px;text-align:center;">
                                        <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                                            <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr id="exp-empty-row">
                                    <td colspan="6" class="p-8 text-center text-slate-400 italic text-sm">
                                        <i class="fa-solid fa-inbox text-slate-600 text-2xl block mb-2"></i>
                                        No experiments loaded yet.<br>
                                        <span class="text-xs">Click <strong class="text-red-400">Load from PDF</strong> to fetch from the uploaded syllabus.</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: Lesson Planner -->
            <div id="tab-lesson_plan" class="tab-content glass-panel p-6 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-day text-slate-500"></i> Lab Lesson Planner splitup
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Split content modularly. Generates timeline including two series exams and open ended project.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <select id="lesson_planner_mode" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 font-bold">
                            <option value="single">Single/Whole Class Batch</option>
                            <option value="split">Split Batch (Batch A/B)</option>
                        </select>
                        <button onclick="generateLessonTimeline()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i> Generate Planner
                        </button>
                        <a href="/r26/classroom/lesson-plan/print/{{ $batchSubject->id }}" target="_blank" class="px-3 py-2 bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-xl font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Print
                        </a>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-xs border border-slate-800 border-collapse" id="lesson-plan-table">
                        <thead>
                            <tr class="bg-slate-900/60 font-bold text-slate-400">
                                <th class="p-3 w-16">Day No</th>
                                <th class="p-3 w-28">Sub-Batch</th>
                                <th class="p-3 w-36">Proposed Date</th>
                                <th class="p-3 w-36">Actual Date</th>
                                <th class="p-3">Topic Content / Target Experiment</th>
                                <th class="p-3 w-24">Mapped CO</th>
                                <th class="p-3 w-20">Hours</th>
                                <th class="p-3 w-28">Pedagogy</th>
                                <th class="p-3 w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody id="lesson-plan-rows-container">
                            @forelse($lessonPlans as $lp)
                            <tr class="lesson-plan-row hover:bg-slate-900/20 border-b border-slate-855" data-id="{{ $lp->id }}">
                                <td class="p-3 font-mono">{{ $lp->day_no }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lp->sub_batch == 'Batch A' ? 'bg-indigo-500/10 text-indigo-400' : ($lp->sub_batch == 'Batch B' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-slate-800 text-slate-350') }}">
                                        {{ $lp->sub_batch ?? 'Whole' }}
                                    </span>
                                </td>
                                <td class="p-2">
                                    <input type="date" value="{{ $lp->proposed_date }}" class="lp-proposed px-2 py-1 bg-slate-950 border border-slate-850 rounded text-slate-200 focus:outline-none w-full font-sans">
                                </td>
                                <td class="p-2">
                                    <input type="date" value="{{ $lp->actual_date }}" class="lp-actual px-2 py-1 bg-slate-950 border border-slate-850 rounded text-slate-200 focus:outline-none w-full font-sans">
                                </td>
                                <td class="p-2">
                                    <input type="text" value="{{ $lp->topic_content }}" class="lp-topic px-2 py-1 bg-slate-950 border border-slate-850 rounded text-slate-200 focus:outline-none w-full">
                                </td>
                                <td class="p-2">
                                    <select class="lp-co bg-slate-950 border border-slate-850 rounded px-1 py-1 focus:outline-none w-full">
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                        <option value="{{ $coId }}" {{ $lp->co_id == $coId ? 'selected' : '' }}>{{ $coId }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2">
                                    <input type="number" value="{{ $lp->allocated_hours }}" class="lp-hours px-2 py-1 bg-slate-950 border border-slate-850 rounded text-slate-200 text-center w-14">
                                </td>
                                <td class="p-2">
                                    <select class="lp-pedagogy bg-slate-950 border border-slate-850 rounded px-1 py-1 focus:outline-none w-full">
                                        <option value="Practical" {{ $lp->pedagogy == 'Practical' ? 'selected' : '' }}>Practical</option>
                                        <option value="Exam" {{ $lp->pedagogy == 'Exam' ? 'selected' : '' }}>Exam</option>
                                        <option value="Lecture" {{ $lp->pedagogy == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                    </select>
                                </td>
                                <td class="p-2">
                                    <select class="lp-status bg-slate-950 border border-slate-850 rounded px-1 py-1 focus:outline-none w-full font-bold">
                                        <option value="Pending" {{ $lp->status == 'Pending' ? 'selected' : '' }} class="text-amber-500">Pending</option>
                                        <option value="Completed" {{ $lp->status == 'Completed' ? 'selected' : '' }} class="text-emerald-500">Completed</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-6 text-center text-slate-500 italic" colspan="10">No planner generated yet. Select batch option and click Generate.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lessonPlans->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="saveLessonPlannerBulk()" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Save Planner Entries
                    </button>
                </div>
                @endif
            </div>

            <!-- TAB: Continuous Log (Table 2.2) -->
            <div id="tab-table22" class="tab-content glass-panel p-6 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-flask text-slate-500"></i> Daywork Log
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Continuous laboratory evaluation log out of 50. Total averages scale to 30 marks.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div>
                            <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1">Select Experiment</label>
                            <select id="exp_select" onchange="switchActiveExperiment(this.value)" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 font-mono font-bold">
                                <!-- Dynamic options -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1">Experiment No</label>
                            <input type="text" id="exp_no" value="Exp 1" oninput="onManualExpNoChange()" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 font-mono w-24">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1">Experiment Title</label>
                            <input type="text" id="exp_title" placeholder="e.g. Verification of Ohm's Law" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 w-48 sm:w-64">
                        </div>
                    </div>
                </div>

                <!-- Student register -->
                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $expLog = $experimentLogs->get('Exp 1') ? $experimentLogs->get('Exp 1')->where('reg_no', $student->reg_no)->first() : null;
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 font-black text-xs flex items-center justify-center border border-blue-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-200">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-400">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Lab Batch:</span>
                                    <select onchange="updateLabBatch('{{ $student->reg_no }}', this.value)" class="student-batch-select-{{ $student->reg_no }} bg-slate-950 border border-slate-855 text-xs rounded py-1 px-1.5 text-slate-300 focus:outline-none focus:border-blue-500">
                                        <option value="" {{ empty($savedBatch) ? '' : ($batchVal == 'Unassigned' || empty($batchVal) ? 'selected' : '') }}>Unassigned</option>
                                        <option value="Batch A" {{ $batchVal == 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                        <option value="Batch B" {{ $batchVal == 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                    </select>
                                </div>


                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table22')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-md shadow-blue-900/30 border border-blue-500">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-exp-{{ $student->reg_no }}" class="font-mono text-xs font-bold text-slate-200">
                                        {{ $expLog ? floatval($expLog->total_score_50) : '0.00' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitExpMarks()" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Save Continuous Log
                    </button>
                </div>
            </div>

            <!-- TAB: Open-Ended (Table 2.3) -->
            <div id="tab-table23" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-400"></i> Open-Ended Experiments
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Assess originality and execution of open-ended projects out of 50. Normalized to 10 marks.</p>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $openLog = $openEndedLogs->get($student->reg_no);
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 font-black text-xs flex items-center justify-center border border-amber-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-200">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-400">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <input type="text" id="open-title-{{ $student->reg_no }}" value="{{ $openLog ? $openLog->project_title : '' }}" placeholder="Project Title..." class="px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl text-xs text-white focus:outline-none w-44">

                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table23')" class="px-4 py-2 bg-amber-600 hover:bg-amber-550 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-md shadow-amber-900/30 border border-amber-500">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-open-{{ $student->reg_no }}" class="font-mono text-xs font-bold text-slate-200">
                                        {{ $openLog ? floatval($openLog->total_score_50) : '0.00' }} / 50
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitOpenEndedMarks()" class="px-6 py-3 bg-amber-600 hover:bg-amber-550 text-white font-bold text-xs rounded-xl shadow transition">
                        Save Open-Ended Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Series QP & Outline -->
            <div id="tab-series_qp" class="tab-content glass-panel p-6 hidden">
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-question text-purple-400"></i> Series Exam Question Paper Setup
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Configure practical examination blueprints. Evaluate mapped outcomes separately.</p>
                    </div>
                    <select id="series_qp_select" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500 font-bold" onchange="loadSeriesQpConfig(this.value)">
                        <option value="Series 1">Practical Series Exam 1</option>
                        <option value="Series 2">Practical Series Exam 2</option>
                    </select>
                </div>

                <!-- Form configs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="space-y-4 md:col-span-1">
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Max Marks</label>
                            <input type="number" id="series_qp_max_marks" value="40" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none w-full">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Duration (mins)</label>
                            <input type="number" id="series_qp_duration" value="120" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-sm text-white focus:outline-none w-full">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Target COs</label>
                            <div class="flex flex-wrap gap-3 mt-1" id="series_qp_co_checks">
                                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coId)
                                <label class="flex items-center gap-2 text-xs font-mono cursor-pointer select-none">
                                    <input type="checkbox" value="{{ $coId }}" class="co-checkbox rounded border-slate-800 text-blue-600 focus:ring-0"> {{ $coId }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-black uppercase text-slate-500 tracking-wider mb-1.5">Instructions & Question Outline (JSON / Instructions)</label>
                        <textarea id="series_qp_outline" rows="8" placeholder="Enter practical experiment choices, setup specifications or instructions..." class="p-3 bg-slate-900 border border-slate-850 rounded-2xl text-xs text-white focus:outline-none w-full font-mono"></textarea>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end gap-3">
                    <button onclick="saveSeriesQpOutline()" class="px-6 py-3 bg-purple-650 hover:bg-purple-600 text-white font-bold text-xs rounded-xl shadow-lg transition">
                        Save Question Blueprint
                    </button>
                </div>
            </div>

            <!-- TAB: Series Marks Entry (Table 3.1) -->
            <div id="tab-table31" class="tab-content glass-panel p-6 hidden">
                <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-signature text-purple-400"></i> Series Marks (CA)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Practical examinations out of 40. Consolidated average represents 15 CIA marks.</p>
                    </div>
                    <select id="series_no" onchange="switchSeriesExam(this.value)" class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white focus:outline-none focus:border-purple-500 font-bold">
                        <option value="Series 1">Series Exam 1</option>
                        <option value="Series 2">Series Exam 2</option>
                    </select>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $series1Log = $seriesExamLogs->get('Series 1') ? $seriesExamLogs->get('Series 1')->where('reg_no', $student->reg_no)->first() : null;
                        $series2Log = $seriesExamLogs->get('Series 2') ? $seriesExamLogs->get('Series 2')->where('reg_no', $student->reg_no)->first() : null;
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 font-black text-xs flex items-center justify-center border border-purple-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-200">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-400">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button onclick="openGradingModal('{{ $student->reg_no }}', 'table31')" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-md shadow-purple-900/30 border border-purple-550">
                                    <i class="fa-solid fa-sliders"></i> Evaluate
                                </button>

                                <div class="text-right min-w-[70px]">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Score</span>
                                    <span id="score-text-series-{{ $student->reg_no }}" class="font-mono text-xs font-bold text-slate-200"
                                          data-s1="{{ $series1Log ? floatval($series1Log->total_score_40) : '0.00' }}"
                                          data-s2="{{ $series2Log ? floatval($series2Log->total_score_40) : '0.00' }}">
                                        {{ $series1Log ? floatval($series1Log->total_score_40) : '0.00' }} / 40
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitSeriesMarks()" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl shadow transition">
                        Save Series Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Surveys & Feedback -->
            <div id="tab-surveys" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-comment-medical text-slate-500"></i> Feedback & Surveys Module
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Initiate and analyze mid-semester feedback and course exit surveys dynamically mapped to COs.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Mid-sem panel -->
                    <div class="bg-slate-900/60 p-6 rounded-2xl border border-slate-800 space-y-4">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2"><i class="fa-solid fa-hourglass-half text-amber-500"></i> Mid-Semester Survey</h3>
                        <p class="text-xs text-slate-400">Collects quick feedback on lab delivery, pace, and evaluation clarity.</p>
                        <div class="flex items-center gap-2 pt-2">
                            <button onclick="manageSurvey('mid', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">Initiate</button>
                            <button onclick="manageSurvey('mid', 'close')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-350 text-xs font-bold rounded-xl transition">Close</button>
                        </div>
                    </div>

                    <!-- Exit Survey panel -->
                    <div class="bg-slate-900/60 p-6 rounded-2xl border border-slate-800 space-y-4">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Course Exit Survey</h3>
                        <p class="text-xs text-slate-400">Assesses direct attainment level of the 4 course outcomes (COs) upon course completion.</p>
                        <div class="flex items-center gap-2 pt-2">
                            <button onclick="manageSurvey('exit', 'initiate')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition">Initiate</button>
                            <button onclick="manageSurvey('exit', 'close')" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-355 text-xs font-bold rounded-xl transition">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: ESE Entry -->
            <div id="tab-ese" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-emerald-400"></i> End Semester Evaluation (ESE) Marks
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Record the final external practical exam marks out of 40. Displays alongside internal totals.</p>
                </div>

                <div class="mt-6 space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                    @foreach($students as $index => $student)
                    @php
                        $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                        $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                        $eseLog = $eseMarks->get($student->reg_no);
                    @endphp
                    <div class="student-row p-4 rounded-2xl bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition-premium" 
                         data-reg-no="{{ $student->reg_no }}" 
                         data-batch="{{ $batchVal }}">

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 font-black text-xs flex items-center justify-center border border-emerald-500/20">
                                    {{ $student->roll_no ?? ($index + 1) }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-200">{{ $student->name }}</h4>
                                    <span class="text-xs font-mono text-slate-500">{{ $student->reg_no }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-[10px] uppercase font-bold text-slate-500 block">Marks (Max 40)</span>
                                <input type="number" id="ese-mark-{{ $student->reg_no }}" value="{{ $eseLog ? floatval($eseLog->ese_score) : '0' }}" min="0" max="40" step="0.5" class="px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl text-sm font-bold font-mono text-white text-center w-20 focus:outline-none focus:border-emerald-500" oninput="recalculateCIA('{{ $student->reg_no }}')">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800 flex justify-end">
                    <button onclick="submitEseMarks()" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition">
                        Save ESE Marks
                    </button>
                </div>
            </div>

            <!-- TAB: Consolidated CIA Summary (100 Marks) -->
            <div id="tab-summary" class="tab-content glass-panel p-6 hidden">
                <div class="pb-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-award text-emerald-400"></i> Lab CIA Consolidated Register
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Continuous assessment summaries combined with external practical totals out of 100.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/cia" target="_blank" class="px-4 py-2.5 bg-emerald-600/20 border border-emerald-500/30 hover:bg-emerald-600/35 text-emerald-400 text-xs rounded-xl font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Print CIA Summary
                        </a>
                        <a href="/r26/classroom/practical/{{ $batchSubject->id }}/print/attainment" target="_blank" class="px-4 py-2.5 bg-blue-600/20 border border-blue-500/30 hover:bg-blue-600/35 text-slate-500 text-xs rounded-xl font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-line"></i> Attainment report
                        </a>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300 border-collapse">
                        <thead>
                            <tr class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                                <th class="p-3">Roll / Reg No</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3 text-center">Batch</th>
                                <th class="p-3 text-center">Lab Work (30M)</th>
                                <th class="p-3 text-center">Series (15M)</th>
                                <th class="p-3 text-center">Open Ended (10M)</th>
                                <th class="p-3 text-center">Attendance (5M)</th>
                                <th class="p-3 text-center font-bold text-white">CIA Total (60M)</th>
                                <th class="p-3 text-center text-emerald-400 font-bold">ESE Marks (40M)</th>
                                <th class="p-3 text-center text-white font-black">Grand Total (100M)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($students as $index => $student)
                            @php
                                $score = $consolidatedScores[$student->reg_no] ?? [];
                                $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                                $batchVal = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                            @endphp
                            <tr class="student-row hover:bg-slate-900/20 transition-premium" 
                                data-reg-no="{{ $student->reg_no }}" 
                                data-batch="{{ $batchVal }}">
                                <td class="p-3 font-mono text-slate-500">{{ $student->reg_no }}</td>
                                <td class="p-3 font-bold text-white">{{ $student->name }}</td>
                                <td class="p-3 text-center">
                                    <span class="student-batch-badge-{{ $student->reg_no }} px-2 py-0.5 rounded text-[10px] font-bold {{ $batchVal == 'Batch A' ? 'bg-indigo-500/10 text-indigo-400' : ($batchVal == 'Batch B' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-slate-800 text-slate-500') }}">
                                        {{ $batchVal }}
                                    </span>
                                </td>

                                <td class="p-3 text-center font-mono text-slate-500" id="cia-lab-work-{{ $student->reg_no }}">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-purple-400" id="cia-series-{{ $student->reg_no }}">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-amber-400" id="cia-open-{{ $student->reg_no }}">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-emerald-400">{{ $attendanceMarks[$student->reg_no]['mark'] ?? 5 }}</td>
                                <td class="p-3 text-center font-mono font-bold text-white" id="cia-total-{{ $student->reg_no }}">{{ $score['total_cia_60'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono text-emerald-400 font-bold" id="cia-ese-{{ $student->reg_no }}">{{ $score['ese_score_40'] ?? '0.00' }}</td>
                                <td class="p-3 text-center font-mono font-black text-sm text-emerald-400" id="cia-grand-{{ $student->reg_no }}">{{ $score['grand_total_100'] ?? '0.00' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @include('partials.virtual_learning_hub_tab', ['roomType' => 'Practical'])

        </section>

    </div>

    <!-- MOBILE TOUCH GRADINGS SLIDE-OVER MODAL -->
    <div id="gradingModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col justify-end sm:justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
            
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <div>
                    <h3 id="modalStudentName" class="font-black text-white text-base">Student Name</h3>
                    <span id="modalStudentReg" class="text-xs font-mono text-slate-400">Reg No</span>
                </div>
                <button onclick="closeGradingModal()" class="w-8 h-8 rounded-full bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white transition flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="modalSlidersContainer" class="space-y-4 max-h-[50vh] overflow-y-auto pr-1">
                <!-- Sliders populated dynamically -->
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-800 gap-3">
                <button onclick="navigateStudent(-1)" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </button>

                <div class="text-center">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Total</span>
                    <span id="modalTotalScore" class="font-mono text-lg font-black text-emerald-400">0.00</span>
                </div>

                <button onclick="navigateStudent(1)" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Scripting controls -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = "{{ $batchSubject->id }}";
        
        const studentList = @json($students);
        const labBatches = @json($labBatches);
        const experimentLogs = @json($experimentLogs);
        const openEndedLogs = @json($openEndedLogs);
        const seriesExamLogs = @json($seriesExamLogs);
        const eseMarks = @json($eseMarks);
        const attendanceMarks = @json($attendanceMarks);
        const consolidatedScores = @json($consolidatedScores);

        let activeTab = 'outline';
        let activeBatchFilter = 'All';
        let currentStudentIndex = 0;


        const scoresState = {
            table22: {},
            table23: {},
            table31: {}
        };

        // Populate local JS scores cache
        studentList.forEach(s => {
            const reg = s.reg_no;

            const expLog = experimentLogs['Exp 1'] ? experimentLogs['Exp 1'].find(x => x.reg_no === reg) : null;
            scoresState.table22[reg] = expLog ? {
                c1: parseFloat(expLog.prep_punctuality),
                c2: parseFloat(expLog.setup_procedure),
                c3: parseFloat(expLog.observation_recording),
                c4: parseFloat(expLog.analysis_interpretation),
                c5: parseFloat(expLog.viva_voce),
                c6: parseFloat(expLog.teamwork_discipline)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

            const openLog = openEndedLogs[reg];
            scoresState.table23[reg] = openLog ? {
                c1: parseFloat(openLog.originality_relevance),
                c2: parseFloat(openLog.objectives_plan),
                c3: parseFloat(openLog.execution_recording),
                c4: parseFloat(openLog.analysis_presentation),
                c5: parseFloat(openLog.teamwork_innovation)
            } : { c1:0, c2:0, c3:0, c4:0, c5:0 };

            const s1Log = seriesExamLogs['Series 1'] ? seriesExamLogs['Series 1'].find(x => x.reg_no === reg) : null;
            const s2Log = seriesExamLogs['Series 2'] ? seriesExamLogs['Series 2'].find(x => x.reg_no === reg) : null;
            scoresState.table31[reg] = {
                'Series 1': s1Log ? {
                    c1: parseFloat(s1Log.writeup_procedure),
                    c2: parseFloat(s1Log.setup_execution),
                    c3: parseFloat(s1Log.observation_result),
                    c4: parseFloat(s1Log.viva_voce),
                    c5: parseFloat(s1Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 },
                'Series 2': s2Log ? {
                    c1: parseFloat(s2Log.writeup_procedure),
                    c2: parseFloat(s2Log.setup_execution),
                    c3: parseFloat(s2Log.observation_result),
                    c4: parseFloat(s2Log.viva_voce),
                    c5: parseFloat(s2Log.record_completion)
                } : { c1:0, c2:0, c3:0, c4:0, c5:0 }
            };
        });

        function handleVirtualLabBack(e) {
            if (e) e.preventDefault();
            if (window.opener && !window.opener.closed) {
                window.close();
                return false;
            }
            if (window.history.length <= 1) {
                window.close();
                return false;
            }
            if (document.referrer && document.referrer.indexOf(window.location.host) !== -1) {
                window.history.back();
                return false;
            }
            window.close();
            return false;
        }

        // Initialize experiment dropdown and handlers
        function initExperimentSelect() {
            const select = document.getElementById('exp_select');
            if (!select) return;
            select.innerHTML = '';
            
            // Collect unique experiment numbers from localExperiments and experimentLogs
            const uniqueExps = new Set();
            if (typeof localExperiments !== 'undefined' && localExperiments && localExperiments.length > 0) {
                localExperiments.forEach(e => {
                    if (e.expt_no) uniqueExps.add(e.expt_no);
                });
            }
            // Add any other experiments found in saved logs
            if (typeof experimentLogs !== 'undefined' && experimentLogs) {
                Object.keys(experimentLogs).forEach(k => {
                    uniqueExps.add(k);
                });
            }

            // Ensure at least "Exp 1" is present
            if (uniqueExps.size === 0) {
                uniqueExps.add("Exp 1");
            }

            // Convert to array and sort naturally
            const sorted = Array.from(uniqueExps).sort((a, b) => {
                return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
            });

            sorted.forEach(expNo => {
                const opt = document.createElement('option');
                opt.value = expNo;
                opt.textContent = expNo;
                select.appendChild(opt);
            });

            // Set default value matching text box
            const activeExp = document.getElementById('exp_no')?.value || 'Exp 1';
            select.value = activeExp;
            
            updateExperimentTitleFromList(activeExp);
        }

        function updateExperimentTitleFromList(expNo) {
            // Find title from localExperiments
            const matched = (typeof localExperiments !== 'undefined' ? localExperiments : []).find(e => e.expt_no === expNo);
            if (matched && matched.title) {
                document.getElementById('exp_title').value = matched.title;
            } else {
                // Fallback: look in experimentLogs
                const logs = experimentLogs[expNo];
                if (logs && logs.length > 0 && logs[0].title) {
                    document.getElementById('exp_title').value = logs[0].title;
                } else {
                    document.getElementById('exp_title').value = '';
                }
            }
        }

        function switchActiveExperiment(expNo) {
            const expNoInput = document.getElementById('exp_no');
            if (expNoInput) expNoInput.value = expNo;
            updateExperimentTitleFromList(expNo);

            // Populate scoresState.table22 with the selected experiment's marks
            studentList.forEach(s => {
                const reg = s.reg_no;
                const expLog = experimentLogs[expNo] ? experimentLogs[expNo].find(x => x.reg_no === reg) : null;
                
                scoresState.table22[reg] = expLog ? {
                    c1: parseFloat(expLog.prep_punctuality) || 0,
                    c2: parseFloat(expLog.setup_procedure) || 0,
                    c3: parseFloat(expLog.observation_recording) || 0,
                    c4: parseFloat(expLog.analysis_interpretation) || 0,
                    c5: parseFloat(expLog.viva_voce) || 0,
                    c6: parseFloat(expLog.teamwork_discipline) || 0
                } : { c1:0, c2:0, c3:0, c4:0, c5:0, c6:0 };

                // Update the student row score display on screen
                const sum = (scoresState.table22[reg].c1||0) + (scoresState.table22[reg].c2||0) + (scoresState.table22[reg].c3||0) + (scoresState.table22[reg].c4||0) + (scoresState.table22[reg].c5||0) + (scoresState.table22[reg].c6||0);
                const scoreTextSpan = document.getElementById(`score-text-exp-${reg}`);
                if (scoreTextSpan) {
                    scoreTextSpan.innerText = sum.toFixed(2) + ' / 50';
                }

                // Recalculate consolidated CIA values for this student on the client side
                recalculateCIA(reg);
            });
        }

        function onManualExpNoChange() {
            const val = document.getElementById('exp_no').value;
            const select = document.getElementById('exp_select');
            if (select) {
                let exists = false;
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === val) {
                        select.value = val;
                        exists = true;
                        break;
                    }
                }
                if (!exists && val.trim() !== '') {
                    const opt = document.createElement('option');
                    opt.value = val;
                    opt.textContent = val;
                    select.appendChild(opt);
                    select.value = val;
                }
            }
            switchActiveExperiment(val);
        }

        // Initialize on load
        initExperimentSelect();

        // Initialise first tab active state and filter on page load
        document.getElementById('btn-outline')?.classList.add('active');
        
        // Tab triggers
        function switchTab(tabId) {
            activeTab = tabId;
            // Hide all tab content panels
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active state from all buttons
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            // Show target tab
            const panel = document.getElementById('tab-' + tabId);
            if (panel) panel.classList.remove('hidden');

            // Activate button
            const btn = document.getElementById('btn-' + tabId);
            if (btn) btn.classList.add('active');

            if (tabId === 'series_qp') {
                loadSeriesQpConfig('Series 1');
            }
            
            // Re-apply the current batch filter to the newly shown tab
            filterLabBatch(activeBatchFilter);
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('workspaceSidebar');
            sidebar.classList.toggle('sidebar-collapsed');
            const btn = document.getElementById('sidebar-toggle-btn');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                btn.style.color = 'var(--accent)';
            } else {
                btn.style.color = '';
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        function filterLabBatch(batch) {
            activeBatchFilter = batch;
            document.querySelectorAll('.batch-filter-btn').forEach(b => b.classList.remove('vl-btn-primary'));

            const btnMap = { 'All': 'All', 'Unassigned': 'Unassigned', 'Batch A': 'A', 'Batch B': 'B' };
            const id = btnMap[batch];
            const activeBtn = document.getElementById(`batch-filter-${id}`);
            if (activeBtn) activeBtn.classList.add('vl-btn-primary');

            document.querySelectorAll('.student-row').forEach(row => {
                const b = row.getAttribute('data-batch') || 'Unassigned';
                if (batch === 'All') row.classList.remove('hidden');
                else if (batch === 'Unassigned' && (b === 'Unassigned' || b === '')) row.classList.remove('hidden');
                else if (b === batch) row.classList.remove('hidden');
                else row.classList.add('hidden');
            });
        }

        async function updateLabBatch(regNo, value) {
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lab-batch`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ reg_no: regNo, lab_batch: value })
                });
                const data = await res.json();
                if (data.success) {
                    const displayVal = value || 'Unassigned';
                    
                    // 1. Update data-batch attributes for visibility matching
                    document.querySelectorAll(`.student-row[data-reg-no="${regNo}"]`).forEach(row => {
                        row.setAttribute('data-batch', displayVal);
                    });
                    
                    // 2. Sync all other select boxes for this student in other views
                    document.querySelectorAll(`.student-batch-select-${regNo}`).forEach(sel => {
                        sel.value = value;
                    });
                    
                    // 3. Sync badge in consolidated summary table
                    document.querySelectorAll(`.student-batch-badge-${regNo}`).forEach(badge => {
                        badge.innerText = displayVal;
                        badge.className = `student-batch-badge-${regNo} px-2 py-0.5 rounded text-[10px] font-bold ` + 
                            (displayVal === 'Batch A' ? 'bg-indigo-500/10 text-indigo-400' : 
                            (displayVal === 'Batch B' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-slate-800 text-slate-500'));
                    });
                    
                    // 4. Re-filter view
                    filterLabBatch(activeBatchFilter);
                }
            } catch(e) {
                console.error(e);
            }
        }

        // Apply initial filter state on page load
        filterLabBatch('All');


        // Syllabus Upload
        async function uploadSyllabusPdf() {
            const input = document.getElementById('syllabus_pdf_input');
            if (input.files.length === 0) return;

            const btn    = document.getElementById('syllabus-upload-btn');
            const status = document.getElementById('syllabus-upload-status');

            // Disable button, show spinner
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading & Parsing...';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            status.className = 'mt-4 p-3 rounded-xl text-sm font-semibold flex items-center gap-3 bg-blue-950/50 border border-blue-700/40 text-blue-300';
            status.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading PDF and parsing COs, CO-PO matrix, and experiments. Please wait…';
            status.classList.remove('hidden');

            const formData = new FormData();
            formData.append('syllabus_file', input.files[0]);
            formData.append('_token', csrfToken);

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    status.className = 'mt-4 p-3 rounded-xl text-sm font-semibold flex items-center gap-3 bg-emerald-950/50 border border-emerald-700/40 text-emerald-300';
                    status.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message} Reloading...`;
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    status.className = 'mt-4 p-3 rounded-xl text-sm font-semibold flex items-center gap-3 bg-red-950/50 border border-red-700/40 text-red-300';
                    status.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${data.message || 'Upload failed. Please try again.'}`;
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload Syllabus PDF';
                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            } catch(e) {
                status.className = 'mt-4 p-3 rounded-xl text-sm font-semibold flex items-center gap-3 bg-red-950/50 border border-red-700/40 text-red-300';
                status.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Network error: ${e.message}. Check your connection and retry.`;
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-upload"></i> Upload Syllabus PDF';
                btn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        }


        // CO-PO Matrix Mappings
        async function saveCoPoMatrix() {
            const mappings = {};
            document.querySelectorAll('.copo-select').forEach(el => {
                const co = el.getAttribute('data-co');
                const po = el.getAttribute('data-po');
                if (!mappings[co]) mappings[co] = {};
                mappings[co][po] = el.value;
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/copo`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mappings: mappings })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save mappings.");
            }
        }

        // Manual Experiments Inventory List (for JS-driven operations: Load from PDF, Add Row)
        let localExperiments = @json($practicalCourseFile ? ($practicalCourseFile->getExperimentsArray()) : []);
        const pdfParsedExperiments = @json($practicalCourseFile ? (json_decode($practicalCourseFile->parsed_experiments, true) ?? []) : []);
        // NOTE: initial rows are server-rendered by Blade; JS only re-renders when user clicks Load from PDF or Add Row


        function loadFromPdfExperiments() {
            const statusEl = document.getElementById('exp-pdf-status');
            if (!pdfParsedExperiments || pdfParsedExperiments.length === 0) {
                if (statusEl) {
                    statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-amber-950/40 border border-amber-700/40 text-amber-300';
                    statusEl.style.display = 'flex';
                    statusEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> No experiments found in uploaded PDF. Please upload a syllabus PDF first from the Course Outline tab.';
                }
                alert('No experiments found in uploaded PDF. Please upload a syllabus PDF first from the Course Outline tab.');
                return;
            }
            localExperiments = JSON.parse(JSON.stringify(pdfParsedExperiments));
            loadExperimentsInventoryTable();
            if (statusEl) {
                statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-emerald-950/40 border border-emerald-700/40 text-emerald-300';
                statusEl.style.display = 'flex';
                statusEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> Loaded ${pdfParsedExperiments.length} experiments from the syllabus PDF. Edit below and click Save List.`;
            }
        }

        function loadExperimentsInventoryTable() {
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;

            const IS = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';

            if (!localExperiments || localExperiments.length === 0) {
                container.innerHTML = `<tr><td colspan="6" style="padding:40px;text-align:center;color:#5a6180;font-style:italic;"><i class="fa-solid fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;color:#323552;"></i>No experiments loaded.<br><span style="font-size:12px;">Click <strong style="color:#fca5a5;">Load from PDF</strong> to fetch experiments.</span></td></tr>`;
                return;
            }

            const esc = str => String(str || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/[\r\n]+/g, ' ');

            let html = '';
            localExperiments.forEach((exp) => {
                const coOptions = ['CO1','CO2','CO3','CO4','CO5','CO6'].map(c =>
                    `<option value="${c}" ${(exp.co||'') === c ? 'selected' : ''}>${c}</option>`
                ).join('');
                html += `
                    <tr style="border-bottom:1px solid #2a2d3e;" onmouseover="this.style.background='#1f2233'" onmouseout="this.style.background='transparent'">
                        <td style="padding:6px 8px;"><input type="text" class="exp-no" style="${IS}width:80px;font-family:monospace;" value="${esc(exp.expt_no)}"></td>
                        <td style="padding:6px 8px;"><input type="text" class="exp-title" style="${IS}" value="${esc(exp.title)}"></td>
                        <td style="padding:6px 8px;"><input type="text" class="exp-desc" style="${IS}color:#a0a7be;" value="${esc(exp.description)}"></td>
                        <td style="padding:6px 8px;"><select class="exp-co" style="${IS}">${coOptions}</select></td>
                        <td style="padding:6px 8px;text-align:center;"><input type="number" class="exp-hours" style="${IS}width:60px;text-align:center;" value="${exp.hours || 2}" min="1" max="6"></td>
                        <td style="padding:6px 8px;text-align:center;">
                            <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                                <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                            </button>
                        </td>
                    </tr>`;
            });
            container.innerHTML = html;
        }

        function addNewExperimentRow() {
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;

            const emptyRow = document.getElementById('exp-empty-row');
            if (emptyRow) emptyRow.remove();

            const count = container.querySelectorAll('tr').length + 1;
            const IS = 'background:#0f1117;border:1px solid #2a2d3e;color:#e2e5f0;border-radius:6px;padding:6px 8px;font-size:13px;width:100%;outline:none;';

            const newTr = document.createElement('tr');
            newTr.style.borderBottom = '1px solid #2a2d3e';
            newTr.style.transition = 'background .1s';
            newTr.onmouseover = function() { this.style.background = '#1f2233'; };
            newTr.onmouseout = function() { this.style.background = 'transparent'; };

            newTr.innerHTML = `
                <td style="padding:6px 8px;"><input type="text" class="exp-no" style="${IS}width:80px;font-family:monospace;" value="Expt ${count}"></td>
                <td style="padding:6px 8px;"><input type="text" class="exp-title" style="${IS}" value="" placeholder="Experiment title..."></td>
                <td style="padding:6px 8px;"><input type="text" class="exp-desc" style="${IS}color:#a0a7be;" value="" placeholder="Practical details / outcomes..."></td>
                <td style="padding:6px 8px;">
                    <select class="exp-co" style="${IS}">
                        <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                        <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                        <option value="CO5">CO5</option>
                        <option value="CO6">CO6</option>
                    </select>
                </td>
                <td style="padding:6px 8px;text-align:center;"><input type="number" class="exp-hours" style="${IS}width:60px;text-align:center;" value="2" min="1" max="6"></td>
                <td style="padding:6px 8px;text-align:center;">
                    <button onclick="removeExperimentRow(this)" style="width:32px;height:32px;background:#7f1d1d;border:1px solid #991b1b;color:#fca5a5;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Remove">
                        <i class="fa-solid fa-trash-can" style="font-size:11px;"></i>
                    </button>
                </td>
            `;
            container.appendChild(newTr);
        }

        function removeExperimentRow(target) {
            if (target && target.closest) {
                const tr = target.closest('tr');
                if (tr) tr.remove();
            } else if (typeof target === 'number') {
                const trs = document.querySelectorAll('#experiments-rows-container tr');
                if (trs[target]) trs[target].remove();
            }
        }

        function syncLocalExperimentsFromDom() {
            const rows = [];
            document.querySelectorAll('#experiments-rows-container tr').forEach(tr => {
                const noInput = tr.querySelector('.exp-no');
                if (!noInput) return;
                rows.push({
                    expt_no: noInput.value,
                    title: tr.querySelector('.exp-title').value,
                    description: tr.querySelector('.exp-desc').value,
                    co: tr.querySelector('.exp-co').value,
                    hours: parseInt(tr.querySelector('.exp-hours').value || 2)
                });
            });
            if (rows.length > 0) localExperiments = rows;
        }

        // keep importDefaults as alias for backward compat
        function importDefaults() { loadFromPdfExperiments(); }

        async function saveExperimentsInventory() {
            const btn = document.getElementById('save-experiments-btn');
            const originalHtml = btn ? btn.innerHTML : '<i class="fa-solid fa-floppy-disk"></i> Save List';
            
            const rows = [];
            const container = document.getElementById('experiments-rows-container');
            if (!container) return;
            const trs = container.querySelectorAll('tr');
            
            trs.forEach(tr => {
                const noInput = tr.querySelector('.exp-no');
                if (!noInput) return;
                rows.push({
                    expt_no: noInput.value,
                    title: tr.querySelector('.exp-title').value,
                    description: tr.querySelector('.exp-desc').value,
                    co: tr.querySelector('.exp-co').value,
                    hours: parseInt(tr.querySelector('.exp-hours').value || 2)
                });
            });

            if (rows.length === 0) {
                alert('No experiment rows to save.');
                return;
            }

            localExperiments = rows;

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            }

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/experiments`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ experiments: localExperiments })
                });
                const data = await res.json();
                
                if (btn) {
                    btn.style.background = '#059669';
                    btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Saved!';
                    setTimeout(() => {
                        btn.style.background = '#087f5b';
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }, 2500);
                }

                const statusEl = document.getElementById('exp-pdf-status');
                if (statusEl) {
                    statusEl.className = 'mt-3 p-3 rounded-xl text-sm font-semibold flex items-center gap-2 bg-emerald-950/60 border border-emerald-700/60 text-emerald-300';
                    statusEl.style.display = 'flex';
                    statusEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message || 'Experiments saved successfully and preserved in Databank!'}`;
                }
            } catch(e) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                alert("Failed to save experiments.");
            }
        }

        // Generate Lesson Planner Timeline
        async function generateLessonTimeline() {
            const mode = document.getElementById('lesson_planner_mode').value;
            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plan/generate`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mode: mode })
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                }
            } catch(e) {
                alert("Failed to generate plan.");
            }
        }

        async function saveLessonPlannerBulk() {
            const plans = {};
            document.querySelectorAll('.lesson-plan-row').forEach(row => {
                const id = row.getAttribute('data-id');
                plans[id] = {
                    topic_content: row.querySelector('.lp-topic').value,
                    co_id: row.querySelector('.lp-co').value,
                    allocated_hours: parseInt(row.querySelector('.lp-hours').value || 1),
                    pedagogy: row.querySelector('.lp-pedagogy').value,
                    status: row.querySelector('.lp-status').value
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/lesson-plans/bulk-update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans: plans })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Save failed.");
            }
        }

        // Save Practical Marks Entry
        function stepSlider(key, val) {
            const slider = document.getElementById(`slider-${key}`);
            if (!slider) return;
            let target = parseFloat(slider.value) + val;
            target = Math.max(parseFloat(slider.min), Math.min(parseFloat(slider.max), target));
            slider.value = target;
            slider.dispatchEvent(new Event('input'));
        }

        function openGradingModal(regNo, type) {
            const student = studentList.find(s => s.reg_no === regNo);
            if (!student) return;

            currentStudentIndex = studentList.findIndex(s => s.reg_no === regNo);

            document.getElementById('modalStudentName').innerText = student.name;
            document.getElementById('modalStudentReg').innerText = student.reg_no;

            const container = document.getElementById('modalSlidersContainer');
            container.innerHTML = '';

            let rubrics = [];
            if (type === 'table22') {
                rubrics = [
                    { label: '1. Prep & Punctuality (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Procedure (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Observation & Recording (Max 5)', key: 'c3', max: 5, step: 0.5 },
                    { label: '4. Analysis & Outcomes (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Viva Voce (Max 10)', key: 'c5', max: 10, step: 0.5 },
                    { label: '6. Workmanship & Safety (Max 5)', key: 'c6', max: 5, step: 0.5 }
                ];
            } else if (type === 'table23') {
                rubrics = [
                    { label: '1. Idea & Relevance (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Plan & Objectives (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Execution & Safety (Max 10)', key: 'c3', max: 10, step: 0.5 },
                    { label: '4. Analysis & Results (Max 10)', key: 'c4', max: 10, step: 0.5 },
                    { label: '5. Teamwork & Creativity (Max 10)', key: 'c5', max: 10, step: 0.5 }
                ];
            } else if (type === 'table31') {
                rubrics = [
                    { label: '1. Procedure Write-up (Max 10)', key: 'c1', max: 10, step: 0.5 },
                    { label: '2. Setup & Execution (Max 10)', key: 'c2', max: 10, step: 0.5 },
                    { label: '3. Output Results (Max 8)', key: 'c3', max: 8, step: 0.5 },
                    { label: '4. Viva Voce (Max 8)', key: 'c4', max: 8, step: 0.5 },
                    { label: '5. Record Book (Max 4)', key: 'c5', max: 4, step: 0.5 }
                ];
            }

            const stateScores = type === 'table31'
                ? (scoresState[type][regNo][document.getElementById('series_no').value] || {})
                : (scoresState[type][regNo] || {});

            rubrics.forEach(r => {
                const val = stateScores[r.key] || 0;
                const html = `
                    <div class="bg-slate-800/40 p-4 rounded-2xl border border-slate-850">
                        <div class="flex justify-between font-bold text-xs mb-1">
                            <span class="text-slate-400">${r.label}</span>
                            <span class="text-emerald-400 font-mono font-black text-sm" id="modal-val-${r.key}">${val}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="stepSlider('${r.key}', -${r.step})" class="w-8 h-8 rounded-lg bg-slate-850 hover:bg-slate-800 border border-slate-800 text-white font-bold">-</button>
                            <input type="range" id="slider-${r.key}" min="0" max="${r.max}" step="${r.step}" value="${val}" oninput="syncModalSlider('${regNo}', '${r.key}', '${type}')" class="flex-grow">
                            <button onclick="stepSlider('${r.key}', ${r.step})" class="w-8 h-8 rounded-lg bg-slate-850 hover:bg-slate-800 border border-slate-800 text-white font-bold">+</button>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });

            updateModalTotal(regNo, type);

            document.getElementById('gradingModal').classList.remove('hidden');
            document.getElementById('gradingModal').classList.add('flex');
        }

        function syncModalSlider(regNo, key, type) {
            const val = parseFloat(document.getElementById(`slider-${key}`).value) || 0;
            document.getElementById(`modal-val-${key}`).innerText = val;

            if (type === 'table31') {
                const sNo = document.getElementById('series_no').value;
                scoresState[type][regNo][sNo][key] = val;
            } else {
                scoresState[type][regNo][key] = val;
            }

            updateModalTotal(regNo, type);
        }

        function updateModalTotal(regNo, type) {
            let total = 0;
            if (type === 'table31') {
                const sNo = document.getElementById('series_no').value;
                const s = scoresState[type][regNo][sNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                document.getElementById(`score-text-series-${regNo}`).innerText = `${total.toFixed(2)} / 40`;
            } else if (type === 'table23') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0);
                document.getElementById(`score-text-open-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
            } else if (type === 'table22') {
                const s = scoresState[type][regNo];
                total = (s.c1||0) + (s.c2||0) + (s.c3||0) + (s.c4||0) + (s.c5||0) + (s.c6||0);
                document.getElementById(`score-text-exp-${regNo}`).innerText = `${total.toFixed(2)} / 50`;
            }

            document.getElementById('modalTotalScore').innerText = total.toFixed(2);
            recalculateCIA(regNo);
        }

        function navigateStudent(direction) {
            let nextIdx = currentStudentIndex + direction;
            if (nextIdx >= studentList.length) nextIdx = 0;
            if (nextIdx < 0) nextIdx = studentList.length - 1;

            const nextStud = studentList[nextIdx];
            const row = document.querySelector(`.student-row[data-reg-no="${nextStud.reg_no}"]`);
            if (row.classList.contains('hidden')) {
                currentStudentIndex = nextIdx;
                navigateStudent(direction);
                return;
            }

            closeGradingModal();
            openGradingModal(nextStud.reg_no, activeTab);
        }

        function closeGradingModal() {
            document.getElementById('gradingModal').classList.add('hidden');
            document.getElementById('gradingModal').classList.remove('flex');
        }

        function recalculateCIA(regNo) {
            // Day Work 30M
            const s22 = scoresState.table22[regNo] || {};
            const sum22 = (s22.c1||0) + (s22.c2||0) + (s22.c3||0) + (s22.c4||0) + (s22.c5||0) + (s22.c6||0);
            const scaled22 = (sum22 / 50) * 30;
            document.getElementById(`cia-lab-work-${regNo}`).innerText = scaled22.toFixed(2);

            // Open-ended 10M
            const s23 = scoresState.table23[regNo] || {};
            const sum23 = (s23.c1||0) + (s23.c2||0) + (s23.c3||0) + (s23.c4||0) + (s23.c5||0);
            const scaled23 = (sum23 / 50) * 10;
            document.getElementById(`cia-open-${regNo}`).innerText = scaled23.toFixed(2);

            // Series 15M
            const s31_1 = scoresState.table31[regNo]['Series 1'] || {};
            const s31_2 = scoresState.table31[regNo]['Series 2'] || {};
            const sum31_1 = (s31_1.c1||0) + (s31_1.c2||0) + (s31_1.c3||0) + (s31_1.c4||0) + (s31_1.c5||0);
            const sum31_2 = (s31_2.c1||0) + (s31_2.c2||0) + (s31_2.c3||0) + (s31_2.c4||0) + (s31_2.c5||0);
            const avg31 = (sum31_1 + sum31_2) / 2;
            const scaled31 = (avg31 / 40) * 15;
            document.getElementById(`cia-series-${regNo}`).innerText = scaled31.toFixed(2);

            // Attendance 5M
            const att = attendanceMarks[regNo] ? attendanceMarks[regNo].mark : 5;

            // Total CIA (60)
            const cia = scaled22 + scaled23 + scaled31 + att;
            document.getElementById(`cia-total-${regNo}`).innerText = cia.toFixed(2);

            // ESE 40M
            const eseInput = document.getElementById(`ese-mark-${regNo}`);
            const ese = eseInput ? (parseFloat(eseInput.value) || 0) : 0;
            document.getElementById(`cia-ese-${regNo}`).innerText = ese.toFixed(2);

            // Grand Total (100)
            const grand = cia + ese;
            document.getElementById(`cia-grand-${regNo}`).innerText = grand.toFixed(2);
        }

        // Submission triggers
        async function submitExpMarks() {
            const expNoVal = document.getElementById('exp_no').value;
            const titleVal = document.getElementById('exp_title').value;
            const marks = scoresState.table22;

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/experiment`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ experiment_no: expNoVal, title: titleVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message);
                if (data.success) {
                    // Update client-side experimentLogs cache
                    experimentLogs[expNoVal] = studentList.map(s => {
                        const m = marks[s.reg_no] || {};
                        return {
                            reg_no: s.reg_no,
                            title: titleVal,
                            prep_punctuality: m.c1 || 0,
                            setup_procedure: m.c2 || 0,
                            observation_recording: m.c3 || 0,
                            analysis_interpretation: m.c4 || 0,
                            viva_voce: m.c5 || 0,
                            teamwork_discipline: m.c6 || 0,
                            total_score_50: (m.c1||0) + (m.c2||0) + (m.c3||0) + (m.c4||0) + (m.c5||0) + (m.c6||0)
                        };
                    });
                    initExperimentSelect();
                }
            } catch(e) {
                alert("Failed to save continuous log.");
            }
        }

        async function submitOpenEndedMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = {
                    title: document.getElementById(`open-title-${reg}`).value || 'Open-ended Project',
                    ...scoresState.table23[reg]
                };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/open-ended`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save open-ended marks.");
            }
        }

        async function submitSeriesMarks() {
            const sNoVal = document.getElementById('series_no').value;
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = scoresState.table31[reg][sNoVal] || { c1:0, c2:0, c3:0, c4:0, c5:0 };
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/evaluate/series`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ series_no: sNoVal, marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save series marks.");
            }
        }

        async function submitEseMarks() {
            const marks = {};
            studentList.forEach(s => {
                const reg = s.reg_no;
                marks[reg] = parseFloat(document.getElementById(`ese-mark-${reg}`).value) || 0;
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/ese-marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ marks: marks })
                });
                const data = await res.json();
                alert(data.message);
            } catch(e) {
                alert("Failed to save ESE marks.");
            }
        }

        // Series QP config
        const seriesQpConfigs = @json($seriesExams);

        function loadSeriesQpConfig(examName) {
            const conf = seriesQpConfigs[examName] || {};
            document.getElementById('series_qp_max_marks').value = conf.max_marks || 40;
            document.getElementById('series_qp_duration').value = conf.duration_minutes || 120;
            document.getElementById('series_qp_outline').value = typeof conf.question_outline === 'string' 
                ? conf.question_outline 
                : (conf.question_outline ? JSON.stringify(conf.question_outline, null, 2) : '');

            const coChecks = document.querySelectorAll('#series_qp_co_checks .co-checkbox');
            const activeCos = conf.co_tags || [];
            coChecks.forEach(chk => {
                chk.checked = activeCos.includes(chk.value);
            });
        }

        async function saveSeriesQpOutline() {
            const examName = document.getElementById('series_qp_select').value;
            const maxMarks = parseInt(document.getElementById('series_qp_max_marks').value || 40);
            const duration = parseInt(document.getElementById('series_qp_duration').value || 120);
            const outline = document.getElementById('series_qp_outline').value;

            const coTags = [];
            document.querySelectorAll('#series_qp_co_checks .co-checkbox').forEach(chk => {
                if (chk.checked) coTags.push(chk.value);
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/series-exams/configure`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        exam_name: examName,
                        max_marks: maxMarks,
                        duration_minutes: duration,
                        question_outline: outline,
                        co_tags: coTags
                    })
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    seriesQpConfigs[examName] = {
                        max_marks: maxMarks,
                        duration_minutes: duration,
                        question_outline: outline,
                        co_tags: coTags
                    };
                }
            } catch(e) {
                alert("Failed to save blueprint.");
            }
        }

        async function savePracticalCoPoMatrixBtn() {
            const btn = document.getElementById('savePracticalCoPoBtn');
            const origHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            }

            const inputs = document.querySelectorAll('#practicalCoPoMatrixTbody input[data-co]');
            const mappings = {};

            inputs.forEach(input => {
                const co = input.getAttribute('data-co');
                const target = input.getAttribute('data-target');
                const val = input.value ? input.value : '-';
                if (!mappings[co]) {
                    mappings[co] = {};
                }
                mappings[co][target] = val;
            });

            try {
                const res = await fetch(`/api/r26/classroom/practical/${subjectId}/copo-mapping/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ mappings: mappings })
                });
                const data = await res.json();
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                if (data.success || data.status === 'SUCCESS') {
                    alert(data.message || 'Matrix saved!');
                } else {
                    alert(data.message || 'Failed to save matrix.');
                }
            } catch(e) {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
                alert('Network error saving matrix.');
            }
        }

        // Initiate Mid/Exit surveys
        async function manageSurvey(type, action) {
            const endpoint = `/api/r26/classroom/${subjectId}/${type === 'mid' ? 'midsem' : 'exit'}-survey/${action}`;
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                alert(data.message || 'Survey state updated successfully!');
            } catch(e) {
                alert("Failed to update survey.");
            }
        }
    </script>
</body>
</html>
