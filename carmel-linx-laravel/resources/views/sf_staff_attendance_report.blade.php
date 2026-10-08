<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SF Staff Biometric Attendance Ledger | Carmel Polytechnic College</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-page: #090d16;
            --bg-card: #0f172a;
            --bg-surface: #141e33;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-focus: #3b82f6;
            
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;

            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-soft: rgba(37, 99, 235, 0.12);
            --primary-border: rgba(59, 130, 246, 0.35);

            --success: #10b981;
            --success-soft: rgba(16, 185, 129, 0.12);
            --success-border: rgba(16, 185, 129, 0.3);

            --warning: #f59e0b;
            --warning-soft: rgba(245, 158, 11, 0.12);
            --warning-border: rgba(245, 158, 11, 0.3);

            --danger: #ef4444;
            --danger-soft: rgba(239, 68, 68, 0.12);
            --danger-border: rgba(239, 68, 68, 0.3);

            --purple: #8b5cf6;
            --purple-soft: rgba(139, 92, 246, 0.12);
            --purple-border: rgba(139, 92, 246, 0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 14px 18px;
            font-size: 13px;
            line-height: 1.45;
        }

        /* -------------------------------------------------------------
           1. COMPACT UNIFIED TOP HEADER BAR
        ------------------------------------------------------------- */
        .app-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 10px 16px;
            margin-bottom: 12px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .brand-title {
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #60a5fa;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Segmented Navigation Tabs */
        .nav-segmented {
            display: inline-flex;
            background: rgba(0, 0, 0, 0.35);
            padding: 3px;
            border-radius: 9px;
            border: 1px solid var(--border-subtle);
            gap: 2px;
        }

        .nav-segment {
            padding: 6px 12px;
            border-radius: 7px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-segment:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-segment.active {
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        }

        .nav-counter {
            background: rgba(255, 255, 255, 0.18);
            color: inherit;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 0.65rem;
            font-weight: 800;
        }

        /* Action Buttons Group */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn {
            height: 32px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 0.725rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover { background: var(--primary-hover); }

        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            border-color: var(--border-subtle);
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-geofence {
            background: var(--success-soft);
            color: #34d399;
            border-color: var(--success-border);
        }
        .btn-geofence:hover {
            background: rgba(16, 185, 129, 0.22);
            color: #6ee7b7;
        }

        /* -------------------------------------------------------------
           2. SLEEK COMPACT METRIC BAR (Replaces huge scattered cards)
        ------------------------------------------------------------- */
        .metric-bar {
            display: flex;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 8px 12px;
            margin-bottom: 12px;
            gap: 8px;
            overflow-x: auto;
        }

        .metric-chip {
            flex: 1;
            min-width: 140px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px;
            border-radius: 7px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .metric-chip-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .chip-blue   { background: var(--primary-soft); color: #60a5fa; }
        .chip-green  { background: var(--success-soft); color: #34d399; }
        .chip-amber  { background: var(--warning-soft); color: #fbbf24; }
        .chip-purple { background: var(--purple-soft);  color: #c084fc; }

        .metric-chip-data {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .metric-chip-val {
            font-size: 1.05rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .metric-chip-lbl {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }

        /* -------------------------------------------------------------
           3. TABLE PANEL WITH INTEGRATED TOOLBAR
        ------------------------------------------------------------- */
        .table-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: rgba(15, 23, 42, 0.6);
            border-bottom: 1px solid var(--border-subtle);
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .input-compact {
            height: 30px;
            padding: 0 9px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 0.75rem;
            outline: none;
            transition: border-color 0.15s;
        }
        .input-compact:focus {
            border-color: var(--border-focus);
        }

        .quick-pill {
            height: 26px;
            padding: 0 8px;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s;
        }
        .quick-pill:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.09);
        }
        .quick-pill.active {
            background: var(--primary-soft);
            color: #60a5fa;
            border-color: var(--primary-border);
        }

        /* -------------------------------------------------------------
           4. HIGH-DENSITY PROFESSIONAL DATA TABLE
        ------------------------------------------------------------- */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            text-align: left;
        }

        thead th {
            background: #090f1d;
            padding: 8px 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        tbody td {
            padding: 8px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 6px;
            border-radius: 5px;
            font-size: 0.65rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .badge-success { background: var(--success-soft); color: #34d399; border: 1px solid var(--success-border); }
        .badge-warning { background: var(--warning-soft); color: #fbbf24; border: 1px solid var(--warning-border); }
        .badge-danger  { background: var(--danger-soft);  color: #f87171; border: 1px solid var(--danger-border); }
        .badge-info    { background: var(--primary-soft); color: #60a5fa; border: 1px solid var(--primary-border); }
        .badge-purple  { background: var(--purple-soft);  color: #c084fc; border: 1px solid var(--purple-border); }
        .badge-neutral { background: rgba(255, 255, 255, 0.06); color: var(--text-secondary); border: 1px solid rgba(255, 255, 255, 0.1); }

        /* Micro-Avatars */
        .avatar-sm {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            object-fit: cover;
            border: 1.5px solid var(--border-subtle);
            cursor: pointer;
            transition: transform 0.15s;
        }
        .avatar-sm:hover { transform: scale(1.15); z-index: 5; }

        .time-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 0.775rem;
        }

        /* Micro Action Buttons */
        .btn-table {
            height: 24px;
            padding: 0 7px;
            border-radius: 5px;
            font-size: 0.65rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
        }
        .btn-table-primary {
            background: var(--primary-soft);
            color: #60a5fa;
            border-color: var(--primary-border);
        }
        .btn-table-primary:hover {
            background: var(--primary);
            color: #fff;
        }
        .btn-table-danger {
            background: var(--danger-soft);
            color: #f87171;
            border-color: var(--danger-border);
        }
        .btn-table-danger:hover {
            background: var(--danger);
            color: #fff;
        }

        /* -------------------------------------------------------------
           5. INDIVIDUAL REPORT SLEEK COMPACT PROFILE BAR
        ------------------------------------------------------------- */
        .ind-profile-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(20, 30, 51, 0.8), rgba(15, 23, 42, 0.8));
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 12px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .ind-user-meta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ind-avatar-box {
            width: 44px;
            height: 44px;
            border-radius: 9px;
            overflow: hidden;
            border: 2px solid #34d399;
            flex-shrink: 0;
            background: #000;
        }
        .ind-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .ind-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }

        .ind-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* -------------------------------------------------------------
           6. MODAL OVERLAYS
        ------------------------------------------------------------- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            width: 100%;
            max-width: 640px;
            padding: 18px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-subtle);
        }
        .modal-head h3 {
            font-size: 0.95rem;
            font-weight: 800;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
        }
        .modal-close-btn:hover { color: #fff; }

        /* -------------------------------------------------------------
           7. CLEAN OFFICIAL PRINT FORMATS (CARMEL POLYTECHNIC COLLEGE)
        ------------------------------------------------------------- */
        .print-only { display: none; }

        @media print {
            .app-header, .metric-bar, .table-toolbar, .col-actions, .screen-only, .btn-table, .modal-overlay {
                display: none !important;
            }
            body {
                background: #fff !important;
                color: #000 !important;
                padding: 0 !important;
                font-family: Arial, sans-serif !important;
            }
            .print-only { display: block !important; }
            .print-page { page-break-after: always; }
            .table-panel {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                background: #fff !important;
                border-radius: 0 !important;
            }
            table {
                width: 100% !important;
                color: #000 !important;
                border-collapse: collapse !important;
            }
            thead th {
                background: #f1f5f9 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
                padding: 6px 8px !important;
                font-size: 8pt !important;
            }
            tbody td {
                color: #000 !important;
                border: 1px solid #000 !important;
                padding: 5px 8px !important;
                font-size: 8pt !important;
            }
            .badge {
                border: 1px solid #666 !important;
                color: #000 !important;
                background: #f8fafc !important;
            }
        }
    </style>
</head>
<body>

    <!-- ========================================================================================= -->
    <!-- 1. COMPACT UNIFIED APP HEADER (SCREEN VIEW)                                               -->
    <!-- ========================================================================================= -->
    <header class="app-header screen-only">
        <!-- Brand Title -->
        <div class="brand-box">
            <div class="brand-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <div class="brand-title">Carmel Polytechnic College</div>
                <div class="brand-sub">SF Staff Biometric Face Punch &amp; Attendance Reports</div>
            </div>
        </div>

        <!-- Segmented Navigation Tabs -->
        <nav class="nav-segmented" aria-label="Attendance Reports Navigation">
            <a href="/sf-attendance/attendance-report?tab=daily&date={{ $selectedDate }}" 
               class="nav-segment {{ $activeTab === 'daily' ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-day"></i>
                <span>Daily Log</span>
                <span class="nav-counter">{{ date('d M', strtotime($selectedDate)) }}</span>
            </a>

            <a href="/sf-attendance/attendance-report?tab=monthly&month={{ $selectedMonth }}" 
               class="nav-segment {{ $activeTab === 'monthly' ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Monthly Ledger</span>
                <span class="nav-counter">{{ date('M Y', strtotime($selectedMonth . '-01')) }}</span>
            </a>

            <a href="/sf-attendance/attendance-report?tab=individual&staff_id={{ $selectedStaffId }}&period={{ $individualPeriod }}&individual_month={{ $individualMonth }}" 
               class="nav-segment {{ $activeTab === 'individual' ? 'active' : '' }}">
                <i class="fa-solid fa-user-clock"></i>
                <span>Individual Report</span>
            </a>

            <a href="/sf-attendance/attendance-report?tab=registered" 
               class="nav-segment {{ $activeTab === 'registered' ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Registered Users</span>
                <span class="nav-counter">{{ count($registeredStaff) }}</span>
            </a>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <a href="/sf-attendance/geofence-setup" class="btn btn-geofence" title="Configure campus GPS geofence">
                <i class="fa-solid fa-location-crosshairs"></i>
                <span>Geofence</span>
            </a>

            <button type="button" class="btn btn-outline" onclick="forceFreshReload()" title="Force Live Sync">
                <i class="fa-solid fa-arrows-rotate" id="refreshSpinIcon"></i>
                <span>Live Sync</span>
            </button>

            <button type="button" class="btn btn-primary" onclick="window.print()" title="Print Current View">
                <i class="fa-solid fa-print"></i>
                <span>Print</span>
            </button>

            <button type="button" class="btn btn-outline" onclick="goBackToDashboard()" title="Return to Dashboard">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Exit</span>
            </button>
        </div>
    </header>


    <!-- ========================================================================================= -->
    <!-- 2. REPORT VIEW 1: DAILY PUNCH LOG                                                         -->
    <!-- ========================================================================================= -->
    @if($activeTab === 'daily')
        <div class="screen-only">
            <!-- Compact Metric Bar -->
            <div class="metric-bar">
                <div class="metric-chip">
                    <div class="metric-chip-icon chip-blue"><i class="fa-solid fa-user-check"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $dailyTotalPresent }}</span>
                        <span class="metric-chip-lbl">Present Today</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-green"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $dailyInside }}</span>
                        <span class="metric-chip-lbl">In Premises</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-amber"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $dailyLate }}</span>
                        <span class="metric-chip-lbl">Late (&gt;9:15 AM)</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-purple"><i class="fa-solid fa-door-open"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $dailyCompleted }}</span>
                        <span class="metric-chip-lbl">Evening Out</span>
                    </div>
                </div>
            </div>

            <!-- Table Panel with Integrated Toolbar -->
            <div class="table-panel">
                <form action="/sf-attendance/attendance-report" method="GET" class="table-toolbar">
                    <input type="hidden" name="tab" value="daily">
                    
                    <!-- Left Toolbar: Date Picker & Quick Jump Pills -->
                    <div class="toolbar-group">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Date:</span>
                            <input type="date" name="date" class="input-compact" value="{{ $selectedDate }}" onchange="this.form.submit()">
                        </div>

                        @php
                            $prevDay = date('Y-m-d', strtotime($selectedDate . ' -1 day'));
                            $nextDay = date('Y-m-d', strtotime($selectedDate . ' +1 day'));
                        @endphp
                        <a href="/sf-attendance/attendance-report?tab=daily&date={{ $prevDay }}" class="quick-pill" title="Previous Day">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                        <a href="/sf-attendance/attendance-report?tab=daily&date={{ date('Y-m-d') }}" class="quick-pill {{ $selectedDate === date('Y-m-d') ? 'active' : '' }}">
                            Today
                        </a>
                        <a href="/sf-attendance/attendance-report?tab=daily&date={{ date('Y-m-d', strtotime('-1 day')) }}" class="quick-pill {{ $selectedDate === date('Y-m-d', strtotime('-1 day')) ? 'active' : '' }}">
                            Yesterday
                        </a>
                        <a href="/sf-attendance/attendance-report?tab=daily&date={{ $nextDay }}" class="quick-pill" title="Next Day">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                    <!-- Right Toolbar: Search & Premises Filter -->
                    <div class="toolbar-group">
                        <select name="premises_status" class="input-compact" onchange="this.form.submit()">
                            <option value="">All Locations</option>
                            <option value="INSIDE_PREMISES" {{ $premisesFilter === 'INSIDE_PREMISES' ? 'selected' : '' }}>Inside Campus Only</option>
                            <option value="OUTSIDE_PREMISES" {{ $premisesFilter === 'OUTSIDE_PREMISES' ? 'selected' : '' }}>Outside Campus Only</option>
                        </select>

                        <div style="position: relative;">
                            <input type="text" name="daily_search" class="input-compact" placeholder="Search staff name / ID..." value="{{ $dailySearch }}" style="padding-left: 24px; width: 170px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 8px; top: 8px; color: var(--text-muted); font-size: 0.7rem;"></i>
                        </div>

                        <button type="submit" class="btn btn-outline" style="height: 30px; padding: 0 10px;">
                            <i class="fa-solid fa-filter"></i> Apply
                        </button>
                    </div>
                </form>

                <!-- Daily Punches Table -->
                @php
                    $regMap = collect($registeredStaff ?? [])->keyBy('staff_id');
                @endphp
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 35px; text-align: center;">#</th>
                                <th>Staff Member &amp; ID</th>
                                <th>Dept</th>
                                <th style="text-align: center;">Audit Photos</th>
                                <th>Morning IN</th>
                                <th>Evening OUT</th>
                                <th>Campus Duration</th>
                                <th>Premises (IN / OUT)</th>
                                <th>GPS Dist</th>
                                <th>Status</th>
                                <th class="col-actions" style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyPunches as $idx => $p)
                                @php
                                    $campusHours = '--';
                                    if ($p->in_time && $p->out_time) {
                                        $diffMinutes = round(abs(strtotime($p->out_time) - strtotime($p->in_time)) / 60);
                                        $campusHours = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';
                                    } elseif ($p->in_time) {
                                        $campusHours = 'Active';
                                    }

                                    $regObj = $regMap->get($p->staff_id);
                                    $regFace = $regObj->photo_url ?? null;
                                    $inSnap = $p->in_snapshot_url ?? null;
                                    $outSnap = $p->out_snapshot_url ?? null;

                                    $inDist = $p->in_gps_distance_meters !== null ? $p->in_gps_distance_meters . 'm' : '--';
                                    $outDist = $p->out_gps_distance_meters !== null ? $p->out_gps_distance_meters . 'm' : '--';
                                @endphp
                                <tr id="row-punch-{{ $p->id }}">
                                    <td style="text-align: center; color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">{{ $p->staff_name }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted); font-family: monospace;">{{ $p->staff_id }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-purple">{{ $regObj->branch ?? 'SF' }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                                            @if($regFace)
                                                <img src="{{ $regFace }}" alt="REG" class="avatar-sm" style="border-color: #34d399;" title="Registered Face (Click to compare)" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                            @if($inSnap)
                                                <img src="{{ $inSnap }}" alt="IN" class="avatar-sm" style="border-color: #60a5fa;" title="Morning IN Snapshot" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                            @if($outSnap)
                                                <img src="{{ $outSnap }}" alt="OUT" class="avatar-sm" style="border-color: #f87171;" title="Evening OUT Snapshot" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="time-text" style="color: #34d399;">
                                            {{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="time-text" style="color: #f87171;">
                                            {{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-weight: 700; color: #60a5fa;">{{ $campusHours }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}">
                                            IN: {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}
                                        </span>
                                        @if($p->out_time)
                                            <span class="badge {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}" style="margin-left: 3px;">
                                                OUT: {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.7rem; color: var(--text-muted); font-family: monospace;">
                                        {{ $inDist }} / {{ $outDist }}
                                    </td>
                                    <td>
                                        @if(str_contains($p->punch_status ?? '', 'LATE_IN'))
                                            <span class="badge badge-warning">Late In</span>
                                        @elseif(str_contains($p->punch_status ?? '', 'EARLY_IN'))
                                            <span class="badge badge-info">Early In</span>
                                        @else
                                            <span class="badge badge-success">Present</span>
                                        @endif
                                    </td>
                                    <td class="col-actions" style="text-align: center;">
                                        <div style="display: inline-flex; gap: 4px;">
                                            <a href="/sf-attendance/attendance-report?tab=individual&staff_id={{ $p->staff_id }}" class="btn-table btn-table-primary" title="View individual statement">
                                                <i class="fa-solid fa-user"></i> Report
                                            </a>
                                            <button class="btn-table btn-table-danger" title="Delete accidental punch" onclick="deletePunchRecord('{{ $p->id }}', '{{ addslashes($p->staff_name) }}', '{{ date('d M Y', strtotime($p->punch_date)) }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" style="text-align: center; padding: 32px 14px; color: var(--text-muted);">
                                        <i class="fa-regular fa-folder-open" style="font-size: 1.8rem; opacity: 0.35; margin-bottom: 6px; display: block;"></i>
                                        No attendance punches recorded for {{ date('d M Y', strtotime($selectedDate)) }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif


    <!-- ========================================================================================= -->
    <!-- 3. REPORT VIEW 2: MONTHLY LEDGER (ALL STAFF)                                              -->
    <!-- ========================================================================================= -->
    @if($activeTab === 'monthly')
        <div class="screen-only">
            <!-- Compact Metric Bar -->
            <div class="metric-bar">
                <div class="metric-chip">
                    <div class="metric-chip-icon chip-blue"><i class="fa-solid fa-users"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $monthlyActiveStaffCount }}</span>
                        <span class="metric-chip-lbl">Active Staff</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-green"><i class="fa-solid fa-business-time"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $monthlyTotalHoursFormatted }}</span>
                        <span class="metric-chip-lbl">Total Man-Hours</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-amber"><i class="fa-solid fa-user-clock"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $monthlyTotalLateEntries }}</span>
                        <span class="metric-chip-lbl">Late Entries</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-purple"><i class="fa-solid fa-fingerprint"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ $allMonthlyPunches->count() }}</span>
                        <span class="metric-chip-lbl">Punch Records</span>
                    </div>
                </div>
            </div>

            <!-- Table Panel with Integrated Toolbar -->
            <div class="table-panel">
                <form action="/sf-attendance/attendance-report" method="GET" class="table-toolbar">
                    <input type="hidden" name="tab" value="monthly">
                    
                    <!-- Left Toolbar: Month Picker & Quick Jump -->
                    <div class="toolbar-group">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Month:</span>
                            <input type="month" name="month" class="input-compact" value="{{ $selectedMonth }}" onchange="this.form.submit()">
                        </div>

                        @php
                            $prevMonth = date('Y-m', strtotime($selectedMonth . '-01 -1 month'));
                            $currMonth = date('Y-m');
                        @endphp
                        <a href="/sf-attendance/attendance-report?tab=monthly&month={{ $prevMonth }}" class="quick-pill">
                            <i class="fa-solid fa-chevron-left"></i> Prev Month
                        </a>
                        <a href="/sf-attendance/attendance-report?tab=monthly&month={{ $currMonth }}" class="quick-pill {{ $selectedMonth === $currMonth ? 'active' : '' }}">
                            Current Month
                        </a>
                    </div>

                    <!-- Right Toolbar: View Switcher & Search -->
                    <div class="toolbar-group">
                        <select name="monthly_view_type" class="input-compact" onchange="this.form.submit()">
                            <option value="summary" {{ $monthlyViewType === 'summary' ? 'selected' : '' }}>Consolidated Summary Ledger</option>
                            <option value="detailed" {{ $monthlyViewType === 'detailed' ? 'selected' : '' }}>Day-by-Day Punches</option>
                        </select>

                        <div style="position: relative;">
                            <input type="text" name="monthly_search" class="input-compact" placeholder="Search staff name / ID..." value="{{ request('monthly_search') }}" style="padding-left: 24px; width: 170px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 8px; top: 8px; color: var(--text-muted); font-size: 0.7rem;"></i>
                        </div>

                        <button type="submit" class="btn btn-outline" style="height: 30px; padding: 0 10px;">
                            <i class="fa-solid fa-filter"></i> Apply
                        </button>
                    </div>
                </form>

                @if($monthlyViewType === 'summary')
                    <!-- Consolidated Monthly Summary Table -->
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 35px; text-align: center;">#</th>
                                    <th>Staff Member &amp; ID</th>
                                    <th>Dept &amp; Designation</th>
                                    <th style="text-align: center;">Days Present</th>
                                    <th style="text-align: center;">In Premises</th>
                                    <th style="text-align: center;">Late Entries</th>
                                    <th style="text-align: center;">Early Outs</th>
                                    <th style="text-align: center;">Total Hours</th>
                                    <th style="text-align: center;">Avg / Day</th>
                                    <th class="col-actions" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyStaffSummary as $idx => $s)
                                    <tr>
                                        <td style="text-align: center; color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div style="position: relative; display: inline-block; flex-shrink: 0;">
                                                    @if($s->photo_url)
                                                        <img src="{{ $s->photo_url }}" alt="{{ $s->staff_name }}" class="avatar-sm" style="border-color: #34d399;">
                                                    @else
                                                        <div class="avatar-sm" style="display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: var(--text-muted); background: rgba(255,255,255,0.05);">SF</div>
                                                    @endif
                                                    @if(!empty($s->staff_id) && \Illuminate\Support\Facades\Cache::has('user_online_' . $s->staff_id))
                                                        <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: #10b981; border: 2px solid #0f172a; border-radius: 50%; box-shadow: 0 0 0 1px rgba(52, 211, 153, 0.4);" title="Online"></span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="font-weight: 700; color: #fff;">{{ $s->staff_name }}</div>
                                                    <div style="font-size: 0.68rem; color: var(--text-muted); font-family: monospace;">{{ $s->staff_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-purple">{{ $s->branch }}</span>
                                            <span style="color: var(--text-muted); font-size: 0.7rem; margin-left: 3px;">{{ $s->designation }}</span>
                                        </td>
                                        <td style="text-align: center; font-weight: 800; color: {{ $s->days_present > 0 ? '#34d399' : 'var(--text-muted)' }};">
                                            {{ $s->days_present }}
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge badge-success">{{ $s->inside_premises_days }}</span>
                                        </td>
                                        <td style="text-align: center;">
                                            @if($s->late_count > 0)
                                                <span class="badge badge-warning">{{ $s->late_count }}</span>
                                            @else
                                                <span style="color: var(--text-muted);">0</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($s->early_out_count > 0)
                                                <span class="badge badge-danger">{{ $s->early_out_count }}</span>
                                            @else
                                                <span style="color: var(--text-muted);">0</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; font-weight: 800; color: #60a5fa;">
                                            {{ $s->total_hours_formatted }}
                                        </td>
                                        <td style="text-align: center; color: var(--text-secondary); font-weight: 600;">
                                            {{ $s->avg_hours_formatted }}
                                        </td>
                                        <td class="col-actions" style="text-align: center;">
                                            <a href="/sf-attendance/attendance-report?tab=individual&staff_id={{ $s->staff_id }}&period=month&individual_month={{ $selectedMonth }}" class="btn-table btn-table-primary">
                                                <i class="fa-solid fa-file-lines"></i> Statement
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                            No staff attendance recorded for {{ date('F Y', strtotime($selectedMonth . '-01')) }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Detailed Day-by-Day Punches Table -->
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 35px; text-align: center;">#</th>
                                    <th>Date</th>
                                    <th>Staff Member &amp; ID</th>
                                    <th>Morning IN</th>
                                    <th>Evening OUT</th>
                                    <th>Duration</th>
                                    <th>Premises (IN / OUT)</th>
                                    <th>Distance</th>
                                    <th>Status</th>
                                    <th class="col-actions" style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allMonthlyPunches as $idx => $p)
                                    @php
                                        $campusHours = '--';
                                        if ($p->in_time && $p->out_time) {
                                            $diffMinutes = round(abs(strtotime($p->out_time) - strtotime($p->in_time)) / 60);
                                            $campusHours = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';
                                        } elseif ($p->in_time) {
                                            $campusHours = 'Active';
                                        }
                                    @endphp
                                    <tr>
                                        <td style="text-align: center; color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                        <td><strong>{{ date('d M Y (D)', strtotime($p->punch_date)) }}</strong></td>
                                        <td>
                                            <div style="font-weight: 700; color: #fff;">{{ $p->staff_name }}</div>
                                            <div style="font-size: 0.68rem; color: var(--text-muted); font-family: monospace;">{{ $p->staff_id }}</div>
                                        </td>
                                        <td><span class="time-text" style="color:#34d399;">{{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--' }}</span></td>
                                        <td><span class="time-text" style="color:#f87171;">{{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--' }}</span></td>
                                        <td><span style="font-weight: 700; color:#60a5fa;">{{ $campusHours }}</span></td>
                                        <td>
                                            <span class="badge {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}">IN: {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}</span>
                                            @if($p->out_time)
                                                <span class="badge {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}" style="margin-left: 3px;">OUT: {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.7rem; color: var(--text-muted); font-family: monospace;">
                                            {{ $p->in_gps_distance_meters !== null ? $p->in_gps_distance_meters . 'm' : '--' }}
                                        </td>
                                        <td>
                                            @if(str_contains($p->punch_status ?? '', 'LATE_IN'))
                                                <span class="badge badge-warning">Late In</span>
                                            @else
                                                <span class="badge badge-success">Present</span>
                                            @endif
                                        </td>
                                        <td class="col-actions" style="text-align: center;">
                                            <a href="/sf-attendance/attendance-report?tab=individual&staff_id={{ $p->staff_id }}" class="btn-table btn-table-primary">
                                                <i class="fa-solid fa-user"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                            No punches logged for this month.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif


    <!-- ========================================================================================= -->
    <!-- 4. REPORT VIEW 3: USER-WISE INDIVIDUAL REPORT                                             -->
    <!-- ========================================================================================= -->
    @if($activeTab === 'individual')
        <div class="screen-only">
            <!-- Sleek Integrated Profile & KPI Strip -->
            @if($individualStaff)
                <div class="ind-profile-strip">
                    <div class="ind-user-meta">
                        <div class="ind-avatar-box">
                            @if($individualStaff->photo_url)
                                <img src="{{ $individualStaff->photo_url }}" alt="{{ $individualStaff->staff_name }}" onclick="openPhotoModal('{{ $individualStaff->photo_url }}', '{{ addslashes($individualStaff->staff_name) }}')" title="Click to enlarge photo">
                            @else
                                <div style="display:flex; align-items:center; justify-content:center; height:100%; color:var(--text-muted);"><i class="fa-solid fa-user"></i></div>
                            @endif
                        </div>
                        <div>
                            <div class="ind-name">{{ $individualStaff->staff_name }}</div>
                            <div class="ind-sub">
                                <span style="font-family: monospace; color: #93c5fd;">{{ $individualStaff->staff_id }}</span>
                                &bull; <span class="badge badge-purple">{{ $individualStaff->branch }}</span>
                                &bull; <span>{{ $individualStaff->designation }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Metrics for this Individual -->
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <div class="metric-chip" style="min-width: 110px; padding: 3px 8px;">
                            <div class="metric-chip-icon chip-green" style="width:24px; height:24px; font-size:0.7rem;"><i class="fa-solid fa-calendar-check"></i></div>
                            <div class="metric-chip-data">
                                <span class="metric-chip-val" style="font-size:0.95rem;">{{ $individualStats->days_present }}</span>
                                <span class="metric-chip-lbl">Days</span>
                            </div>
                        </div>

                        <div class="metric-chip" style="min-width: 110px; padding: 3px 8px;">
                            <div class="metric-chip-icon chip-blue" style="width:24px; height:24px; font-size:0.7rem;"><i class="fa-solid fa-hourglass-half"></i></div>
                            <div class="metric-chip-data">
                                <span class="metric-chip-val" style="font-size:0.95rem;">{{ $individualStats->total_hours_formatted }}</span>
                                <span class="metric-chip-lbl">Hours</span>
                            </div>
                        </div>

                        <div class="metric-chip" style="min-width: 110px; padding: 3px 8px;">
                            <div class="metric-chip-icon chip-amber" style="width:24px; height:24px; font-size:0.7rem;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div class="metric-chip-data">
                                <span class="metric-chip-val" style="font-size:0.95rem;">{{ $individualStats->late_count }}</span>
                                <span class="metric-chip-lbl">Late</span>
                            </div>
                        </div>

                        <div class="metric-chip" style="min-width: 110px; padding: 3px 8px;">
                            <div class="metric-chip-icon chip-purple" style="width:24px; height:24px; font-size:0.7rem;"><i class="fa-solid fa-location-crosshairs"></i></div>
                            <div class="metric-chip-data">
                                <span class="metric-chip-val" style="font-size:0.95rem;">{{ $individualStats->inside_percentage }}%</span>
                                <span class="metric-chip-lbl">In-Campus</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table Panel with Staff Selector Toolbar -->
            <div class="table-panel">
                <form action="/sf-attendance/attendance-report" method="GET" class="table-toolbar">
                    <input type="hidden" name="tab" value="individual">
                    
                    <!-- Left Toolbar: Staff Selector -->
                    <div class="toolbar-group">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Staff:</span>
                        <select name="staff_id" class="input-compact" style="width: 250px;" onchange="this.form.submit()">
                            @foreach($registeredStaff as $rs)
                                <option value="{{ $rs->staff_id }}" {{ $selectedStaffId === $rs->staff_id ? 'selected' : '' }}>
                                    {{ $rs->staff_name }} ({{ $rs->staff_id }}) &ndash; {{ $rs->branch }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Right Toolbar: Period Selector -->
                    <div class="toolbar-group">
                        <select name="period" class="input-compact" onchange="this.form.submit()">
                            <option value="month" {{ $individualPeriod === 'month' ? 'selected' : '' }}>Monthly Filter</option>
                            <option value="all" {{ $individualPeriod === 'all' ? 'selected' : '' }}>All Time (Lifetime)</option>
                        </select>

                        @if($individualPeriod === 'month')
                            <input type="month" name="individual_month" class="input-compact" value="{{ $individualMonth }}" onchange="this.form.submit()">
                        @endif

                        <button type="submit" class="btn btn-outline" style="height: 30px; padding: 0 10px;">
                            <i class="fa-solid fa-arrow-rotate-right"></i> Load
                        </button>
                    </div>
                </form>

                <!-- Individual Chronological Punches Table -->
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 35px; text-align: center;">#</th>
                                <th>Date &amp; Day</th>
                                <th style="text-align: center;">Face Audit</th>
                                <th>Morning IN</th>
                                <th>Evening OUT</th>
                                <th>Campus Duration</th>
                                <th>Premises (IN / OUT)</th>
                                <th>Distance</th>
                                <th>Status</th>
                                <th class="col-actions" style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($individualPunches as $idx => $p)
                                @php
                                    $campusHours = '--';
                                    if ($p->in_time && $p->out_time) {
                                        $diffMinutes = round(abs(strtotime($p->out_time) - strtotime($p->in_time)) / 60);
                                        $campusHours = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';
                                    } elseif ($p->in_time) {
                                        $campusHours = 'Active';
                                    }

                                    $regFace = $individualStaff->photo_url ?? null;
                                    $inSnap = $p->in_snapshot_url ?? null;
                                    $outSnap = $p->out_snapshot_url ?? null;
                                @endphp
                                <tr id="row-punch-{{ $p->id }}">
                                    <td style="text-align: center; color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">{{ date('d M Y', strtotime($p->punch_date)) }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted);">{{ date('l', strtotime($p->punch_date)) }}</div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                                            @if($regFace)
                                                <img src="{{ $regFace }}" alt="REG" class="avatar-sm" style="border-color: #34d399;" title="Registered Face" onclick="openFaceCompareModal('{{ addslashes($individualStaff->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                            @if($inSnap)
                                                <img src="{{ $inSnap }}" alt="IN" class="avatar-sm" style="border-color: #60a5fa;" title="Morning IN Snapshot" onclick="openFaceCompareModal('{{ addslashes($individualStaff->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                            @if($outSnap)
                                                <img src="{{ $outSnap }}" alt="OUT" class="avatar-sm" style="border-color: #f87171;" title="Evening OUT Snapshot" onclick="openFaceCompareModal('{{ addslashes($individualStaff->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.95 }}')">
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="time-text" style="color: #34d399;">{{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--' }}</span></td>
                                    <td><span class="time-text" style="color: #f87171;">{{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--' }}</span></td>
                                    <td><span style="font-weight: 700; color: #60a5fa;">{{ $campusHours }}</span></td>
                                    <td>
                                        <span class="badge {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}">IN: {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}</span>
                                        @if($p->out_time)
                                            <span class="badge {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'badge-success' : 'badge-danger' }}" style="margin-left: 3px;">OUT: {{ $p->out_premises_status === 'INSIDE_PREMISES' ? 'Inside' : 'Outside' }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.7rem; color: var(--text-muted); font-family: monospace;">
                                        {{ $p->in_gps_distance_meters !== null ? $p->in_gps_distance_meters . 'm' : '--' }}
                                    </td>
                                    <td>
                                        @if(str_contains($p->punch_status ?? '', 'LATE_IN'))
                                            <span class="badge badge-warning">Late In</span>
                                        @else
                                            <span class="badge badge-success">Present</span>
                                        @endif
                                    </td>
                                    <td class="col-actions" style="text-align: center;">
                                        <button class="btn-table btn-table-danger" title="Delete accidental punch" onclick="deletePunchRecord('{{ $p->id }}', '{{ addslashes($individualStaff->staff_name) }}', '{{ date('d M Y', strtotime($p->punch_date)) }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                        No attendance punches recorded for this staff member in the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif


    <!-- ========================================================================================= -->
    <!-- 5. REPORT VIEW 4: REGISTERED USERS DIRECTORY                                              -->
    <!-- ========================================================================================= -->
    @if($activeTab === 'registered')
        <div class="screen-only">
            <!-- Compact Metric Bar -->
            <div class="metric-bar">
                <div class="metric-chip">
                    <div class="metric-chip-icon chip-green"><i class="fa-solid fa-users"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ count($registeredStaff) }}</span>
                        <span class="metric-chip-lbl">Registered SF Staff</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-blue"><i class="fa-solid fa-id-card-clip"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ collect($registeredStaff)->whereNotNull('photo_url')->count() }}</span>
                        <span class="metric-chip-lbl">With Face Photo</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-purple"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">{{ collect($registeredStaff)->pluck('branch')->unique()->count() }}</span>
                        <span class="metric-chip-lbl">Departments</span>
                    </div>
                </div>

                <div class="metric-chip">
                    <div class="metric-chip-icon chip-amber"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="metric-chip-data">
                        <span class="metric-chip-val">100%</span>
                        <span class="metric-chip-lbl">Biometric Protected</span>
                    </div>
                </div>
            </div>

            <!-- Table Panel with Live Search Toolbar -->
            <div class="table-panel">
                <div class="table-toolbar">
                    <div class="toolbar-group">
                        <span style="font-size: 0.8rem; font-weight: 700; color: #fff;">
                            <i class="fa-solid fa-users-gear me-1 text-blue"></i> Enrolled Faculty &amp; Staff Directory
                        </span>
                    </div>

                    <div class="toolbar-group">
                        <div style="position: relative;">
                            <input type="text" id="registeredSearchInput" class="input-compact" placeholder="Quick search staff, ID, dept..." oninput="filterRegisteredStaffTable()" style="padding-left: 24px; width: 220px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 8px; top: 8px; color: var(--text-muted); font-size: 0.7rem;"></i>
                        </div>
                        <span class="badge badge-neutral">
                            <span id="regStaffCount">{{ count($registeredStaff) }}</span> staff enrolled
                        </span>
                    </div>
                </div>

                <!-- Registered Users Table -->
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 35px; text-align: center;">#</th>
                                <th style="text-align: center;">Face</th>
                                <th>Staff Name &amp; ID</th>
                                <th>Department &amp; Designation</th>
                                <th>Biometric Status</th>
                                <th>Enrolment Date</th>
                                <th style="text-align: center;">Total Punches</th>
                                <th>Latest Punch</th>
                                <th class="col-actions" style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registeredStaff as $idx => $rs)
                                <tr id="row-reg-{{ $rs->staff_id }}" class="reg-staff-row">
                                    <td style="text-align: center; color: var(--text-muted); font-weight: 700;">{{ $idx + 1 }}</td>
                                    <td style="text-align: center;">
                                        <div style="position: relative; display: inline-block;">
                                            @if($rs->photo_url)
                                                <img src="{{ $rs->photo_url }}" alt="{{ $rs->staff_name }}" class="avatar-sm" style="border-color: #34d399;" onclick="openPhotoModal('{{ $rs->photo_url }}', '{{ addslashes($rs->staff_name) }}')" title="Click to view enlarged photo">
                                            @else
                                                <div class="avatar-sm" style="display:flex; align-items:center; justify-content:center; font-size:0.6rem; color:var(--text-muted); background:rgba(255,255,255,0.05); margin:0 auto;">--</div>
                                            @endif
                                            @if(!empty($rs->staff_id) && \Illuminate\Support\Facades\Cache::has('user_online_' . $rs->staff_id))
                                                <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: #10b981; border: 2px solid #0f172a; border-radius: 50%; box-shadow: 0 0 0 1px rgba(52, 211, 153, 0.4);" title="Online"></span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">{{ $rs->staff_name }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted); font-family: monospace;">{{ $rs->staff_id }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-purple">{{ $rs->branch }}</span>
                                        <span style="font-size: 0.7rem; color: var(--text-muted); margin-left: 3px;">{{ $rs->designation }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Enrolled</span>
                                    </td>
                                    <td style="font-size: 0.72rem; color: var(--text-muted);">
                                        {{ $rs->created_at ? date('d M Y, h:i A', strtotime($rs->created_at)) : '--' }}
                                    </td>
                                    <td style="text-align: center; font-weight: 800; color: #60a5fa;">
                                        {{ $rs->total_punches }}
                                    </td>
                                    <td style="font-size: 0.72rem; color: var(--text-muted);">
                                        {{ $rs->latest_punch ? date('d M Y', strtotime($rs->latest_punch)) : 'Never' }}
                                    </td>
                                    <td class="col-actions" style="text-align: center;">
                                        <div style="display: inline-flex; gap: 4px;">
                                            <a href="/sf-attendance/attendance-report?tab=individual&staff_id={{ $rs->staff_id }}" class="btn-table btn-table-primary" title="View individual statement">
                                                <i class="fa-solid fa-user"></i> Report
                                            </a>
                                            <button class="btn-table btn-table-danger" title="Deregister face biometric" onclick="resetFaceRegistration('{{ $rs->staff_id }}', '{{ addslashes($rs->staff_name) }}')">
                                                <i class="fa-solid fa-user-xmark"></i> Dereg
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                        No staff members have registered their biometric face profile yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif


    <!-- ========================================================================================= -->
    <!-- 6. CLEAN PRINT LAYOUTS (CARMEL POLYTECHNIC COLLEGE)                                       -->
    <!-- ========================================================================================= -->

    <!-- PRINT FORMAT 1: DAILY REPORT -->
    @if($activeTab === 'daily')
        <div class="print-only print-page">
            <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px;">
                <h1 style="font-size: 16pt; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">CARMEL POLYTECHNIC COLLEGE</h1>
                <h2 style="font-size: 12pt; font-weight: 700; color: #222; margin-bottom: 4px;">Self-Financing (SF) Staff Daily Biometric Attendance Ledger</h2>
                <p style="font-size: 9pt; margin: 0;">
                    Attendance Date: <strong>{{ date('l, d F Y', strtotime($selectedDate)) }}</strong> &nbsp;|&nbsp; Shift Timing: <strong>09:00 AM – 04:00 PM</strong>
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 8.5pt; margin-bottom: 12px; border: 1px solid #000; padding: 6px 10px; background: #f8fafc;">
                <div>Total Present: <strong>{{ $dailyTotalPresent }}</strong></div>
                <div>Inside Campus: <strong>{{ $dailyInside }}</strong></div>
                <div>Late Entries: <strong>{{ $dailyLate }}</strong></div>
                <div>Evening Completed: <strong>{{ $dailyCompleted }}</strong></div>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 8pt;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="width: 25px; text-align: center;">#</th>
                        <th style="text-align: left;">Staff Name &amp; ID</th>
                        <th style="text-align: left;">Department</th>
                        <th style="text-align: center;">Morning IN</th>
                        <th style="text-align: center;">Evening OUT</th>
                        <th style="text-align: center;">Campus Hours</th>
                        <th style="text-align: center;">Premises</th>
                        <th style="text-align: center;">GPS Distance</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyPunches as $idx => $p)
                        @php
                            $campusHours = '--';
                            if ($p->in_time && $p->out_time) {
                                $diffMinutes = round(abs(strtotime($p->out_time) - strtotime($p->in_time)) / 60);
                                $campusHours = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';
                            } elseif ($p->in_time) {
                                $campusHours = 'Active';
                            }
                            $regObj = $regMap->get($p->staff_id);
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ $p->staff_name }}</strong> ({{ $p->staff_id }})</td>
                            <td>{{ $regObj->branch ?? 'SF' }} &ndash; {{ $regObj->designation ?? 'Faculty' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--' }}</td>
                            <td style="text-align: center;">{{ $campusHours }}</td>
                            <td style="text-align: center;">
                                {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'INSIDE' : 'OUTSIDE' }} /
                                {{ $p->out_time ? ($p->out_premises_status === 'INSIDE_PREMISES' ? 'INSIDE' : 'OUTSIDE') : '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ $p->in_gps_distance_meters !== null ? $p->in_gps_distance_meters . 'm' : '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ str_contains($p->punch_status ?? '', 'LATE_IN') ? 'LATE IN' : 'PRESENT' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">No punches recorded for this date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 50px; font-size: 8.5pt; font-weight: 700;">
                <div>Prepared By: _____________________<br><small style="font-weight: 400;">Academic Coordinator (SF)</small></div>
                <div>Verified By: _____________________<br><small style="font-weight: 400;">Administrative Officer</small></div>
                <div>Approved By: _____________________<br><small style="font-weight: 400;">Principal / Chairman</small></div>
            </div>
        </div>
    @endif

    <!-- PRINT FORMAT 2: MONTHLY REPORT -->
    @if($activeTab === 'monthly')
        <div class="print-only print-page">
            <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px;">
                <h1 style="font-size: 16pt; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">CARMEL POLYTECHNIC COLLEGE</h1>
                <h2 style="font-size: 12pt; font-weight: 700; color: #222; margin-bottom: 4px;">Self-Financing (SF) Staff Monthly Attendance &amp; Working Hours Ledger</h2>
                <p style="font-size: 9pt; margin: 0;">
                    Month: <strong>{{ date('F Y', strtotime($selectedMonth . '-01')) }}</strong> &nbsp;|&nbsp; Period: <strong>{{ date('01 M Y', strtotime($selectedMonth . '-01')) }} to {{ date('t M Y', strtotime($selectedMonth . '-01')) }}</strong>
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 8.5pt; margin-bottom: 12px; border: 1px solid #000; padding: 6px 10px; background: #f8fafc;">
                <div>Active Staff: <strong>{{ $monthlyActiveStaffCount }}</strong></div>
                <div>Total Man-Hours: <strong>{{ $monthlyTotalHoursFormatted }}</strong></div>
                <div>Total Late Entries: <strong>{{ $monthlyTotalLateEntries }}</strong></div>
                <div>Total Logged Punches: <strong>{{ $allMonthlyPunches->count() }}</strong></div>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 8pt;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="width: 25px; text-align: center;">#</th>
                        <th style="text-align: left;">Staff ID</th>
                        <th style="text-align: left;">Staff Member Name</th>
                        <th style="text-align: left;">Dept &amp; Designation</th>
                        <th style="text-align: center;">Days Present</th>
                        <th style="text-align: center;">In Premises</th>
                        <th style="text-align: center;">Late Entries</th>
                        <th style="text-align: center;">Total Hours</th>
                        <th style="text-align: center;">Avg / Day</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyStaffSummary as $idx => $s)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td>{{ $s->staff_id }}</td>
                            <td><strong>{{ $s->staff_name }}</strong></td>
                            <td>{{ $s->branch }} &ndash; {{ $s->designation }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $s->days_present }}</td>
                            <td style="text-align: center;">{{ $s->inside_premises_days }}</td>
                            <td style="text-align: center;">{{ $s->late_count }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $s->total_hours_formatted }}</td>
                            <td style="text-align: center;">{{ $s->avg_hours_formatted }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 50px; font-size: 8.5pt; font-weight: 700;">
                <div>Prepared By: _____________________<br><small style="font-weight: 400;">Academic Coordinator (SF)</small></div>
                <div>Verified By: _____________________<br><small style="font-weight: 400;">Administrative Officer</small></div>
                <div>Approved By: _____________________<br><small style="font-weight: 400;">Principal / Chairman</small></div>
            </div>
        </div>
    @endif

    <!-- PRINT FORMAT 3: INDIVIDUAL REPORT -->
    @if($activeTab === 'individual' && $individualStaff)
        <div class="print-only print-page">
            <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 14px;">
                <h1 style="font-size: 16pt; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">CARMEL POLYTECHNIC COLLEGE</h1>
                <h2 style="font-size: 12pt; font-weight: 700; color: #222; margin-bottom: 4px;">Individual Staff Biometric Attendance Statement &amp; Punch Log</h2>
                <p style="font-size: 8.5pt; margin: 0;">Self-Financing (SF) Faculty &amp; Staff Attendance Record</p>
            </div>

            <!-- Staff Details & Metrics Box -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border: 1px solid #000; padding: 10px; margin-bottom: 14px; font-size: 8.5pt; background: #f8fafc;">
                <div>
                    <div>Staff Name: <strong>{{ $individualStaff->staff_name }}</strong></div>
                    <div>Staff ID / Mobile: <strong>{{ $individualStaff->staff_id }}</strong></div>
                    <div>Department / Branch: <strong>{{ $individualStaff->branch }}</strong></div>
                    <div>Designation: <strong>{{ $individualStaff->designation }}</strong></div>
                </div>
                <div>
                    <div>Period: <strong>{{ $individualPeriod === 'month' ? date('F Y', strtotime($individualMonth . '-01')) : 'All-Time Lifetime Record' }}</strong></div>
                    <div>Total Days Present: <strong>{{ $individualStats->days_present }}</strong></div>
                    <div>Total Hours Logged: <strong>{{ $individualStats->total_hours_formatted }}</strong> (Avg: {{ $individualStats->avg_hours_formatted }}/day)</div>
                    <div>Late Arrivals: <strong>{{ $individualStats->late_count }}</strong> &nbsp;|&nbsp; Campus Compliance: <strong>{{ $individualStats->inside_percentage }}%</strong></div>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 7.5pt;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="width: 25px; text-align: center;">#</th>
                        <th style="text-align: left;">Date &amp; Day</th>
                        <th style="text-align: center;">Morning IN</th>
                        <th style="text-align: center;">Evening OUT</th>
                        <th style="text-align: center;">Duration</th>
                        <th style="text-align: center;">Premises</th>
                        <th style="text-align: center;">Distance</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($individualPunches as $idx => $p)
                        @php
                            $campusHours = '--';
                            if ($p->in_time && $p->out_time) {
                                $diffMinutes = round(abs(strtotime($p->out_time) - strtotime($p->in_time)) / 60);
                                $campusHours = floor($diffMinutes / 60) . 'h ' . ($diffMinutes % 60) . 'm';
                            } elseif ($p->in_time) {
                                $campusHours = 'Active';
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ date('d M Y (D)', strtotime($p->punch_date)) }}</strong></td>
                            <td style="text-align: center; font-weight: 700;">{{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--' }}</td>
                            <td style="text-align: center;">{{ $campusHours }}</td>
                            <td style="text-align: center;">
                                {{ $p->in_premises_status === 'INSIDE_PREMISES' ? 'IN' : 'OUT' }} /
                                {{ $p->out_time ? ($p->out_premises_status === 'INSIDE_PREMISES' ? 'IN' : 'OUT') : '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ $p->in_gps_distance_meters !== null ? $p->in_gps_distance_meters . 'm' : '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ str_contains($p->punch_status ?? '', 'LATE_IN') ? 'LATE IN' : 'PRESENT' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px;">No punches recorded for this staff member in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 45px; font-size: 8.5pt; font-weight: 700;">
                <div>Staff Signature: _____________________<br><small style="font-weight: 400;">{{ $individualStaff->staff_name }}</small></div>
                <div>Verified By: _____________________<br><small style="font-weight: 400;">Academic Coordinator (SF)</small></div>
                <div>Approved By: _____________________<br><small style="font-weight: 400;">Principal / Chairman</small></div>
            </div>
        </div>
    @endif

    <!-- PRINT FORMAT 4: REGISTERED USERS DIRECTORY -->
    @if($activeTab === 'registered')
        <div class="print-only print-page">
            <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px;">
                <h1 style="font-size: 16pt; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">CARMEL POLYTECHNIC COLLEGE</h1>
                <h2 style="font-size: 12pt; font-weight: 700; color: #222; margin-bottom: 4px;">Self-Financing (SF) Staff Biometric Face Enrolment Directory</h2>
                <p style="font-size: 9pt; margin: 0;">
                    Official Enrolled Staff Count: <strong>{{ count($registeredStaff) }}</strong> &nbsp;|&nbsp; Generated on: <strong>{{ date('d F Y, h:i A') }}</strong>
                </p>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 8pt;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="width: 25px; text-align: center;">#</th>
                        <th style="text-align: left;">Staff ID / Mobile</th>
                        <th style="text-align: left;">Staff Member Name</th>
                        <th style="text-align: left;">Department &amp; Designation</th>
                        <th style="text-align: center;">Biometric Status</th>
                        <th style="text-align: center;">Enrolment Date</th>
                        <th style="text-align: center;">Lifetime Punches</th>
                        <th style="text-align: center;">Last Punch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registeredStaff as $idx => $rs)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td>{{ $rs->staff_id }}</td>
                            <td><strong>{{ $rs->staff_name }}</strong></td>
                            <td>{{ $rs->branch }} &ndash; {{ $rs->designation }}</td>
                            <td style="text-align: center;">ENROLLED</td>
                            <td style="text-align: center;">{{ $rs->created_at ? date('d M Y', strtotime($rs->created_at)) : '--' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $rs->total_punches }}</td>
                            <td style="text-align: center;">{{ $rs->latest_punch ? date('d M Y', strtotime($rs->latest_punch)) : 'Never' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 50px; font-size: 8.5pt; font-weight: 700;">
                <div>Prepared By: _____________________<br><small style="font-weight: 400;">Academic Coordinator (SF)</small></div>
                <div>Verified By: _____________________<br><small style="font-weight: 400;">Administrative Officer</small></div>
                <div>Approved By: _____________________<br><small style="font-weight: 400;">Principal / Chairman</small></div>
            </div>
        </div>
    @endif


    <!-- ========================================================================================= -->
    <!-- 7. MODALS: Face Verification Audit, Photo Zoom                                            -->
    <!-- ========================================================================================= -->

    <!-- MODAL: Biometric Face Verification Audit -->
    <div class="modal-overlay screen-only" id="modalFaceCompare">
        <div class="modal-box" style="max-width: 680px;">
            <div class="modal-head">
                <h3><i class="fa-solid fa-face-viewfinder"></i> Biometric Verification Audit</h3>
                <button class="modal-close-btn" onclick="closeFaceCompareModal()">&times;</button>
            </div>
            
            <div style="background: rgba(0, 0, 0, 0.4); border: 1px solid var(--border-subtle); border-radius: 9px; padding: 10px 14px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div>
                    <div id="cmpStaffName" style="font-size: 0.95rem; color: #fff; font-weight: 800;">Staff Name</div>
                    <div id="cmpStaffId" style="font-size: 0.7rem; color: var(--text-muted); font-family: monospace;">Staff ID</div>
                </div>
                <div style="display: flex; gap: 6px;">
                    <span id="cmpDateBadge" class="badge badge-info">10 Aug 2026</span>
                    <span id="cmpScoreBadge" class="badge badge-success">Match: 95%</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 14px;">
                <!-- 1. Registered Face -->
                <div style="background: #090f1d; border: 1px solid var(--success-border); border-radius: 10px; padding: 10px; text-align: center;">
                    <span class="badge badge-success" style="margin-bottom: 6px;"><i class="fa-solid fa-id-card me-1"></i> Registered Master</span>
                    <div style="width: 100%; height: 160px; border-radius: 8px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center;">
                        <img id="cmpImgReg" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Registered">
                        <div id="cmpImgRegEmpty" style="color: var(--text-muted); font-size: 0.7rem;">No Image</div>
                    </div>
                </div>

                <!-- 2. Morning IN Snapshot -->
                <div style="background: #090f1d; border: 1px solid var(--primary-border); border-radius: 10px; padding: 10px; text-align: center;">
                    <span class="badge badge-info" style="margin-bottom: 6px;"><i class="fa-solid fa-sun me-1"></i> Morning IN</span>
                    <div style="width: 100%; height: 160px; border-radius: 8px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center;">
                        <img id="cmpImgIn" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Morning IN">
                        <div id="cmpImgInEmpty" style="color: var(--text-muted); font-size: 0.7rem;">No Image</div>
                    </div>
                </div>

                <!-- 3. Evening OUT Snapshot -->
                <div style="background: #090f1d; border: 1px solid var(--danger-border); border-radius: 10px; padding: 10px; text-align: center;">
                    <span class="badge badge-danger" style="margin-bottom: 6px;"><i class="fa-solid fa-moon me-1"></i> Evening OUT</span>
                    <div style="width: 100%; height: 160px; border-radius: 8px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center;">
                        <img id="cmpImgOut" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Evening OUT">
                        <div id="cmpImgOutEmpty" style="color: var(--text-muted); font-size: 0.7rem;">No Image</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closeFaceCompareModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL: Photo Zoom -->
    <div class="modal-overlay screen-only" id="modalPhotoZoom">
        <div class="modal-box" style="max-width: 420px; text-align: center;">
            <div class="modal-head">
                <h3 id="zoomStaffName"><i class="fa-solid fa-image"></i> Staff Photo</h3>
                <button class="modal-close-btn" onclick="closePhotoModal()">&times;</button>
            </div>
            <div style="width: 100%; height: 320px; overflow: hidden; border-radius: 10px; background: #000; margin-bottom: 14px;">
                <img id="zoomImgSrc" src="" style="width: 100%; height: 100%; object-fit: contain;" alt="Enlarged Photo">
            </div>
            <div style="display: flex; justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closePhotoModal()">Close</button>
            </div>
        </div>
    </div>


    <!-- ========================================================================================= -->
    <!-- 8. JAVASCRIPT INTERACTIONS                                                                -->
    <!-- ========================================================================================= -->
    <script>
        function openPhotoModal(imgUrl, staffName) {
            document.getElementById('zoomStaffName').innerHTML = '<i class="fa-solid fa-image me-1"></i> ' + staffName;
            document.getElementById('zoomImgSrc').src = imgUrl;
            document.getElementById('modalPhotoZoom').classList.add('active');
        }
        function closePhotoModal() {
            document.getElementById('modalPhotoZoom').classList.remove('active');
        }

        function openFaceCompareModal(staffName, staffId, regUrl, inUrl, outUrl, dateStr, score) {
            document.getElementById('cmpStaffName').innerText = staffName;
            document.getElementById('cmpStaffId').innerText = 'ID: ' + staffId;
            document.getElementById('cmpDateBadge').innerText = dateStr;
            const pct = Math.round((parseFloat(score) || 0.95) * 100);
            document.getElementById('cmpScoreBadge').innerText = 'Match: ' + pct + '%';

            const imgReg = document.getElementById('cmpImgReg');
            const imgRegEmpty = document.getElementById('cmpImgRegEmpty');
            if (regUrl && regUrl !== 'null' && regUrl !== '') {
                imgReg.src = regUrl;
                imgReg.style.display = 'block';
                imgRegEmpty.style.display = 'none';
            } else {
                imgReg.style.display = 'none';
                imgRegEmpty.style.display = 'block';
            }

            const imgIn = document.getElementById('cmpImgIn');
            const imgInEmpty = document.getElementById('cmpImgInEmpty');
            if (inUrl && inUrl !== 'null' && inUrl !== '') {
                imgIn.src = inUrl;
                imgIn.style.display = 'block';
                imgInEmpty.style.display = 'none';
            } else {
                imgIn.style.display = 'none';
                imgInEmpty.style.display = 'block';
            }

            const imgOut = document.getElementById('cmpImgOut');
            const imgOutEmpty = document.getElementById('cmpImgOutEmpty');
            if (outUrl && outUrl !== 'null' && outUrl !== '') {
                imgOut.src = outUrl;
                imgOut.style.display = 'block';
                imgOutEmpty.style.display = 'none';
            } else {
                imgOut.style.display = 'none';
                imgOutEmpty.style.display = 'block';
            }

            document.getElementById('modalFaceCompare').classList.add('active');
        }
        function closeFaceCompareModal() {
            document.getElementById('modalFaceCompare').classList.remove('active');
        }

        // Live instant filter for Registered Staff Directory table
        function filterRegisteredStaffTable() {
            const query = (document.getElementById('registeredSearchInput').value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.reg-staff-row');
            let visibleCount = 0;
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            const countEl = document.getElementById('regStaffCount');
            if (countEl) countEl.innerText = visibleCount;
        }

        function forceFreshReload() {
            const icon = document.getElementById('refreshSpinIcon');
            if (icon) icon.classList.add('fa-spin');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('_t', Date.now());
            window.location.href = currentUrl.toString();
        }

        // Live Auto-Sync every 35s
        setInterval(() => {
            if (!document.hidden && !document.querySelector('.modal-overlay.active')) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('_t', Date.now());
                window.location.href = currentUrl.toString();
            }
        }, 35000);

        // Delete an accidental punch entry (Admin only)
        async function deletePunchRecord(id, staffName, dateStr) {
            if (!confirm(`Are you sure you want to PERMANENTLY DELETE the attendance punch record for ${staffName} on ${dateStr}?\n\nThis entry will be permanently removed.`)) {
                return;
            }

            const row = document.getElementById(`row-punch-${id}`);
            if (row) row.style.opacity = '0.3';

            try {
                const response = await fetch(`/sf-attendance/delete-punch/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    if (row) row.remove();
                    forceFreshReload();
                } else {
                    alert("Error: " + (data.message || "Failed to delete punch record."));
                    if (row) row.style.opacity = '1';
                }
            } catch (err) {
                console.error(err);
                alert("Network error deleting punch record.");
                if (row) row.style.opacity = '1';
            }
        }

        // Reset / Deregister Biometric Face Profile (Admin only)
        async function resetFaceRegistration(staffId, staffName) {
            if (!confirm(`Are you sure you want to DEREGISTER biometric face profile and clear all attendance logs for ${staffName} (ID: ${staffId})?\n\nThis will purge their registered face data so they can re-register on their next mobile login.`)) {
                return;
            }

            const regRow = document.getElementById(`row-reg-${staffId}`);
            if (regRow) regRow.style.opacity = '0.3';

            try {
                const response = await fetch(`/sf-attendance/reset-face/${encodeURIComponent(staffId)}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    if (regRow) regRow.remove();
                    forceFreshReload();
                } else {
                    alert("Notice: " + (data.message || "Failed to reset face registration."));
                    if (regRow) regRow.style.opacity = '1';
                }
            } catch (err) {
                console.error(err);
                alert("Network error resetting face registration.");
                if (regRow) regRow.style.opacity = '1';
            }
        }

        function goBackToDashboard() {
            const ref = document.referrer;
            if (ref && ref.includes(window.location.host) && !ref.includes('/sf-attendance/')) {
                window.location.href = ref;
                return;
            }

            const userRole = "{{ session('userRole') }}";
            if (userRole === 'Super_Admin' || userRole === 'Principal' || userRole === 'SUPER_ADMIN' || userRole === 'PRINCIPAL') {
                window.location.href = '/dashboard/principal';
            } else if (userRole === 'Academic_Coordinator_SF' || userRole === 'ACADEMIC_COORDINATOR_SF') {
                window.location.href = '/dashboard/academic-coordinator-sf';
            } else if (userRole === 'Gen_Dept_Coordinator_Self_Finance' || userRole === 'GEN_DEPT_COORDINATOR_SELF_FINANCE') {
                window.location.href = '/dashboard/general-coordinator-sf';
            } else if (userRole === 'Admin' || userRole === 'ADMIN') {
                window.location.href = '/dashboard/admin';
            } else {
                window.location.href = '/dashboard/principal';
            }
        }
    </script>
</body>
</html>
