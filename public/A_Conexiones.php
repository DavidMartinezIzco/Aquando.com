<?php

    require '../app/Database/Database.php';

    $opcion = $_GET['opcion'];
    $db = new Database();

    if($opcion == 'conex'){
            
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
                    if($dif->h <= 24){
                        $ultimasConexiones[$estacion][0]['estado'] = "error";
                    }
                    else {
                        $ultimasConexiones[$estacion][0]['estado'] = "correcto";
                    }
                }
            }
        }

        foreach ($ultimasConexiones as $estacion => $datos) {
            echo "<tr id='seccionEstacion' name=".$datos[0]['id_estacion']." onclick=obtenerCalidadTags(".$datos[0]['id_estacion'].")>";
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
                    if($dato == 'id_estacion'){
                        
                    }
                    if($dato == 'estado'){
                        if($valor == "correcto"){
                            echo "<td id='secEstado'><i class='fas fa-check'></i></td>"; 
                        }
                        else {
                            echo "<td id='secProblema'><i name='alerta' class='fas fa-exclamation-triangle'></i></td>";
                        }
                    }
                }
            echo "</tr>";
        }
    }
    if ($opcion == 'cali') {
        $id_estacion = $_GET['estacion'];

        $calidadTags = $db->calidadTagsEstacion($id_estacion);

        foreach ($calidadTags as $index => $tag) {
            echo "<tr>";
            foreach ($tag as $clave => $valor) {
                echo "<td>";
                if($clave == 'nombre_tag'){
                    echo $valor;
                }
                if($clave == 'calidad'){
                    echo "Calidad: " . $valor;
                }
                echo "</td>";
            }
            echo "</tr>";
        }

    }

    if($opcion == 'nom'){
        $id_estacion = $_GET['estacion'];

        $estacion = $db->obtenerNombreEstacion($id_estacion);
        echo($estacion[0]['nombre_estacion']);
    }

?>