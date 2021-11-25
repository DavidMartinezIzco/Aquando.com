<?php

require '../app/Database/Database.php';



$i = 0;
$e = 0;


$conexionDB = New Database();
$conexion = $conexionDB->conectar();



$sql = "SELECT TOP 7 [Motivo],[Canal],[Estacion] FROM [Zeus].[dbo].[SMS] ORDER BY [Fecha]";
$consulta = sqlsrv_query($conexion, $sql);

//error
if (!$consulta ) {
    $alarmas[] = array("Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
    return $alarmas;
}

//sin resultados
if(!sqlsrv_has_rows($consulta)){
    $alarmas[] = array("Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
    return $alarmas;
}

// bien
else{
    while($alarmasDeEstacion = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
        $alarmas[] = $alarmasDeEstacion;
    }
    
    foreach ($alarmas as $index => $alarmasDeEstacion) {
        
        $e++;
        echo "<tr class='filaAl' id=".$e.">";
        foreach ($alarmasDeEstacion as $dato => $info) {
            $i ++;
            if($dato != "Fecha" && $info != "sin datos de la consulta" && $info != "error"){
                if($dato == "Estacion"){
                    echo "<td>Estacion: ".$info."</td>";
                }
                elseif ($dato == "Motivo") {
                    switch ($info) {
                        case 1:
                            echo "<script>
                            document.getElementById('".$e."').style.backgroundColor='#de3d37';
                            document.getElementById('".$e."').style.color='white';
                            setInterval(latido(document.getElementById('".$e."')), 500);
                            </script>";
                            
                            break;
                        
                        case 3:
                            echo "<script>document.getElementById('".$e."').style.backgroundColor='rgb(144, 238, 144)'</script>";
                            break;
                               
                        default:
                            # code...
                            break;
                    }
                    echo "<td>Motivo: ".$info."</td>";
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

?>