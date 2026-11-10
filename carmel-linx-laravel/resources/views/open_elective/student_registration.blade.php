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
    html { font-size: 92%; }
    .font-extrabold, .font-black { font-weight: 700 !important; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased p-3 sm:p-6 pb-20">

  <!-- Mobile Top Bar -->
  <header class="max-w-2xl mx-auto flex items-center justify-between py-3 border-b border-slate-800 mb-5">
    <a href="/dashboard/student" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-xs font-bold no-underline">
      <span class="material-symbols-rounded text-sm">arrow_back</span> Mobile Dashboard
    </a>
    <div class="text-right">
      <span class="text-xs font-extrabold text-white block">{{ $student->name }}</span>
      <span class="text-[10px] font-mono text-cyan-400 font-bold block">{{ $student->sbte_reg_no ?? $student->reg_no }} &bull; S5 {{ $student->branch }}</span>
    </div>
  </header>

  <main class="max-w-2xl mx-auto space-y-5">

    <!-- Header Hero Card -->
    <div class="p-5 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900/90 to-blue-950/40 border border-slate-800/80 shadow-xl relative overflow-hidden">
      <div class="absolute -right-8 -top-8 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

      <div class="flex items-center justify-between mb-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
          Rev 2021 &bull; Semester 6
        </span>
        <span class="text-[11px] font-bold text-amber-400 flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">block</span> Dept {{ $student->branch }} Excluded
        </span>
      </div>

      <h1 class="text-lg sm:text-xl font-black text-white tracking-tight">Open Elective Registration</h1>
      <p class="text-xs text-slate-300 mt-1 leading-relaxed">
        Select and rank your <strong>5 preferred Open Elective courses</strong> for Semester 6. Courses are offered by the other 5 engineering departments.
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
      <div class="p-5 rounded-3xl bg-gradient-to-r from-emerald-950/90 to-slate-900 border-2 border-emerald-500/60 shadow-2xl space-y-2">
        <div class="flex items-center justify-between">
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
            🎉 Allotment Confirmed
          </span>
          <span class="text-xs font-mono font-bold text-emerald-400">Class: {{ $existingReg->allottedCourse->classroom_id ?: 'OE_2024_' . $existingReg->allottedCourse->offering_branch }}</span>
        </div>
        <h2 class="text-base sm:text-lg font-black text-white">
          {{ $existingReg->allottedCourse->course_title }}
        </h2>
        <div class="text-xs text-slate-300 flex items-center gap-2">
          <span>Offering Dept: <strong class="text-white">{{ $existingReg->allottedCourse->offering_branch }}</strong></span>
          <span>&bull;</span>
          <span>Course Code: <strong class="text-cyan-400 font-mono">{{ $existingReg->allottedCourse->course_code }}</strong></span>
        </div>
        <p class="text-[11px] text-emerald-300/80 pt-1">
          You are officially enrolled in this multi-branch theory classroom for Semester 6. Attendance and CIA marks will be maintained by Department of {{ $existingReg->allottedCourse->offering_branch }}.
        </p>
      </div>
    @endif

    <!-- If Student Already Registered: Option Chart Summary & Print Slip Button -->
    @if($existingReg)
      <div class="p-5 rounded-3xl bg-slate-900/80 border border-cyan-500/30 shadow-lg space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-cyan-400 text-xl">fact_check</span>
            <h2 class="text-sm font-extrabold text-white">Your Option Chart (Preferences)</h2>
          </div>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 font-mono">
            OE-2026-{{ str_pad($existingReg->id, 4, '0', STR_PAD_LEFT) }}
          </span>
        </div>

        <p class="text-xs text-slate-400">
          Submitted on <strong class="text-slate-200">{{ $existingReg->registered_at->format('d M Y, h:i A') }}</strong>.
        </p>

        <!-- Option Chart Visual Cards -->
        <div class="space-y-2 pt-2">
          @php
            $optItems = [
              ['rank' => '1st Choice', 'icon' => 'star', 'badge' => 'bg-amber-500/20 text-amber-300 border-amber-500/30', 'course' => $existingReg->option1Course],
              ['rank' => '2nd Choice', 'icon' => 'looks_two', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option2Course],
              ['rank' => '3rd Choice', 'icon' => 'looks_3', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option3Course],
              ['rank' => '4th Choice', 'icon' => 'looks_4', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option4Course],
              ['rank' => '5th Choice', 'icon' => 'looks_5', 'badge' => 'bg-slate-800 text-slate-300 border-slate-700', 'course' => $existingReg->option5Course],
            ];
          @endphp

          @foreach($optItems as $item)
            @if($item['course'])
              <div class="p-3 rounded-2xl bg-slate-950/80 border border-slate-800 flex items-start gap-3">
                <span class="px-2 py-1 rounded-lg text-[10px] font-black border uppercase {{ $item['badge'] }} shrink-0">
                  {{ $item['rank'] }}
                </span>
                <div class="flex-grow overflow-hidden">
                  <div class="flex items-center justify-between text-[11px] mb-0.5">
                    <span class="font-extrabold text-white truncate">{{ $item['course']->course_title }}</span>
                    <span class="text-slate-400 font-mono ml-2 shrink-0">{{ $item['course']->offering_branch }}</span>
                  </div>
                  @if($item['course']->course_outcomes)
                    <p class="text-[10px] text-slate-400 line-clamp-1 italic">
                      COs: {{ $item['course']->course_outcomes }}
                    </p>
                  @endif
                </div>
              </div>
            @endif
          @endforeach
        </div>

        <!-- Print Slip Button -->
        <div class="pt-3 border-t border-slate-800 flex justify-end">
          <a href="/student/open-elective/print-slip?student_adm_no={{ $student->adm_no }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs font-bold flex items-center gap-1.5 no-underline transition-premium">
            <span class="material-symbols-rounded text-sm">print</span> Print Official Option Chart Slip
          </a>
        </div>
      </div>
    @endif

    <!-- Eligible Courses Directory with Course Outcomes & Syllabus PDF -->
    <div class="space-y-3">
      <h2 class="text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-1.5 px-1">
        <span class="material-symbols-rounded text-cyan-400 text-sm">visibility</span> Department Offerings &amp; Course Outcomes
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
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/15 text-cyan-300 border border-cyan-500/25">
                Dept of {{ $c->offering_branch }}
              </span>
              <span class="text-xs font-mono font-bold text-slate-400">Code: {{ $c->course_code }}</span>
            </div>

            <h3 class="text-sm sm:text-base font-extrabold text-white">{{ $c->course_title }}</h3>
            
            @if($c->course_description)
              <p class="text-xs text-slate-300 leading-relaxed">{{ $c->course_description }}</p>
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

    <!-- Ranked Preference Submission Form -->
    @if($isWindowOpen && $eligibleCourses->count() >= 5)
      <div class="p-5 rounded-3xl bg-slate-900/90 border border-cyan-500/30 shadow-2xl space-y-4">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-cyan-400 text-xl">format_list_numbered</span>
          <h2 class="text-sm font-extrabold text-white">
            {{ $existingReg ? 'Update Your Course Preferences' : 'Submit Your Ranked Preferences' }}
          </h2>
        </div>
        <p class="text-xs text-slate-400">
          Rank all 5 subjects below. <strong>Option 1</strong> is your dream course. If seats fill up on FCFS, your 2nd and subsequent options will be used.
        </p>

        <form id="formStudentReg" onsubmit="handleStudentSubmit(event)" class="space-y-3.5">
          <!-- Option 1 -->
          <div>
            <label class="block text-xs font-bold text-cyan-400 mb-1 flex items-center gap-1">
              <span class="w-4 h-4 rounded-full bg-cyan-500/20 text-cyan-300 text-[10px] inline-flex items-center justify-center font-black">1</span>
              Option 1 (Highest Priority)
            </label>
            <select id="option_1" name="option_1" required onchange="validateUniqueChoices()" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">-- Choose 1st Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" {{ ($existingReg && $existingReg->option_1_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 2 -->
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1 flex items-center gap-1">
              <span class="w-4 h-4 rounded-full bg-slate-800 text-slate-300 text-[10px] inline-flex items-center justify-center font-black">2</span>
              Option 2 (First Backup)
            </label>
            <select id="option_2" name="option_2" required onchange="validateUniqueChoices()" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">-- Choose 2nd Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" {{ ($existingReg && $existingReg->option_2_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 3 -->
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1 flex items-center gap-1">
              <span class="w-4 h-4 rounded-full bg-slate-800 text-slate-300 text-[10px] inline-flex items-center justify-center font-black">3</span>
              Option 3 (Second Backup)
            </label>
            <select id="option_3" name="option_3" required onchange="validateUniqueChoices()" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">-- Choose 3rd Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" {{ ($existingReg && $existingReg->option_3_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 4 -->
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1 flex items-center gap-1">
              <span class="w-4 h-4 rounded-full bg-slate-800 text-slate-300 text-[10px] inline-flex items-center justify-center font-black">4</span>
              Option 4 (Third Backup)
            </label>
            <select id="option_4" name="option_4" required onchange="validateUniqueChoices()" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">-- Choose 4th Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" {{ ($existingReg && $existingReg->option_4_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Option 5 -->
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1 flex items-center gap-1">
              <span class="w-4 h-4 rounded-full bg-slate-800 text-slate-300 text-[10px] inline-flex items-center justify-center font-black">5</span>
              Option 5 (Fourth Backup)
            </label>
            <select id="option_5" name="option_5" required onchange="validateUniqueChoices()" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white focus:outline-none focus:border-cyan-500 font-bold">
              <option value="">-- Choose 5th Preference --</option>
              @foreach($eligibleCourses as $c)
                <option value="{{ $c->id }}" {{ ($existingReg && $existingReg->option_5_course_id == $c->id) ? 'selected' : '' }}>
                  {{ $c->offering_branch }} — {{ $c->course_title }} (Code: {{ $c->course_code }})
                </option>
              @endforeach
            </select>
          </div>

          <div id="duplicateAlert" class="hidden p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs font-bold">
            Notice: Each option must be a distinct course. Please ensure no duplicate course is chosen.
          </div>

          <button type="submit" id="btnSubmitPreferences" class="w-full py-3 rounded-2xl bg-cyan-600 hover:bg-cyan-500 text-white font-extrabold text-sm shadow-xl shadow-cyan-900/40 transition-premium flex items-center justify-center gap-2">
            <span class="material-symbols-rounded text-base">check_circle</span>
            {{ $existingReg ? 'Confirm Updated Preferences' : 'Submit Ranked Preferences' }}
          </button>
        </form>
      </div>
    @elseif(!$isWindowOpen)
      <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 text-center space-y-2">
        <span class="material-symbols-rounded text-3xl text-amber-400">lock_clock</span>
        <h3 class="text-sm font-extrabold text-white">Registration Window is Currently Closed</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto">
          The Principal Office will open the registration desk as per schedule. Check notifications for announcements.
        </p>
      </div>
    @endif

  </main>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Countdown Timer
    function updateCountdown() {
      const el = document.getElementById('countdownTimer');
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

    // Validate unique choices
    function validateUniqueChoices() {
      const vals = [
        document.getElementById('option_1').value,
        document.getElementById('option_2').value,
        document.getElementById('option_3').value,
        document.getElementById('option_4').value,
        document.getElementById('option_5').value,
      ].filter(v => v !== '');

      const uniqueVals = new Set(vals);
      const alertEl = document.getElementById('duplicateAlert');
      const submitBtn = document.getElementById('btnSubmitPreferences');

      if (vals.length !== uniqueVals.size) {
        alertEl.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        return false;
      } else {
        alertEl.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        return true;
      }
    }

    // Submit handler
    async function handleStudentSubmit(e) {
      e.preventDefault();
      if (!validateUniqueChoices()) return;

      const btn = document.getElementById('btnSubmitPreferences');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">progress_activity</span> Submitting...';

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
        btn.innerHTML = '<span class="material-symbols-rounded text-base">check_circle</span> Submit Ranked Preferences';
      }
    }
  </script>
</body>
</html>
