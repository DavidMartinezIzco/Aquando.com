function actualizarConexiones(nombre, pwd) {

    $(document.getElementById("iconoActu")).addClass("rotante");
    setTimeout(function() {
        document.getElementById("iconoActu").classList.remove("rotante");
    }, 3000);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Conexiones.php?nombre=' + nombre + '&pwd=' + pwd + '&opcion=conex',
            success: function(conex) {
                document.getElementById("tablaConex").innerHTML = conex;
            },
            error: function() {
                console.log("error");
            }

        });
    });
}

function obtenerCalidadTags(estacion) {
    if (document.getElementsByClassName('comsSelec')[0]) {
        document.getElementsByClassName('comsSelec')[0].classList.remove("comsSelec");
    }
    $(document.getElementsByName(estacion)[0]).addClass('comsSelec');

    nombrarEstacion(estacion);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Conexiones.php?estacion=' + estacion + '&opcion=cali',
            success: function(cali) {
                document.getElementById("seccionCalidad").innerHTML = cali;
            },
            error: function() {
                console.log("error");
            }

        });
    });
}

function parpadeoProblema() {
    desvanecer();
    setTimeout(aparecer, 1000);
}

function desvanecer() {

    var nalertas = document.getElementsByName('alerta').length;
    for (var i = 0; i < nalertas; i++) {
        document.getElementsByName('alerta')[i].style.opacity = '0';
    }
}

function aparecer() {
    var nalertas = document.getElementsByName('alerta').length;
    for (var i = 0; i < nalertas; i++) {
        document.getElementsByName('alerta')[i].style.opacity = '1';
    }
}

function nombrarEstacion(estacion) {

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Conexiones.php?estacion=' + estacion + '&opcion=nom',
            success: function(est) {
                document.getElementById("calidadSenales").innerHTML = '<h4 id="calidadSenales"> Calidad de señal: ' + est + '</h4>';

            },
            error: function() {
                console.log("error");
            }

        });
    });

}