<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/graficasCustom.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<link rel="stylesheet" type="text/css" href="css/graficasCustom.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">

    <!--necesitamos nombre de estacion, nombre de tags, datos de tags-->

    <div id="display">
        <div id="zonaControles">
            <div id="panelOpciones">

                <form id="formOpciones">
                    <h3 onclick="aplicarCustom()">Vista Personalizada</h3>

                    <!--selector de tag/tags-->
                    <h6>Mostrar:</h6>
                    <ul  class='listaGrafCustom' id="opcionesTag"  name="opcionesTag" onchange=""></ul>

                    <!--opciones de metadatos-->
                    <h6>Opciones:</h6>
                    <ul id="conMetaTag" class='listaGrafCustom'>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMax" value="maxGen">
                            <label for="checkMax">Máximos generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMax" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMin" value="minGen">
                            <label for="checkMin">Mínimos generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMin" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkAvg" value="avgGen">
                            <label for="checkAvg">Medias generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorAvg" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMaxInt" value="maxInt">
                            <label for="checkMaxInt">Máximos Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMaxInt" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMinInt" value="minInt">
                            <label for="checkMinInt">Mínimos Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMinInt" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkAvgInt" value="avgInt">
                            <label for="checkAvgInt">Medias Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorAvgInt" style="visibility:hidden" title="color" list="coloresMetaGraf"></label>
                        </li>
                    </ul>
                    
                    <!--lista de colores-->
                    <datalist id="coloresTagGraf">
                        <option value="#2f4b7c">
                        <option value="#5e508f">
                        <option value="#905196">
                        <option value="#c0508f">
                        <option value="#e7537c">
                        <option value="#ff6460">
                        <option value="#ff823d">
                        <option value="#ffa600">
                        <option value="#8ac900">
                        <option value="#fd385e">
                        <option value="#ec432b">
                        <option value="#ed6227">
                    </datalist>
                    <datalist id="coloresMetaGraf">
                        <option value="ff5400">
                        <option value="ef476f">
                        <option value="ff6d00">
                        <option value="ff8500">
                        <option value="118ab2">
                        <option value="ff9100">
                        <option value="073b4c">
                        <option value="ff9e00">
                        <option value="00b4d8">
                        <option value="0096c7">
                        <option value="ffd166">
                        <option value="0077b6">
                        <option value="023e8a">
                    </datalist>



                    <!-- rango de fechas-->
                    <h6>Rango:</h6>
                    <input type="date" id="fechaInicio" style="transition: 0.5s;" name="fechaInicio">
                    <label for="fecha">Hasta</label><br>
                    <input type="date" id="fechaFin" style="transition: 0.5s;" name="fechaFin">
                    <label for="fecha">Desde</label>
                    <hr>

                    <label for="opciones">Estación:</label>
                    <select class="controlSel" id="opciones" style="transition: 0.5s;" name="opciones" onchange="iniciar(this.value)">
                        <?php
                        $i = 1;
                        foreach ($_SESSION['estaciones'] as $index => $value) {
                            echo "<option value=" . $value['id_estacion'] . ">" . $value['nombre_estacion'] . "</option>";
                            $i++;
                        }
                        ?>
                    </select>
                    
                </form>
                

                <!--controles-->
                <!-- <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" onclick="aplicarOpciones()" name="btnControlAplicar">aplicar</button>
                <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
                <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button> -->
            </div>
        </div>

        <!--espacio para las graficas--->
        <div id="zonaGraficos">
            <div id="grafica" style="width: 100%; height: 100%; border-radius:10px;">
                
            </div>
        </div>
    </div>
    <!---alarmas--->
    <table id="alarmasSur">
    </table>
</main>


<script>
    pantalla();
    //Falta de momento: actualizar los controles, control de colores en las series
    window.onload = function() {
        iniciar();
        
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        var pwd = '<?php echo $_SESSION['pwd'] ?>';
        var idusu = <?php echo $_SESSION['idusu'] ?>;
        //handlers para los controles de tags , meta y colores
        $(document).on('change', 'input[type=color]', function() {
            this.parentNode.style.color = this.value;
        });
        $(document).on('change', 'input[type=checkbox]', function() {
            if (this.parentNode.style.backgroundColor == 'darkgray') {
                this.parentNode.style.backgroundColor = 'lightgray';
            } else {
                this.parentNode.style.backgroundColor = 'darkgray';
            }
        });
        //config para el controlador de fechas
        Date.prototype.toDateInputValue = (function() {
            var local = new Date(this);
            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
            return local.toJSON().slice(0,10);
        });
        $(document).ready( function() {
            $('#fechaInicio').val(new Date().toDateInputValue());
        });
        $(document).ready( function() {
            $('#fechaFin').val(new Date().toDateInputValue());
        });

        comprobarTiempo();
        
        setInterval(actualizarSur('general', usu, pwd, idusu, null), 20000);
        //setTimeout(aplicarOpciones, 1500);
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
        if (document.getElementById("opciones")) {
            var estacion = document.getElementById("opciones").value;
            tagsEstacionCustom(estacion);
        }
    }
    //muestra u oculta las opciones y controles sin readaptar nada
    $(window).keydown(function(e) {
        if (e.ctrlKey)
            mostrarOpciones();
    });
</script>


<?= $this->endSection() ?>