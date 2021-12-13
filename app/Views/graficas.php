<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src ='css/echarts.js'></script>
<script src='css/graficas.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<link rel="stylesheet" type="text/css" href="css/graficas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">



    <div id="display">
        <div id="zonaControles">

            <div id="panelInfo" style="color: black;padding: 1% 2%">
                <h3>Mostrando:</h3>
                <p id="infoGraf"></p>
            </div>

            <div id="panelOpciones">
                <form>
                    <!--datos a mostrar-->
                    <fieldset id="infoRepren" style="transition: 0.5s">
                    <?php
                    $i = 1;
                        foreach ($datosF as $key => $value) {
                            $clave = substr($key, strpos($key, " ")+1,10 );
                            echo "<input type='checkbox' style='margin:0% 2%' name='$clave' value='$clave' checked>";
                            echo "<label for='$clave'>Info: $clave</label>";
                            if($i == 3 || $i == 6){
                                echo "<br>";
                            }
                            $i++;
                        }
                    ?>
                    </fieldset>
                    <hr>
                        
                            <!-- <input type="checkbox" name="1" value=1 checked>
                            <label for="1">Dato 1</label>
                            
                            <input type="checkbox" name="2" value=2 checked>
                            <label for="2">Dato 2</label>
                            
                            <input type="checkbox" name="3" value=3 checked>
                            <label for="3">Dato 3</label>
                            <br>
                            <input type="checkbox" name="4" value=4>
                            <label for="4">Dato 4</label>
                            
                            <input type="checkbox" name="5" value=5>
                            <label for="5">Dato 5</label>
                            
                            <input type="checkbox" name="6" value=6>
                            <label for="6">Dato 6</label>
                            <br>
                            <input type="checkbox" name="7" value=7>
                            <label for="7">Dato 7</label>
                            
                            <input type="checkbox" name="8" value=8>
                            <label for="8">Dato 8</label>
                            
                            <input type="checkbox" name="9" value=9>
                            <label for="9">Dato 9</label>
                            <br> -->
                        
                    <!--tipo de representacion-->
                    <h6>Tipo de representacion:</h6>
                    <select class="controlSel" id="tipoRender" name="tipoRender" onchange="alternarOpciones(this.value)">
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
                        <select class="controlSel" id="opciones" style="transition: 0.5s;" name="opciones">
                            <option value="e1">Estación 1</option>
                            <option value="e2">Estación 2</option>
                            <option value="e3">Estación 3</option>
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
                </div>
            </div>
    </div>
    
</main>
    <!---alarmas--->
        <table id="alarmasSur">
        </table>

<script>
    window.onload = function () {
        var datos = [];
        iniciar();
        actualizarMini();
        aplicarOpciones();
        alternarOpciones(document.getElementById("tipoRender").value);
        setInterval(fechaYHora, 1000);
        setInterval(actualizarMini, 3000);
        setInterval(comprobarTiempo, 1000);
    }

    function iniciar() {
    <?php

        //carga de datos general
        echo "datos = {"; 

            foreach ($datosF as $index => $datos) {
                echo "'". $index ."':[";
                foreach ($datos as $nombre => $valores) {
                    
                    echo $valores.",";
                    
                }
                echo "],";
            }

        echo "};"

    ?>
    }
    
    

    $(window).keydown(function(e){
    if (e.ctrlKey)
        mostrarOpciones();
    });


</script>


<?= $this->endSection() ?>