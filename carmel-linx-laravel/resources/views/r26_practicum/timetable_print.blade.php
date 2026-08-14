<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class Timetable - {{ $classroomId }} (Sem {{ $semester }})</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background-color: #cbd5e1;
      color: #000000;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      background-color: #ffffff;
      border: 2px solid #000000;
    }
    th {
      border: 1.5px solid #000000;
      background-color: #f1f5f9;
      color: #000000;
      font-weight: 800;
    }
    td {
      border: 1.5px solid #000000;
      background-color: #ffffff;
      color: #000000;
      font-weight: 700;
    }
    .bg-day {
      background-color: #f1f5f9 !important;
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .bg-lunch {
      background-color: #e2e8f0 !important;
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .legend-card {
      border: 1.5px solid #000000;
      background-color: #ffffff;
      color: #000000;
    }
    .legend-item {
      border: 1.5px solid #000000;
      background-color: #f8fafc;
      color: #000000;
    }
    @media print {
      * {
        color: #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      .no-print {
        display: none !important;
      }
      @page {
        size: A4 landscape;
        margin: 10mm 12mm;
      }
      html, body {
        background-color: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
        height: auto !important;
        min-height: 0 !important;
        overflow: visible !important;
      }
      .page-container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 2mm 4mm !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        border: none !important;
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
      }
      .print-header {
        border-bottom: 2px solid #000000 !important;
      }
      table {
        border: 2px solid #000000 !important;
        margin-top: 2px !important;
        margin-bottom: 2px !important;
        page-break-inside: avoid !important;
      }
      th {
        border: 1.5px solid #000000 !important;
        color: #000000 !important;
        background-color: #f1f5f9 !important;
        padding: 2.5px 3px !important;
        font-size: 9.5px !important;
        font-weight: 800 !important;
      }
      td {
        border: 1.5px solid #000000 !important;
        color: #000000 !important;
        background-color: #ffffff !important;
        padding: 1.5px 3px !important;
        font-size: 9.5px !important;
        font-weight: 700 !important;
      }
      .legend-card {
        border: 1.5px solid #000000 !important;
        background-color: #ffffff !important;
        color: #000000 !important;
        padding: 3px 5px !important;
        margin-top: 2px !important;
        page-break-inside: avoid !important;
      }
      .legend-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 2px 6px !important;
      }
      .legend-item {
        border: 1.5px solid #000000 !important;
        background-color: #ffffff !important;
        padding: 1.5px 4px !important;
        border-radius: 3px !important;
      }
      .signature-footer {
        padding-top: 4px !important;
        margin-top: 2px !important;
        page-break-inside: avoid !important;
      }
      .signature-footer p {
        border-top: 1.5px solid #000000 !important;
        color: #000000 !important;
        font-weight: 800 !important;
      }
    }
  </style>
