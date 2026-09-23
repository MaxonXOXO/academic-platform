<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Self-Learning Activity-Wise Splitup Report - {{ $batchSubject->subject_code }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
            @page { size: A4 landscape; margin: 10mm; }
        }
    </style>
</head>
<body class="p-8 max-w-7xl mx-auto bg-slate-50 text-slate-900">

    <!-- Print Button Bar -->
    <div class="no-print mb-6 flex items-center justify-between bg-white p-4 rounded-xl border border-slate-300 shadow-sm">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Self-Learning Activity-Wise Splitup Report (CA - 5 CIA Marks)</h2>
            <p class="text-slate-600 text-xs">Official printable splitup report of all assigned assessment activities per student.</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Splitup Report</span>
            </button>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Close Window</span>
            </button>
        </div>
    </div>

    <!-- College Header -->
    <div class="border-b-2 border-slate-900 pb-3 mb-5 text-center space-y-1">
        <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">CARMEL POLYTECHNIC COLLEGE, PUNNAPRA</h1>
        <h2 class="text-sm font-bold text-slate-700 uppercase">DEPARTMENT OF {{ strtoupper($departmentName) }}</h2>
        <h3 class="text-xs font-bold text-blue-900 uppercase">CONTINUOUS ASSESSMENT (CA) - SELF-LEARNING ACTIVITY-WISE SPLITUP REPORT</h3>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 gap-4 border border-slate-300 p-3 rounded-lg bg-white mb-5 text-xs">
        <div class="space-y-1">
            <p><strong>Institution:</strong> Carmel Polytechnic College, Punnapra</p>
            <p><strong>Department Name:</strong> Department of {{ $departmentName }}</p>
            <p><strong>Batch Name:</strong> {{ $batchName }}</p>
            <p><strong>Subject Name & Code:</strong> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</p>
        </div>
        <div class="space-y-1 text-right">
            <p><strong>Syllabus Revision:</strong> {{ $batchSubject->syllabus_revision_code ?? 'Revision 2026 Practicum' }}</p>
            <p><strong>Lecturer Name:</strong> {{ $lecturerName }}</p>
            <p><strong>Assessment Year:</strong> {{ $batchYear . ' - ' . ($batchYear + 1) }}</p>
            <p><strong>Generated Date:</strong> {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Splitup Table -->
    <table class="w-full border-collapse border border-slate-400 text-left text-xs mb-10">
        <thead>
            <tr class="bg-slate-200 text-slate-900 font-bold border-b border-slate-400">
                <th class="border border-slate-400 p-2 text-center w-10">Roll</th>
                <th class="border border-slate-400 p-2 w-28">SBTE Reg No</th>
                <th class="border border-slate-400 p-2">Student Name</th>
                <th class="border border-slate-400 p-2 text-center">CO1 Splitup (/15)</th>
                <th class="border border-slate-400 p-2 text-center">CO2 Splitup (/15)</th>
                <th class="border border-slate-400 p-2 text-center">CO3 Splitup (/15)</th>
                <th class="border border-slate-400 p-2 text-center">CO4 Splitup (/15)</th>
                <th class="border border-slate-400 p-2 text-center w-24">Avg Raw (/15)</th>
                <th class="border border-slate-400 p-2 text-center w-24">CA Score (/5M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $st)
            @php
                $rNo = $st->reg_no;
                $split = $slStudentSplitup[$rNo] ?? [];
                $co1Str = collect($split['CO1'] ?? [])->map(fn($v, $k) => ucfirst($k).': '.$v)->join(', ');
                $co2Str = collect($split['CO2'] ?? [])->map(fn($v, $k) => ucfirst($k).': '.$v)->join(', ');
                $co3Str = collect($split['CO3'] ?? [])->map(fn($v, $k) => ucfirst($k).': '.$v)->join(', ');
                $co4Str = collect($split['CO4'] ?? [])->map(fn($v, $k) => ucfirst($k).': '.$v)->join(', ');

                $allVals = collect($split)->flatMap(fn($item) => array_values($item));
                $avgRaw = $allVals->count() > 0 ? $allVals->avg() : 0.0;
                $caScore = min(5.0, ($avgRaw / 15.0) * 5.0);
            @endphp
            <tr class="border-b border-slate-300">
                <td class="border border-slate-300 p-1.5 text-center font-bold">{{ $st->roll_no }}</td>
                <td class="border border-slate-300 p-1.5 font-mono text-emerald-800 font-bold">{{ $st->sbte_reg_no ?: $st->reg_no }}</td>
                <td class="border border-slate-300 p-1.5 font-bold text-slate-900">{{ $st->name }}</td>
                <td class="border border-slate-300 p-1.5 text-[11px]">{{ $co1Str ?: '-' }}</td>
                <td class="border border-slate-300 p-1.5 text-[11px]">{{ $co2Str ?: '-' }}</td>
                <td class="border border-slate-300 p-1.5 text-[11px]">{{ $co3Str ?: '-' }}</td>
                <td class="border border-slate-300 p-1.5 text-[11px]">{{ $co4Str ?: '-' }}</td>
                <td class="border border-slate-300 p-1.5 text-center font-bold">{{ number_format($avgRaw, 2) }}</td>
                <td class="border border-slate-300 p-1.5 text-center font-extrabold text-emerald-900">{{ number_format($caScore, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="grid grid-cols-3 gap-8 pt-8 text-center font-bold text-xs">
        <div><div class="border-t border-slate-800 pt-2">Faculty In-Charge</div></div>
        <div><div class="border-t border-slate-800 pt-2">Course Coordinator</div></div>
        <div><div class="border-t border-slate-800 pt-2">Head of Department (HOD)</div></div>
    </div>
</body>
</html>
