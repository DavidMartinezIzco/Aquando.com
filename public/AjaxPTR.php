<?php
require "../app/Libraries/ZeusApi.php";
require '../app/Database/Database.php';

if(isset($_GET['canal'])){
    $ajaxTag = null;
    $ajaxCanal = $_GET['canal'];
    $estacion = $_GET['estacion'];
    $db = new Database();
    $ajaxTag = $db->obtenerInfoTag($ajaxCanal, $estacion);

    if(isset($ajaxTag)){
        echo "<br><hr><h3>datos del Canal: ". $ajaxCanal."</h3>";
        foreach ($ajaxTag as $key => $value) {
            if(!is_array($value)){
                echo  $key  .": " . "$value" . "<br>";
            }
            else {
                foreach ($value as $clave => $dato){
                    if(is_array($dato)){
                        print_r($dato);
                        echo "<br>";
                    }else {
                        echo  $clave  .": " . "$dato" . "<br>";
                    }
                    
                }
            }
        }
          
    }
}


if(!isset($_GET['canal']) && isset($_GET['estacion'])){
    $pass = $_GET['pass'];
    $pass = str_replace(' ', '+', $pass);
    $acc = $_GET['acc'];
    $pwd = $_GET['pwd'];
    $pwd = str_replace(' ', '', $pwd);
    $estacion = $_GET['estacion'];
    if($conexion = new Client("172.16.3.2", 3030, false, $acc, $pwd, $pass)){
        $ultimosDatos = $conexion->GetLastKnownValues($estacion);
        if(isset($ultimosDatos)){
            echo "<hr><h4 id='tcanales' name='tcanales' value=". $estacion .">CANALES DISPLONIBLES DE LA ESTACION: ".$estacion."</h4><table class='customScroll' style='padding:0.5em;max-width:100%;overflow:auto;display:inline-block;'><tr>";
                    foreach ($ultimosDatos as $index => $value) {
                        if ($index == "values") {
                            foreach ($value as $index => $data) {
                                if ($data != 0) {
                                    echo '<th>
                                    <button class="btn btn-outline-dark me-2 btn-block" name="btnCanal" id="btnCanal" onclick= mostrarTAG(this.value) value= '.$index.' style="margin: 0.5em;">
                                    <i class="far fa-chart-bar"></i> '.((int)$index).'
                                    </button>
                                    </th>';
                                }
                            }
                        }
                    }
                
                echo "</tr></table>";
                }
                else {
                    echo "error pepino";
                }

            }
            else {
                echo "error en auten";
            }
            
    }
    

    
    return false;  

?>