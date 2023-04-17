<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: GET, POST");
require_once '../app/Database/Database.php';
if (isset($_POST['tag'])) {
    $id_tag = $_POST['tag'];
}
$opcion = $_POST['opcion'];
$id_estacion = $_POST['estacion'];
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
