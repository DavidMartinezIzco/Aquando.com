var datosM = new Array();
var datosR = new Array();
//reestablece los filtros por defecto
function limpiar() {
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';
    var i = 1;
    while (i <= 9) {

        if (document.getElementsByName(i)[0]) {
            if (document.getElementsByName(i)[0].checked) {
                document.getElementsByName(i)[0].checked = false;
            }
        }
        i++
    }

    document.getElementById("opciones").value = 'e1';

    aplicarOpciones();


    setTimeout(function() {
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}


function metaDatosTag(id_tag, id_estacion) {

    $.ajax({
        type: 'GET',
        url: 'A_Graficas.php?opcion=meta&tag=' + id_tag + '&estacion=' + id_estacion,
        success: function(meta) {
            datosM['max'] = meta['max'];
            datosM['min'] = meta['min'];
            datosM['avg'] = meta['avg'];

            $.ajax({
                type: 'GET',
                url: 'A_Graficas.php?estacion=' + id_estacion + '&tag=' + id_tag + '&opcion=render',
                success: function(histo) {
                    datosR = histo;
                    //var tipo = document.getElementById("tipoRender").value;
                    renderGrafico(datosR);
                },
                error: function() {
                    console.log("error");
                },
                dataType: 'json'
            });

        },
        error: function() {
            console.log("error");
        },
        dataType: 'json'
    });

}

//aplica las opciones de los controles
function aplicarOpciones() {
    var idEstacion = document.getElementById("opciones").value;
    var idTag = document.getElementById("opcionesTag").value;
    metaDatosTag(idTag, idEstacion);
}

function tagsEstacion(id_estacion) {

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Graficas.php?estacion=' + id_estacion + '&opcion=tags',
            success: function(tags) {
                document.getElementById("opcionesTag").innerHTML = "";
                document.getElementById("compararSel").innerHTML = "<option value='nada' selected>Nada</option>";
                var e = 0;
                sessionStorage.setItem('tagsAct', JSON.stringify(tags));
                for (var tag in tags) {
                    if (e == 0) {
                        document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + " selected>" + tags[tag]['nombre_tag'] + "</option>";
                    } else {
                        document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + ">" + tags[tag]['nombre_tag'] + "</option>";
                    }
                    document.getElementById("compararSel").innerHTML += "<option value=" + tags[tag]['id_tag'] + ">" + tags[tag]['nombre_tag'] + "</option>";
                    e++;
                }
                aplicarOpciones();

            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });
}

//prepara el grafico
//sabemos que funciona, pero cuando tengamos datos muy bestias tal vez empiece a ir lenta.
//debería repartir algunas tareas para venir hechas desde servidor

function renderGrafico(datosR) {

    var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;
    var nombreDato = "Info";
    var tagsAct = JSON.parse('[' + sessionStorage.getItem("tagsAct") + ']');

    for (var tindex in tagsAct[0]) {
        if (tagsAct[0][tindex]['id_tag'] == document.getElementById("opcionesTag").value || tagsAct[0][tindex]['id_tag'] == document.getElementById("compararSel").value) {
            nombreDato = tagsAct[0][tindex]['nombre_tag'];
        }
    }


    //Ajustes

    option = {
        //las etiquetas de "legend" en ocasiones se cambian por otras viejas que ya no se están representando
        //aun no se por que es, pero pinta un fallo gordo en la logica en las funciones de comparación
        legend: {
            x: 'center',
            y: 'top',
            textStyle: {
                fontWeight: 'normal',
                fontSize: 10
            },
            padding: 1,
            data: [{
                    name: nombreDato,
                    icon: 'circle',
                },
                {
                    name: 'Maximo Total ' + nombreDato,
                    icon: 'circle',
                },
                {
                    name: 'Minimo Total ' + nombreDato,
                    icon: 'circle',
                },
                {
                    name: 'Media Total ' + nombreDato,
                    icon: 'circle',
                }
            ],
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
    if (sessionStorage.getItem('leyenda') && document.getElementById("compararSel").value != "nada") {
        var leyendaV = JSON.parse('[' + sessionStorage.getItem('leyenda') + ']');
        option['legend']['data'] = leyendaV[0]['data'].concat(option['legend']['data']);
    }

    sessionStorage.setItem('leyenda', JSON.stringify(option['legend']));


    //series de meta 
    //esto igual lo hago desde servidor para quitarle curro al renderizado
    var fechas = new Array();
    var serieMax = new Array();
    var serieMin = new Array();
    var serieAvg = new Array();

    for (var index in datosR) {
        fechas.push(datosR[index]['fecha']);
        serieMax.push(datosM['max']);
        serieMin.push(datosM['min']);
        serieAvg.push(datosM['avg']);

    }

    //el chandrío que mas habría que optimizar o pasar a servidor
    var calidades = new Array();
    for (var index in datosR) {
        calidades.push(datosR[index]['calidad']);
    }
    var valores = new Array();
    for (var index in datosR) {
        for (var valor in datosR[index]) {
            if (valor != "fecha" && valor != "calidad") {
                valores.push(datosR[index][valor]);
            }
        }
    }
    var serieMin = new Array();
    for (var i in fechas) {
        serieMin[i] = datosM['min'];
    }
    var serieAvg = new Array();
    for (var i in fechas) {
        serieAvg[i] = datosM['avg'];
    }



    //Ajustes
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

    option['xAxis'] = {

        boundaryGap: false,
        splitNumber: 10,
        data: fechas,
        label: {
            show: true,
            position: 'top',
            color: "black",
            fontSize: 30,
        },

    };

    //aqui tiene que haber un fallo que solapa las etiquetas de los ejes propios
    //pero seguro que es porque eres tonto y se solucione facil
    option['yAxis'] = [{
        type: 'value',
        name: nombreDato,
        label: {
            show: true
        },
        boundaryGap: [0, '100%'],

    }, ];
    if (sessionStorage.getItem('yaxis') && document.getElementById("compararSel").value != "nada") {
        var yaxisV = JSON.parse('[' + sessionStorage.getItem('yaxis') + ']');
        option['yAxis'] = yaxisV[0].concat(option['yAxis']);
        console.log(option['yAxis']);
    }
    sessionStorage.setItem('yaxis', JSON.stringify(option['yAxis']));

    //en el dataZoom molaría que el scroll lo tuviera el eje X que por alguna razón ahora el scroll
    //lo ha robado el eje Y. Si lo pueden tener los dos --> s u b l i m e
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
            type: 'slider',
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            yAxisIndex: 0,
            filterMode: 'filter'
        },
        {
            type: 'inside',
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            xAxisIndex: 10,
            start: 0,
            end: 10,
            filterMode: 'filter'
        },
        {
            type: 'inside',
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            yAxisIndex: 0,
            filterMode: 'filter'
        }
    ];



    var series = [{
            name: nombreDato,
            type: 'line',
            smooth: true,
            symbol: 'none',
            sampling: 'lttb',
            areaStyle: {
                show: true,
            },
            data: valores,

            markLine: {

                data: [{
                        symbol: 'none',
                        type: 'average',
                        name: 'media',
                        lineStyle: {
                            normal: {
                                type: 'dashed',
                                color: 'darkseagreen',
                            }
                        },
                        label: {
                            formatter: '{b} ' + nombreDato + ': {c}',
                            position: 'insideEnd',
                            backgroundColor: 'darkseagreen',
                            color: 'white',
                            padding: [5, 20],
                            borderColor: "rgba(0, 0, 0, 1)",
                            borderRadius: [5, 5, 5, 5],
                            borderWidth: 2
                        }
                    },
                    {
                        symbol: 'none',
                        type: 'max',
                        name: 'maximo',
                        lineStyle: {
                            normal: {
                                type: 'dashed',
                                color: 'tomato',
                            }
                        },
                        label: {
                            formatter: '{b} ' + nombreDato + ': {c}',
                            position: 'insideEndTop',

                            backgroundColor: 'tomato',
                            color: 'white',
                            padding: [5, 20],
                            borderColor: "rgba(0, 0, 0, 1)",
                            borderRadius: [5, 5, 5, 5],
                            borderWidth: 2
                        }
                    },
                    {
                        symbol: 'none',
                        type: 'min',
                        name: 'minino',
                        lineStyle: {
                            normal: {
                                type: 'dashed',
                                color: 'white',
                            }
                        },
                        label: {
                            formatter: '{b} ' + nombreDato + ': {c}',
                            position: 'insideEndBottom',

                            backgroundColor: 'white',
                            color: 'black',
                            padding: [5, 20],
                            borderColor: "rgba(0, 0, 0, 1)",
                            borderRadius: [5, 5, 5, 5],
                            borderWidth: 2
                        }
                    }
                ],
            }

        },
        {
            name: 'Maximo Total ' + nombreDato,
            type: 'line',
            silent: true,
            symbol: 'none',
            sampling: 'lttb',
            itemStyle: {},
            data: serieMax,
        },
        {
            name: 'Minimo Total ' + nombreDato,
            silent: true,
            type: 'line',
            symbol: 'none',
            sampling: 'lttb',
            itemStyle: {},
            data: serieMin,
        },
        {
            name: 'Media Total ' + nombreDato,
            silent: true,
            type: 'line',
            symbol: 'none',
            sampling: 'lttb',
            itemStyle: {},
            data: serieAvg,
        }

    ];

    option['series'] = series;



    //estos even handlers son para los cambios de tamaño del grafico
    //igual habría que ampliarlos con cuidado pero de momento sirven
    $(window).keyup(function() {
        grafico.resize();
    });

    document.getElementById("conPrincipal").onclick = function() {
        setTimeout(grafico.resize(), 500);
    };

    document.getElementById('conPrincipal').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }
    document.getElementById('grafica').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }
    document.getElementById('zonaControles').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }

    if (document.getElementById("compararSel").value != "nada") {
        //hay que tener guardado el yaxis, legend, series(particular), nombre, series(de maximos y minimos)?
        //se guardan despues de generarse al final
        //al final tambien guardo los metadata (maximos minimos y eso)
        var seriesV = JSON.parse('[' + sessionStorage.getItem('series') + ']');

        option['yAxis'] = option['yAxis'].concat(yaxisV[0]);
        option['series'] = seriesV[0].concat(option['series']);


    }
    sessionStorage.setItem('series', JSON.stringify(series));
    option && grafico.setOption(option, true);


}

