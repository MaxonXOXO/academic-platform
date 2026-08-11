<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practicum Lesson Planner ({{ $practicumCourseFile->contact_hours ?? 90 }} Hours) - {{ $batchSubject->subject_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            color: #1e293b;
            font-size: 0.8125rem !important; /* 13px compact font for dense 90-hour lesson plan print */
        }
        table td, table th {
            padding: 0.35rem 0.5rem !important;
            font-size: 0.8125rem !important;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            @page { size: A4 portrait; margin: 10mm; }
        }
    </style>
</head>
<body class="p-8 max-w-6xl mx-auto">

    <!-- Print Button -->
    <div class="no-print mb-6 flex items-center justify-between bg-slate-100 p-4 rounded-xl border border-slate-300">
        <div>
            <h2 class="font-bold text-slate-800 text-lg">Practicum Combined Lesson Planner ({{ $practicumCourseFile->contact_hours ?? 90 }} Hours Schedule)</h2>
            <p class="text-slate-600 text-sm">Official print-ready course plan covering Theory (L), Practical (P), and Series Tests (ST/SP).</p>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Lesson Plan</span>
            </button>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg shadow transition-all flex items-center space-x-2">
                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Close Window</span>
            </button>
        </div>
    </div>

    <!-- Header -->
    <div class="border-b-2 border-slate-900 pb-3 mb-5 text-center space-y-1">
        <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">CARMEL POLYTECHNIC COLLEGE, PUNNAPRA</h1>
        <h2 class="text-sm font-bold text-slate-700 uppercase">DEPARTMENT OF {{ strtoupper($departmentName) }}</h2>
        <h3 class="text-xs font-bold text-blue-900 uppercase">PRACTICUM COMBINED LESSON PLANNER ({{ $practicumCourseFile->contact_hours ?? 90 }} HOURS SCHEDULE)</h3>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 gap-4 border border-slate-300 p-3.5 rounded-lg bg-slate-50 mb-6 text-xs">
        <div class="space-y-1">
            <div><span class="font-bold">Institution:</span> Carmel Polytechnic College, Punnapra</div>
            <div><span class="font-bold">Department:</span> Department of {{ $departmentName }}</div>
            <div><span class="font-bold">Batch Name:</span> {{ $batchName }}</div>
            <div><span class="font-bold">Subject Name & Code:</span> {{ $practicumCourseFile->course_title ?: $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</div>
        </div>
        <div class="space-y-1">
            <div><span class="font-bold">Syllabus Revision:</span> {{ $batchSubject->syllabus_revision_code ?? 'Revision 2026 Practicum' }}</div>
            <div><span class="font-bold">Lecturer Name:</span> {{ $lecturerName }}</div>
            <div><span class="font-bold">Assessment Academic Year:</span> {{ $batchYear . ' - ' . ($batchYear + 1) }}</div>
            <div><span class="font-bold">Generated Date:</span> {{ date('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Lesson Plan Table -->
    <table class="w-full border-collapse border border-slate-400 text-left text-sm mb-12">
        <thead>
            <tr class="bg-slate-200 text-slate-900 font-bold border-b border-slate-400">
                <th class="border border-slate-400 p-2.5 text-center w-12">Day / Hr</th>
                <th class="border border-slate-400 p-2.5 w-32">Pedagogy</th>
                <th class="border border-slate-400 p-2.5 w-28">Proposed Date</th>
                <th class="border border-slate-400 p-2.5 w-28">Actual Date</th>
                <th class="border border-slate-400 p-2.5">Topic & Content Description</th>
                <th class="border border-slate-400 p-2.5 text-center w-16">CO</th>
                <th class="border border-slate-400 p-2.5 w-28">Sub-Batch</th>
                <th class="border border-slate-400 p-2.5 text-center w-24">Hours Needed</th>
                <th class="border border-slate-400 p-2.5 w-28">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lessonPlans as $plan)
            <tr class="border-b border-slate-300">
                <td class="border border-slate-300 p-2 text-center font-bold">{{ $plan->day_no }}</td>
                <td class="border border-slate-300 p-2 font-semibold">
                    {{ $plan->pedagogy ?? ($plan->mode === 'L' ? 'Lecture (L)' : ($plan->mode === 'P' ? 'Practical Lab (P)' : ($plan->mode === 'ST' ? 'Theory Series (ST)' : 'Lab Series (SP)'))) }}
                </td>
                <td class="border border-slate-300 p-2">{{ $plan->proposed_date }}</td>
                <td class="border border-slate-300 p-2">{{ $plan->actual_date }}</td>
                <td class="border border-slate-300 p-2 font-medium">{{ $plan->topic_content }}</td>
                <td class="border border-slate-300 p-2 text-center font-bold text-slate-800">{{ $plan->co_id }}</td>
                <td class="border border-slate-300 p-2">{{ $plan->sub_batch ?? 'Batch A & B' }}</td>
                <td class="border border-slate-300 p-2 text-center font-bold">
                    {{ in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)) ? '3 Hours' : '1 Hour' }}
                </td>
                <td class="border border-slate-300 p-2 text-slate-600">{{ $plan->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="grid grid-cols-3 gap-8 pt-12 text-center font-bold text-sm">
        <div>
            <div class="border-t border-slate-800 pt-2">Faculty In-Charge</div>
        </div>
        <div>
            <div class="border-t border-slate-800 pt-2">Course Coordinator</div>
        </div>
        <div>
            <div class="border-t border-slate-800 pt-2">Head of Department (HOD)</div>
        </div>
    </div>

</body>
</html>
