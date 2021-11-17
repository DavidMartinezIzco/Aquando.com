
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


    function aplicarFiltros() {
        var filtro = "";
        if (document.getElementById("radioFecha").checked) {
            filtro = document.getElementById("radioFecha").value;
        }
        if (document.getElementById("radioMotivo").checked) {
            filtro = document.getElementById("radioMotivo").value;
        }
        if (document.getElementById("radioCanal").checked) {
            filtro = document.getElementById("radioCanal").value;
        }

        var estacion = null;
        if(document.getElementById("estaciones").value != 'all'){
            estacion = document.getElementById("estaciones").value;
            
            $(document).ready(function(){
                
                $.ajax({
                    type: 'GET',
                    url: 'A_Alarmas.php?estacion=' + estacion + '&filtro=' + filtro,
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


