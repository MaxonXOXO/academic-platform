<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NBA Criteria Folders - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0b1329 0%, #030712 100%);
      color: #f1f5f9;
      min-height: 100vh;
    }
    .custom-gradient-bg {
      background: radial-gradient(circle at 10% 20%, rgba(244, 63, 94, 0.15), transparent 45%),
                  radial-gradient(circle at 90% 10%, rgba(99, 102, 241, 0.12), transparent 40%),
                  radial-gradient(circle at 50% 80%, rgba(20, 184, 166, 0.08), transparent 50%);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col custom-gradient-bg relative selection:bg-rose-500/30 selection:text-rose-200">

  <!-- Header Panel -->
  <header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur-md sticky top-0 z-40 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="/hod/report-centre" class="text-slate-400 hover:text-white transition flex items-center">
          <span class="material-symbols-rounded text-xl">arrow_back</span>
        </a>
        <h1 class="text-lg font-black tracking-tight text-white uppercase flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-500 text-xl">menu_book</span> NBA Criteria Accreditation
        </h1>
      </div>
      <div class="flex items-center gap-3">
        <form method="GET" action="/hod/nba-audit" class="flex items-center gap-2">
          <select name="academic_year" onchange="this.form.submit()" class="bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-sm font-bold text-white outline-none cursor-pointer">
            <option value="2025-2026" {{ $academicYear === '2025-2026' ? 'selected' : '' }}>2025-2026</option>
            <option value="2026-2027" {{ $academicYear === '2026-2027' ? 'selected' : '' }}>2026-2027</option>
          </select>
        </form>
        <a href="/hod/nba-audit/print?academic_year={{ urlencode($academicYear) }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold transition flex items-center gap-2 text-sm shadow-md border-none">
          <span class="material-symbols-rounded text-sm">print</span> Print Audit
        </a>
      </div>
    </div>
  </header>

  <!-- Main Console Layout -->
  <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    @if(session('success'))
      <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm font-bold">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-3 shadow-lg">
      <h3 class="text-base font-black text-white uppercase tracking-wider flex items-center gap-2">
        NBA Academic Year Status: <span class="text-rose-500 font-extrabold">{{ $academicYear }}</span>
      </h3>
      <p class="text-slate-300 text-sm leading-relaxed font-medium">
        Maintain accreditation documentation criteria files for the <span class="font-bold text-slate-200">{{ $department }}</span> branch. Course files (Criteria 3) are managed separately under the Course Files tab.
      </p>
    </div>

    <!-- Criteria Folders Layout -->
    <div class="space-y-6">
      @for($i = 1; $i <= 9; $i++)
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl overflow-hidden shadow-lg hover:border-slate-700/60 transition duration-300">
          <div class="bg-slate-950 px-6 py-4.5 border-b border-slate-800 flex justify-between items-center">
            <h4 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
              <span class="material-symbols-rounded text-rose-500 text-lg">folder</span> Criteria {{ $i }} Folder
            </h4>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Self Assessment Report (SAR)</span>
          </div>

          <div class="p-6 divide-y divide-slate-800">
            @if(isset($documents[$i]))
              @foreach($documents[$i] as $doc)
                <div class="py-4 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-4">
                  <div class="space-y-2">
                    <p class="text-sm font-bold text-slate-100">{{ $doc->document_name }}</p>
                    <div class="flex items-center gap-4">
                      @if($doc->status === 'Verified')
                        <span class="inline-flex items-center gap-1.5 text-xs font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-lg">
                          <span class="material-symbols-rounded text-sm">check_circle</span> Verified
                        </span>
                      @elseif($doc->status === 'Uploaded')
                        <span class="inline-flex items-center gap-1.5 text-xs font-black bg-rose-500/10 text-rose-400 border border-rose-500/30 px-3 py-1 rounded-lg">
                          <span class="material-symbols-rounded text-sm">hourglass_empty</span> Uploaded (Audit Pending)
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-black bg-slate-800 text-slate-350 border border-slate-700 px-3 py-1 rounded-lg">
                          <span class="material-symbols-rounded text-sm">cancel</span> Missing / Pending
                        </span>
                      @endif
                      
                      @if($doc->file_path)
                        <a href="{{ $doc->file_path }}" target="_blank" class="text-xs font-black text-rose-400 hover:text-rose-350 hover:underline flex items-center gap-1.5 transition">
                          <span class="material-symbols-rounded text-sm">visibility</span> View PDF
                        </a>
                      @endif
                      @if($i == 3)
                        <a href="/hod/report-centre" class="text-xs font-black text-teal-400 hover:text-teal-300 hover:underline flex items-center gap-1.5 transition">
                          <span class="material-symbols-rounded text-sm">rocket_launch</span> Launch Attainment Engine
                        </a>
                      @endif
                    </div>
                  </div>

                  <!-- File Upload Panel -->
                  <div class="w-full md:w-auto">
                    <form method="POST" action="/hod/nba-audit/upload" enctype="multipart/form-data" class="flex items-center gap-2">
                      @csrf
                      <input type="hidden" name="id" value="{{ $doc->id }}">
                      <label class="flex-1 md:flex-initial cursor-pointer px-4 py-2.5 bg-slate-950 hover:bg-slate-800 text-slate-200 border border-slate-800 hover:border-slate-700 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-md">
                        <span class="material-symbols-rounded text-sm">cloud_upload</span> Choose File
                        <input type="file" name="file" accept=".pdf,image/*" onchange="this.form.submit()" class="hidden">
                      </label>
                    </form>
                  </div>
                </div>
              @endforeach
            @else
              <p class="text-slate-400 italic text-sm p-4">No compliance files registered for this criteria folder.</p>
            @endif
          </div>
        </div>
      @endfor
    </div>

  </main>

  <!-- Sticky Footer -->
  <footer class="bg-slate-950 border-t border-slate-900 py-4 text-center text-slate-550 text-xs mt-auto">
    <p>&copy; 2026 Carmel Linx - NBA Criteria Audit Engine. All rights reserved.</p>
  </footer>

</body>
</html>
