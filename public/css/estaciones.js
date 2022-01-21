var datosDigi = Array();
var datosAnalog = Array();
var consignas = Array();
var todoDato = Array();
var todoTrends = Array();

//actualizar la info de la seccion estacion
//vas a separar este motor para el texto de los widgets con el ultimo dato
//de otro que usarás para sacar los ultimos 7 de lo que toque

function actualizar(id_estacion) {

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Estacion.php?opcion=actualizar&estacion=' + id_estacion + '&tipo=todos',
            success: function(datos) {
                filtrarDatos(datos);
            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });

}

function trendsTags() {

    //aqui quieres sacar los trends (datos max de los ultimos 7 dias)
    //tendras que sacar de uno a uno en un bucle js o pasar el bucle a AJAX
    //luego ya veremos que seguro que la cagas asi que tienes tiempo para pensarlo

    var arrTags = JSON.stringify(datosAnalog);
    var id_estacion = estacion;
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            data: { arrTags: arrTags },
            contentType: 'application/json;charset=utf-8',
            url: 'A_Estacion.php?opcion=trends&estacion=' + id_estacion + '&tipo=todos',
            success: function(trends) {
                todoTrends = trends;
                console.log(todoTrends);
            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });



}

//divide los ultimos datos de la estacion según el tipo de señal
function filtrarDatos(datos) {
    for (var indexDato in datos) {
        if (datos[indexDato]['valor'] == 't' || datos[indexDato]['valor'] == 'f') {
            datosDigi[indexDato] = datos[indexDato];
        } else {
            if (datos[indexDato]['nombre_tag'].includes("Consigna")) {
                consignas[indexDato] = datos[indexDato];
            } else {
                datosAnalog[indexDato] = datos[indexDato];
                datosAnalog[indexDato]['consignas'] = [];
            }
        }
    }

    for (var index in datosAnalog) {
        for (var con in consignas) {
            if (consignas[con]['nombre_tag'].includes(datosAnalog[index]['nombre_tag'])) {
                datosAnalog[index]['consignas'].push(consignas[con]);
            }
        }
    }

    todoDato['tags_digitales'] = datosDigi;
    todoDato['tags_analogicos'] = datosAnalog;
    todoDato['consignas'] = consignas;

    trendsTags();
    setTimeout(() => {
        montarWidgetsDigi();
        montarWidgetsAnalogicos();
    }, 1500);

}

//montar widgets de tags digitales
function montarWidgetsDigi() {

    var seccionDigital = document.getElementById('seccionInf');
    var widg = "";
    seccionDigital.innerHTML = '';
    for (var indexDato in datosDigi) {
        if (datosDigi[indexDato]['valor'] == 't') {
            widg = '<div class="widDigi"><div class="widDigiIcono"><i style="color:darkseagreen;" class="fas fa-check"></i></i></div><div class="widDigiText">' + datosDigi[indexDato]['nombre_tag'] + '</div></div>';
        } else {
            widg = '<div class="widDigi"><div class="widDigiIcono"><i style="color:tomato;" class="fas fa-pause"></i></div><div class="widDigiText">' + datosDigi[indexDato]['nombre_tag'] + '</div></div>'
        }
        seccionDigital.innerHTML += widg;
    }

}

//montar widgets analógicos
function montarWidgetsAnalogicos() {
    var seccionAnalog = document.getElementById('estacionDer');
    seccionAnalog.innerHTML = '';

    for (var indexDato in datosAnalog) {
        var widgInicio = '<div class="widAna">';
        var widgFin = '';
        var widgInfo = '<div class="widAnaInfo"><div class="widAnaInfoPrin">' + datosAnalog[indexDato]['nombre_tag'] + ': ' + datosAnalog[indexDato]['valor'] + ' ' + '</div>';
        var consi = '';
        var widgSec = '';

        consi += '<div id="gau' + datosAnalog[indexDato]['nombre_tag'].replace(/\s+/g, '') + '" class="widAnaInfoSec"></div>';
        // if (datosAnalog[indexDato]['consignas'].length > 0) {
        //     for (var i = 0; i < datosAnalog[indexDato]['consignas'].length; i++) {
        //         consi += '<div class="widAnaInfoSec">' + datosAnalog[indexDato]['consignas'][i]['nombre_tag'] + ': ' + datosAnalog[indexDato]['consignas'][i]['valor'] + '</div>';
        //     }
        // } else {
        //     consi += '<div class="widAnaInfoSec">sin consignas</div><div class="widAnaInfoSec">sin consignas</div>';
        // }
        consi += '</div>';
        var widgGraf = '<div class="widAnaGraf" id="chart' + datosAnalog[indexDato]['nombre_tag'].replace(/\s+/g, '') + '"></div>';
        var widget = widgInicio + widgInfo + widgSec + consi + widgGraf + widgFin;
        seccionAnalog.innerHTML += widget;
    }
    montarGraficosWidget();
}

