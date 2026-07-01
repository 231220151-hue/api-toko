const CACHE_NAME = "toko-pwa-v5-dashboard-small-localhost";

const urlsToCache = [
    "/",
    "/index.html",
    "/login.html",
    "/app.js",
    "/manifest.json"
];

self.addEventListener("install", event => {

    event.waitUntil(

        caches.open(CACHE_NAME)
        .then(cache => {
            return cache.addAll(urlsToCache);
        })

    );

});

self.addEventListener("fetch", event => {

    event.respondWith(

        caches.match(event.request)
        .then(response => {

            return response || fetch(event.request);

        })

    );

});