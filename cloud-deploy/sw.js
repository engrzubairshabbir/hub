const CACHE_NAME = 'cw-hub-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/assets/css/tailwind.min.css',
  '/assets/css/app.css',
  '/assets/js/app.js',
  '/manifest.json'
];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS_TO_CACHE)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((k) => {
          if (k !== CACHE_NAME) return caches.delete(k);
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (e) => {
  // Stale-while-revalidate for static assets, network-first for HTML/APIs
  if (e.request.url.includes('/api/') || e.request.url.includes('google')) {
    return;
  }
  e.respondWith(
    caches.match(e.request).then((res) => {
      return res || fetch(e.request).then((networkRes) => {
        if (networkRes.status === 200 && (e.request.url.endsWith('.css') || e.request.url.endsWith('.js'))) {
          const clone = networkRes.clone();
          caches.open(CACHE_NAME).then((c) => c.put(e.request, clone));
        }
        return networkRes;
      });
    }).catch(() => caches.match('/'))
  );
});