//muestra o esconde las opciones de los graficos
function mostrarOpciones() {
    if (document.getElementById("zonaControles").style.width == '1%') {
        document.getElementById("zonaControles").style.width = '19.5%';
        document.getElementById("zonaControles").style.left = '80%';
        document.getElementById("zonaGraficos").style.width = '80%';

    } else {
        document.getElementById("zonaControles").style.width = '1%';
        document.getElementById("zonaControles").style.left = '100%';
        document.getElementById("zonaGraficos").style.width = '98%';

    }

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

function alternarOpciones(repren) {


    switch (repren) {
        case "histo":
            document.getElementById("infoRepren").style.opacity = "50%";
            document.getElementById("infoRepren").disabled = true;
            document.getElementById("fechaInicio").style.opacity = "50%";
            document.getElementById("fechaInicio").disabled = true;
            document.getElementById("fechaFin").style.opacity = "50%";
            document.getElementById("fechaFin").disabled = true;
            document.getElementById("opciones").style.opacity = "50%";
            document.getElementById("opciones").disabled = true;
            break;

            // case "linea":
            //     break;

            // case "barra":
            //     break;

        case "tarta":
            document.getElementById("infoRepren").style.opacity = "100%";
            document.getElementById("infoRepren").disabled = false;
            document.getElementById("fechaInicio").style.opacity = "50%";
            document.getElementById("fechaInicio").disabled = true;
            document.getElementById("fechaFin").style.opacity = "50%";
            document.getElementById("fechaFin").disabled = true;
            document.getElementById("opciones").style.opacity = "50%";
            document.getElementById("opciones").disabled = true;
            break;

        default:
            document.getElementById("infoRepren").disabled = false;
            document.getElementById("fechaInicio").disabled = false;
            document.getElementById("fechaFin").disabled = false;
            document.getElementById("opciones").disabled = false;

            document.getElementById("infoRepren").style.opacity = "100%";
            document.getElementById("fechaInicio").style.opacity = "100%";
            document.getElementById("fechaFin").style.opacity = "100%";
            document.getElementById("opciones").style.opacity = "100%";
            break;
    }
    aplicarOpciones();

}


//la funcion de comparación de gráficos.
//la chicha la has llevado toda a renderGrafico()
//se queda por si acabo cambiando la logica entera de la comparacion

//otra vez :S
function comparar() {

    if (document.getElementById("compararSel").value != "nada") {
        var idEstacionCom = document.getElementById("opciones").value;
        var idTagCom = document.getElementById("compararSel").value;
        metaDatosTag(idTagCom, idEstacionCom);
    } else {
        aplicarOpciones();
    }
}