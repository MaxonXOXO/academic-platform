<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Parent Portal — {{ $student->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --app-bg: #090d16;
            --card-bg: rgba(17, 24, 39, 0.95);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
        }

        body {
            background-color: var(--app-bg);
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            min-height: 100vh;
            padding-bottom: 75px; /* Space for bottom nav */
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--app-bg);
            position: relative;
        }

        /* Mobile Header */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.5), 0 2px 6px -1px rgba(6, 182, 212, 0.15);
        }

        /* App Cards */
        .app-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.5);
        }

        /* Hero Attendance Circle */
        .attendance-dial {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--accent-emerald);
            background: rgba(16, 185, 129, 0.08);
            margin: 0 auto;
        }
        .attendance-dial.warning {
            border-color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.08);
        }
        .attendance-dial.danger {
            border-color: var(--accent-rose);
            background: rgba(244, 63, 94, 0.08);
        }

        /* Hour Timeline Card */
        .timeline-item {
            background: rgba(30, 41, 59, 0.6);
            border-left: 4px solid var(--accent-cyan);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }
        .timeline-item.present { border-left-color: var(--accent-emerald); }
        .timeline-item.absent { border-left-color: var(--accent-rose); }
        .timeline-item.not-marked { border-left-color: #64748b; }

        /* Bottom Mobile Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(16px);
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 1000;
        }
        .nav-link-mobile {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.68rem;
            font-weight: 600;
            gap: 3px;
        }
        .nav-link-mobile.active {
            color: var(--accent-cyan);
        }
        .nav-link-mobile i {
            font-size: 1.2rem;
        }

        .badge-app {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        .text-cyan { color: #38bdf8 !important; }
        .brand-title {
            font-weight: 900 !important;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .badge-parent-app {
            background-color: rgba(6, 182, 212, 0.18);
            color: #38bdf8;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }

        /* Profile & Status Badges with Border Block */
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

        .badge-status {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #34d399 !important;
            border: 1px solid rgba(16, 185, 129, 0.35) !important;
        }
        [data-theme="light"] .badge-status {
            background-color: rgba(5, 150, 105, 0.1) !important;
            color: #059669 !important;
            border: 1px solid rgba(5, 150, 105, 0.35) !important;
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
            background: rgba(255, 255, 255, 0.94) !important;
            border-bottom-color: rgba(0, 0, 0, 0.08) !important;
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
        [data-theme="light"] .badge-parent-app {
            background-color: rgba(2, 132, 199, 0.12) !important;
            color: #0369a1 !important;
            border: 1px solid rgba(2, 132, 199, 0.3) !important;
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
                        <span class="badge badge-parent-app fw-extrabold px-2 py-0.5" style="font-size: 0.78rem; letter-spacing: 0.2px;">
                            <i class="fa-solid fa-users me-1"></i> Parent App
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <button onclick="toggleTheme()" class="btn btn-sm px-2.5 py-1.5 rounded-pill fw-bold theme-toggle-btn" style="font-size: 0.72rem;" title="Toggle Light / Dark Mode">
                    <i id="themeIcon" class="fa-solid fa-sun text-warning"></i>
                </button>
                <a href="/parent" class="btn btn-sm btn-outline-danger px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-power-off me-1"></i> Sign Out
                </a>
            </div>
        </div>

        <!-- Scrollable Content View -->
        <div class="p-3">

            <!-- Student Profile Header Card -->
            <div class="app-card">
                <div class="d-flex align-items-center gap-3">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="avatar-mobile">
                    @else
                        <div class="avatar-mobile bg-dark text-cyan d-flex align-items-center justify-content-center fw-bold fs-5">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="fw-extrabold text-white mb-0" style="font-size: 1rem;">{{ $student->name }}</h6>
                        <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                            <span class="badge badge-reg badge-app">Reg: {{ $student->reg_no }}</span>
                            <span class="badge badge-sem badge-app">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                            <span class="badge badge-status badge-app">
                                <i class="fa-solid fa-graduation-cap me-1"></i>{{ !empty($academicStatus) ? $academicStatus : 'Regular (Active)' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($tutor && $tutor->mobile_no)
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                    <span class="text-secondary small" style="font-size: 0.78rem;">Advisor: <strong>{{ $tutor->name }}</strong></span>
                    <a href="tel:{{ $tutor->mobile_no }}" class="btn btn-sm btn-success px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-phone me-1"></i> Call Advisor
                    </a>
                </div>
                @endif
            </div>

            <!-- Tab Content 1: Today's Attendance & Hero Gauge -->
            <div id="tab-attendance" class="tab-pane active">
                
                <!-- Hero Attendance Gauge -->
                <div class="app-card text-center">
                    <span class="text-secondary uppercase text-[11px] fw-bold d-block mb-2">Overall Attendance Percentage</span>
                    <div class="attendance-dial {{ $overallAttendancePct >= 75 ? '' : ($overallAttendancePct >= 65 ? 'warning' : 'danger') }}">
                        <span class="fw-extrabold fs-4 {{ $overallAttendancePct >= 75 ? 'text-emerald-400' : ($overallAttendancePct >= 65 ? 'text-amber-400' : 'text-rose-400') }}">
                            {{ number_format($overallAttendancePct, 1) }}%
                        </span>
                    </div>
                    <div class="mt-2">
                        <span class="badge {{ $totalConductedClasses == 0 ? 'bg-secondary' : ($overallAttendancePct >= 75 ? 'bg-success' : ($overallAttendancePct >= 65 ? 'bg-warning text-dark' : 'bg-danger')) }} badge-app">
                            {{ $totalConductedClasses == 0 ? 'No Attendance Marked Yet' : ($overallAttendancePct >= 75 ? 'Good Standing (Eligible)' : ($overallAttendancePct >= 65 ? 'Warning: Low Attendance' : 'Critical: Condonation Alert')) }}
                        </span>
                    </div>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Attended: <strong>{{ $totalAttendedClasses }}</strong> / Total Conducted: <strong>{{ $totalConductedClasses }}</strong> Hours
                    </small>
                </div>

                <!-- Today's Hour-Wise Attendance Grid -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-day me-1 text-cyan"></i> Today's Schedule (Periods 1–6 + Special 7th Hour)
                        </h6>
                        <small class="text-secondary" style="font-size: 0.72rem;">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                    </div>

                    @foreach($hourlyStatus as $pNum => $pData)
                    <div class="timeline-item {{ $pNum === 7 ? 'special-hour border-start border-4 border-purple' : strtolower(str_replace(' ', '-', $pData['status'])) }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge {{ $pNum === 7 ? 'bg-dark text-cyan border border-cyan' : 'bg-secondary' }}" style="font-size: 0.68rem;">P{{ $pNum }}</span>
                                    @if(isset($pData['time_slot']))
                                    <span class="text-secondary font-monospace" style="font-size: 0.72rem;">
                                        <i class="fa-regular fa-clock me-1 text-cyan"></i>{{ $pData['time_slot'] }}
                                    </span>
                                    @endif
                                </div>
                                <strong class="text-white" style="font-size: 0.85rem;">{{ $pData['subject_name'] }}</strong>
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

                <!-- Leave Applications & History Report -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-file-signature me-1 text-warning"></i> Ward Leave History & Applications
                        </h6>
                        <span class="badge bg-warning text-dark badge-app">
                            {{ count($leaveRecords ?? []) }} {{ count($leaveRecords ?? []) == 1 ? 'Record' : 'Records' }}
                        </span>
                    </div>

                    @forelse($leaveRecords as $leave)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-white" style="font-size: 0.82rem;">
                                    <i class="fa-regular fa-calendar-minus me-1 text-warning"></i>{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}
                                </span>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $leave->no_of_days }} {{ $leave->no_of_days == 1 ? 'Day' : 'Days' }}</span>
                            </div>
                            <small class="text-secondary d-block" style="font-size: 0.72rem;">Reason: {{ $leave->reason }}</small>
                            <small class="text-slate-400 d-block mt-0.5" style="font-size: 0.68rem;">
                                {{ !empty($leave->parent_informed) ? '✓ Parent Informed Tutor' : 'Parent Not Informed' }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if(strtolower($leave->status) === 'approved')
                                <span class="badge bg-success text-white badge-app">Approved</span>
                            @elseif(strtolower($leave->status) === 'rejected')
                                <span class="badge bg-danger text-white badge-app">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark badge-app">Pending</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-secondary bg-dark rounded border border-secondary border-opacity-25 small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> No leave applications submitted for this student.
                    </div>
                    @endforelse
                </div>

            </div>

            <!-- Tab Content 2: Ward Academic Status & Performance -->
            <div id="tab-academic" class="tab-pane d-none">
                <!-- Academic Overview Card -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-award me-1 text-warning"></i> Ward Academic Status
                        </h6>
                        <span class="badge bg-success badge-app">
                            <i class="fa-solid fa-circle-check me-1"></i>{{ !empty($academicStatus) ? $academicStatus : 'Regular (Active)' }}
                        </span>
                    </div>

                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">CGPA Score</small>
                                <strong class="text-warning fs-5 fw-extrabold">{{ number_format($cgpa ?? 0.00, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">Current SGPA</small>
                                <strong class="text-cyan fs-5 fw-extrabold">{{ number_format($sgpa ?? 0.00, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">Activity Points</small>
                                <strong class="text-emerald-400 fs-5 fw-extrabold" style="color: #10b981;">{{ $activityPoints ?? 0 }} <span style="font-size: 0.7rem;">/ 100</span></strong>
                            </div>
                        </div>
                    </div>

                    @if(!empty($statusNotes))
                    <div class="p-2.5 rounded bg-dark border border-cyan border-opacity-25 mb-2">
                        <small class="text-cyan fw-bold d-block mb-1" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-info-circle me-1"></i> Academic Progress Note:
                        </small>
                        <p class="text-slate-200 mb-0" style="font-size: 0.8rem;">
                            {{ $statusNotes }}
                        </p>
                    </div>
                    @endif

                    <div class="p-2 rounded bg-success bg-opacity-10 border border-success border-opacity-25 d-flex align-items-center justify-content-between">
                        <span class="text-white small" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-shield-check text-success me-1"></i> SBTE Examination Status
                        </span>
                        <span class="badge bg-success text-white badge-app">Eligible</span>
                    </div>
                </div>

                <!-- Subject-wise Internal Scores & CIE Evaluation -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-book-open me-1"></i> Subject Internal & CIE Marks (Sem {{ $student->semester }})
                    </h6>

                    @forelse($subjectAcademicPerformance as $subj)
                    <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $subj->subject_name }}</strong>
                                <span class="badge bg-secondary badge-app font-monospace me-1">{{ $subj->subject_code }}</span>
                                <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">{{ $subj->subject_type }} ({{ $subj->credits }} Credits)</span>
                            </div>
                            @if($subj->board_grade)
                            <span class="badge bg-warning text-dark fw-bold badge-app fs-6">Grade: {{ $subj->board_grade }}</span>
                            @elseif($subj->total_internal)
                            <span class="badge bg-emerald text-white fw-bold badge-app" style="background-color: #10b981;">CIE: {{ $subj->total_internal }} Marks</span>
                            @endif
                        </div>

                        <!-- Series & Internal Marks Row -->
                        <div class="row g-2 mt-1 text-center">
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Series 1</small>
                                    <strong class="text-cyan" style="font-size: 0.78rem;">{{ $subj->series1 !== null ? $subj->series1 . ' / 20' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Series 2</small>
                                    <strong class="text-cyan" style="font-size: 0.78rem;">{{ $subj->series2 !== null ? $subj->series2 . ' / 20' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Assg 1</small>
                                    <strong class="text-warning" style="font-size: 0.78rem;">{{ $subj->assignment1 !== null ? $subj->assignment1 . ' / 10' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Assg 2</small>
                                    <strong class="text-warning" style="font-size: 0.78rem;">{{ $subj->assignment2 !== null ? $subj->assignment2 . ' / 10' : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No subject performance records uploaded yet for this semester.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content 3: Assignments & Tests -->
            <div id="tab-tasks" class="tab-pane d-none">
                <!-- Assignments Card -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-list-check me-1"></i> Assignments & Submissions
                    </h6>
                    @forelse($assignments as $asgn)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-info" style="font-size: 0.82rem;">{{ $asgn->subject_code ?? 'Subject' }}</strong>
                            <span class="badge bg-warning text-dark badge-app">Pending</span>
                        </div>
                        <p class="text-white mb-1 fw-semibold" style="font-size: 0.85rem;">{{ $asgn->title }}</p>
                        <small class="text-secondary" style="font-size: 0.72rem;">
                            Due: {{ \Carbon\Carbon::parse($asgn->due_date)->format('d M Y') }}
                        </small>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No pending assignments listed for today.</p>
                    @endforelse
                </div>

                <!-- Practical Series Tests -->
                <div class="app-card">
                    <h6 class="fw-bold text-emerald mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-file-pen me-1"></i> Scheduled Practical Tests
                    </h6>
                    @forelse($practicalTests as $test)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-white d-block" style="font-size: 0.85rem;">{{ $test->test_name }}</strong>
                            <small class="text-secondary" style="font-size: 0.72rem;">Date: {{ $test->test_date }}</small>
                        </div>
                        <span class="badge bg-cyan text-dark badge-app">{{ $test->max_marks }} Marks</span>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No test evaluations scheduled today.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content 4: Remarks & Comments -->
            <div id="tab-remarks" class="tab-pane d-none">
                <div class="app-card">
                    <h6 class="fw-bold text-purple mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-comments me-1"></i> Tutor & Faculty Remarks
                    </h6>
                    @forelse($mentoringNotes as $note)
                    <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-cyan" style="font-size: 0.8rem;">{{ $note->faculty_name ?? 'Faculty Advisor' }}</strong>
                            <small class="text-secondary" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($note->created_at)->format('d M Y') }}</small>
                        </div>
                        <p class="text-slate-200 mb-0" style="font-size: 0.82rem;">
                            {{ $note->comments ?? 'Academic guidance session conducted.' }}
                        </p>
                    </div>
                    @empty
                    <div class="p-3 text-center text-secondary bg-dark rounded border border-secondary border-opacity-25 small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> No critical remarks. Student academic progress is satisfactory.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Share SMS Link Box -->
            <div class="app-card text-center">
                <small class="text-secondary fw-semibold d-block mb-2" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-share-nodes me-1"></i> Share Access Link via SMS
                </small>
                <div class="input-group input-group-sm">
                    <input type="text" id="smsLinkInput" class="form-control bg-dark text-light border-secondary" value="{{ $smsShareUrl }}" readonly style="font-size: 0.75rem;">
                    <a href="sms:{{ $student->guardian_mobile ?: $student->phone }}?body={{ urlencode('Carmel Poly: View ward status: ' . $smsShareUrl) }}" class="btn btn-cyan text-dark font-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> SMS
                    </a>
                </div>
            </div>

        </div>

        <!-- Bottom Mobile Navigation Bar -->
        <div class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchTab(event, 'tab-attendance')">
                <i class="fa-solid fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-academic')">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Academic</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-tasks')">
                <i class="fa-solid fa-list-check"></i>
                <span>Tasks & Tests</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-remarks')">
                <i class="fa-solid fa-comments"></i>
                <span>Remarks</span>
            </a>
        </div>

    </div>

    <!-- Script for Tab Switching & Theme Toggle -->
    <script>
        function switchTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            document.getElementById(tabId).classList.remove('d-none');
            e.currentTarget.classList.add('active');
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

        document.addEventListener('DOMContentLoaded', initTheme);
    </script>
</body>
</html>
