<?php

require '../app/Database/Database.php';
$DB = new Database();
$opcion = $_GET['opcion'];
$id_estacion = $_GET['estacion'];
$tipo = $_GET['tipo'];

if($opcion == 'actualizar' && $tipo = 'todos'){
    
    try{
        echo json_encode($DB->datosEstacion($id_estacion, true));
    }
    catch(Throwable $e){
        echo $e;
    }

}

?>