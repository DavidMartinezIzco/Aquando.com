<?php

require(APPPATH . "Database/Database.php");


class Usuario{
    
    private $nombre;
    private $contrasena;
    private $DB;

    public function __construct($nombre, $contrasena)
    {
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        //falta: cambiar a nueva BD
        $this->DB = new Database($this->nombre, $this->contrasena);
    }

    public function existeUsuario(){

        return $this->DB->existeUsuario($this->nombre, $this->contrasena);
       
    }

    public function obetenerEstacionesUsuario(){
        return $this->DB->mostrarEstacionesCliente($this->nombre, $this->contrasena);
    }

    // public function obtenerPropiedadesEstacion($estacion){
        
    // }

    // public function obtenerUltimosDatosEstacion($estacion){
        
    // }


    // public function comprobarSQL(){
        
    // }

    // public function pruebaTag($estacion, $canal){

    // }


    // public function conseguirAlarmas($fechaInicio, $fechaFin, $desde){

       


    // }


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
