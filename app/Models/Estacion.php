<?php

class Estacion
{

    private $nombreEstacion;
    private $zeusId;
    private $modelo;
    private $numSerie;
    private $coordenadas;
    private $grupo;
    private $ref1;
    private $ref2;
    private $nombresCanales;
    private $unidadesCanales;
    private $vistasCanales;
    private $valorCanales;

    public function __construct()
    {
        
    }

    public function getUltimosDatosEstacion(){
    
        
        
    }



    public function getPropiedadesEstacion(){

        $propiedades = array(
        $this->nombreEstacion,
        $this->zeusId,
        $this->modelo,
        $this->numSerie,
        $this->coordenadas,
        $this->grupo,
        $this->ref1,
        $this->ref2,
        $this->nombresCanales,
        $this->unidadesCanales,
        $this->vistasCanales,
        $this->valorCanales
        );
        return $propiedades;
    }





}


?>