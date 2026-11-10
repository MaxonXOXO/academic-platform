<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S6 Open Elective — HOD Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans antialiased p-4 sm:p-8 flex items-center justify-center">

  <div class="max-w-md w-full p-8 rounded-3xl bg-slate-900 border border-slate-800 text-center space-y-4 shadow-2xl">
    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 mx-auto flex items-center justify-center">
      <span class="material-symbols-rounded text-3xl">lock_clock</span>
    </div>

    <div>
      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
        Centralized Allocation in Progress
      </span>
      <h1 class="text-lg font-extrabold text-white mt-2">S6 Open Elective — Department {{ $branch }}</h1>
    </div>

    <p class="text-xs text-slate-300 leading-relaxed">
      Student registrations, choice ranking, and multi-branch classroom allocations are currently being managed centrally by the <strong>Principal &amp; Super Admin Office</strong>.
    </p>

    <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 text-xs text-slate-400 text-left space-y-2">
      <div class="flex items-center gap-2 text-slate-300 font-bold">
        <span class="material-symbols-rounded text-cyan-400 text-base">info</span> Department Handover Policy:
      </div>
      <p class="text-[11px] leading-snug">
        Once student allocations are approved and finalized, the Principal will release the inter-branch student nominal roll to your HOD dashboard. You will then be able to assign departmental faculty and conduct classes.
      </p>
    </div>

    <a href="/dashboard/hod" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-premium no-underline shadow-md">
      <span class="material-symbols-rounded text-sm">arrow_back</span> Return to HOD Dashboard
    </a>
  </div>

</body>
</html>
