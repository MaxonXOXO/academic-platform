<!-- Live Support Desk & Laser Pointer (Beta Overlay Component) -->
<div id="supportDeskOverlayContainer">
  
  <!-- Staff Side: Top Active Stream Notification Bar -->
  <div id="staffSupportActiveBar" class="hidden fixed top-0 left-0 right-0 z-[99999] bg-gradient-to-r from-red-950 via-rose-900 to-red-950 border-b border-rose-500/50 px-4 py-2 text-white shadow-2xl flex items-center justify-between backdrop-blur-md animate-pulse">
    <div class="flex items-center gap-3">
      <span class="relative flex h-3 w-3">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
      </span>
      <span class="font-bold text-xs md:text-sm tracking-wide">
        🔴 Live Remote Support Active — Sharing Screen with <span id="activeSupportAdminName" class="underline text-amber-300">Dhanush.A (Dept. of Electronics)</span>
      </span>
    </div>
    <div class="flex items-center gap-3">
      <span class="text-[11px] text-rose-200 hidden md:inline">Protected P2P WebRTC Connection</span>
      <button onclick="stopStaffScreenShare()" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs rounded-lg shadow-lg border border-red-400 transition-premium cursor-pointer flex items-center gap-1">
        <span class="material-symbols-rounded text-sm">stop_circle</span> End Support Session
      </button>
    </div>
  </div>

  <!-- Staff Side: Laser Pointer Overlay Target -->
  <div id="supportLaserPointer" class="hidden fixed z-[999999] pointer-events-none transform -translate-x-1/2 -translate-y-1/2 transition-all duration-75">
    <div class="w-8 h-8 rounded-full border-2 border-red-500 bg-red-500/30 animate-ping absolute"></div>
    <div class="w-5 h-5 rounded-full bg-red-600 border-2 border-white shadow-[0_0_15px_#ef4444] flex items-center justify-center">
      <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
    </div>
    <div id="supportLaserLabel" class="absolute left-6 top-0 bg-slate-900/90 border border-red-500 text-red-300 font-extrabold text-[10px] px-2 py-0.5 rounded shadow-md whitespace-nowrap">
      Support Pointer
    </div>
  </div>

  <!-- Staff Side: Request Modal -->
  <div id="staffSupportRequestModal" class="hidden fixed inset-0 z-[9999] bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4 md:p-6">
    <div class="bg-slate-900 border-2 border-slate-700/90 rounded-3xl max-w-2xl w-full p-8 shadow-[0_20px_60px_rgba(0,0,0,0.8)] space-y-6 relative">
      <button onclick="closeStaffSupportModal()" class="absolute top-6 right-6 text-slate-400 hover:text-white p-2 rounded-xl bg-slate-800/60 hover:bg-slate-800 transition-premium cursor-pointer">
        <span class="material-symbols-rounded text-xl">close</span>
      </button>
      
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 shrink-0">
          <span class="material-symbols-rounded text-3xl">desktop_windows</span>
        </div>
        <div>
          <h3 class="text-2xl font-black text-white leading-tight tracking-tight">Live Remote Support (Beta)</h3>
          <p class="text-sm font-bold text-slate-400 mt-0.5">Dhanush.A • Dept. of Electronics</p>
        </div>
      </div>

      <div class="p-5 bg-slate-950/80 border border-slate-800/90 rounded-2xl space-y-3.5 text-sm text-slate-200">
        <div class="flex items-start gap-3">
          <span class="material-symbols-rounded text-emerald-400 text-xl shrink-0 mt-0.5">verified_user</span>
          <span class="leading-relaxed"><strong>Explicit User Permission:</strong> Your screen will ONLY be visible after you click <em class="text-amber-300 font-bold">Share Screen</em> in your browser prompt.</span>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-rounded text-blue-400 text-xl shrink-0 mt-0.5">lock</span>
          <span class="leading-relaxed"><strong>Secure P2P Encryption:</strong> Direct browser-to-browser WebRTC encrypted connection. No video recording or files stored.</span>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-rounded text-rose-400 text-xl shrink-0 mt-0.5">touch_app</span>
          <span class="leading-relaxed"><strong>Interactive Guidance:</strong> Dhanush.A can place a glowing laser pointer on your screen to show you exactly where to click.</span>
        </div>
      </div>

      <div id="staffModalStatusText" class="text-sm font-extrabold text-amber-400 text-center hidden p-3 rounded-xl bg-amber-950/30 border border-amber-500/30 animate-pulse">
        Request sent! Waiting for Support Admin to accept...
      </div>

      <div class="flex items-center gap-4 pt-2">
        <button onclick="closeStaffSupportModal()" class="w-1/2 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl text-sm transition-premium cursor-pointer">
          Cancel
        </button>
        <button id="btnStartSupportShare" onclick="initiateStaffSupportShare()" class="w-1/2 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-extrabold rounded-2xl text-sm transition-premium cursor-pointer shadow-xl shadow-blue-600/30 flex items-center justify-center gap-2">
          <span class="material-symbols-rounded text-lg">screen_share</span> Request Assist
        </button>
      </div>
    </div>
  </div>

