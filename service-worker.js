/**
 * Copyright 2016 Google Inc. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

// DO NOT EDIT THIS GENERATED OUTPUT DIRECTLY!
// This file should be overwritten as part of your build process.
// If you need to extend the behavior of the generated service worker, the best approach is to write
// additional code and include it using the importScripts option:
//   https://github.com/GoogleChrome/sw-precache#importscripts-arraystring
//
// Alternatively, it's possible to make changes to the underlying template file and then use that as the
// new base for generating output, via the templateFilePath option:
//   https://github.com/GoogleChrome/sw-precache#templatefilepath-string
//
// If you go that route, make sure that whenever you update your sw-precache dependency, you reconcile any
// changes made to this original template file with your modified copy.

// This generated service worker JavaScript will precache your site's resources.
// The code needs to be saved in a .js file at the top-level of your site, and registered
// from your pages in order to be used. See
// https://github.com/googlechrome/sw-precache/blob/master/demo/app/js/service-worker-registration.js
// for an example of how you can register this script and handle various service worker events.

/* eslint-env worker, serviceworker */
/* eslint-disable indent, no-unused-vars, no-multiple-empty-lines, max-nested-callbacks, space-before-function-paren, quotes, comma-spacing */
'use strict';

var precacheConfig = [["README.md","98fd2ce6af08d52be7904fa46587f80f"],["account.php","9c635cebd69686cceb9028781863d88a"],["add_product.php","c327b7000901a2f13e4779a89f0f7508"],["admin_class.php","40da514cea9e15c086500dbd04619c35"],["admin_login.php","6fee9774bbc91833851c46e1f000618f"],["ajax.php","bf4f07c1108d4f88d670a32b5b0c413c"],["assets/Capture.PNG","4d5c362059a41880d1a9d1a06b1b4716"],["assets/css/bootstrap.min.css","9e95ff2feaa5195ac9ff56c97baf12b1"],["assets/css/bootstrap.min.css.map","1a8b1c497ee4bda639e18129852b523e"],["assets/css/dataTables.bootstrap5.min.css","1f151083c80e4b708277c1c8341cc1dd"],["assets/css/jquery.dataTables.min.css","7edbedfe0a1ce44a4197e477048711aa"],["assets/css/select2.min.css","32dd009436bd851fa512b1f9496c3c17"],["assets/css/shepherd.css","4b43c49836c24874ccb139547b04210c"],["assets/css/style.css","96f0aba9a780a00180bec2f0fc75964b"],["assets/fontawesome/css/all.min.css","91e894c15cf722354ed5d2db4ebdaa00"],["assets/fontawesome/js/all.min.js","75d708f51d16729ce7457c429c76aca0"],["assets/fontawesome/webfonts/fa-brands-400.ttf","56c8d80832e37783f12c05db7c8849e2"],["assets/fontawesome/webfonts/fa-brands-400.woff2","715d593456fa02fe72a008a72398f5be"],["assets/fontawesome/webfonts/fa-regular-400.ttf","370dd5af19f8364907b6e2c41f45dbbf"],["assets/fontawesome/webfonts/fa-regular-400.woff2","2d89b49ac28614e9ccd9c81308b5120c"],["assets/fontawesome/webfonts/fa-solid-900.ttf","adec7d6f310bc577f05e8fe06a5daccf"],["assets/fontawesome/webfonts/fa-solid-900.woff2","237f4a0afbdb652fb2330ee7e1567dd3"],["assets/fontawesome/webfonts/fa-v4compatibility.ttf","38fec6307db2547752379b51322ded10"],["assets/fontawesome/webfonts/fa-v4compatibility.woff2","7c428138d15b872f193c40828f6fa0d3"],["assets/fonts/font.css","38ec09cd649de82549a89e519bac2696"],["assets/icons/apple-touch-icon.png","1a03c9f6c2ef0d9628bb87981245a546"],["assets/icons/browserconfig.xml","a493ba0aa0b8ec8068d786d7248bb92c"],["assets/icons/favicon-16x16.png","1bba83e506974a6af25ce4d1d5c08076"],["assets/icons/favicon-32x32.png","83339969562fb06a10ac86fb6da5f76f"],["assets/icons/favicon.ico","c690d4fa92e2449f386a0182877b1a1f"],["assets/icons/icon-192.png","4f48eb81db46fe0c64b7bfb7c5706103"],["assets/icons/icon-512.png","6f91d91815b256ea5e0cf9e1811966f5"],["assets/icons/mstile-150x150.png","b900d80eff5b8407df28d6e481e9e8d8"],["assets/icons/safari-pinned-tab.svg","4436b7015a343c7f642d4ca505481324"],["assets/img/1600398180_no-image-available.png","94c0c57d53b1ee9771925957f29d149c"],["assets/img/1600415460_avatar2.png","229a2d322a57b76e0d8b8ce600c33880"],["assets/img/1719653820_paint.png","c449d1dcfe2e70a7c2187d9f2d884b16"],["assets/img/1721986860_company.png","8c100f5757a6303ab2d772458cd8c792"],["assets/img/1722322380_1722161302822.jpg","a607d988e07a18130d02aedf0f45859c"],["assets/img/1722497820_1722161302828.jpg","60346da23bf3cbc834d8bda975e00a34"],["assets/img/1722609600_hero-bg.jpg","17e06cc3eb75f5ac4df4364519ad5f3d"],["assets/img/1722609660_hero-bg.jpg","17e06cc3eb75f5ac4df4364519ad5f3d"],["assets/img/1722613140_feature2.jpg","b90c92859058cf1ef99502ab65dabf9f"],["assets/img/20_20_cookies_classic_butter.png","b1135bcacd3af170db351ca247e95263"],["assets/img/20_20_cookies_classic_cashew.png","5dbd7a2dbbfb33dbb99a9f0f8bb92e71"],["assets/img/20_20_gold_cookies.png","f532d9647a766be4a0e9dfdf3892acd3"],["assets/img/20_20_nice.png","a24c07b799a4e990e85a8c589a7745ed"],["assets/img/about_us.png","79494610dbd73a55fe8b5dab31ec12e8"],["assets/img/coconut_cookies.png","ad4e9fd1c5cde41b494d6082be5ca11b"],["assets/img/company.jpg","4b4d76993e5962469420107edc2ea2e5"],["assets/img/company.png","58e07a7a3ada951fa8898e7a0fda46e4"],["assets/img/contact.png","3575087976e3825ee4ddea413233bc2c"],["assets/img/fab_bourbon.png","fd36dfd0325529d107811752c3e14a87"],["assets/img/fabio_chocolate.png","d5d427bf73accbac0442846f25f5662a"],["assets/img/feature1.png","146d4cb9eedefd117e68413ef3420edf"],["assets/img/feature2.png","c2603f8e062cbbcee12cfebe5eb1a8bc"],["assets/img/feature3.png","2f610a10ebcd634fc919ea1b50f92db1"],["assets/img/googly.png","761bb075d35373b8a972be0d8b53171e"],["assets/img/happy_happy_cookies.png","257b4733bf1a40b266ec449cf64e6ebd"],["assets/img/hero-bg.png","82d446b326c801408f3bffc19fb5c654"],["assets/img/jeera_wonder.png","b70da968860b02b428185897c8797ffa"],["assets/img/krackjack.png","89f0f4c70e99ae85a4e6dbd0c2d3647c"],["assets/img/krackjack_butter_masala.png","0ed8a2f903ba04a0a6e4f1aeaa1763cc"],["assets/img/krackjack_jeera.png","af55e6577d43272b60d619408f6372b3"],["assets/img/login_url_error.jpg","03f9c1029398028bd91a796bd8da8e34"],["assets/img/maaza.png","64f5092b5a7059df50309c4f96ae167a"],["assets/img/maaza1.jpg","774ff96f41d26e1078da992a2930ae9b"],["assets/img/magix_chocolate.png","d463e0125e3e623676a7ab3d1429288d"],["assets/img/magix_elaichi.png","d11aee76070533a1a9d56dc7ec158d92"],["assets/img/magix_orange.png","0eb020976cf6a8b8db5b676ea1eb6a93"],["assets/img/monaco.png","726a26e0bd11cb9197a8ff321233d5fe"],["assets/img/monaco_piri_piri.png","4dbad709f76b4c9a74b211d36858d346"],["assets/img/monaco_pizza.png","83401f9fd1dbcdefda1db4ad8a0863e8"],["assets/img/monaco_zeera.png","0ee7c732f88bebbaec6f8bea4f4364f4"],["assets/img/parle_g.png","bdecbfaa290cfa1ab07cea681466d3d2"],["assets/img/parle_g_chhota_bheem.png","69d41e295cf914899e79fb76e320b28e"],["assets/img/parle_g_gold.png","7528f9a29246790bdbdebc6645ab933c"],["assets/img/parle_marie.png","d2df7db7ce7c180e557215e9ee037e5f"],["assets/img/thumps_up.png","20d4dc7d92bf02d8d4c801e9687efb99"],["assets/img/thumps_up2.jpg","58b53da9b8201eba32ba9aa65cc59a0c"],["assets/img/top_crackers.png","298344033e580e505fc97c6b995f9a46"],["assets/img/top_spin_crackers.png","68c8c1724f7556e50119944674bfc0f5"],["assets/jquery/jquery-3.6.0.min.js","2557bc98fdca31b3fdee229a5a4a2f14"],["assets/jquery/jquery.dataTables.min.js","ce1d6403e75b5dcbf619dc7f7bf945e6"],["assets/js/bootstrap.min.js","a25ae912a62c472dfe28bc736f3c9044"],["assets/js/dataTables.bootstrap5.min.js","061cc73cea33f86977c77e95a13452a3"],["assets/js/index.js","5e999a8e3574cf4924c0361cfc08db07"],["assets/js/popper.min.js","053bc2b68f696d22f2b195b56f378a85"],["assets/js/select2.min.js","fba9db03fd0a41087d1d4d5aa0ad9714"],["assets/js/shepherd.min.js","77f3b8aca820e91fe4cfa7d02297bfcd"],["assets/js/shepherd.min.js.map","68392cac67466498b37d32469339bdd0"],["assets/js/tour.js","3c893e99cb8c2e0524fe2effc5825177"],["assets/uploads/combined.png","0ec3532f4db166d42e881088d739080d"],["assets/uploads/dark.png","979146f731cfec79c3a8899d03aab703"],["assets/uploads/light.png","1b73eafe0527bc26f80a82c00626f86d"],["assets/uploads/localgoods-tracker.png","d7c9696ad9b1a6496e6e61a5057fdd79"],["categories.php","0720ebf2aa8c42da152a4f0e7afa1ad9"],["credit.php","9b76a1557fa7223dfb6562a1aefb9200"],["creditlist.php","603c3df9eefa4fc93b9ee95bdcc0b1bf"],["customer.php","bb5c1d19756f370f6a7bf7bb649d5a30"],["dashboard.php","3d9b0a8a7fbd36d1e3561a5e0dbd7239"],["database/central_db.sql","7608fb1ec80699baa99333f241167aa9"],["db_connect.php","b8ccbe04f394c32cf19739ac9d6209d9"],["error.html","a5cdfde8cfafbcc3d88273bd7e39af4f"],["example.html","0c293a41a3ee5624b4a2e8efe37ff9ef"],["example.php","c75e5a6ce7b201dfa8a2f5d05ecc47ba"],["expenses.php","48830c4ffba8f5cb9d0e73f04ec4af3e"],["fetch_data.php","e3cf0912f18f271618ad3f474da31625"],["get_products.php","30dd8d87fd448eaca24c6a2b1a832e66"],["header.php","39811aa7901fceff046386a63da0a1a4"],["home.php","0c0e1a170b5c2d88e7f11a65acaa123c"],["important.md","ae87580abc9a72c98dffced4bb9c7bb1"],["index.html","2dae0532fe59ed4f710826e3cf9ac41c"],["index.php","e332d21013b407d5eb13489a36008178"],["inventory.php","42bc793e8a774a00e17dcf1579a70779"],["loader.php","f85357a51d6bd7dd8f5fcfa564c0d9bd"],["login.php","985d1d3b011af8d33a2dd55996e71405"],["manage_receiving.php","6d677fd400823b8d9d2808634f678df8"],["manage_user.php","2c5e1228f248dc6d5f60790569967131"],["manifest.json","afb64fdbdc17aa9ac0c246b382ca840f"],["navbar.php","0d809eab29de47ff24a09877c5982012"],["pos.php","d9370ddd6c0995da61e8bdb3d3de6cb0"],["print_sales.php","3b8220214fc7ecc7f6f06a197cfa296c"],["product.php","faf2aaaa82177592fa587ca45b649b9e"],["receiving.php","e382916de9148b491da19a59fe434c1e"],["sales.php","84e73ef078d58e873ba2273b08a10c7a"],["script.js","708cf75d552a8af8f3c456edbe1f1488"],["shops.php","f6d779a95316cf22b229cfe944fb4846"],["signup.php","79991f5ad8929e7dae8c80de5706d72b"],["style.css","d41d8cd98f00b204e9800998ecf8427e"],["supplier.php","064005d6a4b5fbc80ac28c5c42224c41"],["sw.js","dd8aba874ec2619b6eace2678a0b0e41"],["users.php","2015e1bd4897c02981d7066cda8cc7e1"],["view_order.php","eba194ee638dd5f4602a2248987e09fb"]];
var cacheName = 'sw-precache-v3-sw-precache-' + (self.registration ? self.registration.scope : '');


