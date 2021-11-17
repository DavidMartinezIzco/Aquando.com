<?php


require_once (APPPATH."Database/Database.php");
require (APPPATH."Models/Tag.php");



//hay un monton de funciones que están por si en algun momento les sacamos
//uso pero si eso las llamaremos desde las funciones del Usuario
class Conexion{

    //para ir por SQL
    private $BD;

    //para ir por Zeus
    private $conexionAPI;

    //credenciales de conexion
    private $credenciales;

    public function __construct($authAcc, $contrasena, $authPass)
    {
        /**Todos los metodos de esta clase seguramente los tendremos que cambiar
         * si no se apoyan en alguna otra clase (ej: usuario) para trabajar con
         * sus resultados
        */
        
        //Estas crendenciales dependerán del usuario
        $this->credenciales = [
            "ip"=>"172.16.3.2",
            "puerto" => 3030,
            "ssl" => false,
            "authAccount" => $authAcc,
            "password" => $contrasena,
            "authPass"=>$authPass
        ];
        $this->conectarBDAPI();
        $this->conectarBDSQL();
    }

    //conecta con la Base de Datos
    //tambien como usa las credenciales del usuario, se puede usar para 
    //el asunto del log-in
    private function conectarBDAPI(){
        $conexion = $this->credenciales;
        $ip = $conexion["ip"];
        $puerto = $conexion["puerto"];
        $ssl = $conexion["ssl"];
        $authAcc = $conexion["authAccount"];
        $pass = $conexion["password"];
        $authPass = $conexion["authPass"];
        try{
            $this->conexionAPI = new Client($ip, $puerto, $ssl, $authAcc, $pass, $authPass);
        } 
        catch(Exception $e) {
            echo 
            '<script language="javascript">
            alert("comprueba tu conexion");
            </script>';
            
        }
        if($this->conexionAPI->IsCreated() == false)
        {
            return false;
        }
        return true;
    }

    // conecta a la BD directamente en SQL Server
    public function conectarBDSQL(){
        $this->BD = new Database;
    }

    public function pruebaSQL(){
        return $this->BD->pruebaSQL();
    }

    public function pruebaObetenerTag($estacion, $canal){
        //Los params seran cosa distinta
        $tag = new Tag($canal, $estacion, "", $this->BD);
        return $tag->actualizar();
    }


    //prueba para conectar a la base de Datos a traves de Zeus
    public function pruebaDBAPI(){
        if($this->conexionAPI->IsCreated()){
            return true;
        }
        else {
            return false;
        }
    }

    //devuelve array con IDs de las estaciones en linea
    public function mostrarOnlineAPI(){
        $estaciones = $this->conexionAPI->Stations();
        if(!empty($estaciones)){
            return $estaciones;
        }
        else{
            return array("error"=>"error");
        }
    }

    //devuelve array con IDs de las estaciones
    public function mostrarEstacionesAPI(){
        $datos = $this->conexionAPI->Stations();
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }

    //devuelve un Objeto Estacion
    public function obtenerUltimosValoresAPI($idEstacion){
        $datos = $this->conexionAPI->GetLastKnownValues($idEstacion);
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }

    // //devuelve un array de objetos Estacion
    // public function obtenerTodosUltimosValoresAPI(){
    //     $datos = $this->conexionAPI->GetAllStationLastKnownValues();
    //     if(!empty($datos)){
    //         return $datos;
    //     }
    //     return array("error"=>"error");
    // }

    // // $valores es un array con: 
    // //          lastConnectionTime. DateTimeAPI. Timestamp of values (UTC).
    // //          RSSI. Integer. Received Signal Strength Indication (RSSI).
    // //          PowerSupply. String. Can be an Integer with Voltage or a String with "green" (good condition), "orange" (must replace batteries in 3 months) or "red" (must replace batteries within current month).
    // //         values. Dictionary(Of String, Single). Every key corresponding to Channels Enumeration.
    // public function establecerUltimosValoresAPI($idEstacion, $valores){
    //     if($this->conexionAPI->SetLastKnownValues($idEstacion, $valores)){
    //         return true;
    //     }
    //     return false;
    // }   

    //las fechas son en DateTimeAPI
    //los canales van en un array de Ints
    //devuelve objeto Historico
    public function obtenerHistoricos($idEstacion, $fechaInicio, $fechaFin, $canales){
        $datos = $this->conexionAPI->GetHistorical($idEstacion, $fechaInicio, $fechaFin, $canales);
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }

    //$valores es array con DateOfRecord-ChannelID-Reason-Value
    //crea el objeto Historico y no hace falta pasarlo
    public function establecerHistoricos($idEstacion, $valores){
        if($this->conexionAPI->SetHistorical($idEstacion, $valores)){
            return true;
        }
        return false;
    }
    // devuelve un array asociativo con las propiedades de la estacion
    public function obtenerPropiedadesEstacion($idEstacion){
        $datos = $this->conexionAPI->GetStationExtendedProperties($idEstacion);
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }
    // devuelve un array asociativo con arrays de la funcion anterior
    public function obtenerTodasPropiedadesEstacion(){
        $datos = $this->conexionAPI->GetAllStationExtendedProperties();
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }

    //$destino es un String con ZeusID/num Telefono (si $sms es true)
    //$mensaje es un string con el mensaje
    //$sms es un boolean para mandar a la estacion/al movil
    public function enviarMensaje($destino, $mensaje, $sms){
        if($this->conexionAPI->SendMessage($destino, $mensaje, $sms)){
            return true;
        }
        return false;
    }

    //devuelve un array asi: [item 1 -> Key:"station b", Value:"OUT1=1"]
    public function obtenerMensajesPendientes(){
        $datos = $this->conexionAPI->PendingMessages();
        if(!empty($datos)){
            return $datos;
        }
        return array("error"=>"error");
    }

    //Las fechas son tipo DateTimeAPI
    //$desde es opcional con valor String
    public function obtenerAlarmas($fechaInicio, $fechaFin, $desde){
        $this->conectarBDSQL();
        $this->conectarBDAPI();
        $estaciones = $this->mostrarEstacionesAPI();
        foreach ($estaciones as $index => $estacion) {
            if ($index != 0) {
                $alarmas[] = $this->BD->obtenerAlarmasEstacion($estacion, $fechaInicio, $fechaFin, $desde, null);
            }
        }

        return $alarmas;
    }

    //$alarmas es un array de objetos alarma
    public function establecerAlarmas($alarmas){
        if($this->conexionAPI->setAlarm($alarmas)){
            return true;
        }
        return false;
    }

}



?>