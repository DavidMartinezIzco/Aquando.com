
    function imprimir() {
        var prtContent = document.getElementById("tablaAlarmas");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.write('');
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

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


