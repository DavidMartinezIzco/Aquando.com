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

//aplica las opciones de los controles
function aplicarOpciones() {

    var datosR = new Array();
    var idEstacion = document.getElementById("opciones").value;
    var idTag = document.getElementById("opcionesTag").value;



    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Graficas.php?estacion=' + idEstacion + '&tag=' + idTag + '&opcion=render',
            success: function(histo) {
                datosR = histo;
                var tipo = document.getElementById("tipoRender").value;
                renderGrafico(tipo, datosR);
            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });

}

function tagsEstacion(id_estacion) {


    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Graficas.php?estacion=' + id_estacion + '&opcion=tags',
            success: function(tags) {
                console.log(tags);
                document.getElementById("opcionesTag").innerHTML = "";
                for (var tag in tags) {
                    document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + ">" + tags[tag]['nombre_tag'] + "</option>";
                }

            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });
}

//prepara el grafico
function renderGrafico(tipo, datosR) {

    var chartDom = document.getElementById('grafica');
    var grafico = echarts.init(chartDom);
    var option;
    var formato = "";

    //Ajustes
    option = {

        legend: {},
        grid: {
            left: '3%',
            right: '4%',
            bottom: '10%',
            containLabel: true
        },
    };

    if (tipo == "barra") {
        formato = "bar";

        option['xAxis'] = [{
            type: 'category',
            data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
        }]
        option['yAxis'] = [{
            type: 'value'
        }]

        var series = []
        for (var index in datosR) {

            var datorender = {
                name: index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: datosR[index]
            }


            series.push(datorender);

        };


        option['series'] = series;
        option['tooltip'] = {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        }
    }

    if (tipo == "linea") {
        formato = "line";
        option['tooltip'] = {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        }
        option['xAxis'] = [{
            type: 'category',
            data: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
        }]
        option['yAxis'] = [{
            type: 'value'
        }]

        var series = []

        for (var index in datosR) {

            var datorender = {
                name: index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: datosR[index]
            }


            series.push(datorender);

        };
        option['series'] = series;
    }

    if (tipo == "tarta") {

        formato = "pie";

        var datos = [];
        for (var index in datosR) {
            var dato = { "value": datosR[index][6], "name": index };
            datos.push(dato);
        };
        var series = [{
            name: "protoChart",
            type: formato,
            radius: '70%',
            data: datos
        }]
        option['series'] = series;
        option['tooltip'] = { trigger: 'item' };
    }

    //historicos todavia no coge datos de servidor
    if (tipo == "histo") {

        var fechas = new Array();
        for (var index in datosR) {
            fechas.push(datosR[index]['fecha']);
        }
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


        //Ajustes
        option['tooltip'] = {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        };

        option['xAxis'] = {
            type: 'category',
            boundaryGap: false,
            data: fechas
        };

        option['yAxis'] = {
            type: 'value',
            boundaryGap: [0, '100%']
        };

        option['dataZoom'] = [{
            type: 'inside',
            start: 92,
            end: 100,

        }, {
            start: 92,
            end: 100
        }];


        var series = [{
            name: 'Datos',
            type: 'line',
            symbol: 'none',
            sampling: 'lttb',
            itemStyle: {
                color: 'rgb(39,45,79)'
            },
            areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgb(1, 168, 184)'
                    },
                    {
                        offset: 1,
                        color: 'rgb(39,45,79)'
                    }
                ])
            },
            data: valores
        }];

        option['series'] = series;

    }

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

    option && grafico.setOption(option, true);

    document.getElementById("infoGraf").innerHTML = "formato: " + tipo + "<br>Periodo: Semanal";


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