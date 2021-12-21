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

function actualizar() {
    var orden = 'fecha';
    var sentido = 'DESC';
    if (document.getElementById('radioAsc').checked) {
        sentido = 'ASC';
    }

    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'A_Alarmas.php?funcion=actualizar&nombre=' + sessionStorage.getItem('nousu') + '&pwd=' + sessionStorage.getItem('pwd') + '&emp=' + sessionStorage.getItem('empusu') + '&sentido=' + sentido,
            success: function(alarmas) {
                document.getElementById("tablaAlarmas").innerHTML = alarmas;
            },
            error: function() {
                console.log("error");
            }

        });
    });
}

function filtrarPorEstacion() {

}