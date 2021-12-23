<?php

require '../app/Database/Database.php';

    $caso = $_GET['caso'];
    $db = new Database();
    if($caso == "general"){
        $nombre = $_GET['nombre'];
        $pwd = $_GET['pwd'];
        $id_emp = $_GET['emp'];

        $id_usuario = $db->obtenerIdUsuario($nombre, $pwd, $id_emp);
        $alarmasSur = $db->alarmasSur($id_usuario);

        echo "<tr>        
        <th>Estación</th>
        <th>Señal </th>
        <th>Valor de la señal</th>
        <th>Fecha de Origen</th>
        <th>Fecha de Restauracion</th>
        <th>Reconocida por</th>
        <th>Fecha de reconocimiento</th>
        </tr>";
        
        if($alarmasSur != false){
            $alarmasLimpio = array();
            foreach ($alarmasSur as $estacion => $alarmas) {
                if($alarmas != false){
                    $alarmasLimpio[$estacion] = $alarmas;
                }
            }

            foreach ($alarmasLimpio as $index => $alarma) {

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
                    
                        echo "<td>";
                        echo $valor;
                        echo "</td>";    
                        
                    }
                    
                }
                echo "</tr>";
                
            }
        }

    }

    
    if($caso = "estacion"){

    }

?>