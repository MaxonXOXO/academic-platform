<!-- Live Online Staff Monitor & Real-Time Activity Log Widget -->
<div id="liveOnlineStaffWidgetCard" class="bg-slate-900/50 border border-slate-800/80 rounded-2xl shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300 overflow-hidden">
  
  <!-- Header Bar -->
  <div class="p-4 sm:p-5 border-b border-slate-800/80 bg-slate-950/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center shrink-0 shadow-inner">
        <span class="material-symbols-rounded text-emerald-400 text-xl animate-pulse">sensors</span>
      </div>
      <div>
        <div class="flex items-center gap-2.5 flex-wrap">
          <h2 class="font-black text-slate-100 text-base sm:text-lg tracking-tight">Live Online Staff &amp; Activity Monitor</h2>
          <span id="widgetOnlinePill" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-xs">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span id="widgetOnlinePillCount">0 Staff Online</span>
          </span>
        </div>
        <p class="text-[11px] text-slate-400 mt-0.5">Real-time session heartbeat monitor (5-min active TTL) and live staff audit activity log.</p>
      </div>
    </div>

    <!-- Right Controls: Auto-refresh & Manual Sync -->
    <div class="flex items-center gap-2.5 flex-wrap self-end md:self-auto">
      <div class="flex items-center bg-slate-950/80 border border-slate-800 rounded-lg p-1 text-[11px]">
        <span class="text-slate-400 px-2 flex items-center gap-1">
          <span class="material-symbols-rounded text-xs text-cyan-400">timelapse</span> Auto:
        </span>
        <select id="widgetRefreshInterval" onchange="changeLiveStaffRefreshInterval(this.value)" class="bg-slate-900 text-slate-200 border-none text-[11px] font-bold rounded px-1.5 py-0.5 focus:outline-none cursor-pointer">
          <option value="10">10s</option>
          <option value="15" selected>15s</option>
          <option value="30">30s</option>
          <option value="60">60s</option>
          <option value="0">Off</option>
        </select>
      </div>

      <button id="widgetManualRefreshBtn" onclick="refreshLiveStaffWidget(true)" class="px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-lg text-xs transition-premium shadow-md flex items-center gap-1.5 cursor-pointer">
        <span id="widgetRefreshIcon" class="material-symbols-rounded text-sm">sync</span>
        <span id="widgetRefreshText">Refresh</span>
      </button>
    </div>
  </div>

  <!-- KPI Quick Stats Strip -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 bg-slate-950/20 border-b border-slate-800/60">
    <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-3">
      <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shrink-0">
        <span class="material-symbols-rounded text-base">wifi_tethering</span>
      </div>
      <div class="overflow-hidden">
        <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block">Online Now</span>
        <span id="kpiStaffOnline" class="font-black text-white text-base leading-none">0</span>
      </div>
    </div>

    <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-3">
      <div class="p-2 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 shrink-0">
        <span class="material-symbols-rounded text-base">group</span>
      </div>
      <div class="overflow-hidden">
        <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block">Active Today</span>
        <span id="kpiStaffActiveToday" class="font-black text-white text-base leading-none">0</span>
      </div>
    </div>

    <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-3">
      <div class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shrink-0">
        <span class="material-symbols-rounded text-base">receipt_long</span>
      </div>
      <div class="overflow-hidden">
        <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block">Actions Today</span>
        <span id="kpiStaffActionsToday" class="font-black text-white text-base leading-none">0</span>
      </div>
    </div>

    <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-3">
      <div class="p-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 shrink-0">
        <span class="material-symbols-rounded text-base">schedule</span>
      </div>
      <div class="overflow-hidden">
        <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block">Server Time (IST)</span>
        <span id="kpiServerTimeIst" class="font-black text-amber-300 text-xs truncate leading-none block font-mono">--:--:--</span>
      </div>
    </div>
  </div>

  <!-- Search, Filter & Tab Controls -->
  <div class="p-4 bg-slate-900/40 border-b border-slate-800/60 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
    <!-- View Tabs -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-950/80 border border-slate-800/90 rounded-xl self-start">
      <button id="btnTabOnline" onclick="switchLiveStaffViewTab('online')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 cursor-pointer">
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Online Now (<span id="tabCountOnline">0</span>)
      </button>
      <button id="btnTabRecent" onclick="switchLiveStaffViewTab('recent')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-slate-200 cursor-pointer">
        <span class="w-2 h-2 rounded-full bg-amber-400/60"></span> Recent Today (<span id="tabCountRecent">0</span>)
      </button>
      <button id="btnTabAll" onclick="switchLiveStaffViewTab('all')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-slate-200 cursor-pointer">
        <span class="w-2 h-2 rounded-full bg-blue-400/60"></span> All Active (<span id="tabCountAll">0</span>)
      </button>
    </div>

    <!-- Search & Department Select -->
    <div class="flex items-center gap-2 flex-grow max-w-xl">
      <div class="relative flex-grow">
        <span class="material-symbols-rounded absolute left-3 top-2.5 text-slate-500 text-sm">search</span>
        <input type="text" id="widgetStaffSearchInput" oninput="filterLiveStaffList()" placeholder="Search by name, mobile, action..." class="w-full pl-9 pr-3 py-1.5 bg-slate-950/90 border border-slate-800 rounded-xl text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-emerald-500/50">
      </div>

      <select id="widgetDeptFilter" onchange="filterLiveStaffList()" class="bg-slate-950/90 border border-slate-800 rounded-xl text-xs text-slate-300 px-3 py-1.5 focus:outline-none focus:border-emerald-500/50 cursor-pointer shrink-0">
        <option value="">All Depts</option>
        <option value="CT">Computer (CT)</option>
        <option value="CE">Civil (CE)</option>
        <option value="ME">Mechanical (ME)</option>
        <option value="EEE">Electrical (EEE)</option>
        <option value="EL">Electronics (EL)</option>
        <option value="AU">Automobile (AU)</option>
        <option value="GEN_AIDED">Gen-Aided</option>
        <option value="GEN_SF">Gen-SF</option>
        <option value="ADMINISTRATION">Administration</option>
      </select>
    </div>
  </div>

  <!-- Live Table Container -->
  <div class="overflow-x-auto scrollbar-hidden">
    <table class="w-full text-left border-collapse text-xs">
      <thead>
        <tr class="bg-slate-950/80 border-b border-slate-800 text-slate-400 font-extrabold text-[11px] uppercase tracking-wider">
          <th class="py-3 px-4">Staff Member</th>
          <th class="py-3 px-4">Designation &amp; Dept</th>
          <th class="py-3 px-4">Session Presence</th>
          <th class="py-3 px-4">Latest Action Recorded</th>
          <th class="py-3 px-4">IP Address</th>
          <th class="py-3 px-4 text-center">Today Actions</th>
          <th class="py-3 px-4 text-right">Audit Trail</th>
        </tr>
      </thead>
      <tbody id="liveStaffTableBody" class="divide-y divide-slate-800/50 font-medium">
        <tr>
          <td colspan="7" class="py-12 text-center text-slate-500 font-bold">
            <div class="flex flex-col items-center justify-center gap-2">
              <span class="material-symbols-rounded text-2xl text-slate-600 animate-spin">progress_activity</span>
              <span>Loading live staff heartbeat and audit data...</span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Footer Info / Last Sync Timestamp -->
  <div class="p-3 bg-slate-950/40 border-t border-slate-800/60 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-500 font-mono">
    <div class="flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
      <span>Live Heartbeat Channel: <strong class="text-slate-400 font-bold">Active (5-Min Rolling Window)</strong></span>
    </div>
    <div>
      <span>Last Synced: <strong id="widgetLastSyncTime" class="text-slate-400 font-bold">Just now</strong></span>
    </div>
  </div>