//render de los graficos
function montarGraficosWidget() {

    for (var tag in datosAnalog) {

        var optionGauge;
        var nombreDato = datosAnalog[tag]['nombre_tag'].replace(/\s+/g, '');

        //gauge para niveles, cloro, caudal


        var chartDom = document.getElementById('gau' + nombreDato);
        var chartDom2 = document.getElementById('chart' + nombreDato);
        var gauge = echarts.init(chartDom);
        var grafTrend = echarts.init(chartDom2);
        var valor = datosAnalog[tag]['valor'];
        var maximo = 10;
        var maximoGraf = maximo + (maximo * 0.2);
        var minimo = 0;
        if (datosAnalog[tag]['consignas'].length >= 1) {
            maximo = parseInt(datosAnalog[tag]['consignas'][0]['valor']);
            maximo /= 10;

            // option['series'][0]['max'] = maximo;
        }
        if (datosAnalog[tag]['consignas'].length == 2) {
            minimo = parseInt(datosAnalog[tag]['consignas'][1]['valor']);
            minimo /= 10;
            // option['series'][0]['min'] = minimo;
        }



        optionGauge = {
            grid: {
                left: '0%',
                right: '0%',
                top: '0%',
                bottom: '0%',
                containLabel: true
            },

            series: [{
                name: nombreDato,
                type: 'gauge',
                itemStyle: {
                    color: 'rgb(1, 168, 184)'
                },
                progress: {
                    show: false
                },
                axisLine: {
                    show: true,
                    lineStyle: {
                        width: 6,
                        color: [
                            [(minimo), 'tomato'],
                            [(maximo), 'rgb(39, 45, 79)'],
                            [1, 'tomato']
                        ]
                    }

                },
                axisTick: {
                    show: false
                },
                axisLabel: {
                    show: false
                },
                splitLine: {
                    show: false
                },
                pointer: {
                    // icon: 'rect',
                    length: '80%',
                    width: 4
                },
                max: maximoGraf,
                min: 0,
                detail: {
                    show: true,
                    valueAnimation: true,
                    formatter: '{value}',
                    fontSize: 12
                },
                data: [{
                    value: valor,
                }]
            }]
        };

        var valores = [];
        var fechas = [];
        valores.push(todoTrends[tag]['max']);
        fechas.push(todoTrends[tag]['fecha']);
        console.log(valores);
        optionChart = {
            grid: {
                left: '2%',
                right: '1%',
                top: '8%',
                bottom: '2%',
                containLabel: true
            },
            tooltip: {
                trigger: 'axis',
                textStyle: {
                    fontStyle: 'bold',
                    fontSize: 12
                },

                axisPointer: {
                    axis: 'x',
                    snap: true,
                    offset: 0,
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
                data: fechas[0]
            },
            yAxis: {
                name: nombreDato,
                type: 'value'
            },
            series: [{
                name: nombreDato,
                data: valores[0],
                type: 'line',
                areaStyle: {
                    show: true,
                },
                symbol: 'none',
                smooth: false
            }]
        };

        optionGauge && gauge.setOption(optionGauge, true);
        optionChart && grafTrend.setOption(optionChart, true);

    }


}

//para los proximos cambios:
//
//      Para cambiar los datos de la sección izquierda
//      de los widget hay que modificar las funcion de 
//      actualizar en AJAX y la de montar WidgetAnalogicos 
//      en este JS

//      para cambiar los trends o su intervalo, hay que modificar
//      la funcion trendTags de AJAX y la función montar graficos Widgets