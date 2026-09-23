<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Online Test Alert' }} - Carmel Linx</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Tailwind CSS (v4 Play CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: "Plus Jakarta Sans", sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col justify-center items-center p-4 bg-slate-950 selection:bg-purple-500/30 selection:text-purple-200">

    <div class="max-w-md w-full bg-slate-900/60 border border-slate-800 rounded-3xl p-6 sm:p-8 text-center shadow-2xl backdrop-blur-xl relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-36 h-36 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-16 h-16 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-5 shadow-lg shadow-purple-500/10">
            <span class="material-symbols-rounded text-3xl">info</span>
        </div>

        <h2 class="text-xl font-black text-white tracking-tight mb-2">
            {{ $title ?? 'Test Notification' }}
        </h2>

        <p class="text-sm text-slate-400 font-medium leading-relaxed mb-6">
            {{ $message ?? 'The test you are trying to access is currently not available.' }}
        </p>

        <a href="{{ $backUrl ?? '/dashboard/student' }}" class="inline-flex items-center justify-center gap-2 w-full py-3 px-5 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-lg shadow-purple-500/20 transition-all">
            <span class="material-symbols-rounded text-base">arrow_back</span> Return to Student Dashboard
        </a>
    </div>

</body>
</html>
