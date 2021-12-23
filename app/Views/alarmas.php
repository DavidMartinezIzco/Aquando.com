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
            <select class="controlSel" id="estaciones" name="estacionSel" onchange="filtrarPorEstacion()">
                <option value="all" >Todas las estaciones</option>
                <?php 
                    foreach($estaciones as $index=>$estacion){
                        echo "<option value=".$estacion['id_estacion'].">".$estacion['nombre_estacion']."</option>";
                    }                   
                ?>
            </select>
        </div>

        <div id="filtros2">
            <input type="radio" id="radioFecha" name="filtro" value="Fecha" checked>
            <label for="radioFecha">Fecha</label>
            <input type="radio" id="radioMotivo" name="filtro" value="estado">
            <label for="radioMotivo">Importancia</label>
            <input type="radio" id="radioRest" name="filtro" value="restauracion">
            <label for="radioRest">Restauracion</label>
            <input type="radio" id="radioEstacion" name="filtro" value="estacion">
            <label for="radioEstacion">Estación</label>
        </div>

        <div id="orden">
            <input type="radio" id="radioAsc" name="orden" value="ASC">
            <label for="radioAsc">Ascendente</label>
            <br>
            <input type="radio" id="radioDesc" name="orden" value="DESC" checked>
            <label for="radioDesc">Descendiente</label>
        </div>

        <div id="fechas">

            <input type="date" id="radioFecha" disabled name="fechaInicio">
            <label for="radioFecha">Inicio</label><br>
            <input type="date" id="radioMotivo" disabled name="fechaFin">
            <label for="radioMotivo">Fin</label>
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
                <th onclick="reordenar('estacion')">Estación</th>
                <th onclick="reordenar('senal')">Señal </th>
                <th onclick="reordenar('valor')">Valor de la señal</th>
                <th onclick="reordenar('origenfecha')">Fecha de Origen</th>
                <th onclick="reordenar('restauracionfecha')">Fecha de Restauracion</th>
                <th onclick="reordenar('reconusu')">Reconocida por</th>
                <th onclick="reordenar('reconfecha')">Fecha de reconocimiento</th>
            </tr>
            <?php
        foreach ($alarmasAll as $index => $alarma) {
            switch ($alarma['estado']) {
                case 1:
                    echo "<tr class='activaNo' >";
                    
                    break;
                case 2:
                    echo "<tr class='restNo'>";
                    
                    break;
                case 3:
                    echo "<tr class='activaSi'>";
                    
                    break;
                case 4:
                    echo "<tr class='restSi'>";
                    break;

                default:
                    break;
            }

            foreach ($alarma as $dato => $valor) {
                if($dato != 'estado' && $dato != 'id_alarmas'){
                    if($dato == 'ack_por'){
                        if($valor == null){
                            echo "<td>";
                            echo '<i class="fas fa-eye" onclick="reconocer('.$alarma['id_alarmas'].')"></i>';
                            echo "</td>"; 
                        }
                        else {
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                        }
                    }
                    else {
                        echo "<td>";
                        echo $valor;
                        echo "</td>";
                    }
                    
                }
            }
            echo "</tr>";
        }
            ?>

        </table>
    </div>

</main>

<script>
window.onload = function() {
    var usu = '<?php echo $_SESSION['nombre'] ?>';
    var pwd = '<?php echo $_SESSION['pwd'] ?>';
    var idusu = <?php echo $_SESSION['idusu']?>;
    sessionStorage.setItem('nousu',usu);
    sessionStorage.setItem('pwd', pwd);
    sessionStorage.setItem('emp', idusu);

    setInterval(fechaYHora, 1000);
    setInterval(actualizar(null), 20000);
    setInterval(comprobarTiempo, 1000);
    setInterval(efectoAlerta, 3000);
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