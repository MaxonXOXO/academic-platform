<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>S6 Open Elective Offering &amp; Nominal Roll — HOD {{ $targetBranch }}</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    .font-extrabold, .font-black { font-weight: 700 !important; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased p-4 sm:p-8">

  <!-- Header -->
  <header class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-4 py-4 border-b border-slate-800 mb-6">
    <div class="flex items-center gap-3">
      <a href="/dashboard/hod" class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-xs sm:text-sm font-bold no-underline transition-premium">
        <span class="material-symbols-rounded text-base">arrow_back</span> HOD Dashboard
      </a>
      <div>
        <h1 class="text-lg sm:text-xl font-black text-white flex items-center gap-2">
          <span>S6 Open Elective Course Setup &amp; Desk</span>
          <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
            Rev 2021 &bull; Dept {{ $targetBranch }}
          </span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Configure Department Offering, Course Outcomes, Syllabus PDF &amp; Assigned Faculty</p>
      </div>
    </div>

    @if($course && $course->exists && $settings->is_released_to_hods)
      <a href="/admin/open-elective/export-roster/{{ $course->id }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs sm:text-sm font-bold flex items-center gap-1.5 no-underline transition-premium">
        <span class="material-symbols-rounded text-base">print</span> Print Official Nominal Roll
      </a>
    @endif
  </header>

  <main class="max-w-6xl mx-auto space-y-6">

    <!-- Section 1: HOD Department Course Setup Form -->
    <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
        <div>
          <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-cyan-400 text-xl">edit_document</span>
            1. Department Open Elective Course Offering Details
          </h2>
          <p class="text-xs sm:text-sm text-slate-400 mt-0.5">This course will be offered to all other 5 engineering branches (excluding {{ $targetBranch }}).</p>
        </div>
        <div>
          @if($course && $course->is_hod_submitted)
            <span class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Course Submitted to Admin Desk
            </span>
          @else
            <span class="px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-black bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Course Setup Pending
            </span>
          @endif
        </div>
      </div>

      <form id="formHodCourse" onsubmit="handleSaveHodCourse(event)" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="offering_branch" value="{{ $targetBranch }}">

        <!-- Row 1: Course Code & Title -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1.5">SBTE Course Code *</label>
            <input type="text" name="course_code" value="{{ $course->course_code ?? '' }}" placeholder="e.g. 6001" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 font-mono font-bold placeholder:text-slate-600">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-200 mb-1.5">Course Name / Title *</label>
            <input type="text" name="course_title" value="{{ $course->course_title ?? '' }}" placeholder="e.g. Concept of Internet of Things (IoT)" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 font-bold placeholder:text-slate-600">
          </div>
        </div>

        <!-- Row 2: Capacity & Lecturer Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1.5">Maximum Seats *</label>
            <input type="number" name="max_capacity" value="{{ $course->max_capacity ?? 60 }}" min="10" max="120" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 font-mono font-bold">
            <span class="text-xs text-slate-400 mt-1 block">Standard classroom batch size is 60 seats.</span>
          </div>

          <div>
            <label class="block text-sm font-bold text-slate-200 mb-1.5">Assigned Faculty Lecturer *</label>
            <select name="faculty_staff_id" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 font-semibold">
              <option value="">-- Choose Lecturer from Dept of {{ $targetBranch }} --</option>
              @foreach($departmentFaculty as $fac)
                <option value="{{ $fac->mobile_no ?? $fac->id }}" {{ ($course->faculty_staff_id == ($fac->mobile_no ?? $fac->id) || $course->faculty_name == $fac->name) ? 'selected' : '' }}>
                  {{ $fac->name }} ({{ $fac->designation ?? 'Lecturer' }})
                </option>
              @endforeach
            </select>
            <span class="text-xs text-cyan-400 mt-1 block">Showing approved faculty from Department of {{ $targetBranch }} only.</span>
          </div>
        </div>

        <!-- Row 3: Description -->
        <div>
          <label class="block text-sm font-bold text-slate-200 mb-1.5">Course Overview &amp; Summary</label>
          <textarea name="course_description" rows="3" placeholder="Brief summary of modules, tools, and technologies taught..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 placeholder:text-slate-600 leading-relaxed">{{ $course->course_description ?? '' }}</textarea>
        </div>

        <!-- Row 4: Course Outcomes -->
        <div>
          <label class="block text-sm font-bold text-cyan-400 mb-1.5 flex items-center justify-between">
            <span class="flex items-center gap-1.5">
              <span class="material-symbols-rounded text-base">psychology</span>
              Course Learning Outcomes (COs) &mdash; Displayed on Student Choice Cards
            </span>
            <span class="text-xs text-slate-400 font-normal">Enter 1 outcome per line</span>
          </label>
          <textarea name="course_outcomes" rows="5" placeholder="CO1: Understand the architecture and principles of IoT&#10;CO2: Interface sensors and actuators with microcontrollers&#10;CO3: Implement cloud data transmission protocols&#10;CO4: Design smart automation applications" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700/80 text-sm sm:text-base text-white focus:outline-none focus:border-cyan-400 font-mono leading-relaxed placeholder:text-slate-600">{{ $course->course_outcomes ?? '' }}</textarea>
        </div>

        <!-- Row 5: Syllabus PDF Upload -->
        <div class="p-5 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-2.5">
          <label class="block text-sm font-bold text-slate-200">Upload Official Syllabus PDF</label>
          <input type="file" name="syllabus_pdf" accept=".pdf" class="w-full text-sm text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs sm:file:text-sm file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
          
          @if($course->syllabus_pdf_path)
            <div class="text-xs sm:text-sm text-emerald-400 flex items-center gap-2 pt-1.5">
              <span class="material-symbols-rounded text-base">picture_as_pdf</span>
              Current File: <a href="{{ $course->syllabus_pdf_path }}" target="_blank" class="underline font-bold text-cyan-400 hover:text-cyan-300">View Uploaded Syllabus PDF</a>
            </div>
          @endif
        </div>

        <div class="flex items-center justify-end gap-3 pt-3">
          <button type="submit" id="btnSaveHodCourse" class="px-6 py-3.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-cyan-900/40 transition-premium flex items-center gap-2">
            <span class="material-symbols-rounded text-lg">save</span> Save &amp; Submit Offering to Admin
          </button>
        </div>
      </form>
    </div>

    <!-- Section 2: Multi-Branch Virtual Classroom & Nominal Roll -->
    @if($settings->is_released_to_hods)
      <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/90 border border-emerald-500/30 shadow-xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-800">
          <div>
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
                Admission 2024 Open Elective
              </span>
              <span class="text-xs sm:text-sm font-mono font-bold text-cyan-400">
                Classroom: {{ $course->classroom_id ?: $targetBranch . '_2024_2027_OE' }}
              </span>
            </div>
            <h3 class="text-lg font-black text-white mt-1">
              2. Semester 6 Multi-Branch Virtual Classroom Theory
            </h3>
            <p class="text-xs sm:text-sm text-slate-300 mt-0.5 leading-relaxed">
              This class contains the newly registered students from other engineering departments (AU, CE, CT, EEE, EL, ME). All theory virtual classroom operations (Period Attendance, Lesson Plans, Assignments, CIA Marks) function identically to standard department batches.
            </p>
          </div>

          @if($course->batch_subject_id)
            <a href="/classroom/subject/{{ $course->batch_subject_id }}" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-md transition-premium no-underline">
              <span class="material-symbols-rounded text-base">co_present</span> Open Virtual Class Theory
            </a>
          @endif
        </div>

        <!-- Composition Summary Pills -->
        <div class="flex flex-wrap gap-2.5">
          @foreach($branchStats as $br => $cnt)
            <span class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold bg-slate-950 border border-slate-800 text-slate-200 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
              Department of {{ $br }}: <strong class="text-white">{{ $cnt }}</strong>
            </span>
          @endforeach
          <span class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-black bg-emerald-500/15 border border-emerald-500/30 text-emerald-300">
            Total Multi-Branch Enrollment: {{ $enrolledStudents->count() }} Students
          </span>
        </div>

        <!-- Enrolled Nominal Roll Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-950 text-slate-400 font-bold uppercase text-[11px] tracking-wider border-b border-slate-800">
              <tr>
                <th class="py-3 px-4">#</th>
                <th class="py-3 px-4">Student Full Name</th>
                <th class="py-3 px-4">SBTE PRN</th>
                <th class="py-3 px-4">Parent Dept</th>
                <th class="py-3 px-4">Admission No</th>
                <th class="py-3 px-4 text-right">Class Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 font-medium">
              @forelse($enrolledStudents as $idx => $st)
                <tr class="hover:bg-slate-800/40 transition-premium">
                  <td class="py-2.5 px-4 text-slate-500 font-mono">{{ $idx + 1 }}</td>
                  <td class="py-2.5 px-4 font-bold text-white">{{ $st->student_name }}</td>
                  <td class="py-2.5 px-4 font-mono text-cyan-400 font-bold">{{ $st->sbte_reg_no }}</td>
                  <td class="py-2.5 px-4">
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-black bg-slate-800 text-amber-300 border border-slate-700">
                      {{ $st->parent_branch }}
                    </span>
                  </td>
                  <td class="py-2.5 px-4 font-mono text-slate-400">{{ $st->student_adm_no }}</td>
                  <td class="py-2.5 px-4 text-right text-emerald-400 font-bold text-xs">Enrolled &bull; S6</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="py-8 text-center text-slate-500 text-xs sm:text-sm">
                    No students currently enrolled in this nominal roll.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @else
      <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/50 border border-dashed border-slate-800 text-center space-y-2">
        <span class="material-symbols-rounded text-3xl text-slate-600">hourglass_top</span>
        <h3 class="text-sm sm:text-base font-extrabold text-slate-300">Classroom Setup &amp; Student List Pending Release</h3>
        <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
          Once all 6 department courses are configured, the Principal Office will initiate student registration and algorithmic allocation. The finalized Admission 2024 Open Elective Multi-Branch classroom will activate automatically here upon Principal approval.
        </p>
      </div>
    @endif

  </main>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function handleSaveHodCourse(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSaveHodCourse');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-base animate-spin">progress_activity</span> Submitting...';

      const formData = new FormData(document.getElementById('formHodCourse'));

      try {
        const res = await fetch('/hod/open-elective/save-offering', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert(data.message);
          window.location.reload();
        } else {
          alert(data.message || 'Failed to save course offering.');
        }
      } catch (err) {
        alert('Server communication error.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-lg">save</span> Save &amp; Submit Offering to Admin';
      }
    }
  </script>
</body>
</html>
