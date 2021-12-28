<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<link rel="stylesheet" type="text/css" href="css/estaciones.css">
<main id="conPrincipal" style="height: 50em; overflow-y:scroll; width:100%;border-radius:10px; margin-top:1%">
    <!---zona superior--->
    <div id="estacionSup">
        <div id="elemGraf">

        </div>

        <div id="sinopMedio">

            <div id="sinopMI">
                <h3>información</h3>
                <hr>
                <ul>
                    <li>Estación: <?php echo $nombreEstacion?></li>
                    <li>Ultima conexión: <?php echo $ultimaConex[0]['valor_date'] ?></li>
                </ul>
            </div>
            <div id="sinopMD">

                <ul>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>
                    <li>
                        <div class="widDigital">
                            indicador:
                        </div>
                    </li>

                </ul>
            </div>
        </div>

        <div id="sinopLado">
            <ul>
                <li class="widMed">
                    <div class="widMedDatos">
                        <ul>
                            <li>dato 1</li>
                            <li>dato 2</li>
                            <li>dato 3</li>
                        </ul>
                    </div>
                    <div class="widMedGraf">
                        y aquí el gráfico
                    </div>
                </li>
                <li class="widMed">
                    <div class="widMedDatos">
                        <ul>
                            <li>dato 1</li>
                            <li>dato 2</li>
                            <li>dato 3</li>
                        </ul>
                    </div>
                    <div class="widMedGraf">
                        y aquí el gráfico
                    </div>
                </li>
            </ul>
        </div>
    </div>


    <!---zona media--->
    <div id="estacionMedio">
        <div id="estMedI">
            <h3>Zona Media Izquierda</h3>
        </div>

        <div id="estMedC">
            <h3>Zona Media Centro</h3>
        </div>

        <div id="estMedD">
            <h3>Zona Media Derecha</h3>
        </div>
    </div>
<!---zona alarmas--->
<table id="alarmasSur">
</table>
</main>


<script>
    window.onload = function() {
        var estacion = <?php echo $id_estacion; ?>;
        actualizarSur('estacion',null, null, null, estacion);
        comprobarTiempo();
        setInterval(fechaYHora, 1000);
        setInterval(actualizarSur('estacion',null, null, null, estacion), 20000);
        setInterval(comprobarTiempo, 1000);
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });
    }
</script>

<?= $this->endSection() ?>