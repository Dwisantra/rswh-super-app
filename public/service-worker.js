const CACHE_NAME = 'rswh-cache-v2'
const STATIC_ASSETS = [
  '/',
  '/offline.html',
  '/pwa/manifest.json',
  '/pwa/icons/android-launchericon-192-192.png',
  '/pwa/icons/android-launchericon-512-512.png'
]

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS)))
  self.skipWaiting()
})

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))
    )
  )
  self.clients.claim()
})

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(fetch(event.request).catch(() => caches.match('/offline.html')))
    return
  }

  event.respondWith(caches.match(event.request).then((res) => res || fetch(event.request)))
})

self.addEventListener('push', (event) => {
  let payload = {}

  if (event.data) {
    try {
      payload = event.data.json()
    } catch (_) {
      payload = { body: event.data.text() }
    }
  }

  const title = payload.title || 'RSWH Super App'
  const options = {
    body: payload.body || 'Ada informasi terbaru untuk Anda.',
    icon: payload.icon || '/pwa/icons/android-launchericon-192-192.png',
    badge: payload.badge || '/pwa/icons/android-launchericon-192-192.png',
    data: {
      url: payload.url || '/',
      ...payload.data
    }
  }

  event.waitUntil(self.registration.showNotification(title, options))
})

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  const urlToOpen = new URL('/profil/notifikasi', self.location.origin).href;

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
      for (var i = 0; i < windowClients.length; i++) {
        var client = windowClients[i];
        if (client.url === urlToOpen && 'focus' in client) return client.focus();
      }
      if (clients.openWindow) return clients.openWindow(urlToOpen);
    })
  );
});
