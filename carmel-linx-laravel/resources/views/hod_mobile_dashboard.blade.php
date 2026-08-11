<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HOD Mobile Portal - Carmel Linx</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              brand: {
                50: '#f0f7ff',
                100: '#e0effe',
                500: '#3b82f6',
                600: '#2563eb',
                700: '#1d4ed8',
                900: '#1e3a8a',
              }
            }
          }
        }
      }
    </script>
    
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-card: rgba(17, 24, 39, 0.85);
            --bg-card-border: rgba(30, 41, 59, 0.8);
            --bg-header: rgba(11, 15, 25, 0.95);
            --bg-bottom-nav: rgba(15, 23, 42, 0.95);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-subtle: #64748b;
            --accent-glow: rgba(59, 130, 246, 0.15);
            --card-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.36);
            --input-bg: #0f172a;
            --input-border: #334155;
        }

        [data-theme="light"] {
            --bg-primary: #f1f5f9;
            --bg-card: rgba(255, 255, 255, 0.95);
            --bg-card-border: rgba(226, 232, 240, 0.9);
            --bg-header: rgba(255, 255, 255, 0.95);
            --bg-bottom-nav: rgba(255, 255, 255, 0.95);
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-subtle: #64748b;
            --accent-glow: rgba(37, 99, 235, 0.08);
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
            -webkit-tap-highlight-color: transparent;
            padding-bottom: 85px;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--bg-card-border);
            box-shadow: var(--card-shadow);
        }

        .nav-tab-link.active {
            color: #3b82f6;
            transform: translateY(-2px);
        }
        .nav-tab-link.active i {
            filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.6));
        }

        .tab-panel {
            display: none;
        }
        .tab-panel.active {
            display: block;
            animation: fadeIn 0.25s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen">

    <!-- FIXED TOP HEADER -->
    <header class="fixed top-0 left-0 right-0 z-50 px-4 py-2.5 border-b flex items-center justify-between shadow-lg" style="background: var(--bg-header); border-color: var(--bg-card-border);">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white font-black flex items-center justify-center shadow-md border border-blue-400/40 text-xs tracking-wider">
                CL
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-extrabold text-sm tracking-tight leading-none bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-300 bg-clip-text text-transparent">Carmel Linx</h1>
                    <span class="px-1.5 py-0.5 text-[9px] font-black rounded-md bg-blue-500/20 text-blue-400 border border-blue-500/40 uppercase tracking-wide">
                        HOD Portal
                    </span>
                </div>
                <p class="text-[10px] font-semibold mt-0.5" style="color: var(--text-muted);">Department Management Hub</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Light / Dark Theme Switch -->
            <button id="themeToggleBtn" onclick="toggleTheme()" class="w-8 h-8 rounded-xl flex items-center justify-center border transition-all shadow-sm" style="background: var(--bg-card); border-color: var(--bg-card-border);" title="Toggle Theme">
                <i id="themeIcon" class="fa-solid fa-moon text-amber-400 text-xs"></i>
            </button>

            <!-- Logout Button -->
            <a href="/logout" onclick="return confirm('Are you sure you want to logout?')" class="w-8 h-8 rounded-xl flex items-center justify-center border border-rose-500/30 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Logout Session">
                <i class="fa-solid fa-right-from-bracket text-xs"></i>
            </a>
        </div>
    </header>

    <!-- ATTRACTIVE HOD HERO TITLE CARD -->
    <div class="pt-16 px-4 mt-2">
        <div class="glass-card rounded-2xl p-4 border border-slate-700/60 shadow-xl relative overflow-hidden bg-gradient-to-r from-slate-900/90 via-slate-900/80 to-blue-950/40">
            <!-- Subtle background accent glow -->
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-600/15 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3.5">
                    <div class="relative">
                        @if(!empty($staff->photo_url))
                            <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-blue-500/80 shadow-md">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 flex items-center justify-center font-black text-white text-lg shadow-md border border-blue-400/30">
                                {{ strtoupper(substr($staff->name ?? 'H', 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full" title="Active HOD Session"></span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-extrabold text-base tracking-tight text-white leading-tight">{{ $staff->name ?? 'HOD Officer' }}</h2>
                        </div>
                        <p class="text-xs text-slate-300 font-medium mt-0.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-shield text-blue-400 text-[10px]"></i>
                            <span>Head of Department</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-mono px-2 py-1 rounded-xl bg-blue-950/80 text-blue-300 border border-blue-700/50 shadow-inner font-bold">
                        {{ $dept }}
                    </span>
                </div>
            </div>

            <!-- Workspace Switcher inside Title Card -->
            <div class="mt-3.5 pt-3 border-t border-slate-800/80 flex items-center justify-between relative z-10">
                <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-layer-group text-blue-400 text-xs"></i> Active Workspace:
                </span>
                <div class="flex items-center bg-slate-950/80 p-1 rounded-xl border border-slate-800">
                    <button id="modeBtnHod" onclick="setWorkingMode('hod')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-sm">
                        <i class="fa-solid fa-user-shield text-[10px] mr-1"></i> HOD Admin
                    </button>
                    <button id="modeBtnFaculty" onclick="setWorkingMode('faculty')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-400 hover:text-white">
                        <i class="fa-solid fa-chalkboard-user text-[10px] mr-1"></i> Faculty Mode
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINERS -->
    <main class="px-4 py-3">

        <!-- ========================================== -->
        <!-- TAB 1: OVERVIEW & DASHBOARD -->
        <!-- ========================================== -->
        <div id="tab-overview" class="tab-panel active space-y-4">

            <!-- PROMINENT NOTIFICATIONS & SEMINARS FEED (HOME TAB) -->
            <div class="glass-card rounded-2xl p-4 border border-slate-700/60 shadow-lg space-y-3">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-2.5">
                    <h3 class="font-extrabold text-xs uppercase tracking-wider text-amber-400 flex items-center gap-2">
                        <i class="fa-solid fa-bell text-amber-400 text-sm animate-pulse"></i>
                        <span>Institutional Circulars & Seminars</span>
                    </h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        {{ count($notices) + count($upcomingSeminars) }} Total
                    </span>
                </div>

                <div class="space-y-2.5">
                    <!-- Principal & High-Priority Notices -->
                    @if(count($notices) > 0)
                        @foreach($notices->take(3) as $notice)
                            <div class="p-3 rounded-xl border transition-all {{ $notice->department == 'Principal' || $notice->priority == 'Urgent' ? 'bg-rose-950/30 border-rose-500/40 text-rose-200' : 'bg-slate-900/60 border-slate-800/80 text-slate-200' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        @if($notice->department == 'Principal')
                                            <span class="px-2 py-0.5 text-[9px] font-black rounded bg-rose-500/20 text-rose-300 border border-rose-500/40 uppercase">
                                                <i class="fa-solid fa-crown text-[8px] mr-1"></i> Principal Directive
                                            </span>
                                        @elseif($notice->priority == 'Urgent')
                                            <span class="px-2 py-0.5 text-[9px] font-black rounded bg-amber-500/20 text-amber-300 border border-amber-500/40 uppercase">
                                                <i class="fa-solid fa-fire text-[8px] mr-1"></i> Urgent Circular
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-[9px] font-black rounded bg-blue-500/20 text-blue-300 border border-blue-500/40 uppercase">
                                                {{ $notice->department }} Notice
                                            </span>
                                        @endif
                                        <span class="text-[10px] text-slate-400 font-mono">{{ date('d M', strtotime($notice->created_at)) }}</span>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-white">{{ $notice->title }}</h4>
                                <p class="text-[11px] text-slate-300 mt-1 line-clamp-2">{{ $notice->content }}</p>
                            </div>
                        @endforeach
                    @endif

                    <!-- Student Seminars & Academic Presentations -->
                    @if(count($upcomingSeminars) > 0)
                        @foreach($upcomingSeminars->take(2) as $sem)
                            <div class="p-3 rounded-xl bg-purple-950/30 border border-purple-500/40 text-purple-200 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded bg-purple-500/20 text-purple-300 border border-purple-500/40 uppercase flex items-center gap-1">
                                        <i class="fa-solid fa-chalkboard-user text-[8px]"></i> Student Seminar
                                    </span>
                                    <span class="text-[10px] font-mono text-purple-300 font-bold">
                                        {{ !empty($sem->presentation_date) ? date('d M Y', strtotime($sem->presentation_date)) : 'Scheduled' }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-white mt-1">{{ $sem->topic }}</h4>
                                <p class="text-[11px] text-slate-300 flex items-center justify-between">
                                    <span>Presenter: <strong>{{ $sem->student_name }}</strong></span>
                                    <span class="font-mono text-[10px] text-purple-400">Reg: {{ $sem->reg_no }}</span>
                                </p>
                            </div>
                        @endforeach
                    @endif

                    @if(count($notices) == 0 && count($upcomingSeminars) == 0)
                        <div class="p-4 text-center text-slate-500 text-xs">
                            <i class="fa-regular fa-bell-slash text-xl mb-1"></i>
                            <p>No active Principal directives or seminar alerts.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Department Quick Stats Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div class="glass-card rounded-2xl p-3.5 border border-slate-700/60 flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold">Pending Staff Leaves</span>
                        <i class="fa-solid fa-user-clock text-amber-400"></i>
                    </div>
                    <div class="mt-2">
                        <span class="text-2xl font-black text-amber-400">{{ count($pendingStaffLeaves) }}</span>
                        <p class="text-[10px] mt-0.5 text-slate-400">Requires HOD Approval</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-3.5 border border-slate-700/60 flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold">Active Dept Batches</span>
                        <i class="fa-solid fa-graduation-cap text-emerald-400"></i>
                    </div>
                    <div class="mt-2">
                        <span class="text-2xl font-black text-emerald-400">{{ count($deptBatches) }}</span>
                        <p class="text-[10px] mt-0.5 text-slate-400">{{ $dept }} Classrooms</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-3.5 border border-slate-700/60 flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold">Pending Student Leaves</span>
                        <i class="fa-solid fa-hospital-user text-rose-400"></i>
                    </div>
                    <div class="mt-2">
                        <span class="text-2xl font-black text-rose-400">{{ count($pendingStudentLeaves) }}</span>
                        <p class="text-[10px] mt-0.5 text-slate-400">Student Requests</p>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-3.5 border border-slate-700/60 flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-400">
                        <span class="text-xs font-bold">Department Staff</span>
                        <i class="fa-solid fa-id-card-clip text-purple-400"></i>
                    </div>
                    <div class="mt-2">
                        <span class="text-2xl font-black text-purple-400">{{ count($deptStaff) }}</span>
                        <p class="text-[10px] mt-0.5 text-slate-400">Registered Faculty</p>
                    </div>
                </div>
            </div>

            <!-- UNIFIED DAY ORDER & DATE CARD WITH DAY SWITCH POPUP -->
            <div class="glass-card rounded-2xl p-4 border border-slate-700/60 border-l-4 border-l-blue-500 shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold">
                            <i class="fa-solid fa-calendar-day text-lg"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Institutional Day Order</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <h2 class="text-lg font-black text-white leading-none">
                                    <span id="displayDayOrder">{{ $defaultDayOrder }}</span>
                                </h2>
                                <button type="button" onclick="openDayOrderModal()" class="px-2 py-0.5 text-[10px] font-bold rounded-lg bg-blue-600/80 hover:bg-blue-500 text-white border border-blue-400/40 shadow-sm flex items-center gap-1 transition-all" title="Change Institutional Day Order">
                                    <i class="fa-solid fa-pen-to-square text-[9px]"></i> Switch Day
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Today</span>
                        <p class="text-xs font-mono font-bold text-blue-400 mt-0.5">{{ date('D, d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- 3-SEMESTER BRANCH TIMETABLE & LIVE CLASS MONITOR -->
            <div class="glass-card rounded-2xl p-4 border border-slate-700/60 shadow-lg space-y-3">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-2.5">
                    <h3 class="font-extrabold text-xs uppercase tracking-wider text-sky-400 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-sky-400 text-sm"></i>
                        <span>Branch Timetables & Class Status</span>
                    </h3>
                    <span class="text-[10px] font-mono font-bold text-slate-400">Sem 1, 3 & 5</span>
                </div>

                <!-- Semester Tabs (Sem 1, Sem 3, Sem 5) -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="switchSemesterTt(1)" id="semTtBtn1" class="flex-1 py-1.5 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow">
                        Sem 1
                    </button>
                    <button type="button" onclick="switchSemesterTt(3)" id="semTtBtn3" class="flex-1 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-900 border border-slate-800">
                        Sem 3
                    </button>
                    <button type="button" onclick="switchSemesterTt(5)" id="semTtBtn5" class="flex-1 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-900 border border-slate-800">
                        Sem 5
                    </button>
                </div>

                <!-- Semester Periods Content List -->
                @foreach([1, 3, 5] as $semNum)
                    <div id="semTtPane{{ $semNum }}" class="sem-tt-pane {{ $semNum == 1 ? '' : 'hidden' }} space-y-2">
                        @if(isset($semesterSchedules[$semNum]))
                            <div class="flex items-center justify-between text-[11px] text-slate-400 px-1">
                                <span>Subjects: <strong class="text-slate-200">{{ $semesterSchedules[$semNum]['subjects_count'] }}</strong></span>
                                <span>Conducted Today: <strong class="text-emerald-400">{{ $semesterSchedules[$semNum]['conducted_count'] }}/6</strong></span>
                            </div>
                            <div class="space-y-2">
                                @foreach($semesterSchedules[$semNum]['periods'] as $period)
                                    <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-lg bg-slate-800 text-slate-300 font-mono font-black text-[11px] flex items-center justify-center border border-slate-700">
                                                P{{ $period['period'] }}
                                            </span>
                                            <div>
                                                <h4 class="font-bold text-slate-200 text-[11px]">{{ $period['subject_code'] }} — {{ $period['subject_name'] }}</h4>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Faculty: <span class="text-slate-300">{{ $period['staff_name'] }}</span> • <span class="font-mono text-slate-400">{{ $period['time_slot'] }}</span></p>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 text-[9px] font-black rounded-full {{ $period['badge_class'] }}">
                                            {{ $period['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Action & To-Do Center -->
            <div class="glass-card rounded-2xl p-4 border border-slate-700/60">
                <div class="flex items-center justify-between border-b pb-3 mb-3" style="border-color: var(--bg-card-border);">
                    <h3 class="font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-blue-400"></i>
                        <span>HOD To-Do & Action Center</span>
                    </h3>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        {{ count($pendingStaffLeaves) + count($pendingStudentLeaves) }} Actions
                    </span>
                </div>

                <div class="space-y-2.5">
                    @if(count($pendingStaffLeaves) > 0)
                        <div onclick="switchStaffTab('tab-approvals')" class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-between cursor-pointer hover:bg-amber-500/20 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-user-clock text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-amber-300">{{ count($pendingStaffLeaves) }} Staff Leave Applications</h4>
                                    <p class="text-[10px] text-slate-400">Tap to review & approve staff requests</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-amber-400 text-xs"></i>
                        </div>
                    @endif

                    @if(count($pendingStudentLeaves) > 0)
                        <div onclick="switchStaffTab('tab-approvals')" class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-between cursor-pointer hover:bg-rose-500/20 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-hospital-user text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-rose-300">{{ count($pendingStudentLeaves) }} Student Leave Applications</h4>
                                    <p class="text-[10px] text-slate-400">Department student leave queue</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-rose-400 text-xs"></i>
                        </div>
                    @endif

                    <!-- Teaching Subjects Quick Access -->
                    @if(count($mySubjects) > 0)
                        <div onclick="setWorkingMode('faculty')" class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-between cursor-pointer hover:bg-blue-500/20 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-chalkboard-user text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-blue-300">{{ count($mySubjects) }} Assigned Teaching Subjects</h4>
                                    <p class="text-[10px] text-slate-400">Switch to Faculty Mode for attendance & course files</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-right-arrow-left text-blue-400 text-xs"></i>
                        </div>
                    @endif

                    @if(count($pendingStaffLeaves) == 0 && count($pendingStudentLeaves) == 0)
                        <div class="text-center py-6 text-slate-500">
                            <i class="fa-solid fa-circle-check text-2xl text-emerald-500/50 mb-2"></i>
                            <p class="text-xs font-medium">All department approval queues are up to date!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Navigation Shortcuts -->
            <div class="grid grid-cols-2 gap-3">
                <a href="/hod/report-centre" class="glass-card rounded-xl p-3 flex items-center gap-3 border hover:border-amber-500/50 transition-all no-underline">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-amber-300">Report Centre</h4>
                        <p class="text-[10px] text-slate-400">Workload & Logs</p>
                    </div>
                </a>

                <a href="/hod/academic-calendar" class="glass-card rounded-xl p-3 flex items-center gap-3 border hover:border-cyan-500/50 transition-all no-underline">
                    <div class="w-9 h-9 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-cyan-300">Academic Calendar</h4>
                        <p class="text-[10px] text-slate-400">Events & Holidays</p>
                    </div>
                </a>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 2: APPROVALS HUB (STAFF & STUDENT LEAVES) -->
        <!-- ========================================== -->
        <div id="tab-approvals" class="tab-panel space-y-4">
            
            <!-- Approvals Sub-Filter Tabs -->
            <div class="flex items-center gap-2 border-b pb-2" style="border-color: var(--bg-card-border);">
                <button id="subTabStaff" onclick="switchApprovalSubTab('staff')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow">
                    Staff Leaves ({{ count($pendingStaffLeaves) }})
                </button>
                <button id="subTabStudent" onclick="switchApprovalSubTab('student')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-800/60 border border-slate-700/50">
                    Student Leaves ({{ count($pendingStudentLeaves) }})
                </button>
            </div>

            <!-- SUB-TAB 1: STAFF LEAVES -->
            <div id="approvalsSubStaff" class="space-y-3">
                @if(count($pendingStaffLeaves) > 0)
                    @foreach($pendingStaffLeaves as $leave)
                        <div class="glass-card rounded-2xl p-4 border-l-4 border-l-amber-500 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-sm text-amber-300">{{ $leave->staff_name }}</h3>
                                        <span class="px-2 py-0.5 text-[10px] font-black rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            {{ $leave->leave_type }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $leave->designation }} • Code: <span class="font-mono text-slate-300">{{ $leave->leave_code }}</span></p>
                                </div>
                                <span class="text-[10px] font-mono px-2 py-1 rounded bg-slate-800 text-slate-400 border border-slate-700">
                                    {{ $leave->total_days }} {{ $leave->total_days == 1 ? 'Day' : 'Days' }}
                                </span>
                            </div>

                            <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80 space-y-1.5 text-xs">
                                <div class="flex items-center justify-between text-slate-300">
                                    <span><i class="fa-regular fa-calendar text-blue-400 mr-1.5"></i> Dates:</span>
                                    <strong class="font-mono">{{ date('d M Y', strtotime($leave->from_date)) }} — {{ date('d M Y', strtotime($leave->to_date)) }}</strong>
                                </div>
                                <div class="text-slate-400">
                                    <strong class="text-slate-300">Reason:</strong> {{ $leave->reason }}
                                </div>

                                @if(!empty($leave->work_arrangement) && is_array($leave->work_arrangement))
                                    <div class="pt-1 border-t border-slate-800/80">
                                        <strong class="text-[11px] text-indigo-400 block mb-1">Substitute Work Arrangements:</strong>
                                        <div class="space-y-1">
                                            @foreach($leave->work_arrangement as $arr)
                                                <div class="text-[11px] bg-slate-800/60 p-1.5 rounded flex items-center justify-between text-slate-300">
                                                    <span>{{ $arr['date'] ?? '' }} (P{{ $arr['period'] ?? '' }})</span>
                                                    <span class="font-semibold text-sky-400">{{ $arr['substitute_name'] ?? 'Assigned Staff' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Approval Action Buttons -->
                            <div class="flex items-center gap-2 pt-1">
                                <button onclick="openStaffLeaveActionModal({{ $leave->id }}, '{{ $leave->staff_name }}', 'Approved')" class="flex-1 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow transition-all">
                                    <i class="fa-solid fa-check text-xs"></i> Approve Leave
                                </button>
                                <button onclick="openStaffLeaveActionModal({{ $leave->id }}, '{{ $leave->staff_name }}', 'Rejected')" class="flex-1 py-2 rounded-xl bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                                    <i class="fa-solid fa-xmark text-xs"></i> Reject
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="glass-card rounded-2xl p-8 text-center text-slate-500">
                        <i class="fa-solid fa-clipboard-check text-3xl text-emerald-500/40 mb-2"></i>
                        <h4 class="text-sm font-bold text-slate-300">No Pending Staff Leaves</h4>
                        <p class="text-xs text-slate-400 mt-1">All staff leave applications for {{ $dept }} have been processed.</p>
                    </div>
                @endif
            </div>

            <!-- SUB-TAB 2: STUDENT LEAVES -->
            <div id="approvalsSubStudent" class="space-y-3 hidden">
                @if(count($pendingStudentLeaves) > 0)
                    @foreach($pendingStudentLeaves as $sLeave)
                        <div class="glass-card rounded-2xl p-4 border-l-4 border-l-rose-500 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-rose-300">{{ $sLeave->student_name }}</h3>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Reg No: <span class="font-mono text-slate-300">{{ $sLeave->reg_no }}</span></p>
                                </div>
                                <span class="text-[10px] font-mono px-2 py-1 rounded bg-slate-800 text-slate-400 border border-slate-700">
                                    {{ $sLeave->no_of_days }} Day(s)
                                </span>
                            </div>

                            <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800/80 text-xs space-y-1">
                                <div class="flex items-center justify-between text-slate-300">
                                    <span>Date:</span>
                                    <strong class="font-mono">{{ $sLeave->leave_date }}</strong>
                                </div>
                                <div class="text-slate-400">
                                    <strong>Reason:</strong> {{ $sLeave->reason }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <button onclick="processStudentLeave({{ $sLeave->id }}, 'Approved')" class="flex-1 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow transition-all">
                                    <i class="fa-solid fa-check text-xs"></i> Approve
                                </button>
                                <button onclick="processStudentLeave({{ $sLeave->id }}, 'Rejected')" class="flex-1 py-2 rounded-xl bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                                    <i class="fa-solid fa-xmark text-xs"></i> Reject
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="glass-card rounded-2xl p-8 text-center text-slate-500">
                        <i class="fa-solid fa-user-check text-3xl text-emerald-500/40 mb-2"></i>
                        <h4 class="text-sm font-bold text-slate-300">No Pending Student Leaves</h4>
                        <p class="text-xs text-slate-400 mt-1">Student leave application queue is empty.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 3: MY BATCHES (FACULTY MODE) -->
        <!-- ========================================== -->
        <div id="tab-mybatches" class="tab-panel space-y-4">
            
            <div class="glass-card rounded-2xl p-4 border-l-4 border-l-sky-500">
                <h3 class="font-bold text-sm text-sky-300 flex items-center gap-2">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>HOD Teaching Assignments</span>
                </h3>
                <p class="text-xs text-slate-400 mt-1">Subjects assigned directly to you as a faculty member.</p>
            </div>

            @if(count($mySubjects) > 0)
                <div class="space-y-3">
                    @foreach($mySubjects as $sub)
                        <div class="glass-card rounded-2xl p-4 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                        Semester {{ $sub->semester }}
                                    </span>
                                    <h4 class="font-bold text-sm text-slate-200 mt-1">{{ $sub->subject_name }}</h4>
                                    <p class="text-xs font-mono text-slate-400">{{ $sub->subject_code }} • Type: {{ $sub->subject_type ?? 'Theory' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-800">
                                <a href="/staff/attendance-log" class="py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs text-center no-underline shadow">
                                    <i class="fa-solid fa-clipboard-user mr-1"></i> Attendance
                                </a>
                                <a href="/course-files" class="py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs text-center border border-slate-700 no-underline">
                                    <i class="fa-solid fa-folder-open mr-1"></i> Course File
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glass-card rounded-2xl p-8 text-center text-slate-500">
                    <i class="fa-solid fa-book-open text-3xl text-sky-500/40 mb-2"></i>
                    <h4 class="text-sm font-bold text-slate-300">No Assigned Teaching Subjects</h4>
                    <p class="text-xs text-slate-400 mt-1">You are currently operating in full administrative HOD mode.</p>
                </div>
            @endif

        </div>

        <!-- ========================================== -->
        <!-- TAB 4: DEPT BATCHES & STAFF ROSTER -->
        <!-- ========================================== -->
        <div id="tab-deptbatches" class="tab-panel space-y-4">
            
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-slate-200">Department Classrooms ({{ count($deptBatches) }})</h3>
                <span class="text-xs text-blue-400 font-bold">{{ $dept }}</span>
            </div>

            <div class="space-y-3">
                @foreach($deptBatches as $b)
                    <div class="glass-card rounded-2xl p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-sm text-blue-400">{{ $b->classroom_id }}</h4>
                                @if(!empty($b->is_r26) || ($b->batch_year ?? 0) == 2026)
                                    <span class="px-1.5 py-0.5 text-[9px] font-black rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase">R26</span>
                                @endif
                            </div>
                            <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
                                Sem {{ $b->current_semester ?? 1 }}
                            </span>
                        </div>

                        <div class="text-xs space-y-1 text-slate-300">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Tutor (Mentor 1):</span>
                                <strong class="text-sky-300">{{ $b->tutor_name ?? 'Unassigned' }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Mentor 2:</span>
                                <strong class="text-emerald-300">{{ $b->mentor_name ?? 'Unassigned' }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Enrolled Students:</span>
                                <strong class="font-mono text-amber-400">{{ $b->student_count }} Students</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 5: NOTICES & ANNOUNCEMENT GENERATOR -->
        <!-- ========================================== -->
        <div id="tab-notices" class="tab-panel space-y-4">
            
            <!-- Notice Compose Form Card -->
            <div class="glass-card rounded-2xl p-4 space-y-3">
                <h3 class="font-bold text-sm text-purple-300 flex items-center gap-2 border-b pb-2" style="border-color: var(--bg-card-border);">
                    <i class="fa-solid fa-bullhorn text-purple-400"></i>
                    <span>Broadcast Department Announcement</span>
                </h3>

                <form id="noticeForm" onsubmit="submitDepartmentNotice(event)" class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Notice Title</label>
                        <input type="text" id="noticeTitle" required placeholder="e.g. Department Staff Meeting Today" class="w-full px-3 py-2 rounded-xl text-xs border focus:ring-2 focus:ring-purple-500 outline-none" style="background: var(--input-bg); border-color: var(--input-border); color: var(--text-main);">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 mb-1">Target Audience</label>
                            <select id="noticeAudience" class="w-full px-2.5 py-2 rounded-xl text-xs border outline-none" style="background: var(--input-bg); border-color: var(--input-border); color: var(--text-main);">
                                <option value="All Staff">All Dept Staff</option>
                                <option value="Tutors Only">Tutors & Mentors</option>
                                <option value="Students">Department Students</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 mb-1">Priority</label>
                            <select id="noticePriority" class="w-full px-2.5 py-2 rounded-xl text-xs border outline-none" style="background: var(--input-bg); border-color: var(--input-border); color: var(--text-main);">
                                <option value="Normal">Normal</option>
                                <option value="Urgent">Urgent 🔥</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Notice Message</label>
                        <textarea id="noticeContent" rows="3" required placeholder="Write message details..." class="w-full px-3 py-2 rounded-xl text-xs border focus:ring-2 focus:ring-purple-500 outline-none" style="background: var(--input-bg); border-color: var(--input-border); color: var(--text-main);"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow transition-all">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Publish Announcement
                    </button>
                </form>
            </div>

            <!-- Published Notices Feed -->
            <div class="space-y-3">
                <h4 class="font-bold text-xs text-slate-400">Published Department Notices</h4>
                @if(count($notices) > 0)
                    @foreach($notices as $n)
                        <div class="glass-card rounded-2xl p-4 space-y-2 border-l-4 {{ $n->priority == 'Urgent' ? 'border-l-rose-500' : 'border-l-purple-500' }}">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-sm text-slate-200">{{ $n->title }}</h4>
                                        <span class="px-1.5 py-0.5 text-[9px] font-black rounded {{ $n->priority == 'Urgent' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30' }}">
                                            {{ $n->priority }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Audience: <strong class="text-slate-300">{{ $n->target_audience }}</strong> • {{ date('d M Y, h:i A', strtotime($n->created_at)) }}</p>
                                </div>
                                <button onclick="deleteNotice({{ $n->id }})" class="text-slate-500 hover:text-rose-400 text-xs p-1" title="Delete Notice">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <p class="text-xs text-slate-300 pt-1 border-t border-slate-800/60">{{ $n->content }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-slate-500">
                        <i class="fa-regular fa-comment-dots text-2xl mb-1"></i>
                        <p class="text-xs">No notices published yet.</p>
                    </div>
                @endif
            </div>

        </div>

    </main>

    <!-- FIXED BOTTOM NAVIGATION BAR -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 px-2 py-2 border-t flex items-center justify-around shadow-2xl" style="background: var(--bg-bottom-nav); border-color: var(--bg-card-border);">
        <button onclick="switchStaffTab('tab-overview')" class="nav-tab-link active flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-400" id="navBtn-overview">
            <i class="fa-solid fa-house-chimney text-base"></i>
            <span>Home</span>
        </button>

        <button onclick="switchStaffTab('tab-approvals')" class="nav-tab-link relative flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-400" id="navBtn-approvals">
            <i class="fa-solid fa-user-check text-base"></i>
            <span>Approvals</span>
            @if(count($pendingStaffLeaves) + count($pendingStudentLeaves) > 0)
                <span class="absolute -top-1 right-1 w-4 h-4 rounded-full bg-amber-500 text-slate-900 font-extrabold text-[9px] flex items-center justify-center">
                    {{ count($pendingStaffLeaves) + count($pendingStudentLeaves) }}
                </span>
            @endif
        </button>

        <button onclick="switchStaffTab('tab-mybatches')" class="nav-tab-link flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-400" id="navBtn-mybatches">
            <i class="fa-solid fa-chalkboard-user text-base"></i>
            <span>My Batches</span>
        </button>

        <button onclick="switchStaffTab('tab-deptbatches')" class="nav-tab-link flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-400" id="navBtn-deptbatches">
            <i class="fa-solid fa-graduation-cap text-base"></i>
            <span>Batches</span>
        </button>

        <button onclick="switchStaffTab('tab-notices')" class="nav-tab-link flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-400" id="navBtn-notices">
            <i class="fa-solid fa-bullhorn text-base"></i>
            <span>Notices</span>
        </button>
    </nav>

    <!-- STAFF LEAVE APPROVAL ACTION MODAL -->
    <div id="staffLeaveModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="glass-card rounded-2xl p-5 w-full max-w-sm space-y-4">
            <div class="flex items-center justify-between border-b pb-2" style="border-color: var(--bg-card-border);">
                <h3 id="leaveModalTitle" class="font-bold text-sm text-slate-200">Process Staff Leave</h3>
                <button onclick="closeStaffLeaveModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <input type="hidden" id="modalLeaveId">
            <input type="hidden" id="modalLeaveAction">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Remarks (Optional)</label>
                <textarea id="modalLeaveRemarks" rows="3" placeholder="Enter optional approval/rejection remarks..." class="w-full px-3 py-2 rounded-xl text-xs border outline-none" style="background: var(--input-bg); border-color: var(--input-border); color: var(--text-main);"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="submitStaffLeaveDecision()" id="modalConfirmBtn" class="flex-1 py-2.5 rounded-xl font-bold text-xs text-white shadow">
                    Confirm Action
                </button>
                <button onclick="closeStaffLeaveModal()" class="py-2.5 px-4 rounded-xl bg-slate-800 text-slate-400 font-bold text-xs border border-slate-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT CONTROLLERS -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Theme Switcher Logic
        function initTheme() {
            const savedTheme = localStorage.getItem('hod_portal_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('hod_portal_theme', next);
            updateThemeIcon(next);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (theme === 'light') {
                icon.className = 'fa-solid fa-sun text-amber-500 text-xs';
            } else {
                icon.className = 'fa-solid fa-moon text-amber-400 text-xs';
            }
        }

        // Tab Navigation
        function switchStaffTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-tab-link').forEach(l => l.classList.remove('active'));
            
            const target = document.getElementById(tabId);
            if (target) target.classList.add('active');

            const btnKey = tabId.replace('tab-', '');
            const btn = document.getElementById('navBtn-' + btnKey);
            if (btn) btn.classList.add('active');
        }

        // Approval Sub-Tab Filter
        function switchApprovalSubTab(type) {
            const staffSub = document.getElementById('approvalsSubStaff');
            const studentSub = document.getElementById('approvalsSubStudent');
            const btnStaff = document.getElementById('subTabStaff');
            const btnStudent = document.getElementById('subTabStudent');

            if (type === 'staff') {
                staffSub.classList.remove('hidden');
                studentSub.classList.add('hidden');
                btnStaff.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow';
                btnStudent.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-800/60 border border-slate-700/50';
            } else {
                staffSub.classList.add('hidden');
                studentSub.classList.remove('hidden');
                btnStudent.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow';
                btnStaff.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-800/60 border border-slate-700/50';
            }
        }

        // Working Mode Switcher (HOD Admin vs Faculty Mode)
        function setWorkingMode(mode) {
            const btnHod = document.getElementById('modeBtnHod');
            const btnFaculty = document.getElementById('modeBtnFaculty');

            if (mode === 'hod') {
                btnHod.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow';
                btnFaculty.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-400 hover:text-white';
                switchStaffTab('tab-overview');
            } else {
                btnFaculty.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow';
                btnHod.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-400 hover:text-white';
                switchStaffTab('tab-mybatches');
            }
        }

        // Staff Leave Approval Action Modal
        function openStaffLeaveActionModal(leaveId, staffName, action) {
            document.getElementById('modalLeaveId').value = leaveId;
            document.getElementById('modalLeaveAction').value = action;
            document.getElementById('modalLeaveRemarks').value = '';

            const title = document.getElementById('leaveModalTitle');
            const confirmBtn = document.getElementById('modalConfirmBtn');

            if (action === 'Approved') {
                title.innerText = 'Approve Leave for ' + staffName;
                confirmBtn.className = 'flex-1 py-2.5 rounded-xl font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-500 shadow';
                confirmBtn.innerText = 'Confirm Approval';
            } else {
                title.innerText = 'Reject Leave for ' + staffName;
                confirmBtn.className = 'flex-1 py-2.5 rounded-xl font-bold text-xs text-white bg-rose-600 hover:bg-rose-500 shadow';
                confirmBtn.innerText = 'Confirm Rejection';
            }

            document.getElementById('staffLeaveModal').classList.remove('hidden');
        }

        function closeStaffLeaveModal() {
            document.getElementById('staffLeaveModal').classList.add('hidden');
        }

        async function submitStaffLeaveDecision() {
            const leaveId = document.getElementById('modalLeaveId').value;
            const action = document.getElementById('modalLeaveAction').value;
            const remarks = document.getElementById('modalLeaveRemarks').value;

            try {
                const res = await fetch('/api/staff/leave/process-approval', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        leave_id: leaveId,
                        stage: 'HOD',
                        action: action,
                        remarks: remarks
                    })
                });

                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    closeStaffLeaveModal();
                    alert('Staff leave application ' + action.toLowerCase() + ' successfully.');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Action failed.'));
                }
            } catch (err) {
                alert('Connection error: ' + err.message);
            }
        }

        // Process Student Leave
        async function processStudentLeave(id, status) {
            if (!confirm('Are you sure you want to set student leave status to ' + status + '?')) return;

            try {
                const res = await fetch('/api/mentoring/leave/approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id: id,
                        status: status
                    })
                });

                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert('Student leave ' + status.toLowerCase() + ' successfully.');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Connection error: ' + err.message);
            }
        }

        // Department Notice Publishing
        async function submitDepartmentNotice(e) {
            e.preventDefault();
            const title = document.getElementById('noticeTitle').value;
            const content = document.getElementById('noticeContent').value;
            const audience = document.getElementById('noticeAudience').value;
            const priority = document.getElementById('noticePriority').value;

            try {
                const res = await fetch('/api/hod/notice/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: title,
                        content: content,
                        target_audience: audience,
                        priority: priority
                    })
                });

                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert('Department announcement published successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Connection error: ' + err.message);
            }
        }

        async function deleteNotice(noticeId) {
            if (!confirm('Are you sure you want to delete this notice?')) return;

            try {
                const res = await fetch('/api/hod/notice/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ notice_id: noticeId })
                });

                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    alert('Notice deleted.');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (err) {
                alert('Connection error: ' + err.message);
            }
        }

        // Switch Semester Timetables Tab
        function switchSemesterTt(semNum) {
            document.querySelectorAll('.sem-tt-pane').forEach(p => p.classList.add('hidden'));
            [1, 3, 5].forEach(s => {
                const btn = document.getElementById('semTtBtn' + s);
                if (btn) btn.className = 'flex-1 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white bg-slate-900 border border-slate-800';
            });

            const activePane = document.getElementById('semTtPane' + semNum);
            if (activePane) activePane.classList.remove('hidden');

            const activeBtn = document.getElementById('semTtBtn' + semNum);
            if (activeBtn) activeBtn.className = 'flex-1 py-1.5 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow';
        }

        // Institutional Day Order Popup Switcher
        function openDayOrderModal() {
            document.getElementById('dayOrderModal').classList.remove('hidden');
        }

        function closeDayOrderModal() {
            document.getElementById('dayOrderModal').classList.add('hidden');
        }

        async function setDayOrder(dayVal) {
            const confirmed = confirm(`Are you sure you want to set the institution-wide Day Order for today to "${dayVal}"?\n\nThis will update class timetables, registers, and schedules across the platform.`);
            if (!confirmed) return;

            try {
                const res = await fetch('/api/system/set-day-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ day_order: dayVal })
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    document.getElementById('displayDayOrder').innerText = dayVal;
                    closeDayOrderModal();
                    alert(`Institution-wide Day Order set to "${dayVal}".`);
                    window.location.reload();
                } else {
                    alert('Failed to update day order: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                alert('Error connecting to server: ' + err.message);
            }
        }

        // Prevent back-button viewing after logout (force re-validation with server)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload(true);
            }
        });

        // Initialize Theme on load
        initTheme();
    </script>

    <!-- DAY ORDER SELECTION POPUP MODAL -->
    <div id="dayOrderModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4 hidden">
        <div class="glass-card rounded-2xl max-w-sm w-full p-5 border border-slate-700/70 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-extrabold text-sm text-white flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-blue-400"></i>
                    <span>Set Institutional Day Order</span>
                </h3>
                <button type="button" onclick="closeDayOrderModal()" class="w-7 h-7 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <p class="text-xs text-slate-300">Select today's active day order. This will immediately update class timetables, attendance registers, and room schedules across the platform:</p>

            <div class="grid grid-cols-1 gap-2">
                @foreach(['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'] as $dayOpt)
                    <button type="button" onclick="setDayOrder('{{ $dayOpt }}')" class="p-3 rounded-xl border text-left font-bold text-xs flex items-center justify-between transition-all {{ $defaultDayOrder == $dayOpt ? 'bg-blue-600/30 border-blue-500 text-white shadow' : 'bg-slate-900/60 border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-dot {{ $defaultDayOrder == $dayOpt ? 'text-blue-400' : 'text-slate-600' }} text-[10px]"></i>
                            <span>{{ $dayOpt }}</span>
                        </span>
                        @if($defaultDayOrder == $dayOpt)
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Active</span>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" onclick="closeDayOrderModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</body>
</html>
