<?php

require '../app/Database/Database.php';

$opcion = $_GET['opcion'];
$db = new Database();

//calcula el tiempo que lleva una estación sin comunicar
//determina en función del y tiempo basandose en unas reglas
//si la estación tiene problemas no
if ($opcion == 'conex') {

    $nombre = $_GET['nombre'];
    $pwd = $_GET['pwd'];

    $estaciones = $db->mostrarEstacionesCliente($nombre, $pwd);
    $ultimasConexiones = array();
    foreach ($estaciones as $estacion) {
        $ultimasConexiones[$estacion['nombre_estacion']] = $db->ultimaComunicacionEstacion($estacion['id_estacion']);
    }
    foreach ($ultimasConexiones as $estacion => $datos) {
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'valor_date') {
                $ultima = new DateTime;
                $ultima = DateTime::createFromFormat('Y-m-d H:i:s', $valor);
                $ahora = new DateTime("now");
                $dif = $ahora->diff($ultima);
                $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                if ($dif->days >= 1) {
                    $ultimasConexiones[$estacion][0]['estado'] = "aviso";
                }
                if ($dif->days >= 2) {
                    $ultimasConexiones[$estacion][0]['estado'] = "error";
                }
            }
        }
    }

    foreach ($ultimasConexiones as $estacion => $datos) {
        echo "<tr id='seccionEstacion' name=" . $datos[0]['id_estacion'] . ">";
        foreach ($datos[0] as $dato => $valor) {
            if ($dato == 'nombre_estacion') {
                echo "<td id='secNombre'>";
                echo $valor;
                echo "</td>";
            }
            if ($dato == 'valor_date') {
                echo "<td id='secUltima'>";
                echo "Última conexión: " . $valor;
                echo "</td>";
            }
            if ($dato == 'nombre_tag') {
            }
            if ($dato == 'id_estacion') {
            }
            if ($dato == 'estado') {
                if ($valor == "correcto") {
                    echo "<td id='secEstado'><i class='fas fa-check'></i></td>";
                }
                if ($valor == "error") {
                    echo "<td id='secProblema' class='' style='color:tomato'><i name='alerta' class='fas fa-exclamation-triangle alerta'></i></td>";
                }
                if ($valor == "aviso") {
                    echo "<td id='secProblema'><i name='alerta' class='fas fa-exclamation-triangle alerta'></i></td>";
                }
            }
        }
        echo "</tr>";
    }
}

//obtiene los nombres de una estación dado su tag
if ($opcion == 'nom') {
    $id_estacion = $_GET['estacion'];

    $estacion = $db->obtenerNombreEstacion($id_estacion);
    echo ($estacion[0]['nombre_estacion']);
}
