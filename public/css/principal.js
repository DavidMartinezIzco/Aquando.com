var feedDigital = new Array();
var listaTags = new Array();
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

function cargarAjustes() {
    var sel = document.getElementById("tagSel");
    sel.innerHTML = "";
    var arrEstaciones = JSON.stringify(estacionesUsu);
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Principal.php?opcion=ajustes',
            data: {
                arrEstaciones: arrEstaciones
            },
            success: function(tagsAnalog) {
                listaTags = tagsAnalog;
                console.log(tagsAnalog);
                for (var deposito in tagsAnalog) {
                    sel.innerHTML += "<optgroup label = '" + tagsAnalog[deposito][0]['nombre_estacion'] + "'>";
                    for (var tag in tagsAnalog[deposito]) {
                        var n_tag = tagsAnalog[deposito][tag]['nombre_tag'];
                        var id_tag = tagsAnalog[deposito][tag]['id_tag'];
                        sel.innerHTML += "<option value=" + id_tag + ">" + n_tag + "</option>";
                    }
                    sel.innerHTML += "</optgroup>";
                }

            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
}

function ajustes() {
    var menu = document.getElementById("ajustesSeccion");
    if (menu.style.display == 'none') {
        menu.style.display = 'block';

    } else {
        menu.style.display = 'none';
    }

    var ul = document.getElementById('widList');
    ul.onclick = function(event) {
        var wid = getEventTarget(event);
        sessionStorage.setItem('widget', wid.value);
        widgetSelec(wid.innerHTML);
    };

}


function widgetSelec(wid) {
    var seccion = document.getElementById('seccionAjustes');

    if (wid == 'widget 1') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>arriba a la izquierda</b></p><p>Señales disponibles:<select id="tagSel"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w1")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        cargarAjustes();
    }
    if (wid == 'widget 2') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>arriba a la derecha</b></p><p>Señales disponibles:<select id="tagSel"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w2")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        cargarAjustes();
    }
    if (wid == 'widget 3') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>abajo a la izquierda</b></p><p>Señales disponibles:<select id="tagSel"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w3")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        cargarAjustes();
    }
    if (wid == 'widget 4') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>abajo a la derecha</b></p><p>Señales disponibles:<select id="tagSel"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w4")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        cargarAjustes();
    }

}

function confirmarAjustesWidget(wid) {

    var widget = wid;
    var tag = document.getElementById("tagSel").value;

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Principal.php?opcion=confirmar&wid=' + widget + '&tag=' + tag + '&usu=' + usu + '&pwd=' + pwd,

            success: function() {
                console.log("widget configurado con exito");
            },
            error: function(e) {
                console.log(e);
            },
            // dataType: 'json'
        });
    });
}

function getEventTarget(e) {
    e = e || window.event;
    return e.target || e.srcElement;
}

function feedPrincipalCustom() {
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Principal.php?opcion=feed&usu=' + usu + '&pwd=' + pwd,

            success: function(feedAna) {
                console.log(feedAna);
            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
}