<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/informes.js'></script>
<script src='css/echarts.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<link rel="stylesheet" type="text/css" href="css/informes.css">
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="width:100%;padding-bottom:5%">

    <div id="informesNorte">
        <button id="btnMenuInformes" onclick="opciones()"><i class="fas fa-sliders-h"></i></button>

        <!--tipo de representacion--->
        <div id="tipoInforme">
            <form>
                <input type="radio" id="radInforme" value="cau" checked />Caudales<br>
                <!-- <label for="tipoInforme">Caudales</label><br> -->
                <input type="radio" id="radInforme" value="niv" />Niveles<br>
                <!-- <label for="tipoInforme">Niveles</label><br> -->
                <input type="radio" id="radInforme" value="acu" />Acumulados
                <!-- <label for="tipoInforme">Acumulados</label> -->
            </form>

        </div>
        <!--controles de fecha busqueda y otros--->
        <div id="opcionesInforme">
            <form>
                <select style="margin-top:1.5%;margin-left:3%;width:97%; height:100%" id="opcionesEstacion" multiple name="opcionesEstacion">
                    <?php
                    foreach ($_SESSION['estaciones'] as $index => $estacion) {
                        echo '<option id=est' . $estacion['id_estacion'] . ' value=' . $estacion['id_estacion'] . ' name="' . $estacion['nombre_estacion'] . '" >' . $estacion['nombre_estacion'] . '</option>';
                    }
                    ?>

                </select><br>

            </form>
        </div>
        <!--campos posibles para representar--->
        <div id="camposInforme">
            <form>
                <input type="date" id="radioFecha" name="fechaInicio">
                <label for="fecha">Inicio</label>
                <input type="date" id="radioMotivo" name="fechaFin" value="2022-01-01">
                <label for="fecha">Fin</label>
            </form>
        </div>


        <div id="controlesInforme">
            <div id="controles">
                <button id="btnInforme" name="btnControl" onclick="obtenerInforme()">ver informe</button><br>
                <button id="btnInforme" onclick=limpiar() value="reset" name="btnControlReset">reset</button><br>
                <button id="btnInforme" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
            </div>
        </div>
    </div>

    <div id="informesSur">
        <div id="espacioInforme">

        </div>
    </div>
</main>

<script>
    window.onload = function() {
        inicioFin();
        setInterval(fechaYHora, 1000);
        setInterval(comprobarTiempo, 1000);
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });



    }

    // $(window).keydown(function(e) {
    //     if (e.ctrlKey)
    //         opciones();
    // });
</script>


<?= $this->endSection() ?>