//limpia los filtros
function limpiar() {
    document.getElementById("radioDesc").checked = 'checked';
    document.getElementById("radioFecha").checked = 'checked';
    document.getElementById("estaciones").value = 'all';
    document.getElementsByName("btnControlReset")[0].textContent = "limpiando...";
    setTimeout(function() { document.getElementsByName("btnControlReset")[0].textContent = "reset" }, 1000);
    actualizar();
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


//saca una captura de las alarmas
function imprimir() {

    var hoy = new Date();
    var fechaHoy = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate();
    var nombre_informe = 'Alarmas ' + fechaHoy + '.pdf';
    var informe = document.getElementById('tablaAlarmas');
    var opt = {
        margin: 0,
        filename: nombre_informe,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, logging: true, dpi: 300, letterRendering: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    var exp_informe = new html2pdf(informe, opt);
    exp_informe.getPdf(true).then((pdf) => {

    });


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

        var fechaInicio = document.getElementById('fechaInicio').value;
        var fechaFin = document.getElementById('fechaFin').value;




        var nombre = sessionStorage.getItem('nousu');
        var pwd = sessionStorage.getItem('pwd');
        var emp = sessionStorage.getItem('emp');

        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: 'A_Alarmas.php?funcion=actualizar&nombre=' + nombre + '&pwd=' + pwd + '&emp=' + emp + '&sentido=' + sentido + '&orden=' + orden + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin,
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
    var fechaInicio = document.getElementById('fechaInicio').value;
    var fechaFin = document.getElementById('fechaFin').value;
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
                url: 'A_Alarmas.php?funcion=estacion&sentido=' + sentido + '&orden=' + orden + '&estacion=' + id_estacion + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin,
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
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Alarmas.php?funcion=reconocer&alarma=' + id_alarma + '&nombre=' + sessionStorage.getItem('nousu') + '&fecha_ack=',
            success: function() {
                actualizar(null);
            },
            error: function() {
                console.log("error en la update");
            }

        });
    });
}

// function efectoAlerta() {
//     var alertas = document.getElementsByClassName('activaNo');
//     for (var i = 0, max = alertas.length; i < max; i++) {
//         setInterval(resaltar(alertas[i]), 1000);
//     }
// }

// function resaltar(elem) {
//     elem.style.backgroundColor = "red";
//     setTimeout(function() { elem.style.backgroundColor = "tomato" }, 1000);
// }

function reordenar(opcion) {

    actualizar(opcion);

}