var feedDigital = new Array();

//faltaría conseguir las coordenadas de cada estación para poder hacer mapas dinámicos

function mapas() {
    var map = L.map('conMapa').setView([42.77219, -1.62511], 11);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoicmdyYXZlc3MiLCJhIjoiY2t6ZTFycXlkMmV3aDJ2bjk1d2Z0dzJvayJ9.LE3efQIzvbIOWOBDqazqyA'
    }).addTo(map);

    //los marker ya veré como hacerlos dinámicos
    var berroa = L.marker([42.77238, -1.62480]).addTo(map);
    var cein = L.marker([42.75458, -1.63709]).addTo(map);
    cein.bindPopup("<b>Esto es Cein</b><br>ubi 2").openPopup();
    berroa.bindPopup("<b>Esto es Berroa</b><br>ubi 1").openPopup();

}

function actualizar() {

    var datos = {};
    datos['nombre'] = sessionStorage.getItem('nousu');
    datos['pwd'] = sessionStorage.getItem('pwd');

    var arrdatos = JSON.stringify(datos);
    console.log(arrdatos);
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Principal.php?opcion=refresh',
            data: {
                arrdatos: arrdatos
            },
            success: function(feedDigi) {
                feedDigital = feedDigi;
                // console.log(feedDigi);
            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
}

function renderFeedDigi() {
    //recorrer el feed digital y crear un widget para cada uno

}