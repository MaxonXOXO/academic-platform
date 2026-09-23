<!DOCTYPE html>
<html lang="en" class="bg-slate-950 text-slate-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Files | Carmel Linx</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .transition-premium { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }

        /* NBA Course Files dashboard layout & form element overrides to increase readability */
        body, button, select, input, textarea, table, th, td, div, p, span, a {
            font-size: 14px !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-size: 1.15rem !important;
            font-weight: 800 !important;
        }

        h2#cfSubjectTitle {
            font-size: 1.5rem !important;
            font-weight: 900 !important;
        }

        /* Enlarge sidebar buttons, buttons, labels */
        button, .btn {
            font-size: 14px !important;
            font-weight: bold !important;
        }
        
        /* Master Checklist items */
        .grid-cols-1.gap-4 div {
            font-size: 14px !important;
        }

        /* Let's increase the width and height of the preview modal popup window */
        #cfPreviewModalContent {
            max-width: 95vw !important;
            height: 95vh !important;
        }

        /* Enlarge inputs and text inside modal */
        #cfPreviewModalContent iframe,
        #cfPreviewModalContent input,
        #cfPreviewModalContent select,
        #cfPreviewModalContent button,
        #cfPreviewModalContent table,
        #cfPreviewModalContent td,
        #cfPreviewModalContent th,
        #cfPreviewModalContent div,
        #cfPreviewModalContent p {
            font-size: 14px !important;
        }
    </style>
