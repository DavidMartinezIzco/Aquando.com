var n_preset = "",
    datosTagCustom = Array(),
    ejesYTagCustom = Array(),
    nombre_estacion_activa = "";
datosTagCustom.serie = Array(), datosTagCustom.fechas = Array();
var serie = Array(),
    presets_config = new Array;

function limpiar() {
    document.getElementsByName( "btnControlReset" )[ 0 ].innerText = "limpio!";
    for ( var b = document.querySelectorAll( "input[name=checkTag]:checked" ), a = 0; a < b.length; a++ ) b[ a ].checked = !1, "darkgray" == b[ a ].parentNode.style.backgroundColor ? b[ a ].parentNode.style.backgroundColor = "lightgray" : b[ a ].parentNode.style.backgroundColor = "darkgray";
    for ( var c = document.querySelectorAll( "input[name=checkMeta]:checked" ), a = 0; a < c.length; a++ ) c[ a ].checked = !1, "darkgray" == c[ a ].parentNode.style.backgroundColor ? c[ a ].parentNode.style.backgroundColor = "lightgray" : c[ a ].parentNode.style.backgroundColor = "darkgray";
    for ( var d = document.querySelectorAll( "input[name=colorDato]" ), a = 0; a < d.length; a++ ) d[ a ].value = "#000000", d[ a ].parentNode.style.color = "#000000";
    inicioFin(), graficoCustom.clear(), setTimeout( function () { document.getElementsByName( "btnControlReset" )[ 0 ].innerHTML = "reset" }, 1e3 )
}

function imprimir() {
    var b = $( "#grafica" )
        .height(),
        c = $( "#zonaGraficos" )
        .width(),
        a = new Date,
        d = a.getFullYear() + "-" + ( a.getMonth() + 1 ) + "-" + a.getDate(),
        e = document.getElementById( "grafica" );
    new html2pdf( e, { margin: 0, filename: "Historico " + d + ".pdf", image: { type: "jpeg", quality: .98 }, html2canvas: { scale: 2, logging: !0, dpi: 300, letterRendering: !0 }, jsPDF: { unit: "px", format: [ c, b ], orientation: "l" } } )
        .getPdf( !0 )
        .then( a => {} )
}

function inicioFin() {
    Date.prototype.seteardesde = ( function () {
        var manana = new Date( new Date()
            .getTime() + 24 * 60 * 60 * 1000 );
        return manana.toJSON()
            .slice( 0, 10 );
    } );
    Date.prototype.setearHasta = ( function () {
        var semant = new Date( new Date()
            .getTime() - 7 * 24 * 60 * 60 * 1000 );
        return semant.toJSON()
            .slice( 0, 10 );
    } );
    $( document )
        .ready( function () {
            $( '#fechaInicio' )
                .val( new Date()
                    .seteardesde() );
        } );
    $( document )
        .ready( function () {
            $( '#fechaFin' )
                .val( new Date()
                    .setearHasta() );
        } );
    Date.prototype.seteardesde = function () {
            return new Date( new Date()
                    .getTime() + 864e5 )
                .toJSON()
                .slice( 0, 10 )
        }, Date.prototype.setearHasta = function () {
            return new Date( new Date()
                    .getTime() - 2592e6 )
                .toJSON()
                .slice( 0, 10 )
        }, $( document )
        .ready( function () {
            $( "#fechaInicio" )
                .val( new Date()
                    .seteardesde() )
        } ), $( document )
        .ready( function () {
            $( "#fechaFin" )
                .val( new Date()
                    .setearHasta() )
        } )
}

function tagsEstacionCustom( a ) {
    $( document )
        .ready( function () {
            $.ajax( {
                type: "GET",
                url: "http://localhost/Aquando/public/A_Graficas.php?estacion=" + a + "&opcion=tags",
                success: function ( a ) {
                    document.getElementById( "opcionesTag" )
                        .innerHTML = "";
                    var c = 0;
                    for ( var b in sessionStorage.setItem( "tagsAct", JSON.stringify( a ) ), a ) 0 == c ? document.getElementById( "opcionesTag" )
                        .innerHTML += '<li><input type="checkbox" name="checkTag" style="visibility: hidden;" value="' + a[ b ].id_tag + '" id = ' + a[ b ].id_tag + '><label for = "' + a[ b ].id_tag + '" style="box-sizing: none"> ' + a[ b ].nombre_tag + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + a[ b ].id_tag + '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label></li>' : document.getElementById( "opcionesTag" )
                        .innerHTML += '<li> <input type = "checkbox" name="checkTag" style = "visibility: hidden;" value="' + a[ b ].id_tag + '" id = ' + a[ b ].id_tag + ' ><label for = "' + a[ b ].id_tag + '" style="box-sizing: none"> ' + a[ b ].nombre_tag + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + a[ b ].id_tag + '" style="visibility:hidden" title="color" name="colorDato" list="coloresTagGraf"></label ></li>', c++
                },
                error: function () { console.log( "error" ) },
                dataType: "json"
            } )
        } )
}

