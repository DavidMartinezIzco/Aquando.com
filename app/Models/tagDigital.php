<?php
class tagDigital extends TagSQL{

    //los tags digitales solo guardan booleans
    //algunos se historizan y otros no
    //las logicas de los metodos van a ser distintas dependiendo de como se obtengan las distintas cosas
    private $valor;
    private $calidad;
    private $timeStamp;
    private $metaData;
    private $canal;
    private $idEstacion;

    public function __construct($canal, $idEstacion)
{
    $this->canal = $canal;
    $this->idEstacion = $idEstacion;

}
    //estas funciones pasaran por Database cuando estén listas

    //obtener el valor del Tag de la BD
    public function actualizar(){

        //el metodo esta sin terminar aun
        //llamará a Database->obtenerInfoTag()
        //devolver true o false por si acaso
    }

    //Dar un nuevo valor a un elemento en la BD
    public function modificarTag($nuevoValor){

        //el metodo esta sin terminar aun
        //llamará a Database->establecerTag()
        //devolver true o false por si acaso



    }

}
?>