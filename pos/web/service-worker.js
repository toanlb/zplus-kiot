/**
 * Service Worker for POS
 * Provides offline functionality
 */

// Cache names
const CACHE_NAME = 'pos-cache-v1';
const API_CACHE_NAME = 'pos-api-cache-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/css/pos.css',
    '/js/pos.js',
    '/js/cart.js',
    '/js/product.js',
    '/js/payment.js',
    '/js/pos-offline.js',
    '/images/logo.png',
    '/images/user-avatar.png',
    '/images/product-default.png',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js'
];

// API routes to cache with network-first strategy
const API_ROUTES = [
    '/pos/get-products',
    '/pos/get-categories',
    '/pos/get-product-details'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    console.log('Service Worker installing.');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker activating.');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames
                        .filter(cacheName => {
                            return (cacheName !== CACHE_NAME && cacheName !== API_CACHE_NAME);
                        })
                        .map(cacheName => {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        })
                );
            })
            .then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    
    // Skip non-GET requests and browser extensions
    if (request.method !== 'GET' || 
        url.origin !== self.location.origin && 
        !url.href.includes('cdnjs.cloudflare.com')) {
        return;
    }
    
    // API routes use network-first strategy
    if (API_ROUTES.some(route => url.pathname.includes(route))) {
        event.respondWith(networkFirstStrategy(request));
        return;
    }
    
    // Static assets use cache-first strategy
    event.respondWith(cacheFirstStrategy(request));
});

/**
 * Cache-first strategy
 * Try cache first, fallback to network
 */
async function cacheFirstStrategy(request) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        // Cache valid responses
        if (networkResponse.ok && networkResponse.status !== 206) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('Service Worker fetch failed:', error);
        
        // For HTML requests, return the offline page
        if (request.headers.get('Accept').includes('text/html')) {
            return caches.match('/offline.html') || new Response('You are offline');
        }
        
        // For images, return a default image
        if (request.url.match(/\.(jpg|jpeg|png|gif|svg)$/)) {
            return caches.match('/images/product-default.png');
        }
        
        // Return a generic error response
        return new Response('Network error happened', {
            status: 408,
            headers: { 'Content-Type': 'text/plain' }
        });
    }
}

/**
 * Network-first strategy
 * Try network first, fallback to cache
 */
async function networkFirstStrategy(request) {
    try {
        const networkResponse = await fetch(request);
        
        // Cache valid API responses
        if (networkResponse.ok) {
            const cache = await caches.open(API_CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.log('Service Worker: Network request failed, falling back to cache');
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // If no cached response, return a JSON error
        return new Response(JSON.stringify({
            success: false,
            message: 'You are offline and no cached data is available.',
            offline: true
        }), {
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Handle background sync for offline operations
self.addEventListener('sync', event => {
    if (event.tag === 'sync-pos-data') {
        console.log('Service Worker: Syncing POS data...');
        event.waitUntil(syncPosData());
    }
});

/**
 * Sync POS data in background
 */
async function syncPosData() {
    try {
        const clients = await self.clients.matchAll();
        if (clients && clients.length > 0) {
            // Notify client to sync data
            clients.forEach(client => {
                client.postMessage({
                    type: 'SYNC_DATA'
                });
            });
        }
    } catch (error) {
        console.error('Service Worker: Error during sync', error);
    }
}

// Log errors
self.addEventListener('error', function(event) {
    console.error('Service Worker error:', event.error);
});