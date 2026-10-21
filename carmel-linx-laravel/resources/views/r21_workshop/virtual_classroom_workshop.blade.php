<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }} - Mechanical Workshop (R-2021)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        // Zero-flicker Theme Pre-loader
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme-preference');
                if (savedTheme === 'light') {
                    document.documentElement.classList.add('light-theme');
                }
            } catch (e) {}
        })();
    </script>
    <style>
        :root {
            --bg-primary: #080d1a;
            --bg-secondary: #0f172c;
            --bg-card: #0f172c;
            --bg-card-hover: #162038;
            --bg-input: #070e1c;
            --border-color: #1e293b;
            --border-focus: #38bdf8;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-blue: #38bdf8;
            --accent-cyan: #0284c7;
            --accent-emerald: #4ade80;
            --accent-amber: #fbbf24;
            --accent-purple: #c084fc;
            --accent-rose: #f87171;
            --accent-orange: #f97316;
        }

        *, *::before, *::after {
            text-shadow: none !important;
        }

        html, body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            background-color: var(--bg-primary);
            color: #f1f5f9;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            font-size: 0.83rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        h1, h2, h3, h4, h5, h6, .brand-font, .card-title, .modal-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #f8fafc;
            font-weight: 600;
            letter-spacing: -0.01em;
            text-shadow: none !important;
        }

        /* Top Navbar */
        .navbar-custom {
            background: #090e17;
            border-bottom: 2px solid #1e293b;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
            padding: 0.85rem 1.25rem;
            min-height: 72px;
            transition: all 0.2s ease;
        }
        .vc-nav-title {
            color: #f8fafc !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 700;
            font-size: 1.12rem;
            line-height: 1.2;
            letter-spacing: -0.01em;
            text-shadow: none !important;
        }
        .vc-sub-title {
            color: #f8fafc !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
            text-shadow: none !important;
        }
        .vc-nav-subtext {
            color: #94a3b8 !important;
            font-size: 0.62rem;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .vc-code-badge {
            background: rgba(249, 115, 22, 0.2);
            color: #fb923c !important;
            border: 1.5px solid rgba(249, 115, 22, 0.45);
            font-size: 0.82rem;
            letter-spacing: 0.5px;
            font-family: monospace;
            font-weight: 700;
        }
        .vc-staff-pill {
            background: rgba(148, 163, 184, 0.1);
            border: 1px solid rgba(148, 163, 184, 0.2);
            font-size: 0.76rem;
            color: #f1f5f9;
        }
        .vc-staff-pill strong {
            color: #38bdf8;
        }
        .vc-theme-btn {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid #334155;
            border-radius: 8px;
            font-size: 0.76rem;
            transition: all 0.18s ease;
        }
        .vc-theme-btn:hover {
            background: #334155;
            color: #ffffff;
        }

        /* Metadata Strip */
        .vc-metadata-bar {
            background: #0f172c;
            border: 1px solid #1e293b;
            border-radius: 0.75rem;
            padding: 0.65rem 1rem;
            margin-bottom: 0.9rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .vc-meta-badge {
            font-size: 0.75rem;
            font-weight: 700;
            color: #e2e8f0;
            background: rgba(30, 41, 59, 0.7);
            padding: 0.25rem 0.6rem;
            border-radius: 0.4rem;
            border: 1px solid rgba(51, 65, 85, 0.6);
        }
        .vc-meta-badge-sub {
            font-size: 0.72rem;
            font-weight: 600;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.12);
            padding: 0.25rem 0.55rem;
            border-radius: 0.4rem;
            border: 1px solid rgba(56, 189, 248, 0.3);
        }
        .vc-meta-dot {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Tab Strip - Redesigned for Optimal Desktop & Laptop Screen Width */
        .vc-tab-strip-card {
            background: #0f172c;
            border: 1px solid #1e293b;
            border-radius: 0.75rem;
            padding: 0.35rem 0.45rem;
            margin-bottom: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .vc-tab-nav {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-wrap: wrap; /* Automatically adjusts with width of screen in PC or laptop! */
            width: 100%;
        }
        .vc-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.44rem 0.72rem;
            border-radius: 0.55rem;
            font-size: 0.78rem;
            font-weight: 500;
            color: #94a3b8 !important;
            background: transparent !important;
            border: 1.5px solid transparent !important;
            cursor: pointer;
            transition: all 0.16s ease;
            white-space: nowrap;
            text-decoration: none !important;
            line-height: 1.2;
            flex: 0 0 auto;
        }
        .vc-tab-btn:hover {
            color: #f1f5f9 !important;
            background: #1e293b !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
        }
        .vc-tab-btn.active, 
        .vc-tab-btn.nav-link.active {
            color: #ffffff !important;
            background: rgba(249, 115, 22, 0.18) !important;
            border: 1.5px solid #f97316 !important;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.15) !important;
            border-radius: 0.55rem !important;
            font-weight: 700 !important;
        }
        .vc-tab-btn .material-symbols-rounded {
            font-size: 1.05rem;
            line-height: 1;
        }
        .vc-tab-pill {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.12rem 0.42rem;
            border-radius: 4px;
            font-family: monospace;
        }

        /* Remove Number Spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button,
        .fast-input::-webkit-outer-spin-button,
        .fast-input::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type="number"], .fast-input {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }

        /* Fast Input Controls */
        .fast-input {
            width: 74px;
            height: 33px;
            text-align: center;
            font-weight: 600;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 0.88rem;
            color: #38bdf8;
            background-color: #070e1c;
            border: 1.5px solid #334155;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .fast-input:focus {
            color: #38bdf8;
            background-color: #091326;
            border-color: #38bdf8;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
            outline: none;
        }
        .fast-input.is-overridden {
            border-color: #f59e0b;
            color: #fbbf24;
            background-color: rgba(245, 158, 11, 0.15);
            box-shadow: 0 0 0 1px #f59e0b;
        }

        /* High-Contrast Tables */
        .table-custom {
            color: #f8fafc;
            border-color: #1e293b;
            font-size: 0.82rem;
            margin-bottom: 0;
            background-color: #0f172c;
        }
        .table-custom th {
            background-color: #090e17 !important;
            color: #e2e8f0 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.70rem;
            letter-spacing: 0.03em;
            padding: 0.65rem 0.5rem;
            border: 1px solid #1e293b !important;
            white-space: nowrap;
        }
        .table-custom td {
            background-color: #0f172c;
            color: #cbd5e1;
            padding: 0.55rem 0.5rem;
            border: 1px solid #1e293b;
            vertical-align: middle;
        }
        .table-custom tr:hover td {
            background-color: #162038 !important;
        }

        /* Cards */
        .glass-card {
            background: #0f172c;
            border: 1px solid #1e293b;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        /* Section Badges */
        .section-pill-btn {
            padding: 0.45rem 0.85rem;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            border: 1.5px solid #334155;
            background: #090e17;
            color: #94a3b8;
            transition: all 0.18s ease;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .section-pill-btn:hover {
            color: #f8fafc;
            background: #1e293b;
            border-color: #475569;
        }
        .section-pill-btn.active {
            color: #ffffff;
            background: rgba(249, 115, 22, 0.2);
            border-color: #f97316;
            font-weight: 700;
        }

        .batch-badge-1 {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.35);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
        }
        .batch-badge-2 {
            background: rgba(168, 85, 247, 0.15);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.35);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
        }

        /* Autosave pill */
        .autosave-pill {
            font-size: 0.74rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .autosave-saving {
            background: rgba(245, 158, 11, 0.18);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }
        .autosave-success {
            background: rgba(16, 185, 129, 0.18);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        /* Light Theme Overrides */
        body.light-theme {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
        }
        body.light-theme h1, body.light-theme h2, body.light-theme h3,
        body.light-theme h4, body.light-theme h5, body.light-theme h6,
        body.light-theme .brand-font {
            color: #0f172a !important;
        }
        body.light-theme .navbar-custom {
            background: #ffffff !important;
            border-bottom: 2px solid #e2e8f0 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
        }
        body.light-theme .vc-nav-title, body.light-theme .vc-sub-title {
            color: #0f172a !important;
        }
        body.light-theme .vc-nav-subtext {
            color: #64748b !important;
        }
        body.light-theme .vc-metadata-bar, body.light-theme .vc-tab-strip-card, body.light-theme .glass-card {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05) !important;
        }
        body.light-theme .table-custom {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }
        body.light-theme .table-custom th {
            background-color: #f8fafc !important;
            color: #334155 !important;
            border-color: #e2e8f0 !important;
        }
        body.light-theme .table-custom td {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-color: #e2e8f0 !important;
        }
        body.light-theme .table-custom tr:hover td {
            background-color: #f1f5f9 !important;
        }
        body.light-theme .fast-input {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        body.light-theme .section-pill-btn {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #475569 !important;
        }
        body.light-theme .section-pill-btn.active {
            background: #ffedd5 !important;
            border-color: #f97316 !important;
            color: #c2410c !important;
        }
    </style>
</head>
<body>
    <script>
        if (document.documentElement.classList.contains('light-theme')) {
            document.body.classList.add('light-theme');
        }
    </script>

    <!-- Professional Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-2.5 px-3 px-md-4">
        <div class="container-fluid px-0 d-flex align-items-center justify-content-between flex-wrap gap-2 gap-md-3">
            <!-- Left: Logo, Carmel Linx Title, Course Type & Subject Details -->
            <div class="d-flex align-items-center gap-2.5 sm:gap-3 flex-wrap">
                @php
                    $role = session('userRole');
                    $backUrl = ($role === 'Demonstrator') ? '/dashboard/demonstrator' : (($role === 'Trade_Instructor') ? '/dashboard/tradeinstructor' : '/dashboard/lecturer');
                @endphp
                <!-- Carmel Linx Logo & Brand Title -->
                <a href="{{ $backUrl }}" class="d-flex align-items-center gap-2 text-decoration-none shrink-0 me-1" title="Return to Dashboard">
                    <img src="{{ asset('logo.jpg') }}" alt="Carmel Linx Logo" style="width: 36px; height: 36px; border-radius: 9px; object-fit: cover; border: 1.5px solid rgba(249, 115, 22, 0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                    <div class="d-flex flex-column leading-none">
                        <span class="vc-nav-title">Carmel Linx</span>
                        <span class="vc-nav-subtext">Mechanical Workshop &bull; R-2021</span>
                    </div>
                </a>

                <div class="vr bg-secondary opacity-50 d-none d-md-block mx-1" style="height: 32px;"></div>

                <!-- Course Type Badge, Course Code & Subject Name -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge px-2.5 py-1 vc-code-badge">
                        {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}
                    </span>
                    <span class="vc-sub-title">
                        {{ $batchSubject->subject_name }}
                    </span>
                    <span class="badge bg-warning text-dark px-2 py-0.5 fw-bold" style="font-size: 0.68rem;">
                        REV 2021 SCHEME
                    </span>
                    <span class="badge bg-secondary px-2 py-0.5 font-monospace" style="font-size: 0.68rem;">
                        WORKSHOP LAB
                    </span>
                </div>
            </div>

            <!-- Right: Staff Name Pill, Theme Switcher & Red Return Button -->
            <div class="d-flex align-items-center gap-2 ms-auto">
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill vc-staff-pill">
                    <i class="fa-solid fa-user-tie text-info"></i>
                    <strong>{{ session('userName') ?? 'Faculty' }}</strong>
                </span>

                <!-- Dynamic Saving Pill -->
                <span id="saveStatusIndicator" class="autosave-pill autosave-saving" style="display: none;"></span>

                <!-- Theme Toggle Button -->
                <button type="button" onclick="toggleTheme()" class="btn btn-sm px-2.5 py-1.5 d-flex align-items-center gap-1.5 vc-theme-btn" id="themeToggleBtn" title="Toggle Light/Dark Theme">
                    <span id="themeToggleIcon" class="material-symbols-rounded" style="font-size: 1.05rem; line-height: 1;">light_mode</span>
                    <span id="themeToggleText" class="fw-bold d-none d-sm-inline">Light Mode</span>
                </button>

                <!-- Return Dashboard Button (Red) -->
                <a href="{{ $backUrl ?? '/dashboard/lecturer' }}" class="btn btn-danger btn-sm px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow" style="font-size: 0.78rem; background-color: #dc2626; border-color: #b91c1c; border-radius: 8px;" title="Return to Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-2.5 px-3 px-md-4">

        <!-- CARD 2: Below Top Bar - Academic Metadata Row & Quick Syllabus Upload / View -->
        <div class="vc-metadata-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="vc-meta-badge">Batch: {{ $classroom->classroom_id ?? $batchSubject->classroom_id }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge">SEM: {{ $classroom->current_semester ?? $batchSubject->semester ?? 3 }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge">Branch: {{ $batchSubject->branch ?? $classroom->branch ?? 'ME' }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge-sub">Revision: R-2021</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge-sub">CIA: 75M | ESE: 50M</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="badge bg-primary text-white" style="font-size: 0.72rem;">6 Sections &bull; 2 Batches Split</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                <!-- Batch & Section Setup Button (Cyan) - Kept out of main tab for maximum desktop/laptop space -->
                <button type="button" class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold text-info border border-info" data-bs-toggle="modal" data-bs-target="#modalBatchSetup" style="font-size: 0.76rem; border-radius: 0.5rem; background: rgba(56, 189, 248, 0.1);" title="Configure Batch Split, Roll Cutoff & Trade Section Allocations">
                    <span class="material-symbols-rounded text-info" style="font-size: 1.05rem;">tune</span>
                    <span>Batch &amp; Trade Setup</span>
                </button>
                <!-- Upload Syllabus Button (Blue) -->
                <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadSyllabus" style="font-size: 0.76rem; border-radius: 0.5rem; background-color: #2563eb; border-color: #1d4ed8;" title="Upload or Update Syllabus PDF">
                    <span class="material-symbols-rounded" style="font-size: 1.05rem;">cloud_upload</span>
                    <span>Upload Syllabus</span>
                </button>
                <!-- View Syllabus Button (Green) -->
                @if(!empty($courseFile->syllabus_pdf_path))
                    <a href="{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold" style="font-size: 0.76rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1.5px solid #10b981;" title="View Uploaded Syllabus Document (PDF)">
                        <span class="material-symbols-rounded" style="font-size: 1.05rem; color: #10b981;">picture_as_pdf</span>
                        <span style="color: #10b981;">View Syllabus</span>
                    </a>
                @else
                    <button type="button" class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalUploadSyllabus" title="No syllabus PDF uploaded yet. Click to upload." style="font-size: 0.76rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.08); color: #10b981; border: 1.5px dashed rgba(16, 185, 129, 0.5);">
                        <span class="material-symbols-rounded" style="font-size: 1.05rem; color: #10b981;">picture_as_pdf</span>
                        <span style="color: #10b981;">View Syllabus</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- CARD 3: Sleek Desktop Tab Navigation (Fits 100% Screen Width Without Horizontal Cutoff) -->
        <div class="vc-tab-strip-card">
            <nav class="vc-tab-nav" id="r21WorkshopTabs" role="tablist">
                <!-- TAB 1: CIA Register -->
                <button class="vc-tab-btn nav-link active" id="tab-cia-link" data-bs-toggle="tab" data-bs-target="#tab-cia" type="button" role="tab" aria-selected="true">
                    <span class="material-symbols-rounded text-warning">table_chart</span>
                    <span>CIA Register</span>
                    <span class="badge bg-warning text-dark vc-tab-pill">75M</span>
                </button>

                <!-- TAB 2: Work Status Matrix -->
                <button class="vc-tab-btn nav-link" id="tab-matrix-link" data-bs-toggle="tab" data-bs-target="#tab-matrix" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-cyan-400">fact_check</span>
                    <span>Work Matrix</span>
                </button>

                <!-- TAB 3: Continuous Work Evaluation -->
                <button class="vc-tab-btn nav-link" id="tab-work-link" data-bs-toggle="tab" data-bs-target="#tab-work" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-primary">engineering</span>
                    <span>Work Evaluation</span>
                    <span class="badge bg-primary vc-tab-pill">37.5M</span>
                </button>

                <!-- TAB 4: Rough & Fair Record -->
                <button class="vc-tab-btn nav-link" id="tab-record-link" data-bs-toggle="tab" data-bs-target="#tab-record" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-emerald-400">menu_book</span>
                    <span>Record (Rough &amp; Fair)</span>
                    <span class="badge bg-success vc-tab-pill">7.5M</span>
                </button>

                <!-- TAB 5: Trade Tests -->
                <button class="vc-tab-btn nav-link" id="tab-test-link" data-bs-toggle="tab" data-bs-target="#tab-test" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded" style="color: #c084fc;">quiz</span>
                    <span>Trade Tests</span>
                    <span class="badge vc-tab-pill text-white" style="background-color: #9333ea;">15M</span>
                </button>

                <!-- TAB 6: Attendance Register -->
                <button class="vc-tab-btn nav-link" id="tab-attendance-link" data-bs-toggle="tab" data-bs-target="#tab-attendance" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-cyan-400">how_to_reg</span>
                    <span>Attendance</span>
                    <span class="badge bg-info text-dark vc-tab-pill">15M</span>
                </button>

                <!-- TAB 7: Attainment Calculation -->
                <button class="vc-tab-btn nav-link" id="tab-attainment-link" data-bs-toggle="tab" data-bs-target="#tab-attainment" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-emerald-400">insights</span>
                    <span>Attainment</span>
                    <span class="badge bg-success text-white vc-tab-pill">CO/PO</span>
                </button>

                <!-- TAB 8: Reports Hub -->
                <button class="vc-tab-btn nav-link" id="tab-reports-link" data-bs-toggle="tab" data-bs-target="#tab-reports" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-amber-400">print</span>
                    <span>Reports &amp; Registers</span>
                    <span class="badge bg-warning text-dark vc-tab-pill">PDF</span>
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT CONTAINER -->
        <div class="tab-content" id="r21WorkshopTabContent">

            <!-- ========================================================= -->
            <!-- TAB 1: CONSOLIDATED CIA REGISTER                          -->
            <!-- ========================================================= -->
            <div class="tab-pane fade show active" id="tab-cia" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-award me-1.5 text-warning"></i>
                                Consolidated CIA Register (Total CIA: 75 Marks | ESE: 50 Marks)
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Live Calculation Formula: <strong>Work Evaluation (37.5M)</strong> + <strong>Open-Ended Record (7.5M)</strong> + <strong>3 Tests Avg (15.0M)</strong> + <strong>Attendance (15.0M)</strong> = <strong>75M CIA</strong>.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <input type="text" id="ciaSearchInput" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Search student..." style="width: 170px; font-size: 0.78rem;" onkeyup="filterCiaTable()">
                            <select id="ciaBatchFilter" class="form-select form-select-sm bg-dark text-light border-secondary" style="width: 120px; font-size: 0.78rem;" onchange="filterCiaTable()">
                                <option value="ALL">All Batches</option>
                                <option value="1">Batch 1</option>
                                <option value="2">Batch 2</option>
                            </select>
                            <button type="button" class="btn btn-warning btn-sm px-3 fw-bold text-dark" onclick="saveConsolidatedCiaFast()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save CIA Marks
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="consolidatedCiaTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">Roll</th>
                                    <th style="width: 110px;">Reg No</th>
                                    <th class="text-start" style="min-width: 160px;">Student Name</th>
                                    <th style="width: 70px;">Batch</th>
                                    <th style="min-width: 190px;">Assigned Sections (3 Trades)</th>
                                    <th style="width: 125px;" class="text-info">
                                        Work Eval<br><small class="text-secondary">(Max 37.5M)</small>
                                    </th>
                                    <th style="width: 115px;" class="text-emerald-400">
                                        Open-Ended Record<br><small class="text-secondary">(Max 7.5M)</small>
                                    </th>
                                    <th style="width: 115px;" style="color: #c084fc;">
                                        Test Average<br><small class="text-secondary">(Max 15.0M)</small>
                                    </th>
                                    <th style="width: 115px;" class="text-cyan-400">
                                        Attendance Mark<br><small class="text-secondary">(Max 15.0M)</small>
                                    </th>
                                    <th style="width: 95px;" class="text-warning fw-bold">
                                        Total CIA<br><small class="text-secondary">(Max 75M)</small>
                                    </th>
                                    <th style="width: 85px;">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $st)
                                    @php
                                        $score = $calculatedScores[$st->reg_no] ?? [];
                                        $batchNo = $score['batch_no'] ?? '1';
                                        $assignedSecs = $score['assigned_sections'] ?? [];
                                        $isPass = ($score['total_cia_75'] ?? 0) >= 30;
                                    @endphp
                                    <tr data-reg="{{ $st->reg_no }}" data-batch="{{ $batchNo }}" class="cia-row">
                                        <td class="fw-bold">{{ $st->roll_no ?? '-' }}</td>
                                        <td class="font-monospace text-secondary small">{{ $st->reg_no }}</td>
                                        <td class="text-start fw-semibold text-light">
                                            {{ $st->name }}
                                            @if($st->academic_status && $st->academic_status !== 'Active')
                                                <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">{{ $st->academic_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="batch-badge-{{ $batchNo }}">B{{ $batchNo }}</span>
                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($assignedSecs as $sName)
                                                    <span class="badge bg-dark border border-secondary text-light font-monospace" style="font-size: 0.65rem;">
                                                        {{ $sName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <!-- Work Score (37.5M) -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="37.5"
                                                   class="fast-input cia-work-input {{ !empty($score['is_work_overridden']) ? 'is-overridden' : '' }}"
                                                   data-reg="{{ $st->reg_no }}"
                                                   value="{{ number_format($score['work_score_375'] ?? 0, 2) }}"
                                                   oninput="recalculateRowCia('{{ $st->reg_no }}')">
                                        </td>
                                        <!-- Open Ended Record (7.5M) -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="7.5"
                                                   class="fast-input cia-open-input {{ !empty($score['is_open_overridden']) ? 'is-overridden' : '' }}"
                                                   data-reg="{{ $st->reg_no }}"
                                                   value="{{ number_format($score['open_ended_75'] ?? 0, 2) }}"
                                                   oninput="recalculateRowCia('{{ $st->reg_no }}')">
                                        </td>
                                        <!-- Test Average (15.0M) -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="15"
                                                   class="fast-input cia-test-input {{ !empty($score['is_test_overridden']) ? 'is-overridden' : '' }}"
                                                   data-reg="{{ $st->reg_no }}"
                                                   value="{{ number_format($score['test_avg_15'] ?? 0, 2) }}"
                                                   oninput="recalculateRowCia('{{ $st->reg_no }}')">
                                        </td>
                                        <!-- Attendance Mark (15.0M) -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="15"
                                                   class="fast-input cia-att-input {{ !empty($score['is_att_overridden']) ? 'is-overridden' : '' }}"
                                                   data-reg="{{ $st->reg_no }}"
                                                   value="{{ number_format($score['att_mark_15'] ?? 0, 2) }}"
                                                   oninput="recalculateRowCia('{{ $st->reg_no }}')">
                                        </td>
                                        <!-- Total CIA (75M) -->
                                        <td>
                                            <span class="badge bg-warning text-dark font-monospace fs-6 px-2 py-1 row-total-cia" id="total_cia_{{ $st->reg_no }}">
                                                {{ $score['total_cia_75'] ?? 0 }}
                                            </span>
                                        </td>
                                        <!-- Result -->
                                        <td id="status_cell_{{ $st->reg_no }}">
                                            @if($isPass)
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.68rem;">Passed</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1" style="font-size: 0.68rem;">Below Min</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-secondary py-4">No enrolled students found for this classroom.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 2: WORK STATUS & EVALUATION MATRIX (DETAILED VIEW)   -->
            <!-- ========================================================= -->
            @php
                $totalMatrixStudents = count($students);
                $fullEvaluatedStudents = 0;
                $pendingWorksStudents = 0;
                $complete3TradesStudents = 0;
                $passStudents = 0;

                foreach ($students as $st) {
                    $r = $st->reg_no;
                    $sc = $calculatedScores[$r] ?? [];
                    $al = $allocations->get($r);
                    $bN = $al->batch_no ?? ($sc['batch_no'] ?? '1');
                    $defSecs = $bN == '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry'];
                    $assSecs = $al->assigned_sections ?? ($sc['assigned_sections'] ?? $defSecs);
                    if (empty($assSecs)) $assSecs = $defSecs;

                    $eCount = 0;
                    $doneTrades = 0;
                    foreach ($assSecs as $secN) {
                        $w = $workEvals[$r][$secN] ?? null;
                        $s = $sectionEvals[$r][$secN] ?? null;

                        $w1 = $w && $w->work_1_mark !== null && $w->work_1_mark !== '' ? floatval($w->work_1_mark) : null;
                        $w2 = $w && $w->work_2_mark !== null && $w->work_2_mark !== '' ? floatval($w->work_2_mark) : null;
                        if ($w1 !== null) $eCount++;
                        if ($w2 !== null) $eCount++;

                        $hasR = $s && ($s->rough_record_mark !== null && $s->rough_record_mark !== '');
                        $hasT = $s && ($s->test_mark !== null && $s->test_mark !== '' || !empty($s->is_test_absent));
                        if ($w1 !== null && $w2 !== null && $hasR && $hasT) {
                            $doneTrades++;
                        }
                    }

                    if ($eCount === 6) $fullEvaluatedStudents++;
                    else $pendingWorksStudents++;

                    if ($doneTrades >= count($assSecs) && count($assSecs) > 0) $complete3TradesStudents++;
                    if (floatval($sc['total_cia_75'] ?? 0) >= 30) $passStudents++;
                }
            @endphp
            <div class="tab-pane fade" id="tab-matrix" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <!-- Top Summary Stats Strip -->
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-2">
                            <div class="p-2 rounded bg-dark border border-secondary text-center">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.68rem; font-weight: 700;">Enrolled</div>
                                <div class="text-light fw-bold fs-6 font-monospace">{{ $totalMatrixStudents }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="p-2 rounded bg-dark border border-success-subtle text-center">
                                <div class="text-success small text-uppercase" style="font-size: 0.68rem; font-weight: 700;">6/6 Works Done</div>
                                <div class="text-success fw-bold fs-6 font-monospace">{{ $fullEvaluatedStudents }} <small class="text-secondary" style="font-size: 0.65rem;">/ {{ $totalMatrixStudents }}</small></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded bg-dark border border-warning-subtle text-center">
                                <div class="text-warning small text-uppercase" style="font-size: 0.68rem; font-weight: 700;">Works Pending (<6)</div>
                                <div class="text-warning fw-bold fs-6 font-monospace">{{ $pendingWorksStudents }} <small class="text-secondary" style="font-size: 0.65rem;">Students</small></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2 rounded bg-dark border border-info-subtle text-center">
                                <div class="text-info small text-uppercase" style="font-size: 0.68rem; font-weight: 700;">3/3 Trades Complete</div>
                                <div class="text-info fw-bold fs-6 font-monospace">{{ $complete3TradesStudents }} <small class="text-secondary" style="font-size: 0.65rem;">/ {{ $totalMatrixStudents }}</small></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="p-2 rounded bg-dark border border-secondary text-center">
                                <div class="text-emerald-400 small text-uppercase" style="font-size: 0.68rem; font-weight: 700;">Eligible (&ge;30)</div>
                                <div class="text-emerald-400 fw-bold fs-6 font-monospace">{{ $passStudents }} <small class="text-secondary" style="font-size: 0.65rem;">/ {{ $totalMatrixStudents }}</small></div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Controls Bar -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom border-secondary">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-table-cells-large me-1.5 text-cyan-400"></i>
                                Continuous Works Status &amp; CIA Matrix
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Single-look inspection of all 6 Continuous Works, Open-Ended Records, and Trade Tests across the 3 assigned trade sections.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <input type="text" 
                                   id="matrixSearchInput" 
                                   class="form-control form-control-sm bg-dark text-light border-secondary" 
                                   placeholder="Search student..." 
                                   style="width: 160px; font-size: 0.78rem;" 
                                   onkeyup="filterStatusMatrixTable()">
                            <select id="matrixBatchFilter" 
                                    class="form-select form-select-sm bg-dark text-light border-secondary" 
                                    style="width: 115px; font-size: 0.78rem;" 
                                    onchange="filterStatusMatrixTable()">
                                <option value="ALL">All Batches</option>
                                <option value="1">Batch 1</option>
                                <option value="2">Batch 2</option>
                            </select>
                            <select id="matrixStatusFilter" 
                                    class="form-select form-select-sm bg-dark text-light border-secondary" 
                                    style="width: 140px; font-size: 0.78rem;" 
                                    onchange="filterStatusMatrixTable()">
                                <option value="ALL">All Works Status</option>
                                <option value="ALL_DONE">6/6 Works Done</option>
                                <option value="HAS_PENDING">Pending Works (<6)</option>
                                <option value="ZERO_WORKS">0 Works Evaluated</option>
                            </select>
                            <select id="matrixResultFilter" 
                                    class="form-select form-select-sm bg-dark text-light border-secondary" 
                                    style="width: 110px; font-size: 0.78rem;" 
                                    onchange="filterStatusMatrixTable()">
                                <option value="ALL">All Results</option>
                                <option value="PASS">Passed (&ge;30)</option>
                                <option value="BELOW">Below Min</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                                <i class="fa-solid fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>

                    <!-- Detailed Table View -->
                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="statusMatrixTable">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 45px;">Roll</th>
                                    <th rowspan="2" class="text-start" style="min-width: 165px;">Student Name</th>
                                    <th rowspan="2" style="width: 55px;">Batch</th>
                                    <th rowspan="2" style="min-width: 130px;" class="text-warning">
                                        Works Status (6)<br><small class="text-secondary">Evaluated vs Pending</small>
                                    </th>
                                    <th colspan="4" class="text-primary border-bottom-0" style="min-width: 200px;">Assigned Trade 1</th>
                                    <th colspan="4" class="text-info border-bottom-0" style="min-width: 200px;">Assigned Trade 2</th>
                                    <th colspan="4" class="text-emerald-400 border-bottom-0" style="min-width: 200px;">Assigned Trade 3</th>
                                    <th colspan="4" class="text-light border-bottom-0" style="min-width: 190px;">Continuous CIA (75M)</th>
                                    <th rowspan="2" style="width: 80px;" class="text-warning fw-bold">Total CIA<br><small>(75M)</small></th>
                                    <th rowspan="2" style="width: 75px;">Result</th>
                                </tr>
                                <tr>
                                    <!-- Trade 1 -->
                                    <th style="font-size: 0.65rem;">W1 (20)</th>
                                    <th style="font-size: 0.65rem;">W2 (20)</th>
                                    <th style="font-size: 0.65rem;">Rec (20)</th>
                                    <th style="font-size: 0.65rem;">Test (15)</th>
                                    <!-- Trade 2 -->
                                    <th style="font-size: 0.65rem;">W1 (20)</th>
                                    <th style="font-size: 0.65rem;">W2 (20)</th>
                                    <th style="font-size: 0.65rem;">Rec (20)</th>
                                    <th style="font-size: 0.65rem;">Test (15)</th>
                                    <!-- Trade 3 -->
                                    <th style="font-size: 0.65rem;">W1 (20)</th>
                                    <th style="font-size: 0.65rem;">W2 (20)</th>
                                    <th style="font-size: 0.65rem;">Rec (20)</th>
                                    <th style="font-size: 0.65rem;">Test (15)</th>
                                    <!-- CIA Breakdown -->
                                    <th style="font-size: 0.65rem;" class="text-warning">Work (37.5)</th>
                                    <th style="font-size: 0.65rem;" class="text-success">Rec (7.5)</th>
                                    <th style="font-size: 0.65rem;" style="color: #c084fc;">Test (15)</th>
                                    <th style="font-size: 0.65rem;" class="text-cyan-400">Att (15)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                    @php
                                        $rNo = $st->reg_no;
                                        $score = $calculatedScores[$rNo] ?? [];
                                        $alloc = $allocations->get($rNo);
                                        $batchNo = $alloc->batch_no ?? ($score['batch_no'] ?? '1');
                                        $defaultSecs = $batchNo == '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry'];
                                        $assignedSecs = $alloc->assigned_sections ?? ($score['assigned_sections'] ?? $defaultSecs);
                                        if (empty($assignedSecs)) $assignedSecs = $defaultSecs;

                                        $evalWorksCount = 0;
                                        $secMatrix = [];

                                        foreach ($assignedSecs as $sName) {
                                            $wRec = $workEvals[$rNo][$sName] ?? null;
                                            $sRec = $sectionEvals[$rNo][$sName] ?? null;

                                            $w1 = $wRec && $wRec->work_1_mark !== null && $wRec->work_1_mark !== '' ? floatval($wRec->work_1_mark) : null;
                                            $w2 = $wRec && $wRec->work_2_mark !== null && $wRec->work_2_mark !== '' ? floatval($wRec->work_2_mark) : null;
                                            if ($w1 !== null) $evalWorksCount++;
                                            if ($w2 !== null) $evalWorksCount++;

                                            $rough = $sRec && $sRec->rough_record_mark !== null && $sRec->rough_record_mark !== '' ? floatval($sRec->rough_record_mark) : null;
                                            $fair  = $sRec && $sRec->fair_record_mark !== null && $sRec->fair_record_mark !== '' ? floatval($sRec->fair_record_mark) : null;
                                            $hasRec = ($rough !== null || $fair !== null);
                                            $recTot = $hasRec ? (($rough ?? 0) + ($fair ?? 0)) : null;

                                            $isTestAbs = !empty($sRec->is_test_absent);
                                            $testMark = $sRec && $sRec->test_mark !== null && $sRec->test_mark !== '' ? floatval($sRec->test_mark) : null;

                                            $secMatrix[] = [
                                                'name' => $sName,
                                                'w1' => $w1,
                                                'w2' => $w2,
                                                'recTot' => $recTot,
                                                'testMark' => $testMark,
                                                'isTestAbs' => $isTestAbs,
                                            ];
                                        }

                                        while (count($secMatrix) < 3) {
                                            $secMatrix[] = ['name' => '-', 'w1' => null, 'w2' => null, 'recTot' => null, 'testMark' => null, 'isTestAbs' => false];
                                        }

                                        $pendingWorksCount = max(0, 6 - $evalWorksCount);
                                        $totalCia = floatval($score['total_cia_75'] ?? 0);
                                        $isPass = $totalCia >= 30;
                                    @endphp
                                    <tr class="matrix-row" data-batch="{{ $batchNo }}" data-eval-works="{{ $evalWorksCount }}" data-pending-works="{{ $pendingWorksCount }}" data-pass="{{ $isPass ? '1' : '0' }}">
                                        <td class="fw-bold font-monospace">{{ $st->roll_no ?? '-' }}</td>
                                        <td class="text-start">
                                            <div class="fw-semibold text-light">{{ $st->name }}</div>
                                            <div class="font-monospace text-secondary small" style="font-size: 0.72rem;">{{ $st->sbte_reg_no ?? $rNo }}</div>
                                        </td>
                                        <td>
                                            <span class="batch-badge-{{ $batchNo }}">B{{ $batchNo }}</span>
                                        </td>
                                        <!-- Works Status Badge in Single Look -->
                                        <td>
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <span class="badge {{ $evalWorksCount === 6 ? 'bg-success text-white' : ($evalWorksCount > 0 ? 'bg-warning text-dark' : 'bg-danger text-white') }} px-2 py-1 mb-1 font-monospace" style="font-size: 0.72rem;">
                                                    {{ $evalWorksCount }} / 6 Done
                                                </span>
                                                @if($pendingWorksCount > 0)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger px-1.5 py-0.5" style="font-size: 0.65rem;">
                                                        {{ $pendingWorksCount }} Pending
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success px-1.5 py-0.5" style="font-size: 0.65rem;">
                                                        <i class="fa-solid fa-check me-0.5"></i> All 6 Done
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Trade 1 -->
                                        @php $t1 = $secMatrix[0]; @endphp
                                        <td>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.65rem;">{{ $t1['name'] }}</div>
                                            @if($t1['w1'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t1['w1'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t1['w2'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t1['w2'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t1['recTot'] !== null)
                                                <span class="fw-bold text-emerald-400 font-monospace">{{ $t1['recTot'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t1['isTestAbs'])
                                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Abs</span>
                                            @elseif($t1['testMark'] !== null)
                                                <span class="fw-bold font-monospace" style="color: #c084fc;">{{ $t1['testMark'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>

                                        <!-- Trade 2 -->
                                        @php $t2 = $secMatrix[1]; @endphp
                                        <td>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.65rem;">{{ $t2['name'] }}</div>
                                            @if($t2['w1'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t2['w1'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t2['w2'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t2['w2'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t2['recTot'] !== null)
                                                <span class="fw-bold text-emerald-400 font-monospace">{{ $t2['recTot'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t2['isTestAbs'])
                                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Abs</span>
                                            @elseif($t2['testMark'] !== null)
                                                <span class="fw-bold font-monospace" style="color: #c084fc;">{{ $t2['testMark'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>

                                        <!-- Trade 3 -->
                                        @php $t3 = $secMatrix[2]; @endphp
                                        <td>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.65rem;">{{ $t3['name'] }}</div>
                                            @if($t3['w1'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t3['w1'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t3['w2'] !== null)
                                                <span class="fw-bold text-light font-monospace">{{ $t3['w2'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t3['recTot'] !== null)
                                                <span class="fw-bold text-emerald-400 font-monospace">{{ $t3['recTot'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t3['isTestAbs'])
                                                <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">Abs</span>
                                            @elseif($t3['testMark'] !== null)
                                                <span class="fw-bold font-monospace" style="color: #c084fc;">{{ $t3['testMark'] }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.65rem;">-</span>
                                            @endif
                                        </td>

                                        <!-- Scaled CIA Breakdown -->
                                        <td class="font-monospace text-warning">{{ number_format($score['work_score_375'] ?? 0, 2) }}</td>
                                        <td class="font-monospace text-emerald-400">{{ number_format($score['open_ended_75'] ?? 0, 2) }}</td>
                                        <td class="font-monospace" style="color: #c084fc;">{{ number_format($score['test_avg_15'] ?? 0, 2) }}</td>
                                        <td class="font-monospace text-cyan-400">{{ number_format($score['att_mark_15'] ?? 0, 2) }}</td>

                                        <!-- Total CIA -->
                                        <td>
                                            <span class="badge bg-warning text-dark font-monospace fs-6 px-2 py-1">
                                                {{ $totalCia }}
                                            </span>
                                        </td>

                                        <!-- Result -->
                                        <td>
                                            @if($isPass)
                                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.68rem;">Passed</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1" style="font-size: 0.68rem;">Below Min</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BATCH SETUP IS MOVED TO DEDICATED MODAL #modalBatchSetup TO MAXIMIZE SCREEN WIDTH -->

            <!-- ========================================================= -->
            <!-- TAB 3: WORK EVALUATION (37.5M CONTINUOUS JOB HUB)         -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-work" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-wrench me-1.5 text-primary"></i>
                                Continuous Work Evaluation &bull; <span id="workSectionTitle">Machine shop</span>
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                In <strong>Machine shop</strong>: 4 Works (Max 20M each, Section Avg = sum/4). In <strong>Sheet metal</strong>: Sheet Metal + Aluminium Fabrication Works. In other trades: 2 Works. Across 3 sections, scaled to <strong>37.5 Marks</strong>.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="saveActiveSectionWorks()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Section Works
                            </button>
                        </div>
                    </div>

                    <!-- Section Selector Pills & Batch Filters -->
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap bg-dark p-2.5 rounded border border-secondary">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-secondary small fw-bold me-1">Select Section:</span>
                            @foreach($sectionsConfig as $idx => $sec)
                                <a href="javascript:void(0)" class="section-pill-btn {{ $idx === 0 ? 'active' : '' }}" data-sec="{{ $sec }}" onclick="switchWorkSection('{{ $sec }}')">
                                    <i class="fa-solid fa-screwdriver-wrench"></i> {{ $sec }}
                                </a>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="text-secondary small fw-bold me-1"><i class="fa-solid fa-filter me-1"></i>Filter Students:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-info active work-batch-filter-btn" data-batch="ASSIGNED" onclick="setWorkBatchFilter('ASSIGNED', this)">
                                    Assigned Batch Only
                                </button>
                                <button type="button" class="btn btn-outline-info work-batch-filter-btn" data-batch="1" onclick="setWorkBatchFilter('1', this)">
                                    Batch 1
                                </button>
                                <button type="button" class="btn btn-outline-info work-batch-filter-btn" data-batch="2" onclick="setWorkBatchFilter('2', this)">
                                    Batch 2
                                </button>
                                <button type="button" class="btn btn-outline-info work-batch-filter-btn" data-batch="ALL" onclick="setWorkBatchFilter('ALL', this)">
                                    All Students
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="workEvaluationTable">
                            <thead id="workTableHeader">
                                <!-- Populated dynamically based on section type in JavaScript -->
                            </thead>
                            <tbody id="workTableBody">
                                <!-- Populated dynamically by switchWorkSection() in JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 4: ROUGH & FAIR RECORD (OPEN-ENDED 7.5M HUB)           -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-record" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-book-open me-1.5 text-emerald-400"></i>
                                Record Work Evaluation &bull; <span id="recordSectionTitle">Machine shop</span>
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Evaluated in each section for marks out of 20 (Rough 10M, Fair 10M). In <strong>Sheet metal</strong>: Sheet Metal + Aluminium Fabrication rough & fair are evaluated. Scaled to <strong>7.5 Marks</strong> CIA Open-Ended component.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-success btn-sm px-3 fw-bold" onclick="saveActiveSectionRecords()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Record Marks
                            </button>
                        </div>
                    </div>

                    <!-- Section Selector Pills & Batch Filters -->
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap bg-dark p-2.5 rounded border border-secondary">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-secondary small fw-bold me-1">Select Section:</span>
                            @foreach($sectionsConfig as $idx => $sec)
                                <a href="javascript:void(0)" class="section-pill-btn record-sec-btn {{ $idx === 0 ? 'active' : '' }}" data-sec="{{ $sec }}" onclick="switchRecordSection('{{ $sec }}')">
                                    <i class="fa-solid fa-pen-nib"></i> {{ $sec }}
                                </a>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="text-secondary small fw-bold me-1"><i class="fa-solid fa-filter me-1"></i>Filter Students:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-success active record-batch-filter-btn" data-batch="ASSIGNED" onclick="setRecordBatchFilter('ASSIGNED', this)">
                                    Assigned Batch Only
                                </button>
                                <button type="button" class="btn btn-outline-success record-batch-filter-btn" data-batch="1" onclick="setRecordBatchFilter('1', this)">
                                    Batch 1
                                </button>
                                <button type="button" class="btn btn-outline-success record-batch-filter-btn" data-batch="2" onclick="setRecordBatchFilter('2', this)">
                                    Batch 2
                                </button>
                                <button type="button" class="btn btn-outline-success record-batch-filter-btn" data-batch="ALL" onclick="setRecordBatchFilter('ALL', this)">
                                    All Students
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="recordEvaluationTable">
                            <thead id="recordTableHeader">
                                <!-- Populated dynamically based on section type in JavaScript -->
                            </thead>
                            <tbody id="recordTableBody">
                                <!-- Populated dynamically by switchRecordSection() -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 5: TRADE TESTS (15.0M AVERAGE HUB)                    -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-test" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-graduation-cap me-1.5" style="color: #c084fc;"></i>
                                Section Trade Tests (3 Tests &bull; Max 15 Marks Each)
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                One test conducted in each of the 3 sections taken by the student. Average of the 3 tests gives the <strong>15 Marks CIA Test Component</strong>.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn text-white btn-sm px-3 fw-bold" style="background-color: #9333ea;" onclick="saveActiveSectionTests()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Test Marks
                            </button>
                        </div>
                    </div>

                    <!-- Section Selector Pills & Batch Filters -->
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap bg-dark p-2.5 rounded border border-secondary">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-secondary small fw-bold me-1">Select Section:</span>
                            @foreach($sectionsConfig as $idx => $sec)
                                <a href="javascript:void(0)" class="section-pill-btn test-sec-btn {{ $idx === 0 ? 'active' : '' }}" data-sec="{{ $sec }}" onclick="switchTestSection('{{ $sec }}')">
                                    <i class="fa-solid fa-file-signature"></i> {{ $sec }}
                                </a>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="text-secondary small fw-bold me-1"><i class="fa-solid fa-filter me-1"></i>Filter Students:</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary active test-batch-filter-btn" data-batch="ASSIGNED" onclick="setTestBatchFilter('ASSIGNED', this)">
                                    Assigned Batch Only
                                </button>
                                <button type="button" class="btn btn-outline-secondary test-batch-filter-btn" data-batch="1" onclick="setTestBatchFilter('1', this)">
                                    Batch 1
                                </button>
                                <button type="button" class="btn btn-outline-secondary test-batch-filter-btn" data-batch="2" onclick="setTestBatchFilter('2', this)">
                                    Batch 2
                                </button>
                                <button type="button" class="btn btn-outline-secondary test-batch-filter-btn" data-batch="ALL" onclick="setTestBatchFilter('ALL', this)">
                                    All Students
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="testEvaluationTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">Roll</th>
                                    <th style="width: 110px;">Reg No</th>
                                    <th class="text-start" style="min-width: 160px;">Student Name</th>
                                    <th style="width: 70px;">Batch</th>
                                    <th style="width: 130px;" style="color: #c084fc;">Trade Test Mark<br><small class="text-secondary">(Max 15M)</small></th>
                                    <th style="width: 90px;">Absent</th>
                                    <th class="text-start" style="min-width: 140px;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="testTableBody">
                                <!-- Populated dynamically by switchTestSection() -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 6: ATTENDANCE REGISTER (15M HUB)                      -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-attendance" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-clipboard-user me-1.5 text-cyan-400"></i>
                                Official TEAMS Attendance Register (Max 15 Marks)
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Synchronized with official TEAMS uploaded attendance records. Attendance slab is mapped to 15.0 marks with manual override support.
                            </div>
                        </div>
                        <button type="button" class="btn btn-info btn-sm px-3 fw-bold text-dark" onclick="saveAttendanceOverrides()">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Attendance Marks
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">Roll</th>
                                    <th style="width: 110px;">Reg No</th>
                                    <th class="text-start" style="min-width: 170px;">Student Name</th>
                                    <th style="width: 70px;">Batch</th>
                                    <th style="width: 120px;">Attendance %</th>
                                    <th style="width: 120px;">Override %</th>
                                    <th style="width: 120px;" class="text-cyan-400">Mark (Out of 15)</th>
                                    <th style="width: 120px;">Override Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                    @php
                                        $score = $calculatedScores[$st->reg_no] ?? [];
                                        $attRec = $attEvals->get($st->reg_no);
                                    @endphp
                                    <tr data-reg="{{ $st->reg_no }}">
                                        <td class="fw-bold">{{ $st->roll_no ?? '-' }}</td>
                                        <td class="font-monospace text-secondary small">{{ $st->reg_no }}</td>
                                        <td class="text-start fw-medium text-light">{{ $st->name }}</td>
                                        <td><span class="batch-badge-{{ $score['batch_no'] ?? '1' }}">B{{ $score['batch_no'] ?? '1' }}</span></td>
                                        <td><span class="badge bg-secondary font-monospace">{{ number_format($score['att_pct'] ?? 85, 1) }}%</span></td>
                                        <td>
                                            <input type="number" step="0.1" min="0" max="100" class="fast-input att-override-pct" data-reg="{{ $st->reg_no }}" value="{{ $attRec->override_percentage ?? '' }}" placeholder="Auto" oninput="debounceAutosave('attendance')">
                                        </td>
                                        <td>
                                            <span class="badge bg-cyan-900 text-cyan-300 font-monospace fs-6 px-2 py-1">
                                                {{ number_format($score['att_mark_15'] ?? 15, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" min="0" max="15" class="fast-input att-override-mark" data-reg="{{ $st->reg_no }}" value="{{ $attRec->override_mark ?? '' }}" placeholder="Auto" oninput="debounceAutosave('attendance')">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 7: ATTAINMENT CALCULATION (CO/PO NBA & SBTE LEVEL)    -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-attainment" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-light fs-6">
                                <i class="fa-solid fa-chart-line me-1.5 text-emerald-400"></i>
                                Course Outcome (CO) &amp; PO Attainment Calculation
                            </div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Direct CIE Attainment (80% weightage) + Indirect Exit Survey (20% weightage) compliant with Kerala SBTE Revision 2021 and NBA standards.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-info btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAttainmentSettings">
                                <i class="fa-solid fa-sliders me-1"></i> Configure Targets &amp; Surveys
                            </button>
                        </div>
                    </div>

                    <!-- Attainment Metric Summary Cards -->
                    <div class="row g-3 mb-4">
                        @foreach($attainment['direct'] as $coKey => $dData)
                            @php
                                $overallData = $attainment['overall'][$coKey] ?? [];
                                $lvl = $overallData['overall_level'] ?? 0;
                                $lvlColor = $lvl >= 2.5 ? 'success' : ($lvl >= 1.5 ? 'warning' : 'danger');
                            @endphp
                            <div class="col-md-3">
                                <div class="p-3 rounded border border-secondary bg-dark h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-secondary font-monospace">{{ $coKey }}</span>
                                        <span class="badge bg-{{ $lvlColor }}">Level {{ number_format($lvl, 2) }}</span>
                                    </div>
                                    <div class="fw-bold text-light small mb-2 text-truncate" title="{{ $attainment['co_definitions'][$coKey] ?? '' }}">
                                        {{ $attainment['co_definitions'][$coKey] ?? '' }}
                                    </div>
                                    <div class="text-secondary" style="font-size: 0.72rem;">
                                        Direct CIE: <span class="text-light fw-bold">{{ $dData['attainment_percentage'] }}%</span> (L{{ $dData['attainment_level'] }})<br>
                                        Indirect: <span class="text-light fw-bold">{{ $attainment['indirect'][$coKey]['survey_avg'] ?? 2.5 }}/3</span> (L{{ $attainment['indirect'][$coKey]['survey_level'] ?? 2.5 }})<br>
                                        Overall Score: <span class="text-info fw-bold">{{ $overallData['overall_attainment'] ?? 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Direct CIE Attainment Table -->
                    <div class="mb-4">
                        <div class="fw-bold text-light small mb-2">
                            <i class="fa-solid fa-calculator text-primary me-1"></i> Direct Assessment Attainment (CIE Components: Work, Record, Test, Attendance)
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">CO</th>
                                        <th class="text-start">Course Outcome Description</th>
                                        <th style="width: 100px;">Target Mark %</th>
                                        <th style="width: 100px;">Total Enrolled</th>
                                        <th style="width: 110px;">Students &ge; Target</th>
                                        <th style="width: 120px;" class="text-info">Attainment %</th>
                                        <th style="width: 110px;">Attainment Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attainment['direct'] as $coKey => $dData)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ $coKey }}</td>
                                            <td class="text-start small text-light">{{ $attainment['co_definitions'][$coKey] ?? '' }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $dData['target_percentage'] }}%</span></td>
                                            <td>{{ $dData['total_students'] }}</td>
                                            <td class="fw-bold text-success">{{ $dData['students_achieved'] }}</td>
                                            <td>
                                                <div class="progress bg-secondary" style="height: 16px;">
                                                    <div class="progress-bar bg-info text-dark fw-bold font-monospace" style="width: {{ $dData['attainment_percentage'] }}%; font-size: 0.68rem;">
                                                        {{ $dData['attainment_percentage'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $dData['attainment_level'] == 3 ? 'success' : ($dData['attainment_level'] == 2 ? 'warning' : 'danger') }} fs-6">
                                                    Level {{ $dData['attainment_level'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CO-PO Mapping Matrix Table -->
                    <div>
                        <div class="fw-bold text-light small mb-2">
                            <i class="fa-solid fa-network-wired text-success me-1"></i> CO-PO Correlation &amp; Mapping Matrix
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">CO</th>
                                        @for($i = 1; $i <= 7; $i++)
                                            <th>PO{{ $i }}</th>
                                        @endfor
                                        <th>PSO1</th>
                                        <th>PSO2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attainment['co_po_matrix'] as $coKey => $poMap)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ $coKey }}</td>
                                            @for($i = 1; $i <= 7; $i++)
                                                @php $val = $poMap['PO'.$i] ?? null; @endphp
                                                <td class="{{ !empty($val) ? 'fw-bold text-success bg-dark' : 'text-secondary' }}">
                                                    {{ $val ?: '-' }}
                                                </td>
                                            @endfor
                                            @php
                                                $pso1 = $poMap['PSO1'] ?? null;
                                                $pso2 = $poMap['PSO2'] ?? null;
                                            @endphp
                                            <td class="{{ !empty($pso1) ? 'fw-bold text-info bg-dark' : 'text-secondary' }}">{{ $pso1 ?: '-' }}</td>
                                            <td class="{{ !empty($pso2) ? 'fw-bold text-info bg-dark' : 'text-secondary' }}">{{ $pso2 ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 8: OFFICIAL STATUTORY REPORTS HUB (DESKTOP ONLY)      -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-reports" role="tabpanel">
                <div class="glass-card p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div class="fw-bold text-light fs-5">
                            <i class="fa-solid fa-file-pdf me-2 text-danger"></i> Official Mechanical Workshop Statutory Reports &amp; Registers
                        </div>
                        <span class="badge bg-primary text-white">Revision 2021 &bull; SBTE Norms</span>
                    </div>
                    <div class="text-secondary small mb-4">
                        Generate and print official pre-formatted A4 statutory documents required for academic auditing, HOD sign-off, and SBTE submission.
                    </div>

                    <div class="row g-3">
                        <!-- Report 1: Consolidated 3-Section Register -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-info mb-2" style="font-size: 2.4rem;">table_chart</span>
                                    <h6 class="text-light fw-bold mb-1">Consolidated 3-Section Register</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Comprehensive A4 Landscape register displaying continuous job marks across all 3 trade sections, test scores, attendance, and total 75-mark CIA.
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/consolidated-3-section" target="_blank" class="btn btn-outline-info btn-sm fw-bold w-100 mt-2">
                                    <i class="fa-solid fa-print me-1"></i> Print 3-Section Register
                                </a>
                            </div>
                        </div>

                        <!-- Report 2: Summary Report -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-success mb-2" style="font-size: 2.4rem;">summarize</span>
                                    <h6 class="text-light fw-bold mb-1">Workshop Summary Report</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Executive A4 Portrait summary featuring overall pass/fail percentage, grade distribution brackets (Outstanding, Very Good, Good, etc.), and class averages.
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/summary" target="_blank" class="btn btn-outline-success btn-sm fw-bold w-100 mt-2">
                                    <i class="fa-solid fa-print me-1"></i> Print Summary Report
                                </a>
                            </div>
                        </div>

                        <!-- Report 3: Internal CIA Report (75M) -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-warning mb-2" style="font-size: 2.4rem;">verified_user</span>
                                    <h6 class="text-light fw-bold mb-1">Internal CIA Statement (75M)</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Statutory 75-mark Continuous Internal Assessment register with component breakdowns (Work 37.5, Record 7.5, Tests 15, Att 15) and sign-off columns.
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/internal" target="_blank" class="btn btn-outline-warning btn-sm fw-bold w-100 mt-2">
                                    <i class="fa-solid fa-print me-1"></i> Print Internal CIA Report
                                </a>
                            </div>
                        </div>

                        <!-- Report 4: Dual Batch Report Internal -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-purple mb-2" style="font-size: 2.4rem; color: #c084fc;">compare</span>
                                    <h6 class="text-light fw-bold mb-1">Dual Batch Internal Report</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Side-by-side comparative register of Batch 1 and Batch 2 showing respective section distributions, performance averages, and trade completion rates.
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/dual-batch" target="_blank" class="btn btn-outline-secondary btn-sm fw-bold w-100 mt-2" style="color: #c084fc; border-color: #c084fc;">
                                    <i class="fa-solid fa-print me-1"></i> Print Dual Batch Report
                                </a>
                            </div>
                        </div>

                        <!-- Report 5: Attendance & Class Session Log -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-cyan mb-2" style="font-size: 2.4rem; color: #38bdf8;">history_edu</span>
                                    <h6 class="text-light fw-bold mb-1">Attendance &amp; Session Log</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Official student-wise attendance records with total practical hours conducted, hours attended, attendance percentage, and 15-mark statutory slab marks.
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/attendance-log" target="_blank" class="btn btn-outline-info btn-sm fw-bold w-100 mt-2">
                                    <i class="fa-solid fa-print me-1"></i> Print Attendance Log
                                </a>
                            </div>
                        </div>

                        <!-- Report 6: Continuous Work Register -->
                        <div class="col-md-4">
                            <div class="p-3.5 rounded border border-secondary bg-dark text-center h-100 d-flex flex-column justify-content-between shadow-sm">
                                <div>
                                    <span class="material-symbols-rounded text-primary mb-2" style="font-size: 2.4rem;">handyman</span>
                                    <h6 class="text-light fw-bold mb-1">Continuous Work Register</h6>
                                    <p class="text-secondary small" style="font-size: 0.74rem;">
                                        Individual trade job marks register showing continuous evaluation of workshop practicals (including 4 Machine shop works and Aluminium Fabrication).
                                    </p>
                                </div>
                                <a href="/r21/classroom/workshop/{{ $batchSubject->id }}/print/works" target="_blank" class="btn btn-outline-primary btn-sm fw-bold w-100 mt-2">
                                    <i class="fa-solid fa-print me-1"></i> Print Work Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- /tab-content -->

    </div> <!-- /container-fluid -->

    <!-- Modal: Upload Syllabus PDF -->
    <div class="modal fade" id="modalUploadSyllabus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card border border-secondary">
                <div class="modal-header border-secondary">
                    <h6 class="modal-title text-light fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload Mechanical Workshop Syllabus
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUploadSyllabus" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="text-secondary small mb-3">
                            Upload the official SBTE Revision 2021 Mechanical Workshop curriculum syllabus document (PDF format, max 15MB).
                        </p>
                        <div class="mb-3">
                            <label class="form-label text-light small fw-bold">Select PDF File:</label>
                            <input type="file" name="syllabus_file" class="form-control form-control-sm bg-dark text-light border-secondary" accept="application/pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold" id="btnSubmitSyllabus">
                            <i class="fa-solid fa-upload me-1"></i> Upload Syllabus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Batch & Trade Section Allocation Setup -->
    <div class="modal fade" id="modalBatchSetup" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content glass-card border border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-light fw-bold">
                        <i class="fa-solid fa-layer-group text-info me-2"></i> Mechanical Workshop Batch Split &amp; Trade Allocation Setup
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-4 text-info bg-info-subtle d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info fs-5"></i>
                        <div>
                            Configure which 3 trade sections belong to <strong>Batch 1</strong> and <strong>Batch 2</strong>. In Rev 2021, each student completes 3 sections (6 works total). Use the roll cutoff tool to auto-split students or manually toggle assignments.
                        </div>
                    </div>

                    <!-- Row 1: Section Allocation to Batches -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded border border-info bg-dark h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-info fw-bold mb-0">
                                        <i class="fa-solid fa-users me-1"></i> Batch 1 Trade Sections
                                    </h6>
                                    <span class="badge bg-success" id="b1SelectedCountBadge">3 / 3 Selected</span>
                                </div>
                                <div class="text-secondary small mb-3">Check exactly 3 sections assigned to Batch 1:</div>
                                <div class="row g-2">
                                    @php $b1Secs = $batchAllocation['1'] ?? ['Fitting', 'Smithy', 'Welding']; @endphp
                                    @foreach($sectionsConfig as $sec)
                                        <div class="col-6">
                                            <div class="form-check p-2 rounded bg-black border border-secondary">
                                                <input class="form-check-input b1-sec-checkbox" type="checkbox" value="{{ $sec }}" id="b1_sec_{{ Str::slug($sec) }}" {{ in_array($sec, $b1Secs) ? 'checked' : '' }} onchange="updateSectionCount('b1')">
                                                <label class="form-check-label text-light small fw-medium" for="b1_sec_{{ Str::slug($sec) }}">
                                                    {{ $sec }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded border border-purple bg-dark h-100" style="border-color: #a855f7 !important;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-purple fw-bold mb-0" style="color: #c084fc;">
                                        <i class="fa-solid fa-users me-1"></i> Batch 2 Trade Sections
                                    </h6>
                                    <span class="badge bg-success" id="b2SelectedCountBadge">3 / 3 Selected</span>
                                </div>
                                <div class="text-secondary small mb-3">Check exactly 3 sections assigned to Batch 2:</div>
                                <div class="row g-2">
                                    @php $b2Secs = $batchAllocation['2'] ?? ['Machine shop', 'Sheet metal', 'Foundry']; @endphp
                                    @foreach($sectionsConfig as $sec)
                                        <div class="col-6">
                                            <div class="form-check p-2 rounded bg-black border border-secondary">
                                                <input class="form-check-input b2-sec-checkbox" type="checkbox" value="{{ $sec }}" id="b2_sec_{{ Str::slug($sec) }}" {{ in_array($sec, $b2Secs) ? 'checked' : '' }} onchange="updateSectionCount('b2')">
                                                <label class="form-check-label text-light small fw-medium" for="b2_sec_{{ Str::slug($sec) }}">
                                                    {{ $sec }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Roll Cutoff Split Tool -->
                    <div class="p-3 rounded border border-secondary bg-dark mb-4">
                        <h6 class="text-light fw-bold mb-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-warning me-1.5"></i> Quick Roll Number Cutoff Split
                        </h6>
                        <div class="text-secondary small mb-3">
                            Set roll number cutoff (e.g. Roll 1 to 30 = Batch 1, Roll 31+ = Batch 2).
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <label class="text-light small fw-bold">Batch 1 Cutoff Roll No:</label>
                            <input type="number" id="batchCutoffInput" class="form-control form-control-sm bg-black text-light border-secondary" style="width: 120px;" value="{{ $courseFile->batch_split_cutoff ?? (int)ceil($students->count() / 2) }}" min="1">
                            <button type="button" class="btn btn-warning btn-sm fw-bold px-3" onclick="applyRollCutoff()">
                                <i class="fa-solid fa-bolt me-1"></i> Apply Cutoff to Students
                            </button>
                        </div>
                    </div>

                    <!-- Row 3: Student Allocation Table -->
                    <div class="p-3 rounded border border-secondary bg-dark">
                        <h6 class="text-light fw-bold mb-3">
                            <i class="fa-solid fa-list-check text-cyan-400 me-1.5"></i> Student Batch Allocations ({{ $students->count() }} Students)
                        </h6>
                        <div class="table-responsive" style="max-height: 380px;">
                            <table class="table table-custom table-bordered align-middle text-center" id="studentBatchTable">
                                <thead class="sticky-top bg-dark">
                                    <tr>
                                        <th style="width: 60px;">Roll</th>
                                        <th style="width: 130px;">Reg No</th>
                                        <th class="text-start">Student Name</th>
                                        <th style="width: 120px;">Current Batch</th>
                                        <th style="width: 160px;">Assign Batch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $st)
                                        @php
                                            $alloc = $allocations->get($st->reg_no);
                                            $bNo = $alloc->batch_no ?? '1';
                                        @endphp
                                        <tr data-reg="{{ $st->reg_no }}" data-roll="{{ $st->roll_no ?? 0 }}">
                                            <td class="fw-bold">{{ $st->roll_no ?? '-' }}</td>
                                            <td class="font-monospace text-secondary small">{{ $st->reg_no }}</td>
                                            <td class="text-start fw-medium text-light">{{ $st->name }}</td>
                                            <td>
                                                <span class="batch-badge-{{ $bNo }}" id="badge_batch_{{ $st->reg_no }}">
                                                    Batch {{ $bNo }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info {{ $bNo === '1' ? 'active' : '' }}" onclick="setStudentBatch('{{ $st->reg_no }}', '1')">
                                                        B1
                                                    </button>
                                                    <button type="button" class="btn btn-outline-purple {{ $bNo === '2' ? 'active' : '' }}" onclick="setStudentBatch('{{ $st->reg_no }}', '2')">
                                                        B2
                                                    </button>
                                                </div>
                                                <input type="hidden" class="student-batch-input" data-reg="{{ $st->reg_no }}" value="{{ $bNo }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm fw-bold px-4 text-dark" onclick="saveBatchAllocationSetup()">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Batch Setup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Attainment Calculation Settings & Surveys -->
    <div class="modal fade" id="modalAttainmentSettings" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card border border-secondary">
                <div class="modal-header border-secondary">
                    <h6 class="modal-title text-light fw-bold">
                        <i class="fa-solid fa-sliders text-success me-2"></i> Attainment Parameters &amp; Course End Surveys
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-light small fw-bold">Direct CIE Attainment Target Threshold %:</label>
                        <input type="number" id="attainmentTargetInput" class="form-control form-control-sm bg-dark text-light border-secondary" value="{{ $attainment['target_percentage'] ?? 50 }}" min="10" max="100" step="5">
                        <div class="text-secondary small mt-1" style="font-size: 0.72rem;">
                            Percentage of CIE marks (out of 75) considered as target achievement (Default: 50%).
                        </div>
                    </div>

                    <div class="fw-bold text-light small mb-2">Indirect Assessment: Course Exit Survey Ratings (0 - 3.0)</div>
                    <div class="row g-2 mb-3">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                            @php $sAvg = $attainment['indirect'][$co]['survey_avg'] ?? 2.5; @endphp
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold">{{ $co }} Survey Avg / 3.0:</label>
                                <input type="number" step="0.1" min="0" max="3" class="form-control form-control-sm bg-dark text-light border-secondary survey-score-input" data-co="{{ $co }}" value="{{ $sAvg }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="saveAttainmentSettings()">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Attainment Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-Side State and Interactive Scripts -->
    <script>
        const subjectId = {{ $batchSubject->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Master Data passed from PHP
        const rawStudents = @json($students);
        const rawCalculated = @json($calculatedScores);
        const rawWorkEvals = @json($workEvals);
        const rawSectionEvals = @json($sectionEvals);
        const allSections = @json($sectionsConfig);

        let activeWorkSection = allSections[0] || 'Machine shop';
        let activeRecordSection = allSections[0] || 'Machine shop';
        let activeTestSection = allSections[0] || 'Machine shop';

        // Initialize dynamic tables on page load
        document.addEventListener('DOMContentLoaded', function() {
            switchWorkSection(activeWorkSection);
            switchRecordSection(activeRecordSection);
            switchTestSection(activeTestSection);
            updateSectionCount('b1');
            updateSectionCount('b2');
        });

        // Theme Toggle Function
        function toggleTheme() {
            const isLight = document.body.classList.toggle('light-theme');
            localStorage.setItem('theme-preference', isLight ? 'light' : 'dark');
            const icon = document.getElementById('themeToggleIcon');
            const text = document.getElementById('themeToggleText');
            if (icon) icon.innerText = isLight ? 'dark_mode' : 'light_mode';
            if (text) text.innerText = isLight ? 'Dark Mode' : 'Light Mode';
        }

        // Live calculation of CIA Row Total
        function recalculateRowCia(regNo) {
            const row = document.querySelector(`tr[data-reg="${regNo}"]`);
            if (!row) return;

            const work = parseFloat(row.querySelector('.cia-work-input')?.value || 0);
            const open = parseFloat(row.querySelector('.cia-open-input')?.value || 0);
            const test = parseFloat(row.querySelector('.cia-test-input')?.value || 0);
            const att  = parseFloat(row.querySelector('.cia-att-input')?.value || 0);

            const total = Math.round(work + open + test + att);
            const totalBadge = document.getElementById(`total_cia_${regNo}`);
            if (totalBadge) {
                totalBadge.innerText = total;
            }

            const statusCell = document.getElementById(`status_cell_${regNo}`);
            if (statusCell) {
                if (total >= 30) {
                    statusCell.innerHTML = `<span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.68rem;">Passed</span>`;
                } else {
                    statusCell.innerHTML = `<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1" style="font-size: 0.68rem;">Below Min</span>`;
                }
            }

            debounceAutosave('cia');
        }

        // Filter CIA Table
        function filterCiaTable() {
            const query = (document.getElementById('ciaSearchInput')?.value || '').toLowerCase();
            const batchFilter = document.getElementById('ciaBatchFilter')?.value || 'ALL';

            document.querySelectorAll('.cia-row').forEach(row => {
                const reg = (row.dataset.reg || '').toLowerCase();
                const name = (row.children[2]?.innerText || '').toLowerCase();
                const bNo = row.dataset.batch || '1';

                const matchesQuery = !query || reg.includes(query) || name.includes(query);
                const matchesBatch = (batchFilter === 'ALL') || (bNo === batchFilter);

                row.style.display = (matchesQuery && matchesBatch) ? '' : 'none';
            });
        }

        // State for Batch Filters in Work, Record, and Test tabs
        let currentWorkBatchFilter = 'ASSIGNED';
        let currentRecordBatchFilter = 'ASSIGNED';
        let currentTestBatchFilter = 'ASSIGNED';

        function setWorkBatchFilter(batchVal, btn) {
            currentWorkBatchFilter = batchVal;
            document.querySelectorAll('.work-batch-filter-btn').forEach(b => b.classList.toggle('active', b.dataset.batch === batchVal));
            switchWorkSection(activeWorkSection);
        }

        function setRecordBatchFilter(batchVal, btn) {
            currentRecordBatchFilter = batchVal;
            document.querySelectorAll('.record-batch-filter-btn').forEach(b => b.classList.toggle('active', b.dataset.batch === batchVal));
            switchRecordSection(activeRecordSection);
        }

        function setTestBatchFilter(batchVal, btn) {
            currentTestBatchFilter = batchVal;
            document.querySelectorAll('.test-batch-filter-btn').forEach(b => b.classList.toggle('active', b.dataset.batch === batchVal));
            switchTestSection(activeTestSection);
        }

        // Switch Continuous Work Section Table
        function switchWorkSection(secName) {
            activeWorkSection = secName;
            document.querySelectorAll('.section-pill-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.sec === secName);
            });

            const titleEl = document.getElementById('workSectionTitle');
            if (titleEl) titleEl.innerText = secName;

            const thead = document.getElementById('workTableHeader');
            if (thead) {
                if (secName === 'Machine shop') {
                    thead.innerHTML = `
                        <tr>
                            <th style="width: 45px;">Roll</th>
                            <th style="width: 110px;">Reg No</th>
                            <th class="text-start" style="min-width: 150px;">Student Name</th>
                            <th style="width: 65px;">Batch</th>
                            <th style="width: 95px;">Work 1<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 95px;">Work 2<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 95px;">Work 3<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 95px;">Work 4<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 105px;" class="text-info">Avg / 20<br><small class="text-secondary">(sum/4)</small></th>
                            <th class="text-start" style="min-width: 120px;">Remarks</th>
                        </tr>
                    `;
                } else if (secName === 'Sheet metal') {
                    thead.innerHTML = `
                        <tr>
                            <th style="width: 45px;">Roll</th>
                            <th style="width: 110px;">Reg No</th>
                            <th class="text-start" style="min-width: 150px;">Student Name</th>
                            <th style="width: 65px;">Batch</th>
                            <th style="width: 100px;">SM Work 1<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 100px;">SM Work 2<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 125px;" class="text-warning">Alum Fab Work<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 105px;" class="text-info">Avg / 20<br><small class="text-secondary">(3 works)</small></th>
                            <th class="text-start" style="min-width: 120px;">Remarks</th>
                        </tr>
                    `;
                } else {
                    thead.innerHTML = `
                        <tr>
                            <th style="width: 45px;">Roll</th>
                            <th style="width: 110px;">Reg No</th>
                            <th class="text-start" style="min-width: 160px;">Student Name</th>
                            <th style="width: 70px;">Batch</th>
                            <th style="width: 110px;">Work 1<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 110px;">Work 2<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 110px;" class="text-info">Section Avg<br><small class="text-secondary">(20M)</small></th>
                            <th class="text-start" style="min-width: 130px;">Remarks</th>
                        </tr>
                    `;
                }
            }

            const tbody = document.getElementById('workTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            rawStudents.forEach(st => {
                const rNo = st.reg_no;
                const score = rawCalculated[rNo] || {};
                const assigned = score.assigned_sections || [];
                const isAssigned = assigned.includes(secName);
                const bNo = score.batch_no || '1';

                // Apply Batch Filter
                if (currentWorkBatchFilter === 'ASSIGNED' && !isAssigned) return;
                if (currentWorkBatchFilter === '1' && bNo !== '1') return;
                if (currentWorkBatchFilter === '2' && bNo !== '2') return;

                const wRec = (rawWorkEvals[rNo] && rawWorkEvals[rNo][secName]) ? rawWorkEvals[rNo][secName] : {};
                const w1 = wRec.work_1_mark !== undefined ? parseFloat(wRec.work_1_mark) : 0;
                const w2 = wRec.work_2_mark !== undefined ? parseFloat(wRec.work_2_mark) : 0;
                const w3 = wRec.work_3_mark !== undefined ? parseFloat(wRec.work_3_mark) : 0;
                const w4 = wRec.work_4_mark !== undefined ? parseFloat(wRec.work_4_mark) : 0;
                const alum = wRec.alum_work_mark !== undefined ? parseFloat(wRec.alum_work_mark) : 0;

                let avg = 0;
                if (secName === 'Machine shop') {
                    avg = ((w1 + w2 + w3 + w4) / 4).toFixed(2);
                } else if (secName === 'Sheet metal') {
                    avg = (alum > 0 ? (w1 + w2 + alum) / 3 : (w1 + w2) / 2).toFixed(2);
                } else {
                    avg = ((w1 + w2) / 2).toFixed(2);
                }

                const tr = document.createElement('tr');
                tr.dataset.reg = rNo;
                tr.style.opacity = isAssigned ? '1' : '0.45';

                let worksInputsHtml = '';
                if (secName === 'Machine shop') {
                    worksInputsHtml = `
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w1-input" data-reg="${rNo}" value="${w1}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w2-input" data-reg="${rNo}" value="${w2}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w3-input" data-reg="${rNo}" value="${w3}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w4-input" data-reg="${rNo}" value="${w4}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                    `;
                } else if (secName === 'Sheet metal') {
                    worksInputsHtml = `
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w1-input" data-reg="${rNo}" value="${w1}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w2-input" data-reg="${rNo}" value="${w2}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input alum-w-input border-warning" data-reg="${rNo}" value="${alum}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                    `;
                } else {
                    worksInputsHtml = `
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w1-input" data-reg="${rNo}" value="${w1}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="20" class="fast-input w2-input" data-reg="${rNo}" value="${w2}" oninput="recalcWorkRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                    `;
                }

                tr.innerHTML = `
                    <td class="fw-bold">${st.roll_no || '-'}</td>
                    <td class="font-monospace text-secondary small">${rNo}</td>
                    <td class="text-start fw-medium text-light">
                        ${st.name}
                        ${!isAssigned ? '<span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Other Batch</span>' : ''}
                    </td>
                    <td><span class="batch-badge-${bNo}">B${bNo}</span></td>
                    ${worksInputsHtml}
                    <td>
                        <span class="badge bg-info text-dark font-monospace fs-6 px-2 py-1 work-avg-badge" id="work_avg_${rNo}">${avg}</span>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary work-remarks-input" data-reg="${rNo}" value="${wRec.remarks || ''}" placeholder="Remarks" style="font-size: 0.75rem;" ${!isAssigned ? 'disabled' : ''} oninput="debounceAutosave('works')">
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function recalcWorkRow(regNo) {
            const row = document.querySelector(`#workTableBody tr[data-reg="${regNo}"]`);
            if (!row) return;

            let avg = 0;
            const w1 = parseFloat(row.querySelector('.w1-input')?.value || 0);
            const w2 = parseFloat(row.querySelector('.w2-input')?.value || 0);

            if (activeWorkSection === 'Machine shop') {
                const w3 = parseFloat(row.querySelector('.w3-input')?.value || 0);
                const w4 = parseFloat(row.querySelector('.w4-input')?.value || 0);
                avg = ((w1 + w2 + w3 + w4) / 4).toFixed(2);
            } else if (activeWorkSection === 'Sheet metal') {
                const alum = parseFloat(row.querySelector('.alum-w-input')?.value || 0);
                avg = (alum > 0 ? (w1 + w2 + alum) / 3 : (w1 + w2) / 2).toFixed(2);
            } else {
                avg = ((w1 + w2) / 2).toFixed(2);
            }

            const badge = document.getElementById(`work_avg_${regNo}`);
            if (badge) badge.innerText = avg;

            debounceAutosave('works');
        }

        // Switch Rough & Fair Record Section
        function switchRecordSection(secName) {
            activeRecordSection = secName;
            document.querySelectorAll('.record-sec-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.sec === secName);
            });

            const titleEl = document.getElementById('recordSectionTitle');
            if (titleEl) titleEl.innerText = secName;

            const thead = document.getElementById('recordTableHeader');
            if (thead) {
                if (secName === 'Sheet metal') {
                    thead.innerHTML = `
                        <tr>
                            <th style="width: 45px;">Roll</th>
                            <th style="width: 110px;">Reg No</th>
                            <th class="text-start" style="min-width: 140px;">Student Name</th>
                            <th style="width: 60px;">Batch</th>
                            <th style="width: 90px;">SM Rough<br><small class="text-secondary">(10M)</small></th>
                            <th style="width: 90px;">SM Fair<br><small class="text-secondary">(10M)</small></th>
                            <th style="width: 90px;" class="text-emerald-400">SM Total<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 90px;" class="text-warning">Alum Rough<br><small class="text-secondary">(10M)</small></th>
                            <th style="width: 90px;" class="text-warning">Alum Fair<br><small class="text-secondary">(10M)</small></th>
                            <th style="width: 90px;" class="text-warning">Alum Total<br><small class="text-secondary">(20M)</small></th>
                            <th style="width: 105px;" class="text-success">Combined Total<br><small class="text-secondary">(20M)</small></th>
                            <th class="text-start" style="min-width: 110px;">Remarks</th>
                        </tr>
                    `;
                } else {
                    thead.innerHTML = `
                        <tr>
                            <th style="width: 45px;">Roll</th>
                            <th style="width: 110px;">Reg No</th>
                            <th class="text-start" style="min-width: 160px;">Student Name</th>
                            <th style="width: 70px;">Batch</th>
                            <th style="width: 120px;">Rough Record<br><small class="text-secondary">(Max 10M)</small></th>
                            <th style="width: 120px;">Fair Record<br><small class="text-secondary">(Max 10M)</small></th>
                            <th style="width: 120px;" class="text-emerald-400">Total Record<br><small class="text-secondary">(Max 20M)</small></th>
                            <th class="text-start" style="min-width: 130px;">Remarks</th>
                        </tr>
                    `;
                }
            }

            const tbody = document.getElementById('recordTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            rawStudents.forEach(st => {
                const rNo = st.reg_no;
                const score = rawCalculated[rNo] || {};
                const assigned = score.assigned_sections || [];
                const isAssigned = assigned.includes(secName);
                const bNo = score.batch_no || '1';

                // Apply Batch Filter
                if (currentRecordBatchFilter === 'ASSIGNED' && !isAssigned) return;
                if (currentRecordBatchFilter === '1' && bNo !== '1') return;
                if (currentRecordBatchFilter === '2' && bNo !== '2') return;

                const sRec = (rawSectionEvals[rNo] && rawSectionEvals[rNo][secName]) ? rawSectionEvals[rNo][secName] : {};
                const rough = sRec.rough_record_mark !== undefined ? parseFloat(sRec.rough_record_mark) : 0;
                const fair  = sRec.fair_record_mark !== undefined ? parseFloat(sRec.fair_record_mark) : 0;
                const smTotal = (rough + fair);

                const tr = document.createElement('tr');
                tr.dataset.reg = rNo;
                tr.style.opacity = isAssigned ? '1' : '0.45';

                if (secName === 'Sheet metal') {
                    const alumRough = sRec.alum_rough_record_mark !== undefined && sRec.alum_rough_record_mark !== null ? parseFloat(sRec.alum_rough_record_mark) : 0;
                    const alumFair  = sRec.alum_fair_record_mark !== undefined && sRec.alum_fair_record_mark !== null ? parseFloat(sRec.alum_fair_record_mark) : 0;
                    const alumTotal = (alumRough + alumFair);
                    const combined = (sRec.alum_rough_record_mark !== null || sRec.alum_fair_record_mark !== null) ? ((smTotal + alumTotal) / 2).toFixed(2) : smTotal.toFixed(2);

                    tr.innerHTML = `
                        <td class="fw-bold">${st.roll_no || '-'}</td>
                        <td class="font-monospace text-secondary small">${rNo}</td>
                        <td class="text-start fw-medium text-light">
                            ${st.name}
                            ${!isAssigned ? '<span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Other Batch</span>' : ''}
                        </td>
                        <td><span class="batch-badge-${bNo}">B${bNo}</span></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input rough-input" data-reg="${rNo}" value="${rough}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input fair-input" data-reg="${rNo}" value="${fair}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><span class="badge bg-secondary font-monospace" id="sm_total_${rNo}">${smTotal.toFixed(2)}</span></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input alum-rough-input border-warning" data-reg="${rNo}" value="${alumRough}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input alum-fair-input border-warning" data-reg="${rNo}" value="${alumFair}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><span class="badge bg-warning text-dark font-monospace" id="alum_total_${rNo}">${alumTotal.toFixed(2)}</span></td>
                        <td><span class="badge bg-success font-monospace fs-6 px-2 py-1 record-total-badge" id="record_total_${rNo}">${combined}</span></td>
                        <td>
                            <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary record-remarks-input" data-reg="${rNo}" value="${sRec.remarks || ''}" placeholder="Remarks" style="font-size: 0.75rem;" ${!isAssigned ? 'disabled' : ''} oninput="debounceAutosave('records')">
                        </td>
                    `;
                } else {
                    const total = smTotal.toFixed(2);
                    tr.innerHTML = `
                        <td class="fw-bold">${st.roll_no || '-'}</td>
                        <td class="font-monospace text-secondary small">${rNo}</td>
                        <td class="text-start fw-medium text-light">
                            ${st.name}
                            ${!isAssigned ? '<span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Other Batch</span>' : ''}
                        </td>
                        <td><span class="batch-badge-${bNo}">B${bNo}</span></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input rough-input" data-reg="${rNo}" value="${rough}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><input type="number" step="0.5" min="0" max="10" class="fast-input fair-input" data-reg="${rNo}" value="${fair}" oninput="recalcRecordRow('${rNo}')" ${!isAssigned ? 'disabled' : ''}></td>
                        <td><span class="badge bg-success font-monospace fs-6 px-2 py-1 record-total-badge" id="record_total_${rNo}">${total}</span></td>
                        <td>
                            <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary record-remarks-input" data-reg="${rNo}" value="${sRec.remarks || ''}" placeholder="Remarks" style="font-size: 0.75rem;" ${!isAssigned ? 'disabled' : ''} oninput="debounceAutosave('records')">
                        </td>
                    `;
                }
                tbody.appendChild(tr);
            });
        }

        function recalcRecordRow(regNo) {
            const row = document.querySelector(`#recordTableBody tr[data-reg="${regNo}"]`);
            if (!row) return;

            const rough = parseFloat(row.querySelector('.rough-input')?.value || 0);
            const fair  = parseFloat(row.querySelector('.fair-input')?.value || 0);
            const smTot = (rough + fair);

            if (activeRecordSection === 'Sheet metal') {
                const alumRough = parseFloat(row.querySelector('.alum-rough-input')?.value || 0);
                const alumFair  = parseFloat(row.querySelector('.alum-fair-input')?.value || 0);
                const alumTot = (alumRough + alumFair);
                const combined = ((alumRough > 0 || alumFair > 0) ? (smTot + alumTot) / 2 : smTot).toFixed(2);

                const smBadge = document.getElementById(`sm_total_${regNo}`);
                if (smBadge) smBadge.innerText = smTot.toFixed(2);
                const alumBadge = document.getElementById(`alum_total_${regNo}`);
                if (alumBadge) alumBadge.innerText = alumTot.toFixed(2);
                const badge = document.getElementById(`record_total_${regNo}`);
                if (badge) badge.innerText = combined;
            } else {
                const badge = document.getElementById(`record_total_${regNo}`);
                if (badge) badge.innerText = smTot.toFixed(2);
            }

            debounceAutosave('records');
        }

        // Switch Trade Test Section
        function switchTestSection(secName) {
            activeTestSection = secName;
            document.querySelectorAll('.test-sec-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.sec === secName);
            });

            const tbody = document.getElementById('testTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            rawStudents.forEach(st => {
                const rNo = st.reg_no;
                const score = rawCalculated[rNo] || {};
                const assigned = score.assigned_sections || [];
                const isAssigned = assigned.includes(secName);
                const bNo = score.batch_no || '1';

                // Apply Batch Filter
                if (currentTestBatchFilter === 'ASSIGNED' && !isAssigned) return;
                if (currentTestBatchFilter === '1' && bNo !== '1') return;
                if (currentTestBatchFilter === '2' && bNo !== '2') return;

                const sRec = (rawSectionEvals[rNo] && rawSectionEvals[rNo][secName]) ? rawSectionEvals[rNo][secName] : {};
                const mark = sRec.test_mark !== undefined ? parseFloat(sRec.test_mark) : 0;
                const isAbsent = !empty(sRec.is_test_absent);

                const tr = document.createElement('tr');
                tr.dataset.reg = rNo;
                tr.style.opacity = isAssigned ? '1' : '0.45';

                tr.innerHTML = `
                    <td class="fw-bold">${st.roll_no || '-'}</td>
                    <td class="font-monospace text-secondary small">${rNo}</td>
                    <td class="text-start fw-medium text-light">
                        ${st.name}
                        ${!isAssigned ? '<span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">Other Batch</span>' : ''}
                    </td>
                    <td><span class="batch-badge-${bNo}">B${bNo}</span></td>
                    <td>
                        <input type="number" step="0.5" min="0" max="15" class="fast-input test-mark-input" data-reg="${rNo}" value="${mark}" ${isAbsent || !isAssigned ? 'disabled' : ''} oninput="debounceAutosave('tests')">
                    </td>
                    <td>
                        <input type="checkbox" class="form-check-input test-absent-chk" data-reg="${rNo}" ${isAbsent ? 'checked' : ''} onchange="toggleTestAbsent('${rNo}', this.checked)" ${!isAssigned ? 'disabled' : ''}>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary test-remarks-input" data-reg="${rNo}" value="${sRec.remarks || ''}" placeholder="Remarks" style="font-size: 0.75rem;" ${!isAssigned ? 'disabled' : ''} oninput="debounceAutosave('tests')">
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function empty(val) {
            return !val || val === false || val === 0 || val === '0';
        }

        function toggleTestAbsent(regNo, isAbsent) {
            const input = document.querySelector(`.test-mark-input[data-reg="${regNo}"]`);
            if (input) {
                if (isAbsent) {
                    input.value = 0;
                    input.disabled = true;
                } else {
                    input.disabled = false;
                }
            }
            debounceAutosave('tests');
        }

        // Batch Split Section Count Update
        function updateSectionCount(prefix) {
            const count = document.querySelectorAll(`.${prefix}-sec-checkbox:checked`).length;
            const badge = document.getElementById(`${prefix}SelectedCountBadge`);
            if (badge) {
                badge.innerText = `${count} / 3 Selected`;
                badge.className = (count === 3) ? 'badge bg-success' : 'badge bg-danger';
            }
        }

        function setStudentBatch(regNo, batchNo) {
            const hidden = document.querySelector(`.student-batch-input[data-reg="${regNo}"]`);
            if (hidden) hidden.value = batchNo;

            const badge = document.getElementById(`badge_batch_${regNo}`);
            if (badge) {
                badge.innerText = `Batch ${batchNo}`;
                badge.className = `batch-badge-${batchNo}`;
            }

            const tr = document.querySelector(`#studentBatchTable tr[data-reg="${regNo}"]`);
            if (tr) {
                const b1Btn = tr.querySelector('.btn-outline-info');
                const b2Btn = tr.querySelector('.btn-outline-purple');
                if (b1Btn) b1Btn.classList.toggle('active', batchNo === '1');
                if (b2Btn) b2Btn.classList.toggle('active', batchNo === '2');
            }
        }

        function applyRollCutoff() {
            const cutoff = parseInt(document.getElementById('batchCutoffInput')?.value || 0);
            if (cutoff <= 0) {
                alert('Please enter a valid cutoff roll number.');
                return;
            }
            document.querySelectorAll('#studentBatchTable tbody tr').forEach(tr => {
                const roll = parseInt(tr.dataset.roll || 0);
                const reg = tr.dataset.reg;
                const bNo = (roll <= cutoff) ? '1' : '2';
                setStudentBatch(reg, bNo);
            });
        }

        // Debounced Autosave for Desktop Virtual Classroom
        let desktopAutosaveTimers = {};
        function debounceAutosave(targetType) {
            showSavingIndicator(false, 'Unsaved changes...');
            clearTimeout(desktopAutosaveTimers[targetType]);
            desktopAutosaveTimers[targetType] = setTimeout(() => {
                if (targetType === 'works') saveActiveSectionWorks(true);
                else if (targetType === 'records') saveActiveSectionRecords(true);
                else if (targetType === 'tests') saveActiveSectionTests(true);
                else if (targetType === 'cia') saveConsolidatedCiaFast(true);
                else if (targetType === 'attendance') saveAttendanceOverrides(true);
            }, 900);
        }

        // Indicator helper
        function showSavingIndicator(isSuccess = false, msg = 'Saving...') {
            const ind = document.getElementById('saveStatusIndicator');
            if (!ind) return;
            ind.style.display = 'inline-flex';
            if (isSuccess) {
                ind.className = 'autosave-pill autosave-success';
                ind.innerHTML = `<span class="material-symbols-rounded" style="font-size:0.95rem;">check_circle</span> Saved`;
                setTimeout(() => { ind.style.display = 'none'; }, 2200);
            } else {
                ind.className = 'autosave-pill autosave-saving';
                ind.innerHTML = `<span class="material-symbols-rounded spin" style="font-size:0.95rem;">sync</span> ${msg}`;
            }
        }

        // AJAX Action: Save Batch Setup
        async function saveBatchAllocationSetup() {
            const b1 = Array.from(document.querySelectorAll('.b1-sec-checkbox:checked')).map(cb => cb.value);
            const b2 = Array.from(document.querySelectorAll('.b2-sec-checkbox:checked')).map(cb => cb.value);

            if (b1.length !== 3 || b2.length !== 3) {
                alert('Please select exactly 3 sections for Batch 1 and 3 sections for Batch 2.');
                return;
            }

            const cutoff = document.getElementById('batchCutoffInput')?.value || null;
            const studentBatches = {};
            document.querySelectorAll('.student-batch-input').forEach(inp => {
                studentBatches[inp.dataset.reg] = inp.value;
            });

            showSavingIndicator(false, 'Saving setup...');

            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/batch-setup`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        batch1_sections: b1,
                        batch2_sections: b2,
                        batch_split_cutoff: cutoff,
                        student_batches: studentBatches
                    })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                    location.reload();
                } else {
                    alert(data.message || 'Error saving batch allocation.');
                }
            } catch (err) {
                alert('Server connection error.');
            }
        }

        // AJAX Action: Save Continuous Section Works
        async function saveActiveSectionWorks(isSilent = false) {
            const evaluations = [];
            document.querySelectorAll('#workTableBody tr').forEach(tr => {
                const reg = tr.dataset.reg;
                const w1Inp = tr.querySelector('.w1-input');
                if (!w1Inp || w1Inp.disabled) return;

                const w1 = w1Inp.value;
                const w2 = tr.querySelector('.w2-input')?.value;
                const w3 = tr.querySelector('.w3-input')?.value;
                const w4 = tr.querySelector('.w4-input')?.value;
                const alum = tr.querySelector('.alum-w-input')?.value;
                const remarks = tr.querySelector('.work-remarks-input')?.value || '';

                evaluations.push({
                    reg_no: reg,
                    work_1_mark: parseFloat(w1 || 0),
                    work_2_mark: parseFloat(w2 || 0),
                    work_3_mark: (w3 !== undefined && w3 !== '') ? parseFloat(w3) : null,
                    work_4_mark: (w4 !== undefined && w4 !== '') ? parseFloat(w4) : null,
                    alum_work_mark: (alum !== undefined && alum !== '') ? parseFloat(alum) : null,
                    remarks: remarks
                });
            });

            showSavingIndicator(false, isSilent ? 'Autosaving works...' : 'Saving works...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/work-marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ section_name: activeWorkSection, evaluations: evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                } else if (!isSilent) {
                    alert(data.message || 'Error saving work marks.');
                }
            } catch (err) {
                if (!isSilent) alert('Server error.');
            }
        }

        // AJAX Action: Save Record Marks
        async function saveActiveSectionRecords(isSilent = false) {
            const evaluations = [];
            document.querySelectorAll('#recordTableBody tr').forEach(tr => {
                const reg = tr.dataset.reg;
                const rInp = tr.querySelector('.rough-input');
                if (!rInp || rInp.disabled) return;

                const rough = rInp.value;
                const fair  = tr.querySelector('.fair-input')?.value;
                const alumRough = tr.querySelector('.alum-rough-input')?.value;
                const alumFair  = tr.querySelector('.alum-fair-input')?.value;
                const remarks = tr.querySelector('.record-remarks-input')?.value || '';

                evaluations.push({
                    reg_no: reg,
                    rough_record_mark: parseFloat(rough || 0),
                    fair_record_mark: parseFloat(fair || 0),
                    alum_rough_record_mark: (alumRough !== undefined && alumRough !== '') ? parseFloat(alumRough) : null,
                    alum_fair_record_mark: (alumFair !== undefined && alumFair !== '') ? parseFloat(alumFair) : null,
                    remarks: remarks
                });
            });

            showSavingIndicator(false, isSilent ? 'Autosaving records...' : 'Saving records...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/record-marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ section_name: activeRecordSection, evaluations: evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                } else if (!isSilent) {
                    alert(data.message || 'Error saving record marks.');
                }
            } catch (err) {
                if (!isSilent) alert('Server error.');
            }
        }

        // AJAX Action: Save Attainment Settings
        async function saveAttainmentSettings() {
            const targetPct = parseFloat(document.getElementById('attainmentTargetInput')?.value || 50);
            const surveyScores = {};
            document.querySelectorAll('.survey-score-input').forEach(inp => {
                surveyScores[inp.dataset.co] = parseFloat(inp.value || 2.5);
            });

            showSavingIndicator(false, 'Saving attainment settings...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/attainment/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ target_percentage: targetPct, survey_scores: surveyScores })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                    location.reload();
                } else {
                    alert(data.message || 'Error saving attainment settings.');
                }
            } catch (err) {
                alert('Server error saving attainment.');
            }
        }

        // AJAX Action: Save Test Marks
        async function saveActiveSectionTests(isSilent = false) {
            const evaluations = [];
            document.querySelectorAll('#testTableBody tr').forEach(tr => {
                const reg = tr.dataset.reg;
                const mark = tr.querySelector('.test-mark-input')?.value;
                const isAbsent = tr.querySelector('.test-absent-chk')?.checked || false;
                const remarks = tr.querySelector('.test-remarks-input')?.value || '';

                if (mark !== undefined && !tr.querySelector('.test-absent-chk')?.disabled) {
                    evaluations.push({
                        reg_no: reg,
                        test_mark: parseFloat(mark || 0),
                        is_absent: isAbsent,
                        remarks: remarks
                    });
                }
            });

            showSavingIndicator(false, isSilent ? 'Autosaving tests...' : 'Saving tests...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/test-marks`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ section_name: activeTestSection, evaluations: evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                } else if (!isSilent) {
                    alert(data.message || 'Error saving trade test marks.');
                }
            } catch (err) {
                if (!isSilent) alert('Server error.');
            }
        }

        // AJAX Action: Save Consolidated Fast CIA
        async function saveConsolidatedCiaFast(isSilent = false) {
            const students = [];
            document.querySelectorAll('.cia-row').forEach(row => {
                const reg = row.dataset.reg;
                const work = row.querySelector('.cia-work-input')?.value;
                const open = row.querySelector('.cia-open-input')?.value;
                const test = row.querySelector('.cia-test-input')?.value;
                const att  = row.querySelector('.cia-att-input')?.value;

                students.push({
                    reg_no: reg,
                    work_375: work,
                    open_75: open,
                    test_15: test,
                    att_15: att
                });
            });

            showSavingIndicator(false, isSilent ? 'Autosaving CIA...' : 'Saving CIA marks...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/fast-cia/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ students: students })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                } else if (!isSilent) {
                    alert(data.message || 'Error saving CIA register.');
                }
            } catch (err) {
                if (!isSilent) alert('Server error.');
            }
        }

        // AJAX Action: Save Attendance Overrides
        async function saveAttendanceOverrides(isSilent = false) {
            const evaluations = [];
            document.querySelectorAll('#attendanceTable tbody tr').forEach(tr => {
                const reg = tr.dataset.reg;
                const pct = tr.querySelector('.att-override-pct')?.value;
                const mark = tr.querySelector('.att-override-mark')?.value;

                evaluations.push({
                    reg_no: reg,
                    override_percentage: pct,
                    override_mark: mark
                });
            });

            showSavingIndicator(false, isSilent ? 'Autosaving attendance...' : 'Saving attendance...');
            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/attendance/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ evaluations: evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                } else if (!isSilent) {
                    alert(data.message || 'Error saving attendance.');
                }
            } catch (err) {
                if (!isSilent) alert('Server error.');
            }
        }

        // Handle Syllabus Upload Form
        document.getElementById('formUploadSyllabus')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitSyllabus');
            if (btn) btn.disabled = true;

            const formData = new FormData(this);
            showSavingIndicator(false, 'Uploading PDF...');

            try {
                const res = await fetch(`/r21/classroom/workshop/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator(true);
                    location.reload();
                } else {
                    alert(data.message || 'Error uploading syllabus.');
                }
            } catch (err) {
                alert('Upload failed.');
            } finally {
                if (btn) btn.disabled = false;
            }
        });

        // Live Search & Filter for Work Status Matrix Table
        function filterStatusMatrixTable() {
            const q = (document.getElementById('matrixSearchInput')?.value || '').toLowerCase().trim();
            const batch = document.getElementById('matrixBatchFilter')?.value || 'ALL';
            const status = document.getElementById('matrixStatusFilter')?.value || 'ALL';
            const result = document.getElementById('matrixResultFilter')?.value || 'ALL';

            document.querySelectorAll('.matrix-row').forEach(row => {
                const rowBatch = row.dataset.batch;
                const rowEvalWorks = parseInt(row.dataset.evalWorks || 0);
                const rowPendingWorks = parseInt(row.dataset.pendingWorks || 0);
                const rowPass = row.dataset.pass === '1';
                const rowText = row.innerText.toLowerCase();

                let visible = true;
                if (batch !== 'ALL' && rowBatch !== batch) visible = false;
                if (q && !rowText.includes(q)) visible = false;
                if (result === 'PASS' && !rowPass) visible = false;
                if (result === 'BELOW' && rowPass) visible = false;

                if (status === 'ALL_DONE' && rowEvalWorks < 6) visible = false;
                if (status === 'HAS_PENDING' && rowPendingWorks === 0) visible = false;
                if (status === 'ZERO_WORKS' && rowEvalWorks > 0) visible = false;

                row.style.display = visible ? '' : 'none';
            });
        }
    </script>
</body>
</html>
