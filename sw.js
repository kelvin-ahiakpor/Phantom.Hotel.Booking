const CACHE_NAME = 'offline-cache-v1';
const OFFLINE_URL = '/no_internet.html';

// Install the service worker and cache the offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('Caching offline page during install');
            return cache.addAll([OFFLINE_URL]);
        })
    );
});

// Intercept fetch requests
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(OFFLINE_URL);
        })
    );
});

// Update the cache if needed
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName !== CACHE_NAME)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});