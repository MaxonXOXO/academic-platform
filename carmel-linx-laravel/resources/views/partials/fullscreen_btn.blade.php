<button id="fullscreenToggleBtn" onclick="toggleFullScreen()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm" title="Toggle Full Screen">
  <span id="fullscreenToggleIcon" class="material-symbols-rounded text-base text-cyan-400">fullscreen</span>
  <span id="fullscreenToggleText" class="hidden sm:inline">Full Screen</span>
</button>

<script>
  if (typeof toggleFullScreen !== 'function') {
    function toggleFullScreen() {
      if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.msRequestFullscreen) {
          document.documentElement.msRequestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
        }
      }
    }

    function updateFullscreenIcon() {
      const icon = document.getElementById('fullscreenToggleIcon');
      const text = document.getElementById('fullscreenToggleText');
      const isFS = !!(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
      if (icon) {
        icon.innerText = isFS ? 'fullscreen_exit' : 'fullscreen';
      }
      if (text) {
        text.innerText = isFS ? 'Exit Full Screen' : 'Full Screen';
      }
    }

    document.addEventListener('fullscreenchange', updateFullscreenIcon);
    document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);
    document.addEventListener('mozfullscreenchange', updateFullscreenIcon);
    document.addEventListener('MSFullscreenChange', updateFullscreenIcon);
  }
</script>
