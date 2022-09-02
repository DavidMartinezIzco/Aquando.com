<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='../../css/informes.js'></script>
<script src='../../css/echarts.js'></script>
<script src='../../css/html2canvas.js'></script>
<script src='../../css/html2canvas.min.js'></script>
<script src='../../css/html2canvas.esm.js'></script>
<script src="../../css/html2pdf/lib/html2pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/informes.css">
<link rel="stylesheet" type="text/css" href="../../css/alarmas.css">
<main id="conPrincipal" style="width:100%;">

    <div id="informesNorte">
        <button id="btnMenuInformes" onclick="opciones()"><i class="fas fa-sliders-h"></i></button>

        <!--tipo de representacion--->
        <div id="tipoInforme">
            <form>
                <label><input type="radio" name="radInforme" id="radInforme" value="cau" checked /> Caudales</label><br>
                <label><input type="radio" name="radInforme" id="radInforme" value="niv" /> Niveles</label><br>
                <label><input type="radio" name="radInforme" id="radInforme" value="acu" /> Acumulados</label><br>
                <label><input type="radio" name="radInforme" id="radInforme" value="clo" /> Cloros y Turbidez</label>
            </form>

        </div>
        <!--controles de fecha busqueda y otros--->
        <div id="opcionesInforme">
            <form>
                <select style="margin-top:1.5%;margin-left:3%;width:97%; height:100%" id="opcionesEstacion" multiple
                    name="opcionesEstacion">
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
                <input type="date" id="fechaFin" name="fechaFin" value="2022-01-01">
                <label for="fecha">Desde</label>
                <input type="date" id="fechaInicio" name="fechaInicio">
                <label for="fecha">Hasta</label>
            </form>
        </div>

        <!-- botonera para los informes -->
        <div id="controlesInforme">
            <div id="controles">
                <button id="btnInforme" name="btnControl" onclick="obtenerInforme()">ver informe</button>
                <button id="btnInforme" onclick=reset() value="reset" name="btnControlReset">reset</button>
                <button id="btnInforme" value="print" onclick="imprimir()" name="btnControlPrint"><i
                        class="fas fa-print"></i></button>
                <button id="btnInforme" value="print" onclick="exportarCSV()" name="btnControlPrint"><i
                        class="fas fa-file-excel"></i></button>
            </div>
        </div>
    </div>
    <!-- zona con los informes -->
    <div id="informesSur">
        <div id="espacioInforme" style="color: black;">

        </div>
    </div>
</main>


<script>
var nomusuario = "<?php echo $_SESSION['nombre']; ?>";
window.onload = function() {
    inicioFin();
    pantalla();
    setInterval(fechaYHora, 1000);
    setInterval(comprobarTiempo, 1000);
    $(window).blur(function() {
        tiempoFuera("");
    });
    $(window).focus(function() {
        tiempoFuera("volver")
    });

}
$(document).keypress(function(e) {
    console.log(e.which);
    if (e.ctrlKey && e.which == 26) {
        opciones();
    }
});
</script>


<?= $this->endSection() ?>