<?php
require '../app/Database/Database.php';

$db = new Database();
$opcion = $_GET['opcion'];

//obtiene los historicos de un tag en un intervalo de fechas determinado
if($opcion == 'tag'){
    $id_estacion = $_GET['estacion'];
    $id_tag = $_GET['id_tag'];
    $fechaIni = $_GET['fechaIni'];
    $fechaFin = $_GET['fechaFin'];
    $meta = $_GET['meta'];
    $ajustesMeta = explode("/", $meta);

    $info = $db->historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaIni, $fechaFin);
    echo json_encode($info);

}


?>