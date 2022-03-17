//configs globales
var n_preset = "";
var datosTagCustom = new Array;
var ejesYTagCustom = new Array;
var nombre_estacion_activa = "";
datosTagCustom['serie'] = [];
datosTagCustom['fechas'] = [];
var serie = {};
var presets_config = new Array();

//reestablece los filtros por defecto

function limpiar() {
    document.getElementsByName('btnControlReset')[0].innerText = 'limpio!';

    //tags y metas seleccionados
    var checkTags = document.querySelectorAll('input[name=checkTag]:checked')
    for (var i = 0; i < checkTags.length; i++) {
        checkTags[i].checked = false;
        if (checkTags[i].parentNode.style.backgroundColor == 'darkgray') {
            checkTags[i].parentNode.style.backgroundColor = 'lightgray';
        } else {
            checkTags[i].parentNode.style.backgroundColor = 'darkgray';
        }
    }
    var checkMetas = document.querySelectorAll('input[name=checkMeta]:checked')
    for (var i = 0; i < checkMetas.length; i++) {
        checkMetas[i].checked = false;
        if (checkMetas[i].parentNode.style.backgroundColor == 'darkgray') {
            checkMetas[i].parentNode.style.backgroundColor = 'lightgray';
        } else {
            checkMetas[i].parentNode.style.backgroundColor = 'darkgray';
        }
    }

    //quitar colores der tags y meta
    var inColor = document.querySelectorAll('input[name=colorDato]')
    for (var i = 0; i < inColor.length; i++) {
        inColor[i].value = '#000000';
        inColor[i].parentNode.style.color = '#000000';
    }

    //reset de fechas
    inicioFin();

    //limpiar zona de gráficos
    graficoCustom.clear();

    setTimeout(function() {
        document.getElementsByName('btnControlReset')[0].innerHTML = "reset";
    }, 1000);
}

//saca una captura del grafico en panatalla
function imprimir() {
    var al = $("#grafica").height();
    var an = $("#zonaGraficos").width();
    var hoy = new Date();
    var fechaHoy = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate();
    var nombre_informe = 'Historico ' + fechaHoy + '.pdf';
    var informe = document.getElementById('grafica');
    var opt = {
        margin: 0,
        filename: nombre_informe,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, logging: true, dpi: 300, letterRendering: true },
        jsPDF: { unit: 'px', format: [an, al], orientation: 'l' }
    };

    var exp_informe = new html2pdf(informe, opt);
    exp_informe.getPdf(true).then((pdf) => {});
}

