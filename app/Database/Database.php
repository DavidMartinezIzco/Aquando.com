<?php
class Database{
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

    function consultaExitosa($resultado){
        $nResuls = pg_num_rows($resultado);

            if($nResuls != 0 || $nResuls != null){
                return true;
            }
            else {
                return false;
            }
    }

    public function existeUsuario($nombre, $pwd){

        if($this->conectar()){
            $consulta = "SELECT * FROM public.usuarios WHERE nombre ='$nombre' AND password ='$pwd'";
            $resultado = pg_query($this->conexion,$consulta);
            if($this->consultaExitosa($resultado)){
                return true;
            }
            else {
                
                return false;
            }
        }
    }


    public function mostrarEstacionesCliente($nombre , $pwd){
        if($this->existeUsuario($nombre, $pwd)){
            $consulta = "SELECT estaciones.nombre_estacion, estaciones.id_estacion FROM usuarios INNER JOIN usuario_estacion ON usuarios.id_usuario = usuario_estacion.id_usuario INNER JOIN estaciones ON usuario_estacion.id_estacion = estaciones.id_estacion WHERE usuarios.nombre ='$nombre' AND usuarios.password ='$pwd'";
            $resultado = pg_query($this->conexion,$consulta);
            if($this->consultaExitosa($resultado)){
                $estacionesArr = pg_fetch_all($resultado);
                return $estacionesArr;
            }
            else {
                echo "sin estaciones (?)";
            }
        }
    }


}
