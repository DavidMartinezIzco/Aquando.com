<?php

require APPPATH."Models/TagAnalogico.php";

class TagSQL extends Tag{

    private $valor;
    private $calidad;
    private $timeStamp;
    private $metaData;
    private $canal;
    private $idEstacion;
    private $BD;

    public function __construct($canal, $idEstacion, $conexion, $BD)
    {
        $this->BD = $BD;
        $this->canal = $canal;
        $this->idEstacion = $idEstacion;
        $this->actualizar();
    }

    public function modificarTag($nuevoValor){
        $tagAnalogico = new TagAnalogico($this->canal, $this->idEstacion, $this->DB);
        return $tagAnalogico->modificarTag($nuevoValor);
    }

    public function actualizar(){
        //no se si va a necesitar parametros aun
        //este metodo es distinto en todos los Tags
        //dejar vacio
        $tagAnalogico = new TagAnalogico($this->canal, $this->idEstacion, $this->BD);

        $datosTag = $tagAnalogico->actualizar();

       

        $this->valor = $datosTag["valor"];
        $this->metaData["nombre"] = $datosTag["nombre"];
        $this->metaData["unidad"] = $datosTag["unidad"];
        $this->metaData["tipo"] = $datosTag["tipo"];
        return $datosTag;
        
    }

    public function historizable(){
        //este metodo será común
    }

    public function tieneAlarmas(){
        //este metodo será común tambien
    }

    //-------------------------------------------------------------------------------------//

    public function getValor(){
        return $this->valor;
    }
    /**
     * Get the value of calidad
     */ 
    public function getCalidad()
    {
        return $this->calidad;
    }
    /**
     * Get the value of timeStamp
     */ 
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }
    /**
     * Get the value of metaData
     */ 
    public function getMetaData()
    {
        return $this->metaData;
    }
    /**
     * Get the value of canal
     */ 
    public function getCanal()
    {
        return $this->canal;
    }
    /**
     * Get the value of idEstacion
     */ 
    public function getIdEstacion()
    {
        return $this->idEstacion;
    }
}
?>