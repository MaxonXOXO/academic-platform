<!-- =========================================================================
     CARMIE - Carmel-linx Campus Guide & Academic Mentor (Widget Partial)
     Carmie is a friendly, smart female academic guide for Carmel Polytechnic.
     ========================================================================= -->
<div id="carmieWidgetContainer" class="no-print select-none">

  <!-- SVG AVATAR DEFINITION TEMPLATE -->
  <svg class="hidden">
    <defs>
      <linearGradient id="carmieAvatarBgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#f43f5e"/>
        <stop offset="50%" stop-color="#a855f7"/>
        <stop offset="100%" stop-color="#6366f1"/>
      </linearGradient>
      <linearGradient id="carmieSkinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#fff1eb"/>
        <stop offset="100%" stop-color="#fcd5c0"/>
      </linearGradient>
      <linearGradient id="carmieHairGrad" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#4a2018"/>
        <stop offset="100%" stop-color="#24100c"/>
      </linearGradient>
    </defs>
  </svg>

  <!-- FLOATING ACTION BUTTON (BOTTOM-RIGHT) -->
  <div id="carmieFabWrapper" class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3">
    <!-- Initial Greeting Balloon (Auto-dismisses or closeable) -->
    <div id="carmieGreetingBubble" class="hidden sm:flex items-center gap-2.5 bg-slate-900/95 border border-rose-500/40 text-slate-100 text-xs font-semibold px-3.5 py-2 rounded-2xl shadow-xl shadow-rose-950/40 backdrop-blur-md animate-bounce cursor-pointer" onclick="toggleCarmieChat()">
      <span class="text-base">🌸</span>
      <span>Need help? Ask <strong>Carmie</strong>!</span>
      <button type="button" onclick="dismissCarmieGreeting(event)" class="text-slate-400 hover:text-white ml-1 text-sm leading-none">&times;</button>
    </div>

    <!-- Toggle Button -->
    <button type="button" 
            id="carmieFabBtn"
            onclick="toggleCarmieChat()" 
            aria-label="Ask Carmie"
            class="relative w-14 h-14 rounded-full bg-slate-900 text-white shadow-xl shadow-slate-950/50 border-2 border-white flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 cursor-pointer group overflow-hidden">
      
      <!-- Sparkle pulse ring -->
      <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 opacity-40 blur-sm group-hover:opacity-75 animate-pulse transition duration-300 pointer-events-none"></span>

      <!-- Carmie Face Avatar (Visible when closed) -->
      <div id="carmieFabAvatar" class="relative z-10 w-full h-full rounded-full overflow-hidden flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
        <img src="{{ asset('carmie_icon.png') }}" alt="Carmie" class="w-full h-full object-cover rounded-full select-none pointer-events-none">
      </div>

      <!-- Close Icon (Visible when open) -->
      <span id="carmieFabClose" class="hidden material-symbols-rounded text-2xl relative z-10 text-white">close</span>

      <!-- Online Dot Indicator -->
      <span class="absolute top-0.5 right-0.5 w-3.5 h-3.5 bg-emerald-400 border-2 border-slate-900 rounded-full z-20 shadow-xs"></span>
    </button>
  </div>

  <!-- CHAT DRAWER / WINDOW -->
  <div id="carmieChatModal" 
       class="hidden fixed bottom-6 right-6 sm:bottom-24 sm:right-6 z-[10000] w-[calc(100vw-32px)] sm:w-[410px] h-[580px] max-h-[calc(100vh-100px)] bg-slate-900/95 border border-slate-700/80 rounded-3xl shadow-2xl shadow-black/80 flex flex-col overflow-hidden backdrop-blur-xl transition-all duration-300 animate-fade-in font-sans">
    
    <!-- HEADER -->
    <div class="px-4 py-3.5 bg-slate-950/80 border-b border-slate-800 flex items-center justify-between shrink-0">
      <div class="flex items-center gap-3">
        <div class="relative w-11 h-11 rounded-full overflow-hidden shadow-md border-2 border-white shrink-0 bg-slate-900 flex items-center justify-center">
          <img src="{{ asset('carmie_icon.png') }}" alt="Carmie" class="w-full h-full object-cover rounded-full select-none">
          <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-slate-900 rounded-full z-20"></span>
        </div>
        <div>
          <div class="flex items-center gap-1.5">
            <h3 class="font-extrabold text-sm text-white tracking-tight">Carmie</h3>
            <span class="text-xs">🌸</span>
            <span id="carmieEngineBadge" class="text-[9.5px] font-mono font-bold px-1.5 py-0.2 rounded bg-rose-500/15 text-rose-300 border border-rose-500/30">
              Campus Mentor
            </span>
          </div>
          <p class="text-[11px] text-slate-400 leading-tight">Carmel-linx Academic Companion</p>
        </div>
      </div>

      <div class="flex items-center gap-1 text-slate-400">
        <button type="button" onclick="clearCarmieHistory()" title="Clear conversation" class="p-1.5 hover:text-slate-200 hover:bg-slate-800/80 rounded-xl transition cursor-pointer">
          <span class="material-symbols-rounded text-lg">delete_sweep</span>
        </button>
        <button type="button" onclick="toggleCarmieChat()" title="Close Carmie" class="p-1.5 hover:text-slate-200 hover:bg-slate-800/80 rounded-xl transition cursor-pointer">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>
    </div>

    <!-- MAIN CATEGORY / REVISION TABS -->
    <div class="px-3 pt-2 pb-1.5 bg-slate-950/80 border-b border-slate-800 flex items-center gap-1.5 overflow-x-auto no-scrollbar shrink-0" id="carmieCategoryTabs">
      <button type="button" onclick="setCarmieCategory('all')" id="carmieTab_all" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-white bg-rose-600 shadow-xs flex items-center gap-1 cursor-pointer shrink-0">
        <span>⚡</span><span>Quick Help</span>
      </button>
      <button type="button" onclick="setCarmieCategory('2021')" id="carmieTab_2021" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📘</span><span>Rev 2021</span>
      </button>
      <button type="button" onclick="setCarmieCategory('2026')" id="carmieTab_2026" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📙</span><span>Rev 2026</span>
      </button>
      <button type="button" onclick="setCarmieCategory('attainment')" id="carmieTab_attainment" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📊</span><span>Attainment</span>
      </button>
    </div>

    <!-- QUICK STARTER SUGGESTION CHIPS (DYNAMIC) -->
    <div class="px-3.5 py-2 bg-slate-950/40 border-b border-slate-800/60 overflow-x-auto flex items-center gap-1.5 no-scrollbar shrink-0" id="carmieChipsContainer">
      <!-- Populated via renderCarmieChips() -->
    </div>

    <!-- CHAT MESSAGES SCROLL AREA -->
    <div id="carmieMessages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs select-text">
      <!-- Default Welcome Message -->
      <div class="flex items-start gap-2.5">
        <div class="w-7 h-7 rounded-full overflow-hidden shrink-0 shadow-sm border-2 border-white bg-slate-900 flex items-center justify-center">
          <img src="{{ asset('carmie_icon.png') }}" alt="Carmie" class="w-full h-full object-cover rounded-full select-none">
        </div>
        <div class="max-w-[85%] bg-slate-800/80 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3 text-slate-200 leading-relaxed space-y-1.5 shadow-sm">
          <p>Hi! I'm <strong>Carmie</strong>, your Carmel-linx academic companion! 🌸</p>
          <p class="text-slate-300 text-[11.5px]">
            Ask me anytime you're stuck or need help with assignments, CO-PO matrices, attendance, marks, or reports.
          </p>
          <p class="text-slate-400 text-[10.5px] italic pt-1 border-t border-slate-700/60">
            Click any suggestion chip above or type your question below!
          </p>
        </div>
      </div>
    </div>

    <!-- TYPING INDICATOR -->
    <div id="carmieTypingIndicator" class="hidden px-4 py-2 bg-slate-950/40 text-slate-400 text-xs flex items-center gap-2 shrink-0">
      <span class="material-symbols-rounded text-sm animate-spin text-rose-400">autorenew</span>
      <span class="text-[11px] font-medium text-rose-200">Carmie is preparing your answer... 🌸</span>
    </div>

    <!-- INPUT BAR -->
    <form id="carmieForm" onsubmit="handleCarmieSubmit(event)" class="p-3 bg-slate-950/90 border-t border-slate-800 flex items-center gap-2 shrink-0">
      <input type="text"
             id="carmieInput"
             placeholder="Ask Carmie a question..."
             autocomplete="off"
             class="flex-1 bg-slate-900 border border-slate-700/80 hover:border-slate-600 focus:border-rose-500 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 outline-none transition-colors shadow-inner">
      <button type="submit" 
              id="carmieSubmitBtn"
              class="w-10 h-10 rounded-xl bg-gradient-to-r from-rose-600 via-purple-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white flex items-center justify-center transition-all shadow-md active:scale-95 cursor-pointer shrink-0 disabled:opacity-50">
        <span class="material-symbols-rounded text-lg">send</span>
      </button>
    </form>
  </div>

