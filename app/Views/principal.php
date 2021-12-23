<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src="css/principal.js"></script>
<script src="css/reloj.js"></script>


<main id="conPrincipal" style="height: 50em; overflow-y:scroll; width:100%;border-radius:10px; margin-top:1%">
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
                while ($cont <= 4) {
                    echo '
                    <div id="widgetMixto" onclick="transicion(' . $cont . ');">
                    <div id="widVal' . $cont . '" style="height: 20em; width:100%;">   
                    </div>
                <!--conexion-->
                    <div id="widConex' . $cont . '" style="height: 20em; width:100%;">
                    </div>
                <!--minimos-->
                    <div id="widMin' . $cont . '" style="height: 20em; width:100%;">
                    </div>
                <!--alarmas-->
                    <div id="widAla' . $cont . '" style="padding:5% 0%;position: relative;right:5%;height: 20em; width:100%;">
                    
                        <ul style="color:white;list-style:none;">
                            <h6>Alarmas:</h6>
                            <li>info de alarma</li>
                            <li>info de alarma</li>
                            <li>info de alarma</li>
                        </ul>
                    </div>
                </div>
                    
                    ';
                    $cont++;
                }
                ?>
            </div>
        </div>
    </div>
    <div id="conCarrusel">
        <h3 style="margin: 3% 0%">Mapa</h3>
        <iframe width="100%" height="80%" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=-1.6398355364799502%2C42.753842721248496%2C-1.635463535785675%2C42.75560341523702&amp;layer=hot&amp;marker=42.75472307449567%2C-1.6376495361328125" style="border: 1px solid black"></iframe>
    </div>
    <table id="alarmasSur">
    </table>
</main>
<!---zona alarmas--->


<script>
    // var nwids = 0;
    // var e = 1;
    // var posiciones = {};
    
    window.onload = function() {
        comprobarTiempo();
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        var idusu = <?php echo $_SESSION['idusu']?>;
        actualizarSur('general',usu, pwd, idusu);
        //setInterval(fechaYHora, 1000);
        //setInterval(comprobarTiempo, 1000);
        //setInterval(actualizarSur('general'), 3000);
        mostrarResumen();
        //cargarDatos();
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });

        
    }
</script>


<?= $this->endSection() ?>