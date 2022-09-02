<?php
require '../app/Database/Database.php';
$db = new Database();
//declaraciones de variables
//actualiza el listado de alarmas con la configuracion establecida por el usuario
if ($_GET['funcion'] == "actualizar") {
    $nombre = $_GET['nombre'];
    $pwd = $_GET['pwd'];
    $emp = $_GET['emp'];
    $orden = $_GET['orden'];
    $sentido = $_GET['sentido'];
    $fechaIni = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];
    $idusu = $db->obtenerIdUsuario($nombre, $pwd, $emp);
    $alarmas = $db->obtenerAlarmasUsuario($idusu, $orden, $sentido, $fechaIni, $fechaFin);
    $alarmasLimpio = array();
    foreach ($alarmas as $estacion => $alarmas) {
        if ($alarmas != false) {
            $alarmasLimpio[$estacion] = $alarmas;
        }
    }
    echo "<tr>        
        <th onclick=reordenar('estacion')>Estacion</th>
        <th onclick=reordenar('senal')>Indicador </th>
        <th onclick=reordenar('valor')>Valor de la Indicador</th>
        <th onclick=reordenar('origenfecha')>Fecha de Origen</th>
        <th onclick=reordenar('restauracionfecha')>Fecha de Restauracion</th>
        <th onclick=reordenar('reconusu')>Reconocida por</th>
        <th onclick=reordenar('reconfecha')>Fecha de reconocimiento</th>
        </tr>";
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
                switch ($dato) {
                    case 'valor_alarma':
                        echo "<td>";
                        echo $valor;
                        //aqui hay que cambiar el filtro de alarmas
                        //
                        if (preg_match('~[0-9]+~', $alarma['valor_alarma'])) {
                            echo '<i class="fas fa-chart-bar" style="opacity:100%;color:rgb(1,168,184)" onclick="detallesAlarma(' . $alarma['id_alarmas'] . ')"></i>';
                        }
                        echo "</td>";
                        break;
                    case 'ack_por':
                        if ($valor == null) {
                            echo "<td>";
                            echo '<i class="fas fa-eye" onclick="reconocer(' . $alarma['id_alarmas'] . ')"></i>';
                            echo "</td>";
                        } else {
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                        }
                        break;
                    default:
                        echo "<td>";
                        echo $valor;
                        echo "</td>";
                        break;
                }
            }
        }
        echo "</tr>";
    }
}
//obtiene el listado de alarmas de una estacion concreta
if ($_GET['funcion'] == "estacion") {
    $orden = $_GET['orden'];
    $sentido = $_GET['sentido'];
    $fechaIni = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];
    $id_estacion = $_GET['estacion'];
    $alarmasEstacion = $db->obtenerAlarmasEstacion($id_estacion, $orden, $sentido, null, null);
    if ($alarmasEstacion != false) {
        $alarmasEstacionLimpio = array();
        foreach ($alarmasEstacion as $alarma => $datos) {
            if ($alarma != false) {
                $alarmasEstacionLimpio[$alarma] = $datos;
            }
        }
        echo "<tr>        
            <th onclick=reordenar('estacion')>Estacion</th>
        <th onclick=reordenar('senal')>Indicador</th>
        <th onclick=reordenar('valor')>Valor de Indicador</th>
        <th onclick=reordenar('origenfecha')>Fecha de Origen</th>
        <th onclick=reordenar('restauracionfecha')>Fecha de Restauracion</th>
        <th onclick=reordenar('reconusu')>Reconocida por</th>
        <th onclick=reordenar('reconfecha')>Fecha de reconocimiento</th>
            </tr>";
        foreach ($alarmasEstacionLimpio as $alarma) {

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
                    switch ($dato) {
                        case 'valor_alarma':
                            echo "<td>";
                            echo $valor;
                            //aqui hay que cambiar el filtro de alarmas
                            if (preg_match('~[0-9]+~', $alarma['valor_alarma'])) {
                                echo '<i class="fas fa-chart-bar" style="opacity:100%;color:rgb(1,168,184)" onclick="detallesAlarma(' . $alarma['id_alarmas'] . ')"></i>';
                            }
                            echo "</td>";
                            break;
                        case 'ack_por':
                            if ($valor == null) {
                                echo "<td>";
                                echo '<i class="fas fa-eye" onclick="reconocer(' . $alarma['id_alarmas'] . ')"></i>';
                                echo "</td>";
                            } else {
                                echo "<td>";
                                echo $valor;
                                echo "</td>";
                            }
                            break;
                        default:
                            echo "<td>";
                            echo $valor;
                            echo "</td>";
                            break;
                    }
                }
            }
            echo "</tr>";
        }
    }
}
//actualiza el estado de una alarma en particular
//establece el estado como reconocida
//establece la fecha de reconocimiento
//establece el usuario que reconoció la alarma
if ($_GET['funcion'] == "reconocer") {
    $nombre = $_GET['nombre'];
    $id_alarma = $_GET['alarma'];
    $hora = date('Y/m/d H:i:s', time());
    $recon = $db->reconocerAlarma($id_alarma, $nombre, $hora);
    if ($recon != false) {
        echo "bien";
    } else {
        echo "mal";
    }
}
if ($_GET['funcion'] == "detalles") {
    $id = $_GET['id'];
    $detalles = $db->obtenerDetallesAlarma($id);
    if ($detalles != false) {
        echo json_encode($detalles);
    } else {
        echo 'error';
    }
    // $fecha_origen = $db
}