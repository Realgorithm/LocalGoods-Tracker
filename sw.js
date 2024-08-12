// sw.js (Service Worker)
self.addEventListener("install", (event) => {
  console.log("[SW] Installing Service Worker...");
  event.waitUntil(
    caches
      .open("my-cache")
      .then((cache) => {
        console.log("[SW] Caching files...");
        return cache.addAll([
          "home.php",
          "index.php",
          "header.php",
          "assets/icons/icon-192.png",
          "assets/icons/icon-512.png",
        ]);
      })
      .catch(function (error) {
        console.error("[SW] Caching failed:", error);
      })
  );
});

self.addEventListener("fetch", function (event) {
  console.log("[SW] Fetch event for ", event.request.url);
  event.respondWith(
    caches
      .match(event.request)
      .then(function (response) {
        return response || fetch(event.request);
      })
      .catch(function (error) {
        console.error("[SW] Fetch failed:", error);
      })
  );
});
