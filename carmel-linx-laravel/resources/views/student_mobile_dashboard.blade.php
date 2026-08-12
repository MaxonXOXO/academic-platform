<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Carmel Student Mobile Dashboard — {{ $student->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --app-bg: #090d16;
            --card-bg: rgba(15, 23, 42, 0.92);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--app-bg);
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            min-height: 100vh;
            padding-bottom: 120px; /* Ample space to prevent bottom navigation overlap */
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-container {
            max-width: 520px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--app-bg);
            position: relative;
            padding-bottom: 30px;
        }

        /* Mobile Header */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 14px 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5), 0 2px 6px -1px rgba(6, 182, 212, 0.15);
        }

        /* App Cards */
        .app-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
            backdrop-filter: blur(12px);
        }

        /* Attendance Circular Dial */
        .attendance-dial {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--accent-emerald);
            background: rgba(16, 185, 129, 0.08);
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
        }
        .attendance-dial.warning {
            border-color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.08);
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
        }
        .attendance-dial.danger {
            border-color: var(--accent-rose);
            background: rgba(244, 63, 94, 0.08);
            box-shadow: 0 0 20px rgba(244, 63, 94, 0.2);
        }

        /* Timeline Items */
        .timeline-item {
            background: rgba(30, 41, 59, 0.6);
            border-left: 4px solid var(--accent-cyan);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 10px;
        }
        .timeline-item.present { border-left-color: var(--accent-emerald); }
        .timeline-item.absent { border-left-color: var(--accent-rose); }
        .timeline-item.not-marked { border-left-color: #64748b; }
        .timeline-item.special-hour { border-left-color: var(--accent-purple); }

        /* Bottom Mobile Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 520px;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: space-around;
            padding: 10px 6px;
            z-index: 1000;
        }
        .nav-link-mobile {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.78rem;
            font-weight: 700;
            gap: 4px;
            flex: 1;
            text-align: center;
            transition: all 0.2s ease;
        }
        .nav-link-mobile.active {
            color: var(--accent-cyan);
        }
        .nav-link-mobile i {
            font-size: 1.25rem;
        }

        .badge-app {
            font-size: 0.78rem;
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: 700;
        }

        .avatar-mobile {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
        }

        .time-pill {
            font-size: 0.76rem;
            color: #94a3b8;
            font-weight: 600;
            background: rgba(15, 23, 42, 0.6);
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* Stat Mini Card */
        .stat-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 14px;
        }

        .form-control, .form-select {
            font-size: 0.88rem !important;
            padding: 8px 12px;
        }

        .form-label {
            font-size: 0.8rem !important;
            font-weight: 700;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-title {
            font-weight: 900 !important;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        [data-theme="light"] .brand-title {
            background: linear-gradient(135deg, #0284c7 0%, #4f46e5 50%, #7e22ce 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .badge-student-app {
            background-color: rgba(6, 182, 212, 0.18) !important;
            color: #38bdf8 !important;
            border: 1px solid rgba(6, 182, 212, 0.35) !important;
            border-radius: 6px !important;
        }

        .badge-reg {
            background-color: rgba(6, 182, 212, 0.15) !important;
            color: #38bdf8 !important;
            border: 1px solid rgba(6, 182, 212, 0.35) !important;
        }
        [data-theme="light"] .badge-reg {
            background-color: rgba(2, 132, 199, 0.1) !important;
            color: #0284c7 !important;
            border: 1px solid rgba(2, 132, 199, 0.35) !important;
        }

        .badge-sem {
            background-color: rgba(139, 92, 246, 0.15) !important;
            color: #c084fc !important;
            border: 1px solid rgba(139, 92, 246, 0.35) !important;
        }
        [data-theme="light"] .badge-sem {
            background-color: rgba(126, 34, 206, 0.1) !important;
            color: #7e22ce !important;
            border: 1px solid rgba(126, 34, 206, 0.35) !important;
        }

        /* Dark/Light Mode Theme Variations */
        [data-theme="light"] {
            --app-bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: rgba(0, 0, 0, 0.08);
        }
        [data-theme="light"] body,
        [data-theme="light"] .mobile-container {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }
        [data-theme="light"] .mobile-header {
            background: rgba(255, 255, 255, 0.95) !important;
            border-bottom-color: rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.08), 0 2px 6px -1px rgba(2, 132, 199, 0.1) !important;
        }
        [data-theme="light"] .app-card {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
        }
        [data-theme="light"] .text-white {
            color: #0f172a !important;
        }
        [data-theme="light"] .text-secondary {
            color: #64748b !important;
        }
        [data-theme="light"] .text-cyan {
            color: #0284c7 !important;
        }
        [data-theme="light"] .brand-title {
            background: linear-gradient(135deg, #0284c7 0%, #4f46e5 50%, #7e22ce 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        [data-theme="light"] .badge-student-app {
            background-color: rgba(2, 132, 199, 0.12) !important;
            color: #0369a1 !important;
            border: 1px solid rgba(2, 132, 199, 0.35) !important;
        }
        [data-theme="light"] .bg-dark {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        [data-theme="light"] .bottom-nav {
            background: rgba(255, 255, 255, 0.96) !important;
            border-top-color: rgba(0, 0, 0, 0.08) !important;
        }
        [data-theme="light"] .timeline-item {
            background: #f1f5f9 !important;
        }
        [data-theme="light"] .table-dark {
            background-color: #ffffff !important;
            color: #0f172a !important;
        }
        [data-theme="light"] .stat-card {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
        }
        .theme-toggle-btn {
            border: 1px solid var(--card-border);
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }
        [data-theme="light"] .theme-toggle-btn {
            background: #e2e8f0;
            color: #0f172a;
            border-color: #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="mobile-container">

        <!-- Top App Bar -->
        <div class="mobile-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('logo.jpg') }}" alt="Carmel Linx" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 1.5px solid #06b6d4; box-shadow: 0 0 10px rgba(6, 182, 212, 0.4);">
                <div class="ms-1">
                    <h5 class="fw-black mb-0 brand-title" style="font-size: 1.25rem;">Carmel Linx</h5>
                    <div class="d-flex align-items-center mt-0.5">
                        <span class="badge badge-student-app fw-extrabold px-2.5 py-1" style="font-size: 0.82rem; letter-spacing: 0.2px;">
                            <i class="fa-solid fa-user-graduate me-1"></i> Student App
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <button onclick="toggleTheme()" class="btn btn-sm px-2 py-1 rounded-pill fw-bold theme-toggle-btn" style="font-size: 0.7rem;" title="Toggle Light / Dark Mode">
                    <i id="themeIcon" class="fa-solid fa-sun text-warning"></i>
                </button>
                <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to logout?')" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-pill" style="font-size: 0.7rem;" title="Sign Out">
                    <i class="fa-solid fa-power-off"></i> Sign Out
                </a>
            </div>
        </div>

        <!-- Scrollable Main View Container -->
        <div class="p-3">

            <!-- Student Profile Header Card -->
            <div class="app-card">
                <div class="d-flex align-items-center gap-3">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="avatar-mobile">
                    @else
                        <div class="avatar-mobile bg-dark text-cyan d-flex align-items-center justify-content-center fw-extrabold fs-5">
                            {{ strtoupper(substr($student->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fw-extrabold text-white mb-0 text-truncate" style="font-size: 1rem;">{{ $student->name }}</h6>
                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                            <span class="badge badge-reg badge-app">Reg: {{ $student->reg_no }}</span>
                            <span class="badge badge-sem badge-app">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                        </div>
                    </div>
                </div>

                @if($tutor && $tutor->name)
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                    <span class="text-secondary small" style="font-size: 0.78rem;">Class Tutor: <strong class="text-white">{{ $tutor->name }}</strong></span>
                    @if($tutor->mobile_no)
                    <a href="tel:{{ $tutor->mobile_no }}" class="btn btn-sm btn-success px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-phone me-1"></i> Call Tutor
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- TAB 1: HOME / DASHBOARD OVERVIEW -->
            <div id="tab-home" class="tab-pane fade-in">
                
                <!-- Urgent Evening Pre-Class Alert Banner (Mobile) -->
                <div id="mobilePreClassAlertBanner" class="app-card border border-warning border-opacity-50 bg-gradient d-none mb-3" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(15, 23, 42, 0.95) 100%);">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="p-2 rounded-3 bg-warning bg-opacity-20 text-warning">
                            <i class="fa-solid fa-bolt fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="badge bg-warning text-dark fw-black" style="font-size: 0.68rem; letter-spacing: 0.3px;">⚡ Pre-Class Alert</span>
                                <small id="mobileAlertTargetDate" class="text-warning font-mono" style="font-size: 0.68rem;"></small>
                            </div>
                            <h6 id="mobileAlertTitle" class="fw-bold text-white mb-1 text-truncate" style="font-size: 0.88rem;"></h6>
                            <p id="mobileAlertInstruction" class="text-secondary mb-2" style="font-size: 0.75rem; line-height: 1.3;"></p>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" onclick="openMobileMaterialsModal()" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-folder-open me-1"></i> Open Study Vault
                                </button>
                                <button type="button" onclick="acknowledgeMobileVlmNotice()" id="btnAckMobileVlm" class="btn btn-sm btn-outline-light px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                                    Acknowledge
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Overview Cards -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="stat-card border-start border-2 border-info">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-secondary uppercase" style="font-size: 0.68rem; font-weight: 700;">Attendance</span>
                                <i class="fa-solid fa-chart-pie text-cyan"></i>
                            </div>
                            <h5 class="fw-extrabold mb-0 {{ $overallAttendancePct >= 75 ? 'text-emerald-400' : 'text-amber-400' }}" style="font-size: 1.2rem;">
                                {{ number_format($overallAttendancePct, 1) }}%
                            </h5>
                            <small class="text-secondary" style="font-size: 0.68rem;">Periods 1–6 Standard</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card border-start border-2 border-emerald-500">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-secondary uppercase" style="font-size: 0.68rem; font-weight: 700;">Attended Hours</span>
                                <i class="fa-solid fa-user-check text-emerald-400"></i>
                            </div>
                            <h5 class="fw-extrabold text-white mb-0" style="font-size: 1.2rem;">
                                {{ $totalAttendedClasses }} <span class="text-secondary fs-6 fw-normal">/ {{ $totalConductedClasses }}</span>
                            </h5>
                            <small class="text-secondary" style="font-size: 0.68rem;">Total Conducted Hours</small>
                        </div>
                    </div>
                </div>

                <!-- Today's Timetable Preview -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-clock me-1 text-cyan"></i> Today's Schedule & Attendance
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-dark fw-black px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); font-size: 1.1rem; font-weight: 900; border-radius: 10px; letter-spacing: 0.5px; box-shadow: 0 0 14px rgba(56, 189, 248, 0.4);">
                                <i class="fa-solid fa-calendar-day fs-6"></i> {{ $activeDayOrder ?? 'Day 1' }}
                            </span>
                            <small class="text-secondary fw-semibold" style="font-size: 0.75rem;">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                        </div>
                    </div>

                    @foreach($hourlyStatus as $pNum => $pData)
                    <div class="timeline-item {{ $pNum === 7 ? 'special-hour' : strtolower(str_replace(' ', '-', $pData['status'])) }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-1.5 mb-1">
                                    <span class="badge {{ $pNum === 7 ? 'bg-purple text-white' : 'bg-secondary' }}" style="font-size: 0.68rem;">P{{ $pNum }}</span>
                                    <span class="time-pill">
                                        <i class="fa-regular fa-clock me-1"></i>{{ $pData['time_slot'] }}
                                    </span>
                                </div>
                                <strong class="text-white d-block" style="font-size: 0.85rem;">{{ $pData['subject_name'] }}</strong>
                                <small class="text-secondary d-block mt-0.5" style="font-size: 0.72rem;">
                                    {{ $pData['topic'] }}
                                </small>
                            </div>
                            <span class="badge {{ $pData['badge_class'] }} badge-app">
                                {{ $pData['status'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Quick Action Shortcuts -->
                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">Quick Shortcuts</h6>
                    <div class="row g-2">
                        <div class="col-4">
                            <button type="button" onclick="openMobileMaterialsModal()" class="btn btn-dark border border-warning border-opacity-30 w-full text-start p-2 rounded-3 d-flex flex-column align-items-center text-center text-decoration-none">
                                <i class="fa-solid fa-folder-open text-warning fs-5 mb-1"></i>
                                <strong class="text-white d-block" style="font-size: 0.74rem;">Study Vault</strong>
                                <small class="text-secondary" style="font-size: 0.62rem;">Pre-Class Notes</small>
                            </button>
                        </div>
                        <div class="col-4">
                            <a href="/student/mentoring-diary" class="btn btn-dark border border-secondary border-opacity-25 w-full text-start p-2 rounded-3 d-flex flex-column align-items-center text-center text-decoration-none">
                                <i class="fa-solid fa-book-open text-cyan fs-5 mb-1"></i>
                                <strong class="text-white d-block" style="font-size: 0.74rem;">Mentoring</strong>
                                <small class="text-secondary" style="font-size: 0.62rem;">Bio & Notes</small>
                            </a>
                        </div>
                        <div class="col-4">
                            <button type="button" onclick="toggleMobileLeaveForm()" class="btn btn-dark border border-secondary border-opacity-25 w-full text-start p-2 rounded-3 d-flex flex-column align-items-center text-center text-decoration-none">
                                <i class="fa-solid fa-calendar-minus text-info fs-5 mb-1"></i>
                                <strong class="text-white d-block" style="font-size: 0.74rem;">Apply Leave</strong>
                                <small class="text-secondary" style="font-size: 0.62rem;">Submit Request</small>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB 2: ATTENDANCE DETAILED REVIEW -->
            <div id="tab-attendance" class="tab-pane d-none fade-in">
                
                <!-- Hero Attendance Gauge -->
                <div class="app-card text-center">
                    <span class="text-secondary uppercase text-[11px] fw-bold d-block mb-2">Overall Attendance (6 Working Hours)</span>
                    <div class="attendance-dial {{ $overallAttendancePct >= 75 ? '' : ($overallAttendancePct >= 65 ? 'warning' : 'danger') }}">
                        <span class="fw-extrabold fs-4 {{ $overallAttendancePct >= 75 ? 'text-emerald-400' : ($overallAttendancePct >= 65 ? 'text-amber-400' : 'text-rose-400') }}">
                            {{ number_format($overallAttendancePct, 1) }}%
                        </span>
                    </div>
                    <div class="mt-2">
                        <span class="badge {{ $overallAttendancePct >= 75 ? 'bg-success' : ($overallAttendancePct >= 65 ? 'bg-warning text-dark' : 'bg-danger') }} badge-app">
                            {{ $overallAttendancePct >= 75 ? 'Good Standing (Eligible for Exams)' : ($overallAttendancePct >= 65 ? 'Warning: Low Attendance' : 'Critical: Condonation Alert') }}
                        </span>
                    </div>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Attended: <strong>{{ $totalAttendedClasses }}</strong> / Total Conducted: <strong>{{ $totalConductedClasses }}</strong> Hours
                    </small>
                </div>

                <!-- Subject-Wise Attendance Breakdown Table -->
                <div class="app-card">
                    <h6 class="fw-bold text-info mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-layer-group me-1"></i> Subject-Wise Breakdown
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.78rem;">
                            <thead>
                                <tr class="text-secondary border-bottom border-secondary border-opacity-25">
                                    <th>Subject</th>
                                    <th class="text-center">Attended</th>
                                    <th class="text-center">Conducted</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjectStats as $stat)
                                <tr class="border-bottom border-secondary border-opacity-10">
                                    <td>
                                        <strong class="text-white d-block">{{ $stat['subject_code'] }}</strong>
                                        <small class="text-secondary">{{ $stat['subject_name'] }}</small>
                                    </td>
                                    <td class="text-center fw-bold text-emerald-400">{{ $stat['attended'] }} hrs</td>
                                    <td class="text-center text-slate-300">{{ $stat['conducted'] }} hrs</td>
                                    <td class="text-end">
                                        <span class="badge {{ $stat['percentage'] >= 75 ? 'bg-success' : ($stat['percentage'] >= 65 ? 'bg-warning text-dark' : 'bg-danger') }} badge-app">
                                            {{ number_format($stat['percentage'], 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-3">No subject logs recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- TAB 3: TASKS & WORKS TO DO -->
            <div id="tab-tasks" class="tab-pane d-none fade-in">
                
                <!-- Quick Tasks Summary Badges -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="stat-card border-start border-2 border-info">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-secondary uppercase" style="font-size: 0.68rem; font-weight: 700;">Assignments</span>
                                <i class="fa-solid fa-file-pen text-cyan"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-secondary" style="font-size: 0.7rem;">Active: <strong class="text-white" id="mStatAssignActive">0</strong></small>
                                <small class="text-secondary" style="font-size: 0.7rem;">Done: <strong class="text-emerald-400" id="mStatAssignDone">0</strong></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card border-start border-2 border-warning">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-secondary uppercase" style="font-size: 0.68rem; font-weight: 700;">Written Tests</span>
                                <i class="fa-solid fa-pen-to-square text-warning"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-secondary" style="font-size: 0.7rem;">Active: <strong class="text-white" id="mStatWrittenActive">0</strong></small>
                                <small class="text-secondary" style="font-size: 0.7rem;">Done: <strong class="text-cyan" id="mStatWrittenDone">0</strong></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Feedback Surveys Section (Rendered dynamically) -->
                <div id="mobileSurveysContainer" class="d-none mb-3"></div>

                <!-- Online MCQ Tests Card -->
                <div class="app-card">
                    <h6 class="fw-bold text-purple mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-laptop-code me-1"></i> Online MCQ Tests
                    </h6>
                    <div class="space-y-2">
                        @forelse($activeTests as $test)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.85rem;">{{ $test->test_name ?? ($test->title ?? 'Online Test') }}</strong>
                                <small class="text-secondary" style="font-size: 0.72rem;">Duration: {{ $test->duration ?? 30 }} mins | Questions: {{ $test->mcq_count ?? 10 }}</small>
                            </div>
                            <a href="/student/online-tests/{{ $test->test_id ?? $test->id }}/start" class="btn btn-sm btn-purple px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 0.72rem; background-color: #8b5cf6;">
                                Launch <i class="fa-solid fa-play ms-1"></i>
                            </a>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                            No active MCQ tests published at the moment.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Written Tests & Assignments Container -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-pen-nib me-1"></i> Assignments & Written Submissions
                    </h6>
                    <div id="mobileTasksList" class="space-y-2">
                        <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                            Loading active assignments...
                        </div>
                    </div>
                </div>

                <!-- Subject Progress & Syllabus Completion -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-book-bookmark me-1"></i> Subject Class & Syllabus Progress
                    </h6>
                    <div id="mobileSubjectProgressContainer" class="space-y-2">
                        <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                            Loading subject completion progress...
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB 4: STATS & ACTIVITY POINTS -->
            <div id="tab-stats" class="tab-pane d-none fade-in">
                
                <!-- GPA & Academic Stats -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Academic Progress & GPA
                    </h6>
                    <div id="mobileAcademicStatsContent">
                        <div class="text-center text-secondary py-3 animate-pulse" style="font-size: 0.78rem;">
                            Fetching cumulative GPA & semester stats...
                        </div>
                    </div>
                </div>

                <!-- Activity Points Goal Tracker -->
                @php
                    $isLet = session('userAdmissionType') === 'LET';
                    $activityGoal = $isLet ? 40 : 60;
                @endphp
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-amber mb-0" style="font-size: 0.9rem; color: #f59e0b;">
                            <i class="fa-solid fa-star me-1"></i> Activity Points (Goal: {{ $activityGoal }})
                        </h6>
                        <button type="button" onclick="toggleActivityForm()" class="btn btn-sm btn-outline-warning px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">
                            <i class="fa-solid fa-plus me-1"></i> Claim Points
                        </button>
                    </div>

                    <!-- Collapsible Activity Claim Form -->
                    <div id="mobileActivityFormCard" class="d-none bg-dark bg-opacity-60 p-3 rounded-3 border border-secondary border-opacity-25 mb-3">
                        <h6 class="fw-bold text-white mb-2" style="font-size: 0.82rem;">Submit Activity Claim</h6>
                        <form id="mobileActivityClaimForm" onsubmit="submitMobileActivityClaim(event)">
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Semester</label>
                                <select name="semester" required class="form-select form-select-sm bg-slate-900 text-white border-secondary border-opacity-25">
                                    <option value="1">Sem 1</option>
                                    <option value="2">Sem 2</option>
                                    <option value="3">Sem 3</option>
                                    <option value="4">Sem 4</option>
                                    <option value="5">Sem 5</option>
                                    <option value="6">Sem 6</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Activity Segment</label>
                                <select name="activity_segment" required class="form-select form-select-sm bg-slate-900 text-white border-secondary border-opacity-25">
                                    <option value="NCC">NCC</option>
                                    <option value="NSS">NSS</option>
                                    <option value="Sports & Games">Sports & Games</option>
                                    <option value="Cultural Activities">Cultural Activities</option>
                                    <option value="Professional Self Initiatives">Prof. Self Initiatives</option>
                                    <option value="Entrepreneurship and Innovation">Entrepreneurship & Innovation</option>
                                    <option value="Leadership & Management">Leadership & Management</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Activity Name</label>
                                <input type="text" name="activity_name" required placeholder="e.g. 1st Prize in Inter-Poly Arts" class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25">
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Level</label>
                                <select name="level" required class="form-select form-select-sm bg-slate-900 text-white border-secondary border-opacity-25">
                                    <option value="Level I - College">Level I - College</option>
                                    <option value="Level II - Zonal">Level II - Zonal</option>
                                    <option value="Level III - State/Univ">Level III - State/Univ</option>
                                    <option value="Level IV - National">Level IV - National</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Points Claimed</label>
                                <input type="number" name="points_claimed" required min="1" max="50" class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Evidence Reference</label>
                                <input type="text" name="document_reference" placeholder="e.g. Hardcopy certificate given to tutor" class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25">
                            </div>
                            <div id="mobileActivityFormStatus" class="d-none small mb-2 font-bold"></div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" onclick="toggleActivityForm()" class="btn btn-sm btn-secondary px-3" style="font-size: 0.75rem;">Cancel</button>
                                <button type="submit" id="btnSubmitMobileActivity" class="btn btn-sm btn-warning px-3 fw-bold" style="font-size: 0.75rem;">Submit Claim</button>
                            </div>
                        </form>
                    </div>

                    <!-- Verified Claims List -->
                    <div id="mobileActivityClaimsList" class="space-y-2">
                        <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                            Loading activity claims...
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB 5: LEAVE APPLICATION & HISTORY -->
            <div id="tab-leave" class="tab-pane d-none fade-in">
                
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-warning mb-0" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-file-signature me-1"></i> Leave Requests & History
                        </h6>
                        <button type="button" onclick="toggleMobileLeaveForm()" class="btn btn-sm btn-outline-warning px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-plus me-1"></i> Apply Leave
                        </button>
                    </div>

                    <!-- Collapsible Leave Application Form -->
                    <div id="mobileLeaveFormCard" class="d-none bg-dark bg-opacity-60 p-3 rounded-3 border border-secondary border-opacity-25 mb-3">
                        <h6 class="fw-bold text-white mb-2" style="font-size: 0.82rem;">New Leave Application</h6>
                        <form id="mobileLeaveForm" onsubmit="submitStudentMobileLeave(event)">
                            <input type="hidden" name="semester" value="{{ $student->semester }}">
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Leave Date</label>
                                <input type="date" name="leave_date" required class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Number of Days</label>
                                <select name="no_of_days" required class="form-select form-select-sm bg-slate-900 text-white border-secondary border-opacity-25">
                                    <option value="1">1 Day (Full Day)</option>
                                    <option value="0.5">0.5 Day (Half Day)</option>
                                    <option value="2">2 Days</option>
                                    <option value="3">3 Days</option>
                                    <option value="4">4 Days</option>
                                    <option value="5">5 Days</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Reason for Absence</label>
                                <textarea name="reason" required rows="2" placeholder="State valid reason (Medical, Emergency)..." class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25" style="font-size: 0.78rem;"></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="parent_informed" id="chkParentInformedMobile" value="1">
                                <label class="form-check-label text-secondary" for="chkParentInformedMobile" style="font-size: 0.75rem;">
                                    Parent / Guardian informed tutor
                                </label>
                            </div>
                            <div id="mobileLeaveFormStatus" class="d-none small mb-2 font-bold"></div>
                            <div class="d-flex justify-end gap-2">
                                <button type="button" onclick="toggleMobileLeaveForm()" class="btn btn-sm btn-secondary px-3" style="font-size: 0.75rem;">Cancel</button>
                                <button type="submit" id="btnSubmitMobileLeave" class="btn btn-sm btn-warning px-3 fw-bold" style="font-size: 0.75rem;">Submit to Tutor</button>
                            </div>
                        </form>
                    </div>

                    <!-- Recent Leave Applications List -->
                    <div class="space-y-2">
                        @forelse($leaveRecords as $record)
                        <div class="p-2.5 rounded-3 bg-dark bg-opacity-40 border border-secondary border-opacity-25 d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold text-white" style="font-size: 0.82rem;">
                                        <i class="fa-regular fa-calendar-minus me-1 text-warning"></i>{{ \Carbon\Carbon::parse($record->leave_date)->format('d M Y') }}
                                    </span>
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $record->no_of_days }} {{ $record->no_of_days == 1 ? 'Day' : 'Days' }}</span>
                                </div>
                                <small class="text-secondary d-block" style="font-size: 0.72rem;">Reason: {{ $record->reason }}</small>
                                <small class="text-slate-400 d-block mt-0.5" style="font-size: 0.68rem;">
                                    {{ $record->parent_informed ? '✓ Parent Informed' : 'Parent Not Informed' }}
                                </small>
                            </div>
                            <div class="text-end">
                                @if(strtolower($record->status) === 'approved')
                                    <span class="badge bg-success text-white badge-app">Approved</span>
                                @elseif(strtolower($record->status) === 'rejected')
                                    <span class="badge bg-danger text-white badge-app">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark badge-app">Pending Review</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                            No leave applications submitted yet.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- TAB 6: PROFILE & ACCOUNT SETTINGS -->
            <div id="tab-profile" class="tab-pane d-none fade-in">
                
                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-user-gear me-1 text-cyan"></i> Profile & SBTE Info
                    </h6>
                    <div class="p-3 bg-dark rounded-3 border border-secondary border-opacity-25 mb-3">
                        <div class="mb-2">
                            <span class="text-secondary d-block" style="font-size: 0.7rem;">Register Number</span>
                            <strong class="text-white font-monospace" style="font-size: 0.88rem;">{{ session('userId') }}</strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-secondary d-block" style="font-size: 0.7rem;">Branch & Classroom</span>
                            <strong class="text-white" style="font-size: 0.85rem;">{{ session('userBranch') }} ({{ session('classroomId') }})</strong>
                        </div>
                        <div>
                            <span class="text-secondary d-block" style="font-size: 0.7rem;">SBTE Exam Register No</span>
                            <strong class="text-amber-400 font-monospace" style="font-size: 0.85rem;">{{ session('sbteRegNo') ?: 'Not Set' }}</strong>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="p-3 bg-dark rounded-3 border border-secondary border-opacity-25">
                        <h6 class="fw-bold text-white mb-2" style="font-size: 0.82rem;">Update Account Password</h6>
                        <div class="mb-2">
                            <input type="password" id="mOldPwd" placeholder="Current Password" class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25">
                        </div>
                        <div class="mb-3">
                            <input type="password" id="mNewPwd" placeholder="New Password (min 6 chars)" class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25">
                        </div>
                        <div id="mPwdAlert" class="d-none small mb-2 font-bold"></div>
                        <button type="button" onclick="updateMobilePassword()" class="btn btn-sm btn-cyan w-full fw-bold text-dark" style="font-size: 0.75rem;">
                            Update Password
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Mobile Navigation Bar -->
        <div class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchMobileTab(event, 'tab-home')">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchMobileTab(event, 'tab-attendance')">
                <i class="fa-solid fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchMobileTab(event, 'tab-tasks')">
                <i class="fa-solid fa-list-check"></i>
                <span>Tasks</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchMobileTab(event, 'tab-stats')">
                <i class="fa-solid fa-chart-line"></i>
                <span>Stats</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchMobileTab(event, 'tab-leave')">
                <i class="fa-solid fa-calendar-minus"></i>
                <span>Leave</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchMobileTab(event, 'tab-profile')">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
        </div>

    </div>

    <!-- Mobile Dashboard Logic -->
    <script>
        function switchMobileTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            const target = document.getElementById(tabId);
            if (target) target.classList.remove('d-none');
            e.currentTarget.classList.add('active');

            if (tabId === 'tab-tasks') {
                loadMobileWorksToDo();
            } else if (tabId === 'tab-stats') {
                loadMobileAcademicReport();
                loadMobileActivityPoints();
            }
        }

        function loadMobileWorksToDo() {
            fetch('/api/student/academic-report')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        // 1. Stats
                        if (data.stats) {
                            document.getElementById('mStatAssignActive').innerText = data.stats.assignments_active || 0;
                            document.getElementById('mStatAssignDone').innerText = data.stats.assignments_submitted || 0;
                            document.getElementById('mStatWrittenActive').innerText = data.stats.written_tests_active || 0;
                            document.getElementById('mStatWrittenDone').innerText = data.stats.written_tests_submitted || 0;
                        }

                        // 2. Active Surveys
                        const surveysContainer = document.getElementById('mobileSurveysContainer');
                        if (data.active_surveys && data.active_surveys.length > 0) {
                            let sHtml = '';
                            data.active_surveys.forEach(srv => {
                                const isExit = srv.type === 'Course Exit';
                                const title = isExit ? 'Course Exit Feedback Survey Active' : 'Mid-Semester Feedback Survey Active';
                                const link = isExit ? `/student/course-exit/${srv.survey_id}` : `/student/survey/${srv.survey_id}`;
                                sHtml += `
                                    <div class="app-card border-start border-3 ${isExit ? 'border-info' : 'border-warning'}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <strong class="text-white d-block" style="font-size: 0.84rem;"><i class="fa-solid fa-comments me-1 ${isExit ? 'text-info' : 'text-warning'}"></i> ${title}</strong>
                                                <small class="text-secondary" style="font-size: 0.72rem;">${srv.subject_code} — ${srv.subject_name}</small>
                                            </div>
                                            <a href="${link}" target="_blank" class="btn btn-sm ${isExit ? 'btn-outline-info' : 'btn-outline-warning'} px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                Take Survey <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                `;
                            });
                            surveysContainer.innerHTML = sHtml;
                            surveysContainer.classList.remove('d-none');
                        } else {
                            surveysContainer.innerHTML = '';
                            surveysContainer.classList.add('d-none');
                        }

                        // 3. Active Tasks
                        const tasksList = document.getElementById('mobileTasksList');
                        if (data.active_tasks && data.active_tasks.length > 0) {
                            let tHtml = '';
                            data.active_tasks.forEach((t, idx) => {
                                const isExp = t.status === 'Expired' || t.status === 'Completed';
                                const badgeClass = isExp ? 'bg-danger' : 'bg-info text-dark';
                                let qListHtml = '';
                                if (t.questions && t.questions.length > 0) {
                                    qListHtml = `
                                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-none" id="mTaskQ_${idx}">
                                            <strong class="text-secondary uppercase d-block mb-1" style="font-size:0.68rem;">Questions:</strong>
                                            <ul class="text-slate-300 ps-3 mb-0" style="font-size:0.75rem;">
                                                ${t.questions.map(q => `<li>${q}</li>`).join('')}
                                            </ul>
                                        </div>
                                    `;
                                }
                                let submitBtn = '';
                                if (t.type === 'Assignment' && !isExp) {
                                    submitBtn = `
                                        <button onclick="submitMobileTaskDone('${t.subject_code}', '${t.co_tag}', 'Assignment')" class="btn btn-sm btn-info w-100 mt-2 fw-bold text-dark" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-check me-1"></i> Mark as Submitted
                                        </button>
                                    `;
                                }
                                tHtml += `
                                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div>
                                                <strong class="text-white d-block" style="font-size: 0.84rem;">${t.type} — ${t.co_tag}</strong>
                                                <small class="text-cyan d-block" style="font-size: 0.72rem;">${t.subject_code} - ${t.subject}</small>
                                            </div>
                                            <span class="badge ${badgeClass} badge-app">${t.status}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-secondary mt-1" style="font-size: 0.7rem;">
                                            <span>Deadline: <strong class="text-white">${t.deadline ? new Date(t.deadline).toLocaleDateString() : '-'}</strong></span>
                                            ${t.questions && t.questions.length > 0 ? `<a href="#" onclick="event.preventDefault(); document.getElementById('mTaskQ_${idx}').classList.toggle('d-none')" class="text-info text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Questions</a>` : ''}
                                        </div>
                                        ${qListHtml}
                                        ${submitBtn}
                                    </div>
                                `;
                            });
                            tasksList.innerHTML = tHtml;
                        } else {
                            tasksList.innerHTML = '<div class="text-center text-secondary py-3" style="font-size: 0.78rem;">No active pending assignments or written tests.</div>';
                        }

                        // 4. Subject Progress
                        const subjContainer = document.getElementById('mobileSubjectProgressContainer');
                        if (data.subject_progress && data.subject_progress.length > 0) {
                            let spHtml = '';
                            data.subject_progress.forEach(sp => {
                                spHtml += `
                                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <strong class="text-white d-block" style="font-size: 0.82rem;">${sp.subject_code} - ${sp.subject_name}</strong>
                                                <small class="text-secondary" style="font-size: 0.7rem;"><i class="fa-solid fa-user-tie me-1"></i> ${sp.staff_name}</small>
                                            </div>
                                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">${sp.percentage}%</span>
                                        </div>
                                        <div class="progress bg-slate-900 mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: ${sp.percentage}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between text-secondary mt-1" style="font-size: 0.68rem;">
                                            <span>Sessions: <strong class="text-white">${sp.completed_sessions}</strong> / ${sp.total_sessions} taught</span>
                                        </div>
                                    </div>
                                `;
                            });
                            subjContainer.innerHTML = spHtml;
                        } else {
                            subjContainer.innerHTML = '<div class="text-center text-secondary py-3" style="font-size: 0.78rem;">No subject progress logs recorded.</div>';
                        }
                    }
                })
                .catch(err => {
                    console.error('Error loading mobile works to do:', err);
                });
        }

        function submitMobileTaskDone(subjectCode, coTag, category) {
            if (!confirm('Mark this task as submitted?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/student/tasks/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ subject_code: subjectCode, co_tag: coTag, category: category, status: 'Submitted' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    loadMobileWorksToDo();
                } else {
                    alert(data.message || 'Error updating task.');
                }
            });
        }

        function toggleMobileLeaveForm() {
            const card = document.getElementById('mobileLeaveFormCard');
            card.classList.toggle('d-none');
        }

        function toggleActivityForm() {
            const card = document.getElementById('mobileActivityFormCard');
            card.classList.toggle('d-none');
        }

        function submitStudentMobileLeave(event) {
            event.preventDefault();
            const form = event.target;
            const btn = document.getElementById('btnSubmitMobileLeave');
            const statusDiv = document.getElementById('mobileLeaveFormStatus');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const payload = {
                semester: form.semester.value,
                leave_date: form.leave_date.value,
                no_of_days: form.no_of_days.value,
                reason: form.reason.value,
                parent_informed: form.parent_informed.checked ? 1 : 0,
                status: 'Pending'
            };

            fetch('/api/mentoring/leave/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = 'Submit to Tutor';

                if (data.status === 'SUCCESS') {
                    statusDiv.className = 'small mb-2 font-bold text-success';
                    statusDiv.innerText = 'Leave application submitted successfully!';
                    statusDiv.classList.remove('d-none');
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    statusDiv.className = 'small mb-2 font-bold text-danger';
                    statusDiv.innerText = data.message || 'Failed to submit leave application.';
                    statusDiv.classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Submit to Tutor';
                statusDiv.className = 'small mb-2 font-bold text-danger';
                statusDiv.innerText = 'Network error. Please try again.';
                statusDiv.classList.remove('d-none');
            });
        }

        function loadMobileAcademicReport() {
            const container = document.getElementById('mobileAcademicStatsContent');
            fetch('/api/student/academic-report')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS' && data.overall) {
                        const cgpa = data.overall.cgpa ? parseFloat(data.overall.cgpa).toFixed(2) : '0.00';
                        const classification = data.overall.classification || 'Satisfactory';
                        container.innerHTML = `
                            <div class="text-center p-3 bg-dark rounded-3 border border-secondary border-opacity-25">
                                <span class="text-secondary uppercase text-[11px] fw-bold d-block mb-1">Cumulative GPA</span>
                                <h2 class="fw-extrabold text-cyan mb-1">${cgpa} / 10.0</h2>
                                <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">${classification}</span>
                            </div>
                        `;
                    }
                })
                .catch(() => {
                    container.innerHTML = '<p class="text-secondary text-center small my-2">Academic statistics currently unavailable.</p>';
                });
        }

        function loadMobileActivityPoints() {
            const list = document.getElementById('mobileActivityClaimsList');
            fetch('/api/student/activity-points')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS' && data.claims) {
                        if (data.claims.length === 0) {
                            list.innerHTML = '<p class="text-secondary text-center small my-2">No activity claims submitted yet.</p>';
                            return;
                        }
                        let html = '';
                        data.claims.forEach(c => {
                            let badgeClass = c.status === 'Verified' ? 'bg-success' : (c.status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark');
                            html += `
                                <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong class="text-white d-block" style="font-size:0.82rem;">${c.activity_name}</strong>
                                        <small class="text-secondary" style="font-size:0.7rem;">Segment: ${c.activity_segment} | Claimed: ${c.points_claimed} pts</small>
                                    </div>
                                    <span class="badge ${badgeClass} badge-app">${c.status}</span>
                                </div>
                            `;
                        });
                        list.innerHTML = html;
                    }
                });
        }

        function submitMobileActivityClaim(event) {
            event.preventDefault();
            const form = event.target;
            const btn = document.getElementById('btnSubmitMobileActivity');
            const statusDiv = document.getElementById('mobileActivityFormStatus');
            btn.disabled = true;

            const payload = {
                semester: form.semester.value,
                activity_segment: form.activity_segment.value,
                activity_name: form.activity_name.value,
                level: form.level.value,
                points_claimed: form.points_claimed.value,
                document_reference: form.document_reference.value
            };

            fetch('/api/student/activity-points', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.status === 'SUCCESS') {
                    statusDiv.className = 'small mb-2 font-bold text-success';
                    statusDiv.innerText = 'Claim submitted successfully!';
                    statusDiv.classList.remove('d-none');
                    setTimeout(() => {
                        toggleActivityForm();
                        loadMobileActivityPoints();
                    }, 1000);
                } else {
                    statusDiv.className = 'small mb-2 font-bold text-danger';
                    statusDiv.innerText = data.message || 'Failed to submit claim.';
                    statusDiv.classList.remove('d-none');
                }
            });
        }

        function updateMobilePassword() {
            const oldPwd = document.getElementById('mOldPwd').value;
            const newPwd = document.getElementById('mNewPwd').value;
            const alertDiv = document.getElementById('mPwdAlert');

            if (!oldPwd || !newPwd || newPwd.length < 6) {
                alertDiv.className = 'small mb-2 font-bold text-danger';
                alertDiv.innerText = 'Password must be at least 6 characters.';
                alertDiv.classList.remove('d-none');
                return;
            }

            fetch('/api/admin/user/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ old_password: oldPwd, new_password: newPwd })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    alertDiv.className = 'small mb-2 font-bold text-success';
                    alertDiv.innerText = 'Password updated successfully!';
                    alertDiv.classList.remove('d-none');
                } else {
                    alertDiv.className = 'small mb-2 font-bold text-danger';
                    alertDiv.innerText = data.message || 'Error updating password.';
                }
            });
        }

        function initTheme() {
            const savedTheme = localStorage.getItem('carmel_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
            localStorage.setItem('carmel_theme', currentTheme);
            updateThemeIcon(currentTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                if (theme === 'light') {
                    icon.className = 'fa-solid fa-moon text-primary';
                } else {
                    icon.className = 'fa-solid fa-sun text-warning';
                }
            }
        }

        // LEAVE APPLICATION MODAL HANDLERS
        function openLeaveModal() {
            const modal = document.getElementById('leaveModal');
            if (modal) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('leaveDate').value = today;
                document.getElementById('leaveAlert').classList.add('d-none');
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        }

        function closeLeaveModal() {
            const modal = document.getElementById('leaveModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        }

        function submitLeaveRequest() {
            const semester = document.getElementById('leaveSemester').value;
            const leaveDate = document.getElementById('leaveDate').value;
            const noOfDays = document.getElementById('leaveNoOfDays').value;
            const reason = document.getElementById('leaveReason').value.trim();
            const parentInformed = document.getElementById('leaveParentInformed').checked ? 1 : 0;
            const alertBox = document.getElementById('leaveAlert');
            const submitBtn = document.getElementById('btnSubmitLeave');

            if (!leaveDate || !noOfDays || !reason) {
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Please fill in all required fields.';
                alertBox.classList.remove('d-none');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/api/mentoring/leave/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    semester: parseInt(semester),
                    leave_date: leaveDate,
                    no_of_days: noOfDays.toString(),
                    reason: reason,
                    parent_informed: parentInformed,
                    status: 'Pending'
                })
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit Leave';

                if (data.status === 'SUCCESS') {
                    alertBox.className = 'alert alert-success py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message || 'Leave request submitted successfully!';
                    alertBox.classList.remove('d-none');
                    document.getElementById('leaveApplicationForm').reset();
                    setTimeout(() => {
                        closeLeaveModal();
                    }, 1500);
                } else {
                    alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message || 'Failed to submit leave request.';
                    alertBox.classList.remove('d-none');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit Leave';
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Network error occurred while submitting leave.';
                alertBox.classList.remove('d-none');
            });
        }

        let activeMobileNoticeId = null;

        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
            loadMobilePreClassAlerts();
        });

        function loadMobilePreClassAlerts() {
            fetch('/api/student/materials/pre-class-notices')
                .then(res => res.json())
                .then(data => {
                    const notices = data.notices || data.alerts || [];
                    if (data.success && notices.length > 0) {
                        const notice = notices[0];
                        activeMobileNoticeId = notice.id;
                        document.getElementById('mobileAlertTitle').innerText = (notice.subject_code ? notice.subject_code + ': ' : '') + (notice.title || 'Pre-Class Preparation');
                        document.getElementById('mobileAlertInstruction').innerText = notice.description || notice.pre_class_instruction || 'Study material uploaded for upcoming session.';
                        document.getElementById('mobileAlertTargetDate').innerText = 'Target: ' + (notice.target_class_date || notice.target_date || 'Next Class');
                        document.getElementById('mobilePreClassAlertBanner').classList.remove('d-none');
                    }
                })
                .catch(err => console.error('Mobile VLM alert fetch error:', err));
        }

        function acknowledgeMobileVlmNotice() {
            if (!activeMobileNoticeId) return;
            const btn = document.getElementById('btnAckMobileVlm');
            btn.disabled = true;
            btn.innerText = 'Saving...';

            fetch(`/api/student/materials/${activeMobileNoticeId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success || data.status === 'SUCCESS') {
                    document.getElementById('mobilePreClassAlertBanner').classList.add('d-none');
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'Acknowledge';
            });
        }

        function openMobileMaterialsModal() {
            const modalEl = document.getElementById('mobileMaterialsModal');
            if (window.bootstrap && window.bootstrap.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            } else {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
            }
            fetchMobileVaultMaterials();
        }

        function closeMobileMaterialsModal() {
            const modalEl = document.getElementById('mobileMaterialsModal');
            if (window.bootstrap && window.bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
        }

        function fetchMobileVaultMaterials() {
            const container = document.getElementById('mobileMaterialsList');
            container.innerHTML = '<div class="text-center text-secondary py-4" style="font-size: 0.78rem;"><i class="fa-solid fa-spinner fa-spin me-1"></i> Loading study materials...</div>';

            fetch('/api/student/materials/pre-class-notices')
                .then(res => res.json())
                .then(data => {
                    const notices = data.notices || data.alerts || data.materials || [];
                    if (!data.success || notices.length === 0) {
                        container.innerHTML = `
                            <div class="text-center text-secondary py-4 px-2 bg-dark rounded-3 border border-secondary border-opacity-25">
                                <i class="fa-solid fa-folder-open text-secondary fs-3 mb-2"></i>
                                <p class="mb-1 text-white fw-bold" style="font-size: 0.82rem;">No study materials published yet</p>
                                <small class="text-secondary" style="font-size: 0.7rem;">Study notes, lab guides, and videos uploaded by teachers will appear here.</small>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    notices.forEach(m => {
                        let typeIcon = 'fa-file-lines text-info';
                        if (m.resource_type === 'video' || m.material_type === 'video') typeIcon = 'fa-circle-play text-danger';
                        else if (m.resource_type === 'image' || m.material_type === 'image') typeIcon = 'fa-file-image text-emerald-400';
                        else if (m.resource_type === 'link' || m.material_type === 'link') typeIcon = 'fa-link text-warning';

                        let fileUrl = m.external_url || m.video_url || (m.file_path ? `/storage/${m.file_path}` : '#');
                        let roomBadge = m.room_type ? `<span class="badge bg-secondary text-uppercase ms-1" style="font-size:0.6rem;">${m.room_type}</span>` : '';

                        html += `
                            <div class="app-card border border-secondary border-opacity-25 p-3 mb-2" style="border-radius: 12px; background: rgba(15,23,42,0.6);">
                                <div class="d-flex align-items-start gap-2.5">
                                    <div class="p-2 rounded-3 bg-dark border border-secondary border-opacity-25">
                                        <i class="fa-solid ${typeIcon} fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                            <span class="badge bg-info text-dark font-mono" style="font-size: 0.65rem;">${m.subject_code || 'COURSE'}</span>
                                            ${roomBadge}
                                            <small class="text-secondary" style="font-size: 0.68rem;">Topic ${m.topic_no || m.experiment_or_topic_no || '1'}</small>
                                        </div>
                                        <h6 class="fw-bold text-white mb-1" style="font-size: 0.84rem;">${m.title}</h6>
                                        <p class="text-secondary mb-2" style="font-size: 0.72rem; line-height: 1.3;">${m.description || m.pre_class_instruction || 'Pre-class guidance resource'}</p>
                                        <div class="d-flex align-items-center justify-content-between pt-1">
                                            <small class="text-warning font-mono" style="font-size: 0.68rem;">Target: ${m.target_class_date || m.target_date || 'Upcoming'}</small>
                                            ${fileUrl !== '#' ? `
                                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                                    Open Material <i class="fa-solid fa-up-right-from-square ms-1"></i>
                                                </a>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(err => {
                    console.error('Fetch VLM error:', err);
                    container.innerHTML = '<div class="alert alert-danger py-2 px-3 small font-bold">Failed to load materials.</div>';
                });
        }

        // Prevent back-button viewing after logout
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload(true);
            }
        });
    </script>

    <!-- MOBILE STUDY MATERIALS VAULT MODAL -->
    <div class="modal fade" id="mobileMaterialsModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-dark border border-secondary border-opacity-25 text-white shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary border-opacity-25 py-2.5">
                    <h6 class="modal-title fw-bold text-warning d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-folder-open text-warning"></i> Study Materials & Pre-Class Vault
                    </h6>
                    <button type="button" class="btn-close btn-close-white" onclick="closeMobileMaterialsModal()"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="mobileMaterialsList">
                        <div class="text-center text-secondary py-4" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-spinner fa-spin me-1"></i> Loading study materials...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LEAVE APPLICATION MODAL -->
    <div id="leaveModal" class="modal fade" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-secondary border-opacity-25 text-white shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="modal-title fw-bold text-info" id="leaveModalLabel">
                        <i class="fa-solid fa-paper-plane me-1"></i> Apply for Leave
                    </h6>
                    <button type="button" class="btn-close btn-close-white" onclick="closeLeaveModal()"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="leaveAlert" class="alert d-none py-2 px-3 small font-bold mb-3"></div>
                    <form id="leaveApplicationForm">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Semester</label>
                            <select id="leaveSemester" class="form-select bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ ($student->current_semester ?? 1) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Leave Date</label>
                            <input type="date" id="leaveDate" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Number of Days</label>
                            <input type="number" step="0.5" min="0.5" id="leaveNoOfDays" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" value="1" style="font-size: 0.85rem;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Reason for Leave</label>
                            <textarea id="leaveReason" rows="3" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" placeholder="Provide reason for absence..." style="font-size: 0.85rem;" required></textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="leaveParentInformed" value="1">
                            <label class="form-check-label text-secondary small" for="leaveParentInformed">
                                Parent / Guardian Informed
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" onclick="closeLeaveModal()">Cancel</button>
                    <button type="button" id="btnSubmitLeave" onclick="submitLeaveRequest()" class="btn btn-sm btn-info px-4 rounded-pill fw-bold text-dark" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); border: none;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Leave
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