</div>

<script>
// WebRTC Live Support State Management
window.SupportDesk = {
  sessionId: null,
  peerConnection: null,
  localStream: null,
  signalPollInterval: null,
  lastSignalId: null,
  role: '{{ session("userRole") }}',

  // Config WebRTC ICE Servers
  rtcConfig: {
    iceServers: [
      { urls: 'stun:stun.l.google.com:19302' },
      { urls: 'stun:stun1.l.google.com:19302' }
    ]
  }
};

// Open Staff Request Modal
function openStaffSupportModal() {
  document.getElementById('staffSupportRequestModal').classList.remove('hidden');
  document.getElementById('staffModalStatusText').classList.add('hidden');
  document.getElementById('btnStartSupportShare').disabled = false;
}

function closeStaffSupportModal() {
  if (SupportDesk.sessionId) {
    stopStaffScreenShare();
  }
  document.getElementById('staffSupportRequestModal').classList.add('hidden');
}

// Initiate Support Request from Staff
async function initiateStaffSupportShare() {
  const btn = document.getElementById('btnStartSupportShare');
  const statusText = document.getElementById('staffModalStatusText');
  btn.disabled = true;
  statusText.classList.remove('hidden');
  statusText.innerText = "Connecting to Support Desk...";

  try {
    const res = await fetch('/api/support/request', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });

    const data = await res.json();
    if (data.status !== 'SUCCESS') {
      alert('Error creating support session: ' + data.message);
      btn.disabled = false;
      return;
    }

    SupportDesk.sessionId = data.session_id;
    statusText.innerText = "Request sent! Waiting for Support Admin to accept...";

    // Start Polling for Admin Acceptance & SDP Answer
    startSignalPolling('staff');
  } catch (err) {
    console.error('Support request failed:', err);
    alert('Failed to connect to Support Desk.');
    btn.disabled = false;
  }
}

// Start WebRTC Screen Capture on Staff Device
async function startStaffScreenCapture() {
  try {
    const stream = await navigator.mediaDevices.getDisplayMedia({
      video: { cursor: "always" },
      audio: false
    });

    SupportDesk.localStream = stream;

    // Show active top bar
    document.getElementById('staffSupportActiveBar').classList.remove('hidden');
    closeStaffSupportModal();

    // Create PeerConnection
    const pc = new RTCPeerConnection(SupportDesk.rtcConfig);
    SupportDesk.peerConnection = pc;

    // Add screen video track
    stream.getTracks().forEach(track => {
      pc.addTrack(track, stream);
      track.onended = () => {
        stopStaffScreenShare();
      };
    });

    // Handle ICE Candidates
    pc.onicecandidate = (event) => {
      if (event.candidate) {
        postSupportSignal('candidate', event.candidate, 'staff');
      }
    };

    // Create SDP Offer
    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);

    // Send Offer to Admin via Signaling API
    postSupportSignal('offer', offer, 'staff');

  } catch (err) {
    console.error('Screen capture permission denied or failed:', err);
    alert('Screen sharing was cancelled or not supported by browser.');
    stopStaffScreenShare();
  }
}

// Stop Staff Screen Share
async function stopStaffScreenShare() {
  if (SupportDesk.localStream) {
    SupportDesk.localStream.getTracks().forEach(track => track.stop());
    SupportDesk.localStream = null;
  }

  if (SupportDesk.peerConnection) {
    SupportDesk.peerConnection.close();
    SupportDesk.peerConnection = null;
  }

  if (SupportDesk.signalPollInterval) {
    clearInterval(SupportDesk.signalPollInterval);
    SupportDesk.signalPollInterval = null;
  }

  if (SupportDesk.sessionId) {
    fetch('/api/support/end', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ session_id: SupportDesk.sessionId })
    }).catch(() => {});
  }

  document.getElementById('staffSupportActiveBar').classList.add('hidden');
  document.getElementById('supportLaserPointer').classList.add('hidden');
  closeStaffSupportModal();
  SupportDesk.sessionId = null;
}

