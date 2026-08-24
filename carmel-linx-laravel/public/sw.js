// Carmel Linx PWA Service Worker & Web Push Notification Handler
const CACHE_NAME = 'carmel-linx-v1';

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(clients.claim());
});

// Push Event Listener - Receives background push notifications from server
self.addEventListener('push', (event) => {
  let data = {
    title: 'Carmel Linx Alert',
    body: 'New notification from Carmel Polytechnic College.',
    icon: '/favicon.ico',
    badge: '/favicon.ico',
    url: '/dashboard/staff/mobile',
    tag: 'carmel-notice-' + Date.now()
  };

  if (event.data) {
    try {
      const payload = event.data.json();
      data = { ...data, ...payload };
    } catch (e) {
      data.body = event.data.text();
    }
  }

  const options = {
    body: data.body,
    icon: data.icon || '/favicon.ico',
    badge: data.badge || '/favicon.ico',
    tag: data.tag || 'carmel-linx-notification',
    renotify: true,
    data: {
      url: data.url || '/'
    },
    vibrate: [200, 100, 200],
    actions: [
      { action: 'open', title: 'Open App 🚀' },
      { action: 'dismiss', title: 'Dismiss' }
    ]
  };

  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

// Notification Click Event Listener
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  if (event.action === 'dismiss') return;

  const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (let i = 0; i < clientList.length; i++) {
        let client = clientList[i];
        if (client.url.includes(targetUrl) && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});
