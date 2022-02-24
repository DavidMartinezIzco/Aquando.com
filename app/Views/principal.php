<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src="css/principal.js"></script>
<script src="css/reloj.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<link rel="stylesheet" type="text/css" href="css/sur.css">


<main id="conPrincipal" style="border-radius:10px; margin-top:0.5%;">

    <!-- zona IZQUIERDA -->
    <div id="prinIzq">

        <div id="prinIzqSup">
            <!-- zona del mapa -->
            <div id="conMapa">

            </div>

        </div>
        <div id="prinIzqInf">

            <!-- zona de wid digitales -->
            <div id="widSup">
                <!-- <div class="digiIzq"></div>
                <div class="digiDer"></div> -->
            </div>
            <div id="widInf">
                <!-- <div class="digiIzq"></div>
                <div class="digiDer"></div> -->
            </div>
        </div>
    </div>

    <!-- ZONA DERECHA -->

    <div id="prinDer">
        <div id="widSup">
            <!-- AQUI IRAN LOS WIDS EN CARRUSEL -->
            <div class="anaIzq" onclick="rotarCarrusel(this)">
                <div id="carrusel">
                    <div class="carr" name="ult_valor">
                        <!-- valor actual --><h4 onclick="ajustes()">pincha aqui para configurar tu widget</h4>
                    </div>
                    <div class="carr" name="trend_dia">
                        <!-- trend dia -->2
                    </div>
                    <div class="carr" name="agreg_semana">
                        <!-- trend semanal -->3
                    </div>
                </div>

            </div>
            <div class="anaDer" onclick="rotarCarrusel(this)">
                <div id="carrusel">
                    <div class="carr" name="carru1">
                        <!-- valor actual --><h4 onclick="ajustes()">pincha aqui para configurar tu widget</h4>
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend dia -->2
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend semanal -->3
                    </div>
                </div>
            </div>
        </div>
        <div id="widInf">
            <!-- AQUI IRAN LOS WIDS EN CARRUSEL -->
            <div class="anaIzq" onclick="rotarCarrusel(this)">
                <div id="carrusel">
                    <div class="carr" name="carru1">
                        <!-- valor actual --><h4 onclick="ajustes()">pincha aqui para configurar tu widget</h4>
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend dia -->2
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend semanal -->3
                    </div>
                </div>
            </div>
            <div class="anaDer" onclick="rotarCarrusel(this)">
                <div id="carrusel">
                    <div class="carr" name="carru1">
                        <!-- valor actual --><h4 onclick="ajustes()">pincha aqui para configurar tu widget</h4>
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend dia -->2
                    </div>
                    <div class="carr" name="carru1">
                        <!-- trend semanal -->3
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!---zona alarmas--->
    <table id="alarmasSur">
    </table>

    <!-- ajustes generales de seccion -->
    <!-- mucho del codigo es provisional -->
    <div id="ajustesSeccion">
        <div id="seccionSel">
            <ul id="widList" style="list-style: none; padding-left: 0;width:100%;height:100%">
                <li id="w1" value="w1">Widget 1</li>
                <li id="w2" value="w2">Widget 2</li>
                <li id="w3" value="w3">Widget 3</li>
                <li id="w4" value="w4">Widget 4</li>
            </ul>
        </div>
        <div id="seccionDisplay">
            <i class="fas fa-times" id="btnAjustesCerrar" onclick="ajustes()"></i>
            <div id="seccionAjustes">
            </div>
        </div>
    </div>

</main>



<script>
    var estacionesUsu = <?php echo json_encode($_SESSION['estaciones']); ?>;
    var estacionesUbis = <?php echo json_encode($estacionesUbis); ?>;
    var usu = '<?php echo $_SESSION['nombre'] ?>';
    var pwd = '<?php echo $_SESSION['pwd'] ?>';
    sessionStorage.setItem('nousu', usu);
    sessionStorage.setItem('pwd', pwd);
    window.onload = function() {
        
        mapas();
        actualizar();
        ajustes();
        comprobarTiempo();
        actualizarSur('general', usu, pwd, null);
        setInterval(fechaYHora, 1000);
        setInterval(comprobarTiempo, 1000);
        setInterval(actualizar, 1000*60*10);
        $("#menuIzq").trigger('widthChange');
        // $(window).blur(function() {
        //     tiempoFuera("");
        // });
        // $(window).focus(function() {
        //     tiempoFuera("volver")
        // });
        setInterval(actualizarSur('general', usu, pwd, null), 20000);
        pantalla();
    }
</script>

<?= $this->endSection() ?>