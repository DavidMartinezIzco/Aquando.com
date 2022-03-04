<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Aquando</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <link rel="stylesheet" type="text/css" href="css/inicio.css">
    <link rel="stylesheet" type="text/css" href="css/principal.css">
    <!--cosillas de Fuentes-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <!--cosillas de Jquery para AJAX-->
    <script src="css/jquery.js"></script>
    <!--cosillas de font-awesome-->
    <link rel="stylesheet" type="text/css" href="css/fontawesome/css/all.css">
    <!--cosillas de bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous">
    </script>
    <!--demás archivos JavaScript-->
    <script src="css/mlat.js"></script>
    <script src="css/reloj.js"></script>
    <script src="css/ayuda.js"></script>
    <script src="css/desconectado.js"></script>
    <script src='css/sur.js'></script>
</head>
<?php
//comprueba el estado de la session
if (isset($_GET['log'])) {

    echo '<script language="javascript">';
    echo 'alert("Su sesión a caducado");';
    echo '</script>';
}
?>

<body>
    <header id="cabeceraPrincipal" class="p-1 text-white">
        <div style="width: 100%; padding-left: 1em">powered by
            <img id="logoPrincipal" src="../public/logo.png" onclick="pantalla()">
            <i style="margin-left: 1%" class="far fa-lightbulb" id="iconoAyuda" onclick="ayuda()"></i>
            <!-- zona ajustes -->
            <?php if (isset($_SESSION['seccion'])) {
                switch ($_SESSION['seccion']) {
                    case 'login':
                        echo "";
                        break;
                    case 'graficos':
                        echo "";
                        break;
                    case 'estacion':
                        echo "";
                        break;
                    case 'alarmas':
                        echo "";
                        break;
                    case 'infos':
                        echo "";
                        break;
                    case 'coms':
                        echo "";
                        break;
                    default:
                        echo '<i class="fas fa-tools" id="iconoAyuda" onclick="ajustes()"></i>';
                        break;
                }
            } ?>
            <!----zona secciones---->
            <?php
            if (isset($_SESSION['seccion'])) {
                echo "<div id='seccion' value=" . $_SESSION['seccion'] . ">";
                switch ($_SESSION['seccion']) {
                    case 'conexion':
                        echo "Pruebas > Conexión a base de datos";
                        break;

                    case 'login':
                        echo "Inicio de sesión";
                        break;

                    case 'tr':
                        echo "Pruebas > Tiempo real";
                        break;
                    case 'graficos':
                        echo "Gráficos";
                        break;
                    case 'estacion':
                        echo "Estación";
                        break;
                    case 'alarmas':
                        echo "Alarmas";
                        break;
                    case 'infos':
                        echo "Informes";
                        break;
                    case 'coms':
                        echo "Comunicaciones";
                        break;

                    default:
                        echo 'Inicio';
                        break;
                }
            }
            ?>
        </div>
        <div id="usuario">
            <?php if (isset($_SESSION['nombre']) && isset($_SESSION['nombre_cliente'])) {
                // hay que añador la empresa
                echo "" . $_SESSION['nombre'] . "<br>" . $_SESSION['nombre_cliente'];
            } else {
                echo "";
            }
            ?>
        </div>
        <div id="fechahora">
        </div>
        </div>
    </header>
    <!-- HEADER: MENU + HEROE SECTION -->
    <button class="btn me-2 btn-block" id="btnMenuIzq" title="ocultar/mostrar menú" onclick="abrirCerrar()">☰</button>
    <div class="d-flex flex-column flex-shrink-0 text-light container-fluid" value="abierto" id="menuIzq">
        <form action="<?php echo base_url(); ?>" id="contenidoMenuIzq1" method="POST">
            <button name="btnFuncion" onclick="carga()" class="btn me-2 btn-block" value="inicio" style="width: 100%; border-radius:0px; font-size:200%; color:white; <?php if (isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'inicio') {
                                                                                                                                                                            echo "background-color:rgb(1, 168, 184)";
                                                                                                                                                                        } ?>">
                <i class="fas fa-home"></i>Inicio
            </button>

        </form>
        <ul class="nav nav-pills flex-column" id="contenidoMenuIzq2">

            <!---estaciones--->
            <li class="Func">
                <button id="btnDesplegable" class="btn me-2 btn-block" name="btnFuncion" value="estaciones" style="font-size:100%;" onclick="desplegar(this.value)" <?php if (!isset($_SESSION["nombre"])) {
                                                                                                                                                                        echo "disabled";
                                                                                                                                                                    } ?>>
                    <i class="fas fa-broadcast-tower" style="margin-right:5%;font-size:80%"></i>Estaciones<i class="fas fa-caret-down"></i>
                </button>
            </li>
            <!---wrap estaciones--->
            <?php
            if (isset($_SESSION['estaciones'])) {
                $estaciones = $_SESSION['estaciones'];
                echo "<ul class='miniEstacion'>";
                foreach ($estaciones as $index => $estacion) {
                    echo
                    '<li>
                                <form action=' . base_url() . '/estacion method="POST">
                                    <button class="btn me-2 btn-block" name="btnEstacion" value="' . $estacion['id_estacion'] . '""
                                    style="padding:0.1em;width:100%; border-radius:0; color:white;">' . $estacion['nombre_estacion'] . '
                                    </button>
                                </form>
                            </li>';
                }
                echo "</ul>";
                echo "<hr class='miniEstacion'>";
            }
            ?>

            <!--demas funciones--->

            <!-- wrap de graficas -->
            <li class="Func">
                <button id="btnDesplegable" class="btn me-2 btn-block" name="btnGraf" value="grafs" style="font-size:100%;" onclick="desplegar(this.value)" <?php if (!isset($_SESSION["nombre"])) {
                                                                                                                                                                echo "disabled";
                                                                                                                                                            } ?>>
                    <i class="far fa-chart-bar" style="margin-right:5%"></i>Graficas <i class="fas fa-caret-down"></i>
                </button>
            </li>
            <ul class='miniEstacion'>
                <li>
                    <form action="<?php echo base_url(); ?>/graficas" method="POST">
                        <button onclick="carga()" class="btn me-2 btn-block" name="btnGraf" value="rapida" style="padding:1em;width:100%;border-radius:0;  color:white;">
                            Vista rápida <i class="fas fa-rocket"></i>
                        </button>
                    </form>
                </li>
                <li>
                    <form action="<?php echo base_url(); ?>/graficas" method="POST">
                        <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" value="custom" style="padding:1em;width:100%;border-radius:0;color:white;">
                            Vista Personalizada <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>
            </ul>
            <li class="Func">
                <form action="<?php echo base_url(); ?>/alarmas" method="POST">
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" <?php if (!isset($_SESSION["nombre"])) {
                                                                                                echo "disabled";
                                                                                            } ?> style="font-size:100%;padding:1em;width:100%;border-radius:0;color:white;">
                        <i class="fas fa-bell" style="margin-right:5%"></i>Alarmas
                    </button>
                </form>
            </li>
            <li class="Func">
                <form action="<?php echo base_url(); ?>/informes" method="POST">
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" <?php if (!isset($_SESSION["nombre"])) {
                                                                                                echo "disabled";
                                                                                            } ?> style="font-size:100%;padding:1em;width:100%;border-radius:0;  color:white;">
                        <i class="fas fa-file" style="margin-right:5%"></i>Informes
                    </button>
                </form>
            </li>
            <li class="Func">
                <form action="<?php echo base_url(); ?>/comunicaciones" method="POST">
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" <?php if (!isset($_SESSION["nombre"])) {
                                                                                                echo "disabled";
                                                                                            } ?> style="font-size:100%;padding:1em;width:100%;border-radius:0;  color:white;">
                        <i class="fas fa-satellite-dish" style="margin-right:5%"></i>Comunicaciones
                    </button>
                </form>
            </li>



            <!---LOGIN---->
            <li class="nav-item">
                <form action="<?php echo base_url(); ?>/inicioSesion" method="POST">
                    <button onclick="carga()" class="btn me-2" id="btnLogin" value="log-in" name="btnFuncion">
                        <?php
                        if (!isset($_SESSION['nombre'])) {
                            echo "Iniciar sesión";
                        } else {
                            echo "Cerrar sesión";
                        }
                        ?>
                    </button>
                </form>
            </li>
            <li id="conRest" style="text-align: center;" onclick="tiempoOpciones()">
                <h6>Tiempo de inactividad restante:</h6>
                <p id="restante"></p>
            </li>
            <li>
                <ul id="amplificador" style="font-size: 80%;">
                    <p> Opciones para modificar Inactividad
                        <i class="fas fa-stopwatch"></i>

                    </p>
                    <li>
                        <button class="btnTiempo" value="15" onclick="modificarInactividad(this.value)">
                            15 Minutos
                        </button>
                    </li>
                    <li>
                        <button class="btnTiempo" value="30" onclick="modificarInactividad(this.value)">
                            30 Minutos
                        </button>
                    </li>
                    <li>
                        <button class="btnTiempo" value="60" onclick="modificarInactividad(this.value)">
                            1 Hora
                        </button>
                    </li>
                </ul>
            </li>

    </div>
    <script>
        pantalla();
        window.onload = function() {
            setInterval(fechaYHora, 1000);
            setInterval(desvanecer, 1500);
        }
        <?php
        if (!isset($_SESSION['nombre'])) {
            echo 'document.getElementById("conRest").style.display ="none";';
        }
        ?>
        document.body.onkeyup = function(e) {
            if (e.keyCode == 9) {
                abrirCerrar();
            }
        }
    </script>
    <!--seccion flotante de ayuda--->
    <div id="conAyuda">
        <i class="fas fa-times" style="font-size: 150%" id="btnAyudaCerrar" onclick="ayuda()"></i>
        <h3>Ayuda:</h3>
        <p id="txtAyuda" value="1"></p>
        <button id="btnAyudaNext" name="ayudaNext" onclick="ayudaNext()">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>
    <!--render de las distintas vistas--->
    <div id="contenido" style="padding-top: 3.8%; padding-left:15%; color:lightgrey;" onclick="cerrarMenuEsp();">
        <?php $this->renderSection('content'); ?>
        <?php
        if (!isset($_SESSION['nombre']) && $_SESSION['mensajeDesc'] == true) {
            // echo '<img src="../public/logo.png"';
            echo "<h1 id='txtDesconectado' style='width:30%;margin:25% 40%;color:grey; transition:1.5s;opacity:60%'>Desconectado</h1>";
        }

        ?>
    </div>
</body>
<script>
    function cerrarMenuEsp() {
        cerrarMenu();
        $("#menuIzq").trigger('widthChange');
    }
</script>

</html>