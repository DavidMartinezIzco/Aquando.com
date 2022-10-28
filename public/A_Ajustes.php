<?php
require '../app/Database/DataWit.php';
require '../app/Models/Validador.php';
$DW = new Datawit();
$vlr = new Validador();
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
    //EXPERIMENTAL: VALIDA NUEVO VALOR DE CONSIGNA
    if ($vlr->valNum($val)) {
        $update = $DW->modificarConsignaWit($ref, $val);
    } else {
        $update = "valor de consigna no válido";
    }
    echo $update;
}

// NO IMPLEMENTADO: SACA LOS PLANNINGS DE LA ESTACION
if ($opcion == "lisPlan") {
    $estacion = $_POST['estacion'];
    $listPlan = $DW->leerPlaningsEstacion($estacion);
    echo json_encode($listPlan);
}

// NO IMPLEMENTADO: SACA LOS CONFIGURACION DE UN PLANNING
if ($opcion == "dataPlan") {
    $recurso = $_POST['recurso'];
    $dataPlan = $DW->leerValorPlanning($recurso);
    echo json_encode(($dataPlan));
}

// NO IMPLEMENTADO: MODIFICA LA CONFIG DE UN PLANNING
if ($opcion == "modPlan") {
    $ref = $_POST['ref'];
    $nPlan = $_POST['val'];
    //EXPERIMENTAL: VALIDA CONIFG DE PLANNING
    if ($vlr->valconfig($nPlan)) {
        $estatus = $DW->modificarPlanning($ref, $nPlan);
    } else {
        $estatus = "Configuración no válida";
    }
    echo $estatus;
}
