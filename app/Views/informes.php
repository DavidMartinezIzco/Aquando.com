<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/informes.js'></script>
<link rel="stylesheet" type="text/css" href="css/informes.css">
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">

<div id="informesNorte">
    <button id="btnMenuInformes" onclick="opciones()"><i class="fas fa-sliders-h"></i></button>
        
        <!--tipo de representacion--->
        <div id="tipoInforme">
            <form>
                <input type="radio" name="tipoInforme" value="tabla" checked/>
                <label for="tipoInforme">Tabla</label><br>
                <input type="radio" name="tipoInforme" value="grafico"/>
                <label for="tipoInforme">Gráfico</label><br>
                <input type="radio" name="tipoInforme" value="esquema"/>
                <label for="tipoInforme">Esquema</label>
            </form>

        </div>
        <!--controles de fecha busqueda y otros--->
        <div id="opcionesInforme">
            <input type="text" style="margin-left: 3%;" name="txtBusqueda"/>
            <label for="txtBusqueda"><i class="fas fa-search"></i></label>
            <button style="margin-left:3%;" id="btnBus" value="ant" disabled><i class="fas fa-angle-double-left"></i></button>
            <button id="btnBus" value="sig" disabled><i class="fas fa-angle-double-right"></i></button>
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
        <!--campos posibles para representar--->
        <div id="camposInforme">
            <form>
                <input type="radio" name="radioCampo1" value ="" checked/>
                <label for="radioCampo1">Campo 1</label>
                <input type="radio" name="radioCampo2" value ="" checked/>
                <label for="radioCampo1">Campo 2</label><br>
                <input type="radio" name="radioCampo3" value ="" checked/>
                <label for="radioCampo1">Campo 3</label>
                <input type="radio" name="radioCampo4" value ="" checked/>
                <label for="radioCampo1">Campo 4</label><br>
                <input type="radio" name="radioCampo5" value ="" checked/>
                <label for="radioCampo1">Campo 5</label>
                <input type="radio" name="radioCampo6" value ="" checked/>
                <label for="radioCampo1">Campo 6</label>
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
    <div id="espacioInforme">

    </div>
</div>
</main>

<script>

window.onload = function () {
    setInterval(fechaYHora, 1000);
    setInterval(comprobarTiempo, 1000);
}

$(window).keydown(function(e){
    if (e.ctrlKey)
        opciones();
    });

</script>


<?= $this->endSection() ?>