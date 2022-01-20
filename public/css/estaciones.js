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

        if (datosAnalog[indexDato]['consignas'].length > 0) {
            for (var i = 0; i < datosAnalog[indexDato]['consignas'].length; i++) {
                consi += '<div class="widAnaInfoSec">' + datosAnalog[indexDato]['consignas'][i]['nombre_tag'] + ': ' + datosAnalog[indexDato]['consignas'][i]['valor'] + '</div>';
            }
        } else {
            consi += '<div class="widAnaInfoSec">sin consignas</div><div class="widAnaInfoSec">sin consignas</div>';
        }
        consi += '</div>';
        var widgGraf = '<div class="widAnaGraf" id="' + datosAnalog[indexDato]['nombre_tag'] + '"></div>';
        var widget = widgInicio + widgInfo + widgSec + consi + widgGraf + widgFin;
        seccionAnalog.innerHTML += widget;
    }
    montarGraficosWidget();
}

//render de los graficos
function montarGraficosWidget() {

    for (var tag in datosAnalog) {

        var option;
        var nombreDato = datosAnalog[tag]['nombre_tag'];

        //gauge para niveles, cloro, caudal
        if (nombreDato.includes("Nivel") || nombreDato.includes("Cloro") || nombreDato.includes("Caudal")) {
            var chartDom = document.getElementById("" + nombreDato + "");
            var grafico = echarts.init(chartDom);
            var valor = datosAnalog[tag]['valor'];
            var maximo = 100;
            var minimo = 0;
            if (datosAnalog[tag]['consignas'].length >= 1) {
                maximo = parseInt(datosAnalog[tag]['consignas'][0]['valor']);
                maximo /= 100;
                // option['series'][0]['max'] = maximo;
            }
            if (datosAnalog[tag]['consignas'].length == 2) {
                minimo = parseInt(datosAnalog[tag]['consignas'][1]['valor']);
                minimo /= 100;
                // option['series'][0]['min'] = minimo;
            }



            option = {
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
                        show: true
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
                        show: true
                    },
                    pointer: {
                        icon: 'roundRect',
                        length: '80%',

                    },
                    detail: {
                        show: true,
                        valueAnimation: true,
                        formatter: '{value}',
                    },
                    data: [{
                        value: valor,
                    }]
                }]
            };



            option && grafico.setOption(option, true);
        }

        //chart acumulados

        if (nombreDato.includes("Acumulado")) {
            var chartDom = document.getElementById("" + nombreDato + "");
            var grafico = echarts.init(chartDom);
            var valores = [];
            var fechas = [];
            for (var index in todoTrends[tag]) {
                valores.push(todoTrends[tag][index]['max']);
                fechas.push(todoTrends[tag][index]['fecha']);
            }


            option = {
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
                        fontSize: 20
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
                    data: fechas
                },
                yAxis: {
                    type: 'value'
                },
                series: [{
                    data: valores,
                    type: 'bar',
                    // areaStyle: {
                    //     show: true,
                    // },
                    symbol: 'none',
                    smooth: true
                }]
            };

            option && grafico.setOption(option);

        }


        //chart caudal
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