function mostrarOpciones() {
    "0px" == document.getElementById( "zonaControles" )
        .style.width ? ( document.getElementById( "zonaControles" )
            .style.width = "34.5%", document.getElementById( "zonaControles" )
            .style.left = "65%", document.getElementById( "zonaGraficos" )
            .style.width = "65%" ) : ( document.getElementById( "zonaControles" )
            .style.width = 0, document.getElementById( "zonaControles" )
            .style.left = "100%", document.getElementById( "zonaGraficos" )
            .style.width = "98%" )
}

function sincroFechas() {
    console.log( datosTags );
    var d = datosTagCustom.serie,
        a = datosTagCustom.fechas;
    console.log( d ), console.log( a );
    var h = 0,
        b = 0;
    for ( var e in a ) console.log( a[ e ] ), a[ e ].length >= h && ( h = a[ e ].length, b = e );
    console.log( b );
    var i = Array(),
        f = Array();
    for ( var m in console.log( d[ b ] ), i.push( d[ b ].data ), f.push( a[ b ] ), a[ b ] ) {
        var j = a[ b ][ m ],
            n = new Date( j );
        for ( var c in a )
            if ( c != b ) {
                var k = a[ c ],
                    l = d[ c ].data;
                for ( var g in a[ c ] ) new Date( a[ c ][ g ] ) < n && ( k.splice( g, 0, j ), l.splice( g, 0, null ) );
                f.push( k ), i.push( l )
            }
    }
    console.log( f )
}

function aplicarCustom() {
    //mirar a ver si en vez de actualizar todo, ver si se pueden reutilizar
    //los estados anteriores (x optimizar vaya)
    document.getElementById( "btnControlCustom" )
        .disabled = true;
    document.getElementById( "selPresets" )
        .disabled = true;
    datosTagCustom = new Array;
    datosTagCustom[ 'serie' ] = [];
    datosTagCustom[ 'fechas' ] = [];
    var ajustesTag = [];
    var checkTags = document.querySelectorAll( 'input[name=checkTag]:checked' );
    if ( checkTags.length > 0 ) {
        document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "cargando...";

        for ( var i = 0; i < checkTags.length; i++ ) {
            ajustesTag.push( checkTags[ i ].value )
        }
        var ajustesMeta = [];
        var checkMetas = document.querySelectorAll( 'input[name=checkMeta]:checked' )
        for ( var i = 0; i < checkMetas.length; i++ ) {
            ajustesMeta.push( checkMetas[ i ].value )
        }
        var id_estacion = document.getElementById( 'opciones' )
            .value;
        var metas = "";
        for ( var meta in ajustesMeta ) {
            metas += ajustesMeta[ meta ] + "/";
        }
        // comparar 2
        //crear linea con fechas entre ambas
        var fechaInicio = document.getElementById( 'fechaInicio' )
            .value;
        var fechaFin = document.getElementById( 'fechaFin' )
            .value;

        for ( var ajusteTag in ajustesTag ) {
            infoTags( id_estacion, ajustesTag, ajustesTag[ ajusteTag ], metas, fechaInicio, fechaFin );
        }
        setTimeout( function () {
            document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "aplicar";
            document.getElementById( "btnControlCustom" )
                .disabled = false;
            document.getElementById( "selPresets" )
                .disabled = false;
        }, 12000 );
    } else {
        document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "¡sin señales!";
        limpiar();
        setTimeout( function () {
            document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "aplicar";
            document.getElementById( "btnControlCustom" )
                .disabled = false;
            document.getElementById( "selPresets" )
                .disabled = false;
        }, 12000 );
        document.getElementById( "btnControlCustom" )
            .disabled = false;
    }
}
//consigue los metadata de un tag
function infoTags( estacion, ajustesTag, tag, metas, fechaIni, fechaFin ) {
    var nTags = ajustesTag.length;
    $.ajax( {
        type: 'GET',
        url: '/Aquando.com/A_GraficasCustom.php?estacion=' + estacion + '&id_tag=' + tag + '&fechaIni=' + fechaIni + '&fechaFin=' + fechaFin + '&meta=' + metas + '&opcion=tag',
        success: function ( datosTag ) {
            prepararTag( datosTag, tag );
            if ( ajustesTag.at( -1 ) == tag ) {
                setTimeout( renderGrafico, ( nTags * 500 ) );
            }
        },
        error: function ( e ) {
            console.log( e );
        },
        dataType: 'json'
    } );


    document.getElementById( "btnControlCustom" )
        .disabled = !0, document.getElementById( "selPresets" )
        .disabled = !0, ( datosTagCustom = new Array )
        .serie = [], datosTagCustom.fechas = [];
    var b = [],
        c = document.querySelectorAll( "input[name=checkTag]:checked" );
    if ( c.length > 0 ) {
        document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "cargando...";
        for ( var a = 0; a < c.length; a++ ) b.push( c[ a ].value );
        for ( var d = [], e = document.querySelectorAll( "input[name=checkMeta]:checked" ), a = 0; a < e.length; a++ ) d.push( e[ a ].value );
        var g = document.getElementById( "fechaInicio" )
            .value,
            h = document.getElementById( "fechaFin" )
            .value,
            i = document.getElementById( "opciones" )
            .value,
            f = "";
        for ( var j in d ) f += d[ j ] + "/";
        for ( var k in b ) infoTags( i, b, b[ k ], f, g, h );
        console.log( datosTagCustom ), setTimeout( function () {
            document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "aplicar", document.getElementById( "btnControlCustom" )
                .disabled = !1, document.getElementById( "selPresets" )
                .disabled = !1
        }, 12e3 )
    } else document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "\xa1sin se\xf1ales!", limpiar(), setTimeout( function () {
            document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "aplicar", document.getElementById( "btnControlCustom" )
                .disabled = !1, document.getElementById( "selPresets" )
                .disabled = !1
        }, 12e3 ), document.getElementById( "btnControlCustom" )
        .disabled = !1
}

