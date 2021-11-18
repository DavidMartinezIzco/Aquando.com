<?= $this->extend('inicio') ?>
<?= $this->section('content') ?>
<script src='css/echarts.min.js'></script>
<script src='css/alarmas.js'></script>
<link rel="stylesheet" type="text/css" href="css/alarmas.css">
<main id="conPrincipal" style="margin-left:2.5%; height: 53em; background-color: rgb(56, 56, 56); width:100%; border-radius:10px; margin-top:1%; padding: 0.5%">


<div id="zonaOpciones">
    <div id="filtros">
    <label for="estacionSel">Estación:</label>
                    <select class="controlSel" id="estaciones" name="estacionSel">
                    <option value="all">Todas</option>
                        <?php 
                        $estaciones = $_SESSION['estaciones'];
                        if(isset($_SESSION['estacion'])){
                            $estacionVieja = $_SESSION['estacion'];
                        }
                        else {
                            $estacionVieja = null;
                        }
                            foreach($estaciones as $index => $estacion){
                                if ($index != 0) {
                                    if ($estacion == $estacionVieja) {
                                        echo "<option value='".$estacion."' selected>".$estacion."</option>";
                                    }
                                    else {
                                        echo "<option  value='".$estacion."'>".$estacion."</option>";
                                    }
                                }
                            }                 
                        ?>
                    </select>
    </div>

    <div id="filtros2">
        <input type="radio" id="radioFecha" name="filtro" value="Fecha" checked>
            <label for="filtro">Fecha</label>
        <input type="radio" id="radioMotivo" name="filtro" value="Motivo">
            <label for="filtro">Importancia</label>
        <input type="radio" id="radioCanal" name="filtro" value="Canal">
            <label for="filtro">Canal</label>
        <input type="radio" id="radioEstacion" name="filtro" value="Estacion">
            <label for="filtro">Estación</label>
    </div>
    <div id="orden">
        <input type="radio" id="radioAsc" name="orden" value="ASC">
        <label for="orden">Ascendente</label>
        <br>
        <input type="radio" id="radioDesc" name="orden" value="DESC" checked>
        <label for="filtro">Descendiente</label>
    </div>

    <div id="acciones">
        <button id="btnControl" style="background-color: yellowgreen;" value="aplicar"onclick=aplicarFiltros() name="btnControl">aplicar</button>
        <button id="btnControl" onclick=limpiar() style="background-color: tomato;" value="reset" name="btnControlReset">reset</button>
        <button id="btnControl" style="background-color: darkseagreen;" value="print" onclick="imprimir()" name="btnControlPrint"><i class="fas fa-print"></i></button>
    </div>

</div>

<div id="zonaAlarmas">

    <table id="tablaAlarmas">
    
        <?php
            $estaciones = $_SESSION['estaciones'];
            $i = 0;
            $e = 0;
            if (isset($_SESSION['alarmas'])) {
                $alarmas = $_SESSION['alarmas'];
            }

            if (isset($alarmas)) {
                if(!empty($alarmas)){
                    foreach ($alarmas as $index => $alarmasDeEstacion) {
                        $e++;
                        foreach ($alarmasDeEstacion as $alarmaDeEstacion) {
                            $i++;
                            echo "<tr id='".$i."'>";
                            foreach ($alarmaDeEstacion as $dato => $info) {
                                if($dato != "Fecha" && $info != "sin datos de la consulta" && $info != "error"){
                                    if ($dato == "Dato") {
                                        $pos = strpos($info, $estaciones[$e]);
                                        $texto = substr($info, $pos+9, 100);
                                        $fechamal = substr($info, 3,12);
                                        $año = "20" . substr($fechamal, 0, 2);
                                        $mes = substr($fechamal, 2, 2);
                                        $dia = substr($fechamal, 4, 2);
                                        $hora = substr($fechamal, 6, 2);
                                        $min = substr($fechamal, 8, 2);
                                        echo "<td>";
                                        echo $dia . "/". $mes . "/" . $año . " - " . $hora . ":". $min;
                                        echo "</td>";

                                        echo "<td>";
                                        echo $texto;
                                        echo "</td>";
    
                                    }
                                    elseif ($dato == "Motivo") {
                                        switch ($info) {
                                            case 1:
                                                echo "<script>document.getElementById('".$i."').style.backgroundColor='#f09595'</script>";
                                                echo "<td>Alarma Alto</td>";
                                                break;
                                            case 2:
                                                echo "<script>document.getElementById('".$i."').style.backgroundColor='#ffac38'</script>";
                                                echo "<td>Alarma Bajo</td>";
                                                break;
                                            case 3:
                                                echo "<script>document.getElementById('".$i."').style.backgroundColor='#dfebe2'</script>";
                                                echo "<td>Normal</td>";
                                                break;
                                            default:
                                            echo "<script>document.getElementById('".$i."').style.backgroundColor='#a1fff7'</script>";
                                                break;
                                        }
                                        
                                    }

                                    else {
                                        echo "<td>";
                                        echo " ".$dato." : ".$info." ";
                                        echo "</td>";
                                    }
                                    
                                }
                                
                            }
                        echo "</tr>";
                        }

                        }
                    }
                    
                }
        ?>

    </table>
</div>

</main>

<script>

    window.onload = function () {
        setInterval(fechaYHora, 1000);
        setInterval(actualizar, 10000);

    }

function actualizar() {

if(document.getElementById("estaciones").value == 'all'){
    var estacion = 'all';
    var filtro = "";
    var orden = "";
    if (document.getElementById("radioFecha").checked) {
        filtro = document.getElementById("radioFecha").value;
    }
    if (document.getElementById("radioMotivo").checked) {
        filtro = document.getElementById("radioMotivo").value;
    }
    if (document.getElementById("radioCanal").checked) {
        filtro = document.getElementById("radioCanal").value;
    }
    if (document.getElementById("radioEstacion").checked) {
        filtro = document.getElementById("radioEstacion").value;
    }

    if (document.getElementById("radioAsc").checked) {
        orden = document.getElementById("radioAsc").value;
    }

    if (document.getElementById("radioDesc").checked) {
        orden = document.getElementById("radioDesc").value;
    }


    var acc = "<?php if(isset($_SESSION['acc'])){echo $_SESSION['acc'];}else{echo "";}?>"
    var pwd = "<?php if(isset($_SESSION['pwd'])){echo $_SESSION['pwd'];}else{echo "";}?>"
    var pass = "<?php if(isset($_SESSION['pass'])){echo $_SESSION['pass'];}else{echo "";}?>"
    $(document).ready(function(){
    
    $.ajax({
        type: 'GET',
        url: 'A_Alarmas.php?acc=' + acc + '&pwd= ' + pwd + '&pass=' + pass + '&estacion=' + estacion + '&filtro=' + filtro + '&orden=' + orden,
        success: function(alarmas) {
            $("#tablaAlarmas").html(alarmas);
        }
    });
    
});
}


}

</script>

<?= $this->endSection() ?>