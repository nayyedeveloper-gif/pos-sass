const CACHE_NAME = 'pos-system-v1';
const urlsToCache = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/build/assets/app-CXbO63wn.css',
    '/build/assets/app-D4CFpKGe.js',
    '/favicon.ico',
    // Add other static assets as needed
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
            .catch(err => {
                console.log('Cache failed:', err);
            })
    );
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip chrome extensions and other protocols
    if (event.request.url.startsWith('chrome-extension://') || 
        event.request.url.startsWith('chrome://') ||
        !event.request.url.startsWith('http')) {
        return;
    }

    // Skip Livewire and API requests
    if (event.request.url.includes('/livewire/') || 
        event.request.url.includes('/api/') ||
        event.request.url.includes('/_livewire/')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Cache hit - return response
                if (response) {
                    return response;
                }

                // Fetch from network
                return fetch(event.request)
                    .then(response => {
                        // Check if valid response
                        if (!response || response.status !== 200 || response.type === 'error') {
                            return response;
                        }

                        // Clone the response for caching
                        const responseToCache = response.clone();

                        // Cache static assets only
                        if (event.request.url.match(/\.(js|css|png|jpg|jpeg|svg|gif|woff|woff2|ttf|eot)$/)) {
                            caches.open(CACHE_NAME)
                                .then(cache => {
                                    cache.put(event.request, responseToCache);
                                })
                                .catch(err => {
                                    console.log('Cache put failed:', err);
                                });
                        }

                        return response;
                    })
                    .catch(error => {
                        console.log('Fetch failed:', error);
                        // Try to return offline page for navigation requests
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                        // For other requests, return a basic error response
                        return new Response('Network error', {
                            status: 408,
                            statusText: 'Request Timeout'
                        });
                    });
            })
            .catch(error => {
                console.log('Cache match failed:', error);
                // Fallback to network
                return fetch(event.request).catch(() => {
                    if (event.request.mode === 'navigate') {
                        return caches.match('/offline.html');
                    }
                    return new Response('Offline', {
                        status: 503,
                        statusText: 'Service Unavailable'
                    });
                });
            })
    );
});

// Background sync for offline orders (future enhancement)
self.addEventListener('sync', event => {
    if (event.tag === 'sync-orders') {
        event.waitUntil(syncOrders());
    }
});

async function syncOrders() {
    // Placeholder for syncing offline orders
    console.log('Syncing orders...');
}

// Push notifications (future enhancement)
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'New notification',
        icon: '/images/icon-192x192.png',
        badge: '/images/icon-72x72.png',
        vibrate: [200, 100, 200],
    };

    event.waitUntil(
        self.registration.showNotification('POS System', options)
    );
});
