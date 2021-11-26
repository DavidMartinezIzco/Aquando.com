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
    <script src="css/mlat.js"></script>
    <script src="css/reloj.js"></script>
    <script src="css/desconectado.js"></script>
    <script src='css/sur.js'></script>
    <link rel="stylesheet" type="text/css" href="css/sur.css">
    
    <!--cosillas de Jquery para AJAX-->
    <script src="css/jquery.js"></script>

    <!--cosillas de font-awesome-->
    <link rel="stylesheet" type="text/css" href="css/fontawesome/css/all.css">
    <script defer src="css/js/all.js"></script>

    <!--cosillas de bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"
        integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous">
    </script>

</head>

<body>
    <header class="p-1 text-white" style="width: 100%;background-color:rgb(39,45,79);position:fixed; height:4.5em; z-index:1000;">
        <div style="width: 100%; padding-left: 1em">powered by
        <img src="../public/logo.png" style="height: 3.5em; margin-left: 1%;">
            <!----zona secciones---->
            <?php
                if(isset($_SESSION['seccion'])){
                    echo "<div id='seccion' value=".$_SESSION['seccion'].">";
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
                        default:
                            echo 'Inicio';
                            break;
                    }
                }
                echo "</div>"
            ?>
            <div id="usuario">
                <?php if(isset($_SESSION['nombre'])){
                    echo "Usuario: " . $_SESSION['nombre'] . "<br>Empresa: ";
                    }
                    else {
                        echo "Usuario:   Desconectado<br>Empresa: ";
                        }
                ?>
            </div>
            <div id="fechahora">
            </div>
        </div>
        

    </header>
    <!-- HEADER: MENU + HEROE SECTION -->
    <button class="btn me-2 btn-block" id="btnMenuIzq" title="ocultar/mostrar menú" onclick="abrirCerrar()">☰</button>
    <div class="d-flex flex-column flex-shrink-0 text-light container-fluid" id="menuIzq" style="width: 15%;">
        <form action="<?php echo base_url(); ?>" id="contenidoMenuIzq1" method="POST">
            <button name="btnFuncion" onclick="carga()" class="btn me-2 btn-block" value="inicio" style="width: 100%; border-radius:0px; font-size:2em; color:white; <?php if(isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'inicio'){echo "background-color:rgb(1, 168, 184)";}?>">
                Inicio
            </button>
            
        </form>
        <ul class="nav nav-pills flex-column" id="contenidoMenuIzq2">
        
        <!---estaciones--->
        <li class="Func">
            <button class="btn me-2 btn-block" name="btnFuncion" value="estaciones" onclick="desplegar(this.value)"<?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> 
            style="padding:1em;width:100%;border-radius:0;  color:white;"> 
            <i class="fas fa-broadcast-tower" style="margin-right:5%"></i>Estaciones
            </button>  
        </li>
        <!---wrap estaciones--->
        <?php 
            if(isset($_SESSION['estaciones'])){

                $estaciones = $_SESSION['estaciones'];
                echo "<ul class='miniEstacion'>";
                foreach ($estaciones as $index => $estacion) {
                    if($index != 0){
                        echo
                            '<li>
                                <form action='. base_url().'/estacion method="POST">
                                    <button class="btn me-2 btn-block" name="" value="'.$estacion.'" onclick="carga()"
                                    style="padding:0.1em;width:100%; border-radius:0; color:white;">'.$estacion.'
                                    </button>
                                </form>
                            </li>';
                        }
                    
                    }
                echo "</ul>";
                echo "<hr class='miniEstacion'>";
                }
        ?>

        <!--demas funciones--->
        <li class="Func">
                <form action="<?php echo base_url(); ?>/graficas" method="POST">
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" <?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> 
                    style="padding:1em;width:100%;border-radius:0;  color:white;"> 
                        <i class="far fa-chart-bar" style="margin-right:5%"></i>Graficas
                    </button>
                </form>
        </li>
        <li class="Func">
            <form action="<?php echo base_url(); ?>/alarmas" method="POST">
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" <?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> 
                    style="padding:1em;width:100%;border-radius:0;  color:white;"> 
                        <i class="fas fa-bell" style="margin-right:5%"></i>Alarmas
                    </button>
                </form>
        </li>
        <li class="Func">
                <form>
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" disabled
                    style="padding:1em;width:100%;border-radius:0;  color:white;"> 
                        <i class="fas fa-file" style="margin-right:5%"></i>Informes
                    </button>
                </form>
        </li>
        <li class="Func">
                <form>
                    <button onclick="carga()" class="btn me-2 btn-block" name="btnFuncion" disabled
                    style="padding:1em;width:100%;border-radius:0;  color:white;"> 
                        <i class="fas fa-satellite-dish" style="margin-right:5%"></i>Comunicaciones
                    </button>
                </form>
        </li>

        <!---debug--->
        <li class="nav-item">
            <button class="btn me-2 btn-block"  name="btnPruebas" value="pruebas" onclick="desplegar(this.value)" style="padding:1em;width:100%; border-radius:0; color:white;">
                Pruebas ⚙
            </button>    
        </li>
        <li class="expPruebas">
            <form action="<?php echo base_url(); ?>/pruebaBD" method="POST">
                <button class="btn me-2 btn-block"  name="btnFuncion"
                    <?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> style="padding:1em;width:100%; border-radius:0; color:white; <?php if(isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'conexion'){echo "background-color:rgb(1, 168, 184)";}?>">
                    <i class="far fa-chart-bar"></i>conexion de BD
                </button>
            </form>
        </li>
        <li class="expPruebas">
            <form action="<?php echo base_url(); ?>/pruebaTR" method="POST">
                <button class="btn me-2 btn-block"
                    <?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> name="btnFuncion"
                    style="padding:1em;width:100%;border-radius:0; color:white; <?php if(isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'tr'){echo "background-color:rgb(1, 168, 184)";}?>">
                    <i class="far fa-chart-bar"></i>Tiempo Real
                </button>
            </form>
        </li>
        <li class="expPruebas">
            <form action="<?php echo base_url(); ?>/pruebaGraficos" method="POST">
                <button class="btn me-2 btn-block" name="btnFuncion"
                    <?php if(!isset($_SESSION["nombre"])){echo "disabled";} ?> style="padding:1em;width:100%;border-radius:0;  color:white; <?php if(isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'graficos'){echo "background-color:rgb(1, 168, 184)";}?>">
                    <i class="far fa-chart-bar"></i>Graficos
                </button>
            </form>
        </li>
        <li class="expPruebas">
            <form action="<?php echo base_url(); ?>/pruebaAnalitico" method="POST">
                <button class="btn me-2 btn-block" disabled name="btnFuncion"
                style="padding:1em;width:100%;border-radius:0;  color:white; <?php if(isset($_SESSION['seccion']) && $_SESSION['seccion'] == 'analitico'){echo "background-color:rgb(1, 168, 184)";}?>">
                    <i class="far fa-chart-bar"></i>Analitico
                </button>
            </form>
        </li>
        
        <hr>
        <!---LOGIN---->
        <li class="nav-item">
            <form action="<?php echo base_url(); ?>/inicioSesion" method="POST" >
                <button onclick="carga()" class="btn me-2" id="btnLogin" value="log-in" name="btnFuncion">
                    <?php 
                        if(!isset($_SESSION['nombre'])) {
                            echo "Iniciar sesión";
                        }
                        else {
                            echo "Cerrar sesión";
                        }
                    ?>
                </button>
            </form>
        </li>
        <li id="conRest" style="text-align: center;" onclick="tiempoOpciones()">
            <h6>Inactividad restante:</h6>
            <p id="restante"></p>
        </li>
        <li>
            <ul id="amplificador">
            <p> Opciones para modificar Inactividad</p>
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
        window.onload = function() {
            setInterval(fechaYHora, 1000);
            setInterval(desvanecer, 1500);        
        }
        
        <?php 
        if(!isset($_SESSION['nombre'])){
            echo 'document.getElementById("conRest").style.display ="none";';
        }
        ?>

    </script>
    

    <div id="contenido" style="padding-top: 5em; padding-left:15%; color:lightgrey;" onclick="cerrarMenu();">
        <?php $this->renderSection('content');?>
        <?php
            if(!isset($_SESSION['nombre'])){
                echo "<h1 id='txtDesconectado' style='width:30%;margin:25% 40%;color:grey; transition:1.5s;opacity:60%'>Desconectado</h1>";
            }
        ?>
    </div>

</body>



</html>