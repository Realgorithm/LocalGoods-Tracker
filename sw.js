// // sw.js (Service Worker)
// self.addEventListener('install', (event) => {
//   console.log('[SW] Installing Service Worker...');
//   event.waitUntil(
//     caches
//       .open('my-cache')
//       .then((cache) => {
//         console.log('[SW] Caching files...');
//         return cache.addAll([
//           '/',
//           '/home.php',
//           '/index.php',
//           '/header.php',
//           '/assets/icons/icon-192.png',
//           '/assets/icons/icon-512.png',
//         ]);
//       })
//       .catch(function (error) {
//         console.error('[SW] Caching failed:', error);
//       })
//   );
// });

// self.addEventListener('fetch', function (event) {
//   console.log('[SW] Fetch event for ', event.request.url);
//   event.respondWith(
//     caches
//       .match(event.request)
//       .then(function (response) {
//         return response || fetch(event.request);
//       })
//       .catch(function (error) {
//         console.error('[SW] Fetch failed:', error);
//       })
//   );
// });

// self.addEventListener('install', function(event) {
// 	event.waitUntil(
// 		caches.open('pwa').then(function(cache) {
// 			return cache.addAll([
// 				'/',
// 				'/ajax.php',
// 				'/script.js',
// 			]);
// 		})
// 	);
// });

// self.addEventListener('fetch', function(event) {
// 	event.respondWith(
// 		caches.open('pwa').then(function(cache) {
// 			return cache.match(event.request).then(function(response) {
// 				cache.addAll([event.request.url]);

// 				if(response) {
// 					return response;
// 				}

// 				return fetch(event.request);
// 			});
// 		})
// 	);
// });
const staticCacheName = "site-static-v1";
const dynamicCacheName = "site-dynamic-v1";
const assets = [
  "/",
  "index.php",
  "home.php",
  "navbar.php",
  "script.js",
  "assets/fonts/font.css",
  "assets/fontawesome/css/all.min.css",
  "assets/css/bootstrap.min.css",
  "assets/css/jquery.dataTables.min.css",
  "assets/css/dataTables.bootstrap5.min.css",
  "assets/css/select2.min.css",
  "assets/css/shepherd.css",
  "assets/css/style.css",
  "assets/fontawesome/js/all.min.js",
  "assets/jquery/jquery-3.6.0.min.js",
  "assets/js/popper.min.js",
  "assets/js/bootstrap.min.js",
  "assets/js/select2.min.js",
  "assets/jquery/jquery.dataTables.min.js",
  "assets/js/dataTables.bootstrap5.min.js",
  "assets/js/shepherd.min.js",
  "assets/js/tour.js",
  "assets/js/index.js",
  "loader.php",
  "fallback.php",
  "manifest.json",
];

const skipAssets = [
  "ajax.php?action=save_account",
  "ajax.php?action=add_product",
  "ajax.php?action=remove_product",
  "ajax.php?action=delete_shop",
  "ajax.php?action=delete_sales",
  "ajax.php?action=save_sales",
  "ajax.php?action=delete_product",
  "ajax.php?action=save_product",
  "ajax.php?action=save_customer",
  "ajax.php?action=delete_customer",
  "ajax.php?action=delete_receiving",
  "ajax.php?action=save_receiving",
  "ajax.php?action=delete_supplier",
  "ajax.php?action=save_supplier",
  "ajax.php?action=delete_expenses",
  "ajax.php?action=save_expenses",
  "ajax.php?action=delete_category",
  "ajax.php?action=save_category",
  "ajax.php?action=save_user",
  "ajax.php?action=save_user",
  "index.php?pages=pos",
];

//cache size limit function
const limitCacheSize = (name, size) => {
  caches.open(name).then((cache) => {
    cache.keys().then((keys) => {
      if (keys.length > size) {
        cache.delete(keys[0]).then(limitCacheSize(name, size));
      }
    });
  });
};

// install service worker
self.addEventListener("install", (event) => {
  // console.log('service worker has been installed');
  event.waitUntil(
    caches
      .open(staticCacheName)
      .then((cache) => {
        console.log("caching shell assets");
        cache.addAll(assets);
      })
      .catch((err) => {
        console.log("Error", err);
      })
  );
});

//activate service worker
self.addEventListener("activate", (event) => {
  //   console.log('service worker has been activated');
  event.waitUntil(
    caches.keys().then((keys) => {
      // console.log(keys); //
      return Promise.all(
        keys
          .filter((key) => key !== staticCacheName && key !== dynamicCacheName)
          .map((key) => caches.delete(key))
      );
    })
  );
});

// Fetch event - Handle requests
self.addEventListener("fetch", (event) => {
  const url = new URL(event.request.url);

  // Ignore requests to chrome-extension://
  if (event.request.url.startsWith("chrome-extension:")) {
    return; // Exit early if it's a chrome-extension request
  }

  // Handle requests to skip caching
  if (skipAssets.includes(url.pathname)) {
    event.respondWith(
      fetch(event.request).catch(() => {
        // Provide a fallback page if offline
        return caches.match("/fallback.php");
      })
    );
    return;
  }

  // Handle requests to the root URL and index.php
  if (url.pathname === "/" || url.pathname === "/index.php") {
    event.respondWith(
      fetch("/check-auth.php") // Server-side script to check authentication
        .then((response) => {
          if (response.ok) {
            // User is authenticated, fetch or return cached dashboard page
            // return caches
            //   .match("/index.php?page=dashboard")
            //   .then((cacheRes) => {
            //     if (cacheRes) {
            //       return cacheRes;
            //     }
                // Fetch from network if not in cache
                return fetch(event.request).then((fetchRes) => {
                  if (
                    fetchRes &&
                    fetchRes.status === 200 &&
                    fetchRes.type === "basic"
                  ) {
                    return caches.open(dynamicCacheName).then((cache) => {
                      cache.put(event.request, fetchRes.clone());
                      limitCacheSize(dynamicCacheName, 30);
                      return fetchRes;
                    });
                  }
                  return fetchRes;
                });
              // });
          } else {
            // User is not authenticated, fetch or return cached home page
            return caches.match("/home.php").then((cacheRes) => {
              return cacheRes || fetch("/home.php");
            });
          }
        })
        .catch(() => {
          // If authentication check fails or network error, return cached home page
          return caches.match("/home.php");
        })
    );
    return;
  }

  // Handle other requests
  event.respondWith(
    caches
      .match(event.request)
      .then((cacheRes) => {
        if (cacheRes) {
          return cacheRes; // Return cached response if found
        }

        // Fetch from network if not in cache
        return fetch(event.request).then((fetchRes) => {
          // Only cache valid HTTP/HTTPS requests
          if (event.request.url.startsWith("http")) {
            return caches.open(dynamicCacheName).then((cache) => {
              cache.put(event.request, fetchRes.clone());
              limitCacheSize(dynamicCacheName, 30);
              return fetchRes;
            });
          }
          return fetchRes;
        });
      })
      .catch(() => {
        // Provide a fallback page if offline
        if (event.request.url.indexOf(".php") > -1) {
          return caches.match("/fallback.php");
        }
      })
  );
});
