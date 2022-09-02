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

//busca los datos de maximos minimos medias y metadados de las estaciones seleccionadas de los tags relacionados con caudales
//despues crea un objeto tabla de Koolreport
if ($opcion == "cau") {

    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();
    $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'cau', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
            foreach ($informeDep as $señal => $info) {
                foreach ($info as $index => $datos) {
                    $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
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
            "fecha" => array(
                "cssStyle" => "text-align:left"
            ),
            "maximo" => array(
                "cssStyle" => "text-align:center"
            ),
            "minimo" => array(
                "cssStyle" => "text-align:center"
            ),
            "media" => array(
                "cssStyle" => "text-align:center"
            ),


        ),
        "grouping" => array(
            "estacion" => array(
                "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
            ),
            "señal" => array(
                "calculate" => array(
                    "{max}" => array("max", "maximo"),
                    "{med}" => array("avg", "media"),
                    "{min}" => array("min", "minimo")
                ),
                "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",

                "bottom" => function ($val) {
                    $fila = "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de " . $val['{señal}'] . ":</b></td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: " . $val['{max}'] . "</td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: " . $val['{min}'] . "</td>
                   <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: " . number_format($val['{med}'], 2) . "</td>";
                    return $fila;
                }
            )
        ),
        "showHeader" => false,
        "cssClass" => array(
            "table" => "table table-hover table-bordered",
        ),
    ));
}

//busca los datos de maximos minimos medias y metadados de las estaciones seleccionadas de los tags relacionados con niveles de las estaciones seleccionadas
//despues crea un objeto tabla de Koolreport
if ($opcion == "niv") {
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();
    $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);
    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'niv', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
            foreach ($informeDep as $señal => $info) {
                foreach ($info as $index => $datos) {
                    $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
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

            "fecha" => array(
                "cssStyle" => "text-align:center"
            ),
            "maximo" => array(
                "cssStyle" => "text-align:center"
            ),
            "minimo" => array(
                "cssStyle" => "text-align:center"
            ),
            "media" => array(
                "cssStyle" => "text-align:center"
            ),
        ),
        "grouping" => array(
            "estacion" => array(
                "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
            ),
            "señal" => array(
                "calculate" => array(
                    "{max}" => array("max", "maximo"),
                    "{med}" => array("avg", "media"),
                    "{min}" => array("min", "minimo")
                ),
                "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",

                "bottom" => "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de {señal}:</b></td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: {max}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: {min}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: {med}</td>",
            )
        ),
        "showHeader" => false,
        "cssClass" => array(
            "table" => "table table-hover table-bordered",
        ),
    ));
}

//busca los datos de maximos y metadatos de los tags relacionados con acumulados DIA de las estaciones seleccionadas
//despues crea un objeto tabla de Koolreport
if ($opcion == "acu") {
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();
    $informeTabla = array(['estacion', 'señal', 'fecha', 'valor']);

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'acu', $fechaIni, $fechaFin);
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
            "fecha" => array(
                "cssStyle" => "text-align:left"
            ),
            "valor" => array(
                "cssStyle" => "text-align:center"
            ),
        ),
        "grouping" => array(
            "estacion" => array(
                "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
            ),
            "señal" => array(
                "calculate" => array(
                    "{maxi}" => array("sum", "valor"),
                ),
                "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:left'><b>{señal}:</b></td>
                            <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>acumulado:</b></td>",

                "bottom" => "<td  style='background-color:grey;font-size:100%;color:white;'><b>Total de {señal}:</b></td>
                            <td style='background-color:grey;font-size:100%;color:white;text-align:center'><b>{maxi}</b></td>",
            )
        ),
        "showHeader" => false,
        "cssClass" => array(
            "table" => "table table-hover table-bordered",
        ),
    ));
}

//busca los datos de maximos minimos medias y metadatos de los tags relacionados con cloros y turbidez de las estaciones seleccionadas
//despues crea un objeto tabla de Koolreport
if ($opcion == "clo") {
    $estaciones = json_decode(($_REQUEST['arrEstaciones']));
    $informesDep = array();
    $informeDep = array();
    $informeTabla = array(['estacion', 'señal', 'fecha', 'maximo', 'minimo', 'media']);

    foreach ($estaciones as $index => $estacion) {
        // $informesDep[] = $estacion;
        $informeDep = $db->informeSeñalEstacion($estacion, 'clo', $fechaIni, $fechaFin);
        if ($informeDep != null && !empty($informeDep)) {
            $informesDep[$estacion] = $informeDep;
            foreach ($informeDep as $señal => $info) {
                foreach ($info as $index => $datos) {
                    $informeTabla[] = [$nombres[$estacion], $señal, $datos['fecha'], $datos['maximo'], $datos['minimo'], $datos['media']];
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

            "fecha" => array(
                "cssStyle" => "text-align:center"
            ),
            "maximo" => array(
                "cssStyle" => "text-align:center"
            ),
            "minimo" => array(
                "cssStyle" => "text-align:center"
            ),
            "media" => array(
                "cssStyle" => "text-align:center"
            ),

        ),
        "grouping" => array(
            "estacion" => array(
                "top" => "<td colspan=4 style='background-color:rgb(39,45,79);font-size:120%;color:whitesmoke;'><b>{estacion}:</b></td>",
            ),
            "señal" => array(
                "calculate" => array(
                    "{max}" => array("max", "maximo"),
                    "{med}" => array("avg", "media"),
                    "{min}" => array("min", "minimo")
                ),
                "top" => "<td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);'><b>{señal}:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Maximo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Minimo:</b></td>
                <td style='background-color:rgb(1, 168, 184);font-size:100%;color:rgba(56, 56, 56);text-align:center'><b>Media:</b></td>",

                "bottom" => "<td style='background-color:grey;font-size:100%;color:white;'><b>Resumen de {señal}:</b></td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Maximo: {max}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Minimo: {min}</td>
                <td style='background-color:grey;font-size:100%;color:white;text-align:center'> Media: {med}</td>",
            )
        ),
        "showHeader" => false,
        "cssClass" => array(
            "table" => "table table-hover table-bordered",
        ),
    ));
}