var ignoreUrlParametersMatching = [/^utm_/];



var addDirectoryIndex = function(originalUrl, index) {
    var url = new URL(originalUrl);
    if (url.pathname.slice(-1) === '/') {
      url.pathname += index;
    }
    return url.toString();
  };

var cleanResponse = function(originalResponse) {
    // If this is not a redirected response, then we don't have to do anything.
    if (!originalResponse.redirected) {
      return Promise.resolve(originalResponse);
    }

    // Firefox 50 and below doesn't support the Response.body stream, so we may
    // need to read the entire body to memory as a Blob.
    var bodyPromise = 'body' in originalResponse ?
      Promise.resolve(originalResponse.body) :
      originalResponse.blob();

    return bodyPromise.then(function(body) {
      // new Response() is happy when passed either a stream or a Blob.
      return new Response(body, {
        headers: originalResponse.headers,
        status: originalResponse.status,
        statusText: originalResponse.statusText
      });
    });
  };

var createCacheKey = function(originalUrl, paramName, paramValue,
                           dontCacheBustUrlsMatching) {
    // Create a new URL object to avoid modifying originalUrl.
    var url = new URL(originalUrl);

    // If dontCacheBustUrlsMatching is not set, or if we don't have a match,
    // then add in the extra cache-busting URL parameter.
    if (!dontCacheBustUrlsMatching ||
        !(url.pathname.match(dontCacheBustUrlsMatching))) {
      url.search += (url.search ? '&' : '') +
        encodeURIComponent(paramName) + '=' + encodeURIComponent(paramValue);
    }

    return url.toString();
  };

