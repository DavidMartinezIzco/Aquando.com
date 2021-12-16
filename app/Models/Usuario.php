<?php


class Usuario{

    private $nombre;
    private $contrasena;
    private $authAcc;
    private $authPass;
    private $DB;

    public function __construct($nombre, $contrasena, $authAcc, $authPass)
    {
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->authAcc = $authAcc;
        $this->authPass = $authPass;
        //falta: cambiar a nueva BD
        $this->DB = new Conexion($this->authAcc, $this->contrasena,$this->authPass);
    }

    public function existeUsuario(){

        if ($this->DB->pruebaDBAPI()) {
            return true;
        }
        return false;
    }

    public function obtenerPropiedadesEstacion($estacion){
        return $this->DB->obtenerPropiedadesEstacion($estacion);
    }

    public function obtenerUltimosDatosEstacion($estacion){
        return $this->DB->obtenerUltimosValoresAPI($estacion);
    }


    //funcion de prueba para las consultas SQL
    //devuelve caracteristicas de la BDD en pruebaBD.php
    public function comprobarSQL(){
        return $this->DB->pruebaSQL();
    }

    public function pruebaTag($estacion, $canal){

        $alarmas = $this->DB->pruebaObetenerTag($estacion, $canal);
        return $alarmas;
    }


    public function conseguirAlarmas($fechaInicio, $fechaFin, $desde){

        return $this->DB->obtenerAlarmas($fechaInicio, $fechaFin, $desde, null);


    }


    /**
     * Get the value of contrasena
     */ 
    public function getContrasena()
    {
        return $this->contrasena;
    }
    /**
     * Set the value of contrasena
     *
     * @return  self
     */ 
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
        return $this;
    }
    /**
     * Get the value of authAcc
     */ 
    public function getAuthAcc()
    {
        return $this->authAcc;
    }
    /**
     * Set the value of authAcc
     *
     * @return  self
     */ 
    public function setAuthAcc($authAcc)
    {
        $this->authAcc = $authAcc;
        return $this;
    }
    /**
     * Get the value of authPass
     */ 
    public function getAuthPass()
    {
        return $this->authPass;
    }
    /**
     * Set the value of authPass
     *
     * @return  self
     */ 
    public function setAuthPass($authPass)
    {
        $this->authPass = $authPass;
        return $this;
    }
    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }
}
