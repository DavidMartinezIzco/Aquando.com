<?php
require_once '../app/Database/Database.php';
$caso = $_POST['caso'];
$db = new Database();

//actualiza el listado del menu sur
//utiliza la config general
if ($caso == "general") {
    $conta = 0;
    $nombre = $_POST['nombre'];
    // $pwd = $_POST['pwd'];
    $id_usuario = $db->obtenerIdUsuario($nombre);
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
    if ($alarmasSur != false) {
        $alarmasLimpio = array();
        foreach ($alarmasSur as $estacion => $alarmas) {
            if ($alarmas != false) {
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
                if ($dato != 'estado' && $dato != 'id_alarmas') {
                    echo "<td>";
                    echo $valor;
                    echo "</td>";
                }
            }
            echo "</tr>";
            $conta++;
        }
        for ($conta; $conta < 7; $conta++) {
            echo "<tr style='background-color:lightgray;color:lightgray;'><td>sin mas alarmas en la estación </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        }
    } else {
        $i = 0;
        while ($i < 6) {
            while ($i <= 6) {
                $i++;
                echo "<tr style='background-color:lightgray;color:lightgray;'><td>sin alarmas en la estación </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            }
        }
    }
}
//actualiza el listado del menu sur en la sección de estacion
//utiliza la config particular
if ($caso == "estacion") {
    $estacion = $_POST['estacion'];
    $alarmasSur = $db->alarmasEstacionSur($estacion);
    echo "<tr>        
        <th>Estación</th>
        <th>Señal </th>
        <th>Valor de la señal</th>
        <th>Fecha de Origen</th>
        <th>Fecha de Restauracion</th>
        <th>Reconocida por</th>
        <th>Fecha de reconocimiento</th>
        </tr>";
    if ($alarmasSur != false) {
        if (!empty($alarmasSur)) {
            $alarmasLimpio = array();
            foreach ($alarmasSur as $estacion => $alarmas) {
                if ($alarmas != false) {
                    $alarmasLimpio[$estacion] = $alarmas;
                }
            }
            if (!empty($alarmasLimpio)) {
                $i = 0;
                foreach ($alarmasLimpio as $index => $alarma) {
                    $i++;
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
                        if ($dato != 'estado' && $dato != 'id_alarmas') {
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }
                while ($i <= 6) {
                    $i++;
                    echo "<tr style='background-color:lightgray;color:lightgray;'><td>sin mas alarmas en la estación </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
            }
        }
    } else {
        $i = 0;
        while ($i < 6) {
            while ($i <= 6) {
                $i++;
                echo "<tr style='background-color:lightgray;color:lightgray;'><td>sin alarmas en la estación </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            }
        }
    }
}
