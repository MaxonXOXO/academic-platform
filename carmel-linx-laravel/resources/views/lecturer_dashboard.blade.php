<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Lecturer Dashboard</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <!-- Flatpickr for premium Date/Time selection -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- SheetJS for client-side Excel parse & generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
  <style>
    /* Hide up/down spinner buttons in number inputs */
    .no-spinner::-webkit-inner-spin-button,
    .no-spinner::-webkit-outer-spin-button,
    input[type=number].no-spinner::-webkit-inner-spin-button,
    input[type=number].no-spinner::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .no-spinner, input[type=number].no-spinner {
      -moz-appearance: textfield;
      appearance: textfield;
    }

    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
    }
    .text-lg {
      font-size: 1.05rem !important;
    }
    .text-base {
      font-size: 0.875rem !important;
    }
    nav.space-y-1\.5 > :not([hidden]) ~ :not([hidden]) {
      margin-top: 0.125rem !important;
    }
    nav.space-y-1\.5 a, nav.space-y-1\.5 button {
      padding-top: 0.375rem !important;
      padding-bottom: 0.375rem !important;
    }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }

    /* Screen responsiveness: scale down fonts, paddings, and gaps for monitors under 1440px (like 1366x768) */
    @media (max-width: 1440px) {
      html, body {
        font-size: 13px !important;
      }
      #panelClassroom, 
      #panelClassroom button,
      #panelClassroom select,
      #panelClassroom input,
      #panelClassroom table,
      #panelClassroom th,
      #panelClassroom td,
      #panelClassroom div,
      #panelClassroom p,
      #panelClassroom h3,
      #panelClassroom h4,
      #panelClassroom h5,
      #panelClassroom span {
        font-size: 12px !important;
      }
      .p-6 {
        padding: 1rem !important;
      }
      .p-8 {
        padding: 1.25rem !important;
      }
      .gap-6 {
        gap: 1rem !important;
      }
      .gap-8 {
        gap: 1.25rem !important;
      }
      .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .text-nowrap {
        white-space: nowrap !important;
      }
    }

    #panelClassroom {
      background-color: #060b13 !important; /* Darker slate/black background */
      border: 1px solid #111827 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.3) !important;
    }

    /* High-density compact typography overrides for Virtual Classroom (#panelClassroom) */
    #panelClassroom {
      background-color: #060b13 !important;
      border: 1px solid #111827 !important;
      border-radius: 1rem !important;
      padding: 1rem !important;
      font-size: 0.75rem !important;
    }

    #panelClassroom h3#vcTitle {
      font-size: 0.95rem !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle {
      font-size: 0.72rem !important;
    }
    
    #panelClassroom #vcViewStudentsBtn {
      font-size: 0.72rem !important;
      padding: 0.35rem 0.75rem !important;
    }

    #panelClassroom button {
      font-size: 0.75rem;
    }

    #panelClassroom table {
      font-size: 0.72rem !important;
    }

    #panelClassroom table th {
      font-size: 0.68rem !important;
      font-weight: 600 !important;
      padding: 0.35rem 0.5rem !important;
      text-transform: uppercase;
      letter-spacing: 0.02em;
    }

    #panelClassroom table td {
      font-size: 0.72rem !important;
      padding: 0.3rem 0.5rem !important;
      font-weight: 500 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 13px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker calendar visibility in dark background */
    .flatpickr-calendar {
      background: #0f172a !important;
      border: 1px solid #334155 !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #1e293b !important;
      color: #38bdf8 !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: white !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #f1f5f9 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #38bdf8 !important;
      fill: #38bdf8 !important;
    }

    /* PREMIUM LIGHT THEME OVERRIDES (100% Layout Safe) */
    body.light-theme {
      background-color: #f8fafc !important; /* slate-50 */
      color: #334155 !important; /* slate-700 */
    }
    body.light-theme header {
      background-color: rgba(255, 255, 255, 0.8) !important;
      border-color: #e2e8f0 !important;
      color: #0f172a !important;
    }
    body.light-theme header h1 {
      color: #0f172a !important;
    }
    body.light-theme aside {
      background-color: #ffffff !important;
      border-color: #e2e8f0 !important;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }
    body.light-theme aside h2,
    body.light-theme aside span {
      color: #1e293b !important;
    }
    body.light-theme aside .text-slate-400 {
      color: #64748b !important;
    }
    body.light-theme aside a,
    body.light-theme aside button {
      color: #475569 !important;
    }
    body.light-theme aside a:hover,
    body.light-theme aside button:hover {
      background-color: #f1f5f9 !important;
      color: #0f172a !important;
    }
    body.light-theme aside .bg-slate-900\/40 {
      background-color: #f8fafc !important;
      border-color: #e2e8f0 !important;
    }
    body.light-theme aside .bg-slate-800 {
      background-color: #f1f5f9 !important;
      color: #334155 !important;
    }

    /* Cards and panels */
    body.light-theme .bg-slate-900,
    body.light-theme .bg-slate-950,
    body.light-theme .bg-slate-900\/50,
    body.light-theme .bg-slate-950\/50,
    body.light-theme .bg-slate-950\/40,
    body.light-theme .bg-slate-950\/30,
    body.light-theme .bg-slate-950\/20,
    body.light-theme .bg-slate-950\/80,
    body.light-theme .bg-slate-900\/80 {
      background-color: #ffffff !important;
      color: #334155 !important;
    }
    body.light-theme .bg-slate-800\/50,
    body.light-theme .bg-slate-800 {
      background-color: #f1f5f9 !important;
      color: #1e293b !important;
    }
    body.light-theme .bg-slate-900\/40,
    body.light-theme .bg-slate-950\/20 {
      background-color: #f8fafc !important;
    }

    /* Borders */
    body.light-theme .border-slate-800,
    body.light-theme .border-slate-700,
    body.light-theme .border-slate-800\/80,
    body.light-theme .border-slate-800\/60,
    body.light-theme .border-slate-800\/40,
    body.light-theme .border-slate-700\/50,
    body.light-theme .border-slate-700\/60 {
      border-color: #e2e8f0 !important;
    }

    /* Texts */
    body.light-theme .text-slate-100,
    body.light-theme .text-slate-200,
    body.light-theme .text-white {
      color: #0f172a !important;
    }
    body.light-theme .text-slate-300,
    body.light-theme .text-slate-400 {
      color: #475569 !important;
    }
    body.light-theme .text-slate-500 {
      color: #64748b !important;
    }

    /* Input elements */
    body.light-theme input,
    body.light-theme select,
    body.light-theme textarea {
      background-color: #ffffff !important;
      border-color: #cbd5e1 !important;
      color: #0f172a !important;
    }
    body.light-theme input::placeholder,
    body.light-theme textarea::placeholder {
      color: #94a3b8 !important;
    }

    /* Flatpickr light override */
    body.light-theme .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
      color: #0f172a !important;
    }
    body.light-theme .flatpickr-calendar .flatpickr-day {
      color: #334155 !important;
    }
    body.light-theme .flatpickr-calendar .flatpickr-day:hover {
      background: #f1f5f9 !important;
    }
    body.light-theme .flatpickr-calendar span.cur-month,
    body.light-theme .flatpickr-calendar input.numInput {
      color: #0f172a !important;
    }
    /* Compact Sidebar Navigation Sizing Standard (Enforcing Principal Desk Density) */
    @media (min-width: 768px) {
      aside nav {
        padding: 0.75rem !important;
      }
      aside nav > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0.125rem !important;
      }
      aside nav a, aside nav button {
        padding-top: 0.375rem !important;
        padding-bottom: 0.375rem !important;
        padding-left: 0.875rem !important;
        padding-right: 0.875rem !important;
        font-size: 11px !important;
        gap: 0.625rem !important;
      }
      aside nav span.material-symbols-rounded {
        font-size: 16px !important;
      }
    }

    /* MOBILE-SPECIFIC SIDEBAR & CARD FIXES (MD breakpoint is 768px) */
    @media (max-width: 767px) {
      /* Sidebar changes: multi-row horizontal block on mobile */
      aside {
        width: 100% !important;
        position: relative !important;
        border-r: none !important;
        border-b: 1px solid #1e293b !important;
        flex-direction: column !important; /* Stack rows vertically */
        align-items: stretch !important;
        padding: 0.75rem 1rem 0.5rem !important;
        gap: 0.75rem !important;
      }
      
      /* Make sidebar brand logo header container visible inline on Row 1 */
      aside > div.border-b {
        display: flex !important;
        border-bottom: none !important;
        padding: 0 !important;
        margin: 0 !important;
        align-items: center !important;
        gap: 0.5rem !important;
      }

      aside > div.border-b img {
        width: 2.25rem !important;
        height: 2.25rem !important;
      }

      aside > div.border-b h2 {
        font-size: 18px !important;
        font-weight: 900 !important;
      }

      aside > div.border-b span {
        display: none !important; /* Hide subtitle to keep Row 1 clean */
      }
      
      /* Make logout block sit inline on Row 1 (far right) with extra top offset spacing */
      aside > div.border-t {
        border-top: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        width: auto !important;
        position: absolute !important;
        right: 1rem !important;
        top: 0.85rem !important;
      }
      
      aside > div.border-t a {
        padding: 0.4rem 0.65rem !important;
        border-radius: 0.5rem !important;
        font-size: 11px !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        background-color: rgba(239, 68, 68, 0.18) !important;
        color: #f87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
      }

      /* Convert vertical nav list to an inline horizontal row on Row 2 with a dark gradient */
      aside nav {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0.5rem !important;
        width: 100% !important;
        padding: 0.4rem 0.5rem !important;
        margin: 0 !important;
        justify-content: space-between !important;
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.8) 100%) !important;
        border: 1px solid rgba(51, 65, 85, 0.4) !important;
        border-radius: 0.75rem !important;
      }
      
      /* Reset standard padding on links/buttons for inline fit */
      aside nav a, aside nav button {
        padding: 0.4rem 0.65rem !important;
        margin: 0 !important;
        border-radius: 0.5rem !important;
        font-size: 11px !important; /* compact font to fit */
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        width: auto !important;
        border-left: none !important; /* Remove custom vertical border indicators */
      }
      
      /* Hide all links except: My Batches (navDashboard), Remedial, and Log & Attendance */
      aside nav > :not(#navDashboard):not([href="/remedial-sessions"]):not([href="/staff/attendance-log"]) {
        display: none !important;
      }
      
      /* Active profile avatar banner is too large on mobile - hide or reduce */
      #sidebarAvatarContainer {
        display: none !important;
      }
      
      /* Grid spacing and layout tweaks to ensure batch cards are easily accessible and stand alone */
      #lecturerBatchGrid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
      }
      
      /* Hide My Assigned Classrooms header banner on mobile */
      #assignedClassroomHeader {
        display: none !important;
      }

      /* Light colored border for batch cards on mobile */
      #lecturerBatchGrid > div {
        border-color: rgba(148, 163, 184, 0.45) !important; /* light slate-400 border */
      }

      /* ENHANCED MOBILE STYLING FOR SEMINAR ROOM & ASSESSMENT MARK ENTRY FIELDS */
      
      /* Make sure that inside classroom, today's seminar evaluations list table transforms to clean cards on mobile */
      #seminarEvaluationContent table, 
      #seminarEvaluationContent thead, 
      #seminarEvaluationContent tbody, 
      #seminarEvaluationContent th, 
      #seminarEvaluationContent td, 
      #seminarEvaluationContent tr { 
        display: block !important; 
      }
      
      #seminarEvaluationContent thead {
        display: none !important; /* Hide header row on mobile */
      }
      
      #seminarEvaluationContent tr {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.6) !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
      }
      
      #seminarEvaluationContent td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 15px !important;
      }
      
      #seminarEvaluationContent td:nth-child(1)::before { content: "Roll No: "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(3)::before { content: "Topic: "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(4)::before { content: "Guide: "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(5)::before { content: "Presentation Date: "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(6)::before { content: "Relevance (7.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(7)::before { content: "Literature (7.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(8)::before { content: "Presentation (37.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(9)::before { content: "Interaction (7.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(10)::before { content: "Report (7.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(11)::before { content: "Attendance (7.5): "; font-weight: bold; color: #94a3b8; }
      #seminarEvaluationContent td:nth-child(12)::before { content: "My Total (75): "; font-weight: bold; color: #38bdf8; }
      #seminarEvaluationContent td:nth-child(13)::before { content: "Class Average (75): "; font-weight: bold; color: #2dd4bf; }
      
      #seminarEvaluationContent td:nth-child(14) {
        margin-top: 0.75rem !important;
        border-top: 1px solid rgba(51, 65, 85, 0.4) !important;
        padding-top: 1rem !important;
        justify-content: center !important;
      }
      
      #seminarEvaluationContent td:nth-child(14) button {
        width: 100% !important;
        padding: 0.75rem !important;
        font-size: 15px !important;
        border-radius: 0.75rem !important;
      }

      /* Seminar Evaluation Modal mobile enhancements */
      #seminarEvaluationModal .bg-slate-950 {
        max-width: 95% !important;
        margin: auto !important;
        border-radius: 1.25rem !important;
      }
      #seminarEvaluationModal label {
        font-size: 14px !important;
      }
      #seminarEvaluationModal input[type="number"] {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        width: 5.5rem !important;
      }
      #seminarEvaluationModal input[type="range"] {
        height: 14px !important;
      }

      /* Arrange Interaction, Report, Attendance in a row to save space and show titles */
      #seminarEvaluationModal .grid-cols-3,
      #mobileSeminarForm .grid-cols-3 {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 0.5rem !important;
      }
      
      #seminarEvaluationModal .grid-cols-3 > div,
      #mobileSeminarForm .grid-cols-3 > div {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.5rem !important;
        border-radius: 0.75rem !important;
        text-align: center !important;
        space-y: 0 !important;
      }
      
      #seminarEvaluationModal .grid-cols-3 > div label,
      #mobileSeminarForm .grid-cols-3 > div label,
      #mobileSeminarForm .grid-cols-3 > div .text-xs,
      #mobileSeminarForm .grid-cols-3 > div .text-purple-300,
      #mobileSeminarForm .grid-cols-3 > div .text-teal-300,
      #mobileSeminarForm .grid-cols-3 > div .text-emerald-300 {
        font-size: 11px !important;
        font-weight: bold !important;
        margin: 0 0 0.25rem 0 !important;
        text-align: center !important;
        display: block !important;
        white-space: nowrap !important;
        color: #94a3b8 !important;
      }

      #seminarEvaluationModal .grid-cols-3 > div input,
      #mobileSeminarForm .grid-cols-3 > div input {
        width: 100% !important;
        font-size: 15px !important;
        min-height: 38px !important;
        margin: 0 !important;
        text-align: center !important;
        padding: 0.25rem !important;
      }
      
      #seminarEvaluationModal .grid-cols-3 > div .text-[9px],
      #mobileSeminarForm .grid-cols-3 > div .text-xs:not(.font-black):not(.text-purple-300):not(.text-teal-300):not(.text-emerald-300),
      #mobileSeminarForm .grid-cols-3 > div .text-xs:not(.font-bold) {
        display: none !important; /* Hide subtexts/limits to keep row clean */
      }

      /* Mobile Seminar Room Panel (#panelMobileSeminar) enhancements */
      #panelMobileSeminar {
        padding: 0.5rem !important;
      }
      #panelMobileSeminar,
      #panelMobileSeminar div,
      #panelMobileSeminar p,
      #panelMobileSeminar span,
      #panelMobileSeminar button,
      #panelMobileSeminar input,
      #panelMobileSeminar label {
        font-size: 14px !important;
      }
      #panelMobileSeminar h3,
      #panelMobileSeminar .text-xl,
      #mobSemStudentName {
        font-size: 18px !important;
      }
      #panelMobileSeminar h4,
      #panelMobileSeminar .text-lg {
        font-size: 16px !important;
      }
      #panelMobileSeminar .text-xs,
      #panelMobileSeminar .text-[10px],
      #panelMobileSeminar .text-[11px] {
        font-size: 13px !important;
      }

      /* Enforce uniform font size for total score display on mobile */
      #mobSemTotalDisplay,
      #mobSemTotalDisplay *,
      #semTotalScoreLabel {
        font-size: 1.25rem !important;
        font-weight: 900 !important;
      }
      
      /* Evaluate details slider & input adjustments in Mobile Seminar Room */
      #mobileSeminarForm input[type="number"] {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
      }

      /* Convert sidebar logout link to an icon-only button on mobile view */
      aside > div.border-t {
        border-top: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        width: auto !important;
        position: absolute !important;
        right: 1rem !important;
        top: 0.85rem !important;
      }
      aside > div.border-t a {
        padding: 0.5rem !important;
        border-radius: 0.5rem !important;
        font-size: 0 !important; /* Hide "Sign Out" text node */
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: rgba(239, 68, 68, 0.18) !important;
        color: #f87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
      }
      aside > div.border-t a span {
        font-size: 16px !important;
        margin: 0 !important;
      }

      /* Reset standard padding on links/buttons for inline fit */
      aside nav a, aside nav button {
        padding: 0.4rem 0.65rem !important;
        margin: 0 !important;
        border-radius: 0.5rem !important;
        font-size: 11px !important; /* compact font to fit */
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        width: auto !important;
        border-left: none !important; /* Remove custom vertical border indicators */
      }

      /* Clean dashboard title bar on mobile */
      header h1#panelTitle {
        font-size: 18px !important;
      }
      header button {
        padding: 0.35rem 0.5rem !important;
      }
      header button span {
        font-size: 14px !important;
      }

      /* Assessment Mark Entry Fields Font Enlargement & App-like Layout */
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #0f172a !important;
        border-color: #334155 !important;
        border-radius: 0.5rem !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(51, 65, 85, 0.6) !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      /* Assignment marks details helper labels */
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #94a3b8; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #94a3b8; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #94a3b8; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #94a3b8; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #38bdf8; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #38bdf8; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #38bdf8; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #38bdf8; }
      
      /* Summative marks details helper labels */
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #94a3b8; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #94a3b8; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #94a3b8; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #94a3b8; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #38bdf8; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #38bdf8; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #38bdf8; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #38bdf8; }
      
      /* Make inputs larger in card layout and right-aligned */
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      /* Enlarge Seminar Day notification card texts in mobile view */
      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 13px !important;
      }

      /* Enlarge lecturer batch cards text sizes in mobile view */
      #lecturerBatchGrid h4 {
        font-size: 19px !important; /* "Admission 2026" main title */
      }
      #lecturerBatchGrid h5 {
        font-size: 14px !important; /* "Assigned Subjects" title */
      }
      #lecturerBatchGrid .text-base,
      #lecturerBatchGrid .text-sm {
        font-size: 17px !important; /* Assigned subject name */
      }
      #lecturerBatchGrid .text-xs {
        font-size: 13px !important; /* Semester details, student count */
      }
      #lecturerBatchGrid span.font-mono {
        font-size: 14px !important; /* Batch code badge */
      }
      #lecturerBatchGrid span.text-[11px] {
        font-size: 12px !important; /* Roles, engaged hours text */
      }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row md:h-screen md:overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  @if(session('userRole') !== 'Demonstrator')
  <aside id="mainSidebar" class="w-full md:w-64 bg-slate-950 text-white shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl md:h-screen md:overflow-y-auto transition-all duration-300">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg">
      <div>
        <h2 class="font-black tracking-tight leading-tight text-white" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.3px; background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Carmel Linx</h2>
        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lecturer Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-[10px] block truncate text-slate-200 text-[10px] text-xs">{{ session('userName') }}</span>
        <span class="text-[10px] font-bold text-teal-400 block uppercase tracking-wider">{{ session('userBranch') }} Lecturer</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 ">
        <span class="material-symbols-rounded text-lg">grid_view</span> My Batches
      </button>



      @php
        $mobileNo = session('userId');
        $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNo);
        $isTutor = \App\Models\ClassManagement::where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('tutor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('tutor_mobile_no', $cleanMobile);
        })->exists() || \DB::table('r26_class_management')->where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('tutor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('tutor_mobile_no', $cleanMobile);
        })->exists();

        $isMentor = \App\Models\ClassManagement::where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('mentor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('mentor_mobile_no', $cleanMobile);
        })->exists() || \DB::table('r26_class_management')->where(function($q) use ($mobileNo, $cleanMobile) {
            $q->where('mentor_mobile_no', $mobileNo);
            if ($cleanMobile) $q->orWhere('mentor_mobile_no', $cleanMobile);
        })->exists();
      @endphp

      @if(session('userRole') === 'HOD')
      <a href="/dashboard/hod" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> HOD Console
      </a>
      @elseif(in_array(session('userRole'), ['Principal', 'Super_Admin']))
      <a href="/dashboard/principal" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-950/30 hover:text-sky-300 cursor-pointer no-underline block border border-sky-900/40 bg-sky-950/20">
        <span class="material-symbols-rounded text-lg">shield_person</span> Principal Control Desk
      </a>
      @endif

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif
      
      <a href="/staff/attendance-log" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">co_present</span> Class Attendance Log
      </a>

      <a href="/remedial-sessions" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">health_and_safety</span> Remedial Sessions
      </a>

      <a href="/course-files" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">folder_open</span> Course Files (2021)
      </a>

      <a href="/staff/mobile?mode=mobile" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">event_note</span> My Leave & Attendance Log
      </a>

      <a href="/staff/professional-activities" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">school</span> Professional Activities
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800/60 hover:text-white cursor-pointer mt-4">
        <span class="material-symbols-rounded text-lg">settings</span> My Profile
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to logout?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-sm">logout</span> Sign Out
      </a>

      <!-- Support Badge -->
      <div onclick="openStaffSupportModal()" class="p-2 bg-slate-950/60 hover:bg-slate-900 border border-slate-800/80 rounded-xl text-center select-none cursor-pointer transition-premium" title="Click to Request Remote Support Assist">
        <div class="flex items-center justify-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
          <span class="material-symbols-rounded text-xs text-blue-400">headset_mic</span> Live Assist
        </div>
        <div class="text-[11px] font-black text-slate-200 mt-0.5">Dhanush.A</div>
        <div class="text-[9px] text-slate-400 font-medium">Dept. of Electronics</div>
      </div>
    </div>
  </aside>
  @endif

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col relative md:h-screen md:overflow-hidden min-w-0">
    
    <!-- Top Header Card (Stable Always) -->
    <header class="h-16 shrink-0 sticky top-0 z-30 border-b border-slate-800/60 bg-slate-900/90 backdrop-blur-md flex items-center justify-between px-4 md:px-8 shadow-md">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex items-center gap-2 shrink-0">
          <span class="material-symbols-rounded text-sky-400 text-xl">school</span>
          <span class="font-extrabold text-white text-base tracking-tight">Carmel Linx</span>
          <span class="text-slate-600 font-bold">|</span>
        </div>
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg md:text-2xl truncate flex items-center gap-2">My Batches</h1>
      </div>
      <div class="flex items-center gap-2 md:gap-3 shrink-0">
        <div id="aiStatusBadge" class="hidden"></div>
        @include('partials.fullscreen_btn')
        <button onclick="toggleTheme()" class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-700/80 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-premium cursor-pointer" title="Toggle Light/Dark Theme">
          <span id="themeToggleIcon" class="material-symbols-rounded text-lg">light_mode</span>
          <span id="themeToggleText" class="text-xs font-bold uppercase tracking-wider hidden sm:inline">Light Mode</span>
        </button>
        <button id="headerBackBtn" onclick="switchPanel('dashboard')" class="hidden items-center gap-1.5 px-2.5 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-rose-500/30 rounded-lg text-[11px] font-bold transition-all duration-200 cursor-pointer shadow-sm group shrink-0" title="Return to Main Dashboard">
          <span class="material-symbols-rounded text-xs text-rose-400">arrow_back</span>
          <span class="hidden sm:inline font-bold text-[11px] tracking-wide text-rose-400 group-hover:text-rose-300">Dashboard</span>
        </button>
      </div>
    </header>

    <!-- Panel Container (Scrollable Content Area) -->
    <div class="flex-grow min-h-0 overflow-y-auto p-4 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD (BATCH CARDS) -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Populated dynamically -->
        </div>

        <div id="assignedClassroomHeader" class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl gap-4">
          <div>
            <h3 class="text-lg font-black text-slate-200">My Assigned Batches & Classrooms</h3>
            <p class="text-sm text-slate-400 mt-0.5">Select a subject to enter the virtual classroom for assignments and assessments.</p>
          </div>
          <div class="flex bg-slate-900 border border-slate-800 p-1 rounded-xl shadow-inner">
            <button onclick="setDashboardBatchFilter('active')" id="btnFilterActive" class="px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer">
              Active Batches
            </button>
            <button onclick="setDashboardBatchFilter('historical')" id="btnFilterHistorical" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer">
              Archived Batches
            </button>
          </div>
        </div>
        
        <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full py-12 text-center text-slate-500 font-bold text-sm animate-pulse">Loading batches...</div>
        </div>
      </div>

      <!-- PANEL: VIRTUAL CLASSROOM / LAB WORKSPACE -->
      <div id="panelClassroom" class="hidden space-y-4">
        
        <!-- CARD 1: Subject Header (Code, Title, Actions) -->
        <div class="bg-slate-950/90 border border-slate-800/80 p-4 rounded-2xl shadow-xl">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <!-- Left: Bold Subject Code & Name -->
            <div class="flex items-center gap-2.5 flex-wrap">
              <span id="vcSubjectFullCode" class="text-lg md:text-xl font-black font-mono text-blue-400 bg-blue-500/10 border border-blue-500/30 px-2.5 py-0.5 rounded-lg shadow-sm">CE-401</span>
              <span class="text-slate-500 font-bold text-base">•</span>
              <h2 id="vcSubjectFullName" class="text-lg md:text-xl font-black text-white tracking-tight">Transportation Engineering Lab</h2>
            </div>

            <!-- Right End: Action Buttons (Upload Syllabus, View Syllabus, View Students) -->
            <div class="flex items-center gap-2 flex-wrap">
              <!-- Upload Syllabus Button -->
              <div id="syllabusUploadBox" onclick="document.getElementById('syllabusFileInput').click()" class="h-8 px-3 inline-flex items-center justify-center gap-1.5 text-[11px] font-bold text-slate-200 bg-slate-900/90 hover:bg-slate-800 border border-slate-700/80 hover:border-blue-500/60 rounded-lg transition cursor-pointer whitespace-nowrap shadow-sm shrink-0">
                <span class="material-symbols-rounded text-sm text-blue-400">upload_file</span>
                <span>Upload Syllabus</span>
                <input type="file" id="syllabusFileInput" class="hidden" accept="application/pdf" onchange="handleSyllabusUpload(this)">
              </div>

              <!-- Upload Progress Indicator -->
              <div id="syllabusUploadProgress" class="hidden relative z-10 flex-col justify-center h-8 px-3 bg-slate-900 border border-slate-800 rounded-lg min-w-[130px]">
                <div class="flex justify-between text-[10px] font-bold text-blue-400 mb-0.5">
                  <span>Extracting...</span>
                  <span id="syllabusProgressText" class="animate-pulse">Processing</span>
                </div>
                <div class="w-full bg-slate-950 rounded-full h-1 border border-slate-800 overflow-hidden">
                  <div class="bg-gradient-to-r from-blue-600 to-sky-400 h-1 rounded-full w-full animate-[progress_2s_ease-in-out_infinite]"></div>
                </div>
              </div>

              <!-- View Syllabus Button (Shown only when syllabus is uploaded) -->
              <button id="downloadSyllabusBtn" onclick="downloadSyllabusPDF()" title="View Syllabus PDF" class="hidden h-8 px-3 inline-flex items-center justify-center gap-1.5 text-[11px] font-bold text-emerald-300 hover:text-white bg-emerald-950/80 hover:bg-emerald-900 border border-emerald-700/60 rounded-lg transition cursor-pointer whitespace-nowrap shadow-sm shrink-0">
                <span class="material-symbols-rounded text-sm text-emerald-400">visibility</span>
                <span>View Syllabus</span>
              </button>

              <!-- View Students List Button -->
              <button id="vcViewStudentsBtn" onclick="showVcStudentsList()" class="h-8 px-3 inline-flex items-center justify-center gap-1.5 text-[11px] font-bold text-blue-300 hover:text-white bg-blue-950/70 hover:bg-blue-900/80 border border-blue-500/50 rounded-lg transition cursor-pointer whitespace-nowrap shadow-sm shrink-0">
                <span class="material-symbols-rounded text-sm text-blue-400">groups</span>
                <span>View Students</span>
              </button>
            </div>
          </div>
        </div>

        <!-- CARD 2: Academic Metadata Row (Separated Card) -->
        <div class="bg-slate-900/60 border border-slate-800/60 p-3 rounded-2xl shadow-md flex items-center gap-2 flex-wrap text-xs font-medium text-slate-400">
          <span id="vcBatchBadge" class="font-mono font-bold text-slate-100 bg-slate-950 border border-slate-700/80 px-2.5 py-1 rounded-lg shadow-sm">Batch: CE_2024_2027</span>
          <span class="text-slate-600 font-bold">•</span>
          <span id="vcSemBadge" class="font-mono font-bold text-slate-100 bg-slate-950 border border-slate-700/80 px-2.5 py-1 rounded-lg shadow-sm">Sem: S4</span>
          <span class="text-slate-600 font-bold">•</span>
          <span id="vcBranchBadge" class="font-mono font-bold text-slate-100 bg-slate-950 border border-slate-700/80 px-2.5 py-1 rounded-lg shadow-sm">Branch: CE</span>
          <span class="text-slate-600 font-bold">•</span>
          <span id="vcRevisionBadge" class="font-mono bg-slate-950/60 px-2.5 py-1 rounded-lg border border-slate-800/80">Revision: R-2021</span>
          <span class="text-slate-600 font-bold">•</span>
          <span id="vcHoursCreditsBadge" class="font-mono bg-slate-950/60 px-2.5 py-1 rounded-lg border border-slate-800/80">Proposed Hours: 60 hrs (+2 tests) | Credits: 2.0</span>
          <span class="text-slate-600 font-bold">•</span>
          <span id="vcMarksBadge" class="font-mono bg-slate-950/60 px-2.5 py-1 rounded-lg border border-slate-800/80">CIA: 60M | ESE: 40M</span>
        </div>
        
        <!-- CARD 3: Professional Horizontal Tab Strip Navigation Container (Separated Card) -->
        <div class="bg-slate-950/80 border border-slate-800/80 p-2 rounded-2xl shadow-lg">
          <nav class="flex flex-wrap md:flex-nowrap items-center gap-1.5 overflow-x-auto scrollbar-none">
             <button onclick="toggleClassroomTab('structure')" id="tabStructure" class="px-3.5 py-2 text-[11px] md:text-xs font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px shadow-[0_-4px_10px_rgba(59,130,246,0.2)]">
               <span class="material-symbols-rounded text-sm">account_tree</span> Course Structure
             </button>
             <button onclick="toggleClassroomTab('planner')" id="tabPlanner" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">calendar_month</span> Lesson Planner
             </button>
             <button onclick="toggleClassroomTab('assessment')" id="tabAssessment" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">assignment_turned_in</span> Formative Assessment
             </button>
             <button onclick="toggleClassroomTab('summative')" id="tabSummative" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">school</span> Summative Assessment
             </button>
             <button onclick="toggleClassroomTab('seminar_evaluation')" id="tabSeminar" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px hidden">
               <span class="material-symbols-rounded text-sm">co_present</span> Seminar Evaluation
             </button>
             <button onclick="toggleClassroomTab('lab_evaluation')" id="tabLab" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px hidden">
               <span class="material-symbols-rounded text-sm text-teal-400">science</span> Formative Assessment
             </button>
             <button onclick="toggleClassroomTab('qbank')" id="tabQBank" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">database</span> Question Bank
             </button>
             <button onclick="toggleClassroomTab('survey')" id="tabSurvey" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">rate_review</span> Surveys
             </button>
             <button onclick="toggleClassroomTab('course_attainment')" id="tabCourseAttainment" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm text-amber-400">emoji_events</span> Course Attainment
             </button>
             <button onclick="toggleClassroomTab('reports')" id="tabReports" class="px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px">
               <span class="material-symbols-rounded text-sm">assessment</span> Reports
             </button>
          </nav>
        </div>

        <!-- Parsed Data View (Full Width) -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl min-h-[400px] flex flex-col w-full">
            <div id="courseStructureContent" class="space-y-6 flex-grow overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">inventory_2</span>
                </div>
                <p class="text-sm font-bold text-slate-300">No syllabus loaded.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
                <button onclick="document.getElementById('syllabusFileInput').click()" class="mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/20 transition cursor-pointer flex items-center gap-2">
                  <span class="material-symbols-rounded text-sm">upload_file</span> Upload Syllabus PDF
                </button>
              </div>
            </div>
            
            <div id="coursePlannerContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">event_note</span>
                </div>
                <p class="text-sm font-bold text-slate-300">Planner not generated.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
              </div>
            </div>

            <div id="formativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">quiz</span>
                </div>
                <p class="text-sm font-bold text-slate-300">No students or COs available.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus to activate formative assessment tasks.</p>
              </div>
            </div>

            <div id="summativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">school</span>
                </div>
                <p class="text-sm font-bold text-slate-300">Loading summative assessments...</p>
              </div>
            </div>

            <div id="classReportsContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <!-- Institutional Academic Reports Header Banner -->
              <div class="bg-gradient-to-r from-slate-900 via-slate-900/90 to-teal-950/40 border border-slate-800/80 rounded-2xl p-5 shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-rounded text-teal-400 text-lg">assessment</span>
                    <span class="text-xs font-black uppercase tracking-wider text-teal-400">Institutional Academic Reports Center</span>
                  </div>
                  <h3 class="text-base font-black text-slate-100">Printable Academic Reports & Attainment Documentation</h3>
                  <p class="text-xs text-slate-400 mt-1">Access A4 laser-ready print reports for Surveys, Attainment Matrices, Lesson Plans, CIA Marksheets, and Course Files.</p>
                </div>
              </div>

              <!-- Practical Sub-Reports Quick Access (Shown for Practical Subjects) -->
              <div id="practicalReportsActions" class="hidden flex-col gap-3.5 p-5 bg-gradient-to-r from-slate-950/60 via-slate-900/50 to-teal-950/30 border border-slate-800/80 rounded-2xl shadow-md">
                <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-teal-400 text-base">description</span>
                    <span class="text-xs font-black uppercase tracking-wider text-teal-400">Practical Lab Audit Reports (A4 Landscape Print)</span>
                  </div>
                  <span class="text-[11px] font-medium text-slate-400">Official CIA & Lab Audit Documentation</span>
                </div>
                <div class="flex flex-wrap gap-2.5 pt-1">
                  <a id="pRepBtnRegister" target="_blank" class="px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm text-teal-400">grid_on</span> Consolidated Register
                  </a>
                  <a id="pRepBtnAttendance" target="_blank" class="px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm text-emerald-400">playlist_add_check</span> Attendance Log
                  </a>
                  <a id="pRepBtnExperiments" target="_blank" class="px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm text-amber-400">list_alt</span> Experiments List
                  </a>
                  <a id="pRepBtnPlanner" target="_blank" class="px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm text-purple-400">calendar_today</span> Lesson Planner
                  </a>
                  <a id="pRepBtnProjects" target="_blank" class="px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm text-rose-400">assignment</span> Open-Ended Projects
                  </a>
                  <a id="printLabReportBtn" target="_blank" class="px-4 py-2.5 bg-blue-600/80 hover:bg-blue-600 border border-blue-500/50 hover:border-blue-400 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-sm">
                    <span class="material-symbols-rounded text-sm">print</span> Print Register
                  </a>
                </div>
              </div>

              <!-- Practical Report Calculation Table (Shown for Practical Subjects in Reports Tab) -->
              <div id="practicalReportsTableContainer" class="hidden flex-col space-y-4">
                <!-- Splitup Overview Banner -->
                <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-950/60 border border-slate-800/80 rounded-xl px-3.5 py-2 text-xs">
                  <div class="flex items-center gap-1.5 font-bold text-slate-300">
                    <span class="material-symbols-rounded text-teal-400 text-sm">analytics</span>
                    <span>Continuous Lab Work Splitup (Max 37.5):</span>
                  </div>
                  <div class="flex flex-wrap items-center gap-x-3.5 gap-y-1 text-slate-400 text-[11px]">
                    <span>Rough Record: <strong class="text-amber-400 font-bold font-mono">5 Marks</strong></span>
                    <span class="text-slate-700 hidden sm:inline">•</span>
                    <span>Fair Record: <strong class="text-emerald-400 font-bold font-mono">7.5 Marks</strong></span>
                    <span class="text-slate-700 hidden sm:inline">•</span>
                    <span>Obs. &amp; Recording: <strong class="text-sky-400 font-bold font-mono">7.5 Marks</strong></span>
                    <span class="text-slate-700 hidden sm:inline">•</span>
                    <span>Proc. &amp; Punctuality: <strong class="text-purple-400 font-bold font-mono">7.5 Marks</strong></span>
                    <span class="text-slate-700 hidden sm:inline">•</span>
                    <span>Viva Voce: <strong class="text-rose-400 font-bold font-mono">10 Marks</strong></span>
                  </div>
                </div>

                <!-- 12-Column Final Report Calculation Register -->
                <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl overflow-hidden shadow-xl">
                  <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between">
                    <div class="font-bold text-xs text-slate-300 flex items-center gap-2 tracking-wide uppercase">
                      <span class="material-symbols-rounded text-base text-teal-400">table_chart</span> Final Practical Report Calculation Register
                    </div>
                    <span id="repTableStudentCount" class="text-[11px] text-slate-400 font-mono">Enrolled: 0 Students</span>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1200px]">
                      <thead>
                        <tr class="border-b border-slate-800/80 text-slate-400 font-semibold uppercase tracking-wider text-[11px] bg-slate-900/80">
                          <th class="p-2.5 w-10 text-center">Roll</th>
                          <th class="p-2.5">Name and PRN (SBTE)</th>
                          <th class="p-2.5 text-center text-cyan-400">Exps Done</th>
                          <th class="p-2.5 text-center">Rough (5)</th>
                          <th class="p-2.5 text-center">Fair (7.5)</th>
                          <th class="p-2.5 text-center">Obs/Rec (7.5)</th>
                          <th class="p-2.5 text-center">Proc/Punct (7.5)</th>
                          <th class="p-2.5 text-center text-rose-400">Viva (10)</th>
                          <th class="p-2.5 text-center text-blue-400 font-bold">Lab Work (37.5)</th>
                          <th class="p-2.5 text-center text-amber-400">Open Ended (7.5)</th>
                          <th class="p-2.5 text-center text-emerald-400">Attendance (15)</th>
                          <th class="p-2.5 text-center text-slate-300">Test 1 (15)</th>
                          <th class="p-2.5 text-center text-slate-300">Test 2 (15)</th>
                          <th class="p-2.5 text-center text-sky-400 font-bold">Test Avg (15)</th>
                          <th class="p-2.5 text-center text-teal-300 bg-teal-500/15 font-black text-xs sm:text-sm tracking-wide">Total CIA (75)</th>
                          <th class="p-2.5 text-center text-sky-400">Board Exam (50)</th>
                        </tr>
                      </thead>
                      <tbody id="practicalReportsTableBody" class="divide-y divide-slate-800/50">
                        <tr>
                          <td colspan="15" class="p-6 text-center text-slate-400 font-bold">Loading report calculations...</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Printable Academic Reports Grid -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Card 1: Surveys & Attainment -->
                <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-4 flex flex-col justify-between space-y-3 hover:border-slate-700/80 transition-premium">
                  <div>
                    <div class="flex items-center gap-2 mb-2">
                      <span class="material-symbols-rounded text-slate-300 text-base">emoji_events</span>
                      <h4 class="text-xs font-black text-slate-200 uppercase tracking-wider">Surveys & Attainment</h4>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">Direct/Indirect CO attainment calculations, SAR survey reports, and 3-2-1 Likert scale feedback.</p>
                  </div>
                  <div class="space-y-2 pt-2 border-t border-slate-800/60">
                    <button onclick="openPrintReport('course_exit')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">print</span> Course Exit Survey Report (A4)</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                    <button onclick="openPrintReport('nba_attainment')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">analytics</span> NBA Course Attainment Report</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                  </div>
                </div>

                <!-- Card 2: Curriculum & Planning -->
                <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-4 flex flex-col justify-between space-y-3 hover:border-slate-700/80 transition-premium">
                  <div>
                    <div class="flex items-center gap-2 mb-2">
                      <span class="material-symbols-rounded text-slate-300 text-base">auto_stories</span>
                      <h4 class="text-xs font-black text-slate-200 uppercase tracking-wider">Curriculum & Course Files</h4>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">Lesson plan execution tracking, self-learning academic reports, and master course file dossier.</p>
                  </div>
                  <div class="space-y-2 pt-2 border-t border-slate-800/60">
                    <button onclick="openPrintReport('lesson_plan')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">calendar_month</span> Lesson Plan Report (A4)</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                    <button onclick="openPrintReport('course_file')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">folder_open</span> Comprehensive Course File (2021)</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                    <button onclick="openPrintReport('self_learning')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">school</span> Self-Learning Report</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                  </div>
                </div>

                <!-- Card 3: Marks & Evaluations -->
                <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-4 flex flex-col justify-between space-y-3 hover:border-slate-700/80 transition-premium">
                  <div>
                    <div class="flex items-center gap-2 mb-2">
                      <span class="material-symbols-rounded text-slate-300 text-base">fact_check</span>
                      <h4 class="text-xs font-black text-slate-200 uppercase tracking-wider">Evaluation Marksheets</h4>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">Internal Continuous Evaluation (CIE), Series Exam marksheets, and final End-Semester Results.</p>
                  </div>
                  <div class="space-y-2 pt-2 border-t border-slate-800/60">
                    <button onclick="openPrintReport('cie_marksheet')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">assignment</span> Internal CIE Marksheet Report</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                    <button onclick="openPrintReport('series_marks')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">edit_note</span> Series Exam Marksheet Report</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                    <button onclick="openPrintReport('final_results')" class="w-full px-3.5 py-2.5 bg-slate-900/80 hover:bg-slate-800 border border-slate-700/60 hover:border-slate-600 text-slate-200 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-between cursor-pointer shadow-sm">
                      <span class="flex items-center gap-2"><span class="material-symbols-rounded text-base text-slate-400">grade</span> Final Results & ESE Marksheet</span>
                      <span class="material-symbols-rounded text-xs text-slate-400">arrow_forward</span>
                    </button>
                  </div>
                </div>

              </div>

              <!-- Attendance & Teaching Logs Sub-Tabs Section -->
              <div class="border-t border-slate-800/60 pt-4 space-y-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                  <h4 class="text-xs font-black uppercase tracking-wider text-slate-300">Classroom Attendance & Subject Logs</h4>
                  <div class="flex flex-wrap gap-2">
                    <button onclick="loadClassReport('attendance_log')" id="btnReportLog" class="px-3.5 py-1.5 bg-indigo-600 text-white rounded-xl font-bold text-xs cursor-pointer transition-premium">
                      Class Attendance Log
                    </button>
                    <button onclick="loadClassReport('subject_log')" id="btnReportSubject" class="px-3.5 py-1.5 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-xs cursor-pointer hover:bg-slate-800 transition-premium">
                      Class Subject Log
                    </button>
                    <button onclick="loadClassReport('summary_matrix')" id="btnReportMatrix" class="px-3.5 py-1.5 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-xs cursor-pointer hover:bg-slate-800 transition-premium">
                      Attendance Matrix
                    </button>
                  </div>
                </div>

                <div id="classroomReportWorkspace" class="pt-2 overflow-x-auto">
                  <div class="text-sm font-bold text-slate-400 py-6 text-center">No reports loaded. Please select a log report type above.</div>
                </div>
              </div>

            </div>

            <!-- Question Bank Panel -->
            <div id="questionBankContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                <div>
                  <h4 class="text-sm font-black text-slate-200">Shared Question Bank Pool</h4>
                  <p class="text-sm text-slate-400 mt-1">Manage and import MCQ or Descriptive questions for this subject code. These questions are pooled across all batches.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                  <button onclick="downloadExcelTemplate()" class="px-4 py-2 bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-200 rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 shadow-md cursor-pointer">
                    <span class="material-symbols-rounded text-base">download</span> Download Excel Template
                  </button>
                  <button onclick="document.getElementById('qbankFileInput').click()" class="px-4 py-2 bg-slate-800/80 hover:bg-slate-700 text-slate-200 rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md border border-slate-700/60">
                    <span class="material-symbols-rounded text-base">upload_file</span> Upload Filled Excel
                  </button>
                  <input type="file" id="qbankFileInput" class="hidden" accept=".xlsx,.xls,.csv" onchange="handleQBankUpload(this)">
                </div>
              </div>

              <!-- Question Bank View -->
              <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl p-6 shadow-inner">
                <div class="space-y-6" id="qbankCoGroups">
                  <div class="text-sm font-bold text-slate-400 py-10 text-center">Loading Question Bank...</div>
                </div>
              </div>
            </div>

            <!-- Combined Surveys Panel (Mid-Semester & Exit Surveys) -->
            <div id="surveysContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex items-center gap-2 p-1.5 bg-slate-900/90 border border-slate-800 rounded-xl w-fit shadow-md">
                <button onclick="switchSurveySubTab('mid_sem')" id="btnSubTabMidSem" class="px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer bg-blue-600 text-white shadow-sm flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sm">rate_review</span> Mid-Semester Survey
                </button>
                <button onclick="switchSurveySubTab('course_exit')" id="btnSubTabExit" class="px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 flex items-center gap-1.5">
                  <span class="material-symbols-rounded text-sm">assignment_turned_in</span> Course Exit Survey
                </button>
              </div>

              <!-- Mid-Semester Survey Section -->
              <div id="midSemesterSurveySection" class="space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                  <div>
                    <h4 class="text-sm font-black text-slate-200">Mid-Semester Survey Evaluation (SAR Criterion 2)</h4>
                    <p class="text-sm text-slate-400 mt-1">Conduct real-time Teaching-Learning process evaluation to identify learning difficulties and plan immediate corrective actions.</p>
                  </div>
                  <div class="flex items-center gap-3 font-semibold text-sm" id="surveyHeaderActions">
                    <!-- Rendered dynamically -->
                  </div>
                </div>

                <div id="surveyWorkspace" class="space-y-6">
                  <!-- Rendered dynamically (Initiate Screen / Live Panel / Results Panel) -->
                </div>
              </div>

              <!-- Course Exit Survey Section -->
              <div id="courseExitSurveySection" class="hidden space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                  <div>
                    <h4 class="text-sm font-black text-slate-200">Course Exit Survey (Indirect CO Attainment)</h4>
                    <p class="text-sm text-slate-400 mt-1">Evaluates indirect Course Outcome (CO) attainment parameters at semester-end for NBA course file accreditation.</p>
                  </div>
                  <div class="flex items-center gap-3 font-semibold text-sm" id="exitSurveyHeaderActions">
                    <!-- Rendered dynamically -->
                  </div>
                </div>

                <div id="exitSurveyWorkspace" class="space-y-6">
                  <!-- Rendered dynamically (Initiate Screen / Live Panel / Results Panel) -->
                </div>
              </div>
            </div>

            <!-- Course Attainment Panel -->
            <div id="courseAttainmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                <div>
                  <h4 class="text-sm font-black text-slate-200">Course Attainment Analysis (R-2021)</h4>
                  <p class="text-sm text-slate-400 mt-1">Direct (Formative & Summative) and Indirect (Exit Survey) Course Outcome (CO) attainment matrix and target benchmarks.</p>
                </div>
                <div class="flex items-center gap-2">
                  <button onclick="openEseMarksModal()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md shadow-indigo-900/30">
                    <span class="material-symbols-rounded text-sm">edit_note</span> Enter / Update ESE Marks
                  </button>
                </div>
              </div>

              <div id="courseAttainmentWorkspace" class="space-y-6">
                <!-- Rendered dynamically -->
              </div>
            </div>

            <!-- Seminar Evaluation Workspace -->
            <div id="seminarEvaluationContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                <div>
                  <h4 class="text-sm font-black text-slate-200">Seminar Evaluation (Revision 2021)</h4>
                  <p class="text-sm text-slate-400 mt-1">Grade student seminars based on CIA criteria. Multiple assessors' scores will be averaged to formulate the final mark.</p>
                </div>
                <div class="flex items-center gap-2">
                  <button onclick="fetchSeminarEvaluations()" title="Sync latest evaluations" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md border border-slate-700/60">
                    <span class="material-symbols-rounded text-base">refresh</span> Refresh
                  </button>
                  <a id="printSeminarReportBtn" href="#" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer shadow-md">
                    <span class="material-symbols-rounded text-base">print</span> Print Seminar Report
                  </a>
                </div>
              </div>

              <!-- Students List with Split Evaluation Details -->
              <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse">
                    <thead>
                      <tr class="border-b border-slate-800 text-slate-300 font-bold uppercase tracking-wider text-xs bg-slate-900/60">
                        <th class="p-3">Roll No</th>
                        <th class="p-3">Student Name</th>
                        <th class="p-3">Topic</th>
                        <th class="p-3">Guide</th>
                        <th class="p-3 text-center">Presentation Date</th>
                        <th class="p-3 text-center">Relevance (7.5)</th>
                        <th class="p-3 text-center">Literature (7.5)</th>
                        <th class="p-3 text-center">Presentation (37.5)</th>
                        <th class="p-3 text-center">Interaction (7.5)</th>
                        <th class="p-3 text-center">Report (7.5)</th>
                        <th class="p-3 text-center">Attendance (7.5)</th>
                        <th class="p-3 text-center">My Total (75)</th>
                        <th class="p-3 text-center text-teal-400">Class Average (75)</th>
                        <th class="p-3 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="seminarEvaluationsTableBody" class="divide-y divide-slate-800/50">
                      <tr>
                        <td colspan="14" class="p-8 text-center text-slate-400 font-bold text-sm">Loading evaluations...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Lab Evaluation Workspace (Revision 2021) -->
            <div id="labEvaluationContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 border-b border-slate-800/80 pb-5">
                <div class="max-w-xl shrink-0">
                  <h4 class="text-base font-black text-white tracking-wide">Practical / Lab Evaluation Register</h4>
                  <p class="text-sm text-slate-400 mt-1.5 leading-relaxed">Grade day-to-day experiments (37.5), model tests (15),<br>micro-projects (7.5), and board exam marks (50).</p>
                </div>
                <div class="flex items-center gap-2.5 w-full lg:w-auto overflow-x-auto whitespace-nowrap pb-1 lg:pb-0 scrollbar-none">
                  <button type="button" onclick="openLwModal(labStudentsData[0]?.reg_no)" class="px-2.5 py-1 bg-blue-500/20 hover:bg-blue-500/35 border border-blue-400/30 text-blue-300 rounded-md text-xs font-normal transition flex items-center gap-1 shadow-sm shrink-0 cursor-pointer" title="Grade continuous lab work experiments with student navigation">
                    <span class="material-symbols-rounded text-xs">science</span> Lab Work Grade
                  </button>
                  <button type="button" onclick="openOeModal(labStudentsData[0]?.reg_no)" class="px-2.5 py-1 bg-blue-500/20 hover:bg-blue-500/35 border border-blue-400/30 text-blue-300 rounded-md text-xs font-normal transition flex items-center gap-1 shadow-sm shrink-0 cursor-pointer" title="Grade open-ended project with student navigation">
                    <span class="material-symbols-rounded text-xs">assignment</span> Open Ended Grade
                  </button>
                  <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg shadow-sm focus-within:border-blue-500/50 transition-all shrink-0">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Batch:</span>
                    <select id="labBatchFilterSelect" onchange="filterLabGridByBatch()" class="bg-transparent border-0 text-white font-medium text-xs outline-none cursor-pointer">
                      <option value="combined" class="bg-slate-950">Full</option>
                      <option value="1" class="bg-slate-950">Batch 1</option>
                      <option value="2" class="bg-slate-950">Batch 2</option>
                    </select>
                  </div>
                  <button type="button" onclick="openManageExperimentsModal(event)" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-lg text-xs font-medium transition flex items-center gap-1.5 cursor-pointer shadow-md shrink-0">
                    <span class="material-symbols-rounded text-sm text-teal-400">settings</span> Setup Experiments
                  </button>
                  <button type="button" onclick="openManageTestsModal(event)" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-850 border border-slate-800 hover:border-slate-700 text-slate-200 rounded-lg text-xs font-medium transition flex items-center gap-1.5 cursor-pointer shadow-md shrink-0">
                    <span class="material-symbols-rounded text-sm text-blue-400">assignment_turned_in</span> Configure Tests
                  </button>
                </div>
              </div>

              <!-- Compact Lab Statistics Summary (Read-only inline stats) -->
              <div class="flex items-center gap-2 py-1.5 px-3 bg-slate-900/40 border border-slate-800/60 rounded-xl text-xs w-fit">
                <span class="material-symbols-rounded text-purple-400 text-sm">biotech</span>
                <span class="text-slate-400 font-medium">Total Experiments:</span>
                <span class="font-mono font-bold text-purple-300" id="statLabTotalExps">0</span>
              </div>

              <!-- Splitup Overview Banner -->
              <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-950/60 border border-slate-800/80 rounded-xl px-3.5 py-2 text-xs">
                <div class="flex items-center gap-1.5 font-bold text-slate-300">
                  <span class="material-symbols-rounded text-teal-400 text-sm">analytics</span>
                  <span>Continuous Lab Work Splitup (Max 37.5):</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-3.5 gap-y-1 text-slate-400 text-[11px]">
                  <span>Rough Record: <strong class="text-amber-400 font-bold font-mono">5 Marks</strong></span>
                  <span class="text-slate-700 hidden sm:inline">•</span>
                  <span>Fair Record: <strong class="text-emerald-400 font-bold font-mono">7.5 Marks</strong></span>
                  <span class="text-slate-700 hidden sm:inline">•</span>
                  <span>Obs. &amp; Recording: <strong class="text-sky-400 font-bold font-mono">7.5 Marks</strong></span>
                  <span class="text-slate-700 hidden sm:inline">•</span>
                  <span>Proc. &amp; Punctuality: <strong class="text-purple-400 font-bold font-mono">7.5 Marks</strong></span>
                  <span class="text-slate-700 hidden sm:inline">•</span>
                  <span>Viva Voce: <strong class="text-rose-400 font-bold font-mono">10 Marks</strong></span>
                </div>
              </div>

              <!-- Lab Evaluation Student Grid -->
              <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse min-w-[1150px]">
                    <thead>
                      <tr class="border-b border-slate-800/80 text-slate-400 font-semibold uppercase tracking-wider text-[11px] bg-slate-900/80">
                        <th class="p-2 w-10 text-center">Roll</th>
                        <th class="p-2">Name and PRN (SBTE)</th>
                        <th class="p-2 text-center text-cyan-400">Exps Done</th>
                        <th class="p-2 text-center">Rough (5)</th>
                        <th class="p-2 text-center">Fair (7.5)</th>
                        <th class="p-2 text-center">Obs/Rec (7.5)</th>
                        <th class="p-2 text-center">Proc/Punct (7.5)</th>
                        <th class="p-2 text-center text-rose-400">Viva (10)</th>
                        <th class="p-2 text-center text-blue-400 font-bold">Lab Work (37.5)</th>
                        <th class="p-2 text-center text-amber-400">Open Ended (7.5)</th>
                        <th class="p-2 text-center text-emerald-400">Attendance (15)</th>
                        <th class="p-2 text-center text-slate-300">Test 1 (15)</th>
                        <th class="p-2 text-center text-slate-300">Test 2 (15)</th>
                        <th class="p-2 text-center text-sky-400 font-bold">Test Avg (15)</th>
                        <th class="p-2 text-center text-teal-300 bg-teal-500/15 font-black text-xs sm:text-sm tracking-wide">Total CIA (75)</th>
                      </tr>
                    </thead>
                    <tbody id="labEvaluationsTableBody" class="divide-y divide-slate-800/50">
                      <tr>
                        <td colspan="14" class="p-8 text-center text-slate-400 font-bold text-sm">Loading students...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Lab CO-PO Articulation Matrix Workspace -->
            <div id="labCoPoMappingContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800/60 pb-4">
                <div>
                  <h4 class="text-sm font-black text-slate-200">CO-PO &amp; CO-PSO Mapping Articulation Matrix</h4>
                  <p class="text-sm text-slate-400 mt-1">Map each Course Outcome (CO1 - CO4) to Program Outcomes (PO1 - PO11) and Program Specific Outcomes (PSO1 - PSO3) on a scale of 1 to 3.</p>
                </div>
                <button onclick="saveCoPoMappingMatrix()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md">
                  <span class="material-symbols-rounded text-base">save</span> Save Matrix
                </button>
              </div>

              <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse min-w-[900px] text-xs">
                    <thead>
                      <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-300 font-bold uppercase">
                        <th class="p-3 w-16">CO</th>
                        <th class="p-3">Course Outcome Statement</th>
                        <!-- POs -->
                        <th class="p-1 text-center w-12">PO1</th>
                        <th class="p-1 text-center w-12">PO2</th>
                        <th class="p-1 text-center w-12">PO3</th>
                        <th class="p-1 text-center w-12">PO4</th>
                        <th class="p-1 text-center w-12">PO5</th>
                        <th class="p-1 text-center w-12">PO6</th>
                        <th class="p-1 text-center w-12">PO7</th>
                        <th class="p-1 text-center w-12">PO8</th>
                        <th class="p-1 text-center w-12">PO9</th>
                        <th class="p-1 text-center w-12">PO10</th>
                        <th class="p-1 text-center w-12">PO11</th>
                        <!-- PSOs -->
                        <th class="p-1 text-center w-12 text-blue-300">PSO1</th>
                        <th class="p-1 text-center w-12 text-blue-300">PSO2</th>
                        <th class="p-1 text-center w-12 text-blue-300">PSO3</th>
                      </tr>
                    </thead>
                    <tbody id="labCoPoMappingTbody" class="divide-y divide-slate-850">
                      <tr>
                        <td colspan="16" class="p-8 text-center text-slate-400 font-bold">Loading articulation matrix...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG / MY PROFILE -->
      <div id="panelSecurity" class="hidden space-y-6 animate-fade-in">
        @include('partials.staff_profile_panel')
      </div>

      <!-- PANEL: MOBILE SEMINAR EVALUATION WORKSPACE -->
      <!-- PANEL: MOBILE SEMINAR EVALUATION -->
      <div id="panelMobileSeminar" class="hidden fade-up">

        <!-- Header — NO Sign Out here, sidebar already has it -->
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/60">
          <button onclick="switchPanel('dashboard')" class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-premium cursor-pointer shrink-0">
            <span class="material-symbols-rounded text-slate-200 text-lg">arrow_back</span>
          </button>
          <div>
            <h3 class="text-lg font-black text-white flex items-center gap-2 leading-tight">
              <span class="material-symbols-rounded text-blue-400 text-xl">co_present</span> Virtual Seminar Room
            </h3>
            <p class="text-sm text-slate-400 mt-0.5">Evaluate student seminar presentations for today.</p>
          </div>
        </div>

        <!-- Seminar Presentations Today dynamic notifications section (Mobile Panel) -->
        <div id="mobileSeminarNotificationsContainer" class="hidden grid grid-cols-1 gap-3 mb-5">
          <!-- Populated dynamically -->
        </div>

        <!-- Mobile toast -->
        <div id="mobileSemToast" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2"></div>

        <!-- Step 1: Pending Invitations -->
        <div id="mobileSemStep1" class="space-y-4">

          <!-- Pending Invitations -->
          <div class="bg-slate-900/60 border border-amber-600/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-amber-600/20 flex items-center gap-3 bg-amber-950/20">
              <span class="material-symbols-rounded text-amber-400 text-xl">mark_email_unread</span>
              <h4 class="text-base font-black text-amber-200">Pending Invitations</h4>
            </div>
            <div id="mobilePendingInvitationsList" class="p-4 space-y-3">
              <div class="text-sm text-slate-400 text-center py-4">Loading...</div>
            </div>
          </div>

          <!-- Accepted / Start Evaluation -->
          <div class="bg-slate-900/60 border border-emerald-700/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-emerald-700/20 flex items-center gap-3 bg-emerald-950/20">
              <span class="material-symbols-rounded text-emerald-400 text-xl">how_to_reg</span>
              <h4 class="text-base font-black text-emerald-200">Attending Seminars</h4>
            </div>
            <div class="p-4 space-y-3">
              <div id="mobileSemAttendingList" class="space-y-2">
                <div class="text-sm text-slate-400 text-center py-4">No accepted seminars yet.</div>
              </div>
            </div>
          </div>

        </div>

        <!-- Step 2: Evaluation Form (shown when a student is selected) -->
        <div id="mobileSemStep2" class="hidden space-y-4">

          <!-- Student Info Card -->
          <div class="bg-gradient-to-br from-blue-950/80 to-indigo-950/80 border border-blue-600/40 rounded-2xl p-5 shadow-xl shadow-blue-900/20">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <div id="mobSemStudentName" class="text-xl font-black text-white leading-tight">-</div>
                <div class="text-sm text-slate-300 mt-1">SBTE Reg: <span id="mobSemSbteRegV2" class="font-mono text-blue-300 font-bold">-</span></div>
                <div class="mt-3 bg-blue-950/60 border border-blue-800/40 rounded-xl px-4 py-3">
                  <div class="text-xs text-blue-400 uppercase tracking-wider font-bold mb-1">Seminar Topic</div>
                  <div id="mobSemTopicV2" class="text-base font-bold text-white leading-snug">-</div>
                </div>
              </div>
              <!-- Live Score Ring -->
              <div class="shrink-0 flex flex-col items-center">
                <div class="relative w-20 h-20">
                  <svg class="w-20 h-20 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="26" fill="none" stroke="#1e293b" stroke-width="6"/>
                    <circle id="mobScoreRingCircle" cx="32" cy="32" r="26" fill="none" stroke="#3b82f6" stroke-width="6"
                      stroke-dasharray="163.36" stroke-dashoffset="163.36" stroke-linecap="round"
                      style="transition: stroke-dashoffset 0.4s ease, stroke 0.3s ease"/>
                  </svg>
                  <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="mobSemRingScore" class="text-lg font-black text-white leading-none">0</span>
                    <span class="text-xs text-slate-400 leading-none mt-0.5">/75</span>
                  </div>
                </div>
                <span class="text-xs text-slate-400 mt-1.5 font-bold uppercase tracking-wide">Your Score</span>
              </div>
            </div>
          </div>

          <!-- Evaluation Criteria Form -->
          <form id="mobileSeminarForm" onsubmit="submitMobileSeminarEvaluation(event)" class="space-y-3">

            <!-- Relevance -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Relevance</div>
                  <div class="text-xs text-slate-400 mt-0.5">Topic alignment & suitability</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemRelevance" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemRelevance').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Literature -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Literature Survey</div>
                  <div class="text-xs text-slate-400 mt-0.5">Depth of research & references</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemLiterature" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemLiterature').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-indigo-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Presentation (largest weight) -->
            <div class="bg-slate-900/70 border border-blue-600/40 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-blue-300">Presentation Quality</div>
                  <div class="text-xs text-slate-400 mt-0.5">Clarity, structure & delivery — highest weight</div>
                </div>
                <div class="bg-slate-800 border border-blue-700/50 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="37.5" id="mobSemPresentation" required
                    oninput="clampMobSem(this,37.5); calcMobSemTotal()"
                    class="w-16 bg-transparent text-blue-300 font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/37.5</span>
                </div>
              </div>
              <input type="range" min="0" max="37.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemPresentation').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-400 bg-slate-700 cursor-pointer">
            </div>

            <!-- Last 3 criteria in a row -->
            <div class="grid grid-cols-3 gap-3">
              <!-- Interaction -->
              <div class="bg-slate-900/70 border border-purple-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-purple-300 uppercase tracking-wide text-center">Interaction</div>
                <div class="text-xs text-slate-400 text-center">Q&A</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemInteraction" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-purple-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-purple-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Report -->
              <div class="bg-slate-900/70 border border-teal-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-teal-300 uppercase tracking-wide text-center">Report</div>
                <div class="text-xs text-slate-400 text-center">Written</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemReport" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-teal-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-teal-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Attendance -->
              <div class="bg-slate-900/70 border border-emerald-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-emerald-300 uppercase tracking-wide text-center">Attendance</div>
                <div class="text-xs text-slate-400 text-center">Presence</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemAttendance" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-emerald-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-emerald-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
            </div>

            <!-- Total + Submit -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-950 border border-slate-600/60 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-xl">
              <div>
                <div class="text-sm text-slate-400 font-bold uppercase tracking-wider mb-1">Total Score</div>
                <div class="text-xl font-black text-slate-500" id="mobSemTotalDisplay">
                  <span id="mobSemTotalNum" class="text-blue-400">0.00</span> / 75
                </div>
                <!-- keep old ID for backward compat -->
                <div id="mobSemTotalScoreLabel" class="hidden"></div>
              </div>
              <button type="submit" id="mobSemSubmitBtn"
                class="px-6 py-4 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl font-black text-base shadow-lg shadow-blue-500/30 transition-premium cursor-pointer flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">save</span> Save
              </button>
            </div>

            <button type="button" onclick="backToSeminarList()" class="w-full py-3.5 text-slate-300 text-sm font-bold flex items-center justify-center gap-2 cursor-pointer hover:text-white transition-premium border border-slate-700/50 rounded-xl hover:bg-slate-800/50">
              <span class="material-symbols-rounded text-base">arrow_back</span> Back to Seminar List
            </button>

          </form>
        </div>

      </div>



    </div>
  </main>

  <script>
    // Self-executing theme preference loader to run immediately and prevent flashing dark theme
    // Self-executing theme preference loader & panel pre-loader to run immediately and prevent flashing
    (function() {
      const savedTheme = localStorage.getItem('theme-preference');
      if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        window.addEventListener('DOMContentLoaded', () => {
          const icon = document.getElementById('themeToggleIcon');
          const text = document.getElementById('themeToggleText');
          if (icon) icon.innerText = 'dark_mode';
          if (text) text.innerText = 'Dark Mode';
        });
      }

      // Check if URL specifies subject_id to pre-switch classroom panel and active tab immediately
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('subject_id')) {
        const initialTab = urlParams.get('tab');
        if (initialTab) {
          window.currentActiveVirtualTab = initialTab;
        }
        window.addEventListener('DOMContentLoaded', () => {
          const dash = document.getElementById('panelDashboard');
          const cls = document.getElementById('panelClassroom');
          if (dash) dash.classList.add('hidden');
          if (cls) cls.classList.remove('hidden');

          if (initialTab) {
            const tabContentMap = {
              'lab_evaluation': 'labEvaluationContent',
              'structure': 'courseStructureContent',
              'planner': 'coursePlannerContent',
              'assessment': 'formativeAssessmentContent',
              'summative': 'summativeAssessmentContent',
              'qbank': 'questionBankContent',
              'survey': 'surveysContent',
              'seminar_evaluation': 'seminarEvaluationContent',
              'lab_copo': 'labCoPoMappingContent',
              'course_attainment': 'courseAttainmentContent',
              'reports': 'classReportsContent'
            };
            const targetId = tabContentMap[initialTab];
            if (targetId) {
              const targetEl = document.getElementById(targetId);
              const structEl = document.getElementById('courseStructureContent');
              if (targetEl && structEl && targetId !== 'courseStructureContent') {
                structEl.classList.add('hidden');
                targetEl.classList.remove('hidden');
                if (initialTab !== 'structure') targetEl.classList.add('flex');
              }
            }
          }
        });
      }
    })();

    function toggleTheme() {
      const body = document.body;
      const isLight = body.classList.toggle('light-theme');
      localStorage.setItem('theme-preference', isLight ? 'light' : 'dark');
      
      const icon = document.getElementById('themeToggleIcon');
      const text = document.getElementById('themeToggleText');
      if (isLight) {
        if (icon) icon.innerText = 'dark_mode';
        if (text) text.innerText = 'Dark Mode';
      } else {
        if (icon) icon.innerText = 'light_mode';
        if (text) text.innerText = 'Light Mode';
      }
    }

    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (sessionStorage.getItem('openClassroomFromHOD') === 'true') {
        sessionStorage.removeItem('openClassroomFromHOD');
        // Instantly force load active batches list
        switchPanel('dashboard');
      }
      const urlParams = new URLSearchParams(window.location.search);
      const subjectId = urlParams.get('subject_id');
      const subjectName = urlParams.get('subject_name') || '';
      const classroomId = urlParams.get('classroom_id') || '';
      const tabParam = urlParams.get('tab');
      const revParam = urlParams.get('revision') || 'REV2021';
      const typeParam = urlParams.get('type') || 'Practical';
      if (subjectId) {
        if (tabParam) {
          window.currentActiveVirtualTab = tabParam;
        }
        openClassroom(classroomId, subjectId, subjectName, '', revParam, typeParam, tabParam);
      } else {
        loadLecturerBatches();
      }
      if (activePanel === 'security') loadSecurityLogs();
      checkTodaySeminars();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const sidebar = document.getElementById('mainSidebar');
      if (sidebar) {
        if (panelId === 'classroom' || panelId === 'mobileSeminar') {
          sidebar.classList.add('hidden');
        } else {
          sidebar.classList.remove('hidden');
        }
      }
      const panels = ['dashboard', 'security', 'classroom', 'mobileSeminar'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const headerBackBtn = document.getElementById('headerBackBtn');
      if (headerBackBtn) {
        if (panelId === 'dashboard') {
          headerBackBtn.classList.add('hidden');
          headerBackBtn.classList.remove('flex');
        } else {
          headerBackBtn.classList.remove('hidden');
          headerBackBtn.classList.add('flex');
        }
      }

      if (panelId === 'dashboard') {
        const pTitle = document.getElementById('panelTitle');
        if (pTitle) pTitle.innerHTML = '<span class="font-extrabold text-slate-100">My Batches</span>';
      } else if (panelId === 'security') {
        const pTitle = document.getElementById('panelTitle');
        if (pTitle) pTitle.innerHTML = '<span class="font-extrabold text-slate-100">My Profile Security Log</span>';
      } else if (panelId === 'mobileSeminar') {
        const pTitle = document.getElementById('panelTitle');
        if (pTitle) pTitle.innerHTML = '<span class="font-black text-slate-100">Seminar Evaluation</span>';
      }

      if (panelId === 'security') loadSecurityLogs();
      if (panelId === 'dashboard') loadLecturerBatches();
    }

    let currentDashboardFilter = 'active';

    function setDashboardBatchFilter(status) {
      currentDashboardFilter = status;
      
      const activeBtn = document.getElementById('btnFilterActive');
      const historicalBtn = document.getElementById('btnFilterHistorical');

      if (status === 'active') {
        if (activeBtn) activeBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
      } else {
        if (activeBtn) activeBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold text-slate-400 hover:text-slate-200 transition-premium cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'px-4 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow transition-premium cursor-pointer';
      }

      loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-500 font-bold text-[10px] animate-pulse">Loading batches...</div>';

      fetch(`/api/lecturer/my-batches?status=${currentDashboardFilter}`, {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-[10px]">${data.message}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900 rounded-xl text-[10px]">Error loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
            <span class="material-symbols-rounded text-5xl text-slate-700 mb-3">sentiment_dissatisfied</span>
            <p class="font-bold text-slate-300 text-sm">No batches assigned</p>
            <p class="text-xs text-slate-500 mt-1">You have not been assigned as a Tutor, Mentor, or Subject Staff for any batches yet.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let color = 'slate';
          if (r === 'Tutor') color = 'sky';
          if (r === 'Mentor') color = 'emerald';
          if (r === 'Subject Staff') color = 'violet';
          rolesHtml += `<span class="px-2 py-0.5 rounded text-[11px] font-bold bg-${color}-500/10 text-${color}-400 border border-${color}-500/20">${r}</span>`;
        });

        let brName = b.branch || (b.classroom_id ? b.classroom_id.split('_')[0] : 'CE');
        let brPrefix = `${brName}-`;

        const card = document.createElement('div');
        let yearBorderColor = 'border-t-violet-500';
        if (b.batch_year % 3 === 0) yearBorderColor = 'border-t-sky-500';
        else if (b.batch_year % 3 === 1) yearBorderColor = 'border-t-emerald-500';
        
        card.className = `bg-slate-950/40 border border-slate-800/80 ${yearBorderColor} border-t-[3px] rounded-2xl overflow-hidden flex flex-col transition-premium hover:shadow-xl hover:shadow-black/50 hover:border-slate-700/60`;
        card.innerHTML = `
          <div class="p-4 border-b border-slate-800/60 bg-slate-900/40">
            <div class="flex justify-between items-start">
              <div>
                <div class="flex items-center gap-1.5 flex-wrap mb-1">
                  <h4 class="font-black text-slate-100 text-lg tracking-tight">Admission ${b.batch_year}</h4>
                  ${b.branch ? `<span class="px-2 py-0.5 bg-slate-800 text-slate-300 border border-slate-600 rounded text-xs font-black font-mono">${b.branch}</span>` : ''}
                  <span class="px-2 py-0.5 bg-slate-800 text-slate-300 border border-slate-600 rounded text-xs font-black font-mono">${b.scheme || (b.classroom_id && b.classroom_id.includes('2026') ? 'R2026' : 'R2021')}</span>
                  ${(b.current_semester || 1) > 6
                    ? `<span class="px-2.5 py-0.5 bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 rounded-lg font-black text-xs select-none flex items-center gap-1"><span class="material-symbols-rounded" style="font-size:14px">school</span>Graduated</span>`
                    : `<span class="px-2.5 py-0.5 bg-indigo-600/20 border border-indigo-500/40 text-indigo-400 rounded-lg font-black text-xs select-none">SEM-${b.current_semester || 1}</span>`
                  }
                </div>
                <span class="inline-block px-2.5 py-0.5 bg-slate-800 border border-slate-600/60 rounded-lg font-mono text-sm font-bold text-slate-300 tracking-wide">${b.classroom_id}</span>
              </div>
              <div class="flex flex-col items-end gap-1">
                <div class="flex flex-wrap gap-1 justify-end">${rolesHtml}</div>
                <span class="flex items-center gap-1 text-xs font-bold text-slate-400 mt-1">
                  <span class="material-symbols-rounded" style="font-size:13px">group</span>${b.student_count || 0} students
                </span>
              </div>
            </div>
          </div>
          
          <div class="p-4 flex-grow space-y-3 bg-slate-950/20">
            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><span class="material-symbols-rounded text-xs">book</span> Assigned Subjects</h5>
            <div class="space-y-3 divide-y divide-slate-800/80">
              ${b.subjects && b.subjects.length > 0 ? b.subjects.map((s, idx) => {
                let topicsPct = s.total_topics > 0 ? Math.round((s.covered_topics / s.total_topics) * 100) : 0;
                let hoursPct  = s.total_hours  > 0 ? Math.round((s.engaged_hours  / s.total_hours)  * 100) : 0;
                let barPct    = topicsPct || hoursPct;
                let barColor  = barPct >= 80 ? 'from-emerald-500 to-teal-400' : barPct >= 50 ? 'from-blue-500 to-sky-400' : 'from-violet-500 to-indigo-400';
                
                let rawCode = s.code || '';
                let formattedCode = (rawCode.startsWith(brPrefix) || (rawCode.includes('-') && !rawCode.startsWith('S-')))
                  ? rawCode
                  : `${brPrefix}${rawCode.replace(/^[A-Z]+-/, '')}`;
                
                return `
                  <div class="${idx > 0 ? 'pt-3' : ''} w-full">
                    <div class="w-full px-3.5 py-3 bg-slate-900/80 border border-slate-800 rounded-xl transition-premium group hover:border-blue-500/50 hover:bg-slate-900 flex flex-col gap-2">
                      <div class="flex justify-between items-center cursor-pointer" onclick="openClassroom('${b.classroom_id}', '${s.id}', '${s.name}', '${formattedCode}', '${s.syllabus_revision_code || 'REV2021'}', '${s.type}')">
                        <div class="flex-1 min-w-0 pr-2">
                          <div class="text-base font-extrabold text-slate-200 group-hover:text-blue-400 transition-premium truncate">${s.name}</div>
                          <div class="text-xs text-slate-450 font-mono mt-0.5">Sem ${s.semester} · ${s.type} · ${formattedCode}</div>
                        </div>
                        <span class="material-symbols-rounded text-slate-600 group-hover:text-blue-500 text-base transition-premium flex-shrink-0">open_in_new</span>
                      </div>
                      <!-- Compact progress bar -->
                      <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 bg-slate-950 rounded-full h-1.5 overflow-hidden border border-slate-900">
                          <div class="bg-gradient-to-r ${barColor} h-1.5 rounded-full transition-all duration-500" style="width: ${barPct}%"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-400 whitespace-nowrap flex-shrink-0">${s.engaged_hours}/${s.total_hours} hrs</span>
                      </div>
                    </div>
                  </div>
                `;
              }).join('') : `<div class="text-xs text-slate-500 italic px-2 py-2">No subjects assigned in this batch.</div>`}
            </div>
          </div>
        `;
        grid.appendChild(card);
      });
    }

    let currentSubjectId = null;
    window.currentVirtualBatchId = '';
    window.currentVirtualSemester = '';

    function openClassroom(batchId, subjectId, subjectName, subjectCode, revision = 'REV2021', type = 'Theory', initialTab = null) {
      const sTypeLower = (type || '').toLowerCase();
      const sNameLower = (subjectName || '').toLowerCase();
      const isR26 = (revision === 'REV2026' || (batchId && batchId.includes('2026')));

      if (isR26) {
        if (sNameLower.includes('health') || sNameLower.includes('physical') || sTypeLower.includes('health') || sTypeLower.includes('physical')) {
          window.open(`/r26/classroom/health-physical/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('drawing') || sNameLower.includes('drawing') || sNameLower.includes('graphics') || sNameLower.includes('cad')) {
          window.open(`/r26/classroom/drawing/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('practicum') || type.includes('Practicum')) {
          window.open(`/r26/classroom/practicum/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('practical') || sTypeLower.includes('lab') || type.includes('Practical') || type.includes('Lab')) {
          window.open(`/r26/classroom/practical/${subjectId}`, '_blank');
          return;
        } else {
          window.open(`/r26/classroom/theory/${subjectId}`, '_blank');
          return;
        }
      } else {
        currentSubjectId = subjectId;
        window.currentVirtualRevision = revision;

        const isPractical = sTypeLower.includes('practical') || sTypeLower.includes('lab') || (type || '').includes('Practical') || (type || '').includes('Lab') || sNameLower.includes('practical') || sNameLower.includes('lab') || sNameLower.includes('practicum') || sNameLower.includes('workshop');
        window.isCurrentSubjectPractical = isPractical;
        const isSeminar   = sTypeLower.includes('seminar') || sNameLower.includes('seminar');
        const rStr        = revision ? revision.toString() : 'R-2021';
        let revLabel      = 'R -2021';
        if (rStr.includes('2026') || rStr.includes('26')) revLabel = 'R -2026';

        const pTitle = document.getElementById('panelTitle');
        if (pTitle) {
          if (isSeminar) {
            pTitle.innerHTML = `<span class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 rounded-xl font-extrabold text-sm md:text-base shadow-sm"><span class="material-symbols-rounded text-indigo-400 text-lg">co_present</span> Virtual Seminar Room</span>`;
          } else if (isPractical) {
            pTitle.innerHTML = `<span class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-sky-500/15 border border-sky-500/30 text-sky-300 rounded-xl font-black text-base md:text-lg lg:text-xl shadow-md tracking-tight"><span class="material-symbols-rounded text-sky-400 text-xl md:text-2xl">science</span> Virtual Lab ( ${revLabel} )</span>`;
          } else {
            pTitle.innerHTML = `<span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-500/30 text-blue-300 rounded-xl font-extrabold text-sm md:text-base shadow-sm"><span class="material-symbols-rounded text-blue-400 text-lg">meeting_room</span> VIrtual theory classroom  R-2021</span>`;
          }
        }

        let latText = '';
        if (batchId && batchId.includes('_LET')) {
          latText = ' <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-[10px] px-2 py-0.5 rounded-md ml-1">LATERAL ENTRY (LET)</span>';
        }

        const vcTitle = document.getElementById('vcTitle');
        if (vcTitle) {
          if (isPractical) {
            vcTitle.innerHTML = `<span class="material-symbols-rounded text-sky-400 text-xl">science</span> <span class="text-base md:text-lg font-black text-sky-300">Virtual Lab ( ${revLabel} )</span>`;
          } else {
            vcTitle.innerHTML = `<span class="material-symbols-rounded text-sky-400 text-base">science</span> ${subjectName || 'Virtual Lab Workspace'}`;
          }
        }

        let brName = 'CE';
        if (batchId) {
          const parts = batchId.split('_');
          if (parts.length > 0 && parts[0]) brName = parts[0];
        }

        const fullCode = document.getElementById('vcSubjectFullCode');
        const fullName = document.getElementById('vcSubjectFullName');
        if (fullCode) {
          const rawCode = subjectCode || '';
          const brPrefix = `${brName}-`;
          const formattedCode = (rawCode.startsWith(brPrefix) || (rawCode.includes('-') && !rawCode.startsWith('S-')))
            ? rawCode
            : `${brPrefix}${rawCode.replace(/^[A-Z]+-/, '')}`;
          fullCode.innerText = formattedCode;
        }
        if (fullName) fullName.innerText = subjectName || '';

        const bBadge = document.getElementById('vcBatchBadge');
        if (bBadge && batchId) bBadge.innerText = `Batch: ${batchId}`;

        const brBadge = document.getElementById('vcBranchBadge');
        if (brBadge) brBadge.innerText = `Branch: ${brName}`;

        const revBadge = document.getElementById('vcRevisionBadge');
        if (revBadge) {
          const rStr = revision ? revision.toString() : 'R-2021';
          revBadge.innerText = `Revision: ${rStr.startsWith('R-') ? rStr : 'R-' + rStr.replace('REV', '')}`;
        }

        switchPanel('classroom');
        if (initialTab) {
          window.currentActiveVirtualTab = initialTab;
          toggleClassroomTab(initialTab);
        }
        loadCourseDetails(subjectId);
      }
    }

    function handleSyllabusUpload(input) {
      if (!input || !input.files || input.files.length === 0) return;
      if (!currentSubjectId) return;

      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      const box = document.getElementById('syllabusUploadBox');
      const progress = document.getElementById('syllabusUploadProgress');
      const fileInput = document.getElementById('syllabusFileInput');

      if (box) box.classList.add('hidden');
      if (progress) progress.classList.remove('hidden');

      fetch(`/api/classroom/${currentSubjectId}/syllabus`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        if (box) box.classList.remove('hidden');
        if (progress) progress.classList.add('hidden');
        if (fileInput) fileInput.value = '';
        if (input) input.value = '';
        if (data.status === 'SUCCESS') {
          // Always generate a new lesson plan when syllabus is reuploaded
          fetch(`/api/classroom/${currentSubjectId}/lesson-plans/regenerate`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
          }).catch(err => {
            console.error('Error auto-generating lesson plan after syllabus upload:', err);
          }).finally(() => {
            loadCourseDetails(currentSubjectId).then(() => {
              toggleClassroomTab('structure');
            }).catch(err => {
              console.error('Error refreshing course details after syllabus upload:', err);
            });
          });
        } else {
          alert(data.message || 'Upload failed.');
        }
      })
      .catch(err => {
        if (box) box.classList.remove('hidden');
        if (progress) progress.classList.add('hidden');
        if (fileInput) fileInput.value = '';
        if (input) input.value = '';
        alert('Failed to upload syllabus: ' + err.message);
      });
    }

    function switchSurveySubTab(type) {
      const btnMid = document.getElementById('btnSubTabMidSem');
      const btnExit = document.getElementById('btnSubTabExit');
      const secMid = document.getElementById('midSemesterSurveySection');
      const secExit = document.getElementById('courseExitSurveySection');

      if (type === 'mid_sem') {
        if (btnMid) btnMid.className = 'px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer bg-blue-600 text-white shadow-sm flex items-center gap-1.5';
        if (btnExit) btnExit.className = 'px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 flex items-center gap-1.5';
        if (secMid) secMid.classList.remove('hidden');
        if (secExit) secExit.classList.add('hidden');
        fetchSurveyResults(currentSubjectId);
      } else {
        if (btnMid) btnMid.className = 'px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 flex items-center gap-1.5';
        if (btnExit) btnExit.className = 'px-4 py-2 rounded-lg text-xs font-bold transition-premium cursor-pointer bg-teal-600 text-white shadow-sm flex items-center gap-1.5';
        if (secMid) secMid.classList.add('hidden');
        if (secExit) secExit.classList.remove('hidden');
        fetchExitSurveyResults(currentSubjectId);
      }
    }

    function loadCourseAttainment() {
      const workspace = document.getElementById('courseAttainmentWorkspace');
      if (!workspace || !currentSubjectId) return;

      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
          <span class="material-symbols-rounded text-3xl text-indigo-400 animate-spin mb-3">progress_activity</span>
          <p class="text-sm font-bold text-slate-300">Calculating Course Attainment Metrics...</p>
        </div>
      `;

      fetch(`/api/r26/classroom/${currentSubjectId}/attainment-summary`)
        .then(res => res.json())
        .then(data => {
          if (data.status !== 'SUCCESS') {
            workspace.innerHTML = `
              <div class="p-6 text-center text-rose-400 font-bold bg-slate-900 border border-slate-800 rounded-xl">
                Failed to load attainment data.
              </div>
            `;
            return;
          }

          const summary = data.summary || {};
          const matrix = data.matrix || [];

          let rowsHtml = matrix.map(row => {
            const attained = row.attained;
            const badgeBg = attained 
              ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' 
              : 'bg-rose-500/10 text-rose-400 border-rose-500/30';

            return `
              <tr class="border-b border-slate-800/60 hover:bg-slate-900/30 transition-premium">
                <td class="p-3.5 font-bold text-blue-400 text-xs">${row.co}</td>
                <td class="p-3.5 text-center text-slate-300 text-xs font-mono">${row.direct_percent}%</td>
                <td class="p-3.5 text-center text-slate-300 text-xs font-mono">${row.indirect_percent}% <span class="text-[10px] text-slate-500">(${row.indirect_rating}/3)</span></td>
                <td class="p-3.5 text-center text-emerald-400 text-xs font-bold font-mono">${row.overall_percent}%</td>
                <td class="p-3.5 text-center text-slate-400 text-xs font-mono">${row.target_benchmark}%</td>
                <td class="p-3.5 text-center text-slate-300 text-xs font-bold">${row.attainment_level}</td>
                <td class="p-3.5 text-center">
                  <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-lg border ${badgeBg}">
                    ${attained ? 'ATTAINED ✓' : 'NOT MET'}
                  </span>
                </td>
              </tr>
            `;
          }).join('');

          workspace.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-slate-900/60 border border-slate-800 p-4 rounded-xl shadow-sm">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Direct Attainment (80%)</div>
                <div class="text-2xl font-black text-blue-400 mt-1">${summary.direct_attainment_percent}%</div>
                <div class="text-[11px] text-slate-500 mt-1">Formative Tasks & ESE Letter Grades</div>
              </div>
              <div class="bg-slate-900/60 border border-slate-800 p-4 rounded-xl shadow-sm">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Indirect Attainment (20%)</div>
                <div class="text-2xl font-black text-teal-400 mt-1">${summary.indirect_attainment_percent}%</div>
                <div class="text-[11px] text-slate-500 mt-1">Course Exit Survey Feedback</div>
              </div>
              <div class="bg-slate-900/60 border border-slate-800 p-4 rounded-xl shadow-sm">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Overall Attainment Level</div>
                <div class="text-2xl font-black text-emerald-400 mt-1">${summary.overall_attainment_level} (${summary.overall_attainment_percent}%)</div>
                <div class="text-[11px] text-emerald-500 font-bold mt-1">Target Student Benchmark: ${summary.target_benchmark}%</div>
              </div>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl overflow-hidden shadow-inner">
              <div class="p-4 bg-slate-900/80 border-b border-slate-800 flex justify-between items-center flex-wrap gap-2">
                <h5 class="text-xs font-black text-slate-200 uppercase tracking-wider">Course Outcome Attainment Summary Matrix</h5>
                <div class="flex items-center gap-3">
                  <span class="text-xs text-slate-400">Target Student Benchmark: <strong class="text-emerald-400 font-mono">${summary.target_benchmark}%</strong></span>
                  <button onclick="loadCourseAttainment()" title="Recalculate Attainment" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/60 rounded-lg text-[11px] font-bold transition-premium flex items-center gap-1 cursor-pointer">
                    <span class="material-symbols-rounded text-xs">refresh</span> Recalculate
                  </button>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                  <thead>
                    <tr class="bg-slate-900/40 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                      <th class="p-3.5">Course Outcome</th>
                      <th class="p-3.5 text-center">Direct Attainment (80%)</th>
                      <th class="p-3.5 text-center">Indirect Exit Survey (20%)</th>
                      <th class="p-3.5 text-center">Overall CO Attainment</th>
                      <th class="p-3.5 text-center">Target Benchmark</th>
                      <th class="p-3.5 text-center">Attainment Level</th>
                      <th class="p-3.5 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${rowsHtml}
                  </tbody>
                </table>
              </div>
            </div>
          `;
        })
        .catch(err => {
          workspace.innerHTML = `
            <div class="p-6 text-center text-rose-400 font-bold bg-slate-900 border border-slate-800 rounded-xl">
              Error connecting to server.
            </div>
          `;
        });
    }

    function toggleClassroomTab(tabName) {
      window.currentActiveVirtualTab = tabName;
      const tabs = [
        { id: 'structure', btn: 'tabStructure', content: 'courseStructureContent' },
        { id: 'planner', btn: 'tabPlanner', content: 'coursePlannerContent' },
        { id: 'assessment', btn: 'tabAssessment', content: 'formativeAssessmentContent' },
        { id: 'summative', btn: 'tabSummative', content: 'summativeAssessmentContent' },
        { id: 'qbank', btn: 'tabQBank', content: 'questionBankContent' },
        { id: 'survey', btn: 'tabSurvey', content: 'surveysContent' },
        { id: 'seminar_evaluation', btn: 'tabSeminar', content: 'seminarEvaluationContent' },
        { id: 'lab_evaluation', btn: 'tabLab', content: 'labEvaluationContent' },
        { id: 'lab_copo', btn: 'tabLabCoPo', content: 'labCoPoMappingContent' },
        { id: 'course_attainment', btn: 'tabCourseAttainment', content: 'courseAttainmentContent' },
        { id: 'reports', btn: 'tabReports', content: 'classReportsContent' }
      ];

      tabs.forEach(t => {
        const btn = document.getElementById(t.btn);
        const content = document.getElementById(t.content);
        
        if (btn) {
          const isHidden = btn.classList.contains('hidden');
          if (t.id === tabName) {
            btn.className = "px-3.5 py-2 text-[11px] md:text-xs font-bold text-blue-400 bg-blue-500/5 border-t-2 border-x-2 border-b-transparent border-blue-400 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px shadow-[0_-4px_10px_rgba(59,130,246,0.2)]" + (isHidden ? " hidden" : "");
          } else {
            btn.className = "px-3.5 py-2 text-[11px] md:text-xs font-medium text-slate-400 hover:text-slate-200 border-t-2 border-x-2 border-b-transparent border-transparent hover:border-slate-800/80 rounded-t-xl flex items-center gap-2 transition-all cursor-pointer whitespace-nowrap -mb-px" + (isHidden ? " hidden" : "");
          }
        }

        if (content) {
          if (t.id === tabName) {
            content.classList.remove('hidden');
            if (t.id !== 'structure') content.classList.add('flex');
          } else {
            content.classList.add('hidden');
            if (t.id !== 'structure') content.classList.remove('flex');
          }
        }
      });

      if (tabName === 'reports') {
        fetchClassReports();
        if (window.isCurrentSubjectPractical) {
          fetchPracticalEvaluations();
        }
      } else if (tabName === 'assessment') {
        if (window.isCurrentSubjectPractical) {
          fetchPracticalEvaluations();
        }
      } else if (tabName === 'qbank') {
        fetchQuestionBank(currentSubjectId);
      } else if (tabName === 'survey') {
        switchSurveySubTab('mid_sem');
      } else if (tabName === 'seminar_evaluation') {
        fetchSeminarEvaluations();
      } else if (tabName === 'lab_evaluation') {
        fetchPracticalEvaluations();
      } else if (tabName === 'summative' || tabName === 'tabSummative') {
        if (window.isCurrentSubjectPractical) {
          fetchPracticalEvaluations();
        }
      } else if (tabName === 'lab_copo') {
        fetchPracticalCoPoMapping();
      } else if (tabName === 'course_attainment') {
        loadCourseAttainment();
      }
    }

    let classReportsData = null;
    let activeReportType = 'attendance_log';
    let currentDeadlines = {};
    let currentQuestions = {};
    let currentSummativeTests = {};
    let currentSubjectName = '';
    let currentSubjectCode = '';
    let currentSubjectSemester = '';
    let currentSubjectAcademicYear = '';
    let currentSubjectClassroomId = '';

    function loadCourseDetails(subjectId) {
      currentSubjectId = subjectId;
      const csContent = document.getElementById('courseStructureContent');
      if (csContent) {
        csContent.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
            <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
            <p class="text-[10px] font-bold text-slate-400">Loading course data...</p>
          </div>
        `;
      }
      const cpContent = document.getElementById('coursePlannerContent');
      if (cpContent) {
        cpContent.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
            <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
            <p class="text-[10px] font-bold text-slate-400">Loading planner...</p>
          </div>
        `;
      }
      const sWorkspace = document.getElementById('surveyWorkspace');
      if (sWorkspace) {
        sWorkspace.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
            <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
            <p class="text-sm font-bold text-slate-400">Loading survey details...</p>
          </div>
        `;
      }
      const activeSyllabusCard = document.getElementById('activeSyllabusCard');
      if (activeSyllabusCard) activeSyllabusCard.classList.add('hidden');
      
      const statusBadge = document.getElementById('parseStatusBadge');
      if (statusBadge) {
        statusBadge.innerText = 'Syncing...';
        statusBadge.className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';
      }

      return fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
          currentDeadlines = data.data.assignment_deadlines || {};
          currentQuestions = data.data.assignment_questions || {};
          currentSummativeTests = data.data.summative_manual_tests || {};
          currentSubjectName = data.data.subject_name || '';
          currentSubjectCode = data.data.subject_code || '';
          const fullName = document.getElementById('vcSubjectFullName');
          const fullCode = document.getElementById('vcSubjectFullCode');
          if (fullName) fullName.innerText = currentSubjectName;
          if (fullCode) {
            fullCode.innerText = currentSubjectCode ? `[ ${currentSubjectCode} ]` : '';
          }

          const bBadge = document.getElementById('vcBatchBadge');
          if (bBadge) {
            const bId = data.data.classroom_id || window.currentVirtualBatchId || '';
            if (bId) bBadge.innerText = `Batch: ${bId}`;
          }

          const semBadge = document.getElementById('vcSemBadge');
          if (semBadge) semBadge.innerText = `SEM: ${data.data.semester || 'S4'}`;

          const brBadge = document.getElementById('vcBranchBadge');
          if (brBadge && data.data.branch) brBadge.innerText = `Branch: ${data.data.branch}`;

          const revBadge = document.getElementById('vcRevisionBadge');
          if (revBadge && data.data.syllabus_revision) {
            const rVal = data.data.syllabus_revision.toString();
            revBadge.innerText = `Revision: ${rVal.startsWith('R-') ? rVal : 'R-' + rVal.replace('REV', '')}`;
          }

          const hcBadge = document.getElementById('vcHoursCreditsBadge');
          if (hcBadge) {
            const pHours = data.data.proposed_total_hours || 60;
            const creds = data.data.credits || 2.0;
            hcBadge.innerText = `Proposed Hours: ${pHours} hrs (+2 tests) | Credits: ${creds}`;
          }

          const marksBadge = document.getElementById('vcMarksBadge');
          if (marksBadge) {
            const cia = data.data.cia_marks || 60;
            const ese = data.data.ese_marks || 40;
            marksBadge.innerText = `CIA: ${cia}M | ESE: ${ese}M`;
          }

          const dlBtn = document.getElementById('downloadSyllabusBtn');
          if (dlBtn) {
            if (data.data.syllabus_pdf_path) {
              dlBtn.classList.remove('hidden');
              dlBtn.classList.add('inline-flex');
              dlBtn.dataset.url = `/api/classroom/${subjectId}/syllabus/download`;
            } else {
              dlBtn.classList.add('hidden');
              dlBtn.classList.remove('inline-flex');
              delete dlBtn.dataset.url;
            }
          }
          currentSubjectSemester = data.data.semester || '';
          currentSubjectAcademicYear = data.data.academic_year || '';
          currentSubjectClassroomId = data.data.classroom_id || '';
          window.currentSyllabusRevision = data.data.syllabus_revision || '2021';
          window.currentVirtualStudents = data.data.students || [];
          window.currentVirtualSemester = data.data.semester || '';
          window.currentProposedTotalHours = data.data.proposed_total_hours || 60;
          const subjectTypeRaw = (data.data.subject_type || '').toLowerCase();
          const sNameLower = (data.data.subject_name || '').toLowerCase();
          const sCodeLower = (data.data.subject_code || '').toLowerCase();
          const isSeminar = subjectTypeRaw === 'seminar' || sNameLower.includes('seminar');
          const isPractical = subjectTypeRaw === 'practical' || subjectTypeRaw === 'lab' || subjectTypeRaw.includes('lab') || subjectTypeRaw.includes('practical') || subjectTypeRaw.includes('practicum') || sNameLower.includes('lab') || sNameLower.includes('practical') || sNameLower.includes('practicum') || sNameLower.includes('workshop');
          window.isCurrentSubjectPractical = isPractical;

          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          renderFormativeAssessment(data.data.students || []);
          renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);
          
          // Always render the formative questions section (show prompt if none generated yet)
          renderAIQuestionsList(currentQuestions, subjectId);

          const tabSeminar = document.getElementById('tabSeminar');
          const tabLab = document.getElementById('tabLab');
          const tabLabCoPo = document.getElementById('tabLabCoPo');
          const tabStructure = document.getElementById('tabStructure');
          const tabPlanner = document.getElementById('tabPlanner');
          const tabAssessment = document.getElementById('tabAssessment');
          const tabSummative = document.getElementById('tabSummative');
          const tabReports = document.getElementById('tabReports');
          const tabQBank = document.getElementById('tabQBank');
          const tabSurvey = document.getElementById('tabSurvey');
          const tabCourseAttainment = document.getElementById('tabCourseAttainment');
          const pRepActions = document.getElementById('practicalReportsActions');
          const vcTitle = document.getElementById('vcTitle');

          const pTitleBox = document.getElementById('panelTitle');
          const rStrVal = window.currentVirtualRevision || 'R-2021';
          let revLabelVal = 'R -2021';
          if (rStrVal.includes('2026') || rStrVal.includes('26')) revLabelVal = 'R -2026';

          const activeTabToRestore = window.currentActiveVirtualTab || 'structure';

          if (isSeminar) {
            if (pTitleBox) pTitleBox.innerHTML = `<span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-xl font-extrabold text-sm md:text-base shadow-sm"><span class="material-symbols-rounded text-emerald-400 text-lg">co_present</span> Virtual Seminar Room</span>`;
            if (vcTitle) vcTitle.innerHTML = `<span class="material-symbols-rounded text-emerald-400 text-sm">co_present</span> Virtual Seminar Room`;
            if (tabSeminar) tabSeminar.classList.remove('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.add('hidden');
            if (tabPlanner) tabPlanner.classList.add('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.add('hidden');
            if (tabReports) tabReports.classList.add('hidden');
            if (tabQBank) tabQBank.classList.add('hidden');
            if (tabSurvey) tabSurvey.classList.add('hidden');
            if (tabCourseAttainment) tabCourseAttainment.classList.add('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab(activeTabToRestore);
          } else if (isPractical) {
            if (pTitleBox) pTitleBox.innerHTML = `<span class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-sky-500/15 border border-sky-500/30 text-sky-300 rounded-xl font-black text-base md:text-lg lg:text-xl shadow-md tracking-tight"><span class="material-symbols-rounded text-sky-400 text-xl md:text-2xl">science</span> Virtual Lab ( ${revLabelVal} )</span>`;
            if (vcTitle) vcTitle.innerHTML = `<span class="material-symbols-rounded text-sky-400 text-sm">science</span> Virtual Lab Workspace`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.remove('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.remove('hidden');
            if (tabReports) tabReports.classList.remove('hidden');
            if (tabQBank) tabQBank.classList.remove('hidden');
            if (tabSurvey) tabSurvey.classList.remove('hidden');
            if (tabCourseAttainment) tabCourseAttainment.classList.remove('hidden');
            if (pRepActions) {
              pRepActions.classList.remove('hidden');
              pRepActions.classList.add('flex');
              const fullWsBtn = document.getElementById('openFullVirtualLabBtn');
              const isR26Val = rStrVal.includes('2026') || rStrVal.includes('26');
              if (fullWsBtn) fullWsBtn.href = isR26Val ? `/r26/classroom/practical/${subjectId}` : `/classroom/practical/${subjectId}`;
              const printBtn = document.getElementById('printLabReportBtn');
              if (printBtn) printBtn.href = `/classroom/practical/${subjectId}/report/print`;
              const btnReg = document.getElementById('pRepBtnRegister');
              if (btnReg) btnReg.href = `/classroom/${subjectId}/practical-report/print?type=register`;
              const btnAtt = document.getElementById('pRepBtnAttendance');
              if (btnAtt) btnAtt.href = `/classroom/${subjectId}/practical-report/print?type=attendance`;
              const btnExp = document.getElementById('pRepBtnExperiments');
              if (btnExp) btnExp.href = `/classroom/${subjectId}/practical-report/print?type=experiments`;
              const btnPlan = document.getElementById('pRepBtnPlanner');
              if (btnPlan) btnPlan.href = `/classroom/${subjectId}/practical-report/print?type=planner`;
              const btnProj = document.getElementById('pRepBtnProjects');
              if (btnProj) btnProj.href = `/classroom/${subjectId}/practical-report/print?type=projects`;
            }
            toggleClassroomTab(activeTabToRestore);
          } else {
            if (pTitleBox) pTitleBox.innerHTML = `<span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-500/30 text-blue-300 rounded-xl font-extrabold text-sm md:text-base shadow-sm"><span class="material-symbols-rounded text-blue-400 text-lg">meeting_room</span> VIrtual theory classroom  R-2021</span>`;
            if (vcTitle) vcTitle.innerHTML = `<span class="material-symbols-rounded text-blue-400 text-xs">meeting_room</span> VIrtual theory classroom  R-2021`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.remove('hidden');
            if (tabSummative) tabSummative.classList.remove('hidden');
            if (tabReports) tabReports.classList.remove('hidden');
            if (tabQBank) tabQBank.classList.remove('hidden');
            if (tabSurvey) tabSurvey.classList.remove('hidden');
            if (tabCourseAttainment) tabCourseAttainment.classList.remove('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab(activeTabToRestore);
          }

          // Update vcTitle to include subject name for regular classrooms
          if (!isSeminar && !isPractical && vcTitle) {
            vcTitle.innerHTML = `<span class="material-symbols-rounded text-blue-400 text-xs">meeting_room</span> VIrtual theory classroom  R-2021 ( ${currentSubjectName || ''} )`;
          }

          const badge = document.getElementById('parseStatusBadge');
          if (data.data.syllabus_pdf_path) {
            const dlBtn = document.getElementById('downloadSyllabusBtn');
            if (dlBtn) {
              dlBtn.classList.remove('hidden');
              dlBtn.classList.add('inline-flex');
              dlBtn.dataset.url = `/api/classroom/${subjectId}/syllabus/download`;
            }
            if (badge) badge.innerText = 'Syllabus Uploaded ✓';
          } else {
            if (badge) {
              badge.innerText = 'Syllabus not uploaded';
              badge.className = 'h-9 px-3.5 inline-flex items-center justify-center gap-1.5 text-xs font-bold bg-slate-900/90 text-slate-400 border border-slate-800 rounded-xl whitespace-nowrap shrink-0';
            }
          }
        } else {
          const badge = document.getElementById('parseStatusBadge');
          if (badge) {
            badge.innerText = 'Syllabus not uploaded';
            badge.className = 'h-9 px-3.5 inline-flex items-center justify-center gap-1.5 text-xs font-bold bg-slate-900/90 text-slate-400 border border-slate-800 rounded-xl whitespace-nowrap shrink-0';
          }
          const csContent = document.getElementById('courseStructureContent');
          if (csContent) {
            csContent.innerHTML = `
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">inventory_2</span>
                </div>
                <p class="text-sm font-bold text-slate-400">No syllabus loaded.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
                <button onclick="document.getElementById(\'syllabusFileInput\').click()" class="mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/20 transition cursor-pointer flex items-center gap-2">
                  <span class="material-symbols-rounded text-sm">upload_file</span> Upload Syllabus PDF
                </button>
              </div>
            `;
          }
          const cpContent = document.getElementById('coursePlannerContent');
          if (cpContent) {
            cpContent.innerHTML = `
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">event_note</span>
                </div>
                <p class="text-sm font-bold text-slate-400">Planner not generated.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
              </div>
            `;
          }
          const faContent = document.getElementById('formativeAssessmentContent');
          if (faContent) {
            faContent.innerHTML = `
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">assignment_turned_in</span>
                </div>
                <p class="text-sm font-bold text-slate-400">Formative Assessment Inactive.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks and mark entry.</p>
              </div>
            `;
          }
          const saContent = document.getElementById('summativeAssessmentContent');
          if (saContent) {
            saContent.innerHTML = `
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                  <span class="material-symbols-rounded text-xl text-slate-600">quiz</span>
                </div>
                <p class="text-sm font-bold text-slate-400">Summative Assessment Inactive.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate written test configuration and mark entry.</p>
              </div>
            `;
          }
          const qbContent = document.getElementById('questionBankContent');
          if (qbContent) {
              qbContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                  <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                    <span class="material-symbols-rounded text-xl text-slate-600">database</span>
                  </div>
                  <p class="text-sm font-bold text-slate-400">Question Bank Inactive.</p>
                  <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate the question bank pooling.</p>
                </div>
              `;
          }
        }
      })
      .catch(err => {
        console.error('[loadCourseDetails] Handled gracefully:', err);
        const badge = document.getElementById('parseStatusBadge');
        if (badge) {
          badge.innerText = 'Syllabus not uploaded';
          badge.className = 'h-9 px-3.5 inline-flex items-center justify-center gap-1.5 text-xs font-bold bg-slate-900/90 text-slate-400 border border-slate-800 rounded-xl whitespace-nowrap shrink-0';
        }
        const csContent = document.getElementById('courseStructureContent');
        if (csContent) {
          csContent.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
                <span class="material-symbols-rounded text-xl text-slate-600">inventory_2</span>
              </div>
              <p class="text-sm font-bold text-slate-300">Syllabus not uploaded yet.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
              <button onclick="document.getElementById(\'syllabusFileInput\').click()" class="mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/20 transition cursor-pointer flex items-center gap-2">
                <span class="material-symbols-rounded text-sm">upload_file</span> Upload Syllabus PDF
              </button>
            </div>
          `;
        }
      });
    }

    /**
     * Opens the syllabus PDF by opening the controller URL via a hidden anchor click.
     * This keeps the session cookie active (same-origin) and avoids popup blockers.
     */
    function downloadSyllabusPDF() {
      const btn = document.getElementById('downloadSyllabusBtn');
      if (!btn) return;
      const url = btn.dataset.url;
      if (!url) {
        alert('No syllabus file available for this subject.');
        return;
      }
      const a = document.createElement('a');
      a.href = url;
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    // CO colour palette for lesson planner badges
    const CO_COLORS = {
      'CO1': 'bg-blue-500/10 text-blue-400 border-blue-500/20',
      'CO2': 'bg-violet-500/10 text-violet-400 border-violet-500/20',
      'CO3': 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
      'CO4': 'bg-amber-500/10 text-amber-400 border-amber-500/20',
      'CO5': 'bg-rose-500/10 text-rose-400 border-rose-500/20',
      'CO6': 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
    };

    function autoGrowTextarea(el) {
      if (!el) return;
      el.style.height = 'auto';
      el.style.height = Math.max(42, el.scrollHeight + 2) + 'px';
    }

    function renderPedagogySelect(selectedValue, lpId) {
      const options = ['Lecture', 'Demo', 'Lab', 'Test', 'Discussion', 'Seminar', 'Tutorial', 'Assignment', 'Presentation'];
      let val = (selectedValue || 'Lecture').trim();
      if (val && !options.some(o => o.toLowerCase() === val.toLowerCase())) {
        options.push(val);
      }
      let optionsHtml = options.map(o => `<option value="${o}" ${o.toLowerCase() === val.toLowerCase() ? 'selected' : ''}>${o}</option>`).join('');
      return `<select data-field="pedagogy" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-xs font-medium focus:outline-none focus:border-blue-500/50" onchange="markPlanDirty('${lpId}')">${optionsHtml}</select>`;
    }

    function renderCoursePlanner(lessonPlans) {
      const container = document.getElementById('coursePlannerContent');
      if (!container) return;

      // ── Empty state ──────────────────────────────────────────────────────────
      if (!lessonPlans || lessonPlans.length === 0) {
        let emptyIcon  = window.isCurrentSubjectPractical ? 'science' : 'event_note';
        let emptyColor = window.isCurrentSubjectPractical ? 'text-teal-400' : 'text-sky-400';
        let genBtn = window.isCurrentSubjectPractical
          ? `<button onclick="openGeneratePlannerModal()" class="px-3 py-1.5 bg-gradient-to-r from-teal-600 to-emerald-500 hover:from-teal-500 hover:to-emerald-400 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-md shadow-teal-900/20">
              <span class="material-symbols-rounded text-xs">auto_awesome</span> Auto-Generate (Lab)
             </button>`
          : `<button onclick="regenerateLessonPlan()" class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-md shadow-blue-900/20">
              <span class="material-symbols-rounded text-xs">auto_awesome</span> Generate Lesson Plan
             </button>`;
        let loadBtn = `<button onclick="loadLessonPlanTemplate()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer border border-slate-700/50 flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">download</span> Load Template
            </button>`;
        container.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center h-full">
            <div class="bg-slate-900/50 p-4 rounded-full mb-4 border border-slate-800/60">
              <span class="material-symbols-rounded text-xl ${emptyColor}">${emptyIcon}</span>
            </div>
            <p class="text-sm font-bold text-slate-400">No Lesson Plan Generated Yet</p>
            <p class="text-xs mt-1.5 max-w-xs text-slate-500 leading-relaxed mb-6">
              Generate a smart plan based on Course Outcomes and Modules, or load a saved template.
            </p>
            <div class="flex items-center gap-3 flex-wrap justify-center">
              ${genBtn}
              ${loadBtn}
            </div>
          </div>
        `;
        return;
      }

      // ── Populated state ──────────────────────────────────────────────────────
      let totalHours = lessonPlans.reduce((sum, lp) => sum + (lp.allocated_hours || 0), 0);
      let testDays   = lessonPlans.filter(lp => (lp.pedagogy || '').toLowerCase() === 'test').length;
      let lectureDays = lessonPlans.length - testDays;
      let proposedVal = window.currentProposedTotalHours || 60;

      let coList = (window.currentCos && window.currentCos.length > 0)
        ? window.currentCos.map(c => c.id || c.co_id)
        : ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'];

      // Header buttons
      const smallBtnCss = `font-size: 11px !important; font-weight: 700; line-height: 1.2; padding: 4px 10px;`;
      let practicalRegenBtn = window.isCurrentSubjectPractical
        ? `<button onclick="openGeneratePlannerModal()" style="${smallBtnCss}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/50 rounded-lg transition-premium cursor-pointer flex items-center gap-1">
             <span class="material-symbols-rounded" style="font-size: 13px;">science</span> Regenerate (Lab)
           </button>` : '';

      let html = `
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4 pb-3 border-b border-slate-800/60">
          <div>
            <h4 class="text-sm font-black text-slate-200">Lesson Planner</h4>
            <p class="text-xs text-slate-500 mt-0.5">${lectureDays} lecture days · ${testDays} test days · ${totalHours} total hours (Syllabus Proposed: ${proposedVal} hours) · Auto-growing content textareas</p>
          </div>
          <div class="flex items-center gap-1.5 flex-wrap">
            ${practicalRegenBtn}
            <select id="coursePlannerBatchFilter" onchange="filterCoursePlannerRowsByBatch(this.value)" style="${smallBtnCss}" class="bg-slate-900 border border-slate-700/60 text-slate-200 rounded-lg focus:outline-none focus:border-blue-500/50 cursor-pointer ${window.isCurrentSubjectPractical ? '' : 'hidden'}">
              <option value="Full">Batch: Full</option>
              <option value="A">Batch: A</option>
              <option value="B">Batch: B</option>
            </select>
            <button onclick="regenerateLessonPlan()" id="btnRegenPlan" style="${smallBtnCss}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700/50 rounded-lg transition-premium cursor-pointer flex items-center gap-1" title="Re-generate all lesson plans from stored syllabus data">
              <span class="material-symbols-rounded" style="font-size: 13px;">refresh</span> Regenerate
            </button>
            <button onclick="saveLessonPlanChanges()" id="btnSavePlan" style="${smallBtnCss}" class="bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg transition-premium cursor-pointer flex items-center gap-1">
              <span class="material-symbols-rounded" style="font-size: 13px;">save</span> Save Changes
            </button>
            <button onclick="saveLessonPlanAsTemplate()" id="btnSavePlanTemplate" style="${smallBtnCss}" class="bg-violet-800/80 hover:bg-violet-700/80 text-violet-200 border border-violet-600/30 rounded-lg transition-premium cursor-pointer flex items-center gap-1" title="Save as reusable template for other batches with the same subject">
              <span class="material-symbols-rounded" style="font-size: 13px;">bookmark_add</span> Save as Template
            </button>
            <button onclick="loadLessonPlanTemplate()" style="${smallBtnCss}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700/50 rounded-lg transition-premium cursor-pointer flex items-center gap-1" title="Load previously saved template">
              <span class="material-symbols-rounded" style="font-size: 13px;">download</span> Load Template
            </button>
            <a href="/classroom/${currentSubjectId}/lesson-plan/print" target="_blank" style="${smallBtnCss}" class="bg-sky-800 hover:bg-sky-700 text-white rounded-lg transition-premium cursor-pointer flex items-center gap-1" title="Print Lesson Plan (A4)">
              <span class="material-symbols-rounded" style="font-size: 13px;">print</span> Print Plan
            </a>
          </div>
        </div>

        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]" id="lessonPlanTable">
              <thead>
                <tr class="bg-slate-900/80 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-10 text-center">#</th>
                  <th class="p-3 w-20 text-center">CO</th>
                  ${window.isCurrentSubjectPractical ? '<th class="p-3 w-24 text-center">Batch</th>' : ''}
                  <th class="p-3">Topic / Content <span class="text-slate-600 normal-case font-normal">(auto-grow)</span></th>
                  <th class="p-3 w-32">Proposed Date</th>
                  <th class="p-3 w-32">Actual Date</th>
                  <th class="p-3 w-16 text-center">Hrs</th>
                  <th class="p-3 w-28">Pedagogy</th>
                  <th class="p-3 w-32">Remarks</th>
                  <th class="p-3 w-10 text-center">Action</th>
                </tr>
              </thead>
              <tbody>
      `;

      lessonPlans.forEach((lp, index) => {
        let co        = lp.co_id || '';
        let proposed  = lp.proposed_date || '';
        let actualDateVal = lp.actual_date || '';
        let pedagogy  = lp.pedagogy || 'Lecture';
        let remarks   = (lp.remarks || '').replace(/"/g, '&quot;');
        let topic     = (lp.topic_content || '');
        let dayNo     = lp.day_no || (index + 1);
        let isTest    = pedagogy.toLowerCase() === 'test';
        let rowBg     = isTest ? 'bg-amber-950/20 border-amber-900/20' : 'border-slate-800/40';

        let coSelectOptions = `<option value="">--</option>` + coList.map(c => `<option value="${c}" ${c === co ? 'selected' : ''}>${c}</option>`).join('');

        let subBatchVal = lp.sub_batch || 'Whole';
        html += `
          <tr class="border-b ${rowBg} last:border-0 hover:bg-slate-900/20 transition-premium course-planner-row" data-lp-id="${lp.id}" data-sub-batch="${subBatchVal}">
            <td class="p-2 text-center text-xs font-bold text-slate-600">
              <input type="number" value="${dayNo}" data-field="day_no"
                class="w-10 bg-transparent border border-transparent hover:border-slate-700/60 text-center text-slate-400 text-xs font-bold rounded py-0.5 focus:outline-none focus:bg-slate-900/80 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2 text-center">
              <select data-field="co_id" class="bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-blue-400 text-xs font-bold focus:outline-none focus:border-blue-500/50" onchange="markPlanDirty(${lp.id})">
                ${coSelectOptions}
              </select>
            </td>
            ${window.isCurrentSubjectPractical ? `
            <td class="p-2 text-center">
              <select data-field="sub_batch" class="bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-sky-400 text-xs font-bold focus:outline-none focus:border-blue-500/50" onchange="markPlanDirty(${lp.id}); this.closest('tr').setAttribute('data-sub-batch', this.value);">
                <option value="Whole" ${subBatchVal==='Whole'||subBatchVal==='Full'||!subBatchVal?'selected':''}>Full</option>
                <option value="Batch A" ${subBatchVal==='Batch A'||subBatchVal==='A'?'selected':''}>A</option>
                <option value="Batch B" ${subBatchVal==='Batch B'||subBatchVal==='B'?'selected':''}>B</option>
              </select>
            </td>
            ` : ''}
            <td class="p-2">
              <textarea data-field="topic" rows="2"
                class="w-full bg-slate-900/80 border border-slate-700/60 focus:border-blue-500/50 rounded p-2 text-slate-200 text-xs focus:outline-none transition-all resize-none leading-relaxed overflow-hidden"
                placeholder="Enter topic content..."
                oninput="autoGrowTextarea(this); markPlanDirty(${lp.id})"
                onfocus="autoGrowTextarea(this)"
                onkeyup="autoGrowTextarea(this)">${topic}</textarea>
            </td>
            <td class="p-2">
              <input type="date" value="${proposed}" data-field="proposed_date"
                class="w-full bg-slate-900/60 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-xs focus:outline-none focus:border-blue-500/50 font-mono"
                onchange="markPlanDirty(${lp.id}); autoSavePlanRow(${lp.id}, this.closest('tr'))">
            </td>
            <td class="p-2">
              <input type="date" value="${actualDateVal}" data-field="actual_date"
                class="w-full bg-slate-900/60 border border-slate-700/60 rounded px-2 py-1 text-emerald-400 text-xs focus:outline-none focus:border-blue-500/50 font-mono"
                onchange="markPlanDirty(${lp.id}); autoSavePlanRow(${lp.id}, this.closest('tr'))">
            </td>
            <td class="p-2 text-center">
              <input type="text" value="${lp.allocated_hours || 1}" data-field="allocated_hours"
                class="w-10 bg-transparent border border-transparent hover:border-slate-700/60 text-center text-slate-400 text-xs font-mono rounded py-0.5 focus:outline-none focus:bg-slate-900/80"
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2">
              ${renderPedagogySelect(pedagogy, lp.id)}
            </td>
            <td class="p-2">
              <input type="text" value="${remarks}" data-field="remarks"
                class="w-full bg-transparent border border-transparent hover:border-slate-700/60 focus:border-blue-500/50 focus:bg-slate-900/60 rounded px-2 py-1 text-slate-500 text-xs focus:outline-none transition-all"
                placeholder="Add remarks..."
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2 text-center">
              <button onclick="deleteLessonPlanRow(this)" class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 p-1 rounded transition cursor-pointer" title="Delete row">
                <span class="material-symbols-rounded text-base">close</span>
              </button>
            </td>
          </tr>
        `;
      });

      html += `
              </tbody>
            </table>
          </div>
          <div class="p-3 bg-slate-900/90 border-t border-slate-800/60 flex items-center justify-between gap-3 flex-wrap">
            <button onclick="addLessonPlanRow()" style="${smallBtnCss}" class="bg-slate-800 hover:bg-slate-700 text-blue-400 hover:text-blue-300 rounded-lg transition-premium cursor-pointer flex items-center gap-1 border border-slate-700/60 shadow-md">
              <span class="material-symbols-rounded" style="font-size: 13px;">add_circle</span> Add Row
            </button>
            <div class="flex items-center gap-2">
              <button onclick="saveLessonPlanChanges()" id="btnSavePlanBottom" style="${smallBtnCss}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700/60 rounded-lg transition-premium cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded" style="font-size: 13px;">save</span> Save
              </button>
              <button onclick="saveLessonPlanChanges()" id="btnSaveNowBottom" style="${smallBtnCss}" class="bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-premium cursor-pointer flex items-center gap-1 shadow-md shadow-emerald-900/20">
                <span class="material-symbols-rounded" style="font-size: 13px;">check_circle</span> Save Now
              </button>
            </div>
          </div>
        </div>

        <div id="planSaveStatusBar" class="hidden mt-3 px-4 py-2.5 bg-amber-900/20 border border-amber-500/20 rounded-xl flex items-center gap-3 text-xs font-bold text-amber-400">
          <span class="material-symbols-rounded text-sm animate-pulse">edit</span>
          <span>You have unsaved changes.</span>
          <button onclick="saveLessonPlanChanges()" class="ml-auto px-3 py-1 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg cursor-pointer transition-premium">
            Save Now
          </button>
        </div>
      `;

      container.innerHTML = html;
      setTimeout(() => {
        container.querySelectorAll('textarea[data-field="topic"]').forEach(ta => autoGrowTextarea(ta));
      }, 50);
    }

    function filterCoursePlannerRowsByBatch(batchVal) {
      const rows = document.querySelectorAll('.course-planner-row');
      rows.forEach(row => {
        const subBatch = row.getAttribute('data-sub-batch') || 'Whole';
        if (batchVal === 'Full' || batchVal === 'all' || !batchVal) {
          row.style.display = '';
        } else if (batchVal === 'A' && (subBatch === 'Batch A' || subBatch === 'A' || subBatch === 'Batch 1' || subBatch === 'Whole')) {
          row.style.display = '';
        } else if (batchVal === 'B' && (subBatch === 'Batch B' || subBatch === 'B' || subBatch === 'Batch 2' || subBatch === 'Whole')) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    function addLessonPlanRow() {
      const tbody = document.querySelector('#lessonPlanTable tbody');
      if (!tbody) return;

      const rowCount = tbody.querySelectorAll('tr').length;
      const nextDayNo = rowCount + 1;
      const tempId = 'new_' + Date.now();

      let coList = (window.currentCos && window.currentCos.length > 0)
        ? window.currentCos.map(c => c.id || c.co_id)
        : ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'];
      let coOptionsHtml = `<option value="">--</option>` + coList.map(c => `<option value="${c}">${c}</option>`).join('');

      const tr = document.createElement('tr');
      tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 transition-premium';
      tr.setAttribute('data-lp-id', tempId);

      tr.innerHTML = `
        <td class="p-2 text-center text-xs font-bold text-slate-600">
          <input type="number" value="${nextDayNo}" data-field="day_no" class="w-10 bg-slate-900/80 border border-slate-700/60 text-center text-slate-300 text-xs rounded py-0.5 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2 text-center">
          <select data-field="co_id" class="bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-blue-400 text-xs font-bold focus:outline-none focus:border-blue-500/50" onchange="markPlanDirty('${tempId}')">
            ${coOptionsHtml}
          </select>
        </td>
        ${window.isCurrentSubjectPractical ? `
        <td class="p-2 text-center">
          <select data-field="sub_batch" class="bg-slate-900/80 border border-slate-700/60 rounded px-1.5 py-1 text-sky-400 text-xs font-bold focus:outline-none focus:border-blue-500/50" onchange="markPlanDirty('${tempId}'); this.closest('tr').setAttribute('data-sub-batch', this.value);">
            <option value="Whole">Full</option>
            <option value="Batch A">A</option>
            <option value="Batch B">B</option>
          </select>
        </td>
        ` : ''}
        <td class="p-2">
          <textarea data-field="topic" rows="2" class="w-full bg-slate-900/80 border border-slate-700/60 focus:border-blue-500/50 rounded p-2 text-slate-200 text-xs focus:outline-none transition-all resize-none leading-relaxed overflow-hidden" placeholder="Enter topic content..." oninput="autoGrowTextarea(this); markPlanDirty('${tempId}')" onfocus="autoGrowTextarea(this)" onkeyup="autoGrowTextarea(this)"></textarea>
        </td>
        <td class="p-2">
          <input type="date" value="" data-field="proposed_date" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-xs focus:outline-none focus:border-blue-500/50 font-mono" onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2">
          <input type="date" value="" data-field="actual_date" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-emerald-400 text-xs focus:outline-none focus:border-blue-500/50 font-mono" onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2 text-center">
          <input type="text" value="1" data-field="allocated_hours" class="w-10 bg-slate-900/80 border border-slate-700/60 text-center text-slate-300 text-xs rounded py-0.5 focus:outline-none font-mono" onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2">
          ${renderPedagogySelect('Lecture', tempId)}
        </td>
        <td class="p-2">
          <input type="text" value="" data-field="remarks" class="w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-400 text-xs focus:outline-none focus:border-blue-500/50" placeholder="Remarks..." onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2 text-center">
          <button onclick="deleteLessonPlanRow(this)" class="text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 p-1 rounded transition cursor-pointer" title="Delete row">
            <span class="material-symbols-rounded text-base">close</span>
          </button>
        </td>
      `;

      tbody.appendChild(tr);
      markPlanDirty(tempId);
      const textarea = tr.querySelector('textarea');
      if (textarea) autoGrowTextarea(textarea);
    }

    function resequenceLessonPlanRows() {
      const rows = document.querySelectorAll('#lessonPlanTable tbody tr');
      rows.forEach((row, index) => {
        const dayInput = row.querySelector('input[data-field="day_no"]');
        if (dayInput) {
          const newDayNo = index + 1;
          if (parseInt(dayInput.value) !== newDayNo) {
            dayInput.value = newDayNo;
            const lpId = row.getAttribute('data-lp-id');
            if (lpId) markPlanDirty(lpId);
          }
        }
      });
    }

    function deleteLessonPlanRow(btn) {
      const tr = btn.closest('tr');
      if (!tr) return;
      const lpId = tr.getAttribute('data-lp-id');
      if (lpId && !lpId.startsWith('new_')) {
        if (!confirm('Are you sure you want to remove this lesson plan row?')) return;
        fetch(`/api/classroom/${currentSubjectId}/lesson-plans/${lpId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(r => r.json()).catch(e => console.error(e));
      }
      tr.remove();
      resequenceLessonPlanRows();
      const bar = document.getElementById('planSaveStatusBar');
      if (bar) { bar.classList.remove('hidden'); bar.classList.add('flex'); }
    }

    // Track which rows have been edited
    window._dirtyPlanRows = new Set();

    function markPlanDirty(lpId) {
      window._dirtyPlanRows.add(lpId);
      const bar = document.getElementById('planSaveStatusBar');
      if (bar) { bar.classList.remove('hidden'); bar.classList.add('flex'); }
    }

    // Auto-save a single row immediately (for date changes)
    function autoSavePlanRow(lpId, row) {
      if (!row || String(lpId).startsWith('new_')) return;
      const rowData = collectPlanRow(lpId, row);
      if (!rowData) return;
      fetch(`/api/classroom/${currentSubjectId}/save-lesson-plans`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows: [rowData] })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') window._dirtyPlanRows.delete(lpId);
      }).catch(e => console.error('Auto-save failed:', e));
    }

    function collectPlanRow(lpId, row) {
      if (!row) {
        row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
        if (!row) return null;
      }
      const isNew = String(lpId).startsWith('new_');
      return {
        id:              isNew ? null : (parseInt(lpId) || null),
        day_no:          row.querySelector('[data-field="day_no"]')?.value        || null,
        co_id:           row.querySelector('[data-field="co_id"]')?.value         || null,
        sub_batch:       row.querySelector('[data-field="sub_batch"]')?.value     || 'Whole',
        topic_content:   row.querySelector('[data-field="topic"]')?.value          || '',
        proposed_date:   row.querySelector('[data-field="proposed_date"]')?.value  || null,
        actual_date:     row.querySelector('[data-field="actual_date"]')?.value    || null,
        allocated_hours: row.querySelector('[data-field="allocated_hours"]')?.value || 1,
        pedagogy:        row.querySelector('[data-field="pedagogy"]')?.value        || 'Lecture',
        remarks:         row.querySelector('[data-field="remarks"]')?.value         || '',
      };
    }

    function saveLessonPlanChanges() {
      const btnTop = document.getElementById('btnSavePlan');
      const btnBottom = document.getElementById('btnSavePlanBottom');
      const btnSaveNow = document.getElementById('btnSaveNowBottom');
      const rows = [];
      document.querySelectorAll('#lessonPlanTable tbody tr[data-lp-id]').forEach(row => {
        const lpId = row.getAttribute('data-lp-id');
        const data = collectPlanRow(lpId, row);
        if (data) rows.push(data);
      });
      if (rows.length === 0) { alert('Nothing to save.'); return; }

      if (btnTop) { btnTop.disabled = true; btnTop.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Saving...'; }
      if (btnBottom) { btnBottom.disabled = true; btnBottom.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Saving...'; }
      if (btnSaveNow) { btnSaveNow.disabled = true; btnSaveNow.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Saving...'; }

      fetch(`/api/classroom/${currentSubjectId}/save-lesson-plans`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          window._dirtyPlanRows.clear();
          const bar = document.getElementById('planSaveStatusBar');
          if (bar) { bar.classList.add('hidden'); bar.classList.remove('flex'); }
          if (btnTop) btnTop.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Saved!';
          if (btnBottom) btnBottom.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Saved!';
          if (btnSaveNow) btnSaveNow.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Saved!';
          setTimeout(() => {
            if (btnTop) { btnTop.disabled = false; btnTop.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save Changes'; }
            if (btnBottom) { btnBottom.disabled = false; btnBottom.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save'; }
            if (btnSaveNow) { btnSaveNow.disabled = false; btnSaveNow.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Save Now'; }
            loadCourseDetails(currentSubjectId).then(() => toggleClassroomTab('planner'));
          }, 1500);
        } else {
          alert(d.message || 'Save failed.');
          if (btnTop) { btnTop.disabled = false; btnTop.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save Changes'; }
          if (btnBottom) { btnBottom.disabled = false; btnBottom.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save'; }
          if (btnSaveNow) { btnSaveNow.disabled = false; btnSaveNow.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Save Now'; }
        }
      }).catch(e => {
        alert('Save failed: ' + e.message);
        if (btnTop) { btnTop.disabled = false; btnTop.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save Changes'; }
        if (btnBottom) { btnBottom.disabled = false; btnBottom.innerHTML = '<span class="material-symbols-rounded text-xs">save</span> Save'; }
        if (btnSaveNow) { btnSaveNow.disabled = false; btnSaveNow.innerHTML = '<span class="material-symbols-rounded text-xs">check_circle</span> Save Now'; }
      });
    }

    function regenerateLessonPlan() {
      if (!confirm('This will delete the current lesson plan and regenerate it from the stored syllabus data.\n\nAny manually entered dates and remarks will be lost.\n\nContinue?')) return;
      const btn = document.getElementById('btnRegenPlan');
      if (btn) { btn.disabled = true; btn.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Generating...'; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/regenerate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded text-xs">refresh</span> Regenerate'; }
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
        } else {
          alert(d.message || 'Regeneration failed.');
        }
      }).catch(e => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded text-xs">refresh</span> Regenerate'; }
        alert('Error: ' + e.message);
      });
    }

    function saveLessonPlanAsTemplate() {
      if (!confirm('Save the current lesson plan as a reusable template for all future batches of this subject?\n\nThis will overwrite any previously saved template for this subject code.')) return;
      const btn = document.getElementById('btnSavePlanTemplate');
      if (btn) { btn.disabled = true; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/save-as-template`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; }
        alert(d.status === 'SUCCESS' ? '✓ ' + d.message : '✗ ' + (d.message || 'Save failed.'));
      }).catch(e => {
        if (btn) { btn.disabled = false; }
        alert('Error: ' + e.message);
      });
    }

    function loadLessonPlanTemplate() {
      if (!confirm('Load the saved template for this subject?\n\nThis will replace the current lesson plan with the template. Existing proposed dates will be cleared.')) return;

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/load-template`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
        } else {
          alert(d.message || 'No template found for this subject.');
        }
      }).catch(e => alert('Error: ' + e.message));
    }

    // Wire up updateProposedDate (was a stub) — now handled by autoSavePlanRow via onchange
    function updateProposedDate(lpId, dateValue) {
      const row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
      autoSavePlanRow(lpId, row);
    }


    function renderFormativeAssessment(students) {
      if (window.isCurrentSubjectPractical) {
        const subjId = currentSubjectId || window.currentSubjectId;
        const studList = (typeof labStudentsData !== 'undefined' && labStudentsData && labStudentsData.length > 0) ? labStudentsData : (students || []);
        const totalExps = (typeof labExperimentsData !== 'undefined' && labExperimentsData) ? labExperimentsData.length : 0;
        
        let html = `
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 mb-4 no-print border-b border-slate-800/60 pb-3">
            <div>
              <h4 class="text-sm font-black text-slate-200 uppercase tracking-wide flex items-center gap-2">
                <span class="material-symbols-rounded text-teal-400 text-lg">science</span>
                Day-to-Day Practical Continuous Formative Assessment
              </h4>
              <p class="text-xs text-slate-400 mt-0.5">Continuous evaluation splitup breakdown: Rough Record (7.5M), Fair Record (10M), Observation &amp; Prep (10M), Procedure &amp; Punctuality (10M).</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="/classroom/practical/${subjId}/report/print" target="_blank" class="px-3.5 py-1.5 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/30 text-blue-400 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-rounded text-sm">print</span> Print Practical Register
              </a>
            </div>
          </div>

          <!-- Splitup Overview Banner -->
          <div class="mb-4 flex flex-wrap items-center justify-between gap-3 bg-slate-950/60 border border-slate-800/80 rounded-xl px-3.5 py-2 text-xs">
            <div class="flex items-center gap-1.5 font-bold text-slate-300">
              <span class="material-symbols-rounded text-teal-400 text-sm">analytics</span>
              <span>Continuous Lab Work Splitup (Max 37.5):</span>
            </div>
            <div class="flex flex-wrap items-center gap-x-3.5 gap-y-1 text-slate-400 text-[11px]">
              <span>Rough Record: <strong class="text-amber-400 font-bold font-mono">7.5 Marks</strong></span>
              <span class="text-slate-700 hidden sm:inline">•</span>
              <span>Fair Record: <strong class="text-emerald-400 font-bold font-mono">10.0 Marks</strong></span>
              <span class="text-slate-700 hidden sm:inline">•</span>
              <span>Obs. &amp; Prep: <strong class="text-sky-400 font-bold font-mono">10.0 Marks</strong></span>
              <span class="text-slate-700 hidden sm:inline">•</span>
              <span>Proc. &amp; Punctuality: <strong class="text-purple-400 font-bold font-mono">10.0 Marks</strong></span>
            </div>
          </div>

          <!-- Continuous Evaluation Splitup Table -->
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl overflow-hidden shadow-xl">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between">
              <div class="font-bold text-xs text-slate-300 flex items-center gap-2 tracking-wide uppercase">
                <span class="material-symbols-rounded text-base text-teal-400">table_chart</span> Day-to-Day Continuous Evaluation Breakdown
              </div>
              <span class="text-[11px] text-slate-400 font-mono">Enrolled: ${studList.length} Students</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                  <tr class="border-b border-slate-800/80 text-slate-400 font-semibold uppercase tracking-wider text-[11px] bg-slate-900/80">
                    <th class="p-2.5 w-12 text-center">Roll</th>
                    <th class="p-2.5">Student Name</th>
                    <th class="p-2.5 text-center">Graded Exps</th>
                    <th class="p-2.5 text-center text-amber-400">Rough Rec (7.5)</th>
                    <th class="p-2.5 text-center text-emerald-400">Fair Rec (15.0)</th>
                    <th class="p-2.5 text-center text-sky-400">Obs &amp; Prep (7.5)</th>
                    <th class="p-2.5 text-center text-purple-400">Proc &amp; Punct (7.5)</th>
                    <th class="p-2.5 text-center text-blue-400 bg-blue-500/5">Lab Work Avg (37.5)</th>
                    <th class="p-2.5 text-center w-24">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
        `;

        if (studList.length > 0) {
          studList.forEach((student, index) => {
            let gradedCount = 0;
            let sumRough = 0, sumFair = 0, sumObs = 0, sumProc = 0;

            if (student.experiments_marks) {
              const expEntries = Object.values(student.experiments_marks).filter(m => m !== null);
              gradedCount = expEntries.length;
              expEntries.forEach(m => {
                sumRough += parseFloat(m.rough_record || m.rough || 0);
                sumFair  += parseFloat(m.fair_record || m.fair || 0);
                sumObs   += parseFloat(m.obs_prep || m.obs || 0);
                sumProc  += parseFloat(m.proc_punct || m.proc || 0);
              });
            }

            const avgRough = gradedCount > 0 ? (sumRough / gradedCount).toFixed(2) : '0.00';
            const avgFair  = gradedCount > 0 ? (sumFair / gradedCount).toFixed(2)  : '0.00';
            const avgObs   = gradedCount > 0 ? (sumObs / gradedCount).toFixed(2)   : '0.00';
            const avgProc  = gradedCount > 0 ? (sumProc / gradedCount).toFixed(2)  : '0.00';
            const avgViva  = student.avg_viva_voce !== undefined && student.avg_viva_voce !== null ? parseFloat(student.avg_viva_voce).toFixed(2) : '0.00';

            const expAverage = student.avg_lab_work !== undefined && student.avg_lab_work !== null ? parseFloat(student.avg_lab_work).toFixed(2) : '0.00';

            html += `
              <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-xs">
                <td class="p-2.5 text-center font-bold text-slate-400 font-mono">${student.roll_no || (index + 1)}</td>
                <td class="p-2.5 font-bold text-slate-200">
                  <button type="button" onclick="openStudentLabModal('${student.reg_no}')" class="hover:text-teal-300 text-left cursor-pointer">
                    ${student.name}
                  </button>
                  <span class="block text-[10px] font-mono text-slate-400">${student.reg_no}</span>
                </td>
                <td class="p-2.5 text-center font-mono text-slate-400">${gradedCount} / ${totalExps}</td>
                <td class="p-2.5 text-center font-mono text-amber-400 font-semibold">${avgRough}</td>
                <td class="p-2.5 text-center font-mono text-emerald-400 font-semibold">${avgFair}</td>
                <td class="p-2.5 text-center font-mono text-sky-400 font-semibold">${avgObs}</td>
                <td class="p-2.5 text-center font-mono text-purple-400 font-semibold">${avgProc}</td>
                <td class="p-2.5 text-center font-mono text-rose-400 font-semibold">${avgViva}</td>
                <td class="p-2.5 text-center font-mono font-bold text-blue-400 bg-blue-500/5 text-sm">${expAverage}</td>
                <td class="p-2.5 text-center">
                  <button type="button" onclick="openStudentLabModal('${student.reg_no}')" class="px-2.5 py-1 bg-teal-600/20 hover:bg-teal-600/30 border border-teal-500/30 text-teal-300 text-xs font-bold rounded-xl transition flex items-center gap-1 mx-auto cursor-pointer">
                    <span class="material-symbols-rounded text-xs">edit</span> Grade
                  </button>
                </td>
              </tr>
            `;
          });
        } else {
          html += `<tr><td colspan="9" class="p-6 text-center text-slate-400 font-bold">No student evaluation records found.</td></tr>`;
        }

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;

        const faContent = document.getElementById('formativeAssessmentContent');
        if (faContent) faContent.innerHTML = html;
        return;
      }
      let html = `
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-sm text-slate-500 mt-1">Generate AI questions for each CO and record 10-mark evaluations.</p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="printAssignmentReport('${currentSubjectId}')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-500/10 cursor-pointer">
              <span class="material-symbols-rounded text-sm">print</span> Print Assignment Report
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}')" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-900/20 cursor-pointer">
              <span class="material-symbols-rounded text-sm">smart_toy</span> AI Generate Questions
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}', null, 'bank')" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-indigo-900/20 cursor-pointer">
              <span class="material-symbols-rounded text-sm">database</span> Pull from Question Bank
            </button>
          </div>
        </div>

        <div id="aiQuestionsContainer" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6" style="display:none;"></div>

        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between">
            <div class="font-bold text-base text-slate-300 flex items-center gap-2 tracking-wide uppercase">
              <span class="material-symbols-rounded text-base text-emerald-400">edit_note</span> Enter Assignment Marks
            </div>
            <button onclick="saveAssignmentMarks('${currentSubjectId}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer">
              Save Marks
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-900/40 text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1 (20)</th>
                  <th class="p-3 text-center w-20">CO2 (20)</th>
                  <th class="p-3 text-center w-20">CO3 (20)</th>
                  <th class="p-3 text-center w-20">CO4 (20)</th>
                </tr>
              </thead>
              <tbody id="markEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let m = student.assignment_marks || {};
          let sub = student.assignment_submissions || {};

          const getInputHtml = (co, val) => {
            let isSubmitted = (sub[co] === 'Submitted');
            let isGraded = val !== null && val !== '';
            let styleClasses = "co-mark w-full bg-slate-900 border rounded-lg px-2 py-2 text-slate-100 text-base font-bold focus:outline-none text-center ";
            let indicator = "";

            if (isGraded) {
              styleClasses += "border-slate-800 focus:border-blue-400";
            } else if (isSubmitted) {
              // Highlight input field with an amber border and a pulsing indicator dot
              styleClasses += "border-amber-500/70 bg-amber-950/20 focus:border-amber-400";
              indicator = `<span class="absolute right-2 top-1.5 flex h-2 w-2" title="Submitted by student"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>`;
            } else {
              styleClasses += "border-slate-700/60 focus:border-blue-400";
            }

            return `
              <div class="relative">
                <input type="number" step="1" max="20" min="0" value="${val !== null ? Math.round(val) : ''}" 
                       class="${styleClasses}" data-co="${co}">
                ${indicator}
              </div>
            `;
          };

          html += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/40 transition-premium" data-reg="${student.reg_no}">
              <td class="px-4 py-4 text-slate-400 font-bold text-base text-center">${index + 1}</td>
              <td class="px-4 py-4 font-bold text-slate-50 text-lg tracking-wide">${student.name}</td>
              <td class="px-4 py-4 font-mono text-slate-200 text-base">${student.reg_no}</td>
              <td class="px-4 py-4 font-mono text-slate-200 text-base">${student.sbte_reg_no || '-'}</td>
              <td class="px-3 py-3">${getInputHtml('CO1', m.CO1)}</td>
              <td class="px-3 py-3">${getInputHtml('CO2', m.CO2)}</td>
              <td class="px-3 py-3">${getInputHtml('CO3', m.CO3)}</td>
              <td class="px-3 py-3">${getInputHtml('CO4', m.CO4)}</td>
            </tr>
          `;
        });
      } else {
        html += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found in this classroom.</td></tr>`;
      }

      html += `</tbody></table></div></div>`;
      const faContent = document.getElementById('formativeAssessmentContent');
      if (faContent) faContent.innerHTML = html;
    }

    function renderAIQuestionsList(questionsData, subjectId) {
      const container = document.getElementById('aiQuestionsContainer');
      if (!container) return;
      container.style.display = 'grid';
      let html = '';
      
      // Show empty-state prompt if no questions have been generated yet
      if (!questionsData || Object.keys(questionsData).length === 0) {
        container.innerHTML = `
          <div class="col-span-full flex flex-col items-center justify-center py-12 text-center bg-slate-900/40 border border-dashed border-slate-700/60 rounded-xl">
            <span class="material-symbols-rounded text-5xl text-slate-600 mb-3">smart_toy</span>
            <p class="font-bold text-slate-300 text-sm mb-1">No Assignment Questions Yet</p>
            <p class="text-xs text-slate-500 mb-4">Click <strong>AI Generate Questions</strong> above to generate questions for all Course Outcomes using Gemini AI.</p>
          </div>
        `;
        return;
      }

      for (const [co, qs] of Object.entries(questionsData)) {
        let qList = qs.map(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : null;
          let marksVal = typeof q === 'object' ? q.marks : null;
          
          let cog = '';
          if (bt) {
            let color = bt.toLowerCase() === 'remember' ? 'text-blue-400' : (bt.toLowerCase() === 'apply' ? 'text-emerald-400' : 'text-indigo-400');
            cog = ` <span class="${color} font-bold">[${bt}]</span>`;
          } else {
            let lower = qText.toLowerCase();
            if (!lower.includes('[remember]') && !lower.includes('[u]') && !lower.includes('[a]') && !lower.includes('[r]') && !lower.includes('cognitive')) {
              if (lower.includes('define') || lower.includes('list') || lower.includes('what is') || lower.includes('state') || lower.includes('name')) {
                cog = ' <span class="text-blue-400 font-bold">[Remember - R]</span>';
              } else if (lower.includes('design') || lower.includes('solve') || lower.includes('calculate') || lower.includes('write') || lower.includes('implement') || lower.includes('apply') || lower.includes('draw')) {
                cog = ' <span class="text-emerald-400 font-bold">[Apply - A]</span>';
              } else {
                cog = ' <span class="text-indigo-400 font-bold">[Understand - U]</span>';
              }
            }
          }
          let marksText = marksVal ? ` <span class="text-slate-500 font-bold">(${marksVal} Marks)</span>` : '';
          return `<li class="text-sm text-slate-300 mb-2 leading-relaxed font-medium">${qText}${cog}${marksText}</li>`;
        }).join('');
        let schedule = currentDeadlines[co] || { start: '', due: '', locked: false };
        if (typeof schedule === 'string') schedule = { start: '', due: schedule, locked: false }; // Legacy fallback
        
        let isLocked = schedule.locked;
        let lockStr = isLocked ? `<span class="material-symbols-rounded text-[10px] text-amber-500 ml-1" title="Locked">lock</span>` : '';
        let disabledAttr = isLocked ? 'disabled' : '';
        let regenBtn = isLocked ? '' : `
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'ai')" class="p-1 rounded-lg bg-slate-800 hover:bg-blue-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Generate via AI (Gemini)">
                  <span class="material-symbols-rounded text-[14px] block">auto_awesome</span>
                </button>
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'bank')" class="p-1 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Pull from Question Bank Pool">
                  <span class="material-symbols-rounded text-[14px] block">database</span>
                </button>
        `;
        let editBtn = isLocked ? '' : `
                <button onclick="openEditQuestionsModal('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Manually Edit Questions">
                  <span class="material-symbols-rounded text-[14px] block">edit</span>
                </button>
        `;
        let lockBtn = isLocked ? '' : `
                <button onclick="toggleAssignmentLock('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
                  <span class="material-symbols-rounded text-[14px] block">lock</span>
                </button>
        `;
        let printBtn = `
                <button onclick="printAssignmentPaperAndRubrics('${subjectId}', '${co}')" class="p-1 rounded-lg bg-slate-800 hover:bg-emerald-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Print Assignment & Rubrics">
                  <span class="material-symbols-rounded text-[14px] block">print</span>
                </button>
        `;

        html += `
          <div class="bg-slate-900/50 border border-slate-800/60 p-4 rounded-xl relative overflow-hidden group ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-premium pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-3 border-b border-slate-800/60 pb-2 relative z-10">
              <h5 class="text-[10px] font-black text-blue-400 flex items-center gap-1">
                <span class="px-1.5 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-[10px] mr-1">${co}</span> Assignment ${lockStr}
              </h5>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[10px] text-slate-500 font-bold uppercase">Start</span>
                  <input type="date" value="${schedule.start || ''}" ${disabledAttr} class="bg-transparent text-[10px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'start', this.value)">
                </div>
                <div class="flex items-center gap-1 bg-slate-950/80 px-2 py-1 rounded border border-slate-700/50">
                  <span class="text-[10px] text-slate-500 font-bold uppercase">Due</span>
                  <input type="date" value="${schedule.due || ''}" ${disabledAttr} class="bg-transparent text-[10px] text-slate-300 font-mono outline-none w-20" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'due', this.value)">
                </div>
                ${regenBtn}
                ${editBtn}
                ${lockBtn}
                ${printBtn}
              </div>
            </div>
            
            <ul id="questions-list-${co}" class="list-none m-0 p-0 relative z-10 min-h-[60px]">${qList}</ul>
          </div>
        `;
      }
      document.getElementById('aiQuestionsContainer').innerHTML = html;
    }

    function generateAIQuestions(subjectId, coTag = null, mode = 'ai') {
      const qContainer = document.getElementById('aiQuestionsContainer');
      if (!coTag) {
        qContainer.style.display = 'grid';
        qContainer.innerHTML = `<div class="col-span-full text-center py-10 text-sm font-bold text-blue-400 animate-pulse flex flex-col items-center gap-3"><div class="w-8 h-8 border-2 border-blue-500/40 border-t-blue-400 rounded-full animate-spin"></div>Generating AI questions for all Course Outcomes...</div>`;
      } else {
        qContainer.style.display = 'grid';
        const ul = document.getElementById(`questions-list-${coTag}`);
        if(ul) ul.innerHTML = `<li class="text-sm text-blue-400 animate-pulse">Generating via Gemini AI...</li>`;
      }
      
      let url = `/api/classroom/${subjectId}/generate-questions?_t=${Date.now()}&generation_mode=${mode}`;
      if (coTag) url += `&co_tag=${coTag}`;

      fetch(url)
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (!coTag) {
             currentQuestions = data.data;
             renderAIQuestionsList(currentQuestions, subjectId);
          } else {
             currentQuestions[coTag] = data.data[coTag];
             const ul = document.getElementById(`questions-list-${coTag}`);
             if (ul && data.data[coTag]) {
               ul.innerHTML = data.data[coTag].map(q => `<li class="text-sm text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
             }
          }
        } else {
           qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">${data.message || 'Generation failed.'}</div>`;
        }
      })
      .catch(err => {
        console.error('AI Generate Error:', err);
        qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">Generation failed: ${err.message}. Check your API key and internet connection.</div>`;
      });
    }

    function updateAssignmentSchedule(subjectId, coTag, type, dateValue) {
      let payload = { co_tag: coTag };
      if (type === 'start') payload.start_date = dateValue;
      if (type === 'due') payload.due_date = dateValue;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           if (type === 'start') currentDeadlines[coTag].start = dateValue;
           if (type === 'due') currentDeadlines[coTag].due = dateValue;
           console.log(`Schedule for ${coTag} updated.`);
        } else {
           alert(data.message);
        }
      });
    }

    function toggleAssignmentLock(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} questions? This cannot be easily undone.`)) return;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           currentDeadlines[coTag].locked = true;
           renderAIQuestionsList(currentQuestions, subjectId);
        } else {
           alert(data.message);
        }
      });
    }

    let currentEditCo = '';
    let currentEditSubjectId = '';

    function openEditQuestionsModal(subjectId, coTag) {
      currentEditCo = coTag;
      currentEditSubjectId = subjectId;
      document.getElementById('editQuestionsCoBadge').innerText = coTag;
      
      const container = document.getElementById('editQuestionsFieldsContainer');
      container.innerHTML = '';

      let qs = currentQuestions[coTag] || [];
      if (qs.length === 0) {
        addManualQuestionField();
      } else {
        qs.forEach(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : 'Understand';
          let marksVal = typeof q === 'object' ? q.marks : 5;
          addManualQuestionField(qText, bt, marksVal);
        });
      }

      updateEditQuestionsTotalMarks();
      
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditQuestionsModal() {
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function addManualQuestionField(question = '', btLevel = 'Understand', marks = 5) {
      const container = document.getElementById('editQuestionsFieldsContainer');
      const div = document.createElement('div');
      div.className = "p-4 bg-slate-950/60 border border-slate-800/80 rounded-xl space-y-3 relative question-field-row shadow-sm";
      
      div.innerHTML = `
        <div class="flex justify-between items-center">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Question Description</span>
          <button type="button" onclick="this.closest('.question-field-row').remove(); updateEditQuestionsTotalMarks();" class="text-rose-400 hover:text-rose-300 cursor-pointer p-1 rounded hover:bg-rose-500/10 transition" title="Delete Question">
            <span class="material-symbols-rounded text-base">delete</span>
          </button>
        </div>
        <div>
          <textarea class="w-full bg-slate-900/90 border border-slate-700/60 rounded-xl p-3 text-slate-100 text-sm font-normal outline-none focus:border-blue-500/80 leading-relaxed resize-y min-h-[130px] q-text" rows="5" placeholder="Type descriptive question content..." required oninput="autoGrowTextarea(this)" onfocus="autoGrowTextarea(this)">${question}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">BT Level</label>
            <select class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs font-bold text-white focus:border-blue-500 outline-none q-bt">
              <option value="Remember" ${btLevel === 'Remember' ? 'selected' : ''}>Remember</option>
              <option value="Understand" ${btLevel === 'Understand' ? 'selected' : ''}>Understand</option>
              <option value="Apply" ${btLevel === 'Apply' ? 'selected' : ''}>Apply</option>
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Marks</label>
            <input type="number" class="w-full bg-slate-900 border border-slate-700/60 rounded-lg px-3 py-2 text-xs font-bold text-white focus:border-blue-500 outline-none q-marks" value="${marks}" min="1" max="20" onchange="updateEditQuestionsTotalMarks()" required>
          </div>
        </div>
      `;
      container.appendChild(div);
      const ta = div.querySelector('textarea.q-text');
      if (ta) autoGrowTextarea(ta);
      updateEditQuestionsTotalMarks();
    }

    function updateEditQuestionsTotalMarks() {
      let sum = 0;
      const inputs = document.querySelectorAll('#editQuestionsFieldsContainer .q-marks');
      inputs.forEach(input => {
        sum += parseInt(input.value || 0);
      });
      document.getElementById('editQuestionsTotalMarks').innerText = sum;
    }

    function saveManualQuestions() {
      const rows = document.querySelectorAll('#editQuestionsFieldsContainer .question-field-row');
      let questions = [];
      let totalMarks = 0;

      rows.forEach(row => {
        const text = row.querySelector('.q-text').value.trim();
        const bt = row.querySelector('.q-bt').value;
        const marks = parseInt(row.querySelector('.q-marks').value || 0);
        
        if (text) {
          questions.push({
            question: text,
            bt_level: bt,
            marks: marks
          });
          totalMarks += marks;
        }
      });

      if (questions.length === 0) {
        alert("Please add at least one question.");
        return;
      }

      if (totalMarks !== 20) {
        if (!confirm(`Warning: Total marks allocated is ${totalMarks}. The target is exactly 20 marks. Do you want to proceed anyway?`)) {
          return;
        }
      }

      fetch(`/api/classroom/${currentEditSubjectId}/save-assignment-questions`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          co_tag: currentEditCo,
          questions: questions
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentQuestions[currentEditCo] = questions;
          renderAIQuestionsList(currentQuestions, currentEditSubjectId);
          closeEditQuestionsModal();
          alert("Questions saved successfully.");
        } else {
          alert(data.message);
        }
      })
      .catch(() => alert("Failed to save assignment questions."));
    }

    function saveAssignmentMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#markEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.co-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-assignment-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updateProposedDate(lessonPlanId, dateValue) {
        console.log("Updating lesson plan", lessonPlanId, "with date", dateValue);
    }

    function renderCourseStructure(cos, modules, textbooks, copo) {
      // Filter out empty/blank COs and modules to show only populated ones
      if (cos && Array.isArray(cos)) {
        cos = cos.filter(co => co && co.description && co.description.trim() !== '' && co.description.trim() !== 'null');
      }
      if (modules && Array.isArray(modules)) {
        modules = modules.filter(m => m && m.content && m.content.trim() !== '' && m.content.trim() !== 'null');
      }

      // Debug: log what we received
      console.log('[renderCourseStructure] cos:', cos ? cos.length : 'null', '| modules:', modules ? modules.length : 'null', '| textbooks:', textbooks ? textbooks.length : 'null', '| copo keys:', copo ? Object.keys(copo).length : 'null');
      const container = document.getElementById('courseStructureContent');
      if (!container) { console.error('[renderCourseStructure] courseStructureContent not found!'); return; }

      let html = `
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-800/60">
          <span class="material-symbols-rounded text-sky-400 text-base">library_books</span>
          <div class="flex gap-3 text-xs font-bold text-slate-400">
            <span class="bg-sky-900/20 border border-sky-500/20 text-sky-400 px-2 py-0.5 rounded-lg">${cos ? cos.length : 0} COs</span>
            <span class="bg-violet-900/20 border border-violet-500/20 text-violet-400 px-2 py-0.5 rounded-lg">${modules ? modules.length : 0} Modules</span>
            <span class="bg-amber-900/20 border border-amber-500/20 text-amber-400 px-2 py-0.5 rounded-lg">${textbooks ? textbooks.length : 0} Textbooks</span>
          </div>
        </div>
      `;

      if (cos && cos.length > 0) {
        let cosList = cos.map(co => `
          <tr class="border-b border-slate-800/60 last:border-0 hover:bg-slate-900/50 transition-premium">
            <td class="p-4 font-black text-sky-400 whitespace-nowrap text-lg md:text-xl border-r border-slate-800/60 text-center align-middle">${co.id}</td>
            <td class="p-4 text-slate-100 leading-relaxed font-bold text-base md:text-xl align-middle">${co.description}</td>
            <td class="p-4 text-center font-black text-slate-100 whitespace-nowrap text-base md:text-lg border-l border-slate-800/60 align-middle">${co.duration ? co.duration + ' hrs' : '-'}</td>
            <td class="p-4 text-emerald-400 font-mono font-black whitespace-nowrap text-base md:text-lg border-l border-slate-800/60 text-center align-middle">${co.cognitive_level || '-'}</td>
          </tr>
        `).join('');
        html += `
          <div class="bg-slate-950/80 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl mb-6">
            <div class="px-6 py-4.5 bg-slate-900 border-b border-slate-800 font-black text-base text-slate-100 flex items-center justify-between tracking-wider uppercase">
              <span class="flex items-center gap-3 text-sky-400 font-black text-base md:text-lg">
                <span class="material-symbols-rounded text-2xl">target</span> Course Outcomes (COs)
              </span>
              <span class="text-xs md:text-sm font-bold text-sky-300 bg-sky-950/80 border border-sky-500/40 px-3.5 py-1.5 rounded-full lowercase">${cos.length} outcomes extracted</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="bg-slate-900/90 text-sm md:text-base font-black text-slate-200 uppercase tracking-wider border-b border-slate-800">
                    <th class="p-4 w-28 text-center border-r border-slate-800/60">CO ID</th>
                    <th class="p-4">Course Outcome Statement</th>
                    <th class="p-4 text-center w-36 border-l border-slate-800/60">Duration</th>
                    <th class="p-4 w-48 text-center border-l border-slate-800/60">Cognitive Level</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                  ${cosList}
                </tbody>
              </table>
            </div>
          </div>
        `;
      }

      let coKeys = [];
      if (copo && Object.keys(copo).length > 0) {
        coKeys = Object.keys(copo);
      } else if (cos && cos.length > 0) {
        coKeys = cos.map(c => c.id || c.co_id || 'CO1');
      }

      if (coKeys.length > 0) {
        let poHeaders = '';
        for (let i = 1; i <= 11; i++) {
          poHeaders += `<th class="p-2 text-center text-xs md:text-sm font-bold border-r border-slate-800/60 text-slate-200 bg-slate-900/80">PO${i}</th>`;
        }
        let psoHeaders = '';
        for (let i = 1; i <= 3; i++) {
          psoHeaders += `<th class="p-2 text-center text-xs md:text-sm font-bold border-r border-slate-800/60 last:border-r-0 text-blue-300 bg-blue-950/40">PSO${i}</th>`;
        }

        let copoList = coKeys.map(coKey => {
          let mapping = (copo && copo[coKey]) ? copo[coKey] : {};
          
          let poCells = '';
          for (let i = 1; i <= 11; i++) {
            let val = mapping['PO' + i];
            let valStr = (val !== null && val !== undefined && val !== '-' && val !== '0' && val !== 0) ? val : '';
            poCells += `
              <td class="p-1.5 text-center border-r border-slate-800/40">
                <input type="text" maxlength="1" value="${valStr}" 
                  oninput="this.value=this.value.replace(/[^1-3]/g,'')" 
                  class="theory-copo-input w-9 h-8 bg-slate-900 border border-slate-700/80 rounded-lg text-center font-black text-emerald-400 focus:border-emerald-500 outline-none text-xs focus:ring-1 focus:ring-emerald-500/50 transition-premium" 
                  data-co="${coKey}" data-target="PO${i}">
              </td>`;
          }

          let psoCells = '';
          for (let i = 1; i <= 3; i++) {
            let val = mapping['PSO' + i];
            let valStr = (val !== null && val !== undefined && val !== '-' && val !== '0' && val !== 0) ? val : '';
            psoCells += `
              <td class="p-1.5 text-center border-r border-slate-800/40 last:border-r-0 bg-blue-950/20">
                <input type="text" maxlength="1" value="${valStr}" 
                  oninput="this.value=this.value.replace(/[^1-3]/g,'')" 
                  class="theory-copo-input w-9 h-8 bg-slate-900 border border-slate-700/80 rounded-lg text-center font-black text-blue-400 focus:border-blue-500 outline-none text-xs focus:ring-1 focus:ring-blue-500/50 transition-premium" 
                  data-co="${coKey}" data-target="PSO${i}">
              </td>`;
          }

          return `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/40 transition-premium text-sm md:text-base">
              <td class="p-3 font-black text-sky-400 whitespace-nowrap border-r border-slate-800/80 bg-slate-900/50 text-center">${coKey}</td>
              ${poCells}
              ${psoCells}
            </tr>
          `;
        }).join('');

        html += `
          <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl overflow-hidden shadow-lg mb-6">
            <div class="px-5 py-3.5 bg-slate-900/90 border-b border-slate-800/80 font-bold text-sm text-slate-200 flex items-center justify-between flex-wrap gap-3 tracking-wider uppercase">
              <span class="flex items-center gap-2.5 text-amber-400 font-black text-sm">
                <span class="material-symbols-rounded text-lg">grid_on</span> CO-PO & PSO Mapping Articulation Matrix
              </span>
              <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-amber-400/80 bg-amber-950/60 border border-amber-500/30 px-3 py-1 rounded-full lowercase">Scale: 1 (Low) to 3 (High)</span>
                <button onclick="saveTheoryCoPoMapping(event)" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-premium flex items-center gap-1.5 shadow-md shadow-emerald-900/30 cursor-pointer">
                  <span class="material-symbols-rounded text-sm">save</span> Save Matrix
                </button>
              </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[750px]">
                  <thead>
                    <tr class="bg-slate-900/70 text-xs md:text-sm font-bold text-slate-300 uppercase tracking-wider border-b border-slate-800/80">
                      <th class="p-3 w-16 border-r border-slate-800/80 text-center bg-slate-900/60">CO</th>
                      ${poHeaders}
                      ${psoHeaders}
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-800/40">
                    ${copoList}
                  </tbody>
                </table>
            </div>
          </div>
        `;
      }

      // Render Modules section
      let validModules = modules ? modules.filter(m => m.content && m.content.trim().length > 0) : [];
      if (validModules.length > 0) {
        let modulesList = validModules.map((m, idx) => `
          <div class="border-b border-slate-800/40 last:border-0 px-4 py-3 hover:bg-slate-900/30 transition-premium">
            <div class="flex items-start gap-3">
              <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-violet-500/15 border border-violet-500/30 flex items-center justify-center text-violet-400 text-xs font-black">${m.module_id || (idx + 1)}</span>
              <p class="text-sm text-slate-300 leading-relaxed">${m.content || ''}</p>
            </div>
          </div>
        `).join('');
        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-xs text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-xs text-violet-400">layers</span> Modules / Units
            </div>
            <div class="divide-y divide-slate-800/40">
              ${modulesList}
            </div>
          </div>
        `;
      }

      // Render Textbooks section
      if (textbooks && textbooks.length > 0) {
        let textbooksList = textbooks.map((tb, idx) => `
          <div class="flex items-start gap-3 px-4 py-3 border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium">
            <span class="flex-shrink-0 w-5 h-5 mt-0.5 rounded bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xs font-bold">${idx + 1}</span>
            <p class="text-sm text-slate-300 leading-relaxed">${tb}</p>
          </div>
        `).join('');
        html += `
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner mb-6">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 font-bold text-xs text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-xs text-amber-400">menu_book</span> Textbooks &amp; References
            </div>
            <div class="divide-y divide-slate-800/40">
              ${textbooksList}
            </div>
          </div>
        `;
      }

      if (html === '') {
        html = `<div class="p-6 text-center text-sm text-slate-500 border border-dashed border-slate-700/50 rounded-xl">Could not extract structured data. The syllabus might have an unparseable format.</div>`;
      }

      console.log('[renderCourseStructure] Writing HTML to courseStructureContent, length:', html.length);
      container.innerHTML = html;
    }

    function renderSummativeAssessment(cos, students) {
      if (window.isCurrentSubjectPractical) {
        let html = `



          <!-- Configured Model Tests Questions Card -->
          <div class="mb-6 bg-slate-950/40 border border-slate-800/60 rounded-2xl p-4 shadow-inner">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800/60 mb-3">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-sky-400 text-lg">help_center</span>
                <div>
                  <h5 class="text-xs font-black uppercase text-slate-200 tracking-wide">Configured Model Tests Questions Scheme</h5>
                  <p class="text-[11px] text-slate-400">Question paper schemes for Model Test 1 (CO1/CO2) and Model Test 2 (CO3/CO4).</p>
                </div>
              </div>
              <button type="button" onclick="openManageTestsModal(event)" class="px-3.5 py-1.5 bg-blue-600/10 hover:bg-blue-600/20 border border-blue-500/30 text-blue-400 hover:text-blue-300 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer shadow-sm">
                <span class="material-symbols-rounded text-sm text-blue-400">settings</span> Setup Test Questions
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
              <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-3">
                <div class="font-bold text-sky-300 mb-1 flex items-center justify-between">
                  <span>Model Test 1 (CO1 &amp; CO2)</span>
                  <span class="text-[10px] bg-sky-500/10 text-sky-400 px-2 py-0.5 rounded-full border border-sky-500/20">Max 15 Marks</span>
                </div>
                <p class="text-[11px] text-slate-400">Choice of 1 out of 2 questions per CO.</p>
              </div>
              <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-3">
                <div class="font-bold text-sky-300 mb-1 flex items-center justify-between">
                  <span>Model Test 2 (CO3 &amp; CO4)</span>
                  <span class="text-[10px] bg-sky-500/10 text-sky-400 px-2 py-0.5 rounded-full border border-sky-500/20">Max 15 Marks</span>
                </div>
                <p class="text-[11px] text-slate-400">Choice of 1 out of 2 questions per CO.</p>
              </div>
            </div>
          </div>

          <!-- Series Exam & Board Exam Register Table -->
          <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl overflow-hidden shadow-xl">
            <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between flex-wrap gap-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-sky-400 text-base">assignment_turned_in</span>
                <span class="text-xs font-bold uppercase text-slate-200 tracking-wider">Series Practical Examination &amp; Board Exam Grade Register</span>
              </div>
              <button type="button" onclick="savePracticalSummativeMarks()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow cursor-pointer">
                <span class="material-symbols-rounded text-sm">save</span> Save Series &amp; Board Marks
              </button>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse min-w-[850px]">
                <thead>
                  <tr class="border-b border-slate-800/80 text-slate-300 font-semibold uppercase tracking-wider text-[11px] bg-slate-900/80">
                    <th class="p-2.5 w-12 text-center text-slate-400">Roll</th>
                    <th class="p-2.5 text-slate-300">Student Name</th>
                    <th class="p-2.5 text-center text-slate-400">Reg No</th>
                    <th class="p-2.5 text-center text-slate-200 bg-slate-800/40">Series Exam 1 (/15)</th>
                    <th class="p-2.5 text-center text-slate-200 bg-slate-800/40">Series Exam 2 (/15)</th>
                    <th class="p-2.5 text-center text-sky-400 bg-sky-500/10">Test Avg (/15)</th>
                    <th class="p-2.5 text-center text-emerald-400 bg-emerald-500/10">Board Exam Grade</th>
                  </tr>
                </thead>
                <tbody id="pracSummativeMarkTbody" class="divide-y divide-slate-800/50">
        `;

        if (students && students.length > 0) {
          students.forEach((student, index) => {
            let s1 = (student.tests && student.tests['Test 1'] && student.tests['Test 1'].total !== undefined) ? student.tests['Test 1'].total : (student.series1_score !== undefined && student.series1_score !== null ? student.series1_score : (student.practical_test1 !== undefined && student.practical_test1 !== null ? student.practical_test1 : ''));
            let s2 = (student.tests && student.tests['Test 2'] && student.tests['Test 2'].total !== undefined) ? student.tests['Test 2'].total : (student.series2_score !== undefined && student.series2_score !== null ? student.series2_score : (student.practical_test2 !== undefined && student.practical_test2 !== null ? student.practical_test2 : ''));
            let tAvg = (s1 !== '' || s2 !== '') ? (((Number(s1) || 0) + (Number(s2) || 0)) / ( (s1 !== '' && s2 !== '') ? 2 : 1 )).toFixed(1) : '-';
            let boardGrade = (student.board_exam_marks !== undefined && student.board_exam_marks !== null) ? String(student.board_exam_marks).trim() : '';

            html += `
              <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-xs" data-reg="${student.reg_no}">
                <td class="p-2.5 text-center font-bold text-slate-400">${index + 1}</td>
                <td class="p-2.5 font-bold text-slate-200">${student.name}</td>
                <td class="p-2.5 text-center font-mono text-slate-400">${student.reg_no}</td>
                <td class="p-2.5 text-center bg-slate-900/40">
                  <input type="number" step="0.5" min="0" max="15" value="${s1 !== '' ? s1 : ''}" placeholder="0-15" class="prac-s1-input w-24 bg-slate-900 border border-slate-700/60 rounded px-2 py-1 text-center font-mono font-bold text-slate-100 focus:border-sky-500 outline-none" oninput="updatePracAvg(this)">
                </td>
                <td class="p-2.5 text-center bg-slate-900/40">
                  <input type="number" step="0.5" min="0" max="15" value="${s2 !== '' ? s2 : ''}" placeholder="0-15" class="prac-s2-input w-24 bg-slate-900 border border-slate-700/60 rounded px-2 py-1 text-center font-mono font-bold text-slate-100 focus:border-sky-500 outline-none" oninput="updatePracAvg(this)">
                </td>
                <td class="p-2.5 text-center bg-sky-500/5">
                  <span class="prac-avg-display font-mono font-bold text-sky-400 text-xs">${tAvg !== '-' ? tAvg + ' / 15' : '-'}</span>
                </td>
                <td class="p-2.5 text-center bg-emerald-500/5">
                  <select class="prac-board-grade w-32 bg-slate-900 border border-slate-800 rounded px-2 py-1 text-center font-mono font-bold text-emerald-400 focus:border-emerald-500 outline-none cursor-pointer">
                    <option value="">Grade -</option>
                    <option value="S" ${boardGrade === 'S' ? 'selected' : ''}>S (90%+)</option>
                    <option value="A+" ${boardGrade === 'A+' ? 'selected' : ''}>A+ (80%+)</option>
                    <option value="A" ${boardGrade === 'A' ? 'selected' : ''}>A (75%+)</option>
                    <option value="B+" ${boardGrade === 'B+' ? 'selected' : ''}>B+ (70%+)</option>
                    <option value="B" ${boardGrade === 'B' ? 'selected' : ''}>B (65%+)</option>
                    <option value="C+" ${boardGrade === 'C+' ? 'selected' : ''}>C+ (60%+)</option>
                    <option value="C" ${boardGrade === 'C' ? 'selected' : ''}>C (55%+)</option>
                    <option value="D" ${boardGrade === 'D' ? 'selected' : ''}>D (50%+)</option>
                    <option value="P" ${boardGrade === 'P' ? 'selected' : ''}>P (Passed)</option>
                    <option value="F" ${boardGrade === 'F' ? 'selected' : ''}>F (Failed)</option>
                    <option value="ABS" ${boardGrade === 'ABS' ? 'selected' : ''}>ABS</option>
                  </select>
                </td>
              </tr>
            `;
          });
        } else {
          html += `<tr><td colspan="7" class="p-6 text-center text-slate-400">No student evaluation data found.</td></tr>`;
        }

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;

        const saContent = document.getElementById('summativeAssessmentContent');
        if (saContent) saContent.innerHTML = html;
        return;
      }

      let html = `
        <div class="flex items-center justify-between mb-4 no-print">
          <div>
            <h4 class="text-[10px] font-black text-slate-200">Summative Assessment (Manual Tests)</h4>
            <p class="text-[10px] text-slate-500 mt-1">Configure and generate precise Cognitive Level based question papers for each CO.</p>
          </div>
        </div>
      `;

      // Build the marks entry table FIRST so it's at the top
      let marksEntryHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('manualMarksWrapper').classList.toggle('hidden'); document.getElementById('marksToggleIcon').innerText = document.getElementById('manualMarksWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-sm text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-sm text-emerald-400">edit_document</span> Enter Manual Marks
              <span id="marksToggleIcon" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
            </div>
            <div class="flex items-center gap-2">
              <button onclick="event.stopPropagation(); printSummativeReport('${currentSubjectId}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Print Written Report
              </button>
              <button onclick="event.stopPropagation(); saveSummativeMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Save Written Marks
              </button>
            </div>
          </div>
          <div id="manualMarksWrapper" class="hidden overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-900/40 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800/60">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1</th>
                  <th class="p-3 text-center w-20">CO2</th>
                  <th class="p-3 text-center w-20">CO3</th>
                  <th class="p-3 text-center w-20">CO4</th>
                </tr>
              </thead>
              <tbody id="summativeMarkEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let sm = student.summative_marks || {};
          marksEntryHtml += `
            <tr class="border-b border-slate-800/40 last:border-0 hover:bg-slate-900/30 transition-premium text-sm" data-reg="${student.reg_no}">
              <td class="p-3 text-slate-400 font-bold">${index + 1}</td>
              <td class="p-3 font-bold text-slate-200">${student.name}</td>
              <td class="p-3 font-mono text-slate-400">${student.reg_no}</td>
              <td class="p-3 font-mono text-slate-400">${student.sbte_reg_no || '-'}</td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO1 !== null ? Math.round(sm.CO1) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO2 !== null ? Math.round(sm.CO2) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO3 !== null ? Math.round(sm.CO3) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO4 !== null ? Math.round(sm.CO4) : ''}" placeholder="-" class="summ-mark w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-sm focus:outline-none focus:border-blue-500/50 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        marksEntryHtml += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found.</td></tr>`;
      }
      marksEntryHtml += `</tbody></table></div></div>`;

      html += marksEntryHtml;

      html += `
        <div id="summativePapersContainer" class="flex flex-col gap-6 mb-6 no-print">
      `;

      if (cos && cos.length > 0) {
        cos.forEach(co => {
          let testData = currentSummativeTests[co.id] || null;
          let generatedContent = '';
          
          if (testData) {
            let partAStr = testData.part_a ? testData.part_a.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partBStr = testData.part_b ? testData.part_b.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partCStr = testData.part_c ? testData.part_c.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-400 mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';

            generatedContent = `
              <div class="mt-4 pt-4 border-t border-slate-800/60" id="paper-${co.id}">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm font-bold text-emerald-400 uppercase tracking-widest">Generated Question Paper</span>
                  <div class="flex items-center gap-2">
                    <button onclick="editSummativeQuestions('${co.id}')" class="flex items-center gap-1.5 text-xs bg-emerald-700/30 hover:bg-emerald-600 border border-emerald-600/40 px-2.5 py-1.5 rounded-lg text-emerald-300 hover:text-white transition-premium cursor-pointer" title="Edit Question Text & Bloom Levels">
                      <span class="material-symbols-rounded text-sm">edit</span> Edit Questions
                    </button>
                    <button onclick="printStudentExamPaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-xs bg-blue-700/30 hover:bg-blue-600 border border-blue-600/40 px-2.5 py-1.5 rounded-lg text-blue-300 hover:text-white transition-premium cursor-pointer" title="Print Clean Examination Paper for Students Only">
                      <span class="material-symbols-rounded text-sm">description</span> Print Exam Paper
                    </button>
                    <button onclick="printSummativePaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-xs bg-purple-700/30 hover:bg-purple-600 border border-purple-600/40 px-2.5 py-1.5 rounded-lg text-purple-300 hover:text-white transition-premium cursor-pointer" title="Print Complete Assessment File with Cognitive Analysis & Scheme for Course File">
                      <span class="material-symbols-rounded text-sm">folder_open</span> Course File Doc
                    </button>
                    <button onclick="printAnswerKey('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-xs bg-amber-700/30 hover:bg-amber-600 border border-amber-600/40 px-2.5 py-1.5 rounded-lg text-amber-300 hover:text-white transition-premium cursor-pointer" title="Print Answer Key & Scheme of Valuation">
                      <span class="material-symbols-rounded text-sm">assignment</span> Answer Key
                    </button>
                  </div>
                </div>
                <div class="text-sm text-slate-300 bg-slate-950/50 p-4 rounded-lg border border-slate-800/40">
                  ${partAStr ? `<div class="font-bold mb-1.5 text-slate-400">PART A (Short Answers)</div><ul class="list-decimal pl-5 mb-4">${partAStr}</ul>` : ''}
                  ${partBStr ? `<div class="font-bold mb-1.5 text-slate-400">PART B (Medium Answers)</div><ul class="list-decimal pl-5 mb-4">${partBStr}</ul>` : ''}
                  ${partCStr ? `<div class="font-bold mb-1.5 text-slate-400">PART C (Long Answers)</div><ul class="list-decimal pl-5 mb-2">${partCStr}</ul>` : ''}
                </div>
              </div>
            `;
          }

          let isLocked = testData && testData.is_locked ? true : false;
          let disabledAttr = isLocked ? 'disabled' : '';
          let lockStr = isLocked ? `<span class="material-symbols-rounded text-sm text-amber-500 ml-1" title="Locked">lock</span>` : '';
          let dateStr = testData && testData.date_of_exam ? testData.date_of_exam : '';

          let qA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qA : (testData?.part_a?.q_count || '');
          let mA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mA : (testData?.part_a?.marks_per_q || '');
          let qB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qB : (testData?.part_b?.q_count || '');
          let mB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mB : (testData?.part_b?.marks_per_q || '');
          let qC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qC : (testData?.part_c?.q_count || '');
          let mC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mC : (testData?.part_c?.marks_per_q || '');

          let lockBtn = isLocked || !testData ? '' : `
            <button onclick="lockSummativeTest('${currentSubjectId}', '${co.id}')" class="p-1.5 rounded-lg bg-slate-800 hover:bg-amber-600 text-slate-400 hover:text-white transition-premium cursor-pointer" title="Lock & Finalize">
              <span class="material-symbols-rounded text-base block">lock</span>
            </button>
          `;

          let genBtn = isLocked ? '' : `
              <button id="gen_btn_${co.id}" onclick="generateSummativePaper('${currentSubjectId}', '${co.id}')" class="w-full py-2.5 bg-blue-600/20 hover:bg-blue-600 border border-blue-500/30 text-blue-400 hover:text-white rounded-xl text-sm font-bold transition-premium mt-3 cursor-pointer">
                ${testData ? 'Regenerate Question Paper' : 'Generate AI Question Paper'}
              </button>
          `;
          
          let dateInputStr = `
            <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700/80 shadow-inner">
              <span class="text-sm text-slate-400 font-bold uppercase flex items-center gap-1"><span class="material-symbols-rounded text-sm">calendar_today</span>Date</span>
              <input type="date" id="summ_date_${co.id}" value="${dateStr}" ${disabledAttr} onchange="saveSummativeConfig('${currentSubjectId}', '${co.id}')" class="bg-slate-900 text-sm text-slate-200 font-mono outline-none w-[110px] px-2 py-0.5 rounded border border-slate-700 focus:border-blue-500">
            </div>
          `;

          html += `
            <div id="summ_card_${co.id}" class="bg-slate-900/50 border border-slate-800/60 p-5 rounded-xl relative ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
              <div class="flex items-center justify-between mb-4 border-b border-slate-800/60 pb-3 cursor-pointer hover:opacity-80 transition-premium" onclick="document.getElementById('co_body_${co.id}').classList.toggle('hidden'); document.getElementById('co_icon_${co.id}').innerText = document.getElementById('co_body_${co.id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
                <h5 class="text-sm font-black text-blue-400 flex items-center gap-1">
                  <span id="co_icon_${co.id}" class="material-symbols-rounded text-sm text-slate-500">expand_more</span>
                  ${co.id} Written Test ${lockStr}
                </h5>
                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                  ${dateInputStr}
                  ${lockBtn}
                </div>
              </div>
 
              <div id="co_body_${co.id}" class="hidden pt-2">
 
              <div class="flex items-center gap-4 mb-4 mt-1 text-sm font-bold text-slate-400 bg-slate-950/50 p-2 rounded-lg border border-slate-800/40 w-max">
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="ai" ${(!testData || !testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-blue-500 focus:ring-blue-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   AI Generation
                 </label>
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-emerald-400 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="manual" ${(testData && testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-emerald-500 focus:ring-emerald-500 bg-slate-900 border-slate-700" ${disabledAttr}>
                   Manual Entry
                 </label>
              </div>
              
              <div class="space-y-3 mb-4">
                <div class="flex items-center gap-3 text-sm text-slate-400 font-bold mb-1"><span class="w-24 shrink-0 whitespace-nowrap">Part</span><span class="flex-1 text-center">Q. Count</span><span class="w-4"></span><span class="flex-1 text-center">Marks/Q</span></div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART A</span>
                  <input type="number" id="summ_q_A_${co.id}" value="${qA}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_A_${co.id}" value="${mA}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART B</span>
                  <input type="number" id="summ_q_B_${co.id}" value="${qB}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_B_${co.id}" value="${mB}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-sm text-slate-400 font-bold w-24 shrink-0 whitespace-nowrap">PART C</span>
                  <input type="number" id="summ_q_C_${co.id}" value="${qC}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                  <span class="text-slate-600 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_C_${co.id}" value="${mC}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-slate-950 border border-slate-700/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 outline-none focus:border-blue-500">
                </div>
              </div>

              <div class="flex items-center justify-between mb-4 border-t border-slate-800/40 pt-3">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-400 hover:text-slate-200 transition-premium">
                  <input type="checkbox" id="sync_pattern_${co.id}" ${disabledAttr} onchange="if(this.checked) applySummativePatternToAll('${co.id}')" class="rounded border-slate-700 bg-slate-900 text-blue-500 focus:ring-blue-500/30">
                  <span>Apply pattern to all COs</span>
                </label>
                <div class="text-sm font-bold text-slate-300 bg-slate-800/50 px-3 py-1 rounded-lg border border-slate-700/50">
                  Total Marks: <span id="summ_total_${co.id}" class="${testData ? 'text-emerald-400' : 'text-blue-400'}">${testData ? testData.total_marks : '0'}</span>
                </div>
              </div>
              
              ${genBtn}

              <div id="manual_form_wrapper_${co.id}"></div>

              ${generatedContent}
              </div> <!-- close co_body -->
            </div>
          `;
        });
      }

      html += `</div>`;

      // Online MCQ Test Setup (Collapsible)
      let onlineTestHtml = `
        <div class="bg-slate-950/50 border border-slate-800/60 rounded-xl overflow-hidden shadow-inner no-print mb-6">
          <div class="px-4 py-3 bg-slate-900/80 border-b border-slate-800/60 flex items-center justify-between cursor-pointer hover:bg-slate-800/80 transition-premium" onclick="document.getElementById('onlineTestWrapper').classList.toggle('hidden'); document.getElementById('onlineTestIcon').innerText = document.getElementById('onlineTestWrapper').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <div class="font-bold text-[10px] text-slate-400 flex items-center gap-2 tracking-wider uppercase">
              <span class="material-symbols-rounded text-[10px] text-purple-400">devices</span> Online MCQ Tests Setup
              <span id="onlineTestIcon" class="material-symbols-rounded text-[10px] text-slate-500">expand_more</span>
            </div>
          </div>
          <div id="onlineTestWrapper" class="hidden p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Configuration Form -->
              <div class="col-span-2 bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-[10px] font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Publish New Online Test</h5>
                <div class="grid grid-cols-2 gap-3 mb-3">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Target COs (Multiple)</label>
                    <select id="online_test_cos" multiple class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500 h-[96px]">
                      ${cos ? cos.map(co => `<option value="${co.id}">${co.id}</option>`).join('') : ''}
                    </select>
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Max Attempts</label>
                    <input type="number" id="online_test_attempts" value="1" min="1" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                    <label class="block text-[10px] text-slate-500 font-bold mt-2 mb-1 uppercase">Duration (Minutes)</label>
                    <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Number of Questions</label>
                    <input type="number" id="online_test_q_count" value="10" min="1" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Generation Mode</label>
                    <select id="online_test_gen_mode" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                      <option value="bank">Mode B: Question Bank Pool</option>
                      <option value="ai">Mode A: AI Generator (Gemini)</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Start Time</label>
                    <input type="text" id="online_test_start" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                  <div>
                    <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="e.g. Midterm Test 1">
                </div>
                <button onclick="publishOnlineTest('${currentSubjectId}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded-lg text-[10px] font-bold transition-premium flex items-center justify-center gap-2">
                  <span class="material-symbols-rounded text-[10px]">rocket_launch</span> Generate & Publish to Students
                </button>
              </div>
              
              <!-- Active Tests Dashboard -->
              <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-800/50">
                <h5 class="text-[10px] font-bold text-slate-300 mb-3 border-b border-slate-800/60 pb-2">Active Online Tests</h5>
                <div id="activeOnlineTestsList" class="space-y-2 text-[10px] text-slate-400">
                   <div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      html += onlineTestHtml;

      html += `
        <div id="printableExamArea" class="hidden no-print"></div>
      `;

      const saContent = document.getElementById('summativeAssessmentContent');
      if (saContent) saContent.innerHTML = html;

      // Automatically spawn manual fields for any saved manual papers on load
      if (cos && cos.length > 0) {
         cos.forEach(co => {
             let testData = currentSummativeTests[co.id] || null;
             if (testData && testData.manual_mode) {
                 spawnManualFields(co.id);
                 // Adjust button styling to show it's manual save
                 const btn = document.getElementById(`gen_btn_${co.id}`);
                 if (btn) {
                     btn.innerText = 'Save Custom Questions';
                     btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
                     btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
                     btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
                     btn.classList.replace('text-blue-400', 'text-emerald-400');
                 }
             }
         });
      }

      // Initialize Flatpickr
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#online_test_start", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
        flatpickr("#online_test_end", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
      }
    }

    function syncSummativeInputs(sourceCoId) {
      calcSummativeTotal(sourceCoId);
      if(document.getElementById(`sync_pattern_${sourceCoId}`)?.checked) {
         applySummativePatternToAll(sourceCoId);
         
         // Trigger spawn for all COs in manual mode
         document.querySelectorAll('[id^="summ_card_"]').forEach(card => {
             const coId = card.id.replace('summ_card_', '');
             const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`)?.value === 'manual';
             if (isManual) {
                 spawnManualFields(coId);
             }
         });
      } else {
         const isManual = document.querySelector(`input[name="summ_mode_${sourceCoId}"]:checked`)?.value === 'manual';
         if (isManual) {
             spawnManualFields(sourceCoId);
         }
      }
    }

    function calcSummativeTotal(coId) {
      let total = 0;
      ['A', 'B', 'C'].forEach(p => {
        let q = parseInt(document.getElementById(`summ_q_${p}_${coId}`).value) || 0;
        let m = parseInt(document.getElementById(`summ_m_${p}_${coId}`).value) || 0;
        total += (q * m);
      });
      const tEl = document.getElementById(`summ_total_${coId}`);
      if (tEl) {
        tEl.innerText = total;
        tEl.classList.remove('text-emerald-400');
        tEl.classList.add('text-blue-400');
      }
    }

    function applySummativePatternToAll(sourceCoId) {
      const qA = document.getElementById(`summ_q_A_${sourceCoId}`).value;
      const mA = document.getElementById(`summ_m_A_${sourceCoId}`).value;
      const qB = document.getElementById(`summ_q_B_${sourceCoId}`).value;
      const mB = document.getElementById(`summ_m_B_${sourceCoId}`).value;
      const qC = document.getElementById(`summ_q_C_${sourceCoId}`).value;
      const mC = document.getElementById(`summ_m_C_${sourceCoId}`).value;

      document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => { if(el.id !== `summ_q_A_${sourceCoId}`) el.value = qA; });
      document.querySelectorAll('[id^="summ_m_A_"]').forEach(el => { if(el.id !== `summ_m_A_${sourceCoId}`) el.value = mA; });
      document.querySelectorAll('[id^="summ_q_B_"]').forEach(el => { if(el.id !== `summ_q_B_${sourceCoId}`) el.value = qB; });
      document.querySelectorAll('[id^="summ_m_B_"]').forEach(el => { if(el.id !== `summ_m_B_${sourceCoId}`) el.value = mB; });
      document.querySelectorAll('[id^="summ_q_C_"]').forEach(el => { if(el.id !== `summ_q_C_${sourceCoId}`) el.value = qC; });
      document.querySelectorAll('[id^="summ_m_C_"]').forEach(el => { if(el.id !== `summ_m_C_${sourceCoId}`) el.value = mC; });

      // Uncheck all other checkboxes to avoid conflict
      document.querySelectorAll('[id^="sync_pattern_"]').forEach(el => {
         if(el.id !== `sync_pattern_${sourceCoId}`) el.checked = false;
         
         // Trigger recalculation on all modified cards
         let c_id = el.id.replace('sync_pattern_', '');
         calcSummativeTotal(c_id);
      });
    }

    function saveSummativeConfig(subjectId, coTag) {
      let dateValue = document.getElementById(`summ_date_${coTag}`).value;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, date_of_exam: dateValue })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') console.log('Saved date');
      });
    }

    function lockSummativeTest(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} test? This cannot be easily undone.`)) return;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') loadCourseDetails(subjectId);
        else alert(data.message);
      });
    }

    let tempSummativePatterns = {};

    function saveSummativePatterns() {
       document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => {
          let coTag = el.id.replace('summ_q_A_', '');
          tempSummativePatterns[coTag] = {
             qA: document.getElementById(`summ_q_A_${coTag}`)?.value || '',
             mA: document.getElementById(`summ_m_A_${coTag}`)?.value || '',
             qB: document.getElementById(`summ_q_B_${coTag}`)?.value || '',
             mB: document.getElementById(`summ_m_B_${coTag}`)?.value || '',
             qC: document.getElementById(`summ_q_C_${coTag}`)?.value || '',
             mC: document.getElementById(`summ_m_C_${coTag}`)?.value || '',
          };
       });
    }

    function toggleSummativeMode(coId) {
       const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`).value === 'manual';
       const btn = document.getElementById(`gen_btn_${coId}`);
       if(btn) {
          if(isManual) {
             btn.innerText = 'Save Custom Questions';
             btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
             btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
             btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
             btn.classList.replace('text-blue-400', 'text-emerald-400');
             
             // Instantly spawn manual question fields
             spawnManualFields(coId);
          } else {
             btn.innerText = 'Generate AI Question Paper';
             btn.classList.replace('bg-emerald-600/20', 'bg-blue-600/20');
             btn.classList.replace('hover:bg-emerald-600', 'hover:bg-blue-600');
             btn.classList.replace('border-emerald-500/30', 'border-blue-500/30');
             btn.classList.replace('text-emerald-400', 'text-blue-400');

             // Remove manual entry fields if switching back to AI
             const wrapper = document.getElementById(`manual_form_wrapper_${coId}`);
             if (wrapper) {
                 wrapper.innerHTML = '';
             }
          }
       }
    }

    function editSummativeQuestions(coId) {
      const body = document.getElementById(`co_body_${coId}`);
      if (body && body.classList.contains('hidden')) {
          body.classList.remove('hidden');
          const icon = document.getElementById(`co_icon_${coId}`);
          if (icon) icon.innerText = 'expand_less';
      }
      const manualRadio = document.querySelector(`input[name="summ_mode_${coId}"][value="manual"]`);
      if (manualRadio) {
          manualRadio.checked = true;
      }
      toggleSummativeMode(coId);
      const wrapper = document.getElementById(`manual_form_wrapper_${coId}`);
      if (wrapper) {
          wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

    function spawnManualFields(coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;

      let testData = currentSummativeTests[coTag] || null;
      let savedA = testData?.part_a?.questions || [];
      let savedB = testData?.part_b?.questions || [];
      let savedC = testData?.part_c?.questions || [];

      let html = `<div id="manual_form_${coTag}" class="mt-4 pt-4 border-t border-slate-800/60">`;
      html += `<div class="text-sm text-slate-300 bg-slate-950/50 p-4 rounded-xl border border-slate-800/40 space-y-4">`;
      
      const buildFields = (count, partName, prefix, savedQuestions) => {
         let fHtml = '';
         if(count > 0) fHtml += `<div class="font-bold text-slate-400 border-b border-slate-800 pb-1.5">${partName}</div><div class="space-y-3 mt-2">`;
         for(let i=0; i<count; i++) {
            let qText = savedQuestions && savedQuestions[i] ? savedQuestions[i].q : '';
            let qLvl = savedQuestions && savedQuestions[i] ? savedQuestions[i].level : 'U';
            fHtml += `
              <div class="flex gap-3 items-start">
                 <span class="text-slate-500 mt-2 font-mono">${i+1}.</span>
                 <textarea id="man_q_${prefix}_${coTag}_${i}" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-200 outline-none focus:border-emerald-500 text-sm" rows="2" placeholder="Enter question ${i+1}...">${qText}</textarea>
                 <select id="man_lvl_${prefix}_${coTag}_${i}" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-slate-200 text-sm w-24 outline-none focus:border-emerald-500 mt-0.5">
                    <option value="U" ${qLvl === 'U' ? 'selected' : ''}>U (Understand)</option>
                    <option value="R" ${qLvl === 'R' ? 'selected' : ''}>R (Remember)</option>
                    <option value="A" ${qLvl === 'A' ? 'selected' : ''}>A (Apply)</option>
                 </select>
              </div>
            `;
         }
         if(count > 0) fHtml += `</div>`;
         return fHtml;
      };

      html += buildFields(qA, 'PART A', 'A', savedA);
      html += buildFields(qB, 'PART B', 'B', savedB);
      html += buildFields(qC, 'PART C', 'C', savedC);
      html += `</div></div>`;

      let wrapper = document.getElementById(`manual_form_wrapper_${coTag}`);
      if (wrapper) {
          wrapper.innerHTML = html;
      }
      
      const btn = document.getElementById(`gen_btn_${coTag}`);
      if (btn) btn.innerText = 'Save Custom Questions';
    }

    function saveManualSummativePaper(subjectId, coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let mA = parseInt(document.getElementById(`summ_m_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let mB = parseInt(document.getElementById(`summ_m_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;
      let mC = parseInt(document.getElementById(`summ_m_C_${coTag}`).value) || 0;

      let testData = currentSummativeTests[coTag] || null;

      let gather = (count, marks, prefix) => {
         let questions = [];
         let partKey = `part_${prefix.toLowerCase()}`;
         let existingQuestions = testData?.[partKey]?.questions || [];

         for(let i=0; i<count; i++) {
            let elQ = document.getElementById(`man_q_${prefix}_${coTag}_${i}`);
            let elL = document.getElementById(`man_lvl_${prefix}_${coTag}_${i}`);
            if(elQ) {
               let qVal = elQ.value.trim();
               let oldAns = existingQuestions[i]?.ans || ['Statement / Key point', 'Explanation details'];
               questions.push({ 
                  q: qVal, 
                  ans: oldAns,
                  level: elL?.value || 'U', 
                  marks: marks 
               });
            }
         }
         return { q_count: count, marks_per_q: marks, total_marks: count * marks, questions: questions };
      };

      saveSummativePatterns();

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ 
           co_tag: coTag, 
           manual_mode: true,
           manual_part_a: gather(qA, mA, 'A'),
           manual_part_b: gather(qB, mB, 'B'),
           manual_part_c: gather(qC, mC, 'C')
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function generateSummativePaper(subjectId, coTag) {
      const isManual = document.querySelector(`input[name="summ_mode_${coTag}"]:checked`).value === 'manual';
      
      if(isManual) {
         if (document.getElementById(`manual_form_${coTag}`)) {
             saveManualSummativePaper(subjectId, coTag);
         } else {
             spawnManualFields(coTag);
         }
         return;
      }

      saveSummativePatterns();

      let partA = { q_count: document.getElementById(`summ_q_A_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_A_${coTag}`).value };
      let partB = { q_count: document.getElementById(`summ_q_B_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_B_${coTag}`).value };
      let partC = { q_count: document.getElementById(`summ_q_C_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_C_${coTag}`).value };

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, part_a: partA, part_b: partB, part_c: partC })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          // Soft re-render the whole summative tab using existing data
          // We don't have cos/students cached globally in a variable cleanly, so let's reload.
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function loadActiveOnlineTests(subjectId) {
      fetch(`/api/classroom/${subjectId}/active-online-tests`)
        .then(res => res.json())
        .then(data => {
          let listDiv = document.getElementById('activeOnlineTestsList');
          if (!listDiv) return;
          if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              html += `
                <div class="bg-slate-950 p-3 rounded-lg border border-slate-800/80 mb-2">
                  <div class="flex justify-between items-start mb-1">
                    <h6 class="font-bold text-purple-400 text-[10px]">${t.test_name}</h6>
                    <span class="bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded text-[10px] font-bold">${t.duration} Mins</span>
                  </div>
                  <div class="text-[10px] text-slate-500 mb-2">
                    Start: ${t.start_time ? new Date(t.start_time).toLocaleString() : 'Now'}<br>
                    Live Students: <span class="text-emerald-400 font-bold">${t.student_count || 0}</span> | Completed: <span class="text-blue-400 font-bold">${t.completed_count || 0}</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[10px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('${t.test_id}')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[10px]">print</span> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('${t.test_id}', '${subjectId}')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[10px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[10px]">delete</span> Delete
                      </button>
                    </div>
                </div>
              `;
            });
            listDiv.innerHTML = html;
          } else {
            listDiv.innerHTML = `<div class="p-3 bg-slate-950 border border-slate-800 rounded text-center border-dashed">No active online tests found.</div>`;
          }
        });
    }

    function publishOnlineTest(subjectId) {
      const selectElement = document.getElementById('online_test_cos');
      const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
      const attempts = document.getElementById('online_test_attempts').value;
      const duration = document.getElementById('online_test_duration').value;
      const start = document.getElementById('online_test_start').value;
      const end = document.getElementById('online_test_end').value;
      const q_count = document.getElementById('online_test_q_count').value;
      const gen_mode = document.getElementById('online_test_gen_mode').value;

      if (selectedCos.length === 0) {
        alert("Please select at least one CO.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/publish-online-test`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end, q_count, generation_mode: gen_mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Online Test successfully published!");
          loadActiveOnlineTests(subjectId);
          
          // Clear inputs
          selectElement.selectedIndex = -1;
          if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
          if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
        } else {
          alert(data.message || "Failed to publish test.");
        }
      });
    }

    function generateOnlineTestReport(testId) {
      fetch(`/api/test-engine/report/${testId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const test = data.test_info;
            const attempts = data.report;
            
            let tableRows = '';
            if(attempts && attempts.length > 0) {
              attempts.forEach(a => {
                 let start = new Date(a.start_time);
                 let end = new Date(a.end_time);
                 let timeTakenStr = '-';
                 if(a.start_time && a.end_time) {
                    let diffMs = end - start;
                    let diffMins = Math.floor(diffMs / 60000);
                    let diffSecs = Math.floor((diffMs % 60000) / 1000);
                    timeTakenStr = `${diffMins}m ${diffSecs}s`;
                 }
                 
                 tableRows += `
                   <tr>
                     <td style="padding: 8px; border: 1px solid #ddd; font-family: monospace;">${a.reg_no}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">${a.name}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${a.attempt_number}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${timeTakenStr}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; font-size: 14px;">${a.total_score}</td>
                   </tr>
                 `;
              });
            } else {
              tableRows = `<tr><td colspan="5" style="padding: 16px; text-align: center; border: 1px solid #ddd;">No completed attempts yet.</td></tr>`;
            }

            const html = `<!DOCTYPE html>
            <html>
            <head>
              <title>${test.test_name} - Report</title>
              <style>
                body { font-family: system-ui, -apple-system, sans-serif; padding: 40px; color: #111; }
                h2 { text-align: center; margin-bottom: 5px; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 10px; display: inline-block; }
                .meta { text-align: center; font-size: 14px; color: #555; margin-bottom: 30px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
                th { background: #f0f0f0; padding: 10px 8px; border: 1px solid #ddd; text-align: left; }
                .center { text-align: center; }
              </style>
            </head>
            <body>
              <div style="text-align: center;">
                <h2>Online Test Evaluation Report</h2>
                <div class="meta">
                  <strong>Test Name:</strong> ${test.test_name} <br>
                  <strong>Subject Code:</strong> ${test.subject_code} <br>
                  <strong>Total MCQs:</strong> ${test.mcq_count} | <strong>Duration:</strong> ${test.duration} Mins<br>
                  <strong>Generated On:</strong> ${new Date().toLocaleString()}
                </div>
              </div>
              
              <table>
                <thead>
                  <tr>
                    <th>Reg No</th>
                    <th>Student Name</th>
                    <th class="center">Attempts Used</th>
                    <th class="center">Time Taken</th>
                    <th class="center">Marks Obtained</th>
                  </tr>
                </thead>
                <tbody>
                  ${tableRows}
                </tbody>
              </table>
              ${'<script>'}
                window.onload = () => { window.print(); window.close(); }
              ${'</' + 'script>'}
            </body>
            </html>`;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
          } else {
            alert(data.message || "Failed to generate report.");
          }
        });
    }

    function saveSummativeMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#summativeMarkEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.summ-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-written-test-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Written Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updatePracAvg(elem) {
      const row = elem.closest('tr');
      if (!row) return;
      const s1Val = row.querySelector('.prac-s1-input')?.value;
      const s2Val = row.querySelector('.prac-s2-input')?.value;
      const avgSpan = row.querySelector('.prac-avg-display');
      if (!avgSpan) return;

      const s1 = parseFloat(s1Val);
      const s2 = parseFloat(s2Val);

      if (!isNaN(s1) && !isNaN(s2)) {
        avgSpan.innerText = ((s1 + s2) / 2).toFixed(1) + ' / 15';
      } else if (!isNaN(s1)) {
        avgSpan.innerText = s1.toFixed(1) + ' / 15';
      } else if (!isNaN(s2)) {
        avgSpan.innerText = s2.toFixed(1) + ' / 15';
      } else {
        avgSpan.innerText = '-';
      }
    }
    window.updatePracAvg = updatePracAvg;

    function savePracticalSummativeMarks() {
      const rows = document.querySelectorAll('#pracSummativeMarkTbody tr[data-reg]');
      if (!rows || rows.length === 0) {
        alert('No student records found to save.');
        return;
      }

      const payload = [];
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const s1 = row.querySelector('.prac-s1-input')?.value ?? '';
        const s2 = row.querySelector('.prac-s2-input')?.value ?? '';
        const boardGrade = row.querySelector('.prac-board-grade')?.value ?? '';

        payload.push({
          reg_no: regNo,
          series1: s1,
          series2: s2,
          board_exam_grade: boardGrade
        });
      });

      const subjId = currentSubjectId || window.currentSubjectId;
      if (!subjId) {
        alert('Subject ID is missing.');
        return;
      }

      fetch(`/api/classroom/${subjId}/practical/evaluate-bulk`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ evaluations: payload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Practical Series Exam marks and Board Exam Grades saved successfully!');
          if (typeof fetchPracticalEvaluations === 'function') {
            fetchPracticalEvaluations();
          }
        } else {
          alert('Error saving marks: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(err => {
        console.error('Error saving practical summative marks:', err);
        alert('Failed to save practical marks.');
      });
    }
    window.savePracticalSummativeMarks = savePracticalSummativeMarks;

    function printAssignmentReport(subjectId) {
      window.open(`/classroom/${subjectId}/assignment-report`, '_blank');
    }

    function printAssignmentPaperAndRubrics(subjectId, coTag) {
      window.open(`/classroom/${subjectId}/assignment-print/${coTag}`, '_blank');
    }

    function printSummativeReport(subjectId) {
      window.open(`/classroom/${subjectId}/summative-report`, '_blank');
    }

    function printStudentExamPaper(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;

      let semNum1 = parseInt(currentSubjectSemester || '3');
      let startYr1 = 2026;
      if (typeof currentSubjectClassroomId !== 'undefined' && currentSubjectClassroomId) {
        let matchYear = currentSubjectClassroomId.match(/(\d{4})/);
        if (matchYear) startYr1 = parseInt(matchYear[1]);
      }
      let yearOffset1 = Math.floor((semNum1 - 1) / 2);
      let calcAyStart1 = startYr1 + yearOffset1;
      const academicYear = `${calcAyStart1} - ${calcAyStart1 + 1}`;
      const durationText = (totalMarks <= 20) ? '1 Hour' : '1.5 Hours';

      let coNum = coTag.replace(/[^0-9]/g, '');
      let seriesTestTitle = coNum ? `SERIES TEST ${coNum} (${coTag.toUpperCase()})` : `${coTag.toUpperCase()} SERIES TEST`;

      let examDateRaw = data.date_of_exam || new Date().toISOString().split('T')[0];
      let examDateFormatted = new Date(examDateRaw).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'});

      const buildExamRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let bt = (q.level || 'U').toUpperCase()[0];
          return `<tr>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; font-weight: bold; vertical-align: top; width: 45px; font-size: 10pt;">${i+1}</td>
            <td style="border: 1px solid #000; padding: 4px 8px; vertical-align: top; line-height: 1.3; font-size: 10pt;">${q.q}</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: top; width: 50px; font-weight: bold; font-size: 10pt;">${coTag}</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: top; width: 45px; font-weight: bold; font-size: 10pt;">${bt}</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: top; width: 50px; font-weight: bold; font-size: 10pt;">${q.marks || part.marks_per_q}</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <div style="margin-top: 10px; margin-bottom: 10px;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:2px; font-size:11pt; text-transform: uppercase;">PART A &nbsp;<small style="font-weight:normal; font-size:10pt;">(${data.part_a.q_count} &times; ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
            <p style="text-align:center; font-style:italic; font-size:9.5pt; margin-bottom:4px;">Answer all questions.</p>
            <table style="width:100%; border-collapse:collapse; font-size:10pt; border:1px solid #000;">
              <thead>
                <tr style="background:#f5f5f5;">
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">Q.No</th>
                  <th style="border:1px solid #000; padding:4px 6px; text-align:left; font-size:9.5pt;">Question</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">CO</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">BT</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">Marks</th>
                </tr>
              </thead>
              <tbody>${buildExamRows(data.part_a)}</tbody>
            </table>
          </div>`;
      }

      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <div style="margin-top: 12px; margin-bottom: 10px;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:2px; font-size:11pt; text-transform: uppercase;">PART B &nbsp;<small style="font-weight:normal; font-size:10pt;">(${data.part_b.q_count} &times; ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
            <p style="text-align:center; font-style:italic; font-size:9.5pt; margin-bottom:4px;">Answer all questions.</p>
            <table style="width:100%; border-collapse:collapse; font-size:10pt; border:1px solid #000;">
              <thead>
                <tr style="background:#f5f5f5;">
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">Q.No</th>
                  <th style="border:1px solid #000; padding:4px 6px; text-align:left; font-size:9.5pt;">Question</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">CO</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">BT</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">Marks</th>
                </tr>
              </thead>
              <tbody>${buildExamRows(data.part_b)}</tbody>
            </table>
          </div>`;
      }

      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <div style="margin-top: 12px; margin-bottom: 10px;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:2px; font-size:11pt; text-transform: uppercase;">PART C &nbsp;<small style="font-weight:normal; font-size:10pt;">(${data.part_c.q_count} &times; ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
            <p style="text-align:center; font-style:italic; font-size:9.5pt; margin-bottom:4px;">Answer all questions.</p>
            <table style="width:100%; border-collapse:collapse; font-size:10pt; border:1px solid #000;">
              <thead>
                <tr style="background:#f5f5f5;">
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">Q.No</th>
                  <th style="border:1px solid #000; padding:4px 6px; text-align:left; font-size:9.5pt;">Question</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">CO</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:45px; text-align:center; font-size:9.5pt;">BT</th>
                  <th style="border:1px solid #000; padding:4px 6px; width:50px; text-align:center; font-size:9.5pt;">Marks</th>
                </tr>
              </thead>
              <tbody>${buildExamRows(data.part_c)}</tbody>
            </table>
          </div>`;
      }

      const examHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Examination Question Paper - ${seriesTestTitle} - ${subjectCode}</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 10mm 12mm 10mm 12mm;
    }
    * { box-sizing: border-box; }
    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 10pt;
      color: #000;
      background: #0f172a;
    }
    .paper-container {
      max-width: 210mm;
      margin: 15px auto 30px auto;
      padding: 12mm 15mm;
      background: #ffffff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      border-radius: 2px;
      box-sizing: border-box;
      width: 100%;
    }
    .header { text-align: center; border-bottom: 1.5px solid #000; padding-bottom: 6px; margin-bottom: 8px; }
    .college-name { font-size: 14pt; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; line-height: 1.2; }
    .dept-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
    .subject-info { font-size: 10.5pt; margin-top: 3px; font-weight: bold; }
    .exam-title { font-size: 11pt; margin-top: 5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 3px 12px; display: inline-block; }
    .meta-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 9.5pt; }
    .meta-table td { padding: 2px 0; font-size: 9.5pt; }
    table { width: 100%; border-collapse: collapse; }
    .print-toolbar {
      background: #0f172a;
      color: #fff;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-radius: 8px;
      margin: 10px auto 16px auto;
      max-width: 210mm;
      font-family: system-ui, -apple-system, sans-serif;
    }
    .print-toolbar input[type="date"] {
      background: #1e293b;
      color: #f8fafc;
      border: 1px solid #475569;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 12px;
    }
    .print-toolbar button {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 12px;
      cursor: pointer;
    }
    .print-toolbar button:hover { background: #1d4ed8; }
    @media print {
      .print-toolbar { display: none !important; }
      html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
      }
      .paper-container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
  <div class="print-toolbar">
    <div style="display: flex; align-items: center; gap: 10px;">
      <label style="font-size: 12px; font-weight: 600; color: #94a3b8;">Select Exam Date:</label>
      <input type="date" id="print-exam-date" value="${examDateRaw}" onchange="updateExamDate(this.value)">
    </div>
    <button onclick="window.print()">Print Student Exam Paper</button>
  </div>

  <div class="paper-container">
    <div class="header">
      <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
      <div class="dept-name">Department of ${deptName}</div>
      <div class="subject-info">${subjectName ? subjectName : 'Subject'} &nbsp;&mdash;&nbsp; <strong>${subjectCode}</strong></div>
      <div style="margin-top:2px;"><span class="exam-title">${seriesTestTitle}</span></div>
      
      <table class="meta-table">
        <tr>
          <td style="text-align: left; width: 33%;"><strong>Semester:</strong> Sem ${currentSubjectSemester}</td>
          <td style="text-align: center; width: 34%;"><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</td>
          <td style="text-align: right; width: 33%;"><strong>Academic Year:</strong> ${academicYear}</td>
        </tr>
        <tr>
          <td style="text-align: left; width: 33%;"><strong>Duration:</strong> ${durationText}</td>
          <td style="text-align: center; width: 34%;"><strong>Date:</strong> <span class="summative-exam-date-display">${examDateFormatted}</span></td>
          <td style="text-align: right; width: 33%;"><strong>Max Marks:</strong> ${totalMarks}</td>
        </tr>
      </table>
    </div>

    <div style="text-align: right; font-size: 9pt; font-style: italic; margin-bottom: 6px;">[ CO: Course Outcome | BT: Bloom's Taxonomy (R: Remember, U: Understand, A: Apply) ]</div>

    ${bodyHtml}
  </div>

  ${'<script>'}
    function updateExamDate(val) {
      if (!val) return;
      const d = new Date(val);
      const formatted = d.toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'});
      document.querySelectorAll('.summative-exam-date-display').forEach(el => el.innerText = formatted);
    }
  ${'</' + 'script>'}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(examHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 500);
    }

    function printSummativePaper(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const lecturerName = "{{ session('userName', 'Faculty Name') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      
      let semNum2 = parseInt(currentSubjectSemester || '3');
      let startYr2 = 2026;
      if (typeof currentSubjectClassroomId !== 'undefined' && currentSubjectClassroomId) {
        let matchYear = currentSubjectClassroomId.match(/(\d{4})/);
        if (matchYear) startYr2 = parseInt(matchYear[1]);
      }
      let yearOffset2 = Math.floor((semNum2 - 1) / 2);
      let calcAyStart2 = startYr2 + yearOffset2;
      const academicYear = `${calcAyStart2} - ${calcAyStart2 + 1}`;
      const durationText = (totalMarks <= 20) ? '1 Hour' : '1.5 Hours';

      let coNum = coTag.replace(/[^0-9]/g, '');
      let seriesTestTitle = coNum ? `SERIES TEST ${coNum} (${coTag.toUpperCase()})` : `${coTag.toUpperCase()} SERIES TEST`;

      let examDateRaw = data.date_of_exam || new Date().toISOString().split('T')[0];
      let examDateFormatted = new Date(examDateRaw).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'});

      let questionsToSolve = [];
      const collectQuestions = (part) => {
        if (part && part.questions) {
          part.questions.forEach(q => {
            questionsToSolve.push({ q: q.q, marks: q.marks });
          });
        }
      };
      collectQuestions(data.part_a);
      collectQuestions(data.part_b);
      collectQuestions(data.part_c);

      const proceedWithPrint = (geminiData) => {
        const getGeminiInfo = (qText) => {
          if (!geminiData) return null;
          return geminiData.find(item => item.q === qText || item.q.includes(qText) || qText.includes(item.q));
        };

        const buildRows = (part) => {
          if (!part || !part.q_count || !part.questions) return '';
          return part.questions.map((q, i) => {
            let lvl = q.level || '';
            if (lvl === 'R') lvl = 'Remember';
            else if (lvl === 'U') lvl = 'Understand';
            else if (lvl === 'A') lvl = 'Apply';
            return `<tr>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${i+1}</td>
              <td style="border: 1px solid #000; padding: 4px; vertical-align: top;">${q.q}</td>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${coTag}</td>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${lvl}</td>
            </tr>`;
          }).join('');
        };

        let bodyHtml = '';

        if (data.part_a && data.part_a.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:10px 0 4px; font-size:12px;">PART A &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_a.q_count} × ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Bloom's Taxonomy Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_a)}</tbody>
            </table>`;
        }
        if (data.part_b && data.part_b.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART B &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_b.q_count} × ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Bloom's Taxonomy Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_b)}</tbody>
            </table>`;
        }
        if (data.part_c && data.part_c.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART C &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_c.q_count} × ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Bloom's Taxonomy Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_c)}</tbody>
            </table>`;
        }

        let counts = {
          A: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_a?.marks_per_q || 1 },
          B: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_b?.marks_per_q || 3 },
          C: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_c?.marks_per_q || 7 }
        };

        if (data.part_a && data.part_a.questions) {
          data.part_a.questions.forEach(q => {
            let lvl = (q.level || 'R').toUpperCase()[0];
            if (counts.A[lvl] !== undefined) counts.A[lvl]++;
            counts.A.total++;
          });
        }
        if (data.part_b && data.part_b.questions) {
          data.part_b.questions.forEach(q => {
            let lvl = (q.level || 'U').toUpperCase()[0];
            if (counts.B[lvl] !== undefined) counts.B[lvl]++;
            counts.B.total++;
          });
        }
        if (data.part_c && data.part_c.questions) {
          data.part_c.questions.forEach(q => {
            let lvl = (q.level || 'A').toUpperCase()[0];
            if (counts.C[lvl] !== undefined) counts.C[lvl]++;
            counts.C.total++;
          });
        }

        let rMarks = (counts.A.R * counts.A.marksPerQ) + (counts.B.R * counts.B.marksPerQ) + (counts.C.R * counts.C.marksPerQ);
        let uMarks = (counts.A.U * counts.A.marksPerQ) + (counts.B.U * counts.B.marksPerQ) + (counts.C.U * counts.C.marksPerQ);
        let aMarks = (counts.A.A * counts.A.marksPerQ) + (counts.B.A * counts.B.marksPerQ) + (counts.C.A * counts.C.marksPerQ);
        let totalCalculatedMarks = rMarks + uMarks + aMarks;

        let cognitiveTableHtml = `
          <div style="margin-top:15px; page-break-inside: avoid;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:6px; text-decoration: underline; font-size:12px;">Bloom's Taxonomy Level wise Question Analysis</h4>
            <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:11px; text-align:center;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; text-align:left;" rowspan="2"></th>
                  <th style="border:1px solid #000; padding:4px;" colspan="3">Bloom's Taxonomy Level</th>
                  <th style="border:1px solid #000; padding:4px;" rowspan="2">No. of Questions</th>
                </tr>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:150px;">Remember</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Understand</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Apply</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part A (${counts.A.marksPerQ} mark${counts.A.marksPerQ > 1 ? 's' : ''})</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.A.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part B (${counts.B.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.B.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part C (${counts.C.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.C.total || '0'}</td>
                </tr>
                <tr style="background-color:#fafafa; font-weight:bold;">
                  <td style="border:1px solid #000; padding:4px; text-align:left;">Marks</td>
                  <td style="border:1px solid #000; padding:4px;">${rMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${uMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${aMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">Total Marks = ${totalCalculatedMarks}</td>
                </tr>
              </tbody>
            </table>
          </div>
        `;

        let signatureBlockHtml = `
          <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 11px; page-break-inside: avoid; border-top: 1px dashed #000; padding-top: 8px;">
            <div><strong>Prepared By:</strong> ${lecturerName} (Course Coordinator)</div>
            <div><strong>Verified By:</strong> Faculty Name (Module Coordinator)</div>
            <div><strong>Approved By:</strong> HOD</div>
          </div>
        `;

        const buildSchemeRows = () => {
          let rowsHtml = '';
          const processPart = (part, partLabel) => {
            if (!part || !part.questions || part.questions.length === 0) return;
            rowsHtml += `
              <tr style="background: #f2f2f2; font-weight: bold;">
                <td colspan="5" style="border: 1px solid #000; padding: 6px; text-align: left; text-transform: uppercase;">${partLabel}</td>
              </tr>
            `;
            part.questions.forEach((q, i) => {
              let geminiInfo = getGeminiInfo(q.q);
              let rubric = (geminiInfo && geminiInfo.rubric) ? geminiInfo.rubric : (q.rubric || []);
              let answers = (geminiInfo && geminiInfo.ans) ? geminiInfo.ans : (q.ans || []);

              if (rubric.length === 0) {
                let marks = q.marks || 1;
                if (marks <= 2) rubric = [{desc: 'Correct answer / explanation', mark: marks}];
                else rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Correct steps & final answer', mark: marks - 1}];
              }
              let rSpan = rubric.length;

              let answersHtml = '';
              if (answers && answers.length > 0) {
                answersHtml = `<div style="margin-bottom: 6px; font-size: 11px; color: #333;">
                  <strong>Expected Answer Key / Suggestions:</strong>
                  <ul style="margin: 2px 0 4px 14px; padding: 0; list-style-type: disc;">
                    ${answers.map(pt => `<li>${pt}</li>`).join('')}
                  </ul>
                </div>`;
              }

              rubric.forEach((r, rIdx) => {
                rowsHtml += `<tr>`;
                if (rIdx === 0) {
                  rowsHtml += `<td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${i + 1}</td>`;
                }
                
                let cellContent = '';
                if (rIdx === 0 && answersHtml) {
                  cellContent += answersHtml + `<div style="margin-top: 6px; border-top: 1px dashed #ccc; padding-top: 4px; font-weight: bold; font-size: 11px;">Scoring Indicator Split-up:</div>`;
                }
                cellContent += `<div style="padding-left: 6px; font-size: 11px;">&bull; ${r.desc}</div>`;

                rowsHtml += `
                  <td style="border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left;">${cellContent}</td>
                  <td style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: top; font-weight: bold;">${r.mark}</td>
                `;
                if (rIdx === 0) {
                  rowsHtml += `
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                  `;
                }
                rowsHtml += `</tr>`;
              });
            });
          };
          processPart(data.part_a, 'Part A');
          processPart(data.part_b, 'Part B');
          processPart(data.part_c, 'Part C');
          return rowsHtml;
        };

        const schemeTableHtml = `
          <div style="page-break-before: always; padding-top: 20px;">
            <div class="header">
              <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
              <div class="dept-name">Department of ${deptName}</div>
              <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
              <div style="margin-top:6px;"><span class="exam-title">&nbsp;${seriesTestTitle} &ndash; SCHEME OF VALUATION&nbsp;</span></div>
              <div class="meta-row" style="margin-top: 8px; font-size: 11px;">
                <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
                <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
                <span><strong>Academic Year:</strong> ${academicYear}</span>
              </div>
              <div class="meta-row" style="margin-top: 4px; font-size: 11px;">
                <span><strong>Time:</strong> ${durationText}</span>
                <span><strong>Date:</strong> <span class="summative-exam-date-display">${examDateFormatted}</span></span>
                <span><strong>Max Marks:</strong> ${totalMarks}</span>
              </div>
            </div>
            <table style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 12px;">
              <thead>
                <tr style="background: #f2f2f2; font-weight: bold;">
                  <th style="border: 1px solid #000; padding: 6px; width: 60px; text-align: center;">Q. No.</th>
                  <th style="border: 1px solid #000; padding: 6px; text-align: left;">Scoring Indicators</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Split Up</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Sub Total</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Total</th>
                </tr>
              </thead>
              <tbody>
                ${buildSchemeRows()}
              </tbody>
            </table>
          </div>
        `;

        const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Course File Document - ${seriesTestTitle} - ${subjectCode}</title>
  <style>
    @page { size: A4 portrait; margin: 10mm 12mm 10mm 12mm; }
    * { box-sizing: border-box; }
    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 10pt;
      color: #000;
      background: #0f172a;
    }
    .paper-container {
      max-width: 210mm;
      margin: 15px auto 30px auto;
      padding: 12mm 15mm;
      background: #ffffff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      border-radius: 2px;
      box-sizing: border-box;
      width: 100%;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 1.5px solid #000; padding-bottom: 6px; margin-bottom: 10px; }
    .college-name { font-size: 14pt; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; line-height: 1.2; }
    .dept-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 2px; }
    .subject-info { font-size: 10.5pt; margin-top: 3px; font-weight: bold; }
    .exam-title { font-size: 11pt; margin-top: 5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 3px 12px; display: inline-block; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 6px; font-size: 9.5pt; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 4px; vertical-align: top; line-height: 1.3; }
    .print-toolbar {
      background: #0f172a;
      color: #fff;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-radius: 8px;
      margin: 10px auto 16px auto;
      max-width: 210mm;
      font-family: system-ui, -apple-system, sans-serif;
    }
    .print-toolbar input[type="date"] {
      background: #1e293b;
      color: #f8fafc;
      border: 1px solid #475569;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 12px;
    }
    .print-toolbar button {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 12px;
      cursor: pointer;
    }
    .print-toolbar button:hover { background: #1d4ed8; }
    @media print {
      .print-toolbar { display: none !important; }
      html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
      }
      .paper-container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
  <div class="print-toolbar">
    <div style="display: flex; align-items: center; gap: 8px;">
      <label style="font-size: 12px; font-weight: 600; color: #94a3b8;">Select Exam Date:</label>
      <input type="date" id="print-exam-date" value="${examDateRaw}" onchange="updateExamDate(this.value)">
    </div>
    <button onclick="window.print()">Print Course File Document</button>
  </div>

  <div class="paper-container">
    <div class="header">
      <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
      <div class="dept-name">Department of ${deptName}</div>
      <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
      <div style="margin-top:6px;"><span class="exam-title">&nbsp;${seriesTestTitle}&nbsp;</span></div>
      <div class="meta-row">
        <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
        <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
        <span><strong>Academic Year:</strong> ${academicYear}</span>
      </div>
      <div class="meta-row" style="margin-top: 4px;">
        <span><strong>Time:</strong> ${durationText}</span>
        <span><strong>Date:</strong> <span class="summative-exam-date-display">${examDateFormatted}</span></span>
        <span><strong>Max Marks:</strong> ${totalMarks}</span>
      </div>
    </div>

    ${bodyHtml}
    ${cognitiveTableHtml}
    ${signatureBlockHtml}
    ${schemeTableHtml}
  </div>

  ${'<script>'}
    function updateExamDate(val) {
      if (!val) return;
      const d = new Date(val);
      const formatted = d.toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'});
      document.querySelectorAll('.summative-exam-date-display').forEach(el => el.innerText = formatted);
    }
  ${'</' + 'script>'}
</body>
</html>`;

        const pw = window.open('', '_blank', 'width=900,height=700');
        pw.document.write(fullHtml);
        pw.document.close();
        pw.focus();
        setTimeout(() => { pw.print(); }, 500);
      };

      proceedWithPrint(null);
    }

    function printAnswerKey(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRubricHtml = (rubric, marks) => {
        // Fallback for older generated papers that don't have a rubric saved
        if (!rubric || rubric.length === 0) {
            if (marks <= 2) rubric = [{desc: 'Correct definition / answer', mark: marks}];
            else if (marks <= 4) rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Explanation / relevant points', mark: marks - 1}];
            else rubric = [{desc: 'Definition / Concept statement', mark: 1}, {desc: 'Explanation with supporting points', mark: Math.floor(marks/2)}, {desc: 'Diagram / Application', mark: marks - Math.floor(marks/2) - 1}];
        }

        return `<table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 4px; background: #fafafa;">
          ${rubric.map(r => `<tr>
            <td style="padding: 3px 6px; border: 1px solid #ddd;">${r.desc}</td>
            <td style="padding: 3px 6px; text-align: center; width: 50px; border: 1px solid #ddd; font-weight: bold; color: #444;">${r.mark}</td>
          </tr>`).join('')}
        </table>`;
      };

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let ansHtml = '';
          if (q.ans && q.ans.length > 0) {
            ansHtml = `<div style="margin-bottom: 8px; font-size: 12px; color: #333;">
              <ul style="margin: 0; padding-left: 16px;">
                ${q.ans.map(a => `<li style="margin-bottom: 3px;">${a}</li>`).join('')}
              </ul>
            </div>`;
          }
          
          return `<tr>
            <td style="width: 40px; text-align: center; vertical-align: top; padding: 10px 5px; border: 1px solid #000; font-weight: bold;">${i+1}</td>
            <td style="vertical-align: top; padding: 10px; border: 1px solid #000;">
              <div style="font-weight: 500; margin-bottom: 6px; font-size: 13px;">${q.q}</div>
              ${ansHtml}
              <div style="font-size: 11px; font-weight: bold; color: #555; margin-bottom: 2px; margin-top: 6px;">Marking Scheme / Answer Pointers:</div>
              ${buildRubricHtml(q.rubric, q.marks)}
            </td>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 14px; font-weight: bold;">${q.marks}</td>
            <td style="width: 60px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 11px;">[${q.level}]</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      const tableHeader = `
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 40px;">Q.No</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee;">Question & Expected Answer Key</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 80px;">Marks</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 60px;">Level</th>
            </tr>
          </thead>
          <tbody>
      `;

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART A <small style="font-weight:normal; font-size:12px;">(${data.part_a.q_count} Ã ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_a)}</tbody></table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART B <small style="font-weight:normal; font-size:12px;">(${data.part_b.q_count} Ã ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_b)}</tbody></table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART C <small style="font-weight:normal; font-size:12px;">(${data.part_c.q_count} Ã ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_c)}</tbody></table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Answer Key - ${coTag}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 5px 3px; vertical-align: top; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; ANSWER KEY & RUBRIC&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.classList.remove('hidden');
        statusEl.className = "text-sm font-bold mt-2 text-blue-400";
        statusEl.innerText = "Uploading photo...";
      }

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(async res => {
        const data = await res.json().catch(() => ({ status: 'ERROR', message: 'Invalid server response.' }));
        if (res.ok && data.status === 'SUCCESS') {
          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-green-400";
            statusEl.innerText = "Photo updated successfully!";
          }

          const photoUrl = data.photo_url + '?t=' + new Date().getTime();
          document.querySelectorAll('#staffProfileImg, #sidebarStaffImg, #sidebarAvatarContainer img, aside img.rounded-full').forEach(img => {
            img.src = photoUrl;
          });

          if (statusEl) {
            setTimeout(() => statusEl.classList.add('hidden'), 3000);
          }
        } else {
          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-rose-400";
            statusEl.innerText = data.message || "Upload failed.";
          }
        }
      })
      .catch(err => {
        console.error('Upload error:', err);
        if (statusEl) {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = "Error uploading photo. Please check file format and size.";
        }
      });
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-[10px] hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }
  
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(`/api/classroom/online-tests/${testId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(`/api/classroom/online-tests/${testId}/key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const deptMap = {
              'EL': 'ELECTRONICS ENGINEERING',
              'CS': 'COMPUTER SCIENCE AND ENGINEERING',
              'CE': 'CIVIL ENGINEERING',
              'ME': 'MECHANICAL ENGINEERING',
              'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
              'IT': 'INFORMATION TECHNOLOGY',
              'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
            };
            const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
            const subjectName = currentSubjectName;
            const subjectCode = currentSubjectCode;
            const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
            const testName = data.test_name || 'Online Test';
            const totalQ = data.total || 0;

            let html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Online MCQ Test - ${testName}</title>
  <style>
    @page { size: A4 portrait; margin: 1.5cm 2cm; }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Times New Roman', Times, serif;
      font-size: 13px;
      color: #000;
      background: #fff;
    }
    h2, h3, h4, p { margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 16px; }
    .college-name { font-size: 21px; font-weight: bold; letter-spacing: 1px; }
    .dept-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
    .subject-info { font-size: 12px; margin-top: 4px; color: #222; }
    .exam-title { font-size: 14px; margin-top: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #888; border-bottom: 1px solid #888; padding: 4px 0; display: inline-block; background-color: #f0f0f0; }
    .meta-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; }
    .q-block { margin-bottom: 15px; page-break-inside: avoid; }
    .q-text { font-weight: bold; margin-bottom: 5px; }
    .options { list-style-type: lower-alpha; margin: 0; padding-left: 20px; }
    .options li { margin-bottom: 3px; }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${testName} &ndash; Answer Key&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Total Questions:</strong> ${totalQ}</span>
    </div>
  </div>`;

            data.details.forEach((q, i) => {
              html += `<div class="q-block">
                <div class="q-text">${i+1}. ${q.q} &nbsp; <em>[${q.co}]</em></div>
                <ul class="options">`;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                if (isCorrect) {
                  html += `<li><strong>${opt} &nbsp; &#10004;</strong></li>`;
                } else {
                  html += `<li>${opt}</li>`;
                }
              });
              html += `</ul></div>`;
            });

            html += `</body></html>`;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }

      function showVcStudentsList() {
        const badge = document.getElementById('vcModalBatchBadge');
        if (badge) {
          badge.innerText = `${window.currentVirtualBatchId || ''} (S-${window.currentVirtualSemester || 1})`;
        }
        let html = '';
        if (window.currentVirtualStudents && window.currentVirtualStudents.length > 0) {
          html = `
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900 border-b border-slate-800 text-slate-400">
                  <th class="p-3 font-bold w-12 text-center">No.</th>
                  <th class="p-3 font-bold w-20 text-center">Roll No</th>
                  <th class="p-3 font-bold w-32 text-center">SBTE Reg No</th>
                  <th class="p-3 font-bold w-32 text-center">Admission No</th>
                  <th class="p-3 font-bold">Student Name</th>
                  <th class="p-3 font-bold w-48">Remarks</th>
                </tr>
              </thead>
              <tbody>
          `;
          window.currentVirtualStudents.forEach((s, idx) => {
            html += `
              <tr class="border-b border-slate-800/50 hover:bg-slate-900/50 transition-colors text-sm">
                <td class="p-3 text-center text-slate-500 font-bold">${idx + 1}</td>
                <td class="p-3 text-center font-mono text-slate-300 font-bold">${s.roll_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-400">${s.sbte_reg_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-400">${s.reg_no}</td>
                <td class="p-3 font-bold text-slate-200 max-w-[220px] whitespace-normal break-words">${s.name}</td>
                <td class="p-2"><input type="text" placeholder="Add remark..." class="w-full bg-slate-900/50 border border-slate-800 rounded-lg px-3 py-1.5 text-sm text-slate-300 focus:outline-none focus:border-blue-500/50"></td>
              </tr>
            `;
          });
          html += `</tbody></table>`;
        } else {
          html = '<p class="text-sm text-slate-500 text-center py-4">No students enrolled in this classroom.</p>';
        }
        document.getElementById('vcStudentsListContent').innerHTML = html;
        document.getElementById('vcStudentsModal').classList.remove('hidden');
      }

      function closeVcStudentsList() {
        document.getElementById('vcStudentsModal').classList.add('hidden');
      }

      function printVcStudentsList() {
        if (!window.currentVirtualStudents || window.currentVirtualStudents.length === 0) {
            alert("No students to print.");
            return;
        }

        const deptMap = {
          'EL': 'ELECTRONICS ENGINEERING',
          'CS': 'COMPUTER SCIENCE AND ENGINEERING',
          'CE': 'CIVIL ENGINEERING',
          'ME': 'MECHANICAL ENGINEERING',
          'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
          'IT': 'INFORMATION TECHNOLOGY',
          'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
        };
        const branchCode = "{{ session('userBranch', '') }}";
        const deptName = deptMap[branchCode.toUpperCase()] || branchCode;
        const batchName = document.getElementById('vcSubtitle') ? document.getElementById('vcSubtitle').innerText.replace('Batch:', '').trim() : '';
        const revision = window.currentSyllabusRevision || '2021';

        let printHtml = `
          <html>
            <head>
              <title>Classroom Students List</title>
              <style>
                @page { size: A4 portrait; margin: 1.5cm; }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #333; padding: 0; margin: 0; }
                .header-container { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 20px; }
                .college-name { font-size: 18px; font-weight: 800; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
                .dept-name { font-size: 13px; font-weight: 600; text-transform: uppercase; margin: 4px 0 0; color: #555; }
                .doc-title { font-size: 15px; font-weight: 700; text-transform: uppercase; margin: 12px 0 4px; letter-spacing: 1px; text-decoration: underline; }
                
                .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 20px; font-size: 12px; }
                .meta-item { line-height: 1.4; }
                .meta-label { font-weight: bold; color: #555; }
                
                table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
                th, td { border: 1px solid #666; padding: 8px 10px; text-align: left; }
                th { background-color: #f1f5f9; font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
                .text-center { text-align: center; }
                .font-mono { font-family: monospace; }
                tr:nth-child(even) { background-color: #f8fafc; }
              </style>
            </head>
              <body>
                <div class="header-container">
                  <h1 class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                  <h2 class="dept-name">DEPARTMENT OF ${deptName}</h2>
                  <div class="doc-title">Enrolled Students Register</div>
                </div>

                <div class="meta-grid">
                  <div class="meta-item"><span class="meta-label">Batch:</span> ${batchName}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Syllabus Revision:</span> ${revision}</div>
                  <div class="meta-item"><span class="meta-label">Subject Code:</span> ${currentSubjectCode}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Subject Name:</span> ${currentSubjectName}</div>
                </div>

                <table>
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 40px;">No.</th>
                      <th class="text-center" style="width: 80px;">Roll No</th>
                      <th class="text-center" style="width: 120px;">SBTE Reg No</th>
                      <th class="text-center" style="width: 120px;">Admission No</th>
                      <th>Student Name</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
          `;

          window.currentVirtualStudents.forEach((s, idx) => {
            printHtml += `
              <tr>
                <td class="text-center font-mono">${idx + 1}</td>
                <td class="text-center font-mono">${s.roll_no || '-'}</td>
                <td class="text-center font-mono">${s.sbte_reg_no || '-'}</td>
                <td class="text-center font-mono">${s.reg_no}</td>
                <td style="font-weight: 600;">${s.name}</td>
                <td></td>
              </tr>
            `;
          });

          printHtml += `
                  </tbody>
                </table>
                ${'<script>'}
                  setTimeout(() => { window.print(); window.close(); }, 500);
                ${'</' + 'script>'}
              </body>
            </html>
          `;

          let printWin = window.open('', '_blank');
          printWin.document.write(printHtml);
          printWin.document.close();
      }

    function fetchClassReports() {
      if (!currentSubjectId) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading reports...</p>
        </div>
      `;

      fetch(`/api/staff/attendance/subjects/${currentSubjectId}/reports`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            classReportsData = data;
            renderActiveReport();
          } else {
            workspace.innerHTML = `<div class="text-sm font-bold text-red-400 py-10 text-center">${data.message || 'Failed to load reports.'}</div>`;
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = '<div class="text-sm font-bold text-red-400 py-10 text-center">Error loading reports.</div>';
        });
    }

    function openPrintReport(reportType) {
      if (!currentSubjectId) {
        alert("Please select a subject first.");
        return;
      }
      let url = '';
      switch(reportType) {
        case 'course_exit':
          url = `/classroom/${currentSubjectId}/course-exit/report`;
          break;
        case 'nba_attainment':
          url = `/r26/classroom/${currentSubjectId}/nba/attainment-report`;
          break;
        case 'lesson_plan':
          url = `/r26/classroom/lesson-plan/print/${currentSubjectId}`;
          break;
        case 'course_file':
          url = '/course-files';
          break;
        case 'self_learning':
          url = `/r26/classroom/self-learning/print/${currentSubjectId}`;
          break;
        case 'cie_marksheet':
          url = `/r26/classroom/${currentSubjectId}/internals/print-cie`;
          break;
        case 'final_results':
          url = `/r26/classroom/${currentSubjectId}/final-results/print`;
          break;
        case 'series_marks':
          url = `/r26/classroom/${currentSubjectId}/series-exams/print-marks`;
          break;
      }
      if (url) {
        window.open(url, '_blank');
      }
    }

    function loadClassReport(type) {
      activeReportType = type;
      
      const buttons = [
        { id: 'attendance_log', btn: 'btnReportLog' },
        { id: 'subject_log', btn: 'btnReportSubject' },
        { id: 'summary_matrix', btn: 'btnReportMatrix' }
      ];

      buttons.forEach(b => {
        const el = document.getElementById(b.btn);
        if (!el) return;
        if (b.id === type) {
          el.className = "px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm cursor-pointer transition-premium";
        } else {
          el.className = "px-4 py-2 bg-slate-900 text-slate-300 border border-slate-800 rounded-xl font-bold text-sm cursor-pointer hover:bg-slate-800 transition-premium";
        }
      });

      renderActiveReport();
    }

    function renderActiveReport() {
      if (!classReportsData) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = '';

      if (activeReportType === 'attendance_log') {
        renderAttendanceLogReport(workspace);
      } else if (activeReportType === 'subject_log') {
        renderSubjectLogReport(workspace);
      } else if (activeReportType === 'summary_matrix') {
        renderSummaryMatrixReport(workspace);
      }
    }

    function renderAttendanceLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black">
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Topics Covered</th>
                <th class="p-4 text-center">Present</th>
                <th class="p-4 text-center">Absent</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 font-mono font-bold text-slate-300">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-200">${log.topics_covered || '-'}</td>
            <td class="p-4 text-center font-bold text-emerald-400">${log.present_count}</td>
            <td class="p-4 text-center font-bold text-rose-400">${log.absent_count}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSubjectLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No class logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black">
                <th class="p-4">Completed Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Lesson Plan Reference</th>
                <th class="p-4">Topics Covered</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 font-mono font-bold text-slate-300">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-400 font-bold">${log.lesson_plan_id ? 'LP ID: ' + log.lesson_plan_id : 'Manual Entry'}</td>
            <td class="p-4 text-slate-200">${log.topics_covered || '-'}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSummaryMatrixReport(container) {
      const dates = classReportsData.dates || [];
      const matrix = classReportsData.matrix || [];

      if (dates.length === 0 || matrix.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance summary available.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-800/60 rounded-xl bg-slate-900/20 max-h-[500px]">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-950/40 text-slate-400 border-b border-slate-800 uppercase tracking-wider text-xs font-black sticky top-0 z-10">
                <th class="p-4 bg-slate-950/90 w-16 text-center sticky left-0 z-20">Roll</th>
                <th class="p-4 bg-slate-950/90 w-44 sticky left-16 z-20">Name</th>
      `;

      dates.forEach(d => {
        const shortDate = d.date.substring(5);
        html += `<th class="p-4 text-center min-w-[70px]">${shortDate}<br><span class="text-[10px] text-slate-500">P${d.period}</span></th>`;
      });

      html += `
              </tr>
            </thead>
            <tbody>
      `;

      matrix.forEach(row => {
        html += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium">
            <td class="p-4 text-center font-bold text-slate-500 bg-slate-900/90 sticky left-0 z-10">${row.roll_no || '-'}</td>
            <td class="p-4 font-bold text-white bg-slate-900/90 sticky left-16 z-10 truncate max-w-[176px]">${row.name}</td>
        `;

        dates.forEach(d => {
          const key = d.date + ' | P' + d.period;
          const status = row.attendance[key] || '-';
          let cellClass = 'text-slate-500';
          if (status === 'P') cellClass = 'text-emerald-400 font-bold';
          if (status === 'A') cellClass = 'text-rose-400 font-bold';

          html += `<td class="p-4 text-center ${cellClass}">${status}</td>`;
        });

        html += `</tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function fetchQuestionBank(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading Question Bank...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderQuestionBank(data.questions);
        } else {
          container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Failed to load questions.</div>`;
        }
      })
      .catch(err => {
        container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Error loading questions.</div>`;
      });
    }

    function renderQuestionBank(questions) {
      const container = document.getElementById('qbankCoGroups');
      if (!questions || questions.length === 0) {
        container.innerHTML = `
          <div class="text-center py-12 text-slate-400 space-y-4 max-w-md mx-auto">
            <div class="bg-slate-900/50 p-4 rounded-full border border-slate-800/60 inline-block">
              <span class="material-symbols-rounded text-3xl text-slate-600 block">database</span>
            </div>
            <p class="text-sm font-bold text-slate-300">No questions in this subject's pool.</p>
            <p class="text-sm text-slate-500">You can download the template CSV, fill it with questions, and upload it. Alternatively, seed the pool instantly with high-quality questions using AI.</p>
            <div class="pt-2">
              <button onclick="seedQuestionBankWithAi(currentSubjectId)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer shadow-md flex items-center gap-1.5 mx-auto">
                <span class="material-symbols-rounded text-base">auto_awesome</span> Seed Pool via AI
              </button>
            </div>
          </div>
        `;
        return;
      }

      const markGroups = {
        '1 Mark Questions': [],
        '3 Mark Questions': [],
        '7 Mark Questions': [],
        'Other Marks': []
      };

      questions.forEach(q => {
        const marks = parseInt(q.marks || 0);
        if (marks === 1) {
          markGroups['1 Mark Questions'].push(q);
        } else if (marks === 3) {
          markGroups['3 Mark Questions'].push(q);
        } else if (marks === 7) {
          markGroups['7 Mark Questions'].push(q);
        } else {
          markGroups['Other Marks'].push(q);
        }
      });

      let html = '';
      Object.keys(markGroups).forEach(groupName => {
        const qList = markGroups[groupName];
        if (qList.length === 0) return;

        html += `
          <div class="border border-slate-800/80 rounded-xl overflow-hidden bg-slate-900/10 mb-6">
            <div class="bg-slate-900/60 p-4 flex justify-between items-center border-b border-slate-800/60">
              <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-400 text-base">grade</span>
                <span class="text-sm font-black text-slate-200">${groupName}</span>
              </div>
              <span class="text-sm text-slate-400 font-bold bg-slate-950/40 px-2.5 py-1 rounded-md">${qList.length} Questions</span>
            </div>
            <div class="p-0 overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="border-b border-slate-800/60 text-slate-400 text-sm bg-slate-950/20">
                    <th class="py-2.5 px-4 font-bold w-12 text-center">#</th>
                    <th class="py-2.5 px-4 font-bold">Question Text</th>
                    <th class="py-2.5 px-4 font-bold w-20 text-center">CO Tag</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Cognitive</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Type</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/30 text-slate-300">
        `;

        qList.forEach((q, index) => {
          const typeStr = q.type === 'MCQ' ? `MCQ (Ans: ${q.correct_answer || 'N/A'})` : 'Descriptive';
          html += `
            <tr class="hover:bg-slate-900/20 transition-premium text-sm">
              <td class="py-3 px-4 text-center text-slate-500 font-mono">${index + 1}</td>
              <td class="py-3 px-4 font-bold text-slate-200">
                <div>${q.question_text}</div>
                ${q.type === 'MCQ' && q.options ? renderCompactOptions(q.options, q.correct_answer) : ''}
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-blue-950/40 text-blue-400 border border-blue-900/30 font-mono text-[11px] font-bold">${q.co_tag || 'CO1'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700/40 text-[11px] font-bold">${q.cognitive_level || 'Understand'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded ${q.type === 'MCQ' ? 'bg-purple-950/40 text-purple-400 border border-purple-900/30' : 'bg-amber-950/40 text-amber-400 border border-amber-900/30'} text-[11px] font-bold">${typeStr}</span>
              </td>
            </tr>
          `;
        });

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;
      });

      if (!html) {
        html = `<div class="text-sm text-slate-500 py-8 text-center">No grouped questions found.</div>`;
      }

      container.innerHTML = html;
    }

    function renderCompactOptions(optionsStr, correctAns) {
      const options = typeof optionsStr === 'string' ? JSON_decode_safe(optionsStr) : optionsStr;
      if (!options || options.length === 0) return '';
      const labels = ['A', 'B', 'C', 'D'];
      let optHtml = '<div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-[11px] text-slate-400 font-normal pl-2 border-l border-slate-800">';
      options.forEach((opt, idx) => {
        if (!opt) return;
        const isCorrect = correctAns === labels[idx] || correctAns === opt;
        const colorClass = isCorrect ? 'text-emerald-400 font-bold' : 'text-slate-500';
        optHtml += `<span class="${colorClass}"><b class="opacity-60">${labels[idx]}:</b> ${opt}</span>`;
      });
      optHtml += '</div>';
      return optHtml;
    }

    function JSON_decode_safe(str) {
      try {
        return JSON.parse(str);
      } catch (e) {
        return [];
      }
    }

    function downloadExcelTemplate() {
      const headers = [
        ['Type', 'Marks', 'Cognitive Level', 'CO Tag', 'Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer'],
        ['MCQ', '1', 'Remember', 'CO1', 'What is the correct definition of an embedded system?', 'A general purpose computer system', 'A specialized computer system designed for specific control functions', 'A computer system with no hardware', 'A system only used in gaming consoles', 'B'],
        ['Descriptive', '5', 'Understand', 'CO2', 'Explain the differences between RISC and CISC architectures in embedded processors.', '', '', '', '', '']
      ];
      const ws = XLSX.utils.aoa_to_sheet(headers);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Questions Template");
      XLSX.writeFile(wb, "Question_Bank_Template.xlsx");
    }

    function handleQBankUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];

      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Parsing Excel file...</p>
        </div>
      `;

      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
          
          if (rows.length < 2) {
            alert('Excel file is empty or missing data rows.');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
            return;
          }

          container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10">
              <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
              <p class="text-sm font-bold text-slate-400">Uploading and saving questions to pool...</p>
            </div>
          `;

          fetch(`/api/classroom/${currentSubjectId}/question-bank/upload-json`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rows: rows })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'SUCCESS') {
              alert(data.message || 'Questions imported successfully!');
            } else {
              alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          })
          .catch(err => {
            alert('Upload failed: server error');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          });

        } catch (err) {
          alert('Error reading Excel file: ' + err.message);
          fetchQuestionBank(currentSubjectId);
          input.value = '';
        }
      };
      
      reader.readAsArrayBuffer(file);
    }

    function seedQuestionBankWithAi(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">AI is generating structured exam questions for all COs...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank/seed-ai`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
        } else {
          alert('Failed to seed: ' + data.message);
        }
        fetchQuestionBank(subjectId);
      })
      .catch(err => {
        alert('Server error seeding question bank.');
        fetchQuestionBank(subjectId);
      });
    }

    // Mid-Semester Survey Functionality (SAR Criterion 2)
    function fetchSurveyResults(subjectId) {
      const workspace = document.getElementById('surveyWorkspace');
      const headerActions = document.getElementById('surveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/survey/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <span class="material-symbols-rounded text-2xl">forum</span>
                </div>
                <h4 class="text-base font-extrabold text-slate-200">Initiate Mid-Semester Feedback Survey</h4>
                <p class="text-sm text-slate-400 leading-relaxed">
                  Conducted around the 7th–9th week of the semester, this evaluates the teaching-learning process in real-time. It gathers student feedback on 5 criteria: Pace, Clarity, Interaction, Practicality, and Evaluation.
                </p>
                <button onclick="initiateMidSemSurvey(${subjectId})" class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold border border-blue-500/30 transition-premium shadow-lg shadow-blue-500/10 cursor-pointer">
                  Start Mid-Semester Survey
                </button>
              </div>
            `;
          } else if (res.status === 'SUCCESS') {
            const survey = res.data.survey;
            const total = res.data.total_students;
            const responded = res.data.responded_count;

            if (survey.status === 'Active') {
              workspace.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Live stats card -->
                  <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 flex flex-col justify-between space-y-4">
                    <div>
                      <span class="text-teal-400 font-bold uppercase tracking-widest text-[10px] block mb-1">Live Status</span>
                      <h4 class="text-base font-extrabold text-slate-200">Survey Active</h4>
                      <p class="text-xs text-slate-400 leading-relaxed mt-1">Students can now see and submit feedback from their dashboard task list.</p>
                    </div>
                    <div class="border-t border-slate-800/60 pt-4">
                      <div class="flex justify-between text-sm font-bold mb-1">
                        <span class="text-slate-400">Participation:</span>
                        <span class="text-white">${responded} / ${total}</span>
                      </div>
                      <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-900">
                        <div class="bg-teal-500 h-2 rounded-full" style="width: ${total > 0 ? (responded / total) * 100 : 0}%"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick instructions card -->
                  <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 flex flex-col justify-between col-span-2">
                    <div>
                      <h4 class="text-sm font-bold text-slate-300">Evaluating Criterion 2 (SAR)</h4>
                      <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        To finalize results, draw graphs, and register action plan notes, you must close the active survey. Encourage students to participate before closing.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-800/60 flex justify-end">
                      <button onclick="closeMidSemSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-600/20 hover:bg-rose-600/45 border border-rose-500/30 text-rose-300 rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results + chart + notes
              const averages = res.data.averages;
              
              workspace.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Stats overview -->
                  <div class="lg:col-span-1 space-y-6">
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">Participation Details</h4>
                      <div class="grid grid-cols-2 gap-4 text-xs font-semibold">
                        <div>
                          <span class="text-slate-500 block">Class Strength</span>
                          <span class="text-slate-200 font-bold text-sm">${total}</span>
                        </div>
                        <div>
                          <span class="text-slate-500 block">Responded</span>
                          <span class="text-slate-200 font-bold text-sm">${responded}</span>
                        </div>
                      </div>
                      <div class="pt-3 border-t border-slate-850">
                        <span class="text-slate-500 block text-xs">Response Rate</span>
                        <span class="text-emerald-400 font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">Average Scores Breakdown</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-400">Pace of delivery</span>
                          <span class="text-teal-400">${averages.pace} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">Concept clarity</span>
                          <span class="text-teal-400">${averages.clarity} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">Interactive lectures</span>
                          <span class="text-teal-400">${averages.interaction} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">Lab practicality</span>
                          <span class="text-teal-400">${averages.practicality} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">Prompt evaluation</span>
                          <span class="text-teal-400">${averages.evaluation} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Charts and Action Plan Notes -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6">
                      <h4 class="text-sm font-black text-slate-200 mb-4">Feedback Chart (Teaching-Learning Process)</h4>
                      <div class="h-64 relative">
                        <canvas id="surveyResultChart"></canvas>
                      </div>
                    </div>

                    <!-- Action Taken Form -->
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">SAR Criterion 2 Action Plan Notes</h4>
                      
                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Improvements Noted by Faculty</label>
                        <textarea id="improvementsNoted" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-xs focus:outline-none focus:border-blue-500 font-medium transition-all" rows="2" placeholder="e.g. Remedial classes identified for weak students, changing lecture pace...">${survey.improvements_noted || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Corrective Action Taken (Faculty Member)</label>
                        <textarea id="correctiveAction" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-xs focus:outline-none focus:border-blue-500 font-medium transition-all" rows="2" placeholder="e.g. Incorporated PPT slides, allocated extra laboratory session...">${survey.action_taken || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Action Taken Notes (Class Tutor Remarks)</label>
                        <textarea id="actionTakenByTutor" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-xs focus:outline-none focus:border-blue-500 font-medium transition-all" rows="2" placeholder="Tutor remarks on student feedback and faculty actions...">${survey.action_taken_by_tutor || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Action Taken Remarks (Head of Department / HOD)</label>
                        <textarea id="actionTakenByHod" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-xs focus:outline-none focus:border-blue-500 font-medium transition-all" rows="2" placeholder="HOD remarks or corrective endorsement...">${survey.action_taken_by_hod || ''}</textarea>
                      </div>

                      <div class="flex justify-between items-center pt-2">
                        <button onclick="saveSurveyActionNotes(${subjectId})" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold border border-blue-500/30 transition-premium shadow cursor-pointer">
                          Save Notes
                        </button>
                        <a href="/classroom/${subjectId}/survey/report" target="_blank" class="px-4 py-2 bg-teal-600/10 hover:bg-teal-600/25 border border-teal-500/30 text-teal-300 rounded-xl text-xs font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer">
                          <span class="material-symbols-rounded text-sm">print</span> Print Survey Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;

              // Initialize result chart
              setTimeout(() => renderSurveyChart(averages), 100);
            }
          } else {
            alert(res.message || "Failed to load survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch survey. Network error.</div>`;
        });
    }

    function initiateMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Mid-Semester Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/survey/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/survey/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function saveSurveyActionNotes(subjectId) {
      const imp = document.getElementById('improvementsNoted').value;
      const act = document.getElementById('correctiveAction').value;
      const tut = document.getElementById('actionTakenByTutor').value;
      const hod = document.getElementById('actionTakenByHod').value;

      fetch(`/api/classroom/${subjectId}/survey/save-notes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
          improvements_noted: imp, 
          action_taken: act,
          action_taken_by_tutor: tut,
          action_taken_by_hod: hod
        })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
      });
    }

    function renderSurveyChart(averages) {
      const ctx = document.getElementById('surveyResultChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Pace', 'Clarity', 'Interaction', 'Practicality', 'Evaluation'],
          datasets: [{
            label: 'Avg Score (Out of 3)',
            data: [averages.pace, averages.clarity, averages.interaction, averages.practicality, averages.evaluation],
            backgroundColor: [
              'rgba(20, 184, 166, 0.2)',
              'rgba(14, 165, 233, 0.2)',
              'rgba(99, 102, 241, 0.2)',
              'rgba(168, 85, 247, 0.2)',
              'rgba(236, 72, 153, 0.2)'
            ],
            borderColor: [
              '#14b8a6',
              '#0ea5e9',
              '#6366f1',
              '#a855f7',
              '#ec4899'
            ],
            borderWidth: 2,
            borderRadius: 8
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: {
              min: 0,
              max: 3,
              ticks: { stepSize: 1, color: '#94a3b8' },
              grid: { color: 'rgba(51, 65, 85, 0.2)' }
            },
            y: {
              ticks: { color: '#94a3b8' },
              grid: { display: false }
            }
          }
        }
      });
    }

    // Course Exit Survey JS methods
    function fetchExitSurveyResults(subjectId) {
      const workspace = document.getElementById('exitSurveyWorkspace');
      const headerActions = document.getElementById('exitSurveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/course-exit/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <span class="material-symbols-rounded text-2xl">assignment_turned_in</span>
                </div>
                <h4 class="text-base font-extrabold text-slate-200">Initiate Course Exit Survey</h4>
                <p class="text-sm text-slate-400 leading-relaxed">
                  Conducted at the end of the semester, this exit survey maps directly to Course Outcomes (CO1 to CO4) using 10 specific attainment questions. Attainments are rated on a Low (1), Medium (2), and High (3) scale.
                </p>
                <button onclick="initiateExitSurvey(${subjectId})" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold border border-teal-500/30 transition-premium shadow-lg shadow-teal-500/10 cursor-pointer">
                  Start Course Exit Survey
                </button>
              </div>
            `;
          } else if (res.status === 'SUCCESS') {
            const survey = res.data.survey;
            const total = res.data.total_students;
            const responded = res.data.responded_count;

            if (survey.status === 'Active') {
              workspace.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Live stats card -->
                  <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 flex flex-col justify-between space-y-4">
                    <div>
                      <span class="text-teal-400 font-bold uppercase tracking-widest text-[10px] block mb-1">Live Status</span>
                      <h4 class="text-base font-extrabold text-slate-200">Survey Active</h4>
                      <p class="text-xs text-slate-400 leading-relaxed mt-1">Students can now submit exit responses mapping to COs via their dashboard.</p>
                    </div>
                    <div class="border-t border-slate-800/60 pt-4">
                      <div class="flex justify-between text-sm font-bold mb-1">
                        <span class="text-slate-400">Participation:</span>
                        <span class="text-white">${responded} / ${total}</span>
                      </div>
                      <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-900">
                        <div class="bg-teal-500 h-2 rounded-full" style="width: ${total > 0 ? (responded / total) * 100 : 0}%"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick instructions card -->
                  <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 flex flex-col justify-between col-span-2">
                    <div>
                      <h4 class="text-sm font-bold text-slate-300">Course Outcome Attainment mapping</h4>
                      <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        To calculate final attainment averages and view the printable Course Exit Report, you must close the active survey.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-800/60 flex justify-end">
                      <button onclick="closeExitSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-600/20 hover:bg-rose-600/45 border border-rose-500/30 text-rose-300 rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Exit Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results breakdown
              const averages = res.data.averages;
              const attainments = res.data.attainment_percentages;

              workspace.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Stats overview -->
                  <div class="lg:col-span-1 space-y-6">
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">Participation Details</h4>
                      <div class="grid grid-cols-2 gap-4 text-xs font-semibold">
                        <div>
                          <span class="text-slate-500 block">Class Strength</span>
                          <span class="text-slate-200 font-bold text-sm">${total}</span>
                        </div>
                        <div>
                          <span class="text-slate-500 block">Responded</span>
                          <span class="text-slate-200 font-bold text-sm">${responded}</span>
                        </div>
                      </div>
                      <div class="pt-3 border-t border-slate-850">
                        <span class="text-slate-500 block text-xs">Response Rate</span>
                        <span class="text-teal-400 font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">CO Averages (Scale 1-3)</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-400">CO1 Average score</span>
                          <span class="text-teal-400">${averages.CO1} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">CO2 Average score</span>
                          <span class="text-teal-400">${averages.CO2} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">CO3 Average score</span>
                          <span class="text-teal-400">${averages.CO3} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-400">CO4 Average score</span>
                          <span class="text-teal-400">${averages.CO4} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Attainments and Print Action -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-200">Indirect CO Attainment Levels</h4>
                      <p class="text-xs text-slate-400 leading-relaxed">Attainment is computed as: <code>(CO Average / 3) * 100</code></p>
                      
                      <div class="space-y-4 pt-2">
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-300">CO1 Attainment</span>
                            <span class="text-teal-400">${attainments.CO1}%</span>
                          </div>
                          <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-800">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO1}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-300">CO2 Attainment</span>
                            <span class="text-teal-400">${attainments.CO2}%</span>
                          </div>
                          <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-800">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO2}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-300">CO3 Attainment</span>
                            <span class="text-teal-400">${attainments.CO3}%</span>
                          </div>
                          <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-800">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO3}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-300">CO4 Attainment</span>
                            <span class="text-teal-400">${attainments.CO4}%</span>
                          </div>
                          <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-800">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO4}%"></div>
                          </div>
                        </div>
                      </div>

                      <div class="flex justify-end items-center pt-6 border-t border-slate-800/60">
                        <a href="/classroom/${subjectId}/course-exit/report" target="_blank" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer shadow-md shadow-teal-600/10">
                          <span class="material-symbols-rounded text-base">print</span> Print Course Exit Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;
            }
          } else {
            alert(res.message || "Failed to load exit survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch exit survey. Network error.</div>`;
        });
    }

    function initiateExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Course Exit Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this Course Exit Survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }
</script>

<!-- Edit Assignment Questions Modal -->
<div id="editQuestionsModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-5xl shadow-2xl flex flex-col max-h-[88vh]">
    <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
      <div>
        <h3 class="text-base font-black text-white flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">edit</span> Manually Edit Questions (<span id="editQuestionsCoBadge"></span>)
        </h3>
        <p class="text-xs text-slate-400 mt-0.5">Define one or more descriptive questions for this Course Outcome. Total marks must equal exactly 20.</p>
      </div>
      <button onclick="closeEditQuestionsModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
        <span class="material-symbols-rounded">close</span>
      </button>
    </div>
    
    <div class="p-6 overflow-y-auto space-y-4 flex-1">
      <div id="editQuestionsFieldsContainer" class="space-y-4">
        <!-- Dyn fields -->
      </div>
      
      <button type="button" onclick="addManualQuestionField()" class="w-full py-2.5 bg-slate-800/80 hover:bg-slate-800 text-slate-355 hover:text-white border border-slate-700/60 rounded-xl text-xs font-bold transition-premium flex items-center justify-center gap-1.5 cursor-pointer">
        <span class="material-symbols-rounded text-base">add_circle</span> Add Question
      </button>
    </div>
    
    <div class="px-6 py-4 bg-slate-950/60 border-t border-slate-800 flex justify-between items-center">
      <div class="text-xs font-bold text-slate-400">
        Total Marks: <span id="editQuestionsTotalMarks" class="text-slate-200 text-sm font-black">0</span> / 20
      </div>
      <div class="flex gap-2">
        <button type="button" onclick="closeEditQuestionsModal()" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-slate-350 rounded-xl text-xs font-bold transition-premium cursor-pointer">Cancel</button>
        <button type="button" onclick="saveManualQuestions()" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sm">save</span> Save Questions
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Virtual Classroom Students Modal -->
<div id="vcStudentsModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-slate-950 border border-slate-800/80 w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
    <div class="p-4 border-b border-slate-800/80 bg-slate-900/50 flex justify-between items-center">
      <h3 class="text-xl font-black text-slate-200 flex items-center gap-2 flex-wrap">
        <span class="material-symbols-rounded text-blue-400 text-2xl flex-shrink-0">groups</span> Enrolled Students
        <span id="vcModalBatchBadge" class="text-sm font-mono font-bold text-slate-300 bg-slate-800 border border-slate-700/60 px-2 py-0.5 rounded ml-2 flex-shrink-0"></span>
      </h3>
      <div class="flex items-center gap-3">
        <button onclick="printVcStudentsList()" class="text-sm font-bold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded flex items-center gap-1.5 transition-premium">
          <span class="material-symbols-rounded text-lg">print</span> Print List
        </button>
        <button onclick="closeVcStudentsList()" class="text-slate-500 hover:text-white transition-premium ml-2">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
    </div>
    <div class="p-0 overflow-y-auto custom-scrollbar flex-1">
      <div id="vcStudentsListContent"></div>
    </div>
  </div>
</div>

<!-- Seminar Evaluation Pop-up Modal -->
<div id="seminarEvaluationModal" class="fixed inset-0 bg-slate-950/85 backdrop-blur-sm z-[110] hidden flex items-center justify-center p-4">
  <div class="bg-slate-950 border border-slate-850 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
    <div class="p-4 border-b border-slate-800/80 bg-slate-900/50 flex justify-between items-center">
      <h3 class="text-base font-black text-slate-200">Evaluate Seminar Presentation</h3>
      <button onclick="closeSeminarEvaluationModal()" class="text-slate-500 hover:text-white transition-premium">
        <span class="material-symbols-rounded">close</span>
      </button>
    </div>
    <form id="seminarEvaluationForm" onsubmit="submitSeminarEvaluation(event)" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
      <div>
        <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Student</label>
        <div id="semStudentName" class="text-base font-black text-white"></div>
        <input type="hidden" id="semStudentRegNo">
      </div>

      <!-- Relevance Slider & Input -->
      <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl space-y-2">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-200">Relevance (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semRelevance" required
            oninput="syncSlider('semRelevance','semRelevanceSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-sm font-black text-white text-center focus:border-blue-500 outline-none">
        </div>
        <input type="range" id="semRelevanceSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semRelevance').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-500 bg-slate-800 cursor-pointer">
      </div>

      <!-- Literature Survey Slider & Input -->
      <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl space-y-2">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-200">Literature Survey (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semLiterature" required
            oninput="syncSlider('semLiterature','semLiteratureSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-sm font-black text-white text-center focus:border-blue-500 outline-none">
        </div>
        <input type="range" id="semLiteratureSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semLiterature').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-indigo-500 bg-slate-800 cursor-pointer">
      </div>

      <!-- Presentation Slider & Input -->
      <div class="bg-slate-900/50 border border-blue-950 p-3.5 rounded-xl space-y-2">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-blue-300">Presentation Quality (Max 37.5)</label>
          <input type="number" step="0.5" min="0" max="37.5" id="semPresentation" required
            oninput="syncSlider('semPresentation','semPresentationSlider',37.5); calculateSeminarTotal()"
            class="w-16 bg-slate-900 border border-blue-800 rounded-lg px-2 py-1 text-sm font-black text-blue-300 text-center focus:border-blue-500 outline-none">
        </div>
        <input type="range" id="semPresentationSlider" min="0" max="37.5" step="0.5" value="0"
          oninput="document.getElementById('semPresentation').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-400 bg-slate-800 cursor-pointer">
      </div>

      <!-- Compact 3 Column Input Grid -->
      <div class="grid grid-cols-3 gap-3">
        <!-- Interaction -->
        <div class="bg-slate-900/30 border border-slate-800/80 p-2.5 rounded-xl text-center space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Interaction</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semInteraction" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1.5 text-sm font-bold text-white text-center focus:border-blue-500 outline-none">
          <div class="text-[9px] text-slate-600">max 7.5</div>
        </div>

        <!-- Report -->
        <div class="bg-slate-900/30 border border-slate-800/80 p-2.5 rounded-xl text-center space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Report</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semReport" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1.5 text-sm font-bold text-white text-center focus:border-blue-500 outline-none">
          <div class="text-[9px] text-slate-600">max 7.5</div>
        </div>

        <!-- Attendance -->
        <div class="bg-slate-900/30 border border-slate-800/80 p-2.5 rounded-xl text-center space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Attendance</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semAttendance" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-1.5 py-1.5 text-sm font-bold text-white text-center focus:border-blue-500 outline-none">
          <div class="text-[9px] text-slate-600">max 7.5</div>
        </div>
      </div>

      <!-- Total Score Banner -->
      <div class="pt-4 border-t border-slate-900 flex justify-between items-center bg-slate-950/40 p-2 rounded-xl">
        <div>
          <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Score:</span>
          <span id="semTotalScoreLabel" class="text-xl font-black text-blue-400 ml-2">0.00 / 75</span>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold shadow-lg transition-premium cursor-pointer">
          Save Evaluation
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function syncSlider(inputId, sliderId, max) {
      const input = document.getElementById(inputId);
      if (!input) return;
      let rawVal = input.value;
      if (rawVal === '' || rawVal === null || rawVal === undefined) {
        if (sliderId) {
          const slider = document.getElementById(sliderId);
          if (slider) slider.value = 0;
        }
        return;
      }
      let val = parseFloat(rawVal);
      if (isNaN(val)) val = 0;
      if (val > max) val = max;
      if (val < 0) val = 0;
      input.value = val;
      if (sliderId) {
        const slider = document.getElementById(sliderId);
        if (slider) slider.value = val;
      }
    }

    let activeSeminarData = [];

    function fetchSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading evaluations data...</td></tr>';
      
      const printBtn = document.getElementById('printSeminarReportBtn');
      if (printBtn) {
        printBtn.href = `/classroom/${currentSubjectId}/seminar-report`;
      }

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          activeSeminarData = res.data;
          renderSeminarEvaluations();
        } else {
          tbody.innerHTML = `<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load seminar evaluations.</td></tr>';
      });
    }

    function renderSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '';

      if (activeSeminarData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 italic text-xs">No students enrolled in this batch.</td></tr>';
        return;
      }

      activeSeminarData.forEach(student => {
        const me = student.my_evaluation;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 text-xs font-semibold text-slate-300';
        
        row.innerHTML = `
          <td class="p-3 font-mono">${student.roll_no || '-'}</td>
          <td class="p-3 font-extrabold text-white">${student.name}</td>
          <td class="p-3 font-medium max-w-[200px] truncate" title="${student.topic || '-'}">${student.topic || '<span class="text-slate-600 italic">Not Registered</span>'}</td>
          <td class="p-3 text-slate-400">${student.guide_name || '-'}</td>
          <td class="p-3 text-center">${student.presentation_date || '-'}</td>
          <td class="p-3 text-center">${me ? me.relevance : '-'}</td>
          <td class="p-3 text-center">${me ? me.literature : '-'}</td>
          <td class="p-3 text-center">${me ? me.presentation : '-'}</td>
          <td class="p-3 text-center">${me ? me.interaction : '-'}</td>
          <td class="p-3 text-center">${me ? me.report : '-'}</td>
          <td class="p-3 text-center">${me ? me.attendance : '-'}</td>
          <td class="p-3 text-center font-bold text-slate-200">${me ? me.total_score : '-'}</td>
          <td class="p-3 text-center font-bold text-teal-400">${student.average_score} <span class="text-[10px] text-slate-500 font-normal">(${student.evaluators_count} assessors)</span></td>
          <td class="p-3 text-center">
            <button onclick="openSeminarEvaluationModal('${student.reg_no}')" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white rounded-lg font-bold text-[11px] transition-premium cursor-pointer border border-blue-500/25">
              ${me ? 'Modify' : 'Evaluate'}
            </button>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function openSeminarEvaluationModal(regNo) {
      const student = activeSeminarData.find(s => s.reg_no === regNo);
      if (!student) return;

      document.getElementById('semStudentName').innerText = `${student.name} (${student.reg_no})`;
      document.getElementById('semStudentRegNo').value = regNo;

      const me = student.my_evaluation;
      document.getElementById('semRelevance').value = me ? me.relevance : '';
      document.getElementById('semLiterature').value = me ? me.literature : '';
      document.getElementById('semPresentation').value = me ? me.presentation : '';
      
      document.getElementById('semRelevanceSlider').value = me ? me.relevance : 0;
      document.getElementById('semLiteratureSlider').value = me ? me.literature : 0;
      document.getElementById('semPresentationSlider').value = me ? me.presentation : 0;

      document.getElementById('semInteraction').value = me ? me.interaction : '';
      document.getElementById('semReport').value = me ? me.report : '';
      document.getElementById('semAttendance').value = me ? me.attendance : '';

      calculateSeminarTotal();
      document.getElementById('seminarEvaluationModal').classList.remove('hidden');
      document.getElementById('seminarEvaluationModal').classList.add('flex');
    }

    function closeSeminarEvaluationModal() {
      document.getElementById('seminarEvaluationModal').classList.add('hidden');
      document.getElementById('seminarEvaluationModal').classList.remove('flex');
    }

    function calculateSeminarTotal() {
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      const total = relevance + literature + presentation + interaction + report + attendance;
      document.getElementById('semTotalScoreLabel').innerText = `${total.toFixed(0)} / 75`;
    }

    function submitSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = document.getElementById('semStudentRegNo').value;
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reg_no: regNo,
          relevance: relevance,
          literature: literature,
          presentation: presentation,
          interaction: interaction,
          report: report,
          attendance: attendance
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Seminar evaluation saved successfully!');
          closeSeminarEvaluationModal();
          fetchSeminarEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Failed to save seminar evaluation.');
      });
    }

    let todaySeminarsData = [];
    let mobSemCurrentRegNo = null;

    function clampMobSem(input, max) {
      const v = parseFloat(input.value);
      if (!isNaN(v) && v > max) input.value = max;
      if (!isNaN(v) && v < 0) input.value = 0;
      // Sync range slider sibling if present
      const sliders = input.closest('.bg-slate-950\/40, .bg-slate-950\/40.border')?.querySelectorAll('input[type=range]');
      if (sliders && sliders.length) sliders[0].value = input.value;
    }

    function showMobileSemToast(msg, type = 'success') {
      const toast = document.getElementById('mobileSemToast');
      toast.className = `mb-4 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 ${
        type === 'success'
          ? 'bg-emerald-950/80 border border-emerald-600/40 text-emerald-300'
          : type === 'warning'
            ? 'bg-amber-950/80 border border-amber-600/40 text-amber-300'
            : 'bg-red-950/80 border border-red-600/40 text-red-300'
      }`;
      const icon = type === 'success' ? 'check_circle' : type === 'warning' ? 'warning' : 'error';
      toast.innerHTML = `<span class="material-symbols-rounded text-base">${icon}</span> ${msg}`;
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 4000);
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        const mobContainer = document.getElementById('mobileSeminarNotificationsContainer');
        
        if (container) container.innerHTML = '';
        if (mobContainer) mobContainer.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          todaySeminarsData = res.data;

          // Group by classroom_id
          const groups = {};
          todaySeminarsData.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render cards
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            // Desktop card
            if (container) {
              const card = document.createElement('div');
              card.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-premium cursor-pointer group relative overflow-hidden";
              card.onclick = () => {
                if (window.innerWidth < 768) {
                  openMobileSeminarEvaluation();
                } else {
                  openClassroom(cid, first.batch_subject_id, first.subject_name || 'Seminar');
                }
              };
              card.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <span class="material-symbols-rounded text-lg block">co_present</span>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <span class="material-symbols-rounded text-slate-600 group-hover:text-blue-400 text-sm transition-premium flex-shrink-0">arrow_forward_ios</span>
              `;
              container.appendChild(card);
            }

            // Mobile card
            if (mobContainer) {
              const cardMob = document.createElement('div');
              cardMob.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] transition-premium cursor-pointer group relative overflow-hidden";
              cardMob.onclick = () => {
                openMobileSeminarEvaluation();
              };
              cardMob.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <span class="material-symbols-rounded text-lg block">phone_android</span>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Active Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <span class="material-symbols-rounded text-slate-600 group-hover:text-amber-400 text-sm transition-premium flex-shrink-0">arrow_forward_ios</span>
              `;
              mobContainer.appendChild(cardMob);
            }
          });

          if (container) container.classList.remove('hidden');
          if (mobContainer) mobContainer.classList.remove('hidden');
        } else {
          if (container) container.classList.add('hidden');
          if (mobContainer) mobContainer.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    function goToVirtualSeminarClassroom() {
      // deprecated but kept as safe fallback
    }

    function openMobileSeminarEvaluation() {
      switchPanel('mobileSeminar');
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
      refreshMobileSeminarsList();
    }

    function backToSeminarList() {
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
    }

    function refreshMobileSeminarsList() {
      const pendingList = document.getElementById('mobilePendingInvitationsList');
      const attendingList = document.getElementById('mobileSemAttendingList');
      pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';
      attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';

      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        if (res.status !== 'SUCCESS') { pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load.</div>'; return; }
        todaySeminarsData = res.data;

        // Pending invitations
        const pending = todaySeminarsData.filter(s => !s.accepted);
        if (pending.length === 0) {
          pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No pending invitations today.</div>';
        } else {
          pendingList.innerHTML = '';
          pending.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-slate-900/60 border border-amber-700/30 rounded-xl p-4 space-y-3';
            card.innerHTML = `
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <div class="font-extrabold text-white text-sm truncate">${s.student_name}</div>
                  <div class="text-[10px] font-mono text-slate-400">${s.sbte_reg_no || '-'}</div>
                </div>
                <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-900/60 text-amber-400 border border-amber-700/40">Pending</span>
              </div>
              <div class="bg-slate-950/60 rounded-lg px-3 py-2">
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">Topic</div>
                <div class="text-xs text-white font-semibold mt-0.5 leading-snug">${s.topic || '-'}</div>
              </div>
              <div class="text-[10px] text-slate-500">Guide: <span class="text-slate-300">${s.guide_name || '-'}</span></div>
              <div class="grid grid-cols-2 gap-2">
                <button onclick="acceptMobileInvitation(${s.id})" class="py-2.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <span class="material-symbols-rounded text-sm">how_to_reg</span> Accept
                </button>
                <button onclick="openMobSemEvaluation('${s.reg_no}')" class="py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold border border-slate-700 transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <span class="material-symbols-rounded text-sm">rate_review</span> Evaluate
                </button>
              </div>
            `;
            pendingList.appendChild(card);
          });
        }

        // Accepted / Attending
        const accepted = todaySeminarsData.filter(s => s.accepted);
        if (accepted.length === 0) {
          attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No accepted seminars yet. Accept an invitation above.</div>';
        } else {
          attendingList.innerHTML = '';
          accepted.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-slate-900/40 border border-emerald-700/20 rounded-xl p-4 flex items-center justify-between gap-3';
            card.innerHTML = `
              <div class="min-w-0">
                <div class="font-bold text-white text-sm truncate">${s.student_name}</div>
                <div class="text-xs text-slate-400 mt-0.5 truncate">${s.topic || '-'}</div>
              </div>
              <button onclick="openMobSemEvaluation('${s.reg_no}')" class="shrink-0 px-4 py-2 bg-emerald-700/80 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1">
                <span class="material-symbols-rounded text-sm">edit_note</span> Evaluate
              </button>
            `;
            attendingList.appendChild(card);
          });
        }
      })
      .catch(() => {
        pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load. Try again.</div>';
      });
    }

    function acceptMobileInvitation(seminarRegId) {
      fetch('/api/lecturer/seminar/accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ seminar_registration_id: seminarRegId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showMobileSemToast('Invitation accepted! You can now evaluate this student.', 'success');
          refreshMobileSeminarsList();
          checkTodaySeminars();
        } else {
          showMobileSemToast(data.message || 'Failed to accept.', 'error');
        }
      })
      .catch(() => showMobileSemToast('Network error. Try again.', 'error'));
    }

    function openMobSemEvaluation(regNo) {
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;
      mobSemCurrentRegNo = regNo;
      currentSubjectId = seminar.batch_subject_id;

      // Populate student card
      document.getElementById('mobSemStudentName').innerText = seminar.student_name || '-';
      document.getElementById('mobSemSbteRegV2').innerText = seminar.sbte_reg_no || '-';
      document.getElementById('mobSemTopicV2').innerText = seminar.topic || '-';

      // Reset form
      ['mobSemRelevance','mobSemLiterature','mobSemPresentation','mobSemInteraction','mobSemReport','mobSemAttendance']
        .forEach(id => { document.getElementById(id).value = ''; });
      // Reset sliders
      document.querySelectorAll('#mobileSeminarForm input[type=range]').forEach(r => r.value = 0);
      calcMobSemTotal();

      // Switch to step 2
      document.getElementById('mobileSemStep1').classList.add('hidden');
      document.getElementById('mobileSemStep2').classList.remove('hidden');

      // Load existing evaluation
      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluations`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          const stud = res.data.find(s => s.reg_no === regNo);
          const me = stud ? stud.my_evaluation : null;
          if (me) {
            document.getElementById('mobSemRelevance').value = me.relevance;
            document.getElementById('mobSemLiterature').value = me.literature;
            document.getElementById('mobSemPresentation').value = me.presentation;
            document.getElementById('mobSemInteraction').value = me.interaction;
            document.getElementById('mobSemReport').value = me.report;
            document.getElementById('mobSemAttendance').value = me.attendance;
            // Sync sliders
            const sliders = document.querySelectorAll('#mobileSeminarForm input[type=range]');
            const vals = [me.relevance, me.literature, me.presentation];
            sliders.forEach((sl, i) => { if (vals[i] !== undefined) sl.value = vals[i]; });
            calcMobSemTotal();
          }
        }
      });
    }

    function calcMobSemTotal() {
      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;
      const total = relevance + literature + presentation + interaction + report + attendance;
      const pct = total / 75;

      // Update number display
      const numEl = document.getElementById('mobSemTotalNum');
      if (numEl) {
        numEl.innerText = total.toFixed(0);
        numEl.style.color = total >= 60 ? '#34d399' : total >= 45 ? '#60a5fa' : total >= 30 ? '#fbbf24' : '#f87171';
      }

      // Update score ring
      const circle = document.getElementById('mobScoreRingCircle');
      if (circle) {
        const circumference = 163.36;
        circle.style.strokeDashoffset = circumference * (1 - pct);
        circle.style.stroke = total >= 60 ? '#34d399' : total >= 45 ? '#3b82f6' : total >= 30 ? '#f59e0b' : '#ef4444';
      }
      const ringScore = document.getElementById('mobSemRingScore');
      if (ringScore) ringScore.innerText = total.toFixed(0);

      // Compat: old label
      const oldLabel = document.getElementById('mobSemTotalScoreLabel');
      if (oldLabel) oldLabel.innerText = `${total.toFixed(0)} / 75`;
    }

    // Keep old name as alias for compat
    function calculateMobileSeminarTotal() { calcMobSemTotal(); }

    function submitMobileSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = mobSemCurrentRegNo;
      if (!regNo) return;
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;

      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;

      const btn = document.getElementById('mobSemSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-base animate-spin">sync</span> Saving...';

      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ reg_no: regNo, relevance, literature, presentation, interaction, report, attendance })
      })
      .then(res => res.json())
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Save';
        if (res.status === 'SUCCESS') {
          showMobileSemToast(`Evaluation saved! Avg score: ${res.average_score} / 75`, 'success');
          // Silently refresh the desktop seminar table so marks appear without page reload
          if (typeof fetchSeminarEvaluations === 'function') {
            try {
              fetchSeminarEvaluations();
            } catch (err) {
              console.warn("Silent background table refresh failed:", err);
            }
          }
          setTimeout(() => backToSeminarList(), 1500);
        } else {
          showMobileSemToast(res.message || 'Failed to save.', 'error');
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">save</span> Save';
        showMobileSemToast('Network error. Please try again.', 'error');
      });
    }

    // Legacy: keep old handler names as aliases for compat with any inline onclick
    function handleMobileSemStudentChange() {}
    // ==========================================
    // VIRTUAL LAB WORKSPACE JAVASCRIPT CONTROLLERS
    // ==========================================
    let labStudentsData = [];
    let labExperimentsData = [];
    let labTestsData = [];
    let activeLabModalTab = 'exp';
    let gradingStudentReg = null;

    function fetchPracticalEvaluations() {
      if (!currentSubjectId) return;
      const tbody = document.getElementById('labEvaluationsTableBody');
      if (tbody) {
        tbody.innerHTML = `
          <tr>
            <td colspan="12" class="p-8 text-center text-slate-400 font-bold text-sm">
              <span class="animate-pulse">Loading student evaluation records...</span>
            </td>
          </tr>
        `;
      }

      // Set print & full workspace button hrefs
      const printBtn = document.getElementById('printLabReportBtn');
      if (printBtn) printBtn.href = `/classroom/practical/${currentSubjectId}/report/print`;
      const fullWsBtn = document.getElementById('openFullVirtualLabBtn');
      if (fullWsBtn) fullWsBtn.href = `/classroom/practical/${currentSubjectId}`;

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          labStudentsData = res.students || [];
          labExperimentsData = res.experiments || [];
          labTestsData = res.tests || [];
          renderLabEvaluationsTable();
          renderPracticalReportsTable();
          calculateLabStatistics();
        } else {
          if (tbody) tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        if (tbody) tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">Error syncing lab evaluations.</td></tr>`;
      });
    }

    function renderLabEvaluationsTable() {
      const tbody = document.getElementById('labEvaluationsTableBody');
      if (!tbody) return;
      tbody.innerHTML = '';

      if (labStudentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="15" class="p-8 text-center text-slate-500 font-bold text-sm">
              No students enrolled in this classroom.
            </td>
          </tr>
        `;
        return;
      }

      const totalExps = labExperimentsData ? labExperimentsData.length : 0;

      labStudentsData.forEach(student => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
        tr.setAttribute('data-reg', student.reg_no);
        
        let gradedCount = 0;
        if (student.experiments_marks) {
          gradedCount = Object.values(student.experiments_marks).filter(m => m !== null).length;
        }

        const avgRough = student.avg_rough_record !== undefined && student.avg_rough_record !== null ? parseFloat(student.avg_rough_record).toFixed(2) : '0.00';
        const avgFair = student.avg_fair_record !== undefined && student.avg_fair_record !== null ? parseFloat(student.avg_fair_record).toFixed(2) : '0.00';
        const avgObs = student.avg_obs_prep !== undefined && student.avg_obs_prep !== null ? parseFloat(student.avg_obs_prep).toFixed(2) : '0.00';
        const avgProc = student.avg_proc_punct !== undefined && student.avg_proc_punct !== null ? parseFloat(student.avg_proc_punct).toFixed(2) : '0.00';
        const avgViva = student.avg_viva_voce !== undefined && student.avg_viva_voce !== null ? parseFloat(student.avg_viva_voce).toFixed(2) : '0.00';
        const expAverage = student.avg_lab_work !== undefined && student.avg_lab_work !== null ? parseFloat(student.avg_lab_work).toFixed(2) : '0.00';
        const t1Val = (student.tests && student.tests['Test 1'] && student.tests['Test 1'].total !== undefined) ? parseFloat(student.tests['Test 1'].total).toFixed(1) : '0.0';
        const t2Val = (student.tests && student.tests['Test 2'] && student.tests['Test 2'].total !== undefined) ? parseFloat(student.tests['Test 2'].total).toFixed(1) : '0.0';
        const testsAvg = (student.tests && student.tests.average !== undefined && student.tests.average !== null) ? parseFloat(student.tests.average).toFixed(2) : '0.00';
        const openEndedVal = (student.open_ended !== undefined && student.open_ended !== null) ? parseFloat(student.open_ended).toFixed(1) : (student.micro_project !== undefined && student.micro_project !== null ? parseFloat(student.micro_project).toFixed(1) : '0.0');
        const attendanceVal = student.attendance_marks !== undefined && student.attendance_marks !== null ? parseFloat(student.attendance_marks).toFixed(1) : '0.0';
        const attHoursText = (student.total_classes !== undefined && student.total_classes > 0) ? ` <span class="text-[10px] text-slate-400 font-sans font-normal">(${student.present_classes}/${student.total_classes} hrs)</span>` : '';
        const internalsTotal = student.total_internal !== undefined && student.total_internal !== null ? parseFloat(student.total_internal).toFixed(2) : '0.00';

        tr.innerHTML = `
          <td class="p-2 text-center font-mono font-medium text-cyan-400 text-xs">${student.roll_no || '-'}</td>
          <td class="p-2">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="text-white hover:text-blue-300 font-medium text-xs text-left block">
              ${student.name}
            </button>
            <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded inline-block mt-0.5">${student.sbte_reg_no || student.reg_no}</span>
          </td>
          <td class="p-2 text-center">
            <span class="px-2 py-0.5 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-mono text-[11px] font-bold rounded-md">${gradedCount} / ${totalExps}</span>
          </td>
          <td class="p-2 text-center font-mono text-slate-300 text-xs">${avgRough}</td>
          <td class="p-2 text-center font-mono text-slate-300 text-xs">${avgFair}</td>
          <td class="p-2 text-center font-mono text-slate-300 text-xs">${avgObs}</td>
          <td class="p-2 text-center font-mono text-slate-300 text-xs">${avgProc}</td>
          <td class="p-2 text-center font-mono text-rose-400 text-xs">${avgViva}</td>
          <td class="p-2 text-center font-mono font-semibold text-blue-400 text-xs cursor-pointer hover:underline" onclick="openLwModal('${student.reg_no}')">${expAverage}</td>
          <td class="p-2 text-center font-mono text-amber-400 text-xs cursor-pointer hover:underline" onclick="openOeModal('${student.reg_no}')">${openEndedVal}</td>
          <td class="p-2 text-center font-mono text-emerald-400 text-xs">${attendanceVal} (${student.attendance_percentage}%)${attHoursText}</td>
          <td class="p-2 text-center font-mono text-purple-400 text-xs">${t1Val}</td>
          <td class="p-2 text-center font-mono text-purple-400 text-xs">${t2Val}</td>
          <td class="p-2 text-center font-mono font-semibold text-purple-300 text-xs">${testsAvg}</td>
          <td class="p-2 text-center font-mono font-black text-teal-300 bg-teal-500/10 text-base">${internalsTotal}</td>
        `;
        tbody.appendChild(tr);
      });
      filterLabGridByBatch();
    }

    function renderPracticalReportsTable() {
      const tbody = document.getElementById('practicalReportsTableBody');
      const countEl = document.getElementById('repTableStudentCount');
      if (!tbody) return;

      if (!labStudentsData || labStudentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="15" class="p-8 text-center text-slate-500 font-bold text-sm">
              No students enrolled in this classroom.
            </td>
          </tr>
        `;
        if (countEl) countEl.innerText = 'Enrolled: 0 Students';
        return;
      }

      if (countEl) countEl.innerText = `Enrolled: ${labStudentsData.length} Students`;
      tbody.innerHTML = '';
      const totalExps = labExperimentsData ? labExperimentsData.length : 0;

      labStudentsData.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
        tr.setAttribute('data-reg', student.reg_no);

        let gradedCount = 0;
        if (student.experiments_marks) {
          gradedCount = Object.values(student.experiments_marks).filter(m => m !== null).length;
        }

        const avgRough = student.avg_rough_record !== undefined && student.avg_rough_record !== null ? parseFloat(student.avg_rough_record).toFixed(2) : '0.00';
        const avgFair = student.avg_fair_record !== undefined && student.avg_fair_record !== null ? parseFloat(student.avg_fair_record).toFixed(2) : '0.00';
        const avgObs = student.avg_obs_prep !== undefined && student.avg_obs_prep !== null ? parseFloat(student.avg_obs_prep).toFixed(2) : '0.00';
        const avgProc = student.avg_proc_punct !== undefined && student.avg_proc_punct !== null ? parseFloat(student.avg_proc_punct).toFixed(2) : '0.00';
        const avgViva = student.avg_viva_voce !== undefined && student.avg_viva_voce !== null ? parseFloat(student.avg_viva_voce).toFixed(2) : '0.00';
        const expAverage = student.avg_lab_work !== undefined && student.avg_lab_work !== null ? parseFloat(student.avg_lab_work).toFixed(2) : '0.00';
        const t1Val = (student.tests && student.tests['Test 1'] && student.tests['Test 1'].total !== undefined) ? parseFloat(student.tests['Test 1'].total).toFixed(1) : '0.0';
        const t2Val = (student.tests && student.tests['Test 2'] && student.tests['Test 2'].total !== undefined) ? parseFloat(student.tests['Test 2'].total).toFixed(1) : '0.0';
        const testsAvg = (student.tests && student.tests.average !== undefined && student.tests.average !== null) ? parseFloat(student.tests.average).toFixed(2) : '0.00';
        const openEndedVal = (student.open_ended !== undefined && student.open_ended !== null) ? parseFloat(student.open_ended).toFixed(1) : (student.micro_project !== undefined && student.micro_project !== null ? parseFloat(student.micro_project).toFixed(1) : '0.0');
        const attendanceVal = student.attendance_marks !== undefined && student.attendance_marks !== null ? parseFloat(student.attendance_marks).toFixed(1) : '0.0';
        const internalsTotal = student.total_internal !== undefined && student.total_internal !== null ? parseFloat(student.total_internal).toFixed(2) : '0.00';
        const boardMarksVal = (student.board_exam_marks !== undefined && student.board_exam_marks !== null) ? student.board_exam_marks : '';

        tr.innerHTML = `
          <td class="p-2.5 text-center font-mono font-medium text-cyan-400 text-xs">${student.roll_no || (index + 1)}</td>
          <td class="p-2.5">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="text-white hover:text-blue-300 font-medium text-xs text-left block">
              ${student.name}
            </button>
            <span class="badge bg-slate-900 border border-cyan-500/30 text-cyan-400 font-mono text-[11px] font-medium px-1.5 py-0.5 rounded inline-block mt-0.5">${student.sbte_reg_no || student.reg_no}</span>
          </td>
          <td class="p-2.5 text-center">
            <span class="px-2 py-0.5 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-mono text-[11px] font-bold rounded-md">${gradedCount} / ${totalExps}</span>
          </td>
          <td class="p-2.5 text-center font-mono text-slate-300 text-xs">${avgRough}</td>
          <td class="p-2.5 text-center font-mono text-slate-300 text-xs">${avgFair}</td>
          <td class="p-2.5 text-center font-mono text-slate-300 text-xs">${avgObs}</td>
          <td class="p-2.5 text-center font-mono text-slate-300 text-xs">${avgProc}</td>
          <td class="p-2.5 text-center font-mono text-rose-400 text-xs">${avgViva}</td>
          <td class="p-2.5 text-center font-mono font-semibold text-blue-400 text-xs cursor-pointer hover:underline" onclick="openLwModal('${student.reg_no}')">${expAverage}</td>
          <td class="p-2.5 text-center font-mono text-amber-400 text-xs cursor-pointer hover:underline" onclick="openOeModal('${student.reg_no}')">${openEndedVal}</td>
          <td class="p-2.5 text-center font-mono text-emerald-400 text-xs">${attendanceVal}</td>
          <td class="p-2.5 text-center font-mono text-purple-400 text-xs">${t1Val}</td>
          <td class="p-2.5 text-center font-mono text-purple-400 text-xs">${t2Val}</td>
          <td class="p-2.5 text-center font-mono font-semibold text-purple-300 text-xs">${testsAvg}</td>
          <td class="p-2.5 text-center font-mono font-black text-teal-300 bg-teal-500/10 text-base">${internalsTotal}</td>
          <td class="p-2.5 text-center">
            <input type="number" step="0.5" min="0" max="50" value="${boardMarksVal}" placeholder="0.0" onchange="quickUpdateBoardExam('${student.reg_no}', this.value)" class="no-spinner w-16 bg-slate-950 border border-slate-800 focus:border-blue-500 rounded px-1.5 py-1 text-center font-mono font-bold text-sky-400 text-xs outline-none">
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function quickUpdateBoardExam(regNo, val) {
      if (!currentSubjectId) return;
      const student = labStudentsData.find(s => s.reg_no === regNo);
      if (!student) return;

      student.board_exam_marks = val;

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reg_no: regNo,
          open_ended_project_topic: student.open_ended_project_topic || '',
          micro_project: student.micro_project !== undefined && student.micro_project !== null ? student.micro_project : (student.open_ended || 0),
          attendance_marks: student.attendance_marks !== undefined && student.attendance_marks !== null ? student.attendance_marks : 0,
          board_exam_marks: val,
          tests: student.tests || {},
          experiments: student.experiments_marks || {}
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          console.log('Board exam mark updated successfully');
        }
      })
      .catch(err => console.error(err));
    }

    function filterLabGridByBatch() {
      const filterSelect = document.getElementById('labBatchFilterSelect');
      const filterVal = filterSelect ? filterSelect.value : 'combined';
      const tbody = document.getElementById('labEvaluationsTableBody');
      if (!tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr[data-reg]'));
      if (rows.length === 0) return;
      const total = rows.length;
      const mid = Math.ceil(total / 2);
      rows.forEach((row, idx) => {
        if (filterVal === 'combined') {
          row.classList.remove('hidden');
        } else if (filterVal === '1') {
          if (idx < mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        } else if (filterVal === '2') {
          if (idx >= mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        }
      });
    }

    function calculateLabStatistics() {
      if (labStudentsData.length === 0) return;
      
      let sumInternals = 0;
      let sumBoard = 0;
      let boardCount = 0;
      let passedCount = 0;

      labStudentsData.forEach(student => {
        sumInternals += parseFloat(student.total_internal || 0);
        if (student.board_exam_marks !== null) {
          sumBoard += parseFloat(student.board_exam_marks);
          boardCount++;

          const totalScore = parseFloat(student.total_internal || 0) + parseFloat(student.board_exam_marks);
          if (totalScore >= 50 && parseFloat(student.board_exam_marks) >= 20) {
            passedCount++;
          }
        }
      });

      const avgInternal = sumInternals / labStudentsData.length;
      const avgBoard = boardCount > 0 ? (sumBoard / boardCount) : 0;
      const passPercent = boardCount > 0 ? ((passedCount / boardCount) * 100) : 0;

      const statAvgInt = document.getElementById('statLabAvgInternal');
      if (statAvgInt) statAvgInt.innerText = `${avgInternal.toFixed(2)} / 75`;
      const statAvgBrd = document.getElementById('statLabAvgBoard');
      if (statAvgBrd) statAvgBrd.innerText = boardCount > 0 ? `${avgBoard.toFixed(2)} / 50` : 'N/A';
      const statPassPct = document.getElementById('statLabPassPercent');
      if (statPassPct) statPassPct.innerText = boardCount > 0 ? `${passPercent.toFixed(1)}%` : 'N/A';
      const statTotalExps = document.getElementById('statLabTotalExps');
      if (statTotalExps) statTotalExps.innerText = labExperimentsData.length;
    }

    // Modal tabs toggle
    function switchLabModalTab(tabId) {
      activeLabModalTab = tabId;
      ['exp', 'test', 'project', 'board'].forEach(t => {
        const el = document.getElementById('labModalTab_' + t);
        const btn = document.getElementById('labTabBtn_' + t);
        if (t === tabId) {
          el.classList.remove('hidden');
          btn.classList.add('border-blue-500', 'text-blue-400');
          btn.classList.remove('border-transparent', 'text-slate-400');
        } else {
          el.classList.add('hidden');
          btn.classList.remove('border-blue-500', 'text-blue-400');
          btn.classList.add('border-transparent', 'text-slate-400');
        }
      });
    }

    /* DEDICATED LABWORK & OPEN-ENDED MODALS */
    let currentLwStudentIndex = -1;
    let currentOeStudentIndex = -1;
    let currentLwTempMarks = {};

    function stepSlider(inputId, delta, min, max) {
      const input = document.getElementById(inputId);
      if (!input) return;
      let current = parseFloat(input.value);
      if (isNaN(current)) current = 0;
      let val = Math.min(max, Math.max(min, current + delta));
      val = Math.round(val * 2) / 2;
      input.value = val;
      input.dispatchEvent(new Event('input'));
    }

    function openLwModal(regNo) {
      if (!labStudentsData || labStudentsData.length === 0) {
        alert("No student data available. Please refresh the page.");
        return;
      }
      const idx = labStudentsData.findIndex(s => s.reg_no == regNo || s.sbte_reg_no == regNo || String(s.reg_no) === String(regNo));
      if (idx === -1) {
        alert("Student evaluation record not found.");
        return;
      }

      currentLwStudentIndex = idx;
      const student = labStudentsData[idx];

      const elInfo = document.getElementById('lwModalStudentInfo');
      const elCount = document.getElementById('lwModalStudentCounter');
      if (elInfo) elInfo.innerText = `${student.name} (${student.sbte_reg_no || student.reg_no})`;
      if (elCount) elCount.innerText = `${idx + 1} / ${labStudentsData.length}`;

      currentLwTempMarks = JSON.parse(JSON.stringify(student.experiments_marks || {}));

      // Populate Experiments Select
      const sel = document.getElementById('lwModalExpSelect');
      if (sel) {
        sel.innerHTML = '';
        if (!labExperimentsData || labExperimentsData.length === 0) {
          sel.innerHTML = '<option value="">No experiments configured</option>';
        } else {
          labExperimentsData.forEach(exp => {
            const opt = document.createElement('option');
            opt.value = exp.id;
            opt.text = `Exp ${exp.experiment_no}: ${exp.title}`;
            sel.appendChild(opt);
          });
        }
      }

      loadLwExpValues();

      const modal = document.getElementById('labworkGradeModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function loadLwExpValues() {
      const sel = document.getElementById('lwModalExpSelect');
      if (!sel || !sel.value) return;
      const expId = sel.value;

      const m = currentLwTempMarks[expId] || {};
      const rough = m.rough_record !== undefined && m.rough_record !== null && m.rough_record !== '' ? Math.min(5, parseFloat(m.rough_record)) : '';
      const fair = m.fair_record !== undefined && m.fair_record !== null && m.fair_record !== '' ? Math.min(7.5, parseFloat(m.fair_record)) : '';
      const obsRaw = m.obs_prep !== undefined && m.obs_prep !== null && m.obs_prep !== '' ? m.obs_prep : (m.prerequisites !== undefined && m.prerequisites !== null && m.prerequisites !== '' ? m.prerequisites : '');
      const obs = obsRaw !== '' ? Math.min(7.5, parseFloat(obsRaw)) : '';
      const procRaw = m.proc_punct !== undefined && m.proc_punct !== null && m.proc_punct !== '' ? m.proc_punct : (m.work_done !== undefined && m.work_done !== null && m.work_done !== '' ? m.work_done : '');
      const proc = procRaw !== '' ? Math.min(7.5, parseFloat(procRaw)) : '';
      const vivaRaw = m.viva_voce !== undefined && m.viva_voce !== null && m.viva_voce !== '' ? m.viva_voce : (m.result !== undefined && m.result !== null && m.result !== '' ? m.result : '');
      const viva = vivaRaw !== '' ? Math.min(10, parseFloat(vivaRaw)) : '';
      const expDate = m.date || '';

      const inputRough = document.getElementById('lw_rough');
      const inputFair = document.getElementById('lw_fair');
      const inputObs = document.getElementById('lw_obs');
      const inputProc = document.getElementById('lw_proc');
      const inputViva = document.getElementById('lw_viva');
      const inputDate = document.getElementById('lwModalExpDate');

      if (inputRough) inputRough.value = (typeof rough === 'number' && !isNaN(rough)) ? rough : '';
      if (inputFair) inputFair.value = (typeof fair === 'number' && !isNaN(fair)) ? fair : '';
      if (inputObs) inputObs.value = (typeof obs === 'number' && !isNaN(obs)) ? obs : '';
      if (inputProc) inputProc.value = (typeof proc === 'number' && !isNaN(proc)) ? proc : '';
      if (inputViva) inputViva.value = (typeof viva === 'number' && !isNaN(viva)) ? viva : '';
      if (inputDate) inputDate.value = expDate;

      syncSlider('lw_rough', 'lw_rough_slider', 5);
      syncSlider('lw_fair', 'lw_fair_slider', 7.5);
      syncSlider('lw_obs', 'lw_obs_slider', 7.5);
      syncSlider('lw_proc', 'lw_proc_slider', 7.5);
      syncSlider('lw_viva', 'lw_viva_slider', 10);

      calcLwExpTotal();
    }

    function syncLwComponent(key, val, max) {
      const sel = document.getElementById('lwModalExpSelect');
      if (!sel || !sel.value) return;
      const expId = sel.value;
      if (!currentLwTempMarks[expId]) currentLwTempMarks[expId] = {};

      const numVal = (val !== '' && val !== null && !isNaN(val)) ? parseFloat(val) : '';
      let boundedVal = numVal;
      if (typeof boundedVal === 'number') {
        if (boundedVal > max) boundedVal = max;
        if (boundedVal < 0) boundedVal = 0;
      }

      if (key === 'rough') currentLwTempMarks[expId].rough_record = boundedVal;
      if (key === 'fair') currentLwTempMarks[expId].fair_record = boundedVal;
      if (key === 'obs') { currentLwTempMarks[expId].obs_prep = boundedVal; currentLwTempMarks[expId].prerequisites = boundedVal; }
      if (key === 'proc') { currentLwTempMarks[expId].proc_punct = boundedVal; currentLwTempMarks[expId].work_done = boundedVal; }
      if (key === 'viva') currentLwTempMarks[expId].viva_voce = boundedVal;

      const input = document.getElementById(`lw_${key}`);
      const slider = document.getElementById(`lw_${key}_slider`);
      const displayVal = boundedVal !== '' ? boundedVal : '';

      if (input && document.activeElement !== input) {
        input.value = displayVal;
      }
      if (slider && document.activeElement !== slider) {
        slider.value = boundedVal !== '' ? boundedVal : 0;
      }

      calcLwExpTotal();
    }

    function updateLwExpField(key, val) {
      const sel = document.getElementById('lwModalExpSelect');
      if (!sel || !sel.value) return;
      const expId = sel.value;
      if (!currentLwTempMarks[expId]) currentLwTempMarks[expId] = {};
      currentLwTempMarks[expId][key] = val;
    }

    function calcLwExpTotal() {
      const r = parseFloat(document.getElementById('lw_rough')?.value) || 0;
      const f = parseFloat(document.getElementById('lw_fair')?.value) || 0;
      const o = parseFloat(document.getElementById('lw_obs')?.value) || 0;
      const p = parseFloat(document.getElementById('lw_proc')?.value) || 0;
      const v = parseFloat(document.getElementById('lw_viva')?.value) || 0;
      const total = r + f + o + p + v;
      const el = document.getElementById('lwModalExpTotal');
      if (el) el.innerText = `${total.toFixed(1)} / 37.5`;
    }

    function closeLwModal() {
      const modal = document.getElementById('labworkGradeModal');
      if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }
    }

    function navigateLwStudent(step) {
      const targetIdx = currentLwStudentIndex + step;
      if (targetIdx >= 0 && targetIdx < labStudentsData.length) {
        openLwModal(labStudentsData[targetIdx].reg_no);
      }
    }

    function saveLwModal(shouldClose, navStep = 0) {
      if (currentLwStudentIndex === -1) return;
      const student = labStudentsData[currentLwStudentIndex];

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          reg_no: student.reg_no,
          experiments: currentLwTempMarks
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          fetchPracticalEvaluations();
          if (navStep !== 0) {
            navigateLwStudent(navStep);
          } else if (shouldClose) {
            closeLwModal();
          }
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save experiment marks.'));
    }

    /* OPEN-ENDED PROJECT MODAL CONTROLLERS */
    function openOeModal(regNo) {
      if (!labStudentsData || labStudentsData.length === 0) {
        alert("No student data available. Please refresh the page.");
        return;
      }
      const idx = labStudentsData.findIndex(s => s.reg_no == regNo || s.sbte_reg_no == regNo || String(s.reg_no) === String(regNo));
      if (idx === -1) {
        alert("Student evaluation record not found.");
        return;
      }

      currentOeStudentIndex = idx;
      const student = labStudentsData[idx];

      const elInfo = document.getElementById('oeModalStudentInfo');
      const elCount = document.getElementById('oeModalStudentCounter');
      if (elInfo) elInfo.innerText = `${student.name} (${student.sbte_reg_no || student.reg_no})`;
      if (elCount) elCount.innerText = `${idx + 1} / ${labStudentsData.length}`;

      const topic = student.open_ended_project_topic || student.open_ended_topic || '';
      const mark = (student.micro_project !== undefined && student.micro_project !== null) ? student.micro_project : ((student.open_ended !== undefined && student.open_ended !== null) ? student.open_ended : '');

      const inputTopic = document.getElementById('oeModalProjectTopic');
      const inputMark = document.getElementById('oe_mark');

      if (inputTopic) inputTopic.value = topic;
      if (inputMark) inputMark.value = mark;
      syncOeSlider(mark);

      const modal = document.getElementById('openEndedGradeModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function syncOeSlider(val) {
      const numVal = (val !== '' && val !== null && !isNaN(val)) ? parseFloat(val) : '';
      let boundedVal = numVal;
      if (typeof boundedVal === 'number') {
        if (boundedVal > 7.5) boundedVal = 7.5;
        if (boundedVal < 0) boundedVal = 0;
      }

      const input = document.getElementById('oe_mark');
      const slider = document.getElementById('oe_mark_slider');
      const displayVal = boundedVal !== '' ? boundedVal : '';

      if (input && document.activeElement !== input) {
        input.value = displayVal;
      }
      if (slider && document.activeElement !== slider) {
        slider.value = boundedVal !== '' ? boundedVal : 0;
      }
    }

    function closeOeModal() {
      const modal = document.getElementById('openEndedGradeModal');
      if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }
    }

    function navigateOeStudent(step) {
      const targetIdx = currentOeStudentIndex + step;
      if (targetIdx >= 0 && targetIdx < labStudentsData.length) {
        openOeModal(labStudentsData[targetIdx].reg_no);
      }
    }

    function saveOeModal(shouldClose, navStep = 0) {
      if (currentOeStudentIndex === -1) return;
      const student = labStudentsData[currentOeStudentIndex];

      const topic = document.getElementById('oeModalProjectTopic')?.value || '';
      const mark = document.getElementById('oe_mark')?.value;

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          reg_no: student.reg_no,
          open_ended_project_topic: topic,
          micro_project: (mark !== '' && mark !== null && !isNaN(mark)) ? parseFloat(mark) : null
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          fetchPracticalEvaluations();
          if (navStep !== 0) {
            navigateOeStudent(navStep);
          } else if (shouldClose) {
            closeOeModal();
          }
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save open-ended evaluation.'));
    }

    window.stepSlider = stepSlider;
    window.openLwModal = openLwModal;
    window.closeLwModal = closeLwModal;
    window.loadLwExpValues = loadLwExpValues;
    window.syncLwComponent = syncLwComponent;
    window.updateLwExpField = updateLwExpField;
    window.navigateLwStudent = navigateLwStudent;
    window.saveLwModal = saveLwModal;
    window.openOeModal = openOeModal;
    window.closeOeModal = closeOeModal;
    window.syncOeSlider = syncOeSlider;
    window.navigateOeStudent = navigateOeStudent;
    window.saveOeModal = saveOeModal;

    let tempStudentExpMarks = {};

    function openStudentLabModal(regNo) {
      gradingStudentReg = regNo;
      if (!labStudentsData || labStudentsData.length === 0) {
        console.warn("labStudentsData is empty when opening modal.");
        return;
      }

      const student = labStudentsData.find(s => s.reg_no == regNo || s.sbte_reg_no == regNo || String(s.reg_no) === String(regNo));
      if (!student) {
        console.warn("Student record not found for:", regNo, labStudentsData);
        alert("Student evaluation record not found. Please refresh the page.");
        return;
      }

      const elName = document.getElementById('labModalStudentName');
      const elReg = document.getElementById('labModalStudentReg');
      const elAtt = document.getElementById('labModalStudentAttPct');

      if (elName) elName.innerText = student.name || 'Student Evaluation';
      if (elReg) elReg.innerText = `Register No: ${student.sbte_reg_no || student.reg_no}`;
      if (elAtt) elAtt.innerText = `${student.attendance_percentage || 0}%` + ((student.total_classes !== undefined && student.total_classes > 0) ? ` (${student.present_classes || 0}/${student.total_classes} hrs)` : '');

      // Set input values safely
      const projectTopic = document.getElementById('labScore_projectTopic');
      const projectMark = document.getElementById('labScore_projectMark');
      const attMark = document.getElementById('labScore_attendanceMark');
      const boardExam = document.getElementById('labScore_boardExam');

      if (projectTopic) projectTopic.value = student.open_ended_project_topic || student.open_ended_topic || '';
      if (projectMark) projectMark.value = (student.micro_project !== undefined && student.micro_project !== null) ? student.micro_project : ((student.open_ended !== undefined && student.open_ended !== null) ? student.open_ended : '');
      if (attMark) attMark.value = (student.attendance_marks !== undefined && student.attendance_marks !== null) ? student.attendance_marks : '';
      if (boardExam) boardExam.value = (student.board_exam_marks !== undefined && student.board_exam_marks !== null) ? student.board_exam_marks : '';

      // Set test marks safely
      const t1Co1El = document.getElementById('labScore_t1_co1');
      const t1Co2El = document.getElementById('labScore_t1_co2');
      const t2Co3El = document.getElementById('labScore_t2_co3');
      const t2Co4El = document.getElementById('labScore_t2_co4');

      const tests = (student.tests && typeof student.tests === 'object') ? student.tests : {};
      const t1 = tests['Test 1'] || {};
      const t2 = tests['Test 2'] || {};

      if (t1Co1El) t1Co1El.value = (t1.CO1 !== undefined && t1.CO1 !== null) ? t1.CO1 : '';
      if (t1Co2El) t1Co2El.value = (t1.CO2 !== undefined && t1.CO2 !== null) ? t1.CO2 : '';
      if (t2Co3El) t2Co3El.value = (t2.CO3 !== undefined && t2.CO3 !== null) ? t2.CO3 : '';
      if (t2Co4El) t2Co4El.value = (t2.CO4 !== undefined && t2.CO4 !== null) ? t2.CO4 : '';

      // Sync other sliders safely
      syncSlider('labScore_projectMark', 'labScore_projectMark_slider', 7.5);
      syncSlider('labScore_attendanceMark', 'labScore_attendanceMark_slider', 15);
      syncSlider('labScore_boardExam', 'labScore_boardExam_slider', 50);
      syncSlider('labScore_t1_co1', 'labScore_t1_co1_slider', 7.5);
      syncSlider('labScore_t1_co2', 'labScore_t1_co2_slider', 7.5);
      syncSlider('labScore_t2_co3', 'labScore_t2_co3_slider', 7.5);
      syncSlider('labScore_t2_co4', 'labScore_t2_co4_slider', 7.5);

      // Clone experiments marks locally
      tempStudentExpMarks = JSON.parse(JSON.stringify(student.experiments_marks || {}));

      renderLabModalExpsList();
      switchLabModalTab('exp');
      calcLabModalScores();
      
      const modal = document.getElementById('studentLabModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    window.openStudentLabModal = openStudentLabModal;

    function changeActiveExperiment() {
      const expId = document.getElementById('labModalExpSelect').value;
      const container = document.getElementById('activeExperimentContainer');
      const msg = document.getElementById('noActiveExperimentMsg');

      if (!expId) {
        container.classList.add('hidden');
        msg.classList.remove('hidden');
        return;
      }

      container.classList.remove('hidden');
      msg.classList.add('hidden');

      const exp = labExperimentsData.find(e => e.id == expId);
      if (!exp) return;

      document.getElementById('activeExpTitle').innerText = `Exp ${exp.experiment_no}: ${exp.title}`;
      document.getElementById('activeExpCo').innerText = exp.co_tag;

      const marks = tempStudentExpMarks[expId] || {};
      
      const setVal = (key, max) => {
        let val = marks[key];
        if (val === undefined || val === null) val = '';
        document.getElementById(`active_exp_${key}`).value = val;
        syncSlider(`active_exp_${key}`, `active_exp_${key}_slider`, max);
      };

      setVal('prerequisite', 7.5);
      setVal('execution', 7.5);
      setVal('rough_record', 5);
      setVal('fair_record', 7.5);
    }

    function updateTempExpMark(key, val) {
      const expId = document.getElementById('labModalExpSelect').value;
      if (!expId) return;

      if (!tempStudentExpMarks[expId]) {
        tempStudentExpMarks[expId] = {};
      }
      tempStudentExpMarks[expId][key] = val;
      calcLabModalScores();
    }

    function closeStudentLabModal() {
      const modal = document.getElementById('studentLabModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function calcLabModalScores() {
      let totalGradedSum = 0;
      let gradedExpsCount = 0;

      labExperimentsData.forEach(exp => {
        const val = tempStudentExpMarks[exp.id] || {};
        const prereq = parseFloat(val.prerequisite || val.obs_prep);
        const exec = parseFloat(val.execution || val.proc_punct);
        const rough = parseFloat(val.rough_record);
        const fair = parseFloat(val.fair_record);
        const viva = parseFloat(val.viva_voce);

        if (!isNaN(prereq) || !isNaN(exec) || !isNaN(rough) || !isNaN(fair) || !isNaN(viva)) {
          totalGradedSum += ((isNaN(prereq)?0:prereq) + (isNaN(exec)?0:exec) + (isNaN(rough)?0:rough) + (isNaN(fair)?0:fair) + (isNaN(viva)?0:viva));
          gradedExpsCount++;
        }
      });

      const expAvg = gradedExpsCount > 0 ? (totalGradedSum / gradedExpsCount) : 0;
      const labelExp = document.getElementById('labModalLabelExp');
      if (labelExp) labelExp.innerText = expAvg.toFixed(2);
      const labelSummaryExp = document.getElementById('labModalLabelExpSummary');
      if (labelSummaryExp) labelSummaryExp.innerText = `${expAvg.toFixed(2)} / 37.5`;

      renderLabModalExpsSummary();

      // Model test
      const t1_co1 = parseFloat(document.getElementById('labScore_t1_co1').value) || 0;
      const t1_co2 = parseFloat(document.getElementById('labScore_t1_co2').value) || 0;
      const t2_co3 = parseFloat(document.getElementById('labScore_t2_co3').value) || 0;
      const t2_co4 = parseFloat(document.getElementById('labScore_t2_co4').value) || 0;

      const t1Total = t1_co1 + t1_co2;
      const t2Total = t2_co3 + t2_co4;
      const testsAvg = (t1Total + t2Total) / 2;

      document.getElementById('labModalT1Sum').innerText = `${t1Total.toFixed(1)} / 15`;
      document.getElementById('labModalT2Sum').innerText = `${t2Total.toFixed(1)} / 15`;
      document.getElementById('labModalLabelTest').innerText = testsAvg.toFixed(2);

      // Project & Attendance
      const projectMark = parseFloat(document.getElementById('labScore_projectMark').value) || 0;
      const attMark = parseFloat(document.getElementById('labScore_attendanceMark').value) || 0;

      const totalCA = expAvg + testsAvg + projectMark + attMark;
      document.getElementById('labModalLabelInternals').innerText = `${totalCA.toFixed(2)} / 75`;
    }

    function adjustExpMark(expId, key, delta, max) {
      const input = document.getElementById(`exp_${expId}_${key}`);
      if (!input) return;
      let curr = parseFloat(input.value) || 0;
      let next = Math.max(0, Math.min(max, curr + delta));
      input.value = next.toFixed(1);
      syncExpSlider(expId, key, next.toString(), max);
    }

    function renderLabModalExpsList() {
      const container = document.getElementById('labModalExpsListContainer');
      const jumpSelect = document.getElementById('expJumpSelect');
      if (!container) return;
      container.innerHTML = '';
      if (jumpSelect) jumpSelect.innerHTML = '<option value="">Jump to Exp...</option>';

      if (!labExperimentsData || labExperimentsData.length === 0) {
        container.innerHTML = `
          <div class="p-8 text-center text-slate-500 font-bold text-sm bg-slate-950/30 border border-slate-800/40 rounded-xl">
            No experiments configured for this subject yet. Use the 'Experiments' settings button to add lab experiments.
          </div>
        `;
        return;
      }

      labExperimentsData.forEach(exp => {
        const val = tempStudentExpMarks[exp.id] || {};
        const prereq = val.prerequisite !== undefined && val.prerequisite !== null ? val.prerequisite : (val.obs_prep !== undefined && val.obs_prep !== null ? val.obs_prep : '');
        const exec = val.execution !== undefined && val.execution !== null ? val.execution : (val.proc_punct !== undefined && val.proc_punct !== null ? val.proc_punct : '');
        const rough = val.rough_record !== undefined && val.rough_record !== null ? val.rough_record : '';
        const fair = val.fair_record !== undefined && val.fair_record !== null ? val.fair_record : '';

        const pNum = parseFloat(prereq);
        const eNum = parseFloat(exec);
        const rNum = parseFloat(rough);
        const fNum = parseFloat(fair);
        const viva = val.viva_voce !== undefined && val.viva_voce !== null ? val.viva_voce : '';
        const vNum = parseFloat(viva);

        const isGraded = !isNaN(pNum) || !isNaN(eNum) || !isNaN(rNum) || !isNaN(fNum) || !isNaN(vNum);
        const expTotalVal = (isNaN(pNum)?0:pNum) + (isNaN(eNum)?0:eNum) + (isNaN(rNum)?0:rNum) + (isNaN(fNum)?0:fNum) + (isNaN(vNum)?0:vNum);
        const totalBadgeText = isGraded ? `${expTotalVal.toFixed(1)} / 37.5` : 'Not Graded';
        const totalBadgeClass = isGraded ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 font-bold' : 'bg-slate-900 border-slate-800 text-slate-500 italic';

        if (jumpSelect) {
          const opt = document.createElement('option');
          opt.value = `exp_card_${exp.id}`;
          opt.text = `Exp ${exp.experiment_no}: ${exp.title.substring(0, 30)}...`;
          jumpSelect.appendChild(opt);
        }

        const card = document.createElement('div');
        card.id = `exp_card_${exp.id}`;
        card.className = "bg-slate-950/40 border border-slate-800/60 rounded-xl p-4 space-y-3.5 transition-all hover:border-slate-700/80";
        card.innerHTML = `
          <div class="flex flex-wrap justify-between items-center bg-slate-900/60 px-4 py-2.5 rounded-lg border border-slate-800/80 gap-3">
            <div class="flex items-center gap-3">
              <span class="px-2.5 py-1 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 font-mono font-black text-xs rounded-md shadow-sm shrink-0">Exp ${exp.experiment_no}</span>
              <h4 class="text-sm font-bold text-slate-100 leading-snug">${exp.title}</h4>
            </div>
            <div class="flex items-center gap-3 shrink-0">
              <span class="px-2.5 py-0.5 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded text-xs font-bold font-mono">${exp.co_tag || 'CO1'}</span>
              <span id="exp_total_badge_${exp.id}" class="text-xs font-mono px-2.5 py-0.5 rounded border ${totalBadgeClass}">${totalBadgeText}</span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Rough Record (5) -->
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2 flex flex-col justify-between">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-amber-300">Rough Record</span>
                <input type="number" step="0.5" min="0" max="5" value="${rough}"
                  oninput="syncExpSlider(${exp.id}, 'rough_record', this.value, 5)"
                  id="exp_${exp.id}_rough_record"
                  class="no-spinner w-14 bg-slate-950 border border-slate-800 rounded px-1.5 py-0.5 text-center font-bold text-amber-400 text-xs focus:border-amber-500 outline-none" placeholder="0.0">
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'rough_record', -0.5, 5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-amber-300 font-bold text-xs flex items-center justify-center shrink-0">-</button>
                  <input type="range" min="0" max="5" step="0.5" value="${rNum || 0}"
                    oninput="document.getElementById('exp_${exp.id}_rough_record').value = this.value; syncExpSlider(${exp.id}, 'rough_record', this.value, 5)"
                    id="exp_${exp.id}_rough_record_slider"
                    class="flex-1 h-1.5 rounded-full accent-amber-500 bg-slate-800 cursor-pointer">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'rough_record', 0.5, 5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-amber-300 font-bold text-xs flex items-center justify-center shrink-0">+</button>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500 font-semibold">
                  <span>0</span><span>Max 5</span>
                </div>
              </div>
            </div>

            <!-- Fair Record (7.5) -->
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2 flex flex-col justify-between">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-emerald-300">Fair Record</span>
                <input type="number" step="0.5" min="0" max="7.5" value="${fair}"
                  oninput="syncExpSlider(${exp.id}, 'fair_record', this.value, 7.5)"
                  id="exp_${exp.id}_fair_record"
                  class="no-spinner w-14 bg-slate-950 border border-slate-800 rounded px-1.5 py-0.5 text-center font-bold text-emerald-400 text-xs focus:border-emerald-500 outline-none" placeholder="0.0">
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'fair_record', -0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-emerald-300 font-bold text-xs flex items-center justify-center shrink-0">-</button>
                  <input type="range" min="0" max="7.5" step="0.5" value="${fNum || 0}"
                    oninput="document.getElementById('exp_${exp.id}_fair_record').value = this.value; syncExpSlider(${exp.id}, 'fair_record', this.value, 7.5)"
                    id="exp_${exp.id}_fair_record_slider"
                    class="flex-1 h-1.5 rounded-full accent-emerald-500 bg-slate-800 cursor-pointer">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'fair_record', 0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-emerald-300 font-bold text-xs flex items-center justify-center shrink-0">+</button>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500 font-semibold">
                  <span>0</span><span>Max 7.5</span>
                </div>
              </div>
            </div>

            <!-- Obs & Recording (7.5) -->
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2 flex flex-col justify-between">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-sky-300">Obs. &amp; Recording</span>
                <input type="number" step="0.5" min="0" max="7.5" value="${prereq}"
                  oninput="syncExpSlider(${exp.id}, 'prerequisite', this.value, 7.5)"
                  id="exp_${exp.id}_prerequisite"
                  class="no-spinner w-14 bg-slate-950 border border-slate-800 rounded px-1.5 py-0.5 text-center font-bold text-sky-400 text-xs focus:border-sky-500 outline-none" placeholder="0.0">
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'prerequisite', -0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-sky-300 font-bold text-xs flex items-center justify-center shrink-0">-</button>
                  <input type="range" min="0" max="7.5" step="0.5" value="${pNum || 0}"
                    oninput="document.getElementById('exp_${exp.id}_prerequisite').value = this.value; syncExpSlider(${exp.id}, 'prerequisite', this.value, 7.5)"
                    id="exp_${exp.id}_prerequisite_slider"
                    class="flex-1 h-1.5 rounded-full accent-sky-500 bg-slate-800 cursor-pointer">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'prerequisite', 0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-sky-300 font-bold text-xs flex items-center justify-center shrink-0">+</button>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500 font-semibold">
                  <span>0</span><span>Max 7.5</span>
                </div>
              </div>
            </div>

            <!-- Proc & Punctuality (7.5) -->
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2 flex flex-col justify-between">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-purple-300">Proc. &amp; Punct</span>
                <input type="number" step="0.5" min="0" max="7.5" value="${exec}"
                  oninput="syncExpSlider(${exp.id}, 'execution', this.value, 7.5)"
                  id="exp_${exp.id}_execution"
                  class="no-spinner w-14 bg-slate-950 border border-slate-800 rounded px-1.5 py-0.5 text-center font-bold text-purple-400 text-xs focus:border-purple-500 outline-none" placeholder="0.0">
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'execution', -0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-purple-300 font-bold text-xs flex items-center justify-center shrink-0">-</button>
                  <input type="range" min="0" max="7.5" step="0.5" value="${eNum || 0}"
                    oninput="document.getElementById('exp_${exp.id}_execution').value = this.value; syncExpSlider(${exp.id}, 'execution', this.value, 7.5)"
                    id="exp_${exp.id}_execution_slider"
                    class="flex-1 h-1.5 rounded-full accent-purple-500 bg-slate-800 cursor-pointer">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'execution', 0.5, 7.5)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-purple-300 font-bold text-xs flex items-center justify-center shrink-0">+</button>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500 font-semibold">
                  <span>0</span><span>Max 7.5</span>
                </div>
              </div>
            </div>

            <!-- Viva Voce (10) -->
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2 flex flex-col justify-between">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-rose-300">Viva Voce</span>
                <input type="number" step="0.5" min="0" max="10" value="${viva}"
                  oninput="syncExpSlider(${exp.id}, 'viva_voce', this.value, 10)"
                  id="exp_${exp.id}_viva_voce"
                  class="no-spinner w-14 bg-slate-950 border border-slate-800 rounded px-1.5 py-0.5 text-center font-bold text-rose-400 text-xs focus:border-rose-500 outline-none" placeholder="0.0">
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'viva_voce', -0.5, 10)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-rose-300 font-bold text-xs flex items-center justify-center shrink-0">-</button>
                  <input type="range" min="0" max="10" step="0.5" value="${vNum || 0}"
                    oninput="document.getElementById('exp_${exp.id}_viva_voce').value = this.value; syncExpSlider(${exp.id}, 'viva_voce', this.value, 10)"
                    id="exp_${exp.id}_viva_voce_slider"
                    class="flex-1 h-1.5 rounded-full accent-rose-500 bg-slate-800 cursor-pointer">
                  <button type="button" onclick="adjustExpMark(${exp.id}, 'viva_voce', 0.5, 10)" class="w-6 h-6 rounded bg-slate-800 hover:bg-slate-700 text-rose-300 font-bold text-xs flex items-center justify-center shrink-0">+</button>
                </div>
                <div class="flex justify-between text-[10px] text-slate-500 font-semibold">
                  <span>0</span><span>Max 10</span>
                </div>
              </div>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function syncExpSlider(expId, key, val, max) {
      const slider = document.getElementById(`exp_${expId}_${key}_slider`);
      if (slider) slider.value = val || 0;
      if (!tempStudentExpMarks[expId]) tempStudentExpMarks[expId] = {};
      tempStudentExpMarks[expId][key] = val;

      // Update card total badge
      const valObj = tempStudentExpMarks[expId];
      const pNum = parseFloat(valObj.prerequisite || valObj.obs_prep);
      const eNum = parseFloat(valObj.execution || valObj.proc_punct);
      const rNum = parseFloat(valObj.rough_record);
      const fNum = parseFloat(valObj.fair_record);

      const isGraded = !isNaN(pNum) || !isNaN(eNum) || !isNaN(rNum) || !isNaN(fNum) || !isNaN(parseFloat(valObj.viva_voce));
      const badge = document.getElementById(`exp_total_badge_${expId}`);
      if (badge) {
        if (isGraded) {
          const vNum = parseFloat(valObj.viva_voce) || 0;
          const tot = (isNaN(pNum)?0:pNum) + (isNaN(eNum)?0:eNum) + (isNaN(rNum)?0:rNum) + (isNaN(fNum)?0:fNum) + vNum;
          badge.className = "text-xs font-mono px-2.5 py-0.5 rounded border bg-emerald-500/10 border-emerald-500/30 text-emerald-400 font-bold";
          badge.innerText = `${tot.toFixed(1)} / 37.5`;
        } else {
          badge.className = "text-xs font-mono px-2.5 py-0.5 rounded border bg-slate-900 border-slate-800 text-slate-500 italic";
          badge.innerText = 'Not Graded';
        }
      }
      calcLabModalScores();
    }

    function jumpToExpCard(cardId) {
      if (!cardId) return;
      const card = document.getElementById(cardId);
      if (card) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function saveStudentLabEvaluation() {
      const regNo = gradingStudentReg;
      if (!regNo) return;

      const projectTopic = document.getElementById('labScore_projectTopic').value;
      const projectMark = document.getElementById('labScore_projectMark').value;
      const attMark = document.getElementById('labScore_attendanceMark').value;
      const boardExamMark = document.getElementById('labScore_boardExam').value;

      // Tests
      const tests = {
        'Test 1': {
          'CO1': document.getElementById('labScore_t1_co1').value,
          'CO2': document.getElementById('labScore_t1_co2').value
        },
        'Test 2': {
          'CO3': document.getElementById('labScore_t2_co3').value,
          'CO4': document.getElementById('labScore_t2_co4').value
        }
      };

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          reg_no: regNo,
          open_ended_project_topic: projectTopic,
          micro_project: projectMark,
          attendance_marks: attMark,
          board_exam_marks: boardExamMark,
          tests,
          experiments: tempStudentExpMarks
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Evaluation saved successfully.');
          closeStudentLabModal();
          fetchPracticalEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save student evaluation.'));
    }

    // Manage Experiments Modal Controllers
    function openManageExperimentsModal(e) {
      if (e) e.preventDefault();
      if (!currentSubjectId) {
        const urlParams = new URLSearchParams(window.location.search);
        currentSubjectId = urlParams.get('subject_id') || window.activeSubjectId || window.currentSubjectId || (document.getElementById('selectedSubjectId') ? document.getElementById('selectedSubjectId').value : null);
      }
      if (!currentSubjectId) {
        alert('Subject context not found. Please select a course batch first.');
        return;
      }

      // Check if databank has previous data
      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/databank`)
      .then(res => res.json())
      .then(res => {
        const importBtn = document.getElementById('btnImportDatabank');
        if (importBtn) {
          if (res.status === 'SUCCESS' && res.has_data) {
            importBtn.classList.remove('hidden');
          } else {
            importBtn.classList.add('hidden');
          }
        }
      })
      .catch(err => console.error(err));

      renderManageExperimentsList();

      const modal = document.getElementById('manageExperimentsModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.zIndex = '99999';
      }
    }
    window.openManageExperimentsModal = openManageExperimentsModal;

    function closeManageExperimentsModal() {
      const modal = document.getElementById('manageExperimentsModal');
      if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        modal.style.setProperty('display', 'none', 'important');
      }
    }
    window.closeManageExperimentsModal = closeManageExperimentsModal;

    function renderManageExperimentsList() {
      const tbody = document.getElementById('manageExpsTableBody');
      if (!tbody) return;
      tbody.innerHTML = '';

      if (labExperimentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="p-6 text-center text-slate-500 font-bold">
              No experiments set up yet. Create experiments using the form above.
            </td>
          </tr>
        `;
        return;
      }

      labExperimentsData.forEach(exp => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/10";
        tr.innerHTML = `
          <td class="p-3 text-center font-bold text-slate-455 font-mono">${exp.experiment_no}</td>
          <td class="p-3 text-slate-200 font-medium text-sm whitespace-pre-wrap leading-relaxed">${exp.title}</td>
          <td class="p-3 text-center font-bold text-blue-400">${exp.co_tag}</td>
          <td class="p-3 text-center whitespace-nowrap space-x-2">
            <button type="button" onclick="editExperiment(${exp.id})" class="px-2.5 py-1 bg-slate-800 text-slate-300 hover:text-white rounded font-bold cursor-pointer">Edit</button>
            <button type="button" onclick="deleteExperiment(${exp.id})" class="px-2.5 py-1 bg-red-950/40 text-red-400 hover:text-red-300 rounded font-bold cursor-pointer border border-red-900/30">Delete</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function savePracticalExperiment(event) {
      event.preventDefault();
      const expId = document.getElementById('expEditId').value;
      const no = document.getElementById('expFormNo').value;
      const title = document.getElementById('expFormTitle').value;
      const co = document.getElementById('expFormCo').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: expId, experiment_no: no, title: title, co_tag: co })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          // Reset form
          document.getElementById('expEditId').value = '';
          document.getElementById('expFormNo').value = '';
          document.getElementById('expFormTitle').value = '';
          document.getElementById('btnSaveExp').innerHTML = '<span class="material-symbols-rounded text-sm">add</span> Add Experiment';

          alert("Experiment successfully saved!");
          fetchPracticalEvaluations();
          setTimeout(() => renderManageExperimentsList(), 300);
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save experiment.'));
    }

    function editExperiment(id) {
      const exp = (labExperimentsData || []).find(e => e.id === id);
      if (!exp) return;
      document.getElementById('expEditId').value = exp.id;
      document.getElementById('expFormNo').value = exp.experiment_no || '';
      document.getElementById('expFormTitle').value = exp.title || '';
      document.getElementById('expFormCo').value = exp.co_tag || 'CO1';
      document.getElementById('btnSaveExp').innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Update';
    }

    function deleteExperiment(id) {
      if (!confirm('Are you sure you want to delete this experiment? All graded marks for this experiment will be permanently deleted!')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      });
    }

    function importFromDatabank() {
      if (!confirm('This will import the standard list of experiments configured for this subject code. Existing student grades for existing matching experiment numbers will not be modified. Proceed?')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/import`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      })
      .catch(() => alert('Import failed.'));
    }

    // Manage Tests modal
    function openManageTestsModal(e) {
      if (e) {
        try { e.preventDefault(); e.stopPropagation(); } catch(err) {}
      }

      console.log('openManageTestsModal triggered');

      // 1. Ensure modal HTML exists in DOM
      let modal = document.getElementById('manageTestsModal');
      if (!modal) {
        if (typeof dynamicLabModalsHtml !== 'undefined' && dynamicLabModalsHtml) {
          document.body.insertAdjacentHTML('beforeend', dynamicLabModalsHtml);
          modal = document.getElementById('manageTestsModal');
        }
      }

      // 2. Display modal immediately with enforced CSS
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.zIndex = '99999';
      } else {
        alert('Error: Test configuration modal container could not be initialized.');
        return;
      }

      // 3. Populate test dropdown & fields safely
      try {
        const testSelect = document.getElementById('designTestName');
        if (testSelect) testSelect.value = 'Test 1';
        renderTestQuestionsFields();
      } catch (err) {
        console.error('Error rendering test fields:', err);
      }

      // 4. Resolve currentSubjectId safely in background
      let subjId = currentSubjectId || window.currentSubjectId || window.activeSubjectId;
      if (!subjId) {
        const urlParams = new URLSearchParams(window.location.search);
        subjId = urlParams.get('subject_id') || (document.getElementById('selectedSubjectId') ? document.getElementById('selectedSubjectId').value : null);
      }
      if (subjId) {
        currentSubjectId = subjId;
        window.currentSubjectId = subjId;

        if (!labTestsData || labTestsData.length === 0) {
          fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
          .then(r => r.json())
          .then(res => {
            if (res && res.status === 'SUCCESS') {
              labTestsData = res.tests || [];
              renderTestQuestionsFields();
            }
          })
        }
      }
    }
    window.openManageTestsModal = openManageTestsModal;

    function closeManageTestsModal() {
      const modal = document.getElementById('manageTestsModal');
      if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        modal.style.setProperty('display', 'none', 'important');
      }
    }
    window.closeManageTestsModal = closeManageTestsModal;

    function formatCognitiveLevel(bt) {
      if (!bt) return 'Apply (A)';
      const s = String(bt).trim();
      if (s === 'K1' || s === 'R' || s.toLowerCase().includes('remember')) return 'Remember (R)';
      if (s === 'K2' || s === 'U' || s.toLowerCase().includes('understand')) return 'Understand (U)';
      return 'Apply (A)';
    }

    function renderTestQuestionsFields() {
      const testSelect = document.getElementById('designTestName');
      const activeTestDesign = testSelect ? testSelect.value : 'Test 1';
      const container = document.getElementById('testQuestionsFieldsContainer');
      if (!container) return;
      container.innerHTML = '';

      const test = (labTestsData || []).find(t => t.test_name === activeTestDesign);
      const existingQ = test ? test.questions : {};

      const defaultCos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      defaultCos.forEach((defaultCo, coIndex) => {
        const coData = existingQ[defaultCo] || existingQ[`CO${coIndex+1}`] || [];
        
        let qA = typeof coData[0] === 'object' ? coData[0] : { text: coData[0] || '', co: defaultCo, bt_level: 'Apply (A)', rubrics: '' };
        let qB = typeof coData[1] === 'object' ? coData[1] : { text: coData[1] || '', co: defaultCo, bt_level: 'Apply (A)', rubrics: '' };

        const card = document.createElement('div');
        card.className = "bg-slate-950/60 border border-slate-800 p-5 rounded-2xl space-y-4 shadow-inner";
        card.innerHTML = `
          <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-lg text-xs font-black uppercase">Module / ${defaultCo}</span>
              <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Choice of 1 out of 2 Questions (7.5 Marks)</h4>
            </div>
            <span class="text-[11px] font-bold text-slate-400 bg-slate-900 border border-slate-800 px-2.5 py-1 rounded-md">Max 7.5 Marks</span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- OPTION A -->
            <div class="bg-slate-900/80 border border-slate-800/80 p-4 rounded-xl space-y-3">
              <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                <span class="text-xs font-black text-sky-400 uppercase tracking-wide">Question Option A (7.5 Marks)</span>
                <div class="flex items-center gap-2">
                  <select name="co_${defaultCo}_0" class="bg-slate-950 border border-slate-800 text-sky-300 font-bold text-[11px] rounded px-2 py-1 outline-none cursor-pointer">
                    <option value="CO1" ${qA.co === 'CO1' ? 'selected' : ''}>CO1</option>
                    <option value="CO2" ${qA.co === 'CO2' ? 'selected' : ''}>CO2</option>
                    <option value="CO3" ${qA.co === 'CO3' ? 'selected' : ''}>CO3</option>
                    <option value="CO4" ${qA.co === 'CO4' ? 'selected' : ''}>CO4</option>
                    <option value="CO5" ${qA.co === 'CO5' ? 'selected' : ''}>CO5</option>
                  </select>
                  <select name="bt_${defaultCo}_0" class="bg-slate-950 border border-slate-800 text-purple-300 font-bold text-[11px] rounded px-2 py-1 outline-none cursor-pointer">
                    <option value="Remember (R)" ${formatCognitiveLevel(qA.bt_level) === 'Remember (R)' ? 'selected' : ''}>Remember (R)</option>
                    <option value="Understand (U)" ${formatCognitiveLevel(qA.bt_level) === 'Understand (U)' ? 'selected' : ''}>Understand (U)</option>
                    <option value="Apply (A)" ${formatCognitiveLevel(qA.bt_level) === 'Apply (A)' ? 'selected' : ''}>Apply (A)</option>
                  </select>
                </div>
              </div>
              <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Question Description</label>
                <textarea name="q_${defaultCo}_0" placeholder="Type-in detailed practical question statement..." rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-slate-100 text-xs font-medium outline-none focus:border-blue-500 transition-premium resize-y">${qA.text || ''}</textarea>
              </div>
              <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Scheme of Evaluation &amp; Rubrics</label>
                <textarea name="rub_${defaultCo}_0" placeholder="e.g., Circuit/Algorithm: 2.5M, Execution: 3.5M, Result & Viva: 1.5M..." rows="2" class="w-full bg-slate-950/70 border border-slate-800/80 rounded-lg p-2.5 text-slate-300 text-xs font-mono outline-none focus:border-purple-500 transition-premium resize-y">${qA.rubrics || ''}</textarea>
              </div>
            </div>

            <!-- OPTION B -->
            <div class="bg-slate-900/80 border border-slate-800/80 p-4 rounded-xl space-y-3">
              <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                <span class="text-xs font-black text-amber-400 uppercase tracking-wide">Question Option B (OR) (7.5 Marks)</span>
                <div class="flex items-center gap-2">
                  <select name="co_${defaultCo}_1" class="bg-slate-950 border border-slate-800 text-amber-300 font-bold text-[11px] rounded px-2 py-1 outline-none cursor-pointer">
                    <option value="CO1" ${qB.co === 'CO1' ? 'selected' : ''}>CO1</option>
                    <option value="CO2" ${qB.co === 'CO2' ? 'selected' : ''}>CO2</option>
                    <option value="CO3" ${qB.co === 'CO3' ? 'selected' : ''}>CO3</option>
                    <option value="CO4" ${qB.co === 'CO4' ? 'selected' : ''}>CO4</option>
                    <option value="CO5" ${qB.co === 'CO5' ? 'selected' : ''}>CO5</option>
                  </select>
                  <select name="bt_${defaultCo}_1" class="bg-slate-950 border border-slate-800 text-purple-300 font-bold text-[11px] rounded px-2 py-1 outline-none cursor-pointer">
                    <option value="Remember (R)" ${formatCognitiveLevel(qB.bt_level) === 'Remember (R)' ? 'selected' : ''}>Remember (R)</option>
                    <option value="Understand (U)" ${formatCognitiveLevel(qB.bt_level) === 'Understand (U)' ? 'selected' : ''}>Understand (U)</option>
                    <option value="Apply (A)" ${formatCognitiveLevel(qB.bt_level) === 'Apply (A)' ? 'selected' : ''}>Apply (A)</option>
                  </select>
                </div>
              </div>
              <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Question Description</label>
                <textarea name="q_${defaultCo}_1" placeholder="Type-in alternative practical question statement..." rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-3 text-slate-100 text-xs font-medium outline-none focus:border-blue-500 transition-premium resize-y">${qB.text || ''}</textarea>
              </div>
              <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Scheme of Evaluation &amp; Rubrics</label>
                <textarea name="rub_${defaultCo}_1" placeholder="e.g., Program / Design: 2.5M, Execution: 3.5M, Viva: 1.5M..." rows="2" class="w-full bg-slate-950/70 border border-slate-800/80 rounded-lg p-2.5 text-slate-300 text-xs font-mono outline-none focus:border-purple-500 transition-premium resize-y">${qB.rubrics || ''}</textarea>
              </div>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function savePracticalTestQuestions(event) {
      event.preventDefault();
      let subjId = currentSubjectId || window.currentSubjectId || window.activeSubjectId;
      if (!subjId) {
        const urlParams = new URLSearchParams(window.location.search);
        subjId = urlParams.get('subject_id') || (document.getElementById('selectedSubjectId') ? document.getElementById('selectedSubjectId').value : null);
      }
      if (subjId) {
        currentSubjectId = subjId;
        window.currentSubjectId = subjId;
      } else {
        alert('Subject context not found. Please select a course batch first.');
        return;
      }

      const testName = document.getElementById('designTestName').value;
      const cos = testName === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      const questions = {};
      cos.forEach(co => {
        const q0Text = document.querySelector(`textarea[name="q_${co}_0"]`).value;
        const q0Co = document.querySelector(`select[name="co_${co}_0"]`).value;
        const q0Bt = document.querySelector(`select[name="bt_${co}_0"]`).value;
        const q0Rub = document.querySelector(`textarea[name="rub_${co}_0"]`).value;

        const q1Text = document.querySelector(`textarea[name="q_${co}_1"]`).value;
        const q1Co = document.querySelector(`select[name="co_${co}_1"]`).value;
        const q1Bt = document.querySelector(`select[name="bt_${co}_1"]`).value;
        const q1Rub = document.querySelector(`textarea[name="rub_${co}_1"]`).value;

        questions[co] = [
          { text: q0Text, co: q0Co, bt_level: q0Bt, rubrics: q0Rub },
          { text: q1Text, co: q1Co, bt_level: q1Bt, rubrics: q1Rub }
        ];
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/tests/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ test_name: testName, questions: questions })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Practical test scheme and questions saved successfully!');
          labTestsData = res.tests || [];
          closeManageTestsModal();
        } else {
          alert(res.message || 'Failed to save test questions.');
        }
      })
      .catch(() => alert('Network error while saving test scheme.'));
    }

    function printPracticalTestPaper() {
      const activeTestDesign = document.getElementById('designTestName').value;
      const test = (labTestsData || []).find(t => t.test_name === activeTestDesign);
      const questions = test ? test.questions : {};

      const testTitle = activeTestDesign === 'Test 1' ? 'SERIES PRACTICAL EXAMINATION 1 (REVISION 2021)' : 'SERIES PRACTICAL EXAMINATION 2 (REVISION 2021)';
      const cos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      let printWin = window.open('', '_blank');
      let paperHtml = `
        <!DOCTYPE html>
        <html>
        <head>
          <title>${testTitle} - Question Paper</title>
          <style>
            @page { size: A4 portrait; margin: 15mm 15mm 15mm 15mm; }
            body { font-family: 'Times New Roman', Times, serif; color: #000; margin: 0; padding: 0; font-size: 11pt; line-height: 1.4; }
            .header-table { width: 100%; border-collapse: collapse; text-align: center; margin-bottom: 12px; }
            .header-table td { font-size: 10.5pt; padding: 2px; }
            .college-name { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
            .exam-title { font-size: 12pt; font-weight: bold; margin: 8px 0 6px; text-align: center; text-transform: uppercase; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; }
            .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10.5pt; font-weight: bold; }
            .meta-table td { padding: 3px 0; }
            .question-box { margin-bottom: 18px; page-break-inside: avoid; }
            .co-header { font-weight: bold; font-size: 11pt; margin-bottom: 8px; border-bottom: 1px dashed #444; padding-bottom: 3px; display: flex; justify-content: space-between; }
            .q-item { margin-bottom: 10px; font-size: 11pt; display: flex; align-items: flex-start; }
            .q-num { width: 65px; font-weight: bold; flex-shrink: 0; }
            .q-text { flex-grow: 1; text-align: justify; padding-right: 15px; white-space: pre-wrap; }
            .q-meta { font-weight: bold; text-align: right; width: 130px; flex-shrink: 0; font-size: 10pt; }
            .or-divider { text-align: center; font-weight: bold; margin: 6px 0; font-size: 10.5pt; text-transform: uppercase; letter-spacing: 1px; }
            @media print { .no-print { display: none !important; } body { padding: 0; } }
          </style>
        </head>
        <body>
          <div class="no-print" style="margin-bottom:15px; text-align:right;">
            <button onclick="window.print()" style="padding:8px 16px; background:#2563eb; color:#fff; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Print Question Paper</button>
          </div>
          <table class="header-table">
            <tr><td class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</td></tr>
            <tr><td style="font-weight:bold; font-size:10.5pt;">DEPARTMENT OF TECHNICAL EDUCATION / SBTE REVISION 2021</td></tr>
          </table>
          <div class="exam-title">${testTitle}</div>
          <table class="meta-table">
            <tr>
              <td>Course: Practical Virtual Lab Workspace</td>
              <td style="text-align:right;">Max. Marks: 15 Marks</td>
            </tr>
            <tr>
              <td>Duration: 1 Hour</td>
              <td style="text-align:right;">Curriculum: R-2021</td>
            </tr>
          </table>
          <p style="font-size:10pt; font-style:italic; margin-bottom:12px; text-align:center; border-bottom:1px solid #ccc; padding-bottom:6px;">
            Instruction: Answer <b>ONE</b> full question from each Course Outcome section. Each Question carries 7.5 Marks.
          </p>
      `;

      cos.forEach((coKey, idx) => {
        const coData = questions[coKey] || [];
        const qA = typeof coData[0] === 'object' ? coData[0] : { text: coData[0] || '', co: coKey, bt_level: 'Apply (A)', rubrics: '' };
        const qB = typeof coData[1] === 'object' ? coData[1] : { text: coData[1] || '', co: coKey, bt_level: 'Apply (A)', rubrics: '' };

        const qNumA = `Q${idx + 1}(A)`;
        const qNumB = `Q${idx + 1}(B)`;

        paperHtml += `
          <div class="question-box">
            <div class="co-header">
              <span>MODULE / ${qA.co || coKey} SECTION</span>
              <span style="font-size:10pt; font-weight:normal;">[ Max Marks: 7.5 ]</span>
            </div>
            <div class="q-item">
              <div class="q-num">${qNumA}</div>
              <div class="q-text">${qA.text || 'Question statement not configured.'}</div>
              <div class="q-meta">[ 7.5M | ${formatCognitiveLevel(qA.bt_level)} ]</div>
            </div>
            <div class="or-divider">— OR —</div>
            <div class="q-item">
              <div class="q-num">${qNumB}</div>
              <div class="q-text">${qB.text || 'Question statement not configured.'}</div>
              <div class="q-meta">[ 7.5M | ${formatCognitiveLevel(qB.bt_level)} ]</div>
            </div>
          </div>
        `;
      });

      paperHtml += `
        </body>
        </html>
      `;

      printWin.document.write(paperHtml);
      printWin.document.close();
    }

    function printPracticalTestScheme() {
      const activeTestDesign = document.getElementById('designTestName').value;
      const test = (labTestsData || []).find(t => t.test_name === activeTestDesign);
      const questions = test ? test.questions : {};

      const testTitle = activeTestDesign === 'Test 1' ? 'SCHEME OF EVALUATION: SERIES EXAM 1 (R-2021)' : 'SCHEME OF EVALUATION: SERIES EXAM 2 (R-2021)';
      const cos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      let printWin = window.open('', '_blank');
      let schemeHtml = `
        <!DOCTYPE html>
        <html>
        <head>
          <title>${testTitle}</title>
          <style>
            @page { size: A4 portrait; margin: 15mm 15mm 15mm 15mm; }
            body { font-family: 'Times New Roman', Times, serif; color: #000; margin: 0; padding: 0; font-size: 10pt; line-height: 1.4; }
            .college-header { text-align: center; font-weight: bold; font-size: 13pt; text-transform: uppercase; margin-bottom: 2px; }
            .sub-header { text-align: center; font-weight: bold; font-size: 10.5pt; color: #1e293b; margin-bottom: 8px; }
            .title-banner { background: #f1f5f9; border: 1px solid #94a3b8; padding: 6px; text-align: center; font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-bottom: 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10pt; }
            th, td { border: 1px solid #1e293b; padding: 6px 8px; vertical-align: top; }
            th { background: #e2e8f0; font-weight: bold; text-transform: uppercase; font-size: 9.5pt; text-align: center; }
            .rubric-text { white-space: pre-wrap; font-family: 'Courier New', Courier, monospace; font-size: 9.5pt; color: #0f172a; }
            @media print { .no-print { display: none !important; } body { padding: 0; } }
          </style>
        </head>
        <body>
          <div class="no-print" style="margin-bottom:15px; text-align:right;">
            <button onclick="window.print()" style="padding:8px 16px; background:#7c3aed; color:#fff; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Print Scheme & Rubrics</button>
          </div>
          <div class="college-header">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</div>
          <div class="sub-header">INTERNAL PRACTICAL EXAMINATION AUDIT / SCHEME OF EVALUATION</div>
          <div class="title-banner">${testTitle} (MAX MARKS: 15 MARKS)</div>
          
          <table>
            <thead>
              <tr>
                <th style="width: 65px;">Q. No.</th>
                <th style="width: 45px;">CO</th>
                <th style="width: 100px;">Level</th>
                <th>Question Description</th>
                <th style="width: 60px;">Marks</th>
                <th>Scheme of Evaluation & Detailed Rubrics</th>
              </tr>
            </thead>
            <tbody>
      `;

      cos.forEach((coKey, idx) => {
        const coData = questions[coKey] || [];
        const qA = typeof coData[0] === 'object' ? coData[0] : { text: coData[0] || '', co: coKey, bt_level: 'Apply (A)', rubrics: '' };
        const qB = typeof coData[1] === 'object' ? coData[1] : { text: coData[1] || '', co: coKey, bt_level: 'Apply (A)', rubrics: '' };

        schemeHtml += `
          <tr>
            <td style="font-weight:bold; text-align:center;">Q${idx + 1} (A)</td>
            <td style="text-align:center; font-weight:bold;">${qA.co || coKey}</td>
            <td style="text-align:center; font-weight:bold; color:#1d4ed8;">${formatCognitiveLevel(qA.bt_level)}</td>
            <td>${qA.text || 'Question statement not set.'}</td>
            <td style="text-align:center; font-weight:bold;">7.5 M</td>
            <td class="rubric-text">${qA.rubrics || 'Circuit / Algorithm: 2.5M\nSetup & Execution: 3.5M\nOutput & Viva: 1.5M'}</td>
          </tr>
          <tr>
            <td style="font-weight:bold; text-align:center; color:#6d28d9;">Q${idx + 1} (B) [OR]</td>
            <td style="text-align:center; font-weight:bold;">${qB.co || coKey}</td>
            <td style="text-align:center; font-weight:bold; color:#1d4ed8;">${formatCognitiveLevel(qB.bt_level)}</td>
            <td>${qB.text || 'Question statement not set.'}</td>
            <td style="text-align:center; font-weight:bold;">7.5 M</td>
            <td class="rubric-text">${qB.rubrics || 'Program / Design: 2.5M\nExecution & Results: 3.5M\nViva Voce: 1.5M'}</td>
          </tr>
        `;
      });

      schemeHtml += `
            </tbody>
          </table>
        </body>
        </html>
      `;

      printWin.document.write(schemeHtml);
      printWin.document.close();
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function openGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
      }
    }

    function generatePlannerFromExperiments(event) {
      event.preventDefault();
      const session_type = document.getElementById('genPlannerBatchMode').value;
      const target_batch = document.getElementById('genPlannerTargetBatch') ? document.getElementById('genPlannerTargetBatch').value : 'Full';
      const allocated_hours = document.getElementById('genPlannerHours').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/lesson-plans/generate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ session_type, target_batch, allocated_hours })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        if (res.status === 'SUCCESS') {
          closeGeneratePlannerModal();
          loadCourseDetails(currentSubjectId);
        }
      })
      .catch(() => alert('Failed to generate lesson planner.'));
    }

    // CO-PO Matrix
    function saveTheoryCoPoMapping(e) {
      const inputs = document.querySelectorAll('.theory-copo-input');
      if (!inputs.length) return;

      const mapping = {};

      inputs.forEach(input => {
        const co = input.getAttribute('data-co');
        const target = input.getAttribute('data-target');
        const val = input.value ? parseInt(input.value) : null;

        if (!mapping[co]) {
          mapping[co] = {};
        }
        mapping[co][target] = val;
      });

      const btn = e ? e.currentTarget : null;
      const origHtml = btn ? btn.innerHTML : '';
      if (btn) {
        btn.innerHTML = `<span class="material-symbols-rounded text-sm animate-spin">sync</span> Saving...`;
        btn.disabled = true;
      }

      fetch(`/api/classroom/${currentSubjectId}/copo-mapping/save`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ copo_mapping: mapping })
      })
      .then(res => res.json())
      .then(res => {
        if (btn) {
          btn.innerHTML = origHtml;
          btn.disabled = false;
        }
        if (res.status === 'SUCCESS') {
          alert('CO-PO & PSO Mapping Matrix saved successfully!');
        } else {
          alert(res.message || 'Failed to save matrix.');
        }
      })
      .catch(() => {
        if (btn) {
          btn.innerHTML = origHtml;
          btn.disabled = false;
        }
        alert('Network error while saving matrix.');
      });
    }

    function fetchPracticalCoPoMapping() {
      const tbody = document.getElementById('labCoPoMappingTbody');
      if (!tbody) return;
      tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading Articulation Matrix...</td></tr>';

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          tbody.innerHTML = '';
          const matrix = res.mapping || {};
          const descriptions = {
            'CO1': 'Formulate solutions for laboratory tasks using theoretical principles and prerequisites.',
            'CO2': 'Conduct structured experiments, verify outputs, and log observations accurately.',
            'CO3': 'Analyze experimental results, troubleshoot errors, and draw logical conclusions.',
            'CO4': 'Demonstrate open-ended problem solving ability and technical documentation skills.'
          };

          ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-800/40 hover:bg-slate-900/10 text-slate-300 font-medium";
            
            let cells = `<td class="p-3.5 font-black text-blue-400 whitespace-nowrap text-base md:text-lg align-middle">${co}</td>`;
            cells += `<td class="p-3.5 text-slate-100 leading-relaxed font-bold text-base md:text-lg align-middle">${descriptions[co]}</td>`;

            // PO1 to PO11 inputs
            for (let i = 1; i <= 11; i++) {
              const val = matrix[co] && matrix[co]['PO' + i] ? matrix[co]['PO' + i] : '';
              cells += `<td class="p-1"><input type="text" maxlength="1" value="${val}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-9 h-8 bg-slate-900 border border-slate-800 rounded px-1 text-center font-bold text-emerald-450 focus:border-blue-500 outline-none text-xs" data-co="${co}" data-target="PO${i}"></td>`;
            }

            // PSO1 to PSO3 inputs
            for (let i = 1; i <= 3; i++) {
              const val = matrix[co] && matrix[co]['PSO' + i] ? matrix[co]['PSO' + i] : '';
              cells += `<td class="p-1"><input type="text" maxlength="1" value="${val}" oninput="this.value=this.value.replace(/[^1-3]/g,'')" class="w-9 h-8 bg-slate-900 border border-slate-800 rounded px-1 text-center font-bold text-blue-405 focus:border-blue-500 outline-none text-xs" data-co="${co}" data-target="PSO${i}"></td>`;
            }

            tr.innerHTML = cells;
            tbody.appendChild(tr);
          });
        }
      })
      .catch(() => {
        if (tbody) tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load articulation matrix.</td></tr>';
      });
    }

    function saveCoPoMappingMatrix() {
      const inputs = document.querySelectorAll('#labCoPoMappingTbody input[data-co]');
      const mapping = {
        'CO1': {}, 'CO2': {}, 'CO3': {}, 'CO4': {}
      };

      inputs.forEach(input => {
        const co = input.getAttribute('data-co');
        const target = input.getAttribute('data-target');
        const val = input.value ? parseInt(input.value) : null;
        if (val) {
          mapping[co][target] = val;
        }
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ mapping })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
      })
      .catch(() => alert('Failed to save mapping matrix.'));
    }

    function openEseMarksModal() {
      if (!currentSubjectId) {
        alert("Please select a subject first.");
        return;
      }
      const modal = document.getElementById('modalEseMarks');
      if (modal) modal.classList.remove('hidden');

      const tbody = document.getElementById('eseMarksTableBody');
      tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-bold">Loading student records...</td></tr>';

      fetch(`/api/r26/classroom/${currentSubjectId}/ese-marks`)
        .then(res => res.json())
        .then(data => {
          if (data.status !== 'SUCCESS') {
            tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-rose-400 font-bold">Failed to load ESE records.</td></tr>';
            return;
          }

          const cfg = data.config || {};
          document.getElementById('eseEntryMode').value = cfg.entry_mode || 'grades';
          document.getElementById('eseMaxMarks').value = cfg.max_marks || 60;
          document.getElementById('eseThresholdPercent').value = cfg.ese_threshold_percent || cfg.target_threshold_percent || 50;
          document.getElementById('eseThresholdGrade').value = cfg.ese_threshold_grade || cfg.target_grade || 'D';
          document.getElementById('cieThresholdPercent').value = cfg.cie_threshold_percent || 50;
          const targetVal = cfg.target_student_percent || cfg.level3_percent || 70;
          document.getElementById('targetStudentPercent').value = targetVal;
          if (document.getElementById('inputLevel3Percent')) document.getElementById('inputLevel3Percent').value = cfg.level3_percent || targetVal;
          if (document.getElementById('inputLevel2Percent')) document.getElementById('inputLevel2Percent').value = cfg.level2_percent || Math.max(0, targetVal - 10);
          if (document.getElementById('inputLevel1Percent')) document.getElementById('inputLevel1Percent').value = cfg.level1_percent || Math.max(0, targetVal - 20);

          renderEseStudentRows(data.students || [], 'grades', cfg.max_marks || 60);
          updateEseSummaryStats(data.summary);
        })
        .catch(err => {
          tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-rose-400 font-bold">Error connecting to server.</td></tr>';
        });
    }

    function toggleEseModeInputs() {
      recalculateEseStats();
    }

    function renderEseStudentRows(students, mode, maxMarks) {
      const tbody = document.getElementById('eseMarksTableBody');
      if (!Array.isArray(students) || students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400 font-bold">No students registered in this batch.</td></tr>';
        return;
      }

      let html = '';
      students.forEach(s => {
        const reg = s.reg_no || s.sbte_reg_no;
        const markVal = s.ese_marks !== null && s.ese_marks !== undefined ? s.ese_marks : 40.0;
        const gradeVal = s.ese_grade || 'D';

        const inputHtml = `
          <select data-reg="${reg}" onchange="recalculateEseStats()" class="ese-val-input bg-slate-900 border border-slate-700 text-teal-400 font-bold text-center w-44 px-2 py-1 rounded-lg outline-none focus:border-teal-500 cursor-pointer">
            <option value="S" ${gradeVal === 'S' ? 'selected' : ''}>S (90%+ Outstanding)</option>
            <option value="A" ${gradeVal === 'A' ? 'selected' : ''}>A (80%-89% Excellent)</option>
            <option value="B" ${gradeVal === 'B' ? 'selected' : ''}>B (70%-79% Very Good)</option>
            <option value="C" ${gradeVal === 'C' ? 'selected' : ''}>C (60%-69% Good)</option>
            <option value="D" ${gradeVal === 'D' ? 'selected' : ''}>D (50%-59% Average)</option>
            <option value="E" ${gradeVal === 'E' || gradeVal === 'P' ? 'selected' : ''}>E (40%-49% Pass)</option>
            <option value="F" ${gradeVal === 'F' ? 'selected' : ''}>F (Below 40% Fail)</option>
          </select>
        `;

        html += `
          <tr class="hover:bg-slate-800/30 transition-premium">
            <td class="p-3 font-mono font-bold text-slate-300">${s.roll_no || '-'}</td>
            <td class="p-3 font-mono text-slate-400">${reg || '-'}</td>
            <td class="p-3 font-bold text-slate-200">${s.name}</td>
            <td class="p-3 text-center">${inputHtml}</td>
            <td class="p-3 text-center" id="status_cell_${reg}">
              <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Target Met</span>
            </td>
          </tr>
        `;
      });

      tbody.innerHTML = html;
      recalculateEseStats();
    }

    function recalculateEseStats(fromTarget = false) {
      const mode = document.getElementById('eseEntryMode').value;
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || 60);
      const eseTargetPct = parseFloat(document.getElementById('eseThresholdPercent').value || 50);
      const targetStudentPct = parseFloat(document.getElementById('targetStudentPercent').value || 70);

      const elL3Input = document.getElementById('inputLevel3Percent');
      const elL2Input = document.getElementById('inputLevel2Percent');
      const elL1Input = document.getElementById('inputLevel1Percent');

      if (fromTarget && elL3Input && elL2Input && elL1Input) {
        elL3Input.value = targetStudentPct;
        elL2Input.value = Math.max(0, targetStudentPct - 10);
        elL1Input.value = Math.max(0, targetStudentPct - 20);
      }

      const lvl3Val = elL3Input ? parseFloat(elL3Input.value || targetStudentPct) : targetStudentPct;
      const lvl2Val = elL2Input ? parseFloat(elL2Input.value || (targetStudentPct - 10)) : Math.max(0, targetStudentPct - 10);
      const lvl1Val = elL1Input ? parseFloat(elL1Input.value || (targetStudentPct - 20)) : Math.max(0, targetStudentPct - 20);

      const inputs = document.querySelectorAll('.ese-val-input');
      const totalStudents = inputs.length;
      let appeared = 0;
      let metTarget = 0;

      inputs.forEach(inp => {
        const reg = inp.getAttribute('data-reg');
        const val = inp.value.trim();
        const statusCell = document.getElementById(`status_cell_${reg}`);
        
        let isMet = false;
        if (mode === 'grades') {
          if (val && val !== 'F' && val !== 'FE') {
            appeared++;
            isMet = true;
            metTarget++;
          }
        } else {
          const mark = parseFloat(val);
          if (!isNaN(mark)) {
            appeared++;
            const pct = (mark / (maxMarks > 0 ? maxMarks : 60)) * 100;
            if (pct >= eseTargetPct) {
              isMet = true;
              metTarget++;
            }
          }
        }

        if (statusCell) {
          if (isMet) {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">ATTAINED ✓</span>';
          } else {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-rose-500/10 text-rose-400 border border-rose-500/30">NOT MET</span>';
          }
        }
      });

      const metPercent = totalStudents > 0 ? ((metTarget / totalStudents) * 100).toFixed(1) : 0;
      let levelText = 'Level 0 (Nil)';
      let levelClass = 'text-rose-400';

      if (parseFloat(metPercent) >= lvl3Val) {
        levelText = `Level 3 (High - ${metPercent}%)`;
        levelClass = 'text-emerald-400';
      } else if (parseFloat(metPercent) >= lvl2Val) {
        levelText = `Level 2 (Moderate - ${metPercent}%)`;
        levelClass = 'text-amber-400';
      } else if (parseFloat(metPercent) >= lvl1Val) {
        levelText = `Level 1 (Low - ${metPercent}%)`;
        levelClass = 'text-blue-400';
      }

      updateEseSummaryStats({
        total_students: totalStudents,
        appeared_count: appeared,
        met_target_count: metTarget,
        met_target_percent: metPercent,
        attainment_level_text: levelText,
        level_class: levelClass
      });
    }

    function updateEseSummaryStats(summary) {
      if (!summary) return;
      document.getElementById('statTotalStudents').innerText = summary.total_students || 0;
      document.getElementById('statAppearedStudents').innerText = summary.appeared_count || 0;
      document.getElementById('statMetTargetStudents').innerText = `${summary.met_target_count || 0} (${summary.met_target_percent || 0}%)`;
      
      const lvlEl = document.getElementById('statAttainmentLevel');
      if (lvlEl) {
        lvlEl.innerText = summary.attainment_level_text || (`Level ${summary.attainment_level || 0}`);
        if (summary.level_class) {
          lvlEl.className = `text-sm font-black ${summary.level_class}`;
        }
      }
    }

    function closeEseMarksModal() {
      const modal = document.getElementById('modalEseMarks');
      if (modal) modal.classList.add('hidden');
    }

    function saveEseMarks() {
      const mode = document.getElementById('eseEntryMode').value;
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || 60);
      const eseThresholdGrade = document.getElementById('eseThresholdGrade').value;
      const eseThresholdPercent = parseFloat(document.getElementById('eseThresholdPercent').value || 50);
      const cieThresholdPercent = parseFloat(document.getElementById('cieThresholdPercent').value || 50);
      const targetStudentPercent = parseFloat(document.getElementById('targetStudentPercent').value || 70);
      const level3Percent = parseFloat(document.getElementById('inputLevel3Percent')?.value || targetStudentPercent);
      const level2Percent = parseFloat(document.getElementById('inputLevel2Percent')?.value || Math.max(0, targetStudentPercent - 10));
      const level1Percent = parseFloat(document.getElementById('inputLevel1Percent')?.value || Math.max(0, targetStudentPercent - 20));

      const inputs = document.querySelectorAll('.ese-val-input');
      const marks = {};
      inputs.forEach(inp => {
        const reg = inp.getAttribute('data-reg');
        if (reg) marks[reg] = inp.value;
      });

      const payload = {
        entry_mode: mode,
        max_marks: maxMarks,
        ese_threshold_grade: eseThresholdGrade,
        ese_threshold_percent: eseThresholdPercent,
        cie_threshold_percent: cieThresholdPercent,
        target_student_percent: targetStudentPercent,
        level3_percent: level3Percent,
        level2_percent: level2Percent,
        level1_percent: level1Percent,
        marks: marks
      };

      fetch(`/api/r26/classroom/${currentSubjectId}/ese-marks/bulk-update`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message || 'Threshold settings & student evaluation records updated successfully.');
        closeEseMarksModal();
        loadCourseAttainment();
      })
      .catch(err => {
        alert('Failed to save ESE records.');
      });
    }

    // Live AI Status Indicator for Faculty
    document.addEventListener("DOMContentLoaded", () => {
      fetch('/api/system/ai-status')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('aiStatusBadge');
          if (badge && data.status === 'SUCCESS') {
            badge.classList.remove('hidden');
            if (data.ai_generation_enabled) {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span> AI Active</span>`;
            } else {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>`;
            }
          }
        })
        .catch(err => console.error("Failed to load system AI status:", err));
    });
  </script>

  <!-- Modal: Bulk Enter End Semester Exam (ESE) Marks & NBA Attainment Criteria -->
  <div id="modalEseMarks" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
      <!-- Modal Header -->
      <div class="p-5 bg-slate-950/80 border-b border-slate-800/80 flex justify-between items-center">
        <div>
          <h3 class="text-sm font-black text-slate-100 flex items-center gap-2">
            <span class="material-symbols-rounded text-emerald-400 text-base">tune</span>
            NBA Attainment Threshold Config & ESE Evaluation
          </h3>
          <p class="text-xs text-slate-400 mt-1">Configure threshold marks/grades for CIE and ESE exams, target student percentage, and batch attainment criteria.</p>
        </div>
        <button onclick="closeEseMarksModal()" class="text-slate-400 hover:text-white text-lg font-bold p-1 cursor-pointer">✕</button>
      </div>

      <!-- Modal Body -->
      <div class="p-5 overflow-y-auto space-y-5 flex-grow" id="eseModalBody">
        
        <!-- Streamlined Threshold Config Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <!-- Card 1: Exam Threshold Settings -->
          <div class="bg-slate-950/50 border border-slate-800/80 p-3 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
              <span class="text-xs font-black text-slate-200 uppercase tracking-wider">1. Assessment Threshold Settings</span>
              <span class="text-[10px] font-bold text-indigo-400 bg-indigo-950/40 px-2 py-0.5 rounded border border-indigo-800/50">CIE & ESE Targets</span>
            </div>

            <!-- Hidden Inputs for API Backward Compatibility -->
            <input type="hidden" id="eseEntryMode" value="grades">
            <input type="hidden" id="eseMaxMarks" value="60">
            <input type="hidden" id="eseThresholdPercent" value="50">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <!-- ESE Threshold Grade (SBTE Kerala Board) -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 mb-1">ESE Threshold Grade (SBTE)</label>
                <select id="eseThresholdGrade" onchange="recalculateEseStats()" class="w-full bg-slate-900 border border-slate-700 text-teal-400 font-bold text-xs px-2 py-1.5 rounded-lg outline-none focus:border-teal-500 overflow-ellipsis">
                  <option value="E">E Grade & Above (Pass - 40%+)</option>
                  <option value="D" selected>D Grade & Above (Average - 50%+)</option>
                  <option value="C">C Grade & Above (Good - 60%+)</option>
                  <option value="B">B Grade & Above (Very Good - 70%+)</option>
                  <option value="A">A Grade & Above (Excellent - 80%+)</option>
                  <option value="S">S Grade (Outstanding - 90%+)</option>
                </select>
              </div>

              <!-- Internal (CIE) Threshold -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 mb-1">Internal (CIE) Threshold (%)</label>
                <input type="number" id="cieThresholdPercent" value="50" min="30" max="90" step="1" oninput="recalculateEseStats()" class="w-full bg-slate-900 border border-slate-700 text-indigo-400 font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg outline-none focus:border-indigo-500">
              </div>
            </div>
          </div>

          <!-- Card 2: Target Student % & Attainment Levels -->
          <div class="bg-slate-950/50 border border-slate-800/80 p-3 rounded-xl space-y-2">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5">
              <span class="text-xs font-black text-slate-200 uppercase tracking-wider">2. Batch Target & Attainment Levels</span>
              <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/50">NBA Criteria</span>
            </div>

            <div class="grid grid-cols-4 gap-2">
              <div class="bg-slate-900/80 border border-emerald-500/40 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Target (T)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <input type="number" id="targetStudentPercent" value="70" min="30" max="100" step="1" oninput="recalculateEseStats(true)" class="w-12 bg-transparent text-emerald-400 font-mono font-black text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-400 font-bold">%</span>
                </div>
              </div>

              <div class="bg-slate-900/80 border border-emerald-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-emerald-400 uppercase tracking-tight">Level 3 (High)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-emerald-300 font-bold">&ge;</span>
                  <input type="number" id="inputLevel3Percent" value="70" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-emerald-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-400 font-bold">%</span>
                </div>
              </div>

              <div class="bg-slate-900/80 border border-amber-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-amber-400 uppercase tracking-tight">Level 2 (Mod)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-amber-300 font-bold">&ge;</span>
                  <input type="number" id="inputLevel2Percent" value="60" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-amber-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-400 font-bold">%</span>
                </div>
              </div>

              <div class="bg-slate-900/80 border border-blue-500/30 p-1.5 rounded-lg text-center flex flex-col justify-center items-center">
                <span class="block text-[9px] font-bold text-blue-400 uppercase tracking-tight">Level 1 (Low)</span>
                <div class="flex items-center justify-center gap-0.5 mt-0.5">
                  <span class="text-[10px] text-blue-300 font-bold">&ge;</span>
                  <input type="number" id="inputLevel1Percent" value="50" min="0" max="100" step="1" oninput="recalculateEseStats(false)" class="w-10 bg-transparent text-blue-300 font-mono font-bold text-xs sm:text-sm text-center outline-none p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  <span class="text-[10px] text-slate-400 font-bold">%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary & Batch Metrics -->
        <div id="eseSummaryBar" class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-slate-950/60 p-3.5 rounded-xl border border-slate-800/80">
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Max Batch Students</span>
            <span id="statTotalStudents" class="text-sm font-black text-slate-200">0</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Students Appeared</span>
            <span id="statAppearedStudents" class="text-sm font-black text-blue-400">0</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">Met Target Threshold</span>
            <span id="statMetTargetStudents" class="text-sm font-black text-emerald-400">0 (0%)</span>
          </div>
          <div>
            <span class="block text-[10px] font-bold text-slate-500 uppercase">ESE Attainment Level</span>
            <span id="statAttainmentLevel" class="text-sm font-black text-amber-400">Level 0</span>
          </div>
        </div>

        <!-- Toolbar & Table -->
        <div class="space-y-3">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 bg-slate-950/40 p-3 rounded-xl border border-slate-800/60">
            <span class="text-xs text-slate-300 font-bold">Student ESE Grade Ledger (SBTE Kerala)</span>
            <span class="text-[10px] font-bold text-slate-400 bg-slate-900 px-2 py-1 rounded border border-slate-800">S, A, B, C, D, E, F Evaluation</span>
          </div>

          <div class="overflow-x-auto border border-slate-800/60 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-950/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800">
                  <th class="p-3 w-16">Roll</th>
                  <th class="p-3 w-36">Register No</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 text-center w-44">ESE Score / Grade</th>
                  <th class="p-3 text-center w-28">Status</th>
                </tr>
              </thead>
              <tbody id="eseMarksTableBody" class="divide-y divide-slate-800/40">
                <tr>
                  <td colspan="5" class="p-6 text-center text-slate-500 font-bold">Loading student records...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="p-4 bg-slate-950/80 border-t border-slate-800/80 flex justify-end gap-3">
        <button onclick="closeEseMarksModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-premium cursor-pointer">
          Cancel
        </button>
        <button onclick="saveEseMarks()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer shadow-lg shadow-indigo-900/30 flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sm">save</span> Save ESE Evaluation & Calculate Attainment
        </button>
      </div>
    </div>
  </div>

  <!-- Dedicated Labwork Grade Modal (37.5 Marks) -->
  <div id="labworkGradeModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl space-y-0">
      <!-- Header -->
      <div class="bg-slate-950 px-4 py-3 border-b border-slate-800 flex justify-between items-center">
        <div class="flex items-center gap-2.5">
          <div class="p-1.5 bg-slate-800/80 border border-slate-700/60 rounded-lg text-slate-300 shrink-0">
            <span class="material-symbols-rounded text-lg">science</span>
          </div>
          <div>
            <h3 id="lwModalStudentInfo" class="text-base sm:text-lg font-black text-white tracking-wide uppercase">Student Name (PRN)</h3>
            <p class="text-[11px] font-bold text-slate-400 mt-0.5 flex items-center gap-1">
              Labwork Continuous Evaluation (Max 37.5 Marks)
            </p>
          </div>
        </div>
        
        <!-- Prev / Next Navigation -->
        <div class="flex items-center gap-1.5">
          <button onclick="navigateLwStudent(-1)" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-semibold rounded-md transition flex items-center gap-0.5 cursor-pointer">
            <span class="material-symbols-rounded text-xs">chevron_left</span> Prev
          </button>
          <span id="lwModalStudentCounter" class="text-[11px] font-mono text-slate-400">1 / 40</span>
          <button onclick="navigateLwStudent(1)" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-semibold rounded-md transition flex items-center gap-0.5 cursor-pointer">
            Next <span class="material-symbols-rounded text-xs">chevron_right</span>
          </button>
          <button onclick="closeLwModal()" class="text-slate-400 hover:text-white p-1 ml-1 cursor-pointer">
            <span class="material-symbols-rounded text-base">close</span>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-4 space-y-3 max-h-[75vh] overflow-y-auto">
        <!-- Select Exp No and Title & Date -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 bg-slate-950/60 p-3 rounded-xl border border-slate-800">
          <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Experiment No &amp; Title</label>
            <select id="lwModalExpSelect" onchange="loadLwExpValues()" class="w-full bg-slate-900 border border-slate-700 focus:border-slate-500 rounded-lg px-2.5 py-1.5 text-xs text-slate-100 font-medium outline-none">
            </select>
          </div>
          <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Date of Experiment</label>
            <input type="date" id="lwModalExpDate" onchange="updateLwExpField('date', this.value)" class="w-full bg-slate-900 border border-slate-700 focus:border-slate-500 rounded-lg px-2 py-1.5 text-xs text-slate-100 font-mono outline-none">
          </div>
        </div>

        <!-- Sliders for 5 Components -->
        <div class="space-y-2.5">
          <!-- Rough Record (5) -->
          <div class="bg-slate-950/40 p-2.5 rounded-xl border border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-300">Rough Record</span>
              <div class="flex items-center gap-1">
                <input type="number" id="lw_rough" step="0.5" min="0" max="5" oninput="syncLwComponent('rough', this.value, 5)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
                <span class="text-slate-500 text-[11px]">/ 5</span>
              </div>
            </div>
            <input type="range" id="lw_rough_slider" min="0" max="5" step="0.5" oninput="syncLwComponent('rough', this.value, 5)" class="w-full accent-slate-400 cursor-pointer">
          </div>

          <!-- Fair Record (7.5) -->
          <div class="bg-slate-950/40 p-2.5 rounded-xl border border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-300">Fair Record</span>
              <div class="flex items-center gap-1">
                <input type="number" id="lw_fair" step="0.5" min="0" max="7.5" oninput="syncLwComponent('fair', this.value, 7.5)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
                <span class="text-slate-500 text-[11px]">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_fair_slider" min="0" max="7.5" step="0.5" oninput="syncLwComponent('fair', this.value, 7.5)" class="w-full accent-slate-400 cursor-pointer">
          </div>

          <!-- Observation & Recording (7.5) -->
          <div class="bg-slate-950/40 p-2.5 rounded-xl border border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-300">Observation &amp; Recording</span>
              <div class="flex items-center gap-1">
                <input type="number" id="lw_obs" step="0.5" min="0" max="7.5" oninput="syncLwComponent('obs', this.value, 7.5)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
                <span class="text-slate-500 text-[11px]">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_obs_slider" min="0" max="7.5" step="0.5" oninput="syncLwComponent('obs', this.value, 7.5)" class="w-full accent-slate-400 cursor-pointer">
          </div>

          <!-- Procedure & Punctuality (7.5) -->
          <div class="bg-slate-950/40 p-2.5 rounded-xl border border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-300">Procedure &amp; Punctuality</span>
              <div class="flex items-center gap-1">
                <input type="number" id="lw_proc" step="0.5" min="0" max="7.5" oninput="syncLwComponent('proc', this.value, 7.5)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
                <span class="text-slate-500 text-[11px]">/ 7.5</span>
              </div>
            </div>
            <input type="range" id="lw_proc_slider" min="0" max="7.5" step="0.5" oninput="syncLwComponent('proc', this.value, 7.5)" class="w-full accent-slate-400 cursor-pointer">
          </div>

          <!-- Viva Voce (10) -->
          <div class="bg-slate-950/40 p-2.5 rounded-xl border border-slate-800 space-y-1.5">
            <div class="flex justify-between items-center text-xs font-bold">
              <span class="text-slate-300">Viva Voce</span>
              <div class="flex items-center gap-1">
                <input type="number" id="lw_viva" step="0.5" min="0" max="10" oninput="syncLwComponent('viva', this.value, 10)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
                <span class="text-slate-500 text-[11px]">/ 10</span>
              </div>
            </div>
            <input type="range" id="lw_viva_slider" min="0" max="10" step="0.5" oninput="syncLwComponent('viva', this.value, 10)" class="w-full accent-slate-400 cursor-pointer">
          </div>
        </div>

        <!-- Live Exp Total -->
        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex justify-between items-center">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Experiment Total Score</span>
          <span id="lwModalExpTotal" class="text-base font-mono font-black text-slate-200">0.0 / 37.5</span>
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-slate-950 px-4 py-3 border-t border-slate-800 flex flex-wrap justify-between items-center gap-2">
        <button onclick="closeLwModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold transition cursor-pointer">Cancel</button>
        <div class="flex items-center gap-1.5">
          <button onclick="saveLwModal(false, -1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition flex items-center gap-0.5 cursor-pointer">
            <span class="material-symbols-rounded text-xs">arrow_back</span> Save &amp; Prev
          </button>
          <button onclick="saveLwModal(true)" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-xs font-bold transition cursor-pointer border border-slate-600">Save Mark</button>
          <button onclick="saveLwModal(false, 1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition flex items-center gap-0.5 cursor-pointer">
            Save &amp; Next <span class="material-symbols-rounded text-xs">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>
  </div>
    </div>
  </div>

  <!-- Dedicated Open-Ended Project Grade Modal (7.5 Marks) -->
  <div id="openEndedGradeModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl space-y-0">
      <!-- Header -->
      <div class="bg-slate-950 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-slate-800 border border-slate-700 rounded-xl text-slate-300 shrink-0">
            <span class="material-symbols-rounded text-xl">assignment</span>
          </div>
          <div>
            <h3 id="oeModalStudentInfo" class="text-lg sm:text-xl font-black text-white tracking-wide uppercase">Student Name (PRN)</h3>
            <p class="text-xs font-bold text-slate-400 mt-0.5 flex items-center gap-1">
              Open-Ended Project Evaluation (Max 7.5 Marks)
            </p>
          </div>
        </div>
        
        <!-- Prev / Next Navigation -->
        <div class="flex items-center gap-2">
          <button onclick="navigateOeStudent(-1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg transition flex items-center gap-1 cursor-pointer">
            <span class="material-symbols-rounded text-xs">chevron_left</span> Prev
          </button>
          <span id="oeModalStudentCounter" class="text-xs font-mono text-slate-400">1 / 40</span>
          <button onclick="navigateOeStudent(1)" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg transition flex items-center gap-1 cursor-pointer">
            Next <span class="material-symbols-rounded text-xs">chevron_right</span>
          </button>
          <button onclick="closeOeModal()" class="text-slate-400 hover:text-white p-1 ml-2 cursor-pointer">
            <span class="material-symbols-rounded text-lg">close</span>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-5">
        <!-- Project Topic/Title -->
        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Project Topic / Title</label>
          <input type="text" id="oeModalProjectTopic" placeholder="e.g., Automatic Water Level Controller Circuit Design" class="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-100 font-medium outline-none">
        </div>

        <!-- Mark (out of 7.5) -->
        <div class="bg-slate-950/40 p-4 rounded-xl border border-slate-800 space-y-2.5">
          <div class="flex justify-between items-center text-xs font-bold">
            <span class="text-slate-300">Open-Ended Evaluation Mark</span>
            <div class="flex items-center gap-1">
              <input type="number" id="oe_mark" step="0.5" min="0" max="7.5" oninput="syncOeSlider(this.value)" class="w-14 bg-slate-900 border border-slate-700 rounded text-center text-xs font-mono font-bold text-slate-100 py-1 outline-none no-spinner focus:border-slate-500">
              <span class="text-slate-500 text-[11px]">/ 7.5</span>
            </div>
          </div>
          <input type="range" id="oe_mark_slider" min="0" max="7.5" step="0.5" oninput="syncOeSlider(this.value)" class="w-full accent-slate-400 cursor-pointer">
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-slate-950 px-6 py-4 border-t border-slate-800 flex flex-wrap justify-between items-center gap-3">
        <button onclick="closeOeModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition cursor-pointer">Cancel</button>
        <div class="flex items-center gap-2">
          <button onclick="saveOeModal(false, -1)" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
            <span class="material-symbols-rounded text-xs">arrow_back</span> Save &amp; Prev
          </button>
          <button onclick="saveOeModal(true)" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-xs font-bold transition cursor-pointer border border-slate-600">Save Mark</button>
          <button onclick="saveOeModal(false, 1)" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
            Save &amp; Next <span class="material-symbols-rounded text-xs">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Virtual Lab Modals (Revision 2021) -->
  <!-- Student Lab Modal -->
  <div id="studentLabModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
      <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
        <div>
          <h3 id="labModalStudentName" class="text-lg sm:text-xl font-black text-white uppercase tracking-wide">Student Evaluation</h3>
          <p id="labModalStudentReg" class="text-sm font-bold text-cyan-400 font-mono mt-0.5"></p>
        </div>
        <button onclick="closeStudentLabModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
      <div class="px-6 py-2 bg-slate-900 border-b border-slate-800/50 flex gap-4 text-xs font-bold">
        <button onclick="switchLabModalTab('exp')" id="labTabBtn_exp" class="py-2 border-b-2 border-blue-500 text-blue-400 px-1 transition-premium">Experiments (37.5)</button>
        <button onclick="switchLabModalTab('test')" id="labTabBtn_test" class="py-2 border-b-2 border-transparent text-slate-400 px-1 transition-premium">Model Tests (15)</button>
        <button onclick="switchLabModalTab('project')" id="labTabBtn_project" class="py-2 border-b-2 border-transparent text-slate-400 px-1 transition-premium">Micro-Project &amp; Attendance (22.5)</button>
        <button onclick="switchLabModalTab('board')" id="labTabBtn_board" class="py-2 border-b-2 border-transparent text-slate-400 px-1 transition-premium font-black text-blue-400">Board Exam (50)</button>
      </div>
      
      <div class="flex-grow overflow-y-auto p-6 space-y-6">
        <!-- TAB: EXPERIMENTS (CONTINUOUS EVALUATION CARDS LIST) -->
        <div id="labModalTab_exp" class="space-y-5">
          <!-- Summary Header Banner -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-950/60 border border-slate-800 p-4 rounded-xl shadow-md">
            <div>
              <h4 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-teal-400 text-base">science</span>
                Continuous Evaluation (Day-to-Day Lab Work)
              </h4>
              <p class="text-xs text-slate-400 mt-0.5">Grade each experiment individually out of 37.5. Final mark is the average across all graded experiments.</p>
            </div>
            <div class="flex items-center gap-4 shrink-0">
              <div class="text-right">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Lab Work Average:</span>
                <span id="labModalLabelExpSummary" class="text-base font-mono font-black text-emerald-400">0.00 / 37.5</span>
              </div>
              <div id="expQuickJumpContainer">
                <select id="expJumpSelect" onchange="jumpToExpCard(this.value)" class="bg-slate-900 border border-slate-800 text-xs font-bold text-slate-200 rounded-lg px-2.5 py-1.5 focus:border-blue-500 outline-none cursor-pointer">
                  <option value="">Jump to Exp...</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Dynamic Experiment Cards Container (List of all experiments with Exp No & Title) -->
          <div id="labModalExpsListContainer" class="space-y-4">
            <!-- Experiment Cards rendered here -->
          </div>
        </div>

        <!-- TAB: TESTS -->
        <div id="labModalTab_test" class="space-y-4 hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Test 1 -->
            <div class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4">
              <div class="border-b border-slate-800 pb-2 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-350 uppercase tracking-widest">Model Test 1 (CO1 &amp; CO2)</h4>
                <span class="text-xs font-bold text-blue-400" id="labModalT1Sum">0.0 / 15</span>
              </div>
              <div class="space-y-4">
                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                  <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-300">CO1 Score (Max 7.5)</span>
                    <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co1" oninput="syncSlider('labScore_t1_co1','labScore_t1_co1_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-blue-500 outline-none">
                  </div>
                  <input type="range" id="labScore_t1_co1_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_t1_co1').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
                </div>
                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                  <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-300">CO2 Score (Max 7.5)</span>
                    <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co2" oninput="syncSlider('labScore_t1_co2','labScore_t1_co2_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none">
                  </div>
                  <input type="range" id="labScore_t1_co2_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_t1_co2').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
                </div>
              </div>
            </div>
            <!-- Test 2 -->
            <div class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4">
              <div class="border-b border-slate-800 pb-2 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-350 uppercase tracking-widest">Model Test 2 (CO3 &amp; CO4)</h4>
                <span class="text-xs font-bold text-slate-300" id="labModalT2Sum">0.0 / 15</span>
              </div>
              <div class="space-y-4">
                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                  <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-300">CO3 Score (Max 7.5)</span>
                    <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co3" oninput="syncSlider('labScore_t2_co3','labScore_t2_co3_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none">
                  </div>
                  <input type="range" id="labScore_t2_co3_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_t2_co3').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
                </div>
                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                  <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-300">CO4 Score (Max 7.5)</span>
                    <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co4" oninput="syncSlider('labScore_t2_co4','labScore_t2_co4_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none">
                  </div>
                  <input type="range" id="labScore_t2_co4_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_t2_co4').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB: PROJECT & ATTENDANCE -->
        <div id="labModalTab_project" class="space-y-4 hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4 flex flex-col justify-between">
              <div>
                <h4 class="text-xs font-black text-slate-350 border-b border-slate-800 pb-2 uppercase tracking-widest mb-3">Open-Ended Project / Micro-Project</h4>
                <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Project Topic Description</label>
                <input type="text" id="labScore_projectTopic" placeholder="Enter assigned micro-project topic..." class="w-full bg-slate-950 border border-slate-850 rounded-lg px-3 py-2 text-base font-normal text-slate-200 focus:border-slate-500 outline-none mb-4">
              </div>
              <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-300">Project Mark (Max 7.5)</span>
                  <input type="number" step="0.1" min="0" max="7.5" id="labScore_projectMark" oninput="syncSlider('labScore_projectMark','labScore_projectMark_slider',7.5); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none">
                </div>
                <input type="range" id="labScore_projectMark_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById('labScore_projectMark').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
              </div>
            </div>
            <div class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4 flex flex-col justify-between">
              <div>
                <h4 class="text-xs font-black text-slate-350 border-b border-slate-800 pb-2 uppercase tracking-widest mb-3">Attendance Scoring</h4>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-xs text-slate-400 font-bold">Class Attendance Percentage:</span>
                  <span class="text-xs font-black text-white font-mono" id="labModalStudentAttPct">0%</span>
                </div>
              </div>
              <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
                <div class="flex justify-between items-center text-xs font-bold">
                  <span class="text-slate-300">Attendance Mark (Max 15)</span>
                  <input type="number" step="0.1" min="0" max="15" id="labScore_attendanceMark" oninput="syncSlider('labScore_attendanceMark','labScore_attendanceMark_slider',15); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none">
                </div>
                <input type="range" id="labScore_attendanceMark_slider" min="0" max="15" step="0.1" value="0" oninput="document.getElementById('labScore_attendanceMark').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
              </div>
            </div>
          </div>
        </div>

        <!-- TAB: BOARD EXAM -->
        <div id="labModalTab_board" class="space-y-4 hidden">
          <div class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4 max-w-md mx-auto">
            <h4 class="text-xs font-black text-slate-350 border-b border-slate-800 pb-2 uppercase tracking-widest">External Board Examination</h4>
            <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-850/50 space-y-2">
              <div class="flex justify-between items-center text-xs font-bold">
                <span class="text-slate-300">Board Exam Mark (Max 50)</span>
                <input type="number" step="0.5" min="0" max="50" id="labScore_boardExam" oninput="syncSlider('labScore_boardExam','labScore_boardExam_slider',50); calcLabModalScores()" class="w-16 bg-slate-950 border border-slate-800 rounded px-2 py-1 text-base font-normal text-slate-200 text-center focus:border-slate-500 outline-none" placeholder="0.0">
              </div>
              <input type="range" id="labScore_boardExam_slider" min="0" max="50" step="0.5" value="0" oninput="document.getElementById('labScore_boardExam').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-slate-400 bg-slate-800 cursor-pointer">
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom bar -->
      <div class="px-6 py-4 bg-slate-950/60 border-t border-slate-800 flex justify-between items-center">
        <div class="text-xs text-slate-400 font-bold flex gap-4">
          <div>Lab Work Avg: <span class="text-slate-200 font-mono" id="labModalLabelExp">0.0</span></div>
          <div>Model Test: <span class="text-slate-200 font-mono" id="labModalLabelTest">0.0</span></div>
          <div>Internal CA: <span class="text-teal-400 font-black font-mono text-sm" id="labModalLabelInternals">0.0 / 75</span></div>
        </div>
        <button onclick="saveStudentLabEvaluation()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-lg shadow-blue-500/10">
          <span class="material-symbols-rounded text-sm">save</span> Save Evaluation
        </button>
      </div>
    </div>
  </div>

  <!-- Manage Experiments Modal -->
  <div id="manageExperimentsModal" onclick="if(event.target === this) closeManageExperimentsModal()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-5xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
      <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
        <div>
          <h3 class="text-base font-black text-white">Experiments List</h3>
          <p class="text-xs text-slate-400 mt-0.5">Setup the experiments syllabus for day-to-day continuous evaluation.</p>
        </div>
        <button onclick="closeManageExperimentsModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>

      <div class="p-6 overflow-y-auto space-y-6 flex-grow">
        <!-- Add Experiment Form -->
        <form onsubmit="savePracticalExperiment(event)" class="bg-slate-950/30 border border-slate-800/40 p-4 rounded-xl space-y-4">
          <input type="hidden" id="expEditId">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-2">
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Exp No.</label>
              <input type="text" id="expFormNo" required placeholder="e.g. 1, 2A" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-normal text-slate-200 focus:border-blue-500 outline-none">
            </div>
            <div class="md:col-span-8">
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Experiment Title / Objective</label>
              <textarea id="expFormTitle" required placeholder="Enter experiment objective / detailed description..." rows="2" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-1.5 text-sm font-normal text-slate-200 focus:border-blue-500 outline-none resize-y"></textarea>
            </div>
            <div class="md:col-span-2">
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1.5">Map CO</label>
              <select id="expFormCo" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-normal text-slate-200 focus:border-blue-500 outline-none cursor-pointer">
                <option value="CO1">CO1</option>
                <option value="CO2">CO2</option>
                <option value="CO3">CO3</option>
                <option value="CO4">CO4</option>
              </select>
            </div>
          </div>
          <div class="flex justify-between items-center pt-2">
            <button type="button" id="btnImportDatabank" onclick="importFromDatabank()" class="hidden px-3.5 py-2 bg-amber-600/10 hover:bg-amber-600 border border-amber-500/20 hover:border-amber-500 text-amber-400 hover:text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1 cursor-pointer">
              <span class="material-symbols-rounded text-sm">database</span> Import from Databank
            </button>
            <button type="submit" id="btnSaveExp" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer ml-auto">
              <span class="material-symbols-rounded text-sm">add</span> Add Experiment
            </button>
          </div>
        </form>

        <!-- Experiments List Table -->
        <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-950/20">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-900 border-b border-slate-800 text-slate-400 font-bold uppercase">
                <th class="p-3 w-16 text-center">No.</th>
                <th class="p-3">Title / Objective</th>
                <th class="p-3 w-20 text-center">CO</th>
                <th class="p-3 w-28 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="manageExpsTableBody" class="divide-y divide-slate-850">
              <tr>
                <td colspan="4" class="p-6 text-center text-slate-500">No experiments set up yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Manage Tests Modal -->
  <div id="manageTestsModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-[95vw] lg:max-w-7xl max-h-[92vh] flex flex-col overflow-hidden shadow-2xl">
      <div class="px-6 py-4 bg-slate-950/80 border-b border-slate-800 flex justify-between items-center">
        <div>
          <h3 class="text-base font-black text-white flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400">quiz</span> Configure Practical Series Exam Questions &amp; Scheme
          </h3>
          <p class="text-xs text-slate-400 mt-0.5">Edit questions, CO selection, cognitive levels (BT), and rubrics for 15-Mark Practical Series Examinations.</p>
        </div>
        <button onclick="closeManageTestsModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>

      <form onsubmit="savePracticalTestQuestions(event)" class="flex-grow flex flex-col overflow-hidden">
        <div class="p-6 overflow-y-auto space-y-6 flex-grow scrollbar-thin">
          
          <!-- Series Exam Selector & Print Controls -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
            <div class="w-full sm:w-auto flex-grow max-w-md">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Select Practical Model Test</label>
              <select id="designTestName" onchange="renderTestQuestionsFields()" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
                <option value="Test 1">Series Exam 1 (CO1 &amp; CO2 — 15 Marks Total)</option>
                <option value="Test 2">Series Exam 2 (CO3 &amp; CO4 — 15 Marks Total)</option>
              </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end pt-2 sm:pt-0">
              <button type="button" onclick="printPracticalTestPaper()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-sky-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                <span class="material-symbols-rounded text-sm text-sky-400">print</span> Print Question Paper
              </button>
              <button type="button" onclick="printPracticalTestScheme()" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-purple-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                <span class="material-symbols-rounded text-sm text-purple-400">fact_check</span> Print Scheme &amp; Rubrics
              </button>
            </div>
          </div>

          <!-- Question Form Fields (2 COs x 2 Questions = 4 Questions per test) -->
          <div id="testQuestionsFieldsContainer" class="space-y-6">
            <!-- Inputs generated dynamically -->
          </div>
        </div>

        <div class="px-6 py-4 bg-slate-950/80 border-t border-slate-800 flex justify-between items-center">
          <span class="text-xs text-slate-400 font-medium">Total Exam Duration: <strong class="text-white">1 Hour</strong> | Total Marks: <strong class="text-white">15 Marks (2 COs × 7.5 Marks)</strong></span>
          <div class="flex items-center gap-2">
            <button type="button" onclick="closeManageTestsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition cursor-pointer">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-md">
              <span class="material-symbols-rounded text-sm">save</span> Save Test Scheme
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Generate Lesson Planner Modal -->
  <div id="generatePlannerModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden justify-center items-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
      <div class="px-6 py-4 bg-slate-950/60 border-b border-slate-800 flex justify-between items-center">
        <h3 class="text-base font-black text-white">Generate Practical Lesson Plan</h3>
        <button onclick="closeGeneratePlannerModal()" class="text-slate-400 hover:text-white transition-premium cursor-pointer">
          <span class="material-symbols-rounded">close</span>
        </button>
      </div>
      <form onsubmit="generatePlannerFromExperiments(event)" class="p-6 space-y-4">
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Lab Batch Session Mode</label>
          <select id="genPlannerBatchMode" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
            <option value="combined">Combined / Full Class (1 entry per experiment)</option>
            <option value="separate">Split Batches / Batch 1 &amp; 2 (2 entries per experiment)</option>
          </select>
        </div>
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Target Batch</label>
          <select id="genPlannerTargetBatch" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none cursor-pointer">
            <option value="Full">Full (Whole Class)</option>
            <option value="A">Batch A</option>
            <option value="B">Batch B</option>
          </select>
        </div>
        <div>
          <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Allocated Hours per Session</label>
          <input type="number" id="genPlannerHours" value="3" min="1" max="10" required class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm font-bold text-white focus:border-blue-500 outline-none">
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" onclick="closeGeneratePlannerModal()" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-400 hover:text-slate-350 rounded-xl text-xs font-bold transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer">Generate</button>
        </div>
      </form>
    </div>
  </div>

  @include('partials.support_desk_overlay')
  @include('partials.birthday_wish_modal')
</body>
</html>
