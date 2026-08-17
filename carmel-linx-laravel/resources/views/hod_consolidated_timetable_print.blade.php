<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consolidated Timetable - {{ $department }}</title>
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
      padding: 3px;
      font-size: 9px;
    }
    td {
      border: 1.5px solid #000000;
      background-color: #ffffff;
      color: #000000;
      font-weight: 700;
      padding: 2px;
      font-size: 8.5px;
    }
    .day-cell {
      background-color: #f1f5f9 !important;
      color: #000000 !important;
      font-weight: 900 !important;
    }
    .batch-cell {
      background-color: #f8fafc !important;
      color: #000000 !important;
      font-weight: 800 !important;
    }
    .lunch-cell {
      background-color: #e2e8f0 !important;
      color: #000000 !important;
      font-weight: 900 !important;
    }
    .free-period {
      color: #000000 !important;
      font-style: italic;
    }

    /* Print (Light Mode) - STRICT SINGLE A4 LANDSCAPE PAGE */
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
        padding: 2px 3px !important;
        font-size: 9px !important;
        font-weight: 800 !important;
      }
      td {
        border: 1.5px solid #000000 !important;
        color: #000000 !important;
        background-color: #ffffff !important;
        padding: 1.5px 2px !important;
        font-size: 8.5px !important;
        font-weight: 700 !important;
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

  <div class="max-w-7xl mx-auto space-y-3">
    
    <!-- Top Floating Action Bar (Screen Only) -->
    <div class="no-print flex justify-between items-center bg-slate-900 text-white rounded-xl p-3 shadow-xl border border-slate-800">
      <div class="flex items-center space-x-3">
        <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded font-bold text-xs">
          CONSOLIDATED TIMETABLE SHEET
        </span>
        <span class="text-slate-200 text-sm font-semibold">
          {{ $department }} • {{ implode(', ', array_map(fn($id, $info) => "$id (Sem " . ($info['semester'] ?? 1) . ")", array_keys($timetables), $timetables)) }}
        </span>
      </div>
      <div class="flex items-center space-x-3">
        <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition-all shadow-md cursor-pointer flex items-center space-x-1.5">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h14z"/></svg>
          <span>Print Consolidated Sheet</span>
        </button>
        <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-lg text-xs transition-all cursor-pointer border border-slate-700">
          Close Preview
        </button>
      </div>
    </div>

    <!-- White Paper Page Container -->
    <div class="bg-white text-black p-5 rounded-xl shadow-2xl border border-slate-300 page-container space-y-2">
      
      <!-- Centered Institutional Title Header -->
      <div class="border-b-2 border-black pb-1.5 text-center relative print-header space-y-0.5">
        <h1 class="text-xs font-black uppercase tracking-widest text-black">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
        <h2 class="text-base font-black text-black uppercase">CONSOLIDATED DEPARTMENT TIMETABLE</h2>
        
        <!-- Metadata Strip: Branch, Batches, Academic Year -->
        <div class="flex justify-center flex-wrap gap-x-6 gap-y-0.5 mt-1 text-[11px] font-black text-black">
          <div>Branch: <strong class="text-black font-black">{{ $department }}</strong></div>
          <div>Batches: <strong class="text-black font-black">{{ implode(', ', array_map(fn($id, $info) => "$id (Sem " . ($info['semester'] ?? 1) . ")", array_keys($timetables), $timetables)) }}</strong></div>
          <div>Year: <strong class="text-black font-black">{{ $currentYear }} - {{ $currentYear + 1 }}</strong></div>
        </div>
      </div>

      <!-- Consolidated Timetable Grid Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="bg-slate-100 text-black font-bold text-xs uppercase">
              <th class="p-1 text-center w-14 bg-day text-black">Day</th>
              <th class="p-1 text-center w-24 bg-batch-cell text-black">Classroom</th>
              <th class="p-1 text-center text-black">Period 1<br><span class="text-[8px] text-black font-normal">09:00 - 10:00</span></th>
              <th class="p-1 text-center text-black">Period 2<br><span class="text-[8px] text-black font-normal">10:00 - 11:00</span></th>
              <th class="p-1 text-center text-black">Period 3<br><span class="text-[8px] text-black font-normal">11:10 - 12:10</span></th>
              <th class="p-1 text-center w-7 bg-lunch text-[8px] text-black">Lunch</th>
              <th class="p-1 text-center text-black">Period 4<br><span class="text-[8px] text-black font-normal">01:00 - 02:00</span></th>
              <th class="p-1 text-center text-black">Period 5<br><span class="text-[8px] text-black font-normal">02:00 - 03:00</span></th>
              <th class="p-1 text-center text-black">Period 6<br><span class="text-[8px] text-black font-normal">03:00 - 04:00</span></th>
            </tr>
          </thead>
          <tbody class="text-xs">
            @php
              $days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
              $batchCount = count($timetables);
            @endphp

            @foreach ($days as $dayIndex => $day)
              @php
                $firstBatchRow = true;
              @endphp
              @foreach ($timetables as $classroomId => $info)
                @php
                  $dayData = $info['data'][$day] ?? [];
                  
                  $s1 = $dayData[1] ?? ['subject' => '', 'staff' => ''];
                  $s2 = $dayData[2] ?? ['subject' => '', 'staff' => ''];
                  $s3 = $dayData[3] ?? ['subject' => '', 'staff' => ''];
                  $s4 = $dayData[4] ?? ['subject' => '', 'staff' => ''];
                  $s5 = $dayData[5] ?? ['subject' => '', 'staff' => ''];
                  $s6 = $dayData[6] ?? ['subject' => '', 'staff' => ''];
                @endphp
                <tr>
                  @if ($firstBatchRow)
                    <td rowspan="{{ $batchCount }}" class="p-1 text-center font-black bg-day text-black uppercase text-[10px]">{{ $day }}</td>
                  @endif
                  
                  <td class="p-1 font-black batch-cell text-black text-[9.5px] border-r border-black">{{ $classroomId }}<br><span class="text-[8px] font-bold text-slate-700">(Sem {{ $info['semester'] ?? 1 }})</span></td>

                  {{-- Forenoon Slots --}}
                  @if (areSlotsEqualForPrint($s1, $s2) && areSlotsEqualForPrint($s2, $s3))
                    {!! renderPrintCellHtml($s1, 3, $info['subjects']) !!}
                  @elseif (areSlotsEqualForPrint($s1, $s2))
                    {!! renderPrintCellHtml($s1, 2, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s3, 1, $info['subjects']) !!}
                  @elseif (areSlotsEqualForPrint($s2, $s3))
                    {!! renderPrintCellHtml($s1, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s2, 2, $info['subjects']) !!}
                  @else
                    {!! renderPrintCellHtml($s1, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s2, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s3, 1, $info['subjects']) !!}
                  @endif

                  {{-- Lunch Column (rowspan across all days & batches) --}}
                  @if ($dayIndex === 0 && $firstBatchRow)
                    <td rowspan="{{ 5 * $batchCount }}" class="bg-lunch text-black font-extrabold text-[9px] uppercase tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg); vertical-align: middle;">
                      LUNCH BREAK
                    </td>
                  @endif

                  {{-- Afternoon Slots --}}
                  @if (areSlotsEqualForPrint($s4, $s5) && areSlotsEqualForPrint($s5, $s6))
                    {!! renderPrintCellHtml($s4, 3, $info['subjects']) !!}
                  @elseif (areSlotsEqualForPrint($s4, $s5))
                    {!! renderPrintCellHtml($s4, 2, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s6, 1, $info['subjects']) !!}
                  @elseif (areSlotsEqualForPrint($s5, $s6))
                    {!! renderPrintCellHtml($s4, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s5, 2, $info['subjects']) !!}
                  @else
                    {!! renderPrintCellHtml($s4, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s5, 1, $info['subjects']) !!}
                    {!! renderPrintCellHtml($s6, 1, $info['subjects']) !!}
                  @endif
                </tr>
                @php
                  $firstBatchRow = false;
                @endphp
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Signature Footer -->
      <div class="pt-2 grid grid-cols-3 text-center text-[9.5px] font-extrabold text-black signature-footer">
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Head of Department</p>
        </div>
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Academic Timetable Coordinator</p>
        </div>
        <div>
          <div class="h-4"></div>
          <p class="border-t-2 border-black pt-0.5 mx-6">Principal</p>
        </div>
      </div>

    </div>

  </div>

</body>
</html>

@php
  function areSlotsEqualForPrint($slotA, $slotB) {
    if (!$slotA || !$slotB) return false;
    if (!empty($slotA['is_parallel']) || !empty($slotB['is_parallel'])) {
        if (empty($slotA['is_parallel']) || empty($slotB['is_parallel'])) return false;
        $labsA = $slotA['parallel_labs'] ?? [];
        $labsB = $slotB['parallel_labs'] ?? [];
        if (count($labsA) !== count($labsB)) return false;
        foreach ($labsA as $i => $labA) {
            $labB = $labsB[$i] ?? [];
            $staffA = is_array($labA['staff'] ?? '') ? implode(',', $labA['staff']) : ($labA['staff'] ?? '');
            $staffB = is_array($labB['staff'] ?? '') ? implode(',', $labB['staff']) : ($labB['staff'] ?? '');
            if (($labA['subject'] ?? '') !== ($labB['subject'] ?? '') || $staffA !== $staffB) {
                return false;
            }
        }
        return true;
    }
    return ($slotA['subject'] ?? '') === ($slotB['subject'] ?? '') && !empty($slotA['subject']);
  }

  function renderPrintCellHtml($slot, $colspan = 1, $subjectsList = []) {
    $colspanAttr = $colspan > 1 ? "colspan=\"{$colspan}\"" : "";
    if (empty($slot)) {
      return "<td {$colspanAttr} class=\"p-0.5 text-center free-period\">-- Free --</td>";
    }

    if (!empty($slot['is_parallel']) && !empty($slot['parallel_labs'])) {
        $labsHtml = [];
        foreach ($slot['parallel_labs'] as $idx => $lab) {
            $subCode = $lab['subject'] ?? '';
            $matchedSub = is_array($subjectsList) 
              ? collect($subjectsList)->firstWhere('subject_code', $subCode)
              : $subjectsList->firstWhere('subject_code', $subCode);

            $subjectName = $matchedSub ? ($matchedSub->subject_name ?? '') : '';
            $staffDisplay = is_array($lab['staff'] ?? null) ? implode(', ', array_filter($lab['staff'])) : ($lab['staff'] ?? '');
            if (empty($staffDisplay) && $matchedSub) {
                $assignments = DB::table('subject_staff_assignments')
                    ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
                    ->where('subject_staff_assignments.batch_subject_id', $matchedSub->id)
                    ->pluck('staff_profiles.name')
                    ->toArray();
                $staffDisplay = implode(', ', $assignments);
            }
            $labLabel = $idx === 0 ? 'LAB 1 (TOP)' : 'LAB 2 (BOTTOM)';
            $border = $idx > 0 ? 'border-t: 1px dashed #000000; margin-top: 1.5px; padding-top: 1.5px;' : '';
            $labsHtml[] = "
              <div style=\"{$border}\">
                <div style=\"font-weight: 900; font-size: 7px; color: #475569; text-transform: uppercase;\">{$labLabel}</div>
                <div style=\"font-weight: 900; font-size: 8.5px; line-height: 1.1; color: #000000;\">{$subCode} " . ($subjectName ? "({$subjectName})" : "") . "</div>
                <div style=\"font-size: 7px; font-weight: 700; color: #1e3a8a; margin-top: 0.5px;\">Faculty: {$staffDisplay}</div>
              </div>
            ";
        }
        $labsHtmlStr = implode('', $labsHtml);
        return "<td {$colspanAttr} class=\"p-0.5 text-center\" style=\"vertical-align: middle;\">{$labsHtmlStr}</td>";
    }

    if (empty($slot['subject'])) {
      return "<td {$colspanAttr} class=\"p-0.5 text-center free-period\">-- Free --</td>";
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
      <td {$colspanAttr} class=\"p-0.5 text-center\">
        <div style=\"font-weight: 900; font-size: 9.5px; line-height: 1.1; color: #000000;\">{$slot['subject']}</div>
        <div style=\"font-weight: 700; font-size: 8.5px; margin-top: 0.5px; line-height: 1.05; color: #000000;\">{$subjectName}</div>
        <div style=\"font-size: 7.5px; font-weight: 700; margin-top: 0.5px; line-height: 1.05; color: #000000;\">{$staffDisplay}</div>
      </td>
    ";
  }
@endphp
