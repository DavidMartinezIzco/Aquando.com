<?php
class Contador extends TagSQL{

    //los contadores no guardan datos reales que se muestren
    //son datos algo mas funcionales para nosotros
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
    public function setValor($valor,$nuevoValor){

        //el metodo esta sin terminar aun
        //llamará a Database->establecerTag()
        //devolver true o false por si acaso
    }

}
?>