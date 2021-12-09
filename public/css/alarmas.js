
    //limpia los filtros
    function  limpiar(){
        document.getElementById("radioDesc").checked ='checked';
        document.getElementById("radioFecha").checked = 'checked';
        document.getElementById("estaciones").value = 'all';
        document.getElementsByName("btnControlReset")[0].textContent = "limpiando...";
        setTimeout(function(){document.getElementsByName("btnControlReset")[0].textContent = "reset"},1000);
        actualizar();
    }

    //aplica los filtros y actualiza las alarmas
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

    //filtra los datos
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
    function opciones(){
        if (document.getElementById("zonaOpciones").style.height == '10%') {
            document.getElementById("zonaOpciones").style.height = "0%";
            document.getElementById("zonaAlarmas").style.maxHeight = '95%';
        }
        else{
            document.getElementById("zonaOpciones").style.height = "10%";
            document.getElementById("zonaAlarmas").style.maxHeight = '85%';
        }
    }