</head>
<body class="h-screen flex flex-col bg-[#070b13] overflow-hidden text-slate-300">

    <!-- Navbar -->
    <nav class="h-16 border-b border-slate-800/80 bg-slate-900/80 backdrop-blur-xl flex items-center justify-between px-8 z-10 shrink-0 shadow-lg shadow-black/20">
        <div class="flex items-center gap-4">
            <a href="javascript:history.back()" class="flex items-center gap-2 px-4 py-2 bg-amber-500/10 hover:bg-amber-500/25 border border-amber-500/30 hover:border-amber-400 text-amber-400 rounded-xl font-bold transition-premium">
                <span class="material-symbols-rounded text-xl">arrow_back</span>
                <span class="text-sm">Back</span>
            </a>
            <div>
                <h1 class="font-extrabold text-slate-100 tracking-tight flex items-center gap-2 text-xl">
                    <span class="material-symbols-rounded text-amber-500 text-2xl">folder_special</span> NBA Course Files Management Desk
                </h1>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-9 h-9 rounded-full border border-slate-700 object-cover shadow-md">
            <span class="font-bold text-slate-200 hidden sm:block text-sm">{{ session('userName') }}</span>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8 bg-gradient-to-b from-[#070b13] to-[#0d1527]">
        <div class="w-full mx-auto space-y-6 px-2">
            
            <!-- BREADCRUMBS -->
            <div id="cfBreadcrumbs" class="hidden flex items-center gap-2 font-bold text-slate-400 bg-slate-900/85 p-4 rounded-2xl border border-slate-800/80 shadow-md text-sm">
                <button onclick="cfShowLevel(1)" class="hover:text-amber-400 transition-colors flex items-center gap-1"><span class="material-symbols-rounded text-lg">home</span> All Batches</button>
                <span id="cfCrumbBatch" class="hidden items-center gap-2"><span class="material-symbols-rounded text-lg text-slate-600">chevron_right</span> <button onclick="cfShowLevel(2)" class="hover:text-amber-400 transition-colors bg-slate-800/50 px-3 py-1 rounded-lg" id="cfCrumbBatchText">2024</button></span>
                <span id="cfCrumbSem" class="hidden items-center gap-2"><span class="material-symbols-rounded text-lg text-slate-600">chevron_right</span> <button onclick="cfShowLevel(3)" class="hover:text-amber-400 transition-colors bg-slate-800/50 px-3 py-1 rounded-lg" id="cfCrumbSemText">Sem 1</button></span>
                <span id="cfCrumbCourse" class="hidden items-center gap-2"><span class="material-symbols-rounded text-lg text-slate-600">chevron_right</span> <span class="text-amber-400 bg-amber-500/10 px-3 py-1 rounded-lg border border-amber-500/20" id="cfCrumbCourseText">Subject</span></span>
            </div>

            <!-- LEVEL 1: BATCH SELECTION -->
            <div id="cfLevel1" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in">
                <div class="col-span-full py-20 text-center text-slate-500 font-bold animate-pulse text-sm">Loading assigned courses...</div>
            </div>

            <!-- LEVEL 2: SEMESTER SELECTION -->
            <div id="cfLevel2" class="hidden grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 animate-fade-in"></div>

            <!-- LEVEL 3: COURSE SELECTION -->
            <div id="cfLevel3" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in"></div>

            <!-- LEVEL 4: COURSE FILE BUILDER -->
            <div id="cfLevel4" class="hidden space-y-6 animate-fade-in">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-900/90 border border-slate-850 rounded-xl p-4 shadow-lg backdrop-blur-md">
                    <div>
                        <h2 id="cfSubjectTitle" class="font-black text-white mb-0.5 text-lg tracking-tight">Subject Name</h2>
                        <p id="cfBatchInfo" class="text-slate-400 font-medium text-sm">Batch Info</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span id="cfStatusBadge" class="px-3 py-1 rounded-lg border font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20 text-sm">DRAFT</span>
                        <button type="button" onclick="openAttainmentSettings()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-3.5 py-2 rounded-lg text-sm font-bold transition-premium flex items-center gap-2 border border-slate-700"><span class="material-symbols-rounded text-sm">tune</span> Attainment Targets</button>
                        <button type="button" onclick="saveCourseFileProgress()" class="bg-indigo-650 hover:bg-indigo-650/80 text-white px-4 py-2 rounded-lg text-sm font-bold transition-premium shadow-lg shadow-indigo-500/10">Save Draft</button>
                        <button type="button" onclick="generateCourseFilePDF()" class="bg-amber-500 hover:bg-amber-450 text-slate-950 px-4 py-2 rounded-lg text-sm font-black transition-premium flex items-center gap-2 shadow-lg shadow-amber-500/10"><span class="material-symbols-rounded text-sm">picture_as_pdf</span> Generate PDF</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Sidebar Tabs -->
                    <div class="lg:col-span-3 space-y-2">
                        <button type="button" onclick="switchCfTab('A')" id="tabBtnA" class="w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium bg-amber-500/10 text-amber-400 border-l-4 border-amber-500 shadow-sm">Section A: Planning</button>
                        <button type="button" onclick="switchCfTab('B')" id="tabBtnB" class="w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800/80 hover:text-white border-l-4 border-transparent">Section B: Materials</button>
                        <button type="button" onclick="switchCfTab('C')" id="tabBtnC" class="w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800/80 hover:text-white border-l-4 border-transparent">Section C: Assessments</button>
                        <button type="button" onclick="switchCfTab('D')" id="tabBtnD" class="w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800/80 hover:text-white border-l-4 border-transparent">Section D: Attainment</button>
                        <button type="button" onclick="switchCfTab('Checklist')" id="tabBtnChecklist" class="w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800/80 hover:text-white border-l-4 border-transparent mt-4 bg-slate-800/40">Master Checklist</button>
                    </div>

                    <!-- Form Content -->
                    <div class="lg:col-span-9 bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-lg min-h-[450px]">
                        <form id="cfForm">
                            <!-- Section A -->
                            <div id="cfTabA" class="space-y-6 animate-fade-in">
                                <div class="bg-slate-950/60 border border-slate-800/60 p-5 rounded-xl flex items-start gap-4 shadow-sm">
                                    <div class="bg-blue-500/10 text-blue-400 p-2 rounded-lg shrink-0 border border-blue-500/20"><span class="material-symbols-rounded">sync</span></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-200 mb-1">Auto-Pulled Data Included</h4>
                                        <p class="text-sm text-slate-400 leading-relaxed">Syllabus, Course Outcomes (COs), and your detailed Lesson Plans will be automatically injected into the final PDF. You do not need to re-enter them here.</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Gaps Identified (if any)</label>
                                        <textarea name="gaps_identified" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600" placeholder="E.g., Students lacked prerequisite knowledge in..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Bridge Topics Delivered</label>
                                        <textarea name="bridge_topics" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600" placeholder="E.g., Conducted 2 extra hours on basics of..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Section B -->
                            <div id="cfTabB" class="hidden space-y-6 animate-fade-in">
                                <div>
                                    <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">NPTEL / Swayam Links</label>
                                    <textarea name="nptel_swayam_links" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600" placeholder="Paste external course URLs..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Other Reference Materials / Handouts</label>
                                    <textarea name="other_resources" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600" placeholder="List textbooks, custom lab manuals, etc..."></textarea>
                                </div>
                            </div>

                            <!-- Section C -->
                            <div id="cfTabC" class="hidden space-y-6 animate-fade-in">
                                <div class="bg-slate-950/60 border border-slate-800/60 p-5 rounded-xl flex items-start gap-4 shadow-sm">
                                    <div class="bg-emerald-500/10 text-emerald-400 p-2 rounded-lg shrink-0 border border-emerald-500/20"><span class="material-symbols-rounded">grading</span></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-200 mb-1">Assessments Auto-Linked</h4>
                                        <p class="text-sm text-slate-400 leading-relaxed">Test Questions, Assignment Questions, and the final Student Mark Sheets will be attached automatically.</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Evaluation Scheme</label>
                                    <textarea name="evaluation_scheme" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600" placeholder="e.g., Internals 40%, End Sem 60%..."></textarea>
                                </div>
                            </div>

                            <!-- Section D -->
                            <div id="cfTabD" class="hidden space-y-6 animate-fade-in">
                                <div>
                                    <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Action Taken Report (if COs not fully attained)</label>
                                    <textarea name="action_taken_report" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-350 uppercase tracking-wider mb-2">Course Committee Minutes (Summary)</label>
                                    <textarea name="committee_minutes" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-premium placeholder-slate-600"></textarea>
                                </div>
                            </div>
                            
                            <!-- Master Checklist -->
                            <div id="cfTabChecklist" class="hidden space-y-6 animate-fade-in">
                                <div class="bg-indigo-500/10 border border-indigo-500/20 p-5 rounded-xl flex items-start gap-4 mb-4 shadow-sm">
                                    <div class="bg-indigo-500/20 text-indigo-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded">checklist</span></div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-200 mb-1">File Integrity Checklist</h4>
                                        <p class="text-sm text-slate-400 leading-relaxed">Verify each document before final PDF generation. You can check off items as you complete them.</p>
                                    </div>
                                </div>
                                <div class="overflow-x-auto rounded-xl border border-slate-800/80">
                                    <table class="w-full text-left text-sm text-slate-300">
                                        <thead class="text-sm text-slate-450 bg-slate-950 uppercase border-b border-slate-850">
                                            <tr>
                                                <th class="px-4 py-3 w-16 text-center">Doc No.</th>
                                                <th class="px-4 py-3">Document Name</th>
                                                <th class="px-4 py-3 w-48 text-center">Action</th>
                                                <th class="px-4 py-3 w-24 text-center">Verified</th>
                                                <th class="px-4 py-3 w-48">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cfChecklistBody" class="divide-y divide-slate-800/40">
                                            <!-- Dynamically populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Attainment Settings Modal -->
    <div id="attainmentModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-md shadow-2xl shadow-black overflow-hidden transform scale-95 transition-transform duration-300" id="attainmentModalContent">
            <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-800/50">
                <h3 class="text-sm font-black text-white flex items-center gap-2"><span class="material-symbols-rounded text-amber-500">tune</span> Attainment Targets</h3>
                <button type="button" onclick="closeAttainmentSettings()" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-700 transition-colors"><span class="material-symbols-rounded">close</span></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-400 mb-1">Target Mark Threshold (%)</label>
                    <div class="relative">
                        <input type="number" id="targetMarkPercent" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white font-bold focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="60">
                        <span class="absolute right-4 top-2.5 text-slate-500 font-bold">%</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Students must score >= this % to attain the CO.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">Level 3 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level3Percent" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white font-bold focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="70">
                        <span class="absolute right-4 top-2.5 text-slate-500 font-bold">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">Level 2 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level2Percent" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white font-bold focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="60">
                        <span class="absolute right-4 top-2.5 text-slate-500 font-bold">%</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 mb-1">Level 1 Batch Target (%)</label>
                    <div class="relative">
                        <input type="number" id="level1Percent" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white font-bold focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none" placeholder="50">
                        <span class="absolute right-4 top-2.5 text-slate-500 font-bold">%</span>
                    </div>
                </div>
                
                <div class="bg-slate-800/50 border border-slate-700/50 p-3 rounded-xl mt-4">
                    <p class="text-[10px] text-slate-400 leading-relaxed font-medium">
                        <strong class="text-amber-500">How this works:</strong> These levels evaluate the <em>entire class</em>. 
                        If you set Level 3 to <strong class="text-slate-300">70%</strong>, it means your batch achieves the maximum Level 3 rating 
                        only if <strong class="text-slate-300">70% or more</strong> of the students score above your Target Mark.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-800 flex justify-end gap-3 bg-slate-900">
                <button type="button" onclick="closeAttainmentSettings()" class="px-5 py-2.5 rounded-xl font-bold text-[10px] text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">Cancel</button>
                <button type="button" id="btnSaveAttainment" onclick="saveAttainmentSettings()" class="bg-amber-500 hover:bg-amber-400 text-slate-950 px-5 py-2.5 rounded-xl text-[10px] font-black transition-premium shadow-lg shadow-amber-500/20 flex items-center gap-2">Save Targets</button>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="cfPreviewModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl shadow-black overflow-hidden transform scale-95 transition-transform duration-300" id="cfPreviewModalContent">
            <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-800/50">
                <h3 id="cfPreviewTitle" class="text-xs font-black text-slate-100">Document Preview</h3>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-700 transition-colors"><span class="material-symbols-rounded">close</span></button>
            </div>
            <div class="flex-1 p-6 overflow-y-auto bg-slate-950/50" id="cfPreviewBody">
                <div class="w-full h-full flex items-center justify-center text-slate-500 font-bold animate-pulse">Loading preview...</div>
            </div>
            <div class="px-6 py-4 border-t border-slate-800 flex justify-end gap-3 bg-slate-900">
                <button type="button" onclick="printPreviewDoc()" class="text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 px-5 py-2.5 rounded-xl text-[10px] font-bold transition-premium flex items-center gap-2"><span class="material-symbols-rounded text-[18px]">print</span> Print Document</button>
                <button type="button" onclick="closePreviewModal()" class="px-5 py-2.5 rounded-xl font-bold text-[10px] text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">Close</button>
                <button type="button" id="btnVerifyDoc" onclick="verifyCurrentDoc()" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-5 py-2.5 rounded-xl text-[10px] font-black transition-premium shadow-lg shadow-emerald-500/20 flex items-center gap-2"><span class="material-symbols-rounded text-[18px]">verified</span> Verify & Approve</button>
            </div>
        </div>
    </div>

    <!-- Global Toast -->
    <div id="globalToast" class="fixed bottom-6 right-6 px-6 py-4 rounded-2xl font-bold text-[10px] shadow-2xl transition-all duration-300 translate-y-24 opacity-0 z-50 flex items-center gap-3"></div>

    <script>
        function showGlobalMessage(msg, isError=false) {
            const toast = document.getElementById('globalToast');
            toast.innerHTML = isError 
                ? `<span class="material-symbols-rounded">error</span> ${msg}`
                : `<span class="material-symbols-rounded">check_circle</span> ${msg}`;
            toast.className = `fixed bottom-6 right-6 px-6 py-4 rounded-2xl font-bold text-[10px] shadow-2xl transition-all duration-300 z-50 flex items-center gap-3 ${isError ? 'bg-red-500 text-white shadow-red-500/30' : 'bg-emerald-500 text-white shadow-emerald-500/30'}`;
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.transform = 'translateY(24px)';
                toast.style.opacity = '0';
            }, 3000);
        }

        // --- COURSE FILES LOGIC ---
        let cfDataTree = [];
        let currentCfId = null;
        let selectedBatch = null;
        let selectedSemester = null;

        document.addEventListener("DOMContentLoaded", () => {
            loadCourseFiles();
        });

        function loadCourseFiles() {
            cfShowLevel(1);
            fetch("/api/course-files/subjects")
                .then(res => res.json())
                .then(data => {
                    if (data.status === "SUCCESS") {
                        cfDataTree = data.batches;
                        renderCfLevel1();
                    } else {
                        document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-red-400 font-bold text-[10px]">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById("cfLevel1").innerHTML = `<div class="col-span-full py-12 text-center text-red-400 font-bold text-[10px]">Error loading data.</div>`;
                });
        }

        function renderCfLevel1() {
            const grid = document.getElementById("cfLevel1");
            if (cfDataTree.length === 0) {
                grid.innerHTML = '<div class="col-span-full py-20 text-center text-slate-500 font-bold text-sm bg-slate-900 border border-slate-800 rounded-xl">No assigned courses found.</div>';
                return;
            }
            grid.innerHTML = cfDataTree.map((b, idx) => `
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 hover:border-amber-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-amber-500/5" onclick="cfSelectBatch(${idx})">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400/20 to-orange-500/10 flex items-center justify-center border border-amber-500/20">
                            <span class="material-symbols-rounded text-amber-500 text-sm">folder_open</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-black text-slate-100 leading-tight mb-1 group-hover:text-amber-400 transition-colors">${b.batch_year} Admission</h4>
                    <p class="text-sm text-slate-450 font-bold uppercase tracking-wider">${b.branch_full_name || b.branch}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-800/60 pt-3">
                        <span class="text-sm font-bold text-slate-500">${b.semesters.length} Semesters</span>
                        <div class="text-sm text-amber-500 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
        }

        function cfSelectBatch(idx) {
            selectedBatch = cfDataTree[idx];
            document.getElementById("cfCrumbBatchText").innerText = `${selectedBatch.batch_year} (${selectedBatch.branch_full_name || selectedBatch.branch})`;
            
            const grid = document.getElementById("cfLevel2");
            grid.innerHTML = selectedBatch.semesters.map((s, sIdx) => `
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 hover:border-indigo-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-indigo-500/5" onclick="cfSelectSemester(${sIdx})">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/20 to-blue-500/10 flex items-center justify-center border border-indigo-500/20">
                            <span class="material-symbols-rounded text-indigo-400 text-sm">calendar_month</span>
                        </div>
                    </div>
                    <h4 class="text-sm font-black text-slate-100 leading-tight mb-1 group-hover:text-indigo-400 transition-colors">Semester ${s.semester}</h4>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-800/60 pt-3">
                        <span class="px-2 py-0.5 rounded-md bg-slate-950 border border-slate-805 text-sm font-bold text-slate-400">${s.courses.length} Courses</span>
                        <div class="text-sm text-indigo-400 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            View <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
            cfShowLevel(2);
        }

        function cfSelectSemester(sIdx) {
            selectedSemester = selectedBatch.semesters[sIdx];
            document.getElementById("cfCrumbSemText").innerText = `Semester ${selectedSemester.semester}`;
            
            const grid = document.getElementById("cfLevel3");
            grid.innerHTML = selectedSemester.courses.map(c => `
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 hover:border-emerald-500/50 hover:bg-slate-800/80 transition-all cursor-pointer group shadow-sm hover:shadow-emerald-500/5" onclick="cfHandleOpen('${c.course_file_id}', '${c.batch_subject_id}', '${c.subject_name.replace(/'/g, "\\'")}', '${c.revision}')">
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 flex items-center justify-center border border-emerald-500/20">
                            <span class="material-symbols-rounded text-emerald-400 text-sm">school</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded border text-sm font-black uppercase tracking-wider ${c.status === 'Complete' ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-amber-400 bg-amber-500/10 border-amber-500/20'}">${c.status}</span>
                    </div>
                    <h4 class="text-sm font-black text-slate-100 leading-snug mb-1 group-hover:text-emerald-400 transition-colors line-clamp-2">${c.subject_name}</h4>
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider">${c.subject_code}</p>
                    <div class="mt-4 flex items-center justify-end border-t border-slate-800/60 pt-3">
                        <div class="text-sm text-emerald-400 font-bold flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            Open Builder <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            `).join("");
            cfShowLevel(3);
        }

        function cfHandleOpen(courseFileId, subjectId, subjectName, revision) {
            if (revision === 'REV2026') {
                window.open(`/r26/classroom/course-file/${subjectId}`, '_blank');
            } else {
                cfOpenBuilder(courseFileId, subjectName);
            }
        }

        function cfOpenBuilder(id, subjectName) {
            currentCfId = id;
            document.getElementById("cfCrumbCourseText").innerText = subjectName;
            document.getElementById("cfSubjectTitle").innerText = subjectName;
            document.getElementById("cfBatchInfo").innerText = `${selectedBatch.batch_year} Admission • ${selectedBatch.branch_full_name || selectedBatch.branch} • Semester ${selectedSemester.semester}`;
            
            cfShowLevel(4);

            // Load draft data
            fetch(`/api/course-files/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "SUCCESS") {
                        const cf = data.course_file;
                        const statusUpper = cf.status.toUpperCase();
                        const badge = document.getElementById("cfStatusBadge");
                        badge.innerText = statusUpper;
                        if (statusUpper === "COMPLETE") {
                            badge.className = "px-3 py-1 rounded-lg border font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border-emerald-500/20 text-sm";
                        } else {
                            badge.className = "px-3 py-1 rounded-lg border font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20 text-sm";
                        }
                        
                        if (cf.attainment_settings) {
                            try {
                                currentAttainmentSettings = JSON.parse(cf.attainment_settings);
                            } catch(e) {
                                currentAttainmentSettings = null;
                            }
                        } else {
                            currentAttainmentSettings = null;
                        }
                        
                        const form = document.getElementById("cfForm");
                        form.gaps_identified.value = cf.section_a?.gaps_identified || "";
                        form.bridge_topics.value = cf.section_a?.bridge_topics || "";
                        form.nptel_swayam_links.value = cf.section_b?.nptel_swayam_links || "";
                        form.other_resources.value = cf.section_b?.other_resources || "";
                        form.evaluation_scheme.value = cf.section_c?.evaluation_scheme || "";
                        form.action_taken_report.value = cf.section_d?.action_taken_report || "";
                        form.committee_minutes.value = cf.section_d?.committee_minutes || "";
                        renderChecklist(cf.documents || []);
                    }
                });
        }

        function cfShowLevel(lvl) {
            const crumb = document.getElementById("cfBreadcrumbs");
            const l1 = document.getElementById("cfLevel1");
            const l2 = document.getElementById("cfLevel2");
            const l3 = document.getElementById("cfLevel3");
            const l4 = document.getElementById("cfLevel4");
            
            crumb.classList.remove("hidden");
            l1.classList.add("hidden");
            l2.classList.add("hidden");
            l3.classList.add("hidden");
            l4.classList.add("hidden");

            document.getElementById("cfCrumbBatch").classList.add("hidden");
            document.getElementById("cfCrumbSem").classList.add("hidden");
            document.getElementById("cfCrumbCourse").classList.add("hidden");
            document.getElementById("cfCrumbBatch").classList.remove("flex");
            document.getElementById("cfCrumbSem").classList.remove("flex");
            document.getElementById("cfCrumbCourse").classList.remove("flex");

            if (lvl === 1) {
                crumb.classList.add("hidden");
                l1.classList.remove("hidden");
            } else if (lvl === 2) {
                l2.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
            } else if (lvl === 3) {
                l3.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
                document.getElementById("cfCrumbSem").classList.remove("hidden");
                document.getElementById("cfCrumbSem").classList.add("flex");
            } else if (lvl === 4) {
                l4.classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.remove("hidden");
                document.getElementById("cfCrumbBatch").classList.add("flex");
                document.getElementById("cfCrumbSem").classList.remove("hidden");
                document.getElementById("cfCrumbSem").classList.add("flex");
                document.getElementById("cfCrumbCourse").classList.remove("hidden");
                document.getElementById("cfCrumbCourse").classList.add("flex");
            }
        }

        function switchCfTab(tab) {
            ["A", "B", "C", "D", "Checklist"].forEach(t => {
                const btn = document.getElementById("tabBtn" + t);
                const panel = document.getElementById("cfTab" + t);
                if (t === tab) {
                    btn.className = "w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium bg-amber-500/10 text-amber-400 border-l-4 border-amber-500 shadow-sm";
                    panel.classList.remove("hidden");
                } else {
                    btn.className = "w-full text-left px-5 py-3.5 rounded-xl text-sm font-bold transition-premium text-slate-400 hover:bg-slate-800/80 hover:text-white border-l-4 border-transparent";
                    panel.classList.add("hidden");
                }
            });
        }

        function saveCourseFileProgress() {
            if (!currentCfId) return;
            const form = document.getElementById("cfForm");
            
            const payload = {
                section_a: { gaps_identified: form.gaps_identified.value, bridge_topics: form.bridge_topics.value },
                section_b: { nptel_swayam_links: form.nptel_swayam_links.value, other_resources: form.other_resources.value },
                section_c: { evaluation_scheme: form.evaluation_scheme.value },
                section_d: { action_taken_report: form.action_taken_report.value, committee_minutes: form.committee_minutes.value },
                checklist: Array.from(document.querySelectorAll('.cf-check-item')).map(cb => ({
                    document_number: cb.dataset.docNo,
                    document_name: cb.dataset.docName,
                    is_checked: cb.checked,
                    remarks: document.getElementById('remark_' + cb.dataset.docNo).value
                }))
            };

            const btnSave = document.querySelector("button[onclick='saveCourseFileProgress()']");
            const originalText = btnSave.innerHTML;
            btnSave.innerHTML = `<span class="material-symbols-rounded animate-spin align-middle text-sm mr-1">sync</span> Saving...`;
            btnSave.disabled = true;

            fetch(`/api/course-files/${currentCfId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                if (data.status === "SUCCESS") {
                    showGlobalMessage("Draft saved successfully.");
                    // Dynamic badge coloring based on updated status
                    const updatedCf = data.course_file || {};
                    const newStatus = (updatedCf.status || "Draft").toUpperCase();
                    const badge = document.getElementById("cfStatusBadge");
                    badge.innerText = newStatus;
                    if (newStatus === "COMPLETE") {
                        badge.className = "px-3 py-1 rounded-lg border font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border-emerald-500/20 text-sm";
                    } else {
                        badge.className = "px-3 py-1 rounded-lg border font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20 text-sm";
                    }
                } else {
                    showGlobalMessage(data.message || "Failed to save", true);
                }
            })
            .catch(() => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                showGlobalMessage("Network error", true);
            });
        }

        function generateCourseFilePDF() {
            if (!currentCfId) return;
            showGlobalMessage("PDF Generation triggered. Opening in a new tab.", false);
            window.open(`/api/course-files/${currentCfId}/pdf`, "_blank");
        }
    
        const defaultChecklist = [
            'Class Time table (current semester Program timetable)',
            'Faculty Workload',
            'Student List with register numbers',
            'Course Syllabus with Recommended Books (SITTTR)',
            'Course information sheet',
            'Course outcomes',
            'Academic calender',
            'Course Plan',
            'Course log and Attendance from TEAMS',
            'Internal Exam Question Papers CO 1,2,3,4 with mark splitup / Scheme',
            'Internal Examination Result Analysis NBA',
            'Weaker student coaching schedule and proof (if)',
            'Teaching and Learning Methods Proof - handouts, capsule notes etc.',
            'Assignment questions with rubrics',
            'Internal Marks - SBTE (CIA)',
            'Grade Sheet - Proof of CO evaluations',
            'External Exam Question Papers / Question bank',
            'SBTE examination result',
            'Attainment of Course Outcome (CO) Co-Po-PsoO map',
            'Attainment of PO/PSO report',
            'Mid semester survey & report',
            'End semester / Course exit survey & report',
            'Internal Examination sample answer scripts',
            'Assignment sample scripts',
            'Others'
        ];

        
        let currentPreviewDocNo = null;

        function renderChecklist(savedDocs) {
            const tbody = document.getElementById('cfChecklistBody');
            let html = '';
            defaultChecklist.forEach((name, idx) => {
                const docNo = idx + 1;
                const saved = savedDocs.find(d => d.document_number == docNo);
                const isChecked = saved && saved.is_checked ? 'checked' : '';
                const remarks = saved && saved.remarks ? saved.remarks : '';
                
                let actionBtn = '';
                const autoGen = [3, 4, 6, 8, 11, 14, 15, 21, 22];
                const calcGen = [16, 19, 20];
                const inputGen = [5, 12];
                
                if (autoGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/20 text-sm font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">visibility</span> Generate Preview</button>`;
                } else if (calcGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-purple-500/10 text-purple-400 hover:bg-purple-500/20 border border-purple-500/20 text-sm font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">calculate</span> Calculate & Preview</button>`;
                } else if (inputGen.includes(docNo)) {
                    actionBtn = `<button type="button" onclick="openPreviewModal(`+docNo+`, '`+name.replace(/'/g, "\'")+`')" class="w-full py-1.5 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 text-sm font-bold transition-colors flex items-center justify-center gap-1"><span class="material-symbols-rounded text-[14px]">edit_document</span> Input Data</button>`;
                } else {
                    actionBtn = `<select class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1.5 text-sm text-slate-300 focus:border-amber-500 outline-none"><option>Attach Physical Copy</option><option>Generate Cover Page</option></select>`;
                }

                html += `
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 text-center font-black text-slate-500">` + docNo + `</td>
                        <td class="px-4 py-3 font-medium text-slate-300">` + name + `</td>
                        <td class="px-4 py-3 text-center">` + actionBtn + `</td>
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" id="cf_check_`+docNo+`" class="cf-check-item w-4 h-4 rounded border-slate-700 bg-slate-900 text-emerald-500 focus:ring-emerald-500/20" data-doc-no="` + docNo + `" data-doc-name="` + name + `" ` + isChecked + `>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" id="remark_` + docNo + `" value="` + remarks + `" class="w-full bg-slate-950 border border-slate-800 rounded px-2 py-1 text-sm text-white focus:border-amber-500 outline-none placeholder-slate-600" placeholder="Optional remarks...">
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function openPreviewModal(docNo, docName) {
            currentPreviewDocNo = docNo;
            document.getElementById('cfPreviewTitle').innerText = "Document " + docNo + ": " + docName;
            
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);

            // Reset standard verify button logic
            document.getElementById('btnVerifyDoc').onclick = verifyCurrentDoc;

            // Fetch preview HTML from server
            document.getElementById('cfPreviewBody').innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-slate-500 font-bold gap-3"><span class="material-symbols-rounded animate-spin text-base">sync</span> Generating Document...</div>';
            
            fetch(`/api/course-files/${currentCfId}/preview/${docNo}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'SUCCESS') {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="bg-white text-black p-8 rounded-xl shadow-inner min-h-[800px] max-w-4xl mx-auto">` + data.html + `</div>`;
                        
                        // Execute any scripts injected via innerHTML
                        const scripts = document.getElementById('cfPreviewBody').getElementsByTagName('script');
                        for (let i = 0; i < scripts.length; i++) {
                            try {
                                eval(scripts[i].innerText);
                            } catch (e) {
                                console.error('Error executing injected script:', e);
                            }
                        }
                    } else {
                        document.getElementById('cfPreviewBody').innerHTML = `<div class="text-red-400 font-bold text-center mt-10">${data.message || 'Preview unavailable.'}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById('cfPreviewBody').innerHTML = `<div class="text-red-400 font-bold text-center mt-10">Failed to load preview layout.</div>`;
                });
        }

        function closePreviewModal() {
            const modal = document.getElementById('cfPreviewModal');
            const content = document.getElementById('cfPreviewModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        let currentAttainmentSettings = null;

        function openAttainmentSettings() {
            if (currentAttainmentSettings) {
                document.getElementById('targetMarkPercent').value = currentAttainmentSettings.target_mark_percent || 60;
                document.getElementById('level3Percent').value = currentAttainmentSettings.level3_percent || 70;
                document.getElementById('level2Percent').value = currentAttainmentSettings.level2_percent || 60;
                document.getElementById('level1Percent').value = currentAttainmentSettings.level1_percent || 50;
            }

            const modal = document.getElementById('attainmentModal');
            const content = document.getElementById('attainmentModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeAttainmentSettings() {
            const modal = document.getElementById('attainmentModal');
            const content = document.getElementById('attainmentModalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function saveAttainmentSettings() {
            const btn = document.getElementById('btnSaveAttainment');
            btn.innerHTML = `<span class="material-symbols-rounded animate-spin">sync</span> Saving...`;

            const payload = {
                target_mark_percent: document.getElementById('targetMarkPercent').value || 60,
                level3_percent: document.getElementById('level3Percent').value || 70,
                level2_percent: document.getElementById('level2Percent').value || 60,
                level1_percent: document.getElementById('level1Percent').value || 50,
            };

            fetch(`/api/course-files/${currentCfId}/attainment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'SUCCESS') {
                    currentAttainmentSettings = data.data;
                    showGlobalMessage('Attainment Targets saved successfully!');
                    closeAttainmentSettings();
                } else {
                    alert(data.message || 'Error saving targets');
                }
            })
            .catch(err => {
                console.error(err);
                alert("Network error.");
            })
            .finally(() => {
                btn.innerHTML = `Save Targets`;
            });
        }

        function printPreviewDoc() {
            let html = document.getElementById('cfPreviewBody').innerHTML;
            if(!html || html.includes('Loading preview') || html.includes('Failed')) {
                alert("Nothing to print.");
                return;
            }
            
            let styles = '';
            document.querySelectorAll('link[rel="stylesheet"], style').forEach(el => {
                styles += el.outerHTML;
            });
            
            let printWin = window.open('', '_blank');
            printWin.document.write(`
                <html>
                    <head>
                        <title>Print Document</title>
                        ${styles}
                        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"><\/script>
                        <style>
                            @page { size: A4; margin: 15mm; }
                            body { font-family: 'Inter', sans-serif; background: #fff !important; color: #000 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                            .min-h-\\[800px\\] { min-height: auto !important; }
                            .max-w-4xl { max-width: 100% !important; }
                            .shadow-inner { box-shadow: none !important; }
                            .bg-white { background: transparent !important; }
                            .mx-auto { margin: 0 !important; }
                            .p-8 { padding: 0 !important; }
                        </style>
                    </head>
                    <body>
                        ${html}
                        <script>
                            setTimeout(() => { window.print(); window.close(); }, 1500);
                        <\/script>
                    </body>
                </html>
            `);
            printWin.document.close();
        }

        function verifyCurrentDoc() {
            if (currentPreviewDocNo) {
                const cb = document.getElementById('cf_check_' + currentPreviewDocNo);
                if (cb) cb.checked = true;
                saveCourseFileProgress();
                closePreviewModal();
                showGlobalMessage('Document ' + currentPreviewDocNo + ' verified and saved.');
            }
        }

        // Dedicated CIS Upload Function for Document 5
        window.uploadCis = function(file) {
            if (!file) return;
            
            const statusDiv = document.getElementById('uploadStatus');
            if (statusDiv) {
                statusDiv.classList.remove('hidden');
                statusDiv.innerHTML = '<span class="material-symbols-rounded animate-spin align-middle text-[16px]">sync</span> Uploading...';
            }
            
            const formData = new FormData();
            formData.append('cis_pdf', file);

            fetch(`/api/course-files/${currentCfId}/document/5/upload-cis`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    showGlobalMessage('CIS PDF successfully uploaded!');
                    // Auto-verify it
                    const cb = document.getElementById('cf_check_5');
                    if (cb) cb.checked = true;
                    saveCourseFileProgress();
                    
                    // Refresh the modal to show the iframe
                    openPreviewModal(5); 
                } else {
                    showGlobalMessage(data.message || 'Error uploading file.', true);
                    if (statusDiv) statusDiv.classList.add('hidden');
                }
            })
            .catch(err => {
                showGlobalMessage('Network error during upload.', true);
                if (statusDiv) statusDiv.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