</div>

<!-- Staff Individual Audit Trail Modal -->
<div id="staffAuditDetailModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl relative text-left">
    <!-- Modal Header -->
    <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between bg-slate-950/60">
      <div class="flex items-center gap-3">
        <div id="modalStaffAvatarWrap" class="w-10 h-10 rounded-full border border-slate-700 bg-slate-800 flex items-center justify-center overflow-hidden shrink-0 font-bold text-white text-sm">
          <!-- Staff Avatar -->
        </div>
        <div>
          <h3 id="modalStaffName" class="font-black text-slate-100 text-base leading-tight">Staff Name</h3>
          <p id="modalStaffMeta" class="text-[11px] text-slate-400 font-medium">Designation · Department · Mobile</p>
        </div>
      </div>
      <button onclick="closeStaffAuditModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition cursor-pointer">
        <span class="material-symbols-rounded text-lg">close</span>
      </button>
    </div>

    <!-- Modal Content: Audit Timeline -->
    <div class="p-4 sm:p-5 overflow-y-auto space-y-3.5 flex-grow custom-scrollbar">
      <div class="flex items-center justify-between pb-2 border-b border-slate-800/80">
        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
          <span class="material-symbols-rounded text-sm text-cyan-400">history</span> Recorded Audit Logs
        </span>
        <span id="modalAuditCountBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">0 Events</span>
      </div>

      <div id="modalAuditList" class="space-y-2.5">
        <!-- Rendered dynamically -->
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="p-3.5 bg-slate-950/60 border-t border-slate-800 flex items-center justify-between">
      <span class="text-[11px] text-slate-500 font-mono">Filter applied: Specific Staff Mobile</span>
      <button onclick="closeStaffAuditModal()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-lg transition cursor-pointer">
        Close
      </button>
    </div>
  </div>
