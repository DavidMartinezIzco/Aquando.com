<?php
require '../app/Database/Database.php';
if (isset($_GET['tag'])) {
    $id_tag = $_GET['tag'];
}
$opcion = $_GET['opcion'];
$id_estacion = $_GET['estacion'];
$db = new Database();
//obtiene datos historicos de un tag
if ($opcion == "render") {

    $histos = $db->historicosTagEstacion($id_estacion, $id_tag);
    if ($histos != false) {
        echo json_encode($histos);
    } else {
        echo "error";
    }
}
//obtiene los tags historizables de una estación
if ($opcion == "tags") {
    $tags = $db->tagsEstacion($id_estacion);
    if ($tags != false) {
        echo json_encode($tags);
    } else {
        echo "error";
    }
}
//obtiene los metadatos (max, min, avg) de un tag
if ($opcion == "meta") {
    $metaDatos = $db->metaTag($id_tag, $id_estacion);
    if ($metaDatos != false) {
        echo json_encode($metaDatos);
    } else {
        echo "error";
    }
}