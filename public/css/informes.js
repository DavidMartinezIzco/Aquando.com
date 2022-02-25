//saca una captura de las alarmas
function imprimir() {


    document.getElementsByName('btnControlPrint')[0].innerText = 'cargando...';
    document.getElementById('informesSur').style.overflowY = 'unset';
    pasarAPDF();
    document.getElementById('informesSur').style.overflowY = 'scroll';
    setTimeout(() => {
        document.getElementsByName('btnControlPrint')[0].innerHTML = '<i class="fas fa-print"></i>';
    }, 4000);

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

function pasarAPDF() {
    //https://openbase.com/js/js-html2pdf/documentation
    var hoy = new Date();
    var al = $("#espacioInforme").height();
    var an = $("#espacioInforme").width();
    var fechaHoy = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate();
    var nombre_informe = 'informe ' + fechaHoy + '.pdf';
    var informe = document.getElementById('espacioInforme');
    var opt = {
        margin: [10, 0, 10, 0],
        filename: nombre_informe,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, logging: true, dpi: 300, letterRendering: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    var exp_informe = new html2pdf(informe, opt);
    exp_informe.getPdf(true).then((pdf) => {});
}

function reset() {
    document.getElementById('espacioInforme').innerHTML = "";
}

//abre o cierra la cabeza de opciones para el informe
function opciones() {
    if (document.getElementById("informesNorte").style.height == '15%') {
        document.getElementById("informesNorte").style.height = 0;
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
    tipo = document.querySelector('input[name="radInforme"]:checked').value;
    var opcion = '';
    var tipoInf = '';
    if (tipo == 'cau') {
        opcion = 'cau';
        tipoInf = 'caudales';
    }
    if (tipo == 'niv') {
        opcion = 'niv';
        tipoInf = 'niveles';
    }
    if (tipo == 'acu') {
        opcion = 'acu';
        tipoInf = 'acumulados';
    }

    var fInicio = document.getElementById('radioFecha').value;
    var fFin = document.getElementById("radioMotivo").value;
    // var estaciones = document.getElementById("opcionesEstacion").value;
    var nestaciones = Array();
    var estaciones = $('#opcionesEstacion').val();
    var nestaciones = [];
    for (var est in estaciones) {
        nestaciones[estaciones[est]] = document.getElementById('est' + estaciones[est]).getAttribute('name');
    }
    var arrEstaciones = JSON.stringify(estaciones);
    var arrNombres = JSON.stringify(nestaciones);

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            data: {
                arrEstaciones: arrEstaciones,
                arrNombres: arrNombres
            },
            contentType: 'application/json;charset=utf-8',
            url: 'A_Informes.php?opcion=' + opcion + '&fechaIni=' + fInicio + '&fechaFin=' + fFin,
            success: function(informe) {
                reset();
                var ahora = new Date();
                var fechahora = "" + ahora.getDate() + "-" +
                    (ahora.getMonth() + 1) + "-" +
                    ahora.getFullYear() + " a las " +
                    ahora.getHours() + ":" +
                    ahora.getMinutes();

                var cabecera = "<h1 style='color:rgb(1, 168, 184);'>Informe sobre " + tipoInf + "</h1><hr><p style='color:rgb(65, 65, 65);'>Desde: " + fInicio + " hasta: " + fFin + " </p><p style='color:rgb(65, 65, 65);'>Por " + nomusuario + " el " + fechahora + "</p><br>";
                var pie = '<p style="text-align:center">powered by <img src="../public/logo.png" style="height: 3.5em; margin-left: 1%;"></p>';

                document.getElementById('espacioInforme').innerHTML += cabecera;
                document.getElementById('espacioInforme').innerHTML += informe;
                document.getElementById('espacioInforme').innerHTML += pie;

            },
            error: function() {
                console.log("error en los informes");
            },
            //dataType: 'json'
        });
    });


}