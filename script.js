// let installEvent = null;
// let installButton = document.getElementById("install");

// if(localStorage["pwa-enabled"]) {
// 	startPwa();
// } else {
// 	startPwa(true);
// }

// function startPwa(firstStart) {
// 	localStorage["pwa-enabled"] = true;

// 	if(firstStart) {
// 		location.reload();
// 	}

// 	window.addEventListener("load", () => {
// 		navigator.serviceWorker.register("sw.js")
// 		.then(registration => {
// 			console.log("Service Worker is registered", registration);
// 		})
// 		.catch(err => {
// 			console.error("Registration failed:", err);
// 		});
// 	});

// 	window.addEventListener("beforeinstallprompt", (e) => {
// 		e.preventDefault();
// 		console.log("Ready to install...");
// 		installEvent = e;
// 		document.getElementById("install").style.display = "initial";
// 	});

// 	setTimeout(cacheLinks, 500);

// 	function cacheLinks() {
// 		caches.open("pwa").then(function(cache) {
// 			let linksFound = [];
// 			document.querySelectorAll("li a").forEach(function(a) {
// 				linksFound.push(a.href);
// 			});

// 			cache.addAll(linksFound);
// 		});
// 	}

// 	if(installButton) {
// 		installButton.addEventListener("click", function() {
// 			installEvent.prompt();
// 		});
// 	}
// }

let installEvent = null;
let installButton = document.getElementById("install");

if (localStorage.getItem("pwa-enabled")) {
  startPwa();
} else {
  startPwa(true);
}

function startPwa(firstStart) {
  localStorage.setItem("pwa-enabled", "true");

  if (firstStart) {
    location.reload();
  }

  window.addEventListener("load", () => {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker
        .register("sw.js")
        .then((registration) => {
          console.log("Service Worker is registered", registration);
        })
        .catch((err) => {
          console.error("Registration failed:", err);
        });
    }
  });

  window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    console.log("Ready to install...");
    installEvent = e;
    installButton.style.display = "initial";
  });

  // setTimeout(cacheLinks, 500);

  // function cacheLinks() {
  //   caches.open("pwa").then(function (cache) {
  //     let linksFound = [];
  //     document.querySelectorAll("li a").forEach(function (a) {
  //       linksFound.push(a.href);
  //     });

  //     cache.addAll(linksFound);
  //   });
  // }

  if (installButton) {
    installButton.addEventListener("click", function () {
      if (installEvent) {
        installEvent.prompt();
      }
    });
  }
}
