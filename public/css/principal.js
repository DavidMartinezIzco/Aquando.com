var feedDigital = new Array();
var listaTags = new Array();

//faltaría conseguir las coordenadas de cada estación para poder hacer mapas dinámicos

function mapas() {
    var map = L.map('conMapa').setView([42.77219, -1.62511], 11);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}@2x?access_token={accessToken}', {
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
                feedPrincipalCustom();
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

    //iconos:
    // alarma gen <i style="red" class="far fa-bell"></i>
    // alerta gen max min <i style="yellow" class="fas fa-exclamation-triangle"></i>
    // marcha gen <i style="gray" class="fas fa-cog"></i>
    // paro gen <i style="darkorange" class="fas fa-pause"></i>
    // ON <i style="color:lightseagreen" class="fas fa-power-off"></i>
    // OFF <i style="color:tomato" class="fas fa-power-off"></i> 

    for (var tag in feedDigital) {

        var iconoAlarma = '<i style="color:tomato" class="far fa-bell parpadeante"></i>';
        if (feedDigital[tag]['valor_alarma'].includes("Min") || feedDigital[tag]['valor_alarma'].includes("Max")) {
            iconoAlarma = '<i style="yellow" class="fas fa-exclamation-triangle"></i>';
        }
        if (feedDigital[tag]['valor_alarma'].includes("Marcha")) {
            iconoAlarma = '<i style="gray" class="fas fa-cog rotante"></i>';
        }
        if (feedDigital[tag]['valor_alarma'].includes("Paro")) {
            iconoAlarma = '<i style="tomato" class="fas fa-pause parpadeante"></i>';
        }
        if (feedDigital[tag]['valor_alarma'].includes("ON")) {
            iconoAlarma = '<i style="color:lightseagreen" class="fas fa-power-off"></i>';
        }
        if (feedDigital[tag]['valor_alarma'].includes("OFF")) {
            iconoAlarma = '<i style="color:tomato" class="fas fa-power-off parpadeante"></i> ';
        }

        if (pos == 1) {
            divSup += '<div class="digiIzq"><div id="digiWidTitulo">' + feedDigital[tag]['nombre'] + '</div><div id="digiWidOrigen">' + feedDigital[tag]['estacion'] + '</div><div id="digiWidMensaje"><span class="tooltiptext">' + feedDigital[tag]['valor_alarma'] + '</span>' + iconoAlarma + '  </div></div>';
        }
        if (pos == 2) {
            divSup += '<div class="digiDer"><div id="digiWidTitulo">' + feedDigital[tag]['nombre'] + '</div><div id="digiWidOrigen">' + feedDigital[tag]['estacion'] + '</div><div id="digiWidMensaje"><span class="tooltiptext">' + feedDigital[tag]['valor_alarma'] + '</span>' + iconoAlarma + '  </div></div>';
        }
        if (pos == 3) {
            divInf += '<div class="digiIzq"><div id="digiWidTitulo">' + feedDigital[tag]['nombre'] + '</div><div id="digiWidOrigen">' + feedDigital[tag]['estacion'] + '</div><div id="digiWidMensaje"><span class="tooltiptext">' + feedDigital[tag]['valor_alarma'] + '</span>' + iconoAlarma + '  </div></div>';
        }
        if (pos == 4) {
            divInf += '<div class="digiDer"><div id="digiWidTitulo">' + feedDigital[tag]['nombre'] + '</div><div id="digiWidOrigen">' + feedDigital[tag]['estacion'] + '</div><div id="digiWidMensaje"><span class="tooltiptext">' + feedDigital[tag]['valor_alarma'] + '</span>' + iconoAlarma + '  </div></div>';
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

    if (sessionStorage.getItem('listaTags') != null && sessionStorage.getItem('listaTags') != undefined) {
        listaTags = sessionStorage.getItem('listaTags');
        listaTags = JSON.parse(listaTags);
    }
    var i = 0;
    for (var index in listaTags) {
        i++;
    }
    if (i > 0) {
        for (var deposito in listaTags) {
            sel.innerHTML += "<optgroup label = '" + listaTags[deposito][0]['nombre_estacion'] + "'>";
            for (var tag in listaTags[deposito]) {
                var n_tag = listaTags[deposito][tag]['nombre_tag'];
                var id_tag = listaTags[deposito][tag]['id_tag'];
                sel.innerHTML += "<option value=" + id_tag + ">" + n_tag + "</option>";
            }
            sel.innerHTML += "</optgroup>";
        }
    } else {

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Principal.php?opcion=ajustes',
                data: {
                    arrEstaciones: arrEstaciones
                },
                success: function(tagsAnalog) {
                    listaTags = tagsAnalog;
                    sessionStorage.setItem('listaTags', JSON.stringify(listaTags));
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
        widgetSelec(wid.innerHTML);
    };
    widgetSelec('Widget 1');
}

function widgetSelec(val) {
    var seccion = document.getElementById('seccionAjustes');
    console.log(val);
    if (val == 'Widget 1') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>arriba a la izquierda</b></p><p>Señales disponibles:<select id="tagSel" style="margin-left:1%"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w1")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        document.getElementById("w1").style.backgroundColor = 'rgb(1, 168, 184)';
        document.getElementById("w2").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w3").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w4").style.backgroundColor = 'rgb(39, 45, 79)';
        cargarAjustes();
    }
    if (val == 'Widget 2') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>arriba a la derecha</b></p><p>Señales disponibles:<select id="tagSel" style="margin-left:1%"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w2")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        document.getElementById("w2").style.backgroundColor = 'rgb(1, 168, 184)';
        document.getElementById("w1").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w3").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w4").style.backgroundColor = 'rgb(39, 45, 79)';
        cargarAjustes();
    }
    if (val == 'Widget 3') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>abajo a la izquierda</b></p><p>Señales disponibles:<select id="tagSel" style="margin-left:1%"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w3")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        document.getElementById("w3").style.backgroundColor = 'rgb(1, 168, 184)';
        document.getElementById("w2").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w1").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w4").style.backgroundColor = 'rgb(39, 45, 79)';
        cargarAjustes();
    }
    if (val == 'Widget 4') {
        var msg = '<h3>Preferencias de inicio</h3><hr><form action="javascript:void(0);"><p>Selecciona una señal para mostrar <b>abajo a la derecha</b></p><p>Señales disponibles:<select id="tagSel" style="margin-left:1%"></select></p><button id="btnAceptarWidget" onclick=confirmarAjustesWidget("w4")>aceptar</button><button id="btnCancelarWidget" onclick="ajustes()">cancelar</button></form>';
        seccion.innerHTML = msg;
        document.getElementById("w4").style.backgroundColor = 'rgb(1, 168, 184)';
        document.getElementById("w2").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w3").style.backgroundColor = 'rgb(39, 45, 79)';
        document.getElementById("w1").style.backgroundColor = 'rgb(39, 45, 79)';
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

                document.getElementById("seccionAjustes").innerHTML += "<br><div id='ajustesRespuesta'>widget configurado con éxito</div>";
                feedPrincipalCustom();
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
                renderPrincipalCustom(feedAna);
            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
}

