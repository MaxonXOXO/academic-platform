<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - R2026 Course File Preparation</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons & Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background-color: #0b0f19;
      color: #f1f5f9;
    }
    .bg-panel {
      background-color: rgba(15, 23, 42, 0.4);
      border-color: rgba(30, 41, 59, 0.8);
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.1);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.3);
      border-radius: 9999px;
    }
  </style>
</head>
<body class="min-h-screen p-3 custom-scrollbar">

  <div class="w-full max-w-none px-4 space-y-3">
    
    <!-- TOP COMPACT BANNER / HEADER -->
    <div class="flex flex-wrap justify-between items-center bg-panel border border-slate-800/80 rounded-xl px-4 py-2 gap-2 shadow-sm">
      <div class="flex items-center gap-2.5">
        <img src="/logo.jpg" class="w-7 h-7 rounded-lg object-cover shadow-sm">
        <div>
          <div class="text-xs font-bold tracking-tight text-slate-100 flex items-center gap-1.5">
            <span>Carmel Linx</span>
            <span class="text-[10px] font-bold font-mono px-1.5 py-0.2 bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 rounded">R2026 COURSE FILE</span>
          </div>
          <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none">Preparation & Checklist Console</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="/r26/classroom/theory/{{ $batchSubject->id }}" onclick="window.close(); return false;" class="px-2 py-0.5 bg-rose-600/80 hover:bg-rose-600 text-white rounded-md text-[10px] font-semibold transition-all border border-rose-500/30 cursor-pointer flex items-center gap-1 shadow-xs">
          <span class="material-symbols-rounded text-xs">arrow_back</span>
          Close & Back to Classroom
        </a>
      </div>
    </div>

    <!-- META INFORMATION STRIP -->
    <div class="bg-panel border border-slate-800/80 rounded-xl px-4 py-2.5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
      <div class="flex flex-wrap items-center gap-2 text-xs">
        <span class="px-3 py-1 bg-slate-800/90 text-slate-100 font-bold text-sm sm:text-base rounded-md border border-slate-700/80 shadow-xs tracking-tight">{{ $batchSubject->subject_name }}</span>
        <span class="px-1.5 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded font-mono text-[11px] font-semibold">{{ $batchSubject->subject_code }}</span>
        <span class="text-slate-500 text-xs">•</span>
        <span class="text-slate-400 text-[11px]">Semester {{ $batchSubject->semester }}</span>
        <span class="text-slate-500 text-xs">•</span>
        <span class="text-slate-400 text-[11px]">{{ $batchSubject->classroom_id }}</span>
      </div>
      
      <div class="flex flex-wrap items-center gap-2">
        <div class="px-2 py-0.5 bg-slate-950/60 border border-slate-800 rounded text-[11px] font-mono text-slate-300">
          Status: <span id="file-status-badge" class="font-extrabold uppercase text-[11px] {{ $courseFile->status === 'Complete' ? 'text-emerald-400' : 'text-amber-400' }}">{{ $courseFile->status }}</span>
        </div>
        <a href="/r26/classroom/course-file/{{ $batchSubject->id }}/print-pdf" class="px-2.5 py-1 bg-indigo-600/80 hover:bg-indigo-600 text-white rounded-md text-[10px] font-semibold transition-all shadow-xs flex items-center gap-1 border border-indigo-500/30">
          <span class="material-symbols-rounded text-xs">picture_as_pdf</span>
          Generate & Download Course File PDF
        </a>
      </div>
    </div>

    <!-- DOCUMENT CHECKLIST TABLE -->
    <div class="bg-panel border border-slate-800/80 rounded-xl p-4 shadow-sm space-y-3">
      <div class="flex justify-between items-center border-b border-slate-800 pb-2">
        <div>
          <h3 class="text-xs font-bold uppercase tracking-wider text-slate-200 flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sky-400 text-sm">playlist_add_check</span>
            Course File Document Index
          </h3>
          <p class="text-[11px] text-slate-400 mt-0.5">Update checklist status and add faculty audit remarks for each standard catalog document.</p>
        </div>
      </div>

      <div class="border border-slate-800 rounded-xl overflow-x-auto bg-slate-950/15 custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[900px]">
          <thead>
            <tr class="bg-slate-900/40 text-[10px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-800">
              <th class="py-1.5 px-2.5 w-[6%] text-center">Doc No.</th>
              <th class="py-1.5 px-2.5 w-[44%]">Document Description</th>
              <th class="py-1.5 px-2.5 w-[14%] text-center">Status</th>
              <th class="py-1.5 px-2.5 w-[22%]">Remarks / Notes</th>
              <th class="py-1.5 px-2.5 w-[14%] text-center">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-850 text-xs">
            @foreach($documents as $doc)
              <tr id="doc-row-{{ $doc->id }}" class="hover:bg-slate-900/30 transition-all">
                <td class="py-1.5 px-2.5 font-mono font-bold text-center text-slate-400 text-xs">{{ sprintf('%02d', $doc->document_number) }}</td>
                <td class="py-1.5 px-2.5 font-medium text-slate-200 text-xs">{{ $doc->document_name }}</td>
                <td class="py-1.5 px-2.5 text-center">
                  <div class="flex items-center justify-center gap-1.5">
                    <input type="checkbox" id="check-{{ $doc->id }}" {{ $doc->is_checked ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-slate-950 border-slate-800 rounded focus:ring-indigo-500 cursor-pointer">
                    <label for="check-{{ $doc->id }}" class="text-[11px] font-bold uppercase cursor-pointer {{ $doc->is_checked ? 'text-emerald-400' : 'text-slate-400' }}" id="lbl-status-{{ $doc->id }}">
                      {{ $doc->is_checked ? 'Verified' : 'Pending' }}
                    </label>
                  </div>
                </td>
                <td class="py-1.5 px-2.5">
                  <input type="text" id="remarks-{{ $doc->id }}" value="{{ $doc->remarks }}" placeholder="No remarks added" class="w-full bg-slate-950/50 border border-slate-800 rounded px-2 py-0.5 text-xs text-slate-200 outline-none focus:border-indigo-500 font-normal">
                </td>
                <td class="py-1.5 px-2.5 text-center">
                    @php
                      $previewUrl = null;
                      $num = $doc->document_number;
                      $manualUpload = in_array($num, [2, 13, 17, 18, 25]);
                      
                      $filePath = null;
                      if ($doc->data_payload) {
                          $payload = json_decode($doc->data_payload, true);
                          $filePath = $payload['file_path'] ?? null;
                      }

                      if ($num == 3 || $num == 4 || $num == 10 || $num == 14) {
                          $previewUrl = "/r26/classroom/theory/" . $batchSubject->id;
                      } elseif ($num == 7 && isset($calendarId) && $calendarId) {
                          $previewUrl = "/hod/academic-calendar/" . $calendarId . "/print";
                      } elseif ($num == 8) {
                          $previewUrl = "/r26/classroom/lesson-plan/print/" . $batchSubject->id;
                      } elseif ($num == 12) {
                          $previewUrl = "/hod/remedial-report/print?classroom_id=" . ($classroom->classroom_id ?? '');
                      } elseif ($num == 15) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/internals/print-cie";
                      } elseif ($num == 16) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/final-results/print";
                      } elseif ($num == 19 || $num == 20) {
                          $previewUrl = "/r26/classroom/" . $batchSubject->id . "/nba/attainment-report";
                      }
                    @endphp
                  <div class="grid grid-cols-2 gap-1.5 w-full max-w-[150px] mx-auto">
                    @if($manualUpload)
                      @if($filePath)
                        <a href="/{{ $filePath }}" target="_blank" class="px-2 py-0.5 bg-sky-600/80 hover:bg-sky-600 text-white rounded-md text-[10px] font-semibold transition-all flex items-center justify-center gap-1 no-underline w-full">
                          <span class="material-symbols-rounded text-xs">download</span>
                          File
                        </a>
                      @else
                        <div class="w-full">
                          <input type="file" id="file-input-{{ $doc->id }}" class="hidden" onchange="uploadAttachment({{ $doc->id }})">
                          <button type="button" onclick="document.getElementById('file-input-{{ $doc->id }}').click()" class="px-2 py-0.5 bg-amber-600/80 hover:bg-amber-600 text-white rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center justify-center gap-1 w-full">
                            <span class="material-symbols-rounded text-xs">upload</span>
                            Upload
                          </button>
                        </div>
                      @endif
                    @elseif($previewUrl)
                      <a href="{{ $previewUrl }}" target="_blank" class="px-2 py-0.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-md text-[10px] font-semibold transition-all flex items-center justify-center gap-1 no-underline w-full">
                        <span class="material-symbols-rounded text-xs">visibility</span>
                        Preview
                      </a>
                    @else
                      <div class="w-full"></div>
                    @endif
                    <button type="button" onclick="saveDocumentStatus({{ $doc->id }})" class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-md text-[10px] font-semibold transition-all cursor-pointer flex items-center justify-center gap-1 w-full">
                      <span class="material-symbols-rounded text-xs">save</span>
                      Save
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    function saveDocumentStatus(docId) {
      const isChecked = document.getElementById('check-' + docId).checked;
      const remarks = document.getElementById('remarks-' + docId).value;

      fetch(`/api/r26/classroom/course-file/{{ $batchSubject->id }}/save-doc`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          doc_id: docId,
          is_checked: isChecked,
          remarks: remarks
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Update status label
          const statusLbl = document.getElementById('lbl-status-' + docId);
          if (isChecked) {
            statusLbl.innerText = 'Verified';
            statusLbl.className = 'text-[11px] font-bold uppercase text-emerald-400';
          } else {
            statusLbl.innerText = 'Pending';
            statusLbl.className = 'text-[11px] font-bold uppercase text-slate-400';
          }

          // Update general file status badge
          const statusBadge = document.getElementById('file-status-badge');
          statusBadge.innerText = data.file_status;
          if (data.file_status === 'Complete') {
            statusBadge.className = 'font-extrabold uppercase text-[11px] text-emerald-400';
          } else {
            statusBadge.className = 'font-extrabold uppercase text-[11px] text-amber-400';
          }

          // Show minor auto-saved toast
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-emerald-600/90 text-white px-3 py-1.5 rounded-md text-xs font-semibold shadow-lg transition-opacity duration-300 z-50';
          toast.innerText = '✓ Saved successfully!';
          document.body.appendChild(toast);
          setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
          }, 1500);
        } else {
          alert('Failed to save document status: ' + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('An error occurred while saving.');
      });
    }

    function uploadAttachment(docId) {
      const fileInput = document.getElementById('file-input-' + docId);
      if (!fileInput.files.length) return;
      
      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('file', file);
      formData.append('doc_id', docId);

      const uploadBtn = fileInput.nextElementSibling;
      const originalText = uploadBtn.innerHTML;
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = `<span class="material-symbols-rounded text-xs animate-spin">sync</span>`;

      fetch(`/api/r26/classroom/course-file/{{ $batchSubject->id }}/upload-doc`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Check the verification checkbox
          document.getElementById('check-' + docId).checked = true;
          const statusLbl = document.getElementById('lbl-status-' + docId);
          statusLbl.innerText = 'Verified';
          statusLbl.className = 'text-[11px] font-bold uppercase text-emerald-400';

          // Replace upload button with download button
          const container = fileInput.parentElement.parentElement;
          container.firstElementChild.outerHTML = `
            <a href="/${data.file_path}" target="_blank" class="px-2 py-0.5 bg-sky-600/80 hover:bg-sky-600 text-white rounded-md text-[10px] font-semibold transition-all flex items-center justify-center gap-1 no-underline shrink-0">
              <span class="material-symbols-rounded text-xs">download</span>
              File
            </a>
          `;

          // Show saved toast
          const toast = document.createElement('div');
          toast.className = 'fixed bottom-4 right-4 bg-emerald-600/90 text-white px-3 py-1.5 rounded-md text-xs font-semibold shadow-lg transition-opacity duration-300 z-50';
          toast.innerText = '✓ File uploaded and verified!';
          document.body.appendChild(toast);
          setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
          }, 1500);
        } else {
          alert('Upload failed: ' + data.message);
          uploadBtn.disabled = false;
          uploadBtn.innerHTML = originalText;
        }
      })
      .catch(err => {
        console.error(err);
        alert('An error occurred during upload.');
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
      });
    }
  </script>
</body>
</html>
