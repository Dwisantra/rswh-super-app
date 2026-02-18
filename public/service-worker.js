const CACHE_NAME = 'rswh-cache-v1'
const STATIC_ASSETS = [
  '/offline.html',
  '/manifest.json',
  '/icons/android-launchericon-192-192.png',
  '/icons/android-launchericon-512-512.png'
]

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(async cache => {
      try {
        const response = await fetch('/', { cache: 'reload' });
        await cache.put('/', response);
      } catch (e) {
        console.log('Failed to cache index');
      }

      await cache.addAll([
        '/manifest.json',
        '/icons/android-launchericon-192-192.png',
        '/icons/android-launchericon-512-512.png'
      ]);
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(key => key !== CACHE_NAME)
          .map(key => caches.delete(key))
      )
    )
  )
  self.clients.claim()
})

self.addEventListener('fetch', event => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .catch(() => caches.match('/'))
    )
    return
  }

  event.respondWith(
    caches.match(event.request)
      .then(res => res || fetch(event.request))
  )
})