function renderPrincipalCustom(feed) {
    document.getElementById("prinDer").innerHTML = "";
    var w1 = "";
    var w2 = "";
    var w3 = "";
    var w4 = "";

    for (var wid in feed) {

        if (feed[wid]['widget'] == 'w1') {
            w1 += '<div  class="anaIzq" onclick="rotarCarrusel(this)">';
            w1 += '<div id="carrusel">';
            //primera vista
            w1 += '<div class="carr" id="gauw1">';
            w1 += "</div>";

            //segunda vista
            w1 += '<div class="carr" id="trendw1">';
            w1 += "</div>";

            //segunda vista
            w1 += '<div class="carr" id="agregw1">';
            w1 += "</div>";

            w1 += "</div></div>";

            //gauge + chart trend + chart agreg




        }
        if (feed[wid]['widget'] == 'w2') {
            w2 += '<div class="anaDer" onclick="rotarCarrusel(this)">';
            w2 += '<div id="carrusel">';
            //primera vista
            w2 += '<div class="carr" id="gauw2">';
            // w2 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w2 += '<p>' + feed[wid]['ultimo_valor']['valor'] + '</p>';
            w2 += "</div>";

            //segunda vista
            w2 += '<div class="carr" id="trendw2">';
            // w2 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w2 += '<p>' + 'aquí irá el trend diario' + '</p>';
            w2 += "</div>";

            //segunda vista
            w2 += '<div class="carr" id="agregw2">';
            // w2 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w2 += '<p>' + 'aquí irá el trend semanal' + '</p>';
            w2 += "</div>";

            w2 += "</div></div>";
        }
        if (feed[wid]['widget'] == 'w3') {
            w3 += '<div class="anaIzq" onclick="rotarCarrusel(this)">';
            w3 += '<div id="carrusel">';
            //primera vista
            w3 += '<div class="carr" id="gauw3">';
            // w3 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w3 += '<p>' + feed[wid]['ultimo_valor']['valor'] + '</p>';
            w3 += "</div>";

            //segunda vista
            w3 += '<div class="carr" id="trendw3">';
            w3 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            w3 += '<p>' + 'aquí irá el trend diario' + '</p>';
            w3 += "</div>";

            //segunda vista
            w3 += '<div class="carr" id="agregw3">';
            // w3 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w3 += '<p>' + 'aquí irá el trend semanal' + '</p>';
            w3 += "</div>";

            w3 += "</div></div>";
        }
        if (feed[wid]['widget'] == 'w4') {
            w4 += '<div class="anaDer" onclick="rotarCarrusel(this)">';
            w4 += '<div id="carrusel">';
            //primera vista
            w4 += '<div class="carr" id="gauw4">';
            // w4 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w4 += '<p>' + feed[wid]['ultimo_valor']['valor'] + '</p>';
            w4 += "</div>";

            //segunda vista
            w4 += '<div class="carr" id="trendw4">';
            // w4 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w4 += '<p>' + 'aquí irá el trend diario' + '</p>';
            w4 += "</div>";

            //segunda vista
            w4 += '<div class="carr" id="agregw4">';
            // w4 += "<h4>" + feed[wid]['nombre'] + " de " + feed[wid]['estacion'] + "</h4>";
            // w4 += '<p>' + 'aquí irá el trend semanal' + '</p>';
            w4 += "</div>";

            w4 += "</div></div>";
        }

    }
    var widSup = "<div id='widSup'>" + w1 + w2 + "</div>";
    var widInf = "<div id='widInf'>" + w3 + w4 + "</div>";
    var conPrinDer = widSup + widInf;
    document.getElementById("prinDer").innerHTML = conPrinDer;
    crearWidgetsChartsCustom(feed);
}

