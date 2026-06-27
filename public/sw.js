const CACHE_NAME = 'smartrt-cache-v2';
const STATIC_ASSETS = [
    '/logo.png',
    '/manifest.json'
];

// Install Event
self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
});

// Activate Event - Clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event - Stale While Revalidate for assets, Network First for HTML
self.addEventListener('fetch', event => {
    const req = event.request;
    const url = new URL(req.url);

    // Only handle GET requests
    if (req.method !== 'GET') return;

    // Static Assets (CSS, JS, Images) -> Cache First, then Network
    if (url.pathname.startsWith('/build/') || url.pathname.match(/\.(png|jpg|jpeg|svg|css|js|woff2)$/)) {
        event.respondWith(
            caches.match(req).then(cachedRes => {
                if (cachedRes) {
                    // Fetch in background to update cache
                    event.waitUntil(
                        fetch(req).then(networkRes => {
                            caches.open(CACHE_NAME).then(cache => cache.put(req, networkRes));
                        }).catch(() => {})
                    );
                    return cachedRes;
                }
                return fetch(req).then(networkRes => {
                    const resClone = networkRes.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(req, resClone));
                    return networkRes;
                });
            })
        );
        return;
    }

    // HTML/Navigational Requests -> Network First, fallback to cache
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req)
                .then(networkRes => {
                    const resClone = networkRes.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(req, resClone));
                    return networkRes;
                })
                .catch(() => caches.match(req))
        );
        return;
    }

    // Default fallback
    event.respondWith(
        fetch(req).catch(() => caches.match(req))
    );
});
