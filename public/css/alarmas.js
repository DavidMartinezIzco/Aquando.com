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
        html2canvas: { scale: 2, logging: false, dpi: 300, letterRendering: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    var exp_informe = new html2pdf(informe, opt);
    exp_informe.getPdf(true).then((pdf) => {

    });

}
//crea un objeto csv de la tabla de alarmas
function exportarCSV() {
    var hoy = new Date();
    var fechaHoy = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate();
    var nombre_informe = 'Alarmas ' + fechaHoy;
    var datosExp = [];
    var orig = document.getElementById("tablaAlarmas");
    var filas = orig.querySelectorAll("table tr");
    for (var i = 0; i < filas.length; i++) {
        var fila = [];
        var colus = filas[i].querySelectorAll("td, th");
        for (var j = 0; j < colus.length; j++) {
            fila.push(colus[j].innerText);
        }
        datosExp.push(fila.join(";"));
    }
    console.log(datosExp.join("\n"));
    descargarArchivoCSV(datosExp.join("\n"), nombre_informe);
}
//descarga el objeto CSV
function descargarArchivoCSV(csv, archivo) {
    var archivo_csv, link_descarga;
    archivo_csv = new Blob([csv], { type: "text/csv" });
    link_descarga = document.createElement("a");
    link_descarga.setAttribute('target', '_blank');
    link_descarga.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(archivo_csv));
    link_descarga.download = archivo;
    link_descarga.href = window.URL.createObjectURL(archivo_csv);
    link_descarga.style.display = "none";
    document.body.appendChild(link_descarga);
    link_descarga.click();
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

//refresca la lista de alarmas según los ajustes seleccionados en los controles
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
                url: 'http://dateando.ddns.net:3000/Aquando.com/A_Alarmas.php?funcion=actualizar&nombre=' + nombre + '&pwd=' + pwd + '&emp=' + emp + '&sentido=' + sentido + '&orden=' + orden + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin,
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

//muestra en la tabla las alarmas de una estacion concreta en funcion de los ajustes seleccionados en los controles
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
                url: 'http://dateando.ddns.net:3000/Aquando.com/A_Alarmas.php?funcion=estacion&sentido=' + sentido + '&orden=' + orden + '&estacion=' + id_estacion + '&fechaInicio=' + fechaInicio + '&fechaFin=' + fechaFin,
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

//establece el valor de una alarma como ACK con la fecha actual como fecha_ack y el usuasrio como reconocedor de la alarma y refresca la lista
function reconocer(id_alarma) {
    var fecha_ack = Date.now();
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'http://dateando.ddns.net:3000/Aquando.com/A_Alarmas.php?funcion=reconocer&alarma=' + id_alarma + '&nombre=' + sessionStorage.getItem('nousu') + '&fecha_ack=',
            success: function() {
                actualizar(null);
            },
            error: function() {
                console.log("error en la update");
            }

        });
    });
}
//recibe los ajustes de los controles y refresca la lista de alarmas
function reordenar(opcion) {

    actualizar(opcion);

}