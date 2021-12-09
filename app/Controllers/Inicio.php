<?php

namespace App\Controllers;

require(APPPATH . "Database/Conexion.php");
require(APPPATH . "Models/Usuario.php");
require(APPPATH . "Libraries/ZeusApi.php");

use Conexion;
use Usuario;


class Inicio extends BaseController
{

    private $usuario;
    private $sesion;

    public function __construct(){
        $this->sesion = \Config\Services::session();
        $this->sesion->start();
        
    }

    //ejecución de arranque del proyecto
    public function index(){
        
        if(isset($_GET['log']) && $_GET['log'] == 'out'){
            $_SESSION['seccion'] = "login";
            return $this->inicioSesion();

        }

        else {
            $_SESSION['seccion'] = "inicio";
            if(isset($_SESSION['nombre'])){
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
                $conexion = new Conexion($this->usuario->getAuthAcc(), $this->usuario->getContrasena(), $this->usuario->getAuthPass());
                $_SESSION['estaciones'] = $conexion->mostrarOnlineAPI();
                return view('principal');
            }
            else{
                return view('inicio');
            }
        }
        
        
    }

    //inicia sesion usando las credenciales de zeus
    //habrá que cambiar este sistema en el futuro
    public function inicioSesion(){
        $_SESSION['seccion'] = "login";
        if (isset($_SESSION['nombre'])) {
            session_unset();
            
        }
        $nombre = "";
        $contra = "";
        $authacc = "";
        $authPass = "";

        if (!empty($_POST)) {
            try {
                $nombre = $_POST["txtNombre"];
                $contra = $_POST["txtContrasena"];
                $authacc = $_POST["txtAuthAccount"];
                $authPass = $_POST["txtAuthPass"];

                $this->usuario = new Usuario($nombre, $contra, $authacc, $authPass);
                if (!$this->usuario->existeUsuario()) {
                    echo '<script language="javascript">';
                    echo 'alert("Datos Incorrectos")';
                    echo '</script>';
                    return view('inicioSesion');
                } else {
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['pwd'] = $contra;
                    $_SESSION['acc'] = $authacc;
                    $_SESSION['pass'] = $authPass;
                    $_SESSION['seccion'] = "inicio";
                    $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
                    $conexion = new Conexion($this->usuario->getAuthAcc(), $this->usuario->getContrasena(), $this->usuario->getAuthPass());
                    $_SESSION['estaciones'] = $conexion->mostrarOnlineAPI();
                    return view('principal');
                }
            }
            catch (\Throwable $th) {
            }
            return view('inicioSesion');
        }
        return view('inicioSesion');

    }

    //caca
    public function cafe(){
        return view('café');
    }

    // (obsoleto) menu debug para Ajax
    public function pruebaTR(){
        
        $_SESSION['seccion'] = "tr";
        $conexion = null;
        
        if (isset($_SESSION['nombre'])) {
            $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
            $_SESSION['usuario'] = $this->usuario;
            $conexion = new Conexion($_SESSION['acc'], $_SESSION['pwd'], $_SESSION['pass']);
            $datos['estaciones'] = $conexion->mostrarOnlineAPI(); //saca las estaciones
            $_SESSION['estaciones'] = $datos['estaciones'];
        }
        
        $datos['estaciones'] = $conexion->mostrarOnlineAPI();
        return view('pruebaTR', $datos);
    }


    //(obsoleto) muestra las pruebas de repren de graficos
    public function pruebaGraficos(){
        $_SESSION['seccion'] = "graficos";
        if (!empty($this->sesion->get('nombre'))) {
            $datos["infoUser"] = array(
                "nombre" => $this->sesion->get('nombre'),
                "pwd" => $this->sesion->get('pwd'),
                "acc" => $this->sesion->get('acc'),
                "pass" => $this->sesion->get('pass')
            );
            return view('pruebaGraficos', $datos);
        }
        return view('pruebaGraficos');
    }

    // nunca llegó a ver la luz
    // public function pruebaAnalitico(){
    // }

    //muestra la vista de las estaciones
    public function estacion(){
        
        if(isset($_SESSION['nombre'])){
            $_SESSION['seccion'] = "estacion";
            return view('estacion');
        }
        else {
            return view('inicio');
        }
    }

    //muestra la vista de graficas (historicos y demas)
    public function graficas(){
        if(isset($_SESSION['nombre'])){
            $_SESSION['seccion'] = "graficos";
            return view('graficas');
        }
        else {
            return view('inicio');
        }

    }
    
    //muestra la zona principal de alarmas
    public function alarmas(){
        
        if(isset($_SESSION['nombre'])){
            $_SESSION['seccion'] = "alarmas";
            if (isset($_SESSION['alarmas'])) {
                $datos['alarmas'] = $_SESSION['alarmas'];
            }
            else {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
                //formato fecha: aaaa-mm-dd hh:mm:ss.000
                //alarmas desde principio de año
                $alarmas = $this->usuario->conseguirAlarmas(null, null, "2021-01-01 00:00:01.000");
                $datos['alarmas'] = $alarmas;
            }
            
            return view('alarmas', $datos);
        }

        else {
            return view('inicio');
        }
    }

    //muestra la zona de informes
    public function informes(){
        $_SESSION['seccion'] = "infos";
        return view('informes');
    }

    //muestra el estado de las conexiones con las estaciones
    public function comunicaciones(){
        $_SESSION['seccion'] = "coms";
        if(isset($_SESSION['nombre'])){
            return view('comunicaciones');
        }
        else {
            return view('inicio');
        }
    }   




}