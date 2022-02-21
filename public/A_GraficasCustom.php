<?php
require '../app/Database/Database.php';

$db = new Database();
$opcion = $_GET['opcion'];

//obtiene los historicos de un tag en un intervalo de fechas determinado
if ($opcion == 'tag') {
    $id_estacion = $_GET['estacion'];
    $id_tag = $_GET['id_tag'];
    $fechaIni = $_GET['fechaIni'];
    $fechaFin = $_GET['fechaFin'];
    $meta = $_GET['meta'];
    $ajustesMeta = explode("/", $meta);
    $info = $db->historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaIni, $fechaFin);
    echo json_encode($info);
}

if ($opcion == 'guardar') {

    $datosPreset = json_decode($_REQUEST['arrDatosPreset']);
    $usuario = $datosPreset->usuario;
    $pwd = $datosPreset->pwd;
    $nombre_preset = $datosPreset->nombre;
    $id_estacion = $datosPreset->id_estacion;
    $tags_colores = $datosPreset->tags_colores;

    $resultado = $db->guardarPreset($usuario, $pwd, $nombre_preset, $id_estacion, $tags_colores);

    echo $resultado;
}

if ($opcion == 'leerPresets') {
    $datos = json_decode($_REQUEST['arrdatos']);
    $n_usuario = $datos->nombre;
    $pwd = $datos->pwd;
    $id_usuario = $db->obtenerIdUsuario($n_usuario, $pwd);
    if ($id_usuario) {
        $presets = $db->leerPresets($id_usuario);
        if ($_GET['para'] == 'mostrar') {

            $res = "";
            foreach ($presets as $index => $datos) {
                $nombre_preset = substr($datos['configuracion'], 0, strpos($datos['configuracion'], '@'));
                $res .= "<option value='" . $nombre_preset . "'>" . $nombre_preset . "</option>";
            }
            echo $res;
        }
        if ($_GET['para'] == 'cargar') {
            echo json_encode($presets);
        }
    }
}

if($opcion == 'borrar'){
    $datos = json_decode($_REQUEST['arrdatos']);
    $usuario = $datos->nombre;
    $pwd = $datos->pwd;

    $id_usuario = $db->obtenerIdUsuario($usuario, $pwd);
    if($id_usuario){
        $preset = $_GET['preset'];
        $db->borrarPreset($preset, $id_usuario);
    
    }

   
}
