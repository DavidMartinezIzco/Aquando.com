<?php

require_once '../app/Database/Database.php';
require_once "../app/Libraries/koolreport/core/autoload.php";
require_once '../app/Models/InformeCaudales.php';

use \koolreport\widgets\koolphp\Table;

$db = new Database();
$opcion = $_GET['opcion'];
$fechaIni = $_GET['fechaIni'];
$fechaFin = $_GET['fechaFin'];
$nombres = json_decode(($_REQUEST['arrNombres']));


if ($opcion == "cau") {

    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();
    $informeTabla = array(['estacion', 'señal', 'fecha', 'valor']);

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'cau', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
            foreach ($informeDep as $señal => $info) {
                foreach ($info as $index => $datos) {
                    $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['valor']];
                }
            }
        }
    }

    //crea obj KR y configs en su archivo aparte (Models)
    $informe = new InformeCaudales($informeTabla);
    $informe->run()->render();

    $table = Table::create(array(
        "dataSource" => $informeTabla,
        "columns" => array(
            // "estacion"=>array(
            //     "cssStyle"=>"font-weight:bold;text-align:center"
            // ),
            // "señal"=>array(
            //     "cssStyle"=>"text-align:left"
            //     ),
            "valor" => array(
                "cssStyle" => "text-align:center"
            ),
            "fecha" => array(
                "cssStyle" => "text-align:center"
            ),
        ),
        "grouping" => array(
            "estacion" => array(
                "top" => "<td style='background-color:rgba(56, 56, 56);color:white'>{estacion}:</td>",
            ),
            "señal" => array(
                "calculate" => array(
                    "{sumAmount}" => array("sum", "valor")
                ),
                "top" => "<b>{señal}:</b>",
                "bottom" => "<td class='lineaEspecial'><b>Total de {señal}:</b></td><td><b>{sumAmount}</b></td>",
            )
        ),
        "cssClass" => array(
            "table" => "table-bordered",

        ),

    ));
}

if ($opcion == "niv") {
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'niv', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
        }
    }

    echo json_encode($informesDep);
}

if ($opcion == "acu") {
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'acu', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
        }
    }

    echo json_encode($informesDep);
}
