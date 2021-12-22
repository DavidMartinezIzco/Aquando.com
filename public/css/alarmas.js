//limpia los filtros
function limpiar() {
    document.getElementById("radioDesc").checked = 'checked';
    document.getElementById("radioFecha").checked = 'checked';
    document.getElementById("estaciones").value = 'all';
    document.getElementsByName("btnControlReset")[0].textContent = "limpiando...";
    setTimeout(function() { document.getElementsByName("btnControlReset")[0].textContent = "reset" }, 1000);
    actualizar();
}

//saca una captura de las alarmas
function imprimir() {
    html2canvas(document.querySelector('#tablaAlarmas')).then(function(canvas) {
        guardar(canvas.toDataURL(), 'alarmas.png');
    });

}

//descarga la captura de las alarmas
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

//esconde o muestra las opciones
function opciones() {
    if (document.getElementById("zonaOpciones").style.height == '10%') {
        document.getElementById("zonaOpciones").style.height = "0%";
        document.getElementById("zonaAlarmas").style.maxHeight = '95%';
    } else {
        document.getElementById("zonaOpciones").style.height = "10%";
        document.getElementById("zonaAlarmas").style.maxHeight = '85%';
    }
}

function actualizar(reorden) {

    var id_estacion = document.getElementById("estaciones").value;
    if (id_estacion != 'all') {
        filtrarPorEstacion();
    } else {
        var orden = 'fecha';
        for (var filtro in document.getElementsByName('filtro')) {
            if (document.getElementsByName('filtro')[filtro].checked == true) {
                orden = document.getElementsByName('filtro')[filtro].value;
            }
        }
        if (reorden != null) {
            orden = reorden;
        }

        var sentido = 'DESC';
        if (document.getElementById('radioAsc').checked == true) {
            sentido = 'ASC';
        }

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Alarmas.php?funcion=actualizar&nombre=' + sessionStorage.getItem('nousu') + '&pwd=' + sessionStorage.getItem('pwd') + '&emp=' + sessionStorage.getItem('empusu') + '&sentido=' + sentido + '&orden=' + orden,
                success: function(alarmas) {
                    document.getElementById("tablaAlarmas").innerHTML = alarmas;
                },
                error: function() {
                    console.log("error");
                }

            });
        });
    }

}

function filtrarPorEstacion() {

    var id_estacion = document.getElementById("estaciones").value;
    if (id_estacion == 'all') {
        actualizar();
    } else {
        var orden = 'fecha';
        for (var filtro in document.getElementsByName('filtro')) {
            if (document.getElementsByName('filtro')[filtro].checked == true) {
                orden = document.getElementsByName('filtro')[filtro].value;
            }
        }
        var sentido = 'DESC';
        if (document.getElementById('radioAsc').checked == true) {
            sentido = 'ASC';
        }

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Alarmas.php?funcion=estacion&sentido=' + sentido + '&orden=' + orden + '&estacion=' + id_estacion,
                success: function(alarmas) {
                    document.getElementById("tablaAlarmas").innerHTML = alarmas;
                },
                error: function() {
                    console.log("error");
                }

            });
        });
    }


}

function reconocer(id_alarma) {
    var fecha_ack = Date.now();
    console.log(fecha_ack);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Alarmas.php?funcion=reconocer&alarma=' + id_alarma + '&nombre=' + sessionStorage.getItem('nousu') + '&fecha_ack=',
            success: function(exito) {
                actualizar();
            },
            error: function() {
                console.log("error en la update");
            }

        });
    });



}

function efectoAlerta() {

    var alertas = document.getElementsByClassName('activaNo');
    for (var i = 0, max = alertas.length; i < max; i++) {
        setInterval(resaltar(alertas[i]), 1000);
    }

}

function resaltar(elem) {
    elem.style.backgroundColor = "red";
    setTimeout(function() { elem.style.backgroundColor = "tomato" }, 1000);
}

function reordenar(opcion) {

    actualizar(opcion);

}