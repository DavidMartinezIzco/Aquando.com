<?php
class Database{
    private $host;
    private $opciones;
    private $conexion;

    public function __construct(){        
        //falta: cambiar a nueva BD y rehacer todas las consultas
        
        $this->host = "172.16.3.2";
        $this->opciones = array("Database"=>"Zeus", "Uid"=>"sa", "PWD"=>"dateando","CharacterSet"=>"UTF-8");
        $this->conectar();
    }

    //parametros y logica provisional
    public function conectar(){
    //falta: cambiar a nueva BD
        
        $this->conexion = sqlsrv_connect($this->host, $this->opciones);
        if($this->conexion == false){
            echo '<script language="javascript">';
            echo 'alert("'.print_r(sqlsrv_errors()).'")';
            echo '</script>';
        }
        return $this->conexion;
    }

    //la dejo de momento aunque ya no la uso
    public function pruebaSQL(){
        $server_info = sqlsrv_server_info( $this->conexion);
        return $server_info;
    }

    // igual acabamos usando esto si hacemos un cargador de datos
    // y vamos cogiendo lo que interese sobre la marcha (una idea de gabriel)
    // public function obetenerTodoSobreUsuario($usuario){
    // }

    public function obtenerInfoTag($canal , $idEstacion){
        
        $sql = "SELECT canales_estaciones.tipo_canal, canales_estaciones.unidad_canal, canales_estaciones.ultimo_valor, canales_estaciones.nombre_canal
        FROM estaciones_cliente INNER JOIN canales_estaciones ON canales_estaciones.estacion = estaciones_cliente.estacion
        WHERE canales_estaciones.estacion = ? AND canales_estaciones.id_canal = ?
        GROUP BY canales_estaciones.tipo_canal, canales_estaciones.unidad_canal, canales_estaciones.ultimo_valor, canales_estaciones.nombre_canal;";

        $parametros = array($idEstacion, $canal);

        //$sql dependerá del usuario la estacion y el dato
        try {
            $consulta = sqlsrv_query($this->conexion, $sql, $parametros);
            
            if (!$consulta ) {
                return array("tipo"=>sqlsrv_errors(SQLSRV_ERR_ALL), "unidad"=>"error", "nombre"=>"error", "valor"=>"error");
            }
            else {
                if(!sqlsrv_has_rows($consulta)){
                    return array("tipo"=>"sin datos de la consulta", "unidad"=>"error", "nombre"=>"error", "valor"=>"error");
                }
                else{
                    while($row = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                        $resultado["datosTag"] = $row;
                    }
                    $datosTag = array("tipo"=>$resultado["datosTag"]["tipo_canal"], "unidad"=>$resultado["datosTag"]["unidad_canal"], "nombre"=>$resultado["datosTag"]["nombre_canal"], "valor"=>$resultado["datosTag"]["ultimo_valor"]);
                    return $datosTag;

                    print_r($resultado["datosTag"]);
                }
            }
            return array("tipo"=>sqlsrv_errors(SQLSRV_ERR_ALL), "unidad"=>"error", "nombre"=>"error", "valor"=>"error");
        }
        catch (Exception $e) {
            echo "error ultra pepino en la consulta: " . $e;
            return null;
        }
    }

    public function modificarTag($tag, $nuevoValor){

        //el metodo esta sin terminar aun
        $idEstacion = $tag->idEstacion;        
        $canal = $tag->canal;
        $sql = "UPDATE canales_estaciones SET ultimo_valor = ? WHERE id_canal = ? AND estacion = ?;";
        $params = array($nuevoValor, $canal, $idEstacion);
        $consulta = sqlsrv_query($this->conexion, $sql, $params);

        if($consulta != false){
            return $consulta;
        }
        return false;
        //--> devolver true o false por si acaso
    }

    public function obtenerAlarmasEstacion($estacion, $inicio,$fin, $desde, $filtro){

        //formato fecha: aaaa-mm-dd hh:mm:ss.000

        if(!is_null($desde)){
            $sql = "SELECT TOP 20 [Fecha],[Motivo],[Canal],[Dato] FROM [Zeus].[dbo].[SMS] WHERE [Estacion] = '".$estacion."'";
        }
        if(isset($filtro) && !is_null($filtro)){
            $sql .= $filtro;
        }

        $consulta = sqlsrv_query($this->conexion, $sql);
        
        if (!$consulta ) {
            $alarmas[] = array("Fecha"=>"error", "Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
            return $alarmas;
        }
        else {
            if(!sqlsrv_has_rows($consulta)){
                $alarmas[] = array("Fecha"=>"error", "Motivo"=>"error", "Canal"=>"error", "Dato"=>"error");
                return $alarmas;
            }
            else{
                while($alarmasDeEstacion = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
                    $alarmas[] = $alarmasDeEstacion;
                }
                return $alarmas;
            }
        }


    }

}
