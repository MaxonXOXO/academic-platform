<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series Exam QP - {{ $batchSubject->subject_code }} {{ $seriesNo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 13px; }
            @page { size: A4 portrait; margin: 18mm 15mm 18mm 15mm; }
        }
        body { font-family: 'Times New Roman', Times, serif; background: #f4f6f8; }
        .print-page { background: white; }
        .part-header { background: #1e293b; color: white; padding: 4px 10px; font-weight: bold; font-size: 13px; letter-spacing: 0.5px; }
        .q-row { border-bottom: 1px solid #e2e8f0; padding: 8px 4px; }
        .q-no { font-weight: bold; color: #1e293b; min-width: 60px; }
        .bloom-badge { font-size: 10px; background: #e2e8f0; color: #475569; padding: 1px 5px; border-radius: 4px; }
        .co-badge { font-size: 10px; background: #dbeafe; color: #1e40af; padding: 1px 5px; border-radius: 4px; }
        .marks-col { min-width: 50px; text-align: right; font-weight: bold; color: #1e293b; }
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

    <!-- Action Bar (No Print) -->
    <div class="no-print mb-6 flex items-center justify-between bg-slate-100 p-4 rounded-xl border border-slate-300">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Series Exam Question Paper — {{ $seriesNo }}</h2>
            <p class="text-slate-600 text-sm">{{ $subjectType['label'] ?? '📄 Standard (Table 4.1)' }} | Max {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25) }} Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Question Paper</span>
            </button>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Close Window</span>
            </button>
        </div>
    </div>

    <div class="print-page p-8 border border-slate-300 rounded-xl shadow-sm">
        <!-- Institution Header -->
        <div class="border-b-2 border-slate-900 pb-3 mb-4 text-center space-y-0.5">
            <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">CARMEL POLYTECHNIC COLLEGE, PUNNAPRA</h1>
            <h2 class="text-sm font-bold text-slate-700 uppercase">DEPARTMENT OF {{ strtoupper($departmentName) }}</h2>
        </div>

        <!-- Exam Title Block -->
        <div class="border border-slate-400 p-3 mb-4 rounded-lg bg-slate-50">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="font-bold">Subject:</span> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }}</div>
                <div><span class="font-bold">Subject Code:</span> {{ $batchSubject->subject_code }}</div>
                <div><span class="font-bold">Batch / Semester:</span> {{ $batchName }}</div>
                <div><span class="font-bold">Academic Year:</span> 2026-2027</div>
                <div><span class="font-bold">Series Exam:</span> {{ $seriesNo }}</div>
                <div><span class="font-bold">Date:</span> {{ date('d/m/Y') }}</div>
                <div class="flex gap-6"><span><span class="font-bold">Duration:</span> {{ ($qpRecord->pattern_type === 'practical_series' || str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span> <span><span class="font-bold">Max Marks:</span> {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'practical_series' ? 40 : ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25)) }}</span></div>
            </div>
        </div>
 
        @php $qp = $qpRecord->qp_data ?? []; @endphp
 
        @if ($qpRecord->pattern_type === 'practical_series')
            {{-- PRACTICAL SERIES EXAM PATTERN --}}
            <div class="mb-4">
                <div class="part-header">PART A &nbsp;|&nbsp; Answer any ONE Question &nbsp;|&nbsp; (1 × 40 = 40 Marks)</div>
                <div class="text-xs text-slate-500 my-2 px-1 font-semibold italic">Grading is based on Table 3.1 Rubrics: Writeup & Procedure (10M), Setup & Execution (10M), Observation & Result (10M), Viva Voce (5M), Record Book (5M).</div>
                <table class="w-full text-sm mt-1">
                    @foreach ($qp['part_a'] ?? [] as $q)
                    <tr class="q-row">
                        <td class="q-no pr-3">{{ $q['q_no'] }}.</td>
                        <td class="py-2">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L3') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </td>
                        <td class="marks-col pl-3">40M</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        @elseif ($qpRecord->pattern_type === 'table_4_2_design')
            {{-- TABLE 4.2 DESIGN PAPER PATTERN --}}
            <!-- Part A -->
            <div class="mb-4">
                <div class="part-header">PART A &nbsp;|&nbsp; Answer ALL Questions &nbsp;|&nbsp; (6 × 5 = 30 Marks)</div>
                <table class="w-full text-sm mt-1">
                    @foreach ($qp['part_a'] ?? [] as $q)
                    <tr class="q-row">
                        <td class="q-no pr-3">{{ $q['q_no'] }}.</td>
                        <td class="py-1">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L2') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </td>
                        <td class="marks-col pl-3">{{ $q['marks'] }}M ({{ getBtShort($q['bloom'] ?? 'L2') }})</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <!-- Part B -->
            <div class="mb-4">
                <div class="part-header">PART B &nbsp;|&nbsp; Answer ANY ONE from EACH Set &nbsp;|&nbsp; (2 × 10 = 20 Marks)</div>
                @php $choiceGroups = collect($qp['part_b'] ?? [])->groupBy('choice_group'); @endphp
                @foreach ($choiceGroups as $setName => $qs)
                <div class="border border-slate-200 rounded-lg mt-2 mb-1">
                    @if(trim($setName) !== '')
                    <div class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-t-lg">{{ $setName }}</div>
                    @endif
                    @foreach ($qs as $q)
                    <div class="q-row flex items-start px-3">
                        <div class="q-no pr-3">{{ $q['q_no'] }}.</div>
                        <div class="flex-1 py-1">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L4') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </div>
                        <div class="marks-col pl-3">{{ $q['marks'] }}M ({{ getBtShort($q['bloom'] ?? 'L4') }})</div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

        @else
            {{-- TABLE 4.1 STANDARD PATTERN --}}
            <!-- Part A -->
            <div class="mb-4">
                <div class="part-header">PART A &nbsp;|&nbsp; Answer ALL Questions &nbsp;|&nbsp; (2 × 1 = 2 Marks)</div>
                <table class="w-full text-sm mt-1">
                    @foreach ($qp['part_a'] ?? [] as $q)
                    <tr class="q-row">
                        <td class="q-no pr-3">{{ $q['q_no'] }}.</td>
                        <td class="py-1">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L1') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </td>
                        <td class="marks-col pl-3">{{ $q['marks'] }}M ({{ getBtShort($q['bloom'] ?? 'L1') }})</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <!-- Part B -->
            <div class="mb-4">
                <div class="part-header">PART B &nbsp;|&nbsp; Answer ALL Questions &nbsp;|&nbsp; (3 × 3 = 9 Marks)</div>
                <table class="w-full text-sm mt-1">
                    @foreach ($qp['part_b'] ?? [] as $q)
                    <tr class="q-row">
                        <td class="q-no pr-3">{{ $q['q_no'] }}.</td>
                        <td class="py-1">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L2') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </td>
                        <td class="marks-col pl-3">{{ $q['marks'] }}M ({{ getBtShort($q['bloom'] ?? 'L2') }})</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <!-- Part C -->
            <div class="mb-4">
                <div class="part-header">PART C &nbsp;|&nbsp; Answer ANY TWO out of THREE Questions &nbsp;|&nbsp; (2 × 7 = 14 Marks)</div>
                @php $choiceGroups = collect($qp['part_c'] ?? [])->groupBy('choice_group'); @endphp
                @foreach ($choiceGroups as $setName => $qs)
                <div class="border border-slate-200 rounded-lg mt-2 mb-1">
                    @if(trim($setName) !== '')
                    <div class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-t-lg">{{ $setName }}</div>
                    @endif
                    @foreach ($qs as $q)
                    <div class="q-row flex items-start px-3">
                        <div class="q-no pr-3">{{ $q['q_no'] }}.</div>
                        <div class="flex-1 py-1">{{ $q['text'] }}
                            <span class="ml-2 bloom-badge">{{ getBtShort($q['bloom'] ?? 'L4') }}</span>
                            <span class="ml-1 co-badge">{{ $q['co'] ?? '' }}</span>
                        </div>
                        <div class="marks-col pl-3">{{ $q['marks'] }}M ({{ getBtShort($q['bloom'] ?? 'L4') }})</div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4 border-t-2 border-slate-900 pt-3 flex justify-between text-sm font-bold">
            <span>Pattern: {{ $qpRecord->pattern_type === 'table_4_2_design' ? 'Table 4.2 Design Paper' : 'Table 4.1 — Single CO Internal Test' }}</span>
            <span>Total: {{ $qpRecord->max_marks ?? ($qpRecord->pattern_type === 'table_4_2_design' ? 50 : 25) }} Marks | {{ (str_contains($qpRecord->co_tag ?? '', '+') || str_contains($qpRecord->co_tag ?? '', ',')) ? '3 Hours' : '1 Hour' }}</span>
        </div>

        <!-- Signature Block -->
        <div class="grid grid-cols-3 gap-8 mt-10 text-sm text-center">
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Lecturer Name<br><span class="text-slate-800 font-bold">{{ $lecturerName }}</span></div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Verified by HOD</div>
            <div class="border-t border-slate-500 pt-2 font-medium text-slate-600">Date: {{ date('d/m/Y') }}</div>
        </div>
    </div>
</body>
</html>
