<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Carmel Linx - My Leave & Staff Portal</title>
    <!-- CSRF Token & PWA Meta Tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Carmel Linx">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

        /* Utility Dark Classes for Tailwind Parity & Dark Theme Consistency */
        .bg-slate-950 { background-color: #020617 !important; }
        .bg-slate-900 { background-color: #0f172a !important; }
        .bg-slate-800 { background-color: #1e293b !important; }
        .bg-slate-900\/90 { background-color: rgba(15, 23, 42, 0.9) !important; }
        .border-slate-800 { border-color: rgba(255, 255, 255, 0.1) !important; }
        .border-slate-700 { border-color: rgba(255, 255, 255, 0.15) !important; }
        .text-slate-100 { color: #f8fafc !important; }
        .text-slate-200 { color: #e2e8f0 !important; }
        .text-slate-300 { color: #cbd5e1 !important; }
        .text-slate-400 { color: #94a3b8 !important; }

        .modal-content {
            background-color: #090d16 !important;
            color: #f3f4f6 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
        }

        .modal-header {
            background-color: #0f172a !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        .modal-body {
            background-color: #090d16 !important;
            color: #f3f4f6 !important;
        }

        .modal-footer {
            background-color: #0f172a !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .btn-cyan {
            background-color: #06b6d4 !important;
            color: #0f172a !important;
            font-weight: 700;
        }

        .btn-outline-cyan {
            color: #06b6d4 !important;
            border-color: rgba(6, 182, 212, 0.4) !important;
        }

        .btn-outline-cyan:hover, .btn-check:checked + .btn-outline-cyan {
            background-color: #06b6d4 !important;
            color: #0f172a !important;
        }

        /* Large Touch-Friendly Responsive Period Circle Buttons */
        .period-circle-btn {
            width: 44px;
            height: 44px;
            max-width: 48px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.95rem;
            font-family: monospace;
            color: #06b6d4;
            border: 2px solid rgba(6, 182, 212, 0.4);
            background: rgba(6, 182, 212, 0.08);
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            user-select: none;
            margin: 0 auto;
        }

        .period-circle-btn:hover {
            border-color: #06b6d4;
            background: rgba(6, 182, 212, 0.2);
        }

        .btn-check:checked + .period-circle-btn {
            background-color: #06b6d4 !important;
            color: #0f172a !important;
            border-color: #06b6d4 !important;
            box-shadow: 0 0 14px rgba(6, 182, 212, 0.6);
            transform: scale(1.08);
        }

        /* Compact Dropdown List Options Styling */
        select option, #attLessonPlanSelect option {
            font-size: 0.72rem !important;
            padding: 5px 8px !important;
            background-color: #0f172a !important;
            color: #ffffff !important;
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

        @media (min-width: 768px) {
            body {
                padding-bottom: 40px;
            }
            .mobile-container {
                max-width: 960px !important;
                margin: 24px auto !important;
                padding: 24px !important;
                border-radius: 24px;
                background: rgba(15, 23, 42, 0.7);
                border: 1px solid var(--card-border);
                box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            }
            .bottom-nav {
                position: static !important;
                transform: none !important;
                max-width: 100% !important;
                border-radius: 16px;
                margin-top: 24px;
                border: 1px solid var(--card-border) !important;
            }
            .desktop-banner {
                display: flex !important;
            }
        }

        @media (max-width: 767px) {
            .desktop-banner {
                display: none !important;
            }
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
            padding: 10px 4px;
            z-index: 1000;
        }

        .nav-link-mobile {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.72rem;
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
            font-size: 1.15rem;
        }

        .badge-app {
            font-size: 0.76rem;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        .avatar-mobile {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
            object-position: center 15%;
            transform: scale(var(--avatar-zoom, 1.08));
            transition: transform 0.2s ease, object-position 0.2s ease;
        }

        /* Stat Mini Card */
        .stat-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 6px 4px;
        }

        .form-control, .form-select {
            font-size: 0.88rem !important;
            padding: 8px 12px;
            background-color: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-cyan) !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.2) !important;
        }

        .form-label {
            font-size: 0.8rem !important;
            font-weight: 700;
            color: #cbd5e1;
        }

        .brand-title {
            font-weight: 900 !important;
            font-size: 1.22rem;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #38bdf8 0%, #60a5fa 45%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 10px rgba(56, 189, 248, 0.4));
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
@php
    $userRole = session('userRole');
    $desktopUrl = '/dashboard/tutor?mode=desktop';
    if (in_array($userRole, ['Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance'])) {
        $desktopUrl = '/dashboard/academic-coordinator?mode=desktop';
    } elseif ($userRole === 'HOD') {
        $desktopUrl = '/dashboard/hod?mode=desktop';
    } elseif ($userRole === 'Gen_Dept_Coordinator_Aided') {
        $desktopUrl = '/dashboard/general-coordinator-aided?mode=desktop';
    } elseif (in_array($userRole, ['Super_Admin', 'Principal'])) {
        $desktopUrl = '/dashboard/principal?mode=desktop';
    } elseif ($userRole === 'Lecturer') {
        $desktopUrl = '/dashboard/lecturer?mode=desktop';
    } elseif ($userRole === 'Demonstrator') {
        $desktopUrl = '/dashboard/demonstrator?mode=desktop';
    } elseif ($userRole === 'Trade_Instructor') {
        $desktopUrl = '/dashboard/tradeinstructor?mode=desktop';
    } elseif ($userRole === 'Workshop_Superintendent') {
        $desktopUrl = '/dashboard/workshop?mode=desktop';
    }
@endphp

    <div class="mobile-container">

        <!-- Mobile Header -->
        <header class="mobile-header d-flex align-items-center justify-content-between" style="background: rgba(15, 23, 42, 0.92); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(56, 189, 248, 0.15); padding: 12px 16px;">
            <div class="d-flex align-items-center" style="gap: 14px;">
                <div style="position: relative; padding: 2.5px; background: linear-gradient(135deg, rgba(56, 189, 248, 0.45), rgba(99, 102, 241, 0.25)); border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);">
                    <img src="{{ asset('logo.jpg') }}" alt="Carmel Linx Logo" style="width: 36px; height: 36px; border-radius: 9.5px; object-fit: cover; display: block;">
                </div>
                <div class="ps-1">
                    <h5 class="brand-title mb-0" style="font-size: 1.18rem; font-weight: 900 !important; line-height: 1.1;">Carmel Linx</h5>
                    <span class="badge" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35); font-size: 0.63rem; font-weight: 800; border-radius: 20px; padding: 2px 8px; letter-spacing: 0.05em; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px; margin-top: 2px;">
                        <span style="width: 5px; height: 5px; background-color: #38bdf8; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px #38bdf8;"></span> Staff Portal
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1.5">
                <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to logout?')" class="btn btn-sm rounded-circle d-inline-flex align-items-center justify-content-center p-0 shadow-sm" style="width: 38px; height: 38px; background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); transition: all 0.2s ease;" title="Logout" aria-label="Logout">
                    <i class="fa-solid fa-power-off" style="font-size: 0.95rem; filter: drop-shadow(0 0 4px rgba(239, 68, 68, 0.5));"></i>
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <div class="p-3">

            <!-- Executive Flash Notice Broadcast Banner -->
            <div id="executiveFlashNoticeBanner" class="d-none mb-3"></div>

            <!-- Staff Identity Banner -->
            <div class="app-card border-start border-2 border-info p-2.5 mb-2.5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="position-relative flex-shrink-0">
                        <div style="width: 44px; height: 44px; border-radius: 50%; overflow: hidden; border: 2px solid var(--accent-cyan); display: flex; align-items: center; justify-content: center; background: #1e293b;">
                            @if(!empty($staff->photo_url))
                                <img id="staffBannerPhoto" src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="avatar-mobile" style="width: 100%; height: 100%; object-fit: cover; object-position: center 15%; transform: scale(1.08);">
                            @else
                                <div id="staffBannerPhotoPlaceholder" class="w-100 h-100 flex items-center justify-center font-black text-white" style="font-size: 0.95rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($staff->name ?? 'S', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-hidden ps-1">
                        <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 0.98rem;">{{ $staff->name ?? session('userName') }}</h6>
                        @php
                            $rawBranch = $staff->branch ?? session('userBranch') ?? '';
                            $deptName = function_exists('getFullBranchName') ? getFullBranchName($rawBranch) : $rawBranch;
                        @endphp
                        @if(!empty($deptName))
                            <small class="fw-medium d-block text-truncate mt-0.5" style="font-size: 0.74rem; color: #60a5fa !important;">
                                <i class="fa-solid fa-building-columns me-1" style="font-size: 0.68rem; color: #60a5fa !important;"></i>{{ $deptName }}
                            </small>
                        @endif
                    </div>
                </div>

                @php
                    $isMentor = (count($classrooms) > 0);
                @endphp

                <div class="row g-1.5 text-center">
                    @if($isMentor)
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">Subjects</span>
                            <strong class="text-white" style="font-size: 0.95rem;">{{ count($assignments) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">Tutorship</span>
                            <strong class="text-cyan" style="font-size: 0.95rem;">{{ count($classrooms) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">To-Dos</span>
                            <strong class="text-warning" style="font-size: 0.95rem;">{{ count($todos) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">Events</span>
                            <strong style="color: #fb923c; font-size: 0.95rem;">{{ $eventsCount ?? 0 }}</strong>
                        </div>
                    </div>
                    @else
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">Subjects</span>
                            <strong class="text-white" style="font-size: 0.95rem;">{{ count($assignments) }}</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">To-Dos</span>
                            <strong class="text-warning" style="font-size: 0.95rem;">{{ count($todos) }}</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.6rem; font-weight: 700;">Events</span>
                            <strong style="color: #fb923c; font-size: 0.95rem;">{{ $eventsCount ?? 0 }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @php
                $inTimeFormatted = (isset($todayPunch) && $todayPunch && $todayPunch->in_time) ? date('h:i A', strtotime($todayPunch->in_time)) : null;
                $outTimeFormatted = (isset($todayPunch) && $todayPunch && $todayPunch->out_time) ? date('h:i A', strtotime($todayPunch->out_time)) : null;
                
                $isPunchedIn = !empty($inTimeFormatted);
                $isPunchedOut = !empty($outTimeFormatted);
                $isCompleted = $isPunchedIn && $isPunchedOut;

                $inStatusLabel = 'PRESENT';
                if ($isPunchedIn && isset($todayPunch->in_time)) {
                    $inHi = date('H:i', strtotime($todayPunch->in_time));
                    if ($inHi < '08:45') {
                        $inStatusLabel = 'EARLY IN';
                    } elseif ($inHi > '09:15') {
                        $inStatusLabel = 'LATE IN';
                    } else {
                        $inStatusLabel = 'PRESENT';
                    }
                }

                $outStatusLabel = 'OUT RECORDED';
                if ($isPunchedOut && isset($todayPunch->out_time)) {
                    $outHi = date('H:i', strtotime($todayPunch->out_time));
                    if ($outHi < '16:00') {
                        $outStatusLabel = 'EARLY OUT';
                    } elseif ($outHi > '16:30') {
                        $outStatusLabel = 'LATE OUT';
                    } else {
                        $outStatusLabel = 'ON TIME OUT';
                    }
                }

                $campusHours = null;
                if ($isCompleted && isset($todayPunch->in_time) && isset($todayPunch->out_time)) {
                    $tIn = strtotime($todayPunch->punch_date . ' ' . $todayPunch->in_time);
                    $tOut = strtotime($todayPunch->punch_date . ' ' . $todayPunch->out_time);
                    $diffSec = max(0, $tOut - $tIn);
                    $hrs = floor($diffSec / 3600);
                    $mins = round(($diffSec % 3600) / 60);
                    $campusHours = "{$hrs}h {$mins}m in Campus";
                } elseif ($isPunchedIn && isset($todayPunch->in_time)) {
                    $tIn = strtotime(($todayPunch->punch_date ?? date('Y-m-d')) . ' ' . $todayPunch->in_time);
                    $tNow = time();
                    $diffSec = max(0, $tNow - $tIn);
                    $hrs = floor($diffSec / 3600);
                    $mins = round(($diffSec % 3600) / 60);
                    $campusHours = "{$hrs}h {$mins}m in Campus";
                }

                // Check if staff belongs to EL, CT, AU, or General SF staff categories
                $staffBranch = strtoupper($staff->branch ?? $staff->department ?? session('userBranch') ?? '');
                $staffRole   = strtoupper(session('userRole') ?? $staff->designation ?? '');

                $sfAllowedBranches = ['EL', 'CT', 'AU', 'GEN_SF', 'SF'];
                $sfAllowedRoles    = ['GEN_DEPT_COORDINATOR_SELF_FINANCE', 'ACADEMIC_COORDINATOR_SF'];

                $isSfStaff = in_array($staffBranch, $sfAllowedBranches)
                    || in_array($staffRole, $sfAllowedRoles)
                    || str_contains($staffRole, 'SELF_FINANCE')
                    || str_contains($staffRole, 'SELF FINANCE')
                    || str_contains($staffRole, '_SF')
                    || str_contains($staffBranch, 'SF');
            @endphp

            <!-- TAB PANES -->

            <!-- TAB 1: TODAY'S TIMETABLE & ATTENDANCE -->
            <div id="tab-classes" class="tab-pane fade-in">

            @if($isSfStaff)
            <!-- Ultra-Compact Staff Biometric Punch Card -->
            <div class="app-card mb-2.5 p-2.5 rounded-3" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.85)); border: 1px solid rgba(56, 189, 248, 0.3); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.35);">
                <!-- Compact Header & Action Row -->
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <div style="width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, #0ea5e9, #10b981); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 1rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.4);">
                            <i class="fa-solid fa-camera-rotate"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <strong class="text-white text-truncate" style="font-size: 0.88rem; font-weight: 800;">Staff Biometric Punch</strong>
                                @if($isCompleted)
                                    <span class="badge px-2 py-0.5 rounded-pill" style="font-size: 0.65rem; font-weight: 900; background: #059669; color: #ffffff; border: 1px solid #34d399; box-shadow: 0 0 8px rgba(52, 211, 153, 0.4);">
                                        <i class="fa-solid fa-circle-check me-1"></i> COMPLETED
                                    </span>
                                @elseif($isPunchedIn)
                                    <span class="badge px-2 py-0.5 rounded-pill" style="font-size: 0.65rem; font-weight: 900; background: #0284c7; color: #ffffff; border: 1px solid #38bdf8; box-shadow: 0 0 10px rgba(56, 189, 248, 0.5);">
                                        <i class="fa-solid fa-right-to-bracket me-1"></i> CHECKED IN
                                    </span>
                                @else
                                    <span class="badge px-2 py-0.5 rounded-pill" style="font-size: 0.65rem; font-weight: 900; background: #d97706; color: #ffffff; border: 1px solid #fbbf24;">
                                        <i class="fa-solid fa-clock me-1"></i> NOT PUNCHED
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        @if($isCompleted)
                            <a href="/sf-attendance/face-punch" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-black" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-circle-check me-1"></i> Log
                            </a>
                        @elseif($isPunchedIn)
                            <a href="/sf-attendance/face-punch" class="btn btn-sm rounded-pill px-3 py-1.5 fw-black text-white shadow-sm" style="font-size: 0.8rem; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: 1px solid #38bdf8; box-shadow: 0 0 12px rgba(56, 189, 248, 0.4) !important;">
                                <i class="fa-solid fa-camera me-1"></i> Punch OUT
                            </a>
                        @else
                            <a href="/sf-attendance/face-punch" class="btn btn-sm rounded-pill px-3 py-1.5 fw-black text-white shadow-sm" style="font-size: 0.8rem; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: 1px solid #34d399; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4) !important;">
                                <i class="fa-solid fa-camera me-1"></i> Punch IN
                            </a>
                        @endif
                    </div>
                </div>

                <!-- High-Visibility Prominent Time Display Strip -->
                <div class="d-flex align-items-center justify-content-between mt-2.5 pt-2 border-top border-secondary border-opacity-25 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5 flex-wrap">
                        <!-- IN Time & Status -->
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="text-uppercase fw-bold" style="font-size: 0.68rem; color: #cbd5e1; letter-spacing: 0.3px;">
                                <i class="fa-solid fa-sun text-warning me-0.5"></i> IN:
                            </span>
                            <span class="font-mono fw-black" style="font-size: 1.05rem; color: {{ $isPunchedIn ? '#34d399' : '#94a3b8' }}; font-weight: 900; letter-spacing: -0.5px;">
                                {{ $inTimeFormatted ?? '--:--' }}
                            </span>
                            @if($isPunchedIn)
                                <span class="badge px-1.5 py-0.5" style="font-size: 0.58rem; font-weight: 900; border-radius: 4px; background: {{ $inStatusLabel === 'LATE IN' ? 'rgba(239, 68, 68, 0.25)' : ($inStatusLabel === 'EARLY IN' ? 'rgba(56, 189, 248, 0.25)' : 'rgba(52, 211, 153, 0.25)') }}; color: {{ $inStatusLabel === 'LATE IN' ? '#f87171' : ($inStatusLabel === 'EARLY IN' ? '#38bdf8' : '#34d399') }}; border: 1px solid {{ $inStatusLabel === 'LATE IN' ? 'rgba(239, 68, 68, 0.5)' : ($inStatusLabel === 'EARLY IN' ? 'rgba(56, 189, 248, 0.5)' : 'rgba(52, 211, 153, 0.5)') }};">
                                    {{ $inStatusLabel }}
                                </span>
                            @endif
                        </div>

                        <!-- OUT Time & Status -->
                        <div class="d-flex align-items-center gap-1.5 ps-2 border-start border-secondary border-opacity-25">
                            <span class="text-uppercase fw-bold" style="font-size: 0.68rem; color: #cbd5e1; letter-spacing: 0.3px;">
                                <i class="fa-solid fa-moon text-info me-0.5"></i> OUT:
                            </span>
                            <span class="font-mono fw-black" style="font-size: 1.05rem; color: {{ $isPunchedOut ? '#38bdf8' : '#94a3b8' }}; font-weight: 900; letter-spacing: -0.5px;">
                                {{ $outTimeFormatted ?? '--:--' }}
                            </span>
                            @if($isPunchedOut)
                                <span class="badge px-1.5 py-0.5" style="font-size: 0.58rem; font-weight: 900; border-radius: 4px; background: {{ $outStatusLabel === 'EARLY OUT' ? 'rgba(245, 158, 11, 0.25)' : ($outStatusLabel === 'LATE OUT' ? 'rgba(168, 85, 247, 0.25)' : 'rgba(52, 211, 153, 0.25)') }}; color: {{ $outStatusLabel === 'EARLY OUT' ? '#fbbf24' : ($outStatusLabel === 'LATE OUT' ? '#c084fc' : '#34d399') }}; border: 1px solid {{ $outStatusLabel === 'EARLY OUT' ? 'rgba(245, 158, 11, 0.5)' : ($outStatusLabel === 'LATE OUT' ? 'rgba(168, 85, 247, 0.5)' : 'rgba(52, 211, 153, 0.5)') }};">
                                    {{ $outStatusLabel }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Time in Campus Badge -->
                    <div style="font-size: 0.74rem;">
                        @if($campusHours)
                            <span class="fw-black px-2 py-0.5 rounded-pill" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); font-size: 0.72rem; font-weight: 800;">
                                <i class="fa-solid fa-stopwatch text-warning me-1"></i>{{ $campusHours }}
                            </span>
                        @else
                            <span class="fw-semibold text-secondary" style="color: #94a3b8;"><i class="fa-solid fa-location-dot text-info me-1"></i>Geofence Lock</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

                <!-- Timetable Day Order Selection Card -->
                <div class="app-card border-start border-2 border-cyan" style="border: 1px solid rgba(56, 189, 248, 0.3);">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-calendar-day me-1 text-cyan"></i> Timetable
                        </h6>
                        <button type="button" id="selectedDayBadge" onclick="toggleDayPicker()" class="btn text-dark fw-black px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-1.5 cursor-pointer" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); font-size: 1.05rem; font-weight: 900; border-radius: 10px; letter-spacing: 0.5px; box-shadow: 0 0 14px rgba(56, 189, 248, 0.4); border: none;" title="Tap to change Day Order">
                            <i class="fa-solid fa-calendar-day fs-6"></i> <span>{{ $defaultDayOrder }}</span> <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>

                    <!-- Hidden Collapsible Day Selection Panel -->
                    <div id="dayOrderPickerPanel" class="d-none mt-1 mb-3 p-2.5 rounded-3 bg-slate-900 border border-slate-800" style="background-color: #0f172a !important; border: 1px solid rgba(56, 189, 248, 0.25) !important;">
                        <div class="text-slate-300 mb-2" style="font-size: 0.74rem; color: #cbd5e1 !important;">
                            <i class="fa-solid fa-circle-info text-cyan me-1"></i> If a holiday occurred, tap an active <strong>Day Order (1-5)</strong> below to update the schedule globally:
                        </div>
                        <!-- Day Selection Pills (Day 1 to Day 5) -->
                        <div class="d-flex gap-1 overflow-x-auto pb-1">
                            @foreach(['Day 1' => 'Mon', 'Day 2' => 'Tue', 'Day 3' => 'Wed', 'Day 4' => 'Thu', 'Day 5' => 'Fri'] as $dKey => $dShort)
                            <button onclick="selectDayOrder('{{ $dKey }}')" 
                                    data-day="{{ $dKey }}" 
                                    class="btn btn-sm {{ $dKey === $defaultDayOrder ? 'btn-cyan fw-bold text-dark' : 'btn-outline-secondary' }} px-2.5 py-1.5 rounded-pill day-order-btn flex-fill" style="font-size: 0.8rem; font-weight: 700; whitespace: nowrap;">
                                {{ $dKey }} <small class="opacity-75">({{ $dShort }})</small>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dynamic Timetable Slots Container -->
                    <div id="timetableScheduleContainer">
                        <!-- Populated dynamically via JS -->
                    </div>
                </div>
            </div>

            <!-- TAB 2: TO-DO WORKS -->
            <div id="tab-todo" class="tab-pane d-none fade-in">
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-list-check me-1"></i> Staff Works & Tasks To-Do
                    </h6>

                    <div class="space-y-2">
                        @forelse($todos as $item)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-start gap-3">
                                <i class="{{ $item->icon }} mt-1" style="font-size: 1.25rem;"></i>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between mb-1 gap-2 flex-wrap">
                                        <strong class="text-white" style="font-size: 0.88rem;">{{ $item->title }}</strong>
                                        <span class="badge {{ $item->badge_class }} badge-app">{{ $item->badge }}</span>
                                    </div>
                                    <small class="text-secondary d-block" style="font-size: 0.76rem;">{{ $item->subtitle }}</small>
                                </div>
                            </div>
                            @if($item->type === 'attendance')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <button type="button" onclick="switchStaffTab(event, 'tab-classes')" class="btn btn-sm btn-cyan px-3 py-1 rounded-pill fw-bold text-dark" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-clipboard-user me-1"></i> Take Attendance
                                </button>
                            </div>
                            @elseif($item->type === 'leave')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <button type="button" onclick="switchStaffTab(event, 'tab-mentoring')" class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Review Leaves
                                </button>
                            </div>
                            @elseif($item->type === 'remedial')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <button type="button" onclick="switchStaffTab(event, 'tab-remedial')" class="btn btn-sm px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 0.72rem; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none;">
                                    <i class="fa-solid fa-kit-medical me-1"></i> Remedial Classes
                                </button>
                            </div>
                            @elseif(!empty($item->link) && $item->link !== '#')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <a href="{{ $item->link }}" class="btn btn-sm btn-outline-info px-3 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-arrow-right-long me-1"></i> Open Task / Portal
                                </a>
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-circle-check d-block mb-1 text-success" style="font-size: 1.2rem;"></i>
                            All staff tasks & works are completed!
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: REMEDIAL CLASSES -->
            <div id="tab-remedial" class="tab-pane d-none fade-in">
                <div class="app-card border-start border-2" style="border-left-color: #f97316 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0" style="color: #fb923c; font-size: 0.95rem;">
                            <i class="fa-solid fa-kit-medical me-1"></i> Remedial Classes & Support
                        </h6>
                        <a href="/remedial-sessions" class="btn btn-sm px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem; color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.4); background: rgba(249, 115, 22, 0.1);">
                            <i class="fa-solid fa-arrow-right-long me-1"></i> Open Portal
                        </a>
                    </div>

                    <div class="space-y-2">
                        @forelse($remedialRooms as $room)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.88rem;">Room: {{ $room->room_code ?? $room->id }}</strong>
                                    <small class="text-secondary" style="font-size: 0.75rem;"><strong class="text-cyan">{{ $room->classroom_id ?? 'Academic' }}</strong></small>
                                </div>
                                <span class="badge badge-app" style="background-color: rgba(249, 115, 22, 0.18); color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.35);">{{ $room->status ?? 'Active' }}</span>
                            </div>
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end gap-2">
                                <a href="/remedial-sessions" class="btn btn-sm px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 0.75rem; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none; box-shadow: 0 0 12px rgba(249, 115, 22, 0.3);">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Log Session & Attendance
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            No active remedial class rooms currently assigned.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 4: MENTORING & LEAVE APPROVALS (IF MENTOR) -->
            @if($isMentor)
            <div id="tab-mentoring" class="tab-pane d-none fade-in">

                <!-- Pending Student Leave Approvals -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Student Leave Requests (Pending)
                    </h6>
                    <div id="mobilePendingLeavesList" class="space-y-2">
                        @forelse($pendingLeaves as $leave)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $leave->student_name }}</strong>
                                    <small class="text-cyan font-mono" style="font-size: 0.75rem;">{{ $leave->reg_no }}</small>
                                </div>
                                <span class="badge bg-warning text-dark badge-app">Pending</span>
                            </div>
                            <div class="text-secondary my-1" style="font-size: 0.76rem;">
                                <div><i class="fa-solid fa-calendar me-1 text-warning"></i> Date: <strong class="text-white">{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}</strong></div>
                                <div><i class="fa-solid fa-circle-info me-1 text-warning"></i> Reason: <span class="text-white">{{ $leave->reason }}</span></div>
                            </div>
                            <div class="d-flex gap-2 mt-2 pt-2 border-top border-secondary border-opacity-25">
                                <button onclick="processMobileLeave('{{ $leave->id }}', 'Approved')" class="btn btn-sm btn-success flex-fill fw-bold" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-check me-1"></i> Approve
                                </button>
                                <button onclick="processMobileLeave('{{ $leave->id }}', 'Rejected')" class="btn btn-sm btn-outline-danger flex-fill fw-bold" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-xmark me-1"></i> Reject
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            No pending student leave requests requiring review.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Assigned Mentoring Tutorship -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-user-graduate me-1"></i> Assigned Tutorship & Mentoring Batches
                    </h6>
                    @forelse($classrooms as $cls)
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $cls->classroom_id }}</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Branch: {{ $cls->branch }} | Sem {{ $cls->current_semester }}</small>
                            </div>
                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">Tutor</span>
                        </div>
                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end gap-2">
                            <a href="/hod/batches/{{ $cls->classroom_id }}/credentials/print" target="_blank" class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-print me-1"></i> Print Credentials
                            </a>
                            <a href="/tutor/mentoring-diary/{{ $cls->classroom_id }}" class="btn btn-sm btn-cyan px-3 py-1 rounded-pill fw-bold text-dark" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-book-open me-1"></i> Mentoring Diary
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                        No mentoring classrooms currently assigned.
                    </div>
                    @endforelse
                </div>

            </div>
            @endif

            <!-- TAB 6: STAFF LEAVE GOVERNANCE PORTAL -->
            <div id="tab-leave" class="tab-pane d-none fade-in">
                
                <div class="app-card border-start border-2 border-info mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold text-white mb-0">Staff Leave Portal</h6>
                            <small class="text-secondary" style="font-size: 0.72rem;">3-Stage Hierarchical Approval Workflow</small>
                        </div>
                        <button type="button" onclick="openStaffLeaveModal()" class="btn btn-sm btn-info fw-bold text-dark rounded-pill px-3" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); border: none;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Apply Leave
                        </button>
                    </div>
                </div>

                <!-- PENDING APPROVALS BOX (FOR HOD / COORDINATOR / PRINCIPAL) -->
                @if(in_array(session('userRole'), ['HOD', 'Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance', 'Principal', 'Super_Admin', 'Admin']))
                <div class="app-card border-start border-2 border-warning mb-3">
                    <h6 class="fw-bold text-warning mb-2" style="font-size: 0.88rem;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Pending Staff Leave Approvals
                    </h6>
                    <div id="pendingApprovalsContainer" class="space-y-2">
                        <small class="text-secondary d-block">Loading pending approval queue...</small>
                    </div>
                    @if(in_array(session('userRole'), ['HOD', 'Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance', 'Principal']))
                    <div class="mt-2 text-end">
                        <a href="/staff/leave/reports" class="btn btn-sm btn-outline-warning py-0.5 px-2" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-table-list me-1"></i> View Master Leave Ledger
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- MY LEAVE APPLICATION HISTORY -->
                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">
                        <i class="fa-solid fa-list-check me-1 text-info"></i> My Leave Applications
                    </h6>
                    <div id="myLeaveHistoryContainer" class="space-y-2">
                        <small class="text-secondary d-block">Loading your leave records...</small>
                    </div>
                </div>

            </div>

            <!-- TAB 5: PROFILE & SECURITY -->
            <div id="tab-profile" class="tab-pane d-none fade-in">
                
                <!-- Staff Profile Photo Card -->
                <div class="app-card mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative flex-shrink-0" style="width: 54px; height: 54px; overflow: hidden; border-radius: 50%; border: 2px solid var(--accent-cyan);">
                                @if(!empty($staff->photo_url))
                                    <img id="staffProfileTabPhoto" src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="avatar-mobile" style="width: 100%; height: 100%; object-fit: cover; object-position: center 15%; transform: scale(1.08);">
                                @else
                                    <div id="staffProfileTabPlaceholder" class="avatar-mobile flex items-center justify-center font-black text-white" style="width: 100%; height: 100%; font-size: 1.2rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center;">
                                        {{ strtoupper(substr($staff->name ?? 'S', 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-0" style="font-size: 0.92rem;">Profile Photo</h6>
                                <small class="text-secondary d-block" style="font-size: 0.74rem;">JPG, PNG, or WebP up to 10MB</small>
                            </div>
                        </div>
                        <label for="staffPhotoFileInput" class="btn btn-sm btn-cyan px-3 py-1.5 rounded-pill fw-bold text-dark cursor-pointer flex-shrink-0 ms-2" style="font-size: 0.76rem;">
                            <i class="fa-solid fa-camera me-1"></i> Change Photo
                        </label>
                    </div>

                    <!-- Photo Framing & Zoom Controls -->
                    <div class="mt-3 pt-2.5 border-top border-secondary border-opacity-25">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-white fw-bold" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-magnifying-glass-plus text-info me-1"></i> Avatar Zoom & Framing
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm text-secondary p-0" onclick="resetStaffAvatarAdjustments()" style="font-size: 0.7rem;">
                                    Reset
                                </button>
                                <button type="button" id="mobileSaveFramingBtn" onclick="saveStaffAvatarFramingToServer()" class="btn btn-sm btn-outline-info py-0.5 px-2 rounded-pill fw-bold" style="font-size: 0.68rem;">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Framing
                                </button>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-6">
                                <div class="d-flex justify-content-between text-secondary" style="font-size: 0.7rem;">
                                    <span>Zoom:</span>
                                    <strong id="mobileZoomVal" class="text-cyan font-mono">1.08x</strong>
                                </div>
                                <input type="range" id="mobileZoomSlider" min="1.0" max="2.5" step="0.05" value="1.08" oninput="adjustStaffAvatarZoom(this.value)" onchange="saveStaffAvatarFramingToServer()" class="form-range custom-range" style="height: 4px;">
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between text-secondary" style="font-size: 0.7rem;">
                                    <span>Vertical Focus:</span>
                                    <strong id="mobilePosVal" class="text-info font-mono">15%</strong>
                                </div>
                                <input type="range" id="mobilePosSlider" min="0" max="80" step="2" value="15" oninput="adjustStaffAvatarPos(this.value)" onchange="saveStaffAvatarFramingToServer()" class="form-range custom-range" style="height: 4px;">
                            </div>
                        </div>
                    </div>

                    <div id="staffPhotoUploadAlert" class="small mt-2 d-none font-bold"></div>
                </div>

                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-id-card me-1 text-info"></i> Staff Profile Overview
                    </h6>
                    <div class="space-y-2 text-secondary" style="font-size: 0.82rem;">
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Full Name:</span> <strong class="text-white">{{ $staff->name ?? session('userName') }}</strong>
                        </div>
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Mobile ID:</span> <strong class="text-cyan font-mono">{{ $staff->mobile_no ?? session('userId') }}</strong>
                        </div>
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Role / Designation:</span> <strong class="text-white">{{ $staff->designation ?? session('userRole') }}</strong>
                        </div>
                        <div class="py-1 d-flex justify-content-between">
                            <span>Department:</span> <strong class="text-white">{{ $staff->department ?? session('userBranch', 'Academic') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="app-card">
                    <h6 class="fw-bold mb-3" style="color: #fbbf24; font-size: 0.95rem;">
                        <i class="fa-solid fa-key me-1"></i> Change Account Password
                    </h6>
                    <div id="staffPwdAlert" class="small mb-2 d-none font-bold"></div>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" id="sOldPwd" class="form-control" placeholder="Enter current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" id="sNewPwd" class="form-control" placeholder="Enter new password (min 6 chars)">
                    </div>
                    <button onclick="updateStaffPassword()" class="btn w-100 fw-bold" style="font-size: 0.84rem; background: linear-gradient(135deg, #fde047 0%, #fbbf24 100%); color: #0f172a; font-weight: 800; border: none; box-shadow: 0 4px 14px rgba(251, 191, 36, 0.35);">
                        <i class="fa-solid fa-shield-halved me-1"></i> Update Password
                    </button>
                </div>

                <!-- Biometric & Fingerprint Quick-Pass -->
                <div class="app-card mt-3">
                    <h6 class="fw-bold mb-2" style="color: #818cf8; font-size: 0.95rem;">
                        <i class="fa-solid fa-fingerprint me-1"></i> Biometric & Fingerprint Login
                    </h6>
                    <p class="text-secondary mb-3" style="font-size: 0.78rem; line-height: 1.4;">
                        Enable one-touch fingerprint scan for fast, secure mobile login without typing your password each time.
                    </p>
                    <div id="staffBioAlert" class="small mb-2 d-none font-bold"></div>
                    <button onclick="registerMobileBiometric()" class="btn w-100 fw-bold" style="font-size: 0.84rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);">
                        <i class="fa-solid fa-fingerprint me-1"></i> Enable Fingerprint on This Device
                    </button>
                    <div id="registeredBioDevicesList" class="mt-3 d-none">
                        <span class="text-secondary d-block mb-2 fw-bold" style="font-size: 0.75rem;">Registered Fingerprint Devices:</span>
                        <div id="bioDevicesContainer" class="space-y-2"></div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Navigation Bar -->
        <nav class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchStaffTab(event, 'tab-classes')">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Classes</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-todo')">
                <i class="fa-solid fa-list-check"></i>
                <span>To-Do</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-remedial')">
                <i class="fa-solid fa-kit-medical"></i>
                <span>Remedial</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-leave')">
                <i class="fa-solid fa-file-signature"></i>
                <span>Leave</span>
            </a>
            @if($isMentor)
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-mentoring')">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Mentoring</span>
            </a>
            @endif
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-profile')">
                <i class="fa-solid fa-user-gear"></i>
                <span>Profile</span>
            </a>
        </nav>

    </div>

    <!-- STAFF LEAVE APPLICATION MODAL -->
    <div id="staffLeaveModal" class="modal fade" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-secondary border-opacity-25 text-white shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="modal-title fw-bold text-info" id="staffLeaveModalLabel">
                        <i class="fa-solid fa-paper-plane me-1"></i> Apply Staff Leave
                    </h6>
                    <button type="button" class="btn-close btn-close-white" onclick="closeStaffLeaveModal()"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="staffLeaveAlert" class="alert d-none py-2 px-3 small font-bold mb-3"></div>
                    <form id="staffLeaveForm">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold mb-1">Leave Type</label>
                                <select id="slvType" onchange="toggleCclDateField()" class="form-select bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                                    <option value="Casual Leave">Casual Leave (CL)</option>
                                    <option value="Compensatory Casual Leave">Compensatory Casual Leave (CCL)</option>
                                    <option value="Duty Leave">Duty Leave (DL)</option>
                                    <option value="Medical Leave">Medical Leave (ML)</option>
                                    <option value="Loss of Pay">Loss of Pay (LOP)</option>
                                    <option value="Special Leave">Special Leave</option>
                                </select>
                                <small id="clBalanceInfo" class="text-cyan d-block mt-1 font-mono fw-bold" style="font-size: 0.72rem;">[Total: 15, Taken: 0]</small>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold mb-1">Session Type</label>
                                <select id="slvSession" class="form-select bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                                    <option value="Full Day">Full Day</option>
                                    <option value="FN">FN (Forenoon)</option>
                                    <option value="AN">AN (Afternoon)</option>
                                </select>
                            </div>
                        </div>

                        <!-- CCL Date Picker (Visible when CCL is selected) -->
                        <div id="cclDateBox" class="mb-3 d-none">
                            <label class="form-label text-info small fw-bold mb-1">
                                <i class="fa-solid fa-calendar-check me-1"></i> CCL Date (Date Duty Worked)
                            </label>
                            <input type="date" id="slvCclDate" class="form-control bg-slate-900 border-info border-opacity-50 text-white" style="font-size: 0.85rem;">
                            <small class="text-secondary" style="font-size: 0.7rem;">Specify the date on which compensatory duty was performed.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-5">
                                <label class="form-label text-secondary small fw-bold mb-1">From Date</label>
                                <input type="date" id="slvFromDate" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                            </div>
                            <div class="col-5">
                                <label class="form-label text-secondary small fw-bold mb-1">To Date</label>
                                <input type="date" id="slvToDate" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                            </div>
                            <div class="col-2">
                                <label class="form-label text-secondary small fw-bold mb-1">Days</label>
                                <input type="number" step="0.5" min="0.5" id="slvTotalDays" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" value="1" style="font-size: 0.85rem;" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Reason for Leave</label>
                            <textarea id="slvReason" rows="2" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" placeholder="Provide reason for absence..." style="font-size: 0.85rem;" required></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label text-secondary small fw-bold mb-1">Work Arrangement / Substitutes</label>
                            <div class="p-2 border border-secondary border-opacity-25 rounded-3 bg-slate-950">
                                <div class="row g-1 mb-2">
                                    <div class="col-5">
                                        <input type="text" id="arrClassroom" class="form-control form-control-sm bg-dark text-white" placeholder="Period / Class">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" id="arrSubstitute" class="form-control form-control-sm bg-dark text-white" placeholder="Substitute Staff">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" onclick="addWorkArrangementRow()" class="btn btn-sm btn-info w-100">+</button>
                                    </div>
                                </div>
                                <ul id="arrList" class="list-group list-group-flush small" style="max-height: 100px; overflow-y: auto;"></ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" onclick="closeStaffLeaveModal()">Cancel</button>
                    <button type="button" id="btnSubmitStaffLeave" onclick="submitStaffLeaveRequest()" class="btn btn-sm btn-info px-4 rounded-pill fw-bold text-dark" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); border: none;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CLASS ATTENDANCE & LOG MODAL -->
    <div class="modal fade" id="classAttendanceModal" tabindex="-1" aria-hidden="true" style="display: none; background: rgba(2, 6, 23, 0.95); backdrop-filter: blur(12px);">
        <div class="modal-dialog modal-dialog-centered modal-lg my-0 my-sm-3" style="max-height: 100vh; height: 100%;">
            <div class="modal-content bg-slate-950 border border-slate-800 text-white shadow-2xl rounded-0 rounded-sm-4" style="background-color: #090d16 !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; max-height: 100vh; height: 100%; display: flex; flex-direction: column; overflow: hidden;">
                
                <!-- Unified Fixed Header on Top -->
                <div class="modal-header py-2.5 px-3 d-flex flex-column gap-2 flex-shrink-0" style="background-color: #0f172a !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important; position: sticky; top: 0; z-index: 1060; box-shadow: 0 4px 14px rgba(0,0,0,0.6);">
                    <!-- Top Row: Subject Code + Name (Left) & Back Button (Right) -->
                    <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                        <div class="d-flex align-items-center gap-1.5 overflow-hidden flex-fill me-2">
                            <i class="fa-solid fa-book-open text-cyan fs-6 flex-shrink-0"></i>
                            <div class="text-truncate">
                                <span class="fw-black text-cyan" style="font-size: 0.95rem;" id="attSubjectConfirmCode">---</span>
                                <span id="attSubjectConfirmName" class="fw-medium text-slate-300" style="font-size: 0.84rem; color: #cbd5e1 !important;"></span>
                                <span id="attBatchConfirmCode" class="d-none"></span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm rounded-pill px-3 py-1 text-white fw-black flex-shrink-0 shadow-sm" onclick="closeClassAttendanceModal()" title="Back to Mobile Dashboard" style="font-size: 0.78rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; box-shadow: 0 0 10px rgba(239, 68, 68, 0.45);">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </button>
                    </div>

                    <!-- Bottom Row: Equal-Width Tab Navigation -->
                    <ul class="nav nav-pills p-1 rounded-pill bg-slate-950 border border-slate-800 d-flex w-100 mb-0" style="background-color: #020617 !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                        <li class="nav-item flex-fill">
                            <button class="nav-link active py-1.5 w-100 rounded-pill text-white fw-bold text-center" id="tabBtnTakeAtt" onclick="switchAttModalTab('take')" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Mark Attendance
                            </button>
                        </li>
                        <li class="nav-item flex-fill">
                            <button class="nav-link py-1.5 w-100 rounded-pill text-secondary fw-bold text-center" id="tabBtnPastLogs" onclick="switchAttModalTab('logs')" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> Past Logs
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Smooth Scrollable Body Underneath -->
                <div class="modal-body p-3 bg-slate-950 flex-grow-1" style="background-color: #090d16 !important; overflow-y: auto; -webkit-overflow-scrolling: touch;">

                    <div id="attModalAlert" class="alert d-none py-2 px-3 small font-bold mb-2.5"></div>

                    <!-- TAB 1: TAKE ATTENDANCE & LOG FORM -->
                    <div id="paneTakeAtt">
                        
                        <!-- Redesigned Class Log Options Card -->
                        <div class="p-3 mb-3 rounded-3 bg-slate-900 border border-slate-800 shadow-sm" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                            
                            <!-- Header Row: Date & Serial Number Pointer Badge -->
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2.5 pb-2 border-bottom border-slate-800" style="border-bottom-color: rgba(255, 255, 255, 0.08) !important;">
                                <div class="flex-grow-1" style="max-width: 175px;">
                                    <label class="form-label text-slate-300 mb-1 fw-bold d-flex align-items-center gap-1" style="font-size:0.76rem; color: #cbd5e1 !important;">
                                        <i class="fa-regular fa-calendar text-cyan"></i> Date
                                    </label>
                                    <input type="date" id="attLogDate" class="form-control form-control-sm bg-slate-950 text-white border-slate-800 rounded-3 shadow-none fw-bold" value="{{ date('Y-m-d') }}" style="background-color: #020617 !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; font-size: 0.84rem;">
                                </div>

                                <div class="text-end">
                                    <span class="text-slate-400 d-block font-mono mb-1" style="font-size: 0.68rem; color: #94a3b8 !important;">LOG POINTER</span>
                                    <span class="badge font-mono fw-black px-2.5 py-1 rounded-pill" id="attNextLogPointer" style="background-color: #06b6d4 !important; color: #0f172a !important; font-size: 0.74rem; box-shadow: 0 0 10px rgba(6, 182, 212, 0.35);">Next Entry: #1</span>
                                </div>
                            </div>

                            <!-- Row 2: Period / Class Hour Selector -->
                            <div class="mb-2.5">
                                <label class="form-label text-slate-300 mb-1.5 fw-bold d-flex align-items-center gap-1" style="font-size:0.78rem; color: #cbd5e1 !important;">
                                    <i class="fa-solid fa-clock text-cyan"></i> Select Period / Class Hour:
                                </label>
                                <div class="d-flex justify-content-between align-items-center w-100 gap-1" id="attPeriodsContainer">
                                    @for ($p = 1; $p <= 7; $p++)
                                    <input type="checkbox" class="btn-check" name="attPeriods" id="attP{{ $p }}" value="{{ $p }}" autocomplete="off">
                                    <label class="period-circle-btn flex-fill" for="attP{{ $p }}">P{{ $p }}</label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Sub-batch Selector (for Labs) -->
                            <div id="attSubBatchBox" class="mt-2.5 pt-2 border-top border-slate-800 d-none" style="border-top-color: rgba(255, 255, 255, 0.08) !important;">
                                <label class="form-label text-slate-300 mb-1 fw-bold d-flex align-items-center gap-1" style="font-size:0.76rem; color: #cbd5e1 !important;">
                                    <i class="fa-solid fa-diagram-project text-cyan"></i> Lab Sub-Batch Partitioning:
                                </label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="attSubBatch" id="sbWhole" value="Whole" checked onchange="filterAttStudentsByBatch()">
                                    <label class="btn btn-sm btn-outline-secondary flex-fill rounded-pill py-1 small text-white" for="sbWhole">Whole Class</label>

                                    <input type="radio" class="btn-check" name="attSubBatch" id="sb1" value="1" onchange="filterAttStudentsByBatch()">
                                    <label class="btn btn-sm btn-outline-secondary flex-fill rounded-pill py-1 small text-white" id="sb1Label" for="sb1">Batch 1</label>

                                    <input type="radio" class="btn-check" name="attSubBatch" id="sb2" value="2" onchange="filterAttStudentsByBatch()">
                                    <label class="btn btn-sm btn-outline-secondary flex-fill rounded-pill py-1 small text-white" id="sb2Label" for="sb2">Batch 2</label>
                                </div>
                            </div>

                            <!-- Row 3: Topic Selection & Manual Entry -->
                            <div class="row g-2 mt-1">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label text-slate-300 mb-1 fw-bold d-flex align-items-center gap-1" style="font-size:0.76rem; color: #cbd5e1 !important;">
                                        <i class="fa-solid fa-book-bookmark text-cyan"></i> Syllabus / Lesson Plan Topic
                                    </label>
                                    <select id="attLessonPlanSelect" onchange="onAttLessonPlanChange()" class="form-select form-select-sm bg-slate-950 text-white border-slate-800 rounded-3 shadow-none text-truncate" style="background-color: #020617 !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; font-size: 0.72rem !important; padding-top: 4px; padding-bottom: 4px;">
                                        <option value="">-- Manual Entry --</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label text-slate-300 mb-1 fw-bold d-flex align-items-center gap-1" style="font-size:0.76rem; color: #cbd5e1 !important;">
                                        <i class="fa-solid fa-pen-nib text-cyan"></i> Topics Covered (Manual Entry / Edit)
                                    </label>
                                    <input type="text" id="attTopicsCovered" placeholder="Describe topics covered in class today..." class="form-control form-control-sm bg-slate-950 text-white border-slate-800 rounded-3 shadow-none" style="font-size:0.78rem; background-color: #020617 !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: #ffffff !important;">
                                </div>
                            </div>
                        </div>

                        <!-- Hero Roster Toolbar Header (Structured 2-row layout) -->
                        <div class="p-2.5 mb-2.5 rounded-3 bg-slate-900 border border-slate-800 shadow-sm" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                            <!-- Row 1: Student Count & Stats -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-slate-800 text-cyan border border-slate-700 px-2.5 py-1 font-black" id="attStudentCountLabel" style="background-color: #1e293b !important; color: #06b6d4 !important; font-size: 0.85rem;">Students: 0</span>
                                    <div style="font-size: 0.76rem;">
                                        <span class="fw-bold" style="color: #34d399;" id="attPresentCountText">0 Present</span>
                                        <span class="text-slate-500 mx-1">•</span>
                                        <span class="fw-bold" style="color: #fb7185;" id="attAbsentCountText">0 Absent</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Action Toolbar (Mark All Absent on Left, List/Grid on Right) -->
                            <div class="d-flex align-items-center justify-content-between gap-2 border-top pt-2 border-slate-800" style="border-top-color: rgba(255, 255, 255, 0.08) !important;">
                                <button type="button" onclick="toggleAllAttStudents()" id="btnAttCheckAll" class="btn btn-sm btn-outline-warning py-1 px-2.5 font-extrabold" style="font-size:0.78rem;">
                                    <i class="fa-solid fa-user-xmark me-1"></i> Mark All Absent
                                </button>

                                <div class="btn-group btn-group-sm">
                                    <button type="button" onclick="switchAttViewMode('list')" id="btnAttModeList" class="btn btn-cyan btn-sm py-1 px-2.5 font-extrabold" style="font-size:0.8rem;"><i class="fa-solid fa-list me-1"></i> List</button>
                                    <button type="button" onclick="switchAttViewMode('grid')" id="btnAttModeGrid" class="btn btn-outline-secondary btn-sm py-1 px-2.5 font-extrabold" style="font-size:0.8rem;"><i class="fa-solid fa-border-all me-1"></i> Grid</button>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Roster Container (TIGHTLY FITTED TO ROSTER & SAVE BUTTON) -->
                        <div id="attModeListContainer" class="border border-slate-800 rounded-3 bg-slate-900 mb-1" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                            <table class="table table-dark table-hover table-sm mb-0 align-middle" style="font-size: 0.85rem;">
                                <thead>
                                    <tr class="text-slate-300 border-bottom border-slate-800" style="background-color: #1e293b; color: #cbd5e1;">
                                        <th class="text-center py-2.5" style="width: 60px;">Roll</th>
                                        <th class="py-2.5">Student Name</th>
                                        <th class="text-center py-2.5" style="width: 85px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="attStudentListTbody">
                                    <!-- Rendered via JS -->
                                </tbody>
                            </table>
                        </div>

                        <div id="attModeGridContainer" class="d-none border border-slate-800 rounded-3 bg-slate-900 p-3 mb-1" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                            <div class="row g-2" id="attStudentGridRow">
                                <!-- Rendered via JS -->
                            </div>
                        </div>

                        <!-- Sticky / Prominent Save Attendance Action Bar -->
                        <div class="sticky-bottom bg-slate-900 p-2.5 mt-2.5 rounded-3 border shadow-lg text-center" style="background-color: #0f172a !important; border: 1px solid rgba(6, 182, 212, 0.4) !important; position: sticky; bottom: 0; z-index: 10;">
                            <div id="attSaveInlineAlert" class="alert alert-danger py-2 px-3 small font-bold mb-2 d-none"></div>
                            <button type="button" onclick="saveClassAttendance()" class="btn btn-cyan btn-save-att w-100 py-2.5 rounded-pill fw-black shadow-lg" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: #ffffff !important; border: none; font-size: 0.95rem;">
                                <i class="fa-solid fa-circle-check me-1.5"></i> Save Class Log & Attendance
                            </button>
                        </div>

                    </div>

                    <!-- TAB 2: PAST CLASS LOGS -->
                    <div id="panePastLogs" class="d-none">
                        <div id="attPastLogsContainer" class="overflow-auto" style="max-height: 360px;">
                            <div class="text-center py-4 text-slate-400" style="color: #94a3b8 !important;">
                                <i class="fa-solid fa-spinner fa-spin fs-4 mb-2 text-cyan"></i>
                                <div>Loading past attendance logs...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>

    <script>
        const allTimetablesByDay = @json($fullTimetablesByDay);
        const currentDefaultDayOrder = @json($defaultDayOrder);
        const desktopAttendanceUrl = @json($desktopUrl);
        let workArrangementsArray = [];

        function switchStaffTab(e, tabId) {
            if (e && e.preventDefault) e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
            
            const matchingNav = document.querySelector(`.nav-link-mobile[onclick*="${tabId}"]`);
            if (matchingNav) {
                matchingNav.classList.add('active');
            }

            if (tabId === 'tab-leave') {
                loadMyLeaveHistory();
                loadPendingApprovals();
            }
            if (tabId === 'tab-profile') {
                setTimeout(applyStaffAvatarAdjustments, 50);
            }
        }

        function toggleCclDateField() {
            const type = document.getElementById('slvType').value;
            const cclBox = document.getElementById('cclDateBox');
            const clInfo = document.getElementById('clBalanceInfo');

            if (type === 'Compensatory Casual Leave' || type === 'CCL') {
                cclBox.classList.remove('d-none');
            } else {
                cclBox.classList.add('d-none');
            }

            if (type === 'Casual Leave' || type === 'CL') {
                if (clInfo) clInfo.classList.remove('d-none');
            } else {
                if (clInfo) clInfo.classList.add('d-none');
            }
        }

        function openStaffLeaveModal() {
            const modal = document.getElementById('staffLeaveModal');
            if (modal) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('slvFromDate').value = today;
                document.getElementById('slvToDate').value = today;
                document.getElementById('slvCclDate').value = today;
                document.getElementById('staffLeaveAlert').classList.add('d-none');
                toggleCclDateField();
                workArrangementsArray = [];
                renderWorkArrangements();
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        }

        function closeStaffLeaveModal() {
            const modal = document.getElementById('staffLeaveModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        }

        function addWorkArrangementRow() {
            const cls = document.getElementById('arrClassroom').value.trim();
            const sub = document.getElementById('arrSubstitute').value.trim();
            if (cls && sub) {
                workArrangementsArray.push({ classroom: cls, substitute_name: sub, date: document.getElementById('slvFromDate').value });
                document.getElementById('arrClassroom').value = '';
                document.getElementById('arrSubstitute').value = '';
                renderWorkArrangements();
            }
        }

        function renderWorkArrangements() {
            const list = document.getElementById('arrList');
            if (!list) return;
            list.innerHTML = '';
            workArrangementsArray.forEach((item, idx) => {
                list.innerHTML += `<li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-1 px-0 border-secondary border-opacity-25" style="font-size: 0.75rem;">
                    <span><strong>${item.classroom}</strong> &rarr; ${item.substitute_name}</span>
                    <button type="button" onclick="removeWorkArrangementRow(${idx})" class="btn btn-sm btn-link text-danger p-0 ms-2">&times;</button>
                </li>`;
            });
        }

        function removeWorkArrangementRow(idx) {
            workArrangementsArray.splice(idx, 1);
            renderWorkArrangements();
        }

        function submitStaffLeaveRequest() {
            const leaveType = document.getElementById('slvType').value;
            const sessionType = document.getElementById('slvSession').value;
            const fromDate = document.getElementById('slvFromDate').value;
            const toDate = document.getElementById('slvToDate').value;
            const cclDate = document.getElementById('slvCclDate').value;
            const totalDays = parseFloat(document.getElementById('slvTotalDays').value);
            const reason = document.getElementById('slvReason').value.trim();
            const alertBox = document.getElementById('staffLeaveAlert');
            const btn = document.getElementById('btnSubmitStaffLeave');

            if (!fromDate || !toDate || !reason || isNaN(totalDays)) {
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Please complete all required fields.';
                alertBox.classList.remove('d-none');
                return;
            }

            if ((leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') && !cclDate) {
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Please specify the CCL Date (Date duty was performed).';
                alertBox.classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';

            fetch('/api/staff/leave/apply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    leave_type: leaveType,
                    session_type: sessionType,
                    from_date: fromDate,
                    to_date: toDate,
                    ccl_date: (leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') ? cclDate : null,
                    total_days: totalDays,
                    reason: reason,
                    work_arrangement: workArrangementsArray
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD';

                if (data.status === 'SUCCESS') {
                    alertBox.className = 'alert alert-success py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message;
                    alertBox.classList.remove('d-none');
                    document.getElementById('staffLeaveForm').reset();
                    setTimeout(() => {
                        closeStaffLeaveModal();
                        loadMyLeaveHistory();
                    }, 1200);
                } else {
                    alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message || 'Failed to submit leave.';
                    alertBox.classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD';
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Network error during leave submission.';
                alertBox.classList.remove('d-none');
            });
        }

        function loadMyLeaveHistory() {
            const container = document.getElementById('myLeaveHistoryContainer');
            if (!container) return;

            fetch('/api/staff/leave/my-history')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    const clTotal = data.cl_total || 15;
                    const clTaken = (data.cl_taken !== undefined) ? data.cl_taken : 0;
                    const clInfo = document.getElementById('clBalanceInfo');
                    if (clInfo) {
                        clInfo.textContent = `[Total: ${clTotal}, Taken: ${clTaken}]`;
                    }
                }
                if (data.status === 'SUCCESS' && data.leaves && data.leaves.length > 0) {
                    let html = '';
                    data.leaves.forEach(item => {
                        let statusBadge = '<span class="badge bg-info text-dark">Pending HOD</span>';
                        if (item.overall_status === 'Approved') statusBadge = '<span class="badge bg-success">Final Approved</span>';
                        else if (item.overall_status === 'Rejected') statusBadge = '<span class="badge bg-danger">Rejected</span>';
                        else if (item.overall_status === 'Pending_Coordinator') statusBadge = '<span class="badge bg-warning text-dark">Pending Coordinator</span>';
                        else if (item.overall_status === 'Pending_Principal') statusBadge = '<span class="badge bg-primary">Pending Principal</span>';

                        const dateStr = (item.from_date === item.to_date) ? item.from_date : `${item.from_date} to ${item.to_date}`;
                        const cclText = item.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${item.ccl_date}</span>` : '';

                        html += `<div class="p-2.5 rounded-3 border border-secondary border-opacity-25 bg-dark mb-2">
                            <div class="fw-bold text-white mb-0.5" style="font-size: 0.88rem;">
                                ${item.leave_type} (${item.session_type})
                            </div>
                            <div class="text-secondary small mb-2" style="font-size: 0.75rem;">
                                <i class="fa-regular fa-calendar me-1"></i> ${dateStr} &bull; ${item.total_days} Day(s)${cclText}
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1 border-top border-secondary border-opacity-25">
                                ${statusBadge}
                                <a href="/staff/leave/${item.id}/pdf" target="_blank" class="btn btn-sm btn-outline-info py-0.5 px-2.5 rounded-pill" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<small class="text-secondary d-block py-2">No leave applications submitted yet.</small>';
                }
            });
        }

        function loadPendingApprovals() {
            const container = document.getElementById('pendingApprovalsContainer');
            if (!container) return;

            fetch('/api/staff/leave/pending-approvals')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.approvals.length > 0) {
                    let html = '';
                    const stage = data.role === 'HOD' ? 'HOD' : (data.role === 'Principal' ? 'Principal' : 'Coordinator');
                    data.approvals.forEach(item => {
                        const cclText = item.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${item.ccl_date}</span>` : '';
                        html += `<div class="p-2.5 rounded-3 border border-warning border-opacity-30 bg-slate-900 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-white small">${item.staff_name} (${item.department})</strong>
                                <span class="badge bg-warning text-dark small">${item.leave_type}</span>
                            </div>
                            <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
                                ${item.from_date} to ${item.to_date} (${item.session_type}) &bull; ${item.total_days} Day(s)${cclText}
                            </small>
                            <div class="text-slate-300 small italic mb-2" style="font-size:0.75rem;">"${item.reason}"</div>
                            <div class="d-flex gap-2">
                                <button onclick="actionLeaveApproval(${item.id}, '${stage}', 'Approved')" class="btn btn-sm btn-success py-0.5 px-3 flex-grow-1" style="font-size:0.72rem;">
                                    <i class="fa-solid fa-check me-1"></i> Approve
                                </button>
                                <button onclick="actionLeaveApproval(${item.id}, '${stage}', 'Rejected')" class="btn btn-sm btn-outline-danger py-0.5 px-2" style="font-size:0.72rem;">
                                    <i class="fa-solid fa-xmark me-1"></i> Reject
                                </button>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<small class="text-secondary d-block py-1">No pending leave requests in your approval queue.</small>';
                }
            });
        }

        function actionLeaveApproval(leaveId, stage, action) {
            const remarks = prompt(`Enter optional remarks for ${action}:`) || '';
            fetch('/api/staff/leave/process-approval', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ leave_id: leaveId, stage: stage, action: action, remarks: remarks })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    loadPendingApprovals();
                } else {
                    alert(data.message || 'Error processing approval.');
                }
            });
        }


        function toggleDayPicker() {
            const panel = document.getElementById('dayOrderPickerPanel');
            if (panel) {
                panel.classList.toggle('d-none');
            }
        }

        function renderDayOrderView(dayKey) {
            document.querySelectorAll('.day-order-btn').forEach(btn => {
                if (btn.dataset.day === dayKey) {
                    btn.className = 'btn btn-sm btn-cyan px-2.5 py-1.5 rounded-pill fw-black text-dark day-order-btn flex-fill shadow-sm';
                    btn.style.fontSize = '0.8rem';
                } else {
                    btn.className = 'btn btn-sm btn-outline-secondary px-2.5 py-1 rounded-pill day-order-btn flex-fill';
                    btn.style.fontSize = '0.75rem';
                }
            });

            const slots = allTimetablesByDay[dayKey] || [];
            const container = document.getElementById('timetableScheduleContainer');
            const badgeLabel = document.getElementById('selectedDayBadge');
            if (badgeLabel) badgeLabel.innerHTML = `<i class="fa-solid fa-calendar-day fs-6 me-1"></i> <span>${dayKey}</span> <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.75rem;"></i>`;

            if (!container) return;
            if (slots.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 px-3 rounded-3" style="background: rgba(15, 23, 42, 0.6); border: 1px dashed rgba(255, 255, 255, 0.12);">
                        <i class="fa-solid fa-mug-hot text-cyan mb-2" style="font-size: 1.6rem; filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.4));"></i>
                        <strong class="text-white d-block mb-1" style="font-size: 0.92rem;">No Classes Scheduled for You Today</strong>
                        <span class="text-secondary d-block" style="font-size: 0.76rem;">You have no allotted teaching periods on <strong>${dayKey}</strong>.</span>
                    </div>`;
                return;
            }

            // Group continuous periods for the same subject & classroom
            const sortedSlots = [...slots].sort((a, b) => parseInt(a.period) - parseInt(b.period));
            const mergedSlots = [];
            sortedSlots.forEach(st => {
                const pNum = parseInt(st.period);
                if (mergedSlots.length > 0) {
                    const lastGroup = mergedSlots[mergedSlots.length - 1];
                    const lastPNum = lastGroup.periods[lastGroup.periods.length - 1];
                    
                    if (
                        st.subject_code === lastGroup.subject_code &&
                        st.classroom_id === lastGroup.classroom_id &&
                        pNum === lastPNum + 1
                    ) {
                        lastGroup.periods.push(pNum);
                        return;
                    }
                }
                
                mergedSlots.push({
                    periods: [pNum],
                    classroom_id: st.classroom_id,
                    subject_code: st.subject_code,
                    subject_name: st.subject_name,
                    batch_subject_id: st.batch_subject_id,
                    progress_percent: st.progress_percent || 0,
                    completed_lesson_plans: st.completed_lesson_plans || 0,
                    total_lesson_plans: st.total_lesson_plans || 0
                });
            });

            let html = '';
            mergedSlots.forEach(st => {
                const periodText = st.periods.length > 1 ? `Periods ${st.periods.join(', ')}` : `Period ${st.periods[0]}`;
                const periodArg = st.periods.join(',');
                const hasProgress = st.total_lesson_plans > 0;
                
                html += `
                    <div class="p-3 rounded-3 bg-slate-900 border border-slate-800 mb-2.5 shadow-sm" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <div>
                                <span class="badge font-mono me-1 fw-black" style="background-color: #38bdf8 !important; color: #000000 !important; border: 1px solid #38bdf8 !important; font-size: 0.74rem;">${periodText}</span>
                                <strong class="text-white font-mono ms-1 fw-black" style="font-size: 0.95rem; color: #ffffff !important; letter-spacing: 0.5px;">${st.subject_code}</strong>
                                <small class="text-slate-400 d-block mt-1" style="font-size: 0.76rem; color: #94a3b8 !important;">${st.subject_name || ''}${st.subject_name ? ' | ' : ''}<strong class="text-cyan">${st.classroom_id}</strong></small>
                            </div>
                            <button onclick="openClassAttendanceModal('${st.batch_subject_id || ''}', '${periodArg}', '${st.subject_code}', '${st.classroom_id}', '${(st.subject_name || '').replace(/'/g, "\\'")}')" class="btn btn-sm btn-cyan px-3 py-1.5 rounded-pill fw-black shadow-sm flex-shrink-0 ms-2" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); color: #ffffff !important; border: none; font-size: 0.76rem;">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Attendance
                            </button>
                        </div>
                        ${hasProgress ? `
                        <div class="mt-2.5 pt-2 border-top border-slate-800" style="border-top-color: rgba(255, 255, 255, 0.1) !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                                <span class="text-slate-400" style="color: #94a3b8 !important;">Syllabus / Topic Coverage</span>
                                <span class="text-cyan font-mono fw-bold" style="color: #06b6d4 !important;">${st.progress_percent}% (${st.completed_lesson_plans}/${st.total_lesson_plans} topics)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 5px; background-color: rgba(255, 255, 255, 0.1);">
                                <div class="progress-bar rounded-pill" role="progressbar" style="width: ${st.progress_percent}%; background: linear-gradient(90deg, #06b6d4 0%, #3b82f6 100%) !important;"></div>
                            </div>
                        </div>` : ''}
                    </div>`;
            });
            container.innerHTML = html;
        }

        async function selectDayOrder(dayKey) {
            const confirmed = confirm(`Are you sure you want to set the institution-wide Day Order for today to "${dayKey}"?\n\nThis will update timetables for all staff and students across the institution.`);
            if (!confirmed) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch('/api/system/set-day-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ day_order: dayKey })
                });
                const data = await response.json();
                if (data.status === 'SUCCESS') {
                    renderDayOrderView(dayKey);
                    const panel = document.getElementById('dayOrderPickerPanel');
                    if (panel) panel.classList.add('d-none');
                    alert(`Institution-wide Day Order updated to "${dayKey}" for today.`);
                } else {
                    alert('Error updating Day Order: ' + (data.message || 'Server Error'));
                }
            } catch (err) {
                console.error(err);
                alert('Network error updating Day Order.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderDayOrderView(currentDefaultDayOrder);
            loadMyLeaveHistory();
            loadPendingApprovals();
        });

        function processMobileLeave(leaveId, decision) {
            if (!confirm(`Are you sure you want to set this leave status to ${decision}?`)) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/mentoring/leave/action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ leave_id: leaveId, status: decision })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    location.reload();
                } else {
                    alert(data.message || 'Error processing leave request.');
                }
            });
        }

        function updateStaffPassword() {
            const oldPwd = document.getElementById('sOldPwd').value;
            const newPwd = document.getElementById('sNewPwd').value;
            const alertDiv = document.getElementById('staffPwdAlert');

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
                    alertDiv.classList.remove('d-none');
                }
            });
        }

        /* --- Class Log & Attendance Modal Logic --- */
        let currentAttBatchSubjectId = null;
        let currentAttStudents = [];
        let currentAttSubjectType = '';
        let currentAttViewMode = 'list';
        let isAttAllChecked = true;

        function openClassAttendanceModal(batchSubjectId, period, subjectCode, classroomId, subjectName) {
            if (!batchSubjectId) {
                const assignmentsData = @json($assignments);
                const found = assignmentsData.find(a => a.subject_code === subjectCode && a.classroom_id === classroomId);
                if (found) {
                    batchSubjectId = found.id;
                    if (!subjectName) subjectName = found.subject_name;
                }
            }

            if (!batchSubjectId) {
                alert("Subject assignment record not found for attendance marking.");
                return;
            }

            currentAttBatchSubjectId = batchSubjectId;
            const modal = document.getElementById('classAttendanceModal');
            const alertBox = document.getElementById('attModalAlert');
            if (alertBox) alertBox.classList.add('d-none');

            document.getElementById('attSubjectConfirmCode').textContent = subjectCode || 'Class';
            document.getElementById('attSubjectConfirmName').textContent = subjectName ? ` - ${subjectName}` : '';
            document.getElementById('attBatchConfirmCode').textContent = classroomId || 'Active Batch';

            document.querySelectorAll('input[name="attPeriods"]').forEach(cb => cb.checked = false);
            if (period) {
                const periodArr = String(period).split(',').map(p => p.trim());
                periodArr.forEach(pVal => {
                    const targetCb = document.getElementById(`attP${pVal}`);
                    if (targetCb) targetCb.checked = true;
                });
            } else {
                const p1 = document.getElementById('attP1');
                if (p1) p1.checked = true;
            }

            // Immediate UI reset to prevent data bleeding between subjects
            const resetNextPointer = document.getElementById('attNextLogPointer');
            if (resetNextPointer) {
                resetNextPointer.textContent = 'Next Entry: #0';
                resetNextPointer.style.backgroundColor = '#06b6d4';
                resetNextPointer.style.color = '#0f172a';
            }
            const resetLpSelect = document.getElementById('attLessonPlanSelect');
            if (resetLpSelect) resetLpSelect.innerHTML = '<option value="">-- Manual Entry --</option>';
            const resetTopics = document.getElementById('attTopicsCovered');
            if (resetTopics) resetTopics.value = '';

            switchAttModalTab('take');

            fetch(`/api/staff/attendance/subjects/${batchSubjectId}/details`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        currentAttStudents = (data.students || []).map(s => ({ ...s, present: true }));
                        currentAttSubjectType = data.subject_type || 'Theory';
                        const badgeElem = document.getElementById('attSubjectTypeBadge');
                        if (badgeElem) badgeElem.textContent = currentAttSubjectType;

                        // Set Serial Number Tracker Pointer
                        const nextSlNo = typeof data.next_log_sl_no !== 'undefined' ? data.next_log_sl_no : 0;
                        const nextPointer = document.getElementById('attNextLogPointer');
                        if (nextPointer) {
                            nextPointer.textContent = `Next Entry: #${nextSlNo}`;
                            nextPointer.style.backgroundColor = '#06b6d4';
                            nextPointer.style.color = '#0f172a';
                        }

                        const isLab = (currentAttSubjectType && (
                            currentAttSubjectType.toLowerCase().includes('lab') ||
                            currentAttSubjectType.toLowerCase().includes('practical') ||
                            currentAttSubjectType.toLowerCase().includes('practicum')
                        ));
                        const subBatchBox = document.getElementById('attSubBatchBox');
                        if (isLab) {
                            subBatchBox.classList.remove('d-none');
                            const half = Math.ceil(currentAttStudents.length / 2);
                            document.getElementById('sb1Label').textContent = `Batch 1 (1-${half})`;
                            document.getElementById('sb2Label').textContent = `Batch 2 (${half + 1}+)`;
                        } else {
                            subBatchBox.classList.add('d-none');
                            document.getElementById('sbWhole').checked = true;
                        }

                        const lpSelect = document.getElementById('attLessonPlanSelect');
                        lpSelect.innerHTML = '<option value="">-- Manual Entry --</option>';
                        (data.lesson_plans || []).forEach((lp, idx) => {
                            const opt = document.createElement('option');
                            opt.value = lp.id;
                            let rawTopic = lp.topic_content || '';
                            opt.dataset.topic = rawTopic;
                            let displayTopic = rawTopic.length > 48 ? rawTopic.substring(0, 45) + '...' : rawTopic;
                            opt.textContent = `#${idx + 1}. [${lp.co_id || 'CO'}] ${displayTopic} (${lp.status || 'Pending'})`;
                            opt.title = rawTopic;
                            lpSelect.appendChild(opt);
                        });

                        document.getElementById('attTopicsCovered').value = '';

                        filterAttStudentsByBatch();

                        if (modal) {
                            modal.style.display = 'block';
                            modal.classList.add('show');
                        }
                    } else {
                        alert(data.message || "Failed to load class details.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Network error fetching class details.");
                });
        }

        function closeClassAttendanceModal() {
            const modal = document.getElementById('classAttendanceModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        }

        function switchAttModalTab(tab) {
            const btnTake = document.getElementById('tabBtnTakeAtt');
            const btnLogs = document.getElementById('tabBtnPastLogs');
            const paneTake = document.getElementById('paneTakeAtt');
            const paneLogs = document.getElementById('panePastLogs');

            if (tab === 'take') {
                btnTake.className = 'nav-link active py-1 px-2.5 rounded-pill text-white fw-bold';
                btnLogs.className = 'nav-link py-1 px-2.5 rounded-pill text-secondary fw-bold';
                paneTake.classList.remove('d-none');
                paneLogs.classList.add('d-none');
            } else {
                btnLogs.className = 'nav-link active py-1 px-2.5 rounded-pill text-white fw-bold';
                btnTake.className = 'nav-link py-1 px-2.5 rounded-pill text-secondary fw-bold';
                paneLogs.classList.remove('d-none');
                paneTake.classList.add('d-none');
                loadClassAttendanceReports();
            }
        }

        function onAttLessonPlanChange() {
            const select = document.getElementById('attLessonPlanSelect');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && select.value) {
                const topic = selectedOption.dataset.topic || selectedOption.title || '';
                document.getElementById('attTopicsCovered').value = topic;
            } else {
                document.getElementById('attTopicsCovered').value = '';
            }
        }

        function getFilteredAttStudents() {
            const subBatchBox = document.getElementById('attSubBatchBox');
            if (subBatchBox.classList.contains('d-none')) {
                return currentAttStudents;
            }
            const selected = document.querySelector('input[name="attSubBatch"]:checked');
            const val = selected ? selected.value : 'Whole';
            if (val === 'Whole') return currentAttStudents;

            const half = Math.ceil(currentAttStudents.length / 2);
            if (val === '1') {
                return currentAttStudents.slice(0, half);
            } else {
                return currentAttStudents.slice(half);
            }
        }

        function filterAttStudentsByBatch() {
            const filtered = getFilteredAttStudents();
            document.getElementById('attStudentCountLabel').textContent = `Students: ${filtered.length}`;
            renderAttRoster();
        }

        function switchAttViewMode(mode) {
            currentAttViewMode = mode;
            const btnList = document.getElementById('btnAttModeList');
            const btnGrid = document.getElementById('btnAttModeGrid');
            const contList = document.getElementById('attModeListContainer');
            const contGrid = document.getElementById('attModeGridContainer');

            if (mode === 'list') {
                btnList.className = 'btn btn-cyan btn-sm py-1.5 px-3 font-extrabold';
                btnGrid.className = 'btn btn-outline-secondary btn-sm py-1.5 px-3 font-extrabold';
                contList.classList.remove('d-none');
                contGrid.classList.add('d-none');
            } else {
                btnGrid.className = 'btn btn-cyan btn-sm py-1.5 px-3 font-extrabold';
                btnList.className = 'btn btn-outline-secondary btn-sm py-1.5 px-3 font-extrabold';
                contGrid.classList.remove('d-none');
                contList.classList.add('d-none');
            }
            renderAttRoster();
        }

        function renderAttRoster() {
            const filtered = getFilteredAttStudents();
            const presentCount = filtered.filter(s => s.present).length;
            const absentCount = filtered.length - presentCount;
            
            const countLabel = document.getElementById('attStudentCountLabel');
            const presText = document.getElementById('attPresentCountText');
            const absText = document.getElementById('attAbsentCountText');
            
            if (countLabel) countLabel.textContent = `Students: ${filtered.length}`;
            if (presText) presText.textContent = `${presentCount} Present`;
            if (absText) absText.textContent = `${absentCount} Absent`;

            if (currentAttViewMode === 'list') {
                const tbody = document.getElementById('attStudentListTbody');
                if (filtered.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-slate-400 py-4 fs-6">No students found.</td></tr>';
                    return;
                }
                let html = '';
                filtered.forEach((s, idx) => {
                    const roll = s.roll_no || (idx + 1);
                    html += `<tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.06); cursor: pointer;" onclick="toggleAttStudentRowClick('${s.reg_no}', event)">
                        <td class="text-center py-2.5" style="width: 55px;">
                            <span class="badge bg-slate-800 text-cyan border border-slate-700 font-mono px-2 py-1" style="font-size: 0.82rem; background-color: #1e293b !important; color: #06b6d4 !important;">${roll}</span>
                        </td>
                        <td class="py-2.5">
                            <div class="fw-bold text-white fs-6 mb-0">${s.name}</div>
                            <div class="text-slate-400 font-mono" style="font-size: 0.72rem; color: #94a3b8 !important;">${s.reg_no}</div>
                        </td>
                        <td class="text-center py-2.5" style="width: 85px;">
                            <input type="checkbox" id="chkAtt_${s.reg_no}" onchange="toggleAttStudentPresent('${s.reg_no}', this.checked)" ${s.present ? 'checked' : ''} class="form-check-input" style="width: 24px; height: 24px; cursor: pointer; accent-color: #06b6d4;">
                        </td>
                    </tr>`;
                });
                tbody.innerHTML = html;
            } else {
                const gridRow = document.getElementById('attStudentGridRow');
                if (filtered.length === 0) {
                    gridRow.innerHTML = '<div class="col-12 text-center text-slate-400 py-4 fs-6">No students found.</div>';
                    return;
                }
                let html = '';
                filtered.forEach((s, idx) => {
                    const roll = s.roll_no || (idx + 1);
                    const btnStyle = s.present
                        ? 'background: rgba(16, 185, 129, 0.25); color: #34d399; border: 2px solid rgba(52, 211, 153, 0.6);'
                        : 'background: rgba(244, 63, 94, 0.25); color: #fb7185; border: 2px solid rgba(251, 113, 133, 0.6);';
                    html += `<div class="col-3 col-sm-2 text-center p-1">
                        <button type="button" onclick="toggleAttStudentGrid('${s.reg_no}')" class="btn w-100 font-black rounded-3 shadow-sm py-2 font-mono" style="${btnStyle} font-size: 1.18rem; font-weight: 900; min-height: 52px;">
                            ${roll}
                        </button>
                    </div>`;
                });
                gridRow.innerHTML = html;
            }
        }

        function toggleAttStudentRowClick(regNo, event) {
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'LABEL') return;
            const s = currentAttStudents.find(st => st.reg_no === regNo);
            if (s) {
                s.present = !s.present;
                renderAttRoster();
            }
        }

        function toggleAttStudentPresent(regNo, isPresent) {
            const s = currentAttStudents.find(st => st.reg_no === regNo);
            if (s) s.present = isPresent;
        }

        function toggleAttStudentGrid(regNo) {
            const s = currentAttStudents.find(st => st.reg_no === regNo);
            if (s) {
                s.present = !s.present;
                renderAttRoster();
            }
        }

        function toggleAllAttStudents() {
            isAttAllChecked = !isAttAllChecked;
            const filtered = getFilteredAttStudents();
            filtered.forEach(s => s.present = isAttAllChecked);
            document.getElementById('btnAttCheckAll').textContent = isAttAllChecked ? "Mark All Absent" : "Mark All Present";
            renderAttRoster();
        }

        function saveClassAttendance() {
            const dateElem = document.getElementById('attLogDate');
            const date = dateElem ? dateElem.value : '';
            const checkedPeriods = Array.from(document.querySelectorAll('input[name="attPeriods"]:checked')).map(el => parseInt(el.value));
            const lpSelect = document.getElementById('attLessonPlanSelect');
            const lpId = lpSelect ? lpSelect.value : '';
            const topicsElem = document.getElementById('attTopicsCovered');
            const topics = topicsElem ? topicsElem.value.trim() : '';

            // Reset field error highlights
            if (topicsElem) {
                topicsElem.style.border = '1px solid rgba(255, 255, 255, 0.15)';
            }

            function displayAttFeedback(msg, isError = true, targetElem = null) {
                const alertBoxes = document.querySelectorAll('#attModalAlert, #attSaveInlineAlert');
                alertBoxes.forEach(box => {
                    box.className = isError 
                        ? 'alert alert-danger py-2 px-3 small font-bold mb-2.5 shadow-sm'
                        : 'alert alert-success py-2 px-3 small font-bold mb-2.5 shadow-sm';
                    box.innerHTML = isError
                        ? `<i class="fa-solid fa-circle-exclamation me-1.5"></i> ${msg}`
                        : `<i class="fa-solid fa-circle-check me-1.5"></i> ${msg}`;
                    box.classList.remove('d-none');
                });

                if (targetElem) {
                    targetElem.style.border = '2px solid #f43f5e';
                    targetElem.focus();
                    targetElem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    const inlineAlert = document.getElementById('attSaveInlineAlert');
                    if (inlineAlert && !isError) {
                        inlineAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        const topAlert = document.getElementById('attModalAlert');
                        if (topAlert) topAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }

            if (!currentAttBatchSubjectId) {
                displayAttFeedback('Invalid subject session. Please close and re-open the class card.', true);
                return;
            }
            if (checkedPeriods.length === 0) {
                const periodsContainer = document.getElementById('attPeriodsContainer');
                displayAttFeedback('Please select at least one Period / Hour (e.g. P1, P2).', true, periodsContainer);
                return;
            }
            if (!topics) {
                displayAttFeedback('Please enter or select the topics covered in class today.', true, topicsElem);
                return;
            }

            // Hide previous alerts during submit
            document.querySelectorAll('#attModalAlert, #attSaveInlineAlert').forEach(box => box.classList.add('d-none'));

            const present = [];
            const absent = [];
            const filtered = getFilteredAttStudents();
            filtered.forEach(s => {
                if (s.present) present.push(s.reg_no);
                else absent.push(s.reg_no);
            });

            const subBatchBox = document.getElementById('attSubBatchBox');
            const selectedSb = document.querySelector('input[name="attSubBatch"]:checked');
            const subBatchVal = (subBatchBox && !subBatchBox.classList.contains('d-none')) 
                ? (selectedSb ? selectedSb.value : 'Whole') 
                : 'Whole';

            const allSaveBtns = document.querySelectorAll('.btn-save-att, #btnSaveClassAtt');
            allSaveBtns.forEach(b => {
                b.disabled = true;
                b.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> Saving Attendance...';
            });

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            fetch('/api/staff/attendance/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    batch_subject_id: currentAttBatchSubjectId,
                    date: date,
                    periods: checkedPeriods,
                    lesson_plan_id: (lpId && !isNaN(parseInt(lpId))) ? parseInt(lpId) : null,
                    topics_covered: topics,
                    present_students: present,
                    absent_students: absent,
                    sub_batch: subBatchVal
                })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errData => {
                        throw new Error(errData.message || `Server returned status ${res.status}`);
                    }).catch(() => {
                        throw new Error(`HTTP error ${res.status}: ${res.statusText}`);
                    });
                }
                return res.json();
            })
            .then(data => {
                allSaveBtns.forEach(b => {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-circle-check me-1.5"></i> Save Class Log & Attendance';
                });
                if (data.status === 'SUCCESS') {
                    displayAttFeedback(data.message || 'Attendance saved successfully!', false);
                    setTimeout(() => {
                        closeClassAttendanceModal();
                    }, 1200);
                } else {
                    displayAttFeedback(data.message || 'Failed to save attendance log.', true);
                }
            })
            .catch(err => {
                console.error('Attendance Save Error:', err);
                allSaveBtns.forEach(b => {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-circle-check me-1.5"></i> Save Class Log & Attendance';
                });
                displayAttFeedback(err.message || 'Network error saving class log.', true);
            });
        }

        window.attPastLogsCache = [];

        function loadClassAttendanceReports() {
            const container = document.getElementById('attPastLogsContainer');
            if (!container || !currentAttBatchSubjectId) return;

            fetch(`/api/staff/attendance/subjects/${currentAttBatchSubjectId}/reports`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS' && data.logs && data.logs.length > 0) {
                        window.attPastLogsCache = data.logs;
                        let html = '';
                        data.logs.forEach((log, idx) => {
                            const slNoDisplay = log.sl_no ? `Log #${log.sl_no}` : `Log #${data.logs.length - idx}`;
                            html += `<div class="p-2.5 rounded-3 border border-slate-800 bg-slate-900 mb-2 shadow-sm" style="background-color: #0f172a !important; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                                <div class="d-flex justify-content-between align-items-center mb-1.5">
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge font-mono fw-black px-2 py-1" style="background-color: #06b6d4 !important; color: #0f172a !important; font-size: 0.76rem;">${slNoDisplay}</span>
                                        <span class="badge font-mono fw-bold px-2 py-1" style="background-color: #1e293b !important; color: #38bdf8 !important; font-size: 0.74rem;">Period ${log.period} &bull; ${log.date}</span>
                                    </div>
                                    <button type="button" onclick="editPastClassLog(${idx})" class="btn btn-sm btn-outline-cyan py-0.5 px-2 font-bold" style="font-size: 0.76rem; border-color: rgba(6, 182, 212, 0.5) !important; color: #06b6d4 !important;">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Log
                                    </button>
                                </div>
                                <div class="text-white small fw-bold mb-1" style="font-size:0.82rem;">${log.topics_covered || 'No topic description'}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-slate-400 font-mono" style="font-size:0.72rem; color: #94a3b8 !important;">Sub-batch: ${log.sub_batch || 'Whole'} &bull; Recorded: ${log.recorded_by || ''}</small>
                                    <small class="font-mono fw-bold" style="font-size:0.74rem;">
                                        <span class="text-emerald" style="color: #34d399;">${log.present_count} Present</span> / <span class="text-rose" style="color: #fb7185;">${log.absent_count} Absent</span>
                                    </small>
                                </div>
                            </div>`;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div class="text-center py-4 text-slate-400 small">No past class logs recorded for this subject yet.</div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = '<div class="text-center py-3 text-rose small" style="color: #fb7185;">Error loading class log history.</div>';
                });
        }

        function editPastClassLog(idx) {
            const log = window.attPastLogsCache[idx];
            if (!log) return;

            // 1. Set Date
            if (log.date) {
                document.getElementById('attLogDate').value = log.date;
            }

            // 2. Set Period
            document.querySelectorAll('input[name="attPeriods"]').forEach(chk => {
                chk.checked = (parseInt(chk.value) === parseInt(log.period));
            });

            // 3. Set Sub-batch if applicable
            if (log.sub_batch === '1') {
                const el = document.getElementById('sb1');
                if (el) el.checked = true;
            } else if (log.sub_batch === '2') {
                const el = document.getElementById('sb2');
                if (el) el.checked = true;
            } else {
                const el = document.getElementById('sbWhole');
                if (el) el.checked = true;
            }

            // 4. Set Topics Covered
            document.getElementById('attTopicsCovered').value = log.topics_covered || '';

            // 5. Select Lesson Plan if matched
            if (log.lesson_plan_id) {
                document.getElementById('attLessonPlanSelect').value = log.lesson_plan_id;
            }

            // 6. Update student attendance states from saved JSON
            let presentArr = [];
            try {
                presentArr = typeof log.present_students === 'string' ? JSON.parse(log.present_students || '[]') : (log.present_students || []);
            } catch (e) {
                presentArr = [];
            }

            currentAttStudents.forEach(s => {
                s.present = presentArr.includes(s.reg_no);
            });

            // Update Log Serial Pointer Badge to indicate editing
            const nextPointer = document.getElementById('attNextLogPointer');
            if (nextPointer && log.sl_no) {
                nextPointer.textContent = `Editing: #${log.sl_no}`;
                nextPointer.style.backgroundColor = '#f59e0b';
                nextPointer.style.color = '#0f172a';
            }

            // 7. Switch to Take Attendance tab and refresh roster
            switchAttModalTab('take');
            filterAttStudentsByBatch();
        }

        // Handle Staff Profile Photo Upload
        function handleStaffPhotoUpload(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];

            if (file.size > 10 * 1024 * 1024) {
                showStaffPhotoAlert('File size exceeds 10MB limit.', 'text-danger');
                return;
            }

            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');

            showStaffPhotoAlert('<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading photo...', 'text-cyan');

            fetch('/api/staff/profile/upload-photo', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.photo_url) {
                    const cacheBustedUrl = data.photo_url + '?t=' + new Date().getTime();

                    const bannerImg = document.getElementById('staffBannerPhoto');
                    if (bannerImg) {
                        bannerImg.src = cacheBustedUrl;
                    } else {
                        const placeholder = document.getElementById('staffBannerPhotoPlaceholder');
                        if (placeholder) {
                            const newImg = document.createElement('img');
                            newImg.id = 'staffBannerPhoto';
                            newImg.src = cacheBustedUrl;
                            newImg.className = 'avatar-mobile';
                            placeholder.parentNode.replaceChild(newImg, placeholder);
                        }
                    }

                    const tabImg = document.getElementById('staffProfileTabPhoto');
                    if (tabImg) {
                        tabImg.src = cacheBustedUrl;
                    } else {
                        const tabPlaceholder = document.getElementById('staffProfileTabPlaceholder');
                        if (tabPlaceholder) {
                            const newTabImg = document.createElement('img');
                            newTabImg.id = 'staffProfileTabPhoto';
                            newTabImg.src = cacheBustedUrl;
                            newTabImg.className = 'avatar-mobile';
                            newTabImg.style.width = '100%';
                            newTabImg.style.height = '100%';
                            tabPlaceholder.parentNode.replaceChild(newTabImg, tabPlaceholder);
                        }
                    }

                    setTimeout(applyStaffAvatarAdjustments, 100);

                    showStaffPhotoAlert('<i class="fa-solid fa-circle-check me-1"></i> Profile photo updated successfully!', 'text-success');
                    setTimeout(() => {
                        const alertEl = document.getElementById('staffPhotoUploadAlert');
                        if (alertEl) alertEl.classList.add('d-none');
                    }, 4000);
                } else {
                    showStaffPhotoAlert(data.message || 'Failed to upload photo.', 'text-danger');
                }
            })
            .catch(err => {
                console.error('Photo upload error:', err);
                showStaffPhotoAlert('Server error during photo upload.', 'text-danger');
            });
        }

        function adjustStaffAvatarZoom(val) {
            const zoom = parseFloat(val).toFixed(2);
            document.querySelectorAll('#avatarZoomVal, #mobileZoomVal').forEach(el => el.innerText = zoom + 'x');
            document.querySelectorAll('#avatarZoomSlider, #mobileZoomSlider').forEach(el => el.value = val);
            localStorage.setItem('staffAvatarZoom', val);
            applyStaffAvatarAdjustments();
        }

        function adjustStaffAvatarPos(val) {
            document.querySelectorAll('#avatarPosVal, #mobilePosVal').forEach(el => el.innerText = val + '%');
            document.querySelectorAll('#avatarPosSlider, #mobilePosSlider').forEach(el => el.value = val);
            localStorage.setItem('staffAvatarPos', val);
            applyStaffAvatarAdjustments();
        }

        function resetStaffAvatarAdjustments() {
            adjustStaffAvatarZoom(1.08);
            adjustStaffAvatarPos(15);
            saveStaffAvatarFramingToServer();
        }

        function saveStaffAvatarFramingToServer() {
            const zoom = localStorage.getItem('staffAvatarZoom') || '1.08';
            const pos = localStorage.getItem('staffAvatarPos') || '15';

            const btn = document.querySelectorAll('#saveFramingBtn, #mobileSaveFramingBtn');
            btn.forEach(b => {
                b.disabled = true;
                b.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';
            });

            fetch('/api/staff/profile/save-avatar-framing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ zoom: zoom, pos: pos })
            })
            .then(res => res.json())
            .then(data => {
                btn.forEach(b => {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Framing';
                });
                if (data.status === 'SUCCESS') {
                    showStaffPhotoAlert('<i class="fa-solid fa-circle-check me-1"></i> ' + data.message, 'text-success');
                } else {
                    showStaffPhotoAlert(data.message || 'Failed to save framing.', 'text-danger');
                }
            })
            .catch(err => {
                console.error('Framing save error:', err);
                btn.forEach(b => {
                    b.disabled = false;
                    b.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Framing';
                });
            });
        }

        function applyStaffAvatarAdjustments() {
            const serverZoom = '{{ $staff->avatar_zoom ?? session("avatarZoom") ?? "" }}';
            const serverPos = '{{ $staff->avatar_pos ?? session("avatarPos") ?? "" }}';
            if (serverZoom && !localStorage.getItem('staffAvatarZoom')) {
                localStorage.setItem('staffAvatarZoom', serverZoom);
            }
            if (serverPos && !localStorage.getItem('staffAvatarPos')) {
                localStorage.setItem('staffAvatarPos', serverPos);
            }

            const zoom = localStorage.getItem('staffAvatarZoom') || serverZoom || '1.08';
            const pos = localStorage.getItem('staffAvatarPos') || serverPos || '15';
            
            document.querySelectorAll('#avatarZoomVal, #mobileZoomVal').forEach(el => el.innerText = parseFloat(zoom).toFixed(2) + 'x');
            document.querySelectorAll('#avatarZoomSlider, #mobileZoomSlider').forEach(el => el.value = zoom);
            document.querySelectorAll('#avatarPosVal, #mobilePosVal').forEach(el => el.innerText = pos + '%');
            document.querySelectorAll('#avatarPosSlider, #mobilePosSlider').forEach(el => el.value = pos);

            document.querySelectorAll('#staffProfileImg, .avatar-mobile, #staffBannerPhoto, #staffProfileTabPhoto, #sidebarAvatarContainer img, aside img.rounded-full, #sidebarStaffImg').forEach(img => {
                if (img && img.tagName === 'IMG') {
                    img.style.objectFit = 'cover';
                    img.style.objectPosition = `center ${pos}%`;
                    img.style.transform = `scale(${zoom})`;
                    img.style.transformOrigin = `center ${pos}%`;
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyStaffAvatarAdjustments);
        } else {
            applyStaffAvatarAdjustments();
        }

        function showStaffPhotoAlert(msg, colorClass) {
            const alertEl = document.getElementById('staffPhotoUploadAlert');
            if (alertEl) {
                alertEl.className = 'small mt-2 font-bold ' + colorClass;
                alertEl.innerHTML = msg;
                alertEl.classList.remove('d-none');
            }
        }

        function base64ToBuffer(base64) {
            const binary = atob(base64.replace(/-/g, '+').replace(/_/g, '/'));
            const len = binary.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            return bytes.buffer;
        }

        function bufferToBase64(buffer) {
            const binary = String.fromCharCode(...new Uint8Array(buffer));
            return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
        }

        async function registerMobileBiometric() {
            const alertEl = document.getElementById('staffBioAlert');
            if (alertEl) alertEl.classList.add('d-none');

            if (!window.PublicKeyCredential) {
                showStaffBioAlert('WebAuthn / Biometric authentication is not supported on this browser.', 'text-danger');
                return;
            }

            try {
                showStaffBioAlert('Preparing biometric challenge...', 'text-warning');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const optRes = await fetch('/api/webauthn/register-options', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const optData = await optRes.json();

                if (optData.status !== 'SUCCESS') {
                    showStaffBioAlert(optData.message || 'Failed to initialize biometric registration.', 'text-danger');
                    return;
                }

                const options = optData.options;
                options.challenge = base64ToBuffer(options.challenge);
                options.user.id = base64ToBuffer(options.user.id);

                showStaffBioAlert('Please scan your fingerprint on your device sensor now...', 'text-info');
                const credential = await navigator.credentials.create({ publicKey: options });

                const credentialId = bufferToBase64(credential.rawId);
                const deviceName = /iPhone|iPad|iPod/.test(navigator.userAgent) ? 'Apple Touch/Face ID' : (/Android/.test(navigator.userAgent) ? 'Android Fingerprint' : 'Mobile Biometric Sensor');

                showStaffBioAlert('Saving biometric credential...', 'text-warning');

                const regRes = await fetch('/api/webauthn/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        credentialId: credentialId,
                        deviceName: deviceName
                    })
                });
                const regData = await regRes.json();

                if (regData.status === 'SUCCESS') {
                    localStorage.setItem('carmel_biometric_cred_id', credentialId);
                    if (optData.user && optData.user.name) {
                        localStorage.setItem('carmel_registered_biometric_mobile', optData.user.name);
                        localStorage.setItem('carmel_last_staff_mobile', optData.user.name);
                    }
                    showStaffBioAlert('<i class="fa-solid fa-circle-check me-1"></i> ' + regData.message, 'text-success');
                    loadRegisteredBioDevices();
                } else {
                    showStaffBioAlert(regData.message || 'Failed to save biometric credential.', 'text-danger');
                }
            } catch (err) {
                if (err.name === 'NotAllowedError') {
                    showStaffBioAlert('Fingerprint registration cancelled or timed out.', 'text-warning');
                } else {
                    showStaffBioAlert('Biometric registration error: ' + (err.message || 'Sensor error.'), 'text-danger');
                }
            }
        }

        function showStaffBioAlert(msg, colorClass) {
            const alertEl = document.getElementById('staffBioAlert');
            if (alertEl) {
                alertEl.className = 'small mb-2 font-bold ' + colorClass;
                alertEl.innerHTML = msg;
                alertEl.classList.remove('d-none');
            }
        }

        function loadRegisteredBioDevices() {
            const container = document.getElementById('registeredBioDevicesList');
            const devicesDiv = document.getElementById('bioDevicesContainer');
            if (!container || !devicesDiv) return;

            fetch('/api/webauthn/credentials')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
                    container.classList.remove('d-none');
                    let html = '';
                    data.data.forEach(cred => {
                        html += `
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-slate-900 border border-secondary border-opacity-25 text-white" style="font-size: 0.78rem;">
                                <div>
                                    <i class="fa-solid fa-mobile-screen me-1 text-info"></i>
                                    <strong>${cred.device_name || 'Biometric Device'}</strong>
                                    <small class="d-block text-secondary" style="font-size: 0.7rem;">Added: ${new Date(cred.created_at).toLocaleDateString()}</small>
                                </div>
                                <button onclick="revokeBioDevice(${cred.id})" class="btn btn-sm btn-outline-danger py-0 px-2 text-danger" style="font-size: 0.72rem;">
                                    Revoke
                                </button>
                            </div>
                        `;
                    });
                    devicesDiv.innerHTML = html;
                } else {
                    localStorage.removeItem('carmel_biometric_cred_id');
                    localStorage.removeItem('carmel_registered_biometric_mobile');
                    container.classList.add('d-none');
                }
            })
            .catch(err => console.error(err));
        }

        function revokeBioDevice(id) {
            if (!confirm('Are you sure you want to revoke biometric login for this device?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/api/webauthn/credentials/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    localStorage.removeItem('carmel_biometric_cred_id');
                    localStorage.removeItem('carmel_registered_biometric_mobile');
                    showStaffBioAlert('<i class="fa-solid fa-circle-check me-1"></i> Biometric device revoked.', 'text-warning');
                    loadRegisteredBioDevices();
                } else {
                    showStaffBioAlert(data.message || 'Failed to revoke device.', 'text-danger');
                }
            })
            .catch(err => showStaffBioAlert('Failed to revoke device.', 'text-danger'));
        }

        function loadExecutiveFlashNotices() {
            const banner = document.getElementById('executiveFlashNoticeBanner');
            if (!banner) return;

            fetch('/api/flash-notices/active?t=' + new Date().getTime(), {
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.notices && data.notices.length > 0) {
                    const dismissedIds = JSON.parse(localStorage.getItem('carmel_dismissed_flash_ids') || '[]');
                    const activeNotices = data.notices.filter(n => !dismissedIds.includes(n.id));

                    if (activeNotices.length === 0) {
                        banner.classList.add('d-none');
                        banner.innerHTML = '';
                        return;
                    }

                    let html = '';
                    activeNotices.forEach(n => {
                        const isUrgent = n.priority === 'Urgent';
                        const isCircular = n.priority === 'Circular';
                        const badgeBg = isUrgent ? '#ef4444' : (isCircular ? '#8b5cf6' : '#3b82f6');
                        const cardBorder = isUrgent ? 'border-danger' : 'border-info';

                        let attachmentHtml = '';
                        if (n.attachment_path) {
                            const fileUrl = '/storage/' + n.attachment_path;
                            if (n.attachment_type === 'image') {
                                attachmentHtml = `
                                    <div class="mt-2 text-center">
                                        <a href="${fileUrl}" target="_blank">
                                            <img src="${fileUrl}" class="img-fluid rounded border border-secondary" style="max-height: 180px; object-fit: cover;" alt="Attachment">
                                        </a>
                                    </div>
                                `;
                            } else {
                                attachmentHtml = `
                                    <div class="mt-2">
                                        <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2.5 text-white fw-bold" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-file-pdf me-1 text-danger"></i> View Attachment Document
                                        </a>
                                    </div>
                                `;
                            }
                        }

                        html += `
                            <div class="app-card border-start border-4 ${cardBorder} p-3 mb-2 shadow-lg position-relative fade-in" style="background: rgba(15, 23, 42, 0.95);">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge px-2 py-1 font-bold text-white" style="background-color: ${badgeBg}; font-size: 0.68rem; letter-spacing: 0.5px;">
                                        <i class="fa-solid ${isUrgent ? 'fa-triangle-exclamation me-1' : 'fa-bullhorn me-1'}"></i>${n.priority.toUpperCase()} BROADCAST
                                    </span>
                                    <button onclick="dismissFlashNotice(${n.id})" class="btn-close btn-close-white" style="font-size: 0.65rem;" title="Dismiss"></button>
                                </div>
                                <h6 class="fw-bold text-white mb-1.5" style="font-size: 0.96rem;">${n.title}</h6>
                                <p class="text-slate-200 mb-2" style="font-size: 0.84rem; white-space: pre-line; line-height: 1.45;">${n.content}</p>
                                ${attachmentHtml}
                                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-secondary border-opacity-25" style="font-size: 0.72rem; color: #94a3b8;">
                                    <span><i class="fa-solid fa-user-shield me-1 text-info"></i>${n.sender_name} (${n.sender_role})</span>
                                    <span><i class="fa-solid fa-clock me-1"></i>${new Date(n.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                            </div>
                        `;
                    });

                    banner.innerHTML = html;
                    banner.classList.remove('d-none');
                } else {
                    banner.classList.add('d-none');
                    banner.innerHTML = '';
                }
            })
            .catch(err => console.error('Flash notice load error:', err));
        }

        function dismissFlashNotice(id) {
            const dismissedIds = JSON.parse(localStorage.getItem('carmel_dismissed_flash_ids') || '[]');
            if (!dismissedIds.includes(id)) {
                dismissedIds.push(id);
                localStorage.setItem('carmel_dismissed_flash_ids', JSON.stringify(dismissedIds));
            }
            loadExecutiveFlashNotices();
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadRegisteredBioDevices();
            loadExecutiveFlashNotices();
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js')
                    .catch(err => console.log('SW bypassed:', err));
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                loadExecutiveFlashNotices();
            }
        });

        // Prevent back-button viewing after logout
        window.addEventListener('pageshow', function (event) {
            loadExecutiveFlashNotices();
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload(true);
            }
        });
    </script>

    <!-- Hidden Input for Photo File Upload -->
    <input type="file" id="staffPhotoFileInput" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display: none;" onchange="handleStaffPhotoUpload(this)">
</body>
</html>