</head>
<body class="p-4 min-h-screen bg-slate-300">

  <div class="max-w-6xl mx-auto space-y-3">
    
    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print flex justify-between items-center bg-slate-900 text-white rounded-xl p-3 shadow-xl border border-slate-800">
      <div class="flex items-center space-x-3">
        <span class="px-2.5 py-1 bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded font-bold text-xs">
          PRINT PREVIEW SHEET
        </span>
        <span class="text-slate-200 text-sm font-semibold">
          {{ $classroomId }} • Semester {{ $semester }}
        </span>
      </div>
      <div class="flex items-center space-x-3">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition-all shadow-md cursor-pointer flex items-center space-x-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h14z"/></svg>
          <span>Print Timetable</span>
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-lg text-xs transition-all cursor-pointer border border-slate-700">
          Close Preview
        </button>
      </div>
    </div>

    @if(empty($timetableData))
      <div class="no-print p-2 bg-amber-50 border border-amber-300 rounded-xl text-amber-900 text-xs font-semibold text-center">
        ⚠️ <strong>Notice:</strong> Weekly timetable schedule has not been generated by HOD yet for <strong>{{ $classroomId }}</strong>. Displaying standard blank timetable template for manual layout and printing.
      </div>
    @endif

    <!-- White Paper Page Container -->
    <div class="bg-white text-black p-5 rounded-xl shadow-2xl border border-slate-300 page-container space-y-2">
      
      <!-- Centered Institutional Title Header -->
      <div class="border-b-2 border-black pb-1.5 text-center relative print-header space-y-0.5">
        <h1 class="text-xs font-black uppercase tracking-widest text-black">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2 class="text-base font-black text-black uppercase">WEEKLY CLASS TIMETABLE</h2>
        
        <!-- Metadata Strip: Branch, Sem, Year, Batch -->
        <div class="flex justify-center flex-wrap gap-x-6 gap-y-0.5 mt-1 text-[11px] font-black text-black">
          <div>Branch: <strong class="text-black font-black">{{ $fullDept }}</strong></div>
          <div>Sem: <strong class="text-black font-black">Semester {{ $semester }}</strong></div>
          <div>Year: <strong class="text-black font-black">{{ date('Y') }} - {{ date('Y') + 1 }}</strong></div>
          <div>Batch: <strong class="text-black font-black">{{ $classroomId }}</strong></div>
        </div>
      </div>

      <!-- Timetable Grid Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="bg-slate-100 text-black font-bold text-xs uppercase">
              <th class="p-1.5 w-16 bg-day text-black">Day Order</th>
              <th class="p-1.5 text-black">Period 1<br><span class="text-[8.5px] text-black font-normal">09:00 - 10:00</span></th>
              <th class="p-1.5 text-black">Period 2<br><span class="text-[8.5px] text-black font-normal">10:00 - 11:00</span></th>
              <th class="p-1.5 text-black">Period 3<br><span class="text-[8.5px] text-black font-normal">11:10 - 12:10</span></th>
              <th class="p-1.5 w-8 bg-lunch text-[8.5px] text-black">Lunch</th>
              <th class="p-1.5 text-black">Period 4<br><span class="text-[8.5px] text-black font-normal">01:00 - 02:00</span></th>
              <th class="p-1.5 text-black">Period 5<br><span class="text-[8.5px] text-black font-normal">02:00 - 03:00</span></th>
              <th class="p-1.5 text-black">Period 6<br><span class="text-[8.5px] text-black font-normal">03:00 - 04:00</span></th>
            </tr>
          </thead>
          <tbody class="text-xs">
            @php
              $days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
              $scheduledSubjects = [];
            @endphp

            @foreach($days as $idx => $day)
              @php
                $dayData = $timetableData[$day] ?? [];
                $p1 = $dayData[1] ?? ['subject' => '', 'staff' => ''];
                $p2 = $dayData[2] ?? ['subject' => '', 'staff' => ''];
                $p3 = $dayData[3] ?? ['subject' => '', 'staff' => ''];
                $p4 = $dayData[4] ?? ['subject' => '', 'staff' => ''];
                $p5 = $dayData[5] ?? ['subject' => '', 'staff' => ''];
                $p6 = $dayData[6] ?? ['subject' => '', 'staff' => ''];

                foreach([$p1, $p2, $p3, $p4, $p5, $p6] as $slot) {
                  if(!empty($slot['subject'])) {
                    $scheduledSubjects[$slot['subject']] = true;
                  }
                }
              @endphp
              <tr>
                <td class="p-1 font-black bg-day text-black uppercase text-[11px]">{{ $day }}</td>

                {{-- Forenoon Slots --}}
                @if ($p1['subject'] && $p1['subject'] === $p2['subject'] && $p2['subject'] === $p3['subject'])
                  {!! renderPrintCellHtml($p1, 3, $allocatedSubjects) !!}
                @elseif ($p1['subject'] && $p1['subject'] === $p2['subject'])
                  {!! renderPrintCellHtml($p1, 2, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p3, 1, $allocatedSubjects) !!}
                @elseif ($p2['subject'] && $p2['subject'] === $p3['subject'])
                  {!! renderPrintCellHtml($p1, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p2, 2, $allocatedSubjects) !!}
                @else
                  {!! renderPrintCellHtml($p1, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p2, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p3, 1, $allocatedSubjects) !!}
                @endif

                {{-- Lunch Break --}}
                @if($idx === 0)
                  <td rowspan="5" class="bg-lunch text-black font-extrabold text-[10px] uppercase tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                    LUNCH BREAK
                  </td>
                @endif

                {{-- Afternoon Slots --}}
                @if ($p4['subject'] && $p4['subject'] === $p5['subject'] && $p5['subject'] === $p6['subject'])
                  {!! renderPrintCellHtml($p4, 3, $allocatedSubjects) !!}
                @elseif ($p4['subject'] && $p4['subject'] === $p5['subject'])
                  {!! renderPrintCellHtml($p4, 2, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p6, 1, $allocatedSubjects) !!}
                @elseif ($p5['subject'] && $p5['subject'] === $p6['subject'])
                  {!! renderPrintCellHtml($p4, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p5, 2, $allocatedSubjects) !!}
                @else
                  {!! renderPrintCellHtml($p4, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p5, 1, $allocatedSubjects) !!}
                  {!! renderPrintCellHtml($p6, 1, $allocatedSubjects) !!}
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Subject Legend & Course Allocations (STRICT 2 COLUMNS) -->
      <div class="bg-white border-2 border-black rounded-lg p-2 space-y-1.5 legend-card">
        <h3 class="text-[10px] font-black uppercase tracking-wider text-black border-b border-black pb-0.5 text-center">
          Course Legend & Assigned Faculty List — Semester {{ $semester }}
        </h3>
        <div class="grid grid-cols-2 gap-1 text-xs legend-grid">
          @forelse($allocatedSubjects as $sub)
            @php
              $staffNames = [];
              if ($sub->staffAssignments && $sub->staffAssignments->count() > 0) {
                foreach($sub->staffAssignments as $sa) {
                  $st = $sa->staff ?: $sa->staffProfile;
                  if ($st && !empty($st->name)) {
                    $staffNames[] = $st->name;
                  }
                }
              }
              $staffDisplay = count($staffNames) > 0 ? implode(', ', $staffNames) : 'Unassigned';
            @endphp
            <div class="flex items-center space-x-1.5 p-1 bg-slate-50 rounded border border-black legend-item">
              <span class="font-mono font-black text-black w-16 text-[9.5px] shrink-0">{{ $sub->subject_code }}</span>
              <div class="flex-grow min-w-0">
                <div class="font-bold text-black text-[10px] truncate">{{ $sub->subject_name }}</div>
                <div class="text-[9px] font-bold text-black truncate">Faculty: {{ $staffDisplay }}</div>
              </div>
            </div>
          @empty
            <div class="col-span-full text-black text-xs italic text-center font-bold">No courses registered for this batch/semester yet.</div>
          @endforelse
        </div>
      </div>

      <!-- Signature Footer -->
      <div class="pt-2 grid grid-cols-3 text-center text-[9.5px] font-extrabold text-black signature-footer">
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Staff Advisor</p>
        </div>
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Head of Department</p>
        </div>
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Principal / Academic Coordinator</p>
        </div>
      </div>

    </div>

  </div>

