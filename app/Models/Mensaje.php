<?php

class Mensaje
{
    //String con o el id de la estacion o un num de telefono
    private $destino;

    //String con el texto del mensaje
    private $textoMensaje;

    //Boolean true:mensaje al telefono | False:mensaje a la estacion
    private $sms;

    public function __construct($destino, $textoMensaje, $sms)
    {
        $this->destino = $destino;
        $this->textoMensaje = $textoMensaje;
        $this->sms = $sms;
    }

    /**
     * Get the value of destino
     */ 
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set the value of destino
     *
     * @return  self
     */ 
    public function setDestino($destino)
    {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get the value of textoMensaje
     */ 
    public function getTextoMensaje()
    {
        return $this->textoMensaje;
    }

    /**
     * Set the value of textoMensaje
     *
     * @return  self
     */ 
    public function setTextoMensaje($textoMensaje)
    {
        $this->textoMensaje = $textoMensaje;

        return $this;
    }

    /**
     * Get the value of sms
     */ 
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Set the value of sms
     *
     * @return  self
     */ 
    public function setSms($sms)
    {
        $this->sms = $sms;

        return $this;
    }
}


?>