<?php

class TagAnalogico extends TagSQL{    
    //los tags analogicos son los que recogen datos varios
    //estos suelen historizarse pero no se si todos
    //las logicas de los metodos van a ser distintas dependiendo de como se obtengan las distintas cosas
    
    private $valor;
    private $canal;
    private $idEstacion;
    private $BD;

        public function __construct($canal, $idEstacion, $BD)
    {
        $this->BD = $BD;
        $this->canal = $canal;
        $this->idEstacion = $idEstacion;
    }

    //estas funciones pasaran por Database cuando estén listas

    //obtener el valor del Tag de la BD
    public function actualizar(){
        //el metodo esta sin terminar aun
        //llamará a Database->obtenerInfoTag()
        //devolver true o false por si acaso
        
        //este metodo falla
        $this->valor = $this->BD->obtenerInfoTag($this->canal, $this->idEstacion);
        return $this->valor;
    }

    //Dar un nuevo valor a un elemento en la BD
    public function modificarTag($nuevoValor){

        //el metodo esta sin terminar aun
        //llamará a Database->modificarTag()
        //devolver true o false por si acaso
        return $this->BD->modificarTag($this, $nuevoValor);
    
    }
}
?>