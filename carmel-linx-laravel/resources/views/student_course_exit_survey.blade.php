<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Exit Survey — Carmel Linx</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .rating-btn { transition: all 0.2s ease; cursor: pointer; }
    .rating-btn:hover { transform: scale(1.02); }
    .rating-btn.selected { transform: scale(1.04); box-shadow: 0 0 0 2px rgba(255,255,255,0.15); }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.5s ease both; }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-2xl fade-up my-6">

    <!-- Header -->
    <div class="bg-slate-950/70 border border-slate-800/80 rounded-3xl p-6 mb-6">
      <div class="flex items-center gap-4 mb-4">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/20">
          <span class="material-symbols-rounded text-teal-400 text-2xl">assignment_turned_in</span>
        </div>
        <div>
          <h1 class="text-xl font-black text-white tracking-tight">Course Exit Survey</h1>
          <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">NBA Course Outcome (CO) Indirect Attainment Evaluation</p>
        </div>
      </div>
      
      <p class="text-xs text-slate-400 leading-relaxed border-t border-slate-800/60 pt-3">
        <strong>Purpose:</strong> This survey gathers student feedback on the level of Course Outcome (CO) attainment at the end of the semester. Your inputs will be used in indirect CO attainment calculations for course accreditation.
      </p>

      <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4 text-xs font-semibold bg-slate-900/60 p-4 rounded-xl border border-slate-800/60">
        <div><span class="text-slate-500 block uppercase text-[10px]">Course & Code</span><span class="text-slate-200 font-bold font-mono">{{ $survey->subject_code }} — {{ $survey->subject_name }}</span></div>
        <div><span class="text-slate-500 block uppercase text-[10px]">Faculty Member</span><span class="text-slate-200 font-bold">{{ $survey->faculty_name ?? 'Faculty Member' }}</span></div>
        <div><span class="text-slate-500 block uppercase text-[10px]">Semester / Batch</span><span class="text-teal-400 font-bold">Sem {{ $survey->semester }} / {{ $survey->batch_year ?? 'N/A' }}</span></div>
      </div>
    </div>

    <!-- Form -->
    <form id="surveyForm" class="space-y-4" onsubmit="submitSurvey(event)">
      <input type="hidden" id="survey_id_val" value="{{ $survey->id }}">

      <div class="px-2 py-1 text-xs text-teal-400 font-bold uppercase tracking-wider">Course Outcome specific questions (Required)</div>

      @php
        $isProject = str_contains(strtolower($survey->subject_name ?? ''), 'project');

        if ($isProject) {
          $defaultQuestions = [
            'q1'  => ['icon' => 'lightbulb',       'label' => 'Q1. CO1 - Problem Identification', 'desc' => 'How well did the major project guide you in identifying relevant engineering problems and defining feasible project objectives?'],
            'q2'  => ['icon' => 'menu_book',       'label' => 'Q2. CO1 - Literature Review & Feasibility', 'desc' => 'How thoroughly were you able to survey existing publications, technical journals, and market solutions?'],
            'q3'  => ['icon' => 'architecture',    'label' => 'Q3. CO2 - System Design & Engineering', 'desc' => 'How effectively did you formulate design architectures, engineering schematics, and component selections?'],
            'q4'  => ['icon' => 'build',           'label' => 'Q4. CO2 - Prototype Fabrication & Testing', 'desc' => 'To what extent were you able to fabricate, assemble, and iteratively test the working model or prototype?'],
            'q5'  => ['icon' => 'computer',        'label' => 'Q5. CO3 - Modern Tools Utilization', 'desc' => 'How effectively did you utilize modern simulation tools, programming languages, CAD/EDA suites, or lab test equipment?'],
            'q6'  => ['icon' => 'security',        'label' => 'Q6. CO3 - Industry Standards & Safety', 'desc' => 'How clearly were engineering safety norms, operational standards, and sustainability considerations applied in your project?'],
            'q7'  => ['icon' => 'groups',          'label' => 'Q7. CO4 - Teamwork & Task Sharing', 'desc' => 'How effectively did your project group distribute tasks, collaborate constructively, and coordinate work throughout the semester?'],
            'q8'  => ['icon' => 'schedule',        'label' => 'Q8. CO4 - Project Management & Budgeting', 'desc' => 'To what extent did you adhere to project timelines, budget constraints, and milestone reviews?'],
            'q9'  => ['icon' => 'description',     'label' => 'Q9. CO5 - Technical Documentation & Report', 'desc' => 'How thoroughly did the project enhance your ability to write technical reports, compile logs, and cite references accurately?'],
            'q10' => ['icon' => 'record_voice_over','label' => 'Q10. CO5 - Presentation, Viva Voce & Ethics', 'desc' => 'How effectively did the department reviews prepare you to present, defend your work in viva voce, and observe professional ethics?'],
          ];
        } else {
          $defaultQuestions = [
            'q1'  => ['icon' => 'menu_book',       'label' => 'Q1. CO1 - Subject Knowledge', 'desc' => 'How well did the course help you understand and remember the core academic principles, models, and structural fundamentals?'],
            'q2'  => ['icon' => 'auto_stories',    'label' => 'Q2. CO1 - Outcome Mapping',   'desc' => 'How clearly were the course objectives, scope, and basic terms aligned with the class presentations?'],
            'q3'  => ['icon' => 'analytics',       'label' => 'Q3. CO2 - Analytical Ability', 'desc' => 'How effectively did the course build your reasoning skills, mathematical derivations, or logical analysis capabilities?'],
            'q4'  => ['icon' => 'build',           'label' => 'Q4. CO2 - Design & Analysis',  'desc' => 'To what extent can you design models, troubleshoot bugs, or draft structural layouts based on class lessons?'],
            'q5'  => ['icon' => 'science',         'label' => 'Q5. CO3 - Practical Skills',  'desc' => 'How confident are you in operating laboratory kits, executing computer programs, or handling workshop machines?'],
            'q6'  => ['icon' => 'health_and_safety','label' => 'Q6. CO3 - Industry Standards', 'desc' => 'How clearly do you understand safety regulations, instrumentation limits, and standard protocols?'],
            'q7'  => ['icon' => 'assignment',      'label' => 'Q7. CO4 - Evaluation Standards','desc' => 'To what extent did assignments, written internal exams, and presentations evaluate your skills thoroughly?'],
            'q8'  => ['icon' => 'gavel',           'label' => 'Q8. CO4 - Professional Ethics', 'desc' => 'How effectively did the course emphasize engineering ethics, environmental issues, and professional conduct?'],
            'q9'  => ['icon' => 'school',          'label' => 'Q9. CO4 - Lifelong Learning',  'desc' => 'How strongly has this course inspired you to self-learn, explore external publications, or research modern field advancements?'],
            'q10' => ['icon' => 'thumb_up',        'label' => 'Q10. Overall Course Rating',  'desc' => 'Rate your overall satisfaction with the course syllabus delivery, faculty guidance, and academic outcomes.'],
          ];
        }

        $custom = json_decode($survey->custom_questions, true) ?: [];
        $questions = [];
        foreach ($defaultQuestions as $key => $val) {
            $questions[$key] = $val;
            if (isset($custom[$key]) && !empty(trim($custom[$key]))) {
                $questions[$key]['desc'] = trim($custom[$key]);
            }
        }
      @endphp

      @foreach($questions as $key => $q)
      <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl p-5 hover:border-slate-700/80 transition-premium">
        <div class="flex items-start gap-3 mb-4">
          <span class="material-symbols-rounded text-teal-400 text-xl mt-0.5">{{ $q['icon'] }}</span>
          <div>
            <h3 class="font-bold text-slate-100 text-base">{{ $q['label'] }}</h3>
            <p class="text-sm text-slate-350 mt-1.5 leading-relaxed">{{ $q['desc'] }}</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-3" id="rg_{{ $key }}">
          <div class="rating-btn border rounded-xl p-3 text-center border-rose-500/30 bg-rose-500/10" id="btn_{{ $key }}_1" onclick="selectRating('{{ $key }}', 1)">
            <div class="text-lg font-black text-slate-200">1</div>
            <div class="text-[10px] font-bold text-rose-400 mt-0.5">Low</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-amber-500/30 bg-amber-500/10" id="btn_{{ $key }}_2" onclick="selectRating('{{ $key }}', 2)">
            <div class="text-lg font-black text-slate-200">2</div>
            <div class="text-[10px] font-bold text-amber-400 mt-0.5">Medium</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-teal-500/30 bg-teal-500/10" id="btn_{{ $key }}_3" onclick="selectRating('{{ $key }}', 3)">
            <div class="text-lg font-black text-slate-200">3</div>
            <div class="text-[10px] font-bold text-teal-400 mt-0.5">High</div>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Submit Block -->
      <div class="flex justify-end gap-3 pt-4">
        <button type="button" onclick="window.close()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-bold border border-slate-700/50 transition-premium cursor-pointer">
          Cancel
        </button>
        <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold border border-teal-500/30 transition-premium shadow-lg shadow-teal-500/10 cursor-pointer">
          Submit Exit Survey
        </button>
      </div>

    </form>

  </div>

  <script>
    const ratings = { q1:0, q2:0, q3:0, q4:0, q5:0, q6:0, q7:0, q8:0, q9:0, q10:0 };

    function selectRating(qKey, value) {
      ratings[qKey] = value;
      
      // Update styling
      for (let i = 1; i <= 3; i++) {
        const btn = document.getElementById(`btn_${qKey}_${i}`);
        if (i === value) {
          btn.classList.add('selected', 'border-slate-300', 'bg-teal-500/20');
        } else {
          btn.classList.remove('selected', 'border-slate-300', 'bg-teal-500/20');
        }
      }
    }

    function submitSurvey(e) {
      e.preventDefault();
      
      // Validation
      const unanswered = Object.keys(ratings).filter(k => ratings[k] === 0);
      if (unanswered.length > 0) {
        alert("Please answer all Course Outcome questions before submitting.");
        return;
      }

      const surveyId = document.getElementById('survey_id_val').value;

      fetch('/api/student/course-exit/submit', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          survey_id: surveyId,
          q1: ratings.q1,
          q2: ratings.q2,
          q3: ratings.q3,
          q4: ratings.q4,
          q5: ratings.q5,
          q6: ratings.q6,
          q7: ratings.q7,
          q8: ratings.q8,
          q9: ratings.q9,
          q10: ratings.q10
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          window.close();
        } else {
          alert(data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert("A network error occurred. Please try again.");
      });
    }
  </script>
</body>
</html>