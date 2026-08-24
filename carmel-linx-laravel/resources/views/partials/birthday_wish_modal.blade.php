<!-- Staff Birthday Wish Card Popup Modal Partial -->
<style>
  #staffBirthdayModalOverlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 999999 !important;
    background: rgba(2, 6, 23, 0.88) !important;
    backdrop-filter: blur(12px) !important;
    -webkit-backdrop-filter: blur(12px) !important;
    display: none;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    overflow-y: auto !important;
    box-sizing: border-box !important;
  }

  #staffBirthdayModalOverlay.show-modal {
    display: flex !important;
  }

  .bday-card-container {
    background: linear-gradient(165deg, #0f172a 0%, #1e1b4b 50%, #020617 100%) !important;
    border: 2px solid rgba(245, 158, 11, 0.5) !important;
    box-shadow: 0 25px 60px -15px rgba(245, 158, 11, 0.35), 0 0 40px rgba(0,0,0,0.9) !important;
    border-radius: 24px !important;
    max-width: 520px !important;
    width: 100% !important;
    color: #ffffff !important;
    position: relative !important;
    overflow: hidden !important;
    margin: auto !important;
    padding: 22px 18px !important;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
    box-sizing: border-box !important;
    text-align: center !important;
  }

  .bday-close-btn {
    position: absolute !important;
    top: 12px !important;
    right: 12px !important;
    z-index: 30 !important;
    width: 34px !important;
    height: 34px !important;
    border-radius: 50% !important;
    background: rgba(30, 41, 59, 0.8) !important;
    color: #cbd5e1 !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    font-size: 1.1rem !important;
    line-height: 1 !important;
  }
  .bday-close-btn:hover {
    background: rgba(239, 68, 68, 0.8) !important;
    color: #ffffff !important;
  }

  .bday-gold-title {
    font-size: 1.85rem !important;
    font-weight: 900 !important;
    letter-spacing: -0.5px !important;
    background: linear-gradient(135deg, #fef08a 0%, #f59e0b 50%, #fef08a 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    text-transform: uppercase !important;
    margin: 4px 0 !important;
  }

  .bday-celebrants-grid {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: center !important;
    gap: 16px !important;
    margin: 18px 0 28px 0 !important;
  }

  .bday-photo-frame {
    position: relative !important;
    padding: 5px !important;
    border-radius: 18px !important;
    background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%) !important;
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.35) !important;
    transition: transform 0.3s ease !important;
  }
  .bday-photo-frame:hover {
    transform: translateY(-4px) scale(1.03) !important;
  }

  .bday-photo-img {
    width: 135px !important;
    height: 135px !important;
    object-fit: cover !important;
    border-radius: 14px !important;
    display: block !important;
    border: 2px solid #0f172a !important;
  }

  .bday-single-img {
    width: 170px !important;
    height: 170px !important;
  }

  .bday-name-tag {
    position: absolute !important;
    bottom: -18px !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    width: 112% !important;
    background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%) !important;
    border: 1.5px solid #fbbf24 !important;
    border-radius: 10px !important;
    padding: 4px 6px !important;
    text-align: center !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.6) !important;
  }

  .bday-name-text {
    font-size: 0.82rem !important;
    font-weight: 800 !important;
    color: #fef08a !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    margin: 0 !important;
    line-height: 1.2 !important;
  }

  .bday-sub-text {
    font-size: 0.68rem !important;
    color: #94a3b8 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    margin: 0 !important;
  }

  .bday-wish-section {
    background: rgba(15, 23, 42, 0.9) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 16px !important;
    padding: 14px !important;
    margin-top: 16px !important;
    text-align: left !important;
  }

  .bday-emoji-bar {
    display: flex !important;
    gap: 6px !important;
    margin: 8px 0 12px 0 !important;
  }

  .bday-emoji-btn {
    flex: 1 !important;
    padding: 8px 4px !important;
    background: #1e293b !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 10px !important;
    font-size: 1.25rem !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
    text-align: center !important;
  }
  .bday-emoji-btn:hover {
    background: rgba(245, 158, 11, 0.2) !important;
    border-color: #f59e0b !important;
    transform: scale(1.15) !important;
  }

  .bday-input-group {
    display: flex !important;
    gap: 8px !important;
  }

  .bday-input-box {
    flex: 1 !important;
    background: #020617 !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    border-radius: 10px !important;
    padding: 8px 12px !important;
    font-size: 0.8rem !important;
    outline: none !important;
    box-sizing: border-box !important;
  }
  .bday-input-box:focus {
    border-color: #f59e0b !important;
  }

  .bday-send-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: #0f172a !important;
    font-weight: 800 !important;
    font-size: 0.8rem !important;
    padding: 8px 16px !important;
    border-radius: 10px !important;
    border: none !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
  }
  .bday-send-btn:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
  }
