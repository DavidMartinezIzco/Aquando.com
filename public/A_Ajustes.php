<?php
require '../app/Database/DataWit.php';
$DW = new Datawit();
$opcion = $_POST['opcion'];

//NO DEJAR QUE SE MODIFIQUEN LAS CONSIGNAS DE BERROA!!!

//muestra lista de consignas
if ($opcion == "con") {
    $estacion = $_POST['estacion'];
    $dump = $DW->consignasEstacion($estacion);
    echo json_encode($dump);
}

//lee valores de una consigna
if ($opcion == "det") {
    $recurso = $_POST['ref'];
    $info = $DW->leerConsignaWIT($recurso);
    echo json_encode($info);
}

//modifica una consigna
if ($opcion == "mod") {
    //[coger params]
    $ref = $_POST['ref'];
    $val = $_POST['val'];
    $update = $DW->modificarConsignaWit($ref,$val);
    echo $update;
}
