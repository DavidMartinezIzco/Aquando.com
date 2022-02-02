//abre o cierra la cabeza de opciones para el informe
function opciones() {
    if (document.getElementById("informesNorte").style.height == '15%') {
        document.getElementById("informesNorte").style.height = '0%';
        document.getElementById("btnMenuInformes").style.top = '6%';
    } else {
        document.getElementById("informesNorte").style.height = '15%';
        document.getElementById("btnMenuInformes").style.top = '19.5%';
    }
}

//inicia con valores los formularios de las fechas
function inicioFin() {
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0, 10);
    });
    $(document).ready(function() {
        $('#radioFecha').val(new Date().toDateInputValue());
    });
}

function obtenerInforme() {
    tipo = document.getElementById('radInforme').value;
    var opcion = '';

    if (tipo == 'cau') {
        opcion = 'cau';
    }
    if (tipo == 'niv') {
        opcion = 'niv';
    }
    if (tipo == 'acu') {
        opcion = 'acu';
    }


    var fInicio = document.getElementById('radioFecha').value;
    var fFin = document.getElementById("radioMotivo").value;
    // var estaciones = document.getElementById("opcionesEstacion").value;
    var estaciones = $('#opcionesEstacion').val();
    console.log(estaciones);
    var arrEstaciones = JSON.stringify(estaciones);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            data: { arrEstaciones: arrEstaciones },
            contentType: 'application/json;charset=utf-8',
            url: 'A_Informes.php?opcion=' + opcion + '&fechaIni=' + fInicio + '&fechaFin=' + fFin,
            success: function(informe) {
                console.log(informe);
            },
            error: function(error) {
                console.log(error);
                console.log("error en los informes");
            },
            dataType: 'json'
        });
    });


}