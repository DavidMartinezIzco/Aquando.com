<?php
class Database
{
    private $host = "172.16.3.2";
    private $dbname = "Aquando";
    private $user = "postgres";
    private $password = "123456";
    private $conexion;


    public function __construct()
    {
    }

    private function conectar()
    {
        return $this->conexion = pg_connect("host=$this->host dbname=$this->dbname user=$this->user password=$this->password");
    }

    function consultaExitosa($resultado)
    {
        $nResuls = pg_num_rows($resultado);

        if ($nResuls != 0 || $nResuls != null) {
            return true;
        } else {
            return false;
        }
    }

    public function existeUsuario($nombre, $pwd, $id_cliente)
    {
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

    public function mostrarEstacionesCliente($nombre, $pwd)
    {
        if($this->conectar()){
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion WHERE usuarios.nombre ='$nombre' AND usuarios.password ='$pwd'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            } else {
                return array("error" => "error");
            }
        }
    }

    public function obtenerAlarmasCliente($nombre,$pwd)
    {
        if($this->conectar()){
            $estacionesCliente = $this->mostrarEstacionesCliente($nombre, $pwd);
            foreach ($estacionesCliente as $index => $estacion) {
                $alarmasEstacion[] = $this->obtenerAlarmasEstacion($estacion['id_estacion']);
            }
            return $alarmasEstacion;
        }
        else {
            return array("error" => "error");
        }
    }

    public function obtenerAlarmasEstacion($id_estacion)
    {
        if ($this->conectar()) {
            $consulta = "SELECT alarmas.fecha_origen, alarmas.fecha_restauracion, alarmas.estado, alarmas.ack_por, alarmas.valor_alarma, alarmas.valor_limite 
            FROM alarmas INNER JOIN estacion_tag ON alarmas.id_tag = estacion_tag.id_tag WHERE estacion_tag.id_estacion = '$id_estacion'";
            $resultado = pg_query($this->conexion, $consulta);
            if ($this->consultaExitosa($resultado)) {
                $alarmasEstacion = pg_fetch_all($resultado);
                return $alarmasEstacion;
            } else {
                return array("fecha_origen" => "error","fecha_restauracion" => "error", "estado" => "error", "akc_por" => "error", "valor_alarma" => "error", "valor_limite" => "error");
            }
        } else {
            return array("error" => "error");
        }
    }
}