</style>

<div id="staffBirthdayModalOverlay">
  <!-- Confetti Canvas -->
  <canvas id="birthdayConfettiCanvas" style="position: fixed; inset: 0; pointer-events: none; z-index: 10; width: 100%; height: 100%;"></canvas>

  <div class="bday-card-container" id="birthdayCardContainer">
    
    <!-- Close Button -->
    <button onclick="closeBirthdayModal()" class="bday-close-btn" title="Close">
      ✕
    </button>

    <!-- Card Content Container -->
    <div style="position: relative; z-index: 20;">

      <!-- College Header Crest & Title -->
      <div>
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 6px;">
          <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(245, 158, 11, 0.15); border: 1.5px solid rgba(245, 158, 11, 0.4); display: flex; align-items: center; justify-content: center; color: #fbbf24; font-size: 1.2rem;">
            🎓
          </div>
        </div>
        <div style="font-size: 0.65rem; letter-spacing: 1.5px; font-weight: 900; text-transform: uppercase; color: #fbbf24; opacity: 0.9;">
          Carmel Polytechnic College Alappuzha
        </div>
        
        <!-- Calligraphic Happy Birthday Title -->
        <h2 class="bday-gold-title">
          HAPPY BIRTHDAY!
        </h2>
        <p style="font-size: 0.76rem; color: #cbd5e1; font-style: italic; margin: 2px 0 0 0;">
          Wishing you a wonderful year ahead filled with joy, success, and good health.
        </p>
      </div>

      <!-- Celebrants Photo Display Area -->
      <div id="birthdayCelebrantsContainer" class="bday-celebrants-grid">
        <!-- Dynamic Photo Frames Rendered via JS -->
      </div>

      <!-- Date Wreath Badge & Quote -->
      <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;">
        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 14px; border-radius: 20px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3);">
          <span style="color: #fbbf24; font-size: 0.8rem;">✦</span>
          <span id="birthdayDateBadge" style="font-size: 0.75rem; font-weight: 900; letter-spacing: 1.5px; text-transform: uppercase; color: #fef08a; font-family: monospace;">
            TODAY
          </span>
          <span style="color: #fbbf24; font-size: 0.8rem;">✦</span>
        </div>
        <p style="font-size: 0.72rem; font-style: italic; color: #94a3b8; margin: 4px 0 0 0;">
          "May your day be as special as you are!"
        </p>
      </div>

      <!-- Interactive Wishes Section -->
      <div class="bday-wish-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
          <span style="font-size: 0.76rem; font-weight: 800; color: #f8fafc; display: flex; align-items: center; gap: 6px;">
            <span>🎉</span> Send Birthday Wishes & Reactions
          </span>
          <span id="birthdayWishCountBadge" style="font-size: 0.65rem; font-weight: 800; color: #fbbf24; background: rgba(245, 158, 11, 0.15); padding: 2px 8px; border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.3);">
            0 Wishes
          </span>
        </div>

        <!-- Quick Emoji Buttons -->
        <div class="bday-emoji-bar">
          <button onclick="sendBirthdayReaction('🎉')" class="bday-emoji-btn" title="Celebrate">🎉</button>
          <button onclick="sendBirthdayReaction('🎂')" class="bday-emoji-btn" title="Cake">🎂</button>
          <button onclick="sendBirthdayReaction('🎈')" class="bday-emoji-btn" title="Balloon">🎈</button>
          <button onclick="sendBirthdayReaction('🎁')" class="bday-emoji-btn" title="Gift">🎁</button>
          <button onclick="sendBirthdayReaction('❤️')" class="bday-emoji-btn" title="Love">❤️</button>
          <button onclick="sendBirthdayReaction('👏')" class="bday-emoji-btn" title="Applaud">👏</button>
        </div>

        <!-- Custom Message Input -->
        <div class="bday-input-group">
          <input id="birthdayMessageInput" type="text" placeholder="Write a warm birthday wish..." class="bday-input-box">
          <button onclick="submitCustomBirthdayWish()" class="bday-send-btn">
            Send 🚀
          </button>
        </div>

        <!-- Live Wishes Feed -->
        <div style="margin-top: 10px;">
          <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; display: block; margin-bottom: 4px;">Colleague Wishes:</span>
          <div id="birthdayWishesFeed" style="max-height: 100px; overflow-y: auto; display: flex; flex-direction: column; gap: 6px; padding-right: 4px;">
            <!-- Wishes injected dynamically -->
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Floating Quick Reopen Birthday Widget Button -->
<button id="reopenBirthdayWidgetBtn" onclick="openBirthdayModal()" style="position: fixed; bottom: 90px; right: 18px; z-index: 99990; background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%) !important; border: 1.5px solid #fbbf24 !important; box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4) !important; color: #fef08a !important; padding: 9px 14px !important; border-radius: 30px !important; font-weight: 800 !important; font-size: 0.78rem !important; display: none; align-items: center !important; gap: 8px !important; cursor: pointer !important; transition: all 0.25s ease !important; font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;">
  <span style="font-size: 1.15rem; line-height: 1;">🎂</span>
  <span>Birthdays</span>
  <span id="reopenBirthdayCountBadge" style="background: #f59e0b; color: #0f172a; border-radius: 12px; padding: 2px 7px; font-size: 0.7rem; font-weight: 900;">0</span>
