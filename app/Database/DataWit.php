<?php

// para conexiones hacia WIT en SQL Server
// no hará gran cosa, es para recoger/modificar consignas en algunas estaciones

//172.16.4.2
//:1443
// sa dateando

class Datawit
{
    //private $conInfo;
    // private $nombre_server = "172.16.4.2\\MSSQLSERVER, 1433";
    private $nombre_server = "tcp:172.16.4.2,1433";
    private $conexion;
    private $info_server;
    //dbname, uid, pwd, puerto, direccion...

    public function __construct()
    {
        
        
    }

    private function conectar()
    {   
        $this->info_server = array("Database" => "DBEASY452", "Uid" => "sa", "PWD" => "dateando","Encrypt"=>false);
        // return sqlsrv_connect($this->nombre_server, $this->info_server);
        $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
        if ($this->consultaExitosa($stmt)) {
            return $this->conexion = $stmt;
        } else {
            return false;
        }
    }

    private function conectarAux()
    {
        $this->info_server = array("Database" => "Conversion_Aquando", "Uid" => "sa", "PWD" => "dateando","Encrypt"=>false);
        // return sqlsrv_connect($this->nombre_server, $this->info_server);
        $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
        if ($this->consultaExitosa($stmt)) {
            return $this->conexion = $stmt;
        } else {
            return false;
        }
    }

    public function consignasEstacion($estacion){
        if($this->conectarAux()){
            $consulta = "SELECT * FROM Info_lkv where estacion like('%".$estacion."%') AND nombre_tag like ('%Consigna%')";
            // $params = array($estacion);
            $respuesta = sqlsrv_query($this->conexion, $consulta);
            if($this->consultaExitosa($respuesta)){
                $datos = array();
                while($fila=sqlsrv_fetch_array($respuesta,SQLSRV_FETCH_ASSOC)){
                    $datos[] = sqlsrv_fetch_array($respuesta,SQLSRV_FETCH_ASSOC);
                }
                return $datos;
            }
        }
        
    }

    private function consultaExitosa($stmt)
    {
        if ($stmt) {
            return true;
        } else {
            print_r(sqlsrv_errors()) ; //provisional
            return false;
        }
    }

    public function estadoConex(){
        if(!($this->conectar())){
            return "desconectado";
        }else{
            return "conectado";
        }
    }

    public function obtenerConsignasWit() //probablemente habrá que incluir parametros (estacion)
    {
        if ($this->conectar()) {
            $result = null; //algo como [index][nombre_consigna, nombre_estacion, valor, estado]
            $conConsignas = "super código de búsqueda de consignas";
            $params = array();
            $stmt = sqlsrv_query($this->conexion, $conConsignas, $params);
            if ($this->consultaExitosa($stmt)) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $result[] = $row; //$row['nombre_columna'] para valores concretos
                }
                sqlsrv_free_stmt($stmt);
                return $result;
            }
        }
        return false;
    }

    public function modificarConsignaWit() //habra que meter params (estacion, tag, consigna, valor etc)
    {
        if ($this->conectar()) {
            $conConsignas = "super código de inserción";
            $params = array();
            $stmt = sqlsrv_query($this->conexion, $conConsignas, $params);
            if ($this->consultaExitosa($stmt)) {
                sqlsrv_free_stmt($stmt);
                return true;
            }
        }
        return false;
    }

    // public function cambiosPendientes() //no se si usare una funcion asi
    // {
    //     if ($this->conectar()) {
    //         $result = null; //algo como [nom_consigna,nom_estacion,estado]
    //         $conConsignas = "super código de búsqueda de cambios pendientes";
    //         $params = array();
    //         $stmt = sqlsrv_query($this->conexion, $conConsignas, $params);
    //         if ($this->consultaExitosa($stmt)) {
    //             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //                 $result[] = $row; //$row['nombre_columna'] para valores concretos
    //             }
    //             sqlsrv_free_stmt($stmt);
    //             return $result;
    //         }
    //     }
    //     return false;
    // }


}