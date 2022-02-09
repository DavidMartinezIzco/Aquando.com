<?php
require_once '../app/Database/Database.php';


$digiVip= [];
$analogVip = [];
$db = new Database();

if($_GET['opcion'] == 'refresh') {

    $datos = json_decode($_REQUEST['arrdatos']);
    $nombre = $datos->nombre;
    $pwd = $datos->pwd;

    $estacionesUsuario = $db->mostrarEstacionesCliente($nombre, $pwd);
    if($estacionesUsuario){
        $digiVip=$db->feedPrincipalDigital($estacionesUsuario);
    }
    
    $espacios = 4;
    $feedDigital = Array();
    foreach ($digiVip as $estacion => $tags) {
        foreach ($tags as $index => $alarma) {
            if($espacios > 0){
                $alarma['estacion'] = $estacion;
                $feedDigital[$alarma['id_tag']] = $alarma;
            }
            $espacios--;
        }
    }
    echo json_encode($feedDigital);
}

if($_GET['opcion'] == 'ajustes'){
    $datos = json_decode($_REQUEST['arrdatos']);
    $tagsDisponibles = Array();
    $nombre = $datos->nombre;
    $pwd = $datos->pwd;
    $estacionesUsuario = $db->mostrarEstacionesCliente($nombre, $pwd);
    foreach ($estacionesEstaciones as $index => $estacion) {
        $tagsDisponibles[$estacion['nombre_estacion']] = $db->tagsEstacion($estacion['id_estacion']);
    }
    echo json_encode($tagsDisponibles);
}

?>