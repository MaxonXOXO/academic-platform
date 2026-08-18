<div class="space-y-6">
  <!-- Top Banner / Header -->
  <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-3">
      <div class="bg-blue-500/10 text-blue-400 p-3 rounded-xl">
        <span class="material-symbols-rounded text-2xl">manage_accounts</span>
      </div>
      <div>
        <h3 class="font-extrabold text-slate-100 text-base">My Profile & Security Settings</h3>
        <p class="text-xs text-slate-400 mt-0.5">Manage your personal account credentials, profile avatar, and view security activity logs.</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile & Photo Upload Card -->
    <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-5 flex flex-col justify-between">
      <div>
        <h4 class="font-extrabold text-slate-200 text-sm border-b border-slate-800/60 pb-3 mb-5 flex items-center gap-2">
          <span class="material-symbols-rounded text-teal-400 text-base">account_circle</span> Personal Details
        </h4>

        <div class="flex flex-col items-center text-center space-y-3">
          <div class="relative group cursor-pointer" title="Click to change profile picture">
            <div id="staffAvatarWrapper" class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-700 bg-slate-800 flex items-center justify-center shadow-xl relative transition-premium group-hover:border-blue-500">
              <img id="staffProfileImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-full h-full object-cover" style="object-position: center 15%; transform: scale(var(--avatar-zoom, 1.08));">
            </div>
            <label for="staffPhotoUploadInput" class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-xs font-bold text-center gap-1 p-1">
              <span class="material-symbols-rounded text-xl">photo_camera</span>
              <span style="font-size:10px;">Change Photo</span>
            </label>
            <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
          </div>

          <!-- Avatar Zoom & Framing Controls -->
          <div class="w-full max-w-[220px] bg-slate-900/90 p-2.5 rounded-xl border border-slate-800 space-y-2 text-left">
            <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
              <span class="flex items-center gap-1"><span class="material-symbols-rounded text-xs text-blue-400">zoom_in</span> Zoom:</span>
              <span id="avatarZoomVal" class="text-blue-400 font-mono">1.08x</span>
            </div>
            <input type="range" id="avatarZoomSlider" min="1.0" max="2.5" step="0.05" value="1.08" oninput="adjustStaffAvatarZoom(this.value)" class="w-full h-1 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-blue-500">
            
            <div class="flex justify-between items-center text-[10px] font-bold text-slate-400 pt-1 border-t border-slate-800/60">
              <span class="flex items-center gap-1"><span class="material-symbols-rounded text-xs text-teal-400">unfold_more</span> Vertical Focus:</span>
              <span id="avatarPosVal" class="text-teal-400 font-mono">15%</span>
            </div>
            <input type="range" id="avatarPosSlider" min="0" max="80" step="2" value="15" oninput="adjustStaffAvatarPos(this.value)" class="w-full h-1 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-teal-500">
          </div>

          <div id="staffPhotoUploadStatus" class="text-xs font-bold mt-1 text-emerald-400 hidden"></div>
          <div>
            <h3 class="font-extrabold text-white text-base leading-tight">{{ session('userName') }}</h3>
            <span class="font-bold text-teal-400 uppercase tracking-wider text-[11px] block mt-1 bg-teal-500/10 px-2.5 py-0.5 rounded-full border border-teal-500/20 inline-block">{{ session('userBranch') }} {{ session('userRole') }}</span>
          </div>
        </div>

        <div class="border-t border-slate-800/60 pt-4 mt-5 space-y-3 text-xs">
          <div class="flex justify-between items-center">
            <span class="text-slate-400">Mobile / Account ID:</span>
            <span class="font-mono font-bold text-slate-200">{{ session('userId') }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-400">Department / Branch:</span>
            <span class="font-bold text-slate-200">{{ session('userBranch') ?: 'Institutional' }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-400">Role Designation:</span>
            <span class="font-bold text-slate-200">{{ session('userRole') }}</span>
          </div>
        </div>
      </div>

      <div class="pt-3">
        <label for="staffPhotoUploadInput" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 hover:border-slate-700 rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-2">
          <span class="material-symbols-rounded text-sm">upload</span> Upload New Profile Photo
        </label>
      </div>
    </div>

    <!-- Password Change Card -->
    <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
      <h4 class="font-extrabold text-slate-200 border-b border-slate-800/60 pb-3 flex items-center gap-2 text-sm">
        <span class="material-symbols-rounded text-blue-400 text-base">lock_reset</span> Security Credentials
      </h4>

      <form id="staffPasswordChangeForm" onsubmit="handleStaffPasswordChange(event)" class="space-y-4">
        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Current Password</label>
          <div class="relative">
            <input type="password" id="staffCurrentPassword" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 text-xs focus:border-blue-500 outline-none transition-premium" placeholder="Enter current password">
            <button type="button" onclick="togglePasswordInputVisibility('staffCurrentPassword', this)" class="absolute right-3 top-2.5 text-slate-500 hover:text-slate-300">
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">New Password</label>
          <div class="relative">
            <input type="password" id="staffNewPassword" required minlength="4" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 text-xs focus:border-blue-500 outline-none transition-premium" placeholder="Enter new password (min 4 chars)">
            <button type="button" onclick="togglePasswordInputVisibility('staffNewPassword', this)" class="absolute right-3 top-2.5 text-slate-500 hover:text-slate-300">
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
          </div>
        </div>

        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Confirm New Password</label>
          <div class="relative">
            <input type="password" id="staffConfirmPassword" required minlength="4" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-100 text-xs focus:border-blue-500 outline-none transition-premium" placeholder="Re-enter new password">
            <button type="button" onclick="togglePasswordInputVisibility('staffConfirmPassword', this)" class="absolute right-3 top-2.5 text-slate-500 hover:text-slate-300">
              <span class="material-symbols-rounded text-base">visibility</span>
            </button>
          </div>
        </div>

        <div id="staffPasswordAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

        <button type="submit" id="btnSaveStaffPassword" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer shadow-lg flex items-center justify-center gap-2">
          <span class="material-symbols-rounded text-base">key</span> Update Password
        </button>
      </form>
    </div>

    <!-- Security Audit Logs Card -->
    <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl flex flex-col space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
        <h4 class="font-extrabold text-slate-200 flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-indigo-400 text-base">security</span> Security Audit Log
        </h4>
        <button type="button" onclick="loadSelfSecurityLogs()" class="text-xs text-indigo-400 hover:text-indigo-300 font-bold flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">sync</span> Refresh
        </button>
      </div>

      <div class="flex-grow max-h-[320px] overflow-y-auto scrollbar-hidden border border-slate-800/80 rounded-xl">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-900/80 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
              <th class="p-3">Time</th>
              <th class="p-3">Action</th>
              <th class="p-3">Details</th>
            </tr>
          </thead>
          <tbody id="selfSecurityLogsTable">
            <tr><td colspan="3" class="p-4 text-center text-slate-500">Querying account logs...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function togglePasswordInputVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('.material-symbols-rounded');
    if (input.type === 'password') {
      input.type = 'text';
      if (icon) icon.innerText = 'visibility_off';
    } else {
      input.type = 'password';
      if (icon) icon.innerText = 'visibility';
    }
  }

  function handleStaffPhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('photo', file);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const statusEl = document.getElementById('staffPhotoUploadStatus');

    if (statusEl) {
      statusEl.classList.remove('hidden');
      statusEl.className = 'text-xs font-bold mt-1 text-amber-400 block';
      statusEl.innerText = 'Uploading photo...';
    }

    fetch('/api/staff/update-photo', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      body: formData
    })
    .then(async res => {
      const data = await res.json().catch(() => ({ status: 'ERROR', message: 'Invalid response from server.' }));
      if (res.ok && data.status === 'SUCCESS') {
        const photoUrl = data.photo_url + '?t=' + new Date().getTime();
        document.querySelectorAll('#staffProfileImg, #sidebarAvatarContainer img, aside img.rounded-full, #sidebarStaffImg').forEach(img => {
          img.src = photoUrl;
        });

        if (statusEl) {
          statusEl.className = 'text-xs font-bold mt-1 text-emerald-400 block';
          statusEl.innerText = 'Photo updated successfully!';
          setTimeout(() => statusEl.classList.add('hidden'), 4000);
        }

        if (typeof showGlobalMessage === 'function') {
          showGlobalMessage('Profile photo updated successfully!');
        }
      } else {
        if (statusEl) {
          statusEl.className = 'text-xs font-bold mt-1 text-rose-400 block';
          statusEl.innerText = data.message || 'Photo upload failed.';
        }
      }
    })
    .catch(err => {
      console.error('Photo upload error:', err);
      if (statusEl) {
        statusEl.className = 'text-xs font-bold mt-1 text-rose-400 block';
        statusEl.innerText = 'Error uploading photo. Please check file format and size.';
      }
    });
  }

  function handleStaffPasswordChange(event) {
    event.preventDefault();

    const oldPassword = document.getElementById('staffCurrentPassword').value.trim();
    const newPassword = document.getElementById('staffNewPassword').value.trim();
    const confirmPassword = document.getElementById('staffConfirmPassword').value.trim();
    const alertEl = document.getElementById('staffPasswordAlert');
    const btn = document.getElementById('btnSaveStaffPassword');

    if (newPassword !== confirmPassword) {
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-950/40 text-rose-400 border-rose-900 block';
        alertEl.innerText = 'New password and confirmation password do not match.';
      }
      return;
    }

    if (newPassword.length < 4) {
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-950/40 text-rose-400 border-rose-900 block';
        alertEl.innerText = 'New password must be at least 4 characters long.';
      }
      return;
    }

    if (btn) btn.disabled = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/api/staff/change-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      body: JSON.stringify({
        oldPassword: oldPassword,
        newPassword: newPassword
      })
    })
    .then(res => res.json())
    .then(data => {
      if (btn) btn.disabled = false;
      if (data.status === 'SUCCESS') {
        if (alertEl) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl text-xs font-bold border bg-emerald-950/40 text-emerald-400 border-emerald-900 block';
          alertEl.innerText = 'Password updated successfully!';
        }
        document.getElementById('staffPasswordChangeForm').reset();
        loadSelfSecurityLogs();

        if (typeof showGlobalMessage === 'function') {
          showGlobalMessage('Password updated successfully!');
        }
      } else {
        if (alertEl) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-950/40 text-rose-400 border-rose-900 block';
          alertEl.innerText = data.message || 'Failed to change password.';
        }
      }
    })
    .catch(() => {
      if (btn) btn.disabled = false;
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-950/40 text-rose-400 border-rose-900 block';
        alertEl.innerText = 'Network error updating password.';
      }
    });
  }

  function loadSelfSecurityLogs() {
    const tbody = document.getElementById('selfSecurityLogsTable') || document.getElementById('securityLogsTable');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500 font-bold">Querying security log records...</td></tr>`;

    fetch('/api/audit-logs?targetId={{ session("userId") }}')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          tbody.innerHTML = "";
          if (!data.logs || data.logs.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No account security logs found.</td></tr>`;
            return;
          }
          data.logs.forEach(log => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/30 transition-premium";
            const date = new Date(log.created_at).toLocaleString();
            tr.innerHTML = `
              <td class="p-3 text-slate-400 font-mono text-[11px]">${date}</td>
              <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
              <td class="p-3 text-slate-300 text-[11px]">${log.details || ''}</td>
            `;
            tbody.appendChild(tr);
          });
        } else {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-rose-400 font-bold">Failed to load logs.</td></tr>`;
        }
      })
      .catch(() => {
        tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-rose-400 font-bold">Error querying logs.</td></tr>`;
      });
  }

  function adjustStaffAvatarZoom(val) {
    const zoom = parseFloat(val).toFixed(2);
    document.querySelectorAll('#avatarZoomVal, #mobileZoomVal').forEach(el => el.innerText = zoom + 'x');
    document.querySelectorAll('#avatarZoomSlider, #mobileZoomSlider').forEach(el => el.value = val);
    localStorage.setItem('staffAvatarZoom', val);
    applyStaffAvatarAdjustments();
  }

  function adjustStaffAvatarPos(val) {
    document.querySelectorAll('#avatarPosVal, #mobilePosVal').forEach(el => el.innerText = val + '%');
    document.querySelectorAll('#avatarPosSlider, #mobilePosSlider').forEach(el => el.value = val);
    localStorage.setItem('staffAvatarPos', val);
    applyStaffAvatarAdjustments();
  }

  function resetStaffAvatarAdjustments() {
    adjustStaffAvatarZoom(1.08);
    adjustStaffAvatarPos(15);
  }

  function applyStaffAvatarAdjustments() {
    const zoom = localStorage.getItem('staffAvatarZoom') || '1.08';
    const pos = localStorage.getItem('staffAvatarPos') || '15';
    
    // Sync slider controls if present
    document.querySelectorAll('#avatarZoomVal, #mobileZoomVal').forEach(el => el.innerText = parseFloat(zoom).toFixed(2) + 'x');
    document.querySelectorAll('#avatarZoomSlider, #mobileZoomSlider').forEach(el => el.value = zoom);
    document.querySelectorAll('#avatarPosVal, #mobilePosVal').forEach(el => el.innerText = pos + '%');
    document.querySelectorAll('#avatarPosSlider, #mobilePosSlider').forEach(el => el.value = pos);

    document.querySelectorAll('#staffProfileImg, .avatar-mobile, #staffBannerPhoto, #staffProfileTabPhoto, #sidebarAvatarContainer img, aside img.rounded-full, #sidebarStaffImg').forEach(img => {
      img.style.objectFit = 'cover';
      img.style.objectPosition = `center ${pos}%`;
      img.style.transform = `scale(${zoom})`;
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyStaffAvatarAdjustments);
  } else {
    applyStaffAvatarAdjustments();
  }

  window.addEventListener('pageshow', function (event) {
    applyStaffAvatarAdjustments();
    if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
      fetch('/api/system/session-check', { method: 'GET', cache: 'no-store' })
        .then(r => r.json())
        .then(data => {
          if (!data || data.status !== 'ACTIVE') {
            window.location.replace('/');
          }
        })
        .catch(() => {
          window.location.replace('/');
        });
    }
  });
</script>
