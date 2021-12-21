<?php

require '../app/Database/Database.php';
$db = new Database();
    //declaraciones de variables
    if($_GET['funcion'] == "actualizar"){

        $nombre = $_GET['nombre'];
        $pwd = $_GET['pwd'];
        $emp = $_GET['emp'];
        // $orden = $_GET['orden'];
        $sentido = $_GET['sentido'];
        $idusu = $db->obtenerIdUsuario($nombre,$pwd, $emp);

        $alarmas = $db->obtenerAlarmasUsuario($idusu, null , $sentido);

        $alarmasLimpio = array();
        foreach ($alarmas as $estacion => $alarmas) {
            if($alarmas != false){
                $alarmasLimpio[$estacion] = $alarmas;
            }
        }
        echo "<tr>      
        <th>Estación</th>
        <th>Fecha de Origen</th>
        <th >Fecha de Restauracion</th>
        <th>Estado</th>
        <th>Reconocida por</th>
        <th>Info</th>
        </tr>";

        $i = 0;
        $e = 0;
        foreach ($alarmasLimpio as $index => $alarma) {
            echo "<tr id=$e>";
            foreach ($alarma as $dato => $valor) {
                if($dato == 'estado'){
                    switch ($valor) {
                        case 1:
                            echo "<script type='text/javascript'>document.getElementById($e).className ='activaNo'</script>";
                            echo "<td>";
                            echo "ACTIVA NO RECONOCIDA";
                            echo "</td>";

                            break;
                        case 2:
                            echo "<script type='text/javascript'>document.getElementById($e).className='restNo'</script>";
                            echo "<td>";
                            echo "RESTAURADA NO RECONOCIDA";
                            echo "</td>";
                            break;
                        case 3:
                            echo "<script type='text/javascript'>document.getElementById($e).className ='activaSi'</script>";
                            echo "<td>";
                            echo "ACTIVA RECONOCIDA";
                            echo "</td>";
                            break;
                        case 4:
                            echo "<script type='text/javascript'>document.getElementById($e).className ='restSi'</script>";
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
        

    }



    //actualizar


    //filtrar
    



?>