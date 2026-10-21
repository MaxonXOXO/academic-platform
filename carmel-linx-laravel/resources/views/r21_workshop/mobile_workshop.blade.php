<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }} - Workshop Mobile</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: #090e17;
            color: #f8fafc;
            min-height: 100vh;
            padding-bottom: 90px;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Range Slider Styles */
        .mark-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 6px;
            background: #1e293b;
            border-radius: 9999px;
            outline: none;
            cursor: pointer;
            transition: background 0.15s ease;
            touch-action: pan-x;
            -webkit-user-select: none;
            user-select: none;
        }
        .mark-slider:focus {
            outline: none;
        }
        .mark-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #f97316;
            border: 2px solid #ffffff;
            box-shadow: 0 0 6px rgba(249, 115, 22, 0.6);
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        .mark-slider::-webkit-slider-thumb:active {
            transform: scale(1.2);
            background: #ea580c;
        }
        .mark-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #f97316;
            border: 2px solid #ffffff;
            box-shadow: 0 0 6px rgba(249, 115, 22, 0.6);
            cursor: pointer;
        }
        .mark-input-box {
            width: 52px;
            height: 32px;
            text-align: center;
            background: #0b1329;
            color: #ffffff;
            border: 1.5px solid #334155;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.92rem;
            font-family: monospace;
            outline: none;
            transition: border-color 0.15s;
        }
        .mark-input-box:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.25);
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] { -moz-appearance: textfield; }

        /* Roll Call Compact Row & Grid Styles */
        .att-row-item {
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .att-status-toggle {
            transition: all 0.15s ease;
            user-select: none;
        }
        .att-status-toggle:active {
            transform: scale(0.95);
        }

        /* Native Mobile App Bottom Navigation Dock */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(11, 19, 43, 0.96);
            backdrop-filter: blur(16px);
            border-top: 1px solid rgba(51, 65, 85, 0.7);
            padding: 6px 8px calc(env(safe-area-inset-bottom, 0px) + 6px);
            z-index: 45;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.55);
        }
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5px 2px;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            cursor: pointer;
            border: 1px solid transparent;
            text-align: center;
            background: transparent;
        }
        .bottom-nav-item:active {
            transform: scale(0.92);
        }
        .bottom-nav-item.active-works {
            background: rgba(245, 158, 11, 0.18);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.45);
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.2);
            font-weight: 800;
        }
        .bottom-nav-item.active-records {
            background: rgba(16, 185, 129, 0.18);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.45);
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.2);
            font-weight: 800;
        }
        .bottom-nav-item.active-tests {
            background: rgba(168, 85, 247, 0.18);
            color: #c084fc;
            border-color: rgba(168, 85, 247, 0.45);
            box-shadow: 0 2px 10px rgba(168, 85, 247, 0.2);
            font-weight: 800;
        }
        .bottom-nav-item.active-attendance {
            background: rgba(6, 182, 212, 0.18);
            color: #22d3ee;
            border-color: rgba(6, 182, 212, 0.45);
            box-shadow: 0 2px 10px rgba(6, 182, 212, 0.2);
            font-weight: 800;
        }
        .bottom-nav-item.active-status {
            background: rgba(245, 158, 11, 0.18);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.45);
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.2);
            font-weight: 800;
        }
        .chip-pill {
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .chip-pill:active {
            background: #f97316;
            color: #ffffff;
            border-color: #ea580c;
        }
    </style>
</head>
<body>

    <!-- Steady Pinned Top Header (Top Bar + 6 Trade Section Pills + Steady Active Section Title) -->
    <header class="sticky top-0 z-40 bg-slate-900 border-b border-slate-800 shadow-lg">
        <!-- Row 1: Clean Course Info & Top-Right Actions -->
        <div class="px-3.5 py-2.5 border-b border-slate-700 shadow-sm" style="border-bottom: 1.5px solid #334155;">
            <div class="flex items-center justify-between gap-2.5">
                <!-- Left: Clean Subject Code, Title & Full Staff Name -->
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 min-w-0">
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-orange-500/15 text-orange-400 border border-orange-500/30 shrink-0">
                            {{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }}
                        </span>
                        <h1 class="text-xs font-black text-white truncate">
                            {{ $batchSubject->subject_name }}
                        </h1>
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium flex items-center gap-1.5 truncate mt-0.5">
                        <span>{{ $classroom->classroom_id ?? $batchSubject->classroom_id }} &bull; Sem {{ $classroom->current_semester ?? 3 }}</span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="text-cyan-400 font-bold truncate">
                            <i class="fa-solid fa-user-tie text-[9px] mr-0.5"></i>{{ session('userName') ?? 'Staff' }}
                        </span>
                    </div>
                </div>

                <!-- Right: Desktop Switch Icon, and Colored Back Button at Top Right -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="/r21/classroom/workshop/{{ $batchSubject->id }}" 
                       title="Open Desktop View" 
                       class="w-7 h-7 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 hover:text-white transition-all shadow-sm">
                        <i class="fa-solid fa-desktop text-xs"></i>
                    </a>
                    <a href="/staff/mobile" 
                       title="Return to Staff Dashboard" 
                       class="px-2.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-[11px] flex items-center gap-1 shadow-md shadow-rose-600/30 border border-rose-500 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Row 2: 6 Trade Section Pills (Horizontal Swipeable Bar) -->
        <div class="bg-slate-950 px-3 py-1.5 border-b border-slate-800/80">
            <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar" id="sectionPillsContainer">
                @foreach($sections as $idx => $sec)
                    @php
                        $count = count($sectionStudents[$sec] ?? []);
                    @endphp
                    <button type="button" 
                            class="section-pill px-3 py-1 rounded-xl text-xs font-bold shrink-0 transition-all flex items-center gap-1.5 border {{ $idx === 0 ? 'bg-orange-500 text-white border-orange-400 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white' }}"
                            data-sec="{{ $sec }}"
                            onclick="switchMobileSection('{{ $sec }}')">
                        <i class="fa-solid fa-screwdriver-wrench text-[10px]"></i>
                        <span>{{ $sec }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $idx === 0 ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                            {{ $count }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Row 3: Steady Selected Section Title & Component Indicator (Steady during mark entry navigation) -->
        <div class="px-3 py-1.5 flex items-center justify-between gap-2 bg-slate-900/98 backdrop-blur-md">
            <div class="min-w-0">
                <div class="text-xs font-bold text-white flex items-center gap-1.5 truncate">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse shrink-0"></span>
                    <span id="activeSectionLabel" class="uppercase text-orange-400 font-black truncate">{{ $sections[0] }}</span>
                    <span class="text-slate-600">&bull;</span>
                    <span id="activeModeLabel" class="text-amber-400 font-extrabold text-[11px] shrink-0">Works (20M)</span>
                    <span class="text-slate-600">&bull;</span>
                    <span id="activeSectionCount" class="text-slate-400 font-mono text-[11px] shrink-0">
                        {{ count($sectionStudents[$sections[0]] ?? []) }} Students
                    </span>
                </div>
            </div>

            <!-- Quick Presets -->
            <div class="flex items-center gap-1 overflow-x-auto hide-scrollbar shrink-0" id="quickPresetsContainer">
                <!-- Dynamically updated by mode -->
            </div>
        </div>
    </header>

    <!-- Student Cards Container -->
    <main class="px-3 pt-2 pb-24 space-y-2.5" id="studentCardsContainer">
        <!-- Rendered dynamically by JavaScript -->
    </main>

    <!-- Empty State (shown if 0 students assigned) -->
    <div id="emptySectionNotice" class="hidden px-4 py-12 text-center text-slate-500">
        <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-600"></i>
        <div class="text-sm font-bold text-slate-400">No Students Assigned to this Trade</div>
        <div class="text-xs text-slate-500 mt-1">This trade is not in the active semester batch allocation for current students.</div>
    </div>

    <!-- Mobile App Bottom Navigation Dock (Works, Record, Test, Roll Call) -->
    <nav class="mobile-bottom-nav" id="mobileBottomNav">
        <!-- Tab 1: Works (20M) -->
        <button type="button" 
                onclick="switchMobileMode('works')" 
                id="modeBtn_works" 
                class="bottom-nav-item active-works">
            <i class="fa-solid fa-hammer text-base mb-0.5"></i>
            <span class="text-[10px] font-black leading-tight">Works</span>
            <span class="text-[9px] font-mono opacity-80 leading-none">20M</span>
        </button>

        <!-- Tab 2: Record (20M) -->
        <button type="button" 
                onclick="switchMobileMode('records')" 
                id="modeBtn_records" 
                class="bottom-nav-item text-slate-400 hover:text-white">
            <i class="fa-solid fa-book-open text-base mb-0.5"></i>
            <span class="text-[10px] font-bold leading-tight">Record</span>
            <span class="text-[9px] font-mono opacity-80 leading-none">20M</span>
        </button>

        <!-- Tab 3: Test (15M) -->
        <button type="button" 
                onclick="switchMobileMode('tests')" 
                id="modeBtn_tests" 
                class="bottom-nav-item text-slate-400 hover:text-white">
            <i class="fa-solid fa-quiz text-base mb-0.5"></i>
            <span class="text-[10px] font-bold leading-tight">Test</span>
            <span class="text-[9px] font-mono opacity-80 leading-none">15M</span>
        </button>

        <!-- Tab 4: Roll Call -->
        <button type="button" 
                onclick="switchMobileMode('attendance')" 
                id="modeBtn_attendance" 
                class="bottom-nav-item text-slate-400 hover:text-white">
            <i class="fa-solid fa-clipboard-user text-base mb-0.5"></i>
            <span class="text-[10px] font-bold leading-tight">Roll Call</span>
            <span class="text-[9px] font-mono opacity-80 leading-none">Att</span>
        </button>

        <!-- Tab 5: Status (3-Section Completion & CIA 75M) -->
        <button type="button" 
                onclick="openMobileReportModal()" 
                id="modeBtn_status" 
                title="View 3-Section Completion & CIA Status" 
                class="bottom-nav-item text-slate-400 hover:text-amber-400 active:scale-95 transition-all">
            <i class="fa-solid fa-chart-pie text-base mb-0.5 text-amber-400"></i>
            <span class="text-[10px] font-bold leading-tight text-amber-300">Status</span>
            <span class="text-[9px] font-mono opacity-80 leading-none text-slate-400">75M</span>
        </button>
    </nav>

    <!-- Mobile Quick Status Report Modal (Full Screen Edge-to-Edge) -->
    <div id="mobileReportModal" class="fixed inset-0 z-50 bg-slate-950 hidden flex-col w-full h-full overflow-hidden">
        <!-- Modal Top Header Bar -->
        <div class="px-3.5 py-2.5 bg-slate-900 border-b border-slate-800 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 min-w-0">
                <span class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-sm font-bold shrink-0 shadow-sm">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <div class="min-w-0">
                    <h3 class="text-xs font-black text-white truncate">Workshop 3-Section Status Report</h3>
                    <p class="text-[10px] text-slate-400 truncate">3 Required Sections Completion &bull; 75M CIA</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeMobileReportModal()" 
                    class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-extrabold text-xs flex items-center gap-1.5 border border-slate-700 active:scale-95 transition-all">
                <span>Close</span>
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- 4 Sub-Header Stat Metrics -->
        <div class="p-2.5 bg-slate-950 border-b border-slate-800/80 grid grid-cols-4 gap-1.5 text-center shrink-0">
            <div class="p-1.5 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[8px] uppercase font-bold text-slate-400 block truncate">3/3 Done</span>
                <span id="reportStatCompletedCount" class="text-xs font-black text-emerald-400 font-mono">0 / 0</span>
            </div>
            <div class="p-1.5 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[8px] uppercase font-bold text-slate-400 block truncate">Class Avg</span>
                <span id="reportStatAvgCia" class="text-xs font-black text-amber-400 font-mono">0.0 / 75</span>
            </div>
            <div class="p-1.5 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[8px] uppercase font-bold text-slate-400 block truncate">&ge; 30M Pass</span>
                <span id="reportStatPassCount" class="text-xs font-black text-teal-400 font-mono">0 / 0</span>
            </div>
            <div class="p-1.5 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[8px] uppercase font-bold text-slate-400 block truncate">&lt; 30M Below</span>
                <span id="reportStatFailCount" class="text-xs font-black text-rose-400 font-mono">0 / 0</span>
            </div>
        </div>

        <!-- Modal Filter / Search Controls -->
        <div class="px-2.5 py-2 bg-slate-900/80 border-b border-slate-800 flex items-center gap-1.5 shrink-0 flex-wrap sm:flex-nowrap">
            <div class="relative flex-1 min-w-[130px]">
                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                <input type="text" 
                       id="reportSearchInput" 
                       oninput="filterMobileReportList()" 
                       placeholder="Search roll, name, SBTE..." 
                       class="w-full pl-8 pr-2.5 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 outline-none focus:border-amber-500 font-sans">
            </div>
            <select id="reportBatchFilter" 
                    onchange="filterMobileReportList()" 
                    class="px-2 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-300 font-bold outline-none shrink-0">
                <option value="ALL">All Batches</option>
                <option value="1">Batch 1</option>
                <option value="2">Batch 2</option>
            </select>
            <select id="reportCompletionFilter" 
                    onchange="filterMobileReportList()" 
                    class="px-2 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-300 font-bold outline-none shrink-0">
                <option value="ALL">All Status</option>
                <option value="ALL_WORKS_DONE">6/6 Works Done</option>
                <option value="PENDING_WORKS">Pending Works</option>
                <option value="3_DONE">3/3 Trades Done</option>
            </select>
            <!-- View Mode Switcher: Table vs Cards -->
            <div class="flex items-center p-0.5 bg-slate-950 border border-slate-800 rounded-xl shrink-0">
                <button type="button" 
                        onclick="setReportViewMode('table')" 
                        id="btnReportViewTable" 
                        class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 bg-amber-500 text-slate-950 shadow-sm font-black">
                    <i class="fa-solid fa-table text-[10px]"></i>
                    <span class="text-[10px]">Table</span>
                </button>
                <button type="button" 
                        onclick="setReportViewMode('cards')" 
                        id="btnReportViewCards" 
                        class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 text-slate-400 hover:text-white">
                    <i class="fa-solid fa-address-card text-[10px]"></i>
                    <span class="text-[10px]">Cards</span>
                </button>
            </div>
        </div>

        <!-- Scrollable Student List Container -->
        <div class="p-2.5 overflow-y-auto space-y-2.5 flex-1 overscroll-contain bg-slate-950" id="reportStudentListContainer">
            <!-- Dynamically populated with 3-section completion breakdown -->
        </div>

        <!-- Modal Bottom Bar -->
        <div class="px-3.5 py-2 bg-slate-900 border-t border-slate-800 flex items-center justify-between shrink-0">
            <span class="text-[10px] text-slate-400 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-emerald-400 text-[9px]"></i>
                <span>Continuous CIA 75M live sync</span>
            </span>
            <button type="button" 
                    onclick="closeMobileReportModal()" 
                    class="px-3 py-1 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-all">
                Close
            </button>
        </div>
    </div>

    <script>
        const subjectId = {{ $batchSubject->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Master Data
        const allSections = @json($sections);
        const sectionStudentsMap = @json($sectionStudents);
        const rawAllocations = @json($allocations);
        const rawWorkEvals = @json($workEvals);
        const rawSectionEvals = @json($sectionEvals);
        const rawCalculatedScores = @json($calculatedScores ?? []);
        const rawAllStudents = @json($students ?? []);

        let activeSection = allSections[0] || 'Machine shop';
        let currentMode = 'works'; // 'works', 'records', 'tests', 'attendance'
        let attendanceViewMode = 'row'; // 'row' or 'grid'
        let activeAttendanceBatch = 'ALL'; // 'ALL', '1', '2', 'SECTION'
        let activeAttendanceDate = '{{ date('Y-m-d') }}';
        let reportViewMode = 'table'; // 'table' or 'card'
        const mobileAttendanceCache = {}; // { [cacheKey]: { [regNo]: boolean } }

        document.addEventListener('DOMContentLoaded', () => {
            renderActiveSection();
        });

        // Switch Trade Section with Vibrant Orange Highlight
        function switchMobileSection(secName) {
            activeSection = secName;
            document.querySelectorAll('.section-pill').forEach(btn => {
                const isActive = btn.dataset.sec === secName;
                btn.className = `section-pill px-3 py-1.5 rounded-xl text-xs font-bold shrink-0 transition-all flex items-center gap-1.5 border ${
                    isActive 
                    ? 'bg-orange-500 text-white border-orange-400 shadow-lg shadow-orange-500/30 ring-2 ring-orange-500/50' 
                    : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white'
                }`;
                const badge = btn.querySelector('span:last-child');
                if (badge) {
                    badge.className = `px-1.5 py-0.2 rounded-full text-[10px] ${isActive ? 'bg-white/20 text-white font-black' : 'bg-slate-800 text-slate-400'}`;
                }
            });

            const label = document.getElementById('activeSectionLabel');
            if (label) label.innerText = secName;
            renderActiveSection();
        }

        // Switch Action Mode using Native Mobile Bottom Navigation Dock
        function switchMobileMode(mode) {
            currentMode = mode;
            document.querySelectorAll('.bottom-nav-item').forEach(btn => {
                if (btn.id === 'modeBtn_status') {
                    btn.className = 'bottom-nav-item text-slate-400 hover:text-amber-400 active:scale-95 transition-all';
                } else {
                    btn.className = 'bottom-nav-item text-slate-400 hover:text-white';
                }
            });

            const activeBtn = document.getElementById(`modeBtn_${mode}`);
            if (activeBtn) {
                if (mode === 'works') {
                    activeBtn.className = 'bottom-nav-item active-works';
                } else if (mode === 'records') {
                    activeBtn.className = 'bottom-nav-item active-records';
                } else if (mode === 'tests') {
                    activeBtn.className = 'bottom-nav-item active-tests';
                } else if (mode === 'attendance') {
                    activeBtn.className = 'bottom-nav-item active-attendance';
                }
            }

            // Update subheader active mode indicator
            const modeLabel = document.getElementById('activeModeLabel');
            if (modeLabel) {
                const names = {
                    works: 'Works (20M)',
                    records: 'Record (20M)',
                    tests: 'Test (15M)',
                    attendance: 'Roll Call'
                };
                const colors = {
                    works: 'text-amber-400',
                    records: 'text-emerald-400',
                    tests: 'text-purple-400',
                    attendance: 'text-cyan-400'
                };
                modeLabel.className = `${colors[mode] || 'text-white'} font-extrabold text-[11px]`;
                modeLabel.innerText = names[mode] || mode;
            }

            renderActiveSection();
        }

        // Get students based on active attendance batch selection
        function getAttendanceStudents() {
            if (activeAttendanceBatch === '1') {
                return rawAllStudents.filter(st => {
                    const alloc = rawAllocations[st.reg_no] || {};
                    return String(alloc.batch_no || '1') === '1';
                });
            } else if (activeAttendanceBatch === '2') {
                return rawAllStudents.filter(st => {
                    const alloc = rawAllocations[st.reg_no] || {};
                    return String(alloc.batch_no || '1') === '2';
                });
            } else if (activeAttendanceBatch === 'SECTION') {
                return sectionStudentsMap[activeSection] || [];
            }
            return rawAllStudents;
        }

        function getAttendanceCacheKey() {
            return `${activeAttendanceDate}__${activeAttendanceBatch}__${activeSection}`;
        }

        async function changeAttendanceDate(newDate) {
            if (!newDate) return;
            activeAttendanceDate = newDate;
            await loadAttendanceForCurrentView();
        }

        async function setAttendanceBatch(batch) {
            activeAttendanceBatch = batch;
            await loadAttendanceForCurrentView();
        }

        async function loadAttendanceForCurrentView() {
            const cacheKey = getAttendanceCacheKey();
            if (!mobileAttendanceCache[cacheKey]) {
                try {
                    const res = await fetch(`/api/staff/mobile/workshop/${subjectId}/section-attendance?date=${activeAttendanceDate}&batch_name=${activeAttendanceBatch}&section_name=${activeSection}`);
                    const data = await res.json();
                    if (data.success && data.has_existing && data.records) {
                        mobileAttendanceCache[cacheKey] = data.records;
                    }
                } catch (e) {
                    console.error('Error loading attendance for date:', e);
                }
            }
            renderAttendanceSection();
        }

        // Toggle Attendance View Mode: Row vs Grid
        function setAttendanceViewMode(view) {
            attendanceViewMode = view;
            renderAttendanceSection();
        }

        // Render Active Section & Students
        function renderActiveSection() {
            // Specialized space-efficient Roll Call handler
            if (currentMode === 'attendance') {
                const emptyNotice = document.getElementById('emptySectionNotice');
                if (emptyNotice) emptyNotice.classList.add('hidden');
                renderQuickPresets();
                const countLabel = document.getElementById('activeSectionCount');
                const summaryLabel = document.getElementById('barSectionSummary');
                const attStudents = getAttendanceStudents();
                if (countLabel) countLabel.innerText = `${attStudents.length} Students`;
                if (summaryLabel) {
                    const batchNames = { 'ALL': 'Full Batch', '1': 'Batch 1 (B1)', '2': 'Batch 2 (B2)', 'SECTION': activeSection };
                    summaryLabel.innerText = `Roll Call • ${batchNames[activeAttendanceBatch] || activeAttendanceBatch}`;
                }
                renderAttendanceSection(attStudents);
                return;
            }

            const students = sectionStudentsMap[activeSection] || [];
            const container = document.getElementById('studentCardsContainer');
            const emptyNotice = document.getElementById('emptySectionNotice');
            const countLabel = document.getElementById('activeSectionCount');
            const summaryLabel = document.getElementById('barSectionSummary');

            if (countLabel) countLabel.innerText = `${students.length} Students`;
            if (summaryLabel) {
                const modeNames = { works: 'Day Works (Max 20)', records: 'Rough & Fair Record', tests: 'Trade Test (Max 15)', attendance: 'Section Roll Call' };
                summaryLabel.innerText = `${activeSection} • ${modeNames[currentMode]}`;
            }

            renderQuickPresets();

            if (students.length === 0) {
                container.innerHTML = '';
                emptyNotice.classList.remove('hidden');
                return;
            }
            emptyNotice.classList.add('hidden');

            let html = '';
            students.forEach(st => {
                const rNo = st.reg_no;
                const alloc = rawAllocations[rNo] || {};
                const bNo = alloc.batch_no || '1';

                const wRec = (rawWorkEvals[activeSection] && rawWorkEvals[activeSection][rNo]) ? rawWorkEvals[activeSection][rNo] : {};
                const sRec = (rawSectionEvals[activeSection] && rawSectionEvals[activeSection][rNo]) ? rawSectionEvals[activeSection][rNo] : {};

                html += `
                <div class="bg-slate-900 border border-slate-800/90 rounded-2xl p-3 shadow-sm student-card" data-reg="${rNo}">
                    <!-- Card Top: Student Info -->
                    <div class="flex items-center justify-between gap-2 mb-2 pb-1.5 border-b border-slate-800">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-slate-800 border border-slate-700 text-cyan-400 font-mono text-[11px] font-bold flex items-center justify-center shrink-0">
                                ${st.roll_no || '-'}
                            </span>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-white truncate">${st.name}</div>
                                <div class="text-[10px] text-slate-500 font-mono">SBTE: ${st.sbte_reg_no || rNo}</div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono ${bNo === '1' ? 'bg-sky-950 text-sky-400 border border-sky-800' : 'bg-purple-950 text-purple-400 border border-purple-800'}">
                            Batch ${bNo}
                        </span>
                    </div>
                `;

                if (currentMode === 'works') {
                    const w1 = wRec.work_1_mark !== undefined ? parseFloat(wRec.work_1_mark) : 0;
                    const w2 = wRec.work_2_mark !== undefined ? parseFloat(wRec.work_2_mark) : 0;
                    const w3 = wRec.work_3_mark !== undefined ? parseFloat(wRec.work_3_mark) : 0;
                    const w4 = wRec.work_4_mark !== undefined ? parseFloat(wRec.work_4_mark) : 0;
                    const alum = wRec.alum_work_mark !== undefined ? parseFloat(wRec.alum_work_mark) : 0;

                    let avg = 0;
                    let worksHtml = '';

                    if (activeSection === 'Machine shop') {
                        avg = ((w1 + w2 + w3 + w4) / 4).toFixed(2);
                        worksHtml = `
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Work 1 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Work 1 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w1-val" data-reg="${rNo}" value="${w1}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w1', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w1}" tabindex="-1" class="mark-slider w1-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w1', this.value)">
                            </div>
                            <!-- Work 2 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Work 2 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w2-val" data-reg="${rNo}" value="${w2}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w2', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w2}" tabindex="-1" class="mark-slider w2-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w2', this.value)">
                            </div>
                            <!-- Work 3 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Work 3 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w3-val" data-reg="${rNo}" value="${w3}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w3', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w3}" tabindex="-1" class="mark-slider w3-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w3', this.value)">
                            </div>
                            <!-- Work 4 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Work 4 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w4-val" data-reg="${rNo}" value="${w4}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w4', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w4}" tabindex="-1" class="mark-slider w4-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w4', this.value)">
                            </div>
                        </div>
                        `;
                    } else if (activeSection === 'Sheet metal') {
                        avg = (alum > 0 ? (w1 + w2 + alum) / 3 : (w1 + w2) / 2).toFixed(2);
                        worksHtml = `
                        <div class="grid grid-cols-2 gap-2">
                            <!-- SM Work 1 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">SM Work 1 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w1-val" data-reg="${rNo}" value="${w1}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w1', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w1}" tabindex="-1" class="mark-slider w1-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w1', this.value)">
                            </div>
                            <!-- SM Work 2 -->
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">SM Work 2 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w2-val" data-reg="${rNo}" value="${w2}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w2', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w2}" tabindex="-1" class="mark-slider w2-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w2', this.value)">
                            </div>
                        </div>
                        <div class="mt-2 bg-amber-950/30 p-2 rounded-xl border border-amber-800/50">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[9px] text-amber-400 font-bold uppercase"><i class="fa-solid fa-cube mr-1"></i>Aluminium Fabrication Work (20M)</span>
                                <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box alum-val text-amber-300" data-reg="${rNo}" value="${alum}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'alum', this.value, true)">
                            </div>
                            <input type="range" min="0" max="20" step="0.5" value="${alum}" tabindex="-1" class="mark-slider alum-slider accent-amber-500" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'alum', this.value)">
                        </div>
                        `;
                    } else {
                        avg = ((w1 + w2) / 2).toFixed(2);
                        worksHtml = `
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Work 1 -->
                            <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Work 1 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w1-val" data-reg="${rNo}" value="${w1}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w1', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w1}" tabindex="-1" class="mark-slider w1-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w1', this.value)">
                            </div>
                            <!-- Work 2 -->
                            <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Work 2 (20M)</span>
                                    <input type="number" step="0.5" min="0" max="20" inputmode="decimal" class="mark-input-box w2-val" data-reg="${rNo}" value="${w2}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'w2', this.value, true)">
                                </div>
                                <input type="range" min="0" max="20" step="0.5" value="${w2}" tabindex="-1" class="mark-slider w2-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'w2', this.value)">
                            </div>
                        </div>
                        `;
                    }

                    html += `
                    ${worksHtml}
                    <div class="mt-2 flex items-center justify-between px-1">
                        <span class="text-[10px] text-slate-500 font-medium">Auto Average / 20:</span>
                        <span class="text-xs font-mono font-extrabold text-orange-400" id="work_avg_${rNo}">${avg} / 20</span>
                    </div>
                    `;
                } else if (currentMode === 'records') {
                    const rough = sRec.rough_record_mark !== undefined ? parseFloat(sRec.rough_record_mark) : 0;
                    const fair  = sRec.fair_record_mark !== undefined ? parseFloat(sRec.fair_record_mark) : 0;
                    const smTot = (rough + fair);

                    if (activeSection === 'Sheet metal') {
                        const alumRough = sRec.alum_rough_record_mark !== undefined && sRec.alum_rough_record_mark !== null ? parseFloat(sRec.alum_rough_record_mark) : 0;
                        const alumFair  = sRec.alum_fair_record_mark !== undefined && sRec.alum_fair_record_mark !== null ? parseFloat(sRec.alum_fair_record_mark) : 0;
                        const alumTot = (alumRough + alumFair);
                        const combined = (sRec.alum_rough_record_mark !== null || sRec.alum_fair_record_mark !== null) ? ((smTot + alumTot) / 2).toFixed(2) : smTot.toFixed(2);

                        html += `
                        <!-- Sheet Metal Record -->
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1 flex items-center justify-between">
                            <span><i class="fa-solid fa-sheet-plastic text-cyan-400 mr-1"></i> Sheet Metal Record</span>
                            <span class="text-slate-300 font-mono" id="sm_record_tot_${rNo}">${smTot.toFixed(2)} / 20</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Rough (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box rough-val" data-reg="${rNo}" value="${rough}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'rough', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${rough}" tabindex="-1" class="mark-slider rough-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'rough', this.value)">
                            </div>
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase">Fair (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box fair-val" data-reg="${rNo}" value="${fair}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'fair', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${fair}" tabindex="-1" class="mark-slider fair-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'fair', this.value)">
                            </div>
                        </div>

                        <!-- Aluminium Fabrication Record -->
                        <div class="text-[10px] font-bold text-amber-400 uppercase tracking-wide mb-1 flex items-center justify-between">
                            <span><i class="fa-solid fa-cube mr-1"></i> Aluminium Fab Record</span>
                            <span class="text-amber-300 font-mono" id="alum_record_tot_${rNo}">${alumTot.toFixed(2)} / 20</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-amber-800/40">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-amber-400/80 font-bold uppercase">Alum Rough (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box alum-rough-val text-amber-300" data-reg="${rNo}" value="${alumRough}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'alum-rough', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${alumRough}" tabindex="-1" class="mark-slider alum-rough-slider accent-amber-500" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'alum-rough', this.value)">
                            </div>
                            <div class="bg-slate-950/70 p-2 rounded-xl border border-amber-800/40">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] text-amber-400/80 font-bold uppercase">Alum Fair (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box alum-fair-val text-amber-300" data-reg="${rNo}" value="${alumFair}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'alum-fair', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${alumFair}" tabindex="-1" class="mark-slider alum-fair-slider accent-amber-500" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'alum-fair', this.value)">
                            </div>
                        </div>

                        <div class="mt-2.5 flex items-center justify-between px-1 pt-1.5 border-t border-slate-800">
                            <span class="text-[10px] text-slate-400 font-bold">Section Record Avg / 20:</span>
                            <span class="text-xs font-mono font-extrabold text-emerald-400" id="record_tot_${rNo}">${combined} / 20</span>
                        </div>
                        `;
                    } else {
                        const tot = smTot.toFixed(2);
                        html += `
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- Rough Record -->
                            <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Rough (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box rough-val" data-reg="${rNo}" value="${rough}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'rough', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${rough}" tabindex="-1" class="mark-slider rough-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'rough', this.value)">
                            </div>
                            <!-- Fair Record -->
                            <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800/80">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">Fair (10M)</span>
                                    <input type="number" step="0.5" min="0" max="10" inputmode="decimal" class="mark-input-box fair-val" data-reg="${rNo}" value="${fair}" onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'fair', this.value, true)">
                                </div>
                                <input type="range" min="0" max="10" step="0.5" value="${fair}" tabindex="-1" class="mark-slider fair-slider" data-reg="${rNo}" onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'fair', this.value)">
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between px-1">
                            <span class="text-[10px] text-slate-500 font-medium">Total Record / 20 (Open-Ended):</span>
                            <span class="text-xs font-mono font-extrabold text-emerald-400" id="record_tot_${rNo}">${tot} / 20</span>
                        </div>
                        `;
                    }
                }
                } else if (currentMode === 'tests') {
                    const test = sRec.test_mark !== undefined ? parseFloat(sRec.test_mark) : 0;
                    const isAbs = !empty(sRec.is_test_absent);

                    html += `
                    <!-- Compact Test Entry: Constrained Slider next to Input & Absent Pill -->
                    <div class="bg-slate-950/70 p-2.5 rounded-xl border border-slate-800/80 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Trade Test</span>
                            <span class="text-[10px] text-purple-400 font-mono font-bold">15 Marks Max</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Constrained Slider (not full width) -->
                            <input type="range" min="0" max="15" step="0.5" value="${test}" tabindex="-1" class="mark-slider test-slider w-24 sm:w-32 accent-purple-500" data-reg="${rNo}" ${isAbs ? 'disabled' : ''} onfocus="this.blur()" ontouchstart="handleSliderTouch(this, event)" onpointerdown="handleSliderTouch(this, event)" oninput="syncMobileInput('${rNo}', 'test', this.value)">
                            <!-- Number Input -->
                            <input type="number" step="0.5" min="0" max="15" inputmode="decimal" class="mark-input-box test-val w-12 text-center" data-reg="${rNo}" value="${test}" ${isAbs ? 'disabled' : ''} onfocus="this.select()" onclick="this.focus(); this.select()" oninput="syncMobileSlider('${rNo}', 'test', this.value, true)">
                            <!-- Absent Pill -->
                            <button type="button" 
                                    class="test-abs-pill px-2.5 py-1.5 rounded-xl text-xs font-bold border transition-all ${isAbs ? 'bg-rose-500/20 text-rose-400 border-rose-500/50' : 'bg-slate-800 text-slate-300 border-slate-700'}"
                                    onclick="toggleMobileTestAbsent('${rNo}')">
                                <i class="fa-solid fa-${isAbs ? 'user-slash' : 'user-check'} mr-1"></i> ${isAbs ? 'Abs' : 'Pres'}
                            </button>
                            <input type="hidden" class="test-abs-val" data-reg="${rNo}" value="${isAbs ? '1' : '0'}">
                        </div>
                    </div>
                    `;
                }

                html += `</div>`;
            });

            container.innerHTML = html;
        }

        // Render Redesigned Roll Call (Row list or Quick Touch Grid)
        function renderAttendanceSection(students = null) {
            if (!students) students = getAttendanceStudents();
            const container = document.getElementById('studentCardsContainer');
            if (!container) return;

            const cacheKey = getAttendanceCacheKey();
            // Initialize cache for active date/batch/section if needed
            if (!mobileAttendanceCache[cacheKey]) {
                mobileAttendanceCache[cacheKey] = {};
                students.forEach(st => {
                    mobileAttendanceCache[cacheKey][st.reg_no] = true; // default present
                });
            }

            const currentStates = mobileAttendanceCache[cacheKey];
            let presCount = 0;
            let absCount = 0;
            students.forEach(st => {
                if (currentStates[st.reg_no] !== false) presCount++;
                else absCount++;
            });

            let topBarHtml = `
            <!-- Space-Efficient Roll Call Control Bar -->
            <div class="bg-slate-900 border border-slate-800 p-2.5 rounded-2xl mb-2.5 space-y-2 shadow-sm">
                <!-- Row 1: Date Picker & Present/Absent Counts & View Switcher -->
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5 bg-slate-950 border border-slate-800 rounded-xl px-2 py-1 shadow-inner">
                        <i class="fa-solid fa-calendar-day text-cyan-400 text-xs"></i>
                        <input type="date" 
                               value="${activeAttendanceDate}" 
                               onchange="changeAttendanceDate(this.value)" 
                               class="bg-transparent text-cyan-300 font-mono text-xs outline-none cursor-pointer">
                    </div>
                    
                    <div class="text-xs font-mono font-bold text-white shrink-0">
                        <span id="attStatsPresent" class="text-emerald-400 font-black">${presCount}</span> P &bull; 
                        <span id="attStatsAbsent" class="text-rose-400 font-black">${absCount}</span> A
                    </div>

                    <!-- View Switcher: Row vs Grid -->
                    <div class="flex items-center p-0.5 bg-slate-950 border border-slate-800 rounded-xl shrink-0">
                        <button type="button" 
                                onclick="setAttendanceViewMode('row')" 
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 ${attendanceViewMode === 'row' ? 'bg-cyan-500 text-slate-950 shadow-sm font-black' : 'text-slate-400 hover:text-white'}">
                            <i class="fa-solid fa-list text-[10px]"></i>
                            <span>Row</span>
                        </button>
                        <button type="button" 
                                onclick="setAttendanceViewMode('grid')" 
                                class="px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 ${attendanceViewMode === 'grid' ? 'bg-cyan-500 text-slate-950 shadow-sm font-black' : 'text-slate-400 hover:text-white'}">
                            <i class="fa-solid fa-table-cells text-[10px]"></i>
                            <span>Grid</span>
                        </button>
                    </div>
                </div>

                <!-- Row 2: Attendance Batch Selector (Full Batch, B1, B2, Trade Section) -->
                <div class="flex items-center justify-between gap-1.5 pt-1 border-t border-slate-800/80">
                    <div class="flex items-center gap-1 overflow-x-auto scrollbar-none py-0.5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase mr-1 shrink-0">Batch:</span>
                        <button type="button" 
                                onclick="setAttendanceBatch('ALL')" 
                                class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all ${activeAttendanceBatch === 'ALL' ? 'bg-cyan-500 text-slate-950 font-black ring-1 ring-cyan-400' : 'bg-slate-950 text-slate-400 border border-slate-800 hover:text-white'}">
                            Full Batch
                        </button>
                        <button type="button" 
                                onclick="setAttendanceBatch('1')" 
                                class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all ${activeAttendanceBatch === '1' ? 'bg-sky-500 text-slate-950 font-black ring-1 ring-sky-400' : 'bg-slate-950 text-slate-400 border border-slate-800 hover:text-white'}">
                            B1
                        </button>
                        <button type="button" 
                                onclick="setAttendanceBatch('2')" 
                                class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all ${activeAttendanceBatch === '2' ? 'bg-purple-500 text-slate-950 font-black ring-1 ring-purple-400' : 'bg-slate-950 text-slate-400 border border-slate-800 hover:text-white'}">
                            B2
                        </button>
                        <button type="button" 
                                onclick="setAttendanceBatch('SECTION')" 
                                class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all ${activeAttendanceBatch === 'SECTION' ? 'bg-orange-500 text-slate-950 font-black ring-1 ring-orange-400' : 'bg-slate-950 text-slate-400 border border-slate-800 hover:text-white'}">
                            ${activeSection}
                        </button>
                    </div>

                    <!-- Quick mark all buttons -->
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="quickSetAllAttendance(true)" class="px-2 py-0.5 rounded-lg bg-emerald-950/80 border border-emerald-800 text-emerald-400 text-[10px] font-bold active:scale-95">All P</button>
                        <button type="button" onclick="quickSetAllAttendance(false)" class="px-2 py-0.5 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-400 text-[10px] font-bold active:scale-95">All A</button>
                    </div>
                </div>
            </div>
            `;

            if (attendanceViewMode === 'row') {
                // Compact Row List View: ~42px height per student
                let rowsHtml = '<div class="space-y-1.5" id="attendanceRowsContainer">';
                students.forEach(st => {
                    const rNo = st.reg_no;
                    const alloc = rawAllocations[rNo] || {};
                    const bNo = alloc.batch_no || '1';
                    const isPres = currentStates[rNo] !== false;

                    rowsHtml += `
                    <div class="att-row-item student-card flex items-center justify-between p-2.5 bg-slate-900 border border-slate-800 rounded-xl hover:border-slate-700 transition-all gap-2" data-reg="${rNo}">
                        <!-- Student Info: Roll, Name, SBTE No & Batch -->
                        <div class="flex items-center gap-2.5 min-w-0" onclick="toggleMobileAttendance('${rNo}')" style="cursor: pointer;">
                            <span class="w-7 h-7 rounded-lg bg-slate-800 border border-slate-700 text-cyan-400 font-mono text-xs font-black flex items-center justify-center shrink-0">
                                ${st.roll_no || '-'}
                            </span>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-white truncate">${st.name}</div>
                                <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1.5">
                                    <span>SBTE: ${st.sbte_reg_no || rNo}</span>
                                    <span class="text-slate-600">&bull;</span>
                                    <span class="text-sky-400 font-bold">B${bNo}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox + Inline Status Button in Row -->
                        <div class="flex items-center gap-2 shrink-0">
                            <input type="checkbox" 
                                   class="att-checkbox w-4 h-4 rounded text-emerald-500 bg-slate-950 border-slate-700 focus:ring-0 cursor-pointer" 
                                   data-reg="${rNo}" 
                                   ${isPres ? 'checked' : ''} 
                                   onchange="toggleMobileAttendance('${rNo}', this.checked)">
                            <button type="button" 
                                    class="att-status-btn px-3 py-1.5 rounded-lg text-xs font-bold border flex items-center gap-1.5 transition-all ${isPres ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/50' : 'bg-rose-500/20 text-rose-400 border-rose-500/50'}"
                                    data-reg="${rNo}"
                                    onclick="toggleMobileAttendance('${rNo}')">
                                <i class="fa-solid fa-${isPres ? 'check' : 'xmark'} text-[10px]"></i>
                                <span>${isPres ? 'Present' : 'Absent'}</span>
                            </button>
                            <input type="hidden" class="att-val" data-reg="${rNo}" value="${isPres ? '1' : '0'}">
                        </div>
                    </div>
                    `;
                });
                rowsHtml += '</div>';
                container.innerHTML = topBarHtml + rowsHtml;
            } else {
                // Compact Matrix Grid View: Quick Touch Chips for Instant Marking
                let gridHtml = '<div class="grid grid-cols-4 sm:grid-cols-6 gap-2" id="attendanceGridContainer">';
                students.forEach(st => {
                    const rNo = st.reg_no;
                    const isPres = currentStates[rNo] !== false;

                    gridHtml += `
                    <div class="att-grid-cell student-card cursor-pointer p-2 rounded-xl border flex flex-col items-center justify-center transition-all ${isPres ? 'bg-emerald-950/70 border-emerald-600/80 text-emerald-300' : 'bg-rose-950/70 border-rose-600/80 text-rose-300'}"
                         data-reg="${rNo}"
                         onclick="toggleMobileAttendance('${rNo}')">
                        <div class="flex items-center gap-1 font-mono text-xs font-black">
                            <span>#${st.roll_no || '-'}</span>
                            <i class="fa-solid fa-${isPres ? 'check' : 'xmark'} text-[10px]"></i>
                        </div>
                        <div class="text-[9px] font-bold truncate max-w-full text-center mt-0.5">${st.name.split(' ')[0]}</div>
                        <div class="text-[8px] font-mono opacity-70 truncate max-w-full">${(st.sbte_reg_no || rNo).slice(-4)}</div>
                        <input type="hidden" class="att-val" data-reg="${rNo}" value="${isPres ? '1' : '0'}">
                    </div>
                    `;
                });
                gridHtml += '</div>';
                container.innerHTML = topBarHtml + gridHtml;
            }
        }

        // Toggle Single Student Attendance
        function toggleMobileAttendance(regNo, explicitValue = null) {
            const cacheKey = getAttendanceCacheKey();
            if (!mobileAttendanceCache[cacheKey]) {
                mobileAttendanceCache[cacheKey] = {};
            }

            const current = mobileAttendanceCache[cacheKey][regNo] !== false;
            const nextVal = (explicitValue !== null) ? !!explicitValue : !current;
            mobileAttendanceCache[cacheKey][regNo] = nextVal;

            // Sync hidden inputs
            document.querySelectorAll(`.att-val[data-reg="${regNo}"]`).forEach(el => el.value = nextVal ? '1' : '0');

            // Sync checkboxes
            document.querySelectorAll(`.att-checkbox[data-reg="${regNo}"]`).forEach(cb => cb.checked = nextVal);

            // Sync row status buttons
            document.querySelectorAll(`.att-status-btn[data-reg="${regNo}"]`).forEach(btn => {
                if (nextVal) {
                    btn.className = 'att-status-btn px-3 py-1.5 rounded-lg text-xs font-bold border flex items-center gap-1.5 transition-all bg-emerald-500/20 text-emerald-400 border-emerald-500/50';
                    btn.innerHTML = `<i class="fa-solid fa-check text-[10px]"></i> <span>Present</span>`;
                } else {
                    btn.className = 'att-status-btn px-3 py-1.5 rounded-lg text-xs font-bold border flex items-center gap-1.5 transition-all bg-rose-500/20 text-rose-400 border-rose-500/50';
                    btn.innerHTML = `<i class="fa-solid fa-xmark text-[10px]"></i> <span>Absent</span>`;
                }
            });

            // Sync grid cells
            document.querySelectorAll(`.att-grid-cell[data-reg="${regNo}"]`).forEach(cell => {
                if (nextVal) {
                    cell.className = 'att-grid-cell student-card cursor-pointer p-2 rounded-xl border flex flex-col items-center justify-center transition-all bg-emerald-950/70 border-emerald-600/80 text-emerald-300';
                    const icon = cell.querySelector('i');
                    if (icon) icon.className = 'fa-solid fa-check text-[10px]';
                } else {
                    cell.className = 'att-grid-cell student-card cursor-pointer p-2 rounded-xl border flex flex-col items-center justify-center transition-all bg-rose-950/70 border-rose-600/80 text-rose-300';
                    const icon = cell.querySelector('i');
                    if (icon) icon.className = 'fa-solid fa-xmark text-[10px]';
                }
            });

            // Update stats
            updateAttendanceStats();

            triggerMobileAutosave();
        }

        // Update live attendance numbers in header
        function updateAttendanceStats() {
            const students = getAttendanceStudents();
            const cacheKey = getAttendanceCacheKey();
            const currentStates = mobileAttendanceCache[cacheKey] || {};
            let pres = 0;
            let abs = 0;
            students.forEach(st => {
                if (currentStates[st.reg_no] !== false) pres++;
                else abs++;
            });

            const presEl = document.getElementById('attStatsPresent');
            const absEl = document.getElementById('attStatsAbsent');
            if (presEl) presEl.innerText = pres;
            if (absEl) absEl.innerText = abs;
        }

        // Quick Presets chips at top
        function renderQuickPresets() {
            const bar = document.getElementById('quickPresetsContainer');
            if (!bar) return;

            // Bulk presets for works, records, and tests (e.g. All 18, All 19, All 20, All 10+10)
            // have been removed to avoid accidental mass overwrites on mobile.
            if (currentMode === 'attendance') {
                bar.innerHTML = `
                    <button type="button" class="chip-pill" onclick="quickSetAllAttendance(true)">All Present</button>
                    <button type="button" class="chip-pill" onclick="quickSetAllAttendance(false)">All Absent</button>
                `;
            } else {
                bar.innerHTML = '';
            }
        }

        // Handle Slider Touch to Prevent Software Keypad
        function handleSliderTouch(sliderEl, e) {
            // Dismiss and blur any active text/number input field to ensure keypad is never opened when sliding
            if (document.activeElement && typeof document.activeElement.blur === 'function') {
                document.activeElement.blur();
            }
            if (sliderEl && typeof sliderEl.blur === 'function') {
                sliderEl.blur();
            }
        }

        // Global listeners: When touching or dragging any slider, prevent keypad / blur active element
        document.addEventListener('touchstart', (e) => {
            if (e.target && e.target.classList && e.target.classList.contains('mark-slider')) {
                if (document.activeElement && document.activeElement.tagName === 'INPUT' && !document.activeElement.classList.contains('mark-slider')) {
                    document.activeElement.blur();
                }
                if (typeof e.target.blur === 'function') e.target.blur();
            }
        }, { passive: true });

        document.addEventListener('pointerdown', (e) => {
            if (e.target && e.target.classList && e.target.classList.contains('mark-slider')) {
                if (document.activeElement && document.activeElement.tagName === 'INPUT' && !document.activeElement.classList.contains('mark-slider')) {
                    document.activeElement.blur();
                }
                if (typeof e.target.blur === 'function') e.target.blur();
            }
        }, { passive: true });

        // Slider & Input Synchronizer
        function syncMobileInput(regNo, field, val) {
            const numVal = parseFloat(val || 0);
            const input = document.querySelector(`.${field}-val[data-reg="${regNo}"]`);
            if (input) {
                input.value = numVal;
                // If input had focus, blur it so keypad doesn't stay open while dragging slider
                if (document.activeElement === input) {
                    input.blur();
                }
            }

            if (['w1', 'w2', 'w3', 'w4', 'alum'].includes(field)) updateWorkAvg(regNo);
            if (['rough', 'fair', 'alum-rough', 'alum-fair'].includes(field)) updateRecordTot(regNo);

            triggerMobileAutosave();
        }

        function syncMobileSlider(regNo, field, val, fromInput = false) {
            let numVal = parseFloat(val);
            if (isNaN(numVal)) numVal = 0;
            const slider = document.querySelector(`.${field}-slider[data-reg="${regNo}"]`);
            if (slider) slider.value = numVal;

            if (['w1', 'w2', 'w3', 'w4', 'alum'].includes(field)) updateWorkAvg(regNo);
            if (['rough', 'fair', 'alum-rough', 'alum-fair'].includes(field)) updateRecordTot(regNo);

            triggerMobileAutosave();
        }

        function updateWorkAvg(regNo) {
            const w1 = parseFloat(document.querySelector(`.w1-val[data-reg="${regNo}"]`)?.value || 0);
            const w2 = parseFloat(document.querySelector(`.w2-val[data-reg="${regNo}"]`)?.value || 0);
            let avg = 0;

            if (activeSection === 'Machine shop') {
                const w3 = parseFloat(document.querySelector(`.w3-val[data-reg="${regNo}"]`)?.value || 0);
                const w4 = parseFloat(document.querySelector(`.w4-val[data-reg="${regNo}"]`)?.value || 0);
                avg = ((w1 + w2 + w3 + w4) / 4).toFixed(2);
            } else if (activeSection === 'Sheet metal') {
                const alum = parseFloat(document.querySelector(`.alum-val[data-reg="${regNo}"]`)?.value || 0);
                avg = (alum > 0 ? (w1 + w2 + alum) / 3 : (w1 + w2) / 2).toFixed(2);
            } else {
                avg = ((w1 + w2) / 2).toFixed(2);
            }

            const badge = document.getElementById(`work_avg_${regNo}`);
            if (badge) badge.innerText = `${avg} / 20`;
        }

        function updateRecordTot(regNo) {
            const r = parseFloat(document.querySelector(`.rough-val[data-reg="${regNo}"]`)?.value || 0);
            const f = parseFloat(document.querySelector(`.fair-val[data-reg="${regNo}"]`)?.value || 0);
            const smTot = (r + f);

            if (activeSection === 'Sheet metal') {
                const alumR = parseFloat(document.querySelector(`.alum-rough-val[data-reg="${regNo}"]`)?.value || 0);
                const alumF = parseFloat(document.querySelector(`.alum-fair-val[data-reg="${regNo}"]`)?.value || 0);
                const alumTot = (alumR + alumF);
                const combined = ((alumR > 0 || alumF > 0) ? (smTot + alumTot) / 2 : smTot).toFixed(2);

                const smEl = document.getElementById(`sm_record_tot_${regNo}`);
                if (smEl) smEl.innerText = `${smTot.toFixed(2)} / 20`;
                const alumEl = document.getElementById(`alum_record_tot_${regNo}`);
                if (alumEl) alumEl.innerText = `${alumTot.toFixed(2)} / 20`;
                const badge = document.getElementById(`record_tot_${regNo}`);
                if (badge) badge.innerText = `${combined} / 20`;
            } else {
                const badge = document.getElementById(`record_tot_${regNo}`);
                if (badge) badge.innerText = `${smTot.toFixed(2)} / 20`;
            }
        }

        function toggleMobileTestAbsent(regNo) {
            const hidden = document.querySelector(`.test-abs-val[data-reg="${regNo}"]`);
            const btn = document.querySelector(`.student-card[data-reg="${regNo}"] .test-abs-pill`);
            const input = document.querySelector(`.test-val[data-reg="${regNo}"]`);
            const slider = document.querySelector(`.test-slider[data-reg="${regNo}"]`);
            if (!hidden || !btn) return;

            const isAbs = hidden.value === '1';
            const newAbs = !isAbs;
            hidden.value = newAbs ? '1' : '0';

            if (newAbs) {
                btn.className = 'test-abs-pill px-2.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-rose-500/20 text-rose-400 border-rose-500/50';
                btn.innerHTML = `<i class="fa-solid fa-user-slash mr-1"></i> Abs`;
                if (input) { input.value = 0; input.disabled = true; }
                if (slider) { slider.value = 0; slider.disabled = true; }
            } else {
                btn.className = 'test-abs-pill px-2.5 py-1.5 rounded-xl text-xs font-bold border transition-all bg-slate-800 text-slate-300 border-slate-700';
                btn.innerHTML = `<i class="fa-solid fa-user-check mr-1"></i> Pres`;
                if (input) { input.disabled = false; }
                if (slider) { slider.disabled = false; }
            }

            triggerMobileAutosave();
        }

        // Quick Preset implementations - Bulk presets disabled to avoid accidental overwrites
        function quickFillAllWorks(val) {
            console.warn('Bulk work mark entry disabled to protect student marks from accidental overwrite.');
        }

        function quickFillAllRecords(rough, fair) {
            console.warn('Bulk record mark entry disabled to protect student marks from accidental overwrite.');
        }

        function quickFillAllTests(val) {
            console.warn('Bulk test mark entry disabled to protect student marks from accidental overwrite.');
        }

        function quickSetAllAttendance(isPresent) {
            const students = getAttendanceStudents();
            const cacheKey = getAttendanceCacheKey();
            if (!mobileAttendanceCache[cacheKey]) {
                mobileAttendanceCache[cacheKey] = {};
            }
            students.forEach(st => {
                mobileAttendanceCache[cacheKey][st.reg_no] = isPresent;
            });

            renderAttendanceSection(students);
            showToast(isPresent ? 'Marked all as Present' : 'Marked all as Absent');
            triggerMobileAutosave();
        }

        function empty(val) {
            return !val || val === false || val === 0 || val === '0';
        }

        // Toast feedback
        function showToast(msg, isErr = false) {
            const toast = document.getElementById('mobileToast');
            const icon = document.getElementById('toastIcon');
            const text = document.getElementById('toastMsg');
            if (!toast) return;

            text.innerText = msg;
            icon.className = isErr ? 'fa-solid fa-circle-xmark text-rose-400' : 'fa-solid fa-circle-check text-emerald-400';
            toast.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 2500);
        }

        // Debounced Autosave for Mobile
        let mobileAutosaveTimer = null;
        function triggerMobileAutosave() {
            const topBadge = document.getElementById('mobileAutosaveBadge');
            if (topBadge) {
                topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-amber-950/80 text-amber-400 border border-amber-800';
                topBadge.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-[9px]"></i> Saving...`;
            }

            clearTimeout(mobileAutosaveTimer);
            mobileAutosaveTimer = setTimeout(() => {
                saveCurrentMobileSection(true);
            }, 800);
        }

        // Save Current Active Section via AJAX (Silent Autosave)
        async function saveCurrentMobileSection(isSilent = false) {
            const topBadge = document.getElementById('mobileAutosaveBadge');

            if (topBadge) {
                topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-amber-950/80 text-amber-400 border border-amber-800';
                topBadge.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-[9px]"></i> Saving...`;
            }

            if (currentMode === 'attendance') {
                const records = [];
                const hiddenInputs = document.querySelectorAll('.att-val');
                hiddenInputs.forEach(input => {
                    const reg = input.dataset.reg;
                    records.push({ reg_no: reg, is_present: input.value === '1' });
                });

                try {
                    const res = await fetch(`/api/staff/mobile/workshop/${subjectId}/section-attendance`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ 
                            section_name: activeSection,
                            batch_name: activeAttendanceBatch,
                            date: activeAttendanceDate,
                            records: records 
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        const batchLabels = { 'ALL': 'Full Batch', '1': 'Batch 1', '2': 'Batch 2', 'SECTION': activeSection };
                        const bLabel = batchLabels[activeAttendanceBatch] || activeAttendanceBatch;
                        if (!isSilent) showToast(`Roll call saved (${activeAttendanceDate} • ${bLabel})`);
                        if (topBadge) {
                            topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-emerald-950/80 text-emerald-400 border border-emerald-800';
                            topBadge.innerHTML = `<i class="fa-solid fa-check text-[9px]"></i> Saved`;
                            setTimeout(() => {
                                topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-emerald-950/80 text-emerald-400 border border-emerald-800';
                                topBadge.innerHTML = `<i class="fa-solid fa-cloud-arrow-up text-[9px]"></i> Autosave`;
                            }, 2000);
                        }
                    } else {
                        if (!isSilent) showToast(data.message || 'Error saving attendance', true);
                        if (topBadge) {
                            topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-rose-950/80 text-rose-400 border border-rose-800';
                            topBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Error`;
                        }
                    }
                } catch (e) {
                    if (!isSilent) showToast('Connection error', true);
                    if (topBadge) {
                        topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-rose-950/80 text-rose-400 border border-rose-800';
                        topBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Offline`;
                    }
                }
                return;
            }

            // Works, Records or Tests
            const evaluations = [];
            document.querySelectorAll('.student-card').forEach(card => {
                const reg = card.dataset.reg;
                if (!reg) return;

                if (currentMode === 'works') {
                    evaluations.push({
                        reg_no: reg,
                        work_1: parseFloat(card.querySelector('.w1-val')?.value || 0),
                        work_2: parseFloat(card.querySelector('.w2-val')?.value || 0),
                        work_3: card.querySelector('.w3-val') ? parseFloat(card.querySelector('.w3-val')?.value || 0) : null,
                        work_4: card.querySelector('.w4-val') ? parseFloat(card.querySelector('.w4-val')?.value || 0) : null,
                        alum_work: card.querySelector('.alum-val') ? parseFloat(card.querySelector('.alum-val')?.value || 0) : null
                    });
                } else if (currentMode === 'records') {
                    evaluations.push({
                        reg_no: reg,
                        rough: parseFloat(card.querySelector('.rough-val')?.value || 0),
                        fair: parseFloat(card.querySelector('.fair-val')?.value || 0),
                        alum_rough: card.querySelector('.alum-rough-val') ? parseFloat(card.querySelector('.alum-rough-val')?.value || 0) : null,
                        alum_fair: card.querySelector('.alum-fair-val') ? parseFloat(card.querySelector('.alum-fair-val')?.value || 0) : null
                    });
                } else if (currentMode === 'tests') {
                    const isAbs = card.querySelector('.test-abs-val')?.value === '1';
                    evaluations.push({
                        reg_no: reg,
                        test_mark: parseFloat(card.querySelector('.test-val')?.value || 0),
                        is_absent: isAbs
                    });
                }
            });

            try {
                const res = await fetch(`/api/staff/mobile/workshop/${subjectId}/quick-save`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ section_name: activeSection, mode: currentMode, evaluations: evaluations })
                });
                const data = await res.json();
                if (data.success) {
                    if (!isSilent) showToast(`Saved ${activeSection} marks!`);
                    if (topBadge) {
                        topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-emerald-950/80 text-emerald-400 border border-emerald-800';
                        topBadge.innerHTML = `<i class="fa-solid fa-check text-[9px]"></i> Saved`;
                        setTimeout(() => {
                            topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-emerald-950/80 text-emerald-400 border border-emerald-800';
                            topBadge.innerHTML = `<i class="fa-solid fa-cloud-arrow-up text-[9px]"></i> Autosave`;
                        }, 2000);
                    }
                } else {
                    if (!isSilent) showToast(data.message || 'Error saving marks', true);
                    if (topBadge) {
                        topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-rose-950/80 text-rose-400 border border-rose-800';
                        topBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Error`;
                    }
                }
            } catch (e) {
                if (!isSilent) showToast('Connection error', true);
                if (topBadge) {
                    topBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-rose-950/80 text-rose-400 border border-rose-800';
                    topBadge.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Offline`;
                }
            }
        }

        // Set Report View Mode: Table vs Cards
        function setReportViewMode(mode) {
            reportViewMode = mode;
            const btnTable = document.getElementById('btnReportViewTable');
            const btnCards = document.getElementById('btnReportViewCards');
            if (btnTable && btnCards) {
                if (mode === 'table') {
                    btnTable.className = 'px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 bg-amber-500 text-slate-950 shadow-sm font-black';
                    btnCards.className = 'px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 text-slate-400 hover:text-white';
                } else {
                    btnTable.className = 'px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 text-slate-400 hover:text-white';
                    btnCards.className = 'px-2 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1 bg-amber-500 text-slate-950 shadow-sm font-black';
                }
            }
            filterMobileReportList();
        }

        // Mobile Quick Status Report Modal
        function openMobileReportModal() {
            const modal = document.getElementById('mobileReportModal');
            if (!modal) return;

            // Dynamic aggregate metrics
            let totalCiaSum = 0;
            let passCount = 0;
            let failCount = 0;
            let allWorksDoneCount = 0;
            const totalCount = rawAllStudents.length;

            rawAllStudents.forEach(st => {
                const rNo = st.reg_no;
                const score = rawCalculatedScores[rNo] || {};
                const total = parseFloat(score.total_cia_75 || 0);
                totalCiaSum += total;
                if (total >= 30) {
                    passCount++;
                } else {
                    failCount++;
                }

                // Check 6 continuous works completion
                const alloc = rawAllocations[rNo] || {};
                const bNo = String(alloc.batch_no || score.batch_no || '1');
                const defaultSecs = bNo === '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry'];
                const assignedSecs = (alloc.assigned_sections && alloc.assigned_sections.length > 0)
                    ? alloc.assigned_sections
                    : ((score.assigned_sections && score.assigned_sections.length > 0) ? score.assigned_sections : defaultSecs);

                let evalWorks = 0;
                assignedSecs.forEach(secName => {
                    const wRec = rawWorkEvals[secName]?.[rNo];
                    if (wRec && wRec.work_1_mark !== null && wRec.work_1_mark !== undefined && wRec.work_1_mark !== '') evalWorks++;
                    if (wRec && wRec.work_2_mark !== null && wRec.work_2_mark !== undefined && wRec.work_2_mark !== '') evalWorks++;
                });

                if (evalWorks === 6) {
                    allWorksDoneCount++;
                }
            });

            const avgCia = totalCount > 0 ? (totalCiaSum / totalCount).toFixed(1) : '0.0';

            const compEl = document.getElementById('reportStatCompletedCount');
            const avgEl = document.getElementById('reportStatAvgCia');
            const passEl = document.getElementById('reportStatPassCount');
            const failEl = document.getElementById('reportStatFailCount');

            if (compEl) compEl.innerText = `${allWorksDoneCount} / ${totalCount}`;
            if (avgEl) avgEl.innerText = `${avgCia} / 75`;
            if (passEl) passEl.innerText = `${passCount} / ${totalCount}`;
            if (failEl) failEl.innerText = `${failCount} / ${totalCount}`;

            filterMobileReportList();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeMobileReportModal() {
            const modal = document.getElementById('mobileReportModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function filterMobileReportList() {
            const query = (document.getElementById('reportSearchInput')?.value || '').toLowerCase().trim();
            const batchFilter = document.getElementById('reportBatchFilter')?.value || 'ALL';
            const completionFilter = document.getElementById('reportCompletionFilter')?.value || 'ALL';
            const container = document.getElementById('reportStudentListContainer');
            if (!container) return;

            const filtered = rawAllStudents.filter(st => {
                const rNo = st.reg_no;
                const score = rawCalculatedScores[rNo] || {};
                const alloc = rawAllocations[rNo] || {};
                const bNo = String(alloc.batch_no || score.batch_no || '1');
                if (batchFilter !== 'ALL' && bNo !== batchFilter) return false;

                if (query) {
                    const roll = String(st.roll_no || '').toLowerCase();
                    const name = String(st.name || '').toLowerCase();
                    const reg = String(st.reg_no || '').toLowerCase();
                    const sbte = String(st.sbte_reg_no || '').toLowerCase();
                    if (!roll.includes(query) && !name.includes(query) && !reg.includes(query) && !sbte.includes(query)) {
                        return false;
                    }
                }

                const defaultSecs = bNo === '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry'];
                const assignedSecs = (alloc.assigned_sections && alloc.assigned_sections.length > 0)
                    ? alloc.assigned_sections
                    : ((score.assigned_sections && score.assigned_sections.length > 0) ? score.assigned_sections : defaultSecs);

                let evalWorks = 0;
                let secDone = 0;
                assignedSecs.forEach(secName => {
                    const wRec = rawWorkEvals[secName]?.[rNo];
                    const sRec = rawSectionEvals[secName]?.[rNo];

                    const hasW1 = wRec && wRec.work_1_mark !== null && wRec.work_1_mark !== undefined && wRec.work_1_mark !== '';
                    const hasW2 = wRec && wRec.work_2_mark !== null && wRec.work_2_mark !== undefined && wRec.work_2_mark !== '';
                    if (hasW1) evalWorks++;
                    if (hasW2) evalWorks++;

                    const hasRecord = sRec && (sRec.rough_record_mark !== null && sRec.rough_record_mark !== undefined && sRec.rough_record_mark !== '');
                    const hasTest = sRec && (sRec.test_mark !== null && sRec.test_mark !== undefined && sRec.test_mark !== '' || sRec.is_test_absent == 1);
                    if (hasW1 && hasW2 && hasRecord && hasTest) {
                        secDone++;
                    }
                });

                if (completionFilter === 'ALL_WORKS_DONE' && evalWorks < 6) return false;
                if (completionFilter === 'PENDING_WORKS' && evalWorks >= 6) return false;
                if (completionFilter === '3_DONE' && secDone < assignedSecs.length) return false;

                return true;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="py-10 text-center text-slate-500 text-xs">
                        <i class="fa-solid fa-magnifying-glass text-2xl mb-2 opacity-50 block text-slate-600"></i>
                        No matching students found in status register.
                    </div>
                `;
                return;
            }

            // Prepare student status records
            const studentRecords = filtered.map(st => {
                const rNo = st.reg_no;
                const score = rawCalculatedScores[rNo] || {};
                const alloc = rawAllocations[rNo] || {};
                const total = parseFloat(score.total_cia_75 || 0);
                const w375 = parseFloat(score.work_score_375 || 0).toFixed(1);
                const o75 = parseFloat(score.open_ended_75 || 0).toFixed(1);
                const t15 = parseFloat(score.test_avg_15 || 0).toFixed(1);
                const a15 = parseFloat(score.att_mark_15 || 0).toFixed(1);
                const isPass = total >= 30;
                const bNo = String(alloc.batch_no || score.batch_no || '1');
                const pct = Math.min(100, Math.round((total / 75) * 100));

                const defaultSecs = bNo === '1' ? ['Fitting', 'Smithy', 'Welding'] : ['Machine shop', 'Sheet metal', 'Foundry'];
                const assignedSecs = (alloc.assigned_sections && alloc.assigned_sections.length > 0)
                    ? alloc.assigned_sections
                    : ((score.assigned_sections && score.assigned_sections.length > 0) ? score.assigned_sections : defaultSecs);

                let evalWorksCount = 0;

                const secDetails = assignedSecs.map(secName => {
                    const wRec = rawWorkEvals[secName]?.[rNo];
                    const sRec = rawSectionEvals[secName]?.[rNo];

                    const w1 = wRec && wRec.work_1_mark !== null && wRec.work_1_mark !== undefined && wRec.work_1_mark !== '' ? parseFloat(wRec.work_1_mark) : null;
                    const w2 = wRec && wRec.work_2_mark !== null && wRec.work_2_mark !== undefined && wRec.work_2_mark !== '' ? parseFloat(wRec.work_2_mark) : null;
                    let secWorksDone = 0;
                    if (w1 !== null) { secWorksDone++; evalWorksCount++; }
                    if (w2 !== null) { secWorksDone++; evalWorksCount++; }

                    const wAvg = wRec && wRec.work_avg_20 !== null && wRec.work_avg_20 !== undefined ? parseFloat(wRec.work_avg_20).toFixed(1) : ((w1 !== null || w2 !== null) ? (((w1||0)+(w2||0))/2).toFixed(1) : '-');

                    const rough = sRec && sRec.rough_record_mark !== null && sRec.rough_record_mark !== undefined && sRec.rough_record_mark !== '' ? parseFloat(sRec.rough_record_mark) : null;
                    const fair = sRec && sRec.fair_record_mark !== null && sRec.fair_record_mark !== undefined && sRec.fair_record_mark !== '' ? parseFloat(sRec.fair_record_mark) : null;
                    const hasRecord = rough !== null || fair !== null;
                    const rTotal = sRec && sRec.record_total_20 !== null && sRec.record_total_20 !== undefined ? parseFloat(sRec.record_total_20).toFixed(1) : (hasRecord ? ((rough||0)+(fair||0)).toFixed(1) : '-');

                    const isAbsent = sRec ? (sRec.is_test_absent == 1 || sRec.is_test_absent === true) : false;
                    const testVal = sRec && sRec.test_mark !== null && sRec.test_mark !== undefined && sRec.test_mark !== '' ? parseFloat(sRec.test_mark) : null;
                    const hasTest = testVal !== null || isAbsent;
                    const tDisplay = isAbsent ? 'Abs' : (testVal !== null ? testVal.toFixed(1) : '-');

                    const isDone = secWorksDone === 2 && hasRecord && hasTest;
                    const isPartial = !isDone && (secWorksDone > 0 || hasRecord || hasTest);

                    return {
                        secName,
                        isDone,
                        isPartial,
                        worksCount: secWorksDone,
                        w1,
                        w2,
                        hasRecord,
                        hasTest,
                        wAvg,
                        rTotal,
                        tDisplay
                    };
                });

                const pendingWorksCount = Math.max(0, 6 - evalWorksCount);
                const completedTradesCount = secDetails.filter(s => s.isDone).length;
                const isAll3Done = completedTradesCount >= assignedSecs.length && assignedSecs.length > 0;

                return {
                    st,
                    rNo,
                    bNo,
                    total,
                    w375,
                    o75,
                    t15,
                    a15,
                    isPass,
                    pct,
                    evalWorksCount,
                    pendingWorksCount,
                    completedTradesCount,
                    isAll3Done,
                    secDetails
                };
            });

            // If Table Mode (Default)
            if (reportViewMode === 'table') {
                let tableHtml = `
                <div class="overflow-x-auto -mx-2.5 rounded-xl border border-slate-800 bg-slate-900 shadow-sm">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-950 border-b border-slate-800 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                <th class="p-2.5 sticky left-0 z-20 bg-slate-950 border-r border-slate-800 min-w-[135px]">Student</th>
                                <th class="p-2 text-center min-w-[110px]">Works (6)</th>
                                <th class="p-2 text-center min-w-[170px]">3 Trades Progress</th>
                                <th class="p-2 text-center min-w-[130px]">CIA Breakdown</th>
                                <th class="p-2 text-center min-w-[65px]">Total</th>
                                <th class="p-2 text-center min-w-[55px]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                `;

                studentRecords.forEach(rec => {
                    const st = rec.st;
                    tableHtml += `
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <!-- Student Column (Sticky Left) -->
                        <td class="p-2.5 sticky left-0 z-10 bg-slate-900 border-r border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-slate-800 border border-slate-700 text-cyan-400 font-mono text-[10px] font-bold flex items-center justify-center shrink-0">
                                    #${st.roll_no || '-'}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-[11px] font-bold text-white truncate max-w-[100px]">${st.name}</div>
                                    <div class="text-[9px] text-slate-400 font-mono flex items-center gap-1">
                                        <span class="${rec.bNo === '1' ? 'text-sky-400' : 'text-purple-400'} font-bold">B${rec.bNo}</span>
                                        <span class="text-slate-600">&bull;</span>
                                        <span>${(st.sbte_reg_no || rec.rNo).slice(-4)}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Evaluated vs Pending Works in Single Look -->
                        <td class="p-2 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-black ${
                                    rec.evalWorksCount === 6 
                                        ? 'bg-emerald-950 text-emerald-300 border border-emerald-700' 
                                        : (rec.evalWorksCount > 0 ? 'bg-amber-950 text-amber-300 border border-amber-700' : 'bg-rose-950 text-rose-400 border border-rose-800')
                                }">
                                    ${rec.evalWorksCount} / 6 Done
                                </span>
                                <span class="text-[9px] font-bold mt-0.5 ${rec.pendingWorksCount > 0 ? 'text-rose-400' : 'text-emerald-400'}">
                                    ${rec.pendingWorksCount > 0 ? `${rec.pendingWorksCount} Pending` : '✓ All Done'}
                                </span>
                            </div>
                        </td>

                        <!-- 3 Trades Progress Chips -->
                        <td class="p-2">
                            <div class="flex items-center justify-center gap-1">
                                ${rec.secDetails.map(sec => `
                                    <div class="px-1.5 py-1 rounded-lg border text-center min-w-[50px] ${
                                        sec.isDone 
                                            ? 'bg-emerald-950/40 border-emerald-700/60 text-emerald-300' 
                                            : (sec.isPartial ? 'bg-amber-950/40 border-amber-700/60 text-amber-300' : 'bg-slate-950 border-slate-800 text-slate-500')
                                    }">
                                        <div class="text-[9px] font-bold truncate text-white">${sec.secName.slice(0, 4)}</div>
                                        <div class="text-[8px] font-mono font-bold mt-0.5 ${sec.worksCount === 2 ? 'text-emerald-400' : (sec.worksCount === 1 ? 'text-amber-400' : 'text-slate-500')}">
                                            ${sec.worksCount}/2W
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </td>

                        <!-- CIA Breakdown -->
                        <td class="p-2 text-center">
                            <div class="text-[9px] font-mono leading-tight">
                                <div><span class="text-amber-400 font-bold">W:${rec.w375}</span> &bull; <span class="text-emerald-400 font-bold">R:${rec.o75}</span></div>
                                <div class="mt-0.5"><span class="text-purple-400 font-bold">T:${rec.t15}</span> &bull; <span class="text-cyan-400 font-bold">A:${rec.a15}</span></div>
                            </div>
                        </td>

                        <!-- Total CIA (75M) -->
                        <td class="p-2 text-center font-mono font-black text-xs ${rec.isPass ? 'text-emerald-400' : 'text-rose-400'}">
                            ${rec.total}
                        </td>

                        <!-- Status Badge -->
                        <td class="p-2 text-center">
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ${rec.isPass ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-rose-950 text-rose-400 border border-rose-800'}">
                                ${rec.isPass ? 'Pass' : 'Below'}
                            </span>
                        </td>
                    </tr>
                    `;
                });

                tableHtml += `
                        </tbody>
                    </table>
                </div>
                `;
                container.innerHTML = tableHtml;
                return;
            }

            // Cards Mode
            let cardsHtml = '';
            studentRecords.forEach(rec => {
                const st = rec.st;
                cardsHtml += `
                <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm space-y-2.5">
                    <!-- Top Student Identification & Total Score -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-6 h-6 rounded-lg bg-slate-800 border border-slate-700 text-cyan-400 font-mono text-[11px] font-bold flex items-center justify-center shrink-0">
                                #${st.roll_no || '-'}
                            </span>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-white truncate">${st.name}</div>
                                <div class="text-[10px] text-slate-500 font-mono">SBTE: ${st.sbte_reg_no || rec.rNo} &bull; Batch ${rec.bNo}</div>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-black font-mono ${rec.isPass ? 'text-emerald-400' : 'text-rose-400'}">
                                ${rec.total} <span class="text-[10px] font-normal text-slate-500">/ 75</span>
                            </span>
                            <div>
                                <span class="px-1.5 py-0.2 rounded-md text-[9px] font-black uppercase ${rec.isPass ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-rose-950 text-rose-400 border border-rose-800'}">
                                    ${rec.isPass ? 'Pass' : 'Below 30'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluated vs Pending Works Single-Look Badge Strip -->
                    <div class="p-2 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-300">
                            <i class="fa-solid fa-list-check text-cyan-400"></i>
                            <span>Continuous Works:</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black ${
                                rec.evalWorksCount === 6 
                                    ? 'bg-emerald-950 text-emerald-300 border border-emerald-700' 
                                    : (rec.evalWorksCount > 0 ? 'bg-amber-950 text-amber-300 border border-amber-700' : 'bg-rose-950 text-rose-400 border border-rose-800')
                            }">
                                ${rec.evalWorksCount} / 6 Evaluated
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${rec.pendingWorksCount > 0 ? 'bg-rose-950/80 text-rose-300 border border-rose-800' : 'bg-emerald-950/80 text-emerald-300 border border-emerald-800'}">
                                ${rec.pendingWorksCount > 0 ? `${rec.pendingWorksCount} Pending` : '✓ Complete'}
                            </span>
                        </div>
                    </div>

                    <!-- 3 Required Sections Completion Status Box -->
                    <div class="p-2 rounded-xl bg-slate-950 border border-slate-800/90 space-y-1.5">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-bold text-slate-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-layer-group text-orange-400 text-[10px]"></i>
                                <span>3 Required Trades:</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase ${
                                rec.isAll3Done 
                                    ? 'bg-emerald-950/80 text-emerald-300 border border-emerald-600/70' 
                                    : (rec.completedTradesCount > 0 ? 'bg-amber-950/80 text-amber-300 border border-amber-600/70' : 'bg-rose-950/80 text-rose-400 border border-rose-800')
                            }">
                                ${rec.isAll3Done ? '✓ 3/3 Trades Done' : (rec.completedTradesCount > 0 ? `${rec.completedTradesCount}/3 Completed` : '0/3 Pending')}
                            </span>
                        </div>

                        <!-- 3 Section Chips Grid -->
                        <div class="grid grid-cols-3 gap-1.5">
                            ${rec.secDetails.map(sec => `
                                <div class="p-1.5 rounded-lg border flex flex-col justify-between ${
                                    sec.isDone 
                                        ? 'bg-emerald-950/30 border-emerald-700/50 text-emerald-200' 
                                        : (sec.isPartial ? 'bg-amber-950/30 border-amber-700/50 text-amber-200' : 'bg-slate-900/60 border-slate-800 text-slate-400')
                                }">
                                    <div class="flex items-center justify-between gap-0.5 leading-tight">
                                        <span class="font-bold text-[10px] truncate text-white">${sec.secName}</span>
                                        <span class="text-[8px] font-black shrink-0 ${
                                            sec.isDone ? 'text-emerald-400' : (sec.isPartial ? 'text-amber-400' : 'text-slate-500')
                                        }">
                                            ${sec.isDone ? '✓' : (sec.isPartial ? '◐' : '✕')}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[8px] font-mono mt-1 text-slate-400 border-t border-slate-800/80 pt-0.5">
                                        <span title="Works done" class="${sec.worksCount === 2 ? 'text-emerald-300 font-bold' : (sec.worksCount === 1 ? 'text-amber-300 font-bold' : '')}">${sec.worksCount}/2W</span>
                                        <span title="Record mark" class="${sec.hasRecord ? 'text-emerald-300 font-bold' : ''}">R:${sec.rTotal}</span>
                                        <span title="Test mark" class="${sec.hasTest ? 'text-purple-300 font-bold' : ''}">T:${sec.tDisplay}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <!-- Mini Progress Bar -->
                    <div class="w-full bg-slate-950 h-1.5 rounded-full overflow-hidden border border-slate-800/80">
                        <div class="h-full rounded-full ${rec.isPass ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-rose-500 to-amber-500'}" style="width: ${rec.pct}%"></div>
                    </div>

                    <!-- Components Breakdown Chips -->
                    <div class="grid grid-cols-4 gap-1 text-center pt-0.5">
                        <div class="p-1 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-[8px] uppercase font-bold text-slate-500 block truncate">Works (37.5)</span>
                            <span class="text-[11px] font-mono font-extrabold text-amber-400">${rec.w375}</span>
                        </div>
                        <div class="p-1 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-[8px] uppercase font-bold text-slate-500 block truncate">Record (7.5)</span>
                            <span class="text-[11px] font-mono font-extrabold text-emerald-400">${rec.o75}</span>
                        </div>
                        <div class="p-1 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-[8px] uppercase font-bold text-slate-500 block truncate">Test (15)</span>
                            <span class="text-[11px] font-mono font-extrabold text-purple-400">${rec.t15}</span>
                        </div>
                        <div class="p-1 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-[8px] uppercase font-bold text-slate-500 block truncate">Att (15)</span>
                            <span class="text-[11px] font-mono font-extrabold text-cyan-400">${rec.a15}</span>
                        </div>
                    </div>
                </div>
                `;
            });

            container.innerHTML = cardsHtml;
        }
    </script>
</body>
</html>
