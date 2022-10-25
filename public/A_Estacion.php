<?php
require '../app/Database/Database.php';
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
    foreach ($arr as $a => $b) {
            // if (!array_key_exists($a, $arrTrends)) {
            //     $arrTrends[$a] = ['fecha' => [], 'max' => []];
            // }
        foreach ($b as $c => $d) {
            $arrTrends[$a]['fecha'][] .= $d['fecha'];
            if ($d['acu'] != null) {
                $arrTrends[$a]['max'][] .= $d['acu'];
                break;
            }
            if ($d['int'] != null) {
                $arrTrends[$a]['max'][] .= $d['int'];
                break;
            }
            if ($d['float'] != null) {
                $arrTrends[$a]['max'][] .= $d['float'];
                break;
            }
        } //PLAN B PASAR ARR AL JS Y APAÑARLO ALLI
    }
    echo json_encode($arrTrends);
}
