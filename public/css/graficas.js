//reestablece los filtros por defecto
function limpiar() {
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';
    var i = 1;
    while (i <= 9) {
        if (document.getElementsByName(i)[0].checked) {
            document.getElementsByName(i)[0].checked = false;
        }
        i++
    }

    document.getElementsByName(1)[0].checked = true;
    document.getElementsByName(2)[0].checked = true;
    document.getElementsByName(3)[0].checked = true;

    document.getElementById("opciones").value = 'p1';
    document.getElementById("tipoRender").value = "histo";
    alternarOpciones("histo");
    aplicarOpciones();


    setTimeout(function() {
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}



//aplica las opciones de los controles
function aplicarOpciones() {
    var datosR = [];
    var datos = {
        "info 1": [320, 332, 301, 334, 390, 330, 320],
        "info 2": [120, 132, 101, 134, 90, 230, 210],
        "info 3": [220, 182, 191, 234, 290, 330, 310],
        "info 4": [150, 232, 201, 154, 190, 330, 410],
        "info 5": [862, 1018, 964, 1026, 1679, 1600, 1570],
        "info 6": [620, 732, 701, 734, 1090, 1130, 1120],
        "info 7": [120, 132, 101, 134, 290, 230, 220],
        "info 8": [60, 72, 71, 74, 190, 130, 110],
        "info 9": [62, 82, 91, 84, 109, 110, 120]
    };

    var i = 1;
    while (i <= 9) {
        if (document.getElementsByName(i)[0].checked) {
            datosR.push(datos["info " + i]);
        }
        i++
    }
    var tipo = document.getElementById("tipoRender").value
    renderGrafico(tipo, datosR);


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

        datosR.forEach(function(valores, index, array) {

            var datorender = {
                name: "dato " + index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: valores
            }


            series.push(datorender);

        });
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

        datosR.forEach(function(valores, index, array) {

            var datorender = {
                name: "dato " + index,
                type: formato,
                smooth: true,
                emphasis: {
                    focus: 'series'
                },
                data: valores
            }


            series.push(datorender);

        });
        option['series'] = series;
    }

    if (tipo == "tarta") {

        formato = "pie";

        var datos = [];
        datosR.forEach(function(valores, index, array) {
            datos.push({ value: valores[6], name: 'dato ' + index });
        });

        var series = [{
            name: "Prototipo Chart",
            type: formato,
            radius: '70%',
            data: datos
        }]
        option['series'] = series;
        option['tooltip'] = { trigger: 'item' };
    }

    if (tipo == "histo") {

        let base = +new Date(2010, 1, 1);
        let undia = 24 * 3600 * 1000;
        let fecha = [];
        var datos = [Math.random() * 300];
        for (let i = 1; i < 4300; i++) {
            var now = new Date((base += undia));
            fecha.push([now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'));
            datos.push(Math.round((Math.random() - 0.5) * 20 + datos[i - 1]));
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
            data: fecha
        };

        option['yAxis'] = {
            type: 'value',
            boundaryGap: [0, '100%']
        };

        option['dataZoom'] = [{
            type: 'inside',
            start: 80,
            end: 100,

        }, {
            start: 80,
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
            data: datos
        }];

        option['series'] = series;



    }


    option && grafico.setOption(option, true);

    document.getElementById("infoGraf").innerHTML = "formato: " + tipo + "<br>Periodo: Semanal";

    $(window).keyup(function() {
        setTimeout(grafico.resize(), 500);
    });

    document.getElementById('conPrincipal').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }
    document.getElementById('grafica').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }
    document.getElementById('zonaControles').onmouseover = function() {
        setTimeout(grafico.resize(), 500);
    }


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

}