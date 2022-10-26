<?php
require '../app/Database/DataWit.php';
$DW = new Datawit();
$opcion = $_POST['opcion'];
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
    $update = $DW->modificarConsignaWit($ref, $val);
    echo $update;
}

if ($opcion == "lisPlan") {
    $estacion = $_POST['estacion'];
    $listPlan = $DW->leerPlaningsEstacion($estacion);
    echo json_encode($listPlan);
}

if ($opcion == "dataPlan") {
    $recurso = $_POST['recurso'];
    $dataPlan = $DW->leerValorPlanning($recurso);
    echo json_encode(($dataPlan));
}

if ($opcion == "modPlan") {
    $ref = $_POST['ref'];
    $nPlan = $_POST['val'];
    $estatus = $DW->modificarPlanning($ref, $nPlan);
    echo $status;
}