</button>

<script>
  let activeBirthdayCelebrants = [];
  let primaryCelebrantMobile = '';

  // Auto Check for Birthdays on Page Load
  document.addEventListener('DOMContentLoaded', function() {
    checkTodayStaffBirthdays();
  });

  function checkTodayStaffBirthdays() {
    fetch('/api/staff/birthdays/today')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.has_birthdays && data.celebrants && data.celebrants.length > 0) {
          activeBirthdayCelebrants = data.celebrants;
          primaryCelebrantMobile = data.celebrants[0].mobile_no;
          
          // Show Floating Reopen Button on Dashboard
          const reopenBtn = document.getElementById('reopenBirthdayWidgetBtn');
          const countBadge = document.getElementById('reopenBirthdayCountBadge');
          if (reopenBtn && countBadge) {
            countBadge.innerText = data.celebrants.length;
            reopenBtn.style.display = 'flex';
          }

          renderBirthdayModalContent(data);
          openBirthdayModal();
        }
      })
      .catch(err => console.log('Birthday check status:', err));
  }

  function renderBirthdayModalContent(data) {
    const container = document.getElementById('birthdayCelebrantsContainer');
    document.getElementById('birthdayDateBadge').innerText = data.date_label || 'TODAY';
    container.innerHTML = '';

    const count = data.celebrants.length;

    data.celebrants.forEach((c, idx) => {
      const card = document.createElement('div');
      card.style.cssText = "display: flex; flex-direction: column; align-items: center; position: relative;";

      // Twin Side-by-Side or Single Portrait Frame Styling
      const photoClass = count === 1 
        ? "bday-photo-img bday-single-img" 
        : "bday-photo-img";

      card.innerHTML = `
        <div class="bday-photo-frame">
          <img src="${c.photo_url}" alt="${c.name}" class="${photoClass}">
          <div class="bday-name-tag">
            <p class="bday-name-text">
              ${c.name}
            </p>
          </div>
        </div>
      `;
      container.appendChild(card);
    });

    renderWishesFeed(data.wishes || []);
  }

  function renderWishesFeed(wishes) {
    const feed = document.getElementById('birthdayWishesFeed');
    const badge = document.getElementById('birthdayWishCountBadge');
    badge.innerText = `${wishes.length} ${wishes.length === 1 ? 'Wish' : 'Wishes'}`;

    if (!wishes || wishes.length === 0) {
      feed.innerHTML = `<p style="color: #64748b; font-size: 0.72rem; font-style: italic; margin: 4px 0;">Be the first colleague to send a birthday wish!</p>`;
      return;
    }

    feed.innerHTML = wishes.map(w => `
      <div style="padding: 6px 10px; border-radius: 10px; background: rgba(2, 6, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); display: flex; align-items: center; justify-content: space-between; gap: 8px;">
        <div style="min-width: 0; text-align: left;">
          <span style="font-weight: 800; color: #fef08a; font-size: 0.75rem;">${w.sender_name}:</span>
          <span style="color: #e2e8f0; font-size: 0.75rem; margin-left: 4px;">${w.message || ''}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
          ${w.emoji ? `<span style="font-size: 0.9rem;">${w.emoji}</span>` : ''}
          <span style="font-size: 0.65rem; color: #64748b; font-family: monospace;">${w.time}</span>
        </div>
      </div>
    `).join('');
  }

  function openBirthdayModal() {
    const modal = document.getElementById('staffBirthdayModalOverlay');
    if (modal) {
      modal.classList.add('show-modal');
      triggerBirthdayConfetti();
    }
  }

  function closeBirthdayModal() {
    const modal = document.getElementById('staffBirthdayModalOverlay');
    if (modal) {
      modal.classList.remove('show-modal');
    }
  }

  function sendBirthdayReaction(emoji) {
    postWishApi(emoji, null);
  }

  function submitCustomBirthdayWish() {
    const input = document.getElementById('birthdayMessageInput');
    const msg = input.value.trim();
    if (!msg) return;
    postWishApi('🎂', msg);
    input.value = '';
  }

  function postWishApi(emoji, message) {
    if (!primaryCelebrantMobile) return;

    fetch('/api/staff/birthdays/wish', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        celebrant_mobile_no: primaryCelebrantMobile,
        emoji: emoji,
        message: message
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'SUCCESS') {
        renderWishesFeed(data.wishes || []);
        triggerBirthdayConfetti();
      }
    })
    .catch(err => console.error('Wish submit error:', err));
  }

  // Lightweight HTML5 Canvas Confetti Burst
  function triggerBirthdayConfetti() {
    const canvas = document.getElementById('birthdayConfettiCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const pieces = [];
    const colors = ['#f59e0b', '#fbbf24', '#3b82f6', '#ec4899', '#10b981', '#ffffff'];

    for (let i = 0; i < 90; i++) {
      pieces.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height - canvas.height,
        size: Math.random() * 8 + 4,
        color: colors[Math.floor(Math.random() * colors.length)],
        vy: Math.random() * 3 + 2,
        vx: (Math.random() - 0.5) * 2,
        rot: Math.random() * 360
      });
    }

    let frame = 0;
    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      pieces.forEach(p => {
        p.y += p.vy;
        p.x += p.vx;
        p.rot += 2;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate((p.rot * Math.PI) / 180);
        ctx.fillStyle = p.color;
        ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
        ctx.restore();
      });

      frame++;
      if (frame < 130) {
        requestAnimationFrame(draw);
      } else {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
      }
    }
    draw();
  }
</script>
