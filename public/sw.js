//para instalacion
//sistema ofline
//guardar en cache

self.addEventListener('install', function(event){
  event.waitUntil(
    caches.open('sw-cache').then(function(cache){
      return cache.add('index');
    })
  );
});

//con peticion
//comprobar cache
self.addEventListener('fetch',function(event){
  event.respondWith(
    caches.match(event.request).then(function(response){
      return response | fetch(event.request);
    })
  );
});