function infoTags( a, b, c, d, e, f ) {
    var g = b.length;
    $.ajax( { type: "GET", url: "http://localhost/Aquando/public/A_GraficasCustom.php?estacion=" + a + "&id_tag=" + c + "&fechaIni=" + e + "&fechaFin=" + f + "&meta=" + d + "&opcion=tag", success: function ( a ) { prepararTag( a, c ), b.at( -1 ) == c && setTimeout( renderGrafico, 500 * g ) }, error: function ( a ) { console.log( a ) }, dataType: "json" } )

}

function prepararTag( d, g ) {
    var k = [],
        e = "Info " + g,
        h = JSON.parse( "[" + sessionStorage.getItem( "tagsAct" ) + "]" );
    for ( var l in h[ 0 ] ) h[ 0 ][ l ].id_tag == g && ( e = h[ 0 ][ l ].nombre_tag );
    var i = document.getElementById( "color" + g )
        .value;
    serie = {};

    // codigo provisional

    //cambiar escalado de los ejes Y en funcion de las series a las que pertenezcan
    //eliminiar los nombres de los tags en la parte superior (está en render)
    //mirar a ver si solucionamos el asunto del zoom

    var eje = {};
    eje[ 'type' ] = 'value';

    eje[ 'axisLine' ] = {
        show: true,
        lineStyle: { color: colorTag }
    };
    eje[ 'axisLabel' ] = { show: true };
    eje[ 'axisTick' ] = { show: true }
    eje[ 'boundaryGap' ] = [ 0, '100%' ];

    eje[ 'inside' ] = true;
    ejesYTagCustom.push( eje );

    serie[ 'name' ] = nombreDato;
    serie[ 'symbol' ] = 'none';
    serie[ 'connectNulls' ] = true;
    serie[ 'type' ] = "line";
    serie[ 'smooth' ] = true;
    serie[ 'sampling' ] = "lttb";
    serie[ 'itemStyle' ] = {
        color: colorTag
    }
    serie[ 'areaStyle' ] = {
        show: true,
        color: colorTag,
        opacity: 0.7
    };
    serie[ 'data' ] = [];
    serie[ 'markLine' ] = { data: [] };

    var mulstack = 1;
    for ( var index in info[ 'tag' ] ) {

        if ( info[ 'tag' ][ index ][ 'valor' ] != 't' ) {
            serie[ 'data' ].push( info[ 'tag' ][ index ][ 'valor' ] );
        } else {
            serie[ 'stack' ] = 'Total';
            serie[ 'data' ].push( 1 * mulstack );
        }
        fechasTag.push( info[ 'tag' ][ index ][ 'fecha' ] );

        var c = {};
        for ( var j in c.type = "value", c.axisLine = { show: !0, lineStyle: { color: i } }, c.axisLabel = { show: !0 }, c.axisTick = { show: !0 }, c.boundaryGap = [ 0, "100%" ], c.inside = !0, ejesYTagCustom.push( c ), serie.name = e, serie.symbol = "none", serie.type = "line", serie.connectNulls = !0, serie.smooth = !0, serie.sampling = "lttb", serie.itemStyle = { color: i }, serie.areaStyle = { show: !0, color: i, opacity: .7 }, serie.data = [], serie.markLine = { data: [] }, d.tag ) "t" != d.tag[ j ].valor ? serie.data.push( d.tag[ j ].valor ) : ( serie.stack = "Total", serie.data.push( 1 ) ), k.push( d.tag[ j ].fecha );
        for ( var f in d.meta ) {
            var b = "",
                m = d.meta[ f ],
                a = {};
            a.lineStyle = { normal: new Array }, "max" == f && ( b = document.getElementById( "colorMax" )
                .value, a.name = "maximo gen", a.lineStyle.normal.color = b ), "min" == f && ( b = document.getElementById( "colorMin" )
                .value, a.name = "minimo gen", a.lineStyle.normal.color = b ), "avg" == f && ( b = document.getElementById( "colorAvg" )
                .value, a.name = "media gen", a.lineStyle.normal.color = b ), a.lineStyle.normal.type = "dashed", a.label = {}, a.label.formatter = "{b} " + e + ": {c}", a.label.position = "insideEnd", a.label.backgroundColor = "lightgray", a.label.color = "black", a.label.padding = [ 5, 20 ], a.label.borderColor = b, a.label.borderRadius = [ 5, 5, 5, 5 ], a.label.borderWidth = 2, a.yAxis = m, serie.markLine.data.push( a )

        }
        if ( document.getElementById( "checkMaxInt" )
            .checked ) {
            b = document.getElementById( "colorMaxInt" )
                .value;
            var a = {};
            a.name = "max", a.type = "max", a.lineStyle = { normal: new Array }, a.lineStyle.normal.color = b, a.lineStyle.normal.type = "dashed", a.label = {}, a.label.formatter = "{b} " + e + ": {c}", a.label.position = "insideEnd", a.label.backgroundColor = "white", a.label.color = "black", a.label.padding = [ 5, 20 ], a.label.borderColor = b, a.label.borderRadius = [ 5, 5, 5, 5 ], a.label.borderWidth = 2, serie.markLine.data.push( a )
        }
        if ( document.getElementById( "checkMinInt" )
            .checked ) {
            b = document.getElementById( "colorMinInt" )
                .value;
            var a = {};
            a.name = "min", a.type = "min", a.lineStyle = { normal: new Array }, a.lineStyle.normal.color = b, a.lineStyle.normal.type = "dashed", a.label = {}, a.label.formatter = "{b} " + e + ": {c}", a.label.position = "insideEnd", a.label.backgroundColor = "white", a.label.color = "black", a.label.padding = [ 5, 20 ], a.label.borderColor = b, a.label.borderRadius = [ 5, 5, 5, 5 ], a.label.borderWidth = 2, serie.markLine.data.push( a )
        }
        if ( document.getElementById( "checkAvgInt" )
            .checked ) {
            b = document.getElementById( "colorAvgInt" )
                .value;
            var a = {};
            a.name = "media", a.type = "average", a.lineStyle = { normal: new Array }, a.lineStyle.normal.color = b, a.lineStyle.normal.type = "dashed", a.label = {}, a.label.formatter = "{b} " + e + ": {c}", a.label.position = "insideEnd", a.label.backgroundColor = "white", a.label.color = "black", a.label.padding = [ 5, 20 ], a.label.borderColor = b, a.label.borderRadius = [ 5, 5, 5, 5 ], a.label.borderWidth = 2, serie.markLine.data.push( a )
        }

        datosTagCustom[ 'serie' ].push( serie );
        datosTagCustom[ 'fechas' ].push( fechasTag );

        datosTagCustom.serie.push( serie ), datosTagCustom.fechas.push( k )
    }

    function renderGrafico() {

        //llegan ajustesTags en tags
        //para organizar los values y las fechas
        var option;
        nombreDato = "info";
        var lineaTiempo = datosTagCustom[ 'fechas' ][ 0 ];

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
        //eje X
        option[ 'xAxis' ] = [ {
            boundaryGap: false,
            inverse: true,
            splitNumber: 10,
            data: lineaTiempo,
    }, ];
        //herramientas
        // option['tooltip'] = tooltips;
        option[ 'tooltip' ] = {
            trigger: 'axis',
            axisPointer: {

                type: 'cross',
                label: {
                    fontStyle: 'bold'
                }
            }
        };
        //controles del eje X
        option[ 'dataZoom' ] = [ {
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

        option[ 'yAxis' ] = [];
        let mul = 0;
        //DataZooms dedicados para los Ejes Y
        //Los datazoom no parecen llegara sincronizarse con la posición de los ejes
        //Los datazoom del lado derecho (mult > 1) están en desorden
        for ( var eje in ejesYTagCustom ) {
            if ( mul >= 1 ) {
                ejesYTagCustom[ eje ][ 'offset' ] = ( 75 ) * ( mul - 1 );

                option[ 'grid' ][ 'right' ] = ( 7 ) * ( mul - 1 ) + '%';
                option[ 'dataZoom' ].push( {
                    type: 'slider',
                    textStyle: {
                        fontSize: 14,
                        fontWeight: 'bold'
                    },
                    right: ( 100 ) * ( mul - 1 ),
                    yAxisIndex: mul,
                    filterMode: 'filter'
                } );
                option[ 'dataZoom' ].push( {
                    type: 'inside',
                    textStyle: {
                        fontSize: 14,
                        fontWeight: 'bold'
                    },
                    right: ( 100 ) * ( mul - 1 ),
                    yAxisIndex: mul,
                    filterMode: 'filter'
                } )
            } else {
                option[ 'dataZoom' ].push( {
                    type: 'slider',
                    textStyle: {
                        fontSize: 14,
                        fontWeight: 'bold'
                    },
                    left: '0%',
                    yAxisIndex: mul,
                    filterMode: 'filter'
                } );
                option[ 'dataZoom' ].push( {
                    type: 'inside',
                    textStyle: {
                        fontSize: 14,
                        fontWeight: 'bold'
                    },
                    left: '0%',
                    yAxisIndex: mul,
                    filterMode: 'filter'
                } )
            }
            mul++;
            option[ 'yAxis' ].push( ejesYTagCustom[ eje ] );
        }
        ejesYTagCustom = new Array;
        //series y datos en el grafico
        //la informacion de aui se cea en prepararTag()
        option[ 'series' ] = [];
        var sInd = 0;
        for ( var index in datosTagCustom[ 'serie' ] ) {
            datosTagCustom[ 'serie' ][ index ][ 'yAxisIndex' ] = sInd;
            option[ 'series' ].push( datosTagCustom[ 'serie' ][ index ] );
            sInd++;
        }
        $( '#menuIzq' )
            .bind( 'widthChange', function () {
                graficoCustom.resize();
            } );
        $( '#zonaControles' )
            .bind( 'widthChange', function () {
                graficoCustom.resize();
            } );

        option && graficoCustom.setOption( option, true );


        nombreDato = "info", ( b = { legend: { x: "center", y: "top", textStyle: { fontWeight: "normal", fontSize: 10 }, padding: 1 }, grid: { left: "5%", right: "1%", bottom: "10%", containLabel: !0 } } )
            .tooltip = { trigger: "axis", icon: "none", textStyle: { fontStyle: "bold", fontSize: 20 }, axisPointer: { type: "line", label: { formatter: "fecha y hora: {value}", fontStyle: "bold" } } }, b.xAxis = { boundaryGap: !1, inverse: !1, splitNumber: 20, data: datosTagCustom.fechas[ 0 ] }, b.dataZoom = [ { type: "slider", textStyle: { fontSize: 14, fontWeight: "bold" }, xAxisIndex: 0, start: 0, end: 100, filterMode: "filter", zlevel: 10 }, { type: "inside", throttle: 0, textStyle: { fontSize: 14, fontWeight: "bold" }, xAxisIndex: 0, start: 0, end: 100, filterMode: "filter", zlevel: 10 }, ], b.yAxis = [];
        let a = 0;
        for ( var c in ejesYTagCustom ) a >= 1 ? ( ejesYTagCustom[ c ].offset = 75 * ( a - 1 ), b.grid.right = 7 * ( a - 1 ) + "%", b.dataZoom.push( { type: "slider", textStyle: { fontSize: 14, fontWeight: "bold" }, right: 100 * ( a - 1 ), yAxisIndex: a, filterMode: "filter" } ), b.dataZoom.push( { type: "inside", textStyle: { fontSize: 14, fontWeight: "bold" }, right: 100 * ( a - 1 ), yAxisIndex: a, filterMode: "filter" } ) ) : ( b.dataZoom.push( { type: "slider", textStyle: { fontSize: 14, fontWeight: "bold" }, left: "0%", yAxisIndex: a, filterMode: "filter" } ), b.dataZoom.push( { type: "inside", textStyle: { fontSize: 14, fontWeight: "bold" }, left: "0%", yAxisIndex: a, filterMode: "filter" } ) ), a++, b.yAxis.push( ejesYTagCustom[ c ] );
        ejesYTagCustom = new Array, b.series = [];
        var b, d = 0;
        for ( var e in datosTagCustom.serie ) datosTagCustom.serie[ e ].yAxisIndex = d, b.series.push( datosTagCustom.serie[ e ] ), d++;
        $( "#menuIzq" )
            .bind( "widthChange", function () { graficoCustom.resize() } ), $( "#zonaControles" )
            .bind( "widthChange", function () { graficoCustom.resize() } ), b && graficoCustom.setOption( b, !0 )

    }

    function ajustesPresets( d ) {
        var a = document.getElementById( "ajustesPresets" );
        if ( "block" == a.style.display ) a.style.display = "none";
        else {
            if ( a.style.display = "block", "cargar" == d ) {
                a.innerHTML = "";
                var b = "<h3>Cargar Preset</h3><p>\xbfquieres cargar <b>" + document.getElementById( "selPresets" )
                    .options[ document.getElementById( "selPresets" )
                        .selectedIndex ].value + "</b>?</p>",
                    c = "<button class='btnPresetOk' onclick='cargarPreset()'>Cargar</button><button <button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button><button onclick='borrarPreset()' class='btnPresetBorrar'>Borrar</button><br><br><p id=txtPresetError></p>";
                a.innerHTML = b + c
            }
            if ( "guardar" == d ) {
                a.innerHTML = "";
                var b = "<h3>Guardar Preset</h3>Nombre:<br><input style='margin-left:2%;' id='txtPreset' type=text><br><br>",
                    c = "<button class='btnPresetOk' onclick='guardarPreset()'>Guardar</button><button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button><br><br><p id=txtPresetError></p>";
                a.innerHTML = b + c
            }
            if ( "vacio" == d ) {
                a.innerHTML = "";
                var b = "<h3>Guardar Preset</h3>No has seleccionado ninguna se\xf1al<br><br>",
                    c = "<button class='btnPresetCancelar' onclick='ajustesPresets(null)'>Cancelar</button>";
                a.innerHTML = b + c
            }
        }
    }

    //busca los presets del usuario y los lista o los carga
    function leerPresets( para ) {
        var datos = {};
        datos[ 'nombre' ] = usu;
        datos[ 'pwd' ] = pwd;
        if ( para == null || para == 'mostrar' ) {
            para = 'mostrar';
            var arrdatos = JSON.stringify( datos );
            $( document )
                .ready( function () {
                    $.ajax( {
                        type: 'GET',
                        url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?opcion=leerPresets&para=' + para,
                        data: {
                            arrdatos: arrdatos
                        },
                        success: function ( presets ) {
                            document.getElementById( "selPresets" )
                                .innerHTML = presets;

                        },
                        error: function ( e ) {
                            console.log( e );
                        },
                        // dataType: 'json'
                    } );
                } );

            function leerPresets( a ) {
                var b = {};
                if ( b.nombre = usu, b.pwd = pwd, null == a || "mostrar" == a ) {
                    a = "mostrar";
                    var c = JSON.stringify( b );
                    $( document )
                        .ready( function () {
                            $.ajax( {
                                type: "GET",
                                url: "http://localhost/Aquando/public/A_GraficasCustom.php?opcion=leerPresets&para=" + a,
                                data: { arrdatos: c },
                                success: function ( a ) {
                                    document.getElementById( "selPresets" )
                                        .innerHTML = a
                                },
                                error: function ( a ) { console.log( a ) }
                            } )
                        } )
                }
                if ( "cargar" == a ) {
                    a = "cargar";
                    var c = JSON.stringify( b );
                    $( document )
                        .ready( function () {
                            $.ajax( {
                                type: "GET",
                                url: "http://localhost/Aquando/public/A_GraficasCustom.php?opcion=leerPresets&para=" + a,
                                data: { arrdatos: c },
                                success: function ( e ) {
                                    for ( var c in presets_config = e )
                                        if ( presets_config[ c ].configuracion.includes( n_preset ) ) {
                                            var b = presets_config[ c ].configuracion;
                                            ( b = b.substring( b.indexOf( "@" ) + 1 ) )
                                            .substring( 0, b.indexOf( "?" ) );
                                            var d = b.substring( b.indexOf( "/" ) + 1 )
                                                .split( "/" ),
                                                f = new Array;
                                            for ( var c in d ) {
                                                var a = d[ c ].split( ":" );
                                                f[ a[ 0 ] ] = a[ 1 ], document.getElementById( a[ 0 ] )
                                                    .checked = "true", document.getElementById( "color" + a[ 0 ] )
                                                    .value = a[ 1 ], "darkgray" == document.getElementById( a[ 0 ] )
                                                    .parentNode.style.backgroundColor ? document.getElementById( a[ 0 ] )
                                                    .parentNode.style.backgroundColor = "lightgray" : document.getElementById( a[ 0 ] )
                                                    .parentNode.style.backgroundColor = "darkgray", document.getElementById( "color" + a[ 0 ] )
                                                    .parentNode.style.color = a[ 1 ]
                                            }
                                            aplicarCustom(), ajustesPresets( null )
                                        }
                                },
                                error: function ( a ) { console.log( a ) },
                                dataType: "json"
                            } )
                        } )
                }
            }

            function mostrarPresets() { leerPresets( "mostrar" ) }

            function cargarPreset() {
                document.getElementById( "btnControlCustom" )
                    .disabled = true;
                document.getElementById( "selPresets" )
                    .disabled = true;
                limpiar();
                document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "cargando...";
                n_preset = document.getElementById( 'selPresets' )
                    .options[ document.getElementById( 'selPresets' )
                        .selectedIndex ].value;
                if ( n_preset.includes( nombre_estacion_activa ) ) {
                    leerPresets( 'cargar' );
                } else {
                    document.getElementById( 'txtPresetError' )
                        .innerHTML += 'El preset no pertenece a esta estación';
                }
                document.getElementsByName( 'btnControlAplicar' )[ 0 ].innerHTML = "aplicar";
            }
            //a traves de AJAX busca en la config de usuario un preset y lo elimina
            function borrarPreset() {
                ajustesPresets( null );
                var n_preset = document.getElementById( 'selPresets' )
                    .options[ document.getElementById( 'selPresets' )
                        .selectedIndex ].value;
                var datos = {};
                datos[ 'nombre' ] = usu;
                datos[ 'pwd' ] = pwd;
                var arrdatos = JSON.stringify( datos );
                $( document )
                    .ready( function () {
                        $.ajax( {
                            type: 'GET',
                            url: 'A_GraficasCustom.php?opcion=borrar&preset=' + n_preset,
                            data: {
                                arrdatos: arrdatos
                            },
                            success: function () {
                                leerPresets( 'mostrar' );
                                setTimeout( ajustesPresets( null ), 1000 );
                            },
                            error: function ( e ) {
                                console.log( e );
                            },
                            dataType: 'json'
                        } );
                    } );
                mostrarPresets();
                document.getElementById( "btnControlCustom" )
                    .disabled = !0, document.getElementById( "selPresets" )
                    .disabled = !0, limpiar(), document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "cargando...", ( n_preset = document.getElementById( "selPresets" )
                        .options[ document.getElementById( "selPresets" )
                            .selectedIndex ].value )
                    .includes( nombre_estacion_activa ) ? leerPresets( "cargar" ) : document.getElementById( "txtPresetError" )
                    .innerHTML += "El preset no pertenece a esta estaci\xf3n", document.getElementsByName( "btnControlAplicar" )[ 0 ].innerHTML = "aplicar"
            }

            function borrarPreset() {
                ajustesPresets( null );
                var b = document.getElementById( "selPresets" )
                    .options[ document.getElementById( "selPresets" )
                        .selectedIndex ].value,
                    a = {};
                a.nombre = usu, a.pwd = pwd;
                var c = JSON.stringify( a );
                $( document )
                    .ready( function () { $.ajax( { type: "GET", url: "A_GraficasCustom.php?opcion=borrar&preset=" + b, data: { arrdatos: c }, success: function () { leerPresets( "mostrar" ), setTimeout( ajustesPresets( null ), 1e3 ) }, error: function ( a ) { console.log( a ) }, dataType: "json" } ) } ), mostrarPresets()

            }

            function guardarPreset() {
                if ( document.getElementById( 'txtPreset' )
                    .value != null && document.getElementById( 'txtPreset' )
                    .value != '' && !document.getElementById( 'txtPreset' )
                    .value.includes( ":" ) && !document.getElementById( 'txtPreset' )
                    .value.includes( "/" ) && !document.getElementById( 'txtPreset' )
                    .value.includes( "@" ) ) {
                    var checkTags = document.querySelectorAll( 'input[name=checkTag]:checked' );
                    var nombre_preset = nombre_estacion_activa + ": " + document.getElementById( 'txtPreset' )
                        .value;
                    var datosPreset = {};
                    var tags_colores = new Array();
                    for ( var i = 0; i < checkTags.length; i++ ) {
                        tags_colores[ checkTags[ i ].value ] = document.getElementById( 'color' + checkTags[ i ].value )
                            .value;
                    }
                    datosPreset[ 'usuario' ] = usu;
                    datosPreset[ 'pwd' ] = pwd;
                    datosPreset[ 'nombre' ] = nombre_preset;
                    datosPreset[ 'id_estacion' ] = document.getElementById( 'opciones' )
                        .value;
                    datosPreset[ 'tags_colores' ] = tags_colores;
                    console.log( datosPreset );
                    var arrDatosPreset = JSON.stringify( datosPreset );
                    $( document )
                        .ready( function () {
                            $.ajax( {
                                type: 'GET',
                                url: 'http://dateando.ddns.net:3000/Aquando.com/A_GraficasCustom.php?opcion=guardar',
                                data: {
                                    arrDatosPreset: arrDatosPreset
                                },
                                success: function ( info ) {

                                    document.getElementById( 'ajustesPresets' )
                                        .innerHTML += 'preset guardado';
                                    leerPresets( 'mostrar' );
                                    setTimeout( ajustesPresets( null ), 1000 );
                                },
                                error: function () {
                                    console.log( 'error en el guardado' );
                                },
                                dataType: 'json'
                            } );
                        } );
                } else {
                    document.getElementById( 'txtPresetError' )
                        .innerHTML = 'Introduce un nombre válido';
                    if ( null == document.getElementById( "txtPreset" )
                        .value || "" == document.getElementById( "txtPreset" )
                        .value || document.getElementById( "txtPreset" )
                        .value.includes( ":" ) || document.getElementById( "txtPreset" )
                        .value.includes( "/" ) || document.getElementById( "txtPreset" )
                        .value.includes( "@" ) ) document.getElementById( "txtPresetError" )
                        .innerHTML = "Introduce un nombre v\xe1lido";
                    else {
                        for ( var c = document.querySelectorAll( "input[name=checkTag]:checked" ), e = nombre_estacion_activa + ": " + document.getElementById( "txtPreset" )
                                .value, a = {}, d = new Array, b = 0; b < c.length; b++ ) d[ c[ b ].value ] = document.getElementById( "color" + c[ b ].value )
                            .value;
                        a.usuario = usu, a.pwd = pwd, a.nombre = e, a.id_estacion = document.getElementById( "opciones" )
                            .value, a.tags_colores = d, console.log( a );
                        var f = JSON.stringify( a );
                        $( document )
                            .ready( function () {
                                $.ajax( {
                                    type: "GET",
                                    url: "http://localhost/Aquando/public/A_GraficasCustom.php?opcion=guardar",
                                    data: { arrDatosPreset: f },
                                    success: function ( a ) {
                                        document.getElementById( "ajustesPresets" )
                                            .innerHTML += "preset guardado", leerPresets( "mostrar" ), setTimeout( ajustesPresets( null ), 1e3 )
                                    },
                                    error: function () { console.log( "error en el guardado" ) },
                                    dataType: "json"
                                } )
                            } )
                    }
                }
            }
        }
    }
}