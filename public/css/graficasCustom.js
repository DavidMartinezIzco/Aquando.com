var datosTagCustom = new Array;
datosTagCustom['serie'] = [];
datosTagCustom['fechas'] = [];
var serie = {};

//reestablece los filtros por defecto
function limpiar() {
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';
    document.getElementById('compararSel').value = 'nada';
    document.getElementById('opcionesTag').selectedIndex = 0;
    document.getElementById('opciones').selectedIndex = 0;

    tagsEstacion(document.getElementById('opciones').value);
    aplicarOpciones();

    setTimeout(function() {
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}

//saca una captura del grafico en panatalla
function imprimir() {
    html2canvas(document.querySelector('#grafica')).then(function(canvas) {
        guardar(canvas.toDataURL(), 'grafico.png');
    });

}

//descarga la captura del grafico
function guardar(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

        link.href = uri;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    } else {

        window.open(uri);

    }
}

function tagsEstacionCustom(id_estacion) {

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Graficas.php?estacion=' + id_estacion + '&opcion=tags',
            success: function(tags) {
                document.getElementById("opcionesTag").innerHTML = "";
                var e = 0;
                sessionStorage.setItem('tagsAct', JSON.stringify(tags));
                for (var tag in tags) {
                    if (e == 0) {
                        //document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + " selected>" + tags[tag]['nombre_tag'] + "</option>";
                        document.getElementById("opcionesTag").innerHTML += '<li><input type="checkbox" name="checkTag" style="visibility: hidden;" value="' + tags[tag]['id_tag'] + '" id = ' + tags[tag]['id_tag'] + '><label for = "' + tags[tag]['id_tag'] + '" style="box-sizing: none"> ' + tags[tag]['nombre_tag'] + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + tags[tag]['id_tag'] + '" style="visibility:hidden" title="color" list="coloresTagGraf"></label></li>';

                    } else {
                        //document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + ">" + tags[tag]['nombre_tag'] + "</option>";
                        document.getElementById("opcionesTag").innerHTML += '<li> <input type = "checkbox" name="checkTag" style = "visibility: hidden;" value="' + tags[tag]['id_tag'] + '" id = ' + tags[tag]['id_tag'] + ' ><label for = "' + tags[tag]['id_tag'] + '" style="box-sizing: none"> ' + tags[tag]['nombre_tag'] + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + tags[tag]['id_tag'] + '" style="visibility:hidden" title="color" list="coloresTagGraf"></label ></li>';
                    }
                    e++;
                }


            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });
}

function mostrarOpciones() {
    if (document.getElementById("zonaControles").style.width == '1%') {
        document.getElementById("zonaControles").style.width = '29.5%';
        document.getElementById("zonaControles").style.left = '70%';
        document.getElementById("zonaGraficos").style.width = '70%';

    } else {
        document.getElementById("zonaControles").style.width = '1%';
        document.getElementById("zonaControles").style.left = '100%';
        document.getElementById("zonaGraficos").style.width = '98%';

    }

}

function aplicarCustom() {
    //hay que actualizar el etiquetado de tags (en series y legend)
    //los metadatos en markline no seestán mostrando
    //los datazooms en X e Y están deshabilitados de momento.
    //mirar a ver si en vez de actualizar todo, ver si se pueden reutilizar
    //los estados anteriores (x optimizar vaya)
    //faltan de implementar los colores custom (igual solo en los series)
    //al refrescar a veces desaparecen series enteras (bug)

    datosTagCustom = new Array;
    datosTagCustom['serie'] = [];
    datosTagCustom['fechas'] = [];
    var ajustesTag = [];
    var checkTags = document.querySelectorAll('input[name=checkTag]:checked')
    for (var i = 0; i < checkTags.length; i++) {
        ajustesTag.push(checkTags[i].value)
    }
    var ajustesMeta = [];
    var checkMetas = document.querySelectorAll('input[name=checkMeta]:checked')
    for (var i = 0; i < checkMetas.length; i++) {
        ajustesMeta.push(checkMetas[i].value)
    }

    var fechaInicio = document.getElementById('fechaInicio').value;
    var fechaFin = document.getElementById('fechaFin').value;
    var id_estacion = document.getElementById('opciones').value;

    var metas = "";

    for (var meta in ajustesMeta) {
        metas += ajustesMeta[meta] + "/";
    }

    for (var ajusteTag in ajustesTag) {
        infoTags(id_estacion, ajustesTag, ajustesTag[ajusteTag], metas, fechaInicio, fechaFin);
    }

}

function infoTags(estacion, ajustesTag, tag, metas, fechaIni, fechaFin) {

    $.ajax({
        type: 'GET',
        url: 'A_GraficasCustom.php?estacion=' + estacion + '&id_tag=' + tag + '&fechaIni=' + fechaIni + '&fechaFin=' + fechaFin + '&meta=' + metas + '&opcion=tag',
        success: function(datosTag) {
            prepararTag(datosTag, tag);
            if (ajustesTag.at(-1) == tag) {
                renderGrafico(ajustesTag);
            }
        },
        error: function() {
            console.log("error");
        },
        dataType: 'json'
    });

}

