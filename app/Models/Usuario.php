<?php

require(APPPATH . "Database/Database.php");


class Usuario{
    
    private $nombre;
    private $contrasena;
    private $cliente;
    private $DB;

    public function __construct($nombre, $contrasena, $cliente)
    {
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;
        $this->cliente = $cliente;

        //falta: cambiar a nueva BD
        $this->DB = new Database($this->nombre, $this->contrasena);
    }

    public function existeUsuario(){
        try {
            return $this->DB->existeUsuario($this->nombre, $this->contrasena, $this->cliente);
        } catch (\Throwable $th) {   
        }
    }

    public function obtenerEstacionesUsuario(){
        try{
            return $this->DB->mostrarEstacionesCliente($this->nombre, $this->contrasena);
        }
        catch(Throwable $e){   
        }
    }

    // public function obtenerAlarmasEstacion($id_estacion, $fechaInicio, $fechaFin){

    // }

    public function obtenerUltimaInfoEstacion($id_estacion){
        try{
            return $this->DB->datosEstacion($id_estacion);
        }
        catch(Throwable $e){
            return $e;
        }
    }

    public function obtenerHistoricosEstacion($id_estacion, $fechaInicio, $fechaFinal){
        if($fechaFinal == null){
            $fechaFinal = "";
        }
        else if($fechaInicio == null){
            $fechaInicio = "";
        }
        try {
            return $this->DB->historicosEstacion($id_estacion, $fechaInicio, $fechaFinal);
        } catch (\Throwable $th) {
            return $th;
        }

    }

    public function obtenerTagsEstaciones(){

        $estaciones = $this->obtenerEstacionesUsuario();
        $tagsEstacion = array();
        foreach ($estaciones as $estacion) {
            $id_estacion = $estacion['id_estacion'];
            $tagsEstacion[$estacion['id_estacion']] = $this->DB->tagsEstacion($id_estacion);
        }
        return $tagsEstacion;
    }




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

    /**
     * Get the value of cliente
     */ 
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }
}
