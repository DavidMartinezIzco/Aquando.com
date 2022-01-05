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
                        document.getElementById("opcionesTag").innerHTML += '<li style="background-color:darkgray"><input type="checkbox" name="checkTag" style="visibility: hidden;" value="' + tags[tag]['id_tag'] + '" checked id = ' + tags[tag]['id_tag'] + '><label for = "' + tags[tag]['id_tag'] + '" style="box-sizing: none"> ' + tags[tag]['nombre_tag'] + ' </label> <label> <i class= "fas fa-palette"> </i><input type="color" class="form-control-color" id="color' + tags[tag]['id_tag'] + '" style="visibility:hidden" title="color" list="coloresTagGraf"></label></li>';

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
        infoTags(id_estacion, ajustesTag[ajusteTag], metas, fechaInicio, fechaFin);
    }

}

function infoTags(estacion, tag, metas, fechaIni, fechaFin) {


    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_GraficasCustom.php?estacion=' + estacion + '&id_tag=' + tag + '&fechaIni=' + fechaIni + '&fechaFin=' + fechaFin + '&meta=' + metas + '&opcion=tag',
            success: function(datosTag) {
                infoTag = datosTag;

                prepararTag(infoTag)

                //se devuelven las "series" del tag y el meta en un mismo array
                //se preparan el tag y las series para el grafico
                //se renderiza el grafico con TODO LO QUE TOQUE
                //rezar
            },
            error: function() {
                console.log("error");
            },
            dataType: 'json'
        });
    });



}

function prepararTag(info, color) {



}

// function quitarTag(id_tag) {}

// function añadirMeta(meta, color) {}

// function quitarMeta(meta) {}

// function renderGrafico(datos) {}