// Post Signal to Server
async function postSupportSignal(type, payload, sender) {
  if (!SupportDesk.sessionId) return;
  try {
    await fetch('/api/support/signal', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        session_id: SupportDesk.sessionId,
        type: type,
        payload: payload,
        sender: sender
      })
    });
  } catch (e) {
    console.error('Failed to post signal:', e);
  }
}

// Poll Signals
function startSignalPolling(myRole) {
  if (SupportDesk.signalPollInterval) clearInterval(SupportDesk.signalPollInterval);

  SupportDesk.signalPollInterval = setInterval(async () => {
    if (!SupportDesk.sessionId) return;

    try {
      let url = `/api/support/signals/${SupportDesk.sessionId}`;
      if (SupportDesk.lastSignalId) {
        url += `?last_id=${SupportDesk.lastSignalId}`;
      }

      const res = await fetch(url);
      const data = await res.json();

      if (data.session_status === 'ended') {
        if (myRole === 'admin') {
          if (typeof endAdminSupportSession === 'function') endAdminSupportSession();
        } else {
          stopStaffScreenShare();
        }
        return;
      }

      if (data.signals && data.signals.length > 0) {
        for (const sig of data.signals) {
          SupportDesk.lastSignalId = sig.id;
          if (sig.sender !== myRole) {
            handleIncomingSignal(sig, myRole);
          }
        }
      }
    } catch (err) {
      console.error('Signal poll error:', err);
    }
  }, 1000);
}

// Handle Incoming WebRTC & Pointer Signals
async function handleIncomingSignal(sig, myRole) {
  const pc = (myRole === 'admin') ? (window.AdminSupport ? window.AdminSupport.peerConnection : null) : SupportDesk.peerConnection;

  if (myRole === 'staff') {
    // If Admin accepted the session, prompt staff to capture screen
    if (sig.type === 'admin_accepted') {
      await startStaffScreenCapture();
    } else if (sig.type === 'answer' && pc) {
      await pc.setRemoteDescription(new RTCSessionDescription(sig.payload));
    } else if (sig.type === 'candidate' && pc) {
      await pc.addIceCandidate(new RTCIceCandidate(sig.payload));
    } else if (sig.type === 'laser_pointer') {
      showStaffLaserPointer(sig.payload.x, sig.payload.y);
    }
  } else if (myRole === 'admin') {
    if (sig.type === 'offer' && pc) {
      await pc.setRemoteDescription(new RTCSessionDescription(sig.payload));
      const answer = await pc.createAnswer();
      await pc.setLocalDescription(answer);
      postSupportSignal('answer', answer, 'admin');
    } else if (sig.type === 'candidate' && pc) {
      await pc.addIceCandidate(new RTCIceCandidate(sig.payload));
    }
  }
}

// Render Laser Pointer on Staff Screen
let laserTimeout = null;
function showStaffLaserPointer(xPercent, yPercent) {
  const pointer = document.getElementById('supportLaserPointer');
  if (!pointer) return;

  const posX = window.innerWidth * (xPercent / 100);
  const posY = window.innerHeight * (yPercent / 100);

  pointer.style.left = posX + 'px';
  pointer.style.top = posY + 'px';
  pointer.classList.remove('hidden');

  if (laserTimeout) clearTimeout(laserTimeout);
  laserTimeout = setTimeout(() => {
    pointer.classList.add('hidden');
  }, 3000);
}

// Automatically terminate support session if staff member closes browser tab or navigates away
window.addEventListener('beforeunload', () => {
  if (SupportDesk.sessionId) {
    const data = JSON.stringify({ session_id: SupportDesk.sessionId });
    if (navigator.sendBeacon) {
      navigator.sendBeacon('/api/support/end', new Blob([data], { type: 'application/json' }));
    }
  }
});
window.addEventListener('pagehide', () => {
  if (SupportDesk.sessionId) {
    const data = JSON.stringify({ session_id: SupportDesk.sessionId });
    if (navigator.sendBeacon) {
      navigator.sendBeacon('/api/support/end', new Blob([data], { type: 'application/json' }));
    }
  }
});
</script>

@include('partials.carmie_assistant')
