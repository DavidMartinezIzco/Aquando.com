var datosDigi = Array();
var datosAnalog = Array();
var consignas = Array();
var todoDato = Array();

//actualizar la info de la seccion estacion
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
    console.log(todoDato);
    montarWidgetsDigi();
    montarWidgetsAnalogicos();
}


//montar widgets de tags digitales
function montarWidgetsDigi() {

    var seccionDigital = document.getElementById('seccionInf');
    var widg = "";
    seccionDigital.innerHTML = '';
    for (var indexDato in datosDigi) {
        if (datosDigi[indexDato]['valor'] == 't') {
            widg = '<div class="widDigi"><div class="widDigiIcono"><i style="color:darkseagreen;" class="fas fa-toggle-on"></i></i></div><div class="widDigiText">' + datosDigi[indexDato]['nombre_tag'] + '</div></div>';
        } else {
            widg = '<div class="widDigi"><div class="widDigiIcono"><i style="color:tomato;" class="fas fa-toggle-off"></i></div><div class="widDigiText">' + datosDigi[indexDato]['nombre_tag'] + '</div></div>'
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
        var widgSec = '<div class="widAnaInfoSec">';

        if (datosAnalog[indexDato]['consignas'].length > 0) {
            for (var i = 0; i < datosAnalog[indexDato]['consignas'].length; i++) {
                consi += '<div>' + datosAnalog[indexDato]['consignas'][i]['valor'] + '</div>';
            }
        } else {
            consi += '<div>?</div><div>?</div>';
        }
        consi += '</div></div>';
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
                                [1, 'rgb(39, 45, 79)']
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
                    detail: {
                        show: true,
                        valueAnimation: true,
                        formatter: '{value}'
                    },
                    data: [{
                        value: valor,
                    }]
                }]
            };

            if (datosAnalog[tag]['consignas'].length >= 1) {
                var maximo = parseInt(datosAnalog[tag]['consignas'][0]['valor']);
                option['series'][0]['max'] = maximo;
            }
            if (datosAnalog[tag]['consignas'].length == 2) {
                var minimo = parseInt(datosAnalog[tag]['consignas'][1]['valor']);
                option['series'][0]['min'] = minimo;
            }


            console.log(option);
            option && grafico.setOption(option, true);
        }

        //chart acumulados

        //chart caudal
    }


}