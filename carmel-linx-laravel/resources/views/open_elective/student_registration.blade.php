<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>S6 Open Elective Registration — Carmel Linx</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html { font-size: 94%; }
    .font-extrabold, .font-black { font-weight: 700 !important; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased p-3 sm:p-6 pb-24">

  <!-- Mobile Top Bar -->
  <header class="max-w-2xl mx-auto flex items-center justify-between py-3 border-b border-slate-800 mb-5">
    <a href="/dashboard/student" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-xs font-bold no-underline">
      <span class="material-symbols-rounded text-sm">arrow_back</span> Mobile Dashboard
    </a>
    <div class="text-right">
      <span class="text-xs font-extrabold text-white block">{{ $student->name }}</span>
      <span class="text-[11px] font-mono text-cyan-400 font-bold block">{{ $student->sbte_reg_no ?? $student->reg_no }} &bull; S5 {{ \App\Models\OeCourse::getBranchFullName($student->branch) }}</span>
    </div>
  </header>

  <main class="max-w-2xl mx-auto space-y-6">

    <!-- Header Hero Card -->
    <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900/90 to-blue-950/40 border border-slate-800/80 shadow-xl relative overflow-hidden">
      <div class="absolute -right-8 -top-8 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

      <div class="flex items-center justify-between mb-2">
        <span class="px-3 py-1 rounded-full text-[11px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
          Rev 2021 &bull; Semester 6
        </span>
        <span class="text-xs font-bold text-amber-400 flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">block</span> {{ \App\Models\OeCourse::getBranchFullName($student->branch) }} Excluded
        </span>
      </div>

      <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Open Elective Registration</h1>
      <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
        As per SBTE Revision 2021 curriculum, each student must register <strong>all 5 options compulsory</strong> in order of preference. Courses are offered by the other 5 engineering departments.
      </p>

      <!-- Live Countdown Pill -->
      <div class="mt-4 p-3 rounded-2xl bg-slate-950/70 border border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-cyan-400 text-lg">timer</span>
          <span class="text-xs font-bold text-slate-300">Registration Window:</span>
        </div>
        <div id="countdownTimer" class="text-xs font-mono font-black text-cyan-400">
          Checking deadline...
        </div>
      </div>
    </div>

    <!-- Official Allotment Notification Banner (When Confirmed by Principal) -->
    @if($existingReg && $existingReg->allottedCourse)
      <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-emerald-950/90 to-slate-900 border-2 border-emerald-500/60 shadow-2xl space-y-2.5">
        <div class="flex items-center justify-between">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
            🎉 Allotment Confirmed
          </span>
          <span class="text-xs font-mono font-bold text-emerald-400">Class: {{ $existingReg->allottedCourse->classroom_id ?: 'OE_2024_' . $existingReg->allottedCourse->offering_branch }}</span>
        </div>
        <h2 class="text-base sm:text-lg font-black text-white">
          {{ $existingReg->allottedCourse->course_title }}
        </h2>
        <div class="text-xs sm:text-sm text-slate-300 flex flex-wrap items-center gap-2">
          <span>Offering Dept: <strong class="text-white">{{ $existingReg->allottedCourse->offering_branch_full }}</strong></span>
          <span>&bull;</span>
          <span>Course Code: <strong class="text-cyan-400 font-mono">{{ $existingReg->allottedCourse->course_code }}</strong></span>
        </div>
        <p class="text-xs text-emerald-300/80 pt-1">
          You are officially enrolled in this multi-branch theory classroom for Semester 6. Attendance and CIA marks will be maintained by Department of {{ $existingReg->allottedCourse->offering_branch_full }}.
        </p>
      </div>
    @endif

    <!-- SUBMISSION STATUS LIST (At the top of the page after submission) -->
    @if($existingReg)
      <div id="studentSubmissionStatusCard" class="p-5 sm:p-6 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950/40 border-2 border-emerald-500/60 shadow-2xl space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
          <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sm">lock</span> Submission Status: Preferences Locked &amp; Confirmed
          </span>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-slate-950 text-cyan-400 border border-slate-800 font-mono">
            OE-2026-{{ str_pad($existingReg->id, 4, '0', STR_PAD_LEFT) }}
          </span>
        </div>

        <div>
          <h2 class="text-base sm:text-lg font-black text-white">Your Submitted Preferences (5 of 5 Compulsory Options)</h2>
          <p class="text-xs text-slate-300 mt-0.5">
            Locked on <strong class="text-emerald-300">{{ $existingReg->registered_at ? $existingReg->registered_at->format('d M Y, h:i A') : 'Recorded' }}</strong>.
            All 5 choices are officially recorded in the central registration ledger.
          </p>
        </div>

        <!-- Numbered List of 5 Preferences -->
        <div class="space-y-2.5 pt-1">
          @php
            $optItems = [
              ['rank' => '1st Choice (Highest Priority)', 'icon' => 'star', 'badge' => 'bg-amber-500/20 text-amber-300 border-amber-500/40', 'course' => $existingReg->option1Course],
              ['rank' => '2nd Choice (Backup 1)', 'icon' => 'looks_two', 'badge' => 'bg-cyan-500/15 text-cyan-300 border-cyan-500/30', 'course' => $existingReg->option2Course],
              ['rank' => '3rd Choice (Backup 2)', 'icon' => 'looks_3', 'badge' => 'bg-blue-500/15 text-blue-300 border-blue-500/30', 'course' => $existingReg->option3Course],
              ['rank' => '4th Choice (Backup 3)', 'icon' => 'looks_4', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option4Course],
              ['rank' => '5th Choice (Backup 4)', 'icon' => 'looks_5', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option5Course],
            ];
          @endphp

          @foreach($optItems as $item)
            @if($item['course'])
              <div class="p-3.5 rounded-2xl bg-slate-950/85 border border-slate-800/80 flex items-start gap-3">
                <span class="px-2.5 py-1 rounded-xl text-[10px] font-black border uppercase {{ $item['badge'] }} shrink-0">
                  {{ $item['rank'] }}
                </span>
                <div class="flex-grow">
                  <div class="flex flex-wrap items-center justify-between text-xs sm:text-sm font-extrabold text-white">
                    <span>{{ $item['course']->course_title }}</span>
                    <span class="text-xs font-mono font-bold text-cyan-400">Code: {{ $item['course']->course_code }}</span>
                  </div>
                  <div class="text-[11px] text-slate-300 mt-0.5">
                    Offering Department: <strong class="text-white">{{ $item['course']->offering_branch_full }}</strong>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>

        <!-- Slip and Re-submit Actions -->
        <div class="pt-3 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3">
          <span class="text-[11px] text-slate-400 flex items-center gap-1">
            <span class="material-symbols-rounded text-emerald-400 text-sm">verified</span> Verified &amp; Locked in Central Database
          </span>
          <div class="flex items-center gap-2">
            @if($isWindowOpen && !$existingReg->allottedCourse)
              <button type="button" onclick="unlockPreferencesForEdit()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-300 border border-slate-700 text-xs font-bold flex items-center gap-1.5 transition-premium">
                <span class="material-symbols-rounded text-sm">edit</span> Change / Re-submit Preferences
              </button>
            @endif
            <a href="/student/open-elective/print-slip?student_adm_no={{ $student->adm_no }}" target="_blank" class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold flex items-center gap-1.5 no-underline transition-premium shadow-md shadow-cyan-950/50">
              <span class="material-symbols-rounded text-sm">print</span> Print Official Option Chart Slip
            </a>
          </div>
        </div>
      </div>
    @endif

    <!-- Eligible Courses Directory with Course Outcomes & Syllabus PDF -->
    <div class="space-y-3">
      <h2 class="text-sm sm:text-base font-black uppercase text-slate-300 tracking-wider flex items-center gap-1.5 px-1">
        <span class="material-symbols-rounded text-cyan-400 text-lg">visibility</span> Department Offerings &amp; Course Outcomes
      </h2>

      <div class="grid grid-cols-1 gap-3.5">
        @forelse($eligibleCourses as $c)
          @php
            $opt1 = $c->opt1_demand ?? 0;
            $cap = $c->max_capacity;
            $pct = $cap > 0 ? min(100, round(($opt1 / $cap) * 100)) : 0;
          @endphp
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/70 border border-slate-800 hover:border-slate-700 transition-premium space-y-2.5">
            <div class="flex items-center justify-between">
              <span class="px-3 py-1 rounded-full text-xs font-black bg-cyan-500/15 text-cyan-300 border border-cyan-500/25">
                Department of {{ $c->offering_branch_full }}
              </span>
              <span class="text-xs font-mono font-bold text-slate-400">Code: {{ $c->course_code }}</span>
            </div>

            <h3 class="text-base sm:text-lg font-black text-white">{{ $c->course_title }}</h3>
            
            @if($c->course_description)
              <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ $c->course_description }}</p>
            @endif

            <!-- Course Outcomes (COs) Section -->
            @if(!empty($c->parsed_outcomes))
              <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800 space-y-1">
                <span class="text-[10px] uppercase font-black text-cyan-400 tracking-wider block flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">psychology</span> Course Learning Outcomes (COs):
                </span>
                <ul class="text-[11px] text-slate-300 space-y-1 list-disc list-inside">
                  @foreach(array_slice($c->parsed_outcomes, 0, 4) as $co)
                    <li class="leading-tight">{{ $co }}</li>
                  @endforeach
                </ul>
              </div>
            @elseif($c->course_outcomes)
              <div class="p-3 rounded-xl bg-slate-950/70 border border-slate-800">
                <span class="text-[10px] uppercase font-black text-cyan-400 tracking-wider block mb-1">Course Outcomes:</span>
                <p class="text-[11px] text-slate-300 leading-tight">{{ $c->course_outcomes }}</p>
              </div>
            @endif

            <!-- Syllabus PDF Download Link if Uploaded by HOD -->
            @if($c->syllabus_pdf_path)
              <div class="pt-1">
                <a href="{{ $c->syllabus_pdf_path }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-400 hover:text-cyan-300 underline underline-offset-2">
                  <span class="material-symbols-rounded text-sm">download</span> Download Official Syllabus PDF
                </a>
              </div>
            @endif

            <!-- Live Demand Bar -->
            <div class="p-2.5 rounded-xl bg-slate-950/90 border border-slate-800/80 space-y-1">
              <div class="flex justify-between text-[11px]">
                <span class="text-slate-400">Live 1st Choice Demand:</span>
                <span class="font-bold text-cyan-400">{{ $opt1 }} / {{ $cap }} opted</span>
              </div>
              <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                <div class="bg-cyan-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          </div>
        @empty
          <div class="p-6 text-center text-slate-500 bg-slate-900/40 rounded-2xl border border-dashed border-slate-800">
            No Open Elective courses available for registration yet.
          </div>
        @endforelse
      </div>
    </div>

    <!-- Ranked Preference Submission Form with Highlighted Amber Thin Border -->
    @if($isWindowOpen && $eligibleCourses->count() >= 5)
      <div id="submitOptionsCard" class="p-5 sm:p-6 rounded-3xl bg-slate-900/95 border border-amber-400 shadow-[0_0_20px_rgba(245,158,11,0.22)] space-y-4 {{ $existingReg ? 'hidden' : '' }}">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-2xl">format_list_numbered</span>
          <h2 class="text-base sm:text-xl font-black text-white">
            {{ $existingReg ? 'Update Your 5 Course Preferences' : 'Submit Your 5 Course Preferences' }}
          </h2>
        </div>

        <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 text-xs sm:text-[13px] leading-relaxed">
          <strong class="text-amber-300">Mandatory Rule (Rev 2021):</strong> Every student <strong>must register all 5 options compulsory</strong>. Single or partial subject submissions are strictly prohibited. Options cannot be repeated.
        </div>

        <form id="formStudentReg" onsubmit="event.preventDefault(); openConfirmLockModal();" class="space-y-4">
          <!-- Option 1 -->
          <div>
            <label class="block text-xs sm:text-sm font-bold text-amber-400 mb-1.5 flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-300 text-xs inline-flex items-center justify-center font-black">1</span>
              Option 1 (Highest Priority / 1st Choice) *
            </label>
            <select id="option_1" name="option_1" required onchange="syncOptionDropdowns()" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-[13.5px] sm:text-sm text-white focus:outline-none focus:border-amber-400 font-semibold transition-premium">
              <option value="">-- Choose 1st Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch_full }}" data-title="{{ $c->course_title }}" data-code="{{ $c->course_code }}" {{ ($existingReg && $existingReg->option_1_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch_full }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 2 -->
          <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-200 mb-1.5 flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-xs inline-flex items-center justify-center font-black">2</span>
              Option 2 (2nd Choice / First Backup) *
            </label>
            <select id="option_2" name="option_2" required onchange="syncOptionDropdowns()" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-[13.5px] sm:text-sm text-white focus:outline-none focus:border-amber-400 font-semibold transition-premium">
              <option value="">-- Choose 2nd Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch_full }}" data-title="{{ $c->course_title }}" data-code="{{ $c->course_code }}" {{ ($existingReg && $existingReg->option_2_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch_full }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 3 -->
          <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-200 mb-1.5 flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-xs inline-flex items-center justify-center font-black">3</span>
              Option 3 (3rd Choice / Second Backup) *
            </label>
            <select id="option_3" name="option_3" required onchange="syncOptionDropdowns()" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-[13.5px] sm:text-sm text-white focus:outline-none focus:border-amber-400 font-semibold transition-premium">
              <option value="">-- Choose 3rd Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch_full }}" data-title="{{ $c->course_title }}" data-code="{{ $c->course_code }}" {{ ($existingReg && $existingReg->option_3_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch_full }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 4 -->
          <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-200 mb-1.5 flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-xs inline-flex items-center justify-center font-black">4</span>
              Option 4 (4th Choice / Third Backup) *
            </label>
            <select id="option_4" name="option_4" required onchange="syncOptionDropdowns()" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-[13.5px] sm:text-sm text-white focus:outline-none focus:border-amber-400 font-semibold transition-premium">
              <option value="">-- Choose 4th Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch_full }}" data-title="{{ $c->course_title }}" data-code="{{ $c->course_code }}" {{ ($existingReg && $existingReg->option_4_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch_full }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 5 -->
          <div>
            <label class="block text-xs sm:text-sm font-bold text-slate-200 mb-1.5 flex items-center gap-1.5">
              <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-300 text-xs inline-flex items-center justify-center font-black">5</span>
              Option 5 (5th Choice / Fourth Backup) *
            </label>
            <select id="option_5" name="option_5" required onchange="syncOptionDropdowns()" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-[13.5px] sm:text-sm text-white focus:outline-none focus:border-amber-400 font-semibold transition-premium">
              <option value="">-- Choose 5th Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->offering_branch_full }}" data-title="{{ $c->course_title }}" data-code="{{ $c->course_code }}" {{ ($existingReg && $existingReg->option_5_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch_full }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- LIVE PREFERENCES LIST BELOW DROPDOWNS -->
          <div class="mt-5 p-4 sm:p-5 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
              <h3 class="text-xs sm:text-sm font-black text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                <span class="material-symbols-rounded text-base">checklist</span> Your Ranked Preferences (Review List)
              </h3>
              <span id="prefCompletionBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30">
                0 of 5 Selected
              </span>
            </div>

            <div id="preferencesLiveList" class="space-y-2">
              <!-- Dynamically populated by syncOptionDropdowns() -->
            </div>
          </div>

          <div id="duplicateAlert" class="hidden p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold">
            Notice: Each option must be a distinct course. Please ensure no duplicate course is chosen.
          </div>

          <button type="submit" id="btnOpenLockModal" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-sm sm:text-base shadow-xl shadow-amber-950/50 transition-premium flex items-center justify-center gap-2">
            <span class="material-symbols-rounded text-lg">lock</span> Review &amp; Lock 5 Preferences
          </button>
        </form>
      </div>
    @elseif(!$isWindowOpen)
      <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 text-center space-y-2">
        <span class="material-symbols-rounded text-3xl text-amber-400">lock_clock</span>
        <h3 class="text-base font-extrabold text-white">Registration Window is Currently Closed</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto">
          The Principal Office will open the registration desk as per schedule. Check notifications for announcements.
        </p>
      </div>
    @endif

  </main>

  <!-- CONFIRM AND LOCK MODAL -->
  <div id="lockConfirmModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-amber-500/50 rounded-3xl w-full max-w-lg p-6 space-y-4 shadow-2xl">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-2xl">lock</span>
          <h3 class="text-base sm:text-lg font-black text-white">Confirm &amp; Lock Your 5 Preferences</h3>
        </div>
        <button onclick="closeConfirmLockModal()" class="text-slate-400 hover:text-white material-symbols-rounded">close</button>
      </div>

      <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 text-xs space-y-1">
        <div>Student: <strong class="text-white">{{ $student->name }}</strong></div>
        <div>SBTE Reg No: <span class="font-mono text-cyan-400 font-bold">{{ $student->sbte_reg_no ?? $student->reg_no }}</span> &bull; Parent Dept: <strong class="text-amber-400">{{ \App\Models\OeCourse::getBranchFullName($student->branch) }}</strong></div>
      </div>

      <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-200 text-xs">
        <strong class="text-amber-300">Compulsory Check:</strong> You have selected all 5 distinct options as required by Revision 2021. Once locked, this order is officially submitted for First-Come First-Served allocation.
      </div>

      <!-- Ranked choices list in modal -->
      <div class="space-y-2" id="modalPrefList">
        <!-- Rendered by JS -->
      </div>

      <div class="pt-2">
        <label class="flex items-start gap-2 text-xs text-slate-300 cursor-pointer">
          <input type="checkbox" id="chkModalConfirm" class="mt-0.5 rounded border-slate-700 text-amber-500 focus:ring-0">
          <span>I verify that I have selected all 5 options in my preferred priority order and confirm this registration.</span>
        </label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
        <button type="button" onclick="closeConfirmLockModal()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-premium">
          Change Preferences
        </button>
        <button type="button" id="btnConfirmAndLockFinal" onclick="handleFinalLockSubmission()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white text-xs sm:text-sm font-black transition-premium shadow-lg shadow-emerald-950/50 flex items-center gap-1.5">
          <span class="material-symbols-rounded text-base">lock</span> Confirm &amp; Lock Now
        </button>
      </div>
    </div>
  </div>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Countdown Timer
    function updateCountdown() {
      const el = document.getElementById('countdownTimer');
      if (!el) return;
      const startStr = '{{ $settings->reg_start_at ? $settings->reg_start_at->toIso8601String() : "" }}';
      const endStr = '{{ $settings->reg_end_at ? $settings->reg_end_at->toIso8601String() : "" }}';
      const isOpen = {{ $settings->is_registration_open ? 'true' : 'false' }};

      if (!isOpen) {
        el.innerText = 'Registration is currently paused';
        return;
      }
      if (!endStr) {
        el.innerText = 'Active (Open)';
        return;
      }

      const now = new Date().getTime();
      const end = new Date(endStr).getTime();
      const diff = end - now;

      if (diff <= 0) {
        el.innerText = 'Window closed';
        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const secs = Math.floor((diff % (1000 * 60)) / 1000);

      el.innerText = `Closes in ${days}d ${hours}h ${mins}m ${secs}s`;
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Prevent Repeating Options & Sync Dropdowns
    function syncOptionDropdowns() {
      const selects = [
        document.getElementById('option_1'),
        document.getElementById('option_2'),
        document.getElementById('option_3'),
        document.getElementById('option_4'),
        document.getElementById('option_5')
      ].filter(Boolean);

      const currentValues = selects.map(s => s.value);

      selects.forEach((select, idx) => {
        const otherValues = currentValues.filter((val, i) => i !== idx && val !== '');

        Array.from(select.options).forEach(opt => {
          if (!opt.value) return;
          const baseLabel = opt.getAttribute('data-base-label') || opt.text.replace(/ \(Already selected\)/, '');
          opt.setAttribute('data-base-label', baseLabel);

          if (otherValues.includes(opt.value)) {
            opt.disabled = true;
            opt.text = `${baseLabel} (Already selected)`;
          } else {
            opt.disabled = false;
            opt.text = baseLabel;
          }
        });
      });

      updatePreferencesLiveList();
    }

    // Update Live Preferences List Below Dropdowns
    function updatePreferencesLiveList() {
      const container = document.getElementById('preferencesLiveList');
      const badge = document.getElementById('prefCompletionBadge');
      const submitBtn = document.getElementById('btnOpenLockModal');
      const alertEl = document.getElementById('duplicateAlert');
      if (!container) return;

      const labels = [
        '1st Choice (Highest Priority)',
        '2nd Choice (Backup 1)',
        '3rd Choice (Backup 2)',
        '4th Choice (Backup 3)',
        '5th Choice (Backup 4)'
      ];

      const selects = [
        document.getElementById('option_1'),
        document.getElementById('option_2'),
        document.getElementById('option_3'),
        document.getElementById('option_4'),
        document.getElementById('option_5')
      ];

      let selectedCount = 0;
      let html = '';
      const chosenIds = [];

      selects.forEach((sel, i) => {
        if (!sel) return;
        const val = sel.value;
        const opt = sel.options[sel.selectedIndex];

        if (val) {
          selectedCount++;
          chosenIds.push(val);
          const branch = opt.getAttribute('data-branch') || '';
          const title = opt.getAttribute('data-title') || opt.text;
          const code = opt.getAttribute('data-code') || '';

          html += `
            <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 flex items-start justify-between gap-3 text-xs">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] inline-flex items-center justify-center font-black">${i + 1}</span>
                <div>
                  <span class="font-extrabold text-white">${title}</span>
                  <div class="text-[11px] text-slate-400">Department: <strong class="text-slate-300">${branch}</strong> &bull; Code: <span class="font-mono text-cyan-400">${code}</span></div>
                </div>
              </div>
              <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-800 text-amber-300 shrink-0 uppercase">${labels[i]}</span>
            </div>
          `;
        } else {
          html += `
            <div class="p-2.5 rounded-xl bg-slate-950/60 border border-dashed border-slate-800 flex items-center justify-between text-xs text-slate-500">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-400 text-[10px] inline-flex items-center justify-center font-black">${i + 1}</span>
                <span>Option ${i + 1}: <strong class="text-amber-400/90 font-medium">Pending selection (Compulsory)</strong></span>
              </div>
              <span class="text-[10px] uppercase font-bold text-slate-600">${labels[i]}</span>
            </div>
          `;
        }
      });

      container.innerHTML = html;

      // Unique check
      const uniqueCount = new Set(chosenIds).size;
      const hasDuplicates = chosenIds.length !== uniqueCount;

      if (hasDuplicates) {
        alertEl.classList.remove('hidden');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500/20 text-rose-300 border border-rose-500/30';
        badge.innerText = 'Duplicate Detected';
        return;
      } else {
        alertEl.classList.add('hidden');
      }

      if (selectedCount === 5) {
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
        badge.innerText = 'All 5 Selected (Ready to Lock)';
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
      } else {
        badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30';
        badge.innerText = `${selectedCount} of 5 Selected (Compulsory)`;
        if (submitBtn) {
          submitBtn.disabled = false; // allow clicking to show alert explaining all 5 are needed
          submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
      }
    }

    // Modal Opening
    function openConfirmLockModal() {
      const selects = [
        document.getElementById('option_1'),
        document.getElementById('option_2'),
        document.getElementById('option_3'),
        document.getElementById('option_4'),
        document.getElementById('option_5')
      ];

      const vals = selects.map(s => s ? s.value : '');
      if (vals.some(v => !v)) {
        alert('Compulsory Rule (Rev 2021):\nYou must select all 5 options before submitting. Please fill all 5 dropdown preferences.');
        return;
      }

      if (new Set(vals).size !== 5) {
        alert('All 5 choices must be distinct courses. You cannot repeat the same option.');
        return;
      }

      // Populate Modal list
      const modalList = document.getElementById('modalPrefList');
      const labels = [
        '1st Choice (Highest Priority)',
        '2nd Choice',
        '3rd Choice',
        '4th Choice',
        '5th Choice'
      ];

      let html = '';
      selects.forEach((sel, i) => {
        const opt = sel.options[sel.selectedIndex];
        const branch = opt.getAttribute('data-branch') || '';
        const title = opt.getAttribute('data-title') || opt.text;
        const code = opt.getAttribute('data-code') || '';

        html += `
          <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] inline-flex items-center justify-center font-black">${i + 1}</span>
              <div>
                <span class="font-extrabold text-white">${title}</span>
                <div class="text-[11px] text-slate-400">Department: <strong class="text-slate-300">${branch}</strong> (Code: ${code})</div>
              </div>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-800 text-amber-300">${labels[i]}</span>
          </div>
        `;
      });

      modalList.innerHTML = html;
      document.getElementById('chkModalConfirm').checked = false;

      const modal = document.getElementById('lockConfirmModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeConfirmLockModal() {
      const modal = document.getElementById('lockConfirmModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Final Lock Submission
    async function handleFinalLockSubmission() {
      const chk = document.getElementById('chkModalConfirm');
      if (!chk.checked) {
        alert('Please tick the confirmation checkbox to verify and lock your preferences.');
        return;
      }

      const btn = document.getElementById('btnConfirmAndLockFinal');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Locking Preferences...';

      const payload = {
        student_adm_no: '{{ $student->adm_no }}',
        option_1: document.getElementById('option_1').value,
        option_2: document.getElementById('option_2').value,
        option_3: document.getElementById('option_3').value,
        option_4: document.getElementById('option_4').value,
        option_5: document.getElementById('option_5').value,
      };

      try {
        const res = await fetch('/student/open-elective/submit', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert('Success! ' + data.message + '\nToken: ' + data.token);
          window.location.reload();
        } else {
          alert(data.message || 'Submission failed.');
        }
      } catch (err) {
        alert('Server communication error. Please try again.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">lock</span> Confirm &amp; Lock Now';
      }
    }

    function unlockPreferencesForEdit() {
      const card = document.getElementById('submitOptionsCard');
      if (card) {
        card.classList.remove('hidden');
        card.scrollIntoView({ behavior: 'smooth' });
      }
    }

    // Initial Sync on Page Load
    document.addEventListener('DOMContentLoaded', () => {
      syncOptionDropdowns();
    });
  </script>
</body>
</html>