function crearWidgetsChartsCustom(feed) {
    var gauges = new Array();
    var trends = new Array();
    var agregs = new Array();


    for (var wid in feed) {
        var gaugeDom = document.getElementById("gau" + feed[wid]['widget']);
        var grafGau = echarts.init(gaugeDom);
        gauges[wid] = grafGau;
        var trendDom = document.getElementById('trend' + feed[wid]['widget']);
        var grafTrend = echarts.init(trendDom);
        trends[wid] = grafTrend;
        var agregDom = document.getElementById('agreg' + feed[wid]['widget']);
        var grafAgreg = echarts.init(agregDom);
        agregs[wid] = grafAgreg;

        var valor_actual = feed[wid]['ultimo_valor']['valor'];
        var nombre_dato = feed[wid]['ultimo_valor']['nombre_tag'] + " (" + feed[wid]['unidad'] + ") ";
        var nombre_estacion = feed[wid]['ultimo_valor']['nombre_estacion'];
        var trend_dia = feed[wid]['trend_dia'];
        var agreg_semanal = feed[wid]['agreg_semana'];

        //gauge con val actual
        var optGau = {
            grid: {
                left: '0%',
                right: '0%',
                top: '0%',
                bottom: '-5%',
                containLabel: true
            },
            title: {
                left: 'center',
                text: "Valor actual de " + nombre_dato + " de " + nombre_estacion,
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 12
                },
            },
            series: [{
                max: 10,
                min: 0,
                name: nombre_dato + " : " + nombre_estacion,
                type: 'gauge',
                itemStyle: {
                    color: 'rgb(1, 168, 184)'
                },
                progress: {
                    show: true
                },
                axisLine: {
                    show: true,
                    // lineStyle: {
                    //     width: 6,
                    //     color: [
                    //         [(minimo), 'tomato'],
                    //         [(maximo), 'rgb(39, 45, 79)'],
                    //         [1, 'tomato']
                    //     ]
                    // }

                },
                axisTick: {
                    show: true
                },
                axisLabel: {
                    show: false
                },
                splitLine: {
                    show: true
                },
                pointer: {
                    // icon: 'rect',
                    length: '80%',
                    width: 4,
                    itemStyle: {
                        color: 'tomato'
                    },
                },
                // max: maximoGraf,
                // min: 0,
                detail: {
                    show: true,
                    valueAnimation: true,
                    formatter: nombre_dato + ':{value}',
                    fontSize: 8
                },
                data: [{
                    value: valor_actual,
                }]
            }]
        };
        optGau && grafGau.setOption(optGau, true);


        //chart lineas trend diario
        var datos_dia = [];
        var horas_dia = [];
        for (var index in trend_dia) {
            datos_dia.push(trend_dia[index]['valor']);
            horas_dia.push(trend_dia[index]['fecha']);
        }

        optDia = {
            grid: {
                left: '5%',
                right: '5%',
                top: '12%',
                bottom: '1%',
                containLabel: true
            },
            title: {
                left: 'center',
                text: "Últimas 24h de " + nombre_dato + " de " + nombre_estacion,
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 10
                },
            },
            tooltip: {
                trigger: 'axis',
                icon: 'none',
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 10
                },
                axisPointer: {
                    type: 'line',
                    label: {
                        formatter: 'fecha y hora: {value}',
                        fontStyle: 'bold'
                    }
                }
            },
            xAxis: {
                inverse: true,
                show: true,
                type: 'category',
                data: horas_dia
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: nombre_dato,
                data: datos_dia,
                type: 'line',
                lineStyle: {
                    width: 0
                },
                areaStyle: {
                    show: true,
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: 'rgb(1, 168, 184)'
                        },
                        {
                            offset: 1,
                            color: 'rgb(39, 45, 79)'
                        }
                    ])
                },
                symbol: 'none',
                smooth: false
            }]
        };
        optDia && grafTrend.setOption(optDia, true);


        //chart barras de agregados semanal

        var max_agreg = [];
        var min_agreg = [];
        var fechas_agreg = [];
        for (var index in agreg_semanal) {
            max_agreg.push(agreg_semanal[index]['max']);
            min_agreg.push(agreg_semanal[index]['min']);
            fechas_agreg.push(agreg_semanal[index]['fecha']);
        }

        optionSemanal = {
            legend: {
                x: 'center',
                y: 'top',
                textStyle: {
                    fontWeight: 'normal',
                    fontSize: 10,
                },
                padding: 2,
                show: false
            },
            grid: {
                left: '5%',
                right: '5%',
                top: '12%',
                bottom: '1%',
                containLabel: true
            },
            title: {
                show: true,
                left: 'center',
                text: "Máximos y mínimos semanales " + nombre_dato + " de " + nombre_estacion,
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 10,
                    margin: 5
                },
            },
            tooltip: {
                show: true,
                trigger: 'axis',
                icon: 'none',
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 8
                },
                axisPointer: {
                    type: 'line',
                    label: {
                        formatter: 'fecha y hora: {value}',
                        fontStyle: 'bold'
                    }
                }
            },
            xAxis: {
                inverse: true,
                show: true,
                type: 'category',
                data: fechas_agreg
            },
            yAxis: {
                // name: nombre_dato + ' : ' + nombre_estacion,
                type: 'value'
            },
            series: [{
                name: 'Máximos de ' + nombre_dato,
                data: max_agreg,
                type: 'bar',

                itemStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [

                        { offset: 0, color: '#01a8b8' },
                        { offset: 1, color: '#272d4f' }

                    ])
                },
                emphasis: {
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#272d4f' },
                            { offset: 1, color: '#01a8b8' }
                        ])
                    }
                },
                symbol: 'none',
                smooth: false
            }, {
                name: 'Mínimos de ' + nombre_dato,
                data: min_agreg,
                type: 'bar',

                itemStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [

                        { offset: 0, color: '#1aff00' },
                        { offset: 1, color: '#307a27' }

                    ])
                },
                emphasis: {
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: '#1aff00' },
                            { offset: 1, color: '#307a27' }
                        ])
                    }
                },
                symbol: 'none',
                smooth: false
            }]
        };
        optionSemanal && grafAgreg.setOption(optionSemanal, true);
    }
    $('#menuIzq').bind('widthChange', function() {
        for (var wid in gauges) {
            gauges[wid].resize();
            trends[wid].resize();
            agregs[wid].resize();
        }
    });

}