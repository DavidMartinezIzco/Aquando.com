

    function  limpiar(){
        document.getElementById("radioDesc").checked ='checked';
        document.getElementById("radioFecha").checked = 'checked';
        document.getElementById("estaciones").value = 'all';
        document.getElementsByName("btnControlReset")[0].textContent = "limpiando...";
        setTimeout(function(){document.getElementsByName("btnControlReset")[0].textContent = "reset"},1000);
        actualizar();
    }

    function aplicarFiltros() {
        var filtro = "";
        var orden = "";

        if (document.getElementById("radioFecha").checked) {
            filtro = document.getElementById("radioFecha").value;
        }
        if (document.getElementById("radioMotivo").checked) {
            filtro = document.getElementById("radioMotivo").value;
        }
        if (document.getElementById("radioCanal").checked) {
            filtro = document.getElementById("radioCanal").value;
        }

        if (document.getElementById("radioAsc").checked) {
            orden = document.getElementById("radioAsc").value;
        }
    
        if (document.getElementById("radioDesc").checked) {
            orden = document.getElementById("radioDesc").value;
        }

        var estacion = null;
        if(document.getElementById("estaciones").value != 'all'){
            estacion = document.getElementById("estaciones").value;
            document.getElementsByName("btnControl")[0].textContent = "Cargando...";
        setTimeout(function(){document.getElementsByName("btnControl")[0].textContent = "aplicar"},1000);
            $(document).ready(function(){
                
                $.ajax({
                    type: 'GET',
                    url: 'A_Alarmas.php?estacion=' + estacion + '&filtro=' + filtro + '&orden=' + orden,
                    success: function(alarmas) {
                        $("#tablaAlarmas").html(alarmas);
                    }
                });
                
            });
        }
        else{
            actualizar();
        }

        
        return false

    }

    function filtrarPor(tipo){

        if(tipo == 'Motivo'){
            if(document.getElementById("radioMotivo").checked && document.getElementById("radioDesc").checked){
                document.getElementById("radioAsc").checked = true;
            }
            else{
                document.getElementById("radioDesc").checked = true;
            }
            document.getElementById("radioMotivo").checked = true;
        }
        if(tipo == 'Canal'){
            if(document.getElementById("radioCanal").checked && document.getElementById("radioDesc").checked){
                document.getElementById("radioAsc").checked = true;
            }
            else{
                document.getElementById("radioDesc").checked = true;
            }
            document.getElementById("radioCanal").checked = true;
        }
        if(tipo == 'Fecha'){
            if(document.getElementById("radioFecha").checked && document.getElementById("radioDesc").checked){
                document.getElementById("radioAsc").checked = true;
            }
            else{
                document.getElementById("radioDesc").checked = true;
            }
            document.getElementById("radioFecha").checked = true;
        }
        if(tipo == 'Estacion'){
            if(document.getElementById("radioEstacion").checked && document.getElementById("radioDesc").checked){
                document.getElementById("radioAsc").checked = true;
            }
            else{
                document.getElementById("radioDesc").checked = true;
            }
            document.getElementById("radioEstacion").checked = true;
        }
        aplicarFiltros();
    }

    function imprimir() {
        html2canvas(document.querySelector('#tablaAlarmas')).then(function(canvas) {
            guardar(canvas.toDataURL(), 'alarmas.png');
        });
        
    }

    
function guardar(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

        link.href = uri;
        link.download = filename;

        //Firefox requires the link to be in the body
        document.body.appendChild(link);

        //simulate click
        link.click();

        //remove the link when done
        document.body.removeChild(link);

    } else {

        window.open(uri);

    }
}