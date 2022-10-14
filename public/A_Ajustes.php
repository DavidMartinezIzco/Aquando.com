<?php
require '../app/Database/DataWit.php';
$DW = new Datawit();
$opcion = $_POST['opcion'];
// $estacion;

//funcion provisional
if($opcion == "test"){
    $estado = $DW->estadoConex();
    echo $estado;
}

// if($opcion == "con"){
//     //[coger datos y params]
//     $consignas = $DW->obtenerConsignasWit();
//     echo json_encode($consignas);
// }

// if($opcion == "mod"){
//     //[coger params]
//     $nok = $DW->modificarConsignaWit();
//     if($nok){
//         echo "ok";
//     }else{
//         echo "error";
//     }
// }