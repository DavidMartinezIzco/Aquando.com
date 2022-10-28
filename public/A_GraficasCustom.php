<?php
require_once '../app/Database/Database.php';
require '../app/Models/Validador.php';

$db = new Database();
$vlr = new Validador();
$opcion = $_POST['opcion'];
//obtiene los historicos de un tag en un intervalo de fechas determinado
if ($opcion == 'tag') {
    $id_estacion = $_POST['estacion'];
    $id_tag = $_POST['id_tag'];
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $meta = $_POST['meta'];
    $ajustesMeta = explode("/", $meta);

    //EXPERIMENTAL: VALIDAR FECHAS
    if ($vlr->valFecha($fechaIni) && $vlr->valFecha($fechaFin)) {
        $info = $db->historicosTagEstacionCustom($id_estacion, $id_tag, $ajustesMeta, $fechaIni, $fechaFin);
        echo json_encode($info);
    } else {
        echo json_encode("fechas no validas");
    }
}
//guarda un preset con la configuracion de tags y colores seleccionados
if ($opcion == 'guardar') {
    $datosPreset = json_decode($_POST['arrDatosPreset']);
    //EXPERIMENTAL: VALIDAR NOMBRE DEL PRESET --> falla
    // if ($vlr->valTextoGen($datosPreset->nombre)) {
        $usuario = $datosPreset->usuario;
        // $pwd = $datosPreset->pwd;
        $nombre_preset = $datosPreset->nombre;
        $id_estacion = $datosPreset->id_estacion;
        $tags_colores = $datosPreset->tags_colores;
        $resultado = $db->guardarPreset($usuario, $nombre_preset, $id_estacion, $tags_colores);
    // } else {
    //     $resultado = "nombre del preset no válido";
    // }

    echo $resultado;
}
//muestra una lista con los presets guardados por el usuario
if ($opcion == 'leerPresets') {
    $datos = json_decode($_POST['arrdatos']);
    $n_usuario = $datos->nombre;
    // $pwd = $datos->pwd;
    $id_usuario = $db->obtenerIdUsuario($n_usuario);
    if ($id_usuario) {
        $presets = $db->leerPresets($id_usuario);
        if ($_POST['para'] == 'mostrar') {
            $res = "";
            foreach ($presets as $index => $datos) {
                $nombre_preset = substr($datos['configuracion'], 0, strpos($datos['configuracion'], '@'));
                $res .= "<option value='" . $nombre_preset . "'>" . $nombre_preset . "</option>";
            }
            echo $res;
        }
        if ($_POST['para'] == 'cargar') {
            echo json_encode($presets);
        }
    }
}
//elimina un preset
if ($opcion == 'borrar') {
    $datos = json_decode($_POST['arrdatos']);
    $usuario = $datos->nombre;
    // $pwd = $datos->pwd;
    $id_usuario = $db->obtenerIdUsuario($usuario);
    if ($id_usuario) {
        $preset = $_GET['preset'];
        $db->borrarPreset($preset, $id_usuario);
    }
}