</body>
</html>

@php
  function renderPrintCellHtml($slot, $colspan = 1, $subjectsList = []) {
    $colspanAttr = $colspan > 1 ? "colspan=\"{$colspan}\"" : "";
    if (empty($slot['subject'])) {
      return "<td {$colspanAttr} class=\"p-0.5 text-center text-slate-400 italic\">-- Free --</td>";
    }
    
    $matchedSub = is_array($subjectsList) 
      ? collect($subjectsList)->firstWhere('subject_code', $slot['subject'])
      : $subjectsList->firstWhere('subject_code', $slot['subject']);

    $subjectName = $matchedSub ? ($matchedSub->subject_name ?? '') : '';
    
    $staffDisplay = '';
    
    if ($matchedSub) {
      $assignments = DB::table('subject_staff_assignments')
          ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
          ->where('subject_staff_assignments.batch_subject_id', $matchedSub->id)
          ->select('staff_profiles.name', 'staff_profiles.designation')
          ->get();

      if ($assignments->count() > 0) {
        if ($colspan == 1) {
          // 1 Hour Slot: Show ONLY Lecturer name (filter out Demonstrators/Trade Instructors/Lab staff)
          $lecturers = $assignments->filter(function($st) {
            $d = strtolower(str_replace(['_', ' ', '-'], '', $st->designation ?? ''));
            return !str_contains($d, 'demonstrator') && 
                   !str_contains($d, 'tradeinstructor') && 
                   !str_contains($d, 'tradesman') && 
                   !str_contains($d, 'workshop') && 
                   !str_contains($d, 'lab');
          })->pluck('name')->toArray();

          $staffDisplay = count($lecturers) > 0 ? implode(', ', $lecturers) : $assignments->first()->name;
        } else {
          // More than 1 Hour Slot (Lab/Practicum Block): Show ALL assigned staff names
          $staffDisplay = implode(', ', $assignments->pluck('name')->toArray());
        }
      }
    }
    
    if (empty($staffDisplay) && !empty($slot['staff'])) {
      $rawStaff = $slot['staff'];
      if ($colspan == 1 && str_contains($rawStaff, ',')) {
        $parts = array_map('trim', explode(',', $rawStaff));
        $staffDisplay = $parts[0] ?? $rawStaff;
      } else {
        $staffDisplay = $rawStaff;
      }
    }

    return "
      <td {$colspanAttr} class=\"p-1 text-center\">
        <div style=\"font-weight: 900; font-size: 10.5px; line-height: 1.1; color: #000000;\">{$slot['subject']}</div>
        <div style=\"font-weight: 700; font-size: 9px; margin-top: 0.5px; line-height: 1.05; color: #000000;\">{$subjectName}</div>
        <div style=\"font-size: 8px; font-weight: 700; margin-top: 0.5px; line-height: 1.05; color: #000000;\">{$staffDisplay}</div>
      </td>
    ";
  }
@endphp
