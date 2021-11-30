<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/informes.js'></script>
<link rel="stylesheet" type="text/css" href="css/informes.css">
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">

<div id="informesNorte">
    <div id="tipoInforme">
        <form>
            <input type="radio" name="tipoInforme" value="niveles" checked/>
            <label for="tipoInforme">Niveles</label><br>
            <input type="radio" name="tipoInforme" value="acumulados"/>
            <label for="tipoInforme">Acumulados</label><br>
            <input type="radio" name="tipoInforme" value="caudales"/>
            <label for="tipoInforme">Caudales</label>
        </form>

    </div>
    <div id="opcionesInforme">
        <input type="text" style="margin-left: 3%;" name="txtBusqueda"/>
        <label for="txtBusqueda"><i class="fas fa-search"></i></label>
        <button style="margin-left:3%;" id="btnBus" value="ant"><i class="fas fa-angle-double-left"></i></button>
        <button id="btnBus" value="sig"><i class="fas fa-angle-double-right"></i></button>
        <br>
        <form>
            <select style="margin-top:1.5%;margin-left:3%;width:100%" id="opcionesEstacion" name="opcionesEstacion">
                <option value="Estacion">Estacion x</option>
                <option value="Estacion">Estacion x</option>
                <option value="Estacion">Estacion x</option>
                <option value="Estacion">Estacion x</option>
                <option value="Estacion">Estacion x</option>
            </select><br>
            <input type="date" id="radioFecha" name="fechaInicio">
            <label for="fecha">Inicio</label>
            <input type="date" id="radioMotivo" name="fechaFin">
            <label for="fecha">Fin</label>
        </form>
    </div>

    <div id="controlesInforme">
        <div id="controles">
            <button id="btnInforme"  value="aplicar"name="btnControl">ver informe</button><br>
            <button id="btnInforme" onclick=limpiar() value="reset" name="btnControlReset">reset</button><br>
            <button id="btnInforme"  value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
        </div>
    </div>
</div>

<div id="informesSur">

</div>

</main>

<script>

window.onload = function () {
    setInterval(fechaYHora, 1000);
    setInterval(comprobarTiempo, 1000);
}

</script>


<?= $this->endSection() ?>