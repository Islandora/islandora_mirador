self.addEventListener('activate', function (event) {
  console.log('Service Worker: claiming control...');
  return self.clients.claim();
});

self.addEventListener('fetch', function (event) {
  if (event.request.destination === "image" && new URL(event.request.url).pathname.startsWith('/cantaloupe/iiif/') && new URL(location).searchParams.has('token')) {
    console.log('Service Worker: fetching...');
    var token = new URL(location).searchParams.get('token');
    event.respondWith(
      fetch(event.request, {
        headers: {
          'Authorization': 'Bearer ' + token,
          'token': token
        },
        mode: "cors",
        credentials: "include"
      })
    );
  }
});
