<?php

namespace App\Controllers;


require(APPPATH . "Models/Usuario.php");
require(APPPATH . "Models/Contras.php");

use Contras;
use Usuario;

class Inicio extends BaseController
{
    private $usuario;
    private $sesion;

    public function __construct()
    {
        $this->sesion = \Config\Services::session();
        $this->sesion->start();
    }

    //arranque del proyecto
    public function index()
    {
        $_SESSION['seccion'] = 'inicio';
        if (isset($_GET['log']) && $_GET['log'] == 'out') {
            session_unset();
            $_SESSION['mensajeDesc'] = true;
            $_SESSION['seccion'] = '';
            return view('inicio');
        } else {
            if (isset($_SESSION['nombre'])) {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
                $_SESSION['nombre_cliente'] = $this->usuario->getCliente();
                $datos['estaciones'] = $this->usuario->obtenerEstacionesUsuario();
                $_SESSION['estaciones'] = $datos['estaciones'];
                $datos['estacionesUbis'] = $this->usuario->ultimasConexiones();
                $_SESSION['seccion'] = "prin";
                return view('principal', $datos);
            } else {
                session_unset();
                $_SESSION['seccion'] = "";
                $_SESSION['mensajeDesc'] = true;
                return view('inicio');
            }
        }
    }

    //inicia la sesion comprobando si existe un usuario con esas credenciales
    //debería cifrar esto cuando empiece a haber usuarios de verdad
    //sobretodo por que no me termino de fiar del AJAX pero no queda otra
    //con mala baba igual pueden decodificar las peticiones de REST
    public function inicioSesion()
    {
        $_SESSION['seccion'] = "login";
        if (isset($_SESSION['nombre'])) {
            session_unset();
        }
        $_SESSION['mensajeDesc'] = false;
        $nombre = "";
        $pwd = "";
        if (isset($_POST["txtNombre"]) && isset($_POST["txtContrasena"])) {
            $nombre = $_POST["txtNombre"];
            $pwd = $_POST["txtContrasena"];
            $this->usuario = new Usuario($nombre, $pwd);
            if ($this->usuario->existeUsuario() == true) {
                //mirar contra y eso
                $hpwd = password_hash($pwd, PASSWORD_DEFAULT, array("cost" => 14));
                echo "console.log(" . $hpwd . ")";
                $id_usu = $this->usuario->obtenerIdUsuario($nombre, $pwd);
                $conSys = new Contras($id_usu);
                if ($conSys->loginUsuario($hpwd)) {
                    $this->usuario->obtenerEstacionesUsuario();
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['pwd'] = $pwd;
                    return $this->index();
                } else {
                    echo '<script language="javascript">alert("Datos incorrectos")</script>';
                    return view('inicioSesion');
                }
            } else {
                echo '<script language="javascript">alert("Revisa los datos")</script>';
                return view('inicioSesion');
            }
        } else {
            return view('inicioSesion');
        }
    }
    //muestra la vista de las estaciones
    public function estacion()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "estacion";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
            foreach ($_SESSION['estaciones'] as $index => $estacion) {
                if ($estacion["id_estacion"] == $_POST["btnEstacion"]) {
                    $nombreEstacion = $estacion["nombre_estacion"];
                    $datos['id_estacion'] = $estacion["id_estacion"];
                }
            }
            $ultimaConex = $usuario->ultimaConexionEstacion($_POST["btnEstacion"]);
            if ($ultimaConex != false) {
                $datos['ultimaConex'] = $ultimaConex;
            } else {
                $datos['ultimaConex'] = "error";
            }
            $datos['nombreEstacion'] = $nombreEstacion;
            return view('estacion', $datos);
        } else {
            return view('inicio');
        }
    }

    //muestra la vista de graficas (historicos y demas)
    //puede ir a vista rapida o personalizada (a.k.a custom)
    public function graficas()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "graficos";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
            $datos['tagsEstaciones'] = $usuario->obtenerTagsEstaciones();
            if (isset($_POST['btnGraf']) && $_POST['btnGraf'] == 'rapida') {
                return view('graficas', $datos);
            } else {
                return view('graficasCustom', $datos);
            }
        } else {
            return view('inicio');
        }
    }

    // //muestra la zona principal de alarmas
    public function alarmas()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "alarmas";
            if (isset($_SESSION['alarmas'])) {
                $datos['alarmas'] = $_SESSION['alarmas'];
            } else {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd']);
                //alarmas desde un mes
                $estaciones = $this->usuario->obtenerEstacionesUsuario();
                $datos['estaciones'] = $estaciones;
            }

            return view('alarmas', $datos);
        } else {
            return view('inicio');
        }
    }

    //muestra la zona de informes
    public function informes()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "infos";
            return view('informes');
        } else {
            return $this->inicioSesion();
        }
    }

    //muestra el estado de las conexiones con las estaciones
    public function comunicaciones()
    {
        $_SESSION['seccion'] = "coms";
        if (isset($_SESSION['nombre'])) {
            return view('comunicaciones');
        } else {
            return view('inicio');
        }
    }
}