<!-- TAB: STUDY MATERIALS & PRE-CLASS HUB -->
<div class="bg-panel border border-slate-800/80 rounded-xl p-5 shadow-md space-y-5">
  
  <!-- Header Bar -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800/50 pb-3 gap-3">
    <div>
      <h3 class="text-base font-bold text-title flex items-center gap-2">
        <span class="material-symbols-rounded text-amber-400 text-xl">folder_open</span>
        <span>Study Materials & Pre-Class / Pre-Lab Hub</span>
      </h3>
      <p class="text-xs text-muted mt-1">Publish lecture notes, PDFs, diagram images, and video clips for students with evening pre-class notifications.</p>
    </div>
    <button onclick="toggleMaterialUploadForm(this)" class="px-3.5 py-2 bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-500 hover:to-sky-500 text-white rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-1.5 cursor-pointer">
      <span class="material-symbols-rounded text-sm">cloud_upload</span>
      <span>Publish New Material</span>
    </button>
  </div>

  <!-- UPLOAD / PUBLISH FORM PANEL (HIDDEN BY DEFAULT) -->
  <div id="materialUploadFormPanel" class="materialUploadFormPanel bg-slate-900/60 border border-slate-800 rounded-xl p-4 space-y-4 hidden">
    <div class="flex justify-between items-center border-b border-slate-800 pb-2">
      <h4 class="font-bold text-title text-xs uppercase tracking-wider flex items-center gap-1.5">
        <span class="material-symbols-rounded text-sky-400 text-sm">add_circle</span>
        <span>Publish Resource / Pre-Class Guidelines</span>
      </h4>
      <button onclick="toggleMaterialUploadForm()" class="text-slate-400 hover:text-white text-xs cursor-pointer">✕ Close</button>
    </div>

    <form id="vlmUploadForm" onsubmit="handleMaterialUploadSubmit(event)" class="space-y-4">
      <input type="hidden" name="batch_subject_id" value="{{ $batchSubject->id }}">
      <input type="hidden" name="room_type" value="{{ $roomType ?? 'Theory' }}">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Topic / Experiment No -->
        <div>
          <label class="block text-xs font-bold text-muted mb-1">Topic / Day / Experiment No. <span class="text-rose-500">*</span></label>
          <input type="text" name="experiment_or_topic_no" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none" placeholder="e.g. Day 12 / Exp 03 (CRO Calibration)">
        </div>

        <!-- Material Title -->
        <div>
          <label class="block text-xs font-bold text-muted mb-1">Material Title <span class="text-rose-500">*</span></label>
          <input type="text" name="title" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none" placeholder="e.g. Pre-Lab Rough Record Guidelines & Circuit PDF">
        </div>
      </div>

      <!-- Pre-Class Instructions / Guidelines -->
      <div>
        <label class="block text-xs font-bold text-muted mb-1">Pre-Class Instructions for Students (Optional)</label>
        <textarea name="pre_class_instruction" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none" placeholder="e.g. Draw circuit diagram in rough record before 9:00 AM class tomorrow..."></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Material Type -->
        <div>
          <label class="block text-xs font-bold text-muted mb-1">Material Type <span class="text-rose-500">*</span></label>
          <select name="material_type" id="vlm_material_type" onchange="toggleMaterialInputFields(this)" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none font-medium">
            <option value="pdf">PDF Document (Notes / Manual)</option>
            <option value="video_clip">🎬 Video Clip — Direct Upload (MP4, max 25 MB)</option>
            <option value="video">▶ Video Link (YouTube / Vimeo)</option>
            <option value="image">Diagram / Image (PNG / JPG)</option>
            <option value="document">Word Doc / Presentation</option>
            <option value="link">External Web Reference Link</option>
          </select>
        </div>

        <!-- Target Date -->
        <div>
          <label class="block text-xs font-bold text-muted mb-1">Target Class / Lab Date</label>
          <input type="date" name="target_date" value="{{ now()->addDay()->toDateString() }}" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none font-medium">
        </div>

        <!-- Pre-Class Notice Checkbox -->
        <div class="flex items-end pb-2">
          <label class="flex items-center gap-2 text-xs font-bold text-amber-400 cursor-pointer">
            <input type="checkbox" name="is_pre_class_notice" value="1" checked class="w-4 h-4 rounded text-amber-500 bg-slate-950 border-slate-800 focus:ring-amber-500">
            <span>⚡ Send Urgent Evening Alert to Students</span>
          </label>
        </div>
      </div>

      <!-- File Attachment Field -->
      <div id="vlm_file_input_container">
        <label id="vlm_file_label" class="block text-xs font-bold text-muted mb-1">Upload File (PDF / Image / Doc — up to 25 MB)</label>
        <input type="file" name="file" id="vlm_file_input" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-title focus:border-blue-500 outline-none">
      </div>

      <!-- Video / Link URL Field -->
      <div id="vlm_url_input_container" class="hidden">
        <label class="block text-xs font-bold text-muted mb-1">YouTube / Vimeo Link</label>
        <input type="url" name="video_url" id="vlm_url_input" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-xs text-title focus:border-blue-500 outline-none" placeholder="https://www.youtube.com/watch?v=... or Vimeo link">
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
        <button type="button" onclick="toggleMaterialUploadForm()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold cursor-pointer">Cancel</button>
        <button type="submit" id="btnSubmitMaterial" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all shadow-md cursor-pointer flex items-center gap-1.5">
          <span class="material-symbols-rounded text-xs">send</span>
          <span>Publish Now</span>
        </button>
      </div>
    </form>
  </div>

  <!-- MATERIALS LOG INDEX TABLE -->
  <div class="bg-panel border border-card rounded-xl p-4 space-y-3">
    <div class="flex justify-between items-center border-b border-card pb-2">
      <h4 class="font-bold text-title text-sm flex items-center gap-1.5">
        <span class="material-symbols-rounded text-emerald-400 text-base">checklist</span>
        <span>Published Learning Resources & Pre-Class Log</span>
      </h4>
      <button onclick="loadSubjectMaterials()" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 cursor-pointer">
        <span class="material-symbols-rounded text-xs">refresh</span>
        <span>Reload</span>
      </button>
    </div>

    <div class="border border-card rounded-lg overflow-x-auto bg-slate-950/10 custom-scrollbar">
      <table class="w-full text-left border-collapse min-w-[750px]">
        <thead>
          <tr class="bg-slate-900/30 text-xs font-bold text-muted uppercase tracking-wider border-b border-card">
            <th class="p-3 pl-4 w-[18%]">Topic / Exp No.</th>
            <th class="p-3 w-[28%]">Material Title & Guidelines</th>
            <th class="p-3 w-[12%] text-center">Type</th>
            <th class="p-3 w-[14%] text-center">Target Date</th>
            <th class="p-3 w-[14%] text-center">Status</th>
            <th class="p-3 pr-4 w-[14%] text-right">Actions</th>
          </tr>
        </thead>
        <tbody id="materialsTableBody" class="vlm-table-body divide-y divide-card text-sm font-normal">
          @if(isset($studyMaterials) && count($studyMaterials) > 0)
            @foreach($studyMaterials as $m)
              <tr class="hover:bg-slate-900/30 transition-all">
                <td class="p-3 pl-4 font-bold text-emerald-400 text-xs">{{ $m->experiment_or_topic_no }}</td>
                <td class="p-3">
                  <p class="font-bold text-title text-xs">{{ $m->title }}</p>
                  @if($m->pre_class_instruction)
                    <p class="text-[11px] text-muted mt-0.5">{{ $m->pre_class_instruction }}</p>
                  @endif
                </td>
                <td class="p-3 text-center">
                  @if($m->material_type === 'video_clip')
                    <span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded font-bold text-xs">🎬 Clip</span>
                  @elseif($m->material_type === 'video')
                    <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded font-bold text-xs">▶ Video</span>
                  @elseif($m->material_type === 'image')
                    <span class="px-2 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded font-bold text-xs">Image</span>
                  @elseif($m->material_type === 'link')
                    <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded font-bold text-xs">Link</span>
                  @else
                    <span class="px-2 py-0.5 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded font-bold text-xs">PDF</span>
                  @endif
                </td>
                <td class="p-3 text-center font-mono text-xs text-title">{{ $m->target_date ? substr($m->target_date, 0, 10) : '—' }}</td>
                <td class="p-3 text-center">
                  @if($m->is_pre_class_notice)
                    <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded font-bold text-[10px]">⚡ Urgent Alert</span>
                  @else
                    <span class="text-slate-400 text-xs">Standard</span>
                  @endif
                </td>
                <td class="p-3 pr-4 text-right flex justify-end gap-1.5 items-center">
                  @if($m->material_type === 'video_clip' && $m->file_path)
                    <button onclick="openVlmVideoModal('{{ addslashes($m->title) }}', '{{ $m->file_path }}', 'clip')" class="px-2.5 py-1 bg-violet-600 hover:bg-violet-700 text-white rounded text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">play_circle</span>
                      <span>Play</span>
                    </button>
                  @elseif($m->file_path)
                    <a href="{{ $m->file_path }}" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-xs font-bold border border-slate-700 transition-all no-underline flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">visibility</span>
                      <span>Preview</span>
                    </a>
                  @elseif($m->video_url)
                    <button onclick="openVlmVideoModal('{{ addslashes($m->title) }}', '{{ $m->video_url }}', 'embed')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1">
                      <span class="material-symbols-rounded text-xs">play_circle</span>
                      <span>Watch</span>
                    </button>
                  @endif
                  <button onclick="deleteSubjectMaterial({{ $m->id }})" class="p-1 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded cursor-pointer" title="Delete Material">
                    <span class="material-symbols-rounded text-sm">delete</span>
                  </button>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="p-6 text-center text-muted italic">No study materials published yet. Click "Publish New Material" above to upload lecture notes or videos.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- INLINE MODAL FOR PREVIEWING VIDEOS (iframe embed OR HTML5 clip) -->