//establece los valores por defecto de los inputs de fecha
//traduce y establece la fecha actual como predeterminado
function inicioFin() {
    Date.prototype.seteardesde = (function() {
        var manana = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
        return manana.toJSON().slice(0, 10);
    });
    Date.prototype.setearHasta = (function() {
        var mesant = new Date(new Date().getTime() - 30 * 24 * 60 * 60 * 1000);
        return mesant.toJSON().slice(0, 10);
    });
    $(document).ready(function() {
        $('#fechaInicio').val(new Date().seteardesde());
    });
    $(document).ready(function() {
        $('#fechaFin').val(new Date().setearHasta());
    });
}
//pasa los tags historizables de una estacion en concreto
function tagsEstacionCustom(id_estacion) {

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'http://dateando.ddns.net:3000/Aquando.com/A_Graficas.php?estacion=' + id_estacion + '&opcion=tags',
            success: function(tags) {
                document.getElementById("opcionesTag").innerHTML = "";
                var e = 0;
                sessionStorage.setItem('tagsAct', JSON.stringify(tags));
                for (var tag in tags) {
                    if (e == 0) {
                        //document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + " selected>" + tags[tag]['nombre_tag'] + "</option>";
                        document.getElementById("opcionesTag").innerHTML += '<li><input type="checkbox" name="checkTag" style="visibility: hidden;" value="' + tags[tag]['id_tag'] + '" id = ' + tags[tag]['id_tag'] + '><label for = "' + tags[tag]['id_tag'] + '" style="box-sizing: none"> ' + tags[tag]['nombre_tag'] + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + tags[tag]['id_tag'] + '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label></li>';

                    } else {
                        //document.getElementById("opcionesTag").innerHTML += "<option value=" + tags[tag]['id_tag'] + ">" + tags[tag]['nombre_tag'] + "</option>";
                        document.getElementById("opcionesTag").innerHTML += '<li> <input type = "checkbox" name="checkTag" style = "visibility: hidden;" value="' + tags[tag]['id_tag'] + '" id = ' + tags[tag]['id_tag'] + ' ><label for = "' + tags[tag]['id_tag'] + '" style="box-sizing: none"> ' + tags[tag]['nombre_tag'] + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + tags[tag]['id_tag'] + '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label ></li>';
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

//display de las opciones
function mostrarOpciones() {
    if (document.getElementById("zonaControles").style.width == '0px') {
        document.getElementById("zonaControles").style.width = '34.5%';
        document.getElementById("zonaControles").style.left = '65%';
        document.getElementById("zonaGraficos").style.width = '65%';

    } else {
        document.getElementById("zonaControles").style.width = 0;
        document.getElementById("zonaControles").style.left = '100%';
        document.getElementById("zonaGraficos").style.width = '98%';

    }

}

//disparador inicial para mostrar el grafico según los ajustes en los contrles
function aplicarCustom() {
    //mirar a ver si en vez de actualizar todo, ver si se pueden reutilizar
    //los estados anteriores (x optimizar vaya)
    document.getElementById("btnControlCustom").disabled = true;
    document.getElementById("selPresets").disabled = true;
    datosTagCustom = new Array;
    datosTagCustom['serie'] = [];
    datosTagCustom['fechas'] = [];
    var ajustesTag = [];
    var checkTags = document.querySelectorAll('input[name=checkTag]:checked');
    if (checkTags.length > 0) {
        document.getElementsByName('btnControlAplicar')[0].innerHTML = "cargando...";

        for (var i = 0; i < checkTags.length; i++) {
            ajustesTag.push(checkTags[i].value)
        }
        var ajustesMeta = [];
        var checkMetas = document.querySelectorAll('input[name=checkMeta]:checked')
        for (var i = 0; i < checkMetas.length; i++) {
            ajustesMeta.push(checkMetas[i].value)
        }
        var id_estacion = document.getElementById('opciones').value;
        var metas = "";
        for (var meta in ajustesMeta) {
            metas += ajustesMeta[meta] + "/";
        }
        // comparar 2
        //crear linea con fechas entre ambas
        var fechaInicio = document.getElementById('fechaInicio').value;
        var fechaFin = document.getElementById('fechaFin').value;

        for (var ajusteTag in ajustesTag) {
            infoTags(id_estacion, ajustesTag, ajustesTag[ajusteTag], metas, fechaInicio, fechaFin);
        }
        setTimeout(function() {
            document.getElementsByName('btnControlAplicar')[0].innerHTML = "aplicar";
            document.getElementById("btnControlCustom").disabled = false;
            document.getElementById("selPresets").disabled = false;
        }, 12000);
    } else {
        document.getElementsByName('btnControlAplicar')[0].innerHTML = "¡sin señales!";
        limpiar();
        setTimeout(function() {
            document.getElementsByName('btnControlAplicar')[0].innerHTML = "aplicar";
            document.getElementById("btnControlCustom").disabled = false;
            document.getElementById("selPresets").disabled = false;
        }, 12000);
        document.getElementById("btnControlCustom").disabled = false;
    }
}
//consigue los metadata de un tag
function infoTags(estacion, ajustesTag, tag, metas, fechaIni, fechaFin) {
    var nTags = ajustesTag.length;
    $.ajax({
        type: 'GET',
        url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?estacion=' + estacion + '&id_tag=' + tag + '&fechaIni=' + fechaIni + '&fechaFin=' + fechaFin + '&meta=' + metas + '&opcion=tag',
        success: function(datosTag) {
            prepararTag(datosTag, tag);
            if (ajustesTag.at(-1) == tag) {
                setTimeout(renderGrafico, (nTags * 500));
            }
        },
        error: function(e) {
            console.log(e);
        },
        dataType: 'json'
    });

}

//crea el objeto series con la info de un tag en concreto
//junta en los series los historicos con los metadatos
//es un caos pero hace lo que promete
function prepararTag(info, tag) {
    var fechasTag = [];
    var nombreDato = "Info " + tag;
    var tagsAct = JSON.parse('[' + sessionStorage.getItem("tagsAct") + ']');

    for (var tindex in tagsAct[0]) {
        if (tagsAct[0][tindex]['id_tag'] == tag) {
            nombreDato = tagsAct[0][tindex]['nombre_tag'];
        }
    }

    //elementos comunes
    var colorTag = document.getElementById("color" + tag).value;
    serie = {};

    // codigo provisional

    //cambiar escalado de los ejes Y en funcion de las series a las que pertenezcan
    //eliminiar los nombres de los tags en la parte superior (está en render)
    //mirar a ver si solucionamos el asunto del zoom

    var eje = {};
    eje['type'] = 'value';

    eje['axisLine'] = {
        show: true,
        lineStyle: { color: colorTag }
    };
    eje['axisLabel'] = { show: true };
    eje['axisTick'] = { show: true }
    eje['boundaryGap'] = [0, '100%'];

    eje['inside'] = true;
    ejesYTagCustom.push(eje);

    serie['name'] = nombreDato;
    serie['symbol'] = 'none';
    serie['connectNulls'] = true;
    serie['type'] = "line";
    serie['smooth'] = true;
    serie['sampling'] = "lttb";
    serie['itemStyle'] = {
        color: colorTag
    }
    serie['areaStyle'] = {
        show: true,
        color: colorTag,
        opacity: 0.7
    };
    serie['data'] = [];
    serie['markLine'] = { data: [] };


    var mulstack = 1;
    for (var index in info['tag']) {

        if (info['tag'][index]['valor'] != 't') {
            serie['data'].push(info['tag'][index]['valor']);
        } else {
            serie['stack'] = 'Total';
            serie['data'].push(1 * mulstack);
        }
        fechasTag.push(info['tag'][index]['fecha']);
    }


    //crear markline-data con los metadatos
    //se separa en dos por la naturaleza de ambos tipos de meta

    for (var meta in info['meta']) {
        var colorMeta = '';
        var valMeta = info['meta'][meta];
        var marcaMeta = {};
        marcaMeta['lineStyle'] = { normal: new Array };

        //PARTE 1: GENERALES

        if (meta == 'max') {
            colorMeta = document.getElementById("colorMax").value;
            marcaMeta['name'] = "maximo gen";
            marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        }
        if (meta == 'min') {
            colorMeta = document.getElementById("colorMin").value;
            marcaMeta['name'] = "minimo gen";
            marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        }
        if (meta == 'avg') {
            colorMeta = document.getElementById("colorAvg").value;
            marcaMeta['name'] = "media gen";
            marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        }

        //letrero con nombre y datos del markline
        //tal vez lo quite, tal vez no, tal vez lo cambie
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'lightgray';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = colorMeta;
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;
        marcaMeta['yAxis'] = valMeta;
        serie['markLine']['data'].push(marcaMeta);
    }

    //PARTE 2: INTERVALOS
    if (document.getElementById("checkMaxInt").checked) {
        colorMeta = document.getElementById("colorMaxInt").value;
        var marcaMeta = {};
        marcaMeta['name'] = 'max';
        marcaMeta['type'] = 'max';
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = colorMeta;
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;
        serie['markLine']['data'].push(marcaMeta);
    }
    if (document.getElementById("checkMinInt").checked) {
        colorMeta = document.getElementById("colorMinInt").value;
        var marcaMeta = {};
        marcaMeta['name'] = 'min';
        marcaMeta['type'] = 'min';
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = colorMeta;
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;
        serie['markLine']['data'].push(marcaMeta);
    }
    if (document.getElementById("checkAvgInt").checked) {
        colorMeta = document.getElementById("colorAvgInt").value;
        var marcaMeta = {};
        marcaMeta['name'] = 'media';
        marcaMeta['type'] = 'average';
        marcaMeta['lineStyle'] = { normal: new Array };
        marcaMeta['lineStyle']['normal']['color'] = colorMeta;
        marcaMeta['lineStyle']['normal']['type'] = 'dashed';
        marcaMeta['label'] = {};
        marcaMeta['label']['formatter'] = '{b} ' + nombreDato + ': {c}';
        marcaMeta['label']['position'] = 'insideEnd';
        marcaMeta['label']['backgroundColor'] = 'white';
        marcaMeta['label']['color'] = 'black';
        marcaMeta['label']['padding'] = [5, 20];
        marcaMeta['label']['borderColor'] = colorMeta;
        marcaMeta['label']['borderRadius'] = [5, 5, 5, 5];
        marcaMeta['label']['borderWidth'] = 2;
        serie['markLine']['data'].push(marcaMeta);
    }

    datosTagCustom['serie'].push(serie);
    datosTagCustom['fechas'].push(fechasTag);


}

//crea el grafico con varios ajustes y con los objetos series
function renderGrafico() {
    //llegan ajustesTags en tags
    //para organizar los values y las fechas
    var option;
    nombreDato = "info";
    var lineaTiempo = [];
    var fDesde = new Date(document.getElementById("fechaInicio").value);
    var fHasta = new Date(document.getElementById("fechaFin").value);
    var diff5m = fDesde - fHasta / 1000 / 60 / 5;
    for (var i = 0; i < diff5m; i++) {
        var fecha = fDesde.getDate() + "/" + fDesde.getMonth + 1 + "/" + fDesde.getFullYear + "  " + fDesde.getHours + ":" + fDesde.getMinutes;
        lineaTiempo.push(fecha);
        fDesde = new Date(fDesde.getTime() + 5 * 60000);
    }

    //leyenda
    option = {
        legend: {
            x: 'center',
            y: 'top',
            textStyle: {
                fontWeight: 'normal',
                fontSize: 10,
            },
            padding: 1,
        },
        grid: {
            left: '5%',
            right: '1%',
            bottom: '10%',
            containLabel: true,
        },
    };

    //coger la serie con menos entradas fecha
    //relleno con nulls hasta que tengan misma length
    // var ejesTiempo = [];
    // var tooltips = [];
    // var mayFech = datosTagCustom['fechas'][0];
    // for (var index in datosTagCustom['fechas']) {
    //     if (datosTagCustom['fechas'][index].length >= mayFech.length) {
    //         mayFech = datosTagCustom['fechas'][index];
    //         ejesTiempo.push({
    //             boundaryGap: false,
    //             inverse: true,
    //             // splitNumber:10,
    //             axisLabel: {
    //                 formatter: '{value}'
    //             },
    //             data: datosTagCustom['fechas'][index],
    //         });
    //     }

    // }

    for (var index in datosTagCustom['serie']) {
        if (datosTagCustom['serie'][index]['data'].length < mayFech.length) {
            console.log('se rellena');
            var relleno = [];
            var cantRelleno = mayFech.length - datosTagCustom['serie'][index]['data'].length;
            // for (var i = 0; i < cantRelleno; i++) {
            //     relleno[i] = null;
            // }

            datosTagCustom['serie'][index]['data'] = datosTagCustom['serie'][index]['data'].concat(relleno);
            console.log(datosTagCustom['serie'][index]);
        }
    }

    //eje X

    //option['xAxis'] = ejesTiempo;
    option['xAxis'] = [{
            boundaryGap: false,
            inverse: true,
            splitNumber: 10,
            data: mayFech,
        },
        {
            boundaryGap: false,
            inverse: true,
            data: lineaTiempo,
        }
    ];
    //herramientas
    // option['tooltip'] = tooltips;
    option['tooltip'] = {
        trigger: 'axis',
        // icon: 'none',
        // textStyle: {
        //     fontStyle: 'bold',
        //     fontSize: 20
        // },
        // formatter: (params) => {
        //     var msg = "";
        //     for(var index in params){
        //         msg += params[index].seriesName + ': ' + params[index].value + '<br>';
        //     }
        //     return msg;
        // },
        axisPointer: {

            type: 'cross',
            label: {
                fontStyle: 'bold'
            }
        }
    };

    //controles del eje X
    option['dataZoom'] = [{
            type: 'slider',
            textStyle: {
                fontSize: 14,
                fontWeight: 'bold'
            },
            xAxisIndex: 0,
            start: 0,
            end: 100,
            filterMode: 'filter',
            zlevel: 10
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
            end: 100,
            filterMode: 'filter',
            zlevel: 10
        },
    ];

    //los ejes Y según se encuentran en mayor número, ocupan mayor espacio hasta el punto
    //de llegar a ocupar casi la mitad del gráfico. Su forma de aparecer va a tener que cambiar
    //(tal vez eliminando las labels)

    option['yAxis'] = [];
    let mul = 0;
    //DataZooms dedicados para los Ejes Y
    //Los datazoom no parecen llegara sincronizarse con la posición de los ejes
    //Los datazoom del lado derecho (mult > 1) están en desorden
    for (var eje in ejesYTagCustom) {

        if (mul >= 1) {
            ejesYTagCustom[eje]['offset'] = (75) * (mul - 1);

            option['grid']['right'] = (7) * (mul - 1) + '%';
            option['dataZoom'].push({
                type: 'slider',
                textStyle: {
                    fontSize: 14,
                    fontWeight: 'bold'
                },
                right: (100) * (mul - 1),
                yAxisIndex: mul,
                filterMode: 'filter'
            });
            option['dataZoom'].push({
                type: 'inside',
                textStyle: {
                    fontSize: 14,
                    fontWeight: 'bold'
                },
                right: (100) * (mul - 1),
                yAxisIndex: mul,
                filterMode: 'filter'
            })
        } else {
            option['dataZoom'].push({
                type: 'slider',
                textStyle: {
                    fontSize: 14,
                    fontWeight: 'bold'
                },
                left: '0%',
                yAxisIndex: mul,
                filterMode: 'filter'
            });
            option['dataZoom'].push({
                type: 'inside',
                textStyle: {
                    fontSize: 14,
                    fontWeight: 'bold'
                },
                left: '0%',
                yAxisIndex: mul,
                filterMode: 'filter'
            })
        }
        mul++;
        option['yAxis'].push(ejesYTagCustom[eje]);
    }
    ejesYTagCustom = new Array;


    //series y datos en el grafico
    //la informacion de aui se cea en prepararTag()
    option['series'] = [];
    var sInd = 0;
    for (var index in datosTagCustom['serie']) {
        datosTagCustom['serie'][index]['yAxisIndex'] = sInd;
        option['series'].push(datosTagCustom['serie'][index]);
        sInd++;
    }

    //ajustes de pantalla para el grafico
    // $(window).keyup(function() {
    //     graficoCustom.resize();
    // });
    // $(window).keydown(function() {
    //     graficoCustom.resize();
    // });
    // $(window).keypress(function() {
    //     graficoCustom.resize();
    // });

    $('#menuIzq').bind('widthChange', function() {
        graficoCustom.resize();
    });

    $('#zonaControles').bind('widthChange', function() {
        graficoCustom.resize();
    });

    // document.getElementById("conPrincipal").onmousemove = function() {
    //     setTimeout(graficoCustom.resize(), 500);
    // };

    // document.getElementById('grafica').onmouseover = function() {
    //     setTimeout(graficoCustom.resize(), 500);
    // }
    // document.getElementById('zonaControles').onmouseover = function() {
    //     setTimeout(graficoCustom.resize(), 500);
    // }

    console.log(option);
    option && graficoCustom.setOption(option, true);


    // setTimeout(function() {
    //     document.getElementsByName('btnControlAplicar')[0].innerHTML = "aplicar";
    // }, 1000);

}

// despliega las ventanas de opciones de los presets
function ajustesPresets(modo) {

    var con = document.getElementById('ajustesPresets');
    if (con.style.display == 'block') {
        con.style.display = 'none';
    } else {
        con.style.display = 'block';
        if (modo == 'cargar') {
            con.innerHTML = "";
            var pre = document.getElementById('selPresets').options[document.getElementById('selPresets').selectedIndex].value;
            var msg = "<h3>Cargar Preset</h3><p>¿quieres cargar <b>" + pre + "</b>?</p>";
            var btns = "<button class='btnPresetOk' onclick='cargarPreset()'>Cargar</button><button <button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button><button onclick='borrarPreset()' class='btnPresetBorrar'>Borrar</button><br><br><p id=txtPresetError></p>";
            con.innerHTML = msg + btns;
        }
        if (modo == 'guardar') {
            con.innerHTML = "";
            var msg = "<h3>Guardar Preset</h3>Nombre:<br><input style='margin-left:2%;' id='txtPreset' type=text><br><br>";
            var btns = "<button class='btnPresetOk' onclick='guardarPreset()'>Guardar</button><button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button><br><br><p id=txtPresetError></p>";
            con.innerHTML = msg + btns;
        }
        if (modo == 'vacio') {
            con.innerHTML = "";
            var msg = "<h3>Guardar Preset</h3>No has seleccionado ninguna señal<br><br>";
            var btns = "<button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button>";
            con.innerHTML = msg + btns;
        }


    }




}

//busca los presets del usuario y los lista o los carga
function leerPresets(para) {

    var datos = {};
    datos['nombre'] = usu;
    datos['pwd'] = pwd;
    if (para == null || para == 'mostrar') {
        para = 'mostrar';
        var arrdatos = JSON.stringify(datos);
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?opcion=leerPresets&para=' + para,
                data: {
                    arrdatos: arrdatos
                },
                success: function(presets) {
                    document.getElementById("selPresets").innerHTML = presets;

                },
                error: function(e) {
                    console.log(e);
                },
                // dataType: 'json'
            });
        });
    }
    if (para == 'cargar') {
        para = 'cargar';
        var arrdatos = JSON.stringify(datos);
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?opcion=leerPresets&para=' + para,
                data: {
                    arrdatos: arrdatos
                },
                success: function(presets) {
                    presets_config = presets;
                    for (var index in presets_config) {
                        if (presets_config[index]['configuracion'].includes(n_preset)) {
                            var config = presets_config[index]['configuracion'];
                            config = config.substring(config.indexOf("@") + 1);
                            var id_est = config.substring(0, config.indexOf("?"));
                            var config_tags = config.substring(config.indexOf("/") + 1);
                            var tagsycolores = config_tags.split("/");
                            var config_tags_colores = new Array();
                            for (var index in tagsycolores) {
                                var info = tagsycolores[index].split(":");
                                config_tags_colores[info[0]] = info[1];
                                document.getElementById(info[0]).checked = 'true';
                                document.getElementById('color' + info[0]).value = info[1];
                                if (document.getElementById(info[0]).parentNode.style.backgroundColor == 'darkgray') {
                                    document.getElementById(info[0]).parentNode.style.backgroundColor = 'lightgray';
                                } else {
                                    document.getElementById(info[0]).parentNode.style.backgroundColor = 'darkgray';
                                }
                                document.getElementById('color' + info[0]).parentNode.style.color = info[1];
                            }
                            aplicarCustom();
                            ajustesPresets(null);
                        }
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

//saca los presets en una lista
function mostrarPresets() {
    leerPresets('mostrar');
}

//a traves de AJAX lee la config de un preset y lo aplica con aplicarCustom()
function cargarPreset() {
    document.getElementById("btnControlCustom").disabled = true;
    document.getElementById("selPresets").disabled = true;
    limpiar();
    document.getElementsByName('btnControlAplicar')[0].innerHTML = "cargando...";
    n_preset = document.getElementById('selPresets').options[document.getElementById('selPresets').selectedIndex].value;
    if (n_preset.includes(nombre_estacion_activa)) {
        leerPresets('cargar');


    } else {
        document.getElementById('txtPresetError').innerHTML += 'El preset no pertenece a esta estación';
    }
    document.getElementsByName('btnControlAplicar')[0].innerHTML = "aplicar";

}

//a traves de AJAX busca en la config de usuario un preset y lo elimina
function borrarPreset() {
    ajustesPresets(null);
    var n_preset = document.getElementById('selPresets').options[document.getElementById('selPresets').selectedIndex].value;
    var datos = {};
    datos['nombre'] = usu;
    datos['pwd'] = pwd;
    var arrdatos = JSON.stringify(datos);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_GraficasCustom.php?opcion=borrar&preset=' + n_preset,
            data: {
                arrdatos: arrdatos
            },
            success: function() {

                leerPresets('mostrar');
                setTimeout(ajustesPresets(null), 1000);
            },
            error: function(e) {
                console.log(e);
            },
            dataType: 'json'
        });
    });
    mostrarPresets();


}

