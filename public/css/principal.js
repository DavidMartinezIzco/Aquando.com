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

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Principal.php?opcion=refresh',
            data: {
                arrdatos: arrdatos
            },
            success: function(feedDigi) {
                feedDigital = feedDigi;
                renderFeedDigi();
            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
}

function renderFeedDigi() {
    var pos = 1;
    var divSup = '<div id="widSup">';
    var divInf = '<div id="widInf">';
    var gridWidDigi = document.getElementById("prinIzqInf");
    //recorrer el feed digital y crear un widget para cada uno
    for (var tag in feedDigital) {
        if (pos == 1) {
            divSup += '<div class="digiIzq">' + feedDigital[tag]['nombre'] + '<br>' + feedDigital[tag]['valor_alarma'] + '<br>' + feedDigital[tag]['estacion'] + '  </div>';
        }
        if (pos == 2) {
            divSup += '<div class="digiDer">' + feedDigital[tag]['nombre'] + '<br>' + feedDigital[tag]['valor_alarma'] + '<br>' + feedDigital[tag]['estacion'] + '  </div>';
        }
        if (pos == 3) {
            divInf += '<div class="digiIzq">' + feedDigital[tag]['nombre'] + '<br>' + feedDigital[tag]['valor_alarma'] + '<br>' + feedDigital[tag]['estacion'] + '  </div>';
        }
        if (pos == 4) {
            divInf += '<div class="digiDer">' + feedDigital[tag]['nombre'] + '<br>' + feedDigital[tag]['valor_alarma'] + '<br>' + feedDigital[tag]['estacion'] + '  </div>';
        }
        pos++;
    }
    divSup += '</div>';
    divInf += '</div>';
    gridWidDigi.innerHTML = divSup + divInf;

}

function renderFeedGene() {
    //obtener en actualizar los datos del feed de los widgets ya definidos por el ususaro
    //orderarlos y crear un widget para cada uno
    //render esos widgets
}

function rotarCarrusel(carr) {
    var elem = carr.children[0];
    var posi = elem.style.right;
    var compo = elem.children;
    if (posi != '200%') {
        if (posi == '100%') {
            posi = '200%';
            compo[0].style.opacity = '0%';
            compo[1].style.opacity = '0%';
            compo[2].style.opacity = '100%';
        }
        if (posi == 0 || posi == '0px' || posi == '0%') {
            posi = '100%';
            compo[0].style.opacity = '0%';
            compo[1].style.opacity = '100%';
            compo[2].style.opacity = '0%';
        }
    } else {
        posi = 0;
        compo[0].style.opacity = '100%';
        compo[1].style.opacity = '0%';
        compo[2].style.opacity = '0%';
    }
    elem.style.right = posi;
}