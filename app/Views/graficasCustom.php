<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='../../css/echarts.js'></script>
<script src='../../css/graficasCustom.js'></script>
<script src='../../css/html2canvas.js'></script>
<script src='../../css/html2canvas.min.js'></script>
<script src='../../css/html2canvas.esm.js'></script>
<script src="../../css/html2pdf/lib/html2pdf.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/graficasCustom.css">
<link rel="stylesheet" type="text/css" href="../../css/sur.css">
<main id="conPrincipal" style="width:100%; border-radius:10px;">
    <div id="display">
        <div id="zonaControles">
            <div id="panelOpciones">
                <form action="javascript:void(0);" id="formOpciones">
                    <h3>Vista Personalizada</h3>
                    <select id="selPresets" onchange='ajustesPresets("cargar")'>Predefinidos:</select>
                    <h6>Mostrar:</h6>
                    <ul class='listaGrafCustom' id="opcionesTag" name="opcionesTag" onchange=""></ul>
                    <h6>Opciones:</h6>
                    <ul id="conMetaTag" class='listaGrafCustom'>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMax" value="maxGen">
                            <label for="checkMax">Máximos generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMax" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMin" value="minGen">
                            <label for="checkMin">Mínimos generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMin" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkAvg" value="avgGen">
                            <label for="checkAvg">Medias generales</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorAvg" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMaxInt" value="maxInt">
                            <label for="checkMaxInt">Máximos Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMaxInt" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkMinInt" value="minInt">
                            <label for="checkMinInt">Mínimos Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorMinInt" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                        <li>
                            <input type="checkbox" style="visibility: hidden;" name="checkMeta" id="checkAvgInt" value="avgInt">
                            <label for="checkAvgInt">Medias Intervalo</label>
                            <label><i class="fas fa-palette"></i><input type="color" class="form-control-color" id="colorAvgInt" style="visibility:hidden" title="color" name="colorDato" list="coloresMetaGraf"></label>
                        </li>
                    </ul>
                    <datalist id="coloresTagGraf">
                        <option value="#01a9b8"></option>
                        <option value="#272d4f"></option>
                        <option value="#2f4b7c"></option>
                        <option value="#5e508f"></option>
                        <option value="#905196"></option>
                        <option value="#c0508f"></option>
                        <option value="#e7537c"></option>
                        <option value="#ff6460"></option>
                        <option value="#ff823d"></option>
                        <option value="#ffa600"></option>
                        <option value="#8ac900"></option>
                        <option value="#fd385e"></option>
                        <option value="#ec432b"></option>
                        <option value="#ed6227"></option>
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

                    <label for="fecha">Desde</label>
                    <input type="date" id="fechaFin" style="transition: 0.5s;" name="fechaFin" value="2021-12-01">
                    <label for="fecha">Hasta</label>
                    <input type="date" id="fechaInicio" style="transition: 0.5s;" name="fechaInicio">
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
                    <button id="btnControlCustom" style="background-color: yellowgreen;border: 2px solid green;" value="aplicar" onclick="aplicarCustom()" name="btnControlAplicar">aplicar</button>
                    <button id="btnControlCustom" type="reset" onclick=limpiar() style="background-color: tomato;border: 2px solid red;" value="reset" name="btnControlReset">reset</button>
                    <button id="btnControlCustom" class="btnOp" style="background-color: darkseagreen;border: 2px solid rgb(0, 128, 58);" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
                    <button id="btnControlCustom" class="btnOp" style="background-color: rgb(1, 168, 184);border: 2px solid rgb(39, 45, 79);" value="guardar" onclick="ajustesPresets('guardar')" name="btnControlPrint"><i class="fas fa-save"></i></button>
                </form>
            </div>
        </div>
        <div id="zonaGraficos">
            <div id="grafica">
            </div>
        </div>
        <div id="ajustesPresets">
        </div>
    </div>
    <div style='overflow:hidden'>
        <button class="btn me-2 btn-block" id="btnAlSur" title="ocultar/mostrar menú" onclick="menuSur()">☰</button>
        <table id="alarmasSur">
        </table>
    </div>
</main>
<script>
    var chartDom = document.getElementById('grafica');
    var graficoCustom = echarts.init(chartDom);
    var usu = '<?php echo $_SESSION['nombre'] ?>';
    var pwd = '<?php echo $_SESSION['hpwd'] ?>';
    window.onload = function() {
        iniciar();
        pantalla();
        inicioFin();
        mostrarPresets();
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
        comprobarTiempo();
        setInterval(actualizarSur('general', usu, pwd, null), 20000);
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
            nombre_estacion_activa = document.getElementById("opciones").options[document.getElementById("opciones")
                .selectedIndex].text;
            tagsEstacionCustom(estacion);
        }
    }
    $(document).keypress(function(e) {
        if (e.ctrlKey && e.which == 26) {
            mostrarOpciones();
        }
    });
</script>
<?= $this->endSection() ?>