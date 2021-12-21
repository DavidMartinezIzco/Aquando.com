<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.js'></script>
<script src='css/alarmas.js'></script>
<script src='css/html2canvas.js'></script>
<script src='css/html2canvas.min.js'></script>
<script src='css/html2canvas.esm.js'></script>
<script src="css/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="height: 53em; width:100%; border-radius:10px;">

    <!--opciones de filtrado de las alarmas--->
    <div id="zonaOpciones">
        <div id="filtros">
            <select class="controlSel" id="estaciones" name="estacionSel">
                <option value="all">Todas</option>
                <?php 
                    foreach($estaciones as $index=>$estacion){
                        echo "<option value".$estacion['id_estacion'].">".$estacion['nombre_estacion']."</option>";
                    }                   
                ?>
            </select>
        </div>

        <div id="filtros2">
            <input type="radio" id="radioFecha" name="filtro" value="Fecha" checked>
            <label for="radioFecha">Fecha</label>
            <input type="radio" id="radioMotivo" name="filtro" value="Motivo">
            <label for="radioMotivo">Importancia</label>
            <input type="radio" id="radioCanal" name="filtro" value="Canal">
            <label for="radioCanal">Canal</label>
            <input type="radio" id="radioEstacion" name="filtro" value="Estacion">
            <label for="radioEstacion">Estación</label>
        </div>

        <div id="orden">
            <input type="radio" id="radioAsc" name="orden" value="ASC">
            <label for="orden">Ascendente</label>
            <br>
            <input type="radio" id="radioDesc" name="orden" value="DESC" checked>
            <label for="filtro">Descendiente</label>
        </div>

        <div id="fechas">

            <input type="date" id="radioFecha" name="fechaInicio">
            <label for="fecha">Inicio</label><br>
            <input type="date" id="radioMotivo" name="fechaFin">
            <label for="fecha">Fin</label>
        </div>

        <div id="acciones">
            <button id="btnControl" style="background-color: yellowgreen;" value="aplicar" onclick=actualizar()
                name="btnControl">aplicar</button>
            <button id="btnControl" onclick=limpiar() style="background-color: tomato;" value="reset"
                name="btnControlReset">reset</button>
            <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()"
                name="btnControlPrint"><i class="fas fa-print"></i></button>
        </div>

    </div>
    <!--zona para representar las alarmas--->
    <div id="zonaAlarmas">

        <table id="tablaAlarmas">
            <tr>
                
                <th>Estación</th>
                <th>Fecha de Origen</th>
                <th >Fecha de Restauracion</th>
                <th>Estado</th>
                <th>Reconocida por</th>
                <th>Info</th>
            </tr>
            <?php
                $i = 0;
                $e = 0;
                    foreach ($alarmasAll as $index => $alarma) {
                        echo "<tr id=$e>";
                        foreach ($alarma as $dato => $valor) {
                            if($dato == 'estado'){
                                switch ($valor) {
                                    case 1:
                                        echo "<script>document.getElementById($e).className ='activaNo'</script>";
                                        echo "<td>";
                                        echo "ACTIVA NO RECONOCIDA";
                                        echo "</td>";

                                        break;
                                    case 2:
                                        echo "<script>document.getElementById($e).className='restNo'</script>";
                                        echo "<td>";
                                        echo "RESTAURADA NO RECONOCIDA";
                                        echo "</td>";
                                        break;
                                    case 3:
                                        echo "<script>document.getElementById($e).className ='activaSi'</script>";
                                        echo "<td>";
                                        echo "ACTIVA RECONOCIDA";
                                        echo "</td>";
                                        break;
                                    case 4:
                                        echo "<script>document.getElementById($e).className ='restSi'</script>";
                                        echo "<td>";
                                        echo "RESTAURADA RECONOCIDA";
                                        echo "</td>";
                                        break;

                                    default:
                                    echo "<td>";
                                    echo "ESTADO DESCONOCIDO";
                                    echo "</td>";
                                        break;
                                }
                            }else {
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                            }
                        }
                        echo "</tr>";
                        $e++;
                    }
            ?>

        </table>
    </div>

</main>

<script>
window.onload = function() {


    var nousu="<?php echo $_SESSION['nombre']?>";
    sessionStorage.setItem('nousu',nousu);
    var psusu="<?php echo $_SESSION['pwd']?>";
    sessionStorage.setItem('pwd',psusu);
    var empusu = "<?php echo $_SESSION['idusu']?>";
    sessionStorage.setItem('empusu',empusu);
    setInterval(fechaYHora, 1000);
    setInterval(actualizar, 10000);
    setInterval(comprobarTiempo, 1000);
    $(window).blur(function() {
        tiempoFuera("");
    });
    $(window).focus(function() {
        tiempoFuera("volver")
    });
}


//muestra / oculta las opciones
$(window).keydown(function(e) {
    if (e.ctrlKey)
        opciones();
});
</script>

<?= $this->endSection() ?>