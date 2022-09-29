//para instalación
//guardar en cache
self.addEventListener('install',function(event){
    event.waitUntil(
        caches.open('sw-cache').then(function(cache){
            return cache.add('../index.php');
        })
    );
});


//con peticion
self.addEventListener('fetch',function(event){
    event.respondWith(
        //probar cache
        caches.match(event.request).then(function(response){
            //devolver si hay respuesta o volver a intentarlo
            return response || fetch(event.request);
        })
    );
});