</div>

<!-- CARMIE CLIENT-SIDE ENGINE SCRIPT -->
<script>
  let carmieChatOpen = false;
  let carmieHistory = [];

  const CARMIE_AVATAR_HTML = `
    <div class="w-7 h-7 rounded-full overflow-hidden shrink-0 shadow-sm border-2 border-white bg-slate-900 flex items-center justify-center">
      <img src="{{ asset('carmie_icon.png') }}" alt="Carmie" class="w-full h-full object-cover rounded-full select-none pointer-events-none">
    </div>
  `;

  function toggleCarmieChat() {
    carmieChatOpen = !carmieChatOpen;
    const modal = document.getElementById('carmieChatModal');
    const fabAvatar = document.getElementById('carmieFabAvatar');
    const fabClose = document.getElementById('carmieFabClose');
    const greeting = document.getElementById('carmieGreetingBubble');
    
    if (greeting) greeting.classList.add('hidden');

    if (carmieChatOpen) {
      modal.classList.remove('hidden');
      if (fabAvatar) fabAvatar.classList.add('hidden');
      if (fabClose) fabClose.classList.remove('hidden');
      setTimeout(() => {
        const inp = document.getElementById('carmieInput');
        if (inp) inp.focus();
      }, 100);
    } else {
      modal.classList.add('hidden');
      if (fabAvatar) fabAvatar.classList.remove('hidden');
      if (fabClose) fabClose.classList.add('hidden');
    }
  }

  function dismissCarmieGreeting(e) {
    if (e) e.stopPropagation();
    const g = document.getElementById('carmieGreetingBubble');
    if (g) g.remove();
    localStorage.setItem('carmie_greeting_dismissed', 'true');
  }

  function sendCarmieQuickPrompt(text) {
    const inp = document.getElementById('carmieInput');
    if (inp) {
      inp.value = text;
      handleCarmieSubmit(new Event('submit'));
    }
  }

  function clearCarmieHistory() {
    carmieHistory = [];
    const container = document.getElementById('carmieMessages');
    if (container) {
      container.innerHTML = `
        <div class="flex items-start gap-2.5">
          ${CARMIE_AVATAR_HTML}
          <div class="max-w-[85%] bg-slate-800/80 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3 text-slate-200 leading-relaxed shadow-sm">
            <p>Chat cleared! What would you like to explore next? 🌸</p>
          </div>
        </div>
      `;
    }
  }

  function appendCarmieMessage(sender, text, actionLabel = null, actionRoute = null, source = null) {
    const container = document.getElementById('carmieMessages');
    if (!container) return;

    const row = document.createElement('div');
    row.className = sender === 'user' ? 'flex items-start justify-end gap-2.5' : 'flex items-start gap-2.5';

    let bubbleHtml = '';

    if (sender === 'user') {
      bubbleHtml = `
        <div class="max-w-[85%] bg-gradient-to-r from-rose-600 via-purple-600 to-indigo-600 text-white rounded-2xl rounded-tr-sm p-3 shadow-md text-xs leading-relaxed">
          ${escapeHtml(text)}
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-600 text-slate-200 flex items-center justify-center shrink-0 text-xs font-bold shadow-sm">
          <span class="material-symbols-rounded text-sm">person</span>
        </div>
      `;
    } else {
      // Parse markdown bold, lists, and headers
      let formatted = formatCarmieMarkdown(text);

      let actionHtml = '';
      if (actionLabel && actionRoute && actionRoute !== '#') {
        actionHtml = `
          <div class="pt-2 mt-2 border-t border-slate-700/60">
            <a href="${actionRoute}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-600/20 hover:bg-rose-600/35 text-rose-200 border border-rose-500/30 text-[11px] font-bold transition-all shadow-xs">
              <span>✨ ${escapeHtml(actionLabel)}</span>
              <span class="material-symbols-rounded text-xs">arrow_forward</span>
            </a>
          </div>
        `;
      }

      let sourceBadge = '';
      if (source === 'local_playbook') {
        sourceBadge = `<span class="inline-block mt-2 text-[9.5px] font-mono text-emerald-400/80 bg-emerald-950/40 px-1.5 py-0.2 rounded border border-emerald-800/40">Verified Carmel-linx Guide ⚡</span>`;
      } else if (source === 'gemini_ai') {
        sourceBadge = `<span class="inline-block mt-2 text-[9.5px] font-mono text-rose-300/80 bg-rose-950/40 px-1.5 py-0.2 rounded border border-rose-800/40">Powered by Gemini Flash ✨</span>`;
      }

      bubbleHtml = `
        ${CARMIE_AVATAR_HTML}
        <div class="max-w-[85%] bg-slate-800/90 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3.5 text-slate-200 leading-relaxed shadow-sm space-y-2">
          <div class="carmie-rendered-markdown leading-relaxed text-slate-200">${formatted}</div>
          ${actionHtml}
          ${sourceBadge}
        </div>
      `;
    }

    row.innerHTML = bubbleHtml;
    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
  }

  function formatCarmieMarkdown(text) {
    if (!text) return '';
    let t = text;

    // Headers
    t = t.replace(/^### (.*$)/gim, '<h4 class="font-bold text-rose-300 text-xs mt-1 mb-1">$1</h4>');
    t = t.replace(/^## (.*$)/gim, '<h3 class="font-bold text-white text-xs mt-1 mb-1">$1</h3>');

    // Bold
    t = t.replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-white">$1</strong>');
    
    // Italics
    t = t.replace(/\*(.*?)\*/gim, '<em class="text-slate-300 italic">$1</em>');

    // Bullet points
    t = t.replace(/^• (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-bold shrink-0 mt-0.5">•</span><span>$1</span></div>');
    t = t.replace(/^(\d+)\. (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-mono font-bold shrink-0">$1.</span><span>$2</span></div>');

    // Line breaks
    t = t.replace(/\n\n/g, '<div class="h-1.5"></div>');

    return t;
  }

  let activeCarmieCategory = 'all';

  const CARMIE_CHIP_SETS = {
    'all': [
      { label: '🚀 Project 2021 (75 CIA + 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '🎤 Seminar 2021 (75 CIA Only)', query: 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?', color: 'blue' },
      { label: '📐 Drawing 2021 (Lab Criteria)', query: 'How does Revision 2021 Drawing class practical evaluation work?', color: 'blue' },
      { label: '📊 Online Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '🎯 CO-PO Autosave in Rev 2026', query: 'How does CO-PO matrix autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📅 Attendance & Subject Log', query: 'How do I submit hourly attendance and subject log?', color: 'slate' },
      { label: '💡 What can I do here?', query: 'Where am I and what can I do on this page?', color: 'rose' }
    ],
    '2021': [
      { label: '🚀 Project 2021 (75 CIA & 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '🎤 Seminar 2021 (75 CIA Only)', query: 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?', color: 'blue' },
      { label: '📐 Drawing 2021 (Lab Criteria)', query: 'How does Revision 2021 Drawing class practical evaluation work?', color: 'blue' },
      { label: '📑 Print Group-Wise Breakdown', query: 'How do I print the Group-Wise Breakdown for separate filing in Major Project?', color: 'blue' },
      { label: '👥 Group Common CIA & ESE (60M / 27.5M)', query: 'How to use 1-click group common scoring in Major Project 2021?', color: 'blue' },
      { label: '🔬 Lab 2021 (37.5 Formative + Tests)', query: 'Explain Revision 2021 Lab 75 CIA split-up with rough record and fair record', color: 'blue' }
    ],
    '2026': [
      { label: '🎯 CO-PO Autosave in Rev 2026', query: 'How does CO-PO matrix autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📝 Theory 40 CIE Breakdown', query: 'Explain the Revision 2026 theory 40 CIE marks breakdown', color: 'emerald' },
      { label: '📚 Table 2.2 Self-Learning', query: 'How to configure Table 2.2 self-learning marks in Rev 2026?', color: 'emerald' },
      { label: '🔬 Practicum 90-Hour Workspace', query: 'How does Revision 2026 Practicum 90-Hour combined workspace operate?', color: 'emerald' },
      { label: '📁 Course File 16-Item Checklist', query: 'How do I prepare the course file checklist for HOD approval?', color: 'slate' }
    ],
    'attainment': [
      { label: '📊 Run Online Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '📈 80% Direct + 20% Indirect Formula', query: 'How is the 80% Direct + 20% Indirect Attainment formula calculated in Carmel-Linx?', color: 'purple' },
      { label: '📁 NBA Criterion 3 Compliance Dossier', query: 'How to export the NBA Criterion 3 attainment reports for department audit?', color: 'purple' },
      { label: '🎯 Universal 5-Classroom Attainment', query: 'How does attainment work across Theory, Lab, Seminar, Project, and Drawing?', color: 'purple' }
    ]
  };

  function setCarmieCategory(cat) {
    activeCarmieCategory = cat;
    const tabs = ['all', '2021', '2026', 'attainment'];
    tabs.forEach(t => {
      const btn = document.getElementById('carmieTab_' + t);
      if (!btn) return;
      if (t === cat) {
        btn.className = "carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-white bg-rose-600 shadow-xs flex items-center gap-1 cursor-pointer shrink-0";
      } else {
        btn.className = "carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0";
      }
    });

    renderCarmieChips(cat);
  }

  function renderCarmieChips(cat) {
    const container = document.getElementById('carmieChipsContainer');
    if (!container) return;

    const chips = CARMIE_CHIP_SETS[cat] || CARMIE_CHIP_SETS['all'];
    let html = '';

    chips.forEach(chip => {
      let colorClass = "bg-slate-800 hover:bg-slate-700 text-slate-300 border-slate-700";
      if (chip.color === 'rose') {
        colorClass = "bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border-rose-500/30";
      } else if (chip.color === 'blue') {
        colorClass = "bg-sky-500/10 hover:bg-sky-500/20 text-sky-300 border-sky-500/30";
      } else if (chip.color === 'emerald') {
        colorClass = "bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border-emerald-500/30";
      } else if (chip.color === 'purple') {
        colorClass = "bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border-purple-500/30";
      }

      const escapedLabel = escapeHtml(chip.label);
      const escapedQuery = chip.query.replace(/'/g, "\\'");
      html += `<button type="button" onclick="sendCarmieQuickPrompt('${escapedQuery}')" class="carmie-chip whitespace-nowrap text-[10.5px] font-semibold ${colorClass} border px-2.5 py-1 rounded-full transition-all cursor-pointer shrink-0">${escapedLabel}</button>`;
    });

    container.innerHTML = html;
  }

  function escapeHtml(string) {
    const el = document.createElement('div');
    el.innerText = string || '';
    return el.innerHTML;
  }

  function handleCarmieSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('carmieInput');
    const submitBtn = document.getElementById('carmieSubmitBtn');
    const typingIndicator = document.getElementById('carmieTypingIndicator');

    if (!input || !input.value.trim()) return;

    const query = input.value.trim();
    input.value = '';

    appendCarmieMessage('user', query);

    if (typingIndicator) typingIndicator.classList.remove('hidden');
    if (submitBtn) submitBtn.disabled = true;

    // Collect active context
    const currentUrl = window.location.pathname + window.location.search;
    let subjectCode = '';
    const codeEl = document.querySelector('[data-subject-code]');
    if (codeEl) subjectCode = codeEl.getAttribute('data-subject-code');

    fetch('/api/carmie/ask', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        query: query,
        current_url: currentUrl,
        subject_code: subjectCode,
        category: activeCarmieCategory
      })
    })
    .then(res => res.json())
    .then(data => {
      if (typingIndicator) typingIndicator.classList.add('hidden');
      if (submitBtn) submitBtn.disabled = false;

      if (data.status === 'SUCCESS' || data.reply) {
        appendCarmieMessage('carmie', data.reply, data.action_label, data.action_route, data.source);
      } else {
        appendCarmieMessage('carmie', "Sorry, I had trouble processing that. Could you try asking in a slightly different way?");
      }
    })
    .catch(err => {
      if (typingIndicator) typingIndicator.classList.add('hidden');
      if (submitBtn) submitBtn.disabled = false;
      appendCarmieMessage('carmie', "Network error connecting to Carmie: " + err.message);
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    renderCarmieChips('all');

    // Check if greeting was already dismissed previously
    if (localStorage.getItem('carmie_greeting_dismissed') === 'true') {
      const g = document.getElementById('carmieGreetingBubble');
      if (g) g.remove();
    } else {
      setTimeout(() => {
        dismissCarmieGreeting();
      }, 9000);
    }
  });
</script>
