<?php

class Historico
{
    
    // DateTimeAPI Fecha de lectura del historico
    private $fechaHistorico;

    //Int canal del historico -> https://www.microcom.es/zeusapi-doc/v1.0.0/api-channels-enum.php
    private $idCanal;

    // Int Razon del historico -> https://www.microcom.es/zeusapi-doc/v1.0.0/api-reasons-enum.php
    private $razonHistorico;

    //valor del historico (puede ser cualquier cosa (creo))
    private $valorHistorico;

    public function __construct($fechaHistorico, $idCanal, $razonHistorico, $valorHistorico)
    {
        $this->fechaHistorico = $fechaHistorico;
        $this->idCanal = $idCanal;
        $this->razonHistorico = $razonHistorico;
        $this->valorHistorico = $valorHistorico;
    }



    /**
     * Get the value of fechaHistorico
     */ 
    public function getFechaHistorico()
    {
        return $this->fechaHistorico;
    }

    /**
     * Set the value of fechaHistorico
     *
     * @return  self
     */ 
    public function setFechaHistorico($fechaHistorico)
    {
        $this->fechaHistorico = $fechaHistorico;

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
     * Get the value of razonHistorico
     */ 
    public function getRazonHistorico()
    {
        return $this->razonHistorico;
    }

    /**
     * Set the value of razonHistorico
     *
     * @return  self
     */ 
    public function setRazonHistorico($razonHistorico)
    {
        $this->razonHistorico = $razonHistorico;

        return $this;
    }

    /**
     * Get the value of valorHistorico
     */ 
    public function getValorHistorico()
    {
        return $this->valorHistorico;
    }

    /**
     * Set the value of valorHistorico
     *
     * @return  self
     */ 
    public function setValorHistorico($valorHistorico)
    {
        $this->valorHistorico = $valorHistorico;

        return $this;
    }
}


?>