var isPathWhitelisted = function(whitelist, absoluteUrlString) {
    // If the whitelist is empty, then consider all URLs to be whitelisted.
    if (whitelist.length === 0) {
      return true;
    }

    // Otherwise compare each path regex to the path of the URL passed in.
    var path = (new URL(absoluteUrlString)).pathname;
    return whitelist.some(function(whitelistedPathRegex) {
      return path.match(whitelistedPathRegex);
    });
  };

var stripIgnoredUrlParameters = function(originalUrl,
    ignoreUrlParametersMatching) {
    var url = new URL(originalUrl);
    // Remove the hash; see https://github.com/GoogleChrome/sw-precache/issues/290
    url.hash = '';

    url.search = url.search.slice(1) // Exclude initial '?'
      .split('&') // Split into an array of 'key=value' strings
      .map(function(kv) {
        return kv.split('='); // Split each 'key=value' string into a [key, value] array
      })
      .filter(function(kv) {
        return ignoreUrlParametersMatching.every(function(ignoredRegex) {
          return !ignoredRegex.test(kv[0]); // Return true iff the key doesn't match any of the regexes.
        });
      })
      .map(function(kv) {
        return kv.join('='); // Join each [key, value] array into a 'key=value' string
      })
      .join('&'); // Join the array of 'key=value' strings into a string with '&' in between each

    return url.toString();
  };


