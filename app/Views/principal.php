<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src="css/principal.js"></script>
<script src="css/reloj.js"></script>





<main id="conPrincipal">
    <div id="conInfo">
        <div id="resumen" style="opacity: 0%; transition: 0.5s; height: 100%">
            <h3 style="margin: 5% 5%; margin-bottom:2%;">Resumen de actividad:</h3>
            <!-- <form id="formFiltrosInfo">
                <input type="checkbox" checked><label style="margin: 0 5px;">Depósito 1</label></input>
                <input type="checkbox" checked><label style="margin: 0 5px;">Depósito X</label></input>
            </form> -->

            <!-----WIDGET DE PRUEBA PEPINA---->
            <div id="widgetsI">
            <?php
                $cont = 1;
                while($cont <= 4){
                    echo '
                    <div id="widgetMixto" onclick="transicion('.$cont.');">
                    <div id="widVal'.$cont.'" style="height: 20em; width:100%;">   
                    </div>
                <!--conexion-->
                    <div id="widConex'.$cont.'" style="height: 20em; width:100%;">
                    </div>
                <!--minimos-->
                    <div id="widMin'.$cont.'" style="height: 20em; width:100%;">
                    </div>
                <!--alarmas-->
                    <div id="widAla'.$cont.'" style="padding:5% 0%;position: relative;right:5%;height: 20em; width:100%;">
                    
                        <ul style="color:white;list-style:none;">
                            <h6>Alarmas:</h6>
                            <li>info de alarma</li>
                            <li>info de alarma</li>
                            <li>info de alarma</li>
                        </ul>
                    </div>
                </div>
                    
                    '
                    ;
                    $cont ++;
                }
            ?>
            </div>
        </div>
    </div>
    <div id="conCarrusel">
        <h3 style="margin: 3% 0%">Experimento</h3>
            
        <iframe width="100%" height="80%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=-1.6398355364799502%2C42.753842721248496%2C-1.635463535785675%2C42.75560341523702&amp;layer=hot&amp;marker=42.75472307449567%2C-1.6376495361328125" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=42.75472&amp;mlon=-1.63765#map=19/42.75472/-1.63765&amp;layers=HN">Ver mapa más grande</a></small>
    </div>

    <table id="alarmasSur">

        <tr id="alarmaAcK">
            <td>alarma de ejemplo</td>
            <td>tipo 1</td>
            <td>alerta roja latiente</td>
        </tr>

        <tr id="alarma">
            <td>alarma de ejemplo</td>
            <td>tipo 1 Ack</td>
            <td>alerta rojo oscuro</td>
        </tr>

        <tr id="restaurada">
            <td>alarma de ejemplo</td>
            <td>tipo 3</td>
            <td>alerta verde claro</td>
        </tr>

        <tr id="restauradaAck">
            <td>alarma de ejemplo</td>
            <td>tipo 3 Ack</td>
            <td>alerta verde Oscuro</td>
        </tr>
    </table>


</main>




<script>
    var nwids = 0;
    var e = 1;
    var posiciones = {};
    window.onload = function() {
        setInterval(fechaYHora, 1000);
        setInterval(latido, 2000);
        mostrarResumen();
        cargarDatos();
        
    }

        
</script>


<?= $this->endSection() ?>