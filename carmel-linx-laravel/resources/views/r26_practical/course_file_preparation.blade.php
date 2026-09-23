<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Course File Checklog - {{ $batchSubject->subject_code }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.25rem;
        }
    </style>
</head>
<body class="min-h-screen p-6">

    <div class="max-w-6xl mx-auto space-y-6">

        <header class="glass-panel p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-3 py-1 text-[11px] font-black tracking-wider rounded-md bg-violet-500/20 text-violet-400 border border-violet-500/30">
                    COURSE PORTFOLIO COMPILATION (R2026)
                </span>
                <h1 class="text-2xl font-black text-white mt-1">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</h1>
                <p class="text-xs text-slate-400 mt-1">Compile and index the 25 required files for academic auditing and NBA portfolio alignment.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="window.close()" class="px-4 py-2 bg-slate-900 border border-slate-800 text-slate-350 text-xs font-bold rounded-xl hover:border-slate-700 transition">
                    Close Window
                </button>
            </div>
        </header>

        <main class="glass-panel p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300 border-collapse">
                    <thead>
                        <tr class="bg-slate-900/60 font-bold border-b border-slate-850 text-slate-400">
                            <th class="p-3 w-16 text-center">Index</th>
                            <th class="p-3 w-8 flex items-center justify-center">Status</th>
                            <th class="p-3">Audit Document Title</th>
                            <th class="p-3 w-48">Attachment Link</th>
                            <th class="p-3 w-72">Remarks / Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                        <tr class="border-b border-slate-850 hover:bg-slate-900/20 transition" data-doc-id="{{ $doc->id }}">
                            <td class="p-3 font-mono text-center font-bold text-slate-500">{{ $doc->document_number }}</td>
                            <td class="p-3 text-center">
                                <input type="checkbox" {{ $doc->is_checked ? 'checked' : '' }} onchange="updateDocCheck('{{ $doc->id }}', this.checked)" class="w-4.5 h-4.5 rounded border-slate-800 bg-slate-950 text-violet-600 focus:ring-0 cursor-pointer">
                            </td>
                            <td class="p-3 font-bold text-slate-200">{{ $doc->document_name }}</td>
                            <td class="p-2">
                                <div class="flex items-center gap-2">
                                    @if($doc->data_payload)
                                    <a href="/{{ $doc->data_payload }}" target="_blank" id="link-{{ $doc->id }}" class="text-violet-400 hover:text-violet-300 font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-file-arrow-down"></i> View File
                                    </a>
                                    @else
                                    <span class="text-slate-550 italic text-[11px]" id="span-{{ $doc->id }}">No attachment</span>
                                    @endif
                                    
                                    <input type="file" id="file-input-{{ $doc->id }}" class="hidden" onchange="uploadDocFile('{{ $doc->id }}')">
                                    <button onclick="document.getElementById('file-input-{{ $doc->id }}').click()" class="text-slate-400 hover:text-white p-1 rounded hover:bg-slate-800">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="p-2">
                                <input type="text" value="{{ $doc->remarks }}" placeholder="Write compliance note..." onchange="updateDocRemarks('{{ $doc->id }}', this.value)" class="px-3 py-1.5 bg-slate-950 border border-slate-850 rounded-xl text-xs text-white focus:outline-none w-full">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = "{{ $batchSubject->id }}";

        async function updateDocCheck(docId, isChecked) {
            try {
                await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/save-doc`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ doc_id: docId, is_checked: isChecked })
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function updateDocRemarks(docId, val) {
            try {
                await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/save-doc`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ doc_id: docId, remarks: val })
                });
            } catch(e) {
                console.error(e);
            }
        }

        async function uploadDocFile(docId) {
            const input = document.getElementById(`file-input-${docId}`);
            if (input.files.length === 0) return;

            const formData = new FormData();
            formData.append('doc_id', docId);
            formData.append('attachment', input.files[0]);

            try {
                const res = await fetch(`/api/r26/classroom/practical/course-file/${subjectId}/upload-doc`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if (data.attachment_url) {
                    const row = document.querySelector(`tr[data-doc-id="${docId}"]`);
                    row.querySelector('input[type="checkbox"]').checked = true;

                    // update attachment cell
                    const cell = row.cells[3];
                    cell.innerHTML = `
                        <div class="flex items-center gap-2">
                            <a href="${data.attachment_url}" target="_blank" id="link-${docId}" class="text-violet-400 hover:text-violet-300 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-file-arrow-down"></i> View File
                            </a>
                            <input type="file" id="file-input-${docId}" class="hidden" onchange="uploadDocFile('${docId}')">
                            <button onclick="document.getElementById('file-input-${docId}').click()" class="text-slate-400 hover:text-white p-1 rounded hover:bg-slate-800">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </button>
                        </div>
                    `;
                }
            } catch(e) {
                alert("Upload failed.");
            }
        }
    </script>
</body>
</html>