var hashParamName = '_sw-precache';
var urlsToCacheKeys = new Map(
  precacheConfig.map(function(item) {
    var relativeUrl = item[0];
    var hash = item[1];
    var absoluteUrl = new URL(relativeUrl, self.location);
    var cacheKey = createCacheKey(absoluteUrl, hashParamName, hash, false);
    return [absoluteUrl.toString(), cacheKey];
  })
);

function setOfCachedUrls(cache) {
  return cache.keys().then(function(requests) {
    return requests.map(function(request) {
      return request.url;
    });
  }).then(function(urls) {
    return new Set(urls);
  });
}

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return setOfCachedUrls(cache).then(function(cachedUrls) {
        return Promise.all(
          Array.from(urlsToCacheKeys.values()).map(function(cacheKey) {
            // If we don't have a key matching url in the cache already, add it.
            if (!cachedUrls.has(cacheKey)) {
              var request = new Request(cacheKey, {credentials: 'same-origin'});
              return fetch(request).then(function(response) {
                // Bail out of installation unless we get back a 200 OK for
                // every request.
                if (!response.ok) {
                  throw new Error('Request for ' + cacheKey + ' returned a ' +
                    'response with status ' + response.status);
                }

                return cleanResponse(response).then(function(responseToCache) {
                  return cache.put(cacheKey, responseToCache);
                });
              });
            }
          })
        );
      });
    }).then(function() {
      
      // Force the SW to transition from installing -> active state
      return self.skipWaiting();
      
    })
  );
});