</div>

<script>
  let liveStaffState = {
    onlineStaff: [],
    recentStaff: [],
    summary: {},
    currentTab: 'online', // 'online' | 'recent' | 'all'
    refreshTimer: null,
    intervalSeconds: 15,
    countdownTimer: null,
  };

  document.addEventListener('DOMContentLoaded', () => {
    initLiveStaffWidget();
  });

  function initLiveStaffWidget() {
    refreshLiveStaffWidget();
    startLiveStaffAutoRefresh();
    // Start second-by-second countdown updater for online expiry pills
    if (!liveStaffState.countdownTimer) {
      liveStaffState.countdownTimer = setInterval(updateLiveCountdowns, 1000);
    }
  }

  function changeLiveStaffRefreshInterval(val) {
    liveStaffState.intervalSeconds = parseInt(val, 10);
    startLiveStaffAutoRefresh();
  }

  function startLiveStaffAutoRefresh() {
    if (liveStaffState.refreshTimer) {
      clearInterval(liveStaffState.refreshTimer);
      liveStaffState.refreshTimer = null;
    }
    if (liveStaffState.intervalSeconds > 0) {
      liveStaffState.refreshTimer = setInterval(() => {
        refreshLiveStaffWidget(false);
      }, liveStaffState.intervalSeconds * 1000);
    }
  }

  function refreshLiveStaffWidget(isManual = false) {
    const icon = document.getElementById('widgetRefreshIcon');
    if (icon && isManual) {
      icon.classList.add('animate-spin');
    }

    fetch('/api/admin/online-staff-log', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(res => {
      if (!res.ok) throw new Error('Network error: ' + res.status);
      return res.json();
    })
    .then(data => {
      if (icon && isManual) {
        setTimeout(() => icon.classList.remove('animate-spin'), 400);
      }
      if (data.status === 'SUCCESS') {
        liveStaffState.onlineStaff = data.online_staff || [];
        liveStaffState.recentStaff = data.recent_staff || [];
        liveStaffState.summary = data.summary || {};
        updateLiveStaffUI();
      }
    })
    .catch(err => {
      console.warn('Error fetching live online staff log:', err);
      if (icon && isManual) {
        icon.classList.remove('animate-spin');
      }
    });
  }

  function updateLiveStaffUI() {
    const summary = liveStaffState.summary || {};
    const onlineCount = liveStaffState.onlineStaff.length;
    const recentCount = liveStaffState.recentStaff.length;
    const allCount = onlineCount + recentCount;

    // Update KPI counters
    const kpiOnline = document.getElementById('kpiStaffOnline');
    const kpiRecent = document.getElementById('kpiStaffActiveToday');
    const kpiActions = document.getElementById('kpiStaffActionsToday');
    const kpiTime = document.getElementById('kpiServerTimeIst');
    const pillCount = document.getElementById('widgetOnlinePillCount');
    const lastSync = document.getElementById('widgetLastSyncTime');

    if (kpiOnline) kpiOnline.textContent = onlineCount;
    if (kpiRecent) kpiRecent.textContent = summary.total_active_today || allCount;
    if (kpiActions) kpiActions.textContent = summary.total_actions_today || 0;
    if (kpiTime && summary.server_time_ist) kpiTime.textContent = summary.server_time_ist;
    if (pillCount) pillCount.textContent = `${onlineCount} Staff Online`;
    if (lastSync) lastSync.textContent = new Date().toLocaleTimeString('en-US', { hour12: true });

    // Update Tab count badges
    const tabOnline = document.getElementById('tabCountOnline');
    const tabRecent = document.getElementById('tabCountRecent');
    const tabAll = document.getElementById('tabCountAll');
    if (tabOnline) tabOnline.textContent = onlineCount;
    if (tabRecent) tabRecent.textContent = recentCount;
    if (tabAll) tabAll.textContent = allCount;

    // Also update sidebar badge if exists in admin desk
    const sidebarBadge = document.getElementById('sidebarOnlineStaffBadge');
    if (sidebarBadge) {
      sidebarBadge.textContent = onlineCount;
      if (onlineCount > 0) {
        sidebarBadge.className = "px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30";
      } else {
        sidebarBadge.className = "px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-800 text-slate-400 border border-slate-700";
      }
    }

    renderLiveStaffTable();
  }

  function switchLiveStaffViewTab(tab) {
    liveStaffState.currentTab = tab;

    const btnOnline = document.getElementById('btnTabOnline');
    const btnRecent = document.getElementById('btnTabRecent');
    const btnAll = document.getElementById('btnTabAll');

    const activeClasses = "bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shadow-xs";
    const inactiveClasses = "text-slate-400 hover:text-slate-200";

    [btnOnline, btnRecent, btnAll].forEach(b => {
      if (b) {
        b.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer " + inactiveClasses;
      }
    });

    if (tab === 'online' && btnOnline) {
      btnOnline.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer " + activeClasses;
    } else if (tab === 'recent' && btnRecent) {
      btnRecent.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer " + activeClasses;
    } else if (tab === 'all' && btnAll) {
      btnAll.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer " + activeClasses;
    }

    renderLiveStaffTable();
  }

  function filterLiveStaffList() {
    renderLiveStaffTable();
  }

  function getFilteredStaffList() {
    let list = [];
    if (liveStaffState.currentTab === 'online') {
      list = [...liveStaffState.onlineStaff];
    } else if (liveStaffState.currentTab === 'recent') {
      list = [...liveStaffState.recentStaff];
    } else {
      list = [...liveStaffState.onlineStaff, ...liveStaffState.recentStaff];
    }

    const searchInput = document.getElementById('widgetStaffSearchInput');
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';

    const deptSelect = document.getElementById('widgetDeptFilter');
    const dept = deptSelect ? deptSelect.value.trim().toUpperCase() : '';

    return list.filter(item => {
      // Branch filter
      if (dept !== '') {
        const itemBranch = (item.branch || '').toUpperCase();
        if (dept === 'GEN_AIDED' && !itemBranch.includes('AIDED') && itemBranch !== 'GEN_AIDED') return false;
        else if (dept === 'GEN_SF' && !itemBranch.includes('SF') && itemBranch !== 'GEN_SF') return false;
        else if (!itemBranch.includes(dept)) return false;
      }

      // Query filter
      if (query !== '') {
        const name = (item.name || '').toLowerCase();
        const mobile = (item.mobile_no || '').toLowerCase();
        const designation = (item.designation || '').toLowerCase();
        const branch = (item.branch || '').toLowerCase();
        const action = (item.latest_action?.action || '').toLowerCase();
        const target = (item.latest_action?.target_name || '').toLowerCase();
        const details = (item.latest_action?.details || '').toLowerCase();

        return name.includes(query) || mobile.includes(query) || designation.includes(query) ||
               branch.includes(query) || action.includes(query) || target.includes(query) || details.includes(query);
      }

      return true;
    });
  }

  function renderLiveStaffTable() {
    const tbody = document.getElementById('liveStaffTableBody');
    if (!tbody) return;

    const list = getFilteredStaffList();

    if (list.length === 0) {
      let emptyMsg = "No staff members currently online.";
      if (liveStaffState.currentTab === 'recent') {
        emptyMsg = "No recent staff activity records found for today.";
      } else if (liveStaffState.currentTab === 'all') {
        emptyMsg = "No active staff found matching your filter criteria.";
      }
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="py-12 text-center text-slate-500 font-bold">
            <div class="flex flex-col items-center justify-center gap-2">
              <span class="material-symbols-rounded text-3xl text-slate-600">person_off</span>
              <span>${emptyMsg}</span>
              <span class="text-[11px] text-slate-600 font-normal">Online status auto-updates every ${liveStaffState.intervalSeconds} seconds</span>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    let html = '';
    list.forEach(s => {
      const isOnline = !!s.is_online;
      const initials = (s.name || 'S').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
      
      // Avatar
      let avatarHtml = `<div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 text-slate-200 font-black text-xs flex items-center justify-center shadow-inner shrink-0">${initials}</div>`;
      if (s.photo_url) {
        avatarHtml = `<img src="${s.photo_url}" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-9 h-9 rounded-full bg-slate-800 border border-slate-700 text-slate-200 font-black text-xs flex items-center justify-center shadow-inner shrink-0\\'>${initials}</div>'" class="w-9 h-9 rounded-full object-cover border border-slate-700 shrink-0 shadow-inner">`;
      }

      // Branch color
      const branchBadge = getBranchBadgeMarkup(s.branch);

      // Presence Badge & Countdown
      let presenceHtml = '';
      if (isOnline) {
        const remaining = s.remaining_seconds || 0;
        const mm = Math.floor(remaining / 60);
        const ss = remaining % 60;
        const timeStr = `${mm}m ${ss < 10 ? '0' : ''}${ss}s`;
        presenceHtml = `
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1.5">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
              </span>
              <span class="font-extrabold text-emerald-400 text-xs">Online Now</span>
            </div>
            <span class="text-[10px] text-slate-400 font-mono" id="countdown_${s.mobile_no}" data-remaining="${remaining}">
              TTL: <strong class="text-slate-300">${timeStr}</strong>
            </span>
            <span class="text-[9px] text-slate-500">Last Ping: ${s.last_seen_human || 'Active'}</span>
          </div>
        `;
      } else {
        presenceHtml = `
          <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-slate-500 inline-block"></span>
              <span class="font-bold text-slate-400 text-xs">Offline</span>
            </div>
            <span class="text-[10px] text-slate-500 font-mono">Last seen: ${s.last_seen_human || 'Earlier today'}</span>
          </div>
        `;
      }

      // Action Badge
      const act = s.latest_action || {};
      const actTypeBadge = getActionTypeBadge(act.action);
      const actTime = act.time_ist ? `${act.time_ist} (${act.time_human || ''})` : 'Active';

      html += `
        <tr class="hover:bg-slate-900/40 transition-colors border-b border-slate-800/40">
          <!-- Staff Info -->
          <td class="py-3 px-4">
            <div class="flex items-center gap-3">
              ${avatarHtml}
              <div class="overflow-hidden">
                <span class="font-black text-slate-100 text-xs block leading-tight hover:text-cyan-400 transition cursor-pointer" onclick="openStaffAuditModal('${s.mobile_no}', '${escapeJs(s.name)}', '${escapeJs(s.designation)}', '${escapeJs(s.branch)}')">${escapeHtml(s.name)}</span>
                <span class="text-[10px] text-slate-400 font-mono block">${escapeHtml(s.mobile_no)}</span>
              </div>
            </div>
          </td>

          <!-- Designation & Dept -->
          <td class="py-3 px-4">
            <span class="font-bold text-slate-300 text-xs block">${escapeHtml(s.designation || 'Faculty')}</span>
            <div class="mt-0.5">${branchBadge}</div>
          </td>

          <!-- Session Presence -->
          <td class="py-3 px-4">
            ${presenceHtml}
          </td>

          <!-- Latest Action -->
          <td class="py-3 px-4 max-w-xs">
            <div class="flex flex-col gap-1">
              <div class="flex items-center gap-1.5 flex-wrap">
                ${actTypeBadge}
                <span class="text-[10px] text-slate-400 font-mono">${actTime}</span>
              </div>
              <span class="text-xs font-bold text-slate-200 block truncate" title="${escapeHtml(act.target_name || '')}">
                ${escapeHtml(act.target_name || 'System Portal')}
              </span>
              ${act.details ? `<p class="text-[10px] text-slate-400 line-clamp-1 leading-tight" title="${escapeHtml(act.details)}">${escapeHtml(act.details)}</p>` : ''}
            </div>
          </td>

          <!-- IP Address -->
          <td class="py-3 px-4">
            <span class="px-2 py-0.5 rounded bg-slate-950/80 border border-slate-800 text-[10px] font-mono text-slate-400 inline-block shadow-inner">
              ${escapeHtml(s.ip_address || '127.0.0.1')}
            </span>
          </td>

          <!-- Actions Today -->
          <td class="py-3 px-4 text-center">
            <span class="px-2.5 py-1 rounded-full text-xs font-black ${s.actions_today > 0 ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30' : 'bg-slate-800 text-slate-500 border border-slate-700/60'}">
              ${s.actions_today || 0}
            </span>
          </td>

          <!-- Audit Trail Action Button -->
          <td class="py-3 px-4 text-right">
            <button onclick="openStaffAuditModal('${s.mobile_no}', '${escapeJs(s.name)}', '${escapeJs(s.designation)}', '${escapeJs(s.branch)}')" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 hover:text-white text-slate-300 rounded-lg text-[11px] font-bold transition flex items-center gap-1 ml-auto cursor-pointer border border-slate-700 shadow-xs" title="View Full Activity Log">
              <span class="material-symbols-rounded text-xs text-cyan-400">receipt_long</span> History
            </button>
          </td>
        </tr>
      `;
    });

    tbody.innerHTML = html;
  }

  function updateLiveCountdowns() {
    if (!liveStaffState.onlineStaff || liveStaffState.onlineStaff.length === 0) return;

    liveStaffState.onlineStaff.forEach(s => {
      if (s.remaining_seconds > 0) {
        s.remaining_seconds--;
      }
      const el = document.getElementById(`countdown_${s.mobile_no}`);
      if (el) {
        const remaining = s.remaining_seconds;
        if (remaining <= 0) {
          el.innerHTML = `TTL: <strong class="text-rose-400">Expiring</strong>`;
        } else {
          const mm = Math.floor(remaining / 60);
          const ss = remaining % 60;
          const timeStr = `${mm}m ${ss < 10 ? '0' : ''}${ss}s`;
          el.innerHTML = `TTL: <strong class="text-slate-300">${timeStr}</strong>`;
        }
      }
    });
  }

  function getBranchBadgeMarkup(branch) {
    const b = (branch || '').toUpperCase();
    if (b.includes('CT')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30">CT</span>`;
    if (b.includes('CE')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-pink-500/10 text-pink-400 border border-pink-500/30">CE</span>`;
    if (b.includes('ME')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">ME</span>`;
    if (b.includes('EEE')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">EEE</span>`;
    if (b.includes('EL')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">EL</span>`;
    if (b.includes('AU')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">AU</span>`;
    if (b.includes('ADMIN')) return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30">ADMIN</span>`;
    return `<span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-teal-500/10 text-teal-400 border border-teal-500/30">${escapeHtml(b || 'GEN')}</span>`;
  }

  function getActionTypeBadge(action) {
    const a = (action || '').toLowerCase();
    if (a.includes('attendance') || a.includes('import')) {
      return `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">${escapeHtml(action)}</span>`;
    }
    if (a.includes('login') || a.includes('biometric')) {
      return `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${escapeHtml(action)}</span>`;
    }
    if (a.includes('password') || a.includes('dob') || a.includes('profile')) {
      return `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${escapeHtml(action)}</span>`;
    }
    if (a.includes('delete') || a.includes('remove') || a.includes('reset')) {
      return `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">${escapeHtml(action)}</span>`;
    }
    return `<span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">${escapeHtml(action || 'Activity')}</span>`;
  }

  function openStaffAuditModal(mobileNo, name, designation, branch) {
    const modal = document.getElementById('staffAuditDetailModal');
    const nameEl = document.getElementById('modalStaffName');
    const metaEl = document.getElementById('modalStaffMeta');
    const avatarWrap = document.getElementById('modalStaffAvatarWrap');
    const countBadge = document.getElementById('modalAuditCountBadge');
    const listEl = document.getElementById('modalAuditList');

    if (!modal) return;

    if (nameEl) nameEl.textContent = name;
    if (metaEl) metaEl.textContent = `${designation || 'Staff'} · ${branch || ''} · Mobile: ${mobileNo}`;
    if (avatarWrap) {
      const initials = (name || 'S').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
      avatarWrap.innerHTML = initials;
    }
    if (listEl) {
      listEl.innerHTML = `
        <div class="py-8 text-center text-slate-500 font-bold flex items-center justify-center gap-2">
          <span class="material-symbols-rounded text-lg animate-spin text-cyan-400">progress_activity</span>
          <span>Loading activity history for ${escapeHtml(name)}...</span>
        </div>
      `;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Query audit logs performed by this staff member
    fetch(`/api/audit-logs?performedBy=${encodeURIComponent(mobileNo)}`)
      .then(r => r.json())
      .then(data => {
        if (data.status === 'SUCCESS' && Array.isArray(data.logs)) {
          if (countBadge) countBadge.textContent = `${data.logs.length} Events`;
          if (data.logs.length === 0) {
            listEl.innerHTML = `
              <div class="p-6 text-center text-slate-500 font-medium bg-slate-950/40 rounded-xl border border-slate-800">
                No audit trail records found performed by this staff member.
              </div>
            `;
            return;
          }

          let html = '';
          data.logs.forEach(log => {
            const timeStr = new Date(log.created_at).toLocaleString('en-US', {
              month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true
            });
            const badge = getActionTypeBadge(log.action);

            html += `
              <div class="p-3 bg-slate-950/60 border border-slate-800/80 rounded-xl hover:border-slate-700 transition space-y-1.5">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <div class="flex items-center gap-2">
                    ${badge}
                    <span class="font-bold text-slate-200 text-xs">${escapeHtml(log.target_name || '')}</span>
                  </div>
                  <div class="flex items-center gap-2 text-[10px] text-slate-400 font-mono">
                    <span>${timeStr}</span>
                    <span class="px-1.5 py-0.5 rounded bg-slate-900 border border-slate-800 text-slate-500">${escapeHtml(log.ip_address || '-')}</span>
                  </div>
                </div>
                ${log.details ? `<p class="text-[11px] text-slate-300 font-sans leading-relaxed">${escapeHtml(log.details)}</p>` : ''}
              </div>
            `;
          });
          listEl.innerHTML = html;
        } else {
          listEl.innerHTML = `<div class="p-6 text-center text-rose-400 font-bold">Failed to load audit logs.</div>`;
        }
      })
      .catch(() => {
        listEl.innerHTML = `<div class="p-6 text-center text-rose-400 font-bold">Network request error.</div>`;
      });
  }

  function closeStaffAuditModal() {
    const modal = document.getElementById('staffAuditDetailModal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
  }

  function escapeJs(str) {
    if (!str) return '';
    return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
  }
</script>
