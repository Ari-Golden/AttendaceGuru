const CACHE_NAME = 'my-pwa-cache-v1';
const urlsToCache = [
    '/',
    '/guru',
    '/login',
    '/register',
    '/css/app.css',
    '/js/app.js',
    '/images/logo.png',
    '/offline.html', // Ensure this file exists
    '/manifest.json'
];

// Register service worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                console.log('Service Worker registered with scope:', registration.scope);
            })
            .catch(error => {
                console.error('Service Worker registration failed:', error);
            });
    });
}

// Install service worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
            .catch(error => {
                console.error('Failed to open cache:', error);
            })
    );
});

// Fetch cached assets
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(error => {
                console.error('Failed to fetch:', error);
                return fetch(event.request);
            })
    );
});
