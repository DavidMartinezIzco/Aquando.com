<?php

require (APPPATH."Models/TagSQL.php");
class Tag{

    private $BD;
    private $valor; 
    private $calidad;
    private $timeStamp;
    private $metaData; //las unidades?
    private $canal;
    private $idEstacion;
    private $conexion;


    public function __construct($canal, $idEstacion, $conexion, $BD)
    {
        $this->BD = $BD;
        $this->canal = $canal;
        $this->idEstacion = $idEstacion;
        $this->conexion = $conexion;
    }

    public function getValor(){

    }
    public function modificarTag($nuevoValor){
        $tagSQL = new TagSQL($this->canal, $this->idEstacion, $this->conexion, $this->BD);
        return $tagSQL->modificarTag($nuevoValor);
    }
    public function actualizar(){
        $tagSQL = new TagSQL($this->canal, $this->idEstacion, $this->conexion, $this->BD);
        return $tagSQL->actualizar();
    }

}
?>