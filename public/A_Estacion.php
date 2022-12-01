<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

require_once '../app/Database/Database.php';
$DB = new Database();
$opcion = $_POST['opcion'];
$id_estacion = $_POST['estacion'];
$tipo = "";
if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}
// echo json_encode($DB->tagTrend(141, $id_estacion));
//obtiene los ultimos datos de los tags de la estacion
if ($opcion == 'actualizar' && $tipo == 'todos') {
    try {
        echo json_encode($DB->datosEstacion($id_estacion, true));
    } catch (Throwable $e) {
        echo $e;
    }
}

//obtiene los trends de las estaciones (valores de los ultimos 7 dias)
if ($opcion == 'trends') {
    $datosAnalog = json_decode($_POST['arrTags']);
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
//busca la foto de la estación
if ($opcion == 'foto') {
    $foto = $DB->obtenerFotoEstacion($id_estacion);
    echo $foto;
    return true;
}
//tagtrend 2.0
//sacar todos los trends desde la mmisma consulta en vez de varias en batería
if ($opcion == 't_trend') {
    $datosAnalog = json_decode($_POST['arrTags']);
    $datosTrends = $DB->tagsTrends($datosAnalog);
    $arr = array();
    $arrTrends = array();
    foreach ($datosTrends as $key => $item) {
        $arr[$item['id_tag']][$key] = $item;
    }
    ksort($arr, SORT_NUMERIC);
    echo json_encode($arr);
    return true;
}

// if ($opcion == "") {
//     echo "Conectados a Aquando.com";
//     echo "\n si ves esto es porque los parametros fallan";
//     echo "\nOpcion = " . $opcion;
//     return true;
// }
