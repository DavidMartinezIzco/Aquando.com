<?php

    require '../app/Database/Database.php';

    $db = new Database();
    $nombre = $_GET['nombre'];
    $pwd = $_GET['pwd'];
    $emp = $_GET['emp'];


    $estaciones = $db->mostrarEstacionesCliente($nombre, $pwd);
    $ultimasConexiones = array();
    foreach ($estaciones as $estacion) {
        $ultimasConexiones[$estacion['nombre_estacion']] = $db->ultimaComunicacionEstacion($estacion['id_estacion']);
    }
    foreach ($ultimasConexiones as $estacion => $datos) {
        foreach ($datos[0] as $dato => $valor) {
            if($dato == 'valor_date'){
                $ultima = new Datetime($valor);
                $ahora = new DateTime("now");
                $dif = $ahora->diff($ultima);
                if($dif->h >= 24){
                    $ultimasConexiones[$estacion][0]['estado'] = "error";
                }
                else {
                    $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                }
            }
        }
    }

    foreach ($ultimasConexiones as $estacion => $datos) {
        echo "<tr id='seccionEstacion'>";
            foreach ($datos[0] as $dato => $valor) {
                if($dato == 'nombre_estacion'){
                    echo "<td id='secNombre'>";
                    echo "ESTACION: " . $valor;
                    echo "</td>";
                }
                if($dato == 'valor_date'){
                    echo "<td id='secUltima'>";
                    echo "ULTIMA CONEXION: " . $valor;
                    echo "</td>";
                }
                if($dato == 'nombre_tag'){
                    
                }
                if($dato == 'estado'){
                    if($valor == "correcto"){
                        echo "<td id='secEstado'><i class='fas fa-check'></i></td>"; 
                    }
                    else {
                        echo "<i class='fas fa-exclamation-triangle'></i>";
                    }
                }
            }
        echo "</tr>";
    }

?>