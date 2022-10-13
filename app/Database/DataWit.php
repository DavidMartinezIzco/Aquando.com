<?php

class Datawit{

private $nombre_server;
private $conexion;
private $info_server;

public function __construct(){
    $this->info_server = array("Database"=>"dbName","UID"=>"nombre_usuario","PWD"=>"constraseña");
}

private function conectar(){
    $this->conexion = sqlsrv_connect($this->nombre_server,$this->info_server);
    if($this->conexion){
        return true;
    }else{
        print_r(sqlsrv_errors(),true);
        return false;
    }
}

private function consultaExitosa(){}

public function obtenerConsignasWit(){}

public function modificarConsignaWit(){}





}