<div id="vlmVideoModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
  <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-2xl w-full p-4 space-y-3 shadow-2xl">
    <div class="flex justify-between items-center border-b border-slate-800 pb-2">
      <h4 id="vlmModalVideoTitle" class="font-bold text-title text-sm">Video Preview</h4>
      <button onclick="closeVlmVideoModal()" class="text-slate-400 hover:text-white text-sm cursor-pointer">✕ Close</button>
    </div>
    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
      <!-- YouTube / Vimeo embed -->
      <iframe id="vlmModalIframe" class="w-full h-full border-0" allowfullscreen></iframe>
      <!-- Uploaded MP4 / WebM clip -->
      <video id="vlmModalVideo" class="w-full h-full hidden" controls controlsList="nodownload">
        <source id="vlmModalVideoSrc" src="" type="video/mp4">
        Your browser does not support HTML5 video.
      </video>
    </div>
  </div>
</div>

<script>
  function toggleMaterialUploadForm(btn) {
    if (btn && btn.closest) {
      const parent = btn.closest('.tab-panel') || document;
      const panel = parent.querySelector('.materialUploadFormPanel') || document.getElementById('materialUploadFormPanel');
      if (panel) panel.classList.toggle('hidden');
    } else {
      const panel = document.getElementById('materialUploadFormPanel');
      if (panel) panel.classList.toggle('hidden');
    }
  }

  function toggleMaterialInputFields(el) {
    const parent = el ? el.closest('form') : document;
    const type = parent ? parent.querySelector('[name="material_type"]')?.value : document.getElementById('vlm_material_type')?.value || 'pdf';
    const fileContainer = (parent && parent.querySelector('#vlm_file_input_container')) || document.getElementById('vlm_file_input_container');
    const urlContainer = (parent && parent.querySelector('#vlm_url_input_container')) || document.getElementById('vlm_url_input_container');
    const fileInput = document.getElementById('vlm_file_input');
    const fileLabel = document.getElementById('vlm_file_label');

    if (type === 'video' || type === 'link') {
      // YouTube / Vimeo link or generic URL
      if (fileContainer) fileContainer.classList.add('hidden');
      if (urlContainer) urlContainer.classList.remove('hidden');
    } else if (type === 'video_clip') {
      // Direct MP4 / WebM upload
      if (fileContainer) fileContainer.classList.remove('hidden');
      if (urlContainer) urlContainer.classList.add('hidden');
      if (fileInput) fileInput.setAttribute('accept', 'video/mp4,video/webm,video/ogg');
      if (fileLabel) fileLabel.textContent = 'Upload Video Clip (MP4 / WebM — max 25 MB)';
    } else {
      // pdf / image / document
      if (fileContainer) fileContainer.classList.remove('hidden');
      if (urlContainer) urlContainer.classList.add('hidden');
      if (fileInput) fileInput.setAttribute('accept', '');
      if (fileLabel) fileLabel.textContent = 'Upload File (PDF / Image / Doc — up to 25 MB)';
    }
  }

  async function handleMaterialUploadSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]') || document.getElementById('btnSubmitMaterial');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-xs animate-spin">progress_activity</span> Publishing...';
    }

    const formData = new FormData(form);

    try {
      const resp = await fetch('/api/virtual-room/materials/upload', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      });
      const res = await resp.json();

      if (res.status === 'SUCCESS') {
        alert('✅ ' + res.message);
        form.reset();
        toggleMaterialUploadForm(btn);
        loadSubjectMaterials();
      } else {
        alert('❌ Error: ' + res.message);
      }
    } catch (e) {
      alert('❌ Upload failed: ' + e.message);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-xs">send</span> Publish Now';
      }
    }
  }

  async function loadSubjectMaterials() {
    const tbodies = document.querySelectorAll('.vlm-table-body, #materialsTableBody');
    if (!tbodies || !tbodies.length) return;

    const subjectId = '{{ $batchSubject->id }}';
    try {
      const resp = await fetch('/api/virtual-room/materials/' + subjectId);
      const res = await resp.json();

      let html = '';
      if (res.status === 'SUCCESS' && res.materials && res.materials.length > 0) {
        res.materials.forEach(m => {
          // Type badge
          let typeBadge = '<span class="px-2 py-0.5 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded font-bold text-xs">PDF</span>';
          if (m.material_type === 'video_clip') typeBadge = '<span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded font-bold text-xs">🎬 Clip</span>';
          else if (m.material_type === 'video')  typeBadge = '<span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded font-bold text-xs">▶ Video</span>';
          else if (m.material_type === 'image')  typeBadge = '<span class="px-2 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded font-bold text-xs">Image</span>';
          else if (m.material_type === 'link')   typeBadge = '<span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded font-bold text-xs">Link</span>';

          const alertBadge = m.is_pre_class_notice
            ? '<span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded font-bold text-[10px]">⚡ Urgent Alert</span>'
            : '<span class="text-slate-400 text-xs">Standard</span>';

          // Action button: play clip, watch embed, or preview file
          let actionBtn = '';
          const safeTitle = m.title.replace(/'/g, "\\'");
          if (m.material_type === 'video_clip' && m.file_path) {
            actionBtn = `<button onclick="openVlmVideoModal('${safeTitle}', '${m.file_path}', 'clip')" class="px-2.5 py-1 bg-violet-600 hover:bg-violet-700 text-white rounded text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1"><span class="material-symbols-rounded text-xs">play_circle</span> Play</button>`;
          } else if (m.file_path) {
            actionBtn = `<a href="${m.file_path}" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-xs font-bold border border-slate-700 transition-all no-underline flex items-center gap-1"><span class="material-symbols-rounded text-xs">visibility</span> Preview</a>`;
          } else if (m.video_url) {
            actionBtn = `<button onclick="openVlmVideoModal('${safeTitle}', '${m.video_url}', 'embed')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition-all shadow-sm cursor-pointer flex items-center gap-1"><span class="material-symbols-rounded text-xs">play_circle</span> Watch</button>`;
          }

          html += `
            <tr class="hover:bg-slate-900/30 transition-all">
              <td class="p-3 pl-4 font-bold text-emerald-400 text-xs">${m.experiment_or_topic_no}</td>
              <td class="p-3">
                <p class="font-bold text-title text-xs">${m.title}</p>
                ${m.pre_class_instruction ? `<p class="text-[11px] text-muted mt-0.5">${m.pre_class_instruction}</p>` : ''}
              </td>
              <td class="p-3 text-center">${typeBadge}</td>
              <td class="p-3 text-center font-mono text-xs text-title">${m.target_date ? m.target_date.substring(0, 10) : '—'}</td>
              <td class="p-3 text-center">${alertBadge}</td>
              <td class="p-3 pr-4 text-right flex justify-end gap-1.5 items-center">
                ${actionBtn}
                <button onclick="deleteSubjectMaterial(${m.id})" class="p-1 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded cursor-pointer" title="Delete Material">
                  <span class="material-symbols-rounded text-sm">delete</span>
                </button>
              </td>
            </tr>
          `;
        });
      } else {
        html = `<tr><td colspan="6" class="p-6 text-center text-muted italic">No study materials published yet. Click "Publish New Material" above to upload lecture notes or videos.</td></tr>`;
      }
      tbodies.forEach(tb => tb.innerHTML = html);
    } catch (e) {
      tbodies.forEach(tb => tb.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-rose-400 italic">Error loading materials.</td></tr>`);
    }
  }

  // Opens either an HTML5 <video> player (clip) or an iframe (embed)
  function openVlmVideoModal(title, url, mode) {
    const modalTitle = document.getElementById('vlmModalVideoTitle');
    const modalIframe = document.getElementById('vlmModalIframe');
    const modalVideo  = document.getElementById('vlmModalVideo');
    const modalVideoSrc = document.getElementById('vlmModalVideoSrc');
    const modal = document.getElementById('vlmVideoModal');

    if (modalTitle) modalTitle.innerText = title;

    if (mode === 'clip') {
      // Uploaded MP4 / WebM
      if (modalIframe) { modalIframe.src = ''; modalIframe.classList.add('hidden'); }
      if (modalVideoSrc) modalVideoSrc.src = url;
      if (modalVideo)  { modalVideo.load(); modalVideo.classList.remove('hidden'); }
    } else {
      // YouTube / Vimeo embed
      if (modalVideo)  { modalVideo.pause(); modalVideo.classList.add('hidden'); }
      if (modalVideoSrc) modalVideoSrc.src = '';
      if (modalIframe) { modalIframe.src = url; modalIframe.classList.remove('hidden'); }
    }

    if (modal) modal.classList.remove('hidden');
  }

  function closeVlmVideoModal() {
    const modalIframe   = document.getElementById('vlmModalIframe');
    const modalVideo    = document.getElementById('vlmModalVideo');
    const modalVideoSrc = document.getElementById('vlmModalVideoSrc');
    const modal = document.getElementById('vlmVideoModal');
    if (modalIframe)   modalIframe.src = '';
    if (modalVideo)    { modalVideo.pause(); modalVideo.classList.add('hidden'); }
    if (modalVideoSrc) modalVideoSrc.src = '';
    if (modalIframe)   modalIframe.classList.remove('hidden'); // reset for next open
    if (modal) modal.classList.add('hidden');
  }


  async function deleteSubjectMaterial(id) {
    if (!confirm('Are you sure you want to delete this material?')) return;
    try {
      const resp = await fetch('/api/virtual-room/materials/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
      });
      const res = await resp.json();
      if (res.status === 'SUCCESS') {
        loadSubjectMaterials();
      } else {
        alert('❌ Error: ' + res.message);
      }
    } catch (e) {
      alert('❌ Delete failed: ' + e.message);
    }
  }

  // Load materials when switching to materials tab
  document.addEventListener('DOMContentLoaded', () => {
    loadSubjectMaterials();
  });
</script>
