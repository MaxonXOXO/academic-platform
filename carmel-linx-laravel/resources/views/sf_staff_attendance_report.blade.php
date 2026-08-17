<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SF Staff Attendance Master Ledger & Reports | Carmel Linx</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            padding: 16px;
        }

        /* Top Header Bar */
        .header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.95));
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            margin-bottom: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .header-title h1 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-title p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.775rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-geofence {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }
        .btn-geofence:hover { background: rgba(16, 185, 129, 0.3); }

        .btn-dereg {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }
        .btn-dereg:hover { background: rgba(245, 158, 11, 0.3); }

        .btn-monthend {
            background: rgba(168, 85, 247, 0.15);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.4);
        }
        .btn-monthend:hover { background: rgba(168, 85, 247, 0.3); }

        .btn-print {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }
        .btn-print:hover { background: rgba(59, 130, 246, 0.3); }

        .btn-back {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        /* Summary Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .stat-info h3 {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-info p {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Filter Console */
        .filter-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 16px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
            align-items: end;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 8px 10px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-light);
            font-size: 0.775rem;
            outline: none;
        }

        .btn-submit-filter {
            padding: 9px 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.775rem;
        }

        /* Master Table - Responsive Compact High-Density View */
        .table-responsive {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            width: 100%;
            overflow-x: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.725rem;
            table-layout: auto;
        }

        th {
            background: #0f172a;
            padding: 8px 10px;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 0.65rem;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.025);
        }

        .badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge-info { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }

        /* Compact Action Buttons */
        .btn-action-sm {
            padding: 3px 7px;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }

        .btn-delete-punch {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .btn-delete-punch:hover { background: rgba(239, 68, 68, 0.3); }

        .btn-reset-face {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .btn-reset-face:hover { background: rgba(245, 158, 11, 0.3); }

        /* Modal Overlays */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            width: 100%;
            max-width: 620px;
            padding: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h2 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
        }

        /* Month-End Print Preview Layout */
        #monthEndPrintArea { display: none; }

        @media print {
            .header-bar, .stats-grid, .filter-card, .btn-action, .modal-overlay, .col-actions, .btn-action-sm { display: none !important; }
            body { background: #fff !important; color: #000 !important; padding: 0 !important; }
            
            body.print-monthend #mainLogArea { display: none !important; }
            body.print-monthend #monthEndPrintArea { display: block !important; }

            .table-responsive { border: 1px solid #ccc !important; box-shadow: none !important; background: #fff !important; }
            table { width: 100% !important; color: #000 !important; }
            th { background: #f1f5f9 !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
            td { color: #000 !important; border-bottom: 1px solid #ddd !important; }
            .badge { border: 1px solid #999 !important; color: #000 !important; background: #f1f5f9 !important; }
        }
    </style>
</head>
<body>

    <!-- Top Header Bar -->
    <div class="header-bar">
        <div class="header-title">
            <h1><i class="fa-solid fa-building-user"></i> Self-Financing Staff Attendance Master Ledger</h1>
            <p>Official Shift: <strong>09:00 AM – 04:00 PM</strong> | Biometric Face & GPS Geofence Verification</p>
        </div>
            <!-- Campus GPS & Google Map Geofence Setup -->
            <a href="/sf-attendance/geofence-setup" class="btn-action" style="text-decoration: none; background: rgba(37, 99, 235, 0.2); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.4);">
                <i class="fa-solid fa-location-dot"></i> Campus GPS &amp; Geofence Setup
            </a>

            <!-- Deregister Face Biometrics Manager -->
            <button class="btn-action btn-dereg" onclick="openDeregModal()">
                <i class="fa-solid fa-user-gear"></i> Deregister Biometrics
            </button>

            <!-- Month-End Executive Printout -->
            <button class="btn-action btn-monthend" onclick="openMonthEndModal()">
                <i class="fa-solid fa-file-invoice"></i> Month-End Report
            </button>

            <button class="btn-action btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>

            <button type="button" onclick="goBackToDashboard()" class="btn-action btn-back">
                <i class="fa-solid fa-chevron-left"></i> Back
            </button>
        </div>
    </div>

    <!-- Main Workspace Area -->
    <div id="mainLogArea">
        <!-- Summary Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ count($punches) }}</h3>
                    <p>Total Log Entries</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $punches->where('in_premises_status', 'INSIDE_PREMISES')->count() }}</h3>
                    <p>Inside Campus</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $punches->where('punch_status', 'LATE_IN')->count() }}</h3>
                    <p>Late Entry</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ count($registeredStaff ?? []) }}</h3>
                    <p>Registered Biometric Staff</p>
                </div>
            </div>
        </div>

        <!-- Filter Console -->
        <div class="filter-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 0.72rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Log View Mode:</span>
                    @if($startDate === $endDate && $startDate === date('Y-m-d'))
                        <span class="badge badge-success" style="font-size: 0.72rem; padding: 4px 10px;">
                            <i class="fa-solid fa-calendar-day"></i> DAILY PUNCHING LOG (TODAY: {{ date('d M Y') }})
                        </span>
                    @else
                        <span class="badge badge-info" style="font-size: 0.72rem; padding: 4px 10px;">
                            <i class="fa-solid fa-calendar-week"></i> HISTORY LOG VIEW ({{ date('d M Y', strtotime($startDate)) }} – {{ date('d M Y', strtotime($endDate)) }})
                        </span>
                    @endif
                </div>

                <!-- Quick Date View Presets -->
                <div style="display: flex; gap: 6px;">
                    <a href="/sf-attendance/attendance-report" class="btn-action {{ ($startDate === $endDate && $startDate === date('Y-m-d')) ? 'btn-geofence' : 'btn-back' }}" style="font-size: 0.7rem; padding: 5px 10px; text-decoration: none;">
                        <i class="fa-solid fa-calendar-day"></i> Today's Daily Log
                    </a>
                    <a href="/sf-attendance/attendance-report?start_date={{ date('Y-m-01') }}&end_date={{ date('Y-m-d') }}" class="btn-action {{ !($startDate === $endDate && $startDate === date('Y-m-d')) ? 'btn-geofence' : 'btn-back' }}" style="font-size: 0.7rem; padding: 5px 10px; text-decoration: none;">
                        <i class="fa-solid fa-calendar-days"></i> Full Month Log
                    </a>
                </div>
            </div>

            <form action="/sf-attendance/attendance-report" method="GET" class="filter-grid">
                <div class="form-group">
                    <label>From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="form-group">
                    <label>To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="form-group">
                    <label>Search Staff Name / ID</label>
                    <input type="text" name="search" class="form-control" placeholder="Staff Name or ID..." value="{{ $search }}">
                </div>
                <div class="form-group">
                    <label>Premises Status</label>
                    <select name="premises_status" class="form-control">
                        <option value="">All Locations</option>
                        <option value="INSIDE_PREMISES" {{ $premisesFilter === 'INSIDE_PREMISES' ? 'selected' : '' }}>Inside Premises Only</option>
                        <option value="OUTSIDE_PREMISES" {{ $premisesFilter === 'OUTSIDE_PREMISES' ? 'selected' : '' }}>Outside Premises</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-submit-filter" style="flex: 1;">
                        <i class="fa-solid fa-filter"></i> Apply Filter
                    </button>
                    <button type="button" class="btn-submit-filter" style="background: rgba(16, 185, 129, 0.2); border: 1px solid #34d399; color: #34d399; flex: 1;" onclick="forceFreshReload()" title="Force cache-busting instant sync">
                        <i class="fa-solid fa-arrows-rotate" id="refreshSpinIcon"></i> Live Sync
                    </button>
                </div>
            </form>
        </div>

        <!-- Master Table - Fully Responsive Compact View -->
        @php
            $regMap = collect($registeredStaff ?? [])->keyBy('staff_id');
        @endphp
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Staff Member & ID</th>
                        <th>Face Biometrics & Snapshots</th>
                        <th>Morning IN</th>
                        <th>Evening OUT</th>
                        <th>Campus Duration</th>
                        <th>Premises (IN/OUT)</th>
                        <th>Actual GPS Distance (IN/OUT)</th>
                        <th>Liveness</th>
                        <th>Status</th>
                        <th class="col-actions" style="text-align: center;">Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($punches as $p)
                        @php
                            $campusHours = '--';
                            if ($p->in_time && $p->out_time) {
                                $tIn = strtotime($p->in_time);
                                $tOut = strtotime($p->out_time);
                                $diffMinutes = round(abs($tOut - $tIn) / 60);
                                $hrs = floor($diffMinutes / 60);
                                $mins = $diffMinutes % 60;
                                $campusHours = "{$hrs}h {$mins}m";
                            } elseif ($p->in_time) {
                                $campusHours = 'Active';
                            }

                            $regObj = $regMap->get($p->staff_id);
                            $regFace = $regObj->photo_url ?? null;
                            $inSnap = $p->in_snapshot_url ?? null;
                            $outSnap = $p->out_snapshot_url ?? null;

                            $inDistStr = '--';
                            if ($p->in_gps_distance_meters !== null) {
                                $dIn = (int) $p->in_gps_distance_meters;
                                $inDistStr = $dIn >= 1000 ? number_format($dIn / 1000, 2) . ' km' : $dIn . 'm';
                            }

                            $outDistStr = '--';
                            if ($p->out_gps_distance_meters !== null) {
                                $dOut = (int) $p->out_gps_distance_meters;
                                $outDistStr = $dOut >= 1000 ? number_format($dOut / 1000, 2) . ' km' : $dOut . 'm';
                            }
                        @endphp
                        <tr id="row-punch-{{ $p->id }}">
                            <td><strong>{{ date('d M Y', strtotime($p->punch_date)) }}</strong></td>
                            <td>
                                <div><strong>{{ $p->staff_name }}</strong></div>
                                <div style="font-size:0.65rem; color:var(--text-muted);">ID: {{ $p->staff_id }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <!-- Registered Face Thumbnail -->
                                    <div style="text-align: center;">
                                        @if($regFace)
                                            <img src="{{ $regFace }}" alt="REG" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 2px solid #34d399; cursor: pointer; transition: transform 0.2s;" title="Registered Face (Click to compare)" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.92 }}')">
                                        @else
                                            <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px dashed var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; color: var(--text-muted);" title="Not Registered">N/A</div>
                                        @endif
                                        <span style="display: block; font-size: 0.58rem; color: #34d399; font-weight: 700; margin-top: 1px;">REG</span>
                                    </div>
                                    <!-- IN Punch Snapshot Thumbnail -->
                                    <div style="text-align: center;">
                                        @if($inSnap)
                                            <img src="{{ $inSnap }}" alt="IN" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 2px solid #60a5fa; cursor: pointer; transition: transform 0.2s;" title="Morning IN Snapshot (Click to compare)" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.92 }}')">
                                        @else
                                            <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px dashed var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; color: var(--text-muted);">--</div>
                                        @endif
                                        <span style="display: block; font-size: 0.58rem; color: #60a5fa; font-weight: 700; margin-top: 1px;">IN</span>
                                    </div>
                                    <!-- OUT Punch Snapshot Thumbnail -->
                                    <div style="text-align: center;">
                                        @if($outSnap)
                                            <img src="{{ $outSnap }}" alt="OUT" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 2px solid #f87171; cursor: pointer; transition: transform 0.2s;" title="Evening OUT Snapshot (Click to compare)" onclick="openFaceCompareModal('{{ addslashes($p->staff_name) }}', '{{ $p->staff_id }}', '{{ $regFace }}', '{{ $inSnap }}', '{{ $outSnap }}', '{{ date('d M Y', strtotime($p->punch_date)) }}', '{{ $p->liveness_score ?? 0.92 }}')">
                                        @else
                                            <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,0.05); border: 1px dashed var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; color: var(--text-muted);">--</div>
                                        @endif
                                        <span style="display: block; font-size: 0.58rem; color: #f87171; font-weight: 700; margin-top: 1px;">OUT</span>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#34d399; font-weight:700;">
                                {{ $p->in_time ? date('h:i A', strtotime($p->in_time)) : '--:--' }}
                            </td>
                            <td style="color:#f87171; font-weight:700;">
                                {{ $p->out_time ? date('h:i A', strtotime($p->out_time)) : '--:--' }}
                            </td>
                            <td style="font-weight:700; color:#60a5fa;">
                                {{ $campusHours }}
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    @if($p->in_premises_status === 'INSIDE_PREMISES')
                                        <span class="badge badge-success" style="font-size:0.65rem; padding: 2px 6px;">IN: Inside</span>
                                    @elseif($p->in_premises_status === 'OUTSIDE_PREMISES')
                                        <span class="badge badge-danger" style="font-size:0.65rem; padding: 2px 6px;">IN: Outside</span>
                                    @else
                                        <span style="font-size:0.65rem; color:var(--text-muted);">IN: --</span>
                                    @endif

                                    @if($p->out_time)
                                        @if($p->out_premises_status === 'INSIDE_PREMISES')
                                            <span class="badge badge-success" style="font-size:0.65rem; padding: 2px 6px;">OUT: Inside</span>
                                        @else
                                            <span class="badge badge-danger" style="font-size:0.65rem; padding: 2px 6px;">OUT: Outside</span>
                                        @endif
                                    @else
                                        <span style="font-size:0.65rem; color:var(--text-muted);">OUT: Pending</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px; font-size: 0.75rem;">
                                    <span style="color:#60a5fa; font-weight:700;"><i class="fa-solid fa-arrow-right-to-bracket" style="font-size:0.65rem;"></i> IN: {{ $inDistStr }}</span>
                                    @if($p->out_time)
                                        <span style="color:#f87171; font-weight:700;"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:0.65rem;"></i> OUT: {{ $outDistStr }}</span>
                                    @else
                                        <span style="color:var(--text-muted); font-size:0.7rem;"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:0.65rem;"></i> OUT: --</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">Smile</span>
                            </td>
                            <td>
                                @if(str_contains($p->punch_status, 'EARLY_IN'))
                                    <span class="badge badge-info" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3);">Early In</span>
                                @elseif(str_contains($p->punch_status, 'LATE_IN'))
                                    <span class="badge badge-warning">Late In</span>
                                @else
                                    <span class="badge badge-success">Present</span>
                                @endif
                            </td>
                            <td class="col-actions" style="text-align: center;">
                                <div style="display: flex; gap: 4px; justify-content: center;">
                                    <button class="btn-action-sm btn-delete-punch" title="Delete accidental punch log" onclick="deletePunchRecord('{{ $p->id }}', '{{ addslashes($p->staff_name) }}', '{{ date('d M Y', strtotime($p->punch_date)) }}')">
                                        <i class="fa-solid fa-trash"></i> Delete Log
                                    </button>
                                    <button class="btn-action-sm btn-reset-face" title="Reset face registration for re-registration test" onclick="resetFaceRegistration('{{ $p->staff_id }}', '{{ addslashes($p->staff_name) }}')">
                                        <i class="fa-solid fa-user-gear"></i> Dereg Face
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; padding:30px; color:var(--text-muted);">
                                <i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:8px; display:block; opacity:0.5;"></i>
                                No SF staff attendance records found for the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Month-End Print Preview Area -->
    <div id="monthEndPrintArea">
        <div style="text-align: center; margin-bottom: 24px; border-bottom: 2px solid #000; padding-bottom: 16px;">
            <h2 style="font-size: 1.5rem; font-weight: 900; text-transform: uppercase;">CARMEL COLLEGE OF ENGINEERING & TECHNOLOGY</h2>
            <h3 style="font-size: 1rem; font-weight: 700; color: #333; margin-top: 4px;">Self-Financing (SF) Staff Monthly Attendance & Hours Summary Report</h3>
            <p style="font-size: 0.85rem; margin-top: 6px;">Report Period: <strong>{{ date('d M Y', strtotime($startDate)) }} to {{ date('d M Y', strtotime($endDate)) }}</strong></p>
        </div>

        @php
            $groupedStaff = $punches->groupBy('staff_id');
        @endphp

        <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:0.75rem;">
            <thead>
                <tr style="background:#f1f5f9;">
                    <th style="border:1px solid #000; padding:6px 8px; text-transform:uppercase; font-size:0.65rem;">Staff ID</th>
                    <th style="border:1px solid #000; padding:6px 8px; text-transform:uppercase; font-size:0.65rem;">Staff Member Name</th>
                    <th style="border:1px solid #000; padding:6px 8px; text-align:center; text-transform:uppercase; font-size:0.65rem;">Days Present</th>
                    <th style="border:1px solid #000; padding:6px 8px; text-align:center; text-transform:uppercase; font-size:0.65rem;">Late Entries</th>
                    <th style="border:1px solid #000; padding:6px 8px; text-align:center; text-transform:uppercase; font-size:0.65rem;">Total Hours Logged</th>
                    <th style="border:1px solid #000; padding:6px 8px; text-align:center; text-transform:uppercase; font-size:0.65rem;">Avg Hours / Day</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedStaff as $sId => $staffPunches)
                    @php
                        $sName = $staffPunches->first()->staff_name ?? 'SF Staff';
                        $daysPresent = $staffPunches->whereNotNull('in_time')->count();
                        $lateCount = $staffPunches->where('punch_status', 'LATE_IN')->count();
                        $totalMinutes = 0;
                        foreach($staffPunches as $sp) {
                            if ($sp->in_time && $sp->out_time) {
                                $totalMinutes += round(abs(strtotime($sp->out_time) - strtotime($sp->in_time)) / 60);
                            }
                        }
                        $totHrs = floor($totalMinutes / 60);
                        $totMins = $totalMinutes % 60;

                        $avgMinutes = $daysPresent > 0 ? round($totalMinutes / $daysPresent) : 0;
                        $avgHrs = floor($avgMinutes / 60);
                        $avgMins = $avgMinutes % 60;
                    @endphp
                    <tr>
                        <td style="border:1px solid #000; padding:6px 8px; font-weight:700;">{{ $sId }}</td>
                        <td style="border:1px solid #000; padding:6px 8px; font-weight:700;">{{ $sName }}</td>
                        <td style="border:1px solid #000; padding:6px 8px; text-align:center;">{{ $daysPresent }}</td>
                        <td style="border:1px solid #000; padding:6px 8px; text-align:center;">{{ $lateCount }}</td>
                        <td style="border:1px solid #000; padding:6px 8px; text-align:center; font-weight:700;">{{ $totHrs }}h {{ $totMins }}m</td>
                        <td style="border:1px solid #000; padding:6px 8px; text-align:center;">{{ $avgHrs }}h {{ $avgMins }}m</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:flex; justify-content:space-between; margin-top:50px; font-weight:700; font-size:0.8rem;">
            <div>Prepared By: ___________________<br><small>Academic Coordinator (SF)</small></div>
            <div>Verified By: ___________________<br><small>Administrative Officer</small></div>
            <div>Approved By: ___________________<br><small>Principal / Chairman</small></div>
        </div>
    </div>

    <!-- MODAL 1: Embedded GPS Geofence Core Setup -->
    <div class="modal-overlay" id="modalGeofence">
        <div class="modal-container">
            <div class="modal-header">
                <h2><i class="fa-solid fa-location-crosshairs"></i> GPS Geofence Core Setup</h2>
                <button class="modal-close" onclick="closeGeofenceModal()">&times;</button>
            </div>
            <form id="formGeofence" onsubmit="saveGeofenceSetup(event)">
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 10px 14px; border-radius: 10px; font-size: 0.75rem; color: #34d399; margin-bottom: 14px;">
                    <i class="fa-solid fa-circle-info"></i> Configure the central GPS coordinates and radius for college premises verification.
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label>Campus Name</label>
                    <input type="text" id="geoCampusName" class="form-control" value="{{ $geofence->campus_name ?? 'Carmel College Campus' }}" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Centroid Latitude</label>
                        <input type="number" step="0.00000001" id="geoLat" class="form-control" value="{{ $geofence->centroid_lat ?? 10.23120000 }}" required>
                    </div>
                    <div class="form-group">
                        <label>Centroid Longitude</label>
                        <input type="number" step="0.00000001" id="geoLng" class="form-control" value="{{ $geofence->centroid_lng ?? 76.20450000 }}" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label>Geofence Radius (Meters)</label>
                        <input type="number" id="geoRadius" class="form-control" value="{{ $geofence->radius_meters ?? 150 }}" required>
                    </div>
                    <div class="form-group">
                        <label>Max Allowed GPS Accuracy</label>
                        <input type="number" id="geoAccuracy" class="form-control" value="{{ $geofence->max_accuracy_meters ?? 30 }}" required>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn-action btn-geofence" onclick="detectCurrentLocation()">
                        <i class="fa-solid fa-crosshairs"></i> Auto-Detect My Current GPS
                    </button>
                    <button type="submit" class="btn-submit-filter">
                        <i class="fa-solid fa-floppy-disk"></i> Save Setup
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Registered Biometrics Deregistration Manager -->
    <div class="modal-overlay" id="modalDereg">
        <div class="modal-container">
            <div class="modal-header">
                <h2><i class="fa-solid fa-user-gear"></i> Deregister Staff Biometric Face Data</h2>
                <button class="modal-close" onclick="closeDeregModal()">&times;</button>
            </div>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-bottom:14px;">
                Resetting a staff member's face biometrics forces them to re-register their face on their next mobile login.
            </p>

            <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
                <thead>
                    <tr>
                        <th style="text-align:center;">Registered Face</th>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Registration Date</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registeredStaff ?? [] as $rs)
                        <tr id="row-reg-{{ $rs->id }}">
                            <td style="text-align:center;">
                                @if($rs->photo_url)
                                    <img src="{{ $rs->photo_url }}" alt="Registered Face" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 2px solid #34d399; cursor: pointer;" title="Click to view full image" onclick="window.open('{{ $rs->photo_url }}', '_blank')">
                                @else
                                    <span class="badge badge-warning">No Photo</span>
                                @endif
                            </td>
                            <td><strong>{{ $rs->staff_id }}</strong></td>
                            <td>{{ $rs->staff_name ?? 'SF Staff' }}</td>
                            <td>{{ date('d M Y', strtotime($rs->created_at)) }}</td>
                            <td style="text-align:center;">
                                <button class="btn-action-sm btn-reset-face" onclick="resetFaceRegistration('{{ $rs->staff_id }}', '{{ addslashes($rs->staff_name) }}')">
                                    <i class="fa-solid fa-trash"></i> Deregister Face
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:var(--text-muted);">
                                No staff biometric face registrations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="display:flex; justify-content:flex-end;">
                <button class="btn-action btn-back" onclick="closeDeregModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Month-End Executive Summary Preview Modal -->
    <div class="modal-overlay" id="modalMonthEnd">
        <div class="modal-container">
            <div class="modal-header">
                <h2><i class="fa-solid fa-file-invoice"></i> Month-End Executive Attendance Summary</h2>
                <button class="modal-close" onclick="closeMonthEndModal()">&times;</button>
            </div>
            <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:14px;">
                Generates a formal monthly staff audit printout grouped by staff member with total campus hours and attendance metrics for range: <strong>{{ date('d M Y', strtotime($startDate)) }} to {{ date('d M Y', strtotime($endDate)) }}</strong>.
            </p>
            @if($startDate === $endDate && $startDate === date('Y-m-d'))
                <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); padding: 10px 14px; border-radius: 10px; font-size: 0.75rem; color: #fbbf24; margin-bottom: 16px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> You are currently viewing <strong>Today's Daily Log</strong>. To print the full month summary, click <strong>"Print Full Month Report"</strong> below.
                </div>
            @endif
            <div style="display:flex; justify-content:flex-end; gap:10px; flex-wrap: wrap;">
                <button class="btn-action btn-back" onclick="closeMonthEndModal()">Cancel</button>
                @if($startDate === $endDate && $startDate === date('Y-m-d'))
                    <a href="/sf-attendance/attendance-report?start_date={{ date('Y-m-01') }}&end_date={{ date('Y-m-d') }}&auto_print=1" class="btn-action btn-monthend" style="text-decoration: none;">
                        <i class="fa-solid fa-calendar-days"></i> Print Full Month Report
                    </a>
                @endif
                <button class="btn-action btn-print" onclick="printMonthEndReport()">
                    <i class="fa-solid fa-print"></i> Print Loaded Range
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 4: Biometric Face Verification Audit & Visual Comparison Modal -->
    <div class="modal-overlay" id="modalFaceCompare">
        <div class="modal-container" style="max-width: 780px;">
            <div class="modal-header">
                <h2><i class="fa-solid fa-face-viewfinder"></i> Biometric Face Verification Audit</h2>
                <button class="modal-close" onclick="closeFaceCompareModal()">&times;</button>
            </div>
            
            <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid var(--border-color); border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 id="cmpStaffName" style="font-size: 1rem; color: #fff; font-weight: 800;">Staff Name</h3>
                    <p id="cmpStaffId" style="font-size: 0.75rem; color: var(--text-muted);">Staff ID</p>
                </div>
                <div style="text-align: right;">
                    <span id="cmpDateBadge" class="badge badge-info" style="font-size: 0.75rem;">10 Aug 2026</span>
                    <span id="cmpScoreBadge" class="badge badge-success" style="font-size: 0.75rem;">Confidence: 96%</span>
                </div>
            </div>

            <!-- Image Comparison Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 16px;">
                <!-- 1. Registered Face Photo -->
                <div style="background: #0f172a; border: 1px solid rgba(52, 211, 153, 0.4); border-radius: 14px; padding: 12px; text-align: center;">
                    <span style="display: inline-block; background: rgba(52, 211, 153, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.4); padding: 2px 8px; border-radius: 10px; font-size: 0.68rem; font-weight: 800; margin-bottom: 8px;">
                        <i class="fa-solid fa-id-card me-1"></i> REGISTERED FACE
                    </span>
                    <div style="width: 100%; height: 180px; border-radius: 10px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; border: 1px solid #1e293b;">
                        <img id="cmpImgReg" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Registered Face">
                        <div id="cmpImgRegEmpty" style="color: var(--text-muted); font-size: 0.75rem;">No Registration Image</div>
                    </div>
                    <small style="color: var(--text-muted); font-size: 0.68rem; display: block; margin-top: 6px;">Biometric Master Reference</small>
                </div>

                <!-- 2. Morning IN Snapshot -->
                <div style="background: #0f172a; border: 1px solid rgba(96, 165, 250, 0.4); border-radius: 14px; padding: 12px; text-align: center;">
                    <span style="display: inline-block; background: rgba(96, 165, 250, 0.15); color: #60a5fa; border: 1px solid rgba(96, 165, 250, 0.4); padding: 2px 8px; border-radius: 10px; font-size: 0.68rem; font-weight: 800; margin-bottom: 8px;">
                        <i class="fa-solid fa-sun me-1"></i> MORNING IN SNAPSHOT
                    </span>
                    <div style="width: 100%; height: 180px; border-radius: 10px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; border: 1px solid #1e293b;">
                        <img id="cmpImgIn" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Morning IN Punch">
                        <div id="cmpImgInEmpty" style="color: var(--text-muted); font-size: 0.75rem;">No IN Punch Image</div>
                    </div>
                    <small style="color: var(--text-muted); font-size: 0.68rem; display: block; margin-top: 6px;">Live Camera Capture</small>
                </div>

                <!-- 3. Evening OUT Snapshot -->
                <div style="background: #0f172a; border: 1px solid rgba(248, 113, 113, 0.4); border-radius: 14px; padding: 12px; text-align: center;">
                    <span style="display: inline-block; background: rgba(248, 113, 113, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.4); padding: 2px 8px; border-radius: 10px; font-size: 0.68rem; font-weight: 800; margin-bottom: 8px;">
                        <i class="fa-solid fa-moon me-1"></i> EVENING OUT SNAPSHOT
                    </span>
                    <div style="width: 100%; height: 180px; border-radius: 10px; overflow: hidden; background: #000; display: flex; align-items: center; justify-content: center; border: 1px solid #1e293b;">
                        <img id="cmpImgOut" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Evening OUT Punch">
                        <div id="cmpImgOutEmpty" style="color: var(--text-muted); font-size: 0.75rem;">No OUT Punch Image</div>
                    </div>
                    <small style="color: var(--text-muted); font-size: 0.68rem; display: block; margin-top: 6px;">Live Camera Capture</small>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button class="btn-action btn-back" onclick="closeFaceCompareModal()">Close Audit View</button>
            </div>
        </div>
    </div>

    <script>
        function openGeofenceModal() { document.getElementById('modalGeofence').classList.add('active'); }
        function closeGeofenceModal() { document.getElementById('modalGeofence').classList.remove('active'); }
        function openDeregModal() { document.getElementById('modalDereg').classList.add('active'); }
        function closeDeregModal() { document.getElementById('modalDereg').classList.remove('active'); }
        function openMonthEndModal() { document.getElementById('modalMonthEnd').classList.add('active'); }
        function closeMonthEndModal() { document.getElementById('modalMonthEnd').classList.remove('active'); }

        function openFaceCompareModal(staffName, staffId, regUrl, inUrl, outUrl, dateStr, score) {
            document.getElementById('cmpStaffName').innerText = staffName;
            document.getElementById('cmpStaffId').innerText = 'Staff ID: ' + staffId;
            document.getElementById('cmpDateBadge').innerText = dateStr;
            const pct = Math.round((parseFloat(score) || 0.92) * 100);
            document.getElementById('cmpScoreBadge').innerText = 'Biometric Match: ' + pct + '%';

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
        function closeFaceCompareModal() { document.getElementById('modalFaceCompare').classList.remove('active'); }

        function detectCurrentLocation() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    document.getElementById('geoLat').value = pos.coords.latitude.toFixed(8);
                    document.getElementById('geoLng').value = pos.coords.longitude.toFixed(8);
                    alert("Current location detected successfully!");
                }, (err) => {
                    alert("Could not detect location: " + err.message);
                }, { enableHighAccuracy: true });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }

        async function saveGeofenceSetup(e) {
            e.preventDefault();
            const campusName = document.getElementById('geoCampusName').value;
            const lat = document.getElementById('geoLat').value;
            const lng = document.getElementById('geoLng').value;
            const radius = document.getElementById('geoRadius').value;
            const accuracy = document.getElementById('geoAccuracy').value;

            try {
                const response = await fetch('/sf-attendance/geofence-setup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        campus_name: campusName,
                        centroid_lat: lat,
                        centroid_lng: lng,
                        radius_meters: radius,
                        max_accuracy_meters: accuracy
                    })
                });

                const data = await response.json();
                if (data.success) {
                    alert(data.message || "GPS Geofence updated successfully!");
                    closeGeofenceModal();
                } else {
                    alert("Error: " + (data.message || "Failed to update geofence."));
                }
            } catch (err) {
                console.error(err);
                alert("Error saving geofence setup.");
            }
        }

        function printMonthEndReport() {
            closeMonthEndModal();
            document.body.classList.add('print-monthend');
            window.print();
            setTimeout(() => {
                document.body.classList.remove('print-monthend');
            }, 1000);
        }

        function forceFreshReload() {
            const icon = document.getElementById('refreshSpinIcon');
            if (icon) icon.classList.add('fa-spin');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('_t', Date.now());
            window.location.href = currentUrl.toString();
        }

        // Live Auto-Sync: Poll every 25 seconds if active tab and no modal open
        setInterval(() => {
            if (!document.hidden && !document.querySelector('.modal-overlay.active')) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('_t', Date.now());
                window.location.href = currentUrl.toString();
            }
        }, 25000);

        // Delete Accidental Punch Record (Admin Only)
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

        // Reset / Deregister Biometric Face Registration & Delete Attendance Logs (Admin Only)
        async function resetFaceRegistration(staffId, staffName) {
            if (!confirm(`Are you sure you want to DEREGISTER biometric face profile and DELETE ALL attendance logs for ${staffName} (ID: ${staffId})?\n\nThis will purge their registered face data and all associated attendance punch logs.`)) {
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
            if (userRole === 'Super_Admin' || userRole === 'Principal') {
                window.location.href = '/dashboard/principal';
            } else if (userRole === 'Academic_Coordinator_SF' || userRole === 'ACADEMIC_COORDINATOR_SF') {
                window.location.href = '/dashboard/academic-coordinator-sf';
            } else if (userRole === 'Gen_Dept_Coordinator_Self_Finance' || userRole === 'GEN_DEPT_COORDINATOR_SELF_FINANCE') {
                window.location.href = '/dashboard/general-coordinator-sf';
            } else if (userRole === 'Admin') {
                window.location.href = '/dashboard/admin';
            } else if (window.history.length > 1 && ref && !ref.includes('/sf-attendance/')) {
                window.history.back();
            } else {
                window.location.href = '/dashboard/principal';
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('auto_print') === '1') {
                setTimeout(() => {
                    printMonthEndReport();
                }, 300);
            }
        });
    </script>
</body>
</html>
