<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='../../css/echarts.js'></script>
<script src='../../css/graficas.js'></script>
<script src='../../css/html2canvas.js'></script>
<script src='../../css/html2canvas.min.js'></script>
<script src='../../css/html2canvas.esm.js'></script>
<script src="../../css/html2pdf/lib/html2pdf.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/graficas.css">
<link rel="stylesheet" type="text/css" href="../../css/sur.css">
<main id="conPrincipal" style="width:100%; border-radius:10px;">
    <div id="display">
        <div id="zonaControles">
            <div id="panelOpciones">
                <form id="formOpciones">
                    <h3>Vista Rápida</h3>
                    <h6>Mostrando:</h6>
                    <select class="controlSel" id="opcionesTag" style="transition: 0.5s;" name="opciones" onchange="aplicarOpciones()"></select>
                    <h6>Comparar con:</h6>
                    <select class="controlSel" id="compararSel" name="tipoRender" onchange="comparar()">
                        <option value="nada" selected>Nada</option>
                    </select>
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
                <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" onclick="aplicarOpciones()" name="btnControlAplicar">aplicar</button>
                <button id="btnControl" type="reset" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
                <button id="btnControl" class="btnOp" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
            </div>
        </div>
        <div id="zonaGraficos">
            <div id="grafica">
                <i class="rotante fas fa-cog"></i>
            </div>
        </div>
    </div>
    <div style='overflow:hidden'>
        <button class="btn me-2 btn-block" id="btnAlSur" title="ocultar/mostrar menú" onclick="menuSur()">☰</button>
        <table id="alarmasSur">
        </table>
    </div>
</main>
<script>
    window.onload = function() {
        pantalla();
        var usu = '<?php echo $_SESSION['nombre'] ?>';
        var pwd = '<?php echo $_SESSION['hpwd'] ?>';
        comprobarTiempo();
        iniciar();
        setInterval(actualizarSur('general', usu, pwd, null), 20000);
        setTimeout(aplicarOpciones, 1500);
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
            tagsEstacion(estacion);
        }
    }
    $(document).keypress(function(e) {
        if (e.ctrlKey && e.which == 26) { //CTRL+Z
            mostrarOpciones();
        }
    });
</script>
<?= $this->endSection() ?>