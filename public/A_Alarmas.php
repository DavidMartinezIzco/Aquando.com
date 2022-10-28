<?php
require_once '../app/Database/Database.php';
require '../app/Models/Validador.php';
$db = new Database();
$vlr = new Validador();
//declaraciones de variables
//actualiza el listado de alarmas con la configuracion establecida por el usuario

if ($_POST['funcion'] == "actualizar") {
    $nombre = $_POST['nombre'];
    $emp = $_POST['emp'];
    $orden = $_POST['orden'];
    $sentido = $_POST['sentido'];
    $fechaIni = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    //EXPERIMENTAL: VALIDAR FECHAS DE LOS INPUTS --> algo falla
    if ($vlr->valFecha($fechaFin)) {
        $idusu = $db->obtenerIdUsuario($nombre, $emp);
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
    else {
        echo "<p>fechas no validas</p>";
    }
}
//obtiene el listado de alarmas de una estacion concreta
if ($_POST['funcion'] == "estacion") {
    $orden = $_POST['orden'];
    $sentido = $_POST['sentido'];
    $fechaIni = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $id_estacion = $_POST['estacion'];
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

if ($_POST['funcion'] == "reconocer") {
    $nombre = $_POST['nombre'];
    $id_alarma = $_POST['alarma'];
    $hora = date('Y/m/d H:i:s', time());
    $recon = $db->reconocerAlarma($id_alarma, $nombre, $hora);
    if ($recon != false) {
        echo "bien";
    } else {
        echo "fallo al reconocer la alarma";
    }
}
if ($_POST['funcion'] == "detalles") {
    $id = $_POST['id'];
    $detalles = $db->obtenerDetallesAlarma($id);
    if ($detalles != false) {
        echo json_encode($detalles);
    } else {
        echo ' error extrayendo detalles (origen no historizable)';
    }
}
