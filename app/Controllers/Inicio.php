<?php

namespace App\Controllers;


require(APPPATH . "Models/Usuario.php");



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

    //ejecución de arranque del proyecto
    public function index(){
        if (isset($_GET['log']) && $_GET['log'] == 'out') {
            $_SESSION['seccion'] = "login";
            return $this->inicioSesion();
        } else {
            $_SESSION['seccion'] = "inicio";
            if (isset($_SESSION['nombre'])) {
                $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['empresa']);
                $_SESSION['estaciones'] = $this->usuario->obtenerEstacionesUsuario();
               
                return view('principal');
            } else {
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
        $pwd = "";
        

        if(isset($_POST["txtNombre"]) && isset($_POST["txtContrasena"])){
                $nombre = $_POST["txtNombre"];
                $pwd = $_POST["txtContrasena"];
                $id = $_POST['selEmpresa'];

                $this->usuario = new Usuario($nombre, $pwd, $id);
                if($this->usuario->existeUsuario() == false){
                    echo '<script language="javascript">';
                    echo 'alert("Datos Incorrectos")';
                    echo '</script>';
                    return view('inicioSesion');
                }

                if($this->usuario->existeUsuario() == true) {
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['pwd'] = $pwd;

                    switch ($this->usuario->getCliente()) {
                        case 1:
                            $_SESSION['empresa'] = "Iturri Ederra";
                            break;
                        case 2:
                            $_SESSION['empresa'] = "Amescoa Alta";
                            break;
                        case 3:
                            $_SESSION['empresa'] = "Amescoa Baja";
                            break;
                        case 5:
                            $_SESSION['empresa'] = "Dateando";
                            break;
                        default:
                        $_SESSION['empresa'] = "Desconocida";
                            break;
                    }

                    $this->usuario->obtenerEstacionesUsuario();
                    return $this->index();
                }
                else {
                    echo '<script language="javascript">alert("Fallo la conexion")</script>';
                    return view('inicioSesion');
                }

        }
        else {
            return view('inicioSesion');
        }



    }

    //muestra la vista de las estaciones
    public function estacion(){
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "estacion";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['empresa']);
            $datosEstacion = $usuario->obtenerUltimaInfoEstacion($_POST['btnEstacion']);
            foreach ($_SESSION['estaciones'] as $index => $estacion) {
                if($estacion["id_estacion"] == $_POST["btnEstacion"]){
                    $nombreEstacion = $estacion["nombre_estacion"];
                }
            }
            $datos['datosEstacion'] = $datosEstacion;
            
            $datos['nombreEstacion'] = $nombreEstacion;

            return view('estacion', $datos);
        } else {
            return view('inicio');
        }
    }

    //muestra la vista de graficas (historicos y demas)
    public function graficas()
    {
        if (isset($_SESSION['nombre'])) {
            $_SESSION['seccion'] = "graficos";
            $usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['empresa']);
            $datos['tagsEstaciones'] = $usuario->obtenerTagsEstaciones();
            $estaciones = $_SESSION['estaciones'];
            $datosHistoricosEstacion = array();
            $datosHistoricosEstacion[] = $usuario->obtenerHistoricosEstacion($estaciones[0]['id_estacion'], null, null);
            if($datosHistoricosEstacion != false){
                $datos['historicos'] = $datosHistoricosEstacion;
                return view('graficas', $datos);
            }
            else {
                echo "<script>alert('error con los historicos');</script>";
                return view('principal');
            }

         }else {
            return view('inicio');
        }
    }

    // //muestra la zona principal de alarmas
    // public function alarmas()
    // {

    //     if (isset($_SESSION['nombre'])) {
    //         $_SESSION['seccion'] = "alarmas";
    //         if (isset($_SESSION['alarmas'])) {
    //             $datos['alarmas'] = $_SESSION['alarmas'];
    //         } else {
    //             //falta: cambiar a nueva BD
    //             $this->usuario = new Usuario($_SESSION['nombre'], $_SESSION['pwd'], $_SESSION['acc'], $_SESSION['pass']);
    //             //formato fecha: aaaa-mm-dd hh:mm:ss.000
    //             //alarmas desde principio de año
    //             $alarmas = $this->usuario->conseguirAlarmas(null, null, "2021-01-01 00:00:01.000");
    //             $datos['alarmas'] = $alarmas;
    //         }

    //         return view('alarmas', $datos);
    //     } else {
    //         return view('inicio');
    //     }
    // }

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
        //falta: cambiar a nueva BD
        $_SESSION['seccion'] = "coms";
        if (isset($_SESSION['nombre'])) {
            return view('comunicaciones');
        } else {
            return view('inicio');
        }
    }
}
