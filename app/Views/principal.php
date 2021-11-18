<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src="css/principal.js"></script>


<main id="conPrincipal">
    <div id="conInfo" style="overflow-y:scroll">
        <div id="resumen" style="opacity: 0%; transition: 0.5s; position: relative;">
            <h3 style="margin: 5% 5%; margin-bottom:2%;">Resumen de actividad:</h3>
            <form id="formFiltrosInfo">
                <input type="checkbox" checked><label style="margin: 0 5px;">Depósito 1</label></input>
                <input type="checkbox" checked><label style="margin: 0 5px;">Depósito X</label></input>
            </form>

            <!-----WIDGET DE PRUEBA PEPINA---->

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
    <div id="conCarrusel">
        <!-- <h3 style="margin-top: 3%;margin-left:5%">Experimento</h3>
            
            <h3 style="margin: 1% 5%;">Últimas alarmas:</h3>
            <form id="formFiltrosAlarmas">
                <input type="checkbox" checked><label style="margin: 0 5px;">Pendientes</label></input>
                <input type="checkbox" checked><label style="margin: 0 5px;">Revisadas</label></input>
                <input type="checkbox" checked><label style="margin: 0 5px;">En revisión</label></input>  
            </form>
            <div id="conPostTabla">
                <ul style="padding: 0;">
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                    <li>alarma:</li>
                </ul>
            </div> -->
    </div>

</main>


<script>

    
    var nwids = 0;
    var e = 1;
    var posiciones = {};
    window.onload = function() {
        mostrarResumen();
        cargarDatos();
        setInterval(fechaYHora, 1000);
        
    }

        
</script>


<?= $this->endSection() ?>