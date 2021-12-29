<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/graficas.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<link rel="stylesheet" type="text/css" href="css/graficas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">

<!--necesitamos nombre de estacion, nombre de tags, datos de tags-->

    <div id="display">
        <div id="zonaControles">
            <!--zona para mostrar informacion de render-->
            <div id="panelInfo" style="color: black;padding: 1% 2%">
                <h3>Mostrando:</h3>
                <select class="controlSel" id="opcionesTag" style="transition: 0.5s;" name="opciones" onchange="aplicarOpciones()">
                </select>
                <hr>
            </div>

            <div id="panelOpciones">
                <form>
                    <!--tipo de representacion-->
                    <h6>Tipo de representacion:</h6>
                    <select class="controlSel" id="tipoRender" name="tipoRender" disabled onchange=alternarOpciones(this.value)>
                        <option value="histo" selected>Historico</option>
                        <option value="linea">Lineas</option>
                        <option value="barra">Barras</option>
                        <option value="tarta">Tarta</option>
                    </select>
                    <hr>
                    <!--rango de fechas-->
                    <input type="date" id="fechaInicio" style="transition: 0.5s;" name="fechaInicio">
                    <label for="fecha">Inicio</label>
                    <input type="date" id="fechaFin" style="transition: 0.5s;" name="fechaFin">
                    <label for="fecha">Fin</label>
                    <hr>
                    <!--presets/tipos de repren-->
                    <label for="opciones">Estación:</label>
                    <select class="controlSel" id="opciones" style="transition: 0.5s;" name="opciones" onchange="iniciar(this.value)">
                            <?php 
                                $i = 1;
                                foreach ($_SESSION['estaciones'] as $index => $value) {
                                    echo "<option value=".$value['id_estacion'].">".$value['nombre_estacion']."</option>";
                                    $i++;
                                }
                                
                            ?>

                    </select>

                </form>

                <!--controles-->
                <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" onclick="aplicarOpciones()" name="btnControlAplicar">aplicar</button>
                <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
                <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <!--espacio para las graficas--->
        <div id="zonaGraficos">
            <div id="grafica" style="width: 100%; height: 100%; border-radius:10px;">
            <i class="rotante fas fa-cog"></i>
            </div>
        </div>
    </div>
<!---alarmas--->
<table id="alarmasSur">
</table>
</main>


<script>
    window.onload = function() {
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        var idusu = <?php echo $_SESSION['idusu']?>;
        

        comprobarTiempo();
        iniciar();
        setInterval(actualizarSur('general',usu, pwd, idusu, null),20000);
        setTimeout(aplicarOpciones,1500);
        //alternarOpciones(document.getElementById("tipoRender").value);
        setInterval(fechaYHora, 1000);
        setInterval(comprobarTiempo, 1000);
        
        $(window).blur(function() {
            tiempoFuera("");
        });
        $(window).focus(function() {
            tiempoFuera("volver")
        });
    }

    function iniciar() {
        if(document.getElementById("opciones")){
            var estacion = document.getElementById("opciones").value;
            tagsEstacion(estacion);
        }
    }

    
    $(window).keydown(function(e) {
        if (e.ctrlKey)
            mostrarOpciones();
    });
</script>


<?= $this->endSection() ?>