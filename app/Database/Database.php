<?php
class Database
{
    private $host = "172.16.3.2";
    private $dbname = "Aquando";
    private $user = "postgres";
    private $password = "123456";
    private $conexion;


    public function __construct(){
    }

    private function conectar(){
        return $this->conexion = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->password");
    }

    public function obtenerIdUsuario($nombre, $pwd, $id_cliente){
        if ($this->conectar()) {
            $consulta = "SELECT id_usuario FROM usuarios WHERE nombre ='$nombre' AND password ='$pwd' AND id_cliente = '$id_cliente'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $id_usu = pg_fetch_all($resultado);
                return $id_usu;
            } else {
                return false;
            }
        }
    }

    function consultaExitosa($resultado){
        $nResuls = pg_num_rows($resultado);
        if ($nResuls != 0 || $nResuls != null) {
            return true;
        } else {
            return false;
        }
    }

    public function existeUsuario($nombre, $pwd, $id_cliente){
        if ($this->conectar()) {
            $consulta = "SELECT * FROM public.usuarios WHERE nombre ='$nombre' AND password ='$pwd' AND id_cliente = '$id_cliente'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function mostrarEstacionesCliente($nombre, $pwd){
        if($this->conectar()){
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion WHERE usuarios.nombre ='$nombre' AND usuarios.password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            } else {
                return false;
            }
        }
    }

    public function obtenerAlarmasUsuario($id_usuario, $orden, $sentido){
        $sentido = null;
        if($this->conectar()){
            $conAlarmas = "SELECT 
             alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.valor_alarma, alarmas.valor_limite 
             FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag INNER JOIN usuario_estacion ON usuario_estacion.id_estacion = estacion_tag.id_estacion
             WHERE usuario_estacion.id_usuario = ".$id_usuario[0]['id_usuario']." ORDER BY alarmas.fecha_origen";

             if($sentido != null){
                 $conAlarmas += $sentido;
             }

             $resulAlarmas = pg_query($conAlarmas);
            if($this->consultaExitosa($resulAlarmas)){
                $alarmas = pg_fetch_all($resulAlarmas);
                return $alarmas;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function obtenerAlarmasEstacion($id_estacion, $fechaInicio, $fechaFin){
        if($fechaInicio != null){
            //traducir fecha
        }
        if($fechaFin != null){
            //traducir fecha
        }
        if ($this->conectar()) {
            $consulta = "SELECT alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.valor_alarma, alarmas.valor_limite 
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = '$id_estacion' LIMIT 100";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $alarmasEstacion = pg_fetch_all($resultado);
                return $alarmasEstacion;
            }
        } else {
            return false;
        }
    }

    public function datosEstacion($id_estacion){

        if($this->conectar()){
                $ultimaFecha = "SELECT MAX(fecha) FROM datos";

                $consulta = "SELECT DISTINCT
                tags.nombre_tag,
                datos.fecha,
                datos.calidad,
                datos.valor_bool,
                datos.valor_int,
                datos.valor_float,
                datos.valor_acu,
                datos.valor_date
            FROM 
            datos INNER JOIN tags ON datos.id_tag = tags.id_tag
            INNER JOIN estacion_tag ON estacion_tag.id_tag = tags.id_tag
            WHERE estacion_tag.id_estacion = '$id_estacion' AND datos.fecha = ($ultimaFecha)
            ORDER BY tags.nombre_tag
            LIMIT 10
            ";
            $resultado = pg_query($this->conexion, $consulta);
            if($this->consultaExitosa($resultado)){
                $datosEstacion = pg_fetch_all($resultado);
                return $datosEstacion;
            }
            else {
                return "error";
            }
        }
    }

    public function tagsEstacion($id_estacion){
        if($this->conectar()){
            $conTags = "SELECT tags.id_tag, tags.nombre_tag FROM estacion_tag INNER JOIN tags ON tags.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = $id_estacion AND tags.historizar = true";
            $resulTags = pg_query($this->conexion, $conTags);
            if($this->consultaExitosa($resulTags)){
                $tagsEstacion = pg_fetch_all($resulTags);

                $_SESSION['tagsEstacion'] = $tagsEstacion;
                return $tagsEstacion;
            }
            else {
                return false;
            }
            return false;
        }
    }

    //para las fechas vamos a necesitar un traductor de Date() a TimeStamp()
    public function historicosEstacion($id_estacion, $fechaIni, $fechaFin){
        if($this->conectar()){

            $tagsEstacion = $this->tagsEstacion($id_estacion);
            $_SESSION['tagsEstacion'] = $tagsEstacion;
            if($tagsEstacion != false){

                foreach ($tagsEstacion as $index => $tag) {
                    if($tag['id_tag'] != 1){
                        $conHisto = "SELECT fecha, calidad, valor_bool, valor_int, valor_acu, valor_float, valor_string, valor_date FROM datos_historicos WHERE id_tag = ".$tag['id_tag']." ";
                        if($fechaIni != ""){
                            $conHisto .= " AND fecha >= $fechaIni ";
                        }
                        if($fechaFin != ""){
                            $conHisto .=  " AND fecha <= $fechaFin ";
                        }
                        $conHisto .= " ORDER BY fecha DESC LIMIT 100";

                        $resulHisto = pg_query($this->conexion, $conHisto);
                        if($this->consultaExitosa($resulHisto)){
                            $historicoTag = pg_fetch_all($resulHisto);
                            $historicos[$tag['nombre_tag']] = $historicoTag;                
                        }
                    }   
                }
                return $historicos;
            }
        }
        else{
            return false;
        }
    }

    public function historicosTagEstacion($id_estacion, $id_tag){
        if($this->conectar()){
            $conHistoTagEst = "SELECT datos_historicos.fecha, datos_historicos.calidad, datos_historicos.valor_bool, datos_historicos.valor_int, datos_historicos.valor_acu, datos_historicos.valor_float, datos_historicos.valor_string, datos_historicos.valor_date FROM datos_historicos INNER JOIN estacion_tag ON estacion_tag.id_tag = datos_historicos.id_tag WHERE estacion_tag.id_tag = ".$id_tag." AND estacion_tag.id_estacion = ".$id_estacion." ORDER BY datos_historicos.fecha DESC";
            $resulHistoTagEst = pg_query($this->conexion, $conHistoTagEst);
            if($this->consultaExitosa($resulHistoTagEst)){
                $datosHistoTagEst = pg_fetch_all($resulHistoTagEst);
                $datosHisto = array();
                foreach ($datosHistoTagEst as $index => $dato) {
                    foreach ($dato as $factor => $valor) {
                        if($valor != null){
                            $datosHisto[$index][$factor] = $valor;
                        }
                    }
                   
                }
                return $datosHisto;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

}
