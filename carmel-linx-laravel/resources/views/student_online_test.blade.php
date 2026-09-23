<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->test_name ?? 'Online MCQ Examination' }} - Carmel Linx</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Tailwind CSS (v4 Play CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .font-mono {
            font-family: "JetBrains Mono", monospace !important;
        }
        .noselect {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.4);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.3);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.6);
        }
        @keyframes pulse-fast {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .animate-pulse-fast {
            animation: pulse-fast 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans antialiased bg-slate-950 text-slate-100 selection:bg-purple-500/30 selection:text-purple-200">

    <!-- Sticky Exam Header -->
    <header class="border-b border-slate-800/80 bg-slate-950/90 backdrop-blur-md sticky top-0 z-40 px-3 sm:px-6 py-3">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center shadow-md shadow-purple-500/20 flex-shrink-0">
                    <span class="material-symbols-rounded text-white text-lg">quiz</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-xs font-black text-white tracking-tight uppercase">{{ $test->subject_code }}</span>
                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-purple-900/40 border border-purple-500/30 text-purple-300">MCQ CBT</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-semibold truncate">{{ $subjectName ?? $test->test_name }}</p>
                </div>
            </div>

            <!-- Header Action & Timer -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <!-- Live Timer (Only visible during active test) -->
                <div id="headerTimerPill" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-950/50 border border-purple-500/30 text-purple-300 font-mono font-bold text-xs shadow-inner">
                    <span class="material-symbols-rounded text-sm text-purple-400">timer</span>
                    <span id="headerTimerDisplay">--:--</span>
                </div>

                <button type="button" onclick="handleExitExam();" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-slate-300 rounded-xl text-xs font-bold border border-slate-800 transition-all flex items-center gap-1 cursor-pointer">
                    <span class="material-symbols-rounded text-sm">logout</span>
                    <span class="hidden sm:inline">Exit</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Viewport -->
    <main class="flex-1 max-w-4xl w-full mx-auto p-3 sm:p-6 flex flex-col justify-start">

        <!-- 1. PRE-EXAM BRIEFING SCREEN -->
        <section id="preExamSection" class="space-y-4 sm:space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-5 sm:p-8 shadow-2xl relative overflow-hidden backdrop-blur-sm">
                <div class="absolute top-0 right-0 h-48 w-48 bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Test Header Banner -->
                <div class="mb-5 sm:mb-6">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                        <i class="fa-solid fa-laptop-code text-[11px]"></i> Examination Portal
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight leading-snug">
                        {{ $test->test_name }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">
                        {{ $subjectName }} &bull; Department of {{ $student->branch ?? 'Engineering' }}
                    </p>
                </div>

                <!-- Metadata Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mb-6">
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-3 sm:p-4 text-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Questions</span>
                        <span class="text-base sm:text-lg font-black text-white">{{ $test->mcq_count ?? 10 }}</span>
                    </div>
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-3 sm:p-4 text-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Duration</span>
                        <span class="text-base sm:text-lg font-black text-purple-400">{{ $test->duration ?? 30 }} Mins</span>
                    </div>
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-3 sm:p-4 text-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Pass Mark</span>
                        <span class="text-base sm:text-lg font-black text-emerald-400">{{ $test->pass_threshold ?? 40 }}%</span>
                    </div>
                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-3 sm:p-4 text-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Attempts</span>
                        <span class="text-base sm:text-lg font-black text-amber-400">{{ $attemptsCount }} / {{ $test->max_attempts ?? 1 }}</span>
                    </div>
                </div>

                <!-- Student Identity Card -->
                <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-3.5 sm:p-4 mb-6 text-xs sm:text-sm">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Candidate Name</span>
                            <span class="text-white font-extrabold">{{ $student->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Reg No / Roll</span>
                            <span class="text-purple-300 font-bold font-mono">{{ $student->reg_no }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Class / Semester</span>
                            <span class="text-slate-300 font-bold">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                        </div>
                    </div>
                </div>

                <!-- Instructions Section -->
                <div class="space-y-2.5 mb-6 text-xs sm:text-sm text-slate-300">
                    <h4 class="font-extrabold text-white text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-clipboard-list text-purple-400"></i> Rules & Examination Instructions
                    </h4>
                    <ul class="space-y-2 pl-1">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-purple-400 mt-1 text-[10px]"></i>
                            <span>The countdown timer will start immediately once you tap <strong>"Start Examination"</strong>.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-purple-400 mt-1 text-[10px]"></i>
                            <span>Select one correct option for each question. You can change your choice anytime before submission.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-purple-400 mt-1 text-[10px]"></i>
                            <span>Do not close or reload this window during the test. When time expires, all marked answers are auto-submitted.</span>
                        </li>
                    </ul>
                </div>

                <!-- Launch CTA -->
                <button type="button" id="btnBeginTest" onclick="startExamination();" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-black text-sm sm:text-base tracking-wide shadow-xl shadow-purple-600/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-play"></i>
                    <span>Start Examination Now</span>
                </button>
            </div>
        </section>

        <!-- 2. ACTIVE EXAM EXECUTION SCREEN -->
        <section id="activeExamSection" class="hidden space-y-4 noselect">

            <!-- Question Progress & Quick Navigation Bar -->
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-3 sm:p-4 flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold text-slate-300">Question <span id="currentQNumText" class="text-white font-extrabold">1</span> of <span id="totalQNumText">10</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="activeCoTagBadge" class="px-2 py-0.5 rounded bg-purple-900/40 border border-purple-500/30 text-purple-300 font-bold text-[10px]">CO1</span>
                    <span id="answeredCountBadge" class="text-slate-400 font-mono text-[11px]">0 Answered</span>
                </div>
            </div>

            <!-- Active Question Card -->
            <div class="bg-slate-900/50 border border-slate-800/90 rounded-3xl p-5 sm:p-7 shadow-2xl relative">
                <!-- Question Statement -->
                <div class="flex items-start gap-3 mb-5 sm:mb-6">
                    <div id="qBadgeNum" class="w-8 h-8 rounded-xl bg-purple-600/20 border border-purple-500/30 text-purple-300 flex items-center justify-center font-black text-sm flex-shrink-0 mt-0.5">
                        1
                    </div>
                    <h3 id="activeQuestionText" class="text-sm sm:text-base font-extrabold text-white leading-relaxed">
                        Loading question statement...
                    </h3>
                </div>

                <!-- Dynamic Radio Options -->
                <div id="activeOptionsList" class="space-y-2.5 sm:space-y-3">
                    <!-- Option items rendered by JS -->
                </div>

                <!-- Step Navigation Controls -->
                <div class="flex items-center justify-between gap-3 mt-6 pt-5 border-t border-slate-800/80">
                    <button type="button" id="btnPrevQ" onclick="stepQuestion(-1);" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-slate-300 rounded-xl text-xs font-bold border border-slate-800 transition-all flex items-center gap-1.5 disabled:opacity-30 disabled:pointer-events-none cursor-pointer">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i> Previous
                    </button>

                    <button type="button" id="btnNextQ" onclick="stepQuestion(1);" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- Question Number Grid Tracker -->
            <div class="bg-slate-900/30 border border-slate-800/60 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Question Quick Tracker</span>
                    <div class="flex items-center gap-3 text-[10px] font-semibold text-slate-400">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-700"></span> Pending</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-600"></span> Answered</span>
                    </div>
                </div>
                <div id="questionPillGrid" class="flex flex-wrap gap-2">
                    <!-- Numbers 1..N rendered by JS -->
                </div>
            </div>

            <!-- Submit Examination Button -->
            <div class="pt-2">
                <button type="button" onclick="confirmSubmitExam();" class="w-full py-4 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm tracking-wide shadow-xl shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Submit & Finish Examination</span>
                </button>
            </div>
        </section>

        <!-- 3. POST-EXAM RESULTS SCREEN -->
        <section id="resultsSection" class="hidden space-y-5">
            <div class="bg-slate-900/50 border border-slate-800/80 rounded-3xl p-6 sm:p-8 text-center shadow-2xl relative overflow-hidden backdrop-blur-md">
                <div class="absolute top-0 right-0 h-40 w-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/10">
                    <i class="fa-solid fa-trophy text-2xl"></i>
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight mb-1">
                    Examination Completed!
                </h2>
                <p class="text-xs text-slate-400 font-medium mb-6">
                    Your responses have been evaluated and synced to your academic record.
                </p>

                <!-- Circular Score Pill -->
                <div class="inline-flex flex-col items-center justify-center p-6 rounded-3xl bg-slate-950 border border-slate-800/80 mb-6 shadow-inner w-48 sm:w-56">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Score Obtained</span>
                    <div class="flex items-baseline gap-1">
                        <span id="finalScoreObtained" class="text-4xl sm:text-5xl font-black text-emerald-400">0</span>
                        <span id="finalScoreTotal" class="text-slate-500 font-bold text-sm">/ 10</span>
                    </div>
                    <div class="mt-2">
                        <span id="finalPercentageBadge" class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-black font-mono">
                            0%
                        </span>
                    </div>
                </div>

                <!-- Feedback Message -->
                <div id="resultFeedbackBox" class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800/60 text-xs sm:text-sm text-slate-300 font-medium mb-6 max-w-md mx-auto">
                    Marks recorded successfully.
                </div>

                <!-- Answer Key Review Accordion / List (When permitted) -->
                <div id="answerReviewContainer" class="text-left hidden mb-6 space-y-3">
                    <h4 class="font-extrabold text-white text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check text-purple-400"></i> Detailed Question Review
                    </h4>
                    <div id="reviewItemsList" class="space-y-2.5">
                        <!-- Questions with review badges rendered here -->
                    </div>
                </div>

                <!-- Back to Dashboard -->
                <a href="/dashboard/student" class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-sm border border-slate-800 shadow-md transition-all">
                    <i class="fa-solid fa-house text-xs"></i> Return to Student Dashboard
                </a>
            </div>
        </section>

    </main>

    <!-- Confirmation Modal -->
    <div id="submitConfirmModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <h3 class="text-lg font-black text-white mb-2">Ready to Submit?</h3>
            <p id="submitConfirmMsg" class="text-xs text-slate-400 font-medium leading-relaxed mb-6">
                You have answered 0 of 10 questions. Once submitted, you cannot change your answers.
            </p>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeSubmitConfirm();" class="py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
                    Keep Reviewing
                </button>
                <button type="button" onclick="executeSubmitExam();" class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-md shadow-emerald-600/20">
                    Confirm & Submit
                </button>
            </div>
        </div>
    </div>

    <!-- Client-Side Examination Logic -->
    <script>
        const TEST_ID = @json($test->test_id);
        const DURATION_DEFAULT = {{ $test->duration ?? 30 }};
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        let examQuestions = [];
        let studentAnswers = {}; // { 0: 'option text', 1: 'option text' }
        let currentQIndex = 0;
        let timerInterval = null;
        let timeRemainingSeconds = 0;
        let isExamActive = false;

        async function startExamination() {
            const btn = document.getElementById('btnBeginTest');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Initializing Environment...`;

            try {
                const response = await fetch(`/api/student/online-tests/${TEST_ID}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'SUCCESS' && Array.isArray(data.questions) && data.questions.length > 0) {
                    examQuestions = data.questions;
                    const durationMins = data.duration || DURATION_DEFAULT;
                    timeRemainingSeconds = durationMins * 60;

                    // Switch screen
                    document.getElementById('preExamSection').classList.add('hidden');
                    document.getElementById('activeExamSection').classList.remove('hidden');
                    document.getElementById('headerTimerPill').classList.remove('hidden');
                    document.getElementById('headerTimerPill').classList.add('flex');

                    isExamActive = true;
                    currentQIndex = 0;
                    renderActiveQuestion();
                    renderTrackerGrid();
                    startCountdown();
                } else {
                    alert(data.message || 'Unable to launch examination. Please verify attempts or try again.');
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fa-solid fa-play"></i> <span>Start Examination Now</span>`;
                }
            } catch (err) {
                alert('Network connection error: ' + err.message);
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-play"></i> <span>Start Examination Now</span>`;
            }
        }

        function renderActiveQuestion() {
            if (!examQuestions[currentQIndex]) return;
            const q = examQuestions[currentQIndex];

            document.getElementById('currentQNumText').innerText = (currentQIndex + 1);
            document.getElementById('totalQNumText').innerText = examQuestions.length;
            document.getElementById('qBadgeNum').innerText = (currentQIndex + 1);
            document.getElementById('activeQuestionText').innerText = q.q;
            document.getElementById('activeCoTagBadge').innerText = q.co || 'CO';

            // Options container
            const container = document.getElementById('activeOptionsList');
            const selectedOpt = studentAnswers[currentQIndex] || null;

            let html = '';
            (q.options || []).forEach((opt, oIdx) => {
                const isSelected = (selectedOpt !== null && selectedOpt === opt);
                const borderClass = isSelected 
                    ? 'border-purple-500 bg-purple-950/30 text-white shadow-sm shadow-purple-500/10' 
                    : 'border-slate-800 bg-slate-950/60 hover:border-slate-700 hover:bg-slate-900/60 text-slate-200';
                const checkClass = isSelected
                    ? 'border-purple-500 bg-purple-600 text-white'
                    : 'border-slate-700 bg-slate-900 text-transparent';

                html += `
                    <label onclick="selectOption(${currentQIndex}, '${escapeOptionStr(opt)}')" class="p-3.5 sm:p-4 rounded-2xl border ${borderClass} cursor-pointer flex items-center gap-3 transition-all">
                        <div class="w-5 h-5 rounded-full border-2 ${checkClass} flex items-center justify-center flex-shrink-0 transition-all text-[10px]">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold leading-snug select-none">${escapeHtml(opt)}</span>
                    </label>
                `;
            });
            container.innerHTML = html;

            // Nav buttons
            document.getElementById('btnPrevQ').disabled = (currentQIndex === 0);
            const isLast = (currentQIndex === examQuestions.length - 1);
            const nextBtn = document.getElementById('btnNextQ');
            if (isLast) {
                nextBtn.innerHTML = `Finish <i class="fa-solid fa-flag-checkered text-[10px]"></i>`;
            } else {
                nextBtn.innerHTML = `Next <i class="fa-solid fa-chevron-right text-[10px]"></i>`;
            }

            updateTrackerHighlights();
        }

        function escapeOptionStr(str) {
            return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }

        function selectOption(qIdx, opt) {
            studentAnswers[qIdx] = opt;
            renderActiveQuestion();
            updateAnsweredBadge();
        }

        function stepQuestion(delta) {
            const newIdx = currentQIndex + delta;
            if (newIdx >= 0 && newIdx < examQuestions.length) {
                currentQIndex = newIdx;
                renderActiveQuestion();
            } else if (newIdx >= examQuestions.length) {
                confirmSubmitExam();
            }
        }

        function jumpToQuestion(idx) {
            if (idx >= 0 && idx < examQuestions.length) {
                currentQIndex = idx;
                renderActiveQuestion();
            }
        }

        function renderTrackerGrid() {
            const container = document.getElementById('questionPillGrid');
            let html = '';
            for (let i = 0; i < examQuestions.length; i++) {
                html += `
                    <button type="button" id="trackerPill_${i}" onclick="jumpToQuestion(${i})" class="w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center border transition-all cursor-pointer bg-slate-900 border-slate-800 text-slate-400 hover:border-purple-500/50">
                        ${i + 1}
                    </button>
                `;
            }
            container.innerHTML = html;
            updateTrackerHighlights();
            updateAnsweredBadge();
        }

        function updateTrackerHighlights() {
            for (let i = 0; i < examQuestions.length; i++) {
                const pill = document.getElementById(`trackerPill_${i}`);
                if (!pill) continue;

                const isAnswered = studentAnswers.hasOwnProperty(i) && studentAnswers[i] !== null;
                const isCurrent = (i === currentQIndex);

                pill.className = `w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center border transition-all cursor-pointer `;

                if (isCurrent) {
                    pill.className += `ring-2 ring-purple-400 font-black `;
                }

                if (isAnswered) {
                    pill.className += `bg-purple-600 text-white border-purple-500 shadow-sm shadow-purple-600/30`;
                } else {
                    pill.className += `bg-slate-900 text-slate-400 border-slate-800 hover:border-slate-700`;
                }
            }
        }

        function updateAnsweredBadge() {
            const count = Object.keys(studentAnswers).filter(k => studentAnswers[k] !== null).length;
            document.getElementById('answeredCountBadge').innerText = `${count} / ${examQuestions.length} Answered`;
        }

        function startCountdown() {
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeRemainingSeconds--;
                updateTimerDisplay();

                if (timeRemainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    alert("⏰ Time is up! Your examination is now automatically submitting.");
                    executeSubmitExam();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const m = Math.floor(timeRemainingSeconds / 60);
            const s = timeRemainingSeconds % 60;
            const text = `${m < 10 ? '0'+m : m}:${s < 10 ? '0'+s : s}`;

            const display = document.getElementById('headerTimerDisplay');
            if (display) display.innerText = text;

            const pill = document.getElementById('headerTimerPill');
            if (pill) {
                if (timeRemainingSeconds <= 300) { // Under 5 minutes
                    pill.classList.remove('bg-purple-950/50', 'text-purple-300', 'border-purple-500/30');
                    pill.classList.add('bg-rose-950/80', 'text-rose-300', 'border-rose-500/50', 'animate-pulse-fast');
                }
            }
        }

        function confirmSubmitExam() {
            const answeredCount = Object.keys(studentAnswers).filter(k => studentAnswers[k] !== null).length;
            const total = examQuestions.length;
            const unanswered = total - answeredCount;

            let msg = `You have answered ${answeredCount} of ${total} questions.`;
            if (unanswered > 0) {
                msg += ` There are still ${unanswered} unanswered question(s).`;
            }
            msg += ` Once submitted, your examination is complete and cannot be re-opened.`;

            document.getElementById('submitConfirmMsg').innerText = msg;
            document.getElementById('submitConfirmModal').classList.remove('hidden');
            document.getElementById('submitConfirmModal').classList.add('flex');
        }

        function closeSubmitConfirm() {
            document.getElementById('submitConfirmModal').classList.add('hidden');
            document.getElementById('submitConfirmModal').classList.remove('flex');
        }

        async function executeSubmitExam() {
            closeSubmitConfirm();
            clearInterval(timerInterval);
            isExamActive = false;

            // Show submitting state
            document.getElementById('activeExamSection').innerHTML = `
                <div class="py-16 text-center">
                    <div class="w-12 h-12 border-3 border-slate-800 border-t-purple-500 rounded-full animate-spin mx-auto mb-4"></div>
                    <h3 class="text-base font-extrabold text-white">Submitting Responses...</h3>
                    <p class="text-xs text-slate-400 mt-1">Evaluating marks and updating student record.</p>
                </div>
            `;

            try {
                const res = await fetch(`/api/student/online-tests/${TEST_ID}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ answers: studentAnswers })
                });

                const data = await res.json();

                if (data.status === 'SUCCESS' && data.summary) {
                    document.getElementById('activeExamSection').classList.add('hidden');
                    document.getElementById('headerTimerPill').classList.add('hidden');
                    document.getElementById('resultsSection').classList.remove('hidden');

                    const s = data.summary;
                    document.getElementById('finalScoreObtained').innerText = s.score;
                    document.getElementById('finalScoreTotal').innerText = `/ ${s.total}`;
                    document.getElementById('finalPercentageBadge').innerText = `${s.percentage}%`;

                    const feedbackBox = document.getElementById('resultFeedbackBox');
                    if (s.percentage >= {{ $test->pass_threshold ?? 40 }}) {
                        feedbackBox.className = 'p-3.5 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-xs sm:text-sm text-emerald-300 font-medium mb-6 max-w-md mx-auto';
                        feedbackBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> Qualified! Score meets the required passing threshold of {{ $test->pass_threshold ?? 40 }}%.`;
                    } else {
                        feedbackBox.className = 'p-3.5 rounded-2xl bg-amber-950/40 border border-amber-500/30 text-xs sm:text-sm text-amber-300 font-medium mb-6 max-w-md mx-auto';
                        feedbackBox.innerHTML = `<i class="fa-solid fa-circle-info me-1"></i> Examination recorded. Score: ${s.percentage}% (Threshold: {{ $test->pass_threshold ?? 40 }}%).`;
                    }

                    // Render answers if available
                    if (Array.isArray(s.details) && s.details.length > 0) {
                        const reviewContainer = document.getElementById('answerReviewContainer');
                        const reviewList = document.getElementById('reviewItemsList');
                        reviewContainer.classList.remove('hidden');

                        let revHtml = '';
                        s.details.forEach((item, idx) => {
                            const isCorrect = item.is_correct;
                            const borderClass = isCorrect ? 'border-emerald-500/30 bg-emerald-950/20' : 'border-rose-500/30 bg-rose-950/20';
                            const badge = isCorrect 
                                ? `<span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-[10px]"><i class="fa-solid fa-check"></i> Correct</span>`
                                : `<span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-400 font-bold text-[10px]"><i class="fa-solid fa-xmark"></i> Incorrect</span>`;

                            revHtml += `
                                <div class="p-3.5 rounded-2xl border ${borderClass} text-xs">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <span class="font-extrabold text-white">Q${idx + 1}. ${escapeHtml(item.q)}</span>
                                        ${badge}
                                    </div>
                                    <div class="space-y-1 text-slate-300">
                                        <div>Your Answer: <strong class="${isCorrect ? 'text-emerald-400' : 'text-rose-400'}">${escapeHtml(item.student_ans || 'Not Attempted')}</strong></div>
                                        ${!isCorrect && item.correct_ans ? `<div>Correct Answer: <strong class="text-emerald-400">${escapeHtml(item.correct_ans)}</strong></div>` : ''}
                                    </div>
                                </div>
                            `;
                        });
                        reviewList.innerHTML = revHtml;
                    }
                } else {
                    alert(data.message || 'Submission completed with warnings.');
                    window.location.href = '/dashboard/student';
                }
            } catch (err) {
                alert('Submission error: ' + err.message);
                window.location.href = '/dashboard/student';
            }
        }

        function handleExitExam() {
            if (isExamActive) {
                if (confirm("⚠️ You have an active examination in progress. Leaving now will forfeit unfinished questions. Are you sure you want to exit?")) {
                    window.location.href = '/dashboard/student';
                }
            } else {
                window.location.href = '/dashboard/student';
            }
        }
    </script>
</body>
</html>
