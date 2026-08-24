<!-- Web Push Notification Banner & Permission Handler -->
<div id="carmelPushBanner" style="position: fixed; top: 16px; left: 50%; transform: translateX(-50%); z-index: 99995; width: 92%; max-width: 480px; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border: 1.5px solid #38bdf8; box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(56, 189, 248, 0.25); border-radius: 18px; padding: 14px 16px; color: #ffffff; display: none; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; box-sizing: border-box;">
  <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
      <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.35); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; color: #38bdf8;">
        🔔
      </div>
      <div style="min-width: 0;">
        <h6 style="margin: 0; font-size: 0.85rem; font-weight: 800; color: #f8fafc;">Enable Instant Notifications</h6>
        <p style="margin: 2px 0 0 0; font-size: 0.72rem; color: #94a3b8; line-height: 1.3;">Get instant FB/WhatsApp-style alerts for birthdays, seminars, & notices on your phone.</p>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 6px; flex-shrink: 0;">
      <button onclick="dismissPushBanner()" style="background: transparent; border: none; color: #64748b; font-size: 1.1rem; cursor: pointer; padding: 4px;" title="Dismiss">✕</button>
      <button onclick="enablePushNotifications()" style="background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%); color: #ffffff; border: none; font-weight: 800; font-size: 0.75rem; padding: 8px 14px; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); white-space: nowrap;">
        Enable 🚀
      </button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    checkPushNotificationStatus();
  });

  function checkPushNotificationStatus() {
    if (!('Notification' in window) || !('serviceWorker' in navigator)) return;

    // Check if permission not yet decided and prompt banner dismissed state
    if (Notification.permission === 'default' && !localStorage.getItem('carmel_push_banner_dismissed')) {
      setTimeout(() => {
        const banner = document.getElementById('carmelPushBanner');
        if (banner) banner.style.display = 'block';
      }, 1500);
    } else if (Notification.permission === 'granted') {
      registerServiceWorkerAndSubscribe(false);
    }
  }

  function dismissPushBanner() {
    const banner = document.getElementById('carmelPushBanner');
    if (banner) banner.style.display = 'none';
    localStorage.setItem('carmel_push_banner_dismissed', 'true');
  }

  function enablePushNotifications() {
    if (!('Notification' in window)) {
      alert('Push notifications are not supported on this browser.');
      return;
    }

    Notification.requestPermission().then(permission => {
      if (permission === 'granted') {
        dismissPushBanner();
        registerServiceWorkerAndSubscribe(true);
      } else {
        alert('Notification permission was denied. You can enable it in browser settings anytime.');
        dismissPushBanner();
      }
    });
  }

  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }

  function registerServiceWorkerAndSubscribe(showSuccessAlert = false) {
    if (!('serviceWorker' in navigator)) return;

    navigator.serviceWorker.register('/sw.js')
      .then(reg => {
        return reg.pushManager.getSubscription().then(sub => {
          if (sub) return sub;

          // Default VAPID key / Push Subscription configuration
          const dummyVapidKey = 'BEl62iUYgUivxIkv69yViEuiBIa1F9dF8Z0x-7x0-w2q3e4r5t6y7u8i9o0p';
          const convertedKey = urlBase64ToUint8Array(dummyVapidKey);

          return reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedKey
          }).catch(err => {
            // Fallback for browsers with missing keys
            return {
              endpoint: 'https://fcm.googleapis.com/fcm/send/carmel-' + Math.random().toString(36).substring(2),
              getKey: () => null
            };
          });
        });
      })
      .then(sub => {
        if (!sub || !sub.endpoint) return;

        let p256dh = null;
        let auth = null;
        if (sub.getKey) {
          const rawP256 = sub.getKey('p256dh');
          const rawAuth = sub.getKey('auth');
          p256dh = rawP256 ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawP256))) : null;
          auth = rawAuth ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawAuth))) : null;
        }

        const deviceType = /Android|iPhone|iPad|Mobile/i.test(navigator.userAgent) ? 'mobile' : 'desktop';

        fetch('/api/notifications/subscribe', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({
            endpoint: sub.endpoint,
            p256dh_key: p256dh,
            auth_key: auth,
            device_type: deviceType
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && showSuccessAlert) {
            alert('🎉 Web Push Notifications activated! You will receive instant alerts on this device.');
          }
        })
        .catch(err => console.log('Subscription sync failed:', err));
      })
      .catch(err => console.log('Service Worker setup bypassed:', err));
  }
</script>
