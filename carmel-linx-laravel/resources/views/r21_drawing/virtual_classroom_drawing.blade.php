<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>[{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}] {{ $batchSubject->subject_name }} - Drawing Lab (R-2021)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        // Zero-flicker Theme Pre-loader (Compatible with Carmel Linx common preference)
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
        }

        /* ------------------------------------------------------------- */
        /* BASE & TYPOGRAPHY: DEFAULT DARK THEME (COMMON CARMEL LINX)   */
        /* ------------------------------------------------------------- */
        body {
            background-color: var(--bg-primary);
            color: #f8fafc;
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            font-size: 0.83rem;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
            color: #f8fafc;
        }

        /* Top Navbar */
        .navbar-custom {
            background: #090e17;
            border-bottom: 2px solid #1e293b;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            padding: 0.85rem 1.25rem;
            min-height: 72px;
            transition: all 0.2s ease;
        }
        .vc-nav-title {
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            line-height: 1.15;
            letter-spacing: -0.01em;
        }
        .vc-sub-title {
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.2px;
        }
        .vc-nav-subtext {
            color: #94a3b8 !important;
            font-size: 0.62rem;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .vc-code-badge {
            background: rgba(37, 99, 235, 0.2);
            color: #60a5fa !important;
            border: 1.5px solid rgba(59, 130, 246, 0.45);
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
            color: #ffffff !important;
        }
        .vc-theme-btn {
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(15, 23, 42, 0.6);
            color: #f1f5f9 !important;
            border-radius: 8px;
            font-size: 0.76rem;
        }

        /* Card Panels */
        .glass-card {
            background: #0f172c;
            border: 1px solid #1e293b;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        /* Metric Cards */
        .metric-card {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            background: #0f172c;
            border: 1px solid #1e293b;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            height: 100%;
            transition: all 0.2s ease;
        }
        .metric-card:hover {
            border-color: #38bdf8;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(56, 189, 248, 0.15);
        }
        .metric-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .metric-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .metric-val {
            font-size: 1.25rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            line-height: 1.2;
            color: #f8fafc;
        }
        .metric-sub {
            font-size: 0.70rem;
            color: #94a3b8;
        }

        /* Academic Metadata Bar */
        .vc-metadata-bar {
            background: rgba(15, 23, 42, 0.65);
            border: 1px solid rgba(51, 65, 85, 0.6);
            border-radius: 1rem;
            padding: 0.65rem 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
            font-size: 0.76rem;
            color: #94a3b8;
            margin-bottom: 0.85rem;
            transition: all 0.2s ease;
        }
        .vc-meta-badge {
            font-family: monospace;
            font-weight: 700;
            color: #f1f5f9;
            background: #030712;
            border: 1px solid #334155;
            padding: 0.2rem 0.65rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        .vc-meta-badge-sub {
            font-family: monospace;
            color: #cbd5e1;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(51, 65, 85, 0.6);
            padding: 0.2rem 0.65rem;
            border-radius: 0.5rem;
        }
        .vc-meta-dot {
            color: #475569;
            font-weight: 800;
        }

        /* Unified Virtual Classroom Horizontal Tab Strip Navigation */
        .vc-tab-strip-card {
            background: rgba(2, 6, 23, 0.85);
            border: 1px solid rgba(51, 65, 85, 0.7);
            border-radius: 1rem;
            padding: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            margin-bottom: 1.15rem;
            transition: all 0.2s ease;
        }

        .vc-tab-nav {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            flex-wrap: nowrap;
        }
        .vc-tab-nav::-webkit-scrollbar {
            display: none;
        }

        .vc-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.58rem 1.05rem;
            border-radius: 0.75rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: #94a3b8 !important;
            background: transparent !important;
            border: 2px solid transparent !important;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            text-decoration: none !important;
            line-height: 1.25;
        }
        .vc-tab-btn:hover {
            color: #f1f5f9 !important;
            background: #0f172a !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
        }
        .vc-tab-btn.active, 
        .vc-tab-btn.nav-link.active {
            color: #ffffff !important;
            background: rgba(56, 189, 248, 0.12) !important;
            border: 2px solid #38bdf8 !important;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.25) !important;
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
        }
        .vc-tab-btn .material-symbols-rounded {
            font-size: 1.18rem;
            line-height: 1;
        }

        /* Fast Input Controls (Dark Mode Default) */
        .fast-input {
            width: 74px;
            height: 33px;
            text-align: center;
            font-weight: 800;
            font-size: 0.90rem;
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
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
            outline: none;
        }
        .fast-input.is-overridden {
            border-color: #f59e0b;
            color: #fbbf24;
            background-color: rgba(245, 158, 11, 0.15);
            box-shadow: 0 0 0 1px #f59e0b;
        }
        .fast-input::placeholder {
            color: #64748b;
            font-weight: 500;
        }

        /* High-Contrast Tables (Dark Mode Default) */
        .table-custom {
            color: #f8fafc;
            border-color: #1e293b;
            font-size: 0.82rem;
            margin-bottom: 0;
            background-color: #0f172c;
        }
        .table-custom th {
            background-color: #090e17 !important;
            color: #38bdf8 !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.70rem;
            letter-spacing: 0.04em;
            padding: 0.65rem 0.5rem;
            border: 1px solid #1e293b !important;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table-custom th small {
            color: #94a3b8 !important;
            font-weight: 600;
        }
        .table-custom th.th-highlight {
            background-color: #061e36 !important;
            color: #fbbf24 !important;
        }
        .table-custom td {
            background-color: #0f172c !important;
            color: #f8fafc;
            border: 1px solid #1e293b !important;
            vertical-align: middle;
            padding: 0.45rem 0.5rem;
        }
        .table-custom tr:nth-child(even) td {
            background-color: #0b1222 !important;
        }
        .table-custom tr:hover td {
            background-color: #162038 !important;
        }

        .roll-badge {
            font-weight: 800;
            color: #f1f5f9;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 4px;
            padding: 2px 7px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
        }
        .reg-badge {
            font-family: monospace;
            font-weight: 700;
            color: #93c5fd;
            background-color: rgba(37, 99, 235, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-radius: 4px;
            padding: 2px 7px;
            font-size: 0.82rem;
        }

        /* Specific Highlight Cells */
        .td-cia-total {
            background-color: rgba(34, 197, 94, 0.12) !important;
        }
        .td-cia-total .val-total-cia {
            color: #4ade80 !important;
        }
        .td-series-avg {
            background-color: rgba(168, 85, 247, 0.12) !important;
        }
        .td-series-avg .val-series-avg {
            color: #c084fc !important;
        }

        /* Sheet Pill */
        .sheet-pill {
            cursor: pointer;
            border: 1px solid #334155;
            background: #0f172a;
            padding: 0.38rem 0.95rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #94a3b8;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .sheet-pill:hover {
            background: #1e293b;
            color: #f8fafc;
        }
        .sheet-pill.active {
            background: #2563eb !important;
            border-color: #38bdf8 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25) !important;
        }

        /* Subview Toggle Button */
        .subview-btn {
            border: 1px solid #334155;
            background: #0f172a;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.76rem;
            padding: 0.35rem 0.85rem;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .subview-btn:hover {
            background: #1e293b;
            color: #f8fafc;
        }
        .subview-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #38bdf8;
            font-weight: 700;
        }

        /* Autosave Badge */
        .autosave-pill {
            font-size: 0.72rem;
            padding: 0.28rem 0.65rem;
            border-radius: 16px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .autosave-ready {
            background: rgba(16, 185, 129, 0.15);
            color: #4ade80;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .autosave-saving {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Common Academic Reports Hub (Unified Design) */
        .report-hub-card {
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(51, 65, 85, 0.6);
            border-radius: 1rem;
            padding: 1.15rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.95rem;
            height: 100%;
            transition: all 0.2s ease;
        }
        .report-hub-card:hover {
            border-color: rgba(100, 116, 139, 0.8);
        }
        .report-hub-header {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 0.4rem;
        }
        .report-hub-header .material-symbols-rounded {
            font-size: 1.25rem;
            color: #94a3b8;
            line-height: 1;
        }
        .report-hub-title {
            font-size: 0.76rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #e2e8f0;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }
        .report-hub-desc {
            font-size: 0.74rem;
            color: #94a3b8;
            line-height: 1.45;
            margin-bottom: 0;
        }
        .report-hub-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding-top: 0.65rem;
            border-top: 1px solid rgba(51, 65, 85, 0.5);
        }
        .report-hub-btn {
            width: 100%;
            padding: 0.65rem 0.95rem;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(51, 65, 85, 0.6);
            color: #cbd5e1;
            border-radius: 0.75rem;
            font-size: 0.77rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            text-decoration: none !important;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }
        .report-hub-btn:hover {
            background: #1e293b;
            border-color: #64748b;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .report-hub-btn .hub-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .report-hub-btn .hub-icon {
            font-size: 1.1rem;
            color: #94a3b8;
            line-height: 1;
        }
        .report-hub-btn:hover .hub-icon {
            color: #38bdf8;
        }
        .report-hub-btn .hub-arrow {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1;
            transition: transform 0.18s ease;
        }
        .report-hub-btn:hover .hub-arrow {
            color: #f1f5f9;
            transform: translateX(2px);
        }

        /* Active / Highlighted Emerald Pill (Matching Consolidated Mark Report in reference) */
        .report-hub-btn.hub-btn-emerald {
            border: 1px solid rgba(16, 185, 129, 0.55);
            background: rgba(6, 78, 59, 0.22);
            color: #a7f3d0;
        }
        .report-hub-btn.hub-btn-emerald:hover {
            background: rgba(6, 78, 59, 0.38);
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.25);
        }
        .report-hub-btn.hub-btn-emerald .hub-icon,
        .report-hub-btn.hub-btn-emerald .hub-arrow {
            color: #34d399;
        }

        /* Info Strips & Slabs in Dark Mode */
        body:not(.light-theme) .sheet-info-strip {
            background: rgba(2, 132, 199, 0.15) !important;
            border: 1px solid rgba(56, 189, 248, 0.3) !important;
            color: #e0f2fe !important;
        }
        body:not(.light-theme) .matrix-info-strip {
            background: rgba(16, 185, 129, 0.12) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            color: #dcfce7 !important;
        }
        body:not(.light-theme) .slab-info-strip {
            background: rgba(16, 185, 129, 0.12) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            color: #dcfce7 !important;
        }
        body:not(.light-theme) .slab-badge {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        body:not(.light-theme) .text-dark {
            color: #f8fafc !important;
        }
        body:not(.light-theme) .text-secondary {
            color: #94a3b8 !important;
        }
        body:not(.light-theme) .bg-white {
            background-color: #0f172c !important;
        }
        body:not(.light-theme) .bg-light {
            background-color: #0b1222 !important;
        }
        body:not(.light-theme) .border {
            border-color: #1e293b !important;
        }
        body:not(.light-theme) .modal-content {
            background-color: #0f172c !important;
            color: #f8fafc !important;
            border: 1px solid #1e293b !important;
        }
        body:not(.light-theme) .modal-header,
        body:not(.light-theme) .modal-footer {
            background-color: #090e17 !important;
            border-color: #1e293b !important;
        }
        body:not(.light-theme) .modal-title {
            color: #f8fafc !important;
        }
        body:not(.light-theme) .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        body:not(.light-theme) .form-control,
        body:not(.light-theme) .form-select {
            background-color: #070e1c !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        body:not(.light-theme) .form-control:focus,
        body:not(.light-theme) .form-select:focus {
            background-color: #091326 !important;
            border-color: #38bdf8 !important;
            color: #f8fafc !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25) !important;
        }
        body:not(.light-theme) ::-webkit-scrollbar { width: 7px; height: 7px; }
        body:not(.light-theme) ::-webkit-scrollbar-track { background: #080d1a; }
        body:not(.light-theme) ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }


        /* ------------------------------------------------------------- */
        /* LIGHT THEME OVERRIDES (ACTIVATED VIA TOGGLE BUTTON)           */
        /* ------------------------------------------------------------- */
        body.light-theme {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
        }
        body.light-theme h1,
        body.light-theme h2,
        body.light-theme h3,
        body.light-theme h4,
        body.light-theme h5,
        body.light-theme h6,
        body.light-theme .brand-font {
            color: #0f172a !important;
        }

        body.light-theme .navbar-custom {
            background: #ffffff !important;
            border-bottom: 2px solid #e2e8f0 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
            padding: 0.85rem 1.25rem !important;
            min-height: 72px !important;
        }
        body.light-theme .vc-nav-title {
            color: #0f172a !important; /* REVERSED: Dark in Light Mode */
        }
        body.light-theme .vc-sub-title {
            color: #0f172a !important; /* REVERSED: Dark in Light Mode */
        }
        body.light-theme .vc-nav-subtext {
            color: #64748b !important;
        }
        body.light-theme .vc-code-badge {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }
        body.light-theme .vc-staff-pill {
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155 !important;
        }
        body.light-theme .vc-staff-pill strong {
            color: #0f172a !important;
        }
        body.light-theme .vc-theme-btn {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.light-theme .vr {
            background-color: #cbd5e1 !important;
        }

        body.light-theme .glass-card {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05) !important;
        }

        body.light-theme .metric-card {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04) !important;
        }
        body.light-theme .metric-card:hover {
            border-color: #93c5fd !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08) !important;
        }
        body.light-theme .metric-label {
            color: #64748b !important;
        }
        body.light-theme .metric-val {
            color: #0f172a !important;
        }
        body.light-theme .metric-sub {
            color: #64748b !important;
        }

        body.light-theme .vc-metadata-bar {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #64748b !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
        }
        body.light-theme .vc-meta-badge {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.light-theme .vc-meta-badge-sub {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #334155 !important;
        }
        body.light-theme .vc-meta-dot {
            color: #94a3b8 !important;
        }

        body.light-theme .vc-tab-strip-card {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: #cbd5e1 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        }
        body.light-theme .vc-tab-btn {
            color: #475569 !important;
        }
        body.light-theme .vc-tab-btn:hover {
            color: #0f172a !important;
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
        }
        body.light-theme .vc-tab-btn.active,
        body.light-theme .vc-tab-btn.nav-link.active {
            color: #0f172a !important;
            background: rgba(37, 99, 235, 0.08) !important;
            border: 2px solid #2563eb !important;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.2) !important;
        }

        body.light-theme .fast-input {
            color: #0f172a !important;
            background-color: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
        }
        body.light-theme .fast-input:focus {
            color: #0f172a !important;
            background-color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18) !important;
        }
        body.light-theme .fast-input.is-overridden {
            border-color: #f59e0b !important;
            color: #b45309 !important;
            background-color: #fffbeb !important;
        }
        body.light-theme .fast-input::placeholder {
            color: #94a3b8 !important;
        }

        body.light-theme .table-custom {
            color: #1e293b !important;
            border-color: #cbd5e1 !important;
            background-color: #ffffff !important;
        }
        body.light-theme .table-custom th {
            background-color: #1e3a5f !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
        }
        body.light-theme .table-custom th small {
            color: #cbd5e1 !important;
        }
        body.light-theme .table-custom th.th-highlight {
            background-color: #0f2d52 !important;
            color: #fbbf24 !important;
        }
        body.light-theme .table-custom td {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
        }
        body.light-theme .table-custom tr:nth-child(even) td {
            background-color: #f8fafc !important;
        }
        body.light-theme .table-custom tr:hover td {
            background-color: #eff6ff !important;
        }

        body.light-theme .roll-badge {
            color: #334155 !important;
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
        }
        body.light-theme .reg-badge {
            color: #1e40af !important;
            background-color: #eff6ff !important;
            border-color: #bfdbfe !important;
        }

        body.light-theme .td-cia-total {
            background-color: #fffbeb !important;
        }
        body.light-theme .td-cia-total .val-total-cia {
            color: #b45309 !important;
        }
        body.light-theme .td-series-avg {
            background-color: #faf5ff !important;
        }
        body.light-theme .td-series-avg .val-series-avg {
            color: #7c3aed !important;
        }

        body.light-theme .sheet-pill {
            border-color: #cbd5e1 !important;
            background: #f8fafc !important;
            color: #475569 !important;
        }
        body.light-theme .sheet-pill:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        body.light-theme .sheet-pill.active {
            background: #2563eb !important;
            border-color: #1d4ed8 !important;
            color: #ffffff !important;
        }

        body.light-theme .subview-btn {
            border-color: #cbd5e1 !important;
            background: #f8fafc !important;
            color: #475569 !important;
        }
        body.light-theme .subview-btn:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        body.light-theme .subview-btn.active {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #1d4ed8 !important;
        }

        body.light-theme .autosave-ready {
            background: #ecfdf5 !important;
            color: #059669 !important;
            border-color: #a7f3d0 !important;
        }
        body.light-theme .autosave-saving {
            background: #fffbeb !important;
            color: #d97706 !important;
            border-color: #fde68a !important;
        }

        body.light-theme .report-hub-card {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
        }
        body.light-theme .report-hub-card:hover {
            border-color: #cbd5e1 !important;
        }
        body.light-theme .report-hub-header .material-symbols-rounded {
            color: #64748b !important;
        }
        body.light-theme .report-hub-title {
            color: #0f172a !important;
        }
        body.light-theme .report-hub-desc {
            color: #64748b !important;
        }
        body.light-theme .report-hub-list {
            border-top-color: #e2e8f0 !important;
        }
        body.light-theme .report-hub-btn {
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #334155 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        }
        body.light-theme .report-hub-btn:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.light-theme .report-hub-btn .hub-icon {
            color: #64748b !important;
        }
        body.light-theme .report-hub-btn:hover .hub-icon {
            color: #2563eb !important;
        }
        body.light-theme .report-hub-btn .hub-arrow {
            color: #94a3b8 !important;
        }
        body.light-theme .report-hub-btn:hover .hub-arrow {
            color: #0f172a !important;
        }
        body.light-theme .report-hub-btn.hub-btn-emerald {
            border-color: #86efac !important;
            background: #ecfdf5 !important;
            color: #065f46 !important;
        }
        body.light-theme .report-hub-btn.hub-btn-emerald:hover {
            background: #d1fae5 !important;
            border-color: #34d399 !important;
            color: #064e3b !important;
        }
        body.light-theme .report-hub-btn.hub-btn-emerald .hub-icon,
        body.light-theme .report-hub-btn.hub-btn-emerald .hub-arrow {
            color: #059669 !important;
        }

        body.light-theme .sheet-info-strip {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #1e40af !important;
        }
        body.light-theme .matrix-info-strip {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            color: #166534 !important;
        }
        body.light-theme .slab-info-strip {
            background: rgba(16, 185, 129, 0.08) !important;
            border: 1px solid rgba(16, 185, 129, 0.25) !important;
            color: #166534 !important;
        }
        body.light-theme .slab-badge {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.light-theme .text-dark {
            color: #0f172a !important;
        }
        body.light-theme .text-secondary {
            color: #64748b !important;
        }
        body.light-theme .bg-white {
            background-color: #ffffff !important;
        }
        body.light-theme .bg-light {
            background-color: #f8fafc !important;
        }
        body.light-theme .border {
            border-color: #e2e8f0 !important;
        }
        body.light-theme .modal-content {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }
        body.light-theme .modal-header,
        body.light-theme .modal-footer {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        body.light-theme .modal-title {
            color: #0f172a !important;
        }
        body.light-theme .btn-close {
            filter: none !important;
        }
        body.light-theme .form-control,
        body.light-theme .form-select {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        body.light-theme .form-control:focus,
        body.light-theme .form-select:focus {
            background-color: #ffffff !important;
            border-color: #2563eb !important;
            color: #0f172a !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18) !important;
        }
        body.light-theme ::-webkit-scrollbar { width: 7px; height: 7px; }
        body.light-theme ::-webkit-scrollbar-track { background: #f1f5f9; }
        body.light-theme ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
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
            <!-- Left: Clinx Logo, Carmel Linx Title & Course Details -->
            <div class="d-flex align-items-center gap-2.5 sm:gap-3 flex-wrap">
                @php
                    $role = session('userRole');
                    $backUrl = ($role === 'Demonstrator') ? '/dashboard/demonstrator' : (($role === 'Trade_Instructor') ? '/dashboard/tradeinstructor' : '/dashboard/lecturer');
                @endphp
                <!-- Clinx Logo & Carmel Linx Title (Common in all Virtual Classrooms) -->
                <a href="{{ $backUrl }}" class="d-flex align-items-center gap-2 text-decoration-none shrink-0 me-1" title="Return to Dashboard">
                    <img src="{{ asset('logo.jpg') }}" alt="Carmel Linx Logo" style="width: 36px; height: 36px; border-radius: 9px; object-fit: cover; border: 1.5px solid rgba(56, 189, 248, 0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.25);">
                    <div class="d-flex flex-column leading-none">
                        <span class="vc-nav-title">Carmel Linx</span>
                        <span class="vc-nav-subtext">Drawing Lab &bull; R-2021</span>
                    </div>
                </a>

                <div class="vr bg-secondary opacity-50 d-none d-md-block mx-1" style="height: 32px;"></div>

                <!-- Course Code (Proportionate) & Subject Name (Big & Prominent) -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge px-2.5 py-1 vc-code-badge">
                        {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}
                    </span>
                    <span class="vc-sub-title">
                        {{ $batchSubject->subject_name }}
                    </span>
                    <span class="badge bg-primary px-2 py-0.5 fw-bold" style="font-size: 0.68rem;">REV 2021 SCHEME</span>
                </div>
            </div>

            <!-- Right: Staff Pill, Theme Switcher & Red Return Button (As Usual) -->
            <div class="d-flex align-items-center gap-2 ms-auto">
                <span class="d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill vc-staff-pill">
                    <i class="fa-solid fa-user-tie text-info"></i>
                    <strong>{{ session('userName') ?? 'Faculty' }}</strong>
                </span>

                <!-- Dynamic Saving Pill (Only visible when saving) -->
                <span id="saveStatusIndicator" class="autosave-pill autosave-saving" style="display: none;"></span>

                <!-- Theme Toggle Button (Dark Default / Light Switcher) -->
                <button type="button" onclick="toggleTheme()" class="btn btn-sm px-2.5 py-1.5 d-flex align-items-center gap-1.5 vc-theme-btn" id="themeToggleBtn" title="Toggle Light/Dark Theme">
                    <span id="themeToggleIcon" class="material-symbols-rounded" style="font-size: 1.05rem; line-height: 1;">light_mode</span>
                    <span id="themeToggleText" class="fw-bold d-none d-sm-inline">Light Mode</span>
                </button>

                <!-- Return Button in Red Colour as Usual -->
                <a href="{{ $backUrl }}" class="btn btn-danger btn-sm px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow" style="font-size: 0.78rem; background-color: #dc2626; border-color: #b91c1c; border-radius: 8px;" title="Return to Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-2.5 px-3 px-md-4">



        <!-- CARD 2: Academic Metadata Row & Quick Syllabus Actions (Below Top Title Bar) -->
        <div class="vc-metadata-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="vc-meta-badge">Batch: {{ $classroom->classroom_id ?? $batchSubject->classroom_id }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge">SEM: {{ $classroom->current_semester ?? $batchSubject->semester ?? 3 }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge">Branch: {{ $batchSubject->branch ?? $classroom->branch ?? 'CE' }}</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge-sub">Revision: R-2021</span>
                <span class="vc-meta-dot">&bull;</span>
                <span class="vc-meta-badge-sub">CIA: 75M | ESE: 50M</span>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                <!-- Upload Syllabus Button (Blue Button) -->
                <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadSyllabus" style="font-size: 0.76rem; border-radius: 0.5rem; background-color: #2563eb; border-color: #1d4ed8;" title="Upload or Update Syllabus PDF">
                    <span class="material-symbols-rounded" style="font-size: 1.05rem;">cloud_upload</span>
                    <span>Upload Syllabus</span>
                </button>
                <!-- View Syllabus Button (Green Text) -->
                @if(!empty($drawingCourseFile->syllabus_pdf_path))
                    <a href="{{ $drawingCourseFile->syllabus_pdf_path }}" target="_blank" class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1.5 px-3 fw-bold" style="font-size: 0.76rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1.5px solid #10b981;" title="View Uploaded Syllabus Document (PDF)">
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

        <!-- CARD 3: Professional Horizontal Tab Strip Navigation Container (Virtual Theory Classroom R-2021 Model) -->
        <div class="vc-tab-strip-card">
            <nav class="vc-tab-nav" id="r21DrawingTabs" role="tablist">
                <!-- TAB 1: Consolidated CIA Register -->
                <button class="vc-tab-btn nav-link active" id="tab-cia-link" data-bs-toggle="tab" data-bs-target="#tab-cia" type="button" role="tab" aria-selected="true">
                    <span class="material-symbols-rounded text-sky-400">table_chart</span>
                    <span>Consolidated CIA Register</span>
                    <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.65rem;">75M CIA</span>
                </button>

                <!-- TAB 2: Sheet Mark Entry Tab (Hidden: Using consolidated override of 37.5M in CIA register) -->
                <button class="vc-tab-btn nav-link d-none" id="tab-sheets-link" data-bs-toggle="tab" data-bs-target="#tab-sheets" type="button" role="tab" aria-selected="false" style="display: none !important;">
                    <span class="material-symbols-rounded text-info">draw</span>
                    <span>Sheet Mark Entry</span>
                    <span class="badge bg-info text-dark font-monospace" style="font-size: 0.65rem;">37.5M</span>
                </button>

                <!-- TAB 3: Series Mark Entry Tab -->
                <button class="vc-tab-btn nav-link" id="tab-series-link" data-bs-toggle="tab" data-bs-target="#tab-series" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded" style="color: #c084fc;">school</span>
                    <span>Series Mark Entry</span>
                    <span class="badge bg-primary font-monospace" style="font-size: 0.65rem;">15M Avg</span>
                </button>

                <!-- TAB 4: Attendance Register Tab -->
                <button class="vc-tab-btn nav-link" id="tab-attendance-link" data-bs-toggle="tab" data-bs-target="#tab-attendance" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-emerald-400">how_to_reg</span>
                    <span>Attendance Register</span>
                    <span class="badge bg-success font-monospace" style="font-size: 0.65rem;">15M Att</span>
                </button>

                <!-- TAB 5: Lesson Planner Tab -->
                <button class="vc-tab-btn nav-link" id="tab-lessonplan-link" data-bs-toggle="tab" data-bs-target="#tab-lessonplan" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-emerald-400">calendar_month</span>
                    <span>Lesson Planner</span>
                    <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">60 Hrs</span>
                </button>

                <!-- TAB 6: Reports & Downloads Tab -->
                <button class="vc-tab-btn nav-link" id="tab-reports-link" data-bs-toggle="tab" data-bs-target="#tab-reports" type="button" role="tab" aria-selected="false">
                    <span class="material-symbols-rounded text-cyan-400">assessment</span>
                    <span>Reports &amp; Downloads</span>
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT CONTAINER -->
        <div class="tab-content" id="r21DrawingTabContent">

            <!-- ========================================================= -->
            <!-- TAB 1: CONSOLIDATED CIA REGISTER (FINAL CONSOLIDATED HUB)  -->
            <!-- ========================================================= -->
            <div class="tab-pane fade show active" id="tab-cia" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><i class="fa-solid fa-award me-1.5 text-warning"></i>Consolidated CIA Register (Total CIA: 75 Marks | ESE: 50 Marks)</div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Enter consolidated marks directly: <strong>Sheet Evaluation (37.5M Consolidated)</strong>, <strong>Open-Ended (7.5M)</strong>, <strong>Series Average (15.0M)</strong>, <strong>Attendance Mark (15.0M from TEAMS)</strong>. Real-time Total CIA calculated live.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <input type="text" id="ciaSearchInput" class="form-control form-control-sm bg-white text-dark border" placeholder="Search student..." style="width: 170px; font-size: 0.78rem;" onkeyup="filterCiaTable()">
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="saveConsolidatedCiaFast()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Consolidated Marks
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="consolidatedCiaTable">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">Roll</th>
                                    <th style="width: 115px;">Reg No</th>
                                    <th class="text-start" style="min-width: 165px;">Student Name</th>
                                    <th style="width: 135px;" class="text-info">Sheet Eval Mark<br><small>(Consolidated 37.5M)</small></th>
                                    <th style="width: 110px;" class="text-warning">Open-Ended<br><small>(Max 7.5M)</small></th>
                                    <th style="width: 110px;" style="color: #c084fc;">Series Avg<br><small>(Max 15.0M)</small></th>
                                    <th style="width: 95px;">TEAMS Att %</th>
                                    <th style="width: 115px;" class="text-success">Attendance Mark<br><small>(Max 15.0M)</small></th>
                                    <th style="width: 95px;" class="th-highlight text-warning">Total CIA<br><small>(Max 75M)</small></th>
                                    <th style="width: 80px;">Result</th>
                                    <th style="width: 95px;">ESE Mark<br><small>(Max 50M)</small></th>
                                    <th style="width: 95px;">Grand Total<br><small>(Max 125M)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                    <tr data-reg-no="{{ $r['reg_no'] }}" class="cia-row">
                                        <td><span class="roll-badge">{{ $r['roll_no'] ?? '-' }}</span></td>
                                        <td><span class="reg-badge">{{ $r['reg_no'] }}</span></td>
                                        <td class="text-start fw-bold text-dark">
                                            {{ $r['name'] }}
                                            @if($r['open_ended_topic'])
                                                <div class="text-secondary font-monospace" style="font-size: 0.66rem;" title="{{ $r['open_ended_topic'] }}">
                                                    <i class="fa-solid fa-lightbulb text-warning me-0.5"></i>{{ Str::limit($r['open_ended_topic'], 24) }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Sheet Evaluation Mark (37.5M Consolidated with direct override provision) -->
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <input type="number" step="0.5" min="0" max="37.5"
                                                    class="fast-input input-continuous {{ $r['has_continuous_override'] ? 'is-overridden' : '' }}"
                                                    value="{{ $r['has_continuous_override'] ? $r['continuous_override'] : ($r['calc_continuous'] > 0 ? $r['calc_continuous'] : '') }}"
                                                    placeholder="{{ $r['calc_continuous'] ?: '0' }}"
                                                    data-calculated="{{ $r['calc_continuous'] }}"
                                                    oninput="recalculateRow(this)">
                                            </div>
                                            <small class="text-secondary d-block mt-0.5" style="font-size: 0.65rem;">
                                                @if($r['has_continuous_override'])
                                                    <span class="text-warning fw-bold"><i class="fa-solid fa-sliders me-0.5"></i>Overridden</span>
                                                @elseif($r['calc_continuous'] > 0)
                                                    <span class="text-primary fw-semibold">Sheet Avg: {{ $r['calc_continuous'] }}</span>
                                                @else
                                                    <span>Direct Entry</span>
                                                @endif
                                            </small>
                                        </td>

                                        <!-- Open-Ended Mark (7.5M) -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="7.5"
                                                class="fast-input input-openended"
                                                value="{{ $r['open_ended_mark'] > 0 ? $r['open_ended_mark'] : '' }}"
                                                placeholder="0"
                                                oninput="recalculateRow(this)">
                                        </td>

                                        <!-- Series Tests Avg (15.0M) -->
                                        <td>
                                            <span class="fw-bold fs-6 val-series" style="color: #7c3aed;">{{ $r['avg_series_15'] }}</span>
                                            <small class="text-secondary d-block" style="font-size: 0.65rem;">
                                                T1: {{ $r['t1_score_15'] !== null ? $r['t1_score_15'] : '-' }} | T2: {{ $r['t2_score_15'] !== null ? $r['t2_score_15'] : '-' }}
                                            </small>
                                        </td>

                                        <!-- TEAMS Attendance % -->
                                        <td>
                                            <span class="fw-bold text-dark">{{ $r['att_percentage'] }}%</span>
                                            <small class="text-secondary d-block" style="font-size: 0.63rem;">{{ $r['att_attended'] }}/{{ $r['att_total'] }}</small>
                                        </td>

                                        <!-- Attendance Mark (15.0M) from TEAMS / Slab / Override -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="15.0"
                                                class="fast-input input-attendance {{ $r['att_override'] !== null ? 'is-overridden' : '' }}"
                                                value="{{ $r['att_override'] !== null ? $r['att_override'] : $r['calc_att_mark'] }}"
                                                placeholder="{{ $r['calc_att_mark'] }}"
                                                data-calculated="{{ $r['calc_att_mark'] }}"
                                                oninput="recalculateRow(this)">
                                            <small class="text-secondary d-block mt-0.5" style="font-size: 0.65rem;">
                                                @if($r['att_override'] !== null)
                                                    <span class="text-warning fw-bold">Override (Slab: {{ $r['calc_att_mark'] }})</span>
                                                @else
                                                    <span>Slab: {{ $r['calc_att_mark'] }}</span>
                                                @endif
                                            </small>
                                        </td>

                                        <!-- Total CIA (Max 75.0 - Whole Number) -->
                                        <td class="td-cia-total">
                                            <span class="fw-bolder fs-6 val-total-cia">{{ round($r['total_cia']) }}</span>
                                            <small class="text-secondary d-block" style="font-size: 0.65rem;">/ 75</small>
                                        </td>

                                        <!-- Result Badge -->
                                        <td>
                                            <span class="badge {{ $r['is_cia_pass'] ? 'bg-success' : 'bg-danger' }} badge-status">
                                                {{ $r['is_cia_pass'] ? 'PASS' : 'FAIL' }}
                                            </span>
                                        </td>

                                        <!-- ESE Mark (Max 50.0) -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="50.0"
                                                class="fast-input input-ese"
                                                value="{{ $r['ese_mark'] !== null ? $r['ese_mark'] : '' }}"
                                                placeholder="-"
                                                oninput="recalculateRow(this)">
                                        </td>

                                        <!-- Grand Total (Max 125.0 - Whole Number) -->
                                        <td>
                                            <span class="fw-bold fs-6 text-dark val-grand-total">{{ round($r['grand_total']) }}</span>
                                            <small class="text-secondary d-block" style="font-size: 0.65rem;">/ 125</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 2: SHEET MARK ENTRY TAB (HIDDEN: CONSOLIDATED IN CIA) -->
            <!-- ========================================================= -->
            <div class="tab-pane fade d-none" id="tab-sheets" role="tabpanel" style="display: none !important;">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><i class="fa-solid fa-pen-ruler me-1.5 text-primary"></i>Continuous Drawing Sheets Evaluation &amp; Group Average Hub</div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Evaluate individual drawing sheets or view all sheets consolidated. <em>Individual sheet evaluation will be conducted from next semester</em> — you can directly enter consolidated marks in Tab 1, or evaluate sheets and group all to calculate the average out of 37.5M.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Toggle Subview -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="subview-btn active" id="btnViewSingleSheet" onclick="setSheetViewMode('single')">
                                    <i class="fa-solid fa-file-pen me-1"></i> Sheet By Sheet
                                </button>
                                <button type="button" class="subview-btn" id="btnViewAllSheets" onclick="setSheetViewMode('all')">
                                    <i class="fa-solid fa-table-cells me-1"></i> All Sheets Matrix &amp; Average
                                </button>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalManageSheets">
                                <i class="fa-solid fa-gear me-1"></i> Configure Sheets
                            </button>
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" id="btnSaveSheetActive" onclick="saveActiveSheetMarks()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Sheet Marks
                            </button>
                        </div>
                    </div>

                    <!-- SUBVIEW 1: SINGLE SHEET DETAIL ENTRY -->
                    <div id="subviewSingleSheetContainer">
                        <!-- Horizontal Sheet Selector Pills (e.g. Sheet 1 to Sheet 8) -->
                        <div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-3 border-bottom">
                            <span class="text-secondary small fw-bold text-uppercase me-1"><i class="fa-solid fa-layer-group me-1"></i>Select Sheet:</span>
                            @foreach($drawingCourseFile->parsed_sheets ?? [] as $index => $sh)
                                <div class="sheet-pill {{ $index === 0 ? 'active' : '' }}"
                                     onclick="switchSheet('{{ $sh['sheet_no'] }}', this)"
                                     data-sheet-no="{{ $sh['sheet_no'] }}"
                                     data-sheet-title="{{ $sh['title'] ?? '' }}"
                                     data-sheet-module="{{ $sh['module'] ?? '' }}"
                                     data-sheet-co="{{ $sh['co_id'] ?? '' }}">
                                    {{ $sh['sheet_no'] }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Active Sheet Banner -->
                        <div class="p-2.5 rounded-3 mb-3 d-flex align-items-center justify-content-between sheet-info-strip">
                            <div class="d-flex align-items-center gap-2.5">
                                <span class="badge bg-primary px-2.5 py-1 fw-bold fs-6" id="activeSheetLabel">{{ ($drawingCourseFile->parsed_sheets[0]['sheet_no'] ?? 'Sheet 1') }}</span>
                                <div>
                                    <div class="fw-bold text-dark fs-6" id="activeSheetTitle">{{ ($drawingCourseFile->parsed_sheets[0]['title'] ?? 'Drawing Sheet Practice') }}</div>
                                    <div class="text-secondary small" id="activeSheetMeta">
                                        <span id="activeSheetModuleTag">Module 1</span> &bull; 
                                        <span id="activeSheetCoTag">CO1</span> &bull; 
                                        Timely Completion (50M) + Accuracy &amp; Appearance (50M) = Max 100M
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom table-bordered align-middle text-center" id="sheetMarksTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Roll</th>
                                        <th style="width: 120px;">Reg No</th>
                                        <th class="text-start">Student Name</th>
                                        <th style="width: 130px;" class="text-info">Timely Completion<br><small>(Max 50)</small></th>
                                        <th style="width: 150px;" class="text-info">Accuracy &amp; Layout<br><small>(Max 50)</small></th>
                                        <th style="width: 110px;" class="text-warning">Total Score<br><small>(Max 100)</small></th>
                                        <th style="width: 80px;">Absent</th>
                                        <th style="width: 150px;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $initialSheetNo = $drawingCourseFile->parsed_sheets[0]['sheet_no'] ?? 'Sheet 1';
                                    @endphp
                                    @foreach($studentResults as $r)
                                        @php
                                            $shObj = $r['sheets_detail']->get($initialSheetNo);
                                        @endphp
                                        <tr data-reg-no="{{ $r['reg_no'] }}" class="sheet-eval-row">
                                            <td><span class="roll-badge">{{ $r['roll_no'] ?? '-' }}</span></td>
                                            <td><span class="reg-badge">{{ $r['reg_no'] }}</span></td>
                                            <td class="text-start fw-bold text-dark">{{ $r['name'] }}</td>
                                            <td>
                                                <input type="number" step="0.5" min="0" max="50"
                                                    class="fast-input input-timely"
                                                    value="{{ $shObj && !$shObj->is_absent && $shObj->timely_completion !== null ? floatval($shObj->timely_completion) : '' }}"
                                                    placeholder="0"
                                                    {{ $shObj && $shObj->is_absent ? 'disabled' : '' }}
                                                    oninput="calcSheetRow(this)">
                                            </td>
                                            <td>
                                                <input type="number" step="0.5" min="0" max="50"
                                                    class="fast-input input-appearance"
                                                    value="{{ $shObj && !$shObj->is_absent && $shObj->appearance_organization !== null ? floatval($shObj->appearance_organization) : '' }}"
                                                    placeholder="0"
                                                    {{ $shObj && $shObj->is_absent ? 'disabled' : '' }}
                                                    oninput="calcSheetRow(this)">
                                            </td>
                                            <td>
                                                <span class="fw-bold fs-6 text-primary val-sheet-total">
                                                    {{ $shObj ? ($shObj->is_absent ? 'ABS' : floatval($shObj->total_score_100)) : 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <input type="checkbox" class="form-check-input chk-absent"
                                                    {{ $shObj && $shObj->is_absent ? 'checked' : '' }}
                                                    onchange="toggleSheetAbsent(this)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm bg-white text-dark border input-remarks" 
                                                    value="{{ $shObj ? ($shObj->remarks ?? '') : '' }}" placeholder="Remarks">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SUBVIEW 2: ALL SHEETS CONSOLIDATED MATRIX & GROUP AVERAGE -->
                    <div id="subviewAllSheetsContainer" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 mb-3 matrix-info-strip">
                            <div>
                                <span class="fw-bold text-success"><i class="fa-solid fa-calculator me-1"></i>Group Average Calculation Engine:</span>
                                <span class="text-secondary small ms-2">Scores across all evaluated drawing sheets are grouped, averaged out of 100, and scaled to <strong>37.5 Marks Max</strong>.</span>
                            </div>
                            <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow" onclick="applyGroupedSheetsAverage()">
                                <i class="fa-solid fa-bolt me-1"></i> Apply Group Average to Continuous CIA (37.5M)
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom table-bordered align-middle text-center" id="allSheetsMatrixTable">
                                <thead>
                                    <tr>
                                        <th style="width: 45px;">Roll</th>
                                        <th style="width: 110px;">Reg No</th>
                                        <th class="text-start" style="min-width: 150px;">Student Name</th>
                                        @foreach($drawingCourseFile->parsed_sheets ?? [] as $sh)
                                            <th style="width: 60px; font-size: 0.65rem;" title="{{ $sh['title'] ?? '' }}">{{ $sh['sheet_no'] }}</th>
                                        @endforeach
                                        <th style="width: 65px;">Sheets</th>
                                        <th style="width: 80px;" class="text-info">Avg (/100)</th>
                                        <th style="width: 90px;" class="text-warning">Continuous<br><small>(Max 37.5M)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentResults as $r)
                                        <tr data-reg-no="{{ $r['reg_no'] }}" class="matrix-row">
                                            <td><span class="roll-badge">{{ $r['roll_no'] ?? '-' }}</span></td>
                                            <td><span class="reg-badge">{{ $r['reg_no'] }}</span></td>
                                            <td class="text-start fw-bold text-dark">{{ $r['name'] }}</td>
                                            @foreach($drawingCourseFile->parsed_sheets ?? [] as $sh)
                                                @php
                                                    $shVal = $r['sheets_detail']->get($sh['sheet_no']);
                                                @endphp
                                                <td class="matrix-cell" data-sheet-no="{{ $sh['sheet_no'] }}">
                                                    @if($shVal)
                                                        @if($shVal->is_absent)
                                                            <span class="text-danger fw-bold">ABS</span>
                                                        @else
                                                            <span class="fw-bold text-dark">{{ floatval($shVal->total_score_100) }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-secondary fw-semibold">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td>
                                                <span class="badge bg-light text-dark border matrix-count">{{ $r['sheet_count'] }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary matrix-avg">{{ $r['avg_sheet_score_100'] }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bolder fs-6 matrix-continuous" style="color: #b45309;">{{ $r['calc_continuous'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 3: SERIES MARK ENTRY TAB (SUMMATIVE SERIES TESTS 15M)  -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-series" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><i class="fa-solid fa-clipboard-check me-1.5" style="color: #7c3aed;"></i>Summative Series Tests Mark Entry (Max 15.0 Marks Average)</div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Two series tests are conducted covering course outcomes: <strong>Test 1 covers CO1 &amp; CO2</strong>, <strong>Test 2 covers CO3 &amp; CO4</strong>. The average of both tests is scaled out of <strong>15.0 Marks</strong>. Existing saved marks are preserved safely.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="saveSeriesFast()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Series Tests
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="seriesFastTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 120px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th style="width: 160px;">
                                        Test 1 (Max 15.0M)<br><small class="text-info font-monospace">CO1 &amp; CO2</small>
                                    </th>
                                    <th style="width: 85px;">T1 Absent</th>
                                    <th style="width: 160px;">
                                        Test 2 (Max 15.0M)<br><small class="font-monospace" style="color: #d8b4fe;">CO3 &amp; CO4</small>
                                    </th>
                                    <th style="width: 85px;">T2 Absent</th>
                                    <th style="width: 140px;" class="th-highlight text-warning">Series Average<br><small>(Max 15.0 Marks)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                    <tr data-reg-no="{{ $r['reg_no'] }}" class="series-fast-row">
                                        <td><span class="roll-badge">{{ $r['roll_no'] ?? '-' }}</span></td>
                                        <td><span class="reg-badge">{{ $r['reg_no'] }}</span></td>
                                        <td class="text-start fw-bold text-dark">{{ $r['name'] }}</td>

                                        <!-- Test 1 (Safe from Meenu M's entries) -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="15.0"
                                                class="fast-input input-t1"
                                                value="{{ $r['t1_score_15'] !== null && !$r['t1_absent'] ? $r['t1_score_15'] : '' }}"
                                                placeholder="0-15"
                                                {{ $r['t1_absent'] ? 'disabled' : '' }}
                                                oninput="calcSeriesRow(this)">
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input chk-t1-absent"
                                                {{ $r['t1_absent'] ? 'checked' : '' }}
                                                onchange="toggleSeriesAbsent(this, 't1')">
                                        </td>

                                        <!-- Test 2 -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="15.0"
                                                class="fast-input input-t2"
                                                value="{{ $r['t2_score_15'] !== null && !$r['t2_absent'] ? $r['t2_score_15'] : '' }}"
                                                placeholder="0-15"
                                                {{ $r['t2_absent'] ? 'disabled' : '' }}
                                                oninput="calcSeriesRow(this)">
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input chk-t2-absent"
                                                {{ $r['t2_absent'] ? 'checked' : '' }}
                                                onchange="toggleSeriesAbsent(this, 't2')">
                                        </td>

                                        <!-- Series Average (/15) -->
                                        <td class="td-series-avg">
                                            <span class="fw-bolder fs-6 val-series-avg">{{ $r['avg_series_15'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 4: ATTENDANCE REGISTER TAB (15.0M FROM TEAMS / LOG)   -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-attendance" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><i class="fa-solid fa-user-check me-1.5 text-success"></i>Attendance Register &amp; Statutory Slabs (Max 15.0 Marks - 20% CIA)</div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                State Board of Technical Education, Kerala: Attendance weightage is <strong>20% of 75 CIA = 15 Marks</strong>. Data pulled from TEAMS uploads and common institutional logs.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" 
                               class="btn btn-outline-success btn-sm px-2.5 py-1 d-flex align-items-center gap-1.5" 
                               title="Open Common Attendance & Log">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Open Common Log Desk</span>
                            </a>
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="saveAttendanceOverrides()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Attendance
                            </button>
                        </div>
                    </div>

                    <!-- Statutory Slab Strip -->
                    <div class="p-2.5 rounded-3 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2 slab-info-strip">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="text-success fw-bold small"><i class="fa-solid fa-scale-balanced me-1"></i>SBTE R21 Slabs (Max 15M):</span>
                            <span class="badge bg-white border text-dark slab-badge">&ge; 90%: <strong class="text-success">15.0 M</strong></span>
                            <span class="badge bg-white border text-dark slab-badge">80% - 89%: <strong class="text-primary">12.0 M</strong></span>
                            <span class="badge bg-white border text-dark slab-badge">75% - 79%: <strong class="text-warning">9.0 M</strong></span>
                            <span class="badge bg-white border text-dark slab-badge">&lt; 75%: <strong class="text-danger">0.0 M</strong></span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-center" id="attTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Roll</th>
                                    <th style="width: 120px;">Reg No</th>
                                    <th class="text-start">Student Name</th>
                                    <th style="width: 100px;">Total Classes</th>
                                    <th style="width: 100px;">Attended</th>
                                    <th style="width: 110px;">Percentage (%)</th>
                                    <th style="width: 130px;">Data Source</th>
                                    <th style="width: 120px;" class="text-success">Slab Mark (/15)</th>
                                    <th style="width: 130px;" class="text-warning">Manual Override (/15)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                    <tr data-reg-no="{{ $r['reg_no'] }}" class="att-row">
                                        <td><span class="roll-badge">{{ $r['roll_no'] ?? '-' }}</span></td>
                                        <td><span class="reg-badge">{{ $r['reg_no'] }}</span></td>
                                        <td class="text-start fw-bold text-dark">{{ $r['name'] }}</td>
                                        <td>{{ $r['att_total'] }}</td>
                                        <td>{{ $r['att_attended'] }}</td>
                                        <td><span class="fw-bold">{{ $r['att_percentage'] }}%</span></td>
                                        <td>
                                            <span class="badge bg-light text-primary border" style="font-size: 0.65rem;">
                                                {{ $r['att_source'] ?? 'Common Log' }}
                                            </span>
                                        </td>
                                        <td><span class="fw-bold text-success">{{ $r['calc_att_mark'] }}</span></td>
                                        <td>
                                            <input type="number" step="0.5" min="0" max="15.0"
                                                class="fast-input input-att-override {{ $r['att_override'] !== null ? 'is-overridden' : '' }}"
                                                value="{{ $r['att_override'] !== null ? $r['att_override'] : '' }}"
                                                placeholder="{{ $r['calc_att_mark'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 5: LESSON PLANNER TAB (60 CONTACT HOURS)              -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-lessonplan" role="tabpanel">
                <div class="glass-card p-3 mb-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><i class="fa-solid fa-calendar-days me-1.5 text-success"></i>Course Lesson Plan &amp; Schedule (60 Hours / 30 Sessions)</div>
                            <div class="text-secondary small" style="font-size: 0.76rem;">
                                Structured drawing hall drafting sessions covering syllabus modules and course outcomes. All fields (Day, Module, Topics, Hours, CO, Proposed Date, Actual Date, Status) are directly editable.
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-success btn-sm fw-semibold" onclick="addNewLessonPlanRow()">
                                <i class="fa-solid fa-plus me-1"></i> Add Session
                            </button>
                            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fa-solid fa-print me-1"></i> Print Lesson Plan
                            </a>
                            <button type="button" class="btn btn-primary btn-sm px-3 fw-bold" onclick="saveLessonPlansFast()">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Lesson Plan
                            </button>
                        </div>
                    </div>

                    <datalist id="coSuggestions">
                        <option value="CO1">
                        <option value="CO2">
                        <option value="CO3">
                        <option value="CO4">
                        <option value="CO1, CO2">
                        <option value="CO3, CO4">
                        <option value="All COs">
                    </datalist>

                    <div class="table-responsive">
                        <table class="table table-custom table-bordered align-middle text-start" id="lessonPlanTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 60px;">Day</th>
                                    <th style="width: 110px;">Module</th>
                                    <th>Planned Topics &amp; Drawing Exercises</th>
                                    <th style="width: 70px;">Hours</th>
                                    <th style="width: 90px;">CO</th>
                                    <th style="width: 145px;" class="text-info">Proposed Date</th>
                                    <th style="width: 145px;" class="text-success">Actual Date</th>
                                    <th style="width: 125px;">Status</th>
                                    <th style="width: 45px;"></th>
                                </tr>
                            </thead>
                            <tbody id="lessonPlanTableBody">
                                @foreach($lessonPlans as $plan)
                                    <tr data-plan-id="{{ $plan->id }}" class="plan-row">
                                        <td class="text-center">
                                            <input type="number" min="1" class="form-control form-control-sm text-center fw-bold plan-day" value="{{ $plan->day_no }}" style="width: 55px; margin: 0 auto;">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm text-center plan-module" value="{{ $plan->remarks ?? 'Module 1' }}" placeholder="Module">
                                        </td>
                                        <td>
                                            <textarea rows="1" class="form-control form-control-sm plan-topic" placeholder="Planned topics & exercises..." style="min-width: 200px;">{{ $plan->topic_content }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            <input type="number" min="1" max="10" step="1" class="form-control form-control-sm text-center plan-hours" value="{{ $plan->allocated_hours ?? 2 }}" style="width: 60px; margin: 0 auto;">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" list="coSuggestions" class="form-control form-control-sm text-center fw-bold text-primary plan-co" value="{{ $plan->co_id }}" placeholder="CO1">
                                        </td>
                                        <td class="text-center">
                                            <input type="date" class="form-control form-control-sm plan-proposed-date" 
                                                value="{{ $plan->proposed_date ? \Carbon\Carbon::parse($plan->proposed_date)->format('Y-m-d') : '' }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="date" class="form-control form-control-sm plan-actual-date" 
                                                value="{{ $plan->actual_date ? \Carbon\Carbon::parse($plan->actual_date)->format('Y-m-d') : '' }}">
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select form-select-sm plan-status">
                                                <option value="Completed" {{ $plan->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="In Progress" {{ $plan->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Pending" {{ ($plan->status === 'Pending' || empty($plan->status)) ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm p-1" onclick="deleteLessonPlanRow(this)" title="Delete session">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 6: REPORTS & DOWNLOADS TAB (COMMON DESIGN)            -->
            <!-- ========================================================= -->
            <div class="tab-pane fade" id="tab-reports" role="tabpanel">
                <div class="glass-card p-3.5 mb-3">
                    <div class="mb-3">
                        <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                            <span class="material-symbols-rounded text-info" style="font-size: 1.35rem;">assessment</span>
                            <span>Official Academic Reports &amp; Documentation Hub</span>
                        </div>
                        <div class="text-secondary small" style="font-size: 0.76rem;">
                            Direct institutional print formats formatted for State Board of Technical Education (SBTE), Kerala compliance.
                        </div>
                    </div>

                    <!-- Printable Academic Reports Grid (Common 3-Column Design) -->
                    <div class="row g-3">
                        
                        <!-- Card 1: Surveys & Attainment -->
                        <div class="col-lg-4 col-md-6">
                            <div class="report-hub-card">
                                <div>
                                    <div class="report-hub-header">
                                        <span class="material-symbols-rounded">emoji_events</span>
                                        <h4 class="report-hub-title">Surveys &amp; Attainment</h4>
                                    </div>
                                    <p class="report-hub-desc">Direct/Indirect CO attainment calculations, SAR survey reports, and 3-2-1 Likert scale feedback.</p>
                                </div>
                                <div class="report-hub-list">
                                    <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">print</span>
                                            <span>Course Exit Survey Report (A4)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">rate_review</span>
                                            <span>Mid-Semester Survey Report (A4)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/classroom/{{ $batchSubject->id }}/attainment-report" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">analytics</span>
                                            <span>NBA Course Attainment Report</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Curriculum & Course Files -->
                        <div class="col-lg-4 col-md-6">
                            <div class="report-hub-card">
                                <div>
                                    <div class="report-hub-header">
                                        <span class="material-symbols-rounded">auto_stories</span>
                                        <h4 class="report-hub-title">Curriculum &amp; Course Files</h4>
                                    </div>
                                    <p class="report-hub-desc">Lesson plan execution tracking, question papers &amp; rubrics, and master course file dossier.</p>
                                </div>
                                <div class="report-hub-list">
                                    <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">calendar_month</span>
                                            <span>Lesson Plan Report (A4)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/classroom/{{ $batchSubject->id }}/course-file/print" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">folder_open</span>
                                            <span>Comprehensive Course File (2021)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/classroom/{{ $batchSubject->id }}/assignment-print/CO1" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">assignment</span>
                                            <span>Assignment QP &amp; Rubrics Report</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    @if(!empty($drawingCourseFile->syllabus_pdf_path))
                                        <a href="{{ $drawingCourseFile->syllabus_pdf_path }}" target="_blank" class="report-hub-btn">
                                            <span class="hub-left">
                                                <span class="material-symbols-rounded hub-icon text-danger">picture_as_pdf</span>
                                                <span>Course Syllabus Document (PDF)</span>
                                            </span>
                                            <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                        </a>
                                    @else
                                        <button type="button" class="report-hub-btn" data-bs-toggle="modal" data-bs-target="#modalUploadSyllabus">
                                            <span class="hub-left">
                                                <span class="material-symbols-rounded hub-icon text-danger">cloud_upload</span>
                                                <span>Upload Syllabus PDF</span>
                                            </span>
                                            <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Evaluation Marksheets -->
                        <div class="col-lg-4 col-md-6">
                            <div class="report-hub-card">
                                <div>
                                    <div class="report-hub-header">
                                        <span class="material-symbols-rounded">fact_check</span>
                                        <h4 class="report-hub-title">Evaluation Marksheets</h4>
                                    </div>
                                    <p class="report-hub-desc">Continuous Internal Evaluation (CIE), Series Exam marksheets, and final End-Semester Results.</p>
                                </div>
                                <div class="report-hub-list">
                                    <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/sheets" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">draw</span>
                                            <span>Continuous Sheets Register (37.5M)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/tests" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">edit_note</span>
                                            <span>Written Series Tests (15.0M)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <!-- Highlighted Consolidated Mark Report (Matching image) -->
                                    <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="report-hub-btn hub-btn-emerald">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">badge</span>
                                            <span>Consolidated Mark Report (75M)</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">grade</span>
                                            <span>Internal Mark Report</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                    <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" target="_blank" class="report-hub-btn">
                                        <span class="hub-left">
                                            <span class="material-symbols-rounded hub-icon">how_to_reg</span>
                                            <span>Institutional Attendance Log</span>
                                        </span>
                                        <span class="material-symbols-rounded hub-arrow">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal: Manage Drawing Sheets -->
    <div class="modal fade" id="modalManageSheets" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-white border-0 shadow-lg text-dark">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fs-6 fw-bold text-dark"><i class="fa-solid fa-pen-ruler me-1.5 text-primary"></i>Configure Drawing Sheets ({{ $batchSubject->subject_name }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small mb-3">Add or edit the drawing sheets evaluated for this course. Click "Save Configuration" to update.</p>
                    <div id="sheetsListContainer" class="space-y-2">
                        @foreach($drawingCourseFile->parsed_sheets ?? [] as $idx => $sh)
                            <div class="d-flex align-items-center gap-2 mb-2 sheet-config-row">
                                <input type="text" class="form-control form-control-sm bg-white text-dark border cfg-sheet-no" value="{{ $sh['sheet_no'] }}" style="width: 100px;">
                                <input type="text" class="form-control form-control-sm bg-white text-dark border cfg-sheet-title flex-grow-1" value="{{ $sh['title'] }}" placeholder="Sheet Title">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.sheet-config-row').remove()"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-2" onclick="addNewSheetRow()">
                        <i class="fa-solid fa-plus me-1"></i> Add Another Sheet
                    </button>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold" onclick="saveSheetsConfig()">Save Configuration</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Upload Syllabus PDF -->
    <div class="modal fade" id="modalUploadSyllabus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-white border-0 shadow-lg text-dark">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fs-6 fw-bold text-dark"><i class="fa-solid fa-cloud-arrow-up me-1.5 text-danger"></i>Upload Syllabus PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadSyllabusForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Select Syllabus PDF Document</label>
                            <input type="file" class="form-control form-control-sm bg-white text-dark border" name="syllabus_file" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm fw-bold" id="uploadBtn">Upload Syllabus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const subjectId = {{ $batchSubject->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const parsedSheets = @json($drawingCourseFile->parsed_sheets ?? []);
        let activeSheetNo = "{{ $drawingCourseFile->parsed_sheets[0]['sheet_no'] ?? 'Sheet 1' }}";

        // Client-side in-memory sheet evaluation store: { regNo: { sheetNo: { timely, appearance, total, is_absent, remarks } } }
        const sheetStore = {};

        // Initialize store from server data
        @foreach($studentResults as $r)
            sheetStore["{{ $r['reg_no'] }}"] = {};
            @foreach($drawingCourseFile->parsed_sheets ?? [] as $sh)
                @php
                    $shData = $r['sheets_detail']->get($sh['sheet_no']);
                @endphp
                @if($shData)
                    sheetStore["{{ $r['reg_no'] }}"]["{{ $sh['sheet_no'] }}"] = {
                        timely_completion: {{ $shData->timely_completion !== null ? floatval($shData->timely_completion) : 'null' }},
                        appearance_organization: {{ $shData->appearance_organization !== null ? floatval($shData->appearance_organization) : 'null' }},
                        total_score_100: {{ floatval($shData->total_score_100) }},
                        is_absent: {{ $shData->is_absent ? 'true' : 'false' }},
                        remarks: {!! json_encode($shData->remarks ?? '') !!}
                    };
                @endif
            @endforeach
        @endforeach

        // Subview Mode (Single Sheet vs All Sheets Matrix)
        function setSheetViewMode(mode) {
            document.getElementById('btnViewSingleSheet').classList.toggle('active', mode === 'single');
            document.getElementById('btnViewAllSheets').classList.toggle('active', mode === 'all');
            document.getElementById('subviewSingleSheetContainer').style.display = mode === 'single' ? '' : 'none';
            document.getElementById('subviewAllSheetsContainer').style.display = mode === 'all' ? '' : 'none';
            document.getElementById('btnSaveSheetActive').style.display = mode === 'single' ? '' : 'none';
        }

        // Filter Consolidated CIA Table
        function filterCiaTable() {
            const query = document.getElementById('ciaSearchInput').value.toLowerCase().trim();
            document.querySelectorAll('#consolidatedCiaTable tbody tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }

        // Live Real-Time Calculation for Consolidated CIA Table
        function recalculateRow(inputEl) {
            const row = inputEl.closest('.cia-row');
            if (!row) return;

            const contInput = row.querySelector('.input-continuous');
            const openInput = row.querySelector('.input-openended');
            const attInput  = row.querySelector('.input-attendance');
            const eseInput  = row.querySelector('.input-ese');
            const seriesVal = parseFloat(row.querySelector('.val-series').innerText) || 0;

            const continuous = Math.min(37.5, Math.max(0, parseFloat(contInput.value) || 0));
            const openEnded  = Math.min(7.5, Math.max(0, parseFloat(openInput.value) || 0));
            const attendance = Math.min(15.0, Math.max(0, parseFloat(attInput.value) || 0));
            const ese        = eseInput.value !== '' ? Math.min(50.0, Math.max(0, parseFloat(eseInput.value) || 0)) : null;

            // Total CIA (Strict SBTE Kerala Max 75.0 - Whole Number)
            const totalCia = Math.min(75, Math.round(continuous + openEnded + seriesVal + attendance));
            row.querySelector('.val-total-cia').innerText = totalCia;

            // Pass / Fail badge (Min 30 for 75 CIA)
            const badge = row.querySelector('.badge-status');
            if (totalCia >= 30) {
                badge.className = 'badge bg-success badge-status';
                badge.innerText = 'PASS';
            } else {
                badge.className = 'badge bg-danger badge-status';
                badge.innerText = 'FAIL';
            }

            // Grand Total (Total CIA + ESE - Whole Number)
            const grandTotal = ese !== null ? Math.round(totalCia + ese) : totalCia;
            row.querySelector('.val-grand-total').innerText = grandTotal;

            showSavingIndicator('ready');
        }

        // Save Consolidated CIA Fast (Hub for 37.5 Continuous Override, 7.5 Open Ended, Attendance, ESE)
        async function saveConsolidatedCiaFast() {
            showSavingIndicator('saving');
            const records = [];

            document.querySelectorAll('.cia-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const contVal = row.querySelector('.input-continuous').value.trim();
                const openVal = row.querySelector('.input-openended').value.trim();
                const attVal  = row.querySelector('.input-attendance').value.trim();
                const eseVal  = row.querySelector('.input-ese').value.trim();
                const calcAtt = row.querySelector('.input-attendance').getAttribute('data-calculated') || '';

                const isAttOverridden = (attVal !== '' && calcAtt !== '' && Math.abs(parseFloat(attVal) - parseFloat(calcAtt)) > 0.001);

                records.push({
                    reg_no: regNo,
                    continuous_override: contVal !== '' ? parseFloat(contVal) : null,
                    open_ended_mark: openVal !== '' ? parseFloat(openVal) : 0,
                    attendance_mark: attVal !== '' ? parseFloat(attVal) : null,
                    attendance_override: isAttOverridden ? parseFloat(attVal) : null,
                    ese_mark: eseVal !== '' ? parseFloat(eseVal) : null,
                });
            });

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/fast-cia/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ records })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator('saved');
                } else {
                    alert(data.message || 'Error saving marks');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving marks.');
                showSavingIndicator('ready');
            }
        }

        // Live Real-Time Calculation for Series Fast Table
        function calcSeriesRow(inputEl) {
            const row = inputEl.closest('.series-fast-row');
            if (!row) return;

            const t1Input = row.querySelector('.input-t1');
            const t2Input = row.querySelector('.input-t2');
            const t1Absent = row.querySelector('.chk-t1-absent').checked;
            const t2Absent = row.querySelector('.chk-t2-absent').checked;

            const t1 = (!t1Absent && t1Input.value !== '') ? Math.min(15.0, Math.max(0, parseFloat(t1Input.value) || 0)) : null;
            const t2 = (!t2Absent && t2Input.value !== '') ? Math.min(15.0, Math.max(0, parseFloat(t2Input.value) || 0)) : null;

            let avg = 0;
            if (t1 !== null && t2 !== null) {
                avg = Math.round(((t1 + t2) / 2.0) * 100) / 100;
            } else if (t1 !== null) {
                avg = t1;
            } else if (t2 !== null) {
                avg = t2;
            }

            row.querySelector('.val-series-avg').innerText = avg;

            // Sync with Consolidated CIA Register
            const regNo = row.getAttribute('data-reg-no');
            const ciaRow = document.querySelector(`.cia-row[data-reg-no="${regNo}"]`);
            if (ciaRow) {
                ciaRow.querySelector('.val-series').innerText = avg;
                recalculateRow(ciaRow.querySelector('.input-continuous'));
            }
        }

        function toggleSeriesAbsent(chk, testType) {
            const row = chk.closest('.series-fast-row');
            const input = row.querySelector(`.input-${testType}`);
            if (chk.checked) {
                input.value = '';
                input.disabled = true;
            } else {
                input.disabled = false;
            }
            calcSeriesRow(chk);
        }

        // Save Series Fast (Preserving Meenu M's Test 1 marks safely)
        async function saveSeriesFast() {
            showSavingIndicator('saving');
            const records = [];

            document.querySelectorAll('.series-fast-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const t1Val = row.querySelector('.input-t1').value.trim();
                const t2Val = row.querySelector('.input-t2').value.trim();
                const t1Absent = row.querySelector('.chk-t1-absent').checked;
                const t2Absent = row.querySelector('.chk-t2-absent').checked;

                records.push({
                    reg_no: regNo,
                    t1_mark: t1Val !== '' ? parseFloat(t1Val) : null,
                    t2_mark: t2Val !== '' ? parseFloat(t2Val) : null,
                    t1_absent: t1Absent,
                    t2_absent: t2Absent,
                });
            });

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/series-fast/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ records })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator('saved');
                } else {
                    alert(data.message || 'Error saving series marks');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving series marks.');
                showSavingIndicator('ready');
            }
        }

        // Capture current table inputs into in-memory store
        function captureCurrentSheetTableInputs() {
            document.querySelectorAll('#sheetMarksTable .sheet-eval-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const timelyVal = row.querySelector('.input-timely').value.trim();
                const appVal = row.querySelector('.input-appearance').value.trim();
                const isAbsent = row.querySelector('.chk-absent').checked;
                const remarks = row.querySelector('.input-remarks').value;

                const timely = timelyVal !== '' ? parseFloat(timelyVal) : null;
                const appearance = appVal !== '' ? parseFloat(appVal) : null;
                const total = isAbsent ? 0 : ((timely || 0) + (appearance || 0));

                if (!sheetStore[regNo]) sheetStore[regNo] = {};
                sheetStore[regNo][activeSheetNo] = {
                    timely_completion: timely,
                    appearance_organization: appearance,
                    total_score_100: total,
                    is_absent: isAbsent,
                    remarks: remarks
                };
            });
        }

        // Switch Sheet Pill with complete dynamic data binding
        function switchSheet(sheetNo, pillEl) {
            // First capture current sheet changes into store
            captureCurrentSheetTableInputs();

            // Update active pill
            document.querySelectorAll('.sheet-pill').forEach(p => p.classList.remove('active'));
            pillEl.classList.add('active');
            activeSheetNo = sheetNo;

            // Update banner
            document.getElementById('activeSheetLabel').innerText = sheetNo;
            document.getElementById('activeSheetTitle').innerText = pillEl.getAttribute('data-sheet-title') || sheetNo;
            document.getElementById('activeSheetModuleTag').innerText = pillEl.getAttribute('data-sheet-module') || 'Module 1';
            document.getElementById('activeSheetCoTag').innerText = pillEl.getAttribute('data-sheet-co') || 'CO1';

            // Populate table rows with selected sheet's stored values
            document.querySelectorAll('#sheetMarksTable .sheet-eval-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const shData = (sheetStore[regNo] && sheetStore[regNo][sheetNo]) ? sheetStore[regNo][sheetNo] : null;

                const timelyInput = row.querySelector('.input-timely');
                const appInput = row.querySelector('.input-appearance');
                const absentChk = row.querySelector('.chk-absent');
                const totalSpan = row.querySelector('.val-sheet-total');
                const remarksInput = row.querySelector('.input-remarks');

                if (shData) {
                    absentChk.checked = Boolean(shData.is_absent);
                    timelyInput.disabled = absentChk.checked;
                    appInput.disabled = absentChk.checked;
                    timelyInput.value = (shData.timely_completion !== null && !shData.is_absent) ? shData.timely_completion : '';
                    appInput.value = (shData.appearance_organization !== null && !shData.is_absent) ? shData.appearance_organization : '';
                    totalSpan.innerText = shData.is_absent ? 'ABS' : (shData.total_score_100 || 0);
                    remarksInput.value = shData.remarks || '';
                } else {
                    absentChk.checked = false;
                    timelyInput.disabled = false;
                    appInput.disabled = false;
                    timelyInput.value = '';
                    appInput.value = '';
                    totalSpan.innerText = '0';
                    remarksInput.value = '';
                }
            });
        }

        function calcSheetRow(inputEl) {
            const row = inputEl.closest('.sheet-eval-row');
            const timely = Math.min(50, Math.max(0, parseFloat(row.querySelector('.input-timely').value) || 0));
            const app = Math.min(50, Math.max(0, parseFloat(row.querySelector('.input-appearance').value) || 0));
            const absent = row.querySelector('.chk-absent').checked;
            const total = absent ? 0 : (timely + app);
            row.querySelector('.val-sheet-total').innerText = total;

            // Sync matrix table in real-time
            const regNo = row.getAttribute('data-reg-no');
            updateMatrixRowForStudent(regNo, activeSheetNo, total, absent);
        }

        function toggleSheetAbsent(chk) {
            const row = chk.closest('.sheet-eval-row');
            const timelyInput = row.querySelector('.input-timely');
            const appInput = row.querySelector('.input-appearance');
            if (chk.checked) {
                timelyInput.disabled = true;
                appInput.disabled = true;
                row.querySelector('.val-sheet-total').innerText = 'ABS';
            } else {
                timelyInput.disabled = false;
                appInput.disabled = false;
                calcSheetRow(chk);
            }
            const regNo = row.getAttribute('data-reg-no');
            updateMatrixRowForStudent(regNo, activeSheetNo, 0, chk.checked);
        }

        function updateMatrixRowForStudent(regNo, sheetNo, totalScore, isAbsent) {
            const mRow = document.querySelector(`#allSheetsMatrixTable tr.matrix-row[data-reg-no="${regNo}"]`);
            if (!mRow) return;

            const cell = mRow.querySelector(`td.matrix-cell[data-sheet-no="${sheetNo}"]`);
            if (cell) {
                if (isAbsent) {
                    cell.innerHTML = '<span class="text-danger fw-bold">ABS</span>';
                } else if (totalScore > 0) {
                    cell.innerHTML = `<span class="fw-semibold">${totalScore}</span>`;
                } else {
                    cell.innerHTML = '<span class="text-muted">-</span>';
                }
            }
        }

        // Save Active Sheet Marks to Database
        async function saveActiveSheetMarks() {
            showSavingIndicator('saving');
            captureCurrentSheetTableInputs();

            const evaluations = [];
            document.querySelectorAll('#sheetMarksTable .sheet-eval-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const sh = sheetStore[regNo] ? sheetStore[regNo][activeSheetNo] : null;
                if (sh) {
                    evaluations.push({
                        reg_no: regNo,
                        timely_completion: sh.timely_completion,
                        appearance_organization: sh.appearance_organization,
                        is_absent: sh.is_absent,
                        remarks: sh.remarks
                    });
                }
            });

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/sheets/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ sheet_no: activeSheetNo, evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator('saved');
                } else {
                    alert(data.message || 'Error saving sheet marks');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving sheet marks.');
                showSavingIndicator('ready');
            }
        }

        // Apply Grouped Sheets Average to Continuous CIA (37.5M)
        async function applyGroupedSheetsAverage() {
            if (!confirm('Apply the calculated drawing sheet averages to the Continuous Assessment (37.5 Marks) column for all students?')) {
                return;
            }

            showSavingIndicator('saving');
            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/sheets-bulk-average/apply`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error applying averages');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error applying sheet averages.');
                showSavingIndicator('ready');
            }
        }

        // Attendance Overrides Save
        async function saveAttendanceOverrides() {
            showSavingIndicator('saving');
            const records = [];
            document.querySelectorAll('.att-row').forEach(row => {
                const regNo = row.getAttribute('data-reg-no');
                const overrideVal = row.querySelector('.input-att-override').value.trim();
                records.push({
                    reg_no: regNo,
                    override_mark: overrideVal !== '' ? parseFloat(overrideVal) : null,
                });
            });

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/attendance/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ records })
                });
                const data = await res.json();
                if (data.success) {
                    showSavingIndicator('saved');
                    // Sync back to Tab 1
                    records.forEach(r => {
                        const ciaRow = document.querySelector(`.cia-row[data-reg-no="${r.reg_no}"]`);
                        if (ciaRow && r.override_mark !== null) {
                            const attInput = ciaRow.querySelector('.input-attendance');
                            attInput.value = r.override_mark;
                            attInput.classList.add('is-overridden');
                            recalculateRow(attInput);
                        }
                    });
                } else {
                    alert(data.message || 'Error saving attendance');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving attendance.');
                showSavingIndicator('ready');
            }
        }

        // Lesson Planner Management
        let deletedPlanIds = [];

        function deleteLessonPlanRow(btn) {
            const row = btn.closest('.plan-row');
            if (!row) return;
            const planId = row.getAttribute('data-plan-id');
            if (planId && !planId.startsWith('new_')) {
                deletedPlanIds.push(parseInt(planId));
            }
            row.remove();
        }

        function addNewLessonPlanRow() {
            const tbody = document.getElementById('lessonPlanTableBody');
            if (!tbody) return;
            const rows = tbody.querySelectorAll('.plan-row');
            let nextDay = 1;
            if (rows.length > 0) {
                const lastDayVal = parseInt(rows[rows.length - 1].querySelector('.plan-day').value);
                nextDay = isNaN(lastDayVal) ? (rows.length + 1) : (lastDayVal + 1);
            }

            const tr = document.createElement('tr');
            tr.className = 'plan-row';
            tr.setAttribute('data-plan-id', 'new_' + Date.now());
            tr.innerHTML = `
                <td class="text-center">
                    <input type="number" min="1" class="form-control form-control-sm text-center fw-bold plan-day" value="${nextDay}" style="width: 55px; margin: 0 auto;">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center plan-module" value="Module 1" placeholder="Module">
                </td>
                <td>
                    <textarea rows="1" class="form-control form-control-sm plan-topic" placeholder="Planned topics & exercises..." style="min-width: 200px;"></textarea>
                </td>
                <td class="text-center">
                    <input type="number" min="1" max="10" step="1" class="form-control form-control-sm text-center plan-hours" value="2" style="width: 60px; margin: 0 auto;">
                </td>
                <td class="text-center">
                    <input type="text" list="coSuggestions" class="form-control form-control-sm text-center fw-bold text-primary plan-co" value="CO1" placeholder="CO1">
                </td>
                <td class="text-center">
                    <input type="date" class="form-control form-control-sm plan-proposed-date" value="">
                </td>
                <td class="text-center">
                    <input type="date" class="form-control form-control-sm plan-actual-date" value="">
                </td>
                <td class="text-center">
                    <select class="form-select form-select-sm plan-status">
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pending" selected>Pending</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm p-1" onclick="deleteLessonPlanRow(this)" title="Delete session">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        async function saveLessonPlansFast() {
            showSavingIndicator('saving');
            const plans = [];
            document.querySelectorAll('#lessonPlanTable .plan-row').forEach((row, idx) => {
                const planId = row.getAttribute('data-plan-id');
                const dayVal = row.querySelector('.plan-day').value;
                const moduleVal = row.querySelector('.plan-module').value;
                const topicVal = row.querySelector('.plan-topic').value;
                const hoursVal = row.querySelector('.plan-hours').value;
                const coVal = row.querySelector('.plan-co').value;
                const proposedVal = row.querySelector('.plan-proposed-date').value;
                const actualVal = row.querySelector('.plan-actual-date').value;
                const statusVal = row.querySelector('.plan-status').value;

                plans.push({
                    id: planId && !planId.startsWith('new_') ? planId : null,
                    day_no: dayVal ? parseInt(dayVal) : (idx + 1),
                    remarks: moduleVal || null,
                    topic_content: topicVal || 'Drawing Exercise',
                    allocated_hours: hoursVal ? parseInt(hoursVal) : 2,
                    co_id: coVal || 'CO1',
                    proposed_date: proposedVal || null,
                    actual_date: actualVal || null,
                    status: statusVal || 'Pending'
                });
            });

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/lesson-plans/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ plans, deleted_ids: deletedPlanIds })
                });
                const data = await res.json();
                if (data.success) {
                    deletedPlanIds = [];
                    showSavingIndicator('saved');
                } else {
                    alert(data.message || 'Error saving lesson plan');
                    showSavingIndicator('ready');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving lesson plan.');
                showSavingIndicator('ready');
            }
        }

        // Manage Sheets Configuration
        function addNewSheetRow() {
            const count = document.querySelectorAll('.sheet-config-row').length + 1;
            const rowHtml = `
                <div class="d-flex align-items-center gap-2 mb-2 sheet-config-row">
                    <input type="text" class="form-control form-control-sm bg-white text-dark border cfg-sheet-no" value="Sheet ${count}" style="width: 100px;">
                    <input type="text" class="form-control form-control-sm bg-white text-dark border cfg-sheet-title flex-grow-1" value="" placeholder="Sheet Title">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.sheet-config-row').remove()"><i class="fa-solid fa-trash"></i></button>
                </div>
            `;
            document.getElementById('sheetsListContainer').insertAdjacentHTML('beforeend', rowHtml);
        }

        async function saveSheetsConfig() {
            const sheets = [];
            document.querySelectorAll('.sheet-config-row').forEach((row, idx) => {
                const sheetNo = row.querySelector('.cfg-sheet-no').value.trim();
                const title = row.querySelector('.cfg-sheet-title').value.trim() || `Drawing Sheet ${idx + 1}`;
                if (sheetNo) {
                    sheets.push({
                        sheet_no: sheetNo,
                        module: `Module ${Math.min(4, Math.ceil((idx + 1) / 2))}`,
                        title: title,
                        co_id: `CO${Math.min(4, Math.ceil((idx + 1) / 2))}`,
                        max_timely: 25,
                        max_appearance: 25,
                    });
                }
            });

            if (sheets.length === 0) {
                alert('Please provide at least one sheet.');
                return;
            }

            try {
                const res = await fetch(`/r21/classroom/drawing/${subjectId}/sheets-config/save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ sheets })
                });
                const data = await res.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error saving sheets configuration');
                }
            } catch (err) {
                console.error(err);
                alert('Network error saving sheets configuration.');
            }
        }

        // Syllabus Upload
        const uploadForm = document.getElementById('uploadSyllabusForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('uploadBtn');
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading...';
                btn.disabled = true;

                const formData = new FormData(this);
                fetch(`/r21/classroom/drawing/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Syllabus PDF uploaded successfully!');
                        location.reload();
                    } else {
                        alert('Upload failed: ' + (data.message || 'Unknown error'));
                        btn.innerHTML = 'Upload Syllabus';
                        btn.disabled = false;
                    }
                })
                .catch(() => {
                    alert('An error occurred during upload.');
                    btn.innerHTML = 'Upload Syllabus';
                    btn.disabled = false;
                });
            });
        }

        function showSavingIndicator(status) {
            const ind = document.getElementById('saveStatusIndicator');
            if (!ind) return;
            if (status === 'saving') {
                ind.style.display = 'inline-flex';
                ind.className = 'autosave-pill autosave-saving';
                ind.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
            } else if (status === 'saved') {
                ind.style.display = 'inline-flex';
                ind.className = 'autosave-pill autosave-ready';
                ind.innerHTML = '<i class="fa-solid fa-check"></i> All Saved';
                setTimeout(() => {
                    ind.style.display = 'none';
                }, 2000);
            } else {
                ind.style.display = 'none';
            }
        }

        // Theme Toggle Engine (Dark Default / Light Switcher)
        function toggleTheme() {
            const isLight = document.body.classList.toggle('light-theme');
            if (isLight) {
                document.documentElement.classList.add('light-theme');
                localStorage.setItem('theme-preference', 'light');
            } else {
                document.documentElement.classList.remove('light-theme');
                localStorage.setItem('theme-preference', 'dark');
            }
            updateThemeToggleUI(isLight);
        }

        function updateThemeToggleUI(isLight) {
            const icon = document.getElementById('themeToggleIcon');
            const text = document.getElementById('themeToggleText');
            const btn = document.getElementById('themeToggleBtn');
            if (icon) {
                icon.innerText = isLight ? 'dark_mode' : 'light_mode';
            }
            if (text) {
                text.innerText = isLight ? 'Dark Mode' : 'Light Mode';
            }
            if (btn) {
                btn.title = isLight ? 'Switch to Dark Mode' : 'Switch to Light Mode';
            }
        }

        // Automatic URL Tab Activation & Theme Init
        window.addEventListener('DOMContentLoaded', () => {
            // Sync theme toggle button UI
            const isLightMode = document.body.classList.contains('light-theme');
            updateThemeToggleUI(isLightMode);

            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab') || window.location.hash.replace('#', '').replace('tab-', '');
            if (tabParam) {
                const map = {
                    'cia': 'tab-cia-link',
                    'sheets': 'tab-cia-link',
                    'sheet': 'tab-cia-link',
                    'series': 'tab-series-link',
                    'tests': 'tab-series-link',
                    'test': 'tab-series-link',
                    'attendance': 'tab-attendance-link',
                    'att': 'tab-attendance-link',
                    'planner': 'tab-lessonplan-link',
                    'lessonplan': 'tab-lessonplan-link',
                    'reports': 'tab-reports-link'
                };
                const btnId = map[tabParam.toLowerCase()];
                if (btnId && document.getElementById(btnId)) {
                    bootstrap.Tab.getOrCreateInstance(document.getElementById(btnId)).show();
                }
            }
        });
    </script>
</body>
</html>
