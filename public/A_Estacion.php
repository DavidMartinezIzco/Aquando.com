<?php

require '../app/Database/Database.php';
$DB = new Database();
$opcion = $_GET['opcion'];
$id_estacion = $_GET['estacion'];
$tipo = $_GET['tipo'];

// echo json_encode($DB->tagTrend(141, $id_estacion));

if ($opcion == 'actualizar' && $tipo = 'todos') {
    try {
        echo json_encode($DB->datosEstacion($id_estacion, true));
    } catch (Throwable $e) {
        echo $e;
    }
}

if ($opcion == 'trends') {

    $datosAnalog = json_decode($_REQUEST['arrTags']);
    $trendsEstacion = [];
    foreach ($datosAnalog as $indexTag => $datosTag) {
        if ($indexTag != null && $datosTag != null) {
            $tag = $datosTag->id_tag;
            $trend = $DB->tagTrend($tag, $id_estacion);
            if ($trend != null || !empty($trend)) {
                $trendFilt = [];
                foreach ($trend as $index => $valores) {
                    foreach ($valores as $nombre => $valor) {
                        if ($valor != null && $nombre != 'fecha') {
                            $trendFilt['max'][] = $valor;
                        }
                        if ($nombre == 'fecha') {
                            $trendFilt['fecha'][] = $valor;
                        }
                    }
                }
                $trendsEstacion[$datosTag->id_tag] = $trendFilt;
            }
        }
    }

    echo json_encode($trendsEstacion);
}
