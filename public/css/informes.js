//saca una captura de las alarmas
function imprimir() {


    document.getElementsByName('btnControlPrint')[0].innerText = 'cargando...';
    pasarAPDF();
    setTimeout(() => {
        document.getElementsByName('btnControlPrint')[0].innerHTML = '<i class="fas fa-print"></i>';
    }, 4000);

    // var w = document.getElementById("espacioInforme").innerWidth;
    // var h = document.getElementById("espacioInforme").innerHeight;
    // console.log('imprimimos');
    // html2canvas(document.querySelector('#espacioInforme'), {
    //     scale: 1,
    //     dpi: 300,
    // }).then(function(canvas) {
    //     guardar(canvas.toDataURL(), 'informe.png');
    //     pasarAPDF();
    // });
}


//descarga la captura de las alarmas 
function guardar(uri, filename) {
    console.log('guardamos');
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
    var doc = new jsPDF();
    doc.setTextColor('#FFFFF')
    doc.addHTML($('#espacioInforme')[0], function() {
        doc.save('informe.pdf');
    });

}

//abre o cierra la cabeza de opciones para el informe
function opciones() {
    if (document.getElementById("informesNorte").style.height == 0) {
        document.getElementById("informesNorte").style.height = '15%';
        document.getElementById("btnMenuInformes").style.top = '19.5%';
    } else {
        document.getElementById("informesNorte").style.height = 0;
        document.getElementById("btnMenuInformes").style.top = '6%';
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
        // console.log(estaciones[est]);
        nestaciones[estaciones[est]] = document.getElementById('est' + estaciones[est]).getAttribute('name');
    }

    // console.log(nestaciones);
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

                // console.log(informe);
                document.getElementById('espacioInforme').innerHTML = "";
                var currentdate = new Date();
                var fechahora = "" + currentdate.getDate() + "/" +
                    (currentdate.getMonth() + 1) + "/" +
                    currentdate.getFullYear() + " a las " +
                    currentdate.getHours() + ":" +
                    currentdate.getMinutes();

                var cabecera = "<h1 style='color:rgb(1, 168, 184);'>Informe sobre " + tipoInf + "</h1><hr><p style='color:rgb(65, 65, 65);'>desde: " + fInicio + " hasta: " + fFin + " </p><p style='color:rgb(65, 65, 65);'>Por " + nomusuario + " el " + fechahora + "</p><br>";
                var pie = '<p style="text-align:center">powered by <img src="../public/logo.png" style="height: 3.5em; margin-left: 1%;"></p>';

                document.getElementById('espacioInforme').innerHTML += cabecera;
                document.getElementById('espacioInforme').innerHTML += informe;
                document.getElementById('espacioInforme').innerHTML += pie;

            },
            error: function(error) {
                console.log(error);
                console.log("error en los informes");
            },
            //dataType: 'json'
        });
    });


}