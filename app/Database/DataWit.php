<?php

// para conexiones hacia WIT en SQL Server
// no hará gran cosa, es para recoger/modificar consignas en algunas estaciones

//172.16.4.2
//:1443
// sa dateando

class Datawit
{
    private $nombre_server = "172.16.4.2:1443";
    private $conexion;
    private $info_server;
    //dbname, uid, pwd, puerto, direccion...

    public function __construct()
    {
        $this->info_server = array("Database" => "DBEASY452", "UID" => "sa", "PWD" => "dateando");
    }

    private function conectar()
    {
        $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
        if ($this->consultaExitosa($stmt)) {
            return $this->conexion = $stmt;
        } else {
            return false;
        }
    }

    private function consultaExitosa($stmt)
    {
        if ($stmt) {
            return true;
        } else {
            print_r(sqlsrv_errors(), true); //provisional
            return false;
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