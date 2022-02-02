<?php

require '../app/Database/Database.php';
$db = new Database();
$opcion = $_GET['opcion'];

$fechaIni = $_GET['fechaIni'];
$fechaFin = $_GET['fechaFin'];

if($opcion == "cau"){
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = Array();
    $informeDep = Array();

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'cau', $fechaIni, $fechaFin);
        if($informeDep != null && !empty($informeDep)){
            $informesDep[$estacion] = $informeDep;
        }
    }

    echo json_encode($informesDep);

}

if($opcion == "niv"){
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = Array();
    $informeDep = Array();

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'niv', $fechaIni, $fechaFin);
        if($informeDep != null && !empty($informeDep)){
            $informesDep[$estacion] = $informeDep;
        }
    }

    echo json_encode($informesDep);

}

if($opcion == "acu"){
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = Array();
    $informeDep = Array();

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'acu', $fechaIni, $fechaFin);
        if($informeDep != null && !empty($informeDep)){
            $informesDep[$estacion] = $informeDep;
        }
    }

    echo json_encode($informesDep);

}
