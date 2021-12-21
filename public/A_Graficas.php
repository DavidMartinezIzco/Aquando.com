<?php
require '../app/Database/Database.php';

if(isset($_GET['tag'])){
    $id_tag = $_GET['tag'];
}
$id_estacion = $_GET['estacion'];
$opcion = $_GET['opcion'];


$db = new Database();

if($opcion == "render"){

    $histos = $db->historicosTagEstacion($id_estacion, $id_tag);

    if($histos != false){
        echo json_encode($histos);
    }
    else {
        echo "error";
    }
}

if($opcion == "tags"){
    $tags = $db->tagsEstacion($id_estacion);
    if($tags != false){
        echo json_encode($tags);
    }
    else {
        echo "error";
    }

}



?>