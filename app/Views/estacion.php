<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='../../css/echarts.js'></script>
<script src='../../css/estaciones.js'></script>
<link rel="stylesheet" type="text/css" href="../../css/estaciones.css">
<link rel="stylesheet" type="text/css" href="../../css/sur.css">
<main id="conPrincipal" style="width:100%;border-radius:10px; margin-top:1%">
    <div id="estacionIzq">
        <div id="seccionSup">
            <div id="seccionFoto"></div>
            <div id="seccionInfo">
                <i class="fas fa-sync-alt btnOpci" id="iconoActu" onclick="updateEstacion()"></i>
                <i class="fas fa-tools btnOpci" onclick="ajustes()" style="display:none;"></i>
                <p style="font-size: 95%;"><b><?php echo $ultimaConex[0]['nombre_estacion'];  ?></b></p>
                <p style="font-size: 95%;">Última comunicación: <?php echo $ultimaConex[0]['valor_date']  ?></p>
            </div>
        </div>
        <div id="seccionInf">
        </div>
    </div>
    <div id="estacionCentro">
    </div>
    <div id="estacionDer">
    </div>
    <div id="ajustesEstacion">
        <i class="fas fa-times" id="btnAjustesCerrar" onclick="ajustes()"></i>
        <!-- <h1>Ajustes de estación <?php echo $nombreEstacion; ?></h1> -->
        <div id="cuerpoAjustes" class="cuerpoAjustes">
            <form id="formAjustes" action="javascript:void(0);">
                <div id="ajustesSeleccion">
                    <ul id="listaTags">
                    </ul>
                </div>
                <div id="ajustesDisplay">
                </div>
            </form>
        </div>
    </div>
    <table id="alarmasSur">
    </table>
</main>
<script>
    var nestacion = "<?php echo $ultimaConex[0]['nombre_estacion']; ?>";
    var estacion = <?php echo $id_estacion ?>;
    // sessionStorage.setItem('param_id',null);
    // sessionStorage.setItem('data',null);
    function updateEstacion() {
        $(document.getElementById("iconoActu")).addClass("rotante");
        sessionStorage.setItem('param_id',null);
        sessionStorage.setItem('data',null);
        actualizar(estacion);
        setTimeout(function() {
            document.getElementById("iconoActu").classList.remove("rotante");
        }, 1500);
    }
    window.onload = function() {
        actualizar(estacion);
        setInterval(updateEstacion(), 60000 * 5);
        fotoEstacion(estacion);
        comprobarTiempo();
        setInterval(fechaYHora, 1000);
        setInterval(actualizarSur('estacion', null, null, estacion), 20000);
        setInterval(comprobarTiempo, 1000);
    }
</script>
<?= $this->endSection() ?>