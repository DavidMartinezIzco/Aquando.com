<?php

// para conexiones hacia WIT en SQL Server
// no hará gran cosa, es para recoger/modificar consignas en algunas estaciones

//SALTAR SSL
//NO ENCRIPTAR
//SI QUEREMOS ENCRIPTAR Y FUNCIONAR POR SSL HAY QUE ACTUALIZAR SQL SERVER A 2008+
//DRIVERS SQLSRV Y PDO_SQLSRV v17+

class Datawit
{
    private $nombre_server = "tcp:172.16.4.2,1433";
    private $conexion;
    private $info_server;
    //dbname, uid, pwd, puerto, direccion...



    public function __construct()
    {
    }
    //CONEXION A DBEASY452 
    //AQUI ESTÁN LOS VALORES DE LAS CONSIGNAS
    private function conectar()
    {
        $this->info_server = array("Database" => "DBEASY452", "Uid" => "sa", "PWD" => "dateando", "Encrypt" => false);
        $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
        if ($this->consultaExitosa($stmt)) {
            return $this->conexion = $stmt;
        } else {
            return false;
        }
    }
    //CONEXION AUXILIAR A Conversion_Aquando
    //AQUI ESTAN EL NEXO ENTRE LAS ESTACIONES Y LAS REFERENCIAS A TAGS EN DBEASY
    private function conectarAux()
    {
        $this->info_server = array("Database" => "Conversion_Aquando", "Uid" => "sa", "PWD" => "dateando", "Encrypt" => false);
        $stmt = sqlsrv_connect($this->nombre_server, $this->info_server);
        if ($this->consultaExitosa($stmt)) {
            return $this->conexion = $stmt;
        } else {
            return false;
        }
    }

    //COMPRUEBA LOS RESULTADOS
    //SE USA ANTES DE DEVOLVER LA INFO
    private function consultaExitosa($stmt)
    {
        if ($stmt) {
            return true;
        } else {
            print_r(sqlsrv_errors()); //provisional
            return false;
        }
    }

    //TESTEO DE LA CONEXION
    //YA NO SE USA
    public function estadoConex()
    {
        if (!($this->conectar())) {
            return "desconectado";
        } else {
            return "conectado";
        }
    }

    //LISTA LAS CONSIGNAS DISPONIBLES DE UNA ESTACION DADO SU NOMBRE
    public function consignasEstacion($estacion)
    {
        if ($this->conectarAux() && $estacion != "Deposito Berroa") {
            $consulta = "SELECT * FROM Info_lkv where estacion like('%" . $estacion . "%') AND nombre_tag like ('%Consigna%')";
            // $params = array($estacion);
            $respuesta = sqlsrv_query($this->conexion, $consulta);
            if ($this->consultaExitosa($respuesta)) {
                $datos = array();
                while ($fila = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC)) {
                    $datos[] = $fila;
                }
                return $datos;
            }
        }
    }

    //LEE LOS VALORES DE UN TAG DADA SU REFERENCIA
    public function leerConsignaWIT($recurso)
    {
        if ($this->conectar()) {
            $consulta = "SELECT * FROM [DBEASY452].[dbo].[WValue] WHERE ValueWOSAdd LIKE('%" . $recurso . "%')";
            $respuesta = sqlsrv_query($this->conexion, $consulta);
            if ($this->consultaExitosa($respuesta)) {
                $datos = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC);
                return $datos;
            }
        }
    }

    //ESTA SIN HACER TODAVIA
    public function modificarConsignaWit($ref, $valor) //habra que meter params (estacion, tag, consigna, valor etc)
    {
        if ($this->conectar()) {
            $conConsignas = "UPDATE [DBEASY452].[dbo].[WValue] SET ValueReadData = '" . $valor . "', ValueWriteStatus = 10 WHERE ValueWOSAdd LIKE('%" . $ref . "%')";
            $params = array();
            $stmt = sqlsrv_query($this->conexion, $conConsignas, $params);
            if ($this->consultaExitosa($stmt)) {
                sqlsrv_free_stmt($stmt);
                return 'update';
            }
        }
        return 'error';
    }
}
