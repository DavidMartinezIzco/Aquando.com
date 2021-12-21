<?php

require '../app/Database/Database.php';

echo "<tr>
<th onclick=filtrarPor('Motivo')>TIPO</th>
            <th onclick=filtrarPor('Canal')>CANAL</th>
            <th onclick=filtrarPor('Fecha')>FECHA</th>
            <th onclick=filtrarPor('Estacion')>MENSAJE</th>
</tr>";


    //declaraciones de variables
if(isset($_GET['estacion'])){
    $estacion = $_GET['estacion'];
    $estacion = str_replace('"', '', $estacion);
    $opciones = array("Database"=>"Zeus", "Uid"=>"sa", "PWD"=>"dateando","CharacterSet"=>"UTF-8");
    $filtro = $_GET['filtro'];
    $orden = $_GET['orden'];
    if($filtro != ""){
        switch ($filtro) {
            case 'Fecha':
                $filtro = "ORDER BY [Fecha] ";
                break;
            
            case 'Motivo':
                $filtro = "ORDER BY [Motivo] ";
                break;
            
            case 'Canal':
                $filtro = "ORDER BY [Canal] ";
                break;

            case 'Estacion':
                $filtro = "ORDER BY [Estacion] ";
                break;
                    
            default:
            $filtro = "";
                break;
        }
        $filtro = $filtro . $orden;
    }

    //actualizar
    if ($estacion == 'all') {
    
        $pass = $_GET['pass'];
        $pass = str_replace(' ', '+', $pass);
        $acc = $_GET['acc'];
        $pwd = $_GET['pwd'];
        $pwd = str_replace(' ', '', $pwd);
        $i = 0;
        $e = 0;

        $conexion = new Client('172.16.3.2', 3030, false, $acc, $pwd, $pass);
        $estaciones = $conexion->Stations();
        $conexionDB = New Database();
        $conexionDB->conectar();
        $desde = "2021-01-01 00:00:01.000";
        foreach ($estaciones as $index => $estacion) {
          if($index != 0){
            $alarmas[] = $conexionDB->obtenerAlarmasEstacion($estacion, null, null, $desde, $filtro);
          }
        }

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
    //filtrar
    else {
        $sql = "SELECT [Motivo],[Canal],[Dato] FROM [Zeus].[dbo].[SMS] WHERE [Estacion] = '".$estacion."' " . $filtro;
        if ($conexion = sqlsrv_connect("172.16.3.2", $opciones)) {
            $consulta = sqlsrv_query($conexion, $sql);

            if (!$consulta ) {
                $alarmas[] = array("Fecha"=>"error", "Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
                return $alarmas;
            }
            else {
                if(!sqlsrv_has_rows($consulta)){
                    $alarmas[] = array("Fecha"=>"error", "Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
                    return $alarmas;
                }
                else{
                    while($alarmasDeEstacion = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                        $alarmas[] = $alarmasDeEstacion;
                    }
                    $i = 0;
                    foreach ($alarmas as $index => $alarmasDeEstacion) {
                        
                        $i++;
                        echo "<tr id='".$i."'>";
                        foreach ($alarmasDeEstacion as $dato => $info) {
                            
                            if($dato != "Fecha" && $info != "sin datos de la consulta" && $info != "error"){
                                if ($dato == "Dato") {
                                    $pos = strpos($info, $estacion);
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
        else {
            echo '<script language="javascript">';
            echo 'alert("error pepino en ajax")';
            echo '</script>';
        }

    }


}



?>