<?php
require_once '../app/Database/Database.php';


$digiVip= [];
$analogVip = [];
$db = new Database();

//falta alguna funcion para sacar los tags de la config de usuario
//y que despues saque el valor actual, trend y agregación semanal de cada uno
//para pasarla al render de widgets

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
    $datos = json_decode($_REQUEST['arrEstaciones']);
    
    $datosAnalog = $db->tagsAnalogHisto($datos);
    echo json_encode($datosAnalog);
}

if($_GET['opcion'] == 'confirmar'){
    $widget = $_GET['wid'];
    $tag = $_GET['tag'];
    $usu = $_GET['usu'];
    $pwd = $_GET['pwd'];

    $id_usuario = $db->obtenerIdUsuario($usu, $pwd);
    if($id_usuario){
        $db->confirmarWidget($widget, $tag, $id_usuario[0]['id_usuario']);
    }

}

if($_GET['opcion'] == 'feed'){
    $usu = $_GET['usu'];
    $pwd = $_GET['pwd'];
    $id_usuario = $db->obtenerIdUsuario($usu, $pwd);
    if($id_usuario){
        echo json_encode($db->feedPrincipalCustom($id_usuario[0]['id_usuario']));
        // $datosFeedCustom = $db->feedPrincipalCustom($id_usuario[0]['id_usuario']);
    }

    //ideas pre-pitillo:

    //hacer los widgets aqui?
    //reciclar widgets de estaciones?
    //necesitamos:
        // vista1 -> valor actual
        // vista2 -> feed diario
        // vista3 -> feed semanal
    //el feed diario tiene mucho peso, habrá que ver que tal se porta

    // $w1 = "";
    // $w2 = "";
    // $w3 = "";
    // $w4 = "";
    // $widSup = "";
    // $widInf = "";
    // foreach ($datosFeedCustom as $wid => $info) {
    //     if($wid == 'w1'){
    //         $w1 .= '<div class="anaIzq" onclick="rotarCarrusel(this)">';
    //         $w1 .= '<div class="carr" name="carru1">';
    //             //primera vista
    //             $w1 .= '<div class="carr">';
    //             $w1 .= "<h4>". $info['nombre'] . " de " . $info['estacion'] ."</h4>";
    //             $w1 .= '<p>'. $info['ultimo_valor']['valor'] .'</p>';
    //             $w1 .= "</div>";

    //             //segunda vista (trend dia)
    //             $w1 .= '<div class="carr">';
    //             $w1 .= "<h4>". $info['nombre'] . " de " . $info['estacion'] ."</h4>";
    //             $w1 .= '<p>'. 'aqui irá el trend diario' .'</p>';
    //             $w1 .= "</div>";

    //             //tercera vista (trend semana)
    //             $w1 .= '<div class="carr">';
    //             $w1 .= "<h4>". $info['nombre'] . " de " . $info['estacion'] ."</h4>";
    //             $w1 .= '<p>'. 'aqui irá el trend semanal con agregados' .'</p>';
    //             $w1 .= "</div>";
    //         $w1.= "</div></div>";
    //     }
    //     if($wid == 'w2'){
    //         $w2 .= '<div class="anaDer" onclick="rotarCarrusel(this)">';
    //         $w2 .= '<div class="carr" name="carru1">';
    //             //primera vista
    //             $w2 .= '<div class="carr">';
    //             $w2 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w2 .= '<p>'. $info['ultimo_valor']['valor'] .'</p>';
    //             $w2 .= "</div>";

    //             //segunda vista (trend dia)
    //             $w2 .= '<div class="carr">';
    //             $w2 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w2 .= '<p>'. 'aqui irá el trend diario' .'</p>';
    //             $w2 .= "</div>";

    //             //tercera vista (trend semana)
    //             $w2 .= '<div class="carr">';
    //             $w2 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w2 .= '<p>'. 'aqui irá el trend semanal con agregados' .'</p>';
    //             $w2 .= "</div>";
    //         $w2.= "</div></div>";
    //     }
    //     if($wid == 'w3'){
    //         $w3 .= '<div class="anaIzq" onclick="rotarCarrusel(this)">';
    //         $w3 .= '<div class="carr" name="carru1">';
    //             //primera vista
    //             $w3 .= '<div class="carr">';
    //             $w3 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w3 .= '<p>'. $info['ultimo_valor']['valor'] .'</p>';
    //             $w3 .= "</div>";

    //             //segunda vista (trend dia)
    //             $w3 .= '<div class="carr">';
    //             $w3 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w3 .= '<p>'. 'aqui irá el trend diario' .'</p>';
    //             $w3 .= "</div>";

    //             //tercera vista (trend semana)
    //             $w3 .= '<div class="carr">';
    //             $w3 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w3 .= '<p>'. 'aqui irá el trend semanal con agregados' .'</p>';
    //             $w3 .= "</div>";
    //         $w3.= "</div></div>";
    //     }
    //     if($wid == 'w4'){
    //         $w4 .= '<div class="anaDer" onclick="rotarCarrusel(this)">';
    //         $w4 .= '<div class="carr" name="carru1">';
    //             //primera vista
    //             $w4 .= '<div class="carr">';
    //             $w4 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w4 .= '<p>'. $info['ultimo_valor']['valor'] .'</p>';
    //             $w4 .= "</div>";

    //             //segunda vista (trend dia)
    //             $w4 .= '<div class="carr">';
    //             $w4 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w4 .= '<p>'. 'aqui irá el trend diario' .'</p>';
    //             $w4 .= "</div>";

    //             //tercera vista (trend semana)
    //             $w4 .= '<div class="carr">';
    //             $w4 .= "<h4>". $info['nombre'] . "de " . $info['estacion'] ."</h4>";
    //             $w4 .= '<p>'. 'aqui irá el trend semanal con agregados' .'</p>';
    //             $w4 .= "</div>";
    //         $w4.= "</div></div>";
    //     }
    // }
    // $widSup = $w1.$w2;
    // $widInf = $w3.$w4;
    // $conPrinDer = $widSup.$widInf;
    // echo $conPrinDer;

}

?>