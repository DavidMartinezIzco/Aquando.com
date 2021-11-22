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
    private $inactividadMax = 900;
    private $vidaSesión = null;

    public function __construct()
    {
        $this->sesion = \Config\Services::session();
        $this->sesion->start();
        
    }

    private function comprobarSesion(){
        
        if(!isset($_SESSION['timeout'])){
            $_SESSION['timeout'] = 0;
        }
        $this->vidaSesión = time() - $_SESSION['timeout'];
        if($this->vidaSesión < $this->inactividadMax) {
            session_destroy();
            
         }
         $_SESSION['timeout']=time();
    }

    public function index()
    {
        
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
    public function pruebaConexion()
    {
        $this->comprobarSesion();
        $_SESSION['seccion'] = "conexion";
        if (!empty($this->sesion->get('estaciones'))&& !empty($this->sesion->get('serverInfo'))) {
            $datos['estaciones'] = $this->sesion->get('estaciones');
            $datos['serverInfo'] = $this->sesion->get('serverInfo');
        } 
        else {
            if (isset($_SESSION['nombre'])) {

                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
                $conexion = new Conexion($this->usuario->getAuthAcc(), $this->usuario->getContrasena(), $this->usuario->getAuthPass());
                $datos["estaciones"] = $conexion->mostrarOnlineAPI();
                $datos["serverInfo"] = $conexion->pruebaSQL();
                $this->sesion->set($datos);
            } else {
                $datos = ["estaciones" => array("error" => "error")];
            }
        }
        if (!empty($this->sesion->get('nombre'))) {
            $datos["infoUser"] = array(
                "nombre" => $this->sesion->get('nombre'),
                "pwd" => $this->sesion->get('pwd'),
                "acc" => $this->sesion->get('acc'),
                "pass" => $this->sesion->get('pass')
            );

        }
        return view('pruebaBD', $datos);
    }

    public function inicioSesion()
    {
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
                    return $this->index();
                }
            }
            catch (\Throwable $th) {
            }
            return view('inicioSesion');
        }
    }
    public function pruebaTR()
    {
        $this->comprobarSesion();
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

    public function pruebaGraficos()
    {
        $this->comprobarSesion();
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

    // public function pruebaAnalitico(){
    // }

    public function estacion(){
        
        if(isset($_SESSION['nombre'])){
            $_SESSION['seccion'] = "estacion";
            return view('estacion');
        }
        else {
            return view('inicio');
        }
    }

    public function graficas(){
        
        if(isset($_SESSION['nombre'])){
            $_SESSION['seccion'] = "graficos";
            return view('graficas');
        }
        else {
            return view('inicio');
        }

    }
    
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

    // public function informes(){

    // }

    // public function comunicaciones(){

    // }




}