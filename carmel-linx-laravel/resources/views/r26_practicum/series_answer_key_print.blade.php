<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Key — {{ $batchSubject->subject_code }} {{ $seriesNo }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            @page { size: A4 portrait; margin: 15mm 12mm 18mm 12mm; }
            body { font-size: 12px; }
        }
        body { font-family: 'Times New Roman', Times, serif; background: #f4f6f8; }
        .print-page { background: white; }
        .sec-head { background: #7c2d12; color: white; padding: 5px 12px; font-weight: bold; font-size: 13px; letter-spacing: 0.5px; }
        .q-card { border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 10px; overflow: hidden; }
        .q-title { background: #fff7ed; padding: 6px 12px; font-weight: bold; font-size: 13px; border-bottom: 1px solid #fed7aa; }
        .key-text { padding: 6px 14px; font-size: 12px; color: #1c1917; line-height: 1.6; }
        .scheme-bar { background: #f0fdf4; border-left: 4px solid #22c55e; padding: 4px 10px; font-size: 11px; color: #15803d; font-weight: 600; }
        .marks-badge { background: #7c2d12; color: white; font-weight: bold; font-size: 11px; padding: 2px 7px; border-radius: 4px; float: right; }
        .bloom-chip { font-size: 10px; background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 3px; }
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
    <div class="no-print mb-6 flex items-center justify-between bg-red-50 p-4 rounded-xl border border-red-200">
        <div>
            <h2 class="font-bold text-red-900 text-lg">⚠ Answer Key & Evaluation Scheme — {{ $seriesNo }}</h2>
            <p class="text-red-700 text-sm">STRICTLY CONFIDENTIAL — For Internal Use Only</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Answer Key</span>
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
            <div class="bg-red-800 text-white text-xs font-bold py-1 px-4 inline-block rounded mt-1 tracking-widest">
                ⚠ ANSWER KEY &amp; EVALUATION SCHEME — STRICTLY CONFIDENTIAL
            </div>
        </div>

        <!-- Metadata -->
        <div class="border border-slate-400 p-3 mb-5 rounded-lg bg-slate-50 grid grid-cols-2 gap-2 text-sm">
            <div><span class="font-bold">Subject:</span> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }}</div>
            <div><span class="font-bold">Code:</span> {{ $batchSubject->subject_code }}</div>
            <div><span class="font-bold">Batch:</span> {{ $batchName }}</div>
            <div><span class="font-bold">Series Exam:</span> {{ $seriesNo }} | Max: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'practical_series' ? 40 : ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25)) }} Marks</div>
            <div><span class="font-bold">Pattern:</span> {{ $qpRecord->pattern_type === 'practical_series' ? 'Practical Series Exam (Table 3.1 Rubrics)' : ($qpRecord->pattern_type === 'table_4_2_design' ? 'Table 4.2 Design Paper' : 'Table 4.1 Standard') }}</div>
            <div><span class="font-bold">Prepared by:</span> {{ $lecturerName }}</div>
        </div>
 
        @php
            $qp  = $qpRecord->qp_data ?? [];
            $key = $qpRecord->answer_key ?? [];
            // Merge scheme_key and answer_key into question-level lookup
            $keyLookup = [];
            foreach (['part_a','part_b','part_c'] as $pt) {
                foreach ($key[$pt] ?? [] as $kq) {
                    $keyLookup[$kq['q_no'] ?? ''] = $kq;
                }
            }
        @endphp
 
        @foreach ([
            'part_a' => $qp['part_a'] ?? [],
            'part_b' => $qp['part_b'] ?? [],
            'part_c' => $qp['part_c'] ?? [],
        ] as $partKey => $questions)
 
        @if(!empty($questions))
 
        @php
            $partLabel = match($partKey) {
                'part_a' => $qpRecord->pattern_type === 'practical_series' ? 'PART A — Practical Tasks (Answer any ONE Question × 40 Marks)' : ($qpRecord->pattern_type === 'table_4_2_design' ? 'PART A — 6 × 5 = 30 Marks' : 'PART A — 4 × 1 = 4 Marks'),
                'part_b' => $qpRecord->pattern_type === 'table_4_2_design' ? 'PART B — 2 × 10 = 20 Marks' : 'PART B — 6 × 3 = 18 Marks',
                'part_c' => 'PART C — 4 × 7 = 28 Marks',
                default  => strtoupper($partKey),
            };
        @endphp

        <div class="mb-5">
            <div class="sec-head">{{ $partLabel }}</div>
            @foreach($questions as $q)
            @php
                $qNo   = $q['q_no'] ?? '';
                $kData = $keyLookup[$qNo] ?? $q;
                $schemeKey = $kData['scheme_key'] ?? ($q['scheme_key'] ?? null);
                $answerKey = $kData['answer_key'] ?? ($q['answer_key'] ?? null);
            @endphp
            <div class="q-card mt-2">
                <div class="q-title">
                    Q{{ $qNo }}: {{ $q['text'] ?? '' }}
                    <span class="marks-badge">{{ $q['marks'] ?? 0 }} M ({{ getBtShort($q['bloom'] ?? 'L2') }})</span>
                    <span class="bloom-chip ml-2">{{ getBtShort($q['bloom'] ?? 'L2') }}</span>
                    @if(!empty($q['choice_group']))<span class="text-xs text-slate-500 ml-2">({{ $q['choice_group'] }})</span>@endif
                </div>

                @if($schemeKey)
                <div class="scheme-bar">📋 Mark Scheme: {{ $schemeKey }}</div>
                @endif

                @if($answerKey)
                <div class="key-text">
                    <strong class="text-orange-800">Model Answer:</strong><br>
                    {!! nl2br(e($answerKey)) !!}
                </div>
                @else
                <div class="key-text text-slate-400 italic">No model answer provided.</div>
                @endif
            </div>
            @endforeach
        </div>

        @endif
        @endforeach

        <!-- Total -->
        <div class="border-t-2 border-slate-900 pt-3 mt-4 flex justify-between text-sm font-bold text-slate-800">
            @if($qpRecord->pattern_type === 'practical_series')
            <span>PART A (Practical Tasks) = 40 Marks | 3 Hours</span>
            @elseif($qpRecord->pattern_type === 'table_4_2_design')
            <span>Part A (30M) + Part B (20M) = 50 Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span>
            @else
            <span>Part A (2M) + Part B (9M) + Part C (2 of 3 × 7 = 14M) = 25 Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span>
            @endif
            <span>Scaled CIA Mark: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'practical_series' ? 40 : ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25)) }}M → 10 CIA Marks</span>
        </div>

        <!-- Signature -->
        <div class="grid grid-cols-3 gap-8 mt-10 text-sm text-center">
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Prepared by<br><span class="text-slate-800 font-bold">{{ $lecturerName }}</span></div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Verified by HOD</div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Date: {{ date('d/m/Y') }}</div>
        </div>

    </div>
</body>
</html>