self.addEventListener('activate', function(event) {
  var setOfExpectedUrls = new Set(urlsToCacheKeys.values());

  event.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.keys().then(function(existingRequests) {
        return Promise.all(
          existingRequests.map(function(existingRequest) {
            if (!setOfExpectedUrls.has(existingRequest.url)) {
              return cache.delete(existingRequest);
            }
          })
        );
      });
    }).then(function() {
      
      return self.clients.claim();
      
    })
  );
});


self.addEventListener('fetch', function(event) {
  if (event.request.method === 'GET') {
    // Should we call event.respondWith() inside this fetch event handler?
    // This needs to be determined synchronously, which will give other fetch
    // handlers a chance to handle the request if need be.
    var shouldRespond;

    // First, remove all the ignored parameters and hash fragment, and see if we
    // have that URL in our cache. If so, great! shouldRespond will be true.
    var url = stripIgnoredUrlParameters(event.request.url, ignoreUrlParametersMatching);
    shouldRespond = urlsToCacheKeys.has(url);

    // If shouldRespond is false, check again, this time with 'index.html'
    // (or whatever the directoryIndex option is set to) at the end.
    var directoryIndex = 'index.html';
    if (!shouldRespond && directoryIndex) {
      url = addDirectoryIndex(url, directoryIndex);
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond is still false, check to see if this is a navigation
    // request, and if so, whether the URL matches navigateFallbackWhitelist.
    var navigateFallback = '';
    if (!shouldRespond &&
        navigateFallback &&
        (event.request.mode === 'navigate') &&
        isPathWhitelisted([], event.request.url)) {
      url = new URL(navigateFallback, self.location).toString();
      shouldRespond = urlsToCacheKeys.has(url);
    }

    // If shouldRespond was set to true at any point, then call
    // event.respondWith(), using the appropriate cache key.
    if (shouldRespond) {
      event.respondWith(
        caches.open(cacheName).then(function(cache) {
          return cache.match(urlsToCacheKeys.get(url)).then(function(response) {
            if (response) {
              return response;
            }
            throw Error('The cached response that was expected is missing.');
          });
        }).catch(function(e) {
          // Fall back to just fetch()ing the request if some unexpected error
          // prevented the cached response from being valid.
          console.warn('Couldn\'t serve response for "%s" from cache: %O', event.request.url, e);
          return fetch(event.request);
        })
      );
    }
  }
});







