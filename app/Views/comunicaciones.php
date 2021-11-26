<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/comunicaciones.js'></script>
<link rel="stylesheet" type="text/css" href="css/comunicaciones.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px; margin-top:1%; padding: 0.5%">

    <div id="display">

        <div id="seccionEstacion">
            <div id="secNombre">
                <h6>N Estacion</h6>
            </div>

            <div id="secEstado">
            <i class="fas fa-check"></i>
            </div>

            <div id="secUltima">
                <p>Ultima Conexion</p>
            </div>

        </div>

        <div id="seccionEstacion">
            <div id="secNombre">
                <h6>N Estacion</h6>
            </div>

            <div id="secEstado">
            <i class="fas fa-check"></i>
            </div>

            <div id="secUltima">
                <p>Ultima Conexion</p>
            </div>

        </div>

        <div id="seccionEstacionProblema" >
            <div id="secNombre">
                <h6>N Estacion</h6>
            </div>

            <div id="secProblema">
            <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div id="secUltima">
                <p>Ultima Conexion</p>
            </div>

        </div>

        <div id="seccionEstacion">
            <div id="secNombre">
                <h6>N Estacion</h6>
            </div>

            <div id="secEstado">
            <i class="fas fa-check"></i>
            </div>

            <div id="secUltima">
                <p>Ultima Conexion</p>
            </div>

        </div>

        <div id="seccionEstacionError">
            <div id="secNombre">
                <h6>N Estacion</h6>
            </div>

            <div id="secError">
            <i class="fas fa-times-circle"></i>

            </div>

            <div id="secUltima">
                <p>Ultima Conexion</p>
            </div>

        </div>

    </div>

</main>

<script>

window.onload = function () {
    setInterval(fechaYHora, 1000);
    setInterval(comprobarTiempo, 1000);
    setInterval(parpadeoProblema, 1000);
    setInterval(parpadeoError, 400);
}

</script>


<?= $this->endSection() ?>