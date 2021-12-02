<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/graficas.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<link rel="stylesheet" type="text/css" href="css/graficas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">



    <div id="display">
        <div id="zonaControles">

            <div id="panelInfo">
                <h3>info</h3>
            </div>

            <div id="panelOpciones">
                <form>
                    <!--datos a mostrar-->
                        <fieldset>
                            <input type="checkbox" name="1" checked>
                            <label for="1">Dato 1</label>
                            
                            <input type="checkbox" name="2"checked>
                            <label for="2">Dato 2</label>
                            
                            <input type="checkbox" name="3"checked>
                            <label for="3">Dato 3</label>
                            <br>
                            <input type="checkbox" name="4">
                            <label for="4">Dato 4</label>
                            
                            <input type="checkbox" name="5">
                            <label for="5">Dato 5</label>
                            
                            <input type="checkbox" name="6">
                            <label for="6">Dato 6</label>
                            <br>
                            <input type="checkbox" name="7">
                            <label for="7">Dato 7</label>
                            
                            <input type="checkbox" name="8">
                            <label for="8">Dato 8</label>
                            
                            <input type="checkbox" name="9">
                            <label for="9">Dato 9</label>
                            <br>
                        </fieldset>
                    <hr>
                    <!--tipo de representacion-->
                        <fieldset>
                            <input type="radio" name="g2" id="tipoLinea" value="linea" checked>
                            <label for="3">Líneas</label>
                            <input type="radio" name="g2" id="tipoBarra" value="barra">
                            <label for="3">Barras</label>
                            <input type="radio" name="g2" id="tipoGraf" value="6" disabled>
                            <label for="6">opcion 6</label>
                        </fieldset>
                    <hr>
                    <!--rango de fechas-->
                        <input type="date" id="dateFecha" name="fechaInicio" disabled>
                        <label for="fecha">Inicio</label>
                        <input type="date" id="dateFecha" name="fechaFin" disabled>
                        <label for="fecha">Fin</label>
                    <hr>
                    <!--presets/tipos de repren-->
                    <label for="opciones">mas opciones:</label>
                        <select class="controlSel" id="opciones" disabled name="opciones">
                            <option value="p1">Preset 1</option>
                            <option value="p2">Preset 2</option>
                            <option value="p3">Preset 3</option>
                        </select>
                    
                </form>

                    <!--controles-->                
                    <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" onclick="aplicarOpciones()" name="btnControlAplicar">aplicar</button>
                    <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
                    <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
                
            </div>

        </div>

        <div id="zonaGraficos">
            <div id="grafica" style="width: 100%; height: 100%; border-radius:10px">
            </div>
        </div>
    </div>

</main>
    <!---alarmas--->
    <table id="alarmasSur">


    </table>

<script>
    window.onload = function () {
        actualizarMini();
        aplicarOpciones();
        setInterval(fechaYHora, 1000);
        setInterval(actualizarMini, 3000);
        setInterval(comprobarTiempo, 1000);
    }

    $(window).keydown(function(e){
    if (e.ctrlKey)
        mostrarOpciones();
    });


</script>


<?= $this->endSection() ?>