function prepararTag(info, tag) {
    var fechasTag = [];
    var nombreDato = "Info " + tag;
    var tagsAct = JSON.parse('[' + sessionStorage.getItem("tagsAct") + ']');

    for (var tindex in tagsAct[0]) {

        if (tagsAct[0][tindex]['id_tag'] == tag) {
            nombreDato = tagsAct[0][tindex]['nombre_tag'];
        }
    }


    serie = {};

    serie['name'] = nombreDato;
    serie['symbol'] = 'none';
    serie['type'] = "line";
    serie['smooth'] = true;
    serie['sampling'] = "lttb";
    serie['areaStyle'] = { show: true };
    serie['data'] = [];
    serie['markLine'] = { data: [] };


    for (var index in info['tag']) {
        serie['data'].push(info['tag'][index]['valor']);
        fechasTag.push(info['tag'][index]['fecha']);
    }

    for (var meta in info['meta']) {
        var colorMeta = '';

        var valMeta = info['meta'][meta];
        var marcaMeta = {};

        if (meta == 'max') {
            colorMeta = document.getElementById("colorMax").value;
            marcaMeta['name'] = "maximo gen";
        }
        if (meta == 'min') {
            colorMeta = document.getElementById("colorMin").value;
            marcaMeta['name'] = "minimo gen";
        }
        if (meta == 'avg') {
            colorMeta = document.getElementById("colorAvg").value;
            marcaMeta['name'] = "media gen";
        }

        //letrero con nombre y datos del markline
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'lightgray';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = 'black';
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;

        marcaMeta['yAxis'] = valMeta;
        serie['markLine']['data'].push(marcaMeta);
    }


    //metadata en intervalos
    if (document.getElementById("checkMaxInt").checked) {
        serie['markLine']['data'].push({
            name: 'max intervalo',
            type: 'max',
            lineStyle: {
                normal: {
                    type: 'dashed',
                    color: document.getElementById("colorMaxInt").value
                }
            }
        });
        var marcaMeta = {};
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEndTop';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = 'black';
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;

        marcaMeta['yAxis'] = valMeta;
        serie['markLine']['data'].push(marcaMeta);
    }
    if (document.getElementById("checkMinInt").checked) {
        serie['markLine']['data'].push({
            name: 'min intervalo',
            type: 'min',
            lineStyle: {
                normal: {
                    type: 'dashed',
                    color: document.getElementById("colorMinInt").value
                }
            }
        });
        var marcaMeta = {};
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEndBottom';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = 'black';
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;

        marcaMeta['yAxis'] = valMeta;
        serie['markLine']['data'].push(marcaMeta);
    }
    if (document.getElementById("checkAvgInt").checked) {
        serie['markLine']['data'].push({
            name: 'media intervalo',
            type: 'average',
            lineStyle: {
                normal: {
                    type: 'dashed',
                    color: document.getElementById("colorAvgInt").value
                }
            }
        });
        var marcaMeta = {};
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = 'black';
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;

        marcaMeta['yAxis'] = valMeta;
        serie['markLine']['data'].push(marcaMeta);
    }

    datosTagCustom['serie'].push(serie);
    datosTagCustom['fechas'].push(fechasTag);


}

function renderGrafico(tags) {

    //llegan ajustesTags en tags
    //para organizar los values y las fechas
    var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;
    nombreDato = "info";


    //falta incluir nombres de los tags

    //leyenda
    option = {

        legend: {
            x: 'center',
            y: 'top',
            textStyle: {
                fontWeight: 'normal',
                fontSize: 10
            },
            padding: 1,
            // data: [{
            //     name: nombreDato,
            //     icon: 'circle',
            // }],
            //se podría hacer que los Meta no se muestren por defecto pero para eso necesitamos
            //meter los nombre en unas variables porque se desformatea concatenando las que ya hay.
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '10%',
            containLabel: true
        },
    };

    //herramientas
    option['tooltip'] = {
        trigger: 'axis',
        textStyle: {
            fontStyle: 'bold',
            fontSize: 20
        },

        axisPointer: {
            type: 'line',
            label: {
                formatter: 'fecha y hora: {value}',
                fontStyle: 'bold'
            }
        }
    };

    //ejes X
    option['xAxis'] = {

        boundaryGap: false,
        splitNumber: 10,
        data: datosTagCustom['fechas'][0],
        label: {
            show: true,
            position: 'top',
            color: "black",
            fontSize: 30,
        },

    };

    option['yAxis'] = [{
        type: 'value',
        label: {
            show: true
        },
        boundaryGap: [0, '100%'],
    }];

    //controles de los ejes
    option['dataZoom'] = [{
            type: 'slider',
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            xAxisIndex: 0,
            start: 0,
            end: 10,
            filterMode: 'filter'
        },
        {
            type: 'inside',
            throttle: 0,
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            xAxisIndex: 0,
            start: 0,
            end: 10,
            filterMode: 'filter'
        }
    ];

    //series y datos en el grafico
    option['series'] = [];

    for (var index in datosTagCustom['serie']) {
        option['series'].push(datosTagCustom['serie'][index]);

    }

    $(window).keyup(function() {
        grafico.resize();
    });

    document.getElementById("conPrincipal").onclick = function() {
        setTimeout(grafico.resize(), 500);
    };

    document.getElementById('grafica').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }
    document.getElementById('zonaControles').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }

    console.log(option);
    //console.log(option);
    option && grafico.setOption(option, true);

}