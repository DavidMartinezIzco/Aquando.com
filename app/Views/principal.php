<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<link rel="stylesheet" type="text/css" href="css/sur.css">
<script src="css/principal.js"></script>
<script src="css/reloj.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

<main id="conPrincipal" style="height:848px;width:100%;border-radius:10px; margin-top:1%">
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
                <div style="border: 2px dashed rgb(1, 168, 184) ;" class="digiIzq"></div>
                <div style="border: 2px dashed rgb(1, 168, 184);" class="digiDer"></div>
            </div>
            <div id="widInf">
                <div style="border: 2px dashed rgb(1, 168, 184);" class="digiIzq"></div>
                <div style="border: 2px dashed rgb(1, 168, 184);" class="digiDer"></div>
            </div>
        </div>
    </div>
    <!-- ZONA DERECHA -->
    <div id="prinDer">
        <div id="widSup">
            <div style="border: 4px dashed rgb(1, 168, 184);" class="digiIzq"></div>
            <div style="border: 4px dashed rgb(1, 168, 184);" class="digiDer"></div>
        </div>
        <div id="widInf">
            <div style="border: 4px dashed rgb(1, 168, 184);" class="digiIzq"></div>
            <div style="border: 4px dashed rgb(1, 168, 184);" class="digiDer"></div>
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
    var estacionesUsu = <?php echo json_encode($estaciones); ?>;
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
        setInterval(actualizar, 1000 * 60 * 10);
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