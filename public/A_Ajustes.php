<?php
require '../app/Database/DataWit.php';
$DW = new Datawit();
$opcion = $_POST['opcion'];

//muestra lista de consignas
if($opcion == "con"){
    $estacion = $_POST['estacion'];
    $dump = $DW->consignasEstacion($estacion);
    echo json_encode($dump);
}

//lee valores de una consigna
if($opcion == "dis"){

}

//modifica una consigna
// if($opcion == "mod"){
//     //[coger params]
//     $nok = $DW->modificarConsignaWit();
//     if($nok){
//         echo "ok";
//     }else{
//         echo "error";
//     }
// }