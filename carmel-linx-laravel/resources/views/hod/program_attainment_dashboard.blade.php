<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Program Attainment (PO & PSO) Console - NBA Criterion 3</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0b1329 0%, #030712 100%);
      color: #f1f5f9;
      min-height: 100vh;
    }
    .custom-gradient-bg {
      background: radial-gradient(circle at 10% 20%, rgba(20, 184, 166, 0.15), transparent 45%),
                  radial-gradient(circle at 90% 10%, rgba(99, 102, 241, 0.12), transparent 40%),
                  radial-gradient(circle at 50% 80%, rgba(244, 63, 94, 0.08), transparent 50%);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col custom-gradient-bg relative selection:bg-teal-500/30 selection:text-teal-200">

  <!-- Header Panel -->
  <header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur-md sticky top-0 z-40 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="/hod/report-centre" class="text-slate-400 hover:text-white transition flex items-center">
          <span class="material-symbols-rounded text-xl">arrow_back</span>
        </a>
        <div>
          <h1 class="text-base font-black tracking-tight text-white uppercase flex items-center gap-2 m-0">
            <span class="material-symbols-rounded text-teal-400 text-xl">stacked_bar_chart</span>
            Program Outcomes (PO / PSO) Attainment
          </h1>
          <p class="text-[11px] text-slate-400 font-medium m-0 flex items-center gap-2">
            <span>Cohort: <b class="text-white">{{ $classroomId }}</b></span>
            <span class="text-slate-600">•</span>
            <span>Revision: <b class="text-teal-400">{{ $revision }}</b></span>
            <span class="text-slate-600">•</span>
            <span>NBA Criterion 3</span>
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2.5">
        <button onclick="saveProgramConfig()" id="btnSaveConfig" class="px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold transition flex items-center gap-2 text-xs shadow-md border-none cursor-pointer">
          <span class="material-symbols-rounded text-sm">save</span> Save Configuration
        </button>
        <a href="/hod/program-attainment/{{ urlencode($classroomId) }}/print" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold transition flex items-center gap-2 text-xs shadow-md border border-slate-700 no-underline">
          <span class="material-symbols-rounded text-sm">print</span> Print NBA Report
        </a>
      </div>
    </div>
  </header>

  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Overview Banner -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-teal-500/20 text-teal-300 border border-teal-500/30">
            {{ $revision }} Cohort
          </span>
          <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">
            Department: {{ $branch }}
          </span>
          <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
            Weight: 80% Direct + 20% Indirect
          </span>
        </div>
        <h2 class="text-sm font-bold text-white m-0">Program Outcome Attainment Matrix & Assessment Summary</h2>
        <p class="text-xs text-slate-400 m-0">
          This console compiles all accredited courses in this cohort, evaluates their correlation with PO1–PO11 & PSO1–PSO3, integrates indirect surveys, and generates DAB gap analysis.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <div class="text-right">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Courses Aggregated</span>
          <span class="text-lg font-black text-teal-400">{{ count($subjects) }} Courses</span>
        </div>
        <div class="h-8 w-px bg-slate-800"></div>
        <div class="text-right">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Audit Status</span>
          <span class="text-sm font-black text-amber-400">{{ $programRecord->status ?? 'Draft' }}</span>
        </div>
      </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
      @foreach($allPoKeys as $poKey)
        @php
          $fin = $finalPo[$poKey] ?? ['direct' => 0, 'indirect' => 0, 'overall' => 0];
          $target = $poTargets[$poKey] ?? 2.0;
          $gap = $gapAnalysis[$poKey]['gap'] ?? 0;
          $isMet = $gapAnalysis[$poKey]['is_met'] ?? false;
        @endphp
        <div class="bg-slate-900/80 border {{ $isMet ? 'border-teal-500/30' : 'border-rose-500/40' }} rounded-xl p-3 space-y-1.5 shadow-sm">
          <div class="flex items-center justify-between">
            <span class="text-xs font-black text-white">{{ $poKey }}</span>
            <span class="text-[10px] font-bold px-1.5 py-0.2 rounded {{ $isMet ? 'bg-teal-500/20 text-teal-300' : 'bg-rose-500/20 text-rose-300' }}">
              {{ $isMet ? '✓ Met' : '⚠ Gap' }}
            </span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-base font-black text-white">{{ number_format($fin['overall'], 2) }}</span>
            <span class="text-[10px] text-slate-400">Target: {{ number_format($target, 1) }}</span>
          </div>
          <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
            <div class="{{ $isMet ? 'bg-teal-400' : 'bg-rose-400' }} h-1.5 rounded-full" style="width: {{ min(100, ($fin['overall'] / 3.0) * 100) }}%"></div>
          </div>
          <div class="flex items-center justify-between text-[9px] text-slate-500 font-mono">
            <span>D: {{ number_format($fin['direct'], 2) }}</span>
            <span>I: {{ number_format($fin['indirect'], 2) }}</span>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Table 3.1: Course-PO Articulation Matrix & Direct Attainment -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-lg">
      <div class="bg-slate-950 px-5 py-3.5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-teal-400 text-base">table_chart</span>
          <h3 class="text-xs font-black text-white uppercase tracking-wider m-0">
            Table 3.1: Course Outcomes to Program Outcomes Articulation & Direct Contribution
          </h3>
        </div>
        <span class="text-[11px] text-slate-400 font-medium">Scale: 1 (Low) to 3 (High)</span>
      </div>

      <div class="overflow-x-auto max-h-[480px]">
        <table class="w-full text-left text-xs border-collapse font-sans">
          <thead class="bg-slate-950 text-[10px] font-black text-slate-400 uppercase tracking-wider sticky top-0 z-10 border-b border-slate-800">
            <tr>
              <th class="py-2.5 px-3 whitespace-nowrap">Sem</th>
              <th class="py-2.5 px-3 whitespace-nowrap">Code</th>
              <th class="py-2.5 px-4 min-w-[200px]">Course Title</th>
              @foreach($allPoKeys as $poKey)
                <th class="py-2.5 px-2 text-center whitespace-nowrap font-mono">{{ $poKey }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 font-medium text-slate-300 text-[11px]">
            @forelse($coursesMatrix as $row)
              <tr class="hover:bg-slate-800/40 transition">
                <td class="py-2 px-3 font-mono text-slate-400">S{{ $row['subject']->semester ?? 1 }}</td>
                <td class="py-2 px-3 font-mono font-bold text-teal-300">{{ $row['subject']->subject_code }}</td>
                <td class="py-2 px-4 truncate max-w-[220px] text-slate-200" title="{{ $row['subject']->subject_name }}">
                  {{ $row['subject']->subject_name }}
                </td>
                @foreach($allPoKeys as $poKey)
                  @php
                    $val = $row['po_contributions'][$poKey]['attainment'] ?? null;
                  @endphp
                  <td class="py-2 px-2 text-center font-mono {{ $val !== null ? 'text-white font-bold bg-slate-800/30' : 'text-slate-600' }}">
                    {{ $val !== null ? number_format($val, 2) : '-' }}
                  </td>
                @endforeach
              </tr>
            @empty
              <tr>
                <td colspan="{{ 3 + count($allPoKeys) }}" class="py-6 text-center text-slate-500 italic">
                  No courses registered in this classroom yet.
                </td>
              </tr>
            @endforelse
          </tbody>
          <tfoot class="bg-slate-950/90 font-black text-xs border-t-2 border-slate-700 text-white sticky bottom-0 z-10">
            <tr>
              <td colspan="3" class="py-3 px-4 text-right uppercase tracking-wider text-teal-400 font-extrabold">
                Direct PO Attainment (Average of Courses)
              </td>
              @foreach($allPoKeys as $poKey)
                <td class="py-3 px-2 text-center font-mono text-teal-300 font-extrabold bg-teal-950/30">
                  {{ number_format($directPo[$poKey] ?? 0.0, 2) }}
                </td>
              @endforeach
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Table 3.2: Indirect Survey Scores & Final Attainment Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Left: Indirect Assessment Survey Inputs -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-400 text-base">ballot</span>
            <h3 class="text-xs font-black text-white uppercase tracking-wider m-0">
              Indirect Assessment Surveys (20% Weight)
            </h3>
          </div>
          <span class="text-[10px] text-slate-400 font-bold">1.0 to 3.0 Scale</span>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed m-0">
          Aggregated departmental feedback from Graduate Exit Survey, Alumni Survey, and Employer Feedback for the graduating batch.
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 max-h-72 overflow-y-auto pr-1">
          @foreach($allPoKeys as $poKey)
            <div class="bg-slate-950 border border-slate-800 rounded-lg p-2 space-y-1">
              <span class="text-[10px] font-black text-slate-400 block font-mono">{{ $poKey }}</span>
              <input type="number" step="0.01" min="0" max="3" id="indirect_{{ $poKey }}" 
                     value="{{ number_format($indirectSurveys[$poKey] ?? 2.4, 2) }}" 
                     class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-white font-mono text-xs text-right outline-none focus:border-indigo-400">
            </div>
          @endforeach
        </div>
      </div>

      <!-- Right: Target Setting & Gap Analysis Plan -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-amber-400 text-base">flag</span>
            <h3 class="text-xs font-black text-white uppercase tracking-wider m-0">
              Batch PO Attainment Targets (Benchmark)
            </h3>
          </div>
          <span class="text-[10px] text-slate-400 font-bold">DAB Approved</span>
        </div>
        <p class="text-xs text-slate-400 leading-relaxed m-0">
          Set benchmark targets established by Department Advisory Board (DAB) to evaluate compliance and drive continuous improvement.
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 max-h-72 overflow-y-auto pr-1">
          @foreach($allPoKeys as $poKey)
            <div class="bg-slate-950 border border-slate-800 rounded-lg p-2 space-y-1">
              <span class="text-[10px] font-black text-slate-400 block font-mono">{{ $poKey }} Target</span>
              <input type="number" step="0.1" min="0" max="3" id="target_{{ $poKey }}" 
                     value="{{ number_format($poTargets[$poKey] ?? 2.0, 1) }}" 
                     class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-white font-mono text-xs text-right outline-none focus:border-amber-400">
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Final Attainment Consolidation & Action Plan Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-lg">
      <div class="bg-slate-950 px-5 py-3.5 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-teal-400 text-base">assignment_turned_in</span>
          <h3 class="text-xs font-black text-white uppercase tracking-wider m-0">
            Table 3.3: Final Program Attainment, Gap Analysis & Continuous Improvement Action Plan
          </h3>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead class="bg-slate-950 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-800">
            <tr>
              <th class="py-2.5 px-3 font-mono">PO / PSO</th>
              <th class="py-2.5 px-4 min-w-[200px]">Outcome Title & Description</th>
              <th class="py-2.5 px-3 text-center">Direct (80%)</th>
              <th class="py-2.5 px-3 text-center">Indirect (20%)</th>
              <th class="py-2.5 px-3 text-center">Final Attainment</th>
              <th class="py-2.5 px-3 text-center">Target</th>
              <th class="py-2.5 px-3 text-center">Gap</th>
              <th class="py-2.5 px-4 min-w-[240px]">Remedial Action Plan for DAB Review</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 font-medium text-slate-300 text-[11px]">
            @foreach($allPoKeys as $poKey)
              @php
                $fin = $finalPo[$poKey] ?? ['direct' => 0, 'indirect' => 0, 'overall' => 0];
                $target = $poTargets[$poKey] ?? 2.0;
                $gap = $gapAnalysis[$poKey]['gap'] ?? 0;
                $isMet = $gapAnalysis[$poKey]['is_met'] ?? false;
                $title = $poList[$poKey]['title'] ?? ($psoList[$poKey]['title'] ?? $poKey);
                $savedAction = $programRecord->action_plans[$poKey] ?? '';
              @endphp
              <tr class="hover:bg-slate-800/30 transition">
                <td class="py-2.5 px-3 font-mono font-black text-teal-300">{{ $poKey }}</td>
                <td class="py-2.5 px-4 text-slate-300">
                  <span class="font-bold text-white block">{{ $title }}</span>
                  <span class="text-[10px] text-slate-400">{{ $poList[$poKey]['desc'] ?? ($psoList[$poKey]['desc'] ?? '') }}</span>
                </td>
                <td class="py-2.5 px-3 text-center font-mono">{{ number_format($fin['direct'], 2) }}</td>
                <td class="py-2.5 px-3 text-center font-mono text-indigo-300">{{ number_format($fin['indirect'], 2) }}</td>
                <td class="py-2.5 px-3 text-center font-mono font-black text-white bg-slate-800/40">
                  {{ number_format($fin['overall'], 2) }}
                </td>
                <td class="py-2.5 px-3 text-center font-mono font-bold text-amber-300">
                  {{ number_format($target, 1) }}
                </td>
                <td class="py-2.5 px-3 text-center font-mono font-bold {{ $isMet ? 'text-emerald-400' : 'text-rose-400' }}">
                  {{ ($gap >= 0 ? '+' : '') . number_format($gap, 2) }}
                </td>
                <td class="py-2.5 px-4">
                  <input type="text" id="action_{{ $poKey }}" value="{{ $savedAction }}" 
                         placeholder="{{ $isMet ? 'Target achieved. Continuous reinforcement.' : 'Action required: Additional practical sessions, workshops...' }}"
                         class="w-full bg-slate-950 border border-slate-800 rounded px-2 py-1 text-slate-200 text-xs outline-none focus:border-teal-400">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <script>
    async function saveProgramConfig() {
      const btn = document.getElementById('btnSaveConfig');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-sm animate-spin">refresh</span> Saving...';

      const poKeys = @json($allPoKeys);
      const indirectSurveys = {};
      const poTargets = {};
      const actionPlans = {};

      poKeys.forEach(k => {
        const indEl = document.getElementById('indirect_' + k);
        const tgtEl = document.getElementById('target_' + k);
        const actEl = document.getElementById('action_' + k);

        if (indEl) indirectSurveys[k] = parseFloat(indEl.value) || 0;
        if (tgtEl) poTargets[k] = parseFloat(tgtEl.value) || 0;
        if (actEl) actionPlans[k] = actEl.value.trim();
      });

      try {
        const res = await fetch('/hod/program-attainment/{{ urlencode($classroomId) }}/save', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            indirect_surveys: indirectSurveys,
            po_targets: poTargets,
            action_plans: actionPlans
          })
        });

        const data = await res.json();
        if (data.status === 'SUCCESS') {
          alert('Program Attainment configuration saved successfully!');
          window.location.reload();
        } else {
          alert('Error: ' + (data.message || 'Could not save configuration.'));
        }
      } catch (err) {
        alert('Network error while saving program attainment.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save Configuration';
      }
    }
  </script>
</body>
</html>
