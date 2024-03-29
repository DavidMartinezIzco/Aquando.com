<?php
require_once '../app/Database/Database.php';
$digiVip = [];
$analogVip = [];
$db = new Database();
//falta alguna funcion para sacar los tags de la config de usuario
//y que despues saque el valor actual, trend y agregación semanal de cada uno
//para pasarla al render de widgets
if ($_POST['opcion'] == 'refresh') {
    $datos = json_decode($_POST['arrdatos']);
    $nombre = $datos->nombre;
    $pwd = $datos->pwd;
    $estacionesUsuario = $db->mostrarEstacionesCliente($nombre, $pwd);
    if ($estacionesUsuario) {
        $digiVip = $db->feedPrincipalDigital($estacionesUsuario);
    }
    $espacios = 4;
    $feedDigital = array();
    foreach ($digiVip as $estacion => $tags) {
        foreach ($tags as $index => $alarma) {
            if ($espacios > 0) {
                $alarma['estacion'] = $estacion;
                $feedDigital[$alarma['id_tag']] = $alarma;
            }
            $espacios--;
        }
    }
    echo json_encode($feedDigital);
}
//obtiene los tags analógicos disponibles para mostrar en el grid de widgets derecho
if ($_POST['opcion'] == 'ajustes') {
    $datos = json_decode($_POST['arrEstaciones']);
    $datosAnalog = $db->tagsAnalogHisto($datos);
    $datosSinAcus = array();
    foreach ($datosAnalog as $estacion => $tags) {
        foreach ($tags as $index => $tag) {
            if (strpos($tag['nombre_tag'], "Acumulado") !== false) {
                if (strpos($tag['nombre_tag'], "Dia") !== false) {
                    $datosSinAcus[$estacion][] = $tag;
                }
            } else {
                $datosSinAcus[$estacion][] = $tag;
            }
        }
    }
    echo json_encode($datosSinAcus);
}
//establece la configuración de un widget
if ($_POST['opcion'] == 'confirmar') {
    $widget = $_POST['wid'];
    $tag = $_POST['tag'];
    $usu = $_POST['usu'];
    // $pwd = $_POST['pwd'];
    $id_usuario = $db->obtenerIdUsuario($usu);
    if ($id_usuario) {
        $db->confirmarWidget($widget, $tag, $id_usuario[0]['id_usuario']);
    }
}
//recoge los datos para el grid de widgets derecho en función de los ajustes del usuario
if ($_POST['opcion'] == 'feed') {
    $usu = $_POST['usu'];
    // $pwd = $_POST['pwd'];
    $id_usuario = $db->obtenerIdUsuario($usu);
    if ($id_usuario) {
        echo json_encode($db->feedPrincipalCustom($id_usuario[0]['id_usuario']));
    }
}
