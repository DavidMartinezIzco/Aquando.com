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
    serie = {};

    serie['name'] = nombreDato;
    serie['type'] = "line";
    serie['smooth'] = true;
    serie['sampling'] = "lttb";
    serie['areaStyle'] = { show: true };
    serie['data'] = [];
    serie['markline'] = { data: [] };

    for (var index in info['tag']) {
        serie['data'].push(info['tag'][index]['valor']);
        fechasTag.push(info['tag'][index]['fecha']);
    }

    for (var meta in info['meta']) {
        serie['markline']['data'].push({
            symbol: 'none',
            name: meta,
            lineStyle: {
                //implementar color
            },
            yAxis: info['meta'][meta],
            label: {
                formatter: '{b} ' + nombreDato + ': {c}',
                position: 'insideEnd',
                padding: [5, 20],
                borderColor: "rgba(0, 0, 0, 1)",
                borderRadius: [5, 5, 5, 5],
                borderWidth: 2
            }
        });
    }

    //metadata en intervalos
    if (document.getElementById("checkMaxInt").checked) {
        serie['markline']['data'].push({
            symbol: 'none',
            type: 'max',
            name: 'máximo intervalo',
            lineStyle: {
                normal: {
                    type: 'dashed',
                }
            },
            label: {
                formatter: '{b} ' + nombreDato + ': {c}',
                position: 'insideEndTop',
                padding: [5, 20],
                borderColor: "rgba(0, 0, 0, 1)",
                borderRadius: [5, 5, 5, 5],
                borderWidth: 2
            }
        });
    }
    if (document.getElementById("checkMinInt").checked) {
        serie['markline']['data'].push({
            symbol: 'none',
            type: 'min',
            name: 'mínimo intervalo',
            lineStyle: {
                normal: {
                    type: 'dashed',
                }
            },
            label: {
                formatter: '{b} ' + nombreDato + ': {c}',
                position: 'insideEndTop',
                padding: [5, 20],
                borderColor: "rgba(0, 0, 0, 1)",
                borderRadius: [5, 5, 5, 5],
                borderWidth: 2
            }
        });
    }
    if (document.getElementById("checkAvgInt").checked) {
        serie['markline']['data'].push({
            symbol: 'none',
            type: 'average',
            name: 'media intervalo',
            lineStyle: {
                normal: {
                    type: 'dashed',
                }
            },
            label: {
                formatter: '{b} ' + nombreDato + ': {c}',
                position: 'insideEndTop',
                padding: [5, 20],
                borderColor: "rgba(0, 0, 0, 1)",
                borderRadius: [5, 5, 5, 5],
                borderWidth: 2
            }
        });
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
    // option['dataZoom'] = [{
    //         type: 'slider',
    //         textStyle: {
    //             fontSize: 14,
    //             fontWeight: 'bold'
    //         },
    //         xAxisIndex: 0,
    //         start: 0,
    //         end: 10,
    //         filterMode: 'filter'
    //     },
    //     {
    //         type: 'slider',
    //         right: 20,
    //         textStyle: {
    //             fontSize: 14,
    //             fontWeight: 'bold'
    //         },
    //         yAxisIndex: 0,
    //         filterMode: 'filter'
    //     },
    //     {
    //         type: 'inside',
    //         throttle: 0,
    //         textStyle: {
    //             fontSize: 14,
    //             fontWeight: 'bold'
    //         },
    //         xAxisIndex: 10,
    //         start: 0,
    //         end: 10,
    //         filterMode: 'filter'
    //     },
    //     {
    //         type: 'inside',
    //         right: 20,
    //         throttle: 0,
    //         textStyle: {
    //             fontSize: 14,
    //             fontWeight: 'bold'
    //         },
    //         yAxisIndex: 0,

    //         filterMode: 'filter'
    //     }
    // ];

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


    //console.log(option);
    console.log(datosTagCustom['serie']);
    option && grafico.setOption(option, true);

}