<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Scheme - {{ $batchSubject->subject_code }} {{ $seriesNo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
            @page { size: A4 portrait; margin: 15mm 12mm 15mm 12mm; }
        }
        body { font-family: 'Times New Roman', Times, serif; background: #f4f6f8; }
        .print-page { background: white; }
        .part-header { background: #1e3a5f; color: white; padding: 4px 10px; font-weight: bold; font-size: 13px; }
        .q-block { border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 8px; overflow: hidden; }
        .q-question { background: #f8fafc; padding: 6px 10px; font-weight: bold; font-size: 13px; border-bottom: 1px solid #e2e8f0; }
        .scheme-text { padding: 8px 12px; font-size: 12px; color: #1e293b; line-height: 1.5; }
        .total-mark-badge { background: #1e3a5f; color: white; font-weight: bold; font-size: 12px; padding: 2px 8px; border-radius: 4px; float: right; }
    </style>
</head>
<body class="p-8 max-w-3xl mx-auto">
@php
if (!function_exists('getBtShort')) {
    function getBtShort($bloom) {
        $bloom = strtoupper(trim(strval($bloom)));
        if (str_contains($bloom, 'REM') || $bloom === 'L1' || $bloom === 'R') return 'R';
        if (str_contains($bloom, 'UND') || $bloom === 'L2' || $bloom === 'U') return 'U';
        if (str_contains($bloom, 'APP') || $bloom === 'L3' || $bloom === 'AP' || $bloom === 'A') return 'A';
        if (str_contains($bloom, 'ANA') || $bloom === 'L4' || $bloom === 'AN') return 'An';
        if (str_contains($bloom, 'EVA') || $bloom === 'L5' || $bloom === 'E') return 'E';
        if (str_contains($bloom, 'CRE') || $bloom === 'L6' || $bloom === 'C') return 'C';
        return $bloom;
    }
}
@endphp

    <!-- Action Bar -->
    <div class="no-print mb-6 flex items-center justify-between bg-slate-100 p-4 rounded-xl border border-slate-300">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Evaluation Scheme — {{ $seriesNo }}</h2>
            <p class="text-slate-600 text-sm">{{ $subjectType['label'] ?? '📄 Standard (Table 4.1)' }} | Max {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25) }} Marks | Strictly Confidential</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Evaluation Scheme</span>
            </button>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Close Window</span>
            </button>
        </div>
    </div>

    <div class="print-page p-8 border border-slate-300 rounded-xl shadow-sm">
        <!-- Header -->
        <div class="border-b-2 border-slate-900 pb-3 mb-4 text-center space-y-0.5">
            <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">CARMEL POLYTECHNIC COLLEGE, PUNNAPRA</h1>
            <h2 class="text-sm font-bold text-slate-700 uppercase">DEPARTMENT OF {{ strtoupper($departmentName) }}</h2>
            <h3 class="text-xs font-bold text-red-800 uppercase">⚠ STRICTLY CONFIDENTIAL — EVALUATION SCHEME ⚠</h3>
        </div>

        <!-- Metadata -->
        <div class="border border-slate-400 p-3 mb-5 rounded-lg bg-slate-50 grid grid-cols-2 gap-2 text-sm">
            <div><span class="font-bold">Subject:</span> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }}</div>
            <div><span class="font-bold">Code:</span> {{ $batchSubject->subject_code }}</div>
            <div><span class="font-bold">Batch:</span> {{ $batchName }}</div>
            <div><span class="font-bold">Academic Year:</span> 2026-2027</div>
            <div><span class="font-bold">Series Exam:</span> {{ $seriesNo }} | Max Marks: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'practical_series' ? 40 : ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25)) }}</div>
            <div><span class="font-bold">Pattern:</span> {{ $qpRecord->pattern_type === 'practical_series' ? 'Practical Series Exam (Table 3.1 Rubrics)' : ($qpRecord->pattern_type === 'table_4_2_design' ? 'Table 4.2 Design Paper' : 'Table 4.1 Standard') }}</div>
            <div><span class="font-bold">Lecturer Name:</span> {{ $lecturerName }}</div>
        </div>
 
        @php $qp = $qpRecord->qp_data ?? []; @endphp
 
        @if ($qpRecord->pattern_type === 'practical_series')
            <!-- Practical Series Scheme -->
            <div class="mb-4">
                <div class="part-header">PART A — Practical Tasks (Answer any ONE Question × 40 Marks)</div>
                <div class="text-xs text-slate-500 my-2 px-1 font-semibold italic">Grading is based on Table 3.1 Rubrics: Writeup (10M), Setup (10M), Observation (10M), Viva (5M), Record (5M).</div>
                @foreach ($qp['part_a'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">40 Marks ({{ getBtShort($q['bloom'] ?? 'L3') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <strong>Rubric Evaluation Guidelines:</strong>
                            1. Writeup & Procedure: 10 Marks
                            2. Setup & Execution: 10 Marks
                            3. Observation & Recording: 10 Marks
                            4. Viva Voce: 5 Marks
                            5. Record Book: 5 Marks
                            Total: 40 Marks
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @elseif ($qpRecord->pattern_type === 'table_4_2_design')
            <!-- Part A Scheme -->
            <div class="mb-4">
                <div class="part-header">PART A — 6 × 5 = 30 Marks</div>
                @foreach ($qp['part_a'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M ({{ getBtShort($q['bloom'] ?? 'L2') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <span class="text-slate-400 italic">No evaluation scheme provided.</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Part B Scheme -->
            <div class="mb-4">
                <div class="part-header">PART B — 2 × 10 = 20 Marks</div>
                @foreach ($qp['part_b'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M ({{ getBtShort($q['bloom'] ?? 'L4') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <span class="text-slate-400 italic">No evaluation scheme provided.</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <!-- Part A Scheme -->
            <div class="mb-4">
                <div class="part-header">PART A — 2 × 1 = 2 Marks</div>
                @foreach ($qp['part_a'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M ({{ getBtShort($q['bloom'] ?? 'L1') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <span class="text-slate-400 italic">No evaluation scheme provided.</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Part B Scheme -->
            <div class="mb-4">
                <div class="part-header">PART B — 3 × 3 = 9 Marks</div>
                @foreach ($qp['part_b'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M ({{ getBtShort($q['bloom'] ?? 'L2') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <span class="text-slate-400 italic">No evaluation scheme provided.</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Part C Scheme -->
            <div class="mb-4">
                <div class="part-header">PART C — Answer any 2 of 3 (7M each = 14 Marks)</div>
                @foreach ($qp['part_c'] ?? [] as $q)
                <div class="q-block mt-2">
                    <div class="q-question">
                        Q{{ $q['q_no'] }}: {{ $q['text'] }}
                        <span class="total-mark-badge">{{ $q['marks'] }} M ({{ getBtShort($q['bloom'] ?? 'L4') }})</span>
                    </div>
                    <div class="scheme-text whitespace-pre-line">
                        @if(!empty($q['scheme_key']))
                            {!! nl2br(e($q['scheme_key'])) !!}
                        @else
                            <span class="text-slate-400 italic">No evaluation scheme provided.</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <!-- Marks Summary -->
        <div class="border-t-2 border-slate-900 pt-3 mt-5 flex justify-between text-sm font-bold">
            @if ($qpRecord->pattern_type === 'table_4_2_design')
            <span>Part A (30M) + Part B (20M) = 50 Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span>
            @else
            <span>Part A (2M) + Part B (9M) + Part C (2 of 3 × 7 = 14M) = 25 Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span>
            @endif
            <span>Scaled CIA: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25) }}M → 10 CIA Marks</span>
        </div>

        <!-- Signature Block -->
        <div class="grid grid-cols-3 gap-8 mt-10 text-sm text-center">
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Prepared by<br><span class="text-slate-800 font-bold">{{ $lecturerName }}</span></div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Verified by HOD</div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Date: {{ date('d/m/Y') }}</div>
        </div>
    </div>
</body>
</html>
