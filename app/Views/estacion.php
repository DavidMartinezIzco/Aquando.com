<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/estaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/estaciones.css">
<main id="conPrincipal" style="height: 50em; width:100%;border-radius:10px; margin-top:1%">
    <!---zona superior--->

    <div id="estacionIzq">
        <div id="seccionSup">
            <div id="seccionFoto"></div>
            <div id="seccionInfo">
                <i class="fas fa-sync-alt btnOpci" id="iconoActu" onclick="updateEstacion()"></i>
                <i class="fas fa-tools btnOpci" onclick="ajustes()"></i>
                <p style="font-size: 80%;"><?php echo $ultimaConex[0]['nombre_estacion'];  ?></p>
                <p style="font-size: 80%;">Ultima comunicación: <?php echo $ultimaConex[0]['valor_date']  ?></p>
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
        <h1 >Ajustes de estación <?php echo $nombreEstacion; ?></h1>
        <div id="cuerpoAjustes" class="cuerpoAjustes">

            <form id="formAjustes">
                <div id="ajustesSeleccion">
                    <ul id="listaTags">

                    </ul>
                </div>
                <div id="ajustesDisplay">

                </div>



            </form>

        </div>
    </div>

    <!---zona alarmas--->

    <table id="alarmasSur">

    </table>


</main>


<script>
    var estacion = <?php echo $id_estacion ?>;
    window.onload = function() {
        updateEstacion();
        actualizarSur('estacion', null, null, null, estacion);
        comprobarTiempo();
        setInterval(fechaYHora, 1000);
        setInterval(updateEstacion, 60000);
        setInterval(actualizarSur('estacion', null, null, null, estacion), 20000);
        setInterval(comprobarTiempo, 1000);
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });
    }

    function updateEstacion() {
        $(document.getElementById("iconoActu")).addClass("rotante");
        actualizar(estacion);
        setTimeout(function() {
            document.getElementById("iconoActu").classList.remove("rotante");
        }, 3000);
    }
</script>

<?= $this->endSection() ?>