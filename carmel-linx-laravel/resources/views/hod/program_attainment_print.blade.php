<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NBA Criterion 3 - Program Attainment Report ({{ $classroomId }})</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    @media print {
      @page {
        size: A4 landscape;
        margin: 10mm 10mm 10mm 10mm;
      }
      body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background: white !important;
        color: black !important;
      }
      .no-print {
        display: none !important;
      }
      .page-break {
        page-break-before: always;
      }
    }
    body {
      font-family: 'Inter', sans-serif;
      background: #f8fafc;
      color: #0f172a;
    }
  </style>
</head>
<body class="p-4 sm:p-8">

  <!-- Print Action Toolbar -->
  <div class="no-print max-w-7xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex items-center gap-3">
      <button onclick="window.history.back()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-bold text-slate-700 cursor-pointer">
        ← Back to Console
      </button>
      <span class="text-xs text-slate-500 font-medium">NBA Criterion 3 SAR Assessment Print Format</span>
    </div>
    <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-md cursor-pointer">
      🖨 Print Official Document (A4 Landscape)
    </button>
  </div>

  <!-- Document Container -->
  <div class="max-w-7xl mx-auto bg-white p-6 sm:p-8 border border-slate-300 rounded-xl shadow-md space-y-6">

    <!-- Header Section -->
    <div class="border-b-2 border-slate-900 pb-4 text-center space-y-1">
      <h1 class="text-base sm:text-lg font-black tracking-wide uppercase text-slate-950">
        Carmel Polytechnic College, Alappuzha
      </h1>
      <p class="text-xs font-semibold text-slate-700 uppercase tracking-wider">
        Department of {{ $classroom->branch ?? $classroom->department ?? 'Engineering' }}
      </p>
      <h2 class="text-xs sm:text-sm font-black text-slate-900 uppercase pt-1">
        NBA Criterion 3: Program Outcomes (PO) and Program Specific Outcomes (PSO) Attainment Report
      </h2>
      <div class="flex justify-center items-center gap-6 pt-1 text-[11px] font-bold text-slate-600">
        <span>Cohort: <b>{{ $classroomId }}</b></span>
        <span>•</span>
        <span>Revision: <b>{{ $revision }}</b></span>
        <span>•</span>
        <span>Batch Year: <b>{{ $classroom->batch_year ?? '2024' }}</b></span>
        <span>•</span>
        <span>Weighting: <b>80% Direct + 20% Indirect</b></span>
      </div>
    </div>

    <!-- Table 3.1: Course-PO Articulation Matrix & Direct Contributions -->
    <div class="space-y-2">
      <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider">
        Table 3.1.2: PO & PSO Attainment from All Courses (Direct Assessment)
      </h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-[10px] border border-slate-300 border-collapse">
          <thead class="bg-slate-100 text-slate-900 font-black border-b border-slate-300">
            <tr>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-10">Sem</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-16">Code</th>
              <th class="border border-slate-300 py-1.5 px-3 min-w-[150px]">Course Title</th>
              @foreach($allPoKeys as $poKey)
                <th class="border border-slate-300 py-1.5 px-1.5 text-center font-mono w-10">{{ $poKey }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach($coursesMatrix as $row)
              <tr>
                <td class="border border-slate-300 py-1 px-2 text-center font-mono">S{{ $row['subject']->semester ?? 1 }}</td>
                <td class="border border-slate-300 py-1 px-2 text-center font-mono font-bold">{{ $row['subject']->subject_code }}</td>
                <td class="border border-slate-300 py-1 px-3 truncate max-w-[200px]">{{ $row['subject']->subject_name }}</td>
                @foreach($allPoKeys as $poKey)
                  @php
                    $val = $row['po_contributions'][$poKey]['attainment'] ?? null;
                  @endphp
                  <td class="border border-slate-300 py-1 px-1.5 text-center font-mono {{ $val !== null ? 'font-bold' : 'text-slate-400' }}">
                    {{ $val !== null ? number_format($val, 2) : '-' }}
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-slate-100 font-black text-[10px] text-slate-950 border-t-2 border-slate-900">
            <tr>
              <td colspan="3" class="border border-slate-300 py-2 px-3 text-right uppercase">
                Direct PO Attainment (Average Across Courses)
              </td>
              @foreach($allPoKeys as $poKey)
                <td class="border border-slate-300 py-2 px-1.5 text-center font-mono font-black text-indigo-900 bg-indigo-50">
                  {{ number_format($directPo[$poKey] ?? 0.0, 2) }}
                </td>
              @endforeach
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Table 3.2: Overall Attainment, Gap Analysis & DAB Action Plan -->
    <div class="space-y-2 pt-2">
      <h3 class="text-xs font-black uppercase text-slate-900 tracking-wider">
        Table 3.3: PO & PSO Final Attainment, Gap Analysis & Continuous Improvement Plan
      </h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-[10px] border border-slate-300 border-collapse">
          <thead class="bg-slate-100 text-slate-900 font-black border-b border-slate-300">
            <tr>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-12">PO / PSO</th>
              <th class="border border-slate-300 py-1.5 px-3 min-w-[200px]">Program Outcome Definition</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-16">Direct (80%)</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-16">Indirect (20%)</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-16 bg-slate-200">Final</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-14">Target</th>
              <th class="border border-slate-300 py-1.5 px-2 text-center w-14">Gap</th>
              <th class="border border-slate-300 py-1.5 px-3 min-w-[220px]">Continuous Improvement / Action Plan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @foreach($allPoKeys as $poKey)
              @php
                $fin = $finalPo[$poKey] ?? ['direct' => 0, 'indirect' => 0, 'overall' => 0];
                $target = $poTargets[$poKey] ?? 2.0;
                $gap = $gapAnalysis[$poKey]['gap'] ?? 0;
                $isMet = $gapAnalysis[$poKey]['is_met'] ?? false;
                $action = $programRecord->action_plans[$poKey] ?? ($isMet ? 'Target achieved. Continuous monitoring.' : 'Remedial workshops and problem-solving tutorial sessions scheduled.');
              @endphp
              <tr>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono font-bold">{{ $poKey }}</td>
                <td class="border border-slate-300 py-1.5 px-3">
                  <b class="text-slate-900">{{ $poList[$poKey]['title'] ?? ($psoList[$poKey]['title'] ?? $poKey) }}:</b>
                  <span class="text-slate-600">{{ $poList[$poKey]['desc'] ?? ($psoList[$poKey]['desc'] ?? '') }}</span>
                </td>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono">{{ number_format($fin['direct'], 2) }}</td>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono">{{ number_format($fin['indirect'], 2) }}</td>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono font-black bg-slate-100">{{ number_format($fin['overall'], 2) }}</td>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono">{{ number_format($target, 1) }}</td>
                <td class="border border-slate-300 py-1.5 px-2 text-center font-mono font-bold {{ $isMet ? 'text-green-700' : 'text-red-600' }}">
                  {{ ($gap >= 0 ? '+' : '') . number_format($gap, 2) }}
                </td>
                <td class="border border-slate-300 py-1.5 px-3 text-slate-700">
                  {{ $action }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Endorsements / Sign-off Footer -->
    <div class="pt-8 grid grid-cols-3 gap-8 text-center text-xs font-bold text-slate-800">
      <div class="space-y-12">
        <p>Prepared By</p>
        <p class="border-t border-slate-400 pt-1">NBA Department Coordinator</p>
      </div>
      <div class="space-y-12">
        <p>Verified By</p>
        <p class="border-t border-slate-400 pt-1">Head of Department (HOD)</p>
      </div>
      <div class="space-y-12">
        <p>Approved By</p>
        <p class="border-t border-slate-400 pt-1">Principal / Academic Head</p>
      </div>
    </div>

  </div>

</body>
</html>