//llama a AJAX para guardar un preset en la configuracion de usuario
function guardarPreset() {

    if (document.getElementById('txtPreset').value != null && document.getElementById('txtPreset').value != '' && !document.getElementById('txtPreset').value.includes(":") && !document.getElementById('txtPreset').value.includes("/") && !document.getElementById('txtPreset').value.includes("@")) {

        var checkTags = document.querySelectorAll('input[name=checkTag]:checked');
        var nombre_preset = nombre_estacion_activa + ": " + document.getElementById('txtPreset').value;
        var datosPreset = {};
        var tags_colores = new Array();
        for (var i = 0; i < checkTags.length; i++) {
            tags_colores[checkTags[i].value] = document.getElementById('color' + checkTags[i].value).value;
        }
        datosPreset['usuario'] = usu;
        datosPreset['pwd'] = pwd;
        datosPreset['nombre'] = nombre_preset;
        datosPreset['id_estacion'] = document.getElementById('opciones').value;
        datosPreset['tags_colores'] = tags_colores;
        console.log(datosPreset);

        var arrDatosPreset = JSON.stringify(datosPreset);

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?opcion=guardar',
                data: {
                    arrDatosPreset: arrDatosPreset
                },
                success: function(info) {

                    document.getElementById('ajustesPresets').innerHTML += 'preset guardado';
                    leerPresets('mostrar');
                    setTimeout(ajustesPresets(null), 1000);
                },
                error: function() {
                    console.log('error en el guardado');
                },
                dataType: 'json'
            });
        });
    } else {
        document.getElementById('txtPresetError').innerHTML = 'Introduce un nombre válido';
    }



}