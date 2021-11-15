<?php

class Alarma
{

    //String Zeus id de la estacion
    private $idEstacion;
    
    //String con fecha en formato: dd-mm-yyyy;hh:mm:ss:fff
    private $fechaAlarma;

    //Int con la razon de la alarma: https://www.microcom.es/zeusapi-doc/v1.0.0/api-reasons-enum.php
    private $razonAlarma;

    //Int ID del canal
    private $idCanal; 

    //String Mensaje de la alarma
    private $textoAlarma;

    //String un codigo de zeus, no hace falta para crearlos (se deja "")
    private $valorCrudo;

    public function __construct($idEstacion, $fechaAlarma, $razonAlarma, $idCanal,$textoAlarma, $valorCrudo)
    {
        $this->idEstacion = $idEstacion;
        $this->$fechaAlarma = $fechaAlarma;
        $this->$razonAlarma = $idCanal;
        $this->$idCanal = $idCanal;
        $this->$textoAlarma = $textoAlarma;
        $this->$valorCrudo = $valorCrudo;
    }

    /**
     * Get the value of idEstacion
     */ 
    public function getIdEstacion()
    {
        return $this->idEstacion;
    }

    /**
     * Set the value of idEstacion
     *
     * @return  self
     */ 
    public function setIdEstacion($idEstacion)
    {
        $this->idEstacion = $idEstacion;

        return $this;
    }

    /**
     * Get the value of fechaAlarma
     */ 
    public function getFechaAlarma()
    {
        return $this->fechaAlarma;
    }

    /**
     * Set the value of fechaAlarma
     *
     * @return  self
     */ 
    public function setFechaAlarma($fechaAlarma)
    {
        $this->fechaAlarma = $fechaAlarma;

        return $this;
    }

    /**
     * Get the value of razonAlarma
     */ 
    public function getRazonAlarma()
    {
        return $this->razonAlarma;
    }

    /**
     * Set the value of razonAlarma
     *
     * @return  self
     */ 
    public function setRazonAlarma($razonAlarma)
    {
        $this->razonAlarma = $razonAlarma;

        return $this;
    }

    /**
     * Get the value of idCanal
     */ 
    public function getIdCanal()
    {
        return $this->idCanal;
    }

    /**
     * Set the value of idCanal
     *
     * @return  self
     */ 
    public function setIdCanal($idCanal)
    {
        $this->idCanal = $idCanal;

        return $this;
    }

    /**
     * Get the value of textoAlarma
     */ 
    public function getTextoAlarma()
    {
        return $this->textoAlarma;
    }

    /**
     * Set the value of textoAlarma
     *
     * @return  self
     */ 
    public function setTextoAlarma($textoAlarma)
    {
        $this->textoAlarma = $textoAlarma;

        return $this;
    }

    /**
     * Get the value of valorCrudo
     */ 
    public function getValorCrudo()
    {
        return $this->valorCrudo;
    }

    /**
     * Set the value of valorCrudo
     *
     * @return  self
     */ 
    public function setValorCrudo($valorCrudo)
    {
        $this->valorCrudo = $valorCrudo;

        return $this;
    }
}


?>